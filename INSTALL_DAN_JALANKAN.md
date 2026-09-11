# INSTALL & JALANKAN SIMRS — satu pintu `http://localhost/simrs/`

**Repositori:** `denisyahid/simrs` · **Tanggal:** 11 September 2026 · **Branch kerja:** `arena/01a08ea9-simrs`

Dokumen ini menjelaskan **cara memasang, menjalankan, dan menghubungkan seluruh aplikasi** di repositori
ini sehingga semua bisa dikunjungi dari **satu alamat: `http://localhost/simrs/`**.

Dokumen terkait di repositori:

| Dokumen | Isi |
|---|---|
| [`ANALISA_SEMUA.md`](ANALISA_SEMUA.md) | analisa lengkap 1.598 folder / 11.634 berkas (per folder, statistik, temuan) |
| [`LAPORAN_ANALISA_DATABASE.md`](LAPORAN_ANALISA_DATABASE.md) | 537 tabel, 7.657 JOIN, pemetaan controller ↔ tabel |
| [`LAPORAN_KONVERSI_SQL.md`](LAPORAN_KONVERSI_SQL.md) | proses `sql.txt` (PostgreSQL) → `sql_mysql.sql` (MySQL) + cara import |
| [`deploy/README.md`](deploy/README.md) | daftar berkas bantu di folder `deploy/` |

---

## 0. TL;DR — jalur tercepat (± 15 menit, tanpa nginx)

```bash
# 1) Database (MySQL/MariaDB) — lihat Langkah 2
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS rsud_malangbong DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p rsud_malangbong < sql_mysql.sql

# 2) Backend Laravel (port 8000) — lihat Langkah 3
cp deploy/env/backend.env.example backend/.env      # Windows: copy deploy\env\backend.env.example backend\.env
cd backend && composer install && php artisan key:generate && php artisan serve --host=0.0.0.0 --port=8000

# 3) Aplikasi petugas (port 2222) — lihat Langkah 4
cp ../deploy/env/frontend-v2.env.example .env       # Windows: copy ..\deploy\env\frontend-v2.env.example .env
npm install --legacy-peer-deps && VITE_BASE_PATH=/simrs/ npm run dev

# 4) Satu pintu /simrs (port 8080) — lihat Langkah 10
cd .. && node deploy/proxy/simrs-proxy.js
#   → buka  http://localhost:8080/simrs/
```

Untuk Windows PowerShell gunakan `deploy\scripts\start-all.ps1`; untuk Linux/macOS/WSL/Git Bash gunakan
`./deploy/scripts/start-all.sh` (lihat [Langkah 11](#11-langkah-11--menyalakanmematikan-semua-service-sekaligus)).

---

## 1. Arsitektur: aplikasi mana terhubung ke apa

### 1.1 Diagram keterkaitan

```
                          ┌────────────────────────  BROWSER  ─────────────────────────┐
                          │  http://localhost/simrs/  (satu pintu)                     │
                          └───────────────┬────────────────────────────────────────────┘
                                          │  reverse proxy  (deploy/proxy/simrs-proxy.js | nginx | Apache)
        ┌──────────────┬──────────────────┼───────────────────┬──────────────┬───────────────────┐
        ▼              ▼                  ▼                   ▼              ▼                   ▼
  /simrs/        /simrs/service/*   /simrs/socket.io   /simrs/viewer/  /simrs/kiosk/   /simrs/e-reservasi/
  frontend-v2      backend API         socket-server      viewer          kiosk            e-reservasi
  (Vue 3, :2222)   (Laravel, :8000)   (Node, :2530)      (Angular,:8202) (Angular,:4200)  (Angular, :4201)
        │              │   │  │            ▲                                                        │
        │  token       │   │  │            │ RabbitMQ (amqp://…, opsional)                          │
        └──────────────┘   │  └────────────┤                                                       │
                HTTP JSON  │               └── io.emit ke semua klien                                │
                           │                                                                       │
             ┌─────────────┴──────────────┬───────────────────────┐                               │
             ▼                            ▼                       ▼                               ▼
   MySQL/MariaDB (sql_mysql.sql)   MongoDB (opsional)      API eksternal BPJS/                eis/ :8001
   PostgreSQL (dump asli sql.txt)  jenssegers/mongodb      SATUSEHAT/Tilaka/IHS              (Laravel 7,
                                                            (bridging/*.php)                 MySQL langsung)
```

### 1.2 Daftar aplikasi, port, dan perannya

| # | Folder | Peran | Teknologi | Port dev | Titik masuk | Butuh |
|---|---|---|---|---|---|---|
| 1 | `backend/` | API utama + seluruh logika bisnis & cetak laporan | Laravel 8, PHP ^7.3\|^8.0 | **8000** | `artisan serve` / `serve.sh` · `routes/web.php` (2.458 rute, prefix `service`) | MySQL **atau** PostgreSQL, (opsional) MongoDB, RabbitMQ klien |
| 2 | `frontend-v2/` | Aplikasi petugas (38 modul, 1.232 halaman) | Vue 3.2 + Vite 3 + TS | **2222** | `src/entry-client.ts` → `src/app.ts` → `src/router.ts` | API backend |
| 3 | `viewer/` | Display antrian/TV + pemanggil antrean | Angular 11 + PrimeNG | **8202** | `src/main.ts`, `src/app/app-routing.module.ts` | API backend, socket.io |
| 4 | `kiosk/` | Anjungan mandiri pasien (check-in, SEP, antrean) | Angular 11 (Vuexy) | **4200** | `src/main.ts`, `app/main/module-v2/**` | API backend, printer QZ Tray |
| 5 | `e-reservasi/` | Reservasi online pasien | Angular 7 | **4201** | `src/main.ts` | API backend |
| 6 | `eis/` | Penampil EMR/EIS & pusat data | Laravel 7 (PHP 7.x) | **8001** | `routes/web.php`, `Auth\AuthController` | **MySQL langsung** (bukan PostgreSQL) |
| 7 | `socket-server/` | Notifikasi realtime (socket.io) | Node + uWebSockets.js | **2530** | `server.js`, `setting.js` | RabbitMQ (opsional saat start) |
| 8 | `load-test/` | 332 skrip uji beban k6 | k6 | — | `test/**/*.js`, `src/api.js` | backend |
| 9 | `tools/` | Skrip Python analisa repo & konversi SQL | Python 3 | — | `analisa/*.py`, `sql_txt_to_mysql.py` | — |
| 10 | `tm/` | Halaman PHP cetak EMR (berdiri sendiri) | PHP + PDO pgsql | — | `tm/index.php` | PostgreSQL |
| — | `deploy/` | Alat bantu: proxy, nginx/apache, env contoh, skrip | Node/NGINX/Apache | **8080** | `deploy/proxy/simrs-proxy.js` | — |

### 1.3 Peta berkas konfigurasi (yang harus Anda ubah/siapkan)

| Berkas | Isi penting | Dibaca oleh | Status di repo |
|---|---|---|---|
| `backend/.env` | `DB_*`, `JWT_KEY`, `APP_KEY`, `CAPTCHA_SECRETKEY` | Laravel backend | **belum ada** → buat dari `deploy/env/backend.env.example` |
| `backend/config/app.php` | default `JWT_KEY='TRANSINDO'`, `JWT_ALG='HS512'`, timezone `Asia/Jakarta` | `JWTAuth` middleware | ada |
| `backend/config/database.php` | koneksi `mysql`, `pgsql`, `mongodb` | Eloquent | ada |
| `backend/config/cors.php` | `allowed_origins: ['*']` untuk `api/*` & `service/*` | browser | ada |
| `frontend-v2/.env` | `VITE_API_BASE_URL`, `VITE_PROJECT`, `VITE_BASE_PATH`, `VITE_CAPTCHA_SITEKEY` | Vite (build/dev) | **belum ada** (di-ignore Git) → buat dari `deploy/env/frontend-v2.env.example` |
| `frontend-v2/vite.config.ts` | `base` = `VITE_BASE_PATH` (baru: dukung `/simrs/`) | Vite | ada |
| `frontend-v2/src/router.ts` | `createWebHistory(import.meta.env.BASE_URL)` | Vue Router | ada |
| `frontend-v2/src/composable/useApi.ts` | Axios `baseURL = VITE_API_BASE_URL`, header **`token`** | semua halaman | ada |
| `viewer/src/app/guard/config.ts` | `apiBackend`, `socketIO`, `socketPath` (+ deteksi `/simrs`) | `ApiService`, `SocketService` | ada (diperbarui) |
| `viewer/src/config_app.json` | `tokenKiosK`, `tokenReservasi`, `kodeRS` | viewer/kiosk | ada (**token lama, wajib rotasi**) |
| `viewer/src/app/service/api.service.ts` | `baseApi = 'service/medifirst2000/'` | viewer | ada |
| `kiosk/src/app/main/module/config.ts` | `apiBackend` (default `http://localhost:8701/service/`) | `HttpClient`/`HttpService` | ada (diperbarui) |
| `kiosk/src/environments/environment*.ts` | `apiUrl` (default `http://localhost:4000`) | sebagian komponen | ada |
| `e-reservasi/src/app/helper/config.ts` | `apiBackend` (default `http://localhost:8701/service/`) | `HttpClient` | ada (diperbarui) |
| `eis/.env` | `DB_*` (MySQL) | Laravel EIS | **belum ada** → buat dari `deploy/env/eis.env.example` |
| `socket-server/setting.js` | `rabbitMQHost` (**kredensial hardcoded**), `portSocket: 2530` | `server.js` | ada |

### 1.4 Pola komunikasi yang harus diikuti (dipakai semua frontend)

1. **Prefix `service/`** — semua API backend berada di `http://<backend>/service/...`
   (didefinisikan pada `routes/web.php` baris 907: `Route::middleware(['jwt.auth'])->prefix("service")`).
2. **Header `token`** — frontend mengirim JWT pada header `token` (bukan `Authorization`).
   Middleware `App\Http\Middleware\JWTAuth` juga menerima `x-token`, `X-AUTH-TOKEN`, `Authorization: Bearer`,
   atau parameter `token` pada body/query.
3. **Token 4 segmen** — middleware memotong segmen ke-4 sebagai `kdProfile`:
   `header.payload.signature.base64(kdProfile)`. Token hasil login backend sudah berbentuk demikian.
4. **Verifikasi tanda tangan** memakai `config('app.JWT_KEY')` (env `JWT_KEY`, default `TRANSINDO`)
   dengan algoritma `HS512` → **jangan ubah `JWT_KEY`** di tengah jalan, semua token lama akan mati.
5. **`kdprofile`** hampir selalu menjadi filter query di hampir semua controller (multi-instansi RS).
6. **CORS** sudah terbuka (`allowed_origins: ['*']` pada `config/cors.php`) untuk `api/*` dan `service/*`
   — sehingga frontend di port berapa pun boleh memanggil backend. Untuk produksi, persempit.

### 1.5 Alur login end-to-end (contoh: aplikasi petugas)

| # | Langkah | Berkas |
|---|---|---|
| 1 | Pengguna isi username/password & submit | `frontend-v2/src/pages/auth/login.vue` |
| 2 | `POST {VITE_API_BASE_URL}auth/login` body `{namaUser, kataSandi, token, tokenCapcay}` | `login.vue` baris ±122 |
| 3 | Rute `service/auth/login` (tanpa middleware) | `backend/routes/web.php:4358` |
| 4 | Cek captcha (bila `settingdatafixed_m.enabledCaptcha = 'true'`) & verifikasi password | `backend/app/Http/Controllers/Auth/AuthCtrl.php` |
| 5 | Ambil `loginuser_s`, `pegawai_m`, `profile_m`, `kelompokuser_s` → susun JWT + menu | `AuthCtrl::login` |
| 6 | Respons `{metaData:{code:200}, response:{data:{pegawai, kelompokUser, menu}, token}}` | — |
| 7 | Frontend simpan ke `stores/userSession` (localStorage `user_session`) | `src/stores/userSession.ts` |
| 8 | Semua permintaan berikutnya menyertakan header `token` | `src/composable/useApi.ts` |
| 9 | Backend validasi token + isi `kdProfile` per request | `app/Http/Middleware/JWTAuth.php` |

### 1.6 Endpoint penting (untuk uji cepat)

| Endpoint | Metode | Butuh token | Keterangan |
|---|---|---|---|
| `/` | GET | tidak | halaman `welcome` Laravel (uji backend hidup) |
| `/service/auth/login` | POST | tidak | login petugas → token |
| `/service/auth/pasien` | POST | tidak | login pasien (e-reservasi/kiosk) |
| `/service/general/menu/list-menu?idUser=…` | GET | ya | menu aplikasi |
| `/service/medifirst2000/...` | GET/POST | ya | seluruh rute kiosk `module-v2/**` |
| `/service/medifirst2000/report/cetak-*` | GET | ya | cetak bukti pendaftaran, antrean, SEP (dipakai kiosk & viewer) |
| `/service/signature`, `/service/signature-petugas`, `/service/cetak-kartu-pasien` | GET | tidak | cetak dokumen via `?key=` base64 (dipakai viewer/tm) |
| `/service/humas/info-bed-semua` | GET | tidak | informasi bed (viewer) |
| `/api/biller/generate-token`, `/api/biller/get-tagihan` | POST/GET | sebagian | integrasi pihak ketiga (middleware `jwt.auth.external`) |
| Socket.IO | — | — | `klinik.register.server`, `notif.*`, `kiosk.*` (lihat `socket-server/server.js`) |

---

## 2. Prasyarat

### 2.1 Ringkasan kebutuhan per aplikasi

| Aplikasi | Runtime | Versi disarankan | Database/Service lain |
|---|---|---|---|
| `backend/` | PHP + Composer | **PHP 8.0 / 8.1** (7.4 masih OK), Composer 2 | MySQL 8 / MariaDB 10.4 **atau** PostgreSQL 13+; MongoDB opsional |
| `frontend-v2/` | Node + npm | **Node 18 LTS** (16–18; hindari ≥20 bila pakai build lama) | — |
| `viewer/` | Node + npm | **Node 16** (Angular 11) | — |
| `kiosk/` | Node + npm | **Node 16** (Angular 11) | — |
| `e-reservasi/` | Node + npm | **Node 12 / 14** (Angular 7 + `node-sass` 4) | — |
| `eis/` | PHP + Composer | **PHP 7.4** (composer platform 7.2.5) | MySQL |
| `socket-server/` | Node | **Node 16 / 18** | RabbitMQ (opsional) |
| `deploy/proxy` | Node | Node ≥ 14 | — |
| `load-test/` | k6 | k6 ≥ 0.40 | backend |
| `tools/` | Python | Python 3.8+ | — |
| `tm/` | PHP | PHP 7.4/8.x + ekstensi `pdo_pgsql` | PostgreSQL |

> **Tips:** gunakan [nvm](https://github.com/nvm-sh/nvm) / [nvm-windows](https://github.com/coreybutler/nvm-windows)
> agar bisa berpindah versi Node per aplikasi (`nvm use 16`, `nvm use 18`, dst.).

### 2.2 Instalasi prasyarat per sistem operasi

**Windows**

| Kebutuhan | Cara tercepat |
|---|---|
| PHP 8.0/8.1 + Composer | `winget install OpenJS.NodeJS.LTS` untuk Node; PHP: XAMPP/Laragon, atau `winget install PHP.PHP.8.1` + `winget install Composer.Composer` |
| MySQL/MariaDB | XAMPP (sudah termasuk), Laragon, atau `winget install MariaDB.Server` |
| Node.js + nvm | `winget install CoreyButler.NVMforWindows`, lalu `nvm install 18.20.4 && nvm use 18.20.4` |
| Git | `winget install Git.Git` |
| k6 (opsional) | `winget install k6-io.k6` |

**Linux (Debian/Ubuntu)**

```bash
sudo apt update
sudo apt install -y php8.1-cli php8.1-mbstring php8.1-xml php8.1-zip php8.1-gd \
                    php8.1-mysql php8.1-pgsql php8.1-curl php8.1-bcmath unzip git curl
curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash - && sudo apt install -y nodejs
sudo apt install -y mariadb-server && sudo systemctl enable --now mariadb
```

**macOS**

```bash
brew install php@8.1 composer node@18 mariadb git
brew services start mariadb
```

**Ekstensi PHP**

| Ekstensi | Wajib? | Dipakai oleh |
|---|---|---|
| `pdo_mysql` / `pdo_pgsql` | ya (salah satu) | Eloquent |
| `mbstring`, `openssl`, `xml`, `ctype`, `json`, `tokenizer`, `fileinfo` | ya | Laravel |
| `gd` | ya (untuk cetak) | DomPDF, QR Code, tanda tangan |
| `zip` | ya | Maatwebsite Excel, impor/ekspor |
| `bcmath` | disarankan | perhitungan keuangan |
| `mongodb` | opsional | `jenssegers/mongodb` (log/EMR). Windows: DLL ada di `backend/extension/php7.4` & `php8.1` |
| `intl` | opsional | format tanggal/angka |

Cek cepat: `php -m` (pastikan daftar di atas muncul).

---

## 3. Langkah 1 — Ambil kode

```bash
git clone https://github.com/denisyahid/simrs.git
cd simrs
git checkout arena/01a08ea9-simrs      # branch kerja sesi ini
```

Ukuran repo besar (± 512 MB, ada video 96 MB di `viewer/`). Bila hanya ingin mencoba aplikasi petugas,
Anda tetap perlu seluruh repo minimal: `backend/`, `frontend-v2/`, `deploy/`, `sql_mysql.sql`.

---

## 4. Langkah 2 — Database

### 4.1 Opsi A (disarankan): MySQL / MariaDB

```bash
# 1. buat database
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS rsud_malangbong DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. naikkan max_allowed_packet (statement terbesar ± 1,2 MB)
#    Linux/MariaDB : /etc/mysql/my.cnf  →  [mysqld] max_allowed_packet=64M
#    Windows XAMPP : C:\xampp\mysql\bin\my.ini → [mysqld] max_allowed_packet=64M
#    lalu restart service MySQL.

# 3. import (42 MB, 537 tabel, ± 15.874 baris) — butuh 1–5 menit
mysql -u root -p rsud_malangbong < sql_mysql.sql

# 4. verifikasi
mysql -u root -p rsud_malangbong -e "SELECT COUNT(*) AS jumlah_tabel FROM information_schema.tables WHERE table_schema='rsud_malangbong';"
```

**Lewat phpMyAdmin (XAMPP/Laragon):** pilih database `rsud_malangbong` → tab **Import** →
pilih `sql_mysql.sql` → **Go**.

### 4.2 Opsi B: PostgreSQL

`sql.txt` **bukan** dump `pg_dump` yang bisa langsung di-restore — isinya hasil salin-tempel tabel HTML
Adminer (lihat [`LAPORAN_KONVERSI_SQL.md`](LAPORAN_KONVERSI_SQL.md)). Jadi:

* Untuk **mencoba aplikasi**, gunakan MySQL + `sql_mysql.sql` (opsi A).
* Untuk **PostgreSQL asli**, minta dump resmi dari server sumber, lalu:

```bash
sudo -u postgres createdb rsud_malangbong
psql -U postgres -d rsud_malangbong -f dump_resmi.sql
```

dan ubah `backend/.env`: `DB_CONNECTION=pgsql`, `DB_PORT=5432`.

### 4.3 Catatan penting kualitas data (dari laporan konversi)

1. Isi `sql_mysql.sql` **hanya contoh `LIMIT 100` baris per tabel** (119 tabel mentok 100 baris) —
   bukan backup penuh. Untuk data produksi, minta dump lengkap.
2. **Tidak ada PRIMARY KEY / FOREIGN KEY / INDEX** dan semua kolom `NULL`-able (karena tipe & kunci
   tidak tersedia di sumber). Aplikasi tetap jalan, tetapi:
   * tambahkan PK pada tabel yang sering di-`UPDATE`/`DELETE` (mis. `*_t` transaksi),
   * tambahkan index pada kolom JOIN tersering (daftar di `LAPORAN_ANALISA_DATABASE.md` §7.2).
3. 169 tabel kosong hanya berisi kolom placeholder `id` — perlu `ALTER TABLE` bila dipakai fitur terkait.
4. 23 tabel yang dipakai controller **tidak ada** di dump → fitur terkait akan error sampai tabel dibuat.

### 4.4 Tabel minimum untuk uji login

| Tabel | Isi yang dibutuhkan |
|---|---|
| `loginuser_s` | `namauser`, `katasandi` (hash bcrypt), `statusenabled=true`, `objectkelompokuserfk`, `objectpegawaifk`, `kdprofile` |
| `kelompokuser_s` | `kelompokuser` + `menu` (menentukan halaman awal setelah login) |
| `pegawai_m`, `profile_m` | identitas pegawai & profil instansi (`kdprofile`) |

> Bila login gagal padahal data ada: pastikan `katasandi` tersimpan sebagai **hash bcrypt**
> (`IS_PASSWORD_HASH=true`), bukan teks biasa. Bila perlu, buat hash baru:
> `php -r "echo password_hash('rahasia123', PASSWORD_BCRYPT);"`.

---

## 5. Langkah 3 — Backend API Laravel (port 8000)

```bash
cd backend

# 1) dependency PHP
composer install --no-interaction --prefer-dist
#    (bila memori kurang)   COMPOSER_MEMORY_LIMIT=-1 composer install

# 2) berkas lingkungan
cp ../deploy/env/backend.env.example .env         # Windows: copy ..\deploy\env\backend.env.example .env
#    sesuaikan DB_HOST / DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD

# 3) kunci aplikasi + hak tulis
php artisan key:generate
chmod -R 775 storage bootstrap/cache              # Windows: cukup pastikan tidak read-only

# 4) bersihkan cache (bila pernah jalan dengan config lama)
php artisan config:clear && php artisan cache:clear && php artisan route:clear

# 5) jalankan
php artisan serve --host=0.0.0.0 --port=8000
#    atau:  ./serve.sh   (isinya persis perintah di atas)
```

Uji:

```bash
curl -i http://localhost:8000/                                   # 200 → tampilan welcome Laravel
curl -i -X POST http://localhost:8000/service/auth/login \
     -H "Content-Type: application/json" \
     -d '{"namaUser":"admin","kataSandi":"rahasia123"}'           # 200 + token, atau 400 pesan validasi
```

**Catatan penting**

* `php artisan` **wajib** dijalankan dari folder `backend/` (butuh `.env` + `vendor/`).
* Rute `/service/*` dilindungi `jwt.auth`; tanpa token akan dijawab `401 {"metaData":{"message":"Token Tidak tersedia"}}`.
* Bila muncul `SQLSTATE[HY000] [2002]` → MySQL belum jalan / `DB_HOST` salah.
* Bila muncul `could not find driver` → ekstensi `pdo_mysql`/`pdo_pgsql` belum aktif di `php.ini`.
* Bila hanya ingin uji tanpa MongoDB: biarkan `DATABASE_URL_MONGO` & `DB_*_MONGO` kosong. Fitur yang
  benar-benar membutuhkan Mongo (log JSON, sebagian EMR) baru akan error saat dipakai.
* Untuk host selain localhost, set `APP_URL=http://localhost/simrs` agar URL yang dihasilkan konsisten.

---

## 6. Langkah 4 — Aplikasi petugas `frontend-v2` (port 2222)

```bash
cd frontend-v2
cp ../deploy/env/frontend-v2.env.example .env     # Windows: copy ..\deploy\env\frontend-v2.env.example .env
# isi utama: VITE_API_BASE_URL=/simrs/service/  (relatif → bebas CORS, cocok untuk /simrs)

npm install --legacy-peer-deps --no-audit --no-fund    # ± 1.500 paket, 3–10 menit

# DEVELOPMENT (diakses langsung)
npm run dev                                            # http://localhost:2222/
# DEVELOPMENT (diakses lewat /simrs/)
VITE_BASE_PATH=/simrs/ npm run dev                     # Windows PowerShell:
#   $env:VITE_BASE_PATH="/simrs/"; npm run dev
```

Uji: `http://localhost:2222/` → halaman login "Transmedic". Login memerlukan backend + data user di database.

Bila `npm install` gagal pada paket native (`sharp`, `imagemin-*`) di jaringan tertutup:

```bash
npm install --legacy-peer-deps --ignore-scripts       # lewati skrip native
# lalu jalankan build/dev hanya bila tidak memakai fitur optimasi gambar
```

**Catatan**

* Semua variabel **harus** berawalan `VITE_` agar terbaca di browser; ubah `.env` → Vite otomatis restart.
* Tidak ada `.env` di repo (di-ignore) — wajib dibuat, jika tidak `VITE_API_BASE_URL` menjadi `undefined`
  dan permintaan akan diarahkan ke origin yang sama (`/auth/login`) → 404.
* Halaman awal setelah login ditentukan `kelompokUser.menu` (lihat `src/pages/auth/login.vue`).
* Halaman/berkas sisa template Vuero (`documentation/`, `pages-quickstart`, `*-old*.vue`) tidak dipakai SIMRS.

---

## 7. Langkah 5 — Viewer antrian/TV (port 8202)

```bash
cd viewer
npm install --legacy-peer-deps --no-audit --no-fund

# Windows (script bawaan sudah menyetel --openssl-legacy-provider)
npm start -- --port 8202 --host 0.0.0.0 --base-href /simrs/viewer/
# Linux/macOS (kata 'set' pada script npm hanya berlaku di Windows)
NODE_OPTIONS=--openssl-legacy-provider npx ng serve --port 8202 --host 0.0.0.0 --base-href /simrs/viewer/
```

Alamat API ditentukan `src/app/guard/config.ts`:

* diakses lewat `http://localhost/simrs/viewer/` → otomatis memakai `http://localhost/simrs/` + socket `/simrs/socket.io`;
* diakses langsung (`http://localhost:8202/`) → `http://localhost:8901/` (port backend lama di server asal —
  ubah ke `http://localhost:8000/` bila ingin memakai backend lokal);
* produksi (root) → `/` dan socket di origin yang sama.

Halaman penting: `/viewer`, `/caller`, `/viewer-poli/:ruanganid`, `/viewer-operasi`, `/viewer-kamar`, `/login`.
Token kiosk/viewer untuk beberapa endpoint baca ada di `src/config_app.json` (**token lama** — ganti bila
backend Anda memakai `JWT_KEY` berbeda).

---

## 8. Langkah 6 — Kiosk mandiri (port 4200)

```bash
cd kiosk
npm install --legacy-peer-deps --no-audit --no-fund
npx ng serve --host 0.0.0.0 --port 4200 --base-href /simrs/kiosk/
# produksi: npm run build:prod -- --base-href /simrs/kiosk/ --output-path dist/kiosk
```

* Alamat API: `src/app/main/module/config.ts` → deteksi `/simrs` **atau** `http://localhost:8701/service/`
  (port lama). Ubah ke `http://localhost:8000/service/` bila memakai backend lokal tanpa sub-path.
* `src/environments/environment.ts` masih memuat `apiUrl: 'http://localhost:4000'`; dipakai sebagian
  komponen lama — sesuaikan bila fitur tersebut dipakai.
* Kiosk memakai printer termal via **QZ Tray** (`qz-tray`, `backend/public/qz`) — izinkan sertifikat
  QZ Tray di browser kios.
* Token fallback pada `HttpService.token` dan `HttpClient.createAuthorizationHeader` bersifat *hardcoded*
  → ganti dengan token hasil login bila backend Anda memakai `JWT_KEY` lain.

---

## 9. Langkah 7 — E-Reservasi (port 4201)

```bash
cd e-reservasi
nvm use 12 || nvm use 14         # Angular 7 + node-sass 4: pakai Node 12/14
npm install --legacy-peer-deps --no-audit --no-fund
npx ng serve --host 0.0.0.0 --port 4201 --base-href /simrs/e-reservasi/
```

* Alamat API: `src/app/helper/config.ts` (deteksi `/simrs` → `http://localhost/simrs/service/`).
* Bila `node-sass` gagal dibangun di Node ≥16: pindah ke Node 12/14 atau ganti ke `sass` (dart-sass)
  dan sesuaikan `angular.json`.
* Endpoint login pasien: `POST /service/auth/pasien` (`AuthCtrl::loginPasien2`).

---

## 10. Langkah 8 — EIS / penampil EMR (port 8001)

```bash
cd eis
composer install --no-interaction --prefer-dist      # butuh PHP 7.4 (platform 7.2.5)
cp ../deploy/env/eis.env.example .env                # Windows: copy ..\deploy\env\eis.env.example .env
php artisan key:generate
php artisan serve --host=0.0.0.0 --port=8001
```

* **Berbeda dari backend**, `eis/` memakai **MySQL langsung** ke database yang sama.
* Halaman login: `http://localhost:8001/` → `routes/web.php` (`Auth\AuthController@show`, POST `/logins`).
* Untuk dipakai lewat sub-path, set `APP_URL=http://localhost/simrs/eis` dan buka `http://localhost/simrs/eis/`.

---

## 11. Langkah 9 — Server notifikasi realtime `socket-server` (port 2530)

```bash
cd socket-server
npm install --no-audit --no-fund        # menarik uWebSockets.js dari GitHub (butuh akses internet)
node server.js                          # mendengarkan port 2530
```

* Port diatur di `setting.js` (`portSocket: 2530`) — sekaligus menyimpan `rabbitMQHost`
  `amqp://rsab:rsab@127.0.0.1` (**kredensial contoh, wajib diganti**).
* **RabbitMQ opsional saat start**: koneksi dibuat/di-retry hanya ketika event `klinik.register.*`
  dipakai (`rabbitHole.js`), jadi server tetap hidup tanpa RabbitMQ.
* Bila ingin uji penuh:

```bash
docker run -d --name rabbit -p 5672:5672 -p 15672:15672 rabbitmq:3-management
#   panel: http://localhost:15672 (guest/guest)
#   lalu ubah setting.js → amqp://guest:guest@127.0.0.1
```
* Frontend memakai socket dengan path `/socket.io` (default) atau `/simrs/socket.io` bila lewat sub-path
  (lihat `viewer/src/app/service/socket.service.ts`).

---

## 12. Langkah 10 — Satu pintu: `http://localhost/simrs/`

Ada tiga cara. **Opsi A** paling mudah dan tidak butuh instalasi tambahan.

### 12.1 Opsi A — Proxy Node (rekomendasi, semua OS)

```bash
node deploy/proxy/simrs-proxy.js
#  → http://localhost:8080/simrs/        (daftar aplikasi + status service)
#  → http://localhost:8080/simrs/health  (status JSON semua port)
```

Bila ingin benar-benar `http://localhost/simrs/` (port 80):

```bash
sudo PORT=80 node deploy/proxy/simrs-proxy.js          # Linux/macOS
# Windows (jalankan PowerShell sebagai Administrator):
#   $env:PORT="80"; node deploy\proxy\simrs-proxy.js
```

Proxy ini juga menampilkan **halaman bantuan berisi perintah start** ketika service tujuan belum aktif —
jadi tidak ada lagi pesan `ERR_CONNECTION_REFUSED` yang membingungkan.

### 12.2 Opsi B — nginx (mendekati produksi)

```bash
# Linux/macOS
sudo cp deploy/nginx/simrs.conf /etc/nginx/conf.d/simrs.conf     # Ubuntu: sites-enabled
sudo nginx -t && sudo systemctl reload nginx

# Windows (nginx.exe)
copy deploy\nginx\simrs.conf C:\nginx\conf\conf.d\simrs.conf
# tambahkan `include conf.d/*.conf;` di dalam blok http pada C:\nginx\conf\nginx.conf
nginx.exe -s reload
```

Sebelum dipakai, ubah baris `set $simrs_root …` di dalam `deploy/nginx/simrs.conf` agar menunjuk
folder repo Anda. Konfigurasi sudah memuat: proxy `service/`, `api/`, `socket.io`, `eis`, empat SPA statis,
alias berkas publik backend, `client_max_body_size 64m`, dan WebSocket upgrade (HMR + socket.io).

### 12.3 Opsi C — Apache / XAMPP

1. Aktifkan modul di `C:\xampp\apache\conf\httpd.conf`:
   `proxy_module`, `proxy_http_module`, `proxy_wstunnel_module`, `rewrite_module`.
2. Salin `deploy/apache/simrs.conf` → `C:\xampp\apache\conf\extra\httpd-simrs.conf`,
   lalu tambahkan `Include conf/extra/httpd-simrs.conf` di akhir `httpd.conf`.
3. Sesuaikan `Define SIMRS_ROOT`/`SIMRS_WWW`, restart Apache, buka `http://localhost/simrs/`.

> Jika Apache versi Anda memetakan `/simrs/service` ke berkas statis (bentrok `Alias`), letakkan blok
> `<Location>` **sebelum** blok `Alias` atau gunakan Opsi A.

### 12.4 Build produksi (agar tidak perlu dev server)

```bash
# Aplikasi petugas  → frontend-v2/dist           → http://localhost/simrs/
cd frontend-v2 && VITE_BASE_PATH=/simrs/ npm run build

# Viewer            → viewer/dist/viewer         → http://localhost/simrs/viewer/
cd viewer && npx ng build --prod --base-href /simrs/viewer/ --output-path dist/viewer

# Kiosk             → kiosk/dist/kiosk           → http://localhost/simrs/kiosk/
cd kiosk && npm run build:prod -- --base-href /simrs/kiosk/ --output-path dist/kiosk

# E-Reservasi       → e-reservasi/dist           → http://localhost/simrs/e-reservasi/
cd e-reservasi && npx ng build --prod --base-href /simrs/e-reservasi/ --output-path dist
```

### 12.5 Peta URL akhir

| URL | Aplikasi | Upstream | Catatan |
|---|---|---|---|
| `http://localhost/simrs/` | Aplikasi petugas | 2222 (dev) / `frontend-v2/dist` | base `/simrs/` |
| `http://localhost/simrs/service/…` | API backend | 8000 | header `token` |
| `http://localhost/simrs/api/…` | API biller/eksternal | 8000 | |
| `http://localhost/simrs/socket.io` | Notifikasi realtime | 2530 | WebSocket |
| `http://localhost/simrs/eis/` | Penampil EMR/EIS | 8001 | MySQL |
| `http://localhost/simrs/viewer/` | Display antrian | 8202 / `viewer/dist/viewer` | |
| `http://localhost/simrs/kiosk/` | Kiosk mandiri | 4200 / `kiosk/dist/kiosk` | |
| `http://localhost/simrs/e-reservasi/` | Reservasi online | 4201 / `e-reservasi/dist` | |
| `http://localhost/simrs/health` | Status service | — | hanya Node proxy |

---

## 13. Langkah 11 — Utilitas (opsional)

```bash
# Laporan analisa repo & database (Python 3)
python3 tools/analisa/scan_repo.py
python3 tools/analisa/extract.py
python3 tools/analisa/build_semua.py        # → ANALISA_SEMUA.md
python3 tools/sql_txt_to_mysql.py           # konversi ulang sql.txt → sql_mysql.sql

# Cetak EMR berdiri sendiri (PHP + PostgreSQL)
php -S 127.0.0.1:8090 -t tm                 # tm/index.php dst (kredensial DB ada di dalam berkas!)

# Uji beban (k6)
k6 run load-test/test/generate-token.js --vus 5 --duration 30s
TEST_BASE_URL=http://localhost:8000 k6 run load-test/test/index.js
```

---

## 14. Langkah 12 — Menyalakan/mematikan semua service sekaligus

**Linux / macOS / WSL / Git Bash**

```bash
./deploy/scripts/start-all.sh                  # semua service
./deploy/scripts/start-all.sh backend frontend # pilih sebagian
./deploy/scripts/stop-all.sh
```

**Windows PowerShell**

```powershell
powershell -ExecutionPolicy Bypass -File deploy\scripts\start-all.ps1
powershell -ExecutionPolicy Bypass -File deploy\scripts\start-all.ps1 -Services backend,frontend,proxy
powershell -ExecutionPolicy Bypass -File deploy\scripts\stop-all.ps1
```

Log setiap service: `.run/logs/<service>.log` · PID: `.run/pids/<service>.pid`.
Skrip otomatis menjalankan `composer install`/`npm install` bila `vendor/`/`node_modules/` belum ada,
dan melewati service yang runtime-nya tidak terpasang.

---

## 15. Verifikasi menyeluruh

| # | Yang diperiksa | Perintah | Hasil yang benar |
|---|---|---|---|
| 1 | MySQL hidup & terisi | `mysql -u root -p -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='rsud_malangbong';"` | ≈ 537 |
| 2 | Backend hidup | `curl -I http://localhost:8000/` | `200 OK` |
| 3 | Login backend | `curl -X POST http://localhost:8000/service/auth/login -H "Content-Type: application/json" -d '{"namaUser":"admin","kataSandi":"..."}'` | `200` + `token` |
| 4 | Token ditolak tanpa header | `curl -i http://localhost:8000/service/general/menu/list-menu?idUser=1` | `401` "Token Tidak tersedia" |
| 5 | Frontend hidup | `curl -I http://localhost:2222/simrs/` atau `http://localhost:2222/` | `200 OK` |
| 6 | Proxy & semua sub-path | `curl -s http://localhost:8080/simrs/health` | JSON `services[]` dengan `up: true/false` |
| 7 | Viewer | `http://localhost:8202/` (atau `/simrs/viewer/`) | halaman login viewer |
| 8 | Kiosk | `http://localhost:4200/` (atau `/simrs/kiosk/`) | halaman kiosk |
| 9 | E-Reservasi | `http://localhost:4201/` (atau `/simrs/e-reservasi/`) | halaman reservasi |
| 10 | EIS | `http://localhost:8001/` (atau `/simrs/eis/`) | halaman login EIS |
| 11 | Socket | `curl -s "http://localhost:2530/socket.io/?EIO=4&transport=polling"` | balasan `0{"sid":…}` |

Checklist manual di browser (setelah `http://localhost/simrs/` terbuka):

- [ ] Halaman login tampil dengan logo/tema, tanpa error di console (F12 → Console).
- [ ] Login berhasil dan halaman awal = menu pada `kelompokuser_s.menu`.
- [ ] Menu Master Data terbuka dan datanya terisi (bukti DB terhubung).
- [ ] Cetak dokumen (mis. kartu pasien) menghasilkan PDF.
- [ ] Badge notifikasi berubah saat ada event socket (butuh socket-server + RabbitMQ).

---

## 16. Troubleshooting

| Gejala | Penyebab umum | Solusi |
|---|---|---|
| `php artisan` → `Failed to open stream: No such file .env` | `backend/.env` belum dibuat | `cp deploy/env/backend.env.example backend/.env` lalu `php artisan key:generate` |
| `could not find driver` | ekstensi `pdo_mysql`/`pdo_pgsql` nonaktif | aktifkan di `php.ini`, restart web server |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL belum jalan / port salah | start MySQL; cek `DB_HOST`, `DB_PORT` |
| `Class "MongoDB\Driver\Manager" not found` | ekstensi `mongodb` belum ada | pasang ekstensi (DLL tersedia di `backend/extension/`) atau kosongkan setting `*_MONGO` |
| Login selalu `400 INVALID_CAPTCHA` / captcha tidak muncul | `settingdatafixed_m.enabledCaptcha='true'` | set ke `false`, atau isi `VITE_CAPTCHA_SITEKEY` + `CAPTCHA_SECRETKEY` |
| `401 Sesi anda telah berakhir` | token lama/`JWT_KEY` berbeda | login ulang; pastikan `JWT_KEY` sama dengan saat token dibuat |
| Frontend: semua request `/auth/login` 404 | `frontend-v2/.env` belum ada / nilai salah | buat `.env` (`VITE_API_BASE_URL=/simrs/service/`), restart `npm run dev` |
| Browser: CORS `No 'Access-Control-Allow-Origin'` | memanggil backend langsung dari origin lain | pakai `VITE_API_BASE_URL=/simrs/service/` (relatif) atau biarkan `config/cors.php` `['*']` |
| Aset 404 saat diakses di `/simrs/` | build/dev tanpa `VITE_BASE_PATH=/simrs/` | jalankan `VITE_BASE_PATH=/simrs/ npm run dev` (atau build ulang) |
| `npm install` gagal `sharp`/`libvips`/`imagemin` | tidak ada akses ke GitHub releases | `npm install --legacy-peer-deps --ignore-scripts`, lalu jalankan dev dengan `SKIP_IMAGE_PLUGINS=true` (plugin gambar dilewati otomatis bila binary tidak ditemukan) |
| Error saat load `vite.config.ts`: `Something went wrong installing the "sharp" module` | binary sharp tidak terpasang | `SKIP_IMAGE_PLUGINS=true npm run dev` |
| Angular `error:0308010C:digital envelope routines::unsupported` | Node ≥17 + webpack lama | `NODE_OPTIONS=--openssl-legacy-provider` atau pakai Node 14/16 |
| `node-sass` gagal build | Node terlalu baru | Node 12/14 untuk `e-reservasi` |
| `npm start` viewer gagal di Linux/macOS | script memakai `set VAR=...` (sintaks Windows) | jalankan `NODE_OPTIONS=--openssl-legacy-provider npx ng serve …` |
| `phpMyAdmin: #1153 Got a packet bigger than max_allowed_packet` | nilai default 1 MB | set `max_allowed_packet=64M`, restart MySQL |
| Tabel hilang / fitur error 500 | 23 tabel tidak ada di dump | lihat `LAPORAN_ANALISA_DATABASE.md` §10.3, buat tabel manual |
| `phpinfo`/token sensitif terlihat | `backend/public/info.php`, `viewer/src/config_app.json` | hapus/rotasi sebelum dipakai publik (§18) |
| Port sudah dipakai | proses lama masih hidup | `./deploy/scripts/stop-all.sh` / `stop-all.ps1`, atau ganti port |

---

## 17. Perubahan kode yang sudah disiapkan di branch ini

Agar `http://localhost/simrs/` benar-benar bekerja tanpa mengubah kode lagi, empat berkas disesuaikan
(backward compatible — tanpa env/setelan khusus, aplikasi tetap berjalan seperti sebelumnya):

| Berkas | Perubahan |
|---|---|
| `frontend-v2/vite.config.ts` | `base` diambil dari env `VITE_BASE_PATH` (default `/`); `VitePWA.base` ikut menyesuaikan; plugin gambar (`imagetools`/sharp + `imagemin`) dimuat **malas** dan otomatis dilewati bila binary native tidak ada — bisa juga dipaksa dengan `SKIP_IMAGE_PLUGINS=true` |
| `frontend-v2/src/router.ts` | `createWebHistory(import.meta.env.BASE_URL)` |
| `viewer/src/app/guard/config.ts` + `service/socket.service.ts` | deteksi sub-path `/simrs`, `socketPath` untuk socket.io |
| `kiosk/src/app/main/module/config.ts`, `e-reservasi/src/app/helper/config.ts` | deteksi sub-path `/simrs` → `http://<host>/simrs/service/` |

Opsional (dapat di-set dari `index.html` bila ingin memaksa base path):

```html
<script>window.SIMRS_BASE_PATH = '/simrs';</script>
```

---

## 18. Keamanan sebelum dipakai/dibagikan

| Lokasi | Temuan | Tindakan |
|---|---|---|
| `viewer/src/config_app.json` | token JWT kiosk/reservasi tertulis di berkas | rotasi token, pindahkan ke env/`localStorage` hasil login |
| `socket-server/setting.js` | `amqp://rsab:rsab@127.0.0.1` | ganti kredensial RabbitMQ, ambil dari env |
| `backend/app/Http/Controllers/Bridging/BridgingBPJSCtrl.php` | `$secretKey` BPJS hardcoded | pindahkan ke `.env`, rotasi |
| `backend/app/Http/Controllers/Bridging/TilakaCtrl.php` | client id/secret eKYC | pindahkan ke `.env`, rotasi |
| `kiosk/src/app/main/module/HttpClient.ts`, `httpService.ts` | token fallback hardcoded | hapus, gunakan token hasil login |
| `tm/*.php` | kredensial PostgreSQL produksi di dalam kode | jangan dipakai apa adanya; parameterkan |
| `backend/public/info.php` | `phpinfo()` | hapus di produksi |
| `e-reservasi/src/upload.php`, `viewer/src/upload.php` | endpoint unggah contoh | hapus atau beri validasi & hak akses |
| `backend/config/cors.php` | `allowed_origins: ['*']` | persempit ke domain resmi |

---

## 19. Perintah harian (cheat sheet)

```bash
# Backend
cd backend && ./serve.sh                                   # port 8000
php artisan config:clear && php artisan cache:clear         # setelah ubah .env
tail -f storage/logs/laravel.log                            # lihat error

# Frontend petugas
cd frontend-v2 && VITE_BASE_PATH=/simrs/ npm run dev        # port 2222
cd frontend-v2 && VITE_BASE_PATH=/simrs/ npm run build      # ke dist/

# Angular (viewer/kiosk/e-reservasi)
npx ng serve --port 8202 --base-href /simrs/viewer/
npx ng build --prod --base-href /simrs/kiosk/ --output-path dist/kiosk

# Satu pintu
node deploy/proxy/simrs-proxy.js                            # port 8080
curl -s http://localhost:8080/simrs/health | python3 -m json.tool

# Semua service
./deploy/scripts/start-all.sh && ./deploy/scripts/stop-all.sh
```

---

## Lampiran A — Daftar variabel lingkungan

**`backend/.env`** (lihat `deploy/env/backend.env.example`)

| Variabel | Default di kode | Keterangan |
|---|---|---|
| `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_KEY`, `APP_URL` | — | standar Laravel; `APP_KEY` diisi `php artisan key:generate` |
| `DB_CONNECTION` | `mysql` | `mysql` (hasil konversi) atau `pgsql` (server asal) |
| `DB_HOST`/`DB_PORT`/`DB_DATABASE`/`DB_USERNAME`/`DB_PASSWORD` | `127.0.0.1` / `3306` / `forge` | database utama |
| `DATABASE_URL_MONGO`, `DB_HOST_MONGO`, `DB_PORT_MONGO`, `DB_DATABASE_MONGO`, `DB_USERNAME_MONGO`, `DB_PASSWORD_MONGO` | `apps.transmedic.co.id:2255` (default di `config/database.php`) | **kosongkan** bila tidak ada MongoDB |
| `JWT_KEY` | `TRANSINDO` | **jangan diubah** bila ada token beredar |
| `JWT_ALG` | `HS512` | harus sama dengan saat token dibuat |
| `JWT_EXPIRED_MINUTE_BPJS` | `10` | masa berlaku token BPJS |
| `IS_PASSWORD_HASH` | `true` | `true` = `katasandi` disimpan sebagai bcrypt |
| `CAPTCHA_SECRETKEY` | `6LeFaW4pAAAAAF…` | samakan dengan `VITE_CAPTCHA_SITEKEY` bila captcha diaktifkan |
| `CACHE_DRIVER`, `SESSION_DRIVER` | `file` | `redis` bila memakai Redis |
| `QUEUE_CONNECTION` | `sync` | `redis`/`database` bila memakai antrian |

**`frontend-v2/.env`** (lihat `deploy/env/frontend-v2.env.example`)

| Variabel | Contoh | Keterangan |
|---|---|---|
| `VITE_API_BASE_URL` | `/simrs/service/` | **wajib**; relatif = bebas CORS |
| `VITE_PROJECT` | `SIMRS` | judul halaman |
| `VITE_FOOTER`, `VITE_FOOTER_BY` | `Transmedic` | footer |
| `VITE_BASE_PATH` | `/simrs/` | dibaca `vite.config.ts` saat dev/build |
| `VITE_CAPTCHA_SITEKEY` | — | situs reCAPTCHA/Turnstile |
| `VITE_MAPBOX_ACCESS_TOKEN` | — | peta pada dashboard (opsional) |

**Angular** — `viewer/src/app/guard/config.ts`, `kiosk/src/app/main/module/config.ts`,
`e-reservasi/src/app/helper/config.ts` (alamat API), `kiosk/src/environments/environment.ts` (`apiUrl`),
`socket-server/setting.js` (`rabbitMQHost`, `portSocket`), `viewer/src/config_app.json` (token).

---

## Lampiran B — Berkas penting per aplikasi

| Aplikasi | Entry point | Konfigurasi | API/Socket |
|---|---|---|---|
| `backend/` | `public/index.php`, `routes/web.php`, `routes/api.php` | `.env`, `config/*.php`, `app/Http/Middleware/JWTAuth.php` | — |
| `frontend-v2/` | `index.html` → `src/entry-client.ts` → `src/app.ts` → `src/router.ts` | `.env`, `vite.config.ts` | `src/composable/useApi.ts` |
| `viewer/` | `src/main.ts` → `app.module.ts` → `app-routing.module.ts` | `src/app/guard/config.ts`, `src/config_app.json` | `src/app/service/api.service.ts`, `socket.service.ts` |
| `kiosk/` | `src/main.ts` → `app.module.ts` | `app/main/module/config.ts`, `environments/*` | `app/main/module/HttpClient.ts`, `httpService.ts` |
| `e-reservasi/` | `src/main.ts` | `src/app/helper/config.ts`, `angular.json` | `src/app/helper/service/HttpClient.ts` |
| `eis/` | `public/index.php`, `routes/web.php` | `.env`, `config/database.php` | langsung ke MySQL |
| `socket-server/` | `server.js` | `setting.js`, `rabbitHole.js` | socket.io ke semua frontend |
| `deploy/` | `proxy/simrs-proxy.js` | `nginx/simrs.conf`, `apache/simrs.conf`, `env/*.example` | — |

---

## Lampiran C — FAQ

**T: Apakah wajib memakai `http://localhost/simrs/`?**
T: Tidak. Semua aplikasi bisa dibuka langsung pada portnya (`:2222`, `:8000`, `:8202`, `:4200`, `:4201`, `:8001`).
`/simrs/` hanya menyatukan semuanya dalam satu origin sehingga bebas masalah CORS dan lebih mudah dibagikan.

**T: Bisakah hanya menjalankan aplikasi petugas + API?**
T: Bisa. Yang wajib hanya `backend/` (8000) dan `frontend-v2/` (2222) + `deploy/proxy/simrs-proxy.js` (8080).

**T: Kenapa login gagal padahal halaman terbuka?**
T: Halaman bisa terbuka tanpa database, tetapi login butuh `loginuser_s`, `kelompokuser_s`, `pegawai_m`,
`profile_m` yang terisi, plus `JWT_KEY` yang sama seperti saat token dibuat.

**T: Apakah perlu MongoDB?**
T: Tidak wajib. Kosongkan variabel `*_MONGO`; hanya fitur log/EMR tertentu yang membutuhkannya.

**T: Bagaimana bila `JWT_KEY` di server berbeda dari default `TRANSINDO`?**
T: Set `JWT_KEY` di `backend/.env` sesuai server tersebut. Token yang tertulis di
`viewer/src/config_app.json`, `kiosk/.../HttpClient.ts`, dan `HttpService.ts` akan tidak valid —
ganti dengan token hasil login.

**T: Bolehkah saya memakai Node 22 (terbaru)?**
T: Untuk `frontend-v2` (Vite 3) umumnya masih jalan. Untuk `viewer`/`kiosk` (Angular 11) dan
`e-reservasi` (Angular 7) sebaiknya gunakan Node 16 dan 12/14 — banyak dependensi lama belum mendukung Node baru.
