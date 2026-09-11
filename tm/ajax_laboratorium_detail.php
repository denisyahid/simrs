<?php
// ajax_laboratorium_detail.php
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

$norec_pd = $_GET['norec_pd'] ?? '';
$noregistrasi = $_GET['noregistrasi'] ?? '';
if (empty($norec_pd) || empty($noregistrasi)) {
    http_response_code(400);
    echo '<p class="text-red-500">Parameter tidak lengkap</p>';
    exit;
}

// Query yang sama dari detail_laboratorium.php
$sql = "
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
        pr.id AS produk_id,
        CASE 
            WHEN so.statusorder = 1 THEN 'verifikasi'
            WHEN so.statusorder = 2 THEN 'selesai'
            ELSE 'pending'
        END AS status_label,
        CASE 
            WHEN so.statusorder = 1 THEN 'danger'
            WHEN so.statusorder = 2 THEN 'success'
            ELSE 'warning'
        END AS status_color,
        'fas fa-flask' AS icon
    FROM strukorder_t so
    JOIN ruangan_m ru ON ru.id = so.objectruangantujuanfk
    LEFT JOIN ruangan_m ruasal ON ruasal.id = so.objectruanganfk
    LEFT JOIN pegawai_m pg_order ON pg_order.id = so.objectpegawaiorderfk
    LEFT JOIN pelayananpasien_t pp ON pp.strukorderfk = so.norec AND pp.statusenabled = true
    LEFT JOIN produk_m pr ON pr.id = pp.produkfk
    LEFT JOIN antrianpasiendiperiksa_t apd ON apd.noregistrasifk = so.noregistrasifk AND apd.statusenabled = true
    WHERE so.noregistrasifk = :norec_pd
      AND so.keteranganorder = 'Order Laboratorium'
      AND so.statusenabled = true
    ORDER BY so.tglorder DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':norec_pd' => $norec_pd]);
$rawData = $stmt->fetchAll();

// Group per order
$orders = [];
foreach ($rawData as $row) {
    $key = $row['norec_so'];
    if (!isset($orders[$key])) {
        $orders[$key] = [
            'norec_so' => $row['norec_so'],
            'noorder' => $row['noorder'],
            'tglorder' => date('d-m-Y H:i', strtotime($row['tglorder'])),
            'ruanganasal' => $row['ruangan_asal'] ?? '-',
            'ruangantujuan' => $row['ruangan_tujuan'],
            'dokter' => $row['dokter_order'] ?? '-',
            'status' => $row['status_label'],
            'color_status' => $row['status_color'],
            'icon' => $row['icon'],
            'noregistrasi' => $row['noregistrasi'],
            'objectruangantujuanfk' => $row['objectruangantujuanfk'],
            'norec_apd' => $row['norec_apd'] ?? '',
            'details' => [],
            'product_ids' => [],
        ];
    }
if (!empty($row['namaproduk'])) {
    // Cegah duplikasi produk dalam satu order
    if (!in_array($row['produk_id'], $orders[$key]['product_ids'])) {
        $orders[$key]['details'][] = [
            'namaproduk' => $row['namaproduk'],
            'produk_id' => $row['produk_id'],
        ];
        $orders[$key]['product_ids'][] = $row['produk_id'];
    }
}
}

// Outputkan konten yang akan dimasukkan ke modal
if (count($orders) == 0): ?>
    <div class="text-center py-12">
        <h3 class="text-lg font-semibold text-gray-600 mt-4">Belum ada order laboratorium</h3>
        <p class="text-gray-400">Pasien ini belum memiliki pemeriksaan laboratorium.</p>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($orders as $order): ?>
            <div class="border-l-4 border-gray-200 pl-4 bg-white rounded-lg shadow-sm p-4">
                <div class="flex flex-wrap justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <i class="fas fa-flask text-blue-500"></i>
                            <span class="font-bold text-gray-800">Order #<?= htmlspecialchars($order['noorder']) ?></span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                                <?= $order['status'] == 'selesai' ? 'bg-green-100 text-green-800' : 
                                   ($order['status'] == 'verifikasi' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <span class="text-xs text-gray-400"><i class="far fa-calendar-alt mr-1"></i><?= $order['tglorder'] ?></span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 text-sm">
                            <table class="text-xs">
                                <tr><td>No Registrasi</td><td>:</td><td class="font-semibold"><?= htmlspecialchars($order['noregistrasi']) ?></td></tr>
                                <tr><td>Ruangan Asal</td><td>:</td><td class="font-semibold"><?= htmlspecialchars($order['ruanganasal']) ?></td></tr>
                                <tr><td>Dokter Order</td><td>:</td><td class="font-semibold"><?= htmlspecialchars($order['dokter']) ?></td></tr>
                            </table>
                            <div>
                                <span class="font-semibold">Produk:</span>
                                <ul class="list-disc list-inside text-gray-700">
                                    <?php foreach ($order['details'] as $detail): ?>
                                        <li><?= htmlspecialchars($detail['namaproduk']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1 mt-2 sm:mt-0">
                        <?php if (!empty($order['norec_apd']) && !empty($order['product_ids'])): 
                            $cetakParams = http_build_query([
                                'noregistrasi' => $order['noregistrasi'],
                                'norec_apd' => $order['norec_apd'],
                                'product' => implode(',', array_unique($order['product_ids'])),
                                'norec_pp' => '',
                                'norec_so' => $order['norec_so'],
                                'user' => 'Pasa Pirdaos, A.Md.A.K',
                                'kdprofile' => '1',
                                'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwYXNhIiwic2Vzc2lvbklkIjoiN2FlODRkMWQtODE0Ny00Yzg2LTk3YmYtMjczZDQ4ZjhlNjZlIiwiZXhwIjoxNzgyMjc0NTk1fQ.s1y3_kHquIMFXLUrySNuyXWQarceI6VhAqUveszO9uhpnGoT_peADF4hdNAiZxhN7uycLVvuicgk_6XgkY3WOQ.MQ=='
                            ]);
                            $cetakUrl = 'https://192.168.22.81/service/laboratorium/cetakan-hasil-lab-manual?' . $cetakParams;
                        ?>
                            <a href="<?= $cetakUrl ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-bold hover:bg-yellow-600">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-300 text-gray-500 rounded-full text-xs font-bold cursor-not-allowed">
                                <i class="fas fa-print"></i> Cetak
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif;