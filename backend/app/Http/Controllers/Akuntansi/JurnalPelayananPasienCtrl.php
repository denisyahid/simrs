<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\ChartOfAccountMapJurnal;
use App\Models\Transaksi\PostingJurnal;
use App\Models\Transaksi\PostingJurnalTransaksi;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;

class JurnalPelayananPasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDetailPelayananPasien(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pd.nostruklastfk')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'pd.nosbmlastfk')
            ->leftjoin('loginuser_s as lu', 'lu.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftjoin('pegawai_m as pgs', 'pgs.id', '=', 'lu.objectpegawaifk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'kp.kelompokpasien',
                'pd.tglpulang',
                'pd.statuspasien',
                'sp.nostruk',
                'sbm.nosbm',
                'pg.id as pgid',
                'pg.namalengkap as namadokter',
                'pgs.namalengkap as kasir',
                'pd.objectruanganlastfk as ruanganid',
            )
            ->where('pd.kdprofile',$this->kdProfile)
            ->where('pd.statusenabled',true);

        $filter = $request->all();
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $data = $data->where('pd.tglregistrasi', '<=', $tgl);
        }
        if (isset($filter['deptId']) && $filter['deptId'] != "" && $filter['deptId'] != "undefined") {
            $data = $data->where('dept.id', '=', $filter['deptId']);
        }
        if (isset($filter['ruangId']) && $filter['ruangId'] != "" && $filter['ruangId'] != "undefined"  && $filter['ruangId'] != 'null' ) {
            $data = $data->where('ru.id', '=', $filter['ruangId']);
        }
        if (isset($filter['kelId']) && $filter['kelId'] != "" && $filter['kelId'] != "undefined") {
            $data = $data->where('kp.id', '=', $filter['kelId']);
        }
        if (isset($filter['dokId']) && $filter['dokId'] != "" && $filter['dokId'] != "undefined") {
            $data = $data->where('pg.id', '=', $filter['dokId']);
        }
        if (isset($filter['sttts']) && $filter['sttts'] != "" && $filter['sttts'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $filter['sttts']);
        }
        if (isset($r['search']) && $r['search'] != '') {

            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm);
            });
        }
        if (isset($filter['jmlRows']) && $filter['jmlRows'] != "" && $filter['jmlRows'] != "undefined") {
            $data = $data->take($filter['jmlRows']);
        }
        $data = $data->orderBy('pd.noregistrasi');

        $data = $data->get();

        return $this->respond($data);
    }
    public function getDataComboMapCoa(Request $r)
    {
        // $res['kelompokpasien'] =  KelompokPasien::mine()->get();
        $res['departemen'] = Departemen::mine()->get()->toArray();
        $jenisTrxfk =  $this->settingFix('jenisTrxCOA');
        $kdProfile = $this->kdProfile;
        $ru = Ruangan::mine()->get();
        foreach ($res['departemen']  as $k => $d) {
            $res['departemen'][$k]['ruangan'] = [];
            foreach ($ru  as $dd) {
                if ($dd->objectdepartemenfk == $d['id']) {
                    $res['departemen'][$k]['ruangan'][] = $dd;
                }
            }
        }
        $dataMapJurnal = DB::table('chartofaccountmapjurnal_t as hp')
            ->select('hp.norec','hp.objectcoadebetfk','hp.objectcoakreditfk','hp.objectdepartemenfk','hp.objectruanganfk','hp.objectkelompokpasienfk','hp.objectcarabayarfk')
            ->where('hp.kdprofile', $kdProfile)
            ->where('hp.statusenabled', true)
            ->where('objectjenistrxfk',$jenisTrxfk)
            ->orderBy('hp.objectcoadebetfk')
            ->get();
            $dataJenisTrxJurnal = DB::table('jenispelayananjurnal_m as dp')
            ->select('dp.id', 'dp.jenistransaksi')
            ->where('dp.statusenabled', true)
            ->where('dp.kdprofile', $kdProfile)
            ->orderBy('dp.jenistransaksi')
            ->get();
            $dataKelompok = DB::table('kelompokpasien_m as kp')
            ->select('kp.id', 'kp.kelompokpasien')
            ->where('kp.kdprofile', $kdProfile)
            ->where('kp.statusenabled', true)
            ->orderBy('kp.kelompokpasien')
            ->get();

        $dataCaraBayar = DB::table('carabayar_m as cb')
            ->select('cb.id', 'cb.carabayar')
            ->where('cb.kdprofile', $kdProfile)
            ->where('cb.statusenabled', true)
            ->orderBy('cb.carabayar')
            ->get();


        $dataCoa = DB::table('chartofaccount_m as hp')
            ->select('hp.id', 'hp.noaccount as kdaccount', 'hp.namaaccount')
            ->join('suratkeputusan_m as sk', 'sk.id', '=', 'hp.suratkeputusanfk')
            ->where('hp.kdprofile', $kdProfile)
            ->where('hp.statusenabled', true)
            ->orderBy('hp.noaccount')
            ->get();



        $data2res =[];
        $sama = false;
        foreach($dataCoa as $coa){
            $sama = false;
            foreach($dataMapJurnal as $map){
                if($map->objectcoadebetfk == $coa->id){
                    $sama = true;
                    $data2res[] = array(
                        'id' => $coa->id,
                        'kdaccount' => $coa->kdaccount,
                        'namaaccount' => $coa->namaaccount . ' /D'
                    );
                    break;
                }
            }
            foreach($dataMapJurnal as $map){
                if($map->objectcoakreditfk == $coa->id){
                    $sama = true;
                    $data2res[] = array(
                        'id' => $coa->id,
                        'kdaccount' => $coa->kdaccount,
                        'namaaccount' =>  $coa->namaaccount . " /K"
                    );
                    break;
                }
            }
            if($sama == false){
                $data2res[] = array(
                    'id' => $coa->id,
                    'kdaccount' => $coa->kdaccount,
                    'namaaccount' => $coa->namaaccount
                );
            }
        }

        $dataKpres =[];
        $sama = false;
        foreach($dataKelompok as $dt){
            $sama = false;
            foreach($dataMapJurnal as $map){
                if($map->objectkelompokpasienfk == $dt->id){
                    $sama = true;
                    $dataKpres[] = array(
                        'id' => $dt->id,
                        'kelompokpasien' => $dt->kelompokpasien . ' /#'
                    );
                    break;
                }
            }
            if($sama == false){
                $dataKpres[] = array(
                    'id' => $dt->id,
                    'kelompokpasien' => $dt->kelompokpasien
                );
            }


        }

        $dataCarapres =[];
        $sama = false;
        foreach($dataCaraBayar as $cb){
            $sama = false;
            foreach($dataMapJurnal as $map){
                if($map->objectcarabayarfk == $cb->id){
                    $sama = true;
                    $dataCarapres[] = array(
                        'id' => $cb->id,
                        'carabayar' => $cb->carabayar . ' /#'
                    );
                    break;
                }
            }
            if($sama == false){
                $dataCarapres[] = array(
                    'id' => $cb->id,
                    'carabayar' => $cb->carabayar
                );
            }


        }
        $dataDetailJenisProduk = [];
        $dataDetailJenisProduk = DB::table('detailjenisproduk_m as djp')
            ->where('djp.statusenabled', true)
            ->orderBy('djp.detailjenisproduk')
            ->get();


        $result = array(
            'departemen' => $res['departemen'],
            'kelompokpasien' =>$dataKpres,
            'jenisproduk' => [],// $dataJenisProduk,
            'detailjenisproduk' => $dataDetailJenisProduk,
            'produk' => [],
            'carabayar' => $dataCarapres,//carabayar
            'coa' => $data2res,//$coa,
            'jenistrxjurnal' => $dataJenisTrxJurnal,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    public function getDetailPelayananPasienByNoregistrasi(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $noregistrasi="";
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != "" && $request['noregistrasi'] != "undefined") {
            $noregistrasi = " and pd.noregistrasi =  '" .  $request['noregistrasi'] . "'";
        }
        $idProduk="";
        if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
            $tglAwal = $request['tglawal'];
            $tglAkhir = $request['tglakhir'];
            $idProduk = " pp.produkfk =  " .  $request['idproduk']  . " and pp.tglpelayanan between '$tglAwal' and '$tglAkhir' ";
        }


        // $data = DB::select(
        //     DB::raw("

        //     select pp.norec as norec_pp,pd.noregistrasi,case when pp.isobat='t' then ru2.namaruangan else ru.namaruangan end as namaruangan,pjt.nojurnal_intern,
        //     pp.tglpelayanan,pr.namaproduk || ', ' || pr.id as namaproduk,djp.detailjenisproduk ,pp.isobat,pp.hargadiscount,pp.hargajual,pp.hargasatuan,pp.jumlah,pp.jasa ,
        //     case when pp.isobat='t' then ru2.id else ru.id end as ruid,pr.id as prid,string_agg(cast(coa.id as text), ' , ' ) as idagg
        //     ,string_agg(case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end || ' : ' || coa.namaaccount || ' ' || case when pjtd.hargasatuank = 0 then pjtd.hargasatuand  else  pjtd.hargasatuank  end, ' , ' order by case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end) as jurnal,
        //     --case when pjt.deskripsiproduktransaksi ='pelayananpasien_t' then
        //     (((case when pp.hargajual is null then 0 else pp.hargajual end)-case when pp.hargadiscount is null then 0 else pp.hargadiscount end)*pp.jumlah) 
        //     + case when pp.jasa is null then 0 else pp.jasa end as hargatotal,
        //     --when pjt.deskripsiproduktransaksi ='bebanpelayananpasien_t' then
        //      round((pp.harganetto/111)*100*pp.jumlah) as bebam,
        //     --when pjt.deskripsiproduktransaksi ='ppnpelayananpasien_t' then 
        //     round((pp.harganetto/111)*11*pp.jumlah)  as ppn,
        //     pp.harganetto,
        //     ru3.jenis,ru.id as objectruanganfk,pd.objectkelompokpasienlastfk,pjt.deskripsiproduktransaksi,
        //     ps.namapasien
        //     from pasiendaftar_t pd
        //     inner join pasien_m as ps on ps.id=pd.nocmfk
        //     inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
        //     INNER JOIN antrianpasiendiperiksa_t apd on pd.norec=apd.noregistrasifk
        //     INNER JOIN ruangan_m ru on ru.id=apd.objectruanganfk
        //     INNER JOIN pelayananpasien_t pp on pp.noregistrasifk=apd.norec
        //     INNER JOIN produk_m pr on pr.id=pp.produkfk
        //     left join detailjenisproduk_m djp on djp.id=pr.objectdetailjenisprodukfk
        //     left join strukresep_t sr on sr.norec=pp.strukresepfk
        //     left join ruangan_m ru2 on ru2.id=sr.ruanganfk
        //     left join postingjurnaltransaksi_t pjt on pjt.norecrelated = pp.norec
        //     LEFT join postingjurnaltransaksid_t pjtd on pjtd.norecrelated=pjt.norec
        //     left join chartofaccount_m coa on coa.id=pjtd.objectaccountfk
        //     where $noregistrasi $idProduk
        //     and pd.kdprofile=$idProfile
        //     and pd.statusenabled=true
        //     group by pd.noregistrasi,case when pp.isobat='t' then ru2.namaruangan else ru.namaruangan end ,
        //     pp.tglpelayanan,pr.namaproduk,pp.isobat,pp.hargadiscount,pp.hargajual,pp.hargasatuan,pp.jumlah,pp.jasa,
        //     case when pp.isobat='t' then ru2.id else ru.id end ,pr.id ,djp.detailjenisproduk,pjt.nojurnal_intern,
        //     (((case when pp.hargajual is null then 0 else pp.hargajual end)-case when pp.hargadiscount is null then 0 else pp.hargadiscount end)*pp.jumlah) + case when pp.jasa is null then 0 else pp.jasa end ,
        //     pp.norec ,ru3.jenis,ru.id ,pd.objectkelompokpasienlastfk,pjt.deskripsiproduktransaksi,ps.namapasien,
        //     (pp.harganetto/100)*111*pp.jumlah
        //     order by pp.tglpelayanan


        // ")
        // );
        $data = DB::select(
            DB::raw("
               
            select pp.norec as norec_pp,pd.noregistrasi,case when pp.isobat='t' then ru2.namaruangan else ru.namaruangan end as namaruangan,pjt.nojurnal_intern,
            pp.tglpelayanan,pr.namaproduk || ', ' || pr.id as namaproduk,djp.detailjenisproduk ,pp.isobat,pp.hargadiscount,pp.hargajual,pp.hargasatuan,pp.jumlah,pp.jasa ,
            case when pp.isobat='t' then ru2.id else ru.id end as ruid,pr.id as prid,string_agg(cast(coa.id as text), ' , ' ) as idagg
            ,string_agg(case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end || ' : ' || coa.namaaccount || ' ' || case when pjtd.hargasatuank = 0 then pjtd.hargasatuand  else  pjtd.hargasatuank  end, ' , ' order by case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end) as jurnal,
            
            (((case when pp.hargajual is null then 0 else pp.hargajual end)-case when pp.hargadiscount is null then 0 else pp.hargadiscount end)*pp.jumlah) + case when pp.jasa is null then 0 else pp.jasa end as hargatotal,
            ru3.jenis,ru.id as objectruanganfk,pd.objectkelompokpasienlastfk
            from pasiendaftar_t pd 
            inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
            INNER JOIN antrianpasiendiperiksa_t apd on pd.norec=apd.noregistrasifk
            INNER JOIN ruangan_m ru on ru.id=apd.objectruanganfk
            INNER JOIN pelayananpasien_t pp on pp.noregistrasifk=apd.norec 
            INNER JOIN produk_m pr on pr.id=pp.produkfk
            left join detailjenisproduk_m djp on djp.id=pr.objectdetailjenisprodukfk
            left join strukresep_t sr on sr.norec=pp.strukresepfk
            left join ruangan_m ru2 on ru2.id=sr.ruanganfk
            left join postingjurnaltransaksi_t pjt on pjt.norecrelated = pp.norec
            LEFT join postingjurnaltransaksid_t pjtd on pjtd.norecrelated=pjt.norec 
            left join chartofaccount_m coa on coa.id=pjtd.objectaccountfk
            where pd.kdprofile=$idProfile
            and pd.statusenabled=true
            $noregistrasi
            $idProduk
            group by pd.noregistrasi,case when pp.isobat='t' then ru2.namaruangan else ru.namaruangan end ,
            pp.tglpelayanan,pr.namaproduk,pp.isobat,pp.hargadiscount,pp.hargajual,pp.hargasatuan,pp.jumlah,pp.jasa,
            case when pp.isobat='t' then ru2.id else ru.id end ,pr.id ,djp.detailjenisproduk,pjt.nojurnal_intern,
            (((case when pp.hargajual is null then 0 else pp.hargajual end)-case when pp.hargadiscount is null then 0 else pp.hargadiscount end)*pp.jumlah) + case when pp.jasa is null then 0 else pp.jasa end ,
            pp.norec ,ru3.jenis,ru.id ,pd.objectkelompokpasienlastfk


        ")
        );
        return $this->respond($data);
    }
    public function getDetailMapCoaByproduk(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $produkid = $request['produkid'];
        $jurn = $this->settingFix('JenisPelayananJurnal');
        $objectjenistrxfk = "";
        if (isset($request['objectjenistrxfk']) && $request['objectjenistrxfk'] != "" && $request['objectjenistrxfk'] != "undefined") {
            $objectjenistrxfk = " and  map.objectjenistrxfk in  (" . $request['objectjenistrxfk']  . ") ";
        }
        $data = DB::select(
            DB::raw("

            select  coa.noaccount as kddebet,coa.namaaccount as nmdebet,coa2.noaccount as kdkredit,coa2.namaaccount as nmkredit,
            dp.namadepartemen,ru.namaruangan,pr.namaproduk,rk.namarekanan,kp.kelompokpasien,
            cb.carabayar,bk.nama as bank,jpj.jenistransaksi,djp.detailjenisproduk,jp.jenisproduk,
            map.*
            from chartofaccountmapjurnal_t map
            INNER JOIN chartofaccount_m coa on coa.id=map.objectcoadebetfk
            INNER JOIN chartofaccount_m coa2 on coa2.id=map.objectcoakreditfk
            INNER JOIN jenispelayananjurnal_m jpj on jpj.id=map.objectjenistrxfk
            left JOIN departemen_m dp on dp.id=map.objectdepartemenfk
            left JOIN ruangan_m ru on ru.id=map.objectruanganfk
            left JOIN produk_m pr on pr.id=map.objectprodukfk
            left JOIN detailjenisproduk_m djp on djp.id=map.objectdetailjenisprodukfk
            left JOIN jenisproduk_m jp on jp.id=map.objectjenisprodukfk
            left JOIN rekanan_m rk on rk.id=map.objectrekananfk
            left JOIN kelompokpasien_m kp on kp.id=map.objectkelompokpasienfk
            left join carabayar_m cb on cb.id=map.objectcarabayarfk
            left join bank_m bk on bk.id=map.objectbankfk
            where
            map.kdprofile=$idProfile
            and map.objectprodukfk=$produkid 
            $objectjenistrxfk

        ")
        );
        return $this->respond($data);
    }
    // public function PostingJurnal_PerDetailTransaksi(Request $request) {
    //     $kdProfile = (int) $this->kdProfile;
    //     DB::beginTransaction();

    //     try {
    //         PostingJurnalTransaksi::where('norecrelated', $request['pelayanan']['norec_pp'])
    //         ->where('deskripsiproduktransaksi', 'pelayananpasien_t')
    //         ->where('statusenabled', true)
    //         ->delete();
    //         PostingJurnalTransaksi::where('norecrelated', $request['pelayanan']['norec_pp'])
    //         ->where('deskripsiproduktransaksi', 'bebanpelayananpasien_t')
    //         ->where('statusenabled', true)
    //         ->delete();
    //         PostingJurnalTransaksi::where('norecrelated', $request['pelayanan']['norec_pp'])
    //         ->where('deskripsiproduktransaksi', 'ppnpelayananpasien_t')
    //         ->where('statusenabled', true)
    //         ->delete();

    //         $idProduk = $request['pelayanan']['prid'];
    //         $dataMapJurnal = DB::select(
    //             DB::raw("

    //                 select  coa.noaccount as kddebet,coa.namaaccount as nmdebet,coa2.noaccount as kdkredit,coa2.namaaccount as nmkredit,
    //                 dp.namadepartemen,ru.namaruangan,pr.namaproduk,rk.namarekanan,kp.kelompokpasien,cb.carabayar,bk.nama as bank,jpj.jenistransaksi,map.*
    //                 from chartofaccountmapjurnal_t map
    //                 INNER JOIN chartofaccount_m coa on coa.id=map.objectcoadebetfk
    //                 INNER JOIN chartofaccount_m coa2 on coa2.id=map.objectcoakreditfk
    //                 INNER JOIN jenispelayananjurnal_m jpj on jpj.id=map.objectjenistrxfk
    //                 left JOIN departemen_m dp on dp.id=map.objectdepartemenfk
    //                 left JOIN ruangan_m ru on ru.id=map.objectruanganfk
    //                 left JOIN produk_m pr on pr.id=map.objectprodukfk
    //                 left JOIN rekanan_m rk on rk.id=map.objectrekananfk
    //                 left JOIN kelompokpasien_m kp on kp.id=map.objectkelompokpasienfk
    //                 left join carabayar_m cb on cb.id=map.objectcarabayarfk
    //                 left join bank_m bk on bk.id=map.objectbankfk
    //                 where map.objectprodukfk=$idProduk and map.objectjenistrxfk in (1,5,9)

    //             ")
    //         );
    //         $dataHasilJurnal = [];

    //         $sama=false;
    //         foreach ($dataMapJurnal as $item) {
    //             if($item->objectjenistrxfk == 1){//PelayananPasien
    //                 $totalRp = $request['pelayanan']['hargatotal'];

    //                 $sama=false;

    //                 if($item->objectruanganfk == null && $item->objectkelompokpasienfk == null){
    //                     $debetId = $item->objectcoadebetfk;
    //                     $kreditId = $item->objectcoakreditfk;
    //                     $sama = true;
    //                 }
    //                 if($item->objectruanganfk != null && $item->objectkelompokpasienfk == null){
    //                     if ($request['pelayanan']['isobat'] == true){
    //                         if($item->objectruanganfk == $request['pelayanan']['ruid'] ){
    //                             $debetId = $item->objectcoadebetfk;
    //                             $kreditId = $item->objectcoakreditfk;
    //                             $sama = true;
    //                         }
    //                     }else{
    //                         if($item->objectruanganfk == $request['pelayanan']['objectruanganfk'] ){
    //                             $debetId = $item->objectcoadebetfk;
    //                             $kreditId = $item->objectcoakreditfk;
    //                             $sama = true;
    //                         }
    //                     }

    //                 }
    //                 if($item->objectruanganfk == null && $item->objectkelompokpasienfk != null){
    //                     if($item->objectkelompokpasienfk == $request['pelayanan']['objectkelompokpasienlastfk'] ){
    //                         $debetId = $item->objectcoadebetfk;
    //                         $kreditId = $item->objectcoakreditfk;
    //                         $sama = true;
    //                     }
    //                 }
    //                 if($item->objectruanganfk != null && $item->objectkelompokpasienfk != null){
    //                     if ($request['pelayanan']['isobat'] == true){
    //                         if($item->objectruanganfk == $request['pelayanan']['ruid'] && $item->objectkelompokpasienfk == $request['pelayanan']['objectkelompokpasienlastfk']){
    //                             $debetId = $item->objectcoadebetfk;
    //                             $kreditId = $item->objectcoakreditfk;
    //                             $sama = true;
    //                         }
    //                     }else{
    //                         if($item->objectruanganfk == $request['pelayanan']['objectruanganfk'] && $item->objectkelompokpasienfk == $request['pelayanan']['objectkelompokpasienlastfk']){
    //                             $debetId = $item->objectcoadebetfk;
    //                             $kreditId = $item->objectcoakreditfk;
    //                             $sama = true;
    //                         }
    //                     }

    //                 }


    //                 // $noBuktiTransaksi =$item->prid;
    //                 $noBuktiTransaksi =$request['pelayanan']['noregistrasi'];

    //                 $noJurnalIntern ='';
    //                 $noPosting = '-';
    //                 $noJurnalIntern = Carbon::parse($request['pelayanan']['tglpelayanan'])->format('ym').'PN'.Carbon::parse($request['pelayanan']['tglpelayanan'])->format('d').'00001';
    //                 $cekSudahPosting =  PostingJurnal::where('norecrelated',$noJurnalIntern)->where('kdprofile', $kdProfile)->get();
    //                 $tgl = Carbon::parse($request['pelayanan']['tglpelayanan'])->format('Y-m-d');//to_char($request['pelayanan']['tglpelayanan'], 'YYYY-MM-DD')

    //                 if ($sama == true){
    //                     if (count($cekSudahPosting) == 0) {
    //                         $postingJurnalTransaksi = new PostingJurnalTransaksi;
    //                         $norecHead = $postingJurnalTransaksi->generateNewId();
    //                         $postingJurnalTransaksi->norec = $norecHead;
    //                         $postingJurnalTransaksi->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksi->noposting =  $noPosting;
    //                         $postingJurnalTransaksi->nojurnal = 0;
    //                         $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
    //                         $postingJurnalTransaksi->objectjenisjurnalfk = 1;
    //                         $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
    //                         $postingJurnalTransaksi->tglbuktitransaksi = $request['pelayanan']['tglpelayanan'];
    //                         $postingJurnalTransaksi->kdproduk = $request['pelayanan']['prid'];
    //                         $postingJurnalTransaksi->namaproduktransaksi = $request['pelayanan']['namaproduk'] . ' a.n ' . $request['pelayanan']['namapasien'] .' ('. $request['pelayanan']['noregistrasi'] .')';//. ' ' . $item->obat;
    //                         $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_t';
    //                         $postingJurnalTransaksi->keteranganlainnya = 'PelayananPasien tgl. ' . $tgl;
    //                         $postingJurnalTransaksi->statusenabled = 1;
    //                         $postingJurnalTransaksi->norecrelated = $request['pelayanan']['norec_pp'];
    //                         $postingJurnalTransaksi->jenis = $request['pelayanan']['jenis'];
    //                         $postingJurnalTransaksi->save();

    //                         $norec_pj = $postingJurnalTransaksi->norec;

    //                         //debet
    //                         $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
    //                         $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
    //                         $postingJurnalTransaksiD->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksiD->nojurnal = 0;
    //                         $postingJurnalTransaksiD->noposting = $noPosting;
    //                         $postingJurnalTransaksiD->objectaccountfk = $debetId;
    //                         $postingJurnalTransaksiD->hargasatuand = $totalRp;
    //                         $postingJurnalTransaksiD->hargasatuank = 0;
    //                         $postingJurnalTransaksiD->statusenabled = 1;
    //                         $postingJurnalTransaksiD->norecrelated = $norec_pj;
    //                         $postingJurnalTransaksiD->save();

    //                         //kredit
    //                         $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
    //                         $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
    //                         $postingJurnalTransaksiD->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksiD->nojurnal = 0;
    //                         $postingJurnalTransaksiD->noposting = $noPosting;
    //                         $postingJurnalTransaksiD->objectaccountfk = $kreditId;
    //                         $postingJurnalTransaksiD->hargasatuand = 0;
    //                         $postingJurnalTransaksiD->hargasatuank = $totalRp;
    //                         $postingJurnalTransaksiD->statusenabled = 1;
    //                         $postingJurnalTransaksiD->norecrelated = $norec_pj;
    //                         $postingJurnalTransaksiD->save();
    //                     }else{
    //                         $transMessage = "Sudah Posting";
    //                         DB::rollBack();
    //                         $result = array(
    //                             "status" => 400,
    //                             "result"  => null
                
    //                         );
    //                         return $this->respond($result['result'], $result['status'], $transMessage);
    //                     }

    //                     $dataHasilJurnal[]=array(
    //                         "debet" => "D : " . $item->nmdebet . ' ' . $totalRp,
    //                         "kredit" => "K : " . $item->nmkredit . ' ' . $totalRp,
    //                         "norec_pp" => $request['pelayanan']['norec_pp'],
    //                         "nojurnal" => $noJurnalIntern,
    //                         "jurnal" => $postingJurnalTransaksi
    //                     );
    //                 }
    //             }
    //             if($item->objectjenistrxfk == 5){//Beban persediaan
    //                 $totalRp = $request['pelayanan']['hargatotal'];

    //                 $debetId = $item->objectcoadebetfk;
    //                 $kreditId = $item->objectcoakreditfk;


    //                 // $noBuktiTransaksi =$item->prid;
    //                 $noBuktiTransaksi =$request['pelayanan']['noregistrasi'];

    //                 $noJurnalIntern ='';
    //                 $noPosting = '-';
    //                 $noJurnalIntern = Carbon::parse($request['pelayanan']['tglpelayanan'])->format('ym').'PN'.Carbon::parse($request['pelayanan']['tglpelayanan'])->format('d').'00005';
    //                 $cekSudahPosting =  PostingJurnal::where('norecrelated',$noJurnalIntern)->where('kdprofile', $kdProfile)->get();
    //                 $tgl = Carbon::parse($request['pelayanan']['tglpelayanan'])->format('Y-m-d');//to_char($request['pelayanan']['tglpelayanan'], 'YYYY-MM-DD')

    //                 if ($sama == true){
    //                     if (count($cekSudahPosting) == 0) {
    //                         $postingJurnalTransaksi = new PostingJurnalTransaksi;
    //                         $norecHead = $postingJurnalTransaksi->generateNewId();
    //                         $postingJurnalTransaksi->norec = $norecHead;
    //                         $postingJurnalTransaksi->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksi->noposting =  $noPosting;
    //                         $postingJurnalTransaksi->nojurnal = 0;
    //                         $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
    //                         $postingJurnalTransaksi->objectjenisjurnalfk = 1;
    //                         $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
    //                         $postingJurnalTransaksi->tglbuktitransaksi = $request['pelayanan']['tglpelayanan'];
    //                         $postingJurnalTransaksi->kdproduk = $request['pelayanan']['prid'];
    //                         $postingJurnalTransaksi->namaproduktransaksi = 'Persediaan ' .  $request['pelayanan']['namaproduk'] . ' a.n ' . $request['pelayanan']['namapasien'] .' ('. $request['pelayanan']['noregistrasi'] .')';//. ' ' . $item->obat;
    //                         $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_t';
    //                         $postingJurnalTransaksi->keteranganlainnya = 'bebanpelayananpasien tgl. ' . $tgl;
    //                         $postingJurnalTransaksi->statusenabled = 1;
    //                         $postingJurnalTransaksi->norecrelated = $request['pelayanan']['norec_pp'];
    //                         $postingJurnalTransaksi->jenis = $request['pelayanan']['jenis'];
    //                         $postingJurnalTransaksi->save();

    //                         $norec_pj = $postingJurnalTransaksi->norec;

    //                         //debet
    //                         $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
    //                         $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
    //                         $postingJurnalTransaksiD->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksiD->nojurnal = 0;
    //                         $postingJurnalTransaksiD->noposting = $noPosting;
    //                         $postingJurnalTransaksiD->objectaccountfk = $debetId;
    //                         $postingJurnalTransaksiD->hargasatuand = $totalRp;
    //                         $postingJurnalTransaksiD->hargasatuank = 0;
    //                         $postingJurnalTransaksiD->statusenabled = 1;
    //                         $postingJurnalTransaksiD->norecrelated = $norec_pj;
    //                         $postingJurnalTransaksiD->save();

    //                         //kredit
    //                         $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
    //                         $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
    //                         $postingJurnalTransaksiD->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksiD->nojurnal = 0;
    //                         $postingJurnalTransaksiD->noposting = $noPosting;
    //                         $postingJurnalTransaksiD->objectaccountfk = $kreditId;
    //                         $postingJurnalTransaksiD->hargasatuand = 0;
    //                         $postingJurnalTransaksiD->hargasatuank = $totalRp;
    //                         $postingJurnalTransaksiD->statusenabled = 1;
    //                         $postingJurnalTransaksiD->norecrelated = $norec_pj;
    //                         $postingJurnalTransaksiD->save();
    //                     }

    //                     $dataHasilJurnal[]=array(
    //                         "debet" => "D : " . $item->nmdebet . ' ' . $totalRp,
    //                         "kredit" => "K : " . $item->nmkredit . ' ' . $totalRp,
    //                         "norec_pp" => $request['pelayanan']['norec_pp'],
    //                         "nojurnal" => $noJurnalIntern,
    //                         "jurnal" => $postingJurnalTransaksi
    //                     );
    //                 }
    //             }
    //             if($item->objectjenistrxfk == 9){//Beban persediaan
    //                 $totalRp = $request['pelayanan']['hargatotal'];

    //                 $debetId = $item->objectcoadebetfk;
    //                 $kreditId = $item->objectcoakreditfk;


    //                 // $noBuktiTransaksi =$item->prid;
    //                 $noBuktiTransaksi =$request['pelayanan']['noregistrasi'];

    //                 $noJurnalIntern ='';
    //                 $noPosting = '-';
    //                 $noJurnalIntern = Carbon::parse($request['pelayanan']['tglpelayanan'])->format('ym').'PN'.Carbon::parse($request['pelayanan']['tglpelayanan'])->format('d').'00009';
    //                 $cekSudahPosting =  PostingJurnal::where('norecrelated',$noJurnalIntern)->where('kdprofile', $kdProfile)->get();
    //                 $tgl = Carbon::parse($request['pelayanan']['tglpelayanan'])->format('Y-m-d');//to_char($request['pelayanan']['tglpelayanan'], 'YYYY-MM-DD')

    //                 if ($sama == true){
    //                     if (count($cekSudahPosting) == 0) {
    //                         $postingJurnalTransaksi = new PostingJurnalTransaksi;
    //                         $norecHead = $postingJurnalTransaksi->generateNewId();
    //                         $postingJurnalTransaksi->norec = $norecHead;
    //                         $postingJurnalTransaksi->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksi->noposting =  $noPosting;
    //                         $postingJurnalTransaksi->nojurnal = 0;
    //                         $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
    //                         $postingJurnalTransaksi->objectjenisjurnalfk = 1;
    //                         $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
    //                         $postingJurnalTransaksi->tglbuktitransaksi = $request['pelayanan']['tglpelayanan'];
    //                         $postingJurnalTransaksi->kdproduk = $request['pelayanan']['prid'];
    //                         $postingJurnalTransaksi->namaproduktransaksi = 'PPN Jual Persediaan ' . $request['pelayanan']['namaproduk'] . ' a.n ' . $request['pelayanan']['namapasien'] .' ('. $request['pelayanan']['noregistrasi'] .')';//. ' ' . $item->obat;
    //                         $postingJurnalTransaksi->deskripsiproduktransaksi = 'ppnpelayananpasien_t';
    //                         $postingJurnalTransaksi->keteranganlainnya = 'ppnpelayananpasien tgl. ' . $tgl;
    //                         $postingJurnalTransaksi->statusenabled = 1;
    //                         $postingJurnalTransaksi->norecrelated = $request['pelayanan']['norec_pp'];
    //                         $postingJurnalTransaksi->jenis = $request['pelayanan']['jenis'];
    //                         $postingJurnalTransaksi->save();

    //                         $norec_pj = $postingJurnalTransaksi->norec;

    //                         //debet
    //                         $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
    //                         $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
    //                         $postingJurnalTransaksiD->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksiD->nojurnal = 0;
    //                         $postingJurnalTransaksiD->noposting = $noPosting;
    //                         $postingJurnalTransaksiD->objectaccountfk = $debetId;
    //                         $postingJurnalTransaksiD->hargasatuand = $totalRp;
    //                         $postingJurnalTransaksiD->hargasatuank = 0;
    //                         $postingJurnalTransaksiD->statusenabled = 1;
    //                         $postingJurnalTransaksiD->norecrelated = $norec_pj;
    //                         $postingJurnalTransaksiD->save();

    //                         //kredit
    //                         $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
    //                         $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
    //                         $postingJurnalTransaksiD->kdprofile = $kdProfile;
    //                         $postingJurnalTransaksiD->nojurnal = 0;
    //                         $postingJurnalTransaksiD->noposting = $noPosting;
    //                         $postingJurnalTransaksiD->objectaccountfk = $kreditId;
    //                         $postingJurnalTransaksiD->hargasatuand = 0;
    //                         $postingJurnalTransaksiD->hargasatuank = $totalRp;
    //                         $postingJurnalTransaksiD->statusenabled = 1;
    //                         $postingJurnalTransaksiD->norecrelated = $norec_pj;
    //                         $postingJurnalTransaksiD->save();
    //                     }

    //                     $dataHasilJurnal[]=array(
    //                         "debet" => "D : " . $item->nmdebet . ' ' . $totalRp,
    //                         "kredit" => "K : " . $item->nmkredit . ' ' . $totalRp,
    //                         "norec_pp" => $request['pelayanan']['norec_pp'],
    //                         "nojurnal" => $noJurnalIntern,
    //                         "jurnal" => $postingJurnalTransaksi
    //                     );
    //                 }
    //             }
    //         }
    //         $transMessage = "Sukses";
    //         DB::commit();
    //         $result = array(
    //             "status" => 200,
    //             "result" => array(
    //                 "data"  => $dataHasilJurnal,
    //                 "as" => '@epic',
    //             ),
    //         );
    //     } catch (\Exception $e) {

    //         $transMessage = "Simpan Gagal";
    //         DB::rollBack();
    //         $result = array(
    //             "status" => 400,
    //             "result"  => $e->getMessage() . ' ' . $e->getLine()

    //         );
    //     }
    //     return $this->respond($result['result'], $result['status'], $transMessage);

    // }
    public function PostingJurnal_PerDetailTransaksi(Request $request) {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        
        try {
            PostingJurnalTransaksi::where('norecrelated', $request['pelayanan']['norec_pp'])
            ->where('deskripsiproduktransaksi', 'pelayananpasien_t')
            ->where('statusenabled', true)
            ->delete();

            $idProduk = $request['pelayanan']['prid'];
            $dataMapJurnal = DB::select(
                DB::raw("
                   
                    select  coa.noaccount as kddebet,coa.namaaccount as nmdebet,coa2.noaccount as kdkredit,coa2.namaaccount as nmkredit,
                    dp.namadepartemen,ru.namaruangan,pr.namaproduk,rk.namarekanan,kp.kelompokpasien,cb.carabayar,bk.nama as bank,jpj.jenistransaksi,map.*
                    from chartofaccountmapjurnal_t map
                    INNER JOIN chartofaccount_m coa on coa.id=map.objectcoadebetfk
                    INNER JOIN chartofaccount_m coa2 on coa2.id=map.objectcoakreditfk
                    INNER JOIN jenispelayananjurnal_m jpj on jpj.id=map.objectjenistrxfk
                    left JOIN departemen_m dp on dp.id=map.objectdepartemenfk
                    left JOIN ruangan_m ru on ru.id=map.objectruanganfk
                    left JOIN produk_m pr on pr.id=map.objectprodukfk
                    left JOIN rekanan_m rk on rk.id=map.objectrekananfk
                    left JOIN kelompokpasien_m kp on kp.id=map.objectkelompokpasienfk
                    left join carabayar_m cb on cb.id=map.objectcarabayarfk
                    left join bank_m bk on bk.id=map.objectbankfk
                    where map.objectprodukfk=$idProduk
        
                ")
            );
            $dataHasilJurnal = [];
            $sama=false;
            foreach ($dataMapJurnal as $item) {
                if($item->objectjenistrxfk == 1){//PelayananPasien
                    $totalRp = $request['pelayanan']['hargatotal'];

                    $sama=false;
                    
                    if($item->objectruanganfk == null && $item->objectkelompokpasienfk == null){
                        $debetId = $item->objectcoadebetfk;
                        $kreditId = $item->objectcoakreditfk;
                        $sama = true;
                    }
                    if($item->objectruanganfk != null && $item->objectkelompokpasienfk == null){
                        if ($request['pelayanan']['isobat'] == true){
                            if($item->objectruanganfk == $request['pelayanan']['ruid'] ){
                                $debetId = $item->objectcoadebetfk;
                                $kreditId = $item->objectcoakreditfk;
                                $sama = true;
                            }
                        }else{
                            if($item->objectruanganfk == $request['pelayanan']['objectruanganfk'] ){
                                $debetId = $item->objectcoadebetfk;
                                $kreditId = $item->objectcoakreditfk;
                                $sama = true;
                            }
                        }
                        
                    }
                    if($item->objectruanganfk == null && $item->objectkelompokpasienfk != null){
                        if($item->objectkelompokpasienfk == $request['pelayanan']['objectkelompokpasienlastfk'] ){
                            $debetId = $item->objectcoadebetfk;
                            $kreditId = $item->objectcoakreditfk;
                            $sama = true;
                        }
                    }
                    if($item->objectruanganfk != null && $item->objectkelompokpasienfk != null){                        
                        if ($request['pelayanan']['isobat'] == true){
                            if($item->objectruanganfk == $request['pelayanan']['ruid'] && $item->objectkelompokpasienfk == $request['pelayanan']['objectkelompokpasienlastfk']){
                                $debetId = $item->objectcoadebetfk;
                                $kreditId = $item->objectcoakreditfk;
                                $sama = true;
                            }
                        }else{
                            if($item->objectruanganfk == $request['pelayanan']['objectruanganfk'] && $item->objectkelompokpasienfk == $request['pelayanan']['objectkelompokpasienlastfk']){
                                $debetId = $item->objectcoadebetfk;
                                $kreditId = $item->objectcoakreditfk;
                                $sama = true;
                            }
                        }
                        
                    }

                
                    // $noBuktiTransaksi =$item->prid;
                    $noBuktiTransaksi =$request['registrasi'];

                    $noJurnalIntern ='';
                    $noPosting = '-';
                    $noJurnalIntern = Carbon::parse($request['pelayanan']['tglpelayanan'])->format('ym').'PN'.Carbon::parse($request['pelayanan']['tglpelayanan'])->format('d').'00001';
                    $cekSudahPosting =  PostingJurnal::where('norecrelated',$noJurnalIntern)->where('kdprofile', $kdProfile)->get();
                    $tgl = Carbon::parse($request['pelayanan']['tglpelayanan'])->format('Y-m-d');//to_char($request['pelayanan']['tglpelayanan'], 'YYYY-MM-DD')
                        
                    if ($sama == true){
                        if (count($cekSudahPosting) == 0) {
                            $postingJurnalTransaksi = new PostingJurnalTransaksi;
                            $norecHead = $postingJurnalTransaksi->generateNewId();
                            $postingJurnalTransaksi->norec = $norecHead;
                            $postingJurnalTransaksi->kdprofile = $kdProfile;
                            $postingJurnalTransaksi->noposting =  $noPosting;
                            $postingJurnalTransaksi->nojurnal = 0;
                            $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                            $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                            $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                            $postingJurnalTransaksi->tglbuktitransaksi = $request['pelayanan']['tglpelayanan'];
                            $postingJurnalTransaksi->kdproduk = $request['pelayanan']['prid'];
                            $postingJurnalTransaksi->namaproduktransaksi = $request['pelayanan']['namaproduk'] . ' a.n ' . $request['namapasien'] .' ('. $request['registrasi'] .')';//. ' ' . $item->obat;
                            $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_t';
                            $postingJurnalTransaksi->keteranganlainnya = 'PelayananPasien tgl. ' . $tgl;
                            $postingJurnalTransaksi->statusenabled = 1;
                            $postingJurnalTransaksi->norecrelated = $request['pelayanan']['norec_pp'];
                            $postingJurnalTransaksi->jenis = $request['pelayanan']['jenis'];
                            $postingJurnalTransaksi->save();

                            $norec_pj = $postingJurnalTransaksi->norec;

                            //debet
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $debetId;
                            $postingJurnalTransaksiD->hargasatuand = $totalRp;
                            $postingJurnalTransaksiD->hargasatuank = 0;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();

                            //kredit
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                            $postingJurnalTransaksiD->hargasatuand = 0;
                            $postingJurnalTransaksiD->hargasatuank = $totalRp;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();
                        }else{
                            $transMessage = "Sudah Posting";
                            DB::rollBack();
                            $result = array(
                                "status" => 400,
                                "result"  => null
                
                            );
                            return $this->respond($result['result'], $result['status'], $transMessage);
                        }
                        
                        $dataHasilJurnal[]=array(
                            "debet" => "D : " . $item->nmdebet . ' ' . $totalRp,
                            "kredit" => "K : " . $item->nmkredit . ' ' . $totalRp,
                            "norec_pp" => $request['pelayanan']['norec_pp'],
                            "nojurnal" => $noJurnalIntern,
                        );
                    }
                }
            }
            $transMessage = "Sukses";
                DB::commit();
                $result = array(
                    "status" => 200,
                    "result" => array(
                        "data" => $dataHasilJurnal,
                        "as" => '@epic',
                    ),
                );
            } catch (\Exception $e) {
    
                $transMessage = "Simpan Gagal";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result"  => $e->getMessage() . ' ' . $e->getLine()
    
                );
            }
            return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveUpdateMapCoa(Request $request){

        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try{

            if($request['norec'] == "-"){
                $map = new ChartOfAccountMapJurnal();
                $MapNorec = $map->generateNewId();
                $map->norec = $MapNorec;
                $map->kdprofile = $idProfile;
                $map->statusenabled = 't';
            }else{
                $map = ChartOfAccountMapJurnal::where('norec',$request['norec'])
                ->first();
            }
            $map->objectjenistrxfk = $request['objectjenistrxfk'];
            $map->objectcoadebetfk = $request['objectcoadebetfk'];
            $map->objectcoakreditfk = $request['objectcoakreditfk'];
            $map->objectdepartemenfk = $request['objectdepartemenfk'];
            $map->objectruanganfk = $request['objectruanganfk'];
            $map->objectkelompokpasienfk = $request['objectkelompokpasienfk'];
            $map->objectrekananfk = $request['objectrekananfk'];
            $map->objectdetailjenisprodukfk = $request['objectdetailjenisprodukfk'];
            $map->objectjenisprodukfk = $request['objectjenisprodukfk'];
            $map->objectcarabayarfk = $request['objectcarabayarfk'];
            $map->objectprodukfk = $request['objectprodukfk'];
            $map->raw = $request['raw'];
            $map->save();

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(

                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveHapusMapCoa(Request $request){
        $kdProfile =$this->kdProfile;

        DB::beginTransaction();
        try{

            if($request['norec'] != "-"){
                $map = ChartOfAccountMapJurnal::where('norec',$request['norec'])
                ->delete();
            }

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(

                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataJurnalUmumRev2019(Request $request) {
     
        $idProfile = (int) $this->kdProfile;
        $filterKeterangan='';
        if(isset($request['keterangan']) && $request['keterangan']!="" && $request['keterangan']!="undefined"){
            $filterKeterangan=' and pj.keteranganlainnya ilike \'%' . $request['keterangan'] .'%\'';
        }
        $dataHead = DB::select(DB::raw("
                    select x.tgl,x.nojurnal_intern as nojurnal,x.keteranganlainnya as kelompok,
                    x.nojurnal_posted as posted,
                    cast(sum(x.debet) AS float) as debet, cast(sum(x.kredit) AS float) as kredit from 
                    (select to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD') as tgl,coa.noaccount,coa.namaaccount,(pjd.hargasatuand) as debet,
                    (pjd.hargasatuank) as kredit,pj.deskripsiproduktransaksi,to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD') as tgl2,
                    coa.id as coaid,pj.keteranganlainnya,pj.nojurnal_intern,
                    posted.nojurnal_intern as nojurnal_posted,posted.tglbuktitransaksi  as tglposting,posted.nojurnal
                    from postingjurnaltransaksi_t as pj
                    INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                    INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                    left JOIN postingjurnal_t as posted on posted.norecrelated=pj.nojurnal_intern
                    where pj.kdprofile = $idProfile and pj.tglbuktitransaksi between '$request[tglAwal]' and '$request[tglAkhir]' 
                    and pj.deskripsiproduktransaksi <> 'SALDOAWAL'
                    $filterKeterangan
                    )as x
                    group by x.tgl,x.nojurnal_intern ,x.keteranganlainnya,
                    x.nojurnal_posted 
                    order by x.nojurnal_intern;
            "));
      

        return $this->respond($dataHead);
    }
}
