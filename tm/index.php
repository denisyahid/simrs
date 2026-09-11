<?php
// ============================================================
// KONEKSI DATABASE
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

// ============================================================
// TOKEN & USER UNTUK LINK CETAK (digunakan juga di Billing & Rujukan)
// ============================================================
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwYXNhIiwic2Vzc2lvbklkIjoiN2FlODRkMWQtODE0Ny00Yzg2LTk3YmYtMjczZDQ4ZjhlNjZlIiwiZXhwIjoxNzgyMjc0NTk1fQ.s1y3_kHquIMFXLUrySNuyXWQarceI6VhAqUveszO9uhpnGoT_peADF4hdNAiZxhN7uycLVvuicgk_6XgkY3WOQ.MQ==';
$userCetak = 'Pasa Pirdaos, A.Md.A.K';
$kdProfile = 1;

// ============================================================
// COLLECT KLAIM (Generate PDF via Bridging)
// ============================================================
if (isset($_GET['action']) && $_GET['action'] === 'collect_klaim' && !empty($_GET['noregistrasi'])) {
    $noregistrasi = $_GET['noregistrasi'];

    // Ambil No SEP
    $sqlSep = "SELECT pa.nosep FROM pasiendaftar_t pd 
               JOIN pemakaianasuransi_t pa ON pa.noregistrasifk = pd.norec 
               WHERE pd.noregistrasi = :noregistrasi AND pd.statusenabled = true";
    $stmtSep = $pdo->prepare($sqlSep);
    $stmtSep->execute([':noregistrasi' => $noregistrasi]);
    $sepRow = $stmtSep->fetch();

    if (!$sepRow || empty($sepRow['nosep'])) {
        die('No SEP tidak ditemukan untuk pasien ini.');
    }
    $nosep = $sepRow['nosep'];

    // Panggil bridging untuk claim_print
    $payload = [
        'data' => [
            [
                'metadata' => ['method' => 'claim_print'],
                'data' => ['nomor_sep' => $nosep]
            ]
        ]
    ];
    $jsonPayload = json_encode($payload);

    $url = 'http://192.168.22.81/inacbgs/save';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($httpCode == 200 && !empty($response)) {
        $data = json_decode($response, true);
        if (isset($data['dataresponse'][0]['dataresponse']->data)) {
            $pdfBase64 = $data['dataresponse'][0]['dataresponse']->data;
            $pdfContent = base64_decode($pdfBase64);
            if ($pdfContent) {
                // Simpan ke bundleklaim_t
                $filename = 'klaim_' . $noregistrasi . '.pdf';
                $encrypt = base64_encode($pdfContent);
                $norec = uniqid('bundle_', true);

                $checkSql = "SELECT norec FROM bundleklaim_t WHERE noregistrasi = :noregistrasi AND filename = :filename";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->execute([':noregistrasi' => $noregistrasi, ':filename' => $filename]);
                $existing = $checkStmt->fetch();

                if ($existing) {
                    $updateSql = "UPDATE bundleklaim_t SET data = :data, urut = urut + 1 WHERE norec = :norec";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([':data' => $encrypt, ':norec' => $existing['norec']]);
                } else {
                    $insertSql = "INSERT INTO bundleklaim_t (norec, noregistrasi, filename, data, urut) VALUES (:norec, :noregistrasi, :filename, :data, 1)";
                    $insertStmt = $pdo->prepare($insertSql);
                    $insertStmt->execute([
                        ':norec' => $norec,
                        ':noregistrasi' => $noregistrasi,
                        ':filename' => $filename,
                        ':data' => $encrypt
                    ]);
                }

                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                echo $pdfContent;
                exit;
            }
        }
    }

    echo '<script>alert("Gagal mencetak klaim. HTTP: ' . $httpCode . ' - Error: ' . $curlError . '"); window.history.back();</script>';
    exit;
}

// ============================================================
// ENDPOINT DOWNLOAD BUNDLE
// ============================================================
if (isset($_GET['action']) && $_GET['action'] === 'download_bundle' && !empty($_GET['norec'])) {
    $norec = $_GET['norec'];

    $sql = "SELECT filename, data FROM bundleklaim_t WHERE norec = :norec";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':norec' => $norec]);
    $bundle = $stmt->fetch();

    if ($bundle && !empty($bundle['data'])) {
        $pdfContent = base64_decode($bundle['data']);
        if ($pdfContent) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $bundle['filename'] . '"');
            echo $pdfContent;
            exit;
        }
    }
    die('File tidak ditemukan.');
}

// ============================================================
// ENDPOINT DETAIL EMR (AJAX)
// ============================================================
if (isset($_GET['action']) && $_GET['action'] === 'get_emr' && !empty($_GET['noregistrasi'])) {
    $noregistrasi = $_GET['noregistrasi'];

    $sqlDetail = "
        SELECT
            bg.name AS kebangsaan,
            apd.norec AS norec_apd,
            pd.nocmfk,
            pd.nostruklastfk,
            ag.id AS agid,
            ag.agama,
            pas.tgllahir,
            kp.id AS kpid,
            kp.kelompokpasien AS jenisPasien,
            pas.objectstatusperkawinanfk,
            pas.namaayah,
            pas.namasuamiistri,
            pas.id AS pasid,
            pas.nobpjs,
            pas.noidentitas,
            pas.notelepon,
            pas.alamatrmh AS alamatlengkap,
            pas.nocm AS noCm,
            jkel.id AS jkelid,
            jkel.jeniskelamin,
            jkel.reportdisplay AS jenisKelamin,
            pd.noregistrasi AS noRegistrasi,
            pas.namapasien AS namaPasien,
            pd.tglregistrasi AS tglMasuk,
            pd.norec AS norec_pd,
            pd.tglpulang AS tglPulang,
            pas.notelepon,
            pas.nohp,
            pd.objectrekananfk AS rekananid,
            pas.noidentitas,
            kls2.id AS klsid2,
            kls2.namakelas AS kelasRawat,
            pg.id AS pgid,
            pg.namalengkap AS namadokter,
            rk.namarekanan AS namaPenjamin,
            rk.id AS objectrekananfk,
            klp.namaexternal AS kelompokpasien,
            pd.objectkelompokpasienlastfk,
            ru2.namaruangan AS lastRuangan,
            sp.nostruk,
            sp.norec AS strukfk,
            pd.statuspasien AS StatusPasien
        FROM pasiendaftar_t pd
        JOIN antrianpasiendiperiksa_t apd ON apd.noregistrasifk = pd.norec
        LEFT JOIN pelayananpasien_t pp ON pp.noregistrasifk = pd.norec
        LEFT JOIN strukpelayanan_t sp ON sp.norec = pp.strukfk
        JOIN pasien_m pas ON pas.id = pd.nocmfk
        LEFT JOIN agama_m ag ON ag.id = pas.objectagamafk
        LEFT JOIN jeniskelamin_m jkel ON jkel.id = pas.objectjeniskelaminfk
        LEFT JOIN kelompokpasien_m kp ON kp.id = pd.objectkelompokpasienlastfk
        LEFT JOIN kelas_m kls2 ON kls2.id = pd.objectkelasfk
        JOIN ruangan_m ru2 ON ru2.id = pd.objectruanganlastfk
        LEFT JOIN rekanan_m rk ON rk.id = pd.objectrekananfk
        LEFT JOIN kelompokpasien_m klp ON klp.id = pd.objectkelompokpasienlastfk
        LEFT JOIN kamar_m kamar ON kamar.id = apd.objectkamarfk
        LEFT JOIN pegawai_m pg ON pg.id = apd.objectpegawaifk
        LEFT JOIN kebangsaan_m bg ON bg.id = pas.objectkebangsaanfk
        WHERE pd.statusenabled = true
          AND apd.statusenabled = true
          AND pd.noregistrasi = :noregistrasi
    ";

    try {
        $stmtDetail = $pdo->prepare($sqlDetail);
        $stmtDetail->execute([':noregistrasi' => $noregistrasi]);
        $detail = $stmtDetail->fetch();

        header('Content-Type: application/json');
        if ($detail) {
            echo json_encode(['success' => true, 'data' => $detail]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// ============================================================
// FILTER PARAMETERS
// ============================================================
$search         = $_GET['search'] ?? '';
$filter_tipe    = $_GET['filter_tipe'] ?? 'pulang';
$tgl_awal       = $_GET['tgl_awal'] ?? '';
$tgl_akhir      = $_GET['tgl_akhir'] ?? '';
if (empty($tgl_awal) && empty($tgl_akhir)) {
    $tgl_awal = date('Y-m-d', strtotime('-1 day'));
    $tgl_akhir = date('Y-m-d', strtotime('-1 day'));
}
$filter_departemen = $_GET['filter_departemen'] ?? '16';
$filter_ruangan = $_GET['filter_ruangan'] ?? '';
$filter_penjamin = $_GET['filter_penjamin'] ?? '';
$filter_carabayar = $_GET['filter_carabayar'] ?? '';
$page           = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit          = 200;
$offset         = ($page - 1) * $limit;
$export_excel   = $_GET['export_excel'] ?? '';

// ============================================================
// AMBIL DATA UNTUK FILTER DROPDOWN
// ============================================================
$departemenList = $pdo->query("SELECT id, namadepartemen FROM departemen_m WHERE statusenabled = true ORDER BY namadepartemen")->fetchAll();
$ruanganList = $pdo->query("SELECT id, namaruangan FROM ruangan_m WHERE statusenabled = true ORDER BY namaruangan")->fetchAll();
$penjaminList = $pdo->query("SELECT id, namarekanan FROM rekanan_m WHERE statusenabled = true AND (namarekanan ILIKE '%BPJS%' OR namarekanan ILIKE '%UMUM%' OR namarekanan ILIKE '%LAPAD RUHAMA%') ORDER BY namarekanan")->fetchAll();
if (empty($penjaminList)) {
    $penjaminList = [['id' => 0, 'namarekanan' => 'BPJS'], ['id' => 0, 'namarekanan' => 'UMUM'], ['id' => 0, 'namarekanan' => 'LAPAD RUHAMA']];
}
$carabayarList = $pdo->query("SELECT id, carabayar FROM carabayar_m WHERE statusenabled = true ORDER BY carabayar")->fetchAll();

// ============================================================
// STATISTIK BOR/LOS/TOI
// ============================================================
$statistik = [];
$rumus_detail = [];
if (!empty($tgl_awal) && !empty($tgl_akhir)) {
    $start_date = new DateTime($tgl_awal);
    $end_date   = new DateTime($tgl_akhir);
    $interval   = $start_date->diff($end_date);
    $jumlah_hari = $interval->days + 1;
    $total_tt = 52;
    
    $sqlKeluar = "SELECT 
                        COUNT(*) AS total_pasien_keluar,
                        SUM(CASE WHEN pd.tglmeninggal IS NOT NULL THEN 1 ELSE 0 END) AS total_meninggal,
                        SUM(CASE WHEN pd.tglmeninggal IS NOT NULL 
                                 AND EXTRACT(EPOCH FROM (pd.tglmeninggal - pd.tglregistrasi)) / 3600 < 48 THEN 1 ELSE 0 END) AS meninggal_48,
                        SUM(CASE WHEN pd.tglmeninggal IS NOT NULL 
                                 AND EXTRACT(EPOCH FROM (pd.tglmeninggal - pd.tglregistrasi)) / 3600 >= 48 THEN 1 ELSE 0 END) AS meninggal_48plus,
                        SUM( FLOOR(EXTRACT(EPOCH FROM (pd.tglpulang - pd.tglregistrasi)) / 86400) + 1 ) AS total_hari_rawat,
                        SUM( FLOOR(EXTRACT(EPOCH FROM (pd.tglpulang - pd.tglregistrasi)) / 86400) ) AS total_lama_rawat
                    FROM pasiendaftar_t pd
                    JOIN ruangan_m ru ON ru.id = pd.objectruanganlastfk
                    WHERE pd.statusenabled = true
                      AND ru.objectdepartemenfk = 16
                      AND pd.tglpulang IS NOT NULL
                      AND DATE(pd.tglpulang) BETWEEN :tgl_awal AND :tgl_akhir";
    $stmtKeluar = $pdo->prepare($sqlKeluar);
    $stmtKeluar->execute([':tgl_awal' => $tgl_awal, ':tgl_akhir' => $tgl_akhir]);
    $keluarData = $stmtKeluar->fetch();
    
    $total_pasien_keluar = $keluarData['total_pasien_keluar'] ?: 0;
    $total_meninggal     = $keluarData['total_meninggal'] ?: 0;
    $meninggal_48        = $keluarData['meninggal_48'] ?: 0;
    $meninggal_48plus    = $keluarData['meninggal_48plus'] ?: 0;
    $total_hari_rawat    = $keluarData['total_hari_rawat'] ?: 0;
    $total_lama_rawat    = $keluarData['total_lama_rawat'] ?: 0;
    
    $avg_los = $total_pasien_keluar > 0 ? $total_hari_rawat / $total_pasien_keluar : 0;
    $bor = ($total_hari_rawat / ($total_tt * $jumlah_hari)) * 100;
    $gdr = $total_pasien_keluar > 0 ? ($total_meninggal / $total_pasien_keluar) * 100 : 0;
    $ndr = $total_pasien_keluar > 0 ? ($meninggal_48plus / $total_pasien_keluar) * 100 : 0;
    $toi = $total_pasien_keluar > 0 ? (($total_tt * $jumlah_hari) - $total_hari_rawat) / $total_pasien_keluar : 0;
    $toi = round($toi, 2);
    
    $statistik = [
        'bor' => round($bor, 2),
        'los' => round($avg_los, 2),
        'toi' => $toi,
        'ndr' => round($ndr, 2),
        'gdr' => round($gdr, 2),
        'total_lamadirawat' => round($total_lama_rawat, 2) . ' hari',
        'total_hariperawatan' => round($total_hari_rawat, 2) . ' hari',
        'total_kamar' => $total_tt,
        'periode_hari' => $jumlah_hari,
        'jumlah_pasien_keluar' => $total_pasien_keluar,
        'jumlah_pasien_meninggal' => $total_meninggal,
        'jumlah_meninggal_48' => $meninggal_48,
        'jumlah_meninggal_48plus' => $meninggal_48plus,
        'total_hari_rawat_numeric' => round($total_hari_rawat, 2),
        'avg_los_numeric' => round($avg_los, 2),
    ];
    
    $rumus_detail = [
        'bor' => "BOR = (Total Hari Rawat (dengan +1 per pasien) / (Jumlah Tempat Tidur × Periode Hari)) × 100% = ({$statistik['total_hari_rawat_numeric']} / ({$total_tt} × {$jumlah_hari})) × 100% = {$statistik['bor']}%",
        'los' => "LOS = Rata-rata lama rawat (dengan +1 per pasien) = {$statistik['los']} hari",
        'toi' => "TOI = (Total Hari Rawat - (LOS × Jumlah Pasien Keluar)) / Jumlah Tempat Tidur = ({$statistik['total_hari_rawat_numeric']} - ({$statistik['los']} × {$total_pasien_keluar})) / 52 = {$statistik['toi']} hari",
        'ndr' => "NDR = (Jumlah Pasien Meninggal ≥ 48 Jam / Jumlah Pasien Keluar) × 100% = ({$meninggal_48plus} / {$total_pasien_keluar}) × 100% = {$statistik['ndr']}%",
        'gdr' => "GDR = (Jumlah Pasien Meninggal / Jumlah Pasien Keluar) × 100% = ({$total_meninggal} / {$total_pasien_keluar}) × 100% = {$statistik['gdr']}%"
    ];
}

// ============================================================
// SUBQUERY DIAGNOSA & ANTRIAN
// ============================================================
$subDiagnosa = "
    SELECT DISTINCT ON (apd.noregistrasifk) 
        apd.noregistrasifk,
        dg.kddiagnosa || ' - ' || dg.namadiagnosa AS diagnosa
    FROM antrianpasiendiperiksa_t apd
    JOIN detaildiagnosapasien_t ddp ON ddp.noregistrasifk = apd.norec
    JOIN diagnosapasien_t dp ON dp.norec = ddp.objectdiagnosapasienfk
    JOIN diagnosa_m dg ON dg.id = ddp.objectdiagnosafk
    WHERE ddp.objectjenisdiagnosafk = 1
      AND apd.statusenabled = true
    ORDER BY apd.noregistrasifk, apd.tglregistrasi
";

$subAntrianPertama = "
    SELECT DISTINCT ON (apd.noregistrasifk)
        apd.noregistrasifk,
        apd.norec AS norec_apd,
        apd.noantrian,
        apd.objectpegawaifk AS dokter_id,
        apd.objectruanganfk AS ruangan_awal_id,
        apd.tglregistrasi AS tgl_periksa_awal
    FROM antrianpasiendiperiksa_t apd
    WHERE apd.statusenabled = true
    ORDER BY apd.noregistrasifk, apd.tglregistrasi ASC
";

// ============================================================
// QUERY UTAMA (ditambahkan subquery Rujukan Pasien)
// ============================================================
$sql = "
WITH diagnosa_cte AS ({$subDiagnosa}),
     antrian_cte AS ({$subAntrianPertama})
SELECT 
    pd.norec AS id_registrasi,
    pd.noregistrasi,
    pd.tglregistrasi AS tgl_masuk,
    pd.tglpulang,
    ps.namapasien,
    ps.noidentitas AS nik,
    ps.nocm,
    ps.tgllahir,
    ps.nohp,
    jk.jeniskelamin,
    kb.name AS bangsa,
    TRIM(
        COALESCE(alm.alamatlengkap, '') ||
        CASE WHEN dsk.namadesakelurahan IS NOT NULL AND dsk.namadesakelurahan != '' THEN ', ' || dsk.namadesakelurahan ELSE '' END ||
        CASE WHEN alm.kecamatan IS NOT NULL AND alm.kecamatan != '' THEN ', ' || alm.kecamatan ELSE '' END ||
        CASE WHEN kkb.namakotakabupaten IS NOT NULL AND kkb.namakotakabupaten != '' THEN ', ' || kkb.namakotakabupaten ELSE '' END
    ) AS alamat_lengkap,
    kkb.namakotakabupaten,
    alm.kecamatan,
    dsk.namadesakelurahan,
    ru_akhir.namaruangan AS ruangan_akhir,
    dep_akhir.namadepartemen AS departemen_akhir,
    antrian.noantrian,
    antrian.dokter_id,
    dokter.namalengkap AS nama_dokter,
    ru_awal.namaruangan AS ruangan_awal,
    dep_awal.namadepartemen AS departemen_awal,
    COALESCE(rk.namarekanan, 'UMUM') AS penjamin,
    pa.nosep,
    pd.totalbiaya AS total_tagihan,
    CASE WHEN EXISTS (
        SELECT 1 FROM pasiendaftar_t pd2 
        WHERE pd2.nocmfk = ps.id 
          AND pd2.tglregistrasi < pd.tglregistrasi 
          AND pd2.statusenabled = true
    ) THEN 'LAMA' ELSE 'BARU' END AS status_kunjungan,
    cb.carabayar AS cara_bayar,
    ps.id AS nocmfk,
    pd.norec AS norec_pd,
    antrian.norec_apd AS norec_apd,
    (SELECT norec FROM emrpasien_t 
     WHERE noregistrasifk = pd.norec 
       AND jenisemr = 'SuratPermintaanDirawat' 
       AND statusenabled = true 
     ORDER BY tglemr DESC LIMIT 1) AS emrpasienfk_surat,
    (SELECT norec FROM emrpasien_t 
     WHERE noregistrasifk = pd.norec 
      
       AND statusenabled = true 
     ORDER BY tglemr DESC LIMIT 1) AS emrpasienfk_rujukan,
    CASE WHEN EXISTS (
        SELECT 1 FROM strukorder_t so2 
        WHERE so2.noregistrasifk = pd.norec 
          AND so2.keteranganorder = 'Order Laboratorium' 
          AND so2.statusenabled = true
    ) THEN 1 ELSE 0 END AS has_laboratorium,
    CASE WHEN EXISTS (
        SELECT 1 FROM strukorder_t so2 
        WHERE so2.noregistrasifk = pd.norec 
          AND so2.keteranganorder = 'Order Radiologi' 
          AND so2.statusenabled = true
    ) THEN 1 ELSE 0 END AS has_radiologi
FROM pasiendaftar_t pd
JOIN pasien_m ps ON ps.id = pd.nocmfk
JOIN jeniskelamin_m jk ON jk.id = ps.objectjeniskelaminfk
LEFT JOIN kebangsaan_m kb ON kb.id = ps.objectkebangsaanfk
LEFT JOIN alamat_m alm ON alm.nocmfk = ps.id
LEFT JOIN desakelurahan_m dsk ON dsk.id = alm.objectdesakelurahanfk
LEFT JOIN kotakabupaten_m kkb ON kkb.id = alm.objectkotakabupatenfk
LEFT JOIN ruangan_m ru_akhir ON ru_akhir.id = pd.objectruanganlastfk
LEFT JOIN departemen_m dep_akhir ON dep_akhir.id = ru_akhir.objectdepartemenfk
LEFT JOIN antrian_cte antrian ON antrian.noregistrasifk = pd.norec
LEFT JOIN pegawai_m dokter ON dokter.id = antrian.dokter_id
LEFT JOIN ruangan_m ru_awal ON ru_awal.id = antrian.ruangan_awal_id
LEFT JOIN departemen_m dep_awal ON dep_awal.id = ru_awal.objectdepartemenfk
LEFT JOIN pemakaianasuransi_t pa ON pa.noregistrasifk = pd.norec AND pa.statusenabled = true
LEFT JOIN rekanan_m rk ON rk.id = pd.objectrekananfk
LEFT JOIN (
    SELECT DISTINCT ON (sb.nosbmfk) 
        sb.nosbmfk,
        cb.carabayar
    FROM strukbuktipenerimaancarabayar_t sb
    LEFT JOIN carabayar_m cb ON cb.id = sb.objectcarabayarfk
    WHERE sb.statusenabled = true
) cb ON cb.nosbmfk = pd.nostruklastfk
LEFT JOIN diagnosa_cte diag ON diag.noregistrasifk = pd.norec
WHERE pd.statusenabled = true
";

$params = [];

// Filter tanggal
if (empty($search)) {
    if (!empty($tgl_awal) && !empty($tgl_akhir)) {
        if ($filter_tipe == 'masuk') {
            $sql .= " AND DATE(pd.tglregistrasi) BETWEEN :tgl_awal AND :tgl_akhir";
        } else {
            $sql .= " AND DATE(pd.tglpulang) BETWEEN :tgl_awal AND :tgl_akhir";
        }
        $params[':tgl_awal'] = $tgl_awal;
        $params[':tgl_akhir'] = $tgl_akhir;
    } elseif (!empty($tgl_awal)) {
        if ($filter_tipe == 'masuk') {
            $sql .= " AND DATE(pd.tglregistrasi) >= :tgl_awal";
        } else {
            $sql .= " AND DATE(pd.tglpulang) >= :tgl_awal";
        }
        $params[':tgl_awal'] = $tgl_awal;
    } elseif (!empty($tgl_akhir)) {
        if ($filter_tipe == 'masuk') {
            $sql .= " AND DATE(pd.tglregistrasi) <= :tgl_akhir";
        } else {
            $sql .= " AND DATE(pd.tglpulang) <= :tgl_akhir";
        }
        $params[':tgl_akhir'] = $tgl_akhir;
    }
}

if (!empty($filter_departemen)) {
    $sql .= " AND dep_akhir.id = :departemen_id";
    $params[':departemen_id'] = $filter_departemen;
}
if (!empty($filter_ruangan)) {
    $sql .= " AND ru_akhir.id = :ruangan_id";
    $params[':ruangan_id'] = $filter_ruangan;
}
if (!empty($filter_penjamin)) {
    if ($filter_penjamin == 'BPJS') {
        $sql .= " AND rk.namarekanan ILIKE '%BPJS%'";
    } elseif ($filter_penjamin == 'UMUM') {
        $sql .= " AND (rk.namarekanan IS NULL OR (rk.namarekanan NOT ILIKE '%BPJS%' AND rk.namarekanan NOT ILIKE '%LAPAD RUHAMA%'))";
    } elseif ($filter_penjamin == 'LAPAD RUHAMA') {
        $sql .= " AND rk.namarekanan ILIKE '%LAPAD RUHAMA%'";
    }
}
if (!empty($filter_carabayar)) {
    $sql .= " AND cb.carabayar ILIKE :carabayar";
    $params[':carabayar'] = '%' . $filter_carabayar . '%';
}
if (!empty($search)) {
    $sql .= " AND (ps.namapasien ILIKE :search OR ps.nocm ILIKE :search OR pd.noregistrasi ILIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

$countSql = "SELECT COUNT(*) as total FROM ({$sql}) as subquery";
$stmtCount = $pdo->prepare($countSql);
foreach ($params as $key => $value) {
    $stmtCount->bindValue($key, $value);
}
$stmtCount->execute();
$total_records = $stmtCount->fetchColumn();
$total_pages = ceil($total_records / $limit);

$sql .= " ORDER BY pd.tglpulang::date DESC, LOWER(ps.namapasien) ASC LIMIT :limit OFFSET :offset";
$params[':limit'] = $limit;
$params[':offset'] = $offset;

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$data = $stmt->fetchAll();

// Hitung lama dirawat
foreach ($data as &$row) {
    $lamaDirawat = '-';
    $hariPerawatan = 0;
    if (!empty($row['tgl_masuk']) && !empty($row['tglpulang'])) {
        $hari = floor((strtotime($row['tglpulang']) - strtotime($row['tgl_masuk'])) / 86400);
        $jam = floor(((strtotime($row['tglpulang']) - strtotime($row['tgl_masuk'])) % 86400) / 3600);
        $lamaDirawat = $hari . ' hari ' . $jam . ' jam';
        $hariPerawatan = $hari;
    }
    $row['lama_dirawat'] = $lamaDirawat;
    $row['hari_perawatan'] = $hariPerawatan + 1;
}
unset($row);

function hitungUmur($tgl_lahir) {
    if (empty($tgl_lahir)) return '-';
    $birth = new DateTime($tgl_lahir);
    $now = new DateTime();
    $diff = $now->diff($birth);
    $umur = '';
    if ($diff->y > 0) $umur .= $diff->y . ' thn ';
    if ($diff->m > 0) $umur .= $diff->m . ' bln ';
    if ($diff->d > 0 && $diff->y == 0 && $diff->m == 0) $umur .= $diff->d . ' hr';
    return trim($umur);
}

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

if ($export_excel == 'true') {
    // (kode export excel tidak diubah)
    exit;
}

// ============================================================
// BUAT PARAMETER UNTUK RESET SEARCH
// ============================================================
$resetParams = $_GET;
unset($resetParams['search']);
unset($resetParams['page']);
$resetQuery = http_build_query($resetParams);
$resetUrl = '?' . $resetQuery;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="logo_only.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @media print {
            .print-hide { display: none !important; }
            .table-container { margin: 0 !important; padding: 0 !important; overflow: visible !important; width: 100% !important; }
            table { width: 100% !important; border-collapse: collapse !important; font-size: 10pt !important; }
            th, td { border: 1px solid #000 !important; padding: 4px !important; background-color: #fff !important; color: #000 !important; }
            th { background-color: #f0f0f0 !important; font-weight: bold !important; }
            tr { page-break-inside: avoid; }
        }
        @media screen {
            .table-container { overflow-x: auto; max-width: 100%; }
            table th, table td { padding: 0.25rem 0.5rem !important; font-size: 0.75rem !important; line-height: 1rem !important; }
        }
        .btn-laboratorium-modal {
                cursor: pointer;
            }
        .btn-detail {
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 2px;
        }
        .btn-detail:hover {
            background-color: #1d4ed8;
        }
        .btn-radiologi {
            background-color: #a079fc;
            color: white;
            font-weight: bold;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            text-decoration: none;
            display: inline-block;
            margin-right: 2px;
        }
        .btn-radiologi:hover {
            background-color: #7c3aed;
        }
        .btn-emr {
            background-color: #10b981;
            color: white;
            font-weight: bold;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            text-decoration: none;
            display: inline-block;
            margin-right: 2px;
        }
        .btn-emr:hover {
            background-color: #059669;
        }
        .btn-billing {
            background-color: #f59e0b;
            color: white;
            font-weight: bold;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            text-decoration: none;
            display: inline-block;
        }
        .btn-billing:hover {
            background-color: #d97706;
        }
        .btn-laboratorium {
            background-color: #0ea5e9;
            color: white;
            font-weight: bold;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            text-decoration: none;
            display: inline-block;
            margin-right: 2px;
        }
        .btn-laboratorium:hover {
            background-color: #0284c7;
        }
        .btn-cetak-surat {
            background-color: #dc2626;
            color: white;
            font-weight: bold;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            text-decoration: none;
            display: inline-block;
            margin-right: 2px;
        }
        .btn-cetak-surat:hover {
            background-color: #b91c1c;
        }

        .btn-action {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
}
.btn-hasil { background: #10b981; color: white; }
.btn-cetak { background: #f59e0b; color: white; }
.btn-hasil:disabled, .btn-cetak:disabled {
    background: #d1d5db;
    color: #6b7280;
    cursor: not-allowed;
    opacity: 0.5;
}
.btn-emr-modal {
    cursor: pointer;
}
.btn-bundle {
    background-color: #8b5cf6;
    color: white;
    font-weight: bold;
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    border: none;
    cursor: pointer;
    display: inline-block;
    margin-right: 2px;
}
.btn-bundle:hover {
    background-color: #7c3aed;
}
    </style>
</head>
<body class="bg-gray-100 p-4">
    <!-- Modal Radiologi (AJAX) -->
    <div id="radiologiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden print-hide">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center border-b pb-2 mb-3">
                <h3 class="text-lg font-semibold text-gray-800">
                    Detail Radiologi - <span id="radiologiNoreg"></span>
                </h3>
                <button id="closeRadiologiModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="radiologiContent" class="text-sm">
                <div class="flex justify-center items-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <span class="ml-2 text-gray-500">Memuat data...</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Laboratorium (AJAX) -->
    <div id="laboratoriumModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden print-hide">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center border-b pb-2 mb-3">
                <h3 class="text-lg font-semibold text-gray-800">
                    Detail Laboratorium - <span id="laboratoriumNoreg"></span>
                </h3>
                <button id="closeLaboratoriumModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="laboratoriumContent" class="text-sm">
                <div class="flex justify-center items-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <span class="ml-2 text-gray-500">Memuat data...</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal EMR (AJAX) -->
    <div id="emrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden print-hide">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center border-b pb-2 mb-3">
                <h3 class="text-lg font-semibold text-gray-800">
                    Detail EMR - <span id="emrNoreg"></span>
                </h3>
                <button id="closeEmrModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="emrContent" class="text-sm">
                <div class="flex justify-center items-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <span class="ml-2 text-gray-500">Memuat data...</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Bundle (AJAX) -->
<div id="bundleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden print-hide">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center border-b pb-2 mb-3">
            <h3 class="text-lg font-semibold text-gray-800">
                Daftar Bundle PDF - <span id="bundleNoreg"></span>
            </h3>
            <button id="closeBundleModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="bundleContent" class="text-sm">
            <div class="flex justify-center items-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                <span class="ml-2 text-gray-500">Memuat data...</span>
            </div>
        </div>
    </div>
</div>
<div class="max-w-full mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="px-4 py-3 flex justify-between items-center print-hide">
        <div>
            <h1 class="text-lg font-bold">Laporan Kunjungan Pasien</h1>
            <p class="text-xs text-gray-300">RSUD Malangbong</p>
        </div>
        <button id="toggleFilterBtn" class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-semibold flex items-center gap-2">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>

    <!-- Modal Filter -->
    <div id="filterModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden print-hide">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center border-b pb-2 mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Filter Data</h3>
                <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700">📅 Filter Berdasarkan</label>
                    <select name="filter_tipe" class="w-full border rounded px-2 py-1 text-xs">
                        <option value="masuk" <?= $filter_tipe=='masuk' ? 'selected' : '' ?>>Tanggal Masuk</option>
                        <option value="pulang" <?= $filter_tipe=='pulang' ? 'selected' : '' ?>>Tanggal Pulang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700">📅 Dari Tanggal</label>
                    <input type="date" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal) ?>" class="w-full border rounded px-2 py-1 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700">📅 Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir) ?>" class="w-full border rounded px-2 py-1 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700">🏥 Departemen</label>
                    <select name="filter_departemen" class="w-full border rounded px-2 py-1 text-xs">
                        <option value="">Semua</option>
                        <?php foreach ($departemenList as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= $filter_departemen==$d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['namadepartemen']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700">🏥 Ruangan</label>
                    <select name="filter_ruangan" class="w-full border rounded px-2 py-1 text-xs">
                        <option value="">Semua</option>
                        <?php foreach ($ruanganList as $r): ?>
                            <option value="<?= $r['id'] ?>" <?= $filter_ruangan==$r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['namaruangan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700">👥 Penjamin</label>
                    <select name="filter_penjamin" class="w-full border rounded px-2 py-1 text-xs">
                        <option value="">Semua</option>
                        <option value="BPJS" <?= $filter_penjamin=='BPJS' ? 'selected' : '' ?>>BPJS</option>
                        <option value="UMUM" <?= $filter_penjamin=='UMUM' ? 'selected' : '' ?>>UMUM</option>
                        <option value="ASURANSI" <?= $filter_penjamin=='ASURANSI' ? 'selected' : '' ?>>ASURANSI</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700">💳 Cara Bayar</label>
                    <select name="filter_carabayar" class="w-full border rounded px-2 py-1 text-xs">
                        <option value="">Semua</option>
                        <?php foreach ($carabayarList as $cb): ?>
                            <option value="<?= htmlspecialchars($cb['carabayar']) ?>" <?= $filter_carabayar==$cb['carabayar'] ? 'selected' : '' ?>><?= htmlspecialchars($cb['carabayar']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end justify-end gap-2 col-span-1 md:col-span-3">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-1 rounded text-sm font-semibold">Terapkan Filter</button>
                    <a href="?" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded text-sm font-semibold">Reset</a>
                    <a href="?<?= http_build_query(array_merge($_GET, ['export_excel'=>'true', 'page'=>1])) ?>" class="bg-green-500 hover:bg-green-700 text-white px-4 py-1 rounded text-sm font-semibold">📎 Export Excel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- SEARCH BAR & FILTER -->
    <div class="bg-white px-4 py-2 print-hide border-b border-gray-200">
        <form method="GET" class="flex flex-wrap items-center gap-3" id="filterForm">
            <?php
                $hiddenParams = $_GET;
                unset($hiddenParams['filter_departemen']);
                unset($hiddenParams['filter_penjamin']);
                unset($hiddenParams['search']);
                unset($hiddenParams['page']);
                foreach ($hiddenParams as $key => $value) {
                    if ($key !== '') {
                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                    }
                }
            ?>
            <div class="relative flex-1 min-w-[180px] max-w-md">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="🔍 Cari Nama / No.RM / No.Reg..." 
                       class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-1.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-green-500" 
                       autofocus>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg text-sm font-semibold flex-shrink-0">Cari</button>
            <?php if (!empty($search)): ?>
                <a href="<?= $resetUrl ?>" class="text-sm text-gray-600 hover:text-gray-800 flex-shrink-0">Reset</a>
            <?php endif; ?>

            <div class="hidden sm:block w-px h-8 bg-gray-300"></div>

            <!-- Filter Departemen -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-medium text-gray-700 whitespace-nowrap">Dept:</span>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="filter_departemen" value="" 
                           <?= ($filter_departemen == '' ? 'checked' : '') ?>
                           onchange="this.form.submit()" class="sr-only peer">
                    <span class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 hover:bg-gray-50 transition-colors whitespace-nowrap">
                        Semua
                    </span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="filter_departemen" value="16" 
                           <?= ($filter_departemen == '16' ? 'checked' : '') ?>
                           onchange="this.form.submit()" class="sr-only peer">
                    <span class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 hover:bg-gray-50 transition-colors whitespace-nowrap">
                        Rawat Inap
                    </span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="filter_departemen" value="18" 
                           <?= ($filter_departemen == '18' ? 'checked' : '') ?>
                           onchange="this.form.submit()" class="sr-only peer">
                    <span class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 hover:bg-gray-50 transition-colors whitespace-nowrap">
                        Rawat Jalan
                    </span>
                </label>
            </div>

            <div class="hidden sm:block w-px h-8 bg-gray-300"></div>

            <!-- Filter Penjamin -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-medium text-gray-700 whitespace-nowrap">Penjamin:</span>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="filter_penjamin" value="" 
                           <?= ($filter_penjamin == '' ? 'checked' : '') ?>
                           onchange="this.form.submit()" class="sr-only peer">
                    <span class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 hover:bg-gray-50 transition-colors whitespace-nowrap">
                        Semua
                    </span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="filter_penjamin" value="BPJS" 
                           <?= ($filter_penjamin == 'BPJS' ? 'checked' : '') ?>
                           onchange="this.form.submit()" class="sr-only peer">
                    <span class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 hover:bg-gray-50 transition-colors whitespace-nowrap">
                        BPJS
                    </span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="filter_penjamin" value="UMUM" 
                           <?= ($filter_penjamin == 'UMUM' ? 'checked' : '') ?>
                           onchange="this.form.submit()" class="sr-only peer">
                    <span class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 hover:bg-gray-50 transition-colors whitespace-nowrap">
                        UMUM
                    </span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="filter_penjamin" value="LAPAD RUHAMA" 
                           <?= ($filter_penjamin == 'LAPAD RUHAMA' ? 'checked' : '') ?>
                           onchange="this.form.submit()" class="sr-only peer">
                    <span class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 hover:bg-gray-50 transition-colors whitespace-nowrap">
                        ASURANSI
                    </span>
                </label>
            </div>
        </form>
    </div>

    <!-- Tabel Data Pasien -->
    <div class="table-container">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="text-xs px-1 py-1">No</th>
                    <th class="text-xs px-1 py-1">Nama Pasien</th>
                    <th class="text-xs px-1 py-1">No RM</th>
                    <th class="text-xs px-1 py-1 print-hide">Tgl Masuk</th>
                    <th class="text-xs px-1 py-1">Tgl Pulang</th>
                    <th class="text-xs px-1 py-1 print-hide">Dep.Akhir</th>
                    <th class="text-xs px-1 py-1">Ruang Akhir</th>
                    <th class="text-xs px-1 py-1">Cara Bayar</th>
                    <th class="text-xs px-1 py-1">No SEP</th>
                    <th class="text-xs px-1 py-1 print-hide">Radiologi</th>
                    <th class="text-xs px-1 py-1 print-hide">Laboratorium</th>
                    <th class="text-xs px-1 py-1 print-hide">Bill</th>
                    <th class="text-xs px-1 py-1 print-hide">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr
    class="hover:bg-gray-200 transition-all cursor-pointer patient-row"
    data-nosep="<?= htmlspecialchars($row['nosep']) ?>"><td colspan="14" class="text-center py-4 text-gray-500">❌ Tidak ada data</td></tr>
                <?php else: ?>
                    <?php $NO = 1; foreach ($data as $row): ?>
                        <?php 
                            $nocmfk = $row['nocmfk'] ?? '';
                            $norec_pd = $row['norec_pd'] ?? '';
                            $norec_apd = $row['norec_apd'] ?? '';
                            $linkEmr = "https://192.168.22.81/module/emr/profile-pasien/page-emr/surat-permintaan-dirawat?nocmfk=". urlencode($row['nocmfk'])."&norec_pasien_daftar=". urlencode($row['norec_pd'])." &norec_pd=". urlencode($row['norec_pd'])."&norec_apd=".urlencode($row['norec_apd']) ."&jenisobgyn&jenisinterna&jenistrauma&norec_emr=". urlencode($row['emrpasienfk_surat']) ."&edit=true";
                            $linkBilling = "/service/kasir/billing/report/rincian-biaya?noregistrasi=" . urlencode($row['noregistrasi']) . "&bangsa=" . urlencode($row['bangsa'] ?? 'WNI') . "&user=" . urlencode($userCetak) . "&kdprofile=" . $kdProfile . "&token=" . urlencode($token);
                            $hasRadiologi = $row['has_radiologi'] ?? 0;
                            // === Rujukan Manual ===
                            $emrpasienfk_rujukan = $row['emrpasienfk_rujukan'] ?? '';
                            if (!empty($emrpasienfk_rujukan)) {
                                $linkRujukan = "https://192.168.22.81/service/emr/cetak/RujukanPasien?pdf=true"
                                    . "&emrpasienfk=" . urlencode($emrpasienfk_rujukan)
                                    . "&user=" . urlencode($userCetak)
                                    . "&kdprofile=" . $kdProfile
                                    . "&token=" . urlencode($token);
                            }
                        ?>
                        <tr
    class="hover:bg-gray-200 transition-all patient-row cursor-pointer"
    data-nosep="<?= htmlspecialchars($row['nosep'] ?? '') ?>">
                            <th class="text-xs px-1 py-1"><?= $NO++ ?></th>
                            <td class="border text-xs px-1 py-0.5"><?= htmlspecialchars(substr($row['namapasien'],0,25)) ?></td>
                            <td class="border text-xs px-1 py-0.5"><?= htmlspecialchars($row['nocm']) ?></td>
                            <td class="border text-xs px-1 py-0.5 print-hide"><?= date('d-m-Y', strtotime($row['tgl_masuk'])) ?></td>
                            <td class="border text-xs px-1 py-0.5 font-bold"><?= $row['tglpulang'] ? date('d-m-Y', strtotime($row['tglpulang'])) : '-' ?></td>
                            <td class="border text-xs px-1 py-0.5 print-hide"><?= htmlspecialchars($row['departemen_akhir'] ?? '-') ?></td>
                            <td class="border text-xs px-1 py-0.5 font-bold"><?= htmlspecialchars($row['ruangan_akhir'] ?? '-') ?></td>
                            <td class="border text-xs px-1 py-0.5">
                                <?php
                                $penjamin = $row['penjamin'];
                                if ($penjamin == 'BPJS KESEHATAN') {
                                    echo '<span class="px-2 py-1 bg-blue-200 rounded-full text-xs ">BPJS</span>';
                                } elseif ($penjamin == 'Diri Sendiri') {
                                    echo '<span class="px-2 py-1 bg-red-200 rounded-full text-xs ">UMUM</span>';
                                } else {
                                    echo '<span class="px-2 py-1 bg-yellow-200 rounded-full text-xs ">LAPAD</span>';
                                }
                                ?>
                            </td>
                            <td class="border text-xs px-1 py-0.5"><?= htmlspecialchars($row['nosep'] ?? '-') ?></td>
                            <td class="border text-xs px-1 py-0.5 print-hide text-center">
                                 <!-- Radiologi (hanya jika ada) -->
                                <?php if ($hasRadiologi): ?>
                                    <button class="btn-radiologi btn-radiologi-modal"
        data-norec_pd="<?= urlencode($norec_pd) ?>"
        data-noregistrasi="<?= urlencode($row['noregistrasi']) ?>"
        data-nama="<?= htmlspecialchars($row['namapasien']) ?>">
    <i class="fas fa-x-ray"></i> Rad
</button>
                                <?php endif; ?>
                            </td>
                          
                            <td class="border text-xs px-1 py-0.5 print-hide text-center">
                                <!-- Laboratorium (hanya jika ada) -->
                                <?php if ($row['has_laboratorium']): ?>
                                  <button class="btn-laboratorium btn-laboratorium-modal"
                                            data-norec_pd="<?= urlencode($norec_pd) ?>"
                                            data-noregistrasi="<?= urlencode($row['noregistrasi']) ?>"
                                            data-nama="<?= htmlspecialchars($row['namapasien']) ?>">
                                        <i class="fas fa-flask"></i> Lab
                                    </button>
                                <?php endif; ?>
                            </td>
                              <td class="border text-xs px-1 py-0.5 print-hide text-center">
                                <a href="http://192.168.22.81<?= $linkBilling ?>" target="_blank" class="btn-billing">
                                    <i class="fas fa-file-invoice-dollar"></i> Billing
                                </a>
                            </td>
                            <td class="border flex justify-content-between text-xs px-1 py-0.5 print-hide text-center">
                                    <button class="btn-emr btn-emr-modal"
        data-norec_pd="<?= urlencode($norec_pd) ?>"
        data-noregistrasi="<?= urlencode($row['noregistrasi']) ?>"
        data-nocmfk="<?= urlencode($row['nocmfk']) ?>"
        data-norec_apd="<?= urlencode($row['norec_apd']) ?>"
        data-emr_surat_fk="<?= urlencode($row['emrpasienfk_surat'] ?? '') ?>"
        data-nama="<?= htmlspecialchars($row['namapasien']) ?>">
    <i class="fas fa-stethoscope"></i> EMR 
</button>

 
                                <!-- EMR -->
                                <a href="<?= $linkEmr ?>" target="_blank" class="btn-emr">
                                    <i class="fas fa-user"></i> TM
                                </a>
                                <!-- Billing -->
                               
                              <!-- Rujukan Manual (diverifikasi lewat AJAX) -->



                               
                                
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="bg-gray-50 px-4 py-2 flex justify-between items-center flex-wrap gap-1 no-print">
        <div class="text-xs text-gray-600">Menampilkan <?= $offset+1 ?> - <?= min($offset+$limit, $total_records) ?> dari <?= number_format($total_records) ?></div>
        <div class="flex gap-1">
            <?php if ($page > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page-1])) ?>" class="px-2 py-0.5 border rounded text-xs hover:bg-gray-200">« Prev</a>
            <?php endif; ?>
            <?php for($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="px-2 py-0.5 bg-green-500 text-white rounded text-xs"><?= $i ?></span>
                <?php else: ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="px-2 py-0.5 border rounded text-xs hover:bg-gray-200"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page+1])) ?>" class="px-2 py-0.5 border rounded text-xs hover:bg-gray-200">Next »</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter Modal
    const toggleBtn = document.getElementById('toggleFilterBtn');
    const filterModal = document.getElementById('filterModal');
    const closeFilterBtn = document.getElementById('closeModalBtn');
    toggleBtn.addEventListener('click', () => { filterModal.classList.toggle('hidden'); });
    closeFilterBtn.addEventListener('click', () => { filterModal.classList.add('hidden'); });
    window.addEventListener('click', (e) => { if (e.target === filterModal) filterModal.classList.add('hidden'); });

    // === Radiologi Modal ===
    const radiologiModal = document.getElementById('radiologiModal');
    const radiologiContent = document.getElementById('radiologiContent');
    const radiologiNoreg = document.getElementById('radiologiNoreg');
    const closeRadiologiBtn = document.getElementById('closeRadiologiModal');

    function closeRadiologiModal() {
        radiologiModal.classList.add('hidden');
        radiologiContent.innerHTML = `<div class="flex justify-center items-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <span class="ml-2 text-gray-500">Memuat data...</span>
        </div>`;
    }

    closeRadiologiBtn.addEventListener('click', closeRadiologiModal);
    window.addEventListener('click', function(e) {
        if (e.target === radiologiModal) closeRadiologiModal();
    });

    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-radiologi-modal');
        if (!btn) return;

        e.preventDefault();
        const norecPd = btn.dataset.norec_pd;
        const noregistrasi = btn.dataset.noregistrasi;
        const nama = btn.dataset.nama || noregistrasi;

        radiologiModal.classList.remove('hidden');
        radiologiNoreg.textContent = noregistrasi;
        radiologiContent.innerHTML = `<div class="flex justify-center items-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <span class="ml-2 text-gray-500">Memuat data...</span>
        </div>`;

        fetch(`ajax_radiologi_detail.php?norec_pd=${encodeURIComponent(norecPd)}&noregistrasi=${encodeURIComponent(noregistrasi)}`)
            .then(response => {
                if (!response.ok) throw new Error('Gagal memuat');
                return response.text();
            })
            .then(html => {
                radiologiContent.innerHTML = html;
            })
            .catch(error => {
                radiologiContent.innerHTML = `<p class="text-red-500 text-center py-4">Gagal memuat data radiologi: ${error.message}</p>`;
            });
    });

    window.showExpertise = function(text) {
        const expertiseModal = document.createElement('div');
        expertiseModal.className = 'fixed inset-0 bg-black bg-opacity-50 z-[60] flex justify-center items-center';
        expertiseModal.innerHTML = `
            <div class="bg-white rounded-xl max-w-lg w-11/12 p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="font-bold text-lg">Hasil Expertise</h4>
                    <button class="text-gray-500 hover:text-gray-700" onclick="this.closest('.fixed').remove()">&times;</button>
                </div>
                <pre class="whitespace-pre-wrap text-sm">${text}</pre>
            </div>
        `;
        document.body.appendChild(expertiseModal);
        expertiseModal.addEventListener('click', function(e) {
            if (e.target === expertiseModal) expertiseModal.remove();
        });
    };

    // === Laboratorium Modal ===
    const laboratoriumModal = document.getElementById('laboratoriumModal');
    const laboratoriumContent = document.getElementById('laboratoriumContent');
    const laboratoriumNoreg = document.getElementById('laboratoriumNoreg');
    const closeLaboratoriumBtn = document.getElementById('closeLaboratoriumModal');

    function closeLaboratoriumModal() {
        laboratoriumModal.classList.add('hidden');
        laboratoriumContent.innerHTML = `<div class="flex justify-center items-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <span class="ml-2 text-gray-500">Memuat data...</span>
        </div>`;
    }

    closeLaboratoriumBtn.addEventListener('click', closeLaboratoriumModal);
    window.addEventListener('click', function(e) {
        if (e.target === laboratoriumModal) closeLaboratoriumModal();
    });

    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-laboratorium-modal');
        if (!btn) return;

        e.preventDefault();
        const norecPd = btn.dataset.norec_pd;
        const noregistrasi = btn.dataset.noregistrasi;
        const nama = btn.dataset.nama || noregistrasi;

        laboratoriumModal.classList.remove('hidden');
        laboratoriumNoreg.textContent = noregistrasi;
        laboratoriumContent.innerHTML = `<div class="flex justify-center items-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <span class="ml-2 text-gray-500">Memuat data...</span>
        </div>`;

        fetch(`ajax_laboratorium_detail.php?norec_pd=${encodeURIComponent(norecPd)}&noregistrasi=${encodeURIComponent(noregistrasi)}`)
            .then(response => {
                if (!response.ok) throw new Error('Gagal memuat');
                return response.text();
            })
            .then(html => {
                laboratoriumContent.innerHTML = html;
            })
            .catch(error => {
                laboratoriumContent.innerHTML = `<p class="text-red-500 text-center py-4">Gagal memuat data laboratorium: ${error.message}</p>`;
            });
    });

    // === EMR Modal ===
    const emrModal = document.getElementById('emrModal');
    const emrContent = document.getElementById('emrContent');
    const emrNoreg = document.getElementById('emrNoreg');
    const closeEmrBtn = document.getElementById('closeEmrModal');

    function closeEmrModal() {
        emrModal.classList.add('hidden');
        emrContent.innerHTML = `<div class="flex justify-center items-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <span class="ml-2 text-gray-500">Memuat data...</span>
        </div>`;
    }

    closeEmrBtn.addEventListener('click', closeEmrModal);
    window.addEventListener('click', function(e) {
        if (e.target === emrModal) closeEmrModal();
    });

    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-emr-modal');
        if (!btn) return;

        e.preventDefault();
        const norecPd = btn.dataset.norec_pd;
        const noregistrasi = btn.dataset.noregistrasi;
        const nocmfk = btn.dataset.nocmfk;
        const norecApd = btn.dataset.norec_apd;
        const emrSuratFk = btn.dataset.emr_surat_fk;
        const nama = btn.dataset.nama || noregistrasi;

        emrModal.classList.remove('hidden');
        emrNoreg.textContent = noregistrasi;
        emrContent.innerHTML = `<div class="flex justify-center items-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <span class="ml-2 text-gray-500">Memuat data...</span>
        </div>`;

        let url = `ajax_emr_detail.php?norec_pd=${encodeURIComponent(norecPd)}&noregistrasi=${encodeURIComponent(noregistrasi)}&nocmfk=${encodeURIComponent(nocmfk)}&norec_apd=${encodeURIComponent(norecApd)}`;
        if (emrSuratFk) {
            url += `&emr_surat_fk=${encodeURIComponent(emrSuratFk)}`;
        }

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Gagal memuat');
                return response.text();
            })
            .then(html => {
                emrContent.innerHTML = html;
            })
            .catch(error => {
                emrContent.innerHTML = `<p class="text-red-500 text-center py-4">Gagal memuat data EMR: ${error.message}</p>`;
            });
    });

    // === Bundle Modal ===
    const bundleModal = document.getElementById('bundleModal');
    const bundleContent = document.getElementById('bundleContent');
    const bundleNoreg = document.getElementById('bundleNoreg');
    const closeBundleBtn = document.getElementById('closeBundleModal');

    function closeBundleModal() {
        bundleModal.classList.add('hidden');
        bundleContent.innerHTML = `<div class="flex justify-center items-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <span class="ml-2 text-gray-500">Memuat data...</span>
        </div>`;
    }

    closeBundleBtn.addEventListener('click', closeBundleModal);
    window.addEventListener('click', function(e) {
        if (e.target === bundleModal) closeBundleModal();
    });

    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-bundle-modal');
        if (!btn) return;

        e.preventDefault();
        const noregistrasi = btn.dataset.noregistrasi;
        const nama = btn.dataset.nama || noregistrasi;

        bundleModal.classList.remove('hidden');
        bundleNoreg.textContent = noregistrasi;
        bundleContent.innerHTML = `<div class="flex justify-center items-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <span class="ml-2 text-gray-500">Memuat data...</span>
        </div>`;

        fetch(`ajax_bundle_detail.php?noregistrasi=${encodeURIComponent(noregistrasi)}`)
            .then(response => {
                if (!response.ok) throw new Error('Gagal memuat');
                return response.text();
            })
            .then(html => {
                bundleContent.innerHTML = html;
            })
            .catch(error => {
                bundleContent.innerHTML = `<p class="text-red-500 text-center py-4">Gagal memuat data bundle: ${error.message}</p>`;
            });
    });
    });



</script>
<script>
document.addEventListener("click", async function(e){

    const tr = e.target.closest(".patient-row");

    if(!tr) return;

    // jangan copy jika klik tombol atau link
    if(e.target.closest("a,button")){
        return;
    }

    const nosep = tr.dataset.nosep;

    if(!nosep || nosep === "-"){
        return;
    }

    try{

        await navigator.clipboard.writeText(nosep);

        // efek highlight
        tr.classList.add("bg-green-200");

        setTimeout(()=>{
            tr.classList.remove("bg-green-200");
        },700);

        // toast kecil
        showToast("No SEP berhasil dicopy : " + nosep);

    }catch(err){

        console.log(err);

    }

});


function showToast(text){

    const toast=document.createElement("div");

    toast.className="fixed bottom-5 right-5 bg-green-600 text-white px-5 py-3 rounded-lg shadow-xl z-50";

    toast.innerHTML="📋 "+text;

    document.body.appendChild(toast);

    setTimeout(()=>{

        toast.remove();

    },1800);

}
</script>
</body>
</html>