<?php

namespace App\Http\Controllers\Anggaran;

use Exception;
use Carbon\Carbon;
use App\Traits\Valet;
use Illuminate\Http\Request;
use App\Models\Transaksi\SPD;
use App\Models\Transaksi\SPM;
use App\Models\Transaksi\SPP;
use App\Models\Transaksi\Panjar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Master\RunningNumber;
use Illuminate\Support\Facades\Date;
use App\Models\Transaksi\SPJtoPanjar;
use App\Models\Transaksi\MataAnggaran;
use App\Models\Transaksi\Pengembalian;
use App\Models\Transaksi\TahapAnggaran;
use App\Models\Transaksi\StrukRealisasi;
use App\Models\Transaksi\RealisasiDetail;
use App\Models\Transaksi\SettingAnggaran;
use App\Models\Transaksi\KegiatanAnggaran;
use App\Models\Transaksi\KeteranganBelanja;
use App\Models\Transaksi\MataAnggaranPermen;
use App\Models\Transaksi\AlokasiKeteranganBelanja;

class AnggaranCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getCombo(Request $request) {
        $tahap = \DB::table('tahapanggaran_m as th')
            ->select('th.tahap', 'th.id')
            ->where('th.kdprofile', $this->kdProfile)
            ->where('th.statusenabled', true)
            ->orderBy('th.id')
            ->get();
        $jenisbelanja = \DB::table('jenisbelanja_m')
            ->select('jenisbelanja','id')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();
        $kelompokanggaran = \DB::table('kelompokanggaran_m')
            ->select('namakelompok as div','id')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id','asc')
            ->get();
            $dataAsalProduk = \DB::table('asalproduk_m as ap')
            ->select('ap.id','ap.asalproduk')
            ->where('ap.statusenabled',true)
            ->where('kdprofile', $this->kdProfile)
            ->where('ap.isanggaran',true)
            ->orderBy('ap.asalproduk')
            ->get();

        $jenisanggaran = DB::table('jenisanggaran_m')->select('id','keterangan')->where('kdprofile', $this->kdProfile)
        ->where('statusenabled', true)->get();
        $digit2 = MataAnggaranPermen::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
        ->select('kode', 'id',DB::raw("kode || ' - '|| mataanggaranpermen as mataanggaranpermen "))
        ->where('div', 2)->get();
        $digit3 = DB::select(DB::raw("
            select map3.id, map3.mataanggaranpermen, map3.kode, map3.mataanggaranpermenfk,
            map2.kode || '.' || map3.kode || ' - ' || map3.mataanggaranpermen as text,
            map2.kode || '.' || map3.kode as kode
            from mataanggaranpermen_m map3
            inner join mataanggaranpermen_m map2 on map3.mataanggaranpermenfk = map2.id and map2.div = 2
            where map2.kdprofile = $this->kdProfile
            and map2.statusenabled = true
            and map3.kdprofile = $this->kdProfile
            and map3.statusenabled = true
            and map3.div = 3
            order by replace(map2.kode, '.', '' )::int4, replace(map3.kode, '.', '' )::int4"));

        $digit4 = DB::select(DB::raw("select map4.id, map4.mataanggaranpermen, map2.kode || '.' || map3.kode || '.' || map4.kode as kode, map4.mataanggaranpermenfk,
            map2.kode || '.' || map3.kode || '.' || map4.kode || ' - ' || map4.mataanggaranpermen as text 
            from mataanggaranpermen_m map4
            inner join mataanggaranpermen_m map3 on map4.mataanggaranpermenfk = map3.id and map3.div = 3
            inner join mataanggaranpermen_m map2 on map3.mataanggaranpermenfk = map2.id and map2.div = 2
            where map2.kdprofile = $this->kdProfile
            and map2.statusenabled = true
            and map3.kdprofile = $this->kdProfile
            and map3.statusenabled = true
            and map4.kdprofile = $this->kdProfile
            and map4.statusenabled = true
            and map4.div = 4
            order by replace(map2.kode, '.', '' )::int4, replace(map3.kode, '.', '' )::int4, replace(map4.kode, '.', '' )::int4"));
            $digit5 =  DB::select(DB::raw("select map.id, map.mataanggaranpermen, map.kode, map.mataanggaranpermenfk,
            map2.kode || '.' || map3.kode || '.' || map4.kode || '.' || map.kode || ' - ' || map.mataanggaranpermen as text 
            from mataanggaranpermen_m map
            inner join mataanggaranpermen_m map4 on map.mataanggaranpermenfk = map4.id and map4.div = 4
            inner join mataanggaranpermen_m map3 on map4.mataanggaranpermenfk = map3.id and map3.div = 3
            inner join mataanggaranpermen_m map2 on map3.mataanggaranpermenfk = map2.id and map2.div = 2
            where map.kdprofile = $this->kdProfile
            and map.statusenabled = true
            and map2.kdprofile = $this->kdProfile
            and map2.statusenabled = true
            and map3.kdprofile = $this->kdProfile
            and map3.statusenabled = true
            and map4.kdprofile = $this->kdProfile
            and map4.statusenabled = true
            and map.div = 5
            order by replace(map2.kode, '.', '' )::int4, replace(map3.kode, '.', '' )::int4, replace(map4.kode, '.', '' )::int4, replace(map.kode, '.', '' )::int4
        "));
        $tahap =TahapAnggaran::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->get();

        


        $kegiatancombo = \DB::table('kegiatananggaran_m as dp')
            ->select('dp.id', 'dp.kode', 'dp.keterangan')
            ->where('dp.kdprofile', $this->kdProfile)
            ->where('dp.statusenabled', true)
            ->where('dp.div', 3)
            ->orderBy('dp.kode')
            ->get();

            // $kegiatansubsubcombo = \DB::table('kegiatananggaran_m as dp')
            // ->select('dp.id', 'dp.kode', 'dp.keterangan')
            // ->where('dp.kdprofile', $idProfile)
            // ->where('dp.tahun', $tahunanggaran)
            // ->where('dp.statusenabled', true)
            // ->where('dp.div', 4)
            // ->where('dp.kode', 'ilike', "%".$subsubkegiatan."%")->orderBy('dp.kode')->distinct();
            // if(isset($request['tahap'])){
            //     $kegiatansubsubcombo = $kegiatansubsubcombo->join('keteranganbelanja_t as kb','kb.objectkegiatanfk','=','dp.id')
            //     ->where('kb.objecttahapfk', $tahap);
            // }
            // if($lisssssssssss != ''){
            //     $kegiatansubsubcombo = $kegiatansubsubcombo->whereIn('dp.id', $listsubsubkegiatan)->get();
            // } else{
            // $kegiatansubsubcombo = $kegiatansubsubcombo->get();
            // }
        $pph = DB::select(DB::raw("select id, jenispajak from jenispajak_m where namaexternal ilike '%pph%' and statusenabled = true and kdprofile = $this->kdProfile"));
            
        $result = array(
            'tahap'=>$tahap,
            'jenisanggaran'=>$jenisanggaran,
            'digit2'=>$digit2,
            'digit3'=>$digit3,
            'digit4'=>$digit4,
            'digit5'=>$digit5,
            'tahap'=>$tahap,
            'jenisbelanja'=>$jenisbelanja,
            'asalproduk'=>$dataAsalProduk,
            'kelompokanggaran'=>$kelompokanggaran,
            'kegiatancombo'=> $kegiatancombo,
            'pph'=>$pph,
        );
        return $this->respond($result);
    }
    public function getPenjagaanSettingAnggaran(Request $request) {
        DB::beginTransaction();
        $tahunanggaran = $request['tahunanggaran'];

        $cek = DB::select(DB::raw("select tahunanggaran from settinganggaran_t where tahunanggaran = '$tahunanggaran' and kdprofile = $this->kdProfile"));

       
            $result = array(
                'status' => 201,
                'data' => $cek,
                'as' => '@epic',
            );

        return $this->setStatusCode($result['status'])->respond($result);
    }
    public function saveSettingAnggaran(Request $request) {
        DB::beginTransaction();
       
        $tahunanggaran = $request['tahunanggaran'];

       try{
            $MA = new SettingAnggaran();
            $MA->norec = $MA->generateNewId();
            $MA->kdprofile = $this->kdProfile;
            $MA->statusenabled = true;
            $MA->kodeexternal = $MA->norec;
            $MA->namaexternal = $MA->norec;
            $MA->reportdisplay = $MA->norec;
            $MA->namapemda = $request['namapemda'];
            $MA->kepaladaerah = $request['kepaladaerah'];
            $MA->namakepaladaerah = $request['namakepaladaerah'];
            $MA->namasekda = $request['namasekda'];
            $MA->nipsekda = $request['nipsekda'];
            $MA->namappkd = $request['namappkd'];
            $MA->nipppkd = $request['nipppkd'];
            $MA->pengelolakeuanganblud = $request['pengelolakeuanganblud'];
            $MA->nipkepalablud = $request['nipkepalablud'];
            $MA->alamat = $request['alamat'];
            $MA->kota = $request['kota'];
            $MA->telepon = $request['telepon'];
            $MA->faximile = $request['faximile'];
            $MA->tahunanggaran = $request['tahunanggaran'];
            $MA->objectkepalabludfk = $request['objectkepalabludfk'];
            $MA->objecttahapaktivfk = $request['objecttahapaktivfk'];
            $MA->nomornpwp = $request['nomornpwp'];
            $MA->organisasi = $request['organisasi'];

            $MA->save();

            $data = \DB::table('settinganggaran_t')->where('norec', '!=', $MA->norec)->update([
                'statusenabled'=> false,
            ]);
            // LOGGING($jenislog,$noreff,$referensi,$keterangan)
           $transStatus = 'true';
           $this->LOGGING(
            'Setting Anggaran',
            $MA->norec,
            'settinganggaran_t',
            'Setting Anggaran untuk Tahun Anggaran' .  $request['tahunanggaran']
        );
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

        if ($transStatus == 'true') {
            $transMessage = "Simpan";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $MA,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataSettingAnggaran(Request $request){
        $data = DB::select(DB::raw("
        select sa.*, th.id, th.tahap, pg.namalengkap as label, pg.id as value from settinganggaran_t sa
        left join tahapanggaran_m as th on th.id = sa.objecttahapaktivfk
        left join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "));
        $kegiatancombo = \DB::table('kegiatananggaran_m as dp')
            ->select('dp.id', 'dp.kode', DB::raw("dp.kode ||' - '||dp.keterangan as keterangan"))
            ->where('dp.kdprofile', $this->kdProfile)
            ->where('dp.statusenabled', true)
            ->where('dp.div', 3)
            ->orderBy('dp.kode')
            ->get();
        $kegiatansubsubcombo = \DB::table('kegiatananggaran_m as dp')->distinct()
            ->select('dp.id', 'dp.kode', DB::raw("dp.kode ||' - '||dp.keterangan as keterangan"))
            ->join('keteranganbelanja_t as kb','kb.objectkegiatanfk','=','dp.id')
            ->where('kb.objecttahapfk', $data[0]->objecttahapaktivfk)
            ->where('dp.kdprofile', $this->kdProfile)
            ->where('dp.tahun', $data[0]->tahunanggaran)
            ->where('dp.statusenabled', true)
            ->where('dp.div', 4)
            ->get();
        $keteranganbelanja =  \DB::table('keteranganbelanja_t as kb')
        ->select('ma.id', 'ka.kode', DB::raw("ma.kodemataanggaran ||' - '|| ma.namamataanggaran as keterangan"), 'ma.kodemataanggaran')
        ->join('mataanggaran_m as ma','kb.objectmataanggaranfk','=','ma.id')
        ->join('kegiatananggaran_m as ka','kb.objectkegiatanfk','=','ka.id')
        ->where('kb.objecttahapfk', $data[0]->objecttahapaktivfk)
        ->where('kb.kdprofile', $this->kdProfile)
        ->where('ka.tahun', $data[0]->tahunanggaran)
        ->where('kb.statusenabled', true)
        ->where('ka.div', 4)
        ->groupBy('ma.id', 'ka.kode', 'ma.kodemataanggaran','ma.namamataanggaran')
        ->orderByRaw("split_part(ma.kodemataanggaran,'.',1) asc, split_part(ma.kodemataanggaran,'.',2) asc, split_part(ma.kodemataanggaran,'.',3)  ::int asc,split_part(ma.kodemataanggaran,'.',4) ::int asc, CASE WHEN split_part(ma.kodemataanggaran, '.', 5 ) = '' THEN 0 ELSE split_part(ma.kodemataanggaran, '.', 5 )::INT end asc")
        // ->orderBy(DB::raw("split_part(ma.kodemataanggaran,'.',1)"),'asc')
        ->get();


        foreach ($kegiatancombo as $d) {
            foreach ($kegiatansubsubcombo as $dd) {
                if(strpos($dd->kode, $d->kode) !== false){
                    $d->detail[] = $dd;
                    foreach ($keteranganbelanja as $ddd) {
                        if($ddd->kode == $dd->kode ){
                            $dd->detail[] = $ddd;
                        }
                    }
                }
            }
        }
        $result = array(
            'data'=> $data,
            'kegiatancombo'=>$kegiatancombo,
            'as'=> 'Fazy@epic'
        );
        return $this->respond($result);
    }
    public function saveMataAnggaran(Request $request) {
        DB::beginTransaction();
        try{
            if ($request['id']==''){
                $newID = MataAnggaran::max('id');

                $MA = new MataAnggaran();
                $MA->id =  $newID + 1;
                $MA->norec = $newID + 1;
                $MA->kdprofile = $this->kdProfile;
                $MA->statusenabled = true;

            }else{
                $MA= MataAnggaran::where('id',$request['id'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
            }
            $MA->reportdisplay = $request['reportdisplay'];
            $MA->kodemataanggaran = $request['kodemataanggaran'];
            $MA->namamataanggaran = $request['namamataanggaran'];
            $MA->objectjenisanggaranfk = $request['jenisanggaran'];
            $MA->keterangan = $request['keterangan'];
            $MA->kodeexternal = $request['kodeexternal'];
            $MA->namaexternal = $request['namaexternal'];
            $MA->mataanggaranpermenfk = $request['mataanggaranpermenfk'];
            $MA->div = $request['div'];

            $MA->save();
           $transStatus = 'true';
        } catch (\Exception $e) {
                $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $MA,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'line'=> $e->getLine(),
                'message'=> $e->getMessage(),
                'result' => $e->getMessage() . ' '.$e->getline(),
                'as' => '@epic',
            );
        }
        
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getMataAnggaran(Request $request) {
        $kdiv = '';
        if(isset($request['div']) && $request['div'] != "" && $request['div'] != "undefined" ){
            $kdiv =  " and ma.div = ". $request['div'];
        }
        // if (isset($request['div']) && $request['div'] != "" && $request['div'] != "undefined" && $request['div'] == "1") {
        //     $kdiv =  "and ma.kodemataanggaran in('I', 'III')";
        // } else if (isset($request['div']) && $request['div'] != "" && $request['div'] != "undefined" && $request['div'] == "4") {
        //     $kdiv =  "and 
        //     case 
        //         when ma.kodemataanggaran ilike 'I.%' then length(ma.kodemataanggaran) in (9,10)
        //         when ma.kodemataanggaran ilike 'III.%' then length(ma.kodemataanggaran) in (9,10)
        //     end";
        // } else if (isset($request['div']) && $request['div'] != "" && $request['div'] != "undefined" && $request['div'] == "2") {
        //     $kdiv =  "and 
        //     case 
        //         when ma.kodemataanggaran ilike 'I.%' then length(ma.kodemataanggaran) in (3)
        //         when ma.kodemataanggaran ilike 'III.%' then length(ma.kodemataanggaran) in (5)
        //     end";
        // } else if (isset($request['div']) && $request['div'] != "" && $request['div'] != "undefined" && $request['div'] == "3") {
        //     $kdiv =  "and 
        //     case 
        //         when ma.kodemataanggaran ilike 'I.%' then length(ma.kodemataanggaran) in (5)
        //         when ma.kodemataanggaran ilike 'III.%' then length(ma.kodemataanggaran) in (7)
        //     end";
        // }
        $data = DB::select(DB::raw("
        select ja.id as id_jenis, ja.keterangan, ma.id, ma.kodemataanggaran,ma.namamataanggaran, ma.div as div_id, case when ma.div = 1 then 'Div 1' when ma.div = 2 then 'Div 2' 
        when ma.div = 3 then 'Div 3' else 'Div 4' end as div
        , split_part(ma.kodemataanggaran, '.', 1) as subkodepertama
        , split_part(ma.kodemataanggaran, '.', 2) as subkodekedua
        , case when split_part(ma.kodemataanggaran, '.', 3) = '' then '0' else split_part(ma.kodemataanggaran, '.', 3)::int4 end as subkodeketiga
        , case when split_part(ma.kodemataanggaran, '.', 4) = '' then '0' else split_part(ma.kodemataanggaran, '.', 4)::int4 end as subkodekeempat
        , case when split_part(ma.kodemataanggaran, '.', 5) = '' then '0' else split_part(ma.kodemataanggaran, '.', 5)::int4 end as subkodekelima
        from mataanggaran_m ma 
        left join jenisanggaran_m ja on ja.id = ma.objectjenisanggaranfk
        where ma.statusenabled = true and ma.kdprofile = $this->kdProfile
        $kdiv
        order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc 
        "));
        $result = array(
            'data'=> $data,
            'as'=> 'Fazy@epic'
        );
        return $this->respond($result);
    }
    public function hapusMataAnggaran(Request $request) {
        DB::beginTransaction();
        try{
            $anggaran = MataAnggaran::where('id', $request['id'])->first();
            $anggaran->statusenabled = false;
            $anggaran->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
                $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $anggaran,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getMessage() . ' '.$e->getLine(),
                'as' => '@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getMataAnggaranPermen(Request $request) {
        $data = DB::select(DB::raw("
        select map.id, map2.kode || '.' || map3.kode || '.' || map4.kode || '.' || map.kode as kode,
        map.mataanggaranpermen
        from mataanggaranpermen_m map
        inner join mataanggaranpermen_m map4 on map.mataanggaranpermenfk = map4.id and map4.div = 4
        inner join mataanggaranpermen_m map3 on map4.mataanggaranpermenfk = map3.id and map3.div = 3
        inner join mataanggaranpermen_m map2 on map3.mataanggaranpermenfk = map2.id and map2.div = 2
        where map.kdprofile = $this->kdProfile
        and map.statusenabled = true
        and map2.kdprofile = $this->kdProfile
        and map2.statusenabled = true
        and map3.kdprofile = $this->kdProfile
        and map3.statusenabled = true
        and map4.kdprofile = $this->kdProfile
        and map4.statusenabled = true
        and map.div = 5
        
        union ALL
        
        select map4.id, map2.kode || '.' || map3.kode || '.' || map4.kode as kode,
        map4.mataanggaranpermen
        from mataanggaranpermen_m map4 
        inner join mataanggaranpermen_m map3 on map4.mataanggaranpermenfk = map3.id and map3.div = 3
        inner join mataanggaranpermen_m map2 on map3.mataanggaranpermenfk = map2.id and map2.div = 2
        where map2.kdprofile = $this->kdProfile
        and map2.statusenabled = true
        and map3.kdprofile = $this->kdProfile
        and map3.statusenabled = true
        and map4.kdprofile = $this->kdProfile
        and map4.statusenabled = true
        and map4.div = 4
        
        union ALL
        
        select map3.id, map2.kode || '.' || map3.kode as kode,
        map3.mataanggaranpermen
        from mataanggaranpermen_m map3 
        inner join mataanggaranpermen_m map2 on map3.mataanggaranpermenfk = map2.id and map2.div = 2
        where map2.kdprofile = $this->kdProfile
        and map2.statusenabled = true
        and map3.kdprofile = $this->kdProfile
        and map3.statusenabled = true
        and map3.div = 3
        
        union all 
        
        select map2.id, map2.kode as kode,
        map2.mataanggaranpermen
        from mataanggaranpermen_m map2
        where map2.kdprofile = $this->kdProfile
        and map2.statusenabled = true
        and map2.div = 2
        
        order by kode"));
        $dataDigit2 = DB::select(DB::raw("
            select map2.id, map2.kode as kode,
            map2.mataanggaranpermen
            from mataanggaranpermen_m map2
            where map2.kdprofile = $this->kdProfile
            and map2.statusenabled = true
            and map2.div = 2
            order by SPLIT_PART(map2.kode, '.', 1), SPLIT_PART(map2.kode, '.', 2), SPLIT_PART(map2.kode, '.', 3)
        "));
        $dataDigit3 = DB::select(DB::raw("
        select map.id, map.kode, map.mataanggaranpermen, map2.kode as kodeup, map2.mataanggaranpermen as mataanggaranpermenup from mataanggaranpermen_m map
        inner join mataanggaranpermen_m map2 on map2.id = map.mataanggaranpermenfk
        where map.kdprofile = $this->kdProfile and map.statusenabled = true and map.div = 3
        and map2.kdprofile = $this->kdProfile and map2.statusenabled = true and map2.div = 2
        order by map2.kode, map.kode
        "));
        $dataDigit4 = DB::select(DB::raw("
        select map2.id, map2.kode, map2.mataanggaranpermen,map4.kode||'.'||map3.kode as kodeup, map3.mataanggaranpermen as mataanggaranpermenup 
        from mataanggaranpermen_m map2
        inner join mataanggaranpermen_m map3 on map3.id = map2.mataanggaranpermenfk
        inner join mataanggaranpermen_m map4 on map4.id = map3.mataanggaranpermenfk
        where map2.kdprofile = $this->kdProfile and map2.statusenabled = true and map2.div = 4
        and map3.kdprofile = $this->kdProfile and map3.statusenabled = true and map3.div = 3
        and map4.kdprofile = $this->kdProfile and map4.statusenabled = true and map4.div = 2
        order by map4.kode, map3.kode, map2.kode;
        "));
        $dataDigit5 = DB::select(DB::raw("
        select map.id, map.kode, map.mataanggaranpermen,map4.kode||'.'||map3.kode||'.'||map2.kode  as kodeup, map2.mataanggaranpermen as mataanggaranpermenup 
        from mataanggaranpermen_m map
        inner join mataanggaranpermen_m map2 on map2.id = map.mataanggaranpermenfk
        inner join mataanggaranpermen_m map3 on map3.id = map2.mataanggaranpermenfk
        inner join mataanggaranpermen_m map4 on map4.id = map3.mataanggaranpermenfk
        where map.kdprofile = $this->kdProfile and map.statusenabled = true and map.div = 5
        and map2.kdprofile = $this->kdProfile and map2.statusenabled = true and map2.div = 4
        and map3.kdprofile = $this->kdProfile and map3.statusenabled = true and map3.div = 3
        and map4.kdprofile = $this->kdProfile and map4.statusenabled = true and map4.div = 2
        order by map4.kode, map3.kode, map2.kode, map.kode
        "));
        $result = array(
            'data'=> $data,
            'dataDigit2'=> $dataDigit2,
            'dataDigit3'=> $dataDigit3,
            'dataDigit4'=> $dataDigit4,
            'dataDigit5'=> $dataDigit5,
            'as'=>'Fazy@epic'
        );
        return $this->respond($result);
    }
    public function saveMataAnggaranPermen(Request $request) {
        DB::beginTransaction();
        try{
            if ($request['id']==''){
                $newID = MataAnggaranPermen::max('id');

                $MAP = new MataAnggaranPermen();
                $MAP->id =  $newID + 1;
                $MAP->kdprofile = $this->kdProfile;
                $MAP->statusenabled = true;

            }else{
                $MAP= MataAnggaranPermen::where('id',$request['id'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
            }
            $MAP->reportdisplay = $request['mataanggaranpermen'];
            $MAP->kode = $request['kode'];
            $MAP->mataanggaranpermen = $request['mataanggaranpermen'];
            $MAP->kodeexternal = 'permendagri';
            $MAP->namaexternal = $request['mataanggaranpermen'];
            $MAP->mataanggaranpermenfk = $request['mataanggaranpermenfk'];
            $MAP->div = $request['div'];

            $MAP->save();
           $transStatus = 'true';
        } catch (\Exception $e) {
                $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $MAP,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getMessage() . ' '.$e->getline(),
                'as' => '@epic',
            );
        }
        
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function hapusMataAnggaranPermen(Request $request) {
        DB::beginTransaction();
        try{
            $anggaran = MataAnggaranPermen::where('id', $request['id'])->first();
            $anggaran->statusenabled = false;
            $anggaran->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
                $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $anggaran,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getMessage() . ' '.$e->getLine(),
                'as' => '@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getRBABelum(Request $request){

        $permen = '';
        if (isset($request['permen']) && $request['permen'] != "" && $request['permen'] != "undefined") {
            $permen =  "and ma.mataanggaranpermenfk = '".$request['permen']."'";
        }

        $data = DB::select(DB::raw("
        select ma.id, ma.kodemataanggaran,ma.namamataanggaran,map.id idmap, map.mataanggaranpermen, coalesce(sum(kt.subtotal),0) as total
        , split_part(ma.kodemataanggaran, '.', 1) as subkodepertama
        , split_part(ma.kodemataanggaran, '.', 2) as subkodekedua
        , case when split_part(ma.kodemataanggaran, '.', 3) = '' then '0' else split_part(ma.kodemataanggaran, '.', 3)::int4 end as subkodeketiga
        , case when split_part(ma.kodemataanggaran, '.', 4) = '' then '0' else split_part(ma.kodemataanggaran, '.', 4)::int4 end as subkodekeempat
        , case when split_part(ma.kodemataanggaran, '.', 5) = '' then '0' else split_part(ma.kodemataanggaran, '.', 5)::int4 end as subkodekelima
        from mataanggaran_m ma 
        left JOIN mataanggaranpermen_m map on map.id=ma.mataanggaranpermenfk
		left JOIN keteranganbelanja_t kt on kt.objectmataanggaranfk=ma.id
        where ma.statusenabled = true and ma.kdprofile = $this->kdProfile
        and ma.mataanggaranpermenfk is null
        and ma.div = 4
        group by ma.id, ma.kodemataanggaran,ma.namamataanggaran,map.id, map.mataanggaranpermen
                , split_part(ma.kodemataanggaran, '.', 1)
                , split_part(ma.kodemataanggaran, '.', 2)
                , case when split_part(ma.kodemataanggaran, '.', 3) = '' then '0' else split_part(ma.kodemataanggaran, '.', 3)::int4 end
                , case when split_part(ma.kodemataanggaran, '.', 4) = '' then '0' else split_part(ma.kodemataanggaran, '.', 4)::int4 end 
                , case when split_part(ma.kodemataanggaran, '.', 5) = '' then '0' else split_part(ma.kodemataanggaran, '.', 5)::int4 end
        order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc ;
            ")
        );

        $detail = DB::select(DB::raw("
        select ma.id, ma.kodemataanggaran,ma.namamataanggaran,map.id idmap, map.mataanggaranpermen, coalesce(sum(kt.subtotal),0) as total
        , split_part(ma.kodemataanggaran, '.', 1) as subkodepertama
        , split_part(ma.kodemataanggaran, '.', 2) as subkodekedua
        , case when split_part(ma.kodemataanggaran, '.', 3) = '' then '0' else split_part(ma.kodemataanggaran, '.', 3)::int4 end as subkodeketiga
        , case when split_part(ma.kodemataanggaran, '.', 4) = '' then '0' else split_part(ma.kodemataanggaran, '.', 4)::int4 end as subkodekeempat
        , case when split_part(ma.kodemataanggaran, '.', 5) = '' then '0' else split_part(ma.kodemataanggaran, '.', 5)::int4 end as subkodekelima
        from mataanggaran_m ma 
        left JOIN mataanggaranpermen_m map on map.id=ma.mataanggaranpermenfk
		left JOIN keteranganbelanja_t kt on kt.objectmataanggaranfk=ma.id
        where ma.statusenabled = true and ma.kdprofile = $this->kdProfile
        and ma.div = 4
        $permen
        group by ma.id, ma.kodemataanggaran,ma.namamataanggaran,map.id, map.mataanggaranpermen
                , split_part(ma.kodemataanggaran, '.', 1)
                , split_part(ma.kodemataanggaran, '.', 2)
                , case when split_part(ma.kodemataanggaran, '.', 3) = '' then '0' else split_part(ma.kodemataanggaran, '.', 3)::int4 end
                , case when split_part(ma.kodemataanggaran, '.', 4) = '' then '0' else split_part(ma.kodemataanggaran, '.', 4)::int4 end 
                , case when split_part(ma.kodemataanggaran, '.', 5) = '' then '0' else split_part(ma.kodemataanggaran, '.', 5)::int4 end
        order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc ;
            ")
        );

        $result = array(
            'data' => $data,
            'detail' => $detail,
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function copyPermen(Request $request) {
        DB::beginTransaction();

        try{
            $MA = MataAnggaran::where('id', '=', $request['idanggaran'])->first();
            $MA->mataanggaranpermenfk = $request['idpermen'];

            $MA->save();
           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

        if ($transStatus == 'true') {
            $transMessage = "Simpan";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $MA,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getMessage() . ' '.$e->getLine(),
                'as' => '@epic',
            );
        }
       
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveKegiatanAnggaran(Request $request) {
        DB::beginTransaction();
       try{
            if ($request['id']==''){
                $newID = KegiatanAnggaran::max('id');
                $MA = new KegiatanAnggaran();
                $MA->id =  $newID + 1;
                $MA->norec = $newID + 1;
                $MA->kdprofile = $this->kdProfile;
                $MA->statusenabled = true;
            }else{
                $cekLock = KegiatanAnggaran::where('id',$request['id'])->first();
                if(!empty($cekLock) && $cekLock->islockrba == true){
                    $result = array(
                        'status' => 400,
                        'result' => 'LOCK!!!',
                        'as' => '@epic',
                    );
                    return $this->respond($result['result'], $result['status'], 'Sudah Dilock tidak bisa dirubah!');
                }
                $MA= KegiatanAnggaran::where('id',$request['id'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
            }
            $MA->kode = $request['kode'];
            $MA->keterangan = $request['keterangan'];
            $MA->kodeexternal = $request['kodeexternal'];
            $MA->namaexternal = $request['namaexternal'];
            $MA->reportdisplay = $request['reportdisplay'];
            $MA->tahun = $request['tahun'];
            // $MA->tahap = $request['tahap'];
            $MA->indikatormasuk = $request['indikatormasuk'];
            $MA->indikatorkeluaran = $request['indikatorkeluaran'];
            $MA->indikatorhasil = $request['indikatorhasil'];
            $MA->targetmasuk = $request['targetmasuk'];
            $MA->targetkeluaran = $request['targetkeluaran'];
            $MA->targethasil = $request['targethasil'];
            $MA->div = $request['div'];
            $MA->objectjenisbelanjafk = $request['jenisbelanja'];
            $MA->objectpptkfk = $request['pptk'];
            $MA->save();
            // if(isset($request['tahap'])){
            //     $KB = new KeteranganBelanja();
            //     $KB->norec = $KB->generateNewId();
            //     $KB->kdprofile = $this->kdProfile;
            //     $KB->statusenabled = true;
            //     $KB->objectkegiatanfk = $MA->id;
            //     $KB->objecttahapfk = $request['tahap'];
            //     $KB->save();
            // }
            
                
           
            

           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

        if ($transStatus == 'true') {
            $transMessage = "Simpan";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $MA,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getMessage() . ' '.$e->getLine(),
                'as' => '@epic',
            );
        }
       
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getKegiatanAnggaran(Request $request) {
        $tahunanggaran = $request['tahun'];
        $dv = '';
        $thdv = '';
        $tn = '';
        $ktahap = '';

        if (isset($request['div']) && $request['div'] != "" && $request['div'] != "undefined" && $request['div'] == 4) {
            $dv = 'and dp.div = '.$request['div'];
            // $thdv = ' and kb.objecttahapfk is not null';
            
        } else if (isset($request['div']) && $request['div'] != "" && $request['div'] != "undefined" && $request['div'] != 4) {
            $dv = 'and dp.div = '.$request['div'];
        } 
        if (isset($tahunanggaran) && $tahunanggaran!= "" && $tahunanggaran!= "undefined") {
            $tn = "and dp.tahun = '$tahunanggaran'";
        }
        if (isset($request['tahap']) && $request['tahap']!= "" && $request['tahap']!= "undefined") {
            $ktahap = "and kb.objecttahapfk = '$request[tahap]'";
        }

        $ktahun = "and dp.tahun = '". date('Y')."'";
        if (isset($request['tahunawal']) && $request['tahunawal'] != "" && $request['tahunawal'] != "undefined") {
            $ktahun =  "and dp.tahun = '".$request['tahunawal']."'";
        }
        $ksubkegiatan = "and dp2.id = '0'";
        if (isset($request['subkegiatan']) && $request['subkegiatan'] != "" && $request['subkegiatan'] != "undefined") {
            $ksubkegiatan =  "and dp2.id = '".$request['subkegiatan']."'";
        }

        
        $ktahapawal = "";
        if (isset($request['tahapawal']) && $request['tahapawal'] != "" && $request['tahapawal'] != "undefined") {
            $ktahapawal =  "and ktb.objecttahapfk = ".$request['tahapawal'];
        }
        
        $kegiatansubsub = DB::select(DB::raw("select dp.tahun tahunsubsubkegiatan, dp.id idsubsubkegiatan, dp.kode kodesubsubkegiatan, dp.id || '-' || dp.keterangan keterangansubsubkegiatan,
        dp.indikatormasuk, dp.indikatorkeluaran, dp.indikatorhasil, dp.targetmasuk, dp.targetkeluaran, dp.targethasil, dp.div,
        dp.objectjenisbelanjafk, dp.objectpptkfk, dp.id, dp.keterangan
        from kegiatananggaran_m as dp
        inner join kegiatananggaran_m as dp2 on dp.kode ilike dp2.kode || '%'
        inner join keteranganbelanja_t as ktb on ktb.objectkegiatanfk = dp.id
        where dp.kdprofile = $this->kdProfile
        and dp.statusenabled = true
        and dp2.kdprofile = $this->kdProfile 
        and dp2.statusenabled = true 
        and ktb.kdprofile = $this->kdProfile
        and ktb.statusenabled = true
        and dp.div = 4
        and dp2.div = 3
        $ktahapawal
        $ktahun
        $ksubkegiatan
        group by dp.id, dp.kode, dp.id || '-' || dp.keterangan, dp.tahun, dp.indikatormasuk, dp.indikatorkeluaran, dp.indikatorhasil, dp.targetmasuk, dp.targetkeluaran, dp.targethasil, dp.div,
        dp.objectjenisbelanjafk, dp.objectpptkfk, dp.id, dp.keterangan
        order by dp.kode"));

        $kegiatan = DB::select(DB::raw("
        select dp.objectjenisbelanjafk, jb.jenisbelanja, dp.objectpptkfk, dp.islockrba, ta.tahap, kb.objecttahapfk, dp.norec, dp0.kode kodesubkegiatan, dp0.keterangan keterangansubkegiatan,
        dp3.kode kodekegiatan, dp3.keterangan keterangankegiatan,
        dp2.kode kodeprogram, dp2.keterangan keteranganprogram,
        dp1.kode kodeurusan, dp1.keterangan keteranganurusan, 
        dp.indikatormasuk, dp.indikatorkeluaran, dp.indikatorhasil, dp.targetmasuk, dp.targetkeluaran, dp.targethasil, 
                    coalesce(sum(kb.subtotal),0) as targetmasuk,dp.id, dp.tahun, dp.kode, dp.keterangan, dp.div, kp.namakelompok, pg.namalengkap, cast(split_part(dp.kode, '.', 1) as int4) as subkodeawal
                    , cast(split_part(dp.kode, '.', 2) as int4) as subkodekedua
                    , case when split_part(dp.kode, '.', 3) = '' then '0' else split_part(dp.kode, '.', 3)::int4 end as subkodeketiga
                    , case when split_part(dp.kode, '.', 4) = '' then '0' else split_part(dp.kode, '.', 4)::int4 end as subkodekeempat
                    , case when split_part(dp.kode, '.', 5) = '' then '0' else split_part(dp.kode, '.', 5)::int4 end as subkodekelima
                    , case when split_part(dp.kode, '.', 6) = '' then '0' else split_part(dp.kode, '.', 6)::int4 end as subkodekeenam
                    , case when split_part(dp.kode, '.', 7) = '' then '0' else split_part(dp.kode, '.', 7)::int4 end as subkodeakhir 
        from kegiatananggaran_m as dp 
		left join keteranganbelanja_t as kb on kb.objectkegiatanfk = dp.id 
		and kb.kdprofile = $this->kdProfile and kb.statusenabled = true 
		left join kegiatananggaran_m as dp3 on dp.kode ilike dp3.kode || '%' and dp3.div = 2
		and dp3.kdprofile = $this->kdProfile and dp3.statusenabled = true 
		left join kegiatananggaran_m as dp2 on dp3.kode ilike dp2.kode || '%' and dp2.div = 1
		and dp2.kdprofile = $this->kdProfile and dp2.statusenabled = true 
		left join kegiatananggaran_m as dp1 on dp2.kode ilike dp1.kode || '%' and dp1.div = 0
		and dp1.kdprofile = $this->kdProfile and dp1.statusenabled = true 
		left join kegiatananggaran_m as dp0 on dp.kode ilike dp0.kode || '%' and dp0.div = 3
		and dp0.kdprofile = $this->kdProfile and dp0.statusenabled = true 
		left join pegawai_m pg on pg.id = dp.objectpptkfk
        left join kelompokanggaran_m as kp on kp.id = dp.div 
        left join tahapanggaran_m ta on ta.id = kb.objecttahapfk
        left join jenisbelanja_m jb on jb.id = dp.objectjenisbelanjafk
		where dp.kdprofile = $this->kdProfile  and dp.statusenabled = true 
        $dv
        $tn
        $thdv
        $ktahap
        group by dp.objectjenisbelanjafk, jb.jenisbelanja, dp.objectpptkfk, ta.tahap, kb.objecttahapfk, dp.norec, dp0.kode, dp0.keterangan, dp3.kode, dp3.keterangan, dp2.kode, dp2.keterangan, dp1.kode, dp1.keterangan, 
        dp.indikatormasuk, dp.indikatorkeluaran, dp.indikatorhasil, dp.targetmasuk, dp.targetkeluaran, dp.targethasil, dp.id, dp.kode, dp.div, dp.tahun, dp.keterangan, kp.namakelompok, pg.namalengkap, cast(split_part(dp.kode, '.', 1) as int4)
        , cast(split_part(dp.kode, '.', 2) as int4)
        , case when split_part(dp.kode, '.', 3) = '' then '0' else split_part(dp.kode, '.', 3)::int4 end
        , case when split_part(dp.kode, '.', 4) = '' then '0' else split_part(dp.kode, '.', 4)::int4 end
        , case when split_part(dp.kode, '.', 5) = '' then '0' else split_part(dp.kode, '.', 5)::int4 end
        , case when split_part(dp.kode, '.', 6) = '' then '0' else split_part(dp.kode, '.', 6)::int4 end
        , case when split_part(dp.kode, '.', 7) = '' then '0' else split_part(dp.kode, '.', 7)::int4 end order by subkodeawal asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc, subkodekeenam asc, subkodeakhir asc
        "));
        $result = array(
            'data'=>$kegiatan,
            'kegiatansubsub'=>$kegiatansubsub,
            'as'=>'Fazy@epic'
        );
        return $this->respond($result);
    }
    public function getTotalAnggaranRcn(Request $request){
        $norec = $request['kegiatan'];
        $data = DB::select(DB::raw("
        select ka.norec, ka.keterangan, coalesce(cast(sum(kt.subtotal) as float),0) as total
                from kegiatananggaran_m ka
                left JOIN keteranganbelanja_t kt on ka.id=kt.objectkegiatanfk
                where ka.kdprofile=$this->kdProfile
                and ka.statusenabled = true
				and kt.kdprofile=$this->kdProfile
                and kt.statusenabled = true
				and ka.id = '$norec'
                group by  ka.norec, ka.keterangan
        ")
        );

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function getJenisBelanja(Request $request){
        
        $keterangan = $request['kegiatan'];
        $data = DB::select(DB::raw("
        select jb.id, jb.jenisbelanja from
        jenisbelanja_m jb 
        inner join kegiatananggaran_m ka on ka.objectjenisbelanjafk = jb.id
        where ka.id = $keterangan
        and jb.kdprofile = $this->kdProfile
        and jb.statusenabled = true
        and ka.kdprofile = $this->kdProfile
        and ka.statusenabled = true
        ")
        );

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    public function getPPTK(Request $request){
        $id = $request['id'];
        $tahun = $request['tahun'];
        $kode = $request['kode'];
        $data = DB::select(DB::raw("
                select ka.*, pg.namalengkap
                from kegiatananggaran_m ka
                inner join pegawai_m pg on pg.id = ka.objectpptkfk  
                where ka.kdprofile = $this->kdProfile
                and ka.statusenabled = true
                and ka.id = $id and tahun = '$tahun';
            ")
        );

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function getTotalMataAnggaran(Request $request){
        $idpegawai = $request['idpegawai'];

        $stahun = '';
        if(isset($request['tahun']) || $request['tahun'] != ''){
            $stahun = "and ka.tahun = '".$request['tahun']."'";
        }

        $stahap = '';
        $qtahap = '';
        if(isset($request['tahap']) && $request['tahap'] != '' && $request['tahap'] != 'undefined'){
            $stahap = "and kb.objecttahapfk = ".$request['tahap'];
            $qtahap = "and kt.objecttahapfk = ".$request['tahap'];
        }

        $skegiatan = '';
        if(isset($request['kegiatan']) && $request['kegiatan'] != '' && $request['kegiatan'] != 'undefined'){
            $skegiatan = "and kb.objectkegiatanfk = ".$request['kegiatan'];
        }
        $subkegiatan = '';
        if(isset($request['subkegiatan']) && $request['subkegiatan'] != '' && $request['subkegiatan'] != 'undefined'){
            $subkegiatan = "and dp0.kode = '$request[subkegiatan]'";
        }
        $subsubkegiatan = '';
        if(isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != '' && $request['subsubkegiatan'] != 'undefined'){
            $subsubkegiatan = "and ka.kode = '$request[subsubkegiatan]'";
        }
        $smataanggaran = '';
        if(isset($request['mataanggaran']) && $request['mataanggaran'] != '' && $request['mataanggaran'] != 'undefined'){
            $smataanggaran = "and ma.kodemataanggaran = '".$request['mataanggaran']."'";
        }

        $snorec = '';
        if(isset($request['noreckegiatan']) && $request['noreckegiatan'] != '' && $request['noreckegiatan'] != 'undefined'){
            $snorec = "and kt.objectkegiatanfk = ".$request['noreckegiatan'];
        }

        $scek = '';
        if(isset($request['cek']) && $request['cek'] != '' && $request['cek'] != 'undefined'){
            if($request['cek'] == "false"){
                $scek = '';
            }
            else if($request['cek'] == "true"){
                $scek = "where total != 0";
            } 
            
        }


        $data = DB::select(DB::raw("select * from(
                select ma.id, ma.kodemataanggaran,ma.namamataanggaran, coalesce(cast(sum(kt.subtotal) as float),0) as total
                , split_part(ma.kodemataanggaran, '.', 1) as subkodepertama
                , split_part(ma.kodemataanggaran, '.', 2) as subkodekedua
                , case when split_part(ma.kodemataanggaran, '.', 3) = '' then '0' else split_part(ma.kodemataanggaran, '.', 3)::int4 end as subkodeketiga
                , case when split_part(ma.kodemataanggaran, '.', 4) = '' then '0' else split_part(ma.kodemataanggaran, '.', 4)::int4 end as subkodekeempat
                , case when split_part(ma.kodemataanggaran, '.', 5) = '' then '0' else split_part(ma.kodemataanggaran, '.', 5)::int4 end as subkodekelima 
                from mataanggaran_m ma
                left JOIN keteranganbelanja_t kt on kt.objectmataanggaranfk=ma.id and kt.statusenabled = true
                $snorec
                where 
                length(ma.kodemataanggaran) in(9,10) 
                and ma.kdprofile=$this->kdProfile
                and ma.statusenabled = true
                group by  ma.id,ma.kodemataanggaran,ma.namamataanggaran
                order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc
                ) as x 
                $scek
                order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc;
            ")
        );
        $dataKeteranganBelanja = DB::select(DB::raw("
            select kb.norec, kb.kdprofile, kb.statusenabled, kb.objectkegiatanfk, kb.objectmataanggaranfk, kb.keteranganbelanja,
            ma.kodemataanggaran || ' ' || ma.namamataanggaran as mataanggaran,
            ka.kode || ' ' || ka.keterangan as kegiatan,ap.asalproduk,
            ma.kodemataanggaran , ma.namamataanggaran,ka.kode , ka.keterangan,ka.tahun,ta.tahap,
            dp0.kode kodesubkegiatan, dp0.keterangan keterangansubkegiatan,
            dp3.kode kodekegiatan, dp3.keterangan keterangankegiatan,
            dp1.kode kodeurusan, dp1.keterangan keteranganurusan
            , split_part(ma.kodemataanggaran, '.', 1) as subkodepertama
            , split_part(ma.kodemataanggaran, '.', 2) as subkodekedua
            , case when split_part(ma.kodemataanggaran, '.', 3) = '' then '0' else split_part(ma.kodemataanggaran, '.', 3)::int4 end as subkodeketiga
            , case when split_part(ma.kodemataanggaran, '.', 4) = '' then '0' else split_part(ma.kodemataanggaran, '.', 4)::int4 end as subkodekeempat
            , case when split_part(ma.kodemataanggaran, '.', 5) = '' then '0' else split_part(ma.kodemataanggaran, '.', 5)::int4 end as subkodekelima
            --,1 as jan,1 as feb,1 as mar, 1 as apr,
            --1 as mei,1 as juni, 1 as juli,1 as agst,1 as sep,1 as okt, 1 as nov,1 as des
            from keteranganbelanja_t kb
            INNER JOIN mataanggaran_m ma on ma.id=kb.objectmataanggaranfk
            INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
            inner join kegiatananggaran_m as dp3 on ka.kode ilike dp3.kode || '%' and dp3.div = 2
            inner join kegiatananggaran_m as dp2 on dp3.kode ilike dp2.kode || '%' and dp2.div = 1
            inner join kegiatananggaran_m as dp1 on dp2.kode ilike dp1.kode || '%' and dp1.div = 0
            inner join kegiatananggaran_m as dp0 on ka.kode ilike dp0.kode || '%' and dp0.div = 3
            INNER JOIN asalproduk_m ap on ap.id=kb.objectasalprodukfk
            INNER JOIN tahapanggaran_m ta on ta.id=kb.objecttahapfk
            --inner join settinganggaran_t sa on sa.objecttahapaktivfk = ta.id
            where kb.kdprofile = $this->kdProfile and kb.statusenabled = true
            and ka.kdprofile = $this->kdProfile and ka.statusenabled = true
            $stahun
            $stahap
            $subkegiatan
            $subsubkegiatan
            group by kb.norec, kb.kdprofile, kb.statusenabled, kb.objectkegiatanfk, kb.objectmataanggaranfk, kb.keteranganbelanja,
            ma.kodemataanggaran || ' ' || ma.namamataanggaran, kb.nourut, kb.jml, kb.hargasatuan, kb.subtotal, kb.satuan,
            kb.objectasalprodukfk, kb.objecttahapfk,
            ka.kode || ' ' || ka.keterangan,ap.asalproduk,
            ma.kodemataanggaran , ma.namamataanggaran,ka.kode , ka.keterangan,ka.tahun,ta.tahap,
            dp0.kode, dp0.keterangan,
            dp3.kode, dp3.keterangan,
            dp1.kode, dp1.keterangan
            order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc"
            )
            );
        $arrALokasi = [];
        $dataAlokasi = \DB::table('alokasiketeranganbelanja_t as ap')
        ->join('keteranganbelanja_t as kb', 'ap.keteranganbelanjafk', '=', 'kb.norec')
        ->join('mataanggaran_m as ma', 'ma.id', '=', 'kb.objectmataanggaranfk')
        ->select('ap.norec', 'ap.keteranganbelanjafk', 'ap.bulanint', 'ap.nilai', 'ma.kodemataanggaran')
        ->where('ap.statusenabled',true)
        // ->where('ap.keteranganbelanjafk',$item->norec)
        ->groupBy('ap.norec', 'ap.keteranganbelanjafk', 'ap.bulanint', 'ap.nilai', 'ma.kodemataanggaran')
        ->get(); 
        foreach($dataKeteranganBelanja as $item){
            $arrALokasi = [];
            foreach ($dataAlokasi as $d) {
                if($item->norec == $d->keteranganbelanjafk){
                        $arrALokasi[] = $d;
                }
            }
        }

        $result = array(
            'data' => $data,
            'keteranganbelanja' => $dataKeteranganBelanja,
            'alokasi' => $arrALokasi,
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function getKeteranganBelanja(Request $request){
        $idkegiatanfk= $request['objectkegiatanfk'];
        $mata = '';
        if(isset($request['idmataanggaran']) && $request['idmataanggaran'] != '' && $request['idmataanggaran'] != "undefined"){
            $mata = ' and ma.id = '.$request['idmataanggaran'];
        }
        $idmataanggaran= $request['idmataanggaran'];
        
        $data = DB::select(DB::raw("
                select kt.password, kt.norec	,kt.kdprofile	,kt.statusenabled	,kt.objectkegiatanfk	,kt.objectmataanggaranfk	
                ,kt.keteranganbelanja	,kt.nourut	,kt.jml	,cast(kt.hargasatuan as float) as hargasatuan	 ,cast(kt.subtotal as float) as subtotal	,
                kt.satuan	,kt.objectasalprodukfk,ap.asalproduk ,ma.namamataanggaran,ma.kodemataanggaran
                from keteranganbelanja_t kt
                INNER JOIN asalproduk_m ap on ap.id=kt.objectasalprodukfk
                INNER JOIN mataanggaran_m ma on ma.id=kt.objectmataanggaranfk
                where kt.objectkegiatanfk= $idkegiatanfk
                $mata
                and kt.statusenabled = true
                and ma.kdprofile = $this->kdProfile
                and ma.statusenabled = true
                order by kt.nourut
            ")
        );

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    public function saveKeteranganBelanja(Request $request) {
        DB::beginTransaction();
       try{
        $cekLock = KegiatanAnggaran::where('id',$request['objectkegiatanfk'])->first();
        if(!empty($cekLock) && $cekLock->islockrba == true){
            $result = array(
                'status' => 400,
                'result' => 'LOCK!!!',
                'as' => '@epic',
            );
            return $this->respond($result['result'], $result['status'], 'Sudah Dilock tidak bisa dirubah!');
        }
            if ($request['norec']==''){
                $MA = new KeteranganBelanja();
                $norecKS = $MA->generateNewId();
                $MA->norec = $norecKS;
                $MA->kdprofile = $this->kdProfile;
                $MA->statusenabled = true;

            }else{
                $MA= KeteranganBelanja::where('norec',$request['norec'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
            }
            $MA->objectkegiatanfk = $request['objectkegiatanfk'];
            $MA->objectmataanggaranfk = $request['objectmataanggaranfk'];
            $MA->keteranganbelanja = $request['keteranganbelanja'];
            $MA->nourut = $request['nourut'];
            $MA->jml = $request['jml'];
            $MA->hargasatuan = $request['hargasatuan'];
            $MA->subtotal = $request['subtotal'];
            $MA->satuan = $request['satuan'];
            $MA->objectasalprodukfk = $request['objectasalprodukfk'];
            $MA->objecttahapfk = $request['idtahap'];
            $MA->password = $request['password'];

            $MA->save();
           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

        if ($transStatus == 'true') {
            $transMessage = "Simpan";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $MA,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getLine() .'/'.$e->getMessage(),
                'as' => '@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteKegiatanAnggaran(Request $request) {
        DB::beginTransaction();
       
        try{
            $MA= KegiatanAnggaran::where('id',$request['id'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
            $MA->statusenabled = false;
            $MA->save();

            

            if($request['div'] == 4){

                $data = \DB::table('keteranganbelanja_t')->where('objectkegiatanfk',$request['id'])->update([
                    'statusenabled'=> false
                ]);
            }

           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $MA,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getLine() .'/'.$e->getMessage(),
                'as' => '@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteAnggaranKas(Request $request)
    {
        DB::beginTransaction();
        
        $data = "";
        try {
            $data = \DB::table('keteranganbelanja_t')->where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update([
                'statusenabled'=> false
            ]);
            $transStatus = 'true';
        } catch (\Throwable $th) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getLine() .'/'.$e->getMessage(),
                'as' => '@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getKegiatanAnggaranKas(Request $request) {
        
        $stahun = '';
        if(isset($request['tahun']) || $request['tahun'] != ''){
            $stahun = "and ka.tahun = '".$request['tahun']."'";
        }
        $pegawai = $request['idpegawai'];
        $stahap = '';
        $qtahap = '';
        if(isset($request['tahap']) && $request['tahap'] != '' && $request['tahap'] != 'undefined'){
            $stahap = "and kb.objecttahapfk = ".$request['tahap'];
            $qtahap = "and kt.objecttahapfk = ".$request['tahap'];
        }

        $skegiatan = '';
        if(isset($request['kegiatan']) && $request['kegiatan'] != '' && $request['kegiatan'] != 'undefined'){
            $skegiatan = "and kb.objectkegiatanfk = ".$request['kegiatan'];
        }
        $subkegiatan = '';
        if(isset($request['subkegiatan']) && $request['subkegiatan'] != '' && $request['subkegiatan'] != 'undefined'){
            $subkegiatan = "and dp0.kode = '$request[subkegiatan]'";
        }
        $subsubkegiatan = '';
        if(isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != '' && $request['subsubkegiatan'] != 'undefined'){
            $subsubkegiatan = "and ka.kode = '$request[subsubkegiatan]'";
        }
        $smataanggaran = '';
        if(isset($request['mataanggaran']) && $request['mataanggaran'] != '' && $request['mataanggaran'] != 'undefined'){
            $smataanggaran = "and ma.kodemataanggaran = '".$request['mataanggaran']."'";
        }

        $snorec = '';
        if(isset($request['noreckegiatan']) && $request['noreckegiatan'] != '' && $request['noreckegiatan'] != 'undefined'){
            $snorec = "and kt.objectkegiatanfk = ".$request['noreckegiatan'];
        }

        $scek = '';
        if(isset($request['cek']) && $request['cek'] != '' && $request['cek'] != 'undefined'){
            if($request['cek'] == "false"){
                $scek = '';
            }
            else if($request['cek'] == "true"){
                $scek = "where total != 0";
            } 
            
        }
        $head = DB::select(DB::raw("
        select id, kode, keterangan, kegiatan, tahun,tahap,sum(subtotal) subtotal,sum(jan::float8) jan, sum(feb::float8) feb, sum(mar::float8) mar,
        sum(apr::float8) apr, sum(mei::float8) mei, sum(jun::float8) jun, sum(jul::float8) jul, sum(agt::float8) agt, sum(sep::float8) sep,
        sum(okt::float8) okt, sum(nov::float8) nov, sum(des::float8) des,
        coalesce(sum(jan::float8) + sum(feb::float8) + sum(mar::float8) +
        sum(apr::float8) + sum(mei::float8) + sum(jun::float8) + sum(jul::float8) + sum(agt::float8) +
        sum(sep::float8) + sum(okt::float8) + sum(nov::float8) + sum(des::float8), 0) totalbulanan
        from (
            
            select norec, id, kodemataanggaran, kode, keterangan, kegiatan, tahun, tahap, subtotal, sum(jan::float8) jan, sum(feb::float8) feb, sum(mar::float8) mar,
        sum(apr::float8) apr, sum(mei::float8) mei, sum(jun::float8) jun, sum(jul::float8) jul, sum(agt::float8) agt, sum(sep::float8) sep,
        sum(okt::float8) okt, sum(nov::float8) nov, sum(des::float8) des from (
            select kb.norec, ka.id, ka.kode, ka.keterangan, ka.tahun, ta.tahap, kb.subtotal, ka.kode || ' - ' || ka.keterangan as kegiatan, ma.kodemataanggaran,
            case when ap.bulanint = 1 then coalesce(ap.nilai, '0') end as jan,
            case when ap.bulanint = 2 then coalesce(ap.nilai, '0') end as feb,
            case when ap.bulanint = 3 then coalesce(ap.nilai, '0') end as mar,
            case when ap.bulanint = 4 then coalesce(ap.nilai, '0') end as apr,
            case when ap.bulanint = 5 then coalesce(ap.nilai, '0') end as mei,
            case when ap.bulanint = 6 then coalesce(ap.nilai, '0') end as jun,
            case when ap.bulanint = 7 then coalesce(ap.nilai, '0') end as jul,
            case when ap.bulanint = 8 then coalesce(ap.nilai, '0') end as agt,
            case when ap.bulanint = 9 then coalesce(ap.nilai, '0') end as sep,
            case when ap.bulanint = 10 then coalesce(ap.nilai, '0') end as okt,
            case when ap.bulanint = 11 then coalesce(ap.nilai, '0') end as nov,
            case when ap.bulanint = 12 then coalesce(ap.nilai, '0') end as des
            from keteranganbelanja_t as kb 
            left join alokasiketeranganbelanja_t as ap on ap.keteranganbelanjafk = kb.norec
            and ap.statusenabled = true
            and ap.kdprofile = $this->kdProfile
            inner join mataanggaran_m as ma on ma.id = kb.objectmataanggaranfk
            inner join kegiatananggaran_m ka on ka.id = kb.objectkegiatanfk 
            inner join kegiatananggaran_m as dp0 on ka.kode ilike dp0.kode || '%' and dp0.div = 3
            inner join tahapanggaran_m ta on ta.id = kb.objecttahapfk
            where ka.statusenabled = true
            and ka.kdprofile = $this->kdProfile
            and kb.statusenabled = true
            and kb.kdprofile = $this->kdProfile
            and dp0.statusenabled = true
            and dp0.kdprofile = $this->kdProfile
            and ma.statusenabled = true
            and ma.kdprofile = $this->kdProfile
            $stahun
            $stahap
            $subkegiatan
                $subsubkegiatan
            $smataanggaran
            $pegawai
                    group by kb.norec, ka.id,ka.kode, ka.keterangan, ka.tahun, ta.tahap, kb.subtotal, ka.kode || ' - ' || ka.keterangan, ap.nilai, ap.bulanint,ma.kodemataanggaran
        ) as x
        group by id, kode, keterangan, kegiatan, tahun, tahap, subtotal, kodemataanggaran, norec) as y
        GROUP BY id, kode, keterangan, kegiatan, tahun,tahap
        "));
                $dataAlokasiMataanggaran = DB::select(DB::raw("select kegiatan, kodesubkegiatan, keterangansubkegiatan, kode, keterangan, tahun, tahap, kodemataanggaran, namamataanggaran, mataanggaran, sum(subtotal) subtotal, sum(jan::float8) jan, sum(feb::float8) feb, sum(mar::float8) mar,
                sum(apr::float8) apr, sum(mei::float8) mei, sum(jun::float8) jun, sum(jul::float8) jul, sum(agt::float8) agt, sum(sep::float8) sep,
                sum(okt::float8) okt, sum(nov::float8) nov, sum(des::float8) des,
                coalesce(sum(jan::float8) + sum(feb::float8) + sum(mar::float8) +
                sum(apr::float8) + sum(mei::float8) + sum(jun::float8) + sum(jul::float8) + sum(agt::float8) +
                sum(sep::float8) + sum(okt::float8) + sum(nov::float8) + sum(des::float8), 0) totalbulanan,
                subkodepertama, subkodekedua, subkodeketiga, subkodekeempat, subkodekelima
                from(
                select norec, kodesubkegiatan, keterangansubkegiatan, kode, keterangan, tahun, tahap, kegiatan, kodemataanggaran, namamataanggaran, mataanggaran, subtotal, sum(jan::float8) jan, sum(feb::float8) feb, sum(mar::float8) mar,
                sum(apr::float8) apr, sum(mei::float8) mei, sum(jun::float8) jun, sum(jul::float8) jul, sum(agt::float8) agt, sum(sep::float8) sep,
                sum(okt::float8) okt, sum(nov::float8) nov, sum(des::float8) des,
                subkodepertama, subkodekedua, subkodeketiga, subkodekeempat, subkodekelima
                from(
                    select kb.norec, dp0.kode kodesubkegiatan, dp0.keterangan keterangansubkegiatan, ka.kode, ka.keterangan, ka.tahun, ta.tahap, kb.subtotal, ka.kode || ' ' || ka.keterangan as kegiatan, ma.kodemataanggaran, ma.namamataanggaran,
                    ma.kodemataanggaran || ' - ' || ma.namamataanggaran mataanggaran, 
                    case when ap.bulanint = 1 then coalesce(ap.nilai, '0') end as jan,
                    case when ap.bulanint = 2 then coalesce(ap.nilai, '0') end as feb,
                    case when ap.bulanint = 3 then coalesce(ap.nilai, '0') end as mar,
                    case when ap.bulanint = 4 then coalesce(ap.nilai, '0') end as apr,
                    case when ap.bulanint = 5 then coalesce(ap.nilai, '0') end as mei,
                    case when ap.bulanint = 6 then coalesce(ap.nilai, '0') end as jun,
                    case when ap.bulanint = 7 then coalesce(ap.nilai, '0') end as jul,
                    case when ap.bulanint = 8 then coalesce(ap.nilai, '0') end as agt,
                    case when ap.bulanint = 9 then coalesce(ap.nilai, '0') end as sep,
                    case when ap.bulanint = 10 then coalesce(ap.nilai, '0') end as okt,
                    case when ap.bulanint = 11 then coalesce(ap.nilai, '0') end as nov,
                    case when ap.bulanint = 12 then coalesce(ap.nilai, '0') end as des
                    , split_part(ma.kodemataanggaran, '.', 1) as subkodepertama
                    , split_part(ma.kodemataanggaran, '.', 2) as subkodekedua
                    , case when split_part(ma.kodemataanggaran, '.', 3) = '' then '0' else split_part(ma.kodemataanggaran, '.', 3)::int4 end as subkodeketiga
                    , case when split_part(ma.kodemataanggaran, '.', 4) = '' then '0' else split_part(ma.kodemataanggaran, '.', 4)::int4 end as subkodekeempat
                    , case when split_part(ma.kodemataanggaran, '.', 5) = '' then '0' else split_part(ma.kodemataanggaran, '.', 5)::int4 end as subkodekelima
                    from keteranganbelanja_t as kb 
                    left join alokasiketeranganbelanja_t as ap on ap.keteranganbelanjafk = kb.norec
                    and ap.statusenabled = true
                    and ap.kdprofile = $this->kdProfile
                    inner join mataanggaran_m as ma on ma.id = kb.objectmataanggaranfk
                    inner join kegiatananggaran_m ka on ka.id = kb.objectkegiatanfk 
                    inner join kegiatananggaran_m as dp0 on ka.kode ilike dp0.kode || '%' and dp0.div = 3
                    inner join tahapanggaran_m ta on ta.id = kb.objecttahapfk
                    where ka.statusenabled = true
                    and ka.kdprofile = $this->kdProfile
                    and kb.statusenabled = true
                    and kb.kdprofile = $this->kdProfile
                    and dp0.statusenabled = true
                    and dp0.kdprofile = $this->kdProfile
                    and ma.statusenabled = true
                    and ma.kdprofile = $this->kdProfile
                    $stahun
                    $stahap
                    $subkegiatan
                        $subsubkegiatan
                    $smataanggaran
                    $pegawai
                    group by kb.norec, dp0.kode, dp0.keterangan, ka.kode, ka.keterangan, ka.tahun, ta.tahap, kb.subtotal, ka.kode || ' ' || ka.keterangan, ap.nilai, ap.bulanint, ma.kodemataanggaran, ma.namamataanggaran,
                    ma.kodemataanggaran || ' - ' || ma.namamataanggaran
                    order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc
                ) as x group by norec, kodesubkegiatan, keterangansubkegiatan, kode, keterangan, tahun, tahap, subtotal, kegiatan, kodemataanggaran, namamataanggaran, mataanggaran, subkodepertama, subkodekedua, subkodeketiga, subkodekeempat, subkodekelima
                order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc
        ) as y group by kodesubkegiatan, keterangansubkegiatan, kode, keterangan, tahun, tahap, kegiatan, kodemataanggaran, namamataanggaran, mataanggaran, subkodepertama, subkodekedua, subkodeketiga, subkodekeempat, subkodekelima
        order by  split_part(kode, '.', 1)asc, split_part(kode, '.', 2)asc,  split_part(kode, '.', 3)asc, split_part(kode, '.', 4)asc, split_part(kode, '.', 5)asc, split_part(kode, '.', 6)::int asc, split_part(kode, '.', 7)::int asc,  subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc
        "));

        $detailalokasimataanggaran = DB::select(DB::raw("select norec, keteranganbelanja, kodesubkegiatan, keterangansubkegiatan, kode, keterangan, tahun, tahap, kegiatan, kodemataanggaran, namamataanggaran, mataanggaran, subtotal, sum(jan::float8) jan, sum(feb::float8) feb, sum(mar::float8) mar,
        sum(apr::float8) apr, sum(mei::float8) mei, sum(jun::float8) jun, sum(jul::float8) jul, sum(agt::float8) agt, sum(sep::float8) sep,
        sum(okt::float8) okt, sum(nov::float8) nov, sum(des::float8) des
        from(
            select kb.norec, kb.keteranganbelanja, dp0.kode kodesubkegiatan, dp0.keterangan keterangansubkegiatan, ka.kode, ka.keterangan, ka.tahun, ta.tahap, kb.subtotal, ka.kode || ' ' || ka.keterangan as kegiatan, ma.kodemataanggaran, ma.namamataanggaran,
            ma.kodemataanggaran || ' - ' || ma.namamataanggaran mataanggaran, 
            case when ap.bulanint = 1 then coalesce(ap.nilai, '0') end as jan,
            case when ap.bulanint = 2 then coalesce(ap.nilai, '0') end as feb,
            case when ap.bulanint = 3 then coalesce(ap.nilai, '0') end as mar,
            case when ap.bulanint = 4 then coalesce(ap.nilai, '0') end as apr,
            case when ap.bulanint = 5 then coalesce(ap.nilai, '0') end as mei,
            case when ap.bulanint = 6 then coalesce(ap.nilai, '0') end as jun,
            case when ap.bulanint = 7 then coalesce(ap.nilai, '0') end as jul,
            case when ap.bulanint = 8 then coalesce(ap.nilai, '0') end as agt,
            case when ap.bulanint = 9 then coalesce(ap.nilai, '0') end as sep,
            case when ap.bulanint = 10 then coalesce(ap.nilai, '0') end as okt,
            case when ap.bulanint = 11 then coalesce(ap.nilai, '0') end as nov,
            case when ap.bulanint = 12 then coalesce(ap.nilai, '0') end as des
            from keteranganbelanja_t as kb 
            left join alokasiketeranganbelanja_t as ap on ap.keteranganbelanjafk = kb.norec
            and ap.statusenabled = true
            and ap.kdprofile = $this->kdProfile
            inner join mataanggaran_m as ma on ma.id = kb.objectmataanggaranfk
            inner join kegiatananggaran_m ka on ka.id = kb.objectkegiatanfk 
            inner join kegiatananggaran_m as dp0 on ka.kode ilike dp0.kode || '%' and dp0.div = 3
            inner join tahapanggaran_m ta on ta.id = kb.objecttahapfk
            where ka.statusenabled = true
            and ka.kdprofile = $this->kdProfile
            and kb.statusenabled = true
            and kb.kdprofile = $this->kdProfile
            and dp0.statusenabled = true
            and dp0.kdprofile = $this->kdProfile
            and ma.statusenabled = true
            and ma.kdprofile = $this->kdProfile
            $stahun
            $subkegiatan
            $subsubkegiatan
            $stahap
            group by kb.norec, kb.keteranganbelanja, dp0.kode, dp0.keterangan, ka.kode, ka.keterangan, ka.tahun, ta.tahap, kb.subtotal, ka.kode || ' ' || ka.keterangan, ap.nilai, ap.bulanint, ma.kodemataanggaran, ma.namamataanggaran,
            ma.kodemataanggaran || ' - ' || ma.namamataanggaran
        ) as x group by norec, keteranganbelanja, kodesubkegiatan, keterangansubkegiatan, kode, keterangan, tahun, tahap, subtotal, kegiatan, kodemataanggaran, namamataanggaran, mataanggaran
        order by kodemataanggaran"));

        foreach ($head as $a) {
            $a->detail =[]; 
           foreach ($dataAlokasiMataanggaran as $aa) {
                if(strpos($aa->kode, $a->kode) !== false){
                    $a->detail[] = $aa;
                }
           }
        }
        $result = array(
            'alokasimataanggaran' => $head,
            'detailalokasimataanggaran' => $detailalokasimataanggaran,
            'message' => 'Fazy@epic',
        );

        return $this->respond($result);
    }
    
    public function saveAlokasiKeteranganBelanja(Request $request) {
        DB::beginTransaction();
        $idProfile = $this->kdProfile;
       try{
           foreach($request['data'] as $item){

                $MAA = AlokasiKeteranganBelanja::where('keteranganbelanjafk',$item['keteranganbelanjafk'])
                    ->where('bulanint',$item['bulanint'])
                    ->where('kdprofile', $idProfile)
                    ->first();
                if(empty($MAA)){
                    $MA = new AlokasiKeteranganBelanja();
                    $norecKS = $MA->generateNewId();
                    $MA->norec = $norecKS;
                    $MA->kdprofile = $idProfile;
                    $MA->statusenabled = true;
                }else{
                    $MA = AlokasiKeteranganBelanja::where('keteranganbelanjafk',$item['keteranganbelanjafk'])
                    ->where('bulanint',$item['bulanint'])
                    ->where('kdprofile', $idProfile)
                    ->first();
                }
                $MA->keteranganbelanjafk = $item['keteranganbelanjafk'];
                $MA->bulanint = (float)$item['bulanint'];
                $MA->nilai = (float)$item['nilai'];

                $MA->save();
                
           }
            
           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

       if ($transStatus == 'true') {
        $transMessage = "Simpan";
        DB::commit();
        $result = array(
            'status' => 201,
            'result' => $MA,
            'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDetailSubKegiatan(Request $request){
        $idProfile = $this->kdProfile;
        $id = $request['kegiatan'];
        $tahun = $request['tahun'];
        $idpeg = $request['idpegawai'];
        $pegawai = '';
        $thp = '';
        if(isset($request['tahap']) && $request['tahap'] != "undefined" && $request['tahap'] != ""){
            $thp = "and kb.objecttahapfk = ".$request['tahap'];
        }
        if (isset($request['idpegawai']) && $request['idpegawai'] != "" && $request['idpegawai'] != "undefined") {
            $pegawai = 'and kg.objectpptkfk = '.$idpeg;
        }
        $data = DB::select(DB::raw("
        select kg.id, kg.kode, kg.keterangan, kg.id || '-' || kg.keterangan as text,
        ta.tahap || '-' || kg.keterangan as texttahap
        from kegiatananggaran_m km 
        inner join kegiatananggaran_m kg on kg.kode ilike km.kode || '%' and kg.div = 4
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = kg.id
        inner join tahapanggaran_m ta on ta.id = kb.objecttahapfk
        where km.statusenabled = true and km.kdprofile = $idProfile
        and kg.statusenabled = true and kg.kdprofile = $idProfile
        and kb.statusenabled = true and kb.kdprofile = $idProfile
        and km.id = $id
        and kg.tahun = '$tahun'
        $thp
        $pegawai
        group by kg.id, kg.kode, kg.keterangan, kg.id || '-' || kg.keterangan, ta.tahap || '-' || kg.keterangan
        ")
        );

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    public function getLockRBA(Request $request){
        $idProfile = $this->kdProfile;
        $tahun = $request['tahun'];

        // $qsub = '';
        // if(isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != "undefined" && $request['subsubkegiatan'] != ""){
        //     $qsub = "and dp.id = ".$request['subsubkegiatan'];
        // }

        $qthp = '';
        if(isset($request['tahap']) && $request['tahap'] != "undefined" && $request['tahap'] != ""){
            $qthp = "and ta.id = ".$request['tahap'];
        }

        $qtahun = '';
        if(isset($request['tahun']) && $request['tahun'] != "undefined" && $request['tahun'] != ""){
            $qtahun = "and dp.tahun = '".$request['tahun']."'";
        }

        $data = DB::select(DB::raw("select dp.norec, ta.id idtahap, ta.tahap, dp0.kode kodesubkegiatan, dp0.keterangan keterangansubkegiatan, dp0.id idsubkegiatan,
        dp.kode kodesubsubkegiatan, dp.keterangan keterangansubsubkegiatan, dp.id idsubsubkegiatan,
        dp.kode || ' ~ ' || dp.keterangan as text, dp.tahun, dp.islock, dp.islockrba
        from kegiatananggaran_m as dp  
        inner join kegiatananggaran_m as dp0 on dp.kode ilike dp0.kode || '%' and dp0.div = 3
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = dp.id
        inner join tahapanggaran_m ta on ta.id = kb.objecttahapfk
        where dp.statusenabled = true
        and dp.kdprofile = $idProfile
        and dp.div = 4
        and dp0.statusenabled = true
        and dp0.kdprofile = $idProfile
        and kb.statusenabled = true
        and kb.kdprofile = $idProfile
        and ta.statusenabled = true
        and ta.kdprofile = $idProfile 
        $qtahun
        $qthp
        group by dp.norec, ta.id, ta.tahap, dp0.kode, dp0.keterangan, dp0.id, dp.tahun, dp.islock,
        dp.kode, dp.keterangan, dp.id, dp.kode || ' ~ ' || dp.keterangan, dp.islockrba
            ")
        );

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    
    public function saveLockRBA(Request $request)
    {

        try {
            $STSK = KegiatanAnggaran::where('norec', '=', $request['data']['norec'])->first();
            $STSK->islockrba = $request['data']['islockrba'] == true ? false : true;
            $STSK->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        
        
                
        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $STSK,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveSettingTahap(Request $request) {
        DB::beginTransaction();
        $idProfile = $this->kdProfile;
        $details = $request['details'];
        $awal = $request['tahapawal']['id'];
        $akhir = $request['tahapakhir']['id'];

        try{

            foreach($details as $dt){
                $cek = KegiatanAnggaran::where('kode', '=', $dt['kodesubsubkegiatan'])
                ->where('tahun', '=', $request['tahunakhir'])
                ->first();
                if(empty($cek)){
                    $newID = KegiatanAnggaran::max('id');

                    $KA = new KegiatanAnggaran();
                    $KA->id =  $newID + 1;
                    $KA->norec = $newID + 1;
                    $KA->kdprofile = $idProfile;
                    $KA->statusenabled = true;
                    $KA->kode = $dt['kodesubsubkegiatan'];
                    $KA->keterangan = $dt['keterangansubsubkegiatan'];
                    $KA->kodeexternal = null;
                    $KA->namaexternal = null;
                    $KA->reportdisplay = null;
                    $KA->tahun = $request['tahunakhir'];
                    $KA->indikatormasuk = $dt['indikatormasuk'];
                    $KA->indikatorkeluaran = $dt['indikatorkeluaran'];
                    $KA->indikatorhasil = $dt['indikatorhasil'];
                    $KA->targetmasuk = $dt['targetmasuk'];
                    $KA->targetkeluaran = $dt['targetkeluaran'];
                    $KA->targethasil = $dt['targethasil'];
                    $KA->div = $dt['div'];
                    $KA->objectjenisbelanjafk = $dt['objectjenisbelanjafk'];
                    $KA->objectpptkfk = $dt['objectpptkfk'];
                    $KA->save();

                    $KB= KeteranganBelanja::where('objectkegiatanfk',$dt['idsubsubkegiatan'])
                    ->where('objecttahapfk', $awal)
                    ->where('statusenabled', true)
                    ->get();
                
                    foreach($KB as $k){

                        $MA = new KeteranganBelanja();
                        $norecKS = $MA->generateNewId();
                        $MA->norec = $norecKS;
                        $MA->kdprofile = $idProfile;
                        $MA->statusenabled = true;
                        $MA->objectkegiatanfk = $KA->id;
                        $MA->objectmataanggaranfk = $k->objectmataanggaranfk;
                        $MA->keteranganbelanja = $k->keteranganbelanja;
                        $MA->nourut = $k->nourut;
                        $MA->jml = $k->jml;
                        $MA->hargasatuan = $k->hargasatuan;
                        $MA->subtotal = $k->subtotal;
                        $MA->satuan = $k->satuan;
                        $MA->objectasalprodukfk = $k->objectasalprodukfk;
                        $MA->password = $k->password;
                        $MA->objecttahapfk = $akhir;

                        $MA->save();
                    }
                }
            }

           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

                
       if ($transStatus == 'true') {
        $transMessage = "Simpan Berhasil";
        DB::commit();
        $result = array(
            'status' => 201,
            'result' => $transMessage,
            'as' => '@epic',
        );
    } else {
        $transMessage = "Simpan Gagal";
        DB::rollBack();
        $result = array(
            "status" => 400,
            "result"  => $e->getMessage() . ' '.$e->getline()
        );
    }
    return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getTotalKeterangan(Request $request){
        $idProfile = $this->kdProfile;

        $tahap = $request['tahap'];
        $rekening = $request['rekening'];
        $subsubkegiatan = '';

        if(isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != "undefined" && $request['subsubkegiatan'] != ""){
            $subsubkegiatan = 'and ka.id = '.$request['subsubkegiatan'];
        }

        $data = DB::select(DB::raw("
        select coalesce(sum(kb.subtotal),0) totalketerangan
        from kegiatananggaran_m ka 
        inner join keteranganbelanja_t kb on ka.id = kb.objectkegiatanfk
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        where kb.objecttahapfk = $tahap
        and ma.kodemataanggaran = '$rekening'
        $subsubkegiatan
        and kb.statusenabled = TRUE
        and kb.kdprofile = $idProfile    ")
        );

        $detail = DB::select(DB::raw("
        select coalesce(sum(rd.subtotal),0) totalrealisasidetail
        from kegiatananggaran_m ka 
        inner join keteranganbelanja_t kb on ka.id = kb.objectkegiatanfk
        inner join realisasidetail_t rd on rd.keteranganbelanjafk = kb.norec
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        where kb.objecttahapfk = $tahap
        and ma.kodemataanggaran = '$rekening'
        $subsubkegiatan
        and rd.statusenabled = true
        and rd.kdprofile = $idProfile;
            ")
        );
        $rincianbelanja = DB::select(DB::raw("
        select kb.keteranganbelanja, kb.norec, kb.password
        from kegiatananggaran_m ka 
        inner join keteranganbelanja_t kb on ka.id = kb.objectkegiatanfk
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        where kb.objecttahapfk = $tahap
        and ma.kodemataanggaran = '$rekening'
        $subsubkegiatan
        and kb.statusenabled = TRUE
        and kb.kdprofile = $idProfile    ")
        );
        // $panjar = DB::select(DB::raw("select pj.norec, pj.jumlah, coalesce(pj.jumlahpengembalian, 0) jumlahpengembalian
        // from kegiatananggaran_m ka 
        // inner join keteranganbelanja_t kb on ka.id = kb.objectkegiatanfk
        // inner join realisasidetail_t rd on rd.keteranganbelanjafk = kb.norec
        // inner join strukrealisasi_t sr on sr.norec = rd.strukrealisasifk
        // inner join panjar_t pj on pj.norec =  sr.objectpanjarfk
        // inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        // where kb.objecttahapfk = $tahap
        // and ma.kodemataanggaran = '$rekening'
        // $subsubkegiatan
        // group by pj.norec, pj.jumlah, pj.jumlahpengembalian"));

        $result = array(
            'data' => $data,
            'detail' => $detail,
            'rincianbelanja'=>$rincianbelanja,
            // 'panjar' => $panjar,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    public function getRincianBelanja(Request $request) {
        $idProfile = $this->kdProfile;

        $tahap = $request['tahap'];
        $rekening = $request['rekening'];
        $norec = $request['rincian'];
        $subsubkegiatan = '';

        if(isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != "undefined" && $request['subsubkegiatan'] != ""){
            $subsubkegiatan = 'and ka.id = '.$request['subsubkegiatan'];
        }

        $data = DB::select(DB::raw("
        select coalesce(sum(kb.subtotal),0) totalketerangan
        from kegiatananggaran_m ka 
        inner join keteranganbelanja_t kb on ka.id = kb.objectkegiatanfk
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        where kb.objecttahapfk = $tahap
        and ma.kodemataanggaran = '$rekening'
        and kb.norec = '$norec'
        $subsubkegiatan
        and kb.statusenabled = TRUE
        and kb.kdprofile = $idProfile    ")
        );

        $detail = DB::select(DB::raw("
        select coalesce(sum(rd.subtotal),0) totalrealisasidetail
        from kegiatananggaran_m ka 
        inner join keteranganbelanja_t kb on ka.id = kb.objectkegiatanfk
        inner join realisasidetail_t rd on rd.keteranganbelanjafk = kb.norec
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        where kb.objecttahapfk = $tahap
        and ma.kodemataanggaran = '$rekening'
        and rd.keteranganbelanjafk = '$norec'
        $subsubkegiatan
        and rd.statusenabled = true
        and rd.kdprofile = $idProfile;
            ")
        );
        $result = array(
            'data' => $data,
            'detail' => $detail,
            // 'rincianbelanja'=>$rincianbelanja,
            // 'panjar' => $panjar,
            'message' => '@epic',
        );
        return $this->respond($result);

    }
    public function saveRealisasiSPJ(Request $request) {
        DB::beginTransaction();
        $idProfile = $this->kdProfile;
        $dataRealisasiDetail = [];
        $thn = date("Y", strtotime($request['tglrealisasi']) );
        $jumlahall = $request['totalbelanja'];
        $jumlahsimpan = 0;
        $ceksisapanjar = 'gak ada';

       try{
            $KM= MataAnggaran::where('kodemataanggaran',$request['objectmataanggaranfk'])
            ->where('statusenabled',true)
                    ->first();

            if ($request['norec']==''){
                $MA = new StrukRealisasi();
                $norecKS = $MA->generateNewId();
                $MA->norec = $norecKS;
                $MA->kdprofile = $idProfile;
                $MA->statusenabled = true;

                // $nos = $this->generateCodeBySeqTable(new StrukRealisasi, 'norealisasi', 6, ' ',$kdProfile);
                $runningNumber = RunningNumber::where('kegunaan','norealisasi')->first();
                $nomor = (int)$runningNumber->nomer_terbaru +1;
                $nos = str_pad((int)$nomor, 5, "0", STR_PAD_LEFT);

                RunningNumber::where('kegunaan','norealisasi')
                ->update(
                    [
                        'nomer_terbaru' => $nomor
                    ]
                );

                $noRealiasi = $nos.'/BPK/UP/1.02.2.22.0.00.01.0004/B02/'.$thn;
                $MA->norealisasi = $noRealiasi;
            }else{
                $MA= StrukRealisasi::where('norec',$request['norec'])
                    ->where('kdprofile', $idProfile)
                    ->first();
                    
                $jumlahall = (float)$request['totalbelanja'] - (float)$MA->totalbelanja;
            }
            if(isset($request['norealisasi'])){
                $MA->norealisasi = $request['norealisasi'];
            }
            $MA->tglrealisasi = $request['tglrealisasi'];
            $MA->status = null;//$request['status'];
            $MA->totalbelanja = $request['totalbelanja'];
            $MA->rekananfk = $request['rekananfk'];
            $MA->deskripsi = $request['deskripsi'];
            $MA->objectkegiatanfk = $request['objectkegiatanfk'];
            $MA->objectmataanggaranfk = $KM->id;
            $MA->objectcarabayarfk = $request['objectcarabayarfk'];
            $MA->objectasalprodukfk = $request['objectasalprodukfk'];
            $MA->bendaharafk = $request['bendaharafk'];
            $MA->penerimafk = $request['penerimafk'];
            $MA->pph = (float)$request['pph'];
            $MA->ppn = (float)$request['ppn'];
            $MA->objectpanjarfk = isset($request['norecpanjar'][0]) ? $request['norecpanjar'][0]['norecpanjar'] : null;
            $MA->save();
            $updateSpjPanjar = SPJtoPanjar::where('objectspjfk',$MA->norec)->update([
                'jumlahspj'=> $MA->totalbelanja
            ]);
            $norecStrukRealisasiJangInsertTableDetailNa = $MA->norec;

            $dataRealisasiDetail = $request['realisasidetail'];
            foreach($dataRealisasiDetail as $item){
                if(empty($item['norec'])){
                    $DT = new RealisasiDetail();
                    $norecDT = $DT->generateNewId();
                    $DT->norec = $norecDT;
                    $DT->kdprofile = $idProfile;
                    $DT->statusenabled = true;
                    $DT->strukrealisasifk = $norecStrukRealisasiJangInsertTableDetailNa;
                    $DT->uraian = $item['uraian'];
                    $DT->jml = $item['jml'];
                    $DT->satuan = $item['satuan'];
                    $DT->harga = $item['harga'];
                    $DT->subtotal = $item['subtotal'];
                    $DT->keteranganbelanjafk = $item['keteranganbelanjafk'];
                    $DT->save();
                } else if(!empty($item['norec'])){
                    $DT = RealisasiDetail::where('norec',$item['norec'])
                    ->where('kdprofile', $idProfile)
                    ->first();;
                    $DT->uraian = $item['uraian'];
                    $DT->jml = $item['jml'];
                    $DT->satuan = $item['satuan'];
                    $DT->harga = $item['harga'];
                    $DT->subtotal = $item['subtotal'];
                    $DT->save();
                } 
                
            }

            if($request['norecpanjar'] != null){
                if($jumlahall > 0){
                    for($i = 0; $i < count($request['norecpanjar']); $i++){

                        $npanjar = $request['norecpanjar'][$i]['norecpanjar'];
                        //cari total semua spj yang sudah dipakai di panjar itu
                        $sisaperpanjar = DB::select(DB::raw("select coalesce(jumlahpanjar,0) jumlahpanjar, coalesce(sum(jumlahspj),0) jumlahspj
                        from spjtopanjar_t 
                        where kdprofile = $this->kdProfile
                        and statusenabled = true
                        and objectspjfk != ''
                        and objectpanjarfk = '$npanjar'
                        group by jumlahpanjar"));
    
                        //jumlah panjar keseluruhan
                        $jumlah = DB::select(DB::raw("
                        select coalesce(pj.jumlah,0) jumlah 
                        from panjar_t pj
                        where pj.kdprofile = $this->kdProfile
                        and pj.statusenabled = true
                        and pj.norec = '$npanjar'
                        "));
    
                        //jumlah pengembalian
                        $pengembalian = DB::select(DB::raw("select coalesce(sum(jumlahpengembalian),0) jumlahpengembalian 
                        from(
                            select pp.jumlahpengembalian, pp.norec
                            from panjar_t pj
                            inner join pengembalianpanjar_t pp on pp.objectpanjarfk = pj.norec
                            where pj.kdprofile = $this->kdProfile
                            and pj.statusenabled = true
                            and pp.kdprofile = $this->kdProfile
                            and pp.statusenabled = true
                            and pj.norec = '$npanjar'
                            group by pp.jumlahpengembalian, pp.norec
                        ) as x
                        "));
    
                        $jumlahspj = 0;
                        if(isset($sisaperpanjar) && count($sisaperpanjar) != 0){
                            $jumlahspj = $sisaperpanjar[0]->jumlahspj;
                        }
    
                        $jumlahpanjar = 0;
                        if(isset($jumlah)){
                            $jumlahpanjar = $jumlah[0]->jumlah;
                        }
    
                        $jumlahkembali = 0;
                        if(isset($pengembalian) && count($pengembalian) != 0){
                            $jumlahkembali = $pengembalian[0]->jumlahpengembalian;
                        }
    
                        $jsisa = (float)$jumlahpanjar - (float)$jumlahspj - (float)$jumlahkembali;
                        
                        if($jsisa > 0){
                            if($jumlahall >= $jsisa){
                                $jumlahsimpan = $jsisa;
                                $jumlahall -= $jsisa;
                            } else{
                                $jumlahsimpan = $jumlahall;
                            }
        
                            $norecpanjar = $request['norecpanjar'][$i]['norecpanjar'];
        
                            $sisaperpanjar = DB::select(DB::raw("select jumlahpanjar, sum(jumlahspj) jumlahspj
                                from spjtopanjar_t 
                            where kdprofile = $this->kdProfile
                            and statusenabled = true
                            and objectpanjarfk = '$norecpanjar'
                            group by jumlahpanjar"));
        
                            $cek = SPJtoPanjar::where('objectpanjarfk', '=', $request['norecpanjar'][$i]['norecpanjar'])
                            ->where('objectspjfk', '=', $MA->norec)->where('statusenabled', '=', true)->first();
            
                            if(empty($cek)){
                                $SP = new SPJtoPanjar();
                                $norecKS = $SP->generateNewId();
                                $SP->norec = $norecKS;
                                $SP->kdprofile = $idProfile;
                                $SP->statusenabled = true;
                                $SP->objectpanjarfk = $request['norecpanjar'][$i]['norecpanjar'];
                                $SP->jumlahpanjar = $request['norecpanjar'][$i]['jumlah'];
                                $SP->nourut = $request['norecpanjar'][$i]['nourut'];
                                $SP->jumlahspj = $jumlahsimpan;
                                $SP->objectspjfk = $MA->norec;
            
                            } else{
                                $SP = SPJtoPanjar::where('objectpanjarfk', '=', $request['norecpanjar'][$i]['norecpanjar'])
                                ->where('objectspjfk', '=', $MA->norec)->where('statusenabled', '=', true)->first();
                                $SP->objectpanjarfk = $request['norecpanjar'][$i]['norecpanjar'];
                                $SP->jumlahpanjar = $request['norecpanjar'][$i]['jumlah'];
                                $SP->nourut = $request['norecpanjar'][$i]['nourut'];
                                $SP->jumlahspj += $jumlahsimpan;
                                $SP->objectspjfk = $MA->norec;
                                
                            }
                            $SP->save();
                        }
                    }
                } else{
                    $jumlahall = $jumlahall * (-1);
                    for($i = count($request['norecpanjar'])-1; $i >= 0; $i--){
                        $SP = SPJtoPanjar::where('objectpanjarfk', '=', $request['norecpanjar'][$i]['norecpanjar'])
                            ->where('objectspjfk', '=', $MA->norec)->where('statusenabled', '=', true)->first();
                        if($jumlahall >= $SP->jumlahspj){
                            $SP->jumlahspj -= $SP->jumlahspj;
                            $jumlahall -= $SP->jumlahspj;
                        } else{
                            $SP->jumlahspj -= $jumlahall;
                            $jumlahall -= $jumlahall;
                        }

                        $SP->save();
                    }
                }
                
            }
            $transStatus = 'true';
            
       } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Simpan Gagal";
       }

       if ($transStatus == 'true') {
        $transMessage = "Simpan Berhasil";
        DB::commit();
        $result = array(
            'status' => 201,
            'result' => $transMessage,
            'as' => '@epic',
        );
    } else {
        $transMessage = "Simpan Gagal";
        DB::rollBack();
        $result = array(
            "status" => 400,
            "result"  => $e->getMessage() . ' '.$e->getline()
        );
    }
    return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveRealisasiSPJVerif(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;
        $dataRealisasiDetail = [];
        $thn = date("Y", strtotime($request['tglrealisasi']) );
        $jumlahall = $request['totalbelanja'];
        $jumlahsimpan = 0;
        $ceksisapanjar = 'gak ada';

       try{
            $MA= StrukRealisasi::where('norec',$request['norec'])
            ->where('kdprofile', $idProfile)
            ->first();
            $MA->objectcarabayarfk = 2;
            $MA->save();

            if($request['norecpanjar'] != null){
                if($jumlahall > 0){
                    for($i = 0; $i < count($request['norecpanjar']); $i++){

                        $npanjar = $request['norecpanjar'][$i]['norecpanjar'];
                        //cari total semua spj yang sudah dipakai di panjar itu
                        $sisaperpanjar = DB::select(DB::raw("select coalesce(jumlahpanjar,0) jumlahpanjar, coalesce(sum(jumlahspj),0) jumlahspj
                        from spjtopanjar_t 
                        where kdprofile = $this->kdProfile
                        and statusenabled = true
                        and objectspjfk != ''
                        and objectpanjarfk = '$npanjar'
                        group by jumlahpanjar"));
    
                        //jumlah panjar keseluruhan
                        $jumlah = DB::select(DB::raw("
                        select coalesce(pj.jumlah,0) jumlah 
                        from panjar_t pj
                        where pj.kdprofile = $this->kdProfile
                        and pj.statusenabled = true
                        and pj.norec = '$npanjar'
                        "));
    
                        //jumlah pengembalian
                        $pengembalian = DB::select(DB::raw("select coalesce(sum(jumlahpengembalian),0) jumlahpengembalian 
                        from(
                            select pp.jumlahpengembalian, pp.norec
                            from panjar_t pj
                            inner join pengembalianpanjar_t pp on pp.objectpanjarfk = pj.norec
                            where pj.kdprofile = $this->kdProfile
                            and pj.statusenabled = true
                            and pp.kdprofile = $this->kdProfile
                            and pp.statusenabled = true
                            and pj.norec = '$npanjar'
                            group by pp.jumlahpengembalian, pp.norec
                        ) as x
                        "));
    
                        $jumlahspj = 0;
                        if(isset($sisaperpanjar) && count($sisaperpanjar) != 0){
                            $jumlahspj = $sisaperpanjar[0]->jumlahspj;
                        }
    
                        $jumlahpanjar = 0;
                        if(isset($jumlah)){
                            $jumlahpanjar = $jumlah[0]->jumlah;
                        }
    
                        $jumlahkembali = 0;
                        if(isset($pengembalian) && count($pengembalian) != 0){
                            $jumlahkembali = $pengembalian[0]->jumlahpengembalian;
                        }
    
                        $jsisa = (float)$jumlahpanjar - (float)$jumlahspj - (float)$jumlahkembali;
                        
                        if($jsisa > 0){
                            if($jumlahall >= $jsisa){
                                $jumlahsimpan = $jsisa;
                                $jumlahall -= $jsisa;
                            } else{
                                $jumlahsimpan = $jumlahall;
                            }
        
                            $norecpanjar = $request['norecpanjar'][$i]['norecpanjar'];
        
                            $sisaperpanjar = DB::select(DB::raw("select jumlahpanjar, sum(jumlahspj) jumlahspj
                                from spjtopanjar_t 
                            where kdprofile = $this->kdProfile
                            and statusenabled = true
                            and objectpanjarfk = '$norecpanjar'
                            group by jumlahpanjar"));
        
                            $cek = SPJtoPanjar::where('objectpanjarfk', '=', $request['norecpanjar'][$i]['norecpanjar'])
                            ->where('objectspjfk', '=', $MA->norec)->where('statusenabled', '=', true)->first();
            
                            if(empty($cek)){
                                $SP = new SPJtoPanjar();
                                $norecKS = $SP->generateNewId();
                                $SP->norec = $norecKS;
                                $SP->kdprofile = $idProfile;
                                $SP->statusenabled = true;
                                $SP->objectpanjarfk = $request['norecpanjar'][$i]['norecpanjar'];
                                $SP->jumlahpanjar = $request['norecpanjar'][$i]['jumlah'];
                                $SP->nourut = $request['norecpanjar'][$i]['nourut'];
                                $SP->jumlahspj = $jumlahsimpan;
                                $SP->objectspjfk = $MA->norec;
            
                            } else{
                                $SP = SPJtoPanjar::where('objectpanjarfk', '=', $request['norecpanjar'][$i]['norecpanjar'])
                                ->where('objectspjfk', '=', $MA->norec)->where('statusenabled', '=', true)->first();
                                $SP->objectpanjarfk = $request['norecpanjar'][$i]['norecpanjar'];
                                $SP->jumlahpanjar = $request['norecpanjar'][$i]['jumlah'];
                                $SP->nourut = $request['norecpanjar'][$i]['nourut'];
                                $SP->jumlahspj += $jumlahsimpan;
                                $SP->objectspjfk = $MA->norec;
                                
                            }
                            $SP->save();
                        }
                    }
                } else{
                    $jumlahall = $jumlahall * (-1);
                    for($i = count($request['norecpanjar'])-1; $i >= 0; $i--){
                        $SP = SPJtoPanjar::where('objectpanjarfk', '=', $request['norecpanjar'][$i]['norecpanjar'])
                            ->where('objectspjfk', '=', $MA->norec)->where('statusenabled', '=', true)->first();
                        if($jumlahall >= $SP->jumlahspj){
                            $SP->jumlahspj -= $SP->jumlahspj;
                            $jumlahall -= $SP->jumlahspj;
                        } else{
                            $SP->jumlahspj -= $jumlahall;
                            $jumlahall -= $jumlahall;
                        }

                        $SP->save();
                    }
                }
                
            }
            $transStatus = 'true';
            
       } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Simpan Gagal";
       }

       if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function savePanjar(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;
        $tgl = date("n", strtotime($request['tglPanjar']) );
        $thn = date("Y", strtotime($request['tglPanjar']) );
        $kode = $request['mataanggaran'];
        $ma = DB::select(DB::raw("select id from mataanggaran_m where kodemataanggaran = '$kode' and statusenabled = true and kdprofile = $idProfile"));
        try{

            if($request['norec'] == ''){

                $MA = new Panjar();
                $norecKS = $MA->generateNewId();
                $MA->norec = $norecKS;
                $MA->kdprofile = $idProfile;
                $MA->statusenabled = true;
                // $nos = $this->generateCodeBySeqTable(new Panjar, 'nopanjar', 6, ' ',$kdProfile);

                $runningNumber = RunningNumber::where('id',13541)->first();
                $nomor = $runningNumber->nomer_terbaru +1;
                $nos = str_pad((int)$nomor, 5, "0", STR_PAD_LEFT);

                RunningNumber::where('kegunaan', 'nopanjar')
                ->update(
                    [
                        'nomer_terbaru' => $nos
                    ]
                );

                $noPanjar = $nos.'/PANJAR/1.02.2.22.0.00.01.0004/'.$tgl.'/'.$thn;
                $MA->nopanjar = $noPanjar;
                
                $MA->objectsubkegiatanfk = $request['subkegiatan'];
                $MA->objectsubsubkegiatanfk = $request['subsusbkegiatan'];
                $MA->objectmataanggaranfk = $ma[0]->id;
                
            } else{
                $MA = Panjar::where('norec', '=', $request['norec'])->first();
                $MA->nopanjar = $request['nopanjar'];
            }
                $MA->tglpanjar = $request['tglPanjar'];
                $MA->uraian = $request['uraianPanjar'];
                $MA->jumlah = $request['jumlahpanjar'];
                $MA->sumberpanjar = $request['sumberPanjar'];
                $MA->idbill = $request['idbill'];
                $MA->ntpn = $request['ntpn'];
                $MA->idbillpph = $request['idbillpph'];
                $MA->ntpnpph = $request['ntpnpph'];
                $MA->objectjenispphfk = $request['objectjenispphfk'];
                $MA->pph = $request['pph'];
                $MA->ppn = $request['ppn'];
                $MA->idbillpph2 = $request['idbillpph2'];
                $MA->ntpnpph2 = $request['ntpnpph2'];
                $MA->objectjenispphfk2 = $request['objectjenispphfk2'];
                $MA->pph2 = $request['pph2'];
                $MA->save();

           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

       if ($transStatus == 'true') {
        $transMessage = "Simpan Berhasil";
        DB::commit();
        $result = array(
            'status' => 201,
            'result' => $transMessage,
            'as' => 'Fizi@epic',
        );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataPanjar(Request $request) {
        $tglpanjardate = "";
        $thnpanjar = "";
        if(isset($request['tglAwal']) && isset($request['tglAkhir'])){
            $tglpanjardate = " and pj.tglpanjar::date between '$request[tglAwal]' and '$request[tglAkhir]'";

        }
        $panjar = DB::select("select kodesubkegiatan, kodesubsubkegiatan,  ntpn, idbill, ppn, pph2,ntpnpph2,idbillpph2,jenispajak2,idpajak2, ntpnpph, idbillpph, pph, jenispajak, idpajak, namamataanggaran, objectbkufk,idmataanggaran, kodemataanggaran, idsubkegiatan, subkegiatan, idsubsubkegiatan, subsubkegiatan,
        norec, nopanjar, uraian, tglpanjar, jumlah, jenispanjar, norealisasi, coalesce(jumlahspj, 0) jumlahspj, sumberpanjar,
        coalesce(sum(jumlahpengembalian), 0) jumlahpengembalian, coalesce(jumlahspj, 0) + coalesce(sum(jumlahpengembalian), 0) totalpemakaian,
        coalesce(jumlah, 0) - (coalesce(jumlahspj, 0) + coalesce(sum(jumlahpengembalian), 0)) selisih
        from(
                select pj.ntpn, pj.idbill, pj.ppn, pj.pph2, pj.ntpnpph2, pj.idbillpph2, jp2.jenispajak as jenispajak2, pj.objectjenispphfk2 as idpajak2, pj.ntpnpph, pj.idbillpph, jp.jenispajak, pj.objectjenispphfk as idpajak, ma.namamataanggaran, ma.id idmataanggaran, ma.kodemataanggaran, subkeg.id idsubkegiatan, 
                subkeg.keterangan subkegiatan, subsubkeg.id idsubsubkegiatan, subsubkeg.kode kodesubsubkegiatan,subsubkeg.keterangan subsubkegiatan, subkeg.kode as kodesubkegiatan,
                pj.norec, pj.nopanjar, pj.uraian, pj.tglpanjar, pj.jumlah, pj.jenispanjar, pj.pph,
                string_agg(DISTINCT sr.norealisasi, ',') norealisasi,
                sum(stp.jumlahspj) as jumlahspj, pj.sumberpanjar, pj.objectbkufk,
                pp.jumlahpengembalian, pp.norec norecpengembalian
                from panjar_t pj
                inner join mataanggaran_m ma on ma.id = pj.objectmataanggaranfk
                inner join kegiatananggaran_m subkeg on subkeg.id = pj.objectsubkegiatanfk
                inner join kegiatananggaran_m subsubkeg on subsubkeg.id = pj.objectsubsubkegiatanfk
                left join jenispajak_m jp on jp.id = pj.objectjenispphfk
                left join jenispajak_m jp2 on jp2.id = pj.objectjenispphfk2
                left join spjtopanjar_t stp on stp.objectpanjarfk = pj.norec
                and stp.kdprofile = $this->kdProfile
                and stp.statusenabled = true
                left join strukrealisasi_t sr on stp.objectspjfk = sr.norec
                and sr.kdprofile = $this->kdProfile
                and sr.statusenabled = true
                left join pengembalianpanjar_t pp on pp.objectpanjarfk = pj.norec
                and pp.kdprofile = $this->kdProfile
                and pp.statusenabled = true
                where pj.kdprofile = $this->kdProfile
                $thnpanjar
                $tglpanjardate
                and pj.statusenabled = true
                group by pj.ntpn, pj.idbill, pj.ppn, pj.pph2, pj.ntpnpph2, pj.idbillpph2, jp2.jenispajak, pj.objectjenispphfk2, pj.ntpnpph,pj.idbillpph, pj.pph, jp.jenispajak, pj.objectjenispphfk,ma.namamataanggaran, ma.id, ma.kodemataanggaran, subkeg.id, subkeg.keterangan, 
                subsubkeg.id, subsubkeg.keterangan, pj.norec, pj.nopanjar, pj.uraian, pj.tglpanjar, 
                pj.jumlah, pj.jenispanjar, pj.sumberpanjar, pp.jumlahpengembalian, pp.norec,pj.objectbkufk
                order by pj.nopanjar
        ) as x 
        group by ntpn, idbill, ppn, pph2,ntpnpph2,idbillpph2,jenispajak2,idpajak2,  ntpnpph, idbillpph, pph, jenispajak, idpajak, namamataanggaran, objectbkufk, idmataanggaran, kodemataanggaran, idsubkegiatan, subkegiatan, idsubsubkegiatan, subsubkegiatan,
        norec, nopanjar, uraian, tglpanjar, jumlah, jenispanjar, norealisasi, jumlahspj, sumberpanjar, kodesubkegiatan, kodesubsubkegiatan
        order by nopanjar asc");
        $offset = $request['offset']; // misalnya 1
        $limit = $request['limit']; // misalnya 10
        $data = collect($panjar)->splice($offset, $limit)->all();
        // if (isset($request['offset']) && $request['offset'] != '') {
        //     $data = collect($panjar)->offset($request['offset']);
        // }
        // if (isset($request['limit']) && $request['limit'] != '') {
        //     $data = collect($panjar)->limit($request['limit']);
        // }
        // $data = $data->get();
            $total = count($panjar);
        $result = array(
            'data'=> $data,
            'total'=> $total,
        );
        return $this->respond($result);
    }

    public function saveSPJPanjar(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;
        $data = $request['data'];
        $nourut = 1;
        try{
            foreach ($data as $dt) {
                $cek = DB::select(DB::raw("select nourut
                from spjtopanjar_t
                where kdprofile = $this->kdProfile
                and statusenabled = true
                and objectspjfk = '$dt[objectspjfk]'
                order by nourut desc
                limit 1"));

                if(!empty($cek)){
                    $nourut = $cek[0]->nourut+1;
                }

                $MA = new SPJtoPanjar();
                $norecKS = $MA->generateNewId();
                $MA->norec = $norecKS;
                $MA->kdprofile = $idProfile;
                $MA->statusenabled = true;
                $MA->objectpanjarfk = $dt['norec'];
                $MA->objectspjfk = $dt['objectspjfk'];
                $MA->nourut = $nourut;
                $MA->jumlahspj = $dt['jumlahspj'];
                $MA->jumlahpanjar = $dt['jumlah'];
                $MA->save();

            }
           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDataSPJ(Request $request){
        $idProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];

        $pegawai = '';
        if(isset($request['idpegawai']) && $request['idpegawai'] != "undefined" && $request['idpegawai'] != ''){
            $pegawai = "and ka.objectpptkfk = ".$request['idpegawai'];
        }

        $status = '';
        if(isset($request['status']) && $request['status'] != "undefined" && $request['status'] != '' && $request['status'] == 0){
            $status = "";
        } else if(isset($request['status']) && $request['status'] != "undefined" && $request['status'] != '' && $request['status'] == 1){
            $status = "and (sr.status is null or sr.status = 1)";
        } else if(isset($request['status']) && $request['status'] != "undefined" && $request['status'] != '' && $request['status'] == 2){
            $status = "and sr.status = 2";
        } else if(isset($request['status']) && $request['status'] != "undefined" && $request['status'] != '' && $request['status'] == 3){
            $status = "and sr.status = 3";
        }
        $limit = '';
        if (isset($request['limit']) && $request['limit'] != '') {
            $limit = "limit ".$request['limit'];
        }
        $filter = '';
        if (isset($request['filter']) && $request['filter'] != '') {
            $filter = "and (sr.norealisasi ilike '%$request[filter]%' or ka.keterangan ilike '%$request[filter]%' or sr.totalbelanja = '$request[filter]')";
        }
        $data = DB::select(DB::raw("
        select sr.*, cbs.id, cbs.carabayar, sr.tglverif, sr.keterangan, sr.jumlahdibayarverif, ka.tahun,--sr.norealisasi,sr.deskripsi,sr.totalbelanja,
                pg1.namalengkap as bendahara, pg2.namalengkap as penerima, rkn.namarekanan as pihakketiga,
                ka.kode || '~' || ka.keterangan as kegiatan, ma.kodemataanggaran || '~' || ma.namamataanggaran as mataanggaran,
                dp0.kode kodesubkegiatan, dp0.keterangan keterangansubkegiatan, ma.kodemataanggaran, ma.namamataanggaran, dp0.id idsubkegiatan, ka.keterangan subsubkegiatan, ka.id idsubsubkegiatan,
                ap.id idasalproduk, ap.asalproduk, sr.objectbkufk
                from strukrealisasi_t sr

                left join asalproduk_m ap on ap.id = sr.objectasalprodukfk
                left JOIN kegiatananggaran_m ka on ka.id=sr.objectkegiatanfk
                and ka.kdprofile = $this->kdProfile and ka.statusenabled=true
                inner join kegiatananggaran_m as dp0 on ka.kode ilike dp0.kode || '%' and dp0.div = 3
                and dp0.kdprofile = $this->kdProfile and dp0.statusenabled=true
                left JOIN rekanan_m rkn on rkn.id=sr.rekananfk
                left JOIN pegawai_m as pg1 on pg1.id=sr.bendaharafk
                left JOIN pegawai_m as pg2 on pg2.id=sr.penerimafk
                left join carabayarspj_m cbs on cbs.id = sr.objectcarabayarfk
                left JOIN mataanggaran_m ma on ma.id=sr.objectmataanggaranfk::int2
                where sr.tglrealisasi between '$tglAwal' and '$tglAkhir'
                and sr.statusenabled = true
              
                $pegawai
                $status
                $filter
				group by cbs.id, dp0.kode, dp0.keterangan, sr.norec, cbs.carabayar, sr.tglverif, sr.keterangan, sr.jumlahdibayarverif, sr.kdprofile, sr.statusenabled, sr.tglrealisasi, sr.norealisasi, sr.status, sr.totalbelanja, sr.objectcarabayarfk, sr.rekananfk, sr.deskripsi, sr.objectkegiatanfk, sr.objectmataanggaranfk, sr.bendaharafk, sr.penerimafk, sr.pph, sr.ppn, pg1.namalengkap, pg2.namalengkap, rkn.namarekanan ,
                ka.kode || '~' || ka.keterangan, ma.kodemataanggaran || '~' || ma.namamataanggaran, ka.tahun, ma.kodemataanggaran, ma.namamataanggaran, dp0.id, ka.keterangan, ka.id, ap.id, ap.asalproduk, sr.objectbkufk
                order by sr.norealisasi desc
                
            ")
        );
        $result = [];
        $strukrealisasifk ='';
        $dataDetail = DB::select(DB::raw("
                    select rd.*,ka.keteranganbelanja,0 as nourut
                    from strukrealisasi_t sr
                inner join realisasidetail_t rd on rd.strukrealisasifk = sr.norec
                    INNER JOIN keteranganbelanja_t ka on ka.norec=rd.keteranganbelanjafk
                    where  rd.statusenabled = true
                    and rd.kdprofile = $this->kdProfile
                    and ka.statusenabled = true
                    and ka.kdprofile = $this->kdProfile
                    and sr.tglrealisasi between  '$tglAwal' and '$tglAkhir'
                ")
            );
        $dataDetail1=[];
        foreach($data as $item){
            $item->status_c = $item->status == 3 ? 'success' : ($item->status == 1 || $item->status == null? 'danger' : 'warning') ;
            $item->status_name = $item->status == 3 ? 'Sudah Verifikasi' : ($item->status == 1 || $item->status == null? 'Belum Verifikasi' : 'Revisi') ;
            // $strukrealisasifk= ;
            $dataDetail1 =[];
            foreach($dataDetail as $detail){
                if($item->norec == $detail->strukrealisasifk){
                    $item->details[]=array(
                        'norec'=> $detail->norec,
                        'kdprofile'	=> $detail->kdprofile,
                        'statusenabled'	=> $detail->statusenabled,
                        'strukrealisasifk'	=> $detail->strukrealisasifk,
                        'uraian'	=> $detail->uraian,
                        'jml'	=> $detail->jml,
                        'satuan'	=> $detail->satuan,
                        'harga'	=> $detail->harga,
                        'subtotal'	=> $detail->subtotal,
                        'keteranganbelanjafk'	=> $detail->keteranganbelanjafk,
                        'keteranganbelanja'	=> $detail->keteranganbelanja,
                        'nourut'	=> $detail->nourut
                    );
                }
                
            }
            
          
        };

        $offset = $request['offset']; // misalnya 1
        $limit = $request['limit']; // misalnya 10
        $data1 = collect($data)->splice($offset, $limit)->all();
        // if (isset($request['offset']) && $request['offset'] != '') {
        //     $data = collect($panjar)->offset($request['offset']);
        // }
        // if (isset($request['limit']) && $request['limit'] != '') {
        //     $data = collect($panjar)->limit($request['limit']);
        // }
        // $data = $data->get();
            $total = count($data);
        $result = array(
            'data' => $data1,
            'total' => $total,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    public function deleteRincianBelanja(Request $request) {
        $idProfile = $this->kdProfile;
        $dataLogin = $request->all();
        $norec = '';
        DB::beginTransaction();
        try{
            if ($request['norec'] != ''){
                $data1 = RealisasiDetail::where('norec', $request['norec'])->where('kdprofile', $idProfile)->first();
                $norec = $data1->strukrealisasifk;
                $data1->statusenabled = false;
                $data1->save();
                
                $update = DB::select(DB::raw("select sum(rd.subtotal) total from strukrealisasi_t sr
                inner join realisasidetail_t rd on sr.norec = rd.strukrealisasifk
                where sr.norec = '$norec'
                and rd.statusenabled = true and rd.kdprofile = $this->kdProfile
                and sr.statusenabled = true and sr.kdprofile = $this->kdProfile"));

                $spj = StrukRealisasi::where('norec', $norec)->where('kdprofile', $idProfile)->first();
                $spj->totalbelanja = $update[0]->total;
                $spj->save();

                
            }
            $transStatus = 'true';
        }
        catch(\Exception $e){
            $transStatus= 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveVerifSPJ(Request $request) {
        DB::beginTransaction();
        $idProfile = $this->kdProfile;

        try{
            $KB= StrukRealisasi::where('norec',$request['norec'])
                ->first();
            $KB->status = $request['statusverif'];
            $KB->tglverif = $request['tglverif'];
            $KB->keterangan = $request['keterangan'];
            $KB->jumlahdibayarverif = $request['jumlahdibayarverif'];
            $KB->save();
            $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

       if ($transStatus == 'true') {
        $transMessage = "Verifikasi Berhasil";
        DB::commit();
        $result = array(
            'status' => 201,
            'result' => $transMessage,
            'as' => '@epic',
        );
    } else {
        $transMessage = "Verifikasi Gagal";
        DB::rollBack();
        $result = array(
            "status" => 400,
            "result"  => $e->getMessage() . ' '.$e->getline()
        );
    }
    return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function batalVerifSPJ(Request $request) {
        DB::beginTransaction();
        $idProfile = $this->kdProfile;

        try{
            $KB= StrukRealisasi::where('norec',$request['norec'])
                ->first();
            $KB->status = null;
            $KB->tglverif = null;
            $KB->keterangan = null;
            $KB->jumlahdibayarverif = null;
            $KB->save();
            $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

        if ($transStatus == 'true') {
            $transMessage = "Batal Verifikasi Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Batal Verifikasi Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteSPJ(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;

        try{
            $cek= StrukRealisasi::where('norec',$request['norec'])
                ->whereNotNull('tglverif')
                ->first();

            if(empty($cek)){
                $KB= StrukRealisasi::where('norec',$request['norec'])
                    ->first();
                $KB->statusenabled = false;
                $KB->save();

                $data = \DB::table('realisasidetail_t')->where('strukrealisasifk', $request['norec'])->where('kdprofile', $idProfile)->update([
                    'statusenabled'=> false
                ]);

                $transStatus = 'true';
            } else{
                $transMessage = "Data Yang Sudah Diverif Tidak Bisa Dihapus";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result"  => $transMessage
                    );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

       
       if ($transStatus == 'true') {
            $transMessage = "Hapus SPJ Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Hapus SPJ Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function hapusPanjar(Request $request) {
        DB::beginTransaction();
        try{
            $MA = Panjar::where('norec', '=', $request['data'])->first();
            $MA->statusenabled = false;
            $MA->save();

           $transStatus = 'true';
        } catch (\Exception $e) {
                $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus SPJ Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Hapus SPJ Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getPengembalianPanjar(Request $request){
       
        $norec = $request['norec'];

        $data = DB::select(DB::raw("
        select pj.nopengembalian, ma.id idmataanggaran, ma.kodemataanggaran, subkeg.id idsubkegiatan, subkeg.keterangan subkegiatan, subsubkeg.id idsubsubkegiatan, subsubkeg.keterangan subsubkegiatan, pj.norec, pj.tglpengembalian, pj.jumlahpengembalian, pj.keterangan
        from pengembalianpanjar_t pj
        inner join mataanggaran_m ma on ma.id = pj.objectmataanggaranfk
        inner join kegiatananggaran_m subkeg on subkeg.id = pj.objectsubkegiatanfk
        inner join kegiatananggaran_m subsubkeg on subsubkeg.id = pj.objectsubsubkegiatanfk
        where pj.kdprofile = $this->kdProfile
        and pj.statusenabled = true
        and pj.objectpanjarfk = '$norec'")
        );

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    public function savePengembalian(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;
        $thn = date("Y", strtotime($request['tglpengembalian']) );
        $tgl = date("n", strtotime($request['tglpengembalian']) );

        try{
            if($request['norec'] == ''){
                $MA = new Pengembalian();
                $MA->norec =  $MA->generateNewId();
                $MA->kdprofile = $idProfile;
                $MA->statusenabled = true;
                $runningNumber = RunningNumber::where('kegunaan','nopengembalian')->first();
                $nomor = (int)$runningNumber->nomer_terbaru +1;
                $nos = str_pad((int)$nomor, 5, "0", STR_PAD_LEFT);

                RunningNumber::where('kegunaan','norealisasi')
                ->update(
                    [
                        'nomer_terbaru' => $nomor
                    ]
                );
                $noPengembalian = $nos.'/PJR/1.02.2.22.0.00.01.0004/'.$tgl.'/'.$thn;
                $MA->nopengembalian = $noPengembalian;
                
            } else{
                $MA = Pengembalian::where('norec', '=', $request['norec'])->first();
            }
                $MA->tglpengembalian = $request['tglpengembalian'];
                $MA->objectpanjarfk = $request['norecpanjar'];
                $MA->jumlahpengembalian = $request['jumlahpengembalian'];
                $MA->keterangan = $request['keterangan'];
                $MA->objectsubkegiatanfk = $request['idsubkegiatan'];
                $MA->objectsubsubkegiatanfk = $request['idsubsubkegiatan'];
                $MA->objectmataanggaranfk = $request['idmataanggaran'];
                $MA->save();
                
           $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }

       if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataPanjarSPJ(Request $request) {
        $data = \DB::table('panjar_t as pj')
        // ->leftjoin('spjtopanjar_t as stp','stp.objectpanjarfk','=','pj.norec')
        ->leftJoin('spjtopanjar_t as stp', function($join) use ($request) {
            $join->on('stp.objectpanjarfk', '=', 'pj.norec')
                 ->where('stp.objectspjfk', '=', $request['norec_stp']);
        })
        ->where('pj.objectmataanggaranfk',$request['objectmataanggaranfk'])
        ->where('pj.objectsubkegiatanfk',$request['objectsubkegiatanfk'])
        ->where('pj.objectsubsubkegiatanfk',$request['objectsubsubkegiatanfk'])
        ->where('pj.kdprofile', $this->kdProfile)
        ->where('pj.statusenabled', true)
        ->select('pj.*','stp.norec as norec_stp')
        ->get();
        foreach ($data as $dt) {
            if($dt->norec_stp == null){
                $dt->ispilihpanjar = false;
            }else{
                $dt->ispilihpanjar = true;
            }
        }
        $result = array(
            'data'=> $data
        );
        return $this->respond($result);
    }
    public function getComboSPP(Request $request) {
        $dataAsalProduk = \DB::table('asalproduk_m as ap')
        ->select('ap.id','ap.asalproduk')
        ->where('ap.statusenabled',true)
        ->where('kdprofile', $this->kdProfile)
        ->where('ap.isanggaran',true)
        ->orderBy('ap.asalproduk')
        ->get();
        $result = array(
            'asalproduk'=> $dataAsalProduk
        );
        return $this->respond($result);
    }
    public function saveSPD(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;

        try{
            if($request['norec'] == ''){
                $MA = new SPD();
                $norecKS = $MA->generateNewId();
                $MA->norec = $norecKS;
                $MA->kdprofile = $idProfile;
                $MA->statusenabled = true;
            } else{
                $MA = SPD::where('norec', '=', $request['norec'])->first();
            }
                $MA->nospd = $request['nospd'];
                $MA->tglspd = $request['tglspd'];
                $MA->nilaispd = $request['nilaispd'];
                $MA->objectasalprodukfk = $request['objectasalprodukfk'];

                $MA->save();

           $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataSPD(Request $request) {
        $spd = DB::select(DB::raw("select spd.*, spp.nospp, ap.id idasalproduk, ap.asalproduk from spd_t spd 
        left join spp_t spp on spp.objectspdfk = spd.norec and spp.statusenabled = true 
        left join asalproduk_m ap on ap.id = spd.objectasalprodukfk and ap.statusenabled = true 
        where spd.statusenabled = true"));
        $result = array(
            'data'=> $spd
        );
        return $this->respond($result);
    }

    public function saveSPP(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;

        $thn = date("Y", strtotime($request['tglspp']) );
        $tgl = $request['tglspp'];
        try{

        if($request['norec'] == ''){
            $SP = SPD::where('nospd', '=', $request['nospd'])->first();        
            $MA = new SPP();
            $norecKS = $MA->generateNewId();
            $MA->norec = $norecKS;
            $MA->kdprofile = $idProfile;
            $MA->statusenabled = true;

            // $nos = $this->generateCodeBySeqTableSPP(new SPP, 'nospp', 6, '/',$kdProfile, $thn, $tgl);

            $runningNumber = RunningNumber::where('kegunaan','nospp')->first();
            $nomor = (int)$runningNumber->nomer_terbaru +1;
            $nos = str_pad((int)$nomor, 5, "0", STR_PAD_LEFT);

            RunningNumber::where('kegunaan','nospp')
            ->update(
                [
                    'nomer_terbaru' => $nos
                ]
            );
            $noSPP = '04.00/02.0/'.$nos.'/LS/1.02.2.22.0.00.01.0004/M/1/'.$thn;
            // return $noSPP;
            $MA->nospp = $noSPP;
        } else{
            $SP = SPD::where('nospd', '=', $request['nospd'])->first(); 
            $MA = SPP::where('norec', '=', $request['norec'])->first();
        }
            $MA->jenisbukti = $request['jenisbukti'];
            $MA->dasarpengeluaran = $request['dasarpengeluaran'];
            $MA->status = $request['statusspp'];
            $MA->tglspp = $request['tglspp'];
            $MA->bulan = $request['bulan'];
            $MA->pengeluaran = $request['pengeluaran'];
            $MA->tglsah = $request['tglsah'];
            $MA->nilaispp = $request['nilaispp'];
            $MA->objectasalprodukfk = $request['objectasalprodukfk'];
            if(empty($SP)){
                $MA->objectspdfk = null;
            } else{
                $MA->objectspdfk = $SP->norec;
            }

            $MA->save();

           
           $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataSPP(Request $request) {
        $tglsppdate = "";
        if(isset($request['tglsppawal']) && isset($request['tglsppakhir'])){
            $tglsppdate = " and spp.tglspp between '$request[tglsppawal]' and '$request[tglsppakhir]'";

        }
        $spp = DB::select(DB::raw("select spp.*, spd.nospd ,spd.nilaispd, ap.id idasalproduk, ap.asalproduk
        from spp_t spp 
        left join spd_t spd on spd.norec = spp.objectspdfk and spd.statusenabled = true 
        left join asalproduk_m ap on ap.id = spp.objectasalprodukfk and ap.statusenabled = true 
        where spp.statusenabled = true $tglsppdate"));
        $result = array(
            'data'=> $spp
        );
        return $this->respond($result);
    }
    public function DeleteSPP(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;

        try{
            $KB= SPP::where('norec',$request['data'])->first();
            $KB->statusenabled = false;
            $KB->save();

            $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }
       if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    
    public function getDataSPM(Request $request) {
        $tglspmdate = '';
        $nospp = '';
        if(isset($request['tglAwal']) && isset($request['tglAkhir'])){
            $tglspmdate = " and spp.tglspp::date between '$request[tglAwal]' and '$request[tglAkhir]'";

        }
        if(isset($request['nospp'])){
            $nospp = " and spp.nospp = '$request[nospp]'";

        }
        $carispm = DB::select(DB::raw("select spm.*, spp.nospp, spp.tglspp, ap.id idasalproduk, ap.asalproduk
        from spm_t spm
        left join spp_t spp on spp.norec = spm.objectsppfk
        left join asalproduk_m ap on ap.id = spm.objectasalprodukfk and ap.statusenabled = true 
        and spp.statusenabled = true
        where spm.statusenabled = true $tglspmdate $nospp"));

        $result = array(
            'data'=> $carispm
        );
        return $this->respond($result);
    }
    public function saveSPM(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;
        $thn = date("Y", strtotime($request['tglspm']) );
        $tgl = $request['tglspm'];
        try{
            if($request['norec'] == ''){
                $SP = SPP::where('nospp', '=', $request['nospp'])->first();        
                $MA = new SPM();
                $norecKS = $MA->generateNewId();
                $MA->norec = $norecKS;
                $MA->kdprofile = $idProfile;
                $MA->statusenabled = true;
                $MA->objectsppfk = $SP->norec;
                
            } else{
                $MA = SPM::where('norec', '=', $request['norec'])->first();
            }

                $noa = $this->generateCode(new SPM, 'noreg', 5, '', $idProfile);
                // $nos = $this->generateCodeBySeqTableSPP(new SPM, 'nospm', 5, '',$kdProfile, $thn, $tgl);

                $runningNumber = RunningNumber::where('kegunaan','nospm')->first();
                $nomor = (int)$runningNumber->nomer_terbaru +1;
                $nos = str_pad((int)$nomor, 5, "0", STR_PAD_LEFT);

                RunningNumber::where('kegunaan','nospm')
                ->update(
                    [
                        'nomer_terbaru' => $nos
                    ]
                );

                $noSPM = '04.00/03.0/'.$nos.'/LS/1.02.2.22.0.00.01.0004/M/1/'.$thn;
                $MA->nospm = $noSPM;
                $MA->jenisbukti = $request['jenisbukti'];
                $MA->untukpengeluaran = $request['untukpengeluaran'];
                $MA->status = $request['status'];
                $MA->tglspm = $request['tglspm'];
                $MA->tglsah = $request['tglsah'];
                $MA->nilaispm = $request['nilaispm'];
                $MA->noreg = $nos;
                $MA->objectasalprodukfk = $request['objectasalprodukfk'];

                $MA->save();

           $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteSPM(Request $request) {
        DB::beginTransaction();
        $idProfile = (int) $this->kdProfile;

        try{
                $KB= SPM::where('norec',$request['norec'])
                    ->first();
                $KB->statusenabled = false;
                $KB->save();

                $transStatus = 'true';
            
        } catch (\Exception $e) {
                $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $transMessage,
                'as' => '@epic',
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
