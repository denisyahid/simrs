#!/usr/bin/env bash
# =============================================================================
# deploy/scripts/start-all.sh — jalankan semua service SIMRS di latar belakang.
#
# Pemakaian:
#   ./deploy/scripts/start-all.sh                 # semua service
#   ./deploy/scripts/start-all.sh backend frontend
#   ./deploy/scripts/start-all.sh proxy           # hanya reverse proxy /simrs
#
# Log  : .run/logs/<service>.log
# PID  : .run/pids/<service>.pid
# Stop : ./deploy/scripts/stop-all.sh
#
# Berjalan di Linux/macOS/WSL maupun Git Bash (Windows).
# =============================================================================
set -uo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
RUN_DIR="$ROOT/.run"
LOG_DIR="$RUN_DIR/logs"
PID_DIR="$RUN_DIR/pids"
mkdir -p "$LOG_DIR" "$PID_DIR"

ALL_SERVICES=(backend frontend socket viewer kiosk e-reservasi eis proxy)
SERVICES=("$@")
if [ ${#SERVICES[@]} -eq 0 ]; then
  SERVICES=("${ALL_SERVICES[@]}")
fi

BASE_PATH="${SIMRS_PREFIX:-/simrs}"
PROXY_PORT="${PORT:-8080}"

have() { command -v "$1" >/dev/null 2>&1; }

is_running() { # $1 = nama service
  local pf="$PID_DIR/$1.pid"
  [ -f "$pf" ] && kill -0 "$(cat "$pf")" 2>/dev/null
}

start() { # $1 = nama, $2 = direktori, $3 = perintah
  local name="$1" dir="$2" cmd="$3"
  if is_running "$name"; then
    echo "  [lewati] $name sudah berjalan (PID $(cat "$PID_DIR/$name.pid"))"
    return
  fi
  if [ ! -d "$dir" ]; then
    echo "  [gagal]  folder $dir tidak ditemukan"
    return
  fi
  ( cd "$dir" && nohup bash -lc "$cmd" > "$LOG_DIR/$name.log" 2>&1 & echo $! > "$PID_DIR/$name.pid" )
  sleep 1
  if is_running "$name"; then
    echo "  [ok]     $name  → PID $(cat "$PID_DIR/$name.pid")  (log: .run/logs/$name.log)"
  else
    echo "  [gagal]  $name tidak dapat dijalankan — periksa .run/logs/$name.log"
  fi
}

port_open() { # $1 = port
  (exec 3<>"/dev/tcp/127.0.0.1/$1") 2>/dev/null && { exec 3>&-; return 0; } || return 1
}

echo "SIMRS — menyalakan service (base path: $BASE_PATH)"
echo "Root : $ROOT"
echo

for svc in "${SERVICES[@]}"; do
  case "$svc" in
    backend)
      if ! have php; then echo "  [lewati] backend: php tidak ditemukan di PATH"; continue; fi
      if have composer && [ ! -d "$ROOT/backend/vendor" ]; then
        echo "  [info]   backend: vendor/ belum ada → menjalankan composer install"
        ( cd "$ROOT/backend" && composer install --no-interaction --prefer-dist || true )
      fi
      start backend "$ROOT/backend" "php artisan serve --host=0.0.0.0 --port=8000"
      ;;
    frontend)
      if ! have npm; then echo "  [lewati] frontend: npm tidak ditemukan di PATH"; continue; fi
      if [ ! -d "$ROOT/frontend-v2/node_modules" ]; then
        echo "  [info]   frontend: node_modules/ belum ada → menjalankan npm install"
        ( cd "$ROOT/frontend-v2" && npm install --legacy-peer-deps --no-audit --no-fund || true )
      fi
      start frontend "$ROOT/frontend-v2" "VITE_BASE_PATH=$BASE_PATH/ npm run dev"
      ;;
    socket)
      if ! have node; then echo "  [lewati] socket: node tidak ditemukan di PATH"; continue; fi
      ( cd "$ROOT/socket-server" && [ -d node_modules ] || npm install --no-audit --no-fund >/dev/null 2>&1 )
      start socket "$ROOT/socket-server" "node server.js"
      ;;
    viewer)
      if ! have npm; then echo "  [lewati] viewer: npm tidak ditemukan di PATH"; continue; fi
      ( cd "$ROOT/viewer" && [ -d node_modules ] || npm install --legacy-peer-deps --no-audit --no-fund >/dev/null 2>&1 )
      start viewer "$ROOT/viewer" "npx ng serve --host 0.0.0.0 --port 8202 --base-href $BASE_PATH/viewer/"
      ;;
    kiosk)
      if ! have npm; then echo "  [lewati] kiosk: npm tidak ditemukan di PATH"; continue; fi
      ( cd "$ROOT/kiosk" && [ -d node_modules ] || npm install --legacy-peer-deps --no-audit --no-fund >/dev/null 2>&1 )
      start kiosk "$ROOT/kiosk" "npx ng serve --host 0.0.0.0 --port 4200 --base-href $BASE_PATH/kiosk/"
      ;;
    e-reservasi)
      if ! have npm; then echo "  [lewati] e-reservasi: npm tidak ditemukan di PATH"; continue; fi
      ( cd "$ROOT/e-reservasi" && [ -d node_modules ] || npm install --legacy-peer-deps --no-audit --no-fund >/dev/null 2>&1 )
      start e-reservasi "$ROOT/e-reservasi" "npx ng serve --host 0.0.0.0 --port 4201 --base-href $BASE_PATH/e-reservasi/"
      ;;
    eis)
      if ! have php; then echo "  [lewati] eis: php tidak ditemukan di PATH"; continue; fi
      if have composer && [ ! -d "$ROOT/eis/vendor" ]; then
        echo "  [info]   eis: vendor/ belum ada → menjalankan composer install"
        ( cd "$ROOT/eis" && composer install --no-interaction --prefer-dist || true )
      fi
      start eis "$ROOT/eis" "php artisan serve --host=0.0.0.0 --port=8001"
      ;;
    proxy)
      if ! have node; then echo "  [lewati] proxy: node tidak ditemukan di PATH"; continue; fi
      start proxy "$ROOT" "PORT=$PROXY_PORT SIMRS_PREFIX=$BASE_PATH node deploy/proxy/simrs-proxy.js"
      ;;
    *)
      echo "  [lewati] service tidak dikenal: $svc (pilihan: ${ALL_SERVICES[*]})"
      ;;
  esac
done

echo
echo "Selesai. Buka  →  http://localhost:$PROXY_PORT$BASE_PATH/"
echo "Status semua service  →  http://localhost:$PROXY_PORT$BASE_PATH/health"
echo "Hentikan semua        →  ./deploy/scripts/stop-all.sh"
