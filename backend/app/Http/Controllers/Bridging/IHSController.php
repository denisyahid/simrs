<?php
namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Traits\CrudMaster;
use App\Traits\Valet;
use App\Models\Transaksi\LoggingUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Web\LoginUser;
use App\Web\Token;
use App\Models\Master\Pasien;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Master\Departemen;
use App\Models\Master\Profile as MasterProfile;
use App\Models\Transaksi\IHS_Transaction;
use App\Models\Transaksi\DetailDiagnosaPasien;
use App\Models\Transaksi\DetailDiagnosaTindakanPasien;
use App\Models\Transaksi\DiagnosaTindakanPasien;
use App\Models\Transaksi\EMRPasienD;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\Rencana;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukPelayanan;

use App\Models\Master\Profile;
use App\Models\Transaksi\StrukPelayananDetail;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
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
use Ramsey\Uuid\Uuid as RamseyUuidUuid;
use Webpatser\Uuid\Uuid as UuidUuid;

class IHSController extends Controller
{
    use Valet;
    protected $kdProfile = 1;

    public function __construct()
    {
        parent::__construct($skip_authentication = true);
    }
    protected function curlAPI($headers, $dataJsonSend = null, $url, $method)
    {
        $curl = curl_init();
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
    function getCredentialz()
    {
        $res['client_id'] = $this->settingDataFixed('client_id_IHS', $this->kdProfile);
        $res['client_secret'] = $this->settingDataFixed('client_secret_IHS', $this->kdProfile);
        $res['org'] = $this->settingDataFixed('organization_id_IHS', $this->kdProfile);
        return $res;
    }
    function endPoint()
    {
        return $this->settingDataFixed('base_url_IHS', $this->kdProfile);
    }
    public function generateToken($lokal = null)
    {
        $url = $this->settingDataFixed('auth_url_IHS', $this->kdProfile);

        $headers = array(
            'Content-Type:application/x-www-form-urlencoded',
        );
        $cred = $this->getCredentialz();
        $client_id = $cred['client_id'];
        $client_secret = $cred['client_secret'];

        $dataJsonSend =   'client_id=' . $client_id . '&client_secret=' . $client_secret;
        $methods = 'POST';
        $url = $url . "?grant_type=client_credentials";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $methods,
            CURLOPT_POSTFIELDS => $dataJsonSend,
            CURLOPT_HTTPHEADER => $headers
        ));

        $response = curl_exec($curl);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpcode == 200) {
            $response =  json_decode($response);
        }else{
            $rss=  json_decode($response);
            if(isset($rss->resourceType)){
                $response =$rss;
            }else{
                $response = array(
                    "result" =>$response,
                    "body" =>$dataJsonSend,
                    "url" =>$url,
                    "method" =>$methods,
                );
           
            }
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    function getHeader()
    {
        $token = $this->generateToken(true)->access_token;

        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        );
        return $header;
    }
    public function ihsTools(Request $request, $lokal = null)
    {
        try {
            $headers = $this->getHeader();
            $dataJsonSend = null;
            if ($request['data'] != null) {
                $dataJsonSend = json_encode($request['data']);
            }
            $methods = $request['method'];
            $url = $this->endPoint() . $request['url'];

            $response = $this->curlAPI($headers, $dataJsonSend, $url, $methods);
        } catch (Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal) {
            return $response;
        }
        return response()->json($response);
    }
    public function Organization(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $q =  collect(DB::select(" 
        SELECT
        kd.*,
        pr.ihs_id AS ihs_profile,
        pr.ihs_province,
        pr.ihs_city,
        pr.ihs_district,
        pr.ihs_village,
        pr.kodepos,
        pr.alamatlengkap,
        pr.namakota,
        pr.alamatemail,
        pr.website,
        pr.fixedphone ,
        pr.namalengkap as namaprofile
    FROM
        departemen_m AS kd
        LEFT JOIN profile_m AS pr ON pr.ID = kd.kdprofile 
    WHERE
        kd.kdprofile = $kdProfile 
        AND kd.statusenabled = true
        AND kd.ID = $request[id_dept]"))->first();


        $data =  array(
            'resourceType' => 'Organization',
            'active' => $q->statusenabled,
            'identifier' =>
            array(
                0 =>
                array(
                    'use' => 'official',
                    'system' => 'http://sys-ids.kemkes.go.id/organization/' . $q->ihs_profile,
                    'value' => (string) $q->id,
                ),
            ),
            'type' =>
            array(
                0 =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                            'code' => 'dept',
                            'display' => 'Hospital Department',
                        ),
                    ),
                ),
            ),
            'name' => $q->namadepartemen,
            'telecom' =>
            array(
                [
                    'system' => 'phone',
                    'value' => $q->fixedphone,
                    'use' => 'work',
                ],
                [
                    'system' => 'email',
                    'value' => $q->alamatemail,
                    'use' => 'work',
                ],
                [
                    'system' => 'url',
                    'value' =>  $q->website,
                    'use' => 'work',
                ]
            ),
            'address' =>
            array(
                0 =>
                array(
                    'use' => 'work',
                    'type' => 'both',
                    'line' =>
                    array(
                        0 => $q->alamatlengkap,
                    ),
                    'city' => $q->namakota,
                    'postalCode' => $q->kodepos,
                    'country' => 'ID',
                    'extension' =>
                    array(
                        0 =>
                        array(
                            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                            'extension' =>
                            array(
                                0 =>
                                array(
                                    'url' => 'province',
                                    'valueCode' =>  $q->ihs_province,
                                ),
                                1 =>
                                array(
                                    'url' => 'city',
                                    'valueCode' =>  $q->ihs_city,
                                ),
                                2 =>
                                array(
                                    'url' => 'district',
                                    'valueCode' =>  $q->ihs_district,
                                ),
                                3 =>
                                array(
                                    'url' => 'village',
                                    'valueCode' =>  $q->ihs_village,
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'partOf' =>
            array(
                'reference' => 'Organization/' . $q->ihs_profile,
                'display' => $q->namaprofile,
            ),
        );

        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['url'] = $data['resourceType'];
        $objetoRequest['method'] = 'POST';
        $objetoRequest['data'] = $data;

        $response =  $this->ihsTools($objetoRequest, true);
        $noterror = true;
        if ($response->resourceType == 'OperationOutcome') {
            $noterror = false;
        }

        if ($q->ihs_id == null) {
            $mod = new IHS_Transaction();
            $mod->norec = $mod->generateNewId();
            $mod->statusenabled = $noterror;
            $mod->kdprofile = $this->kdProfile;
        } else {
            $mod = IHS_Transaction::where('id', $q->ihs_id)->first();
        }
        $mod->resourcetype = $data['resourceType'];
        $mod->url =  $this->endPoint() . $data['resourceType'];
        $mod->method = 'POST';
        $mod->id = isset($response->id) ? $response->id : null;
        $mod->body = json_encode($data);
        $mod->response =  json_encode($response);
        $mod->date = date('Y-m-d H:i:s');
        $mod->save();
        if (isset($response->id)) {
            Departemen::where('id', $q->id)->update([
                'ihs_id' => $response->id
            ]);
        }
        return response()->json($response);
    }

    public function Location(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $q =  collect(DB::select(" 
        SELECT
        kd.*,
        pr.ihs_id AS ihs_profile,
        pr.ihs_province,
        pr.ihs_city,
        pr.ihs_district,
        pr.ihs_village,
        pr.kodepos,
        pr.alamatlengkap,
        pr.namakota,
        pr.alamatemail,
        pr.website,
        pr.fixedphone ,
        pr.namalengkap as namaprofile,
        pr.lat,
        pr.lng,
        dep.ihs_id as ihs_dep
    FROM
        ruangan_m AS kd
        LEFT JOIN profile_m AS pr ON pr.ID = kd.kdprofile 
        LEFT JOIN departemen_m AS dep ON dep.ID = kd.objectdepartemenfk 
    WHERE
        kd.kdprofile = $kdProfile 
        AND kd.statusenabled = true
        AND kd.ID = $request[id_ru]"))->first();


        $data = array(
            'resourceType' => 'Location',
            'identifier' =>
            array(
                0 =>
                array(
                    'system' => 'http://sys-ids.kemkes.go.id/location/' . $q->ihs_profile,
                    'value' => (string) $q->id,
                ),
            ),
            'status' => 'active',
            'name' =>  $q->namaruangan,
            'description' => $q->namaruangan,
            'mode' => 'instance',
            'telecom' =>
            array(
                0 =>
                array(
                    'system' => 'phone',
                    'value' => $q->fixedphone,
                    'use' => 'work',
                ),
                1 =>
                array(
                    'system' => 'email',
                    'value' =>  $q->alamatemail,
                    'use' => 'work',
                ),
                2 =>
                array(
                    'system' => 'url',
                    'value' =>  $q->website,
                    'use' => 'work',
                ),
            ),
            'physicalType' =>
            array(
                'coding' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
                        'code' => 'ro',
                        'display' => 'Room',
                    ),
                ),
            ),
            'position' =>
            array(
                'longitude' => (float) $q->lng,
                'latitude' => (float)$q->lat,
                'altitude' => 0,
            ),
            'managingOrganization' =>
            array(
                'reference' => 'Organization/' . $q->ihs_dep,
            ),
        );

        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['url'] = $data['resourceType'];
        $objetoRequest['method'] = 'POST';
        $objetoRequest['data'] = $data;

        $response =  $this->ihsTools($objetoRequest, true);
        $noterror = true;
        if ($response->resourceType == 'OperationOutcome') {
            $noterror = false;
        }

        if ($q->ihs_id == null) {
            $mod = new IHS_Transaction();
            $mod->norec = $mod->generateNewId();
            $mod->statusenabled = $noterror;
            $mod->kdprofile = $this->kdProfile;
        } else {
            $mod = IHS_Transaction::where('id', $q->ihs_id)->first();
        }
        $mod->resourcetype = $data['resourceType'];
        $mod->url =  $this->endPoint() . $data['resourceType'];
        $mod->method = 'POST';
        $mod->id = isset($response->id) ? $response->id : null;
        $mod->body = json_encode($data);
        $mod->response =  json_encode($response);
        $mod->date = date('Y-m-d H:i:s');
        $mod->save();
        if (isset($response->id)) {
            Ruangan::where('id', $q->id)->update([
                'ihs_id' => $response->id
            ]);
        }
        return response()->json($response);
    }
    public function Encounter(Request $request, $lokal = null)
    {
        try {

            $kdProfile = $this->kdProfile;
            $q =  collect(DB::select(" 
        SELECT 
        ps.ihs_number
        ,ps.noidentitas
        ,ps.namapasien
        ,case when ru.objectdepartemenfk=16 then  'IMP' else 'AMB'  end as code_class
        ,case when ru.objectdepartemenfk=16 then  'inpatient encounter' else 'ambulatory'  end as name_class
        ,pg.ihs_id as ihs_practitioner
        ,pg.namalengkap as dokter
        ,pg.noidentitas as nik_dokter
        ,ru.namaruangan
        ,ru.ihs_id as ihs_location
        ,pd.tglregistrasi
        ,pd.tglpulang
        ,pd.noregistrasi
        ,pd.ihs_id as ihs_encounter
        ,pro.ihs_id as ihs_service_provider
        ,pd.ihs_condition
        ,pd.ihs_in_progress
        ,pd.ihs_finished
         FROM pasiendaftar_t as pd
        join pasien_m as ps on ps.id=pd.nocmfk
        join ruangan_m as ru on ru .id=pd.objectruanganlastfk
        left join pegawai_m as pg on pg .id=pd.objectpegawaifk
        join profile_m as pro on pro.id=pd.kdprofile
        where pd.noregistrasi='$request[noregistrasi]'
        "))->first();

            $diagnosis = [];
            if ($q->ihs_condition != null && $request['diagnosis']) {
                $diagnosis = $request['diagnosis'];
            }
            if ($q->ihs_number == null) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = 'Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $q->noidentitas;
                $objetoRequest['method'] = 'GET';
                $objetoRequest['data'] = null;

                $response2 =  $this->ihsTools($objetoRequest, true);
                if ($response2->resourceType != 'OperationOutcome' && $response2->resourceType == 'Bundle'  &&  $response2->total != 0) {
                    $id_IHS = null;
                    foreach ($response2->entry  as $dd) {
                        $id_IHS = $dd->resource->id;
                    }
                    if ($id_IHS != null) {
                        Pasien::where('noidentitas', $q->noidentitas)->update([
                            'ihs_number' => $id_IHS
                        ]);
                    }
                } else {
                    if ($lokal == true) {
                        return $response2;
                    }
                    return response()->json($response2);
                }
                $IHS_NUMBER_PASIEN = $id_IHS;
            } else {
                $IHS_NUMBER_PASIEN = $q->ihs_number;
            }
            $IHS_NAMA_DOKTER =  $q->dokter;
            if ($q->ihs_practitioner == null && $q->nik_dokter != null) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = 'Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $q->nik_dokter;
                $objetoRequest['method'] = 'GET';
                $objetoRequest['data'] = null;
                $id_IHSDOK = null;
                $response2 =  $this->ihsTools($objetoRequest, true);
                if ($response2->resourceType != 'OperationOutcome' && $response2->resourceType == 'Bundle'  &&  $response2->total != 0) {

                    foreach ($response2->entry  as $dd) {
                        $id_IHSDOK = $dd->resource->id;
                    }
                    if ($id_IHSDOK != null) {
                        Pegawai::where('noidentitas', $q->nik_dokter)->update([
                            'ihs_id' => $id_IHSDOK
                        ]);
                    }
                }
                $IHS_NAMA_DOKTER =  $q->dokter;
                $IHS_NUMBER_DOKTER = $id_IHSDOK;
            } else {
                $IHS_NAMA_DOKTER = $q->dokter;
                $IHS_NUMBER_DOKTER = $q->ihs_practitioner;
            }

            $tglregistrasi =    strtotime(date($q->tglregistrasi));
            $tglregistrasi =  substr(date("Y-m-d\TH:i:s", $tglregistrasi), 0, 23) . date('P', $tglregistrasi);
            $ihs_in_progress = null;
            $ihs_finished = null;
            if ($q->ihs_in_progress != null) {
                $ihs_in_progress = strtotime(date($q->ihs_in_progress));
                $ihs_in_progress = substr(date("Y-m-d\TH:i:s", $ihs_in_progress), 0, 23) . date('P', $ihs_in_progress);
            }
            if ($q->ihs_finished != null) {
                $ihs_finished = strtotime(date($q->ihs_finished));
                $ihs_finished = substr(date("Y-m-d\TH:i:s", $ihs_finished), 0, 23) . date('P', $ihs_finished);
            }


            if ($q->code_class == 'AMB') {
                $status =  'arrived';

                $tglpulang =    strtotime(date($q->tglpulang));
                $tglpulang =  substr(date("Y-m-d\TH:i:s", $tglpulang), 0, 23) . date('P', $tglpulang);
                $statusHis =   array(
                    0 =>
                    array(
                        'status' => 'arrived',
                        'period' =>
                        array(
                            'start' => $tglregistrasi,
                            'end' => $ihs_in_progress ? $ihs_in_progress : $tglregistrasi,
                        ),
                    )
                );
                if ($ihs_in_progress != null) {
                    $status =  'in-progress';
                    $statusHis =   array(
                        0 =>
                        array(
                            'status' => 'arrived',
                            'period' =>
                            array(
                                'start' => $tglregistrasi,
                                'end' => $ihs_in_progress,
                            ),
                        ),
                        1 =>
                        array(
                            'status' => 'in-progress',
                            'period' =>
                            array(
                                'start' => $ihs_in_progress,
                                'end' =>  $ihs_finished ? $ihs_finished :  $ihs_in_progress,
                            ),
                        ),
                    );
                }
                if ($ihs_finished != null) {
                    $status =  'finished';
                    $statusHis =   array(
                        0 =>
                        array(
                            'status' => 'arrived',
                            'period' =>
                            array(
                                'start' => $tglregistrasi,
                                'end' => $ihs_in_progress,
                            ),
                        ),
                        1 =>
                        array(
                            'status' => 'in-progress',
                            'period' =>
                            array(
                                'start' => $ihs_in_progress,
                                'end' =>  $ihs_finished,
                            ),
                        ),
                        2 =>
                        array(
                            'status' => 'finished',
                            'period' =>
                            array(
                                'start' => $ihs_finished,
                                'end' => $ihs_finished
                            ),
                        ),
                    );
                }
            } else {
                if ($q->tglpulang == null) {
                    $status = 'arrived';

                    $statusHis =   array(
                        0 =>
                        array(
                            'status' => 'arrived',
                            'period' =>
                            array(
                                'start' => $tglregistrasi,
                                'end' => $ihs_in_progress ? $ihs_in_progress : $tglregistrasi,
                            ),
                        ),

                    );
                    if ($ihs_in_progress != null) {
                        $status =  'in-progress';
                        $statusHis =   array(
                            0 =>
                            array(
                                'status' => 'arrived',
                                'period' =>
                                array(
                                    'start' => $tglregistrasi,
                                    'end' => $ihs_in_progress,
                                ),
                            ),
                            1 =>
                            array(
                                'status' => 'in-progress',
                                'period' =>
                                array(
                                    'start' => $ihs_in_progress,
                                    'end' =>  $ihs_finished ? $ihs_finished :  $ihs_in_progress,
                                ),
                            ),
                        );
                    }
                } else {
                    $status =   'finished';
                    $tglpulang =    strtotime(date($q->tglpulang));
                    $tglpulang =  substr(date("Y-m-d\TH:i:s", $tglpulang), 0, 23) . date('P', $tglpulang);
                    if ($ihs_in_progress != null) {
                        $status =  'in-progress';
                        $statusHis =   array(
                            0 =>
                            array(
                                'status' => 'arrived',
                                'period' =>
                                array(
                                    'start' => $tglregistrasi,
                                    'end' => $ihs_in_progress,
                                ),
                            ),
                            1 =>
                            array(
                                'status' => 'in-progress',
                                'period' =>
                                array(
                                    'start' => $ihs_in_progress,
                                    'end' =>  $ihs_finished ? $ihs_finished :  $ihs_in_progress,
                                ),
                            ),
                        );
                    }
                    if ($ihs_finished != null) {
                        $status =  'finished';
                        $statusHis =   array(
                            0 =>
                            array(
                                'status' => 'arrived',
                                'period' =>
                                array(
                                    'start' => $tglregistrasi,
                                    'end' => $ihs_in_progress,
                                ),
                            ),
                            1 =>
                            array(
                                'status' => 'in-progress',
                                'period' =>
                                array(
                                    'start' => $ihs_in_progress,
                                    'end' =>  $ihs_finished,
                                ),
                            ),
                            2 =>
                            array(
                                'status' => 'finished',
                                'period' =>
                                array(
                                    'start' => $ihs_finished,
                                    'end' => $ihs_finished
                                ),
                            ),
                        );
                    }
                }
            }

            $data = array(
                'id' => $q->ihs_encounter,
                'resourceType' => 'Encounter',
                'identifier' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://sys-ids.kemkes.go.id/encounter/' . $q->ihs_service_provider,
                        'value' => $q->noregistrasi,
                    )
                ),
                'status' => $status,
                'class' =>
                array(
                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                    'code' =>  $q->code_class,
                    'display' =>  $q->name_class,
                ),
                'subject' =>
                array(
                    'reference' => 'Patient/' . $IHS_NUMBER_PASIEN,
                    'display' => $q->namapasien,
                ),
                'participant' =>
                array(
                    0 =>
                    array(
                        'type' =>
                        array(
                            0 =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                                        'code' => 'ATND',
                                        'display' => 'attender',
                                    ),
                                ),
                            ),
                        ),
                        'individual' =>
                        array(
                            'reference' => 'Practitioner/' . $IHS_NUMBER_DOKTER,
                            'display' => $IHS_NAMA_DOKTER,
                        ),
                    ),
                ),
                'period' =>
                array(
                    'start' => $tglregistrasi,
                    'end' => $ihs_finished,
                ),
                'location' =>
                array(
                    0 =>
                    array(
                        'location' =>
                        array(
                            'reference' => 'Location/' . $q->ihs_location,
                            'display' => $q->namaruangan,
                        ),
                    ),
                ),
                'diagnosis' => $diagnosis,
                'serviceProvider' =>  array(
                    "reference" => "Organization/" . $q->ihs_service_provider
                ),
                'statusHistory' =>
                $statusHis
            );

            if ($IHS_NUMBER_DOKTER == null) {
                unset($data['participant']);
            }
            if ($q->ihs_encounter == null) {
                unset($data['id']);
            }
            if ($q->ihs_condition == null) {
                unset($data['diagnosis']);
            }
            if ($ihs_finished == null) {
                unset($data['period']['end']);
            }



            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = $q->ihs_encounter == null ? $data['resourceType'] : $data['resourceType'] . '/' . $q->ihs_encounter;
            $objetoRequest['method'] =  $q->ihs_encounter == null ? 'POST' : 'PUT';
            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);
        
            $noterror = true;
            if ($response->resourceType == 'OperationOutcome') {
                $noterror = false;
            }

            if ($q->ihs_encounter == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            } else {
                $mod = IHS_Transaction::where('id', $q->ihs_encounter)->first();
            }
            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method = 'POST';
            $mod->id = isset($response->id) ? $response->id : null;
            $mod->body = json_encode($data);
            $mod->response =  json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
            if (isset($response->id)) {
                PasienDaftar::where('noregistrasi', $q->noregistrasi)->update([
                    'ihs_id' => $response->id
                ]);
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Condition(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q =  collect(DB::select(" 
        SELECT 
        ps.ihs_number
        ,ps.namapasien
        ,pd.noregistrasi
        ,pd.ihs_id as ihs_encounter
        ,pd.ihs_condition as conditionhead
        ,dg.kddiagnosa
        ,dg.namadiagnosa
        ,pd.noregistrasi
        ,ddp.ihs_id as ihs_condition
        ,ddp.norec
         FROM pasiendaftar_t as pd
        join pasien_m as ps on ps.id=pd.nocmfk
        join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
        JOIN detaildiagnosapasien_t as ddp on ddp.noregistrasifk=apd.norec
        join diagnosa_m as dg on dg.id=ddp.objectdiagnosafk 
        where pd.noregistrasi='$request[noregistrasi]'
        "));
            if (count($q) == 0) {
                $response = array(
                    "issue" => 'Diagnosis belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            // if ($q[0]->conditionhead == null) {


            if ($q[0]->ihs_number == null) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = 'Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $q[0]->noidentitas;
                $objetoRequest['method'] = 'GET';
                $objetoRequest['data'] = null;

                $response2 =  $this->ihsTools($objetoRequest, true);
                if ($response2->resourceType != 'OperationOutcome' && $response2->resourceType == 'Bundle' &&  $response2->total != 0) {
                    $id_IHS = null;
                    foreach ($response2->entry  as $dd) {

                        $id_IHS = $dd->resource->id;
                    }
                    if ($id_IHS != null) {
                        Pasien::where('noidentitas', $q[0]->noidentitas)->update([
                            'ihs_number' => $id_IHS
                        ]);
                    }
                }
                $IHS_NUMBER_PASIEN = $id_IHS;
            } else {
                $IHS_NUMBER_PASIEN = $q[0]->ihs_number;
            }
            $diagnosisArr = [];
            foreach ($q as $k =>  $item) {
                // $diagnosis[] = ;
                $data = array(
                    'id' => $item->ihs_condition,
                    'resourceType' => 'Condition',
                    'clinicalStatus' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                                'code' => 'active',
                                'display' => 'Active',
                            ),
                        ),
                    ),
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/condition-category',
                                    'code' => 'encounter-diagnosis',
                                    'display' => 'Encounter Diagnosis',
                                ),
                            ),
                        ),
                    ),
                    'code' =>
                    array(
                        'coding' => [
                            array(
                                'system' => 'http://hl7.org/fhir/sid/icd-10',
                                'code' => $item->kddiagnosa,
                                'display' =>  $item->namadiagnosa,
                            )
                        ]
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $IHS_NUMBER_PASIEN,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                    ),
                );

                if ($item->ihs_condition == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_condition == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);


                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_condition == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_condition)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    PasienDaftar::where('noregistrasi', $item->noregistrasi)->update([
                        'ihs_condition' => $response->id
                    ]);
                    DetailDiagnosaPasien::where('norec', $item->norec)->update([
                        'ihs_id' => $response->id
                    ]);

                    $diagnosisArr[] = array(
                        'condition' =>
                        array(
                            'reference' => 'Condition/' . $mod->id,
                            'display' =>  $item->namadiagnosa,
                        ),
                        'use' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/condition-category',
                                    'code' => 'encounter-diagnosis',
                                    'display' => 'Discharge diagnosis',
                                ),
                            ),
                        ),
                        'rank' => $k + 1,
                    );
                }
            }

            if (count($diagnosisArr) > 0) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $q[0]->noregistrasi;
                $objetoRequest['diagnosis'] = $diagnosisArr;
                PasienDaftar::where('noregistrasi', $q[0]->noregistrasi)->update([
                    'ihs_diagnosis' => json_encode($diagnosisArr)
                ]);
                $ihs = app('App\Http\Controllers\Bridging\IHSController')->Encounter($objetoRequest, true);
            }
            // }else{
            //     $response = array(
            //         "issue" => $e->getMessage() . ' ' . $e->getLine(),
            //         "resourceType" => "OperationOutcome"
            //     );
            // }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Encountertes(Request $request)
    {
        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['noregistrasi'] = $request['noregistrasi'];
        $ihs = app('App\Http\Controllers\Bridging\IHSController')->Encounter($objetoRequest);
        return $ihs;
    }
    public function getList(Request $request)
    {
        $data = DB::table('ihs_transaction')
            ->select('*')
            ->where('resourcetype', '=', $request['resourcetype']);
        if (isset($request['dari']) && $request['dari'] != '') {
            $data = $data->where('date', '>=', $request['dari'] . ' 00:00');
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data = $data->where('date', '<=', $request['sampai'] . ' 23:59');
        }
        $data = $data->orderby('date', 'desc');
        $data = $data->get();
        $data2 = [];
        foreach ($data as $d) {
            // $d->body = json_decode( $d->body);
            // $d->response = json_decode( $d->response);
            if ($d->resourcetype == 'Bundle') {
                $daaa = array(
                    'request' => json_decode($d->body),
                    'response' => json_decode($d->response),
                );
                $data2[] =  $daaa;
            } else {
                $daaa = json_decode($d->response);
            }

            if (isset($daaa->resourceType) && $daaa->resourceType != 'OperationOutcome') {
                $data2[] =  $daaa;
            }
        }
        return response()->json($data2);
    }
    public function Practitioner(Request $request)
    {

        $pegawai =  Pegawai::where('id', $request['id_nakes'])->first();
        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['url'] = 'Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $pegawai->noidentitas;
        $objetoRequest['method'] = 'GET';
        $objetoRequest['data'] = null;
        $id_IHSDOK = '';
        $response2 =  $this->ihsTools($objetoRequest, true);

        if ($response2->resourceType != 'OperationOutcome' && $response2->resourceType == 'Bundle' &&  $response2->total != 0) {
            foreach ($response2->entry  as $dd) {
                $id_IHSDOK = $dd->resource->id;
            }
        }

        if ($id_IHSDOK != '') {
            Pegawai::where('id', $request['id_nakes'])->update([
                'ihs_id' => $id_IHSDOK
            ]);
        }


        return response()->json($response2);
    }

    public function updateIHSPasien(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {

            $JD = Pasien::where('id', $request['id'])->where('kdprofile', $kdProfile)->update(
                [
                    'ihs_number' => $request['ihs_number']
                ]
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "pasien" => $JD,
                "as" => 'inhuman',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "as" => 'inhuman',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }
    public function Medication(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $data =  collect(DB::select(" 
                SELECT
                kd.*
                ,kd.id as idprodukna
                ,pr.ihs_id AS ihs_profile
                ,pr.namalengkap as namaprofile
                ,mbh.ihs_bahanzat 
                ,mbh.*
                ,bh.nama as bahanzat 
                ,kd.ihs_sediaan
                ,sed.nama as ihs_sediaan_display
                ,kff.namaproduk as kfa_display						       
                FROM
                produk_m AS kd
                LEFT JOIN profile_m AS pr ON pr.ID = kd.kdprofile 
                LEFT JOIN ihs_map_bahanzat AS mbh ON mbh.produkfk = kd.id 
                LEFT JOIN ihs_bahanzat AS bh ON bh.ID = mbh.ihs_bahanzat 
                LEFT JOIN ihs_sediaan AS sed ON sed.ID = kd.ihs_sediaan 
                LEFT JOIN ihs_kode_kf_a AS kff ON kff.ID = kd.ihs_kfa_code 
                WHERE
                kd.kdprofile = $kdProfile
                AND kd.statusenabled = true
                AND kd.ID = $request[id]
          "));
            if (count($data) == 0) {
                return response()->json(null);
            }

            $ingredients = [];
            foreach ($data as $item) {
                $ingredients[] =  array(
                    'itemCodeableConcept' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://sys-ids.kemkes.go.id/kfa',
                                'code' => $item->ihs_bahanzat,
                                'display' =>  $item->bahanzat,
                            ),
                        ),
                    ),
                    'isActive' => true,
                    'strength' =>
                    array(
                        'numerator' =>
                        array(
                            'value' => (float)$item->qtynum,
                            'system' => 'http://unitsofmeasure.org',
                            'code' => $item->numerartorsatuanfk,
                        ),
                        'denominator' =>
                        array(
                            'value' => (float)$item->qtydenom,
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                            'code' => $item->denomsatuanfk,
                        ),
                    ),
                );
            }
            $q = $data[0];
            $SPD =  StokProdukDetail::where('objectprodukfk', $q->idprodukna)
                ->where('qtyproduk', '>', 0)
                ->orderBy('tglkadaluarsa', 'desc')
                ->get();

            $tglkadaluarsa = '';
            $nobatch = '';

            foreach ($SPD as $d) {
                if ($d->nobatch != null) {
                    $nobatch = $d->nobatch;
                }
                if ($d->tglkadaluarsa != null) {
                    $tglkadaluarsa = date('Y-m-d', strtotime($d->tglkadaluarsa));
                }
            }
            $data = array(
                'id' =>  $q->ihs_id,
                'resourceType' => 'Medication',
                'identifier' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://sys-ids.kemkes.go.id/medication/' . $q->ihs_profile,
                        'use' => 'official',
                        'value' => (string)$q->idprodukna,
                    ),
                ),
                'code' =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/kfa',
                            'code' => trim($q->ihs_kfa_code),
                            'display' => trim($q->kfa_display),
                        ),
                    ),
                ),
                'status' => 'active',
                'manufacturer' =>
                array(
                    'reference' => 'Organization/900001' //.$q->ihs_profile, //PT KIMIA FARMA, Tbk
                ),
                'form' =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'https://terminology.kemkes.go.id/CodeSystem/medication-form',
                            'code' => $q->ihs_sediaan,
                            'display' => $q->ihs_sediaan_display,
                        ),
                    ),
                ),
                'ingredient' =>
                $ingredients,
                'batch' =>
                array(
                    'lotNumber' => $nobatch,
                    'expirationDate' => $tglkadaluarsa,
                ),
            );

            if ($q->ihs_id == null) {
                unset($data['id']);
            }

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = $data['resourceType'];
            $objetoRequest['method'] = $q->ihs_id == null ? 'POST' : 'PUT';
            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);

            $noterror = true;
            if ($response->resourceType == 'OperationOutcome') {
                $noterror = false;
            }

            if ($q->ihs_id == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            } else {
                $mod = IHS_Transaction::where('id', $q->ihs_id)->first();
            }
            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method =  $q->ihs_id == null ? 'POST' : 'PUT';
            $mod->id = isset($response->id) ? $response->id : null;
            $mod->body = json_encode($data);
            $mod->response =  json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
            if (isset($response->id)) {
                Produk::where('id', $q->idprodukna)->update([
                    'ihs_id' => $response->id
                ]);
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function MedicationRequest(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q  =  collect(DB::select(" 
                SELECT so.noorder,op.rke, jk.jeniskemasan, pr.namaproduk, ss.satuanstandar
                ,op.aturanpakai, op.jumlah, op.hargasatuan,op.keteranganpakai as keterangan,
                op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa
                ,pr.id as idproduk
                ,op.ihs_id as ihs_medication_request
                ,case when ru.objectdepartemenfk=16 then  'inpatient' else 'outpatient'  end as code_class
                ,case when ru.objectdepartemenfk=16 then  'Inpatient' else 'Outpatient'  end as name_class
                ,ru.namaruangan
                ,pr.ihs_id as ihs_medication
                ,pd.ihs_id as ihs_encounter
                ,ps.ihs_number as ihs_patient
                ,ps.namapasien
                ,so.tglorder
                ,case when pg.ihs_id is null then pg2.ihs_id else pg.ihs_id end as ihs_practitioner
                ,case when pg.ihs_id is null then pg2.namalengkap else pg.namalengkap end as pegawaiorder
                ,pr.ihs_denominator_code
                ,pr.ihs_kfa_display
                ,op.norec as norec_op
                ,op.ihs_noorder
                from strukorder_t as so 
                inner join orderpelayanan_t as op on op.strukorderfk = so.norec
                inner join produk_m as pr on pr.id=op.objectprodukfk
                inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
                inner join pasien_m as ps on ps.id=pd.nocmfk
                left join jeniskemasan_m as jk on jk.id=op.jeniskemasanfk
                left join satuanstandar_m as ss on ss.id=op.objectsatuanstandarfk
                left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
                left join pegawai_m as pg2 on pg2.id=pd.objectpegawaifk
                left join satuanresep_m as sn on sn.id=op.satuanresepfk
                inner join ruangan_m as ru on ru.id=so.objectruanganfk
         
                where so.noorder='$request[noorder]'
                and so.statusenabled=true
                and so.kdprofile=$kdProfile
                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'MedicationRequest belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $IDpRODUK = [];
            foreach ($q as $k =>  $item) {
                $IDpRODUK[] = $item->idproduk;
            }

            $ingredientsS = DB::table('ihs_map_bahanzat as mm1')
                ->leftJoin('ihs_numerator_satuan as mm', 'mm.id', '=', 'mm1.numerartorsatuanfk')
                ->leftJoin('ihs_denom_satuan as mm3', 'mm3.id', '=', 'mm1.denomsatuanfk')
                ->leftJoin('ihs_bahanzat as bh', 'bh.id', '=', 'mm1.ihs_bahanzat')
                ->select(
                    'mm1.*',
                    'mm.nama as numerator',
                    'mm3.nama as denominator',
                    'mm1.qtynum as denominatorvalue',
                    'mm1.qtydenom as numeratorvalue',
                    'mm.id as numeratorfk',
                    'mm3.id as denominatorfk',
                    'mm1.aktif as isactive',
                    'mm1.ihs_bahanzat as komposisizatfk',
                    'bh.nama as komposisizat'
                )
                ->whereiN('mm1.produkfk', $IDpRODUK)
                ->get();
            $profile = Profile::where('statusenabled', true)->first();
            foreach ($q as $k =>  $item) {
                if ($item->ihs_medication == null) {
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['id'] = $item->idproduk;
                    $ihs = app('App\Http\Controllers\Bridging\IHSController')->Medication($objetoRequest, true);
                    if ($ihs->resourceType != 'OperationOutcome') {
                        Produk::where('id', $item->idproduk)->update([
                            'ihs_id' => $ihs->id
                        ]);
                        $item->ihs_medication = $ihs->id;
                    }
                }
                $DoseQtyunitUnit = $item->satuanstandar;
                $DoseQtyunitCode = $item->satuanstandar;
                foreach ($ingredientsS as $ing) {
                    if ($ing->produkfk == $item->idproduk) {
                        if ($ing->denominator != null) {
                            $DoseQtyunitUnit = $ing->denominator;
                            $DoseQtyunitCode = $ing->denominatorfk;
                        }
                    }
                }

                $repeat_period = 1;
                $repeat_frequency = 1;
                $arr = explode('x', $item->aturanpakai);
                if (count($arr) == 2) {
                    $repeat_frequency = (float)$arr[0];
                    $repeat_period = (float) $arr[1];
                }
                $data = array(
                    'id' => $item->ihs_medication_request,
                    'resourceType' => 'MedicationRequest',
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/prescription/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' => $item->noorder,
                        ),
                        1 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/prescription-item/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' =>  $item->noorder . '-' . ($k + 1),
                        ),
                    ),
                    'status' => 'completed',
                    'statusReason' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-status-reason',
                                'code' => 'clarif',
                                'display' => 'Prescription requires clarification',
                            ),
                        ),
                    ),
                    'intent' => 'order',
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-category',
                                    'code' => $item->code_class,
                                    'display' => $item->name_class,
                                ),
                            ),
                        ),
                    ),
                    'priority' => 'routine',
                    'medicationReference' =>
                    array(
                        'reference' => 'Medication/' . $item->ihs_medication,
                        'display' => $item->ihs_kfa_display != null ? $item->ihs_kfa_display : $item->namaproduk,
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_patient,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                    ),
                    'authoredOn' => date('Y-m-d', strtotime($item->tglorder)),
                    'requester' =>
                    array(
                        'reference' => 'Practitioner/' . $item->ihs_practitioner,
                        'display' => $item->pegawaiorder,
                    ),
                    'performerType' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://snomed.info/sct',
                                'code' => '309343006',
                                'display' => 'Physician',
                            ),
                        ),
                    ),
                    // 'reasonCode' =>
                    // array(
                    //     0 =>
                    //     array(
                    //         'coding' =>
                    //         array(
                    //             0 =>
                    //             array(
                    //                 'system' => 'http://hl7.org/fhir/sid/icd-10',
                    //                 'code' => 'A15.0',
                    //                 'display' => 'Tuberculosis of lung, confirmed by sputum microscopy with or without culture',
                    //             ),
                    //         ),
                    //     ),
                    // ),
                    'courseOfTherapyType' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy',
                                'code' => 'seasonal', // 'continuous',
                                'display' => 'Seasonal', // 'Continuing long term therapy',
                            ),
                        ),
                    ),
                    'dosageInstruction' =>
                    array(
                        0 =>
                        array(
                            'sequence' => 1,
                            'text' => $item->aturanpakai,
                            'additionalInstruction' =>
                            array(
                                0 =>
                                array(
                                    'text' =>  $item->aturanpakai,
                                ),
                            ),
                            'patientInstruction' =>  $item->keterangan,
                            'timing' =>
                            array(
                                'repeat' =>
                                array(
                                    'frequency' => $repeat_frequency,
                                    'period' => $repeat_period,
                                    'periodUnit' => 'd',
                                ),
                            ),
                            'route' =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://www.whocc.no/atc',
                                        'code' => 'O',
                                        'display' => 'Oral',
                                    ),
                                ),
                            ),
                            'doseAndRate' =>
                            array(
                                0 =>
                                array(
                                    'type' =>
                                    array(
                                        'coding' =>
                                        array(
                                            0 =>
                                            array(
                                                'system' => 'http://terminology.hl7.org/CodeSystem/dose-rate-type',
                                                'code' => 'ordered',
                                                'display' => 'Ordered',
                                            ),
                                        ),
                                    ),

                                    'doseQuantity' =>
                                    array(
                                        'value' => (float) $item->jumlah,
                                        'unit' => $DoseQtyunitUnit,
                                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                        'code' => $DoseQtyunitCode,
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'dispenseRequest' =>
                    array(
                        // 'dispenseInterval' =>
                        // array(
                        //     'value' => 1,
                        //     'unit' => 'days',
                        //     'system' => 'http://unitsofmeasure.org',
                        //     'code' => 'd',
                        // ),
                        // 'validityPeriod' =>
                        // array(
                        //     'start' => '2022-01-01',
                        //     'end' => '2022-01-30',
                        // ),
                        'numberOfRepeatsAllowed' => 0,
                        'quantity' =>
                        array(
                            'value' => (float) $item->jumlah,
                            'unit' => $DoseQtyunitUnit,
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                            'code' => $DoseQtyunitCode,
                        ),
                        // 'expectedSupplyDuration' =>
                        // array(
                        //     'value' => 30,
                        //     'unit' => 'days',
                        //     'system' => 'http://unitsofmeasure.org',
                        //     'code' => 'd',
                        // ),
                    ),
                );


                if ($item->ihs_medication_request == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_medication_request == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);


                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_medication_request == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_medication_request)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    OrderPelayanan::where('norec', $item->norec_op)->update([
                        'ihs_id' => $response->id,
                        'ihs_noorder' => $data['identifier'][1]['value']
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function MedicationDispense(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q  =  collect(DB::select(" 

                 SELECT
                sr.noresep,
                pp.hargasatuan,
                pp.stock,
                apd.objectruanganfk,
                ru.namaruangan,
                pp.rke,
                pp.jeniskemasanfk,
                jk.id AS jkid,
                jk.jeniskemasan,
                pp.aturanpakai,
                pp.routefk,
                rt.name AS namaroute,
                pp.produkfk,
                pp.produkfk as idproduk,
                pr.namaproduk,
                pp.nilaikonversi,
                pr.objectsatuanstandarfk,
                ss.satuanstandar,
                pp.satuanviewfk,
                ss2.satuanstandar AS ssview,
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
                sn.satuanresep,
                pp.tglkadaluarsa 
                ,pp.ihs_id as ihs_medicationdispense
                ,case when ru.objectdepartemenfk=16 then  'inpatient' else 'outpatient'  end as code_class
                ,case when ru.objectdepartemenfk=16 then  'Inpatient' else 'Outpatient'  end as name_class
                ,ru.namaruangan
                ,pr.ihs_id as ihs_medication
                ,pd.ihs_id as ihs_encounter
                ,ps.ihs_number as ihs_patient
                ,ps.namapasien
                ,case when pg.ihs_id is null then pg2.ihs_id else pg.ihs_id end as ihs_practitioner
                ,case when pg.ihs_id is null then pg2.namalengkap else pg.namalengkap end as penulisresep
                ,ru2.ihs_id as ihs_apotik
                ,ru2.namaruangan as ihs_apotik_display
                ,op.ihs_id as ihs_medication_request
                ,pr.ihs_denominator_code
                ,sr.tglresep
                ,pr.ihs_kfa_display
                ,pp.norec as norec_pp
            FROM
                strukresep_t AS sr
                INNER JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
                INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                INNER JOIN ruangan_m AS ru2 ON ru2.id = sr.ruanganfk
                INNER JOIN jeniskemasan_m AS jk ON jk.id = pp.jeniskemasanfk
                LEFT JOIN routefarmasi AS rt ON rt.id = pp.routefk
                INNER JOIN produk_m AS pr ON pr.id = pp.produkfk
                INNER JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
                INNER JOIN satuanstandar_m AS ss2 ON ss2.id = pp.satuanviewfk
                LEFT JOIN satuanresep_m AS sn ON sn.id = pp.satuanresepfk 
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                inner join pasien_m as ps on ps.id=pd.nocmfk
                left join pegawai_m as pg on pg.id=sr.penulisresepfk
                left join pegawai_m as pg2 on pg2.id=pd.objectpegawaifk
                left join orderpelayanan_t as op on sr.orderfk = op.strukorderfk and op.objectprodukfk = pr.id
            WHERE
                sr.kdprofile = $kdProfile 
                AND sr.noresep = '$request[noresep]'
                and sr.statusenabled=true
                
                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'MedicationDispense belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $IDpRODUK = [];
            foreach ($q as $k =>  $item) {
                $IDpRODUK[] = $item->idproduk;
            }

            $ingredientsS = DB::table('ihs_map_bahanzat as mm1')
                ->leftJoin('ihs_numerator_satuan as mm', 'mm.id', '=', 'mm1.numerartorsatuanfk')
                ->leftJoin('ihs_denom_satuan as mm3', 'mm3.id', '=', 'mm1.denomsatuanfk')
                ->leftJoin('ihs_bahanzat as bh', 'bh.id', '=', 'mm1.ihs_bahanzat')
                ->select(
                    'mm1.*',
                    'mm.nama as numerator',
                    'mm3.nama as denominator',
                    'mm1.qtynum as denominatorvalue',
                    'mm1.qtydenom as numeratorvalue',
                    'mm.id as numeratorfk',
                    'mm3.id as denominatorfk',
                    'mm1.aktif as isactive',
                    'mm1.ihs_bahanzat as komposisizatfk',
                    'bh.nama as komposisizat'
                )
                ->whereiN('mm1.produkfk', $IDpRODUK)
                ->get();

            $profile = Profile::where('statusenabled', true)->first();
            foreach ($q as $k =>  $item) {
                $tglresep =    strtotime(date($item->tglresep));
                $tglresep =  substr(date("Y-m-d\TH:i:s", $tglresep), 0, 23) . date('P', $tglresep);
                $repeat_period = 1;
                $repeat_frequency = 1;
                $arr = explode('x', $item->aturanpakai);
                if (count($arr) == 2) {
                    $repeat_frequency = (float)$arr[0];
                    $repeat_period = (float) $arr[1];
                }
                $DoseQtyunitUnit = $item->satuanstandar;
                $DoseQtyunitCode = $item->satuanstandar;
                foreach ($ingredientsS as $ing) {
                    if ($ing->produkfk == $item->idproduk) {
                        if ($ing->denominator != null) {
                            $DoseQtyunitUnit = $ing->denominator;
                            $DoseQtyunitCode = $ing->denominatorfk;
                        }
                    }
                }
                $data = array(
                    'id' => $item->ihs_medicationdispense,
                    'resourceType' => 'MedicationDispense',
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/prescription/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' =>   $item->noresep,
                        ),
                        1 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/prescription-item/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' => $item->noresep . '-' . ($k + 1),
                        ),
                    ),
                    'status' => 'completed',
                    'category' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category',
                                'code' => $item->code_class,
                                'display' => $item->name_class,
                            ),
                        ),
                    ),
                    'medicationReference' =>
                    array(
                        'reference' => 'Medication/' . $item->ihs_medication,
                        'display' => $item->ihs_kfa_display != null ? $item->ihs_kfa_display : $item->namaproduk,
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_patient,
                        'display' => $item->namapasien,
                    ),
                    'context' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                    ),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'actor' =>
                            array(
                                'reference' => 'Practitioner/' . $item->ihs_practitioner,
                                'display' => $item->penulisresep,
                            ),
                        ),
                    ),
                    'location' =>
                    array(
                        'reference' => 'Location/' . $item->ihs_apotik,
                        'display' =>  $item->ihs_apotik_display,
                    ),
                    'authorizingPrescription' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'MedicationRequest/' . $item->ihs_medication_request,
                        ),
                    ),
                    'quantity' =>
                    array(
                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                        'code' => $DoseQtyunitCode,
                        'value' => (float)$item->jumlah,
                    ),
                    'daysSupply' =>
                    array(
                        'value' => 1,
                        'unit' => 'Day',
                        'system' => 'http://unitsofmeasure.org',
                        'code' => 'd',
                    ),
                    'whenPrepared' => $tglresep,
                    'whenHandedOver' => $tglresep,
                    'dosageInstruction' =>
                    array(
                        0 =>
                        array(
                            'sequence' => 1,
                            'text' => $item->aturanpakai,
                            'timing' =>
                            array(
                                'repeat' =>
                                array(
                                    'frequency' => $repeat_frequency,
                                    'period' => $repeat_period,
                                    'periodUnit' => 'd',
                                ),
                            ),
                            'doseAndRate' =>
                            array(
                                0 =>
                                array(
                                    'type' =>
                                    array(
                                        'coding' =>
                                        array(
                                            0 =>
                                            array(
                                                'system' => 'http://terminology.hl7.org/CodeSystem/dose-rate-type',
                                                'code' => 'ordered',
                                                'display' => 'Ordered',
                                            ),
                                        ),
                                    ),
                                    'doseQuantity' =>
                                    array(
                                        'value' => (float) $item->jumlah,
                                        'unit' => $DoseQtyunitUnit,
                                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                        'code' =>  $DoseQtyunitCode,
                                    ),
                                ),
                            ),
                        ),
                    ),
                );




                if ($item->ihs_medicationdispense == null) {
                    unset($data['id']);
                }
                if ($item->ihs_medication_request == null) {
                    unset($data['authorizingPrescription']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_medicationdispense == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);
                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_medicationdispense == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_medicationdispense)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    PelayananPasien::where('norec', $item->norec_pp)->update([
                        'ihs_id' => $response->id,
                        'ihs_noresep' => $data['identifier'][1]['value']
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function MedicationDispenseObatBebas(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q  =  collect(DB::select(" 

            SELECT
                sp.tglstruk as tglresep
                ,sp.nostruk as noresep
                ,spd.aturanpakai
                ,sp.nostruk_intern
                ,sp.namapasien_klien
                ,spd.qtyproduk  as jumlah
                ,( ( spd.hargasatuan - spd.hargadiscount ) * spd.qtyproduk ) + spd.hargatambahan AS total,
                spd.tglkadaluarsa 
                ,spd.ihs_id as ihs_medicationdispense
                ,pr.id as idproduk
                ,pr.namaproduk
                ,ss.satuanstandar
                ,case when ru.objectdepartemenfk=16 then  'inpatient' else 'outpatient'  end as code_class
                ,case when ru.objectdepartemenfk=16 then  'Inpatient' else 'Outpatient'  end as name_class
                ,pr.ihs_id as ihs_medication
                ,pg.ihs_id  as ihs_practitioner
                ,pg.namalengkap  as penulisresep
                ,ru.ihs_id as ihs_apotik
                ,ru.namaruangan as ihs_apotik_display
                ,spd.norec as norec_pp
                ,pr.ihs_kfa_code
                ,ko.namaproduk as  ihs_kfa_display
                ,pr.ihs_sediaan
                ,sed.nama as ihs_sediaan_display
                ,spd.urn_uuid_medication
                ,sp.ihs_bundle
                ,sp.norec as norec_sp
            FROM
                strukpelayanan_t AS sp
                INNER join strukpelayanandetail_t as spd on spd.nostrukfk=sp.norec
                LEFT JOIN pegawai_m AS pg ON pg.ID = sp.objectpegawaipenanggungjawabfk
                LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
                LEFT JOIN produk_m AS pr ON pr.ID = spd.objectprodukfk
                LEFT JOIN jeniskemasan_m AS jkm ON jkm.ID = spd.objectjeniskemasanfk
                LEFT JOIN satuanstandar_m AS ss ON ss.ID = spd.objectsatuanstandarfk 
                left join ihs_kode_kf_a as ko on ko.id=pr.ihs_kfa_code
                LEFT JOIN ihs_sediaan AS sed ON sed.ID = pr.ihs_sediaan 
                WHERE
                sp.kdprofile = $kdProfile 
                and sp.nostruk = '$request[noresep]'
                AND sp.statusenabled = true
            ORDER BY
                sp.nostruk ASC
                
                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'MedicationDispense belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }

            $IDpRODUK = [];
            foreach ($q as $k =>  $item) {
                $IDpRODUK[] = $item->idproduk;
            }
            $SPD =  StokProdukDetail::whereIn('objectprodukfk', $IDpRODUK)
                ->where('qtyproduk', '>', 0)
                ->orderBy('tglkadaluarsa', 'desc')
                ->get();


            $ingredientsS = DB::table('ihs_map_bahanzat as mm1')
                ->leftJoin('ihs_numerator_satuan as mm', 'mm.id', '=', 'mm1.numerartorsatuanfk')
                ->leftJoin('ihs_denom_satuan as mm3', 'mm3.id', '=', 'mm1.denomsatuanfk')
                ->leftJoin('ihs_bahanzat as bh', 'bh.id', '=', 'mm1.ihs_bahanzat')
                ->select(
                    'mm1.*',
                    'mm.nama as numerator',
                    'mm3.nama as denominator',
                    'mm1.qtynum as denominatorvalue',
                    'mm1.qtydenom as numeratorvalue',
                    'mm.id as numeratorfk',
                    'mm3.id as denominatorfk',
                    'mm1.aktif as isactive',
                    'mm1.ihs_bahanzat as komposisizatfk',
                    'bh.nama as komposisizat'
                )
                ->whereiN('mm1.produkfk', $IDpRODUK)
                ->get();
            $profile = Profile::where('statusenabled', true)->first();
            $entry = [];


            foreach ($q as $k =>  $item) {
                $tglresep =    strtotime(date($item->tglresep));
                $tglresep =  substr(date("Y-m-d\TH:i:s", $tglresep), 0, 23) . date('P', $tglresep);
                $repeat_period = 1;
                $repeat_frequency = 1;
                $arr = explode('x', $item->aturanpakai);
                if (count($arr) == 2) {
                    $repeat_frequency = (float)$arr[0];
                    $repeat_period = (float) $arr[1];
                }
                $medi_DoseQtyunitUnit = $item->satuanstandar;
                $medi_DoseQtyunitCode = $item->satuanstandar;
                $medi_kodeKamusKFAobat = $item->ihs_kfa_code ? $item->ihs_kfa_code : '';
                $medi_namaKamusKFAobat = $item->ihs_kfa_display ? $item->ihs_kfa_display : $item->namaproduk;
                $ingredients = [];
                foreach ($ingredientsS as $ing) {
                    if ($ing->produkfk == $item->idproduk) {
                        if ($ing->denominator != null) {
                            $medi_DoseQtyunitUnit = $ing->denominator;
                            $medi_DoseQtyunitCode = $ing->denominatorfk;
                        }
                        $ingredients[] =  array(
                            'itemCodeableConcept' =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://sys-ids.kemkes.go.id/kfa',
                                        'code' => $ing->komposisizatfk,
                                        'display' => $ing->komposisizat,
                                    ),
                                ),
                            ),
                            'isActive' => $ing->isactive ? $ing->isactive : true,
                            'strength' =>
                            array(
                                'numerator' =>
                                array(
                                    'value' => (float)$ing->numeratorvalue,
                                    'system' => 'http://unitsofmeasure.org',
                                    'code' => $ing->numeratorfk,
                                ),
                                'denominator' =>
                                array(
                                    'value' => (float)$ing->denominatorvalue,
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                    'code' => $ing->denominatorfk,
                                ),
                            ),
                        );
                    }
                }
                $tglkadaluarsa = '';
                $nobatch = '';
                foreach ($SPD as $d) {
                    if ($d->objectprodukfk == $item->idproduk) {
                        if ($d->nobatch != null) {
                            $nobatch = $d->nobatch;
                        }
                        if ($d->tglkadaluarsa != null) {
                            $tglkadaluarsa = date('Y-m-d', strtotime($d->tglkadaluarsa));
                        }
                    }
                }

                $uuidMedication = $item->urn_uuid_medication != null ? $item->urn_uuid_medication : substr(Uuid::generate(), 0, 36);
                $uuidMedicationDispense =   $item->ihs_medicationdispense != null ? $item->ihs_medicationdispense : substr(Uuid::generate(), 0, 36);
                $medication =  array(
                    'fullUrl' => 'urn:uuid:' . $uuidMedication,
                    'resource' =>
                    array(
                        'id' =>  $item->urn_uuid_medication,
                        'resourceType' => 'Medication',
                        'identifier' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://sys-ids.kemkes.go.id/medication/' . $profile->ihs_id,
                                'use' => 'official',
                                'value' => (string)$item->idproduk,
                            ),
                        ),
                        'code' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://sys-ids.kemkes.go.id/kfa',
                                    'code' => $medi_kodeKamusKFAobat,
                                    'display' => $medi_namaKamusKFAobat
                                ),
                            ),
                        ),
                        'status' => 'active',
                        'form' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'https://terminology.kemkes.go.id/CodeSystem/medication-form',
                                    'code' => $item->ihs_sediaan,
                                    'display' => $item->ihs_sediaan_display,
                                ),
                            ),
                        ),
                        'ingredient' =>
                        $ingredients,
                        'batch' =>
                        array(
                            'lotNumber' => $nobatch,
                            'expirationDate' => $tglkadaluarsa,
                        ),
                    ),
                    'request' =>
                    array(
                        'method' =>  $item->urn_uuid_medication != null ? 'PUT' : 'POST',
                        'url' => 'Medication',
                    ),
                );
                $medicationDispense =
                    array(
                        'fullUrl' => 'urn:uuid:' . $uuidMedicationDispense,
                        'resource' =>
                        array(
                            'id' =>  $item->ihs_medicationdispense,
                            'resourceType' => 'MedicationDispense',
                            'identifier' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://sys-ids.kemkes.go.id/medicationdispense/1000004',
                                    'value' =>   $item->noresep,
                                ),
                                1 =>
                                array(
                                    'system' => 'http://sys-ids.kemkes.go.id/medicationdispense-item/1000004',
                                    'value' => $item->noresep . '-' . ($k + 1),
                                ),
                            ),
                            'status' => 'completed',
                            'category' =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category',
                                        'code' => $item->code_class,
                                        'display' => $item->name_class,
                                    ),
                                ),
                            ),
                            'medicationReference' =>
                            array(
                                'reference' => 'urn:uuid:' . $uuidMedication,
                                'display' => $item->ihs_kfa_display != null ? $item->ihs_kfa_display : $item->namaproduk,
                            ),
                            'subject' =>
                            array(
                                'reference' => 'Group/PERSON-UNIDENTIFIED',
                                'display' =>   $item->namapasien_klien, //"PERSON-UNIDENTIFIED"
                            ),
                            'performer' =>
                            array(
                                0 =>
                                array(
                                    'actor' =>
                                    array(
                                        'reference' => 'Practitioner/' . $item->ihs_practitioner,
                                        'display' => $item->penulisresep,
                                    ),
                                ),
                                1 =>
                                array(
                                    'actor' =>
                                    array(
                                        'reference' => 'Organization/' . $profile->ihs_id,
                                    ),
                                ),
                            ),
                            'location' =>
                            array(
                                'reference' => 'Location/' . $item->ihs_apotik,
                                'display' =>  $item->ihs_apotik_display,
                            ),
                            'quantity' =>
                            array(
                                'value' => (float)$item->jumlah,
                                'unit' => $medi_DoseQtyunitUnit,
                                'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                'code' => $medi_DoseQtyunitCode,
                            ),
                            'whenPrepared' => $tglresep,
                            'whenHandedOver' => $tglresep,
                            'dosageInstruction' =>
                            array(
                                0 =>
                                array(
                                    'sequence' => 1,
                                    'text' => $item->aturanpakai,
                                    'timing' =>
                                    array(
                                        'repeat' =>
                                        array(
                                            'frequency' => $repeat_frequency,
                                            'period' => $repeat_period,
                                            'periodUnit' => 'd',
                                        ),
                                    ),
                                    'route' =>
                                    array(
                                        'coding' =>
                                        array(
                                            0 =>
                                            array(
                                                'system' => 'http://www.whocc.no/atc',
                                                'code' => 'O',
                                                'display' => 'Oral',
                                            ),
                                        ),
                                    ),
                                    'doseAndRate' =>
                                    array(
                                        0 =>
                                        array(
                                            'type' =>
                                            array(
                                                'coding' =>
                                                array(
                                                    0 =>
                                                    array(
                                                        'system' => 'http://terminology.hl7.org/CodeSystem/dose-rate-type',
                                                        'code' => 'ordered',
                                                        'display' => 'Ordered',
                                                    ),
                                                ),
                                            ),
                                            'doseQuantity' =>
                                            array(
                                                'value' => (float)$item->jumlah,
                                                'unit' => $medi_DoseQtyunitUnit,
                                                'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                                'code' => $medi_DoseQtyunitCode,
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                        'request' =>
                        array(
                            'method' =>  $item->ihs_medicationdispense != null ? 'PUT' : 'POST',
                            'url' => 'MedicationDispense',
                        ),

                    );

                if ($item->ihs_medicationdispense == null) {
                    unset($medicationDispense['resource']['id']);
                }
                if ($item->urn_uuid_medication == null) {
                    unset($medication['resource']['id']);
                }

                $entry[] = $medication;
                $entry[] = $medicationDispense;
            }
            $data = array(
                'resourceType' => 'Bundle',
                'type' => 'transaction',
                'entry' => $entry
            );
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = '';
            $objetoRequest['method'] = 'POST';
            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);
            // return response()->json($data);
            $noterror = true;
            if ($response->resourceType == 'OperationOutcome') {
                $noterror = false;

                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }

            $responseBundle = '';
            foreach ($response->entry as $ddd) {
                if (isset($ddd->response->resourceID)) {
                    $responseBundle = $responseBundle . ',' . $ddd->response->resourceID;
                    StrukPelayananDetail::where('norec', $item->norec_pp)->update([
                        'ihs_id' => $ddd->response->resourceID,
                    ]);
                }
            }
            $responseBundle =  substr($responseBundle, 1, strlen($responseBundle) - 1);

            if ($q[0]->ihs_bundle == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            } else {
                $mod = IHS_Transaction::where('id', $q[0]->ihs_bundle)->first();
            }
            if (empty($mod)) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            }
            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method = 'POST';
            $mod->id = $responseBundle;
            $mod->body = json_encode($data);
            $mod->response = json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Observation(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
            select x.* from (

                SELECT
                    case when ps.id  is null then ps2.id  else ps.id end as idpasien,
                    case when ps.namapasien  is null then ps2.namapasien  else ps.namapasien end as given,
                    emrp.statusenabled,
                    emrdp.norec  as id_param,
                  
                    emrp.noregistrasifk AS noregistrasi,
                    emrp.tglregistrasi,
                    emrp.noemr,
                    emrp.tglemr,
                    emrd.caption AS namaemr,
                    emrdp.
                VALUE
                    AS nilai,
                    emrp.namaruangan,
                    emrdp.emrdfk ,
                    ps.nocm,ps.noidentitas,
                    emrdp.ihs_id as ihs_observation,
                    ps.ihs_number as ihs_patient,
                    pd.ihs_id as ihs_encounter,
                    pd.tglregistrasi as masuk
                FROM
                    emrpasiend_t AS emrdp
                    INNER JOIN emrpasien_t AS emrp ON emrp.noemr = emrdp.emrpasienfk
                    LEFT JOIN emrd_t AS emrd ON emrd.id = emrdp.emrdfk
                    LEFT JOIN pegawai_m AS pg ON pg.id = emrdp.pegawaifk
                    LEFT JOIN pasiendaftar_t AS pd ON pd.noregistrasi = emrp.noregistrasifk
                    LEFT JOIN pasien_m AS ps ON ps.id = pd.nocmfk 
                    LEFT JOIN pasien_m AS ps2 ON ps2.nocm = emrp.nocm 
                WHERE
                    emrdp.statusenabled = true
                    AND emrdp.emrdfk IN ( 4241, 4242, 4243, 4244, 4245, 4246 ) 
                    AND emrp.kdprofile = $kdProfile 
                ORDER BY
                    emrp.tglemr ASC 

            ) as x where 
            x.statusenabled =true
            and x.noregistrasi ='$request[noregistrasi]'    
          "));
            //   dd($q);

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'Observation belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }

            $profile = Profile::where('statusenabled', true)->first();
            $array = [];


            foreach ($q as $k =>  $item) {

                if ($item->emrdfk == 4243) {
                    $array[] = array(
                        'id' => $item->ihs_observation,
                        'code' => '3141-9',
                        'display' => 'Weight',
                        'patient' => $item->ihs_patient,
                        'patient_name' => $item->given,
                        'encounter' => $item->ihs_encounter,
                        'encounter_display' => 'Pemeriksaan Fisik Tinggi Badan ' . $item->given . ' di hari ' . $this->hari_ini($item->masuk) . ', ' . $this->getDateIndo($item->masuk),
                        'effectiveDateTime' => date('Y-m-d', strtotime($item->tglemr)),
                        'unit' => 'kg',
                        'unitCode' => 'kg',
                        'value' => (float) $item->nilai,
                        'norec' => $item->id_param
                    );
                }
                if ($item->emrdfk == 4242) {
                    $array[] = array(
                        'id' => $item->ihs_observation,
                        'code' => '8302-2',
                        'display' => 'Height',
                        'patient' => $item->ihs_patient,
                        'patient_name' => $item->given,
                        'encounter' => $item->ihs_encounter,
                        'encounter_display' => 'Pemeriksaan Fisik Berat Badan ' . $item->given . ' di hari ' . $this->hari_ini($item->masuk) . ', ' . $this->getDateIndo($item->masuk),
                        'effectiveDateTime' => date('Y-m-d', strtotime($item->tglemr)),
                        'unit' => 'cm',
                        'unitCode' => 'cm',
                        'value' => (float) $item->nilai,
                        'norec' => $item->id_param
                    );
                }
                if ($item->emrdfk == 4246) {
                    $array[] = array(
                        'id' => $item->ihs_observation,
                        'code' => '9279-1',
                        'display' => 'Respiratory rate',
                        'patient' => $item->ihs_patient,
                        'patient_name' => $item->given,
                        'encounter' => $item->ihs_encounter,
                        'encounter_display' => 'Pemeriksaan Fisik Pernafasan ' . $item->given . ' di hari ' . $this->hari_ini($item->masuk) . ', ' . $this->getDateIndo($item->masuk),
                        'effectiveDateTime' => date('Y-m-d', strtotime($item->tglemr)),
                        'unit' => 'breaths/minute',
                        'unitCode' => '/min',
                        'value' => (float) $item->nilai,
                        'norec' => $item->id_param
                    );
                }
                if ($item->emrdfk == 4241) {
                    $sis = explode('/', $item->nilai);
                    if (count($sis) == 2) {
                        $array[] = array(
                            'id' => $item->ihs_observation,
                            'code' => '8480-6',
                            'display' => 'Systolic blood pressure',
                            'patient' => $item->ihs_patient,
                            'patient_name' => $item->given,
                            'encounter' => $item->ihs_encounter,
                            'encounter_display' => 'Pemeriksaan Fisik Nadi ' . $item->given . ' di hari ' . $this->hari_ini($item->masuk) . ', ' . $this->getDateIndo($item->masuk),
                            'effectiveDateTime' => date('Y-m-d', strtotime($item->tglemr)),
                            'unit' => 'mm[Hg]',
                            'unitCode' => 'mm[Hg]',
                            'value' => (float) $sis[0],
                            'norec' => $item->id_param
                        );
                        $array[] = array(
                            'id' => $item->ihs_observation,
                            'code' => '8462-4',
                            'display' => 'Diastolic blood pressure',
                            'patient' => $item->ihs_patient,
                            'patient_name' => $item->given,
                            'encounter' => $item->ihs_encounter,
                            'encounter_display' => 'Pemeriksaan Fisik Nadi ' . $item->given . ' di hari ' . $this->hari_ini($item->masuk) . ', ' . $this->getDateIndo($item->masuk),
                            'effectiveDateTime' => date('Y-m-d', strtotime($item->tglemr)),
                            'unit' => 'mm[Hg]',
                            'unitCode' => 'mm[Hg]',
                            'value' => (float) $sis[1],
                            'norec' => $item->id_param
                        );
                    } else {
                        $array[] = array(
                            'id' => $item->ihs_observation,
                            'code' => '55284-4',
                            'display' => 'Blood pressure',
                            'patient' => $item->ihs_patient,
                            'patient_name' => $item->given,
                            'encounter' => $item->ihs_encounter,
                            'encounter_display' => 'Pemeriksaan Fisik Nadi ' . $item->given . ' di hari ' . $this->hari_ini($item->masuk) . ', ' . $this->getDateIndo($item->masuk),
                            'effectiveDateTime' => date('Y-m-d', strtotime($item->tglemr)),
                            'unit' => 'mm[Hg]',
                            'unitCode' => 'mm[Hg]',
                            'value' => (float) $item->nilai,
                            'norec' => $item->id_param
                        );
                    }
                }
                if ($item->emrdfk == 4244) {
                    $kode = '8310-5';
                    $nama = 'Body temperature';
                    $array[] = array(
                        'id' => $item->ihs_observation,
                        'code' => $kode,
                        'display' => $nama,
                        'patient' => $item->ihs_patient,
                        'patient_name' => $item->given,
                        'encounter' => $item->ihs_encounter,
                        'encounter_display' => 'Pemeriksaan Fisik Suhu ' . $item->given . ' di hari ' . $this->hari_ini($item->masuk) . ', ' . $this->getDateIndo($item->masuk),
                        'effectiveDateTime' => date('Y-m-d', strtotime($item->tglemr)),
                        'unit' => 'C',
                        'unitCode' => 'Cel',
                        'value' => (float) $item->nilai,
                        'norec' => $item->id_param
                    );
                }
                if ($item->emrdfk == 4245) {
                    $kode = '8867-4';
                    $nama = 'Heart rate';
                    $array[] = array(
                        'id' => $item->ihs_observation,
                        'code' => $kode,
                        'display' => $nama,
                        'patient' => $item->ihs_patient,
                        'patient_name' => $item->given,
                        'encounter' => $item->ihs_encounter,
                        'encounter_display' => 'Pemeriksaan Fisik Nadi ' . $item->given . ' di hari ' . $this->hari_ini($item->masuk) . ', ' . $this->getDateIndo($item->masuk),
                        'effectiveDateTime' => date('Y-m-d', strtotime($item->tglemr)),
                        'unit' => 'beats/minute',
                        'unitCode' => '/min',
                        'value' => (float) $item->nilai,
                        'norec' => $item->id_param
                    );
                }
            }
            foreach ($array as $item) {
                $data = array(
                    'id' => $item['id'],
                    'resourceType' => 'Observation',
                    'status' => 'final',
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                    'code' => 'vital-signs',
                                    'display' => 'Vital Signs',
                                ),
                            ),
                        ),
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://loinc.org',
                                'code' => $item['code'],
                                'display' => $item['display'],
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item['patient'],
                        'display' => $item['patient_name'],
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item['encounter'],
                        'display' => $item['encounter_display'],
                    ),
                    'effectiveDateTime' => $item['effectiveDateTime'],
                    'valueQuantity' =>
                    array(
                        'value' => $item['value'],
                        'unit' => $item['unit'],
                        'system' => 'http://unitsofmeasure.org',
                        'code' => $item['unitCode'],
                    ),
                );


                if ($data['id'] == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item['id'] == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST' ;
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                // if ($item['id'] == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                // } else {
                //     $mod = IHS_Transaction::where('id', $item['id'])->first();
                // }
                // if (empty($mod)) {
                //     $mod = new IHS_Transaction();
                //     $mod->norec = $mod->generateNewId();
                //     $mod->statusenabled = $noterror;
                //     $mod->kdprofile = $this->kdProfile;
                // }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    EMRPasienD::where('norec', $item['norec'])->update([
                        'ihs_id' => $response->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Procedure(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                    pd.noregistrasi,
                    pd.tglregistrasi,
                    apd.objectruanganfk,
                    ru.namaruangan,
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
                    ddt.ihs_id as ihs_diagnosa,
                    ps.ihs_number,
                    ps.namapasien,
                    pd.ihs_id as ihs_encounter,
                    pg.ihs_id as ihs_practitioner
                FROM
                    pasiendaftar_t AS pd
                    INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                    INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                    INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                    INNER JOIN diagnosatindakanpasien_t AS dtp ON dtp.objectpasienfk = apd.norec
                    INNER JOIN detaildiagnosatindakanpasien_t AS ddt ON ddt.objectdiagnosatindakanpasienfk = dtp.norec
                    INNER JOIN diagnosatindakan_m AS dt ON dt.id = ddt.objectdiagnosatindakanfk
                    LEFT JOIN pegawai_m AS pg ON pg.id = ddt.objectpegawaifk 
                WHERE
                    pd.kdprofile = $kdProfile
                    and pd.noregistrasi ='$request[noregistrasi]'    
                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'Procedure belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('statusenabled', true)->first();
            $array = [];
            foreach ($q as $k =>  $item) {
                $data = array(
                    'id' => $item->ihs_diagnosa,
                    'resourceType' => 'Procedure',
                    'status' => 'completed',
                    'category' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://snomed.info/sct',
                                'code' => '103693007',
                                'display' => 'Diagnostic procedure',
                            ),
                        ),
                        'text' => 'Diagnostic procedure',
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://hl7.org/fhir/sid/icd-9-cm',
                                'code' =>  $item->kddiagnosatindakan,
                                'display' => $item->namadiagnosatindakan,
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_number,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                        'display' => 'Tindakan ' . $item->keterangantindakan . ' ' . $item->namapasien . ' pada ' . $this->hari_ini($item->tglinputdiagnosa) . ', ' . $this->getDateIndo($item->tglinputdiagnosa),
                    ),
                    'performedPeriod' =>
                    array(
                        'start' => substr(date("Y-m-d\TH:i:s",  strtotime(date($item->tglinputdiagnosa))), 0, 23) . date('P',  strtotime(date($item->tglinputdiagnosa))),
                        'end' => substr(date("Y-m-d\TH:i:s",  strtotime(date($item->tglinputdiagnosa))), 0, 23) . date('P',  strtotime(date($item->tglinputdiagnosa))),
                    ),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'actor' =>
                            array(
                                'reference' => 'Practitioner/' . $item->ihs_practitioner,
                                'display' => $item->namalengkap,
                            ),
                        ),
                    ),
                    // 'reasonCode' => 
                    // array (
                    //   0 => 
                    //   array (
                    //     'coding' => 
                    //     array (
                    //       0 => 
                    //       array (
                    //         'system' => 'http://hl7.org/fhir/sid/icd-10',
                    //         'code' => 'A15.0',
                    //         'display' => 'Tuberculosis of lung, confirmed by sputum microscopy with or without culture',
                    //       ),
                    //     ),
                    //   ),
                    // ),
                    // 'bodySite' => 
                    // array (
                    //   0 => 
                    //   array (
                    //     'coding' => 
                    //     array (
                    //       0 => 
                    //       array (
                    //         'system' => 'http://snomed.info/sct',
                    //         'code' => '302551006',
                    //         'display' => 'Entire Thorax',
                    //       ),
                    //     ),
                    //   ),
                    // ),
                    'note' =>
                    array(
                        0 =>
                        array(
                            'text' => $item->keterangantindakan,
                        ),
                    ),
                );

                if ($item->ihs_practitioner == null) {
                    unset($data['performer']);
                }
                if ($item->ihs_diagnosa == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item->ihs_diagnosa == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST' ;
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

           
                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                // if ($item->ihs_diagnosa == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                // } else {
                //     $mod = IHS_Transaction::where('id', $item->ihs_diagnosa)->first();
                // }
                // if (empty($mod)) {
                //     $mod = new IHS_Transaction();
                //     $mod->norec = $mod->generateNewId();
                //     $mod->statusenabled = $noterror;
                //     $mod->kdprofile = $this->kdProfile;
                // }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    DetailDiagnosaTindakanPasien::where('norec', $item->norec_detaildpasien)->update([
                        'ihs_id' => $response->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Composition(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                    dtp.rencana as rekomendasi,
                    dtp.tanggalinput as tanggal,
                    ps.namapasien,
                    ps.ihs_number as ihs_pasien,
                    pd.ihs_id as ihs_encounter,
                    pd.tglregistrasi,
                    pg.namalengkap as dokter,
                    pg.ihs_id as ihs_practitioner,
                    dtp.ihs_id as ihs_composition,
                    dtp.norec,
                    dp.namadepartemen
                FROM
                    pasiendaftar_t AS pd
                    INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                    INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                    INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                    INNER JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                    INNER JOIN rencana_t AS dtp ON dtp.noregistrasifk = apd.norec
                    INNER JOIN pegawai_m AS pg ON pg.id = dtp.objectpetugas 
                WHERE
                    pd.kdprofile = $kdProfile
                    and pd.noregistrasi ='$request[noregistrasi]'    
                "));
        
            if (count($q) == 0) {
                $response = array(
                    "issue" => 'Procedure belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('statusenabled', true)->first();
            $array = [];
            foreach ($q as $k =>  $item) {
                $data = array (
                    'id'=> $item->ihs_composition ,
                    'resourceType' => 'Composition',
                    'identifier' => 
                    array (
                      'system' => 'http://sys-ids.kemkes.go.id/composition/'.$profile->ihs_id,
                      'value' => $item->norec,
                    ),
                    'status' => 'final',
                    'type' => 
                    array (
                      'coding' => 
                      array (
                        0 => 
                        array (
                          'system' => 'http://loinc.org',
                          'code' => '18842-5',
                          'display' => 'Discharge summary',
                        ),
                      ),
                    ),
                    'category' => 
                    array (
                      0 => 
                      array (
                        'coding' => 
                        array (
                          0 => 
                          array (
                            'system' => 'http://loinc.org',
                            'code' => 'LP173421-1',
                            'display' => 'Report',
                          ),
                        ),
                      ),
                    ),
                    'subject' => 
                    array (
                      'reference' => 'Patient/'.$item->ihs_pasien,
                      'display' => $item->namapasien,
                    ),
                    'encounter' => 
                    array (
                      'reference' => 'Encounter/'.$item->ihs_encounter,
                      'display' => 'Kunjungan '.$item->namapasien.' di hari '.$this->hari_ini($item->tanggal) . ', ' . $this->getDateIndo($item->tanggal),
                    ),
                    'date' => date('Y-m-d',strtotime($item->tanggal)),
                    'author' => 
                    array (
                      0 => 
                      array (
                        'reference' => 'Practitioner/'.$item->ihs_practitioner,
                        'display' => $item->dokter,
                      ),
                    ),
                    'title' => 'Resume Medis '.(str_replace('Instalasi','',$item->namadepartemen)),
                    'custodian' => 
                    array (
                      'reference' => 'Organization/'.$profile->ihs_id,
                    ),
                    'section' => 
                    array (
                      0 => 
                      array (
                        'code' => 
                        array (
                          'coding' => 
                          array (
                            0 => 
                            array (
                              'system' => 'http://loinc.org',
                              'code' => '42344-2',
                              'display' => 'Discharge diet (narrative)',
                            ),
                          ),
                        ),
                        'text' => 
                        array (
                          'status' => 'additional',
                          'div' => $item->rekomendasi,
                        ),
                      ),
                    ),
                  );

  
                if ($item->ihs_composition == null) {
                    unset($data['id']);
                }
          
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_composition == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_composition == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_composition)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    Rencana::where('norec', $item->norec)->update([
                        'ihs_id' => $response->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

    public function Immunization(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            // $q = [];
            // if (count($q) == 0) {
            //     $response = array(
            //         "issue" => 'Procedure belum ada',
            //         "resourceType" => "OperationOutcome"
            //     );
            //     if ($lokal == true) {
            //         return $response;
            //     }
            //     return response()->json($response);
            // }
            // $profile = Profile::where('statusenabled', true)->first();
          
           $data = array (
                'resourceType' => 'Immunization',
                'status' => 'completed',
                'vaccineCode' => 
                array (
                  'coding' => 
                  array (
                    0 => 
                    array (
                      'system' => 'http://sys-ids.kemkes.go.id/kfa',
                      'code' => '92000818',
                      'display' => 'Vaksin Diphteria Toxoid 20Lf / Tetanus Toxoid 5 Lf / Bordetella Pertusis 12 IU / Hepatitis B 10 ug / Haemophilus Influenza B 10 ug 0,5 mL',
                    ),
                  ),
                ),
                'patient' => 
                array (
                  'reference' => 'Patient/100000030009',
                  'display' => 'Budi Santoso',
                ),
                'encounter' => 
                array (
                  'reference' => 'Encounter/8a224d91-5132-47d0-ae35-0fc70f24a776',
                ),
                'occurrenceDateTime' => '2022-01-10',
                'recorded' => '2022-01-10',
                'primarySource' => true,
                'location' => 
                array (
                  'reference' => 'Location/ef011065-38c9-46f8-9c35-d1fe68966a3e',
                  'display' => 'Ruang 1A, Poliklinik Rawat Jalan',
                ),
                'lotNumber' => '202009007',
                'route' => 
                array (
                  'coding' => 
                  array (
                    0 => 
                    array (
                      'system' => 'http://www.whocc.no/atc',
                      'code' => 'inj.intramuscular',
                      'display' => 'Injection Intramuscular',
                    ),
                  ),
                ),
                'doseQuantity' => 
                array (
                  'value' => 1,
                  'unit' => 'mL',
                  'system' => 'http://unitsofmeasure.org',
                  'code' => 'ml',
                ),
                'performer' => 
                array (
                  0 => 
                  array (
                    'function' => 
                    array (
                      'coding' => 
                      array (
                        0 => 
                        array (
                          'system' => 'http://terminology.hl7.org/CodeSystem/v2-0443',
                          'code' => 'AP',
                          'display' => 'Administering Provider',
                        ),
                      ),
                    ),
                    'actor' => 
                    array (
                      'reference' => 'Practitioner/N10000001',
                    ),
                  ),
                ),
                'programEligibility' => 
                array (
                  0 => 
                  array (
                    'coding' => 
                    array (
                      0 => 
                      array (
                        'system' => 'https://terminology.kemkes.go.id/CodeSystem/immunization-program-eligibility',
                        'code' => '1',
                        'display' => 'Diverifikasi',
                      ),
                    ),
                  ),
                ),
            );

                // if ($item->ihs_practitioner == null) {
                //     unset($data['performer']);
                // }
                // if ($item->ihs_diagnosa == null) {
                //     unset($data['id']);
                // }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item->ihs_diagnosa == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

           
                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                // if ($item->ihs_diagnosa == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                // } else {
                //     $mod = IHS_Transaction::where('id', $item->ihs_diagnosa)->first();
                // }
               
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    // DetailDiagnosaTindakanPasien::where('norec', $item->norec_detaildpasien)->update([
                    //     'ihs_id' => $response->id,
                    // ]);
                }
           
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

}
