<?php

namespace App\Http\Controllers\Bridging;
use App\Http\Controllers\Controller;
use App\Master\Agama;
use App\Master\Pegawai;
use App\Traits\CrudMaster;
use App\Traits\Valet;
use App\Transaksi\LoggingUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Master\Agama as MasterAgama;
use App\Models\Master\JenisKelamin;
use App\Models\Master\Pegawai as MasterPegawai;
use App\Models\Master\Pendidikan;
use App\Models\Master\Profile as MasterProfile;
use App\Models\Master\StatusPerkawinan;
use App\Models\Transaksi\DetailDiagnosaPasien;
use App\Models\Transaksi\DetailDiagnosaTindakanPasien;
use App\Models\Transaksi\DiagnosaPasien;
use App\Models\Transaksi\DiagnosaTindakanPasien;
use App\Web\LoginUser;
use App\Web\Profile;
use App\Web\Token;
// use App\Web\Admin\ProfileHistoriAwards as ProfileHistoriAwards;
// use App\Web\Admin\Awards as Awards_M;
// use App\Web\Asal as Asal_M;
// use App\Transaksi\StrukHistori as StrukHistori_T;
use DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Namshi\JOSE\Base64\Base64UrlSafeEncoder;
use Namshi\JOSE\JWT;
use Namshi\JOSE\JWS;
use Namshi\JOSE\Base64\Encoder;
use Webpatser\Uuid\Uuid;

use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Hmac\Sha384;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use PhpParser\Node\Stmt\Catch_;

class TelemedicineCtrl extends Controller
{
    use Valet;

    public function __construct() {
        parent::__construct($skip_authentication=false);
    }
    public function getDetailPasien(Request $request) {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('pasien_m as ps')
            ->leftjoin('jeniskelamin_m as jk','jk.id','=','ps.objectjeniskelaminfk')
            ->leftjoin('agama_m as ag','ag.id','=','ps.objectagamafk')
            ->leftjoin('golongandarah_m as gd','gd.id','=','ps.objectgolongandarahfk')
            ->leftjoin('pekerjaan_m as pk','pk.id','=','ps.objectpekerjaanfk')
            ->leftjoin('pendidikan_m as pdd','pdd.id','=','ps.objectpendidikanfk')
            ->leftjoin('statusperkawinan_m as spkw','spkw.id','=','ps.objectstatusperkawinanfk')
            ->leftjoin('alamat_m as al','al.nocmfk','=','ps.id')
            ->leftjoin('suku_m as sk','sk.id','=','ps.objectsukufk')
            ->select(DB::raw("ps.id,ps.nocm,
    ps.noidentitas as nik,	ps.namapasien,	ps.tgllahir,	ps.nohp AS telepon,	sk.suku as etnis,
	ps.bahasa,	jk.jeniskelamin,	ag.agama,	gd.golongandarah,	al.alamatlengkap,	al.alamatemail as email,
	ps.namaayah as wali,	'Orang Tua' as hubunganwali,	null as alamatwali,
	null as teleponwali,	null as faksesbpjs,	null as alamatfaskes,	null as teleponfaskes,	pk.pekerjaan,
	pdd.pendidikan,	spkw.statusperkawinan,ps.nobpjs"))
            ->where('ps.statusenabled',true)
            ->where('ps.kdprofile',$kdProfile);
        $stt = false;
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
            $stt = true;
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
            $stt = true;
        }
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
             $stt = true;
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','ilike', '%'.$request['namapasien'].'%');
            $stt = true;
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        // $data = $data->take(50);
        $data = $data->first();


        if(!empty($data ) && $stt==true){

            $deptJalan = explode (',',$this->settingDataFixed('kdDepartemenRawatJalanFix',$kdProfile));
            $kdDepartemenRawatJalan = [];
            foreach ($deptJalan as $item){
                $kdDepartemenRawatJalan []=  (int)$item;
            }

            $data2  = DB::table('antrianpasiendiperiksa_t as apd')
                ->join('pasiendaftar_t as pd','pd.norec','=','apd.noregistrasifk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->select('apd.noantrian','ru.id as idruangan','ru.namaruangan',DB::raw("case when apd.tgldipanggilsuster is null then false else true end as ispanggil"))
                ->whereBetween('pd.tglregistrasi',[date('Y-m-d 00:00'),date('Y-m-d 23:59')])
                ->where('pd.statusenabled',true)
                ->whereIn('ru.objectdepartemenfk',$kdDepartemenRawatJalan)
                ->where('pd.nocmfk',$data->id)
                ->first();
            $data3  = DB::table('pasiendaftar_t as pd')
                ->where('pd.nocmfk',$data->id)
                ->whereNull('pd.tglpulang')
                ->where('pd.statusenabled',true)
                ->first();
            $data->queue = $data2;
            $data->status = empty($data3)?null:'masih dirawat';
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
        // return $this->respondV2($data);
    }
    public function getPerawatan(Request $request) {
        try{


            $kdProfile = $this->kdProfile;
            $data = \DB::table('pasien_m as ps')
                ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
                ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                ->leftJoin('batalregistrasi_t as br', 'br.pasiendaftarfk', '=', 'pd.norec')
                ->join('profile_m as pro', 'pro.id', '=', 'pd.kdprofile')
                ->select(DB::raw("ps.noidentitas as nik,pd.tglregistrasi,ps.id as idpasien,ps.nocm,pd.noregistrasi,
                                ps.namapasien,ru.namaruangan,
                            pg.namalengkap as namadokter,pd.tglpulang,
                            CASE when ru.objectdepartemenfk in (16,25,26) then 1 else 0 end as statusinap,'' AS kddiagnosa,pd.norec,ps.ihs_number,
                            pro.namalengkap as nama_rs,pro.ihs_id as id_ihs_rs"))
                ->whereNull('br.pasiendaftarfk')
                ->where('ps.kdprofile',$kdProfile);
            $cari = false;
            if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
                $cari = true;
                $data = $data->where('ps.noidentitas','=', $request['nik']);
            }
            if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
                $cari = true;
                $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
            }
            if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
                $cari = true;
                $data = $data->where('ps.id','=', $request['id_pasien']);
            }
            if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
                $cari = true;
                $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
            }
            if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
                $cari = true;
                $data = $data->where('ps.namapasien','iilike', '%'.$request['namapasien'].'%');
            }
            if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
    //            $data = $data->where('ps.nokk','=', $request['nokk']);
            }
            if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
                $cari = true;
                $data = $data->limit( $request['limit']);
            }
            if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
                $cari = true;
                $data = $data->offset($request['page']);
            }
            $data = $data->where('ps.statusenabled', true);
            $data = $data->orderBy('pd.tglregistrasi', 'desc');
            $data = $data->get();
            $norecaPd = '';
            $diagnosa = '';
    //        return $this->respondV2($data);
            foreach ($data as $ob){
                $norecaPd = $norecaPd.",'".$ob->norec . "'";
    //                        $ob->kddiagnosa = [];
            }
            $norecaPd = substr($norecaPd, 1, strlen($norecaPd)-1);
    //                    $diagnosa = [];
            if($norecaPd!= ''){
                $diagnosa = DB::select(DB::raw("
                            select dg.kddiagnosa || ': ' || dg.namadiagnosa AS diagnosa,ddp.noregistrasifk as norec_apd,apd.noregistrasifk AS norec_pd
                            from antrianpasiendiperiksa_t AS apd
                            inner join detaildiagnosapasien_t as ddp ON ddp.noregistrasifk = apd.norec
                            left join diagnosapasien_t as dp on dp.norec=ddp.objectdiagnosapasienfk
                            left join diagnosa_m as dg on ddp.objectdiagnosafk=dg.id
                            where ddp.objectjenisdiagnosafk = 1 and apd.noregistrasifk in ($norecaPd) "));
                $i = 0;
                foreach ($data as $h){
                    foreach ($diagnosa as $d){
                        if($data[$i]->norec == $d->norec_pd){
    //                        return $this->respondV2($d);
                            if ($d->diagnosa != null){
    //                            return $this->respondV2($data[$i]->diagnosa);
                                $data[$i]->kddiagnosa = $data[$i]->kddiagnosa . ', ' . $d->diagnosa;
                            }
                        }
                    }
                    $i++;
                }
            }
            $ihs_ID =  MasterProfile::where('statusenabled',true)->first()->ihs_id;
            $d=0;
            $result=[];
            if($cari == true && count($data)> 0 ){
                if($data[0]->ihs_number != null){
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest ['data']= null;
                    $objetoRequest ['method']= "GET";
                    $objetoRequest ['url']= "Encounter?subject=Patient/".$data[0]->ihs_number;
                    $ihs = app('App\Http\Controllers\Bridging\IHSController')->ihsTools($objetoRequest,true);

                    if($ihs->total > 0){
                        foreach($ihs->entry as $items){
                            $element =  $items->resource;

                            $org_ID = [];
                            if(isset($element->serviceProvider )){
                                $org_ID =  explode('/',$element->serviceProvider->reference);
                            }
                            if(count($org_ID) > 0 && $ihs_ID != $org_ID[1]){
                                $element->ihs_encounter =  "Encounter/" . $element->id ;
                                $element->practice = '';
                                if (isset($element->participant)&& count($element->participant)
                                    && isset($element->participant[0]->individual)&& $element->participant[0]->individual->reference) {
                                    $element->practice = $element->participant[0]->individual->reference;
                                }
                                $tglpulang = '';
                                $tglregis = '';
                                if(isset($element->statusHistory) ){
                                for ($u = 0; $u <  count($element->statusHistory); $u++) {
                                    $element2 =  $element->statusHistory[$u];
                                        if($element2->status == 'finished'){
                                            $tglpulang = date('Y-m-d H:i:s',strtotime($element2->period->end));

                                        }
                                        if($element2->status == 'arrived'){
                                            $tglregis = date('Y-m-d H:i:s',strtotime($element2->period->start));
                                        }
                                    }
                                }
                                $objetoRequest = new \Illuminate\Http\Request();
                                $objetoRequest ['data']= null;
                                $objetoRequest ['method']= "GET";
                                $objetoRequest ['url']= "Organization/".$org_ID[1];
                                $ihs2 = app('App\Http\Controllers\Bridging\IHSController')->ihsTools($objetoRequest,true);

                                $diagnosis = '';
                                if(isset($element->diagnosis ) && count($element->diagnosis) > 0
                                && isset($element->diagnosis[0]->condition)
                                && isset($element->diagnosis[0]->condition->display) ){
                                    $diagnosis = $element->diagnosis[0]->condition->display;
                                }
                            $dokter = '';
                            if(isset($element->practice)){
                                $objetoRequest2 = new \Illuminate\Http\Request();
                                $objetoRequest2 ['data']= null;
                                $objetoRequest2 ['method']= "GET";
                                $objetoRequest2 ['url']= $element->practice;
                                $dok = app('App\Http\Controllers\Bridging\IHSController')->ihsTools($objetoRequest2,true);

                                if($dok->resourceType == 'Practitioner'){
                                    if(isset($dok->name) && count($dok->name) >0
                                    && isset($dok->name[0]->text)){
                                        $dokter = $dok->name[0]->text;
                                    }
                                }
                            }

                            if($tglregis == ''){
                                if(isset($element->period->start)){
                                    $tglregis = date('Y-m-d H:i:s',strtotime($element->period->start));
                                }
                            }
                            if($tglpulang == ''){
                                if(isset($element->period->end)){
                                    $tglpulang = date('Y-m-d H:i:s',strtotime($element->period->end));
                                }
                            }



                            $result [] = array(
                                'nik' => $data[0]->nik,
                                'tglregistrasi' => $tglregis,
                                'idpasien' => '',
                                'nocm' => $data[0]->ihs_number,
                                'noregistrasi' => $element->id,
                                'namapasien' => $element->subject->display,
                                'namaruangan' => $element->location[0]->location->display,
                                'tujuan' => $element->location[0]->location->display,
                                'kddiagnosa'=> $diagnosis,
                                'namadokter' => $dokter,
                                'tglpulang' => $tglpulang,
                                'statusinap' => $element->class->code == 'AMB'? 0:1,
                                'norec' =>'',
                                'nama_rs' => $ihs2->name,
                                'id_ihs_rs' => $ihs2->id,

                            );
                        }

                        }
                    }
                    // dd($result);
                    // // if($ihs->total){
                    // dd($ihs->total);
                }
            }

            foreach ($data as $hideung){
                if ($hideung->kddiagnosa != ""){
                    $data[$d]->kddiagnosa = substr($data[$d]->kddiagnosa,1);
                }
                $result [] = array(
                    'nik' =>  $data[$d]->nik,
                    'tglregistrasi' => $data[$d]->tglregistrasi,
                    'idpasien' => $data[$d]->idpasien,
                    'nocm' =>$data[$d]->nocm,
                    'noregistrasi' => $data[$d]->noregistrasi,
                    'namapasien' => $data[$d]->namapasien,
                    'namaruangan' => $data[$d]->namaruangan,
                    'kddiagnosa'=> $data[$d]->kddiagnosa,
                    'namadokter' => $data[$d]->namadokter,
                    'tglpulang' => $data[$d]->tglpulang,
                    'statusinap' => $data[$d]->statusinap,
                    'norec' =>$data[$d]->norec,
                    'nama_rs' => $data[$d]->nama_rs,
                    'id_ihs_rs' => $data[$d]->id_ihs_rs,
                    'tujuan' =>  $data[$d]->namaruangan,

                );
                $d = $d + 1;
            }
            if(count($result) > 0){
                foreach ($result as $key => $row) {
                    $count[$key] = $row['tglregistrasi'];
                }

                array_multisort($count, SORT_DESC, $result);
            }


            if(count($data) > 0){
                $result = array(
                    "response" =>  $result,
                    "metaData" => array(
                        "code" => "200",
                        "message" => "Ok"
                    )
                );
            }else{
                $result = array(
                    "response" =>  null,
                    "metaData" => array(
                        "code" => "400",
                        "message" => "Data tidak ditemukan"
                    )
                );
            }

        }catch(Exception $e){
            $result = array(
                    "response" =>  null,
                    "metaData" => array(
                        "code" => "400",
                        "message" => $e->getMessage()
                    )
                );
        }
       return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
        // return $this->respondV2($data);
    }
    public function getObservasiKesehatan(Request $request) {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join ('detaildiagnosapasien_t as ddp','ddp.noregistrasifk','=','apd.norec')
            ->leftjoin ('diagnosapasien_t as dp','dp.norec','=','ddp.objectdiagnosapasienfk')
            ->leftjoin ('diagnosa_m as dg','ddp.objectdiagnosafk','=','dg.id')
            ->select(DB::raw("ddp.tglinputdiagnosa as tanggal, dg.kddiagnosa || ': ' || dg.namadiagnosa AS observasi,
                        ddp.keterangan,'' as status,
                        ps.nocm,ps.namapasien,pd.tglregistrasi,
                        pd.noregistrasi,ps.noidentitas as nik
                     "))
            ->where('pd.statusenabled',true)
            ->where('ps.statusenabled', true)
            ->where('ddp.objectjenisdiagnosafk',1)
            ->where('ps.kdprofile',$kdProfile);
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','iilike', '%'.$request['namapasien'].'%');
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }

        $data = $data->orderBy('pd.tglregistrasi', 'desc');
        $data = $data->get();


        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
        // return $this->respondV2($data);
    }
    public function getProsedur(Request $request) {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join ('diagnosatindakanpasien_t as dp','dp.objectpasienfk','=','apd.norec')
            ->leftjoin ('detaildiagnosatindakanpasien_t as ddp','ddp.objectdiagnosatindakanpasienfk','=','dp.norec')
            ->leftjoin ('diagnosatindakan_m as dg','ddp.objectdiagnosatindakanfk','=','dg.id')
            ->leftjoin ('pegawai_m as pg','pg.id','=','ddp.objectpegawaifk')
            ->select(DB::raw("ddp.tglinputdiagnosa AS tanggal,
                        dg.kddiagnosatindakan  || ' : ' || dg.namadiagnosatindakan AS prosedur,	ddp.keterangantindakan as keterangan,
                    pg.namalengkap as penyedia,ps.nocm,	ps.namapasien,	pd.tglregistrasi,	pd.noregistrasi,
                        ps.noidentitas AS nik
                     "))
            ->where('pd.statusenabled',true)
            ->where('ps.statusenabled', true)
            ->where('ps.kdprofile',$kdProfile);
//            ->where('ddp.objectjenisdiagnosafk',1);
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
       if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','iilike', '%'.$request['namapasien'].'%');
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }

        $data = $data->orderBy('pd.tglregistrasi', 'desc');
        $data = $data->get();


        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
        // return $this->respondV2($data);
    }

    public function getKesehatanUmum(Request $request) {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('emrpasiend_t as emrdp')
            ->join('emrpasien_t as emrp', 'emrp.noemr', '=', 'emrdp.emrpasienfk')
            ->leftjoin('emrd_t as emrd', 'emrd.id', '=', 'emrdp.emrdfk')

            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'emrdp.pegawaifk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.noregistrasi', '=', 'emrp.noregistrasifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select(DB::raw("emrp.noregistrasifk as noregistrasi,emrp.tglregistrasi, emrp.noemr,
            emrp.tglemr as tglemr,emrd.caption as namaemr,emrdp.value as nilai,emrp.namaruangan,
                emrdp.emrdfk"))
            ->where('emrdp.statusenabled', true)
            ->whereIn('emrdp.emrdfk',[4241,4242,4243,4244,4245,4246])
            ->orderBy('emrp.tglemr')
            ->where('emrp.kdprofile',$kdProfile );

        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
       if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','ilike', '%'.$request['namapasien'].'%');
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }

        // $data = $data->orderBy('tr.tgltransaksi','desc');
        $data = $data->get();
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
        // return $this->respondV2($data);
    }
    public function getAlergi2(Request $request) {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('emrpasiend_t as emrdp')
            ->join('emrpasien_t as emrp', 'emrp.noemr', '=', 'emrdp.emrpasienfk')
            ->leftjoin('emrd_t as emrd', 'emrd.id', '=', 'emrdp.emrdfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'emrdp.pegawaifk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.noregistrasi', '=', 'emrp.noregistrasifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select(DB::raw("emrp.noregistrasifk as noregistrasi,emrp.tglregistrasi, emrp.noemr,
            emrp.tglemr as tglemr,emrd.caption as namaemr,case when emrd.type='checkbox' and emrdp.value='1' then 'Ya'
            when emrd.type='checkbox' and emrdp.value='0' then 'Tidak' else  emrdp.value end as nilai,emrp.namaruangan,
                emrdp.emrdfk"))
            ->where('emrdp.statusenabled', true)
            ->where('emrp.statusenabled', true)
            ->whereIn('emrdp.emrdfk',[3105794,3105795])
            ->orderBy('emrp.tglemr')
            ->where('emrp.kdprofile',$kdProfile );

        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','ilike', '%'.$request['namapasien'].'%');
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }

        // $data = $data->orderBy('tr.tgltransaksi','desc');
        $data = $data->get();
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
        // return $this->respondV2($data);
    }
    public function getAlergi(Request $request) {

        $data=[];
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);

    }
    public function getPengobatan(Request $request) {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd','apd.norec','=','pp.noregistrasifk')
            ->JOIN('pasiendaftar_t as pd','pd.norec','=','apd.noregistrasifk')
            ->JOIN('pasien_m as ps','ps.id','=','pd.nocmfk')
            ->JOIN('produk_m as pr','pr.id','=','pp.produkfk')
            ->JOIN('ruangan_m as ru','ru.id','=','apd.objectruanganfk')
            ->leftJoin('satuanstandar_m as ss','ss.id','=','pp.satuanviewfk')
            ->leftJOIN('strukresep_t as sr','sr.norec','=','pp.strukresepfk')
            ->leftJOIN('pegawai_m as dok','dok.id','=','sr.penulisresepfk')
            ->JOIN('jeniskemasan_m as jkm','jkm.id','=','pp.jeniskemasanfk')
            ->JOIN('detailjenisproduk_m as djp','djp.id','=','pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp','jp.id','=','djp.objectjenisprodukfk')
            ->leftJOIN('ruangan_m as ru2','ru2.id','=','sr.ruanganfk')
            ->select(DB::raw("pd.noregistrasi,pd.tglregistrasi,sr.tglresep,sr.noresep,
                pr.namaproduk,pp.jumlah,pp.dosis,  pp.aturanpakai,ru.namaruangan,ss.satuanstandar as satuan,jkm.jeniskemasan,
                dok.namalengkap as pemberiresep,jp.jenisproduk,djp.detailjenisproduk,ru2.namaruangan as ruangandepo

            "))
            ->where('pd.statusenabled',true)
            ->whereNotNull('pp.strukresepfk')
            ->where('pp.kdprofile',$kdProfile );
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','ilike', '%'.$request['namapasien'].'%');
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }

        // $data = $data->orderBy('tr.tgltransaksi','desc');
        $data = $data->orderBy('sr.tglresep','desc');
        $data = $data->get();
//        $data = collect($data);
        if(count($data) > 0){
            $result = array(
                "response" => $data,//  $data->groupBy('noresep'),
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);

    }
    public function getPertemuanMendatang(Request $request) {
         $kdProfile = $this->kdProfile;
        $data = \DB::table('antrianpasienregistrasi_t as apr')
            ->leftJoin('pasien_m as pm','pm.id','=','apr.nocmfk')
            ->leftJoin('ruangan_m as ru','ru.id','=','apr.objectruanganfk')
            ->leftJoin('pegawai_m as pg','pg.id','=','apr.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as kps','kps.id','=','apr.objectkelompokpasienfk')
            ->select(
                DB::raw("
                apr.noreservasi,apr.tanggalreservasi as tanggal,
                ru.namaruangan,pg.namalengkap as dokter,
                apr.notelepon,pm.nohp,
                (case when pm.namapasien is null then apr.namapasien else pm.namapasien end) as namapasien,pm.nocm,
                apr.keterangan as informasi,'Reservasi' as jenis")

            )
            ->where('apr.noreservasi','<>','-')
            ->where('apr.statusenabled',true)
            ->whereNotNull('apr.noreservasi')
            ->where('apr.tanggalreservasi','>=',date('Y-m-d H:i:s'))
            ->where('apr.kdprofile',$kdProfile);
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('pm.noidentitas','=', $request['nik']);
        }
       if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('pm.nocm','ilike', '%'.$request['nocm'].'%');
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $nama = $request['namapasien'];
            $data = $data->whereRaw("(pm.namapasien ilike'%$nama%' or apr.namapasien ilike'%$nama%') " );
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('pm.id','=', $request['id_pasien']);
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('pm.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('pm.namapasien','ilike', '%'.$request['namapasien'].'%');
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }

        $data = $data->orderBy('apr.tanggalreservasi', 'desc');
        $data = $data->get();


        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);

    }
    public function getRadiologi(Request $request) {
        $kdProfile = $this->kdProfile;
        $rad =  $this->settingDataFixed('idDepartemenRadiologi',$kdProfile);

        $cat = explode(',',$this->settingDataFixed('KdDepartemenCathlab',$kdProfile));
        $data = \DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->leftJOIN('hasilradiologi_t as hrx','hrx.pelayananpasienfk','=','pp.norec')
            ->leftJOIN('hasilradiologilistgambar_t as hr','hr.pelayananpasienfk','=','pp.norec')
            ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftJOIN('ris_order as ris', 'ris.order_no', '=',
                       DB::raw('so.noorder AND ris.order_code=cast(pp.produkfk as text)'))
            ->distinct()
            ->select(
                DB::raw("
               to_char(pp.tglpelayanan,'dd-MM-yyyy') as tgllayanan,
                to_char(pp.tglpelayanan,'HH:mm') as jamlayanan,pp.tglpelayanan as tanggal, pr.namaproduk,pp.jumlah,pp.hargasatuan,ps.nohp as notelpon,
                ru.namaruangan,ps.nocm,ps.namapasien,ps.noidentitas as nik,pd.noregistrasi, pd.tglregistrasi,pp.norec as norec_pp,
                hr.keterangan as ekspertise,hr.filename,ris.order_cnt as nourutrad,hrx.keterangan as expertise,ru.objectdepartemenfk")
            )
            ->where(function ($query) use ($cat,$rad) {
                $query->whereIn('ru.objectdepartemenfk',$cat)
                      ->orWhere('ru.objectdepartemenfk', '=', $rad);

            })

            ->whereNull('pp.strukresepfk')
            // ->whereNotNull('hr.filename')
            ->where('pp.kdprofile', $kdProfile )
            ->where('ris.order_complete','!=', 0)
            ->orderBy('pp.tglpelayanan','desc');

        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
       if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
        }


        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','ilike', '%'.$request['namapasien'].'%');
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }
        $data = $data->get();

        if(count($data) > 0){
                $pelayananpetugas = \DB::table('pasiendaftar_t as pd')
                    ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                    ->join('pelayananpasienpetugas_t as ptu', 'ptu.nomasukfk', '=', 'apd.norec')
                    ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
                    ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg.id')
//                    ->where('pd.kdprofile',$idProfile)
                    ->where('ptu.objectjenispetugaspefk', 4)
                    ->where('pd.kdprofile', $kdProfile )
                    ->where('pd.noregistrasi', $data[0]->noregistrasi)
                    ->get();

                foreach ($data as $item) {
                    $NamaDokter = '-';
                    $idPeg = 0;
                    foreach ($pelayananpetugas as $hahaha) {
                        if ($hahaha->pelayananpasien == $item->norec_pp) {
                            $NamaDokter = $hahaha->namalengkap;
                            $idPeg = $hahaha->id;
                        }
                    }
                    $item->id_hasil = ($item->nourutrad == null) ? '-' : $item->nocm. '-'.$item->nourutrad;
                    $item->penyedia = $NamaDokter;

                    // $curl = curl_init();
                    // curl_setopt_array($curl, array(
                    //     CURLOPT_URL => 'https://transmedic.co.id:2301/dcm4chee-arc/aets/TRANSMEDIC/rs/studies?limit=1&includefield=all&offset=0&PatientID='.$item->id_hasil,//$data->result_id,
                    //     CURLOPT_RETURNTRANSFER => true,
                    //     CURLOPT_ENCODING => "",
                    //     CURLOPT_MAXREDIRS => 10,
                    //     CURLOPT_TIMEOUT => 30,
                    //     CURLOPT_SSL_VERIFYHOST => 0,
                    //     CURLOPT_SSL_VERIFYPEER => 0,
                    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     CURLOPT_CUSTOMREQUEST => "GET",
                    //     CURLOPT_HTTPHEADER => array(
                    //         "Content-Type: application/json;",
                    //     ),
                    // ));

                    // $response = curl_exec($curl);
                    // $err = curl_error($curl);

                    // curl_close($curl);
                    // $hs = json_decode($response);
                    $baseUrl= '';
                    $item->ket_hasil = 'Belum Ada';
                    $urlPACS ='';
                    $item->url_hasil ='';
                    if( $item->filename !=null){
                        // if(isset($hs[0])){
                           $item->ket_hasil = 'Ada';
                        //    $urlPACS= $hs[0]->{'0020000D'}->Value[0];
                           $baseUrl = 'https://app.rsjpparamarta.com/service/medifirst2000/radiologi/images/pacs/'.$item->filename;//http://bdg2.jasamedika.com:2303/viewer/'.$idPeg.'/'.$item->norec_pp.'/'. $item->nocm.'/'. $urlPACS;
                           $item->url_hasil =$baseUrl;
                        // }
                    }
                    $item->lokasi = null;
                    if($item->id_hasil == '00000184-9'){
                        $item->lokasi= 'https://app.rsjpparamarta.com/service/v-echo-00000184-9.html';
                    }
                    if($item->id_hasil == '00000119-17'){
                        $item->lokasi= 'https://app.rsjpparamarta.com/service/v-echo-00000119-17.html';
                    }else{
                        // $var = 'echo';
                        // if (in_array($item->objectdepartemenfk,$cat) ) {
                        //     $var = 'cathlab';
                        // }
                        // $video ='https://10.20.30.40/service/v-'.$var.'-'.$item->id_hasil .'.html';
                        // $curl = curl_init();
                        // curl_setopt_array($curl, array(
                        //     CURLOPT_URL => $video,
                        //     CURLOPT_RETURNTRANSFER => true,
                        //     CURLOPT_ENCODING => "",
                        //     CURLOPT_MAXREDIRS => 10,
                        //     CURLOPT_TIMEOUT => 30,
                        //     CURLOPT_SSL_VERIFYHOST => 0,
                        //     CURLOPT_SSL_VERIFYPEER => 0,
                        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //     CURLOPT_CUSTOMREQUEST => "GET",
                        //     CURLOPT_HTTPHEADER => array(
                        //         "Content-Type: application/json;",
                        //     ),
                        // ));

                        // $response = curl_exec($curl);
                        // $err = curl_error($curl);

                        // curl_close($curl);

                        // $hs = json_decode($response);
                        // if(isset( $hs->status)){
                            $item->lokasi  = null;
                        // }else{
                        //     $item->lokasi= 'https://app.rsjpparamarta.com/service/v-'.$var.'-'.$item->id_hasil .'.html';
                        // }

                    }
                    // if($item->lokasi =='https://app.rsjpparamarta.com/service/v-echo-00000775-2.html'){
                    //     $item->lokasi  = null;
                    // }


                }
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }
        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);

    }
    public function getHasilLab(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $p_NIK ='';
        $p_nocm ='';
        $p_id ='';
        $p_nobpjs ='';
        $p_namaPasien ='';
        $page = '';
        $limit = '';
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $p_NIK =" and ps.noidentitas='".$request['nik']."'";
        }
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $p_nocm =  " and ps.nocm ilike '%".$request['nocm']."%'";
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $p_id =' and ps.id='. $request['id_pasien'];
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $p_nobpjs = " and ps.nobpjs='".$request['nobpjs']."'";
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $p_namaPasien = " and ps.namapasien ilike '%".$request['namapasien']."%'";
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $limit = (int)$request['limit'];
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $page = (int)$request['page'];
        }
        $pasien = collect(DB::select( "select ps.* from pasien_m as ps where ps.statusenabled=true
        and ps.kdprofile=$kdProfile  $p_NIK
                 $p_nocm
                 $p_id "))->first();

        $jk = 0;
        if(!empty($pasien)){
            $jk = $pasien->objectjeniskelaminfk;
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
           return $this->setStatusCode($result['metaData']['code'])->respondV2($result);

        }
        // $data = [];
        $noc = $pasien->nocm;
        // $data= collect( DB::connection('sqlsrv')
        //        ->select("select NOLAB_RS as no_lab,REG_DATE as tgl_lab,KEL_PEMERIKSAAN as kelompokpemeriksaan,TARIF_NAME as pemeriksaan,
        //         PARAMETER_NAME as detailpemeriksaan,HASIL as hasil,NILAI_RUJUKAN as nilainormal,SATUAN as satuan,FLAG_HL as flag,
        //         NORM as nocm,URUT_BOUND as nourut,MODIFIED_DATE as tgl_update ,METODE_PERIKSA as metodeperiksa,Catatan as catatan,
        //         Rekomendasi as rekomendasi

        //          from HasilLIS
        //    where
        //    NORM= '$noc'
        //    "));

        $data = collect(DB::select("
        select
        res.no_order AS visit_trans_id, res.kode_pemeriksaan AS tarif_id,
        res.nama_pemeriksaan AS examination_name, res.hasil AS result_value, res.unit,
        res.normal AS normal_value, res.metode, res.nama_alat AS treatment_name,
        res.no_urut AS urut, res.no_rm AS rm_number,  res.tgl_hasil AS visit_date, res.kode_sir as his_test_id,
        res.nama_pemeriksaan AS tarif_name, res.flag, res.user_validasi as analis
        from lab_hasil as res
        join pasiendaftar_t as pd on pd.noregistrasi=res.no_registrasi
        join pasien_m as ps on ps.id = pd.nocmfk
        where  ps.nocm= '$noc'"));
        if(count($data)> 0){

            foreach($data as $dd){
                if($dd->flag == ''){
                    $dd->flag = 'N';
                }
            }
        }
        // $data = DB::select(DB::raw("SELECT pp.tglpelayanan as tanggal ,djp.detailjenisproduk as jenispemeriksaan,prd.namaproduk as namapemeriksaan ,
        //         maps.detailpemeriksaan,maps.memohasil as memohasilperiksa,
        //         maps.nourutdetail,hh.hasil as hasilpemeriksaan,ss.satuanstandar as satuan,nn.nilaitext as batasnormal,nn.tipedata,
        //         nn.nilaimin,nn.nilaimax,
        //         maps.nourutjenispemeriksaan,
        //         hh.flag,pd.noregistrasi,pd.tglregistrasi,pd.tglpulang
        //         FROM pelayananpasien_t  as pp
        //         inner join produk_m as prd on prd.id = pp.produkfk
        //         inner join detailjenisproduk_m as djp on djp.id = prd.objectdetailjenisprodukfk
        //         inner join maphasillab_m  as maps on maps.produkfk = prd.id
        //         inner join maphasillabdetail_m  as maps2 on maps2.maphasilfk = maps.id
        //         and maps2.jeniskelaminfk ='$jk'
        //         inner join nilainormal_m  as nn on nn.id = maps2.nilainormalfk
        //         inner join antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
        //         inner join pasiendaftar_t as pd on pd.norec = apd.noregistrasifk
        //         inner join pasien_m as ps on ps.id = pd.nocmfk
        //         left join satuanstandar_m  as ss on ss.id = maps.satuanstandarfk
        //          join hasillaboratorium_t  as hh on hh.norecpelayanan  = pp.norec
        //         and pp.noregistrasifk=hh.noregistrasifk
        //         and maps.detailpemeriksaan =hh.detailpemeriksaan
        //         where pd.statusenabled=1
        //          $p_NIK
        //          $p_nocm
        //          $p_id
        //          $p_nobpjs
        //          $p_namaPasien
        //         group by pp.tglpelayanan ,pp.noregistrasifk ,djp.detailjenisproduk,pp.produkfk,prd.namaproduk ,maps.detailpemeriksaan,maps.memohasil,
        //         maps.nourutdetail,maps.satuanstandarfk,ss.satuanstandar,nn.nilaitext,nn.tipedata,nn.nilaimin,nn.nilaimax,hh.hasil,
        //         maps.id ,hh.norec ,maps.nourutjenispemeriksaan,maps.nourutdetail,pp.norec ,
        //         hh.flag,nn.id ,maps2.jeniskelaminfk,pd.noregistrasi,pd.tglregistrasi,pd.tglpulang
        //         order by  maps.nourutjenispemeriksaan,maps.nourutdetail asc"));
        if( $page != '' && $limit != '' ){
            $data  = collect($data)->forPage($page, $limit);
        }

         $data2 = collect(DB::select("select case when so.noorder is null then  pd.noregistrasi  else so.noorder end as visit_trans_id,
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
        where  ps.nocm= '$noc'

        "));

        if( $page != '' && $limit != '' ){
            $data2  = collect($data2)->forPage($page, $limit);
        }
        $data =  array_merge($data2->toArray(),$data->toArray());
        if(count($data) > 0){
            foreach ($data as $key => $value) {
               $value->nik= $pasien->noidentitas ;
               $value->namapasien= $pasien->namapasien ;
            }
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }
        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);

    }
    public function getECG(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('eecg_t as emr')
            ->leftJoin('pasien_m as ps','ps.nocm','=','emr.customerid')
            ->select('emr.norec','emr.kunci','emr.nilai','emr.urut','emr.customerid','emr.datesend','ps.nocm','ps.namapasien','ps.noidentitas as nik',
                DB::raw("null as url_hasil"))
            ->orderBy('emr.urut');
       if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('emr.customerid','like', '%'.$request['nocm'].'%');

        }
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
//        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
//            $data = $data->where('ps.nocm','=', $request['nocm']);
//        }

        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','like', '%'.$request['namapasien'].'%');
        }
        if (isset($request['date_send']) && $request['date_send'] != '') {
            $data = $data->where('emr.norec', 'like', $request['date_send'] . '%');
        }
        if (isset($request['vis']) && $request['vis'] != '') {
            if ($request['vis'] == 'true') {
                $data = $data->where('emr.statusenabled', $request['vis']);
            } else {
                $data = $data->whereNull('emr.statusenabled');
            }
        }
        $data =$data->get();

    //    $soap = \DB::table('emrfoto_t as emrdp')
    //         ->join('emrpasien_t as emrp', 'emrp.noemr', '=', 'emrdp.noemrpasienfk')
    //         ->leftjoin('pasien_m as ps', 'ps.nocm', '=', 'emrp.nocm')
    //         ->select(DB::raw("emrdp.*,ps.nocm,ps.namapasien,emrp.tglemr,ps.noidentitas,ps.nohp"))
    //         ->where('emrp.statusenabled', true)
    //         ->where('emrdp.emrfk', 154)
    //         ->where('emrdp.emrdfk',10)
    //         ->whereNotNull('emrdp.image')
    //         ->orderBy('emrp.tglemr')
    //         ->where('emrp.kdprofile',$kdProfile );
    //     if(isset($request['noregistrasi']) && $request['noregistrasi']!="" && $request['noregistrasi']!="undefined"){
    //         $soap = $soap->where('emrp.noregistrasifk','=',$request['noregistrasi']);
    //     }
    //     if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
    //         $soap = $soap->where('ps.noidentitas','=', $request['nik']);
    //     }
    //     if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
    //         $soap = $soap->where('ps.id','=', $request['id_pasien']);
    //     }
    //    if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
    //         $soap = $soap->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
    //     }
    //     if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
    //         $soap = $soap->where('ps.nobpjs','=', $request['nobpjs']);
    //     }
    //     if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
    //         $soap = $soap->where('ps.namapasien','ilike', '%'.$request['namapasien'].'%');
    //     }

    //     if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
    //         $soap = $soap->limit( $request['limit']);
    //     }
    //     if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
    //         $soap = $soap->offset($request['page']);
    //     }

    //     $soap = $soap->get();
        $data2= [];
        // if(count($soap)  !=0){
        //   foreach($soap as $dd){
        //         $image = str_replace('data:image/jpeg;base64,', '', $dd->image);
        //         $image = str_replace(' ', '+', $image);
        //         $filename = $dd->norec . '.jpg';
        //         \Storage::disk('ecg')->put($filename,base64_decode($image));
        //         $data2[]= array(
        //             'norec' =>  $dd->norec,
        //             'kunci' => 'image' ,
        //             'nilai' => 'image' ,
        //             'urut' => null,
        //             'customerid' => $dd->nohp,
        //             'datesend' => $dd->tglemr,
        //             'nocm' => $dd->nocm,
        //             'namapasien' =>  $dd->namapasien,
        //             'nik' =>  $dd->noidentitas,
        //             'url_hasil' => 'http://103.166.210.122/service/medifirst2000/radiologi/images/ecg/'.$filename
        //         );
        //     }
        // }

        // $data = array_merge($data,$data2);
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }
        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
    }
    public function getRencanaRekomen(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('rencana_t as rm')
            ->select('rm.rencana', 'rm.tanggalinput as tglinput',
                'pg.namalengkap as petugas',  'ru.namaruangan', 'pd.noregistrasi', 'pd.tglregistrasi', 'ps.nocm',
                'ps.namapasien','ps.noidentitas as nik')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'rm.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rm.objectpetugas')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'rm.objectruanganfk')
            ->where('rm.statusenabled', true)
            ->where('rm.kdprofile', $kdProfile);

        if (isset($request['noregistrasifk']) && $request['noregistrasifk'] != '') {
            $data = $data->where('rm.noregistrasifk', $request['noregistrasifk']);
        }
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','like', '%'.$request['nocm'].'%');

        }

        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','like', '%'.$request['namapasien'].'%');
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }
        $data =$data->get();
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }
        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
    }
    public function getDaftarPasienDK(Request $request) {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('pasien_m as ps')
            ->leftjoin('jeniskelamin_m as jk','jk.id','=','ps.objectjeniskelaminfk')
            ->leftjoin('agama_m as ag','ag.id','=','ps.objectagamafk')
            ->leftjoin('golongandarah_m as gd','gd.id','=','ps.objectgolongandarahfk')
            ->leftjoin('pekerjaan_m as pk','pk.id','=','ps.objectpekerjaanfk')
            ->leftjoin('pendidikan_m as pdd','pdd.id','=','ps.objectpendidikanfk')
            ->leftjoin('kebangsaan_m as kbg','kbg.id','=','ps.objectkebangsaanfk')
            ->leftjoin('negara_m as ngr','ngr.id','=','ps.objectnegarafk')
            ->leftjoin('statusperkawinan_m as spkw','spkw.id','=','ps.objectstatusperkawinanfk')
            ->join('alamat_m as al','al.nocmfk','=','ps.id')
            ->leftjoin('suku_m as sk','sk.id','=','ps.objectsukufk')
            ->select(DB::raw("ps.id,ps.nocm,
    ps.noidentitas as nik,	ps.namapasien,	ps.tgllahir,	ps.nohp AS telepon,	sk.suku as etnis,
	ps.bahasa,	jk.jeniskelamin,	ag.agama,	gd.golongandarah,	al.alamatlengkap,	al.alamatemail as email,
	ps.namaayah as wali,	'Orang Tua' as hubunganwali,	null as alamatwali, ngr.namanegara as negara,
	null as teleponwali,	null as faksesbpjs,	null as alamatfaskes,	null as teleponfaskes,	pk.pekerjaan, kbg.name as kebangsaan,
	pdd.pendidikan,	spkw.statusperkawinan,ps.nobpjs"))
            ->where('ps.statusenabled',true)
            ->where('ps.namapasien','!=','-')
            ->where('ps.kdprofile',$kdProfile);
        $stt = false;
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
            $stt = true;
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
            $stt = true;
        }
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','like', '%'.$request['nocm'].'%');
            $stt = true;
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','like', '%'.$request['namapasien'].'%');
            $stt = true;
        }
        if(isset($request['id_kebangsaan']) && $request['id_kebangsaan']!="" && $request['id_kebangsaan']!="undefined"){
            $data = $data->where('kbg.id','=', $request['id_kebangsaan']);
            $stt = true;
        }
        if(isset($request['id_negara']) && $request['id_negara']!="" && $request['id_negara']!="undefined"){
            $data = $data->where('ngr.id','=', $request['id_negara']);
            $stt = true;
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
         if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }
        $data = $data->orderBy('ps.namapasien');
        $data = $data->get();
        if(count($data)> 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok",
                    "count" => count($data)
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
    }

    public function getListNegara(Request $request) {
        $kdProfile = (int) $this->kdProfile;
        $data = \DB::table('negara_m as ngr')
            ->where('ngr.statusenabled',true )
            ->where('ngr.kdprofile',$kdProfile );

        if(isset($request['id_negara']) && $request['id_negara']!="" && $request['id_negara']!="undefined"){
            $data = $data->where('ngr.id','=', $request['id_negara']);
        }
        if(isset($request['nama_negara']) && $request['nama_negara']!="" && $request['nama_negara']!="undefined"){
            $data = $data->where('ngr.namanegara','ilike', '%'.$request['nama_negara'].'%');
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }
        
        $data = $data->get();
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metadata" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metadata" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metadata']['code'])->respond($result);
    }

    public function getListKebangsaan(Request $request) {
        $kdProfile = (int) $this->kdProfile;
        $data = \DB::table('kebangsaan_m as kbg')
            ->where('kbg.statusenabled',true )
            ->where('kbg.kdprofile',$kdProfile );

        if(isset($request['id_kebangsaan']) && $request['id_kebangsaan']!="" && $request['id_kebangsaan']!="undefined"){
            $data = $data->where('kbg.id','=', $request['id_kebangsaan']);
        }
        if(isset($request['kebangsaan']) && $request['kebangsaan']!="" && $request['kebangsaan']!="undefined"){
            $data = $data->where('kbg.name','ilike', '%'.$request['kebangsaan'].'%');
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }
        
        $data = $data->get();
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metadata" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metadata" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metadata']['code'])->respond($result);
    }

    public function getAntrianPoli(Request $request) {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::select(DB::raw("select ru.namaruangan, coalesce(x.noantrian, 0) noantrian
        from ruangan_m as ru
        left join(
            select ru.id, ru.namaruangan, apd.status, max(apd.noantrian) noantrian from
            pasiendaftar_t pd 
            inner join antrianpasiendiperiksa_t apd on apd.noregistrasifk = pd.norec
            inner join ruangan_m ru on ru.id = apd.objectruanganfk
            where pd.statusenabled = true
            and ru.statusenabled = true
            and apd.statusantrian = '1'
            and ru.objectdepartemenfk = 18
            and pd.tglregistrasi::date = to_char(now(), 'YYYY-MM-DD')::date
            group by ru.namaruangan, apd.status, ru.id
        ) as x on ru.id = x.id
        where ru.statusenabled = true
        and ru.objectdepartemenfk = 18
        order by ru.namaruangan
        "));
        
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metadata" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metadata" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metadata']['code'])->respond($result);
    }

    public function getReffKontrol(Request $request){
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        $deptJalan = explode(',', $this->settingDataFixed('kdDepartemenRawatJalanFix',$idProfile));
        $deptKonsul = explode(',', $this->settingDataFixed('KdDeptKonsul',$idProfile));
        $kdDepartemenRawatJalan = [];
        foreach ($deptJalan as $item) {
            $kdDepartemenRawatJalan [] = (int)$item;
        }
        $kdDepartemenKonsul = [];
        foreach ($deptKonsul as $items) {
            $kdDepartemenKonsul [] = (int)$items;
        }

        $dokter = \DB::table('pegawai_m as rm')
            ->select('rm.id', 'rm.namalengkap', 'rm.noidentitas')
            ->where('rm.kdprofile', $idProfile)
            ->where('rm.statusenabled', true)
            ->where('rm.objectjenispegawaifk', 1)
            ->orderBy('rm.namalengkap');

        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $dokter = $dokter->where('rm.noidentitas','ilike', '%'.$request['nik'].'%');
        }

        $dokter = $dokter ->get();


        $dataRuanganJalan = \DB::table('ruangan_m as ru')
            ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk', 'ru.isreservasi')
            ->where('ru.kdprofile', $idProfile)
            ->where('ru.statusenabled', true)
            ->where('ru.isreservasi', true)
            ->wherein('ru.objectdepartemenfk', $kdDepartemenRawatJalan)
            ->orderBy('ru.namaruangan')
            ->get();


        $result = array(

            'dokter' => $dokter,
            'ruangan' => $dataRuanganJalan,

            'message' => 'inhuman',
        );

        return $this->respondV2($result);
    }
     public function getSOAP(Request $request) {

        $kdProfile = $this->kdProfile;

        $data = \DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->select(DB::raw("ps.noidentitas as nik,pd.tglregistrasi,ps.id as idpasien,ps.nocm,pd.noregistrasi,
                            ps.namapasien,ru.namaruangan,
                            pd.tglpulang,
                           CASE when ru.objectdepartemenfk in (16,25,26) then 1 else 0 end as statusinap,pd.norec"))
             ->where('pd.statusenabled',true)
             ->where('pd.kdprofile',$kdProfile);
        if(isset($request['noregistrasi']) && $request['noregistrasi']!="" && $request['noregistrasi']!="undefined"){
            $data = $data->where('pd.noregistrasi','=', $request['noregistrasi']);
        }
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $data = $data->where('ps.noidentitas','=', $request['nik']);
        }
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $data = $data->where('ps.id','=', $request['id_pasien']);
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $data = $data->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $data = $data->where('ps.namapasien','iilike', '%'.$request['namapasien'].'%');
        }
        if(isset($request['nokk']) && $request['nokk']!="" && $request['nokk']!="undefined"){
//            $data = $data->where('ps.nokk','=', $request['nokk']);
        }
        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $data = $data->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $data = $data->offset($request['page']);
        }
        $data = $data->where('ps.statusenabled', true);
        $data = $data->orderBy('pd.tglregistrasi', 'desc');
        $data = $data->get();

        $soap = \DB::table('emrpasiend_t as emrdp')
            ->join('emrpasien_t as emrp', 'emrp.noemr', '=', 'emrdp.emrpasienfk')
            ->leftjoin('pasien_m as ps', 'ps.nocm', '=', 'emrp.nocm')
            ->select(DB::raw("emrp.noregistrasifk as noregistrasi,emrp.tglregistrasi, emrp.noemr,
            emrp.tglemr as tglemr,emrdp.value as nilai,emrp.namaruangan,
                emrdp.emrdfk"))

            ->where('emrdp.statusenabled', true)
            ->whereIn('emrdp.emrdfk',[
                22034961,22034963,22034964])
            ->orderBy('emrp.tglemr')
            ->where('emrp.kdprofile',$kdProfile );
        if(isset($request['noregistrasi']) && $request['noregistrasi']!="" && $request['noregistrasi']!="undefined"){
            $soap = $soap->where('emrp.noregistrasifk','=',$request['noregistrasi']);
        }
        if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
            $soap = $soap->where('ps.noidentitas','=', $request['nik']);
        }
        if(isset($request['id_pasien']) && $request['id_pasien']!="" && $request['id_pasien']!="undefined"){
            $soap = $soap->where('ps.id','=', $request['id_pasien']);
        }
       if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $soap = $soap->where('ps.nocm','ilike', '%'.$request['nocm'].'%');
        }
        if(isset($request['nobpjs']) && $request['nobpjs']!="" && $request['nobpjs']!="undefined"){
            $soap = $soap->where('ps.nobpjs','=', $request['nobpjs']);
        }
        if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
            $soap = $soap->where('ps.namapasien','ilike', '%'.$request['namapasien'].'%');
        }

        if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
            $soap = $soap->limit( $request['limit']);
        }
        if(isset($request['page']) && $request['page']!="" && $request['page']!="undefined"){
            $soap = $soap->offset($request['page']);
        }
        $soap =$soap->groupBy("emrp.noregistrasifk","emrp.tglregistrasi", "emrp.noemr",
            "emrp.tglemr","emrdp.value","emrp.namaruangan",
                "emrdp.emrdfk");
        $soap = $soap->get();
            $i=0;
         foreach ($data as $d) {
           $data[$i]->detail_soap=[];
            foreach ($soap as $itemTgl) {
                if ($data[$i]->noregistrasi == trim($itemTgl->noregistrasi)) {
                    $namaemr ='';
                    if (in_array($itemTgl->emrdfk, [22034961]) ) {
                        $namaemr = "Tgl Input";
                    }
                    if (in_array($itemTgl->emrdfk, [22034963]) ) {
                        $namaemr = "SOAP";
                    }
                    if (in_array($itemTgl->emrdfk, [22034964]) ) {
                        $explode = explode('~',$itemTgl->nilai);
                        if(count($explode)>1){
                            $itemTgl->nilai = $explode[1];
                        }else{
                             $itemTgl->nilai = $explode[0];
                        }
                        $namaemr = "Dokter";
                    }
                    $data[$i]->detail_soap[] = array(
                        'noemr' => $itemTgl->noemr,
                        'nilai' => $itemTgl->nilai,
                        'namaemr' => $namaemr,
                    ) ;
                }
            }

           $i ++;
        }
        for ($k = count($data) - 1; $k >= 0; $k--) {
           if(count($data[$k]->detail_soap)==0){
                array_splice($data,$k,1);
           }
        }
        if(count($data) > 0){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);
        // return $this->respondV2($data);
    }

    function transposeData($data)
    {
        foreach ($data as $row => $columns) {
          foreach ($columns as $row2 => $column2) {
              $retData[$row2][$row]=$column2;
          }
        }
      return $retData;
    }

  function transpose($array_one) {
    $array_two = [];
    foreach ($array_one as $key => $item) {
        foreach ($item as $subkey => $subitem) {
            $array_two[$subkey][$key] = $subitem;
        }
    }
    return $array_two;
}
function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
public function sendPHR(Request $request)
{
    try {

            $pasien  =  DB::table('pasien_m')->where('nocm',$request['nocm'])->first();
            $dataJsonSend = null;
            $methods = 'GET';
         
            $url =  $this->settingFix('urlTelemedicineEMR') .'notify-update/'.$pasien->noidentitas.'/'.$request['nocm'];
          
            $headers = ['token'=>'HNoURUUdjnnzkiWTSR5b9XxungeLC0Dsv600X3qWcug='];

            $response = Http::withHeaders($headers)
            ->withoutVerifying()
            ->withOptions(["verify" => false])
            ->get($url);
         
            $response2 = array(
                "metaData" => array(
                    "code" =>$response->status() == 200? $response->status():201,
                    "message" => 'Ok'
                ),
                "response" => 
                array(
                    "error" =>  $response->status() == 200  ? false:true,
                    "res" => $response->status() == 200 ? $response->json() == 200:$response->body()
                )
               
            );
            return $this->respondV2($response2);

        } catch (\Exception $e) {
            $response = array(
                "metaData" => array(
                    "code" => "404",
                    "message" => "Transaksi tidak dapat di proses, Gagal dicoba kembali!"
                ),
                "response" => null,
                "e"=> $e->getMessage(). ' '.$e->getLine()
            );

            return $this->respondV2($response);
        }
    }
    protected function curlAPI2($headers, $dataJsonSend = null, $url, $method, $tipe = null)
    {
        $curl = curl_init();
        if ($dataJsonSend == null) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
            ));
        } else {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $dataJsonSend,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
            ));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $result = array(
                'metaData' => array(
                    'code' => 400,
                    'message' => 'Gagal menghubungkan ke  (' . $url . ')'
                ),
            );
        } else {

            if ($this->isJson($response)) {
                $result = json_decode($response);
            } else {
                $result = $response;
            }
        }

        return $result;
    }
    public function getFukuda(Request $request)
    {
        try {
                $pasien  =  DB::table('pasien_m')->where('nocm',$request['nocm'])->first();
                $dataJsonSend = null;
                $methods = 'GET';

                $url =  str_replace(' ','%20','http://10.20.30.40:7000/api/data/patient/data?nomr='.$request['nocm'].'&timeAwal='.$request['dari'].'&timeAkhir='.$request['sampai']);
                // return $url;
                $header= ['Content-Type:application/json'];
                $response = $this->curlAPI3($header, $dataJsonSend, $url, $methods, null);

                $response2 = array(
                    "metaData" => array(
                        "code" => "200",
                        "message" => 'Ok'
                    ),
                    "response" => str_replace('},]','}]',$response)// $this->isJson($response) ? $response : json_decode(json_encode($response))
                );
                return  str_replace('},]','}]',$response) ;//$this->respondV2($response2);

            } catch (\Exception $e) {
                $response = array(
                    "metaData" => array(
                        "code" => "404",
                        "message" => "Transaksi tidak dapat di proses, Gagal dicoba kembali!"
                    ),
                    "response" => null,
                    "e"=> $e->getMessage(). ' '.$e->getLine()
                );

                return $this->respondV2($response);
            }
        }

        public function listRegistrasi(Request $request) {
            $kdProfile = $this->kdProfile;

            $stt = false;
            $dari = '';
            $sampai = '';
            $nik = '';
            $nocm = '';
            $nama = '';
            $nores = '';
            $limit = '';
            $isreservasi = "" ;
            if(isset($request['dari']) && $request['dari']!="" && $request['dari']!="undefined"){
                $dari = " and pd.tglregistrasi >= '". $request['dari']." 00:00'";
            }
            if(isset($request['sampai']) && $request['sampai']!="" && $request['sampai']!="undefined"){
                $sampai = " and pd.tglregistrasi <= '". $request['sampai']." 23:59'";
            }
            if(isset($request['nik']) && $request['nik']!="" && $request['nik']!="undefined"){
                $nik = " and ps.noidentitas='". $request['nik']."'";
            }

            if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
                $nocm =  " and ps.nocm='". $request['nocm']."'";
            }
            if(isset($request['namapasien']) && $request['namapasien']!="" && $request['namapasien']!="undefined"){
                $nama =" and ps.namapasien ilike '%".$request['namapasien']."%'";
            }
            if(isset($request['noreservasi']) && $request['noreservasi']!="" && $request['noreservasi']!="undefined"){
                $nores =  " and apr.noreservasi='". $request['noreservasi']."'";
            }
            if(isset($request['limit']) && $request['limit']!="" && $request['limit']!="undefined"){
                $limit =  " limit ". $request['limit'];
            }
            if(isset($request['isreservasi']) && $request['isreservasi']!="" && $request['isreservasi']!="undefined"
           ){
                if($request['isreservasi'] == 'true'){
                     $isreservasi = "  and apr.noreservasi is not null" ;
                }
                 if($request['isreservasi'] == 'false'){
                     $isreservasi = "  and apr.noreservasi is  null" ;
                }
            }


            $data =DB::select(DB::raw("select * from
                (select  row_number() over (partition by pd.noregistrasi order by apd.tglmasuk desc) as rownum,
                apr.noreservasi,
                apd.norec,pd.norec as norec_pd,ps.nocm,ps.namapasien,jk.jeniskelamin,pd.noregistrasi,pd.tglregistrasi,
                kp.kelompokpasien,pd.objectkelasfk,kls.namakelas,pd.objectruanganlastfk as objectruanganfk,
                ru.namaruangan,pg.namalengkap as dokterdpjp,pg.id as iddpjp,ps.tgllahir,alm.alamatlengkap
                from antrianpasiendiperiksa_t as apd
                inner join pasiendaftar_t as pd on pd.norec = apd.noregistrasifk and pd.objectruanganlastfk = apd.objectruanganfk
                left join antrianpasienregistrasi_t as apr on apr.noreservasi=pd.statusschedule and apr.nocmfk=pd.nocmfk
                inner join pasien_m as ps on ps.id = pd.nocmfk
                left join jeniskelamin_m as jk on jk.id = ps.objectjeniskelaminfk
                left join alamat_m as alm on alm.nocmfk = ps.id
                inner join kelas_m as kls on kls.id = pd.objectkelasfk
                inner join ruangan_m as ru on ru.id = apd.objectruanganfk
                inner join departemen_m as dept on dept.id = ru.objectdepartemenfk
                left join pegawai_m as pg on pg.id = pd.objectpegawaifk
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                where pd.statusenabled = true and pd.kdprofile = $kdProfile
                $dari
                $sampai

                $nik
                $nocm
                $nama
                 $nores
                 $isreservasi
                 $limit
                ) as x where x.rownum=1") );
        if(count($data)){
            $result = array(
                "response" =>  $data,
                "metaData" => array(
                    "code" => "200",
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metaData" => array(
                    "code" => "400",
                    "message" => "Data tidak ditemukan"
                )
            );
        }


        return $this->setStatusCode($result['metaData']['code'])->respondV2($result);

    }
    protected function curlAPI3($headers, $dataJsonSend = null, $url, $method, $tipe = null)
    {
        $curl = curl_init();
        if ($dataJsonSend == null) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
            ));
        } else {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $dataJsonSend,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'
            ));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $result = array(
                'metaData' => array(
                    'code' => 400,
                    'message' => 'Gagal menghubungkan ke  (' . $url . ')'
                ),
            );
        } else {

            // if ($this->isJson($response)) {
            //     $result = json_decode($response);
            // } else {
                $result = $response;
            // }
        }

        return $result;
    }
    public function getDiagnosaIcd10Part(Request $request)
    {
        $req = $request->all();
        $dataProduk = [];
        $dataProduk = \DB::table('diagnosa_m as st')
            ->select('st.id', 'st.kddiagnosa as kdDiagnosa', 'st.namadiagnosa as namaDiagnosa')
            ->where('st.statusenabled', true)
            ->orderBy('st.kddiagnosa');
        if (
            isset($req['filter']['filters'][0]['value']) &&
            $req['filter']['filters'][0]['value'] != "" &&
            $req['filter']['filters'][0]['value'] != "undefined"
        ) {
            $dataProduk = $dataProduk->where('st.kddiagnosa', 'ilike', '%' . $req['filter']['filters'][0]['value'] . '%')
                ->orWhere('st.namadiagnosa', 'ilike', '%' . $req['filter']['filters'][0]['value'] . '%');
        };
        if (
            isset($req['kodenamaicd']) &&
            $req['kodenamaicd'] != "" &&
            $req['kodenamaicd'] != "undefined"
        ) {
            $dataProduk = $dataProduk
                ->where('st.kddiagnosa', 'ilike', '%' .  $req['kodenamaicd']  . '%')
                ->orWhere('st.namadiagnosa', 'ilike', $req['kodenamaicd']  . '%');
        }
        $dataProduk = $dataProduk->take(10);
        $dataProduk = $dataProduk->get();


        $data = [];
        if (count($dataProduk) > 0) {
            foreach ($dataProduk as $item) {
                $data[] = array(
                    'kodeNama' => $item->kdDiagnosa . ' - ' . $item->namaDiagnosa,
                    'id' => $item->id,
                    'kdDiagnosa' => $item->kdDiagnosa,
                    'namaDiagnosa' => $item->namaDiagnosa,

                );
            }
        }

        return $this->respondV2($data);
    }
    public function getIcd9(Request $request)
    {
        $req = $request->all();
        $icdIX = \DB::table('diagnosatindakan_m as dg')
            ->select('dg.id', 'dg.kddiagnosatindakan as kdDiagnosaTindakan', 'dg.namadiagnosatindakan as namaDiagnosaTindakan')
            ->where('dg.statusenabled', true)
            ->orderBy('dg.kddiagnosatindakan');

        if (
            isset($req['filter']['filters'][0]['value']) &&
            $req['filter']['filters'][0]['value'] != "" &&
            $req['filter']['filters'][0]['value'] != "undefined"
        ) {
            $icdIX = $icdIX
                ->whereRaw("( dg.namadiagnosatindakan  ilike '%" . $req['filter']['filters'][0]['value'] . "%'
                or dg.kddiagnosatindakan ilike'%". $req['filter']['filters'][0]['value'] . "%' )");
        }
        if (
            isset($req['kodenamaicd']) &&
            $req['kodenamaicd'] != "" &&
            $req['kodenamaicd'] != "undefined"
        ) {
            $icdIX = $icdIX
            ->whereRaw("( dg.namadiagnosatindakan ilike '%" .  $req['kodenamaicd']  . "%'
                 or dg.kddiagnosatindakan', 'ilike '%". $req['kodenamaicd']  . "%' )");
        }


        $icdIX = $icdIX->take(10);
        $icdIX = $icdIX->get();
        $data = [];
        if (count($icdIX) > 0) {
            foreach ($icdIX as $item) {
                $data[] = array(
                    'kodeNama' => $item->kdDiagnosaTindakan . ' - ' . $item->namaDiagnosaTindakan,
                    'id' => $item->id,
                    'kdDiagnosaTindakan' => $item->kdDiagnosaTindakan,
                    'namaDiagnosaTindakan' => $item->namaDiagnosaTindakan,

                );
            }
        }

        return $this->respond($data);
    }
    public function saveDiagnosaPasien(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        DB::beginTransaction();
        //        try{
        $dataPegawaiUser = DB::select(
            DB::raw("select pg.id,pg.namalengkap from loginuser_s as lu
                INNER JOIN pegawai_m as pg on lu.objectpegawaifk=pg.id
                where pg.kdprofile = $idProfile and lu.id=:idLoginUser"),
            array(
                'idLoginUser' => $dataLogin['userData']['id'],
            )
        );

        if ($request['detaildiagnosapasien']['norec_dp'] == '') {
            $dataDiagnosa = new DiagnosaPasien();
            $dataDiagnosa->norec = $dataDiagnosa->generateNewId();
            $dataDiagnosa->kdprofile = $idProfile;
            $dataDiagnosa->statusenabled = true;
        } else {
            $dataDiagnosa = DiagnosaPasien::where('norec', $request['detaildiagnosapasien']['norec_dp'])->first();
        }

        $dataDiagnosa->noregistrasifk = $request['detaildiagnosapasien']['noregistrasifk'];
        $dataDiagnosa->ketdiagnosis = 'Diagnosa Pasien';
        $dataDiagnosa->tglregistrasi = null;
        $dataDiagnosa->tglpendaftaran = $request['detaildiagnosapasien']['tglregistrasi'];
        if (isset($request['detaildiagnosapasien']['kasusbaru'])) {
            $dataDiagnosa->iskasusbaru = $request['detaildiagnosapasien']['kasusbaru'];
        }
        if (isset($request['detaildiagnosapasien']['kasuslama'])) {
            $dataDiagnosa->iskasuslama = $request['detaildiagnosapasien']['kasuslama'];
        }
        if (isset($request['detaildiagnosapasien']['istelemedicine'])) {
            $dataDiagnosa->istelemedicine = $request['detaildiagnosapasien']['istelemedicine'];
        }

        try {
            $dataDiagnosa->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "simpan Diagnosa Baru";
        }


        if ($request['detaildiagnosapasien']['norec_dp'] == '' || $request['detaildiagnosapasien']['keterangan'] == '') {
            $dataDetailDiagnosa = new DetailDiagnosaPasien();
            $dataDetailDiagnosa->norec = $dataDetailDiagnosa->generateNewId();
            $dataDetailDiagnosa->kdprofile = $idProfile;
            $dataDetailDiagnosa->statusenabled = true;
            //               $dataDetailDiagnosa->keterangan = '-';
            $dataDetailDiagnosa->objectpegawaifk = $dataPegawaiUser[0]->id; // $this->getCurrentLoginID();

        } else {
            $dataDetailDiagnosa = DetailDiagnosaPasien::where('objectdiagnosapasienfk', $request['detaildiagnosapasien']['norec_dp'])->first();
        }

        $dataDetailDiagnosa->noregistrasifk = $request['detaildiagnosapasien']['noregistrasifk'];
        $dataDetailDiagnosa->tglregistrasi = $request['detaildiagnosapasien']['tglregistrasi'];
        $dataDetailDiagnosa->norec = $dataDetailDiagnosa->generateNewId();
        $dataDetailDiagnosa->objectdiagnosafk = $request['detaildiagnosapasien']['objectdiagnosafk'];
        $dataDetailDiagnosa->objectdiagnosapasienfk = $dataDiagnosa->norec;
        $dataDetailDiagnosa->objectjenisdiagnosafk = $request['detaildiagnosapasien']['objectjenisdiagnosafk'];
        $dataDetailDiagnosa->tglinputdiagnosa = date('Y-m-d H:i:s'); //$request['detaildiagnosapasien']['tglinputdiagnosa'];
        $dataDetailDiagnosa->keterangan = $request['detaildiagnosapasien']['keterangan'];
        $dataDetailDiagnosa->objectpegawaifk = $dataPegawaiUser[0]->id; // $this->getCurrentLoginID();

        $Diagnosa = Diagnosa::where('id', $request['detaildiagnosapasien']['objectdiagnosafk'])
            ->first();
        $Pasien = [];
        if (isset($request['detaildiagnosapasien']['norm'])) {
            $Pasien = Pasien::where('nocm', $request['detaildiagnosapasien']['norm'])
                ->select(DB::raw("
                            namapasien,nocm
                        "))
                ->first();
        }

        $pd = AntrianPasienDiperiksa::where('norec', $request['detaildiagnosapasien']['noregistrasifk'])->first();
        if ($pd->pasien_daftar->ihs_in_progress == null) {
            PasienDaftar::where('norec', $pd->pasien_daftar->norec)->update([
                'ihs_in_progress' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($pd->pasien_daftar->ihs_finished == null) {
            PasienDaftar::where('norec', $pd->pasien_daftar->norec)->update([
                'ihs_finished' => date('Y-m-d H:i:s', strtotime('+1 minutes')),
            ]);
        }

        try {
            $dataDetailDiagnosa->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "simpan Pasien Baru";
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Tersimpan";
            DB::commit();

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $pd->pasien_daftar->noregistrasi;//$request['detaildiagnosapasien']['noregistrasi'];
            $ihs = app('App\Http\Controllers\Bridging\IHSController')->Condition($objetoRequest, true);
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'data' => $dataDiagnosa,
                'diagnosa' => $Diagnosa,
                'pasien' => $Pasien,
                'ihs' => isset($ihs) ? $ihs : null,
                'as' => 'er@epic',
            );
        } else {
            $transMessage = "Data Gagal Disimpan";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message' => $transMessage,
                'data' => $dataDiagnosa,
                'pasien' => $Pasien,
                'as' => 'egie@ramdan',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }

    public function deleteDiagnosaPasien(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        DB::beginTransaction();
        if ($request['diagnosa']['norec_dp'] != '') {
            try {
                $data1 = DetailDiagnosaPasien::where('objectdiagnosapasienfk', $request['diagnosa']['norec_dp'])->where('kdprofile', $idProfile)->delete();
                $transStatus = 'true';
            } catch (\Exception $e) {
                $transStatus = false;
            }
            try {
                $data2 = DiagnosaPasien::where('norec', $request['diagnosa']['norec_dp'])->where('kdprofile', $idProfile)->delete();
                $transStatus = 'true';
            } catch (\Exception $e) {
                $transStatus = false;
            }
        }
        if ($transStatus = 'true') {
            DB::commit();
            $transMessage = "Data Terhapus";
        } else {
            DB::rollBack();
            $transMessage = "Data Gagal Dihapus";
        }

        return $this->setStatusCode(201)->respond([], $transMessage);
    }
    public function saveDiagnosaTindakanPasien(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        DB::beginTransaction();
        $dataPegawaiUser = DB::select(
            DB::raw("select pg.id,pg.namalengkap from loginuser_s as lu
                INNER JOIN pegawai_m as pg on lu.objectpegawaifk=pg.id
                where lu.id=:idLoginUser"),
            array(
                'idLoginUser' => $dataLogin['userData']['id'],
            )
        );

        //        try{
        if ($request['detaildiagnosatindakanpasien']['norec_dp'] == '') {
            $dataDiagnosa = new DiagnosaTindakanPasien();
            $dataDiagnosa->norec = $dataDiagnosa->generateNewId();
            $dataDiagnosa->kdprofile = $idProfile;
            $dataDiagnosa->statusenabled = true;
        } else {
            $dataDiagnosa = DiagnosaTindakanPasien::where('norec', $request['detaildiagnosatindakanpasien']['norec_dp'])->first();
        }
        $dataDiagnosa->objectpasienfk = isset($request['detaildiagnosatindakanpasien']['objectpasienfk']) ? $request['detaildiagnosatindakanpasien']['objectpasienfk'] : $request['detaildiagnosatindakanpasien']['noregistrasifk'];
        $dataDiagnosa->tglpendaftaran = $request['detaildiagnosatindakanpasien']['tglpendaftaran'];
        if (isset($request['detaildiagnosatindakanpasien']['istelemedicine'])) {
            $dataDiagnosa->istelemedicine = $request['detaildiagnosatindakanpasien']['istelemedicine'];
        }
        try {
            $dataDiagnosa->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "simpan Diagnosa Baru";
        }


        if ($request['detaildiagnosatindakanpasien']['norec_dp'] == '') {
            $dataDetailDiagnosa = new DetailDiagnosaTindakanPasien();
            $dataDetailDiagnosa->norec = $dataDetailDiagnosa->generateNewId();
            $dataDetailDiagnosa->kdprofile = $idProfile;
            $dataDetailDiagnosa->statusenabled = true;
            $dataDetailDiagnosa->objectpegawaifk = $dataPegawaiUser[0]->id; // $this->getCurrentLoginID();
        } else {
            $dataDetailDiagnosa = DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $request['detaildiagnosatindakanpasien']['norec_dp'])->first();
        }

        $dataDetailDiagnosa->objectdiagnosatindakanfk = $request['detaildiagnosatindakanpasien']['objectdiagnosatindakanfk'];
        $dataDetailDiagnosa->objectdiagnosatindakanpasienfk = $dataDiagnosa->norec;
        $dataDetailDiagnosa->jumlah = null;
        $dataDetailDiagnosa->objectpegawaifk = $dataPegawaiUser[0]->id; // $this->getCurrentLoginID()
        if (isset($request['detaildiagnosatindakanpasien']['keterangantindakan'])) {
            $dataDetailDiagnosa->keterangantindakan = $request['detaildiagnosatindakanpasien']['keterangantindakan'];
        }

        $dataDetailDiagnosa->tglinputdiagnosa = date('Y-m-d H:i:s');

        try {
            $dataDetailDiagnosa->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "simpan Pasien Baru";
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Tersimpan";
            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $request['detaildiagnosatindakanpasien']['noregistrasi'];
            $ihs = app('App\Http\Controllers\Bridging\IHSController')->Procedure($objetoRequest, true);
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'data' => $dataDiagnosa,
                'Procedure' => $ihs ? $ihs : null,
                'as' => 'egie@ramdan',
            );
        } else {
            $transMessage = "Data Gagal Disimpan";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message' => $transMessage,
                'data' => $dataDiagnosa,
                'as' => 'egie@ramdan',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }

    public function deleteDiagnosaTindakanPasien(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        DB::beginTransaction();
        if ($request['diagnosa']['norec_dp'] != '') {
            try {
                $data1 = DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $request['diagnosa']['norec_dp'])->where('kdprofile', $idProfile)->delete();
                $transStatus = 'true';
            } catch (\Exception $e) {
                $transStatus = false;
            }
            try {
                $data2 = DiagnosaTindakanPasien::where('norec', $request['diagnosa']['norec_dp'])->where('kdprofile', $idProfile)->delete();
                $transStatus = 'true';
            } catch (\Exception $e) {
                $transStatus = false;
            }
        }
        if ($transStatus = 'true') {
            DB::commit();
            $transMessage = "Data Terhapus";
        } else {
            DB::rollBack();
            $transMessage = "Data Gagal Dihapus";
        }
        return $this->setStatusCode(201)->respond([], $transMessage);
    }

    public function getDiagnosaPasienByNoregICD9(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $data = \DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddt.objectdiagnosatindakanfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'dtp.norec as norec_diagnosapasien',
                'ddt.norec as norec_detaildpasien',
                'dt.*',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                'ddt.tglinputdiagnosa'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->where('pd.kdprofile', $idProfile);
        //            ->join ('jenisdiagnosa_m as jd','jd.id','=','ddp.objectjenisdiagnosafk');
        if (isset($request['noCm']) && $request['noCm'] != "" && $request['noCm'] != "undefined") {
            $data = $data->where('ps.nocm', '=', $request['noCm']);
        };
        if (isset($request['noReg']) && $request['noReg'] != "" && $request['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', '=', $request['noReg']);
        };
        if (isset($request['idDept']) && $request['idDept'] != "" && $request['idDept'] != "undefined") {
            $data = $data->where('apd.objectruanganfk', '=', $request['idDept']);
        };
        if (isset($request['kddiagnosatindakan']) && $request['kddiagnosatindakan'] != "" && $request['kddiagnosatindakan'] != "undefined") {
            $data = $data->where('dt.kddiagnosatindakan', '=', $request['kddiagnosatindakan']);
        }
        $data = $data->get();

        $result = array(
            'datas' => $data,
            'message' => 'giw@cepot',
        );
        return $this->respond($result);
    }

    public function getDiagnosaPasienByNoreg(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $data = \DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddp.objectdiagnosafk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'ddp.objectjenisdiagnosafk',
                'jd.jenisdiagnosa',
                'dp.norec as norec_diagnosapasien',
                'ddp.norec as norec_detaildpasien',
                'ddp.tglinputdiagnosa',
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dg.*',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $idProfile);

        if (isset($request['noReg']) && $request['noReg'] != "" && $request['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', '=', $request['noReg']);
        };

        if (isset($request['noCm']) && $request['noCm'] != "" && $request['noCm'] != "undefined") {
            $data = $data->where('ps.nocm', '=', $request['noCm']);
        };
        $data = $data->orderby('ddp.tglinputdiagnosa', 'desc');

        $data = $data->get();

        $result = array(
            'datas' => $data,
            'message' => 'giw',
        );
        return $this->respond($result);
    }
    public function getPasienPerjanjian(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $data = \DB::table('pasienperjanjian_t as rm')
            ->select(
                'rm.norec',
                'rm.objectdokterfk',
                'pg.namalengkap',
                'rm.jumlahkujungan',
                'rm.keterangan',
                'rm.objectpasienfk',
                'rm.tglinput',
                'st.tglkontrol AS tglperjanjian',
                'rm.noperjanjian',
                'rm.objectruanganfk',
                'ru.namaruangan',
                'ru.kdinternal',
                'pg.kddokterbpjs',
                //              'pd.tglregistrasi',
                'ps.nocm',
                'pd.noregistrasi',
                'ps.namapasien',
                'st.diagnosa',
                'st.indikasi',
                'rm.objectsuratfk'
            )
            ->leftJoin('pasien_m as ps', 'rm.objectpasienfk', '=', 'ps.id')
            //            ->leftJoin('pasiendaftar_t as pd','pd.nocmfk','=','ps.id')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rm.objectdokterfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'rm.objectruanganfk')
            ->leftJoin('suratketerangan_t as st', 'st.norec', '=', 'rm.objectsuratfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'st.pasiendaftarfk')
            ->where('rm.kdprofile', $idProfile)
            ->where('rm.statusenabled', true)
            ->orderBy('rm.noperjanjian');

        if (isset($request['noregistrasifk']) && $request['noregistrasifk'] != '') {
            $data = $data->where('rm.noregistrasifk', $request['noregistrasifk']);
        }
        if (isset($request['nocm']) && $request['nocm'] != '') {
            $data = $data->where('ps.nocm', $request['nocm']);
        }
        if (isset($request['nik']) && $request['nik'] != '') {
            $data = $data->where('ps.noidentitas', $request['nik']);
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'Inhuman',
        );
        return $this->respond($result);
    }
    public function getComboRegBaru(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $dataLogin = $request->all();
        $dataPegawai = \DB::table('loginuser_s as lu')
            ->join('pegawai_m as pg', 'pg.id', '=', 'lu.objectpegawaifk')
            ->select('lu.objectpegawaifk', 'pg.namalengkap')
            ->where('lu.id', $dataLogin['userData']['id'])
            ->where('lu.kdprofile', $kdProfile)
            ->first();

        $jk = JenisKelamin::where('statusenabled', true)
            ->select(DB::raw("id, UPPER(jeniskelamin) as jeniskelamin"))
            ->where('kdprofile', $kdProfile)
            ->get();

        $agama = MasterAgama::where('statusenabled', true)
            ->select(DB::raw("id, UPPER(agama) as agama"))
            ->where('kdprofile', $kdProfile)
            ->get();

        $statusPerkawinan = StatusPerkawinan::where('statusenabled', true)
            ->select(DB::raw("id, UPPER(statusperkawinan) as statusperkawinan,namaexternal as namadukcapil"))
            ->where('kdprofile', $kdProfile)
            ->get();

        $pendidikan = Pendidikan::where('statusenabled', true)
            ->select(DB::raw("id, UPPER(pendidikan) as pendidikan,namaexternal as namadukcapil"))
            ->where('kdprofile', $kdProfile)
            ->get();

        $pekerjaan = DB::table('pekerjaan_m')
            ->select(DB::raw("id, UPPER(pekerjaan) as pekerjaan,namaexternal as namadukcapil"))
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->get();

        $gd = DB::table('golongandarah_m')
            ->select(DB::raw("id, UPPER(golongandarah) as golongandarah,namaexternal as namadukcapil"))
            ->where('statusenabled', true)
            ->get();
        $suku = DB::table('suku_m')
            ->select(DB::raw("id, UPPER(suku) as suku"))
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->get();
        // $jenisidentitas = DB::table('rm_jenisidentitas_m')
        //     ->select('id', 'name')
        //     ->where('statusenabled', true)
        //     ->where('kdprofile', $kdProfile)
        //     ->get();
        $result = array(
            'jeniskelamin' => $jk,
            'agama' => $agama,
            'statusperkawinan' => $statusPerkawinan,
            'pendidikan' => $pendidikan,
            'pekerjaan' => $pekerjaan,
            'pegawaiLogin' => $dataPegawai->namalengkap,
            'golongandarah' => $gd,
            'suku' => $suku,
            // 'jenisidentitas' => $jenisidentitas,
            'message' => 'inhuman',
        );

        return $this->respond($result);
    }
    public function getComboAddress(Request $request)
    {

        $kebangsaan = DB::table('kebangsaan_m')
            ->select(DB::raw("id, UPPER(name) as name"))
            ->where('statusenabled', true)
            ->get();

        $negara = DB::table('negara_m')
            ->select(DB::raw("id, UPPER(namanegara) as namanegara"))
            ->where('statusenabled', true)
            ->orderBy('namanegara')
            ->get();

        $kotakabupaten = DB::table('kotakabupaten_m')
            ->select(DB::raw("id, UPPER(namakotakabupaten) as namakotakabupaten"))
            ->where('statusenabled', true)
            ->orderBy('namakotakabupaten')
            ->get();

        $propinsi = DB::table('propinsi_m')
            ->select(DB::raw("id, UPPER(namapropinsi) as namapropinsi"))
            ->where('statusenabled', true)
            ->orderBy('namapropinsi')
            ->get();

        $kecamatan = DB::table('kecamatan_m')
            ->select(DB::raw("id, UPPER(namakecamatan) as namakecamatan"))
            ->where('statusenabled', true)
            ->orderBy('namakecamatan')
            ->get();
        $result = array(
            'kebangsaan' => $kebangsaan,
            'negara' => $negara,
            'kotakabupaten' => $kotakabupaten,
            'propinsi' => $propinsi,
            'kecamatan' => $kecamatan,
            'message' => 'inhuman',
        );

        return $this->respond($result);
    }
    public function getDesaKelurahanPaging(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $req = $request->all();
        if (isset($req['filter']['filters'][0]['value'])) {
            $explode = explode(',', $req['filter']['filters'][0]['value']);
            if (count($explode) > 1) {
                $namaDesa = $explode[0];
                $namaKec = $explode[1];
            }
        }

        //        return $namaKec;
        $Desa = \DB::table('desakelurahan_m as ds')
            ->join('kecamatan_m as kc', 'ds.objectkecamatanfk', '=', 'kc.id')
            ->join('kotakabupaten_m as kk', 'ds.objectkotakabupatenfk', '=', 'kk.id')
            ->join('propinsi_m as pp', 'ds.objectpropinsifk', '=', 'pp.id')
            ->select(DB::raw("ds.id,UPPER(ds.namadesakelurahan) as namadesakelurahan,ds.kodepos,
			                 ds.objectkecamatanfk,ds.objectkotakabupatenfk,ds.objectpropinsifk,
				             kc.namakecamatan,kk.namakotakabupaten,pp.namapropinsi"))
            ->where('ds.statusenabled', true)
            ->orderBy('ds.namadesakelurahan');

        if (
            isset($req['namadesakelurahan']) &&
            $req['namadesakelurahan'] != "" &&
            $req['namadesakelurahan'] != "undefined"
        ) {
            $Desa = $Desa->where('ds.namadesakelurahan', 'ilike', '%' . $req['namadesakelurahan'] . '%');
        };
        if (
            isset($req['namakecamatan']) &&
            $req['namakecamatan'] != "" &&
            $req['namakecamatan'] != "undefined"
        ) {
            $Desa = $Desa->where('kc.namakecamatan', 'ilike', '%' . $req['namakecamatan'] . '%');
        };
        if (
            isset($req['iddesakelurahan']) &&
            $req['iddesakelurahan'] != "" &&
            $req['iddesakelurahan'] != "undefined"
        ) {
            $Desa = $Desa->where('ds.id', $req['iddesakelurahan']);
        };
        if (
            isset($req['filter']['filters'][0]['value']) &&
            $req['filter']['filters'][0]['value'] != "" &&
            $req['filter']['filters'][0]['value'] != "undefined"
        ) {
            if (isset($namaDesa) && isset($namaKec)) {
                $Desa = $Desa
                    ->where('ds.namadesakelurahan', 'ilike', '%' . $namaDesa . '%')
                    ->where('kc.namakecamatan', 'ilike', '%' . $namaKec . '%');
            } else {
                $Desa = $Desa
                    ->where('ds.namadesakelurahan', 'ilike', '%' . $req['filter']['filters'][0]['value'] . '%')
                    ->Orwhere('kc.namakecamatan', 'ilike', '%' . $req['filter']['filters'][0]['value'] . '%');
            }
        }

        $Desa = $Desa->take(20);
        $Desa = $Desa->get();
        $tempDesa = [];
        if (count($Desa) != 0) {
            foreach ($Desa as $item) {
                $tempDesa[] = array(
                    'id' => $item->id,
                    'namadesakelurahan' => $item->namadesakelurahan,
                    'kodepos' => $item->kodepos,
                    'namakecamatan' => $item->namakecamatan,
                    'namakotakabupaten' => $item->namakotakabupaten,
                    'namapropinsi' => $item->namapropinsi,
                    'desa' => $item->namadesakelurahan . ', ' . $item->namakecamatan . ',  ' . $item->namakotakabupaten . ', ' .
                        $item->namapropinsi,
                    'objectkecamatanfk' => $item->objectkecamatanfk,
                    'objectkotakabupatenfk' => $item->objectkotakabupatenfk,
                    'objectpropinsifk' => $item->objectpropinsifk,
                );
            }
        }
        return $this->respond($tempDesa);
    }
    public function getDataComboNEW(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $deptRanap = explode(',', $this->settingDataFixed('kdDepartemenRanapFix', $idProfile));
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $deptJalan = explode(',', $this->settingDataFixed('kdDepartemenRawatJalanFix', $idProfile));
        $kdDepartemenRawatJalan = [];
        foreach ($deptJalan as $item) {
            $kdDepartemenRawatJalan[] =  (int)$item;
        }

        $dataRuanganInap = \DB::table('ruangan_m as ru')
            ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk')
            ->where('ru.statusenabled', true)
            ->wherein('ru.objectdepartemenfk', $kdDepartemenRawatInap)
            ->where('ru.kdprofile', (int)$kdProfile)
            ->orderBy('ru.namaruangan')
            ->get();
        $dataRuanganJalan = \DB::table('ruangan_m as ru')
            ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk', 'ru.noruangan as kodebpjs')
            ->where('ru.statusenabled', true)
            ->wherein('ru.objectdepartemenfk', $kdDepartemenRawatJalan)
            ->where('ru.kdprofile', (int)$kdProfile)
            ->orderBy('ru.namaruangan')
            ->get();

        $dataAsalRujukan = collect(DB::select("
            select id,asalrujukan from asalrujukan_m am where am.statusenabled = true and am.id in (11,12)
        "));
        // \DB::table('asalrujukan_m as as')
        //     ->select('as.id', 'as.asalrujukan')
        //     ->where('as.statusenabled', true)
        //     ->orderBy('as.asalrujukan')
        //     ->get();


        $dataAsalRujukanRegis = collect(DB::select("
        select id,asalrujukan from asalrujukan_m am where am.statusenabled = true and am.id not in (11,12)
        "));

        $dataKelompok = \DB::table('kelompokpasien_m as kp')
            ->select('kp.id', 'kp.kelompokpasien')
            ->where('kp.statusenabled', true)
            //            ->where('kp.kdprofile',(int)$kdProfile)
            ->orderBy('kp.kelompokpasien')
            ->get();

        $dataKelas = \DB::table('kelas_m as kl')
            ->select('kl.id', 'kl.namakelas')
            ->where('kl.statusenabled', true)
            ->orderBy('kl.namakelas')
            ->get();

        $dataKamar = \DB::table('kamar_m as kmr')
            ->select('kmr.id', 'kmr.namakamar')
            ->where('kmr.statusenabled', true)
            ->where('kmr.kdprofile', (int)$kdProfile)
            ->orderBy('kmr.namakamar')
            ->get();

        $dataHubunganPeserta = \DB::table('hubunganpesertaasuransi_m as hp')
            ->select('hp.id', 'hp.hubunganpeserta')
            ->where('hp.statusenabled', true)
            ->where('hp.kdprofile', (int)$kdProfile)
            ->orderBy('hp.hubunganpeserta')
            ->get();

        $jenisPelayanan = \DB::table('jenispelayanan_m as jp')
            ->select('jp.kodeinternal as id', 'jp.jenispelayanan')
            ->where('jp.statusenabled', true)
            ->orderBy('jp.jenispelayanan')
            ->get();
        $pekerjaan = DB::table('pekerjaan_m')
            ->select('id', 'pekerjaan')
            ->where('statusenabled', true)
            ->get();
        $kdJenisPegawaiDokter = $this->settingDataFixed('kdJenisPegawaiDokter', $kdProfile);

        $dataPegawai = \DB::table('pegawai_m')
            ->select('id','namalengkap')
            ->where('statusenabled', true)
            ->where('objectjenispegawaifk', $kdJenisPegawaiDokter)
            ->where('kdprofile', (int)$kdProfile)
            ->orderBy('namalengkap')
            ->get();

        $result = array(
            'ruanganranap' => $dataRuanganInap,
            'ruanganrajal' => $dataRuanganJalan,
            'kelompokpasien' => $dataKelompok,
            'kelas' => $dataKelas,
            'kamar' => $dataKamar,
            'asalrujukan' => $dataAsalRujukan,
            'asalrujukanregis' => $dataAsalRujukanRegis,
            'hubunganpeserta' => $dataHubunganPeserta,
            'jenispelayanan' => $jenisPelayanan,
            'pekerjaan' => $pekerjaan,
            'dokter' => $dataPegawai,
            'message' => 'ramdanegie',
        );

        return $this->respond($result);
    }
    public function getDataComboResepEMR(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();

        $dataSigna = \DB::table('stigma as st')
            ->select('st.id', 'st.name')
            ->orderBy('st.name')
            ->get();

        $dataRuangan = \DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id', 'ru.namaruangan')
            ->where('mlu.kdprofile', $idProfile)
            ->where('mlu.objectloginuserfk', $request['userData']['id'])
            ->get();

        $dataRuanganFamasi = \DB::table('ruangan_m as ru')
            ->select('ru.id', 'ru.namaruangan')
            ->where('ru.kdprofile', $idProfile)
            ->where('ru.objectdepartemenfk', 14)
            ->where('ru.statusenabled', true)
            ->orderBy('ru.id')
            ->get();

        $dataJenisKemasan = \DB::table('jeniskemasan_m as jk')
            ->select('jk.id', 'jk.jeniskemasan')
            ->where('jk.kdprofile', $idProfile)
            ->where('jk.statusenabled', true)
            ->get();
        $dataJenisRacikan = \DB::table('jenisracikan_m as jk')
            ->select('jk.id', 'jk.jenisracikan')
            ->where('jk.kdprofile', $idProfile)
            ->where('jk.statusenabled', true)
            ->get();
        $kdjenisobat = explode(',', $this->settingDataFixed('kdJenisProdukObat', $idProfile));
        $arrkdjenisobat = [];
        foreach ($kdjenisobat as $it) {
            $arrkdjenisobat[] =  (int)$it;
        }


        $dataAsalProduk = \DB::table('asalproduk_m as ap')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectasalprodukfk', '=', 'ap.id')
            ->select('ap.id', 'ap.asalproduk')
            ->where('ap.kdprofile', $idProfile)
            ->where('ap.statusenabled', true)
            ->orderBy('ap.id')
            ->groupBy('ap.id', 'ap.asalproduk')
            ->get();


        $dataKonversiProduk = \DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $idProfile)
            ->where('ks.statusenabled', true)
            ->get();

        $dataTarifAdminResep = \DB::table('settingdatafixed_m as rt')
            ->select('rt.namafield', 'rt.nilaifield')
            ->where('rt.kdprofile', $idProfile)
            ->where('rt.statusenabled', true)
            ->where('rt.namafield', 'tarifadminresep')
            ->orderBy('rt.id')
            ->first();

        $dataRoute = \DB::table('routefarmasi as rt')
            ->select('rt.id', 'rt.name')
            ->where('rt.kdprofile', $idProfile)
            ->where('rt.statusenabled', true)
            ->orderBy('rt.id')
            ->get();


        $dataProdukResult = [];


        $dataSatuanResep = \DB::table('satuanresep_m as kp')
            ->select('kp.id', 'kp.satuanresep')
            ->where('kp.statusenabled', true)
            ->orderBy('kp.satuanresep')
            ->get();

        $result = array(
            'ruanganfarmasi' => $dataRuanganFamasi,
            'jeniskemasan' => $dataJenisKemasan,
            'produk' => $dataProdukResult,
            'ruangan' => $dataRuangan,
            'asalproduk' => $dataAsalProduk,
            'signa' => $dataSigna,
            'jenisracikan' => $dataJenisRacikan,
            'tarifadminresep' => $dataTarifAdminResep,
            'route' => $dataRoute,
            'satuanresep' => $dataSatuanResep,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }
    public function getRuanganDepoTelemedicine(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $kdDepoTelemedicine = $this->settingDataFixed('kdDepoTelemedicine', $kdProfile);

        $result = array(
            'koderuangan' => $kdDepoTelemedicine,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }
    public function getMasterDokter(Request $r)
    {

        $result =
            MasterPegawai::mine()
            ->whereIn('objectjenispegawaifk', explode(',', $this->settingFix('idJenisPegawaiDokter')))
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }
}
