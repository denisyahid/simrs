<?php
/*
| Ever tried.
| Ever failed.
| No matter.
| Try Again.
| Fail again.
| Fail better".
| , Samuel Beckett
|
*/

use Illuminate\Http\Request;
use App\Http\Controllers\EMR\EMRCtrl;
use App\Http\Controllers\IGD\IGDCtrl;
use App\Http\Controllers\Ppi\PPICtrl;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthCtrl;
use App\Http\Controllers\Asset\AssetCtrl;
use App\Http\Controllers\Higea\HigeaCtrl;
use App\Http\Controllers\Humas\HumasCtrl;
use App\Http\Controllers\EMR\TindakanCtrl;
use App\Http\Controllers\laporan\MKKOCtrl;
use App\Http\Controllers\Bridging\BSRECtrl;
use App\Http\Controllers\Bridging\HRISCtrl;
use App\Http\Controllers\EMR\ReportEMRCtrl;
use App\Http\Controllers\Kasir\BillingCtrl;
use App\Http\Controllers\Report\ReportCtrl;
use App\Http\Controllers\Indikator\PMKPCtrl;
use App\Http\Controllers\Ambulan\AmbulanCtrl;
use App\Http\Controllers\Antrian\AntrianCtrl;
use App\Http\Controllers\Bridging\GithubCtrl;
use App\Http\Controllers\Bridging\InaCbgCtrl;
use App\Http\Controllers\Bridging\NoAuthCtrl;
use App\Http\Controllers\Bridging\TilakaCtrl;
use App\Http\Controllers\Cathlab\CathlabCtrl;
use App\Http\Controllers\Darah\BankDarahCtrl;
use App\Http\Controllers\General\GeneralCtrl;
use App\Http\Controllers\Jenazah\JenazahCtrl;
use App\Http\Controllers\Piutang\PiutangCtrl;
use App\Http\Controllers\Akuntansi\JurnalCtrl;
use App\Http\Controllers\Bridging\SiranapCtrl;
use App\Http\Controllers\Darah\OrderDarahCtrl;
use App\Http\Controllers\General\SysAdminCtrl;
use App\Http\Controllers\Akuntansi\ArusKasCtrl;
use App\Http\Controllers\Anggaran\AnggaranCtrl;
use App\Http\Controllers\EMR\InputDiagnosaCtrl;
use App\Http\Controllers\EMR\ProfilePasienCtrl;
use App\Http\Controllers\Kiosk\KiosKController;
use App\Http\Controllers\Registrasi\PasienCtrl;
use App\Http\Controllers\Bridging\SATUSEHATCtrl;
use App\Http\Controllers\Farmasi\InputResepCtrl;
use App\Http\Controllers\Farmasi\OrderResepCtrl;
use App\Http\Controllers\Iprs\DashboardIprcCtrl;
use App\Http\Controllers\Logistik\KartuStokCtrl;
use App\Http\Controllers\Sysadmin\MasterEMRCtrl;
use App\Http\Controllers\Sysadmin\MasterPPICtrl;
use App\Http\Controllers\Akuntansi\BukuBesarCtrl;
use App\Http\Controllers\Akuntansi\MasterCOACtrl;
use App\Http\Controllers\Kasir\PiutangPasienCtrl;
use App\Http\Controllers\Kasir\TagihanPasienCtrl;
use App\Http\Controllers\Logistik\PersediaanCtrl;
use App\Http\Controllers\Logistik\StokBarangCtrl;
use App\Http\Controllers\Radiologi\RadiologiCtrl;
use App\Http\Controllers\Sysadmin\MasterBankCtrl;
use App\Http\Controllers\Sysadmin\MasterListCtrl;
use App\Http\Controllers\Sysadmin\MasterSukuCtrl;
use App\Http\Controllers\Ambulan\OrderAmbulanCtrl;
use App\Http\Controllers\Cathlab\OrderCathlabCtrl;
use App\Http\Controllers\Farmasi\ProduksiObatCtrl;
use App\Http\Controllers\Jenazah\OrderJenazahCtrl;
use App\Http\Controllers\Logistik\OrderBarangCtrl;
use App\Http\Controllers\Logistik\StokRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterAgamaCtrl;
use App\Http\Controllers\Sysadmin\MasterKamarCtrl;
use App\Http\Controllers\Sysadmin\MasterKelasCtrl;
use App\Http\Controllers\Sysadmin\MasterPaketCtrl;
use App\Http\Controllers\Sysadmin\MasterRangeCtrl;
use App\Http\Controllers\Sysadmin\MasterSignaCtrl;
use App\Http\Controllers\Akuntansi\NeracaSaldoCtrl;
use App\Http\Controllers\Bridging\ApotikOnlineCtrl;
use App\Http\Controllers\Bridging\BridgingBPJSCtrl;
use App\Http\Controllers\Bridging\TelemedicineCtrl;
use App\Http\Controllers\Dashboard\DashboardRICtrl;
use App\Http\Controllers\Dashboard\DashboardRJCtrl;
use App\Http\Controllers\Registrasi\PasienBaruCtrl;
use App\Http\Controllers\Registrasi\PasienLamaCtrl;
use App\Http\Controllers\RekamMedis\RekamMedisCtrl;
use App\Http\Controllers\Remunerasi\RemunerasiCtrl;
use App\Http\Controllers\Sysadmin\MapAkomodasiCtrl;
use App\Http\Controllers\Sysadmin\MasterAlergiCtrl;
use App\Http\Controllers\Sysadmin\MasterNegaraCtrl;
use App\Http\Controllers\Sysadmin\MasterProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterRhesusCtrl;
use App\Http\Controllers\Bridging\AntrianOnlineCtrl;
use App\Http\Controllers\Dashboard\DashboardIGDCtrl;
use App\Http\Controllers\laporan\LaporanFarmasiCtrl;
use App\Http\Controllers\Logistik\PurchaseOrderCtrl;
use App\Http\Controllers\RawatInap\PulangPindahCtrl;
use App\Http\Controllers\Sysadmin\MapProdukPacsCtrl;
use App\Http\Controllers\Sysadmin\MasterGenerikCtrl;
use App\Http\Controllers\Sysadmin\MasterJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterProfileCtrl;
use App\Http\Controllers\Sysadmin\MasterRekananCtrl;
use App\Http\Controllers\Sysadmin\MasterRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterSediaanCtrl;
use App\Http\Controllers\BedahSentral\OrderBedahCtrl;
use App\Http\Controllers\Dashboard\DashboardGiziCtrl;
use App\Http\Controllers\Kasir\PembayaranTagihanCtrl;
use App\Http\Controllers\Kasir\TagihanNonLayananCtrl;
use App\Http\Controllers\Kasir\VerifikasiTagihanCtrl;
use App\Http\Controllers\Logistik\TransferBarangCtrl;
use App\Http\Controllers\Registrasi\MutasiPasienCtrl;
use App\Http\Controllers\Sterilisasi\SterilisasiCtrl;
use App\Http\Controllers\Sysadmin\MasterDiagnosaCtrl;
use App\Http\Controllers\Sysadmin\MasterProvinsiCtrl;
use App\Http\Controllers\Dashboard\DashboardBedahCtrl;
use App\Http\Controllers\Dashboard\DashboardKasirCtrl;
use App\Http\Controllers\Kasir\DaftarPasienPulangCtrl;
use App\Http\Controllers\Logistik\PemesananBarangCtrl;
use App\Http\Controllers\Logistik\PurchaseRequestCtrl;
use App\Http\Controllers\Radiologi\OrderRadiologiCtrl;
use App\Http\Controllers\Remunerasi\PotonganRemunCtrl;
use App\Http\Controllers\Sysadmin\MapAdministrasiCtrl;
use App\Http\Controllers\Sysadmin\MasterIndikatorCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisDietCtrl;
use App\Http\Controllers\Sysadmin\MasterKecamatanCtrl;
use App\Http\Controllers\Sysadmin\MasterPaketObatCtrl;
use App\Http\Controllers\Sysadmin\MasterPekerjaanCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusBedCtrl;
use App\Http\Controllers\Dashboard\DashboardApotikCtrl;
use App\Http\Controllers\Laboratorium\LaboratoriumCtrl;
use App\Http\Controllers\Laporan\LaporanPengunjungCtrl;
use App\Http\Controllers\Laporan\LaporanRekamMedisCtrl;
use App\Http\Controllers\Logistik\DistribusiBarangCtrl;
use App\Http\Controllers\Logistik\MonitoringBarangCtrl;
use App\Http\Controllers\Logistik\PenerimaanBarangCtrl;
use App\Http\Controllers\Reservasi\ReservasiMobileCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterDepartemenCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisKasusCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisRangeCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisTarifCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisWaktuCtrl;
use App\Http\Controllers\Sysadmin\MasterMerkProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterPendidikanCtrl;
use App\Http\Controllers\Sysadmin\MasterShiftKerjaCtrl;
use App\Http\Controllers\Sysadmin\SettingDataFixedCtrl;
use App\Http\Controllers\Akuntansi\JurnalNonLayananCtrl;
use App\Http\Controllers\Akuntansi\LaporanAkuntansiCtrl;
use App\Http\Controllers\Bridging\BridgingPenunjangCtrl;
use App\Http\Controllers\Dashboard\DashboardCathlabCtrl;
use App\Http\Controllers\Dashboard\DashboardPegawaiCtrl;
use App\Http\Controllers\Farmasi\PelayananObatBebasCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalRujukanCtrl;
use App\Http\Controllers\Sysadmin\MasterBahanProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisAlamatCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisUsulanCtrl;
use App\Http\Controllers\Sysadmin\MasterMapKelompokCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanBesarCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanKecilCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanResepCtrl;
use App\Http\Controllers\Sysadmin\MasterTandaTanganCtrl;
use App\Http\Controllers\Sysadmin\MasterTempatTidurCtrl;
use App\Http\Controllers\Sysadmin\MasterTipePegawaiCtrl;
use App\Http\Controllers\Bridging\BridgingSirsOnlineCtrl;
use App\Http\Controllers\Dashboard\DashboardAnggaranCtrl;
use App\Http\Controllers\Dashboard\DashboardLogistikCtrl;
use App\Http\Controllers\Dashboard\DashboardTindakanCtrl;
use App\Http\Controllers\Farmasi\DaftarPasienFarmasiCtrl;
use App\Http\Controllers\Kasir\DaftarPenerimaanKasirCtrl;
use App\Http\Controllers\Logistik\SuratPerintahKerjaCtrl;
use App\Http\Controllers\Registrasi\DaftarRegistrasiCtrl;
use App\Http\Controllers\Registrasi\RegistrasiPasienCtrl;
use App\Http\Controllers\Remunerasi\RemunerasiDokterCtrl;
use App\Http\Controllers\Sysadmin\MapKelompokLaporanCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalAnggaranCtrl;
use App\Http\Controllers\Sysadmin\MasterBentukProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterJadwalDokterCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisGenerikCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisKelaminCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisLaporanCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisRacikanCtrl;
use App\Http\Controllers\Sysadmin\MasterKategoryDietCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokUserCtrl;
use App\Http\Controllers\Sysadmin\MasterRouteFarmasiCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusApotikCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusKeluarCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusPulangCtrl;
use App\Http\Controllers\Akuntansi\JurnalSetoranKasirCtrl;
use App\Http\Controllers\Akuntansi\JurnalVerifTagihanCtrl;
use App\Http\Controllers\Dashboard\DashboardObatAlkesCtrl;
use App\Http\Controllers\Dashboard\DashboardRadiologiCtrl;
use App\Http\Controllers\Kasir\DaftarPasienAktifKasirCtrl;
use App\Http\Controllers\Kasir\DaftarPengeluaranKasirCtrl;
use App\Http\Controllers\Registrasi\PemakaianAsuransiCtrl;
use App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterGolonganDarahCtrl;
use App\Http\Controllers\Sysadmin\MasterJadwalPraktekCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokShiftCtrl;
use App\Http\Controllers\Sysadmin\MasterKomponenHargaCtrl;
use App\Http\Controllers\Sysadmin\MasterKondisiPasienCtrl;
use App\Http\Controllers\Sysadmin\MasterKotaKabupatenCtrl;
use App\Http\Controllers\Sysadmin\MasterModulAplikasiCtrl;
use App\Http\Controllers\Sysadmin\MasterSatuanStandarCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusPegawaiCtrl;
use App\Http\Controllers\Bendahara\BendaharaPenerimaanCtrl;
use App\Http\Controllers\Dashboard\DashboardMasterDataCtrl;
use App\Http\Controllers\Dashboard\DashboardRegistrasiCtrl;
use App\Http\Controllers\Kasir\DaftarTagihanNonLayananCtrl;
use App\Http\Controllers\laporan\LaporanTindakanPasienCtrl;
use App\Http\Controllers\Pelayanan\DokterCareIntegrasiCtrl;
use App\Http\Controllers\Sysadmin\MapKelompokPenghasilCtrl;
use App\Http\Controllers\Sysadmin\MasterAsalSukuCadangCtrl;
use App\Http\Controllers\Sysadmin\MasterAsuransiPasienCtrl;
use App\Http\Controllers\Sysadmin\MasterDiagnosaKankerCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisIndikatorCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisPerawatanCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokPasienCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterKonversiSatuanCtrl;
use App\Http\Controllers\Sysadmin\MasterProdusenProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterSlottingOnlineCtrl;
use App\Http\Controllers\Bendahara\BendaharaPengeluaranCtrl;
use App\Http\Controllers\Laboratorium\OrderLaboratoriumCtrl;
use App\Http\Controllers\Sysadmin\MapJenisPaguToPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokJabatanCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokLaporanCtrl;
use App\Http\Controllers\Sysadmin\MasterTambahLoginUserCtrl;
use App\Http\Controllers\Sysadmin\MasterTargetIndikatorCtrl;
use App\Http\Controllers\Akuntansi\JurnalPelayananPasienCtrl;
use App\Http\Controllers\Dashboard\DashboardLaboratoriumCtrl;
use App\Http\Controllers\Sysadmin\MasterCapaianIndikatorCtrl;
use App\Http\Controllers\Sysadmin\MasterDiagnosaTindakanCtrl;
use App\Http\Controllers\Sysadmin\MasterHubunganKeluargaCtrl;
use App\Http\Controllers\Sysadmin\MasterKategoriDiagnosaCtrl;
use App\Http\Controllers\Sysadmin\MasterKedudukanPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterMapDepoToRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterMapPaketToProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterStatusPerkawinanCtrl;
use App\Http\Controllers\Sysadmin\MasterUnitKerjaPegawaiCtrl;
use App\Http\Controllers\Dashboard\DashboardRencanaMutasiCtrl;
use App\Http\Controllers\Sysadmin\MasterDetailJenisProdukCtrl;
use App\Http\Controllers\Sysadmin\MasterAstorBMCtrl;
use App\Http\Controllers\Sysadmin\MasterKelompokTransaksiCtrl;
use App\Http\Controllers\Sysadmin\MasterMapRuanganToKelasCtrl;
use App\Http\Controllers\Sysadmin\MasterPersenHargaJualProduk;
use App\Http\Controllers\Farmasi\TransaksiPelayananFarmasiCtrl;
use App\Http\Controllers\Laboratorium\PendukungPemeriksaanCtrl;
use App\Http\Controllers\Registrasi\DaftarPasienPerjanjianCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisKomponenHargaCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisKondisiPasienCtrl;
use App\Http\Controllers\Sysadmin\MasterMapRuanganToProdukCtrl;
use App\Http\Controllers\Sysadmin\MapBkutoKelompokTransaksiCtrl;
use App\Http\Controllers\Akuntansi\JurnalPenerimaanPersediaanCtrl;
use App\Http\Controllers\Sysadmin\MasterDetailKategoryPegawaiCtrl;
use App\Http\Controllers\Sysadmin\MasterJenisPetugasPelaksanaCtrl;
use App\Http\Controllers\Sysadmin\MasterMapLoginUserToRuanganCtrl;
use App\Http\Controllers\Sysadmin\MasterHargaNettoProdukByKelasCtrl;
use App\Http\Controllers\Sysadmin\MasterMapLoginUserToModulAplikasiCtrl;
use App\Http\Controllers\Sysadmin\MasterMapJenisPetugasToJenisPegawaiCtrl;
use App\Http\Controllers\Laporan\LaporanKeteranganLahirCtrl;
use App\Http\Controllers\Kemoterapi\PenjadwalanKemoterapiCtrl;
use App\Http\Controllers\Sysadmin\MasterBerkasPasienCtrl;
use App\Http\Controllers\JasaPelayanan\JasaPelayananCtrl;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('service/test-encode-bundle', function() {
    $dir = storage_path('app/public/dokumen_klaim');
    if (!is_dir($dir)) {
        exit('Invalid directory path');
    }

    $files = array_diff(scandir($dir), ['.', '..']);
    DB::beginTransaction();
    try {
        foreach (array_chunk($files, 5) as $batch) {
            foreach ($batch as $file) {
                
                if (stripos($file, '.pdf') !== false) {
                    $fullpath = "$dir/$file";
                    $name     = $file;
                    $noreg    = count(explode('_', $sub)[1]) > 1 ? explode('_', $sub)[1] : '-';
                } elseif (is_dir("$dir/$file")) {
                    foreach (array_diff(scandir("$dir/$file"), ['.', '..']) as $sub) {
                        if (stripos($sub, '.pdf') === false) {
                            continue;
                        }
                        $fullpath = "$dir/$file/$sub";
                        $name     = $sub;
                        $noreg    = count(explode('_', $sub)[1]) > 1 ? explode('_', $sub)[1] : '-';
                    }
                } else {
                    continue;
                }
    
                $encrypt = base64_encode(file_get_contents($fullpath));
                DB::table('bundleklaim_t')->insert([
                    'norec'         => Ramsey\Uuid\Uuid::uuid4()->toString(),
                    'noregistrasi'  => $noreg,
                    'filename'      => $name,
                    'data'          => $encrypt,
                ]);
            }
        }
        
        DB::commit();
        return "sukses";
    }catch(\Exception $e) {
        DB::rollback();
        return $e->getMessage() . ' ' . $e->getLine();
    }

});
// test GET CPPT JGN DI HAPUS DL
Route::get('service/test-get-cppt', function () {
    ini_set('max_execution_time', 10000);
    $a = \DB::table('pasiendaftar_t as pd')
        ->select('pd.norec as norecpd')
        ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
        ->whereDate('pd.tglregistrasi', '>=', '2025-01-02')
        ->whereDate('pd.tglregistrasi', '<=', '2025-01-03')
        ->where('apd.objectruanganfk', 268)
        // ->limit(5)
        ->get()
        ->pluck('norecpd');

    $kan = [];
    $sl['existing'] = 0;
    $sl['backup'] = 0;
    $sl['result'] = 0;
    $t = \DB::connection('mongodb')
        ->table('CatatanPerkembanganPasienTerintegrasi')
        ->whereIn('registrasi.norec_pd', $a)
        // ->where('statusenabled', true)
        ->get();

    $ts = \DB::connection('mongodb')
        ->table('CPPTDetail_Backup')
        ->whereIn('norec_pd', $a)
        ->where('flag', 'dokter')
        ->get();
    // ->count();

    $tsk = \DB::connection('mongodb')
        ->table('CPPTDetail')
        ->whereIn('norec_pd', $a)
        ->where('flag', 'dokter')
        ->get();

    $sl['backup'] = $sl['backup'] + count($ts);
    $sl['existing'] = $sl['existing'] + count($tsk);
    if (count($t) > 0) {
        $t = $t->toArray();
        $ts = $ts->toArray();
        foreach ($t as $tt => $cppt) {
            $cppt['details'] = [];
            foreach ($ts as $tss => $detail) {
                if ($detail['emrpasienfk'] == $cppt['emrpasienfk']) {
                    $cppt['details'][] = $detail;
                }
            }
            $kan[] = $cppt;
        }
    }
    // foreach($a as $k => $v) {

    //     // ->count();


    // }
    $sl['result'] = $sl['backup'] - $sl['existing'];
    return $sl;
});
Route::controller(DashboardRegistrasiCtrl::class)->group(function () {
    Route::get('service/cetak-kartu-pasien', 'cetakKartuPasienWeb');
    Route::get('service/cetak-bukti-reservasi', 'cetakBuktiReservasiWeb');
});

Route::controller(HumasCtrl::class)->group(function () {
    Route::get('service/humas/info-bed-semua', 'infoBed');
});

Route::controller(ReportCtrl::class)->group(function () {
    Route::get('service/cetak-hasil-mikro', 'cetakHasilMikro');
    Route::get('service/view-bentar-lap', 'liatViewLap');
    Route::get('service/bukti-layanan-bpjs-klaim', 'cetakBillbpjsKlaim');
});

Route::controller(ReportEMRCtrl::class)->group(function () {
    Route::get('service/cetak-rencana-kontrol-klaim-poli', 'cetakRencanaKontrolPoli');
    Route::get('service/generate-all-ringkasan', 'generateAllRingkasan');
});

Route::get('service/signature-petugas', function (Request $r) {
    try {
        if (empty($r['key']) || (base64_decode($r['key'], true) === false)) {
            throw new Exception;
        }

        $bs64 = base64_decode($r['key']);
        $exp = explode(';', $bs64);

        // $reJson = [
        //     "nocm" => $exp[0] ?? null,
        //     "inisial" => $exp[1] ?? null,
        //     "namaruangan" => $exp[2] ?? null,
        //     "dpjp" => $exp[3] ?? null,
        //     "tglberkunjung" => $exp[4] ?? null,
        //     "tglpulang" => $exp[5] ?? null,
        //     "created_at" => $exp[6] ?? null,
        //     "type" => $exp[7] ?? null,
        // ];

        // dd($exp);

        $reJson = [
            "collection" => $exp[0] ?? null,
            "id" => $exp[1] ?? null
        ];

        // Ijin tambah nama handle nama dokter EMR
        if (isset($exp[2])) {
            $reJson['petugas'] = $exp[2];
        }

        $ifValid = array_filter($reJson, function ($q) {
            return $q == null;
        });
        if (count($ifValid) > 0) throw new \Exception;

        $kdProfile = app('App\Http\Controllers\Controller')->getProfile();
        $data = DB::connection('mongodb')
            ->table($reJson['collection'])
            ->where('_id', $reJson['id'])
            ->first();

        if (empty($data)) throw new \Exception;
        // GET PETUGAS / DPJP
        $petugas = null;
        if (isset($reJson['petugas'])) {
            $petugas = $reJson['petugas'];
        } else {
            $petugas = $data['registrasi']['dokter'];
        }

        $exp = explode(' ', $data['pasien']['namapasien']);
        $alfa = '';

        for ($i = 0; $i < count($exp); $i++) {
            $space = (count($exp) == $i ? '' : ' ');
            $alfa .= $exp[$i][0] . $space;
        }
        $key = [
            "nocm" => $data['pasien']['nocm'],
            "inisial" => $alfa,
            "namaruangan" => $data['registrasi']['namaruangan'],
            "petugas" => $petugas,
            "tglberkunjung" => date("Y-m-d", strtotime($data['registrasi']['tglregistrasi'])),
            "tglpulang" => date("Y-m-d", strtotime($data['registrasi']['tglpulang'])),
            "created_at" => date("Y-m-d", strtotime($data['created_at'])),
            "type" => $reJson['collection'],
        ];


        return view('signature.surat-keterangan-petugas', compact('key'));
    } catch (\Exception $e) {
        abort(404);
    }
})->name('dokumen.signature-petugas');

Route::get('service/signature', function (Request $r) {
    try {
        if (empty($r['key']) || (base64_decode($r['key'], true) === false)) {
            throw new Exception;
        }

        $bs64 = base64_decode($r['key']);
        $exp = explode(';', $bs64);

        // $reJson = [
        //     "nocm" => $exp[0] ?? null,
        //     "inisial" => $exp[1] ?? null,
        //     "namaruangan" => $exp[2] ?? null,
        //     "dpjp" => $exp[3] ?? null,
        //     "tglberkunjung" => $exp[4] ?? null,
        //     "tglpulang" => $exp[5] ?? null,
        //     "created_at" => $exp[6] ?? null,
        //     "type" => $exp[7] ?? null,
        // ];
        $reJson = [
            "collection" => $exp[0] ?? null,
            "id" => $exp[1] ?? null
        ];

        // Ijin tambah nama handle nama dokter EMR
        if (isset($exp[2])) {
            $reJson['dpjp'] = $exp[2];
        }

        $ifValid = array_filter($reJson, function ($q) {
            return $q == null;
        });

        if (count($ifValid) > 0) throw new \Exception;

        $kdProfile = app('App\Http\Controllers\Controller')->getProfile();
        $data = DB::connection('mongodb')
            ->table($reJson['collection'])
            ->where('_id', $reJson['id'])
            ->first();

        if (empty($data)) throw new \Exception;
        // GET DOKTER / DPJP
        $dokter = null;
        if (isset($data['DDDokter'])) {
            if (is_array($data['DDDokter']) && isset($data['DDDokter']['label'])) {
                $dokter = $data['DDDokter']['label'];
            } else {
                $dokter = $data['DDDokter'];
            }
        } else if (isset($data['CBDokter'])) {
            if (is_array($data['CBDokter']) && isset($data['CBDokter']['label'])) {
                $dokter = $data['CBDokter']['label'];
            } else {
                $dokter = $data['CBDokter'];
            }
        } else if (isset($reJson['dpjp'])) {
            $dokter = $reJson['dpjp'];
        } else {
            $dokter = $data['registrasi']['dokter'];
        }

        $exp = explode(' ', $data['pasien']['namapasien']);
        $alfa = '';

        for ($i = 0; $i < count($exp); $i++) {
            $space = (count($exp) == $i ? '' : ' ');
            $alfa .= $exp[$i][0] . $space;
        }
        $key = [
            "nocm" => $data['pasien']['nocm'],
            "inisial" => $alfa,
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dpjp" => $dokter,
            "tglberkunjung" => date("Y-m-d", strtotime($data['registrasi']['tglregistrasi'])),
            "tglpulang" => date("Y-m-d", strtotime($data['registrasi']['tglpulang'])),
            "created_at" => date("Y-m-d", strtotime($data['created_at'])),
            "type" => $reJson['collection'],
        ];

        if ($reJson['collection'] == "FormulirBuktiPelayananCanggih") {
            $key = [
                "nocm" => $data['pasien']['nocm'],
                "inisial" => $alfa,
                "namaruangan" => $data['registrasi']['namaruangan'],
                "dpjp" => $dokter,
                "tglberkunjung" => date("Y-m-d", strtotime($data['jamKunjungan'])),
                "tglpulang" => date("Y-m-d", strtotime($data['jamSelesai'])),
                "created_at" => date("Y-m-d", strtotime($data['created_at'])),
                "type" => $reJson['collection'],
            ];
        }
        // else if ($reJson['collection'] == "SuratPermintaanDirawat") {
        //     $admission = null;
        //     if(isset($data['user_input'])){
        //         if (is_array($data['user_input']) && isset($data['user_input']['namalengkap'])) {
        //             $admission = $data['user_input']['namalengkap'];
        //         } else {
        //             $admission = $data['user_input'];
        //         }
        //     }
        //     $key = [
        //         "nocm" => $data['pasien']['nocm'],
        //         "inisial" => $alfa,
        //         "namaruangan" => $data['registrasi']['namaruangan'],
        //         "dpjp" => $dokter,
        //         "tglberkunjung" => date("Y-m-d", strtotime($data['jamKunjungan'])),
        //         "tglpulang" => date("Y-m-d", strtotime($data['jamSelesai'])),
        //         "created_at" => date("Y-m-d", strtotime($data['created_at'])),
        //         "type" => $reJson['collection'],
        //         "admission" => $admission,
        //     ];
        //     // dd($key);
        // }

        return view('signature.surat-keterangan', compact('key'));
    } catch (\Exception $e) {
        abort(404);
    }
})->name('dokumen.signature');

//qrcode pg
Route::get('service/signature-pg', function (Request $r) {
    try {
        if (empty($r['key']) || (base64_decode($r['key'], true) === false)) {
            throw new Exception;
        }

        $bs64 = base64_decode($r['key']);
        $exp = explode(';', $bs64);
        // dd($exp);

        $id = $exp;

        // if (!$id) {
        //     throw new Exception;
        // }

        $data = DB::table('keteranganlahir_t as kl')
            ->select(
                "kl.namaanak",
                "kl.namasuami",
                "kl.noregistrasifk",
                "kl.norm",
                "kl.dokterPenolong",
                "apd.noregistrasi as nomorregistrasi",
                "apd.tglpulang",
                "apd.tglcetak",
                "apdd.objectpegawaifk",
                "pg.namalengkap as dokter"
            )
            ->join("pasiendaftar_t as apd", "apd.noregistrasi", "=", "kl.noregistrasifk") // JOIN tabel pasiendafar
            ->leftJoin("antrianpasiendiperiksa_t as apdd", "apdd.noregistrasi", "=", "kl.noregistrasifk") // LEFT JOIN tabel antrianpasiendiperiksa
            ->leftJoin("pegawai_m as pg", "pg.id", "=", "apdd.objectpegawaifk") // LEFT JOIN tabel pegawai
            ->where('kl.norec', $id)
            ->first();


        // dd($data);

        if (empty($data)) {
            throw new Exception;
        }

        $dokter = null;
        if (isset($data->dokter)) {
            $dokter = $data->dokter;
        } else {
            $dokter = '-';
        }
        // dd($dokter);

        // $exp = explode(' ', $data->namaanak);
        // $alfa = '';
        // for ($i = 0; $i < count($exp); $i++) {
        //     $space = (count($exp) == $i ? '' : ' ');
        //     $alfa .= $exp[$i][0] . $space;
        // }

        // $key = [
        //     "nocm" => $data->norm,
        //     "inisial" => $alfa,
        //     // "namaruangan" => $data->namaruangan,
        //     "namaanak" => $data->namaanak,
        //     "dpjp" => $dokter,
        //     // "tglberkunjung" => date("Y-m-d", strtotime($data->tglregistrasi)),
        //     // "tglpulang" => date("Y-m-d", strtotime($data->tglpulang)),
        //     "created_at" => date("Y-m-d", strtotime($data->created_at)),
        //     "type" => "Surat Keterangan Lahir", // Menambahkan 'type' secara statis
        // ];
        // dd($key);

        // Return view dengan data yang telah diproses
        return view('signature.surat-keterangan-pg', compact('data'));
    } catch (\Exception $e) {
        abort(404);
    }
})->name('dokumen.signature.pg');





Route::get('service/signature-nocl', function (Request $r) {
    try {
        if (empty($r['key']) || (base64_decode($r['key'], true) === false)) {
            throw new Exception;
        }

        $bs64 = base64_decode($r['key']);
        $exp = explode(';', $bs64);

        $reJson = [
            "nocm" => $exp[0] ?? null,
            "inisial" => $exp[1] ?? null,
            "namaruangan" => $exp[2] ?? null,
            "dpjp" => $exp[3] ?? null,
            "tglberkunjung" => $exp[4] ?? null,
            "tglpulang" => $exp[5] ?? null,
            "created_at" => $exp[6] ?? null,
            "type" => $exp[7] ?? null,
        ];
        $ifValid = array_filter($reJson, function ($q) {
            return $q == null;
        });

        if (count($ifValid) > 0) throw new \Exception;

        $key = $reJson;

        return view('signature.surat-keterangan', compact('key'));
    } catch (\Exception $e) {
        abort(404);
    }
})->name('dokumen.signature.nocollection');

Route::get('service/signature-hasil-lab', function (Request $r) {
    // try {
    //     if (empty($r['key']) || (base64_decode($r['key'], true) === false)) {
    //         throw new Exception;
    //     }

    //     $bs64 = base64_decode($r['key']);

    //     // $exp = explode(' ', $exp64[2]);
    //     // $alfa = '';
    //     // for ($i = 0; $i < count($exp); $i++) {
    //     //     $space = (count($exp) == $i ? '' : ' ');
    //     //     $alfa .= $exp[$i][0] . $space;
    //     // }

    //     // $reJson = [
    //     //     "nama" => $exp64[0] ?? null,
    //     //     "nocm" => $exp64[1] ?? null,
    //     //     "inisial" => $alfa,
    //     //     "tglregistrasi" => $exp64[3] ?? null,
    //     //     "ono" => $exp64[4] ?? null,
    //     // ];

    //     // $ifValid = array_filter($reJson, function ($q) {
    //     //     return $q == null;
    //     // });

    //     // if (count($ifValid) > 0) throw new \Exception;

    //     // $key = $reJson;

    //     $data = DB::table('strukorder_t AS so')
    //         ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
    //         ->leftjoin('pasien_m AS pm', 'pm.id', '=', 'so.nocmfk')
    //         ->leftjoin('ruangan_m AS ru', 'ru.id', '=', 'so.objectruangantujuanfk')
    //         ->where('so.noorder', '=', $bs64)
    //         ->where('pd.statusenabled', true)
    //         ->where('so.statusenabled', true)
    //         ->select('so.noorder', 'ps.nocm', 'ru.namaruangan', 'pd.tglregistrasi', 'pd.tglpulang', 'so.created_at')
    //         ->first();

    //         // dd($data);

    //     $exp = explode(' ', $data->namapasien);
    //     $alfa = '';

    //     for ($i = 0; $i < count($exp); $i++) {
    //         $space = (count($exp) == $i ? '' : ' ');
    //         $alfa .= $exp[$i][0] . $space;
    //     }

    //     $key = [
    //         "nocm" => $data->nocm,
    //         "inisial" => $alfa,
    //         "namaruangan" => $data->namaruangan,
    //         "dpjp" => 'ABC',
    //         "tglberkunjung" => date("Y-m-d", strtotime($data->tglregistrasi)),
    //         "tglpulang" => date("Y-m-d", strtotime($data->tglpulang)),
    //         "created_at" => date("Y-m-d", strtotime($data->created_at)),
    //         "type" => 'HASIL LABORATORIUM',
    //     ];


    //     return view('signature.surat-keterangan', compact('key'));
    // } catch (\Exception $e) {
    //     abort(404);
    // }
    try {
        // if (empty($r['key']) || (base64_decode($r['key'], true) === false)) {
        //     throw new Exception;
        // }

        // $bs64 = base64_decode($r['key']);
        // $exp64 = explode(';', $bs64);


        // $exp = explode(' ', $exp64[2]);
        // $alfa = '';
        // for ($i = 0; $i < count($exp); $i++) {
        //     $space = (count($exp) == $i ? '' : ' ');
        //     $alfa .= $exp[$i][0] . $space;
        // }

        // $reJson = [
        //     "ono" => $exp64[0] ?? null,
        // ];

        // $pasien=DB::table('strukorder_t as so')
        // ->join('pasiendaftar_t as pd','pd.norec','=','so.noregistrasifk')
        // ->join('pasien_m as ps','ps.id','=','pd.nocmfk')->select(
        //     'ps.nocm as norm',
        //     'ps.namapasien as nama',
        //     'pd.tglregistrasi',
        //     'so.noorder'
        // )
        // ->where('so.noorder',$r['no'])
        // ->first();

        // $datatest = [
        //     // "nama" => $exp64[0] ?? null,
        //     // "nocm" => $exp64[1] ?? null,
        //     // "inisial" => $alfa,
        //     // "tglregistrasi" => $exp64[3] ?? null,
        //     // "ono" => $exp64[4] ?? null,

        //     "nama" => $pasien->nama ?? null,
        //     "nocm" => $pasien->norm ?? null,
        //     "inisial" => 'ABD',
        //     // "inisial" => null,
        //     "tglregistrasi" => $pasien->tglregistrasi ?? null,
        //     "ono" => $pasien->noorder ?? null,
        // ];

        // $ifValid = array_filter($datatest, function ($q) {
        //     return $q == null;
        // });

        // if (count($ifValid) > 0) throw new \Exception;

        // $key = $reJson;
        // $datapasien=$datatest;


        // return view('signature.surat-keterangan', compact('key','datapasien'));

        if (empty($r['key']) || !base64_decode($r['key'], true)) {
            return abort(404, 'Invalid key parameter');
        }
        if (empty($r['no'])) {
            return abort(404, 'Nomor order tidak ditemukan');
        }

        $bs64 = base64_decode($r['key'], true);
        if ($bs64 === false || empty($bs64)) {
            return abort(404, 'Invalid Base64 string');
        }

        $exp64 = explode(';', $bs64);
        $reJson = [
            "ono" => $exp64[0] ?? null,
        ];

        $pasien = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select(
                'ps.nocm as norm',
                'ps.namapasien as nama',
                'pd.tglregistrasi',
                'so.noorder'
            )
            ->where('so.noorder', $r['no'])
            ->first();

        if (!$pasien) {
            return abort(404, 'Patient data not found');
        }

        $datatest = [
            "nama" => $pasien->nama ?? null,
            "nocm" => $pasien->norm ?? null,
            // "inisial" => 'ABD',
            "tglregistrasi" => $pasien->tglregistrasi ?? null,
            "ono" => $pasien->noorder ?? null,
        ];

        $ifValid = array_filter($datatest, function ($q) {
            return $q == null;
        });

        if (count($ifValid) > 0) {
            return abort(404, 'Incomplete patient data');
        }

        $key = $reJson;
        $datapasien = $datatest;

        return view('signature.surat-keterangan', compact('key', 'datapasien'));
    } catch (\Exception $e) {
        abort(404);
    }
})->name('dokumen.signature.hasillab');

Route::middleware(['jwt.auth'])->prefix("service")->group(function () {
    Route::middleware(['log'])->group(function () {
        // Route::prefix("histransmedic/serve")->group(function () {


        Route::prefix("general/menu")->group(function () {
            Route::controller(SysAdminCtrl::class)->group(function () {
                Route::get('/list-menu', 'listMenu');
            });
        });
        Route::controller(AssetCtrl::class)->group(function () {
            Route::get('asset/get-daftar-asset', 'getDataBarangRegisterAset');
            Route::get('asset/get-data-combo-asset', 'getDataComboAset');
            Route::get('asset/get-daftar-dropdown-asset', 'getDropdownAsset');
            Route::get('asset/pegawai-paging', 'pegwaiPart');
            Route::get('asset/get-data-jadwal-kalibrasi', 'getDaftarKalibrasi');
            Route::get('asset/get-detail-registrasiasset', 'getDetailBarangRegisterAset');
            Route::get('asset/get-data-penyusutan-asset', 'getDataPenyusutan');
            Route::get('asset/get-daftar-history-pindah-asset', 'getDaftarHistoryAsset');
            Route::get('asset/get-data-jadwal-pemeliharaan', 'getDaftarPemeliharaan');
            Route::get('asset/get-produk-kirim', 'getDataProdukKirim');

            Route::post('asset/save-data-jadwal-kalibrasi', 'SaveDataJadwalAssetKalibrasi');
            Route::post('asset/save-data-jadwal-pemeliharaan', 'SaveDataJadwalAssetPemeliharaan');
            Route::post('asset/delete-data-jadwal-pemeliharaan', 'DeleteDataJadwalAssetPemeliharaan');
            Route::post('asset/save-worklist', 'SaveDataWorkList');
            Route::post('asset/save-inspeksi', 'SaveDataInspeksi');
            Route::post('asset/save-duedate', 'SaveDataStartDate');
            Route::post('asset/simpan-kirimbarang-aset', 'saveKirimBarangAsset');
            Route::post('asset/simpan-detail-regisaset', 'SimpanDetailRegisterAset');
        });

        Route::prefix("akuntansi")->group(function () {
            Route::controller(JurnalPelayananPasienCtrl::class)->group(function () {
                Route::get('/get-daftar-registrasi-pasien', 'getDetailPelayananPasien');
                Route::get('/get-detail-pelayanan-pasien-by-noregistrasi', 'getDetailPelayananPasienByNoregistrasi');
                Route::get('/get-detail-map-coa-by-produkid', 'getDetailMapCoaByproduk');
                Route::get('/get-data-combo-map-coa', 'getDataComboMapCoa');
                Route::get('/get-data-jurnal-umum-2018', 'getDataJurnalUmumRev2019');

                Route::post('/save-map-jurnal', 'saveUpdateMapCoa');
                Route::post('/post-detail-jurnal', 'PostingJurnal_PerDetailTransaksi');
                Route::post('/hapus-map-jurnal', 'saveHapusMapCoa');
            });
            Route::controller(JurnalCtrl::class)->group(function () {
                Route::get('/get-data-detail-jurnal', 'getDetailJurnalRev2018');
                Route::get('/get-data-detail-jurnal-posting', 'getDetailJurnalPosting');
                Route::get('/get-data-combo-coa-part', 'getCoaSaeutik');
                Route::get('/template-excel', 'downloadTemplate');

                Route::post('/save-posting-jurnalv1', 'PostingJurnalRev2018');
                Route::post('/save-unposting-jurnalv1', 'UnPostingJurnalRev2018');
                Route::post('/save-bengkel-jurnal', 'BengkelJurnal');
                Route::post('/save-hapus-double-jurnal', 'HapusDoubleJurnal');
                Route::post('/save-entry-jurnal', 'PostingJurnal_entry');
                Route::post('/save-hapus-data-jurnal', 'PostingHapusJurnal_entry');
                Route::post('/import-jurnal-manual-excel', 'getInputJurnalManualFromFileExcel');
            });
            Route::controller(BukuBesarCtrl::class)->group(function () {
                Route::get('/get-data-buku-besar-rev2', 'getDataBukuBesarRev2');
                Route::get('/get-data-detail-buku-besar', 'getDetailJurnalRev2018BukuBesar');
                Route::get('/get-data-buku-besar-pembantu', 'getDataBukuBesarPembantu');
                Route::get('/get-datacombo-rekanan', 'getRekananPaging');
            });
            Route::controller(NeracaSaldoCtrl::class)->group(function () {
                Route::get('/get-data-trial-balance', 'getDataTrialBalance');
                Route::get('/get-data-trial-balance-rev', 'getDataTrialBalancerevNeracalajur');

                Route::post('/save-data-closing-jurnal', 'SaveClosingJurnal');
                Route::post('/save-batal-closing-jurnal', 'SaveBatalClosingJurnal');
            });
            Route::controller(JurnalVerifTagihanCtrl::class)->group(function () {
                Route::get('/get-detailverifikasi', 'getDetailVerifikasi');
                Route::get('/get-detail-map-coa-by-rekanan-kelompokpasien', 'getDetailMapCoaByKelompokPasienRekanan');
                Route::post('/post-detail-jurnal-verifikasi', 'PostingJurnal_PerDetailTransaksi_verifikasi');
            });
            Route::controller(JurnalPenerimaanPersediaanCtrl::class)->group(function () {
                Route::get('/get-detail-penerimaanbarang', 'getDetailPenerimaanBarang');
                Route::get('/get-detail-map-coa-by-produkid-persediaan', 'getDetailMapCoaByprodukPersediaan');
                Route::post('/post-detail-jurnal-penerimaan-suplier', 'PostingJurnal_PerDetailTransaksi_PenerimaanSuplier');
            });

            Route::controller(JurnalNonLayananCtrl::class)->group(function () {
                Route::get('/get-detail-nonlayanan', 'getDetailNonLayanan');
                // Route::post('/post-detail-jurnal-verifikasi', 'PostingJurnal_PerDetailTransaksi_verifikasi');
            });
            Route::controller(JurnalSetoranKasirCtrl::class)->group(function () {
                Route::get('/get-detail-setoran-kasir', 'getDetailSetoranKasir');
                Route::get('/get-detail-terima-kasir', 'getDetailTerimaKasir');
                Route::get('/get-detail-map-coa-by-carabayarid', 'getDetailMapCoaByCaraBayar');

                Route::post('/post-detail-jurnal-kwitansi', 'PostingJurnal_PerDetailTransaksi_Kwitansi');
            });
            Route::controller(ArusKasCtrl::class)->group(function () {
                // Route::get('/get-data-aruskas', 'getDataArusKas');
                Route::get('/get-data-aruskas', 'getDataArusKas_COA_SAK');
            });
            Route::controller(LaporanAkuntansiCtrl::class)->group(function () {
                Route::get('/get-data-aruskas-revmar23', 'getDataArusKasRevMar2023');
                Route::get('/get-data-aruskas-revmar23', 'getDataArusKasRev_SAK');
                Route::get('/get-data-jurnal-pendapatan', 'getJurnalPendapatan');
                Route::get('/get-data-jurnal-pendapatan-belum-verif', 'getJurnalPendapatanBelumverif');
                Route::get('/get-jurnal-pelunasan-piutang', 'getJurnalPelunasanPiutang');
                Route::get('/get-jurnal-pelunasan-detail-piutang', 'getdetailJurnalPelunasanPiutang');
                Route::get('/get-data-detail-pendapatan', 'getDetailJurnalPendapatan');
                Route::get('/get-data-detail-pendapatan-belumverif', 'getDetailJurnalPendapatanbelumverif');
            });
            Route::controller(MasterCOACtrl::class)->group(function () {
                Route::get('/get-data-daftar-master-coa', 'getDaftarCoa');
                Route::get('/get-data-combo-master', 'getDataComboMasterAkun');
                Route::get('/get-data-daftar-saldo-awal', 'getDaftarSaldoAwal');
                Route::get('/get-mapping-jurnal', 'mappingjurnal');
                Route::post('/save-data-master-coa', 'SaveDataChartOfAccount');
                Route::post('/save-hapus-data-master-coa', 'SaveHapusChartOfAccount');
                Route::post('/save-data-saldo-awal', 'SaveSaldoAwal');
                Route::post('/save-hapus-saldo-awal', 'SaveHapusSaldoAwal');
            });
        });
        Route::prefix("bridging")->group(function () {
            Route::controller(BridgingBPJSCtrl::class)->group(function () {
                Route::post('/bpjs/tools', 'bpjsTools');
                Route::post('/bpjs/test', 'bpjsToolsTest');
                Route::get('/bpjs/get-rujukan-pcare-nokartu', 'getNoRujukanPcareNoKartu');
                Route::get('/bpjs/get-kamar-rs', 'getKamarRS');
                Route::post('/bpjs/get-list-pemakaian-asuransi', 'getListPemakaianAsuransi');
                Route::post('/bpjs/update-kamar', 'updateAplicaresBedAfter');
            });
            Route::controller(InaCbgCtrl::class)->group(function () {
                Route::get('/inacbgs', 'daftarPasienINACBG');
                Route::get('/inacbgs-cppt-dokter', 'daftarPasienINACBGCPPTDokter');
                Route::get('/inacbgs-cob', 'daftarPasienINACBGCOB');
                Route::get('/inacbgs-backup', 'daftarPasienINACBGBackup');
                Route::get('/inacbgs-download', 'daftarPasienINACBGDownload');
                Route::get('/inacbgs-rm', 'daftarPasienINACBGRM');
                Route::get('/export-inacbgs', 'exportDaftarPasienINACBG');
                Route::get('/inacbgs/dropdown', 'dropDownINACBG');
                Route::get('/inacbgs/get-kunjungan-sebelumnya', 'kunjungansebelumnyaINACBG');
                Route::get('/inacbgs/dokter-paging', 'listDokterPaging');
                Route::get('/inacbgs/get-status', 'getStatusBridgingINACBG');
                Route::get('/inacbgs/get-status-grouping', 'getGroupingINACBG');
                Route::get('/inacbgs/bundle-dokumen', 'bundleDokumen');
                Route::get('/inacbgs/get-for-plafon', 'getFlafonINACBG');
                Route::get('/inacbgs/claim-print', 'claimPRINT');
                Route::get('/inacbgs/lihat-bundle-dokumen', 'lihatbundleDokumen');
                Route::get('/inacbgs/bundle-dokumen-rev', 'bundleDokumenRev');
                Route::get('/inacbgs/bundle-dokumen-rev-download', 'bundleDokumenRevDownload');
                // Route::get('/inacbgs/bundle-dokumen-rev-download-rar', 'bundleDokumenRevDownloadRAR');
                Route::get('/inacbgs/bundle-dokumen-rev-download-rar', 'downloadBundleToZip');
                Route::post('/inacbgs/bundle-dokumen-rev-download-v2', 'bundleDokumenRevDownloadV2');
                Route::get('/inacbgs/zip-dokumen', 'zipDokumen');

                Route::post('/inacbgs/save', 'saveBridgingINACBG');
                Route::post('/inacbgs/save-klaim-print', 'saveBridgingINACBGKlaimPrint');
                Route::post('/inacbgs/save-status', 'saveStatusBridgingINACBG');
                Route::post('/inacbgs/save-grouping', 'saveGroupingINACBG');
                Route::post('/inacbgs/save-dokumen', 'saveDokumenINACBG');
                Route::post('/inacbgs/collect-dokumen', 'collectDokumenINACBG');
                Route::post('/inacbgs/verif-dokumen', 'verifDokumenINACBG');
                Route::post('/inacbgs/save-pemakaian-asuransi', 'savePemakaianAsuransi');

                Route::get('/klaim/get-daftar-klaim', 'getDaftarKlaim');
                Route::get('/klaim/get-daftar-hasil-lab', 'getDaftarKlaimHasilLab');
                Route::get('/klaim/get-daftar-expertise-radiologi', 'getDaftarKlaimHasilRad');
                Route::get('/klaim/get-daftar-emr', 'getDaftarKlaimEMR');


                Route::post('/persalinan', 'savePersalinan');
                Route::post('/apgar', 'saveApgar');
                Route::post('/dializer', 'saveDializer');
            });
            Route::controller(BridgingPenunjangCtrl::class)->group(function () {
                Route::post('/penunjang/save-bridging-zeta', 'saveBridgingPacs');
                Route::post('/penunjang/save-bridging-vans-lab', 'saveBridgingVansLab');
                Route::post('/penunjang/update-expertise', 'saveSendBack');
                Route::post('/penunjang/save-radiografer', 'updateRadiograferRIS');
                Route::post('/penunjang/edit-bridging-vans-lab', 'editBridgingVansLab');
                Route::post('/penunjang/save-edit-lab', 'saveValueEditBridgingVansLab');
                Route::post('/penunjang/delete-bridging-lab', 'deleteLabBriding');
            });
            Route::controller(BridgingSirsOnlineCtrl::class)->group(function () {
                Route::post('/kemenkes/tools', 'kemenkesTools');

                Route::get('/rsonline/get-pasien', 'daftarPasienRS');
            });

            Route::controller(SATUSEHATCtrl::class)->group(function () {
                Route::get('/satusehat/get-list', 'getList');
                Route::get('/satusehat/get-setting', 'getSetting');
                Route::get('/satusehat/get-for-encounter', 'getListRegis');
                Route::get('/satusehat/get-for-observation', 'getListRegisObservation');

                Route::post('/satusehat/tools', 'ihsTools');
                Route::post('/satusehat/generate-token', 'generateToken');
                Route::post('/satusehat/Organization', 'Organization');
                Route::post('/satusehat/Location', 'Location');
                Route::post('/satusehat/Encounter', 'Encounter');
                Route::post('/satusehat/Condition', 'Condition');
                Route::post('/satusehat/Practitioner', 'Practitioner');
                Route::post('/satusehat/update-ihs-pasien', 'updateIHSPasien');
                Route::post('/satusehat/Medication', 'Medication');
                Route::post('/satusehat/MedicationRequest', 'MedicationRequest');
                Route::post('/satusehat/MedicationDispense', 'MedicationDispense');
                Route::post('/satusehat/MedicationDispenseObatBebas', 'MedicationDispenseObatBebas');
                Route::post('/satusehat/Observation', 'Observation');
                Route::post('/satusehat/Procedure', 'Procedure');
                Route::post('/satusehat/Immunization', 'Immunization');
                Route::post('/satusehat/Composition', 'Composition');
                Route::post('/satusehat/ServiceRequest', 'ServiceRequest');
                Route::post('/satusehat/Specimen', 'Specimen');
                Route::post('/satusehat/ObservationLab', 'ObservationLab');
                Route::post('/satusehat/ObservationRad', 'ObservationRad');
                Route::post('/satusehat/DiagnosticReport', 'DiagnosticReport');
                Route::post('/satusehat/ObservationLabDiagnos', 'ObservationLabDiagnos');
                Route::post('/satusehat/AllergyIntolerance', 'AllergyIntolerance');
                Route::post('/satusehat/ClinicalImpression', 'ClinicalImpression');
                Route::post('/satusehat/ObservationKesadaran', 'ObservationKesadaran');
                Route::post('/satusehat/ProcedureEdukasi', 'ProcedureEdukasi');
                Route::post('/satusehat/ConditionSaatMeninggalkanRS', 'ConditionSaatMeninggalkanRS');

                Route::get('/satusehat/send-100-persen', 'send100');
            });

            Route::controller(AntrianOnlineCtrl::class)->group(function () {
                Route::post('/antrol/sendDataAntrean', 'sendDataAntrean');
                Route::post('/antrol/sendTaskId', 'sendTaskId');
                Route::get('/antrol/ambilWaktudiKiosk', 'ambilWaktudiKiosk');
                Route::get('/antrol/getMonitoringWaktu', 'getMonitoringWaktu');
                Route::post('/antrol/saveMonitoringTaksId', 'saveMonitoringTaksId');
                Route::get('/antrol/getComboMonitoring', 'getComboMonitoring');
                Route::get('/antrol/getDataAntrean', 'getDataAntrean');
                Route::post('/antrol/updateDataAntrean', 'updateDataAntrean');
            });
            Route::controller(TilakaCtrl::class)->prefix('tilaka')->group(function () {
                Route::post('/upload', 'upload');
                Route::post('/api-tool', 'apiTools');
            });
            Route::controller(SiranapCtrl::class)->group(function () {
                Route::post('/siranap/tools', 'siranapTools');
                Route::get('/siranap/get-tt', 'getTTeuy');
                Route::get('/siranap/master-kelas', 'masterKelas');
                Route::get('/siranap/master-kamar', 'masterKamar');
                Route::post('/siranap/map-kelas', 'mapKelas');
                Route::post('/siranap/map-kamar', 'mapKamar');
            });
            Route::controller(GithubCtrl::class)->group(function () {
                Route::get('/github/log-commit', 'getLogCommit');
            });
        });
        Route::controller(DashboardPegawaiCtrl::class)->group(function () {
            Route::get('dashboard/data-pegawai', 'dashboardPegawai');
            Route::get('dashboard/data-pegawaiaktif', 'DataPegawaiAktif');
            Route::get('dashboard/get-ruangpegawai', 'getRuangKerja');

            Route::get('dashboard/get-detail-pegawai', 'getJumlah');
        });
        Route::controller(DashboardMasterDataCtrl::class)->group(function () {
            Route::get('dashboard/dashboard-sysadmin', 'dashboardMasterData');
            Route::get('dashboard/dashboard-masterdata', 'DataMaster');

            Route::post('dashboard/save-master-list', 'saveListMaster');
        });

        Route::controller(DashboardRJCtrl::class)->group(function () {
            Route::get('dashboard/rawat-jalan-detail', 'getRawatJalanDetail');
            Route::get('dashboard/rawat-jalan-pasien', 'getRJPasien');
            Route::get('dashboard/rawat-jalan-reservasi', 'getRJPasienReservasi');
            Route::get('dashboard/dropdown-rawat-jalan', 'getDD');
            Route::get('dashboard/dropdown-rawat-jalan-nurse', 'getDDNurse');
            Route::get('dashboard/get-intruksi-cppt-dokter', 'getIntruksiCPPTDokter');
            Route::get('dashboard/get-evaluasi-fisio', 'getEvaluasiFisio');
            Route::post('dashboard/save-evaluasi-fisio', 'saveEvaluasiFisio');
            Route::post('dashboard/verif-intruksi-cppt', 'verifIntruksiCPPT');
            Route::get('dashboard/get-pelayanan-status', 'HitungAntrian');
            Route::get('dashboard/get-combo-jumlah', 'getComboCount');
            Route::get('dashboard/get-jumlah-konsul', 'CountKonsul');
            Route::get('dashboard/get-detail-konsul', 'getDetailKonsul');

            Route::post('dashboard/rawat-jalan/panggil', 'panggilPasien');
            Route::get('dashboard/rawat-jalan-pasien-nurse', 'getRJPasienNurse');
            Route::post('dashboard/checkin-jkn', 'checkinJkn');
            Route::post('dashboard/batal-jkn', 'batalJkn');
            Route::post('dashboard/save-meninggal-rj', 'saveMeninggalRJ');
            Route::post('dashboard/save-pulang-rj', 'savePulangRJ');
        });
        Route::controller(DashboardRegistrasiCtrl::class)->group(function () {
            Route::get('dashboard/registrasi/dropdown', 'getDropdown');
            Route::get('dashboard/registrasi', 'dashboardRegis');
            Route::get('dashboard/registrasi/list-pasien-reservasi', 'daftarReservasi');
            Route::get('dashboard/registrasi/cetak-label-pasien', 'cetakLabelPasien');
            Route::get('dashboard/registrasi/cetak-label-pasien-web', 'cetakKartuPasienWeb');
            Route::get('dashboard/registrasi/cetak-identitas-pasien', 'cetakIdentitasPasien');
            Route::get('dashboard/registrasi/cetak-label-odc', 'cetakLabelODC');
            Route::get('dashboard/registrasi/cetak-kartu-pasien', 'cetakKartuPasien');
            Route::get('dashboard/registrasi/cetak-surat-keterangan-dokter', 'getDataSuratKeteranganDokterAsli');
            Route::get('dashboard/registrasi/cetak-surat-keterangan-keluar', 'getDataSuratKeteranganKeluar');
            Route::get('dashboard/registrasi/cetak-billing-bpjs', 'getDataBillingBpjs');
            Route::get('dashboard/get-norecapd', 'getAPD');
            Route::get('dashboard/get-norecapd-ruangan', 'getAPDRuangan');

            Route::post('dashboard/registrasi/save-surat-regis-ranap', 'SaveSuratRegisRanap');
            Route::post('dashboard/registrasi/hapus-reservasi', 'hapusReservasi');
            Route::post('dashboard/save-batal-registrasi', 'saveBatalRegis');
            Route::post('dashboard/registrasi/gabung-norm', 'saveMergeNoRM');
            Route::post('dashboard/registrasi/update-nomor-rujuk', 'editReservasi');
        });

        Route::controller(MasterBerkasPasienCtrl::class)->group(function () {
            Route::get('dashboard/master-berkas-pasien', 'getAllBerkasPasien');
            Route::post('dashboard/master-berkas-pasien/save', 'saveBerkasPasien');
            Route::post('dashboard/master-berkas-pasien/delete/{id}', 'deleteBerkasPasien');
        });

        Route::controller(DashboardRICtrl::class)->group(function () {
            Route::get('dashboard/dropdown-rawat-inap', 'getDropdown');
            Route::get('dashboard/detail-rawat-inap', 'getDetailRI');
            Route::get('dashboard/rawat-inap-pasien-total', 'getRIPasienTotal');
            Route::get('dashboard/rawat-inap/list', 'getRIPasien');
            Route::get('dashboard/get-data-surat-keterangan', 'getDataSuratKeterangan');
            Route::get('dashboard/get-jumlah-pendapatan', 'getPendapatan');
            Route::get('dashboard/get-mutasi-ranap', 'getRiwayatMutasiRanap');
            Route::get('dashboard/get-intruksi-cppt-dokter-ranap', 'getIntruksiCPPTDokterRanap');

            Route::post('dashboard/batal-ranap', 'BatalRawatInap');
            Route::post('dashboard/save-surat-keterangan-dokter', 'SaveSuratKeteranganDokter');
            Route::post('dashboard/save-surat-keterangan-sakit', 'SaveSuratKeteranganSakit');
        });

        Route::controller(DashboardRencanaMutasiCtrl::class)->group(function () {
            Route::get('dashboard/dropdown-rencana-mutasi', 'getDropdown');
            Route::get('dashboard/detail-rencana-mutasi', 'getDetailRencanaMutasi');
            Route::get('dashboard/rencana-mutasi/list', 'getRencanaMutasi');
            Route::get('dashboard/rencana-mutasi-tolak/list', 'getRencanaMutasiDitolak');

            Route::post('dashboard/save-rencana-mutasi', 'SaveRencanaMutasi');
            Route::post('dashboard/save-rencana-mutasi-pindah', 'SaveRencanaMutasiPindah');
            Route::post('dashboard/save-rencana-mutasi-mutasi', 'SaveRencanaMutasiMutasi');
            Route::post('dashboard/update-rencana-mutasi', 'UpdateStatusRencanaMutasi');
        });

        Route::controller(DashboardGiziCtrl::class)->group(function () {
            Route::get('dashboard/detail-pasien-gizi', 'headerPasienGizi');
            Route::get('dashboard/pasien-order-gizi', 'getPasienInap');
            Route::get('dashboard/histori-order-gizi', 'getHistoriOrder');
            Route::get('dashboard/dropdown-order-gizi', 'listOrderGizi');
            Route::get('dashboard/riwayat-order-gizi', 'riwayatOrderGizi');
            Route::get('dashboard/laporan-order-gizi', 'laporanOrderGizi');
            Route::get('dashboard/riwayat-kirim-gizi', 'riwayatKirimGizi');
            Route::get('dashboard/gizi/get-daftar-order-gizi', 'getDaftarOrderGizi');

            Route::post('dashboard/save-multiple-order-gizi', 'simpanMultipleOrderGizi');
            Route::post('dashboard/edit-multiple-order-gizi', 'editMultipleOrderGizi');
            Route::post('dashboard/edit-order-gizi', 'editOrderGizi');
            Route::post('dashboard/save-order-gizi', 'simpanOrderGizi');
            Route::post('dashboard/delete-order-gizi', 'deleteOrderGizi');
            Route::post('dashboard/multiple-delete-order-gizi', 'multipleDeleteOrderGizi');
            Route::post('dashboard/save-kirim-gizi', 'saveKirimGizi');
        });

        Route::controller(DashboardObatAlkesCtrl::class)->group(function () {
            Route::get('dashboard/data-obat', 'getObat');
            Route::get('farmasi/daftar-retur-obat-alkes', 'getDaftarReturObat');
            Route::get('farmasi/cetak-bukti-retur', 'cetakReturResep');
        });
        Route::controller(DashboardTindakanCtrl::class)->group(function () {
            Route::get('dashboard/data-tindakan', 'getTindakan');
            Route::get('dashboard/data-tindakan-dropdown', 'TindakanDropdown');
            Route::get('dashboard/data-noorder', 'getNoorder');
        });

        Route::controller(DashboardLaboratoriumCtrl::class)->group(function () {
            Route::get('dashboard/so-lab', 'getStrukOrderLab');
            Route::get('dashboard/list-lab', 'listLab');
            Route::get('dashboard/get-lab-verify', 'getOrderLab');
            Route::get('dashboard/lab-detail', 'getLabDetail');
            Route::get('dashboard/get-pelayanan-lab', 'getPelayananLab');
            Route::get('dashboard/get-order', 'getOrderPelayananLab');
            Route::get('dashboard/get-dokter-verify', 'ListDokterVerify');
            Route::get('dashboard/get-komponen-lab', 'getKomponenHargaLab');
            Route::get('dashboard/chart-lab-ruangan', 'chartOrderLabByRuangan');
            Route::get('dashboard/penunjang-lab', 'getPenunjangPasien');
            Route::get('dashboard/headerpasien', 'HeaderPasienLab');
            Route::get('laboratorium/report/bukti-layanan-lab', 'cetakBuktiLab');
            Route::get('dashboard/petugaspe', 'detailPetugasLab');

            Route::post('dashboard/change-dokter-order', 'updatePegawaiOrderGeneral');
            Route::post('dashboard/batal-verif-lab', 'BatalVerifLab');
            Route::post('dashboard/delete-pp', 'hapusPelayananTindakan');
            Route::post('dashboard/delete-petugaspe', 'deleteJenisPetugasLab');
            Route::post('dashboard/save-order-pelayanan-lab', 'savePelayananPasienLab');
            Route::post('dashboard/save-petugaspe', 'savePetugasPe');
            Route::post('dashboard/save-jenkel', 'UpdateJenisKelamin');
            Route::post('dashboard/save-goldar', 'UpdateGolonganDarah');
        });

        Route::controller(DashboardKasirCtrl::class)->group(function () {
            Route::get('dashboard/kasir', 'countDashboardKasir');
            Route::get('dashboard/data-combo-kasir', 'getDataComboKasir');
            Route::get('dashboard/tagihan-lunas', 'TagihanLunas');
            Route::get('dashboard/kasir/list-tagihan-pasien', 'listTagihanPasien');
            Route::get('dashboard/tagihan-non-layanan', 'TagihanNonLayanan');
            Route::get('dashboard/daftar-pasien-pulang', 'daftarPasienPulang');
            Route::get('dashboard/daftar-pasien-pulang/detail-verif', 'detailVerifikasi');
            Route::post('dashboard/daftar-pasien-pulang/batal-verif', 'batalVerifikasiTagihan');
            Route::post('dashboard/daftar-pasien-pulang/batal-piutang', 'batalPiutang');
            Route::get('dashboard/riwayat-openbill', 'riwayatOpenbill');
        });


        Route::controller(PasienLamaCtrl::class)->group(function () {
            Route::get('registrasi/pasien-lama', 'pasienLama');
            Route::get('registrasi/dropdown', 'dropdown');
            Route::get('registrasi/cek-pasien-pulang', 'cekPulangpasien');
            Route::get('registrasi/cek-pasien-piutang', 'cekPiutangpasien');
            Route::post('registrasi/delete-pasien', 'deletePasien');
        });
        Route::controller(PasienBaruCtrl::class)->group(function () {
            Route::get('registrasi/desa-kelurahan-paging', 'listDesaKelurahanPaging');
            Route::get('registrasi/kecamatan-paging', 'listKecamatanPaging');
            Route::get('registrasi/kotakabupaten', 'listKotaKab');
            Route::get('registrasi/kecamatan', 'listKecamatan');
            Route::get('registrasi/desakelurahan', 'listDesa');
            Route::get('registrasi/list-dropdown', 'listDropdown');
            Route::get('registrasi/list-dropdown-desa', 'listDesaNew');
            Route::get('registrasi/list-dropdown-provinsi', 'listProvinsiNew');
            Route::get('registrasi/list-dropdown-kabupaten', 'listKabupatenNew');
            Route::get('registrasi/list-dropdown-kecamatan', 'listKecamatanNew');
            Route::get('registrasi/pasien', 'pasienByID');
            Route::get('registrasi/riwayat-registrasi', 'riwayatRegistrasi');

            Route::post('registrasi/save-pasien', 'savePasien');
            Route::post('registrasi/save-pasien-pj', 'savePasienPJ');
            Route::post('registrasi/save-pasien-kartu', 'savePasienKartu');
            Route::post('registrasi/save-pasien-bayi', 'savePasienBayi');
            Route::post('registrasi/save-pasien-foto', 'savePasienFoto');
        });
        Route::controller(RegistrasiRuanganCtrl::class)->group(function () {
            Route::get('registrasi/pasien-registrasi', 'pasienRegistrasi');
            Route::get('registrasi/pasien-registrasi-rev', 'pasienRegistrasiRev');
            Route::get('registrasi/list-ruangan-rawat-jalan', 'listRuanganRJ');
            Route::get('registrasi/list-ruangan-rawat-jalan-semua', 'listRuanganRJSemua');
            Route::get('registrasi/list-kelompokpasien-all', 'listKelompokPasienAll');
            Route::get('registrasi/list-asalrujukan-pasien', 'asalRujukanPasien');
            Route::get('registrasi/list-ruangan-ri-rj', 'listRuanganRJRI');
            Route::get('registrasi/dokter-paging', 'listDokterPagingKontrol');
            Route::get('registrasi/dokter-paging-web', 'listDokterPagingWeb'); //RESERVASI, Saya alihin dulu ke function baru supaya jalan sementara
            Route::get('registrasi/list-kelas-detail-regis', 'listKelasDetailRegis');
            Route::get('registrasi/kelas-by-ruangan', 'listKelasByRuangan');
            Route::get('registrasi/kamar-by-kelas', 'listKamarByKelas');
            Route::get('registrasi/penjamin-by-kelompokpasien', 'listPenjaminByKelompokPasien');
            Route::get('registrasi/pilihan-ruangan', 'listRuanganByLoginUser');
            Route::get('registrasi/pasien-hari-ini', 'checkIsExsist');
            Route::get('registrasi/pasien-hari-ini-reservasi', 'checkIsExsistReservasi');
            Route::post('registrasi/get-asal-rujukan-id', 'getAsalRujukanID');

            Route::post('registrasi/save-registrasi', 'saveRegistrasi');
            Route::post('registrasi/save-registrasi-nuklir', 'saveRegistrasiNuklir');
            Route::post('registrasi/save-registrasi-nuklir-rev', 'saveOrderPenunjangNuklir');
            Route::post('registrasi/save-adminsitrasi', 'saveAdministrasi');
            Route::post('registrasi/edit-jenis-pembayaran', 'editJenisPembayaran');
        });
        Route::controller(AnggaranCtrl::class)->group(function () {
            Route::get('anggaran/get-combo', 'getCombo');
            Route::get('perencanaan/get-penjagaan-setting-anggaran', 'getPenjagaanSettingAnggaran');
            Route::get('anggaran/get-data-setting-anggaran', 'getDataSettingAnggaran');
            Route::get('anggaran/get-mata-anggaran', 'getMataAnggaran');
            Route::get('anggaran/get-mata-anggaran-permen', 'getMataAnggaranPermen');
            Route::get('perencanaan/get-data-rba-belum', 'getRBABelum');
            Route::get('anggaran/get-kegiatan-anggaran', 'getKegiatanAnggaran');
            Route::get('perencanaan/get-total-anggaran-rcn', 'getTotalAnggaranRcn');
            Route::get('perencanaan/get-jenis-belanja', 'getJenisBelanja');
            Route::get('perencanaan/get-daftar-pptk', 'getPPTK');
            Route::get('perencanaan/get-total-mata-anggaran', 'getTotalMataAnggaran');
            Route::get('perencanaan/get-keterangan-belanja', 'getKeteranganBelanja');
            Route::get('perencanaan/get-total-anggaran-rcn', 'getTotalAnggaranRcn');
            Route::get('anggaran/get-kegiatan-anggaran-kas', 'getKegiatanAnggaranKas');
            Route::get('perencanaan/get-detail-sub-kegiatan', 'getDetailSubKegiatan');
            Route::get('perencanaan/get-lock-entry-rba', 'getLockRBA');
            Route::get('perencanaan/get-total-mata-anggaran-keterangan', 'getTotalKeterangan');
            Route::get('perencanaan/get-rincian-belanja', 'getRincianBelanja');
            Route::get('perencanaan/get-spj', 'getDataSPJ');
            Route::get('perencanaan/get-data-panjar', 'getDataPanjar');
            Route::get('perencanaan/get-pengembalian-panjar', 'getPengembalianPanjar');
            Route::get('perencanaan/get-data-panjar-spj', 'getDataPanjarSPJ');
            Route::get('perencanaan/get-combo-spp', 'getComboSPP');
            Route::get('perencanaan/get-data-spd', 'getDataSPD');
            Route::get('perencanaan/get-data-spp', 'getDataSPP');
            Route::get('perencanaan/get-data-spm', 'getDataSPM');

            Route::post('perencanaan/save-lock-entry-rba', 'saveLockRBA');
            Route::post('perencanaan/save-setting-anggaran', 'saveSettingAnggaran');
            Route::post('perencanaan/save-mata-anggaran', 'saveMataAnggaran');
            Route::post('anggaran/hapus-mata-anggaran', 'hapusMataAnggaran');
            Route::post('anggaran/hapus-mata-anggaran-permen', 'hapusMataAnggaranPermen');
            Route::post('anggaran/save-mata-anggaran-permen', 'saveMataAnggaranPermen');
            Route::post('perencanaan/copy-permen', 'copyPermen');
            Route::post('perencanaan/save-kegiatan-anggaran', 'saveKegiatanAnggaran');
            Route::post('perencanaan/save-keterangan-belanja', 'saveKeteranganBelanja');
            Route::post('perencanaan/delete-kegiatan-anggaran', 'deleteKegiatanAnggaran');
            Route::post('anggaran/delete-anggaran-kas', 'deleteAnggaranKas');
            Route::post('perencanaan/save-alokasi-keterangan-belanja', 'saveAlokasiKeteranganBelanja');
            Route::post('perencanaan/save-setting-tahap', 'saveSettingTahap');
            Route::post('perencanaan/save-realisasi-spj', 'saveRealisasiSPJ');
            Route::post('perencanaan/delete-rincian-belanja', 'deleteRincianBelanja');
            Route::post('perencanaan/save-verif-spj', 'saveVerifSPJ');
            Route::post('perencanaan/batal-verif-spj', 'batalVerifSPJ');
            Route::post('perencanaan/delete-spj', 'deleteSPJ');
            Route::post('perencanaan/save-panjar', 'savePanjar');
            Route::post('perencanaan/hapus-panjar', 'hapusPanjar');
            Route::post('perencanaan/save-pengembalian', 'savePengembalian');
            Route::post('perencanaan/simpan-spj-panjar', 'saveSPJPanjar');
            Route::post('perencanaan/save-spd', 'saveSPD');
            Route::post('perencanaan/save-spp', 'saveSPP');
            Route::post('perencanaan/delete-spp', 'DeleteSPP');
            Route::post('perencanaan/save-spm', 'saveSPM');
            Route::post('perencanaan/delete-spm', 'deleteSPM');
        });
        Route::controller(DashboardAnggaranCtrl::class)->group(function () {
            Route::get('/dashboard/get-data-anggaran', 'getDataAnggaran');
        });
        Route::controller(HRISCtrl::class)->group(function () {
            Route::post('hris/sync-hristools', 'hrisTools');
            Route::get('hris/get-masterhris', 'getMasterHRIS');
        });
        Route::controller(ReportCtrl::class)->group(function () {
            Route::prefix('report')->group(function () {
                Route::get('/get-cetak-rba-detail', 'CetakRBADetail');
                Route::get('/get-cetak-rekap-SumberDana-anggaran-sebelum', 'CetakRekapSumberDanaAnggaranSebelum');
                Route::get('/get-cetak-per-jenis-anggaran', 'CetakPerJenisAnggaran');
                Route::get('/get-cetak-rekap-total-anggaran', 'CetakRekapTotalAnggaran');
                Route::get('/get-cetak-angkas-jadwal', 'CetakAngkasJadwal');
                Route::get('/get-cetak-kas', 'CetakKas');
                Route::get('/get-cetak-konsolidasi-per-permen', 'CetakKonsolidasiPermen');
                Route::get('/get-cetak-spj-dan-monev-rba', 'CetakSPJMonevRBA');
                Route::get('/get-cetak-spj-dan-monev', 'CetakSPJMonev');
                Route::get('/get-cetak-spj', 'getCetakSPJ');
                Route::get('/get-cetak-pengantar-spp', 'getCetakPengantarSPP');
                Route::get('/get-cetak-ringkasan-spp', 'getCetakRingkasanSPP');
                Route::get('/get-cetak-rincian-spp', 'getCetakRincianSPP');
                Route::get('/get-cetak-pengantar-spm', 'getCetakPengantarSPM');
                Route::get('/get-cetak-pertanggungjawaban-spm', 'getCetakPertanggungjawabanSPM');
                Route::get('/get-cetak-rincian-spm', 'getCetakRincianSPM');
            });
        });
        Route::controller(PenjadwalanKemoterapiCtrl::class)->group(function () {
            Route::prefix('penjadwalan-kemoterapi')->group(function () {
                Route::get('/list-dropdown', 'listDropdown');
                Route::get('/get-penjadwalan', 'getPenjadwalan');
                Route::get('/get-detail-penjadwalan', 'getDetailPenjadwalan');

                Route::post('/save-penjadwalan', 'savePenjadwalan');
                Route::post('/verifikasi-penjadwalan', 'verifikasiPenjadwalan');
            });
        });
        Route::controller(PemakaianAsuransiCtrl::class)->group(function () {
            Route::get('registrasi/pemakaian-asuransi', 'pemakaianAsuransi');
            Route::get('registrasi/pemakaian-asuransi/sep', 'cetakSEP');
            Route::get('registrasi/pemakaian-asuransi/sep-klaim', 'cetakSEPKlaim');

            Route::post('registrasi/pemakaian-asuransi/save', 'savePemakaianAsuransi');
        });
        Route::controller(PasienCtrl::class)->group(function () {
            Route::get('registrasi/list-pasien-grid', 'listPasienGrid');
            Route::get('registrasi/count-daftar', 'CountDaftar');

            Route::post('registrasi/batal-meninggal', 'batalMeninggal');
        });

        Route::controller(DaftarRegistrasiCtrl::class)->group(function () {
            Route::get('registrasi/daftar-registrasi-grid', 'listRegistrasi');
            Route::get('registrasi/daftar-registrasi-dropdown', 'listRegistrasiDropdown');
            Route::post('registrasi/cancel-registration', 'batalRegistrasi');
            Route::post('registrasi/change-dokter-registration', 'ubahDokter');
            Route::get('registrasi/get-detail-registrasi', 'detailRegistrasi');
            Route::get('registrasi/get-detail-registrasi-pasien', 'detailRegistrasiPasien');
            Route::get('registrasi/get-data-combo-detail-registrasi', 'getDataComboDetailRegis');
            Route::post('registrasi/simpan-pasien-konsul', 'simpanKonsul');
            Route::post('registrasi/change-dokter-apd', 'ubahDokterAPD');
            Route::post('registrasi/change-dokter-dpjp', 'ubahDokterDPJP');
            Route::post('registrasi/hapus-apd', 'hapusAPD');
            Route::post('registrasi/ubah-tanggal', 'ubahTanggalDetailRegis');
            Route::post('registrasi/ubah-kelas', 'ubahKelas');
            Route::get('registrasi/get-daftar-pasien-meninggal2', 'getDaftarPasienMeningga2');
            Route::get('registrasi/get-daftar-konsultasi', 'getDaftarKonsulFromOrder');
            Route::get('registrasi/get-order-konsul', 'getOrderKonsul');
            Route::post('registrasi/get-save-konsul-order', 'saveKonsulFromOrder');
            Route::post('registrasi/save-pilih-dokter-konsul', 'updateDokterAntrian');
            Route::post('registrasi/tetapkan-perawat', 'tetapkanPerawat');
        });
        Route::controller(ProfilePasienCtrl::class)->group(function () {
            Route::get('emr/header-pasien', 'headerPasien');
            Route::get('emr/riwayat-pasien', 'getRiwyatPasien');
            Route::get('emr/detail-pelayanan', 'detailPelayanan');
            Route::get('emr/list-pasien-rj', 'listPasienRJ');
            Route::get('emr/surat-keterangan', 'suratKeterangan');
            Route::get('emr/set-ok', 'setOK');
            Route::get('emr/set-ranap', 'setRanap');
            Route::get('emr/set-rujukan', 'setRujukan');
            Route::get('emr/form-admisi', 'formAdmisi');
            Route::get('emr/total-biliing', 'getTotalBilling');
            Route::get('emr/info-pasien', 'infoPasien');
            Route::get('emr/history-sim-lama', 'detailPelayananSIMRSLama');
            Route::get('dokter/get-catatan', 'getCatatanDokter');
            Route::get('dokter/get-kemo', 'getHistoriKemo');
            Route::get('emr/hasil-lab', 'getHasilLab');
            Route::get('emr/hasil-lab-pa', 'hasilLabPA');
            Route::get('pasien/show-akses-emr', 'showAksesEMR');
            Route::get('pasien/get-riwayat-kontrol', 'getRiyawatKontrol');
            Route::get('pasien/get-riwayat-kontrol-terakhir', 'getRiyawatKontrolTerakhir');
            Route::get('pasien/get-poli-kontrol', 'getPoli');
            Route::get('pasien/get-poli-kontrol-kasir', 'getPoliKasir');
            Route::get('pasien/get-dokter-kontrol', 'getDokter');

            Route::post('pasien/hapus-riwayat-kontrol', 'deleteRiwayatKontrol');
            Route::post('pasien/create-riwayat-kontrol', 'saveRiwayatKontrol');
            Route::post('pasien/create-riwayat-kontrol-web', 'saveRiwayatKontrolWeb');
            Route::post('pasien/update-surat-kontrol', 'updateSuratKontrol');
            Route::post('emr/simpan-alergi-pasien', 'simpanAlergiPasien');
            Route::post('emr/closing-pasien', 'changeClosing');
            Route::post('dokter/save-catatan', 'saveCatatanDokter');
            Route::post('dokter/save-riwayat-kanker', 'saveHistoriKemo');
            Route::post('pasien/buka-emr', 'bukaEMR');
            Route::post('pasien/kunci-emr', 'kunciEMR');
        });
        Route::controller(EMRCtrl::class)->group(function () {
            Route::get('emr/get-emr', 'getEMR');
            Route::get('emr/get-obat-by-pasien', 'getOrderObatByNocmfk');
            Route::get('emr/get-emr-monitoring-icu-jadwal-terbuka', 'getEMRMonitoringICUJadwalTerbuka');
            Route::get('emr/get-emr-monitoring-icu-jadwal-tertutup', 'getEMRMonitoringICUJadwalTertutup');
            Route::get('emr/get-emr-monitoring-icu-jadwal-tertutup-detail', 'getEMRMonitoringICUJadwalTertutupDetail');
            Route::get('emr/check-resume-medis', 'checkresume');
            Route::get('emr/check-assesmen-medis', 'checkAsmed');
            Route::get('emr/check-lembar-penyinaran', 'checkLP');
            Route::get('emr/check-suket-gadar', 'checkSuketGadar');
            Route::get('emr/get-medis-diagnosa', 'getMedisDiagnosa');
            Route::get('emr/get-emr-template', 'getEMRTemplate');
            Route::get('emr/get-emr-labo', 'getEMRLab');
            Route::get('emr/get-emr-radio', 'getEMRRadio');
            Route::get('emr/get-emr-history-terakhir', 'getEMR');
            Route::get('emr/get-emr-history-terakhir-v2', 'getLastEMR');
            Route::get('emr/get-emr-history-ct', 'getEMRCT');
            Route::get('emr/get-emr-history-edukasi', 'getEmrRE');
            Route::get('emr/get-emr-tgl-terakhir', 'getEMRTgl');
            Route::get('emr/check-asesmen-cppt', 'checkAsesmenCPPT');
            Route::get('emr/check-asesmen-hemo', 'checkAsesmenHemo');
            Route::get('emr/get-asesmen-ranap', 'getAsesmenRanap');
            Route::get('emr/get-emr-tgl-terakhir-dengan-ruangan', 'getEMRTglRuangan');
            Route::get('emr/riwayat-emr', 'getRiwayatEMR');
            Route::get('emr/riwayat-emr-detail', 'getRiwayatEMR_DETAIL');
            Route::get('emr/menu-emr-detail', 'menuEMR');
            Route::get('emr/menu-emr-bundle', 'menuEMRBundle');
            Route::get('emr/menu-emr-bundle-detail', 'menuEMRBundleDetail');
            Route::get('emr/get-list-pegawai', 'getDataComboPegawai');
            Route::get('emr/get-diagnosa-pasien-icd9', 'getDiagnosaPasienByNoregICD9');
            Route::get('emr/get-diagnosa-pasien-icd10', 'getDiagnosaPasienICD10');
            Route::get('emr/get-data-diagnosa', 'getDataComboDiagnosa');
            Route::get('emr/get-master-obat', 'getMasterObat');
            Route::get('emr/get-kasus-diagnosa', 'getKasusDiagnosa');
            Route::get('emr/get-ibu', 'getIbu');
            Route::get('emr/dropdown/{table}', 'dropdownEMR');
            Route::get('emr/dropdown/custom/get-ruangan', 'getRuanganCustom');
            Route::get('emr/dropdown/custom/get-kelas', 'getKelas');
            Route::get('emr/dropdown/custom/get-user-ruangan', 'getUserRuangan');
            Route::get('emr/get-pasien-by-nocm', 'getPasieBynocm');
            Route::get('emr/get-emr-dynamic', 'getEMRDynamic');
            Route::get('emr/get-data-exist', 'getDataExist');
            Route::get('emr/get-data-exist-semua', 'getDataExistSemua');
            Route::get('emr/get-data-exist-semua-cppt', 'getDataExistSemuaCPPT');
            Route::get('emr/get-data-exist-pertama', 'getDataExistPertama');
            Route::get('emr/get-dokter-dpjp', 'getDokterDPJP');
            Route::get('emr/tanda-tangan/{pegawaifk}', 'getTandaTangan');
            Route::get('emr/auto-fill', 'getAutoFill');
            Route::get('emr/menu-emr', 'menuNavigasi');
            Route::get('emr/master-bundle-hais', 'getDataBundleHais');
            Route::get('emr/get-obat', 'getDataComboPartObat');
            Route::get('emr/auto-fill-icd10/{noregistrasi}', 'getAutoFillICD10');
            Route::get('emr/master-faktor-resiko', 'getFaktorRisiko');
            Route::get('emr/get-ecg-fukuda', 'getFukuda');
            Route::get('emr/combo-jenis-berkas', 'getComboBerkas');
            Route::get('emr/berkas-pasien', 'getBerkasPasien');
            Route::get('emr/get-perjanjian', 'getPasienPerjanjian');
            Route::get('emr/collection/{url_form}', 'getCollectionNameByForm');
            Route::get('emr/get-order-konsul', 'getOrderKonsul');
            Route::get('emr/get-resume-medis', 'getResumeMedis');
            Route::get('emr/get-dropdown-diagnosa-keperawatan', 'getDropdownDiagnosaKeper');
            Route::get('emr/get-emr-cppt', 'getEMRCPPT');
            Route::get('emr/list-diagnosa-keperawatan', 'listDiagnosaKeperawatan');
            Route::get('emr/list-diagnosa-sdki', 'listDiagnosaSdki');
            Route::get('emr/list-siki', 'listSiki');
            Route::get('emr/list-tujuan-keperawatan', 'listTujuanKeperawatan');
            Route::get('emr/list-intervensi', 'listIntervensiKeperawatan');
            Route::get('emr/list-implementasi', 'implementasiKeperawatan');
            Route::get('emr/auto-resep', 'getResep');
            Route::get('emr/get-petugas', 'getPetugasPe');
            Route::get('emr/get-hasil-radiologi', 'getHasilRadiologi');
            Route::get('emr/get-data-odontogram', 'getDataOdontogram');
            Route::get('emr/get-tabs-emr', 'getEMRTabs');
            Route::get('emr/update-cppt-invalid', 'updateCPPTInvalid');
            Route::get('emr/get-emr-igd', 'getEMRIGD');
            Route::get('emr/get-riwayat-menyusui', 'getRiwayatMenyusui');
            Route::get('emr/get-skema-penyinaran', 'getSkemaPenyinaran');
            Route::get('emr/get-penunjang-khusus', 'getPenunjangKhusus');
            Route::get('emr/get-rencanaperawat-ranap', 'getRencanaKeperawatanRanap');
            Route::get('emr/get-data-formulir-fisik', 'getDataFormulirFisik');
            Route::get('emr/get-data-lokalis-mata', 'getDataLokalisMata');
            Route::get('emr/get-order-lab', 'getOrderLab');
            Route::get('emr/get-detail-hasil-lab', 'getDetailHasilLab');
            Route::get('emr/get-order-radiologi', 'getOrderRadiologi');
            Route::get('emr/get-detail-hasil-radiologi', 'getDetailHasilRadiologi');
            Route::get('emr/check-departemen-masuk-pasien', 'getDepartemenMasukPasien');
            Route::get('emr/get-order-bedah', 'getOrderBedah');
            Route::get('emr/menu-profile-pasien', 'getMenuProfilePasien');

            Route::post('emr/hapus-penunjang/{collection}', 'hapusPenunjang');
            Route::post('emr/hapus-template', 'deleteEMRTemplate');
            Route::post('emr/simpan-emr', 'saveEMR');
            Route::post('emr/simpan-emr-template', 'saveEMRTemplate');
            Route::post('emr/simpan-emr-igd', 'saveEMRIGD');
            Route::post('emr/simpan-emr-surket', 'saveSuratKeterangan');
            Route::post('emr/hapus-emr', 'hapusEMR');
            Route::post('emr/simpan-emr-cppt', 'saveEMRCPPT');
            Route::post('emr/update-cppt', 'updateCPPT');
            Route::post('emr/simpan-bundle-hais', 'saveBundleHis');
            Route::post('emr/hapus-bundle-hais', 'hapusBundleHis');
            Route::post('emr/simpan-berkas-pasien-old', 'saveBerkasPasienOld');
            Route::post('emr/simpan-berkas-pasien', 'saveBerkasPasien');
            Route::post('emr/hapus-berkas-pasien', 'hapusBerkasPasien');
            Route::post('emr/simpan-perjanjian', 'simpanPerjanjian');
            Route::post('emr/hapus-perjanjian', 'hapusOrderPerjanjian');
            Route::post('emr/simpan-order-konsul', 'saveOrderKonsul');
            Route::post('emr/hapus-order-konsul', 'hapusOrderKonsul');
            Route::post('emr/jawab-order-konsul', 'jawabOrderKonsul');
            Route::post('emr/uploadImage', 'uploadImageBarcode');
            Route::post('emr/simpan-transaksi-odontogram', 'SaveTransaksiEMROdontogram');
            Route::post('emr/simpan-resume', 'saveResumeMedis');
            Route::post('emr/hapus-resume-medis', 'hapusResumeMedis');
            Route::post('emr/simpan-skema-penyinaran', 'saveSkemaPenyinaran');
            Route::post('emr/hapus-skema-penyinaran', 'hapusSkemaPenyinaran');
        });

        Route::controller(ReportEMRCtrl::class)->group(function () {
            Route::get('emr/cetak-asesmen-medis-ri', 'cetakAsesmenMedisRI');
            Route::get('emr/cetak-asesmen-gizi-awal', 'cetakAsesmenGiziAwal');
            Route::get('emr/cetak-asesmen-awal-keper-ri', 'cetakAsesmenAwalKeperawatanRI');
            Route::get('emr/cetak-triase', 'cetakTriase');
            Route::get('emr/cetak-resume-medis-rj', 'cetakResumeRJ');
            Route::get('emr/cetak-asesmen-keper-rj', 'cetakAsesmenAwalKeperRJ');
            Route::get('emr/cetak-asesmen-medis-rj', 'cetakAsesmenMedisRJ');
            Route::get('emr/cetak-hasil-pemeriksaan-mcu', 'cetakHasilPemeriksaanMCU');
            Route::get('emr/cetak-pola-nafas-tidak-efektif', 'cetakPolaNafasTidakEfektif');
            Route::get('emr/cetak-formulir-skrining-igd', 'cetakFormulirSkriningIGD');
            Route::get('emr/cetak-resume-medis', 'cetakResumeMedis');
            Route::get('emr/cetak-formulir-permintaan-konseling-gizi', 'cetakFormulirPermintaanKonselingGizi');
            Route::get('emr/cetak-asesmen-keperawatan-igd', 'cetakAsesmenKeperawatanIGD');
            Route::get('emr/cetak-lembar-bantu-pengamatan-menyusui', 'cetakLembarBantuPengamatanMenyusui');
            Route::get('emr/cetak/cetak-cppt', 'cetakCPPT');
            Route::get('emr/cetak/cetak-cppt-klaim', 'cetakCPPTKlaim');
            Route::get('emr/cetak/{collection}', 'cetakEMR');
            Route::get('emr/cetak2/{collection}', 'cetakEMR2');
            Route::get('emr/cetak-detail/{collection}', 'cetakEMRDetail');
            Route::get('emr/cetak-surat-kontrol', 'cetakSuratKontrol');
            Route::get('emr/cetak-spri', 'cetakSPRI');
            Route::get('emr/cetak-rencana-kontrol', 'cetakRencanaKontrol');
            Route::get('emr/cetak-rencana-kontrol-poli', 'cetakRencanaKontrolPoli');
            Route::get('emr/cetak-rujukan', 'cetakRujukan');
            Route::get('emr/get-riwayat-resep', 'getResep');
            Route::get('emr/cetak-pengkajian-dokter-ri', 'cetakPengkajianDokterRi');
            Route::get('emr/cetak-formulir-echo-poli-jantung-TransThoracaEchoBayi', 'cetakFormulirTranstorachaEchoBayi');
            Route::get('emr/cetak-formulir-echo-poli-jantung-TransThoracaEchoDewasa', 'cetakFormulirTranstorachaEchoDewasa');
            Route::get('emr/cetak-formulir-echo-poli-jantung-LowerExtermityDuplexUltrasoundUSGDoppler', 'cetakFormulirLowerExtermityDuplexUltrasoundUSGDoppler');
            Route::get('emr/cetak-formulir-echo-poli-jantung-CarotidDuplexUltrasound', 'cetakFormulirCarotidDuplexUltrasound');
            Route::get('emr/cetak-formulir-hasil-pemeriksaan-ekg', 'cetakFormulirHasilPemeriksaanEkg');
            Route::get('emr/cetak-formulir-bukti-pelayanan-canggih', 'cektakFormulirBuktiPelayananCanggih');
            Route::get('emr/cetak-formulir-kriteria-masuk-icu', 'cetakFormulirKriteriaMasukICU');
            Route::get('emr/cetak-formulir-kriteria-masuk-hcu', 'cetakFormulirKriteriaMasukHCU');
            Route::get('emr/cetak-formulir-kriteria-masuk-iccu', 'cetakFormulirKriteriaMasukICCU');
            Route::get('emr/cetak-formulir-kriteria-masuk-stroke-corner', 'cetakFormulirKriteriaMasukStrokeCorner');
            Route::get('emr/cetak-formulir-pengkajian-tingkat-keparahan-stroke', 'cetakPengkajianTingkatKeparahanStroke');
            // Route::get('emr/cetak-informasi-edukasi-rj', 'cetakCatatanInformasiDanEdukasiRJ');
        });

        Route::controller(TindakanCtrl::class)->group(function () {
            Route::get('tindakan/header-pasien', 'headerPasien');
            Route::get('tindakan/list-tindakan', 'listTindakan');
            Route::get('tindakan/list-tindakan-komponen', 'listTindakanKomponen');
            Route::get('tindakan/list-jenis-petugas', 'listJenisPetugasPE');
            Route::get('tindakan/list-map-jenis-petugas', 'listMapJenisPetugasPE');
            Route::get('tindakan/list-map-jenis-petugas-all', 'listMapPetugasAll');
            Route::get('tindakan/list-paket', 'listPaket');
            Route::get('tindakan/list-dropdown-registrasi', 'listRegistrasi');
            Route::get('tindakan/list-petugas', 'listPetugas');
            Route::post('tindakan/save-tindakan-operasi', 'saveTindakanOperasi');
            Route::post('tindakan/save-tindakan', 'saveTindakan');
            Route::post('tindakan/save-temp-tindakan', 'saveTempTindakan');
            Route::get('tindakan/get-temp-tindakan', 'getTempTindakan');
            Route::post('tindakan/update-status-temp', 'updateTempTindakan');
        });
        Route::controller(InputDiagnosaCtrl::class)->group(function () {
            Route::get('diagnosa/header-pasien', 'headerPasien');
            Route::get('diagnosa/diagnosa-x-paging', 'listDianosaX');
            Route::get('diagnosa/diagnosa-morfologi-paging', 'listDianosaO');
            Route::get('diagnosa/diagnosa-ix-paging', 'listDianosaIX');
            Route::get('diagnosa/list-dropdown', 'listDropdownDiagnosa');
            Route::get('diagnosa/riwayat-diagnosa-x', 'riwayatDiagnosaX');
            Route::get('diagnosa/riwayat-moi', 'riwayatMOI');
            Route::get('diagnosa/riwayat-diagnosa-x-rm', 'riwayatDiagnosaXRM');
            Route::get('diagnosa/riwayat-diagnosa-x-klaim', 'riwayatDiagnosaXKlaim');
            Route::get('diagnosa/riwayat-diagnosa-x-klaim-ina', 'riwayatDiagnosaXKlaimINA');
            Route::get('diagnosa/riwayat-diagnosa-x-cppt', 'riwayatDiagnosaXCppt');
            Route::get('diagnosa/riwayat-diagnosa-ix', 'riwayatDiagnosaIX');
            Route::get('diagnosa/riwayat-diagnosa-o', 'riwayatDiagnosaO');
            Route::get('diagnosa/riwayat-diagnosa-ix-rm', 'riwayatDiagnosaIXRM');
            Route::get('diagnosa/riwayat-diagnosa-ix-klaim', 'riwayatDiagnosaIXKlaim');
            Route::get('diagnosa/riwayat-diagnosa-ix-klaim-ina', 'riwayatDiagnosaIXKlaimINA');
            Route::get('diagnosa/riwayat-diagnosa-ix-cppt', 'riwayatDiagnosaIXCppt');

            Route::post('diagnosa/save-diagnosa', 'saveDiagnosaPasien');
            Route::post('diagnosa/save-diagnosa-rm', 'saveDiagnosaPasienRM');
            Route::post('diagnosa/save-diagnosa-klaim', 'saveDiagnosaPasienKlaim');
            Route::post('diagnosa/save-diagnosa-klaim-primary', 'saveDiagnosaPasienKlaimPrimary');
            Route::post('diagnosa/save-moi', 'saveMOI');
            Route::post('diagnosa/save-diagnosa-ix', 'saveDiagnosaTindakanPasien');
            Route::post('diagnosa/save-diagnosa-ix-rm', 'saveDiagnosaTindakanPasienRM');
            Route::post('diagnosa/save-diagnosa-ix-klaim', 'saveDiagnosaTindakanPasienKlaim');
            Route::post('diagnosa/save-diagnosa-o-rm', 'saveDiagnosaMorfologiPasienRM');
            Route::post('diagnosa/save-more-diagnosa', 'saveMoreDiagnosaPasien');
            Route::post('diagnosa/save-more-diagnosa-ix', 'saveMoreDiagnosaTindakanPasien');
            Route::post('diagnosa/delete-diagnosa-x', 'deleteDiagnosaPasienX');
            Route::post('diagnosa/delete-diagnosa-o', 'deleteDiagnosaPasienO');
            Route::post('diagnosa/delete-diagnosa-ix', 'deleteDiagnosaPasienIX');
        });
        Route::controller(OrderLaboratoriumCtrl::class)->group(function () {
            Route::get('laboratorium/header-pasien-order', 'headerPasienOrder');
            Route::get('laboratorium/list-dropdown', 'listDropdown');
            Route::get('laboratorium/list-tindakan-for-order', 'listTindakanForOrder');
            Route::get('laboratorium/riwayat-order', 'listRiwayatOrder');
            Route::get('laboratorium/riwayat-order-lis', 'riwayatOrderLIS');
            Route::get('laboratorium/detail-order', 'detailOrder');

            Route::post('laboratorium/simpan-order', 'simpanOrderLab');
            Route::post('laboratorium/simpan-order-pa', 'simpanOrderLabPA');
            Route::post('laboratorium/order-merge', 'orderMerge');
            Route::post('laboratorium/simpan-order-susulan', 'simpanOrderLabSusulan');
            Route::post('laboratorium/delete-order', 'hapusOrderLab');
            Route::post('laboratorium/hapus-amprah', 'hapusAmprahLab');
            Route::post('laboratorium/save-berkas-lab', 'saveBerkasLab');
            Route::post('laboratorium/update-filter-produk-lab', 'updateFilterProd');
        });
        Route::controller(OrderRadiologiCtrl::class)->group(function () {
            Route::get('radiologi/header-pasien-order', 'headerPasienOrder');
            Route::get('radiologi/list-dropdown', 'listDropdown');
            Route::get('radiologi/list-tindakan-for-order', 'listTindakanForOrder');
            Route::get('radiologi/riwayat-order', 'listRiwayatOrder');
            Route::get('radiologi/detail-order', 'detailOrder');

            Route::post('radiologi/delete-order-rad', 'hapusOrderRad');
        });
        Route::controller(OrderCathlabCtrl::class)->group(function () {
            Route::get('cathlab/header-pasien-order', 'headerPasienOrder');
            Route::get('cathlab/list-dropdown', 'listDropdown');
            Route::get('cathlab/list-tindakan-for-order', 'listTindakanForOrder');
            Route::get('cathlab/riwayat-order', 'listRiwayatOrder');
            Route::get('cathlab/detail-order', 'detailOrder');

            Route::post('cathlab/delete-order-rad', 'hapusOrderRad');
        });
        Route::controller(OrderBedahCtrl::class)->group(function () {
            Route::get('bedah/header-pasien-order', 'headerPasienOrder');
            Route::get('bedah/list-dropdown', 'listDropdown');
            Route::get('bedah/list-tindakan-for-order', 'listTindakanForOrder');
            Route::get('bedah/riwayat-order', 'listRiwayatOrder');
            Route::get('bedah/detail-order', 'detailOrder');
            Route::get('bedah/get-data-autofill-bedah', 'autoFillBedah');
            Route::get('bedah/surat-pengantar-rencana-operasi', 'SuratPengantarRencanaOperasi');
            Route::get('bedah/surat-pengantar-rencana-operasi-klaim', 'SuratPengantarRencanaOperasiKlaim');
            Route::get('nuklir/list-dropdown', 'listDropdownNuklir');

            Route::post('bedah/simpan-order', function (Request $request) {
                return app('App\Http\Controllers\Laboratorium\OrderLaboratoriumCtrl')->simpanOrderLab($request);
            });
            Route::post('bedah/delete-order-bedah', 'hapusOrderBedah');
        });
        Route::controller(OrderDarahCtrl::class)->prefix('darah')->group(function () {
            Route::get('/list-dropdown', 'listDropdown');
            Route::get('/riwayat-order', 'listRiwayatOrder');

            Route::post('/simpan-order', 'simpanOrderLab');
            Route::post('/delete-order-darah', 'hapusOrderDarah');
        });
        Route::controller(OrderResepCtrl::class)->group(function () {
            Route::get('farmasi/riwayat-order-resep', 'riwayatOrderResep');
            Route::get('farmasi/riwayat-resep-verif', 'resepVerif');
            Route::get('farmasi/riwayat-resep-rutin', 'resepRutin');
            Route::get('farmasi/riwayat-ppra', 'getHistoryPpra');
            Route::get('farmasi/riwayat-resep-pulang', 'riwayatResepPulang');
            Route::get('farmasi/data-order-resep-hari-ini', 'getOrderResepNow');
            Route::get('farmasi/data-paket-obat', 'getDataPaketObat');
            Route::get('farmasi/retur-resep', 'returResep');
            Route::post('farmasi/retur-obat-perawat', 'returObatPerawat');
            Route::post('farmasi/simpan-permohonan-permintaan-alat-kuao', 'saveAlatKuao');
            Route::post('farmasi/update-permohonan-permintaan-alat-kuao', 'UpdateAlatKuao');
            Route::post('farmasi/set-no-antrian-farmasi', 'SetNoAntrianFarmasi');
            Route::post('farmasi/simpan-order', 'simpanOrderResep');
            Route::post('farmasi/hapus-order', 'hapusOrderResep');
        });
        Route::controller(StokRuanganCtrl::class)->group(function () {
            Route::get('logistik/stok-ruangan-grid', 'getDataGrid');
            Route::get('logistik/stok-ruangan-grid-rekap', 'getDataGridRekap');
            Route::get('logistik/stok-ruangan-grid-order', 'getDataGridOrder');
            Route::get('logistik/stok-ruangan-grid-sr', 'getDataGridSR');
            Route::get('logistik/daftar-pemakaian-stok-ruangan', 'getDaftarPemakaianStokRuangan');
            Route::get('logistik/stok-ruangan-cbo', 'getCombo');
            Route::get('logistik/get-detail-pemakaian-ruangan', 'getPemakaianStokRuanganByNorec');
            Route::post('logistik/save-pemakaian-stok-ruangan', 'savePemakaianStokRuangan');
            Route::post('logistik/update-tanggal-kadaluarsa', 'updateED');
            Route::post('logistik/hapus-pemakaian-stok-ruangan', 'hapusPemakaianStokRuangan');
            Route::post('logistik/save-adjustman-stok', 'saveAdjustmentStok');
            Route::get('logistik/daftar-harga-jual', 'getDataHargaJual');
        });

        Route::controller(PulangPindahCtrl::class)->group(function () {
            Route::get('rawatinap/get-pasien-pindah', 'dataPasien');
            Route::get('rawatinap/combo-pindah', 'dropDownPulang');
            Route::get('rawatinap/riwayat-apd', 'riwayatAPD');
            Route::get('rawatinap/kelas-ranap-by-ruangan', 'RanapKelasByRuangan');
            Route::get('rawatinap/kamar-ranap-by-kelas', 'RanapKamarByKelas');

            Route::post('rawatinap/save-pulang-pasien', 'savePulang');
            Route::post('rawatinap/save-pindah-pasien', 'savePindah');
            Route::post('rawatinap/save-meninggal-pasien', 'saveMeninggal');
            Route::post('rawatinap/save-rujuk-pasien', 'saveRujuk');
            Route::post('rawatinap/batal-pindah-pasien', 'saveBatalPindah');
            Route::post('rawatinap/update-rencana-mutasi', 'updateRencanaMutasi');
            Route::post('rawatinap/save-pindah-bed-pasien', 'savePindahBed');
            Route::post('rawatinap/batal-pulang-pasien', 'saveBatalPulang');
        });
        Route::controller(PersediaanCtrl::class)->group(function () {
            Route::get('farmasi/saldo/ruangan', 'getRuanganPersediaan');
            Route::get('farmasi/get-saldo-ruangan-detail', 'getDataSaldoRuanganDetail');
            Route::get('farmasi/persediaan-dropdown', 'getDropdownPersediaan');
            Route::get('farmasi/get-data-laporan-persediaan-v4', 'getLaporanPersediaan_v4_2');
            Route::get('farmasi/get-data-laporan-persediaan-v5', 'getLaporanPersediaan_v5_2');
            Route::get('farmasi/get-data-laporan-persediaan-new', 'getLaporanPersediaan_new');

            Route::post('farmasi/get-save-saldo-produk-detail', 'saveSaldoProdukDetail');
        });

        Route::controller(DaftarPasienPerjanjianCtrl::class)->group(function () {
            Route::get('registrasi/daftar-pasien-penunjang', 'index');
            Route::post('registrasi/update-tanggal-reservasi', 'updateTglReservasi');
            Route::post('registrasi/delete-pasien-penunjang', 'deleteReservasi');
        });

        Route::controller(LaporanPengunjungCtrl::class)->group(function () {
            Route::get('pelayanan/get-laporan-kunjungan', 'getLaporanPengunjungPemeriksaan');
            Route::get('pelayanan/get-laporan-pengunjung', 'getLaporanPengunjung');
            Route::get('pelayanan/get-laporan-pengunjung-tindakan', 'getLaporanPengunjungTindakan');
            Route::get('pelayanan/get-laporan-penyerahan-obat', 'getLaporanPenyerahanObat');
            Route::get('pelayanan/get-laporan-retur-suplier', 'getDaftarReturPenerimaanSuplierDetail');
            Route::get('pelayanan/get-laporan-retur-obat', 'getDaftarReturObatDetail');
            Route::get('pelayanan/get-laporan-sensus-rawat-inap', 'getLaporanSesusRawatInap');
            Route::get('pelayanan/get-laporan-antrian-kuota-poli', 'getLaporanAntrianKuotaPoli');
            Route::get('pelayanan/get-laporan-antrian-online', 'informasiAntrianPasienAntrol');
            Route::get('pelayanan/get-ruangan-poli', 'getRuanganPoli');
            Route::get('pelayanan/get-laporan-resep', 'LaporanObatPerResep');
            Route::get('pelayanan/get-laporan-persentase-mikroba', 'LaporanPersentaseMikroba');
            Route::get('pelayanan/get-laporan-kuantitas-antimikroba', 'LaporanKuantitasAntimikroba');
            Route::get('pelayanan/get-laporan-pasien', 'LaporanObatPasien');
            Route::get('pelayanan/get-laporan-pelayanan-resep', 'LaporanPelayananResep');
            Route::get('pelayanan/get-laporan-perencanaan-BMHP', 'LaporanPerencanaanBHMP');
            Route::get('pelayanan/get-laporan-perencanaan-obat', 'LaporanPerencanaanObat');
            Route::get('pelayanan/get-laporan-perencanaan-farmasi', 'LaporanPerencanaanFarmasi');
            Route::get('pelayanan/get-laporan-perencanaan-BMHP-2', 'LaporanPerencanaanBHMPPart2');
            Route::get('pelayanan/get-laporan-perencanaan-Obat-2', 'LaporanPerencanaanObatPart2');
            Route::get('pelayanan/get-laporan-pasien-ranap', 'LaporanPasienRawatInap');
            Route::get('pelayanan/get-laporan-perencanaan-AMHP-2', 'LaporanPerencanaanAMHPPart2');
            Route::get('pelayanan/get-laporan-kadaluarsa-BMHP', 'LaporanKadaluarsaBMHP');
            Route::get('pelayanan/get-laporan-kadaluarsa-obat', 'LaporanKadaluarsaObat');
            Route::get('pelayanan/get-laporan-kadaluarsa-satelit', 'LaporanKadaluarsaSatelit');
            Route::get('pelayanan/get-laporan-kadaluarsa-obat-2', 'LaporanKadaluarsaObatPart2');
            Route::get('pelayanan/get-laporan-kadaluarsa-BMHP-2', 'LaporanKadaluarsaBMHPPart2');
            Route::get('pelayanan/laporan-persediaan-psikotropika', 'laporanPsikotropika');
            Route::get('pelayanan/laporan-persediaan-narkotik', 'laporanNarkotik');
            Route::get('pelayanan/get-laporan-penerimaan', 'LaporanPenerimaan');
            Route::get('pelayanan/get-laporan-amprahan', 'LaporanDistribusi');
            Route::get('pelayanan/get-laporan-mutasi-ed', 'LaporanKirimKadaluarsa');
            Route::get('pelayanan/get-laporan-penerimaan-pinjam', 'LaporanPenerimaanPinjam');
        });

        Route::controller(LaporanTindakanPasienCtrl::class)->group(function () {
            Route::get('laporan/get-laporan-pengunjung', 'getPelayananTindakan');
            Route::get('laporan/get-laporan-batal-amprah', 'getBatalAmprah');
            Route::get('laporan/laporan-time-respon', 'getTimeRespon');
            Route::get('laporan/pilihan-search', 'pilihanSearch');
        });

        Route::controller(LaporanKeteranganLahirCtrl::class)->group(function () {
            Route::get('laporan/get-laporan-lahir', 'getLaporanLahir');
            Route::get('laporan/get-all-laporan-lahir', 'getAllSKLahir');
            Route::get('laporan/get-detail-laporan/{norec}', 'getDetail');
            Route::get('laporan/cetak-laporan-lahir', 'cetakKelahiran');
            Route::get('laporan/cetak-laporan-lahir-klaim', 'cetakKelahiranKlaim');
            Route::get('laporan/get-pasien-istri', 'getPasienIstri');
            Route::get('laporan/get-jeniskelamin', 'getJenisKelamin');
            Route::get('laporan/check-is-baby', 'checkIsBaby');
            Route::get('laporan/cari-ibu', 'getPasienSKL');

            Route::post('laporan/create-laporan-lahir', 'createLaporanLahir');
            Route::post('laporan/update-laporan-lahir/{norec}', 'editLaporanLahir');
            Route::post('laporan/delete-laporan-lahir/{norec}', 'deleteLaporanLahir');
        });

        Route::controller(KartuStokCtrl::class)->group(function () {
            Route::get('logistik/kartu-stok-grid', 'getDataGrid');
            Route::get('logistik/penggunaan-obat-alkes', 'getPenggunaanObatAlkes');
            Route::get('logistik/penggunaan-alkes-floorstok', 'getPenggunaanAlkesFloorStok');
            Route::get('logistik/kartu-stok-cbo', 'getCombo');
            Route::get('logistik/list-produk', 'listProduk');
            Route::get('logistik/list-ruangan-ranap', 'getRuangan');
            Route::get('logistik/get-produk', 'getProduk');
            Route::get('logistik/laporan-fast-moving', 'getFastMoving');
            Route::get('logistik/laporan-slow-moving', 'getSlowMoving');
        });
        Route::controller(MonitoringBarangCtrl::class)->group(function () {
            Route::get('logistik/data-fast-moving', 'getDataFastMoving');
            Route::get('logistik/data-slow-moving', 'getDataSlowMoving');
            Route::get('logistik/data-dead-moving', 'getDataDeadMoving');
            Route::get('logistik/monitoring/combo', 'getCombo');
        });
        Route::controller(DistribusiBarangCtrl::class)->group(function () {
            Route::get('logistik/distribusi-barang-produk', 'getProduk');
            Route::get('logistik/distribusi-barang-cbo', 'getCombo');
            Route::get('logistik/distribusi-edit', 'getDetailKirim');
            // Route::get('logistik/distribusi-detail', 'getDetailKirimBarang');
            Route::get('logistik/distribusi-detail', 'getDetailKirimBarangNew');
            Route::post('logistik/chekstok-validasi', 'checkStok');
            Route::get('logistik/get-detail-kirim-order', 'getDetailOrderBarangForKirim');
            Route::get('logistik/daftar-distribusi', 'getDaftarDistribusiBarang');
            Route::get('logistik/daftar-retur-distribusi', 'getReturDistribusiBarang');
            Route::post('logistik/distribusi-barang-produk-save', 'saveKirimBarangRuangan');
            Route::post('logistik/retur-distribusi-barang-save', 'SaveReturDistribusi');
            Route::post('logistik/save-kirim-order-barang', 'saveKirimOrderBarang');
            Route::post('logistik/batal-kirim-verif-barang', 'batalVerifDanKirimBarang');
            Route::get('logistik/cetak-bukti-kirim', 'cetakBuktiKirim');
            Route::get('logistik/cetak-bukti-tarik', 'cetakBuktiTarik');
            Route::get('logistik/get-detail-kirim-order-barang', 'getDetailKirimOrderBarang');
            Route::post('logistik/distribusi-barang-floor-stok', 'saveKirimFloorStok');
            Route::post('logistik/distribusi-barang-penarikan-barang', 'savePenarikanBarang');
            Route::get('logistik/daftar-floorstok', 'getDaftarFloorStok');
            Route::get('logistik/daftar-penarikan', 'getPenarikanBarang');
        });
        Route::controller(PenerimaanBarangCtrl::class)->group(function () {
            Route::get('logistik/penerimaan-barang', 'getDaftarPenerimaanSuplier');
            Route::get('logistik/get-retur-barang-suplier', 'getDaftarReturPenerimaanSuplier');
            Route::get('logistik/get-detail-penerimaan', 'getDetailPenerimaanBarang');
            Route::get('logistik/penerimaan-barang-suplier', 'saveDataPenerimaanBarang');
            Route::get('logistik/penerimaan-barang/get-data-combo', 'getDataCombo');
            Route::get('logistik/penerimaan-barang/get-produkdetail', 'getHargaTerakhir');
            Route::get('logistik/penerimaan-barang/get-no-terima', 'getNoTerimaGenerate');
            Route::get('logistik/penerimaan-barang/get-produk-bykelompok', 'getDataProdukDetail');
            Route::post('logistik/penerimaan-barang/save-penerimaan-suplier', 'savePenerimaanBarangSuplier');
            Route::post('logistik/penerimaan-barang/edit-header-penerimaan-suplier', 'editHeaderPenerimaanSuplier');
            Route::post('logistik/penerimaan-barang/retur-penerimaan-suplier', 'SaveReturPenerimaan');
            Route::post('logistik/penerimaan-barang/delete-penerimaan-suplier', 'DeletePenerimaanBarangSupplier');
            Route::get('logistik/penerimaan-barang/get-data-produk-logistik', 'getDataProdukLogitik');
            Route::get('logistik/penerimaan-barang/get-rekanan', 'getRekanan');
            Route::get('logistik/penerimaan-barang/get-jabatan', 'getJabatan');
            Route::get('logistik/penerimaan-barang-retur', 'getDaftarRetur');
            Route::get('logistik/report/cetak-bukti-penerimaan-barang', 'cetakBuktiPenerimaanBarang');
        });

        // Route::controller(PurchaseRequestCtrl::class)->group(function () {
        //     Route::get('logistik/get-daftar-permintaaan-barang-ruangan', 'getDaftarPermintaanBarangRuangan');

        //     Route::post('logistik/save-usulan-permintaan','saveUsulanPermintaan');
        //     Route::post('logistik/batal-usulan-permintaan', 'batalPO');
        // });

        Route::controller(PurchaseOrderCtrl::class)->group(function () {
            Route::get('logistik/get-combo-barang-logistik', 'getDataProdukLogistik');
            Route::get('logistik/get-harga-produk', 'getHargaTerakhir');
            Route::get('logistik/get-daftar-usulan-permintaan', 'getDaftarUsulanPermintaan');
            Route::get('logistik/get-detail-data-po', 'getDataDetailPO');
            Route::get('logistik/get-detail-perencanaan', 'getDataDetailPerencanaan');
            Route::get('logistik/get-daftar-data-po', 'getDaftarUsulanPermintaanRuangan');
            Route::get('logistik/daftar-rencana-usulan-permintaan-barang-ruangan', 'getDaftarRencanaUsulanPermintaan');
            Route::get('logistik/get-daftar-permintaaan-barang-ruangan', 'getDaftarPermintaanBarangRuangan');
            Route::get('logistik/get-data-detail-rencana-usulan', 'getDetailRUPB');
            Route::get('logistik/get-data-detail-purchaserequest', 'getDataDetailPurchaseRequest');

            Route::post('logistik/save-usulan-permintaan', 'saveUsulanPermintaan');
            Route::post('logistik/batal-usulan-permintaan', 'batalPO');
            Route::post('logistik/batal-verif-request', 'batalVerif');
            Route::post('logistik/save-data-rencana-usulan', 'saveRencanaUsulanPermintaan');
            Route::post('logistik/save-data-usulan-permintaan-barang-ruangan', 'saveUsulanPermintaan2');
            Route::post('logistik/delete-data-usulan-ruangan', 'saveBatalUsulanPermintaanBarang');
        });

        Route::controller(PMKPCtrl::class)->group(function () {
            Route::get('pmkp/get-data-indikator-departemen', 'getDaftarIndikator');
            Route::get('pmkp/get-hasil-sensus-indikator', 'getHasilSensus');
            Route::get('pmkp/get-daftar-laporan-insiden-internal', 'GetDaftarLaporanInsidenInternal');
            Route::get('pmkp/get-daftar-lembar-investigasi-sederhana', 'GetDaftarLembarInvestigasiSederhana');
            Route::get('pmkp/get-daftar-sensus-keselamatan-pasien-bulanan', 'getLaporanSensusKeselamatanPasienBulanan');
            Route::get('pmkp/get-daftar-insiden-keselamatan-pasien', 'GetDaftarInsidenKeselamatanPasien');
            Route::get('pmkp/get-daftar-laporan-identifikasi-risiko', 'GetDaftarLaporanIdentifikasiRisiko');
            Route::get('pmkp/get-data-combo-pmkp', 'getDataCombo');
            Route::get('pmkp/get-laporan-kematian-pasien-igd', 'getLaporanKematianPasienIgd');
            Route::get('pmkp/get-laporan-dokter-pelayanan-poli', 'getDataLaporanDokterPelayananPoliklinik');
            Route::get('pmkp/get-laporan-dokter-pelayanan-ranap', 'getDataLaporanDokterPelayananRanap');
            Route::get('pmkp/get-laporan-dokter-penangung-jawab-ranap', 'getDataLaporanDokterPenanggungJawabRanap');
            Route::get('pmkp/get-laporan-visit-dokter-ranap', 'getLaporanJamVisiteDokter');
            Route::get('pmkp/get-laporan-kematian-pasien-ranap', 'getLaporanKematianPasienRanap');
            Route::get('pmkp/get-laporan-pasien-pulang-paksa', 'getLaporanPasienPulangPaksa');
            Route::get('pmkp/get-laporan-pasien-lama-dirawat', 'getLaporanLamaHariPerawatanPasien');
            Route::get('pmkp/get-laporan-pasien-lama-dirawat-gj', 'getLaporanLamaHariPerawatanPasienGangguanJiwa');
            Route::get('pmkp/get-daftar-penanganan-keluhan', 'getDaftarPenanganKeluhan');
            Route::get('pmkp/cetak-insiden-internal', 'cetakInsidenInternal');

            Route::post('pmkp/simpan-sensus-mutu', 'simpanSensusMutu');
            Route::post('pmkp/hapus-sensus-mutu', 'deleteLaporanInsidenInternal');
            Route::post('pmkp/simpan-laporan-insiden-internal', 'saveLaporanInsidenInternal');
            Route::post('pmkp/simpan-lembar-kerja-investigasi', 'saveLembarKerjaInvestigasi');
            Route::post('pmkp/hapus-lembar-kerja-investigasi', 'hapusDataLembarInvestigasi');
            Route::post('pmkp/save-insiden-keselamatan', 'saveInsidenKeselamatan');
            Route::post('pmkp/hapus-insiden-keselamatan', 'hapusInsidenKeselamatanPasien');
            Route::post('pmkp/save-identifikasi-resiko', 'saveIdentifikasiRisiko');
            Route::post('pmkp/hapus-identifikasi-resiko', 'hapusIdentifikasiResiko');
        });

        Route::controller(InputResepCtrl::class)->group(function () {
            Route::get('farmasi/input-resep-header', 'getHeader');
            Route::get('farmasi/input-resep-cbo', 'getCombo');
            Route::get('farmasi/input-resep-cbo-ruang', 'getComboRuang');
            Route::get('farmasi/input-resep-produk', 'getProduk');
            Route::get('farmasi/input-resep-cbo-order', 'getComboOrder');
            Route::get('farmasi/get-produkdetail', 'getProdukDetail');
            Route::get('farmasi/get-produkdetail-ceklis', 'getProdukDetailCeklis');
            Route::get('farmasi/get-produkdetail-ceklis2', 'getProdukDetailCeklis2');
            Route::get('farmasi/get-produkdetail-antibiotik', 'getProdukDetailAntibiotik');
            Route::get('farmasi/get-produkdetail-antibiotik-profilaksis', 'getProdukDetailAntibiotikProfilaksis');
            Route::get('farmasi/input-resep-edit', 'getDetailResep');
            Route::get('farmasi/detail-resep-retur', 'getDetailResepRetur');
            Route::get('farmasi/detail-resep-retur-perawat', 'fetchDetailReturPerawat');
            Route::get('farmasi/input-resep-order', 'getDetailOrder');
            Route::get('farmasi/dropdown-obat', 'dropdownObat');
            Route::get('farmasi/check-obat-periode', 'chekPeriodeObat');
            Route::get('farmasi/get-skrining-farmasi', 'getSkriningFarmasi');
            Route::get('farmasi/get-stok-produk-by-ruangan', 'getStokProduk');

            Route::post('farmasi/input-resep-save', 'simpanResep');
            Route::post('farmasi/input-resep-kronis-save', 'simpanResepKronis');
            Route::post('farmasi/save-retur-pelayanan', 'SimpanReturPelayananObat');
            Route::post('farmasi/save-retur-resep-dibayar-ranap', 'SimpanReturResepDibayarRanap');
            Route::post('farmasi/save-retur-pelayanan-ranap', 'SimpanReturPelayananObatRanap');
            Route::post('farmasi/save-retur-pelayanan-perawat', 'verificationReturPerawat');
            Route::post('farmasi/save-retur-resep-dibayar', 'SimpanReturResepDibayar');
            Route::post('farmasi/save-skrining-farmasi', 'saveSkriningFarmasi');
            Route::get('farmasi/report-kwitansi-pengembalian-obat-dibayar', 'cetakKwitansi');
        });
        Route::controller(PelayananObatBebasCtrl::class)->group(function () {
            Route::get('farmasi/get-daftar-jual-bebas', 'getDaftarPenjualanBebas');
            Route::get('farmasi/get-resep-bebas', 'getDaftarResepBebas');
            Route::get('farmasi/get-daftar-floor-stock', 'getDaftarFloorStock');
            Route::get('farmasi/get-daftar-floor-stock-history', 'getDaftarFloorStockHistory');
            Route::get('farmasi/get-pasien', 'getPasien');
            Route::get('farmasi/get-pasien-pj', 'getPasienPJ');
            Route::get('farmasi/get-detail-pasien', 'getDetailResepBebas');
            Route::get('farmasi/get-detail-pasien-pesanan', 'getDetailResepPesanan');
            Route::get('farmasi/daftar-resep-pesanan', 'getDaftarResepPesanan');
            Route::post('farmasi/save-input-non-layanan-obat', 'saveInputTagihanObat');
            Route::post('farmasi/save-input-non-layanan-pesanan', 'saveInputPesanan');
            Route::post('farmasi/save-stock-merger', 'stokMerger');
            Route::post('farmasi/delete-resep-bebas', 'deleteResepOB');
            Route::post('farmasi/save-retur-resep-bebas', 'saveReturTagihanObat');
        });
        Route::controller(DaftarPasienFarmasiCtrl::class)->group(function () {
            Route::get('farmasi/daftar-pasien-farmasi-grid', 'getDataGrid');
            Route::get('farmasi/daftar-permohonan', 'getDataPermohonan');
            Route::get('farmasi/daftar-pasien-ranap', 'getDataRanap');
            Route::get('farmasi/daftar-pasien-farmasi-cbo', 'getCombo');
            Route::get('farmasi/daftar-ruangan-cbo', 'listRuangan');
            Route::get('farmasi/daftar-resep-pasien', 'getDaftarResep');
            Route::get('farmasi/ruangan-depo', 'getRuanganDepo');
        });
        Route::controller(TransaksiPelayananFarmasiCtrl::class)->group(function () {
            Route::get('farmasi/transaksi-pelayanan-farmasi', 'transaksiPelayananFarmasi');
            Route::get('farmasi/transaksi-pelayanan-farmasi-modal', 'transaksiPelayananFarmasiModal');
            Route::get('farmasi/transaksi-pelayanan-farmasi-kronis', 'transaksiPelayananFarmasiKronis');

            Route::post('farmasi/transaksi-pelayanan-farmasi-hapus', 'transaksiPelayananFarmasiHapus');
            Route::post('farmasi/transaksi-pelayanan-farmasi-hapus-kronis', 'transaksiPelayananFarmasiHapusKronis');
            Route::post('farmasi/save-stock-split', 'saveStockSplit');
        });

        Route::controller(HumasCtrl::class)->group(function () {
            Route::get('humas/data-tempat-tidur', 'getDataViewBed');
            Route::get('humas/data-detail-tempat-tidur', 'getDetailBed');
            Route::get('humas/info-bed', 'infoBed');
            Route::get('humas/data-info-layanan', 'getInfoLayanan');
            Route::get('humas/combo-cari', 'getPilihan');
            Route::get('humas/get-pasien-teregistrasi', 'getDaftarRegistrasiPasien');
        });


        Route::controller(MasterRuanganCtrl::class)->group(function () {
            Route::get('sysadmin/master-ruangan', 'masterRuangan');
            Route::get('sysadmin/master-ruangan-dropdown', 'masterRuangandropdown');

            Route::post('sysadmin/save-master-ruangan', 'saveRuangan');
            Route::post('sysadmin/delete-master-ruangan', 'deleteRuangan');
            Route::post('sysadmin/detail-master-ruangan', 'detailRuangan');
            Route::post('sysadmin/update-master-ruangan', 'updateRuangan');
        });

        Route::controller(MasterDepartemenCtrl::class)->group(function () {
            Route::get('sysadmin/master-departemen', 'masterDepartemen');
            Route::get('sysadmin/master-departemen-dropdown', 'masterDepartemendropdown');

            Route::post('sysadmin/save-master-departemen', 'saveDepartemen');
            Route::post('sysadmin/delete-master-departemen', 'deleteDepartemen');
            Route::post('sysadmin/detail-master-departemen', 'detailDepartemen');
            Route::post('sysadmin/update-master-departemen', 'updatesaveDepartemen');
        });

        Route::controller(MasterKelompokPasienCtrl::class)->group(function () {
            Route::get('sysadmin/master-kelompok-pasien', 'masterKelompokPasien');
            Route::get('sysadmin/master-kelompok-pasien-dropdown', 'masterKelompokPasiendropdown');

            Route::post('sysadmin/save-master-kelompok-pasien', 'saveKelompokPasien');
            Route::post('sysadmin/delete-master-kelompok-pasien', 'deleteKelompokPasien');
        });

        Route::controller(MasterPersenHargaJualProduk::class)->group(function () {
            Route::get('sysadmin/get-combo-persen-harga-jual', 'getComboPersenHargaJual');
            Route::get('sysadmin/get-range-harga', 'getRangePersen');
            Route::get('sysadmin/get-data-persen-harga-jual', 'getData');
            Route::get('sysadmin/get-data-jenis-transaksi', 'getDataJenisTransaksi');
            Route::get('sysadmin/get-data-sistem-harga', 'getDataSistemHarga');

            Route::post('sysadmin/simpan-data-persen-harga-jual', 'simpanData');
            Route::post('sysadmin/delete-data-persen-harga-jual', 'delete');
            Route::post('sysadmin/simpan-jenis-transaksi', 'simpanJenisTransaksi');
        });

        Route::controller(MasterRekananCtrl::class)->group(function () {
            Route::get('sysadmin/master-rekanan', 'masterRekanan');
            Route::get('sysadmin/master-rekanan-dropdown', 'masterRekanandropdown');
            Route::get('sysadmin/desa-kelurahan-paging', 'listDesaKelurahanPaging');
            Route::get('sysadmin/kecamatan-paging', 'listKecamatanPaging');
            Route::get('sysadmin/kotakabupaten', 'listKotaKab');
            Route::get('sysadmin/kecamatan', 'listKecamatan');
            Route::get('sysadmin/desakelurahan', 'listDesa');

            Route::post('sysadmin/save-master-rekanan', 'saveRekanan');
            Route::post('sysadmin/delete-master-rekanan', 'deleteRekanan');
            Route::post('sysadmin/detail-master-rekanan', 'detailRekanan');
        });

        Route::controller(MasterMapKelompokCtrl::class)->group(function () {
            Route::get('sysadmin/master-map-kelompok', 'masterMapKelompok');
            Route::get('sysadmin/master-map-kelompok-dropdown', 'masterMapKelompokdropdown');

            Route::post('sysadmin/save-map-kelompok', 'saveMapKelompok');
            Route::post('sysadmin/delete-map-kelompok', 'deleteMapKelompok');
            Route::post('sysadmin/detail-map-kelompok', 'detailMapKelompok');
        });

        Route::controller(PotonganRemunCtrl::class)->group(function () {
            Route::get('sysadmin/get-data-potongan-remun', 'getData');

            Route::post('sysadmin/save-potongan-remun', 'savePotonganRemun');
            Route::post('sysadmin/delete-potongan-remun', 'deletePotongan');
        });

        Route::controller(MasterProfileCtrl::class)->group(function () {
            Route::get('sysadmin/get-profile-dropdown', 'dropDown');
            Route::get('sysadmin/get-profile', 'index');

            Route::post('sysadmin/simpan-profile', 'saveProfile');
            // Route::post('sysadmin/delete-potongan-remun', 'deletePotongan');
        });

        Route::controller(MasterMapDepoToRuanganCtrl::class)->group(function () {
            Route::get('sysadmin/master-map-depo-to-ruangan', 'masterMapDepoToRuangan');
            Route::get('sysadmin/master-map-depo-to-ruangan-dropdown', 'masterMapDepoToRuangandropdown');

            Route::post('sysadmin/save-map-depo-to-ruangan', 'saveMapDepoToRuangan');
            Route::post('sysadmin/delete-map-depo-to-ruangan', 'deleteMapDepoToRuangan');
        });
        Route::controller(MapJenisPaguToPegawaiCtrl::class)->group(function () {
            Route::get('sysadmin/get-mapping-pagu-to-pegawai', 'getMappingPaguToPegawai');
            // Route::get('sysadmin/master-map-depo-to-ruangan-dropdown', 'masterMapDepoToRuangandropdown');

            Route::post('sysadmin/save-map-pagu-to-pegawai', 'saveMapPaguToPegawai');
            // Route::post('sysadmin/delete-map-depo-to-ruangan', 'deleteMapDepoToRuangan');
        });
        Route::controller(MapKelompokPenghasilCtrl::class)->group(function () {
            Route::get('sysadmin/get-mapping-remun-kelompok', 'getMapRemunKelompok');
            Route::get('sysadmin/get-data-by-date', 'getDataByDate');

            Route::post('sysadmin/save-map-remun-kelompok', 'saveMapRemunKelompok');
            Route::post('sysadmin/delete-map-remun-kelompok', 'deleteMapRemunKelompok');
        });

        Route::controller(MapKelompokLaporanCtrl::class)->group(function () {
            Route::get('sysadmin/get-combo-map-laporan', 'getCombo');
            Route::get('sysadmin/get-map-laporan-rl', 'getMapLaporanRL');

            Route::post('sysadmin/save-map-laporan-rl', 'SaveMappingRl');
            Route::post('sysadmin/delete-map-laporan-rl', 'deleteMap');
        });

        Route::controller(GeneralCtrl::class)->group(function () {
            Route::get('general/pasien-registrasi', 'pasienRegistrasiSearching');
            Route::get('general/dokter-paging', 'listDokterPaging');
            Route::get('general/header-pasien', 'headerPasien');
            Route::get('general/header-pasien-first', 'headerPasienFirst');
            Route::get('general/show-file', 'showFileGeneral');
            Route::get('general/jenis-operasi', 'jenisoperasi');
            Route::get('general/jenis-ksm', 'jenisksm');
            Route::get('general/jenis-tindakan', 'jenistindakan');
            Route::get('general/jenis-operasi-details', 'operasiDetail');
            Route::get('general/ppk-bpjs', 'settingPPKBPJS');
            Route::get('general/template-expertise', 'getTemplateExpertice');
            Route::get('general/printer', 'masterPrinter');
            Route::get('general/list-log-user', 'getLogUser');
            Route::get('general/get-status-close', 'getStatusClosePeriksa');
            Route::get('general/get-penunjang-close', 'getPenjunjangClosing');
            Route::get('general/device-name', function () {
                $response = array(
                    'metaData' => array(
                        "code" => 200,
                        "message" => 'Sukses',
                    ),
                    'response' => gethostname(),
                );
                return $response;
            });
            Route::get('/jumlah', 'getTerbilang');


            Route::post('general/api-tools', 'apiTOOLS');
            Route::post('general/save-surat-keterangan-kematian', 'SaveSuratKeteranganKematian');
            Route::post('general/save-surat-keterangan-meninggal', 'SaveSuratKeteranganMeninggal');
            Route::post('general/save-printer', 'savePrinter');
            Route::post('general/delete-printer', 'deletePrinter');
            Route::post('general/store-notif', 'storeNotif');

            Route::post('general/save-jurnal-pelayananpasien_t', 'PostingJurnal_pelayananpasien_t');
            Route::post('general/save-jurnal-pelayananpasien_t-noreg', 'PostingJurnal_pelayananpasien_t_NoRegistrasi');
            Route::post('general/save-jurnal-pembayaran_tagihan', 'PostingJurnal_pembayaran_tagihan');
            Route::post('general/save-jurnal-verifikasi_tarek', 'PostingJurnal_strukpelayanan_t_verifikasi_tarek');
            Route::post('general/save-jurnal-penerimaan-barang', 'PostingJurnal_terimabarang');
            Route::post('general/save-jurnal-beban_pelayanan', 'PostingJurnal_bebanpelayananpasien');
            Route::post('general/save-jurnal-pelayananpasien_tob', 'PostingJurnal_pelayananpasien_tob');
            Route::post('general/save-jurnal-amprahan-barang-all', 'PostingJurnal_amprahanForDaftar');
            Route::post('general/hapus-jurnal-amprahan-barang', 'PostingHapusJurnal_BatalKirim');
            Route::post('akuntansi/post-jurnal-pembayaran-piutang-rekanan', 'PostingJurnal_penerimaan_piutang');
            Route::post('akuntansi/postingjurnal-pemakaian-deposit', 'PostingJurnal_Pemakaiandeposit');
            Route::post('general/save-jurnal-cash-flow', 'PostingJurnal_cashflow_statement');
        });
        Route::controller(MasterKelompokProdukCtrl::class)->group(function () {
            Route::get('sysadmin/master-kelompok-produk', 'masterKelompokProduk');
            Route::get('sysadmin/master-kelompok-produk-dropdown', 'masterKelompokProdukdropdown');

            Route::post('sysadmin/save-master-kelompok-produk', 'saveKelompokProduk');
            Route::post('sysadmin/delete-kelompok-produk', 'deleteKelompokProduk');
        });
        Route::controller(MasterJenisProdukCtrl::class)->group(function () {
            Route::get('sysadmin/master-jenis-produk', 'masterJenisProduk');
            Route::get('sysadmin/master-jenis-produk-dropdown', 'masterJenisProdukdropdown');

            Route::post('sysadmin/save-master-jenis-produk', 'saveJenisProduk');
            Route::post('sysadmin/delete-jenis-produk', 'deleteJenisProduk');
            Route::post('sysadmin/detail-jenis-produk', 'detailJenisProduk');
        });
        Route::controller(MasterDetailJenisProdukCtrl::class)->group(function () {
            Route::get('sysadmin/master-detail-jenis-produk', 'masterDetailJenisProduk');
            Route::get('sysadmin/master-detail-jenis-produk-dropdown', 'masterDetailJenisProdukdropdown');
            Route::post('sysadmin/save-master-detail-jenis-produk', 'saveDetailJenisProduk');
            Route::post('sysadmin/delete-detail-jenis-produk', 'deleteDetailJenisProduk');
            Route::post('sysadmin/detail-jenis-produk', 'detailJenisProduk');
        });

        Route::controller(MasterAstorBMCtrl::class)->group(function () {
            Route::get('sysadmin/master-detail-jenis-generik', 'masterDataGenerik');
            Route::get('sysadmin/master-detail-divisi', 'masterDivisiPPRA');
            Route::get('sysadmin/master-detail-operasi', 'ListOperasiProfilaksis');
            Route::get('sysadmin/master-detail-tindakan-empiris', 'ListTindakanEmpiris');
            Route::get('sysadmin/master-ppra-generik', 'masterPPRAGenerik');
            Route::post('sysadmin/save-master-generik-new', 'saveMasterGenerik');
            Route::post('sysadmin/delete-master-generik-new', 'deleteMasterGenerik');
            Route::get('sysadmin/master-ppra-divisi', 'masterPPRADivisi');
            Route::post('sysadmin/save-master-ppra-divisi', 'saveMasterPPRAGenerik');
            Route::post('sysadmin/delete-master-ppra-divisi', 'deleteMasterPPRADivisi');
            Route::get('sysadmin/master-operasi-profilaksis', 'masterOperasiProfilaksis');
            Route::post('sysadmin/save-master-operasi-profilaksis', 'saveMasterOperasiProfilaksis');
            Route::post('sysadmin/delete-master-operasi-profilaksis', 'deleteMasterOperasiProfilaksis');
            Route::get('sysadmin/master-data-profilaksis', 'masterDataProfilaksis');
            Route::post('sysadmin/save-master-data-profilaksis', 'saveMasterDataOperasiProfilaksis');
            Route::post('sysadmin/delete-master-data-profilaksis', 'deleteMasterDataOperasiProfilaksis');
            Route::get('sysadmin/master-tindakan-empiris', 'masterTindakanEmpiris');
            Route::post('sysadmin/save-master-tindakan-empiris', 'saveMasterTindakanEmpiris');
            Route::post('sysadmin/delete-master-tindakan-empiris', 'deleteMasterTindakanEmpiris');
            Route::get('sysadmin/master-data-empiris', 'masterDataEmpiris');
            Route::post('sysadmin/save-master-data-empiris', 'saveMasterDataTindakanEmpiris');
            Route::post('sysadmin/delete-master-data-empiris', 'deleteMasterDataTindakanEmpiris');
        });

        Route::controller(MasterProdukCtrl::class)->group(function () {
            Route::get('sysadmin/master-produk', 'masterProduk');
            Route::get('sysadmin/master-produk-dropdown', 'masterProdukdropdown');
            Route::get('sysadmin/produk-grid', 'getDataGrid');
            Route::get('sysadmin/produk-cbo', 'getCombo');
            Route::get('sysadmin/jenis-produk', 'listJenisProduk');
            Route::get('sysadmin/detail-jenis-produk', 'listDetailJenisProduk');
            Route::get('sysadmin/master-produk-satset', 'masterProdukSatset');

            Route::post('sysadmin/save-master-produk', 'saveProduk');
            Route::post('sysadmin/delete-master-produk', 'deleteProduk');
            Route::post('sysadmin/detail-produk', 'detailProduk');
            Route::post('sysadmin/update-produk', 'updateProduk');
        });
        Route::controller(ProduksiObatCtrl::class)->group(function () {
            Route::get('sysadmin/master-produksi-obat', 'getDataMasterBarangProduksi');
            Route::get('sysadmin/produksi-obat/combo', 'comboProduksiObat');
            Route::get('sysadmin/get-detail-produksi-obat', 'getDetailMasterProduksi');
            Route::get('sysadmin/get-daftar-produksi-obat-non-steril', 'getDaftarProduksiObat');

            Route::post('sysadmin/save-produksi-obat', 'saveMasterProdukFormulaProduksi');
            Route::post('sysadmin/save-produksi-obat-non-steril', 'saveProduksiObatNonSteril');
            Route::post('sysadmin/delete-produksi-obat', 'DeleteMasterProduksi');
            Route::post('sysadmin/delete-obat-produksi-non-steril', 'hapusObatProduksi');
        });

        Route::controller(MasterSatuanStandarCtrl::class)->group(function () {
            Route::get('sysadmin/master-satuan-standar', 'masterSatuanStandar');
            Route::get('sysadmin/master-satuan-standar-dropdown', 'masterSatuanStandardropdown');

            Route::post('sysadmin/save-satuan-standar', 'saveSatuanStandar');
            Route::post('sysadmin/delete-satuan-standar', 'deleteSatuanStandar');
            Route::post('sysadmin/detail-satuan-standar', 'detailSatuanStandar');
        });

        Route::controller(MasterKelompokTransaksiCtrl::class)->group(function () {
            Route::get('sysadmin/master-kelompok-transaksi', 'masterKelompokTransaksi');
            Route::get('sysadmin/master-kelompok-transaksi-dropdown', 'masterKelompokTransaksidropdown');

            Route::post('sysadmin/save-kelompok-transaksi', 'saveKelompokTransaksi');
            Route::post('sysadmin/delete-kelompok-transaksi', 'deleteKelompokTransaksi');
            Route::post('sysadmin/detail-kelompok-transaksi', 'detailKelompokTransaksi');
        });
        Route::controller(MasterAsalProdukCtrl::class)->group(function () {
            Route::get('sysadmin/master-asal-produk', 'masterAsalProduk');
            Route::get('sysadmin/master-asal-produk-dropdown', 'masterAsalProdukdropdown');

            Route::post('sysadmin/save-asal-produk', 'saveAsalProduk');
            Route::post('sysadmin/delete-asal-produk', 'deleteAsalProduk');
            Route::post('sysadmin/detail-asal-produk', 'detailAsalProduk');
        });
        Route::controller(MasterAgamaCtrl::class)->group(function () {
            Route::get('sysadmin/master-agama', 'masterAgama');

            Route::post('sysadmin/save-agama', 'saveAgama');
            Route::post('sysadmin/delete-agama', 'deleteAgama');
            Route::post('sysadmin/detail-agama', 'detailAgama');
        });
        Route::controller(MasterJenisKelaminCtrl::class)->group(function () {
            Route::get('sysadmin/master-jenis-kelamin', 'masterJenisKelamin');

            Route::post('sysadmin/save-jenis-kelamin', 'saveJenisKelamin');
            Route::post('sysadmin/delete-jenis-kelamin', 'deleteJenisKelamin');
            Route::post('sysadmin/detail-jenis-kelamin', 'detailJenisKelamin');
        });
        Route::controller(MasterStatusPerkawinanCtrl::class)->group(function () {
            Route::get('sysadmin/master-status-perkawinan', 'masterStatusPerkawinan');

            Route::post('sysadmin/save-status-perkawinan', 'saveStatusPerkawinan');
            Route::post('sysadmin/delete-status-perkawinan', 'deleteStatusPerkawinan');
        });
        Route::controller(MasterPendidikanCtrl::class)->group(function () {
            Route::get('sysadmin/master-pendidikan', 'masterPendidikan');
            Route::get('sysadmin/master-pendidikan-dropdown', 'masterPendidikandropdown');

            Route::post('sysadmin/save-pendidikan', 'savePendidikan');
            Route::post('sysadmin/delete-pendidikan', 'deletePendidikan');
        });
        Route::controller(MasterPekerjaanCtrl::class)->group(function () {
            Route::get('sysadmin/master-pekerjaan', 'masterPekerjaan');
            Route::get('sysadmin/master-pekerjaan-dropdown', 'masterPekerjaandropdown');

            Route::post('sysadmin/save-pekerjaan', 'savePekerjaan');
            Route::post('sysadmin/delete-pekerjaan', 'deletePekerjaan');
        });
        Route::controller(MasterGolonganDarahCtrl::class)->group(function () {
            Route::get('sysadmin/master-golongan-darah', 'masterGolonganDarah');

            Route::post('sysadmin/save-golongan-darah', 'saveGolonganDarah');
            Route::post('sysadmin/delete-golongan-darah', 'deleteGolonganDarah');
        });
        Route::controller(MasterSatuanResepCtrl::class)->group(function () {
            Route::get('sysadmin/master-satuan-resep', 'masterSatuanResep');

            Route::post('sysadmin/save-satuan-resep', 'saveSatuanResep');
            Route::post('sysadmin/delete-satuan-resep', 'deleteSatuanResep');
        });
        Route::controller(MasterNegaraCtrl::class)->group(function () {
            Route::get('sysadmin/master-negara', 'masterNegara');

            Route::post('sysadmin/save-negara', 'saveNegara');
            Route::post('sysadmin/delete-negara', 'deleteNegara');
        });
        Route::controller(MasterSignaCtrl::class)->group(function () {
            Route::get('sysadmin/master-signa', 'masterSigna');

            Route::post('sysadmin/save-signa', 'saveSigna');
            Route::post('sysadmin/delete-signa', 'deleteSigna');
        });
        Route::controller(MasterJenisLaporanCtrl::class)->group(function () {
            Route::get('sysadmin/master-jenis-laporan', 'masterJenisLaporan');

            Route::post('sysadmin/save-jenis-laporan', 'saveJenisLaporan');
            Route::post('sysadmin/delete-jenis-laporan', 'deleteJenisLaporan');
        });
        Route::controller(MasterRouteFarmasiCtrl::class)->group(function () {
            Route::get('sysadmin/master-route-farmasi', 'masterRouteFarmasi');

            Route::post('sysadmin/save-route-farmasi', 'saveRouteFarmasi');
            Route::post('sysadmin/delete-route-farmasi', 'deleteRouteFarmasi');
        });
        Route::controller(MasterSukuCtrl::class)->group(function () {
            Route::get('sysadmin/master-suku', 'masterSuku');

            Route::post('sysadmin/save-suku', 'saveSuku');
            Route::post('sysadmin/delete-suku', 'deleteSuku');
        });
        Route::controller(MasterProvinsiCtrl::class)->group(function () {
            Route::get('sysadmin/master-provinsi', 'masterProvinsi');

            Route::post('sysadmin/save-provinsi', 'saveProvinsi');
            Route::post('sysadmin/delete-provinsi', 'deleteProvinsi');
        });
        Route::controller(MasterKotaKabupatenCtrl::class)->group(function () {
            Route::get('sysadmin/master-kota-kabupaten', 'masterKotaKabupaten');
            Route::get('sysadmin/master-kota-kabupaten-dropdown', 'masterKotaKabupatendropdown');

            Route::post('sysadmin/save-kota-kabupaten', 'saveKotaKabupaten');
            Route::post('sysadmin/delete-kota-kabupaten', 'deleteKotaKabupaten');
        });
        Route::controller(MasterKecamatanCtrl::class)->group(function () {
            Route::get('sysadmin/master-kecamatan', 'masterKecamatan');
            Route::get('sysadmin/master-kecamatan-dropdown', 'masterKecamatandropdown');

            Route::post('sysadmin/save-kecamatan', 'saveKecamatan');
            Route::post('sysadmin/delete-kecamatan', 'deleteKecamatan');
        });
        Route::controller(MasterHargaNettoProdukByKelasCtrl::class)->group(function () {
            Route::get('sysadmin/master-harga-netto-produk-by-kelas', 'masterHargaNettoProdukByKelas');
            Route::get('sysadmin/master-harga-netto-produk-by-kelas/{id}', 'masterHargaNettoProdukByKelasEdit');
            Route::get('sysadmin/master-harga-netto-produk-by-kelas-dropdown', 'masterHargaNettoProdukByKelasdropdown');
            Route::get('sysadmin/master-harga-netto-produk-by-kelas-dropdown-import', 'masterHargaNettoProdukByKelasdropdownImport');
            Route::get('sysadmin/master-harga-netto-produk-by-kelas-download', 'downloadTemplate');

            Route::post('sysadmin/save-harga-netto-produk-by-kelas', 'saveHargaNettoProdukByKelas');
            Route::post('sysadmin/add-harga-netto-produk-by-kelas', 'saveKomponenHarga');
            Route::post('sysadmin/delete-harga-netto-produk-by-kelas', 'deleteHargaNettoProdukByKelas');
            Route::post('sysadmin/import-harga-netto-produk-by-kelas', 'importTarif');
        });
        Route::controller(MasterMapRuanganToProdukCtrl::class)->group(function () {
            Route::get('sysadmin/master-map-ruangan-to-produk', 'masterMapRuanganToProduk');
            Route::get('sysadmin/master-map-ruangan-to-produk-dropdown', 'masterMapRuanganToProdukdropdown');
            Route::get('sysadmin/master-map-ruangan-to-produk-dropdown-produk', 'masterMapRuanganToProdukdropdownProduk');
            Route::get('sysadmin/ruangan', 'listRuangan');
            Route::get('sysadmin/kelompok-produk', 'listKelompokProduk');
            Route::get('sysadmin/jenis-produk-', 'listJenisProduk');
            Route::get('sysadmin/detail-jenis-produk-', 'listDetailJenisProduk');
            Route::get('sysadmin/list-produk', 'listProduk');

            Route::post('sysadmin/save-map-ruangan-to-produk', 'saveMapRuanganToProduk');
            Route::post('sysadmin/delete-map-ruangan-to-produk', 'deleteMapRuanganToProduk');
            Route::post('sysadmin/detail-map-ruangan-to-produk', 'detailMapRuanganToProduk');
        });
        Route::controller(MasterPaketCtrl::class)->group(function () {
            Route::get('sysadmin/master-paket', 'masterPaket');
            Route::get('sysadmin/master-paket-dropdown', 'masterPaketdropdown');

            Route::post('sysadmin/save-master-paket', 'savePaket');
            Route::post('sysadmin/delete-master-paket', 'deletePaket');
        });
        Route::controller(MasterKelasCtrl::class)->group(function () {
            Route::get('sysadmin/master-kelas', 'masterKelas');

            Route::post('sysadmin/save-master-kelas', 'saveKelas');
            Route::post('sysadmin/delete-master-kelas', 'deleteKelas');
        });
        Route::controller(MasterMapRuanganToKelasCtrl::class)->group(function () {
            Route::get('sysadmin/master-map-ruangan-to-kelas', 'masterMapRuanganToKelas');
            Route::get('sysadmin/master-map-ruangan-to-kelas-dropdown', 'masterMapRuanganToKelasdropdown');
            Route::get('sysadmin/ruangan', 'listRuangan');

            Route::post('sysadmin/save-map-ruangan-to-kelas', 'saveMapRuanganToKelas');
            Route::post('sysadmin/delete-map-ruangan-to-kelas', 'deleteMapRuanganToKelas');
            Route::post('sysadmin/detail-map-ruangan-to-kelas', 'detailMapRuanganToKelas');
        });
        Route::controller(MasterMapPaketToProdukCtrl::class)->group(function () {
            Route::get('sysadmin/master-map-paket-to-produk', 'masterMapPaketToProduk');
            Route::get('sysadmin/master-map-paket-to-produk-dropdown', 'masterMapPaketToProdukdropdown');
            Route::get('sysadmin/master-map-paket-status', 'getMapPaketToProdukStatusPending');
            Route::get('sysadmin/master-map-paket-by-id', 'getMapPaketToProdukByID');
            Route::post('sysadmin/save-map-paket-to-produk-temporary', 'saveMapPaketToProdukTemporary');

            Route::post('sysadmin/save-map-paket-to-produk', 'saveMapPaketToProduk');
            Route::post('sysadmin/delete-map-paket-to-produk', 'deleteMapPaketToProduk');
            Route::post('sysadmin/detail-map-paket-to-produk', 'detailMapPaketToProduk');
        });
        Route::controller(MasterJabatanCtrl::class)->group(function () {
            Route::get('sysadmin/master-jabatan', 'masterJabatan');
            Route::get('sysadmin/master-jabatan-dropdown', 'masterJabatandropdown');

            Route::post('sysadmin/save-master-jabatan', 'saveJabatan');
            Route::post('sysadmin/delete-master-jabatan', 'deleteJabatan');
        });
        Route::controller(MasterKelompokJabatanCtrl::class)->group(function () {
            Route::get('sysadmin/master-kelompok-jabatan', 'masterKelompokJabatan');
            Route::get('sysadmin/master-kelompok-jabatan-dropdown', 'masterKelompokJabatandropdown');

            Route::post('sysadmin/save-master-kelompok-jabatan', 'saveKelompokJabatan');
            Route::post('sysadmin/delete-master-kelompok-jabatan', 'deleteKelompokJabatan');
        });
        Route::controller(MasterJenisJabatanCtrl::class)->group(function () {
            Route::get('sysadmin/master-jenis-jabatan', 'masterJenisJabatan');
            Route::get('sysadmin/master-jenis-jabatan-dropdown', 'masterJenisJabatandropdown');

            Route::post('sysadmin/save-master-jenis-jabatan', 'saveJenisJabatan');
            Route::post('sysadmin/delete-master-jenis-jabatan', 'deleteJenisJabatan');
        });
        Route::controller(MasterAsalRujukanCtrl::class)->group(function () {
            Route::get('sysadmin/master-asal-rujukan', 'masterAsalRujukan');

            Route::post('sysadmin/save-asal-rujukan', 'saveAsalRujukan');
            Route::post('sysadmin/delete-asal-rujukan', 'deleteAsalRujukan');
        });
        Route::controller(MasterSlottingOnlineCtrl::class)->group(function () {
            Route::get('sysadmin/master-slotting-online', 'masterSlottingOnline');
            Route::get('sysadmin/master-slotting-online-dropdown', 'masterSlottingOnlinedropdown');

            Route::post('sysadmin/save-slotting-online', 'saveSlottingOnline');
            Route::post('sysadmin/delete-slotting-online', 'deleteSlottingOnline');
        });
        Route::controller(MasterKelompokUserCtrl::class)->group(function () {
            Route::get('sysadmin/master-kelompok-user', 'masterKelompokUser');

            Route::post('sysadmin/save-master-kelompok-user', 'saveKelompokUser');
            Route::post('sysadmin/delete-master-kelompok-user', 'deleteKelompokUser');
        });
        Route::controller(MasterJenisPetugasPelaksanaCtrl::class)->group(function () {
            Route::get('sysadmin/master-jenis-petugas-pelaksana', 'masterJenisPetugasPelaksana');

            Route::post('sysadmin/save-jenis-petugas-pelaksana', 'saveJenisPetugasPelaksana');
            Route::post('sysadmin/delete-jenis-petugas-pelaksana', 'deleteJenisPetugasPelaksana');
        });
        Route::controller(MasterPegawaiCtrl::class)->group(function () {
            Route::get('sysadmin/master-pegawai', 'masterPegawai');
            Route::get('sysadmin/master-pegawai-d3', 'masterPegawaiD3');
            Route::get('sysadmin/master-pegawai-dropdown', 'masterPegawaidropdown');
            Route::get('sysadmin/pegawai', 'pegawaiByID');
            Route::get('sysadmin/pegawai-by-id', 'pegawaiByID');
            Route::get('sysadmin/jadwal-kerja', 'jadwalKerja');
            Route::get('sysadmin/master-pegawai-satset', 'masterPegawaiSatset');

            Route::post('sysadmin/save-pegawai', 'savePegawai');
            Route::post('sysadmin/delete-pegawai', 'deletePegawai');
            Route::post('sysadmin/update-pegawai', 'updatePegawai');
        });
        Route::controller(MasterJenisPegawaiCtrl::class)->group(function () {
            Route::get('sysadmin/master-jenis-pegawai', 'masterJenisPegawai');
            Route::get('sysadmin/master-jenis-pegawai-dropdown', 'masterJenisPegawaidropdown');

            Route::post('sysadmin/save-jenis-pegawai', 'saveJenisPegawai');
            Route::post('sysadmin/delete-jenis-pegawai', 'deleteJenisPegawai');
        });
        Route::controller(MasterMapJenisPetugasToJenisPegawaiCtrl::class)->group(function () {
            Route::get('sysadmin/master-map-jenis-petugas-to-jenis-pegawai', 'masterMapJenisPetugasToJenisPegawai');
            Route::get('sysadmin/master-map-jenis-petugas-to-jenis-pegawai-dropdown', 'masterMapJenisPetugasToJenisPegawaiDropdown');

            Route::post('sysadmin/save-map-jenis-petugas-to-jenis-pegawai', 'saveMapJenisPetugasToJenisPegawai');
            Route::post('sysadmin/delete-map-jenis-petugas-to-jenis-pegawai', 'deleteMapJenisPetugasToJenisPegawai');
        });
        Route::controller(MasterDetailKategoryPegawaiCtrl::class)->group(function () {
            Route::get('sysadmin/master-detail-kategory-pegawai', 'masterDetailKategoriPegawai');
            Route::get('sysadmin/master-detail-kategory-pegawai-dropdown', 'masterDetailKategoryPegawaidropdown');

            Route::post('sysadmin/save-detail-kategory-pegawai', 'saveDetailKategoryPegawai');
            Route::post('sysadmin/delete-detail-kategory-pegawai', 'deleteDetailKategoryPegawai');
        });
        Route::controller(MasterJadwalDokterCtrl::class)->group(function () {
            Route::get('sysadmin/master-jadwal-dokter', 'masterJadwalDokter');
            Route::get('sysadmin/master-jadwal-dokter-dropdown', 'masterJadwalDokterDropdown');

            Route::post('sysadmin/save-jadwal-dokter', 'saveJadwalDokter');
            Route::post('sysadmin/save-bulk-jadwal-dokter', 'saveBulkJadwalDokter');
            Route::post('sysadmin/update-bulk-jadwal-dokter', 'updateBulkJadwalDokter');
            Route::post('sysadmin/delete-jadwal-dokter', 'deleteJadwalDokter');
        });
        Route::controller(MasterTambahLoginUserCtrl::class)->group(function () {
            Route::get('sysadmin/master-tambah-login-user', 'masterTambahLoginUser');
            Route::get('sysadmin/master-tambah-login-user-dropdown', 'masterTambahLoginUserDropdown');

            Route::post('sysadmin/save-login-user', 'saveLoginUser');
            Route::post('sysadmin/delete-login-user', 'deleteLoginUser');
        });
        Route::controller(MasterModulAplikasiCtrl::class)->group(function () {
            Route::get('sysadmin/master-modul-aplikasi', 'masterModulAplikasi');
            Route::get('sysadmin/master-objek-modul-aplikasi', 'masterObjekModulAplikasi');
            Route::get('sysadmin/master-modul-aplikasi-by-head', 'masterModulAplikasiHead');
            Route::get('sysadmin/master-menu-modul-aplikasi', 'masterMenuObjek');
            Route::get('sysadmin/master-modul-aplikasi-nourut', 'lastNourutObjekModul');


            Route::post('sysadmin/save-modul-aplikasi', 'saveModulAplikasi');
            Route::post('sysadmin/delete-modul-aplikasi', 'deleteModulAplikasi');
            Route::post('sysadmin/save-objek-modul-aplikasi', 'saveObjekModulAplikasi');
            Route::post('sysadmin/delete-objek-modul-aplikasi', 'deleteObjekModulAplikasi');
            Route::post('sysadmin/save-objek-modul-aplikasi-map', 'saveObjekModulAplikasiMap');
            Route::post('sysadmin/hapus-objek-modul-aplikasi-map', 'hapusObjekModulAplikasiMap');
        });
        Route::controller(MasterMapLoginUserToModulAplikasiCtrl::class)->group(function () {
            Route::get('sysadmin/master-map-loginuser-to-modulaplikasi', 'masterMapLoginUserToModulAplikasi');
            Route::get('sysadmin/master-map-loginuser-to-modulaplikasi-dropdown', 'masterMapLoginUserToModulAplikasidropdown');

            Route::post('sysadmin/save-map-loginuser-to-modulaplikasi', 'saveMapLoginUserToModulAplikasi');
            Route::post('sysadmin/delete-map-loginuser-to-modulaplikasi', 'deleteMapLoginUserToModulAplikasi');
        });
        Route::controller(MasterMapLoginUserToRuanganCtrl::class)->group(function () {
            Route::get('sysadmin/master-map-loginuser-to-ruangan', 'masterMapLoginUserToRuangan');
            Route::get('sysadmin/master-map-loginuser-to-ruangan-dropdown', 'masterMapLoginUserToRuangandropdown');

            Route::post('sysadmin/save-map-loginuser-to-ruangan', 'saveMapLoginUserToRuangan');
            Route::post('sysadmin/delete-map-loginuser-to-ruangan', 'deleteMapLoginUserToRuangan');
        });
        Route::controller(SettingDataFixedCtrl::class)->group(function () {
            Route::get('sysadmin/get-settingdatafixed', 'getDataFixed');
            Route::get('sysadmin/get-settingdatafixedbyid', 'getSettingById');
            Route::get('sysadmin/update-status-enabled', 'updateStatuEnabled');
            Route::get('sysadmin/get-kelompok-setting', 'getKelompokSettingDataFix');
            Route::get('sysadmin/get-setting-detail', 'getSettingDetail');
            Route::get('sysadmin/get-setting-combo', 'getComboPart');
            Route::get('sysadmin/get-table', 'getTable');
            Route::get('sysadmin/get-field-table', 'getFieldTable');
            Route::get('sysadmin/get-data-from-table', 'getDataFromTable');
            Route::get('sysadmin/get-report-display', 'getReportDisplayTable');
            Route::get('sysadmin/get/{namaField}', 'getSettingDataFixedGeneric');

            Route::post('sysadmin/post-settingdatafixe', 'SaveSettingDataFixed');
            Route::post('sysadmin/hapus-settingdatafixe', 'HapusSettingDataFixed');
            Route::post('sysadmin/tambah-settingdatafixe', 'TambahSettingDataFixed');
            Route::post('sysadmin/delete-settingdatafixed', 'deleteSettingDataFix');
            Route::post('sysadmin/update-setting', 'updateSettingDataFix');
        });

        Route::controller(MasterJenisDietCtrl::class)->group(function () {
            Route::get('sysadmin/master-jenis-diet', 'masterJenisDiet');
            Route::get('sysadmin/dropdown-jenis-diet', 'KelompokDrop');

            Route::post('sysadmin/save-jenis-diet', 'saveJenisDiet');
            Route::post('sysadmin/delete-jenis-diet', 'deleteJenisDiet');
        });

        Route::controller(MasterKategoryDietCtrl::class)->group(function () {
            Route::get('sysadmin/master-kategory-diet', 'masterKategoryDiet');
            Route::get('sysadmin/dropdown-kp', 'ListKP');

            Route::post('sysadmin/save-kategory-diet', 'saveKategoryDiet');
            Route::post('sysadmin/delete-kategory-diet', 'deleteKategoryDiet');
        });

        Route::controller(MasterJenisWaktuCtrl::class)->group(function () {
            Route::get('sysadmin/master-jenis-waktu', 'masterJenisWaktu');
            Route::get('sysadmin/dropdown-departemen', 'DropdownKP');

            Route::post('sysadmin/save-jenis-waktu', 'saveJenisWaktu');
            Route::post('sysadmin/delete-jenis-waktu', 'deleteJenisWaktu');
        });

        Route::controller(StokBarangCtrl::class)->group(function () {
            Route::prefix('logistik')->group(function () {
                Route::get('/stok-barang', 'getStokProduck');
                Route::get('/select-item', 'itemDropdown');
                Route::get('/daftar-stok-opname', 'getDaftarStokOpname');
                Route::get('/stok-opname-get-produk', 'getProduk');
                Route::get('/stok-opname', 'getStokRuanganSO');
                Route::get('/stok-opname-ed', 'getStokRuanganSOByEd');
                Route::get('/get-daftar-so', 'getDaftarSO');
                Route::get('/get-password-aut', 'passwordCheck');
                Route::post('/save-stok-opname', 'saveStockOpname');
                Route::post('/save-stok-opname-ed', 'saveStockOpnameByEd');
            });
        });

        Route::controller(MasterStatusPulangCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-status-pulang')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterStatusPegawaiCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-status-pegawai')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterProdusenProdukCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-produsen-produk')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
                Route::get('/select-item', 'dropdownItem');
            });
        });

        Route::controller(MasterBahanProdukCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-bahan-produk')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
                Route::get('/select-item', 'dropdownItem');
            });
        });

        Route::controller(MasterBentukProdukCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-bentuk-produk')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
                Route::get('/select-item', 'dropdownItem');
            });
        });

        Route::controller(MasterKamarCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-kamar')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
                Route::get('/select-item', 'dropdownItem');
            });
        });

        Route::controller(MasterStatusKeluarCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-status-keluar')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisKasusCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-kasus')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisIndikatorCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-indikator')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterIndikatorCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-indikator')->group(function () {
                Route::get('/', 'getIndikatorRensar_M');
                Route::get('/get-data-combo', 'getDataCombo');
                Route::post('/save', 'saveIndikatorRensar_M');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterTargetIndikatorCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-target-indikator')->group(function () {
                Route::get('/', 'getTargetIndikator');
                Route::get('/get-data-combo', 'getDataCombo');
                Route::post('/save', 'saveTargetIndikator');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterCapaianIndikatorCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-capaian-indikator')->group(function () {
                Route::get('/', 'getCapaianIndikator');
                Route::get('/get-data-combo', 'getDataCombo');
                Route::post('/save', 'saveIndikatorRensar');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisKondisiPasienCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-kondisi-pasien')->group(function () {
                Route::get('/', 'index');
                Route::get('/cek', 'test');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterKondisiPasienCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-kondisi-pasien')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::get('/detail/{id}', 'detail');
                Route::post('/delete', 'delete');
                Route::get('/jenis-kondisi-pasien', 'jenisKondisiPasien');
            });
        });

        Route::controller(MasterUnitKerjaPegawaiCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-unit-kerja-pegawai')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::get('/detail/{id}', 'detail');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterAlergiCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-alergi')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterTipePegawaiCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-tipe-pegawai')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisUsulanCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-usulan')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterDiagnosaCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-diagnosa')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'dropdownItem');
                Route::get('/export-diagnosa', 'exportDiagnosa');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
                Route::get('/coba', 'coba');
            });
        });
        Route::controller(MasterDiagnosaKankerCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-diagnosa-kanker')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'dropdownItem');
                Route::get('/export-diagnosa', 'exportDiagnosa');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
                Route::get('/coba', 'coba');
            });
        });

        Route::controller(MasterKategoriDiagnosaCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-kategori-diagnosa')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterDiagnosaTindakanCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-diagnosa-tindakan')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'kategoriDiagnosa');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterKelompokShiftCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-kelompok-shift')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterShiftKerjaCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-shift-kerja')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::get('/select-item', 'dropdownItem');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJadwalPraktekCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jadwal-praktek')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterKedudukanPegawaiCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-kedudukan-pegawai')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterHubunganKeluargaCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-hubungan-keluarga')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterTempatTidurCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-tempat-tidur')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::get('/select-item', 'dropdownItem');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterStatusBedCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-status-bed')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterSatuanBesarCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-satuan-besar')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'dropdownItem');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterSatuanKecilCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-satuan-kecil')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'dropdownItem');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterMerkProdukCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-merk-produk')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'dropdownItem');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterKomponenHargaCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-komponen-harga')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'dropdownItem');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisKomponenHargaCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-komponen-harga')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'dropdownItem');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisTarifCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-tarif')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterAsalAnggaranCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-asal-anggaran')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterAsalSukuCadangCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-asal-suku-cadang')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisAlamatCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-alamat')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterGenerikCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-generik')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::get('/select-item', 'dropdown');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisGenerikCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-generik')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterRangeCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-range')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::get('/select-item', 'dropdown');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisRangeCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-range')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisPerawatanCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-perawatan')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterJenisRacikanCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-jenis-racikan')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterStatusApotikCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-status-apotik')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterSediaanCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-sediaan')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterRhesusCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-rhesus')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterKelompokLaporanCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-kelompok-laporan')->group(function () {
                Route::get('/', 'index');
                Route::get('/get-jenis-laporan', 'getJenisLaporan');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterStatusApotikCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-status-apotik')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterAsuransiPasienCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-asuransi-pasien')->group(function () {
                Route::get('/', 'index');
                Route::get('/select-item', 'dropdownItem');
                Route::post('/save', 'store');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterKonversiSatuanCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-konversi-satuan')->group(function () {
                Route::get('/', 'index');
                Route::get('/get-produk', 'getProduk');
                Route::get('/list-satuan', 'listSatuanStandar');
                Route::post('/save', 'store');
                Route::get('/data-konversi-satuan-by-produk', 'getDataKonversiSatuan');
                Route::post('/delete', 'delete');
            });
        });

        Route::controller(MasterTandaTanganCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-tanda-tangan')->group(function () {
                Route::get('/', 'index');
                Route::post('/save', 'save');
                Route::post('/delete', 'delete');
            });
        });
        Route::controller(MasterEMRCtrl::class)->group(function () {
            Route::prefix('sysadmin/master-emr')->group(function () {
                Route::get('/', 'index');
                Route::get('/nourut', 'nourut');
                Route::get('/get-map', 'getMap');
                Route::post('/save', 'save');
                Route::post('/delete', 'delete');
                Route::post('/save-map', 'saveMap');
            });
        });
        Route::controller(DashboardApotikCtrl::class)->group(function () {
            Route::prefix('dashboard/apotik')->group(function () {
                Route::get('/', 'getDaftarOrder');
                Route::get('/detail-order', 'getDetailOrder');
                Route::get('/count-by-date', 'countOrderByStatus');
                Route::get('/count-by-room', 'countAllbyRoom');
                Route::get('/data-combo', 'dataComboApotik');
                Route::get('/list-stok-obat', 'getStokObat');
                Route::get('/cetak-resep-obat', 'cetakResepObat');
                Route::post('/batal-verifikasi', 'batalVerifikasi');
                Route::post('/save-status-resepelektonik', 'saveStatusResepElektronik');
            });
        });

        Route::controller(DashboardRadiologiCtrl::class)->group(function () {
            Route::prefix('dashboard/radiologi')->group(function () {
                Route::get('/', 'getStrukOrderRad');
                Route::get('/detail-order', 'getDetailOrder');
                Route::get('/get-order-verify', 'getDetailOrderVerify');
                Route::get('/get-order-layanan-rad', 'getOrderPelayananRad');
                Route::get('/get-pelayanan', 'getPelayanan');
                Route::get('/get-pelayanan-nuklir-terapi', 'getPelayananNuklirTerapi');
                Route::get('/get-pelayanan-nuklir-invivo', 'getPelayananNuklirInVivo');
                Route::get('/get-dokter', 'getDataDokter');
                Route::get('/get-dokter-nuklir', 'getDataDokterNuklir');
                Route::get('/get-pegawai-invitro', 'getPegawaiinVitro');
                Route::get('/get-pegawai-nuklir-terapi', 'getPegawaiNuklirTerapi');
                Route::get('/get-only-dokter', 'getOnlyDokter');
                Route::get('/get-only-dokter-kemoterapi', 'getOnlyDokterKemoterapi');
                Route::get('/get-komponen-harga', 'getKomponenHarga');
                Route::get('/chart-layanan-ruangan', 'chartOrderByRuangan');
                Route::get('/get-detail-rad', 'getRadDetail');
                Route::get('/get-penunjang-rad', 'getDaftarPasienPenunjang');
                Route::get('/get-header-rad', 'HeaderPasienRad');
                Route::post('/save-order-pelayanan', 'savePelayananPasien');
                Route::post('/save-penjadwalan', 'savePenjadwalan');
                Route::post('/save-order-pelayanan-edit', 'savePelayananPasienEdit');
                Route::post('/save-order-pelayanan-edit-reschedule', 'savePelayananPasienEditReschedule');
                Route::post('/save-order-pelayanan-tunda', 'savePelayananPasienTunda');
                Route::post('/update-jk', 'UpdateJK');
                Route::post('/update-goldar', 'UpdateGoldar');
                Route::post('/unverif-order', 'unverifPelayananPasien');
            });
        });
        Route::controller(DashboardCathlabCtrl::class)->group(function () {
            Route::prefix('dashboard/cathlab')->group(function () {
                Route::get('/', 'getStrukOrderCathlab');
                Route::get('/detail-order', 'getDetailOrder');
                Route::get('/get-order-verify', 'getDetailOrderVerify');
                Route::get('/get-order-layanan', 'getOrderPelayanan');
                Route::get('/get-pelayanan', 'getPelayanan');
                Route::get('/get-dokter', 'getDataDokter');
                Route::get('/get-komponen-harga', 'getKomponenHarga');
                Route::get('/chart-layanan-ruangan', 'chartOrderByRuangan');
                Route::get('/get-detail-rad', 'getRadDetail');
                Route::get('/get-penunjang-rad', 'getDaftarPasienPenunjang');
                Route::get('/get-header-rad', 'HeaderPasienRad');

                Route::post('/save-order-pelayanan', 'savePelayananPasien');
                Route::post('/update-jk', 'UpdateJK');
                Route::post('/update-goldar', 'UpdateGoldar');
            });
        });

        Route::controller(DashboardTindakanCtrl::class)->group(function () {
            Route::prefix('dashboard/tindakan')->group(function () {
                Route::get('/', 'produkTindakan');
            });
        });

        Route::controller(DashboardBedahCtrl::class)->group(function () {
            Route::get('dashboard/get-order-bedah', 'getOrderBedah');
            Route::get('dashboard/get-operasi-pasien-bedah', 'getOperasiPasien');
            Route::get('dashboard/list-bedah', 'ListBedah');
            Route::get('dashboard/bedah-detail', 'getBedahDetail');
            Route::get('dashboard/get-detail-order', 'getOrderPelayananBedah');
            Route::get('dashboard/get-pelayanan-bedah', 'getPelayanaBedah');
            Route::get('dashboard/get-komponen-bedah', 'getKomponenHargaBedah');
            Route::get('dashboard/get-verifikator-dokter', 'ListVerif');
            Route::get('dashboard/jadwal-operasi', 'getJadwalOperasi');
            Route::get('dashboard/get-bedah-verify', 'getBedahVerif');
            Route::get('dashboard/get-petugas-verify', 'getpetugasVerif');
            Route::get('dashboard/laporan-tindakan-operasi', 'LapTindakanOperasi');
            Route::get('dashboard/get-operasi-pasien-cathlab', 'getOperasiCathlab');
            Route::post('dashboard/save-order-pelayanan-bedah', 'savePelayananPasienBedah');
        });

        Route::controller(TransferBarangCtrl::class)->group(function () {
            Route::prefix('/logistik')->group(function () {
                Route::get('/order-barang', 'getComboLogistik');
                Route::post('/verif-order-barang', 'saveKirimBarangRuangan');
            });
        });

        Route::controller(DashboardLogistikCtrl::class)->group(function () {
            Route::prefix('dashboard/logistik')->group(function () {
                Route::get('/get-daftar-order', 'getDaftarOrderBarang');
                Route::get('/list-ruangan', 'getListRuangan');
                Route::get('/chart-request-ruangan', 'chartCountRuanganByDate');
                Route::get('/get-data-distribusi', 'getDaftarDistribusiBarang');
                Route::get('/get-stok-produk', 'stokProdukByRuangan');
                Route::get('/daftar-penerimaan-barang', 'getDaftarPenerimaanBarang');
                Route::get('/chart-medis-non-medis', 'chartMedisNonMedis');
                Route::get('/get-informasi-stok', 'getInformasiStok');
                Route::post('/batal-kirim-barang', 'BatalKirimTerima');
                Route::get('/get-barang-kadaluarsa', 'getBarangKadaluarsa');
                Route::get('/get-combo-produk', 'getDataComboKadaluarsa');
                Route::get('/get-jenis-produk', 'getDataComboKadaluarsa');
                Route::post('/save-barang-kadaluarsa', 'saveBarangKadaluarsa');
            });
        });

        Route::controller(SuratPerintahKerjaCtrl::class)->group(function () {
            Route::get('logistik/gat-daftar-spk', 'getDaftarSPK');
            Route::get('logistik/combo-surat-perintah-kerja', 'getComboSuratPerintah');
            Route::get('logistik/get-data-produk', 'getDataProdukLogistik');
            Route::get('logistik/get-detail-data-spk', 'getDetailDataSPk');

            Route::post('logistik/save-spk', 'SaveSPK');
            Route::post('logistik/delete-spk', 'DeleteSPK');
        });

        Route::controller(SuratPerintahKerjaCtrl::class)->group(function () {
            Route::get('logistik/gat-daftar-spk', 'getDaftarSPK');
            Route::get('logistik/combo-surat-perintah-kerja', 'getComboSuratPerintah');
            Route::get('logistik/get-data-produk', 'getDataProdukLogistik');
            Route::get('logistik/get-detail-data-spk', 'getDetailDataSPk');

            Route::post('logistik/save-spk', 'SaveSPK');
            Route::post('logistik/delete-spk', 'DeleteSPK');
        });

        Route::controller(MasterListCtrl::class)->group(function () {
            Route::get('sysadmin/master-list', 'masterList');
            Route::post('sysadmin/save-master-list', 'saveList');
            Route::post('sysadmin/delete-master-list', 'deleteList');
        });
        Route::controller(BillingCtrl::class)->group(function () {
            Route::prefix('kasir/billing')->group(function () {
                Route::get('/', 'billingPasien');
                Route::get('/koding', 'billingPasienKoding');
                Route::get('/tagihan-konversi', 'detailKonversiHarga');
                Route::get('/tagihan-konversi-dropdown', 'detailKonversiHargaDropdown');
                Route::get('/petugas-tindakan', 'detailPetugasTindakan');
                Route::get('/detail-tindakan', 'detailKomponenTindakan');
                Route::get('/report/rincian-biaya', 'cetakBilling')->name('rincian-biaya');
                Route::get('/report/rincian-biaya-casemix', 'cetakBillingCasemix')->name('rincian-biaya');
                Route::get('/report/rincian-biaya-casemix-ranap', 'cetakBillingCasemixRanap');
                Route::get('/report/bukti-layanan-jasa', 'cetakBuktiLayananJasa');
                Route::get('/report/bukti-layanan-pertindakan', 'cetakBuktiLayananPerTindakan');
                Route::get('/report/cetak-form-nuklir', 'cetakFormNuklir');
                Route::get('/report/cetak-form-jadwal', 'cetakFormJadwal');
                Route::get('/report/bukti-layanan-ruangan', 'cetakBuktiLayananRuangan');
                Route::post('/hapus-tindakan', 'hapusTindakan');
                Route::post('/save-jenis-petugas', 'saveJenisPetugasTindakan');
                Route::post('/delete-jenis-petugas', 'deleteJenisPetugasTindakan');
                Route::post('/update-tgl-tindakan', 'updateTglTindakan');
                Route::post('/update-diskon-tindakan', 'updateDiskon');
                Route::post('/update-harga-konversi', 'konversiharga');
                Route::post('/simpan-harga-konversi', 'simpankonversiharga');
            });
        });

        Route::controller(PiutangPasienCtrl::class)->group(function () {
            Route::get('kasir/daftar-piutang-pasien', 'daftarPiutangPasien');
            Route::get('kasir/daftar-bayar-piutang', 'daftarPiutang');
            Route::post('kasir/verify-piutang-pasien', 'verifyPiutangPasien');
            Route::post('kasir/cancel-verif-piutang', 'cancelVerifyPiutangPasien');
            Route::get('kasir/list-item-pendukung', 'loadListData');
            Route::post('kasir/update-rekanan', 'editRekanan');
            Route::get('kasir/detail-piutang-pasien', 'detailPiutangPasien');
        });

        Route::controller(PiutangCtrl::class)->prefix('piutang')->group(function () {
            Route::get('daftar-piutang-layanan', 'daftarPiutang');
            Route::get('daftar-piutang-non-layanan', 'daftarPiutangNonLayanan');
            Route::get('daftar-collected-piutang-layanan', 'daftarCollectedPiutang');
            Route::get('collected-piutang-layanan/{noposting}', 'collectedPiutang');
            Route::get('detail-piutang-pasien-collect/{noposting}', 'detailPiutangPasienCollecting');
            Route::get('get-daftar-kartupiutang', 'daftarKartuPiutang');
            Route::get('daftar-data-klaim-bpjs', 'getMonitoringKlaimApi');
            Route::get('daftar-kartu-piutang-perusahaan', 'daftarKartuPiutangPerusahaanPeriode');
            Route::get('daftar-pembayaran-piutang-perusahaan-periode', 'daftarPembayaranPiutangPeriode');
            Route::get('rekap-klaim-by-diagnosa', 'RekapKlainDiagnosaTXT');
            Route::get('collecting-from-txt-inacbgs', 'CollectingFromTxtInaCbgs');
            Route::get('collecting-piutang', 'collectionPiutang');
            Route::get('get-checklist-klaim', 'getChecklistKlaim');
            Route::get('umur-piutang', 'umurPiutang');
            Route::get('gagal-klaim-bpjs', 'gagalKlaimBpjs');

            Route::post('collecting-piutang-layanan', 'collectingPiutang');
            Route::post('batal-collected-piutang-layanan', 'batalCollectingPiutang');
            Route::post('save-nomor-kwitansi-piutang', 'saveDataKwitansiPiutang');
            Route::post('save-bpjs-klaim', 'simpanBpjsKlaim');
            Route::post('save-bpjs-klaim-gagal-hitung', 'simpanGagalHitungBpjsKlaim');
        });

        Route::controller(PPICtrl::class)->prefix('ppi')->group(function () {
            Route::get('get-data-surveilans', 'getDataSurveilans');
            Route::get('get-data-kepatuhan-handhygiene-ipcn', 'getDataKepatuhanHandHygieneIPCN');
            Route::get('get-data-kepatuhan-handhygiene', 'getDataKepatuhanHandHygiene');
            Route::get('get-riwayat', 'getRiwayat');
            Route::get('get-data-indikator-ppi', 'getindikatoripcn');
            Route::get('get-data-cheklis-apd', 'getDataCheklisApd');
            Route::get('get-data-history-surveilans', 'getHistorySurveilans');
            Route::get('data-ipcln', 'getDataIPCLN');

            Route::post('/save-data-edukasi', 'saveEdukasiIpcln');
            Route::post('/save-kepatuhanhandhygiene', 'saveDataKepatuhanHandHygiene');
            Route::post('/batal-kepatuhanhandhygiene', 'saveBatalKepatuhanHandHygiene');
            Route::post('/save-data-pmkp', 'saveRiwayat');
            Route::post('/save-suvervisi-ipcn', 'saveSuvervisiIPCN');
            Route::post('/save-data-apd', 'saveCheklisApd');
            Route::post('/save-data-surveilans', 'saveDataSurveilans');
            Route::post('/hapus-data-surveilans', 'hapusDataSurveilans');
            Route::post('/hapus-riwayat', 'hapusRiwayat');
        });

        Route::controller(TagihanPasienCtrl::class)->group(function () {
            Route::get('kasir/daftar-tagihan-lunas', 'DaftarTagihanLunas');
            Route::get('kasir/daftar-tagihan-belum-lunas', 'DaftarTagihanBelumLunas');
            Route::get('kasir/detail-tagihan', 'detailTagihanPasien');
            Route::get('kasir/detail-bayaran', 'detailBayaran');
            Route::get('kasir/daftar-deposit-pasien', 'getDaftarDepositPasien');
            Route::get('kasir/list-tindakan-pasien', 'listTindakanBelumVerifikasi');
            Route::get('kasir/list-tindakan-pasien-emr', 'listTindakanPasien');

            Route::post('kasir/closing-pemeriksaan', 'closePemeriksaanPD');
            Route::post('kasir/save-piutang', 'savePiutang');
            Route::post('kasir/delete-deposit', 'deleteDeposit');
        });
        Route::controller(DaftarPasienPulangCtrl::class)->group(function () {
            Route::get('kasir/daftar-pasien-pulang', 'daftarPasienPulang');
            Route::get('kasir/check-pasien-active', 'checkPasienActive');
            // Route::get('kasir/daftar-ruangan', '');
            // Route::get('kasir/verifikasi-tagihan', 'verifikasiTagihan');
            // Route::get('kasir/detail-verif-tagihan', 'detailTagihanVerifikasi');

        });
        Route::controller(VerifikasiTagihanCtrl::class)->group(function () {
            Route::get('kasir/verifikasi-tagihan', 'dataTagihan');
            Route::get('kasir/list-kelas', 'listKelas');
            Route::get('kasir/get-plafon', 'getPlafon');
            Route::get('kasir/list-koding', 'listKoding');
            Route::get('kasir/get-multipenjamin', 'getMultiPenjamin');

            Route::post('kasir/verifikasi-tagihan/simpan', 'simpanVerifikasiTagihan');
            Route::post('kasir/update-tglpulang', 'updateTanggalPulang');
        });

        Route::controller(RemunerasiCtrl::class)->group(function () {
            Route::get('remunerasi/get-daftar-index-pegawai', 'getDaftarPerhitunganIndexPegawai');
            Route::get('remunerasi/get-pagu-remunerasi', 'paguRemunerasi');
            Route::get('remunerasi/get-combo-idx', 'getComboIdx');
            Route::get('remunerasi/get-ruangan', 'getRuangan');
            Route::get('remunerasi/rekap-temp', 'rekapRemunerasiTemp');
            Route::get('remunerasi/dropdown-temp', 'dropdownRemunTemp');
            Route::get('remunerasi/get-daftar-pagu-layanan', 'getDaftarJP1Rev2');
            Route::get('remunerasi/get-pegawai-by-jenis-pagu', 'getPegawaiByJenisPagu');
            Route::get('remunerasi/get-detail-laporan-remun', 'getDataDetailLaporanRemunerasi');
            Route::get('remunerasi/get-rekap-laporan-remun', 'getDataRekapLaporanRemunerasi');
            Route::get('remunerasi/get-detail-laporan-remun-dokter', 'getDataDetailLaporanRemunerasiDokter');
            Route::get('remunerasi/get-detail-laporan-remun-paramedis', 'getDataDetailLaporanRemunerasiParamedis');
            Route::get('remunerasi/get-daftar-remun-kelompok', 'getDaftarRemunKelompok');
            Route::get('remunerasi/get-daftar-remun-pegawai', 'getDaftarRemunPegawai');
            Route::get('remunerasi/get-rincian-pendapatan', 'getRincianPendapatan');
            Route::get('remunerasi/get-laporan-pendapatan-rs', 'getLapPagu');
            Route::get('remunerasi/get-daftar-detail-jenis-pagu-remun', 'getDafarDetailJenisPaguRemun');
            Route::get('remunerasi/get-detail-remun-pegawai', 'GetDetailRemunPegawai');
            Route::get('remunerasi/get-rincian-detail-remun-pegawai', 'getRincianRemunDetailPegawai');
            Route::get('remunerasi/get-data-detail-kelompok', 'getDataDetailKelompok');

            Route::post('remunerasi/update-index-pegawai', 'updateIndexPegawai');
            Route::post('remunerasi/save-remunerasi', 'saveRemunerasiJP1');
            Route::post('remunerasi/verifikasi-bayar', 'updateStatusBayar');
            Route::post('remunerasi/closing-direksi', 'saveClosingDireksi');
            Route::post('remunerasi/closing-rcd', 'saveClosingJPL');
            Route::post('remunerasi/closing-rc', 'saveClosingStruktural');
            Route::post('remunerasi/closing-cc', 'saveClosingJPTL');
            Route::post('remunerasi/closing-ccs', 'saveClosingGabungan');
            Route::post('remunerasi/closing-potongan', 'saveClosingPotongan');
            Route::post('remunerasi/save-detail-kelompok', 'saveDetailKelompok');
        });

        Route::controller(RemunerasiDokterCtrl::class)->group(function () {
            Route::get('remunerasi/get-combo', 'getCombo');
            Route::get('remunerasi/get-data-dokter', 'getData');
            Route::get('remunerasi/get-data-dokter-tindakan', 'getDataTindakan');
            Route::get('remunerasi/get-total-layanan', 'getTotalLayanan');
        });

        Route::controller(JasaPelayananCtrl::class)->group(function () {
            Route::get('jasapelayanan/get-combo-idx', 'getComboIdx');
            // Route::get('jasapelayanan/get-pagu-noreg', 'getPaguNoreg');
            // Route::get('jasapelayanan/get-pagu-layanan', 'getPaguLayanan');
            // Route::get('jasapelayanan/get-pagu-ibsa', 'getPaguIbsa');
            // Route::get('jasapelayanan/get-pagu-obat', 'getPaguObat');

            Route::post('jasapelayanan/get-pagu-noreg', 'getPaguNoreg');
            Route::post('jasapelayanan/get-pagu-layanan', 'getPaguLayanan');
            Route::post('jasapelayanan/get-pagu-ibsa', 'getPaguIbsa');
            Route::post('jasapelayanan/get-pagu-obat', 'getPaguObat');

            Route::post('jasapelayanan/update-status-jaspel', 'updateStatusJaspel');
            Route::post('jasapelayanan/update-status-jaspel-all', 'updateStatusJaspelAll');
        });


        Route::controller(PembayaranTagihanCtrl::class)->group(function () {
            Route::get('kasir/pembayaran-tagihan', 'dataPembayaranPasien');
            Route::post('kasir/pembayaran-tagihan/simpan', 'simpanPembayaran');
            Route::get('kasir/cara-bayar', 'caraBayar');
        });
        Route::controller(DaftarPenerimaanKasirCtrl::class)->group(function () {
            Route::get('kasir/daftar-penerimaan', 'daftarPenerimaan');
            Route::get('kasir/daftar-penerimaan/dropdown', 'daftarPenerimaanDropdown');
            Route::get('kasir/daftar-penerimaan/report/kwitansi', 'cetakKwitansi');
            Route::get('kasir/daftar-penerimaan/report/kwitansi-rajal-wna', 'cetakKwitansiRajalWNA');
            Route::get('kasir/daftar-penerimaan/report/kwitansi-ranap-wna', 'cetakKwitansiRanapWNA');

            Route::post('kasir/daftar-penerimaan/ubah-cara-bayar', 'saveUbahCaraBayar');
            Route::post('kasir/daftar-penerimaan/batal-bayar', 'saveBatalBayar');
        });
        Route::controller(DaftarPengeluaranKasirCtrl::class)->group(function () {
            Route::get('kasir/daftar-pengeluaran', 'daftarPengeluaran');
            Route::get('kasir/daftar-pengeluaran/dropdown', 'daftarpengeluaranDropdown');
            Route::get('kasir/daftar-pengeluaran/report/kwitansi', 'cetakKwitansi');

            Route::post('kasir/daftar-pengeluaran/ubah-cara-bayar', 'saveUbahCaraBayar');
            Route::post('kasir/daftar-pengeluaran/batal-bayar', 'saveBatalBayar');
        });
        Route::controller(DaftarPasienAktifKasirCtrl::class)->group(function () {
            Route::get('kasir/daftar-pasien-aktif', 'daftarPasienAktif');
            Route::get('kasir/daftar-pasien-aktif/detail-deposit', 'detailDeposit');
        });
        Route::controller(DaftarTagihanNonLayananCtrl::class)->group(function () {
            Route::get('kasir/daftar-tagihan-non-layanan', 'daftarTagihanNonLayanan');
            Route::get('kasir/jumlah-nominal', 'nomialTagihan');
            Route::post('kasir/daftar-tagihan-non-layanan/hapus', 'hapusNonLayanan');
        });
        Route::controller(TagihanNonLayananCtrl::class)->group(function () {
            Route::get('kasir/tagihan-non-layanan', 'tagihanNonLayanan');
            Route::get('kasir/tagihan-non-layanan/dropdown', 'dropdownTagihanNonLayanan');
            Route::get('kasir/tagihan-non-layanan/penjamin-by-kelompokpasien', 'listPenjaminByKelompokPasien');
            Route::get('kasir/tagihan-non-layanan/pelayanan', 'listPelayananNonKelas');

            Route::post('kasir/tagihan-non-layanan/simpan', 'simpanNonLayanan');
        });
        Route::controller(OrderBarangCtrl::class)->group(function () {
            Route::get('logistik/list-order-cbo', 'dropdownList');
            Route::get('logistik/get-order-barang', 'getDaftarOrderBarang');
            Route::get('logistik/get-detail-order', 'getDetailOrderBarang');

            Route::post('logistik/hapus-order-barang', 'hapusOrderBarang');
            Route::post('logistik/save-order-barang', 'saveOrderBarang');
            Route::post('logistik/batal-kirim-order-barang', 'batalKirimBarang');
        });
        Route::controller(LaboratoriumCtrl::class)->group(function () {
            Route::get('laboratorium/layanan-lab', 'LayananLab');
            Route::get('laboratorium/dokter-hasil-lab', 'dokterLab');
            Route::get('laboratorium/get-hasil-manual', 'getHasilLabManual');
            Route::get('laboratorium/get-hasil-bridging', 'getHasilLabBridging');
            Route::get('laboratorium/get-hasil-pa-bridging', 'getHasilLabPABridging');
            Route::get('laboratorium/get-hasil-pa-bridging-all', 'getHasilLabPAAllBridging');
            Route::get('laboratorium/get-hasil-pa-bridging-klaim', 'getHasilLabPABridgingKlaim');
            Route::get('laboratorium/petugas-lab', 'detailPetugasLab');
            Route::get('laboratorium/layanan-lab-pertindakan', 'LayananLabPerTindakan');
            Route::get('laboratorium/cetakan-hasil-lab', 'cetakHasilLab');
            Route::get('laboratorium/cetakan-hasil-lab-manual', 'cetakHasilLabManual');
            Route::get('laboratorium/cetakan-hasil-lab-manual-klaim', 'cetakHasilLabManualKlaim');
            Route::get('laboratorium/cetakan-hasil-lab-new', 'cetakHasilLab2');
            Route::get('laboratorium/cetakan-hasil-lab-new-klaim', 'cetakHasilLab2Klaim');
            Route::get('laboratorium/cetakan-hasil-lab-new-klaim-mikro', 'cetakHasilLab2KlaimMikro');
            Route::get('laboratorium/cetakan-hasil-culture-new', 'cetakHasilLabCulture');
            Route::get('laboratorium/get-hasil-pa', 'getHasilPemeriksaanLab');
            Route::get('laboratorium/get-hasil-pcr', 'getHasilPemeriksaanPcr');
            Route::get('laboratorium/get-hasil-mikro', 'getHasilPemeriksaanMikro');
            Route::get('laboratorium/get-regis-pasien', 'listPasienLab');
            Route::get('laboratorium/get-expertise', 'getExpertise');
            Route::get('laboratorium/cetak-ekspertise', 'cetakEkspertiseEcho');
            Route::get('laboratorium/hasil-lab', 'hasilLab');
            Route::get('laboratorium/source-hasil-lab', 'sourceHasilLab');
            Route::get('laboratorium/cetak-bukti-lab', 'cetakbukti');
            Route::get('laboratorium/bukti-lab-mikro', 'buktiMikro');

            //LAPORAN
            Route::get('laporan/laboratorium/rekap-jenis-pemeriksaan', 'laporanJenisPemeriksaan');
            Route::get('laporan/laboratorium/rekap-data-rujukan', 'laporanDataRujukan');
            Route::get('laporan/laboratorium/rekap-transaksi-laboratorium', 'laporanTransaksiOrderLaboratorium');
            Route::get('laporan/laboratorium/rekap-kunjungan-laboratorium', 'laporanKunjungan');
            Route::get('laporan/laboratorium/rekap-transaksi-laboratorium-jenis', 'transaksiLaboratorium');

            Route::post('laboratorium/save-hasillab-pa', 'saveHasilLabPA');
            Route::post('laboratorium/save-hasil-manual', 'saveHasilLabManual');
            Route::post('laboratorium/save-petugas-lab', 'savePetugasLab');
            Route::post('laboratorium/delet-petugas-lab', 'deletePetugasLab');
            Route::post('laboratorium/hapus-tindakan-lab', 'hapusTindakanLab');
            Route::post('laboratorium/hapus-tindakan-lab-verif', 'hapusTIndakanLabVerif');
            Route::post('laboratorium/hapus-tindakan-lab-all', 'hapusTindakanLabAll');
            Route::post('laboratorium/save-penunjang', 'saveTransaksi');
            Route::post('laboratorium/save-expertise', 'saveExpertise');
            Route::post('laboratorium/save-hasillab-pcr', 'saveHasilLabPCR');
            Route::post('laboratorium/save-hasillab-mikro', 'saveHasilLabMikro');
            Route::get('laboratorium/laporan-glucotest', 'getLaporanGlucotest');
            Route::post('laboratorium/save-data-bukti', 'saveDataBukti');
        });
        Route::controller(MutasiPasienCtrl::class)->group(function () {
            Route::get('registrasi/head-mutasi', 'headMutasi');
            Route::get('registrasi/dokter-mutasi', 'dokterMutasi');
            Route::get('registrasi/list-kelas-mutasi', 'listKelasMutasi');
            Route::get('registrasi/list-kamar-mutasi', 'listKamarMutasi');
            Route::get('registrasi/penjamin-mutasi', 'listPenjaminMutasi');

            Route::post('registrasi/save-mutasi', 'saveMutasi');
            Route::post('registrasi/edit-mutasi', 'editMutasi');
        });

        Route::controller(LaporanRekamMedisCtrl::class)->group(function () {

            Route::get('laporan/indikator-pelayanan-rs', 'getDataRL31RawatInapNew');
            Route::get('laporan/indikator-pelayanan-ranap', 'getDataRL31RawatInap');
            Route::get('laporan/index-penyakit', 'getIndexPenyakit');
            Route::get('laporan/penyakit-terbanyak', 'getPenyakitTerbanyak');
            Route::get('laporan/pasien-by-tindakan', 'getPasienByTindakan');
            Route::get('laporan/sensus-rajal', 'getSensusRajal');
            Route::get('laporan/registrasi-rajal', 'getRegisRajal');
            Route::get('laporan/registrasi-ranap', 'getRegisRanap');
            Route::get('laporan/registrasi-vk-igd', 'getRegisVKIGD');
            Route::get('laporan/registrasi-igd', 'getRegisIGD');
            Route::get('laporan/index-ranap', 'getIndexRanap');
            Route::get('laporan/pasien-pindah-ruangan', 'getPasienPindahRuangan');
            Route::get('laporan/get-produk-mapping', 'getProdukMapLaporanRL');
            Route::get('laporan/kegiatan-pelayanan-ranap', 'getDataRL31YangRawatInap');
            Route::get('laporan/get-produk-mapping-rl', 'getProdukMapLaporanRL');
            Route::get('laporan/get-combo-data-rl', 'getComboMappingRL');
            Route::get('laporan/get-laporan-rl4a', 'getLaporanRL4aRawatInap');
            Route::get('laporan/get-laporan-rl41', 'getLaporanRL41PenyakitRanap');
            Route::get('laporan/get-laporan-rl42', 'getLaporanRL42PenyakitRanap');
            Route::get('laporan/get-laporan-rl43', 'getLaporanRL43KematianRanap');
            Route::get('laporan/get-laporan-rl4b', 'getLaporanRL4bRawatJalan');
            Route::get('laporan/get-laporan-kunjugan-gd', 'getLaporanRL32RawatDarurat');
            Route::get('laporan/get-laporan-kegiatan-gigi-mulut', 'getKegiatanKesehatanGigidanMulut');
            Route::get('laporan/get-laporan-kegiatan-kebidanan', 'getLaporanRL34Kebidanan');
            Route::get('laporan/get-laporan-kegiatan-perinatologi', 'getLaporanRL35Perinatologi');
            Route::get('laporan/get-laporan-kegiatan-pembedahan', 'getLaporanRL36Pembedahan');
            Route::get('laporan/get-laporan-kegiatan-Radiologi', 'getLaporanRL37Radiologi');
            Route::get('laporan/get-laporan-kegiatan-Laboratorium', 'getPemeriksaanLab');
            Route::get('laporan/get-laporan-kegiatan-rehab-medik', 'getPelayananRehab');
            Route::get('laporan/get-laporan-kegiatan-pelayanan-khusus', 'getLaporanRL310Khusus');
            Route::get('laporan/get-laporan-kegiatan-kesehatan-jiwa', 'getLaporanRL311KesehatanJiwa');
            Route::get('laporan/get-laporan-kegiatan-asal-rujukan', 'getRL314Rujukan');
            Route::get('laporan/get-laporan-rekapitulasi-kunjungan', 'getRL35Kunjungan');
            Route::get('laporan/get-laporan-cara-bayar', 'getRL315CaraBayar');
            Route::get('laporan/get-kegiatan-laporan-farmasi', 'getPengadaanObat');
            Route::get('laporan/get-pelayanan-farmasi-resep', 'getPelayananResep');
            Route::get('laporan/get-laporan-rl5a', 'getDataLaporanRL51Kujungan');
            Route::get('laporan/get-laporan-rl5b', 'getDataLaporanRL52KunjuanRawatJalan');
            Route::get('laporan/get-laporan-rl5c', 'getDataLaporanRL53PenyakitaRawatInap');
            Route::get('laporan/get-laporan-rl51', 'getLaporanRL51PenyakitRawatJalan');
            Route::get('laporan/get-laporan-rl52', 'getDataLaporanRL5210PenyakitRawatJalan');
            Route::get('laporan/get-laporan-rl53', 'getDataLaporanRL5310PenyakitRawatJalan');
            Route::get('laporan/get-laporan-rekapitulasi-pengunjung', 'getDataRekapPengunjung');
            Route::get('laporan/get-laporan-pengadaan-obat', 'getDataRL317PengadaanObat');
            Route::get('laporan/get-laporan-keluarga-berencana', 'getLaporanRL316KeluargaBerencana');
            Route::get('laporan/get-laporan-sensus-ranap', 'getLapSensusRanap');
            Route::get('laporan/get-profil-rumah-sakit', 'getProfilRumahSakit');
        });

        Route::get('/notfound', function () {
            return view('template.404');
        })->name('notfound');

        Route::controller(RadiologiCtrl::class)->group(function () {
            Route::get('radiologi/layanan-radiologi', 'LayananRad');
            Route::get('radiologi/hasil-pacs', 'HasilPacs');
            Route::get('radiologi/petugas-radiologi', 'detailPetugasRad');
            Route::get('radiologi/cetakan-hasil-radiologi', 'cetakLayananRadiologi');
            Route::get('radiologi/get-expertise', 'getExpertise');
            Route::get('radiologi/cetak-ekspertise', 'cetakEkspertiseEcho');
            Route::get('radiologi/cetak-ekspertise-klaim', 'cetakEkspertiseEchoKlaim');
            Route::get('radiologi/list-pasien-regis', 'listRegisRadiologi');
            Route::get('radiologi/cetak-ekspertise-manual', 'cetakExpertiseManual');
            Route::get('radiologi/laporan-tindakana-radiologi', 'getLaporanTindakanRadiologi');
            Route::get('radiologi/laporan-rekap-tindakan-radiologi', 'getLaporanRekapTindakanRadiologi');

            //LAPORAN
            Route::get('laporan/radiologi/laporan-rekap-transaksi-radiologi', 'laporanTransaksiRadiologi');
            Route::get('laporan/radiologi/laporan-rekap-kunjungan-radiologi', 'laporanKunjunganRadiologi');


            Route::post('radiologi/hapus-tindakan-rad', 'hapusTindakanRad');
            Route::post('radiologi/save-petugas-rad', 'savePetugasRad');
            Route::post('radiologi/hapus-petugas-rad', 'deletePetugasRad');
            Route::post('radiologi/save-expertise', 'saveExpertise');
            Route::post('radiologi/save-template', 'saveTemplate');
            Route::post('radiologi/save-draft', 'saveDraftExpertise');
            Route::post('radiologi/save-expertise-berkas', 'saveExpertiseBerkas');
            Route::post('radiologi/save-transaksi-rad', 'saveTransaksiRad');
            Route::post('radiologi/hapus-expertise', 'hapusExpertise');
        });
        Route::controller(CathlabCtrl::class)->group(function () {
            Route::get('cathlab/layanan-cathlab', 'LayananRad');
            Route::get('cathlab/petugas-cathlab', 'detailPetugasRad');
            Route::get('cathlab/cetakan-hasil-cathlab', 'cetakLayananCathlab');

            Route::post('cathlab/hapus-tindakan-rad', 'hapusTindakanRad');
            Route::post('cathlab/save-petugas-rad', 'savePetugasRad');
            Route::post('cathlab/hapus-petugas-rad', 'deletePetugasRad');
            Route::post('cathlab/save-expertise', 'saveExpertise');
        });
        Route::controller(PendukungPemeriksaanCtrl::class)->group(function () {
            Route::get('laboratorium/get-jenis-pemeriksaan', 'getJenisPemeriksaan');
            Route::get('laboratorium/load-pendukung', 'LoadPendukung');
            Route::get('laboratorium/get-satuan-hasil', 'getSatuanHasil');
            Route::get('laboratorium/get-nilai-normal', 'getNilaiNormal');
            Route::get('laboratorium/get-detail-pemeriksaan', 'getMapHasilLab');
            Route::get('laboratorium/get-dd-layanan', 'getLayananDD');

            Route::post('laboratorium/save-jenis-pemeriksaan', 'saveJenisPemeriksaan');
            Route::post('laboratorium/delete-pendukung', 'deleteJenisPemeriksaan');
            Route::post('laboratorium/save-satuan-hasil', 'saveSatuanHasil');
            Route::post('laboratorium/delete-satuan-hasil', 'deleteSatuanHasil');
            Route::post('laboratorium/save-detail-pemeriksaan', 'saveDetailPemeriksaan');
        });
        Route::controller(DashboardIGDCtrl::class)->group(function () {
            Route::get('dashboard/igd-detail', 'getIGDDetail');
            Route::get('dashboard/igd-pasien', 'getIGDPasien');
            Route::get('dashboard/dropdown-igd', 'getIGD');
            Route::get('dashboard/get-pelayanan-igd', 'HitungAntrianIGD');
            Route::get('dashboard/get-riwayat-mutasi-igd', 'getRiwayatMutasiIGD');

            Route::post('dashboard/igd/panggil', 'panggilPasienIGD');
        });
        Route::controller(MasterPaketObatCtrl::class)->group(function () {
            Route::get('sysadmin/master-paket-obat', 'masterPaketObat');
            Route::get('sysadmin/master-paket-obat-dd', 'masterPaketObatdropdown');
            Route::post('sysadmin/update-master-paket-obat', 'updatePaketObat');
            Route::post('sysadmin/save-master-paket-obat', 'savePaketObat');
            Route::post('sysadmin/delete-master-paket-obat', 'deletePaketObat');
        });
        Route::controller(MapAdministrasiCtrl::class)->group(function () {
            Route::get('sysadmin/get-ruang', 'getListCombo');
            Route::get('sysadmin/produk-admin', 'getProdukAdmin');
            Route::get('sysadmin/get-produk-harga', 'getTindakanKomponen');
            Route::get('sysadmin/mapping-admin', 'getMapAdministrasi');

            Route::post('sysadmin/save-map-administrasi', 'saveMapAdmin');
            Route::post('sysadmin/delete-administrasi', 'deletMapping');
        });
        Route::controller(MapAkomodasiCtrl::class)->group(function () {
            Route::get('sysadmin/get-ruang-akomodasi', 'getListComboakomodasi');
            Route::get('sysadmin/produk-akomodasi', 'getProdukakomodasi');
            Route::get('sysadmin/get-produk-harga-akomodasi', 'getTindakanKomponen');
            Route::get('sysadmin/mapping-akomodasi', 'getMapAkomodasi');

            Route::post('sysadmin/save-map-akomodasi', 'saveMapAkomodasi');
            Route::post('sysadmin/save-akomodasi', 'saveAkomodasiAuto');
            Route::post('sysadmin/delete-akomodasi', 'deletMappingAkomodasi');
        });
        Route::controller(MapProdukPacsCtrl::class)->group(function () {
            Route::get('sysadmin/list-produk-pacs', 'getProdukRad');
            Route::get('sysadmin/list-modality', 'getMapping');

            Route::post('sysadmin/save-map-pacs', 'saveMapPacs');
        });
        Route::controller(MapBkutoKelompokTransaksiCtrl::class)->group(function () {
            Route::get('sysadmin/dd-bku', 'dropDownBKU');
            Route::get('sysadmin/master-map-bku', 'masterMapBkU');

            Route::post('sysadmin/save-map-bku', 'saveMapBKU');
        });
        Route::controller(MasterBankCtrl::class)->group(function () {
            Route::get('sysadmin/master-akun-bank', 'masterBank');
            Route::get('sysadmin/load-rekanan', 'rkDD');

            Route::post('sysadmin/save-bank-akun', 'saveBankAkun');
            Route::post('sysadmin/delete-bank-akun', 'deleteBankAkun');
        });
        Route::controller(MasterPPICtrl::class)->prefix('sysadmin')->group(function () {
            Route::get('/kelompok-ipcn', 'masterIndikator');
            Route::get('/indikator-ipcn', 'getindikatoripcn');

            Route::post('/delete-kelompok-ipcn', 'deleteKelompokIPCN');
            Route::post('/save-kelompok-ipcn', 'saveKelompokIPCN');
            Route::post('/save-indikator-ipcn', 'saveIndikatorIPCN');
            Route::post('/delete-indkator-ipcn', 'deleteIndkatorIPCN');
        });
        Route::controller(BendaharaPenerimaanCtrl::class)->group(function () {
            Route::get('bendahara/get-daftar-sbm', 'getDaftarSBM');
            Route::get('bendahara/get-list-bayar', 'getListPilihan');
            Route::get('bendahara/daftar-bku', 'daftarBKU');
            Route::get('bendahara/get-laporan-pendapatan', 'getDataPendapatanBP');

            Route::post('bendahara/save-setoran-kasir', 'simpanSetoran');
            Route::post('bendahara/save-bku', 'simpanBKU');
            Route::post('bendahara/batal-setoran', 'batalSetoranKasir');
        });
        Route::controller(BendaharaPengeluaranCtrl::class)->group(function () {
            Route::get('bendahara/get-tagihan-supplier', 'getDaftarTagihanSuplier');
            Route::get('bendahara/get-detail-tagihan-sup', 'getDetailTagihanSuplier');
            Route::get('bendahara/get-riwayat-bayar', 'getRiwayatPembayaran');
            Route::get('bendahara/detail-rekanan-tagihan', 'detailRekanan');
            Route::get('bendahara/buku-kas-pengeluaran', 'daftarBKUPengeluaran');
            Route::get('bendahara/get-list-bk', 'getListBK');
            Route::get('bendahara/list-collecting-suplier', 'getRekapCollecting');
            Route::get('bendahara/detail-collecting', 'getDetailTagihanCollecting');
            Route::get('bendahara/riwayat-bayar-collect', 'getDetailPembayaranCollecting');
            Route::get('bendahara/daftar-pembayaran-bk', 'getDataPembayaran');

            Route::post('bendahara/save-bayar-tagihan-suplier', 'saveBayarTagihanSuplier');
            Route::post('bendahara/delete-bku-bk', 'hapusBKU');
            Route::post('bendahara/save-collecting', 'saveCollectTagihan');
            Route::post('bendahara/save-bayar-collecting', 'savePembayaranCollecting');
            Route::post('bendahara/delete-collect-sup', 'batalCollectSup');
            Route::post('bendahara/batal-bayar-sup', 'saveBatalBayarSup');
        });

        Route::controller(OrderJenazahCtrl::class)->group(function () {

            Route::get('jenazah/list-ruang-jenazah', 'listRuangJenazah');
            Route::get('jenazah/list-tindakan-jenazah', 'TindakanForJenazah');
            Route::get('jenazah/riwayat-jenazah-order', 'listRiwayatOrderJenazah');
            Route::get('jenazah/detail-order-pel', 'detailOrderJenazah');

            Route::post('jenazah/delete-order-pj', 'hapusOrderPJ');
        });

        Route::controller(JenazahCtrl::class)->group(function () {

            Route::get('jenazah/list-verif', 'getOrderPJ');
            Route::get('jenazah/dd-pj', 'jenazahDD');
            Route::get('jenazah/detail-verif-pj', 'detailOrderPJ');
            Route::get('jenazah/pasien-forensik', 'getPasienForensikMedikolegal');
            Route::get('jenazah/cetak-bukti-order', 'layananJenazahperTindakan');
            Route::get('jenazah/rincian-pelayanan', 'DetailTindakanJenazah');
            Route::get('jenazah/petugas-pj', 'detailPetugasPJ');
            Route::get('jenazah/get-status-pj', 'listPasienMeninggal');
            Route::get('jenazah/get-laporan', 'laporanPemlusaranJenazah');
            Route::get('jenazah/cetak-laporan', 'cetakLaporan');
            Route::get('jenazah/get-combo', 'getCombo');

            Route::post('jenazah/save-pelayanan-pj', 'savePelayananJenazah');
            // Route::post('jenazah/verif-order', 'simpanNonLayanan');
            Route::post('jenazah/save-pengambilan-jenazah', 'savePengambilanJenazah');
            Route::post('jenazah/save-petugas-pj', 'savePetugasPJ');
            Route::post('jenazah/delete-petugas-pj', 'deletePetugasPJ');
            Route::post('jenazah/save-batal-meninggal', 'saveBatalMeninggal');
            Route::post('jenazah/save-permohonan-pj', 'savePermohonanPelayananJenazah');
        });

        Route::controller(OrderAmbulanCtrl::class)->group(function () {

            Route::get('ambulan/list-ruang-ambulan', 'listRuangJenazah');
            Route::get('ambulan/list-tindakan-ambulan', 'TindakanForAmbulance');
            Route::get('ambulan/riwayat-ambulan-order', 'listRiwayatOrderAmbulan');
            Route::get('ambulan/detail-ambulan', 'detailOrderAmbulan');

            Route::post('ambulan/delete-order-ambulan', 'hapusOrderAmbulance');
        });

        Route::controller(AmbulanCtrl::class)->group(function () {

            Route::get('ambulan/ambulan-verif', 'getOrderAmbulan');
            Route::get('ambulan/dd-ambulan', 'ambulanDD');
            Route::get('ambulan/detail-verif-ambulan', 'detailOrderAmbulan');
            Route::get('ambulan/get-layanan-ambulance', 'getLayananAmbulance');
            Route::get('ambulan/cetak-bukti-ambulan', 'layananAmbulanperTindakan');
            Route::get('ambulan/penunjang-ambulan', 'getPasienAmbulan');
            Route::get('ambulan/rincian-ambulan', 'DetailTindakanAmbulan');
            Route::get('ambulan/petugas-ambulan', 'detailPetugasAmbulan');
            Route::get('ambulan/list-pasien-regist', 'listPasienAmbulan');
            Route::get('ambulan/laporan-order-ambulan', 'laporanOrderAmbulan');
            Route::get('ambulan/get-surat-jalan', 'getSuratJalan');
            Route::get('ambulan/cetak-laporan', 'cetakLaporan');

            Route::post('ambulan/save-verif-ambulan', 'savePelayananAmbulan');
            Route::post('ambulan/save-petugas-ambulan', 'savePetugasAmbulan');
            Route::post('ambulan/save-transaksi-ambulan', 'saveTransaksiAmbulan');
            Route::post('ambulan/save-surat-jalan', 'simpanSuratJalan');
        });
        Route::controller(BankDarahCtrl::class)->prefix('bank-darah')->group(function () {
            Route::get('list-pasien-regist', 'listPasienRegis');
            Route::get('get-order-darah', 'getOrderDarah');
            Route::get('get-pelayanan-darah', 'getPelayanaDarah');
            Route::get('get-darah-verify', 'getDarahVerif');
            Route::get('get-penunjang', 'getPenunjangPasien');
            Route::get('layanan-bank-darah', 'LayananLab');
            Route::get('get-combo', 'getCombo');
            Route::get('get-produk', 'getDataProdukDetail');
            Route::get('get-stok-produk', 'getStokProduk');
            Route::get('get-daftar-penerimaan', 'getDaftarPenerimaanDarah');
            Route::get('get-detail-penerimaan', 'getDetailPenerimaanDarah');
            Route::get('get-pasien-order-darah', 'getPasienOrderDarah');
            Route::get('get-pengeluran-stok-darah', 'getPengeluaranDarah');

            Route::post('save-order-pelayanan-darah', 'savePelayananPasienDarah');
            Route::post('update-tindakan-darah', 'updateTindakanDarah');
            Route::post('save-penerimaan-darah', 'savePenerimaanDarah');
            Route::post('update-penerimaan-darah', 'updateStokDarah');
            Route::post('save-pengeluaran-produk', 'PengeluaranProduk');
            Route::post('delete-penerimaan-darah', 'DeletePenerimaanDarah');
            Route::get('cetak-surat-pernyataan', 'suratPernyataanUTd');
            Route::get('cetak-surat-persetujuan', 'suratPersetujuanUTd');
            Route::get('hasil-darah', 'getHasilDarah');


            Route::post('save-order-pelayanan-darah', 'savePelayananPasienDarah');
            Route::post('save-hasil', 'saveHasil');
        });
        Route::controller(PemesananBarangCtrl::class)->group(function () {
            Route::get('logistik/cbo-spbb', 'ComboSPBB');
            Route::get('logistik/rekanan-detail', 'getRekananDetail');
            Route::get('logistik/daftar-sppb', 'getDaftarSPPB');
            Route::get('logistik/detail-sppb', 'getDetailDataSPPB');

            Route::post('logistik/save-sppb', 'savePemesananBarang');
        });
        Route::controller(LaporanFarmasiCtrl::class)->prefix('farmasi/laporan/')->group(function () {
            Route::get('detail-penjualan', 'getLaporanPenjualanObatDetail');
            Route::get('penjualan', 'getLaporanPengeluaranObat');
        });
        Route::controller(ReportCtrl::class)->group(function () {
            Route::prefix('report')->group(function () {
                Route::get('farmasi/resep', 'cetakResep');
                Route::get('farmasi/resep-obat-bebas', 'cetakResepObatBebas');
                Route::get('farmasi/label-resep', 'cetakLabelResep');
                Route::get('farmasi/copy-resep', 'cetakCopyResep');
                Route::get('/cetak-lembar-ranap', 'cetakLembarRawatInap');
                Route::get('/cetak-lembar-keluar-masuk', 'suratKeluarMasuk');
                Route::get('/cetak-gelang-pasien', 'cetakGelangPasien');
                Route::get('/cetak-surat-kematian', 'cetakSuratKematian');
                Route::get('/cetak-surat-meninggal', 'cetakSuratMeninggal');
                Route::get('farmasi/get-data-waktuminum-resep', 'getDataWaktuMinum');
                // Route::get('farmasi/cetak-apotik-rekap-label', 'apotikRekapLabel');
                Route::get('farmasi/cetak-nomor-antrian', 'apotikCetakAntrian');
                Route::get('farmasi/resep-obat-23', 'cetakResepObat23');
                Route::get('farmasi/kwitansi-obat-23', 'cetakKwitansiObat23');
                Route::get('farmasi/rekap-label-kecil', 'rekapLabelInject');
                Route::get('farmasi/label-custom', 'labelCustom');
                Route::get('asset/cetak-label-barang', 'cetakLabelBarang');

                Route::get('kiosk/cetak-antrian', 'cetakAntrianKiosk');
                Route::get('/cetak-order', 'cetakOrder');
                Route::get('/cetak-label', 'cetakLabel');
                Route::get('/cetak-label-tindakan', 'cetakLabelTindakan');

                Route::get('farmasi/cetak-apotik-label-kecil', 'apotikRekapLabelKecil');
                Route::get('farmasi/cetak-nama-pasien', 'apotikCetakNama');
                Route::get('farmasi/cetak-apotik-label-kecil-bebas', 'apotikRekapLabelKecilObatBebas');
                Route::get('farmasi/cetak-apotik-label-kecil-pesanan', 'apotikRekapLabelKecilObatPesanan');
                Route::get('farmasi/get-data-waktuminum-resep', 'getDataWaktuMinum');
                Route::get('farmasi/cetak-nomor-antrian', 'apotikCetakAntrian');
                Route::get('kasir/laporan-penerimaan', 'getDataLaporanPenerimaanSemuaKasirPDF');
                Route::get('kasir/laporan-penerimaan-perunit', 'getDataLaporanPenerimaanSemuaKasirPerunitPDF');
                Route::get('kasir/laporan-penerimaan-obat-bebas', 'getDataLaporanObatBebas');
                Route::get('kasir/laporan-penerimaan-pasien-lost', 'getDataLaporanPasienLost');
                Route::get('kasir/laporan-penerimaan-harian', 'getDataLaporanPenerimaanHarianPDF');
                // Route::get('bendahara/lap-penerimaan-harian', 'getLapPenerimaan');
                Route::get('bendahara/get-lap-harian', 'getLapKasir');


                Route::get('cetak-antrian', 'cetakAntrianKiosk');
                Route::get('bukti-pendaftaran', 'cetakBuktiPendaftaran');
                Route::get('kasir/bukti-pembayaran', 'buktiPembayaran');

                Route::get('ranap/cetak-surat-sehat', 'cetakSuratKeteranganSehat');
                Route::get('ranap/cetak-surat-sakit', 'cetakSuratKeteranganSakit');
                Route::get('cetak-surat-pendaftaran-ranap', 'suratPendaftaranRanap');

                Route::get('bukti-layanan-bpjs', 'cetakBillbpjs');
                Route::get('bukti-layanan-bpjs-klaim', 'cetakBillbpjsKlaim');
                Route::get('bukti-layanan-bpjs-nonlayanan', 'cetakBillbpjsNon');
                Route::get('bukti-layanan-va', 'cetakBillVA');
                Route::get('bukti-layanan-carabayar', 'cetakBillCaraBayar');
                Route::get('bukti-layanan-carabayar-nonlayanan', 'cetakBillCaraBayarNon');
                Route::get('bukti-kwitansi', 'cetakKwitansi');
                Route::get('resum-medis', 'cetakResumMedis');

                Route::get('radiologi/cetak-rekap-expertise', 'cetakRekapExpertise');

                Route::get('gizi/cetak-label', 'cetakLabelGizi');
                Route::get('gizi/multiple-cetak-label', 'cetakMultipleLabelGizi');
                Route::get('mcu/cetak-mcu', 'cetakMCU');
                Route::get('/cetak-sks', 'cetakSKS');
                Route::get('/cetak-skjiwa', 'cetakSKJiwa');
                Route::get('/cetak-sknapza', 'cetakSKNapza');
                Route::get('/get-riwayat-resep', 'getResep');
                Route::get('/cetak-hasil-pcr', 'cetakHasilAntigen');
                Route::get('/cetak-hasil-mikro', 'cetakHasilMikro');

                Route::get('/cetak-kwitansi-tagihan', 'cetakKwintansiTagihan');
                Route::get('/rekapitulasi-tagihan-asuransi', 'cetakRekapitulasiTagihanAsuransi');
                Route::get('/bukti-kwitansi-piutang', 'cetakKwitansiPiutang');
                Route::get('/cetak-surat-piutang', 'cetakSurat');
                Route::get('/cetak-tagihan-piutang', 'cetakTagihan');
                Route::prefix('piutang')->group(function () {
                    Route::get('/cetak-kartu-piutang-perusahaan', 'cetakKartuPiutangPerusahaan');
                    Route::get('/daftar-pembayaran', 'laporanPembayaranPiutangPerusahaan');
                    Route::get('/rekap-pembayaran', 'rekapPembayaranPiutangPerusahaan');
                });
            });
        });
        Route::controller(RekamMedisCtrl::class)->group(function () {
            Route::prefix('rekammedis')->group(function () {
                Route::get('/dropdown', 'getDropdown');
                Route::get('/get-ruangan-by-departement', 'getRuanganBydepartemenId');
                Route::get('/get-data-kendali-dokumen-rm', 'getDaftarKendaliDokumenRM');
                Route::post('/update-status-kendali-dokumen-rm', 'updateStatusKendaliDokumenRM');
            });
        });
        Route::prefix("medifirst2000")->group(function () {
            Route::controller(AntrianCtrl::class)->group(function () {
                Route::post('viewer/update-antrian', 'updatePanggil');
                Route::post('viewer/update-finish', 'updateFinish');
                Route::get('viewer/update-skip', 'updateSkip');
                Route::post('viewer/update-sedangdipanggil', 'updateSedang');
                Route::get('viewer/get-data-viewer', 'getViewer');
                Route::get('viewer/get-setting-viewer', 'getSettingViewer');
                Route::get('viewer/get-dipanggil', 'getDipanggil');
                Route::get('viewer/get-list-antrian', 'getListAntrian');
                Route::get('viewer/get-list-antrian-farmasi', 'getListAntrianFarm');
                Route::get('viewer/get-data-viewer-far', 'getViewerFar');
                Route::get('viewer/get-data-detail-panggil', 'getDetail');
                Route::get('viewer/get-list-datalast-panggil', 'getListCallerByRuangan');
                Route::get('viewer/get-data-viewer-ok', 'getViewerOK');
                Route::get('viewer/get-data-viewer-tempat-tidur', 'getTempatTidur');
                Route::get('viewer/get-list-antrian-lab', 'getListAntrianLab');
            });

            Route::controller(GeneralCtrl::class)->group(function () {
                Route::get('sysadmin/logging/save-log-all', 'saveLoggingAll');
                Route::get('sysadmin/settingdatafixed/get/{setting}', 'settingFixData');
            });
            Route::prefix("bridging")->group(function () {
                Route::controller(BridgingBPJSCtrl::class)->group(function () {
                    Route::post('/bpjs/tools', 'bpjsTools');
                    Route::get('/bpjs/get-rujukan-pcare-nokartu', 'getNoRujukanPcareNoKartu');
                    Route::get('/bpjs/get-rujukan-rs-nokartu', 'getNoRujukanRs');
                    Route::get('/bpjs/get-no-peserta', 'getNoPeserta');
                    Route::get('/bpjs/monitoring/HistoriPelayanan/NoKartu/{noKartu}', 'getMonitoringHistori');
                    Route::get('/bpjs/get-rujukan-pcare', 'getNoRujukanPcare');
                    Route::get('/bpjs/get-rujukan-rs', 'getNoRujukanRs');
                    Route::get('/bpjs/get-ref-dokter-dpjp', 'getDokterDPJP');
                    Route::get('/bpjs/get-mapping-dkoterbpjs', 'getDaftarMappingDokterBpjsToDokterRs');
                    Route::get('/bpjs/test', 'bpjsToolsTest');

                    Route::post('/bpjs/save-data-mappingdkoterbpjs', 'saveMappingDokterBpjsDokterRs');
                    Route::post('/bpjs/delete-data-mappingdkoterbpjs', 'saveHapusMappingDokterBpjsDokterRs');
                    Route::post('/bpjs/save-monitoring-klaim', 'saveMonitoringKlaim');
                });
                Route::controller(ApotikOnlineCtrl::class)->prefix('bpjs/apotik')->group(function () {
                    Route::get('/obat', 'getDaftarMappingObatBpjsToObatRs');
                    Route::get('/ruangan', 'getDaftarMappingRuangan');

                    Route::post('/obat', 'saveMappingObatBpjsObatRs');
                    Route::post('/delete-obat', 'saveHapusMappingObatBpjs');
                    Route::post('/ruangan', 'saveMappingRuangan');
                });

                Route::controller(AntrianOnlineCtrl::class)->group(function () {
                    Route::post('/antrol/sendDataAntrean', 'sendDataAntrean');
                    Route::post('/antrol/sendTaskId', 'sendTaskId');
                    Route::get('/antrol/ambilWaktudiKiosk', 'ambilWaktudiKiosk');
                    Route::get('/antrol/getMonitoringWaktu', 'getMonitoringWaktu');
                    Route::post('/antrol/saveMonitoringTaksId', 'saveMonitoringTaksId');
                    Route::get('/antrol/getComboMonitoring', 'getComboMonitoring');
                    Route::get('/antrol/getDataAntrean', 'getDataAntrean');
                    Route::post('/antrol/updateDataAntrean', 'updateDataAntrean');
                });

            });
            Route::prefix("reservasionline")->group(function () {
                Route::controller(ReservasiMobileCtrl::class)->group(function () {
                    Route::get('/get-pasien-by-no-rm', 'getDataPasienOnlyRm');
                    Route::post('/update-data-status-reservasi', 'UpdateStatConfirm');
                    Route::get('/get-pasien-nokartu/{nocm}', 'getPasienByNoka');
                });
            });
            Route::controller(ReportCtrl::class)->group(function () {
                Route::prefix('report')->group(function () {
                    Route::get('/cetak-antrian', 'cetakAntrianKiosk');
                    Route::get('/cetak-bukti-pendaftaran', 'cetakBuktiPendaftaran');
                    Route::get('/get-cetak-bukti-pendaftaran', 'Report\ReportController@cetakBuktiPendaftaranGet');
                });
            });

            Route::controller(KiosKController::class)->group(function () {
                Route::prefix('kiosk')->group(function () {
                    Route::get('get-combo-setting', 'getComboSettingKios');
                    Route::post('save-antrian', 'saveAntrianTouchscreen')->name("pasienBaru");
                    Route::post('save-antrian-kanker', 'saveAntrianTouchscreenKanker');
                    Route::post('save-antrian-baru', 'saveAntrianTouchscreenNew');
                    Route::get('get-combo-registrasi', 'getComboRegBaru');
                    Route::get('get-pasien/{nocm}/{tgllahir}', 'getPasienByNoCmTglLahir');
                    Route::get('get-combo-kiosk2', 'getComboKios2');
                    Route::get('get-daftar-jadwal-dokter', 'getJadwalDokter');
                    Route::get('get-daftar-poli-internal', 'getRuanganBPJSInternal');
                    Route::get('get-penjaminbykelompokpasien', 'getPenjaminByKelompokPasien');
                    Route::get('get-diagnosabykode/{kode}', 'getDiagnosaByKode');
                    Route::get('get-ruanganbykode/{kode}', 'getRuanganByKodeInternal');
                    Route::get('get-ruangan', 'getComboRuanganKios');
                    Route::get('get-jumlah-loket', 'getJumlahLoket');
                    Route::get('get-slotting-kosong', 'getSlottingKosong');
                    Route::get('get-slotting-kiosk', 'getSlottingKios');
                    Route::get('get-data-pasien/{identitas}', 'getDataPasien');
                    Route::get('get-dokter-internal', 'getDokterInternal');
                    Route::get('get-view-bed-tea', 'getKetersediaanTempatTidurView');
                    Route::get('get-dokterbyruangan', 'getComboDokterByRuanganKiosV2');
                    Route::get('get-dokterbyruangan-semuatgl', 'getComboDokterByRuanganKiosV2Semua');
                    Route::get('get-dokterbyruangan-semuatgl-web', 'getComboDokterByRuanganKiosV2SemuaWeb');
                    Route::get('get-dokterbyruangan-semuaruangan', 'getComboDokterByRuanganKiosV2SemuaRuangan');
                    Route::get('get-view-bed', 'viewBed');

                    Route::post('save-slotting-kiosk', 'saveSlottingKios');
                    Route::post('delete-slotting-kiosk', 'deleteSlotting');


                    Route::post('kiosk/save-antrian', 'KiosK\KiosKController@saveAntrianTouchscreen')->name("pasienBaru");
                    Route::get('kiosk/get-ruanganbykode/{kode}', 'KiosK\KiosKController@getRuanganByKodeInternal');
                    Route::get('kiosk/get-diagnosabykode/{kode}', 'KiosK\KiosKController@getDiagnosaByKode');
                    Route::get('kiosk/get-view-bed-tea', 'KiosK\KiosKController@getKetersediaanTempatTidurView');
                    Route::get('kiosk/get-view-bed', 'KiosK\KiosKController@viewBed');
                    Route::get('kiosk/get-combo', 'KiosK\KiosKController@getDataCombo');
                    Route::get('kiosk/get-tarif', 'KiosK\KiosKController@getDaftarTarif');
                    Route::post('kiosk/save-survey', 'KiosK\KiosKController@saveSurvey');
                    Route::get('kiosk/get-combo-dokter-temp', 'KiosK\KiosKController@getComboDokterKios');
                    Route::get('kiosk/get-combo-setting', 'KiosK\KiosKController@getComboSettingKios');
                    Route::get('kiosk/get-ruangan', 'KiosK\KiosKController@getComboRuanganKios');
                    Route::get('kiosk/get-slotting-kosong', 'KiosK\KiosKController@getSlottingKosong');
                    Route::get('kiosk/get-list-loket', 'KiosK\KiosKController@getListLoket');
                    Route::get('kiosk/get-dokter-internal', 'KiosK\KiosKController@getDokterInternal');
                    Route::get('kiosk/get-combo-kiosk2', 'KiosK\KiosKController@getComboKios2');
                    Route::get('kiosk/get-daftar-jadwal-dokter', 'KiosK\KiosKController@getJadwalDokter');
                    Route::get('kiosk/get-pasien-by-noka', 'KiosK\KiosKController@getPasienByNoka');
                    Route::get('kiosk/get-quisoner', 'KiosK\KiosKController@getQuisonerMaster');
                    Route::get('kiosk/get-quisoner-transaksi-detail', 'KiosK\KiosKController@getQuisonerTransaksiDetail');
                    Route::get('kiosk/get-data-ruangan', 'KiosK\KiosKController@getDataRuangan');

                    Route::post('kiosk/save-keluhan-pelanggan', 'Humas\HumasController@SaveKeluhanPelanggan');
                    Route::post('kiosk/save-quiz', 'Humas\HumasController@saveQuisDinamis');
                });
            });

            Route::controller(PasienBaruCtrl::class)->group(function () {
                Route::post('registrasi/save-pasien-fix', 'savePasien');
            });
            Route::controller(RegistrasiRuanganCtrl::class)->group(function () {
                Route::post('registrasi/save-registrasipasien', 'saveRegistrasi');
                Route::post('registrasi/save-adminsitrasi', 'saveAdministrasi');
            });

            Route::controller(ReservasiMobileCtrl::class)->group(function () {
                Route::prefix('reservasionline')->group(function () {
                    Route::get('/get-list-data', 'getComboReservasi');
                    Route::get('/find-ruangan-rajal', 'getRuanganRajal');
                    Route::get('/find-pegawai-dokter', 'getPegawaiDokter'); // gajadi tp biarin dl
                    Route::get('/get-daftar-slotting', 'getDaftarSlotting');
                    Route::get('/get-history', 'getHistoryReservasi');
                    Route::get('/get-history-ereservasi', 'getHistoryEReservasi');
                    Route::get('/get-pasien/{nocm}/{tgllahir}', 'getPasienByNoCmTglLahir');
                    Route::get('/get-libur', 'getLiburSlotting');
                    Route::get('/get-bank-account', 'getNomorRekening');
                    Route::get('/cek-reservasi-satu', 'cekReservasiDipoliYangSama');
                    Route::get('/get-slotting-by-ruangan-new/{kode}/{tgl}', 'getSlottingByRuanganNew');
                    Route::get('/get-slot-available', 'getDaftarSlottingAktif');
                    Route::get('/tagihan/get-pasien/{noregistrasi}', 'getPasienByNoRegistrasi'); //done
                    Route::get('/get-tagihan-pasien/{noregistasi}', 'getTagihanEbilling');
                    Route::get('/get-setting', 'getSetting');
                    Route::get('/daftar-riwayat-registrasi', 'getDaftarRiwayatRegistrasi');
                    Route::get('/cek-pasien-baru-by-nik/{nik}', 'cekPasienByNik');
                    Route::get('/get-status-va', 'getDaftarStatusVA');
                    Route::get('/get-slotting-new', 'getSlottingByRuanganNew2');
                    Route::get('/get-data', 'getDataReservasi');
                    Route::get('/get-slotting-rev', 'getSlottingByRuanganDokter');
                    Route::get('/get-dokter', 'getDokterByRuang');
                    Route::get('/get-pasien-nokartu/{nocm}', 'getPasienByNoka');
                    Route::get('/billing', 'billingPasien');
                    Route::get('/info-bed', 'infoBed');
                    Route::get('/jadwal-dokter', 'jadwalDokter');


                    Route::post('/save-slotting', 'saveSlotting');
                    Route::post('/update-data-status-reservasi', 'UpdateStatConfirm');
                    Route::post('/update-nocmfk-antrian-registrasi', 'updateNoCmInAntrianRegistrasi');
                    Route::post('/save', 'saveReservasi');
                    Route::post('/delete', 'deleteReservasi');
                    Route::post('/save-libur', 'saveLibur');
                    Route::post('/delete-libur', 'deleteLibur');
                    Route::post('/update-tglreservasi', 'updateTglReservasi');
                });
            });
        });

        Route::controller(RegistrasiPasienCtrl::class)->group(function () {
            Route::get('resgistrasi/get-daftar-pasienbatal', 'getPembatalanPasien');
            Route::get('resgistrasi/get-daftar-pasien-meninggal', 'getPasienMeninggal');
            Route::get('laporan/pendaftaran', 'getLaporanPasienDaftar');
            Route::get('resgistrasi/get-top-ten-diagnosa', 'getTopTenDiagnosa');
            Route::get('registrasi/sudah-periksa', 'getSudahPeriksa');
            Route::get('resgistrasi/get-laporan-tracer', 'getLaporanTracer');
            Route::get('registrasi/cetak-tracer', 'cetakTracer');
            Route::post('registrasi/save-update-rekanan_pd', 'simpanUpdateRekananPD');
        });
        Route::controller(SterilisasiCtrl::class)->prefix('stelilisasi')->group(function () {
            Route::get('/combo', 'getComboSteril');
            Route::get('/get-data-stok-steril', 'getDataStokInsSteril');
            Route::get('/data-orderalatsteril', 'getDaftarOrderAlatSteril');
            Route::get('/get-produk', 'getProdukCssd');
            Route::get('/get-info-stok', 'getInformasiStok');
            Route::get('/kelompok-alat', 'getDataKelompokAlat');
            Route::get('/daftar-distribusi-barang', 'getDaftarDistribusiBarangSteril');

            Route::post('/save-registrasi-barang', 'saveRegistrasiBarangSteril');
            Route::post('/save-kelompok-alat', 'saveKelompokAlat');
            Route::post('/delete-kelompok-alat', 'deleteKelompokAlat');
            Route::post('/save-kirim-barang-ruangan', 'saveKirimBarangRuangan');
        });
        Route::controller(DashboardIprcCtrl::class)->prefix('iprs')->group(function () {
            Route::get('/combo', 'combo');
            Route::get('/penangung-jawab', 'getPegawaiPenangungJawab');
            Route::get('/get-daftar-rencana-usulan-permintaan', 'getDaftarRencanaUsulanPermintaan');
            Route::get('/get-detail-rencana-usulan-permintaan', 'getDetailRUPB');
            Route::get('/get-daftar-ipsrs', 'getDaftarIPSRS');
            Route::get('/get-daftar-produk', 'dropdownProduk');
            Route::get('/cek-kirim-barang-ruangan', 'CekProdukKirim');
            Route::get('/daftar-pemeliharaan', 'getDaftarPemeliharaan');
            Route::get('/produk-asset', 'getDataProduk');

            Route::post('/save-data-rencana-usulan', 'saveRencanaUsulanPermintaanNew');
            Route::post('/verifikasi-data-rencana-usulan', 'saveVerifikasiPengelolaUrusan');
            Route::post('/hapus-data-rencana-usulan', 'hapusDataRUPB');
            Route::post('/save-permohonan-perbaikan', 'SavePermohonan');
            Route::post('/save-pengerjaan-permohonan-perbaikan', 'SavePengerjaanPermohonan');
            Route::post('/hapus-permohonan-ipsrs', 'HapusPermohonanIPSRS');
            Route::post('/kirim-produk-pemakaian-barang', 'saveKirimBarangRuangan');
        });
        Route::prefix("tele")->group(function () {
            Route::controller(TelemedicineCtrl::class)->group(function () {
                Route::get('detail-pasien', 'getDetailPasien');
                Route::get('get-kesehatan-umum', 'getKesehatanUmum');
                Route::get('get-perawatan', 'getPerawatan');
                Route::get('get-alergi', 'getAlergi2');
                Route::get('get-pengobatan', 'getPengobatan');
                Route::get('get-imunisasi', 'getAlergi');
                Route::get('get-observasi-kesehatan', 'getObservasiKesehatan');
                Route::get('get-prosedur', 'getProsedur');
                Route::get('get-pertemuan-mendatang', 'getPertemuanMendatang');
                Route::get('get-rekomendasi', 'getRencanaRekomen');
                Route::get('get-elektrokardiogram', 'getECG');
                Route::get('get-radiologi', 'getRadiologi');
                Route::get('get-hasil-lab', 'getHasilLab');
                Route::get('get-observasi-kesehatan', 'getObservasiKesehatan');
                Route::get('get-list-pasien', 'getDaftarPasienDK');
                Route::get('get-referensi', 'getReffKontrol');
                Route::get('get-skrining-ascvd', 'getSkriningACC');
                Route::get('get-hasil-treadmill', 'getSkriningACC');
                Route::get('get-master-dokter', 'getMasterDokter');
                Route::get('get-list-negara', 'getListNegara');
                Route::get('get-list-kebangsaan', 'getListKebangsaan');
                Route::get('get-antrian-poli', 'getAntrianPoli');


                Route::get('registrasi/get-combo-registrasi', 'getComboRegBaru');
                Route::get('registrasi/get-combo-address', 'getComboAddress');
                Route::get('registrasi/get-desa-kelurahan-paging', 'getDesaKelurahanPaging');
                Route::get('registrasi/get-data-combo-new', 'getDataComboNEW');
                Route::get('registrasi/list', 'listRegistrasi');
                Route::get('get-combo-resep-emr', 'getDataComboResepEMR');
                Route::get('get-daftar-detail-order', 'getDaftarDetailOrder');
                Route::get('get-kode-ruangan-depo-telemedicine', 'getRuanganDepoTelemedicine');
                Route::get('trigger-telemedicine', 'sendPHR');
            });
            Route::controller(PelayananObatBebasCtrl::class)->group(function () {
                Route::get('farmasi/get-daftar-jual-bebas', 'getDaftarPenjualanBebas');
                Route::post('farmasi/save-input-non-layanan-obat', 'saveInputTagihanObat');
            });
            Route::controller(OrderResepCtrl::class)->group(function () {
                Route::get('riwayat-order-resep', 'riwayatOrderResep');
                Route::post('farmasi/hapus-order', 'hapusOrderResep');
                Route::post('simpan-order', 'simpanOrderResep');
            });
            Route::controller(InputResepCtrl::class)->group(function () {
                Route::get('farmasi/dropdown-obat', 'dropdownObat');

                Route::get('farmasi/get-produkdetail', 'getProdukDetail');
            });
            Route::controller(RegistrasiRuanganCtrl::class)->group(function () {
                Route::post('registrasi/save-registrasipasien', 'saveRegistrasi');
            });
            Route::controller(PasienBaruCtrl::class)->group(function () {
                Route::post('registrasi/save-pasien-fix', 'savePasien');
            });
            Route::controller(KiosKController::class)->group(function () {
                Route::post('kiosk/save-antrian', 'saveAntrianTouchscreen');
            });
            Route::controller(InputDiagnosaCtrl::class)->group(function () {
                Route::get('diagnosa/diagnosa-x-paging', 'listDianosaX');
                Route::get('diagnosa/diagnosa-ix-paging', 'listDianosaIX');
                Route::get('diagnosa/riwayat-diagnosa-x', 'riwayatDiagnosaX');
                Route::get('diagnosa/riwayat-diagnosa-ix', 'riwayatDiagnosaIX');

                Route::post('diagnosa/save-diagnosa', 'saveDiagnosaPasien');
                Route::post('diagnosa/save-diagnosa-ix', 'saveDiagnosaTindakanPasien');
            });
            Route::controller(EMRCtrl::class)->group(function () {

                Route::get('emr/get-perjanjian', 'getPasienPerjanjian');
                Route::post('emr/simpan-perjanjian', 'simpanPerjanjian');
                Route::post('emr/hapus-perjanjian', 'hapusOrderPerjanjian');
            });
            Route::controller(DaftarRegistrasiCtrl::class)->group(function () {

                Route::post('simpan-pasien-konsul', 'simpanKonsul');
            });
            Route::controller(ReservasiMobileCtrl::class)->group(function () {
                Route::get('reservasionline/get-dokter', 'getDokterByRuang');
                Route::get('reservasionline/get-slotting-rev', 'getSlottingByRuanganDokter');

                Route::get('reservasionline/get-history', 'getHistoryReservasi');
                Route::get('reservasionline/get-history-ereservasi', 'getHistoryEReservasi');
                Route::post('reservasionline/save', 'saveReservasi');
                Route::post('reservasionline/delete', 'deleteReservasi');
                Route::post('reservasionline/batal-reservasi', 'batalReservasi');
                Route::post('reservasionline/update-jadwal-reservasi', 'updateJadwalReservasi');
                Route::post('reservasionline/akun-register', 'akunRegister');
            });
            Route::controller(SterilisasiCtrl::class)->prefix('stelilisasi')->group(function () {
                Route::get('/combo', 'getComboSteril');
                Route::get('/get-data-stok-steril', 'getDataStokInsSteril');
                Route::get('/data-orderalatsteril', 'getDaftarOrderAlatSteril');
                Route::get('/get-produk', 'getProdukCssd');
                Route::get('/get-info-stok', 'getInformasiStok');
            });
        });
        Route::controller(MKKOCtrl::class)->group(function () {
            Route::get('mkko/get-borlostoi', 'getBORLOSTOI');
            Route::get('mkko/jumlah-pasien-by-kelompok', 'jmlPengunjung');
            Route::post('mkko/jumlah-pasien-by-kelompok-query', 'jmlPengunjungQuery');
            Route::get('mkko/get-borlostoi', 'getBORLOS');
            Route::get('mkko/persentase-inpatien-visit', 'persentaseInpatien');
            Route::get('mkko/get-target-mkko', 'getTargetMKKO');
            Route::get('mkko/lap-jumlah-tindakan-operasi', 'laporanTindakanOperasi');
            Route::get('mkko/lap-jml-pegawai', 'jmlPegawai');
            Route::post('mkko/lap-jml-pegawai', 'jmlPegawaiQuery');
            // Route::get('mkko/lap-jml-pendapatan', 'jmlPendapatanQuery');
            Route::get('mkko/lap-jml-pendapatan', 'jmlPendapatanBAHV');
            Route::post('mkko/lap-jml-pendapatan', 'jmlPendapatanQuery');
            Route::post('mkko/lap-jml-pendapatan-lain', 'jmlPendapatanLainQuery');
            Route::get('mkko/lap-beban-usaha', 'laporanBebanUsaha');
            Route::get('mkko/lap-pendapatan-keuangan', 'jmlPendapatanKeuangan');
            Route::get('mkko/lap-cashflow', 'LapCashflow');
            Route::get('mkko/lap-balance-sheet', 'lapBalanceSheet');
            Route::get('mkko/lap-wt-radiologi', 'waktuRadiologi');
            Route::get('mkko/lap-wt-laborat', 'waktuLaboratorium');
            Route::get('mkko/lap-wt-igd', 'waktuIGD');
            Route::get('mkko/lap-wt-pelayanan', 'waktuPelayanan');
            Route::get('mkko/lap-wt-ranap', 'waktuRanap');
            Route::get('mkko/lap-receivable', 'lapReceivable');
            Route::get('mkko/lap-payable', 'lapPayable');
            Route::get('mkko/lap-inventory', 'lapInventory');
            Route::get('mkko/lap-inventori', 'getPenjualan');
            Route::get('mkko/send-operasional-lain', 'apiOperasional');




            Route::post('mkko/persentase-inpatien-visit', 'persentaseInpatienQuery');
            Route::post('mkko/api-integrate', 'apiIntegrate');
            Route::post('mkko/save-borlostoi', 'saveBORLOS');
            Route::post('mkko/save-target-mkko', 'saveMKKOTarget');
        });

        Route::controller(DokterCareIntegrasiCtrl::class)->group(function () {
            Route::get('doktercare/profile-dokter', 'profileDokter');
            Route::get('doktercare/jadwal-dokter', 'jadwalDokter');
            Route::get('doktercare/data-reservasi', 'rekapHarianReservasi');
            Route::get('doktercare/data-pasien-operasi', 'rekapHarianPasienOperasi');
            Route::get('doktercare/data-remun', 'rekapHarianRemun');
            Route::get('doktercare/data-pasien-dokter', 'rekapHarianPasienDokter');
        });
        Route::controller(IGDCtrl::class)->group(function () {
            Route::get('igd/data-pasien', 'dataPasienLama');
            Route::get('igd/dokter-igd', 'getDokterIGD');

            Route::post('igd/update-triage', 'UpdateTriage');
        });
    });
});
Route::controller(NoAuthCtrl::class)->group(function () {

    Route::post('service/bridging/penunjang/update-hasil-pacs', 'saveSendBack');
});
Route::controller(BSRECtrl::class)->group(function () {
    Route::get('service/bsre-esign/bsre-cppt-ranap', 'BSRECPPTRanap');
});
Route::controller(AuthCtrl::class)->group(function () {
    Route::post('service/auth/login', 'login');
    Route::post('service/auth/pasien', 'loginPasien2');
    Route::post('service/auth/pasien-regis', 'registasiPasien');
    Route::post('service/tele/generate-token', 'getSignature2');
    Route::post('service/accesstoken', 'accessToken');
});

Route::controller(AntrianCtrl::class)->group(function () {
    Route::get('service/medifirst2000/viewer/get-data-viewer-tempat-tidur', 'getTempatTidur');
});

Route::controller(TelemedicineCtrl::class)->group(function () {
    Route::get('service/tele/get-antrian-poli', 'getAntrianPoli');
});



Route::middleware(['jwt.auth.pasien'])->prefix("service/reservasionline")->group(function () {
    Route::controller(ReservasiMobileCtrl::class)->group(function () {

        Route::get('/get-list-data', 'getComboReservasi');
        Route::get('/get-daftar-slotting', 'getDaftarSlotting');
        Route::get('/get-history', 'getHistoryReservasi');
        Route::get('/get-history-ereservasi', 'getHistoryEReservasi');
        Route::get('/get-history-all', 'getHistoryReservasiMobile');
        Route::get('/get-pasien/{nocm}/{tgllahir}', 'getPasienByNoCmTglLahir');
        Route::get('/get-libur', 'getLiburSlotting');
        Route::get('/get-bank-account', 'getNomorRekening');
        Route::get('/cek-reservasi-satu', 'cekReservasiDipoliYangSama');
        Route::get('/get-slotting-by-ruangan-new/{kode}/{tgl}', 'getSlottingByRuanganNew');
        Route::get('/get-slot-available', 'getDaftarSlottingAktif');
        Route::get('/tagihan/get-pasien/{noregistrasi}', 'getPasienByNoRegistrasi'); //done
        Route::get('/get-tagihan-pasien/{noregistasi}', 'getTagihanEbilling');
        Route::get('/get-setting', 'getSetting');
        Route::get('/daftar-riwayat-registrasi', 'getDaftarRiwayatRegistrasi');
        Route::get('/cek-pasien-baru-by-nik/{nik}', 'cekPasienByNik');
        Route::get('/get-status-va', 'getDaftarStatusVA');
        Route::get('/get-slotting-new', 'getSlottingByRuanganNew2');
        Route::get('/get-data', 'getDataReservasi');
        Route::get('/get-slotting-rev', 'getSlottingByRuanganDokter');
        Route::get('/get-dokter', 'getDokterByRuang');
        Route::get('/get-pasien-nokartu/{nocm}', 'getPasienByNoka');
        Route::get('/billing', 'billingPasien');
        Route::get('/info-bed', 'infoBed');
        Route::get('/jadwal-dokter', 'jadwalDokter');
        Route::get('/pasien-keluarga', 'getPasienAnggotaKeluarga');
        Route::get('/hubungan-keluarga', 'getHubunganKeluarga');
        Route::get('/antrian-poli', 'antrianPoli');
        Route::get('/antrian-radiologi', 'antrianRadiologi');

        Route::post('/save-slotting', 'saveSlotting');
        Route::post('/update-data-status-reservasi', 'UpdateStatConfirm');
        Route::post('/update-nocmfk-antrian-registrasi', 'updateNoCmInAntrianRegistrasi');
        Route::post('/save', 'saveReservasi');
        Route::post('/delete', 'deleteReservasi');
        Route::post('/save-libur', 'saveLibur');
        Route::post('/delete-libur', 'deleteLibur');
        Route::post('/update-tglreservasi', 'updateTglReservasi');
        Route::post('/pasien-keluarga', 'savePasienKeluarga');
        Route::post('/check-in', 'saveCheckinPasien');
    });
});

Route::controller(AntrianOnlineCtrl::class)->prefix("antrian-bpjs")->group(function () {
    Route::get('antrol/auth', 'tokenAntrean');
    Route::middleware(['jwt.auth'])->group(function () {
        Route::middleware(['log'])->group(function () {
            Route::post('antrol/antrean', 'ambilAntrean');
            Route::post('antrol/rekap', 'statusAntrean');
            Route::post('antrol/sisa', 'sisaAntrean');
            Route::post('antrol/batal', 'batalAntrean');
            Route::post('antrol/checkin', 'checkIn');
            Route::post('antrol/pasienbaru', 'pasienBaru');
            Route::post('antrol/operasi', 'jadwalOperasiPasien');
            Route::post('antrol/jadwaloperasi', 'jadwalOperasiRS');
            // farmasi
            Route::post('antrol/antrean-farmasi', 'ambilAntreanFarmasi');
            Route::post('antrol/rekap-farmasi', 'statusAntreanFarmasi');
        });
    });
});

Route::controller(HigeaCtrl::class)->prefix('service')->group(function () {
    Route::post('auth/get-access-token', 'getAccessToken');
    Route::middleware(['jwt.auth'])->group(function () {
        Route::get('list-doctor', 'getDoctor');
        Route::get('list-ruangan', 'getRuangan');
        Route::get('list-kelas', 'getKelas');
        Route::get('list-kamar-kelas', 'getKamarByKelas');
        Route::get('list-bed', 'getBed');
    });
});

Route::get('/', function () {
    return view('welcome');
});
