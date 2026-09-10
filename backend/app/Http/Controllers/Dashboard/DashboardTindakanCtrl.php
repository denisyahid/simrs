<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelas;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DashboardTindakanCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getTindakan(Request $r)
    {
        $dataProduk = DB::table('produk_m as pr')
            ->join('harganettoprodukbykelas_m as hpk', 'hpk.objectprodukfk', '=', 'pr.id')
            ->join('kelas_m as ks', 'hpk.objectkelasfk', '=', 'ks.id')
            ->select(
                'pr.id',
                'ks.id as kelasid',
                'pr.namaproduk',
                'ks.namakelas',
            )
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('hpk.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->distinct();

        if ($r['filterTindakan']) {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ILIKE', '%' . $r['filterTindakan'] . '%');
        }
        $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        $dataProduk = $dataProduk->limit(20);
        $dataProduk = $dataProduk->get();
        $data = [];
        foreach ($dataProduk as $item) {
            $data[] = [
                'id' => $item->id,
                'nama' => $item->namaproduk . ' - ' . $item->namakelas,
                'kelasid' => $item->kelasid
            ];
        }

        return $this->respond($data);
    }

    public function komponenHarga(Request $r)
    {
        $data  = DB::table('harganettoprodukbykelas_m as hpk')
            ->join('kelas_m as ks', 'hpk.objectkelasfk', '=', 'ks.id')
            ->join('produk_m as pr', 'hpk.objectprodukfk', '=', 'pr.id')
            ->join('jenispelayanan_m as jp', 'hpk.objectjenispelayananfk', '=', 'jp.id')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'hpk.objectpenjaminfk')
            ->leftjoin('jenistarif_m as jt', 'jt.id', '=', 'hpk.objectjenistariffk')
            ->select(
                'hpk.*',
                'ks.namakelas',
                'pr.namaproduk',
                'jp.jenispelayanan',
                'rk.namarekanan',
                'jt.jenistarif',
            )
            ->where('hpk.statusenabled', true)
            ->where('hpk.kdprofile', $this->kdProfile);

        if (isset($r['tindakanid']) && $r['tindakanid'] != '') {
            $data = $data->where('pr.id', '=',  $r['tindakanid']);
        }
        if (isset($r['kelasid']) && $r['kelasid'] != '') {
            $data = $data->where('ks.id', '=',  $r['kelasid']);
        }

        $data = $data->first();

        $detail  = DB::table('harganettoprodukbykelasd_m as hpk')
            ->join('kelas_m as ks', 'hpk.objectkelasfk', '=', 'ks.id')
            ->join('produk_m as pr', 'hpk.objectprodukfk', '=', 'pr.id')
            ->join('jenispelayanan_m as jp', 'hpk.objectjenispelayananfk', '=', 'jp.id')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'hpk.objectpenjaminfk')
            ->leftjoin('jenistarif_m as jt', 'jt.id', '=', 'hpk.objectjenistariffk')
            ->join('komponenharga_m as kom', 'kom.id', '=', 'hpk.objectkomponenhargafk')
            ->select(
                'hpk.*',

                'kom.komponenharga',
            )

            ->where('hpk.statusenabled', true)
            ->where('hpk.kdprofile', $this->kdProfile)
            ->where('hpk.objectjenistariffk', $data->objectjenistariffk)
            ->where('hpk.objectkelasfk', $data->objectkelasfk)
            ->where('hpk.objectjenispelayananfk', $data->objectjenispelayananfk)
            ->where('hpk.objectprodukfk', $data->objectprodukfk);

        if (isset($r['tindakanid']) && $r['tindakanid'] != '') {
            $detail = $detail->where('pr.id', '=',  $r['tindakanid']);
        }
        if (isset($r['kelasid']) && $r['kelasid'] != '') {
            $detail = $detail->where('ks.id', '=',  $r['kelasid']);
        }

        $detail = $detail->get();

        $res['head'] = $data;
        $res['detail'] = $detail;
        return $this->respond($res);
    }

    public function countTindakan(Request $r)
    {
        $tglBetween = [$r->dari, $r->sampai];
        $data =  DB::table('pasiendaftar_t as pd')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasi', 'pd.noregistrasi')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pp.kelasfk')
            ->select('pd.noregistrasi', 'ps.namapasien', DB::raw("
                sum(pp.jumlah) as jumlah,
                   sum ((
                        (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                        * pp.jumlah)
                    + (case when pp.jasa is not null then pp.jasa else 0 end))
                    as total
             "))
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $tglBetween)
            ->whereNull('pp.strukresepfk')
            ->groupBy('pd.noregistrasi', 'ps.namapasien');

        if (isset($r['tindakanid']) && $r['tindakanid'] != '') {
            $data = $data->where('pr.id', '=',  $r['tindakanid']);
        }
        if (isset($r['kelasid']) && $r['kelasid'] != '') {
            $data = $data->where('kl.id', '=',  $r['kelasid']);
        }
        $data = $data->get();
        $totalpasien =  count($data);

        $pegawai = DB::table('pelayananpasienpetugas_t as ppp')
            ->join('pelayananpasien_t as pp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->join('pasiendaftar_t as pd', 'pd.noregistrasi', 'ppp.noregistrasi')
            ->leftjoin('ruangan_m as ru', 'ru.id','=','pd.objectruanganlastfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'ppp.objectpegawaifk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pp.kelasfk')
            ->select('pg.namalengkap', 'ru.namaruangan', DB::raw("
            sum(pp.jumlah) as jumlah
         "))
            ->where('ppp.statusenabled', true)
            ->where('ppp.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $tglBetween)
            ->groupBy('pg.namalengkap', 'ru.namaruangan');

        if (isset($r['tindakanid']) && $r['tindakanid'] != '') {
            $pegawai = $pegawai->where('pr.id', '=',  $r['tindakanid']);
        }
        if (isset($r['kelasid']) && $r['kelasid'] != '') {
            $pegawai = $pegawai->where('kl.id', '=',  $r['kelasid']);
        }

        $pegawai = $pegawai->get();
        $totalpegawai =  count($data);

        $grafik = DB::table('pelayananpasien_t as pp')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
        ->join('ruangan_m as ru', 'ru.id','apd.objectruanganfk')
        ->select('ru.namaruangan', DB::raw("sum(pp.jumlah) as jumlah") )
        ->where('pp.statusenabled', true)
        ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $tglBetween)
        ->whereNull('pp.strukresepfk')
        ->groupBy('ru.namaruangan');

        if (isset($r['tindakanid']) && $r['tindakanid'] != '') {
            $grafik = $grafik->where('pp.produkfk', '=',  $r['tindakanid']);
        }
        if (isset($r['kelasid']) && $r['kelasid'] != '') {
            $grafik = $grafik->where('apd.objectkelasfk', '=',  $r['kelasid']);
        }

        $grafik =$grafik->get();

        $totalLayanan = [];
        $seriesLayanan = [];

        foreach ($grafik as $dat) {
            $totalLayanan[] = $dat->jumlah;
            $seriesLayanan[] = strtolower($dat->namaruangan);
        }

        $res['chartLO']['series'] = $totalLayanan;
        $res['chartLO']['labels'] = $seriesLayanan;

        $res['jumlahPasien'] = $totalpasien;
        $res['jumlahDokter'] = $totalpegawai;
        $res['detailPasien'] = $data;
        $res['detailDokter'] = $pegawai;

        return $this->respond($res);
    }

    public function getNoorder(Request $r){
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->JOIN('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftJOIN('order_lab as lis', function ($join) {
                $join->on('lis.no_lab', '=', 'so.noorder');
                $join->on(DB::raw('lis.kode_test::int'), '=', 'pp.produkfk');
            })
            ->select(
                // 'pp.norec',
                // 'apd.norec as norec_apd',
                // 'prd.namaproduk',
                // 'kls.namakelas',
                // 'pp.tglpelayanan',
                // 'ru.namaruangan',
                // 'pp.strukresepfk',
                // 'pp.jumlah',
                // 'pp.hargasatuan',
                // 'pg.namalengkap',
                // 'apd.norec as norec_apd',
                'so.noorder',
            //     'so.keteranganlainnya',
            //     'pp.strukfk',
            //     'pp.produkfk',
            //     'ru.objectdepartemenfk',
            //     'lis.no_lab as idbridging',
            //     'so.namafile',
            //     'so.norec as norec_so',
            //     DB::raw("
            //     case when pp.jasa is not null then pp.jasa else 0 end jasa,
            //     case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
            //     (
            //         (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
            //          * pp.jumlah)
            //     + (case when pp.jasa is not null then pp.jasa else 0 end)
            //      as total,
            //     to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
            //     case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
            //    ")
            )
            ->where('pp.statusenabled', true)
            ->whereNull('pp.strukresepfk')
            ->whereNotNull('so.noorder')
            ->where('pp.kdprofile', $this->kdProfile)
            
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderByDesc('so.created_at');
            
            if(isset($r['noorderrad']) && $r['noorderrad'] == true){
                $data=$data->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'));
            }
            else{
                $data=$data->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'));
            }

            $data=$data->first();
                
            $result['ordelab'] = $data;

            return $this->respond($result);

    }

}
