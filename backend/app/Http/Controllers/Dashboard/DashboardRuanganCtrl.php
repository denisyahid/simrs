<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JadwalDokter;
use App\Models\Master\Kamar;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StokProdukDetail;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardRuanganCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function  getRuangan(Request $r)

    {
        $data  = DB::table('maploginusertoruangan_s as mpl')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'mpl.objectruanganfk')
            ->leftjoin('loginuser_s as ls', 'ls.id', '=', 'mpl.objectloginuserfk')
            ->leftjoin('departemen_m as dp', 'ru.objectdepartemenfk', '=', 'dp.id')
            ->select(
                'ru.id',
                'mpl.statusenabled',
                'ru.namaruangan',
                'dp.namadepartemen',
                'ru.objectdepartemenfk',
                'mpl.objectruanganfk',
                'mpl.objectloginuserfk',
                'ls.namauser'
            )
            ->where('mpl.kdprofile', $this->kdProfile)
            ->where ('mpl.statusenabled','=','true');
            

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mpl.objectruanganfk', '=',  $r['objectruanganfk']);
        }
        if (isset($r['namaruangan']) && $r['namaruangan'] != '') {
            $data = $data->where('ru.namaruangan', 'ilike', '%' . $r['namaruangan'] . '%');
        }
        if (isset($r['namadepartemen']) && $r['namadepartemen'] != '') {
            $data = $data->where('dp.id', '=', $r['namadepartemen']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mpl.statusenabled', '=', $r['statusenabled']);
        }
        if (isset($r['objectdepartemenfk']) && $r['objectdepartemenfk'] != '') {
            $data = $data->where('ru.objectdepartemenfk', '=',  $r['objectdepartemenfk']);
        }

        $data = $data->orderByDesc('ru.namaruangan');
        $data = $data->get();

        foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'purple';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }
        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getHeader (Request $r)
    {
        $data  = DB::table('ruangan_m as ru')
        ->leftjoin('departemen_m as dp', 'ru.objectdepartemenfk', '=', 'dp.id')
        ->select(
            'ru.id',
            'ru.statusenabled',
            'ru.namaruangan',
            'dp.namadepartemen',
            'ru.objectdepartemenfk',
        )
        ->where('ru.kdprofile', $this->kdProfile);

    $data = $data->orderByDesc('ru.namaruangan');
    $data = $data->get();

    $res['data'] = $data;
    return $this->respond($res);
}

    public function  getKamar(Request $r)
    {
        $data  = DB::table('ruangan_m as ru')
            ->leftjoin('kamar_m as kmr', 'kmr.objectruanganfk', '=', 'ru.id')
            ->leftjoin('kelas_m as kl', 'kmr.objectkelasfk', '=', 'kl.id')
            ->select(
                'ru.id',
                'ru.statusenabled',
                'ru.namaruangan',
                'kmr.namakamar',
                'kmr.jumlakamarisi',
                'kmr.jumlakamarkosong',
                'kl.namakelas'
            )
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('kmr.statusenabled', true);
           

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('ru.id', '=',  $r['id']);
        }

        $data = $data->get();
       

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function  getDokter(Request $r)
    {
        $data  = DB::table('ruangan_m as ru')
            ->leftjoin('jadwaldokter_m as jd', 'jd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'jd.objectpegawaifk', '=', 'pg.id')
            ->select(
                'ru.id',
                'ru.statusenabled',
                'ru.namaruangan',
                'jd.hari',
                'jd.jammulai',
                'jd.jamakhir',
                'jd.objectruanganfk',
                'jd.objectpegawaifk',
                'pg.namalengkap'
            )
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('jd.statusenabled', true);

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('ru.id', '=',  $r['id']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function  getStokProduk(Request $r)
    {
        $data  = DB::table('ruangan_m as ru')
            ->leftjoin('stokprodukdetail_t as spd', 'spd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
            ->leftjoin('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'spd.qtyproduk',
                'spd.objectruanganfk',
                'spd.objectprodukfk',
                'pr.namaproduk',
                'ap.asalproduk',
                'spd.harganetto1',
                'spd.harganetto2',

            )
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('spd.statusenabled', true);

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('ru.id', '=',  $r['id']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function getJumlah (Request $r)
    {
        $kamar = Kamar::where('objectruanganfk',$r->objectruanganfk)
        ->where('statusenabled',true)
        ->count();
        
        $produk = StokProdukDetail::where('objectruanganfk',$r->objectruanganfk)
        ->where('statusenabled',true)
        ->count();

        $dokter = JadwalDokter::where('objectruanganfk',$r->objectruanganfk)
        ->where('statusenabled',true)
        ->count();

        $regis = PasienDaftar::where('objectruanganlastfk',$r->objectruanganfk)
        ->where('statusenabled',true)
        ->count();
        
        $res['totalKamar']= $kamar;
        $res['totalProduk']= $produk;
        $res['totalDokter']= $dokter;
        $res['totalRegis']= $regis;
         
        return $this->respond($res);
     }

    
    public function getPasien (Request $r)
    {
        $data  = DB::table('ruangan_m as ru')
        ->join('pasiendaftar_t as pd', 'pd.objectruanganlastfk', '=', 'ru.id')
        ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
        ->leftjoin ('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
        ->select(
            'ru.id',
            'ru.statusenabled',
            'ru.namaruangan',
            'pd.objectruanganlastfk',
            'pd.objectpegawaifk',
            'pg.namalengkap',
            'ps.namapasien',
            'pd.tglregistrasi'
        )
        ->where('ru.kdprofile', $this->kdProfile)
        ->where('pd.statusenabled', true);

    if (isset($r['id']) && $r['id'] != '') {
        $data = $data->where('ru.id', '=',  $r['id']);
    }
    if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
         $data = $data->where('pd.tglregistrasi', '>=', $r['dari']);
    }
    if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
        $data = $data->where('pd.tglregistrasi', '<=', $r['sampai']);
    }

    $data = $data->get();

    $res['data'] = $data;
    return $this->respond($res);
}
    
     public function Ruangandropdown(Request $r)

    {
        $res['ruanganLab'] = Ruangan::mine()
            ->where('objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->get();

        return $this->respond($res);
    }
}
