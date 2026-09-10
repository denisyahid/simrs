<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use App\Traits\Valet;
use App\Models\Master\Kamar;
use Illuminate\Http\Request;
use App\Models\Master\Ruangan;
use App\Models\Master\Departemen;
use App\Models\Master\TempatTidur;
use Illuminate\Support\Facades\DB;
use App\Models\Master\JadwalDokter;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\StrukResep;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\SuratKeterangan;
use App\Models\Transaksi\StokProdukDetail;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Http\Resources\Dashboard\TotalPasien\TotalPasienResource;

class DashboardRICtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function getRuanganRanap()
    {
        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('mlur.statusenabled', true)
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        return $ruanganTersedia;
    }

    // DROPDOWN RUANGAN
    public function getDropdown()
    {
        $listRuangan = $this->getRuanganRanap();

        $set = explode(',', $this->settingFix('idDepRawatInap'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->whereIn('id', $listRuangan)->get();

        return $this->respond($res);
    }

    // PASIEN PER RUANGAN
    public function getRIPasien(Request $r)
    {
        $ruanganTersedia = $this->getRuanganRanap();
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->join('kamar_m as kmr', 'kmr.id', 'apd.objectkamarfk')
            ->leftJoin('kelas_m as klsr', 'klsr.id', 'apd.kelasrawatfk')
            ->join('kelas_m as kls', 'kls.id', 'apd.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->leftjoin('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftJoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftJoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            // ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftJoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftJoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->select(
                'pd.isnaikkelas',
                'pd.iskelastitip',
                'pd.noregistrasi',
                'ru.id',
                'kp.kelompokpasien',
                'ru.statusenabled',
                'ru.namaruangan',
                'kls.namakelas',
                'klsr.namakelas as namakelasr',
                'kmr.namakamar',
                'pd.norec as norec_pd',
                'pd.objectruanganasalfk',
                'pd.nocmfk',
                'pd.tglpulang',
                'ru.objectdepartemenfk',
                'tt.reportdisplay',
                'pd.objectruanganlastfk',
                'pd.objectpegawaifk',
                // 'emr.tglberakhir',
                // 'emr.tglmulai',
                // 'emr.objectkelompokuserfk',
                // 'emr.pegawaipemohonfk',
                'pd.objectpegawairawatbersamafk',
                'pd.objectpegawairawatbersamadinamisfk',
                'pd.noregistrasi',
                'pg.namalengkap',
                DB::raw('pg2.namalengkap as nama'),
                'ps.namapasien',
                'ps.nocm',
                'ps.alamatrmh',
                'ps.nobpjs',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.noidentitas',
                'kbs.name as kebangsaan',
                'apd.norec as norec_apd',
                'pd.tglregistrasi',
                DB::raw("case when apd.israwatgabung is true then 'Rawat Gabung' else '' end as rawatgabung"),
                DB::raw("
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER / (24 * 3600)) || ' hari ' ||
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER % (24 * 3600) / 3600) || ' jam ' ||
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER % 3600 / 60) || ' menit' AS selisihWaktu
                "),
                DB::raw("
                    (
                        SELECT STRING_AGG(pg3.namalengkap, ', ')
                        FROM (
                            SELECT unnest(string_to_array(pd.objectpegawairawatbersamadinamisfk, ','))::int AS dokter_id
                        ) AS dokter_ids
                        JOIN pegawai_m pg3 ON pg3.id = dokter_ids.dokter_id
                    ) AS dokter_nama
                ")

            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereNull('apd.tglkeluar')
            ->where('apd.kdprofile', $this->kdProfile)
            ->whereNull('pd.tglpulang')
            ->orderBy('pd.noregistrasi', 'DESC')
            ->orderBy('pd.tglregistrasi', 'DESC');

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $data = $data->where(function ($query) use ($r) {
                $query->where('pd.objectpegawaifk', $r['idpegawai'])
                    ->orWhere('pd.objectpegawairawatbersamafk', $r['idpegawai'])
                    ->orWhere('pd.objectpegawairawatbersamadinamisfk', 'LIKE', '%'.$r['idpegawai'].'%');
            });
        }

        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
        }

        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }

        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }

        $data = $data->distinct('pd.noregistrasi')->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);
        return $this->respond($data);
    }

    // PASIEN SUDAH PULANG
    public function getRIPasienTotal(Request $r)
    {
        $ruanganTersedia = $this->getRuanganRanap();

        $data = DB::table('pasiendaftar_t as pd')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
            ->select(
                DB::raw("
                DISTINCT on (apd.noregistrasifk, pd.tglregistrasi)
                ru.id,
                ps.tgllahir,
                ps.nohp,
                pg.namalengkap as dokter,
                pd.isresumemedis,
                asru.asalrujukan,
                kp.kelompokpasien,
                kl.namakelas,
                alm.alamatlengkap,
                jk.jeniskelamin,
                pas.nosep,
                pd.objectstatuspulangfk,
                sp.statuspulang,
                ru.statusenabled,
                ru.namaruangan,
                pd.objectruanganasalfk,
                emr.tglberakhir,
                emr.tglmulai,
                emr.objectkelompokuserfk,
                emr.pegawaipemohonfk,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.norec as norec_pd,
                apd.norec as norec_apd,
                pd.nocmfk,
                ps.nocm,
                pd.noregistrasi,
                pg.namalengkap,
                pg.id as dokterfk,
                ps.namapasien,
                ps.nobpjs,
                ps.noidentitas,
                ps.tglmeninggal,
                pd.tglregistrasi,
                pd.tglclosing,
                pd.tglpulang
                ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->whereNotNull('pd.tglpulang');
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("pd.tglpulang::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("pd.tglpulang::date"), '<=', $r->sampai);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
        } else {
            $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
        }

        if (isset($r['qsearch']) && $r['qsearch'] != '') {
            $searchTerm = '%' . $r['qsearch'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where(DB::raw("REPLACE(namapasien, ' ', '')"), 'like', '%' . str_replace(' ', '', $searchTerm) . '%')
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $data = $data->where('pd.objectpegawaifk', '=', $r['idpegawai']);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=', $r['nocm']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }

        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        foreach ($data as $d) {
            $d->lamarawat = $this->getAge($d->tglregistrasi, $d->tglpulang ? $d->tglpulang : date('Y-m-d H:i:s'));
            $d->umur = $this->getAge($d->tgllahir, date('Y-m-d H:i:s'));
        }

        $resourse = TotalPasienResource::collection(new Collection($data));
        $res['data'] = $resourse;
        return $this->respond($data);
    }

    public function getDetailRI(Request $r)
    {

        $ruanganTersedia = $this->getRuanganRanap();

        $now = $this->hari_ini(date('Y-m-d'));
        $jadwal = DB::table('jadwaldokter_m as jd')
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
            ->where('pg.statusenabled', true)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'));

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $jadwal = $jadwal->whereIn('ru.id', explode(',', $r['ruanganid']));
        } else {
            $jadwal = $jadwal->whereIn('ru.id', $ruanganTersedia);
        }

        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $jadwal = $jadwal->where('pg.namalengkap', 'ilike', '%' . $r['namadokter'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $jadwal = $jadwal->limit($r['limit']);
        }
        $jadwal->orderBy('pg.namalengkap');
        $jadwal = $jadwal->get();
        foreach ($jadwal as $d) {
            $d->hari = $now;
        }

        $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
        $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

        $data = DB::table('tempattidur_m as tt')
            ->join('kamar_m as kmr', 'kmr.id', 'tt.objectkamarfk')
            ->join('ruangan_m as ru', 'ru.id', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', 'kmr.objectkelasfk')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'kl.namakelas',
                'kmr.namakamar',
                DB::raw("
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong]  then  1 else 0 end ) as isi,
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedIsi] then  1 else 0 end ) as kosong,
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong] then  1 else 0 end ) +
                    sum(case when tt.objectstatusbedfk =$SET[idStatusBedIsi] then  1 else 0 end ) as total
                    ")
            )
            ->where('tt.kdprofile', $this->kdProfile)
            ->where('kmr.statusenabled', true)
            ->where('tt.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'));
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('ru.id', explode(',', $r['ruanganfk']));
        } else {
            // $data = $data->whereIn('ru.id', $ruanganTersedia);
        }
        $data = $data->groupBy('ru.id', 'ru.namaruangan', 'kl.namakelas', 'kmr.namakamar');
        $data = $data->get();

        $bedIsi = 0;
        $bedKosong = 0;
        foreach ($data as $d) {
            $bedKosong = $bedKosong + $d->kosong;
            $bedIsi = $bedIsi + $d->isi;
        }
        // $data  =  DB::select(DB::raw("
        //     select
        //     ru.id,
        //     ru.namaruangan,
        //     kl.namakelas,
        //     kmr.namakamar,
        //     sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong]  then  1 else 0 end )as isi,
        //     sum(case when tt.objectstatusbedfk =$SET[idStatusBedIsi] then  1 else 0 end )as kosong,
        //     sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong] then  1 else 0 end ) +
        //     sum(case when tt.objectstatusbedfk =$SET[idStatusBedIsi] then  1 else 0 end ) as total
        //     from tempattidur_m as tt
        //     join kamar_m as kmr on kmr.id =tt.objectkamarfk
        //     join ruangan_m as ru on ru.id = kmr.objectruanganfk
        //     join kelas_m as kl on kl.id = kmr.objectkelasfk
        //     where tt.kdprofile =  $this->kdProfile
        //     and kmr.statusenabled =TRUE
        //     and tt.statusenabled=TRUE
        //     and ru.statusenabled =TRUE
        //     and ru.objectdepartemenfk in ( $dep)
        //     $ruang
        //     GROUP BY ru.id,ru.namaruangan,kl.namakelas,kmr.namakamar"));

        // $bedIsi = 0;
        // $bedKosong = 0;
        // foreach ($data as $d) {
        //     $bedKosong = $bedKosong + $d->kosong;
        //     $bedIsi = $bedIsi + $d->isi;
        // }

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
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $produk = $produk->where('ru.id', '=', $r['ruanganid']);
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

        $PasienPulang = DB::table('pasiendaftar_t as pd')
            ->where('pd.kdprofile', $this->kdProfile)
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->where('pd.statusenabled', true)
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))

            ->whereNotNull('pd.tglpulang');
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $PasienPulang = $PasienPulang->where('pd.objectruanganlastfk', '=', $r['ruanganid']);
        }
        if (isset($r['tgl']) && $r['tgl'] != '') {
            $PasienPulang = $PasienPulang->whereRaw("to_char(pd.tglpulang,'yyyy-MM-dd') = '$r[tgl]'");
        }
        $PasienPulang = $PasienPulang->count();

        $PasienRawat = PasienDaftar::where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('tglpulang');
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $PasienRawat = $PasienRawat->where('objectruanganlastfk', '=', $r['ruanganid']);
        }
        $PasienRawat = $PasienRawat->count();

        $jumlahDokter = DB::table('jadwaldokter_m as jd')
            ->where('jd.kdprofile', $this->kdProfile)
            ->join('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->where('jd.statusenabled', true)
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')));

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $jumlahDokter = $jumlahDokter->where('jd.objectruanganfk', '=', $r['ruanganid']);
        }
        $jumlahDokter = $jumlahDokter->count();


        $res['jadwal'] = $jadwal;
        $res['produk'] = $produk;
        $res['data'] = $data;
        $res['totalPulang'] = $PasienPulang;
        $res['totalRawat'] = $PasienRawat;
        $res['jumlahDokter'] = $jumlahDokter;
        // $res['tempatTidur'] = $tempatTidur;
        $res['totalBedIsi'] = $bedIsi;
        $res['totalBedKosong'] = $bedKosong;
        return $this->respond($res);
    }

    public function Ruangandropdown(Request $r)
    {
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }

    public function BatalRawatInap(Request $request)
    {
        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];

            $pd = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
                ->join('ruangan_m as rutu', 'rutu.id', '=', 'pd.objectruanganlastfk')
                ->select(
                    'ru.objectdepartemenfk',
                    'pd.norec',
                    'pd.objectruanganasalfk',
                    'pd.objectruanganlastfk',
                    'rutu.namaruangan as ruanganTerakhir',
                    'ru.namaruangan as ruanganSebelumnya',
                    'pd.objectkelasfk',
                )
                ->where('pd.norec', $r_NewPD['norec_pd'])
                ->where('pd.kdprofile', $this->kdProfile)
                ->first();

            $apd = DB::table('antrianpasiendiperiksa_t as apd')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->select(
                    'ru.objectdepartemenfk',
                    'apd.norec',
                    'apd.objectruanganfk',
                    'apd.objectruanganasalfk',
                    'apd.objectkelasfk',
                    'apd.nobed',
                    'pd.norec',
                    'apd.objectkamarfk'
                )
                ->where('apd.noregistrasifk', $r_NewPD['norec_pd'])
                ->where('apd.kdprofile', $this->kdProfile)
                ->first();

            $cekPP = PelayananPasien::where('noregistrasifk', $r_NewAPD['norec_apd'])->where('statusenabled', true)->first();
            $cekSR = StrukResep::where('pasienfk', $r_NewAPD['norec_apd'])->where('statusenabled', true)->first();

            if (!empty($cekPP)) {
                DB::rollBack();
                $transMessage = 'Pasien sudah Mendapatkan Pelayanan : '
                    . 'Pada : ' . $cekPP->tglpelayanan;
                $result = array("status" => 400, "result" => $cekPP);
                return $this->respond($result['result'], $result['status'], $transMessage);
            } elseif (!empty($cekSR)) {
                $message = 'Pasien sudah Mendapatkan Resep!';
                return $this->respond($message);
            } else {
                PasienDaftar::where('norec', $r_NewPD['norec_pd'])
                    ->where('kdprofile', $kdProfile)
                    ->update([
                        'statusenabled' => true,
                        'objectruanganlastfk' => $pd->objectruanganasalfk,
                        'objectruanganasalfk' => $pd->objectruanganasalfk,
                        'tglpulang' => date('Y-m-d H:i:s'),
                    ]);

                $spd = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();

                $SET = $this->settingFix('idStatusBedKosong');
                try {
                    TempatTidur::where('id', $spd->nobed)
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->lockForUpdate()
                        ->update(['objectstatusbedfk' => $SET]);

                    DB::commit();

                    $message = 'Berhasil Batal Rawat Inap';
                    Log::info('Tempat tidur berhasil diupdate menjadi kosong');
                } catch (\Exception $e) {
                    DB::rollBack();

                    $message = 'Gagal Batal Rawat Inap';
                    Log::error('Error pada update status tempat tidur: ' . $e->getMessage());
                }

                $this->historyBED([
                    "tempattidurfk" => $spd->nobed,
                    "statusbedfk" => $SET,
                    "ruanganfk" => $spd->objectruanganfk,
                    "kamarfk" => $spd->objectkamarfk,
                ]);

                AntrianPasienDiperiksa::where('noregistrasifk', $r_NewPD['norec_pd'])
                    ->whereNotNull('nobed')
                    ->where('kdprofile', $kdProfile)
                    ->update(['statusenabled' => false]);

                $message = 'Berhasil Batal Rawat Inap';
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $message,
                "result" => 'Berhasil Batal Rawat Inap'
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result" => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function getIntruksiCPPTDokterRanap(Request $r)
    {
        $objectdokter = $r['dpjp'];
        // if(!isset($objectdokter)) return $this->respond([]);

        $data = DB::table('intruksi_cppt_t as ic')
            ->select(
                'ic.norec',
                'ic.note as intruksi',
                'ru.namaruangan',
                'ppa.namalengkap as namaPPA',
                'dokter.namalengkap as namaDokter',
                'ic.isconfirm',
                'ic.norec_cpptdetail',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien'
            )
            ->leftJoin('pegawai_m as ppa', 'ppa.id', 'ic.objectppafk')
            ->leftJoin('pegawai_m as dokter', 'dokter.id', 'ic.objectpegawaifk')
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

        if (isset($r['raber']) && $r['raber'] == true) {
            $data = $data->where('ic.israber', true);
        } else {
            $data = $data->where('ic.israber', false);
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
            ->select(["S", "O", "A", "P", "created_at", "uuid"])
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
    public function getIntruksiCPPTDokterRanapNew(Request $r)
    {
        $objectdokter = $r['dpjp'];
        // if(!isset($objectdokter)) return $this->respond([]);

        $data = DB::table('intruksi_cppt_t as ic')
            ->select(
                'ic.norec',
                'ic.note as intruksi',
                'ru.namaruangan',
                'ppa.namalengkap as namaPPA',
                'dokter.namalengkap as namaDokter',
                'ic.isconfirm',
                'ic.norec_cpptdetail',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien'
            )
            ->leftJoin('pegawai_m as ppa', 'ppa.id', 'ic.objectppafk')
            ->leftJoin('pegawai_m as dokter', 'dokter.id', 'ic.objectpegawaifk')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'ic.objectruanganfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'ic.norecpd')
            ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->where('ic.statusenabled', true)
            ->where('ic.isconfirm', true)
            ->where('pd.norec', $r['norec_pd']);

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
            ->where('noticeDokter', 'Notice')
            ->whereIn('uuid', $norec_cpptdetail)
            ->select(["S", "O", "A", "P", "created_at", "uuid"])
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

    public function SaveSuratKeteranganDokter(Request $request)
    {
        DB::beginTransaction();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $this->kdProfile);
        $jenisSurat = DB::table('jenissurat_m')
            ->select('*')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('id', $kdJenisSurat)
            ->first();
        $kodeSurat = '';

        if (!empty($kdJenisSurat)) {
            $kodeSurat = $jenisSurat->kodeexternal;
        }

        try {
            if ($request['norec'] == '') {
                $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4, '', $this->kdProfile);
                $SKD = new SuratKeterangan();
                $norecNew = $SKD->generateNewId();
                $SKD->kdprofile = $this->kdProfile;
                $SKD->norec = $norecNew;
                $SKD->nosurat = $genSurat['nosurat'];
                $SKD->nosint = $genSurat['noint'];
                $SKD->statusenabled = true;
                $SKD->jenissuratfk = $kdJenisSurat;
            } else {
                $SKD = SuratKeterangan::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->first();
            }

            $SKD->pegawaifk = $this->getPegawaiId();
            $SKD->tglsurat = date('Y-m-d H:i:s');
            $SKD->pasiendaftarfk = $request['norec_pd'];
            $SKD->dokterfk = $request['dokterfk'];
            $SKD->tinggibadan = $request['tinggibadan'];
            $SKD->beratbadan = $request['beratbadan'];
            $SKD->tekanandarah = $request['tekanandarah'];
            $SKD->denyutjantung = $request['denyutjantung'];
            $SKD->save();
            $norecSKD = $SKD->norec;

            DB::commit();
            $response = [
                "status" => 200,
                "message" => "Simpan Berhasil",
                "data" => $norecSKD
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "status" => 400,
                "message" => "Simpan Gagal",
                "data" => $norecSKD
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function SaveSuratKeteranganSakit(Request $request)
    {

        DB::beginTransaction();

        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSakit', $this->kdProfile);

        $jenisSurat = DB::table('jenissurat_m')
            ->select('*')
            ->where('kdprofile', $this->kdProfile)
            ->where('id', $kdJenisSurat)
            ->first();
        if (!empty($jenisSurat)) {
            $kodeSurat = $jenisSurat->kodeexternal;
        }

        try {

            if ($request['norec'] == '') {
                $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4, '', $this->kdProfile);
                $SKS = new SuratKeterangan();
                $norecNew = $SKS->generateNewId();
                $SKS->kdprofile = $this->kdProfile;
                $SKS->norec = $norecNew;
                $SKS->nosurat = $genSurat['nosurat'];
                $SKS->nosint = $genSurat['noint'];
                $SKS->statusenabled = true;
                $SKS->jenissuratfk = $kdJenisSurat;
            } else {
                $SKS = SuratKeterangan::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->first();
            }
            $SKS->keterangan = $request['keterangan'];
            $SKS->diagnosa = $request['diagnosa'];
            $SKS->indikasi = $request['indikasi'];
            $SKS->hasilpemeriksaan = $request['hasilpemeriksaan'];
            $SKS->tglkontrol = $request['tglkontrol'];
            $SKS->tglawal = $request['tglijinawal'];
            $SKS->tglakhir = $request['tglijinakhir'];
            $SKS->pegawaifk = $this->getPegawaiId();
            $SKS->tglsurat = date('Y-m-d H:i:s');
            $SKS->pasiendaftarfk = $request['norec_pd'];
            $SKS->dokterfk = $request['dokterfk'];
            $SKS->save();
            $norecSKS = $SKS->norec;
            DB::commit();

            $respond = [
                "status" => 200,
                "message" => "Simpan Berhasil",
                "data" => $norecSKS
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

    public function getDataSuratKeterangan(Request $request)
    {

        $kdJenisSurat = (int) $this->settingFix($request['jenissurat'], $this->kdProfile);

        $data = DB::table('suratketerangan_t as sk')
            ->join('pasiendaftar_t as pd', function ($join) {
                $join->on('pd.norec', '=', 'sk.pasiendaftarfk')->on('pd.kdprofile', '=', 'sk.kdprofile');
            })
            ->join('pasien_m as pm', function ($join) {
                $join->on('pm.id', '=', 'pd.nocmfk')->on('pm.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('alamat_m as alm', function ($join) {
                $join->on('alm.nocmfk', '=', 'pm.id')->on('alm.kdprofile', '=', 'pm.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($join) {
                $join->on('pg.id', '=', 'sk.dokterfk')->on('pg.kdprofile', '=', 'sk.kdprofile');
            })
            ->leftjoin('jeniskelamin_m as jk', function ($join) {
                $join->on('jk.id', '=', 'pm.objectjeniskelaminfk')->on('jk.kdprofile', '=', 'pm.kdprofile');
            })
            ->select(DB::raw("sk.*,pm.nocm,pd.noregistrasi,pm.namapasien,pm.tgllahir,pm.tempatlahir,jk.jeniskelamin,alm.alamatlengkap,sk.diagnosa"))
            ->where('sk.kdprofile', $this->kdProfile)
            ->where('sk.jenissuratfk', $kdJenisSurat)
            ->where('sk.pasiendaftarfk', $request['norec_pd'])
            ->first();

        return $this->respond($data);
    }
    public function getPendapatan(Request $request)
    {
        $datas = [
            'jumlahPasien' => $this->countPasien($request['idpegawai']),
            'jumlahTindakan' => $this->countPasienTindakan($request['idpegawai']),
            'pendapatanJasa' => $this->countJasaPelayanan($request['idpegawai'])
        ];
        return $this->respond($datas);
    }

    public function countPasien($pegawaifk)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->distinct('pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->where('apd.statusenabled', true)
            ->where('apd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->whereNull('pd.tglpulang');
        if ($pegawaifk) {
            $data = $data->where('pd.objectpegawaifk', $pegawaifk);
        }
        $data = $data->count();
        return $data;
    }

    public function countPasienTindakan($pegawaifk)
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
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->where('ppd.komponenhargafk', $this->settingFix('kdJasaPelayanan'))
            ->whereNull('apd.tglkeluar')
            ->whereNull('pd.tglpulang');
        if ($pegawaifk) {
            $data = $data->where('ppp.objectpegawaifk', $pegawaifk);
        }
        $data = $data->count();

        return $data;
    }

    public function countJasaPelayanan($pegawaifk)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', 'ru.id')
            ->join('pelayananpasiendetail_t as ppd', 'ppd.pelayananpasien', 'pp.norec')
            ->join('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            // ->join('produk_m as pr','pr.id','ppd.produkfk')
            // ->select('ppd.norec','ppd.hargajual','pr.namaproduk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereNull('apd.tglkeluar')
            ->whereNull('pd.tglpulang')
            ->where('ppd.komponenhargafk', $this->settingFix('kdJasaPelayanan'))
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')));

        if ($pegawaifk) {
            $data = $data->where('ppp.objectpegawaifk', $pegawaifk);
        }

        $data = $data->sum('ppd.hargajual');

        return $data;
    }

    public function getRiwayatMutasiRanap(Request $r)
    {

        $dateBetween = [$r->tglAwal, $r->tglAkhir];
        $data = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as rulast', 'rulast.id', '=', 'pd.objectruanganasalfk')
            ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->select(
                DB::raw("
                ru.statusenabled,
                kls.namakelas,
                kmr.namakamar,
                apd.norec as norec_apd,
                pd.norec as norec_pd,
                pd.nocmfk,
                ps.nocm,
                ds.namadesakelurahan,
                km.namakecamatan,
                apd.tglmasuk,
                apd.tglkeluar,
                kbp.namakotakabupaten,
                ps.nobpjs,
                ps.alamatrmh,
                ru.namaruangan as ruanganSekarang,
                rulast.namaruangan as ruanganAsal,
                ps.noidentitas,
                ru.objectdepartemenfk,
                tt.reportdisplay,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.objectpegawairawatbersamafk,
                pd.noregistrasi,
                pg.namalengkap,
                pd.objectruanganasalfk,
                pg2.namalengkap as nama,
                ps.namapasien,
                CAST(pd.tglregistrasi AS DATE)
                ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereRaw("CAST(pd.objectruanganlastfk AS INT) != CAST(pd.objectruanganasalfk AS INT)")
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('rulast.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(apd.tglmasuk AS DATE)"), $dateBetween)
            ->whereNull('apd.tglkeluar')
            ->where('apd.kdprofile', $this->kdProfile)
            ->whereNull('pd.tglpulang');

        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('pd.objectruanganasalfk', explode(',', $r['ruanganfk']));
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTermMutasi = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTermMutasi) {
                $query->where('pd.noregistrasi', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.nocm', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.namapasien', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTermMutasi);
            });
        }

        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->distinct('apd.noregistrasifk')->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);
        foreach ($data as $d) {
            $d->lamarawat = $this->getAge($d->tglmasuk, $d->tglkeluar ? $d->tglkeluar : date('Y-m-d H:i:s'));
        }

        return $this->respond($data);
    }
}
