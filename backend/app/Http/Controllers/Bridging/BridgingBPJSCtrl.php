<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\LoggingUser;
use App\Traits\Valet;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Webpatser\Uuid\Uuid;
use Exception;

class BridgingBPJSCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    function getSetting()
    {
        $res = [
            'BPJS_urlAplicare' => '',
            'BPJS_urlAntrol' => '',
            'BPJS_userKeyAntrol' => '',
            'BPJS_urlVclaim' => '',
            'BPJS_ConsID' => '',
            'BPJS_ConsPassword' => '',
            'BPJS_userKeyVclaim' => '',
            'BPJS_EnabledDummyRegistrasi' => '',
            'BPJS_EnabledKioskDummy' => '',
            'BPJS_namaPPKRujukan' => '',
            'BPJS_kodePPKRujukan' => '',
            'BPJS_urlERekamMedis' => '',
            'BPJS_urlApotik' => '',
            'BPJS_userKeyApotikOnline' => '',
            'PasswordConsumerApotikOnline' => '',
            'BPJS_UserIDAPOTIK' => ''
        ];

        $data = DB::table('settingdatafixed_m')->where('kdprofile', !empty(session('kdProfile')) ? session('kdProfile') : 1)
            ->select('namafield', 'nilaifield')
            ->where('statusenabled', true)
            ->whereIn('namafield', [
                'BPJS_urlAplicare',
                'BPJS_urlAntrol',
                'BPJS_userKeyAntrol',
                'BPJS_urlApotik',
                'BPJS_userKeyApotikOnline',

                'BPJS_urlVclaim',
                'BPJS_ConsID',
                'BPJS_ConsPassword',
                'BPJS_userKeyVclaim',

                'BPJS_EnabledDummyRegistrasi',
                'BPJS_EnabledKioskDummy',
                'BPJS_namaPPKRujukan',
                'BPJS_kodePPKRujukan',
                'BPJS_urlERekamMedis',
                'PasswordConsumerApotikOnline',
                'BPJS_UserIDAPOTIK'
            ])
            ->get();


        foreach ($data as $v) {
            foreach (array_keys($res) as $v2) {
                if ($v->namafield == $v2) {
                    $res[$v->namafield] = $v->nilaifield;
                }
            }
        }

        return $res;
    }
    function getHeaderBPJSV2()
    {
        $set = $this->getSetting();

        $data = $set['BPJS_ConsID'];

        $secretKey = $set['BPJS_ConsPassword'];
        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);

        $encodedSignature = base64_encode($signature);

        $header = array(
            "Content-Type" => "Application/x-www-form-urlencoded",
            "X-cons-id" => $data,
            "X-signature" => $encodedSignature,
            "X-timestamp" => $tStamp,
            "user_key" => $set['BPJS_userKeyVclaim']

        );

        return $header;
    }
    public function bpjsTools(Request $request, $local = false)
    {
        ini_set('max_execution_time', 60000);

        
        try {
            if(isset($request["encrypted"]) && $request["encrypted"] == true) {
                return $this->respond([
                    "metaData" => [
                        "code" => 400,
                        "message" => "Service sementara ditutup"
                    ]
                ], 400, "Service sementara ditutup");
            }
            if (isset($request['jenis']) && $request['jenis'] == 'apotik') {
                $headers = $this->getHeaderApotikOnline();
            }else{
                $headers = $this->getHeaderBPJSV2();
            }
            $set = $this->getSetting();
            $dataJsonSend = null;

            if ($request['data'] != null) {
                $dataJsonSend = $request['data']; //json_encode($request['data']);
            }
            // if (substr($request['url'], 0, 12) == 'Peserta/nik/' || substr($request['url'], 0, 16) == 'Peserta/nokartu/') {
            //     if (isset($request['token'])) {
            //         $secret = config('app.CAPTCHA_SECRETKEY');
            //         $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$request['token']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
            //         if (!$response['success']) {
            //             $metadata['code'] = 201;
            //             $metadata['message'] = 'INVALID_CAPTCHA, are you robot ?';

            //             return $this->respond(null,  $metadata['code'], $metadata['message']);
            //         }
            //     }else{
            //         $metadata['code'] = 201;
            //         $metadata['message'] = 'NOTFOUND_CAPTCHA, are you robot ?';

            //         return $this->respond(null,  $metadata['code'], $metadata['message']);
            //     }
            // }

            $methods = $request['method'];
            $baseURL = $set['BPJS_urlVclaim'];
            if (isset($request['jenis']) && $request['jenis'] == 'apotik') {
                $keyDecrypt = $set['BPJS_UserIDAPOTIK'] . $set['PasswordConsumerApotikOnline'] . $headers['X-timestamp'];
            }else{
                $keyDecrypt = $set['BPJS_ConsID'] . $set['BPJS_ConsPassword'] . $headers['X-timestamp'];
            }
            if (isset($request['jenis']) && $request['jenis'] == 'antrean') {
                $baseURL = $set['BPJS_urlAntrol'];
                $headers['user_key'] =  $set['BPJS_userKeyAntrol'];
            }
            if (isset($request['jenis']) && $request['jenis'] == 'i-care') {
                $baseURL = $set['BPJS_urlVclaim'];
                $baseURL =  str_replace('vclaim-rest/', 'wsihs/', $baseURL);
                $headers['Content-Type']  = 'application/json';
                $headers['user_key'] =  $set['BPJS_userKeyVclaim'];
            }
            if (isset($request['jenis']) && $request['jenis'] == 'aplicare') {
                $baseURL = $set['BPJS_urlVclaim'];
                $baseURL =  str_replace('vclaim-rest/', 'aplicaresws/', $baseURL);

                $headers['Content-Type']  = 'application/json';
                $headers['user_key'] =  $set['BPJS_userKeyVclaim'];
            }
            if (isset($request['jenis']) && $request['jenis'] == 'apotik') {
                $headers['Content-Type']  = 'application/json';
                $baseURL = $set['BPJS_urlApotik'];
                $headers['user_key'] = $set['BPJS_userKeyApotikOnline'];
            }
            if (isset($request['jenis']) && $request['jenis'] == 'eRekamMedis') {
                $baseURL = $set['BPJS_urlERekamMedis'];
                $headers['Content-Type'] = 'text/plain';
                $jsonErekamMedis = $request['data'];

                $key = $set['BPJS_ConsID'] .  $set['BPJS_ConsPassword'] .  $set['BPJS_kodePPKRujukan'];

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['nosep'] = $request['data']['request']['noSep'];

                $getMRBundle = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->getMRBundle($objetoRequest, true);
                $jsonErekamMedis['request']['dataMR'] = $this->compressGZIP($getMRBundle, $key);

                $dataJsonSend = $jsonErekamMedis;
            }

            $methods = strtoupper($request['method']);
            $url = $baseURL . $request['url'];
            $curl = curl_init();

            if (!$curl) {
                return response()->json([
                    'metaData' => ['code' => 500, 'message' => 'Failed to initialize cURL'],
                    'response' => null
                ], 500);
            }

            $httpHeaders = [];
            foreach ($headers as $key => $value) {
                $httpHeaders[] = "$key: $value";
            }

            $options = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $methods,
                CURLOPT_HTTPHEADER => $httpHeaders,
                CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1',
            ];

            if ($dataJsonSend != null) {
                $options[CURLOPT_POSTFIELDS] = json_encode($dataJsonSend, true);
            }

            curl_setopt_array($curl, $options);
            
            $response = curl_exec($curl);
            // return $response;

            if ($response === false) {
                $err = curl_error($curl);
                curl_close($curl);
                $metadata['code'] = 201;
                $metadata['message'] = 'Response Dari BPJS : ' . $url . ' ' . json_encode($err);
                if ($local) {
                    return array(
                        'metaData' => $metadata,
                        'response' => null,
                    );
                }
                return $this->respond(null,  $metadata['code'], $metadata['message']);
            }

            curl_close($curl);
            
            if (isset($request['jenis']) && $request['jenis'] == 'eRekamMedis') {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['nosep'] = $request['data']['request']['noSep'];
                $objetoRequest['keterangan'] = $response;
                $this->saveLogMRBundle($objetoRequest);
            }
            // return $this->respond($url);

            if ($response !== false) {
                if ($this->isJson($response)) {
                    $response = json_decode($response);
                } else {
                    $response = $response;
                }
                if(!isset($response->metaData) && !isset($response->metadata)) {
                    // return $response;
                    $result = [
                        "message" => "Data tidak ditemukan",
                        "code" => 500,
                        "data" => null
                    ];
                    return $this->respond($result, 500, "Data tidak ditemukan");
                }
                $metadata = isset($response->metaData) ? $response->metaData : $response->metadata;
                
                if ($metadata->code != 200 && $metadata->code != 1) {
                    if ($metadata->code == 0) {
                        $metadata->code = 201;
                    }
                    $result = null;
                    return $this->respond($result,  $metadata->code, $metadata->message);
                }
                // return $response;
                if (!isset($response->response)) {
                    if ($metadata->code == 1) {
                        $metadata->code = 200;
                    }
                    $result = null;
                    return $this->respond($result,  $metadata->code, $metadata->message);
                }
                
                // return $response;
                $bahan = $response->response;
                // return $bahan;
                if($request['jenis'] == 'aplicare') {
                    return $this->respond($bahan);
                }
                try {
                    $encrypt_method = 'AES-256-CBC';
                    $key_hash = hex2bin(hash('sha256', $keyDecrypt));
                    $iv = substr(hex2bin(hash('sha256', $keyDecrypt)), 0, 16);
                    $output = openssl_decrypt(base64_decode($bahan), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                    // return $bahan;
                    $x = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
                    $result = json_decode($x);
                    // return $result;
                } catch (Exception $e) {
                    $result = $bahan;
                    $metadata['code'] = 201;
                    $metadata['message'] = $e->getMessage() . ' ' . $e->getLine();
                    $metadata['result'] = $result;
        
                    if ($local) {
                        return array(
                            'metaData' => $metadata,
                            'response' => null,
                        );
                    }
                    return $this->respond(null,  $metadata['code'], $metadata['message']);
                }
                if(isset($request["encrypted"]) && $request["encrypted"] == true) {
                    $result = $this->encryptData($result);
                    return $this->respond($result);
                }
                if ($metadata->code == 1) {
                    $metadata->code = 200;
                }

                if ($local) {
                    return array(
                        'metaData' => $metadata,
                        'response' => $result,
                    );
                }
                return $this->respond($result, (int)$metadata->code == 500 ? 201 : (int)$metadata->code, $metadata->message);
            }
        } catch (\Exception $e) {
            $metadata['code'] = 201;
            $metadata['message'] = $e->getMessage() . ' ' . $e->getLine();

            if ($local) {
                return array(
                    'metaData' => $metadata,
                    'response' => null,
                );
            }
            return $this->respond(null,  $metadata['code'], $metadata['message']);
        }
    }

    function encryptData($data) {
        $response = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $enc = sprintf("%u", crc32($response));
        return base64_encode($response . '::' . $enc);
    }

    function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function getNoRujukanPcareNoKartu(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] = "Rujukan/" . $request['nokartu'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getNoRujukanRs(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] = "Rujukan/RS/" . $request['nokartu'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getNoPeserta(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] = "Peserta/nokartu/" . $request['nokartu'] . "/tglSEP/" . $request['tglsep'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getMonitoringHistori($noKartu)
    {
        // $tglMulai = Carbon::now()->subMonth(3)->format('Y-m-d');
        $tglMulai = date('Y-m-d', strtotime("-90 days"));
        $tglAkhir = Carbon::now()->format('Y-m-d');

        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] = "monitoring/HistoriPelayanan/NoKartu/" . $noKartu . "/tglAwal/" . $tglMulai . "/tglAkhir/" . $tglAkhir;
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getNoRujukanPcare(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] =  "Rujukan/" . $request['norujukan'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getNoRujukanRsNoKartu(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] =  "Rujukan/RS/Peserta/" . $request['nokartu'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getDokterDPJP(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] =   "referensi/dokter/pelayanan/" . $request['jenisPelayanan'] . "/tglPelayanan/"
            . $request['tglPelayanan'] . "/Spesialis/" . $request['kodeSpesialis'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getListPemakaianAsuransi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tglawal = $request['tgl'] . " 00:00";
        $tglakhir = $request['tgl'] . " 23:59";
        $data = DB::table("pemakaianasuransi_t as pa")
            ->select("pa.nosep", "pa.nmprovider")
            ->join("pasiendaftar_t as pd", "pd.norec", "=", "pa.noregistrasifk")
            ->where("pd.statusenabled", true)
            ->where("pd.kdprofile", (int)$kdProfile)
            ->whereBetween("pd.tglregistrasi", [$tglawal, $tglakhir])
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'hs@epic',
        );
        return $this->respond($result);
    }

    public function saveMonitoringKlaim(Request $request)
    {
        try {

            $kdProfile = $this->kdProfile;
            DB::table('monitoringklaim_t')
                ->where('jenispelayanan', $request['jenispelayanan'])
                ->where('statusklaimfk', $request['statusklaimfk'])
                ->whereRaw("to_char(tglpulang,'yyyy-MM') ='$request[bulan]'")
                ->delete();

            $dataInsert = [];
            $newData2 = $request['details'];
            foreach ($newData2 as $item) {
                $dataInsert[] = array(
                    'norec' => substr(Uuid::generate(), 0, 32),
                    'kdprofile' => $kdProfile,
                    'statusenabled' => true,
                    'nofpk' => $item['nofpk'],
                    'tglpulang' => $item['tglpulang'],
                    'jenispelayanan' => $request['jenispelayanan'],
                    'nosep' => $item['nosep'],
                    'tglsep' => $item['tglsep'],
                    'status' => $item['status'],
                    'totalpengajuan' => $item['totalpengajuan'],
                    'totalsetujui' => $item['totalsetujui'],
                    'totaltarifrs' => $item['totaltarifrs'],
                    'statusklaimfk' => $request['statusklaimfk'],
                    'totalgrouper' => $item['totalgrouper'],
                );


                if (count($dataInsert) > 100) {
                    DB::table('monitoringklaim_t')->insert($dataInsert);
                    $dataInsert = [];
                }
            }

            DB::table('monitoringklaim_t')->insert($dataInsert);
            $result = array(
                'result' => $dataInsert,
                'message' => 'er@epic',
                'messages' => 'Sukses',
                'status' => 200
            );
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "message" => $e->getMessage(),
                "as" => 'ea@epic',
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $result['messages']);
    }
    public function getDaftarMappingDokterBpjsToDokterRs(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $kdProfile = $this->kdProfile;
        $data = DB::table('pegawai_m as pg')
            ->selectRaw("
                    pg.id,pg.kddokterbpjs,pg.namalengkap
                ")
            ->where('pg.kdprofile', $kdProfile)
            ->where('pg.statusenabled', true)
            ->where('pg.objectjenispegawaifk', 1)
            ->wherenotNull('pg.kddokterbpjs');

        if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
            $data = $data->where('pg.id', '=', $request['idPegawai']);
        }

        if (isset($request['kodeDokterBpjs']) && $request['kodeDokterBpjs'] != "" && $request['kodeDokterBpjs'] != "undefined") {
            $data = $data->where('pg.kddokterbpjs', '=', $request['kodeDokterBpjs']);
        } else {
            if (isset($request['dokterbpjsArr']) && $request['dokterbpjsArr'] != "" && $request['dokterbpjsArr'] != "undefined") {
                $arrRuang = explode(',', $request['dokterbpjsArr']);
                $kodeRuang = [];
                foreach ($arrRuang as $item) {
                    $kodeRuang[] = (int)$item;
                }
                $data = $data->whereIn('pg.kddokterbpjs', $kodeRuang);
            }
        }
        $data = $data->orderBy('pg.namalengkap', 'asc');
        $count = $data->count();
        $data = $data->when($limit, function ($query) use ($limit) {
            return $query->limit($limit);
        });
        $data = $data->when($offset, function ($query) use ($offset) {
            return $query->offset($offset);
        });
        $result = [
            'data' => $data->get(),
            'total' => $count
        ];
        return $this->respond($result);
    }
    public function saveMappingDokterBpjsDokterRs(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = Pegawai::where('id', $request['idpegawai'])
                ->where('kdprofile', $kdProfile)->first();
            $data->kodeexternal = $request['kodedokterbpjs'];
            $data->kddokterbpjs = $request['kodedokterbpjs'];
            $data->save();
            $this->LOGGING(
                'Mapping Dokter Bpjs To Dokter Rs',
                $data->norec ?? null,
                'pegawai_m',
                'Mapping Dokter Bpjs To Dokter Rs ' . $data->namalengkap ?? null
            );
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Simpan Gagal" . $e->getMessage() . $e->getLine();
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'ea@epic',
            );
        } else {
            $transMessage = $transMessage;
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "as" => 'ea@epic',
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function saveHapusMappingDokterBpjsDokterRs(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = Pegawai::where('id', $request['id'])
                ->where('kdprofile', $kdProfile)->first();
            $data->kodeexternal = "V3-RSGJ";
            $data->kddokterbpjs = null;
            $this->LOGGING(
                'Hapus Mapping Dokter Bpjs To Dokter Rs',
                $data->norec ?? null,
                'pegawai_m',
                'Hapus Mapping Dokter Bpjs To Dokter Rs ' . $data->namalengkap ?? null
            );
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Hapus Gagal" . $e->getMessage() . $e->getLine();
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'ea@epic',
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "result" => null,
                "as" => 'ea@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function updateAplicaresBedAfter(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $kodeppk =  $this->settingFix('BPJS_kodePPKRujukan', $kdProfile);

        $data = collect(DB::select("
            select x.kodekelas,
            x.namakelas,x.koderuang,x.namaruang,sum(x.tersedia) as tersedia,sum(x.tersediapria) as tersediapria,
            sum(x.tersediawanita) as tersediawanita,sum(x.tersediapriawanita) as tersediapriawanita,
            sum(x.kapasitas) as kapasitas
                from (SELECT
                        CASE WHEN kmr.kodeexternal IS NOT NULL THEN kmr.kodeexternal ELSE kl.namaexternal END AS kodekelas,
                        ru.ID AS koderuang,
                        CASE WHEN 	kmr.kodeexternal IS NOT NULL THEN  kmr.kodeexternal ELSE kl.namakelas END AS namakelas,
                        ru.namaruangan AS namaruang,
                        0 AS tersediapria,
                        0 AS tersediawanita,
                        0 AS tersediapriawanita,
                        COUNT ( tt.ID ) AS kapasitas,
                        SUM ( CASE WHEN sb.ID = 2 THEN 1 ELSE 0 END ) AS tersedia
                        FROM tempattidur_m AS tt
                        INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
                        INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
                        INNER JOIN kelas_m AS kl ON kl. ID = kmr.objectkelasfk
                        INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
                        WHERE tt.statusenabled = TRUE
                        AND kmr.statusenabled = TRUE
                        AND ru.id=" . $request->idruangan . "
                            AND kl.id=" . $request->idkelas . "
                        GROUP BY
                        kl.namakelas,ru.id,kl.namaexternal,
                        ru.namaruangan, kmr.kodeexternal

            ) as x group by  x.kodekelas,
            x.namakelas,x.koderuang,x.namaruang
            "))->first();

        if (!empty($data)) {
            $json = array(
                "kodekelas" => $data->kodekelas,
                "koderuang" => $data->koderuang,
                "namaruang" => $data->namaruang,
                "kapasitas" => $data->kapasitas,
                "tersedia" => $data->tersedia,
                "tersediapria" => $data->tersediapria,
                "tersediawanita" => $data->tersediawanita,
                "tersediapriawanita" => $data->tersediapriawanita
            );

            $objreqhis = new \Illuminate\Http\Request();
            $objreqhis['url'] =   "rest/bed/update/" . $kodeppk;
            $objreqhis['method'] = "POST";
            $objreqhis['jenis'] = "aplicare";
            $objreqhis['data'] = $json;
            $response =  $this->bpjsTools($objreqhis, true);

            return $this->respond($response);
        }
    }

    public function getKamarRS(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $kodeppk =  $this->settingFix('BPJS_kodePPKRujukan', $kdProfile);

        $paramRuangan = '';
        if (isset($request['namaruangan']) && $request['namaruangan'] != 'undefined' && $request['namaruangan'] != '') {
            $paramRuangan = " and ru.namaruangan ilike '%" . $request['namaruangan'] . "%'";
        }
        $paramKelas = '';
        if (isset($request['kelas']) && $request['kelas'] != 'undefined' && $request['kelas'] != '') {
            $paramKelas = " and kl.namakelas ilike '%" . $request['kelas'] . "%'";
        }

        $data = DB::select(DB::raw("
            select x.kodekelas,
            x.namakelas,x.koderuang,x.namaruang,sum(x.tersedia) as tersedia,sum(x.tersediapria) as tersediapria,
            sum(x.tersediawanita) as tersediawanita,sum(x.tersediapriawanita) as tersediapriawanita,
            sum(x.kapasitas) as kapasitas

            from (SELECT
                    -- CASE WHEN kmr.kodeexternal IS NOT NULL THEN kmr.kodeexternal ELSE kl.namaexternal END AS kodekelas,
                    CASE WHEN kmr.kodeexternal IS NOT NULL AND kmr.kodeexternal not in ('RSBM') THEN kmr.kodeexternal
                    ELSE kl.kodeexternal END AS kodekelas,
                    ru.ID AS koderuang,
                    -- CASE WHEN 	kmr.kodeexternal IS NOT NULL THEN  kmr.kodeexternal ELSE kl.namakelas END AS namakelas,
                    case when kmr.kodeexternal is not null AND kmr.kodeexternal not in ('RSBM') THEN kmr.namaexternal
                    else kl.namakelas end as namakelas,
                    ru.namaruangan AS namaruang,
                    0 AS tersediapria,
                    0 AS tersediawanita,
                    0 AS tersediapriawanita,
                    COUNT ( tt.ID ) AS kapasitas,
                    SUM ( CASE WHEN sb.ID = 2 THEN 1 ELSE 0 END ) AS tersedia
                        FROM tempattidur_m AS tt
                        INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
                        INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
                        INNER JOIN kelas_m AS kl ON kl. ID = kmr.objectkelasfk
                        INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
                        WHERE tt.statusenabled = TRUE
                        AND kmr.statusenabled = TRUE
                        $paramRuangan
                        $paramKelas
                        GROUP BY
                        kl.namakelas,ru.id,kl.namaexternal,
                        kl.kodeexternal,
                        kmr.namaexternal,
                        ru.namaruangan, kmr.kodeexternal

            ) as x group by  x.kodekelas,
            x.namakelas,x.koderuang,x.namaruang

            ORDER BY kodekelas, namaruang
    "));

        $res = [
            'data' => $data,
            'setting' => $kodeppk
        ];

        return $this->respond($res);
    }
    public function compressGZIP($req, $key)
    {

        $string = json_encode($req, false);
        $compressed = gzencode($string, 9);
        $encrypt_method = 'AES-256-CBC';
        $encrypt_key = $key; // gabungan consid + secretkey + kodefaskes

        // hash
        $key_hash = hex2bin(hash('sha256', $encrypt_key));

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hex2bin(hash('sha256', $encrypt_key)), 0, 16);

        //Data to encrypt
        $data = $compressed;
        $encrypted_data = openssl_encrypt(base64_encode($data), $encrypt_method, $key_hash, 0, $iv);
        return $encrypted_data;
    }
    public function decompressGZIP($data, $key)
    {

        // $data = 'ASA';
        // $output = false;
        // $encrypt_method = "AES-256-CBC";
        // $string =  gzcompress($data, 9);
        // $key =  hex2bin(hash('sha256', $key));
        // $iv =  substr(hex2bin(hash('sha256', $key)), 0, 16);
        // $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);


        //     // decrypt
        // $output = openssl_decrypt($output, $encrypt_method, $key, 0, $iv);
        // $result = gzuncompress($output);

        // $res['bahan'] = $data;
        // $res['encrypt'] = $output;
        // $res['decrypt'] = $result;


        $encrypt_method = 'AES-256-CBC';
        $encrypt_key = $key; // gabungan consid + secretkey + kodefaskes

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hex2bin(hash('sha256', $encrypt_key)), 0, 16);

        $key_hash =  hex2bin(hash('sha256', $key));
        //Data to encrypt
        $output = openssl_decrypt($data, $encrypt_method, $key_hash, 0, $iv);

        $encrypted_data = gzuncompress($output);
        $decrypted_data =  json_decode($encrypted_data);


        return $decrypted_data;
    }
   public function getMRBundle(Request $r){
        $kodePPK = $this->getSetting()['BPJS_kodePPKRujukan'];
        // $kdProfile = $this->getDataKdProfile($r);
        $data = DB::table('pemakaianasuransi_t as pa')
        ->join ('pasiendaftar_t as pd','pd.norec','=','pa.noregistrasifk')
        ->join ('pasien_m as ps','ps.id','=','pd.nocmfk')
        ->join ('alamat_m as alm','alm.nocmfk','=','ps.id')
        ->leftjoin ('ruangan_m as ru','ru.id','=','pd.objectruanganlastfk')
        ->leftjoin ('asuransipasien_m as apn','apn.id','=','pa.objectasuransipasienfk')
        ->leftjoin ('rekanan_m as rek2','rek2.id','=','pd.objectrekananfk')
        // ->leftjoin ('hubunganpesertaasuransi_m as hpa','hpa.id','=','apn.objecthubunganpesertafk')
        ->leftjoin ('kelas_m as kls','kls.id','=','apn.objectkelasdijaminfk')
        ->leftjoin ('diagnosa_m as dg','dg.id','=','pa.objectdiagnosafk')
        ->leftjoin ('kelompokpasien_m as kps','kps.id','=','pd.objectkelompokpasienlastfk')
        ->leftjoin ('asalrujukan_m as asl','asl.id','=','pd.asalrujukanfk')
        ->leftjoin ('pegawai_m as pg','pg.id','=','pd.objectpegawaifk')
        ->leftjoin ('desakelurahan_m as ds','ds.id','=','alm.objectdesakelurahanfk')
        ->select('pa.norec','apn.id as norec_ap','pd.noregistrasi','pa.nokepesertaan','apn.namapeserta',
            'apn.noasuransi','apn.tgllahir','apn.noidentitas','apn.alamatlengkap',
            'apn.objecthubunganpesertafk','pa.nosep','pa.tglsep as tanggalsep',
            'apn.objectkelasdijaminfk','kls.namakelas','pa.catatan','pa.norujukan',
            'apn.kdprovider','apn.nmprovider','pa.tglrujukan','pa.objectdiagnosafk',
            'dg.kddiagnosa','dg.namadiagnosa','pa.lakalantas_nama as lakalantas','pa.ppkrujukan',
            // 'pa.penjaminlaka',
            'pd.objectkelompokpasienlastfk','pd.objectrekananfk','rek2.namarekanan','kps.kelompokpasien','apn.kdpenjaminpasien',
            'apn.jenispeserta','apn.jenispeserta as hubunganpeserta','apn.tgllahir','pa.cob','pa.katarak',
            // 'pa.keteranganlaka',
            'pa.tglkejadian','pa.suplesi','pa.nosepsuplesi','pa.kdpropinsi_kode as kdpropinsi','pa.kdpropinsi_nama as namapropinsi','pa.kdkabupaten_kode as kdkabupaten',
            'pa.kdkabupaten_nama as namakabupaten','pa.kdkecamatan_kode as kdkecamatan','pa.kdkecamatan_nama as namakecamatan','pa.nosurat as nosuratskdp','pa.kodedpjp','pa.namadpjp',
            'pd.asalrujukanfk','asl.asalrujukan','pa.dpjplayan_kode as kodedpjpmelayani','pa.dpjplayan_nama as namadjpjpmelayanni',
            'pa.klsrawatnaik_nama as klsrawatnaik', 'pa.pembiayaan_nama as pembiayaan', 'pa.pembiayaan_nama as penanggungjawab','pa.tujuankun_nama as tujuankunj',
            'pa.flagprocedure_nama as flagprocedure','pa.kdpenunjang_nama as kdpenunjang',
            'pa.assesmentpel_nama as assesmentpel','pa.tglcreate',
            // 'pa.statuskunjungan',
            'pa.poli_kode as poliasalkode','pa.poli_kode as politujuankode',
            'ru.objectdepartemenfk','pd.objectpegawaifk',
            'pg.tgllahir as tgllahir_prac','pg.objectjeniskelaminfk as jeniskelaminfk_prac',
            'pg.namalengkap as namalengkap_prac', 'pg.noidentitas as noidentitas_prac',
            'ps.objectjeniskelaminfk as jeniskelaminfk_pas','ps.nohp','ps.noidentitas','ps.tgllahir as tgllahir_pas',
            'alm.alamatlengkap', 'alm.kodepos','ds.namadesakelurahan','ps.namapasien',
            'pd.tglregistrasi','pd.tglpulang','ps.nocm'
            )
        ->where('pa.nosep',$r['nosep'])
        // ->where('pa.kdprofile', $kdProfile)
        ->first();
        $icd10 =  collect(DB::select("
                SELECT
                ps.namapasien
                ,pd.noregistrasi
                ,dg.kddiagnosa
                ,dg.namadiagnosa
                ,pd.noregistrasi
                ,ddp.norec
                ,ddp.tglinputdiagnosa
                FROM pasiendaftar_t as pd
                join pasien_m as ps on ps.id=pd.nocmfk
                join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                JOIN detaildiagnosapasien_t as ddp on ddp.noregistrasifk=apd.norec
                join diagnosa_m as dg on dg.id=ddp.objectdiagnosafk
                where pd.noregistrasi='$data->noregistrasi'
        "));
        $icd9 = collect(DB::select("
                SELECT
                pd.noregistrasi,
                pd.tglregistrasi,
                apd.objectruanganfk,

                apd.norec AS norec_apd,
                ddt.objectdiagnosatindakanfk,
                dt.kddiagnosatindakan,
                dt.namadiagnosatindakan,
                dtp.norec AS norec_diagnosapasien,
                ddt.norec AS norec_detaildpasien,
                dt.*,
                ddt.keterangantindakan,
                pg.namalengkap,
                ddt.tglinputdiagnosa,
                ps.namapasien

            FROM
                pasiendaftar_t AS pd
                INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                INNER JOIN diagnosatindakanpasien_t AS dtp ON dtp.objectpasienfk = apd.norec
                INNER JOIN detaildiagnosatindakanpasien_t AS ddt ON ddt.objectdiagnosatindakanpasienfk = dtp.norec
                INNER JOIN diagnosatindakan_m AS dt ON dt.id = ddt.objectdiagnosatindakanfk
                LEFT JOIN pegawai_m AS pg ON pg.id = ddt.objectpegawaifk
                 WHERE  pd.noregistrasi ='$data->noregistrasi'
            "));


        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['id_dept'] = $data->objectdepartemenfk;
        $objetoRequest['smart_claim'] = true;
        $Organization = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Organization($objetoRequest, true);
        // $Organization['id'] = $kodePPK.'-'.substr(Uuid::generate(), 0, 36);

        $Practitioner = [
            "resourceType" => "Practitioner",
            "id" => $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
            "birthDate" => $data->tgllahir_prac,
            "gender" => $data->jeniskelaminfk_prac  == 1 ? 'male' : "female",
            "identifier" => [
                [
                    "system" => "https://fhir.kemkes.go.id/id/nakes-his-number",
                    "value" => $data->objectpegawaifk
                ],
                [
                    "system" => "https://fhir.kemkes.go.id/id/nik",
                    "use" => "official",
                    "value" =>  $data->noidentitas_prac,
                ]
            ],
            "meta" => [
                "lastUpdated" => "2023-08-31T01:05:06.281570+00:00",
                "versionId" => "MTY5MzQ0MzkwNjI4MTU3MDAwMA"
            ],
            "name" => [
                [
                    "text" =>  $data->namalengkap_prac,
                    "use" => "official"
                ]
            ],
            "qualification" => [
                [
                    "code" => [
                        "coding" => [
                            [
                                "code" => "STR-KKI",
                                "display" => "Surat Tanda Registrasi Dokter",
                                "system" => "https://terminology.kemkes.go.id/v1-0302"
                            ]
                        ]
                    ],
                    "identifier" => [
                        [
                            "system" => "https://fhir.kemkes.go.id/id/str-kki-number",
                            "value" => ""
                        ]
                    ],
                    "period" => [
                        "end" => "",
                        "start" => ""
                    ]
                ],
                [
                    "code" => [
                        "coding" => [
                            [
                                "code" => "STR-KKI",
                                "display" => "Surat Tanda Registrasi Dokter",
                                "system" => "https://terminology.kemkes.go.id/v1-0302"
                            ]
                        ]
                    ],
                    "identifier" => [
                        [
                            "system" => "https://fhir.kemkes.go.id/id/str-kki-number",
                            "value" => ""
                        ]
                    ],
                    "period" => [
                        "end" => "",
                        "start" => ""
                    ]
                ]
            ]
        ];
        $Patient = [
            "resourceType" => "Patient",
            "id" => $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
            "identifier" => [
                [
                    "use" => "usual",
                    "type" => [
                        "coding" => [
                            [
                                "system" => "http://hl7.org/fhir/v2/0203",
                                "code" => "MR"
                            ]
                        ]
                    ],
                    "value" => $data->nocm,
                    "assigner" => [
                        "display" => $Organization['partOf']['display']
                    ]
                ],
                [
                    "use" => "official",
                    "type" => [
                        "coding" => [
                            [
                                "system" => "http://hl7.org/fhir/v2/0203",
                                "code" => "MB"
                            ]
                        ]
                    ],
                    "value" => $data->nokepesertaan,
                    "assigner" => [
                        "display" => "BPJS KESEHATAN"
                    ]
                ],
                [
                    "use" => "official",
                    "type" => [
                        "coding" => [
                            [
                                "system" => "http://hl7.org/fhir/v2/0203",
                                "code" => "NNIDN"
                            ]
                        ]
                    ],
                    "value" =>  $data->noidentitas,
                    "assigner" => [
                        "display" => "KEMENDAGRI"
                    ]
                ]
            ],
            "active" => true,
            "name" => [
                [
                    "use" => "official",
                    "text" => $data->namapasien
                ]
            ],
            "maritalStatus" => [
                "coding" => [
                    [
                        "system" => "http://hl7.org/fhir/v3/MaritalStatus",
                        "code" => ""
                    ]
                ]
            ],
            "telecom" => [
                [
                    "system" => "phone",
                    "value" => "",
                    "use" => "work"
                ],
                [
                    "system" => "phone",
                    "value" => $data->nohp,
                    "use" => "mobile"
                ],
                [
                    "system" => "phone",
                    "value" => "TDK ADA",
                    "use" => "home"
                ]
            ],
            "gender" => $data->jeniskelaminfk_pas == 1 ?  "male" : "female",
            "birthDate" => $data->tgllahir_pas,
            "deceasedBoolean" => false,
            "address" => [
                [
                    "line" => [
                        $data->alamatlengkap
                    ],
                    "city" => "",
                    "district" => $data->namadesakelurahan,
                    "state" => "",
                    "postalCode" => $data->kodepos,
                    "text" =>   $data->alamatlengkap,
                    "use" => "home",
                    "type" => "both"
                ]
            ],
            "managingOrganization" => [
                "reference" => "Organization/" . explode('/', $Organization['partOf']['reference'])[1],
                "display" => $Organization['partOf']['display']
            ]

        ];
        $Condition = [];
        $Procedure = [];
        if ($data->kddiagnosa != null) {
            $Condition[] = [

                "resourceType" => "Condition",
                "id" => $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
                "clinicalStatus" => "active",
                "verificationStatus" => "confirmed",
                "category" => [
                    [
                        "coding" => [
                            [
                                "system" => "http://hl7.org/fhir/condition-category",
                                "code" => "encounter-diagnosis",
                                "display" => "Encounter Diagnosis"
                            ]
                        ]
                    ]
                ],
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://hl7.org/fhir/sid/icd-10",
                            "code" => $data->kddiagnosa,
                            "display" => $data->namadiagnosa,
                        ]
                    ],
                    "text" => $data->namadiagnosa,
                ],
                "subject" => [
                    "reference" => "Patient/" . $Patient['id'],
                ],
                "onsetDateTime" => $data->tanggalsep

            ];
        }

        if (count($icd10) > 0) {
            foreach ($icd10 as $d) {
                $Condition[] = [
                    "resourceType" => "Condition",
                    "id" => $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
                    "clinicalStatus" => "active",
                    "verificationStatus" => "confirmed",
                    "category" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://hl7.org/fhir/condition-category",
                                    "code" => "encounter-diagnosis",
                                    "display" => "Encounter Diagnosis"
                                ]
                            ]
                        ]
                    ],
                    "code" => [
                        "coding" => [
                            [
                                "system" => "http://hl7.org/fhir/sid/icd-10",
                                "code" => $d->kddiagnosa,
                                "display" => $d->namadiagnosa,
                            ]
                        ],
                        "text" => $d->namadiagnosa,
                    ],
                    "subject" => [
                        "reference" => "Patient/" . $Patient['id'],
                    ],
                    "onsetDateTime" => $d->tglinputdiagnosa

                ];
            }
        }

        $conEncounter = [];
        foreach ($Condition as $cin) {
            $conEncounter[] =  [
                "condition" => [
                    "reference" => "Condition/" . $cin['id'],
                    "role" => [
                        "coding" => [
                            [
                                "system" => "http://hl7.org/fhir/diagnosis-role",
                                "code" => "DD",
                                "display" => "Discharge Diagnosis"
                            ]
                        ]
                    ],
                    "rank" => 1
                ]
            ];
        }

        $Encounter = [

            "resourceType" => "Encounter",
            "id" => $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
            "identifier" => [
                [
                    "system" => "http://api.bpjs-kesehatan.go.id:8080/Vclaim-rest/SEP/",
                    "value" => $r['nosep']
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $Patient['id'],
                "display" => $Patient['name'][0]['text'],
                "noSep" => $r['nosep']
            ],
            "class" => [
                "system" => "http://hl7.org/fhir/v3/ActCode",
                "code" =>  $data->objectdepartemenfk == 16 ?  'IMP' : 'AMB',
                "display" =>  $data->objectdepartemenfk == 16 ?   "inpatient encounter" :  "ambulatory"
            ],
            "incomingReferral" => [
                [
                    "identifier" => [
                        [
                            "system" => "nomor_rujukan_bpjs",
                            "value" => $data->norujukan
                        ],
                        [
                            "system" => "nomor_rujukan_internal_rs",
                            "value" => $data->nosuratskdp
                        ]
                    ]
                ]
            ],
            "reason" => [
                [
                    "coding" => [
                        [
                            "code" => $data->kddiagnosa,
                            "display" =>  $data->namadiagnosa,
                            "system" => "http://hl7.org/fhir/sid/icd-10"
                        ]
                    ],
                    "text" =>  $data->namadiagnosa
                ]
            ],
            "diagnosis" => $conEncounter,
            "hospitalization" => [
                "dischargeDisposition" => [
                    [
                        "coding" => [
                            [
                                "code" => "home",
                                "display" => "Home",
                                "system" => "http://hl7.org/fhir/discharge-disposition"
                            ]
                        ]
                    ]
                ]
            ],
            "period" => [
                "end" => $data->tglregistrasi,
                "start" => $data->tglpulang
            ],
            "status" => "finished"


        ];

        $Procedure = [];
        if (count($icd9) > 0) {
            foreach ($icd9 as $d) {

                $Procedure[] =
                    [
                        "resourceType" => "Procedure",
                        "id" =>  $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
                        "status" => "completed",
                        'code' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://hl7.org/fhir/sid/icd-9-cm',
                                    'code' =>  $d->kddiagnosatindakan,
                                    'display' => $d->namadiagnosatindakan,
                                ),
                            ),
                        ),
                        "subject" => [
                            "reference" => "Patient/" . $Patient['id'],
                            "display" => $Patient['name'][0]['text'],
                        ],
                        "context" => [
                            "reference" =>
                            "Encounter/" . $Encounter['id'],
                            "display" => $Patient['name'][0]['text'] . " encounter on " . $data->tglregistrasi,
                        ],
                        "performedPeriod" => [
                            "start" => $d->tglinputdiagnosa,
                            "end" => $d->tglinputdiagnosa,
                        ],
                        "performer" => [
                            [
                                "actor" => [
                                    "reference" =>
                                    "Practitioner/" . $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
                                    "display" => $d->namalengkap,
                                ],
                            ],
                        ],
                        'note' =>
                        array(

                            array(
                                'text' => $d->keterangantindakan,
                            ),
                        )
                    ];
            }
        }


        $medication  =  collect(DB::select("

            SELECT
            sr.noresep,
            pp.hargasatuan,
            pp.stock,
            apd.objectruanganfk,
            ru.namaruangan,
            pp.rke,
            pp.jeniskemasanfk,
            pp.aturanpakai,
            pp.routefk,
            pp.produkfk,
            pp.produkfk as idproduk,
            pr.namaproduk,
            pp.nilaikonversi,
            pr.objectsatuanstandarfk,
            ss.satuanstandar,
            pp.satuanviewfk,
            pp.jumlah,
            pp.hargadiscount,
            pp.dosis,
            pp.jenisobatfk,
            pp.jasa,
            pp.hargajual,
            pp.hargasatuan,
            pp.strukterimafk,
            pp.qtydetailresep,
            pp.ispagi,
            pp.issiang,
            pp.ismalam,
            pp.issore,
            pr.kekuatan,
            pp.keteranganpakai,
            pp.iskronis,
            pp.satuanresepfk,

            pp.tglkadaluarsa
            ,case when ru.objectdepartemenfk=16 then  'inpatient' else 'outpatient'  end as code_class
            ,case when ru.objectdepartemenfk=16 then  'Inpatient' else 'Outpatient'  end as name_class
            ,ru.namaruangan

            ,pg.namalengkap  as penulisresep
            ,ru2.namaruangan as ihs_apotik_display
            ,sr.tglresep
            ,pp.norec as norec_pp
        FROM
            strukresep_t AS sr
            INNER JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
            INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
            INNER JOIN ruangan_m AS ru2 ON ru2.id = sr.ruanganfk
            INNER JOIN produk_m AS pr ON pr.id = pp.produkfk
            INNER JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
            inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
            left join pegawai_m as pg on pg.id=sr.penulisresepfk
        WHERE
             pd.noregistrasi = '$data->noregistrasi'
            and sr.statusenabled=true
            and pp.strukresepfk is not null

       "));
        $MedicationRequest = [];
        foreach ($medication as $k => $m) {
            $MedicationRequest[] =
                [
                    "resourceType" => "MedicationRequest",
                    "identifier" => [
                        "system" => "https://transmedic.co.id/no_resep",
                        "value" =>  $m->noresep . '-' . ($k + 1),
                    ],
                    "subject" => [

                        "reference" => "Patient/" . $Patient['id'],
                        "display" => $Patient['name'][0]['text'],

                    ],
                    "intent" => "final",
                    "medicationCodeableConcept" => [
                        "coding" => [
                            [
                                "code" => $m->produkfk,
                                "system" => "https://transmedic.co.id/drug",
                            ],
                        ],
                        "text" => $m->namaproduk,
                    ],
                    "dosageInstruction" => [
                        [
                            "doseQuantity" => [
                                "code" => $m->satuanstandar,
                                "system" => "http://unitsofmeasure.org",
                                "unit" => $m->satuanstandar,
                                "value" => $m->jumlah,
                            ],
                            // "route" => [
                            //     "coding" => [
                            //         [
                            //             "code" => "002",
                            //             "display" => "INTRAVENOUS",
                            //             "system" => "http://snomed.info/sct",
                            //         ],
                            //     ],
                            // ],
                            "timing" => [
                                "repeat" => [
                                    "frequency" =>  $m->aturanpakai,
                                    "period" => 1,
                                    "periodUnit" => "na",
                                ],
                            ],
                            "additionalInstruction" => [["text" => $m->aturanpakai]],
                        ],
                    ],
                    "reasonCode" => [
                        [
                            "coding" => [
                                ["code" => "", "display" => "", "system" => ""],
                            ],
                            "text" => "",
                        ],
                    ],
                    "requester" => [
                        "agent" => [
                            "display" => $m->penulisresep,
                            "reference" =>
                            "Practitioner/" . $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
                        ],
                        "onBehalfOf" => [
                            "reference" =>
                            "Organization/" . explode('/', $Organization['partOf']['reference'])[1],
                        ],
                    ],
                    "meta" => ["lastUpdated" =>  $m->tglresep],


                ];
        }
        $expertise = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('hasilradiologi_t as hr', 'hr.pelayananpasienfk', '=', 'pp.norec')
            ->join('pegawai_m as pg', 'hr.pegawaifk', '=', 'pg.id')
            ->select(
                DB::raw("
                to_char(pp.tglpelayanan,'dd-MM-yyyy') as tgllayanan,
                to_char(pp.tglpelayanan,'HH:mm') as jamlayanan,pp.tglpelayanan as tanggal, pp.jumlah,
                ru.namaruangan,pd.noregistrasi, pd.tglregistrasi,hr.norec,hr.nofoto,
                hr.keterangan as ekspertise,pr.namaproduk,pr.id as produkfk,pg.namalengkap as dokter")
            )
            ->where('pd.noregistrasi', $data->noregistrasi)
            ->get();
        $DiagnosticReport = [];

        foreach ($expertise as $ex) {
            $DiagnosticReport[] = [
                "resourceType" => "DiagnosticReport",
                "id" =>  $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
                "subject" => [
                    "reference" => "Patient/" . $Patient['id'],
                    "display" => $Patient['name'][0]['text'],
                    "noSep" => $r['nosep']
                ],
                "category" => [
                    "coding" => [
                        "system" => "http://hl7.org/fhir/v2/0074",
                        "code" => "RAD",
                        "display" => "Radiology",
                    ],
                ],
                "status" => "final",
                "performer" => [
                    [
                        "reference" =>
                        "Organization/" . $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
                        "display" => $ex->namaruangan,
                    ],
                ],
                "result" => [
                    [
                        "resourceType" => "Observation",
                        "id" => $ex->norec,
                        "status" => "final",

                        "issued" => $ex->tanggal,
                        "effectiveDateTime" => $ex->tanggal,
                        "code" => [
                            "coding" => [
                                // "system" => "http://snomed.info/sct",
                                "system" => "http://transmedic.co.id/radiologi_id",
                                "code" => $ex->produkfk,
                                "display" =>  $ex->namaproduk,
                            ],
                            "text" =>  $ex->namaproduk,
                        ],
                        "performer" => [
                            "reference" =>
                            "Practitioner/" . $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
                            "display" => $ex->dokter,
                        ],
                        "image" => [
                            [
                                "comment" => $ex->nofoto,
                                "link" => ["reference" => "", "display" => ""],
                            ],
                        ],
                        "conclusion" =>
                        $ex->ekspertise,
                    ],
                ],

            ];
        }

        $bundle = [
            "resourceType" => "Bundle",
            "id" => $kodePPK . '-' . substr(Uuid::generate(), 0, 36),
            "meta" => [
                "lastUpdated" => date('Y-m-d H:i:s')
            ],
            "identifier" => [
                "system" => "sep",
                "value" => $r['nosep']
            ],
            "type" => "document",
            "entry" => [
                array(
                    "resource" => $Organization
                ),

                array(
                    "resource" => $Patient
                ),

                array(
                    "resource" => $Practitioner
                ),

                array(
                    "resource" => $Condition
                ),
                array(
                    "resource" => $Encounter
                ),
                array(
                    "resource" => $Procedure
                ),
                array(
                    "resource" => $MedicationRequest
                ),
                array(
                    "resource" => $DiagnosticReport
                ),
                // array(
                //     "resource" => $Composition
                // ),
                // array(
                //     "resource" => $Device
                // ),

            ],
        ];

        foreach ($bundle['entry']  as $k =>  $bun) {
            if (count($bun['resource']) == 0 || $bun['resource'] == null) {
                array_splice($bundle['entry'], $k);
            }
        }
        return $bundle;
    }
    public function saveLogMRBundle(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = DB::table('loginuser_s')->where('id',  session("id"))->first();
            $kdProfile = $data->kdprofile;

            $newId = LoggingUser::max('id');
            $newId = $newId + 1;
            $logUser = new LoggingUser();
            $logUser->id = $newId;
            $logUser->norec = $logUser->generateNewId();
            $logUser->kdprofile = $kdProfile;
            $logUser->statusenabled = true;
            $logUser->jenislog = 'SMART CLAIM';
            $logUser->noreff = $request['nosep'];
            $logUser->referensi = 'nomor sep';
            $logUser->keterangan = 'SMART CLAIM No SEP : ' . $request['nosep'] . ' | ' . json_encode($request['keterangan']);
            $logUser->objectloginuserfk =  session("id");
            $logUser->tanggal = date('Y-m-d H:i:s');
            $logUser->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
    function getHeaderApotikOnline()
    {
        $data = "32333";

        $secretKey = "0kS55036F0";
        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);

        $encodedSignature = base64_encode($signature);


        $header = array(
            "Content-Type" => "Application/x-www-form-urlencoded",
            "X-cons-id" => $data,
            "X-signature" => $encodedSignature,
            "X-timestamp" => $tStamp,

        );

        return $header;
    }
}

