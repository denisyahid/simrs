# `deploy/` — alat bantu instalasi & satu pintu `localhost/simrs/`

Folder ini berisi berkas pendukung agar seluruh aplikasi SIMRS bisa diakses dari
**satu alamat**: `http://localhost/simrs/`. Semua bersifat opsional — aplikasi tetap
bisa dijalankan satu per satu pada portnya masing-masing (lihat
[`INSTALL_DAN_JALANKAN.md`](../INSTALL_DAN_JALANKAN.md)).

| Berkas | Fungsi |
|---|---|
| `proxy/simrs-proxy.js` | **Reverse proxy tanpa dependency** (Node ≥14) → `http://localhost:8080/simrs/`. Pengganti nginx/Apache, termasuk halaman status `/simrs/health` dan halaman bantuan bila service belum jalan. |
| `nginx/simrs.conf` | Konfigurasi nginx untuk `http://localhost/simrs/` (Linux, macOS, WSL, nginx.exe Windows). |
| `apache/simrs.conf` | Konfigurasi Apache/XAMPP (mod_proxy + mod_proxy_wstunnel + Alias SPA). |
| `landing/index.html` | Halaman depan `http://localhost/` yang menautkan semua aplikasi. |
| `env/backend.env.example` | Contoh `backend/.env` (Laravel 8, MySQL/PostgreSQL). |
| `env/eis.env.example` | Contoh `eis/.env` (Laravel 7, MySQL). |
| `env/frontend-v2.env.example` | Contoh `frontend-v2/.env` (Vue 3 + Vite). |
| `scripts/start-all.sh` · `stop-all.sh` | Menyalakan/mematikan semua service (Linux, macOS, WSL, Git Bash). |
| `scripts/start-all.ps1` · `stop-all.ps1` | Versi Windows PowerShell. |

## Peta URL setelah aktif

| URL | Aplikasi | Port upstream |
|---|---|---|
| `http://localhost/simrs/` | Aplikasi petugas (Vue 3 + Vite) | 2222 (dev) / build statis |
| `http://localhost/simrs/service/...` | API backend Laravel | 8000 |
| `http://localhost/simrs/api/...` | API backend (biller/eksternal) | 8000 |
| `http://localhost/simrs/socket.io` | Notifikasi realtime | 2530 |
| `http://localhost/simrs/eis/` | Penampil EMR/EIS | 8001 |
| `http://localhost/simrs/viewer/` | Display antrian / TV | 8202 / build statis |
| `http://localhost/simrs/kiosk/` | Kiosk mandiri | 4200 / build statis |
| `http://localhost/simrs/e-reservasi/` | Reservasi online | 4201 / build statis |
| `http://localhost:8080/simrs/health` | Status JSON semua service (khusus Node proxy) | — |

## Tiga cara menjalankan

```bash
# A. Tanpa nginx/Apache (paling cepat, semua OS)
node deploy/proxy/simrs-proxy.js          # lalu buka http://localhost:8080/simrs/

# B. nginx
sudo cp deploy/nginx/simrs.conf /etc/nginx/conf.d/simrs.conf && sudo nginx -t && sudo systemctl reload nginx

# C. Apache/XAMPP
#   salin deploy/apache/simrs.conf ke C:\xampp\apache\conf\extra\httpd-simrs.conf,
#   tambahkan Include di httpd.conf, lalu restart Apache.

# Menyalakan semua service sekaligus (opsional)
./deploy/scripts/start-all.sh            # Linux/macOS/Git Bash
powershell -ExecutionPolicy Bypass -File deploy\scripts\start-all.ps1   # Windows
```

> Port bisa diubah lewat environment: `PORT=80 SIMRS_PREFIX=/simrs node deploy/proxy/simrs-proxy.js`.
> Override port upstream: `SIMRS_BACKEND_PORT`, `SIMRS_FRONTEND_PORT`, `SIMRS_SOCKET_PORT`, `SIMRS_VIEWER_PORT`, `SIMRS_KIOSK_PORT`, `SIMRS_RESERVASI_PORT`, `SIMRS_EIS_PORT`.
