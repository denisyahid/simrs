<?php
// ajax_emr_detail.php
$host = "192.168.22.81";
$port = "5792";
$dbname = "rsud_malangbong";
$user = "postgres";
$password = "Tr4nsm3d!c MaRe#T3aM";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo '<p class="text-red-500">Gagal koneksi database</p>';
    exit;
}

// Parameter input
$norec_pd   = $_GET['norec_pd'] ?? '';
$noregistrasi = $_GET['noregistrasi'] ?? '';
$nocmfk     = $_GET['nocmfk'] ?? '';
$norec_apd  = $_GET['norec_apd'] ?? '';
$emr_surat_fk = $_GET['emr_surat_fk'] ?? '';

if (empty($norec_pd)) {
    http_response_code(400);
    echo '<p class="text-red-500">Parameter norec_pd diperlukan</p>';
    exit;
}

// Token & user untuk cetak
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwYXNhIiwic2Vzc2lvbklkIjoiN2FlODRkMWQtODE0Ny00Yzg2LTk3YmYtMjczZDQ4ZjhlNjZlIiwiZXhwIjoxNzgyMjc0NTk1fQ.s1y3_kHquIMFXLUrySNuyXWQarceI6VhAqUveszO9uhpnGoT_peADF4hdNAiZxhN7uycLVvuicgk_6XgkY3WOQ.MQ==';
$userCetak = 'Pasa Pirdaos, A.Md.A.K';
$kdProfile = 1;

// ============================================================
// FUNGSI REUSABLE UNTUK CEK KETERSEDIAAN URL
// ============================================================
function checkUrlAvailability($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);        // hanya header, ringan
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpCode >= 200 && $httpCode < 300);
}

// ============================================================
// 1. RUJUKAN MANUAL
// ============================================================
$rujukanNorec = null;
$rujukanUrl = null;
$showRujukan = false;

// Cari norec Rujukan Pasien dari emrpasien_t (tanpa JOIN)
$sqlRujukan = "
    SELECT ep.norec
    FROM emrpasien_t ep
    WHERE ep.noregistrasifk = :norec_pd
    ORDER BY ep.tglemr DESC
    
";
$stmt = $pdo->prepare($sqlRujukan);
$stmt->execute([':norec_pd' => $norec_pd]);
$row = $stmt->fetch();
$rujukanNorec = $row['norec'];

   $rujukanUrl = "https://192.168.22.81/service/emr/cetak/RujukanPasien?pdf=true"
        . "&emrpasienfk=" . urlencode($rujukanNorec)
        . "&user=" . urlencode($userCetak)
        . "&kdprofile=" . $kdProfile
        . "&token=" . urlencode($token);

    $showRujukan = checkUrlAvailability($rujukanUrl);

// ============================================================
// 2. SPRI (Surat Permintaan Dirawat)
// ============================================================
$showSPRI = false;
$spriUrl = null;

  $spriUrl = "https://192.168.22.81/service/emr/cetak/SuratPermintaanDirawat?pdf=true"
        . "&emrpasienfk=" . urlencode($rujukanNorec)
        . "&user=" . urlencode($userCetak)
        . "&kdprofile=" . $kdProfile
        . "&token=" . urlencode($token);

    $showSPRI = checkUrlAvailability($spriUrl);

    
$showResume = false;
$ResumeUrl = null;

  $ResumeUrl = "https://192.168.22.81/service/emr/cetak/resumeMedis?pdf=true"
        . "&emrpasienfk=" . urlencode($rujukanNorec)
        . "&user=" . urlencode($userCetak)
        . "&kdprofile=" . $kdProfile
        . "&token=" . urlencode($token);

    $showResume = checkUrlAvailability($ResumeUrl);
?>
<!-- https://192.168.22.81/service/emr/cetak/resumeMedis?pdf=true&emrpasienfk=a860bfb6-c186-4bd4-99cc-9b778f19b348&user=Pasa%20Pirdaos,%20A.Md.A.K&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwYXNhIiwic2Vzc2lvbklkIjoiNGY0YmYzYWMtNzAyMC00NTFjLTk1MTktYzQ3NDkxY2NiMTM1IiwiZXhwIjoxNzgwNDY1MzczfQ.5VlFCiP0o3VsnvAdPSFKNjXh5nueJpMHPhlBhuaMmh_WB4ANSFttgHC2YNhcllkIvOnIYKTL36ze3i_L-U6zBg.MQ== -->

<!-- TAMPILAN UTAMA -->
<div class="space-y-2">
    <!-- Tombol Rujukan Manual -->
    <?php if ($showRujukan): ?>
    <div class=" pt-3 mt-3">
        <a href="<?= htmlspecialchars($rujukanUrl) ?>" target="_blank"
           class="inline-flex items-center gap-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition">
            <i class="fas fa-print"></i> Cetak Rujukan Manual
        </a>
    </div>
   
    <?php endif; ?>

    <!-- Tombol SPRI -->
    <?php if ($showSPRI): ?>
    <div class=" pt-3 mt-3">
        <a href="<?= htmlspecialchars($spriUrl) ?>" target="_blank"
           class="inline-flex items-center gap-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition">
            <i class="fas fa-file-medical-alt"></i> Cetak SPRI
        </a>
    </div>
   
    <?php endif; ?>
    <?php if ($showResume): ?>
    <div class=" pt-3 mt-3">
        <a href="<?= htmlspecialchars($ResumeUrl) ?>" target="_blank"
           class="inline-flex items-center gap-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition">
            <i class="fas fa-file-medical-alt"></i> Cetak Resume
        </a>
    </div>
   
    <?php endif; ?>
</div>

buat agar jadi array ['RujukanPasien','SuratPermintaanDirawat','resumeMedis',] lalu  foreach  gunakan logika yang sama $spriUrl = null;

  $spriUrl = "https://192.168.22.81/service/emr/cetak/SuratPermintaanDirawat?pdf=true"
        . "&emrpasienfk=" . urlencode($rujukanNorec)
        . "&user=" . urlencode($userCetak)
        . "&kdprofile=" . $kdProfile
        . "&token=" . urlencode($token);

    $showSPRI = checkUrlAvailability($spriUrl); dan button dengan warna acak  


