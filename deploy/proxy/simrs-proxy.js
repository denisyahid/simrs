#!/usr/bin/env node
/**
 * simrs-proxy.js — reverse proxy "localhost/simrs/" tanpa perlu nginx/Apache.
 *
 * Fungsi:
 *   1. Menyediakan SATU alamat untuk semua aplikasi SIMRS:
 *          http://localhost:8080/simrs/
 *   2. Meneruskan tiap sub-path ke service yang sesuai (lihat tabel ROUTES).
 *   3. Bila sebuah service belum dijalankan, menampilkan halaman bantuan
 *      (bukan error mentah) berisi perintah untuk menyalakannya.
 *   4. Menyajikan build statis (dist/) bila ada, jika tidak maka di-proxy
 *      ke dev server (ng serve / vite).
 *
 * Pemakaian:
 *   node deploy/proxy/simrs-proxy.js                 # port 8080
 *   PORT=80 node deploy/proxy/simrs-proxy.js         # port 80 (butuh root/sudo)
 *   SIMRS_PREFIX=/simrs node deploy/proxy/simrs-proxy.js
 *
 * Tanpa dependency eksternal — hanya modul bawaan Node.js (>=14).
 */

'use strict'

const http = require('http')
const https = require('https')
const fs = require('fs')
const path = require('path')
const net = require('net')
const { URL } = require('url')

// ---------------------------------------------------------------------------
// Konfigurasi
// ---------------------------------------------------------------------------

const ROOT = path.resolve(__dirname, '..', '..') // root repositori simrs/
const PORT = Number(process.env.PORT || 8080)
const PREFIX = (process.env.SIMRS_PREFIX || '/simrs').replace(/\/+$/, '') // '/simrs'

/** Port service (bisa dioverride lewat environment). */
const PORTS = {
  backend: Number(process.env.SIMRS_BACKEND_PORT || 8000),
  frontend: Number(process.env.SIMRS_FRONTEND_PORT || 2222),
  eis: Number(process.env.SIMRS_EIS_PORT || 8001),
  viewer: Number(process.env.SIMRS_VIEWER_PORT || 8202),
  kiosk: Number(process.env.SIMRS_KIOSK_PORT || 4200),
  reservasi: Number(process.env.SIMRS_RESERVASI_PORT || 4201),
  socket: Number(process.env.SIMRS_SOCKET_PORT || 2530),
}

const HOST = process.env.SIMRS_UPSTREAM_HOST || '127.0.0.1'

/**
 * Tabel routing.
 *
 * `strip: true`  → prefix /simrs dibuang sebelum diteruskan (backend Laravel
 *                  punya rute /service & /api di root).
 * `strip: false` → URL apa adanya diteruskan, karena dev server (Vite dengan
 *                  VITE_BASE_PATH=/simrs/, ng serve --base-href) memang
 *                  mengharapkan prefix tersebut.
 */
const ROUTES = [
  {
    id: 'backend',
    label: 'API Backend (Laravel)',
    prefix: `${PREFIX}/service/`,
    target: `http://${HOST}:${PORTS.backend}/service/`,
    strip: true,
    start: 'cd backend && ./serve.sh          # atau: php artisan serve --host=0.0.0.0 --port=8000',
  },
  {
    id: 'backend-api',
    label: 'API Backend — /api (biller, eksternal)',
    prefix: `${PREFIX}/api/`,
    target: `http://${HOST}:${PORTS.backend}/api/`,
    port: PORTS.backend,
    strip: true,
    start: 'cd backend && ./serve.sh          # /api dilayani server yang sama',
  },
  {
    id: 'socket',
    label: 'Socket.IO (notifikasi realtime)',
    prefix: `${PREFIX}/socket.io/`,
    target: `http://${HOST}:${PORTS.socket}/socket.io/`,
    strip: true,
    websocket: true,
    start: 'cd socket-server && node server.js   # butuh RabbitMQ (opsional)',
  },
  {
    id: 'eis',
    label: 'EIS / EMR (Laravel)',
    prefix: `${PREFIX}/eis/`,
    target: `http://${HOST}:${PORTS.eis}/`,
    strip: true,
    start: 'cd eis && php artisan serve --host=0.0.0.0 --port=8001',
  },
  {
    id: 'viewer',
    label: 'Viewer antrian / TV (Angular)',
    prefix: `${PREFIX}/viewer/`,
    target: `http://${HOST}:${PORTS.viewer}/`,
    strip: false,
    staticDir: path.join(ROOT, 'viewer', 'dist', 'viewer'),
    start: 'cd viewer && npm start -- --port 8202 --base-href ' + PREFIX + '/viewer/',
  },
  {
    id: 'kiosk',
    label: 'Kiosk mandiri (Angular)',
    prefix: `${PREFIX}/kiosk/`,
    target: `http://${HOST}:${PORTS.kiosk}/`,
    strip: false,
    staticDir: path.join(ROOT, 'kiosk', 'dist', 'kiosk'),
    start: 'cd kiosk && npm start -- --port 4200 --host 0.0.0.0 --base-href ' + PREFIX + '/kiosk/',
  },
  {
    id: 'reservasi',
    label: 'E-Reservasi (Angular)',
    prefix: `${PREFIX}/e-reservasi/`,
    target: `http://${HOST}:${PORTS.reservasi}/`,
    strip: false,
    staticDir: path.join(ROOT, 'e-reservasi', 'dist'),
    start: 'cd e-reservasi && npm start -- --base-href ' + PREFIX + '/e-reservasi/',
  },
  {
    id: 'frontend',
    label: 'Aplikasi petugas (Vue 3 + Vite)',
    prefix: `${PREFIX}/`,
    target: `http://${HOST}:${PORTS.frontend}${PREFIX}/`,
    strip: false,
    staticDir: path.join(ROOT, 'frontend-v2', 'dist'),
    start:
      'cd frontend-v2 && VITE_BASE_PATH=' + PREFIX + '/ npm run dev   ' +
      '(Windows PowerShell: $env:VITE_BASE_PATH="' + PREFIX + '/"; npm run dev)',
  },
]

// ---------------------------------------------------------------------------
// Utilitas
// ---------------------------------------------------------------------------

function log(...args) {
  console.log('[simrs-proxy ' + new Date().toISOString().substring(11, 19) + ']', ...args)
}

function matchRoute(pathname) {
  for (const route of ROUTES) {
    if (pathname === route.prefix.replace(/\/$/, '') || pathname.startsWith(route.prefix)) {
      return route
    }
  }
  return null
}

function html(res, status, body) {
  res.writeHead(status, { 'Content-Type': 'text/html; charset=utf-8' })
  res.end(body)
}

function json(res, status, data) {
  res.writeHead(status, { 'Content-Type': 'application/json; charset=utf-8' })
  res.end(JSON.stringify(data, null, 2))
}

/** Cek apakah ada TCP yang mendengarkan di host:port. */
function isPortOpen(port, timeout = 400) {
  return new Promise((resolve) => {
    const socket = new net.Socket()
    let done = false
    const finish = (ok) => {
      if (done) return
      done = true
      socket.destroy()
      resolve(ok)
    }
    socket.setTimeout(timeout)
    socket.once('connect', () => finish(true))
    socket.once('timeout', () => finish(false))
    socket.once('error', () => finish(false))
    socket.connect(port, HOST)
  })
}

/** Halaman bantuan bila upstream belum jalan. */
function serviceDownPage(route, pathname) {
  const command = route.start || '(perintah start tidak diketahui)'
  return `<!doctype html>
<html lang="id"><head><meta charset="utf-8">
<title>${route.label} belum berjalan</title>
<style>
  body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0;padding:48px}
  .card{max-width:860px;margin:0 auto;background:#1e293b;border:1px solid #334155;border-radius:14px;padding:32px}
  h1{margin:0 0 8px;font-size:22px}
  p{color:#94a3b8;line-height:1.6}
  code,pre{font-family:ui-monospace,SFMono-Regular,Menlo,monospace}
  pre{background:#0b1220;border:1px solid #334155;border-radius:10px;padding:14px;overflow:auto;color:#7dd3fc}
  a{color:#60a5fa}
  .tag{display:inline-block;background:#7f1d1d;color:#fecaca;border-radius:99px;padding:3px 10px;font-size:12px;margin-bottom:14px}
  ul{color:#cbd5e1;line-height:1.8}
</style></head>
<body><div class="card">
  <span class="tag">503 · SERVICE BELUM AKTIF</span>
  <h1>${route.label}</h1>
  <p>Permintaan <code>${pathname}</code> diteruskan ke
     <code>http://${HOST}:${route.port || PORTS[route.id] || PORTS.backend}</code>, tetapi tidak ada proses yang mendengarkan di port tersebut.</p>
  <p>Jalankan service berikut pada terminal terpisah:</p>
  <pre>${command}</pre>
  <p>Setelah service aktif, muat ulang halaman ini. Halaman status semua service:
     <a href="${PREFIX}/health">${PREFIX}/health</a>.</p>
</div></body></html>`
}

/** Sajikan berkas statis dari dist (SPA fallback ke index.html). */
function serveStatic(route, req, res, pathname) {
  if (!route.staticDir || !fs.existsSync(route.staticDir)) {
    return false
  }
  const rel = pathname.slice(route.prefix.replace(/\/$/, '').length).replace(/^\/+/, '')
  let filePath = path.join(route.staticDir, rel || 'index.html')
  if (!filePath.startsWith(route.staticDir)) {
    html(res, 403, '<h1>403</h1>')
    return true
  }
  if (!fs.existsSync(filePath) || fs.statSync(filePath).isDirectory()) {
    // SPA fallback
    const indexFile = path.join(route.staticDir, 'index.html')
    if (fs.existsSync(indexFile)) {
      filePath = indexFile
    } else {
      return false
    }
  }
  const ext = path.extname(filePath).toLowerCase()
  const types = {
    '.html': 'text/html; charset=utf-8',
    '.js': 'text/javascript; charset=utf-8',
    '.mjs': 'text/javascript; charset=utf-8',
    '.css': 'text/css; charset=utf-8',
    '.json': 'application/json; charset=utf-8',
    '.svg': 'image/svg+xml',
    '.png': 'image/png',
    '.jpg': 'image/jpeg',
    '.jpeg': 'image/jpeg',
    '.webp': 'image/webp',
    '.ico': 'image/x-icon',
    '.woff': 'font/woff',
    '.woff2': 'font/woff2',
    '.ttf': 'font/ttf',
    '.mp4': 'video/mp4',
    '.webm': 'video/webm',
    '.map': 'application/json; charset=utf-8',
  }
  res.writeHead(200, { 'Content-Type': types[ext] || 'application/octet-stream' })
  fs.createReadStream(filePath).pipe(res)
  return true
}

// ---------------------------------------------------------------------------
// Proxy
// ---------------------------------------------------------------------------

function proxyRequest(route, req, res, pathname, search) {
  const target = new URL(route.target)
  const targetPath = route.strip
    ? target.pathname.replace(/\/$/, '') + pathname.slice(PREFIX.length)
    : pathname
  const options = {
    hostname: target.hostname,
    port: target.port || 80,
    path: (targetPath || '/') + (search || ''),
    method: req.method,
    headers: Object.assign({}, req.headers, {
      host: target.host + (target.port ? ':' + target.port : ''),
      'x-forwarded-host': req.headers.host || '',
      'x-forwarded-proto': 'http',
      'x-forwarded-prefix': PREFIX,
    }),
  }

  const upstream = http.request(options, (upstreamRes) => {
    // Teruskan header, kecuali hop-by-hop.
    const headers = Object.assign({}, upstreamRes.headers)
    delete headers.connection
    headers['access-control-allow-origin'] = headers['access-control-allow-origin'] || '*'
    res.writeHead(upstreamRes.statusCode || 502, headers)
    upstreamRes.pipe(res)
  })

  upstream.on('error', (err) => {
    if (res.headersSent) {
      res.destroy()
      return
    }
    if (err.code === 'ECONNREFUSED' || err.code === 'EHOSTUNREACH' || err.code === 'ENOTFOUND') {
      html(res, 503, serviceDownPage(route, pathname))
    } else {
      json(res, 502, { error: 'BAD_GATEWAY', message: err.message, route: route.id })
    }
  })

  req.pipe(upstream)
}

function proxyWebSocket(route, req, socket, head) {
  const target = new URL(route.target)
  const upstream = net.connect(Number(target.port), target.hostname, () => {
    const path = route.strip
      ? target.pathname.replace(/\/$/, '') + req.url.slice(PREFIX.length)
      : req.url
    const headers = Object.assign({}, req.headers, {
      host: target.host + ':' + target.port,
    })
    let raw = `GET ${path} HTTP/1.1\r\n`
    for (const key of Object.keys(headers)) {
      raw += `${key}: ${headers[key]}\r\n`
    }
    raw += '\r\n'
    upstream.write(raw)
    if (head && head.length) {
      upstream.write(head)
    }
    upstream.pipe(socket)
    socket.pipe(upstream)
  })
  upstream.on('error', () => socket.destroy())
  socket.on('error', () => upstream.destroy())
}

// ---------------------------------------------------------------------------
// Halaman status
// ---------------------------------------------------------------------------

function landingPage(statusMap) {
  const rows = ROUTES.map((route) => {
    const up = statusMap[route.id]
    const url = route.prefix
    const dot = up ? '#22c55e' : '#ef4444'
    const state = up ? 'AKTIF' : 'belum aktif'
    return `<tr>
      <td><span class="dot" style="background:${dot}"></span></td>
      <td><a href="${url}">${url}</a></td>
      <td>${route.label}</td>
      <td class="state" style="color:${dot}">${state}</td>
    </tr>`
  }).join('\n')

  const warns = ROUTES.filter((r) => !statusMap[r.id])
    .map((r) => `<li><b>${r.label}</b><pre>${r.start || '-'}</pre></li>`)
    .join('\n')

  return `<!doctype html>
<html lang="id"><head><meta charset="utf-8">
<title>SIMRS — ${PREFIX}/</title>
<style>
  body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:0;background:#0b1220;color:#e2e8f0;padding:40px}
  .wrap{max-width:1000px;margin:0 auto}
  h1{font-size:26px;margin:0 0 4px}
  p.sub{color:#94a3b8;margin:0 0 28px}
  table{width:100%;border-collapse:collapse;background:#111c31;border:1px solid #263449;border-radius:12px;overflow:hidden}
  th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #1e2b40;font-size:14px}
  th{background:#152238;color:#94a3b8;font-weight:600;font-size:12px;letter-spacing:.04em;text-transform:uppercase}
  a{color:#60a5fa;text-decoration:none;font-family:ui-monospace,Menlo,monospace}
  .dot{display:inline-block;width:10px;height:10px;border-radius:50%}
  .state{font-weight:600;font-size:12px}
  pre{background:#0b1220;border:1px solid #263449;border-radius:8px;padding:10px;color:#7dd3fc;overflow:auto;margin:6px 0 14px}
  ul{padding-left:20px}
  li{margin-bottom:10px;color:#cbd5e1}
  code{font-family:ui-monospace,Menlo,monospace;color:#fbbf24}
</style></head>
<body><div class="wrap">
  <h1>SIMRS — satu pintu <code>http://localhost:${PORT}${PREFIX}/</code></h1>
  <p class="sub">Halaman ini dihasilkan oleh <code>deploy/proxy/simrs-proxy.js</code>.
     Status di bawah diambil langsung dari port tiap service.</p>
  <table>
    <tr><th></th><th>URL</th><th>Aplikasi</th><th>Status</th></tr>
    ${rows}
  </table>
  ${warns ? `<h2 style="font-size:16px;margin-top:30px">Cara menyalakan service yang belum aktif</h2><ul>${warns}</ul>` : '<p style="margin-top:24px">Semua service aktif. 🎉</p>'}
  <p class="sub" style="margin-top:26px">Ringkasan status JSON: <a href="${PREFIX}/health">${PREFIX}/health</a></p>
</div></body></html>`
}

async function statusMap() {
  const map = {}
  await Promise.all(
    ROUTES.map(async (route) => {
      const port = route.port || PORTS[route.id] || PORTS.frontend
      map[route.id] = await isPortOpen(port)
    })
  )
  return map
}

// ---------------------------------------------------------------------------
// Server
// ---------------------------------------------------------------------------

const server = http.createServer(async (req, res) => {
  const parsed = new URL(req.url, 'http://localhost')
  const pathname = decodeURIComponent(parsed.pathname)
  const search = parsed.search

  // Root → landing page.
  if (pathname === '/' || pathname === PREFIX || pathname === PREFIX + '/index.html') {
    if (pathname === PREFIX) {
      res.writeHead(302, { Location: PREFIX + '/' })
      res.end()
      return
    }
    if (pathname === '/') {
      const map = await statusMap()
      html(res, 200, landingPage(map))
      return
    }
    // PREFIX + '/' → teruskan ke frontend (route terakhir menangani).
  }

  if (pathname === PREFIX + '/health') {
    const map = await statusMap()
    return json(res, 200, {
      prefix: PREFIX,
      ports: PORTS,
      upstreamHost: HOST,
      services: ROUTES.map((route) => ({
        id: route.id,
        url: route.prefix,
        label: route.label,
        port: route.port || PORTS[route.id] || PORTS.frontend,
        up: map[route.id],
        start: route.start,
      })),
    })
  }

  const route = matchRoute(pathname)
  if (!route) {
    return html(
      res,
      404,
      `<h1>404</h1><p>Tidak ada rute untuk <code>${pathname}</code>.
       Semua aplikasi berada di bawah <a href="${PREFIX}/">${PREFIX}/</a>.</p>`
    )
  }

  if (serveStatic(route, req, res, pathname)) {
    return
  }

  proxyRequest(route, req, res, pathname, search)
})

server.on('upgrade', (req, socket, head) => {
  const pathname = decodeURIComponent(new URL(req.url, 'http://localhost').pathname)
  const route = matchRoute(pathname)
  if (route && route.websocket) {
    proxyWebSocket(route, req, socket, head)
  } else if (route) {
    // HMR / WebSocket dev server (Vite, webpack-dev-server) → teruskan apa adanya.
    const target = new URL(route.target)
    const upstream = net.connect(Number(target.port), target.hostname, () => {
      const path = route.strip ? target.pathname.replace(/\/$/, '') + req.url.slice(PREFIX.length) : req.url
      let raw = `GET ${path} HTTP/1.1\r\n`
      const headers = Object.assign({}, req.headers, { host: target.host + ':' + target.port })
      for (const key of Object.keys(headers)) {
        raw += `${key}: ${headers[key]}\r\n`
      }
      raw += '\r\n'
      upstream.write(raw)
      if (head && head.length) upstream.write(head)
      upstream.pipe(socket)
      socket.pipe(upstream)
    })
    upstream.on('error', () => socket.destroy())
    socket.on('error', () => upstream.destroy())
  } else {
    socket.destroy()
  }
})

server.listen(PORT, '0.0.0.0', () => {
  log(`Reverse proxy SIMRS aktif di http://localhost:${PORT}${PREFIX}/`)
  log('Port upstream:', JSON.stringify(PORTS))
  log(`Halaman status: http://localhost:${PORT}${PREFIX}/health`)
})
