<?php

namespace App\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\JenisLaporan;
use App\Models\Master\KelompokLaporan;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Master\SlottingKiosk;
use App\Models\Transaksi\LoggingUser;
use App\Models\Transaksi\PasienDaftar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanRekamMedisCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function getDataRL31RawatInapNew(Request $request)
    {
        $idProfile = $this->kdProfile;

        $kdRanap = $this->settingFix('kdDepartemenRanapFix');


        $idStatKelMeninggal = (int) $this->settingFix('kdMeninggal');
        $idKondisiPasienMeninggal = $this->settingFix('idKondisiPasienMeninggal');
        $kodeRS = $this->settingFix('kodeRS');
        $kodeProv = $this->settingFix('kodeProv');
        $getKota = $this->settingFix('getKota');
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $tahun = new \DateTime($tglAkhir);
        $tahun = date('Y');
        $datetime1 = new \DateTime($tglAwal);
        $datetime2 = new \DateTime($tglAkhir);
        $interval = $datetime1->diff($datetime2);
        $sehari = 1; //$interval->format('%d');
        $data10 = [];
        // Query untuk jumlah tempat tidur per ruangan
        $jumlahTTPerRuangan = DB::select(DB::raw("
        SELECT 
            CASE 
                WHEN ru.id = '318' THEN 'ICU'
                WHEN ru.id = '320' THEN 'PICU/NICU'
                WHEN ru.id IN ('319','317') THEN 'Intensif lainnya'
                ELSE 'Non Intensif' 
            END AS kategori_ruangan,
            COUNT(tt.id) AS jumlah_tempat_tidur
        FROM tempattidur_m AS tt
        INNER JOIN kamar_m AS kmr ON kmr.id = tt.objectkamarfk
        INNER JOIN ruangan_m AS ru ON ru.id = kmr.objectruanganfk
        WHERE tt.kdprofile = $idProfile
        AND tt.statusenabled = true
        AND kmr.statusenabled = true
        AND tt.reportdisplay NOT ILIKE '%cadangan%'
        AND ru.statusenabled = true
        GROUP BY kategori_ruangan
        "));

        // Jika tidak ada tempat tidur, kembalikan data kosong
        if (count($jumlahTTPerRuangan) == 0) {
        return [[
            'kategori_ruangan' => 'Tidak Ada Data',
            'lamarawat' => 0,
            'hariperawatan' => 0,
            'pasienpulang' => 0,
            'meninggal' => 0,
            'matilebih48' => 0,
            'bor' => 0,
            'alos' => 0,
            'bto' => 0,
            'toi' => 0,
            'gdr' => 0,
            'ndr' => 0,
        ]];
        }

        // Query untuk hari perawatan per ruangan
        $hariPerawatan = DB::select(DB::raw("
        SELECT 
            CASE 
                WHEN ru.id = '318' THEN 'ICU'
                WHEN ru.id = '320' THEN 'PICU/NICU'
                WHEN ru.id IN ('319','317') THEN 'Intensif lainnya'
                ELSE 'Non Intensif' 
            END AS kategori_ruangan,
            COUNT(pd.noregistrasi) AS jumlahhariperawatan
        FROM pasiendaftar_t AS pd
        INNER JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
        WHERE ru.objectdepartemenfk IN (16,35)
        AND pd.kdprofile = $idProfile
        AND pd.statusenabled = true
        AND ru.statusenabled = true
        AND ((pd.tglregistrasi < '$tglAwal' AND pd.tglpulang >= '$tglAkhir') OR pd.tglpulang IS NULL)
        GROUP BY kategori_ruangan
        "));

        // Query untuk lama rawat dan pasien pulang per ruangan
        $lamaRawat = DB::select(DB::raw("
        SELECT 
            CASE 
                WHEN ru.id = '318' THEN 'ICU'
                WHEN ru.id = '320' THEN 'PICU/NICU'
                WHEN ru.id IN ('319','317') THEN 'Intensif lainnya'
                ELSE 'Non Intensif' 
            END AS kategori_ruangan,
            SUM(DATE_PART('DAY', pd.tglpulang - pd.tglregistrasi)) AS lamarawat,
            COUNT(pd.noregistrasi) AS jumlahpasienpulang
        FROM pasiendaftar_t AS pd
        INNER JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
        WHERE pd.kdprofile = $idProfile
        AND pd.tglpulang BETWEEN '$tglAwal' AND '$tglAkhir'
        AND pd.tglpulang IS NOT NULL
        AND pd.statusenabled = true
        AND ru.objectdepartemenfk IN (16,35)
        AND ru.statusenabled = true
        GROUP BY kategori_ruangan
        "));

        // Query untuk jumlah pasien meninggal dan lebih dari 48 jam per ruangan
        $dataMeninggal = DB::select(DB::raw("
        SELECT 
            CASE 
                WHEN ru.id = '318' THEN 'ICU'
                WHEN ru.id = '320' THEN 'PICU/NICU'
                WHEN ru.id IN ('319','317') THEN 'Intensif lainnya'
                ELSE 'Non Intensif' 
            END AS kategori_ruangan,
            COUNT(x.noregistrasi) AS jumlahmeninggal,
            COUNT(CASE WHEN x.objectkondisipasienfk = $idKondisiPasienMeninggal THEN 1 END) AS jumlahlebih48
        FROM (
            SELECT 
                pd.noregistrasi,
                pd.objectruanganlastfk,
                pd.objectkondisipasienfk
            FROM pasiendaftar_t AS pd
            INNER JOIN statuskeluar_m ON statuskeluar_m.id = pd.objectstatuskeluarfk
            LEFT JOIN kondisipasien_m ON kondisipasien_m.id = pd.objectkondisipasienfk
            WHERE pd.kdprofile = $idProfile
            AND pd.objectstatuskeluarfk = $idStatKelMeninggal
            AND pd.tglregistrasi BETWEEN '$tglAwal' AND '$tglAkhir'
            AND pd.statusenabled = true
        ) AS x
        INNER JOIN ruangan_m AS ru ON ru.id = x.objectruanganlastfk
        WHERE ru.statusenabled = true
        GROUP BY kategori_ruangan
        "));

        // Proses perhitungan data
        $data10 = [];
        foreach ($jumlahTTPerRuangan as $ruangan) {
        $kategoriRuangan = $ruangan->kategori_ruangan;
        $jumlahTempatTidur = $ruangan->jumlah_tempat_tidur;

        $hariPerawatanJml = collect($hariPerawatan)->firstWhere('kategori_ruangan', $kategoriRuangan)->jumlahhariperawatan ?? 0;
        $lamaRawatJumlah = collect($lamaRawat)->firstWhere('kategori_ruangan', $kategoriRuangan)->lamarawat ?? 0;
        $jumlahPasienPulang = collect($lamaRawat)->firstWhere('kategori_ruangan', $kategoriRuangan)->jumlahpasienpulang ?? 0;
        $jumlahMeninggal = collect($dataMeninggal)->firstWhere('kategori_ruangan', $kategoriRuangan)->jumlahmeninggal ?? 0;
        $jumlahLebih48 = collect($dataMeninggal)->firstWhere('kategori_ruangan', $kategoriRuangan)->jumlahlebih48 ?? 0;

        // Perhitungan indikator
        $bor = ($hariPerawatanJml * 100) / ($jumlahTempatTidur * $sehari);
        $alos = $jumlahPasienPulang > 0 ? $lamaRawatJumlah / $jumlahPasienPulang : 0;
        $bto = $jumlahPasienPulang / $jumlahTempatTidur;
        $toi = $jumlahPasienPulang > 0 ? (($jumlahTempatTidur * $sehari) - $hariPerawatanJml) / $jumlahPasienPulang : 0;
        $gdr = ($jumlahMeninggal * 1000) / max($jumlahPasienPulang, 1);
        $ndr = ($jumlahLebih48 * 1000) / max($jumlahPasienPulang, 1);

        $data10[] = [
            'kategori_ruangan' => $kategoriRuangan,
            'lamarawat' => $lamaRawatJumlah,
            'hariperawatan' => $hariPerawatanJml,
            'pasienpulang' => $jumlahPasienPulang,
            'meninggal' => $jumlahMeninggal,
            'matilebih48' => $jumlahLebih48,
            'bor' => number_format($bor, 2),
            'alos' => number_format($alos, 2),
            'bto' => number_format($bto, 2),
            'toi' => number_format($toi, 2),
            'gdr' => number_format($gdr, 2),
            'ndr' => number_format($ndr, 2),
        ];
        }


        $result = array(
            'data' => $data10,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getDataRL31RawatInap(Request $request)
    {

        $deptId = explode(',', $this->settingFix('KdListDepartemen'));
        $KdJenisLaporan = $this->settingFix('KdJenisLapRanap');
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];

        $data = DB::table('antrianpasiendiperiksa_t as app')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->join('asalrujukan_m as ar', 'ar.id', '=', 'app.objectasalrujukanfk')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'app.objectruanganfk')
            ->join('ruangan_m as ru1', 'ru1.id', '=', 'app.objectruanganasalfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            // ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'app.norec')
            // ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.ruanganfk', '=', 'pd.objectruanganlastfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'app.objectkelasfk')
            ->leftjoin('statuskeluar_m as sk', 'sk.id', '=', 'pd.objectstatuskeluarfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->select(
                'pd.tglregistrasi',
                'pm.nocm',
                'pd.noregistrasi',
                'pm.namapasien',
                'app.objectasalrujukanfk',
                'ar.asalrujukan',
                'ru.namaruangan',
                'ru1.namaruangan as ruanganasal',
                'ru1.objectdepartemenfk as deptasal',
                'dpm.namadepartemen',
                'pg.namalengkap',
                'pm.objectjeniskelaminfk',
                'ru.objectdepartemenfk',
                'pd.objectstatuskeluarfk as objectstatuskeluarfk',
                'pd.objectkelasfk as objectkelasfk',
                'kl.kelompoklaporan as jenis_spesialisasi',
                'jl.jenislaporan',
                // 'pr.id as produkfk',
                DB::raw(
                    "
                    CASE
                        WHEN pd.tglpulang IS NULL THEN
                        DATE_PART('day', now() - pd.tglregistrasi::timestamp)
                        ELSE
                        DATE_PART('day', pd.tglpulang::TIMESTAMP - pd.tglregistrasi::timestamp)
                        END AS lamadirawat,
                        to_char( pd.tglregistrasi,'MM') as bulanregistrasi,
                        to_char( pd.tglregistrasi,'DD') as hariregistrasi,
                        to_char( pd.tglregistrasi,'YYYY-MM-DD') as tglregistrasi"
                )

            )
            ->where('app.kdprofile', $this->kdProfile)
            ->whereRaw("pd.tglregistrasi::date between '$tglAwal' and '$tglAkhir'")
            ->whereIn('dpm.id', $deptId)
            ->where('jl.id', $KdJenisLaporan);

        $data = $data->get();

        $data10 = [];
        $jml = 0;
        $sama = false;

        $pasienAwalBulan = 0;
        $pasienMasuk = 0;
        $pasienDipindahkan = 0;
        $pasienKeluarHidup = 0;
        $matilebih48jaml = 0;
        $matilebih48jamp = 0;
        $matikurang48jaml = 0;
        $matikurang48jamp = 0;
        $jumlahLamaDirawat = 0;
        $pasienAkhirBulan = 0;
        $jumlahHariDirawat = 0;
        $rincianVvip = 0;
        $rincianVip = 0;
        $rincianKel1 = 0;
        $rincianKel2 = 0;
        $rincianKel3 = 0;
        $rincianKelasKhusus = 0;
        $DiTerimaKembali = 0;


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenis_spesialisasi == $data10[$i]['jenis_spesialisasi']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    $data10[$i]['lamadirawat'] += $item->lamadirawat;
                    $data10[$i]['jumlahharidirawat'] += $item->lamadirawat;

                    if (date('d', strtotime($item->tglregistrasi)) == '01') {
                        $data10[$i]['pasienawalbulan'] = (float)$hideung['pasienawalbulan'] + 1;
                    } else if (date('d', strtotime($item->tglregistrasi)) == date('t', strtotime($item->tglregistrasi))) {
                        $data10[$i]['pasienakhirbulan'] = (float)$hideung['pasienakhirbulan'] + 1;
                    } else if ($item->objectdepartemenfk == 16 || $item->objectdepartemenfk == 25) {
                        $data10[$i]['pasienmasuk'] = (float)$hideung['pasienmasuk'] + 1;
                    } 
                    
                    if ($item->objectstatuskeluarfk != 5) {
                        $data10[$i]['pasienkeluarhidup'] = (float)$hideung['pasienkeluarhidup'] + 1;
                    } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat < 48 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['matikurang48jaml'] = (float)$hideung['matikurang48jaml'] + 1;
                    } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat < 48 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['matikurang48jamp'] = (float)$hideung['matikurang48jamp'] + 1;
                    } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat > 48 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['matilebih48jaml'] = (float)$hideung['matilebih48jaml'] + 1;
                    } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat > 48 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['matilebih48jamp'] = (float)$hideung['matilebih48jamp'] + 1;
                    }

                    if($item->deptasal == 16 && $item->objectdepartemenfk == 16){
                        $data10[$i]['pasiendipindahkan'] = (float)$hideung['pasiendipindahkan'] + 1;
                    }

                    if ($item->objectkelasfk == 5) {
                        $data10[$i]['rincianvvip'] = (float)$hideung['rincianvvip'] + 1;
                    } elseif ($item->objectkelasfk == 4) {
                        $data10[$i]['rincianvip'] = (float)$hideung['rincianvip'] + 1;
                    } elseif ($item->objectkelasfk == 3) {
                        $data10[$i]['rinciankel1'] = (float)$hideung['rinciankel1'] + 1;
                    } elseif ($item->objectkelasfk == 2) {
                        $data10[$i]['rinciankel2'] = (float)$hideung['rinciankel2'] + 1;
                    } elseif ($item->objectkelasfk == 1) {
                        $data10[$i]['rinciankel3'] = (float)$hideung['rinciankel3'] + 1;
                    } elseif ($item->objectkelasfk == 6 && $item->objectkelasfk == 8 ) {
                        $data10[$i]['rinciankelaskhusus'] = (float)$hideung['rinciankelaskhusus'] + 1;
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if (date('d', strtotime($item->tglregistrasi)) == '01') {
                    $pasienAwalbulan = 1;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } else if (date('d', strtotime($item->tglregistrasi)) == date('t', strtotime($item->tglregistrasi))) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 1;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } else if ($item->objectdepartemenfk == 16 || $item->objectdepartemenfk == 25) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 1;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } else if ($item->objectdepartemenfk == 16 || $item->deptasal == 16) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 1;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectstatuskeluarfk != 5) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 1;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat < 48 && $item->objectjeniskelaminfk == 1) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 1;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat < 48 && $item->objectjeniskelaminfk == 2) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 1;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat > 48 && $item->objectjeniskelaminfk == 1) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 1;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat > 48 && $item->objectjeniskelaminfk == 2) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 1;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                }
                if ($item->objectkelasfk == 8) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 1;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 5) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 1;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 3) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 1;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 2) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 1;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 1) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 1;
                    $rincianKelasKhusus = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 6 && $item->objectkelasfk == 8) {
                    $pasienAwalbulan = 0;
                    $pasienMasuk = 0;
                    $pasienDipindahkan = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jaml = 0;
                    $matilebih48jamp = 0;
                    $matikurang48jaml = 0;
                    $matikurang48jamp = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirbulan = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVvip = 0;
                    $rincianVip = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelasKhusus = 1;
                    $DiTerimaKembali = 0;
                }

                $data10[] = array(
                    'jenis_spesialisasi' => $item->jenis_spesialisasi,
                    'pasienmasuk' => $pasienMasuk,
                    'pasiendipindahkan' => $pasienDipindahkan,
                    'lamadirawat' => $jumlahLamaDirawat,
                    'pasienawalbulan' => $pasienAwalBulan,
                    'pasienakhirbulan' => $pasienAkhirBulan,
                    'pasienkeluarhidup' => $pasienKeluarHidup,
                    'matilebih48jaml' => $matilebih48jaml,
                    'matilebih48jamp' => $matilebih48jamp,
                    'matikurang48jaml' => $matikurang48jaml,
                    'matikurang48jamp' => $matikurang48jamp,
                    'jumlahlamadirawat' => $jumlahLamaDirawat,
                    // 'pasienakhirtahun' => $pasienAkhirTahun,
                    'jumlahharidirawat' => $jumlahHariDirawat,
                    'rincianvvip' => $rincianVvip,
                    'rincianvip' => $rincianVip,
                    'rinciankel1' => $rincianKel1,
                    'rinciankel2' => $rincianKel2,
                    'rinciankel3' => $rincianKel3,
                    'rinciankelaskhusus' => $rincianKelasKhusus,
                    'jumlah' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLaporan and statusenabled = true order by kodeexternal::int4 asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data10 as $dt){
                if($sp->kelompoklaporan == $dt['jenis_spesialisasi']){
                    $sama = true;
                    $pasienMasuk = $dt['pasienmasuk'];
                    $pasienDipindahkan = $dt['pasiendipindahkan'];
                    $lamadirawat = $dt['lamadirawat'];
                    $pasienAwalBulan = $dt['pasienawalbulan'];
                    $pasienAkhirBulan = $dt['pasienakhirbulan'];
                    $pasienKeluarHidup = $dt['pasienkeluarhidup'];
                    $matilebih48jaml = $dt['matilebih48jaml'];
                    $matilebih48jamp = $dt['matilebih48jamp'];
                    $matikurang48jaml = $dt['matikurang48jaml'];
                    $matikurang48jamp = $dt['matikurang48jamp'];
                    $jumlahLamaDirawat = $dt['jumlahlamadirawat'];
                    $jumlahHariDirawat = $dt['jumlahharidirawat'];
                    $rincianVvip = $dt['rincianvvip'];
                    $rincianVip = $dt['rincianvip'];
                    $rincianKel1 = $dt['rinciankel1'];
                    $rincianKel2 = $dt['rinciankel2'];
                    $rincianKel3 = $dt['rinciankel3'];
                    $rincianKelasKhusus = $dt['rinciankelaskhusus'];
                } 
            }

            if($sama == false){
                $pasienMasuk = 0;
                $pasienDipindahkan = 0;
                $lamadirawat = 0;
                $pasienAwalBulan = 0;
                $pasienAkhirBulan = 0;
                $pasienKeluarHidup = 0;
                $matilebih48jaml = 0;
                $matilebih48jamp = 0;
                $matikurang48jaml = 0;
                $matikurang48jamp = 0;
                $jumlahLamaDirawat = 0;
                $jumlahHariDirawat = 0;
                $rincianVvip = 0;
                $rincianVip = 0;
                $rincianKel1 = 0;
                $rincianKel2 = 0;
                $rincianKel3 = 0;
                $rincianKelasKhusus = 0;
            }

            $data10fix[] = array(
                'jenis_spesialisasi' => $sp->kelompoklaporan,
                'pasienmasuk' => $pasienMasuk,
                'pasiendipindahkan' => $pasienDipindahkan,
                'lamadirawat' => $lamadirawat,
                'pasienawalbulan' => $pasienAwalBulan,
                'pasienakhirbulan' => $pasienAkhirBulan,
                'pasienkeluarhidup' => $pasienKeluarHidup,
                'matilebih48jaml' => $matilebih48jaml,
                'matilebih48jamp' => $matilebih48jamp,
                'matikurang48jaml' => $matikurang48jaml,
                'matikurang48jamp' => $matikurang48jamp,
                'jumlahlamadirawat' => $jumlahLamaDirawat,
                'jumlahharidirawat' => $jumlahHariDirawat,
                'rincianvvip' => $rincianVvip,
                'rincianvip' => $rincianVip,
                'rinciankel1' => $rincianKel1,
                'rinciankel2' => $rincianKel2,
                'rinciankel3' => $rincianKel3,
                'rinciankelaskhusus' => $rincianKelasKhusus,
                'jumlah' => 0,
            );
        }


        $result = array(
            'data' => $data10fix,
            'message' => 'as@siayu',
        );

        return $this->respond($result);
    } 

    public function getComboMappingRL()
    {

        $result['ruanganRanap'] = Ruangan::mine()->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->get();


        $result['ruanganRajal'] = Ruangan::mine()->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('objectdepartemenfk', explode(',', $this->settingFix('idDepartemenRajal')))
            ->get();

        $result['caraBayar'] = CaraBayar::mine()->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();
        $result['jenisLaporan'] = JenisLaporan::mine()->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();
        $result['kelompokLaporan'] = KelompokLaporan::mine()->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();

        return $this->respond($result);
    }

    public function getProdukMapLaporanRL(Request $request)
    {

        $kdPelayanan = explode(',', $this->settingFix('KdlistPelayanan'));
        $dataProduk = DB::table('produk_m as pro')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pro.objectdetailjenisprodukfk')
            ->leftJoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pro.id as idproduk',
                'pro.namaproduk',
                'djp.id as iddetail',
                'djp.detailjenisproduk',
                'jp.id as idjenis',
                'jp.jenisproduk',
                'jp.objectkelompokprodukfk',
                'kp.kelompokproduk'
            )
            ->where('pro.kdprofile', $this->kdProfile)
            // ->whereIn('jp.objectkelompokprodukfk', $kdPelayanan)
            ->where('pro.statusenabled', true)
            ->orderBy('pro.namaproduk');
        if (isset($request['namaproduk'])) {
            $dataProduk = $dataProduk->where('pro.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        };

        $dataProduk = $dataProduk->limit($request['limit']);
        $dataProduk = $dataProduk->get();


        return $this->respond($dataProduk);
    }

    public function getLaporanRL4aRawatInap(Request $request)
    {
        $kdDeptRanapAll = explode(',', $this->settingFix('KdDepartemenRIAll'));
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as app', 'app.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->leftjoin('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->leftjoin('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->leftjoin('diagnosabantuan_m as dbn', 'dbn.kddiagnosa', '=', 'dm.kddiagnosa')
            ->leftjoin('diagnosadtd_m as ddtd', 'ddtd.nodtd', '=', 'dbn.nodtd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->leftjoin('mappingrlmorbiditas_m as mpr', 'mpr.kddiagnosa', '=', 'dm.kddiagnosa')
            ->select(
                'pd.tglregistrasi',
                'app.statuspenyakit',
                'dbn.nodtd',
                'dm.kddiagnosa',
                'ps.tgllahir as tglLahir',
                'ps.objectjeniskelaminfk',
                'pd.objectstatuskeluarfk',
                DB::raw('EXTRACT(YEAR from AGE(pd.tglregistrasi, ps.tgllahir)) as umuryear,
                                EXTRACT(MONTH from AGE(pd.tglregistrasi, ps.tgllahir)) as umurmonth,
                                EXTRACT(DAY from AGE(pd.tglregistrasi, ps.tgllahir)) as umurday,
                                lower(ddtd.golongansebabpenyakit) as golongansebabpenyakit
                       ')
            )
            ->whereIn('dpm.id', $kdDeptRanapAll)
            ->distinct();

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('pd.tglregistrasi', '<=', $tgl);
        }
        $data = $data->orderBy('dm.kddiagnosa');
        $data = $data->get();

        $data10 = [];
        $sama = false;
        $jml = 0;
        $jml6HariL = 0;
        $jml6HariP = 0;
        $jml28HariL = 0;
        $jml28HariP = 0;
        $jml1ThnL = 0;
        $jml1ThnP = 0;
        $jml4ThnL = 0;
        $jml4ThnP = 0;
        $jml14ThnL = 0;
        $jml14ThnP = 0;
        $jml24ThnL = 0;
        $jml24ThnP = 0;
        $jml44ThnL = 0;
        $jml44ThnP = 0;
        $jml64ThnL = 0;
        $jml64ThnP = 0;
        $jml65ThnL = 0;
        $jml65ThnP = 0;
        $totalMenurutL = 0;
        $totalMenurutP = 0;
        $totalKasusBaru = 0;
        $totalKunjungan = 0;
        $jmlPL = 0;

        $totalMenurutP = 0;
        $totalMenurutL = 0;
        $jmlKeluarMati = 0;
        $jmlKeluarHidup = 0;
        $statusKd = true;

        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;

            foreach ($data10 as $hideung) {
                if ($item->nodtd == $data10[$i]['nodtd']) {
                    $sama = true;
                    $jml = (float)$hideung['totalAll'] + 1;
                    $data10[$i]['totalAll'] = $jml;
                    $statusKd = false;

                    if (str_contains($data10[$i]['kddiagnosa'], $item->kddiagnosa)) {
                        $statusKd = true;
                    }
                    if ($statusKd == false) {
                        $data10[$i]['kddiagnosa'] = $data10[$i]['kddiagnosa'] . ',' . $item->kddiagnosa;
                    }

                    //Laki =1 && Perempuan=2
                    if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml6HariL'] = (float)$hideung['jml6HariL'] + 1;
                    } else if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml6HariP'] = (float)$hideung['jml6HariP'] + 1;
                    } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml28HariL'] = (float)$hideung['jml28HariL'] + 1;
                    } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml28HariP'] = (float)$hideung['jml28HariP'] + 1;
                    } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml1ThnL'] = (float)$hideung['jml1ThnL'] + 1;
                    } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml1ThnP'] = (float)$hideung['jml1ThnP'] + 1;
                    } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml4ThnL'] = (float)$hideung['jml4ThnL'] + 1;
                    } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml4ThnP'] = (float)$hideung['jml4ThnP'] + 1;
                    } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml14ThnL'] = (float)$hideung['jml14ThnL'] + 1;
                    } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml14ThnP'] = (float)$hideung['jml14ThnP'] + 1;
                    } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml24ThnL'] = (float)$hideung['jml24ThnL'] + 1;
                    } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml24ThnP'] = (float)$hideung['jml24ThnP'] + 1;
                    } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml44ThnL'] = (float)$hideung['jml44ThnL'] + 1;
                    } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml44ThnP'] = (float)$hideung['jml44ThnP'] + 1;
                    } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml64ThnL'] = (float)$hideung['jml64ThnL'] + 1;
                    } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml64ThnP'] = (float)$hideung['jml64ThnP'] + 1;
                    } else if ($item->umuryear >= 65  && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml65ThnL'] = (float)$hideung['jml65ThnL'] + 1;
                    } else if ($item->umuryear >= 65 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml65ThnP'] = (float)$hideung['jml65ThnP'] + 1;
                    }

                    if ($item->objectstatuskeluarfk == 5) {
                        $data10[$i]['jmlKeluarMati'] = (float)$hideung['jmlKeluarMati'] + 1;
                    }
                    if ($item->objectstatuskeluarfk != 5) {
                        $data10[$i]['jmlKeluarHidup'] = (float)$hideung['jmlKeluarHidup'] + 1;
                    }

                    $data10[$i]['jmlPL'] = $data10[$i]['jml6HariL'] + $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariL'] + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnL'] + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnL'] + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnL'] + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnL'] + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnL'] + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnL'] + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnL'] + $data10[$i]['jml65ThnP'];
                    $data10[$i]['totalMenurutL'] = $data10[$i]['jml6HariL']
                        + $data10[$i]['jml28HariL']
                        + $data10[$i]['jml1ThnL']
                        + $data10[$i]['jml4ThnL']
                        + $data10[$i]['jml14ThnL']
                        + $data10[$i]['jml24ThnL']
                        + $data10[$i]['jml44ThnL']
                        + $data10[$i]['jml64ThnL']
                        + $data10[$i]['jml65ThnL'];
                    $data10[$i]['totalMenurutP'] = $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnP'];
                }
                $i = $i + 1;
            }

            //jika false
            if ($sama == false) {
                if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 1;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 1;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 1) {
                    $jml28HariL = 1;
                    $jml6HariL = 1;
                    $jml6HariP = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 1;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 1;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 1;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 1;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 1;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 1;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 1;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 1;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 1;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 1;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 1;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 1;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 1;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 65  && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 1;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 65 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 1;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                }
                if ($item->objectstatuskeluarfk == 5) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 1;
                    $jmlKeluarHidup = 0;
                }
                if ($item->objectstatuskeluarfk != 5) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 1;
                }



                $data10[] = array(
                    'kddiagnosa' => $item->kddiagnosa,
                    'nodtd' => $item->nodtd,
                    'golongansebabpenyakit' => $item->golongansebabpenyakit,
                    'tglregistrasi' => $item->tglregistrasi,
                    'statuspenyakit' => $item->statuspenyakit,
                    'tglLahir' => $item->tglLahir,
                    'objectstatuskeluarfk' => $item->objectstatuskeluarfk,
                    'umuryear' => $item->umuryear,
                    'umurmonth' => $item->umurmonth,
                    'umurday' => $item->umurday,
                    'totalAll' => 1,
                    'jml6HariL' =>  $jml6HariL,
                    'jml6HariP' =>  $jml6HariP,
                    'jml28HariL' =>  $jml28HariL,
                    'jml28HariP' =>  $jml28HariP,
                    'jml1ThnL' =>  $jml1ThnL,
                    'jml1ThnP' =>  $jml1ThnP,
                    'jml4ThnL' =>  $jml4ThnL,
                    'jml4ThnP' =>  $jml4ThnP,
                    'jml14ThnL' =>  $jml14ThnL,
                    'jml14ThnP' =>  $jml14ThnP,
                    'jml24ThnL' =>  $jml24ThnL,
                    'jml24ThnP' =>  $jml24ThnP,
                    'jml44ThnL' =>  $jml44ThnL,
                    'jml44ThnP' =>  $jml44ThnP,
                    'jml64ThnL' =>  $jml64ThnL,
                    'jml64ThnP' =>  $jml64ThnP,
                    'jml65ThnL' =>  $jml65ThnL,
                    'jml65ThnP' =>  $jml65ThnP,
                    'totalMenurutL' => $totalMenurutL,
                    'jmlKeluarMati' => $jmlKeluarMati,
                    'jmlKeluarHidup' => $jmlKeluarHidup,
                    'totalMenurutP' => $totalMenurutP,
                    'totalHidupMati' => $jmlKeluarHidup + $jmlKeluarMati,
                    'jmlPL' => $totalMenurutL + $totalMenurutP,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['nodtd'];
            }

            array_multisort($count, SORT_ASC, $data10);
        }

        $result = array(
            'data' => $data10,
            'dept' => $kdDeptRanapAll,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL4bRawatJalan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $KdDeptRajalRehab = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $kdDepartemenRajalRehab = [];
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as app', 'app.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->leftjoin('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->leftjoin('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->leftjoin('diagnosabantuan_m as dbn', 'dbn.kddiagnosa', '=', 'dm.kddiagnosa')
            ->leftjoin('diagnosadtd_m as ddtd', 'ddtd.nodtd', '=', 'dbn.nodtd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('mappingrlmorbiditas_m as mpr', 'mpr.kddiagnosa', '=', 'dm.kddiagnosa')
            ->select(
                'pd.tglregistrasi',
                'app.statuspenyakit',
                'dbn.nodtd',
                'dm.kddiagnosa',
                'ps.tgllahir as tglLahir',
                'ps.objectjeniskelaminfk',
                DB::raw('EXTRACT(YEAR from AGE(pd.tglregistrasi, ps.tgllahir)) as umuryear,
                                EXTRACT(MONTH from AGE(pd.tglregistrasi, ps.tgllahir)) as umurmonth,
                                EXTRACT(DAY from AGE(pd.tglregistrasi, ps.tgllahir)) as umurday,lower(ddtd.golongansebabpenyakit) as golongansebabpenyakit
                       ')
            )
            ->where('pd.kdprofile', $kdProfile)
            ->whereIn('dpm.id', $KdDeptRajalRehab)
            ->distinct();


        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('pd.tglregistrasi', '<=', $tgl);
        }
        $data = $data->orderBy('dm.kddiagnosa');
        $data = $data->get();

        $data10 = [];
        $sama = false;
        $jml = 0;
        $jml6HariL = 0;
        $jml6HariP = 0;
        $jml28HariL = 0;
        $jml28HariP = 0;
        $jml1ThnL = 0;
        $jml1ThnP = 0;
        $jml4ThnL = 0;
        $jml4ThnP = 0;
        $jml14ThnL = 0;
        $jml14ThnP = 0;
        $jml24ThnL = 0;
        $jml24ThnP = 0;
        $jml44ThnL = 0;
        $jml44ThnP = 0;
        $jml64ThnL = 0;
        $jml64ThnP = 0;
        $jml65ThnL = 0;
        $jml65ThnP = 0;
        $jmlKasusBaruL = 0;
        $jmlKasusBaruP = 0;
        $totalKasusBaru = 0;
        $totalKunjungan = 0;
        $totalMenurutUmur = 0;
        $totalMenurutL = 0;
        $totalMenurutP = 0;
        $jmlPL = 0;

        $statusKd = true;

        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;

            foreach ($data10 as $hideung) {
                if ($item->nodtd == $data10[$i]['nodtd']) {
                    $sama = true;
                    $jml = (float)$hideung['totalAll'] + 1;
                    $data10[$i]['totalAll'] = $jml;
                    $statusKd = false;

                    if (str_contains($data10[$i]['kddiagnosa'], $item->kddiagnosa)) {
                        $statusKd = true;
                    }
                    if ($statusKd == false) {
                        $data10[$i]['kddiagnosa'] = $data10[$i]['kddiagnosa'] . ',' . $item->kddiagnosa;
                    }

                    //Laki =1 && Perempuan=2
                    if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml6HariL'] = (float)$hideung['jml6HariL'] + 1;
                    } else if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml6HariP'] = (float)$hideung['jml6HariP'] + 1;
                    } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml28HariL'] = (float)$hideung['jml28HariL'] + 1;
                    } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml28HariP'] = (float)$hideung['jml28HariP'] + 1;
                    } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml1ThnL'] = (float)$hideung['jml1ThnL'] + 1;
                    } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml1ThnP'] = (float)$hideung['jml1ThnP'] + 1;
                    } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml4ThnL'] = (float)$hideung['jml4ThnL'] + 1;
                    } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml4ThnP'] = (float)$hideung['jml4ThnP'] + 1;
                    } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml14ThnL'] = (float)$hideung['jml14ThnL'] + 1;
                    } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml14ThnP'] = (float)$hideung['jml14ThnP'] + 1;
                    } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml24ThnL'] = (float)$hideung['jml24ThnL'] + 1;
                    } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml24ThnP'] = (float)$hideung['jml24ThnP'] + 1;
                    } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml44ThnL'] = (float)$hideung['jml44ThnL'] + 1;
                    } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml44ThnP'] = (float)$hideung['jml44ThnP'] + 1;
                    } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml64ThnL'] = (float)$hideung['jml64ThnL'] + 1;
                    } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml64ThnP'] = (float)$hideung['jml64ThnP'] + 1;
                    } else if ($item->umuryear >= 65  && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml65ThnL'] = (float)$hideung['jml65ThnL'] + 1;
                    } else if ($item->umuryear >= 65 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml65ThnP'] = (float)$hideung['jml65ThnP'] + 1;
                    }
                    if ($item->statuspenyakit == 'BARU'  && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jmlKasusBaruL'] = (float)$hideung['jmlKasusBaruL'] + 1;
                    } else if ($item->statuspenyakit == 'BARU' && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jmlKasusBaruP'] = (float)$hideung['jmlKasusBaruP'] + 1;
                    }
                    $data10[$i]['totalKasusBaru'] = $data10[$i]['jmlKasusBaruP'] + $data10[$i]['jmlKasusBaruL'];

                    $data10[$i]['jmlPL'] = $data10[$i]['jml6HariL'] + $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariL'] + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnL'] + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnL'] + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnL'] + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnL'] + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnL'] + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnL'] + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnL'] + $data10[$i]['jml65ThnP'];
                    $data10[$i]['totalMenurutL'] = $data10[$i]['jml6HariL']
                        + $data10[$i]['jml28HariL']
                        + $data10[$i]['jml1ThnL']
                        + $data10[$i]['jml4ThnL']
                        + $data10[$i]['jml14ThnL']
                        + $data10[$i]['jml24ThnL']
                        + $data10[$i]['jml44ThnL']
                        + $data10[$i]['jml64ThnL']
                        + $data10[$i]['jml65ThnL'];
                    $data10[$i]['totalMenurutP'] = $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnP'];
                    $data10[$i]['totalKasusBaru'] = $data10[$i]['jmlKasusBaruP'] + $data10[$i]['jmlKasusBaruL'];

                    $data10[$i]['totalMenurutUmur'] = $data10[$i]['jml6HariL'] + $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariL'] + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnL'] + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnL'] + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnL'] + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnL'] + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnL'] + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnL'] + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnL'] + $data10[$i]['jml65ThnP'];
                }
                $i = $i + 1;
            }

            //jika false
            if ($sama == false) {
                if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 1;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 1;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 1;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 1;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 1;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 1;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 1;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 1;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 1;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 1;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 1;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 1;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 1;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 1;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 1;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 1;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 65  && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 1;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 65 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 1;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                }

                if ($item->statuspenyakit == 'BARU'  && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 1;
                    $jmlKasusBaruP = 0;
                } else if ($item->statuspenyakit == 'BARU' && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 1;
                }

                $data10[] = array(
                    'kddiagnosa' => $item->kddiagnosa,
                    'nodtd' => $item->nodtd,
                    'golongansebabpenyakit' => $item->golongansebabpenyakit,
                    'tglregistrasi' => $item->tglregistrasi,
                    'statuspenyakit' => $item->statuspenyakit,
                    'tglLahir' => $item->tglLahir,
                    'umuryear' => $item->umuryear,
                    'umurmonth' => $item->umurmonth,
                    'umurday' => $item->umurday,
                    'totalAll' => 1,
                    'jml6HariL' =>  $jml6HariL,
                    'jml6HariP' =>  $jml6HariP,
                    'jml28HariL' =>  $jml28HariL,
                    'jml28HariP' =>  $jml28HariP,
                    'jml1ThnL' =>  $jml1ThnL,
                    'jml1ThnP' =>  $jml1ThnP,
                    'jml4ThnL' =>  $jml4ThnL,
                    'jml4ThnP' =>  $jml4ThnP,
                    'jml14ThnL' =>  $jml14ThnL,
                    'jml14ThnP' =>  $jml14ThnP,
                    'jml24ThnL' =>  $jml24ThnL,
                    'jml24ThnP' =>  $jml24ThnP,
                    'jml44ThnL' =>  $jml44ThnL,
                    'jml44ThnP' =>  $jml44ThnP,
                    'jml64ThnL' =>  $jml64ThnL,
                    'jml64ThnP' =>  $jml64ThnP,
                    'jml65ThnL' =>  $jml65ThnL,
                    'jml65ThnP' =>  $jml65ThnP,
                    'jmlKasusBaruL' => $jmlKasusBaruL,
                    'jmlKasusBaruP' => $jmlKasusBaruP,
                    'totalKasusBaru' => $totalKasusBaru,
                    'totalMenurutUmur' => $totalMenurutUmur,
                    'totalMenurutL' => $totalMenurutL,
                    'totalMenurutP' => $totalMenurutP,
                    'jmlPL' => $jmlPL,

                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['nodtd'];
            }

            array_multisort($count, SORT_ASC, $data10);
        }

        $result = array(
            'data' => $data10,
            'dept' => $KdDeptRajalRehab,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL32RawatDarurat(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $kdDeptIGD = (int) $this->settingFix('idDepartemenIGD');
        $KdJenisLapIGD = (int) $this->settingFix('KdJenisLapIGD');
        $ruanganIGD = (int) $this->settingFix('kdRuanganIGD');

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->leftjoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->leftjoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('asalrujukan_m as ar', 'ar.id', '=', 'apd.objectasalrujukanfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->select(
                'pd.objectruanganasalfk as objectruanganasalfk ',
                'pd.objectruanganlastfk as objectruanganlastfk',
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'pr.objectdetailjenisprodukfk',
                'djp.detailjenisproduk',
                'apd.objectasalrujukanfk',
                'ar.asalrujukan',
                'pd.objectstatuskeluarfk',
                'kl.kelompoklaporan as jenispelayanan',
                'pm.objectjeniskelaminfk',
                'pd.objectkondisipasienfk',
                'pd.norec as norec_pd'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('dpm.id', $kdDeptIGD)
            ->where('pd.statusenabled', true)
            ->whereIn('pd.objectruanganasalfk', [322, 323])
            ->where('jl.id', $KdJenisLapIGD)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();

            $isGadar = DB::connection('mongodb')
                    ->table('AsesmenAwalMedisGawatDarurat')
                    ->select('Parameter_GawatDarurat', 'registrasi.norec_pd', 'created_at', 'updated_at')
                    ->whereNotNull('Parameter_GawatDarurat')
                    ->whereNull('namatemplate')
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('created_at', [$request['tglAwal'], $request['tglAkhir']]);
                        
                        // Add optional updated_at condition
                        $query->orWhereBetween('updated_at', [$request['tglAwal'], $request['tglAkhir']]);
                    })
                    ->get()
                    ->keyBy('registrasi.norec_pd');
             
        $data10 = [];
        $jml = 0;
        $rujukan = 0;
        $nonrujukan = 0;
        $dirawat = 0;
        $dirujuk = 0;
        $pulang = 0;
        $mati = 0;
        $doal = 0;
        $doap = 0;
        $tidakGawatDaruratCount = 0;
        $sama = false;


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenispelayanan == $data10[$i]['jenispelayanan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    if ($item->objectasalrujukanfk == 5) {
                        $data10[$i]['nonrujukan'] = (float)$hideung['nonrujukan'] + 1;
                    } else if ($item->objectasalrujukanfk != 5) {
                        $data10[$i]['rujukan'] = (float)$hideung['rujukan'] + 1;
                    }
                    if ($item->objectruanganlastfk != $ruanganIGD) {
                        $data10[$i]['dirawat'] = (float)$hideung['dirawat'] + 1;
                    } else if ($item->objectstatuskeluarfk == $this->settingFix('kdRujuk')) {
                        $data10[$i]['dirujuk'] = (float)$hideung['dirujuk'] + 1;
                    } else if ($item->objectruanganlastfk == $ruanganIGD) {
                        $data10[$i]['pulang'] = (float)$hideung['pulang'] + 1;
                    } else if ($item->objectstatuskeluarfk == 5 && $item->objectruanganlastfk == $ruanganIGD && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['matil'] = (float)$hideung['matil'] + 1;
                    } else if ($item->objectstatuskeluarfk == 5 && $item->objectruanganlastfk == $ruanganIGD && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['matip'] = (float)$hideung['matip'] + 1;
                    } else if ($item->objectkondisipasienfk == 7 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['doal'] = (float)$hideung['doal'] + 1;
                    } else if ($item->objectkondisipasienfk == 7 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['doap'] = (float)$hideung['doap'] + 1;
                    } 

                    if (isset($isGadar[$item->norec_pd])) {
                        $parameter = $isGadar[$item->norec_pd]['Parameter_GawatDarurat'];
                         if ($parameter == "Tidak Gawat Darurat") {
                            $tidakGawatDaruratCount++;
                        }
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->objectasalrujukanfk == 5) {
                    $rujukan = 0;
                    $nonrujukan = 1;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $matil = 0;
                    $matip = 0;
                    $doal = 0;
                    $doap = 0;
                } else if ($item->objectasalrujukanfk != 5) {
                    $rujukan = 1;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $matil = 0;
                    $matip = 0;
                    $doal = 0;
                    $doap = 0;
                }
                if ($item->objectruanganlastfk != $ruanganIGD) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 1;
                    $dirujuk = 0;
                    $pulang = 0;
                    $matil = 0;
                    $matip = 0;
                    $doal = 0;
                    $doap = 0;
                } else if ($item->objectstatuskeluarfk == $this->settingFix('kdRujuk')) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 1;
                    $pulang = 0;
                    $matil = 0;
                    $matip = 0;
                    $doal = 0;
                    $doap = 0;
                } else if ($item->objectstatuskeluarfk == $this->settingFix('kdMeninggal') && $item->objectruanganlastfk == $ruanganIGD && $item->objectjeniskelaminfk == 1) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $matil = 1;
                    $matip = 0;
                    $doal = 0;
                    $doap = 0;
                } else if ($item->objectstatuskeluarfk == $this->settingFix('kdMeninggal') && $item->objectruanganlastfk == $ruanganIGD && $item->objectjeniskelaminfk == 2) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $matil = 0;
                    $matip = 1;
                    $doal = 0;
                    $doap = 0;
                } else if ($item->objectruanganlastfk == $ruanganIGD) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 1;
                    $matil = 0;
                    $matip = 0;
                    $doal = 0;
                    $doap = 0;
                } else if ($item->objectkondisipasienfk == 7 && $item->objectjeniskelaminfk == 1) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $mati = 0;
                    $doal = 1;
                    $doap = 0;
                } else if ($item->objectkondisipasienfk == 7 && $item->objectjeniskelaminfk == 2) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $mati = 0;
                    $doal = 0;
                    $doap = 1;
                }

                $tidakGawatDarurat = 0;
                if (isset($isGadar[$item->norec_pd])) {
                    $parameter = $isGadar[$item->norec_pd]['Parameter_GawatDarurat'];
                     if ($parameter == "Tidak Gawat Darurat") {
                        $tidakGawatDarurat = 1;
                        $tidakGawatDaruratCount++;
                    }
                }

                $data10[] = array(
                    'jenispelayanan' => $item->jenispelayanan,
                    'jumlah' => 1,
                    'rujukan' => $rujukan,
                    'nonrujukan' => $nonrujukan,
                    'dirawat' => $dirawat,
                    'dirujuk' => $dirujuk,
                    'pulang' => $pulang,
                    'matil' => $matil,
                    'matip' => $matip,
                    'data' => $data,
                    'doal' => $doal,
                    'doap' => $doap,
                    'tidakgawatdarurat' => $tidakGawatDarurat,
                    

                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapIGD order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data10 as $dt){
                if($sp->kelompoklaporan == $dt['jenispelayanan']){
                    $sama = true;
                    $rujukan = $dt['rujukan'];
                    $nonrujukan = $dt['nonrujukan'];
                    $dirawat = $dt['dirawat'];
                    $dirujuk = $dt['dirujuk'];
                    $pulang = $dt['pulang'];
                    $matil = $dt['matil'];
                    $matip = $dt['matip'];
                    $data = $dt['data'];
                    $doal = $dt['doal'];
                    $doap = $dt['doap'];
                    $tidakgawatdarurat = $dt['tidakgawatdarurat'];
                } 
            }

            if($sama == false){
                $rujukan = 0;
                $nonrujukan = 0;
                $dirawat = 0;
                $dirujuk = 0;
                $pulang = 0;
                $matil = 0;
                $matip = 0;
                $data = 0;
                $doal = 0;
                $doap = 0;
                $tidakgawatdarurat = 0;
            }

            $data10fix[] = array(
                'jenispelayanan' => $sp->kelompoklaporan,
                'rujukan' => $rujukan,
                'nonrujukan' => $nonrujukan,
                'dirawat' => $dirawat,
                'dirujuk' => $dirujuk,
                'pulang' => $pulang,
                'matil' => $matil,
                'matip' => $matip,
                'data' => $data,
                'doal' => $doal,
                'doap' => $doap,
                'tidakgawatdarurat' => $tidakgawatdarurat,
                'jumlah' => 1,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getKegiatanKesehatanGigidanMulut(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $KdJenisLapGigiMulut = (int) $this->settingFix('KdJenisLapGigiMulut');
        $data = DB::select(DB::raw("SELECT 
                    jl.jenislaporan,
                    kl.kelompoklaporan,
                    COUNT(mpptrl.objectkontenlaporanfk) as jumlah
                    FROM pasiendaftar_t as pd
                    INNER JOIN antrianpasiendiperiksa_t as apd ON apd.noregistrasifk = pd.norec
                    LEFT JOIN pelayananpasien_t as pp ON pp.noregistrasifk = apd.norec
                    INNER JOIN ruangan_m as ru ON ru.id = apd.objectruanganfk
                    INNER JOIN produk_m as pr ON pr.id = pp.produkfk
                    INNER JOIN detailjenisproduk_m as djp ON djp.id = pr.objectdetailjenisprodukfk
                    left JOIN jenisproduk_m as jp ON jp.id = djp.objectjenisprodukfk
                    LEFT JOIN kelompokproduk_m as kp ON kp.id = jp.objectkelompokprodukfk
                    LEFT JOIN strukpelayanan_t as sp ON sp.norec = pp.strukfk
                    LEFT JOIN mapproduktolaporanrl_m as mpptrl ON mpptrl.produkfk = pr.id
                    LEFT JOIN kelompoklaporan_m as kl ON kl.id = mpptrl.objectkontenlaporanfk
                    LEFT JOIN jenislaporan_m as jl ON jl.id = mpptrl.objectjenislaporanfk
                    AND jl.id = $KdJenisLapGigiMulut
                    WHERE pd.kdprofile = 1
                    AND pd.statusenabled = true
                    AND pp.tglpelayanan::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    GROUP BY mpptrl.objectkontenlaporanfk, jl.jenislaporan, kl.kelompoklaporan"));

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapGigiMulut order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data as $dt){
                if($sp->kelompoklaporan == $dt->kelompoklaporan){
                    $sama = true;
                    $jumlah = $dt->jumlah;
                } 
            }

            if($sama == false){
                $jumlah = 0;
            }

            $data10fix[] = array(
                'kelompoklaporan' => $sp->kelompoklaporan,
                'jumlah' => $jumlah,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'siayu',
        );

        return $this->respond($result);
    }

    public function getLaporanRL34Kebidanan(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];
        
        $KdJenisLapKebidanan = (int) $this->settingFix('KdJenisLapKebidanan');
        $KdRuangKebidanan = explode(',', $this->settingFix('KdRuanganKandungan'));

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->leftjoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('asalrujukan_m as ar', 'ar.id', '=', 'apd.objectasalrujukanfk')
            ->leftjoin('statuskeluar_m as stk', 'stk.id', '=', 'pd.objectstatuskeluarfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'apd.objectasalrujukanfk',
                'ar.asalrujukan',
                'pd.objectstatuskeluarfk',
                'apd.objectruanganfk',
                'kl.kelompoklaporan as jenispelayanan',
                'pd.norec as norec_pd'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('jl.id', $KdJenisLapKebidanan)
            ->whereIn('apd.objectruanganfk', [303,216,301,310,323,202,370,402])
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();

                // var_dump($data);

        // Collect all norec_pd values from $data
        $norecPdValues = $data->pluck('norec_pd')->toArray();

        $riwayatkeluar = DB::connection('mongodb')
            ->table('AsesmenMedisRawatJalan')
            ->select('riwayatkeluar', 'statuskeluar', 'perlukontrol', 'registrasi.norec_pd') // Include statuskeluar
            ->whereNotNull('riwayatkeluar') // Ensures riwayatkeluar is not null
            ->whereNotNull('statuskeluar') // Ensures statuskeluar is not null
            ->whereNotNull('perlukontrol') // Ensures perlukontrol is not null
            ->whereIn('registrasi.norec_pd', $norecPdValues) // Filters by relevant norec_pd values
            ->get()
            ->keyBy('registrasi.norec_pd'); // Index the result by norec_pd

        // Process data
        foreach ($data as $d) {
            // Check if the current norec_pd exists in $riwayatkeluar
            if (isset($riwayatkeluar[$d->norec_pd])) {
                $record = $riwayatkeluar[$d->norec_pd];

                // Assign riwayatkeluar and statuskeluar if they exist
                $d->riwayatkeluar = $record['riwayatkeluar'] ?? 'Data tidak ada';
                $d->statuskeluar = $record['statuskeluar'] ?? 'Data tidak ada';
                $d->perlukontrol = $record['perlukontrol'] ?? 'Data tidak ada';
            } else {
                // Assign "Data tidak ada" if the norec_pd is not found
                $d->riwayatkeluar = 'Data tidak ada';
                $d->statuskeluar = 'Data tidak ada';
                $d->perlukontrol = 'Data tidak ada';
            }
        }
            
        $data10 = []; // Result array
        $defaultValues = [
            'rujukanRS' => 0,
            'rujukanBidan' => 0,
            'rujukanPuskes' => 0,
            'rujukanFaskesLain' => 0,
            'jmlMedisHidup' => 0,
            'jmlMedisMati' => 0,
            'jmlTotalMedis' => 0,
            'jmlNonMedisHidup' => 0,
            'jmlNonMedisMati' => 0,
            'jmlTotalNonMedis' => 0,
            'nonRujukanHidup'=> 0,
            'nonRujukanMati'=> 0,
            'totalNonRujukan'=> 0,
            'dirujuk' => 0,
        ];
        
        // Group by jenispelayanan
        foreach ($data as $item) {
            $key = $item->jenispelayanan;
        
            // Check if `jenispelayanan` already exists in `$data10`
            if (!isset($data10[$key])) {
                $data10[$key] = $defaultValues;
                $data10[$key]['jenispelayanan'] = $key; // Add service type
            }
        
            // Update counts based on conditions
            if (in_array($item->objectasalrujukanfk, [2,29])) {
                $data10[$key]['rujukanRS']++;
            } elseif ($item->objectasalrujukanfk == 20) {
                $data10[$key]['rujukanPuskes']++;
            } elseif (in_array($item->objectasalrujukanfk, [18,22,23,21,25,19,6])) {
                $data10[$key]['rujukanFaskesLain']++;
            }
        
            if (in_array($item->objectasalrujukanfk, [20,2,18,22,23,21,25,19,29,6]) && in_array($item->riwayatkeluar, ['Sembuh','Membaik','Belum Sembuh','Tidak Ada Perkembangan'])) {
                $data10[$key]['jmlMedisHidup']++;
            } elseif (in_array($item->objectasalrujukanfk, [20,2,18,22,23,21,25,19,29,6]) && in_array($item->riwayatkeluar, ['Meninggal >= 48 Jam','Meninggal <= 48 Jam','DOA'])) {
                $data10[$key]['jmlMedisMati']++;
            }
        
            if (in_array($item->objectasalrujukanfk, [7]) && in_array($item->riwayatkeluar, ['Sembuh','Membaik','Belum Sembuh','Tidak Ada Perkembangan'])) {
                $data10[$key]['jmlNonMedisHidup']++;
            } elseif (in_array($item->objectasalrujukanfk, [7]) && in_array($item->riwayatkeluar, ['Meninggal >= 48 Jam','Meninggal <= 48 Jam','DOA'])) {
                $data10[$key]['jmlNonMedisMati']++;
            } elseif (in_array($item->objectasalrujukanfk, [5,24]) && in_array($item->riwayatkeluar, ['Sembuh','Membaik','Belum Sembuh','Tidak Ada Perkembangan'])) {
                $data10[$key]['nonRujukanHidup']++;
            } elseif (in_array($item->objectasalrujukanfk, [5,24]) && in_array($item->riwayatkeluar, ['Meninggal >= 48 Jam','Meninggal <= 48 Jam','DOA'])) {
                $data10[$key]['nonRujukanMati']++;
            } else if (in_array($item->statuskeluar, ['Dirujuk'])) {
                $data10[$key]['dirujuk']++;
            }
        
            // Update totals
            $data10[$key]['jmlTotalMedis'] =  $data10[$key]['jmlMedisHidup'] + $data10[$key]['jmlMedisMati'];
            $data10[$key]['jmlTotalNonMedis'] = $data10[$key]['jmlNonMedisHidup'] + $data10[$key]['jmlNonMedisMati'];
            $data10[$key]['totalNonRujukan'] = $data10[$key]['nonRujukanHidup'] + $data10[$key]['nonRujukanMati'];
        }
        
        // Convert associative array to indexed array for array structure
        $resultArray = array_values($data10);

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapKebidanan order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data10 as $dt){
                if($sp->kelompoklaporan == $dt['jenispelayanan']){
                    $sama = true;
                    $rujukanRS = $dt['rujukanRS'];
                    $rujukanFaskesLain = $dt['rujukanFaskesLain'];
                    $rujukanPuskes = $dt['rujukanPuskes'];
                    $rujukanBidan = $dt['rujukanBidan'];
                    $jmlMedisHidup = $dt['jmlMedisHidup'];
                    $jmlMedisMati = $dt['jmlMedisMati'];
                    $jmlTotalMedis = $dt['jmlTotalMedis'];
                    $jmlNonMedisHidup = $dt['jmlNonMedisHidup'];
                    $jmlNonMedisMati = $dt['jmlNonMedisMati'];
                    $jmlTotalNonMedis = $dt['jmlTotalNonMedis'];
                    $nonRujukanHidup = $dt['nonRujukanHidup'];
                    $nonRujukanMati = $dt['nonRujukanMati'];
                    $totalNonRujukan = $dt['totalNonRujukan'];
                    $dirujuk = $dt['dirujuk'];
                } 
            }

            if($sama == false){
                $rujukanRS = 0;
                $rujukanFaskesLain = 0;
                $rujukanPuskes = 0;
                $rujukanBidan = 0;
                $jmlMedisHidup = 0;
                $jmlMedisMati = 0;
                $jmlTotalMedis = 0;
                $jmlNonMedisHidup = 0;
                $jmlNonMedisMati = 0;
                $jmlTotalNonMedis = 0;
                $nonRujukanHidup = 0;
                $nonRujukanMati = 0;
                $totalNonRujukan = 0;
                $dirujuk = 0;
            }

            $data10fix[] = array(
                'jenispelayanan' => $sp->kelompoklaporan,
                'rujukanRS' => $rujukanRS,
                'rujukanFaskesLain' => $rujukanFaskesLain,
                'rujukanPuskes' => $rujukanPuskes,
                'rujukanBidan' => $rujukanBidan,
                'jmlMedisHidup' => $jmlMedisHidup,
                'jmlMedisMati' => $jmlMedisMati,
                'jmlTotalMedis' => $jmlTotalMedis,
                'jmlNonMedisHidup' => $jmlNonMedisHidup,
                'jmlNonMedisMati' => $jmlNonMedisMati,
                'jmlTotalNonMedis' => $jmlTotalNonMedis,
                'nonRujukanHidup' => $nonRujukanHidup,
                'nonRujukanMati' => $nonRujukanMati,
                'totalNonRujukan' => $totalNonRujukan,
                'dirujuk' => $dirujuk,
                'jumlah' => 1,
            );
        }
        
            
        $result = array(
            'data' => $data10fix,
            'data2' => $data,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL35Perinatologi(Request $request)
    {

        $KdJenisLapPerinatologi = (int)$this->settingFix('KdJenisLapPerinatologi');
        // $KdRuangKebidanan = explode(',', $this->settingDataFixed('KdRuangKebidanan', $idProfile));
        $KdRuangKebidanan = explode(',', $this->settingFix('KdRuanganKandungan'));
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->join('asalrujukan_m as ar', 'ar.id', '=', 'apd.objectasalrujukanfk')
            ->leftjoin('statuskeluar_m as stk', 'stk.id', '=', 'pd.objectstatuskeluarfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'apd.objectasalrujukanfk',
                'ar.asalrujukan',
                'pd.objectstatuskeluarfk',
                'apd.objectruanganfk',
                'kl.kelompoklaporan as jenispelayanan'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('jl.id', $KdJenisLapPerinatologi) //laporan RL 35
            ->whereIn('apd.objectruanganfk', $KdRuangKebidanan)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();


        $data10 = [];
        $jml = 0;
        $rujukanRS = 0;
        $rujukanBidan = 0;
        $rujukanPuskes = 0;
        $rujukanFaskesLain = 0;
        $jmlMedisHidup = 0;
        $jmlMedisMati = 0;
        $jmlTotalMedis = 0;
        $jmlNonMedisHidup = 0;
        $jmlNonMedisMati = 0;
        $jmlTotalNonMedis = 0;
        $nonRujukanHidup = 0;
        $nonRujukanMati = 0;
        $totalNonRujukan = 0;
        $dirujuk = 0;
        $sama = false;

        $kdPulang = $this->settingFix('kdPulang');
        $kdPindah = $this->settingFix('kdPindah');
        $kdStatusRJ = $this->settingFix('kdStatusRJ');
        $kdRujuk = $this->settingFix('kdRujuk');
        $kdMeninggal = $this->settingFix('kdMeninggal');


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenispelayanan == $data10[$i]['jenispelayanan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    //RS=2, puskes=1, faskeslan=3, non rujikan =5, klinik=3
                    //statuskeluar --->1 pulang , 2 pindah,3 rajal, 4 rujuk, 5 meningggal

                    if ($item->objectasalrujukanfk == $kdPindah) {
                        $data10[$i]['rujukanRS'] = (float)$hideung['rujukanRS'] + 1;
                    } else if ($item->objectasalrujukanfk == $kdPulang) {
                        $data10[$i]['rujukanPuskes'] = (float)$hideung['rujukanPuskes'] + 1;
                    } else if ($item->objectasalrujukanfk == $kdStatusRJ || $item->objectasalrujukanfk == $kdRujuk) {
                        $data10[$i]['rujukanFaskesLain'] = (float)$hideung['rujukanFaskesLain'] + 1;
                    }
                    if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdPulang) {
                        $data10[$i]['jmlMedisHidup'] = (float)$hideung['jmlMedisHidup'] + 1;
                    } else if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdMeninggal) {
                        $data10[$i]['jmlMedisMati'] = (float)$hideung['jmlMedisMati'] + 1;
                    }
                    if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdPulang) {
                        $data10[$i]['jmlNonMedisHidup'] = (float)$hideung['jmlNonMedisHidup'] + 1;
                    } else if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdMeninggal) {
                        $data10[$i]['jmlNonMedisMati'] = (float)$hideung['jmlNonMedisMati'] + 1;
                    }
                    if ($item->objectstatuskeluarfk == $kdRujuk) {
                        $data10[$i]['dirujuk'] = (float)$hideung['dirujuk'] + 1;
                    }

                    $data10[$i]['jmlTotalMedis'] = $data10[$i]['rujukanRS'] + $data10[$i]['rujukanPuskes'] + $data10[$i]['rujukanFaskesLain'] + $data10[$i]['jmlMedisHidup'] + $data10[$i]['jmlMedisMati'];
                    $data10[$i]['jmlTotalNonMedis'] = $data10[$i]['jmlNonMedisHidup'] + $data10[$i]['jmlNonMedisMati'];
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->objectasalrujukanfk == $kdPindah) {
                    $rujukanRS = 1;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if ($item->objectasalrujukanfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 1;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if ($item->objectasalrujukanfk == $kdStatusRJ || $item->objectasalrujukanfk == $kdRujuk) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 1;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 1;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdMeninggal) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 1;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 1;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdMeninggal) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 1;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if ($item->objectstatuskeluarfk == $kdRujuk) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 1;
                }

                $data10[] = array(
                    //                    'produkfk' => $item->produkfk,
                    'jenispelayanan' => $item->jenispelayanan,
                    'jumlah' => 1,

                    'rujukanRS' => $rujukanRS,
                    'rujukanFaskesLain' => $rujukanFaskesLain,
                    'rujukanPuskes' => $rujukanPuskes,
                    'rujukanBidan' => 0,
                    'jmlMedisHidup' => $jmlMedisHidup,
                    'jmlMedisMati' => $jmlMedisMati,
                    'jmlTotalMedis' => $jmlTotalMedis,
                    'jmlNonMedisHidup' => $jmlNonMedisHidup,
                    'jmlNonMedisMati' => $jmlNonMedisMati,
                    'jmlTotalNonMedis' => $jmlTotalNonMedis,
                    'nonRujukanHidup' => $nonRujukanHidup,
                    'nonRujukanMati' => $nonRujukanMati,
                    'totalNonRujukan' => $totalNonRujukan,
                    'dirujuk' => $dirujuk,

                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = 28 order by kdkelompoklaporan::int4 asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data10 as $dt){
                if($sp->kelompoklaporan == $dt['jenispelayanan']){
                    $sama = true;
                    $rujukanRS = $dt['rujukanRS'];
                    $rujukanFaskesLain = $dt['rujukanFaskesLain'];
                    $rujukanPuskes = $dt['rujukanPuskes'];
                    $rujukanBidan = $dt['rujukanBidan'];
                    $jmlMedisHidup = $dt['jmlMedisHidup'];
                    $jmlMedisMati = $dt['jmlMedisMati'];
                    $jmlTotalMedis = $dt['jmlTotalMedis'];
                    $jmlNonMedisHidup = $dt['jmlNonMedisHidup'];
                    $jmlNonMedisMati = $dt['jmlNonMedisMati'];
                    $jmlTotalNonMedis = $dt['jmlTotalNonMedis'];
                    $nonRujukanHidup = $dt['nonRujukanHidup'];
                    $nonRujukanMati = $dt['nonRujukanMati'];
                    $totalNonRujukan = $dt['totalNonRujukan'];
                    $dirujuk = $dt['dirujuk'];
                } 
            }

            if($sama == false){
                $rujukanRS = 0;
                $rujukanFaskesLain = 0;
                $rujukanPuskes = 0;
                $rujukanBidan = 0;
                $jmlMedisHidup = 0;
                $jmlMedisMati = 0;
                $jmlTotalMedis = 0;
                $jmlNonMedisHidup = 0;
                $jmlNonMedisMati = 0;
                $jmlTotalNonMedis = 0;
                $nonRujukanHidup = 0;
                $nonRujukanMati = 0;
                $totalNonRujukan = 0;
                $dirujuk = 0;
            }

            $data10fix[] = array(
                'jenispelayanan' => $sp->kelompoklaporan,
                'rujukanRS' => $rujukanRS,
                'rujukanFaskesLain' => $rujukanFaskesLain,
                'rujukanPuskes' => $rujukanPuskes,
                'rujukanBidan' => $rujukanBidan,
                'jmlMedisHidup' => $jmlMedisHidup,
                'jmlMedisMati' => $jmlMedisMati,
                'jmlTotalMedis' => $jmlTotalMedis,
                'jmlNonMedisHidup' => $jmlNonMedisHidup,
                'jmlNonMedisMati' => $jmlNonMedisMati,
                'jmlTotalNonMedis' => $jmlTotalNonMedis,
                'nonRujukanHidup' => $nonRujukanHidup,
                'nonRujukanMati' => $nonRujukanMati,
                'totalNonRujukan' => $totalNonRujukan,
                'dirujuk' => $dirujuk,
                'jumlah' => 1,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL36Pembedahan(Request $request)
    {

        $KdJenisLapPembedahan = (int) $this->settingFix('KdJenisLapPembedahan');
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        //        $dataLogin = $request->all();
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'pr.objectdetailjenisprodukfk',
                'djp.detailjenisproduk',
                'kl.kelompoklaporan as spesialisasi'
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('jl.id', $KdJenisLapPembedahan)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();
    
        $data10 = [];
        $jml = 0;
        $khusus = 0;
        $besar = 0;
        $sedang = 0;
        $kecil = 0;
        $lainnya = 0;

        $sama = false;

        $KdKelompokTindakanBesar = explode(',',$this->settingFix('KdKelompokTindakanBesar'));
        $KdKelompokTindakanKhusus = explode(',',$this->settingFix('KdKelompokTindakanKhusus'));
        $KdKelompokTindakanSedang = explode(',',$this->settingFix('KdKelompokTindakanSedang'));
        $KdKelompokTindakanKecil =  explode(',',$this->settingFix('KdKelompokTindakanKecil'));


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->spesialisasi == $data10[$i]['spesialisasi']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanBesar)) {
                        $data10[$i]['besar'] = (float)$hideung['besar'] + 1;
                    } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanKhusus)) {
                        $data10[$i]['khusus'] = (float)$hideung['khusus'] + 1;
                    } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanSedang)) {
                        $data10[$i]['sedang'] = (float)$hideung['sedang'] + 1;
                    } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanKecil)) {
                        $data10[$i]['kecil'] = (float)$hideung['kecil'] + 1;
                    }
                
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanBesar)) {
                    $khusus = 0;
                    $besar = 1;
                    $sedang = 0;
                    $kecil = 0;
                    $lainnya = 0;
                } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanKhusus)) {
                    $khusus = 1;
                    $besar = 0;
                    $sedang = 0;
                    $kecil = 0;
                    $lainnya = 0;
                } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanSedang)) {
                    $khusus = 0;
                    $besar = 0;
                    $sedang = 1;
                    $kecil = 0;
                    $lainnya = 0;
                } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanKecil)) {
                    $khusus = 0;
                    $besar = 0;
                    $sedang = 0;
                    $kecil = 1;
                    $lainnya = 0;
                }


                $data10[] = array(
                    'spesialisasi' => $item->spesialisasi,
                    'jumlah' => 1,
                    'khusus' => $khusus,
                    'besar' => $besar,
                    'sedang' => $sedang,
                    'kecil' => $kecil,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapPembedahan order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data10 as $dt){
                if($sp->kelompoklaporan == $dt['spesialisasi']){
                    $sama = true;
                    $khusus = $dt['khusus'];
                    $besar = $dt['besar'];
                    $sedang = $dt['sedang'];
                    $kecil = $dt['kecil'];
                } 
            }

            if($sama == false){
                $khusus = 0;
                $besar = 0;
                $sedang = 0;
                $kecil = 0;
            }

            $data10fix[] = array(
                'spesialisasi' => $sp->kelompoklaporan,
                'khusus' => $khusus,
                'besar' => $besar,
                'sedang' => $sedang,
                'kecil' => $kecil,
                'jumlah' => $khusus + $besar + $sedang + $kecil,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL37Radiologi(Request $request)
    {

        $KdJenisLapRadiologi = (int) $this->settingFix('KdJenisLapRadiologi');
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        //////With Mapping****
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mpptrl', 'mpptrl.produkfk', '=', 'pr.id')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mpptrl.objectkontenlaporanfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mpptrl.objectjenislaporanfk')
            ->select(
                'jl.jenislaporan',
                'kl.kelompoklaporan',
                (DB::raw('COUNT(mpptrl.objectkontenlaporanfk) as jumlah'))
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('jl.id', $KdJenisLapRadiologi)
            ->groupBy('mpptrl.objectkontenlaporanfk', 'jl.jenislaporan', 'kl.kelompoklaporan')
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapRadiologi order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data as $dt){
                if($sp->kelompoklaporan == $dt->kelompoklaporan){
                    $sama = true;
                    $jumlah = $dt->jumlah;
                } 
            }

            if($sama == false){
                $jumlah = 0;
            }

            $data10fix[] = array(
                'kelompoklaporan' => $sp->kelompoklaporan,
                'jumlah' => $jumlah,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getPemeriksaanLab(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $KdJenisLapLaborat = $this->settingFix('KdJenisLapLaborat');

        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', 'mprl.objectkontenlaporanfk')
            ->select(
                'ru.objectdepartemenfk',
                'dpm.namadepartemen',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pp.produkfk',
                'prd.id as idproduk',
                'kl.kelompoklaporan as namaproduk',
                DB::raw('
                COUNT(prd.namaproduk) as jmlProduk')
            )
            ->groupBy(
                'ru.objectdepartemenfk',
                'dpm.namadepartemen',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pp.produkfk',
                'prd.id',
                'prd.namaproduk',
                'kl.kelompoklaporan'
            )
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('jl.id', $this->settingFix('KdJenisLapLaborat'))
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();


            $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapLaborat order by kdkelompoklaporan::int4 asc"));
            $data10fix = [];
            foreach($spesialis as $sp){
                $sama = false;
                foreach($data as $dt){
                    if($sp->kelompoklaporan == $dt->namaproduk){
                        $sama = true;
                        $jmlproduk = $dt->jmlproduk;
                    } 
                }
    
                if($sama == false){
                    $jmlproduk = 0;
                }
    
                $data10fix[] = array(
                    'namaproduk' => $sp->kelompoklaporan,
                    'jmlproduk' => $jmlproduk,
                );
            }
        
        $result = array(
            'data' => $data10fix,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getPelayananRehab(Request $request)
    {
        $KdJenisLapRehab = $this->settingFix('KdJenisLapRehabMedik');
        // $kdDepartemenRehabMedik = $this->settingFix('kdDepartemenRehabMedik');

        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        // $dateRange = [$request->tglAwal, $request->tglAkhir];
        //#WIth Mapp
        // $data = DB::table('pasiendaftar_t as pd')
        //     ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
        //     ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk','apd.norec')
        //     ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
        //     ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
        //     ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
        //     ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', 'pp.produkfk')
        //     ->join('jenislaporan_m as jl', 'jl.id', 'mprl.objectjenislaporanfk')
        //     ->join('kelompoklaporan_m as kl', 'kl.id', 'mprl.objectkontenlaporanfk')
        //     ->select(
        //         'kl.kelompoklaporan as namaproduk',
        //         DB::raw('COUNT(prd.namaproduk) as JmlTindakan')
        //     )
        //     ->groupBy('kl.kelompoklaporan')
        //     ->where('pd.kdprofile', $this->kdProfile)
        //     ->where('ru.objectdepartemenfk', $KdJenisLapRehab)
        //      ->where('jl.id', $kdDepartemenRehabMedik)
        //     ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
        //     ->get();

        $data = DB::select(DB::raw("SELECT 
        jl.jenislaporan,
        kl.id,
        kl.kelompoklaporan,
        COUNT(mpptrl.objectkontenlaporanfk) as jumlah
        FROM pasiendaftar_t as pd
        INNER JOIN antrianpasiendiperiksa_t as apd ON apd.noregistrasifk = pd.norec
        inner JOIN pelayananpasien_t as pp ON pp.noregistrasifk = apd.norec
        INNER JOIN ruangan_m as ru ON ru.id = apd.objectruanganfk
        INNER JOIN produk_m as pr ON pr.id = pp.produkfk
        INNER JOIN detailjenisproduk_m as djp ON djp.id = pr.objectdetailjenisprodukfk
        inner JOIN mapproduktolaporanrl_m as mpptrl ON mpptrl.produkfk = pr.id and mpptrl.ruanganfk = ru.id
        INNER JOIN kelompoklaporan_m as kl ON kl.id = mpptrl.objectkontenlaporanfk
        inner JOIN jenislaporan_m as jl ON jl.id = mpptrl.objectjenislaporanfk
        left JOIN jenisproduk_m as jp ON jp.id = djp.objectjenisprodukfk
        LEFT JOIN kelompokproduk_m as kp ON kp.id = jp.objectkelompokprodukfk
        LEFT JOIN strukpelayanan_t as sp ON sp.norec = pp.strukfk
        WHERE pd.kdprofile = 1
        AND pd.statusenabled = true
        AND jl.id = $KdJenisLapRehab
        AND pp.tglpelayanan::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
        GROUP BY mpptrl.objectkontenlaporanfk, jl.jenislaporan, kl.kelompoklaporan, kl.id"));

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapRehab order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data as $dt){
                if($sp->kelompoklaporan == $dt->kelompoklaporan && $sp->id == $dt->id){
                    $sama = true;
                    $jumlah = $dt->jumlah;
                } 
            }

            if($sama == false){
                $jumlah = 0;
            }

            $data10fix[] = array(
                'kelompoklaporan' => $sp->kelompoklaporan,
                'jumlah' => $jumlah,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL310Khusus(Request $request)
    {
        $KdJenisLapPelKhusus = (int) $this->settingFix('KdJenisLapPelayananKhusus');
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT 
                    jl.jenislaporan,
                    kl.kelompoklaporan,
                    COUNT(mpptrl.objectkontenlaporanfk) as jumlah
                    FROM pasiendaftar_t as pd
                    INNER JOIN antrianpasiendiperiksa_t as apd ON apd.noregistrasifk = pd.norec
                    LEFT JOIN pelayananpasien_t as pp ON pp.noregistrasifk = apd.norec
                    INNER JOIN ruangan_m as ru ON ru.id = apd.objectruanganfk
                    INNER JOIN produk_m as pr ON pr.id = pp.produkfk
                    INNER JOIN detailjenisproduk_m as djp ON djp.id = pr.objectdetailjenisprodukfk
                    left JOIN jenisproduk_m as jp ON jp.id = djp.objectjenisprodukfk
                    LEFT JOIN kelompokproduk_m as kp ON kp.id = jp.objectkelompokprodukfk
                    LEFT JOIN strukpelayanan_t as sp ON sp.norec = pp.strukfk
                    LEFT JOIN mapproduktolaporanrl_m as mpptrl ON mpptrl.produkfk = pr.id
                    INNER JOIN kelompoklaporan_m as kl ON kl.id = mpptrl.objectkontenlaporanfk
                    LEFT JOIN jenislaporan_m as jl ON jl.id = mpptrl.objectjenislaporanfk
                    WHERE pd.kdprofile = 1
                    AND pd.statusenabled = true
                    AND jl.id = $KdJenisLapPelKhusus
                    AND pp.tglpelayanan::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    GROUP BY mpptrl.objectkontenlaporanfk, jl.jenislaporan, kl.kelompoklaporan"));

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapPelKhusus order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data as $dt){
                if($sp->kelompoklaporan == $dt->kelompoklaporan){
                    $sama = true;
                    $jumlah = $dt->jumlah;
                } 
            }

            if($sama == false){
                $jumlah = 0;
            }

            $data10fix[] = array(
                'kelompoklaporan' => $sp->kelompoklaporan,
                'jumlah' => $jumlah,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL311KesehatanJiwa(Request $request)
    {
   
        $KdJenisLapKesehatanJiwa = (int) $this->settingFix('KdJenisLapKesehatanJiwa');
        // $dateRange = [$request->tglAwal, $request->tglAkhir];
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $kdRuanganJiwa = $this->settingFix('kdRuanganJiwa');
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT 
                jl.jenislaporan,
                kl.kelompoklaporan,
                COUNT(CASE WHEN pm.objectjeniskelaminfk = 1 AND mpptrl.objectkontenlaporanfk IS NOT NULL THEN 1 ELSE NULL END) as jumlahlaki,
                COUNT(CASE WHEN pm.objectjeniskelaminfk = 2 AND mpptrl.objectkontenlaporanfk IS NOT NULL THEN 1 ELSE NULL END) as jumlahperempuan,
                COUNT(mpptrl.objectkontenlaporanfk) as jumlah
                FROM pasiendaftar_t as pd
                INNER JOIN pasien_m pm on pm.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd ON apd.noregistrasifk = pd.norec
                INNER JOIN pelayananpasien_t as pp ON pp.noregistrasifk = apd.norec
                INNER JOIN ruangan_m as ru ON ru.id = apd.objectruanganfk
                INNER JOIN produk_m as pr ON pr.id = pp.produkfk
                INNER JOIN detailjenisproduk_m as djp ON djp.id = pr.objectdetailjenisprodukfk
                LEFT JOIN jenisproduk_m as jp ON jp.id = djp.objectjenisprodukfk
                LEFT JOIN kelompokproduk_m as kp ON kp.id = jp.objectkelompokprodukfk
                LEFT JOIN strukpelayanan_t as sp ON sp.norec = pp.strukfk
                INNER JOIN mapproduktolaporanrl_m as mpptrl ON mpptrl.produkfk = pr.id
                INNER JOIN kelompoklaporan_m as kl ON kl.id = mpptrl.objectkontenlaporanfk and mpptrl.ruanganfk = ru.id
                INNER JOIN jenislaporan_m as jl ON jl.id = mpptrl.objectjenislaporanfk
                WHERE pd.kdprofile = 1
                AND pd.statusenabled = true
                AND jl.id = $KdJenisLapKesehatanJiwa
                AND pp.tglpelayanan::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                GROUP BY mpptrl.objectkontenlaporanfk, jl.jenislaporan, kl.kelompoklaporan;"));


        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapKesehatanJiwa order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data as $dt){
                if($sp->kelompoklaporan == $dt->kelompoklaporan){
                    $sama = true;
                    $jumlah = $dt->jumlah;
                    $jumlahlaki = $dt->jumlahlaki;
                    $jumlahperempuan = $dt->jumlahperempuan;
                } 
            }

            if($sama == false){
                $jumlah = 0;
                $jumlahlaki = 0;
                $jumlahperempuan = 0;
            }

            $data10fix[] = array(
                'kelompoklaporan' => $sp->kelompoklaporan,
                'jumlah' => $jumlah,
                'jumlahlaki' => $jumlahlaki,
                'jumlahperempuan' => $jumlahperempuan,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL316KeluargaBerencana(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $KdJenisLapKB = (int) $this->settingFix('KdJenisLapKB');
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->select(
                'kl.kelompoklaporan as jenispelayanan',
                DB::raw('
                 COUNT(prd.namaproduk) as jmltindakan')
            )
            ->groupBy('kl.kelompoklaporan')
            ->where('pd.kdprofile', $kdProfile)
            ->where('jl.id', $KdJenisLapKB)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $dateRange)
            ->get();


            $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapKB order by id asc"));
            $data10fix = [];
            foreach($spesialis as $sp){
                $sama = false;
                foreach($data as $dt){
                    if($sp->kelompoklaporan == $dt->jenispelayanan){
                        $sama = true;
                        $jmltindakan = $dt->jmltindakan;
                    } 
                }
    
                if($sama == false){
                    $jmltindakan = 0;
                }
    
                $data10fix[] = array(
                    'jenispelayanan' => $sp->kelompoklaporan,
                    'jmltindakan' => $jmltindakan,
                );
            }

        $result = array(
            'data' => $data10fix,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    
    public function getRL314Rujukan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $KdJenisLapRujukan = (int) $this->settingFix('KdJenisLapRujukan');
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as app')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'app.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.ruanganfk', '=', 'ru.id')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('asalrujukan_m as ar', 'ar.id', '=', 'app.objectasalrujukanfk')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('statuspulang_m as splng', 'splng.id', '=', 'pd.objectstatuspulangfk')
            ->leftJoin('statuskeluar_m as sklr', 'sklr.id', '=', 'pd.objectstatuskeluarfk')
            ->select(
                'pd.tglregistrasi',
                'pm.nocm',
                'pd.noregistrasi',
                'pm.namapasien',
                'app.objectasalrujukanfk',
                'ar.asalrujukan',
                'ru.namaruangan',
                'dpm.namadepartemen',
                'pg.namalengkap',
                'pm.objectjeniskelaminfk',
                'pd.objectstatuspulangfk',
                'splng.statuspulang',
                'sklr.statuskeluar',
                'kl.kelompoklaporan as jenis_spesialisasi'
            )
            ->where('app.kdprofile', $kdProfile)
            ->where('jl.id', $KdJenisLapRujukan)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $dateRange);

        $data = $data->get();

        $data10 = [];
        $jml = 0;
        $sama = false;

        $DatangSendiri = 0;
        $FasilitasLain = 0;
        $Puskesmas = 0;
        $RsLain = 0;
        $PasienRujukan = 0;
        $DiterimaKembali = 0;

        $BackToPuskesmas = 0;
        $BackToRsAsal = 0;
        $BackToKlinik = 0;
        $BackToRs = 0;

        $kdPuskesmas = $this->settingFix('kdPuskesmas');
        $kdRumahSakit = $this->settingFix('kdRumahSakit');
        $kdDatangSendiri = $this->settingFix('kdDatangSendiri');
        $kdKlinik = $this->settingFix('kdKlinik');
        $kdPasRujukan = $this->settingFix('kdRujukanLain');
        $kdKembaliPuskes = $this->settingFix('kdPuskesmasAsal');
        $kdKembaliKlinik = $this->settingFix('kdKlinikAsal');
        $kdKembaliRS = $this->settingFix('kdRsAsal');
        $kdDiterimaKembali = $this->settingFix('kdDiterimaKembali');
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenis_spesialisasi == $data10[$i]['jenis_spesialisasi']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    if ($item->objectasalrujukanfk == $kdDatangSendiri) {
                        $data10[$i]['dtgsendiri'] = (float)$hideung['dtgsendiri'] + 1;
                    } elseif ($item->objectasalrujukanfk == $kdPuskesmas) {
                        $data10[$i]['puskesmas'] = (float)$hideung['puskesmas'] + 1;
                    } elseif ($item->objectasalrujukanfk == $kdRumahSakit) {
                        $data10[$i]['hospital'] = (float)$hideung['hospital'] + 1;
                    } elseif ($item->objectasalrujukanfk == $kdKlinik) {
                        $data10[$i]['klinik'] = (float)$hideung['klinik'] + 1;
                    } elseif ($item->objectasalrujukanfk == $kdPasRujukan) {
                        $data10[$i]['PasienRujukan'] = (float)$hideung['PasienRujukan'] + 1;
                    }
                    if ($item->objectasalrujukanfk == $kdDiterimaKembali) {
                        $data10[$i]['DTrmaKembali'] = (float)$hideung['DTrmaKembali'] + 1;
                    }
                    if ($item->objectstatuspulangfk == $kdKembaliRS) {
                        $data10[$i]['DKembalikanKRs'] = (float)$hideung['DKembalikanKRs'] + 1;
                    }
                    if ($item->objectstatuspulangfk == $kdKembaliPuskes) {
                        $data10[$i]['DKembalikanKPuskes'] = (float)$hideung['DKembalikanKPuskes'] + 1;
                    }
                    if ($item->objectstatuspulangfk == $kdKembaliKlinik) {
                        $data10[$i]['DKembalikanKKlinik'] = (float)$hideung['DKembalikanKKlinik'] + 1;
                    }
                    //                    $data10[$i]['total'] = $data10[$i]['jmlBaruL'] + $data10[$i]['jmlBaruP'];
                }
                $i = $i + 1;
            }

            if ($sama == false) {

                if ($item->objectasalrujukanfk == $kdDatangSendiri) {
                    $DatangSendiri = 1;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdPuskesmas) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 1;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdRumahSakit) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 1;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdKlinik) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 1;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdDiterimaKembali) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 1;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdPasRujukan) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 1;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectstatuspulangfk == $kdKembaliRS) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 1;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectstatuspulangfk == $kdKembaliPuskes) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 1;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectstatuspulangfk == $kdKembaliKlinik) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 1;
                    $BackToRs = 0;
                }

                $data10[] = array(
                    'jenis_spesialisasi' => $item->jenis_spesialisasi,
                    'DTrmaKembali' => $DiterimaKembali,
                    'dtgsendiri' => $DatangSendiri,
                    'puskesmas' => $Puskesmas,
                    'hospital' => $RsLain,
                    'klinik' => $FasilitasLain,
                    'PasienRujukan' => $PasienRujukan,
                    'DKembalikanKRs' => $BackToRsAsal,
                    'DKembalikanKPuskes' => $BackToPuskesmas,
                    'DKembalikanKKlinik' => $BackToKlinik,
                    'jumlah' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapRujukan order by id asc"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data10 as $dt){
                if($sp->kelompoklaporan == $dt['jenis_spesialisasi']){
                    $sama = true;
                    $DTrmaKembali = $dt['DTrmaKembali'];
                    $dtgsendiri = $dt['dtgsendiri'];
                    $puskesmas = $dt['puskesmas'];
                    $hospital = $dt['hospital'];
                    $klinik = $dt['klinik'];
                    $PasienRujukan = $dt['PasienRujukan'];
                    $DKembalikanKRs = $dt['DKembalikanKRs'];
                    $DKembalikanKPuskes = $dt['DKembalikanKPuskes'];
                    $DKembalikanKKlinik = $dt['DKembalikanKKlinik'];
                } 
            }

            if($sama == false){
                $DTrmaKembali = 0;
                $dtgsendiri = 0;
                $puskesmas = 0;
                $hospital = 0;
                $klinik = 0;
                $PasienRujukan = 0;
                $DKembalikanKRs = 0;
                $DKembalikanKPuskes = 0;
                $DKembalikanKKlinik = 0;
            }

            $data10fix[] = array(
                'jenis_spesialisasi' => $sp->kelompoklaporan,
                'DTrmaKembali' => $DTrmaKembali,
                'dtgsendiri' => $dtgsendiri,
                'puskesmas' => $puskesmas,
                'hospital' => $hospital,
                'klinik' => $klinik,
                'PasienRujukan' => $PasienRujukan,
                'DKembalikanKRs' => $DKembalikanKRs,
                'DKembalikanKPuskes' => $DKembalikanKPuskes,
                'DKembalikanKKlinik' => $DKembalikanKKlinik,
                'jumlah' => 1,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getRL35Kunjungan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $KdJenisLapKunjungan = (int) $this->settingFix('KdJenisLapKunjungan');
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as app')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'app.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.ruanganfk', '=', 'ru.id')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('asalrujukan_m as ar', 'ar.id', '=', 'app.objectasalrujukanfk')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('statuspulang_m as splng', 'splng.id', '=', 'pd.objectstatuspulangfk')
            ->leftJoin('statuskeluar_m as sklr', 'sklr.id', '=', 'pd.objectstatuskeluarfk')
            ->select(
                'pd.tglregistrasi',
                'pm.nocm',
                'pm.alamatlengkap',
                'pd.noregistrasi',
                'pm.namapasien',
                'app.objectasalrujukanfk',
                'ar.asalrujukan',
                'ru.namaruangan',
                'dpm.namadepartemen',
                'pg.namalengkap',
                'pm.objectjeniskelaminfk',
                'pd.objectstatuspulangfk',
                'splng.statuspulang',
                'sklr.statuskeluar',
                'kl.kelompoklaporan as jenis_spesialisasi'
            )
            ->where('app.kdprofile', $kdProfile)
            ->where('jl.id', $KdJenisLapKunjungan)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $dateRange);

        $data = $data->get();

        $data10 = [];
        $jml = 0;
        $sama = false;

        $DalamKotaL = 0;
        $DalamKotaP = 0;
        $LuarKotaL = 0;
        $LuarKotaP = 0;
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenis_spesialisasi == $data10[$i]['jenis_spesialisasi']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    if (str_contains(strtoupper($item->alamatlengkap), 'DENPASAR') && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['DalamKotaL'] = (float)$hideung['DalamKotaL'] + 1;
                    } 
                    if (str_contains(strtoupper($item->alamatlengkap), 'DENPASAR') && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['DalamKotaP'] = (float)$hideung['DalamKotaP'] + 1;
                    } 
                    if (!str_contains(strtoupper($item->alamatlengkap), 'DENPASAR') && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['LuarKotaL'] = (float)$hideung['LuarKotaL'] + 1;
                    } 
                    if (!str_contains(strtoupper($item->alamatlengkap), 'DENPASAR') && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['LuarKotaP'] = (float)$hideung['LuarKotaP'] + 1;
                    } 
                }
                $i = $i + 1;
            }

            if ($sama == false) {

                if (str_contains(strtoupper($item->alamatlengkap), 'DENPASAR') && $item->objectjeniskelaminfk == 1) {
                    $DalamKotaL = 1;
                    $DalamKotaP = 0;
                    $LuarKotaL = 0;
                    $LuarKotaP = 0;
                } 
                if (str_contains(strtoupper($item->alamatlengkap), 'DENPASAR') && $item->objectjeniskelaminfk == 2) {
                    $DalamKotaL = 0;
                    $DalamKotaP = 1;
                    $LuarKotaL = 0;
                    $LuarKotaP = 0;
                } 
                if (!str_contains(strtoupper($item->alamatlengkap), 'DENPASAR') && $item->objectjeniskelaminfk == 1) {
                    $DalamKotaL = 0;
                    $DalamKotaP = 0;
                    $LuarKotaL = 1;
                    $LuarKotaP = 0;
                } 
                if (!str_contains(strtoupper($item->alamatlengkap), 'DENPASAR') && $item->objectjeniskelaminfk == 2) {
                    $DalamKotaL = 0;
                    $DalamKotaP = 0;
                    $LuarKotaL = 0;
                    $LuarKotaP = 1;
                } 

                $data10[] = array(
                    'jenis_spesialisasi' => $item->jenis_spesialisasi,
                    'DalamKotaL' => $DalamKotaL,
                    'DalamKotaP' => $DalamKotaP,
                    'LuarKotaL' => $LuarKotaL,
                    'LuarKotaP' => $LuarKotaP,
                    'jumlah' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }

        $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = $KdJenisLapKunjungan order by id"));
        $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data10 as $dt){
                if($sp->kelompoklaporan == $dt['jenis_spesialisasi']){
                    $sama = true;
                    $DalamKotaL = $dt['DalamKotaL'];
                    $DalamKotaP = $dt['DalamKotaP'];
                    $LuarKotaL = $dt['LuarKotaL'];
                    $LuarKotaP = $dt['LuarKotaP'];
                } 
            }

            if($sama == false){
                $DalamKotaL = 0;
                $DalamKotaP = 0;
                $LuarKotaL = 0;
                $LuarKotaP = 0;
            }

            $data10fix[] = array(
                'jenis_spesialisasi' => $sp->kelompoklaporan,
                'DalamKotaL' => $DalamKotaL,
                'DalamKotaP' => $DalamKotaP,
                'LuarKotaL' => $LuarKotaL,
                'LuarKotaP' => $LuarKotaP,
                'Total' => $DalamKotaL + $DalamKotaP + $LuarKotaP + $LuarKotaL,
                'jumlah' => 1,
            );
        }

        $result = array(
            'data' => $data10fix,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getRL315CaraBayar(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KdJenisLapCaraBayar = $this->settingFix('KdJenisLapCaraBayar');
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $kdRanap = $this->settingFix('kdDepartemenRanapFix');
        $kdDepRad = $this->settingFix('KdInstalasiRadiologi');
        $kdDepLab = $this->settingFix('kdDepLab');

        $data = DB::table('pasiendaftar_t as pd')
        ->join('pasien_m as p', 'p.id', 'pd.nocmfk')
        ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', 'apd.noregistrasifk')
        ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.norec', 'pd.nosbmlastfk')
        ->leftJoin('strukbuktipenerimaancarabayar_t as sbmcb', 'sbmcb.nosbmfk', 'sbm.norec')
        ->leftJoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
        ->leftJoin('ruangan_m as ru1', 'ru1.id', 'pd.objectruanganlastfk')
        ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
        ->leftJoin('departemen_m as dept', 'dept.id', 'ru.objectdepartemenfk')
        ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'p.objectjeniskelaminfk')
        ->leftJoin('kelas_m as kel', 'kel.id', 'pd.objectkelasfk')
        ->leftJoin('carabayar_m as cb', 'cb.id', 'sbmcb.objectcarabayarfk')
        ->select(
            'pd.tglregistrasi',
            'p.nocm',
            'pd.noregistrasi',
            'ru.namaruangan',
            'p.namapasien',
            'kp.id as kp_id',
            'kp.kelompokpasien as kdekelompokpasien',
            'pd.tglpulang',
            'pd.statuspasien',
            'jk.jeniskelamin',
            'p.tgllahir',
            'kel.namakelas',
            'pd.nosbmlastfk',
            'cb.carabayar',
            'ru.objectdepartemenfk as kddepartemen',
            'ru.objectdepartemenfk as kddepartemenranap',
            DB::raw("
            EXTRACT(YEAR FROM current_date) - EXTRACT(YEAR FROM tgllahir) as umur,
            CASE 
                WHEN kp.id = 2 THEN 'Asuransi JKN (BPJS Kesehatan)'
                WHEN kp.id IN (3, 5) THEN 'Asuransi Swasta'
                WHEN kp.id = 1 THEN 'Membayar Sendiri'
                ELSE NULL
            END as golbayar,
            CASE 
                WHEN kp.id = 2 THEN '2.1'
                WHEN kp.id IN (3, 5) THEN '2.2'
                WHEN kp.id = 1 THEN '1'
                ELSE NULL
            END as idbayar
        ")

        )
        ->where('pd.kdprofile', $kdProfile)
        ->whereBetween(DB::raw('CAST(pd.tglregistrasi AS DATE)'), $dateRange)
        ->get();

        $data10 = collect($data)
        ->groupBy('golbayar')
        ->map(function ($group) use ($kdRanap, $kdDepRad, $kdDepLab) {
            // Count based on conditions
            $pasienKeluarRI = $group->where('tglpulang', '!=', null)->where('kddepartemenranap', $kdRanap)->count();
            $pasienDirawatRI = $group->where('tglpulang', null)->where('kddepartemenranap', $kdRanap)->count();
            $pasienRad = $group->where('kddepartemen', $kdDepRad)->count();
            $pasienLab = $group->where('kddepartemen', $kdDepLab)->count();
            $pasienLain = $group->whereNotIn('kddepartemen', [$kdDepLab, $kdDepRad, $kdRanap])->count();
            $jumlah = $group->count();

            // Calculate total days for "pasienLamaDirawatRI"
            $pasienLamaDirawatRI = $group
            ->where('kddepartemenranap', $kdRanap)
            ->filter(function ($item) {
                // Only include items that have a valid tglpulang (departure date)
                return $item->tglpulang !== null;
            })
            ->sum(function ($item) {
                $tglRegistrasi = Carbon::parse($item->tglregistrasi);
                $tglPulang = Carbon::parse($item->tglpulang);

                // Calculate the difference in days (minimum of 1 day)
                return max($tglRegistrasi->diffInDays($tglPulang), 1);
            });

            // Return aggregated results
            return [
                'golbayar' => $group->first()->golbayar,
                'idbayar' => $group->first()->idbayar,
                'pasienKeluarRI' => $pasienKeluarRI,
                'pasienDirawatRI' => $pasienDirawatRI,
                'pasienLamaDirawatRI' => $pasienLamaDirawatRI,
                'pasienRawatJalan' => $pasienRad + $pasienLab + $pasienLain,
                'pasienLab' => $pasienLab,
                'pasienRad' => $pasienRad,
                'pasienLain' => $pasienLain,
                'jumlah' => $jumlah,
            ];
        })
        ->values()
        ->sortBy('idbayar')
        ->toArray();
    
    $spesialis = DB::select(DB::raw("select * from kelompoklaporan_m where idjenislaporanfk = 19 order by id asc"));
    $data10fix = [];
        foreach($spesialis as $sp){
            $sama = false;
            foreach($data10 as $dt){
                if($sp->kelompoklaporan == $dt['golbayar']){
                    $sama = true;
                    $pasienKeluarRI = $dt['pasienKeluarRI'];
                    $pasienDirawatRI = $dt['pasienDirawatRI'];
                    $pasienLamaDirawatRI = $dt['pasienLamaDirawatRI'];
                    $pasienRawatJalan = $dt['pasienRawatJalan'];
                    $pasienLab = $dt['pasienLab'];
                    $pasienRad = $dt['pasienRad'];
                    $pasienLain = $dt['pasienLain'];
                } 
            }

            if($sama == false){
                $pasienKeluarRI = 0;
                $pasienDirawatRI = 0;
                $pasienLamaDirawatRI = 0;
                $pasienRawatJalan = 0;
                $pasienLab = 0;
                $pasienRad = 0;
                $pasienLain = 0;
            }

            $data10fix[] = array(
                'golbayar' => $sp->kelompoklaporan,
                'pasienKeluarRI' => $pasienKeluarRI,
                'pasienDirawatRI' => $pasienDirawatRI,
                'pasienLamaDirawatRI' => $pasienLamaDirawatRI,
                'pasienRawatJalan' => $pasienRawatJalan,
                'pasienLab' => $pasienLab,
                'pasienRad' => $pasienRad,
                'pasienLain' => $pasienLain,
                'jumlah' => 0,
            );
        }

    $result = [
        'data' => $data10fix,
        'message' => 'er@epic',
    ];

    return $this->respond($result);

    }

    public function getPengadaanObat(Request $request)
    {
        // Query with your new logic
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $data = DB::select(DB::raw("select jenis, sum(jumlahitemobat) jumlahitemobat, sum(jumlahtersedia) jumlahtersedia
        from(
            select 'Obat Generik Formularium Nasional' as jenis,
            count(pr.id) as jumlahitemobat,
            case when spd.norec is not null then count(pr.id) else 0 end as jumlahtersedia
            from produk_m as pr
            left join stokprodukdetail_t as spd on spd.objectprodukfk = pr.id
            and spd.statusenabled = true
            and spd.tglpelayanan between '$tglAwal' and '$tglAkhir'
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and pr.isfornas = true
            and pr.objectjenisgenerikfk is not null
            group by spd.norec
        ) as x group by jenis
        
        union all
        
        select jenis, sum(jumlahitemobat) jumlahitemobat, sum(jumlahtersedia) jumlahtersedia
        from(
            select 'Obat Generik Non Formularium Nasional' as jenis,
            count(pr.id) as jumlahitemobat,
            case when spd.norec is not null then count(pr.id) else 0 end as jumlahtersedia
            from produk_m as pr
            left join stokprodukdetail_t as spd on spd.objectprodukfk = pr.id
            and spd.statusenabled = true
            and spd.tglpelayanan between '$tglAwal' and '$tglAkhir'
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and pr.isfornas is null
            and pr.objectjenisgenerikfk is not null
            group by spd.norec
        ) as x group by jenis
        
        union all
        
        select jenis, sum(jumlahitemobat) jumlahitemobat, sum(jumlahtersedia) jumlahtersedia
        from(
            select 'Obat Non Generik Formularium Nasional' as jenis,
            count(pr.id) as jumlahitemobat,
            case when spd.norec is not null then count(pr.id) else 0 end as jumlahtersedia
            from produk_m as pr
            left join stokprodukdetail_t as spd on spd.objectprodukfk = pr.id
            and spd.statusenabled = true
            and spd.tglpelayanan between '$tglAwal' and '$tglAkhir'
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and pr.isfornas = true
            and pr.objectjenisgenerikfk is null
            group by spd.norec
        ) as x group by jenis
        
        union all
        
        select jenis, sum(jumlahitemobat) jumlahitemobat, sum(jumlahtersedia) jumlahtersedia
        from(
            select 'Obat Non Generik Non Formularium Nasional' as jenis,
            count(pr.id) as jumlahitemobat,
            case when spd.norec is not null then count(pr.id) else 0 end as jumlahtersedia
            from produk_m as pr
            left join stokprodukdetail_t as spd on spd.objectprodukfk = pr.id
            and spd.statusenabled = true
            and spd.tglpelayanan between '$tglAwal' and '$tglAkhir'
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and pr.isfornas is null
            and pr.objectjenisgenerikfk is null
            group by spd.norec
        ) as x group by jenis
        
        union all
        
        select jenis, sum(jumlahitemobat) jumlahitemobat, sum(jumlahtersedia) jumlahtersedia
        from(
            select 'TOTAL' as jenis,
            count(pr.id) as jumlahitemobat,
            case when spd.norec is not null then count(pr.id) else 0 end as jumlahtersedia
            from produk_m as pr
            left join stokprodukdetail_t as spd on spd.objectprodukfk = pr.id
            and spd.statusenabled = true
            and spd.tglpelayanan between '$tglAwal' and '$tglAkhir'
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and (pr.isfornas is null or pr.isfornas = true)
            group by spd.norec
        ) as x group by jenis
        order by jenis
        ;"));
        // Default values for each category

        $result = [
            'data' => $data,
            'message' => 'as@vexana',
        ];

        return $this->respond($result);

    }

    public function getPelayananResep(Request $request)
    {
        // Query with your new logic
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $data = DB::select(DB::raw("select jenis, sum(jumlahrajal) jumlahrajal, sum(jumlahigd) jumlahigd, sum(jumlahranap) jumlahranap
        from(
            select 'Obat Generik Formularium Nasional' as jenis,
            case when ru.objectdepartemenfk not in(9,16) then count(pp.norec) else 0 end as jumlahrajal,
            case when ru.objectdepartemenfk in(9) then count(pp.norec) else 0 end as jumlahigd,
            case when ru.objectdepartemenfk in(16) then count(pp.norec) else 0 end as jumlahranap
            from produk_m as pr
            inner join pelayananpasien_t as pp on pp.produkfk = pr.id
            and pp.statusenabled = true
            inner join strukresep_t as sr on sr.norec = pp.strukresepfk
            inner join strukorder_t as so on so.norec = sr.orderfk
            inner join ruangan_m as ru on ru.id = so.objectruanganfk
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and pr.isfornas = true
            and pr.objectjenisgenerikfk is not null
            and pp.tglpelayanan::date between '$tglAwal' and '$tglAkhir'
            group by ru.objectdepartemenfk
        ) as x group by jenis
        
        union all
        
        select jenis, sum(jumlahrajal) jumlahrajal, sum(jumlahigd) jumlahigd, sum(jumlahranap) jumlahranap
        from(
            select 'Obat Generik Non Formularium Nasional' as jenis,
            case when ru.objectdepartemenfk not in(9,16) then count(pp.norec) else 0 end as jumlahrajal,
            case when ru.objectdepartemenfk in(9) then count(pp.norec) else 0 end as jumlahigd,
            case when ru.objectdepartemenfk in(16) then count(pp.norec) else 0 end as jumlahranap
            from produk_m as pr
            inner join pelayananpasien_t as pp on pp.produkfk = pr.id
            and pp.statusenabled = true
            inner join strukresep_t as sr on sr.norec = pp.strukresepfk
            inner join strukorder_t as so on so.norec = sr.orderfk
            inner join ruangan_m as ru on ru.id = so.objectruanganfk
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and pr.isfornas is null
            and pr.objectjenisgenerikfk is not null
            and pp.tglpelayanan::date between '$tglAwal' and '$tglAkhir'
            group by ru.objectdepartemenfk
        ) as x group by jenis
        
        union all
        
        select jenis, sum(jumlahrajal) jumlahrajal, sum(jumlahigd) jumlahigd, sum(jumlahranap) jumlahranap
        from(
            select 'Obat Non Generik Formularium Nasional' as jenis,
            case when ru.objectdepartemenfk not in(9,16) then count(pp.norec) else 0 end as jumlahrajal,
            case when ru.objectdepartemenfk in(9) then count(pp.norec) else 0 end as jumlahigd,
            case when ru.objectdepartemenfk in(16) then count(pp.norec) else 0 end as jumlahranap
            from produk_m as pr
            inner join pelayananpasien_t as pp on pp.produkfk = pr.id
            and pp.statusenabled = true
            inner join strukresep_t as sr on sr.norec = pp.strukresepfk
            inner join strukorder_t as so on so.norec = sr.orderfk
            inner join ruangan_m as ru on ru.id = so.objectruanganfk
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and pr.isfornas = true
            and pr.objectjenisgenerikfk is null
            and pp.tglpelayanan::date between '$tglAwal' and '$tglAkhir'
            group by ru.objectdepartemenfk
        ) as x group by jenis
        
        union all
        
        select jenis, sum(jumlahrajal) jumlahrajal, sum(jumlahigd) jumlahigd, sum(jumlahranap) jumlahranap
        from(
            select 'Obat Non Generik Non Formularium Nasional' as jenis,
            case when ru.objectdepartemenfk not in(9,16) then count(pp.norec) else 0 end as jumlahrajal,
            case when ru.objectdepartemenfk in(9) then count(pp.norec) else 0 end as jumlahigd,
            case when ru.objectdepartemenfk in(16) then count(pp.norec) else 0 end as jumlahranap
            from produk_m as pr
            inner join pelayananpasien_t as pp on pp.produkfk = pr.id
            and pp.statusenabled = true
            inner join strukresep_t as sr on sr.norec = pp.strukresepfk
            inner join strukorder_t as so on so.norec = sr.orderfk
            inner join ruangan_m as ru on ru.id = so.objectruanganfk
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and pr.isfornas is null
            and pr.objectjenisgenerikfk is null
            and pp.tglpelayanan::date between '$tglAwal' and '$tglAkhir'
            group by ru.objectdepartemenfk
        ) as x group by jenis
        
        union all
        
        select jenis, sum(jumlahrajal) jumlahrajal, sum(jumlahigd) jumlahigd, sum(jumlahranap) jumlahranap
        from(
            select 'TOTAL' as jenis,
            case when ru.objectdepartemenfk not in(9,16) then count(pp.norec) else 0 end as jumlahrajal,
            case when ru.objectdepartemenfk in(9) then count(pp.norec) else 0 end as jumlahigd,
            case when ru.objectdepartemenfk in(16) then count(pp.norec) else 0 end as jumlahranap
            from produk_m as pr
            inner join pelayananpasien_t as pp on pp.produkfk = pr.id
            and pp.statusenabled = true
            inner join strukresep_t as sr on sr.norec = pp.strukresepfk
            inner join strukorder_t as so on so.norec = sr.orderfk
            inner join ruangan_m as ru on ru.id = so.objectruanganfk
            where pr.statusenabled = true
            and pr.objectkelompokprodukbpjsfk in(12,13,14)
            and (pr.isfornas is null or pr.isfornas = true)
            and pp.tglpelayanan::date between '$tglAwal' and '$tglAkhir'
            group by ru.objectdepartemenfk
        ) as x group by jenis
        order by jenis;"));

        $result = [
            'data' => $data,
            'message' => 'as@vexana',
        ];

        return $this->respond($result);

    }


    public function getDataLaporanRL51Kujungan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('pasiendaftar_t as pd')
            ->leftjoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->select(DB::raw('count(pd.norec) as jumlah,pd.statuspasien,ru.objectdepartemenfk'))
            ->where('pd.kdprofile', $kdProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $request['tglAkhir']);
        }
        if (isset($request['departementfk']) && $request['departementfk'] != "" && $request['departementfk'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk ', '=', $request['departementfk']);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }
        $data = $data->whereNull('pd.tanggalpembatalan');
        $data = $data->groupBy('pd.statuspasien', 'ru.objectdepartemenfk');
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'success',
        );
        return $this->respond($result);
    }


    public function getDataLaporanRL52KunjuanRawatJalan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->LEFTJOIN('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->LEFTJOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->select(
                'ru.namaruangan',
                DB::raw('COUNT(ru.namaruangan) as jumlahKunjungan')
            )
            ->where('pd.kdprofile', $kdProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $request['tglAkhir']);
        }
        if (isset($request['departemenfk']) && $request['departemenfk'] != "" && $request['departemenfk'] != "undefined") {
            $data = $data->where('dpm.id', '=', $request['departemenfk']);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }
        $data = $data->groupBy('dept.namadepartemen', 'ru.namaruangan');
        $data = $data->orderBy('ru.namaruangan', 'ASC');
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'success',
        );

        return $this->respond($result);
    }
    public function getIndexPenyakit(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $idRuangan = $request->ruanganId;
        $KelompokpasienId = $request->kpid;
        $DokterId = $request->dokter;
        $JkId = $request->jeniskelamin;
        // $KelasId = $request->kelas;
        // $QStatusPasien = $request->status;


        $ruangan = '';
        if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
            $ruangan = 'and ru2.id = ' . $idRuangan;
        }
        $kelompokpasien = '';
        if (isset($request->kpid) && $request->kpid != "undefined" && $request->kpid != null) {
            $kelompokpasien = 'and kp.id = ' . $KelompokpasienId;
        }
        $dokter = '';
        if (isset($request->dokter) && $request->dokter != "undefined" && $request->dokter != null) {
            $dokter = 'and pg.id = ' . $DokterId;
        }
        $jeniskelamin = '';
        if (isset($request->jeniskelamin) && $request->jeniskelamin != "undefined" && $request->jeniskelamin != null) {
            $jeniskelamin = 'and jk.id = ' . $JkId;
        }
        // $kelas = '';
        // if (isset($request->kelas) && $request->kelas != "undefined" && $request->kelas != null) {
        //     $kelas = 'and kl.id = ' . $KelasId;
        // }
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT DISTINCT on (apd.norec)
                        ps.nocm,
                        ps.namapasien,
                        ps.alamatlengkap,
                        kb.name,
                        ps.noidentitas,
                        asa.asalrujukan,
                        pas.nmprovider,
                        pas.nosep,
                        ru2.namaruangan,
                        TO_CHAR(age(ps.tgllahir), 'YY') as umur,
                        pd.statuspasien as statuskunjungan,
                        CASE 
                            WHEN dp.iskasusbaru = true and dp.iskasuslama = false THEN 'BARU' 
                            WHEN dp.iskasuslama = true and dp.iskasusbaru = false THEN 'LAMA' 
                            ELSE NULL END AS kasus,
                        jk.jeniskelamin,
                        TO_CHAR(pd.tglregistrasi, 'YY-Mon-DD') AS tglmasuk,
                        TO_CHAR(pd.tglregistrasi, 'HH24:MI') AS jammasuk,
                        pd.tglregistrasi AS tgljamregistrasi,
                        pd.tglpulang AS tglkeluar,
                        kls.reportdisplay AS namakelas,
                        (coalesce(apd.tglkeluar::date, now()::date) - apd.tglmasuk::date) + 1 as lamahari,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 0 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 7 then 1 else 0 end as jumlah07,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 8 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 28 then 1 else 0 end as jumlah828,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 > 28 then 1 else 0 end as jumlahkurang1,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 4 then 1 else 0 end as jumlah14,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 5 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 9 then 1 else 0 end as jumlah59,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 10 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 14 then 1 else 0 end as jumlah1014,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 15 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 19 then 1 else 0 end as jumlah1519,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 20 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 44 then 1 else 0 end as jumlah2044,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 45 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 54 then 1 else 0 end as jumlah4554,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 55 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 59 then 1 else 0 end as jumlah5559,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 60 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 69 then 1 else 0 end as jumlah6069,
                        case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 70 then 1 else 0 end as jumlah70,
                        kp.kelompokpasien, limited_ddt.kddiagnosatindakan, limited_ddt.namadiagnosatindakan,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp.kddiagnosa, ';'), ';', 1) AS kodeicd10primer,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp.namadiagnosa, ';'), ';', 1) AS namaicd10primer,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 1) AS kodeicd10sekunder_1,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 2) AS kodeicd10sekunder_2,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 3) AS kodeicd10sekunder_3,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 4) AS kodeicd10sekunder_4,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 5) AS kodeicd10sekunder_5,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 6) AS kodeicd10sekunder_6,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 1) AS namaicd10sekunder_1,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 2) AS namaicd10sekunder_2,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 3) AS namaicd10sekunder_3,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 4) AS namaicd10sekunder_4,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 5) AS namaicd10sekunder_5,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 6) AS namaicd10sekunder_6,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 1) AS kodeicd9_1,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 2) AS kodeicd9_2,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 3) AS kodeicd9_3,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 4) AS kodeicd9_4,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 5) AS kodeicd9_5,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 6) AS kodeicd9_6,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 1) AS namaicd9_1,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 2) AS namaicd9_2,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 3) AS namaicd9_3,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 4) AS namaicd9_4,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 5) AS namaicd9_5,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 6) AS namaicd9_6,
                        pg.namalengkap as dokter
                        FROM pasiendaftar_t AS pd
                        JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                        JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                        JOIN kelas_m AS kls ON kls.id = pd.objectkelasfk
                        JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                        LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
                        LEFT JOIN pemakaianasuransi_t AS pas ON pas.noregistrasifk = pd.norec
                        LEFT JOIN asuransipasien_m AS asu ON asu.id = pas.objectasuransipasienfk
                        LEFT JOIN kelas_m AS kls2 ON kls2.id = asu.objectkelasdijaminfk
                        LEFT JOIN rekanan_m AS rk ON rk.id = pd.objectrekananfk
                        LEFT JOIN asalrujukan_m AS asa ON asa.id = pd.asalrujukanfk
                        LEFT JOIN statuspulang_m AS sp ON sp.id = pd.objectstatuspulangfk
                        LEFT JOIN inacbg_status AS ic ON ic.inacbg_status = pd.inacbg_status
                        LEFT JOIN persalinan_t AS prs ON prs.norecpd = pd.norec
                        LEFT JOIN apgar_t AS apg ON apg.norecpd = pd.norec
                        LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                        LEFT JOIN pegawai_m AS pg ON pg.id = apd.objectpegawaifk
                        left join diagnosapasien_t as dp on dp.noregistrasifk = apd.norec
                        left join diagnosapasien_t as dp2 on dp2.noregistrasifk = apd.norec
                        left join detaildiagnosapasien_t as ddp on ddp.objectdiagnosapasienfk = dp.norec
                        left join diagnosa_m as d on d.id = ddp.objectdiagnosarmfk
                        LEFT JOIN (
                                SELECT DISTINCT ON (ddp.noregistrasifk)
                                    ddp.noregistrasifk, 
                                    d2.kddiagnosa, 
                                    d2.namadiagnosa, 
                                    ROW_NUMBER() OVER (
                                        PARTITION BY ddp.noregistrasifk 
                                        ORDER BY ddp.created_at ASC
                                    ) AS row_num
                                FROM detaildiagnosapasien_t ddp
                                INNER JOIN diagnosa_m d2 ON d2.id = ddp.objectdiagnosarmfk
                                WHERE ddp.objectjenisdiagnosarmfk = 1
                            ) AS limited_ddp
                        ON limited_ddp.noregistrasifk = dp.noregistrasifk AND limited_ddp.row_num <= 1
                        LEFT JOIN (
                            SELECT DISTINCT
                                ddp2.noregistrasifk, 
                                d2.kddiagnosa, 
                                d2.namadiagnosa, 
                                ROW_NUMBER() OVER (
                                    PARTITION BY ddp2.noregistrasifk 
                                    ORDER BY ddp2.created_at ASC
                                ) AS row_num
                            FROM detaildiagnosapasien_t ddp2
                            INNER JOIN diagnosa_m d2 ON d2.id = ddp2.objectdiagnosarmfk
                            WHERE ddp2.objectjenisdiagnosarmfk = 2
                        ) AS limited_ddp2 
                        ON limited_ddp2.noregistrasifk = dp.noregistrasifk AND limited_ddp2.row_num <= 6
                        left join diagnosatindakanpasien_t as dtp on dtp.objectpasienfk = apd.norec
                        LEFT JOIN (
                                SELECT 
                                        ddt.noregistrasifk, 
                                        dt.kddiagnosatindakan, 
                                        dt.namadiagnosatindakan, 
                                        ROW_NUMBER() OVER (
                                                PARTITION BY ddt.objectdiagnosatindakanpasienfk 
                                                ORDER BY ddt.created_at ASC
                                        ) AS row_num
                                FROM detaildiagnosatindakanpasien_t ddt
                                INNER JOIN diagnosatindakan_m dt ON dt.id = ddt.objectdiagnosatindakanrmfk
                        ) AS limited_ddt
                        ON limited_ddt.noregistrasifk = dtp.objectpasienfk AND limited_ddt.row_num <= 6
                        JOIN ruangan_m AS ru2 ON ru2.id = apd.objectruanganfk
                        JOIN departemen_m AS dept ON dept.id = ru2.objectdepartemenfk
                        left join kebangsaan_m as kb on kb.id = ps.objectkebangsaanfk
                        WHERE ru2.namaruangan NOT IN (
                            'RADIOLOGI', 'LABORATORIUM', 'BANK DARAH', 
                            'LAB - INVITRO KEDOKTERAN NUKLIR', 'LAB - PATOLOGI KLINIK', 
                            'LAB - PATOLOGI ANATOMI', 'LAB - MIKROBIOLOGI KLINIK', 
                            'CATH LAB', 'BEDAH SENTRAL IBS'
                        ) 
                        and ru2.objectdepartemenfk in (18,9)
                        and pd.tglregistrasi::date between '$tglAwal' and '$tglAkhir'
                        $ruangan
                        $kelompokpasien
                        $dokter
                        $jeniskelamin
                        AND pd.statusenabled = TRUE
                        AND apd.statusenabled = TRUE
                        AND pd.kdprofile = 1
                        GROUP BY pd.norec,
                        ps.nocm,
                        ps.namapasien,
                        ps.alamatlengkap,
                        kb.name,
                        ps.noidentitas,
                        asa.asalrujukan,
                        pas.nmprovider,
                        pas.nosep,
                        ru2.namaruangan,
                        ps.tgllahir,
                        dp.iskasusbaru,
                        dp.iskasuslama,
                        jk.jeniskelamin,
                        pd.tglregistrasi,
                        pd.tglpulang,
                        kls.reportdisplay,
                        apd.tglkeluar,
                        apd.tglmasuk,
                        ps.tgllahir,
                        kp.kelompokpasien,
                        d.kddiagnosa,
                        d.namadiagnosa,
                        pg.namalengkap,
                        apd.norec,
                        pd.statuspasien,
                        limited_ddt.kddiagnosatindakan, limited_ddt.namadiagnosatindakan
                        ORDER BY apd.norec ASC;"));
        
        // $riwayatkeluar = DB::connection('mongodb')
        //         ->table('RingkasanKeluar')
        //         ->select('riwayatkeluar', 'registrasi.norec_pd', 'created_at', 'updated_at')
        //         ->whereNotNull('riwayatkeluar')
        //         ->whereNull('namatemplate')
        //         ->where(function ($query) use ($request) {
        //             $query->whereBetween('created_at', [$request['tglAwal'], $request['tglAkhir']])
        //                 ->orWhereBetween('updated_at', [$request['tglAwal'], $request['tglAkhir']]);
        //         })
        //         ->get()
        //         ->keyBy('registrasi.norec_pd');

        //     // Process data
        //     foreach ($data as $d) {
        //         // Check if the current norec_pd exists in $isGadar
        //         if (isset($riwayatkeluar[$d->norec_pd]) && $riwayatkeluar[$d->norec_pd]['riwayatkeluar']) {
        //             // Assign the value of riwayatkeluar from $riwayatkeluar
        //             $d->riwayatkeluar = $riwayatkeluar[$d->norec_pd]['riwayatkeluar'];
        //         } else {
        //             // Assign "Data tidak ada" if the condition is not met
        //             $d->riwayatkeluar = '';
        //         }
        //     }

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }
    public function getPenyakitTerbanyak(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("select kddiagnosa, namadiagnosa, count(norec) as jumlah, sum(jumlah07) jumlah07, sum(jumlah828) jumlah828, sum(jumlahkurang1) jumlahkurang1, sum(jumlah14) jumlah14, sum(jumlah59) jumlah59, sum(jumlah1014) jumlah1014, sum(jumlah1519) jumlah1519, sum(jumlah2044) jumlah2044, sum(jumlah4554) jumlah4554, sum(jumlah5559) jumlah5559, sum(jumlah6069) jumlah6069, sum(jumlah70) jumlah70
        from(
                select d.kddiagnosa, d.namadiagnosa, pd.norec, 
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 0 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 7 then 1 else 0 end as jumlah07,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 8 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 28 then 1 else 0 end as jumlah828,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 > 28 then 1 else 0 end as jumlahkurang1,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 4 then 1 else 0 end as jumlah14,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 5 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 9 then 1 else 0 end as jumlah59,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 10 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 14 then 1 else 0 end as jumlah1014,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 15 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 19 then 1 else 0 end as jumlah1519,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 20 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 44 then 1 else 0 end as jumlah2044,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 45 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 54 then 1 else 0 end as jumlah4554,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 55 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 59 then 1 else 0 end as jumlah5559,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 60 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 69 then 1 else 0 end as jumlah6069,
                case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 70 then 1 else 0 end as jumlah70
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                inner join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                inner join ruangan_m as ru on ru.id = apd.objectruanganfk
                inner join diagnosapasien_t as dp on dp.noregistrasifk = apd.norec
                inner join detaildiagnosapasien_t as ddp on ddp.objectdiagnosapasienfk = dp.norec
                inner join diagnosa_m as d on d.id = ddp.objectdiagnosarmfk
                left join pegawai_m as pg on pg.id = pd.objectpegawaifk
                where pd.statusenabled = true
                and pd.kdprofile = 1
                and pd.tglregistrasi::date between '$tglAwal' and '$tglAkhir'
        ) as x
        group by kddiagnosa, namadiagnosa
        order by jumlah desc limit 20"));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }
    public function getPasienByTindakan(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("select ps.nocm, ps.namapasien, pd.tglregistrasi::date as tglmasuk, ddt.tglinputdiagnosa::date tgltindakan, 
        dt.kddiagnosatindakan || ' - ' || dt.namadiagnosatindakan as diagnosa, pg.namalengkap
        from pasiendaftar_t as pd
        inner join pasien_m as ps on ps.id = pd.nocmfk
        inner join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
        inner join ruangan_m as ru on ru.id = apd.objectruanganfk
        inner join diagnosatindakanpasien_t as dtp on dtp.objectpasienfk = apd.norec
        inner join detaildiagnosatindakanpasien_t as ddt on ddt.objectdiagnosatindakanpasienfk = dtp.norec
        inner join diagnosatindakan_m as dt on dt.id = ddt.objectdiagnosatindakanrmfk
        left join pegawai_m as pg on pg.id = pd.objectpegawaifk
        where pd.statusenabled = true
        and pd.kdprofile = 1
        and pd.tglregistrasi::date between '$tglAwal' and '$tglAkhir'"));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }
    public function getSensusRajal(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("select distinct ps.nocm, ps.namapasien, jk.jeniskelamin, TO_CHAR(age(ps.tgllahir), 'YY') as umur, pd.statuspasien, pr.namaproduk
        from pasiendaftar_t as pd
        inner join pasien_m as ps on ps.id = pd.nocmfk
        inner join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
        inner join ruangan_m as ru on ru.id = apd.objectruanganfk
        inner join pelayananpasien_t as pp on pp.noregistrasifk = apd.norec
        inner join produk_m as pr on pr.id = pp.produkfk
        left join pegawai_m as pg on pg.id = pd.objectpegawaifk
        left join jeniskelamin_m jk on jk.id = ps.objectjeniskelaminfk
        left join kelompokpasien_m kp on kp.id = pd.objectkelompokpasienlastfk
        where pd.statusenabled = true
        and pd.kdprofile = 1
        and pd.tglregistrasi::date between '$tglAwal' and '$tglAkhir'
        and pp.isobat is null"));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }
    public function getRegisRajal(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $idRuangan = $request->ruanganId;
        $KelompokpasienId = $request->kpid;
        $DokterId = $request->dokter;
        $JkId = $request->jeniskelamin;
        // $QStatusPasien = $request->status;


        $ruangan = '';
        if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
            $ruangan = 'and ru2.id = ' . $idRuangan;
        }
        $kelompokpasien = '';
        if (isset($request->kpid) && $request->kpid != "undefined" && $request->kpid != null) {
            $kelompokpasien = 'and kp.id = ' . $KelompokpasienId;
        }
        $dokter = '';
        if (isset($request->dokter) && $request->dokter != "undefined" && $request->dokter != null) {
            $dokter = 'and pg.id = ' . $DokterId;
        }
        $jeniskelamin = '';
        if (isset($request->jeniskelamin) && $request->jeniskelamin != "undefined" && $request->jeniskelamin != null) {
            $jeniskelamin = 'and jk.id = ' . $JkId;
        }
        // $statuspasien = '';
        // if (isset($request->status) && $request->status != "undefined" && $request->status != null) {
        //     $status = 'and pd.statuspasien = ' . $QStatusPasien;
        // }
        
        
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT DISTINCT on (apd.norec)
                            ps.nocm,
                            ps.namapasien,
                            ps.alamatlengkap,
                            kb.name,
                            ps.noidentitas,
                            asa.asalrujukan,
                            pas.nosep,
                            ru2.namaruangan as namapoli,
                            TO_CHAR(age(ps.tgllahir), 'YY') as umur,
                            CASE 
                                    WHEN dp.iskasusbaru = true and dp.iskasuslama = false THEN 'BARU' 
                                    WHEN dp.iskasuslama = true and dp.iskasusbaru = false THEN 'LAMA' 
                                    ELSE NULL END AS kasus,
                        jk.jeniskelamin,
                         TO_CHAR(pd.tglregistrasi, 'YY-MM-DD') AS tglregistrasi,
                         TO_CHAR(pd.tglregistrasi, 'HH24:MI') AS jamregistrasi,
                         pd.tglregistrasi AS tgljamregistrasi,
                        pd.tglpulang AS tglkeluar,
                            kls.reportdisplay AS namakelas,
                            (coalesce(apd.tglkeluar::date, now()::date) - apd.tglmasuk::date) + 1 as lamahari,
                            kp.kelompokpasien AS status,
                            CASE WHEN rk.namarekanan = 'Diri Sendiri' THEN 'UMUM' else rk.namarekanan end as namarekanan,
                            CASE 
                                        WHEN kp.kelompokpasien = 'UMUM/PRIBADI' THEN '' 
                                        ELSE ps.nobpjs 
                                END AS nobpjs, 
                            SPLIT_PART(STRING_AGG(DISTINCT limited_ddp.namadiagnosa, ';'), ';', 1) AS namadiagnosa,
                            pg.namalengkap as namadokter,
                            pd.statuspasien,pd.noregistrasi
                    FROM pasiendaftar_t AS pd
                    JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                    JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                    JOIN kelas_m AS kls ON kls.id = pd.objectkelasfk
                    JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                    LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
                    LEFT JOIN pemakaianasuransi_t AS pas ON pas.noregistrasifk = pd.norec
                    LEFT JOIN asuransipasien_m AS asu ON asu.id = pas.objectasuransipasienfk
                    LEFT JOIN kelas_m AS kls2 ON kls2.id = asu.objectkelasdijaminfk
                    LEFT JOIN rekanan_m AS rk ON rk.id = pd.objectrekananfk
                    LEFT JOIN asalrujukan_m AS asa ON asa.id = pd.asalrujukanfk
                    LEFT JOIN statuspulang_m AS sp ON sp.id = pd.objectstatuspulangfk
                    LEFT JOIN inacbg_status AS ic ON ic.inacbg_status = pd.inacbg_status
                    LEFT JOIN persalinan_t AS prs ON prs.norecpd = pd.norec
                    LEFT JOIN apgar_t AS apg ON apg.norecpd = pd.norec
                    LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                    LEFT JOIN pegawai_m AS pg ON pg.id = apd.objectpegawaifk
                    left join diagnosapasien_t as dp on dp.noregistrasifk = apd.norec
                    left join diagnosapasien_t as dp2 on dp2.noregistrasifk = apd.norec
                    left join detaildiagnosapasien_t as ddp on ddp.objectdiagnosapasienfk = dp.norec
                    left join diagnosa_m as d on d.id = ddp.objectdiagnosarmfk
                    LEFT JOIN (
                                    SELECT DISTINCT ON (ddp.noregistrasifk)
                                        ddp.noregistrasifk, 
                                        d2.kddiagnosa, 
                                        d2.namadiagnosa, 
                                        ROW_NUMBER() OVER (
                                            PARTITION BY ddp.noregistrasifk 
                                            ORDER BY ddp.created_at ASC
                                        ) AS row_num
                                    FROM detaildiagnosapasien_t ddp
                                    INNER JOIN diagnosa_m d2 ON d2.id = ddp.objectdiagnosarmfk
                                    WHERE ddp.objectjenisdiagnosarmfk = 1
                                ) AS limited_ddp 
                                ON limited_ddp.noregistrasifk = dp.noregistrasifk AND limited_ddp.row_num <= 1
                    JOIN ruangan_m AS ru2 ON ru2.id = apd.objectruanganfk
                    JOIN departemen_m AS dept ON dept.id = ru2.objectdepartemenfk
                    left join kebangsaan_m as kb on kb.id = ps.objectkebangsaanfk
                    WHERE ru2.namaruangan NOT IN (
                        'RADIOLOGI', 'LABORATORIUM', 'BANK DARAH', 
                        'LAB - INVITRO KEDOKTERAN NUKLIR', 'LAB - PATOLOGI KLINIK', 
                        'LAB - PATOLOGI ANATOMI', 'LAB - MIKROBIOLOGI KLINIK', 
                        'CATH LAB', 'BEDAH SENTRAL IBS'
                    ) 
                    and ru2.objectdepartemenfk = 18
                    AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    $ruangan
                    $kelompokpasien
                    $dokter
                    $jeniskelamin
                    AND pd.statusenabled = TRUE
                    AND apd.statusenabled = TRUE
                    AND pd.kdprofile = 1
                    GROUP BY pd.norec,
                        ps.nocm,
                            ps.namapasien,
                            ps.alamatlengkap,
                            kb.name,
                            ps.noidentitas,
                            asa.asalrujukan,
                            pas.nosep,
                            ru2.namaruangan,
                            ps.tgllahir,
                            dp.iskasusbaru,
                            dp.iskasuslama,
                            jk.jeniskelamin,
                            pd.tglregistrasi,
                            pd.tglpulang,
                            kls.reportdisplay,
                            apd.tglkeluar,
                            apd.tglmasuk,
                            ps.tgllahir,
                            kp.kelompokpasien,
                            d.kddiagnosa,
                            d.namadiagnosa,
                            pg.namalengkap,
                            apd.norec,
                            rk.namarekanan,
                            ps.nobpjs
                    ORDER BY apd.norec ASC;
;
                "));
        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }
    public function getRegisRanap(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $idRuangan = $request->ruanganId;
        $KelompokpasienId = $request->kpid;
        $DokterId = $request->dokter;
        $JkId = $request->jeniskelamin;
        $KelasId = $request->kelas;
        // $QStatusPasien = $request->status;


        $ruangan = '';
        if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
            $ruangan = 'and rp.objectruanganfk = ' . $idRuangan;
        }
        $kelompokpasien = '';
        if (isset($request->kpid) && $request->kpid != "undefined" && $request->kpid != null) {
            $kelompokpasien = 'and kp.id = ' . $KelompokpasienId;
        }
        $dokter = '';
        if (isset($request->dokter) && $request->dokter != "undefined" && $request->dokter != null) {
            $dokter = 'and pg.id = ' . $DokterId;
        }
        $jeniskelamin = '';
        if (isset($request->jeniskelamin) && $request->jeniskelamin != "undefined" && $request->jeniskelamin != null) {
            $jeniskelamin = 'and jk.id = ' . $JkId;
        }
        $kelas = '';
        if (isset($request->kelas) && $request->kelas != "undefined" && $request->kelas != null) {
            $kelas = 'and kl.id = ' . $KelasId;
        }
        //#WIth Mapp
        $data = DB::select(DB::raw("WITH RuanganPertama AS (
                        SELECT DISTINCT ON (apd.noregistrasifk)
                            apd.noregistrasifk, 
                            apd.objectruanganfk, 
                            ru.objectdepartemenfk,
                            ru.namaruangan 
                        FROM antrianpasiendiperiksa_t AS apd
                        INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                        WHERE ru.objectdepartemenfk = 16  and apd.statusenabled = true
                        ORDER BY apd.noregistrasifk, apd.tglmasuk ASC
                    ),

                    RuanganTerakhirDiagnosa AS (
                        SELECT DISTINCT ON (apd.noregistrasifk)
                            apd.noregistrasifk, 
                            apd.norec
                        FROM antrianpasiendiperiksa_t AS apd
                        INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                        WHERE ru.objectdepartemenfk = 16  and apd.statusenabled = true
                        ORDER BY apd.noregistrasifk, apd.tglmasuk DESC
                    )

                    SELECT DISTINCT ON (pd.noregistrasi)
                        apd.norec,
                        ps.nocm,
                        pd.norec,
                        TO_CHAR(pd.tglregistrasi, 'DD-Mon-YY') AS tglregistrasi,
                        TO_CHAR(pd.tglregistrasi, 'HH24:MI') AS jamregistrasi,
                        pd.tglregistrasi AS tgljamregistrasi,
                        pd.noregistrasi,
                        ps.namapasien,
                        jk.jeniskelamin, 
                        TO_CHAR(age(ps.tgllahir), 'YY') AS umur,
                        pd.statuspasien,
                        ps.alamatlengkap,
                        kp.kelompokpasien AS status,
                        CASE WHEN rm.namarekanan = 'Diri Sendiri' THEN 'UMUM' ELSE rm.namarekanan END AS namarekanan, 
                        CASE 
                            WHEN kp.kelompokpasien = 'UMUM/PRIBADI' THEN '' 
                            ELSE ps.nobpjs 
                        END AS nobpjs,
                        COALESCE(rp.namaruangan, ru.namaruangan) AS namaruangan,
                        pg.namalengkap AS namadokter,
                        km.namakamar AS kamar,
                        kl.namakelas AS kelas,
                        ps.penanggungjawab,
                        string_agg(DISTINCT dm.kddiagnosa, ';') AS kodeicd10primer,
                        string_agg(DISTINCT dm.namadiagnosa, ';') AS namaicd10primer, 
                        string_agg(DISTINCT limited_ddp2.kddiagnosa, ';') AS kodeicd10sekunder,
                        string_agg(DISTINCT limited_ddp2.namadiagnosa, ';') AS namaicd10sekunder,
                        CASE 
                            WHEN ap.jenispeserta IS NULL THEN NULL 
                            WHEN ap.jenispeserta IN ('PBI (APBD)', 'PBI (APBN)', 'PBI JAMINAN KESEHATAN') THEN 'PBI' 
                            ELSE 'NON PBI' 
                        END AS jenispeserta,
                        kb.name,
                        kl.id AS kelasid
                    FROM pasiendaftar_t AS pd
                    INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                    INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                    INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                    LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                    LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
                    LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                    LEFT JOIN rekanan_m AS rm ON rm.id = pd.objectrekananfk
                    LEFT JOIN pemakaianasuransi_t pa ON pd.norec = pa.noregistrasifk
                    LEFT JOIN asuransipasien_m ap ON pa.objectasuransipasienfk = ap.id
                    LEFT JOIN detaildiagnosapasien_t AS ddp ON ddp.noregistrasifk = (
                        SELECT rt.norec FROM RuanganTerakhirDiagnosa rt WHERE rt.noregistrasifk = apd.noregistrasifk
                    ) AND ddp.objectjenisdiagnosarmfk = 1
                    LEFT JOIN diagnosa_m AS dm ON dm.id = ddp.objectdiagnosarmfk
                    LEFT JOIN (
                        SELECT 
                            ddp2.noregistrasifk, 
                            d2.kddiagnosa, 
                            d2.namadiagnosa, 
                            ROW_NUMBER() OVER (
                                PARTITION BY ddp2.noregistrasifk 
                                ORDER BY ddp2.created_at ASC
                            ) AS row_num
                        FROM detaildiagnosapasien_t ddp2
                        INNER JOIN diagnosa_m d2 ON d2.id = ddp2.objectdiagnosarmfk
                        WHERE ddp2.objectjenisdiagnosarmfk = 2
                    ) AS limited_ddp2 
                    ON limited_ddp2.noregistrasifk = (
                        SELECT rt.norec FROM RuanganTerakhirDiagnosa rt WHERE rt.noregistrasifk = apd.noregistrasifk
                    ) AND limited_ddp2.row_num <= 6
                    LEFT JOIN kelas_m AS kl ON kl.id = pd.objectkelasfk 
                    JOIN kamar_m AS km ON km.id = apd.objectkamarfk
                    LEFT JOIN kebangsaan_m AS kb ON kb.id = ps.objectkebangsaanfk
                    LEFT JOIN RuanganPertama AS rp 
                        ON rp.noregistrasifk = apd.noregistrasifk 
                         $ruangan   
                    WHERE pd.statusenabled = TRUE
                    AND pd.kdprofile = 1
                    AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    AND apd.statusenabled = TRUE
                    AND rp.noregistrasifk IS NOT NULL  
                    
                    $kelompokpasien
                    $dokter
                    $jeniskelamin
                    GROUP BY 
                        apd.norec, ps.nocm, pd.norec, pd.tglregistrasi, pd.noregistrasi, ps.namapasien,
                        jk.jeniskelamin, ps.tgllahir, pd.statuspasien, ps.alamatlengkap,
                        kp.kelompokpasien, rm.namarekanan, ps.nobpjs, ru.namaruangan,
                        pg.namalengkap, kb.name, kl.id, kl.namakelas, km.namakamar, ps.penanggungjawab, ap.jenispeserta, rp.namaruangan
                    ORDER BY pd.noregistrasi, apd.tglmasuk;

                        "));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }
    public function getRegisVKIGD(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        // $idRuangan = $request->ruanganId;
        $KelompokpasienId = $request->kpid;
        $DokterId = $request->dokter;
        $JkId = $request->jeniskelamin;
        // $KelasId = $request->kelas;
        // $QStatusPasien = $request->status;


        // $ruangan = '';
        // if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
        //     $ruangan = 'and ru.id = ' . $idRuangan;
        // }
        $kelompokpasien = '';
        if (isset($request->kpid) && $request->kpid != "undefined" && $request->kpid != null) {
            $kelompokpasien = 'and kp.id = ' . $KelompokpasienId;
        }
        $dokter = '';
        if (isset($request->dokter) && $request->dokter != "undefined" && $request->dokter != null) {
            $dokter = 'and pg2.id = ' . $DokterId;
        }
        $jeniskelamin = '';
        if (isset($request->jeniskelamin) && $request->jeniskelamin != "undefined" && $request->jeniskelamin != null) {
            $jeniskelamin = 'and jk.id = ' . $JkId;
        }
        //#WIth Mapp
        $data = DB::select(DB::raw("WITH all_apd AS (
                SELECT 
                    apd.norec,
                    apd.noregistrasifk,
                    apd.objectruanganfk
                FROM antrianpasiendiperiksa_t AS apd
                WHERE apd.noregistrasifk IN (
                    SELECT pd.norec 
                    FROM pasiendaftar_t AS pd
                    WHERE pd.statusenabled = true
                    AND pd.kdprofile = 1
                    AND pd.tglregistrasi::date BETWEEN '$tglAwal' AND '$tglAkhir'
                )
            ),
            all_diagnosa AS (
                SELECT DISTINCT ON (pd.norec)
                    pd.norec AS norec_pd,
                    string_agg(DISTINCT dm.namadiagnosa, '; ') AS diagnosa1, 
                    SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 1) AS diagnosa2,
                    ddp.ismasuk
                FROM pasiendaftar_t AS pd
                LEFT JOIN all_apd AS apd ON apd.noregistrasifk = pd.norec
                LEFT JOIN diagnosapasien_t AS dpt ON dpt.noregistrasifk = apd.norec
                LEFT JOIN detaildiagnosapasien_t AS ddp 
                    ON ddp.objectdiagnosapasienfk = dpt.norec 
                    AND ddp.objectjenisdiagnosarmfk = 1 
                    AND ddp.ismoi IS NULL
                    AND (
                        (apd.objectruanganfk = 323 AND ddp.objectjenisdiagnosarmfk = 1)
                        OR (apd.objectruanganfk <> 323 AND ddp.ismasuk = true)  
                        OR (apd.objectruanganfk <> 323 AND ddp.objectjenisdiagnosarmfk = 1) 
                    )
                LEFT JOIN diagnosa_m AS dm ON dm.id = ddp.objectdiagnosarmfk
                LEFT JOIN diagnosatindakanpasien_t AS dtp ON dtp.objectpasienfk = apd.norec
                LEFT JOIN (
                    SELECT 
                        ddt.noregistrasifk, 
                        dt.kddiagnosatindakan, 
                        dt.namadiagnosatindakan, 
                        ROW_NUMBER() OVER (
                                    PARTITION BY ddt.objectdiagnosatindakanpasienfk 
                                    ORDER BY ddt.created_at ASC
                        ) AS row_num
                    FROM detaildiagnosatindakanpasien_t ddt
                    INNER JOIN diagnosatindakan_m dt ON dt.id = ddt.objectdiagnosatindakanrmfk
                ) AS limited_ddt
                ON limited_ddt.noregistrasifk = dtp.objectpasienfk AND limited_ddt.row_num <= 1
                GROUP BY pd.norec, ddp.ismasuk, apd.objectruanganfk,ddp.objectjenisdiagnosarmfk
                ORDER BY 
                    pd.norec, 
                    CASE 
                    WHEN apd.objectruanganfk = 323 AND ddp.objectjenisdiagnosarmfk = 1 THEN 1
                        WHEN apd.objectruanganfk <> 323 AND ddp.ismasuk = true THEN 2  
                        WHEN apd.objectruanganfk <> 323 AND ddp.objectjenisdiagnosarmfk = 1 THEN 3 
                        ELSE 4  
                    END, 
                    ddp.ismasuk DESC  
            )

            SELECT DISTINCT on (apd.norec)
                ps.nocm, 
                TO_CHAR(pd.tglregistrasi, 'DD-Mon-YY') AS tglregistrasi, 
                TO_CHAR(pd.tglregistrasi, 'HH24:MI:SS') AS jamregistrasi,
                pd.tglregistrasi AS tgljamregistrasi,
                pd.noregistrasi,
                pd.norec AS norec_pd,  
                ps.namapasien, 
                jk.jeniskelamin, 
                TO_CHAR(age(ps.tgllahir), 'YY') AS umur, 
                ad.diagnosa1,
                    ad.ismasuk, 
                ad.diagnosa2,
                pd.statuspasien, 
                ps.alamatlengkap, 
                kp.kelompokpasien AS status, 
                CASE 
                    WHEN rm.namarekanan = 'Diri Sendiri' THEN 'UMUM' 
                    ELSE rm.namarekanan 
                END AS namarekanan,
                CASE 
                    WHEN kp.kelompokpasien = 'UMUM/PRIBADI' THEN '' 
                    ELSE ps.nobpjs 
                END AS nobpjs, 
                ru.namaruangan AS namapoli, 
                pg.namalengkap AS namadokter,
                CASE WHEN sk.statuskeluar = 'Pulang' THEN '✓' ELSE NULL END AS plg,
                CASE WHEN sk.statuskeluar = 'Meninggal' THEN '✓' ELSE NULL END AS mati,
                CASE WHEN sk.statuskeluar IS NULL THEN '✓' ELSE NULL END AS mrs,
                sk.statuskeluar,
                CASE WHEN kpm.kondisipasien = 'Dead On Arrival (DOA)' THEN '✓' ELSE NULL END AS doa,
                kpm.kondisipasien,
                kb.name,
                CASE 
                    WHEN pg3.namalengkap IS NOT NULL THEN pg3.namalengkap 
                    ELSE pg.namalengkap 
                END AS dokterjaga
            FROM pasiendaftar_t AS pd
            INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
            INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
            INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
            INNER JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
            LEFT JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
            LEFT JOIN produk_m AS pr ON pr.id = pp.produkfk
            LEFT JOIN pegawai_m AS pg2 ON pg2.id = apd.objectpegawaifk
            LEFT JOIN statuskeluar_m AS sk ON sk.id = pd.objectstatuskeluarfk
            LEFT JOIN kondisipasien_m AS kpm ON kpm.id = pd.objectkondisipasienfk
            LEFT JOIN ruangan_m AS ru2 ON ru2.id = apd.objectruanganasalfk
            LEFT JOIN pegawai_m AS pg3 ON pg3.id = pd.objectpegawaifk_dod 
            LEFT JOIN kebangsaan_m AS kb ON kb.id = ps.objectkebangsaanfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
            LEFT JOIN rekanan_m AS rm ON rm.id = pd.objectrekananfk
            LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
            LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
            LEFT JOIN all_diagnosa AS ad ON ad.norec_pd = pd.norec
            WHERE pd.statusenabled = true
            AND pd.kdprofile = 1
            AND pd.tglregistrasi::date BETWEEN '$tglAwal' AND '$tglAkhir'
            AND pp.isobat IS NULL
            AND ru.id = 323 
            $kelompokpasien
            $dokter
            $jeniskelamin
            GROUP BY 
                ps.nocm, 
                pd.tglregistrasi, 
                pd.noregistrasi, 
                ps.namapasien, 
                jk.jeniskelamin, 
                ps.tgllahir, 
                pd.statuspasien, 
                ps.alamatlengkap, 
                kp.kelompokpasien, 
                rm.namarekanan, 
                ps.nobpjs, 
                ru.namaruangan, 
                pg2.namalengkap,
                sk.statuskeluar,
                kpm.kondisipasien,
                pd.norec,
                pg3.namalengkap,
                pg.namalengkap,
                kb.name,
                ad.diagnosa1, 
                ad.diagnosa2,
                ad.ismasuk,
                apd.norec
            ORDER BY apd.norec,pd.noregistrasi ASC;
        "));

        // Coba
        $isGadar = DB::connection('mongodb')
                    ->table('AsesmenAwalMedisGawatDarurat')
                    ->select('Parameter_GawatDarurat', 'registrasi.norec_pd', 'created_at', 'updated_at')
                    ->whereNotNull('Parameter_GawatDarurat')
                    ->whereNull('namatemplate')
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('created_at', [$request['tglAwal'], $request['tglAkhir']]);
                        
                        // Add optional updated_at condition
                        $query->orWhereBetween('updated_at', [$request['tglAwal'], $request['tglAkhir']]);
                    })
                    ->get()
                    ->keyBy('registrasi.norec_pd');


                    // return $isGadar; 

            // Process data
            foreach ($data as $d) {
                // Check if the current norec_pd exists in $isGadar
                if(isset($isGadar[$d->norec_pd]) && $isGadar[$d->norec_pd]['Parameter_GawatDarurat'] == "Gawat Darurat") {
                    $d->E = '✓';
                } else if (isset($isGadar[$d->norec_pd]) && $isGadar[$d->norec_pd]['Parameter_GawatDarurat'] == "Tidak Gawat Darurat") {
                    $d->FE = '✓';
                } 
            }
            
            

        $result = array(
            'data' => $data,
            'message' => 'as@copot',
        );
        return $this->respond($result);
    }

    public function getRegisIGD(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        // $idRuangan = $request->ruanganId;
        $KelompokpasienId = $request->kpid;
        $DokterId = $request->dokter;
        $JkId = $request->jeniskelamin;
        // $KelasId = $request->kelas;
        // $QStatusPasien = $request->status;


        // $ruangan = '';
        // if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
        //     $ruangan = 'and ru.id = ' . $idRuangan;
        // }
        $kelompokpasien = '';
        if (isset($request->kpid) && $request->kpid != "undefined" && $request->kpid != null) {
            $kelompokpasien = 'and kp.id = ' . $KelompokpasienId;
        }
        $dokter = '';
        if (isset($request->dokter) && $request->dokter != "undefined" && $request->dokter != null) {
            $dokter = 'and pg2.id = ' . $DokterId;
        }
        $jeniskelamin = '';
        if (isset($request->jeniskelamin) && $request->jeniskelamin != "undefined" && $request->jeniskelamin != null) {
            $jeniskelamin = 'and jk.id = ' . $JkId;
        }
        //#WIth Mapp
        $data = DB::select(DB::raw("WITH all_apd AS (
                SELECT 
                    apd.norec,
                    apd.noregistrasifk,
                    apd.objectruanganfk
                FROM antrianpasiendiperiksa_t AS apd
                WHERE apd.noregistrasifk IN (
                    SELECT pd.norec 
                    FROM pasiendaftar_t AS pd
                    WHERE pd.statusenabled = true
                    AND pd.kdprofile = 1
                    AND pd.tglregistrasi::date BETWEEN '$tglAwal' AND '$tglAkhir'
                )
            ),
            all_diagnosa AS (
                SELECT DISTINCT ON (pd.norec)
                    pd.norec AS norec_pd,
                    string_agg(DISTINCT dm.namadiagnosa, '; ') AS diagnosa1, 
                    SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 1) AS diagnosa2,
                    ddp.ismasuk
                FROM pasiendaftar_t AS pd
                LEFT JOIN all_apd AS apd ON apd.noregistrasifk = pd.norec
                LEFT JOIN diagnosapasien_t AS dpt ON dpt.noregistrasifk = apd.norec
                LEFT JOIN detaildiagnosapasien_t AS ddp 
                    ON ddp.objectdiagnosapasienfk = dpt.norec 
                    AND ddp.objectjenisdiagnosarmfk = 1 
                    AND ddp.ismoi IS NULL
                    AND (
                        (apd.objectruanganfk = 322 AND ddp.objectjenisdiagnosarmfk = 1)
                        OR (apd.objectruanganfk <> 322 AND ddp.ismasuk = true)  
                        OR (apd.objectruanganfk <> 322 AND ddp.objectjenisdiagnosarmfk = 1) 
                    )
                LEFT JOIN diagnosa_m AS dm ON dm.id = ddp.objectdiagnosarmfk
                LEFT JOIN diagnosatindakanpasien_t AS dtp ON dtp.objectpasienfk = apd.norec
                LEFT JOIN (
                    SELECT 
                        ddt.noregistrasifk, 
                        dt.kddiagnosatindakan, 
                        dt.namadiagnosatindakan, 
                        ROW_NUMBER() OVER (
                                    PARTITION BY ddt.objectdiagnosatindakanpasienfk 
                                    ORDER BY ddt.created_at ASC
                        ) AS row_num
                    FROM detaildiagnosatindakanpasien_t ddt
                    INNER JOIN diagnosatindakan_m dt ON dt.id = ddt.objectdiagnosatindakanrmfk
                ) AS limited_ddt
                ON limited_ddt.noregistrasifk = dtp.objectpasienfk AND limited_ddt.row_num <= 1
                GROUP BY pd.norec, ddp.ismasuk, apd.objectruanganfk,ddp.objectjenisdiagnosarmfk
                ORDER BY 
                    pd.norec, 
                    CASE 
                    WHEN apd.objectruanganfk = 322 AND ddp.objectjenisdiagnosarmfk = 1 THEN 1
                        WHEN apd.objectruanganfk <> 322 AND ddp.ismasuk = true THEN 2  
                        WHEN apd.objectruanganfk <> 322 AND ddp.objectjenisdiagnosarmfk = 1 THEN 3 
                        ELSE 4  
                    END, 
                    ddp.ismasuk DESC  
            )

            SELECT DISTINCT on (apd.norec)
                ps.nocm, 
                TO_CHAR(pd.tglregistrasi, 'DD-Mon-YY') AS tglregistrasi, 
                TO_CHAR(pd.tglregistrasi, 'HH24:MI:SS') AS jamregistrasi,
                pd.tglregistrasi AS tgljamregistrasi,
                pd.noregistrasi,
                pd.norec AS norec_pd,  
                ps.namapasien, 
                jk.jeniskelamin, 
                TO_CHAR(age(ps.tgllahir), 'YY') AS umur, 
                ad.diagnosa1,
                    ad.ismasuk, 
                ad.diagnosa2,
                pd.statuspasien, 
                ps.alamatlengkap, 
                kp.kelompokpasien AS status, 
                CASE 
                    WHEN rm.namarekanan = 'Diri Sendiri' THEN 'UMUM' 
                    ELSE rm.namarekanan 
                END AS namarekanan,
                CASE 
                    WHEN kp.kelompokpasien = 'UMUM/PRIBADI' THEN '' 
                    ELSE ps.nobpjs 
                END AS nobpjs, 
                ru.namaruangan AS namapoli, 
                pg.namalengkap AS namadokter,
                CASE WHEN sk.statuskeluar = 'Pulang' THEN '✓' ELSE NULL END AS plg,
                CASE WHEN sk.statuskeluar = 'Meninggal' THEN '✓' ELSE NULL END AS mati,
                CASE WHEN sk.statuskeluar IS NULL THEN '✓' ELSE NULL END AS mrs,
                sk.statuskeluar,
                CASE WHEN kpm.kondisipasien = 'Dead On Arrival (DOA)' THEN '✓' ELSE NULL END AS doa,
                kpm.kondisipasien,
                kb.name,
                CASE 
                    WHEN pg3.namalengkap IS NOT NULL THEN pg3.namalengkap 
                    ELSE pg.namalengkap 
                END AS dokterjaga
            FROM pasiendaftar_t AS pd
            INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
            INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
            INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
            INNER JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
            LEFT JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
            LEFT JOIN produk_m AS pr ON pr.id = pp.produkfk
            LEFT JOIN pegawai_m AS pg2 ON pg2.id = apd.objectpegawaifk
            LEFT JOIN statuskeluar_m AS sk ON sk.id = pd.objectstatuskeluarfk
            LEFT JOIN kondisipasien_m AS kpm ON kpm.id = pd.objectkondisipasienfk
            LEFT JOIN ruangan_m AS ru2 ON ru2.id = apd.objectruanganasalfk
            LEFT JOIN pegawai_m AS pg3 ON pg3.id = pd.objectpegawaifk_dod 
            LEFT JOIN kebangsaan_m AS kb ON kb.id = ps.objectkebangsaanfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
            LEFT JOIN rekanan_m AS rm ON rm.id = pd.objectrekananfk
            LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
            LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
            LEFT JOIN all_diagnosa AS ad ON ad.norec_pd = pd.norec

            WHERE pd.statusenabled = true
            AND pd.kdprofile = 1
            AND pd.tglregistrasi::date BETWEEN '$tglAwal' AND '$tglAkhir'
            AND pp.isobat IS NULL
            AND ru.id = 322 
            $kelompokpasien
            $dokter
            $jeniskelamin
            GROUP BY 
                ps.nocm, 
                pd.tglregistrasi, 
                pd.noregistrasi, 
                ps.namapasien, 
                jk.jeniskelamin, 
                ps.tgllahir, 
                pd.statuspasien, 
                ps.alamatlengkap, 
                kp.kelompokpasien, 
                rm.namarekanan, 
                ps.nobpjs, 
                ru.namaruangan, 
                pg2.namalengkap,
                sk.statuskeluar,
                kpm.kondisipasien,
                pd.norec,
                pg3.namalengkap,
                pg.namalengkap,
                kb.name,
                ad.diagnosa1, 
                ad.diagnosa2,
                    ad.ismasuk,
                    apd.norec

            ORDER BY apd.norec,pd.noregistrasi ASC;
        "));

        // Coba
        $isGadar = DB::connection('mongodb')
                    ->table('AsesmenAwalMedisGawatDarurat')
                    ->select('Parameter_GawatDarurat', 'registrasi.norec_pd', 'created_at', 'updated_at')
                    ->whereNotNull('Parameter_GawatDarurat')
                    ->whereNull('namatemplate')
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('created_at', [$request['tglAwal'], $request['tglAkhir']]);
                        
                        // Add optional updated_at condition
                        $query->orWhereBetween('updated_at', [$request['tglAwal'], $request['tglAkhir']]);
                    })
                    ->get()
                    ->keyBy('registrasi.norec_pd');


                    // return $isGadar; 

            // Process data
            foreach ($data as $d) {
                // Check if the current norec_pd exists in $isGadar
                if(isset($isGadar[$d->norec_pd]) && $isGadar[$d->norec_pd]['Parameter_GawatDarurat'] == "Gawat Darurat") {
                    $d->E = '✓';
                } else if (isset($isGadar[$d->norec_pd]) && $isGadar[$d->norec_pd]['Parameter_GawatDarurat'] == "Tidak Gawat Darurat") {
                    $d->FE = '✓';
                } 
            }                    
            
            

        $result = array(
            'data' => $data,
            'message' => 'as@copot',
        );
        return $this->respond($result);
    }
    public function getPasienPindahRuangan(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("select distinct apd.tglmasuk::date tglmasuk, pd.noregistrasi, ps.nocm, ps.namapasien, jk.jeniskelamin,
        ru.namaruangan as ruanganasal, ru1.namaruangan as ruangantujuan, km.namakamar as kamarasal, km.namakamar as kamartujuan,
        TO_CHAR(age(ps.tgllahir), 'YY') as umur, pg.namalengkap
        from pasiendaftar_t as pd
        inner join pasien_m as ps on ps.id = pd.nocmfk
        inner join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
        inner join ruangan_m as ru on ru.id = apd.objectruanganasalfk and ru.objectdepartemenfk = 16
        left join kamar_m as km on km.id = apd.objectkamarfk
        inner join ruangan_m as ru1 on ru1.id = apd.objectruanganfk and ru1.objectdepartemenfk = 16
        left join kamar_m as km2 on km2.id = apd.objectkamarfk
        left join pegawai_m as pg on pg.id = pd.objectpegawaifk
        left join jeniskelamin_m jk on jk.id = ps.objectjeniskelaminfk
        left join kelompokpasien_m kp on kp.id = pd.objectkelompokpasienlastfk
        where pd.statusenabled = true
        and pd.kdprofile = 1
        and apd.tglmasuk::date between '$tglAwal' and '$tglAkhir'
        and apd.statusenabled = true
        and ru.namaruangan != ru1.namaruangan
        order by apd.tglmasuk::date"));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }
    public function getDataLaporanRL53PenyakitaRawatInap(Request $request)
    {
        $kdProfile      = (int) $this->kdProfile;
        $kdDeptRanapAll = explode(',', $this->settingFix('KdDepartemenRIAll'));
        $datadiagnosa = DB::table('antrianpasiendiperiksa_t as app')
            ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->leftJoin('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'app.objectruanganfk')
            ->leftJoin('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->select(
                'dm.kddiagnosa',
                'dm.namadiagnosa',
                'pd.noregistrasi',
                'pd.objectstatuskeluarfk',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'pd.tglmeninggal',
                'ru.reportdisplay',
                'app.noregistrasifk as noregistrasifk',
                'pd.statuspasien',
                'dpm.reportdisplay',
                'dpm.id'
            )
            ->where('app.kdprofile', $kdProfile)
            ->whereIn('dpm.id', $kdDeptRanapAll);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $datadiagnosa = $datadiagnosa->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $datadiagnosa = $datadiagnosa->where('app.objectruanganfk', '=', $request['idRuangan']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $datadiagnosa = $datadiagnosa->where('pd.tglregistrasi', '<=', $tgl);
        }
        $datadiagnosa = $datadiagnosa->get();

        $data10 = [];
        $jml = 0;
        $jmlM = 0;
        $jmlL = 0;
        $jmlLM = 0;
        $sama = false;
        $jmlP = 0;
        $jmlPM = 0;
        $jmlHidupMati = 0;
        foreach ($datadiagnosa as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->kddiagnosa == $data10[$i]['kddiagnosa']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    if ($item->objectjeniskelaminfk == 1 && ($item->objectstatuskeluarfk != 5 || $item->objectstatuskeluarfk == '')) {
                        $data10[$i]['jumlahLKH'] = (float)$hideung['jumlahLKH'] + 1;
                    } else if ($item->objectjeniskelaminfk == 2 && ($item->objectstatuskeluarfk != 5  || $item->objectstatuskeluarfk == '')) {
                        $data10[$i]['jumlahPRH'] = (float)$hideung['jumlahPRH'] + 1;
                    } else if ($item->objectjeniskelaminfk == 1 && $item->objectstatuskeluarfk == 5) {
                        $data10[$i]['jumlahLKM'] = (float)$hideung['jumlahLKM'] + 1;
                    } else if ($item->objectjeniskelaminfk == 2 && $item->objectstatuskeluarfk == 5) {
                        $data10[$i]['jumlahPRM'] = (float)$hideung['jumlahPRM'] + 1;
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->objectjeniskelaminfk == 1 && $item->objectstatuskeluarfk == 1) {
                    $jmlL = 1;
                    $jmlP = 0;
                    $jmlPM = 0;
                    $jmlLM = 0;
                } else if ($item->objectjeniskelaminfk == 2 && $item->objectstatuskeluarfk == 1) {
                    $jmlL = 0;
                    $jmlP = 1;
                    $jmlPM = 0;
                    $jmlLM = 0;
                } else if ($item->objectjeniskelaminfk == 1 && $item->objectstatuskeluarfk == 5) {
                    $jmlL = 0;
                    $jmlP = 0;
                    $jmlPM = 0;
                    $jmlLM = 1;
                } else if ($item->objectjeniskelaminfk == 2 && $item->objectstatuskeluarfk == 5) {
                    $jmlL = 0;
                    $jmlP = 0;
                    $jmlLM = 0;
                    $jmlPM = 1;
                }

                $data10[] = array(
                    'kddiagnosa' => $item->kddiagnosa,
                    'namadiagnosa' => $item->namadiagnosa,
                    'jumlah' => 1,
                    'jumlahLKH' => $jmlL,
                    'jumlahPRH' => $jmlP,
                    'jumlahLKM' => $jmlLM,
                    'jumlahPRM' => $jmlPM,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }
        $result = [
            'data' => $data10,
            'message' => 'success',

        ];

        return $this->respond($result);
    }
    public function getDataLaporanRL54PenyakitaRawatJalan(Request $request){
        $kdProfile = (int) $this->kdProfile;
        $KdDeptRajalAll = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $datadiagnosa = DB::table('antrianpasiendiperiksa_t as app')
            ->LEFTJOIN('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->LEFTJOIN('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->JOIN('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->LeftJOIN('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->LeftJOIN('ruangan_m as ru', 'ru.id', '=', 'app.objectruanganfk')
            ->LeftJOIN('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->select('dm.kddiagnosa', 'dm.namadiagnosa','app.statuspenyakit', 'pd.noregistrasi', 'ps.objectjeniskelaminfk', 'jk.jeniskelamin',
                'pd.tglregistrasi', 'pd.tglpulang', 'pd.tglmeninggal', 'ru.reportdisplay', 'app.noregistrasifk as noregistrasifk','pd.statuspasien',
                'dpm.reportdisplay', 'dpm.id')
            ->where('app.kdprofile', $kdProfile)
            ->whereIn('ru.objectdepartemenfk',$KdDeptRajalAll);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $datadiagnosa = $datadiagnosa->where('pd.tglpulang', '>=', $request['tglAwal']);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $datadiagnosa = $datadiagnosa->where('app.objectruanganfk', '=', $request['idRuangan']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $datadiagnosa = $datadiagnosa->where('pd.tglpulang', '<=', $tgl);
        }
        $datadiagnosa = $datadiagnosa->get();

        $data10 = [];
        $sama = false;
        $jml = 0;
        $jmlL = 0;
        $jmlP = 0;
        $jmlLL = 0;
        $jmlPL = 0;
        foreach ($datadiagnosa as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->kddiagnosa == $data10[$i]['kddiagnosa']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    if ($item->objectjeniskelaminfk == 1 && $item->statuspasien == 'BARU') {
                        $data10[$i]['jumlahLKH'] = (float)$hideung['jumlahLKH'] + 1;
                    } else if ($item->objectjeniskelaminfk == 2 && $item->statuspasien == 'BARU')  {
                        $data10[$i]['jumlahPRH'] = (float)$hideung['jumlahPRH'] + 1;
                    }
                    else if ($item->objectjeniskelaminfk == 1 && $item->statuspasien == 'LAMA') {
                        $data10[$i]['jumlahLKL'] = (float)$hideung['jumlahLKL'] + 1;
                    } else if ($item->objectjeniskelaminfk == 2 && $item->statuspasien == 'LAMA')  {
                        $data10[$i]['jumlahPRL'] = (float)$hideung['jumlahPRL'] + 1;
                    }
                    $data10[$i]['totalbaru'] = $data10[$i]['jumlahLKH'] + $data10[$i]['jumlahPRH'];
                    $data10[$i]['totallama'] = $data10[$i]['jumlahLKL'] + $data10[$i]['jumlahPRL'];
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                if ($item->objectjeniskelaminfk == 1 && $item-> statuspasien == 'BARU') {
                    $jmlL = 1;
                    $jmlP = 0;
                } else if ($item->objectjeniskelaminfk == 2 && $item->statuspasien == 'BARU')  {
                    $jmlL = 0;
                    $jmlP = 1;
                }

                $data10[] = array(
                    'kddiagnosa' => $item->kddiagnosa,
                    'namadiagnosa' => $item->namadiagnosa,
                    'jumlah' => 1,
                    'jumlahLKH' => $jmlL,
                    'jumlahPRH' => $jmlP,
                    'jumlahLKL' => $jmlLL,
                    'jumlahPRL' => $jmlPL,
                    'totalbaru' => $jmlL + $jmlP,
                    'totallama' => $jmlLL + $jmlPL,
                );
            }


            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }
            array_multisort($count, SORT_DESC, $data10);
        }

        $result = array(
            'data' => $data10,
            'message' => 'as@vandrian',

        );
        return $this->respond($result);
    }

    public function getDataRekapPengunjung(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("WITH Pengunjung AS (
                    SELECT 
                    CASE 
                    WHEN pd.statuspasien = 'LAMA' THEN 'Pengunjung Lama' 
                    WHEN pd.statuspasien = 'BARU' THEN 'Pengunjung Baru' 
                    ELSE 'Kategori Tidak Diketahui'
                    END AS jenispengunjung,
                    COUNT(*) AS jumlah
                    FROM pasiendaftar_t AS pd
                    WHERE pd.statusenabled = TRUE
                    AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    AND pd.kdprofile = 1
                    GROUP BY 
                    CASE 
                    WHEN pd.statuspasien = 'LAMA' THEN 'Pengunjung Lama' 
                    WHEN pd.statuspasien = 'BARU' THEN 'Pengunjung Baru' 
                    ELSE 'Kategori Tidak Diketahui' END
                )
                SELECT * FROM Pengunjung

                UNION ALL

                SELECT 
                'Total' AS jenispengunjung,
                SUM(jumlah) AS jumlah
                FROM Pengunjung
                ORDER BY jenispengunjung;"));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getIndexRanap(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $idRuangan = $request->ruanganId;
        $KelompokpasienId = $request->kpid;
        $DokterId = $request->dokter;
        $JkId = $request->jeniskelamin;
        $KelasId = $request->kelas;
        // $QStatusPasien = $request->status;


        $ruangan = '';
        if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
            $ruangan = 'and ru.id = ' . $idRuangan;
        }
        $kelompokpasien = '';
        if (isset($request->kpid) && $request->kpid != "undefined" && $request->kpid != null) {
            $kelompokpasien = 'and kp.id = ' . $KelompokpasienId;
        }
        $dokter = '';
        if (isset($request->dokter) && $request->dokter != "undefined" && $request->dokter != null) {
            $dokter = 'and pg2.id = ' . $DokterId;
        }
        $jeniskelamin = '';
        if (isset($request->jeniskelamin) && $request->jeniskelamin != "undefined" && $request->jeniskelamin != null) {
            $jeniskelamin = 'and jk.id = ' . $JkId;
        }
        $kelas = '';
        if (isset($request->kelas) && $request->kelas != "undefined" && $request->kelas != null) {
            $kelas = 'and kl.id = ' . $KelasId;
        }
        //#WIth Mapp
        $data = DB::select(DB::raw("WITH all_apd AS (
                    SELECT apd.norec,apd.noregistrasifk,apd.objectruanganfk
                    FROM antrianpasiendiperiksa_t AS apd
                    WHERE apd.noregistrasifk IN (
                    SELECT pd.norec 
                    FROM pasiendaftar_t AS pd
                    WHERE pd.statusenabled = true
                    AND pd.kdprofile = 1 AND pd.tglregistrasi::date BETWEEN '$tglAwal' AND '$tglAkhir'
                    )
                ),
                all_diagnosa AS (
                    SELECT 
                        pd.norec AS norec_pd,dm.kddiagnosa AS kodeicd10primer,dm.namadiagnosa AS namaicd10primer, ddp.ismasuk,
                        CASE WHEN dp.iskasusbaru = TRUE THEN 'BARU' WHEN dp.iskasusbaru = FALSE THEN 'LAMA' ELSE NULL END as kasus,
                        limited_ddt.kddiagnosatindakan, limited_ddt.namadiagnosatindakan,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 1) AS kodeicd10sekunder_1,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 2) AS kodeicd10sekunder_2,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 3) AS kodeicd10sekunder_3,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 4) AS kodeicd10sekunder_4,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 5) AS kodeicd10sekunder_5,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.kddiagnosa, ';'), ';', 6) AS kodeicd10sekunder_6,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 1) AS namaicd10sekunder_1,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 2) AS namaicd10sekunder_2,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 3) AS namaicd10sekunder_3,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 4) AS namaicd10sekunder_4,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 5) AS namaicd10sekunder_5,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddp2.namadiagnosa, ';'), ';', 6) AS namaicd10sekunder_6,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 1) AS kodeicd9_1,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 2) AS kodeicd9_2,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 3) AS kodeicd9_3,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 4) AS kodeicd9_4,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 5) AS kodeicd9_5,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.kddiagnosatindakan, ';'), ';', 6) AS kodeicd9_6,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 1) AS namaicd9_1,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 2) AS namaicd9_2,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 3) AS namaicd9_3,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 4) AS namaicd9_4,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 5) AS namaicd9_5,
                        SPLIT_PART(STRING_AGG(DISTINCT limited_ddt.namadiagnosatindakan, ';'), ';', 6) AS namaicd9_6
                    FROM pasiendaftar_t AS pd
                    LEFT JOIN all_apd AS apd ON apd.noregistrasifk = pd.norec
                    LEFT JOIN diagnosapasien_t as dp on dp.noregistrasifk = apd.norec
                    LEFT JOIN detaildiagnosapasien_t AS ddp ON ddp.objectdiagnosapasienfk = dp.norec 
                    AND ddp.objectjenisdiagnosarmfk = 1 AND ddp.ismoi is null AND ddp.ismasuk is null AND ddp.iskematian is null
                    LEFT JOIN diagnosatindakanpasien_t AS dtp ON dtp.objectpasienfk = apd.norec
                    LEFT JOIN (
                        SELECT 
                            ddt.noregistrasifk, 
                            dt.kddiagnosatindakan, 
                            dt.namadiagnosatindakan, 
                            ROW_NUMBER() OVER (
                            PARTITION BY ddt.objectdiagnosatindakanpasienfk 
                            ORDER BY ddt.created_at ASC
                            ) AS row_num
                            FROM detaildiagnosatindakanpasien_t ddt
                            INNER JOIN diagnosatindakan_m dt ON dt.id = ddt.objectdiagnosatindakanrmfk
                            inner join antrianpasiendiperiksa_t apd on apd.norec = ddt.noregistrasifk
							inner join pasiendaftar_t pd on pd.norec = apd.noregistrasifk
							where pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                            ) AS limited_ddt
                            ON limited_ddt.noregistrasifk = dtp.objectpasienfk AND limited_ddt.row_num <= 6
                    LEFT JOIN diagnosa_m AS dm ON dm.id = ddp.objectdiagnosarmfk
                    LEFT JOIN (
                        SELECT 
                            ddp2.noregistrasifk, 
                            d2.kddiagnosa, 
                            d2.namadiagnosa, 
                            ROW_NUMBER() OVER (
                                    PARTITION BY ddp2.noregistrasifk 
                                    ORDER BY ddp2.created_at ASC
                            ) AS row_num
                            FROM detaildiagnosapasien_t ddp2
                            INNER JOIN diagnosa_m d2 ON d2.id = ddp2.objectdiagnosarmfk
                            inner join antrianpasiendiperiksa_t apd on apd.norec = ddp2.noregistrasifk
							inner join pasiendaftar_t pd on pd.norec = apd.noregistrasifk
							where pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                        and ddp2.objectjenisdiagnosarmfk = 2
                        ) AS limited_ddp2 
                    ON limited_ddp2.noregistrasifk = dp.noregistrasifk AND limited_ddp2.row_num <= 6
                    GROUP BY pd.norec,ddp.ismasuk,dm.namadiagnosa,dm.kddiagnosa,dp.iskasusbaru,limited_ddt.kddiagnosatindakan, limited_ddt.namadiagnosatindakan
                                
                )
            SELECT DISTINCT on (pd.norec)
                    ps.nocm, pd.norec,
                    TO_CHAR(pd.tglregistrasi, 'YY-MM-DD') AS tglregistrasi,
                    TO_CHAR(pd.tglregistrasi, 'HH24:MI') AS jammasuk,
                    pd.tglregistrasi AS tgljamregistrasi,
                    pd.noregistrasi,  ps.namapasien,ps.alamatlengkap,ps.noidentitas as nik, jk.jeniskelamin, TO_CHAR(age(ps.tgllahir), 'YY') AS umur, pd.statuspasien,kp.kelompokpasien AS status, 
                    CASE WHEN rm.namarekanan = 'Diri Sendiri' THEN 'UMUM' else rm.namarekanan end as namarekanan,
                    pd.tglregistrasi::date tglmasuk, pd.tglpulang::date as tglkeluar,
                    (coalesce(pd.tglpulang::date, now()::date) - pd.tglregistrasi::date) + 1 as harirawat,
                    (coalesce(pd.tglpulang::date, now()::date) - pd.tglregistrasi::date)  AS lamarawat,
                    CASE WHEN kp.kelompokpasien = 'UMUM/PRIBADI' THEN '' ELSE ps.nobpjs END AS nobpjs, 
                    ru.namaruangan AS namapoli, 
                    pg2.namalengkap AS namadokter,
                    ar.asalrujukan,kl.namakelas,kd.kondisipasien,kb.name,
                    ad.kodeicd10primer,ad.namaicd10primer, 
                    ad.kodeicd10sekunder_1,ad.kodeicd10sekunder_2,ad.kodeicd10sekunder_3,
                    ad.kodeicd10sekunder_4,ad.kodeicd10sekunder_5,ad.kodeicd10sekunder_6,
                    ad.namaicd10sekunder_1,ad.namaicd10sekunder_2,ad.namaicd10sekunder_3,
                    ad.namaicd10sekunder_4,ad.namaicd10sekunder_5,ad.namaicd10sekunder_6,
					ad.kodeicd9_1,ad.kodeicd9_2,ad.kodeicd9_3,ad.kodeicd9_4,ad.kodeicd9_5,ad.kodeicd9_6,
                    ad.namaicd9_1,ad.namaicd9_2,ad.namaicd9_3,ad.namaicd9_4,ad.namaicd9_5,ad.namaicd9_6,
                    (CASE WHEN ap.jenispeserta IS NULL THEN NULL WHEN ap.jenispeserta IN ('PBI (APBD)', 'PBI (APBN)') THEN 'PBI' ELSE 'NON PBI' END) as jenispeserta,
					case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 0 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 7 then 1 else 0 end as jumlah07,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 8 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 28 then 1 else 0 end as jumlah828,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 > 28 then 1 else 0 end as jumlahkurang1,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 4 then 1 else 0 end as jumlah14,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 5 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 9 then 1 else 0 end as jumlah59,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 10 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 14 then 1 else 0 end as jumlah1014,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 15 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 19 then 1 else 0 end as jumlah1519,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 20 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 44 then 1 else 0 end as jumlah2044,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 45 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 54 then 1 else 0 end as jumlah4554,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 55 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 59 then 1 else 0 end as jumlah5559,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 60 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 69 then 1 else 0 end as jumlah6069,
                    case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 70 then 1 else 0 end as jumlah70,
                    pa.nmprovider,pa.nosep,ad.kasus
                FROM pasiendaftar_t AS pd
                INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                INNER JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                INNER JOIN pegawai_m AS pg2 ON pg2.id = apd.objectpegawaifk
                LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
                LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                LEFT JOIN rekanan_m AS rm ON rm.id = pd.objectrekananfk
                left JOIN pemakaianasuransi_t pa on pd.norec = pa.noregistrasifk
                left JOIN asuransipasien_m ap on pa.objectasuransipasienfk = ap.id
                LEFT JOIN all_diagnosa AS ad ON ad.norec_pd = pd.norec 
                LEFT JOIN asalrujukan_m AS ar ON ar.id = pd.asalrujukanfk
                LEFT JOIN kelas_m AS kl on kl.id = pd.objectkelasfk 
                LEFT JOIN kondisipasien_m as kd on kd.id = pd.objectkondisipasienfk 
                left join kebangsaan_m as kb on kb.id = ps.objectkebangsaanfk
                WHERE pd.statusenabled = TRUE
                AND pd.kdprofile = 1
                AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                AND ru.objectdepartemenfk = 16
				$ruangan
                $kelompokpasien
                $dokter
                $jeniskelamin
                $kelas				
                GROUP BY 
                    ps.nocm, 
                    pd.norec,
                    ps.id,
                    pd.tglregistrasi, 
                    pd.noregistrasi, 
                    ps.namapasien, 
                    pa.nosep,
                    jk.jeniskelamin, 
                    ps.tgllahir, 
                    pd.statuspasien, 
                    ps.alamatlengkap, 
                    kp.kelompokpasien, 
                    rm.namarekanan, 
                    ps.nobpjs, 
                    ru.namaruangan, 
                    ap.jenispeserta,
                    pg2.namalengkap,
                    ps.noidentitas,
                    apd.tglmasuk,
                    apd.tglkeluar,
                    ar.asalrujukan,
                    kl.namakelas,
                    pd.tglpulang,
                    kd.kondisipasien,
                    pa.nmprovider,
                    kb.name,
                    ad.kodeicd10primer,ad.namaicd10primer,ad.kodeicd10sekunder_1,ad.kodeicd10sekunder_2,
                    ad.kodeicd10sekunder_3,ad.kodeicd10sekunder_4,ad.kodeicd10sekunder_5,ad.kodeicd10sekunder_6,
                    ad.namaicd10sekunder_1,ad.namaicd10sekunder_2,ad.namaicd10sekunder_3,ad.namaicd10sekunder_4,
                    ad.namaicd10sekunder_5,ad.namaicd10sekunder_6,ad.kodeicd9_1,ad.kodeicd9_2,ad.kodeicd9_3,
                    ad.kodeicd9_4,ad.kodeicd9_5,ad.kodeicd9_6,ad.namaicd9_1,ad.namaicd9_2,ad.namaicd9_3,ad.namaicd9_4, 
                    ad.namaicd9_5,ad.namaicd9_6,ad.kasus
                    ORDER BY pd.norec,ps.namapasien ASC
                "));
        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL41PenyakitRanap(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT 
                        kddiagnosa, 
                        namadiagnosa,
                        SUM(hiduplaki) AS hiduplaki,
                        SUM(hidupperempuan) AS hidupperempuan,
                        COUNT(norec) AS jumlahhiduppl,
                        SUM(matilaki) as matilaki,
                        SUM(matiperempuan) as matiperempuan,
                        SUM(matiperempuan + matilaki) as matipl,
                        jumlahkurang1jaml,
                        jumlahkurang1jamp,
                        jumlahkurang23jaml,
                        jumlahkurang23jamp,
                        jumlah17hl,
                        jumlah17hp,
                        jumlah828hl,
                        jumlah828hp,
                        jumlah293l,
                        jumlah293p,
                        jumlah36l,
                        jumlah36p,
                        jumlah611l,
                        jumlah611p,
                        jumlah14l,
                        jumlah14p,
                        jumlah59l,
                        jumlah59p,
                        jumlah1014l,
                        jumlah1014p,
                        jumlah1519l,
                        jumlah1519p,
                        jumlah2024l,
                        jumlah2024p,
                        jumlah2529l,
                        jumlah2529p,
                        jumlah3034l,
                        jumlah3034p,
                        jumlah3539l,
                        jumlah3539p,
                        jumlah4044l,
                        jumlah4044p,
                        jumlah4549l,
                        jumlah4549p,
                        jumlah5054l,
                        jumlah5054p,
                        jumlah5559l,
                        jumlah5559p,
                        jumlah6064l,
                        jumlah6064p,
                        jumlah6569l,
                        jumlah6569p,
                        jumlah7074l,
                        jumlah7074p,
                        jumlah7579l,
                        jumlah7579p,
                        jumlah8084l,
                        jumlah8084p,
                        jumlah85l,
                        jumlah85p
                        FROM (
                        SELECT 
                            d.kddiagnosa, 
                            d.namadiagnosa, 
                            pd.norec,
                            CASE WHEN ps.objectjeniskelaminfk = 1 THEN 1 ELSE 0 END AS hiduplaki,
                            CASE WHEN ps.objectjeniskelaminfk = 2 THEN 1 ELSE 0 END AS hidupperempuan,
                            CASE WHEN ps.objectjeniskelaminfk = 1 and pd.objectstatuskeluarfk = 5 THEN 1 ELSE 0 END AS matilaki,
                            CASE WHEN ps.objectjeniskelaminfk = 2 and pd.objectstatuskeluarfk = 5 THEN 1 ELSE 0 END AS matiperempuan,
                            case when TO_CHAR(age(ps.tgllahir), 'HH')::int4 < 1 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlahkurang1jaml,
                            case when TO_CHAR(age(ps.tgllahir), 'HH')::int4 < 1 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlahkurang1jamp,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 0 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'HH')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'HH')::int4 < 23 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlahkurang23jaml,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 0 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'HH')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'HH')::int4 < 23 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlahkurang23jamp,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 < 7 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah17hl,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 < 7 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah17hp,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 8 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 < 28 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah828hl,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 8 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 < 28 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah828hp,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 0 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 29 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 < 3 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah293l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 0 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 29 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 < 3 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah293p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 3 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 < 6 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah36l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 3 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 < 6 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah36p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 6 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 11 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah611l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 6 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 11 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah611p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 4 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah14l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 4 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah14p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 5 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 9 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah59l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 5 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 9 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah59p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 10 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 14 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah1014l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 10 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 14 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah1014p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 15 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 19 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah1519l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 15 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 19 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah1519p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 20 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 24 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah2024l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 20 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 24 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah2024p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 25 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 29 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah2529l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 25 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 29 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah2529p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 30 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 34 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah3034l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 30 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 34 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah3034p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 35 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 39 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah3539l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 35 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 39 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah3539p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 40 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 44 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah4044l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 40 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 44 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah4044p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 45 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 49 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah4549l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 45 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 49 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah4549p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 50 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 50 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah5054l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 50 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 54 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah5054p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 55 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 59 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah5559l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 55 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 59 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah5559p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 60 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 64 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah6064l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 60 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 64 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah6064p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 65 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 69 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah6569l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 65 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 69 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah6569p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 70 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 74 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah7074l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 70 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 74 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah7074p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 75 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 79 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah7579l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 75 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 79 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah7579p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 80 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 84 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah8084l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 80 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 84 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah8084p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 85 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah85l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 85 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah85p
                            FROM pasiendaftar_t AS pd
                            INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                            INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                            INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                            INNER JOIN diagnosapasien_t AS dp ON dp.noregistrasifk = apd.norec
                            INNER JOIN detaildiagnosapasien_t AS ddp ON ddp.objectdiagnosapasienfk = dp.norec
                            INNER JOIN diagnosa_m AS d ON d.id = ddp.objectdiagnosarmfk
                            LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                            WHERE 
                                    pd.statusenabled = true
                                    AND pd.kdprofile = 1
                                    AND ru.objectdepartemenfk = 16
                                    AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                            ) AS x
                            GROUP BY kddiagnosa, namadiagnosa,
                        jumlahkurang1jaml,
                        jumlahkurang1jamp,
                        jumlahkurang23jaml,
                        jumlahkurang23jamp,
                        jumlah17hl,
                        jumlah17hp,
                        jumlah828hl,
                        jumlah828hp,
                        jumlah293l,
                        jumlah293p,
                        jumlah36l,
                        jumlah36p,
                        jumlah611l,
                        jumlah611p,
                        jumlah14l,
                        jumlah14p,
                        jumlah59l,
                        jumlah59p,
                        jumlah1014l,
                        jumlah1014p,
                        jumlah1519l,
                        jumlah1519p,
                        jumlah2024l,
                        jumlah2024p,
                        jumlah2529l,
                        jumlah2529p,
                        jumlah2529l,
                        jumlah2529p,
                        jumlah2529l,
                        jumlah2529p,
                        jumlah3034l,
                        jumlah3034p,
                        jumlah3539l,
                        jumlah3539p,
                        jumlah4044l,
                        jumlah4044p,
                        jumlah4549l,
                        jumlah4549p,
                        jumlah5054l,
                        jumlah5054p,
                        jumlah5559l,
                        jumlah5559p,
                        jumlah6064l,
                        jumlah6064p,
                        jumlah6569l,
                        jumlah6569p,
                        jumlah7074l,
                        jumlah7074p,
                        jumlah7579l,
                        jumlah7579p,
                        jumlah8084l,
                        jumlah8084p,
                        jumlah85l,
                        jumlah85p
                        "));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL42PenyakitRanap(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT 
                        kddiagnosa, 
                        namadiagnosa,
                        SUM(hiduplaki) AS hiduplaki,
                        SUM(hidupperempuan) AS hidupperempuan,
                        COUNT(norec) AS jumlahhiduppl,
                        SUM(matilaki) as matilaki,
                        SUM(matiperempuan) as matiperempuan,
                        SUM(matiperempuan + matilaki) as matipl
                    FROM (
                        SELECT 
                        d.kddiagnosa, 
                        d.namadiagnosa, 
                        pd.norec,
                        CASE WHEN ps.objectjeniskelaminfk = 1 THEN 1 ELSE 0 END AS hiduplaki,
                        CASE WHEN ps.objectjeniskelaminfk = 2 THEN 1 ELSE 0 END AS hidupperempuan,
                        CASE WHEN ps.objectjeniskelaminfk = 1 and pd.objectstatuskeluarfk = 5 THEN 1 ELSE 0 END AS matilaki,
                         CASE WHEN ps.objectjeniskelaminfk = 2 and pd.objectstatuskeluarfk = 5 THEN 1 ELSE 0 END AS matiperempuan
                        FROM pasiendaftar_t AS pd
                        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                        INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                        INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                        INNER JOIN diagnosapasien_t AS dp ON dp.noregistrasifk = apd.norec
                        INNER JOIN detaildiagnosapasien_t AS ddp ON ddp.objectdiagnosapasienfk = dp.norec
                        INNER JOIN diagnosa_m AS d ON d.id = ddp.objectdiagnosarmfk
                        LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                        WHERE 
                        pd.statusenabled = true
                        AND pd.kdprofile = 1
                        AND ru.objectdepartemenfk = 16
                        AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    ) AS x
                    GROUP BY kddiagnosa, namadiagnosa
                    ORDER BY jumlahhiduppl DESC
                    LIMIT 10;
                        "));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL43KematianRanap(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT 
                        kddiagnosa, 
                        namadiagnosa,
                        SUM(hiduplaki) AS hiduplaki,
                        SUM(hidupperempuan) AS hidupperempuan,
                        COUNT(norec) AS jumlahhiduppl,
                        SUM(matilaki) as matilaki,
                        SUM(matiperempuan) as matiperempuan,
                        SUM(matiperempuan + matilaki) as matipl
                    FROM (
                        SELECT 
                        d.kddiagnosa, 
                        d.namadiagnosa, 
                        pd.norec,
                        CASE WHEN ps.objectjeniskelaminfk = 1 THEN 1 ELSE 0 END AS hiduplaki,
                        CASE WHEN ps.objectjeniskelaminfk = 2 THEN 1 ELSE 0 END AS hidupperempuan,
                        CASE WHEN ps.objectjeniskelaminfk = 1 and pd.objectstatuskeluarfk = 5 THEN 1 ELSE 0 END AS matilaki,
                         CASE WHEN ps.objectjeniskelaminfk = 2 and pd.objectstatuskeluarfk = 5 THEN 1 ELSE 0 END AS matiperempuan
                        FROM pasiendaftar_t AS pd
                        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                        INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                        INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                        INNER JOIN diagnosapasien_t AS dp ON dp.noregistrasifk = apd.norec
                        INNER JOIN detaildiagnosapasien_t AS ddp ON ddp.objectdiagnosapasienfk = dp.norec
                        INNER JOIN diagnosa_m AS d ON d.id = ddp.objectdiagnosarmfk
                        LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                        WHERE 
                        pd.statusenabled = true
                        AND pd.kdprofile = 1
                        AND ru.objectdepartemenfk = 16
                        AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    ) AS x
                    GROUP BY kddiagnosa, namadiagnosa
                    ORDER BY matipl DESC
                    LIMIT 10;
                        "));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL51PenyakitRawatJalan(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT 
                        kddiagnosa, 
                        namadiagnosa,
                        SUM(barulaki) AS barulaki,
                        SUM(baruperempuan) AS baruperempuan,
                        SUM(barulaki + baruperempuan) as totalbaru,
                        SUM(kunjlaki) as kunjlaki,
                        SUM(kunjperempuan) as kunjperempuan,
                        SUM(kunjlaki + kunjperempuan) as totalkunj,
                        jumlahkurang1jaml,
                        jumlahkurang1jamp,
                        jumlahkurang23jaml,
                        jumlahkurang23jamp,
                        jumlah17hl,
                        jumlah17hp,
                        jumlah828hl,
                        jumlah828hp,
                        jumlah293l,
                        jumlah293p,
                        jumlah36l,
                        jumlah36p,
                        jumlah611l,
                        jumlah611p,
                        jumlah14l,
                        jumlah14p,
                        jumlah59l,
                        jumlah59p,
                        jumlah1014l,
                        jumlah1014p,
                        jumlah1519l,
                        jumlah1519p,
                        jumlah2024l,
                        jumlah2024p,
                        jumlah2529l,
                        jumlah2529p,
                        jumlah3034l,
                        jumlah3034p,
                        jumlah3539l,
                        jumlah3539p,
                        jumlah4044l,
                        jumlah4044p,
                        jumlah4549l,
                        jumlah4549p,
                        jumlah5054l,
                        jumlah5054p,
                        jumlah5559l,
                        jumlah5559p,
                        jumlah6064l,
                        jumlah6064p,
                        jumlah6569l,
                        jumlah6569p,
                        jumlah7074l,
                        jumlah7074p,
                        jumlah7579l,
                        jumlah7579p,
                        jumlah8084l,
                        jumlah8084p,
                        jumlah85l,
                        jumlah85p
                        FROM (
                        SELECT 
                            d.kddiagnosa, 
                            d.namadiagnosa, 
                            pd.norec,
                            CASE WHEN ps.objectjeniskelaminfk = 1 and pd.statuspasien ilike '%BARU%' THEN 1 ELSE 0 END AS barulaki,
                            CASE WHEN ps.objectjeniskelaminfk = 2 and pd.statuspasien ilike '%BARU%' THEN 1 ELSE 0 END AS baruperempuan,
                            CASE WHEN ps.objectjeniskelaminfk = 1  THEN 1 ELSE 0 END AS kunjlaki,
                            CASE WHEN ps.objectjeniskelaminfk = 2 THEN 1 ELSE 0 END AS kunjperempuan,
                             case when TO_CHAR(age(ps.tgllahir), 'HH')::int4 < 1 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlahkurang1jaml,
                            case when TO_CHAR(age(ps.tgllahir), 'HH')::int4 < 1 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlahkurang1jamp,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 0 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'HH')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'HH')::int4 < 23 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlahkurang23jaml,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 0 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'HH')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'HH')::int4 < 23 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlahkurang23jamp,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 < 7 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah17hl,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 < 7 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah17hp,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 8 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 < 28 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah828hl,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 1 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 8 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 < 28 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah828hp,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 0 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 29 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 < 3 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah293l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 0 and TO_CHAR(age(ps.tgllahir), 'DD')::int4 >= 29 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 < 3 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah293p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 3 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 < 6 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah36l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 3 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 < 6 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah36p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 6 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 11 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah611l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 < 1 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 >= 6 and TO_CHAR(age(ps.tgllahir), 'MM')::int4 <= 11 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah611p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 4 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah14l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 1 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 4 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah14p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 5 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 9 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah59l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 5 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 9 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah59p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 10 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 14 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah1014l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 10 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 14 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah1014p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 15 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 19 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah1519l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 15 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 19 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah1519p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 20 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 24 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah2024l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 20 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 24 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah2024p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 25 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 29 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah2529l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 25 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 29 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah2529p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 30 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 34 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah3034l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 30 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 34 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah3034p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 35 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 39 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah3539l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 35 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 39 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah3539p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 40 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 44 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah4044l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 40 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 44 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah4044p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 45 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 49 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah4549l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 45 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 49 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah4549p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 50 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 50 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah5054l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 50 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 54 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah5054p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 55 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 59 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah5559l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 55 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 59 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah5559p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 60 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 64 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah6064l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 60 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 64 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah6064p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 65 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 69 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah6569l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 65 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 69 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah6569p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 70 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 74 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah7074l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 70 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 74 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah7074p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 75 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 79 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah7579l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 75 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 79 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah7579p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 80 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 84 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah8084l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 80 and TO_CHAR(age(ps.tgllahir), 'YY')::int4 <= 84 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah8084p,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 85 and ps.objectjeniskelaminfk = 1 then 1 else 0 end as jumlah85l,
                            case when TO_CHAR(age(ps.tgllahir), 'YY')::int4 >= 85 and ps.objectjeniskelaminfk = 2 then 1 else 0 end as jumlah85p
                            FROM pasiendaftar_t AS pd
                            INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                            INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                            INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                            INNER JOIN diagnosapasien_t AS dp ON dp.noregistrasifk = apd.norec
                            INNER JOIN detaildiagnosapasien_t AS ddp ON ddp.objectdiagnosapasienfk = dp.norec
                            INNER JOIN diagnosa_m AS d ON d.id = ddp.objectdiagnosarmfk
                            LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                            WHERE 
                                    pd.statusenabled = true
                                    AND pd.kdprofile = 1
                                    AND ru.objectdepartemenfk = 18
                                    AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                            ) AS x
                            GROUP BY kddiagnosa, namadiagnosa,
                        jumlahkurang1jaml,
                        jumlahkurang1jamp,
                        jumlahkurang23jaml,
                        jumlahkurang23jamp,
                        jumlah17hl,
                        jumlah17hp,
                        jumlah828hl,
                        jumlah828hp,
                        jumlah293l,
                        jumlah293p,
                        jumlah36l,
                        jumlah36p,
                        jumlah611l,
                        jumlah611p,
                        jumlah14l,
                        jumlah14p,
                        jumlah59l,
                        jumlah59p,
                        jumlah1014l,
                        jumlah1014p,
                        jumlah1519l,
                        jumlah1519p,
                        jumlah2024l,
                        jumlah2024p,
                        jumlah2529l,
                        jumlah2529p,
                        jumlah2529l,
                        jumlah2529p,
                        jumlah2529l,
                        jumlah2529p,
                        jumlah3034l,
                        jumlah3034p,
                        jumlah3539l,
                        jumlah3539p,
                        jumlah4044l,
                        jumlah4044p,
                        jumlah4549l,
                        jumlah4549p,
                        jumlah5054l,
                        jumlah5054p,
                        jumlah5559l,
                        jumlah5559p,
                        jumlah6064l,
                        jumlah6064p,
                        jumlah6569l,
                        jumlah6569p,
                        jumlah7074l,
                        jumlah7074p,
                        jumlah7579l,
                        jumlah7579p,
                        jumlah8084l,
                        jumlah8084p,
                        jumlah85l,
                        jumlah85p
                        "));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getDataLaporanRL5210PenyakitRawatJalan(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT 
                        kddiagnosa, 
                        namadiagnosa,
                        SUM(barulaki) AS barulaki,
                        SUM(baruperempuan) AS baruperempuan,
                        SUM(barulaki + baruperempuan) as totalbaru,
                        SUM(kunjlaki) as kunjlaki,
                        SUM(kunjperempuan) as kunjperempuan,
                        SUM(kunjlaki + kunjperempuan) as totalkunj
                    FROM (
                        SELECT 
                        d.kddiagnosa, 
                        d.namadiagnosa, 
                        pd.norec,
                        CASE WHEN ps.objectjeniskelaminfk = 1 and ddp.iskasusbaru = true THEN 1 ELSE 0 END AS barulaki,
                        CASE WHEN ps.objectjeniskelaminfk = 2 and ddp.iskasusbaru = true THEN 1 ELSE 0 END AS baruperempuan,
                        CASE WHEN ps.objectjeniskelaminfk = 1  THEN 1 ELSE 0 END AS kunjlaki,
                         CASE WHEN ps.objectjeniskelaminfk = 2 THEN 1 ELSE 0 END AS kunjperempuan
                        FROM pasiendaftar_t AS pd
                        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                        INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                        INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                        INNER JOIN diagnosapasien_t AS dp ON dp.noregistrasifk = apd.norec
                        INNER JOIN detaildiagnosapasien_t AS ddp ON ddp.objectdiagnosapasienfk = dp.norec
                        INNER JOIN diagnosa_m AS d ON d.id = ddp.objectdiagnosarmfk
                        LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                        WHERE 
                        pd.statusenabled = true
                        AND pd.kdprofile = 1
                        AND ru.objectdepartemenfk = 18
                        AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    ) AS x
                    GROUP BY kddiagnosa, namadiagnosa
                    ORDER BY totalbaru DESC
                    LIMIT 10;
                        "));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getDataLaporanRL5310PenyakitRawatJalan(Request $request)
    {
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        //#WIth Mapp
        $data = DB::select(DB::raw("SELECT 
                        kddiagnosa, 
                        namadiagnosa,
                        SUM(barulaki) AS barulaki,
                        SUM(baruperempuan) AS baruperempuan,
                        SUM(barulaki + baruperempuan) as totalbaru,
                        SUM(kunjlaki) as kunjlaki,
                        SUM(kunjperempuan) as kunjperempuan,
                        SUM(kunjlaki + kunjperempuan) as totalkunj
                    FROM (
                        SELECT 
                        d.kddiagnosa, 
                        d.namadiagnosa, 
                        pd.norec,
                        CASE WHEN ps.objectjeniskelaminfk = 1 and ddp.iskasusbaru = true THEN 1 ELSE 0 END AS barulaki,
                        CASE WHEN ps.objectjeniskelaminfk = 2 and ddp.iskasusbaru = true THEN 1 ELSE 0 END AS baruperempuan,
                        CASE WHEN ps.objectjeniskelaminfk = 1  THEN 1 ELSE 0 END AS kunjlaki,
                         CASE WHEN ps.objectjeniskelaminfk = 2 THEN 1 ELSE 0 END AS kunjperempuan
                        FROM pasiendaftar_t AS pd
                        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                        INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                        INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                        INNER JOIN ddiagnosapasien_t AS dp ON dp.noregistrasifk = apd.norec
                        INNER JOIN detaildiagnosapasien_t AS ddp ON ddp.objectdiagnosapasienfk = dp.norec
                        INNER JOIN diagnosa_m AS d ON d.id = ddp.objectdiagnosarmfk
                        LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                        WHERE 
                        pd.statusenabled = true
                        AND pd.kdprofile = 1
                        AND ru.objectdepartemenfk = 18
                        AND pd.tglregistrasi::DATE BETWEEN '$tglAwal' AND '$tglAkhir'
                    ) AS x
                    GROUP BY kddiagnosa, namadiagnosa
                    ORDER BY totalkunj DESC
                    LIMIT 10;
                        "));

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLapSensusRanap(Request $request)
    {
        ini_set('max_execution_time', 6000);
        $idProfile = $this->kdProfile;

        $tgl_permulaan = '2024-10-31 00:00';

        $ruanganasal = $request['ruanganAsal'];
        $idruangan = $request['idRuangan'];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];

        $idRuangan = $request->ruanganId;

        // $ruangan = '';
        // if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
        //     $ruangan = 'and ru.id = ' . $idRuangan;
        // }
        // $ruanganasal = '';
        // if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
        //     $ruangan = 'and ru.id = ' . $idRuangan;
        // }
        // $tglAwal = $request->tglAwal;
        // $tglAkhir = $request->tglAkhir;
        // $idProfile = (int) $kdProfile;

        $query = "
        
        WITH 
            tanggal_series AS (
                SELECT date::date AS tgl_filter
                FROM generate_series('".$tgl_permulaan.")'::date, '".$tglAkhir."'::date, '1 day') AS date
            ),
                
            data_pasien AS (
         
                SELECT 
                    rpp.tglmasuk::date AS tanggal_masuk,
                    COUNT(DISTINCT pd.noregistrasi) AS jumlah_pasien
                FROM pasiendaftar_t AS pd
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                left JOIN registrasipelayananpasien_t AS rpp ON rpp.noregistrasifk = pd.norec
                WHERE 
                        pd.kdprofile = $idProfile
                    AND rpp.tglmasuk::date IN (SELECT tgl_filter FROM tanggal_series)
                    AND apd.objectruanganfk =  '".$idRuangan."' 
                   
                GROUP BY rpp.tglmasuk::date
 
            ),
            
            data_pasien_kemarin as (

                SELECT 
            
                    EXTRACT(DAY FROM  ts.tgl_filter) AS tanggalmasuk,
                    COALESCE(dp.jumlah_pasien, 0) AS pasien_hari_ini,
                    LAG(COALESCE(dp.jumlah_pasien, 0), 1, 0) OVER (ORDER BY ts.tgl_filter) AS banyaknyapasiendiharisebelumnya,
                    SUM(COALESCE(dp.jumlah_pasien, 0)) OVER (ORDER BY ts.tgl_filter) AS total_kumulatif
                FROM tanggal_series ts

                LEFT JOIN data_pasien dp ON ts.tgl_filter = dp.tanggal_masuk
            
            ),
            
            
            
            data_pasien_masuk  as ( 
            
                select 
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk, 
                    SUM(CASE WHEN apd.tglmasuk IS NOT NULL THEN 1 ELSE 0 END) AS banyakpasienmasuk 
                FROM pasiendaftar_t as pd
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN registrasipelayananpasien_t as rpp on rpp.noregistrasifk = pd.norec
                                                       
                LEFT JOIN batalregistrasi_t as br on br.pasiendaftarfk = pd.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN kelas_m as kls on kls.id = apd.objectkelasfk
                INNER JOIN ruangan_m as ru2 on ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m as ru3 on ru3.id = rpp.objectruanganasalfk 
                                               
                where 
                    pd.kdprofile = $idProfile
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'  
                    AND (pd.objectruanganasalfk = '322' or pd.objectruanganasalfk='318')
                    and ru2.id= '".$idRuangan."'  
                    and rpp.objectruanganfk = apd.objectruanganfk
                    and rpp.tglmasuk = apd.tglmasuk
                    and (rpp.objectstatuskeluarfk is null or rpp.objectstatuskeluarfk <>2)
                    
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
                                              
            ),
            
            
            
            data_pasien_pindahan as (
            
                select 
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk, 
                    SUM(CASE WHEN apd.tglmasuk IS NOT NULL THEN 1 ELSE 0 END) AS banyakpasienpindahan 
                FROM 
                    pasiendaftar_t AS pd
                INNER JOIN 
                    antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                INNER JOIN 
                    registrasipelayananpasien_t AS rpp ON rpp.noregistrasifk = pd.norec
                LEFT JOIN 
                    batalregistrasi_t AS br ON br.pasiendaftarfk = pd.norec
                INNER JOIN 
                    pasien_m AS pm ON pm.id = pd.nocmfk
                INNER JOIN 
                    kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN 
                    kelas_m AS kls ON kls.id = apd.objectkelasfk
                INNER JOIN 
                    ruangan_m AS ru2 ON ru2.id = rpp.objectruanganfk
                inner JOIN 
                    ruangan_m AS ru3 ON ru3.id = rpp.objectruanganasalfk 
                
                WHERE 
                    pd.kdprofile = $idProfile 
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND (ru3.objectdepartemenfk = 16 AND ru2.objectdepartemenfk = 16)
                    AND (ru3.id = 319 OR ru3.id = 318)
                    AND rpp.objectruanganasalfk <>  '".$idRuangan."' 
                    AND rpp.objectruanganasalfk <> '322' 
                    AND rpp.objectruanganfk =  '".$idRuangan."'  
                    AND rpp.objectruanganfk = apd.objectruanganfk
                    and rpp.tglmasuk = apd.tglmasuk
                    and (rpp.objectstatuskeluarfk is null or rpp.objectstatuskeluarfk <>2)
                
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
            
            ),
            
             
            
            daily_stats AS (

                SELECT
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggal,
                    SUM(CASE WHEN pd.tglmeninggal IS NULL and pd.objectstatuskeluarfk<>5 THEN 1 ELSE 0 END) AS banyakpasienkeluarhidup,
                    SUM(CASE WHEN pd.objectruanganlastfk =  '".$idRuangan."'    and EXTRACT(EPOCH FROM (pd.tglpulang - pd.tglregistrasi)) <= 47 * 3600 and  pd.tglmeninggal IS not NULL and pd.objectstatuskeluarfk=5  THEN 1 ELSE 0 END) AS pasienmeninggalkurangdari48jam,
                    SUM(CASE WHEN pd.objectruanganlastfk =  '".$idRuangan."'    and EXTRACT(EPOCH FROM (pd.tglpulang - pd.tglregistrasi)) >= 48 * 3600 and  pd.tglmeninggal IS not NULL and pd.objectstatuskeluarfk=5  THEN 1 ELSE 0 END) AS pasienmeninggallebihdari48jam,
                    COALESCE(SUM(EXTRACT(DAY FROM AGE(pd.tglpulang, apd.tglmasuk))::INTEGER), 0) AS lama_hari_perawatan, 
                    SUM(CASE WHEN pd.tglpulang IS NULL THEN 1 ELSE 0 END) AS totalpasienyangmasihdirawat,
                    SUM(CASE WHEN rpp.objectruanganfk = '322' IS NULL THEN 1 ELSE 0 END) AS total_pasien_dipindahkan ,
                    SUM(CASE WHEN pd.tglpulang IS NULL THEN 1 ELSE 0 END) AS total_pasien_keluar_hidup 
                
                FROM pasiendaftar_t AS pd
                JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                LEFT JOIN registrasipelayananpasien_t as rpp on rpp.noregistrasifk = pd.norec
                                                            and apd.tglkeluar = rpp.tglpindah
                                                            and rpp.objectstatuskeluarfk = 2
                WHERE 
                        pd.kdprofile = $idProfile 
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND dp.id = 16 AND ru.id =  '".$idRuangan."' 
                
                    GROUP BY EXTRACT(DAY FROM apd.tglmasuk)
            ),

            
            cumulative_patients AS (
                SELECT
                    tanggal,                  
                    COALESCE(SUM(totalpasienyangmasihdirawat) 
                    OVER (ORDER BY tanggal ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING), 0) AS banyaknyapasiendiharisebelumnya
                FROM daily_stats
            ) ,
                                            
            
            data_pasien_dipindahkan as (
                
                select 
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk, 
                    SUM(CASE WHEN apd.tglmasuk IS NOT NULL THEN 1 ELSE 0 END) AS banyakpasiendipindahkan 
                FROM pasiendaftar_t as pd
               
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN registrasipelayananpasien_t as rpp on rpp.noregistrasifk = pd.norec
                                                        
                LEFT JOIN batalregistrasi_t as br on br.pasiendaftarfk = pd.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN kelas_m as kls on kls.id = apd.objectkelasfk
                INNER JOIN ruangan_m as ru2 on ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m as ru3 on ru3.id = rpp.objectruanganasalfk 
                                                
                left join alamat_m as alm on alm.nocmfk = pm.id
                where 
                    pd.kdprofile = $idProfile  
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND ru3.id =  '".$idRuangan."' 
                    AND rpp.objectruanganfk = apd.objectruanganfk
                    and rpp.tglmasuk = apd.tglmasuk
                    and pd.objectstatuskeluarfk = 2
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
                                               
            ),

            data_pasien_pulang as (
                
                select 
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk, 
                    SUM(CASE WHEN apd.tglmasuk IS NOT NULL THEN 1 ELSE 0 END) AS banyakpasienpulang 
                FROM pasiendaftar_t as pd
               
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN registrasipelayananpasien_t as rpp on rpp.noregistrasifk = pd.norec
                                                        
                LEFT JOIN batalregistrasi_t as br on br.pasiendaftarfk = pd.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN kelas_m as kls on kls.id = apd.objectkelasfk
                INNER JOIN ruangan_m as ru2 on ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m as ru3 on ru3.id = rpp.objectruanganasalfk 
                                                
                left join alamat_m as alm on alm.nocmfk = pm.id
                where 
                    pd.kdprofile = $idProfile  
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND ru3.id =  '".$idRuangan."' 
                    AND rpp.objectruanganfk = apd.objectruanganfk
                    and rpp.tglmasuk = apd.tglmasuk
                    and pd.objectstatuskeluarfk = 6
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
                                               
            ),

            data_pasien_dirujuk as (
                
                select 
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk, 
                    SUM(CASE WHEN apd.tglmasuk IS NOT NULL THEN 1 ELSE 0 END) AS banyakpasiendirujuk
                FROM pasiendaftar_t as pd
               
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN registrasipelayananpasien_t as rpp on rpp.noregistrasifk = pd.norec
                                                        
                LEFT JOIN batalregistrasi_t as br on br.pasiendaftarfk = pd.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN kelas_m as kls on kls.id = apd.objectkelasfk
                INNER JOIN ruangan_m as ru2 on ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m as ru3 on ru3.id = rpp.objectruanganasalfk 
                                                
                left join alamat_m as alm on alm.nocmfk = pm.id
                where 
                    pd.kdprofile = $idProfile  
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND ru3.id =  '".$idRuangan."' 
                    AND rpp.objectruanganfk = apd.objectruanganfk
                    and rpp.tglmasuk = apd.tglmasuk
                    and pd.objectstatuskeluarfk = 4
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
                                               
            ),

            data_pasien_pindahrslain as (
                
                select 
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk, 
                    SUM(CASE WHEN apd.tglmasuk IS NOT NULL THEN 1 ELSE 0 END) AS banyakpasienpindahrslain 
                FROM pasiendaftar_t as pd
               
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN registrasipelayananpasien_t as rpp on rpp.noregistrasifk = pd.norec
                                                        
                LEFT JOIN batalregistrasi_t as br on br.pasiendaftarfk = pd.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN kelas_m as kls on kls.id = apd.objectkelasfk
                INNER JOIN ruangan_m as ru2 on ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m as ru3 on ru3.id = rpp.objectruanganasalfk 
                                                
                left join alamat_m as alm on alm.nocmfk = pm.id
                where 
                    pd.kdprofile = $idProfile  
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND ru3.id =  '".$idRuangan."' 
                    AND rpp.objectruanganfk = apd.objectruanganfk
                    and rpp.tglmasuk = apd.tglmasuk
                    and pd.objectstatuskeluarfk = 2
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
                                               
            ),

            data_pasien_aps as (
                
                select 
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk, 
                    SUM(CASE WHEN apd.tglmasuk IS NOT NULL THEN 1 ELSE 0 END) AS banyakpasienaps
                FROM pasiendaftar_t as pd
               
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN registrasipelayananpasien_t as rpp on rpp.noregistrasifk = pd.norec
                                                        
                LEFT JOIN batalregistrasi_t as br on br.pasiendaftarfk = pd.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN kelas_m as kls on kls.id = apd.objectkelasfk
                INNER JOIN ruangan_m as ru2 on ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m as ru3 on ru3.id = rpp.objectruanganasalfk 
                                                
                left join alamat_m as alm on alm.nocmfk = pm.id
                where 
                    pd.kdprofile = $idProfile  
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND ru3.id =  '".$idRuangan."' 
                    AND rpp.objectruanganfk = apd.objectruanganfk
                    and rpp.tglmasuk = apd.tglmasuk
                    and pd.objectstatuskeluarfk = 6
                    and pd.objectstatuspulangfk = 3
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
                                               
            ),
            
            
            
            data_lama_perawatan as (
                
                SELECT
                    
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk,
                    SUM((DATE_TRUNC('day', pd.tglpulang)::date - DATE_TRUNC('day', apd.tglmasuk)::date) + 1) AS total_lama_dirawat_tanpa_pindah,
                    
                    SUM(CASE 
                        WHEN rpp.tglmasuk IS NOT NULL 
                        THEN (DATE_TRUNC('day', rpp.tglmasuk)::date - DATE_TRUNC('day', apd.tglmasuk)::date) + 1 
                        ELSE 0 
                    END) AS total_lama_dirawat_dengan_pindah,
                    
                    -- Total lama hari perawatan (gabungan dari kedua kondisi)
                    SUM(
                        (DATE_TRUNC('day', pd.tglpulang)::date - DATE_TRUNC('day', apd.tglmasuk)::date) + 1
                        + CASE 
                            WHEN rpp.tglmasuk IS NOT NULL 
                            THEN (DATE_TRUNC('day', rpp.tglmasuk)::date - DATE_TRUNC('day', apd.tglmasuk)::date) + 1 
                            ELSE 0 
                        END
                    ) AS total_lama_hari_perawatan
                    
                FROM pasiendaftar_t AS pd
                
                JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                LEFT JOIN registrasipelayananpasien_t AS rpp 
                    ON rpp.noregistrasifk = pd.norec AND apd.tglkeluar = rpp.tglpindah
                INNER JOIN pasien_m AS pm ON pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                LEFT JOIN ruangan_m AS ru2 ON ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m AS ru3 ON ru3.id = rpp.objectruanganasalfk
               
                WHERE 
                    pd.kdprofile = $idProfile  
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND ru.id =  '".$idRuangan."' 
               
                    GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
                                  
            ),
            
            
            data_kelas_pasien as (
            
                SELECT
                    EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk,
                
                    SUM(CASE WHEN pd.objectkelasfk = 8 THEN 1 ELSE 0 END) AS suite,
                    SUM(CASE WHEN pd.objectkelasfk = 5 THEN 1 ELSE 0 END) AS vvip,
                    SUM(CASE WHEN pd.objectkelasfk = 4 THEN 1 ELSE 0 END) AS vip,
                    SUM(CASE WHEN pd.objectkelasfk = 6 THEN 1 ELSE 0 END) AS nonkelas,
                    SUM(CASE WHEN pd.objectkelasfk = 3 THEN 1 ELSE 0 END) AS kelas1,
                    SUM(CASE WHEN pd.objectkelasfk = 2 THEN 1 ELSE 0 END) AS kelas2,
                    SUM(CASE WHEN pd.objectkelasfk = 1 THEN 1 ELSE 0 END) AS kelas3
                    
                FROM pasiendaftar_t AS pd
                JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                LEFT JOIN registrasipelayananpasien_t AS rpp 
                    ON rpp.noregistrasifk = pd.norec AND apd.tglkeluar = rpp.tglpindah
                INNER JOIN pasien_m AS pm ON pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN kelas_m AS kls ON kls.id = apd.objectkelasfk
                LEFT JOIN ruangan_m AS ru2 ON ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m AS ru3 ON ru3.id = rpp.objectruanganasalfk
                                        
                WHERE   
                        pd.kdprofile = $idProfile  
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND ru.id =  '".$idRuangan."'    
                    
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
                
            
            
            ),
            
            
            data_pasien_sudah_pulang as (
                
                SELECT
                        EXTRACT(DAY FROM apd.tglmasuk) AS tanggalmasuk,
                        COUNT(CASE WHEN pd.tglpulang IS NULL THEN 1 ELSE NULL END) AS totalpasienyangmasihdirawat
                        
                FROM pasiendaftar_t AS pd
                JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                LEFT JOIN registrasipelayananpasien_t AS rpp  ON rpp.noregistrasifk = pd.norec AND apd.tglkeluar = rpp.tglpindah
                INNER JOIN pasien_m AS pm ON pm.id = pd.nocmfk
                INNER JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN kelas_m AS kls ON kls.id = apd.objectkelasfk
                LEFT JOIN ruangan_m AS ru2 ON ru2.id = rpp.objectruanganfk
                LEFT JOIN ruangan_m AS ru3 ON ru3.id = rpp.objectruanganasalfk
                                                                
                WHERE   
                        pd.kdprofile = $idProfile  
                    AND apd.tglmasuk BETWEEN '".$tglAwal."' AND '".$tglAkhir."'
                    AND ru.id =  '".$idRuangan."' 
                    AND rpp.tglmasuk IS NULL  
                    
                GROUP BY EXTRACT(DAY FROM apd.tglmasuk) 
            
            )
                                            
                            
            SELECT
                ds.tanggal,
                dpk.banyaknyapasiendiharisebelumnya  ,

                (CASE WHEN dpm.banyakpasienmasuk >= 1 THEN dpm.banyakpasienmasuk ELSE '0' END) banyakpasienmasuk,
                (CASE WHEN dpp.banyakpasienpindahan >= 1 THEN dpp.banyakpasienpindahan ELSE '0' END) banyaknyapasienpindahan,

               
                (CASE WHEN dpp.banyakpasienpindahan >= 1 THEN dpp.banyakpasienpindahan ELSE '0' END) banyaknyapasienpindahan,
                 
                ( 
                        dpk.banyaknyapasiendiharisebelumnya 
                    + (CASE WHEN dpm.banyakpasienmasuk >= 1 THEN dpm.banyakpasienmasuk ELSE '0' END)  
                    + (CASE WHEN dpp.banyakpasienpindahan >= 1 THEN dpp.banyakpasienpindahan ELSE '0' END)  
                ) total_234,
 

                (CASE WHEN dpd.banyakpasiendipindahkan >= 1 THEN dpd.banyakpasiendipindahkan ELSE '0' END) banyakpasiendipindahkan,
                (CASE WHEN dppl.banyakpasienpulang >= 1 THEN dppl.banyakpasienpulang ELSE '0' END) banyakpasienpulang,
                (CASE WHEN dpdr.banyakpasiendirujuk >= 1 THEN dpdr.banyakpasiendirujuk ELSE '0' END) banyakpasiendirujuk,
                (CASE WHEN dprs.banyakpasienpindahrslain >= 1 THEN dprs.banyakpasienpindahrslain ELSE '0' END) banyakpasienpindahrslain,
                (CASE WHEN dpap.banyakpasienaps >= 1 THEN dpap.banyakpasienaps ELSE '0' END) banyakpasienaps,
            
                ds.banyakpasienkeluarhidup,
                (ds.pasienmeninggalkurangdari48jam + ds.pasienmeninggallebihdari48jam) banyaknyapasienmeninggal,
                ds.pasienmeninggalkurangdari48jam,
                ds.pasienmeninggallebihdari48jam,

                ( 
                     
                     + (CASE WHEN ds.pasienmeninggalkurangdari48jam  >= 1 THEN ds.pasienmeninggalkurangdari48jam  ELSE '0' END) 
                     +(CASE WHEN ds.pasienmeninggallebihdari48jam    >= 1 THEN ds.pasienmeninggallebihdari48jam  ELSE '0' END) 
                       
                )jumlah1213,

                (
                    (CASE WHEN dpd.banyakpasiendipindahkan >= 1 THEN dpd.banyakpasiendipindahkan ELSE '0' END) 
                     + (CASE WHEN dppl.banyakpasienpulang >= 1 THEN dppl.banyakpasienpulang ELSE '0' END) 
                     + (CASE WHEN dpdr.banyakpasiendirujuk >= 1 THEN dpdr.banyakpasiendirujuk ELSE '0' END) 
                     + (CASE WHEN dprs.banyakpasienpindahrslain >= 1 THEN dprs.banyakpasienpindahrslain ELSE '0' END) 
                     + (CASE WHEN dpap.banyakpasienaps >= 1 THEN dpap.banyakpasienaps ELSE '0' END) 
                     + (CASE WHEN ds.pasienmeninggalkurangdari48jam  >= 1 THEN ds.pasienmeninggalkurangdari48jam  ELSE '0' END) 
                     +(CASE WHEN ds.pasienmeninggallebihdari48jam    >= 1 THEN ds.pasienmeninggallebihdari48jam  ELSE '0' END) 
                ) jumlah67891011,

                dlp.total_lama_hari_perawatan totallamadirawat,
                (CASE WHEN  dkl.suite is not null and  dkl.suite <> 0 then dkl.suite ELSE 0 END) suite,
                (CASE WHEN  dkl.vvip is not null and  dkl.vvip <> 0 then dkl.vvip ELSE 0 END) vvip,
                (CASE WHEN  dkl.vip is not null and  dkl.vip <> 0 THEN  dkl.vip ELSE 0 END) vip,
                (CASE WHEN  dkl.nonkelas is not null and  dkl.nonkelas <> 0 THEN  dkl.nonkelas ELSE 0 END) nonkelas,
                (CASE WHEN  dkl.kelas1 is not null and  dkl.kelas1 <> 0 THEN  dkl.kelas1 ELSE 0 END) kelas1,
                (CASE WHEN  dkl.kelas2 is not null and  dkl.kelas2 <> 0 THEN  dkl.kelas2 ELSE 0 END) kelas2,
                (CASE WHEN  dkl.kelas3 is not null and  dkl.kelas3 <> 0 THEN  dkl.kelas3 ELSE 0 END) kelas3, 
                (CASE WHEN  dpsp.totalpasienyangmasihdirawat  <> 0 THEN  dpsp.totalpasienyangmasihdirawat ELSE 0 END)	totalpasienyangmasihdirawat
                                                            
            FROM daily_stats ds
            LEFT JOIN cumulative_patients cp ON ds.tanggal = cp.tanggal
            LEFT JOIN data_pasien_masuk dpm on ds.tanggal = dpm.tanggalmasuk 
            LEFT JOIN data_pasien_dipindahkan dpd on ds.tanggal = dpd.tanggalmasuk 
            LEFT JOIN data_pasien_pulang dppl on ds.tanggal = dppl.tanggalmasuk 
            LEFT JOIN data_pasien_dirujuk dpdr on ds.tanggal = dpdr.tanggalmasuk 
            LEFT JOIN data_pasien_pindahrslain dprs on ds.tanggal = dprs.tanggalmasuk 
            LEFT JOIN data_pasien_aps dpap on ds.tanggal = dpap.tanggalmasuk 
            left join data_pasien_kemarin dpk on ds.tanggal = dpk.tanggalmasuk
                                        
            LEFT JOIN data_pasien_pindahan dpp on ds.tanggal = dpp.tanggalmasuk 
            LEFT JOIN data_lama_perawatan dlp on ds.tanggal = dlp.tanggalmasuk
            LEFT JOIN data_kelas_pasien dkl on ds.tanggal = dkl.tanggalmasuk
            LEFT JOIN data_pasien_sudah_pulang dpsp on ds.tanggal = dpsp.tanggalmasuk
                                            
            ORDER BY ds.tanggal;
                
        ";


        $data = DB::select(
            DB::raw($query)
        );

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
            'query'=>$query,
        );
        return $this->respond($result);
    } 
}
