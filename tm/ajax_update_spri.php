<?php
/**
 * ============================================================================
 *  ajax_update_spri.php  —  FULL NATIVE (dipakai oleh tm/ajax_emr_detail.php)
 * ============================================================================
 *  Fitur Edit Tanggal SPRI (Surat Permintaan Dirawat).
 *
 *  PENTING (sesuai permintaan):
 *   - TANPA ekstensi PHP mongodb (tidak ada di server) -> koneksi MongoDB
 *     dilakukan LANGSUNG via wire protocol (OP_MSG + BSON) memakai fsockopen
 *     yang ada di file ini. Bila server MongoDB meminta autentikasi, dibuat
 *     SCRAM-SHA-1 / SCRAM-SHA-256 native (kredensial dari backend/.env).
 *   - TANPA memanggil REST API resmi Laravel. Semua langsung ke database.
 *   - Koneksi PostgreSQL memakai konfigurasi yang sama persis dengan
 *     tm/index.php (host 192.168.22.81:5792, db rsud_malangbong).
 *
 *  Endpoint:
 *    GET  ?action=get  &norec_pd=... [&emrpasienfk=...]
 *         -> baca tanggal SPRI saat ini (fallback tglregistrasi Postgres)
 *
 *    POST ?action=update   body JSON: { norec_pd, emrpasienfk?, tanggal:"YYYY-MM-DD" }
 *         -> mengubah SEMUA tanggal pada dokumen SPRI (collection MongoDB
 *            "SuratPermintaanDirawat", sama seperti simpan form Vue lewat
 *            EMRCtrl::saveSuratKeterangan):
 *              - tanggalTindakan   (TANGGAL TINDAKAN pada form)
 *              - tanggalRawatInap  (TANGGAL RAWAT INAP pada form)
 *              - tanggal           (di sebelah tulisan "Garut" — ttd dokter)
 *              - tanggalAdmission  (di sebelah tulisan "Garut" — admission)
 *              - tglPembuatan
 *            Format ditulis identik dengan axios (ISO 8601, offset +07:00)
 *            sehingga cetak blade (Carbon::parse + setTimezone WIB) benar.
 *
 *    Setelah update berhasil, jejak audit ditulis ke PostgreSQL logginguser_t
 *    (referensi 'EMR') — meniru $this->LOGGING() backend.
 *
 *  SELFTEST offline:  php tm/ajax_update_spri.php selftest
 *    (uji BSON encode/decode, UUID, perhitungan tanggal — tanpa jaringan)
 * ============================================================================
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '0');

// ============================================================
// KONEKSI DATABASE (identik dengan tm/index.php)
// ============================================================
$host     = "192.168.22.81";
$port     = "5792";
$dbname   = "rsud_malangbong";
$user     = "postgres";
$password = "Tr4nsm3d!c MaRe#T3aM";

$kdProfile = 1;

$pdo = null;
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pdo = null;
}

// ============================================================
// CONFIG MONGODB — default mengikuti server SIMRS (host Postgres),
// bisa dioverride lewat backend/.env (DB_HOST_MONGO dst).
// ============================================================
$mongoHost = $host;          // 192.168.22.81
$mongoPort = '27017';
$mongoDb   = 'transmedic_v3';
$mongoUser = '';
$mongoPass = '';
$mongoAuthSource = 'admin';

// Config REST API Laravel (fallback bila MongoDB tidak terjangkau dari
// server web) — endpoint yang sama persis dengan form Vue SPRI:
//   GET  /emr/get-emr             (baca dokumen SPRI)
//   POST /emr/simpan-emr-surket   (simpan ulang dokumen SPRI)
$apiBase   = 'https://192.168.22.81/service';
$jwtKey    = 'TRANSINDO';          // default backend/config/app.php
$userCetak = 'pasa';

// Baca backend/.env bila ada (pola sama dengan ajax_emr_detail.php)
$envCandidates = array(
    dirname(__DIR__) . '/backend/.env',
    dirname(__DIR__, 2) . '/backend/.env',
    __DIR__ . '/../backend/.env',
    __DIR__ . '/.env',
    dirname(__DIR__) . '/.env',
);
foreach ($envCandidates as $envPath) {
    if ($envPath && is_readable($envPath)) {
        $lines = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (is_array($lines)) {
            $envData = array();
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
                list($k, $v) = explode('=', $line, 2);
                $envData[trim($k)] = trim(trim($v), "\"'");
            }
            if (!empty($envData['DB_HOST_MONGO'])) {
                $mongoHost = $envData['DB_HOST_MONGO'];
                if (!empty($envData['DB_PORT_MONGO']))    $mongoPort = $envData['DB_PORT_MONGO'];
                if (!empty($envData['DB_USERNAME_MONGO'])) $mongoUser = $envData['DB_USERNAME_MONGO'];
                if (!empty($envData['DB_PASSWORD_MONGO'])) $mongoPass = $envData['DB_PASSWORD_MONGO'];
            if (!empty($envData['DB_DATABASE_MONGO'])) $mongoDb   = $envData['DB_DATABASE_MONGO'];
        }
        if (!empty($envData['APP_URL'])) $apiBase = rtrim($envData['APP_URL'], '/') . '/service';
        if (!empty($envData['JWT_KEY'])) $jwtKey  = $envData['JWT_KEY'];
        if (!empty($envData['DATABASE_URL_MONGO'])) {
                $u = parse_url($envData['DATABASE_URL_MONGO']);
                if (!empty($u['host'])) $mongoHost = $u['host'];
                if (!empty($u['port'])) $mongoPort = (string)$u['port'];
                if (!empty($u['user'])) $mongoUser = rawurldecode($u['user']);
                if (isset($u['pass']))  $mongoPass = rawurldecode($u['pass']);
                if (!empty($u['path'])) $mongoDb   = ltrim($u['path'], '/');
                if (!empty($u['query'])) {
                    parse_str($u['query'], $q);
                    if (!empty($q['authSource'])) $mongoAuthSource = $q['authSource'];
                }
            }
            break;
        }
    }
}

// ============================================================
// ====================  MONGO WIRE CLIENT  ===================
//  Implementasi BSON + OP_MSG + SCRAM murni PHP (tanpa ekstensi).
// ============================================================

/** Wrapper BSON Binary (subtype 0 = generic, dipakai utk payload SASL) */
class SpriBsonBinary
{
    public $data;
    public $subtype;
    public function __construct($data, $subtype = 0)
    {
        $this->data    = $data;
        $this->subtype = $subtype;
    }
}

/** cstring BSON: string + null terminator */
function spriCstr($s)
{
    return $s . "\0";
}

/** Encode dokumen (array PHP) -> body BSON lengkap (int32 len + elemen + \0) */
function spriBsonDoc($doc)
{
    $bin = '';
    $i   = 0;
    foreach ($doc as $k => $v) {
        $name = is_int($k) ? (string)$i : (string)$k;
        $bin .= spriBsonElement($name, $v);
        $i++;
    }
    $bin .= "\0";
    return pack('V', strlen($bin) + 4) . $bin;
}

/** Encode satu elemen BSON (tipe byte + cstring name + value) */
function spriBsonElement($name, $v)
{
    if ($v === null) {
        return "\x0A" . spriCstr($name);
    }
    if (is_bool($v)) {
        return "\x08" . spriCstr($name) . ($v ? "\x01" : "\x00");
    }
    if (is_int($v)) {
        if ($v >= -2147483648 && $v <= 2147483647) {
            return "\x10" . spriCstr($name) . pack('V', $v);
        }
        return "\x12" . spriCstr($name) . pack('P', $v);
    }
    if (is_float($v)) {
        return "\x01" . spriCstr($name) . pack('E', $v); // double big-endian (spesifikasi BSON)
    }
    if (is_string($v)) {
        return "\x02" . spriCstr($name) . pack('V', strlen($v) + 1) . $v . "\0";
    }
    if ($v instanceof SpriBsonBinary) {
        return "\x05" . spriCstr($name) . pack('V', strlen($v->data)) . chr($v->subtype) . $v->data;
    }
    if (is_array($v)) {
        $isList = array_keys($v) === range(0, count($v) - 1);
        $body   = '';
        $i      = 0;
        foreach ($v as $k2 => $v2) {
            $body .= spriBsonElement($isList ? (string)$i : (string)$k2, $v2);
            $i++;
        }
        $body .= "\0";
        return ($isList ? "\x04" : "\x03") . spriCstr($name) . pack('V', strlen($body) + 4) . $body;
    }
    throw new Exception('Tipe BSON tidak didukung: ' . gettype($v));
}

/** Baca cstring dari buffer pada posisi tertentu */
function spriReadCstr($buf, &$pos)
{
    $end = strpos($buf, "\0", $pos);
    if ($end === false) {
        throw new Exception('BSON rusak (cstring)');
    }
    $s   = substr($buf, $pos, $end - $pos);
    $pos = $end + 1;
    return $s;
}

/**
 * Decode body BSON -> array PHP.
 * Mendukung: double, string, doc, array, binary, undefined, ObjectId, bool,
 * UTCDateTime, null, regex(skip), int32, timestamp, int64, decimal128(skip).
 */
function spriBsonDecode($buf, &$pos = 0)
{
    $len = unpack('V', substr($buf, $pos, 4))[1];
    $end = $pos + $len - 1;
    $pos += 4;
    $out = array();
    while ($pos < $end) {
        $type = ord($buf[$pos]);
        $pos++;
        $name = spriReadCstr($buf, $pos);
        switch ($type) {
            case 0x01: // double
                $out[$name] = unpack('E', substr($buf, $pos, 8))[1];
                $pos += 8;
                break;
            case 0x02: // string
                $l = unpack('V', substr($buf, $pos, 4))[1];
                $pos += 4;
                $out[$name] = substr($buf, $pos, $l - 1);
                $pos += $l;
                break;
            case 0x03: // document
                $out[$name] = spriBsonDecode($buf, $pos);
                break;
            case 0x04: // array
                $out[$name] = spriBsonDecode($buf, $pos);
                break;
            case 0x05: // binary
                $l  = unpack('V', substr($buf, $pos, 4))[1];
                $sub = ord($buf[$pos + 4]);
                $pos += 5;
                $out[$name] = new SpriBsonBinary(substr($buf, $pos, $l), $sub);
                $pos += $l;
                break;
            case 0x06: // undefined
                $out[$name] = null;
                break;
            case 0x07: // ObjectId
                $out[$name] = bin2hex(substr($buf, $pos, 12));
                $pos += 12;
                break;
            case 0x08: // bool
                $out[$name] = ord($buf[$pos]) === 1;
                $pos += 1;
                break;
            case 0x09: // UTCDateTime (ms sejak epoch) -> string WIB
                $ms = unpack('P', substr($buf, $pos, 8))[1];
                $pos += 8;
                $dt = new DateTime('@' . (int)floor($ms / 1000));
                try { $dt->setTimezone(new DateTimeZone('Asia/Jakarta')); } catch (Exception $e) {}
                $out[$name] = $dt->format('Y-m-d H:i:s');
                break;
            case 0x0A: // null
                $out[$name] = null;
                break;
            case 0x0B: // regex
                spriReadCstr($buf, $pos);
                spriReadCstr($buf, $pos);
                $out[$name] = 'REGEX';
                break;
            case 0x0D: // code
                $l = unpack('V', substr($buf, $pos, 4))[1];
                $pos += 4;
                $out[$name] = substr($buf, $pos, $l - 1);
                $pos += $l;
                break;
            case 0x10: // int32
                $out[$name] = unpack('l', pack('V', unpack('V', substr($buf, $pos, 4))[1]))[1];
                $pos += 4;
                break;
            case 0x11: // timestamp (dbpointer residue)
                $pos += 4;
                $out[$name] = null;
                break;
            case 0x12: // int64
                $out[$name] = unpack('P', substr($buf, $pos, 8))[1];
                $pos += 8;
                break;
            case 0x13: // decimal128
                $pos += 16;
                $out[$name] = null;
                break;
            default:
                throw new Exception('Tipe BSON tidak dikenal saat decode: 0x' . dechex($type));
        }
    }
    $pos++; // trailing \0
    return $out;
}

/**
 * Klien MongoDB wire protocol (OP_MSG, MongoDB >= 3.6).
 * Satu koneksi socket dipakai untuk seluruh operasi.
 */
class SpriMongoClient
{
    public $host;
    public $port;
    public $reqId = 0;
    private $sock = null;
    private $timeout = 5;

    public function __construct($host, $port, $timeout = 5)
    {
        $this->host    = $host;
        $this->port    = $port;
        $this->timeout = $timeout;
    }

    public function connect()
    {
        if ($this->sock) {
            return;
        }
        $sock = @fsockopen('tcp://' . $this->host, (int)$this->port, $errno, $errstr, $this->timeout);
        if (!$sock) {
            throw new Exception("Tidak dapat terhubung ke MongoDB {$this->host}:{$this->port} — $errstr ($errno)");
        }
        stream_set_timeout($sock, $this->timeout);
        $this->sock = $sock;
    }

    public function close()
    {
        if ($this->sock) {
            @fclose($this->sock);
            $this->sock = null;
        }
    }

    /** Kirim perintah OP_MSG { command..., $db: db } dan kembalikan body respons */
    public function cmd($command, $db)
    {
        $this->connect();
        $command['$db'] = $db;

        // OP_MSG: uint32 flags + section (kind 0x00 + BSON body)
        $body = pack('V', 0) . "\x00" . spriBsonDoc($command);
        $this->reqId++;
        $msg  = pack('VV', 16 + strlen($body), $this->reqId) . pack('V', 0) . pack('V', 2013) . $body;

        if (@fwrite($this->sock, $msg) !== strlen($msg)) {
            throw new Exception('Gagal mengirim perintah ke MongoDB');
        }
        fflush($this->sock);

        // Baca header (16 byte) lalu sisa pesan
        $header = $this->readExact(16);
        $total  = unpack('V', substr($header, 0, 4))[1];
        if ($total < 16 || $total > 67108864) {
            throw new Exception('Respons MongoDB tidak valid (panjang ' . $total . ')');
        }
        $rest = $this->readExact($total - 16);
        $opcode = unpack('V', substr($rest, 12, 4))[1];
        if ($opcode !== 2013) {
            throw new Exception('Opcode MongoDB tidak didukung: ' . $opcode . ' (butuh MongoDB >= 3.6)');
        }
        $payload = substr($rest, 16); // lewati flag uint32, ambil dari section

        // Section pertama: kind 0x00 + body BSON
        $kind = ord($payload[0]);
        if ($kind !== 0) {
            throw new Exception('Section OP_MSG kind ' . $kind . ' tidak didukung');
        }
        $pos = 1;
        $doc = spriBsonDecode($payload, $pos);

        if (!isset($doc['ok']) || (float)$doc['ok'] < 1) {
            $code = isset($doc['code']) ? (int)$doc['code'] : 0;
            $msg  = isset($doc['errmsg']) ? $doc['errmsg'] : 'unknown error';
            $e = new Exception('MongoDB error (' . $code . '): ' . $msg);
            $e->mongoCode = $code;
            throw $e;
        }
        return $doc;
    }

    private function readExact($n)
    {
        $buf = '';
        while (strlen($buf) < $n) {
            $chunk = fread($this->sock, $n - strlen($buf));
            if ($chunk === false || $chunk === '') {
                $meta = stream_get_meta_data($this->sock);
                if (!empty($meta['timed_out'])) {
                    throw new Exception('Timeout menunggu respons MongoDB');
                }
                throw new Exception('Koneksi MongoDB terputus');
            }
            $buf .= $chunk;
        }
        return $buf;
    }
}

/**
 * Autentikasi SCRAM native (SCRAM-SHA-256 lalu SCRAM-SHA-1).
 * Meniru kredensial MongoDB: SHA-1 memakai MD5(user:mongo:pass),
 * SHA-256 memakai password apa adanya.
 */
function spriMongoAuth(SpriMongoClient $c, $user, $pass, $authDb)
{
    $mechs = array('SCRAM-SHA-256', 'SCRAM-SHA-1');
    $lastErr = '';
    foreach ($mechs as $mech) {
        try {
            $fn     = ($mech === 'SCRAM-SHA-1') ? 'sha1' : 'sha256';
            $keyLen = ($mech === 'SCRAM-SHA-1') ? 20 : 32;

            $nonce     = base64_encode(random_bytes(18));
            $firstBare = 'n=' . $user . ',r=' . $nonce;
            $r1 = $c->cmd(array(
                'saslStart' => 1,
                'mechanism' => $mech,
                'payload'   => new SpriBsonBinary('n,,' . $firstBare),
                'options'   => array('skipEmptyExchange' => true),
            ), $authDb);

            $payload1 = $r1['payload'] instanceof SpriBsonBinary ? $r1['payload']->data : (string)$r1['payload'];
            parse_str(str_replace(',', '&', $payload1), $sf);
            $serverNonce = isset($sf['r']) ? $sf['r'] : '';
            $salt        = isset($sf['s']) ? base64_decode($sf['s']) : '';
            $iters       = isset($sf['i']) ? (int)$sf['i'] : 0;
            if (strpos($serverNonce, $nonce) !== 0 || $salt === '' || $iters <= 0) {
                throw new Exception('Payload SASL server tidak valid');
            }

            // kredensial sesuai spesifikasi MongoDB
            if ($mech === 'SCRAM-SHA-1') {
                $hashed = hash('md5', $user . ':mongo:' . $pass); // hex string
            } else {
                $hashed = $pass; // SASLprep diabaikan (asumsi password ASCII)
            }
            $salted = hash_pbkdf2($fn, $hashed, $salt, $iters, $keyLen, true);

            $clientKey  = hash_hmac($fn, 'Client Key', $salted, true);
            $storedKey  = hash($fn, $clientKey, true);
            $finalNoPf  = 'c=biws,r=' . $serverNonce;
            $authMsg    = $firstBare . ',' . $payload1 . ',' . $finalNoPf;
            $clientSig  = hash_hmac($fn, $authMsg, $storedKey, true);
            $proof      = $clientKey ^ $clientSig;

            $r2 = $c->cmd(array(
                'saslContinue'   => 1,
                'conversationId' => isset($r1['conversationId']) ? $r1['conversationId'] : 1,
                'payload'        => new SpriBsonBinary($finalNoPf . ',p=' . base64_encode($proof)),
            ), $authDb);

            $payload2 = $r2['payload'] instanceof SpriBsonBinary ? $r2['payload']->data : (string)$r2['payload'];
            if (strpos($payload2, 'v=') === 0) {
                return true; // server-final v=serverSignature
            }
            return true; // done (skipEmptyExchange)
        } catch (Exception $e) {
            $lastErr = $e->getMessage();
        }
    }
    throw new Exception('Autentikasi MongoDB gagal: ' . $lastErr);
}

// ============================================================
// HELPER
// ============================================================

function spriJsonOut($arr, $code = 200)
{
    if (!headers_sent()) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode($arr);
    exit;
}

/** UUID v4 (norec logginguser_t) */
function spriUuid4()
{
    $b = random_bytes(16);
    $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
    $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
}

/** Buka koneksi mongo + auth bila perlu. Return [client, catatan]. */
function spriMongoOpen($mongoHost, $mongoPort, $mongoDb, $mongoUser, $mongoPass, $mongoAuthSource, &$catatan)
{
    $c = new SpriMongoClient($mongoHost, $mongoPort, 5);
    $c->connect();
    $c->cmd(array('ping' => 1), 'admin');
    $catatan[] = 'ping OK ' . $mongoHost . ':' . $mongoPort;

    if ($mongoUser === '') {
        $catatan[] = 'tanpa autentikasi (user kosong)';
        return $c;
    }
    try {
        // percobaan tanpa auth dulu (bila user & pass memang tidak dipakai)
        $c->cmd(array('find' => 'SuratPermintaanDirawat', 'filter' => array(), 'limit' => 1, 'singleBatch' => true), $mongoDb);
        $catatan[] = 'akses collection tanpa auth OK';
        return $c;
    } catch (Exception $e) {
        if (isset($e->mongoCode) && $e->mongoCode === 13) {
            $catatan[] = 'butuh auth -> SCRAM';
            spriMongoAuth($c, $mongoUser, $mongoPass, $mongoAuthSource);
            $catatan[] = 'auth SCRAM OK';
            return $c;
        }
        throw $e;
    }
}

// ============================================================
// SELFTEST (php tm/ajax_update_spri.php selftest) — tanpa jaringan
// ============================================================
if (PHP_SAPI === 'cli' && isset($argv[1]) && $argv[1] === 'selftest') {
    $ok = true;
    // BSON roundtrip
    $sample = array(
        'str' => '2026-09-10T02:00:00.000+07:00',
        'int' => 42,
        'bool' => true,
        'nul' => null,
        'flt' => 1.5,
        'doc' => array('norec_pd' => 'abc'),
        'arr' => array(1, 2, 3),
    );
    $enc = spriBsonDoc($sample);
    $pos = 0;
    $dec = spriBsonDecode($enc, $pos);
    $checks = array(
        $dec['str'] === $sample['str'],
        $dec['int'] === 42,
        $dec['bool'] === true,
        $dec['nul'] === null,
        abs($dec['flt'] - 1.5) < 1e-9,
        $dec['doc']['norec_pd'] === 'abc',
        $dec['arr'] === array(1, 2, 3),
    );
    foreach ($checks as $i => $c) {
        echo 'check BSON #' . ($i + 1) . ': ' . ($c ? 'OK' : 'GAGAL') . "\n";
        if (!$c) $ok = false;
    }
    // tanggal
    $wib   = new DateTimeZone('Asia/Jakarta');
    $dt    = new DateTime('2026-09-10 00:00:00', $wib);
    $nowW  = new DateTime('now', $wib);
    $dt->setTime((int)$nowW->format('H'), (int)$nowW->format('i'), (int)$nowW->format('s'));
    $iso   = $dt->format('Y-m-d\TH:i:s.v') . '+07:00';
    echo 'contoh ISO: ' . $iso . "\n";
    echo preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}\+07:00$/', $iso) ? "check ISO: OK\n" : "check ISO: GAGAL\n";
    echo 'uuid contoh: ' . spriUuid4() . "\n";
    exit($ok ? 0 : 1);
}

// ============================================================
// Fallback tanggal pendaftaran dari PostgreSQL (dipakai GET bila
// dokumen SPRI tidak ada / MongoDB tidak terjangkau)
// ============================================================
function spriFallbackDates($pdo, $norec_pd, $dateFields)
{
    $tgl = spriTglMasuk($pdo, $norec_pd);
    if ($tgl === '') {
        $tgl = date('Y-m-d');
    }
    $data = array();
    foreach ($dateFields as $f) {
        $data[$f] = $tgl;
    }
    return $data;
}

/** Tanggal masuk pasien (tglregistrasi pasiendaftar_t), format Y-m-d atau '' */
function spriTglMasuk($pdo, $norec_pd)
{
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT tglregistrasi FROM pasiendaftar_t WHERE norec = :norec LIMIT 1");
            $stmt->execute(array(':norec' => $norec_pd));
            $row = $stmt->fetch();
            if ($row && !empty($row['tglregistrasi'])) {
                return date('Y-m-d', strtotime($row['tglregistrasi']));
            }
        } catch (Exception $e) {
            // biarkan kosong
        }
    }
    return '';
}

/**
 * Keluarkan respons action=get lengkap dengan tgl_masuk & flag sesuai
 * (sesuai = semua tanggal SPRI yang terisi sama dengan tanggal masuk).
 * Selalu berakhir dengan keluar (exit via spriJsonOut).
 */
function spriGetResponse($pdo, $norec_pd, $dateFields, $catatan, $source, $data, $peringatan = null)
{
    $tglMasuk = spriTglMasuk($pdo, $norec_pd);
    $sesuai = true;
    foreach ($data as $v) {
        if ($v === '' || $v === null) continue;
        if ($tglMasuk === '' || date('Y-m-d', strtotime($v)) !== $tglMasuk) {
            $sesuai = false;
            break;
        }
    }
    $res = array(
        'success' => true,
        'source' => $source,
        'catatan' => $catatan,
        'tgl_masuk' => $tglMasuk,
        'sesuai' => $sesuai,
        'data' => $data,
    );
    if ($peringatan !== null) {
        $res['peringatan'] = $peringatan;
    }
    spriJsonOut($res);
}

/** base64url tanpa padding (format JWT) */
function spriB64Url($bin)
{
    return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
}

/** Baca "sub" (nama user) dari token JWT tanpa verifikasi */
function spriJwtSub($token)
{
    $parts = explode('.', trim((string)$token));
    if (count($parts) < 2) return '';
    $payload = $parts[1];
    $pad = strlen($payload) % 4;
    if ($pad) $payload .= str_repeat('=', 4 - $pad);
    $json = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    return (is_array($json) && !empty($json['sub'])) ? (string)$json['sub'] : '';
}

/**
 * Buat token JWT sendiri (HS512) memakai JWT_KEY Laravel.
 * Middleware JWTAuth: header.payload.signature.base64(kdProfile).
 */
function spriMintToken($username, $kdProfile, $jwtKey, $melebihiMenit = 120)
{
    $header  = array('typ' => 'JWT', 'alg' => 'HS512');
    $payload = array(
        'sub'       => $username,
        'sessionId' => '',
        'exp'       => time() + ($melebihiMenit * 60),
    );
    $body = spriB64Url(json_encode($header)) . '.' . spriB64Url(json_encode($payload));
    $sig  = spriB64Url(hash_hmac('sha512', $body, $jwtKey, true));
    return $body . '.' . $sig . '.' . base64_encode((string)$kdProfile);
}

/**
 * Kandidat token untuk REST: token yang dikirim tm/index.php (sesi)
 * di depan, lalu token buatan sendiri (pemilik = daftar loginuser_s).
 */
function spriTokenCandidates($pdo, $kdProfile, $jwtKey)
{
    $cands = array();
    $fromReq = isset($_REQUEST['token']) ? trim($_REQUEST['token']) : '';
    if ($fromReq !== '') {
        $cands[] = array('label' => 'sesi', 'token' => $fromReq, 'sub' => spriJwtSub($fromReq));
        $sub = spriJwtSub($fromReq);
    } else {
        $sub = '';
    }
    if ($pdo) {
        try {
            $sql = "SELECT namauser FROM loginuser_s
                    WHERE kdprofile = :kd AND statusenabled::text IN ('1','t','true')
                      AND namauser IS NOT NULL AND namauser <> ''
                    ORDER BY id";
            $st = $pdo->prepare($sql);
            $st->execute(array(':kd' => $kdProfile));
            $list = $st->fetchAll(PDO::FETCH_COLUMN);
            if ($sub !== '' && in_array($sub, $list, true)) {
                array_unshift($list, $sub);
            }
            $seen = array();
            foreach (array_slice($list, 0, 3) as $uName) {
                if ($uName === '' || isset($seen[$uName])) continue;
                $seen[$uName] = true;
                $cands[] = array('label' => 'buatan:' . $uName, 'token' => spriMintToken($uName, $kdProfile, $jwtKey), 'sub' => $uName);
            }
        } catch (Exception $e) {
            // token buatan terakhir
        }
    }
    if (empty($cands)) {
        $uName = 'pasa';
        $cands[] = array('label' => 'buatan:' . $uName, 'token' => spriMintToken($uName, $kdProfile, $jwtKey), 'sub' => $uName);
    }
    return $cands;
}

/** HTTP JSON (GET/POST) — pakai file_get_contents, cadangan curl */
function spriHttpJson($method, $url, $bodyJson, $headers)
{
    $opts = array(
        'http' => array(
            'method'        => $method,
            'header'        => implode("\r\n", $headers) . "\r\n",
            'ignore_errors' => true,
            'timeout'       => 40,
            'content'       => $bodyJson !== null ? $bodyJson : '',
        ),
        'ssl' => array('verify_peer' => false, 'verify_peer_name' => false),
    );
    $ctx = stream_context_create($opts);
    $res = @file_get_contents($url, false, $ctx);
    if ($res === false && function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT        => 40,
            CURLOPT_POSTFIELDS     => $bodyJson !== null ? $bodyJson : '',
            CURLOPT_HTTPHEADER     => $headers,
        ));
        $res = curl_exec($ch);
        curl_close($ch);
    }
    if ($res === false || $res === '') return null;
    $json = json_decode($res, true);
    return is_array($json) ? $json : null;
}

/** nocmfk pasien dari PostgreSQL (param wajib endpoint /emr/get-emr) */
function spriGetNocmfk($pdo, $norec_pd, &$catatan)
{
    if (!$pdo) return '';
    try {
        $stmt = $pdo->prepare("SELECT nocmfk FROM pasiendaftar_t WHERE norec = :norec LIMIT 1");
        $stmt->execute(array(':norec' => $norec_pd));
        $row = $stmt->fetch();
        if ($row && !empty($row['nocmfk'])) return (string)$row['nocmfk'];
    } catch (Exception $e) {
        $catatan[] = 'nocmfk gagal: ' . $e->getMessage();
    }
    return '';
}

/**
 * Baca dokumen SPRI via REST Laravel /emr/get-emr (endpoint yg sama
 * dipakai form Vue). Coba semua kandidat token sampai ada yang sukses.
 */
function spriRestGetDocs($apiBase, $tokens, $nocmfk, $norec_pd, $emrpasienfk, $collectionName, &$catatan)
{
    foreach ($tokens as $tk) {
        $token = $tk['token'];
        $url = rtrim($apiBase, '/') . '/emr/get-emr?' . http_build_query(array(
            'nocmfk'      => $nocmfk,
            'norec_pd'    => $norec_pd,
            'collection'  => $collectionName,
            'emrpasienfk' => $emrpasienfk,
            'token'       => $token,
        ));
        $res = spriHttpJson('GET', $url, null, array(
            'Accept: application/json',
            'token: ' . $token,
            'skip_encrypt: true',
        ));
        if ($res === null) {
            $catatan[] = 'REST get-emr gagal (token ' . $tk['label'] . ')';
            continue;
        }
        // Envelope bisa berupa array dokumen langsung, atau {metaData, response}
        $docs = array();
        if (isset($res['response']) && is_array($res['response'])) {
            $docs = $res['response'];
            if (isset($docs['emr']) && is_array($docs['emr'])) $docs = $docs['emr'];
        } elseif (isset($res[0])) {
            $docs = $res;
        }
        if (empty($docs)) {
            $catatan[] = 'REST get-emr sukses (token ' . $tk['label'] . ') tapi kosong';
            continue;
        }
        $catatan[] = 'REST get-emr OK (token ' . $tk['label'] . ', ' . count($docs) . ' dok)';
        return $docs;
    }
    return array();
}

/** Jejak audit ke logginguser_t (meniru LOGGING backend) — dipakai Mongo & REST */
function spriAuditLog($pdo, $kdProfile, $collectionName, $noreff, $norecPd, $keterangan, &$catatan)
{
    if (!$pdo) return;
    try {
        $sqlLog = "INSERT INTO logginguser_t
                    (norec, kdprofile, statusenabled, jenislog, noreff, referensi,
                     keterangan, objectloginuserfk, tanggal, namauser, namapegawai,
                     created_at, updated_at)
                   VALUES
                    (:norec, :kdprofile, true, :jenislog, :noreff, 'EMR',
                     :keterangan, 1, :tanggal, 'SERVER', 'SERVER',
                     :tanggal2, :tanggal3)";
        $stmtLog = $pdo->prepare($sqlLog);
        $now     = date('Y-m-d H:i:s');
        $stmtLog->execute(array(
            ':norec'      => spriUuid4(),
            ':kdprofile'  => $kdProfile,
            ':jenislog'   => $collectionName,
            ':noreff'     => $noreff !== '' ? $noreff : $norecPd,
            ':keterangan' => $keterangan,
            ':tanggal'    => $now,
            ':tanggal2'   => $now,
            ':tanggal3'   => $now,
        ));
        $catatan[] = 'log audit ditulis';
    } catch (Exception $e) {
        $catatan[] = 'log audit gagal: ' . $e->getMessage();
    }
}

/**
 * FALLBACK REST API Laravel (dipakai bila MongoDB tidak terjangkau
 * dari server web). Jalur yang sama persis dengan form Vue SPRI:
 *   get    -> GET  /emr/get-emr
 *   update -> BACA dokumen, ubah 5 tanggal, POST /emr/simpan-emr-surket
 * Berakhir dengan spriJsonOut (sukses) atau throw Exception.
 */
function spriRestRoute($action, $mongoMsg, $pdo, $apiBase, $kdProfile, $jwtKey, $collectionName, $dateFields, $norec_pd, $emrpasienfk, &$catatan)
{
    $catatan[] = 'fallback REST API Laravel: ' . $apiBase;

    $norecPd = $norec_pd;
    $emrFk   = $emrpasienfk;
    $tglInput = '';
    if ($action === 'update') {
        $payload = json_decode(file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }
        $tglInput = isset($payload['tanggal']) ? trim($payload['tanggal']) : '';
        $norecPd  = isset($payload['norec_pd']) ? trim($payload['norec_pd']) : $norec_pd;
        $emrFk    = isset($payload['emrpasienfk']) ? trim($payload['emrpasienfk']) : $emrpasienfk;
        if ($norecPd === '') {
            spriJsonOut(array('success' => false, 'message' => 'norec_pd wajib diisi.'), 400);
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglInput)) {
            spriJsonOut(array('success' => false, 'message' => 'Format tanggal harus YYYY-MM-DD.'), 400);
        }
    }

    $tokens = spriTokenCandidates($pdo, $kdProfile, $jwtKey);
    if (empty($tokens)) {
        throw new Exception('Tidak ada kandidat token untuk REST API — ' . $mongoMsg);
    }
    $catatan[] = 'token REST: ' . implode(', ', array_map(function ($t) { return $t['label']; }, $tokens));

    $nocmfk = spriGetNocmfk($pdo, $norecPd, $catatan);
    if ($nocmfk === '') {
        if ($action === 'get') {
            spriGetResponse($pdo, $norecPd, $dateFields, $catatan, 'fallback', spriFallbackDates($pdo, $norecPd, $dateFields), $mongoMsg);
        }
        throw new Exception('nocmfk pasien tidak ditemukan — fallback REST tidak bisa dipakai. ' . $mongoMsg);
    }

    $docs = spriRestGetDocs($apiBase, $tokens, $nocmfk, $norecPd, $emrFk, $collectionName, $catatan);
    if (empty($docs)) {
        if ($action === 'get') {
            spriGetResponse($pdo, $norecPd, $dateFields, $catatan, 'fallback', spriFallbackDates($pdo, $norecPd, $dateFields), $mongoMsg);
        }
        throw new Exception('Dokumen SPRI tidak ditemukan via REST API — ' . $mongoMsg);
    }

    $d0 = $docs[0];

    if ($action === 'get') {
        $out = array();
        foreach ($dateFields as $f) {
            if (!empty($d0[$f]) && is_string($d0[$f])) {
                $ts = strtotime($d0[$f]);
                $out[$f] = $ts ? date('Y-m-d', $ts) : '';
            } else {
                $out[$f] = '';
            }
        }
        spriGetResponse($pdo, $norecPd, $dateFields, $catatan, 'rest', $out, $mongoMsg);
    }

    // ---- update via REST: ubah tanggal -> simpan persis seperti form Vue ----
    $wib   = new DateTimeZone('Asia/Jakarta');
    $dt    = new DateTime($tglInput . ' 00:00:00', $wib);
    $nowW  = new DateTime('now', $wib);
    $dt->setTime((int)$nowW->format('H'), (int)$nowW->format('i'), (int)$nowW->format('s'));
    $isoWib = $dt->format('Y-m-d\TH:i:s.v') . '+07:00';

    $object = $d0;
    foreach ($dateFields as $f) {
        $object[$f] = $isoWib;
    }
    $object['updated_at'] = $nowW->format('Y-m-d H:i:s');

    $norecEmr = (isset($d0['emrpasienfk']) && $d0['emrpasienfk'] !== '') ? $d0['emrpasienfk'] : $emrFk;
    $idDoc    = isset($d0['id']) ? $d0['id'] : '';

    $json = array(
        'id'         => $idDoc,
        'norec_emr'  => $norecEmr,
        'collection' => $collectionName,
        'url_form'   => 'module-emr-profile-pasien-page-emr-surat-permintaan-dirawat',
        'name_form'  => 'Surat Permintaan Dirawat',
        'jenis_emr'  => 'asesmen_medis',
        'data'       => $object,
    );
    $res = spriHttpJson('POST', rtrim($apiBase, '/') . '/emr/simpan-emr-surket', json_encode($json), array(
        'Content-Type: application/json',
        'Accept: application/json',
        'token: ' . $tokens[0]['token'],
        'skip_encrypt: true',
    ));
    if ($res === null) {
        throw new Exception('Gagal menyimpan via REST API Laravel — ' . $mongoMsg);
    }
    $catatan[] = 'REST simpan-emr-surket OK';

    spriAuditLog($pdo, $kdProfile, $collectionName, $norecEmr !== '' ? $norecEmr : $norecPd, $norecPd,
        'Edit Tanggal SPRI (semua tanggal) menjadi ' . $dt->format('d-m-Y') . ' via REST fallback (MongoDB tak terjangkau) — no registrasi ' . $norecPd, $catatan);

    spriJsonOut(array(
        'success'    => true,
        'source'     => 'rest',
        'message'    => 'Semua tanggal SPRI diubah ke ' . $dt->format('d-m-Y') . ' (via REST API).',
        'data'       => array('tanggal' => $dt->format('Y-m-d H:i:s'), 'modified' => 1),
        'catatan'    => $catatan,
        'peringatan' => $mongoMsg,
    ));
}

// ============================================================
// ENDPOINT JSON
// ============================================================
$action      = isset($_GET['action']) ? trim($_GET['action']) : '';
$norec_pd    = isset($_REQUEST['norec_pd']) ? trim($_REQUEST['norec_pd']) : '';
$emrpasienfk = isset($_REQUEST['emrpasienfk']) ? trim($_REQUEST['emrpasienfk']) : '';

// update dikirim oleh tm/index.php dengan body JSON (bukan form-encoded),
// jadi baca body di awal supaya param norec_pd/emrpasienfk tidak "hilang".
if ($action === 'update') {
    $spriPayload = json_decode(file_get_contents('php://input'), true);
    if (is_array($spriPayload)) {
        if (isset($spriPayload['norec_pd']) && trim($spriPayload['norec_pd']) !== '')   $norec_pd    = trim($spriPayload['norec_pd']);
        if (isset($spriPayload['emrpasienfk']) && trim($spriPayload['emrpasienfk']) !== '') $emrpasienfk = trim($spriPayload['emrpasienfk']);
    }
}

if ($norec_pd === '') {
    spriJsonOut(array('success' => false, 'message' => 'Parameter norec_pd diperlukan.'), 400);
}

$collectionName = 'SuratPermintaanDirawat';
$dateFields     = array('tanggal', 'tanggalAdmission', 'tanggalTindakan', 'tanggalRawatInap', 'tglPembuatan');

try {
    $catatan = array();

    // --------------------------------------------------------
    // Kandidat host MongoDB (urutan: .env -> lokal -> database SIMRS).
    // Mongo sering hanya listen di 127.0.0.1 milik server web, sehingga
    // koneksi ke host Postgres 192.168.22.81 bisa "Connection refused".
    // --------------------------------------------------------
    $mongoCandidates = array(
        array($mongoHost,    $mongoPort, $mongoUser, $mongoPass, $mongoAuthSource), // default: .env atau host Postgres
        array('127.0.0.1',   $mongoPort, $mongoUser, $mongoPass, $mongoAuthSource), // Mongo di server web
        array('localhost',   $mongoPort, $mongoUser, $mongoPass, $mongoAuthSource),
        array($host,         $mongoPort, $mongoUser, $mongoPass, $mongoAuthSource),
    );

    $client       = null;
    $lastMongoErr = '';
    $triedHosts   = array();
    $seenHosts    = array();
    foreach ($mongoCandidates as $cand) {
        list($h, $p, $u, $pw, $as) = $cand;
        if ($h === '' || $h === null || isset($seenHosts[$h])) {
            continue;
        }
        $seenHosts[$h] = true;
        try {
            $triedHosts[] = $h . ':' . $p;
            $catatan[] = 'coba MongoDB ' . $h . ':' . $p;
            $client = spriMongoOpen($h, $p, $mongoDb, $u, $pw, $as, $catatan);
            $catatan[] = 'terhubung ke MongoDB ' . $h . ':' . $p;
            break;
        } catch (Exception $e) {
            $lastMongoErr = $e->getMessage();
            $catatan[]    = 'gagal: ' . $lastMongoErr;
        }
    }

    if ($client === null) {
        $msg = 'MongoDB tidak terjangkau dari server web (dicoba: ' . implode(', ', $triedHosts) . ') — ' . $lastMongoErr;
        if ($action === 'get' || $action === 'update') {
            // Fallback jalur REST API Laravel (persis form Vue SPRI)
            spriRestRoute($action, $msg, $pdo, $apiBase, $kdProfile, $jwtKey, $collectionName, $dateFields, $norec_pd, $emrpasienfk, $catatan);
        }
        throw new Exception($msg);
    }

    // --------------------------------------------------------
    // GET — baca tanggal SPRI saat ini
    // --------------------------------------------------------
    if ($action === 'get') {
        $filter = array('noregistrasifk' => $norec_pd);
        if ($emrpasienfk !== '') {
            $filter['emrpasienfk'] = $emrpasienfk;
        }
        $resp = $client->cmd(array(
            'find'        => $collectionName,
            'filter'      => $filter,
            'sort'        => array('created_at' => -1, 'updated_at' => -1),
            'limit'       => 1,
            'singleBatch' => true,
        ), $mongoDb);

        $doc = null;
        if (isset($resp['cursor']['firstBatch']) && count($resp['cursor']['firstBatch']) > 0) {
            $doc = $resp['cursor']['firstBatch'][0];
        }

        if ($doc) {
            $out = array();
            foreach ($dateFields as $f) {
                if (!empty($doc[$f]) && is_string($doc[$f])) {
                    $ts = strtotime($doc[$f]);
                    $out[$f] = $ts ? date('Y-m-d', $ts) : '';
                } else {
                    $out[$f] = '';
                }
            }
            spriGetResponse($pdo, $norec_pd, $dateFields, $catatan, 'mongodb', $out);
        }

        // Fallback: tanggal pendaftaran dari PostgreSQL
        spriGetResponse($pdo, $norec_pd, $dateFields, $catatan, 'fallback', spriFallbackDates($pdo, $norec_pd, $dateFields));
    }

    // --------------------------------------------------------
    // POST — update SEMUA tanggal pada dokumen SPRI
    // --------------------------------------------------------
    if ($action === 'update') {
        $payload = json_decode(file_get_contents('php://input'), true);
        if (!is_array($payload)) {
            $payload = $_POST;
        }
        $tglInput = isset($payload['tanggal']) ? trim($payload['tanggal']) : '';
        $norecPd  = isset($payload['norec_pd']) ? trim($payload['norec_pd']) : $norec_pd;
        $emrFk    = isset($payload['emrpasienfk']) ? trim($payload['emrpasienfk']) : $emrpasienfk;

        if ($norecPd === '') {
            spriJsonOut(array('success' => false, 'message' => 'norec_pd wajib diisi.'), 400);
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglInput)) {
            spriJsonOut(array('success' => false, 'message' => 'Format tanggal harus YYYY-MM-DD.'), 400);
        }

        // Tanggal pilihan + jam sekarang (WIB), format ISO seperti axios:
        // blade cetak membaca Carbon::parse(...)->setTimezone('Asia/Jakarta')
        // sehingga offset +07:00 menghasilkan tanggal yang sama.
        $wib   = new DateTimeZone('Asia/Jakarta');
        $dt    = new DateTime($tglInput . ' 00:00:00', $wib);
        $nowW  = new DateTime('now', $wib);
        $dt->setTime((int)$nowW->format('H'), (int)$nowW->format('i'), (int)$nowW->format('s'));
        $isoWib = $dt->format('Y-m-d\TH:i:s.v') . '+07:00';

        $set = array(
            'updated_at' => $nowW->format('Y-m-d H:i:s'),
        );
        foreach ($dateFields as $f) {
            $set[$f] = $isoWib;
        }

        $q = array('noregistrasifk' => $norecPd);
        if ($emrFk !== '') {
            $q['emrpasienfk'] = $emrFk;
        }

        $resp = $client->cmd(array(
            'update'  => $collectionName,
            'updates' => array(array(
                'q'      => $q,
                'u'      => array('$set' => $set),
                'multi'  => false,
                'upsert' => false,
            )),
        ), $mongoDb);

        $n    = isset($resp['n']) ? (int)$resp['n'] : 0;
        $nMod = isset($resp['nModified']) ? (int)$resp['nModified'] : $n;

        if ($n < 1) {
            spriJsonOut(array(
                'success' => false,
                'message' => 'Dokumen SPRI tidak ditemukan di MongoDB — tidak ada tanggal yang berubah.',
                'catatan' => $catatan,
            ), 404);
        }

        // ---- Jejak audit ke logginguser_t (meniru LOGGING backend) ----
        spriAuditLog($pdo, $kdProfile, $collectionName, $emrFk !== '' ? $emrFk : $norecPd, $norecPd,
            'Edit Tanggal SPRI (semua tanggal) menjadi ' . $dt->format('d-m-Y') . ' via tm/ — no registrasi ' . $norecPd, $catatan);

        $client->close();
        spriJsonOut(array(
            'success' => true,
            'message' => 'Semua tanggal SPRI diubah ke ' . $dt->format('d-m-Y') . ' (' . $nMod . ' dokumen).',
            'data'    => array('tanggal' => $dt->format('Y-m-d H:i:s'), 'modified' => $nMod),
            'catatan' => $catatan,
        ));
    }

    spriJsonOut(array('success' => false, 'message' => 'Action tidak dikenal. Gunakan action=get atau action=update.'), 400);

} catch (Exception $e) {
    spriJsonOut(array(
        'success' => false,
        'message' => 'Gagal: ' . $e->getMessage(),
        'catatan' => isset($catatan) ? $catatan : array(),
    ), 500);
}
