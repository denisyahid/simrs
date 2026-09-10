<?php

namespace App\Http\Controllers\Bridging;

use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HRISCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    function getHeader($request)
    {
        $kdProfile = $this->kdProfile;
        $header = array(
            "x-auth-token:". $this->settingDataFixed('tokenHRIS',$kdProfile)
        );
        return $header;
    }
    function endPoint($request)
    {
        $kdProfile = $this->kdProfile;
        return $this->settingDataFixed('urlHRIS', $kdProfile);
    }
    function endPointMaster($request)
    {
        $kdProfile = $this->kdProfile;
        return $this->settingDataFixed('urlMasterDataHRIS', $kdProfile);
    }
    public function hrisTools(Request $request, $lokal = null)
    {
        try {
            $headers = $this->getHeader($request);
            $dataJsonSend = null;
            if ($request['data'] != null) {
                $dataJsonSend = json_encode($request['data']);
            }
            $methods = $request['method'];
            $kdProfile = $this->kdProfile;
        // return $this->settingDataFixed('urlHRIS', $kdProfile);
            $url = $this->settingDataFixed('urlHRIS', $kdProfile) . $request['url'];
            // if($methods == 'PUT'){
            //     $url = $this->endPoint() . $request['url'].'/'.$request['data']['id'];
            // }
           
            $response = $this->curlAPI($headers, $dataJsonSend, $url, $methods);
            $metadata['code'] = $response->statusCode;
            $metadata['message'] = $response->message;
        } catch (Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
            $response = json_decode(json_encode($response)); //Turn it into an object
        }
      
        if ($lokal) {
            return $response;
        }
       
        // return response()->json($response);
        // return $this->respond($response,  $metadata['code'], $metadata['message']);
        return $this->respond($response, (int)$metadata['code'] == 500 ? 201 : (int)$metadata['code'], $metadata['message']);

    }
    public function hrisToolsMaster(Request $request, $lokal = null)
    {
        $kdProfile = $this->kdProfile;
        if(isset($request['simrs'])){
            $simrs = DB::table($request['simrs'])->where('kdprofile', $kdProfile)->get();
        }
        try {
            $headers = $this->getHeader($request);
            $dataJsonSend = null;
            if ($request['data'] != null) {
                $dataJsonSend = json_encode($request['data']);
            }
            $methods = $request['method'];
            $url = $this->endPointMaster($request) . $request['url'];
            // return $url;
            // if($methods == 'PUT'){
            //     $url = $this->endPoint() . $request['url'].'/'.$request['data']['id'];
            // }
           
            $response = $this->curlAPI($headers, $dataJsonSend, $url, $methods);
            
        } catch (Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
            $response = json_decode(json_encode($response)); //Turn it into an object
        }
        $result = array(
            'hris'=> $response,
            'simrs' => $simrs
        );
        return $this->respond($result);
        // if ($lokal) {
        //     return $response;
        // }
       
        // return response()->json($response);
    }
    protected function curlAPI($headers, $dataJsonSend = null, $url, $method)
    {
        $curl = curl_init();
        // dd ($headers, $dataJsonSend = null, $url, $method);
        if ($dataJsonSend == null || $dataJsonSend == '') {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers
            ));
        } else {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $dataJsonSend,
                CURLOPT_HTTPHEADER => $headers
            ));
        }
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $result = "Terjadi Kesalahan #:" . $err;
        } else {
            $result = json_decode($response);
        }
        return $result;
    }
    public function getDataCombo(Request $request) {
        $urldata = DB::table('maptablehristosimrs_m')->where('statusenabled', true)->get();
        $result = array(
            'as'=>'fizi@epic',
            'urldata'=> $urldata
        );
        return $this->respond($result);
    }

    public function getDataHRISSIMRS(Request $request) {
        $kdProfile = $this->kdProfile;
        $hris = $this->hrisToolsMaster($request);   
        $simrs = DB::table($request['simrs'])->where('kdprofile', $kdProfile)->get();
        $result = array(
            'hris'=> $hris,
            'simrs' => $simrs
        );
        return $this->respond($result);
    }

    public function saveMasterHRISID(Request $request) {
        $kdProfile = $this->kdProfile;
        \DB::beginTransaction();
        try {
            $updatehris = 
            $data = DB::table($request['master'])->where('id', $request['id_simrs'])->update([
                'hris_id'=>$request['id_hris']
            ]);
            $transStatus = true;
        } catch (\Exception $e) {
            //throw $th;
            $transStatus = false;
        }
        if ($transStatus) {
            $transMessage = 'Simpan Berhasil';
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'as' => 'ea@epic',
            );
        } else {
            $transMessage = 'Simpan Gagal';
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => $transMessage,
                'ee'=>$e->getMessage() . ' ' . $e->getLine(),
                'as' => 'ea@epic',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }

    public function getMasterHRIS(Request $request) {
        $urldata = DB::table('mappinghris_m')->where('statusenabled', true)->get();
        $result = array(
            'as'=>'fizi@epic',
            'urldata'=> $urldata
        );
        return $this->respond($result);
    }
}
