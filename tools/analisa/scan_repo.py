#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Pemindai seluruh folder & file repositori SIMRS untuk ANALISA_SEMUA.md.

Menghasilkan tools/analisa/out/scan.json berisi:
  - ringkasan per folder (jumlah file, ukuran, ekstensi, kedalaman)
  - daftar file terbesar
  - deteksi teknologi (package.json, composer.json, angular.json, Dockerfile, ...)
  - pohon direktori terbatas beberapa level
  - statistik baris kode per bahasa
"""

import collections
import json
import os
import re

ROOT = "/home/user/simrs"
OUT = os.path.join(ROOT, "tools", "analisa", "out")
os.makedirs(OUT, exist_ok=True)

SKIP_DIRS = {".git", "node_modules", "vendor", ".venv", "dist", "build", ".next",
             "coverage", "__pycache__", ".cache", "tmp"}
BINARY_EXT = {
    ".png", ".jpg", ".jpeg", ".gif", ".ico", ".webp", ".bmp", ".svg",
    ".woff", ".woff2", ".ttf", ".eot", ".otf", ".mp3", ".mp4", ".pdf",
    ".zip", ".rar", ".7z", ".gz", ".tar", ".xlsx", ".xls", ".doc", ".docx",
    ".jar", ".exe", ".dll", ".so", ".phar", ".lock", ".sqlite", ".mo", ".po",
}
LANG_BY_EXT = {
    ".php": "PHP", ".vue": "Vue", ".ts": "TypeScript", ".js": "JavaScript",
    ".mjs": "JavaScript", ".cjs": "JavaScript", ".json": "JSON", ".scss": "SCSS",
    ".css": "CSS", ".sass": "Sass", ".html": "HTML", ".md": "Markdown",
    ".sql": "SQL", ".txt": "Teks", ".xml": "XML", ".yml": "YAML", ".yaml": "YAML",
    ".sh": "Shell", ".bat": "Batch", ".ps1": "PowerShell", ".py": "Python",
    ".kt": "Kotlin", ".java": "Java", ".dart": "Dart", ".rb": "Ruby",
}


def is_text(path, ext):
    if ext in BINARY_EXT:
        return False
    try:
        with open(path, "rb") as fh:
            chunk = fh.read(4096)
    except OSError:
        return False
    if b"\x00" in chunk:
        return False
    return True


def count_lines(path):
    try:
        with open(path, "rb") as fh:
            return fh.read().count(b"\n") + 1
    except OSError:
        return 0


def main():
    dirs = collections.defaultdict(lambda: {"files": 0, "bytes": 0, "ext": collections.Counter(),
                                            "lines": 0})
    files = []
    tree = {}
    lang_lines = collections.Counter()
    lang_files = collections.Counter()

    for dirpath, dirnames, filenames in os.walk(ROOT):
        rel_dir = os.path.relpath(dirpath, ROOT)
        if rel_dir == ".":
            rel_dir = ""
        parts = rel_dir.split(os.sep) if rel_dir else []
        if any(p in SKIP_DIRS for p in parts):
            dirnames[:] = []
            continue
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]
        # catat direktori untuk pohon
        node = tree
        for p in parts:
            node = node.setdefault(p, {})

        for fn in filenames:
            path = os.path.join(dirpath, fn)
            try:
                size = os.path.getsize(path)
            except OSError:
                continue
            ext = os.path.splitext(fn)[1].lower()
            top = parts[0] if parts else "(root)"
            text = is_text(path, ext)
            lines = count_lines(path) if text and size < 8 * 1024 * 1024 else 0
            d = dirs[top]
            d["files"] += 1
            d["bytes"] += size
            d["ext"][ext or "(tanpa ekstensi)"] += 1
            d["lines"] += lines
            if text:
                lang = LANG_BY_EXT.get(ext)
                if lang:
                    lang_files[lang] += 1
                    lang_lines[lang] += lines
            files.append({"path": os.path.relpath(path, ROOT), "size": size,
                          "ext": ext, "lines": lines, "text": text, "top": top})

    biggest = sorted(files, key=lambda f: -f["size"])[:60]
    most_lines = sorted([f for f in files if f["lines"]], key=lambda f: -f["lines"])[:40]

    # deteksi teknologi: file penanda
    markers = collections.defaultdict(list)
    MARKER_NAMES = {
        "package.json": "Node.js / npm", "composer.json": "PHP / Composer",
        "angular.json": "Angular", "vite.config.ts": "Vite", "vite.config.js": "Vite",
        "Dockerfile": "Docker", "docker-compose.yml": "Docker Compose",
        "docker-compose.e2e.yml": "Docker Compose", "artisan": "Laravel",
        "phpunit.xml": "PHPUnit", "tsconfig.json": "TypeScript",
        "webpack.mix.js": "Laravel Mix", "karma.conf.js": "Karma",
        "protractor.conf.js": "Protractor", "cypress.json": "Cypress",
        "nginx.conf": "Nginx", "server.php": "PHP dev server",
        "yarn.lock": "Yarn", "package-lock.json": "npm lock",
        "requirements.txt": "Python", "go.mod": "Go", ".env.example": "Env contoh",
        "tailwind.config.js": "Tailwind", "bulma-css-vars.config.js": "Bulma",
        "firebase.json": "Firebase", "tsoa.json": "tsoa", "serve.sh": "Skrip serve",
    }
    for f in files:
        name = os.path.basename(f["path"])
        if name in MARKER_NAMES:
            markers[f["top"]].append((name, MARKER_NAMES[name], f["path"]))
        if name.startswith(".env"):
            markers[f["top"]].append((name, "Konfigurasi environment", f["path"]))

    data = {
        "total_files": len(files),
        "total_bytes": sum(f["size"] for f in files),
        "dirs": {k: {"files": v["files"], "bytes": v["bytes"],
                     "ext": dict(v["ext"].most_common(30)), "lines": v["lines"]}
                 for k, v in sorted(dirs.items(), key=lambda kv: -kv[1]["bytes"])},
        "biggest": biggest,
        "most_lines": most_lines,
        "lang_lines": dict(lang_lines),
        "lang_files": dict(lang_files),
        "markers": {k: v for k, v in markers.items()},
        "tree": tree,
    }
    json.dump(data, open(os.path.join(OUT, "scan.json"), "w", encoding="utf-8"),
              indent=1, ensure_ascii=False)
    print("folder terdeteksi:", len(data["dirs"]))
    for k, v in list(data["dirs"].items())[:15]:
        print("  %-14s %6d file  %8.1f MB" % (k, v["files"], v["bytes"] / 1048576))
    print("total file:", data["total_files"], "%.1f MB" % (data["total_bytes"] / 1048576))


if __name__ == "__main__":
    main()
