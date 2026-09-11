<?php
// ajax_radiologi_detail.php
// Hanya mengembalikan konten utama (daftar order) dalam format HTML

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

// Query yang sama dari detail_radiologi.php
$sql = "
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
        pp.norec AS norec_pp,
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
        'fas fa-radiation' AS icon
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

$stmt = $pdo->prepare($sql);
$stmt->execute([':norec_pd' => $norec_pd]);
$rawData = $stmt->fetchAll();

// Group per order (sama seperti sebelumnya)
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
            'dokterbaca' => $row['dokter_baca'] ?? '-',
            'status' => $row['status_label'],
            'color_status' => $row['status_color'],
            'icon' => $row['icon'],
            'noregistrasi' => $row['noregistrasi'],
            'objectruangantujuanfk' => $row['objectruangantujuanfk'],
            'details' => [],
            'expertise' => $row['expertise'],
            'norec_exper' => $row['norec_exper'],
            'norec_pp' => $row['norec_pp'],
        ];
    }
    if (!empty($row['namaproduk'])) {
        $orders[$key]['details'][] = [
            'namaproduk' => $row['namaproduk'],
            'produk_id' => $row['produk_id'],
            'norec_pp' => $row['norec_pp'],
            'norec_exper' => $row['norec_exper'],
        ];
    }
}

// Outputkan konten yang akan dimasukkan ke modal
if (count($orders) == 0): ?>
    <div class="text-center py-12">
        <h3 class="text-lg font-semibold text-gray-600 mt-4">Belum ada order radiologi</h3>
        <p class="text-gray-400">Pasien ini belum memiliki pemeriksaan radiologi.</p>
    </div>
<?php else: ?>
    <div class="space-y-3">
        <?php foreach ($orders as $order): ?>
            <div class="border-l-4 border-gray-200 pl-4 mb-4 bg-white rounded-lg shadow-sm p-4">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div class="flex-1 min-w-[200px]">
                        <div class="flex items-center gap-2 flex-wrap">
                            <i class="fas fa-radiation text-indigo-500"></i>
                            <span class="font-bold text-gray-800">Order #<?= htmlspecialchars($order['noorder']) ?></span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                                <?= $order['status'] == 'selesai' ? 'bg-green-100 text-green-800' : 
                                   ($order['status'] == 'verifikasi' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <span class="text-xs text-gray-400"><i class="far fa-calendar-alt mr-1"></i><?= $order['tglorder'] ?></span>
                        </div>
                        <?php if (!empty($order['details'])): ?>
                            <ul class="mt-1 list-none pl-0">
                                <?php foreach ($order['details'] as $detail): ?>
                                    <li class="flex items-center gap-1 text-sm py-0.5">
                                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                                        <?= htmlspecialchars($detail['namaproduk']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-wrap items-center gap-1 mt-2 sm:mt-0">
                        <?php if (!empty($order['expertise'])): ?>
                            <button onclick="showExpertise('<?= addslashes($order['expertise']) ?>')" class="btn-action btn-hasil">
                                <i class="fas fa-file-text"></i> Hasil
                            </button>
                        <?php else: ?>
                            <button class="btn-action btn-hasil opacity-50 cursor-not-allowed" disabled>Hasil</button>
                        <?php endif; ?>

                        <?php if (!empty($order['norec_exper'])): 
                            $userName = !empty($order['dokterbaca']) ? $order['dokterbaca'] : $order['dokter'];
                            $cetakUrl = "http://192.168.22.81/service/radiologi/cetak-ekspertise-manual?" . http_build_query([
                                'echo' => 'true',
                                'norec' => $order['norec_exper'],
                                'user' => $userName,
                                'kdprofile' => '1',
                                'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwYXNhIiwic2Vzc2lvbklkIjoiN2FlODRkMWQtODE0Ny00Yzg2LTk3YmYtMjczZDQ4ZjhlNjZlIiwiZXhwIjoxNzgyMjc0NTk1fQ.s1y3_kHquIMFXLUrySNuyXWQarceI6VhAqUveszO9uhpnGoT_peADF4hdNAiZxhN7uycLVvuicgk_6XgkY3WOQ.MQ==',
                            ]);
                        ?>
                            <a href="<?= $cetakUrl ?>" target="_blank" class="btn-action btn-cetak">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                        <?php else: ?>
                            <button class="btn-action btn-cetak opacity-50 cursor-not-allowed" disabled>Cetak</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif;