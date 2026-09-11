<?php
// Koneksi database
$host = "192.168.22.81";
$port = "5792";
$dbname = "rsud_malangbong";
$user = "postgres";
$password = "Tr4nsm3d!c MaRe#T3aM";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

session_start();

// Cek apakah PhpSpreadsheet tersedia
$usePhpSpreadsheet = false;
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        $usePhpSpreadsheet = true;
    }
}

// Ambil daftar produk dan ruangan
$produkList = $pdo->query("SELECT id, namaproduk FROM produk_m WHERE statusenabled = true and namaexternal like '%BMHP%' or namaexternal like '%OBAT%' ORDER BY namaproduk ")->fetchAll();
$ruanganList = $pdo->query("SELECT id, namaruangan FROM ruangan_m WHERE statusenabled = true AND namaruangan like '%FARMASI%' ORDER BY namaruangan LIMIT 100")->fetchAll();

$records_per_page = 100;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $records_per_page;

$search = $_GET['search'] ?? '';
$filter_produk = $_GET['filter_produk'] ?? '';
$filter_ruangan = $_GET['filter_ruangan'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';
$filter_expired = $_GET['filter_expired'] ?? '';

// Query conditions
$whereConditions = [];
$params = [];
$paramTypes = [];

if (!empty($search)) {
    $whereConditions[] = "(pr.namaproduk ILIKE :search OR ru.namaruangan ILIKE :search)";
    $params[':search'] = '%' . $search . '%';
    $paramTypes[':search'] = PDO::PARAM_STR;
}
if (!empty($filter_produk)) {
    $whereConditions[] = "spd.objectprodukfk = :filter_produk";
    $params[':filter_produk'] = $filter_produk;
    $paramTypes[':filter_produk'] = PDO::PARAM_INT;
}
if (!empty($filter_ruangan)) {
    $whereConditions[] = "spd.objectruanganfk = :filter_ruangan";
    $params[':filter_ruangan'] = $filter_ruangan;
    $paramTypes[':filter_ruangan'] = PDO::PARAM_INT;
}
if ($filter_status !== '') {
    $whereConditions[] = "spd.statusenabled = :filter_status";
    $params[':filter_status'] = $filter_status;
    $paramTypes[':filter_status'] = PDO::PARAM_BOOL;
}
if ($filter_expired !== '') {
    $today = date('Y-m-d');
    if ($filter_expired === 'expired') {
        $whereConditions[] = "spd.tglkadaluarsa < :today AND spd.tglkadaluarsa IS NOT NULL";
    } elseif ($filter_expired === 'soon') {
        $whereConditions[] = "spd.tglkadaluarsa BETWEEN :today AND :next_month";
        $next_month = date('Y-m-d', strtotime('+30 days'));
        $params[':next_month'] = $next_month;
        $paramTypes[':next_month'] = PDO::PARAM_STR;
    } elseif ($filter_expired === 'safe') {
        $whereConditions[] = "(spd.tglkadaluarsa > :next_month OR spd.tglkadaluarsa IS NULL)";
        $next_month = date('Y-m-d', strtotime('+30 days'));
        $params[':next_month'] = $next_month;
        $paramTypes[':next_month'] = PDO::PARAM_STR;
    }
    $params[':today'] = $today;
    $paramTypes[':today'] = PDO::PARAM_STR;
}

$queryBase = "FROM stokprodukdetail_t spd
              JOIN produk_m pr ON pr.id = spd.objectprodukfk
              JOIN ruangan_m ru ON ru.id = spd.objectruanganfk";

$whereClause = '';
if (!empty($whereConditions)) {
    $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
}

$queryData = "SELECT spd.norec, spd.qtyproduk, spd.tglkadaluarsa, 
                     spd.statusenabled, pr.namaproduk, ru.namaruangan,
                     spd.objectprodukfk, spd.objectruanganfk
              $queryBase
              $whereClause
              ORDER BY spd.created_at DESC
              LIMIT :limit OFFSET :offset";

$queryCount = "SELECT COUNT(*) as total $queryBase $whereClause";

$stmtCount = $pdo->prepare($queryCount);
foreach ($params as $key => $value) {
    $stmtCount->bindValue($key, $value, $paramTypes[$key] ?? PDO::PARAM_STR);
}
$stmtCount->execute();
$total_records = $stmtCount->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// --- HANDLER EXPORT EXCEL (XLSX atau CSV) ---
if (isset($_GET['ajax']) && $_GET['ajax'] === 'export') {
    $queryExport = "SELECT pr.namaproduk, ru.namaruangan, spd.qtyproduk, spd.tglkadaluarsa, spd.statusenabled
                    $queryBase $whereClause ORDER BY spd.created_at DESC";
    $stmtExport = $pdo->prepare($queryExport);
    foreach ($params as $key => $value) {
        $stmtExport->bindValue($key, $value, $paramTypes[$key] ?? PDO::PARAM_STR);
    }
    $stmtExport->execute();
    $dataExport = $stmtExport->fetchAll();

    // Jika PhpSpreadsheet tersedia, export .xlsx
    if ($usePhpSpreadsheet) {
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Stok Obat');

            // Header
            $headers = ['Produk', 'Ruangan', 'Quantity', 'Tanggal Kadaluarsa', 'Status', 'Keterangan'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $col++;
            }

            // Data
            $row = 2;
            foreach ($dataExport as $data) {
                $status = $data['statusenabled'] ? 'Aktif' : 'Nonaktif';
                $tgl = $data['tglkadaluarsa'] ? date('d/m/Y', strtotime($data['tglkadaluarsa'])) : '';
                $keterangan = '';
                if ($data['tglkadaluarsa']) {
                    $now = new DateTime();
                    $exp = new DateTime($data['tglkadaluarsa']);
                    if ($exp < $now) {
                        $keterangan = 'Expired';
                    } else {
                        $diff = $now->diff($exp)->days;
                        if ($diff <= 30) {
                            $keterangan = 'Hampir Expired (≤30 hari)';
                        } else {
                            $keterangan = 'Aman';
                        }
                    }
                }
                $sheet->setCellValue('A' . $row, $data['namaproduk']);
                $sheet->setCellValue('B' . $row, $data['namaruangan']);
                $sheet->setCellValue('C' . $row, $data['qtyproduk']);
                $sheet->setCellValue('D' . $row, $tgl);
                $sheet->setCellValue('E' . $row, $status);
                $sheet->setCellValue('F' . $row, $keterangan);
                $row++;
            }

            // Auto size columns
            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Output
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="stok_obat_' . date('Y-m-d_H-i-s') . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            // Fallback ke CSV jika terjadi error
            $usePhpSpreadsheet = false;
        }
    }

    // Fallback ke CSV jika PhpSpreadsheet tidak tersedia atau gagal
    if (!$usePhpSpreadsheet) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="stok_obat_' . date('Y-m-d_H-i-s') . '.csv"');
        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF");
        fputcsv($output, ['Produk', 'Ruangan', 'Quantity', 'Tanggal Kadaluarsa', 'Status', 'Keterangan']);
        foreach ($dataExport as $row) {
            $status = $row['statusenabled'] ? 'Aktif' : 'Nonaktif';
            $tgl = $row['tglkadaluarsa'] ? date('d/m/Y', strtotime($row['tglkadaluarsa'])) : '';
            $keterangan = '';
            if ($row['tglkadaluarsa']) {
                $now = new DateTime();
                $exp = new DateTime($row['tglkadaluarsa']);
                if ($exp < $now) {
                    $keterangan = 'Expired';
                } else {
                    $diff = $now->diff($exp)->days;
                    if ($diff <= 30) {
                        $keterangan = 'Hampir Expired (≤30 hari)';
                    } else {
                        $keterangan = 'Aman';
                    }
                }
            }
            fputcsv($output, [
                $row['namaproduk'],
                $row['namaruangan'],
                $row['qtyproduk'],
                $tgl,
                $status,
                $keterangan
            ]);
        }
        fclose($output);
        exit;
    }
}

// --- CRUD AJAX handlers (sama seperti sebelumnya) ---
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if (isset($_GET['ajax']) && $_GET['ajax'] === 'load-data') {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $records_per_page;

    $stmtData = $pdo->prepare($queryData);
    foreach ($params as $key => $value) {
        $stmtData->bindValue($key, $value, $paramTypes[$key] ?? PDO::PARAM_STR);
    }
    $stmtData->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmtData->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmtData->execute();
    $stokList = $stmtData->fetchAll();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'data' => $stokList,
        'total_records' => $total_records,
        'total_pages' => $total_pages,
        'page' => $page
    ]);
    exit;
}

if (isset($_GET['ajax']) && $_GET['ajax'] === 'get-data' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM stokprodukdetail_t WHERE norec = :id");
    $stmt->execute([':id' => $_GET['id']]);
    $data = $stmt->fetch();
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

if (isset($_GET['ajax']) && $_GET['ajax'] === 'save-data') {
    $objectprodukfk = filter_input(INPUT_POST, 'objectprodukfk', FILTER_VALIDATE_INT);
    $objectruanganfk = filter_input(INPUT_POST, 'objectruanganfk', FILTER_VALIDATE_INT);
    $qtyproduk = filter_input(INPUT_POST, 'qtyproduk', FILTER_VALIDATE_INT);
    $tglkadaluarsa = !empty($_POST['tglkadaluarsa']) ? $_POST['tglkadaluarsa'] : null;
    $statusenabled = isset($_POST['statusenabled']);
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? '';

    if ($objectprodukfk === false || $objectruanganfk === false || $qtyproduk === false || $qtyproduk < 0) {
        echo json_encode(['success' => false, 'message' => 'Data tidak valid!']);
        exit;
    }

    if ($action === 'update' && $id) {
        $sql = "UPDATE stokprodukdetail_t SET 
                objectprodukfk=:objectprodukfk, 
                objectruanganfk=:objectruanganfk, 
                qtyproduk=:qtyproduk, 
                tglkadaluarsa=:tglkadaluarsa, 
                statusenabled=:statusenabled, 
                updated_at=NOW() 
                WHERE norec=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':objectprodukfk' => $objectprodukfk,
            ':objectruanganfk' => $objectruanganfk,
            ':qtyproduk' => $qtyproduk,
            ':tglkadaluarsa' => $tglkadaluarsa,
            ':statusenabled' => $statusenabled,
            ':id' => $id
        ]);
        echo json_encode(['success' => true, 'message' => 'Data stok obat berhasil diperbarui!']);
        exit;
    } else {
        $sql = "INSERT INTO stokprodukdetail_t 
                (objectprodukfk, objectruanganfk, qtyproduk, tglkadaluarsa, statusenabled, created_at, updated_at)
                VALUES (:objectprodukfk, :objectruanganfk, :qtyproduk, :tglkadaluarsa, :statusenabled, NOW(), NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':objectprodukfk' => $objectprodukfk,
            ':objectruanganfk' => $objectruanganfk,
            ':qtyproduk' => $qtyproduk,
            ':tglkadaluarsa' => $tglkadaluarsa,
            ':statusenabled' => $statusenabled
        ]);
        echo json_encode(['success' => true, 'message' => 'Data stok obat berhasil ditambahkan!']);
        exit;
    }
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM stokprodukdetail_t WHERE norec=:id");
    $stmt->execute([':id' => $id]);
    $_SESSION['success_message'] = "Data stok obat berhasil dihapus!";
    header("Location: " . basename($_SERVER['PHP_SELF']) . buildQueryString(['action' => null, 'id' => null]));
    exit;
}

// Load data awal
$stmtData = $pdo->prepare($queryData);
foreach ($params as $key => $value) {
    $stmtData->bindValue($key, $value, $paramTypes[$key] ?? PDO::PARAM_STR);
}
$stmtData->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmtData->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtData->execute();
$stokList = $stmtData->fetchAll();

function buildQueryString($modifications = []) {
    $current = $_GET;
    foreach ($modifications as $key => $value) {
        if ($value === null) unset($current[$key]);
        else $current[$key] = $value;
    }
    return empty($current) ? '' : '?' . http_build_query($current);
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stok Obat - RSUD Malangbong</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Select2 styling */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 4px 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
            color: #1f2937;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }

        .pagination-link {
            padding: 0.3rem 0.6rem;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            color: #374151;
            transition: background 0.2s;
            background: white;
            font-size: 0.875rem;
        }
        .pagination-link:hover { background: #f3f4f6; }
        .pagination-link.active { background: #2563eb; border-color: #2563eb; color: white; }

        .sticky-filter {
            position: sticky;
            top: 0;
            z-index: 30;
            background: white;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 0.75rem;
        }
        .filter-bar .filter-item {
            flex: 1 1 150px;
            min-width: 120px;
        }
        .filter-bar .filter-item label {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.2rem;
        }
        .filter-bar .filter-item input,
        .filter-bar .filter-item select {
            width: 100%;
            padding: 0.3rem 0.6rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            background: white;
        }
        .filter-bar .filter-item .select2-container {
            width: 100% !important;
        }
        .filter-bar .filter-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex: 0 0 auto;
        }

        @media (max-width: 768px) {
            .filter-bar .filter-item {
                flex: 1 1 100%;
                min-width: unset;
            }
            .filter-bar .filter-actions {
                flex: 1 1 100%;
                justify-content: flex-end;
            }
        }

        .table-wrapper {
            overflow-y: auto;
            max-height: calc(100vh - 240px);
        }
        .table-minimal {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table-minimal thead {
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .table-minimal thead th {
            background: #f9fafb;
            border-bottom: 2px solid #d1d5db;
            padding: 0.6rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .table-minimal tbody td {
            padding: 0.6rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.875rem;
        }
        .table-minimal tbody tr:last-child td {
            border-bottom: none;
        }
        .table-minimal tbody tr:hover {
            background: #f9fafb;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary {
            background: white;
            color: #374151;
            padding: 0.4rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            border: 1px solid #d1d5db;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-secondary:hover { background: #f3f4f6; }
        .btn-success {
            background: #16a34a;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-success:hover { background: #15803d; }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 40;
            display: none;
        }
        .modal {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 36rem;
            margin: 1rem;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .loading-spinner {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 4px solid #bfdbfe;
            border-top-color: #2563eb;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .status-expired { color: #dc2626; font-weight: 600; }
        .status-soon { color: #d97706; }
        .status-safe { color: #4b5563; }
        .batch-badge {
            background: #e5e7eb;
            color: #1f2937;
            padding: 0.15rem 0.6rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Modal -->
    <div id="modalOverlay" class="modal-overlay"></div>
    <div id="crudModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800" id="modalTitle">Tambah Stok Obat</h2>
                <button type="button" id="closeModalBtn" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="crudForm" class="space-y-4">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id" id="formId" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Produk <span class="text-red-500">*</span></label>
                        <select name="objectprodukfk" required id="modalProduk" class="select2 w-full">
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($produkList as $produk): ?>
                                <option value="<?= $produk['id'] ?>"><?= htmlspecialchars($produk['namaproduk']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                        <select name="objectruanganfk" required id="modalRuangan" class="select2 w-full">
                            <option value="">-- Pilih Ruangan --</option>
                            <?php foreach ($ruanganList as $ruangan): ?>
                                <option value="<?= $ruangan['id'] ?>"><?= htmlspecialchars($ruangan['namaruangan']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="qtyproduk" min="0" required id="modalQty" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Jumlah">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                        <input type="date" name="tglkadaluarsa" id="modalKadaluarsa" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="statusenabled" value="1" id="modalStatus" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Status Aktif</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" id="cancelModalBtn" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-4">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-3 gap-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-pills mr-2 text-blue-600"></i>Manajemen Stok Obat</h1>
                <p class="text-sm text-gray-500">RSUD Malangbong</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" id="addNewBtn" class="btn-primary"><i class="fas fa-plus-circle mr-1"></i> Tambah</button>
                <button type="button" id="exportBtn" class="btn-success"><i class="fas fa-file-excel mr-1"></i> Export Excel</button>
                <div class="bg-gray-100 px-3 py-1 rounded-lg text-sm text-gray-700 border">
                    <i class="fas fa-database mr-1"></i>
                    Total: <span id="totalRecords" class="font-semibold"><?= number_format($total_records) ?></span>
                    • Halaman <span id="currentPage"><?= $page ?></span>/<span id="totalPages"><?= $total_pages ?></span>
                </div>
            </div>
        </div>

        <!-- Filter Bar (Sticky) -->
        <div class="sticky-filter" id="filterBar">
            <form id="filterForm" class="filter-bar">
                <div class="filter-item">
                    <label for="searchInput">Cari</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" id="searchInput" placeholder="Produk / Ruangan">
                </div>
                <div class="filter-item">
                    <label for="filterProduk">Produk</label>
                    <select name="filter_produk" id="filterProduk" class="select2">
                        <option value="">Semua</option>
                        <?php foreach ($produkList as $produk): ?>
                            <option value="<?= $produk['id'] ?>" <?= $filter_produk == $produk['id'] ? 'selected' : '' ?>><?= htmlspecialchars($produk['namaproduk']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterRuangan">Ruangan</label>
                    <select name="filter_ruangan" id="filterRuangan" class="select2">
                        <option value="">Semua</option>
                        <?php foreach ($ruanganList as $ruangan): ?>
                            <option value="<?= $ruangan['id'] ?>" <?= $filter_ruangan == $ruangan['id'] ? 'selected' : '' ?>><?= htmlspecialchars($ruangan['namaruangan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterStatus">Status</label>
                    <select name="filter_status" id="filterStatus">
                        <option value="">Semua</option>
                        <option value="1" <?= $filter_status === '1' ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= $filter_status === '0' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="filterExpired">Kadaluarsa</label>
                    <select name="filter_expired" id="filterExpired">
                        <option value="">Semua</option>
                        <option value="expired" <?= $filter_expired === 'expired' ? 'selected' : '' ?>>Sudah</option>
                        <option value="soon" <?= $filter_expired === 'soon' ? 'selected' : '' ?>>≤30 hari</option>
                        <option value="safe" <?= $filter_expired === 'safe' ? 'selected' : '' ?>>Aman</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="button" id="applyFilterBtn" class="btn-primary"><i class="fas fa-filter mr-1"></i> Terapkan</button>
                    <button type="button" id="resetFilterBtn" class="btn-secondary"><i class="fas fa-undo mr-1"></i> Reset</button>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="card mt-2">
            <div class="px-4 py-2 border-b border-gray-200 bg-gray-50 flex justify-between items-center text-sm">
                <span class="font-semibold text-gray-700"><i class="fas fa-list mr-2"></i>Daftar Stok</span>
                <span class="text-xs text-gray-500" id="dataRange">
                    <?php
                    $start = min(count($stokList), $offset + 1);
                    $end = min($total_records, $offset + $records_per_page);
                    ?>
                    Menampilkan <?= $start ?>-<?= $end ?> dari <?= number_format($total_records) ?>
                </span>
            </div>

            <div class="table-wrapper">
                <table class="table-minimal">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Ruangan</th>
                            <th>Quantity</th>
                            <th>Kadaluarsa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="data-table-body">
                        <?php if (empty($stokList)): ?>
                            <tr><td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($stokList as $stok): ?>
                                <tr class="table-row-hover">
                                    <td class="font-medium text-gray-900"><?= htmlspecialchars($stok['namaproduk']) ?></td>
                                    <td class="text-gray-700"><?= htmlspecialchars($stok['namaruangan']) ?></td>
                                    <td><span class="batch-badge"><?= number_format($stok['qtyproduk']) ?></span></td>
                                    <td>
                                        <?php if ($stok['tglkadaluarsa']):
                                            $tgl = new DateTime($stok['tglkadaluarsa']);
                                            $now = new DateTime();
                                            $diff = $now->diff($tgl);
                                            $days = $diff->days;
                                            $isExpired = $tgl < $now;
                                        ?>
                                            <span class="<?= $isExpired ? 'status-expired' : ($days <= 30 ? 'status-soon' : 'status-safe') ?>">
                                                <?= date('d/m/Y', strtotime($stok['tglkadaluarsa'])) ?>
                                            </span>
                                            <?php if ($isExpired): ?>
                                                <span class="badge badge-inactive ml-1"><i class="fas fa-exclamation-triangle mr-1"></i>Expired</span>
                                            <?php elseif ($days <= 30): ?>
                                                <span class="badge" style="background:#fef3c7;color:#92400e;">Hampir</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $stok['statusenabled'] ? 'badge-active' : 'badge-inactive' ?>">
                                            <i class="fas fa-<?= $stok['statusenabled'] ? 'check-circle' : 'times-circle' ?> mr-1"></i>
                                            <?= $stok['statusenabled'] ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="window.editData('<?= $stok['norec'] ?>')" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></button>
                                            <button type="button" onclick="window.deleteData(<?= $stok['norec'] ?>, '<?= htmlspecialchars(addslashes($stok['namaproduk'])) ?>')" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="loadingIndicator" class="hidden p-4 text-center">
                <div class="loading-spinner mx-auto mb-2"></div>
                <p class="text-sm text-gray-500">Memuat...</p>
            </div>

            <div id="paginationContainer" class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                <?php if ($total_pages > 1): ?>
                    <div class="flex flex-col md:flex-row items-center justify-between gap-3">
                        <div class="text-sm text-gray-600">
                            Halaman <span class="font-semibold"><?= $page ?></span> dari <span class="font-semibold"><?= $total_pages ?></span>
                        </div>
                        <div class="flex flex-wrap gap-1" id="paginationLinks">
                            <?php if ($page > 1): ?>
                                <button type="button" onclick="window.changePage(1)" class="pagination-link"><i class="fas fa-angle-double-left"></i></button>
                                <button type="button" onclick="window.changePage(<?= $page - 1 ?>)" class="pagination-link"><i class="fas fa-angle-left"></i></button>
                            <?php endif; ?>
                            <?php
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);
                            if ($start_page > 1) {
                                echo '<button type="button" onclick="window.changePage(1)" class="pagination-link">1</button>';
                                if ($start_page > 2) echo '<span class="px-2 text-gray-500">...</span>';
                            }
                            for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <button type="button" onclick="window.changePage(<?= $i ?>)" class="pagination-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></button>
                            <?php endfor;
                            if ($end_page < $total_pages) {
                                if ($end_page < $total_pages - 1) echo '<span class="px-2 text-gray-500">...</span>';
                                echo '<button type="button" onclick="window.changePage('.$total_pages.')" class="pagination-link">'.$total_pages.'</button>';
                            }
                            if ($page < $total_pages): ?>
                                <button type="button" onclick="window.changePage(<?= $page + 1 ?>)" class="pagination-link"><i class="fas fa-angle-right"></i></button>
                                <button type="button" onclick="window.changePage(<?= $total_pages ?>)" class="pagination-link"><i class="fas fa-angle-double-right"></i></button>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center space-x-2 text-sm">
                            <span class="text-gray-600">Ke halaman:</span>
                            <input type="number" id="goToPage" min="1" max="<?= $total_pages ?>" class="w-14 px-1 py-0.5 border border-gray-300 rounded text-center" value="<?= $page ?>">
                            <button type="button" onclick="window.goToPage()" class="px-2 py-0.5 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Go</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-6 text-center text-xs text-gray-400">
            © <?= date('Y') ?> RSUD Malangbong • Sistem Manajemen Stok Obat
        </div>
    </div>

    <script>
        // Variabel global
        let currentPage = <?= $page ?>;
        let totalPages = <?= $total_pages ?>;
        let searchTimeout;

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih...",
                allowClear: true,
                width: '100%'
            });

            <?php if (isset($_SESSION['success_message'])): ?>
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= $_SESSION['success_message'] ?>', timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
        });

        // Modal functions
        window.openModal = function(action = 'create', id = null) {
            const modal = document.getElementById('crudModal');
            const overlay = document.getElementById('modalOverlay');
            const title = document.getElementById('modalTitle');
            const formAction = document.getElementById('formAction');
            const formId = document.getElementById('formId');
            const form = document.getElementById('crudForm');

            if (action === 'create') {
                title.textContent = 'Tambah Stok Obat';
                formAction.value = 'create';
                formId.value = '';
                form.reset();
                $('#modalProduk').val('').trigger('change');
                $('#modalRuangan').val('').trigger('change');
                document.getElementById('modalStatus').checked = true;
                document.getElementById('modalKadaluarsa').value = '';
                document.getElementById('modalQty').value = '';
            } else {
                title.textContent = 'Edit Stok Obat';
            }

            modal.style.display = 'flex';
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        };

        window.closeModal = function() {
            document.getElementById('crudModal').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
            document.body.style.overflow = 'auto';
        };

        window.editData = async function(id) {
            try {
                const response = await fetch(`<?= basename($_SERVER['PHP_SELF']) ?>?ajax=get-data&id=${id}`);
                const result = await response.json();

                if (result.success) {
                    const data = result.data;
                    document.getElementById('modalTitle').textContent = 'Edit Stok Obat';
                    document.getElementById('formAction').value = 'update';
                    document.getElementById('formId').value = data.norec;

                    $('#modalProduk').val(data.objectprodukfk).trigger('change');
                    $('#modalRuangan').val(data.objectruanganfk).trigger('change');
                    document.getElementById('modalQty').value = data.qtyproduk;
                    let tgl = data.tglkadaluarsa || '';
                    if (tgl.length > 10) tgl = tgl.substring(0, 10);
                    document.getElementById('modalKadaluarsa').value = tgl;
                    document.getElementById('modalStatus').checked = data.statusenabled;

                    window.openModal('update', id);
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data' });
            }
        };

        window.deleteData = function(id, produkName) {
            Swal.fire({
                title: 'Hapus data?',
                html: `Stok obat <strong>${produkName}</strong> akan dihapus.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `<?= basename($_SERVER['PHP_SELF']) . buildQueryString() ?>?action=delete&id=${id}`;
                }
            });
        };

        // Load data dengan AJAX
        window.loadData = async function(page = 1) {
            const loading = document.getElementById('loadingIndicator');
            const tableBody = document.getElementById('data-table-body');
            const pagContainer = document.getElementById('paginationContainer');
            const totalSpan = document.getElementById('totalRecords');
            const currentSpan = document.getElementById('currentPage');
            const totalPagesSpan = document.getElementById('totalPages');
            const rangeSpan = document.getElementById('dataRange');

            loading.classList.remove('hidden');
            tableBody.innerHTML = '';
            pagContainer.classList.add('hidden');

            const formData = new FormData(document.getElementById('filterForm'));
            const params = new URLSearchParams();
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            params.append('page', page);
            params.append('ajax', 'load-data');

            try {
                const response = await fetch(`<?= basename($_SERVER['PHP_SELF']) ?>?${params.toString()}`);
                const result = await response.json();

                if (result.success) {
                    if (result.data.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data</td></tr>`;
                    } else {
                        let html = '';
                        result.data.forEach(stok => {
                            const tgl = stok.tglkadaluarsa ? new Date(stok.tglkadaluarsa) : null;
                            let statusKadaluarsa = '', badgeExtra = '';
                            if (tgl) {
                                const now = new Date();
                                const diffDays = Math.ceil((tgl - now) / (1000 * 60 * 60 * 24));
                                if (diffDays < 0) {
                                    statusKadaluarsa = 'status-expired';
                                    badgeExtra = `<span class="badge badge-inactive ml-1"><i class="fas fa-exclamation-triangle mr-1"></i>Expired</span>`;
                                } else if (diffDays <= 30) {
                                    statusKadaluarsa = 'status-soon';
                                    badgeExtra = `<span class="badge" style="background:#fef3c7;color:#92400e;">Hampir</span>`;
                                } else {
                                    statusKadaluarsa = 'status-safe';
                                }
                                const formatted = new Date(stok.tglkadaluarsa).toLocaleDateString('id-ID');
                                html += `
                                    <tr>
                                        <td class="font-medium text-gray-900">${escapeHtml(stok.namaproduk)}</td>
                                        <td class="text-gray-700">${escapeHtml(stok.namaruangan)}</td>
                                        <td><span class="batch-badge">${new Intl.NumberFormat().format(stok.qtyproduk)}</span></td>
                                        <td><span class="${statusKadaluarsa}">${formatted}</span> ${badgeExtra}</td>
                                        <td><span class="badge ${stok.statusenabled ? 'badge-active' : 'badge-inactive'}"><i class="fas fa-${stok.statusenabled ? 'check-circle' : 'times-circle'} mr-1"></i>${stok.statusenabled ? 'Aktif' : 'Nonaktif'}</span></td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <button type="button" onclick="window.editData(${stok.norec})" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></button>
                                                <button type="button" onclick="window.deleteData(${stok.norec}, '${escapeHtml(stok.namaproduk)}')" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            } else {
                                html += `
                                    <tr>
                                        <td class="font-medium text-gray-900">${escapeHtml(stok.namaproduk)}</td>
                                        <td class="text-gray-700">${escapeHtml(stok.namaruangan)}</td>
                                        <td><span class="batch-badge">${new Intl.NumberFormat().format(stok.qtyproduk)}</span></td>
                                        <td class="text-gray-400">-</td>
                                        <td><span class="badge ${stok.statusenabled ? 'badge-active' : 'badge-inactive'}"><i class="fas fa-${stok.statusenabled ? 'check-circle' : 'times-circle'} mr-1"></i>${stok.statusenabled ? 'Aktif' : 'Nonaktif'}</span></td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <button type="button" onclick="window.editData(${stok.norec})" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></button>
                                                <button type="button" onclick="window.deleteData(${stok.norec}, '${escapeHtml(stok.namaproduk)}')" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            }
                        });
                        tableBody.innerHTML = html;
                    }

                    currentPage = result.page;
                    totalPages = result.total_pages;
                    totalSpan.textContent = new Intl.NumberFormat().format(result.total_records);
                    currentSpan.textContent = currentPage;
                    totalPagesSpan.textContent = totalPages;

                    const start = Math.min(result.data.length, ((currentPage - 1) * <?= $records_per_page ?>) + 1);
                    const end = Math.min(result.total_records, currentPage * <?= $records_per_page ?>);
                    rangeSpan.textContent = `Menampilkan ${start}-${end} dari ${new Intl.NumberFormat().format(result.total_records)}`;

                    updatePaginationLinks();
                    if (totalPages > 1) pagContainer.classList.remove('hidden');
                    loading.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                loading.classList.add('hidden');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data' });
            }
        };

        function updatePaginationLinks() {
            const container = document.getElementById('paginationLinks');
            if (!container) return;
            let html = '';
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            if (currentPage > 1) {
                html += `<button type="button" onclick="window.changePage(1)" class="pagination-link"><i class="fas fa-angle-double-left"></i></button>`;
                html += `<button type="button" onclick="window.changePage(${currentPage - 1})" class="pagination-link"><i class="fas fa-angle-left"></i></button>`;
            }
            if (startPage > 1) {
                html += `<button type="button" onclick="window.changePage(1)" class="pagination-link">1</button>`;
                if (startPage > 2) html += `<span class="px-2 text-gray-500">...</span>`;
            }
            for (let i = startPage; i <= endPage; i++) {
                html += `<button type="button" onclick="window.changePage(${i})" class="pagination-link ${i === currentPage ? 'active' : ''}">${i}</button>`;
            }
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += `<span class="px-2 text-gray-500">...</span>`;
                html += `<button type="button" onclick="window.changePage(${totalPages})" class="pagination-link">${totalPages}</button>`;
            }
            if (currentPage < totalPages) {
                html += `<button type="button" onclick="window.changePage(${currentPage + 1})" class="pagination-link"><i class="fas fa-angle-right"></i></button>`;
                html += `<button type="button" onclick="window.changePage(${totalPages})" class="pagination-link"><i class="fas fa-angle-double-right"></i></button>`;
            }
            container.innerHTML = html;
        }

        window.changePage = function(page) {
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            document.getElementById('goToPage').value = page;
            window.loadData(page);
            document.querySelector('.table-wrapper').scrollTop = 0;
        };

        window.goToPage = function() {
            const page = parseInt(document.getElementById('goToPage').value);
            if (isNaN(page) || page < 1 || page > totalPages) {
                Swal.fire({ icon: 'error', title: 'Halaman tidak valid', text: `Masukkan angka 1-${totalPages}` });
                return;
            }
            window.changePage(page);
        };

        window.applyFilters = function() {
            currentPage = 1;
            document.getElementById('goToPage').value = 1;
            window.loadData(1);
        };

        window.debouncedSearch = function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(window.applyFilters, 500);
        };

        window.resetFilters = function() {
            document.getElementById('filterForm').reset();
            $('.select2').val('').trigger('change');
            window.applyFilters();
        };

        // Export Excel
        document.getElementById('exportBtn').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('filterForm'));
            const params = new URLSearchParams();
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            params.append('ajax', 'export');
            window.location.href = `<?= basename($_SERVER['PHP_SELF']) ?>?${params.toString()}`;
        });

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addNewBtn').addEventListener('click', () => window.openModal('create'));
            document.getElementById('closeModalBtn').addEventListener('click', window.closeModal);
            document.getElementById('cancelModalBtn').addEventListener('click', window.closeModal);
            document.getElementById('modalOverlay').addEventListener('click', window.closeModal);
            document.getElementById('applyFilterBtn').addEventListener('click', window.applyFilters);
            document.getElementById('resetFilterBtn').addEventListener('click', window.resetFilters);
            document.getElementById('searchInput').addEventListener('input', window.debouncedSearch);
            $('#filterProduk, #filterRuangan, #filterStatus, #filterExpired').on('change', window.applyFilters);

            document.getElementById('crudForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const qty = document.getElementById('modalQty');
                if (parseInt(qty.value) < 0) {
                    Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Quantity tidak boleh negatif' });
                    qty.focus();
                    return;
                }
                const formData = new FormData(this);
                formData.append('ajax', 'save-data');
                try {
                    const response = await fetch('<?= basename($_SERVER['PHP_SELF']) ?>', { method: 'POST', body: formData });
                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: result.message, timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
                        window.closeModal();
                        window.loadData(currentPage);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: result.message || 'Terjadi kesalahan' });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan data' });
                }
            });

            document.getElementById('goToPage')?.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') window.goToPage();
            });
        });
    </script>
</body>
</html>