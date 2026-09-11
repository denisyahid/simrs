# Alat Analisa Database & Kode

Folder ini berisi skrip yang dipakai untuk menghasilkan **`LAPORAN_ANALISA_DATABASE.md`**
(analisa tabel database, controller backend, dan halaman frontend beserta koneksi/JOIN-nya).

## Cara menjalankan

```bash
# 1) ekstraksi data dari kode & dump (butuh sql_mysql.sql sudah ada)
python3 tools/analisa/extract.py

# 2) susun laporan markdown
python3 tools/analisa/build_report.py
```

Hasil antara (JSON) ditulis ke `tools/analisa/out/` dan tidak ikut di-commit karena
bisa dibuat ulang kapan saja (~6 detik).

## Yang dibaca oleh `extract.py`

| Sumber | Data yang diambil |
|---|---|
| `backend/app/Models/**/*.php` | `protected $table` → peta kelas model ke nama tabel |
| `backend/app/Http/Controllers/**/*.php` | `DB::table()`, `DB::select()`, raw SQL (`FROM`/`JOIN`), `Model::` → tabel & JOIN per method |
| `backend/routes/web.php` | prefix + `Route::controller()` → daftar rute `path → Controller@method` |
| `frontend-v2`, `kiosk`, `e-reservasi`, `viewer` | `useApi().get/post/put/delete()` → endpoint per halaman |
| `sql_mysql.sql` | nama tabel, kolom, tipe, jumlah baris contoh |

## Catatan

- Nama tabel dari raw SQL disaring: hanya diterima bila ada di dump **atau**
  mengikuti konvensi `_m`/`_t`/`_s`/`_d`, dan bukan nama CTE/kata kunci SQL.
- JOIN dari query builder dinormalisasi dari alias (`pd`, `ps`, `ru`, …) ke nama
  tabel asli memakai peta alias per method.
- JOIN dengan operator tidak valid (mis. `->join('t', 'a.x', 'b.y', 'c.z')`)
  diabaikan dari peta relasi dan dicatat sebagai temuan di laporan.
