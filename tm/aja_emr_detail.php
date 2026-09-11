<?php
/**
 * ajax_emr_detail.php
 * Native PHP port dari tab/card EMR di frontend-v2 (t-emr-detail.vue LIST_EMR)
 * + backend ProfilePasienCtrl::detailPelayanan (response.emr dari #ResumeEMR / emrpasien_t)
 *
 * Dipanggil dari tm/index.php saat tombol EMR diklik (modal AJAX).
 */
$host     = "192.168.22.81";
$port     = "5792";
$dbname   = "rsud_malangbong";
$user     = "postgres";
$password = "Tr4nsm3d!c MaRe#T3aM";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo '<p class="text-red-500">Gagal koneksi database</p>';
    exit;
}

$norec_pd     = trim($_GET['norec_pd'] ?? '');
$noregistrasi = trim($_GET['noregistrasi'] ?? '');
$nocmfk       = trim($_GET['nocmfk'] ?? '');
$norec_apd    = trim($_GET['norec_apd'] ?? '');
$emr_surat_fk = trim($_GET['emr_surat_fk'] ?? '');
$qSearch      = trim($_GET['q'] ?? '');

if ($norec_pd === '') {
    http_response_code(400);
    echo '<p class="text-red-500">Parameter norec_pd diperlukan</p>';
    exit;
}

$token     = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwYXNhIiwic2Vzc2lvbklkIjoiN2FlODRkMWQtODE0Ny00Yzg2LTk3YmYtMjczZDQ4ZjhlNjZlIiwiZXhwIjoxNzgyMjc0NTk1fQ.s1y3_kHquIMFXLUrySNuyXWQarceI6VhAqUveszO9uhpnGoT_peADF4hdNAiZxhN7uycLVvuicgk_6XgkY3WOQ.MQ==';
$userCetak = 'Pasa Pirdaos, A.Md.A.K';
$kdProfile = 1;
$baseUrl   = 'https://192.168.22.81';

// Warna ikon box (mirip listColor di Vue)
$listColor = ['info', 'success', 'warning', 'danger', 'purple', 'orange', 'primary', 'blue', 'green', 'indigo'];
$colorHex  = [
    'info'    => ['bg' => '#dbeafe', 'fg' => '#1d4ed8'],
    'success' => ['bg' => '#d1fae5', 'fg' => '#047857'],
    'warning' => ['bg' => '#fef3c7', 'fg' => '#b45309'],
    'danger'  => ['bg' => '#fee2e2', 'fg' => '#b91c1c'],
    'purple'  => ['bg' => '#ede9fe', 'fg' => '#6d28d9'],
    'orange'  => ['bg' => '#ffedd5', 'fg' => '#c2410c'],
    'primary' => ['bg' => '#e0e7ff', 'fg' => '#4338ca'],
    'blue'    => ['bg' => '#dbeafe', 'fg' => '#2563eb'],
    'green'   => ['bg' => '#dcfce7', 'fg' => '#15803d'],
    'indigo'  => ['bg' => '#e0e7ff', 'fg' => '#4338ca'],
];

// Label & warna tombol cetak cepat (dari draft aja_emr_detail_dulu.php)
$quickPrintMap = [
    'RujukanPasien'         => ['label' => 'Cetak Rujukan Manual', 'color' => 'bg-red-600 hover:bg-red-700', 'icon' => 'fas fa-print'],
    'SuratPermintaanDirawat'=> ['label' => 'Cetak SPRI',           'color' => 'bg-green-600 hover:bg-green-700', 'icon' => 'fas fa-file-medical-alt'],
    'resumeMedis'           => ['label' => 'Cetak Resume Medis',   'color' => 'bg-emerald-600 hover:bg-emerald-700', 'icon' => 'fas fa-notes-medical'],
    'ResumeMedis'           => ['label' => 'Cetak Resume Medis',   'color' => 'bg-emerald-600 hover:bg-emerald-700', 'icon' => 'fas fa-notes-medical'],
    'CPPT'                  => ['label' => 'Cetak CPPT',           'color' => 'bg-blue-600 hover:bg-blue-700', 'icon' => 'fas fa-clipboard-list'],
    'RingkasanPulang'       => ['label' => 'Cetak Ringkasan Pulang','color' => 'bg-indigo-600 hover:bg-indigo-700', 'icon' => 'fas fa-file-alt'],
];

/**
 * Format tanggal Indonesia sederhana (DD-MM-YYYY HH:ii)
 */
function formatDateIndoSimple($dt) {
    if (empty($dt)) return '-';
    $ts = is_numeric($dt) ? (int)$dt : strtotime($dt);
    if (!$ts) return htmlspecialchars((string)$dt);
    return date('d-m-Y H:i', $ts);
}

/**
 * Ubah CamelCase / snake menjadi label spasi
 */
function humanizeEmrName($name) {
    if ($name === null || $name === '') return 'EMR';
    $name = str_replace(['_', '-'], ' ', $name);
    $name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);
    $name = preg_replace('/\s+/', ' ', $name);
    return trim(ucwords(strtolower($name)));
}

/**
 * Bangun URL cetak EMR (setara H.printBlade emr/cetak/{collection})
 */
function buildCetakUrl($baseUrl, $collection, $emrpasienfk, $noregistrasi, $userCetak, $kdProfile, $token) {
    $collection = rawurlencode($collection);
    $q = http_build_query([
        'pdf'         => 'true',
        'emrpasienfk' => $emrpasienfk,
        'noregistrasi'=> $noregistrasi,
        'user'        => $userCetak,
        'kdprofile'   => $kdProfile,
        'token'       => $token,
    ]);
    return rtrim($baseUrl, '/') . '/service/emr/cetak/' . $collection . '?' . $q;
}

/**
 * Bangun URL buka form EMR di frontend Vue
 */
function buildEditUrl($baseUrl, $urlForm, $nocmfk, $norec_pd, $norec_apd, $emrpasienfk) {
    $slug = $urlForm ?: 'surat-permintaan-dirawat';
    // url_form di master kadang sudah slug, kadang path penuh
    $slug = preg_replace('#^/+#', '', $slug);
    $slug = preg_replace('#^module/emr/profile-pasien/page-emr/#', '', $slug);
    $q = http_build_query([
        'nocmfk'              => $nocmfk,
        'norec_pasien_daftar' => $norec_pd,
        'norec_pd'            => $norec_pd,
        'norec_apd'           => $norec_apd,
        'norec_emr'           => $emrpasienfk,
        'edit'                => 'true',
    ]);
    return rtrim($baseUrl, '/') . '/module/emr/profile-pasien/page-emr/' . $slug . '?' . $q;
}

/**
 * Coba ambil daftar EMR lewat API Laravel (sama endpoint frontend: /emr/detail-pelayanan)
 * Mengembalikan array item LIST_EMR atau [] jika gagal.
 */
function fetchEmrFromApi($baseUrl, $norec_pd, $nocmfk, $token) {
    $url = rtrim($baseUrl, '/') . '/service/emr/detail-pelayanan?' . http_build_query([
        'norec_pd' => $norec_pd,
        'nocmfk'   => $nocmfk,
        'token'    => $token,
        'kdprofile'=> 1,
    ]);
    if (!function_exists('curl_init')) {
        return [];
    }
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 8,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            'token: ' . $token,
        ],
    ]);
    $body = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code < 200 || $code >= 300 || !$body) {
        return [];
    }
    $json = json_decode($body, true);
    if (!is_array($json)) {
        return [];
    }
    // Bentuk response bisa { data: { emr: [...] } } atau { emr: [...] } atau langsung array
    $emr = $json['emr']
        ?? ($json['data']['emr'] ?? null)
        ?? ($json['result']['emr'] ?? null);
    if (!is_array($emr)) {
        return [];
    }
    return $emr;
}

/**
 * Fallback: ambil dari Postgres emrpasien_t (+ mapping caption/icon/url dari emr_t)
 * Setara ringkasan #ResumeEMR bila Mongo tidak tersedia.
 */
function fetchEmrFromPostgres(PDO $pdo, $norec_pd, $kdProfile) {
    $sql = "
        SELECT
            ep.norec          AS emrpasienfk,
            ep.jenisemr,
            ep.tglemr         AS last_update,
            ep.namaruangan    AS ruangan,
            ep.noemr,
            ep.norec_apd,
            ep.noregistrasi,
            ep.nocmfk,
            COALESCE(pg.namalengkap, '-') AS author,
            e.caption         AS caption_master,
            e.url_form        AS url_form_master,
            e.icon            AS icon_master,
            e.collection      AS collection_master
        FROM emrpasien_t ep
        LEFT JOIN pegawai_m pg ON pg.id = ep.pegawaifk
        LEFT JOIN LATERAL (
            SELECT caption, url_form, icon, collection
            FROM emr_t
            WHERE statusenabled::text IN ('1','t','true')
              AND (
                    collection = ep.jenisemr
                 OR LOWER(REPLACE(caption, ' ', '')) = LOWER(REPLACE(ep.jenisemr, ' ', ''))
                 OR url_form = ep.jenisemr
              )
            ORDER BY id
            LIMIT 1
        ) e ON true
        WHERE ep.noregistrasifk = :norec_pd
          AND (ep.statusenabled IS NULL
               OR ep.statusenabled::text IN ('1','t','true',''))
          AND (ep.kdprofile IS NULL OR ep.kdprofile = :kdprofile)
        ORDER BY ep.tglemr DESC NULLS LAST
    ";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':norec_pd'  => $norec_pd,
            ':kdprofile' => $kdProfile,
        ]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        // Query lebih sederhana jika LATERAL/cast gagal
        $sql2 = "
            SELECT
                ep.norec AS emrpasienfk,
                ep.jenisemr,
                ep.tglemr AS last_update,
                ep.namaruangan AS ruangan,
                ep.noemr,
                ep.norec_apd,
                ep.noregistrasi,
                ep.nocmfk,
                COALESCE(pg.namalengkap, '-') AS author
            FROM emrpasien_t ep
            LEFT JOIN pegawai_m pg ON pg.id = ep.pegawaifk
            WHERE ep.noregistrasifk = :norec_pd
              AND (ep.statusenabled IS NULL OR ep.statusenabled::text IN ('1','t','true',''))
            ORDER BY ep.tglemr DESC NULLS LAST
        ";
        $stmt = $pdo->prepare($sql2);
        $stmt->execute([':norec_pd' => $norec_pd]);
        return $stmt->fetchAll();
    }
}

/**
 * Normalisasi item EMR ke struktur seragam (mirip LIST_EMR Vue)
 */
function normalizeEmrItem(array $item) {
    $table = $item['table']
        ?? $item['collection']
        ?? $item['collection_master']
        ?? $item['jenisemr']
        ?? '';
    $nama = $item['namaemr']
        ?? $item['caption']
        ?? $item['caption_master']
        ?? humanizeEmrName($table ?: ($item['jenisemr'] ?? 'EMR'));
    $urlForm = $item['url_form']
        ?? $item['url_form_master']
        ?? '';
    // Jika url_form kosong, turunkan dari collection (CamelCase -> kebab)
    if ($urlForm === '' && $table !== '') {
        $urlForm = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $table));
        $urlForm = str_replace('_', '-', $urlForm);
    }
    $icon = $item['icon'] ?? $item['icon_master'] ?? 'fas fa-laptop-medical';
    if (empty($icon)) $icon = 'fas fa-laptop-medical';

    return [
        'emrpasienfk' => $item['emrpasienfk'] ?? $item['norec'] ?? '',
        'namaemr'     => $nama,
        'table'       => $table,
        'url_form'    => $urlForm,
        'last_update' => $item['last_update'] ?? $item['tglemr'] ?? null,
        'ruangan'     => $item['ruangan'] ?? $item['namaruangan'] ?? '',
        'author'      => $item['author'] ?? '-',
        'icon'        => $icon,
        'noemr'       => $item['noemr'] ?? '',
    ];
}

// ============================================================
// AMBIL DATA EMR
// ============================================================
$rawEmr = fetchEmrFromApi($baseUrl, $norec_pd, $nocmfk, $token);
$source = 'api';
if (empty($rawEmr)) {
    $rawEmr = fetchEmrFromPostgres($pdo, $norec_pd, $kdProfile);
    $source = 'postgres';
}

$listEmr = [];
foreach ($rawEmr as $row) {
    if (!is_array($row)) {
        // stdClass dari json
        $row = (array)$row;
    }
    $norm = normalizeEmrItem($row);
    // Skip vital sign (sama filter backend)
    if (in_array(strtolower((string)$norm['table']), ['vitalsign', 'vital_sign'], true)) {
        continue;
    }
    if ($norm['emrpasienfk'] === '' && $norm['table'] === '') {
        continue;
    }
    $listEmr[] = $norm;
}

// Filter pencarian (client-side juga ada, server-side bila ?q=)
if ($qSearch !== '') {
    $qLower = mb_strtolower($qSearch);
    $listEmr = array_values(array_filter($listEmr, function ($it) use ($qLower) {
        return (strpos(mb_strtolower($it['namaemr']), $qLower) !== false)
            || (strpos(mb_strtolower((string)$it['author']), $qLower) !== false)
            || (strpos(mb_strtolower((string)$it['ruangan']), $qLower) !== false)
            || (strpos(mb_strtolower((string)$it['table']), $qLower) !== false);
    }));
}

// Ambil norec terbaru per collection untuk tombol cetak cepat
$latestByCollection = [];
foreach ($listEmr as $it) {
    $key = $it['table'] ?: $it['namaemr'];
    if ($key === '') continue;
    if (!isset($latestByCollection[$key])) {
        $latestByCollection[$key] = $it;
    }
}
// Juga pastikan SPRI dari parameter emr_surat_fk
if ($emr_surat_fk !== '' && !isset($latestByCollection['SuratPermintaanDirawat'])) {
    $latestByCollection['SuratPermintaanDirawat'] = [
        'emrpasienfk' => $emr_surat_fk,
        'table'       => 'SuratPermintaanDirawat',
        'namaemr'     => 'Surat Permintaan Dirawat',
    ];
}

$noregForLink = $noregistrasi;
if ($noregForLink === '' && !empty($listEmr[0]['noemr'])) {
    // fallback kosong — biarkan
}
?>
<style>
.emr-card-wrap { max-height: 420px; overflow-y: auto; }
.emr-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0.5rem;
    border-bottom: 1px solid #e5e7eb;
}
.emr-item:last-child { border-bottom: none; }
.emr-item:hover { background: #f9fafb; }
.emr-icon-box {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 1rem;
}
.emr-meta { flex: 1; min-width: 0; }
.emr-meta a.emr-title {
    font-weight: 600;
    color: #111827;
    text-decoration: none;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.emr-meta a.emr-title:hover { color: #059669; }
.emr-meta .emr-date { font-size: 0.75rem; color: #6b7280; display: block; margin: 2px 0 4px; }
.emr-tag {
    display: inline-block;
    font-size: 0.65rem;
    padding: 0.1rem 0.45rem;
    border-radius: 9999px;
    margin-right: 0.25rem;
    margin-top: 0.15rem;
    font-weight: 600;
}
.emr-tag-ruangan { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.emr-tag-author  { background: #f3f4f6; color: #374151; }
.emr-actions { display: flex; flex-direction: column; gap: 0.25rem; flex-shrink: 0; }
.emr-actions a, .emr-actions button {
    font-size: 0.7rem;
    padding: 0.2rem 0.55rem;
    border-radius: 0.35rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
}
.emr-btn-lihat { background: #dbeafe; color: #1e40af; }
.emr-btn-lihat:hover { background: #bfdbfe; }
.emr-btn-cetak { background: #fef3c7; color: #92400e; }
.emr-btn-cetak:hover { background: #fde68a; }
.emr-search {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 9999px;
    padding: 0.4rem 0.9rem;
    font-size: 0.8rem;
    outline: none;
}
.emr-search:focus { border-color: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,.2); }
.emr-quick-print { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.emr-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.emr-badge-count {
    background: #10b981;
    color: #fff;
    font-size: 0.65rem;
    padding: 0.1rem 0.45rem;
    border-radius: 9999px;
    font-weight: 700;
}
</style>

<div class="space-y-4">
    <!-- Header card EMR (setara updates-header "EMR" di t-emr-detail.vue) -->
    <div>
        <div class="emr-section-title">
            <i class="fas fa-notes-medical text-emerald-600"></i>
            EMR
            <span class="emr-badge-count"><?= count($listEmr) ?></span>
        </div>
        <input
            type="text"
            id="emrSearchInput"
            class="emr-search"
            placeholder="Cari EMR..."
            value="<?= htmlspecialchars($qSearch) ?>"
            autocomplete="off"
        >
    </div>

    <!-- Daftar card EMR -->
    <?php if (empty($listEmr)): ?>
        <div class="text-center py-10">
            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
            <h3 class="text-base font-semibold text-gray-600">Belum ada data EMR</h3>
            <p class="text-gray-400 text-xs mt-1">Pasien ini belum memiliki form EMR tersimpan.</p>
        </div>
    <?php else: ?>
        <div class="emr-card-wrap border rounded-lg bg-white" id="emrListContainer">
            <?php foreach ($listEmr as $idx => $item):
                $colorKey = $listColor[$idx % count($listColor)];
                $hex = $colorHex[$colorKey];
                $emrFk = $item['emrpasienfk'];
                $table = $item['table'] ?: 'SuratPermintaanDirawat';
                $cetakUrl = buildCetakUrl($baseUrl, $table, $emrFk, $noregForLink, $userCetak, $kdProfile, $token);
                $editUrl  = buildEditUrl(
                    $baseUrl,
                    $item['url_form'],
                    $nocmfk ?: ($item['nocmfk'] ?? ''),
                    $norec_pd,
                    $norec_apd ?: ($item['norec_apd'] ?? ''),
                    $emrFk
                );
                $searchHay = mb_strtolower(
                    $item['namaemr'] . ' ' . $item['author'] . ' ' . $item['ruangan'] . ' ' . $item['table']
                );
            ?>
            <div class="emr-item" data-search="<?= htmlspecialchars($searchHay) ?>">
                <div class="emr-icon-box" style="background:<?= $hex['bg'] ?>;color:<?= $hex['fg'] ?>">
                    <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                </div>
                <div class="emr-meta">
                    <a class="emr-title" href="<?= htmlspecialchars($editUrl) ?>" target="_blank" title="Lihat / ubah EMR">
                        <?= htmlspecialchars($item['namaemr']) ?>
                    </a>
                    <span class="emr-date">
                        <i class="far fa-clock mr-1"></i><?= formatDateIndoSimple($item['last_update']) ?>
                        <?php if (!empty($item['noemr'])): ?>
                            · <span class="text-gray-400"><?= htmlspecialchars($item['noemr']) ?></span>
                        <?php endif; ?>
                    </span>
                    <?php if (!empty($item['ruangan'])): ?>
                        <span class="emr-tag emr-tag-ruangan"><?= htmlspecialchars($item['ruangan']) ?></span>
                    <?php endif; ?>
                    <span class="emr-tag emr-tag-author"><?= htmlspecialchars($item['author'] ?: '-') ?></span>
                </div>
                <div class="emr-actions">
                    <a class="emr-btn-lihat" href="<?= htmlspecialchars($editUrl) ?>" target="_blank" title="Lihat atau ubah data EMR">
                        <i class="fas fa-eye"></i> Lihat
                    </a>
                    <a class="emr-btn-cetak" href="<?= htmlspecialchars($cetakUrl) ?>" target="_blank" title="Cetak data EMR">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Tombol cetak cepat untuk form umum (SPRI / Rujukan / Resume) -->
    <?php
    $quickShown = false;
    $quickHtml = '';
    foreach ($quickPrintMap as $coll => $meta) {
        $fk = null;
        if (isset($latestByCollection[$coll])) {
            $fk = $latestByCollection[$coll]['emrpasienfk'];
        } elseif ($coll === 'SuratPermintaanDirawat' && $emr_surat_fk !== '') {
            $fk = $emr_surat_fk;
        }
        // Cari case-insensitive
        if (!$fk) {
            foreach ($latestByCollection as $k => $v) {
                if (strcasecmp($k, $coll) === 0) {
                    $fk = $v['emrpasienfk'];
                    break;
                }
            }
        }
        if (!$fk) continue;
        $quickShown = true;
        $url = buildCetakUrl($baseUrl, $coll, $fk, $noregForLink, $userCetak, $kdProfile, $token);
        $quickHtml .= '<a href="' . htmlspecialchars($url) . '" target="_blank" '
            . 'class="inline-flex items-center gap-1 px-3 py-1.5 text-white rounded-lg text-xs font-semibold transition '
            . $meta['color'] . '">'
            . '<i class="' . $meta['icon'] . '"></i> ' . htmlspecialchars($meta['label'])
            . '</a>';
    }
    if ($quickShown):
    ?>
    <div class="pt-2 border-t">
        <div class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Cetak Cepat</div>
        <div class="emr-quick-print">
            <?= $quickHtml ?>
        </div>
    </div>
    <?php endif; ?>

    <p class="text-[10px] text-gray-400 text-right">sumber: <?= htmlspecialchars($source) ?></p>
</div>

<script>
(function () {
    var input = document.getElementById('emrSearchInput');
    var list  = document.getElementById('emrListContainer');
    if (!input || !list) return;
    input.addEventListener('input', function () {
        var q = (input.value || '').toLowerCase().trim();
        var items = list.querySelectorAll('.emr-item');
        items.forEach(function (el) {
            var hay = el.getAttribute('data-search') || '';
            el.style.display = (!q || hay.indexOf(q) !== -1) ? '' : 'none';
        });
    });
})();
</script>