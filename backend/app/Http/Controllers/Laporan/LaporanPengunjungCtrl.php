<?php

namespace App\Http\Controllers\laporan;

use App\Datatrans\PasienDaftar;
use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Master\SlottingKiosk;
use App\Models\Transaksi\LoggingUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPengunjungCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getLaporanPengunjungPemeriksaan(Request $request)
    {
        ini_set('max_execution_time', 6000);
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('agama_m as ag', 'ag.id', '=', 'ps.objectagamafk')
            ->leftjoin('pendidikan_m as pdd', 'pdd.id', '=', 'ps.objectpendidikanfk')
            ->leftjoin('pekerjaan_m as pkr', 'pkr.id', '=', 'ps.objectpekerjaanfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('desakelurahan_m as dsk', 'dsk.id', '=', 'alm.objectdesakelurahanfk')
            ->leftjoin('kotakabupaten_m as kkb', 'kkb.id', '=', 'alm.objectkotakabupatenfk')
            ->leftjoin('statusperkawinan_m as sp', 'sp.id', '=', 'ps.objectstatusperkawinanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->join('ruangan_m as rg', 'rg.id', '=', 'apd.objectruanganfk')
            ->leftjoin('logginguser_t AS lg', function ($join) {
                $join->on('lg.noreff', '=', 'pd.norec')
                    ->on('lg.kdprofile', '=', 'pd.kdprofile')
                    ->where('lg.jenislog', '=', 'Pendaftaran Pasien');
            })
            ->leftJoin('loginuser_s AS lu', 'lu.id', '=', 'lg.objectloginuserfk')
            ->leftJoin('pegawai_m AS pg1', 'pg1.id', '=', 'lu.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', '=', 'pp.pelayananpegawaifk')
            ->select(
                'pd.norec',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ps.nohp',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'ag.agama',
                'pdd.pendidikan',
                'pkr.pekerjaan',
                'alm.alamatlengkap',
                'dsk.namadesakelurahan',
                'alm.kecamatan',
                'kkb.namakotakabupaten',
                'sp.statusperkawinan',
                'pd.statuspasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'ps.tgldaftar',
                'pa.nosep',
                'klp.kelompokpasien',
                'pd.statuspasien',
                'apd.noantrian',
                'pg1.namalengkap AS user',
                'apd.objectruanganfk',
                'jk.reportdisplay',
                'pg2.namalengkap as pelaksana'
            )
            ->groupBy(
                'pd.tglregistrasi',
                'pd.norec',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ps.nohp',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'ag.agama',
                'pdd.pendidikan',
                'pkr.pekerjaan',
                'alm.alamatlengkap',
                'dsk.namadesakelurahan',
                'alm.kecamatan',
                'kkb.namakotakabupaten',
                'sp.statusperkawinan',
                'pd.statuspasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'ps.tgldaftar',
                'klp.kelompokpasien',
                'pd.statuspasien',
                'apd.noantrian',
                'user',
                'pg1.namalengkap',
                'apd.objectruanganfk',
                'jk.reportdisplay',
                'pa.nosep',
                'pg2.namalengkap'

            )
            ->where('pd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('pd.kdprofile', $this->kdProfile);

        if (isset($request['pelaksana']) && $request['pelaksana'] != "" && $request['pelaksana'] != "undefined") {
            $data = $data->where('pg2.id', '=', $request['pelaksana']);
        }
        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rg.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $data = $data->where('pd.objectkelompokpasienlastfk', '=', $request['kpid']);
        }
        $data = $data->orderBy('pd.tglregistrasi', 'asc'); // Urutkan terlebih dahulu berdasarkan tglregistrasi
        // $data = $data->orderBy('pd.norec', 'asc'); // Lalu urutkan berdasarkan norec
        $data = $data->distinct('pd.tglregistrasi'); // Tambahkan distinct setelah pengurutan

        $data = $data->get();

        foreach ($data as $kd => $d) {
            $d->jamregis = Carbon::parse($d->tglregistrasi)->format('H:i');
            $d->tglregistrasi = Carbon::parse($d->tglregistrasi)->format('d-m-Y');
            $d->bayar = 0;
        }

        // $diagnosa = DB::table('antrianpasiendiperiksa_t AS apd')
        //     ->join('detaildiagnosapasien_t AS ddp', 'ddp.noregistrasifk', '=', 'apd.norec')
        //     ->join('diagnosapasien_t AS dp', 'dp.norec', '=', 'ddp.objectdiagnosapasienfk')
        //     ->join('diagnosa_m as dg', 'ddp.objectdiagnosafk', '=', 'dg.id')
        //     ->select(DB::raw("apd.noregistrasifk,ddp.objectjenisdiagnosafk,dg.kddiagnosa AS diagnosa,
        //                     CASE WHEN dp.iskasusbaru = true AND dp.iskasuslama = false THEN 'BARU'
        //                     WHEN dp.iskasuslama = true AND dp.iskasusbaru = false THEN 'LAMA' ELSE '' END kasus"))
        //     ->where('apd.kdprofile', $this->kdProfile)
        //     ->where('apd.statusenabled', true)
        //     ->whereBetween(DB::raw("CAST(apd.tglregistrasi AS DATE)"), $rangeDate)
        //     ->get();

        // $bayar = DB::table('antrianpasiendiperiksa_t AS apd')
        //     ->join('pelayananpasien_t AS pp', 'pp.noregistrasifk', '=', 'apd.norec')
        //     ->leftJoin('strukpelayanan_t AS sp', 'sp.norec', '=', 'pp.strukfk')
        //     ->leftJoin('strukbuktipenerimaan_t AS sbm', 'sbm.nostrukfk', '=', 'sp.norec')
        //     ->select(DB::raw("apd.noregistrasifk,apd.objectruanganfk,CASE WHEN pp.strukfk IS NOT NULL AND sbm.norec IS NOT NULL THEN pp.jumlah*pp.hargajual ELSE 0 END total"))
        //     ->where('apd.kdprofile', $this->kdProfile)
        //     ->where('apd.statusenabled', true)
        //     ->whereBetween(DB::raw("CAST(apd.tglregistrasi AS DATE)"), $rangeDate)
        //     ->get();
        // $i = 0;
        // $dataDiagnosa = '';
        // foreach ($data as $items) {
        //     foreach ($diagnosa as $dg) {
        //         if ($data[$i]->norec == $dg->noregistrasifk) {
        //             if ($dataDiagnosa == '') {
        //                 $dataDiagnosa = $dg->diagnosa;
        //             } else {
        //                 $dataDiagnosa = $dataDiagnosa . ',' . $dg->diagnosa;
        //             }
        //             $data[$i]->diagnosa = $dataDiagnosa;
        //             $data[$i]->kasus = $dg->kasus;
        //         } else {
        //             $data[$i]->diagnosa = '';
        //             $data[$i]->kasus = '';
        //         }
        //     }
        //     $i = $i + 1;
        // }

        // $d = 0;
        // foreach ($data as $itemss) {
        //     foreach ($bayar as $dataBayar) {
        //         if ($data[$d]->norec == $dataBayar->noregistrasifk && $data[$d]->objectruanganfk == $dataBayar->objectruanganfk) {
        //             $data[$d]->bayar = $dataBayar->total;
        //         } else {
        //             $data[$d]->bayar = 0;
        //         }
        //     }
        //     $d = $d + 1;
        // }


        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanPengunjung(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('statuspiutang_m as stp', 'stp.id', '=', 'pd.objectstatuspiutangfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('kebangsaan_m as kbg', 'kbg.id', '=', 'p.objectkebangsaanfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'p.objectjeniskelaminfk')
            ->leftjoin('strukpelayanan_t as sp', function ($join) {
                $join->on('pd.norec', '=', 'sp.noregistrasifk')
                    ->where('sp.objectkelompoktransaksifk', '!=', 46)
                    ->where('sp.statusenabled', '=', true);
            })
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftJoin('strukbuktipenerimaan_t as sbm', function ($join) {
                $join->on('sbm.nostrukfk', '=', 'sp.norec')
                    ->where('sbm.statusenabled', '=', true);
            })
            ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'p.id')
            ->select(
                'pd.norec AS norec_pd',
                'pd.tglregistrasi',
                'p.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'p.namapasien',
                'kp.kelompokpasien',
                'pd.tglpulang',
                'alm.alamatlengkap',
                'pd.statuspasien',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'pd.tglmeninggal',
                'p.nosuratkematian',
                'pd.objectkelompokpasienlastfk',
                'dept.id as deptid',
                'pd.objectstatuspiutangfk',
                DB::raw("case when pd.objectstatuspiutangfk is not null then stp.statuspiutang else '-' end as statuspiutang"),
                DB::raw("p.namapasien || ' - (' || case when jk.id = 1 then 'L' else 'P' end || ') - ' || kbg.name as namatext"),
                'jk.jeniskelamin',
                // 'kbg.name'
                'pd.tglclosing',
                'pd.objectruanganlastfk',
                'pd.objectkelasfk',
                'p.tgllahir',
                'rek.namarekanan',
                'pa.nosep as nosep',
                'pa.norec as norec_pa',
                'sp.norec as norec_sp',
                'pa.objectasuransipasienfk',
                'kbg.name as kebangsaan',
                'pa.ppkrujukan',
                'pa.objectdiagnosafk as iddiagnosabpjs',
                'sbm.nosbm',
                'sbmcr.norec as norec_sbmcr',
                'sbm.norec as norec_sbm',
                'pg.namalengkap AS dokter',
                'pg2.namalengkap as closer'
            )

            ->where('pd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('pd.kdprofile', $this->kdProfile);

        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('p.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('p.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $data = $data->where('pd.objectkelompokpasienlastfk', '=', $request['kpid']);
        }
        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->distinct();
        $data = $data->get();

        foreach ($data as $kd => $d) {
            $d->jamregis = Carbon::parse($d->tglregistrasi)->format('H:i');
            $d->tglregistrasi = Carbon::parse($d->tglregistrasi)->format('d-m-Y');
        }

        $diagnosa = DB::table('antrianpasiendiperiksa_t AS apd')
            ->join('detaildiagnosapasien_t AS ddp', 'ddp.noregistrasifk', '=', 'apd.norec')
            ->join('diagnosapasien_t AS dp', 'dp.norec', '=', 'ddp.objectdiagnosapasienfk')
            ->join('diagnosa_m as dg', 'ddp.objectdiagnosafk', '=', 'dg.id')
            ->select(DB::raw("apd.noregistrasifk,ddp.objectjenisdiagnosafk,dg.kddiagnosa AS diagnosa,
                            CASE WHEN dp.iskasusbaru = true AND dp.iskasuslama = false THEN 'BARU'
                            WHEN dp.iskasuslama = true AND dp.iskasusbaru = false THEN 'LAMA' ELSE '' END kasus"))
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(apd.tglregistrasi AS DATE)"), $rangeDate)
            ->get();

        $bayar = DB::table('antrianpasiendiperiksa_t AS apd')
            ->join('pelayananpasien_t AS pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftJoin('strukpelayanan_t AS sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJoin('strukbuktipenerimaan_t AS sbm', 'sbm.nostrukfk', '=', 'sp.norec')
            ->select(DB::raw("apd.noregistrasifk,apd.objectruanganfk,CASE WHEN pp.strukfk IS NOT NULL AND sbm.norec IS NOT NULL THEN pp.jumlah*pp.hargajual ELSE 0 END total"))
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(apd.tglregistrasi AS DATE)"), $rangeDate)
            ->get();



        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanPengunjungTindakan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftJoin('pelayananpasien_t as pp', function ($j) {
                $j->on('pp.noregistrasifk', '=', 'apd.norec')
                    ->on('pp.kdprofile', '=', 'apd.kdprofile')
                    ->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk')
                    ->on('pd.kdprofile', '=', 'apd.kdprofile');
            })
            ->join('pasien_m as ps', function ($j) {
                $j->on('ps.id', '=', 'pd.nocmfk')
                    ->on('ps.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('jeniskelamin_m as jk', function ($j) {
                $j->on('jk.id', '=', 'ps.objectjeniskelaminfk')
                    ->on('jk.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('agama_m as ag', function ($j) {
                $j->on('ag.id', '=', 'ps.objectagamafk')
                    ->on('ag.kdprofile', '=', 'ps.kdprofile');
            })
            ->leftjoin('pendidikan_m as pdd', function ($j) {
                $j->on('pdd.id', '=', 'ps.objectpendidikanfk')
                    ->on('pdd.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('pekerjaan_m as pkr', function ($j) {
                $j->on('pkr.id', '=', 'ps.objectpekerjaanfk')
                    ->on('pkr.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('alamat_m as alm', function ($j) {
                $j->on('alm.nocmfk', '=', 'ps.id')
                    ->on('ps.kdprofile', '=', 'alm.kdprofile');
            })
            ->join('desakelurahan_m as dsk', function ($j) {
                $j->on('dsk.id', '=', 'alm.objectdesakelurahanfk')
                    ->on('dsk.kdprofile', '=', 'alm.kdprofile');
            })
            ->join('kotakabupaten_m as kkb', function ($j) {
                $j->on('kkb.id', '=', 'alm.objectkotakabupatenfk')
                    ->on('kkb.kdprofile', '=', 'alm.kdprofile');
            })
            ->leftjoin('statusperkawinan_m as sp', function ($j) {
                $j->on('sp.id', '=', 'ps.objectstatusperkawinanfk')
                    ->on('sp.kdprofile', '=', 'ps.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'pd.objectdokterpemeriksafk')
                    ->on('pg.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('ruangan_m as rg', function ($j) {
                $j->on('rg.id', '=', 'pd.objectruanganlastfk')
                    ->on('rg.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('diagnosapasien_t as dp', function ($j) {
                $j->on('dp.noregistrasifk', '=', 'apd.norec')
                    ->on('dp.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftJoin('kelompokpasien_m as klp', function ($j) {
                $j->on('klp.id', '=', 'pd.objectkelompokpasienlastfk')
                    ->on('klp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftJoin('detaildiagnosapasien_t as ddp', function ($j) {
                $j->on('dp.norec', '=', 'ddp.objectdiagnosapasienfk')
                    ->on('dp.kdprofile', '=', 'ddp.kdprofile');
            })
            ->join('diagnosa_m as dg', function ($j) {
                $j->on('ddp.objectdiagnosafk', '=', 'dg.id')
                    ->on('ddp.kdprofile', '=', 'dg.kdprofile');
            })
            ->leftJoin('produk_m as pro', function ($j) {
                $j->on('pro.id', '=', 'pp.produkfk')
                    ->on('pro.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJoin('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'pro.objectdetailjenisprodukfk')
                    ->on('djp.kdprofile', '=', 'pro.kdprofile');
            })
            ->select(
                'pd.norec',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ps.nohp',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'ag.agama',
                'pdd.pendidikan',
                'pkr.pekerjaan',
                'alm.alamatlengkap',
                'dsk.namadesakelurahan',
                'alm.kecamatan',
                'kkb.namakotakabupaten',
                'sp.statusperkawinan',
                'pd.statuspasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ps.tgldaftar',
                'klp.kelompokpasien',
                'djp.detailjenisproduk',
                'pro.namaproduk',
                DB::raw('EXTRACT(YEAR FROM AGE(ps.tgllahir)) AS tahun'),
                DB::raw('EXTRACT(MONTH FROM AGE(ps.tgllahir)) AS bulan'),
                DB::raw('EXTRACT(DAY FROM AGE(ps.tgllahir)) AS hari')
            )
            ->groupBy(
                'pd.norec',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ps.nohp',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'ag.agama',
                'pdd.pendidikan',
                'pkr.pekerjaan',
                'alm.alamatlengkap',
                'dsk.namadesakelurahan',
                'alm.kecamatan',
                'kkb.namakotakabupaten',
                'sp.statusperkawinan',
                'pd.statuspasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ps.tgldaftar',
                'klp.kelompokpasien',
                'djp.detailjenisproduk',
                'pro.namaproduk'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $idProfile)
            ->where('ddp.objectjenisdiagnosafk', 1)
            ->whereNotIn('djp.id', [
                1405,
                1406,
                1407,
                1408,
                1409,
                1587,
                1588,
                1589,
                1590,
                1591,
                1592,
                1593,
                1594,
                1595,
                1596,
                1597,
                1598,
                1599,
                1600,
                1601,
                1346,
                1347,
                1348,
                1349,
                1350,
                1351,
                1352,
                1353,
                1354,
                1355,
                1356,
                1357,
                1358,
                1359,
                1360,
                1361,
                1362,
                1363,
                1364,
                1365,
                1366,
                1367,
                1368,
                1369,
                1370,
                1371,
                1372,
                1373,
                1374,
                1375,
                1376,
                1377,
                1378,
                1379,
                1380,
                1381,
                1382,
                1383,
                1384,
                1385,
                1386,
                1387,
                1388,
                1389,
                1390,
                1391,
                1392,
                1393,
                1394,
                1395,
                1396,
                1397,
                1398,
                1399,
                1400,
                1401,
                1402,
                1403,
                474
            ])
            ->whereNotIn('pro.id', [4040398, 4040406, 4041196, 4041200, 4041204, 4041209, 4041212, 4041215, 4041218, 4040399]);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data->where('pd.tglregistrasi', '<=', $request['tglAkhir']);
        }
        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data->where('rg.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data->where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data->where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kotaKab']) && $request['kotaKab'] != "" && $request['kotaKab'] != "undefined") {
            $data->where('kkb.id', '=', $request['kotaKab']);
        }

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function getLaporanPenyerahanObat(Request $request)
    {
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $kdProfile = (int) $this->kdProfile;

        $data = DB::table('strukresep_t as sr')
            ->leftJoin('antrianapotik_t as aa', 'aa.noresep', '=', 'sr.noresep')
            ->leftJoin('strukorder_t as so', 'so.norec', '=', 'sr.orderfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sr.ruanganfk')
            ->leftJoin('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruanganfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->where('sr.kdprofile', $kdProfile)
            // ->whereBetween('sr.tglresep', [$tglAwal, $tglAkhir])
            ->when($tglAwal, function ($query) use ($tglAwal) {
                $query->where('sr.tglresep', '>=', $tglAwal);
            })
            ->when($tglAkhir, function ($query) use ($tglAkhir) {
                $query->where('sr.tglresep', '<=', $tglAkhir);
            })
            ->whereNotNull('aa.noantri')
            ->select(
                'so.noorder',
                'sr.noresep',
                'pm.nocm',
                'pd.noregistrasi',
                'pm.namapasien',
                'jk.jeniskelamin',
                'so.tglorder',
                'sr.tglresep as tglverifikasi',
                DB::raw("CONCAT(aa.jenis,'-', aa.noantri) AS noantri"),
                'so.namapengambilorder',
                'so.tglambilorder',
                'ru.namaruangan as namaruanganapotik',
                'kp.kelompokpasien',
                'so.keterangankeperluan',
                'so.cito',
                'so.isreseppulang as checkreseppulang',
                'ru2.namaruangan AS namaruanganrawat'
            );

        if (isset($request['jeniskemasan']) && $request['jeniskemasan'] != "" && $request['jeniskemasan'] != "undefined") {
            if ($request['jeniskemasan'] == 1) {
                $data = $data->where('aa.jenis', 'R');
            } else {
                $data = $data->where('aa.jenis', 'N');
            }
        }

        if (isset($request['IdFarmasi']) && $request['IdFarmasi'] != "" && $request['IdFarmasi'] != "undefined") {
            $data = $data->where('sr.ruanganfk', $request['IdFarmasi']);
        }

        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }


    public function getDaftarReturPenerimaanSuplierDetail(Request $request)
    {

        $data = DB::table('strukretur_t as sr')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'sr.strukterimafk')
            ->LEFTJOIN('strukreturdetail_t as srd', 'srd.strukreturfk', '=', 'sr.norec')
            ->LEFTJOIN('produk_m as pr', 'pr.id', '=', 'srd.objectprodukfk')
            ->LEFTJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->LEFTJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->LEFTJOIN('pegawai_m as pg1', 'pg1.id', '=', 'sr.objectpegawaifk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipengeluaran_t as sbk', 'sbk.norec', '=', 'sp.nosbklastfk')
            ->select(DB::raw("sr.norec,sr.tglretur,sr.noretur,sp.nostruk,sp.nosppb,sp.nokontrak,sp.nofaktur,sp.tglfaktur,
                                    sp.objectruanganfk,ru.namaruangan,rkn.namarekanan,pg.namalengkap,pg1.namalengkap as pegawairetur,
                                    pr.namaproduk,ss.satuanstandar,srd.qtyproduk,srd.harganetto1,srd.hargadiscount,
							        ((srd.harganetto1-srd.hargadiscount)*srd.qtyproduk) as total,srd.tglkadaluarsa,srd.nobatch"))
            ->where('sr.kdprofile', $this->kdProfile);

        // if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
        //     $data = $data->where('sr.tglretur', '>=', $request['tglAwal']);
        // }
        // if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
        //     $tgl = $request['tglAkhir'];
        //     $data = $data->where('sr.tglretur', '<=', $tgl);
        // }
        // if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
        //     $data = $data->where('sp.nostruk', 'ILIKE', '%' . $request['nostruk']);
        // }
        // if (isset($request['namarekanan']) && $request['namarekanan'] != "" && $request['namarekanan'] != "undefined") {
        //     $data = $data->where('rkn.namarekanan', 'ILIKE', '%' . $request['namarekanan'] . '%');
        // }
        // if (isset($request['nofaktur']) && $request['nofaktur'] != "" && $request['nofaktur'] != "undefined") {
        //     $data = $data->where('sp.nofaktur', 'ILIKE', '%' . $request['nofaktur'] . '%');
        // }
        // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
        //     $data = $data->where('srd.objectprodukfk', '=', $request['produkfk']);
        // }
        // if (isset($request['noSppb']) && $request['noSppb'] != "" && $request['noSppb'] != "undefined") {
        //     $data = $data->where('sp.nosppb', 'ILIKE', '%' . $request['noSppb'] . '%');
        // }
        // if (isset($request['noretur']) && $request['noretur'] != "" && $request['noretur'] != "undefined") {
        //     $data = $data->where('sr.noretur', 'ILIKE', '%' . $request['noretur'] . '%');
        // }

        //        $data = $data->wherein('sp.objectruanganfk',$strRuangan);
        $data = $data->where('sr.statusenabled', true);
        $data = $data->where('sr.objectkelompoktransaksifk', 9);
        $data = $data->orderBy('sr.noretur');
        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function getDaftarReturObatDetail(Request $request)
    {

        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('strukretur_t as srt')
            ->leftJoin('strukresep_t as sr', 'sr.norec', '=', 'srt.strukresepfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'srt.strukresepfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pelayananpasienretur_t as spd', 'spd.strukreturfk', '=', 'srt.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'spd.produkfk')
            ->join('jeniskemasan_m as jkm', 'jkm.id', '=', 'spd.jeniskemasanfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'spd.satuanviewfk')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'srt.objectpegawaifk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'srt.objectruanganfk')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'apd.objectruanganfk')
            ->select(DB::raw("srt.tglretur,srt.noretur,CASE WHEN pd.noregistrasi IS NULL THEN '-' ELSE pd.noregistrasi END AS noregistrasi,
                    CASE WHEN ps.nocm IS NULL THEN sp.nostruk_intern ELSE ps.nocm END AS nocm,
                    CASE WHEN ps.namapasien IS NULL THEN sp.namapasien_klien ELSE ps.namapasien END AS namapasien,
                    CASE WHEN ru1.namaruangan IS NULL THEN '-' ELSE ru1.namaruangan END as unitlayanan,
                    ps.namapasien,pg.namalengkap,ru.namaruangan AS depo,srt.norec,srt.keteranganlainnya,
                    spd.tglpelayanan, spd.rke,jkm.jeniskemasan,pr.namaproduk,ss.satuanstandar,spd.jumlah,spd.hargasatuan,
                    spd.hargadiscount,spd.jasa,((spd.hargasatuan-spd.hargadiscount)*spd.jumlah)+spd.jasa as total"))
            ->where('srt.kdprofile', $this->kdProfile)
            ->where('srt.statusenabled', true)
            ->whereBetween(DB::raw("CAST(srt.tglretur as Date)"), $rangeDate);

        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $data = $data->where('srt.noretur', 'ilike', '%' . $request['nostruk']);
        }

        if (isset($request['idRuangLayanan']) && $request['idRuangLayanan'] != "" && $request['idRuangLayanan'] != "undefined") {
            $data = $data->where('ru1.id', '=', $request['idRuangLayanan']);
        }

        $data = $data->orderBy('srt.noretur');
        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }
    public function getLaporanAntrianKuotaPoli(Request $request)
    {
        $date = $request->date;
        $kdProfile = $this->kdProfile;
        $set = explode(',', $this->settingFix('kdDepartemenKiosk', $kdProfile));
        $dp = [];
        foreach ($set as $it) {
            $dp[] = (int) $it;
        }
        ;

        $kuotaPoli = DB::table('slottingkiosk_m as sk')
            ->join('ruangan_m as ru', 'ru.id', 'sk.objectruanganfk', )
            ->selectRaw("ru.namaruangan,sk.objectruanganfk,sk.quota,sk.quotafix,0 as terpakai,
                                0 as batal, 0 as sisa, 0 as bersedia, 0 as mjkn, 0 as kiosk")
            ->where('sk.statusenabled', true)
            ->where('sk.tanggal', $date)
            ->where('sk.kdprofile', $this->kdProfile);
        if (isset($request['ruanganfk'])) {
            $kuotaPoli = $kuotaPoli->where('sk.objectruanganfk', $request['ruanganfk']);
        }
        $kuotaPoli = $kuotaPoli->get();

        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->join('ruangan_m as ru', 'ru.id', 'apr.objectruanganfk')
            ->join('slottingkiosk_m as sk', 'sk.objectruanganfk', 'ru.id')
            ->select(
                'ru.namaruangan',
                'apr.objectruanganfk',
                'sk.quota',
                'sk.quotafix',
                DB::raw(
                    "COUNT(apr.objectruanganfk) as terpakai,
                     CASE WHEN(sk.quotafix - COUNT(apr.objectruanganfk)) < 0 THEN 0
                     ELSE (sk.quotafix - COUNT(apr.objectruanganfk)) END as sisa,
                     COUNT(CASE WHEN apr.statusenabled = 'f' THEN 1 END) as batal,
                     COUNT(CASE WHEN apr.statusenabled = 't' THEN 1 END) as bersedia,
                     COUNT(CASE WHEN apr.ismobilejkn = 't' THEN 1 END) as mjkn,
                     COUNT(CASE WHEN apr.iskiosk = 't' THEN 1 END) as kiosk
                    "
                )
            )
            ->whereRaw("to_char(apr.tanggalreservasi, 'yyyy-MM-dd') = '$date'")
            ->where('sk.tanggal', $date)
            ->where('sk.statusenabled', true)
            ->whereNotNull('apr.jenis')
            ->where('apr.kdprofile', $kdProfile);
        if (isset($request['ruanganfk'])) {
            $data = $data->where('apr.objectruanganfk', $request['ruanganfk']);
        }
        $data = $data->groupBy('apr.objectruanganfk', 'ru.namaruangan', 'sk.quota', 'sk.quotafix');
        $data = $data->get();
        foreach ($kuotaPoli as $item) {
            $found = false;
            foreach ($data as $dat) {
                if ($item->objectruanganfk == $dat->objectruanganfk) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $data[] = $item;
            }
        }
        // $data = DB::table('pasiendaftar_t as pd')
        //     ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
        //     ->join('slottingkiosk_m as sk', 'sk.objectruanganfk', 'ru.id')
        //     ->select(
        //         'ru.namaruangan',
        //         'pd.objectruanganlastfk',
        //         'sk.quota',
        //         'sk.quotafix',
        //         DB::raw(
        //             "COUNT(pd.objectruanganlastfk) as terpakai,
        //              CASE WHEN(sk.quotafix - COUNT(pd.objectruanganlastfk)) < 0 THEN 0
        //              ELSE (sk.quotafix - COUNT(pd.objectruanganlastfk)) END as sisa,
        //              COUNT(CASE WHEN pd.statusenabled = 'f' THEN 1 END) as batal,
        //              COUNT(CASE WHEN pd.statusenabled = 't' THEN 1 END) as bersedia,
        //              COUNT(CASE WHEN pd.ismobilejkn = 't' THEN 1 END) as reservasi,
        //              COUNT(CASE WHEN pd.ismobilejkn is null THEN 1 END) as langsung
        //             "
        //         )
        //     )
        //     ->whereRaw("to_char(pd.tglregistrasi, 'yyyy-MM-dd') = '$date'")
        //     ->where('sk.tanggal', $date)
        //     ->where('sk.statusenabled', true)
        //     ->where('pd.kdprofile', $kdProfile)
        //     ->groupBy('pd.objectruanganlastfk', 'ru.namaruangan', 'sk.quota', 'sk.quotafix');

        // $result = $data1->union($data)->get();
        // return $result;
        // if (isset($request['ruanganfk'])) {
        //     $data =  $data->where('ru.id', $request['ruanganfk']);
        // }
        // $data = $data->groupBy('pd.objectruanganlastfk', 'ru.namaruangan', 'sk.quota', 'sk.quotafix');
        // $data = $data->get();

        $loggings = LoggingUser::whereRaw("to_char(tanggal, 'yyyy-MM-dd') = '$date'")->where('jenislog', 'Simpan Slotting Kios')->get();
        $logging = [];
        foreach ($loggings as $key => $log) {
            $decode = json_decode($log->keterangan, true);
            $logging[] = [
                'namapegawai' => $log->namapegawai,
                'tanggal' => $log->tanggal,
                'namarungan' => $decode['namaRuangan'],
                'konvensionalold' => $decode['old'],
                'konvensiolanNew' => $decode['konvensionalNew']
            ];
        }
        $data = [
            'data' => $data,
            'loggings' => $logging
        ];
        return $this->respond($data);
    }

    public function getRuanganPoli()
    {
        $data = Ruangan::select('namaruangan', 'id')->where('kdprofile', $this->kdProfile)
            ->where('objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->get();
        return $this->respond($data);
    }

    public function informasiAntrianPasienAntrol(Request $request)
    {
        $tanggal = Carbon::parse($request->tanggal);
        $tglawal = $request->tglAwal;
        $tglakhir = $request->tglAkhir;
        $objectpegawaifk = $request->objectpegawaifk;

        $kdBooking = "";
        if (isset($request['kdBooking']) && $request['kdBooking'] != '' && $request['kdBooking'] != null) {
            $kdBooking = " and sx.noregistrasi = '" . $request['kdBooking'] . "'";
        }

        $data = collect(DB::select("
            WITH sumber AS (
                select
                ps.nocm as norm,
                coalesce(rk.namarekanan, '') namarekanan,
                pd.objectrekananfk,
                case when apr.ismobilejkn = true or(apr.noreservasi !='' and apr.noreservasi !='Kios-K') then apr.noreservasi else pd.noregistrasi end as noregistrasi,
                mt.noregistrasifk,
                pd.tglregistrasi,
                ps.namapasien,
                rm.namaruangan,
                mt.taskid,
                mt.waktu,
                mt.statuskirim,
                apr.noreservasi,
                rm.kdspesialisbpjs as kodepoli
                from monitoringtaskid_t mt
                inner join pasiendaftar_t pd on pd.norec = mt.noregistrasifk
                inner join ruangan_m rm on rm.id = pd.objectruanganlastfk
                inner join pasien_m ps on ps.id = pd.nocmfk
                left join rekanan_m as rk on rk.id = pd.objectrekananfk
                left join antrianpasienregistrasi_t as apr on pd.antrianpasienregistrasifk = apr.norec
                where pd.statusenabled = true
                and mt.statusenabled = true
                and pd.tglregistrasi between '$tglawal' and '$tglakhir'
            )
            select sx.namarekanan, sx.objectrekananfk, sx.norm, sx.noregistrasi, sx.tglregistrasi, sx.namapasien, sx.namaruangan,sx.noregistrasifk,sx.kodepoli,sx.noreservasi,
            (select sz.waktu from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 1 limit 1) as taksid_1,
            (select sz.statuskirim from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 1 limit 1) as status_1,
            (select sz.waktu from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 2 limit 1) as taksid_2,
            (select sz.statuskirim from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 2 limit 1) as status_2,
            (select sz.waktu from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 3 limit 1) as taksid_3,
            (select sz.statuskirim from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 3 limit 1) as status_3,
            (select sz.waktu from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 4 limit 1) as taksid_4,
            (select sz.statuskirim from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 4 limit 1) as status_4,
            (select sz.waktu from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 5 limit 1) as taksid_5,
            (select sz.statuskirim from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 5 limit 1) as status_5,
            (select sz.waktu from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 6 limit 1) as taksid_6,
            (select sz.statuskirim from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 6 limit 1) as status_6,
            (select sz.waktu from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 7 limit 1) as taksid_7,
            (select sz.statuskirim from sumber sz where sz.noregistrasi = sx.noregistrasi and sz.taskid = 7 limit 1) as status_7
            from sumber sx
            where 1=1
            $kdBooking
            GROUP BY sx.namarekanan, sx.objectrekananfk, sx.norm, sx.noregistrasi, sx.tglregistrasi, sx.namapasien, sx.namaruangan,sx.noregistrasifk,sx.kodepoli,sx.noreservasi
            ORDER BY sx.noregistrasi
        "));

        foreach ($data as $item) {
            $item->taksid_1 = $item->taksid_1 == null ? "-" : date('Y-m-d H:i', $item->taksid_1 / 1000);
            $item->taksid_2 = $item->taksid_2 == null ? "-" : date('Y-m-d H:i', $item->taksid_2 / 1000);
            $item->taksid_3 = $item->taksid_3 == null ? "-" : date('Y-m-d H:i', $item->taksid_3 / 1000);
            $item->taksid_4 = $item->taksid_4 == null ? "-" : date('Y-m-d H:i', $item->taksid_4 / 1000);
            $item->taksid_5 = $item->taksid_5 == null ? "-" : date('Y-m-d H:i', $item->taksid_5 / 1000);
            $item->taksid_6 = $item->taksid_6 == null ? "-" : date('Y-m-d H:i', $item->taksid_6 / 1000);
            $item->taksid_7 = $item->taksid_7 == null ? "-" : date('Y-m-d H:i', $item->taksid_7 / 1000);
        }

        return $this->respond($data);

    }

    public function LaporanObatPerResep(Request $request)
    {
        $rangeDate = [
            $request->tglAwal, 
            $request->tglAkhir . ' 23:59:59' 
        ];

        $query1 = DB::table('strukresep_t as sr')
        ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
        ->join('stokprodukdetail_t as spdd', 'spdd.norec', '=', 'pp.stokprodukdetailfk')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
        ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
        ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
        ->join('detailjenisproduk_m as djp', 'pr.objectdetailjenisprodukfk', '=', 'djp.id')
        ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
        ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->join('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
        ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->join('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
        ->where('sr.statusenabled', true)
        ->whereBetween('sr.tglresep', $rangeDate)
        ->selectRaw("sr.noresep, DATE(sr.tglresep) as tglresep, ps.namapasien, djp.detailjenisproduk, pr.kdproduk, spdd.harganetto1, pr.namaproduk, ru2.namaruangan as satelit, ps.alamatrmh, ps.nocm, kp.kelompokpasien, ru.namaruangan, pg.namalengkap, pp.hargajual, pp.jumlah, pr.isfornas, pr.ispsikotropika, pr.isnarkotika, pr.objectdetailjenisprodukfk, kp.id as kelompokpasienid, ru.id as ruanganid, pp.hargajual * pp.jumlah as harga_total");

        $query2 = DB::table('strukpelayanan_t as sp')
            ->join('strukpelayanandetail_t as spdt', 'spdt.nostrukfk', '=', 'sp.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->join('produk_m as pr', 'pr.id', '=', 'spdt.objectprodukfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('stokprodukdetail_t as spd', 'spd.norec', '=', 'spdt.stokprodukdetailfk')
            ->where('sp.statusenabled', true)
            // ->where('sp.objectkelompoktransaksifk', 4)
            ->whereBetween('sp.tglstruk', $rangeDate)
            ->selectRaw("sp.nostruk as noresep, DATE(sp.tglstruk) as tglresep, namapasien_klien as namapasien, djp.detailjenisproduk, pr.kdproduk, spd.harganetto2 as harganetto1, pr.namaproduk, ru.namaruangan as satelit, sp.namatempattujuan as alamatrmh, nostruk_intern as nocm, namarekanan as kelompokpasien, ru.namaruangan, pg.namalengkap, spdt.hargasatuan as hargajual, spdt.qtyproduk as jumlah, pr.isfornas, pr.ispsikotropika, pr.isnarkotika, pr.objectdetailjenisprodukfk, null as kelompokpasienid, ru.id as ruanganid, spdt.qtyproduk * spdt.hargasatuan as harga_total");

            $union = $query1->unionAll($query2);

            $data = collect(
                DB::table(DB::raw("({$union->toSql()}) as a"))
                    ->mergeBindings($union)
                    ->orderBy('tglresep', 'asc')
                    ->get()
            );
            
            // Filtering manual via Collection
            $data = $data
                ->when($request->ruanganId ?? null, fn($q) => $q->where('ruanganid', $request->ruanganId))
                ->when($request->namaproduk ?? null, fn($q) => $q->filter(fn($item) =>
                    stripos($item->namaproduk, $request->namaproduk) !== false))
                ->when($request->nama ?? null, fn($q) => $q->filter(fn($item) =>
                    stripos($item->namapasien, $request->nama) !== false))
                ->when(($request->status ?? null) === 'P', fn($q) => $q->where('ispsikotropika', true))
                ->when(($request->status ?? null) === 'N', fn($q) => $q->where('isnarkotika', true))
                ->when(($request->status ?? null) === 'O', fn($q) => $q->where('objectdetailjenisprodukfk', 2546))
                ->when(($request->status ?? null) === 'B', fn($q) => $q->where('objectdetailjenisprodukfk', 2549))
                ->when(($request->status ?? null) === 'A', fn($q) => $q->where('objectdetailjenisprodukfk', 2547))
                ->when(($request->status ?? null) === 'G', fn($q) => $q->where('objectdetailjenisprodukfk', 3099))
                ->when(($request->kelompokpasien ?? null), fn($q) => $q->where('kelompokpasienid', (int) $request->kelompokpasien))
                ->values();    
    
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function LaporanPersentaseMikroba(Request $request)
    {
        $rangeDate = [
            $request->tglAwal, 
            $request->tglAkhir . ' 23:59:59' 
        ];

        $totalPasien = DB::table('pasiendaftar_t as pd')
        ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->where('pd.statusenabled', 't')
        ->whereBetween('tglregistrasi', $rangeDate);
         if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "rajal")  {
            $totalPasien = $totalPasien->whereNotIn('ru.objectdepartemenfk',  [16, 9]);
         }
         else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "ranap")  {
            $totalPasien = $totalPasien->where('ru.objectdepartemenfk', '=', 16);
         }

         if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined"){
            $totalPasien = $totalPasien->where('ru.id', $request['ruanganId']);
         }

         $totalPasien = $totalPasien->count();

        $totalPasienAntiMikroba = DB::table('pasiendaftar_t as y')
        ->join('ruangan_m as ru', 'ru.id', '=', 'y.objectruanganlastfk')
        ->join('strukorder_t as so', 'so.noregistrasifk', '=', 'y.norec')
        ->join('orderpelayanan_t as op', 'op.noorderfk', '=', 'so.norec')
        ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
        ->where('y.statusenabled', 't')
        ->whereIn('pr.objectjenisgenerikfk', [27, 31, 47, 38])
        ->whereBetween('y.tglregistrasi', $rangeDate)
        ->distinct('y.norec');
        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "rajal")  {
            $totalPasienAntiMikroba = $totalPasienAntiMikroba->whereNotIn('ru.objectdepartemenfk',  [16, 9]);
         }
         else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "ranap")  {
            $totalPasienAntiMikroba = $totalPasienAntiMikroba->where('ru.objectdepartemenfk', '=', 16);
         }
         if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined"){
            $totalPasienAntiMikroba = $totalPasienAntiMikroba->where('ru.id', $request['ruanganId']);
         }
         $totalPasienAntiMikroba = $totalPasienAntiMikroba->count('y.norec');

        $results[] = array(
            'totalpasien' => $totalPasien,
            'totalpasienmikroba' => $totalPasienAntiMikroba,
        );

        return $this->respond($results);
    }

    public function LaporanKuantitasAntiMikroba(Request $request)
    {
        $rangeDate = [
            $request->tglAwal, 
            $request->tglAkhir . ' 23:59:59' 
        ];

        if($request['status'] == "ranap")
        {
            $data = DB::table('ppra_transaksi as pt')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'pt.noregistrasifk')
                ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
                ->join('produk_m as pr', 'pr.id', '=', 'pt.objectprodukfk')
                ->join('ppra_generik as pg', 'pg.id', '=', 'pr.objectgenerikfk')
                ->join('strukorder_t as so', 'so.norec', '=', 'pt.strukorderfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
                ->leftJoin('ppra_tindakan as ptn', 'ptn.id', '=', 'pt.objectppratindakanfk')
                ->leftJoin('ppra_jenisoperasi as pjo', 'pjo.id', '=', 'pt.objectjenisoperasifk')
                ->select(
                    'pm.namapasien',
                    'pm.nocm',
                    'pr.namaproduk',
                    DB::raw('SUM(qtyorder) as jumlah'),
                    'pg.namagenerik',
                    'pd.noregistrasi',
                    DB::raw("CASE 
                        WHEN pt.antibiotik = 'Obat Empiris' THEN ptn.namatindakan
                        WHEN pt.antibiotik = 'Obat Profilaksis' THEN pjo.namaoperasi
                        ELSE '' 
                    END as DiagnosaTindakan"),
                    DB::raw('DATE(pd.tglregistrasi) as tglregistrasi'),
                    DB::raw('DATE(pd.tglpulang) as tglpulang'),
                    'pt.antibiotik'
                )
                ->where('ru.objectdepartemenfk', 16)
                ->whereBetween('pd.tglregistrasi', $rangeDate)
                ->groupBy(
                    'ru.namaruangan',
                    'pm.namapasien',
                    'pm.nocm',
                    'pr.namaproduk',
                    'pg.namagenerik',
                    'pd.noregistrasi',
                    'pd.tglpulang',
                    'pt.antibiotik',
                    'ptn.namatindakan',
                    'pjo.namaoperasi',
                    'pd.tglregistrasi'
                );
            
            $data = $data->get();
        }

        else if($request['status'] == "rajal")
        {
            $subPasien = DB::table('pasiendaftar_t as pasien')
            ->selectRaw('COUNT(pasien.norec) as totalpasien, pasien.objectruanganlastfk')
            ->where('pasien.statusenabled', true)
            ->whereBetween('pasien.tglregistrasi', $rangeDate)
            ->groupBy('pasien.objectruanganlastfk');

            $data = DB::table('ppra_transaksi as pt')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'pt.noregistrasifk')
                ->join('strukorder_t as so', 'so.norec', '=', 'pt.strukorderfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
                ->leftJoinSub($subPasien, 'pasien', function ($join) {
                    $join->on('pasien.objectruanganlastfk', '=', 'so.objectruanganfk');
                })
                ->join('produk_m as pr', 'pr.id', '=', 'pt.objectprodukfk')
                ->whereBetween('pd.tglregistrasi', $rangeDate)
                ->whereNotIn('ru.objectdepartemenfk',  [16])
                ->select(
                    'ru.id',
                    'ru.namaruangan',
                    DB::raw('pasien.totalpasien'),
                    'pr.namaproduk',
                    DB::raw('SUM(pt.qtyorder) as jumlah')
                )
                ->groupBy(
                    'ru.id',
                    'ru.namaruangan',
                    'pr.namaproduk',
                    'pasien.totalpasien'
                );
                $data = $data->get();
        }
            $result = array(
                'data' => $data,
                'message' => 'Data successfully retrieved',
            );

            return $this->respond($result);
    }

    public function LaporanObatPasien(Request $request)
    {
        $rangeDate = [
            $request->tglAwal, 
            $request->tglAkhir . ' 23:59:59' 
        ];

        $query1 = DB::table('strukresep_t as sr')
            ->select([
                'sr.noresep',
                'sr.tglresep',
                'ps.namapasien',
                'ps.nocm',
                'kp.kelompokpasien',
                'ru.namaruangan',
                'pg.namalengkap',
                DB::raw("CAST(pp.iskronis AS VARCHAR) AS iskronis"),
                'pd.noregistrasi',
                DB::raw('SUM(pp.jumlah * pp.hargasatuan) AS total_tagihan')
            ])
            ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->where('sr.statusenabled', true)
            ->whereBetween('sr.tglresep', $rangeDate)
            ->groupBy([
                'pd.noregistrasi',
                'sr.noresep',
                'sr.tglresep',
                'pp.iskronis',
                'ps.namapasien',
                'ps.nocm',
                'kp.kelompokpasien',
                'ru.namaruangan',
                'pg.namalengkap'
            ]);

        if (!empty($request->ruanganId)) {
            $query1->where('ru.id', '=', $request->ruanganId);
        }
        if (!empty($request->nocm)) {
            $query1->where('ps.nocm', 'ilike', '%' . $request->nocm . '%');
        }
        if (!empty($request->nama)) {
            $query1->where('ps.namapasien', 'ilike', '%' . $request->nama . '%');
        }
        if (!empty($request->dokter)) {
            $query1->where('pg.id', '=', $request->dokter);
        }

        $query2 = DB::table('strukpelayanan_t as sp')
            ->select([
                'sp.nostruk as noresep',
                'sp.tglstruk as tglresep',
                'sp.namapasien_klien as namapasien',
                'sp.nostruk_intern as nocm',
                'sp.namarekanan as kelompokpasien',
                'ru.namaruangan',
                'pg.namalengkap',
                DB::raw("'OB' as iskronis"),
                DB::raw("'' as noregistrasi"),
                'sp.totalharusdibayar as total_tagihan'
            ])
            ->join('strukpelayanandetail_t as spdt', 'spdt.nostrukfk', '=', 'sp.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->where('sp.statusenabled', 't')
            ->whereBetween('sp.tglstruk', $rangeDate)
            ->groupBy([
                'sp.nostruk',
                'sp.tglstruk',
                'sp.namapasien_klien',
                'sp.nostruk_intern',
                'sp.namarekanan',
                'ru.namaruangan',
                'pg.namalengkap',
                'sp.totalharusdibayar'
            ]);

        $data = $query1->unionAll($query2)->orderBy('tglresep')->get();


        $result = [
            'data' => $data,
            'message' => 'ea@epic',
        ];
        return $this->respond($result);
    }

    public function LaporanPelayananResep(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $idRuangan = $request->ruanganId;

        $ruangan = '';
        if (isset($request->ruanganId) && $request->ruanganId != "undefined" && $request->ruanganId != null) {
            $ruangan = 'and sr.ruanganfk = ' . $idRuangan;
        }

        $data = DB::select(DB::raw("SELECT 
            z.namaruangan,
            SUM(z.non_racikan) AS resepnonracikan, 
            SUM(z.racikan) AS resepracikan,
            SUM(z.totalresep) AS totalresep,
            SUM(z.generik) AS generik, 
            SUM(z.nongenerik) AS nongenerik,
                SUM(z.generik) + SUM(z.nongenerik) as totalitem,
            z.kelompokpasien 
        FROM (
        
            SELECT 
                y.namaruangan,
                COUNT(CASE WHEN y.jeniskemasan = 'Non Racikan' THEN 1 END) AS non_racikan,
                COUNT(CASE WHEN y.jeniskemasan = 'Racikan' THEN 1 END) AS racikan,
                COUNT(CASE WHEN y.jeniskemasan = 'Non Racikan' THEN 1 END) + COUNT(CASE WHEN y.jeniskemasan = 'Racikan' THEN 1 END) AS totalresep,
                0 AS generik, 
                0 AS nongenerik, 
                y.kelompokpasien  
            FROM (
                SELECT 
                    sr.noresep,
                    ru.namaruangan,
                    CASE 
                        WHEN COUNT(CASE WHEN jk.id = 1 THEN 1 END) > 0 THEN 'Racikan'
                        ELSE 'Non Racikan'
                    END AS jeniskemasan,
                    kp.kelompokpasien  
                FROM 
                    strukresep_t AS sr
                INNER JOIN 
                    pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
                INNER JOIN 
                    jeniskemasan_m AS jk ON jk.id = pp.jeniskemasanfk
                INNER JOIN 
                    strukorder_t AS so ON so.norec = sr.orderfk
                LEFT JOIN 
                    ruangan_m AS ru ON ru.id = so.objectruanganfk
                LEFT JOIN 
                    antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk  
                LEFT JOIN 
                    pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                LEFT JOIN 
                    kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk 
                WHERE 
                    sr.statusenabled = TRUE
                    AND pp.statusenabled = TRUE
                    AND sr.tglresep::date BETWEEN '$tglAwal' AND '$tglAkhir'
                    $ruangan
                GROUP BY 
                    sr.noresep, ru.namaruangan, kp.kelompokpasien
            ) AS y
            GROUP BY 
                y.namaruangan, y.kelompokpasien

            UNION ALL


            SELECT 
                ru.namaruangan,
                0 AS non_racikan, 
                0 AS racikan,  
                0 AS totalresep,  
                SUM(CASE 
                    WHEN pm.namaexternal = 'Generik' THEN 1
                    ELSE 0 
                END) AS generik,
                SUM(CASE 
                    WHEN pm.namaexternal != 'Generik' THEN 1
                    ELSE 0 
                END) AS nongenerik,
                kp.kelompokpasien  -- Menambahkan kelompok pasien
            FROM 
                pelayananpasien_t AS pp
            INNER JOIN 
                produk_m AS pm ON pm.id = pp.produkfk
            INNER JOIN 
                strukresep_t AS sr ON sr.norec = pp.strukresepfk
            INNER JOIN 
                strukorder_t AS so ON so.norec = sr.orderfk
            LEFT JOIN 
                ruangan_m AS ru ON ru.id = so.objectruanganfk
            LEFT JOIN 
                antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk  
            LEFT JOIN 
                pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk  
            LEFT JOIN 
                kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk 
            WHERE 
                pp.statusenabled = TRUE
                AND sr.tglresep::date BETWEEN '$tglAwal' AND '$tglAkhir'
                $ruangan
            GROUP BY 
                ru.namaruangan, kp.kelompokpasien
        ) AS z
        GROUP BY 
            z.namaruangan, z.kelompokpasien;"));

        $result = [
            'data' => $data,
            'message' => 'ea@epic',
        ];
        return $this->respond($result);
    }

    public function LaporanPerencanaanBHMP(Request $request)
    {
        $threeMonthsAgo = now()->subMonths(3);

        $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
        ->select('spd_latest.objectprodukfk', 'spd_latest.harganetto1')
        ->where('spd_latest.statusenabled', true)
        ->whereRaw('spd_latest.created_at = (
            SELECT MAX(spd2.created_at)
            FROM stokprodukdetail_t AS spd2
            WHERE spd2.objectprodukfk = spd_latest.objectprodukfk
            AND spd2.statusenabled = true
        )')
        ->whereRaw('spd_latest.harganetto1 = (
            SELECT MAX(spd3.harganetto1)
            FROM stokprodukdetail_t AS spd3
            WHERE spd3.objectprodukfk = spd_latest.objectprodukfk
            AND spd3.statusenabled = true
            AND spd3.created_at = spd_latest.created_at
        )');

        $stokpemesan = DB::table('stokprodukdetail_t as stokpemesan')
            ->selectRaw('SUM(stokpemesan.qtyproduk) as total, stokpemesan.objectprodukfk')  
            ->where('stokpemesan.statusenabled', true)
            ->where('stokpemesan.objectruanganfk', 343)  
            ->groupBy('stokpemesan.objectprodukfk'); 
        
        $stokpengeluaran = DB::table('kirimproduk_t as pp_pengeluaran')
        ->join('strukkirim_t as sr', 'pp_pengeluaran.nokirimfk', '=', 'sr.norec')
        ->selectRaw('SUM(pp_pengeluaran.qtyprodukkonfirmasi) as pengeluaran, pp_pengeluaran.objectprodukfk')  
        ->where('pp_pengeluaran.statusenabled', true)
        ->where('sr.objectruanganfk',343 )  
        ->where('pp_pengeluaran.tglpelayanan', '>=', $threeMonthsAgo)
        ->groupBy('pp_pengeluaran.objectprodukfk'); 

        $data = DB::table('produk_m as pr')
            ->join('kirimproduk_t as pp', 'pp.objectprodukfk', '=', 'pr.id')
            ->join('stokprodukdetail_t as spd', 'pp.stokprodukdetailfk', '=', 'spd.norec') 
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->leftJoinSub($stokpemesan, 'stokpemesan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stokpemesan.objectprodukfk');
            })
            ->leftJoinSub($stokpengeluaran, 'pp_pengeluaran', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran.objectprodukfk');
            })
            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'ss.id as satuanstandarfk',
                'stokpemesan.total AS total',
                DB::raw('CASE WHEN pp_pengeluaran.pengeluaran IS NULL THEN 0 ELSE pp_pengeluaran.pengeluaran END AS pengeluaran'),
                'latest_price.harganetto1 AS latest_harganetto1'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pr.objectkelompokprodukfk', 24)
            ->where('pp.statusenabled', true)
            ->whereRaw('(pr.objectsumberdanafk IS NULL OR pr.objectsumberdanafk != 4)')
            ->where('pr.objectdetailjenisprodukfk', 2549)
            ->groupBy(
                'pr.kdproduk',   
                'ss.id',            
                'pr.id',                
                'pr.namaproduk',                                   
                'ss.satuanstandar',               
                'stokpemesan.total',  
                'pp_pengeluaran.pengeluaran',  
                'latest_price.harganetto1' 
            );

            if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
                $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['nama'] . '%');
            }

            $data = $data->get();

            $result = array(
                'data' => $data,
                'message' => 'Data successfully retrieved',
        );

        return $this->respond($result);
    }

    public function LaporanPasienRawatInap(Request $request)
    {
       $data = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->join('kamar_m as kmr', 'kmr.id', '=', 'apd.objectkamarfk')
            ->leftJoin('kelas_m as klsr', 'klsr.id', '=', 'apd.kelasrawatfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->leftJoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'kp.kelompokpasien',
                'ru.namaruangan',
                'kls.namakelas',
                'kmr.namakamar',
                'tt.reportdisplay',
                'ps.alamatrmh',
                'ps.nobpjs',
                'ps.noidentitas',
                'kbs.name AS kebangsaan',
                'pd.tglregistrasi',
                DB::raw("CASE WHEN apd.israwatgabung IS TRUE THEN 'Rawat Gabung' ELSE '' END AS rawatgabung")
            )
            ->where('ru.objectdepartemenfk', 16)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereNull('pd.tglpulang')
            ->where('apd.kdprofile', $this->kdProfile) 
            ->orderBy('pd.noregistrasi', 'DESC')
            ->orderBy('pd.tglregistrasi', 'DESC');

            if(isset($request['ruanganCondition']) && $request['ruanganCondition'] != ''){
                $data=$data->where('pd.objectruanganlastfk',$request['ruanganCondition']);
            }

            $data=$data->get();

    
        $result = [
            'data' => $data, 
            'message' => 'ea@epic',
        ];

        return $this->respond($result);

    }


    public function LaporanPerencanaanObat(Request $request)
    {
        $threeMonthsAgo = now()->subMonths(3);

        // $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
        // ->select('spd_latest.objectprodukfk', DB::raw('MAX(spd_latest.harganetto1) as harganetto1'))
        // ->where('spd_latest.statusenabled', true)
        // ->whereRaw("spd_latest.created_at >= NOW() - INTERVAL '1 YEAR'")
        // ->groupBy('spd_latest.objectprodukfk');

        $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
        ->select(
            'spd_latest.objectprodukfk',
            'spd_latest.harganetto1',
            'spd_latest.created_at',
            DB::raw('ROW_NUMBER() OVER (
                PARTITION BY spd_latest.objectprodukfk 
                ORDER BY spd_latest.created_at DESC
            ) as rn')
        )
        ->where('spd_latest.statusenabled', true)
        ->whereRaw("spd_latest.created_at >= NOW() - INTERVAL '1 YEAR'");

        $stokpemesan = DB::table('stokprodukdetail_t as stokpemesan')
            ->selectRaw('SUM(stokpemesan.qtyproduk) as total, stokpemesan.objectprodukfk')  
            ->where('stokpemesan.statusenabled', true)
            ->where('stokpemesan.objectruanganfk', 343)  
            ->groupBy('stokpemesan.objectprodukfk'); 
        
        $stokpengeluaran = DB::table('kirimproduk_t as pp_pengeluaran')
        ->join('strukkirim_t as sr', 'pp_pengeluaran.nokirimfk', '=', 'sr.norec')
        ->selectRaw('SUM(pp_pengeluaran.qtyprodukkonfirmasi) as pengeluaran, pp_pengeluaran.objectprodukfk')  
        ->where('pp_pengeluaran.statusenabled', true)
        ->where('sr.objectruanganfk',343 )  
        ->where('pp_pengeluaran.tglpelayanan', '>=', $threeMonthsAgo)
        ->groupBy('pp_pengeluaran.objectprodukfk'); 

        $data = DB::table('produk_m as pr')
            ->join('kirimproduk_t as pp', 'pp.objectprodukfk', '=', 'pr.id')
            ->join('stokprodukdetail_t as spd', 'pp.stokprodukdetailfk', '=', 'spd.norec') 
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->leftJoinSub($stokpemesan, 'stokpemesan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stokpemesan.objectprodukfk');
            })
            ->leftJoinSub($stokpengeluaran, 'pp_pengeluaran', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran.objectprodukfk');
            })
            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'ss.id as satuanstandarfk',
                'stokpemesan.total AS total',
                DB::raw('CASE WHEN pp_pengeluaran.pengeluaran IS NULL THEN 0 ELSE pp_pengeluaran.pengeluaran END AS pengeluaran'),
                'latest_price.harganetto1 AS latest_harganetto1'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pr.objectkelompokprodukfk', 24)
            ->where('pp.statusenabled', true)
            ->whereRaw('(pr.objectsumberdanafk IS NULL OR pr.objectsumberdanafk != 4)')
            ->where('pr.objectdetailjenisprodukfk', 2546)
            ->where(function ($q) {
                $q->where('latest_price.rn', 1)
                  ->orWhereNull('latest_price.rn');
            })
            ->groupBy(
                'pr.kdproduk',   
                'ss.id',            
                'pr.id',                
                'pr.namaproduk',                                   
                'ss.satuanstandar',               
                'stokpemesan.total',  
                'pp_pengeluaran.pengeluaran',  
                'latest_price.harganetto1' 
            );

            if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
                $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['nama'] . '%');
            }

            $data = $data->get();

            $result = array(
                'data' => $data,
                'message' => 'Data successfully retrieved',
        );

        return $this->respond($result);
    }

    public function LaporanPerencanaanFarmasi(Request $request)
    {
        $threeMonthsAgo = now()->subMonths(3);

        $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
        ->select('spd_latest.objectprodukfk', DB::raw('MAX(spd_latest.harganetto1) as harganetto1'))
        ->where('spd_latest.statusenabled', true)
        ->whereRaw("spd_latest.created_at >= NOW() - INTERVAL '1 YEAR'")
        ->groupBy('spd_latest.objectprodukfk');

        $stokpemesan = DB::table('stokprodukdetail_t as stokpemesan')
            ->selectRaw('SUM(stokpemesan.qtyproduk) as total, stokpemesan.objectprodukfk')  
            ->where('stokpemesan.statusenabled', true)
            ->whereIn('stokpemesan.objectruanganfk', [326, 327, 329, 343, 328, 325])  
            ->groupBy('stokpemesan.objectprodukfk'); 
        
        $stokpengeluaran = DB::table('pelayananpasien_t as pp_pengeluaran')
        ->join('strukresep_t as sr', 'pp_pengeluaran.strukresepfk', '=', 'sr.norec')
        ->selectRaw('SUM(pp_pengeluaran.jumlah) as pengeluaran, pp_pengeluaran.produkfk')  
        ->where('pp_pengeluaran.statusenabled', true)
        ->whereIn('sr.ruanganfk', [326, 327, 329, 343, 328, 325]) 
        ->where('pp_pengeluaran.tglpelayanan', '>=', $threeMonthsAgo)
        ->groupBy('pp_pengeluaran.produkfk');

        $stokpengeluaran2 = DB::table('kirimproduk_t as pp_pengeluaran2')
        ->join('strukkirim_t as sr', 'pp_pengeluaran2.nokirimfk', '=', 'sr.norec')
        ->join('produk_m as pr', 'pr.id', '=', 'pp_pengeluaran2.objectprodukfk')
        ->selectRaw('SUM(pp_pengeluaran2.qtyprodukkonfirmasi) as pengeluaran, pp_pengeluaran2.objectprodukfk')  
        ->where('pp_pengeluaran2.statusenabled', true)
        ->where('sr.objectruanganfk', 343)
        ->whereNotIn('sr.objectruangantujuanfk', [326, 327, 329, 343, 328, 325, 357, 344])
        ->where('pp_pengeluaran2.tglpelayanan', '>=', $threeMonthsAgo)
        ->groupBy('pp_pengeluaran2.objectprodukfk');

        $data = DB::table('produk_m as pr')
            ->leftjoin('stokprodukdetail_t as spd', 'pr.id', '=', 'spd.objectprodukfk') 
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->leftJoinSub($stokpemesan, 'stokpemesan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stokpemesan.objectprodukfk');
            })
            ->leftJoinSub($stokpengeluaran, 'pp_pengeluaran', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran.produkfk');
            })

            ->leftJoinSub($stokpengeluaran2, 'pp_pengeluaran2', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran2.objectprodukfk');
            })


            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.isfornas',
                'pr.objectdetailjenisprodukfk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'ss.id as satuanstandarfk',
                'stokpemesan.total AS total',
                DB::raw('
                    COALESCE(pp_pengeluaran.pengeluaran, 0) + COALESCE(pp_pengeluaran2.pengeluaran, 0) AS pengeluaran
                '),
                'latest_price.harganetto1 AS latest_harganetto1'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->whereRaw('(pp_pengeluaran.pengeluaran > 0 OR stokpemesan.total > 0 OR pp_pengeluaran2.pengeluaran > 0)')
            ->whereRaw('(pr.objectsumberdanafk IS NULL OR pr.objectsumberdanafk != 4)')
            ->where('pr.objectkelompokprodukfk', 24)
            ->groupBy(
                'pr.kdproduk',   
                'ss.id',            
                'pr.id',                
                'pr.namaproduk',                                   
                'ss.satuanstandar',               
                'stokpemesan.total',  
                'pp_pengeluaran.pengeluaran',  
                'pp_pengeluaran2.pengeluaran',  
                'latest_price.harganetto1' 
            );

            if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
                $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['nama'] . '%');
            }

            if (isset($request['status']) && $request['status'] == "" && $request['status'] == "undefined")  {
                $data = $data->get();
            }

            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "O")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2546);
            }
    
            else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "B")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2549);
            }
    
            else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "A")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2547);
            }

            else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "G")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 3099);
            }

            $data = $data->orderBy('pengeluaran', 'desc')->get();

            $result = array(
                'data' => $data,
                'message' => 'Data successfully retrieved',
        );

        return $this->respond($result);
    }

    public function LaporanPerencanaanBHMPPart2(Request $request)
    {
        $sevenDaysAgo = now()->subDays(7);

        $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
            ->select('spd_latest.objectprodukfk', 'spd_latest.harganetto1')
            ->where('spd_latest.statusenabled', true)
            ->whereRaw('spd_latest.created_at = (
                SELECT MAX(spd2.created_at)
                FROM stokprodukdetail_t AS spd2
                WHERE spd2.objectprodukfk = spd_latest.objectprodukfk
                AND spd2.statusenabled = true
            )')
            ->whereRaw('spd_latest.harganetto1 = (
                SELECT MAX(spd3.harganetto1)
                FROM stokprodukdetail_t AS spd3
                WHERE spd3.objectprodukfk = spd_latest.objectprodukfk
                AND spd3.statusenabled = true
                AND spd3.created_at = spd_latest.created_at
            )');

        $stokpemesan = DB::table('stokprodukdetail_t as stokpemesan')
            ->selectRaw('SUM(stokpemesan.qtyproduk) as total, stokpemesan.objectprodukfk')  
            ->where('stokpemesan.statusenabled', true)
            ->where('stokpemesan.objectruanganfk',$request['ruangan'] )  
            ->groupBy('stokpemesan.objectprodukfk'); 
         
        $stokpengeluaran = DB::table('pelayananpasien_t as pp_pengeluaran')
        ->join('strukresep_t as sr', 'pp_pengeluaran.strukresepfk', '=', 'sr.norec')
        ->selectRaw('SUM(pp_pengeluaran.jumlah) as pengeluaran, pp_pengeluaran.produkfk')  
        ->where('pp_pengeluaran.statusenabled', true)
        ->where('sr.ruanganfk',$request['ruangan'] )  
        ->where('pp_pengeluaran.tglpelayanan', '>=', $sevenDaysAgo)
        ->groupBy('pp_pengeluaran.produkfk'); 

        $data = DB::table('produk_m as pr')
            ->join('pelayananpasien_t as pp', 'pp.produkfk', '=', 'pr.id')
            ->join('stokprodukdetail_t as spd', 'pp.stokprodukdetailfk', '=', 'spd.norec') 
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->leftJoinSub($stokpemesan, 'stokpemesan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stokpemesan.objectprodukfk');
            })
            ->leftJoinSub($stokpengeluaran, 'pp_pengeluaran', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran.produkfk');
            })
            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'ss.id as satuanstandarfk',
                'stokpemesan.total AS total',
                DB::raw('CASE WHEN pp_pengeluaran.pengeluaran IS NULL THEN 0 ELSE pp_pengeluaran.pengeluaran END AS pengeluaran'),
                'latest_price.harganetto1 AS latest_harganetto1'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pr.objectkelompokprodukfk', 24)
            ->where('pp.statusenabled', true)
            ->where('pr.objectdetailjenisprodukfk', 2549)
            ->groupBy(
                'pr.kdproduk',   
                'ss.id',            
                'pr.id',                
                'pr.namaproduk',                                   
                'ss.satuanstandar',               
                'stokpemesan.total',  
                'pp_pengeluaran.pengeluaran',  
                'latest_price.harganetto1' 
            );

        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        }

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'Data successfully retrieved',
        );

        return $this->respond($result);
    }

    public function LaporanPerencanaanObatPart2(Request $request)
    {
        $sevenDaysAgo = now()->subDays(30);

        // $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
        // ->select('spd_latest.objectprodukfk', DB::raw('MAX(spd_latest.harganetto1) as harganetto1'))
        // ->where('spd_latest.statusenabled', true)
        // ->whereRaw("spd_latest.created_at >= NOW() - INTERVAL '1 YEAR'")
        // ->groupBy('spd_latest.objectprodukfk');

        $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
        ->select(
            'spd_latest.objectprodukfk',
            'spd_latest.harganetto1',
            'spd_latest.created_at',
            DB::raw('ROW_NUMBER() OVER (
                PARTITION BY spd_latest.objectprodukfk 
                ORDER BY spd_latest.created_at DESC
            ) as rn')
        )
        ->where('spd_latest.statusenabled', true)
        ->whereRaw("spd_latest.created_at >= NOW() - INTERVAL '1 YEAR'");

        $stokpemesan = DB::table('stokprodukdetail_t as stokpemesan')
            ->selectRaw('SUM(stokpemesan.qtyproduk) as total, stokpemesan.objectprodukfk')  
            ->where('stokpemesan.statusenabled', true)
            ->where('stokpemesan.objectruanganfk',$request['ruangan'] )  
            ->groupBy('stokpemesan.objectprodukfk'); 
         
        $stokpengeluaran = DB::table('pelayananpasien_t as pp_pengeluaran')
        ->join('strukresep_t as sr', 'pp_pengeluaran.strukresepfk', '=', 'sr.norec')
        ->selectRaw('SUM(pp_pengeluaran.jumlah) as pengeluaran, pp_pengeluaran.produkfk')  
        ->where('pp_pengeluaran.statusenabled', true)
        ->where('sr.ruanganfk',$request['ruangan'] )  
        ->where('pp_pengeluaran.tglpelayanan', '>=', $sevenDaysAgo)
        ->groupBy('pp_pengeluaran.produkfk'); 

        $data = DB::table('produk_m as pr')
            ->join('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id') 
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->leftJoinSub($stokpemesan, 'stokpemesan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stokpemesan.objectprodukfk');
            })
            ->leftJoinSub($stokpengeluaran, 'pp_pengeluaran', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran.produkfk');
            })
            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'pr.isfornas',
                'pr.objectdetailjenisprodukfk',
                'ss.satuanstandar',
                'ss.id as satuanstandarfk',
                'stokpemesan.total AS total',
                DB::raw('CASE WHEN pp_pengeluaran.pengeluaran IS NULL THEN 0 ELSE pp_pengeluaran.pengeluaran END AS pengeluaran'),
                'latest_price.harganetto1 AS latest_harganetto1'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pr.objectkelompokprodukfk', 24)
            // ->where('pp.statusenabled', true)
            ->where('spd.objectruanganfk', $request['ruangan'])
            // ->where('pp_pengeluaran.pengeluaran', '>', 0)
            // ->where('stokpemesan.total', '>', 0)
            ->whereRaw('(pp_pengeluaran.pengeluaran > 0 OR stokpemesan.total > 0)')
            ->whereRaw('(pr.objectsumberdanafk IS NULL OR pr.objectsumberdanafk != 4)')
            ->where(function ($q) {
                $q->where('latest_price.rn', 1)
                  ->orWhereNull('latest_price.rn');
            })
            ->groupBy(
                'pr.kdproduk',   
                'ss.id',            
                'pr.id',                
                'pr.namaproduk',                                   
                'ss.satuanstandar',               
                'stokpemesan.total',  
                'pp_pengeluaran.pengeluaran',  
                'latest_price.harganetto1' 
            );

            if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
                $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['nama'] . '%');
            }

            if (isset($request['status']) && $request['status'] == "" && $request['status'] == "undefined")  {
                $data = $data->get();
            }

            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "O")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2546);
            }
    
            else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "B")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2549);
            }
    
            else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "A")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2547);
            }

            else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "G")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 3099);
            }

            $data = $data->orderBy('pengeluaran', 'desc')->get();

        $result = array(
            'data' => $data,
            'message' => 'Data successfully retrieved',
        );

        return $this->respond($result);

    }

    public function LaporanPerencanaanAMHPPart2(Request $request)
    {
        $sevenDaysAgo = now()->subDays(7);

        $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
            ->select('spd_latest.objectprodukfk', 'spd_latest.harganetto1')
            ->where('spd_latest.statusenabled', true)
            ->whereRaw('spd_latest.created_at = (
                SELECT MAX(spd2.created_at)
                FROM stokprodukdetail_t AS spd2
                WHERE spd2.objectprodukfk = spd_latest.objectprodukfk
                AND spd2.statusenabled = true
            )')
            ->whereRaw('spd_latest.harganetto1 = (
                SELECT MAX(spd3.harganetto1)
                FROM stokprodukdetail_t AS spd3
                WHERE spd3.objectprodukfk = spd_latest.objectprodukfk
                AND spd3.statusenabled = true
                AND spd3.created_at = spd_latest.created_at
            )');

        $stokpemesan = DB::table('stokprodukdetail_t as stokpemesan')
            ->selectRaw('SUM(stokpemesan.qtyproduk) as total, stokpemesan.objectprodukfk')  
            ->where('stokpemesan.statusenabled', true)
            ->where('stokpemesan.objectruanganfk',$request['ruangan'] )  
            ->groupBy('stokpemesan.objectprodukfk'); 
         
        $stokpengeluaran = DB::table('pelayananpasien_t as pp_pengeluaran')
        ->join('strukresep_t as sr', 'pp_pengeluaran.strukresepfk', '=', 'sr.norec')
        ->selectRaw('SUM(pp_pengeluaran.jumlah) as pengeluaran, pp_pengeluaran.produkfk')  
        ->where('pp_pengeluaran.statusenabled', true)
        ->where('sr.ruanganfk',$request['ruangan'] )  
        ->where('pp_pengeluaran.tglpelayanan', '>=', $sevenDaysAgo)
        ->groupBy('pp_pengeluaran.produkfk'); 

        $data = DB::table('produk_m as pr')
            ->join('pelayananpasien_t as pp', 'pp.produkfk', '=', 'pr.id')
            ->join('stokprodukdetail_t as spd', 'pp.stokprodukdetailfk', '=', 'spd.norec') 
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->leftJoinSub($stokpemesan, 'stokpemesan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stokpemesan.objectprodukfk');
            })
            ->leftJoinSub($stokpengeluaran, 'pp_pengeluaran', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran.produkfk');
            })
            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'ss.id as satuanstandarfk',
                'stokpemesan.total AS total',
                DB::raw('CASE WHEN pp_pengeluaran.pengeluaran IS NULL THEN 0 ELSE pp_pengeluaran.pengeluaran END AS pengeluaran'),
                'latest_price.harganetto1 AS latest_harganetto1'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pr.objectkelompokprodukfk', 24)
            ->where('pp.statusenabled', true)
            ->where('pr.objectdetailjenisprodukfk', 2547)
            ->groupBy(
                'pr.kdproduk',   
                'ss.id',            
                'pr.id',                
                'pr.namaproduk',                                   
                'ss.satuanstandar',               
                'stokpemesan.total',  
                'pp_pengeluaran.pengeluaran',  
                'latest_price.harganetto1' 
            );

        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        }

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'Data successfully retrieved',
        );

        return $this->respond($result);
        
    }

    public function LaporanKadaluarsaBMHP(Request $request)
    {
        $data = DB::table('stokprodukdetail_t as spd')
            ->select([
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'spd.harganetto1',
                'spd.tglkadaluarsa',
                DB::raw("SUM(CASE WHEN ru.id = 326 THEN spd.qtyproduk ELSE 0 END) as intensif"),
                DB::raw("SUM(CASE WHEN ru.id = 327 THEN spd.qtyproduk ELSE 0 END) as bedsen"),
                DB::raw("SUM(CASE WHEN ru.id = 329 THEN spd.qtyproduk ELSE 0 END) as ranap"),
                DB::raw("SUM(CASE WHEN ru.id = 343 THEN spd.qtyproduk ELSE 0 END) as gudfar"),
                DB::raw("SUM(CASE WHEN ru.id = 343 THEN spd.qtyproduk ELSE 0 END) as rajal"),
                DB::raw("SUM(CASE WHEN ru.id = 343 THEN spd.qtyproduk ELSE 0 END) as onko"),
                DB::raw("SUM(CASE WHEN ru.id = 343 THEN spd.qtyproduk ELSE 0 END) as sentral")
            ])
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->where('pr.statusenabled', true)
            ->where('pr.objectdetailjenisprodukfk', 2549);

        // Filter berdasarkan ruangan jika ada
        if (isset($request['ruangan']) && $request['ruangan'] != "" && $request['ruangan'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruangan']);
        }
        // Filter berdasarkan nocm jika ada
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        // Filter berdasarkan nama pasien jika ada
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        // Filter berdasarkan dokter jika ada
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->where('pg.id', '=', $request['dokter']);
        }

     
        $currentDate = now(); 
        $oneYearFromNow = now()->addYear(); 

        $data = $data->whereBetween('spd.tglkadaluarsa', [$currentDate, $oneYearFromNow]);

        $data = $data->groupBy('pr.kdproduk', 'pr.namaproduk', 'ss.satuanstandar', 'spd.harganetto1', 'spd.tglkadaluarsa')
            ->orderBy('pr.kdproduk')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    
    public function LaporanKadaluarsaObat(Request $request)
    {
        $data = DB::table('stokprodukdetail_t as spd')
            ->select([
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'spd.harganetto1',
                'spd.tglkadaluarsa',
                DB::raw("SUM(CASE WHEN ru.id = 326 THEN spd.qtyproduk ELSE 0 END) as intensif"),
                DB::raw("SUM(CASE WHEN ru.id = 327 THEN spd.qtyproduk ELSE 0 END) as bedsen"),
                DB::raw("SUM(CASE WHEN ru.id = 329 THEN spd.qtyproduk ELSE 0 END) as ranap"),
                DB::raw("SUM(CASE WHEN ru.id = 343 THEN spd.qtyproduk ELSE 0 END) as gudfar"),
                DB::raw("SUM(CASE WHEN ru.id = 325 THEN spd.qtyproduk ELSE 0 END) as rajal"),
                DB::raw("SUM(CASE WHEN ru.id = 326 THEN spd.qtyproduk ELSE 0 END) as onko"),
                DB::raw("SUM(CASE WHEN ru.id = 325 THEN spd.qtyproduk ELSE 0 END) as sentral")
            ])
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->where('pr.statusenabled', true)
            ->where('spd.statusenabled', true)
            ->where('spd.qtyproduk', '>', 0)
            ->where('pr.objectkelompokprodukfk', 24);

            if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
                $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['nama'] . '%');
            }

            if (isset($request['status']) && $request['status'] == "" && $request['status'] == "undefined")  {
                $data = $data->get();
            }

            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "O")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2546);
            }
    
            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "B")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2549);
            }
    
            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "A")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2547);
            }
        
        
        $currentDate = now(); 
        $sampai = now()->addDays(180);

        $data = $data->whereBetween('spd.tglkadaluarsa', [$currentDate, $sampai]);

        $data = $data->groupBy('pr.kdproduk', 'pr.namaproduk', 'ss.satuanstandar', 'spd.harganetto1', 'spd.tglkadaluarsa')
            ->orderBy('pr.namaproduk')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function LaporanKadaluarsaSatelit(Request $request)
    {
        $data = DB::table('stokprodukdetail_t as spd')
            ->select([
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'spd.harganetto1',
                'spd.tglkadaluarsa',
                DB::raw("SUM(spd.qtyproduk) as totalstok"),
            ])
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->where('pr.statusenabled', true)
            ->where('spd.statusenabled', true)
            ->where('spd.qtyproduk', '>', 0)
            ->where('spd.objectruanganfk', $request['ruangan'])
            ->where('pr.objectkelompokprodukfk', 24);

            if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
                $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['nama'] . '%');
            }

            if (isset($request['status']) && $request['status'] == "" && $request['status'] == "undefined")  {
                $data = $data->get();
            }

            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "O")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2546);
            }
    
            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "B")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2549);
            }
    
            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "A")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2547);
            }
        
        
        $currentDate = now(); 
        $sampai = now()->addDays(180);

        $data = $data->whereBetween('spd.tglkadaluarsa', [$currentDate, $sampai]);

        $data = $data->groupBy('pr.kdproduk', 'pr.namaproduk', 'ss.satuanstandar', 'spd.harganetto1', 'spd.tglkadaluarsa')
            ->orderBy('pr.namaproduk')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function LaporanKadaluarsaObatPart2(Request $request)
    {
        $data = DB::table('stokprodukdetail_t as spd')
            ->select([
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'spd.harganetto1',
                'spd.tglkadaluarsa',
                DB::raw('SUM(spd.qtyproduk) as total_qty'),

                'ru.namaruangan',
            ])
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->where('pr.statusenabled', true)
            ->where('pr.objectdetailjenisprodukfk', 2546);

        if (isset($request['ruangan']) && $request['ruangan'] != "" && $request['ruangan'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruangan']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->where('pg.id', '=', $request['dokter']);
        }

        $currentDate = now(); // Mendapatkan tanggal sekarang
        $oneYearFromNow = now()->addYear(); // Mendapatkan tanggal satu tahun dari sekarang

        $data = $data->whereBetween('spd.tglkadaluarsa', [$currentDate, $oneYearFromNow]);

        $data = $data->groupBy('pr.kdproduk', 'pr.namaproduk', 'ss.satuanstandar', 'spd.harganetto1', 'spd.tglkadaluarsa', 'ru.namaruangan')
            ->orderBy('pr.kdproduk')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function LaporanKadaluarsaBMHPPart2(Request $request)
    {
        $data = DB::table('stokprodukdetail_t as spd')
            ->select([
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'spd.harganetto1',
                'spd.tglkadaluarsa',
                DB::raw('SUM(spd.qtyproduk) as total_qty'),

                'ru.namaruangan',
            ])
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->where('pr.statusenabled', true)
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->where('pr.objectdetailjenisprodukfk', 2549);

        // Filter berdasarkan ruangan jika ada
        if (isset($request['ruangan']) && $request['ruangan'] != "" && $request['ruangan'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruangan']);
        }
        // Filter berdasarkan nocm jika ada
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        // Filter berdasarkan nama pasien jika ada
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        // Filter berdasarkan dokter jika ada
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->where('pg.id', '=', $request['dokter']);
        }

        // Menambahkan filter untuk tglkadaluarsa yang dalam rentang satu tahun dari sekarang
        $currentDate = now(); // Mendapatkan tanggal sekarang
        $oneYearFromNow = now()->addYear(); // Mendapatkan tanggal satu tahun dari sekarang

        $data = $data->whereBetween('spd.tglkadaluarsa', [$currentDate, $oneYearFromNow]);

        $data = $data->groupBy('pr.kdproduk', 'pr.namaproduk', 'ss.satuanstandar', 'spd.harganetto1', 'spd.tglkadaluarsa', 'ru.namaruangan')
            ->orderBy('pr.kdproduk')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function laporanPsikotropika(Request $request)
    {
        $produk = '';
        $tgl = '';
        if (isset($request['produk']) && $request['produk'] != '' && $request['produk'] != 'undefined') {
            $produk = "and pr.namaproduk ilike '%" . $request['produk'] . "%'";
        }
        if (isset($request['tglAwal']) && $request['tglAwal'] != '' && $request['tglAwal'] != 'undefined') {
            $tgl = "BETWEEN '" . $request['tglAwal'] . " 00:00:00' AND '" . $request['tglAkhir'] . " 23:59:59'";
        }
        // return [$tgl];
        $data = collect(DB::select(DB::raw("
           SELECT
                y.id,
                y.namaproduk,
                y.satuanstandar,
                y.hargaawal,
                SUM(y.saldoawal) AS saldoawal,
                SUM(y.qtyterima) AS qtyterima,
                SUM(y.qtykeluar) AS qtykeluar,
                SUM(y.returjual) AS returjual,
                SUM(y.returbeli) AS returbeli,
                SUM(y.saldoakhir) AS saldoakhir
                FROM
                (
                -- penerimaan barang
                SELECT
                    sp.norec,
                    pr.ID,
                    pr.namaproduk,
                -- 	djp.detailjenisproduk,
                    0 AS saldoawal,
                    ( spd.qtyproduk * spd.hasilkonversi ) AS qtyterima,
                    0 AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    round( 0 ) AS hargaawal,ss.satuanstandar 
                FROM
                    strukpelayanan_t sp
                    INNER JOIN strukpelayanandetail_t spd ON spd.nostrukfk = sp.norec
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sp.tglstruk $tgl
                    AND sp.objectkelompoktransaksifk = 99 
                    AND sp.statusenabled = TRUE
                    and pr.statusenabled = true
                    and djp.objectjenisprodukfk = 97
                    and pr.ispsikotropika = true
                    $produk

                    UNION ALL
                    -- returbeli
                    SELECT
                    sr.norec,
                    pr.ID,
                    pr.namaproduk,
                -- 	djp.detailjenisproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    0 AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    ppr.qtyproduk AS returbeli,
                    0 AS hargaawal,
                    ss.satuanstandar 
                FROM
                    strukretur_t AS sr
                    INNER JOIN strukreturdetail_t AS ppr ON sr.norec = ppr.strukreturfk 
                    INNER JOIN produk_m AS pr ON pr.ID = ppr.objectprodukfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sr.tglretur $tgl 
                    AND sr.statusenabled = TRUE
                    and pr.statusenabled = true
                    and pr.ispsikotropika = true
                    $produk

                UNION ALL

                --resep
                SELECT
                    x.norec,
                    x.ID,
                    x.namaproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    SUM ( x.jumlah ) AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    x.hargaawal,
                    x.satuanstandar 
                FROM
                    (
                    SELECT
                        pr.ID,
                        pp.norec,
                        pr.namaproduk,
                        pp.jumlah,
                        0 AS harganetto,
                        0 AS hargaawal,
                        pp.strukterimafk,
                -- 'Pembelian' as asalproduk,
                        ss.satuanstandar 
                    FROM
                        pelayananpasien_t pp
                        INNER JOIN produk_m pr ON pr.ID = pp.produkfk
                        LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                        LEFT JOIN strukresep_t st ON st.norec = pp.strukresepfk
                        LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                        LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                    WHERE
                        pp.isobat = 't' 
                        AND st.statusenabled = TRUE 
                        AND pp.tglpelayanan $tgl  
                        and pr.statusenabled = true
                        and pr.ispsikotropika = true
                        $produk
                    ) AS x 
                GROUP BY
                    x.norec,
                    x.ID,
                    x.namaproduk,
                    x.hargaawal,
                -- x.asalproduk,
                    x.satuanstandar 
                    
                    UNION ALL
                    
                    --obat bebas
                SELECT
                    sp.norec,
                    pr.ID,
                    pr.namaproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    ( spd.qtyproduk ) AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    round( 0 ) AS hargaawal,
                -- 'Pembelian' as asalproduk,
                    ss.satuanstandar 
                FROM
                    strukpelayanan_t sp
                    INNER JOIN strukpelayanandetail_t spd ON spd.nostrukfk = sp.norec
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sp.tglstruk $tgl 
                    AND SUBSTRING ( sp.nostruk, 1, 2 ) = 'OB' 
                    AND sp.statusenabled = TRUE 
                    and pr.statusenabled = true
                    and pr.ispsikotropika = true
                    $produk
                    
                    UNION ALL
                    
                    --amprah
                SELECT
                    sk.norec,
                    pr.ID,
                    pr.namaproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    ( kp.qtyproduk ) AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    0 AS hargaawal,
                -- 'Pembelian' as asalproduk,
                    ss.satuanstandar 
                FROM
                    strukkirim_t AS sk
                    INNER JOIN kirimproduk_t AS kp ON kp.nokirimfk = sk.norec
                    INNER JOIN produk_m AS pr ON pr.ID = kp.objectprodukfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sk.kdprofile = 1
                    AND sk.jenispermintaanfk = 1 
                    AND sk.statusenabled = TRUE 
                    AND kp.qtyproduk > 0 
                    AND sk.tglkirim $tgl
                    and pr.statusenabled = true
                    and pr.ispsikotropika = true
                    $produk
                    
                    UNION ALL
                    
                -- 	produk kadaluarsa
                    SELECT
                    spk.norec,
                    pr.id,
                    pr.namaproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    ( spk.qtyproduk ) AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    0 AS hargaawal,
                    ss.satuanstandar 
                FROM
                    stokprodukkadaluarsa_t AS spk
                    INNER JOIN produk_m as pr ON pr.id = spk.objectprodukfk
                    INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    INNER JOIN asalproduk_m ap ON ap.ID = spk.objectasalprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                    WHERE
                    spk.kdprofile = 1
                    AND spk.tglkadaluarsa $tgl
                    and pr.statusenabled = true
                    and pr.ispsikotropika = true
                    $produk
                    
                    union all 
                    
                    --returjual
                    SELECT
                    sr.norec,
                    pr.ID,
                    pr.namaproduk,
                -- 	djp.detailjenisproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    0 AS qtykeluar,
                    0 AS saldoakhir,
                    ppr.jumlah AS returjual,
                    0 AS returbeli,
                    0 AS hargaawal,
                    ss.satuanstandar 
                FROM
                    strukretur_t AS sr
                    INNER JOIN pelayananpasienretur_t AS ppr ON sr.norec = ppr.strukreturfk 
                    INNER JOIN produk_m AS pr ON pr.ID = ppr.produkfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sr.tglretur $tgl
                    and pr.ispsikotropika = true
                    $produk
                    AND sr.statusenabled = TRUE and pr.statusenabled = true
                    ) as y
                    GROUP BY 
                    y.id,
                y.namaproduk,
                y.satuanstandar,
                y.hargaawal
                        ")))->map(function ($item) {
            return (object) $item;
        });
        // $data1pluck = $data->pluck('id')->toArray();
        // $data1pluck = implode(', ', $data1pluck);


        foreach ($data->values() as $unit) {
            $item = $this->kartustokpertamaPsikotropika($unit->id, $tgl);
            // return [$item];
            $unit->saldoawal = $item->saldoakhir;
            $unit->saldoakhir = ($unit->saldoawal + $unit->qtyterima) - $unit->qtykeluar;
        }

        //         $finalData = $mergedData->groupBy('kdproduk')->map(function ($items) {
        //     $first = $items->first();
        //     $first->barang_keluar = $items->sum('barang_keluar');
        //     $first->amprahan = $items->sum('amprahan');
        //     $first->stok = $items->sum('stok');
        //     return $first;
        // })->values();
        return $this->respond($data);
    }
    public function kartustokpertamaPsikotropika($produkfk, $tgl)
    {
        $unit = collect(DB::select(DB::raw("
        SELECT * FROM kartustok_t where produkfk = $produkfk and tglinput $tgl ORDER BY tglinput limit 10;
        ")))->map(function ($item) {
            return (object) $item;
        });
        if ($unit->isNotEmpty()) {
            return $unit->first();
        } else {
            return (object) ['saldoakhir' => 0];
        }

    }
    public function laporanPsikotropikaProduk(Request $request)
    {
        $produk = '';
        if (isset($request['produk']) && $request['produk'] != '' && $request['produk'] != 'undefined') {
            $produk = "and namaproduk ilike '%" . $request['produk'] . "%'";
        }
        $unit = collect(DB::select(DB::raw("
        SELECT * FROM produk_m where ispsikotropika = true and statusenabled = true $produk limit 20;  
        ")))->map(function ($item) {
            return (object) $item;
        });


        return $this->respond($unit);
    }

    public function laporanNarkotik(Request $request)
    {
        $produk = '';
        $tgl = '';
        if (isset($request['produk']) && $request['produk'] != '' && $request['produk'] != 'undefined') {
            $produk = "and pr.namaproduk ilike '%" . $request['produk'] . "%'";
        }
        if (isset($request['tglAwal']) && $request['tglAwal'] != '' && $request['tglAwal'] != 'undefined') {
            $tgl = "BETWEEN '" . $request['tglAwal'] . " 00:00:00' AND '" . $request['tglAkhir'] . " 23:59:59'";
        }
        // return [$tgl];
        $data = collect(DB::select(DB::raw("
           SELECT
                y.id,
                y.namaproduk,
                y.satuanstandar,
                y.hargaawal,
                SUM(y.saldoawal) AS saldoawal,
                SUM(y.qtyterima) AS qtyterima,
                SUM(y.qtykeluar) AS qtykeluar,
                SUM(y.returjual) AS returjual,
                SUM(y.returbeli) AS returbeli,
                SUM(y.saldoakhir) AS saldoakhir
                FROM
                (
                -- penerimaan barang
                SELECT
                    sp.norec,
                    pr.ID,
                    pr.namaproduk,
                -- 	djp.detailjenisproduk,
                    0 AS saldoawal,
                    ( spd.qtyproduk * spd.hasilkonversi ) AS qtyterima,
                    0 AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    round( 0 ) AS hargaawal,ss.satuanstandar 
                FROM
                    strukpelayanan_t sp
                    INNER JOIN strukpelayanandetail_t spd ON spd.nostrukfk = sp.norec
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sp.tglstruk $tgl
                    AND sp.objectkelompoktransaksifk = 99 
                    AND sp.statusenabled = TRUE
                    and pr.statusenabled = true
                    and djp.objectjenisprodukfk = 97
                    and pr.isnarkotika = true
                    $produk

                    UNION ALL
                    -- returbeli
                    SELECT
                    sr.norec,
                    pr.ID,
                    pr.namaproduk,
                -- 	djp.detailjenisproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    0 AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    ppr.qtyproduk AS returbeli,
                    0 AS hargaawal,
                    ss.satuanstandar 
                FROM
                    strukretur_t AS sr
                    INNER JOIN strukreturdetail_t AS ppr ON sr.norec = ppr.strukreturfk 
                    INNER JOIN produk_m AS pr ON pr.ID = ppr.objectprodukfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sr.tglretur $tgl 
                    AND sr.statusenabled = TRUE
                    and pr.statusenabled = true
                    and pr.isnarkotika = true
                    $produk

                UNION ALL

                --resep
                SELECT
                    x.norec,
                    x.ID,
                    x.namaproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    SUM ( x.jumlah ) AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    x.hargaawal,
                    x.satuanstandar 
                FROM
                    (
                    SELECT
                        pr.ID,
                        pp.norec,
                        pr.namaproduk,
                        pp.jumlah,
                        0 AS harganetto,
                        0 AS hargaawal,
                        pp.strukterimafk,
                -- 'Pembelian' as asalproduk,
                        ss.satuanstandar 
                    FROM
                        pelayananpasien_t pp
                        INNER JOIN produk_m pr ON pr.ID = pp.produkfk
                        LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                        LEFT JOIN strukresep_t st ON st.norec = pp.strukresepfk
                        LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                        LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                    WHERE
                        pp.isobat = 't' 
                        AND st.statusenabled = TRUE 
                        AND pp.tglpelayanan $tgl  
                        and pr.statusenabled = true
                        and pr.isnarkotika = true
                        $produk
                    ) AS x 
                GROUP BY
                    x.norec,
                    x.ID,
                    x.namaproduk,
                    x.hargaawal,
                -- x.asalproduk,
                    x.satuanstandar 
                    
                    UNION ALL
                    
                    --obat bebas
                SELECT
                    sp.norec,
                    pr.ID,
                    pr.namaproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    ( spd.qtyproduk ) AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    round( 0 ) AS hargaawal,
                -- 'Pembelian' as asalproduk,
                    ss.satuanstandar 
                FROM
                    strukpelayanan_t sp
                    INNER JOIN strukpelayanandetail_t spd ON spd.nostrukfk = sp.norec
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sp.tglstruk $tgl 
                    AND SUBSTRING ( sp.nostruk, 1, 2 ) = 'OB' 
                    AND sp.statusenabled = TRUE 
                    and pr.statusenabled = true
                    and pr.isnarkotika = true
                    $produk
                    
                    UNION ALL
                    
                    --amprah
                SELECT
                    sk.norec,
                    pr.ID,
                    pr.namaproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    ( kp.qtyproduk ) AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    0 AS hargaawal,
                -- 'Pembelian' as asalproduk,
                    ss.satuanstandar 
                FROM
                    strukkirim_t AS sk
                    INNER JOIN kirimproduk_t AS kp ON kp.nokirimfk = sk.norec
                    INNER JOIN produk_m AS pr ON pr.ID = kp.objectprodukfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sk.kdprofile = 1
                    AND sk.jenispermintaanfk = 1 
                    AND sk.statusenabled = TRUE 
                    AND kp.qtyproduk > 0 
                    AND sk.tglkirim $tgl
                    and pr.statusenabled = true
                    and pr.isnarkotika = true
                    $produk
                    
                    UNION ALL
                    
                -- 	produk kadaluarsa
                    SELECT
                    spk.norec,
                    pr.id,
                    pr.namaproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    ( spk.qtyproduk ) AS qtykeluar,
                    0 AS saldoakhir,
                    0 AS returjual,
                    0 AS returbeli,
                    0 AS hargaawal,
                    ss.satuanstandar 
                FROM
                    stokprodukkadaluarsa_t AS spk
                    INNER JOIN produk_m as pr ON pr.id = spk.objectprodukfk
                    INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    INNER JOIN asalproduk_m ap ON ap.ID = spk.objectasalprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                    WHERE
                    spk.kdprofile = 1
                    AND spk.tglkadaluarsa $tgl
                    and pr.statusenabled = true
                    and pr.isnarkotika = true
                    $produk
                    
                    union all 
                    
                    --returjual
                    SELECT
                    sr.norec,
                    pr.ID,
                    pr.namaproduk,
                -- 	djp.detailjenisproduk,
                    0 AS saldoawal,
                    0 AS qtyterima,
                    0 AS qtykeluar,
                    0 AS saldoakhir,
                    ppr.jumlah AS returjual,
                    0 AS returbeli,
                    0 AS hargaawal,
                    ss.satuanstandar 
                FROM
                    strukretur_t AS sr
                    INNER JOIN pelayananpasienretur_t AS ppr ON sr.norec = ppr.strukreturfk 
                    INNER JOIN produk_m AS pr ON pr.ID = ppr.produkfk
                    LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    LEFT JOIN jenisproduk_m AS jp ON jp.ID = djp.objectjenisprodukfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk 
                WHERE
                    sr.tglretur $tgl
                    and pr.isnarkotika = true
                    $produk
                    AND sr.statusenabled = TRUE and pr.statusenabled = true
                    ) as y
                    GROUP BY 
                    y.id,
                y.namaproduk,
                y.satuanstandar,
                y.hargaawal
           ")))->map(function ($item) {
            return (object) $item;
        });
        // $data1pluck = $data->pluck('id')->toArray();
// $data1pluck = implode(', ', $data1pluck);


        foreach ($data->values() as $unit) {
            $item = $this->kartustokpertamaNarkotik($unit->id, $tgl);
            // return $item; 
            $unit->saldoawal = $item->saldoakhir;
            $unit->saldoakhir = ($unit->saldoawal + $unit->qtyterima) - $unit->qtykeluar;
        }

        //         $finalData = $mergedData->groupBy('kdproduk')->map(function ($items) {
        //     $first = $items->first();
        //     $first->barang_keluar = $items->sum('barang_keluar');
        //     $first->amprahan = $items->sum('amprahan');
        //     $first->stok = $items->sum('stok');
        //     return $first;
        // })->values();
        return $this->respond($data);
    }
    public function kartustokpertamaNarkotik($produkfk, $tgl)
    {
        $unit = collect(DB::select(DB::raw("
        SELECT * FROM kartustok_t where produkfk = $produkfk and tglinput $tgl ORDER BY tglinput limit 10;
        ")))->map(function ($item) {
            return (object) $item;
        });
        if ($unit->isNotEmpty()) {
            return $unit->first();
        } else {
            return (object) ['saldoakhir' => 0];
        }

    }
    public function laporanNarkotikProduk(Request $request)
    {
        $produk = '';
        if (isset($request['produk']) && $request['produk'] != '' && $request['produk'] != 'undefined') {
            $produk = "and namaproduk ilike '%" . $request['produk'] . "%'";
        }
        $unit = collect(DB::select(DB::raw("
        SELECT * FROM produk_m where isnarkotika = true and statusenabled = true $produk limit 20;
        ")))->map(function ($item) {
            return (object) $item;
        });


        return $this->respond($unit);
    }

    public function LaporanPenerimaan(Request $request)
    {
        $rangeDate = [
            $request->tglAwal, 
            $request->tglAkhir . ' 23:59:59' 
        ];

        $data = DB::table('strukpelayanan_t as sp')
            ->select([
                'sp.nostruk',
                'sp.totalpembulatan',
                'pr.namaproduk',
                'ss.satuanstandar',
                'spd.hargasatuan',
                'pr.kdproduk',
                'djp.detailjenisproduk',
                'pr.kdproduk',
                'sp.namapengadaan',
                'spd.nobatch',
                'spd.qtyproduk',
                'sp.namarekanan',
                'sp.nosppb',
                'sp.tglspk',
                'sp.nofaktur',
                DB::raw('((spd.hargasatuan)*(spd.qtyproduk)) as subtotal'),
                DB::raw('DATE(sp.tglstruk) as tglstruk'), 
                'sp.tglkontrak',
                'sp.tgldokumen',
                DB::raw('DATE(sp.tglfaktur) as tglfaktur'),
                'sp.totalharusdibayar',
                DB::raw('DATE(spd.tglkadaluarsa) as tglkadaluarsa'), 
            ])
            ->leftJoin('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->where('sp.statusenabled', true)
            ->where('sp.ispinjam', null)
            ->where('spd.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', '518')
            ->whereBetween('sp.tglstruk', $rangeDate)
            ->where('spd.qtyproduk', '>', 0)
            ->orderBy('sp.nostruk');

            if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
                $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['nama'] . '%');
            }

            if (isset($request['status']) && $request['status'] == "" && $request['status'] == "undefined")  {
                $data = $data->get();
            }

            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "O")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2546);
            }
    
            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "B")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2549);
            }
    
            if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "A")  {
                $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2547);
            }

        $data = $data->get();
        
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function LaporanDistribusi(Request $request)
    {
        $rangeDate = [
            $request->tglAwal, 
            $request->tglAkhir . ' 23:59:59' 
        ];

        $data =  DB::table('strukkirim_t as sk')
        ->join('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sk.norec')
        ->join('produk_m as pr', 'pr.id', '=', 'kp.objectprodukfk')
        ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        ->join('ruangan_m as ru1', 'ru1.id', '=', 'sk.objectruanganfk')
        ->join('ruangan_m as ru2', 'ru2.id', '=', 'sk.objectruangantujuanfk')
        ->where('sk.statusenabled', true)
        ->where('kp.statusenabled', true)
        ->where('sk.ispenarikan', null)
        ->where('sk.isfloor', null)
        ->where('sk.objectruangantujuanfk', '<>', 344)
        ->whereBetween('sk.tglkirim', $rangeDate)
        ->select(
            DB::raw('DATE(sk.tglkirim) as tglkirim'),
            'sk.nokirim',
            'ru1.namaruangan as ruanganpengirim',
            'ru2.namaruangan as ruangantujuan',
            'pr.namaproduk',
            'pr.kdproduk',
            'ss.satuanstandar',
            DB::raw('AVG(kp.hargasatuan) AS hargasatuan_rata2'),
            'kp.qtyorder',
            DB::raw('SUM(kp.qtyprodukkonfirmasi) as qtyprodukkonfirmasi'),
            DB::raw('SUM(kp.qtyprodukkonfirmasi) * AVG(kp.hargasatuan) AS totalharga')
        )
        ->groupBy(
            'sk.tglkirim',
            'sk.nokirim',
            'pr.kdproduk',
            'ru1.namaruangan',
            'ru2.namaruangan',
            'pr.namaproduk',
            'ss.satuanstandar',
            'kp.qtyorder'
        );

        if (isset($request['ruanganpengirim']) && $request['ruanganpengirim'] != "" && $request['ruanganpengirim'] != "undefined") {
            $data = $data->where('ru1.id', '=', $request['ruanganpengirim']);
        }

        if (isset($request['ruangantujuan']) && $request['ruangantujuan'] != "" && $request['ruangantujuan'] != "undefined") {
            $data = $data->where('ru2.id', '=', $request['ruangantujuan']);
        }

        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == 'non_satelit') {
            $data = $data->where('ru2.namaruangan', 'not like','%SATELIT FARMASI%');
        }

        
        
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function LaporanKirimKadaluarsa(Request $request)
    {
        $rangeDate = [
            $request->tglAwal . ' 00:00:00', 
            $request->tglAkhir . ' 23:59:59' 
        ];

        $data = DB::table('strukkirim_t as sk')
            ->join('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sk.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'kp.objectprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->join('stokprodukdetail_t as spd', 'spd.norec', '=', 'kp.stokprodukdetailfk')
            ->select(
                DB::raw('DATE(sk.tglkirim) as tglkirim'),
                'sk.nokirim',
                'spd.nobatch',
                DB::raw('DATE(spd.tglkadaluarsa) as tglkadaluarsa'),
                'pr.kdproduk',
                'pr.namaproduk',
                DB::raw('SUM(kp.qtyprodukkonfirmasi) as qtyprodukkonfirmasi'),
                'ss.satuanstandar',
                'kp.hargasatuan',
                DB::raw('SUM(kp.qtyprodukkonfirmasi * kp.hargasatuan) as totalharga')
            )
            ->whereBetween('sk.tglkirim', $rangeDate)
            ->where('sk.statusenabled', true)
            ->where('sk.objectruangantujuanfk', 344)
            ->where('kp.statusenabled', true)
            ->where('kp.qtyprodukkonfirmasi', '>', 0)
            ->orderBy('sk.tglkirim', 'asc')
            ->groupBy(
                'sk.tglkirim',
                'sk.nokirim',
                'spd.nobatch',
                'spd.tglkadaluarsa',
                'pr.kdproduk',
                'pr.namaproduk',
                'kp.hargasatuan',
                'ss.satuanstandar'
            );
            
            $data = $data->get();
            
            $result = array(
                'data' => $data,
                'message' => 'ea@epic',
            );
            return $this->respond($result);
    }

    public function LaporanPenerimaanPinjam(Request $request)
    {
        $rangeDate = [
            $request->tglAwal, 
            $request->tglAkhir . ' 23:59:59' 
        ];

        $data = DB::table('strukpelayanan_t as sp')
            ->select([
                'sp.nostruk',
                'sp.totalpembulatan',
                'pr.namaproduk',
                'ss.satuanstandar',
                'spd.hargasatuan',
                'pr.kdproduk',
                'djp.detailjenisproduk',
                'pr.kdproduk',
                'sp.namapengadaan',
                'spd.nobatch',
                'spd.nobatch',
                'spd.qtyproduk',
                'sp.namarekanan',
                'sp.nosppb',
                'sp.tglspk',
                'sp.nofaktur',
                DB::raw('((spd.hargasatuan)*(spd.qtyproduk)) as subtotal'),
                DB::raw('DATE(sp.tglstruk) as tglstruk'), 
                'sp.tglkontrak',
                'sp.tgldokumen',
                DB::raw('DATE(sp.tglfaktur) as tglfaktur'),
                'sp.totalharusdibayar',
                DB::raw('DATE(spd.tglkadaluarsa) as tglkadaluarsa'), 
                'rk2.namarekanan as pinjam'
            ])
            ->leftJoin('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoin('rekanan_m as rk2', 'rk2.id', '=', 'sp.rekananpinjamfk')
            ->where('sp.statusenabled', true)
            ->where('sp.ispinjam', true)
            ->where('spd.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', '518')
            ->where('spd.qtyproduk', '>', 0)
            ->whereBetween('sp.tglstruk', $rangeDate)
            ->orderBy('sp.nostruk');

        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['nama'] . '%');
        }

        if (isset($request['status']) && $request['status'] == "" && $request['status'] == "undefined")  {
            $data = $data->get();
        }

        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "O")  {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2546);
        }

        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "B")  {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2549);
        }

        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "A")  {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2547);
        }

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }



}
