#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Pembangun ANALISA_SEMUA.md — analisa lengkap seluruh folder & berkas repositori SIMRS.

Bagian utama :
  1. Ringkasan repositori
  2. Peta & pohon direktori
  3. Statistik bahasa & tipe berkas
  4. Analisa per folder (backend, frontend-v2, viewer, kiosk, e-reservasi, eis,
     socket-server, load-test, tools, berkas root)
  5. Keterkaitan antar aplikasi
  6. Berkas besar, duplikasi, kebersihan repositori
  7. Temuan & rekomendasi
  Lampiran A: daftar SELURUH folder (1.660 direktori)
  Lampiran B: daftar SELURUH berkas (11.6xx berkas)
"""

import collections
import os
import time

ROOT = "/home/user/simrs"
OUT = os.path.join(ROOT, "tools", "analisa", "out")
REPORT = os.path.join(ROOT, "ANALISA_SEMUA.md")

SKIP_DIRS = {".git", "node_modules", "vendor", ".venv", "dist", "build", ".next",
             "coverage", "__pycache__", ".cache", "tmp"}

LANG_EXT = {
    ".php": "PHP", ".vue": "Vue", ".ts": "TypeScript", ".js": "JavaScript",
    ".scss": "SCSS", ".css": "CSS", ".html": "HTML", ".md": "Markdown",
    ".json": "JSON", ".sql": "SQL", ".txt": "Teks", ".xml": "XML",
    ".yml": "YAML", ".yaml": "YAML", ".sh": "Shell", ".py": "Python",
    ".mjs": "JavaScript", ".cjs": "JavaScript",
}
TEXT_EXT = set(LANG_EXT) | {".example", ".editorconfig", ".prettierrc", ".eslintrc",
                            ".gitignore", ".gitattributes", ".browserslistrc",
                            ".stylelintignore", ".dockerignore", ".hintrc", ".m3u8",
                            ".tsv", ".csv", ".conf", ".ini", ".lock", ".dat"}


def num(n):
    return f"{n:,}".replace(",", ".")


def human(nbytes):
    for unit in ("B", "KB", "MB", "GB"):
        if nbytes < 1024 or unit == "GB":
            return ("%d B" % nbytes) if unit == "B" else ("%.1f %s" % (nbytes, unit))
        nbytes /= 1024.0


def is_text(path):
    try:
        with open(path, "rb") as fh:
            return b"\x00" not in fh.read(2048)
    except OSError:
        return False


def lines_of(path):
    try:
        with open(path, "rb") as fh:
            return fh.read(4 * 1024 * 1024).count(b"\n") + 1
    except OSError:
        return 0


def walk(base):
    out = []
    for dirpath, dirnames, filenames in os.walk(base):
        rel = os.path.relpath(dirpath, ROOT)
        parts = [] if rel == "." else rel.split(os.sep)
        if any(p in SKIP_DIRS for p in parts):
            dirnames[:] = []
            continue
        dirnames[:] = sorted(d for d in dirnames if d not in SKIP_DIRS)
        for fn in sorted(filenames):
            out.append(os.path.join(dirpath, fn))
    return out


def stats(paths):
    total = 0
    ext = collections.Counter()
    lines = collections.Counter()
    langs = collections.Counter()
    for p in paths:
        try:
            total += os.path.getsize(p)
        except OSError:
            continue
        e = os.path.splitext(p)[1].lower()
        ext[e or "(tanpa ekstensi)"] += 1
        lang = LANG_EXT.get(e)
        if lang:
            langs[lang] += 1
            lines[lang] += lines_of(p)
    return {"files": len(paths), "bytes": total, "ext": ext, "lines": lines, "langs": langs}


def dir_summary(path):
    n = b = 0
    for dirpath, dirnames, filenames in os.walk(path):
        rel = os.path.relpath(dirpath, ROOT)
        parts = [] if rel == "." else rel.split(os.sep)
        if any(p in SKIP_DIRS for p in parts):
            dirnames[:] = []
            continue
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]
        for fn in filenames:
            try:
                b += os.path.getsize(os.path.join(dirpath, fn))
            except OSError:
                pass
            n += 1
    return n, b


def tree(base, max_depth=2, max_files=8, key_files=None):
    """Pohon direktori ringkas: folder + berkas penting saja."""
    lines = []
    key_files = key_files or set()

    def rec(path, prefix, depth):
        if depth > max_depth:
            return
        try:
            entries = sorted(os.listdir(path))
        except OSError:
            return
        entries = [e for e in entries if e not in SKIP_DIRS]
        dirs = [e for e in entries if os.path.isdir(os.path.join(path, e))]
        files = [e for e in entries if os.path.isfile(os.path.join(path, e))]
        shown = [f for f in files if f in key_files] if depth == 1 else []
        if depth > 1:
            shown = []
        # tampilkan berkas konfigurasi penting pada tiap level
        shown = [f for f in files if f in key_files]
        extra = len(files) - len(shown)
        children = len(dirs) + len(shown) + (1 if extra > 0 else 0)
        idx = 0
        for d in dirs:
            idx += 1
            last = idx == children
            sub = os.path.join(path, d)
            n, b = dir_summary(sub)
            lines.append("%s%s %s/  (%s berkas, %s)" % (
                prefix, "└──" if last else "├──", d, num(n), human(b)))
            rec(sub, prefix + ("    " if last else "│   "), depth + 1)
        for f in shown:
            idx += 1
            last = idx == children
            try:
                sz = human(os.path.getsize(os.path.join(path, f)))
            except OSError:
                sz = "?"
            lines.append("%s%s %s  (%s)" % (prefix, "└──" if last else "├──", f, sz))
        if extra > 0:
            lines.append("%s└── … %d berkas lain" % (prefix, extra))

    rec(base, "", 1)
    return lines


def list_modules(path, pattern="*"):
    """Kembalikan daftar (nama, jumlah berkas) untuk subfolder tingkat-1."""
    res = []
    if not os.path.isdir(path):
        return res
    for d in sorted(os.listdir(path)):
        sub = os.path.join(path, d)
        if not os.path.isdir(sub) or d in SKIP_DIRS:
            continue
        n = len(walk(sub))
        res.append((d, n))
    return res


def module_table(title, items, label="Modul"):
    out = []
    if not items:
        return out
    out.append("**%s:**" % title)
    out.append("")
    out.append("| %s | Berkas |" % label)
    out.append("|---|---|")
    for name, n in items:
        out.append("| `%s` | %d |" % (name, n))
    out.append("")
    return out


# --------------------------------------------------------------------------
# keterangan tiap folder (hasil pembacaan langsung kode & konfigurasi)
# --------------------------------------------------------------------------
FOLDER_INFO = {
    "backend": {
        "judul": "`backend/` — API utama (Laravel 8)",
        "ringkas": "Otak aplikasi SIMRS: seluruh endpoint API, logika bisnis, akses database, "
                   "cetak dokumen, integrasi eksternal, dan cron job.",
        "teknologi": ["PHP ^7.3|^8.0", "Laravel ^8.12", "Eloquent + Query Builder + raw SQL",
                      "JWT (lcobucci/jwt, namshi/jose)", "MongoDB (jenssegers/mongodb)",
                      "DomPDF & laravel-pdfmerger", "Maatwebsite Excel", "QR Code",
                      "Laravel Mix (aset lama)"],
        "masuk": ["`artisan serve` / `serve.sh` → port 8000",
                  "`public/index.php` (front controller)",
                  "`routes/web.php` (4.453 baris, prefix `service`, middleware `jwt.auth`)",
                  "`routes/api.php` (32 baris)"],
        "catatan": [
            "269 berkas controller dalam 40 folder modul (`app/Http/Controllers/<Modul>`).",
            "373 berkas model: 185 di `Models/Master`, 177 di `Models/Transaksi`, 10 di `Models/Standar`.",
            "Skema database tidak dikelola lewat migration aplikasi (hanya 3 migration bawaan "
            "Laravel) — tabel dibuat langsung di database.",
            "8 command cron (`app/Console/Commands`): BOR, MKKO (2 berkas), BPJS Klaim, "
            "Akomodasi, Indeks MongoDB, PostSaldoProdukDetail, Saldo Awal Harian.",
            "Ekspor laporan Excel di `app/Exports` (Invoice COB, Invoice Ranap, Laporan Harian, "
            "Piutang Pasien) dan job antrian `app/Jobs/PostSaldoProdukDetail.php`.",
            "Trait bersama `app/Traits/{JsonRespon, PelayananPasienTrait, Valet}.php` dan "
            "service `app/Services/BridgingBPDService.php`.",
            "Aset publik terbesar: `public/img` 5,1 MB, `public/fonts` 3 MB, "
            "`public/kyc-satset` 3,1 MB (termasuk `vendor` yang ikut ter-commit), "
            "`public/qz` 1,8 MB (library cetak QZ Tray).",
            "`resources/views/report/**` berisi ±200 template Blade cetak laporan per modul.",
            "`public/info.php` ikut ter-commit (`phpinfo()`) — sebaiknya dihapus di produksi.",
            "`coverage.xml` (2 MB) = hasil coverage PHPUnit yang tersimpan di repo.",
            "Kredensial pihak ketiga masih tertulis di kode (mis. `BridgingBPJSCtrl.php` "
            "`$secretKey = \"0kS55036F0\"`, `TilakaCtrl.php` client id/secret) — "
            "sebaiknya dipindah ke `.env`.",
            "Data statistik database lengkap (537 tabel, 7.657 JOIN, dsb.) ada di "
            "`LAPORAN_ANALISA_DATABASE.md`.",
        ],
    },
    "frontend-v2": {
        "judul": "`frontend-v2/` — Aplikasi utama petugas (Vue 3 + Vite)",
        "ringkas": "Aplikasi web yang dipakai petugas rumah sakit: registrasi, EMR, farmasi, "
                   "kasir, akuntansi, laporan, sampai master data. Berbasis template Vuero.",
        "teknologi": ["Vue 3.2.33 + TypeScript", "Vite 3.1.3", "Piñia-style store lokal",
                      "Axios (composable `useApi`)", "Bulma/SCSS", "PrimeVue",
                      "Cypress (e2e)", "PWA (service worker)"],
        "masuk": ["`src/main.ts` → `src/app.ts`", "`src/router.ts` (rute halaman)",
                  "`src/pages/module/**` (aplikasi SIMRS)",
                  "`npm run dev` → `vite --host 0.0.0.0 --port 2222`"],
        "catatan": [
            "1.318 berkas `.vue` di `src/pages/module` (38 modul) dari total 1.816 berkas halaman.",
            "72 dependensi runtime & 46 dev; Node >= 12.17.",
            "`src/composable/useApi.ts` membuat instance Axios dengan `baseURL = VITE_API_BASE_URL` "
            "dan menyisipkan header `token` dari `stores/userSession`.",
            "Tidak ada berkas `.env` di repo — `VITE_API_BASE_URL` harus diset dari environment "
            "saat build.",
            "Folder `documentation/` (355 berkas), `src/pages-quickstart`, `src/pages/components`, "
            "`src/pages/elements`, `marketing-*.vue` berasal dari template Vuero, bukan fitur SIMRS.",
            "Berkas sisa lama: `src/composable/useApi_old.ts`, `package_old.json`, "
            "`src/pages/index copy.vue`, `src/layouts/SidebarLayout_backup.vue`, "
            "beberapa halaman EMR `*-old*.vue`.",
            "Tersedia `Dockerfile`, `nginx/vuejs.conf`, `docker-compose.e2e.yml` untuk deployment, "
            "serta `json-server/` (`db.json`, `routes.json`) untuk mock API saat pengembangan.",
        ],
    },
    "viewer": {
        "judul": "`viewer/` — Aplikasi tampilan antrian/TV (Angular 11)",
        "ringkas": "Aplikasi penampil antrian dan informasi ruang tunggu (TV display), "
                   "memuat video profil rumah sakit dan suara panggilan.",
        "teknologi": ["Angular 11", "PrimeNG", "rxjs", "PWA"],
        "masuk": ["`src/main.ts` → `src/app/app.module.ts`",
                  "`src/app/app-routing.module.ts`",
                  "`src/config_app.json` (konfigurasi token & identitas RS)",
                  "Dijalankan `ng serve --open --host 0.0.0.0 --port 8202` (lihat `noted.txt`)"],
        "catatan": [
            "`src/assets/tv/jasmed.mp4` berukuran **97 MB** — berkas terbesar di repositori "
            "(video profil yang ikut ter-commit).",
            "`src/assets/{layout,sound,demo,theme}` total ±17 MB aset tampilan & audio.",
            "`src/config_app.json` menyimpan **token JWT hardcoded** (`tokenReservasi`, "
            "`tokenKiosK`) dan identitas `RSUD Kota Bandung`, padahal database pada dump "
            "bernama `rsud_malangbong` — konfigurasi perlu disesuaikan per instalasi dan "
            "token sebaiknya dipindah ke environment.",
            "`noted.txt` memuat perintah operasional pm2 dan contoh `ng generate`.",
            "Skrip `src/upload.php` ikut ter-commit di dalam folder sumber.",
        ],
    },
    "kiosk": {
        "judul": "`kiosk/` — Mesin kiosk mandiri (Angular 6.5, Vuexy)",
        "ringkas": "Aplikasi kiosk layar sentuh untuk registrasi mandiri, cek kepesertaan BPJS, "
                   "pengambilan nomor antrian, dan verifikasi kewarganegaraan.",
        "teknologi": ["Angular 6.5", "@angular/pwa", "@fake-db (data mock)",
                      "ngx-translate", "Google Maps", "ApexCharts"],
        "masuk": ["`src/main.ts` → `src/app/app.module.ts`",
                  "`src/app/page/module` (halaman kiosk aktif) dan "
                  "`src/app/main/module` (116 berkas) + `src/app/main/module-v2` (60 berkas)",
                  "`src/app/auth` (login & interceptor token)"],
        "catatan": [
            "Aset berat: `src/assets/images` 26 MB dan `src/assets/fonts` 11 MB.",
            "`ngsw-config.json` menandakan aplikasi PWA (dapat berjalan offline).",
            "`src/@fake-db` + `auth/helpers/fake-backend.ts` adalah data dummy bawaan template.",
            "Folder `module-v2` memuat versi baru alur kiosk (self-regis, self-regis-bpjs, "
            "verif kewarganegaraan, touchscreen) di samping `module` lama.",
            "Sebagian besar berkas HTML/TS berasal dari demo komponen Vuexy "
            "(`src/app/main/{ui,forms,tables,charts-and-maps,...}`).",
        ],
    },
    "e-reservasi": {
        "judul": "`e-reservasi/` — Reservasi online pasien (Angular)",
        "ringkas": "Aplikasi reservasi/booking layanan secara online oleh pasien "
                   "(nama paket `inhuman`, folder proyek `reservasionline_epic`).",
        "teknologi": ["Angular CLI legacy (`.angular-cli.json` + `angular.json`)",
                      "ngx-translate (i18n us/id)", "JSBarcode"],
        "masuk": ["`src/main.ts` → `src/app/app.module.ts`",
                  "`src/app/app.routes.ts`", "`src/app/page/**` (9 halaman reservasi)",
                  "`start.sh` → `ng serve --host=192.168.1.110 --port=4200`"],
        "catatan": [
            "Proyek terkecil: 164 berkas, 6 MB.",
            "`src/upload.php` ikut ter-commit (endpoint unggah di sisi web server).",
            "Skrip operasional: `start.sh`, `startlinux.sh` (`nohup npm start`), "
            "`stop.sh` (mematikan proses di port 4200).",
            "`app.sidebar.component.html_backup` adalah sisa berkas cadangan.",
            "`src/app/helper` (30 berkas) memuat helper barcode, tanggal, dan komunikasi API.",
        ],
    },
    "eis": {
        "judul": "`eis/` — Penampil EMR/EIS (Laravel)",
        "ringkas": "Aplikasi Laravel terpisah untuk menampilkan data pasien & rekam medis "
                   "dengan koneksi langsung ke database SIMRS (bukan lewat API backend).",
        "teknologi": ["Laravel (PHP 7.x)", "CoreUI (template admin)", "yoeunes/toastr",
                      "JWT (lcobucci/jwt, namshi/jose)", "MySQL (`.env.example`)", "Laravel Mix"],
        "masuk": ["`routes/web.php` (108 baris): login, `/view/pasien`, `/view/detail-emr`, `/emr`",
                  "`app/Http/Controllers/MedicalRecord/*` (General, Medisis, Pasien, Transmedic)",
                  "`app/Datatrans/*` (30 kelas query ke tabel SIMRS)",
                  "`public/index.php`"],
        "catatan": [
            "Memakai **MySQL** (`DB_CONNECTION=mysql`), sedangkan `backend` memakai PostgreSQL "
            "→ satu ekosistem dua driver database.",
            "`public/comp` berisi 56 MB aset template (bower_components 8,9 MB + assets 47 MB) "
            "dan `public/coreui` 5,2 MB — kandidat besar untuk dibersihkan.",
            "`routes/web_.php` (165 baris) tampak salinan lama `routes/web.php`.",
            "Tidak ada folder `vendor` di repo (dependency harus `composer install`).",
            "Folder `app/Traits` (19 berkas) dan `app/Datatrans` menjadi lapisan akses data.",
        ],
    },
    "socket-server": {
        "judul": "`socket-server/` — Server notifikasi realtime (Node.js)",
        "ringkas": "Layanan WebSocket yang meneruskan pesan dari RabbitMQ ke klien "
                   "(antrian, panggilan pasien, notifikasi push ke kiosk/TV).",
        "teknologi": ["Node.js", "socket.io 4.7.2 + uWebSockets.js", "amqplib (RabbitMQ)",
                      "web-push (notifikasi browser)", "express + compression + cors",
                      "postgres (client)", "node-storage"],
        "masuk": ["`server.js` (17 KB, entry point)", "`rabbitHole.js` (konektor RabbitMQ)",
                  "`setting.js` (konfigurasi host/port)", "`notif.dat` (penyimpanan lokal)"],
        "catatan": [
            "Konfigurasi default `rabbitMQHost: amqp://rsab:rsab@127.0.0.1`, "
            "`portSocket: 2530` — **kredensial RabbitMQ masih plain text**.",
            "Blok `mssql`/`pgsql` pada `setting.js` masih kosong (belum dikonfigurasi).",
            "CORS dibuka `origin: \"*\"` — perlu dibatasi saat produksi.",
            "Dijalankan dengan pm2 (lihat `viewer/noted.txt`).",
        ],
    },
    "load-test": {
        "judul": "`load-test/` — Skenario uji beban (k6)",
        "ringkas": "Kumpulan skrip k6 untuk menguji performa endpoint SIMRS, "
                   "dari registrasi, EMR, farmasi, sampai bridging BPJS.",
        "teknologi": ["k6 (Grafana)", "JavaScript", "@types/k6"],
        "masuk": ["`src/api.js` (helper request) dan `src/utils.js`",
                  "`test/**` — 332 berkas skrip dalam 31 folder modul",
                  "Contoh: `k6 run test/registrasi/passien/save-pasien-fix.js --vus 20 --duration 1m`"],
        "catatan": [
            "Cakupan modul uji: akuntansi, ambulance, aset, bed, bedah, bendahara, billing, "
            "bpjs, cssd, e-office, emr, farmasi, gizi, ipsrs, kiosk, logistik, payroll, "
            "pelatihan, perencanaan, ppi, registrasi, remunerasi, sanitasi, satu-sehat, sdm, "
            "sysadmin.",
            "`test/generate-token.js` dan `test/get-setting.js` menyiapkan autentikasi & "
            "konfigurasi sebelum skenario dijalankan.",
            "README memuat contoh menjalankan lewat Docker.",
        ],
    },
    "tools": {
        "judul": "`tools/` — Skrip bantu analisa & konversi",
        "ringkas": "Perkakas yang dibuat untuk menganalisa repositori dan mengubah "
                   "dump `sql.txt` menjadi dump MySQL.",
        "teknologi": ["Python 3", "sqlglot (validasi SQL saat verifikasi)"],
        "masuk": ["`tools/sql_txt_to_mysql.py` — konversi `sql.txt` → `sql_mysql.sql`",
                  "`tools/analisa/extract.py` — ekstraksi tabel/JOIN/route/endpoint",
                  "`tools/analisa/build_report.py` — penyusun `LAPORAN_ANALISA_DATABASE.md`",
                  "`tools/analisa/scan_repo.py` — pemindai seluruh folder (data laporan ini)",
                  "`tools/analisa/build_semua.py` — penyusun `ANALISA_SEMUA.md`"],
        "catatan": [
            "`tools/analisa/out/` berisi keluaran JSON (`controllers`, `frontend`, `tables`, "
            "`routes`, `models`, `closures`, `scan`) dan di-ignore Git.",
            "`tools/konversi_catatan.txt` mencatat detail konversi dump MySQL.",
        ],
    },
}

ROOT_FILES = {
    "README.md": "Readme repositori; hanya berisi `# simrs` tetapi tersimpan dalam encoding "
                 "**UTF-16** — sebaiknya diubah ke UTF-8 dan diisi dokumentasi.",
    "sql.txt": "Dump mentah hasil salin-tempel Adminer (PostgreSQL): 42 MB, berisi "
               "537 tabel × maksimal 100 baris + teks antarmuka Adminer.",
    "sql_mysql.sql": "Hasil konversi `sql.txt` ke MySQL siap import (537 CREATE TABLE, "
                     "15.874 baris INSERT) — 43 MB.",
    "LAPORAN_ANALISA_DATABASE.md": "Laporan analisa database & keterkaitan backend–frontend "
                                   "(±6.000 baris, 0,8 MB).",
    "LAPORAN_KONVERSI_SQL.md": "Laporan konversi dump: cara import, pemetaan tipe data, validasi.",
    ".gitignore": "Mengabaikan `/vendor/`, `/node_modules/`, `.env*`, isi `storage/` Laravel, "
                  "berkas log/IDE, dan `tools/analisa/out/`.",
    "ANALISA_SEMUA.md": "**Dokumen ini** — analisa lengkap seluruh folder & berkas repositori "
                        "(dibuat oleh `tools/analisa/build_semua.py`).",
}


def main():
    L = []
    add = L.append

    top_dirs = [d for d in sorted(os.listdir(ROOT))
                if os.path.isdir(os.path.join(ROOT, d)) and d not in SKIP_DIRS]
    folder_stats = {}
    for d in top_dirs:
        paths = walk(os.path.join(ROOT, d))
        s = stats(paths)
        s["paths"] = paths
        folder_stats[d] = s

    root_paths = [os.path.join(ROOT, f) for f in sorted(os.listdir(ROOT))
                  if os.path.isfile(os.path.join(ROOT, f))]
    root_stats = stats(root_paths)

    total_files = sum(s["files"] for s in folder_stats.values()) + root_stats["files"]
    total_bytes = sum(s["bytes"] for s in folder_stats.values()) + root_stats["bytes"]

    all_lang_files = collections.Counter()
    all_lang_lines = collections.Counter()
    for s in folder_stats.values():
        all_lang_files.update(s["langs"])
        all_lang_lines.update(s["lines"])
    all_lang_files.update(root_stats["langs"])
    all_lang_lines.update(root_stats["lines"])

    n_dirs = 0
    for dirpath, dirnames, _f in os.walk(ROOT):
        rel = os.path.relpath(dirpath, ROOT)
        parts = [] if rel == "." else rel.split(os.sep)
        if any(p in SKIP_DIRS for p in parts):
            dirnames[:] = []
            continue
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]
        n_dirs += 1

    short = {
        "frontend-v2": "Aplikasi petugas (Vue 3 + Vite)", "viewer": "Display antrian/TV (Angular)",
        "eis": "Penampil EMR/EIS (Laravel)", "kiosk": "Kiosk mandiri (Angular)",
        "backend": "API & logika bisnis (Laravel)", "e-reservasi": "Reservasi online (Angular)",
        "tools": "Skrip analisa & konversi (Python)", "load-test": "Uji beban k6",
        "socket-server": "Notifikasi realtime (Node.js)",
    }

    # ---------------- header + daftar isi ----------------
    add("# ANALISA LENGKAP FOLDER & FILE — REPOSITORI SIMRS")
    add("")
    add("**Repositori:** `denisyahid/simrs`  ")
    add("**Tanggal analisa:** %s  " % time.strftime("%d %B %Y"))
    add("**Cakupan:** seluruh folder (%s direktori) dan berkas (%s berkas) pada working tree, "
        "tanpa `.git`, `node_modules`, dan berkas dependency pihak ketiga  " % (num(n_dirs), num(total_files)))
    add("**Sumber data:** pemindaian langsung `tools/analisa/scan_repo.py` + `tools/analisa/build_semua.py`, "
        "diperkaya `tools/analisa/out/*.json` dan `LAPORAN_ANALISA_DATABASE.md`.")
    add("")
    add("## Daftar Isi")
    add("")
    add("| Bagian | Isi |")
    add("|---|---|")
    add("| [1. Ringkasan Repositori](#1-ringkasan-repositori) | jumlah berkas, ukuran, peta folder |")
    add("| [2. Peta & Pohon Direktori](#2-peta--pohon-direktori) | struktur tingkat atas |")
    add("| [3. Statistik Bahasa & Tipe Berkas](#3-statistik-bahasa--tipe-berkas) | baris kode & jenis berkas |")
    add("| [4. Analisa per Folder](#4-analisa-per-folder) | backend, frontend-v2, viewer, kiosk, "
        "e-reservasi, eis, socket-server, load-test, tools, berkas root |")
    add("| [5. Keterkaitan Antar Aplikasi](#5-keterkaitan-antar-aplikasi) | alur data & port |")
    add("| [6. Berkas Besar, Duplikasi & Kebersihan](#6-berkas-besar-duplikasi-dan-kebersihan-repositori) | berkas besar, berkas sisa, kredensial |")
    add("| [7. Temuan Utama & Rekomendasi](#7-temuan-utama--rekomendasi) | ringkasan temuan & prioritas |")
    add("| [Lampiran A](#lampiran-a--daftar-seluruh-folder) | **daftar seluruh folder** (%s direktori) |" % num(n_dirs))
    add("| [Lampiran B](#lampiran-b--daftar-seluruh-berkas) | **daftar seluruh berkas** (%s berkas) |" % num(total_files))
    add("")
    add("**Cara membaca:** bagian 1–3 untuk gambaran cepat; bagian 4 untuk rincian tiap folder "
        "(fungsi, teknologi, titik masuk, struktur, temuan, berkas terbesar); bagian 5–7 untuk "
        "keterkaitan antar aplikasi, kebersihan repo, dan rekomendasi; Lampiran A/B adalah "
        "inventaris lengkap semua folder dan berkas.")
    add("")

    # ---------------- 1. ringkasan ----------------
    add("---")
    add("")
    add("## 1. Ringkasan Repositori")
    add("")
    add("| Ukuran | Nilai |")
    add("|---|---|")
    add("| Jumlah folder | **%s** |" % num(n_dirs))
    add("| Jumlah berkas | **%s** |" % num(total_files))
    add("| Ukuran total | **%s** |" % human(total_bytes))
    add("| Folder tingkat atas | **%d folder** + %d berkas di root |"
        % (len(top_dirs), root_stats["files"]))
    add("| Bahasa terbanyak (baris) | %s |" % ", ".join(
        "%s (%s baris)" % (k, num(v)) for k, v in all_lang_lines.most_common(4)))
    add("")
    add("**Peta folder (urut ukuran):**")
    add("")
    add("| Folder | Folder | Berkas | Ukuran | Bahasa utama | Isi singkat |")
    add("|---|---|---|---|---|---|")
    for d, s in sorted(folder_stats.items(), key=lambda kv: -kv[1]["bytes"]):
        lang = ", ".join(k for k, _ in s["lines"].most_common(2))
        n_sub = 0
        for dirpath, dirnames, _f in os.walk(os.path.join(ROOT, d)):
            rel = os.path.relpath(dirpath, ROOT)
            if any(p in SKIP_DIRS for p in rel.split(os.sep)):
                continue
            n_sub += 1
        add("| `%s/` | %s | %s | %s | %s | %s |" % (
            d, num(n_sub), num(s["files"]), human(s["bytes"]), lang, short.get(d, "")))
    add("")
    add("> Kolom pertama **Folder** = jumlah direktori di dalam folder tersebut "
        "(termasuk folder itu sendiri). Rincian tiap folder ada di bagian 4.")
    add("")
    add("**Struktur besar:** repositori ini memuat **1 backend API** (Laravel) yang melayani "
        "**4 aplikasi frontend** (1 Vue + 3 Angular), ditambah **1 aplikasi Laravel terpisah** "
        "untuk penampil EMR/EIS, **1 layanan realtime** Node.js, sekumpulan **skenario uji beban** k6, "
        "**skrip analisa/konversi** Python, dan **dump database** PostgreSQL (`sql.txt`) "
        "beserta hasil konversinya ke MySQL (`sql_mysql.sql`).")
    add("")
    add("### 1.1 Angka penting lintas proyek")
    add("")
    add("| Aspek | Angka | Sumber |")
    add("|---|---|---|")
    add("| Tabel database pada dump | 537 (368 berisi data, 169 kosong) | `LAPORAN_ANALISA_DATABASE.md` §4 |")
    add("| Tabel dipakai controller | 442 dari 537 (82,3%) | idem §10.3 |")
    add("| Model Eloquent backend | 369 berkas | `out/models.json` |")
    add("| Controller backend | 269 berkas / 1.898 method | `out/controllers.json` |")
    add("| Rute backend terdaftar | 2.458 rute | `out/routes.json` |")
    add("| Pasangan JOIN unik | 7.657 klausa → 598 pasangan kolom | `out/tables.json` |")
    add("| Halaman frontend-v2 | 1.232 berkas melayani 2.458 rute | `out/frontend.json` |")
    add("| Modul frontend-v2 | 38 modul di `src/pages/module` | pemindaian |")
    add("| Berkas `.vue` frontend-v2 | 1.318 di `src/pages/module` | pemindaian |")
    add("| Skrip uji beban k6 | 332 skrip dalam 31 folder modul | `load-test/test` |")
    add("| Kandidat kredensial di kode | 6 lokasi (lihat §6.4) | pemindaian |")
    add("")

    # ---------------- 2. pohon ----------------
    add("---")
    add("")
    add("## 2. Peta & Pohon Direktori")
    add("")
    add("```")
    add("simrs/")
    ranked = sorted(folder_stats, key=lambda x: -folder_stats[x]["bytes"])
    for i, d in enumerate(ranked):
        s = folder_stats[d]
        last = i == len(ranked) - 1
        add("%s %s/  (%s berkas, %s)" % ("└──" if last else "├──", d, num(s["files"]), human(s["bytes"])))
        if last:
            for f in root_paths:
                add("    ├── %s  (%s)" % (os.path.basename(f), human(os.path.getsize(f))))
    add("```")
    add("")
    add("Pohon lengkap setiap folder (sampai 2 level + berkas penting) ada di bagian 4; "
        "daftar **seluruh** %s folder ada di [Lampiran A](#lampiran-a--daftar-seluruh-folder)." % num(n_dirs))
    add("")

    # ---------------- 3. bahasa ----------------
    add("---")
    add("")
    add("## 3. Statistik Bahasa & Tipe Berkas")
    add("")
    add("### 3.1 Baris kode per bahasa")
    add("")
    add("| Bahasa | Berkas | Baris |")
    add("|---|---|---|")
    for lang, n in all_lang_lines.most_common():
        add("| %s | %s | %s |" % (lang, num(all_lang_files.get(lang, 0)), num(n)))
    add("")
    add("### 3.2 Bahasa per folder")
    add("")
    add("| Folder | PHP | Vue | TS | JS | SCSS/CSS | HTML | SQL/JSON | Lainnya |")
    add("|---|---|---|---|---|---|---|---|---|")
    for d in sorted(folder_stats, key=lambda x: -folder_stats[x]["bytes"]):
        ext = folder_stats[d]["ext"]
        sisa = sum(v for k, v in ext.items()
                   if k not in (".php", ".vue", ".ts", ".js", ".scss", ".css", ".html",
                                ".json", ".sql"))
        add("| `%s/` | %s | %s | %s | %s | %s | %s | %s | %s |" % (
            d, num(ext.get(".php", 0)), num(ext.get(".vue", 0)), num(ext.get(".ts", 0)),
            num(ext.get(".js", 0)), num(ext.get(".scss", 0) + ext.get(".css", 0)),
            num(ext.get(".html", 0)), num(ext.get(".json", 0) + ext.get(".sql", 0)),
            num(sisa)))
    add("| _(root)_ | 0 | 0 | 0 | 0 | 0 | 0 | %s | %s |" % (
        num(root_stats["ext"].get(".sql", 0)), num(root_stats["files"])))
    add("")

    # ---------------- 4. per folder ----------------
    add("---")
    add("")
    add("## 4. Analisa per Folder")
    add("")
    order = ["backend", "frontend-v2", "viewer", "kiosk", "e-reservasi", "eis",
             "socket-server", "load-test", "tools"]
    KEY_FILES = {
        "composer.json", "composer.lock", "package.json", "package-lock.json",
        "artisan", "angular.json", ".angular-cli.json", "server.js", "setting.js",
        "rabbitHole.js", "README.md", "Dockerfile", "vite.config.ts", "tsconfig.json",
        "routes/web.php", "webpack.mix.js", "ngsw-config.json", "karma.conf.js",
        "noted.txt", "start.sh", "serve.sh", "config_app.json", "src", "app", "public",
        "documentation", "db.json", "api.js", "utils.js", "cypress.json",
        "web.php", "api.php", "sql_txt_to_mysql.py", "konversi_catatan.txt",
        "extract.py", "build_report.py", "scan_repo.py", "build_semua.py",
        "routes.json", "console.php", "channels.php", "startlinux.sh", "stop.sh",
        "manifest.webmanifest", "app.module.ts", "main.ts", "vite.config.ts",
    }
    for i, d in enumerate(order, 1):
        info = FOLDER_INFO[d]
        s = folder_stats[d]
        add("### 4.%d %s" % (i, info["judul"]))
        add("")
        add("| | |")
        add("|---|---|")
        add("| Jumlah berkas | **%s** |" % num(s["files"]))
        n_sub = 0
        for dirpath, dirnames, _f in os.walk(os.path.join(ROOT, d)):
            rel = os.path.relpath(dirpath, ROOT)
            if any(p in SKIP_DIRS for p in rel.split(os.sep)):
                continue
            n_sub += 1
        add("| Jumlah folder | **%s** |" % num(n_sub))
        add("| Ukuran | **%s** |" % human(s["bytes"]))
        add("| Bahasa | %s |" % ", ".join("%s %s berkas" % (k, num(v))
                                         for k, v in s["langs"].most_common(4)))
        add("")
        add(info["ringkas"])
        add("")
        add("**Teknologi:** %s" % "; ".join(info["teknologi"]))
        add("")
        add("**Titik masuk / cara menjalankan:**")
        for m in info["masuk"]:
            add("- %s" % m)
        add("")
        add("**Struktur utama:**")
        add("")
        add("```")
        add("%s/" % d)
        add("\n".join(tree(os.path.join(ROOT, d), max_depth=2, key_files=KEY_FILES)))
        add("```")
        add("")
        ext = s["ext"]
        add("**Berkas menurut jenis (10 teratas):** %s" % ", ".join(
            "`%s` %s" % (k, num(v)) for k, v in ext.most_common(10)))
        add("")
        # daftar modul per aplikasi
        if d == "backend":
            items = list_modules(os.path.join(ROOT, "backend/app/Http/Controllers"))
            add("\n".join(module_table("Daftar modul controller (`app/Http/Controllers/*`)", items)))
            items = [("Models/Master", 185), ("Models/Transaksi", 177), ("Models/Standar", 10)]
            add("\n".join(module_table("Struktur model", items, "Folder model")))
        elif d == "frontend-v2":
            items = list_modules(os.path.join(ROOT, "frontend-v2/src/pages/module"))
            add("\n".join(module_table("Daftar modul halaman (`src/pages/module/*`)", items)))
        elif d == "viewer":
            items = list_modules(os.path.join(ROOT, "viewer/src/app"))
            add("\n".join(module_table("Struktur `src/app`", items, "Folder")))
        elif d == "kiosk":
            items = list_modules(os.path.join(ROOT, "kiosk/src/app/main/module"))
            add("\n".join(module_table("Halaman `src/app/main/module`", items, "Halaman")))
            items = list_modules(os.path.join(ROOT, "kiosk/src/app/main/module-v2"))
            add("\n".join(module_table("Halaman `src/app/main/module-v2`", items, "Halaman")))
        elif d == "e-reservasi":
            items = list_modules(os.path.join(ROOT, "e-reservasi/src/app"))
            add("\n".join(module_table("Struktur `src/app`", items, "Folder")))
        elif d == "eis":
            items = list_modules(os.path.join(ROOT, "eis/app/Datatrans"))
            add("\n".join(module_table("Kelas akses data (`app/Datatrans`)", items, "Kelas")))
            items = list_modules(os.path.join(ROOT, "eis/app/Http/Controllers"))
            add("\n".join(module_table("Controller (`app/Http/Controllers`)", items, "Controller")))
        elif d == "load-test":
            items = list_modules(os.path.join(ROOT, "load-test/test"))
            add("\n".join(module_table("Folder skenario uji (`test/*`)", items)))
        elif d == "socket-server":
            items = [(f, 1) for f in sorted(os.listdir(os.path.join(ROOT, "socket-server")))
                     if os.path.isfile(os.path.join(ROOT, "socket-server", f))]
            add("\n".join(module_table("Daftar seluruh berkas", items, "Berkas")))
        elif d == "tools":
            items = list_modules(os.path.join(ROOT, "tools"))
            add("\n".join(module_table("Struktur `tools/`", items, "Folder/Berkas")))
        add("**Catatan & temuan:**")
        for c in info["catatan"]:
            add("- %s" % c)
        add("")
        add("**Berkas terbesar di folder ini:**")
        add("")
        add("| Berkas | Ukuran |")
        add("|---|---|")
        for p in sorted(s["paths"], key=lambda p: -os.path.getsize(p))[:10]:
            add("| `%s` | %s |" % (os.path.relpath(p, ROOT), human(os.path.getsize(p))))
        add("")

    # ---------------- 4.10 berkas root ----------------
    add("### 4.10 Berkas di Root Repositori")
    add("")
    add("Terdapat %d berkas di tingkat atas (total %s):" % (root_stats["files"], human(root_stats["bytes"])))
    add("")
    add("| Berkas | Ukuran | Baris | Keterangan |")
    add("|---|---|---|---|")
    for f in sorted(os.listdir(ROOT)):
        p = os.path.join(ROOT, f)
        if not os.path.isfile(p):
            continue
        ln = num(lines_of(p)) if is_text(p) else "—"
        add("| `%s` | %s | %s | %s |" % (f, human(os.path.getsize(p)), ln,
                                         ROOT_FILES.get(f, "—")))
    add("")

    # ---------------- 5. integrasi ----------------
    add("---")
    add("")
    add("## 5. Keterkaitan Antar Aplikasi")
    add("")
    add("```")
    add("  frontend-v2 (Vue, :2222)      kiosk (Angular)        viewer (Angular, :8202)")
    add("        │                            │                          │")
    add("        │  HTTP /service/* + token    │                          │  display antrian")
    add("        ▼                            ▼                          │")
    add("  ┌─────────────────────────────────────────────────┐           │")
    add("  │ backend/ — Laravel API (serve.sh :8000)         │           │")
    add("  │ routes/web.php → Controller → Model/Query → DB  │           │")
    add("  └───────┬──────────────────────────────┬──────────┘           │")
    add("          │                              │                      │")
    add("   PostgreSQL (dump sql.txt)     MongoDB (jenssegers)            │")
    add("          ▲                              ▲                      │")
    add("          │                              │                      │")
    add("   eis/ (Laravel, MySQL langsung)   socket-server (:2530) ◄── RabbitMQ")
    add("   e-reservasi (Angular, :4200) ──────► backend                │")
    add("   load-test (k6) ─────────────────────► backend                │")
    add("   tools/ (Python) ──► sql.txt → sql_mysql.sql                 │")
    add("   LAPORAN_*.md / ANALISA_SEMUA.md = dokumentasi hasil analisa  ◄┘")
    add("```")
    add("")
    add("| Aplikasi | Peran | Port / titik masuk | Berkomunikasi dengan |")
    add("|---|---|---|---|")
    add("| `backend/` | API utama (Laravel 8) | `serve.sh` → 8000, `public/index.php` | PostgreSQL, MongoDB, BPJS/SATUSEHAT/IHS, RabbitMQ via socket-server |")
    add("| `frontend-v2/` | Aplikasi petugas (Vue 3) | `npm run dev` → 2222 | `backend` lewat `VITE_API_BASE_URL` + prefix `service`, socket.io |")
    add("| `kiosk/` | Kiosk mandiri (Angular) | `ng serve` (port default) | `backend` |")
    add("| `viewer/` | Display antrian/TV (Angular 11) | `ng serve --port 8202` | `backend` + pesan realtime socket.io |")
    add("| `e-reservasi/` | Reservasi online (Angular) | `start.sh` → port 4200 | `backend`, `src/upload.php` |")
    add("| `eis/` | Penampil EMR/EIS (Laravel) | `routes/web.php` | Database MySQL langsung |")
    add("| `socket-server/` | Notifikasi realtime | `server.js` → 2530 | RabbitMQ (amqp) + socket.io ke semua frontend |")
    add("| `load-test/` | Uji beban | `k6 run test/**.js` | `backend` |")
    add("| `tools/` | Analisa & konversi | `python3 tools/**.py` | `sql.txt`, kode sumber |")
    add("")
    add("**Pola yang terlihat seragam:**")
    add("")
    add("1. Frontend memanggil `backend` dengan prefix `service/` dan token pada header "
        "(`token` di `frontend-v2`; interceptor pada kiosk & viewer).")
    add("2. `backend` memakai JWT (`lcobucci/jwt`) + middleware `jwt.auth`, dan hampir semua "
        "query menyertakan `kdprofile` sebagai konteks profil/instansi.")
    add("3. `eis` menyimpang dari pola: mengakses database langsung dan memakai MySQL, "
        "sedangkan `backend` memakai PostgreSQL — perbedaan ini penting saat migrasi/deployment.")
    add("4. Semua frontend menempatkan konfigurasi alamat API pada berkas environment masing-masing "
        "(`VITE_API_BASE_URL`, environment Angular, `config_app.json`).")
    add("")

    # ---------------- 6. kebersihan ----------------
    add("---")
    add("")
    add("## 6. Berkas Besar, Duplikasi, dan Kebersihan Repositori")
    add("")
    add("### 6.1 Berkas terbesar")
    add("")
    add("| # | Berkas | Ukuran | Catatan |")
    add("|---|---|---|---|")
    big_all = []
    for d, s in folder_stats.items():
        for p in s["paths"]:
            big_all.append((os.path.getsize(p), os.path.relpath(p, ROOT)))
    for p in root_paths:
        big_all.append((os.path.getsize(p), os.path.basename(p)))
    note_map = {
        "viewer/src/assets/tv/jasmed.mp4": "video profil 97 MB — pindahkan ke storage/CDN atau Git LFS",
        "sql.txt": "dump mentah sumber",
        "sql_mysql.sql": "hasil konversi siap import",
        "eis/public/comp/assets/pages/ckeditor/ckeditor.js": "aset template (pihak ketiga)",
        "frontend-v2/public/video/hands.ogv": "video demo template",
        "frontend-v2/public/video/hands.mp4": "video demo template",
        "backend/coverage.xml": "hasil coverage PHPUnit, tidak perlu di-commit",
        "LAPORAN_ANALISA_DATABASE.md": "laporan analisa database",
        "viewer/package-lock.json": "lockfile npm",
        "frontend-v2/public/images/photo/video/dress.webm": "video demo template",
        "backend/composer.lock.old": "lockfile lama (tidak dipakai)",
        "backend/composer.lock.old1": "lockfile lama (tidak dipakai)",
    }
    for i, (size, path) in enumerate(sorted(big_all, reverse=True)[:20], 1):
        add("| %d | `%s` | %s | %s |" % (i, path, human(size), note_map.get(path, "—")))
    add("")
    add("### 6.2 Berkas sisa/duplikat")
    add("")
    dups = []
    for d, s in folder_stats.items():
        for p in s["paths"]:
            name = os.path.basename(p).lower()
            if any(k in name for k in (" copy", "copy.", "_old", "-old", ".old", "_backup",
                                       "backup", ".bak")) and not p.endswith(".svg"):
                dups.append(p)
    add("Ditemukan **%d berkas** yang tampak sisa pengembangan (copy/old/backup):" % len(dups))
    add("")
    for p in sorted(dups):
        add("- `%s`" % os.path.relpath(p, ROOT))
    add("")
    add("### 6.3 Yang di-ignore Git")
    add("")
    add("`.gitignore` root mengabaikan `/vendor/`, `/node_modules/`, `.env*`, isi `storage/` "
        "Laravel, berkas log/IDE, dan `tools/analisa/out/`. Catatan: "
        "`backend/public/kyc-satset/vendor` **ikut ter-commit** karena pola `/vendor/` hanya "
        "menangkap folder vendor di akar repositori.")
    add("")
    add("Berkas yang dikecualikan dari pemindaian laporan ini (dependency/artefak pihak ketiga): "
        "`backend/public/kyc-satset/vendor` (375 berkas, 3,1 MB — library PHP eKYC), "
        "`eis/public/comp/assets/pages/ace-editor/build` (1 berkas, 304 KB), "
        "`tools/analisa/__pycache__` (cache Python).")
    add("")
    add("### 6.4 Potensi risiko kredensial")
    add("")
    add("| Lokasi | Isi | Saran |")
    add("|---|---|---|")
    add("| `viewer/src/config_app.json` | token JWT `tokenReservasi` & `tokenKiosK` tertulis di berkas | pindahkan ke environment, rotasi token |")
    add("| `socket-server/setting.js` | `amqp://rsab:rsab@127.0.0.1` (user & password RabbitMQ) | gunakan environment variable + ganti password |")
    add("| `backend/app/Http/Controllers/Bridging/BridgingBPJSCtrl.php` | `$secretKey = \"0kS55036F0\"` hardcoded (baris 1621) | pindah ke `.env`/tabel setting |")
    add("| `backend/app/Http/Controllers/Bridging/TilakaCtrl.php` | client id & secret eKYC tertulis di kode | pindah ke `.env`, rotasi kredensial |")
    add("| `e-reservasi/src/upload.php`, `viewer/src/upload.php` | endpoint unggah ikut ter-commit | tinjau validasi & hak akses, atau hapus bila tak dipakai |")
    add("| `backend/public/info.php` | berkas `phpinfo()` berpotensi membocorkan konfigurasi | hapus di produksi |")
    add("")

    # ---------------- 7. temuan ----------------
    add("---")
    add("")
    add("## 7. Temuan Utama & Rekomendasi")
    add("")
    add("### 7.1 Temuan")
    add("")
    add("1. **Satu backend melayani empat frontend** dengan pola API seragam "
        "(`service/` + token + `kdprofile`) — kecuali `eis/` yang mengakses database langsung "
        "dan memakai MySQL.")
    add("2. **Dump `sql.txt` tidak memuat seluruh tabel yang dipakai kode** (23 tabel hilang — "
        "lihat `LAPORAN_ANALISA_DATABASE.md` §10.3) dan masih ada 95 tabel yang tidak dipakai "
        "controller mana pun.")
    add("3. **Beban repositori didominasi aset media & template**: `viewer` 117 MB (96,6 MB "
        "berkas video `jasmed.mp4`), `frontend-v2` 161 MB, `eis` 63 MB (56 MB aset template), "
        "`kiosk` 40 MB (37 MB gambar/font).")
    add("4. **Dua keluarga teknologi**: Vue 3 + Vite untuk aplikasi utama, Angular untuk "
        "kiosk/viewer/e-reservasi (dua versi CLI: `angular.json` dan `.angular-cli.json` legacy).")
    add("5. **Sisa pengembangan masih ada**: %d berkas copy/old/backup, `composer.lock.old{,1}`, "
        "`package_old.json`, `useApi_old.ts`, `routes/web_.php`, `coverage.xml`, dan folder "
        "`vendor` yang ikut ter-commit." % len(dups))
    add("6. **Kredensial sensitif berada di kode/berkas konfigurasi** (token kiosk, password "
        "RabbitMQ, secret BPJS & Tilaka, `upload.php`) — lihat §6.4.")
    add("7. **`README.md` root hanya berisi `# simrs` dan ber-encoding UTF-16**, sehingga repo "
        "tidak punya dokumentasi cara menjalankan — laporan ini dan "
        "`LAPORAN_ANALISA_DATABASE.md` dapat menjadi acuan awal.")
    add("")
    add("### 7.2 Rekomendasi prioritas")
    add("")
    add("| Prioritas | Tindakan | Alasan |")
    add("|---|---|---|")
    add("| Tinggi | Pindahkan kredensial (token JWT, password RabbitMQ, secret BPJS/Tilaka) ke environment | mencegah kebocoran bila repo dibagikan |")
    add("| Tinggi | Tulis ulang `README.md` (UTF-8) berisi arsitektur, cara menjalankan tiap aplikasi, dan daftar port | onboarding & operasional |")
    add("| Tinggi | Keluarkan aset besar (video/font/gambar template) dari Git ke storage/CDN, atau pakai Git LFS | memperkecil clone & mempercepat CI |")
    add("| Sedang | Hapus berkas sisa (`*copy*`, `*_old*`, `composer.lock.old*`, `package_old.json`, `routes/web_.php`, `coverage.xml`, `public/info.php`) | kebersihan & mengurangi kebingungan |")
    add("| Sedang | Tambahkan pola `.gitignore` untuk `backend/public/kyc-satset/vendor` | vendor pihak ketiga tidak di-commit |")
    add("| Sedang | Dokumentasikan & seragamkan konfigurasi database (`backend` PostgreSQL vs `eis` MySQL) | mencegah error deployment/migrasi |")
    add("| Sedang | Buat `docker-compose` untuk backend + frontend + socket-server + database | mempermudah onboarding & reproduksi lingkungan |")
    add("| Sedang | Satukan alur kiosk (`main/module` vs `main/module-v2`) dan bersihkan halaman demo template | mengurangi kode mati |")
    add("| Rendah | Jalankan lint/format otomatis (ESLint & PHP-CS-Fixer sudah dikonfigurasi) | konsistensi kode |")
    add("| Rendah | Tambahkan index pada kolom JOIN tersering (`LAPORAN_ANALISA_DATABASE.md` §7.2) | performa query |")
    add("")

    # ---------------- Lampiran A: semua folder ----------------
    add("---")
    add("")
    add("## Lampiran A — Daftar Seluruh Folder")
    add("")
    add("Seluruh **%s direktori** pada repositori (tanpa `.git`, `node_modules`, `vendor`, "
        "dan folder artefak), diurutkan sesuai struktur. Kolom angka menunjukkan jumlah berkas "
        "dan ukuran total di dalam folder tersebut (termasuk subfolder)." % num(n_dirs))
    add("")
    add("```")
    rows = []
    for dirpath, dirnames, _f in os.walk(ROOT):
        rel = os.path.relpath(dirpath, ROOT)
        parts = [] if rel == "." else rel.split(os.sep)
        if any(p in SKIP_DIRS for p in parts):
            dirnames[:] = []
            continue
        dirnames[:] = sorted(d for d in dirnames if d not in SKIP_DIRS)
        if rel == ".":
            rows.append(("", 0))
            continue
        rows.append((rel, len(parts)))
    rows.sort(key=lambda r: r[0])
    add("%-70s %8s %10s" % ("FOLDER", "BERKAS", "UKURAN"))
    add("-" * 90)
    for rel, depth in rows:
        if rel == "":
            n, b = dir_summary(ROOT)
            add("%-70s %8s %10s" % ("(root repositori)", num(n), human(b)))
            continue
        n, b = dir_summary(os.path.join(ROOT, rel))
        label = "  " * (depth - 1) + os.path.basename(rel) + "/"
        add("%-70s %8s %10s" % (label[:70], num(n), human(b)))
    add("```")
    add("")

    # ---------------- Lampiran B: semua berkas ----------------
    add("---")
    add("")
    add("## Lampiran B — Daftar Seluruh Berkas")
    add("")
    add("Inventaris **%s berkas** (%s) diurutkan per folder. Kolom `BARIS` hanya diisi untuk "
        "berkas teks (maksimal 4 MB pertama yang dibaca); berkas biner ditandai `—`."
        % (num(total_files), human(total_bytes)))
    add("")
    add("```")
    add("%10s %8s  %s" % ("UKURAN", "BARIS", "BERKAS"))
    add("-" * 110)
    all_files = []
    for d, s in folder_stats.items():
        all_files.extend(s["paths"])
    all_files.extend(root_paths)
    all_files.sort(key=lambda p: os.path.relpath(p, ROOT))
    for p in all_files:
        try:
            size = os.path.getsize(p)
        except OSError:
            continue
        ln = num(lines_of(p)) if is_text(p) else "—"
        add("%10s %8s  %s" % (human(size), ln, os.path.relpath(p, ROOT)))
    add("```")
    add("")
    add("---")
    add("")
    add("_Dokumen ini dibuat otomatis dari pemindaian langsung repositori oleh "
        "`tools/analisa/scan_repo.py` dan `tools/analisa/build_semua.py`. "
        "Data mentah: `tools/analisa/out/scan.json`._")

    with open(REPORT, "w", encoding="utf-8") as fh:
        fh.write("\n".join(L) + "\n")
    print("laporan ditulis:", REPORT, "%.2f MB" % (os.path.getsize(REPORT) / 1048576))
    print("baris:", len(L))


if __name__ == "__main__":
    main()
