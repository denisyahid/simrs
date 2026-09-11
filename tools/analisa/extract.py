#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Ekstraksi data untuk LAPORAN_ANALISA_DATABASE.md.

Menghasilkan JSON di tools/analisa/out/ :
  - models.json      : peta kelas model Eloquent -> nama tabel
  - controllers.json : per file controller -> method -> tabel & join
  - routes.json      : peta URL -> controller@method (dari routes/web.php)
  - frontend.json    : per file frontend -> endpoint API yang dipanggil
  - tables.json      : info tabel dari sql_mysql.sql (kolom, tipe, jumlah baris)
"""

import collections
import json
import os
import re
import sys

ROOT = "/home/user/simrs"
BACKEND = os.path.join(ROOT, "backend")
OUT = os.path.join(ROOT, "tools", "analisa", "out")
os.makedirs(OUT, exist_ok=True)

# --------------------------------------------------------------------------
# 1. peta model -> tabel
# --------------------------------------------------------------------------
def extract_models():
    models = {}
    root = os.path.join(BACKEND, "app", "Models")
    for dirpath, _dirs, files in os.walk(root):
        for fn in files:
            if not fn.endswith(".php"):
                continue
            path = os.path.join(dirpath, fn)
            text = open(path, encoding="utf-8", errors="replace").read()
            m = re.search(r'protected\s+\$table\s*=\s*["\']([^"\']+)["\']', text)
            cls = fn[:-4]
            ns = re.search(r"namespace\s+([^;]+);", text)
            table = m.group(1) if m else None
            if table is None:
                # konvensi Laravel: NamaKelas -> nama_kelas (jarang dipakai di sini)
                continue
            models[cls] = {
                "table": table.strip(),
                "file": os.path.relpath(path, ROOT),
                "namespace": ns.group(1).strip() if ns else "",
                "timestamps": "public $timestamps = true" in text.replace(" ", " ")
                              or "$timestamps = true" in text,
            }
    return models


# --------------------------------------------------------------------------
# 2. analisa controller
# --------------------------------------------------------------------------
RE_METHOD = re.compile(
    r"^\s*(?:public|private|protected|static|\s)*function\s+(\w+)\s*\(", re.M
)

# kata kunci/fungsi SQL yang sering "terbaca" sebagai nama tabel dari raw SQL
DROP_WORDS = {
    "select", "where", "from", "join", "left", "right", "inner", "outer", "cross",
    "full", "on", "and", "or", "not", "null", "dual", "values", "set", "update",
    "delete", "insert", "into", "table", "group", "order", "by", "limit", "offset",
    "union", "all", "distinct", "as", "with", "case", "when", "then", "else", "end",
    "now", "age", "current_date", "current_time", "current_timestamp", "lateral",
    "generate_series", "information_schema", "index", "status", "extract", "cast",
    "coalesce", "concat", "substring", "upper", "lower", "count", "sum", "avg",
    "min", "max", "interval", "date", "time", "timestamp", "text", "int", "bigint",
    "numeric", "boolean", "begin", "declare", "loop", "return", "returns", "language",
    "json", "jsonb", "array", "row", "rows", "only", "using", "natural", "having",
    "exists", "between", "like", "ilike", "in", "is", "over", "partition", "window",
    "create", "alter", "drop", "truncate", "grant", "revoke", "commit", "rollback",
    "true", "false", "default", "primary", "key", "foreign", "references", "constraint",
}
DUMP_TABLES = set()
RE_DBTABLE = re.compile(r"""DB::table\(\s*['"]([^'"]+)['"]""")
RE_DBSELECT_FROM = re.compile(r"""DB::(?:select|selectOne|statement|raw)""")
RE_SQL_FROM = re.compile(r"\b(?:from|join|into|update)\s+([a-zA-Z_][a-zA-Z0-9_#]*)", re.I)
RE_JOIN_BUILDER = re.compile(
    r"""->(?:join|leftJoin|rightJoin|joinWhere)\(\s*['"]([^'"]+)['"]\s*,\s*['"]([^'"]+)['"]\s*,\s*(?:['"]([^'"]+)['"]\s*,\s*)?['"]([^'"]+)['"]"""
)
RE_RAW_JOIN = re.compile(
    r"\b(?:left\s+|right\s+|inner\s+|cross\s+|full\s+)?join\s+([a-zA-Z_][a-zA-Z0-9_#]*)"
    r"(?:\s+(?:as\s+)?([a-zA-Z_][a-zA-Z0-9_]*))?\s+on\s+([^;\n]{0,160})", re.I
)
# string yang isinya SQL
RE_SQLSTR = re.compile(r"(['\"])((?:\\.|(?!\1)[^\\])*?)\1", re.S)


def clean_alias(raw):
    """'pasiendaftar_t as pd' -> ('pasiendaftar_t','pd')"""
    parts = re.split(r"\s+as\s+|\s+", raw.strip(), maxsplit=1, flags=re.I)
    table = parts[0].strip('"`[] ')
    alias = parts[1].strip('"`[] ') if len(parts) > 1 else table
    return table, alias


def analyze_module_imports(text):
    """use App\\Models\\...\\Kelas; -> {Kelas: fqn}"""
    out = {}
    for m in re.finditer(r"^use\s+([A-Za-z0-9_\\]+?)\\(\w+);", text, re.M):
        fqn, cls = m.group(1), m.group(2)
        if "Models" in fqn:
            out[cls] = fqn
    return out


def analyze_controller(path, models):
    text = open(path, encoding="utf-8", errors="replace").read()
    rel = os.path.relpath(path, ROOT)
    module = os.path.basename(os.path.dirname(path))
    imports = analyze_module_imports(text)
    model_names = {c: models[c]["table"] for c in imports if c in models}

    methods = list(RE_METHOD.finditer(text))
    result = {"file": rel, "module": module, "class": os.path.basename(path)[:-4],
              "methods": {}, "n_methods_total": len(methods)}
    if not methods:
        spans = [("__class__", text)]
    else:
        spans = []
        for i, m in enumerate(methods):
            start = m.start()
            end = methods[i + 1].start() if i + 1 < len(methods) else len(text)
            spans.append((m.group(1), text[start:end]))

    for name, body in spans:
        # buang komentar baris supaya "// join x on y" tidak ikut terhitung
        clean = "\n".join(l for l in body.split("\n")
                          if not l.strip().startswith(("//", "*", "/*", "#")))
        tables = collections.Counter()
        weak = collections.Counter()          # hanya dari raw SQL (perlu diverifikasi)
        cte_names = set()                     # nama CTE/subquery, bukan tabel
        joins = []
        alias_map = {}

        for m in RE_DBTABLE.finditer(clean):
            t, a = clean_alias(m.group(1))
            tables[t] += 1
            alias_map[a] = t
            alias_map[t] = t

        for m in re.finditer(r"""->from\(\s*['"]([^'"]+)['"]""", clean):
            t, a = clean_alias(m.group(1))
            tables[t] += 1
            alias_map[a] = t

        for m in RE_JOIN_BUILDER.finditer(clean):
            t, alias = clean_alias(m.group(1))
            left = m.group(2)
            right = m.group(4)
            on = m.group(3) or "="
            tables[t] += 1
            alias_map[alias] = t
            joins.append({"table": t, "alias": alias, "left": left, "op": on,
                          "right": right, "style": "builder",
                          "op_invalid": on.strip().upper() not in
                                        ("=", "<", ">", "<=", ">=", "<>", "!=", "LIKE")})

        # raw SQL di dalam string
        for m in RE_SQLSTR.finditer(clean):
            s = m.group(2)
            if not re.search(r"\b(select|from|join|insert|update|delete)\b", s, re.I):
                continue
            for cte in re.finditer(r"\bwith\s+(\w+)\s+as\s*\(|,\s*(\w+)\s+as\s*\(",
                                   s, re.I):
                cte_names.add(cte.group(1) or cte.group(2))
            for fm in RE_SQL_FROM.finditer(s):
                weak[fm.group(1)] += 1
            aliases = {}
            for jm in RE_RAW_JOIN.finditer(s):
                t = jm.group(1)
                alias = jm.group(2) or t
                if alias.lower() in ("on", "where", "and", "or", "inner", "left",
                                     "right", "join", "as", "group", "order", "set"):
                    alias = t
                aliases[alias] = t
                alias_map[alias] = t
                joins.append({"table": t, "alias": alias, "on": jm.group(3).strip(),
                              "style": "raw"})
            # alias dari FROM/JOIN dikembalikan ke nama tabel asli
            for fm in re.finditer(r"\bfrom\s+([a-zA-Z_][\w#]*)(?:\s+(?:as\s+)?([a-zA-Z_]\w*))?",
                                  s, re.I):
                t = fm.group(1)
                al = fm.group(2)
                if al and al.lower() not in ("where", "group", "order", "limit",
                                             "left", "right", "inner", "join", "on",
                                             "and", "or", "as", "select", "union", "set"):
                    aliases[al] = t
                    alias_map[al] = t

        # pemakaian model Eloquent: Kelas::...
        for cls, table in model_names.items():
            n = len(re.findall(r"\b" + re.escape(cls) + r"::", clean))
            if n:
                tables[table] += n

        # gabungkan referensi raw SQL yang lolos penyaring:
        #  - nama tabel memang ada di dump, ATAU
        #  - mengikuti konvensi nama tabel SIMRS (_m/_t/_s/_d) dan bukan CTE
        for t, n in weak.items():
            low = t.lower()
            if low in DROP_WORDS:
                continue
            if t in cte_names or low in {c.lower() for c in cte_names}:
                continue
            if t in DUMP_TABLES or (re.fullmatch(r"[a-z][a-z0-9_]*", t)
                                    and t.endswith(("_t", "_m", "_s", "_d"))):
                tables[t] += n

        # buang nama tabel palsu (kata kunci SQL / variabel)
        bad = {"select", "where", "and", "or", "set", "values", "dual", "on",
               "update", "delete", "insert", "from", "join", "table", "t", "x"}
        tables = collections.Counter({
            k: v for k, v in tables.items()
            if k and k.lower() not in bad
            and k.lower() not in DROP_WORDS
            and not k.startswith("$")
            and "." not in k
            and k.lower() != "information_schema"})

        if tables or joins:
            result["methods"][name] = {
                "tables": dict(tables.most_common()),
                "joins": joins,
                "aliases": dict(alias_map),
            }
    return result


# --------------------------------------------------------------------------
# 3. parser routes/web.php
# --------------------------------------------------------------------------
def parse_routes(path):
    """Parser sederhana untuk routes/web.php (prefix + controller + closure)."""
    src = open(path, encoding="utf-8", errors="replace").read()
    routes = []
    closures = []
    i, n = 0, len(src)
    level = 0
    stack = []          # [(prefix, controller, middleware, brace_level)]

    def read_group_body(pos):
        """pos = indeks '{' pembuka; kembalikan (body, index setelah '}')."""
        depth, j = 1, pos + 1
        in_str = None
        while j < n and depth > 0:
            ch = src[j]
            if in_str:
                if ch == "\\":
                    j += 2
                    continue
                if ch == in_str:
                    in_str = None
            elif ch in "\"'":
                in_str = ch
            elif ch == "{":
                depth += 1
            elif ch == "}":
                depth -= 1
            j += 1
        return src[pos + 1:j - 1], j

    while i < n:
        ch = src[i]
        if ch == "/" and src.startswith("//", i):          # komentar baris
            i = src.find("\n", i)
            if i < 0:
                break
            continue
        if ch == "/" and src.startswith("/*", i):          # komentar blok
            i = src.find("*/", i)
            if i < 0:
                break
            i += 2
            continue
        if ch == "{":
            level += 1
            i += 1
            continue
        if ch == "}":
            level -= 1
            while stack and stack[-1][3] > level:
                stack.pop()
            i += 1
            continue
        if not src.startswith("Route::", i):
            i += 1
            continue
        start = i
        # baca sampai ';' atau '{' di kedalaman 0
        j, depth, in_str, end, opened = i + len("Route::"), 0, None, None, False
        while j < n:
            ch = src[j]
            if in_str:
                if ch == "\\":
                    j += 2
                    continue
                if ch == in_str:
                    in_str = None
                j += 1
                continue
            if ch in "\"'":
                in_str = ch
            elif ch == "(":
                depth += 1
            elif ch == ")":
                depth -= 1
            elif ch == "{" and depth <= 1:
                end, opened = j, True
                break
            elif ch == ";" and depth == 0:
                end, opened = j, False
                break
            j += 1
        if end is None:
            break
        stmt = src[start:end]
        norm = re.sub(r"\s+", "", stmt)

        if opened and "group(" in norm:
            pref = stack[-1][0] if stack else ""
            ctrl = stack[-1][1] if stack else None
            mw = stack[-1][2] if stack else ""
            pm = re.search(r"""prefix\(\s*['"]([^'"]+)['"]""", stmt)
            cm = re.search(r"controller\(\s*([A-Za-z0-9_\\]+)::class", stmt)
            mm = re.search(r"""middleware\(\s*\[?([^\]\)]+)\]?\s*\)""", stmt)
            if pm:
                pref = (pref + "/" + pm.group(1).strip("/")).strip("/")
            if cm:
                ctrl = cm.group(1).split("\\")[-1]
            if mm:
                mw = (mw + "," + mm.group(1).strip("'\" []")).strip(",")
            level += 1
            stack.append((pref, ctrl, mw, level))
            i = end + 1
            continue

        if opened:
            # closure: Route::get('x', function () { ... });
            body, after = read_group_body(end)
            verbs = re.findall(r"""Route::(\w+)\(\s*['"]([^'"]*)['"]""", stmt)
            for verb, rp in verbs:
                pref = stack[-1][0] if stack else ""
                closures.append({
                    "verb": verb.upper(),
                    "path": (pref + "/" + rp.strip("/")).strip("/"),
                    "controller": "Closure", "method": "__closure__",
                    "middleware": stack[-1][2] if stack else "",
                    "tables": sorted(set(
                        t for t in re.findall(
                            r"\b(?:from|join|into|update)\s+([a-zA-Z_][a-zA-Z0-9_#]*)",
                            body, re.I)
                        if t.lower() not in ("select", "where", "dual"))),
                    "line": src[:start].count("\n") + 1,
                })
            tail = src[after:after + 3]
            i = after + (1 if tail.startswith(";") else 0)
            continue

        # bentuk: Route::get('path', [Ctrl::class, 'method'])
        #        Route::get('path', 'Ctrl@method')
        #        Route::get('path', 'method')      <- controller dari grup
        for verb, rp, arg in re.findall(
                r"""Route::(\w+)\(\s*['"]([^'"]*)['"]\s*,\s*(\[[^\]]+\]|['"][^'"]+['"])""",
                stmt):
            ctrl, method = None, None
            am = re.search(r"""([A-Za-z0-9_\\]+)::class\s*,\s*['"](\w+)['"]""", arg)
            bm = re.match(r"""['"]([A-Za-z0-9_\\]+)@(\w+)['"]""", arg.strip())
            cm = re.match(r"""['"](\w+)['"]""", arg.strip())
            if am:
                ctrl, method = am.group(1).split("\\")[-1], am.group(2)
            elif bm:
                ctrl, method = bm.group(1).split("\\")[-1], bm.group(2)
            elif cm and stack and stack[-1][1]:
                ctrl, method = stack[-1][1], cm.group(1)
            if not (ctrl and method):
                continue
            pref = stack[-1][0] if stack else ""
            routes.append({
                "verb": verb.upper(),
                "path": (pref + "/" + rp.strip("/")).strip("/"),
                "controller": ctrl,
                "method": method,
                "middleware": stack[-1][2] if stack else "",
                "line": src[:start].count("\n") + 1,
            })
        i = end + 1
    return routes, closures


# --------------------------------------------------------------------------
# 4. frontend: endpoint yang dipanggil
# --------------------------------------------------------------------------
RE_APICALL = re.compile(
    r"""useApi\(\)\s*\.\s*(get|post|put|patch|delete)\s*\(\s*(`[^`]*`|'[^']*'|"[^"]*")""",
    re.I)
RE_NAMED = re.compile(r"""useApi\(\)\s*\.\s*(\w+)""")
# request langsung lewat axios instance di dalam composable/useApi
RE_GENERIC = re.compile(
    r"""\bapi\s*\.\s*(get|post|put|patch|delete)\s*\(\s*(`[^`]*`|'[^']*'|"[^"]*")""")


def norm_endpoint(raw):
    s = raw.strip("`'\"")
    s = re.sub(r"\$\{[^}]*\}", "", s)         # buang interpolasi
    s = s.split("?")[0]
    s = re.sub(r"\s+", "", s)
    s = s.strip("/")
    return s


def analyze_frontend(app_dir, label):
    out = []
    src_root = os.path.join(app_dir, "src")
    if not os.path.isdir(src_root):
        return out
    for dirpath, dirs, files in os.walk(src_root):
        dirs[:] = [d for d in dirs if d not in ("node_modules", "dist", ".git",
                                                "assets", "locales")]
        for fn in files:
            if not fn.endswith((".vue", ".ts", ".js")):
                continue
            path = os.path.join(dirpath, fn)
            try:
                text = open(path, encoding="utf-8", errors="replace").read()
            except Exception:
                continue
            eps = collections.Counter()
            for m in RE_APICALL.finditer(text):
                eps[(m.group(1).upper(), norm_endpoint(m.group(2)))] += 1
            for m in RE_GENERIC.finditer(text):
                eps[(m.group(1).upper(), norm_endpoint(m.group(2)))] += 1
            if eps:
                rel_dir = os.path.relpath(dirpath, src_root)
                parts = rel_dir.split(os.sep)
                if len(parts) >= 2 and parts[0] == "pages" and parts[1] == "module":
                    area = parts[2] if len(parts) > 2 else "module"
                elif len(parts) >= 2 and parts[0] == "pages":
                    area = "%s/%s" % (parts[0], parts[1])
                else:
                    area = parts[0]
                out.append({
                    "file": os.path.relpath(path, ROOT),
                    "app": label,
                    "area": area,
                    "endpoints": [{"verb": k[0], "path": k[1], "n": v}
                                  for k, v in eps.most_common()],
                })
    return out


# --------------------------------------------------------------------------
# 5. info tabel dari dump MySQL
# --------------------------------------------------------------------------
def analyze_dump(path):
    tables = collections.OrderedDict()
    name = None
    cur_cols = []
    for line in open(path, encoding="utf-8", errors="replace"):
        s = line.rstrip("\n")
        m = re.match(r"^-- ===== public\.(\S+)  \((\d+) baris, (\d+) kolom\)", s)
        if m:
            name, rows, ncols = m.group(1), int(m.group(2)), int(m.group(3))
            cur_cols = []
            tables[name] = {"rows": rows, "cols": [], "empty": False}
            continue
        m = re.match(r"^-- public\.(\S+) \(0 baris di sql.txt\)", s)
        if m:
            name = m.group(1)
            tables[name] = {"rows": 0, "cols": [], "empty": True, "placeholder": True}
            continue
        if name and line.startswith("  `"):
            cm = re.match(r"^  `([^`]+)` (.+?),?$", s)
            if cm:
                tables[name]["cols"].append({"name": cm.group(1),
                                             "type": cm.group(2).replace(" DEFAULT NULL", "")})
    return tables


def main():
    global DUMP_TABLES
    dump = analyze_dump(os.path.join(ROOT, "sql_mysql.sql"))
    DUMP_TABLES = set(dump)
    print("tabel dari dump:", len(DUMP_TABLES))

    models = extract_models()
    json.dump(models, open(os.path.join(OUT, "models.json"), "w"),
              indent=1, ensure_ascii=False)
    print("models:", len(models))

    controllers = []
    ctrl_root = os.path.join(BACKEND, "app", "Http", "Controllers")
    for dirpath, _dirs, files in os.walk(ctrl_root):
        for fn in sorted(files):
            if not fn.endswith(".php"):
                continue
            controllers.append(analyze_controller(os.path.join(dirpath, fn), models))
    json.dump(controllers, open(os.path.join(OUT, "controllers.json"), "w"),
              indent=1, ensure_ascii=False)
    print("controllers:", len(controllers))

    routes, closures = parse_routes(os.path.join(BACKEND, "routes", "web.php"))
    json.dump(routes, open(os.path.join(OUT, "routes.json"), "w"),
              indent=1, ensure_ascii=False)
    json.dump(closures, open(os.path.join(OUT, "closures.json"), "w"),
              indent=1, ensure_ascii=False)
    print("routes:", len(routes), "| closure routes:", len(closures))

    front = []
    front += analyze_frontend(os.path.join(ROOT, "frontend-v2"), "frontend-v2")
    front += analyze_frontend(os.path.join(ROOT, "kiosk"), "kiosk")
    front += analyze_frontend(os.path.join(ROOT, "e-reservasi"), "e-reservasi")
    front += analyze_frontend(os.path.join(ROOT, "viewer"), "viewer")
    json.dump(front, open(os.path.join(OUT, "frontend.json"), "w"),
              indent=1, ensure_ascii=False)
    print("frontend files:", len(front))

    json.dump(dump, open(os.path.join(OUT, "tables.json"), "w"),
              indent=1, ensure_ascii=False)
    print("tables:", len(dump))


if __name__ == "__main__":
    main()
