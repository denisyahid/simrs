<?php
/**
 * ============================================================================
 *  ajax_update_tgl_spri.php  —  endpoint "Edit Tgl SPRI" (dipakai tm/index.php)
 * ============================================================================
 *  Tanggal SPRI adalah field "tanggal" pada dokumen MongoDB collection
 *  SuratPermintaanDirawat (yang dicetak sebagai "Garut, <tanggal>" di surat).
 *  Field tambahan yang bisa diedit: tanggalTindakan, tanggalRawatInap.
 *
 *  Parameter GET/POST :
 *    norec_pd      (WAJIB — fallback otomatis dari noregistrasi bila kosong)
 *    noregistrasi  (opsional, dipakai utk lookup norec_pd)
 *    emr_surat_fk  (opsional, norec emrpasien_t milik SPRI)
 *    id            (opsional, id dokumen MongoDB SuratPermintaanDirawat)
 *    action        = get  -> ambil data tanggal SPRI saat ini (JSON)
 *                  = save -> simpan perubahan tanggal
 *    tgl_spri      (utk action=save, format  YYYY-MM-DDTHH:mm  /  YYYY-MM-DD)
 *    tgl_tindakan  (opsional, format YYYY-MM-DD)
 *    tgl_rawat_inap(opsonal, format YYYY-MM-DD)
 *
 *  CATATAN PERBAIKAN:
 *    Versi fitur sebelumnya memanggil endpoint tanpa parameter norec_pd
 *    sehingga muncul error "Parameter norec_pd diperlukan.".
 *    File ini WAJIB menerima norec_pd, dan bila norec_pd kosong tetapi
 *    noregistrasi tersedia, nilainya dicari dari tabel pasiendaftar_t
 *    (sama seperti perilaku ajax_emr_detail.php).
 * ============================================================================
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');

// ============================================================
// KONFIGURASI (sama dengan tm/index.php & tm/ajax_emr_detail.php)
// ============================================================
$host     = "192.168.22.81";
$port     = "5792";
$dbname   = "rsud_malangbong";
$user     = "postgres";
$password = "Tr4nsm3d!c MaRe#T3aM";
$kdProfile = 1;

$mongoUriManual = '';   // isi bila ingin memaksa URI mongodb tertentu
$mongoDbManual  = '';   // kosongkan = dibaca dari backend/.env (DB_DATABASE_MONGO)

// ============================================================
// UTILITAS
// ============================================================
function spriJsonOut($arr, $code = 200)
{
    http_response_code($code);
    echo json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function spriReq($key, $default = '')
{
    if (isset($_POST[$key]) && $_POST[$key] !== '') {
        return trim((string)$_POST[$key]);
    }
    if (isset($_GET[$key]) && $_GET[$key] !== '') {
        return trim((string)$_GET[$key]);
    }
    return $default;
}

/**
 * Baca file .env backend Laravel (sama pola dengan ajax_emr_detail.php).
 */
function spriReadBackendEnv()
{
    $candidates = array(
        dirname(__DIR__) . '/backend/.env',
        dirname(__DIR__, 2) . '/backend/.env',
        __DIR__ . '/../backend/.env',
        __DIR__ . '/.env',
        dirname(__DIR__) . '/.env',
        '/var/www/html/simrs/backend/.env',
        '/var/www/simrs/backend/.env',
        'C:/xampp/htdocs/simrs/backend/.env',
        'D:/xampp/htdocs/simrs/backend/.env',
    );
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
        $candidates[] = $docRoot . '/backend/.env';
        $candidates[] = $docRoot . '/../backend/.env';
        $candidates[] = $docRoot . '/../.env';
    }
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
            return $data;
        }
    }
    return array();
}

/** Kandidat URI MongoDB (dari .env lalu fallback lokal / host database) */
function spriMongoUriList($env, $manual, $pgHost)
{
    $uriList = array();
    if ($manual !== '') {
        $uriList[] = $manual;
        return $uriList;
    }
    if (!empty($env['DATABASE_URL_MONGO'])) {
        $uriList[] = $env['DATABASE_URL_MONGO'];
    }
    $mHost = !empty($env['DB_HOST_MONGO']) ? $env['DB_HOST_MONGO'] : '';
    if ($mHost !== '') {
        $mPort = !empty($env['DB_PORT_MONGO']) ? $env['DB_PORT_MONGO'] : '27017';
        $mUser = !empty($env['DB_USERNAME_MONGO']) ? $env['DB_USERNAME_MONGO'] : '';
        $mPass = !empty($env['DB_PASSWORD_MONGO']) ? $env['DB_PASSWORD_MONGO'] : '';
        $auth  = ($mUser !== '') ? ($mUser . (($mPass !== '') ? (':' . $mPass) : '') . '@') : '';
        $uriList[] = 'mongodb://' . $auth . $mHost . ':' . $mPort . '/?authSource=admin';
    }
    $uriList[] = 'mongodb://127.0.0.1:27017/?authSource=admin';
    $uriList[] = 'mongodb://localhost:27017/?authSource=admin';
    if (!empty($pgHost)) {
        $uriList[] = 'mongodb://' . $pgHost . ':27017/?authSource=admin';
    }
    return array_values(array_unique($uriList));
}

/**
 * Cari dokumen SPRI di MongoDB collection SuratPermintaanDirawat.
 * Mengembalikan array ['manager'=>..., 'doc'=>..., 'raw'=>...] atau null.
 */
function spriFindDoc($uriList, $mongoDb, $filters)
{
    if (!class_exists('MongoDB\Driver\Manager')) {
        return array('error' => 'ekstensi PHP mongodb tidak aktif');
    }
    if (empty($uriList)) {
        return array('error' => 'konfigurasi koneksi MongoDB tidak ditemukan');
    }
    $ns = $mongoDb . '.SuratPermintaanDirawat';
    $lastError = '';
    foreach ($uriList as $uri) {
        try {
            $manager = new MongoDB\Driver\Manager($uri, array(), array(
                'serverSelectionTimeoutMS' => 4000,
                'connectTimeoutMS'         => 4000,
            ));
            foreach ($filters as $filter) {
                try {
                    $query  = new MongoDB\Driver\Query($filter, array('sort' => array('created_at' => -1), 'limit' => 1));
                    $cursor = $manager->executeQuery($ns, $query);
                    foreach ($cursor as $doc) {
                        return array('manager' => $manager, 'doc' => $doc, 'uri' => $uri, 'ns' => $ns);
                    }
                } catch (Exception $e) {
                    $lastError = $e->getMessage();
                } catch (Error $e) {
                    $lastError = $e->getMessage();
                }
            }
            // terhubung tapi tidak ada dokumen -> stop (tidak perlu coba URI lain)
            return array('error' => 'dokumen SPRI tidak ditemukan', 'notfound' => true);
        } catch (Exception $e) {
            $lastError = $e->getMessage();
        } catch (Error $e) {
            $lastError = $e->getMessage();
        }
    }
    return array('error' => $lastError !== '' ? $lastError : 'MongoDB tidak terjangkau');
}

/** Konversi nilai tanggal MongoDB/PHP -> format input datetime-local (WIB) */
function spriToInputValue($value, $dateOnly = false)
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        if ($value instanceof MongoDB\BSON\UTCDateTime) {
            $dt = $value->toDateTime();
            $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
            return $dt->format($dateOnly ? 'Y-m-d' : 'Y-m-d\TH:i');
        }
        if (is_object($value) && method_exists($value, 'toDateTime')) {
            $dt = $value->toDateTime();
            $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
            return $dt->format($dateOnly ? 'Y-m-d' : 'Y-m-d\TH:i');
        }
        if (is_numeric($value)) {
            $dt = new DateTime('@' . (int)$value);
            $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
            return $dt->format($dateOnly ? 'Y-m-d' : 'Y-m-d\TH:i');
        }
        $str = (string)$value;
        // String tanpa info timezone (tanpa Z / offset) dianggap WIB —
        // sesuai timezone aplikasi (Asia/Jakarta) yang menulis nilai tsb.
        $hasTz = (bool)preg_match('/(Z|[+-]\d{2}:?\d{2})\s*$/i', $str);
        if ($hasTz) {
            $dt = new DateTime($str);
        } else {
            $dt = new DateTime($str, new DateTimeZone('Asia/Jakarta'));
        }
        $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $dt->format($dateOnly ? 'Y-m-d' : 'Y-m-d\TH:i');
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Konversi input user (dianggap WIB) -> nilai yang disimpan, mengikuti
 * bentuk data yang ditulis frontend Vue (JS Date -> ISO-8601 UTC "Z").
 * Bila nilai lama bertipe BSON date, ikut tulis BSON date.
 */
function spriToStoreValue($input, $dateOnly, $oldValueIsDate)
{
    try {
        if ($dateOnly) {
            $dt = new DateTime($input, new DateTimeZone('Asia/Jakarta'));
            $dt->setTime(0, 0, 0);
        } else {
            // datetime-local: YYYY-MM-DDTHH:mm
            $dt = new DateTime(str_replace(' ', 'T', $input), new DateTimeZone('Asia/Jakarta'));
        }
    } catch (Exception $e) {
        return null; // format tidak valid
    }
    if ($oldValueIsDate) {
        return new MongoDB\BSON\UTCDateTime(((int)$dt->format('U')) * 1000);
    }
    $utc = clone $dt;
    $utc->setTimezone(new DateTimeZone('UTC'));
    return $utc->format('Y-m-d\TH:i:s') . '.000Z';
}

// ============================================================
// PARAMETER — norec_pd WAJIB (fallback: noregistrasi)
// ============================================================
$action       = spriReq('action', 'get');
$norec_pd     = spriReq('norec_pd');
$noregistrasi = spriReq('noregistrasi');
$emr_surat_fk = spriReq('emr_surat_fk');
$docId        = spriReq('id');

if ($action !== 'get' && $action !== 'save') {
    spriJsonOut(array('ok' => false, 'error' => 'action tidak dikenal'), 400);
}

// --- Koneksi Postgres (untuk lookup norec_pd dari noregistrasi) ---
$pdo = null;
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ));
} catch (PDOException $e) {
    // dilanjutkan; hanya dibutuhkan saat fallback lookup
    $pdo = null;
}

// Bila norec_pd belum ada, cari dari noregistrasi (PERBAIKAN utk error
// "Parameter norec_pd diperlukan." pada versi fitur sebelumnya)
if ($norec_pd === '' && $noregistrasi !== '' && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT norec FROM pasiendaftar_t WHERE noregistrasi = :noreg AND statusenabled = true LIMIT 1");
        $stmt->execute(array(':noreg' => $noregistrasi));
        $found = $stmt->fetch();
        if ($found) {
            $norec_pd = (string)$found['norec'];
        }
    } catch (Exception $e) {
        // biarkan kosong -> pesan error di bawah
    }
}

if ($norec_pd === '') {
    spriJsonOut(array(
        'ok'    => false,
        'error' => 'Parameter norec_pd diperlukan.',
        'hint'  => 'Kirim norec_pd (atau noregistrasi) pada request.',
    ), 400);
}

// ============================================================
// KONEKSI MONGODB + FILTER PENCARIAN DOKUMEN SPRI
// ============================================================
$env      = spriReadBackendEnv();
$mongoDb  = $mongoDbManual !== '' ? $mongoDbManual : (!empty($env['DB_DATABASE_MONGO']) ? $env['DB_DATABASE_MONGO'] : 'transmedic_v3');
$uriList  = spriMongoUriList($env, $mongoUriManual, $host);

$filters = array();
if ($docId !== '') {
    $filters[] = array('id' => $docId);
}
if ($emr_surat_fk !== '') {
    $filters[] = array('emrpasienfk' => $emr_surat_fk, 'profile.kdprofile' => (int)$kdProfile, 'statusenabled' => true);
    $filters[] = array('emrpasienfk' => $emr_surat_fk);
}
$filters[] = array('registrasi.norec_pd' => $norec_pd, 'profile.kdprofile' => (int)$kdProfile, 'statusenabled' => true, 'namatemplate' => null);
$filters[] = array('registrasi.norec_pd' => $norec_pd);

$found     = spriFindDoc($uriList, $mongoDb, $filters);
$doc       = isset($found['doc']) ? $found['doc'] : null;

if ($doc === null) {
    $isNotFound = !empty($found['notfound']);
    spriJsonOut(array(
        'ok'    => false,
        'error' => $isNotFound
            ? 'Dokumen SPRI tidak ditemukan untuk registrasi ini.'
            : ('Gagal menghubungi MongoDB: ' . (isset($found['error']) ? $found['error'] : 'tidak diketahui')),
        'notfound' => $isNotFound,
    ), $isNotFound ? 404 : 500);
}

// Ambil nilai mentah (untuk tahu tipe penyimpanan saat update)
$raw = array();
foreach ($doc as $k => $v) {
    $raw[$k] = $v;
}

$tanggalRaw   = isset($raw['tanggal']) ? $raw['tanggal'] : null;
$tindakanRaw  = isset($raw['tanggalTindakan']) ? $raw['tanggalTindakan'] : null;
$rawatRaw     = isset($raw['tanggalRawatInap']) ? $raw['tanggalRawatInap'] : null;

$docIdVal = isset($raw['id']) ? (string)$raw['id'] : '';

// ============================================================
// ACTION: GET — kirim data tanggal SPRI saat ini
// ============================================================
if ($action === 'get') {
    spriJsonOut(array(
        'ok'   => true,
        'data' => array(
            'id'               => $docIdVal,
            'emrpasienfk'      => isset($raw['emrpasienfk']) ? (string)$raw['emrpasienfk'] : '',
            'nosurat'          => isset($raw['nosurat']) ? (string)$raw['nosurat'] : '',
            'noemr'            => isset($raw['noemr']) ? (string)$raw['noemr'] : '',
            'tgl_spri'         => spriToInputValue($tanggalRaw, false),
            'tgl_tindakan'     => spriToInputValue($tindakanRaw, true),
            'tgl_rawat_inap'   => spriToInputValue($rawatRaw, true),
            'tgl_spri_raw'     => spriToInputValue($tanggalRaw, false) !== '' ? spriToInputValue($tanggalRaw, false) : '-',
        ),
    ));
}

// ============================================================
// ACTION: SAVE — perbarui tanggal SPRI
// ============================================================
$tglSpri      = spriReq('tgl_spri');
$tglTindakan  = spriReq('tgl_tindakan');
$tglRawatInap = spriReq('tgl_rawat_inap');

$set = array();
$now = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s');
$set['updated_at'] = $now;

if ($tglSpri !== '') {
    $val = spriToStoreValue($tglSpri, false, $tanggalRaw instanceof MongoDB\BSON\UTCDateTime);
    if ($val === null) {
        spriJsonOut(array('ok' => false, 'error' => 'Format tgl_spri tidak valid (pakai YYYY-MM-DD atau YYYY-MM-DDTHH:mm)'), 400);
    }
    $set['tanggal'] = $val;
}
if ($tglTindakan !== '') {
    $val = spriToStoreValue($tglTindakan, true, $tindakanRaw instanceof MongoDB\BSON\UTCDateTime);
    if ($val === null) {
        spriJsonOut(array('ok' => false, 'error' => 'Format tgl_tindakan tidak valid (pakai YYYY-MM-DD)'), 400);
    }
    $set['tanggalTindakan'] = $val;
}
if ($tglRawatInap !== '') {
    $val = spriToStoreValue($tglRawatInap, true, $rawatRaw instanceof MongoDB\BSON\UTCDateTime);
    if ($val === null) {
        spriJsonOut(array('ok' => false, 'error' => 'Format tgl_rawat_inap tidak valid (pakai YYYY-MM-DD)'), 400);
    }
    $set['tanggalRawatInap'] = $val;
}

if (count($set) === 1) { // hanya updated_at -> tidak ada tanggal yg dikirim
    spriJsonOut(array('ok' => false, 'error' => 'Tidak ada tanggal yang dikirim untuk disimpan.'), 400);
}

try {
    $manager = $found['manager'];
    $ns      = $found['ns'];
    $updFilter = ($docIdVal !== '')
        ? array('id' => $docIdVal)
        : ((!empty($filters[0]) ? $filters[0] : array('id' => null)));

    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->update($updFilter, array('$set' => $set), array('multi' => false, 'upsert' => false));
    $result = $manager->executeBulkWrite($ns, $bulk);

    if ((int)$result->getModifiedCount() < 1 && (int)$result->getMatchedCount() < 1) {
        spriJsonOut(array('ok' => false, 'error' => 'Update gagal: dokumen SPRI tidak cocok / tidak ditemukan.'), 500);
    }

    // ambil ulang nilai terbaru utk konfirmasi
    $after = spriFindDoc($uriList, $mongoDb, array(array('id' => $docIdVal !== '' ? $docIdVal : null)));
    $newDoc = isset($after['doc']) ? $after['doc'] : $doc;

    spriJsonOut(array(
        'ok'      => true,
        'message' => 'Tanggal SPRI berhasil diperbarui.',
        'data'    => array(
            'id'             => $docIdVal,
            'tgl_spri'       => spriToInputValue(isset($newDoc->tanggal) ? $newDoc->tanggal : $tanggalRaw, false),
            'tgl_tindakan'   => spriToInputValue(isset($newDoc->tanggalTindakan) ? $newDoc->tanggalTindakan : $tindakanRaw, true),
            'tgl_rawat_inap' => spriToInputValue(isset($newDoc->tanggalRawatInap) ? $newDoc->tanggalRawatInap : $rawatRaw, true),
        ),
    ));
} catch (Exception $e) {
    spriJsonOut(array('ok' => false, 'error' => 'Gagal menyimpan: ' . $e->getMessage()), 500);
} catch (Error $e) {
    spriJsonOut(array('ok' => false, 'error' => 'Gagal menyimpan: ' . $e->getMessage()), 500);
}
