<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pasien;
use App\Models\Master\Ruangan;
use App\Models\Master\Profile;
use App\Models\Master\SettingDataFixed;
use App\Models\Standar\MapLoginUserToRuangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\EMRPasien;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\SuratKeterangan;
use App\Http\Controllers\Bridging\BridgingBPJSCtrl;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Exception;

class DashboardRegistrasiCtrl extends Controller
{
    use Valet;
    protected $bridgingBPJSCtrl;
    public function __construct(BridgingBPJSCtrl $bridgingBPJSCtrl)
    {
        parent::__construct($is_encrypt = true);
        $this->bridgingBPJSCtrl = $bridgingBPJSCtrl;
    }

    public function getDropdown(Request $r)
    {
        $now = $this->hari_ini(date('Y-m-d'));
        $jadwal  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->where('jd.hari', 'ilike', '%' . $now . '%')
            ->where('jd.statusenabled', '=', 'true')
            ->where('ru.statusenabled', true)
            ->where('pg.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $jadwal = $jadwal->where('ru.id', $r['ruanganid']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $jadwal = $jadwal->where('pg.namalengkap', 'ilike',  '%' . $r['namadokter'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $jadwal = $jadwal->limit($r['limit']);
        }
        $jadwal->orderBy('pg.namalengkap');
        $jadwal =  $jadwal->get();
        foreach ($jadwal as $d) {
            $d->hari = $now;
        }


        $dep = $this->settingFixMultiple(['kdDepartemenRawatJalanFix', 'kdDepartemenRanapFix']);
        
        //$setranap = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $depart = [];
            foreach ($dep as $key => $ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $k => $d) {
                    $depart[] = $d;
                }
            }
        
        // array_push()
        $set2 = explode(',', $this->settingFix('listDepartemenPelayanan'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $depart)->get();
        $res['kelompokpasien'] = KelompokPasien::mine()->get();
        $res['departemen'] = Departemen::mine()->whereIn('id', $set2)->get();
        $res['jadwaldokter'] = $jadwal;

        return $this->respond($res);
    }
    public function dashboardRegis(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $reser = DB::table('antrianpasienregistrasi_t as apr')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile);
        if (isset($r['tgl']) && $r['tgl'] != '') {
            $reser = $reser->whereBetween('tanggalreservasi', [$r['tgl'] . ' 00:00', $r['tgl'] . ' 23:59']);
        }
        // ->where('tanggalreservasi', '>=', date('Y-m-d H:i:s'));
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $reser = $reser->where('apr.objectruanganfk', $r['ruanganfk']);
        }
        $reser = $reser->get();
        $regis  =  PasienDaftar::where('statusenabled', true)
            ->where('kdprofile', $kdProfile);
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $regis = $regis->where('objectruanganlastfk', $r['ruanganfk']);
        }
        if (isset($r['tgl']) && $r['tgl'] != '') {
            $regis = $regis->whereBetween('tglregistrasi', [$r['tgl'] . ' 00:00', $r['tgl'] . ' 23:59']);
        }
        $regis = $regis->get();
        $res['c_reservasi'] = 0;
        $res['c_antrian'] = 0;
        $res['c_registrasi'] = 0;
        $res['c_dilayani'] = 0;
        foreach ($regis as $d) {
            $res['c_registrasi'] =   $res['c_registrasi'] + 1;
            if ($d->ispelayananpasien) {
                $res['c_dilayani'] =   $res['c_dilayani'] + 1;
            }
        }
        foreach ($reser as $d) {
            if ($d->iskiosk) {
                $res['c_antrian'] =  $res['c_antrian'] + 1;
            } else {
                $res['c_reservasi'] =   $res['c_reservasi'] + 1;
            }
        }
        return $this->respond($res);
    }
    public function daftarReservasi(Request $r)
    {
        $count = 0;
        $data  = DB::table('antrianpasienregistrasi_t as apr')
            ->leftjoin('pasien_m as ps', 'apr.nocmfk', '=', 'ps.id')
            ->leftjoin('ruangan_m as ru', 'apr.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'apr.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('kelompokpasien_m as kp', 'apr.objectkelompokpasienfk', '=', 'kp.id')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apr.pasiendaftarfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.norec', 'pd.noregistrasifk')
            ->select(
                'apr.*',
                'apr.pasiendaftarfk as norec_pd',
                'ps.nocm',
                'ps.noidentitas',
                'ps.nohp',
                'ps.nobpjs',
                'ps.tgllahir',
                'alm.alamatlengkap',
                'ru.namaruangan',
                'pg.namalengkap as dokter',
                'kp.kelompokpasien',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'jk.jeniskelamin',
                'pd.noregistrasi',
                'apr.noidentitas as nik',
                'pa.norujukan as norujukan',
            DB::raw("case when ps.namapasien is null then apr.namapasien else ps.namapasien end as namapasien"))
            ->where('apr.statusenabled', true)
            ->where('apr.noreservasi', '!=', '-')
            ->where('apr.kdprofile', $this->kdProfile);

        if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->whereBetween('apr.tanggalreservasi', [$r['dari'],  $r['sampai']]);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                      ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                      ->orWhere('ps.nocm', 'ilike', $searchTerm)
                      ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                      ->orWhere('apr.noreservasi', 'ilike', $searchTerm)
                      ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }
        if (isset($r['nik']) && $r['nik'] != '') {
            $data = $data->where('ps.noidentitas', '=',  $r['nik']);
        }
        if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
            $data = $data->where('ps.nobpjs', '=',  $r['nobpjs']);
        }
        if (isset($r['kelompokpasienfk']) && $r['kelompokpasienfk'] != '') {
            $data = $data->whereIN('apr.objectkelompokpasienlastfk',  explode(',', $r['kelompokpasienfk']));
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->where('apr.objectruanganfk',  $r['ruanganfk']);
        }
        if (isset($r['_total']) && $r['_total'] != '') {
            $count = $data->count();
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        $data = $data->orderBy('apr.tanggalreservasi');
        $data = $data->get();

        foreach ($data as $d) {
            $d->umur =  $this->getAge($d->tgllahir,   date('Y-m-d H:i:s'));
        }
        if (count($data) > 0) {
            $price = array();
            $data = $data->toArray();
            foreach ($data as $key => $row) {
                $price[$key] = $row->tanggalreservasi;
            }
            array_multisort($price, SORT_DESC, $data);
        }
        $res['data'] = $data;
        $res['total'] = $count;
        return $this->respond($res);
    }
    public function hapusReservasi(Request $r)
    {
        DB::beginTransaction();
        try {

            $apr =  AntrianPasienRegistrasi::where('norec', $r['norec'])->first();
            if ($apr->nocmfk != null) {
                $ps = Pasien::where('id', $apr->nocmfk)->first();
                $this->LOGGING(
                    'Reservasi Online',
                    $apr->norec,
                    'antrianpasienregistrasi_t',
                    'Hapus Reservasi pada Pasien ' .
                        $ps->namapasien . ' (' . $ps->nocm . ') - ' . $apr->noreservasi
                );
            } else {
                $this->LOGGING(
                    'Reservasi Online',
                    $apr->norec,
                    'antrianpasienregistrasi_t',
                    'Hapus Reservasi pada Pasien ' .
                        $apr->namapasien . ' - ' . $apr->noreservasi
                );
            }


            AntrianPasienRegistrasi::where('norec', $r['norec'])->update([
                'statusenabled' => false,
            ]);
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function editReservasi(Request $r) {
        DB::beginTransaction();

        $result['status'] = 400;
        $result['result'] = null;
        $result['message'] = "Telah terjadi kesalahan.";
        try {
            // $apr = AntrianPasienRegistrasi::where('norec', $r['norec'])
            //      ->leftjoin('pasiendaftar_t as pd', 'pd.norec', 'pasiendaftarfk')
            //      ->select('*', 'pd.noregistrasifk as apr_fk')
            //      ->first();
            $apr = DB::table('antrianpasienregistrasi_t as apr')
                 ->leftjoin('pasiendaftar_t as pd', 'pd.norec', 'apr.pasiendaftarfk')
                 ->select('apr.*', 'pd.noregistrasifk as apr_fk')
                 ->first();
            if(!empty($apr)) {

                $pa = PemakaianAsuransi::where('norec', $apr->apr_fk)->first();
                if(!empty($r['no_rujukan'])) {
                    $pa->norujukan = $r['no_rujukan'];
                    $pa->save();

                    $result['status'] = 200;
                    $result['result'] = ["data" => $pa];
                    $result['message'] = "Berhasil merubah data";
                    DB::commit();
                }
            }else {
                $result['status'] = 404;
                $result['message'] = "Silahkan buat rujukan terlebih dahulu";
            }
            // return "apr not empty";
        }catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function cetakLabelPasien(Request $request)
    {
        $kdProfile                  = $this->kdProfile;
        $dokter                     = $request['dokter'];
        $kelompokpasien             = $request['kelompokpasien'];
        $objectdepartemenfk         = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi              = $request['tglregistrasi'];
        $tglAyeuna                  = date('d/m/Y');
        $tglAyeuna                  = date('Y-m-d H:i:s');
        $print                      = false;
        $profile                    = Profile::where('id', $this->kdProfile)->first();
        $noregistrasi               = explode(',', $request['noregistrasi']);

        PasienDaftar::where('noregistrasi', $noregistrasi)->whereNull('tglcetak')->update(['tglcetak' => date('Y-m-d H:i:s')]);
        $dataPasien = DB::table('pasiendaftar_t AS pd')
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'pm.nohp as notelepon',
                DB::raw("'*' || pm.nocm || '*' AS barcode"),
                'jk.reportdisplay AS jeniskelamin',
                DB::raw("CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik"),
                DB::raw("to_char(pm.tgllahir, 'DD-MM-YYYY') AS tanggal_lahir"),
                // DB::raw("TO_CHAR(age(pm.tgllahir), 'YYYY thn MM bln DD hr') as umur"),
                DB::raw("EXTRACT(YEAR FROM age(pm.tgllahir)) || ' thn ' || EXTRACT(MONTH FROM age(pm.tgllahir)) || ' bln ' || EXTRACT(DAY FROM age(pm.tgllahir)) || ' hr' as umur"),
                DB::raw("CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin"),
                'pd.tglregistrasi',
                'pg.namalengkap as dokter',
                'alm.alamatlengkap',
                'dep.namadepartemen'
            )
            ->join('pasien_m AS pm', 'pd.nocmfk', 'pm.id')
            ->leftJoin('jeniskelamin_m AS jk', 'jk.id', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'pm.id')
            ->join('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->join('departemen_m AS dep', 'dep.id', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
            ->where('pd.noregistrasi', $noregistrasi)
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $pageWidth = 250;

        $dataReport = array(
            'data' => $dataPasien,
        );
        $request['pdf']  = true;
        $judul = 'Cetak Label Pasien';
        $blade = 'report.registrasi.cetak-label-pasien';
        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 170, 80], 'potrait');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $request,
                    'dataPasien' => $dataPasien
                )
            );
            return $pdf->stream();
        }
        return view(
            $blade,
            compact('dataPasien', 'pageWidth', 'request')
        );
    }


    public function cetakIdentitasPasien(Request $request)
    {
        $kdProfile                  = $this->kdProfile;
        $dokter                     = $request['dokter'];
        $kelompokpasien             = $request['kelompokpasien'];
        $jenis                      = $request['jenis'];
        $objectdepartemenfk         = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi              = $request['tglregistrasi'];
        $tglAyeuna                  = date('d/m/Y');
        $tglAyeuna                  = date('Y-m-d H:i:s');
        $print                      = false;
        $profile                    = Profile::where('id', $this->kdProfile)->first();
        $noregistrasi               = explode(',', $request['noregistrasi']);


        if ($jenis == "onkologiRadiasi") {
            $dataPasien = DB::table('pasiendaftar_t AS pd')
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'pd.noregistrasi',
                'pm.tempatlahir',
                'pm.nobpjs',
                'ag.agama',
                'spk.statusperkawinan',
                'pnd.pendidikan',
                'pkj.pekerjaan',
                'pm.nohp as notelepon',
                'kb.name as kebangsaan',
                'des.namadesakelurahan',
                'kec.namakecamatan',
                'kab.namakotakabupaten',
                'prov.namapropinsi',
                'alm.alamatlengkap',
                'pm.nohp',
                'kp.kelompokpasien',
                'kls.namakelas',
                'ru.namaruangan',
                'pm.penanggungjawab',
                'pm.hubungankeluargapj',
                'pm.telponpenanggungjawab',
                'hk.hubungankeluarga',
                'pm.alamatrmh',
                'pm.filename',
                'ng.namanegara',
                'pm.id',
                DB::raw("'*' || pm.nocm || '*' AS barcode"),
                'jk.reportdisplay AS jeniskelamin',
                DB::raw("CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik"),
                DB::raw("to_char(pm.tgllahir, 'DD-MM-YYYY') AS tanggal_lahir"),
                DB::raw("to_char(pd.tglregistrasi, 'DD-MM-YYYY') AS tglregistrasi"),
                DB::raw("TO_CHAR(age(pm.tgllahir), 'YY thn MM bln DD hr') as umur"),
                DB::raw("CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin"),
                'pd.tglregistrasi',
                'pg.namalengkap as dokter',
                'dep.namadepartemen'
            )
            ->join('pasien_m AS pm', 'pd.nocmfk', 'pm.id', 'pm.filename')
            ->leftJoin('jeniskelamin_m AS jk', 'jk.id', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'pm.id')
            ->leftJoin('statusperkawinan_m AS spk', 'spk.id', 'pm.objectstatusperkawinanfk')
            ->leftJoin('agama_m AS ag', 'ag.id', 'pm.objectagamafk')
            ->leftJoin('kebangsaan_m AS kb', 'kb.id', 'pm.objectkebangsaanfk')
            ->leftJoin('negara_m AS ng', 'ng.id', 'pm.objectnegarafk')
            ->leftJoin('pekerjaan_m AS pkj', 'pkj.id', 'pm.objectpekerjaanfk')
            ->leftJoin('pendidikan_m AS pnd', 'pnd.id', 'pm.objectpendidikanfk')
            ->leftJoin('desakelurahan_m AS des', 'alm.objectdesakelurahanfk', 'des.id')
            ->leftJoin('kecamatan_m AS kec', 'alm.objectkecamatanfk', 'kec.id')
            ->leftJoin('kotakabupaten_m AS kab', 'alm.objectkotakabupatenfk', 'kab.id')
            ->leftJoin('propinsi_m AS prov', 'alm.objectpropinsifk', 'prov.id')
            // ->leftJoin('kelas_m AS kls', 'pd.objectkelasfk', 'kls.id')
            ->leftJoin('hubungankeluarga_m AS hk', 'hk.id', 'pm.hubungankeluargapj')
            ->join('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->join('departemen_m AS dep', 'dep.id', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
            ->leftJoin('pemakaianasuransi_t AS pa', 'pa.nokartu', 'pm.nobpjs')
            ->leftJoin('kelas_m AS kls', 'kls.id', 'pa.kelasfk')
            ->where('pd.noregistrasi', $noregistrasi)
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $pageWidth = 500;

        $dataReport = array(
            'data' => $dataPasien,
            'kelas' => $request['hakKelas'],
            'statusbpjs' => $request['statusbpjs'] ?? '',
        );
        $request['pdf']  = true;
        $judul = 'Cetak Label Pasien';
        $blade = 'report.registrasi.cetak-identitas-pasien-onkologi-radiasi';
        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'potrait');
            $pdf->loadView(
                $blade,
                array(
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => $request,
                    'dataPasien' => $dataPasien,
                    'dataReport' => $dataReport
                )
            );
            return $pdf->stream();
        }
        return view(
            $blade,
            compact('dataPasien', 'pageWidth', 'request', 'profile', 'dataReport')
        );
        } else {
            $dataPasien = DB::table('pasiendaftar_t AS pd')
                ->select(
                    'pm.nocm',
                    'pm.namapasien',
                    'pm.tempatlahir',
                    'pm.nobpjs',
                    'ag.agama',
                    'spk.statusperkawinan',
                    'pnd.pendidikan',
                    'pkj.pekerjaan',
                    'pm.nohp as notelepon',
                    'kb.name as kebangsaan',
                    'des.namadesakelurahan',
                    'kec.namakecamatan',
                    'kab.namakotakabupaten',
                    'prov.namapropinsi',
                    'alm.alamatlengkap',
                    'pm.nohp',
                    'kp.kelompokpasien',
                    'kls.namakelas',
                    'ru.namaruangan',
                    'pm.penanggungjawab',
                    'pm.hubungankeluargapj',
                    'pm.telponpenanggungjawab',
                    'hk.hubungankeluarga',
                    'pm.alamatrmh',
                    'pm.filename',
                    'ng.namanegara',
                    'pm.id',
                    DB::raw("'*' || pm.nocm || '*' AS barcode"),
                    'jk.reportdisplay AS jeniskelamin',
                    DB::raw("CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik"),
                    DB::raw("to_char(pm.tgllahir, 'DD-MM-YYYY') AS tanggal_lahir"),
                    DB::raw("to_char(pd.tglregistrasi, 'DD-MM-YYYY') AS tglregistrasi"),
                    DB::raw("TO_CHAR(age(pm.tgllahir), 'YY thn MM bln DD hr') as umur"),
                    DB::raw("CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin"),
                    'pd.tglregistrasi',
                    'pg.namalengkap as dokter',
                    'dep.namadepartemen'
                )
                ->join('pasien_m AS pm', 'pd.nocmfk', 'pm.id', 'pm.filename')
                ->leftJoin('jeniskelamin_m AS jk', 'jk.id', 'pm.objectjeniskelaminfk')
                ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'pm.id')
                ->leftJoin('statusperkawinan_m AS spk', 'spk.id', 'pm.objectstatusperkawinanfk')
                ->leftJoin('agama_m AS ag', 'ag.id', 'pm.objectagamafk')
                ->leftJoin('kebangsaan_m AS kb', 'kb.id', 'pm.objectkebangsaanfk')
                ->leftJoin('negara_m AS ng', 'ng.id', 'pm.objectnegarafk')
                ->leftJoin('pekerjaan_m AS pkj', 'pkj.id', 'pm.objectpekerjaanfk')
                ->leftJoin('pendidikan_m AS pnd', 'pnd.id', 'pm.objectpendidikanfk')
                ->leftJoin('desakelurahan_m AS des', 'alm.objectdesakelurahanfk', 'des.id')
                ->leftJoin('kecamatan_m AS kec', 'alm.objectkecamatanfk', 'kec.id')
                ->leftJoin('kotakabupaten_m AS kab', 'alm.objectkotakabupatenfk', 'kab.id')
                ->leftJoin('propinsi_m AS prov', 'alm.objectpropinsifk', 'prov.id')
                // ->leftJoin('kelas_m AS kls', 'pd.objectkelasfk', 'kls.id')
                ->leftJoin('hubungankeluarga_m AS hk', 'hk.id', 'pm.hubungankeluargapj')
                ->join('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
                ->join('departemen_m AS dep', 'dep.id', 'ru.objectdepartemenfk')
                ->join('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
                ->leftJoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
                ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
                ->leftJoin('pemakaianasuransi_t AS pa', 'pa.nokartu', 'pm.nobpjs')
                ->leftJoin('kelas_m AS kls', 'kls.id', 'pa.kelasfk')
                ->where('pd.noregistrasi', $noregistrasi)
                ->where('pd.kdprofile', $kdProfile)
                ->where('pd.statusenabled', true)
                ->first();
    
            $pageWidth = 500;
    
            $dataReport = array(
                'data' => $dataPasien,
                'kelas' => $request['hakKelas'],
                'statusbpjs' => $request['statusbpjs'] ?? '',
            );
            $request['pdf']  = true;
            $judul = 'Cetak Label Pasien';
            $blade = 'report.registrasi.cetak-identitas-pasien';
            if ($request['pdf']) {
                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper([0, 0, 595.28, 941.89], 'potrait');
                $pdf->loadView(
                    $blade,
                    array(
                        'pageWidth' => $pageWidth,
                        'profile' => $profile,
                        'res' => $request,
                        'dataPasien' => $dataPasien,
                        'dataReport' => $dataReport
                    )
                );
                return $pdf->stream();
            }
            return view(
                $blade,
                compact('dataPasien', 'pageWidth', 'request', 'profile', 'dataReport')
            );
        }

    }

    public function cetakLabelODC(Request $request)
    {
        $kdProfile                  = $this->kdProfile;
        $dokter                     = $request['dokter'];
        $kelompokpasien             = $request['kelompokpasien'];
        $objectdepartemenfk         = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi              = $request['tglregistrasi'];
        $tglAyeuna                  = date('d/m/Y');
        $tglAyeuna                  = date('Y-m-d H:i:s');
        $print                      = false;
        $profile                    = Profile::where('id', $this->kdProfile)->first();
        $noregistrasi               = explode(',', $request['noregistrasi']);
        $dataPasien = DB::table('pasiendaftar_t AS pd')
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'pm.nohp as notelepon',
                DB::raw("'*' || pm.nocm || '*' AS barcode"),
                'jk.reportdisplay AS jeniskelamin',
                DB::raw("CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik"),
                DB::raw("to_char(pm.tgllahir, 'DD-MM-YYYY') AS tanggal_lahir"),
                DB::raw("EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur"),
                DB::raw("CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin"),
                'pd.tglregistrasi',
                'pg.namalengkap as dokter',
                'dep.namadepartemen'
            )
            ->join('pasien_m AS pm', 'pd.nocmfk', 'pm.id')
            ->leftJoin('jeniskelamin_m AS jk', 'jk.id', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'pm.id')
            ->join('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->join('departemen_m AS dep', 'dep.id', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
            ->where('pd.noregistrasi', $noregistrasi)
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $pageWidth = 850;

        $dataReport = array(
            'data' => $dataPasien,
        );
        $request['pdf']  = true;
        $judul = 'Cetak Label Pasien';
        $blade = 'report.registrasi.cetak-label-pasien-odc';
        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $request,
                    'dataPasien' => $dataPasien
                )
            );
            return $pdf->stream();
        }
        return view(
            $blade,
            compact('dataPasien', 'pageWidth', 'request')
        );
    }
    public function cetakKartuPasien(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $noregistrasi = '';
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != 'undefined' && $request['noregistrasi'] != '') {
            $noregistrasi = "and (pd.noregistrasi = '".$request['noregistrasi']."' or pm.nocm = '".$request['noregistrasi']."')";
        }

        $norec_pd = '';
        if (isset($request['norec_pd']) && $request['norec_pd'] != 'undefined' && $request['norec_pd'] != '') {
            $norec_pd = "and pd.norec = '".$request['norec_pd']."'";
        }

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            '*' || pm.nocm || '*' AS barcode,
            jk.reportdisplay AS jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS tanggal_lahir,
            EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur,
            pd.tglregistrasi,
            alm.alamatlengkap as alamatlengkap,
            des.namadesakelurahan as desa,
            kec.namakecamatan as kecamatan,
            kab.namakotakabupaten as kota,
            prov.namapropinsi as province
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = pm.id and  alm.kdprofile = pm.kdprofile
            LEFT JOIN desakelurahan_m AS des ON alm.objectdesakelurahanfk = des.id
            LEFT JOIN kecamatan_m AS kec ON alm.objectkecamatanfk = kec.id
            LEFT JOIN kotakabupaten_m AS kab ON alm.objectkotakabupatenfk = kab.id
            LEFT JOIN propinsi_m AS prov ON alm.objectpropinsifk = prov.id

        WHERE pd.statusenabled=true
            $noregistrasi
            $norec_pd
            and pd.kdprofile =$kdProfile
        ");
        $pageWidth = 950;
        $dataReport = array(
            'data' => $dataPasien
        );

        $judul = 'Cetak Kartu Pasien';

        // dd($dataReport['data']);

        $blade = 'report.registrasi.cetak-kartu-pasien';

        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 200, 300], 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'judul' => $judul,
                )
            );
            return $pdf->stream();
            // return view(
            //     $blade,
            //     compact('dataReport', 'profile', 'pageWidth', 'print', 'judul')
            // );
        } else{
            return view(
                $blade,
                compact('dataReport', 'profile', 'pageWidth', 'print', 'judul')
            );
        }


        // dd($dataReport);
    }
    public function cetakKartuPasienWeb(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $responseRujukan = [];
        $responseRujukan2 = [];
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $noregistrasi = '';
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != 'undefined' && $request['noregistrasi'] != '') {
            $noregistrasi = "and (pm.nocm = '".$request['noregistrasi']."' or pm.noidentitas = '".$request['noregistrasi']."' or pm.nobpjs = '".$request['noregistrasi']."')";
        }

        $tgllahir = '';
        if (isset($request['tgllahir']) && $request['tgllahir'] != 'undefined' && $request['tgllahir'] != '') {
            $tgllahir = "and pm.tgllahir = '".$request['tgllahir']."'";
        }

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            pm.nobpjs,
            '*' || pm.nocm || '*' AS barcode
        FROM pasien_m as pm
        WHERE pm.statusenabled=true
            $noregistrasi
            $tgllahir
            and pm.kdprofile =$kdProfile
        ");

        $pageWidth = 950;
        $dataReport = $dataPasien;

        $judul = 'Cetak Kartu Pasien';

        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['url'] = "Rujukan/List/Peserta/" . $dataPasien[0]->nobpjs;
        $objetoRequest['method'] = "GET";
        $objetoRequest['data'] = null;
        $cariSEP =  $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
        $responseSEPVc = json_decode(json_encode($cariSEP, false));

        $objetoRequest2 = new \Illuminate\Http\Request();
        $objetoRequest2['url'] = "Rujukan/RS/List/Peserta/" . $dataPasien[0]->nobpjs;
        $objetoRequest2['method'] = "GET";
        $objetoRequest2['data'] = null;
        $cariSEP2 =  $this->bridgingBPJSCtrl->bpjsTools($objetoRequest2, true);
        $responseSEPVc2 = json_decode(json_encode($cariSEP2, false));

        // var_dump($responseSEPVc->response->rujukan);

        if (isset($responseSEPVc->metaData) && $responseSEPVc->metaData->code == 200) {
            
            $responseRujukan = $responseSEPVc->response->rujukan;
        }

        if (isset($responseSEPVc2->metaData) && $responseSEPVc2->metaData->code == 200) {
            
            $responseRujukan2 = $responseSEPVc2->response->rujukan;
        }

        if ($dataPasien == []) {
            $transMessage = "Data Tgl Lahir atau No RM tidak sesuai";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => []
            );
            return $this->respond($result['result'], $result['status'], $transMessage);
        }

        // dd($dataReport['data']);

        $blade = 'report.registrasi.cetak-kartu-pasien-web';

        if ($request['pdf'] == "true") {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 180, 300], 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'rujukan' => $responseRujukan,
                    'rujukan2' => $responseRujukan2,
                    'backgroundColor' => '#6dc7ed',
                    'judul' => $judul,
                )
            );
            // return $pdf->stream($dataPasien[0]->nocm."-".$dataPasien[0]->namapasien.".pdf", array("Attachment" => false));
            return $pdf->download($dataPasien[0]->nocm."-".$dataPasien[0]->namapasien.".pdf");
            // return view(
            //     $blade,
            //     compact('dataReport', 'profile', 'pageWidth', 'print', 'judul')
            // );
        } else{
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 180, 300], 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'rujukan' => $responseRujukan,
                    'rujukan2' => $responseRujukan2,
                    'backgroundColor' => '#6dc7ed',
                    'judul' => $judul,
                )
            );
            return $pdf->stream($dataPasien[0]->nocm."-".$dataPasien[0]->namapasien.".pdf", array("Attachment" => false));
        }


        // dd($dataReport);
    }

    public function cetakBuktiReservasiWeb(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];

        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'apr.nocmfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('jeniskelamin_m as jks', 'jks.id', '=', 'apr.objectjeniskelaminfk')
            ->leftJoin('pekerjaan_m as pk', 'pk.id', '=', 'pm.objectpekerjaanfk')
            ->leftJoin('pendidikan_m as pdd', 'pdd.id', '=', 'pm.objectpendidikanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apr.objectpegawaifk')
            ->leftJoin('jadwaldokter_m as jd', function ($join) {
                $join->on('jd.objectruanganfk', '=', 'apr.objectruanganfk')
                ->whereRaw("to_char(apr.tanggalreservasi,'YYYY-MM-DD') = to_char(jd.tanggal,'YYYY-MM-DD')");
            })
            ->join('kelompokpasien_m as kps', 'kps.id', '=', 'apr.objectkelompokpasienfk')
            ->select(
                'apr.norec',
                'pm.nocm',
                'pm.nobpjs',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                DB::raw("jd.jammulai || ' - ' || jd.jamakhir as jamreservasi"),
                'apr.objectruanganfk',
                'apr.objectpegawaifk',
                'ru.namaruangan',
                'apr.isconfirm',
                'apr.noantrian',
                'apr.noantrianpoli',
                'pg.namalengkap as dokter',
                'pm.id as nocmfk',
                'pk.pekerjaan',
                'pm.noasuransilain',
                'pdd.pendidikan',
                'apr.type',
                'kps.kelompokpasien',
                'apr.objectkelompokpasienfk',
                'ru.objectdepartemenfk',
                'ru.prefixnoantrian',
                'apr.norujukan',
                'ru.id as idruangan',
                'pg.id as iddokter',
                'apr.jenis',
                DB::raw("case when apr.isconfirm=true then 'Confirm' else 'Reservasi' end as status,
                case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when pm.noidentitas is null then apr.noidentitas else pm.noidentitas end as noidentitas,
                case when pm.nobpjs is null then apr.nobpjs else pm.nobpjs end as nobpjs,
                case when pm.namapasien is null then apr.namapasien else pm.namapasien end as namapasien,
                case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when pm.objectjeniskelaminfk is null then apr.objectjeniskelaminfk else pm.objectjeniskelaminfk end as objectjeniskelaminfk,
                case when pm.nohp is null then apr.notelepon else pm.nohp end as notelepon,
                case when pm.email is null then apr.email else pm.email end as email,
                case when alm.alamatlengkap is null then apr.alamatlengkap else alm.alamatlengkap end as alamatlengkap,
                case when pm.objectkebangsaanfk is null then apr.objectkebangsaanfk else pm.objectkebangsaanfk end as objectkebangsaanfk,
                case when pm.objectagamafk is null then apr.objectagamafk else pm.objectagamafk end as objectagamafk,
                case when jk.jeniskelamin is null then jks.jeniskelamin else jk.jeniskelamin end as jeniskelamin,
                case when pm.tgllahir is null then to_char(apr.tgllahir,'YYYY-MM-DD') else to_char(pm.tgllahir,'YYYY-MM-DD') end as tgllahir, 
                apr.loketkiosk")
            )
            ->whereNotNull('apr.noreservasi')
            ->whereNull('apr.noantrian')
            ->where('apr.kdprofile',  $kdProfile)
            ->where('apr.norec',  $norec)
            ->where('apr.statusenabled', true)
            ->get();

        $blade = 'report.registrasi.cetak-bukti-reservasi-web';

        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 300, 500], 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'data' => $data,
                    'backgroundColor' => '#6dc7ed',
                )
            );
            return $pdf->stream();
        } else{
            return view(
                $blade,
                compact('data')
            );
        }


        // dd($dataReport);
    }

    public function getDataSuratKeteranganDokterAsli(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            pm.tempatlahir,
            '*' || pm.nocm || '*' AS barcode,
            jk.reportdisplay AS jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS tanggal_lahir,
            pm.tgllahir,
            EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur,
            CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin,
            kp.kelompokpasien,
            alm.alamatlengkap,
            pd.tglregistrasi,
			dds.keterangan,
			dam.reportdisplay,
            pg.namalengkap
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = pm.id and  alm.kdprofile = pm.kdprofile
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk and  pd.kdprofile = ru.kdprofile
            INNER JOIN departemen_m AS dep ON dep.ID = ru.objectdepartemenfk and  ru.kdprofile = dep.kdprofile
            INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk and  pd.kdprofile = kp.kdprofile
            LEFT JOIN rekanan_m as rk on rk.id = pd.objectrekananfk and  pd.kdprofile = rk.kdprofile
            LEFT JOIN detaildiagnosapasien_t as dds on dds.noregistrasi = pd.noregistrasi
            LEFT JOIN diagnosa_m as dam on dam.id = dds.objectdiagnosafk
            LEFT JOIN pegawai_m as pg on pg.id = pd.objectpegawaifk
        WHERE
            pd.noregistrasi = '$request[noregistrasi]'
            and pd.kdprofile =$kdProfile
            and pd.statusenabled=true
        ");

        $pageWidth = 950;

        $dataReport = array(
            'data' => $dataPasien,
            'dokter' => $dokter,
            'kelompokpasien' => $kelompokpasien,
            'objectdepartemenfk' => $objectdepartemenfk,
            'tglregistrasi' => $tglregistrasi,
        );

        $res['pdf']  = false;

        $judul = 'Cetak Keterangan Dokter';

        // dd($dataReport);

        $blade = 'report.registrasi.cetak-surat-keterangan-dokter';
        if ($res['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }


        // dd($dataReport);

        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res', 'judul')
        );
    }
    public function getDataSuratKeteranganKeluar(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            pm.tempatlahir,
            '*' || pm.nocm || '*' AS barcode,
            jk.reportdisplay AS jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS tanggal_lahir,
            pm.tgllahir,
            EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur,
            CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin,
            kp.kelompokpasien,
            alm.alamatlengkap,
            pd.tglregistrasi,
			dds.keterangan,
			dam.reportdisplay
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = pm.id and  alm.kdprofile = pm.kdprofile
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk and  pd.kdprofile = ru.kdprofile
            INNER JOIN departemen_m AS dep ON dep.ID = ru.objectdepartemenfk and  ru.kdprofile = dep.kdprofile
            INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk and  pd.kdprofile = kp.kdprofile
            LEFT JOIN rekanan_m as rk on rk.id = pd.objectrekananfk and  pd.kdprofile = rk.kdprofile
            LEFT JOIN detaildiagnosapasien_t as dds on dds.noregistrasi = pd.noregistrasi
            LEFT JOIN diagnosa_m as dam on dam.id = dds.objectdiagnosafk
        WHERE
            pd.noregistrasi = '$request[noregistrasi]'
            and pd.kdprofile =$kdProfile
            and pd.statusenabled=true
        ");

        $pageWidth = '950px';

        $dataReport = array(
            'data' => $dataPasien,
            'dokter' => $dokter,
            'kelompokpasien' => $kelompokpasien,
            'objectdepartemenfk' => $objectdepartemenfk,
            'tglregistrasi' => $tglregistrasi
        );

        $res['pdf']  = false;

        $judul = 'Cetak Rujukan Keluar';

        // dd($dataReport);

        $blade = 'report.registrasi.cetak-surat-pengantar-keluar';
        if ($res['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }


        // dd($dataReport);

        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res', 'judul')
        );
    }
    public function getDataBillingBpjs(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            '*' || pm.nocm || '*' AS barcode,
            jk.reportdisplay AS jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS tanggal_lahir,
            EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur,
            CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin,
            kp.kelompokpasien,
            alm.alamatlengkap,
            pd.tglregistrasi
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = pm.id and  alm.kdprofile = pm.kdprofile
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk and  pd.kdprofile = ru.kdprofile
            INNER JOIN departemen_m AS dep ON dep.ID = ru.objectdepartemenfk and  ru.kdprofile = dep.kdprofile
            INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk and  pd.kdprofile = kp.kdprofile
            LEFT JOIN rekanan_m as rk on rk.id = pd.objectrekananfk and  pd.kdprofile = rk.kdprofile
        WHERE
            pd.noregistrasi = '$request[noregistrasi]'
            and pd.kdprofile =$kdProfile
            and pd.statusenabled=true
        ");

        $pageWidth = 950;

        $dataReport = array(
            'data' => $dataPasien,
            'dokter' => $dokter,
            'kelompokpasien' => $kelompokpasien,
            'objectdepartemenfk' => $objectdepartemenfk,
            'tglregistrasi' => $tglregistrasi
        );

        $res['pdf']  = false;
        $res['indo'] = true;
        $judul = 'Cetak Label Pasien';

        // dd($dataReport);

        $blade = 'report.registrasi.cetak-billing-bpjs';
        if ($res['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }


        // dd($dataReport);

        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res', 'judul')
        );
    }

    public function saveBatalRegis(Request $request)
    {

        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $deleteAdminAuto = PelayananPasien::where('noregistrasifk', $r_NewAPD['norec_apd'])
            ->where('statusenabled', true)
            ->where('isadministrasi', true)
            ->get();

            foreach ($deleteAdminAuto as $item) {
                PelayananPasienDetail::where('pelayananpasien',$item->norec)->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasienDetail::where('pelayananpasien',$item->norec)->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasien::where('norec',$item->norec)->where('kdprofile', $this->kdProfile)->delete();
            }
            $cekPP = PelayananPasien::where('noregistrasifk', $r_NewAPD['norec_apd'])->where('statusenabled', true)->first();
            if ($cekPP) {
                $message = 'Pasien sudah mendapatkan Pelayanan ';
                DB::rollBack();

                $result = array(
                    "status" => 400,
                    "message" => $message,
                    "result"  => $cekPP
                );

                 return $this->respond($result['result'], $result['status'], $result['message']);
            } else {

                AntrianPasienDiperiksa::where('noregistrasifk', $r_NewPD['norec_pd'])
                    // ->whereNotNull('objectruanganasalfk')
                    ->where('kdprofile', $kdProfile)
                    ->update(['statusenabled' => false]);

                PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                    'tanggalpembatalan' => date('Y-m-d H:i:s'),
                    'alasanpembatalan' => $r_NewPD['alasanpembatalan'],
                    'statusenabled' => false,
                    'objectpegawaibatalfk' => $this->getPegawaiId(),
                ]);


                $message = 'Berhasil Batal Registrasi';
                $this->LOGGING(
                    'Batal Registrasi',
                    $r_NewPD['norec_pd'],
                    'pasiendaftar_t',
                    'Batal Registrasi ' . $r_NewPD['ruangan'] .' pada Pasien ' .
                    $r_NewPD['namapasien'] . ' (' . $r_NewPD['nocm'] . ') - ' . $r_NewPD['noregistrasi']
                );
            }
            $post = null;
            $post2 = null;
            $pasienDaftar = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();
            if ($pasienDaftar->antrianpasienregistrasifk != null) {
                $apr = AntrianPasienRegistrasi::where('norec', $pasienDaftar->antrianpasienregistrasifk)->first();
                $apr->statusenabled = false;
                $apr->save();
                $waktukirim = strtotime(date('Y-m-d H:i:s')) * 1000;
                $json = array(
                    "kodebooking" => $apr->noreservasi,
                    "taskid" => 99, //pasien lama langsung task 99 //(tidak hadir/batal)
                    "waktu" => $waktukirim,
                );
                $post = $this->buildRequestbpjstools("antrean/updatewaktu", "antrean", "POST", $json);

                $jsons = array(
                    "kodebooking" => $apr->noreservasi,
                    "keterangan" => "Terjadi perubahan jadwal dokter, silahkan daftar kembali",
                );
                $post2 = $this->buildRequestbpjstools("antrean/batal", "antrean", "POST", $jsons);
            }
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $message,
                "result" => array(
                    'antrol' => $post,
                    'postKeMJKN' => $post2
                )
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function SaveSuratRegisRanap(Request $request)
    {

        DB::beginTransaction();
        // return $request['bantuanPelayanan'];
        try {
            PasienDaftar::where('norec', $request['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'bantuanpelayanan' => $request['bantuanPelayanan'],
                        'bantuanpenerjemah' => $request['bantuanPenerjemah'],
                        'dikunjungi' => $request['dikunjungi'],
                        'bahasa' => $request['bahasa']
                    ]
                );
            $data =  PasienDaftar::where('norec', $request['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->first();
            DB::commit();
            $respond = [
                "status" => 200,
                "message" => "Berhasil",
                "data" => $data,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $respond = [
                "status" => 401,
                "message" => "Simpan Gagal !",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }
    // START fungsi pendukung
    public function buildRequestbpjstools($url, $jenis, $method, $data)
    {
        $buildReq = new Request();
        $buildReq['url'] = $url;
        $buildReq['jenis'] = $jenis;
        $buildReq['method'] = $method;
        $buildReq['data'] = $data;
        $buildReq['user'] = $this->getNamaPegawai();
        $postBuild = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->bpjsTools($buildReq, false);
        $postBuild = json_decode($postBuild->content(), true);
        return $postBuild;
    }

    public function getAPD(Request $request)
    {
        $apd = DB::table('antrianpasiendiperiksa_t AS apd')
            ->select('apd.norec as norec_apd', 'tglkeluar', 'tglmasuk')
            ->where('apd.noregistrasifk', $request['norec_pd'])
            ->where('apd.objectruanganfk', $request['objectruanganlastfk'])
            ->orderByDesc('tglmasuk')
            ->first();

        return $this->respond($apd);
    }

    public function getAPDRuangan(Request $request)
    {
        $apd = DB::table('antrianpasiendiperiksa_t AS apd')
            ->select('apd.norec as norec_apd', 'apd.objectruanganfk', 'tglkeluar', 'tglmasuk')
            ->where('apd.noregistrasifk', $request['norec_pd'])
            ->orderByDesc('tglmasuk')
            ->get();

        return $this->respond($apd);
    }

    public function saveMergeNoRM(Request $request)
    {
        DB::beginTransaction();

        try {
            Pasien::where('nocm', $request['normAsal'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            $pasienTujuan = Pasien::where('nocm', $request['normTujuan'])
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->first();

            PasienDaftar::where('nocmfk',  $request['normTujuan'])->where('kdprofile', $this->kdProfile)->update(['nocmfk' =>  $pasienTujuan->id]);
            EMRPasien::where('nocm',  $request['normAsal'])->where('kdprofile', $this->kdProfile)->update(['nocm' =>  $pasienTujuan->nocm]);
            // $rekamMedis = RekamMedis::where('nocm',  $request['rmAsal'])->where('kdprofile', $this->kdProfile)->update([
            //     'nocm' =>  $pasienTujuan->nocm
            // ]);

            DB::commit();
            $response = [
                "status" => 200,
                "message" => "Gabung No RM Berhasil",
                "data" => $pasienTujuan,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "status" => 400,
                "message" => "Simpan Gagal",
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'],$response['status'],$response['message']);

    }
}
