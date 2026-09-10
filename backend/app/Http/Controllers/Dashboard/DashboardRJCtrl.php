<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JadwalDokter;
use App\Models\Master\Kamar;
use App\Models\Master\KondisiPasien;
use App\Models\Master\Pasien;
use App\Models\Master\Ruangan;
use App\Models\Master\StatusKeluar;
use App\Models\Master\StatusPulang;
use App\Models\Standar\KelompokUser;
use App\Models\Standar\MapLoginUserToRuangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\EvaluasiPasien;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DashboardRJCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    // DROPDOWN RUANGAN

    public function getDD(Request $r)
    {
        $kelompokUser = KelompokUser::where('id', session('kelompokuser_id'))->first();
        //var_dump($kelompokUser->id);

        $ruangannurse = DB::table('mapkelompokusertoruangan_m as mku')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mku.objectruanganfk')
            ->select('ru.*')
            ->where('mku.kdprofile', $this->kdProfile)
            ->where('mku.statusenabled', true)
            ->where('mku.objectkelompokuserfk', $kelompokUser->id)
            ->get();
        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();
        $ruangansemua = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();
        $ruanganJadwalNuklir = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->whereIn('ru.id', [331, 338, 389, 268])
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();


        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        $set = explode(',', $this->settingFix('idDepartemenRajal'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->whereIn('id', $ruanganTersedia)->get();
        $res['ruangannurse'] = $ruangannurse;
        $res['ruangansemua'] = $ruangansemua;
        $res['ruanganJadwalNuklir'] = $ruanganJadwalNuklir;
        $res['statuskeluar'] = StatusKeluar::mine()->get();
        $res['statuspulang'] = StatusPulang::mine()->get();
        $res['kondisipasien'] = KondisiPasien::mine()->get();

        return $this->respond($res);
    }

    public function getDDNurse(Request $r)
    {
        $kelompokUser = $r['idkelompokuser'];
        //var_dump($kelompokUser->id);

        $ruangannurse = DB::table('mapkelompokusertoruangan_m as mku')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mku.objectruanganfk')
            ->select('ru.*')
            ->where('mku.kdprofile', $this->kdProfile)
            ->where('mku.statusenabled', true)
            ->where('mku.objectkelompokuserfk', $kelompokUser)
            ->distinct()
            ->get();
        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        $set = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->whereIn('id', $ruanganTersedia)->get();
        $res['ruangannurse'] = $ruangannurse;
        $res['statuskeluar'] = StatusKeluar::mine()->get();
        $res['statuspulang'] = StatusPulang::mine()->get();
        $res['kondisipasien'] = KondisiPasien::mine()->get();

        return $this->respond($res);
    }

    public function getRJPasienNurse(Request $r)
    {
        $ruanganTersedia = [];

        $data = DB::table('antrianpasiendiperiksa_t  as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as per', 'pd.perawatfk', '=', 'per.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            // ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            // ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            // ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            // ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            // ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kendalidokumen_t as kd', 'kd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kebangsaan_m as bg', 'ps.objectkebangsaanfk', '=', 'bg.id')
            ->select(
                'ru.namaruangan',
                'pd.norec as norec_pd',
                'pd.nocmfk',
                'ps.nocm',
                'ps.nobpjs as nobpjs',
                // 'ds.namadesakelurahan',
                // 'km.namakecamatan',
                // 'kbp.namakotakabupaten',
                'ps.alamatrmh',
                'ps.nobpjs',
                'ps.noidentitas',
                'ps.tgllahir',
                'pd.objectpegawaifk',
                'pd.noregistrasi',
                'pd.tglcetak',
                'pg.namalengkap',
                'pg.id as pgid',
                'ps.namapasien',
                'jk.jeniskelamin',
                // 'emr.tglberakhir',
                // 'emr.tglmulai',
                // 'emr.objectkelompokuserfk',
                // 'emr.pegawaipemohonfk',
                'apd.noantrian',
                'per.namalengkap as perawat',
                'apd.status',
                'apd.noantrian',
                'apd.norec as norec_apd',
                'kp.kelompokpasien',
                'pa.nosep',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'pd.perawatfk',
                'kl.namakelas',
                'kd.isdikirim',
                'apd.objectstrukorderfk',
                'apd.tglkeluar',
                'ps.tglmeninggal',
                'apd.objectruanganfk',
                'bg.name as kebangsaan',
                DB::raw("
                to_char(pd.tglregistrasi,'YYYY-mm-dd HH:mm:ss') as tanggal,
                to_char(pd.tglregistrasi,'HH:mm:ss') as jam"),
                DB::raw("null as tglkontrol"),
                DB::raw("null as diagnosaakhir"),
                DB::raw("null as indikasikontrol"),
                DB::raw("null as catatan"),
                DB::raw("null as norec_kontrol"),
                DB::raw("null as id_emr"),
                DB::raw("CAST(pd.tglregistrasi AS DATE),
                (case when pd.iscppt=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk")
            )

            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->whereNotNull('apd.tglkeluar')
            ->where('ps.statusenabled', true)
            ->where('pd.statusenabled', true)
            // ->where('pa.nosep', '!=', null)
            ->where('apd.statusenabled', true);
        $filter = false;
        // if(isset($r['ruanganfk']) && $r['idpegawai'] != ''){
        //     $data = $data
        // }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('ru.id', explode(',', $r['ruanganfk']));
        } else {
            $filter = true;
            $ruangan = DB::table('maploginusertoruangan_s as mlur')
                ->join('loginuser_s as lu', function ($j) {
                    $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
                })
                ->join('ruangan_m as ru', function ($j) {
                    $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
                })
                ->select('ru.id', 'ru.namaruangan')
                ->where('lu.kdprofile', $this->kdProfile)
                ->where('ru.statusenabled', true)
                ->where('lu.id', $this->getUserId())
                ->groupBy('ru.id', 'ru.namaruangan')
                ->get();
            foreach ($ruangan as $ru) {
                $ruanganTersedia[] = $ru->id;
            }

            $data = $data->whereIn('ru.id', $ruanganTersedia);
        }

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $filter = true;
            $data = $data->where('pg.id', '=', $r['idpegawai']);
        }

        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $filter = true;
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $filter = true;
            $data = $data->where('ps.nocm', '=', $r['nocm']);
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $filter = true;
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        // if (isset($r['statuspanggil']) && $r['statuspanggil'] != '') {
        //     $filter = true;
        //     $data = $data->where('apd.status', '=',  $r['statuspanggil']);
        // }
        // if (isset($r['limit']) && $r['limit'] != '') {
        //     $data = $data->limit($r['limit']);
        // }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '' && $filter == true) {
            $page = $r['page'];
        }
        $data = $data->orderBy('apd.noantrian');
        // $data = $data->get();
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 50);

        $newArr = $data->getCollection();
        $newTotal = $data->total();
        foreach ($newArr as $kDt => $dt) {
            $dt->umur = $this->getAgeYear($dt->tgllahir, $dt->tglregistrasi) . ' thn';
            // $d->tanggal
            $suratkontrol = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->join('antrianpasienregistrasi_t as apr', 'ps.id', '=', 'apr.nocmfk')
            ->join('riwayatkontrol_t as rk', 'apr.norec', 'rk.antrianpasienregistrasifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apr.statusenabled', true)
            ->whereRaw("rk.tglentry::date = pd.tglregistrasi::date")
            ->where("pd.nocmfk", "=", $dt->nocmfk)
            ->where('pd.norec', $dt->norec_pd)
            ->select('pd.tglregistrasi', 'pd.noregistrasi', 'ps.namapasien', 'rk.nocmfk', 'rk.tglkontrol', 'rk.catatan', 'rk.indikasikontrol', 'rk.diagnosaakhir', 'rk.norec')
            ->groupBy('pd.tglregistrasi', 'pd.noregistrasi', 'ps.namapasien', 'rk.nocmfk', 'rk.tglkontrol', 'rk.catatan', 'rk.indikasikontrol', 'rk.diagnosaakhir', 'rk.norec')
            ->orderBy('rk.tglkontrol', 'desc')
            ->first();


            if (isset($suratkontrol)) {
                $dt->tglkontrol = $suratkontrol->tglkontrol;
                $dt->diagnosaakhir = $suratkontrol->diagnosaakhir;
                $dt->indikasikontrol = $suratkontrol->indikasikontrol;
                $dt->norec_kontrol = $suratkontrol->norec;
                $dt->catatan = $suratkontrol->catatan;
            }

            $res = DB::connection('mongodb')
                ->table('AsesmenAwalKeperawatanPasienRawatJalanNurse')
                ->select('*')
                ->where('registrasi.norec_pd', $dt->norec_pd)
                ->where('statusenabled', true)
                ->orderByDesc('updated_at')
                ->orderByDesc('created_at')
                ->first();

            $res2 = DB::connection('mongodb')
                ->table('AsesmenAwalKebidananRawatJalanNurse')
                ->select('*')
                ->where('registrasi.norec_pd', $dt->norec_pd)
                ->where('statusenabled', true)
                ->orderByDesc('updated_at')
                ->orderByDesc('created_at')
                ->first();

            if (!empty($res)) {
                $dt->id_emr = $res['_id'];
            } else if (!empty($res2)) {
                $dt->id_emr = $res2['_id'];
            }

            if ($dt->kelompokpasien == "BPJS") {
                if ($dt->nosep == null || $dt->tglcetak == null) {
                    unset($newArr[$kDt]);
                    $newTotal--;
                }
            } else {
                if ($dt->tglcetak == null) {
                    unset($newArr[$kDt]);
                    $newTotal--;
                }
            }
        }

        if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Belum Dipanggil') {
            $newArr = $newArr->where('id_emr', '==', null);
        } else if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Selesai') {
            $newArr = $newArr->where('id_emr', '!=', null);
        }
        // $newArr = $newArr->get();
        $newPaginate = new \Illuminate\Pagination\LengthAwarePaginator(
            array_values($newArr->toArray()),
            $newTotal,
            $data->perPage(),
            $page,
            [
                'path' => \Request::url(),
                'query' => [
                    'page' => $page
                ]
            ]
        );

        return $this->respond($newPaginate);
    }

    public function getRawatJalanDetail(Request $r)
    {
        $now = $this->hari_ini(date('Y-m-d'));
        $dokter = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->where('jd.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->where('jd.hari', 'ilike', '%' . $now . '%')
            ->where('pg.namalengkap', '<>', 'Dokter Umum');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=', $r['ruanganid']);
        }
        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $dokter = $dokter->where('jd.objectpegawaifk', '=', $r['idpegawai']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike', '%' . $r['namadokter'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit($r['limit']);
        }
        $dokter->orderBy('pg.namalengkap');

        $dokter = $dokter->get();

        foreach ($dokter as $d) {
            $d->hari = $now;
        }

        $produk = DB::table('stokprodukdetail_t as spd')
            ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
            ->leftjoin('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
            ->select(
                DB::raw("sum(spd.qtyproduk) as qtyproduk,
                ru.id,
                ru.namaruangan,
                pr.namaproduk,
                ap.asalproduk,
                spd.harganetto1,
                spd.harganetto2")
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $produk->where('ru.id', '=', $r['ruanganid']);
        }
        if (isset($r['nama']) && $r['nama'] != '') {
            $produk->where('pr.namaproduk', 'ilike', '%' . $r['nama'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $produk->limit($r['limit']);
        }
        $produk->groupBy('ru.id', 'ru.namaruangan', 'pr.namaproduk', 'ap.asalproduk', 'spd.harganetto1', 'spd.harganetto2');
        $produk->orderBy('pr.namaproduk');
        $produk = $produk->get();


        $res['dokter'] = $dokter;
        $res['produk'] = $produk;
        return $this->respond($res);
    }

    public function getRJPasien(Request $r)
    {
        $tglawal = $r['dari'];
        $tglakhir = $r['sampai'];
        $data = DB::table('antrianpasiendiperiksa_t  as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'apd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as pg1', 'pd.objectpegawaifk', '=', 'pg1.id')
            ->leftjoin('pegawai_m as per', 'pd.perawatfk', '=', 'per.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            // ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            // ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            // ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            // ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            // ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kendalidokumen_t as kd', 'kd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kebangsaan_m as bg', 'ps.objectkebangsaanfk', '=', 'bg.id')
            ->select(
                'apd.iskonsul as konsul',
                'ru.namaruangan',
                'ru.kdinternal as kdpoli',
                'pd.norec as norec_pd',
                'pd.tglclosing',
                'pd.nocmfk',
                'ps.nocm',
                'pg1.namalengkap as dpjp',
                'ps.nobpjs as nobpjs',
                // 'ds.namadesakelurahan',
                // 'km.namakecamatan',
                // 'kbp.namakotakabupaten',
                'ps.alamatrmh',
                'ps.nobpjs',
                'ps.noidentitas',
                'ps.tgllahir',
                'pd.objectpegawaifk',
                'pd.noregistrasi',
                'pg.namalengkap',
                'pg.id as pgid',
                'ps.namapasien',
                'jk.jeniskelamin',
                // 'emr.tglberakhir',
                // 'emr.tglmulai',
                // 'emr.objectkelompokuserfk',
                // 'emr.pegawaipemohonfk',
                'apd.noantrian',
                'per.namalengkap as perawat',
                'apd.status',
                'apd.norec as norec_apd',
                'kp.kelompokpasien',
                'pa.nosep',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'pd.perawatfk',
                'pd.isasmed',
                // 'pd.ispelayananpasien',
                'pd.istindakan',
                'pd.isaskeprj',
                'pd.iscppt',
                'kl.namakelas',
                'kd.isdikirim',
                'apd.objectstrukorderfk',
                'apd.tglkeluar',
                'ps.tglmeninggal',
                'apd.objectruanganfk',
                'ru.objectdepartemenfk',
                'ru.id as norecpoli',
                'apd.tglregistrasi',
                'bg.name as kebangsaan',
                // 'apd.isasmed',
                'apd.isaskepnurse',
                // 'apd.isaskeprj as apd_isaskeprj',
                'apd.iscppt_perawat',
                'apd.iscppt_dokter',
                DB::raw("CAST(pd.tglregistrasi AS DATE) as tglregistrasi_date,ps.objectjeniskelaminfk")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->whereraw("apd.tglregistrasi::date between '$tglawal' and '$tglakhir'")
            ->where('ps.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            // ->where('apd.isaskepnurse', true)
            ->where(function ($query) {
                $query->where('kp.kelompokpasien', '!=', 'BPJS')
                      ->orWhereNotNull('pa.nosep'); // Allow BPJS only if nosep is NOT null
            })
            ->orderby("ru.namaruangan");

        $filter = false;
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('ru.id', explode(',', $r['ruanganfk']));
        } else {
            $filter = true;
            $ruangan = DB::table('maploginusertoruangan_s as mlur')
                ->join('loginuser_s as lu', function ($j) {
                    $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
                })
                ->join('ruangan_m as ru', function ($j) {
                    $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
                })
                ->select('ru.id', 'ru.namaruangan')
                ->where('lu.kdprofile', $this->kdProfile)
                ->where('ru.statusenabled', true)
                ->where('lu.id', $this->getUserId())
                ->groupBy('ru.id', 'ru.namaruangan')
                ->get();

            $ruanganTersedia = [];
            foreach ($ruangan as $ru) {
                $ruanganTersedia[] = $ru->id;
            }
            $data = $data->whereIn('ru.id', $ruanganTersedia);
        }
        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $filter = true;
            $data = $data->where('pg.id', '=', $r['idpegawai']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $filter = true;
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $filter = true;
            $data = $data->where('ps.nocm', '=', $r['nocm']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['status']) && $r['status'] != '') {
            $filter = true;
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        if (isset($r['kelompokUser']) && $r['kelompokUser'] == 'perawat') {
            if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Menunggu Pelayanan') {
                $filter = true;
                // $data = $data->where('apd.iscppt_perawat', '=', null);
                // $data = $data->where('pd.isaskeprj', '=', null);
                $data = $data->where(function ($query) {
                    $query->where(function ($a) {
                        $a->where('apd.iskonsul', '!=', null)
                        ->where('apd.iscppt_perawat', '=', null)
                        ->where('pd.isaskeprj', '=', null)
                        ->where('pd.tglclosing', '=', null);
                    })->orWhere(function ($a) {
                        $a->where('apd.iskonsul', '=', null)
                        ->where(function ($q) {
                            $q->where('pd.isaskeprj', '=', null)
                            ->where('apd.iscppt_perawat', '=', null)
                            ->where('pd.tglclosing', '=', null);
                        });
                    });
                });
            }
            if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Selesai') {
                $filter = true;
                $data = $data->where(function ($query) {
                    $query->where(function ($a) {
                        $a->where('apd.iskonsul', '!=', null)
                        ->where('apd.iscppt_perawat', '!=', null);
                    })->orWhere(function ($a) {
                        $a->where('apd.iskonsul', '=', null)
                        ->where(function ($q) {
                            $q->where('pd.isaskeprj', '!=', null)
                            ->orWhere('apd.iscppt_perawat', '!=', null);
                        });
                    });
                });
            }
        } else if (isset($r['kelompokUser']) && $r['kelompokUser'] == 'dokter') {
            if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Menunggu Pelayanan') {
                $filter = true;
                $data = $data->where(function ($query) {
                    $query->where(function ($a) {
                        $a->where('apd.iskonsul', '!=', null)
                        ->where('apd.iscppt_dokter', '=', null)
                        ->where('pd.isasmed', '=', null);
                    })->orWhere(function ($a) {
                        $a->where('apd.iskonsul', '=', null)
                        ->where(function ($q) {
                            $q->where('pd.isasmed', '=', null)
                            ->where('apd.iscppt_dokter', '=', null);
                        });
                    });
                });
            }
            //  PASIEN KONSUL SALAH DISINI
            if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Selesai') {
                $filter = true;
                $data = $data->where('pd.tglclosing', '=', null);
                $data = $data->where(function ($query) {
                    $query->where(function ($a) {
                        $a->where('apd.iskonsul', '!=', null)
                        ->where('apd.iscppt_dokter', '!=', null)
                        ->orWhere('pd.isasmed', '!=', null);
                    })->orWhere(function ($a) {
                        $a->where('apd.iskonsul', '=', null)
                        ->where(function ($q) {
                            $q->where('pd.isasmed', '!=', null)
                            ->orWhere('apd.iscppt_dokter', '!=', null);
                        });
                    });
                });
            }
        } else {
            if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Menunggu Pelayanan') {
                $filter = true;
                $data = $data->where('pd.isasmed', '=', null);
                $data = $data->where('pd.iscppt', '=', null);
                $data = $data->where('pd.isaskeprj', '=', null);
            }
            if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Selesai') {
                $filter = true;
                $data = $data->where('pd.tglclosing', '=', null);
                $data = $data->where(function ($query) {
                    $query->where('pd.isasmed', '!=', null)
                        ->orWhere('pd.iscppt', '!=', null);
                });
            }
        }

        if (isset($r['statuspanggil']) && $r['statuspanggil'] == 'Sudah Closing') {
            $filter = true;
            $data = $data->where('pd.tglclosing', '!=', null);
            $data = $data->where(function ($query) {
                $query->where('pd.isasmed', '!=', null)
                    ->orWhere('pd.iscppt', '!=', null)
                    ->orWhere('pd.isaskeprj', '!=', null);
            });
        }

        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->orderBy('apd.noantrian', 'ASC');
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 50, ['*'], 'page', $page);

        try {
            if (env('APP_DEVMODE')) {
                $rad = [];

                $s_tglawal = str_replace("-", "", $tglawal);
                $s_tglakhir = str_replace("-", "", $tglakhir);
                $lab = [];
            } else {
                $rad = [];
                $lab = [];
                // $rad = DB::connection('sqlsrv_ris')
                //     ->table('ris_in as ri')
                //     ->join('ris_out as ro', 'ri.no_rontgen', '=', 'ro.no_rontgen')
                //     // ->where('ri.no_register', '=', $d->noregistrasi)
                //     ->whereRaw("format(tanggal_order,'yyyy-MM-dd') between '$tglawal' and '$tglakhir'")
                //     ->select('ro.*', 'ri.nobukti', 'ri.kode_pemeriksaan')
                //     ->get()->toArray();
                $s_tglawal = str_replace("-", "", $tglawal);
                $s_tglakhir = str_replace("-", "", $tglakhir);
                // $lab = DB::connection('sqlsrv_lis')
                //     ->table('reshd as rh')
                //     ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
                //     // ->where('rh.PID', $d->nocm)
                //     ->whereRaw("LEFT(convert(varchar, REQUEST_DT, 112),8) between '$s_tglawal' and '$s_tglakhir'")
                //     ->select('rd.*', 'rh.CLINICIAN_NM')
                //     ->get()->toArray();
            }

            $strukorder = DB::table('strukorder_t as so')
                ->join('ruangan_m as r', 'r.id', '=', 'so.objectruanganfk')
                ->where('so.statusenabled', true)
                ->where('r.statusenabled', true)
                ->where('r.objectdepartemenfk', '3')
                ->whereraw("so.tglorder::date between '$tglawal' and '$tglakhir'")
                ->select('so.noregistrasifk', 'noorder')
                ->get()->toArray();

            // IGD , HEMO , KEMO
            $s_tglawal = $tglawal . ' 00:00:00';
            $s_tglakhir = $tglakhir . ' 23:59:59';
        } catch (\Exception $ex) {
            $rad = [];
            $lab = [];
            $res_1 = [];
            $res_2 = [];
            $strukorder = [];
        }

        $nocmfks = collect($data->items())->pluck('nocmfk');
        $surkonss = DB::table('pasiendaftar_t as pd')
        ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
        ->join('antrianpasienregistrasi_t as apr', 'ps.id', '=', 'apr.nocmfk')
        ->join('riwayatkontrol_t as rk', 'apr.norec', 'rk.antrianpasienregistrasifk')
        ->where('pd.kdprofile', $this->kdProfile)
        ->where('pd.statusenabled', true)
        ->where('apr.statusenabled', true)
        ->whereRaw("rk.tglentry::date = pd.tglregistrasi::date")
        ->whereIn('pd.nocmfk', $nocmfks)
        // ->where("pd.nocmfk", "=", $d->nocmfk)
        // ->where('pd.norec', $d->norec_pd)
        ->select('pd.norec as norec_pd','pd.tglregistrasi', 'pd.noregistrasi', 'ps.namapasien', 'rk.nocmfk', 'rk.tglkontrol', 'rk.catatan', 'rk.indikasikontrol', 'rk.diagnosaakhir', 'rk.norec')
        ->groupBy('pd.norec','pd.tglregistrasi', 'pd.noregistrasi', 'ps.namapasien', 'rk.nocmfk', 'rk.tglkontrol', 'rk.catatan', 'rk.indikasikontrol', 'rk.diagnosaakhir', 'rk.norec')
        ->orderBy('rk.tglkontrol', 'desc')
        ->get()
        ->groupBy('norec_pd');
        // return $surkonss;
        $kabehkontrol = DB::table('riwayatkontrol_t')
        ->whereIn('nocmfk', $nocmfks)
        ->get()
        ->groupBy('nocmfk');

        foreach ($data as $kd => $d) {
            $d->umur = $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';
            $sk = $surkonss[$d->norec_pd] ?? collect([]);
            $suratkontrol = $sk->first();
            $valueSkio=0;
            $targetCount = $kabehkontrol[$d->nocmfk] ?? collect([]);
            $valueSkio=count($targetCount);
            $dataIndikasiKontrol = $targetCount->where('tglkontrol', $d->tglregistrasi_date)->first();
            if(isset($dataIndikasiKontrol)){
                $d->indiakasiKontrolNew=$dataIndikasiKontrol->indikasikontrol;
                $d->norecIndikasiKontrolNew=$dataIndikasiKontrol->norec;
                $d->diagnosaIndikasiKontrolNew=$dataIndikasiKontrol->diagnosaakhir;
            }
            if (isset($suratkontrol)) {
                $d->tglkontrol = $suratkontrol->tglkontrol;
                $d->diagnosaakhir = $suratkontrol->diagnosaakhir;
                $d->indikasikontrol = $suratkontrol->indikasikontrol;
                $d->norec_kontrol = $suratkontrol->norec;
                $d->catatan = $suratkontrol->catatan;
            }

            $d->statusrad = 'Belum Ada Hasil';
            $d->statuslab = 'Belum Ada Hasil';

            $d->class_statuslab = "is-danger";
            $d->class_statusrad = "is-danger";

            $noorder = null;
            $strukorders = $strukorder;
            $s_norec_apd = $d->norec_apd;
            $s_norec_pd = $d->norec_pd;
            $s_noregistrasi = $d->noregistrasi;
            $rads = $rad;
            if (!empty($rads)) {
                $r_rad = [];
                $r_rad = array_filter($rads, function ($d_rad) use ($s_noregistrasi) {
                    return $d_rad->no_register === $s_noregistrasi;
                });
                if (!empty($r_rad)) {
                    $d->statusrad = 'Ada Hasil';
                    $d->class_statusrad = "is-success";
                }
            }

            if (!empty($strukorders)) {
                $r_so = [];
                $r_so = array_filter($strukorders, function ($d_so) use ($s_norec_apd) {
                    return $d_so->noregistrasifk === $s_norec_apd;
                });
                if (!empty($r_so)) {
                    $noorder = $strukorders[array_keys($r_so)[0]]['noorder'];
                    $labs = $lab;
                    if (!empty($labs)) {
                        $r_lab = [];
                        $r_lab = array_filter($labs, function ($d_lab) use ($noorder) {
                            return $d_lab['ono'] === $noorder;
                        });
                        if (!empty($r_lab)) {
                            $d->statuslab = 'Ada Hasil';
                            $d->class_statuslab = "is-success";
                        }
                    }
                }
            }

            switch ($d->kebangsaan) {
                case 'WNA KITAS':
                    $d->class_kebangsaan = 'tag is-warning';
                    break;
                case 'WNA NON KITAS':
                    $d->class_kebangsaan = 'tag is-danger';
                    break;

                default:
                    $d->class_kebangsaan = 'tag is-info';
                    break;
            }
            if (isset($r['kelompokUser']) && $r['kelompokUser'] == 'perawat') {
                if ($d->iscppt_perawat == null && $d->tglclosing == null && $d->konsul == true) {
                    $d->class_statusperiksa = "is-danger tag";
                    $d->label_statusperiksa = "Menunggu Pelayanan";
                } else if (($d->isaskeprj != null || $d->iscppt_perawat != null) && $d->tglclosing == null) {
                    $d->class_statusperiksa = "is-warning tag";
                    $d->label_statusperiksa = "Selesai";
                } else if (($d->iscppt_perawat != null || $d->isaskeprj != null) && $d->tglclosing != null) {
                    $d->class_statusperiksa = "is-success tag";
                    $d->label_statusperiksa = "Sudah Closing";
                } else {
                    $d->class_statusperiksa = "is-danger tag";
                    $d->label_statusperiksa = "Menunggu Pelayanan";
                }
            } else if (isset($r['kelompokUser']) && $r['kelompokUser'] == 'dokter') {
                if($d->konsul == null) {
                    if (($d->isasmed != null || $d->iscppt_dokter != null) && $d->tglclosing == null) {
                        $d->class_statusperiksa = "is-warning tag";
                        $d->label_statusperiksa = "Selesai";
                    } else if (($d->iscppt_dokter != null || $d->isasmed != null) && $d->tglclosing != null) {
                        $d->class_statusperiksa = "is-success tag";
                        $d->label_statusperiksa = "Sudah Closing";
                    }else {
                        $d->class_statusperiksa = "is-danger tag";
                        $d->label_statusperiksa = "Menunggu Pelayanan";
                    }
                }else if ($d->konsul) {
                    if (($d->isasmed != null || $d->iscppt_dokter != null) && $d->tglclosing == null) {
                        $d->class_statusperiksa = "is-warning tag";
                        $d->label_statusperiksa = "Selesai";
                    } else if (($d->isasmed != null || $d->iscppt_dokter != null) && $d->tglclosing != null) {
                        $d->class_statusperiksa = "is-success tag";
                        $d->label_statusperiksa = "Sudah Closing";
                    }else {
                        $d->class_statusperiksa = "is-danger tag";
                        $d->label_statusperiksa = "Menunggu Pelayanan";
                    }
                }
            } else {
                if (($d->isasmed == null && $d->iscppt == null) || ($d->isaskeprj == null && $d->iscppt == null)) {
                    $d->class_statusperiksa = "is-danger tag";
                    $d->label_statusperiksa = "Menunggu Pelayanan";
                }
                if (($d->iscppt != null || $d->isasmed != null) && $d->tglclosing == null) {
                    $d->class_statusperiksa = "is-warning tag";
                    $d->label_statusperiksa = "Selesai";
                }
                if (($d->iscppt != null || $d->isasmed != null || $d->isaskeprj != null) && $d->tglclosing != null) {
                    $d->class_statusperiksa = "is-success tag";
                    $d->label_statusperiksa = "Sudah Closing";
                }
            }
        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    private function paginateArray(array $items, int $perPage = 5, ?int $page = null, array $options = [])
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }

    public function getRJPasienReservasi(Request $r)
    {
        $reservasi = DB::table('antrianpasienregistrasi_t as ap')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'ap.pasiendaftarfk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'ap.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'ap.objectruanganfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ap.objectpegawaifk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftJoin('kelompokpasien_m as kps', 'kps.id', '=', 'ap.objectkelompokpasienfk')
            ->leftjoin(db::raw('(select distinct noregistrasifk, noregistrasifk as norec from emrpasien_t where statusenabled=true) as emrp'),
            'emrp.noregistrasifk','=','pd.norec')
            ->select(
                'ap.norec',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'emrp.norec as norec_emr',
                'ps.nocm',
                'ps.nobpjs',
                'ps.noidentitas',
                'ap.noreservasi',
                'ap.tanggalreservasi',
                'ru.namaruangan',
                'ap.isconfirm',
                'pg.namalengkap as dokter',
                'ap.notelepon',
                'ps.namapasien',
                'ap.namapasien',
                'kps.kelompokpasien',
                'ap.tglinput',
                'ap.tipepasien',
                'ap.tgllahir',
                'ap.nocmfk',
                'ap.noantrianpoli',
                'ap.objectruanganfk',
                'ap.objectkelompokpasienfk',
                'ap.objectjeniskelaminfk',
                'ap.objectpegawaifk',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'pg.namalengkap as dokter',
                DB::raw('(case when ps.namapasien is null then ap.namapasien else ps.namapasien end) as namapasien,
            (case when ap.isconfirm=\'true\' then \'Confirm\' else \'Reservasi\' end) as status')
            )

            ->where('ap.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->where('ap.statusenabled', true)
            ->whereNotNull('ap.noreservasi')
            // ->whereNull('ap.noantrian')
            ->where('ap.noreservasi', '!=', '-')
            ->orderBy('ap.noantrianpoli', 'DESC');

        // ->where('ap.tanggalreservasi', '>=', date('Y-m-d 00:00:00'));

        if (isset($r['dokter']) && $r['dokter'] != '') {
            $reservasi = $reservasi->where('ap.objectpegawaifk', '=', $r['dokter']);
        }
        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $reservasi = $reservasi->where('ap.objectpegawaifk', '=', $r['idpegawai']);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $reservasi = $reservasi->whereIn('ru.id', explode(',', $r['ruanganfk']));
        }
        // if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
        //     $reservasi = $reservasi->whereIn('ru.id', explode(',', $r['ruanganid']));
        // }

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $reservasi = $reservasi->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ap.noreservasi', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['noreservasi']) && $r['noreservasi'] != '') {
            $reservasi = $reservasi->where('ap.noreservasi', '=', $r['noreservasi']);
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $reservasi = $reservasi->where('ap.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $reservasi = $reservasi->where(DB::raw("ap.tanggalreservasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $reservasi = $reservasi->where(DB::raw("ap.tanggalreservasi::date"), '<=', $r->sampai);
        }



        $reservasi = $reservasi->get();

        foreach ($reservasi as $d) {
            if ($d->objectkelompokpasienfk == null) {
                $d->objectkelompokpasienfk = 1;
                $d->kelompokpasien = 'Umum/Pribadi';
            }
            $d->status = 'Confirm';
            $d->status_c = 'purple';
            if ($d->isconfirm == null) {
                $d->status = 'Reservasi';
                $d->status_c = 'danger';
            }
            if($d->norec_emr != null){
                $d->class_statusperiksa = 'tag is-primary';
                $d->label_statusperiksa = 'Sudah Periksa';
            }else{
                $d->class_statusperiksa = 'tag is-danger';
                $d->label_statusperiksa = 'Belum Periksa';
            }
        }

        $res['reservasi'] = $reservasi;
        return $this->respond($res);
    }


    public function Ruangandropdown(Request $r)
    {
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }
    public function HitungAntrian(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::table('antrianpasiendiperiksa_t as at')
            ->join('ruangan_m as ru', 'ru.id', '=', 'at.objectruanganfk')
            ->select(DB::raw("count (at.norec) as total,
            case when at.status is null   then 'Belum Dipanggil' else  at.status end as name "))
            ->where('at.statusenabled', true)
            ->where('at.kdprofile', $kdProfile)
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')));
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=', $r['ruanganid']);
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where('at.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where('at.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        $data = $data->groupBy('at.status', 'at.statusantrian');
        $data = $data->get();

        $seriesJK = [0, 0, 0];
        $labelJK = ['Belum Dipanggil', 'Sudah Dipanggil', 'Selesai'];
        foreach ($data as $d) {
            if ($d->name == 'Belum Dipanggil') {
                $seriesJK[0] = $d->total;
            }
            if ($d->name == 'Sudah Dipanggil') {
                $seriesJK[1] = $d->total;
            }
            if ($d->name == 'Selesai') {
                $seriesJK[2] = $d->total;
            }

            // $labelJK[]  = $d->name;
        }
        $result['chartStatus']['series'] = $seriesJK;
        $result['chartStatus']['labels'] = $labelJK;
        return $this->respond($result);
    }
    public function panggilPasien(Request $r)
    {

        DB::beginTransaction();
        try {
            $status = 'Sudah Dipanggil';
            $kelompokUser = KelompokUser::where('id', session('kelompokuser_id'))->first();
            if (!empty($kelompokUser) && $kelompokUser->kelompokuser == 'dokter') {
                $update = [
                    'status' => $status,
                    'statusantrian' => 1,
                    'tgldipanggildokter' => date('Y-m-d H:i:s'),
                ];
            } else {
                $update = [
                    'status' => $status,
                    'statusantrian' => 1,
                    'tgldipanggilsuster' => date('Y-m-d H:i:s'),
                ];
            }
            AntrianPasienDiperiksa::where('norec', $r->norec_apd)
                ->where('kdprofile', $this->kdProfile)
                ->update($update);

            $url = $this->settingFix('urlTelemedicine') . "queues";
            $headers['Content-Type'] = 'application/json';

            $response = Http::withHeaders($headers)
                ->withoutVerifying()
                ->withOptions(["verify" => false])
                ->post($url, $r['telemedicine']);
            $transMessage = "Sukses";
            $pd = PasienDaftar::where('norec', $r->norec_pd)->where('kdprofile', $this->kdProfile)->first();
            $ihs = null;
            if ($pd->ihs_in_progress == null) {
                PasienDaftar::where('norec', $r->norec_pd)->where('kdprofile', $this->kdProfile)->update([
                    'ihs_in_progress' => date('Y-m-d H:i:s'),
                ]);
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $pd->noregistrasi;
                if ($pd->ihs_diagnosis != null) {
                    $objetoRequest['diagnosis'] = json_decode($pd->ihs_diagnosis);
                }
                $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Encounter($objetoRequest, true);
            }
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "status" => $status,
                    "queue_telemedicine" => $response->json(),
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDaftarKonsulFromOrder(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->Join('strukorder_t as so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('alamat_m as alm', 'ps.id', '=', 'alm.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            // ->leftJoin('departemen_m as dept','dept.id','=','ru.objectdepartemenfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'apd.residencefk')
            ->Join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftjoin('antrianpasienregistrasi_t as apr', 'apr.noreservasi', '=', 'pd.statusschedule')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'pa.objectasuransipasienfk', '=', 'asu.id')
            ->leftjoin('kelas_m as klstg', 'klstg.id', '=', 'asu.objectkelasdijaminfk')
            ->select(
                'apd.tglmasuk as tglregistrasi',
                'ps.nocm',
                'pd.noregistrasi',
                'ps.namapasien',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'kls.id as idkelas',
                'kls.namakelas',
                'kp.kelompokpasien',
                'rek.namarekanan',
                'apd.objectpegawaifk',
                'pg.namalengkap as namadokter',
                'pd.norec as norec_pd',
                'apd.norec as norec_apd',
                'apd.objectasalrujukanfk',
                'apd.tgldipanggildokter',
                'apd.statuspasien as statuspanggil',
                'pd.statuspasien',
                'apd.tgldipanggildokter',
                'apd.tgldipanggilsuster',
                'apr.noreservasi',
                'apd.noantrian',
                'apr.tanggalreservasi',
                'alm.alamatlengkap',
                'klstg.namakelas as kelasdijamin',
                'ru.ipaddress',
                'ps.iskompleks',
                'apd.residencefk',
                'pg2.namalengkap as residence',
                DB::raw('case when apd.ispelayananpasien is null then \'false\' else \'true\' end as statuslayanan')
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglmasuk as Date)"), $dateRange);

        $data = $data->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')));
        //        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->orderBy('apd.noantrian');
        $data = $data->get();
        return $this->respond($data);
    }

    public function getComboCount(Request $request)
    {
        $dinamicRuangan = explode(',', $request['ruanganfk']);

        $defaultRuangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($defaultRuangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        $tglBetween = [$request->dari, $request->sampai];
        $datas = [
            'jumlahPasien' => $this->countPasien($tglBetween, $request['idpegawai'], $dinamicRuangan, $ruanganTersedia),
            'jumlahTindakan' => $this->countPasienTindakan($tglBetween, $request['idpegawai'], $dinamicRuangan, $ruanganTersedia),
            'pendapatanJasa' => $this->countJasaPelayanan($tglBetween, $request['idpegawai'])
        ];
        return $this->respond($datas);
    }

    public function countPasien($rangeDate, $pegawaifk, $dinamicRuangan, $defaultRuangan)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->where('apd.statusenabled', true)
            ->whereraw("case when ru.objectdepartemenfk not in ('9') and  apd.objectruanganfk not in('332','237') then apd.isaskepnurse = true end")
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->whereNotNull('pd.tglpulang')
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $rangeDate)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('KdDeptPasienRJ')));
        if ($pegawaifk) {
            $data = $data->where('pd.objectpegawaifk', $pegawaifk);
        }
        if ($dinamicRuangan && $dinamicRuangan != [""]) {
            $data = $data->whereIn('pd.objectruanganlastfk', $dinamicRuangan);
        } else {
            $data = $data->whereIn('pd.objectruanganlastfk', $defaultRuangan);
        }
        $data = $data->count();
        return $data;
    }

    public function countPasienTindakan($rangeDate, $pegawaifk, $dinamicRuangan, $defaultRuangan)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', 'ru.id')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('pelayananpasiendetail_t as ppd', 'ppd.pelayananpasien', 'pp.norec')
            ->join('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->where('apd.statusenabled', true)
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('pp.keteranganlain', 'Tindakan')
            ->where('ppd.komponenhargafk', $this->settingFix('kdJasaPelayanan'))
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $rangeDate)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $rangeDate)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('KdDeptPasienRJ')));

        if ($pegawaifk) {
            $data = $data->where('ppp.objectpegawaifk', $pegawaifk);
        }
        if ($dinamicRuangan && $dinamicRuangan != [""]) {
            $data = $data->whereIn('apd.objectruanganfk', $dinamicRuangan);
        } else {
            $data = $data->whereIn('apd.objectruanganfk', $defaultRuangan);
        }
        $data = $data->count();

        return $data;
    }

    public function countJasaPelayanan($rangeDate, $pegawaifk)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('pelayananpasiendetail_t as ppd', 'ppd.pelayananpasien', 'pp.norec')
            ->join('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->select(DB::raw("SUM(ppd.hargajual) as total"))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ppd.komponenhargafk', $this->settingFix('kdJasaPelayanan'))
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $rangeDate);

        if ($pegawaifk) {
            $data = $data->where('ppp.objectpegawaifk', $pegawaifk);
        }

        $data = $data->first();

        return $data;
    }

    public function checkinJkn(Request $request)
    {
        date_default_timezone_set(config('app.timezone')); // set timezone
        $kdprofile = $this->kdProfile;
        $objReq = new Request();
        $objReq['kodebooking'] = $request->kodebooking;
        $objReq['waktu'] = strtotime(date('Y-m-d H:i:s')) * 1000;
        $objReq['user'] = $this->getNamaPegawai();
        $post = app('App\Http\Controllers\Bridging\AntrianOnlineCtrl')->checkIn($objReq, false);
        $post = json_decode($post->content(), true);
        return $this->respond(null, $post['metadata']['code'], $post['metadata']['message']);
    }

    public function batalJkn(Request $request)
    {
        $kdprofile = $this->kdProfile;
        $objReq = new Request();
        $objReq['kodebooking'] = $request->kodebooking;
        $objReq['keterangan'] = "Tidak jadi berobat";
        $objReq['user'] = $this->getNamaPegawai();
        $post = app('App\Http\Controllers\Bridging\AntrianOnlineCtrl')->batalAntrean($objReq, false);
        $post = json_decode($post->content(), true);
        return $this->respond(null, $post['metadata']['code'], $post['metadata']['message']);
    }

    public function CountKonsul(Request $request)
    {
        $data = DB::table('strukorder_t as so')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->where('so.kdprofile', $this->kdProfile)
            ->whereNull('apd.norec')
            ->where('so.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
            ->orderBy('so.tglorder', 'desc');

        // if (isset($request['isnotverif']) && $request['isnotverif'] != '' &&  $request['isnotverif'] == 'true') {
        //     $data = $data->wherenull('apd.norec');
        // }

        if (isset($request['idpegawai']) && $request['idpegawai'] != '') {
            $data = $data->where('pg.id', $request['idpegawai']);
        }

        $data = $data->count();

        return $this->respond($data);
    }

    public function getDetailKonsul(Request $request)
    {
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->Join('strukorder_t as so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'apd.objectruanganasalfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'so.objectpetugasfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'so.rawatbersama',
                'so.konsultasi',
                'so.lainlain',
                'ru2.namaruangan as ruanganasal',
                'ru.namaruangan as ruangantujuan',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'pg2.namalengkap',
                'pg.namalengkap as dokter',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'apd.norec as norec_apd',
                'so.keteranganlainnya',
                'pd.objectkelasfk as kelasfk_pd'
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('apd.norec', $request['norec_apd'])
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'));


        $data = $data->orderBy('so.tglorder', 'desc');
        $data = $data->get();

        return $this->respond($data);
    }

    public function saveMeninggalRJ(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewP = $request['pasien'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];

            PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                'objectstatuskeluarfk' => $r_NewPD['objectstatuskeluarfk'],
                'objectkondisipasienfk' => $r_NewPD['objectkondisipasienfk'],
                'tglpulang' => $r_NewPD['tglmeninggal'],
                'tglmeninggal' => $r_NewPD['tglmeninggal'],
                'keteranganpenyebabkematian' => $r_NewPD['keteranganpenyebabkematian'],

            ]);

            Pasien::where('nocm', $r_NewP['nocm'])->update(['tglmeninggal' => $r_NewPD['tglmeninggal'],]);
            // $d = Pasien::where('nocm', $r_NewP['nocm'])->first();
            // return $this->respond($d);

            $apd = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();
            $apd->tglkeluar = $r_NewAPD['tglkeluar'];
            $apd->save();

            $transMessage = "Sukses";
            DB::commit();

            $result = array(
                "status" => 201,
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . $e->getLine()
            );
        }

        return $this->respond($result, $result['status'], $transMessage);
    }

    public function savePulangRJ(Request $request)
    {
        $kdProfile = $this->kdProfile;

        DB::beginTransaction();

        try {

            PasienDaftar::where('norec', $request['pasiendaftar']['norec_pd'])->update([
                'objectstatuskeluarfk' => $request['pasiendaftar']['objectstatuskeluarfk'],
                'objectstatuspulangfk' => $request['pasiendaftar']['objectstatuspulangfk'],
                'objectkondisipasienfk' => $request['pasiendaftar']['objectkondisipasienfk'],
                'tglpulang' => date('Y-m-d H:m:s')
            ]);

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }


        if ($transStatus == 'true') {
            $transMessage = "Sukses ";
            DB::commit();
            // $objetoRequest = new \Illuminate\Http\Request();
            // $objetoRequest['noregistrasi'] = $request['noregistrasi'];
            // $ihs = app('App\Http\Controllers\Bridging\IHSController')->ClinicalImpression($objetoRequest, true);

            // $objetoRequest2 = new \Illuminate\Http\Request();
            // $objetoRequest2['noregistrasi'] = $pd->noregistrasi;
            // $enc = null;
            // if ($pd->ihs_diagnosis != null) {
            //     $objetoRequest2['diagnosis'] =  json_decode($pd->ihs_diagnosis);
            // }
            // $enc = app('App\Http\Controllers\Bridging\IHSController')->Encounter($objetoRequest2, true);

            $result = array(
                'status' => 201,
                'result' => null,
                'message' => $transMessage,
                // 'ClinicalImpression' => $ihs ? $ihs : null,
                // 'Encounter' => $enc ? $enc : null,
            );
        } else {
            $transMessage = "Data Gagal Disimpan";
            DB::rollBack();
            $result = array(
                'status' => 400,
                "result" => $e->getMessage() . $e->getLine(),

            );
        }
        return $this->respond($result, $result['status'], $transMessage);
    }

    public function getIntruksiCPPTDokter(Request $r)
    {
        $objectdokter = $r['dpjp'];
        // if(!isset($objectdokter)) return $this->respond([]);

        $data = DB::table('intruksi_cppt_t as ic')
            ->select(
                'ic.norec',
                'ru.namaruangan',
                'ppa.namalengkap as namaPPA',
                'ic.isconfirm',
                'ic.norec_cpptdetail',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien'
            )
            ->leftJoin('pegawai_m as ppa', 'ppa.id', 'ic.objectppafk')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'ic.objectruanganfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'ic.norecpd')
            ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->where('ic.statusenabled', true)
            ->where('ic.isconfirm', false);
        if (isset($objectdokter)) {
            $data = $data->where('ic.objectpegawaifk', $objectdokter);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] !== '') {
            $data = $data->where('ic.norecpd', $r['norec_pd']);
        }
        if (isset($r['norec_apd'])) {
            $data = $data->where('ic.norecapd', $r['norec_apd']);
        }
        $data = $data->get();

        if (count($data) == 0) {
            return $this->respond([]);
        }
        // $details = [];
        $newArr = [];
        $norec_cpptdetail = array_column($data->toArray(), 'norec_cpptdetail');
        // return $norec_cpptdetail;
        $detail = DB::connection('mongodb')
            ->collection('CPPTDetail')
            ->whereIn('uuid', $norec_cpptdetail)
            ->select(["S", "O", "A", "P", "intruksiPPA", "created_at", "uuid", "dpjpUtama"])
            ->get()
            ->keyBy('uuid')
            ->toArray();

        // return $detail;
        // return $detail['0d5424c0-356e-47f5-8654-1cb282ee8478'];
        foreach ($data as $key => $master) {
            $newArr[$key] = $master;
            $newArr[$key]->details = isset($detail[$master->norec_cpptdetail]) ? $detail[$master->norec_cpptdetail] : null;
        }
        // return $newArr;
        return $this->respond($newArr);
    }

    public function verifIntruksiCPPT(Request $r)
    {
        if (!isset($r['norec']))
            return $this->respond([
                "status" => 500,
                "message" => "Terjadi kesalahan"
            ], 500);
        DB::table('intruksi_cppt_t')
            ->where('norec', $r['norec'])
            ->update(['isconfirm' => true]);

        $detail = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('uuid', $r['cpptdetail'])
            ->update(['isverif' => true]);

        return $this->respond([
            "status" => 200,
            "message" => "Berhasil diverifikasi"
        ], 200);
    }

    public function getEvaluasiFisio(Request $req)
    {
        $data = DB::table('evaluasi_pasien_t as ept')
            ->select('ept.*', 'pg.namalengkap as namadokter')
            ->leftJoin('pegawai_m as pg', 'ept.objectpegawaifk', 'pg.id')
            ->whereDate('ept.tanggal', '>=', date('Y-m-d'))
            ->where('ept.objectpegawaifk', $req['dokter'])
            ->where('ept.statusenabled', true)
            ->orderBy('ept.tanggal', 'desc')
            ->get();

        $newArray = [];
        $dataPasien = [];
        foreach ($data as $eval) {
            // return json_decode($eval->pasienfk, true);
            $pasien = DB::table('pasien_m')
                ->select('id', 'namapasien', 'nocm')
                ->whereIn('id', json_decode($eval->pasienfk, true))
                ->get();
            // return $pasien;

            foreach ($pasien as $key => $pas) {
                $newArray[] = [
                    'norec' => $eval->norec,
                    'tanggal' => $eval->tanggal,
                    'objectpegawaifk' => $eval->objectpegawaifk,
                    'intruksi' => $eval->intruksi,
                    'namadokter' => $eval->namadokter,
                    'nocm' => $pas->id,
                    'namapasien' => $pas->namapasien,
                    'norm' => $pas->nocm
                ];
            }
        }
        return $this->respond($newArray);
    }

    public function saveEvaluasiFisio(Request $req)
    {
        try {
            DB::beginTransaction();
            $data = new EvaluasiPasien();
            $data->norec = $data->generateNewId();
            $data->pasienfk = $req['pasienfk'];
            $data->objectpegawaifk = $req['objectpegawaifk'];
            $data->tanggal = $req['tanggal'];
            $data->intruksi = $req['intruksi'];
            $data->created_at = date('Y-m-d H:i:s');
            $data->updated_at = date('Y-m-d H:i:s');
            $data->save();
            DB::commit();

            return $this->respond([
                "code" => 200,
                "message" => "success",
                "result" => $data,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respond([
                "code" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
