<?php
// ============================================================
// AJAX BUNDLE DETAIL
// ============================================================
$host = "192.168.22.81";
$port = "5792";
$dbname = "rsud_malangbong";
$user = "postgres";
$password = "Tr4nsm3d!c MaRe#T3aM";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

$noregistrasi = $_GET['noregistrasi'] ?? '';
$nama = $_GET['nama'] ?? $noregistrasi;

if (empty($noregistrasi)) {
    echo '<p class="text-red-500">No Registrasi tidak ditemukan.</p>';
    exit;
}

// Ambil data bundle (HAPUS statusenabled)
$sql = "SELECT norec, filename, data, urut 
        FROM bundleklaim_t 
        WHERE noregistrasi = :noregistrasi 
        ORDER BY urut ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':noregistrasi' => $noregistrasi]);
$bundles = $stmt->fetchAll();

if (empty($bundles)) {
    echo '<p class="text-center text-gray-500 py-4">Tidak ada file PDF untuk pasien ini.</p>';
    echo '<div class="text-center mt-4">';
    echo '<a href="?action=collect_klaim&noregistrasi=' . urlencode($noregistrasi) . '" target="_blank" class="btn-collect" style="background-color:#2563eb; color:white; padding:0.5rem 1rem; border-radius:0.25rem; text-decoration:none;">';
    echo '<i class="fas fa-download"></i> Collect / Cetak Sekarang';
    echo '</a>';
    echo '</div>';
    exit;
}

// Tampilkan daftar bundle
echo '<div class="overflow-x-auto">';
echo '<table class="min-w-full divide-y divide-gray-200">';
echo '<thead class="bg-gray-50">';
echo '<tr>';
echo '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>';
echo '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama File</th>';
echo '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>';
echo '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody class="bg-white divide-y divide-gray-200">';

$no = 1;
foreach ($bundles as $bundle) {
    $filename = htmlspecialchars($bundle['filename']);
    $norec = htmlspecialchars($bundle['norec']);
    $size = strlen(base64_decode($bundle['data']));
    $sizeFormatted = number_format($size / 1024, 0) . ' KB';
    
    echo '<tr>';
    echo '<td class="px-4 py-2 text-sm text-gray-900">' . $no++ . '</td>';
    echo '<td class="px-4 py-2 text-sm text-gray-900">' . $filename . '</td>';
    echo '<td class="px-4 py-2 text-sm text-gray-900">' . $sizeFormatted . '</td>';
    echo '<td class="px-4 py-2 text-sm">';
    echo '<a href="?action=download_bundle&norec=' . $norec . '" class="btn-download-pdf" style="background-color:#2563eb; color:white; padding:0.25rem 0.75rem; border-radius:0.25rem; font-size:0.75rem; text-decoration:none; display:inline-block;">';
    echo '<i class="fas fa-download"></i> Download';
    echo '</a>';
    echo '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';
echo '<div class="text-center mt-4">';
echo '<a href="?action=collect_klaim&noregistrasi=' . urlencode($noregistrasi) . '" class="btn-collect" style="background-color:#2563eb; color:white; padding:0.5rem 1rem; border-radius:0.25rem; text-decoration:none;">';
echo '<i class="fas fa-sync"></i> Collect Ulang / Cetak';
echo '</a>';
echo '</div>';
?>