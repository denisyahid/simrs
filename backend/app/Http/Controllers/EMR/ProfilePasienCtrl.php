<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Master\Pasien;
use App\Models\Master\Ruangan;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\AksesEMR;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\HistoriAksesEMR;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\RiwayatKontrol;
use App\Traits\Valet;
use Exception;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Validator;

class ProfilePasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function headerPasien(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('agama_m as agm', 'ps.objectagamafk', '=', 'agm.id')
            ->leftJoin('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('hubungankeluarga_m as hk', 'hk.id', '=', 'ps.hubungankeluargapj')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftJoin('pendidikan_m as pe', 'ps.objectpendidikanfk', '=', 'pe.id')
            ->leftJoin('suku_m as su', 'ps.objectsukufk', '=', 'su.id')
            ->leftJoin('statusperkawinan_m as sm', 'ps.objectstatusperkawinanfk', '=', 'sm.id')
            ->leftJoin('catatandokter_t as ct', 'ct.nocmfk', '=', 'ps.id')
            ->LEFTJOIN('golongandarah_m as gm', 'gm.id', '=', 'ps.objectgolongandarahfk')
            ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->leftJoin('catatandokter_t as latest_ct', function ($join) {
                $join->on('ct.nocmfk', '=', 'latest_ct.nocmfk')
                    ->on('ct.tanggal', '<', 'latest_ct.tanggal');
            })
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.alergi',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.penanggungjawab',
                'hk.hubungankeluarga',
                'ps.tempatlahir',
                'su.suku',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.statusemr',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'emr.tglberakhir',
                'emr.tglmulai',
                'emr.objectkelompokuserfk',
                'emr.pegawaipemohonfk',
                'ps.nohp',
                'ps.namaayah',
                'pd.noregistrasi',
                'ps.namaibu',
                'ps.email',
                'pd.objectruanganlastfk',
                'agm.agama',
                'pe.pendidikan',
                'pk.pekerjaan',
                'ps.isfoto',
                'ps.objectkebangsaanfk',
                'ps.filename',
                'agm.id as objectagamafk',
                'kp.kelompokpasien',
                'gm.golongandarah',
                'sm.statusperkawinan',
                'latest_ct.catatan as catatan',
                DB::raw("EXTRACT(YEAR FROM AGE(pd.tglregistrasi, ps.tgllahir)) || ' Thn '
                || EXTRACT(MONTH FROM AGE(pd.tglregistrasi, ps.tgllahir)) || ' Bln '
                || EXTRACT(DAY FROM AGE(pd.tglregistrasi, ps.tgllahir)) || ' Hr' AS umur")
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk']);

        if (isset($r['norec_pd']) && $r['norec_pd']) {
            $data =  $data->where('pd.norec', '=', $r['norec_pd']);
        }

        $data =  $data->first();

        $alamat = DB::table('pasien_m as ps')
            ->select('ds.namadesakelurahan', 'kec.namakecamatan', 'alm.rtrw', 'kab.namakotakabupaten')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('desakelurahan_m as ds', 'ds.id', '=', 'alm.objectdesakelurahanfk')
            ->leftJoin('kecamatan_m as kec', 'kec.id', '=', 'alm.objectkecamatanfk')
            ->leftJoin('kotakabupaten_m as kab', 'kab.id', '=', 'alm.objectkotakabupatenfk')
            ->union(
                DB::table('pasien_m as ps')
                    ->select('ds.namadesakelurahan', 'kec.namakecamatan', 'alm.rtrw', 'kab.namakotakabupaten')
                    ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
                    ->rightJoin('desakelurahan_m as ds', 'ds.id', '=', 'alm.objectdesakelurahanfk')
                    ->rightJoin('kecamatan_m as kec', 'kec.id', '=', 'alm.objectkecamatanfk')
                    ->rightJoin('kotakabupaten_m as kab', 'kab.id', '=', 'alm.objectkotakabupatenfk')
                    ->where('ps.kdprofile', (int)$this->kdProfile)
                    ->where('ps.statusenabled', true)
                    ->where('ps.id', $r['nocmfk'])
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->get();

        // return $alamat;
        $registrasi = DB::table('pasiendaftar_t as pd')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
                // $join->limit))
            })
            ->leftJoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->LEFTJOIN('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'pd.objectpegawairawatbersamafk')
            ->LEFTJOIN('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->LEFTJOIN('jenispelayanan_m as jp', 'jp.id', '=', 'pd.jenispelayanan')
            // ->LEFTJOIN('intruksi_cppt_t as ic','ic.norecpd','=','pd.norec')
            // ->leftjoin('strukorder')
            ->select(
                'pd.noregistrasi',
                'pd.norec',
                'pd.isclosing',
                'pd.isPenunjangSusulan',
                'pd.isPenunjangSusulanRad',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'asru.asalrujukan',
                'ru.namaruangan',
                'kl.namakelas',
                'pd.nocmfk',
                'rk.namarekanan',
                'pg.namalengkap as dokter',
                'pg.id as iddokter',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'apd.norec as norec_apd',
                'apd.kelasrawatfk',
                'pd.isnaikkelas',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan',
                'pd.jenispelayanan as jenispelayananfk',
                'apd.objectkelasfk',
                'ru.objectdepartemenfk',
                'pd.objectpegawaifk',
                'pa.nosep',
                'pa.tglsep',
                'pd.inacbg_totalgrouper',
                'ru.kdsubspesialisbpjs',
                'ru.namasubspesialisbpjs',
                'pa.dpjplayan_kode',
                'pa.dpjplayan_nama',
                'pd.tinggibadan',
                'pd.beratbadan',
                'pd.suhu',
                'pd.nadi',
                'pd.pernafasan',
                'pd.tekanandarah',
                'pd.spo2',
                'jp.jenispelayanan as jenispelayananBaru',
                'pd.isberkas',
                'pd.iskelastitip',
                'pd.ispenjadwalankemoterapi',
                'pg2.namalengkap as dokterrawatbersama',
                'pd.tglmeninggal',
                'pd.tglclosing',
                // 'ic.isconfirm',
                // 'ic.norec_cpptdetail',
                'pa.nosurat',
                DB::raw("
                    FLOOR(
                        EXTRACT(EPOCH FROM (
                            COALESCE(apd.tglkeluar, NOW()) - apd.tglmasuk
                        ))::INTEGER / (24 * 3600)
                    ) || ' hari ' ||
                    FLOOR(
                        EXTRACT(EPOCH FROM (
                            COALESCE(apd.tglkeluar, NOW()) - apd.tglmasuk
                        ))::INTEGER % (24 * 3600) / 3600
                    ) || ' jam ' ||
                    FLOOR(
                        EXTRACT(EPOCH FROM (
                            COALESCE(apd.tglkeluar, NOW()) - apd.tglmasuk
                        ))::INTEGER % 3600 / 60
                    ) || ' menit' AS selisihwaktu
                ")
            )
            // ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk']);

        // if (isset($r['dari']) && $r['dari']) {
        //     $registrasi =  $registrasi->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        // }
        // if (isset($r['sampai']) && $r['sampai']) {
        //     $registrasi =  $registrasi->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        // }
        if (isset($r['norec_pd']) && $r['norec_pd']) {
            $registrasi =  $registrasi->where('pd.norec', '=', $r['norec_pd']);
        }
        // $registrasi = $registrasi->get();
        // return $registrasi;
        $jmlRegis = $registrasi->count();
        
        if (isset($r['limit']) && $r['limit'] != '') {
            $registrasi = $registrasi->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $registrasi = $registrasi->offset($r['offset']);
        }
        // $registrasi =  $registrasi->orderByDesc('pd.tglregistrasi');
        $registrasi =  $registrasi->orderByDesc('apd.tglmasuk');
        $registrasi =  $registrasi->orderByDesc('dp.namadepartemen');
        // ->limit(10)
        $registrasi =  $registrasi->get();
        // return $registrasi;
        $apd = null;
        if (isset($r['norec_apd']) && $r['norec_apd'] != 'undefined' &&  $r['norec_apd'] != '') {
            $apd = DB::table('antrianpasiendiperiksa_t as apd')
                ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftJoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
                ->select(
                    'apd.norec as norec_apd',
                    'apd.objectkelasfk',
                    'apd.objectruanganfk',
                    'apd.kelasrawatfk',
                    'ru.namaruangan',
                    'kl.namakelas',
                    'apd.noregistrasifk',
                    'apd.tglmasuk as tglregistrasi',
                    'apd.iskonsul as konsul',
                )
                ->where('apd.kdprofile', (int)$this->kdProfile)
                ->where('apd.statusenabled', true)
                ->where('apd.norec',  $r['norec_apd'])
                ->first();
        }
        $apdd = DB::table('antrianpasiendiperiksa_t as apd')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->select(
                'apd.norec as norec_apd',
                'apd.objectkelasfk',
                'apd.objectruanganfk',
                'apd.kelasrawatfk',
                'ru.namaruangan',
                'kl.namakelas',
                'apd.noregistrasifk',
                'apd.tglmasuk as tglregistrasi',
                'apd.iskonsul as konsul'
            )
            ->where('pd.nocmfk', $r['nocmfk']) // Ambil data berdasarkan `nocmfk`
            ->where('apd.kdprofile', (int)$this->kdProfile)
            ->where('apd.statusenabled', true)
            ->where('apd.iskonsul', true)
            ->get();

        $diagnosaX = DB::table('detaildiagnosapasien_t as ddp')
            ->leftJoin('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->leftJoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                'dg.kddiagnosa',
                'dg.id',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'jd.jenisdiagnosa',
                'ddp.keterangan',
                'ddp.noregistrasi',
                DB::raw("case when ddp.iskasusbaru is not null then 'Baru'
            when ddp.iskasuslama is not null then 'Lama'
            else ''
            end as kasus  ")
            )
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('ddp.statusenabled', true)
            ->where('ddp.objectjenisdiagnosafk', $this->settingFix('kdDiagnosaUtama'));
        if (isset($r['dari']) && $r['dari']) {
            $diagnosaX =  $diagnosaX->where('ddp.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai']) {
            $diagnosaX =  $diagnosaX->where('ddp.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $diagnosaX = $diagnosaX->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $diagnosaX = $diagnosaX->offset($r['offset']);
        }
        $diagnosaX = $diagnosaX->orderByDesc('ddp.tglinputdiagnosa');
        $diagnosaX = $diagnosaX->get();

        $laboratRad = DB::table('strukorder_t as so')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')

            ->select(
                'so.norec',
                'so.noregistrasifk',
                'so.keteranganorder'
            )
            ->where('so.kdprofile', (int)$this->kdProfile)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('so.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->orderByDesc('so.tglorder')
            ->get();


        foreach ($registrasi as $d) {
            $d->apd = null;
            if (!empty($apd)) {
                if ($d->norec == $apd->noregistrasifk) {
                    $d->apd = $apd;
                }
            }

            $d->apdd = null;
            $d->apdd = $apdd->where('noregistrasifk', $d->norec)->all();

            $d->billing = 0;
            if (!empty($data)) {
                $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
            }
            $d->diagnosis = [];
            foreach ($diagnosaX as $dd) {
                if ($d->noregistrasi == $dd->noregistrasi) {
                    $d->diagnosis[]    = $dd;
                }
            }
            $d->laboratorium = [];
            $d->radiologi = [];
            foreach ($laboratRad as $ss) {
                if ($d->norec == $ss->noregistrasifk) {

                    if ($ss->keteranganorder == 'Order Radiologi') {
                        $d->radiologi[]    = $ss;
                    }
                    if ($ss->keteranganorder == 'Order Laboratorium') {
                        $d->laboratorium[]    = $ss;
                    }
                }
            }
        }
        $totalBiaya = 0;
        if (count($registrasi) > 0) {
            $totalBiaya =  PelayananPasien::totalTagihan($registrasi[0]->noregistrasi);
            $registrasi[0]->billing = $totalBiaya;
        }

        $data->isFilterProdukLab = $this->settingFix('isFilterProdukLab');
        $getEval = DB::table('evaluasi_pasien_t')
            ->where('pasienfk', '@>', '[' . json_encode($data->nocmfk) . ']')
            ->whereDate('tanggal', '>=', date('Y-m-d'))
            ->where('statusenabled', true)
            ->orderBy('tanggal', 'desc')
            ->first();
        $data->iseval = isset($getEval) ? true : false;
        $data->evaluasi_pasien = $getEval;
        $result['pasien'] = $data;
        $result['alamat'] = $alamat;
        $result['registrasi'] = $registrasi;
        $result['count_registrasi'] = $jmlRegis;
        $result['idDepartemenRI'] = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $result['pasien']->enabledEMRSimrsLama = $this->settingFix('enabledEMRSimrsLama');
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function getRiwyatPasien(Request $req)
    {

        $registrasi = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            // ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->join('agama_m as agm', 'ps.objectagamafk', '=', 'agm.id')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftJoin('pendidikan_m as pe', 'ps.objectpendidikanfk', '=', 'pe.id')
            ->leftJoin('suku_m as su', 'ps.objectsukufk', '=', 'su.id')
            ->leftJoin('catatandokter_t as ct', 'ct.nocmfk', '=', 'ps.id')
            ->leftJoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->leftJoin('catatandokter_t as latest_ct', function ($join) {
                $join->on('ct.nocmfk', '=', 'latest_ct.nocmfk')
                    ->whereRaw('ct.tanggal < latest_ct.tanggal');
            })
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->selectRaw("
                ps.nocm,
                ps.id AS nocmfk,
                pd.nocmfk AS pd_nocmfk,
                ps.namapasien,
                ps.tgllahir,
                ps.tempatlahir,
                su.suku,
                ps.objectjeniskelaminfk,
                jk.jeniskelamin,
                ps.noidentitas,
                ps.nobpjs,
                ps.statusemr,
                ps.noasuransilain,
                ps.alamatlengkap,
                ps.notelepon,
                emr.tglberakhir,
                emr.tglmulai,
                emr.objectkelompokuserfk,
                emr.pegawaipemohonfk,
                ps.nohp,
                ps.namaayah,
                pd.noregistrasi,
                ps.namaibu,
                ps.email,
                pd.objectruanganlastfk,
                agm.agama,
                pe.pendidikan,
                pk.pekerjaan,
                ps.isfoto,
                ps.objectkebangsaanfk,
                ps.filename,
                kp.kelompokpasien,
                latest_ct.catatan AS catatan,
                EXTRACT(YEAR FROM AGE(pd.tglregistrasi, ps.tgllahir)) || ' Thn ' ||
                EXTRACT(MONTH FROM AGE(pd.tglregistrasi, ps.tgllahir)) || ' Bln ' ||
                EXTRACT(DAY FROM AGE(pd.tglregistrasi, ps.tgllahir)) || ' Hr' AS umur
            ")
            ->where('ps.kdprofile', '=', (int)$this->kdProfile)
            ->where('ps.statusenabled', '=', true)
            ->where('ps.id', $req['nocmfk'])
            ->get();

        $result['registrasi'] = $registrasi;

        return $this->respond($result);
    }

    public function detailPelayanan(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $status = true;
        $paramsLimit = 1000;

        $statusPasien =  DB::table('pasiendaftar_t as pd')
            ->leftJoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('pasien_m as pa', 'pa.id', '=', 'pd.nocmfk')
            ->leftJoin('kondisipasien_m as kp', 'kp.id', '=', 'pd.objectkondisipasienfk')
            ->select(
                'pa.alergi',
                'pd.statuspasien',
                'sp.statuspulang',
                'kp.kondisipasien',
                'pd.tglpulang',
                DB::raw("(pd.tglpulang::date - pd.tglregistrasi::date)  + 1 || ' hari' AS lamarawat")
            )
            ->where('pd.norec', $r['norec_pd'])
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $vitalSign = DB::connection('mongodb')
            ->table('VitalSign')
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->first();

        $orderBedah = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->join('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->join('pegawai_m as peg', 'peg.id', 'so.objectpegawaiorderfk')
            ->join('departemen_m as dep2', 'dep2.id', 'ruTu.objectdepartemenfk')

            ->where('so.kdprofile', $this->kdProfile)
            ->where('pd.norec', $r['norec_pd'])
            ->whereIn('dep2.id', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('so.statusenabled', true)
            ->count();

        $tindakanResep = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukresep_t as sr', function ($join) {
                $join->on('sr.norec', '=', 'pp.strukresepfk')
                    ->whereNull('sr.orderfk');
            })
            // ->leftJOIN('strukorder_t as so', function ($join) {
            //     $join->on('so.norec', '=', 'pp.strukorderfk')
            //         ->whereNull('pp.strukorderfk');
            // })
            ->select(
                'prd.namaproduk',
                'ru.namaruangan',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                DB::raw("

                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)

                 as total
                 ,'Selesai' as status")
            )
            ->whereNull('pp.strukorderfk')
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderByDesc('pp.tglpelayanan')
            ->limit($paramsLimit)
            ->get();

        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));

        $orderResep = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->join('produk_m as prd', 'prd.id', '=', 'op.objectprodukfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
            ->select(
                'prd.namaproduk',
                'so.tglorder as tglpelayanan',
                'ru.namaruangan',
                'so.noorder',
                'pd.noregistrasi',
                'pg.reportdisplay as namapegawai',
                'so.norec as strukresepfk',
                'op.jumlah',
                DB::raw("

                (
                    (op.hargasatuan  - case when op.hargadiscount is null then 0 else op.hargadiscount end)
                     * op.jumlah)

                 as total
                 ,'Pending' as status")
            )
            ->where('so.statusenabled', true)
            ->where('so.kdprofile', $kdProfile)
            ->where('so.noregistrasifk', $r['norec_pd'])
            ->where('so.objectkelompoktransaksifk',  $set->objectkelompoktransaksifk)
            ->where('so.statusorder', $this->settingFix('statusMenungguApotik'))
            ->orderByDesc('so.tglorder')
            ->limit($paramsLimit)
            ->get();

        $konsul = DB::table('strukorder_t as so')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            // ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->select(
                'so.norec',
                'so.konsultasi',
                'so.rawatbersama',
                'so.tglorder',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'ru.namaruangan as ruanganasal',
                'pg.id as pegawaifk',
                'pg.namalengkap',
                'pd.objectkelasfk as kelasfk_pd',
                'apd.objectkelasfk as kelasfk_apd',
                // 'kls.namakelas',
                'rutuju.namaruangan as ruangantujuan',
                'pet.namalengkap as pengonsul',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'so.keteranganlainnya',
                DB::raw("case when so.keteranganlainnya is not null
                then 'Selesai' else 'Menunggu Jawaban' end
                as status")
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.noregistrasifk', $r['norec_pd'])
            ->where('so.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
            ->orderBy('so.tglorder', 'desc')
            ->get();

        $berkas = DB::table('emrdokumen_t as emrdk')
            ->join('pasiendaftar_t as pd', 'pd.noregistrasi', 'emrdk.noregistrasi')
            ->select('emrdk.*')
            ->where('emrdk.statusenabled', true)
            ->where('emrdk.kdprofile', $this->kdProfile)
            ->where('pd.norec', $r['norec_pd'])
            ->get();


        $diagnosaX = DB::table('detaildiagnosapasien_t as ddp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ddp.noregistrasifk')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'jd.jenisdiagnosa',
                'ddp.keterangan',
                DB::raw("'ICD X' as jenis")
            )
            ->where('ddp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderByDesc('ddp.tglinputdiagnosa')
            ->limit($paramsLimit)
            ->get();
        $diagnosaIX = DB::table('detaildiagnosatindakanpasien_t as ddt')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ddt.noregistrasifk')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->select(
                'dt.kddiagnosatindakan as kddiagnosa',
                'dt.namadiagnosatindakan as namadiagnosa',
                'ddt.keterangantindakan as keterangan',
                'ddt.tglinputdiagnosa',
                DB::raw("'ICD IX' as jenis, null as jenisdiagnosa")
            )
            ->where('ddt.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderByDesc('ddt.tglinputdiagnosa')
            ->limit($paramsLimit)
            ->get();
        $depLab = $this->settingFix('idDepartemenLab');
        $depRad = $this->settingFix('idDepartemenRadiologi');
        $depBedah = $this->settingFix('idDepartemenBedah');
        $expertise = DB::table('hasilradiologi_t as hh')
            ->join('pelayananpasien_t as pp', function ($j) {
                $j->on('pp.norec', '=', 'hh.pelayananpasienfk')->where('pp.statusenabled', true);
            })
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hh.noregistrasifk')
            ->where('hh.noregistrasifk', $r['norec_pd'])
            ->where('hh.statusenabled', true)
            ->select('pp.strukorderfk', 'hh.keterangan as expertise', 'pp.norec as norec_pp', 'hh.norec as norec_exper');

        $laboratRad = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'op.noorderfk', '=', 'so.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.norec_apd')
            ->join('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftjoin('pelayananpasienpetugas_t as pts', 'pts.pelayananpasien', '=', 'pp.norec')
            ->leftJoinSub($expertise, 'hh', 'so.norec', '=', 'hh.strukorderfk')
            ->leftjoin('ris_order as ris', function ($j) {
                $j->on('ris.order_no', '=', 'so.noorder')->on(DB::raw("cast(op.objectprodukfk as text)"), '=', 'ris.order_code');
            })
            // ->leftjoin('hasilradiologi_t as hh', 'hh.pelayananpasienfk', '=', 'so.objectruangantujuanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'ru.namaruangan',
                'op.norec as norec_op',
                'pr.namaproduk',
                'pg.namalengkap',
                'ru.objectdepartemenfk',
                'so.tglpelayananawal as tgloperasi',
                'so.estimasiwaktuoperasi',
                'so.keteranganlainnya',
                'pd.norec as norec_pd',
                'pd.noregistrasi as noregistrasi',
                // 'pp.norec as norec_ppkun',
                // 'pts.objectpegawaifk as iddokterbaca',
                'apd.norec as norec_apd',
                'pr.sanata_jasa_id',
                DB::raw(" case when so.statusorder = 1 then 'verifikasi'
                when so.statusorder = 2 then 'selesai'
                else 'pending' end as status,
                case when so.statusorder = 1 then 'danger'
                when so.statusorder = 2 then 'green'
                else 'orange' end as color_status,hh.expertise,hh.norec_pp,hh.norec_exper,
                so.objectruangantujuanfk,
                ris.patient_id || '-' || ris.order_cnt as radiologiid,
                ris.order_complete,ris.accession_num,
                op.objectprodukfk")

            )
            ->where('so.kdprofile', $kdProfile)
            // ->where('so.noregistrasifk', $r['norec_pd'])
            // ->where('pd.nocmfk',$r['nocmfk'])
            ->whereIn('ru.objectdepartemenfk', [$depLab, $depRad, $depBedah])
            ->where('so.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->orderByDesc('so.tglorder')
            ->distinct()
            ->limit($paramsLimit);

        // $testvalue='';       //TEST CONDITION QUERY
        if (isset($r['riwayatpasien']) && $r['riwayatpasien'] == 'true') {
            $laboratRad = $laboratRad->where('pd.nocmfk', $r['nocmfk']);
            // $testvalue='masuk if';       //TEST CONDITION QUERY
        } else {
            $laboratRad = $laboratRad->where('so.noregistrasifk', $r['norec_pd']);
            // $testvalue='masuk else';        //TEST CONDITION QUERY
        }
        $laboratRad = $laboratRad->get();


        $laboratorium = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'op.noorderfk', '=', 'so.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.norec_apd')
            // ->join('pelayananpasien_t as pp','pp.strukorderfk','=','so.norec')
            // ->leftjoin('pelayananpasienpetugas_t as pts','pts.pelayananpasien','=','pp.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'ru.namaruangan',
                // 'op.norec as norec_op',
                // 'pr.namaproduk',
                'pg.namalengkap',
                'ru.objectdepartemenfk',
                'so.tglpelayananawal as tgloperasi',
                'so.estimasiwaktuoperasi',
                'so.keteranganlainnya',
                'pd.norec as norec_pd',
                'pd.noregistrasi as noregistrasi',
                'apd.norec as norec_apd',
                'so.objectruangantujuanfk'
                // 'pr.sanata_jasa_id'
            )
            ->where('so.kdprofile', $kdProfile)
            // ->where('so.noregistrasifk', $r['norec_pd'])
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('ru.objectdepartemenfk', $depLab)
            ->where('so.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->orderByDesc('so.tglorder')
            ->distinct()
            ->limit($paramsLimit)
            ->get();

        // return $this->respond($laboratRad);
        //  $hasil_LAB = collect(DB::select("select
        //     res.his_reg_no AS visit_trans_id,  res.lis_test_id AS tarif_id,
        //     res.test_name AS examination_name, res.result AS result_value,  res.test_units_name AS unit,
        //     res.reference_value AS normal_value,  res.result_comment AS metode,   res.test_group AS treatment_name,
        //     res.sequence AS urut, orz.patient_id AS rm_number,  res.authorization_date AS visit_date,    res.his_test_id,
        //     res.test_name AS tarif_name,  res.test_flag_sign AS flag ,res.authorization_user as analis
        //     from result_bridge as res
        //     join order_bridge as orz on orz.order_number=res.his_reg_no
        //     join pasiendaftar_t as pd on pd.noregistrasi=orz.visit_number
        //     where pd.norec = '$r[norec_pd]'
        //     "))->toArray();

        $hasil_VansLAB = $this->hasilLab($r['norec_pd'])['hasil_VansLAB'] ?? [];

        $hasil_LAB_MANUAL = $this->hasilLab($r['norec_pd'])['hasil_LAB_MANUAL'] ?? [];


        $asesmenAwal = DB::table('emrpasien_t as emrp')
            ->where('emrp.statusenabled', true)
            ->where('emrp.kdprofile', $this->kdProfile)
            ->where('emrp.noregistrasifk', $r['norec_pd'])
            ->where('emrp.jenisemr', '=', 'asesmenawal')
            ->orderByDesc('emrp.tglemr')
            ->first();

        // $EMR_ = DB::table('emrpasien_t as emrp')
        //     ->where('emrp.statusenabled', true)
        //     ->where('emrp.kdprofile', $this->kdProfile)
        //     ->where('emrp.noregistrasifk', $r['norec_pd'])
        //     ->where('emrp.jenisemr', '=', 'asesmen_medis')
        //     ->get();


        // $EMR_FORM = DB::table('emrpasienform_t as emrp')
        //     ->where('emrp.statusenabled', true)
        //     ->where('emrp.kdprofile', $this->kdProfile)
        //     ->where('emrp.noregistrasifk', $r['norec_pd'])
        //     ->where('emrp.table', '!=', 'VitalSign')
        //     ->get();
        $EMR_FORM = DB::connection('mongodb')
            ->table('#ResumeEMR')
            ->where('kdprofile', $this->kdProfile)
            ->where('noregistrasifk', $r['norec_pd'])
            ->where('table', '!=', 'VitalSign')
            ->where('table', '!=', 'AsesmenAwal')
            ->where('statusenabled', true)
            ->orderByDesc('last_update')
            ->get();
        $EMR_FORM_ =  DB::connection('mongodb')
            ->table('#ResumeEMR')
            ->where('kdprofile', $this->kdProfile)
            ->where('noregistrasifk', $r['norec_pd'])
            ->where('table', 'VitalSign')
            ->where('statusenabled', true)
            ->limit(1)
            ->orderByDesc('last_update')
            ->get();

        $namaTables = [
            'PersetujuanTindakanKedokteran',
            'AssesmentPraAnestesiSedasi',
            'AssesmenPraOperasiPerempuan',
            'AssesmenPraOperasiLaki',
            'ChecklistKeselamatanPasienOperasi',
            'KomitePencegahanPengendalianInfeksi',
            'FormulirTransferPasienIntraRS',
            'ChecklistPraOperasi',
            'AsuhanKeperawatanPeriOperatif',
            'PenolakanTindakanKedokteran',
            'PersetujuanTindakanKedokteranAnestesi',
            'PersetujuanTindakanKedokteranPembedahanUmum',
            'PersetujuanTindakanKedokteranTransfusiDarah',
            'PersetujuanTindakanKedokteranMasukRuangIntensif',
            'ChecklistKesiapanAnastesi'
        ];
        $hasilCekTables = [];

        $norecPd = $r['norec_pd'];

        $dataMongo = DB::connection('mongodb')
            ->table('#ResumeEMR')
            ->select('namaemr', 'url_form', 'table')
            ->where('kdprofile', $this->kdProfile)
            ->where('noregistrasifk', $norecPd)
            ->whereIn('table', $namaTables)
            ->where('statusenabled', true)
            ->get()->toArray();

        $dataPostgres = DB::table('emr_t')
            ->select('caption', 'url_form', 'collection as table')
            ->where('kdprofile', $this->kdProfile)
            ->whereIn('collection', $namaTables)
            ->where('statusenabled', true)
            ->get()->toArray();    

        // Merge and de-duplicate by 'collection'
        $combinedRaw = array_merge($dataMongo, $dataPostgres);

        $merged = [];
        foreach ($combinedRaw as $item) {
            $item = (array) $item; // convert stdClass to array
            $key = $item['table'];
            $merged[$key] = array_merge($merged[$key] ?? [], $item);
        }

        $hasilCekTables = array_values($merged);
        $listIBS = $hasilCekTables;
        $emr = array_merge($EMR_FORM->toArray(), $EMR_FORM_->toArray());
        $tindakan = [];
        $resep = [];
        $laborat = [];
        $radiologi = [];
        $bedah = [];

        // $emr = [];
        $diagnosa = array_merge($diagnosaX->toArray(), $diagnosaIX->toArray());
        foreach ($tindakanResep as $items) {
            if ($items->strukresepfk == null) {
                $tindakan[] = $items;
            } else {
                $resep[] = $items;
            }
        }
        foreach ($orderResep as $items) {
            $resep[] = $items;
        }

        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        foreach ($laboratRad as $items) {
            $items->url_pacs_hasil = null;
            if ($items->order_complete == 1) {
                $exp = explode(',', $urlPACSHasil);
                $exp[0] = $exp[0] . $items->accession_num;
                $exp[1] = $exp[1] . $items->accession_num;
                $urlPACSHasil = implode(",", $exp);
                $items->url_pacs_hasil = $urlPACSHasil;
                $items->status = 'selesai';
                $items->color_status = 'success';
            }

            $items->hasil_lab = [];
            foreach ($hasil_VansLAB as $hLab) {
                if ($hLab->visit_trans_id == $items->noorder && $hLab->his_test_id == $items->objectprodukfk) {
                    $items->hasil_lab[] = $hLab;
                }
            }
            foreach ($hasil_LAB_MANUAL as $hLab) {
                if ($hLab->visit_trans_id == $items->noorder && $hLab->his_test_id == $items->objectprodukfk) {
                    $items->hasil_lab[] = $hLab;
                }
            }
            if ($items->objectdepartemenfk == $depLab) {
                $laborat[] = $items;
            }
            if ($items->objectdepartemenfk == $depRad) {
                $radiologi[] = $items;
            }
            if ($items->objectdepartemenfk == $depBedah) {
                $bedah[] = $items;
            }
        }


        foreach ($emr as $k => $items2) {
            $emr[$k]['icon'] = isset($items2['icon']) ?  $items2['icon'] : 'fas fa-laptop-medical';
        }

        if (count($tindakan) > 0) {
            $status = false;
        }
        if (count($resep) > 0) {
            $status = false;
        }
        if (count($diagnosa) > 0) {
            $status = false;
        }
        if (count($laborat) > 0) {
            $status = false;
        }
        if (count($radiologi) > 0) {
            $status = false;
        }
        if ($orderBedah > 0) {
            $status = false;
        }
        if (count($emr) > 0) {
            $status = false;
        }
        if (!empty($asesmenAwal)) {
            $status = false;
        }
        if (count($konsul) > 0) {
            $status = false;
        }
        $result['enabledEMRSimrsLama'] = $this->settingFix('enabledEMRSimrsLama');
        $result['vitalSign'] = $vitalSign != null ? true : false;
        $result['tindakan'] = $tindakan;
        $result['resep'] = $resep;
        $result['diagnosis'] = $diagnosa;
        $result['laboratorium'] = $laborat;
        $result['onlylaboratorium'] = $laboratorium;
        $result['radiologi'] = $radiologi;
        // $result['testmasukvalue'] = $testvalue;          // TEST CONDITION QUERY
        $result['bedah'] = $bedah;
        $result['orderBedah'] = $orderBedah != null ? true : false;
        $result['konsul'] = $konsul;
        $result['asesmenawal'] = $asesmenAwal;
        $result['emr'] = $emr;
        $result['empty'] = $status;
        $result['statuspasien'] = $statusPasien;
        $result['berkas'] = $berkas;
        $result['listIBS'] = $listIBS;
        $result['as'] = '@epic';
        return $this->respond($result);
    }
    public function listPasienRJ(Request $r)
    {
        $data  = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->select(
                'apd.norec as norec_apd',
                'pd.norec as norec_pd',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ps.nocm',
                'pd.tglregistrasi',
                'ps.namapasien',
                'apd.noantrian',
                'apd.status',
                'ru.namaruangan',
                'kp.kelompokpasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')));

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where('apd.tglmasuk', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where('apd.tglmasuk', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ruid']) && $r['ruid'] != '' && $r['ruid'] != 'undefined') {
            $data = $data->where('ru.id', '=',  $r['ruid']);
        }
        if (isset($r['dokid']) && $r['dokid'] != '' && $r['dokid'] != 'undefined') {
            $data = $data->where('apd.objectpegawaifk', '=',  $r['dokid']);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        if (isset($r['statuspanggil']) && $r['statuspanggil'] != '' && $r['statuspanggil'] != 'undefined') {
            $data = $data->where('apd.status', '=',  $r['statuspanggil']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $data = $data->where('pd.norec', '!=', $r['norec_pd']);
        }
        $data = $data->orderBy('apd.noantrian');
        $data = $data->get();
        $res['dipanggil'] = 0;
        $res['belumpanggil'] = 0;
        foreach ($data as $d) {
            if ($d->status == null || $d->status == 'Belum Dipanggil') {
                $res['belumpanggil'] = $res['belumpanggil'] + 1;
            } else {
                $res['dipanggil'] = $res['dipanggil'] + 1;
            }
        }
        $res['data'] = $data;
        $res['total'] = count($data);

        return $this->respond($res);
    }
    public function suratKeterangan(Request $r)
    {
        $data  = DB::table('emr_t as emr')
            ->where('emr.headfk', 210337)
            ->orderBy('emr.id')
            ->select('emr.*', 'emr.url_form as url')
            ->get();

        $res['data'] = $data;

        return $this->respond($res);
    }
    public function setOK(Request $r)
    {
        $data  = DB::table('emr_t as emr')
            ->where('emr.headfk', 310239)
            ->orderBy('emr.id')
            ->select('emr.*', 'emr.url_form as url')
            ->get();

        $res['data'] = $data;

        return $this->respond($res);
    }
    public function setRanap(Request $r)
    {
        $data  = DB::table('emr_t as emr')
            ->where('emr.headfk', 410239)
            ->orderBy('emr.id')
            ->select('emr.*', 'emr.url_form as url')
            ->get();

        $res['data'] = $data;

        return $this->respond($res);
    }
    public function setRujukan(Request $r)
    {
        $data  = DB::table('emr_t as emr')
            ->where('emr.headfk', 510239)
            ->orderBy('emr.id')
            ->select('emr.*', 'emr.url_form as url')
            ->get();

        $res['data'] = $data;

        return $this->respond($res);
    }
    public function formAdmisi(Request $r)
    {
        $data  = DB::table('emr_t as emr')
            ->where('emr.headfk', 210237)
            ->orderBy('emr.id')
            ->select('emr.*', 'emr.url_form as url')
            ->get();

        $res['data'] = $data;

        return $this->respond($res);
    }
    public function getTotalBilling(Request $r)
    {
        $totalBiaya =  PelayananPasien::totalTagihan($r['noregistrasi']);
        return $this->respond($totalBiaya);
    }
    public function infoPasien(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('agama_m as agm', 'ps.objectagamafk', '=', 'agm.id')
            ->leftJoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftJoin('pendidikan_m as pe', 'ps.objectpendidikanfk', '=', 'pe.id')
            ->leftJoin('suku_m as su', 'ps.objectsukufk', '=', 'su.id')
            ->leftjoin('golongandarah_m as gd', 'ps.objectgolongandarahfk', 'gd.id')
            ->leftjoin('statusperkawinan_m as spk', 'spk.id', 'ps.objectstatusperkawinanfk')
            // ->leftjoin('kebangsaan_m as kb', 'kb.id', '=', 'ps.objectkebangsaanfk')
            ->leftjoin('negara_m as ng', 'ng.id', '=', 'alm.objectnegarafk')
            ->leftjoin('desakelurahan_m as dsk', 'dsk.id', '=', 'alm.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kcm', 'kcm.id', '=', 'alm.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kkb', 'kkb.id', '=', 'alm.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as prp', 'prp.id', '=', 'alm.objectpropinsifk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.noidentitas',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'su.suku',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email',
                'agm.agama',
                'pe.pendidikan',
                'pk.pekerjaan',
                'ps.isfoto',
                'ps.filename',
                'gd.golongandarah',
                'spk.statusperkawinan',
                'ng.namanegara',
                'dsk.namadesakelurahan',
                'kcm.namakecamatan',
                'kkb.namakotakabupaten',
                'prp.namapropinsi',
                // 'kb.kebangsaan',
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        return $this->respond($data);
    }

    public function simpanAlergiPasien(Request $request)
    {

        try {
            DB::beginTransaction();

            Pasien::where('id', $request['id'])->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(['alergi' => $request['alergi']]);

            DB::commit();
            $response = [
                'data' => $request['alergi'],
                'status' => 200,
                'message' => 'Simpan Alergi Pasien Berhasil'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                'data' => $e->getMessage(),
                'status' => 400,
                'message' => 'Simpan Alergi Pasien Gagal'
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function updateSuratKontrol(Request $request)
    {

        try {
            DB::beginTransaction();

            $data = AntrianPasienRegistrasi::where('noreservasi', $request['noreservasi'])->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(['nosuratkontrol' => $request['nosuratkontrol']]);

            DB::commit();
            $response = [
                'data' => $data,
                'status' => 200,
                'message' => 'Simpan Surat Kontrol Berhasil'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                'data' => $e->getMessage(),
                'status' => 400,
                'message' => 'Simpan Surat Kontrol Gagal'
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function changeClosing(Request $request)
    {

        PasienDaftar::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
            ->where('norec', $request['norec_pd'])
            ->update(
                ['isclosing' => $request['closing']]
            );

        $status = $request['closing'] == true ? 'Pasien Berhasil diclosing' : 'Batal Closing Berhasil';
        return $this->respond('Berhasil', 200, $status);
    }
    public function detailPelayananSIMRSLama(Request $r)
    {
        $set =  explode(',', $this->settingFix('urlEMRSimrsLama'));
        $prefix = $set[1];
        if ($r['local'] == 'true') {
            $prefix = $set[0];
        }
        $url1 = $prefix . 'apiriwayat/riwayat/' . $r['prefix'];
        // $url2 = $prefix .'rme/riwayat/diagnosa';
        // $url3 = $prefix .'rme/riwayat/catatan_dokter';
        // $url4 = $prefix .'rme/riwayat/rujukan_penunjang';
        // $url5 = $prefix .'rme/riwayat/rujukan_nonpenunjang';
        // $url6 = $prefix .'rme/riwayat/order_resep';
        $json = array(
            "nocm" => $r['nocm']
        );

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "username" => "userapi",
            "password" => "api2023"
        ])
            ->withoutVerifying()
            ->withOptions(["verify" => false])
            ->post($url1, $json);

        $res = $response->json();

        $resp = isset($res['response']['message']) && $res['response']['message'] == 'true' ? $res['metadata']['data'] : [];
        return $this->respond($resp);
    }

    public function saveCatatanDokter(Request $request)
    {
        try {
            $pegawai = LoginUser::where('id', $request->userData['id'])->first();
            DB::beginTransaction();
            $data = [
                'norec' => $this->Uuid4(),
                'kdprofile' => $this->kdProfile,
                'statusenabled' => true,
                'catatan' => $request->catatan,
                'tanggal' => date($request->tanggal),
                'nocmfk' => $request->nocmfk,
                'noregistrasifk' => $request->noregistrasifk,
                'pegawaifk' => $pegawai->objectpegawaifk
            ];
            DB::table('catatandokter_t')->insert($data);
            DB::commit();
            $response = [
                'data' => $data,
                'status' => 200,
                'message' => 'Simpan catatan dokter  Berhasil'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                'data' => $e->getMessage(),
                'status' => 400,
                'message' => 'Simpan Catatan Dokter Gagal'
            ];
        }
        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function saveHistoriKemo(Request $request)
    {
        try {
            $pegawai = LoginUser::where('id', $request->userData['id'])->first();
            DB::beginTransaction();
            $data = [
                'norec' => $this->Uuid4(),
                'kdprofile' => $this->kdProfile,
                'statusenabled' => true,
                'diagnosa' => $request->diagnosa,
                'staging' => $request->staging,
                'tanggalOperasi' => date($request->tanggalOperasi),
                'kemo' => $request->kemo,
                'tanggalKemoterapi' => date($request->tanggalKemoterapi),
                'radiasi' => $request->radiasi,
                'surveilans' => $request->surveilans,
                'perkembangan' => $request->perkembangan,
                'tanggal' => date($request->tanggal),
                'nocmfk' => $request->nocmfk,
                'noregistrasifk' => $request->noregistrasifk,
                'pegawaifk' => $pegawai->objectpegawaifk
            ];
            DB::table('historikemoterapi_t')->insert($data);
            DB::commit();
            $response = [
                'data' => $data,
                'status' => 200,
                'message' => 'Simpan Riwayat kemoterapi  Berhasil'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                'data' => $e->getMessage(),
                'status' => 400,
                'message' => 'Gagal Simpan Riwayat kemoterapi'
            ];
        }
        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function getHistoriKemo(Request $request)
    {
        $data = DB::table('historikemoterapi_t as ct')->where('ct.nocmfk', $request->nocmfk)
            ->join('pegawai_m as p', 'p.id', 'ct.pegawaifk')
            ->select(
                'ct.diagnosa',
                'ct.staging',
                'ct.tanggalOperasi',
                'ct.kemo',
                'ct.tanggalKemoterapi',
                'ct.radiasi',
                'ct.surveilans',
                'ct.perkembangan',
                'ct.norec',
                'ct.tanggal',
                'p.namalengkap as dokter'
            )->orderBy('ct.tanggal', 'DESC')
            ->limit(100)
            ->get();
        return $this->respond($data);
    }

    public function getCatatanDokter(Request $request)
    {
        $data = DB::table('catatandokter_t as ct')->where('ct.nocmfk', $request->nocmfk)
            ->join('pegawai_m as p', 'p.id', 'ct.pegawaifk')
            ->select(
                'ct.catatan',
                'ct.norec',
                'ct.tanggal',
                'p.namalengkap as dokter'
            )->orderBy('ct.tanggal', 'DESC')
            ->limit(100)
            ->get();
        return $this->respond($data);
    }
    public function hasilLab($norec)
    {

        $res['hasil_VansLAB'] = collect(DB::select("select
            res.no_order AS visit_trans_id, res.kode_pemeriksaan AS tarif_id,
            res.nama_pemeriksaan AS examination_name, res.hasil AS result_value, res.unit,
            res.normal AS normal_value, res.metode, res.nama_alat AS treatment_name,
            res.no_urut AS urut, res.no_rm AS rm_number,  res.tgl_hasil AS visit_date, res.kode_sir as his_test_id,
            res.nama_pemeriksaan AS tarif_name, res.flag, res.user_validasi as analis
            from lab_hasil as res
            join pasiendaftar_t as pd on pd.noregistrasi=res.no_registrasi
            where pd.norec = '$norec'
            "))->toArray();


        $res['hasil_LAB_MANUAL']  = collect(DB::select(
            "select case when so.noorder is null then  pd.noregistrasi  else so.noorder end as visit_trans_id,
                res.produkfk AS tarif_id,
                pr.namaproduk AS examination_name, res.hasil AS result_value,  res.satuan AS unit,
                res.nilainormal AS normal_value,  res.metode AS metode,   res.group AS treatment_name,
                null AS urut,  ps.nocm AS rm_number,  res.tglhasil AS visit_date,    res.produkfk as his_test_id,
                pr.namaproduk AS tarif_name,  res.flag,pg.namalengkap as analis
                from hasillaboratorium_t as res
                join produk_m as pr on pr.id=  res.produkfk
                join antrianpasiendiperiksa_t as apd on  apd.norec= res.noregistrasifk
                join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                left join strukorder_t as so on so.noregistrasifk=pd.norec
                join pasien_m as ps on ps.id=pd.nocmfk
                left join pegawai_m as pg on cast(pg.id as text)=res.pegawaifk
                where pd.norec= '$norec'"
        ))->toArray();
    }
    public function getHasilLab(Request $r)
    {
        $hasil_VansLAB = $this->hasilLab($r['norec_pd'])['hasil_VansLAB'];

        $hasil_LAB_MANUAL = $this->hasilLab($r['norec_pd'])['hasil_LAB_MANUAL'];

        $data = '';
        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        $depLab = $this->settingFix('idDepartemenLab');
        $depRad = $this->settingFix('idDepartemenRadiologi');
        $depBedah = $this->settingFix('idDepartemenBedah');
        $expertise = DB::table('hasilradiologi_t as hh')
            ->join('pelayananpasien_t as pp', function ($j) {
                $j->on('pp.norec', '=', 'hh.pelayananpasienfk')->where('pp.statusenabled', true);
            })
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hh.noregistrasifk')
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->where('hh.statusenabled', true)
            ->select('pp.strukorderfk', 'hh.keterangan as expertise', 'pp.norec as norec_pp');

        $laboratRad = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'op.noorderfk', '=', 'so.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftJoinSub($expertise, 'hh', 'so.norec', '=', 'hh.strukorderfk')
            ->leftjoin('ris_order as ris', function ($j) {
                $j->on('ris.order_no', '=', 'so.noorder')->on(DB::raw("cast(op.objectprodukfk as text)"), '=', 'ris.order_code');
            })
            // ->leftjoin('hasilradiologi_t as hh', 'hh.pelayananpasienfk', '=', 'so.objectruangantujuanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'op.norec as norec_op',
                'pr.namaproduk',
                'pg.namalengkap',
                'ru.objectdepartemenfk',
                'so.tglpelayananawal as tgloperasi',
                'so.estimasiwaktuoperasi',
                'so.keteranganlainnya',
                DB::raw(" case when so.statusorder = 1 then 'verifikasi'
            when so.statusorder = 2 then 'selesai'
            else 'pending' end as status,
            case when so.statusorder = 1 then 'danger'
            when so.statusorder = 2 then 'green'
            else 'orange' end as color_status,hh.expertise,hh.norec_pp,
            so.objectruangantujuanfk,
            ris.patient_id || '-' || ris.order_cnt as radiologiid,
            ris.order_complete,ris.accession_num,
            op.objectprodukfk")

            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.noregistrasifk', $r['norec_pd']);
        if (isset($r['islab']) && $r['islab'] == 'true') {
            $laboratRad = $laboratRad->whereIn('ru.objectdepartemenfk', [$depLab]);
        }
        if (isset($r['israd']) && $r['israd'] == 'true') {
            $laboratRad = $laboratRad->whereIn('ru.objectdepartemenfk', [$depRad]);
        }

        $laboratRad = $laboratRad->where('so.statusenabled', true);
        $laboratRad = $laboratRad->orderByDesc('so.tglorder');
        $laboratRad = $laboratRad->get();
        $laborat = [];
        $radiologi = [];
        foreach ($laboratRad as $items) {
            $items->url_pacs_hasil = null;
            if ($items->order_complete == 1) {
                $exp = explode(',', $urlPACSHasil);
                $exp[0] = $exp[0] . $items->accession_num;
                $exp[1] = $exp[1] . $items->accession_num;
                $urlPACSHasil = implode(",", $exp);
                $items->url_pacs_hasil = $urlPACSHasil;
                $items->status = 'selesai';
                $items->color_status = 'success';
            }

            $items->hasil_lab = [];
            foreach ($hasil_VansLAB as $hLab) {
                if ($hLab->visit_trans_id == $items->noorder && $hLab->his_test_id == $items->objectprodukfk) {
                    $items->hasil_lab[] = $hLab;
                }
            }
            foreach ($hasil_LAB_MANUAL as $hLab) {
                if ($hLab->visit_trans_id == $items->noorder && $hLab->his_test_id == $items->objectprodukfk) {
                    $items->hasil_lab[] = $hLab;
                }
            }
            if ($items->objectdepartemenfk == $depLab) {
                $laborat[] = $items;
            }
            if ($items->objectdepartemenfk == $depRad) {
                $radiologi[] = $items;
            }
        }
        $result['laboratorium'] = $laborat;
        $result['radiologi'] = $radiologi;
        return $this->respond($result);
    }
    public function hasilLabPA(Request $r)
    {
        $data = DB::table('hasilpemeriksaanlab_t as ar')
            ->join('pelayananpasien_t as pp', 'pp.norec', '=', 'ar.pelayananpasienfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ar.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->select(
                'pr.namaproduk',
                'ar.tanggal as tanggalpemeriksaan',
                'ar.diagnosaklinik as diagnosa',
                'ar.diagnosaklinik',
                'ar.morfologi',
                'ar.diagnosapb',
                'ar.jenis',
                'ar.makroskopik',
                'ar.mikroskopik',
                'ar.kesimpulan',
                'ar.anjuran',

            )
            ->where('pd.norec', $r['norec_pd'])
            ->orderBy('ar.tanggal', 'DESC')
            ->get();

        return $this->respond($data);
    }

    public function bukaEMR(Request $request)
    {

        DB::beginTransaction();
        try {

            AksesEMR::where('pasienfk', $request['nocmfk'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                ->update([
                    'deskripsi' => $request['keterangan'],
                    'tglmulai' => $request['tglAwal'],
                    'tglberakhir' => $request['tglAkhir'],
                    'pegawaipemohonfk' => $request['petugaspemohon'],
                    'pegawaipenerimafk' => $this->getPegawaiId(),
                    'objectkelompokuserfk' => implode(',', $request['kelompokuser']),
                ]);

            DB::commit();

            $result = [
                'status' => 201,
                'message' => 'Akses EMR Berhasil dibuka',
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $result = [
                'status' => 400,
                'respon' => $e->getMessage(),
                'message' => 'Gagal Buka EMR',
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function showAksesEMR(Request $request)
    {

        $data = DB::table('aksesemr_t as ar')
            ->leftjoin('pegawai_m as pg', 'ar.pegawaipemohonfk', 'pg.id')
            ->select('ar.*', 'pg.namalengkap')
            ->where('ar.statusenabled', true)
            ->where('ar.kdprofile', $this->kdProfile)
            ->where('ar.pasienfk', $request['pasienfk'])
            ->first();

        return $this->respond($data);
    }

    public function kunciEMR(Request $request)
    {

        DB::beginTransaction();
        try {

            Pasien::where('id', $request['nocmfk'])->update(['statusemr' => 1]);

            $this->LOGGING('Kunci EMR', $request['nocmfk'], 'pasien_t', 'EMR Pasien ' . $request['pasien'] . 'dikunci oleh' . $this->getNamaPegawai());

            DB::commit();

            $result = [
                'status' => 200,
                'message' => 'EMR Berhasil dikunci',
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $result = [
                'status' => 400,
                'respon' => $e->getMessage(),
                'message' => 'Gagal Kunci EMR',
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getRiyawatKontrol(Request $r)
    {
        $query = DB::table('antrianpasienregistrasi_t as apr')
            ->select(
                'apr.nocmfk',
                'rk.norec',
                'apr.objectpegawaifk',
                'apr.objectruanganfk',
                'rk.kdprofile',
                'rk.nosurat',
                'rk.typekontrol',
                'rk.tglkontrol',
                'rk.tglentry',
                'rk.nosepasal',
                'rk.isterbitsep',
                'rk.nokartu',
                'rk.jenispelayanan',
                'rk.terapi',
                'rk.catatan',
                'rk.jam',
                'rk.diagnosaakhir',
                'rk.indikasikontrol',
                'rk.updated_at',
                'rk.nobukti',
                'dokter.namalengkap as namadokter',
                'pasien.nocm',
                'ruang.reportdisplay as poli',
                DB::raw("coalesce(apr.namapasien, pasien.namapasien) as namapasien"),
                'apr.noantrianpoli',
                'apr.noantrian',
                'apr.isconfirm',
                'apr.norec as norec_apr',
                'pasien.tgllahir',
                'jk.jeniskelamin',
                'ruang.namaruangan',
                'dokter.namalengkap',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.type',
                'apr.tipepasien',
                'apr.jenis',
                'apr.tglinput',
                'apr.nosuratkontrol',
                'apr.norec as antrianpasienregistrasifk',
                'kp.kelompokpasien',
                'kp.id as idkelompokpasien',
                'kbs.name as kebangsaan',
                'pd.norec as norec_pd',
                DB::raw("coalesce(apr.jam, rk.jam) as jamreservasi"),
                DB::raw("coalesce(pasien.nobpjs, apr.nobpjs) as nobpjs"),
                DB::raw("TO_CHAR(age(pasien.tgllahir), 'YY Thn MM Bln DD Hr') as umur"),
                'apr.noidentitas',
                'apr.tgllahir',
                'apr.ismobilejkn',
                'apr.objectjeniskelaminfk',
                DB::raw("coalesce(apr.notelepon, pasien.nohp) as notelepon"),
                'apr.objectagamafk',
                'apr.objectkebangsaanfk',
                'apr.alamatlengkap',
                DB::raw("coalesce(apr.nosuratkontrol, rk.nosurat) as nosuratkontrol"),
                'pd.noregistrasi',
            )
            ->leftJoin('riwayatkontrol_t as rk', 'apr.norec', 'rk.antrianpasienregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.antrianpasienregistrasifk', 'apr.norec')
            ->leftJoin('pasien_m as pasien', 'pasien.id', 'apr.nocmfk')
            ->leftJoin('pegawai_m as dokter', 'dokter.id', 'apr.objectpegawaifk')
            ->join('ruangan_m as ruang', 'ruang.id', 'apr.objectruanganfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'pasien.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', 'pasien.objectkebangsaanfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'apr.objectkelompokpasienfk')
            ->where('apr.kdprofile', $this->kdProfile)
            ->where('apr.statusenabled', true)
            ->where('apr.noreservasi', '!=', '-')
            ->groupBy(
                'rk.norec',
                'apr.nocmfk',
                'apr.objectpegawaifk',
                'apr.objectruanganfk',
                'rk.kdprofile',
                'rk.nosurat',
                'pasien.nobpjs',
                'apr.nobpjs',
                'rk.typekontrol',
                'rk.tglkontrol',
                'rk.tglentry',
                'rk.nosepasal',
                'rk.isterbitsep',
                'rk.nokartu',
                'rk.jenispelayanan',
                'rk.terapi',
                'rk.catatan',
                'rk.diagnosaakhir',
                'rk.indikasikontrol',
                'rk.updated_at',
                'rk.nobukti',
                'rk.created_at',
                'rk.jam',
                'kp.kelompokpasien',
                'kp.id',
                'dokter.namalengkap',
                'ruang.reportdisplay',
                'pasien.namapasien',
                'pasien.nohp',
                'apr.jam',
                'apr.ismobilejkn',
                'pasien.tgllahir',
                'jk.jeniskelamin',
                'ruang.namaruangan',
                'dokter.namalengkap',
                'apr.noantrianpoli',
                'apr.norec',
                'apr.nosuratkontrol',
                'kbs.name',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.type',
                'apr.tipepasien',
                'apr.jenis',
                'pd.norec',
                'apr.namapasien',
                'pasien.nocm',
                'apr.tglinput',
                'apr.nosuratkontrol',
                'pd.noregistrasi',
                DB::raw("TO_CHAR(age(pasien.tgllahir), 'YY thn MM Bulan DD Hari')")
            );

        if (isset($r['orderby']) && $r['orderby'] == 'tglkontrol') {
            $query->orderBy('rk.tglkontrol', 'desc');
        } else {
            $query->orderBy('rk.tglentry', 'desc');
        }

        if (isset($r['dokter']) && $r['dokter'] != '') {
            $query->where('dokter.id', $r['dokter']);
        }

        if (isset($r['nocmfk']) && $r['nocmfk']) {
            $query->where('apr.nocmfk', '=', $r['nocmfk']);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk']) {
            $query->where('apr.objectruanganfk', '=', $r['ruanganfk']);
        }
        if (isset($r['norm']) && $r['norm']) {
            $query->where('pasien.nocm', 'ilike', '%' . $r['norm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien']) {
            $namapasien = $r['namapasien'];
            $query->whereRaw("(apr.namapasien ilike '%$namapasien%' or pasien.namapasien ilike '%$namapasien%')");
        }
        if (isset($r['dari']) && $r['dari'] && $r['kategori'] == 'tglreservasi' && $r['isAllPeriode'] != "true") {
            $query->whereDate('apr.tanggalreservasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] && $r['kategori'] == 'tglreservasi' && $r['isAllPeriode'] != "true") {
            $query->whereDate('apr.tanggalreservasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['dari']) && $r['dari'] && $r['kategori'] == 'tglentri' && $r['isAllPeriode'] != "true") {
            $query->whereDate('rk.tglentry', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] && $r['kategori'] == 'tglentri' && $r['isAllPeriode'] != "true") {
            $query->whereDate('rk.tglentry', '<=', $r['sampai'] . ' 23:59');
        }

        $nobpjspasien = DB::table('pasiendaftar_t as pd')
            ->select('pa.nobpjs')
            ->leftJoin('pasien_m as pa', 'pa.id', 'pd.nocmfk')
            ->where('pd.norec', $r->get('norec_pd'))
            ->first();


        $data['nobpjs'] = $nobpjspasien->nobpjs ?? '';
        $data['riwayat'] = $query->get();

        return $this->respond($data);
    }

    public function getRiyawatKontrolTerakhir(Request $r)
    {
        $query = DB::table('riwayatkontrol_t as rk')
            ->select(
                'rk.*',
                'dokter.namalengkap as namadokter',
                'ruang.reportdisplay as poli',
                'pasien.namapasien',
                'apr.noantrianpoli',
                'apr.norec as norec_apr',
                'apr.objectkelompokpasienfk',
                'pasien.tgllahir',
                'jk.jeniskelamin',
                'ruang.namaruangan',
                'dokter.namalengkap'
            )
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', 'rk.nocmfk')
            ->join('pegawai_m as dokter', 'dokter.id', 'rk.objectpegawaifk')
            ->join('ruangan_m as ruang', 'ruang.id', 'rk.objectruanganfk')
            ->join('pasien_m as pasien', 'pasien.id', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', 'pasien.objectjeniskelaminfk')
            ->leftJoin('antrianpasienregistrasi_t as apr', 'apr.norec', 'rk.antrianpasienregistrasifk')
            ->where('rk.nocmfk', $r->get('nocmfk'))
            ->where('rk.kdprofile', $this->kdProfile)
            ->groupBy(
                'rk.norec',
                'rk.nocmfk',
                'rk.objectpegawaifk',
                'rk.objectruanganfk',
                'rk.kdprofile',
                'rk.nosurat',
                'rk.typekontrol',
                'rk.tglkontrol',
                'rk.tglentry',
                'rk.nosepasal',
                'rk.isterbitsep',
                'rk.nokartu',
                'rk.jenispelayanan',
                'rk.terapi',
                'rk.catatan',
                'rk.diagnosaakhir',
                'rk.indikasikontrol',
                'rk.updated_at',
                'rk.nobukti',
                'rk.created_at',
                'dokter.namalengkap',
                'ruang.reportdisplay',
                'pasien.namapasien',
                'pasien.tgllahir',
                'jk.jeniskelamin',
                'ruang.namaruangan',
                'dokter.namalengkap',
                'apr.noantrianpoli',
                'apr.objectkelompokpasienfk',
                'apr.norec'
            );

        $query->orderBy('apr.tanggalreservasi', 'desc')->limit(1);
        $data['riwayat'] = $query->get();

        return $this->respond($data);
    }

    public function saveRiwayatKontrol(Request $r)
    {
        // return $this->respond($r->poliKontrol['id'], 200, "test");
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $this->kdProfile;
        $namapasien = '';
        $nocm = '';
        $validator = Validator::make($r->all(), [
            // "nobukti" => "required",
            // "diagnosaakhir" => "required",
            // "indikasikontrol" => "required",
            // "terapi" => "required",
        ]);

        if ($validator->fails()) {
            $response = [
                "data" => $validator->errors(),
                "status" => 400,
                "message" => "Terdapat data yang belum terisi"
            ];
        } else {
            DB::beginTransaction();
            try {

                if ($r["isReservasiBaru"] == false) {
                    $idPasien = Pasien::where(
                        'id',
                        $r["nocmfk"]
                    )->where('kdprofile', $kdProfile)->first();
                }
                // $umur =  $this->getAge($idPasien->tgllahir, date('Y-m-d H:i:s'));

                if ($r["isReservasiBaru"] == false) {
                    if (isset($idPasien) && isset($idPasien->tgllahir)) {
                        $datetime = new \DateTime(date($idPasien->tgllahir));
                    } else {
                        $datetime = new \DateTime(date('Y-m-d', strtotime($r["tgllahir"])));
                    }
                } else {
                    $datetime = new \DateTime(date('Y-m-d', strtotime($r["tgllahir"])));
                }

                $umur = (int) $datetime->diff(new \DateTime(date('Y-m-d')))->format('%y');

                // var_dump($umur);
                if ($r["isReservasiBaru"] == false) {
                    if ($r['norec_apr'] == '' || $r['norec_apr'] == null || $r['norec_apr'] == 'undefined') {
                        if ($r['kelompokpasien'] != "2") {
                            $checkDaftar = DB::table('antrianpasienregistrasi_t as apr')
                                ->whereDate('apr.tanggalreservasi', '=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])))
                                ->where('apr.objectruanganfk', $r['poliKontrol']['id'])
                                ->where('apr.nocmfk', $r['nocmfk'])
                                ->where('apr.statusenabled', true)
                                ->first();

                            if (isset($checkDaftar)) {
                                DB::rollback();
                                $response = [
                                    "data" => [],
                                    "status" => 400,
                                    "message" => "Tidak dapat mendaftar di Poli yang sama pada hari yang sama"
                                ];

                                return $this->respond($response, $response['status'], $response['message']);
                            }
                        } else {
                            $checkDaftar = DB::table('antrianpasienregistrasi_t as apr')
                                ->whereDate('apr.tanggalreservasi', '=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])))
                                ->where('apr.nocmfk', $r['nocmfk'])
                                ->where('apr.statusenabled', true)
                                ->first();

                            if (isset($checkDaftar)) {
                                DB::rollback();
                                $response = [
                                    "data" => [],
                                    "status" => 400,
                                    "message" => "Pasien BPJS tidak dapat mendaftar di Hari yang sama"
                                ];

                                return $this->respond($response, $response['status'], $response['message']);
                            }
                        }
                        // $checkDaftar = DB::table('antrianpasienregistrasi_t as apr')
                        // ->whereDate('apr.tanggalreservasi', '=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])))
                        // ->where('apr.objectruanganfk', $r['poliKontrol']['id'])
                        // ->where('apr.nocmfk', $r['nocmfk'])
                        // ->where('apr.statusenabled', true)
                        // ->first();

                        // if(isset($checkDaftar)) {
                        //     DB::rollback();
                        //     $response = [
                        //         "data" => [],
                        //         "status" => 400,
                        //         "message" => "Tidak dapat mendaftar di Poli yang sama pada hari yang sama"
                        //     ];

                        //     return $this->respond($response, $response['status'], $response['message']);
                        // }
                    }
                }

                $maxantrian = AntrianPasienRegistrasi::whereDate('tanggalreservasi', '=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])))
                    ->where('objectruanganfk', '=', $r['poliKontrol']['id'])
                    ->max('noantrianpoli');

                $jenis = '';
                if ($umur <= 17 || $umur > 60) {
                    $jenis = 'RA';
                } else {
                    $jenis = 'RL';
                }

                if ($r['norec_apr'] != '' && $r['norec_apr'] != null && $r['norec_apr'] != 'undefined') {
                    $APR = AntrianPasienRegistrasi::where('norec', '=', $r['norec_apr'])->first();
                    $namaLog = 'Edit Reservasi ke Poli ' . Ruangan::mine()->where('id', $r['poliKontrol']['id'])->first()->namaruangan . ' Untuk Tanggal ' . date('Y-m-d', strtotime($r["tglRencanaKontrol"]));
                    if ($APR->objectruanganfk != $r['poliKontrol']['id'] || date('Y-m-d', strtotime($APR->tanggalreservasi)) != date('Y-m-d', strtotime($r["tglRencanaKontrol"]))) {
                        $APR->noantrianpoli = $maxantrian + 1;
                    }
                } else {
                    $APR = new AntrianPasienRegistrasi();
                    $APR->norec = $APR->generateNewId();
                    $APR->kdprofile = $kdProfile;
                    $APR->statusenabled = true;
                    $APR->noreservasi = substr(Uuid::uuid4()->toString(), 0, 7);
                    $APR->noantrianpoli = $maxantrian + 1;
                    $namaLog = 'Tambah Reservasi ke Poli ' . Ruangan::mine()->where('id', $r['poliKontrol']['id'])->first()->namaruangan . ' Untuk Tanggal ' . date('Y-m-d', strtotime($r["tglRencanaKontrol"]));
                }

                if ($r["isReservasiBaru"] == false) {
                    $APR->nocmfk = $idPasien->id;
                    $namapasien = $idPasien->namapasien;
                    $nocm = $idPasien->nocm;
                    if ($idPasien->objectpendidikanfk != null) {
                        $APR->objectpendidikanfk = $idPasien->objectpendidikanfk;
                    } else {
                        $APR->objectpendidikanfk = 0;
                    }
                    $APR->objectjeniskelaminfk = $idPasien->objectjeniskelaminfk;
                    $APR->objectkebangsaanfk = $idPasien->objectkebangsaanfk;
                    $APR->tipepasien = 'LAMA';
                    $APR->type = 'LAMA';
                    $APR->jenis = $jenis;
                }

                $APR->objectpegawaifk = $r["kodeDokter"] != null ? $r['kodeDokter']['id'] : null;
                $APR->objectruanganfk = $r['poliKontrol']['id'];
                $APR->objectkelompokpasienfk = $r['kelompokpasien'];
                $APR->tanggalreservasi = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) . ' ' . date('H:i:s');
                $APR->tglinput = date('Y-m-d H:i:s');
                $APR->iskiosk = true;
                $APR->loketkiosk = 3;
                $APR->isreservasi = true;
                $APR->umursaatinput = $umur;
                $APR->jam = $r["jam"] ?? null;
                $APR->keterangan = $r["catatan"] ?? null;
                $APR->norujukan = $r["nokunjungan"] ?? null;
                $APR->perjanjianfk = null;
                $APR->objectkelompokpasienfk = $r['kelompokpasien'];
                if ($r["isReservasiBaru"] == true) {
                    $APR->objectagamafk = $r['agama'];
                    $APR->alamatlengkap = $r['alamat'];
                    $APR->objectjeniskelaminfk = $r['jenisKelamin'];
                    $APR->namapasien = $r['namapasien'];
                    $namapasien = $r['namapasien'];
                    $APR->nobpjs = $r['nobpjs'];
                    $APR->noidentitas = $r['nik'];
                    $APR->notelepon = $r['nohp'];
                    $APR->tempatlahir = $r['tempatlahir'];
                    $APR->tgllahir = date('Y-m-d', strtotime($r['tgllahir']));
                    $APR->tipepasien = 'LAMA';
                    $APR->type = 'BARU';
                    $APR->jenis = 'RB';
                    $APR->objectkebangsaanfk = $r['kebangsaan'];
                }
                $APR->save();

                if ($r["isReservasiBaru"] == false) {
                    if ($r['norec_rk'] != '' && $r['norec_rk'] != null && $r['norec_rk'] != 'undefined') {
                        $app = RiwayatKontrol::where('norec', '=', $r['norec_rk'])->first();
                    } else {
                        $app = new RiwayatKontrol;
                        $app->norec = $app->generateNewId();
                    }

                    $app->nocmfk = $r["nocmfk"];
                    $app->objectpegawaifk = $r["kodeDokter"] != null ? $r['kodeDokter']['id'] : null;
                    $app->objectruanganfk = $r['poliKontrol']['id'];
                    $app->kdprofile = $this->kdProfile;
                    $app->nosurat = $r["noSuratKontrol"] ?? null;
                    $app->tglkontrol = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) . ' ' . date('H:i:s');;
                    $app->tglentry = date("Y-m-d");
                    $app->isterbitsep = isset($r["isterbit"]) ? true : false;
                    $app->nokartu = $r["noKartu"] ?? null;
                    $app->jenispelayanan = $r["pelayanan"];
                    $app->nosepasal = $r['noSEP'];
                    $app->terapi = $r["terapi"] ?? null;
                    $app->jam = $r["jam"] ?? null;
                    $app->catatan = $r["catatan"] ?? null;
                    $app->diagnosaakhir = $r["diagnosaakhir"] ?? null;
                    $app->indikasikontrol = $r["indikasikontrol"] ?? null;
                    $app->nobukti = $this->SEQUENCE(new RiwayatKontrol(), 'nosuratkontrol', 16, date('ymd') . 'CNT-', $idProfile);
                    $app->antrianpasienregistrasifk = $APR->norec;
                    $app->save();
                }

                // $noregistrasi = $this->SEQUENCE(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $kdProfile);


                // $model_PD = new PasienDaftar();
                // $model_PD->norec = $model_PD->generateNewId();
                // $model_PD->kdprofile = $kdProfile;
                // $model_PD->statusenabled = true;
                // $model_PD->objectruanganasalfk = $r['poliKontrol']['id'];
                // $model_PD->statuspasien = 'LAMA';
                // $model_PD->tglpulang = null;
                // $model_PD->objectruanganlastfk = $r['poliKontrol']['id'];
                // $model_PD->objectpegawaifk = $r['kodeDokter']['id'];
                // $model_PD->objectpegawairawatbersamafk = null;
                // $model_PD->jenispelayanan = 1;
                // $model_PD->objectkelasfk = 6;
                // $model_PD->objectkelompokpasienlastfk = $r['kelompokpasien'];
                // $model_PD->nocmfk = $idPasien->id;
                // $model_PD->objectrekananfk = $r['rekanan'];
                // $model_PD->ismobilejkn = true;
                // $model_PD->antrianpasienregistrasifk = $APR->norec;
                // $model_PD->noreservasi = null;
                // $model_PD->tglregistrasi = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) .' '. date('H:i:s');
                // $model_PD->tglpulang = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) .' '. date('H:i:s');
                // $model_PD->asalrujukanfk = null;
                // $model_PD->keteranganasalrujukan = null;
                // $model_PD->noregistrasi = $noregistrasi;
                // $model_PD->petugas = $this->getNamaPegawai();
                // $model_PD->iskiosk = null;
                // $model_PD->save();

                // $max = AntrianPasienDiperiksa::where('objectruanganfk', $r['poliKontrol']['id'])
                //     ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])) . ' 00:00')
                //     ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])) . ' 23:59')
                //     ->where('statusenabled', true)
                //     ->max('noantrian');
                // $noAntrian = $max + 1;

                // $model_APD = new AntrianPasienDiperiksa;
                // $model_APD->norec = $model_APD->generateNewId();
                // $model_APD->kdprofile = (int) $kdProfile;
                // $model_APD->statusenabled = true;
                // $model_APD->noantrian = $noAntrian;
                // $model_APD->objectasalrujukanfk = null;
                // $model_APD->objectkamarfk = null;
                // $model_APD->objectruanganfk = $r['poliKontrol']['id'];
                // $model_APD->tglkeluar = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) .' '. date('H:i:s');
                // $model_APD->objectkelasfk = 6;
                // $model_APD->nobed = null;
                // $model_APD->noregistrasifk = $model_PD->norec;
                // $model_APD->objectpegawaifk = $r['kodeDokter']['id'];
                // $model_APD->statusantrian = 0;
                // $model_APD->status = "Belum Dipanggil";
                // $model_APD->statuskunjungan = 'LAMA';
                // $model_APD->tglregistrasi = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) .' '. date('H:i:s');
                // $model_APD->tglmasuk = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) .' '. date('H:i:s');
                // $model_APD->israwatgabung = false;
                // $model_APD->nojkn = null;
                // $model_APD->noregistrasi = $noregistrasi;
                // $model_APD->save();

                $data = DB::table('antrianpasienregistrasi_t as apr')
                    ->select(DB::raw("apr.tanggalreservasi,
                    rk.jam,
                    coalesce(pasien.namapasien, apr.namapasien) as namapasien,
                    kbs.name as kebangsaan,
                    pasien.nocm,
                    pasien.namapasien,
                    TO_CHAR(age(pasien.tgllahir), 'YY Thn MM Bln DD Hr') as umur,
                    apr.noreservasi,
                    apr.noantrianpoli,
                    ruang.namaruangan as poli,
                    kp.kelompokpasien,
                    pasien.nobpjs,
                    pasien.nohp,
                    pasien.noidentitas,
                    apr.jenis
                "))
                    ->leftJoin('riwayatkontrol_t as rk', 'apr.norec', 'rk.antrianpasienregistrasifk')
                    ->leftJoin('pasiendaftar_t as pd', 'pd.antrianpasienregistrasifk', 'apr.norec')
                    ->leftJoin('pasien_m as pasien', 'pasien.id', 'apr.nocmfk')
                    ->join('ruangan_m as ruang', 'ruang.id', 'apr.objectruanganfk')
                    ->leftJoin('kebangsaan_m as kbs', 'kbs.id', 'pasien.objectkebangsaanfk')
                    ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'apr.objectkelompokpasienfk')
                    ->where('apr.kdprofile', $this->kdProfile)
                    ->where('apr.statusenabled', true)
                    ->where('apr.norec', $APR->norec)
                    ->groupBy(DB::raw("apr.tanggalreservasi,
                    rk.jam,
                    pasien.namapasien, apr.namapasien,
                    kbs.name,
                    pasien.nocm,
                    pasien.namapasien,
                    pasien.tgllahir,
                    apr.noreservasi,
                    apr.noantrianpoli,
                    ruang.namaruangan,
                    kp.kelompokpasien,
                    pasien.nobpjs,
                    pasien.nohp,
                    pasien.noidentitas,
                    apr.jenis
                "))
                    ->get();

                $pasien = Pasien::where('id', '=', $r["nocmfk"])->first();

                $this->LOGGING(
                    'Reservasi',
                    $pasien->nocm,
                    'pasien_m',
                    $namaLog . ' pada Pasien ' .  $pasien->namapasien . ' (' . $pasien->nocm . ')'
                );

                DB::commit();
                $response = [
                    "data" => $data,
                    "status" => 201,
                    "message" => "Data berhasil dibuat"
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // Dev mode
                $response = [
                    "data" => [],
                    "status" => 400,
                    "message" => $e->getMessage() . ' ' . $e->getLine()
                ];
            }
        }

        return $this->respond($response, $response['status'], $response['message']);
    }

    public function saveRiwayatKontrolWeb(Request $r)
    {
        // return $this->respond($r->poliKontrol['id'], 200, "test");
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $this->kdProfile;
        $namapasien = '';
        $nocm = '';
        $validator = Validator::make($r->all(), [
            // "nobukti" => "required",
            // "diagnosaakhir" => "required",
            // "indikasikontrol" => "required",
            // "terapi" => "required",
        ]);

        if ($validator->fails()) {
            $response = [
                "data" => $validator->errors(),
                "status" => 400,
                "message" => "Terdapat data yang belum terisi"
            ];
        } else {
            DB::beginTransaction();
            try {

                if ($r["isReservasiBaru"] == false) {
                    $idPasien = Pasien::where(
                        'id',
                        $r["nocmfk"]
                    )->where('kdprofile', $kdProfile)->first();
                }


                // $umur =  $this->getAge($idPasien->tgllahir, date('Y-m-d H:i:s'));
                if ($r["isReservasiBaru"] == false) {
                    if ($r['norec_apr'] == '' || $r['norec_apr'] == null || $r['norec_apr'] == 'undefined') {
                        if ($r['kelompokpasien'] != "2") {
                            $checkDaftar = DB::table('antrianpasienregistrasi_t as apr')
                                ->whereDate('apr.tanggalreservasi', '=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])))
                                ->where('apr.objectruanganfk', $r['poliKontrol']['id'])
                                ->where('apr.nocmfk', $r['nocmfk'])
                                ->where('apr.statusenabled', true)
                                ->first();

                            if (isset($checkDaftar)) {
                                DB::rollback();
                                $response = [
                                    "data" => [],
                                    "status" => 400,
                                    "message" => "Tidak dapat mendaftar di Poli yang sama pada hari yang sama"
                                ];

                                return $this->respond($response, $response['status'], $response['message']);
                            }
                        } else {
                            $checkDaftar = DB::table('antrianpasienregistrasi_t as apr')
                                ->whereDate('apr.tanggalreservasi', '=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])))
                                ->where('apr.nocmfk', $r['nocmfk'])
                                ->where('apr.statusenabled', true)
                                ->first();

                            if (isset($checkDaftar)) {
                                DB::rollback();
                                $response = [
                                    "data" => [],
                                    "status" => 400,
                                    "message" => "Pasien BPJS tidak dapat mendaftar di Hari yang sama"
                                ];

                                return $this->respond($response, $response['status'], $response['message']);
                            }
                        }
                        // $checkDaftar = DB::table('antrianpasienregistrasi_t as apr')
                        // ->whereDate('apr.tanggalreservasi', '=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])))
                        // ->where('apr.objectruanganfk', $r['poliKontrol']['id'])
                        // ->where('apr.nocmfk', $r['nocmfk'])
                        // ->where('apr.statusenabled', true)
                        // ->first();

                        // if(isset($checkDaftar)) {
                        //     DB::rollback();
                        //     $response = [
                        //         "data" => [],
                        //         "status" => 400,
                        //         "message" => "Tidak dapat mendaftar di Poli yang sama pada hari yang sama"
                        //     ];

                        //     return $this->respond($response, $response['status'], $response['message']);
                        // }
                    }
                }
                if ($r["isReservasiBaru"] == false) {
                    $datetime = new \DateTime(date($idPasien->tgllahir));
                } else {
                    $datetime = new \DateTime(date('Y-m-d', strtotime($r["tgllahir"])));
                }

                $umur = (int) $datetime->diff(new \DateTime(date('Y-m-d')))->format('%y');

                // var_dump($umur);

                $maxantrian = AntrianPasienRegistrasi::whereDate('tanggalreservasi', '=', date('Y-m-d', strtotime($r["tglRencanaKontrol"])))
                    ->where('objectruanganfk', '=', $r['poliKontrol']['id'])
                    ->max('noantrianpoli');

                $jenis = '';
                if ($umur <= 17 || $umur > 60) {
                    $jenis = 'RA';
                } else {
                    $jenis = 'RL';
                }

                $APR = new AntrianPasienRegistrasi();
                $APR->norec = $APR->generateNewId();
                $APR->kdprofile = $kdProfile;
                $APR->statusenabled = true;
                $APR->noreservasi = 'W' . substr(Uuid::uuid4()->toString(), 0, 7);
                if ($r["isReservasiBaru"] == false) {
                    $APR->nocmfk = $idPasien->id;
                    $namapasien = $idPasien->namapasien;
                    $nocm = $idPasien->nocm;
                    if ($idPasien->objectpendidikanfk != null) {
                        $APR->objectpendidikanfk = $idPasien->objectpendidikanfk;
                    } else {
                        $APR->objectpendidikanfk = 0;
                    }
                    $APR->objectjeniskelaminfk = $idPasien->objectjeniskelaminfk;
                    $APR->objectkebangsaanfk = $idPasien->objectkebangsaanfk;
                    $APR->tipepasien = 'LAMA';
                    $APR->type = 'LAMA';
                    $APR->jenis = $jenis;
                }

                $APR->objectpegawaifk = $r["kodeDokter"] != null ? $r['kodeDokter']['id'] : null;
                $APR->objectruanganfk = $r['poliKontrol']['id'];
                $APR->objectkelompokpasienfk = $r['kelompokpasien'];
                $APR->tanggalreservasi = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) . ' ' . date('H:i:s');
                $APR->noantrianpoli = $maxantrian + 1;
                $APR->tglinput = date('Y-m-d H:i:s');
                $APR->iskiosk = true;
                $APR->loketkiosk = 3;
                $APR->isreservasi = true;
                $APR->umursaatinput = $umur;
                $APR->jam = $r["jam"] ?? null;
                $APR->keterangan = $r["catatan"] ?? null;
                $APR->norujukan = $r["nokunjungan"] ?? null;
                $APR->perjanjianfk = null;
                $APR->objectkelompokpasienfk = $r['kelompokpasien'];
                $APR->notelepon = $r['noTelpon'] ?? null;
                if ($r["isReservasiBaru"] == true) {
                    $APR->objectagamafk = $r['agama'];
                    $APR->alamatlengkap = $r['alamat'];
                    $APR->objectjeniskelaminfk = $r['jenisKelamin'];
                    $APR->namapasien = $r['namapasien'];
                    $namapasien = $r['namapasien'];
                    $APR->nobpjs = $r['nobpjs'];
                    $APR->noidentitas = $r['nik'];
                    $APR->tempatlahir = $r['tempatlahir'];
                    $APR->tgllahir = date('Y-m-d', strtotime($r['tgllahir']));
                    $APR->tipepasien = 'LAMA';
                    $APR->type = 'BARU';
                    $APR->jenis = 'RB';
                    $APR->objectkebangsaanfk = $r['kebangsaan'];
                }
                $APR->save();

                if ($r["isReservasiBaru"] == false) {
                    $app = new RiwayatKontrol;
                    $app->norec = $app->generateNewId();
                    $app->nocmfk = $r["nocmfk"];
                    $app->objectpegawaifk = $r["kodeDokter"] != null ? $r['kodeDokter']['id'] : null;
                    $app->objectruanganfk = $r['poliKontrol']['id'];
                    $app->kdprofile = $this->kdProfile;
                    $app->nosurat = $r["noSuratKontrol"] ?? null;
                    $app->tglkontrol = date('Y-m-d', strtotime($r["tglRencanaKontrol"])) . ' ' . date('H:i:s');;
                    $app->tglentry = date("Y-m-d");
                    $app->isterbitsep = isset($r["isterbit"]) ? true : false;
                    $app->nokartu = $r["noKartu"] ?? null;
                    $app->jenispelayanan = $r["pelayanan"];
                    $app->nosepasal = $r['noSEP'];
                    $app->terapi = $r["terapi"] ?? null;
                    $app->jam = $r["jam"] ?? null;
                    $app->catatan = $r["catatan"] ?? null;
                    $app->diagnosaakhir = $r["diagnosaakhir"] ?? null;
                    $app->indikasikontrol = $r["indikasikontrol"] ?? null;
                    $app->nobukti = $this->SEQUENCE(new RiwayatKontrol(), 'nosuratkontrol', 16, date('ymd') . 'CNT-', $idProfile);
                    $app->antrianpasienregistrasifk = $APR->norec;
                    $app->save();
                }

                $data = DB::table('antrianpasienregistrasi_t as apr')
                    ->select(DB::raw("apr.tanggalreservasi,
                    rk.jam,
                    coalesce(pasien.namapasien, apr.namapasien) as namapasien,
                    kbs.name as kebangsaan,
                    pasien.nocm,
                    TO_CHAR(age(pasien.tgllahir), 'YY Thn MM Bln DD Hr') as umur,
                    apr.noreservasi,
                    apr.noantrianpoli,
                    ruang.reportdisplay as poli,
                    kp.kelompokpasien"))
                    ->leftJoin('riwayatkontrol_t as rk', 'apr.norec', 'rk.antrianpasienregistrasifk')
                    ->leftJoin('pasiendaftar_t as pd', 'pd.antrianpasienregistrasifk', 'apr.norec')
                    ->leftJoin('pasien_m as pasien', 'pasien.id', 'apr.nocmfk')
                    ->join('ruangan_m as ruang', 'ruang.id', 'apr.objectruanganfk')
                    ->leftJoin('kebangsaan_m as kbs', 'kbs.id', 'pasien.objectkebangsaanfk')
                    ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'apr.objectkelompokpasienfk')
                    ->where('apr.kdprofile', $this->kdProfile)
                    ->where('apr.statusenabled', true)
                    ->where('apr.norec', $APR->norec)
                    ->groupBy(DB::raw("apr.tanggalreservasi,
                    rk.jam,
                    pasien.namapasien, apr.namapasien,
                    kbs.name,
                    pasien.nocm,
                    pasien.tgllahir,
                    apr.noreservasi,
                    apr.noantrianpoli,
                    ruang.reportdisplay,
                    kp.kelompokpasien"))
                    ->get();

                DB::commit();
                $response = [
                    "data" => $data,
                    "status" => 201,
                    "message" => "Data berhasil dibuat"
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // Dev mode
                $response = [
                    "data" => [],
                    "status" => 400,
                    "message" => $e->getMessage() . ' ' . $e->getLine()
                ];
            }
        }

        return $this->respond($response, $response['status'], $response['message']);
    }

    public function deleteRiwayatKontrol(Request $request)
    {
        DB::beginTransaction();
        $result["status"] = 400;
        $result["message"] = "";
        try {

            $APR = AntrianPasienRegistrasi::where('norec', '=', $request->norec_apr)->first();
            $namaLog = 'Hapus Reservasi ke Poli ' . Ruangan::mine()->where('id', $APR->objectruanganfk)->first()->namaruangan . ' Untuk Tanggal ' . date('Y-m-d', strtotime($APR->tanggalreservasi));
            $pasien = Pasien::where('id', '=', $APR->nocmfk)->first();


            $bu1 = RiwayatKontrol::where('norec', '=', $request->norec)->first();
            $bu1->statusenabled = false;
            $bu1->save();
            $bu2 = AntrianPasienRegistrasi::where('norec', '=', $request->norec_apr)->first();
            $bu2->statusenabled = false;
            $bu2->save();

            $this->LOGGING(
                'Reservasi',
                $pasien->nocm,
                'pasien_m',
                $namaLog . ' pada Pasien ' .  $pasien->namapasien . ' (' . $pasien->nocm . ')'
            );

            DB::commit();
            $result["status"] = 200;
            $result["message"] = "Riwayat Kontrol berhasil dihapus";
        } catch (\Exception $e) {
            DB::rollback();
            $result["status"] = 400;
            $result["message"] = $e->getMessage() . " " . $e->getLine();
        }

        return $this->respond($result, $result["status"], $result["message"]);
    }

    public function getPoliKasir(Request $req)
    {
        $query = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan as nama')
            ->where('ru.statusenabled', true)
            ->when(!empty($req->get('search')), function ($q) use ($req) {
                return $q->whereRaw("UPPER(namaruangan) LIKE '%" . strtoupper($req->get('search')) . "%'");
            })->get();
        return $this->respond($query, 200, "data found");
    }

    public function getPoli(Request $req)
    {
        $query = DB::table('ruangan_m')
            ->select('id', 'namaruangan as nama')
            ->where('statusenabled', true)
            ->when(!empty($req->get('search')), function ($q) use ($req) {
                return $q->whereRaw("UPPER(namaruangan) LIKE '%" . strtoupper($req->get('search')) . "%'");
            })->get();
        return $this->respond($query, 200, "data found");
    }

    public function getDokter(Request $req)
    {
        $query = DB::table('pegawai_m')
            ->select('id', 'namalengkap as nama')
            ->where('statusenabled', true)
            ->when(!empty($req->get('search')), function ($q) use ($req) {
                return $q->whereRaw("UPPER(namalengkap) LIKE '%" . strtoupper($req->get('search')) . "%'");
            })->get();
        return $this->respond($query, 200, "data found");
    }
}
