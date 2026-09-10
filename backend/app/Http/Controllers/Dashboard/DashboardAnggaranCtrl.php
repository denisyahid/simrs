<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Datatrans\Pegawai;
use App\Models\Master\HubunganKeluarga;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai as MasterPegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianApotik;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukResep;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Exception;
class DashboardAnggaranCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getDataAnggaran(Request $request) {
        $div = '';
        if(isset($request['div']) && $request['div']!= '-'){
            $div = 'where div =' . $request['div'];
        }
        $tahun = '';
        if(isset($request['tahun'])){
            $tahun = "and ka.tahun ='$request[tahun]'";
        }
        $tahap = '';
        if(isset($request['tahap'])){
            $tahap = "and kb.objecttahapfk=$request[tahap] and ka.objecttahapfk=$request[tahap]";
        }
        $data = DB::select(DB::raw("
        select y.*, km.objecttahapfk, km.sort, ta.tahap,
        case 
            when km.div = 0 then 'Urusan Pemerintahan'
            when km.div = 1 then 'Program' 
            when km.div = 2 then 'Kegiatan' 
            when km.div = 3 then 'Sub Kegiatan' 
            when km.div = 4 then 'Sub Sub Kegiatan' 
        end as divide
        from kegiatananggaran_m km 
        inner join (

        select ka.id, ka.kode,ka.keterangan,sum(x.subtotal) as total, x.tahun, x.namalengkap from kegiatananggaran_m ka 
        INNER JOIN (
            select ka.id, ka.kode, kb.norec, ka.keterangan,
            kb.keteranganbelanja,kb.subtotal, ka.tahun, pg.namalengkap 
            from keteranganbelanja_t kb
            INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
            left join pegawai_m pg on pg.id = ka.objectpptkfk 
            where ka.statusenabled = true and ka.kdprofile = $this->kdProfile $tahun
            $tahap  and kb.subtotal is not null
            )as x on ka.kode=left(x.kode,4) 
        where ka.statusenabled='t' and length(ka.kode)=4 and ka.div = 0
        group by ka.kode,ka.keterangan,ka.id, x.namalengkap, x.tahun

        union all 
        select ka.id, ka.kode,ka.keterangan,sum(x.subtotal) as total, x.tahun, x.namalengkap from kegiatananggaran_m ka 
        INNER JOIN (
            select ka.id, ka.kode, kb.norec, ka.keterangan,
            kb.keteranganbelanja,kb.subtotal, ka.tahun, pg.namalengkap 
            from keteranganbelanja_t kb
            INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
            left join pegawai_m pg on pg.id = ka.objectpptkfk 
            where  ka.statusenabled = true and ka.kdprofile = $this->kdProfile $tahun
            $tahap and kb.subtotal is not null
            )as x on ka.kode=left(x.kode,7) 
        where ka.statusenabled='t' and length(ka.kode)=7 and ka.div = 1
        group by ka.kode,ka.keterangan,ka.id, x.namalengkap, x.tahun

        union all 
        select ka.id, ka.kode,ka.keterangan,sum(x.subtotal) as total, x.tahun, x.namalengkap from kegiatananggaran_m ka 
        INNER JOIN (
            select ka.id, ka.kode, kb.norec, ka.keterangan,
            kb.keteranganbelanja,kb.subtotal, ka.tahun, pg.namalengkap 
            from keteranganbelanja_t kb
            INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
            left join pegawai_m pg on pg.id = ka.objectpptkfk 
            where  ka.statusenabled = true and ka.kdprofile = $this->kdProfile $tahun
            $tahap and kb.subtotal is not null
            )as x on ka.kode=left(x.kode,12) 
        where ka.statusenabled='t' and length(ka.kode)=12 and ka.div = 2
        group by ka.kode,ka.keterangan,ka.id, x.namalengkap, x.tahun

        union all 
        select ka.id, ka.kode,ka.keterangan,sum(x.subtotal) as total, x.tahun, x.namalengkap from kegiatananggaran_m ka 
        INNER JOIN (
            select ka.id, ka.kode, kb.norec, ka.keterangan,
            kb.keteranganbelanja,kb.subtotal, ka.tahun, pg.namalengkap 
            from keteranganbelanja_t kb
            INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
            left join pegawai_m pg on pg.id = ka.objectpptkfk 
            where  ka.statusenabled = true and ka.kdprofile = $this->kdProfile $tahun
            $tahap and kb.subtotal is not null
            )as x on ka.kode=left(x.kode,15) 
        where ka.statusenabled='t' and length(ka.kode)=15 and ka.div = 3 
        group by ka.kode,ka.keterangan,ka.id, x.namalengkap, x.tahun


        union all 
        select ka.id, ka.kode,ka.keterangan,sum(x.subtotal) as total, x.tahun, x.namalengkap from kegiatananggaran_m ka 
        INNER JOIN (
            select ka.id, ka.kode, kb.norec, ka.keterangan,
            kb.keteranganbelanja,kb.subtotal, ka.tahun, pg.namalengkap 
            from keteranganbelanja_t kb
            INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
            left join pegawai_m pg on pg.id = ka.objectpptkfk 
            where  ka.statusenabled = true and ka.kdprofile = $this->kdProfile $tahun
            $tahap and kb.subtotal is not null
            )as x on ka.kode=left(x.kode,18) 

        where ka.statusenabled='t' and length(ka.kode)=18 and ka.div = 4 and ka.objecttahapfk = 2
        group by ka.kode,ka.keterangan,ka.id, x.namalengkap, x.tahun
        ) as y on km.id = y.id
        left join tahapanggaran_m ta on ta.id = km.objecttahapfk 
        $div
        order by km.kode asc ;
        "));
        $offset = $request['offset']; // misalnya 1
        $limit = $request['limit'];
        $data2 = collect($data)->splice($offset, $limit)->all();

        $result = array(
            'data'=>$data2,
            'total'=> count($data)
        );
        return $this->respond($result); 
    }
}
