<#
=============================================================================
 deploy/scripts/start-all.ps1 — jalankan semua service SIMRS (Windows PowerShell)

 Pemakaian:
   powershell -ExecutionPolicy Bypass -File deploy\scripts\start-all.ps1
   powershell -ExecutionPolicy Bypass -File deploy\scripts\start-all.ps1 -Services backend,frontend,proxy

 Log   : .run\logs\<service>.log
 Stop  : powershell -ExecutionPolicy Bypass -File deploy\scripts\stop-all.ps1

 Prasyarat per service (lewati otomatis bila tidak ada):
   php, composer (backend & eis) · node, npm (frontend, viewer, kiosk, e-reservasi, socket, proxy)
=============================================================================>
param(
  [string[]]$Services = @('backend', 'frontend', 'socket', 'viewer', 'kiosk', 'e-reservasi', 'eis', 'proxy'),
  [string]$BasePath = '/simrs',
  [int]$ProxyPort = 8080
)

$ErrorActionPreference = 'Continue'
$Root = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
$RunDir = Join-Path $Root '.run'
$LogDir = Join-Path $RunDir 'logs'
$PidDir = Join-Path $RunDir 'pids'
New-Item -ItemType Directory -Force -Path $LogDir, $PidDir | Out-Null

function Test-Cmd([string]$Name) { return [bool](Get-Command $Name -ErrorAction SilentlyContinue) }

function Start-Svc {
  param([string]$Name, [string]$WorkDir, [string]$Command)

  if (-not (Test-Path $WorkDir)) { Write-Host "  [gagal]  folder $WorkDir tidak ada"; return }

  $log = Join-Path $LogDir "$Name.log"
  $err = Join-Path $LogDir "$Name.err.log"
  $proc = Start-Process -FilePath 'cmd.exe' -ArgumentList '/c', $Command `
    -WorkingDirectory $WorkDir -WindowStyle Hidden -PassThru `
    -RedirectStandardOutput $log -RedirectStandardError $err
  $proc.Id | Out-File -Encoding ascii (Join-Path $PidDir "$Name.pid")
  Start-Sleep -Seconds 1
  if (-not $proc.HasExited) {
    Write-Host "  [ok]     $Name  -> PID $($proc.Id)  (log: .run\logs\$Name.log)"
  }
  else {
    Write-Host "  [gagal]  $Name berhenti segera - periksa .run\logs\$Name.log"
  }
}

Write-Host "SIMRS - menyalakan service (base path: $BasePath)"
Write-Host "Root : $Root"
Write-Host ''

foreach ($svc in $Services) {
  switch ($svc) {
    'backend' {
      if (-not (Test-Cmd 'php')) { Write-Host '  [lewati] backend: php tidak ada di PATH'; break }
      if ((Test-Cmd 'composer') -and -not (Test-Path (Join-Path $Root 'backend\vendor'))) {
        Write-Host '  [info]   backend: menjalankan composer install...'
        Push-Location (Join-Path $Root 'backend'); composer install --no-interaction --prefer-dist; Pop-Location
      }
      Start-Svc -Name 'backend' -WorkDir (Join-Path $Root 'backend') -Command 'php artisan serve --host=0.0.0.0 --port=8000'
    }
    'frontend' {
      if (-not (Test-Cmd 'npm')) { Write-Host '  [lewati] frontend: npm tidak ada di PATH'; break }
      if (-not (Test-Path (Join-Path $Root 'frontend-v2\node_modules'))) {
        Write-Host '  [info]   frontend: menjalankan npm install (bisa 3-10 menit)...'
        Push-Location (Join-Path $Root 'frontend-v2'); npm install --legacy-peer-deps --no-audit --no-fund; Pop-Location
      }
      Start-Svc -Name 'frontend' -WorkDir (Join-Path $Root 'frontend-v2') `
        -Command "set VITE_BASE_PATH=$BasePath/&& npm run dev"
    }
    'socket' {
      if (-not (Test-Cmd 'node')) { Write-Host '  [lewati] socket: node tidak ada di PATH'; break }
      Start-Svc -Name 'socket' -WorkDir (Join-Path $Root 'socket-server') -Command 'node server.js'
    }
    'viewer' {
      if (-not (Test-Cmd 'npm')) { Write-Host '  [lewati] viewer: npm tidak ada di PATH'; break }
      Start-Svc -Name 'viewer' -WorkDir (Join-Path $Root 'viewer') `
        -Command "npx ng serve --host 0.0.0.0 --port 8202 --base-href $BasePath/viewer/"
    }
    'kiosk' {
      if (-not (Test-Cmd 'npm')) { Write-Host '  [lewati] kiosk: npm tidak ada di PATH'; break }
      Start-Svc -Name 'kiosk' -WorkDir (Join-Path $Root 'kiosk') `
        -Command "npx ng serve --host 0.0.0.0 --port 4200 --base-href $BasePath/kiosk/"
    }
    'e-reservasi' {
      if (-not (Test-Cmd 'npm')) { Write-Host '  [lewati] e-reservasi: npm tidak ada di PATH'; break }
      Start-Svc -Name 'e-reservasi' -WorkDir (Join-Path $Root 'e-reservasi') `
        -Command "npx ng serve --host 0.0.0.0 --port 4201 --base-href $BasePath/e-reservasi/"
    }
    'eis' {
      if (-not (Test-Cmd 'php')) { Write-Host '  [lewati] eis: php tidak ada di PATH'; break }
      Start-Svc -Name 'eis' -WorkDir (Join-Path $Root 'eis') -Command 'php artisan serve --host=0.0.0.0 --port=8001'
    }
    'proxy' {
      if (-not (Test-Cmd 'node')) { Write-Host '  [lewati] proxy: node tidak ada di PATH'; break }
      Start-Svc -Name 'proxy' -WorkDir $Root `
        -Command "set PORT=$ProxyPort&& set SIMRS_PREFIX=$BasePath&& node deploy\proxy\simrs-proxy.js"
    }
    default { Write-Host "  [lewati] service tidak dikenal: $svc" }
  }
}

Write-Host ''
Write-Host "Selesai. Buka        -> http://localhost:$ProxyPort$BasePath/"
Write-Host "Status service       -> http://localhost:$ProxyPort$BasePath/health"
Write-Host 'Hentikan semua       -> powershell -File deploy\scripts\stop-all.ps1'
