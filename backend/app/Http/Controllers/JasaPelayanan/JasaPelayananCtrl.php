<?php

namespace App\Http\Controllers\JasaPelayanan;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\Departemen;
use App\Models\Master\GolonganPegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\JenisPagu;
use App\Models\Master\Kebangsaan;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Pendidikan;
use App\Models\Master\Ruangan;
use App\Models\Master\UnitKerjaPegawai;
use App\Models\Transaksi\DetailKelompokPenghasil;
use App\Models\Transaksi\DetailPegawaiPagu;
use App\Models\Transaksi\Jaspel_Noreg;
use App\Models\Transaksi\Jaspel_Layanan;
use App\Models\Transaksi\Jaspel_IBSA;
use App\Models\Transaksi\Jaspel_Obat;
use App\Models\Transaksi\StrukClosing;
use App\Models\Transaksi\StrukDetailPagu;
use App\Models\Transaksi\StrukPagu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;
use Ramsey\Uuid\Uuid;

class JasaPelayananCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getComboIdx()
    {
        $filter = ['statusenabled' => true, 'kdprofile' => $this->kdProfile];

        // $res['pendidikan'] = Pendidikan::where($filter)->select('id', 'pendidikan')->orderBy('pendidikan')->get();
        // $res['jabatan'] = Jabatan::where($filter)->select('id', 'namajabatan')->orderBy('namajabatan')->get();
        // $res['golonganpegawai'] = GolonganPegawai::where($filter)->select('id', 'name as golongan')->orderBy('name')->get();
        // $res['jenispagu'] = JenisPagu::mine()->get();
        // $res['unitkerja'] = UnitKerjaPegawai::where($filter)->select('id', 'name as unitkerja')->orderBy('name')->get();

        $res['ruangan'] = Ruangan::select('namaruangan', 'id')->where($filter)->get();
        $res['kelompokpasien'] = KelompokPasien::mine()->get();
        $res['departemen'] = Departemen::mine()->get();
        $res['carabayar'] = CaraBayar::mine()->get();
        $res['kebangsaan'] = Kebangsaan::mine()->get();

        return $this->respond($res);
    }

    public function getPaguNoreg(Request $request)
    {
        ini_set('max_execution_time', 300);
        $kdProfile = $this->kdProfile;
        $tglawal = $request->tglAwal ? $request->tglAwal . ' 00:00:00' : null;
        $tglakhir = $request->tglAkhir ? $request->tglAkhir . ' 23:59:59' : null;

        // Filter
        $nocm = '';
        $noregis = '';
        $nosep = '';
        $departemen = '';
        $ruangan = '';
        $carabayar = '';
        $kelompokpasien = '';
        $statusverif = '';
        $filtertgl = '';

        if (isset($request->nocm) && $request->nocm != '') {
            $nocm = " AND ps.nocm ILIKE '%" . addslashes($request->nocm) . "%'";
        }
        if (isset($request->noregis) && $request->noregis != '') {
            $values = explode(',', $request->noregis);
            $cleaned = [];
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $cleaned[] = "'" . addslashes($val) . "'";
                }
            }
            if (!empty($cleaned)) {
                $noregis = " AND pd.noregistrasi IN (" . implode(',', $cleaned) . ")";
            }
        }
        if (isset($request->nosep) && $request->nosep != '') {
            $values = explode(',', $request->nosep);
            $cleaned = [];
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $cleaned[] = "'" . addslashes($val) . "'";
                }
            }
            if (!empty($cleaned)) {
                $nosep = " AND pa.nosep IN (" . implode(',', $cleaned) . ")";
            }
        }
        if (isset($request->departemen) && $request->departemen != '') {
            $departemen = " AND dprt.id = " . intval($request->departemen);
        }
        if (isset($request->ruangan) && $request->ruangan != '') {
            $ruangan = " AND r.id = " . intval($request->ruangan);
        }
        if (isset($request->carabayar) && $request->carabayar != '') {
            $carabayar = " AND cb.id = " . intval($request->carabayar);
        }
        if (isset($request->kelompokpasien) && $request->kelompokpasien != '') {
            $kelompokpasien = " AND kp.id = " . intval($request->kelompokpasien);
        }
        if (isset($request->statusverif) && $request->statusverif != '') {
            // $statusverif = " AND jp1.status = " . intval($request->statusverif);
            $status = intval($request->statusverif);

            if ($status != 3) {
                $statusverif = " AND (
                    jp1.status = $status
                    OR
                    jp2.status = $status
                )";
            } else {
                // Filter belum ditindak lanjuti
                $statusverif = " AND (
                    jp1.status is NULL
                    AND
                    jp2.status is NULL
                )";
            }
        }
        if (!empty($tglawal) || !empty($tglakhir)) {
            $filtertgl = "AND COALESCE(pd.tglpulang, pd.tglclosing)::DATE BETWEEN '$tglawal' AND '$tglakhir'";
        }

        $data = db::table(db::raw(
            "
                (
                SELECT DISTINCT
                    --PARAM UPDATE STATUS
                    jp1.id as id_jp1,
                    jp1.status as status1,
                    jp1.alasan_pembatalan as alasan_pembatalan1,
                    jp1.noverifikasi as noverifikasi1,
                    jp2.id as id_jp2,
                    jp2.status as status2,
                    jp2.alasan_pembatalan as alasan_pembatalan2,
                    jp2.noverifikasi as noverifikasi2,

                    sp.norec as norec_sp,
                    sbmc.norec as norec_sbmc,

                    kp.kelompokpasien AS ket,
                    CASE
                        WHEN sbm.nosbm IS NULL THEN sp.nostruk
                        ELSE sbm.nosbm
                    END AS nobukti,
                    pd.tglpulang::DATE AS tanggal,
                    pd.tglpulang::TIME AS jam,
                    sp.tglstruk::TIME AS jam_nobukti,
                    pd.tglregistrasi AS tglreg,
                    pd.noregistrasi AS noreg,
                    ps.nocm AS nrm,
                    ps.namapasien,
                    kp.kelompokpasien AS jenispasien,
                    COALESCE(pa.nokartu, '-')::TEXT AS nokartu,
                    COALESCE(pa.nosep, '-') AS nosep,
                    rk.namarekanan AS nama_customer,
                    RTRIM(ps.alamatlengkap, '\n') AS alamat,
                    sp.totalhargasatuan AS total_billing,
                    COALESCE(sbmc.totaldibayar, 0) AS nilaibayar,
                    pg.namalengkap AS nama_asli,
                    dprt.namadepartemen AS tipeperawatan,
                    CASE
                        WHEN COALESCE(pd.objectstatuspiutangfk, 0) = 1 THEN 1
                        WHEN COALESCE(pd.objectstatuspiutangfk, 0) = 0
                            AND pd.objectkelompokpasienlastfk = 1
                            AND COALESCE(sbmc.totaldibayar, 0) <= 0 THEN 1
                        ELSE 0
                    END AS pasienlost,
                    cb.carabayar AS description
                FROM
                    pasiendaftar_t pd
                JOIN pasien_m ps ON ps.ID = pd.nocmfk
                JOIN ruangan_m r ON r.ID = pd.objectruanganlastfk
                JOIN departemen_m dprt ON dprt.ID = r.objectdepartemenfk
                JOIN kelompokpasien_m kp ON kp.ID = pd.objectkelompokpasienlastfk
                JOIN rekanan_m rk ON rk.ID = pd.objectrekananfk
                LEFT JOIN pemakaianasuransi_t pa ON pa.noregistrasifk = pd.norec
                LEFT JOIN strukpelayanan_t sp ON pd.norec = sp.noregistrasifk
                    AND sp.statusenabled = TRUE
                    AND sp.objectkelompoktransaksifk != 46
                LEFT JOIN strukbuktipenerimaan_t sbm ON sbm.nostrukfk = sp.norec
                    AND sbm.statusenabled = TRUE
                LEFT JOIN strukbuktipenerimaancarabayar_t sbmc ON sbmc.nosbmfk = sbm.norec
                    AND sbmc.statusenabled = TRUE
                LEFT JOIN pegawai_m pg ON pg.ID = sp.objectpegawaipenerimafk
                LEFT JOIN carabayar_m cb ON cb.ID = sbmc.objectcarabayarfk
                -- FK BPJS
                LEFT JOIN jaspel_noreg_t jp1 ON jp1.norec_sp = sp.norec 
                    AND jp1.statusenabled = true
                    AND jp1.norec_sbmc is null
                -- FK Umum
                LEFT JOIN jaspel_noreg_t jp2 ON jp2.norec_sbmc = sbmc.norec 
                    AND jp2.statusenabled = true
                    AND jp2.norec_sbmc is not null
                WHERE
                    pd.statusenabled = TRUE
                    AND sp.totalhargasatuan > 0
                    $filtertgl
                    $nocm
                    $noregis
                    $nosep
                    $departemen
                    $ruangan
                    $carabayar
                    $kelompokpasien
                    $statusverif
                ORDER BY
                    pd.tglpulang::DATE,
                    pd.noregistrasi
                ) xx
                -- Filter menampilkan pasien yang tidak memiliki piutang (0 == false, 1 == true)
                WHERE pasienlost = 0
                ORDER BY
                    noreg
            "
        ))
            ->get();
        return $this->respond($data);
    }

    public function getPaguLayanan(Request $request)
    {
        // ini_set('max_execution_time', 300);
        $kdProfile = $this->kdProfile;
        $tglawal = $request->tglAwal ? $request->tglAwal . ' 00:00:00' : null;
        $tglakhir = $request->tglAkhir ? $request->tglAkhir . ' 23:59:59' : null;
        $tglawaltindakan = $request->tglAwalTindakan ? $request->tglAwalTindakan . ' 00:00:00' : null;
        $tglakhirtindakan = $request->tglAkhirTindakan ? $request->tglAkhirTindakan . ' 23:59:59' : null;

        // Filter
        $nocm = '';
        $noregis = '';
        $nosep = '';
        $departemen = '';
        $ruangan = '';
        $carabayar = '';
        $kelompokpasien = '';
        $kebangsaan = '';
        $tgltindakan = '';
        $dpjp = '';
        $statusverif = '';
        $filtertgl = '';

        if (isset($request->nocm) && $request->nocm != '') {
            $nocm = " AND ps.nocm ILIKE '%" . addslashes($request->nocm) . "%'";
        }
        if (isset($request->noregis) && $request->noregis != '') {
            $values = explode(',', $request->noregis);
            $cleaned = [];
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $cleaned[] = "'" . addslashes($val) . "'";
                }
            }
            if (!empty($cleaned)) {
                $noregis = " AND pd.noregistrasi IN (" . implode(',', $cleaned) . ")";
            }
        }
        if (isset($request->nosep) && $request->nosep != '') {
            $values = explode(',', $request->nosep);
            $cleaned = [];
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $cleaned[] = "'" . addslashes($val) . "'";
                }
            }
            if (!empty($cleaned)) {
                $nosep = " AND pa.nosep IN (" . implode(',', $cleaned) . ")";
            }
        }
        if (isset($request->departemen) && $request->departemen != '') {
            $departemen = " AND dprt.id = " . intval($request->departemen);
        }
        if (isset($request->ruangan) && $request->ruangan != '') {
            $ruangan = " AND ru.id = " . intval($request->ruangan);
        }
        if (isset($request->carabayar) && $request->carabayar != '') {
            $carabayar = " AND cb.id = " . intval($request->carabayar);
        }
        if (isset($request->kelompokpasien) && $request->kelompokpasien != '') {
            $kelompokpasien = " AND kp.id = " . intval($request->kelompokpasien);
        }
        if (isset($request->kebangsaan) && $request->kebangsaan != '') {
            $kebangsaan = " AND kb.id = " . intval($request->kebangsaan);
        }
        if ((isset($tglawaltindakan) && $tglawaltindakan != '') && (isset($tglakhirtindakan) && $tglakhirtindakan != '')) {
            $tgltindakan = "AND COALESCE(pp.tglpelayanan)::date BETWEEN '$tglawaltindakan' AND '$tglakhirtindakan'";
        }
        if (isset($request->dpjp) && $request->dpjp != '') {
            $dpjp = " AND (CASE WHEN dprt1.ID = 9 THEN pg2.ID ELSE pg.ID END) = " . intval($request->dpjp);
        }
        if (isset($request->statusverif) && $request->statusverif != '') {
            $status = intval($request->statusverif);

            if ($status != 3) {
                $statusverif = " AND jp.status = " . $status;
            } else {
                $statusverif = " AND jp.status is NULL ";
            }
        }
        if (!empty($tglawal) || !empty($tglakhir)) {
            $filtertgl = "AND COALESCE(pd.tglpulang, pd.tglclosing)::DATE BETWEEN '$tglawal' AND '$tglakhir'";
        }

        $data = db::table(db::raw(
            "
            (
                SELECT DISTINCT 
                    --PARAM UPDATE STATUS
                    jp.id as id_jp,
                    jp.status,
                    jp.alasan_pembatalan,
                    jp.noverifikasi,
                    pp.norec as norec_pp,

                    pd.noregistrasi AS noreg,
                    sp.nostruk AS nobukti,
                    ps.nocm AS nrm,
                    COALESCE(pa.nosep, '-') AS nosep,
                    CASE
                        WHEN dprt1.ID = 9 THEN
                            RTRIM(RTRIM(jpg2.jenispegawai, '\n') || '-' || pg2.ID, '\n')
                        ELSE
                            RTRIM(RTRIM(jpg.jenispegawai, '\n') || '-' || pg.ID, '\n')
                    END AS iddokter,
                    CASE
                        WHEN dprt1.ID = 9 THEN pg2.namalengkap
                        ELSE pg.namalengkap
                    END AS dokterdpjp,
                    RTRIM(RTRIM(jpg1.jenispegawai, '\n') || '-' || pg1.ID, '\n') AS idpelaksana,
                    pg1.namalengkap AS namapelaksana,
                    ps.namapasien,
                    pr.namaproduk AS jasaname,
                    ru.namaruangan AS sectionname,
                    CASE
                        WHEN ps.objectkebangsaanfk NOT IN (1) THEN
                            CASE
                                WHEN kp.ID IN (2) THEN kp.kelompokpasien || '-' || kb.NAME
                                ELSE kb.NAME
                            END
                        ELSE kp.kelompokpasien
                    END AS jeniskerjasama,
                    pp.tglpelayanan::DATE AS tgltindakan,
                    COALESCE(pd.tglpulang, pd.tglclosing)::DATE AS tglpulang,
                    pp.jumlah,
                    pp.hargasatuan AS tarif,
                    COALESCE(xyz.jasapelayanan, 0) AS jasapelayanan,
                    COALESCE(xyz.jasasarana, 0) AS jasasarana,
                    pp.hargasatuan - (COALESCE(xyz.jasapelayanan, 0) + COALESCE(xyz.jasasarana, 0)) AS selisih,
                    '-' AS luar,
                    CASE
                        WHEN dprt.ID IN (16) THEN 'Pasien Ranap'::VARCHAR(30)
                        ELSE '-'::VARCHAR(30)
                    END AS keterangan,
                    COALESCE(pp.jumlah, 0) * COALESCE(pp.hargasatuan, 0) AS total,
                    pp.norec

                FROM pelayananpasien_t pp
                JOIN antrianpasiendiperiksa_t apd ON apd.norec = pp.noregistrasifk
                JOIN pasiendaftar_t pd ON pd.norec = apd.noregistrasifk
                LEFT JOIN strukpelayanan_t sp ON sp.norec = pp.strukfk AND sp.statusenabled = true
                JOIN kelompokpasien_m kp ON kp.id = pd.objectkelompokpasienlastfk
                JOIN pasien_m ps ON ps.id = pd.nocmfk
                JOIN produk_m pr ON pr.id = pp.produkfk
                LEFT JOIN strukorder_t so ON so.noregistrasifk = pd.norec AND so.norec = pp.strukorderfk

                LEFT JOIN (
                    SELECT 
                        pelayananpasien, 
                        objectpegawaifk, 
                        statusenabled,
                        ROW_NUMBER() OVER (PARTITION BY pelayananpasien ORDER BY created_at DESC) AS row_num
                    FROM pelayananpasienpetugas_t ppx
                ) ppp ON ppp.pelayananpasien = pp.norec AND ppp.row_num = 1

                LEFT JOIN (
                    SELECT 
                        xx.hnp_id, xx.produkid, xx.hargasatuan, xx.jasasarana, xx.jasapelayanan,
                        ROW_NUMBER() OVER (PARTITION BY xx.produkid, xx.hargasatuan ORDER BY xx.produkid ASC) AS row_num
                    FROM (
                        SELECT 
                            hnp.id AS hnp_id,
                            hnpd.id AS hnpd_93,
                            hnpd2.id AS hnpd_94,
                            pr.id AS produkid,
                            pr.namaproduk,
                            hnp.hargasatuan,
                            hnpd.hargasatuan AS jasasarana,
                            hnpd2.hargasatuan AS jasapelayanan,
                            hnp.hargasatuan - (COALESCE(hnpd.hargasatuan, 0) + COALESCE(hnpd2.hargasatuan, 0)) AS selisih
                        FROM harganettoprodukbykelas_m hnp
                        JOIN produk_m pr ON pr.id = hnp.objectprodukfk
                        LEFT JOIN harganettoprodukbykelasd_m hnpd 
                            ON hnpd.objectharganettoprodukfk = hnp.id 
                            AND hnpd.objectkomponenhargafk = 93 
                            AND hnpd.statusenabled = true
                        LEFT JOIN harganettoprodukbykelasd_m hnpd2 
                            ON hnpd2.objectharganettoprodukfk = hnp.id 
                            AND hnpd2.objectkomponenhargafk = 94 
                            AND hnpd2.statusenabled = true
                        WHERE hnp.statusenabled = true
                    ) xx
                ) xyz ON xyz.produkid = pp.produkfk AND xyz.hargasatuan = pp.hargasatuan AND xyz.row_num = 1

                LEFT JOIN pegawai_m pg ON pg.id = COALESCE(apd.objectpegawaifk, pd.objectpegawaifk)
                LEFT JOIN pegawai_m pg1 ON pg1.id = COALESCE(pp.pelayananpegawaifk, ppp.objectpegawaifk)
                LEFT JOIN pegawai_m pg2 ON pg2.id = pd.objectpegawaifk_dod

                LEFT JOIN ruangan_m ru ON ru.id = apd.objectruanganfk
                LEFT JOIN kebangsaan_m kb ON kb.id = ps.objectkebangsaanfk
                LEFT JOIN pemakaianasuransi_t pa ON pa.noregistrasifk = pd.norec
                LEFT JOIN ruangan_m rus ON rus.id = pd.objectruanganlastfk
                LEFT JOIN departemen_m dprt ON dprt.id = rus.objectdepartemenfk
                LEFT JOIN departemen_m dprt1 ON dprt1.id = ru.objectdepartemenfk
                LEFT JOIN jenispegawai_m jpg ON jpg.id = pg.objectjenispegawaifk AND jpg.statusenabled = true
                LEFT JOIN jenispegawai_m jpg1 ON jpg1.id = pg1.objectjenispegawaifk AND jpg1.statusenabled = true
                LEFT JOIN jenispegawai_m jpg2 ON jpg2.id = pg2.objectjenispegawaifk AND jpg2.statusenabled = true
                LEFT JOIN jaspel_layanan_t jp ON jp.norec_pp = pp.norec AND jp.statusenabled = true

                WHERE pp.statusenabled = true 
                AND pd.statusenabled = true
                AND apd.objectruanganfk NOT IN (363) -- ibsa
                AND pp.strukresepfk IS NULL
                $filtertgl
                $nocm
                $noregis
                $nosep
                $departemen
                $ruangan
                $carabayar
                $kelompokpasien
                $kebangsaan
                $tgltindakan
                $dpjp
                $statusverif
            ) xx
            ORDER BY noreg;
            "
        ))
            ->get();



        return $this->respond($data);
    }
    public function getPaguIbsa(Request $request)
    {
        // ini_set('max_execution_time', 300);
        $kdProfile = $this->kdProfile;
        $tglawal = $request->tglAwal ? $request->tglAwal . ' 00:00:00' : null;
        $tglakhir = $request->tglAkhir ? $request->tglAkhir . ' 23:59:59' : null;
        $tglawaltindakan = $request->tglAwalTindakan ? $request->tglAwalTindakan . ' 00:00:00' : null;
        $tglakhirtindakan = $request->tglAkhirTindakan ? $request->tglAkhirTindakan . ' 23:59:59' : null;

        // Filter
        $nocm = '';
        $noregis = '';
        $nosep = '';
        $departemen = '';
        $ruangan = '';
        $carabayar = '';
        $kelompokpasien = '';
        $kebangsaan = '';
        $dpjp = '';
        $tgltindakan = '';
        $produk = '';
        $statusverif = '';
        $filtertgl = '';

        if (isset($request->nocm) && $request->nocm != '') {
            $nocm = " AND ps.nocm ILIKE '%" . addslashes($request->nocm) . "%'";
        }
        if (isset($request->noregis) && $request->noregis != '') {
            $values = explode(',', $request->noregis);
            $cleaned = [];
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $cleaned[] = "'" . addslashes($val) . "'";
                }
            }
            if (!empty($cleaned)) {
                $noregis = " AND pd.noregistrasi IN (" . implode(',', $cleaned) . ")";
            }
        }
        if (isset($request->nosep) && $request->nosep != '') {
            $values = explode(',', $request->nosep);
            $cleaned = [];
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $cleaned[] = "'" . addslashes($val) . "'";
                }
            }
            if (!empty($cleaned)) {
                $nosep = " AND pa.nosep IN (" . implode(',', $cleaned) . ")";
            }
        }
        if (isset($request->departemen) && $request->departemen != '') {
            $departemen = " AND dprt.id = " . intval($request->departemen);
        }
        if (isset($request->ruangan) && $request->ruangan != '') {
            $ruangan = " AND ru.id = " . intval($request->ruangan);
        }
        if (isset($request->carabayar) && $request->carabayar != '') {
            $carabayar = " AND cb.id = " . intval($request->carabayar);
        }
        if (isset($request->kelompokpasien) && $request->kelompokpasien != '') {
            $kelompokpasien = " AND kp.id = " . intval($request->kelompokpasien);
        }
        if (isset($request->kebangsaan) && $request->kebangsaan != '') {
            $kebangsaan = " AND kb.id = " . intval($request->kebangsaan);
        }
        if ((isset($tglawaltindakan) && $tglawaltindakan != '') && (isset($tglakhirtindakan) && $tglakhirtindakan != '')) {
            $tgltindakan = "AND COALESCE(pp.tglpelayanan)::date BETWEEN '$tglawaltindakan' AND '$tglakhirtindakan'";
        }
        if (isset($request->dpjp) && $request->dpjp != '') {
            $dpjp = " AND pg.id = " . intval($request->dpjp);
        }
        if (isset($request->produk) && $request->produk != '') {
            $produk = " AND pr.id = " . intval($request->produk);
        }
        if (isset($request->statusverif) && $request->statusverif != '') {
            $status = intval($request->statusverif);

            if ($status != 3) {
                $statusverif = " AND jp.status = " . $status;
            } else {
                $statusverif = " AND jp.status is NULL ";
            }
        }
        if (!empty($tglawal) || !empty($tglakhir)) {
            $filtertgl = "AND COALESCE(pd.tglpulang, pd.tglclosing)::DATE BETWEEN '$tglawal' AND '$tglakhir'";
        }

        $data = db::table(db::raw(
            "
            (
            SELECT DISTINCT
            --PARAM UPDATE STATUS
            jp.id as id_jp,
            jp.status,
            jp.alasan_pembatalan,
            jp.noverifikasi,
            pp.norec as norec_pp,

            pd.noregistrasi AS noreg,
            sp.nostruk AS nobukti,
            ps.nocm AS nrm,
            COALESCE ( pa.nosep, '-' ) AS nosep,
            RTRIM( RTRIM( jpg.jenispegawai, '\n' ) || '-' || pg.ID, '\n' ) AS iddokter,
            pg.namalengkap AS dokterdpjp,
            RTRIM( RTRIM( jpg1.jenispegawai, '\n' ) || '-' || pg1.ID, '\n' ) AS idpelaksana,
            pg1.namalengkap AS namapelaksana,
            ps.namapasien,
            pr.namaproduk AS jasaname,
            ru.namaruangan AS sectionname,
            CASE

            WHEN ps.objectkebangsaanfk NOT IN ( 1 ) THEN
            CASE

            WHEN kp.ID IN ( 2 ) THEN
            kp.kelompokpasien || '-' || kb.NAME ELSE kb.NAME
            END ELSE kp.kelompokpasien
            END AS jeniskerjasama,
            pp.tglpelayanan :: DATE AS tgltindakan,
            pd.tglpulang :: DATE,
            pp.jumlah,
            pp.hargasatuan,
            COALESCE ( xyz.jasapelayanan, 0 ) AS jasapelayanan,
            COALESCE ( xyz.jasasarana, 0 ) AS jasasarana,
            pp.hargasatuan - ( COALESCE ( xyz.jasapelayanan, 0 ) + COALESCE ( xyz.jasasarana, 0 ) ) AS selisih,
            '-' AS luar,
            CASE

            WHEN dprt.ID IN ( 16 ) THEN
            'Pasien Ranap' ELSE'-'
            END AS keterangan,
            pg_op1.namalengkap AS dokteranak,
            pg_op2.namalengkap AS dokteranastesi,
            pg_op3.namalengkap AS dokteroperator1,
            pg_op4.namalengkap AS dokterasisten,
            pg_op5.namalengkap AS dokteroperator3,
            CASE

            WHEN pp.isasa0 = TRUE THEN
            'NON ASA'
            WHEN PP.isasa1 = 1 THEN
            'ASA I'
            WHEN pp.isasa2 = 1 THEN
            'ASA II'
            WHEN pp.isasa3 = 1 THEN
            'ASA III'
            WHEN pp.isasa4 = TRUE THEN
            'ASA IV' ELSE NULL
            END AS asa,
            pp.norec,
            COALESCE ( pp.jumlah, 0 ) * COALESCE ( pp.hargasatuan, 0 ) AS total


            from pelayananpasien_t pp
            join antrianpasiendiperiksa_t apd on apd.norec = pp.noregistrasifk
            join pasiendaftar_t pd on pd.norec = apd.noregistrasifk
            join strukpelayanan_t sp on sp.norec = pp.strukfk and sp.statusenabled = true
            join kelompokpasien_m kp on kp.id = pd.objectkelompokpasienlastfk
            join pasien_m ps on ps.id = pd.nocmfk
            join produk_m pr on pr.id = pp.produkfk
            left join strukorder_t so on so.noregistrasifk = pd.norec and so.norec = pp.strukorderfk

            left join (
            SELECT xx.hnp_id ,xx.produkid, xx.hargasatuan, xx.jasasarana, xx.jasapelayanan,
            ROW_NUMBER() OVER (PARTITION BY xx.produkid,xx.hargasatuan ORDER BY xx.produkid asc) AS row_num
            from (
            SELECT hnp.id as hnp_id, hnpd.id as hnpd_93, hnpd2.id as hnpd_94,pr.id as
            produkid,pr.namaproduk,hnp.hargasatuan,
            hnpd.hargasatuan as jasasarana, hnpd2.hargasatuan as jasapelayanan,
            hnp.hargasatuan - (COALESCE(hnpd.hargasatuan,0) + COALESCE(hnpd2.hargasatuan,0)) as selisih

            from harganettoprodukbykelas_m hnp
            join produk_m pr on pr.id = hnp.objectprodukfk
            left join harganettoprodukbykelasd_m hnpd on hnpd.objectharganettoprodukfk = hnp.id and
            hnpd.objectkomponenhargafk=93 and hnpd.statusenabled=true
            left join harganettoprodukbykelasd_m hnpd2 on hnpd2.objectharganettoprodukfk = hnp.id and
            hnpd2.objectkomponenhargafk=94 and hnpd2.statusenabled=true
            WHERE hnp.statusenabled = true
            ) xx

            ) xyz on xyz.produkid = pp.produkfk and xyz.hargasatuan = pp.hargasatuan and xyz.row_num = 1



            LEFT JOIN (
            SELECT pelayananpasien, objectpegawaifk,statusenabled,
            ROW_NUMBER() OVER (PARTITION BY pelayananpasien ORDER BY created_at DESC) AS row_num
            FROM pelayananpasienpetugas_t ppx -- where ppx.pelayananpasien='c2a380c6-99d6-4a1c-a82a-3773854be526'
            ) ppp ON ppp.pelayananpasien = pp.norec and ppp.row_num = 1

            LEFT JOIN (
            SELECT pelayananpasien, objectpegawaifk,
            objectoperator1fk,
            objectoperator2fk,
            objectoperator3fk,
            objectoperator4fk,
            objectoperator5fk,
            statusenabled,
            ROW_NUMBER() OVER (PARTITION BY pelayananpasien ORDER BY created_at DESC) AS row_num
            FROM pelayananpasienpetugas_t ppx where objectoperator3fk is not null
            ) pppx ON pppx.pelayananpasien = pp.norec and pppx.row_num = 1

            left JOIN pegawai_m pg on pg.id = COALESCE(apd.objectpegawaifk,pd.objectpegawaifk)
            left join pegawai_m pg1 on pg1.id = COALESCE(pp.pelayananpegawaifk,ppp.objectpegawaifk)
            left join ruangan_m ru on ru.id = apd.objectruanganfk
            left join kebangsaan_m kb on kb.id = ps.objectkebangsaanfk
            left join pemakaianasuransi_t pa on pa.noregistrasifk = pd.norec
            left join ruangan_m rus on rus.id = pd.objectruanganlastfk
            left join departemen_m dprt on dprt.id = rus.objectdepartemenfk
            left join jenispegawai_m jpg on jpg.id = pg.objectjenispegawaifk and jpg.statusenabled = true
            left join jenispegawai_m jpg1 on jpg1.id = pg1.objectjenispegawaifk and jpg1.statusenabled = true
            left join pegawai_m pg_op1 on pg_op1.id = pppx.objectoperator1fk --dokter anak
            left join pegawai_m pg_op2 on pg_op2.id = pppx.objectoperator2fk --dokter anastesi
            left join pegawai_m pg_op3 on pg_op3.id = pppx.objectoperator3fk --dokter operator 1
            left join pegawai_m pg_op4 on pg_op4.id = pppx.objectoperator4fk --dokter asisten
            left join pegawai_m pg_op5 on pg_op5.id = pppx.objectoperator5fk --dokter operator 3
            LEFT JOIN jaspel_ibsa_t jp ON jp.norec_pp = pp.norec AND jp.statusenabled = true

            where pp.statusenabled=true and pd.statusenabled = true
            and apd.objectruanganfk=363
            and pp.strukresepfk is null
            $filtertgl
            $nocm
            $noregis
            $nosep
            $departemen
            $ruangan
            $kelompokpasien
            $kebangsaan
            $tgltindakan
            $dpjp
            $produk
            $statusverif

            )xxxx
            order by noreg;
            "
        ))
            ->get();

        return $this->respond($data);
    }

    public function getPaguObat(Request $request)
    {
        // ini_set('max_execution_time', 300);
        $kdProfile = $this->kdProfile;
        $tglawal = $request->tglAwal ? $request->tglAwal . ' 00:00:00' : null;
        $tglakhir = $request->tglAkhir ? $request->tglAkhir . ' 23:59:59' : null;
        $tglawaltindakan = $request->tglAwalTindakan ? $request->tglAwalTindakan . ' 00:00:00' : null;
        $tglakhirtindakan = $request->tglAkhirTindakan ? $request->tglAkhirTindakan . ' 23:59:59' : null;

        // Filter
        $nocm = '';
        $noregis = '';
        $nosep = '';
        $departemen = '';
        $ruangan = '';
        $carabayar = '';
        $kelompokpasien = '';
        $kebangsaan = '';
        $dpjp = '';
        $tgltindakan = '';
        $produk = '';
        $statusverif = '';
        $filtertgl = '';

        if (isset($request->nocm) && $request->nocm != '') {
            $nocm = " AND ps.nocm ILIKE '%" . addslashes($request->nocm) . "%'";
        }
        if (isset($request->noregis) && $request->noregis != '') {
            $values = explode(',', $request->noregis);
            $cleaned = [];
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $cleaned[] = "'" . addslashes($val) . "'";
                }
            }
            if (!empty($cleaned)) {
                $noregis = " AND pd.noregistrasi IN (" . implode(',', $cleaned) . ")";
            }
        }
        if (isset($request->nosep) && $request->nosep != '') {
            $values = explode(',', $request->nosep);
            $cleaned = [];
            foreach ($values as $val) {
                $val = trim($val);
                if ($val !== '') {
                    $cleaned[] = "'" . addslashes($val) . "'";
                }
            }
            if (!empty($cleaned)) {
                $nosep = " AND pa.nosep IN (" . implode(',', $cleaned) . ")";
            }
        }
        if (isset($request->departemen) && $request->departemen != '') {
            $departemen = " AND dprt.id = " . intval($request->departemen);
        }
        if (isset($request->ruangan) && $request->ruangan != '') {
            $ruangan = " AND ru.id = " . intval($request->ruangan);
        }
        if (isset($request->carabayar) && $request->carabayar != '') {
            $carabayar = " AND cb.id = " . intval($request->carabayar);
        }
        if (isset($request->kelompokpasien) && $request->kelompokpasien != '') {
            $kelompokpasien = " AND kp.id = " . intval($request->kelompokpasien);
        }
        if (isset($request->kebangsaan) && $request->kebangsaan != '') {
            $kebangsaan = " AND kb.id = " . intval($request->kebangsaan);
        }
        if ((isset($tglawaltindakan) && $tglawaltindakan != '') && (isset($tglakhirtindakan) && $tglakhirtindakan != '')) {
            $tgltindakan = "AND COALESCE(pp.tglpelayanan)::date BETWEEN '$tglawaltindakan' AND '$tglakhirtindakan'";
        }
        if (isset($request->dpjp) && $request->dpjp !== '') {
            $dpjp = " AND (pg.id = " . intval($request->dpjp) . " OR pg2.id = " . intval($request->dpjp) . ")";
        }
        if (isset($request->produk) && $request->produk != '') {
            $produk = " AND pr.id = " . intval($request->produk);
        }
        if (isset($request->statusverif) && $request->statusverif != '') {
            $status = intval($request->statusverif);

            if ($status != 3) {
                $statusverif = " AND jp.status = " . $status;
            } else {
                $statusverif = " AND jp.status is NULL ";
            }
        }
        if (!empty($tglawal) || !empty($tglakhir)) {
            $filtertgl = "AND COALESCE(pd.tglpulang, pd.tglclosing)::DATE BETWEEN '$tglawal' AND '$tglakhir'";
        }

        $data = db::table(db::raw(
            "
            (
            SELECT DISTINCT
            --PARAM UPDATE STATUS
            jp.id as id_jp,
            jp.status,
            jp.alasan_pembatalan,
            jp.noverifikasi,
            pp.norec as norec_pp,

            sp.nostruk AS nobukti,
            sr.noresep,
            pp.produkfk AS barang_id,
            kb.name AS ktp,
            pa.nosep,
            CASE

            WHEN ps.objectkebangsaanfk NOT IN ( 1 ) THEN
            CASE

            WHEN kp.ID IN ( 2 ) THEN
            kp.kelompokpasien || '-' || kb.NAME ELSE kb.NAME
            END ELSE kp.kelompokpasien
            END AS jeniskerjasama,
            pp.tglpelayanan AS tanggal,
            pr.kdproduk AS kode_barang,
            pr.namaproduk AS nama_barang,
            pp.jumlah AS qty,
            COALESCE ( pp.harganetto, 0 ) AS hargaorig,
            COALESCE ( pp.hargasatuan, 0 ) AS hargajual,
            pd.noregistrasi AS noreg,
            CASE

            WHEN jeniskemasanfk = 1 THEN
            '1' ELSE '0'
            END AS jeniskemasan,
            ru.namaruangan AS sectionname,
            COALESCE ( pg.namalengkap, pg2.namalengkap ) AS namadokter,
            RTRIM(
            RTRIM( COALESCE ( jpg.jenispegawai, jpg2.jenispegawai ), '\n' ) || '-' || COALESCE ( pg.ID, pg2.ID ),
            '\n'
            ) AS iddokter,
            COALESCE ( pd.tglpulang, pd.tglclosing ) :: DATE AS tglpulang,
            COALESCE ( pp.jasa, 0 ) AS jasa,
            COALESCE ( pp.hargadiscount, 0 ) AS discount,
            ROUND(
            (
            ( COALESCE ( pp.hargasatuan, 0 ) - COALESCE ( pp.hargadiscount, 0 ) ) * COALESCE ( pp.jumlah, 1 )
            ) + COALESCE ( pp.jasa, 0 )
            ) AS total,
            pp.norec

            from pelayananpasien_t pp
            join antrianpasiendiperiksa_t apd on apd.norec = pp.noregistrasifk
            join pasiendaftar_t pd on pd.norec = apd.noregistrasifk
            left join strukpelayanan_t sp on sp.norec = pp.strukfk and sp.statusenabled = true
            join strukresep_t sr on sr.norec = pp.strukresepfk
            join kelompokpasien_m kp on kp.id = pd.objectkelompokpasienlastfk
            join pasien_m ps on ps.id = pd.nocmfk
            join produk_m pr on pr.id = pp.produkfk
            left join strukorder_t so on so.noregistrasifk = pd.norec and so.norec = pp.strukorderfk
            left join pelayananpasienpetugas_t ppp on ppp.pelayananpasien = pp.norec

            left join pegawai_m pg on pg.id = case when pp.strukorderfk is null then
            COALESCE(apd.objectpegawaifk,pd.objectpegawaifk) else COALESCE(so.objectpegawaiorderfk, pd.objectpegawaifk)
            end
            left join pegawai_m pg1 on pg1.id = case when pp.strukorderfk is null then pp.pelayananpegawaifk else
            ppp.objectpegawaifk end
            left join pegawai_m pg2 on pg2.id = COALESCE(ppp.objectpegawaifk,pp.pelayananpegawaifk)
            left join ruangan_m ru on ru.id = apd.objectruanganfk
            left join kebangsaan_m kb on kb.id = ps.objectkebangsaanfk
            left join pemakaianasuransi_t pa on pa.noregistrasifk = pd.norec
            left join ruangan_m rus on rus.id = pd.objectruanganlastfk
            left join departemen_m dprt on dprt.id = rus.objectdepartemenfk

            left join jenispegawai_m jpg on jpg.id = pg.objectjenispegawaifk and jpg.statusenabled = true
            left join jenispegawai_m jpg1 on jpg1.id = pg1.objectjenispegawaifk and jpg1.statusenabled = true
            left join jenispegawai_m jpg2 on jpg2.id = pg2.objectjenispegawaifk and jpg2.statusenabled = true
            LEFT JOIN jaspel_obat_t jp ON jp.norec_pp = pp.norec AND jp.statusenabled = true

            where pp.statusenabled=true and pd.statusenabled = true
            and pp.strukresepfk is not null
            $filtertgl
            $departemen
            $ruangan
            $kelompokpasien
            $kebangsaan
            $dpjp
            $tgltindakan
            $nosep
            $statusverif

            ) as xx
             order by noreg;
            "
        ))
            ->get();

        return $this->respond($data);

    }

    public function updateStatusJaspel(Request $r)
    {
        DB::beginTransaction();
        $error = null;
        $fk = null;
        $noverifikasi = null;

        try {
            if (empty($r['jenis'])) {
                throw new Exception("Terjadi kesalahan!");
            }

            switch ($r['jenis']) {
                case 'NOREG':
                    $model = Jaspel_Noreg::class;
                    $fk = 'norec_sp';
                    $noverifikasi = 'JPN-';
                    break;
                case 'LAYANAN':
                    $model = Jaspel_Layanan::class;
                    $fk = 'norec_pp';
                    $noverifikasi = 'JPL-';
                    break;
                case 'IBSA':
                    $model = Jaspel_IBSA::class;
                    $fk = 'norec_pp';
                    $noverifikasi = 'JPI-';
                    break;
                case 'OBAT':
                    $model = Jaspel_Obat::class;
                    $fk = 'norec_pp';
                    $noverifikasi = 'JPO-';
                    break;
                default:
                    $fk = null;
                    $model = null;
                    break;
            }

            if (!empty($model)) {
                // Dijadikan log
                if (!empty($r['id_jp'])) {
                    $model::where('id', $r['id_jp'])->update(['statusenabled' => false]);
                }
                $data = new $model;
            }

            // Relasi antar table jaspel dengan table utama
            if ($fk == 'norec_sp') {
                $data->norec_sp = $r['norec_sp'];
                $data->norec_sbmc = !empty($r['norec_sbmc']) ? $r['norec_sbmc'] : null;
            } else if ($fk == 'norec_pp') {
                $data->norec_pp = $r['norec_pp'];
            }

            $data->status = $r['status'];
            $data->catatan = !empty($r['catatan']) ? $r['catatan'] : null;
            $data->alasan_pembatalan = !empty($r['alasan_pembatalan']) ? $r['alasan_pembatalan'] : null;
            $data->noverifikasi = $r['status'] == 2 ? $r['noverifikasi'] :$this->SEQUENCE($model, 'noverifikasi', 14, $noverifikasi . date('ym'), $this->kdProfile);
            $data->statusenabled = true;
            $data->pegawaifk = $this->getPegawai()->id;
            $data->save();

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $error = $e->getMessage() . ' ' . $e->getLine();
        }

        // Execute
        if ($transStatus == 'true') {
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Update Status Berhasil!"
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Error",
                "result" => $error
            );
        }

        return $this->respond($result);
    }

    public function updateStatusJaspelAll(Request $r)
    {
        /*
            Sementara hanya all verifikasi saja, belum batal verifikasi. 
            Karena jika batal verifikasi harus ngecek tablenya dulu.
            Kalo datanya banyak, pasti ngelag.
        */

        DB::beginTransaction();
        $error = null;
        $fk = null;
        $timestamp = now();
        $jenis = !empty($r['data']) && count($r['data']) != 0 ? $r['data'][0]['jenis'] : null;

        if (empty($jenis)) {
            throw new Exception("Terjadi kesalahan!");
        }

        switch ($jenis) {
            case 'NOREG':
                $model = Jaspel_Noreg::class;
                $fk = 'norec_sp';
                $noverifikasiPrefix = 'JPN-';
                break;
            case 'LAYANAN':
                $model = Jaspel_Layanan::class;
                $fk = 'norec_pp';
                $noverifikasiPrefix = 'JPL-';
                break;
            case 'IBSA':
                $model = Jaspel_IBSA::class;
                $fk = 'norec_pp';
                $noverifikasiPrefix = 'JPI-';
                break;
            case 'OBAT':
                $model = Jaspel_Obat::class;
                $fk = 'norec_pp';
                $noverifikasiPrefix = 'JPO-';
                break;
            default:
                throw new Exception("Jenis tidak valid.");
        }

        $prefix = $noverifikasiPrefix . date('ym');

        try {
            $dataInsert = [];
            foreach ($r['data'] as $item) {
                $row = [
                    $fk => $item[$fk] ?? null,
                    'status' => $item['status'],
                    'catatan' => $item['catatan'] ?? null,
                    'alasan_pembatalan' => $item['alasan_pembatalan'] ?? null,
                    'noverifikasi' => $this->SEQUENCE($model, 'noverifikasi', 14, $prefix, $this->kdProfile),
                    'statusenabled' => true,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                    'pegawaifk' => $this->getPegawai()->id
                ];

                // Tambahkan 'norec_sbmc' jika jenis jaspel noreg
                if ($jenis == 'NOREG') {
                    $row['norec_sbmc'] = $item['norec_sbmc'] ?? null;
                }

                $dataInsert[] = $row;
            }

            $model::insert($dataInsert);
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $error = $e->getMessage() . ' ' . $e->getLine();
        }

        // Execute
        if ($transStatus == 'true') {
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Update Status Berhasil!"
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Error",
                "result" => $error
            );
        }

        return $this->respond($result);
    }
}
