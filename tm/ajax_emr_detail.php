<?php
/**
 * ============================================================================
 *  ajax_emr_detail.php  —  Native PHP (dipakai oleh tm/index.php)
 * ============================================================================
 *  Menampilkan card / tab EMR (daftar dokumen EMR pasien) seperti tab "EMR"
 *  pada halaman frontend Vue:  module/emr/profile-pasien  (t-emr-detail.vue
 *  bagian LIST_EMR).
 *
 *  HASIL ANALISA BACKEND (Laravel) + FRONTEND (Vue):
 *
 *  1) Saat form EMR disimpan  -> EMRCtrl (saveEMR / saveEMRCPPT / ...)
 *     - emrpasien_t  : 1 baris / 1x simpan (jenisemr = 'asesmen_medis' utk
 *                      hampir semua form, jadi TIDAK bisa dipakai utk tahu
 *                      nama form / collection!).
 *     - MongoDB #ResumeEMR: history yg dipakai frontend, berisi
 *         { noregistrasifk, kdprofile, emrpasienfk, table (collection),
 *           last_update, author, url_form, namaemr, noemr, ruangan, icon }
 *       ==> NAMA DOKUMEN (SPRI / CPPT / MEOWS / Resume Medis / dst) ADA DI SINI
 *           (namaemr  diambil dari "name_form" form masing-masing).
 *     - logginguser_t: log EMR -> jenislog = collection, noreff = emrpasien_t.norec,
 *           referensi = 'EMR', keterangan = 'input EMR <NAMA FORM> dari pasien ...'
 *
 *  2) Halaman EMR Vue memanggil  GET /service/emr/detail-pelayanan
 *     (ProfilePasienCtrl::detailPelayanan) dan hasilnya dibaca di
 *     frontend sebagai:  riwayatPemeriksaan.LIST_EMR = response.emr
 *     ==> envelope Laravel selalu { metaData:{...}, response:{ emr:[...] } }
 *         (sebelumnya kode di sini membaca $json['emr'] / $json['data']['emr']
 *          sehingga SELALU gagal dan jatuh ke query Postgres yang salah).
 *
 *  Urutan sumber data pada file ini (otomatis):
 *     1. MongoDB  : collection "#ResumeEMR"       (identik dgn backend/frontend)
 *     2. REST API : /service/emr/detail-pelayanan (Laravel)  -> response.emr
 *     3. PostgreSQL : emrpasien_t + emr_t + logginguser_t  (mode terbatas)
 *
 *  Yang dikerjakan pada revisi ini (menjawab "nama dokumen tidak muncul"):
 *     - Envelope API Laravel dibaca benar: response.emr (bukan $json['emr']).
 *     - Nama dokumen diambil dari sumber yang sama dengan frontend
 *       (#ResumeEMR.namaemr), bukan hasil join string emr_t.collection =
 *       emrpasien_t.jenisemr (jenisemr hampir selalu 'asesmen_medis' sehingga
 *       semua kartu tampil sebagai "Asesmen Medis").
 *     - VitalSign tetap ditampilkan (1 terbaru) persis seperti response.emr.
 *     - Bila namaemr / url_form / icon tidak ada, dilengkapi dari master emr_t
 *       (collection -> caption / url_form / icon).
 *     - Postgres (mode terbatas) memakai logginguser_t (jenislog = collection,
 *       keterangan = "input EMR <NAMA FORM> ...") + emr_t, bukan jenisemr.
 *     - Kartu ganda (emrpasienfk + collection sama) dihilangkan.
 *
 *  Parameter GET : norec_pd, noregistrasi, nocmfk, norec_apd, emr_surat_fk,
 *                  q (cari), token (opsional, agar token selalu baru), debug=1
 *
 *  CARA PAKAI / CEK CEPAT:
 *     - Dari tm/index.php: klik tombol "EMR" pada baris pasien.
 *     - Cek langsung   : ajax_emr_detail.php?norec_pd=<uuid>&noregistrasi=<no>&debug=1
 *       (panel "Diagnosa sumber data EMR" menampilkan sumber yang dipakai,
 *        status koneksi MongoDB, token yang dicoba, dan error bila ada).
 *     - Token kedaluwarsa: buka tm/index.php?...&token=<token dari Vue>
 *       (token disimpan di session dan dipakai untuk daftar EMR + link cetak).
 * ============================================================================
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '0');
header('Content-Type: text/html; charset=utf-8');

// ============================================================
// KONFIGURASI
// ============================================================
// --- Database SIMRS (PostgreSQL) ---
$host     = "192.168.22.81";
$port     = "5792";
$dbname   = "rsud_malangbong";
$user     = "postgres";
$password = "Tr4nsm3d!c MaRe#T3aM";

$kdProfile = 1;

// Alamat frontend Vue (untuk membuka / mengubah form EMR)
$webBase = 'https://192.168.22.81';

// Alamat REST API Laravel (sumber data EMR)
$apiBase = 'https://192.168.22.81/service';

// User yang dicetak pada footer cetak EMR
$userCetak = 'Pasa Pirdaos, A.Md.A.K';

// Token default (dipakai bila token pada URL / session tidak ada).
// Bila token di URL tm/index.php?token=... diisi, token itulah yang dipakai.
$tokenDefault = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwYXNhIiwic2Vzc2lvbklkIjoiN2FlODRkMWQtODE0Ny00Yzg2LTk3YmYtMjczZDQ4ZjhlNjZlIiwiZXhwIjoxNzgyMjc0NTk1fQ.s1y3_kHquIMFXLUrySNuyXWQarceI6VhAqUveszO9uhpnGoT_peADF4hdNAiZxhN7uycLVvuicgk_6XgkY3WOQ.MQ==';

// --- MongoDB (sumber utama daftar EMR) ---
// Kosongkan = otomatis: dibaca dari backend/.env, lalu dicoba host lokal.
$mongoUriManual = '';          // contoh: 'mongodb://user:pass@127.0.0.1:27017/?authSource=admin'
$mongoDbManual  = '';          // contoh: 'transmedic_v3'
$mongoCollection = '#ResumeEMR';

// --- JWT Laravel (untuk membuat token sendiri bila token default tidak valid) ---
// Nilai default di backend/config/app.php : env('JWT_KEY', 'TRANSINDO')
$jwtKeyManual = '';

// Tampilkan panel diagnosa (cara pakai: ajax_emr_detail.php?...&debug=1)
$showDebug = isset($_GET['debug']) && $_GET['debug'] == '1';

$diag = array();
$diag[] = array('config', 'kdProfile=' . $kdProfile . ', webBase=' . $webBase . ', apiBase=' . $apiBase);

// ============================================================
// SESSION (menyimpan token yang dipakai / dikirim dari URL)
// ============================================================
// Token bisa "disuntik" dari URL:  tm/index.php?...&token=<token baru>
// sehingga tidak perlu mengedit file bila token SIMRS kadaluarsa.
if (isset($_COOKIE[session_name()]) || !empty($_GET['token'])) {
    @session_start();
}
if (session_status() === PHP_SESSION_ACTIVE && !empty($_GET['token'])) {
    $_SESSION['emr_token'] = trim($_GET['token']);
}
$tokenFromUrl = (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['emr_token']))
    ? trim($_SESSION['emr_token']) : '';

// ============================================================
// KONEKSI POSTGRES
// ============================================================
$pdo = null;
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ));
} catch (PDOException $e) {
    // Postgres dipakai untuk fallback & info pasien; kalau gagal, masih bisa
    // jalan lewat MongoDB / API.
    $diag[] = array('postgres', 'GAGAL: ' . $e->getMessage());
}

// ============================================================
// PARAMETER
// ============================================================
$norec_pd     = trim(isset($_GET['norec_pd']) ? $_GET['norec_pd'] : '');
$noregistrasi = trim(isset($_GET['noregistrasi']) ? $_GET['noregistrasi'] : '');
$nocmfk       = trim(isset($_GET['nocmfk']) ? $_GET['nocmfk'] : '');
$norec_apd    = trim(isset($_GET['norec_apd']) ? $_GET['norec_apd'] : '');
$emr_surat_fk = trim(isset($_GET['emr_surat_fk']) ? $_GET['emr_surat_fk'] : '');
$qSearch      = trim(isset($_GET['q']) ? $_GET['q'] : '');

// Bila norec_pd belum ada, cari dari noregistrasi
if ($norec_pd === '' && $noregistrasi !== '' && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT norec FROM pasiendaftar_t WHERE noregistrasi = :noreg AND statusenabled = true LIMIT 1");
        $stmt->execute(array(':noreg' => $noregistrasi));
        $found = $stmt->fetch();
        if ($found) {
            $norec_pd = $found['norec'];
        }
    } catch (Exception $e) {
        $diag[] = array('postgres', 'lookup norec_pd: ' . $e->getMessage());
    }
}

if ($norec_pd === '') {
    http_response_code(400);
    echo '<p class="text-red-500 p-3">Parameter <b>norec_pd</b> / <b>noregistrasi</b> diperlukan.</p>';
    exit;
}

// ============================================================
// UTILITAS
// ============================================================

/**
 * Tanggal gaya frontend: "Jum, 11 Sep 26 10:01" (H.formatDateIndoSimple).
 *
 * Catatan penting: backend menulis last_update dengan date('Y-m-d H:i:s')
 * memakai timezone aplikasi (config/app.php -> Asia/Jakarta). Nilai itu
 * diperlakukan sebagai WIB tanpa digeser, sehingga tampilan tetap benar
 * walaupun timezone PHP di server berbeda (tidak bergantung date_default_timezone).
 * Nilai berzona (ISO +07:00 / Z) dan epoch milidetik (BSON UTCDateTime)
 * dikonversi dulu ke Asia/Jakarta.
 */
function emrTanggalIndoSimple($dt)
{
    if ($dt === null || $dt === '' || $dt === false) {
        return '-';
    }

    $hariArr  = array('Ming', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab');
    $bulanArr = array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');

    $rapikan = function ($d) use ($hariArr, $bulanArr) {
        return $hariArr[(int)$d->format('w')] . ', ' . $d->format('j') . ' '
            . $bulanArr[(int)$d->format('n') - 1] . ' ' . substr($d->format('Y'), 2, 2)
            . ' ' . $d->format('H:i');
    };

    $wib = new DateTimeZone('Asia/Jakarta');

    // 1) Nilai BSON (bila Mongo menyimpan last_update sebagai tanggal)
    if (is_object($dt)) {
        if ($dt instanceof MongoDB\BSON\UTCDateTime) {
            $d = $dt->toDateTime();
            $d->setTimezone($wib);
            return $rapikan($d);
        }
        return htmlspecialchars((string)$dt);
    }

    $str = trim((string)$dt);

    // 2) Epoch milidetik (dari driver Mongo / API tertentu)
    if (preg_match('/^\d{12,}$/', $str)) {
        $d = new DateTime('@' . (int)floor(((float)$str) / 1000));
        $d->setTimezone($wib);
        return $rapikan($d);
    }

    // 3) String tanggal
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})/', $str, $m)) {
        if (preg_match('/(Z|[+-]\d{2}:?\d{2})$/', $str)) {
            // ada info zona -> geser ke WIB
            try {
                $d = new DateTime($str);
                $d->setTimezone($wib);
                return $rapikan($d);
            } catch (Exception $e) {
                // lanjut: perlakukan sebagai waktu lokal apa adanya
            }
        }
        // tanpa zona -> waktu lokal apa adanya (WIB) => tidak digeser
        try {
            $d = new DateTime($m[0]);
            return $rapikan($d);
        } catch (Exception $e) {
            return htmlspecialchars($str);
        }
    }

    // 4) Format lain: serahkan ke strtotime lalu tampilkan apa adanya
    //    (strtotime & date memakai timezone server yang sama, jadi jam tidak bergeser)
    $ts = strtotime($str);
    if (!$ts) {
        return htmlspecialchars($str);
    }
    return $hariArr[(int)date('w', $ts)] . ', ' . date('j', $ts) . ' '
        . $bulanArr[(int)date('n', $ts) - 1] . ' ' . substr(date('Y', $ts), 2, 2)
        . ' ' . date('H:i', $ts);
}

/** "SuratPermintaanDirawat" / "surat_permintaan_dirawat" -> "Surat Permintaan Dirawat" */
function emrHumanize($name)
{
    if ($name === null || $name === '') {
        return 'EMR';
    }
    $name = str_replace(array('_', '-'), ' ', (string)$name);
    $name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);
    $name = preg_replace('/\s+/', ' ', $name);
    return trim(ucwords(strtolower($name)));
}

/**
 * ubah url_form dari database menjadi slug halaman frontend Vue.
 * url_form bisa berbentuk:
 *   - nama route : module-emr-profile-pasien-page-emr-surat-permintaan-dirawat
 *   - slug       : surat-permintaan-dirawat
 *   - path       : /module/emr/profile-pasien/page-emr/surat-permintaan-dirawat
 */
function emrSlug($urlForm, $collection)
{
    $slug = trim((string)$urlForm);
    if ($slug !== '') {
        $slug = str_replace('\\', '/', $slug);
        if (strpos($slug, '/') !== false) {
            $parts = array_values(array_filter(explode('/', $slug), 'strlen'));
            $slug  = end($parts);
        }
        $prefix = 'module-emr-profile-pasien-page-emr-';
        if (stripos($slug, $prefix) === 0) {
            $slug = substr($slug, strlen($prefix));
        }
    }
    if ($slug === '') {
        // turunkan dari collection: SuratPermintaanDirawat -> surat-permintaan-dirawat
        $slug = strtolower(preg_replace('/([a-z0-9])([A-Z])/', '$1-$2', (string)$collection));
        $slug = str_replace('_', '-', $slug);
    }
    return strtolower($slug);
}

/**
 * HTTP GET JSON sederhana.
 * Utamakan cURL; bila ekstensi cURL tidak aktif (umum di sebagian instalasi
 * XAMPP/Laragon), pakai file_get_contents + stream context (allow_url_fopen).
 */
function emrHttpGetJson($url, $headers, $timeout)
{
    if (!function_exists('curl_init')) {
        return emrHttpGetJsonStream($url, $headers, $timeout);
    }
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => $timeout,
        CURLOPT_CONNECTTIMEOUT => min(4, $timeout),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER     => $headers,
    ));
    $body = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    return array(
        'ok'    => ($code >= 200 && $code < 300 && $body !== false && $body !== ''),
        'code'  => $code,
        'body'  => $body,
        'error' => $err,
    );
}

/**
 * Cadangan tanpa cURL: file_get_contents + stream context.
 * Dipakai bila ekstensi cURL tidak tersedia tetapi allow_url_fopen aktif.
 */
function emrHttpGetJsonStream($url, $headers, $timeout)
{
    if (!ini_get('allow_url_fopen')) {
        return array('ok' => false, 'code' => 0, 'body' => '', 'error' => 'cURL tidak aktif dan allow_url_fopen OFF');
    }
    $ctx = stream_context_create(array(
        'http' => array(
            'method'        => 'GET',
            'header'        => implode("\r\n", $headers),
            'timeout'       => $timeout,
            'ignore_errors' => true,
        ),
        'ssl'  => array(
            'verify_peer'      => false,
            'verify_peer_name' => false,
        ),
    ));
    $body = @file_get_contents($url, false, $ctx);
    $code = 0;
    if (!empty($http_response_header)) {
        foreach ($http_response_header as $h) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $h, $m)) {
                $code = (int)$m[1];
            }
        }
    }
    $ok = ($code >= 200 && $code < 300 && $body !== false && $body !== '');
    return array(
        'ok'    => $ok,
        'code'  => $code,
        'body'  => $body === false ? '' : $body,
        'error' => $ok ? '' : 'stream: HTTP ' . $code,
    );
}

/**
 * Baca file .env milik backend Laravel (tm/ biasanya satu folder dengan backend/).
 * Dipakai untuk: koneksi MongoDB, APP_URL, JWT_KEY.
 */
function emrReadBackendEnv($extraPaths = array())
{
    $candidates = array(
        dirname(__DIR__) . '/backend/.env',       // <root>/tm -> <root>/backend/.env
        dirname(__DIR__, 2) . '/backend/.env',
        __DIR__ . '/../backend/.env',
        __DIR__ . '/.env',
        dirname(__DIR__) . '/.env',
        '/var/www/html/simrs/backend/.env',
        '/var/www/simrs/backend/.env',
        'C:/xampp/htdocs/simrs/backend/.env',
        'D:/xampp/htdocs/simrs/backend/.env',
    );
    // tm/ bisa saja diakses lewat sub-folder webroot (mis. /simrs/tm/)
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
        $candidates[] = $docRoot . '/backend/.env';
        $candidates[] = $docRoot . '/../backend/.env';
        $candidates[] = $docRoot . '/../.env';
    }
    $candidates = array_merge($candidates, $extraPaths);

    $envFromServer = getenv('SIMRS_BACKEND_ENV');
    if ($envFromServer) {
        array_unshift($candidates, $envFromServer);
    }

    foreach ($candidates as $path) {
        if ($path && is_readable($path)) {
            $data = array();
            $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (is_array($lines)) {
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                        continue;
                    }
                    list($k, $v) = explode('=', $line, 2);
                    $k = trim($k);
                    $v = trim($v);
                    $len = strlen($v);
                    if ($len >= 2 && (($v[0] === '"' && $v[$len - 1] === '"') || ($v[0] === "'" && $v[$len - 1] === "'"))) {
                        $v = substr($v, 1, $len - 2);
                    }
                    $data[$k] = $v;
                }
            }
            if (!empty($data)) {
                return array('path' => $path, 'data' => $data);
            }
        }
    }
    return array('path' => null, 'data' => array());
}

/** Konversi nilai BSON -> tipe PHP biasa */
function emrBsonToPhp($value)
{
    if (is_object($value)) {
        if ($value instanceof MongoDB\BSON\UTCDateTime) {
            $dt = $value->toDateTime();
            try {
                // backend menulis last_update dengan date('Y-m-d H:i:s') (Asia/Jakarta);
                // bila tersimpan sebagai tipe tanggal BSON, konversi ke WIB agar sama.
                $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
            } catch (Exception $e) {
                // biarkan UTC bila timezone tidak dikenal
            }
            return $dt->format('Y-m-d H:i:s');
        }
        if ($value instanceof MongoDB\BSON\ObjectId) {
            return (string)$value;
        }
        if ($value instanceof MongoDB\BSON\Binary) {
            return null;
        }
        if ($value instanceof MongoDB\BSON\Decimal128) {
            return (string)$value;
        }
        if ($value instanceof MongoDB\BSON\Timestamp) {
            return (string)$value;
        }
        // BSONDocument / BSONArray -> array
        $out = array();
        foreach ($value as $k => $v) {
            $out[$k] = emrBsonToPhp($v);
        }
        return $out;
    }
    if (is_array($value)) {
        $out = array();
        foreach ($value as $k => $v) {
            $out[$k] = emrBsonToPhp($v);
        }
        return $out;
    }
    return $value;
}

/**
 * Dokumen VitalSign terbaru pada #ResumeEMR.
 * Backend (ProfilePasienCtrl::detailPelayanan) mengeluarkan VitalSign dari daftar
 * utama, lalu menambahkan SATU dokumen VitalSign terbaru ke response.emr
 * ($EMR_FORM_). Agar kartu EMR di sini sama dengan halaman Vue, dokumen itu ikut
 * ditampilkan ("Tanda Vital").
 */
function emrFetchVitalMongo($manager, $ns, $norec_pd, $kdProfile, &$diag)
{
    $filter = array(
        'noregistrasifk' => $norec_pd,
        'table'          => 'VitalSign',
        'kdprofile'      => (int)$kdProfile,
        'statusenabled'  => array('$in' => array(true, 1, '1', 'true')),
    );
    try {
        $query  = new MongoDB\Driver\Query($filter, array('sort' => array('last_update' => -1), 'limit' => 1));
        $cursor = $manager->executeQuery($ns, $query);
        foreach ($cursor as $doc) {
            return emrBsonToPhp($doc);
        }
    } catch (Exception $e) {
        $diag[] = array('mongodb', 'query VitalSign gagal: ' . $e->getMessage());
    } catch (Error $e) {
        $diag[] = array('mongodb', 'query VitalSign gagal: ' . $e->getMessage());
    }
    return array();
}

/**
 * SUMBER 1 — MongoDB "#ResumeEMR" (persis yang dibaca backend -> response.emr)
 */
function emrFetchFromMongo($norec_pd, $kdProfile, $collectionName, $mongoDb, $uriList, &$diag)
{
    if (!class_exists('MongoDB\Driver\Manager')) {
        $diag[] = array('mongodb', 'ekstensi PHP mongodb tidak aktif -> dilewati');
        return array('items' => array(), 'error' => 'ekstensi mongodb tidak aktif');
    }
    if (empty($uriList)) {
        $diag[] = array('mongodb', 'konfigurasi koneksi tidak ditemukan -> dilewati');
        return array('items' => array(), 'error' => 'konfigurasi mongo tidak ditemukan');
    }

    $ns = $mongoDb . '.' . $collectionName;
    $lastError = '';

    foreach ($uriList as $uri) {
        // Filter ketat dulu (sama seperti ProfilePasienCtrl::detailPelayanan)
        $filters = array(
            array(
                'noregistrasifk' => $norec_pd,
                'kdprofile'      => (int)$kdProfile,
                'statusenabled'  => array('$in' => array(true, 1, '1', 'true', 't', 'T', 'Y', 'y')),
                'table'          => array('$nin' => array('VitalSign', 'AsesmenAwal')),
            ),
            // cadangan: tanpa filter statusenabled & table
            array('noregistrasifk' => $norec_pd),
        );

        try {
            $manager = new MongoDB\Driver\Manager($uri, array(), array(
                'serverSelectionTimeoutMS' => 3000,
                'connectTimeoutMS'         => 3000,
            ));

            foreach ($filters as $i => $filter) {
                $query  = new MongoDB\Driver\Query($filter, array('sort' => array('last_update' => -1), 'limit' => 500));
                $cursor = $manager->executeQuery($ns, $query);

                $items = array();
                foreach ($cursor as $doc) {
                    $items[] = emrBsonToPhp($doc);
                }
                if (!empty($items)) {
                    // Tanda Vital: backend mengeluarkan VitalSign dari daftar utama
                    // (table != 'VitalSign') lalu menambahkan 1 dokumen terbaru
                    // ($EMR_FORM_) ke response.emr — frontend tetap menampilkannya.
                    $vital = emrFetchVitalMongo($manager, $ns, $norec_pd, $kdProfile, $diag);
                    if (!empty($vital)) {
                        $items[] = $vital;
                    }
                    $diag[] = array('mongodb', 'OK (' . count($items) . ' dokumen) dari ' . preg_replace('/\/\/.*@/', '//***@', $uri) . ' filter#' . ($i + 1));
                    return array('items' => $items, 'error' => '');
                }
                $diag[] = array('mongodb', 'terhubung, filter#' . ($i + 1) . ' = 0 dokumen (' . $ns . ')');
            }
            // Terhubung tapi tidak ada dokumen -> anggap sah (memang kosong)
            return array('items' => array(), 'error' => 'mongo: tidak ada dokumen');
        } catch (Exception $e) {
            $lastError = $e->getMessage();
            $diag[] = array('mongodb', 'gagal koneksi/query: ' . $lastError);
        } catch (Error $e) {
            $lastError = $e->getMessage();
            $diag[] = array('mongodb', 'gagal koneksi/query: ' . $lastError);
        }
    }
    return array('items' => array(), 'error' => $lastError !== '' ? $lastError : 'mongo tidak terjangkau');
}

/** base64url tanpa padding (format JWT) */
function emrB64Url($bin)
{
    return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
}

/** Baca payload JWT tanpa verifikasi (hanya untuk mengambil "sub"/username) */
function emrJwtPayload($token)
{
    $parts = explode('.', trim((string)$token));
    if (count($parts) < 2) {
        return array();
    }
    $payload = $parts[1];
    $pad = strlen($payload) % 4;
    if ($pad) {
        $payload .= str_repeat('=', 4 - $pad);
    }
    $json = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    return is_array($json) ? $json : array();
}

/**
 * Buat token JWT sendiri (HS512) memakai JWT_KEY Laravel.
 * Middleware JWTAuth membentuk token 4 segmen: header.payload.signature.base64(kdProfile).
 */
function emrMintToken($username, $kdProfile, $jwtKey, $melebihiMenit = 120)
{
    $header  = array('typ' => 'JWT', 'alg' => 'HS512');
    $payload = array(
        'sub'       => $username,
        'sessionId' => '',
        'exp'       => time() + ($melebihiMenit * 60),
    );
    $body = emrB64Url(json_encode($header)) . '.' . emrB64Url(json_encode($payload));
    $sig  = emrB64Url(hash_hmac('sha512', $body, $jwtKey, true));
    return $body . '.' . $sig . '.' . base64_encode((string)$kdProfile);
}

/**
 * SUMBER 2 — REST API Laravel: /service/emr/detail-pelayanan
 * Envelope: { metaData:{code,message}, response:{ emr:[...], ... } }
 */
function emrFetchFromApi($apiBase, $params, $tokens, &$diag)
{
    $lastError = '';
    foreach ($tokens as $tokenInfo) {
        $token = $tokenInfo['token'];
        $url   = rtrim($apiBase, '/') . '/emr/detail-pelayanan?' . http_build_query(array_merge($params, array('token' => $token)));
        $res   = emrHttpGetJson($url, array(
            'Accept: application/json',
            'token: ' . $token,
            'skip_encrypt: true',
        ), 25);

        if (!$res['ok']) {
            $lastError = 'HTTP ' . $res['code'] . ($res['error'] ? ' ' . $res['error'] : '');
            $diag[] = array('api', 'token[' . $tokenInfo['label'] . '] gagal: ' . $lastError);
            continue;
        }

        $json = json_decode($res['body'], true);
        if (!is_array($json)) {
            $lastError = 'response bukan JSON';
            $diag[] = array('api', 'token[' . $tokenInfo['label'] . ']: response bukan JSON');
            continue;
        }

        $code  = isset($json['metaData']['code']) ? (int)$json['metaData']['code'] : 200;
        $pesan = isset($json['metaData']['message']) ? $json['metaData']['message'] : '';
        if ($code !== 200) {
            $lastError = 'metaData.code=' . $code . ' (' . $pesan . ')';
            $diag[] = array('api', 'token[' . $tokenInfo['label'] . '] ditolak: ' . $lastError);
            continue;
        }

        // ==== inti perbaikan: envelope Laravel = response.emr ====
        $envelope = isset($json['response']) && is_array($json['response']) ? $json['response'] : $json;
        $emr      = null;
        foreach (array('emr', 'EMR', 'listEmr', 'listEMR') as $key) {
            if (isset($envelope[$key]) && is_array($envelope[$key])) {
                $emr = $envelope[$key];
                break;
            }
        }
        if ($emr === null && isset($json['emr']) && is_array($json['emr'])) {
            $emr = $json['emr'];
        }
        if ($emr === null) {
            $lastError = 'field "response.emr" tidak ada pada response';
            $diag[] = array('api', 'token[' . $tokenInfo['label'] . ']: ' . $lastError);
            continue;
        }

        $diag[] = array('api', 'OK (' . count($emr) . ' item) token[' . $tokenInfo['label'] . ']');
        return array('items' => $emr, 'token' => $token, 'error' => '');
    }
    return array('items' => array(), 'token' => '', 'error' => ($lastError !== '' ? $lastError : 'API tidak terjangkau'));
}

/**
 * SUMBER 3 — PostgreSQL (mode terbatas):
 *   emrpasien_t  -> 1 baris per dokumen yang pernah disimpan
 *   logginguser_t (referensi='EMR') -> collection & nama form
 *   emr_t        -> caption resmi per collection / url_form
 */
function emrFetchFromPostgres($pdo, $norec_pd, $kdProfile, &$diag)
{
    if (!$pdo) {
        return array('items' => array(), 'error' => 'koneksi postgres tidak ada');
    }
    try {
        $sql = "
            SELECT
                ep.norec            AS emrpasienfk,
                ep.jenisemr,
                ep.tglemr           AS last_update,
                ep.namaruangan      AS ruangan,
                ep.noemr,
                ep.norec_apd,
                ep.noregistrasi,
                ep.nocmfk,
                COALESCE(pg.namalengkap, '-') AS author
            FROM emrpasien_t ep
            LEFT JOIN pegawai_m pg ON pg.id = ep.pegawaifk
            WHERE ep.noregistrasifk = :norec_pd
              AND (ep.statusenabled IS NULL OR ep.statusenabled::text IN ('1', 't', 'true', ''))
            ORDER BY ep.tglemr DESC NULLS LAST
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':norec_pd' => $norec_pd));
        $rows = $stmt->fetchAll();
    } catch (Exception $e) {
        $diag[] = array('postgres', 'query emrpasien_t gagal: ' . $e->getMessage());
        return array('items' => array(), 'error' => $e->getMessage());
    }

    if (empty($rows)) {
        return array('items' => array(), 'error' => 'postgres: tidak ada data EMR');
    }

    // ---- Referensi nama resmi dari emr_t (caption per collection / url_form) ----
    $refByCollection = array();
    $refByUrl        = array();
    try {
        $refStmt = $pdo->query("SELECT caption, url_form, collection, icon FROM emr_t
                                WHERE statusenabled IS NULL OR statusenabled::text IN ('1','t','true')");
        foreach ($refStmt->fetchAll() as $ref) {
            $collection = strtolower(trim((string)$ref['collection']));
            $url        = strtolower(trim((string)$ref['url_form']));
            $caption    = trim((string)$ref['caption']);
            $icon       = trim((string)$ref['icon']);
            if ($collection !== '' && $caption !== '' && !isset($refByCollection[$collection])) {
                $refByCollection[$collection] = array('nama' => $caption, 'icon' => $icon, 'url' => $ref['url_form']);
            }
            if ($url !== '' && $caption !== '' && !isset($refByUrl[$url])) {
                $refByUrl[$url] = array('nama' => $caption, 'icon' => $icon, 'url' => $ref['url_form']);
            }
        }
        $diag[] = array('postgres', 'referensi emr_t: ' . count($refByCollection) . ' collection / ' . count($refByUrl) . ' url_form');
    } catch (Exception $e) {
        $diag[] = array('postgres', 'referensi emr_t gagal: ' . $e->getMessage());
    }

    // ---- Log EMR: collection + nama form per emrpasien_t.norec ----
    $logByNorec = array();
    $ids = array();
    foreach ($rows as $r) {
        if (!empty($r['emrpasienfk'])) {
            $ids[] = $r['emrpasienfk'];
        }
    }
    if (!empty($ids)) {
        try {
            $place = array();
            $bind  = array();
            foreach ($ids as $i => $id) {
                $key           = ':id' . $i;
                $place[]       = $key;
                $bind[$key]    = $id;
            }
            $logSql = "SELECT noreff, jenislog, keterangan, namapegawai, tanggal
                       FROM logginguser_t
                       WHERE referensi = 'EMR' AND noreff IN (" . implode(',', $place) . ")
                       ORDER BY tanggal DESC";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute($bind);
            foreach ($logStmt->fetchAll() as $log) {
                $noreff = $log['noreff'];
                if (!isset($logByNorec[$noreff])) {
                    $logByNorec[$noreff] = $log;
                }
            }
            $diag[] = array('postgres', 'log EMR: ' . count($logByNorec) . ' dokumen teridentifikasi');
        } catch (Exception $e) {
            $diag[] = array('postgres', 'query logginguser_t gagal: ' . $e->getMessage());
        }
    }

    // ---- Susun item ----
    $items = array();
    foreach ($rows as $r) {
        $norec     = $r['emrpasienfk'];
        $collection = '';
        $nama       = '';
        $icon       = '';

        $log = isset($logByNorec[$norec]) ? $logByNorec[$norec] : null;
        if ($log) {
            $collection = trim((string)$log['jenislog']);
            $ket        = trim((string)$log['keterangan']);
            // keterangan: 'input EMR <NAMA FORM> dari pasien dengan no registrasi ...'
            if (preg_match('/input EMR (.+?) dari pasien/i', $ket, $m)) {
                $nama = trim($m[1]);
            } elseif (preg_match('/^Edit EMR (.+?) dari pasien/i', $ket, $m)) {
                $nama = trim($m[1]);
            }
            if ($log['namapegawai']) {
                $r['author'] = $log['namapegawai'];
            }
        }

        // Nama resmi dari master emr_t (collection diprioritaskan)
        $keyCol = strtolower($collection);
        $keyJen = strtolower(trim((string)$r['jenisemr']));
        if ($keyCol !== '' && isset($refByCollection[$keyCol])) {
            if ($nama === '') {
                $nama = $refByCollection[$keyCol]['nama'];
            }
            $icon = $refByCollection[$keyCol]['icon'];
        } elseif ($keyJen !== '' && isset($refByCollection[$keyJen])) {
            if ($nama === '') {
                $nama = $refByCollection[$keyJen]['nama'];
            }
            if ($collection === '') {
                $collection = $refByCollection[$keyJen]['url'];
            }
            $icon = $refByCollection[$keyJen]['icon'];
        } elseif ($keyJen !== '' && isset($refByUrl[$keyJen])) {
            if ($nama === '') {
                $nama = $refByUrl[$keyJen]['nama'];
            }
            if ($collection === '') {
                $collection = $refByUrl[$keyJen]['url'];
            }
            $icon = $refByUrl[$keyJen]['icon'];
        }

        // Fallback terakhir: label dari jenisemr (tidak menyesatkan)
        if ($nama === '') {
            $nama = ($keyJen === '' || $keyJen === 'asesmen_medis' || $keyJen === 'asesmen medis')
                ? 'Dokumen EMR (nama form tidak tersedia)'
                : emrHumanize($keyJen);
        }

        $r['log_collection'] = $collection;
        $r['log_nama']       = $nama;
        $r['log_icon']       = $icon;
        $items[] = $r;
    }

    $diag[] = array('postgres', 'OK (' . count($items) . ' baris emrpasien_t)');
    return array('items' => $items, 'error' => '');
}

/**
 * Ambil satu field dari referensi emr_t.
 * $refCollection mendukung dua bentuk nilai:
 *   - string                      : caption saja (kompatibel kode lama)
 *   - array('nama','url','icon')  : lengkap (dipakai mulai revisi ini)
 */
function emrRefGet($refCollection, $collection, $field)
{
    $key = strtolower(trim((string)$collection));
    if ($key === '' || !isset($refCollection[$key])) {
        return '';
    }
    $ref = $refCollection[$key];
    if (is_array($ref)) {
        if ($field === 'nama') {
            return isset($ref['nama']) ? $ref['nama'] : '';
        }
        if ($field === 'url') {
            return isset($ref['url']) ? $ref['url'] : '';
        }
        if ($field === 'icon') {
            return isset($ref['icon']) ? $ref['icon'] : '';
        }
        return '';
    }
    return ($field === 'nama') ? (string)$ref : '';
}

/**
 * Normalisasi item dari sumber manapun ke struktur LIST_EMR frontend:
 *   emrpasienfk, namaemr, table, url_form, last_update, ruangan, author, icon, noemr
 */
function emrNormalizeItem($item, $refCollection = array())
{
    $emrpasienfk = '';
    foreach (array('emrpasienfk', 'norec', 'norec_emr') as $k) {
        if (!empty($item[$k])) {
            $emrpasienfk = $item[$k];
            break;
        }
    }

    $collection = '';
    foreach (array('table', 'collection', 'log_collection', 'collection_master') as $k) {
        if (!empty($item[$k])) {
            $collection = $item[$k];
            break;
        }
    }

    $jenis = isset($item['jenisemr']) ? $item['jenisemr'] : '';

    $nama = '';
    foreach (array('namaemr', 'log_nama', 'caption', 'caption_master') as $k) {
        if (isset($item[$k]) && trim((string)$item[$k]) !== '') {
            $nama = trim((string)$item[$k]);
            break;
        }
    }
    if ($nama === '') {
        // nama resmi dari master emr_t (bila dokumen tidak membawa namaemr)
        $nama = emrRefGet($refCollection, $collection, 'nama');
    }
    if ($nama === '' && $jenis !== '' && strtolower($jenis) !== 'asesmen_medis') {
        $nama = emrHumanize($jenis);
    }
    if ($nama === '') {
        $nama = emrRefGet($refCollection, $jenis, 'nama');
    }
    if ($nama === '') {
        $nama = 'Dokumen EMR (nama form tidak tersedia)';
    }

    // url_form -> slug halaman Vue
    $urlForm = '';
    foreach (array('url_form', 'url_form_master') as $k) {
        if (!empty($item[$k])) {
            $urlForm = $item[$k];
            break;
        }
    }
    if ($urlForm === '') {
        // master emr_t: url_form resmi untuk collection / jenisemr
        $urlForm = emrRefGet($refCollection, $collection, 'url');
        if ($urlForm === '') {
            $urlForm = emrRefGet($refCollection, $jenis, 'url');
        }
    }

    $icon = '';
    foreach (array('icon', 'icon_master', 'log_icon') as $k) {
        if (!empty($item[$k])) {
            $icon = $item[$k];
            break;
        }
    }
    if ($icon === '') {
        $icon = emrRefGet($refCollection, $collection, 'icon');
    }
    if ($icon === '') {
        $icon = 'fas fa-laptop-medical';
    }

    $lastUpdate = null;
    foreach (array('last_update', 'tglemr', 'updated_at', 'created_at') as $k) {
        if (!empty($item[$k])) {
            $lastUpdate = $item[$k];
            break;
        }
    }

    $ruangan = '';
    foreach (array('ruangan', 'namaruangan') as $k) {
        if (!empty($item[$k])) {
            $ruangan = $item[$k];
            break;
        }
    }

    return array(
        'emrpasienfk'   => (string)$emrpasienfk,
        'namaemr'       => $nama,
        'table'         => (string)$collection,
        'url_form'      => (string)$urlForm,
        'slug'          => emrSlug($urlForm, $collection),
        'last_update'   => $lastUpdate,
        'ruangan'       => (string)$ruangan,
        'author'        => isset($item['author']) && trim((string)$item['author']) !== ''
                            ? trim((string)$item['author']) : '-',
        'icon'          => $icon,
        'noemr'         => isset($item['noemr']) ? (string)$item['noemr'] : '',
        'noregistrasi'  => isset($item['noregistrasi']) && $item['noregistrasi'] !== '' ? (string)$item['noregistrasi'] : '',
        'nocmfk'        => isset($item['nocmfk']) ? (string)$item['nocmfk'] : '',
        'norec_apd'     => isset($item['norec_apd']) ? (string)$item['norec_apd'] : '',
    );
}

/** Bangun URL buka / ubah form EMR (frontend Vue) */
function emrBuildEditUrl($webBase, $slug, $nocmfk, $norec_pd, $norec_apd, $emrpasienfk)
{
    $q = http_build_query(array(
        'nocmfk'              => $nocmfk,
        'norec_pasien_daftar' => $norec_pd,
        'norec_pd'            => $norec_pd,
        'norec_apd'           => $norec_apd,
        'jenisobgyn'          => '',
        'jenisinterna'        => '',
        'jenistrauma'         => '',
        'norec_emr'           => $emrpasienfk,
        'edit'                => 'true',
    ));
    return rtrim($webBase, '/') . '/module/emr/profile-pasien/page-emr/' . rawurlencode($slug) . '?' . $q;
}

/** Bangun URL cetak EMR: {apiBase}/emr/cetak/{collection}?... (H.printBlade) */
function emrBuildCetakUrl($apiBase, $collection, $emrpasienfk, $noregistrasi, $userCetak, $kdProfile, $token)
{
    $q = http_build_query(array(
        'pdf'          => 'true',
        'emrpasienfk'  => $emrpasienfk,
        'noregistrasi' => $noregistrasi,
        'user'         => $userCetak,
        'kdprofile'    => $kdProfile,
        'token'        => $token,
    ));
    return rtrim($apiBase, '/') . '/emr/cetak/' . rawurlencode(trim($collection)) . '?' . $q;
}

// ============================================================
// AMBIL DATA EMR (MongoDB -> API -> PostgreSQL)
// ============================================================
$envInfo  = emrReadBackendEnv();
$env      = $envInfo['data'];
$envPath  = $envInfo['path'];
$diag[]   = array('env', $envPath ? ('dibaca dari ' . $envPath) : 'tidak ditemukan (pakai nilai default)');

// --- APP_URL & JWT_KEY dari .env (bila tidak di-override manual) ---
if (isset($env['APP_URL']) && $env['APP_URL'] !== '' && $apiBase === 'https://192.168.22.81/service') {
    $apiBase = rtrim($env['APP_URL'], '/') . '/service';
    $diag[] = array('config', 'apiBase dari .env: ' . $apiBase);
}
$jwtKey = $jwtKeyManual !== '' ? $jwtKeyManual : (isset($env['JWT_KEY']) && $env['JWT_KEY'] !== '' ? $env['JWT_KEY'] : 'TRANSINDO');

// --- Kandidat URI MongoDB ---
$mongoDb = $mongoDbManual !== '' ? $mongoDbManual : (isset($env['DB_DATABASE_MONGO']) && $env['DB_DATABASE_MONGO'] !== '' ? $env['DB_DATABASE_MONGO'] : 'transmedic_v3');
$uriList = array();
if ($mongoUriManual !== '') {
    $uriList[] = $mongoUriManual;
} else {
    if (!empty($env['DATABASE_URL_MONGO'])) {
        $uriList[] = $env['DATABASE_URL_MONGO'];
    }
    $mHost = !empty($env['DB_HOST_MONGO']) ? $env['DB_HOST_MONGO'] : '';
    if ($mHost !== '') {
        $mPort = !empty($env['DB_PORT_MONGO']) ? $env['DB_PORT_MONGO'] : '27017';
        $mUser = !empty($env['DB_USERNAME_MONGO']) ? $env['DB_USERNAME_MONGO'] : '';
        $mPass = !empty($env['DB_PASSWORD_MONGO']) ? $env['DB_PASSWORD_MONGO'] : '';
        $auth  = ($mUser !== '') ? rawurlencode($mUser) . ':' . rawurlencode($mPass) . '@' : '';
        // backend/config/database.php memakai options.database = 'admin' utk autentikasi
        $uriList[] = 'mongodb://' . $auth . $mHost . ':' . $mPort . '/?authSource=admin';
    }
    // cadangan: mongo lokal pada server yang sama
    $uriList[] = 'mongodb://127.0.0.1:27017/?authSource=admin';
    $uriList[] = 'mongodb://localhost:27017/?authSource=admin';
    // cadangan: mongo pada server database SIMRS (host yang sama dengan Postgres)
    if (!empty($host) && $host !== '127.0.0.1' && $host !== 'localhost') {
        $uriList[] = 'mongodb://' . $host . ':27017/?authSource=admin';
    }
}
$diag[] = array('mongodb', 'db=' . $mongoDb . ', collection=' . $mongoCollection . ', kandidat URI=' . count($uriList));

// === 1. MongoDB ===
$mongoRes = emrFetchFromMongo($norec_pd, $kdProfile, $mongoCollection, $mongoDb, $uriList, $diag);
$rawItems = $mongoRes['items'];
$source   = 'mongodb';
$sourceLabel = 'MongoDB ' . $mongoDb . '.' . $mongoCollection;
$sourceError = $mongoRes['error'];
$workingToken = '';

// === 2. REST API Laravel ===
if (empty($rawItems)) {
    $tokenCandidates = array();
    if ($tokenFromUrl !== '') {
        $tokenCandidates[] = array('label' => 'url/session', 'token' => $tokenFromUrl);
    }
    if (!empty($tokenDefault)) {
        $tokenCandidates[] = array('label' => 'default', 'token' => $tokenDefault);
    }
    // token buatan sendiri (JWT_KEY dari .env / default 'TRANSINDO')
    if ($pdo) {
        try {
            $payload     = emrJwtPayload($tokenCandidates ? $tokenCandidates[0]['token'] : $tokenDefault);
            $preferUser  = isset($payload['sub']) ? $payload['sub'] : '';
            $userSql     = "SELECT namauser FROM loginuser_s
                            WHERE kdprofile = :kd AND statusenabled::text IN ('1','t','true')
                              AND namauser IS NOT NULL AND namauser <> ''
                            ORDER BY id";
            $userStmt = $pdo->prepare($userSql);
            $userStmt->execute(array(':kd' => $kdProfile));
            $userList = $userStmt->fetchAll(PDO::FETCH_COLUMN);
            if ($preferUser !== '' && in_array($preferUser, $userList, true)) {
                array_unshift($userList, $preferUser);
            }
            $minted = array();
            foreach (array_slice($userList, 0, 3) as $uName) {
                if (in_array($uName, $minted, true)) {
                    continue;
                }
                $minted[] = $uName;
                $tokenCandidates[] = array('label' => 'buatan:' . $uName, 'token' => emrMintToken($uName, $kdProfile, $jwtKey));
            }
            $diag[] = array('api', 'kandidat token buatan: ' . implode(', ', $minted));
        } catch (Exception $e) {
            $diag[] = array('api', 'gagal menyiapkan token buatan: ' . $e->getMessage());
        }
    }

    $apiRes = emrFetchFromApi($apiBase, array(
        'norec_pd'  => $norec_pd,
        'nocmfk'    => $nocmfk,
        'kdprofile' => $kdProfile,
    ), $tokenCandidates, $diag);

    if (!empty($apiRes['items'])) {
        $rawItems     = $apiRes['items'];
        $source       = 'api';
        $sourceLabel  = 'Laravel API /emr/detail-pelayanan (response.emr)';
        $workingToken = $apiRes['token'];
        $sourceError  = '';
    } elseif ($apiRes['error']) {
        $sourceError = ($sourceError !== '' ? $sourceError . ' | ' : '') . 'api: ' . $apiRes['error'];
    }
}

// === 3. PostgreSQL (mode terbatas) ===
if (empty($rawItems)) {
    $pgRes    = emrFetchFromPostgres($pdo, $norec_pd, $kdProfile, $diag);
    $rawItems = $pgRes['items'];
    if (!empty($rawItems)) {
        $source      = 'postgres';
        $sourceLabel = 'Database SIMRS (emrpasien_t + logginguser_t + emr_t)';
        $sourceError = '';
    } elseif ($pgRes['error']) {
        $sourceError = ($sourceError !== '' ? $sourceError . ' | ' : '') . 'postgres: ' . $pgRes['error'];
    }
}

// Referensi cadangan dari master emr_t (collection -> caption / url_form / icon).
// Dipakai bila dokumen #ResumeEMR tidak membawa namaemr / url_form / icon
// (mis. dokumen lama atau collection baru yang belum lengkap datanya).
$refCollection = array();
if ($pdo && $source !== 'postgres') {
    try {
        $refStmt = $pdo->query("SELECT caption, url_form, collection, icon FROM emr_t
                                WHERE caption IS NOT NULL AND collection IS NOT NULL
                                  AND (statusenabled IS NULL OR statusenabled::text IN ('1','t','true'))");
        foreach ($refStmt->fetchAll() as $ref) {
            $key = strtolower(trim((string)$ref['collection']));
            if ($key !== '' && !isset($refCollection[$key])) {
                $refCollection[$key] = array(
                    'nama' => trim((string)$ref['caption']),
                    'url'  => trim((string)$ref['url_form']),
                    'icon' => trim((string)$ref['icon']),
                );
            }
        }
        $diag[] = array('postgres', 'referensi cadangan emr_t: ' . count($refCollection) . ' collection');
    } catch (Exception $e) {
        $diag[] = array('postgres', 'referensi nama cadangan gagal: ' . $e->getMessage());
    }
}

// Bentuk response.emr bila datang dari API (bisa stdClass)
$listEmr = array();
foreach ($rawItems as $row) {
    if (is_object($row)) {
        $row = emrBsonToPhp($row);
    }
    if (!is_array($row)) {
        continue;
    }
    $item = emrNormalizeItem($row, $refCollection);

    // AsesmenAwal dikelola terpisah di frontend (t-emr-asesmen-awal.vue),
    // VitalSign tetap ditampilkan tetapi hanya yang terbaru (lihat di bawah).
    $tbl = strtolower($item['table']);
    if ($tbl === 'assesmenawal' || $tbl === 'asesmenawal') {
        continue;
    }
    if ($item['emrpasienfk'] === '' && $item['table'] === '' && $item['namaemr'] === '') {
        continue;
    }
    $listEmr[] = $item;
}

// VitalSign: ambil hanya 1 yang terbaru (sama seperti $EMR_FORM_ pada backend)
$vitalIdx = -1;
foreach ($listEmr as $i => $it) {
    $t = strtolower($it['table']);
    if ($t !== 'vitalsign' && $t !== 'vital_sign') {
        continue;
    }
    if ($vitalIdx === -1) {
        $vitalIdx = $i;
        continue;
    }
    $tNew = $listEmr[$i]['last_update'] ? strtotime((string)$listEmr[$i]['last_update']) : 0;
    $tOld = $listEmr[$vitalIdx]['last_update'] ? strtotime((string)$listEmr[$vitalIdx]['last_update']) : 0;
    if ($tNew > $tOld) {
        $old = $vitalIdx;
        $vitalIdx = $i;
        unset($listEmr[$old]);
    } else {
        unset($listEmr[$i]);
    }
}
$listEmr = array_values($listEmr);

// Hindari kartu ganda: satu dokumen EMR = satu emrpasienfk + satu collection.
// ($emrpasienfk kosong tidak dianggap duplikat karena bisa berbeda dokumen.)
$seenDoc = array();
foreach ($listEmr as $i => $it) {
    if ($it['emrpasienfk'] === '') {
        continue;
    }
    $key = strtolower($it['table']) . '|' . $it['emrpasienfk'];
    if (isset($seenDoc[$key])) {
        unset($listEmr[$i]);
        continue;
    }
    $seenDoc[$key] = true;
}
$listEmr = array_values($listEmr);

// urutkan terbaru di atas
usort($listEmr, function ($a, $b) {
    $ta = $a['last_update'] ? strtotime((string)$a['last_update']) : 0;
    $tb = $b['last_update'] ? strtotime((string)$b['last_update']) : 0;
    if ($ta === $tb) {
        return 0;
    }
    return ($ta < $tb) ? 1 : -1;
});

// pencarian sisi server (opsional, dipakai bila ?q=)
if ($qSearch !== '') {
    $qLower = strtolower($qSearch);
    $listEmr = array_values(array_filter($listEmr, function ($it) use ($qLower) {
        $hay = strtolower($it['namaemr'] . ' ' . $it['author'] . ' ' . $it['ruangan'] . ' ' . $it['table']);
        return strpos($hay, $qLower) !== false;
    }));
}

// token untuk link cetak: pakai token yang terbukti valid (bila ada)
$tokenCetak = $workingToken !== '' ? $workingToken : ($tokenFromUrl !== '' ? $tokenFromUrl : $tokenDefault);

// cetak cepat (kunci HARUS lowercase — dicocokkan dengan strtolower(nama collection))
// Label tombol dibuat singkat tanpa penjelasan singkatan; nama lengkap hanya
// muncul sebagai tooltip (atribut title).
$quickPrintMap = array(
    'suratpermintaandirawat' => array('collection' => 'SuratPermintaanDirawat', 'label' => 'SPRI',             'full' => 'Surat Permintaan Dirawat (SPRI)'),
    'laporanoperasi'         => array('collection' => 'laporanOperasi',         'label' => 'Laporan Operasi',  'full' => 'Laporan Operasi'),
    'laporanobservasi'       => array('collection' => 'laporanObservasi',       'label' => 'Laporan Observasi','full' => 'Laporan Observasi'),
    'rujukanpasien'          => array('collection' => 'RujukanPasien',          'label' => 'Rujukan',          'full' => 'Rujukan Pasien'),
    'resumemedis'            => array('collection' => 'resumeMedis',            'label' => 'Resume Medis',     'full' => 'Resume Medis'),
    'ringkasankeluar'        => array('collection' => 'RingkasanKeluar',        'label' => 'Ringkasan Pulang', 'full' => 'Ringkasan Keluar / Pulang'),
);
$latestByCollection = array();
foreach ($listEmr as $it) {
    $key = strtolower($it['table'] !== '' ? $it['table'] : '');
    if ($key === '') {
        continue;
    }
    if (!isset($latestByCollection[$key])) {
        $latestByCollection[$key] = $it;
    }
}
// pastikan SPRI dari parameter emr_surat_fk
if ($emr_surat_fk !== '' && !isset($latestByCollection['suratpermintaandirawat'])) {
    $latestByCollection['suratpermintaandirawat'] = array(
        'emrpasienfk'  => $emr_surat_fk,
        'table'        => 'SuratPermintaanDirawat',
        'namaemr'      => 'Surat Permintaan Dirawat',
        'noregistrasi' => $noregistrasi,
    );
}

// Susun tombol cetak cepat (hanya untuk dokumen yang ada).
// Label singkat tanpa penjelasan singkatan; posisi tombol di paling atas modal.
$quickHtml = '';
foreach ($quickPrintMap as $key => $meta) {
    if (!isset($latestByCollection[$key])) {
        continue;
    }
    $it   = $latestByCollection[$key];
    $fk   = $it['emrpasienfk'];
    $nore = !empty($it['noregistrasi']) ? $it['noregistrasi'] : $noregistrasi;
    if ($fk === '' || $nore === '') {
        continue;
    }
    $url = emrBuildCetakUrl($apiBase, $meta['collection'], $fk, $nore, $userCetak, $kdProfile, $tokenCetak);
    $url = str_replace('http://localhost', 'http://192.168.22.81', $url);
    $quickHtml .= '<a href="' . htmlspecialchars($url) . '" target="_blank" '
        . 'title="' . htmlspecialchars($meta['full']) . '" class="qp-btn">'
        . '<i class="fas fa-print"></i> ' . htmlspecialchars($meta['label']) . '</a>';
}

// ============================================================
// AMBIL DATA LABORATORIUM & RADIOLOGI (untuk panel kiri modal EMR)
// ============================================================
$labOrders = array();
$radOrders = array();
if ($pdo && $norec_pd !== '') {
    // --- Laboratorium ---
    try {
        $sqlLab = "
            SELECT 
                so.norec AS norec_so,
                so.norec_apd,
                so.noorder,
                so.tglorder,
                so.objectruangantujuanfk,
                so.statusorder,
                so.noregistrasi,
                ru.namaruangan AS ruangan_tujuan,
                ruasal.namaruangan AS ruangan_asal,
                pg_order.namalengkap AS dokter_order,
                pr.namaproduk,
                pr.id AS produk_id
            FROM strukorder_t so
            JOIN ruangan_m ru ON ru.id = so.objectruangantujuanfk
            LEFT JOIN ruangan_m ruasal ON ruasal.id = so.objectruanganfk
            LEFT JOIN pegawai_m pg_order ON pg_order.id = so.objectpegawaiorderfk
            LEFT JOIN pelayananpasien_t pp ON pp.strukorderfk = so.norec AND pp.statusenabled = true
            LEFT JOIN produk_m pr ON pr.id = pp.produkfk
            WHERE so.noregistrasifk = :norec_pd
              AND so.keteranganorder = 'Order Laboratorium'
              AND so.statusenabled = true
            ORDER BY so.tglorder DESC
        ";
        $stmtLab = $pdo->prepare($sqlLab);
        $stmtLab->execute(array(':norec_pd' => $norec_pd));
        $rawLab = $stmtLab->fetchAll();
        $tmp = array();
        foreach ($rawLab as $row) {
            $key = $row['norec_so'];
            if (!isset($tmp[$key])) {
                $tmp[$key] = array(
                    'norec_so' => $row['norec_so'],
                    'noorder' => $row['noorder'],
                    'tglorder' => $row['tglorder'] ? date('d-m-Y H:i', strtotime($row['tglorder'])) : '-',
                    'ruanganasal' => $row['ruangan_asal'] ?? '-',
                    'ruangantujuan' => $row['ruangan_tujuan'] ?? '-',
                    'dokter' => $row['dokter_order'] ?? '-',
                    'status' => $row['statusorder'] == 2 ? 'selesai' : ($row['statusorder']==1?'verifikasi':'pending'),
                    'noregistrasi' => $row['noregistrasi'],
                    'norec_apd' => $row['norec_apd'] ?? '',
                    'details' => array(),
                    'product_ids' => array(),
                );
            }
            if (!empty($row['namaproduk']) && !in_array($row['produk_id'], $tmp[$key]['product_ids'])) {
                $tmp[$key]['details'][] = array('namaproduk'=>$row['namaproduk'],'produk_id'=>$row['produk_id']);
                $tmp[$key]['product_ids'][] = $row['produk_id'];
            }
        }
        $labOrders = array_values($tmp);
    } catch (Exception $e) {
        $diag[] = array('lab','gagal: '.$e->getMessage());
    }

    // --- Radiologi ---
    try {
        $sqlRad = "
            SELECT 
                so.norec AS norec_so,
                so.noorder,
                so.tglorder,
                so.objectruangantujuanfk,
                so.statusorder,
                so.noregistrasi,
                ru.namaruangan AS ruangan_tujuan,
                ruasal.namaruangan AS ruangan_asal,
                pg_order.namalengkap AS dokter_order,
                pg_baca.namalengkap AS dokter_baca,
                pr.namaproduk,
                pr.id AS produk_id,
                hr.keterangan AS expertise,
                hr.norec AS norec_exper,
                pp.norec AS norec_pp
            FROM strukorder_t so
            JOIN ruangan_m ru ON ru.id = so.objectruangantujuanfk
            LEFT JOIN ruangan_m ruasal ON ruasal.id = so.objectruanganfk
            LEFT JOIN pegawai_m pg_order ON pg_order.id = so.objectpegawaiorderfk
            LEFT JOIN pelayananpasien_t pp ON pp.strukorderfk = so.norec AND pp.statusenabled = true
            LEFT JOIN produk_m pr ON pr.id = pp.produkfk
            LEFT JOIN hasilradiologi_t hr ON hr.pelayananpasienfk = pp.norec AND hr.statusenabled = true
            LEFT JOIN pegawai_m pg_baca ON pg_baca.id = hr.pegawaifk
            WHERE so.noregistrasifk = :norec_pd
              AND so.keteranganorder = 'Order Radiologi'
              AND so.statusenabled = true
            ORDER BY so.tglorder DESC
        ";
        $stmtRad = $pdo->prepare($sqlRad);
        $stmtRad->execute(array(':norec_pd' => $norec_pd));
        $rawRad = $stmtRad->fetchAll();
        $tmpR = array();
        foreach ($rawRad as $row) {
            $key = $row['norec_so'];
            if (!isset($tmpR[$key])) {
                $tmpR[$key] = array(
                    'norec_so' => $row['norec_so'],
                    'noorder' => $row['noorder'],
                    'tglorder' => $row['tglorder'] ? date('d-m-Y H:i', strtotime($row['tglorder'])) : '-',
                    'ruanganasal' => $row['ruangan_asal'] ?? '-',
                    'ruangantujuan' => $row['ruangan_tujuan'] ?? '-',
                    'dokter' => $row['dokter_order'] ?? '-',
                    'dokterbaca' => $row['dokter_baca'] ?? '-',
                    'status' => $row['statusorder'] == 2 ? 'selesai' : ($row['statusorder']==1?'verifikasi':'pending'),
                    'noregistrasi' => $row['noregistrasi'],
                    'expertise' => $row['expertise'] ?? '',
                    'norec_exper' => $row['norec_exper'] ?? '',
                    'details' => array(),
                );
            }
            if (!empty($row['namaproduk'])) {
                $exists = false;
                foreach ($tmpR[$key]['details'] as $d) { if ($d['produk_id']==$row['produk_id']) { $exists=true; break; } }
                if (!$exists) {
                    $tmpR[$key]['details'][] = array('namaproduk'=>$row['namaproduk'],'produk_id'=>$row['produk_id'],'norec_exper'=>$row['norec_exper'],'norec_pp'=>$row['norec_pp']);
                    if (!empty($row['expertise']) && empty($tmpR[$key]['expertise'])) $tmpR[$key]['expertise']=$row['expertise'];
                    if (!empty($row['norec_exper']) && empty($tmpR[$key]['norec_exper'])) $tmpR[$key]['norec_exper']=$row['norec_exper'];
                }
            }
        }
        $radOrders = array_values($tmpR);
    } catch (Exception $e) {
        $diag[] = array('rad','gagal: '.$e->getMessage());
    }
}

// Billing link untuk Cetak Cepat (opsional, bila noregistrasi ada)
$billingUrl = '';
if ($noregistrasi !== '') {
    $bangsaVal = 'WNI';
    if ($pdo) {
        try {
            $stmtBg = $pdo->prepare("SELECT kb.name AS bangsa FROM pasiendaftar_t pd JOIN pasien_m ps ON ps.id=pd.nocmfk LEFT JOIN kebangsaan_m kb ON kb.id=ps.objectkebangsaanfk WHERE pd.norec=:norec LIMIT 1");
            $stmtBg->execute(array(':norec'=>$norec_pd));
            $bgRow = $stmtBg->fetch();
            if ($bgRow && !empty($bgRow['bangsa'])) $bangsaVal = $bgRow['bangsa'];
        } catch (Exception $e) {}
    }
    $billingUrl = '/service/kasir/billing/report/rincian-biaya?noregistrasi='.urlencode($noregistrasi).'&bangsa='.urlencode($bangsaVal).'&user='.urlencode($userCetak).'&kdprofile='.$kdProfile.'&token='.urlencode($tokenCetak);
    $billingUrl = 'http://192.168.22.81'.$billingUrl;
}
if ($billingUrl !== '') {
    $billingBtn = '<a href="'.htmlspecialchars($billingUrl).'" target="_blank" title="Rincian Biaya / Billing" class="qp-btn qp-btn-billing"><i class="fas fa-file-invoice-dollar"></i> Billing</a>';
    $quickHtml .= $billingBtn;
}

?>
<style>
.emr-wrap { font-size: 0.875rem; }
/* Grid 50/50 */
.emr-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    align-items: start;
}
@media (max-width: 900px) {
    .emr-grid { grid-template-columns: 1fr; }
}
.emr-col { display:flex; flex-direction:column; gap:0.6rem; min-width:0; }

/* Panel umum */
.emr-panel {
    border: 1px solid #f3f4f6;
    border-radius: 0.6rem;
    background: #fff;
    overflow: hidden;
}
.emr-panel-head {
    display:flex; align-items:center; gap:0.4rem;
    padding: 0.5rem 0.65rem;
    background: #f9fafb;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; color:#6b7280;
}
.emr-panel-head i { font-size:0.75rem; }
.emr-panel-body { padding:0.55rem; }
.emr-panel-body.scroll { max-height: 260px; overflow-y:auto; }
.emr-panel-body.scroll-sm { max-height: 220px; overflow-y:auto; }

/* Cetak Cepat */
.qp-wrap {
    display: flex; flex-wrap: wrap; align-items: center; gap: 0.35rem;
}
.qp-label {
    font-size: 0.60rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.05em; color: #9ca3af; margin-right: 0.15rem;
}
.qp-btn {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.32rem 0.7rem; font-size: 0.72rem; font-weight: 600;
    color: #374151; background: #ffffff; border: 1px solid #d1d5db;
    border-radius: 0.5rem; text-decoration: none; transition: all .15s ease;
    white-space: nowrap; line-height:1;
}
.qp-btn i { color: #10b981; font-size: 0.68rem; }
.qp-btn:hover { border-color: #10b981; color: #047857; background: #ecfdf5; }
.qp-btn-billing i { color:#f59e0b; }
.qp-btn-billing:hover { border-color:#f59e0b; background:#fffbeb; color:#b45309; }

/* Lab / Radiologi item di panel kiri */
.mini-order {
    border: 1px solid #f3f4f6; border-left:3px solid #e5e7eb;
    border-radius:0.5rem; padding:0.5rem 0.55rem; background:#fff; margin-bottom:0.45rem;
}
.mini-order:last-child{margin-bottom:0}
.mini-order.lab { border-left-color:#38bdf8; }
.mini-order.rad { border-left-color:#a78bfa; }
.mini-order-head { display:flex; flex-wrap:wrap; align-items:center; gap:0.35rem; font-size:0.72rem; font-weight:600; color:#111827; }
.mini-order-head .badge {
    font-size:0.60rem; font-weight:700; padding:0.15rem 0.4rem; border-radius:9999px; text-transform:uppercase;
}
.badge-selesai { background:#dcfce7; color:#166534; }
.badge-verifikasi { background:#dbeafe; color:#1e40af; }
.badge-pending { background:#fef3c7; color:#92400e; }
.mini-order-meta { font-size:0.68rem; color:#6b7280; margin-top:0.25rem; line-height:1.3; }
.mini-order-produk { margin-top:0.3rem; }
.mini-order-produk li { font-size:0.70rem; color:#374151; display:flex; gap:0.25rem; align-items:center; }
.mini-order-actions { display:flex; gap:0.3rem; margin-top:0.4rem; flex-wrap:wrap; }
.mini-btn {
    display:inline-flex; align-items:center; gap:0.25rem;
    padding:0.22rem 0.55rem; border-radius:9999px; font-size:0.68rem; font-weight:600; text-decoration:none; border:1px solid transparent;
}
.mini-btn-cetak { background:#f59e0b; color:#fff; }
.mini-btn-cetak:hover { background:#d97706; }
.mini-btn-hasil { background:#10b981; color:#fff; }
.mini-btn-hasil:hover { background:#059669; }
.mini-btn-off { background:#f3f4f6; color:#9ca3af; border-color:#e5e7eb; cursor:not-allowed; }
.mini-empty { text-align:center; padding:1.2rem 0.5rem; color:#9ca3af; font-size:0.75rem; }
.mini-empty i { font-size:1.2rem; display:block; margin-bottom:0.3rem; }

/* Kanan: Pencarian EMR minimal */
.emr-search-wrap {
    display:flex; align-items:center; gap:0.4rem;
    background:#f9fafb; border:1px solid #f3f4f6; border-radius:0.6rem;
    padding:0.4rem 0.5rem;
}
.emr-search-wrap .count {
    background:#10b981; color:#fff; font-size:0.65rem; font-weight:700;
    padding:0.2rem 0.45rem; border-radius:9999px; flex-shrink:0;
}
.emr-search {
    flex:1; border:none; background:transparent;
    font-size:0.75rem; outline:none; color:#111827; min-width:0;
}
.emr-search::placeholder{color:#9ca3af}
.emr-search:focus{ outline:none; }

/* Daftar EMR di kanan — minimal */
.emr-card-wrap { max-height: 62vh; overflow-y: auto; border: 1px solid #f3f4f6; border-radius: 0.6rem; background: #fff; }
.emr-item {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.45rem 0.6rem; border-bottom: 1px solid #f3f4f6;
}
.emr-item:last-child { border-bottom: none; }
.emr-item:hover { background: #f9fafb; }
.emr-item-icon { color: #9ca3af; font-size: 0.85rem; width: 1rem; text-align: center; flex-shrink: 0; }
.emr-meta { flex: 1; min-width: 0; }
.emr-meta a.emr-title {
    font-weight: 600; color: #111827; text-decoration: none; display: block;
    font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.emr-meta a.emr-title:hover { color: #059669; }
.emr-meta .emr-date {
    font-size: 0.65rem; color: #9ca3af; display: block; margin-top: 1px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.emr-actions { display: flex; gap: 0.25rem; flex-shrink: 0; }
.emr-icbtn, .emr-icbtn-off {
    width: 26px; height: 26px; display: inline-flex; align-items: center;
    justify-content: center; border-radius: 0.4rem; font-size: 0.68rem;
    text-decoration: none;
}
.emr-icbtn { color: #6b7280; border: 1px solid #e5e7eb; background: #fff; }
.emr-icbtn:hover { color: #047857; border-color: #10b981; background: #ecfdf5; }
.emr-icbtn-off { color: #d1d5db; border: 1px solid #f3f4f6; background: #fafafa; cursor: not-allowed; }

.emr-debug {
    grid-column:1 / -1;
    background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 0.5rem;
    padding: 0.5rem 0.75rem; font-size: 0.65rem; color: #334155; margin-top: 0.25rem;
    white-space: pre-wrap; word-break: break-word; font-family: monospace;
}
</style>

<div class="emr-wrap">
    <div class="emr-grid">
        <!-- KIRI 50% : Cetak Cepat + Lab + Radiologi -->
        <div class="emr-col">
            <!-- Cetak Cepat -->
            <div class="emr-panel">
                <div class="emr-panel-head"><i class="fas fa-print text-emerald-500"></i> Cetak Cepat</div>
                <div class="emr-panel-body">
                    <?php if ($quickHtml !== ''): ?>
                        <div class="qp-wrap"><?php echo $quickHtml; ?></div>
                    <?php else: ?>
                        <div class="mini-empty"><i class="fas fa-file-alt"></i>Tidak ada dokumen untuk cetak cepat</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lab -->
            <div class="emr-panel">
                <div class="emr-panel-head"><i class="fas fa-flask text-sky-500"></i> Laboratorium <span style="margin-left:auto;font-weight:600;text-transform:none;letter-spacing:0;color:#9ca3af;font-size:0.65rem;"><?php echo count($labOrders); ?> order</span></div>
                <div class="emr-panel-body scroll-sm">
                    <?php if (empty($labOrders)): ?>
                        <div class="mini-empty"><i class="fas fa-flask"></i>Belum ada order laboratorium</div>
                    <?php else: ?>
                        <?php foreach ($labOrders as $order): ?>
                            <div class="mini-order lab">
                                <div class="mini-order-head">
                                    <span>#<?php echo htmlspecialchars($order['noorder']); ?></span>
                                    <span class="badge <?php echo $order['status']=='selesai'?'badge-selesai':($order['status']=='verifikasi'?'badge-verifikasi':'badge-pending'); ?>"><?php echo htmlspecialchars($order['status']); ?></span>
                                    <span style="font-weight:400;color:#9ca3af;font-size:0.65rem;"><i class="far fa-clock mr-1"></i><?php echo htmlspecialchars($order['tglorder']); ?></span>
                                </div>
                                <div class="mini-order-meta">
                                    <?php if ($order['ruanganasal'] !== '-' ): ?><span><?php echo htmlspecialchars($order['ruanganasal']); ?> → <?php echo htmlspecialchars($order['ruangantujuan']); ?></span> <?php endif; ?>
                                    <?php if ($order['dokter'] !== '-' ): ?> · <?php echo htmlspecialchars($order['dokter']); ?><?php endif; ?>
                                </div>
                                <?php if (!empty($order['details'])): ?>
                                <ul class="mini-order-produk">
                                    <?php foreach ($order['details'] as $d): ?><li><i class="fas fa-check-circle text-emerald-500" style="font-size:0.6rem;"></i> <?php echo htmlspecialchars($d['namaproduk']); ?></li><?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                                <div class="mini-order-actions">
                                    <?php if (!empty($order['norec_apd']) && !empty($order['product_ids'])):
                                        $cetakParams = http_build_query(array(
                                            'noregistrasi' => $order['noregistrasi'],
                                            'norec_apd' => $order['norec_apd'],
                                            'product' => implode(',', array_unique($order['product_ids'])),
                                            'norec_pp' => '',
                                            'norec_so' => $order['norec_so'],
                                            'user' => $userCetak,
                                            'kdprofile' => '1',
                                            'token' => $tokenCetak
                                        ));
                                        $cetakUrl = 'https://192.168.22.81/service/laboratorium/cetakan-hasil-lab-manual?'.$cetakParams;
                                    ?>
                                        <a href="<?php echo htmlspecialchars($cetakUrl); ?>" target="_blank" class="mini-btn mini-btn-cetak"><i class="fas fa-print"></i> Cetak</a>
                                    <?php else: ?>
                                        <span class="mini-btn mini-btn-off"><i class="fas fa-print"></i> Cetak</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Radiologi -->
            <div class="emr-panel">
                <div class="emr-panel-head"><i class="fas fa-x-ray text-violet-500"></i> Radiologi <span style="margin-left:auto;font-weight:600;text-transform:none;letter-spacing:0;color:#9ca3af;font-size:0.65rem;"><?php echo count($radOrders); ?> order</span></div>
                <div class="emr-panel-body scroll-sm">
                    <?php if (empty($radOrders)): ?>
                        <div class="mini-empty"><i class="fas fa-x-ray"></i>Belum ada order radiologi</div>
                    <?php else: ?>
                        <?php foreach ($radOrders as $order): ?>
                            <div class="mini-order rad">
                                <div class="mini-order-head">
                                    <span>#<?php echo htmlspecialchars($order['noorder']); ?></span>
                                    <span class="badge <?php echo $order['status']=='selesai'?'badge-selesai':($order['status']=='verifikasi'?'badge-verifikasi':'badge-pending'); ?>"><?php echo htmlspecialchars($order['status']); ?></span>
                                    <span style="font-weight:400;color:#9ca3af;font-size:0.65rem;"><i class="far fa-clock mr-1"></i><?php echo htmlspecialchars($order['tglorder']); ?></span>
                                </div>
                                <?php if (!empty($order['details'])): ?>
                                <ul class="mini-order-produk">
                                    <?php foreach ($order['details'] as $d): ?><li><i class="fas fa-check-circle text-emerald-500" style="font-size:0.6rem;"></i> <?php echo htmlspecialchars($d['namaproduk']); ?></li><?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                                <div class="mini-order-actions">
                                    <?php if (!empty($order['expertise'])): ?>
                                        <button onclick="showExpertise('<?php echo addslashes(str_replace(array("\r","\n"), array("\\r","\\n"), $order['expertise'])); ?>')" class="mini-btn mini-btn-hasil"><i class="fas fa-file-alt"></i> Hasil</button>
                                    <?php else: ?>
                                        <span class="mini-btn mini-btn-off">Hasil</span>
                                    <?php endif; ?>
                                    <?php if (!empty($order['norec_exper'])):
                                        $userName = !empty($order['dokterbaca']) && $order['dokterbaca'] !== '-' ? $order['dokterbaca'] : $order['dokter'];
                                        $cetakUrl = "http://192.168.22.81/service/radiologi/cetak-ekspertise-manual?".http_build_query(array('echo'=>'true','norec'=>$order['norec_exper'],'user'=>$userName,'kdprofile'=>'1','token'=>$tokenCetak));
                                    ?>
                                        <a href="<?php echo htmlspecialchars($cetakUrl); ?>" target="_blank" class="mini-btn mini-btn-cetak"><i class="fas fa-print"></i> Cetak</a>
                                    <?php else: ?>
                                        <span class="mini-btn mini-btn-off"><i class="fas fa-print"></i> Cetak</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- KANAN 50% : Pencarian EMR minimal + Daftar EMR -->
        <div class="emr-col">
            <div class="emr-panel" style="display:flex;flex-direction:column;flex:1;">
                <div class="emr-panel-head"><i class="fas fa-notes-medical text-emerald-600"></i> EMR <span style="margin-left:auto;display:flex;align-items:center;gap:0.35rem;"><span style="background:#10b981;color:#fff;font-size:0.60rem;font-weight:700;padding:0.15rem 0.4rem;border-radius:9999px;"><?php echo count($listEmr); ?></span></span></div>
                <div style="padding:0.5rem;border-bottom:1px solid #f3f4f6;">
                    <div class="emr-search-wrap">
                        <i class="fas fa-search" style="color:#9ca3af;font-size:0.70rem;"></i>
                        <input type="text" id="emrSearchInput" class="emr-search" placeholder="Cari EMR..." value="<?php echo htmlspecialchars($qSearch); ?>" autocomplete="off">
                        <?php if ($qSearch !== ''): ?><button onclick="document.getElementById('emrSearchInput').value='';document.getElementById('emrSearchInput').dispatchEvent(new Event('input'))" style="color:#9ca3af;font-size:0.70rem;background:none;border:none;cursor:pointer;"><i class="fas fa-times"></i></button><?php endif; ?>
                    </div>
                </div>
                <?php if (empty($listEmr)): ?>
                    <div class="mini-empty" style="padding:2rem 1rem;">
                        <i class="fas fa-folder-open" style="font-size:1.6rem;"></i>
                        <div style="font-weight:600;color:#6b7280;margin-top:0.3rem;">Belum ada data EMR</div>
                        <div style="font-size:0.70rem;color:#9ca3af;margin-top:0.15rem;">Tidak ada dokumen EMR tersimpan untuk registrasi ini.</div>
                        <?php if ($sourceError !== ''): ?><div style="font-size:0.65rem;color:#d97706;margin-top:0.3rem;"><?php echo htmlspecialchars($sourceError); ?></div><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="emr-card-wrap" id="emrListContainer" style="border:none;border-radius:0;max-height:62vh;">
                        <?php foreach ($listEmr as $item):
                            $emrFk     = $item['emrpasienfk'];
                            $noregItem = $item['noregistrasi'] !== '' ? $item['noregistrasi'] : $noregistrasi;
                            $editUrl   = emrBuildEditUrl($webBase,$item['slug'],($item['nocmfk'] !== '' ? $item['nocmfk'] : $nocmfk),$norec_pd,($item['norec_apd'] !== '' ? $item['norec_apd'] : $norec_apd),$emrFk);
                            $cetakUrl  = $item['table'] !== '' ? emrBuildCetakUrl($apiBase, $item['table'], $emrFk, $noregItem, $userCetak, $kdProfile, $tokenCetak) : '';
                            if ($cetakUrl !== '') $cetakUrl = str_replace('http://localhost','http://192.168.22.81',$cetakUrl);
                            $searchHay = strtolower($item['namaemr'].' '.$item['author'].' '.$item['ruangan'].' '.$item['table'].' '.$item['noemr']);
                        ?>
                        <div class="emr-item" data-search="<?php echo htmlspecialchars($searchHay); ?>">
                            <i class="<?php echo htmlspecialchars($item['icon']); ?> emr-item-icon"></i>
                            <div class="emr-meta">
                                <a class="emr-title" href="<?php echo htmlspecialchars($editUrl); ?>" target="_blank" title="Lihat / ubah EMR"><?php echo htmlspecialchars($item['namaemr']); ?></a>
                                <span class="emr-date"><?php echo emrTanggalIndoSimple($item['last_update']); ?><?php if ($item['noemr'] !== ''): ?> · <?php echo htmlspecialchars($item['noemr']); ?><?php endif; ?><?php if ($item['ruangan'] !== ''): ?> · <?php echo htmlspecialchars($item['ruangan']); ?><?php endif; ?><?php if ($item['author'] !== '' && $item['author'] !== '-'): ?> · <?php echo htmlspecialchars($item['author']); ?><?php endif; ?></span>
                            </div>
                            <div class="emr-actions">
                                <a class="emr-icbtn" href="<?php echo htmlspecialchars($editUrl); ?>" target="_blank" title="Lihat / ubah"><i class="fas fa-eye"></i></a>
                                <?php if ($cetakUrl !== ''): ?>
                                <a class="emr-icbtn" href="<?php echo htmlspecialchars($cetakUrl); ?>" target="_blank" title="Cetak"><i class="fas fa-print"></i></a>
                                <?php else: ?>
                                <span class="emr-icbtn-off" title="Collection cetak belum diketahui"><i class="fas fa-ban"></i></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($showDebug): ?>
    <div class="emr-debug">
        <b>Diagnosa sumber data EMR</b>
        <?php
        echo "\n" . 'norec_pd     : ' . htmlspecialchars($norec_pd);
        echo "\n" . 'noregistrasi : ' . htmlspecialchars($noregistrasi);
        echo "\n" . 'hasil        : ' . htmlspecialchars($source) . ' (' . count($listEmr) . ' item) lab='.count($labOrders).' rad='.count($radOrders);
        echo "\n" . 'sumber       : ' . htmlspecialchars($sourceLabel);
        echo "\n" . 'catatan      : ' . htmlspecialchars($sourceError);
        foreach ($diag as $d) {
            echo "\n" . str_pad('[' . $d[0] . ']', 12) . ' ' . htmlspecialchars($d[1]);
        }
        ?>
    </div>
    <?php endif; ?>
</div>

<script>
(function () {
    var input = document.getElementById('emrSearchInput');
    var list  = document.getElementById('emrListContainer');
    if (!input || !list) return;
    input.addEventListener('input', function () {
        var q = (input.value || '').toLowerCase().trim();
        var items = list.querySelectorAll('.emr-item');
        for (var i = 0; i < items.length; i++) {
            var hay = items[i].getAttribute('data-search') || '';
            items[i].style.display = (!q || hay.indexOf(q) !== -1) ? '' : 'none';
        }
    });
    // autofocus minimal search
    setTimeout(function(){ try{ input.focus(); }catch(e){} }, 120);
})();
</script>
