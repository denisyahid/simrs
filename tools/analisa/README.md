# Alat Analisa Database, Kode & Repositori

Folder ini berisi skrip yang dipakai untuk menghasilkan dua laporan:

| Laporan | Skrip | Isi |
|---|---|---|
| `LAPORAN_ANALISA_DATABASE.md` | `extract.py` → `build_report.py` | tabel database, controller backend, halaman frontend, JOIN/koneksi |
| `ANALISA_SEMUA.md` | `scan_repo.py` → `build_semua.py` | analisa seluruh folder & berkas repositori (semua aplikasi) |

## Cara menjalankan

```bash
# --- laporan database & koneksi backend-frontend ---
python3 tools/analisa/extract.py        # ekstraksi data (butuh sql_mysql.sql)
python3 tools/analisa/build_report.py   # susun LAPORAN_ANALISA_DATABASE.md

# --- analisa seluruh folder & berkas ---
python3 tools/analisa/scan_repo.py      # pindai seluruh direktori -> out/scan.json
python3 tools/analisa/build_semua.py    # susun ANALISA_SEMUA.md
```

Hasil antara (JSON) ditulis ke `tools/analisa/out/` dan tidak ikut di-commit karena
bisa dibuat ulang kapan saja (masing-masing di bawah 3 detik).

## Yang dibaca oleh `extract.py`

| Sumber | Data yang diambil |
|---|---|
| `backend/app/Models/**/*.php` | `protected $table` → peta kelas model ke nama tabel |
| `backend/app/Http/Controllers/**/*.php` | `DB::table()`, `DB::select()`, raw SQL (`FROM`/`JOIN`), `Model::` → tabel & JOIN per method |
| `backend/routes/web.php` | prefix + `Route::controller()` → daftar rute `path → Controller@method` |
| `frontend-v2`, `kiosk`, `e-reservasi`, `viewer` | `useApi().get/post/put/delete()` → endpoint per halaman |
| `sql_mysql.sql` | nama tabel, kolom, tipe, jumlah baris contoh |

## Yang dibaca oleh `scan_repo.py` / `build_semua.py`

- Seluruh direktori & berkas di repo (kecuali `.git`, `node_modules`, `vendor`,
  `dist`, `build`, `coverage`, `__pycache__`), lengkap dengan ukuran dan jumlah baris.
- Berkas penanda teknologi (`package.json`, `composer.json`, `angular.json`, `Dockerfile`, …)
  dan isi konfigurasi penting (port, token, endpoint) untuk keperluan deskripsi per folder.
- Deskripsi kualitatif tiap folder ditulis manual di dalam `build_semua.py`
  (kamus `FOLDER_INFO` dan `ROOT_FILES`) dan dapat diperbarui tanpa mengubah skrip pemindaian.

## Catatan

- Nama tabel dari raw SQL disaring: hanya diterima bila ada di dump **atau**
  mengikuti konvensi `_m`/`_t`/`_s`/`_d`, dan bukan nama CTE/kata kunci SQL.
- JOIN dari query builder dinormalisasi dari alias (`pd`, `ps`, `ru`, …) ke nama
  tabel asli memakai peta alias per method.
- JOIN dengan operator tidak valid (mis. `->join('t', 'a.x', 'b.y', 'c.z')`)
  diabaikan dari peta relasi dan dicatat sebagai temuan di laporan.
- `build_semua.py` menuliskan **daftar seluruh folder (Lampiran A)** dan
  **daftar seluruh berkas (Lampiran B)** langsung ke dalam `ANALISA_SEMUA.md`.
