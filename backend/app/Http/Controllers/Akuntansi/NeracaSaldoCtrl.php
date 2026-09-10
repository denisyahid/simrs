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
use App\Models\Transaksi\PostingSaldoAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;

class NeracaSaldoCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDataTrialBalancerevNeracalajur(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $aingMacan = DB::select(DB::raw("select coa.id,coa.noaccount,coa.namaaccount,cast(sum(pjd.hargasatuand) as FLOAT8) as debet,
        cast(sum(pjd.hargasatuank) as FLOAT8) as kredit, case when pj.adj is null then 'false' ELSE pj.adj end AS adj,coa.saldonormaladd 
                from chartofaccount_m as coa
                left JOIN postingjurnald_t as pjd on coa.id=pjd.objectaccountfk
                left JOIN postingjurnal_t as pj on pjd.norecrelated=pj.norec
                    join suratkeputusan_m as sk on sk.id=coa.suratkeputusanfk
                where coa.kdprofile = :idProfile and tglbuktitransaksi between :tglAwal and :tglAkhir and sk.statusenabled=true
                --and pj.adj = false
                group by coa.id,coa.noaccount,coa.namaaccount,case when pj.adj is null then 'false' ELSE pj.adj end,coa.saldonormaladd 
                order by coa.noaccount;
            "),
            array(
                'tglAwal' => $request['tglAwal'] ,
                'tglAkhir' => $request['tglAkhir'] ,
                'idProfile' => $idProfile,
            )
        );
        if(count($aingMacan) ==0){
            return $this->respond([]);
        }
        $aingMacanAdj = DB::select(DB::raw("select coa.id,coa.noaccount,coa.namaaccount,sum(pjd.hargasatuand) as debet,
                sum(pjd.hargasatuank) as kredit,pj.adj,coa.saldonormaladd 
                from chartofaccount_m as coa
                left JOIN postingjurnald_t as pjd on coa.id=pjd.objectaccountfk
                left JOIN postingjurnal_t as pj on pjd.norecrelated=pj.norec
                    join suratkeputusan_m as sk on sk.id=coa.suratkeputusanfk
                where coa.kdprofile = :idProfile and tglbuktitransaksi between :tglAwal and :tglAkhir and sk.statusenabled=true
                and pj.adj = true
                group by coa.id,coa.noaccount,coa.namaaccount,pj.adj,coa.saldonormaladd 
                order by coa.noaccount;
            "),
            array(
                'tglAwal' => $request['tglAwal'] ,
                'tglAkhir' => $request['tglAkhir'] ,
                'idProfile' => $idProfile,
            )
        );
        $mydate = $request['tglAwal'];
        $daystosum = '1';

        $datesum = date('d-m-Y', strtotime($mydate.' - '.$daystosum.' months'));
        $data = date('Ym', strtotime($datesum));
        $strData = (string)$data;
        $strDT = substr($strData, 4, 2);

        $dataCoa = DB::select(DB::raw
            ("
                select coa.id,coa.noaccount,coa.namaaccount,psa.ym,
                case when psa.hargasatuand is null then 0 else psa.hargasatuand end as debet,
                case when psa.hargasatuank is null then 0 else psa.hargasatuank end as kredit
                from chartofaccount_m as coa
                join suratkeputusan_m as sk on sk.id=coa.suratkeputusanfk
                left JOIN (select * from postingsaldoawal_t where statusenabled=1 and cast(ym as varchar) = :strdata) as psa  on psa.objectaccountfk=coa.id
                where coa.kdprofile = :idProfile and sk.statusenabled=true and coa.statusenabled=true  order by coa.noaccount
            "),
            array(
                'strdata' => $strData ,
                'idProfile' => $idProfile,
            )
        );
        $sama=false;
        // $pada = false;
        $result = [];
        foreach ($dataCoa as $coa){
            $sama=false;
            if ($coa->ym == (string)$data) {
                $debetAwal = $coa->debet;
                $kreditAwal = $coa->kredit;
            }else{
                $debetAwal = 0;
                $kreditAwal = 0;
            }


            $debetMutasi = 0;
            $kreditMutasi = 0;
            $debetAdj = 0;
            $kreditAdj = 0;
            foreach ($aingMacan as $item) {
                $sama = false;
                if ($item->id == $coa->id ) {
                    // if($item->adj == true){
                        // $debetAdj = $item->debet;
                        // $kreditAdj = $item->kredit;
                    // }else{
                        $debetMutasi = $item->debet;
                        $kreditMutasi = $item->kredit;
                    // }
                    $result[] = array(
                        'idaccount' => $coa->id,
                        'noaccount' => $item->noaccount,
                        'namaaccount' => $item->namaaccount,
                        'saldonormaladd' => $item->saldonormaladd,
                        'debetAwal' => $debetAwal,
                        'kreditAwal' => $kreditAwal,
                        'debetMutasi' => $debetMutasi,
                        'kreditMutasi' => $kreditMutasi,
                        'debetadj' => 0,//$debetAdj,
                        'kreditadj' => 0,//$kreditAdj,
                    );
                    $sama = true;
                    break;
                }
            }
            if ($sama == false  ){//&& $request['fldetail'] == 'true') {
                $result[] = array(
                    'idaccount' => $coa->id,
                    'noaccount' => $coa->noaccount,
                    'namaaccount' => $coa->namaaccount,
                    'saldonormaladd' => $item->saldonormaladd,
                    'debetAwal' => $debetAwal,
                    'kreditAwal' => $kreditAwal,
                    'debetMutasi' => 0,
                    'kreditMutasi' => 0,
                    'debetadj' => 0,
                    'kreditadj' => 0,
                );
            }

        }
        
        foreach ($aingMacanAdj as $adj){
            for ($i=0; $i < count($result); $i++) { 
                if ($adj->id == $result[$i]['idaccount'] ) {
                    $result[$i]['debetadj'] = (float)$result[$i]['debetadj'] + $adj->debet;
                    $result[$i]['kreditadj'] = (float)$result[$i]['kreditadj'] + $adj->kredit;
                }
            }
        }
        $rslt = [];
        if($request['fldetail'] == 'false'){
            for ($i=0; $i < count($result); $i++) { 
                if ( (float)$result[$i]['debetMutasi'] > 0 
                || (float)$result[$i]['kreditMutasi'] > 0 
                || (float)$result[$i]['debetadj'] > 0 
                || (float)$result[$i]['kreditadj'] > 0
                || (float)$result[$i]['debetAwal'] > 0 
                || (float)$result[$i]['kreditAwal'] > 0 
                ) {
                    $rslt[] = $result[$i];
                }
            }
        }else{
            $rslt = $result;
        }
        return $this->respond($rslt);

    }
    public function getDataTrialBalance(Request $request) {
        $idProfile = (int) $this->kdProfile;
        $req=$request->all();
        $aingMacan = DB::select(DB::raw("select coa.id,coa.noaccount,coa.namaaccount,sum(pjd.hargasatuand) as debet,
                sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                left JOIN postingjurnald_t as pjd on coa.id=pjd.objectaccountfk
                left JOIN postingjurnal_t as pj on pjd.norecrelated=pj.norec
                    join suratkeputusan_m as sk on sk.id=coa.suratkeputusanfk
                where coa.kdprofile = $idProfile and tglbuktitransaksi between :tglAwal and :tglAkhir and sk.statusenabled=true
                group by coa.id,coa.noaccount,coa.namaaccount
                order by coa.noaccount;
            "),
            array(
                'tglAwal' => $request['tglAwal'] ,
                'tglAkhir' => $request['tglAkhir'] ,
            )
        );
        $mydate = $request['tglAwal'];
        $daystosum = '1';

        $datesum = date('d-m-Y', strtotime($mydate.' - '.$daystosum.' months'));
        $data = date('Ym', strtotime($datesum));
        $strData = (string)$data;
        $strDT = substr($strData, 4, 2);

//        if ($strDT != '12'){
        $dataCoa = DB::select(DB::raw("
                    select coa.id,coa.noaccount,coa.namaaccount,psa.ym,
                    case when psa.hargasatuand is null then 0 else psa.hargasatuand end as debet,
                    case when psa.hargasatuank is null then 0 else psa.hargasatuank end as kredit
                    from chartofaccount_m as coa
                    join suratkeputusan_m as sk on sk.id=coa.suratkeputusanfk
                    left JOIN (select * from postingsaldoawal_t where statusenabled=1 and cast(ym as varchar) = '$strData') as psa  on psa.objectaccountfk=coa.id
                    where coa.kdprofile = $idProfile and sk.statusenabled=true and coa.statusenabled=true  order by coa.noaccount
                ")
        );
//        }else{
//            $dataCoa = DB::select(DB::raw("
//                    select coa.id,coa.noaccount,coa.namaaccount,psa.ym,
//                    case when psa.hargasatuand is null or left(coa.noaccount,1) in ('4','5') then 0 else psa.hargasatuand end as debet,
//                    case when psa.hargasatuank is null or left(coa.noaccount,1) in ('4','5') then 0 else psa.hargasatuank end as kredit
//                    from chartofaccount_m as coa
//                    join suratkeputusan_m as sk on sk.id=coa.suratkeputusanfk
//                    left JOIN (select * from postingsaldoawal_t where statusenabled=1 and ym = '$strData') as psa  on psa.objectaccountfk=coa.id
//                    where  sk.statusenabled=1 and coa.statusenabled=1  order by coa.noaccount
//                ")
//            );
//        }
        $sama=false;
        foreach ($dataCoa as $coa){
            $sama=false;
            if ($coa->ym == (string)$data) {
                $debetAwal = $coa->debet;
                $kreditAwal = $coa->kredit;
            }else{
                $debetAwal = 0;
                $kreditAwal = 0;
            }


            foreach ($aingMacan as $item) {
                $sama = false;
                if ($item->id == $coa->id) {
                    $debetMutasi = $item->debet;
                    $kreditMutasi = $item->kredit;
                    $result[] = array(
                        'idaccount' => $coa->id,
                        'noaccount' => $item->noaccount,
                        'namaaccount' => $item->namaaccount,
                        'debetAwal' => $debetAwal,
                        'kreditAwal' => $kreditAwal,
                        'debetMutasi' => $debetMutasi,
                        'kreditMutasi' => $kreditMutasi,
                    );
                    $sama = true;
                    break;
                }
            }
            if ($sama == false) {
                $result[] = array(
                    'idaccount' => $coa->id,
                    'noaccount' => $coa->noaccount,
                    'namaaccount' => $coa->namaaccount,
                    'debetAwal' => $debetAwal,
                    'kreditAwal' => $kreditAwal,
                    'debetMutasi' => 0,
                    'kreditMutasi' => 0,
                    'by' => 'as@epic'
                );
            }

        }

        return $this->respond($result);
    }
    public function SaveClosingJurnal(Request $request)
    {
        ini_set('max_execution_time', 200);

        DB::beginTransaction();
        $dataReq = $request->all();
        $data = $dataReq['data'];
        $idProfile =  $this->kdProfile;

        try {
            $postingSA = PostingSaldoAwal::where('ym', $dataReq['ym'])
                ->delete();
            $dataInsert = [];
            foreach ($data as $item) {
                $dataInsert[] = array(
                    'norec' => $this->Uuid4(),
                    'kdprofile' => $idProfile,
                    'statusenabled' => true,
                    'objectaccountfk' =>  $item['idaccount'],
                    'hargasatuand' =>  $item['debetAkhir'],
                    'hargasatuank' =>  $item['kreditAkhir'],
                    'ym' =>    $dataReq['ym'],

                );
            }
            $chunkSize = 2000; // Adjust the chunk size based on your needs

            collect($dataInsert)->chunk($chunkSize)->each(function ($chunk) {
                DB::table('postingsaldoawal_t')->insert($chunk->toArray());
            });


            // foreach ($data as $item) {
            //     $postingSA = new PostingSaldoAwal();
            //     $norecHead = $postingSA->generateNewId();
            //     $postingSA->norec = $norecHead;
            //     $postingSA->kdprofile = $idProfile;

            //     $postingSA->objectaccountfk = $item['idaccount'];
            //     $postingSA->hargasatuand = $item['debetAkhir'];
            //     $postingSA->hargasatuank = $item['kreditAkhir'];
            //     $postingSA->statusenabled = 1;
            //     $postingSA->ym = $dataReq['ym'];
            //     $postingSA->save();
            // }


            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Closing Jurnal ';

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => 'as@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "data" => $postingSA,
                    "e" => $e->getMessage() . ' ' . $e->getLine(),
                    "as" => 'as@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function SaveBatalClosingJurnal(Request $request)
    {
        DB::beginTransaction();
        $dataReq = $request->all();
        $idProfile =  $this->kdProfile;
        try {
            $postingSA = PostingSaldoAwal::where('ym', $dataReq['ym'])
                ->where('kdprofile', $idProfile)
                ->delete();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Closing Jurnal ';

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => 'as@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "data" => $postingSA,
                    "as" => 'as@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
