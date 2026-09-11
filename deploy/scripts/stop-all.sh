#!/usr/bin/env bash
# =============================================================================
# deploy/scripts/stop-all.sh — hentikan semua service yang dijalankan
# oleh deploy/scripts/start-all.sh.
#
# Pemakaian:
#   ./deploy/scripts/stop-all.sh              # hentikan semua
#   ./deploy/scripts/stop-all.sh backend      # hanya backend
# =============================================================================
set -uo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
PID_DIR="$ROOT/.run/pids"
ALL_SERVICES=(backend frontend socket viewer kiosk e-reservasi eis proxy)

SERVICES=("$@")
if [ ${#SERVICES[@]} -eq 0 ]; then
  SERVICES=("${ALL_SERVICES[@]}")
fi

for name in "${SERVICES[@]}"; do
  pf="$PID_DIR/$name.pid"
  if [ ! -f "$pf" ]; then
    echo "  [lewati] $name tidak punya berkas PID"
    continue
  fi
  pid="$(cat "$pf")"
  if kill -0 "$pid" 2>/dev/null; then
    # Matikan seluruh proses anak (npm/ng spawn child process).
    pkill -TERM -P "$pid" 2>/dev/null || true
    kill -TERM "$pid" 2>/dev/null || true
    sleep 1
    if kill -0 "$pid" 2>/dev/null; then
      pkill -KILL -P "$pid" 2>/dev/null || true
      kill -KILL "$pid" 2>/dev/null || true
    fi
    echo "  [ok]     $name dihentikan (PID $pid)"
  else
    echo "  [info]   $name sudah tidak berjalan"
  fi
  rm -f "$pf"
done

echo
echo "Selesai. Log tersimpan di .run/logs/."
