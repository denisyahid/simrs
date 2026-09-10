<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Builder;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\MonitoringTaksId;
use App\Models\Master\Alamat;
use App\Models\Master\JenisKelamin;
use App\Models\Master\Pasien;
use App\Http\Controllers\Bridging\BridgingBPJSCtrl;
use App\Models\Master\Ruangan;
use App\Models\Master\Pegawai;
use Exception;

class AntrianOnlineCtrl extends Controller
{
    use Valet;
    protected $bridgingBPJSCtrl;
    public function __construct(BridgingBPJSCtrl $bridgingBPJSCtrl)
    {
        parent::__construct(true);
        $this->bridgingBPJSCtrl = $bridgingBPJSCtrl;
    }

    // START Area fungsi buat token mobile jkn
    public function checkHashEncypt($password, $dbpass)
    {
        if (config('app.IS_PASSWORD_HASH')) {
            return Hash::check($password, $dbpass);
        } else {
            return $this->hashing_password($password) == $dbpass;
        }
    }

    public function createToken($namaUser)
    {
        $class = new Builder();
        $signer = new Sha512();
        $now = time();
        $token = $class->setHeader('alg', config('app.JWT_ALG'))
            ->set('sub', $namaUser)
            ->expiresAt($now + (config('app.JWT_EXPIRED_MINUTE_BPJS') * 60))
            ->sign($signer, config('app.JWT_KEY'))
            ->getToken();
        return $token;
    }
    // END Area fungsi buat token mobile jkn

    // START fungsi pendukung
    public function buildRequestbpjstools($url, $jenis, $method, $data)
    {
        $buildReq = new Request();
        $buildReq['url'] = $url;
        $buildReq['jenis'] = $jenis;
        $buildReq['method'] = $method;
        $buildReq['data'] = $data;
        $buildReq['user'] = $this->getNamaPegawai();
        $postBuild = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->bpjsTools($buildReq, false);
        $postBuild = json_decode($postBuild->content(), true);
        return $postBuild;
    }
    // END fungsi pendukung

    // START Area fungsi reservasi mobile jkn / WS RS
    public function tokenAntrean(Request $request)
    {
        $username = $request->header('x-username');
        $password = $request->header('x-password');
        $login = DB::table('loginuser_s')
            ->where('namauser', $username)
            ->where('statusenabled', true)
            ->first();

        if (!empty($login) && $this->checkHashEncypt($password, $login->katasandi)) {
            $token = $this->createToken($login->namauser) . '' . '.' . base64_encode((string)$login->kdprofile);
            $result = array(
                "response" => array(
                    "token" => $token,
                ),
                "metadata" => array(
                    "message" => 'Ok',
                    "code" => 200,
                )
            );
        } else {
            $result = array(
                "metadata" => array(
                    "message" => 'Username atau password Tidak sesuai',
                    "code" => 201,
                )
            );
        }
        return response()->json($result, $result['metadata']['code']);
    }
    public function statusAntrean(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $kodepoli = $request->kodepoli;
        $kodedokter = $request->kodedokter;
        $tanggalperiksa = $request->tanggalperiksa;
        $jampraktek = $request->jampraktek;

        $tglAwal = date('Y-m-d', strtotime($tanggalperiksa)) . ' 00:00:00';
        $tglAkhir = date('Y-m-d', strtotime($tanggalperiksa)) . ' 23:59:59';

        if ($tanggalperiksa != date('Y-m-d', strtotime($tanggalperiksa))) {
            $result = array("metadata" => array("message" => "Format Tanggal Tidak Sesuai, format yang benar adalah yyyy-mm-dd", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if ($tglAwal < date('Y-m-d') . ' 00:00:00') {
            $result = array("metadata" => array("message" => "Tanggal Periksa Tidak Berlaku", "code" => 201));
            return response()->json($result, $result['metadata']['code']);;
        }

        $ruangan = DB::table('ruangan_m')
            ->where('kdsubspesialisbpjs', $kodepoli)
            ->whereRaw("(iseksekutif = false or iseksekutif is null)")
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->first();

        if (empty($ruangan)) {
            $result = array("metadata" => array("message" => "Poli Tidak Ditemukan atau belum termapping di RS", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        $data = DB::select(DB::raw("
        SELECT 
        SUM(x.totalantrean) as totalantrean,
        SUM(x.totalantrean) - SUM(x.sudahdipanggil) AS sisaantrean,
        x.namaruangan,
        x.dokter
        FROM (
            SELECT COUNT(apd.norec) AS totalantrean
            ,apd.noantrian
            ,rm.noruangan
            ,rm.namaruangan
            ,pg.namalengkap as dokter
            ,CASE WHEN CAST(apd.statusantrian AS INTEGER) = 0 THEN 1 ELSE 0 END AS belumdipanggil
            ,CASE WHEN CAST(apd.statusantrian AS INTEGER) = 1 THEN 1 ELSE 0 END AS sudahdipanggil
            FROM antrianpasiendiperiksa_t AS apd 
            JOIN pasiendaftar_t AS pd ON apd.noregistrasifk = pd.norec
            JOIN ruangan_m AS rm ON rm.id = apd.objectruanganfk
            JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
            WHERE pd.statusenabled = true
            AND pd.kdprofile = $kdProfile
            AND rm.kdsubspesialisbpjs = '$kodepoli'
            AND pg.kddokterbpjs = '$kodedokter'
            AND pd.tglregistrasi BETWEEN '$tglAwal' AND '$tglAkhir'
            GROUP BY apd.noantrian,
            rm.noruangan,
            rm.namaruangan,
            pg.namalengkap,
            apd.statusantrian
        ) x
        GROUP BY x.namaruangan, x.dokter
        "));

        if (count($data) > 0) {
            $antrianSelanjutnya = DB::table('antrianpasiendiperiksa_t as apd')
                ->select('ru.noruangan', 'apd.noantrian')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                ->whereBetween('pd.tglregistrasi', [$tglAwal, $tglAkhir])
                ->where('ru.kdsubspesialisbpjs', $kodepoli)
                ->where('pd.statusenabled', true)
                ->where('pg.kddokterbpjs', $kodedokter)
                ->where('apd.statusantrian', '1')
                ->orderBy('apd.noantrian', 'desc')
                ->first();

            $kouta = DB::table('ruangan_m as ru')
                ->select('slot.*')
                ->join('slottingkiosk_m as slot', 'slot.objectruanganfk', '=', 'ru.id')
                ->where('ru.id', $ruangan->id)
                ->where('ru.statusenabled', true)
                ->where('slot.statusenabled', true)
                ->first();

            $jmlkouta = 0;
            $jmlsisakouta = 0;
            if (!empty($kouta)) {
                $jmlkouta = $kouta->quota;
                $jmlsisakouta = $kouta->quota - $data[0]->totalantrean;
            }

            $result = array(
                "response" => array(
                    "namapoli" => $data[0]->namaruangan,
                    "namadokter" => $data[0]->dokter,
                    "totalantrean" => $data[0]->totalantrean,
                    "sisaantrean" => $data[0]->sisaantrean,
                    "antreanpanggil" => $antrianSelanjutnya == null ? '-' : $antrianSelanjutnya->noruangan . "-" . str_pad($antrianSelanjutnya->noantrian, 3, "0", STR_PAD_LEFT),
                    "sisakuotajkn" => $jmlsisakouta,
                    "kuotajkn" => $jmlkouta,
                    "sisakuotanonjkn" => $jmlsisakouta,
                    "kuotanonjkn" => $jmlkouta,
                    "keterangan" => ""
                ),
                "metadata" => array(
                    "message" => "Ok",
                    "code" => 200,
                ),
            );
        } else {
            $result = array(
                "metadata" => array(
                    "message" => "Belum ada data yang bisa ditampilkan",
                    "code" => 201,
                ),
            );
        }
        return response()->json($result, $result['metadata']['code']);
    }
    public function ambilAntrean(Request $request)
    {
        $kdProfile = $this->kdProfile;
        date_default_timezone_set(config('app.timezone')); // set timezone

        // validasi parameter 
        if (empty($request->nomorkartu)) {
            $result = array("metadata" => array("message" => "Nomor Kartu Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->tanggalperiksa)) {
            $result = array("metadata" => array("message" => "Tanggal Periksa Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $request['tanggalperiksa'])) {
            $result = array("metadata" => array("message" => "Format Tanggal Tidak Sesuai, format yang benar adalah yyyy-mm-dd", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if ($request->tanggalperiksa >= date('Y-m-d', strtotime('+90 days', strtotime(date('Y-m-d'))))) {
            $result = array("metadata" => array("message" => "Tanggal periksa maksimal 90 hari dari hari ini", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if ($request->tanggalperiksa == date('Y-m-d')) {
            $result = array("metadata" => array("message" => "Tanggal periksa minimal besok", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if ($request->kodepoli == 'BDM') {
            $result = array("metadata" => array("message" => "Poli ini tidak membuka reservasi online", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        // if ($request->kodepoli == 'INT') {
        //     $result = array("metadata" => array("message" => "Poli ini tidak membuka reservasi online", "code" => 201));
        //     return response()->json($result, $result['metadata']['code']);
        // }

        if ($request->tanggalperiksa < date('Y-m-d')) {
            $result = array("metadata" => array("message" => "Tanggal Periksa Tidak Berlaku", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        // if(date('w',strtotime( $request->tanggalperiksa )) == 0 ){
        //     $result = array("metadata"=>array("message" => "Tidak ada jadwal Poli di hari Minggu", "code" => 201));
        //     return response()->json($result, $result['metadata']['code']);
        // }

        if (empty($request->nik)) {
            $result = array("metadata" => array("message" => "NIK Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (!is_numeric($request->nik) || strlen($request->nik) < 16) {
            $result = array("metadata" => array("message" => "Format NIK Tidak Sesuai ", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->nohp)) {
            $result = array("metadata" => array("message" => "No hp tidak boleh kosong", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->kodepoli)) {
            $result = array("metadata" => array("message" => "Poli Tidak Ditemukan", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->jeniskunjungan)) {
            $result = array("metadata" => array("message" => "Jenis Kunjungan tidak boleh kosong", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->nomorreferensi)) {
            $result = array("metadata" => array("message" => "Nomor Referensi tidak boleh kosong", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        // $dateNow = date("H");
        // if ($dateNow >= "12") {
        //     $result = array("metadata" => array("message" => "Pendaftaran telah tutup untuk hari ini", "code" => 201));
        //     return response()->json($result, $result['metadata']['code']);
        // }

        $ruangan = DB::table('ruangan_m')
            ->where('kdsubspesialisbpjs', $request->kodepoli)
            ->whereRaw("(iseksekutif = false or iseksekutif is null)")
            ->whereRaw("namaruangan not ilike '%SORE%'")
            ->whereRaw("namaruangan not ilike '%VIP%'")
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->first();

        if (empty($ruangan)) {
            $result = array("metadata" => array("message" => "Poli Tidak Ditemukan atau belum termapping di RS", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        // ENDOSKOPI SEMENTARA TUTUP
        if($ruangan->id == 286) {
            $result = array("metadata" => array("message" => "Poli ini tidak membuka reservasi online", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($ruangan->noruangan) || $ruangan->noruangan == null) {
            $result = array("metadata" => array("message" => "No ruangan Poli Belum diatur di RS", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        // cekJadwalHFIS
        $url= "jadwaldokter/kodepoli/".$ruangan->noruangan."/tanggal/". $request->tanggalperiksa;
        $jenis= "antrean";
        $method= "GET";
  
        $cek = $this->buildRequestbpjstools($url, $jenis, $method, null);

        // dd($cek);
        
        if (!is_array($cek)) {
            $cek = json_decode($cek->content(), true);
        }
        
        $code = isset($cek['metaData']['code']) ? $cek['metaData']['code'] : null;
  
        if($code != '200'){
            $result = array("metadata"=>array("message" => "Pendaftaran ke Poli Ini Sedang Tutup", "code" => 201));
            return $this->setStatusCode($result['metadata']['code'])->respond($result);
        }else{
            $ada = false;
            $jamPrakteks = null;
            if(count($cek['response']) > 0){
                foreach($cek['response'] as $item){
                    // dd($item);
                    if($request['kodedokter'] == $item['kodedokter']){
                        $ada = true;
                        $jamPrakteks = explode("-",$item['jadwal']);                        
                        break;
                    }
                }
            }
            if($ada == false){
                $dokter = DB::table('pegawai_m')
                ->where('statusenabled', true)
                ->where('kdprofile', $kdProfile)
                ->where('kddokterbpjs', $request['kodedokter'])
                ->first();

                $nama = !empty($dokter) ? $dokter->namalengkap:'-';
                $result = array("metadata"=>array("message" => "Jadwal Dokter ".$nama." Tersebut Belum Tersedia, Silahkan Reschedule Tanggal dan Jam Praktek Lainnya", "code" => 201));
                return $this->setStatusCode($result['metadata']['code'])->respond($result);
            }
        }

        $dokter = DB::table('pegawai_m')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->where('kddokterbpjs', $request->kodedokter)
            ->first();

        if (empty($dokter)) {
            $result = array("metadata" => array("message" => "Dokter Tidak Ditemukan atau belum termapping di RS", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        $eksek = false;
        $antrian = $this->GetJamKosong($request->kodepoli, $request->tanggalperiksa, $kdProfile, $eksek, $dokter);

        if ($antrian['antrian'] == 0) {
            DB::rollBack();
            $result = array("metadata" => array("message" => "Jadwal Poli sedang tutup atau kuota habis", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        DB::beginTransaction();
        try {
            $pasienBaru = 0;
            $pasien = DB::table('pasien_m')
                ->where(function ($query) use ($request) {
                    $query->where("nobpjs", $request->nomorkartu)
                        ->orWhere('noidentitas', $request->nik);
                })
                ->where('statusenabled', true)
                ->where('kdprofile', $kdProfile)
                ->first();

            if (empty($pasien)) {
                DB::rollBack();
                $result = array(
                    "metadata" => array(
                        "code" => 202,
                        "message" => "Data pasien ini tidak ditemukan, silahkan Melakukan Registrasi Pasien Baru"
                    )
                );
                return response()->json($result, $result['metadata']['code']);
            } else {
                $pasienBaru = 1;
            }

            $tgl = $request->tanggalperiksa;

            $dataReservasi = DB::table('antrianpasienregistrasi_t as apr')
                ->select('apr.noantrian', 'apr.noreservasi', 'ru.namaexternal', 'apr.tanggalreservasi')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
                ->whereRaw("to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$tgl'")
                ->where('apr.objectruanganfk', '=', $ruangan->id)
                ->where('apr.noreservasi', '!=', '-')
                ->where('apr.noidentitas', '=', $request->nik)
                ->where('apr.nobpjs', '=', $request->nomorkartu)
                ->whereNotNull('apr.noreservasi')
                ->whereRaw("(apr.isbatal = false or apr.isbatal is null)")
                ->where('apr.statusenabled', true)
                ->first();

            if (isset($dataReservasi) && !empty($dataReservasi)) {
                DB::rollBack();
                $result = array("metadata" => array("code" => 201, "message" => "Nomor Antrean Hanya Dapat Diambil 1 Kali Pada Tanggal Yang Sama"));
                return response()->json($result, $result['metadata']['code']);
            }

            $datetime = new \DateTime(date($pasien->tgllahir));
            $umur = (int) $datetime->diff(new \DateTime(date('Y-m-d')))->format('%y');

            $jenis = '';
                if ($umur <= 17 || $umur > 60) {
                    $jenis = 'RA';
                } else {
                    $jenis = 'RL';
                }


            $newptp = new AntrianPasienRegistrasi();
            $newptp->norec = $newptp->generateNewId();
            $newptp->kdprofile = $kdProfile;
            $newptp->statusenabled = true;
            $newptp->objectruanganfk = $ruangan->id;
            $newptp->objectjeniskelaminfk = !empty($pasien) ? $pasien->objectjeniskelaminfk : null;
            $newptp->noreservasi = substr(Uuid::generate(), 0, 7);
            $newptp->tanggalreservasi = $request->tanggalperiksa . " " . $antrian['jamkosong'];
            $newptp->tgllahir = !empty($pasien) ? $pasien->tgllahir : null;
            $newptp->objectkelompokpasienfk = "2";
            $newptp->objectpendidikanfk = 0;
            $newptp->namapasien = !empty($pasien) ? $pasien->namapasien : null;
            $newptp->tglinput = date('Y-m-d H:i:s');
            $newptp->nobpjs = $request->nomorkartu;
            $newptp->jenis = 'RMJ';
            $newptp->norujukan = $request->nomorreferensi;
            $newptp->notelepon = !empty($pasien) ? $pasien->nohp : null;
            $newptp->objectpegawaifk = $dokter->id;
            $newptp->tipepasien = !empty($pasien) ? "LAMA" : "BARU";
            $newptp->ismobilejkn = true;
            $newptp->isreservasi = true;
            $newptp->objectasalrujukanfk = $request->jeniskunjungan;
            $newptp->type = !empty($pasien) ? "LAMA" : "BARU";
            $newptp->objectagamafk = !empty($pasien) ? $pasien->objectagamafk : null;
            if (!empty($pasien)) {
                $alamat = Alamat::where('nocmfk', $pasien->id)->first();
                if (!empty($alamat)) {
                    $newptp->alamatlengkap = $alamat->alamatlengkap;
                    $newptp->objectdesakelurahanfk = $alamat->objectdesakelurahanfk;
                    $newptp->negara = $alamat->objectnegarafk;
                }
            }
            $newptp->objectgolongandarahfk = !empty($pasien) ? $pasien->objectgolongandarahfk : null;
            $newptp->kebangsaan = !empty($pasien) ? $pasien->objectkebangsaanfk : null;
            $newptp->objectkebangsaanfk = !empty($pasien) ? $pasien->objectkebangsaanfk : null;
            $newptp->namaayah = !empty($pasien) ? $pasien->namaayah : null;
            $newptp->namaibu = !empty($pasien) ? $pasien->namaibu : null;
            $newptp->namasuamiistri = !empty($pasien) ? $pasien->namasuamiistri : null;
            $newptp->noaditional = !empty($pasien) ? $pasien->noaditional : null;
            $newptp->umursaatinput = $umur;
            $newptp->jam = $request->jampraktek;
            // $newptp->noantrian = $antrian['antrian'];
            $newptp->noantrianpoli = $antrian['antrian'];
            $newptp->noidentitas =  $request->nik;
            $newptp->nocmfk = !empty($pasien) ? $pasien->id : null;
            $newptp->paspor = !empty($pasien) ? $pasien->paspor : null;
            $newptp->objectpekerjaanfk = !empty($pasien) ? $pasien->objectpekerjaanfk : null;
            $newptp->objectpendidikanfk = !empty($pasien) && $pasien->objectpendidikanfk != null ? $pasien->objectpendidikanfk :  0;
            $newptp->objectstatusperkawinanfk = !empty($pasien) ? $pasien->objectstatusperkawinanfk : null;
            $newptp->tempatlahir = !empty($pasien) ? $pasien->tempatlahir : null;
            $newptp->statuspanggil  = 0;

            $newptp->loketkiosk  = $antrian['loket'];
            $newptp->save();
            $norecAntrianRegis = $newptp->norec;

            $tglAwal = date('Y-m-d', strtotime($request->tanggalperiksa)) . ' 00:00:00';
            $tglAkhir = date('Y-m-d', strtotime($request->tanggalperiksa)) . ' 23:59:59';
            $idNonKelas = (int) $this->settingFix('idNonKelas');
            $idKelBPJS = (int) $this->settingFix('idKelompokPasienBPJS');
            $idrekananBPJS = (int) $this->settingFix('BPJS_kodeRekanan');
            $idtipelayananBPJS = (int) $this->settingFix('idJenisPelayananReguler');

            $asalrujukan = 20;
            switch ($request->jeniskunjungan) {
                case 1:
                    $asalrujukan = 20;
                    break;
                case 2:
                    $asalrujukan = 5;
                    break;
                case 3:
                    $asalrujukan = 5;
                    break;
                case 4:
                    $asalrujukan = 2;
                    break;
            }
            // if (!empty($pasien)) {
            //     $noAntrianRegistrasi = $newptp->jenis . '-' . str_pad($newptp->noantrian, 3, "0", STR_PAD_LEFT);
            //     $pasiendaftar = array(
            //         'norec' => '',
            //         'nocmfk' => $pasien->id,
            //         'tglregistrasi' => $newptp->tanggalreservasi,
            //         'objectruanganlastfk' => $ruangan->id,
            //         'objectruangantujuanfk' => $ruangan->id,
            //         'asalrujukanfk' => $asalrujukan,
            //         'keteranganasalrujukan' => null,
            //         'objectkelompokpasienlastfk' => 2,
            //         'objectrekananfk' => 2551,
            //         'jenispelayananfk' => $idtipelayananBPJS,
            //         'objectpegawaifk' => $dokter->id,
            //         'objectpegawairawatbersamafk' => null,
            //         'objectkelasfk' => $idNonKelas,
            //         'israwatinap' => false,
            //         'catatan' => null,
            //         'statuspasien' => !empty($pasien) ? "LAMA" : "BARU",
            //         'objectrekananfk' => $idrekananBPJS,
            //         'nocm' => !empty($pasien) ? $pasien->nocm : null,
            //         'namapasien' => !empty($pasien) ? $pasien->namapasien : null,
            //         'antrianpasienregistrasifk' => $norecAntrianRegis,
            //         'nojkn' => $noAntrianRegistrasi, //null,
            //         'isjkn' => true,
            //         'noreservasi' => $newptp->noreservasi,
            //     );
            //     $antrianpasiendiperiksa = array(
            //         'norec' => '',
            //         'objectkamarfk' => null,
            //         'nobed' => null,
            //         'israwatgabung' => null,
            //     );

            //     $objetoRequest = new Request();
            //     $objetoRequest['pasiendaftar'] = $pasiendaftar;
            //     $objetoRequest['kelompokpasienfk'] = 2;
            //     $objetoRequest['antrianpasiendiperiksa'] = $antrianpasiendiperiksa;
            //     $objetoRequest['user'] = $this->getNamaPegawai();
            //     $cek = app('App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl')->saveRegistrasi($objetoRequest);
            //     $cek = json_decode($cek->content(), true);

            //     $nomorAntrian = 0;
            //     $nomorAntrianANGKA = $nomorAntrian;

                

            //     if (isset($cek["metaData"])) {
            //         if ($cek["metaData"]["code"] == 200) {
            //             $nomorAntrianANGKA = $cek["response"]["dataAPD"]["noantrian"];
            //             $newptp->noantrian = $nomorAntrianANGKA;
            //             $newptp->save();
            //             $huruf = $newptp->jenis;

            //             // if ($ruangan->noruangan != null) {
            //             //     $huruf = $ruangan->noruangan;
            //             // }
            //             $nomorAntrian = $huruf . '-' . str_pad($cek["response"]["dataAPD"]["noantrian"], 3, "0", STR_PAD_LEFT);

            //             $transMessage = "Ok";
            //             $transStatus = true;
            //         } else {

            //             DB::rollBack();
            //             $result = array(
            //                 "metadata" => array(
            //                     "message" => $cek['metaData']['message'],
            //                     "code" => 201,
            //                     "result" => 'Tess 1'
            //                 )
            //             );
            //             return response()->json($result, $result['metadata']['code']);
            //         }
            //     } else {
            //         DB::rollBack();
            //         $result = array(
            //             "metadata" => array(
            //                 "message" => $cek['response'],
            //                 "code" => 201,
            //                 "result" => 'Tess 2'
            //             )
            //         );
            //         return response()->json($result, $result['metadata']['code']);
            //     }
            // }

            $huruf = $newptp->jenis;
            $nomorAntrian = str_pad($newptp->noantrianpoli, 3, "0", STR_PAD_LEFT);
            $nomorAntrianANGKA = $newptp->noantrianpoli;

            $jmlJKN = collect(DB::select("select count(norec) as jml 
                from antrianpasienregistrasi_t 
                where objectruanganfk = '$ruangan->id'
                --and objectpegawaifk = '$dokter->id'
                and statusenabled = true 
                and tanggalreservasi between '$tglAwal' and '$tglAkhir'
                "))->first();

             // rumus estimasidilayani = jampoli buka + (SPM*sisaantrean-1)
                    // *SPM itu standar pelayanan minimal
                    // jadi kalau misal jam polibukanya jam 08.00, SPMnya 6 menit dan saya dapat antrian nomor 10 jadi perhitangnnya gini
                    // 08.00 + (6*(10-1)) = 08.00 + 54
                    // jadi estimasidilayani yg muncul disini harus 08.54
            // end rumus

            // date_default_timezone_set('Asia/Jakarta'); 
            date_default_timezone_set(config('app.timezone'));// set timezone
            $jamsatu = trim($jamPrakteks[0] ?? '08:00'); 
            $rata2 = 10;  // 5 menit
            $antriJam = (int)$nomorAntrianANGKA - 1;
            $timeCalculate = floor($rata2 * $antriJam);
            $finalCalculate = \DateTime::createFromFormat('H:i', $jamsatu);
            if (!$finalCalculate) {
                $finalCalculate = new \DateTime('08:00'); // Get current time
            }
            $finalCalculate->modify("+$timeCalculate minutes");

            if(new \DateTime($finalCalculate->format("H:i")) > new \DateTime($jamPrakteks[1]) ) {
                $transMessage = "Antrian telah ditutup untuk hari ini, silahkan coba dilain hari";
                // $transMessage = $hasilTunggu->format("H:i");
                $transStatus = false;
            }

            $createTglReser = date_create($newptp->tanggalreservasi);
            $finalEstimate = date_format($createTglReser, "Y-m-d"). " " .$finalCalculate->format("H:i:s");
            $estimasidilayani = strtotime($finalEstimate) * 1000;

            $transStatus = true;
            $transMessage = "Ok";
        } catch (Exception $e) {
            $transMessage = "Gagal Reservasi";
            $transStatus = false;
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "response" => array(
                    "nomorantrean" => $nomorAntrian, //$noAntrianRegistrasi,
                    "angkaantrean" => $newptp->noantrianpoli,
                    "kodebooking" => $newptp->noreservasi,
                    "norm" => $pasien->nocm,
                    "namapoli" => $ruangan->namaruangan,
                    "namadokter" => $dokter->namalengkap,
                    "estimasidilayani" => $estimasidilayani,
                    "sisakuotajkn" => $antrian['kuota'] - (!empty($jmlJKN) ? $jmlJKN->jml : 0),
                    "kuotajkn" => $antrian['kuota'],
                    "sisakuotanonjkn" => $antrian['kuota'] - (!empty($jmlJKN) ? $jmlJKN->jml : 0),
                    "kuotanonjkn" => $antrian['kuota'],
                    "keterangan" => "Peserta harap 60 menit lebih awal guna pencatatan administrasi."
                ),
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 200,
                ),
            );
        } else {
            DB::rollBack();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 201,
                    "result" => $e->getMessage() . $e->getLine()
                ),
            );
        }
        return response()->json($result, $result['metadata']['code']);
    }
    public function sisaAntrean(Request $request)
    {
        $kdProfile = $this->kdProfile;
        date_default_timezone_set(config('app.timezone')); // set timezone

        if (empty($request->kodebooking)) {
            $result = array("metadata" => array("message" => "Kode Booking Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        // $data = DB::table('antrianpasienregistrasi_t as apr')
        // ->leftJoin('pegawai_m as pg','pg.id','=','apr.objectpegawaifk')
        // ->leftJoin('ruangan_m as ru','ru.id','=','apr.objectruanganfk')
        // ->selectRaw("apr.*, pg.namalengkap, ru.namaruangan")
        // ->where('apr.kdprofile', $kdProfile)
        // ->where('apr.statusenabled', true)
        // ->where('apr.noreservasi', $request->kodebooking)
        // ->first();
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->selectRaw("pd.*, pg.namalengkap, ru.noruangan, ru.namaruangan, apd.noantrian, apd.objectruanganfk")
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.noreservasi', $request->kodebooking)
            ->first();

        if (!empty($data)) {
            $tglAwal = date('Y-m-d', strtotime($data->tglregistrasi)) . ' 00:00:00';
            $tglAkhir = date('Y-m-d', strtotime($data->tglregistrasi)) . ' 23:59:59';

            $sisaAntrian = DB::table('antrianpasiendiperiksa_t as apd')
                ->select('ru.noruangan', 'apd.noantrian')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                ->whereBetween('pd.tglregistrasi', [$tglAwal, $tglAkhir])
                ->where('ru.id', $data->objectruanganfk)
                ->where('pd.statusenabled', true)
                ->where('pg.id', $data->objectpegawaifk)
                ->where('apd.statusantrian', '0')
                ->count();

            $antrianPanggil = DB::table('antrianpasiendiperiksa_t as apd')
                ->select('ru.noruangan', 'apd.noantrian')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                ->whereBetween('pd.tglregistrasi', [$tglAwal, $tglAkhir])
                ->where('ru.id', $data->objectruanganfk)
                ->where('pd.statusenabled', true)
                ->where('pg.id', $data->objectpegawaifk)
                ->where('apd.statusantrian', '1')
                ->orderBy('apd.noantrian', 'desc')
                ->first();

            // $noAntrianRegistrasi = $data->jenis . '-' . str_pad($data->noantrian, 3, "0", STR_PAD_LEFT);
            $noAntrianRegistrasi = $data->noruangan . '-' . str_pad($data->noantrian, 3, "0", STR_PAD_LEFT);
            $noAntrianNow = "";
            if (!empty($antrianPanggil)) {
                $noAntrianNow = $antrianPanggil->noruangan . '-' . str_pad($antrianPanggil->noantrian, 3, "0", STR_PAD_LEFT);
            }

            $estimasidilayani = 0;
            if ((int)$sisaAntrian == 0) {
                $estimasidilayani = 0;
            } else if ((int)$sisaAntrian == 1) {
                $estimasidilayani = 1800 * 1; //dalam detik (30 menit)
            } else {
                $estimasidilayani = 1800 * ((int)$sisaAntrian - 1);
            }

            $result = array(
                "response" => array(
                    "nomorantrean" => $noAntrianRegistrasi,
                    "namapoli" => $data->namaruangan,
                    "namadokter" => $data->namalengkap,
                    "sisaantrean" => $sisaAntrian,
                    "antreanpanggil" => $noAntrianNow,
                    "waktutunggu" => $estimasidilayani,
                    "keterangan" => ""
                ),
                "metadata" => array(
                    "message" => "Ok",
                    "code" => 200,
                ),
            );
            return response()->json($result, $result['metadata']['code']);
        } else {
            $result = array(
                "metadata" => array(
                    "message" => "Belum ada data yang bisa ditampilkan",
                    "code" => 201,
                ),
            );
            return response()->json($result, $result['metadata']['code']);
        }
    }
    public function batalAntrean(Request $request)
    {
        $kdProfile = $this->kdProfile;
        if (empty($request->kodebooking)) {
            $result = array("metadata" => array("message" => "Kode Booking Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->keterangan)) {
            $result = array("metadata" => array("message" => "Keterangan Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        DB::beginTransaction();
        try {
            $data = DB::table('pasiendaftar_t')
                ->where('kdprofile', $kdProfile)
                ->where('noreservasi', $request->kodebooking)
                ->first();

            if (empty($data)) {
                $result = array("metadata" => array("message" => "Antrean Tidak Ditemukan", "code" => 201));
                return response()->json($result, $result['metadata']['code']);
            } else {
                // if($data->statusenabled == false){
                //     $result = array(
                //         "metadata" => array(
                //             "message" => "Antrean Tidak Ditemukan atau Sudah Dibatalkan",
                //             "code" => 201
                //         )
                //     );
                //     return response()->json($result, $result['metadata']['code']);
                // }
                if ($data->ischeckin == true) {
                    $result = array(
                        "metadata" => array(
                            "message" => "Pasien Sudah Dilayani, Antrean Tidak Dapat Dibatalkan",
                            "code" => 201
                        )
                    );
                    return response()->json($result, $result['metadata']['code']);
                }

                $dataperjanjian = DB::table('antrianpasienregistrasi_t')
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->where('noreservasi', $request->kodebooking)
                    ->update([
                        'statusenabled' => false,
                        'isbatal' => false,
                        'keteranganbatal' => $request->keterangan,
                    ]);
                $pendaftaran = DB::table('pasiendaftar_t')
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->where('noreservasi', $request->kodebooking)
                    ->update([
                        'statusenabled' => false,
                    ]);
                $antrianpasien = DB::table('antrianpasiendiperiksa_t')
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->where('noregistrasifk', $data->norec)
                    ->update([
                        'statusenabled' => false,
                    ]);

                $waktukirim = strtotime(date('Y-m-d H:i:s')) * 1000;
                $json = array(
                    "kodebooking" => $data->noreservasi,
                    "taskid" => 99, //pasien lama langsung task 99 //(tidak hadir/batal)
                    "waktu" => $waktukirim,
                );
                $post = $this->buildRequestbpjstools("antrean/updatewaktu", "antrean", "POST", $json);
            }

            $transMessage = "Ok";
            $transStatus = true;
        } catch (Exception $e) {
            $transMessage = "Gagal Batal Antrean";
            $transStatus = false;
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 200,
                ),
            );
        } else {
            DB::rollBack();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 201,
                ),
            );
        }

        return response()->json($result, $result['metadata']['code']);
    }
    public function checkIn(Request $request)
    {
        $kdProfile = $this->kdProfile;
        date_default_timezone_set(config('app.timezone')); // set timezone
        $kodebooking = $request->kodebooking;
        $waktu = $request->waktu;

        if (empty($kodebooking)) {
            $result = array("metadata" => array("message" => "Kode Booking Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($waktu)) {
            $result = array("metadata" => array("message" => "Waktu Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }


        DB::beginTransaction();
        $noRec = '';
        try {
            $tglAyeuna = date('Y-m-d H:i:s');
            $tglAwal = date('Y-m-d 00:00:00');
            $tglAkhir = date('Y-m-d 23:59:59');

            $dataasli = DB::table('antrianpasienregistrasi_t')
                        ->where('noreservasi', '=', $kodebooking)
                        ->first();

            if ($dataasli->isconfirm == true) {
                $transMessage = "Pasien sudah checkin hari ini";
                DB::rollBack();
                $result = array("status" => 201, "result"  => $datacek);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            $data = AntrianPasienRegistrasi::where('noreservasi', '=', $kodebooking)->first();
            $data->isconfirm = true;
            $data->save();


            $transMessage = "Checkin Berhasil";
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Checkin Gagal";
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 200,
                ),
            );
        } else {
            DB::rollBack();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 201,
                ),
            );
        }
        return response()->json($result, $result['metadata']['code']);
    }
    public function pasienBaru(Request $request)
    {
        $kdProfile = $this->kdProfile;
        if (empty($request->nomorkartu)) {
            $result = array("metadata" => array("message" => "Nomor Kartu Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (!is_numeric($request->nomorkartu) || strlen($request->nomorkartu) < 13) {
            $result = array("metadata" => array("message" => "Format Nomor Kartu Tidak Sesuai", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->nik)) {
            $result = array("metadata" => array("message" => "NIK Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (!is_numeric($request->nik) || strlen($request->nik) < 16) {
            $result = array("metadata" => array("message" => "Format NIK Tidak Sesuai ", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->nomorkk)) {
            $result = array("metadata" => array("message" => "Nomor KK Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->nama)) {
            $result = array("metadata" => array("message" => "Nama Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->jeniskelamin)) {
            $result = array("metadata" => array("message" => "Jenis Kelamin Belum Dipilih", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->tanggallahir)) {
            $result = array("metadata" => array("message" => "Tanggal Lahir Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if ($request->tanggallahir != date('Y-m-d', strtotime($request->tanggallahir)) || date('Y-m-d', strtotime($request->tanggallahir)) > date('Y-m-d')) {
            $result = array("metadata" => array("message" => "Format Tanggal Lahir tidak Sesuai", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->nohp)) {
            $result = array("metadata" => array("message" => "No hp tidak boleh kosong", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->alamat)) {
            $result = array("metadata" => array("message" => "Alamat Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->kodeprop)) {
            $result = array("metadata" => array("message" => "Kode Propinsi Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->namaprop)) {
            $result = array("metadata" => array("message" => "Nama Propinsi Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->kodedati2)) {
            $result = array("metadata" => array("message" => "Kode Dati 2 Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->namadati2)) {
            $result = array("metadata" => array("message" => "Dati 2 Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->kodekec)) {
            $result = array("metadata" => array("message" => "Kode Kecamatan Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->namakec)) {
            $result = array("metadata" => array("message" => " Kecamatan Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->kodekel)) {
            $result = array("metadata" => array("message" => "Kode Kelurahan Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->namakel)) {
            $result = array("metadata" => array("message" => "Kelurahan Belum Diisi ", "code" => 201));

            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->rt)) {
            $result = array("metadata" => array("message" => "RT Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if (empty($request->rw)) {
            $result = array("metadata" => array("message" => "RW Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        $cek = Pasien::where('nobpjs', $request['nomorkartu'])->where('statusenabled', true)->first();
        if (!empty($cek)) {
            $result = array("metadata" => array("message" => "Data Peserta sudah pernah dientrikan", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        $propinsi = DB::table('propinsi_m')
            ->select('id', 'namapropinsi')
            ->where('kodebpjs', $request->kodeprop)
            ->first();

        $kotakabupaten = DB::table('kotakabupaten_m')
            ->select('id', 'namakotakabupaten')
            ->where('kodebpjs', $request->kodedati2)
            ->first();

        $jk = JenisKelamin::where('reportdisplay', strtoupper($request->jeniskelamin))
            ->select('id', 'jeniskelamin', 'reportdisplay')
            ->first();

        DB::beginTransaction();
        try {
            $pasien = array(
                'id' => '',
                'isPenunjang' => false,
                'isbayi' => false,
                'nocmfkibu' => null,
                'noidentitas' => $request->nik,
                'nobpjs' => $request->nomorkartu,
                'namapasien' => $request->nama,
                'tempatlahir' => null,
                'tgllahir' => date('Y-m-d H:i:s', strtotime($request->tanggallahir)),
                'objectjeniskelaminfk' => !empty($jk) ? $jk->id : null,
                'nohp' => $request->nohp,
                'objectagamafk' => null,
                'email' => null,
                'namaibu' => null,
                'objectstatusperkawinanfk' => null,
                'objectgolongandarahfk' => null,
                'objectpendidikanfk' => null,
                'objectpekerjaanfk' => null,
                'objectsukufk' => null,
                'noaditional' => null,
                'notelepon' => $request->nohp,
                'namaayah' => null,
                'namakeluarga' => null,
                'namasuamiistri' => null,
                'penanggungjawab' => null,
                'hubungankeluargapj' => null,
                'telponpenanggungjawab' => null,
                'bahasa' => null,
                'jeniskelaminpenanggungjawab' => null,
                'umurpenanggungjawab' => null,
                'pekerjaanpenangggungjawab' => null,
                'alamatrmh' => null,
                'objectkebangsaanfk' => null,
                'objectnegarafk' => null,
                'progress' => 0,
                'isjkn' => true,
            );
            $alamat = array(
                'alamatlengkap' => $request->alamat,
                'rtrw' => $request->rt . "/" . $request->rw,
                'objectpropinsifk' => !empty($propinsi) ? $propinsi->id : null,
                'objectkotakabupatenfk' => !empty($kotakabupaten) ? $kotakabupaten->id : null,
                'objectkecamatanfk' => null,
                'objectdesakelurahanfk' => null,
                'kodepos' => null,
            );

            $objetoRequest = new Request();
            $objetoRequest['pasien'] = $pasien;
            $objetoRequest['alamat'] = $alamat;
            $objetoRequest['user'] = $this->getNamaPegawai();
            $cek = app('App\Http\Controllers\Registrasi\PasienBaruCtrl')->savePasien($objetoRequest);
            $cek = json_decode($cek->content(), true);
            if (isset($cek["metadata"])) {
                if ($cek["metadata"]["code"] == "200") {
                    $norm = $cek["response"]["norm"];
                } else {
                    DB::rollBack();
                    $result = array(
                        "metadata" => array(
                            "message" => $cek["metadata"]["message"],
                            "code" => 201,
                        ),
                    );

                    return response()->json($result, $result['metadata']['code']);
                }
            } else {

                DB::rollBack();
                $result = array(
                    "metadata" => array(
                        "message" =>  "Terjadi Kesalahan",
                        "code" => 201,
                    ),
                );

                return response()->json($result, $result['metadata']['code']);
            }

            $transStatus = true;
            $transMessage = "Ok";
        } catch (Exception $e) {
            $transStatus = false;
            $transMessage = "Terjadi Kesalahan";
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "response" => array(
                    "norm" => $norm
                ),
                "metadata" => array(
                    "message" => "Harap datang ke admisi untuk melengkapi data rekam medis",
                    "code" => 200,
                ),
            );
        } else {
            DB::rollBack();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 201,
                ),
            );
        }
        return response()->json($result, $result['metadata']['code']);
    }
    public function jadwalOperasiRS(Request $request)
    {
        $kdProfile = $this->kdProfile;
        date_default_timezone_set(config('app.timezone')); // set timezone

        if ((!isset($request->tanggalawal) && empty($request->tanggalawal)) && (!isset($request->tanggalakhir) && empty($request->tanggalakhir))) {
            $result = array("metadata" => array("message" => "Tanggal Awal dan Akhir tidak boleh kosong", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        if ($request->tanggalawal > $request->tanggalakhir) {
            $result = array("metadata" => array("message" => "Tanggal Akhir Tidak Boleh Kecil dari Tanggal Awal ", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        $depbedah = (int) $this->settingFix('idDepartemenBedah');
        try {
            $data = DB::select(DB::raw("SELECT
            so.noorder as kodebooking,
            so.tglpelayananawal  as tanggaloperasi,
            pr.namaproduk as jenistindakan,
            case when ru.kdinternal is null then 'BED' else ru.kdinternal  end as kodepoli,
            case when ru.namaexternal is null then 'BEDAH' else ru.namaexternal end AS namapoli,
            pas.nocm,
            pd.noregistrasi,pas.nobpjs,
            so.statusorder,pd.objectkelompokpasienlastfk
            FROM strukorder_t AS so
            --join orderpelayanan_t as op on op.noorderfk=so.norec
            LEFT JOIN produk_m as pr on pr.id=so.jenisoperasifk
            LEFT JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
            INNER JOIN pasien_m AS pas ON pas.id = pd.nocmfk
            LEFT JOIN ruangan_m AS ru ON ru.id = so.objectruanganfk
            LEFT JOIN ruangan_m AS ru2 ON ru2.id = so.objectruangantujuanfk
            WHERE so.kdprofile = $kdProfile
            --AND pas.nocm ILIKE '%11233764%'
            AND ru2.objectdepartemenfk = $depbedah
            AND so.statusenabled = true
            --and so.statusorder is null
            --and ru.kdinternal is not null
            and so.tglpelayananawal between '$request->tanggalawal 00:00:00' and '$request->tanggalakhir 23:59:59'
            ORDER BY so.tglorder desc"));

            $list = [];
            foreach ($data as $k) {
                $stt = 0; //$k->statusorder;
                if ($k->statusorder == null) {
                    $stt = 0;
                }
                //1 sudah dilaksanakan , 0 belum ,2 batal
                $list[] = array(
                    'kodebooking' => $k->kodebooking,
                    'tanggaloperasi' => date('Y-m-d', strtotime($k->tanggaloperasi)),
                    'jenistindakan' => $k->jenistindakan,
                    'kodepoli' => $k->kodepoli,
                    'namapoli' => $k->namapoli,
                    'terlaksana' => $stt,
                    'nopeserta' => $k->objectkelompokpasienlastfk != 2 ? '' : $k->nobpjs,
                    'lastupdate' => strtotime(date('Y-m-d H:i:s')) * 1000
                );
            }

            if (count($list) > 0) {
                $result = array(
                    "response" => array(
                        "list" => $list,
                    ),
                    "metadata" => array(
                        "message" => "Ok",
                        "code" => 200,
                    )
                );
            } else {
                $result = array(
                    "metadata" => array(
                        "message" => "Belum ada data yang bisa ditampilkan",
                        "code" => 201,
                    )
                );
            }
        } catch (Execption $e) {
            $result = array(
                "metadata" => array(
                    "message" => "Gagal menampilkan data",
                    "code" => 201,
                )
            );
        }

        return response()->json($result, $result['metadata']['code']);
    }

    public function jadwalOperasiPasien(Request $request)
    {
        $kdProfile = $this->kdProfile;
        if (empty($request->nopeserta)) {
            $result = array("metadata" => array("message" => "Nomor Kartu Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (!is_numeric($request->nopeserta) || strlen($request->nopeserta) < 13) {
            $result = array("metadata" => array("message" => "Format Nomor Kartu Tidak Sesuai", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }
        $depbedah = (int) $this->settingFix('idDepartemenBedah');
        $idKelBPJS = (int) $this->settingFix('idKelompokPasienBPJS');
        try {
            $data = DB::select(DB::raw("SELECT
            so.noorder as kodebooking,
            DATE(so.tglpelayananawal) AS tgloperasi,
            pr.namaproduk as jenistindakan,
            case when ru.kdinternal is null then 'BED' else ru.kdinternal end as kodepoli,
            case when ru.namaexternal is null then 'BEDAH' else ru.namaexternal end AS namapoli,
            pas.nocm,
            pd.noregistrasi,pas.nobpjs    
            FROM strukorder_t AS so
            --join orderpelayanan_t as op on op.noorderfk=so.norec
            LEFT JOIN produk_m AS pr ON pr.id = so.jenisoperasifk
            LEFT JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
            INNER JOIN pasien_m AS pas ON pas.id = pd.nocmfk
            LEFT JOIN ruangan_m AS ru ON ru.id = so.objectruanganfk
            LEFT JOIN ruangan_m AS ru2 ON ru2.id = so.objectruangantujuanfk
            WHERE so.kdprofile = $kdProfile
            --AND pas.nocm ILIKE '%11233764%'
            and pas.nobpjs = '$request->nopeserta'
            AND ru2.objectdepartemenfk = $depbedah
            AND so.statusenabled = true
            and so.statusorder is null
            --and ru.kdinternal is not null
            and pd.objectkelompokpasienlastfk = $idKelBPJS
            ORDER BY so.tglorder desc"));

            $list = [];
            foreach ($data as $k) {
                $list[] = array(
                    'kodebooking' => $k->kodebooking,
                    'tanggaloperasi' => date('Y-m-d', strtotime($k->tanggaloperasi)),
                    'jenistindakan' => $k->jenistindakan,
                    'kodepoli' => $k->kodepoli,
                    'namapoli' => $k->namapoli,
                    'terlaksana' => 0,
                );
            }

            if (count($list) > 0) {
                $result = array(
                    "response" => array(
                        "list" => $list,
                    ),
                    "metadata" => array(
                        "message" => "Ok",
                        "code" => 200,
                    )
                );
            } else {
                $result = array(
                    "metadata" => array(
                        "message" => "Belum ada data yang bisa ditampilkan",
                        "code" => 201,
                    )
                );
            }
        } catch (Exception $e) {
            $result = array(
                "metadata" => array(
                    "message" => "Gagal menampilkan data",
                    "code" => 201,
                )
            );
        }
        return response()->json($result, $result['metadata']['code']);
    }
    public function ambilAntreanFarmasi(Request $request)
    {
        $result = array(
            "response" => array(
                "jenisresep" => "Racikan/Non Racikan",
                "nomorantrean" => 1,
                "keterangan" => ""
            ),
            "metadata" => array(
                "message" => "Ok",
                "code" => 200,
            ),
        );
        return response()->json($result, $result['metadata']['code']);
    }
    public function statusAntreanFarmasi(Request $request)
    {
        $result = array(
            "response" => array(
                "jenisresep" => "Racikan/Non Racikan",
                "totalantrean" => 10,
                "sisaantrean" => 8,
                "antreanpanggil" => 2,
                "keterangan" => ""
            ),
            "metadata" => array(
                "message" => "Ok",
                "code" => 200,
            ),
        );
        return response()->json($result, $result['metadata']['code']);
    }

    public function GetJamKosong($kode, $tgl, $kdProfile, $eksek, $dokter)
    {
        $ruangan = DB::table('ruangan_m')
            ->where('kdsubspesialisbpjs', $kode)
            ->whereRaw("(iseksekutif = false or iseksekutif is null)")
            ->whereRaw("namaruangan not ilike '%SORE%'")
            ->whereRaw("namaruangan not ilike '%VIP%'")
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->first();

        /*
        slot lokal
        */
        $hariReserve = $this->hari_ini($tgl);

        // var_dump($ruangan);

        // if(str_contains($ruangan->namaruangan, 'SORE') == true){
        //     $slotruangan = DB::table('ruangan_m as ru')
        //     // ->join('slottingkiosk_m as slot', 'slot.objectruanganfk', '=', 'ru.id')
        //     ->join('jadwaldokter_m as slot', 'slot.objectruanganfk', '=', 'ru.id')
            
        //     ->select(
        //         'ru.id',
        //         'ru.namaruangan',
        //         'ru.objectdepartemenfk',
        //         'slot.jammulai as jambuka',
        //         'slot.jamakhir as jamtutup',
        //         'slot.quota',
        //         'slot.kodeexternal as loket',
        //         'slot.objectpegawaifk as dokterfk',
        //         DB::raw("(EXTRACT(EPOCH FROM slot.jamakhir) - EXTRACT(EPOCH FROM slot.jammulai))/3600 as totaljam")
        //     )
        //     ->where('ru.statusenabled', true)
        //     ->where('ru.id', $ruangan->id)
        //     ->where('ru.kdprofile', $kdProfile)
        //     ->where('slot.statusenabled', true)
        //     ->where('slot.tanggal', $tgl)
        //     ->when($dokter != null, function($q) use($dokter) {
        //         return $q->where('slot.objectpegawaifk', $dokter->id);
        //     })
        //     // ->where('slot.hari', 'ILIKE', '%'. strtoupper($hariReserve) .'%')
        //     ->first();
        // } else{
            $slotruangan = DB::table('ruangan_m as ru')
            // ->join('slottingkiosk_m as slot', 'slot.objectruanganfk', '=', 'ru.id')
            ->join('jadwaldokter_m as slot', 'slot.objectruanganfk', '=', 'ru.id')
            
            ->select(
                'ru.id',
                'ru.namaruangan',
                'ru.objectdepartemenfk',
                'slot.jammulai as jambuka',
                'slot.jamakhir as jamtutup',
                'slot.quota',
                'slot.kodeexternal as loket',
                'slot.objectpegawaifk as dokterfk',
                DB::raw("(EXTRACT(EPOCH FROM slot.jamakhir) - EXTRACT(EPOCH FROM slot.jammulai))/3600 as totaljam")
            )
            ->where('ru.statusenabled', true)
            ->where('ru.id', $ruangan->id)
            ->where('ru.kdprofile', $kdProfile)
            ->where('slot.statusenabled', true)
            ->where('slot.quota', '!=', 0)
            ->where('slot.tanggal', $tgl)
            // ->where('slot.hari', 'ILIKE', '%'. strtoupper($hariReserve) .'%')
            ->first();
        // }


        

        // var_dump($slotruangan);

        if (empty($slotruangan)) {
            return array("antrian" => 0, "jamkosong" => "00:00");
        }
        /*
            slot hfis
        */
        // $this->bridgingBPJSCtrl->getSetting()

        $dataReservasi = DB::table('antrianpasienregistrasi_t as apr')
            ->select('apr.norec', 'apr.tanggalreservasi')
            ->whereRaw(" to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$tgl'")
            ->where('apr.objectruanganfk', $ruangan->id)
            ->where('apr.noreservasi', '!=', '-')
            ->whereNotNull('apr.noreservasi')
            ->where('apr.statusenabled', true)
            ->where('apr.kdprofile', $kdProfile)
            ->whereRaw("(apr.isbatal != false or apr.isbatal is null)")
            ->orderBy('apr.tanggalreservasi')
            ->get();

        $begin = new Carbon($slotruangan->jambuka);
        $jamBuka = $begin->format('H:i');
        $end = new Carbon($slotruangan->jamtutup);
        $jamTutup = $end->format('H:i');
        $quota = (float)$slotruangan->quota;
        if ($quota == 0) {
            return array("antrian" => 0, "jamkosong" => "00:00");
        }
        $waktuPerorang = round(((float)$slotruangan->totaljam / (float)$slotruangan->quota) * 60, 1);

        $i = 0;
        $slotterisi = 0;
        $jamakhir = '00:00';
        $reservasi = [];
        foreach ($dataReservasi as $items) {
            $jamUse =  new Carbon($items->tanggalreservasi);
            $slotterisi += 1;
            $reservasi[] = array(
                'jamreservasi' => $jamUse->format('H:i')
            );
            $jamakhir = $jamUse->format('H:i');
        }

        $intervals = [];
        $intervalsAwal  = [];
        $begin = new \DateTime($jamBuka);
        $end = new \DateTime($jamTutup);
        $interval = \DateInterval::createFromDateString(floor($waktuPerorang) . ' minutes');

        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $intervals[] = array(
                'jam' =>  $dt->format("H:i")
            );
            $intervalsAwal[] = array(
                'jam' =>  $dt->format("H:i")
            );
        }
        if (count($intervals) == 0) {
            return array("antrian" => 0, "jamkosong" => "00:00");
        }

        if (count($reservasi) > 0) {
            for ($j = count($reservasi) - 1; $j >= 0; $j--) {
                for ($k = count($intervals) - 1; $k >= 0; $k--) {
                    if ($intervals[$k]['jam'] == $reservasi[$j]['jamreservasi']) {
                        array_splice($intervals, $k, 1);
                    }
                }
            }
        }

        if (count($intervals) > 0) {

            $tglAwal = date('Y-m-d', strtotime($tgl)) . ' 00:00:00';
            $tglAkhir = date('Y-m-d', strtotime($tgl)) . ' 23:59:59';

            $noantri = AntrianPasienRegistrasi::where('objectruanganfk', $ruangan->id)
                    ->where('tanggalreservasi', '>=', $tglAwal)
                    ->where('tanggalreservasi', '<=', $tglAkhir)
                    ->where('statusenabled', true)
                    ->max('noantrianpoli') + 1;

            // $noantri = DB::table('antrianpasienregistrasi_t as apr')
            //     ->where('jenis', $ruangan->noruangan)
            //     ->where('loketkiosk', $slotruangan->loket)
            //     ->whereBetween('tanggalreservasi', [$tglAwal, $tglAkhir])
            //     ->max('noantrian') + 1;

            // $antrian = 1;
            // for ($x = 0; $x <= count($intervalsAwal); $x++) {
            //     if ($intervals[0]['jam'] == $intervalsAwal[$x]['jam']) {
            //         $antrian = $x + 1;
            //         // dd($antrian);
            //         break;
            //     }
            // }

            return array("antrian" => $noantri, "jamkosong" => $intervals[0]['jam'], "kuota" => $quota, "loket" => $slotruangan->loket);
        } else {
            return array("antrian" => 0, "jamkosong" => "00:00");
        }
    }
    // END Area fungsi reservasi mobile jkn

    // START Area fungsi kirim taks id / WS BPJS
    public function saveMonitoringTaksId(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $deptJalan = explode(',', $this->settingFix('KdDepartemenRawatJalan'));
        $kdDepartemenRawatJalan = [];
        foreach ($deptJalan as $item) {
            $kdDepartemenRawatJalan[] = (int)$item;
        }
        DB::beginTransaction();
        try {
            // Cek Pasien Rawat Jalan
            $data = DB::table("pasiendaftar_t as pd")
                ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
                ->where('pd.kdprofile', $kdProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $request->noregistrasifk)
                ->wherein('rm.objectdepartemenfk', $kdDepartemenRawatJalan)
                ->first();

            if (!empty($data)) {
                $monitoring = MonitoringTaksId::where('noregistrasifk', $request->noregistrasifk)
                    ->where('taskid', $request->taskid)
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();

                if (empty($monitoring)) {
                    // insert data monitoring task id
                    $monitoring = new MonitoringTaksId();
                    $monitoring->norec = $monitoring->generateNewId();
                    $monitoring->kdprofile = $kdProfile;
                    $monitoring->statusenabled = true;
                    $monitoring->noregistrasifk = $request->noregistrasifk;
                    $monitoring->taskid = $request->taskid;
                    $monitoring->waktu = $request->waktu;
                    $monitoring->statuskirim = true;
                    $monitoring->save();
                } else {
                    // update data monitoring task id jika status kirim true
                    if ($request->statuskirim) {
                        $monitoring->waktu = $request->waktu;
                        $monitoring->statuskirim = true;
                        $monitoring->save();
                    }
                }
            }

            $transStatus = true;
            $transMessage = "Simpan Berhasil";
        } catch (Exception $e) {
            $transStatus = false;
            $transMessage = "Simpan Gagal";
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "as" => 'hs@epic'
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "as" => 'hs@epic'
            );
        }
        return response()->json($result, $result['status']);
    }

    public function sendDataAntrean(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idKelBPJS = (int) $this->settingFix('idKelompokPasienBPJS');
        // date_default_timezone_set(config('Asia/Jakarta')); // set timezone
        $data = DB::table("pasiendaftar_t as pd")
            ->select(DB::raw("case when pd.ismobilejkn = true then pd.noreservasi else pd.noregistrasi end as kodebooking
        ,pd.objectkelompokpasienlastfk
        ,pd.statuspasien
        ,pd.tglregistrasi
        ,pd.objectpegawaifk
        ,ps.nobpjs
        ,ps.noidentitas
        ,ps.nohp
        ,ps.nocm
        ,ru.kdsubspesialisbpjs
        ,ru.namaruangan
        ,ru.noruangan
        ,ru.id as idruangan
        ,apd.noantrian
        "))
            ->join("antrianpasiendiperiksa_t as apd", "apd.noregistrasifk", "=", "pd.norec")
            ->join("pasien_m as ps", "ps.id", "=", "pd.nocmfk")
            ->join("ruangan_m as ru", "ru.id", "=", "pd.objectruanganlastfk")
            ->where("pd.norec", $request->noregistrasifk)
            ->where("pd.kdprofile", $kdProfile)
            ->where("pd.statusenabled", true)
            ->first();

        $CekAntrean = $this->buildRequestbpjstools("antrean/pendaftaran/kodebooking/$data->kodebooking", "antrean", "GET", null);
        if (isset($CekAntrean["metaData"])) {
            // kondisi kalau antrean belum ada
            if ($CekAntrean["metaData"]["code"] !== 200) {
                $noAntrianRegistrasi = $data->noruangan . '-' . str_pad($data->noantrian, 3, "0", STR_PAD_LEFT);
                $kouta = DB::table("ruangan_m as ru")
                    ->select("slot.*")
                    ->join("slottingkiosk_m as slot", "slot.objectruanganfk", "=", "ru.id")
                    ->where("ru.id", $data->idruangan)
                    ->where("ru.statusenabled", true)
                    ->where("slot.statusenabled", true)
                    ->first();

                $tglawal = date('Y-m-d', strtotime($data->tglregistrasi)) . " 00:00";
                $tglakhir = date('Y-m-d', strtotime($data->tglregistrasi)) . " 23:59";
                $totalantrean = DB::table("antrianpasiendiperiksa_t as apd")
                    ->join("pasiendaftar_t as pd", "pd.norec", "=", "apd.noregistrasifk")
                    ->join("ruangan_m as rm", "rm.id", "=", "apd.objectruanganfk")
                    ->join("pegawai_m as pg", "pg.id", "=", "pd.objectpegawaifk")
                    ->where("pd.kdprofile", $kdProfile)
                    ->where("pd.statusenabled", true)
                    ->where("rm.id", $data->idruangan)
                    ->where("pg.id", $data->objectpegawaifk)
                    ->whereBetween('pd.tglregistrasi', [$tglawal, $tglakhir])
                    ->count();

                $jmlkouta = 0;
                $jmlsisakouta = 0;
                if (!empty($kouta)) {
                    $jmlkouta = $kouta->quota;
                    $jmlsisakouta = $kouta->quota - $totalantrean;
                }

                // kirim data antrean disini
                // "kodebooking": "{kodebooking yang dibuat unik}",
                // "jenispasien": "{JKN / NON JKN}",
                // "nomorkartu": "{noka pasien BPJS,diisi kosong jika NON JKN}",
                // "nik": "{nik pasien}",
                // "nohp": "{no hp pasien}",
                // "kodepoli": "{memakai kode subspesialis BPJS}",
                // "namapoli": "{nama poli}",
                // "pasienbaru": {1(Ya),0(Tidak)},
                // "norm": "{no rekam medis pasien}",
                // "tanggalperiksa": "{tanggal periksa}",
                // "kodedokter": {kode dokter BPJS},
                // "namadokter": "{nama dokter}",
                // "jampraktek": "{jam praktek dokter}",
                // "jeniskunjungan": {1 (Rujukan FKTP), 2 (Rujukan Internal), 3 (Kontrol), 4 (Rujukan Antar RS)},
                // "nomorreferensi": "{norujukan/kontrol pasien JKN,diisi kosong jika NON JKN}",
                // "nomorantrean": "{nomor antrean pasien}",
                // "angkaantrean": {angka antrean},
                // "estimasidilayani": {waktu estimasi dilayani dalam miliseconds},
                // "sisakuotajkn": {sisa kuota JKN},
                // "kuotajkn": {kuota JKN},
                // "sisakuotanonjkn": {sisa kuota non JKN},
                // "kuotanonjkn": {kuota non JKN},
                // "keterangan": "{informasi untuk pasien}"

                $nohp = preg_replace('/\D/', '', $request->nohp); 

                // Potong jika lebih dari 13 digit
                if (strlen($nohp) > 13) {
                    $nohp = substr($nohp, 0, 13);
                }

                $jsonSend = array(
                    "kodebooking" => $data->kodebooking,
                    "jenispasien" => $data->objectkelompokpasienlastfk == $idKelBPJS ? "JKN" : "NON JKN",
                    "nomorkartu" => $data->nobpjs,
                    "nik" => $data->noidentitas ? $data->noidentitas : '0000000000000000',
                    "nohp" => $nohp,
                    "kodepoli" => $data->kdsubspesialisbpjs,
                    "namapoli" => $data->namaruangan,
                    "pasienbaru" => $data->statuspasien == "BARU" ? 1 : 0,
                    "norm" => $data->nocm,
                    "tanggalperiksa" => date('Y-m-d', strtotime($data->tglregistrasi)),
                    "kodedokter" => $request->kodedokter,
                    "namadokter" => $request->namadokter,
                    "jampraktek" => $request->jampraktek,
                    "jeniskunjungan" => $request->jeniskunjungan,
                    "nomorreferensi" => $request->nomorreferensi,
                    "nomorantrean" => $noAntrianRegistrasi,
                    "angkaantrean" => $data->noantrian,
                    "estimasidilayani" => strtotime(date($data->tglregistrasi)) * 1000,
                    "sisakuotajkn" => $jmlsisakouta,
                    "kuotajkn" => $jmlkouta,
                    "sisakuotanonjkn" => $jmlsisakouta,
                    "kuotanonjkn" => $jmlkouta,
                    "keterangan" => "Peserta harap 30 menit lebih awal guna pencatatan administrasi.",
                );

                //var_dump($jsonSend);
                $kirimAntrean = $this->buildRequestbpjstools("antrean/add", "antrean", "POST", $jsonSend);
                if (isset($kirimAntrean["metaData"])) {
                    // simpan log
                    $this->LOGGING(
                        'Antrian Online',
                        $request->noregistrasifk,
                        'pasiendaftar_t',
                        'Tambah Antrean dengan kode ' . $data->kodebooking,
                        $jsonSend,
                        $kirimAntrean
                    );

                    $monitoring = MonitoringTaksId::where('noregistrasifk', $request->noregistrasifk)
                        ->where('kdprofile', $kdProfile)
                        ->where('statusenabled', true)
                        ->update([
                            'dataantrean' => json_encode($jsonSend)
                        ]);

                    $result = array(
                        "metadata" => array(
                            "message" => $kirimAntrean["metaData"]["message"],
                            "code" => $kirimAntrean["metaData"]["code"],
                        )
                    );
                }
            } else {
                $result = array(
                    "metadata" => array(
                        "message" => 'Data Sudah dikirim',
                        "code" => 200,
                    )
                );
            }
        }
        return response()->json($result, $result['metadata']['code']);
    }

    public function sendTaskId(Request $request)
    {
        // date_default_timezone_set(config('Asia/Jakarta'));
        $kdProfile = $this->kdProfile;
        $data = DB::table("pasiendaftar_t as pd")
            ->leftJoin("antrianpasienregistrasi_t as apr", "apr.norec", "=", "pd.antrianpasienregistrasifk")
            ->select(
                "pd.*",
                DB::raw('case when pd.antrianpasienregistrasifk != null then apr.noreservasi else pd.noregistrasi end as kodebooking'),
            )
            ->where("pd.norec", $request->noregistrasifk)
            ->where("pd.statusenabled", true)
            ->where("pd.kdprofile", $kdProfile)
            ->first();

        $kodebooking = $data->kodebooking;
        $jsonSend = array(
            "kodebooking" => $kodebooking,
            "taskid" => $request->taskid,
            "waktu" => $request->waktu,
            // "jenisresep" => "Tidak ada/Racikan/Non racikan" //---> khusus yang sudah implementasi antrean farmasi
        );
        $sendWaktu = $this->buildRequestbpjstools("antrean/updatewaktu", "antrean", "POST", $jsonSend);

        $statusKirim = false;
        if (isset($sendWaktu["metaData"])) {
            if ($sendWaktu["metaData"]["code"] == 200) {
                $statusKirim = true;
                $this->LOGGING(
                    'Antrian Online',
                    $request->noregistrasifk,
                    'pasiendaftar_t',
                    'Update waktu task id ' . $request->taskid . ' dengan kode ' . $kodebooking
                );
            }
        }

        $objetoRequest2 = new Request();
        $objetoRequest2['noregistrasifk'] = $request->noregistrasifk;
        $objetoRequest2['taskid'] = $request->taskid;
        $objetoRequest2['waktu'] = $request->waktu;
        $objetoRequest2['statuskirim'] = $statusKirim;
        $objetoRequest2['user'] = $this->getNamaPegawai();
        $this->saveMonitoringTaksId($objetoRequest2);

        return response()->json($sendWaktu, $sendWaktu["metaData"]["code"]);
    }

    public function getMonitoringWaktu(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $deptJalan = $this->settingFix('kdDepartemenPoli');
        $idKelBPJS = (int) $this->settingFix('idKelompokPasienBPJS');
        $tglawal = $request->tglAwal;
        $tglakhir = $request->tglAkhir;

        $nocm = "";
        if (isset($request->norm) && $request->norm != '' && $request->norm != null) {
            $nocm = " and ps.nocm = '" . $request->norm . "'";
        }
        $nama = "";
        if (isset($request->nama) && $request->nama != '' && $request->nama != null) {
            $nama = " and ps.namapasien ilike '%" . $request->nama . "%'";
        }
        $ruangId = "";
        if (isset($request->ruangId) && $request->ruangId != '' && $request->ruangId != null) {
            $ruangId = " and rm.id = '" . $request->ruangId . "'";
        }
        $kdBooking = "";
        if (isset($request->kdBooking) && $request->kdBooking != '' && $request->kdBooking != null) {
            $kdBooking = " and sx.noregistrasi = '" . $request->kdBooking . "'";
        }

        $data = DB::select(DB::raw("
        select sx.* ,string_agg(lg.keterangan, ' | ' ) as log from (
            select
            ps.nocm as norm,
            case when pd.ismobilejkn = true then pd.noreservasi else pd.noregistrasi end as noregistrasi,
            mt.noregistrasifk,
            pd.tglregistrasi,
            ps.namapasien,
            rm.namaruangan,
            mt.taskid,
            mt.waktu,
            mt.statuskirim,
            mt.dataantrean,
            row_number() over(PARTITION BY mt.noregistrasifk,mt.taskid ORDER BY taskid) as nomor
            from monitoringtaskid_t mt
            inner join pasiendaftar_t pd on pd.norec = mt.noregistrasifk
            inner join ruangan_m rm on rm.id = pd.objectruanganlastfk
            inner join pasien_m ps on ps.id = pd.nocmfk
            left join kelompokpasien_m kp on kp.id = pd.objectkelompokpasienlastfk
            left join antrianpasienregistrasi_t apr on apr.nocmfk = pd.nocmfk
            and apr.objectruanganfk=pd.objectruanganlastfk
            and to_char(pd.tglregistrasi,'yyyy-MM-dd') =  to_char(apr.tanggalreservasi,'yyyy-MM-dd')
            where pd.statusenabled = true
            and rm.kdsubspesialisbpjs <> 'HDL'
            and rm.objectdepartemenfk = $deptJalan
            and pd.tglregistrasi between '$tglawal' and '$tglakhir'
            and pd.objectkelompokpasienlastfk = $idKelBPJS
            and case when pd.ismobilejkn = true then pd.ischeckin = true else pd.ischeckin is null end
            $nocm
            $nama
            $ruangId
        ) sx
        left join logginguser_t lg on lg.noreff = sx.noregistrasi
        where sx.nomor = 1
        $kdBooking

        GROUP BY sx.norm, sx.noregistrasi, sx.tglregistrasi, sx.namapasien, sx.namaruangan, sx.noregistrasifk, sx.taskid,sx.waktu,
        sx.statuskirim,sx.dataantrean,sx.nomor
        ORDER BY sx.tglregistrasi asc
        "));

        $dataGROUP = [];
        foreach ($data as $item) {
            $status_1 = false;
            $taksid_1 = null;
            $status_2 = false;
            $taksid_2 = null;
            $status_3 = false;
            $taksid_3 = null;
            $status_4 = false;
            $taksid_4 = null;
            $status_5 = false;
            $taksid_5 = null;
            $status_6 = false;
            $taksid_6 = null;
            $status_7 = false;
            $taksid_7 = null;
            $sama = false;
            $i = 0;
            foreach ($dataGROUP as $item2) {
                if ($item->noregistrasi == $dataGROUP[$i]['noregistrasi']) {
                    $sama = true;
                    if ($item->taskid == 1) {
                        if($item->waktu != null){
                            $dataGROUP[$i]['status_1'] = true;
                        }
                        //$dataGROUP[$i]['status_1'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_1'] = $item->waktu;
                    }
                    if ($item->taskid == 2) {
                        if($item->waktu != null){
                            $dataGROUP[$i]['status_2'] = true;
                        }
                        //$dataGROUP[$i]['status_2'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_2'] = $item->waktu;
                    }
                    if ($item->taskid == 3) {
                        if($item->waktu != null){
                            $dataGROUP[$i]['status_3'] = true;
                        }
                        //$dataGROUP[$i]['status_3'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_3'] = $item->waktu;
                    }
                    if ($item->taskid == 4) {
                        if($item->waktu != null){
                            $dataGROUP[$i]['status_4'] = true;
                        }
                        //$dataGROUP[$i]['status_4'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_4'] = $item->waktu;
                    }
                    if ($item->taskid == 5) {
                        if($item->waktu != null){
                            $dataGROUP[$i]['status_5'] = true;
                        }
                        //$dataGROUP[$i]['status_5'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_5'] = $item->waktu;
                    }
                    if ($item->taskid == 6) {
                        if($item->waktu != null){
                            $dataGROUP[$i]['status_6'] = true;
                        }
                        //$dataGROUP[$i]['status_6'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_6'] = $item->waktu;
                    }
                    if ($item->taskid == 7) {
                        if($item->waktu != null){
                            $dataGROUP[$i]['status_7'] = true;
                        }
                        //$dataGROUP[$i]['status_7'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_7'] = $item->waktu;
                    }
                    if ($item->dataantrean != null)
                        $dataGROUP[$i]['dataantrean'] = json_decode($item->dataantrean);
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                if ($item->taskid == 1) {
                    if($item->waktu != null){
                        $status_1 = true;
                    }
                    $taksid_1 = $item->waktu;
                }
                if ($item->taskid == 2) {
                    if($item->waktu != null){
                        $status_2 = true;
                    }
                    $taksid_2 = $item->waktu;
                }
                if ($item->taskid == 3) {
                    if($item->waktu != null){
                        $status_3 = true;
                    }
                    $taksid_3 = $item->waktu;
                }
                if ($item->taskid == 4) {
                    if($item->waktu != null){
                        $status_4 = true;
                    }
                    $taksid_4 = $item->waktu;
                }
                if ($item->taskid == 5) {
                    if($item->waktu != null){
                        $status_5 = true;
                    }
                    $taksid_5 = $item->waktu;
                }
                if ($item->taskid == 6) {
                    if($item->waktu != null){
                        $status_6 = true;
                    }
                    $taksid_6 = $item->waktu;
                }
                if ($item->taskid == 7) {
                    if($item->waktu != null){
                        $status_7 = true;
                    }
                    $taksid_7 = $item->waktu;
                }
                $dataGROUP[] = array(
                    'norm' => $item->norm,
                    'noregistrasi' => $item->noregistrasi,
                    'tglregistrasi' => $item->tglregistrasi,
                    'namapasien' =>  $item->namapasien,
                    'namaruangan' => $item->namaruangan,
                    'noregistrasifk' => $item->noregistrasifk,
                    'status_1' => $status_1,
                    'status_2' => $status_2,
                    'status_3' => $status_3,
                    'status_4' => $status_4,
                    'status_5' => $status_5,
                    'status_6' => $status_6,
                    'status_7' => $status_7,
                    'taksid_1' => $taksid_1,
                    'taksid_2' => $taksid_2,
                    'taksid_3' => $taksid_3,
                    'taksid_4' => $taksid_4,
                    'taksid_5' => $taksid_5,
                    'taksid_6' => $taksid_6,
                    'taksid_7' => $taksid_7,
                    'dataantrean' => $item->dataantrean,
                    'log' => $item->log,
                );
            }
        }

        $dataGROUP = json_decode(json_encode($dataGROUP));
        foreach ($dataGROUP as $item) {
            $message = '';
            if ($item->log != null) {
                $log = explode("|", $item->log);

                $logmes = '';
                if (count($log) > 0) {
                    foreach ($log as $l => $vv) {
                        $logs = json_decode($vv);
                        if (isset($logs->metaData) && isset($logs->metaData->message)) {
                            $message = $message . ',' . $logs->metaData->message;
                        }
                    }
                }
            }

            $item->log =  substr($message, 1, strlen($message) - 1);
            if ($item->log == false) {
                $item->log = '';
            }
            $item->taksid_1 = $item->taksid_1 == null ? "-" : date('Y-m-d H:i', $item->taksid_1 / 1000);
            $item->taksid_2 = $item->taksid_2 == null ? "-" : date('Y-m-d H:i', $item->taksid_2 / 1000);
            $item->taksid_3 = $item->taksid_3 == null ? "-" : date('Y-m-d H:i', $item->taksid_3 / 1000);
            $item->taksid_4 = $item->taksid_4 == null ? "-" : date('Y-m-d H:i', $item->taksid_4 / 1000);
            $item->taksid_5 = $item->taksid_5 == null ? "-" : date('Y-m-d H:i', $item->taksid_5 / 1000);
            $item->taksid_6 = $item->taksid_6 == null ? "-" : date('Y-m-d H:i', $item->taksid_6 / 1000);
            $item->taksid_7 = $item->taksid_7 == null ? "-" : date('Y-m-d H:i', $item->taksid_7 / 1000);
        }

        $result = array(
            "response" => $dataGROUP,
            "metaData" => array(
                "message" => "Ok",
                "code" => 200,
            )
        );

        return response()->json($result, $result["metaData"]["code"]);
    }

    public function ambilWaktudiKiosk(Request $request)
    {
        $kdProfile = $this->kdProfile;
        date_default_timezone_set(config('app.timezone')); // set timezone

        $tglAwal = date('Y-m-d', strtotime($request->tanggal)) . ' 00:00:00';
        $tglAkhir = date('Y-m-d', strtotime($request->tanggal)) . ' 23:59:59';

        $data = DB::table("antrianpasienregistrasi_t as apr")
            ->where("apr.nobpjs", $request->nokartu)
            ->whereBetween("apr.tanggalreservasi", [$tglAwal, $tglAkhir])
            ->first();

        if (empty($data)) {
            $result = array(
                "response" => array(
                    "waktutaksid1" => strtotime(date('Y-m-d H:i:s') . ' -' . rand(1, 15) . ' minutes') * 1000,
                    "waktutaksid2" => strtotime(date('Y-m-d H:i:s')) * 1000,
                ),
                "metaData" => array(
                    "message" => "Ok",
                    "code" => 200,
                )
            );
        } else {
            $result = array(
                "response" => array(
                    "waktutaksid1" => strtotime(date($data->tanggalreservasi)) * 1000,
                    "waktutaksid2" => $data->tglinput == null ? strtotime(date('Y-m-d H:i:s')) * 1000 : strtotime(date($data->tglinput)) * 1000,
                ),
                "metaData" => array(
                    "message" => "Ok",
                    "code" => 200,
                )
            );
        }
        return response()->json($result, $result["metaData"]["code"]);
    }

    public function getComboMonitoring(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $kdDepartemenPoli = (int) $this->settingFix('kdDepartemenPoli');
        $idJenisPegawaiDokter = (int) $this->settingFix('idJenisPegawaiDokter');

        $dataRuangan = Ruangan::mine()
            ->select("id", "namaruangan", "kdspesialisbpjs", "kdsubspesialisbpjs")
            ->where('objectdepartemenfk', $kdDepartemenPoli)
            ->whereNotNull('kdsubspesialisbpjs')
            ->get();
        $dataDokter = Pegawai::mine()
            ->select("id", "namalengkap", "kddokterbpjs")
            ->where('objectjenispegawaifk', $idJenisPegawaiDokter)
            ->whereNotNull('kddokterbpjs')
            ->get();

        $result = array(
            "response" => array(
                "ruangan" => $dataRuangan,
                "dokter" => $dataDokter,
            ),
            "metaData" => array(
                "message" => "Ok",
                "code" => 200,
            )
        );
        return response()->json($result, $result["metaData"]["code"]);
    }

    public function getDataAntrean(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec_pd = $request['norec_pd'];
        date_default_timezone_set(config('app.timezone')); // set timezone

        $data = collect(DB::select("
            select case when pd.ismobilejkn = true then pd.noreservasi else pd.noregistrasi end as kodebooking
            ,case when pd.objectkelompokpasienlastfk = 2 then 'JKN' else 'NON JKN' end as jenispasien
            ,case when pd.objectkelompokpasienlastfk = 2 then ps.nobpjs else '' end as nomorkartu
            ,ps.noidentitas as nik
            ,case when ps.notelepon is null then '-' else ps.notelepon end as nohp
            ,rm.kdspesialisbpjs
            ,rm.kdsubspesialisbpjs as kodepoli
            ,rm.namaruangan as namapoli
            ,case when pd.statuspasien = 'BARU' then 1 else 0 end as pasienbaru
            ,ps.nocm as norm
            ,to_char(pd.tglregistrasi, 'yyyy-MM-dd') as tanggalperiksa
            ,pd.tglregistrasi
            ,case when pa.dpjplayan_kode is null then pg.kddokterbpjs else pa.dpjplayan_kode end as kodedokter
            ,case when pa.dpjplayan_nama is null then pg.namalengkap else pa.dpjplayan_nama end as namadokter
            ,'08:00-16:00' as jampraktek
            ,pa.norujukan
            ,case 
                when pa.asalrujukan = '1' then 1
                when pa.asalrujukan = '2' then 4
                when pa.nosurat is not null then 3
                when pa.isrujukinternal = true then 2
            end as jeniskunjungan
            ,case when pa.nosurat is not null then pa.nosurat else pa.norujukan end as nomorreferensi
            ,rm.noruangan as nomorantrean
            ,apd.noantrian as angkaantrean
            from pasiendaftar_t pd
            inner join antrianpasiendiperiksa_t apd on apd.noregistrasifk = pd.norec
            inner join pasien_m ps on ps.id = pd.nocmfk
            inner join ruangan_m rm on rm.id = pd.objectruanganlastfk
            inner join pegawai_m pg on pg.id = pd.objectpegawaifk
            left join pemakaianasuransi_t pa on pa.noregistrasifk = pd.norec
            where pd.norec = '$norec_pd'
        "))->first();

        $results = [];
        if (!empty($data)) {
            $pcare = $this->buildRequestbpjstools("Rujukan/" . $data->norujukan, "", "GET", null);
            if (isset($pcare["metaData"])) {
                if ($pcare["metaData"]["code"] == 200) {
                    $data->jeniskunjungan = 1;
                    $data->nomorreferensi = $data->norujukan;
                    if ($data->kdspesialisbpjs != $pcare['response']['rujukan']['poliRujukan']['kode']) {
                        $data->jeniskunjungan = 2;
                        $data->nomorreferensi = $data->norujukan;
                    }
                } else {
                    $rs = $this->buildRequestbpjstools("Rujukan/RS/" . $data->norujukan, "", "GET", null);
                    if (isset($rs["metaData"])) {
                        if ($rs["metaData"]["code"] == 200) {
                            $data->jeniskunjungan = 4;
                            $data->nomorreferensi = $data->norujukan;

                            if ($data->kdspesialisbpjs != $rs['response']['rujukan']['poliRujukan']['kode']) {
                                $data->jeniskunjungan = 2;
                                $data->nomorreferensi = $data->norujukan;
                            }
                        }
                    }
                }
            }

            $results = array(
                'kodebooking' => $data->kodebooking,
                'jenispasien' => $data->jenispasien,
                'nomorkartu' => $data->nomorkartu,
                'nik' => $data->nik,
                'nohp' => $data->nohp,
                'kodepoli' => $data->kodepoli,
                'namapoli' => $data->namapoli,
                'pasienbaru' => $data->pasienbaru,
                'norm' => $data->norm,
                'tanggalperiksa' => $data->tanggalperiksa,
                'kodedokter' => $data->kodedokter,
                'namadokter' => $data->namadokter,
                'jampraktek' => $data->jampraktek,
                'jeniskunjungan' => $data->jeniskunjungan,
                'nomorreferensi' => $data->nomorreferensi,
                'nomorantrean' => $data->nomorantrean . "-" . str_pad((int)$data->angkaantrean, 3, "0", STR_PAD_LEFT),
                'angkaantrean' => $data->angkaantrean,
                'estimasidilayani' => strtotime(date($data->tglregistrasi)) * 1000,
                'sisakuotajkn' => 0,
                'kuotajkn' => 0,
                'sisakuotanonjkn' => 0,
                'kuotanonjkn' => 0,
                'keterangan' => ""
            );
        }

        $result = array(
            "response" => $results,
            "metaData" => array(
                "message" => "Ok",
                "code" => 200,
            )
        );
        return response()->json($result, $result["metaData"]["code"]);
    }

    public function updateDataAntrean(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $monitoring = MonitoringTaksId::where('noregistrasifk', $request->noregistrasifk)
                ->where('kdprofile', $kdProfile)
                ->where('statusenabled', true)
                ->update([
                    'dataantrean' => json_encode($request->json)
                ]);

            $transStatus = true;
            $transMessage = "Ok";
        } catch (Exception $e) {
            $transStatus = false;
            $transMessage = "Terjadi Kesalahan";
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 200,
                ),
            );
        } else {
            DB::rollBack();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 201,
                ),
            );
        }
        return response()->json($result, $result['metadata']['code']);
    }
    // END Area fungsi kirim taks id
}
