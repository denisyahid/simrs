<#
=============================================================================
 deploy/scripts/stop-all.ps1 — hentikan service yang dijalankan start-all.ps1

 Pemakaian:
   powershell -ExecutionPolicy Bypass -File deploy\scripts\stop-all.ps1
   powershell -ExecutionPolicy Bypass -File deploy\scripts\stop-all.ps1 -Services backend,backend
=============================================================================>
param(
  [string[]]$Services = @('backend', 'frontend', 'socket', 'viewer', 'kiosk', 'e-reservasi', 'eis', 'proxy')
)

$Root = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
$PidDir = Join-Path $Root '.run\pids'

foreach ($svc in $Services) {
  $pidFile = Join-Path $PidDir "$svc.pid"
  if (-not (Test-Path $pidFile)) { Write-Host "  [lewati] $svc tidak punya berkas PID"; continue }
  $procId = (Get-Content $pidFile | Select-Object -First 1).Trim()
  try {
    $children = Get-CimInstance Win32_Process -Filter "ParentProcessId=$procId" -ErrorAction SilentlyContinue
    foreach ($c in $children) { Stop-Process -Id $c.ProcessId -Force -ErrorAction SilentlyContinue }
    Stop-Process -Id ([int]$procId) -Force -ErrorAction SilentlyContinue
    Write-Host "  [ok]     $svc dihentikan (PID $procId)"
  }
  catch {
    Write-Host "  [info]   $svc sudah tidak berjalan"
  }
  Remove-Item $pidFile -Force -ErrorAction SilentlyContinue
}

# Beberapa dev server Windows kadang masih menahan port.
foreach ($port in 8000, 8001, 2222, 2530, 4200, 4201, 8080, 8202) {
  $conn = Get-NetTCPConnection -LocalPort $port -State Listen -ErrorAction SilentlyContinue
  if ($conn) {
    $conn | Select-Object -ExpandProperty OwningProcess -Unique | ForEach-Object {
      Stop-Process -Id $_ -Force -ErrorAction SilentlyContinue
      Write-Host "  [ok]     proses pada port $port dihentikan (PID $_)"
    }
  }
}

Write-Host ''
Write-Host 'Selesai. Log tersimpan di .run\logs\'
