<?php

namespace App\Http\Controllers\Higea;

use App\Http\Controllers\Higea\BaseHigeaCtrl;
use App\Http\Controllers\Higea\ServerResponse;
use App\Models\Master\Kelas;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Standar\LoginUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Builder;

class HigeaCtrl extends BaseHigeaCtrl
{
    public function createToken($namaUser)
    {
        $class = new Builder();
        $signer = new Sha512();
        $now = time();
        $token = $class->setHeader('alg', config('app.JWT_ALG'))
            ->set('sub', $namaUser)
            ->expiresAt($now + (config('app.JWT_EXPIRED_MINUTE') * 60))
            ->sign($signer, config('app.JWT_KEY'))
            ->getToken();
        return $token;
    }
    public function checkHashEncypt($password, $dbpass)
    {
        if (config('app.IS_PASSWORD_HASH')) {
            return Hash::check($password, $dbpass);
        } else {
            return $this->hashing_password($password) == $dbpass;
        }
    }
    public function getAccessToken(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->error(ServerResponse::VALIDATION, 400, ['error' => $validator->errors()]);
        }
        $login = LoginUser::where('namauser', '=', $r->input('username'))
            ->where('statusenabled', true)
            ->first();
        if ($login) {
            $expired =  date("Y-m-d H:i:s", strtotime("+" . config('app.JWT_EXPIRED_MINUTE') . " minutes"));
            $response = [
                'user' => [
                    'userLogin' => $login->namauser
                ],
                'accessToken' => [
                    'token' =>  $this->createToken($login->namauser) . '' . '.' . base64_encode((string)$login->kdprofile),
                    'expired' => $expired
                ]
            ];
            return $this->success(ServerResponse::SUCCESS, $response, 200);
        }
        return $this->error(ServerResponse::DATA_NOT_FOUND, 404);
    }
    public function getDoctor(Request $r)
    {
        $limit = $r->get('limit');
        $search = $r->get('search');
        $kdDokter = explode(',', $this->settingFix('kdPsikologDokter'));
        $result = Pegawai::mine()
            ->select('id', 'namalengkap')
            ->whereIn('objectjenispegawaifk', $kdDokter)
            ->when($search, function ($query) use ($search) {
                $query->like('namalengkap', 'LIKE', '%' . $search . '%');
            })
            ->when($limit, function ($query) use ($limit) {
                $query->limit($limit);
            })
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $result], 200);
    }
    public function getRuangan(Request $r)
    {
        $isRawatInap = $r->get('isRawatinap');
        $filter['idDepartemenRI'] = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $filter['idDepartemenRJ'] = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $result =  Ruangan::when($isRawatInap, function ($query)  use ($isRawatInap, $filter) {
            if ($isRawatInap == 'true') {
                $query->whereIn('objectdepartemenfk', $filter['idDepartemenRI']);
            } else {
                $query->whereIn('objectdepartemenfk', $filter['idDepartemenRJ']);
            }
        })
            ->mine()
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $result], 200);
    }
    public function getKelas(Request $r)
    {
        if ($r->ruanganId) {
            $result = DB::table('mapruangantokelas_m as mrk')
                ->join('kelas_m as kl', 'kl.id', '=', 'mrk.objectkelasfk')
                ->select('kl.id', 'kl.namakelas')
                ->where('mrk.objectruanganfk', $r->ruanganId)
                ->where('mrk.kdprofile',  $this->kdProfile)
                ->where('mrk.statusenabled', true)
                ->where('kl.statusenabled', true)
                ->orderBy('kl.namakelas')
                ->get();
        } else {
            $result = Kelas::mine()->get();
        }
        return $this->success(ServerResponse::SUCCESS, ['data' => $result], 200);
    }
    public function getKamarByKelas(Request $r)
    {
        $result = DB::table('kamar_m as kmr')
            ->join('ruangan_m as ru', 'ru.id', '=', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'kmr.objectkelasfk')
            ->select(
                'kmr.id as idKamar',
                'kmr.namakamar as namaKamar',
                'kl.id as id_kelas as idKelas',
                'ru.id as id_ruangan as idRuangan',
                'ru.namaruangan as namaRuangan',
                'kmr.jumlakamarisi as jumalhKamarIsi',
                'kmr.qtybed'
            )
            ->where('kmr.objectruanganfk', $r['idRuangan'])
            ->where('kmr.objectkelasfk', $r['idKelas'])
            ->where('kmr.statusenabled', true)
            ->where('kmr.kdprofile', $this->kdProfile)
            ->orderBy('kmr.namakamar')
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $result], 200);
    }
    public function getBed(Request $r)
    {
        $result['idStatusBedKosong'] = explode(',', $this->settingFix('idStatusBedKosong'));
        $data = DB::table('tempattidur_m')
            ->select('id', 'reportdisplay', 'nomorbed', 'objectkamarfk')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('objectstatusbedfk', $result['idStatusBedKosong'])
            ->orderBy('reportdisplay')
            ->where('objectkamarfk', $r->idKamar)
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $data], 200);
    }
    public function registrasi(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $PD = $r['pasiendaftar'];
        $APD = $r['antrianpasiendiperiksa'];
        $PS = $r['pasien'];
    }
}
