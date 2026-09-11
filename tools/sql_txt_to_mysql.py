#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Konversi sql.txt -> dump MySQL (sql_mysql.sql).

sql.txt bukan file SQL, melainkan hasil "salin-tempel" tabel HTML dari Adminer 6
(PostgreSQL, host 192.168.22.81:5792, db rsud_malangbong, schema public).
Isinya hanya hasil query:

    SELECT 'public.<tabel>' AS nama_tabel, t.* FROM public.<tabel> t LIMIT 100;

sehingga setiap tabel maksimal 100 baris dan TIDAK ADA DDL sama sekali.

Script ini membangun ulang:
  1. CREATE TABLE  -> nama kolom dari baris header hasil query,
                      tipe kolom diperkirakan dari isi datanya.
  2. INSERT INTO   -> seluruh baris data yang ada di sql.txt.

Yang sengaja dibuang/dihindari supaya import tidak error:
  - semua teks UI Adminer (SQL, QUERY PLAN, "Tidak ada baris.", dst.)
  - tipe PostgreSQL (uuid, text[], timestamptz, bytea, numeric, serial)
  - PRIMARY KEY / FOREIGN KEY / UNIQUE / INDEX / NOT NULL / DEFAULT
    (informasinya tidak ada di sql.txt -> semua kolom dibuat NULL-able)
  - CREATE DATABASE / USE (ditulis sebagai komentar agar file bisa di-import
    ke database apa pun tanpa perlu hak CREATE DATABASE)

Pemakaian:  python3 tools/sql_txt_to_mysql.py [sql.txt] [sql_mysql.sql]
"""

import collections
import datetime
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC_DEFAULT = os.path.join(ROOT, "sql.txt")
OUT_DEFAULT = os.path.join(ROOT, "sql_mysql.sql")

DB_NAME = "rsud_malangbong"
ROWS_PER_INSERT = 100          # maksimum baris per satu statement INSERT
MAX_INSERT_BYTES = 512 * 1024  # maksimum ukuran satu statement INSERT
MAX_ROW_BYTES = 60000          # batas 65535 byte per baris tabel (MySQL)

RE_SELECT = re.compile(
    r"^SELECT 'public\.(?P<name>.*?)' AS nama_tabel, t\.\* "
    r"FROM public\.\"?(?P<tbl>.*?)\"? t(?: LIMIT 100;?)?$"
)
RE_INT = re.compile(r"^-?\d+$")
RE_DEC = re.compile(r"^-?\d+\.\d+$")
RE_DATE = re.compile(r"^\d{4}-\d{2}-\d{2}$")
RE_DATETIME = re.compile(r"^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}(\.\d+)?$")
RE_TIME = re.compile(r"^-?\d{1,3}:\d{2}:\d{2}(\.\d+)?$")

NULL_TOKEN = "NULL"            # Adminer menampilkan NULL sebagai teks "NULL"
INT_MAX = 2 ** 31 - 1
BIGINT_MAX = 2 ** 63 - 1
VARCHAR_BUCKETS = (64, 128, 255, 512, 1024, 2048, 4096, 8192)


# --------------------------------------------------------------------------
# util
# --------------------------------------------------------------------------
def utf8len(text):
    return len(text.encode("utf-8", errors="replace"))


def bucket_for(nbytes):
    """Bucket VARCHAR terkecil yang masih menampung panjang data."""
    for limit in VARCHAR_BUCKETS:
        if nbytes <= limit:
            return limit
    return None


def q(ident):
    """Backtick-kan identifier (aman untuk nama seperti #_jaspel_obat)."""
    return "`" + ident.replace("`", "``") + "`"


TYPE_BYTES = {
    "INT": 4, "BIGINT": 8, "DOUBLE": 8, "DATE": 3, "TIME": 3,
    "DATETIME": 8, "DATETIME(6)": 8, "TEXT": 12, "MEDIUMTEXT": 12,
    "LONGTEXT": 12,
}


def type_bytes(sqltype):
    if sqltype in TYPE_BYTES:
        return TYPE_BYTES[sqltype]
    if sqltype.startswith("VARCHAR"):
        return int(sqltype[8:-1]) * 4          # utf8mb4 -> 4 byte/karakter
    if sqltype.startswith("DECIMAL"):
        return 16
    return 8


def base_type(sqltype):
    """Bentuk umum tipe, dipakai untuk mewarisi tipe antar tabel."""
    if sqltype is None:
        return None
    if sqltype.startswith("VARCHAR"):
        return "VARCHAR(255)"
    if sqltype in ("TEXT", "MEDIUMTEXT", "LONGTEXT"):
        return "TEXT"
    if sqltype.startswith("DECIMAL") or sqltype == "DOUBLE":
        return "DECIMAL(65,30)"
    return sqltype


# --------------------------------------------------------------------------
# 1. baca sql.txt
# --------------------------------------------------------------------------
def parse(path):
    """Kembalikan (order, tables): daftar tabel + kolom + barisnya."""
    tables = collections.OrderedDict()
    order = []
    cur = None
    with open(path, "r", encoding="utf-8", errors="replace") as fh:
        for line in fh:
            line = line.rstrip("\n").rstrip("\r")
            if line.startswith("SELECT "):
                m = RE_SELECT.match(line)
                # daftar tabel di bagian akhir file berakhiran ';' dan tidak
                # punya baris data -> dilewati
                if m and not line.endswith(";"):
                    name = m.group("name")
                    if name in tables:
                        cur = None
                    else:
                        cur = tables[name] = {"cols": None, "rows": []}
                        order.append(name)
                else:
                    cur = None
                continue
            if cur is None:
                continue
            if line.startswith("nama_tabel\t") and cur["cols"] is None:
                cur["cols"] = line.split("\t")[1:]
            elif line.startswith("public."):
                cur["rows"].append(line)
    return order, tables


# --------------------------------------------------------------------------
# 2. perkiraan tipe kolom
# --------------------------------------------------------------------------
def infer(values):
    """Perkirakan tipe MySQL dari daftar nilai (string) satu kolom."""
    nonnull = [v for v in values if v != NULL_TOKEN]
    if not nonnull:
        return None                     # semua NULL -> belum bisa ditentukan
    if all(v == "" for v in nonnull):
        return "VARCHAR(255)"

    # bilangan bulat; angka berkode seperti '0001' (nol di depan) tetap teks
    if all(RE_INT.match(v) for v in nonnull) and not any(
        len(v.lstrip("-")) > 1 and v.lstrip("-").startswith("0") for v in nonnull
    ):
        biggest = max(abs(int(v)) for v in nonnull)
        if biggest <= INT_MAX:
            return "INT"
        if biggest <= BIGINT_MAX:
            return "BIGINT"
        digits = max(len(v.lstrip("-")) for v in nonnull)
        return "DECIMAL(%d,0)" % min(65, digits)

    # bilangan pecahan
    if all(RE_INT.match(v) or RE_DEC.match(v) for v in nonnull):
        scale = 0
        ints = 1
        for v in nonnull:
            v = v.lstrip("-")
            if "." in v:
                a, b = v.split(".", 1)
                scale = max(scale, len(b.rstrip()))
                ints = max(ints, len(a))
            else:
                ints = max(ints, len(v))
        if scale == 0:
            return "DECIMAL(%d,0)" % min(65, ints)
        if ints + scale <= 64:
            return "DECIMAL(%d,%d)" % (ints + scale + 1, scale)
        return "DOUBLE"

    if all(RE_DATETIME.match(v) for v in nonnull):
        return "DATETIME(6)" if any("." in v for v in nonnull) else "DATETIME"
    if all(RE_DATE.match(v) for v in nonnull):
        return "DATE"
    if all(RE_TIME.match(v) for v in nonnull):
        return "TIME"

    nbytes = max(utf8len(v) for v in nonnull)
    size = bucket_for(nbytes)
    if size is not None:
        return "VARCHAR(%d)" % size
    if nbytes <= 65535:
        return "TEXT"
    if nbytes <= 16777215:
        return "MEDIUMTEXT"
    return "LONGTEXT"


def dedupe_columns(cols, table, notes):
    """Nama kolom unik (sql.txt bisa memuat nama kolom kembar)."""
    out = []
    seen = collections.Counter()
    for col in cols:
        name = col.strip()
        if not name:
            name = "kolom_tanpa_nama"
        if len(name) > 64:
            notes.append("%s: nama kolom '%s' dipotong jadi 64 karakter" % (table, name))
            name = name[:64]
        seen[name] += 1
        if seen[name] > 1:
            new = "%s_%d" % (name, seen[name])
            notes.append(
                "%s: nama kolom '%s' muncul %dx -> kemunculan ke-%d dinamai '%s'"
                % (table, name, seen[name], seen[name], new)
            )
            name = new
        out.append(name)
    return out


# --------------------------------------------------------------------------
# 3. penulisan nilai
# --------------------------------------------------------------------------
def literal(value, sqltype):
    if value == NULL_TOKEN:
        return "NULL"
    if (sqltype.startswith("INT") or sqltype in ("BIGINT", "DOUBLE")
            or sqltype.startswith("DECIMAL")):
        return value
    escaped = (
        value.replace("\\", "\\\\")
        .replace("'", "\\'")
        .replace("\0", "\\0")
        .replace("\n", "\\n")
        .replace("\r", "\\r")
        .replace("\x1a", "\\Z")
    )
    return "'" + escaped + "'"


def main():
    src = sys.argv[1] if len(sys.argv) > 1 else SRC_DEFAULT
    out = sys.argv[2] if len(sys.argv) > 2 else OUT_DEFAULT
    notes = []

    order, tables = parse(src)

    # --- periksa hasil parsing -------------------------------------------
    data_tables, empty_tables, bad_rows, total_rows = [], [], 0, 0
    for name in order:
        info = tables[name]
        if info["cols"] is None:
            if info["rows"]:
                notes.append("%s: ada baris data tanpa baris header kolom" % name)
                bad_rows += len(info["rows"])
            empty_tables.append(name)
            info["rows"] = []
            continue
        ncols = len(info["cols"])
        keep = []
        for line in info["rows"]:
            parts = line.split("\t")
            if parts[0] != "public." + name or len(parts) - 1 != ncols:
                bad_rows += 1
                notes.append("%s: baris dengan jumlah kolom tidak cocok dibuang" % name)
                continue
            keep.append(parts[1:])
            total_rows += 1
        info["rows"] = keep
        if keep:
            data_tables.append(name)
        else:
            empty_tables.append(name)

    # --- kolom + tipe per tabel ------------------------------------------
    unknown_cols = collections.Counter()
    type_by_colname = collections.defaultdict(collections.Counter)
    table_meta = collections.OrderedDict()

    for name in data_tables:
        info = tables[name]
        cols = dedupe_columns(info["cols"], name, notes)
        types = []
        for idx, col in enumerate(cols):
            values = [row[idx] for row in info["rows"]]
            t = infer(values)
            if t is None:
                unknown_cols[col.lower()] += 1
            else:
                type_by_colname[col.lower()][base_type(t)] += 1
            types.append(t)
        table_meta[name] = {"cols": cols, "types": types, "rows": info["rows"]}

    # kolom yang seluruhnya NULL -> warisi tipe dari kolom bernama sama pada
    # tabel lain (penamaan kolom SIMRS ini konsisten), kalau tidak ada ->
    # VARCHAR(255).
    inherited = 0
    for meta in table_meta.values():
        for idx, col in enumerate(meta["cols"]):
            if meta["types"][idx] is None:
                known = type_by_colname.get(col.lower())
                if known:
                    meta["types"][idx] = known.most_common(1)[0][0]
                    inherited += 1
                else:
                    meta["types"][idx] = "VARCHAR(255)"

    # --- jaring pengaman batas ukuran baris (65535 byte) ------------------
    for name, meta in table_meta.items():
        types = meta["types"]
        rows = meta["rows"]
        need = [max((utf8len(r[i]) for r in rows if r[i] != NULL_TOKEN), default=0)
                for i in range(len(types))]
        total = sum(type_bytes(t) for t in types)

        # 1) kecilkan VARCHAR yang kepanjangan (tanpa mengorbankan data)
        while total > MAX_ROW_BYTES:
            best = None
            for i, t in enumerate(types):
                if not t.startswith("VARCHAR"):
                    continue
                cur = int(t[8:-1])
                minb = bucket_for(need[i])
                if minb is None:
                    continue
                if minb < cur:
                    gain = (cur - minb) * 4
                    if best is None or gain > best[0]:
                        best = (gain, i, minb)
            if best is None:
                break
            gain, i, minb = best
            types[i] = "VARCHAR(%d)" % minb
            total -= gain

        # 2) kalau masih lebih besar, ubah VARCHAR terbesar -> TEXT
        while total > MAX_ROW_BYTES:
            cand = [(type_bytes(t), i) for i, t in enumerate(types)
                    if t.startswith("VARCHAR")]
            if not cand:
                break
            _, i = max(cand)
            old = types[i]
            types[i] = "TEXT" if need[i] <= 65535 else "MEDIUMTEXT"
            total += type_bytes(types[i]) - type_bytes(old)
            notes.append("%s: kolom '%s' (%s) diubah jadi %s karena baris tabel "
                         "melewati batas 65535 byte" % (name, meta["cols"][i], old, types[i]))

    # --- tulis file -------------------------------------------------------
    now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M")
    n_data = len(table_meta)
    n_empty = len(empty_tables)
    n_tables = len(order)
    sql = []
    add = sql.append

    add("-- =====================================================================")
    add("--  DUMP MySQL hasil konversi sql.txt (Adminer 6 / PostgreSQL)")
    add("--  Sumber     : 192.168.22.81:5792 -> db rsud_malangbong -> schema public")
    add("--  Dibuat     : %s" % now)
    add("--  Isi        : %d CREATE TABLE + INSERT  (%d baris data)"
        % (n_data, total_rows))
    add("--               %d CREATE TABLE kosong (tabel tanpa data di sql.txt)"
        % n_empty)
    add("--  Cara pakai : mysql -u root -p nama_database < sql_mysql.sql")
    add("--               atau import lewat phpMyAdmin / Adminer / DBeaver.")
    add("--  ---------------------------------------------------------------------")
    add("--  CATATAN PENTING")
    add("--  1. sql.txt tidak memuat DDL asli. Nama kolom diambil dari baris header")
    add("--     hasil query, TIPE kolom diperkirakan dari isi datanya, sehingga tipe")
    add("--     bisa berbeda dari skema PostgreSQL aslinya.")
    add("--  2. Semua query di sql.txt memakai LIMIT 100, jadi setiap tabel hanya")
    add("--     memuat maksimal 100 baris (contoh data, bukan isi penuh database).")
    add("--  3. Tidak ada PRIMARY KEY / FOREIGN KEY / UNIQUE / INDEX / NOT NULL /")
    add("--     DEFAULT karena informasinya tidak ada di sql.txt. Semua kolom")
    add("--     dibuat NULL-able supaya INSERT tidak pernah gagal.")
    add("--  4. Nilai NULL tetap NULL, sel kosong tetap string kosong ''.")
    add("--  5. Tabel yang di sql.txt mengembalikan 0 baris tidak menyisakan info")
    add("--     kolom apa pun, sehingga dibuat sebagai tabel placeholder berisi")
    add("--     kolom `id` (lihat bagian akhir file).")
    add("--  6. Semua tabel memakai engine InnoDB + utf8mb4 sehingga aman di MySQL")
    add("--     5.7 / 8.x maupun MariaDB, termasuk nama tabel berawalan '#'")
    add("--     (contoh: #_jaspel_obat).")
    add("--  7. Setiap tabel didahului DROP TABLE IF EXISTS supaya file aman")
    add("--     di-import ulang berkali-kali.")
    add("-- =====================================================================")
    add("")
    add("-- Aktifkan dua baris berikut bila ingin membuat database baru:")
    add("-- CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET utf8mb4 "
        "COLLATE utf8mb4_unicode_ci;" % q(DB_NAME))
    add("-- USE %s;" % q(DB_NAME))
    add("")
    add("/*!40101 SET NAMES utf8mb4 */;")
    add("/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;")
    add("/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;")
    add("")
    add("")

    def emit_create(name, body):
        add("DROP TABLE IF EXISTS %s;" % q(name))
        add("CREATE TABLE %s (" % q(name))
        add(",\n".join("  " + line for line in body))
        add(") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;")
        add("")

    def column_lines(cols, types):
        """Definisi kolom; TEXT tidak perlu DEFAULT NULL (default-nya NULL)."""
        lines = []
        for col, typ in zip(cols, types):
            if typ in ("TEXT", "MEDIUMTEXT", "LONGTEXT"):
                lines.append("%s %s" % (q(col), typ))
            else:
                lines.append("%s %s DEFAULT NULL" % (q(col), typ))
        return lines

    def emit_inserts(name, cols, types, rows):
        collist = "(%s)" % ", ".join(q(c) for c in cols)
        prefix = "INSERT INTO %s %s VALUES\n" % (q(name), collist)
        batch, batch_bytes = [], len(prefix)

        def flush():
            if batch:
                add(prefix + ",\n".join(batch) + ";")
                add("")
                del batch[:]

        for row in rows:
            tuples = "(" + ", ".join(literal(v, t) for v, t in zip(row, types)) + ")"
            if batch and (len(batch) >= ROWS_PER_INSERT
                          or batch_bytes + len(tuples) > MAX_INSERT_BYTES):
                flush()
                batch_bytes = len(prefix)
            batch.append(tuples)
            batch_bytes += len(tuples) + 2
        flush()

    n_insert = 0
    for name in data_tables:
        meta = table_meta[name]
        add("-- ===== public.%s  (%d baris, %d kolom) ====="
            % (name, len(meta["rows"]), len(meta["cols"])))
        emit_create(name, column_lines(meta["cols"], meta["types"]))
        if meta["rows"]:
            emit_inserts(name, meta["cols"], meta["types"], meta["rows"])
            n_insert += 1

    add("-- =====================================================================")
    add("--  TABEL TANPA DATA DI sql.txt (%d tabel)" % n_empty)
    add("--  Query-nya mengembalikan 0 baris sehingga nama/tipe kolomnya tidak")
    add("--  diketahui sama sekali. Tabel tetap dibuat agar tidak ada tabel yang")
    add("--  terlewat; isinya hanya kolom placeholder `id` dan silakan disesuaikan")
    add("--  (ALTER TABLE) bila akan dipakai aplikasi.")
    add("-- =====================================================================")
    add("")
    for name in empty_tables:
        add("-- public.%s (0 baris di sql.txt)" % name)
        emit_create(name, ["%s BIGINT NOT NULL AUTO_INCREMENT" % q("id"),
                           "PRIMARY KEY (%s)" % q("id")])
    add("-- Total tabel dibuat: %d" % n_tables)

    add("/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;")
    add("/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;")
    add("-- Selesai.")
    add("")

    with open(out, "w", encoding="utf-8", newline="\n") as fh:
        fh.write("\n".join(sql))

    # --- ringkasan --------------------------------------------------------
    print("Sumber             : %s" % src)
    print("Hasil              : %s (%.1f MB)" % (out, os.path.getsize(out) / 1048576.0))
    print("Tabel total        : %d" % n_tables)
    print("  - ada data       : %d (%d baris, %d statement INSERT)"
          % (n_data, total_rows, n_insert))
    print("  - tanpa data     : %d" % n_empty)
    print("Baris dibuang      : %d" % bad_rows)
    print("Kolom seluruh NULL : %d  (diwarisi dari tabel lain: %d)"
          % (sum(unknown_cols.values()), inherited))
    print("Kolom tanpa nama   : %d" % 0)
    if notes:
        print("Catatan (%d):" % len(notes))
        for note in notes[:40]:
            print("  * " + note)

    note_path = os.path.join(os.path.dirname(out), "tools", "konversi_catatan.txt")
    with open(note_path, "w", encoding="utf-8") as fh:
        fh.write("Catatan konversi sql.txt -> sql_mysql.sql\n")
        fh.write("Dibuat: %s\n\n" % now)
        for note in notes:
            fh.write("- %s\n" % note)
        fh.write("\nKolom yang seluruhnya NULL (tipe diwarisi/di-default-kan):\n")
        for col, cnt in sorted(unknown_cols.items()):
            fh.write("- %s: %d kolom\n" % (col, cnt))
    print("Catatan detail     : %s" % note_path)


if __name__ == "__main__":
    main()
