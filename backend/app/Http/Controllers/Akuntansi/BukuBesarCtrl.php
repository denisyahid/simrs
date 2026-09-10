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
use App\Models\Transaksi\PostingJurnalD;
use App\Models\Transaksi\PostingJurnalTransaksi;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;

class BukuBesarCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDataBukuBesarRev2(Request $request) {

        $idProfile = (int) $this->kdProfile;
        $req=$request->all();
        //        ini_set('max_execution_time', 1000);
        $mydate = $request['tglAwal'];
        $daystosum = '1';

        $datesum = date('d-m-Y', strtotime($mydate.' - '.$daystosum.' months'));
        $data = date('Ym', strtotime($datesum));
        $data10=[];
        if ($request['noaccount'] == '-' and $request['noaccount2'] == '-'){
        
            $aingMacan = DB::select(DB::raw("select * from
                    (select  to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD') as tglbuktitransaksi ,
                    coa.noaccount as noaccount,pj.keteranganlainnya,pj.nobuktitransaksi as noref,
                    sum(pjd.hargasatuand) as hargasatuand,
                    sum(pjd.hargasatuank) as hargasatuank,coa.saldonormaladd,coa.saldonormalmin,coa.id as coaid,pj.nojurnal_intern,coa.namaaccount
                    from postingjurnal_t as pj
                    INNER JOIN postingjurnald_t as pjd on pjd.norecrelated=pj.norec
                    INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                    where pj.kdprofile = $idProfile and pj.tglbuktitransaksi between :tglAwal and :tglAkhir 
                    
                    group by to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD'),pj.keteranganlainnya,pj.deskripsiproduktransaksi,coa.saldonormaladd,
                    coa.saldonormalmin,coa.id,pj.nojurnal_intern,coa.namaaccount,coa.noaccount,pj.nobuktitransaksi)as x
                    order by x.noaccount,x.nojurnal_intern ;
                "),
                array(
                    'tglAwal' => $request['tglAwal'] ,
                    'tglAkhir' => $request['tglAkhir'],
                )
            );

            $aingSA = DB::select(DB::raw("
                select case when psa.hargasatuand is null then 0 else psa.hargasatuand end as hargasatuand,
                    case when psa.hargasatuank is null then 0 else psa.hargasatuank end as hargasatuank,psa.objectaccountfk,
                    coa.noaccount 
                    from postingsaldoawal_t as psa
                    INNER JOIN chartofaccount_m as coa on coa.id=psa.objectaccountfk
                    where psa.kdprofile = $idProfile and psa.ym ='$data' and psa.statusenabled = 1 ;
                ")
            );

            $coaSA ='';
            $saldoD = 0;
            $saldoK = 0;
            foreach ($aingMacan as $item) {
                $tgl = date('Y-m-d', strtotime($item->tglbuktitransaksi));
                $detailData = [];

                $sama = false;                
                $saldoD = 0;
                $saldoK = 0;
                if ($coaSA != $item->noaccount) {
                    $coaSA = $item->noaccount;
                    $sama = false;  
                    foreach ($aingSA as $kutukupret) {
                        if ($item->noaccount == $kutukupret->noaccount) {
                            $saldo = 0;                  
                            $saldoD = 0;
                            $saldoK = 0;
                        if ($kutukupret->hargasatuand > 0) {
                            $saldo = $kutukupret->hargasatuand;
                        } else {
                            $saldo = $kutukupret->hargasatuank;
                        }
                        $saldoD = $saldoD + $kutukupret->hargasatuand;
                        $saldoK = $saldoK + $kutukupret->hargasatuank;
                            $data10[] = array(
                                'hargasatuand' => $kutukupret->hargasatuand,
                                'hargasatuank' => $kutukupret->hargasatuank,
                                'keteranganlainnya' => 'Saldo Awal',
                                'noaccount' => $item->noaccount,
                                'saldonormaladd' => $item->saldonormaladd,
                                'saldonormalmin' => $item->saldonormalmin,
                                'tglbuktitransaksi' => $data . '31',
                                'coaid' => $item->coaid,
                                'noaccount' => $item->noaccount,
                                'namaaccount' => $item->namaaccount,
                                'KodePerkiraan' => $item->noaccount . ' ' . $item->namaaccount,
                                'details' => [],
                                'tgl' => (string)$data . '31',
                                'nojurnal' => '',
                                'saldo' => $saldo,
                                'saldod' => $saldoD,
                                'saldok' => $saldoK,
                                'noref' => '',
                            );
                            $sama = true;
                        }
                    }
                    if ($sama == false) {
                        $saldo = 0;
                        $data10[] = array(
                            'hargasatuand' => 0,
                            'hargasatuank' => 0,
                            'keteranganlainnya' => 'Saldo Awal',
                            'noaccount' => $item->noaccount,
                            'saldonormaladd' => $item->saldonormaladd,
                            'saldonormalmin' => $item->saldonormalmin,
                            'tglbuktitransaksi' => $data . '31',
                            'coaid' => $item->coaid,
                            'noaccount' => $item->noaccount,
                            'namaaccount' => $item->namaaccount,
                            'KodePerkiraan' => $item->noaccount . ' ' . $item->namaaccount,
                            'details' => [],
                            'tgl' => (string)$data . '31',
                            'nojurnal' => '',
                            'saldo' => 0,
                            'saldod' => 0,
                            'saldok' => 0,
                            'noref' => '',
                        );
                    }

                }

                if($item->saldonormaladd =='K'){
                    $saldo = $saldo + $item->hargasatuank - $item->hargasatuand;
                    $saldoD = 0;
                    $saldoK = $saldo;
                }else{
                    $saldo = $saldo + $item->hargasatuand - $item->hargasatuank;
                    $saldoD = $saldo;
                    $saldoK = 0;
                }

                $data10[] = array(
                    'hargasatuand' => $item->hargasatuand,
                    'hargasatuank' => $item->hargasatuank,
                    'keteranganlainnya' => $item->keteranganlainnya,
                    'noaccount' => $item->noaccount,
                    'saldonormaladd' => $item->saldonormaladd,
                    'saldonormalmin' => $item->saldonormalmin,
                    'tglbuktitransaksi' => $item->tglbuktitransaksi,
                    'coaid' => $item->coaid,
                    'noaccount' => $item->noaccount,
                    'namaaccount' => $item->namaaccount,
                    'KodePerkiraan' => $item->noaccount . ' ' . $item->namaaccount,
                    'details' => $detailData,
                    'tgl' => (string)$tgl,
                    'nojurnal' => $item->nojurnal_intern,
                    'saldo' => $saldo,
                    'saldod' => $saldoD,
                    'saldok' => $saldoK,
                    'noref' => '',
                );
        //                }
            }

            // $same = false;
            // $data11=[];
            // $i = 0;
            // foreach ($aingMacan as $item) {
            //     $same = false;
            //     $i = 0;
            //     foreach ($data11 as $temtem){
            //         if ( $item->coaid == $temtem['coaid']) {
            //             $same = true;
            //             $data11[$i]['hargasatuand'] = ((float)$temtem['hargasatuand'] + (float)$item->hargasatuand);
            //             $data11[$i]['hargasatuank'] =  ((float)$temtem['hargasatuank'] + (float)$item->hargasatuank);
            //             if($item->saldonormaladd =='K'){
            //                 $data11[$i]['saldo'] =  ((float)$data11[$i]['hargasatuand'] - (float)$data11[$i]['hargasatuank']);
            //             }
            //             $i =$i +1;
            //         }
            //         if ($same == false){
            //             $data11[] = array(
            //                 'hargasatuand' => $item->hargasatuand,
            //                 'hargasatuank' => $item->hargasatuank,
            //                 'keteranganlainnya' => 'Total',
            //                 'noaccount' => $item->noaccount,
            //                 'saldonormaladd' => 'D',
            //                 'saldonormalmin' => 'K',
            //                 'tglbuktitransaksi' => '0-0-0-0-0',
            //                 'coaid' => $item->coaid,
            //                 'noaccount' => '',
            //                 'namaaccount' => $item->namaaccount,
            //                 'KodePerkiraan' => $item->noaccount . ' ' . $item->namaaccount,
            //                 'details' => [],
            //                 'tgl' => '0-0-0-0-0',
            //                 'nojurnal' => '',
            //                 'saldo' => 0,
            //                 'noref' => '',
            //             );
            //         }
            //     }
                // foreach ($data11 as $temtem){
                //     $data10[] = $temtem;
                // }
            // }
            $aingSA[] =array(
                'hargasatuand' => 0,
                'hargasatuank' => 0,
            );
        }
        if ($request['noaccount'] != '-' and $request['noaccount2'] != '-'){
            $data10=[];
            $noaccount = $request['noaccount'];
            $noaccount2 = $request['noaccount2'];
            $aingMacan = DB::select(DB::raw("select * from
                    (select  to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD') as tglbuktitransaksi ,
                    coa.noaccount as noaccount,pj.keteranganlainnya,pj.nobuktitransaksi as noref,
                    sum(pjd.hargasatuand) as hargasatuand,
                    sum(pjd.hargasatuank) as hargasatuank,coa.saldonormaladd,coa.saldonormalmin,coa.id as coaid,pj.nojurnal_intern,coa.namaaccount
                    from postingjurnal_t as pj
                    INNER JOIN postingjurnald_t as pjd on pjd.norecrelated=pj.norec
                    INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                    where pj.kdprofile = $idProfile and pj.tglbuktitransaksi between :tglAwal and :tglAkhir 
                    and ( coa.noaccount between '$noaccount' and '$noaccount2')
                    group by to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD'),pj.keteranganlainnya,pj.deskripsiproduktransaksi,coa.saldonormaladd,
                    coa.saldonormalmin,coa.id,pj.nojurnal_intern,coa.namaaccount,coa.noaccount,pj.nobuktitransaksi)as x
                    order by x.noaccount,x.nojurnal_intern ;
                "),
                array(
                    'tglAwal' => $request['tglAwal'] ,
                    'tglAkhir' => $request['tglAkhir'],
                )
            );

            $aingSA = DB::select(DB::raw("
                select case when psa.hargasatuand is null then 0 else psa.hargasatuand end as hargasatuand,
                    case when psa.hargasatuank is null then 0 else psa.hargasatuank end as hargasatuank,psa.objectaccountfk
                    from postingsaldoawal_t as psa
                    INNER join chartofaccount_m as coa on coa.id=psa.objectaccountfk
                    where psa.kdprofile = $idProfile and cast(psa.ym as varchar) ='$data' and psa.statusenabled = 1  and coa.noaccount between '$noaccount' and '$noaccount2';
                ")
            );

            $coaSA ='';
            foreach ($aingMacan as $item) {
                $tgl = date('Y-m-d', strtotime($item->tglbuktitransaksi));
                $detailData = [];

                $sama = false;
                $saldoD = 0;
                $saldoK = 0;
                if ($coaSA != $item->noaccount) {
                    $coaSA = $item->noaccount;
                    $sama = false;
                    foreach ($aingSA as $kutukupret) {
                        if ($item->coaid == $kutukupret->objectaccountfk) {
                            $saldo = 0;
                            $saldoD = 0;
                            $saldoK = 0;
                        //    if ($item->saldonormaladd =='K') {
                        //        $saldo = $kutukupret->hargasatuand;
                        //    } else {
                        //        $saldo = $kutukupret->hargasatuank;
                        //    }
                        

                            //ini sebelumnya 2023-09-08
                            // if($saldoD > $saldoK){
                            //     $saldoD = $saldoD + $kutukupret->hargasatuand - $kutukupret->hargasatuank;
                            //     $saldoK = 0;
                            // }else{
                            //     $saldoD = 0;
                            //     $saldoK = $saldoK + $kutukupret->hargasatuank - $kutukupret->hargasatuand ;
                            // }

                            //ini ubahannya 2023-09-08
                            if ($kutukupret->hargasatuand > 0) {
                                $saldo = $kutukupret->hargasatuand;
                            } else {
                                $saldo = $kutukupret->hargasatuank;
                            }
                            $saldoD = $saldoD + $kutukupret->hargasatuand;
                            $saldoK = $saldoK + $kutukupret->hargasatuank;
                        
                            $data10[] = array(
                                'hargasatuand' => $kutukupret->hargasatuand,
                                'hargasatuank' => $kutukupret->hargasatuank,
                                'keteranganlainnya' => 'Saldo Awal',
                                'noaccount' => $item->noaccount,
                                'saldonormaladd' => 'D',
                                'saldonormalmin' => 'K',
                                'tglbuktitransaksi' => $data . '31',
                                'coaid' => $item->coaid,
                                'noaccount' => '',
                                'namaaccount' => $item->namaaccount,
                                'KodePerkiraan' => $item->noaccount . ' ' . $item->namaaccount,
                                'details' => [],
                                'tgl' => (string)$data . '31',
                                'nojurnal' => '',
                                'saldo' => $saldo,
                                'saldod' => $saldoD,
                                'saldok' => $saldoK,
                                'noref' => '',
                            );
                            $sama = true;
                        }
                    }
                    if ($sama == false) {
                        $saldo = 0;
                        $data10[] = array(
                            'hargasatuand' => 0,
                            'hargasatuank' => 0,
                            'keteranganlainnya' => 'Saldo Awal',
                            'noaccount' => $item->noaccount,
                            'saldonormaladd' => 'D',
                            'saldonormalmin' => 'K',
                            'tglbuktitransaksi' => $data . '31',
                            'coaid' => $item->coaid,
                            'noaccount' => '',
                            'namaaccount' => $item->namaaccount,
                            'KodePerkiraan' => $item->noaccount . ' ' . $item->namaaccount,
                            'details' => [],
                            'tgl' => (string)$data . '31',
                            'nojurnal' => '',
                            'saldo' => 0,
                            'saldod' => 0,
                            'saldok' => 0,
                            'noref' => '',
                        );
                    }

                }
                if($item->saldonormaladd =='K'){
                    $saldo = $saldo + $item->hargasatuank - $item->hargasatuand;
                    $saldoD = 0;
                    $saldoK = $saldo;
                }else{
                    $saldo = $saldo + $item->hargasatuand - $item->hargasatuank;
                    $saldoD = $saldo;
                    $saldoK = 0;
                }
                
                // $saldoD = $saldoD + $kutukupret->hargasatuand;
                // $saldoK = $saldoK + $kutukupret->hargasatuank;
                // if($saldoD > $saldoK){
                //     $saldoD = $saldoD + $item->hargasatuand - $item->hargasatuank;
                //     $saldoK = 0;
                // }else{
                //     $saldoD = 0;
                //     $saldoK = $saldoK + $item->hargasatuank - $item->hargasatuand ;
                // }
                // $saldo = 0;//$saldo + $item->hargasatuand - $item->hargasatuank;


                $data10[] = array(
                    'hargasatuand' => $item->hargasatuand,
                    'hargasatuank' => $item->hargasatuank,
                    'keteranganlainnya' => $item->keteranganlainnya,
                    'noaccount' => $item->noaccount,
                    'saldonormaladd' => $item->saldonormaladd,
                    'saldonormalmin' => $item->saldonormalmin,
                    'tglbuktitransaksi' => $item->tglbuktitransaksi,
                    'coaid' => $item->coaid,
                    'noaccount' => $item->noaccount,
                    'namaaccount' => $item->namaaccount,
                    'KodePerkiraan' => $item->noaccount . ' ' . $item->namaaccount,
                    'details' => $detailData,
                    'tgl' => (string)$tgl,
                    'nojurnal' => $item->nojurnal_intern,
                    'saldo' => $saldo,
                    'saldod' => $saldoD,
                    'saldok' => $saldoK,
                    'noref' => $item->noref,
                );
        //                }
            }

            // $same = false;
            // $data11=[];
            // $i = 0;
            // foreach ($aingMacan as $item) {
            //     $same = false;
            //     $i = 0;
            //     foreach ($data11 as $temtem){
            //         if ( $item->coaid == $temtem['coaid']) {
            //             $same = true;
            //             $data11[$i]['hargasatuand'] = (float)$temtem['hargasatuand'] + (float)$item->hargasatuand;
            //             $data11[$i]['hargasatuank'] = (float)$temtem['hargasatuank'] + (float)$item->hargasatuank;
            //             $data11[$i]['saldo'] = (float)$data11[$i]['saldo'] + ((float)$data11[$i]['hargasatuand'] - (float)$data11[$i]['hargasatuank']);
            //         }
            //         $i =$i +1;
            //     }
            //     if ($same == false){
            //         $data11[] = array(
            //             'hargasatuand' => $item->hargasatuand,
            //             'hargasatuank' => $item->hargasatuank,
            //             'keteranganlainnya' => 'Total',
            //             'noaccount' => $item->noaccount,
            //             'saldonormaladd' => 'D',
            //             'saldonormalmin' => 'K',
            //             'tglbuktitransaksi' => '0-0-0-0-0',
            //             'coaid' => $item->coaid,
            //             'noaccount' => '',
            //             'namaaccount' => $item->namaaccount,
            //             'KodePerkiraan' => $item->noaccount . ' ' . $item->namaaccount,
            //             'details' => [],
            //             'tgl' => '0-0-0-0-0',
            //             'nojurnal' => '',
            //             'saldo' => (float) $item->hargasatuand - (float) $item->hargasatuank,
            //             'noref' => '',
            //             );
            //     }
            // }
            // foreach ($data11 as $temtem){
            //     $data10[] = $temtem;
            // }
        }
            $aingSA[] =array(
                'hargasatuand' => 0,
                'hargasatuank' => 0,
            );
        // }

        $result = array(
            'saldoawal' => $aingSA,
            // 'dat' => $data11,
            'data' => $data10,
        //            'coa' => $coaAing,
            'by' => '@epic'
        );
        return $this->respond($result);
    }
    public function getDetailJurnalRev2018BukuBesar(Request $request) {

        $idProfile = (int) $this->kdProfile;
        $data = DB::select(DB::raw("select pj.nojurnal,coa.noaccount,
                case when pjd.hargasatuand = 0 then '--- ' || coa.namaaccount else coa.namaaccount end as namaaccount,
                pj.namaproduktransaksi as keteranganlainnya,pjd.hargasatuand,pjd.hargasatuank from postingjurnal_t as pj
                INNER JOIN postingjurnald_t as pjd on pj.norec=pjd.norecrelated
                INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                where pj.kdprofile = $idProfile and nojurnal_intern=:nojurnal
                and coa.id=:accountid
                and pjd.hargasatuand + pjd.hargasatuank > 0
                --and coa.id=:accountid;
                "),
            array(
                'nojurnal' => $request['nojurnal'],
                'accountid' => $request['accountid'],
            )
        );
        return $this->respond($data);
    }
   
    public function getRekananPaging(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $req = $request->all();
        $data = [];
        $data = DB::table('rekanan_m as pr')
            ->select('pr.id', 'pr.namarekanan')
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', '=', true)
            ->orderBy('pr.namarekanan');

        //        if ($request['jenis'] != 'noaccount'){
        if (
            isset($req['filter']['filters'][0]['value']) &&
            $req['filter']['filters'][0]['value'] != "" &&
            $req['filter']['filters'][0]['value'] != "undefined"
        ) {
            $data = $data->where('pr.namaaccount', 'ilike', '%' . $req['filter']['filters'][0]['value'] . '%')
                ->orWhere('pr.noaccount', 'ilike', $req['filter']['filters'][0]['value'] . '%');
        };
        if (
            isset($req['name']) &&
            $req['name'] != "" &&
            $req['name'] != "undefined"
        ) {
            $data = $data->where('pr.namarekanan', 'ilike', '%' . $req['name'] . '%')
                ->orWhere('pr.namarekanan', 'ilike', $req['name'] . '%');
        };
        if (
            isset($req['limit']) &&
            $req['limit'] != "" &&
            $req['limit'] != "undefined"
        ) {
            $data = $data->take($req['limit'] . '%');
        } else {
            $data = $data->take(10);
        }



        $data = $data->get();
        return $this->respond($data);
    }
    public function getDataBukuBesarPembantu(Request $request) {
    
        $idProfile = (int) $this->kdProfile;
        $mydate = $request['tglAwal'];
        $daystosum = '1';

        $datesum = date('d-m-Y', strtotime($mydate.' - '.$daystosum.' months'));
        $data = date('Ym', strtotime($datesum));
        $nocoaid = '';
        $idrekanan="";
        if ($request['rknid'] != "" && $request['rknid'] != "undefined" &&  $request['rknid'] != "null" ) {
            $idrekanan= "and rkn.id=" . $request['rknid'] . "";
            
        } 

        $idnoaccount="";
        if ($request['noaccount'] != "" && $request['noaccount'] != "undefined" &&  $request['noaccount'] != "null" ) {
            $idnoaccount= "and coa.id=" . $request['noaccount'] . "";
            $nocoaid=" and psa.objectaccountfk=" . $request['noaccount'] . "";
        } 
        // join  strukpelayananpenjamin_t as spp on spp.nostrukfk=sp.norec
        $aingSA = [];
        $aingMacan = [];
        $mapCOA = DB::select(DB::raw("		
            select DISTINCT x.id from (								
            select rkn.namarekanan,coa.namaaccount,coa.id from chartofaccountmapjurnal_t  as map 
            left JOIN chartofaccount_m as coa on coa.id=map.objectcoadebetfk
            left JOIN rekanan_m as rkn on rkn.id=map.objectrekananfk
            where map.kdprofile=$idProfile
            $idrekanan
            
            union all 
            select rkn.namarekanan,coa.namaaccount ,coa.id from chartofaccountmapjurnal_t  as map 
            left JOIN chartofaccount_m as coa on coa.id=map.objectcoakreditfk
            left JOIN rekanan_m as rkn on rkn.id=map.objectrekananfk
            where map.kdprofile=$idProfile
            $idrekanan) as x;
        "));
        $paramsCoa = '';
        foreach($mapCOA as $coas){
            $paramsCoa = $paramsCoa .','.$coas->id;
        }
        $paramsCoa = substr($paramsCoa, 1, strlen($paramsCoa)-1);
       
        if ($request['jenis'] == 'Piutang'){

            $piutang ="" ;//" where  coaid in (67) ";
            $aingMacan = DB::select(DB::raw("select * from
                (select * from
                (select  to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD') as tglbuktitransaksi ,
                sp.nostruk as noaccount,'Verifikasi tagihan ' || pd.noregistrasi || '/' || ps.nocm || ' '  || ps.namapasien  as  keteranganlainnya,
                sum(pjd.hargasatuand) as hargasatuand,
                sum(pjd.hargasatuank) as hargasatuank,coa.saldonormaladd,coa.saldonormalmin,coa.id as coaid,pj.nojurnal_intern
                from postingjurnaltransaksi_t as pj
                INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec 
                INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk 
                INNER JOIN strukpelayanan_t as sp on sp.norec=pj.norecrelated
                INNER JOIN pasiendaftar_t as pd on sp.noregistrasifk=pd.norec
                INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                left JOIN rekanan_m as rkn on rkn.id=pd.objectrekananfk
                inner join chartofaccountmapjurnal_t as maps on maps.objectrekananfk = rkn.id and  coa.id=maps.objectcoakreditfk
                where pj.kdprofile = $idProfile and pj.tglbuktitransaksi between :tglAwal and :tglAkhir  $idrekanan  --and rkn.id=:rknid --and sp.statusenabled not in ('f')
                group by to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD'),sp.nostruk,'Verifikasi tagihan ' || pd.noregistrasi || '/' || ps.nocm || ' '  || ps.namapasien ,pj.deskripsiproduktransaksi,coa.saldonormaladd,
                coa.saldonormalmin,coa.id,pj.nojurnal_intern)as x  $piutang
                
                union all

                select * from
                (select  to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD') as tglbuktitransaksi ,
                sbm.nosbm as noaccount,'Pembayaran tagihan ' || pd.noregistrasi || '/' || ps.nocm || ' '  || ps.namapasien  as  keteranganlainnya,
                sum(pjd.hargasatuand) as hargasatuand,
                sum(pjd.hargasatuank) as hargasatuank,coa.saldonormaladd,coa.saldonormalmin,coa.id as coaid,pj.nojurnal_intern
                from postingjurnaltransaksi_t as pj
                INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec 
                INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=pj.norecrelated
                INNER JOIN strukpelayanan_t as sp on sp.norec=sbm.nostrukfk
                INNER JOIN pasiendaftar_t as pd on sp.noregistrasifk=pd.norec
                INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                left JOIN rekanan_m as rkn on rkn.id=pd.objectrekananfk
                inner join chartofaccountmapjurnal_t as maps on maps.objectrekananfk = rkn.id and  coa.id=maps.objectcoadebetfk
                where pj.kdprofile = $idProfile and pj.tglbuktitransaksi between :tglAwal and :tglAkhir  $idrekanan --and sp.statusenabled not in ('f')
                group by to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD'),sbm.nosbm,'Pembayaran tagihan ' || pd.noregistrasi || '/' || ps.nocm || ' '  || ps.namapasien ,pj.deskripsiproduktransaksi,coa.saldonormaladd,
                coa.saldonormalmin,coa.id,pj.nojurnal_intern)as y  $piutang 
                
                ) as z 
                order by z.tglbuktitransaksi
            "),
                array(
                    'tglAwal' => $request['tglAwal'] ,
                    'tglAkhir' => $request['tglAkhir'] ,
                    // 'rknid' => $request['rknid'] ,
                )
            );

           
            $aingSA = DB::select(DB::raw("
                select
                coa.saldonormaladd,
                case when psa.hargasatuand is null then 0 else psa.hargasatuand end as hargasatuand,
                case when psa.hargasatuank is null then 0 else psa.hargasatuank end as hargasatuank
                from postingsaldoawal_t as psa
                join chartofaccount_m as coa on coa.id=psa.objectaccountfk
                where psa.kdprofile = $idProfile 
                $nocoaid
                --and psa.objectaccountfk=:noakun 
                and cast(psa.ym as varchar) =:tglAwal and psa.statusenabled=1
                and psa.objectaccountfk in ($paramsCoa)
            "),
                array(
                    'tglAwal' => (string)$data ,
                    // 'noakun' => (int)10897 ,
                )
            );
        }
        if ($request['jenis'] == 'Hutang'){
            $piutang = "";// " and coa.objectkategoryaccountfk =2"; //" where  coaid in (11136) ";
            $aingMacan = DB::select(DB::raw("select * from
                (select  to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD') as tglbuktitransaksi ,
                sp.nostruk as noaccount,sp.nostruk || '/' || rkn.id || ' '  || rkn.namarekanan  as  keteranganlainnya,
                sum(pjd.hargasatuand) as hargasatuand,
                sum(pjd.hargasatuank) as hargasatuank,coa.saldonormaladd,coa.saldonormalmin,coa.id as coaid,pj.nojurnal_intern
                from postingjurnaltransaksi_t as pj
                INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                INNER JOIN strukpelayanandetail_t as spd on spd.norec=pj.norecrelated
                INNER JOIN strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                left JOIN rekanan_m as rkn on rkn.id=sp.objectrekananfk
                inner join chartofaccountmapjurnal_t as maps on maps.objectrekananfk = rkn.id and  coa.id=maps.objectcoadebetfk
                where pj.kdprofile = $idProfile and pj.tglbuktitransaksi between :tglAwal and :tglAkhir  
               
                $idrekanan
                $idnoaccount
                --and rkn.id=:rknid --and sp.statusenabled not in ('f')
                group by to_char(pj.tglbuktitransaksi, 'YYYY-MM-DD'),sp.nostruk,sp.nostruk || '/' || rkn.id || ' '  || rkn.namarekanan ,pj.deskripsiproduktransaksi,coa.saldonormaladd,
                coa.saldonormalmin,coa.id,pj.nojurnal_intern)as x  $piutang
                order by x.tglbuktitransaksi ;
            "),
                array(
                    'tglAwal' => $request['tglAwal'] ,
                    'tglAkhir' => $request['tglAkhir'] ,
                    // 'rknid' => $request['rknid'] ,
                )
            );

            $aingSA = DB::select(DB::raw("
                select
                coa.saldonormaladd,
                case when psa.hargasatuand is null then 0 else psa.hargasatuand end as hargasatuand,
                case when psa.hargasatuank is null then 0 else psa.hargasatuank end as hargasatuank
                from postingsaldoawal_t as psa
                join chartofaccount_m as coa on coa.id=psa.objectaccountfk
                where psa.kdprofile = $idProfile 
                $nocoaid
               -- and psa.objectaccountfk=:noakun 
                and psa.ym =:tglAwal and psa.statusenabled=1;
            "),
                array(
                    'tglAwal' => (string)$data ,
                    // 'noakun' => (int)11136 ,
                )
            );
        }


        $data10=[];
        foreach ($aingMacan as $item) {

            //cari detail
            $tgl = date('Y-m-d', strtotime($item->tglbuktitransaksi));
            $detailData=[];
//            foreach ($dataDetail as $det){
//                if ($det->coaid2 == $item->coaid){
//                    if (strpos((string)'  '. $det->tglpelayanan , (string)$tgl) != false){
//                            $detailData[]=$det;
//                    }
//                }
//            }

            $data10[] = array(
                'hargasatuand' => $item->hargasatuand,
                'hargasatuank' => $item->hargasatuank,
                'keteranganlainnya' => $item->keteranganlainnya,
                'noaccount' => $item->noaccount,
                'saldonormaladd' => $item->saldonormaladd,
                'saldonormalmin' => $item->saldonormalmin,
                'tglbuktitransaksi' =>$item->tglbuktitransaksi,
                'coaid' => $item->coaid,
                'details' => $detailData,
//                'tglpelayanan'=>(string)$det->tglpelayanan ,
                'tgl' => (string)$tgl,
                'nojurnal'=> $item->nojurnal_intern,
            );

        }

        $datdatdat = array(
            'tglAwal' => (string)$data ,
            'noakun' => (int)$request['noaccount'] ,
        );
        if (count($aingSA) == 0){
            $aingSA[] =array(
                'hargasatuand' => 0,
                'hargasatuank' => 0,
                'datareq' => (string)$data,
            );
        }
        $result = array(
            'saldoawal' => $aingSA,
            'dat' => $datdatdat,
            'data' => $data10,
            'by' => 'as@epic'
        );
        return $this->respond($result);
    }

}
