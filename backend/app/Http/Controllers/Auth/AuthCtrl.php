<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Master\Alamat;
use App\Models\Master\JenisPegawai as MasterJenisPegawai;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Standar\JenisPegawai;
use App\Models\Standar\KelompokUser;
use App\Models\Standar\LoginPasien;
use App\Models\Standar\LoginUser;
use App\Traits\Valet;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Builder;
use Ramsey\Uuid\Uuid;
// use Lcobucci\JWT\Configuration;


class AuthCtrl extends Controller
{
    use Valet;
    public function login(Request $r)
    {
        $status = false;
        $set = DB::table('settingdatafixed_m')->where('namafield','enabledCaptcha')->first();
        if(!empty($set) && $set->nilaifield == 'true'){
            if(isset($r['token'])){
                $secret = config('app.CAPTCHA_SECRETKEY');
                $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$r['token']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
                if ($response['success']) {
                    $status = true;
                }
            }
            if(!$status){
                $response = array(
                    'metaData' => array(
                        "code" => 400,
                        "message" => 'INVALID_CAPTCHA, are you robot ?',
                    ),
                    'response' => null,
                );

                return response()->json($response, $response['metaData']['code']);
            }
        }

        $validDomains = [
            'simrsbm.transmedic.co.id',
            'simrsbm-test.baliprov.go.id',
            'simrsbm.baliprov.go.id',
        ];

        if (!empty($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], $validDomains)){
            $postData = array(
                'response' => $r->input('tokenCapcay'),
                'secret' => '0x4AAAAAAAy3VEc3NL5x10Ulm91s496tuJ4'
            );
    
            $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    
            $curl = curl_init();
            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_SSL_VERIFYPEER => 2,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ));
    
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    
            $respi = curl_exec($curl);
            $err = curl_error($curl);
    
            curl_close($curl);
    
            if ($err) {
                $response = array(
                    'metaData' => array(
                        'data' => $err,
                        "code" => 400,
                        "message" => 'Captcha bermasalah',
                    ),
                    'response' => null,
                );
    
                return response()->json($response, $response['metaData']['code']);
            } 
        }

        if (empty($r->input('kataSandi')) || empty($r->input('namaUser'))) {
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'Username atau Password harus di isi',
                ),
                'response' => null,
            );

            return response()->json($response, $response['metaData']['code']);
        }
        $login = LoginUser::
            // where('katasandi', '=', $r->input('kataSandi')))
            where('namauser', '=', $r->input('namaUser'))
            ->where('statusenabled', true)
            ->first();
        if (!empty($login) && $this->checkHashEncypt($r->input('kataSandi'), $login->katasandi)) {
            $kelompokUser = KelompokUser::select('id', 'kelompokuser as kelompokUser','menu')
                ->where('id', '=', $login->objectkelompokuserfk)
                ->where('statusenabled', true)
                ->first();
            $pegawai = DB::table('pegawai_m as p')
                ->leftjoin('kelompokjabatan_m as kj', 'kj.id', '=', 'p.objectkelompokjabatanfk')
                ->select('p.id', 'p.namalengkap', 'p.tempatlahir', 'p.tgllahir', 'p.noidentitas', 'p.statusenabled',
                'p.objectjeniskelaminfk', 'p.objectjenispegawaifk', 'p.objectruangankerjafk', 'kj.namakelompokjabatan')
                ->where('p.statusenabled', true)
                ->where('p.id', $login->objectpegawaifk)
                ->first();
            if (empty($pegawai)) {
                $response = array(
                    'metaData' => array(
                        "code" => 400,
                        "message" => 'Pegawai tidak aktif',
                    ),
                    'response' => null,
                );
                return response()->json($response, $response['metaData']['code']);
            }
            $mapLoginUserToRuangan = DB::table('maploginusertoruangan_s as mlur')
                ->join('loginuser_s as lu', 'lu.id', '=', 'mlur.objectloginuserfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'mlur.objectruanganfk')
                ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
                ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk', 'dept.namadepartemen as departemen')
                ->where('ru.statusenabled', true)
                ->where('lu.id', '=', $login->id)
                ->get();
            $jenisPegawai = MasterJenisPegawai::where('id', '=', $pegawai->objectjenispegawaifk)
                ->select('id', 'jenispegawai')
                ->first();

            $ruangKerja =  Ruangan::where('id', '=', $pegawai->objectruangankerjafk)
                ->where('statusenabled', true)
                ->first();
            $profile =  Profile::where('id', '=', $login->kdprofile)
                ->select('id', 'namalengkap as namaprofile', 'alamatlengkap', 'alamatemail', 'fixedphone', 'website', 'namakota', 'lat', 'lng', 'logoprofil',
                'namaexternal')
                ->first();
            $resPegawai = array(
                'id' => $pegawai->id,
                'namaLengkap' => $pegawai->namalengkap,
                'jabatan' => $pegawai->namakelompokjabatan,
                'tempatLahir' => $pegawai->tempatlahir,
                'tglLahir' => $pegawai->tgllahir,
                'noIdentitas' => $pegawai->noidentitas,
                'statusEnabled' => $pegawai->statusenabled,
                'jenisPegawai' => $jenisPegawai,
                'ruangan' => $ruangKerja,
                'jenisKelamin_id' => $pegawai->objectjeniskelaminfk,
            );

            $session_id = substr(Uuid::uuid4(), 0, 36);
            $login->access_token = $session_id;
            $login->update();

            $dataLogin = array(
                'id' => $login->id,
                'kdProfile' => $login->kdprofile,
                'namaUser' => $login->namauser,
                'kelompokUser' => $kelompokUser,
                'pegawai' => $resPegawai,
                'profile' => $profile,
                'mapLoginUserToRuangan' => $mapLoginUserToRuangan,
            );

            $expired=  date("Y-m-d H:i:s", strtotime("+".config('app.JWT_EXPIRED_MINUTE')." minutes"));

            // $en = base64_encode($expired);
            $response = array(
                'metaData' => array(
                    "code" => 200,
                    "message" => 'Sukses',
                ),
                'response' => array(
                    'token' =>  $this->createToken($login->namauser, $session_id, 'user') . '' . '.' . base64_encode((string)$login->kdprofile),
                    'expired' => $expired,
                    'data' => $dataLogin,
                ),
            );
        } else {
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'Login gagal, Username atau Password salah',
                ),
                'response' => null,
            );
        }
        return response()->json($response, $response['metaData']['code']);
    }
    public function checkHashEncypt($password, $dbpass)
    {
        if (config('app.IS_PASSWORD_HASH')) {
            return Hash::check($password, $dbpass);
        } else {
            return $this->hashing_password($password) == $dbpass;
        }
    }
    public function createToken($namaUser, $sessionId = null, $typeLogin = 'others')
    {
        $class = new Builder();
        $signer = new Sha512();
        $now = time();
        if($typeLogin == "user") {
            $token = $class->setHeader('alg', config('app.JWT_ALG'))
            ->set('sub', $namaUser)
            ->set('sessionId', $sessionId)
            ->expiresAt($now + (config('app.JWT_EXPIRED_MINUTE') * 60))
            ->sign($signer, config('app.JWT_KEY'))
            ->getToken();
        }else {
            $token = $class->setHeader('alg', config('app.JWT_ALG'))
            ->set('sub', $namaUser)
            ->expiresAt($now + (config('app.JWT_EXPIRED_MINUTE') * 60))
            ->sign($signer, config('app.JWT_KEY'))
            ->getToken();
        }
        return $token;
    }
    public function loginPasien(Request $r)
    {
        if (empty($r['norm']) || empty($r['tgllahir'])) {
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'No Rekam Medis atau NIK atau Tgl Lahir harus di isi',
                ),
                'response' => null,
            );

            return response()->json($response, $response['metaData']['code']);
        }
        $login = Pasien::
            // where('nocm', '=', $r['norm'])
            where(function ($query) use ($r) {
                $query->where('nocm', '=',  $r['norm'])
                      ->orWhere('noidentitas', '=',  $r['norm']);
            })
            ->whereRaw("to_char(tgllahir,'dd-MM-yyyy' ) ='$r[tgllahir]'")
            ->where('statusenabled', true)
            ->first();
        if (!empty($login) ) {

            $kdProfile = $login->kdprofile;
            $pasien = DB::table('pasien_m as ps')
                ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
                ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
                ->where('ps.id', $login->id)
                ->select(
                    'ps.id',
                    'ps.namapasien',
                    'ps.tgllahir',
                    'ps.tempatlahir',
                    'alm.alamatlengkap',
                    'jk.jeniskelamin',
                    'ps.nobpjs',
                    'ps.noidentitas',
                    'ps.nocm',
                    'ps.nohp',
                    'ps.objectjeniskelaminfk as jeniskelamin_id'
                )
                ->where('ps.statusenabled', true)
                ->where('ps.kdprofile', $kdProfile)
                ->first();


            if (empty($pasien)) {
                $response = array(
                    'metaData' => array(
                        "code" => 400,
                        "message" => 'Pasien sudah di nonaktifkan oleh pihak RS',
                    ),
                    'response' => null,
                );
                return response()->json($response, $response['metaData']['code']);
            }


            $profile =  Profile::where('id', '=', $login->kdprofile)
                ->select('namalengkap as namaprofile', 'alamatlengkap', 'alamatemail', 'fixedphone', 'website', 'namakota', 'lat', 'lng', 'logoprofil')
                ->first();


            $dataLogin = array(
                'id' => $login->id,
                'kdProfile' => $login->kdprofile,
                'namaUser' => $login->nocm,
                'pasien' => $pasien,
                'profile' => $profile,
            );
            $date = strtotime('+' . config('app.JWT_EXPIRED_MINUTE') . ' minutes', strtotime(date('Y-m-d H:i:s')));
            $expired = date('Y-m-d H:i:s', $date);

            // $en = base64_encode($expired);
            $response = array(
                'metaData' => array(
                    "code" => 200,
                    "message" => 'Sukses',
                ),
                'response' => array(
                    'token' =>  $this->createToken($login->nocm) . '' . '.' . base64_encode((string)$login->kdprofile),
                    'expired' => $expired,
                    'data' => $dataLogin,
                ),
            );
        } else {
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'Login gagal, No Rekam Medis atau Tgl Lahir tidak ditemukan',
                ),
                'response' => null,
            );
        }
        return response()->json($response, $response['metaData']['code']);
    }
    public function getSignature2(Request $request)
    {
        try{
        $login = DB::table('loginuser_s')
            ->where('namauser', '=', $request->input('username'));
        $LoginUser = $login->first();

        if (!empty($LoginUser) && $this->checkHashEncypt($request->input('password'), $LoginUser->katasandi)) {
            if(isset($request['expired'])){
                $date = strtotime('+' . config('app.JWT_EXPIRED_MINUTE') . ' minutes', strtotime(date('Y-m-d H:i:s')));
                $expired = date('Y-m-d H:i:s', $date);
                $data['user'] = $LoginUser->namauser;
                $data['expires_in'] =  config('app.JWT_EXPIRED_MINUTE') *60*60; //seconds
                $data['expires_in_date'] = $expired;
                $data['access_token'] = $this->createToken($LoginUser->namauser) . '' . '.' . base64_encode((string)$LoginUser->kdprofile);
            }else{
                $data['X-ID'] =$LoginUser->id;
                $data['X-USERNAME'] =$LoginUser->namauser;
                $data['X-AUTH-TOKEN'] =   $this->createToken($LoginUser->namauser) . '' . '.' . base64_encode((string)$LoginUser->kdprofile);
                $pegawai = DB::table('pegawai_m')->where('id',$LoginUser->objectpegawaifk)->first();
                $data['NIK'] = !empty($pegawai)?$pegawai->noidentitas:null;
            }



            $result = array(
                "response" =>  $data,
                "metadata" => array(
                    "code" => 200,
                    "message" => "Ok"
                )
            );
        }else{
            $result = array(
                "response" =>  null,
                "metadata" => array(
                    "code" => 400,
                    "message" => "Username atau Password salah"
                )
            );
        }
        }catch(Exception $e){
            $result = array(
                "response" =>  null,
                "metadata" => array(
                    "code" => "400",
                    "message" => "Terjadi Kesalahan"
                )
            );
        }

        return response()->json($result, $result['metadata']['code']);


    }
    public function loginPasien2(Request $r)
    {
        if ($r->email) {
            $validator = Validator::make($r->all(),[
                'email' => 'required|email'
            ],[
                'email.required' => 'Email Harus Disis !',
                'email.email' => 'Format Email Tidak Sesuai !',
            ]);
            if ($validator->fails()) {
                $response = [
                    'metaData' => [
                        "code" => 400,
                        "message" => 'Validation Failed !',
                    ],
                    'response' =>null,
                    'error' => $validator->errors(),
                ];
                return response()->json($response, $response['metaData']['code']);
            }
        }else if(empty($r['norm']) || empty($r['tgllahir'])){
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'No Rekam Medis atau NIK atau Tgl Lahir harus di isi',
                ),
                'response' => null,
            );

            return response()->json($response, $response['metaData']['code']);
        }
        $nocm       = $r->norm;
        $email      = $r->email;
        $tgllahir   = $r->tgllahir;
        $login = DB::table('pasien_m AS ps')
            ->leftJoin('loginpasien_s AS  lp' ,'lp.nocmfk' ,'ps.id')
            ->select('ps.id','ps.kdprofile','ps.nocm')
            ->when($nocm, function ($query) use ($nocm) {
                return $query->where('ps.nocm', $nocm);
            })
            ->when($email, function ($query) use ($email) {
                return $query->where('lp.email', $email);
            })
            ->when($tgllahir, function ($query) use ($tgllahir) {
                return $query->whereRaw("to_char(tgllahir,'dd-MM-yyyy' ) ='$tgllahir'");
            })
            ->where('ps.statusenabled', true)
            ->first();
        if (!empty($login) ) {
            $kdProfile = $login->kdprofile;
            $pasien = DB::table('pasien_m as ps')
                ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
                ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
                ->where('ps.id', $login->id)
                ->select(
                    'ps.id',
                    'ps.namapasien',
                    'ps.tgllahir',
                    'ps.tempatlahir',
                    'alm.alamatlengkap',
                    'jk.jeniskelamin',
                    'ps.nobpjs',
                    'ps.noidentitas',
                    'ps.nocm',
                    'ps.nohp',
                    'ps.objectjeniskelaminfk as jeniskelamin_id'
                )
                ->where('ps.statusenabled', true)
                ->where('ps.kdprofile', $kdProfile)
                ->first();
            if (empty($pasien)) {
                $response = [
                    'metaData' => [
                        "code" => 400,
                        "message" => 'Pasien sudah di nonaktifkan oleh pihak RS',
                    ],
                    'response' => null,
                ];
                return response()->json($response, $response['metaData']['code']);
            }


            $profile =  Profile::where('id', '=', $login->kdprofile)
                ->select('namalengkap as namaprofile', 'alamatlengkap', 'alamatemail', 'fixedphone', 'website', 'namakota', 'lat', 'lng', 'logoprofil')
                ->first();


            $dataLogin = [
                'id' => $login->id,
                'kdProfile' => $login->kdprofile,
                'namaUser' => $login->nocm,
                'pasien' => $pasien,
                'profile' => $profile,
            ];
            $date = strtotime('+' . config('app.JWT_EXPIRED_MINUTE') . ' minutes', strtotime(date('Y-m-d H:i:s')));
            $expired = date('Y-m-d H:i:s', $date);
            $response = array(
                'metaData' => array(
                    "code" => 200,
                    "message" => 'Sukses',
                ),
                'response' => array(
                    'token' =>  $this->createToken($login->nocm) . '' . '.' . base64_encode((string)$login->kdprofile),
                    'expired' => $expired,
                    'data' => $dataLogin,
                ),
            );
        } else {
            $response = [
                'metaData' => array(
                    "code" => 400,
                    "message" => 'Login gagal, data pasien tidak ditemukan',
                ),
                'response' => null,
            ];
        }
        return response()->json($response, $response['metaData']['code']);
    }
    public function registasiPasien(Request $r){
        $validator = Validator::make($r->all(),[
            'email' => 'required|email',
            'nocm' =>'required',
            'nama' =>'required',
            'password' =>'required',
            'tglLahir' =>'required'
        ],[
            'email.required' => 'Email Harus Disis !',
            'email.email' => 'Format Email Tidak Sesuai !',
            'password.required' =>'Password Harus Disis !',
            'nama.required' => 'Nama Harus Disis !',
            'tglLahir.required' =>'Tanggal Lahir Harus Disis !',
        ]);
        if ($validator->fails()) {
            return $this->respond($validator->errors(),400, 'Validation Failed !');
        }
        $nocm  = $r->nocm;
        $pasien = DB::table('pasien_m as ps')
        ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
        ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
        ->select(
            'ps.id',
            'ps.namapasien',
            'ps.tgllahir',
            'ps.tempatlahir',
            'alm.alamatlengkap',
            'jk.jeniskelamin',
            'ps.nobpjs',
            'ps.noidentitas',
            'ps.nocm',
            'ps.nohp',
            'ps.objectjeniskelaminfk as jeniskelamin_id',
            'ps.statusenabled',
            'ps.kdprofile'
        )
        ->where('ps.statusenabled', true)
        ->where('ps.nocm' ,$nocm)
        ->first();
        $loginUser = LoginPasien::where('email' ,$r->email)->where('statusenabled' ,true)->count();
        if ($loginUser > 0) {
            $response = [
                'metaData' => [
                    "code" => 409,
                    "message" => 'Email Sudah Terdaftar !',
                ],
                'response' => null,
            ];
            return response()->json($response, $response['metaData']['code']);
        }
        if (!$pasien || $pasien->statusenabled != true) {
            $response = [
                'metaData' => [
                    "code" => 400,
                    "message" => 'No Rekam Medis tidak ditemukan !',
                ],
                'response' => null,
            ];
            return response()->json($response, $response['metaData']['code']);
        }
        $profile =  Profile::where('id', '=', $pasien->kdprofile)
        ->select('namalengkap as namaprofile', 'alamatlengkap', 'alamatemail', 'fixedphone', 'website', 'namakota', 'lat', 'lng', 'logoprofil')
        ->first();

        try {
            $id = $this->SEQUENCE_MASTER(new LoginPasien(),'id',$pasien->kdprofile);
            $pasienLogin =  new LoginPasien();
            $pasienLogin->id =$id;
            $pasienLogin->statusenabled =true;
            $pasienLogin->kdprofile = $pasien->kdprofile;
            $pasienLogin->katasandi = $this->hashing_password($r->password);
            $pasienLogin->namauser = $r->nama;
            $pasienLogin->nocmfk = $pasien->id;
            $pasienLogin->email = $r->email;
            $pasienLogin->notelepon = $r->noTelp;
            $pasienLogin->statuslogin = 1;
            $pasienLogin->save();

            $dataLogin = array(
                'id' => $pasien->id,
                'kdProfile' => $pasien->kdprofile,
                'namaUser' => $pasien->nocm,
                'pasien' => $pasien,
                'profile' => $profile,
            );
            $date = strtotime('+' . config('app.JWT_EXPIRED_MINUTE') . ' minutes', strtotime(date('Y-m-d H:i:s')));
            $expired = date('Y-m-d H:i:s', $date);

            $response = [
                'metaData' => [
                    "code" => 200,
                    "message" => 'Sukses',
                ],
                'response' => [
                    'token' =>  $this->createToken($pasien->nocm) . '' . '.' . base64_encode((string)$pasien->kdprofile),
                    'expired' => $expired,
                    'data' => $dataLogin,
                ],
            ];

        } catch (Exception $e) {
            $response = array(
                'metaData' => array(
                    "code" => 500,
                    "message" => 'Registrasi gagal '
                ),
                'response' => null,
            );
        }
        return response()->json($response, $response['metaData']['code']);
    }
    public function accessToken(Request $request)
    {
        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['expired'] = true;
        $objetoRequest['username'] = $request['username'];
        $objetoRequest['password'] =  $request['password'];;
        return $this->getSignature2($objetoRequest);
    }
}
