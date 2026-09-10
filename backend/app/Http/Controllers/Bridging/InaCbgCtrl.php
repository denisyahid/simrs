<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\AsuransiPasien;
use App\Models\Master\Departemen;
use App\Models\Master\INACBG_Status;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\Apgar;
use App\Models\Transaksi\Dializer;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\MonitoringDokKlaim;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PemakaianAsuransi;
use App\Models\Transaksi\Persalinan;
use App\Models\Transaksi\PersalinanDetail;
use App\Models\Transaksi\BundleKlaim;
use App\Traits\Valet;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use zys\Chumper\Zipper\ZipperFacade as ZipperFacade;
use ZipStream\ZipStream;
use ZipStream\Option\Archive as ArchiveOptions;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InaCbgCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function dropDownINACBG(Request $r)
    {
        $res['kelompokpasien'] =  KelompokPasien::mine()->get();
        $res['departemen'] = Departemen::mine()->get()->toArray();
        $res['idKelompokPasienBPJS'] = explode(',', $this->settingFix('idKelompokPasienBPJS'));
        $res['idKelompokPasienCOB'] = explode(',', $this->settingFix('idKelompokPasienCOB'));
        $res['inacbg_status'] = INACBG_Status::mine()->get();


        $ru = Ruangan::mine()->get();
        foreach ($res['departemen']  as $k => $d) {
            $res['departemen'][$k]['ruangan'] = [];
            foreach ($ru  as $dd) {
                if ($dd->objectdepartemenfk == $d['id']) {
                    $res['departemen'][$k]['ruangan'][] = $dd;
                }
            }
        }
        return $this->respond($res);
    }
    public function kunjungansebelumnyaINACBG(Request $r)
    {
        $nocmfk = $r['nocmfk'];
        $tglregistrasi = $r['tglregistrasi'];
        $nosep = $r['nosep'];

        $res['data'] = DB::select(DB::raw("select * from
        pasiendaftar_t as pd
        inner join pemakaianasuransi_t as pa on pa.noregistrasifk = pd.norec
        where pd.nocmfk = '$nocmfk'
        and pd.statusenabled = true
        and pd.tglregistrasi::date < '$tglregistrasi'::date
        and pa.nosep = '$nosep'
        order by pd.tglregistrasi desc limit 1"));

        return $this->respond($res);
    }
    public function saveBridgingINACBGKlaimPrint(Request $request){
        $kdProfile = $this->kdProfile;

        $kdProfile = 1;
        $norec_pd = $request['norec_pd'];
        $link = $request['link'];
        $dataRegistrasi = PasienDaftar::where('norec', $request['norec_pd'])->first();
        $dataPasien = Pasien::where('id', $dataRegistrasi->nocmfk)->first();
        $namafile = 'klaim_'.$dataRegistrasi->noregistrasi.'.pdf';
        $path = 'dokumen_klaim/'.$dataRegistrasi->noregistrasi."/".$namafile;
        $waktu = time();

        $dataInsert = array(
            "norec" => Uuid::uuid4(),
            "kdprofile" => $kdProfile,
            "statusenabled" => true,
            "filename" => $namafile,
            "filepath" => $path,
            "nocmfk" => $dataRegistrasi->nocmfk,
            "tglregistrasi" => $dataRegistrasi->tglregistrasi,
            "noregistrasifk" => $dataRegistrasi->norec,
            "documentklaimfk" => 14,
        );


        DB::table('monitoringdokklaim_t')->insert($dataInsert);

        $public_path = storage_path('app/public/'.$path);
        // \File::makeDirectory($savePath, 0755, true, true);
        // $public_path = $savePath."/".$namafile;

        // var_dump($dataRegistrasi->cetakanklaim);

        file_put_contents($public_path, base64_decode($link));

        // $request->fPut(
        //     $public_path,
        //     base64_decode($link),
        //     'public'
        // );

        return;

    }
    public function saveBridgingINACBG(Request $request,$local = false)
    {
        $kdProfile = $this->kdProfile;
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }


        $dataReq = $request['data'];
        $responseArr = [];

        foreach ($dataReq as $dataLoop) {
            $json_request = json_encode($dataLoop);
            $payload = $this->inacbg_encrypt($json_request, $key);
            $header = array("Content-Type: application/x-www-form-urlencoded");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            if ($err) {
                return $this->setStatusCode(400)->respond($err, $err);
            }
            $first  = strpos($response, "\n") + 1;
            $last   = strrpos($response, "\n") - 1;
            $response  = substr(
                $response,
                $first,
                strlen($response) - $first - $last
            );
            $response = $this->inacbg_decrypt($response, $key);
            $responseArr[] = array(
                'datarequest' => $dataLoop,
                'dataresponse' =>   $response
            );
        }
        $result = array(
            "status" => 200,
            "dataresponse" => $responseArr,
            "as" => '@epic',
        );
        if($local){
            return $result;
        }
        return $this->respond($result, $result['status'], "Bridging InaCBG");

        // return $this->setStatusCode($result['status'])->respond($result, "Bridging InaCBG");
    }
    // Encryption Function
    function inacbg_encrypt($data, $key)
    {
        /// make binary representasion of $key
        $key = hex2bin($key);
        /// check key length, must be 256 bit or 32 bytes
        if (mb_strlen($key, "8bit") !== 32) {
            throw new Exception("Needs a 256-bit key!");
        }
        /// create initialization vector
        $iv_size = openssl_cipher_iv_length("aes-256-cbc");
        $iv = openssl_random_pseudo_bytes($iv_size);
        // dengan catatan dibawah
        /// encrypt
        $encrypted = openssl_encrypt(
            $data,
            "aes-256-cbc",
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        /// create signature, against padding oracle attacks
        $signature = mb_substr(hash_hmac(
            "sha256",
            $encrypted,
            $key,
            true
        ), 0, 10, "8bit");
        /// combine all, encode, and format
        $encoded = chunk_split(base64_encode($signature . $iv . $encrypted));
        return $encoded;
    }
    // Decryption Function
    function inacbg_decrypt($str, $strkey)
    {
        /// make binary representation of $key
        $key = hex2bin($strkey);
        /// check key length, must be 256 bit or 32 bytes
        if (mb_strlen($key, "8bit") !== 32) {
            throw new Exception("Needs a 256-bit key!");
        }
        /// calculate iv size
        $iv_size = openssl_cipher_iv_length("aes-256-cbc");
        /// breakdown parts
        $decoded = base64_decode($str);
        $signature = mb_substr($decoded, 0, 10, "8bit");
        $iv = mb_substr($decoded, 10, $iv_size, "8bit");
        $encrypted = mb_substr($decoded, $iv_size + 10, NULL, "8bit");
        /// check signature, against padding oracle attack
        $calc_signature = mb_substr(hash_hmac(
            "sha256",
            $encrypted,
            $key,
            true
        ), 0, 10, "8bit");
        if ($this->inacbg_compare($signature, $calc_signature)) {
            //            return "SIGNATURE_NOT_MATCH"; /// signature doesn't match
        }
        $decrypted = openssl_decrypt(
            $encrypted,
            "aes-256-cbc",
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $dtdtd = json_decode($decrypted);
        return $dtdtd;
    }
    /// Compare Function
    function inacbg_compare($a, $b)
    {
        /// compare individually to prevent timing attacks
        /// compare length
        if (strlen($a) !== strlen($b)) return false;
        /// compare individual
        $result = 0;
        for ($i = 0; $i < strlen($a); $i++) {
            $result |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $result == 0;
    }
    public function daftarPasienINACBGBackup(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $depRI =  $this->settingFix('kdDepartemenRanapFix');
        $depIGD =   $this->settingFix('idDepartemenIGD');
        $deptRanap = explode(',', $depRI);
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();
        $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile);
        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $dataMaster = $dataMaster->where('objectdepartemenfk', '=', $request['deptId']);
        };
        $dataMaster = $dataMaster->orderBy('nourut');
        $dataMaster = $dataMaster->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->leftjoin('persalinan_t as prs', 'prs.norecpd', '=', 'pd.norec')
            ->leftjoin('apgar_t as apg', 'apg.norecpd', '=', 'pd.norec')
            ->leftjoin('dializer_t as dz', 'dz.norecpd', '=', 'pd.norec')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                DB::raw("case when dept.id <> 16 and (pd.tglpulang is null or pd.tglpulang > pd.tglregistrasi) then pd.tglregistrasi else pd.tglpulang end as tgl_pulang"),
                'pd.tglmeninggal',
                'pd.tglverifklaim',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien as nama_pasien',
                'kp.kelompokpasien',
                'pd.statuspasien',
                'pd.statusverifikasi',
                'pg.id as pgid',
                'pg.namalengkap as nama_dokter',
                'kp.id as kpid',
                'pd.objectruanganlastfk as ruanganid',
                'pas.nosep as nomor_sep',
                'pas.norec as norec_pa',
                'ps.nobpjs as nomor_kartu',
                'ps.tgllahir as tgl_lahir',
                'ps.objectjeniskelaminfk as gender',
                'dept.id as deptid',
                'kls.kodebpjs as kelas_rawat',
                'kls.namabpjs as kelas_rawat_nama',
                'pas.klsrawathak_kode as kelas_dijamin',
                'kls.reportdisplay as namakelasdaftar',
                'pas.klsrawathak_nama as namakelas',
                'pd.objectstatuspulangfk',
                'ps.beratbadan',
                'rk.id as idrekanan',
                'rk.namarekanan',
                'ic.status as inacbg_status',
                'pd.inacbg_totaltarifrs',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_biayanaikkelas',
                'pas.statuscovid',
                'ps.noidentitas',
                'jk.jeniskelamin',
                'asa.inacbg_kode',
                'sp.inacbg_kode as discharge_status',
                'pd.nocmfk',
                'pd.inacbg_topup',
                'pd.sitb_id',
                'pd.kemkes_dc_status',
                'prs.usia_kehamilan',
                'prs.gravida',
                'prs.partus',
                'prs.abortus',
                'prs.onsetkontraksi',
                'prs.kodeonsetkontraksi',
                'apg.appearance',
                'apg.pulse',
                'apg.grimace',
                'apg.activity',
                'apg.respiration',
                'apg.appearance5',
                'apg.pulse5',
                'apg.grimace5',
                'apg.activity5',
                'apg.respiration5',
                'pd.isPenunjangKhusus',
                'pd.isPenunjangSusulan',
                'pd.isPenunjangSusulanRad',
                'pd.ketverifikasi',
                'dz.dializer_single_use',
                'dz.kantong_darah',
                DB::raw("case when pd.jenispelayanan = 2 then true else false end as eksekutif"),

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 0) {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 0) {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 1) {
            $data = $data->where('pd.tglpulang', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 1) {
            $data = $data->where('pd.tglpulang', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('dept.id', '=', $r['ins']);
        }
        if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 0) {
            $data = $data->whereNotIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 1) {
            $data = $data->whereIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 2) {
            $data = $data->whereIn('dept.id', [9]);
        }
        if (isset($r['ruangId']) && $r['ruangId'] != "" && $r['ruangId'] != "undefined") {
            $data = $data->where('ru.id', '=', $r['ruangId']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->whereIn('ru.id', explode(',', $r['ruang']));
        }
        if (isset($r['inacbg_status']) && $r['inacbg_status'] != "" && $r['inacbg_status'] != "undefined") {
            if($r['inacbg_status'] == "belum_kirim"){
                $data = $data->whereNull('pd.inacbg_status');
            }else{
                $data = $data->where('pd.inacbg_status', '=', $r['inacbg_status']);
            }
        }
        if (isset($r['isNotSEP']) && $r['isNotSEP'] != "" && $r['isNotSEP'] != "undefined" && $r['isNotSEP'] =="true") {
            $data = $data->whereRaw("(pas.nosep is null or pas.nosep  ='')");
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] =="true") {
            $data = $data->whereNull('pd.inacbg_status');
        }
        if (isset($r['isNotVerifikasi']) && $r['isNotVerifikasi'] != "" && $r['isNotVerifikasi'] != "undefined" && $r['isNotVerifikasi'] =="true") {
            $data = $data->whereNull('pd.tglverifklaim');
        }
        if (isset($r['isCatatan']) && $r['isCatatan'] != "" && $r['isCatatan'] != "undefined" && $r['isCatatan'] =="true") {
            $data = $data->whereNotNull('pd.tglverifklaim')->where('pd.ketverifikasi', '!=', '-');
        }
        if (isset($r['isPenunjang']) && $r['isPenunjang'] != "" && $r['isPenunjang'] != "undefined" && $r['isPenunjang'] =="true") {
            $data = $data->whereIn('ru.objectdepartemenfk', [3,27]);
        }
        $kelPasien  = '';
        if (isset($r['kelId']) && $r['kelId'] != "" && $r['kelId'] != "undefined") {
            $arrKel = explode(',', $r['kelId']);
            $kodeKel = [];
            foreach ($arrKel as $item) {
                $kodeKel[] = (int) $item;
            }
            $kelPasien = ' and kp.id in (' . $r['kelId'] . ')';
            $data = $data->whereIn('kp.id', $kodeKel);
        } else {
            // $data = $data->whereIn('kp.id',  explode(',',$   kelompokPasienINACBG));

        }
        if (isset($r['dokId']) && $r['dokId'] != "" && $r['dokId'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokId']);
        }
        if (isset($r['status_pasien']) && $r['status_pasien'] != "" && $r['status_pasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['status_pasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['nosep']) && $r['nosep'] != "" && $r['nosep'] != "undefined") {
            $data = $data->where('pas.nosep', '=', $r['nosep']);
        }
        if (isset($r['status']) && $r['status'] != "" && $r['status'] != "undefined") {
            $data = $data->where('pd.statusklaim', '=', $r['status']);
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $data = $data->whereNotNull('pd.tglpulang');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('rk.namarekanan', 'ilike', $searchTerm)
                    ->orWhere('pas.nosep', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '' ) {
            $page = $r['page'];
        }

        $data = $data->orderBy('pd.noregistrasi');
        // $data = $data->get();
        $data = $data ->paginate(isset($r['limit'])?$r['limit']: 10, ['*'], 'page', $page);

        // var_dump($data);

        $data2 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                DB::raw("string_agg(ru.namaruangan, ', \n') as ruangankonsul"),
                'ps.namapasien as nama_pasien',
                'pd.statuspasien',
                'apd.iskonsul',
                'pd.objectruanganlastfk as ruanganid',
            )
            ->where('pd.statusenabled', true)
            ->where('apd.iskonsul', true)
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data2 = $data2->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data2 = $data2->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data2 = $data2->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data2 = $data2->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data2 = $data2->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', '=', $r['norec_pd']);
        }

        $data2 = $data2->groupBy('pd.norec', 'pd.tglregistrasi', 'pd.tglpulang', 'ps.nocm',
        'pd.noregistrasi', 'ps.namapasien', 'pd.statuspasien', 'apd.iskonsul', 'pd.objectruanganlastfk');
        $data2 = $data2->get();

        $data3 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('strukorder_t AS so', 'pd.norec', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'pd.tglmeninggal',
                'pd.tglverifklaim',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan as ruangantujuan',
                'ru.objectdepartemenfk'
            )
            ->where('pd.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', [3, 27])
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data3 = $data3->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data3 = $data3->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data3 = $data3->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data3 = $data3->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data3 = $data3->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data3 = $data3->where('pd.norec', '=', $r['norec_pd']);
        }

        $data3 = $data3->get();

        $noregpenunjang = null;
        $dataPenunjang = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('strukorder_t AS so', 'pd.norec', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'pd.tglmeninggal',
                'pd.tglverifklaim',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan as ruangantujuan',
                'ru.objectdepartemenfk'
            )
            ->where('pd.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', [3, 27])
            ->where('pas.nosep', $data[0]->nomor_sep)
            ->where('pd.kdprofile', $kdProfile);

        $dataPenunjang = $dataPenunjang->first();

        if(!empty($dataPenunjang)){
            $noregpenunjang = $dataPenunjang->noregistrasi;
        }

        $data4 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi', 'ru.id as idruangankonsul',
                'ps.namapasien as nama_pasien',
                'pd.statuspasien',
                'apd.iskonsul',
                'pd.objectruanganlastfk as ruanganid',
            )
            ->where('pd.statusenabled', true)
            ->where('apd.iskonsul', true)
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data4 = $data4->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data4 = $data4->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data4 = $data4->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data4 = $data4->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data4 = $data4->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data4 = $data4->where('pd.norec', '=', $r['norec_pd']);
        }

        $data4 = $data4->get();

        // var_dump($data3);

        $dataDokKlaim = DB::table("monitoringdokklaim_t")
            // ->where("noregistrasifk", $value->norec)
            ->where("kdprofile", $kdProfile)
            ->where("statusenabled", true);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        $dataDokKlaim = $dataDokKlaim->get();

        $norec_APD = '';
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            try {
                $APD = AntrianPasienDiperiksa::where('noregistrasifk', $r['norec_pd'])
                    ->where('objectruanganfk', $data[0]->ruanganid)
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();
            } catch (Exception $ee) {
            }
        }

        // ini comment
        $i = 0;
        $dtdt = '';

        $dataDiagnosa = DB::table('detaildiagnosapasien_t as dp')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'dp.objectdiagnosafk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('dg.kddiagnosa', 'apd.objectasalrujukanfk', 'pd.norec','dp.objectjenisdiagnosafk')
            ->wherein('dp.objectjenisdiagnosafk', explode(',', $this->settingFix('jenisDiagnosaINACBG')))

            ->where('pd.kdprofile', $kdProfile)
            ->orderBy('dp.objectjenisdiagnosafk', 'asc');
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.norec',  $r['norec_pd']);
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] =="true") {
            $dataDiagnosa = $dataDiagnosa->whereNull('dp.objectdiagnosafk');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dataDiagnosa = $dataDiagnosa->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.namapasien', 'ilike', $searchTerm);
            });
        }
        $dataDiagnosa = $dataDiagnosa->get();

        // ini batas comment

        // dd($dataDokKlaim);
        foreach ($data as $item) {

            // ini comment
            $dtdt = '';
            $asalRujukan = '';
            $covid19_status_cd = '';
            foreach ($dataDiagnosa as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' .  $item2->kddiagnosa;
                    $asalRujukan = $item2->objectasalrujukanfk;
                }
            }
            // ini batas comment
            $res = DB::connection('mongodb')
            ->table('RingkasanKeluar')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res2 = DB::connection('mongodb')
            ->table('AsesmenMedisRawatJalan')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res3 = DB::connection('mongodb')
            ->table('AsesmenAwalKeperawatanPasienRawatJalan')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res4 = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('nocmfk', $item->nocmfk)
            ->where('created_at', '>=', date('Y-m-d', strtotime($item->tgl_masuk)).' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d', strtotime($item->tgl_masuk)).' 23:59:59')
            ->where('flag', 'dokter')
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res5 = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('nocmfk', $item->nocmfk)
            ->where('created_at', '>=', date('Y-m-d', strtotime($item->tgl_masuk)).' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d', strtotime($item->tgl_masuk)).' 23:59:59')
            ->where('statusenabled', true)
            ->where('flag', 'perawat')
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res6 = DB::connection('mongodb')
            ->table('CatatanPerkembanganPasienTerintegrasi')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $data[$i]->iskonsul = false;
            $data[$i]->ruangankonsul = null;
            if(!empty($data2)){
                foreach($data2 as $d2){
                    if($d2->noregistrasi == $item->noregistrasi){
                        $data[$i]->iskonsul = $d2->iskonsul;
                        $data[$i]->ruangankonsul = $d2->ruangankonsul;
                    }
                }
            }

            $data[$i]->islab = false;
            $data[$i]->israd = false;
            $data[$i]->islabharih = false;
            $data[$i]->isradharih = false;

            if($item->isPenunjangSusulan != null){
                $data[$i]->islab = 'L: '.$item->isPenunjangSusulan;
            }

            if($item->isPenunjangSusulanRad != null){
                $data[$i]->israd = 'R: '.$item->isPenunjangSusulanRad;
            }

            if(!empty($data3)){
                foreach($data3 as $d3){
                    if($d3->noregistrasi == $item->noregistrasi){
                        if($data[$i]->islabharih == false){
                            if($d3->objectdepartemenfk == 3){
                                $data[$i]->islabharih = true;
                            } else{
                                $data[$i]->islabharih = false;
                            }
                        }

                        if($data[$i]->isradharih == false){
                            if($d3->objectdepartemenfk == 27){
                                $data[$i]->isradharih = true;
                            } else{
                                $data[$i]->isradharih = false;
                            }
                        }
                    }
                }
            }

            // var_dump($res4);

            $data[$i]->icd10 = substr($dtdt, 1, strlen($dtdt) - 1);
            $data[$i]->codernik = $codernik;
            $data[$i]->objectasalrujukanfk = $asalRujukan;
            $data[$i]->kodetarif = $kodetarif;
            $data[$i]->emrpasienfkasmed = false;
            $data[$i]->emrpasienfkaskep = false;
            $data[$i]->emrpasienfkcpptdokter = false;
            $data[$i]->emrpasienfkcpptperawat = false;
            $data[$i]->emrpasienfkcppt = false;
            $data[$i]->emrpasienfkassmed = false;
            $data[$i]->emrpasienfkresume = false;
            $data[$i]->emrpasienfk = '96600e3f-5a27-47bb-8617-7a96b1115bad';
            if(!empty($res) || !empty($res4)){
                $data[$i]->emrpasienfk = $res;
                $data[$i]->emrpasienfkresume = true;
            }
            if(!empty($res2)){
                $data[$i]->emrpasienfkasmed = true;
            }
            if(!empty($res2)){
                $data[$i]->emrpasienfkassmed = $res2;
            }
            if(!empty($res3)){
                $data[$i]->emrpasienfkaskep = true;
            }
            if(!empty($res4)){
                $data[$i]->emrpasienfkcpptdokter = true;
            }
            if(!empty($res5)){
                $data[$i]->emrpasienfkcpptperawat = true;
            }
            if(!empty($res6)){
                $data[$i]->emrpasienfkcppt = $res6;
            }

            $data[$i]->dokumen = null;
            if(!empty($dataMaster)){
                foreach ($dataMaster as $itemXX) {
                    if ($item->deptid == $itemXX->objectdepartemenfk && ($itemXX->objectruanganfk == null || str_contains($itemXX->objectruanganfk, (string)$item->ruanganid) == true) && ($itemXX->objectkelompokpasienfk == null || str_contains($itemXX->objectkelompokpasienfk, (string)$item->kpid) == true) && $itemXX->id != 515 && $itemXX->id != 518 && $itemXX->id != 355 && $itemXX->id != 360) {
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    if($data[$i]->islabharih && ($itemXX->id == 515 || $itemXX->id == 518 || $itemXX->id == 355)){
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    if($data[$i]->isradharih && ($itemXX->id == 360)){
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    foreach($data4 as $dat){
                        if(str_contains($itemXX->objectruanganfk, (string)$dat->idruangankonsul) == true && $dat->idruangankonsul != 215 && $dat->idruangankonsul != 411 && $dat->idruangankonsul != 379 && $dat->idruangankonsul != 256 && $item->norec == $dat->norec && $itemXX->id != 515 && $itemXX->id != 518 && $itemXX->id != 355 && $itemXX->id != 360){
                            $data[$i]->dokumen[] = array(
                                'name' => $itemXX->dokumen,
                                'urutan' => $itemXX->id,
                                'checked' => true,
                                'kodeexternal' => $itemXX->kodeexternal,
                                'norec_pd' =>   $data[$i]->norec,
                                'noregistrasi' =>   $data[$i]->noregistrasi,
                                'tglregistrasi' =>   $data[$i]->tgl_masuk,
                                'documentklaimfk' => $itemXX->id,
                                'api' => $itemXX->api,
                                'doc' => null
                            );
                        }
                    }
                }
            }

            if($data[$i]->dokumen != null){
                foreach($data[$i]->dokumen as $dd => $yy){
                    foreach ($dataDokKlaim as $datDok) {
                        if($yy['documentklaimfk'] == $datDok->documentklaimfk && $datDok->noregistrasifk == $yy['norec_pd']){
                            $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                        }
                    }
                }
            }

            $i = $i + 1;
        }

        // ini comment
        $i = 0;
        $dtdt = '';
        $dataICD9 = DB::table('diagnosatindakanpasien_t as dpa')
            ->join('detaildiagnosatindakanpasien_t as dp', 'dpa.norec', '=', 'dp.objectdiagnosatindakanpasienfk')
            ->join('diagnosatindakan_m as dg', 'dg.id', '=', 'dp.objectdiagnosatindakanfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dpa.objectpasienfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('dg.kddiagnosatindakan', 'pd.norec')
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.norec',  $r['norec_pd']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dataICD9 = $dataICD9->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                ->orWhere('ps.nocm', 'ilike', $searchTerm)
                ->orWhere('ps.namapasien', 'ilike', $searchTerm);
            });
        }
        $dataICD9 = $dataICD9->get();
        foreach ($data as $item) {
            $data[$i]->jenis_rawat = 2;
            foreach ($kdDepartemenRawatInap as $kddept) {
                if ($kddept == $item->deptid) {
                    $data[$i]->jenis_rawat = 1;
                }
            }
            $dtdt = '';
            foreach ($dataICD9 as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' . $item2->kddiagnosatindakan;
                }
            }
            $data[$i]->icd9 = substr($dtdt, 1, strlen($dtdt) - 1);
            $i = $i + 1;
        }

        $dariawal = '';
        $sampaiakhir = '';
        $noregs = '';
        $norms = '';
        $namas = '';
        $norec_pd = '';
        $ooooor = '';
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dariawal = " and pd.tglregistrasi >= '$r[dari] 00:00'";
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $sampaiakhir = " and pd.tglregistrasi <= '$r[sampai] 23:59'";
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $noregs = " and pd.noregistrasi='$r[noreg]'";
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $norms = " and ps.nocm='$r[nocm]'";
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $namas = " and ps.namapasien ilike '%" . $r['nama'] . "%'";
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = $r['search'];
            $ooooor =  " and ( pd.noregistrasi ilike '%$searchTerm%'
            or ps.namapasien ilike '%$searchTerm%'
            or ps.nocm ilike '%$searchTerm%'
            )";

        }

        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $norec_pd = " and pd.norec='$r[norec_pd]'";
        }
        $dataTarif16Non = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal,pp.produkfk,pr.namaproduk
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                LEFT JOIN kelompokprodukbpjs_m as kpb on pr.objectkelompokprodukbpjsfk  =kpb.id
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                and pr.objectkelompokprodukbpjsfk is null
                $dariawal
                $sampaiakhir
                --and kp.id in ($kelompokPasienINACBG)
                $noregs
                $namas
                $norms
                $kelPasien
                $norec_pd
                $ooooor
                group by pd.norec,kpb.namaexternal ,pp.produkfk,pr.namaproduk
                order by pd.norec")
        );


        $dataTarif16 = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                INNER JOIN kelompokprodukbpjs_m as kpb on kpb.id=pr.objectkelompokprodukbpjsfk
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                and pp.statusenabled = true
                --and kp.id in ($kelompokPasienINACBG)
                $dariawal
                $sampaiakhir
                $noregs
                $namas
                $norms
                $kelPasien
                $norec_pd
                $ooooor
                group by pd.norec,kpb.namaexternal

                union all

                select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                INNER JOIN kelompokprodukbpjs_m as kpb on kpb.id=pr.objectkelompokprodukbpjsfk
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                and pp.statusenabled = true
                and pd.noregistrasi = '$noregpenunjang'
                and kpb.id not in (10)
                group by pd.norec,kpb.namaexternal

                order by norec")
        );

        $i = 0;
        $datatatat = array(
            'prosedur_non_bedah' => 0,
            'prosedur_bedah' => 0,
            'konsultasi' => 0,
            'tenaga_ahli' => 0,
            'keperawatan' => 0,
            'penunjang' => 0,
            'radiologi' => 0,
            'laboratorium' => 0,
            'pelayanan_darah' => 0,
            'rehabilitasi' => 0,
            'kamar' => 0,
            'rawat_intensif' => 0,
            'obat' => 0,
            'obat_kronis' => 0,
            'obat_kemoterapi' => 0,
            'alkes' => 0,
            'bmhp' => 0,
            'sewa_alat' => 0,
        );
        $keys = array_keys($datatatat);
        foreach ($data as $item) {
            if (!empty($APD)) {
                if ($APD->noregistrasifk == $data[$i]->norec) {
                    $data[$i]->norec_apd = $APD->norec;
                }
            }
            $data[$i]->payor_id  = 3;
            $data[$i]->belum_mapping = [];
            $data[$i]->totalmappingtarif = 0;
            $data[$i]->totalbilling = 0;
            $data[$i]->umur_tahun = $this->getAgeYear($data[$i]->tgl_lahir, date('Y-m-d'));
            foreach ($dataTarif16 as $itm) {
                // if ($itm->norec == $data[$i]->norec) {
                    foreach ($keys as $k) {
                        if ($itm->namaexternal == $k) {
                            $datatatat[$k] = (float)$itm->ttl;
                            $data[$i]->totalmappingtarif = $data[$i]->totalmappingtarif + (float)$itm->ttl;;
                            break;
                        }
                    }
                // }
            }
            foreach ($dataTarif16Non as $itms) {
                if ($itms->norec == $data[$i]->norec) {
                    $data[$i]->totalbilling = $data[$i]->totalbilling + (float)$itms->ttl;;
                    $data[$i]->belum_mapping[] = $itms;
                }
            }

            $data[$i]->tarif_rs = $datatatat;
            $data[$i]->totalbilling = $data[$i]->totalbilling + $data[$i]->totalmappingtarif;
            $data[$i]->new_claim  = $this->new_claim($data[$i]);
            $data[$i]->set_claim_data  = $this->set_claim_data($data[$i], $depRI, $depIGD);
            $data[$i]->grouper  = $this->grouper($data[$i]);
            $data[$i]->delete_claim  = $this->delete_claim($data[$i]);
            if( $data[$i]->usia_kehamilan != null ){
                $data2 = DB::table('persalinandetail_t as pd')->where('norecpd', $data[$i]->norec)->get();
                $data[$i]->delivery = $data2;
            }else{
                $data[$i]->delivery = null;
            }

            $i = $i + 1;
        }
        $data = $data->toArray();
        if(isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] == 'true'){
            $dataR = [];
            // return count($data['data']);
            for ($i = count($data['data']) - 1; $i >= 0; $i--) {
                if($data['data'][$i]->icd10 == false){
                    $dataR[] = $data['data'][$i];
                }
            }
            $data['data'] = $dataR;
        }

        // ini batas comment
        return $this->respond($data);
    }

    public function daftarPasienINACBG(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $depRI =  $this->settingFix('kdDepartemenRanapFix');
        $depIGD =   $this->settingFix('idDepartemenIGD');
        $deptRanap = explode(',', $depRI);
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();
        $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile);
        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $dataMaster = $dataMaster->where('objectdepartemenfk', '=', $request['deptId']);
        };
        $dataMaster = $dataMaster->orderBy('nourut');
        $dataMaster = $dataMaster->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->leftjoin('persalinan_t as prs', 'prs.norecpd', '=', 'pd.norec')
            ->leftjoin('apgar_t as apg', 'apg.norecpd', '=', 'pd.norec')
            ->leftjoin('dializer_t as dz', 'dz.norecpd', '=', 'pd.norec')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                DB::raw("case when dept.id <> 16 and (pd.tglpulang is null or pd.tglpulang > pd.tglregistrasi) then pd.tglregistrasi else pd.tglpulang end as tgl_pulang"),
                'pd.tglmeninggal',
                'pd.tglverifklaim',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien as nama_pasien',
                'kp.kelompokpasien',
                'pd.statuspasien',
                'pd.nocmfk',
                'pd.statusverifikasi',
                'pg.id as pgid',
                'pg.namalengkap as nama_dokter',
                'kp.id as kpid',
                'pd.objectruanganlastfk as ruanganid',
                'pas.nosep as nomor_sep',
                'pas.norec as norec_pa',
                'ps.nobpjs as nomor_kartu',
                'ps.tgllahir as tgl_lahir',
                'ps.objectjeniskelaminfk as gender',
                'dept.id as deptid',
                'kls.kodebpjs as kelas_rawat',
                'kls.namabpjs as kelas_rawat_nama',
                'pas.klsrawathak_kode as kelas_dijamin',
                'kls.reportdisplay as namakelasdaftar',
                'pas.klsrawathak_nama as namakelas',
                'pd.objectstatuspulangfk',
                'ps.beratbadan',
                'rk.id as idrekanan',
                'rk.namarekanan',
                'ic.status as inacbg_status',
                'pd.inacbg_totaltarifrs',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_biayanaikkelas',
                'pas.statuscovid',
                'ps.noidentitas',
                'jk.jeniskelamin',
                'asa.inacbg_kode',
                'sp.inacbg_kode as discharge_status',
                'pd.nocmfk',
                'pd.inacbg_topup',
                'pd.sitb_id',
                'pd.kemkes_dc_status',
                'prs.usia_kehamilan',
                'prs.gravida',
                'prs.partus',
                'prs.abortus',
                'prs.onsetkontraksi',
                'prs.kodeonsetkontraksi',
                'apg.appearance',
                'apg.pulse',
                'apg.grimace',
                'apg.activity',
                'apg.respiration',
                'apg.appearance5',
                'apg.pulse5',
                'apg.grimace5',
                'apg.activity5',
                'apg.respiration5',
                'pd.isPenunjangKhusus',
                'pd.isPenunjangSusulan',
                'pd.isPenunjangSusulanRad',
                'pd.ketverifikasi',
                'dz.dializer_single_use',
                'dz.kantong_darah',
                DB::raw("case when pd.jenispelayanan = 2 then true else false end as eksekutif"),

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 0) {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 0) {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 1) {
            $data = $data->where('pd.tglpulang', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 1) {
            $data = $data->where('pd.tglpulang', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('dept.id', '=', $r['ins']);
        }
        if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 0) {
            $data = $data->whereNotIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 1) {
            $data = $data->whereIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 2) {
            $data = $data->whereIn('dept.id', [9]);
        }
        if (isset($r['ruangId']) && $r['ruangId'] != "" && $r['ruangId'] != "undefined") {
            $data = $data->where('ru.id', '=', $r['ruangId']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->whereIn('ru.id', explode(',', $r['ruang']));
        }
        if (isset($r['inacbg_status']) && $r['inacbg_status'] != "" && $r['inacbg_status'] != "undefined") {
            if($r['inacbg_status'] == "belum_kirim"){
                $data = $data->whereNull('pd.inacbg_status');
            }else{
                $data = $data->where('pd.inacbg_status', '=', $r['inacbg_status']);
            }
        }
        if (isset($r['isNotSEP']) && $r['isNotSEP'] != "" && $r['isNotSEP'] != "undefined" && $r['isNotSEP'] =="true") {
            $data = $data->whereRaw("(pas.nosep is null or pas.nosep  ='')");
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] =="true") {
            $data = $data->whereNull('pd.inacbg_status');
        }
        if (isset($r['isNotVerifikasi']) && $r['isNotVerifikasi'] != "" && $r['isNotVerifikasi'] != "undefined" && $r['isNotVerifikasi'] =="true") {
            $data = $data->whereNull('pd.tglverifklaim');
        }
        if (isset($r['isCatatan']) && $r['isCatatan'] != "" && $r['isCatatan'] != "undefined" && $r['isCatatan'] =="true") {
            $data = $data->whereNotNull('pd.tglverifklaim')->where('pd.ketverifikasi', '!=', '-');
        }
        if (isset($r['isPenunjang']) && $r['isPenunjang'] != "" && $r['isPenunjang'] != "undefined" && $r['isPenunjang'] =="true") {
            $data = $data->whereIn('ru.objectdepartemenfk', [3,27]);
        }
        $kelPasien  = '';
        if (isset($r['kelId']) && $r['kelId'] != "" && $r['kelId'] != "undefined") {
            $arrKel = explode(',', $r['kelId']);
            $kodeKel = [];
            foreach ($arrKel as $item) {
                $kodeKel[] = (int) $item;
            }
            $kelPasien = ' and kp.id in (' . $r['kelId'] . ')';
            $data = $data->whereIn('kp.id', $kodeKel);
        } else {
            // $data = $data->whereIn('kp.id',  explode(',',$   kelompokPasienINACBG));

        }
        if (isset($r['dokId']) && $r['dokId'] != "" && $r['dokId'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokId']);
        }
        if (isset($r['status_pasien']) && $r['status_pasien'] != "" && $r['status_pasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['status_pasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['nosep']) && $r['nosep'] != "" && $r['nosep'] != "undefined") {
            $data = $data->where('pas.nosep', '=', $r['nosep']);
        }
        if (isset($r['status']) && $r['status'] != "" && $r['status'] != "undefined") {
            $data = $data->where('pd.statusklaim', '=', $r['status']);
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $data = $data->whereNotNull('pd.tglpulang');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('rk.namarekanan', 'ilike', $searchTerm)
                    ->orWhere('pas.nosep', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '' ) {
            $page = $r['page'];
        }

        $data = $data->orderBy('pd.noregistrasi');
        // $data = $data->get();
        $data = $data ->paginate(isset($r['limit'])?$r['limit']: 10, ['*'], 'page', $page);

        // var_dump($data);

        $data2 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                DB::raw("string_agg(ru.namaruangan, ', \n') as ruangankonsul"),
                'ps.namapasien as nama_pasien',
                'pd.statuspasien',
                'apd.iskonsul',
                'pd.objectruanganlastfk as ruanganid',
            )
            ->where('pd.statusenabled', true)
            ->where('apd.iskonsul', true)
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data2 = $data2->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data2 = $data2->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data2 = $data2->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data2 = $data2->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data2 = $data2->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', '=', $r['norec_pd']);
        }

        $data2 = $data2->groupBy('pd.norec', 'pd.tglregistrasi', 'pd.tglpulang', 'ps.nocm',
        'pd.noregistrasi', 'ps.namapasien', 'pd.statuspasien', 'apd.iskonsul', 'pd.objectruanganlastfk');
        $data2 = $data2->get();

        $data3 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('strukorder_t AS so', 'pd.norec', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'pd.tglmeninggal',
                'pd.tglverifklaim',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan as ruangantujuan',
                'ru.objectdepartemenfk'
            )
            ->where('pd.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', [3, 27])
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data3 = $data3->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data3 = $data3->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data3 = $data3->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data3 = $data3->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data3 = $data3->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data3 = $data3->where('pd.norec', '=', $r['norec_pd']);
        }

        $data3 = $data3->get();

        $data4 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi', 'ru.id as idruangankonsul',
                'ps.namapasien as nama_pasien',
                'pd.statuspasien',
                'apd.iskonsul',
                'pd.objectruanganlastfk as ruanganid',
            )
            ->where('pd.statusenabled', true)
            ->where('apd.iskonsul', true)
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data4 = $data4->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data4 = $data4->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data4 = $data4->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data4 = $data4->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data4 = $data4->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data4 = $data4->where('pd.norec', '=', $r['norec_pd']);
        }

        $data4 = $data4->get();

        // var_dump($data3);

        $dataDokKlaim = DB::table("monitoringdokklaim_t")
            // ->where("noregistrasifk", $value->norec)
            ->where("kdprofile", $kdProfile)
            ->where("statusenabled", true);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        $dataDokKlaim = $dataDokKlaim->get();


        // ini comment
        $i = 0;

        // dd($dataDokKlaim);
        foreach ($data as $item) {
            // ini batas comment
            $res = DB::connection('mongodb')
            ->table('RingkasanKeluar')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res4 = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('nocmfk', $item->nocmfk)
            ->where('created_at', '>=', date('Y-m-d', strtotime($item->tgl_masuk)).' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d', strtotime($item->tgl_masuk)).' 23:59:59')
            ->where('flag', 'dokter')
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $data[$i]->iskonsul = false;
            $data[$i]->ruangankonsul = null;
            if(!empty($data2)){
                foreach($data2 as $d2){
                    if($d2->noregistrasi == $item->noregistrasi){
                        $data[$i]->iskonsul = $d2->iskonsul;
                        $data[$i]->ruangankonsul = $d2->ruangankonsul;
                    }
                }
            }

            $data[$i]->islab = false;
            $data[$i]->israd = false;
            $data[$i]->islabharih = false;
            $data[$i]->isradharih = false;

            if($item->isPenunjangSusulan != null){
                $data[$i]->islab = 'L: '.$item->isPenunjangSusulan;
            }

            if($item->isPenunjangSusulanRad != null){
                $data[$i]->israd = 'R: '.$item->isPenunjangSusulanRad;
            }

            if(!empty($data3)){
                foreach($data3 as $d3){
                    if($d3->noregistrasi == $item->noregistrasi){
                        if($data[$i]->islabharih == false){
                            if($d3->objectdepartemenfk == 3){
                                $data[$i]->islabharih = true;
                            } else{
                                $data[$i]->islabharih = false;
                            }
                        }

                        if($data[$i]->isradharih == false){
                            if($d3->objectdepartemenfk == 27){
                                $data[$i]->isradharih = true;
                            } else{
                                $data[$i]->isradharih = false;
                            }
                        }
                    }
                }
            }

            // var_dump($res4);

            $data[$i]->emrpasienfkasmed = false;
            $data[$i]->emrpasienfkaskep = false;
            $data[$i]->emrpasienfkcpptdokter = false;
            $data[$i]->emrpasienfkcpptperawat = false;
            $data[$i]->emrpasienfkcppt = false;
            $data[$i]->emrpasienfkassmed = false;
            $data[$i]->emrpasienfkresume = false;
            $data[$i]->emrpasienfk = '96600e3f-5a27-47bb-8617-7a96b1115bad';
            if(!empty($res) || !empty($res4)){
                $data[$i]->emrpasienfk = $res;
                $data[$i]->emrpasienfkresume = true;
            }
            if(!empty($res4)){
                $data[$i]->emrpasienfkcpptdokter = true;
            }

            $data[$i]->dokumen = null;
            if(!empty($dataMaster)){
                foreach ($dataMaster as $itemXX) {
                    if ($item->deptid == $itemXX->objectdepartemenfk && ($itemXX->objectruanganfk == null || str_contains($itemXX->objectruanganfk, (string)$item->ruanganid) == true) && ($itemXX->objectkelompokpasienfk == null || str_contains($itemXX->objectkelompokpasienfk, (string)$item->kpid) == true) && $itemXX->id != 515 && $itemXX->id != 518 && $itemXX->id != 355 && $itemXX->id != 360) {
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    if($data[$i]->islabharih && ($itemXX->id == 515 || $itemXX->id == 518 || $itemXX->id == 355)){
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    if($data[$i]->isradharih && ($itemXX->id == 360)){
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    if(($itemXX->id == 298)){
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    foreach($data4 as $dat){
                        if(str_contains($itemXX->objectruanganfk, (string)$dat->idruangankonsul) == true && $dat->idruangankonsul != 215 && $dat->idruangankonsul != 411 && $dat->idruangankonsul != 379 && $dat->idruangankonsul != 256 && $item->norec == $dat->norec && $itemXX->id != 515 && $itemXX->id != 518 && $itemXX->id != 355 && $itemXX->id != 360){
                            $data[$i]->dokumen[] = array(
                                'name' => $itemXX->dokumen,
                                'urutan' => $itemXX->id,
                                'checked' => true,
                                'kodeexternal' => $itemXX->kodeexternal,
                                'norec_pd' =>   $data[$i]->norec,
                                'noregistrasi' =>   $data[$i]->noregistrasi,
                                'tglregistrasi' =>   $data[$i]->tgl_masuk,
                                'documentklaimfk' => $itemXX->id,
                                'api' => $itemXX->api,
                                'doc' => null
                            );
                        }
                    }
                }
            }
            // var_dump($dataDokKlaim);

            if($data[$i]->dokumen != null){
                foreach($data[$i]->dokumen as $dd => $yy){
                    foreach ($dataDokKlaim as $datDok) {
                        if($item->deptid == 16){
                            if($yy['documentklaimfk'] == $datDok->documentklaimfk && $datDok->noregistrasifk == $yy['norec_pd']){
                                //  var_dump($datDok->ischecked == NULL);
                                $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                                if($datDok->ischecked === true || $datDok->ischecked === false){
                                    // var_dump('Halo 1 ');
                                    $data[$i]->dokumen[$dd]['checked'] = $datDok->ischecked;
                                } else{
                                    // var_dump('Halo 2 ');
                                    $data[$i]->dokumen[$dd]['checked'] = true;
                                }
                                // var_dump($data[$i]->dokumen[$dd]['checked']);
                            }
                        } else{
                            if($yy['documentklaimfk'] == $datDok->documentklaimfk && $datDok->noregistrasifk == $yy['norec_pd']){
                                $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                                $data[$i]->dokumen[$dd]['checked'] = true;
                            }
                        }

                    }
                }
            }

            $i = $i + 1;
        }

        // ini comment

        // ini batas comment
        return $this->respond($data);
    }

    public function daftarPasienINACBGCOB(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $depRI =  $this->settingFix('kdDepartemenRanapFix');
        $depIGD =   $this->settingFix('idDepartemenIGD');
        $deptRanap = explode(',', $depRI);
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();
        $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile);
        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $dataMaster = $dataMaster->where('objectdepartemenfk', '=', $request['deptId']);
        };
        $dataMaster = $dataMaster->orderBy('nourut');
        $dataMaster = $dataMaster->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->leftjoin('persalinan_t as prs', 'prs.norecpd', '=', 'pd.norec')
            ->leftjoin('apgar_t as apg', 'apg.norecpd', '=', 'pd.norec')
            ->leftjoin('dializer_t as dz', 'dz.norecpd', '=', 'pd.norec')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                DB::raw("case when dept.id <> 16 and (pd.tglpulang is null or pd.tglpulang > pd.tglregistrasi) then pd.tglregistrasi else pd.tglpulang end as tgl_pulang"),
                'pd.tglmeninggal',
                'pd.tglverifklaim',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien as nama_pasien',
                'kp.kelompokpasien',
                'pd.statuspasien',
                'pd.statusverifikasi',
                'pg.id as pgid',
                'pg.namalengkap as nama_dokter',
                'kp.id as kpid',
                'pd.objectruanganlastfk as ruanganid',
                'pas.nosep as nomor_sep',
                'pas.norec as norec_pa',
                'ps.nobpjs as nomor_kartu',
                'ps.tgllahir as tgl_lahir',
                'ps.objectjeniskelaminfk as gender',
                'dept.id as deptid',
                'kls.kodebpjs as kelas_rawat',
                'kls.namabpjs as kelas_rawat_nama',
                'pas.klsrawathak_kode as kelas_dijamin',
                'kls.reportdisplay as namakelasdaftar',
                'pas.klsrawathak_nama as namakelas',
                'pd.objectstatuspulangfk',
                'ps.beratbadan',
                'ps.rujukanrs',
                'ps.penjaminanrs',
                'ps.riwayattambahan',
                'rk.id as idrekanan',
                'rk.namarekanan',
                'ic.status as inacbg_status',
                'pd.inacbg_totaltarifrs',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_biayanaikkelas',
                'pas.statuscovid',
                'ps.noidentitas',
                'jk.jeniskelamin',
                'asa.inacbg_kode',
                'sp.inacbg_kode as discharge_status',
                'pd.nocmfk',
                'pd.inacbg_topup',
                'pd.sitb_id',
                'pd.kemkes_dc_status',
                'prs.usia_kehamilan',
                'prs.gravida',
                'prs.partus',
                'prs.abortus',
                'prs.onsetkontraksi',
                'prs.kodeonsetkontraksi',
                'apg.appearance',
                'apg.pulse',
                'apg.grimace',
                'apg.activity',
                'apg.respiration',
                'apg.appearance5',
                'apg.pulse5',
                'apg.grimace5',
                'apg.activity5',
                'apg.respiration5',
                'pd.isPenunjangKhusus',
                'pd.isPenunjangSusulan',
                'pd.isPenunjangSusulanRad',
                'pd.ketverifikasi',
                'dz.dializer_single_use',
                'dz.kantong_darah',
                DB::raw("case when pd.jenispelayanan = 2 then true else false end as eksekutif"),

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 0) {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 0) {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 1) {
            $data = $data->where('pd.tglpulang', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 1) {
            $data = $data->where('pd.tglpulang', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('dept.id', '=', $r['ins']);
        }
        if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 0) {
            $data = $data->whereNotIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 1) {
            $data = $data->whereIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 2) {
            $data = $data->whereIn('dept.id', [9]);
        }
        if (isset($r['ruangId']) && $r['ruangId'] != "" && $r['ruangId'] != "undefined") {
            $data = $data->where('ru.id', '=', $r['ruangId']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->whereIn('ru.id', explode(',', $r['ruang']));
        }
        if (isset($r['inacbg_status']) && $r['inacbg_status'] != "" && $r['inacbg_status'] != "undefined") {
            if($r['inacbg_status'] == "belum_kirim"){
                $data = $data->whereNull('pd.inacbg_status');
            }else{
                $data = $data->where('pd.inacbg_status', '=', $r['inacbg_status']);
            }
        }
        if (isset($r['isNotSEP']) && $r['isNotSEP'] != "" && $r['isNotSEP'] != "undefined" && $r['isNotSEP'] =="true") {
            $data = $data->whereRaw("(pas.nosep is null or pas.nosep  ='')");
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] =="true") {
            $data = $data->whereNull('pd.inacbg_status');
        }
        if (isset($r['isNotVerifikasi']) && $r['isNotVerifikasi'] != "" && $r['isNotVerifikasi'] != "undefined" && $r['isNotVerifikasi'] =="true") {
            $data = $data->whereNull('pd.tglverifklaim');
        }
        if (isset($r['isCatatan']) && $r['isCatatan'] != "" && $r['isCatatan'] != "undefined" && $r['isCatatan'] =="true") {
            $data = $data->whereNotNull('pd.tglverifklaim')->where('pd.ketverifikasi', '!=', '-');
        }
        if (isset($r['isPenunjang']) && $r['isPenunjang'] != "" && $r['isPenunjang'] != "undefined" && $r['isPenunjang'] =="true") {
            $data = $data->whereIn('ru.objectdepartemenfk', [3,27]);
        }
        $kelPasien  = '';
        if (isset($r['kelId']) && $r['kelId'] != "" && $r['kelId'] != "undefined") {
            $arrKel = explode(',', $r['kelId']);
            $kodeKel = [];
            foreach ($arrKel as $item) {
                $kodeKel[] = (int) $item;
            }
            $kelPasien = ' and kp.id in (' . $r['kelId'] . ')';
            $data = $data->whereIn('kp.id', $kodeKel);
        } else {
            // $data = $data->whereIn('kp.id',  explode(',',$   kelompokPasienINACBG));

        }
        if (isset($r['dokId']) && $r['dokId'] != "" && $r['dokId'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokId']);
        }
        if (isset($r['status_pasien']) && $r['status_pasien'] != "" && $r['status_pasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['status_pasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['nosep']) && $r['nosep'] != "" && $r['nosep'] != "undefined") {
            $data = $data->where('pas.nosep', '=', $r['nosep']);
        }
        if (isset($r['status']) && $r['status'] != "" && $r['status'] != "undefined") {
            $data = $data->where('pd.statusklaim', '=', $r['status']);
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $data = $data->whereNotNull('pd.tglpulang');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('rk.namarekanan', 'ilike', $searchTerm)
                    ->orWhere('pas.nosep', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '' ) {
            $page = $r['page'];
        }

        $data = $data->orderBy('pd.noregistrasi');
        // $data = $data->get();
        $data = $data ->paginate(isset($r['limit'])?$r['limit']: 10, ['*'], 'page', $page);

        // var_dump($data);

        $data2 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                DB::raw("string_agg(ru.namaruangan, ', \n') as ruangankonsul"),
                'ps.namapasien as nama_pasien',
                'pd.statuspasien',
                'apd.iskonsul',
                'pd.objectruanganlastfk as ruanganid',
            )
            ->where('pd.statusenabled', true)
            ->where('apd.iskonsul', true)
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data2 = $data2->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data2 = $data2->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data2 = $data2->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data2 = $data2->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data2 = $data2->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', '=', $r['norec_pd']);
        }

        $data2 = $data2->groupBy('pd.norec', 'pd.tglregistrasi', 'pd.tglpulang', 'ps.nocm',
        'pd.noregistrasi', 'ps.namapasien', 'pd.statuspasien', 'apd.iskonsul', 'pd.objectruanganlastfk');
        $data2 = $data2->get();

        $data3 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('strukorder_t AS so', 'pd.norec', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'pd.tglmeninggal',
                'pd.tglverifklaim',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan as ruangantujuan',
                'ru.objectdepartemenfk'
            )
            ->where('pd.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', [3, 27])
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data3 = $data3->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data3 = $data3->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data3 = $data3->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data3 = $data3->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data3 = $data3->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data3 = $data3->where('pd.norec', '=', $r['norec_pd']);
        }

        $data3 = $data3->get();

        $data4 = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi', 'ru.id as idruangankonsul',
                'ps.namapasien as nama_pasien',
                'pd.statuspasien',
                'apd.iskonsul',
                'pd.objectruanganlastfk as ruanganid',
            )
            ->where('pd.statusenabled', true)
            ->where('apd.iskonsul', true)
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data4 = $data4->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data4 = $data4->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data4 = $data4->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data4 = $data4->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data4 = $data4->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data4 = $data4->where('pd.norec', '=', $r['norec_pd']);
        }

        $data4 = $data4->get();

        // var_dump($data3);

        $dataDokKlaim = DB::table("monitoringdokklaim_t")
            // ->where("noregistrasifk", $value->norec)
            ->where("kdprofile", $kdProfile)
            ->where("statusenabled", true);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        $dataDokKlaim = $dataDokKlaim->get();


        // ini comment
        $i = 0;

        // dd($dataDokKlaim);
        foreach ($data as $item) {
            // ini batas comment
            $res = DB::connection('mongodb')
            ->table('RingkasanKeluar')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res2 = DB::connection('mongodb')
            ->table('AsesmenMedisRawatJalan')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res3 = DB::connection('mongodb')
            ->table('AsesmenAwalKeperawatanPasienRawatJalan')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res4 = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('nocmfk', $item->nocmfk)
            ->where('created_at', '>=', date('Y-m-d', strtotime($item->tgl_masuk)).' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d', strtotime($item->tgl_masuk)).' 23:59:59')
            ->where('flag', 'dokter')
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res5 = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('nocmfk', $item->nocmfk)
            ->where('created_at', '>=', date('Y-m-d', strtotime($item->tgl_masuk)).' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d', strtotime($item->tgl_masuk)).' 23:59:59')
            ->where('statusenabled', true)
            ->where('flag', 'perawat')
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $res6 = DB::connection('mongodb')
            ->table('CatatanPerkembanganPasienTerintegrasi')
            ->where('registrasi.norec_pd', $item->norec)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

            $data[$i]->iskonsul = false;
            $data[$i]->ruangankonsul = null;
            if(!empty($data2)){
                foreach($data2 as $d2){
                    if($d2->noregistrasi == $item->noregistrasi){
                        $data[$i]->iskonsul = $d2->iskonsul;
                        $data[$i]->ruangankonsul = $d2->ruangankonsul;
                    }
                }
            }

            $data[$i]->islab = false;
            $data[$i]->israd = false;
            $data[$i]->islabharih = false;
            $data[$i]->isradharih = false;

            if($item->isPenunjangSusulan != null){
                $data[$i]->islab = 'L: '.$item->isPenunjangSusulan;
            }

            if($item->isPenunjangSusulanRad != null){
                $data[$i]->israd = 'R: '.$item->isPenunjangSusulanRad;
            }

            if(!empty($data3)){
                foreach($data3 as $d3){
                    if($d3->noregistrasi == $item->noregistrasi){
                        if($data[$i]->islabharih == false){
                            if($d3->objectdepartemenfk == 3){
                                $data[$i]->islabharih = true;
                            } else{
                                $data[$i]->islabharih = false;
                            }
                        }

                        if($data[$i]->isradharih == false){
                            if($d3->objectdepartemenfk == 27){
                                $data[$i]->isradharih = true;
                            } else{
                                $data[$i]->isradharih = false;
                            }
                        }
                    }
                }
            }

            // var_dump($res4);

            $data[$i]->emrpasienfkasmed = false;
            $data[$i]->emrpasienfkaskep = false;
            $data[$i]->emrpasienfkcpptdokter = false;
            $data[$i]->emrpasienfkcpptperawat = false;
            $data[$i]->emrpasienfkcppt = false;
            $data[$i]->emrpasienfkassmed = false;
            $data[$i]->emrpasienfkresume = false;
            $data[$i]->emrpasienfk = '96600e3f-5a27-47bb-8617-7a96b1115bad';
            if(!empty($res) || !empty($res4)){
                $data[$i]->emrpasienfk = $res;
                $data[$i]->emrpasienfkresume = true;
            }
            if(!empty($res2)){
                $data[$i]->emrpasienfkasmed = true;
            }
            if(!empty($res2)){
                $data[$i]->emrpasienfkassmed = $res2;
            }
            if(!empty($res3)){
                $data[$i]->emrpasienfkaskep = true;
            }
            if(!empty($res4)){
                $data[$i]->emrpasienfkcpptdokter = true;
            }
            if(!empty($res5)){
                $data[$i]->emrpasienfkcpptperawat = true;
            }
            if(!empty($res6)){
                $data[$i]->emrpasienfkcppt = $res6;
            }

            $data[$i]->dokumen = null;
            if(!empty($dataMaster)){
                foreach ($dataMaster as $itemXX) {
                    if ($item->deptid == $itemXX->objectdepartemenfk && ($itemXX->objectruanganfk == null || str_contains($itemXX->objectruanganfk, (string)$item->ruanganid) == true) && ($itemXX->objectkelompokpasienfk == null || str_contains($itemXX->objectkelompokpasienfk, (string)$item->kpid) == true) && $itemXX->id != 515 && $itemXX->id != 518 && $itemXX->id != 355 && $itemXX->id != 360) {
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    if($data[$i]->islabharih && ($itemXX->id == 515 || $itemXX->id == 518 || $itemXX->id == 355)){
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    if($data[$i]->isradharih && ($itemXX->id == 360)){
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }

                    foreach($data4 as $dat){
                        if(str_contains($itemXX->objectruanganfk, (string)$dat->idruangankonsul) == true && $dat->idruangankonsul != 215 && $dat->idruangankonsul != 411 && $dat->idruangankonsul != 379 && $dat->idruangankonsul != 256 && $item->norec == $dat->norec && $itemXX->id != 515 && $itemXX->id != 518 && $itemXX->id != 355 && $itemXX->id != 360){
                            $data[$i]->dokumen[] = array(
                                'name' => $itemXX->dokumen,
                                'urutan' => $itemXX->id,
                                'checked' => true,
                                'kodeexternal' => $itemXX->kodeexternal,
                                'norec_pd' =>   $data[$i]->norec,
                                'noregistrasi' =>   $data[$i]->noregistrasi,
                                'tglregistrasi' =>   $data[$i]->tgl_masuk,
                                'documentklaimfk' => $itemXX->id,
                                'api' => $itemXX->api,
                                'doc' => null
                            );
                        }
                    }
                }
            }

            if($data[$i]->dokumen != null){
                foreach($data[$i]->dokumen as $dd => $yy){
                    foreach ($dataDokKlaim as $datDok) {
                        if($yy['documentklaimfk'] == $datDok->documentklaimfk && $datDok->noregistrasifk == $yy['norec_pd']){
                            $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                        }
                    }
                }
            }

            $i = $i + 1;
        }

        // ini comment

        // ini batas comment
        return $this->respond($data);
    }

    public function daftarPasienINACBGCPPTDokter(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('nocmfk', $r['nocmfk'])
            ->where('created_at', '>=', date('Y-m-d', strtotime($r['tgl_masuk'])).' 00:00:00')
            ->where('created_at', '<=', date('Y-m-d', strtotime($r['tgl_masuk'])).' 23:59:59')
            ->where('flag', 'dokter')
            ->where('statusenabled', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

        return $this->respond($data);
    }

    public function daftarPasienINACBGDownload(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $depRI =  $this->settingFix('kdDepartemenRanapFix');
        $depIGD =   $this->settingFix('idDepartemenIGD');
        $deptRanap = explode(',', $depRI);
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();
        $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile);
        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $dataMaster = $dataMaster->where('objectdepartemenfk', '=', $request['deptId']);
        };
        $dataMaster = $dataMaster->orderBy('nourut');
        $dataMaster = $dataMaster->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->leftjoin('persalinan_t as prs', 'prs.norecpd', '=', 'pd.norec')
            ->leftjoin('apgar_t as apg', 'apg.norecpd', '=', 'pd.norec')
              // ->leftjoin('dializer_t as dz', 'dz.norecpd', '=', 'pd.norec')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'pd.tglmeninggal',
                'pd.tglverifklaim',
                'pd.statusverifikasi',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien as nama_pasien',
                'kp.kelompokpasien',
                'pd.statuspasien',
                'pg.id as pgid',
                'pg.namalengkap as nama_dokter',
                'kp.id as kpid',
                'pd.objectruanganlastfk as ruanganid',
                'pas.nosep as nomor_sep',
                'pas.norec as norec_pa',
                'ps.nobpjs as nomor_kartu',
                'ps.tgllahir as tgl_lahir',
                'ps.objectjeniskelaminfk as gender',
                'dept.id as deptid',
                'kls.kodebpjs as kelas_rawat',
                'kls.namabpjs as kelas_rawat_nama',
                'pas.klsrawathak_kode as kelas_dijamin',
                'kls.reportdisplay as namakelasdaftar',
                'pas.klsrawathak_nama as namakelas',
                'pd.objectstatuspulangfk',
                'ps.beratbadan',
                'rk.id as idrekanan',
                'rk.namarekanan',
                'ic.status as inacbg_status',
                'pd.inacbg_totaltarifrs',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_biayanaikkelas',
                'pas.statuscovid',
                'ps.noidentitas',
                'jk.jeniskelamin',
                'asa.inacbg_kode',
                'sp.inacbg_kode as discharge_status',
                'pd.nocmfk',
                'pd.inacbg_topup',
                'pd.sitb_id',
                'pd.kemkes_dc_status',
                'prs.usia_kehamilan',
                'prs.gravida',
                'prs.partus',
                'prs.abortus',
                'prs.onsetkontraksi',
                'prs.kodeonsetkontraksi',
                'apg.appearance',
                'apg.pulse',
                'apg.grimace',
                'apg.activity',
                'apg.respiration',
                'apg.appearance5',
                'apg.pulse5',
                'apg.grimace5',
                'apg.activity5',
                'apg.respiration5',
                'pd.isPenunjangKhusus',
                'pd.isPenunjangSusulan',
                'pd.isPenunjangSusulanRad',
                'pd.ketverifikasi',
                 // 'dz.dializer_single_use',
                DB::raw("case when pd.jenispelayanan = 2 then true else false end as eksekutif"),

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('dept.id', '=', $r['ins']);
        }
        if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 0) {
            $data = $data->whereNotIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 1) {
            $data = $data->whereIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 2) {
            $data = $data->whereIn('dept.id', [9]);
        }
        if (isset($r['ruangId']) && $r['ruangId'] != "" && $r['ruangId'] != "undefined") {
            $data = $data->where('ru.id', '=', $r['ruangId']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->whereIn('ru.id', explode(',', $r['ruang']));
        }
        if (isset($r['inacbg_status']) && $r['inacbg_status'] != "" && $r['inacbg_status'] != "undefined") {
            if($r['inacbg_status'] == "belum_kirim"){
                $data = $data->whereNull('pd.inacbg_status');
            }else{
                $data = $data->where('pd.inacbg_status', '=', $r['inacbg_status']);
            }
        }
        if (isset($r['isNotSEP']) && $r['isNotSEP'] != "" && $r['isNotSEP'] != "undefined" && $r['isNotSEP'] =="true") {
            $data = $data->whereRaw("(pas.nosep is null or pas.nosep  ='')");
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] =="true") {
            $data = $data->whereNull('pd.inacbg_status');
        }
        if (isset($r['isNotVerifikasi']) && $r['isNotVerifikasi'] != "" && $r['isNotVerifikasi'] != "undefined" && $r['isNotVerifikasi'] =="true") {
            $data = $data->whereNull('pd.tglverifklaim');
        }
        if (isset($r['isCatatan']) && $r['isCatatan'] != "" && $r['isCatatan'] != "undefined" && $r['isCatatan'] =="true") {
            $data = $data->whereNotNull('pd.tglverifklaim')->where('pd.ketverifikasi', '!=', '-');
        }
        if (isset($r['isPenunjang']) && $r['isPenunjang'] != "" && $r['isPenunjang'] != "undefined" && $r['isPenunjang'] =="true") {
            $data = $data->whereIn('ru.objectdepartemenfk', [3,27]);
        }
        $kelPasien  = '';
        if (isset($r['kelId']) && $r['kelId'] != "" && $r['kelId'] != "undefined") {
            $arrKel = explode(',', $r['kelId']);
            $kodeKel = [];
            foreach ($arrKel as $item) {
                $kodeKel[] = (int) $item;
            }
            $kelPasien = ' and kp.id in (' . $r['kelId'] . ')';
            $data = $data->whereIn('kp.id', $kodeKel);
        } else {
            // $data = $data->whereIn('kp.id',  explode(',',$   kelompokPasienINACBG));

        }
        if (isset($r['dokId']) && $r['dokId'] != "" && $r['dokId'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokId']);
        }
        if (isset($r['status_pasien']) && $r['status_pasien'] != "" && $r['status_pasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['status_pasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['nosep']) && $r['nosep'] != "" && $r['nosep'] != "undefined") {
            $data = $data->where('pas.nosep', '=', $r['nosep']);
        }
        if (isset($r['status']) && $r['status'] != "" && $r['status'] != "undefined") {
            $data = $data->where('pd.statusklaim', '=', $r['status']);
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $data = $data->whereNotNull('pd.tglpulang');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        $string = $this->strtoarray($r['search']);
        if (isset($r['search']) && $r['search'] != '') {
            $data = $data->whereIn('pas.nosep', $string);
        }
        // $page = 1;
        // if (isset($r['page']) && $r['page'] != '' ) {
        //     $page = $r['page'];
        // }

        $data = $data->orderBy('pd.noregistrasi');
        $data = $data->get();
        // $data = $data ->paginate(200, ['*'], 'page', $page);

        $dataDokKlaim = DB::table("monitoringdokklaim_t")
            // ->where("noregistrasifk", $value->norec)
            ->where("kdprofile", $kdProfile)
            ->where("statusenabled", true);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        $dataDokKlaim = $dataDokKlaim->get();

        $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile);
        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $dataMaster = $dataMaster->where('objectdepartemenfk', '=', $request['deptId']);
        };
        $dataMaster = $dataMaster->orderBy('nourut');
        $dataMaster = $dataMaster->get();

        // dd($dataDokKlaim);
        $i = 0;
        foreach ($data as $item) {

            $data[$i]->dokumen = null;
            if(!empty($dataMaster)){
                foreach ($dataMaster as $itemXX) {
                    if ($item->deptid == $itemXX->objectdepartemenfk && ($itemXX->objectruanganfk == null || str_contains($itemXX->objectruanganfk, (string)$item->ruanganid) == true) && ($itemXX->objectkelompokpasienfk == null || str_contains($itemXX->objectkelompokpasienfk, (string)$item->kpid) == true)) {
                        $data[$i]->dokumen[] = array(
                            'name' => $itemXX->dokumen,
                            'urutan' => $itemXX->id,
                            'checked' => true,
                            'kodeexternal' => $itemXX->kodeexternal,
                            'norec_pd' =>   $data[$i]->norec,
                            'noregistrasi' =>   $data[$i]->noregistrasi,
                            'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            'documentklaimfk' => $itemXX->id,
                            'api' => $itemXX->api,
                            'doc' => null
                        );
                    }
                }
            }
            // else{
            //     $data[$i]->dokumen[] = array();
            // }

            // foreach ($data[$i]->dokumen as $dd => $vv) {
            //     if ($datDok->documentklaimfk == $vv['documentklaimfk']) {
            //         $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
            //     }
            // }
            if($data[$i]->dokumen != null){
                foreach($data[$i]->dokumen as $dd => $yy){
                    foreach ($dataDokKlaim as $datDok) {
                        if($yy['documentklaimfk'] == $datDok->documentklaimfk && $datDok->noregistrasifk == $yy['norec_pd']){
                            $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                            // $data[$i]->dokumen[] = array(
                            //     'name' => $itemXX->dokumen,
                            //     'urutan' => $itemXX->id,
                            //     'checked' => true,
                            //     'kodeexternal' => $itemXX->kodeexternal,
                            //     'norec_pd' =>   $data[$i]->norec,
                            //     'noregistrasi' =>   $data[$i]->noregistrasi,
                            //     'tglregistrasi' =>   $data[$i]->tgl_masuk,
                            //     'documentklaimfk' => $itemXX->id,
                            //     'api' => $itemXX->api,
                            //     'doc' => $datDok->filename
                            // );
                        }
                    }
                }
            }

            $i = $i + 1;
        }
        return $this->respond($data);
    }

    function strtoarray($a, $t = ''){
        $arr = [];
        $a = ltrim($a, '[');
        $a = ltrim($a, 'array(');
        $a = rtrim($a, ']');
        $a = rtrim($a, ')');
        $tmpArr = explode(",", $a);
        foreach ($tmpArr as $v) {
            if($t == 'keys'){
                $tmp = explode("=>", $v);
                $k = $tmp[0]; $nv = $tmp[1];
                $k = trim(trim($k), "'");
                $k = trim(trim($k), '"');
                $nv = trim(trim($nv), "'");
                $nv = trim(trim($nv), '"');
                $arr[$k] = $nv;
            } else {
                $v = trim(trim($v), "'");
                $v = trim(trim($v), '"');
                $arr[] = $v;
            }
        }
        return $arr;
    }

    public function daftarPasienINACBGRM(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $depRI =  $this->settingFix('kdDepartemenRanapFix');
        $depIGD =   $this->settingFix('idDepartemenIGD');
        $deptRanap = explode(',', $depRI);
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();
        $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile);
        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $dataMaster = $dataMaster->where('objectdepartemenfk', '=', $request['deptId']);
        };
        $dataMaster = $dataMaster->orderBy('nourut');
        $dataMaster = $dataMaster->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            // ->join('departemen_m as dept', 'dept.id', '=', 'ru2.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->leftjoin('persalinan_t as prs', 'prs.norecpd', '=', 'pd.norec')
            ->leftjoin('apgar_t as apg', 'apg.norecpd', '=', 'pd.norec')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru2.objectdepartemenfk')
            ->whereNotIn('ru2.namaruangan', ['RADIOLOGI', 'LABORATORIUM', 'BANK DARAH', 'LAB - INVITRO KEDOKTERAN NUKLIR', 'LAB - PATOLOGI KLINIK', 'LAB - PATOLOGI ANATOMI', 'LAB - MIKROBIOLOGI KLINIK', 'CATHLAB', 'BEDAH SENTRAL IBS'])
              // ->leftjoin('dializer_t as dz', 'dz.norecpd', '=', 'pd.norec')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'pd.tglclosing',
                'pd.tglmeninggal',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien as nama_pasien',
                'kp.kelompokpasien',
                'pd.statuspasien',
                'pg.id as pgid',
                'pg.namalengkap as nama_dokter',
                'kp.id as kpid',
                'pd.objectruanganlastfk as ruanganid',
                'pas.nosep as nomor_sep',
                'pas.norec as norec_pa',
                'ps.nobpjs as nomor_kartu',
                'ps.tgllahir as tgl_lahir',
                'ps.objectjeniskelaminfk as gender',
                'dept.id as deptid',
                'kls.kodebpjs as kelas_rawat',
                'kls.namabpjs as kelas_rawat_nama',
                'pas.klsrawathak_kode as kelas_dijamin',
                'kls.reportdisplay as namakelasdaftar',
                'pas.klsrawathak_nama as namakelas',
                'pd.objectstatuspulangfk',
                'ps.beratbadan',
                'rk.id as idrekanan',
                'rk.namarekanan',
                'ic.status as inacbg_status',
                'pd.inacbg_totaltarifrs',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_biayanaikkelas',
                'pas.statuscovid',
                'ps.noidentitas',
                'jk.jeniskelamin',
                'asa.inacbg_kode',
                'sp.inacbg_kode as discharge_status',
                'pd.nocmfk',
                'pd.inacbg_topup',
                'pd.sitb_id',
                'pd.kemkes_dc_status',
                'prs.usia_kehamilan',
                'prs.gravida',
                'prs.partus',
                'prs.abortus',
                'prs.onsetkontraksi',
                'prs.kodeonsetkontraksi',
                'apg.appearance',
                'apg.pulse',
                'apg.grimace',
                'apg.activity',
                'apg.respiration',
                'apg.appearance5',
                'apg.pulse5',
                'apg.grimace5',
                'apg.activity5',
                'apg.respiration5',
                'ru2.namaruangan as ruangan2',
                'apd.norec as norec_apd',
                 // 'dz.dializer_single_use',
                DB::raw("case when pd.jenispelayanan = 2 then true else false end as eksekutif"),

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile)
            ->where(function($query) {
                $query->whereRaw("dept.id != 16 AND dept.id != 9") // Kecuali dept.id 16 & 9
                      ->orWhereRaw("apd.norec = (
                            SELECT apd2.norec
                            FROM antrianpasiendiperiksa_t as apd2
                            JOIN ruangan_m as ru3 ON ru3.id = apd2.objectruanganfk
                            WHERE apd2.noregistrasifk = pd.norec
                            AND ru3.objectdepartemenfk IN (16, 9)
                            ORDER BY CASE WHEN ru3.objectdepartemenfk = 16 THEN 1 ELSE 2 END, apd2.tglmasuk DESC
                            LIMIT 1
                      )");
            });
            $data = $data->orderBy('ps.namapasien', 'asc'); // Mengurutkan berdasarkan nama pasien secara ascending
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 0) {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 0) {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 1) {
            $data = $data->where('pd.tglpulang', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 1) {
            $data = $data->where('pd.tglpulang', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('dept.id', '=', $r['ins']);
        }
        if (isset($r['ruangId']) && $r['ruangId'] != "" && $r['ruangId'] != "undefined") {
            $data = $data->where('ru2.id', '=', $r['ruangId']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->whereIn('ru2.id', explode(',', $r['ruang']));
        }
        if (isset($r['inacbg_status']) && $r['inacbg_status'] != "" && $r['inacbg_status'] != "undefined") {
            if($r['inacbg_status'] == "belum_kirim"){
                $data = $data->whereNull('pd.inacbg_status');
            }else{
                $data = $data->where('pd.inacbg_status', '=', $r['inacbg_status']);
            }
        }
        if (isset($r['isNotSEP']) && $r['isNotSEP'] != "" && $r['isNotSEP'] != "undefined" && $r['isNotSEP'] =="true") {
            $data = $data->whereRaw("(pas.nosep is null or pas.nosep  ='')");
        }
        if (isset($r['dokId']) && $r['dokId'] != "" && $r['dokId'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokId']);
        }
        if (isset($r['status_pasien']) && $r['status_pasien'] != "" && $r['status_pasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['status_pasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['nosep']) && $r['nosep'] != "" && $r['nosep'] != "undefined") {
            $data = $data->where('pas.nosep', '=', $r['nosep']);
        }
        if (isset($r['status']) && $r['status'] != "" && $r['status'] != "undefined") {
            $data = $data->where('pd.statusklaim', '=', $r['status']);
        }
        if (isset($r['isClosing']) && $r['isClosing'] != "" && $r['isClosing'] != "undefined" && $r['isClosing'] == "true") {
            $data = $data->whereNotNull('pd.tglclosing');
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $data = $data->whereNotNull('pd.tglpulang');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '' ) {
            $page = $r['page'];
        }

        $data = $data->orderBy('pd.noregistrasi');
        // $data = $data->get();
        $data = $data ->paginate(isset($r['limit'])?$r['limit']: 10, ['*'], 'page', $page);


        $dataDokKlaim = DB::table("monitoringdokklaim_t")
            // ->where("noregistrasifk", $value->norec)
            ->where("kdprofile", $kdProfile)
            ->where("statusenabled", true);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        $dataDokKlaim = $dataDokKlaim->get();
        $norec_APD = '';
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            try {
                $APD = AntrianPasienDiperiksa::where('noregistrasifk', $r['norec_pd'])
                    ->where('objectruanganfk', $data[0]->ruanganid)
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();
            } catch (Exception $ee) {
            }
        }
        $i = 0;
        $dtdt = '';

        $dataDiagnosa = DB::table('detaildiagnosapasien_t as dp')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'dp.objectdiagnosarmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('dg.kddiagnosa', 'apd.objectasalrujukanfk', 'pd.norec','dp.objectjenisdiagnosafk')
            ->wherein('dp.objectjenisdiagnosarmfk', explode(',', $this->settingFix('jenisDiagnosaINACBG')))

            ->where('pd.kdprofile', $kdProfile)
            ->orderBy('dp.objectjenisdiagnosarmfk', 'asc');
            if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 0) {
                $dataDiagnosa = $dataDiagnosa->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
            }
            if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 0) {
                $dataDiagnosa = $dataDiagnosa->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
            }
            if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 1) {
                $dataDiagnosa = $dataDiagnosa->where('pd.tglpulang', '>=', $r['dari'] . ' 00:00');
            }
            if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 1) {
                $dataDiagnosa = $dataDiagnosa->where('pd.tglpulang', '<=', $r['sampai'] . ' 23:59');
            }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.norec',  $r['norec_pd']);
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] =="true") {
            $dataDiagnosa = $dataDiagnosa->whereNull('dp.objectdiagnosarmfk');
        }
        if (isset($r['isClosing']) && $r['isClosing'] != "" && $r['isClosing'] != "undefined" && $r['isClosing'] == "true") {
            $dataDiagnosa = $dataDiagnosa->whereNotNull('pd.tglclosing');
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $dataDiagnosa = $dataDiagnosa->whereNotNull('pd.tglpulang');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dataDiagnosa = $dataDiagnosa->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.namapasien', 'ilike', $searchTerm);
                    // ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    // ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    // ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $dataDiagnosa = $dataDiagnosa->get();
        foreach ($data as $item) {
            $dtdt = '';
            $asalRujukan = '';
            $covid19_status_cd = '';
            foreach ($dataDiagnosa as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' .  $item2->kddiagnosa;
                    $asalRujukan = $item2->objectasalrujukanfk;
                }
            }
            $data[$i]->icd10 = substr($dtdt, 1, strlen($dtdt) - 1);
            $data[$i]->codernik = $codernik;
            $data[$i]->objectasalrujukanfk = $asalRujukan;
            $data[$i]->kodetarif = $kodetarif;

            foreach ($dataMaster as $itemXX) {

                if ($item->deptid == $itemXX->objectdepartemenfk) {
                    $data[$i]->dokumen[] = array(
                        'name' => $itemXX->dokumen,
                        'urutan' => $itemXX->id,
                        'checked' => true,
                        'kodeexternal' => $itemXX->kodeexternal,
                        'norec_pd' =>   $data[$i]->norec,
                        'noregistrasi' =>   $data[$i]->noregistrasi,
                        'tglregistrasi' =>   $data[$i]->tgl_masuk,
                        'documentklaimfk' => $itemXX->id,
                        'api' => $itemXX->api,
                        'doc' => null
                    );
                }
            }
            foreach ($dataDokKlaim as $datDok) {
                if ($datDok->noregistrasifk == $item->norec) {
                    foreach ($data[$i]->dokumen as $dd => $vv) {
                        if ($datDok->documentklaimfk == $vv['documentklaimfk']) {
                            $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                        }
                    }
                }
            }

            $i = $i + 1;
        }

        $i = 0;
        $dtdt = '';
        $dataICD9 = DB::table('diagnosatindakanpasien_t as dpa')
            ->join('detaildiagnosatindakanpasien_t as dp', 'dpa.norec', '=', 'dp.objectdiagnosatindakanpasienfk')
            ->join('diagnosatindakan_m as dg', 'dg.id', '=', 'dp.objectdiagnosatindakanrmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dpa.objectpasienfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('dg.kddiagnosatindakan', 'pd.norec')
            ->where('pd.kdprofile', $kdProfile);
            if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 0) {
                $dataICD9 = $dataICD9->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
            }
            if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 0) {
                $dataICD9 = $dataICD9->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
            }
            if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined" && $r['tipeTgl'] == 1) {
                $dataICD9 = $dataICD9->where('pd.tglpulang', '>=', $r['dari'] . ' 00:00');
            }
            if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined" && $r['tipeTgl'] == 1) {
                $dataICD9 = $dataICD9->where('pd.tglpulang', '<=', $r['sampai'] . ' 23:59');
            }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.norec',  $r['norec_pd']);
        }
        if (isset($r['isClosing']) && $r['isClosing'] != "" && $r['isClosing'] != "undefined" && $r['isClosing'] == "true") {
            $dataICD9 = $dataICD9->whereNotNull('pd.tglclosing');
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $dataICD9 = $dataICD9->whereNotNull('pd.tglpulang');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dataICD9 = $dataICD9->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                ->orWhere('ps.nocm', 'ilike', $searchTerm)
                ->orWhere('ps.namapasien', 'ilike', $searchTerm);
                    // ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    // ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    // ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    // ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $dataICD9 = $dataICD9->get();
        foreach ($data as $item) {
            $data[$i]->jenis_rawat = 2;
            foreach ($kdDepartemenRawatInap as $kddept) {
                if ($kddept == $item->deptid) {
                    $data[$i]->jenis_rawat = 1;
                }
            }
            $dtdt = '';
            foreach ($dataICD9 as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' . $item2->kddiagnosatindakan;
                }
            }
            $data[$i]->icd9 = substr($dtdt, 1, strlen($dtdt) - 1);
            $i = $i + 1;
        }

        $dariawal = '';
        $sampaiakhir = '';
        $noregs = '';
        $norms = '';
        $namas = '';
        $norec_pd = '';
        $ooooor = '';
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dariawal = " and pd.tglregistrasi >= '$r[dari] 00:00'";
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $sampaiakhir = " and pd.tglregistrasi <= '$r[sampai] 23:59'";
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $noregs = " and pd.noregistrasi='$r[noreg]'";
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $norms = " and ps.nocm='$r[nocm]'";
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $namas = " and ps.namapasien ilike '%" . $r['nama'] . "%'";
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = $r['search'];
            $ooooor =  " and ( pd.noregistrasi ilike '%$searchTerm%'
            or ps.namapasien ilike '%$searchTerm%'
            or ps.nocm ilike '%$searchTerm%'
            )";

        }

        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $norec_pd = " and pd.norec='$r[norec_pd]'";
        }
        $dataTarif16Non = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal,pp.produkfk,pr.namaproduk
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                LEFT JOIN kelompokprodukbpjs_m as kpb on pr.objectkelompokprodukbpjsfk  =kpb.id
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                and pr.objectkelompokprodukbpjsfk is null
                $dariawal
                $sampaiakhir
                --and kp.id in ($kelompokPasienINACBG)
                $noregs
                $namas
                $norms
                $norec_pd
                $ooooor
                group by pd.norec,kpb.namaexternal ,pp.produkfk,pr.namaproduk
                order by pd.norec")
        );


        $dataTarif16 = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                INNER JOIN kelompokprodukbpjs_m as kpb on kpb.id=pr.objectkelompokprodukbpjsfk
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                --and kp.id in ($kelompokPasienINACBG)
                $dariawal
                $sampaiakhir
                $noregs
                $namas
                $norms
                $norec_pd
                $ooooor
                group by pd.norec,kpb.namaexternal
                order by pd.norec")
        );
        $i = 0;
        $datatatat = array(
            'prosedur_non_bedah' => 0,
            'prosedur_bedah' => 0,
            'konsultasi' => 0,
            'tenaga_ahli' => 0,
            'keperawatan' => 0,
            'penunjang' => 0,
            'radiologi' => 0,
            'laboratorium' => 0,
            'pelayanan_darah' => 0,
            'rehabilitasi' => 0,
            'kamar' => 0,
            'rawat_intensif' => 0,
            'obat' => 0,
            'obat_kronis' => 0,
            'obat_kemoterapi' => 0,
            'alkes' => 0,
            'bmhp' => 0,
            'sewa_alat' => 0,
        );
        $keys = array_keys($datatatat);
        foreach ($data as $item) {
            if (!empty($APD)) {
                if ($APD->noregistrasifk == $data[$i]->norec) {
                    $data[$i]->norec_apd = $APD->norec;
                }
            }
            $data[$i]->payor_id  = 3;
            $data[$i]->belum_mapping = [];
            $data[$i]->totalmappingtarif = 0;
            $data[$i]->totalbilling = 0;
            $data[$i]->umur_tahun = $this->getAgeYear($data[$i]->tgl_lahir, date('Y-m-d'));
            foreach ($dataTarif16 as $itm) {
                if ($itm->norec == $data[$i]->norec) {
                    foreach ($keys as $k) {
                        if ($itm->namaexternal == $k) {
                            $datatatat[$k] = (float)$itm->ttl;
                            $data[$i]->totalmappingtarif = $data[$i]->totalmappingtarif + (float)$itm->ttl;;
                            break;
                        }
                    }
                }
            }
            foreach ($dataTarif16Non as $itms) {
                if ($itms->norec == $data[$i]->norec) {
                    $data[$i]->totalbilling = $data[$i]->totalbilling + (float)$itms->ttl;;
                    $data[$i]->belum_mapping[] = $itms;
                }
            }


            $data[$i]->tarif_rs = $datatatat;
            $data[$i]->totalbilling = $data[$i]->totalbilling + $data[$i]->totalmappingtarif;
            $data[$i]->new_claim  = $this->new_claim($data[$i]);
            $data[$i]->set_claim_data  = $this->set_claim_data($data[$i], $depRI, $depIGD);
            $data[$i]->grouper  = $this->grouper($data[$i]);
            $data[$i]->delete_claim  = $this->delete_claim($data[$i]);
            if( $data[$i]->usia_kehamilan != null ){
                $data2 = DB::table('persalinandetail_t as pd')->where('norecpd', $data[$i]->norec)->get();
                $data[$i]->delivery = $data2;
            }else{
                $data[$i]->delivery = null;
            }

            $i = $i + 1;
        }
        $data = $data->toArray();
        if(isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] == 'true'){
            $dataR = [];
            // return count($data['data']);
            for ($i = count($data['data']) - 1; $i >= 0; $i--) {
                if($data['data'][$i]->icd10 == false){
                    $dataR[] = $data['data'][$i];
                }
            }
            $data['data'] = $dataR;
        }

        // $result['total'] = count($data);
        // $result['data'] = $data;
        return $this->respond($data);
    }

    public function exportDaftarPasienINACBG(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $depRI =  $this->settingFix('kdDepartemenRanapFix');
        $depIGD =   $this->settingFix('idDepartemenIGD');
        $deptRanap = explode(',', $depRI);
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();
        $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile);
        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $dataMaster = $dataMaster->where('objectdepartemenfk', '=', $request['deptId']);
        };
        $dataMaster = $dataMaster->orderBy('nourut');
        $dataMaster = $dataMaster->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'pd.statusverifikasi',
                'pd.tglverifklaim',
                'ru.namaruangan',
                'ps.namapasien as nama_pasien',
                'kp.kelompokpasien',
                'pd.statuspasien',
                'pg.id as pgid',
                'pg.namalengkap as nama_dokter',
                'kp.id as kpid',
                'pd.objectruanganlastfk as ruanganid',
                'pas.nosep as nomor_sep',
                'pas.norec as norec_pa',
                'ps.nobpjs as nomor_kartu',
                'ps.tgllahir as tgl_lahir',
                'ps.objectjeniskelaminfk as gender',
                'dept.id as deptid',
                'kls.kodebpjs as kelas_rawat',
                'kls.namabpjs as kelas_rawat_nama',
                'pas.klsrawathak_kode as kelas_dijamin',
                'kls.reportdisplay as namakelasdaftar',
                'pas.klsrawathak_nama as namakelas',
                'pd.objectstatuspulangfk',
                'ps.beratbadan',
                'rk.id as idrekanan',
                'ic.status as inacbg_status',
                'pd.inacbg_totaltarifrs',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_biayanaikkelas',
                'pas.statuscovid',
                'ps.noidentitas',
                'jk.jeniskelamin',
                'asa.inacbg_kode',
                'sp.inacbg_kode as discharge_status',
                'pd.nocmfk',
                'pd.inacbg_topup',
                'pd.sitb_id',
                'pd.kemkes_dc_status',
                'pd.ketverifikasi',
                'rk.namarekanan',
                DB::raw("case when pd.jenispelayanan = 2 then true else false end as eksekutif"),

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('dept.id', '=', $r['ins']);
        }
        if (isset($r['ruangId']) && $r['ruangId'] != "" && $r['ruangId'] != "undefined") {
            $data = $data->where('ru.id', '=', $r['ruangId']);
        }

        if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 0) {
            $data = $data->whereNotIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 1) {
            $data = $data->whereIn('dept.id', [16]);
        } else if (isset($r['tab']) && $r['tab'] != "" && $r['tab'] != "undefined" && $r['tab'] == 2) {
            $data = $data->whereIn('dept.id', [9]);
        }
        if (isset($r['inacbg_status']) && $r['inacbg_status'] != "" && $r['inacbg_status'] != "undefined") {
            if($r['inacbg_status'] == "belum_kirim"){
                $data = $data->whereNull('pd.inacbg_status');
            }else{
                $data = $data->where('pd.inacbg_status', '=', $r['inacbg_status']);
            }
        }
        if (isset($r['isNotSEP']) && $r['isNotSEP'] != "" && $r['isNotSEP'] != "undefined" && $r['isNotSEP'] =="true") {
            $data = $data->whereRaw("(pas.nosep is null or pas.nosep  ='')");
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] =="true") {
            $data = $data->whereNull('pd.inacbg_status');
        }
        if (isset($r['isNotVerifikasi']) && $r['isNotVerifikasi'] != "" && $r['isNotVerifikasi'] != "undefined" && $r['isNotVerifikasi'] =="true") {
            $data = $data->whereNull('pd.tglverifklaim');
        }
        if (isset($r['isCatatan']) && $r['isCatatan'] != "" && $r['isCatatan'] != "undefined" && $r['isCatatan'] =="true") {
            $data = $data->whereNotNull('pd.tglverifklaim')->where('pd.ketverifikasi', '!=', '-');
        }
        if (isset($r['isClosing']) && $r['isClosing'] != "" && $r['isClosing'] != "undefined" && $r['isClosing'] == "true") {
            $data = $data->whereNotNull('pd.tglclosing');
        }
        $kelPasien  = '';
        if (isset($r['kelId']) && $r['kelId'] != "" && $r['kelId'] != "undefined") {
            $arrKel = explode(',', $r['kelId']);
            $kodeKel = [];
            foreach ($arrKel as $item) {
                $kodeKel[] = (int) $item;
            }
            $kelPasien = ' and kp.id in (' . $r['kelId'] . ')';
            $data = $data->whereIn('kp.id', $kodeKel);
        } else {
            // $data = $data->whereIn('kp.id',  explode(',',$   kelompokPasienINACBG));

        }
        if (isset($r['dokId']) && $r['dokId'] != "" && $r['dokId'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokId']);
        }
        if (isset($r['status_pasien']) && $r['status_pasien'] != "" && $r['status_pasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['status_pasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['nosep']) && $r['nosep'] != "" && $r['nosep'] != "undefined") {
            $data = $data->where('pas.nosep', '=', $r['nosep']);
        }
        if (isset($r['status']) && $r['status'] != "" && $r['status'] != "undefined") {
            $data = $data->where('pd.statusklaim', '=', $r['status']);
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $data = $data->whereNotNull('pd.tglpulang');
        }
        if (isset($r['isPenunjang']) && $r['isPenunjang'] != "" && $r['isPenunjang'] != "undefined" && $r['isPenunjang'] =="true") {
            $data = $data->whereIn('ru.objectdepartemenfk', [3,27]);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('rk.namarekanan', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        // $data = $data->orderBy('pd.noregistrasi');
        // $data = $data->get();

        // $page = 1;
        // if (isset($r['page']) && $r['page'] != '' ) {
        //     $page = $r['page'];
        // }

        $data = $data->orderBy('pd.noregistrasi');
        $data = $data->get();
        // return $data;
        // $data = $data ->paginate(isset($r['limit'])?$r['limit']: 10, ['*'], 'page', $page);


        $dataDokKlaim = DB::table("monitoringdokklaim_t")
            // ->where("noregistrasifk", $value->norec)
            ->where("kdprofile", $kdProfile)
            ->where("statusenabled", true);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        $dataDokKlaim = $dataDokKlaim->get();
        $norec_APD = '';
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            try {
                $APD = AntrianPasienDiperiksa::where('noregistrasifk', $r['norec_pd'])
                    ->where('objectruanganfk', $data[0]->ruanganid)
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();
            } catch (Exception $ee) {
            }
        }
        $i = 0;
        $dtdt = '';

        $dataDiagnosa = DB::table('detaildiagnosapasien_t as dp')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'dp.objectdiagnosafk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('dg.kddiagnosa', 'apd.objectasalrujukanfk', 'pd.norec','dp.objectjenisdiagnosafk')
            ->wherein('dp.objectjenisdiagnosafk', explode(',', $this->settingFix('jenisDiagnosaINACBG')))

            ->where('pd.kdprofile', $kdProfile)
            ->orderBy('dp.objectjenisdiagnosafk', 'asc');
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.norec',  $r['norec_pd']);
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] =="true") {
            $dataDiagnosa = $dataDiagnosa->whereNull('dp.objectdiagnosafk');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dataDiagnosa = $dataDiagnosa->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.namapasien', 'ilike', $searchTerm);
                    // ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    // ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    // ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $dataDiagnosa = $dataDiagnosa->get();

        foreach ($data as $item) {
            $dtdt = '';
            $asalRujukan = '';
            $covid19_status_cd = '';
            foreach ($dataDiagnosa as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' .  $item2->kddiagnosa;
                    $asalRujukan = $item2->objectasalrujukanfk;
                }
            }
            $data[$i]->icd10 = substr($dtdt, 1, strlen($dtdt) - 1);
            $data[$i]->codernik = $codernik;
            $data[$i]->objectasalrujukanfk = $asalRujukan;
            $data[$i]->kodetarif = $kodetarif;

            foreach ($dataMaster as $itemXX) {

                if ($item->deptid == $itemXX->objectdepartemenfk) {
                    $data[$i]->dokumen[] = array(
                        'name' => $itemXX->dokumen,
                        'kodeexternal' => $itemXX->kodeexternal,
                        'norec_pd' =>   $data[$i]->norec,
                        'noregistrasi' =>   $data[$i]->noregistrasi,
                        'tglregistrasi' =>   $data[$i]->tgl_masuk,
                        'documentklaimfk' => $itemXX->id,
                        'api' => $itemXX->api,
                        'doc' => null
                    );
                }
            }
            foreach ($dataDokKlaim as $datDok) {
                if ($datDok->noregistrasifk == $item->norec) {
                    foreach ($data[$i]->dokumen as $dd => $vv) {
                        if ($datDok->documentklaimfk == $vv['documentklaimfk']) {
                            $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                        }
                    }
                }
            }

            $i = $i + 1;
        }

        $i = 0;
        $dtdt = '';
        $dataICD9 = DB::table('diagnosatindakanpasien_t as dpa')
            ->join('detaildiagnosatindakanpasien_t as dp', 'dpa.norec', '=', 'dp.objectdiagnosatindakanpasienfk')
            ->join('diagnosatindakan_m as dg', 'dg.id', '=', 'dp.objectdiagnosatindakanfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dpa.objectpasienfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('dg.kddiagnosatindakan', 'pd.norec')
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.norec',  $r['norec_pd']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dataICD9 = $dataICD9->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                ->orWhere('ps.nocm', 'ilike', $searchTerm)
                ->orWhere('ps.namapasien', 'ilike', $searchTerm);
                    // ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    // ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    // ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    // ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $dataICD9 = $dataICD9->get();
        foreach ($data as $item) {
            $data[$i]->jenis_rawat = 2;
            foreach ($kdDepartemenRawatInap as $kddept) {
                if ($kddept == $item->deptid) {
                    $data[$i]->jenis_rawat = 1;
                }
            }
            $dtdt = '';
            foreach ($dataICD9 as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' . $item2->kddiagnosatindakan;
                }
            }
            $data[$i]->icd9 = substr($dtdt, 1, strlen($dtdt) - 1);
            $i = $i + 1;
        }

        $dariawal = '';
        $sampaiakhir = '';
        $noregs = '';
        $norms = '';
        $namas = '';
        $norec_pd = '';
        $ooooor = '';
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dariawal = " and pd.tglregistrasi >= '$r[dari] 00:00'";
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $sampaiakhir = " and pd.tglregistrasi <= '$r[sampai] 23:59'";
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $noregs = " and pd.noregistrasi='$r[noreg]'";
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $norms = " and ps.nocm='$r[nocm]'";
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $namas = " and ps.namapasien ilike '%" . $r['nama'] . "%'";
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = $r['search'];
            $ooooor =  " and ( pd.noregistrasi ilike '%$searchTerm%'
            or ps.namapasien ilike '%$searchTerm%'
            or ps.nocm ilike '%$searchTerm%'
            or rk.namarekanan ilike '%$searchTerm%'
            )";

        }

        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $norec_pd = " and pd.norec='$r[norec_pd]'";
        }
        $dataTarif16Non = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal,pp.produkfk,pr.namaproduk
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                LEFT JOIN kelompokprodukbpjs_m as kpb on pr.objectkelompokprodukbpjsfk  =kpb.id
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                left join rekanan_m as rk on rk.id = pd.objectrekananfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                and pr.objectkelompokprodukbpjsfk is null
                $dariawal
                $sampaiakhir
                --and kp.id in ($kelompokPasienINACBG)
                $noregs
                $namas
                $norms
                $kelPasien
                $norec_pd
                $ooooor
                group by pd.norec,kpb.namaexternal ,pp.produkfk,pr.namaproduk
                order by pd.norec")
        );


        $dataTarif16 = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                INNER JOIN kelompokprodukbpjs_m as kpb on kpb.id=pr.objectkelompokprodukbpjsfk
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                left join rekanan_m as rk on rk.id = pd.objectrekananfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                --and kp.id in ($kelompokPasienINACBG)
                $dariawal
                $sampaiakhir
                $noregs
                $namas
                $norms
                $kelPasien
                $norec_pd
                $ooooor
                group by pd.norec,kpb.namaexternal
                order by pd.norec")
        );
        $i = 0;
        $datatatat = array(
            'prosedur_non_bedah' => 0,
            'prosedur_bedah' => 0,
            'konsultasi' => 0,
            'tenaga_ahli' => 0,
            'keperawatan' => 0,
            'penunjang' => 0,
            'radiologi' => 0,
            'laboratorium' => 0,
            'pelayanan_darah' => 0,
            'rehabilitasi' => 0,
            'kamar' => 0,
            'rawat_intensif' => 0,
            'obat' => 0,
            'obat_kronis' => 0,
            'obat_kemoterapi' => 0,
            'alkes' => 0,
            'bmhp' => 0,
            'sewa_alat' => 0,
        );
        $keys = array_keys($datatatat);
        // return $data;
        foreach ($data as $item) {
            if (!empty($APD)) {
                if ($APD->noregistrasifk == $data[$i]->norec) {
                    $data[$i]->norec_apd = $APD->norec;
                }
            }
            $data[$i]->payor_id  = 3;
            $data[$i]->belum_mapping = [];
            $data[$i]->totalmappingtarif = 0;
            $data[$i]->totalbilling = 0;
            $data[$i]->umur_tahun = $this->getAgeYear($data[$i]->tgl_lahir, date('Y-m-d'));
            foreach ($dataTarif16 as $itm) {
                if ($itm->norec == $data[$i]->norec) {
                    foreach ($keys as $k) {
                        if ($itm->namaexternal == $k) {
                            $datatatat[$k] = (float)$itm->ttl;
                            $data[$i]->totalmappingtarif = $data[$i]->totalmappingtarif + (float)$itm->ttl;;
                            break;
                        }
                    }
                }
            }
            foreach ($dataTarif16Non as $itms) {
                if ($itms->norec == $data[$i]->norec) {
                    $data[$i]->totalbilling = $data[$i]->totalbilling + (float)$itms->ttl;;
                    $data[$i]->belum_mapping[] = $itms;
                }
            }

            // $data[$i]->tarif_rs = $datatatat;
            // $data[$i]->totalbilling = $data[$i]->totalbilling + $data[$i]->totalmappingtarif;
            // $data[$i]->new_claim  = $this->new_claim($data[$i]);
            // $data[$i]->set_claim_data  = $this->set_claim_data($data[$i], $depRI, $depIGD);
            // $data[$i]->grouper  = $this->grouper($data[$i]);
            // $data[$i]->delete_claim  = $this->delete_claim($data[$i]);

            $i = $i + 1;
        }
        $data = $data->toArray();
        if(isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] == 'true'){
            $dataR = [];
            // return count($data['data']);
            for ($i = count($data['data']) - 1; $i >= 0; $i--) {
                if($data['data'][$i]->icd10 == false){
                    $dataR[] = $data['data'][$i];
                }
            }
            $data['data'] = $dataR;
        }

        // $result['total'] = count($data);
        // $result['data'] = $data;
        return $this->respond($data);
    }

    function new_claim($r)
    {
        $data = array(
            'metadata' =>
            array(
                'method' => 'new_claim',
            ),
            'data' =>
            array(
                'nomor_kartu' => $r->nomor_kartu,
                'nomor_sep' => $r->nomor_sep,
                'nomor_rm' => $r->nomor_rm,
                'nama_pasien' => $r->nama_pasien,
                'tgl_lahir' => $r->tgl_lahir,
                'gender' => $r->gender,
            )
        );
        return $data;
    }
    function set_claim_data($r, $depRI, $depIGD)
    {

        $upgrade_class_ind= '';
        if($depRI == $r->deptid && $r->kelas_dijamin  != null ){
            if( $r->kelas_rawat  != $r->kelas_dijamin  && $r->kelas_rawat < $r->kelas_dijamin )
            $upgrade_class_ind = '1';
        }

        $data = array(
            'metadata' =>
            array(
                'method' => 'set_claim_data',
                'nomor_sep' => $r->nomor_sep,
            ),
            'data' =>
            array(
                'nomor_sep' => $r->nomor_sep,
                'nomor_kartu' => $r->nomor_kartu,
                'tgl_masuk' => $r->tgl_masuk,
                'tgl_pulang' => $r->tgl_pulang,
                'cara_masuk' =>  $r->inacbg_kode ? $r->inacbg_kode : 'other',
                'jenis_rawat' => $depRI == $r->deptid ? 1 : ($depIGD == $r->deptid ? 2 : 2), // 1 = rawat inap, 2 = rawat jalan, 3 = rawat igd
                'kelas_rawat' => $depRI == $r->deptid ? $r->kelas_rawat : $r->kelas_dijamin  ,
                'adl_sub_acute' => '',
                'adl_chronic' =>  '',
                'icu_indikator' =>  '',
                'icu_los' => '',
                'ventilator_hour' =>  '',
                'ventilator' =>
                array(
                    'use_ind' => '',
                    'start_dttm' => '',
                    'stop_dttm' => '',
                ),
                'upgrade_class_ind' => $upgrade_class_ind,//$depRI == $r->deptid && $r->kelas_dijamin  != null && $r->kelas_rawat  != $r->kelas_dijamin ? '1' : '',
                'upgrade_class_class' => $depRI == $r->deptid && $r->kelas_dijamin  != null &&  $r->kelas_rawat  != $r->kelas_dijamin ? $r->kelas_rawat_nama : '',
                'upgrade_class_los' => '',
                'upgrade_class_payor' => $depRI == $r->deptid && $r->kelas_dijamin  != null &&   $r->kelas_rawat  != $r->kelas_dijamin ? 'peserta' : '', //"peserta/pemberi_kerja/asuransi_tambahan
                'add_payment_pct' => '',
                'birth_weight' => '',
                'sistole' => '',
                'diastole' => '',
                'discharge_status' => $r->discharge_status ? $r->discharge_status : '1',
                'diagnosa' => $r->icd10,
                'procedure' => $r->icd9,
                'diagnosa_inagrouper' => $r->icd10,
                'procedure_inagrouper' =>  $r->icd9,
                'tarif_rs' => $r->tarif_rs,
                'pemulasaraan_jenazah' => '',
                'kantong_jenazah' => '',
                'peti_jenazah' => '',
                'plastik_erat' => '',
                'desinfektan_jenazah' => '',
                'mobil_jenazah' => '',
                'desinfektan_mobil_jenazah' => '',
                'covid19_status_cd' => '',
                'nomor_kartu_t' => 'kartu_jkn',
                'episodes' => '',
                'covid19_cc_ind' => '',
                'covid19_rs_darurat_ind' => '',
                'covid19_co_insidense_ind' => '',
                'covid19_penunjang_pengurang' =>
                array(
                    'lab_asam_laktat' => '',
                    'lab_procalcitonin' => '',
                    'lab_crp' => '',
                    'lab_kultur' => '',
                    'lab_d_dimer' => '',
                    'lab_pt' => '',
                    'lab_aptt' => '',
                    'lab_waktu_pendarahan' => '',
                    'lab_anti_hiv' => '',
                    'lab_analisa_gas' => '',
                    'lab_albumin' => '',
                    'rad_thorax_ap_pa' => '',
                ),
                'terapi_konvalesen' => '',
                'akses_naat' => '',
                'isoman_ind' => '',
                'bayi_lahir_status_cd' => $r->payor_id == 7 ? '1' : '',
                'dializer_single_use' => '',
                'kantong_darah' => '',
                'apgar' =>
                array(
                    'menit_1' =>
                    array(
                        'appearance' => '',
                        'pulse' => '',
                        'grimace' => '',
                        'activity' => '',
                        'respiration' => '',
                    ),
                    'menit_5' =>
                    array(
                        'appearance' => '',
                        'pulse' => '',
                        'grimace' => '',
                        'activity' => '',
                        'respiration' => '',
                    ),
                ),
                'persalinan' =>
                array(
                    'usia_kehamilan' => '',
                    'gravida' => '',
                    'partus' => '',
                    'abortus' => '',
                    'onset_kontraksi' => '',
                    'delivery' =>
                    array(
                        0 =>
                        array(
                            'delivery_sequence' => '',
                            'delivery_method' => '',
                            'delivery_dttm' => '',
                            'letak_janin' => '',
                            'kondisi' => '',
                            'use_manual' => '',
                            'use_forcep' => '',
                            'use_vacuum' => '',
                            'shk_spesimen_ambil' => 'tidak',
                            'shk_alasan' => 'tidak-dapat',
                            'shk_lokasi' => '',
                            'shk_spesimen_dttm' => '',
                        ),
                        1 =>
                        array(
                            'delivery_sequence' => '',
                            'delivery_method' => '',
                            'delivery_dttm' => '',
                            'letak_janin' => '',
                            'kondisi' => '',
                            'use_manual' => '',
                            'use_forcep' => '',
                            'use_vacuum' => '',
                            'shk_spesimen_ambil' => 'tidak',
                            'shk_alasan' => 'tidak-dapat',
                            'shk_lokasi' => '',
                            'shk_spesimen_dttm' => '',
                        ),
                    ),
                ),
                'tarif_poli_eks' => '',
                'nama_dokter' => $r->nama_dokter,
                'kode_tarif' => $r->kodetarif,
                'payor_id' =>  $r->payor_id,
                'payor_cd' => 'JKN',
                'cob_cd' => '#',
                'coder_nik' =>  $r->codernik,
            ),
        );
        return $data;
    }
    function grouper($r)
    {
        $data = array(
            'metadata' =>
            array(
                'method' => 'grouper',
                "stage" => "1"
            ),
            'data' =>
            array(
                'nomor_sep' => $r->nomor_sep,
            )
        );
        return $data;
    }
    function delete_claim($r)
    {
        $data = array(
            'metadata' =>
            array(
                'method' => 'delete_claim',
            ),
            'data' =>
            array(
                'nomor_sep' => $r->nomor_sep,
                'coder_nik' => $r->codernik,
            )
        );
        return $data;
    }
    public function saveStatusBridgingINACBG(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request['data'] as $value) {
                $pd = PasienDaftar::where('norec', $value['norec'])
                    ->first();
                $pd->inacbg_status = $value['inacbg_status'] == 'delete_claim' ? null : $value['inacbg_status'];
                if (isset($value['kemkes_dc_status'])) {
                    $pd->kemkes_dc_status = $value['kemkes_dc_status'];
                }
                if (isset($value['bpjs_dc_status'])) {
                    $pd->bpjs_dc_status = $value['bpjs_dc_status'];
                }
                if (isset($value['cob_dc_status'])) {
                    $pd->cob_dc_status = $value['cob_dc_status'];
                }
                if (isset($value['diagnosa'])) {
                    $pd->inacbg_diagnosa = $value['diagnosa'];
                }
                if (isset($value['procedure'])) {
                    $pd->inacbg_procedure = $value['procedure'];
                }
                if (isset($value['diagnosa_inagrouper'])) {
                    $pd->inacbg_diagnosa_inagrouper = $value['diagnosa_inagrouper'];
                }
                if (isset($value['procedure_inagrouper'])) {
                    $pd->inacbg_procedure_inagrouper = $value['procedure_inagrouper'];
                }
                if ($value['inacbg_status'] == 'delete_claim') {
                    $pd->inacbg_totalgrouper = null;
                    $pd->inacbg_biayanaikkelas = null;
                    $pd->inacbg_totaltarifrs = null;
                    $pd->inacbg_grouper = null;
                }

                if ($value['inacbg_status'] == 'sitb_validate') {
                    $pd->sitb_id = $value['sitb_id'];
                }
                if (isset($value['dokterdpjp']) && $value['dokterdpjp'] != null) {
                    if($pd->objectpegawaifk != $value['dokterdpjp']) {
                        $dokterlama = Pegawai::mine()->where('id', $pd->objectpegawaifk)->first();
                        $dokterbaru = Pegawai::mine()->where('id', $value['dokterdpjp'])->first();
                        $pd->objectpegawaifk = $value['dokterdpjp'];
                        $this->LOGGING(
                            'Registrasi Pasien',
                            $pd->norec,
                            'pasiendaftar_t',
                            'Merubah DPJP di Noregistrasi '.$pd->noregistrasi.'  dari '. $dokterlama->namalengkap .' ke '. $dokterbaru->namalengkap .' tgl '. date('Y-m-d')
                        );
                    }
                }
                $pd->save();
            }

            $transMessage = "Sukses";
            DB::commit();
            $ihs = null;
            // if ($value['inacbg_status'] == 'new_claim') {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $pd->noregistrasi;
                $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Composition($objetoRequest, true);
            // }
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
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveGroupingINACBG(Request $r)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $pd = PasienDaftar::where('norec', $r['norec'])->first();
            $totalBiaya =  PelayananPasien::totalTagihan($pd->noregistrasi);

            $totalpertindakan = PelayananPasien::where('kdprofile', $kdProfile)
                ->where('statusenabled', true)
                ->where('noregistrasi', $pd->noregistrasi)
                ->select(DB::raw("norec,
                COALESCE (hargadiscount, 0)  as diskon,
                ( (hargasatuan  - COALESCE (hargadiscount, 0))   * jumlah)
                + ( COALESCE (jasa, 0)) as totalbiayapertindakan"))
                ->get();
            foreach ($totalpertindakan->chunk(1000) as $chunk) {
                $cases = [];
                $ids = [];
                foreach ($chunk as $item) {
                    $proporsipertindakan = ((float)$r['totaldijamin'] / (float)$totalBiaya) * (float)$item->totalbiayapertindakan;
                    $cases[] = "WHEN '{$item->norec}' then " . $proporsipertindakan;
                    $ids[] = "'" . $item->norec . "'";
                }
                $ids = implode(',', $ids);
                $cases = implode(' ', $cases);

                if (!empty($ids)) {
                    DB::update("UPDATE pelayananpasien_t SET piutangpenjamin = CASE norec {$cases} END WHERE norec in ({$ids})");
                }
            }

            $pd->inacbg_totalgrouper = (float)$r['totaldijamin'];
            $pd->inacbg_biayanaikkelas = (float)$r['biayanaikkelas'];
            $pd->inacbg_totaltarifrs = (float)$totalBiaya;
            $pd->inacbg_grouper = json_encode($r['inacbg_grouper']);
            $pd->inacbg_topup = isset($r['totaltopup'])?$r['totaltopup']:null;
            $pd->save();
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
    public function listDokterPaging(Request $r)
    {
        $result['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        $result['dokter'] =
            Pegawai::mine()
            ->where('objectjenispegawaifk', $result['idJenisPegawaiDokter'])
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }
    public function getStatusBridgingINACBG(Request $request)
    {
        $d = PasienDaftar::where('norec', $request['norec'])->first();
        $status = INACBG_Status::mine()->get();
        $stat = $d->inacbg_status;
        foreach ($status as $it) {
            if ($it->inacbg_status == $d->inacbg_status) {
                $stat =  $it->status;
            }
        }

        $res['inacbg_status'] = $stat;
        $res['kemkes_dc_status'] = $d->kemkes_dc_status;
        $res['bpjs_dc_status'] = $d->bpjs_dc_status;
        $res['cob_dc_status'] = $d->cob_dc_status;
        return $this->respond($res);
    }
    public function getGroupingINACBG(Request $request)
    {
        $d = PasienDaftar::where('norec', $request['norec'])->first()->inacbg_grouper;
        if ($d != null) {
            $d =  json_decode($d);
        }
        return $this->respond($d);
    }
    // public function saveDokumenINACBG(Request $dataReq)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $uploadBerkasPasien = $dataReq->file('fileBerkas');
    //         $dataRegistrasi = PasienDaftar::where('norec', $dataReq['norec_pd'])->first();
    //         $path = 'dokumen_klaim/' . $dataRegistrasi->noregistrasi;

    //         $extension = $uploadBerkasPasien->getClientOriginalExtension();
    //         $filename = $dataReq['namafile'] . '_' . $dataRegistrasi->noregistrasi . '.' . $extension;

    //         // $filename = $dataReq['namafile'] . '_' . date('YmdHis') . '.' . $extension;
    //         // DB::table('monitoringdokklaim_t')
    //         // ->where('noregistrasifk', $dataReq['norec_pd'])
    //         // ->where('documentklaimfk', $dataReq['documentklaimfk'])
    //         // ->update(['statusenabled' => false]);

    //         $dataInsert = array(
    //             "norec" => Uuid::uuid4(),
    //             "kdprofile" => $this->kdProfile,
    //             "statusenabled" => true,
    //             "filename" => $filename,
    //             "filepath" => $path . '/' . $filename,
    //             "nocmfk" => $dataRegistrasi->nocmfk,
    //             "noregistrasifk" => $dataReq['norec_pd'],
    //             "documentklaimfk" => $dataReq['documentklaimfk'],
    //             "tglregistrasi" => $dataReq['tglregistrasi'],
    //         );
    //         DB::table('monitoringdokklaim_t')->updateOrInsert([
    //             "noregistrasifk" => $dataReq['norec_pd'],
    //             "documentklaimfk" => $dataReq['documentklaimfk'],
    //         ], $dataInsert);
    //         // $dataReq->file('fileBerkas')->move($path, $filename);

    //         $dataReq->fPut(
    //             $path . '/' . $filename,
    //             File::get($dataReq->file('fileBerkas')->getRealPath()),
    //             'public'
    //         );
    //         $transMessage = "Sukses";
    //         DB::commit();
    //         $result = array(
    //             "status" => 200,
    //             "result" => array(
    //                 "as" => '@epic',
    //                 "filename" => $filename
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

    public function saveDokumenINACBG(Request $dataReq)
    {
        DB::beginTransaction();
        try {
            $uploadBerkasPasien = $dataReq->file('fileBerkas');
            $dataRegistrasi = PasienDaftar::where('norec', $dataReq['norec_pd'])->first();
            $path = 'dokumen_klaim/' . $dataRegistrasi->noregistrasi;

            // Cek apakah file image
            $isImage = in_array($uploadBerkasPasien->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
            $pdfPath = null;

            if ($isImage) {
                $imagePath = $uploadBerkasPasien->getRealPath();
                $imageData = base64_encode(file_get_contents($imagePath));
                $imageSrc = 'data:image/' . $uploadBerkasPasien->getClientOriginalExtension() . ';base64,' . $imageData;

                $html = '<img src="' . $imageSrc . '" style="width: 100%;">';

                if (!File::exists(storage_path('app/' . $path))) {
                    File::makeDirectory(storage_path('app/' . $path), 0777, true, true);
                }

                $filename = $dataReq['namafile'] . '_' . $dataRegistrasi->noregistrasi . '.pdf';
                $pdfPath = storage_path('app/' . $path . '/' . $filename);

                $pdf = Pdf::loadHTML($html);
                $pdf->save($pdfPath);

                // Timpa file image menjadi file pdf
                $uploadBerkasPasien = new \Illuminate\Http\UploadedFile(
                    $pdfPath,
                    $filename,
                    'application/pdf',
                    null,
                    true
                );
            } else {
                $extension = $uploadBerkasPasien->getClientOriginalExtension();
                $filename = $dataReq['namafile'] . '_' . $dataRegistrasi->noregistrasi . '.' . $extension;
            }

            $dataInsert = array(
                "norec" => Uuid::uuid4(),
                "kdprofile" => $this->kdProfile,
                "statusenabled" => true,
                "filename" => $filename,
                "filepath" => $path . '/' . $filename,
                "nocmfk" => $dataRegistrasi->nocmfk,
                "noregistrasifk" => $dataReq['norec_pd'],
                "documentklaimfk" => $dataReq['documentklaimfk'],
                "tglregistrasi" => $dataReq['tglregistrasi'],
            );

            DB::table('monitoringdokklaim_t')->updateOrInsert([
                "noregistrasifk" => $dataReq['norec_pd'],
                "documentklaimfk" => $dataReq['documentklaimfk'],
            ], $dataInsert);

            // Simpan file ke storage/public
            $dataReq->fPut(
                $path . '/' . $filename,
                File::get($uploadBerkasPasien->getRealPath()),
                'public'
            );

            // Hapus file sementara
            if ($pdfPath && file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "filename" => $filename
                ),
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(),
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }


    public function deleteDokumenMonitoring(Request $request)
    {
        $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int) $kdProfile;
        DB::beginTransaction();
        try {
            $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
            $Dkmn = DB::table("monitoringdokklaim_t")
                ->where('noregistrasifk', $dataRegistrasi->norec)
                ->where('documentklaimfk', $request['documentklaimfk'])
                ->where('kdprofile', $idProfile)
                ->first();

            // delete file
            $path = public_path($Dkmn->filepath);
            if (File::exists($Dkmn->filepath)) {
                File::delete($path);
            }

            // detele data
            DB::table("monitoringdokklaim_t")
                ->where('noregistrasifk', $dataRegistrasi->norec)
                ->where('documentklaimfk', $request['documentklaimfk'])
                ->where('kdprofile', $idProfile)
                ->delete();
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
    public function bundleDokumen(Request $request)
    {

        $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])
            ->where('kdprofile', $this->kdProfile)
            ->first();
        $dataDokumen = DB::table('monitoringdokklaim_t as mk')
            ->join("dokumenklaim_m as dk", "dk.id", "=", "mk.documentklaimfk")
            ->select('mk.*')
            ->where('mk.noregistrasifk', $dataRegistrasi->norec)
            ->where('mk.kdprofile', $this->kdProfile)
            ->where('mk.statusenabled', true)
            ->orderBy('dk.nourut')
            ->get();
        $fileName = 'bundle_' . $request['noregistrasi'] . '.pdf';
        $disk = 'app/public/';
        $pathbundle = $disk. 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;
        if (File::exists($pathbundle)) {
            File::delete($pathbundle);
        }
        // $localDisk = empty(config('app.s3telkom'));
        // $storage = 'local';
        // if ($localDisk) {
        //     $disk = $storage;
        // } else {
        //     $disk = 's3'.$storage;
        // }

        // dd($disk);
        if (count($dataDokumen) > 0) {
            $file = [];
            foreach ($dataDokumen as $item) {
                array_push($file, storage_path($disk . $item->filepath));
            }

            $pdf = PDFMerger::init();
            foreach ($file as $data) {
                $pdf->addPDF($data, 'all');
            }
            $pdf->merge();
            $pdf->save(storage_path($pathbundle));

            if (!File::exists(storage_path($pathbundle))) {
                return '
                <script language="javascript">
                    window.alert("Tidak ada data.");
                    window.close()
                </script>';

            }
            $file = File::get(storage_path($pathbundle));

            $type = File::mimeType(storage_path($pathbundle));

            $response = response()->make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        } else {
            return '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';

        }
    }
    public function collectDokumenINACBG(Request $dataReq)
    {
        DB::beginTransaction();
        try {

            $dataRegistrasi = PasienDaftar::where('norec', $dataReq['norec_pd'])->first();

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest ['noregistrasi'] = $dataRegistrasi->noregistrasi;
            $objetoRequest ['user'] = $this->getNamaPegawai();
            $objetoRequest ['storage'] = true;
            $route =  explode('@', $dataReq['api']);
            $fixroute = "App\Http\Controllers\'".str_replace('-', "\'", $route[0]);
            $fix =  str_replace("'", '', $fixroute);
            $funccollect = explode('-', $route[1]);
            $func = $funccollect[0];
            $collection = isset($funccollect[1]) ? $funccollect[1] : "";
            if(isset($funccollect[1])) {
                $pdf = app($fix)->$func($collection, $objetoRequest);
            } else {
                $pdf = app($fix)->$func($objetoRequest);
            }

            $extension = 'pdf';
            $path = 'dokumen_klaim/' . $objetoRequest ['noregistrasi'];
            // if($dataReq['namafile'] == 'billing'){
            //     $filename = $dataReq['namafile']  . $objetoRequest ['noregistrasi'] . '_' . date('YmdHis'). '.' . $extension;
            // } else{
            //     $filename = $dataReq['namafile']  . $objetoRequest ['noregistrasi'] . '.' . $extension;
            // }
            $filename = $dataReq['namafile']  . $objetoRequest ['noregistrasi'] . '.' . $extension;

            // DB::table('monitoringdokklaim_t')
            // ->where('noregistrasifk', $dataReq['norec_pd'])
            // ->where('documentklaimfk', $dataReq['documentklaimfk'])
            // ->update(['statusenabled' => false]);
            $content = null;
            if($dataReq['namafile'] != 'klaim_indi' && $dataReq['namafile'] != 'klaim_ind' && $dataReq['namafile'] != 'hasil_lab_pa'){
                if(empty($pdf)){
                    $transMessage = "Data belum ada";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result"  => null
                    );

                  return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $content = $pdf->download()->getOriginalContent();
            }

            $dataInsert = array(
                "norec" => Uuid::uuid4(),
                "kdprofile" => $this->kdProfile,
                "statusenabled" => true,
                "filename" => $filename,
                "filepath" => $path . '/' . $filename,
                "nocmfk" => $dataRegistrasi->nocmfk,
                "noregistrasifk" => $dataReq['norec_pd'],
                "documentklaimfk" => $dataReq['documentklaimfk'],
                "tglregistrasi" => $dataReq['tglregistrasi'],
            );
            DB::table('monitoringdokklaim_t')->updateOrInsert([
                "noregistrasifk" => $dataReq['norec_pd'],
                "documentklaimfk" => $dataReq['documentklaimfk'],
            ], $dataInsert);

            if($dataReq['namafile'] != 'klaim_indi' && $dataReq['namafile'] != 'klaim_ind'){
                // $dataReq->fPut(
                //     $path . '/' . $filename,
                //     $content  ,
                //     'public'
                // );
                $encrypt = base64_encode($content);
                BundleKlaim::updateOrCreate(
                    [
                        "noregistrasi" => $dataRegistrasi->noregistrasi,
                        "filename" => $filename,
                    ],
                    [
                        "norec" => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                        "data" => $encrypt,
                        "urut" => 1
                    ]
                );
                
            }
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "filename" => $filename
                ),
            );
        } catch (\Exception $e) {
            $transMessage = $e->getMessage();//"Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function verifDokumenINACBG(Request $dataReq)
    {
        DB::beginTransaction();
        try {

            $dataRegistrasi = PasienDaftar::where('norec', $dataReq['norec_pd'])->first();
            $dataRegistrasi->tglverifklaim = date('Y-m-d H:m:s');
            // var_dump($dataReq['statusverifikasi']);
            if($dataReq['statusverifikasi'] == "0"){
                $dataRegistrasi->statusverifikasi = false;
                $dataRegistrasi->ketverifikasi = $dataReq['ketverif'];
            } else if($dataReq['statusverifikasi'] == "1"){
                $dataRegistrasi->statusverifikasi = true;
                $dataRegistrasi->ketverifikasi = 'BENAR';
            } else{
                $dataRegistrasi->statusverifikasi = null;
            }
            $dataRegistrasi->save();

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "data" => $dataRegistrasi
                ),
            );
        } catch (\Exception $e) {
            $transMessage = $e->getMessage();//"Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getFlafonINACBG(Request $r)
        {
            $kdProfile = $this->kdProfile;
            $depRI =  $this->settingFix('kdDepartemenRanapFix');
            $depIGD =   $this->settingFix('idDepartemenIGD');
            $deptRanap = explode(',', $depRI);
            $kdDepartemenRawatInap = [];
            foreach ($deptRanap as $itemRanap) {
                $kdDepartemenRawatInap[] =  (int)$itemRanap;
            }
            $data  = DB::table('settingdatafixed_m')
                ->select('namafield', 'nilaifield')
                ->where('kelompok', "INACBG's")
                ->where('kdprofile', $kdProfile)
                ->get();


            $codernik = '';
            $key = '';
            $url = '';
            $kodetarif = '';
            $kelompokPasienINACBG = '';
            foreach ($data as $item) {
                if ($item->namafield == 'codernik') {
                    $codernik = $item->nilaifield;
                }
                if ($item->namafield == 'key') {
                    $key = $item->nilaifield;
                }
                if ($item->namafield == 'url') {
                    $url = $item->nilaifield;
                }
                if ($item->namafield == 'kodetarif') {
                    $kodetarif = $item->nilaifield;
                }
                if ($item->namafield == 'kelompokPasienINACBG') {
                    $kelompokPasienINACBG = $item->nilaifield;
                }
            }

            $data = DB::table('pasiendaftar_t as pd')
                ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
                ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
                ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
                ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
                ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
                ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
                ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
                ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
                ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
                ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
                ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
                ->select(
                    'pd.norec',
                    'pd.tglregistrasi as tgl_masuk',
                    'pd.tglpulang as tgl_pulang',
                    'ps.nocm as nomor_rm',
                    'pd.noregistrasi',
                    'ru.namaruangan',
                    'ps.namapasien as nama_pasien',
                    'kp.kelompokpasien',
                    'pd.statuspasien',
                    'pg.id as pgid',
                    'pg.namalengkap as nama_dokter',
                    'kp.id as kpid',
                    'pd.objectruanganlastfk as ruanganid',
                    'pas.nosep as nomor_sep',
                    'pas.norec as norec_pa',
                    'ps.nobpjs as nomor_kartu',
                    'ps.tgllahir as tgl_lahir',
                    'ps.objectjeniskelaminfk as gender',
                    'dept.id as deptid',
                    'kls.kodebpjs as kelas_rawat',
                    'kls.namabpjs as kelas_rawat_nama',
                    'pas.klsrawathak_kode as kelas_dijamin',
                    'kls.reportdisplay as namakelasdaftar',
                    'pas.klsrawathak_nama as namakelas',
                    'pd.objectstatuspulangfk',
                    'ps.beratbadan',
                    'rk.id as idrekanan',
                    'ic.status as inacbg_status',
                    'pd.inacbg_totaltarifrs',
                    'pd.inacbg_totalgrouper',
                    'pd.inacbg_biayanaikkelas',
                    'pas.statuscovid',
                    'ps.noidentitas',
                    'jk.jeniskelamin',
                    'asa.inacbg_kode',
                    'sp.inacbg_kode as discharge_status',
                    'pd.nocmfk'

                )
                ->where('pd.statusenabled', true)
                ->where('pd.kdprofile', $kdProfile);
            if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
                $data = $data->where('pd.norec', '=', $r['norec_pd']);
            }
            $data = $data->limit(1);
            $data = $data->get();

            $norec_APD = '';
            if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
                try {
                    $APD = AntrianPasienDiperiksa::where('noregistrasifk', $r['norec_pd'])
                        ->where('objectruanganfk', $data[0]->ruanganid)
                        ->where('kdprofile', $kdProfile)
                        ->where('statusenabled', true)
                        ->first();
                } catch (Exception $ee) {
                }
            }
            $i = 0;
            $dtdt = '';

            $dataDiagnosa = DB::table('detaildiagnosapasien_t as dp')
                ->join('diagnosa_m as dg', 'dg.id', '=', 'dp.objectdiagnosafk')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dp.noregistrasifk')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->select('dg.kddiagnosa', 'apd.objectasalrujukanfk', 'pd.norec')
                ->wherein('dp.objectjenisdiagnosafk', explode(',', $this->settingFix('jenisDiagnosaINACBG')))

                ->where('pd.kdprofile', $kdProfile)
                ->orderBy('dp.objectjenisdiagnosafk', 'asc');

            if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
                $dataDiagnosa = $dataDiagnosa->where('pd.norec',  $r['norec_pd']);
            }

            $dataDiagnosa = $dataDiagnosa->get();
            foreach ($data as $item) {
                $dtdt = '';
                $asalRujukan = '';
                $covid19_status_cd = '';
                foreach ($dataDiagnosa as $item2) {
                    if ($item2->norec == $data[$i]->norec) {
                        $dtdt = $dtdt . '#' .  $item2->kddiagnosa;
                        $asalRujukan = $item2->objectasalrujukanfk;
                    }
                }
                $data[$i]->icd10 = substr($dtdt, 1, strlen($dtdt) - 1);
                $data[$i]->codernik = $codernik;
                $data[$i]->objectasalrujukanfk = $asalRujukan;
                $data[$i]->kodetarif = $kodetarif;


                $i = $i + 1;
            }

            $i = 0;
            $dtdt = '';
            $dataICD9 = DB::table('diagnosatindakanpasien_t as dpa')
                ->join('detaildiagnosatindakanpasien_t as dp', 'dpa.norec', '=', 'dp.objectdiagnosatindakanpasienfk')
                ->join('diagnosatindakan_m as dg', 'dg.id', '=', 'dp.objectdiagnosatindakanfk')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dpa.objectpasienfk')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->select('dg.kddiagnosatindakan', 'pd.norec')
                ->where('pd.kdprofile', $kdProfile);

            if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
                $dataICD9 = $dataICD9->where('pd.norec',  $r['norec_pd']);
            }
            $dataICD9 = $dataICD9->get();
            foreach ($data as $item) {
                $data[$i]->jenis_rawat = 2;
                foreach ($kdDepartemenRawatInap as $kddept) {
                    if ($kddept == $item->deptid) {
                        $data[$i]->jenis_rawat = 1;
                    }
                }
                $dtdt = '';
                foreach ($dataICD9 as $item2) {
                    if ($item2->norec == $data[$i]->norec) {
                        $dtdt = $dtdt . '#' . $item2->kddiagnosatindakan;
                    }
                }
                $data[$i]->icd9 = substr($dtdt, 1, strlen($dtdt) - 1);
                $i = $i + 1;
            }

            $dariawal = '';
            $sampaiakhir = '';
            $noregs = '';
            $norms = '';
            $namas = '';
            $norec_pd = '';

            if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
                $norec_pd = " and pd.norec='$r[norec_pd]'";
            }
            $dataTarif16Non = DB::select(
                DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                    case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal,pp.produkfk,pr.namaproduk
                    from pasiendaftar_t as pd
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                    INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                    INNER JOIN produk_m as pr on pr.id=pp.produkfk
                    LEFT JOIN kelompokprodukbpjs_m as kpb on pr.objectkelompokprodukbpjsfk  =kpb.id
                    left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                    where pd.statusenabled=true
                    and pd.kdprofile=$kdProfile
                    and pr.objectkelompokprodukbpjsfk is null
                    $dariawal
                    $sampaiakhir

                    $noregs
                    $namas
                    $norms

                    $norec_pd
                    group by pd.norec,kpb.namaexternal ,pp.produkfk,pr.namaproduk
                    order by pd.norec")
            );


            $dataTarif16 = DB::select(
                DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                    case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal
                    from pasiendaftar_t as pd
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                    INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                    INNER JOIN produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN kelompokprodukbpjs_m as kpb on kpb.id=pr.objectkelompokprodukbpjsfk
                    left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                    where pd.statusenabled=true
                    and pd.kdprofile=$kdProfile

                    $dariawal
                    $sampaiakhir
                    $noregs
                    $namas
                    $norms

                    $norec_pd
                    group by pd.norec,kpb.namaexternal
                    order by pd.norec")
            );
            $i = 0;
            $datatatat = array(
                'prosedur_non_bedah' => 0,
                'prosedur_bedah' => 0,
                'konsultasi' => 0,
                'tenaga_ahli' => 0,
                'keperawatan' => 0,
                'penunjang' => 0,
                'radiologi' => 0,
                'laboratorium' => 0,
                'pelayanan_darah' => 0,
                'rehabilitasi' => 0,
                'kamar' => 0,
                'rawat_intensif' => 0,
                'obat' => 0,
                'obat_kronis' => 0,
                'obat_kemoterapi' => 0,
                'alkes' => 0,
                'bmhp' => 0,
                'sewa_alat' => 0,
            );
            $keys = array_keys($datatatat);
            $new_claim = null;
            $set_claim_data = null;
            $grouper =  null;
            $delete_claim = null;
            foreach ($data as $item) {
                if (!empty($APD)) {
                    if ($APD->noregistrasifk == $data[$i]->norec) {
                        $data[$i]->norec_apd = $APD->norec;
                    }
                }
                $data[$i]->payor_id  = 3;
                $data[$i]->belum_mapping = [];
                $data[$i]->totalmappingtarif = 0;
                $data[$i]->totalbilling = 0;
                $data[$i]->umur_tahun = $this->getAgeYear($data[$i]->tgl_lahir, date('Y-m-d'));
                foreach ($dataTarif16 as $itm) {
                    if ($itm->norec == $data[$i]->norec) {
                        foreach ($keys as $k) {
                            if ($itm->namaexternal == $k) {
                                $datatatat[$k] = (float)$itm->ttl;
                                $data[$i]->totalmappingtarif = $data[$i]->totalmappingtarif + (float)$itm->ttl;;
                                break;
                            }
                        }
                    }
                }
                foreach ($dataTarif16Non as $itms) {
                    if ($itms->norec == $data[$i]->norec) {
                        $data[$i]->totalbilling = $data[$i]->totalbilling + (float)$itms->ttl;;
                        $data[$i]->belum_mapping[] = $itms;
                    }
                }

                $data[$i]->tarif_rs = $datatatat;
                $data[$i]->totalbilling = $data[$i]->totalbilling + $data[$i]->totalmappingtarif;
                $data[$i]->new_claim  = $this->new_claim($data[$i]);
                $data[$i]->set_claim_data  = $this->set_claim_data($data[$i], $depRI, $depIGD);
                $data[$i]->grouper  = $this->grouper($data[$i]);
                $data[$i]->delete_claim  = $this->delete_claim($data[$i]);
                $new_claim =   $data[$i]->new_claim ;
                $set_claim_data =  $data[$i]->set_claim_data ;
                $grouper =    $data[$i]->grouper ;
                $delete_claim =   $data[$i]->delete_claim ;

                $i = $i + 1;
            }

            // $result['total'] = count($data);

            $result['new_claim'] = $new_claim;
            $result['set_claim_data'] = $set_claim_data;
            $result['grouper'] = $grouper;
            $result['delete_claim'] = $delete_claim;
            $result['data'] = $data;
            return $this->respond($result);
        }

        public function claimPRINT(Request $request)
        {

            $data=  DB::table('pemakaianasuransi_t as pa')
            ->join('pasiendaftar_t as pd','pd.norec','=','pa.noregistrasifk')
            ->select('pa.nosep')
            ->where('pd.noregistrasi',$request['noregistrasi'])
            ->where('pd.kdprofile',$this->kdProfile)
            ->first();

            if(empty($data)){
                return null;
            }
            if($data->nosep == null || $data->nosep == ''){
                return null;
            }

            $objreqhis = new \Illuminate\Http\Request();
            $objreqhis['data'] = [
                array(
                    "metadata" => array(
                        "method" => "claim_print"
                    ),
                    "data" =>  array(
                        "nomor_sep" => $data->nosep
                    )
                )
            ];
            $response =  $this->saveBridgingINACBG($objreqhis, true);
            if(isset($response['dataresponse'][0]['dataresponse']->metadata) && $response['dataresponse'][0]['dataresponse']->metadata->code == 200){
                // $fileData = 'data:application/pdf;base64,'. $response['dataresponse'][0]['dataresponse']->data;
                $sourcePdf = $response['dataresponse'][0]['dataresponse']->data;
            //    return view('report.registrasi.preview-pdf', compact('sourcePdf'));

                $extension = 'pdf';
                $path = 'dokumen_klaim/' . $request['noregistrasi'];
                $filename1 = 'klaim_indi' . $request['noregistrasi'] . '.' . $extension;
                // $filename2 = 'klaim_ind' . $request['noregistrasi'] . '.' . $extension;

                $noUrut = BundleKlaim::where('noregistrasi', $request['noregistrasi'])
                ->where('filename', $filename1)
                ->orderBy('urut', 'desc')
                ->first();

                // $noUrut = DB::table('bundleklaim_t')
                //     ->select('filename', 'urut', 'norec')
                //     ->where('noregistrasi', $request['noregistrasi'])
                    // ->whereIn('filename', [$filename1, $filename2])
                    // ->where('filename', $filename1)
                    // ->orderBy('urut', 'desc')
                    // ->first();
                    // ->get()
                    // ->keyBy('filename');

                if (isset($noUrut)) {
                    $noUrut->data = $sourcePdf;
                    $noUrut->save();
                    // BundleKlaim::where('norec', $noUrut[$filename1]->norec)
                    //     ->update(['data' => $sourcePdf]);
                } else {
                    BundleKlaim::create([
                        'norec' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                        'noregistrasi' => $request['noregistrasi'],
                        'filename' => $filename1,
                        'data' => $sourcePdf,
                        'urut' => 1
                    ]);
                }

                // if (isset($noUrut[$filename2])) {
                //     BundleKlaim::where('norec', $noUrut[$filename2]->norec)
                //         ->update(['data' => $sourcePdf]);
                // } else {
                //     BundleKlaim::create([
                //         'norec' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                //         'noregistrasi' => $request['noregistrasi'],
                //         'filename' => $filename2,
                //         'data' => $sourcePdf,
                //         'urut' => 1
                //     ]);
                // }
                // DB::table('bundleklaim_t')->insert([
                //     [
                //         'norec'         => Uuid::uuid4()->toString(),
                //         'noregistrasi'  => $request['noregistrasi'],
                //         'filename'      => $filename1,
                //         'data'          => $sourcePdf,
                //         'urut'          => $urut1,
                //     ],
                //     [
                //         'norec'         => Uuid::uuid4()->toString(),
                //         'noregistrasi'  => $request['noregistrasi'],
                //         'filename'      => $filename2,
                //         'data'          => $sourcePdf,
                //         'urut'          => $urut2,
                //     ]
                // ]);

                // $request->fPut(
                //     $path . '/' . $filename,
                //     $fileData  ,
                //     'public'
                // );

                // $fileData = base64_decode($sourcePdf);

                // $request->fPut(
                //     $path . '/' . $filename,
                //     $fileData  ,
                //     'public'
                // );
                return 'Ok';
            }else{
                return null;
            }

        }
        public function savePemakaianAsuransi(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save
            $kdProfile = $this->kdProfile;
            if(strlen($r['nomor_sep']) < 18 ) {
                $result = array(
                    "status" => 400,
                    "result"  => 'error'
                );
                return $this->respond($result['result'], $result['status'], 'SEP tidak boleh kurang dari 18 karakter');
            }
            $cekPA =  PemakaianAsuransi::where('noregistrasifk', $r['norec_pd'])
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->first();
            if(empty($cekPA)){
                $cek = AsuransiPasien::where('noasuransi', $r['nomor_kartu'])->where('kdprofile', $kdProfile)
                ->where('statusenabled', true)
                ->where('kdprofile', $kdProfile)
                ->first();
                if (empty($cek)) {
                    $id = $this->SEQUENCE_MASTER(new AsuransiPasien,'id',$kdProfile);// $this->Uuid4();
                    $model_AP = new AsuransiPasien();
                    $model_AP->id = $id;
                    $model_AP->norec = $model_AP->generateNewId();
                    $model_AP->kdprofile = $kdProfile;
                    $model_AP->statusenabled = true;
                } else {
                    $model_AP  = $cek;
                }
                $model_AP->kdpenjaminpasien = $r['kpid'];
                $model_AP->namapeserta =  $r['namapasien'];
                $model_AP->noasuransi =   $r['nomor_kartu'];
                $model_AP->nocmfk = $r['nocmfk'];
                $model_AP->kelompokpasienfk =  $r['kpid'];
                $model_AP->save();

                $model_PA = new PemakaianAsuransi();
                $model_PA->norec = $model_PA->generateNewId();
                $model_PA->kdprofile = $kdProfile;
                $model_PA->statusenabled = true;
            }else{
                $model_PA  = $cekPA;
            }
            $model_PA->noregistrasifk =  $r['norec_pd'];
            $model_PA->nosep = $r['nomor_sep'];
            $model_PA->nokartu = $r['nomor_kartu'];
            $model_PA->nomr = $r['nocm'];
            $model_PA->user = $this->getNamaPegawai();

            $model_PA->save();

            $pasien = Pasien::where('id',$r['nocmfk'])->first();
            if($pasien->nobpjs == null){
                $pasien->nobpjs = $r['nomor_kartu'];
                $pasien->save();
            }
            //endregion

            $this->LOGGING(
                'Pemakaian Asuransi',
                $model_PA->norec,
                'pemakaianasuransi',
                'Tambah No. SEP '.$r['nomor_sep'].'  dibuat MANUAL di INACBG DETAIL  tgl '. date('Y-m-d') .' pada Pasien ' .
                $r['namapasien'] . ' (' .   $r['nocm'] . ') - ' . $r['noregistrasi']
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $ihs = null;
            // if ($value['inacbg_status'] == 'new_claim') {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $r['noregistrasi'];
                $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Composition($objetoRequest, true);
            // }
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "Composition" => $ihs,
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage(). ' '.$e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDaftarKlaim(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
        ->join('pasien_m as ps','ps.id','=','pd.nocmfk')
        ->join('ruangan_m as ru','ru.id','=','pd.objectruanganlastfk')
        ->join('departemen_m as dep','dep.id','=','ru.objectdepartemenfk')
        ->join('jeniskelamin_m as jk','jk.id', '=','ps.objectjeniskelaminfk')
        ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
        ->leftjoin('monitoringklaim_t as mk', 'mk.nosep', '=', 'pa.nosep')
        ->leftjoin('asuransipasien_m as asu', 'pa.objectasuransipasienfk', '=', 'asu.id')
        ->leftjoin('kelompokpasien_m as klp','klp.id','=','pd.objectkelompokpasienlastfk')
        ->leftjoin('pegawai_m as pg','pg.id','=','pd.objectpegawaifk')
        ->leftjoin('alamat_m as al','al.nocmfk','=','ps.id')
        ->join('kelas_m as kls','kls.id','=','pd.objectkelasfk')
        ->join('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
        ->select(
            'ru.namaruangan', 'ru.id as idruangan', 'ps.nobpjs',
            'ps.namapasien', 'ps.nocm', 'ps.objectjeniskelaminfk', 'ps.nohp', 'al.alamatlengkap as alamatrmh', DB::raw("to_char(ps.tgllahir,'YYYY-MM-DD') as tgllahir"), 'ps.nobpjs',
            'pd.norec as norec_pd','pd.noregistrasi', 'ic.status', DB::raw("to_char(pd.tglregistrasi,'YYYY-MM-DD HH:mm') as tglregistrasi"),
            'pa.nosep', 'pa.flagprocedure_kode','pa.tujuankun_kode','pa.poli_kode','pa.tglcreate',
            'asu.nmprovider',
            'jk.jeniskelamin',
            'klp.kelompokpasien',
            'kls.namakelas',
            'pg.namalengkap as dokter',
            'mk.status as ketKlaim',
            'pd.inacbg_totalgrouper',
            'pd.statuskelengkapandok',
            'pd.tglmeninggal',
            'pd.nocmfk',
            DB::raw("to_char(pd.tglpulang,'YYYY-MM-DD HH:mm') as tglpulang"),
            'pd.objectstatuspulangfk',
            'dep.id as id_dep'
        )
        ->whereIN('ic.inacbg_status', ['send_claim_individual'])
        ->groupBy(
            'ru.namaruangan', 'ru.id', 'ps.nobpjs',
            'ps.namapasien', 'ps.nocm', 'ps.objectjeniskelaminfk', 'ps.nohp', 'al.alamatlengkap', 'ps.tgllahir', 'ps.nobpjs',
            'pd.norec','pd.noregistrasi', 'ic.status', 'pd.tglregistrasi',
            'pa.nosep', 'pa.flagprocedure_kode','pa.tujuankun_kode','pa.poli_kode','pa.tglcreate',
            'asu.nmprovider',
            'jk.jeniskelamin',
            'klp.kelompokpasien',
            'kls.namakelas',
            'pg.namalengkap',
            'mk.status',
            'pd.inacbg_totalgrouper',
            'pd.statuskelengkapandok',
            'pd.tglmeninggal',
            'pd.tglpulang',
            'pd.objectstatuspulangfk',
            'dep.id'
        )->orderBy('pd.tglregistrasi');

        if (isset($request->ispulang) && $request->ispulang == true) {
            if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
                $data = $data->where('pd.tglpulang', '>=', $request->tglawal . " 00:00");
            }
            if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
                $data = $data->where('pd.tglpulang', '<=', $request->tglakhir . " 23:59");
            }
        } else {
            if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
                $data = $data->where('pd.tglregistrasi', '>=', $request->tglawal . " 00:00");
            }
            if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
                $data = $data->where('pd.tglregistrasi', '<=', $request->tglakhir . " 23:59");
            }
        }

        if (isset($request->search) && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->Where('ps.nocm', 'ilike', $searchTerm)
                ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                ->orWhere('pa.nosep', 'ilike', $searchTerm)
                ->orWhere('ps.namapasien', 'ilike', $searchTerm);
            });
        }

        if (isset($request->ruanganid) && $request->ruanganid != "" && $request->ruanganid != "undefined") {
            $data = $data->where('pd.objectruanganlastfk', '=', $request->ruanganid);
        }
        if (isset($request->depid) && $request->depid != "" && $request->depid != "undefined") {
            $data = $data->where('dep.id', '=', $request->depid);
        }
        if (isset($request->norec_pd) && $request->norec_pd != "" && $request->norec_pd != "undefined") {
            $data = $data->where('pd.norec', '=', $request->norec_pd);
        }

        $data = $data->get();

        $dataulasan = DB::table("ulasanklaim_t")
        ->where('statusenabled', true);

        if (isset($request->ispulang) && $request->ispulang == true) {
            if (isset($request->tglAwal) && $request->tglAwal != "" && $request->tglAwal != "undefined") {
                $dataulasan = $dataulasan->where('tglpulang', '>=', $request->tglAwal);
            }
            if (isset($request->tglAkhir) && $request->tglAkhir != "" && $request->tglAkhir != "undefined") {
                $dataulasan = $dataulasan->where('tglpulang', '<=', $request->tglAkhir);
            }
        } else {
            if (isset($request->tglAwal) && $request->tglAwal != "" && $request->tglAwal != "undefined") {
                $dataulasan = $dataulasan->where('tglregistrasi', '>=', $request->tglAwal);
            }
            if (isset($request->tglAkhir) && $request->tglAkhir != "" && $request->tglAkhir != "undefined") {
                $dataulasan = $dataulasan->where('tglregistrasi', '<=', $request->tglAkhir);
            }
        }
        $dataulasan = $dataulasan->get();
        $arrDataUlasan = [];
        if($dataulasan) {
            $arrDataUlasan = json_decode(json_encode($dataulasan), true);
        }

        $i=0;
        foreach($data as $item) {
            $norec_pd = $item->norec_pd;
            $filterUlasan = array_filter($arrDataUlasan, function ($val) use ($norec_pd) {
                return ($val['noregistrasifk'] == $norec_pd);
            });
            $filterUlasan = array_merge($filterUlasan);
            $ulasanBelumdibaca = array_filter($arrDataUlasan, function ($val) use ($norec_pd) {
                return ($val['noregistrasifk'] == $norec_pd && $val['isread']==true && $val['isread']==false);
            });
            $data[$i]->ulasan = "Tot Ulasan ".count($filterUlasan);

            $data[$i]->inacbg_totalgrouper = !empty($item->inacbg_totalgrouper)?$item->inacbg_totalgrouper:"Belum Ada";

            $tglLahir = new \DateTime($item->tgllahir);
            $today = new \DateTime("today");
            $y = $today->diff($tglLahir)->y;
            $m = $today->diff($tglLahir)->m;
            $d = $today->diff($tglLahir)->d;
            $umur = $y;

            $data[$i]->umur = $umur;

            $i++;
        }
        return $this->respond($data);
    }

    public function getDaftarKlaimHasilLab(Request $request)
    {
        // hasil bridging
        $kdProfile = $this->kdProfile;
        $dataOrder = DB::table('strukorder_t')
        ->select('noorder', 'tglorder', 'noregistrasifk')
        ->where('noregistrasifk', $request['norec_pd'])
        ->where('keteranganorder', 'Order Laboratorium')
        ->where('statusenabled', true)
        ->where('kdprofile', $kdProfile)
        ->get();

        if(count($dataOrder) == 0) {
            $res['data'] = [];
            return $this->respond($res);
        }

        $noorder = [];
        foreach($dataOrder as $item) {
            array_push($noorder, $item->noorder);
        }
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));
        $dataBrid = DB::select(DB::raw("SELECT hl.nama_pemeriksaan as namaproduk, hl.nama_pemeriksaan as detailpemeriksaan,
        hl.hasil, hl.flag, hl.normal as nilaitext, hl.unit as satuanstandar, hl.user_validasi, hl.tgl_hasil, hl.metode, so.noorder
        FROM lab_hasil hl
        INNER JOIN strukorder_t so ON so.noorder = hl.no_order
        WHERE so.noorder IN ($placeholders)"), $noorder);

        $result = [];
        foreach($dataOrder as $key => $item) {
            $item->hasil = [];
            foreach ($dataBrid as $item2) {
                if($item->noorder == $item2->noorder){
                    $item->hasil[] = [
                        'namaproduk' => $item2->namaproduk,
                        'detailpemeriksaan' => $item2->detailpemeriksaan,
                        'hasil' => $item2->hasil,
                        'flag' => $item2->flag,
                        'nilaitext' => $item2->nilaitext,
                        'satuanstandar' => $item2->satuanstandar,
                        'analis' => $item2->user_validasi,
                        'tglhasil' => $item2->tgl_hasil,
                        'metode' => $item2->metode,
                    ];
                }
            }
            // masukin data yg ada hasilnya aja
            if(count($item->hasil) > 0) {
                array_push($result, $item);
            }
        }

        $res['data'] = $result;
        return $this->respond($res);
    }

    public function getDaftarKlaimHasilRad(Request $request) {
        $kdProfile = $this->kdProfile;
        $result = DB::table('hasilradiologi_t as hr')
        ->join('pelayananpasien_t as pp', 'hr.pelayananpasienfk','=','pp.norec')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
        ->join('pasiendaftar_t as pd', 'pd.norec','=','apd.noregistrasifk')
        ->join('strukorder_t as so', 'so.norec','=','pp.strukorderfk')
        ->join('pegawai_m as pg','pg.id','=','hr.pegawaifk')
        ->join('produk_m AS pr','pr.id','=','pp.produkfk')
        ->select('hr.keterangan', 'hr.tanggalreport', 'so.noorder', 'pr.namaproduk', 'pg.namalengkap as dokterrab')
        ->where('so.keteranganorder','Order Radiologi')
        ->where('pd.norec', $request['norec_pd'])
        ->distinct()
        ->orderby('hr.tanggalreport', 'asc')
        ->get();

        if($result) {
            $result = json_decode(json_encode($result), true);
        }
        $i=0;
        foreach($result as $item) {
            $result[$i]['id'] = $i + 1;
            $result[$i]['keterangan'] = nl2br(str_replace('~','<br />',$item['keterangan']));
            $i++;
        }
        return $this->respond($result);
    }

    public function getDaftarKlaimEMR(Request $request) {
        $kdProfile = $this->kdProfile;
        $EMR_FORM = DB::connection('mongodb')
        ->table('#ResumeEMR')
        ->where('kdprofile', $kdProfile)
        ->where('noregistrasifk', $request['norec_pd'])
        ->where('table', '!=', 'VitalSign')
        ->where('table', '!=', 'resumeMedis')
        ->where('statusenabled', true)
        ->orderByDesc('last_update')
        ->get();

        $emrpasien = DB::table("emrpasien_t")
        ->where('noregistrasifk', $request['norec_pd'])
        ->where('kdprofile', $kdProfile)
        ->where('statusenabled', true)
        ->get();

        $result = [];
        foreach($EMR_FORM as $item) {
            // dd($item);
            $item["norec_apd"] = '';
            $item["noregistrasi"] = '';
            foreach($emrpasien as $item2) {
                if($item2->norec == $item["emrpasienfk"]) {
                    $item["norec_apd"] = $item2->norec_apd;
                    $item["noregistrasi"] = $item2->noregistrasi;
                }
            }
            array_push($result, $item);
        }

        return $this->respond($result);
    }
    public function lihatbundleDokumen(Request $request)
    {
        $dataDokumen = DB::table('monitoringdokklaim_t as mk')
            ->join("dokumenklaim_m as dk", "dk.id", "=", "mk.documentklaimfk")
            ->select('mk.*','dk.id as iddk')
            ->where('mk.noregistrasifk', $request->norec_pd)
            ->where('mk.kdprofile', $this->kdProfile)
            ->where('mk.statusenabled', true)
            ->orderBy('dk.nourut')
            ->get();
        $fileName = 'bundle_' . $request['noregistrasi'] . '.pdf';
        $disk = 'app/public/';
        //$pathbundle = $disk. 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;
        $pathbundle = $disk. 'dokumen_klaim/' . $fileName;
        if (File::exists($pathbundle)) {
            File::delete($pathbundle);
        }

        $urutdokumen=explode(',',$request['urutdokumen']);
        if (count($dataDokumen) > 0) {
            $file = [];
            $filetmp = [];
            foreach ($dataDokumen as $item) {
                //array_push($file, storage_path($disk . $item->filepath));
                $filetmp[$item->iddk]=base64_decode($item->filebase64);
            }

            //ash 2024-01-22
            $jum=count($urutdokumen);
            $k=0;
            for($i=0;$i<$jum;$i++) {
                $j=$urutdokumen[$i];
                if(isset($filetmp[$j])) {
                    /*if (file_exists($filetmp[$j])) {
                        $file[$k]=$filetmp[$j];
                        $k++;
                    }*/
                    if($filetmp[$j]!=NULL){
                        $file[$k]=$filetmp[$j];
                        $k++;
                    }
                }

            }



            if($k>0) {
                $pdf = PDFMerger::init();
                foreach ($file as $data) {
                    //$pdf->addString($data, 'all');
                    $pdf->addString($data, 'all');
                }

                $pdf->merge();
                $pdf->save(storage_path($pathbundle));

                if (!File::exists(storage_path($pathbundle))) {
                    return '
                    <script language="javascript">
                        window.alert("Tidak ada data.");
                        window.close()
                    </script>';

                }
                $file = File::get(storage_path($pathbundle));

                $type = File::mimeType(storage_path($pathbundle));

                $response = response()->make($file, 200);
                $response->header("Content-Type", $type);
                return $response;
            } else {
                return '
                <script language="javascript">
                    window.alert("Tidak ada data.");
                    window.close()
                </script>';
            }
        } else {
            return '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';

        }
    }

    public function bundleDokumenRev(Request $request)
    {

        $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])
            ->where('kdprofile', $this->kdProfile)
            ->first();
        $noregistrasifk = $dataRegistrasi->norec;
        $id = substr($request['urutdokumen'], 0, -1);
        $dataDokumen = DB::table('monitoringdokklaim_t as mk')
            ->join("dokumenklaim_m as dk", "dk.id", "=", "mk.documentklaimfk")
            ->select('mk.*','dk.id as iddk')
            ->where('mk.noregistrasifk', $dataRegistrasi->norec)
            ->where('mk.kdprofile', $this->kdProfile)
            ->where('mk.statusenabled', true)
            ->whereRaw("mk.filename not ilike '%skdp%'")
            ->orderBy('dk.nourut')
            ->get();

        
        $fileName = 'bundle_' . $request['noregistrasi'] . '.pdf';
        $disk = 'app/public/';
        $pathbundle = $disk. 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;
        if (File::exists($pathbundle)) {
            File::delete($pathbundle);
        }
        //$pathbundle = $disk. 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;

        $pathbundle = $disk. 'dokumen_klaim/' . $fileName;

        if (File::exists($pathbundle)) {
            File::delete($pathbundle);
        }
        // $localDisk = empty(config('app.s3telkom'));
        // $storage = 'local';
        // if ($localDisk) {
        //     $disk = $storage;
        // } else {
        //     $disk = 's3'.$storage;
        // }








        $urutdokumen=explode(',',$request['urutdokumen']);
        $urutanfilename = [];
        $pathBerkasManual = ''; 
        if (count($dataDokumen) > 0) {
            $file = [];
            $filetmp = [];
            foreach ($dataDokumen as $dd => $item) {
                for($i=0;$i<count($urutdokumen);$i++) {
                    if($item->iddk == $urutdokumen[$i]){
                        // var_dump($item);
                        // var_dump($urutdokumen[$i]);

                        // var_dump($item->filename.' '.$item->nourut);
                        if($item->documentklaimfk == 298) {
                            $pathBerkasManual = storage_path($disk . $item->filepath);
                        }
                        $urutanfilename[] = $item->filename;
                        $filetmp[$dd] = storage_path($disk . $item->filepath);
                        $file[$dd] = storage_path($disk . $item->filepath);
                    }
                }
                // $filetmp[$item->iddk]=base64_decode($item->filebase64);
            }

            DB::update("UPDATE monitoringdokklaim_t SET ischecked = false WHERE noregistrasifk = '$noregistrasifk' and documentklaimfk not in($id)");
            DB::update("UPDATE monitoringdokklaim_t SET ischecked = true WHERE noregistrasifk = '$noregistrasifk' and documentklaimfk in($id)");

            // for($i=0;$i<count($urutdokumen);$i++){
            //     $dataDokumen = MonitoringDokKlaim::where('noregistrasifk', $dataRegistrasi->norec)
            //     ->where('documentklaimfk', $urutdokumen[$i])
            //     ->first();
            // }

            //ash 2024-01-22
        //     $jum=count($urutdokumen);

        //     $k=0;
        //     for($i=0;$i<$jum;$i++) {
        //         $j=$urutdokumen[$i];
        //     var_dump($j);

        //         if(isset($filetmp[$j])) {
        //             /*if (file_exists($filetmp[$j])) {
        //                 $file[$k]=$filetmp[$j];
        //                 $k++;
        //             }*/
        //             if($filetmp[$j]!=NULL){
        //                 // $file[$k]=$filetmp[$j];
        //                 $k++;
        //             }
        //         }

        //     }
        // }

        // }

            if(count($file)>0) {
                $pdf = PDFMerger::init();

                $bundleDokKlaim = DB::table('bundleklaim_t')
                    ->select('filename', 'data')
                    ->where('noregistrasi', $request['noregistrasi'])
                    ->where('filename', 'not ilike', '%bundle%')
                    ->whereIn('filename', $urutanfilename) // Filter hanya filename yang dibutuhkan
                    ->orderByRaw('array_position(ARRAY[' . collect($urutanfilename)->map(fn($f) => "'$f'")->implode(',') . '], filename)')
                    ->get();

                if(!File::isDirectory(storage_path('tmp'))) {
                    File::makeDirectory(storage_path('tmp'), 0777, true);
                };

                $isBerkas = false;
                foreach($bundleDokKlaim as $k => $doc) {
                    if(str_contains($doc->filename, 'berkas'.$request['noregistrasi'])) {
                        $isBerkas = true;
                        break;
                    }
                    $pdf->addString(base64_decode($doc->data), 'all');
                }

                if($isBerkas == false) {
                    if($pathBerkasManual != '') {
                        $dts = base64_encode(file_get_contents($pathBerkasManual));
                        BundleKlaim::create([
                            'norec' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                            'data' => $dts,
                            'urut' => 1,
                            'noregistrasi' => $request['noregistrasi'],
                            'filename' => $fileName,
                        ]);
                        $pdf->addString(base64_decode($dts), 'all');
                    }
                }                
                $pdf->merge();
                $pdfOutput = $pdf->output();
                $encrypt = base64_encode($pdfOutput);

                BundleKlaim::updateOrCreate(
                    [
                        'noregistrasi' => $request['noregistrasi'],
                        'filename' => $fileName,
                    ],
                    [
                        'data' => $encrypt,
                        'urut' => 1,
                        'norec' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                    ]
                );

                return response($pdfOutput, 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="'.$fileName.'"');

                // if (!File::exists(storage_path($pathbundle))) {
                //     return '
                //     <script language="javascript">
                //         window.alert("Tidak ada data.");
                //         window.close()
                //     </script>';

                // }
                // $file = File::get(storage_path($pathbundle));

                // $type = File::mimeType(storage_path($pathbundle));

                // $response = response()->make($file, 200);
                // $response->header("Content-Type", $type);
                // return $response;
            } else {
                return '
                <script language="javascript">
                    window.alert("Tidak ada data.");
                    window.close()
                </script>';
            }
        } else {
            return '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';

        }

        // return $file;
    }

    public function bundleDokumenRevDownloadTest(Request $request) {
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $disk = 'app/public/dokumen_klaim';

        $data = DB::select(DB::raw("select * from pasiendaftar_t
        where tglregistrasi::date between '$tglAwal' and '$tglAkhir'
        and ketverifikasi = 'BENAR'"));

        $files = [];
        for($x = 0; $x < count($data); $x++){
            $files[$x] = $data[$x]->noregistrasi;
        }

        // $files = ['0233R7790425V000541.pdf', '0233R7790425V000537.pdf', '0233R7790425V000501.pdf'];

        $zip = new \ZipArchive();
        $zipDirectory = storage_path('app/public/dokumen_klaim/zip');

        if (!file_exists($zipDirectory)) {
            mkdir($zipDirectory, 0755, true);
        }

        $zipFilePath = $zipDirectory . '/' . $tglAwal . '.zip';
        $result = $zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($result !== TRUE) {
            exit("Failed to create or open zip file");
        }

        $tmpDir = storage_path('app/public/dokumen_klaim/tmp_pdf');
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        foreach ($files as $file) {
            $row = DB::table('bundleklaim_t')
                ->where('noregistrasi', $file)
                ->orderBy('urut', 'desc')
                ->first();

            $nama = DB::selectOne("
                SELECT pa.nosep 
                FROM pasiendaftar_t AS pd
                JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
                WHERE pd.noregistrasi = ?
            ", [$file]);

            $filenameInZip = $nama ? $nama->nosep . '.pdf' : 'bundle_' . $file . '.pdf';
            $tmpPdfPath = $tmpDir . '/bundle_' . $file . '.pdf';

            if ($row && $row->data) {
                $binaryPdf = base64_decode($row->data);

                file_put_contents($tmpPdfPath, $binaryPdf);

                $zip->addFile($tmpPdfPath, $filenameInZip);
            }else {
                $filePath = storage_path('app/public/dokumen_klaim/bundle_' . $file. '.pdf');
                //var_dump(file_exists($filePath));
                if (file_exists($filePath)) {
                    $fileName = basename($filePath);
                    $encrypt = base64_encode(file_get_contents($filePath));
                    
                    DB::table('bundleklaim_t')->insert([
                        'norec'         => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                        'noregistrasi'  => $file,
                        'filename'      => $fileName,
                        'data'          => $encrypt,
                        'urut'          => 1
                    ]);
                    $zip->addFile($filePath, $filenameInZip);                   
                    // unlink($filePath);
                }
            }
        }

        $zip->close();
        File::deleteDirectory($tmpDir);

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
    public function bundleDokumenRevDownload(Request $request)
    {

        $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])
            ->where('kdprofile', $this->kdProfile)
            ->first();
        $dataDokumen = DB::table('monitoringdokklaim_t as mk')
            ->join("dokumenklaim_m as dk", "dk.id", "=", "mk.documentklaimfk")
            ->select('mk.*','dk.id as iddk')
            ->where('mk.noregistrasifk', $dataRegistrasi->norec)
            ->where('mk.kdprofile', $this->kdProfile)
            ->where('mk.statusenabled', true)
            ->orderBy('dk.nourut')
            ->get();
        $fileName = 'bundle_' . $request['noregistrasi'] . '.pdf';
        $disk = 'app/public/';
        $pathbundle = $disk. 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;
        // $pathbundles = 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;
        if (File::exists($pathbundle)) {
            File::delete($pathbundle);
        }
        // //$pathbundle = $disk. 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;

        $pathbundle = $disk. 'dokumen_klaim/' . $fileName;

        if (File::exists($pathbundle)) {
            File::delete($pathbundle);
        }
        // // $localDisk = empty(config('app.s3telkom'));
        // // $storage = 'local';
        // // if ($localDisk) {
        // //     $disk = $storage;
        // // } else {
        // //     $disk = 's3'.$storage;
        // // }

        // // var_dump($dataDokumen);
        $urutdokumen=explode(',',$request['urutdokumen']);
        $urutanfilename = [];
        if (count($dataDokumen) > 0) {
            $file = [];
            $filetmp = [];
            foreach ($dataDokumen as $item) {
                $urutanfilename[] = $item->filename;
                $filetmp[$item->iddk] = storage_path($disk . $item->filepath);
                $file[$item->iddk] = storage_path($disk . $item->filepath);

                // $filetmp[$item->iddk]=base64_decode($item->filebase64);
            }

        //     // var_dump($filetmp);

        //     //ash 2024-01-22
        //     $jum=count($urutdokumen);
        //     $k=0;
        //     for($i=0;$i<$jum;$i++) {
        //         $j=$urutdokumen[$i];
        //         if(isset($filetmp[$j])) {
        //             /*if (file_exists($filetmp[$j])) {
        //                 $file[$k]=$filetmp[$j];
        //                 $k++;
        //             }*/
        //             if($filetmp[$j]!=NULL){
        //                 // $file[$k]=$filetmp[$j];
        //                 $k++;
        //             }
        //         }

        //     }




            if($k>0) {
                $pdf = PDFMerger::init();
                $bundleDokKlaim = DB::table('bundleklaim_t')
                    ->select('filename', 'data')
                    ->where('noregistrasi', $request['noregistrasi'])
                    // ->where('filename', 'not ilike', '%bundle%')
                    ->whereIn('filename', $urutanfilename) // Filter hanya filename yang dibutuhkan
                    ->orderByRaw('array_position(ARRAY[' . collect($urutanfilename)->map(fn($f) => "'$f'")->implode(',') . '], filename)')
                    ->get();

                if(!File::isDirectory(storage_path('tmp'))) {
                    File::makeDirectory(storage_path('tmp'), 0777, true);
                };


                foreach($bundleDokKlaim as $k => $doc) {
                    $pdf->addString(base64_decode($doc->data), 'all');
                }

                $pdf->merge();

                $pdfOutput = $pdf->output();
                $encrypt = base64_encode($pdfOutput);

                
                BundleKlaim::updateOrCreate(
                    [
                        'noregistrasi' => $request['noregistrasi'],
                        'filename' => $fileName,
                    ],
                    [
                        'data' => $encrypt,
                        'urut' => 1,
                        'norec' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                    ]
                );
                return response($rawPdf, 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="'.$fileName.'"');
                // if (!File::exists(public_path($fileName))) {
                //     return '
                //     <script language="javascript">
                //         window.alert("Tidak ada data.");
                //         window.close()
                //     </script>';

                // } else{
                    // $headers = [ 'Content-Type' => 'application/pdf', ];

                    // var_dump(response()->download(storage_path($pathbundle, $headers)));
                    // var_dump(response()->download(storage_path($pathbundle)));

                    // return response()->download(public_path($fileName));
                    // return Storage::disk('public')->download('dokumen_klaim/'.$fileName);
                    // return response()->file(storage_path('app/public/'.$pathbundles, $headers));
                    $file = File::get(storage_path('app/public/dokumen_klaim/'.$fileName));

                    $type = File::mimeType(storage_path('app/public/dokumen_klaim/'.$fileName));

                    $response = response()->make($file, 200);
                    $response->header("Content-Type", $type);
                    return $response;
                    // return response()->download(storage_path('app/public/dokumen_klaim/'.$fileName));

                    // return Storage::download(storage_path($pathbundle), 'Laras Cantik.pdf');

                // }
                // $file = File::get(storage_path($pathbundle));

                // $type = File::mimeType(storage_path($pathbundle));


                // $file= storage_path($pathbundle);

                // $headers = [ 'Content-Type' => 'application/pdf', ];

                        // dd('HALO DOWNLOAD');

                        // dd(Response::download($file, 'Laras.pdf', $headers));

                // return Response::download($file, 'Laras.pdf', $headers);

                // $response = response()->make($file, 200)->header("Content-Type", $type)->download($file);
                // $response->header("Content-Type", $type);

                // return Response::download($pathbundle);
            // return response()->download(storage_path($pathbundle));

            } else {
                return '
                <script language="javascript">
                    window.alert("Tidak ada data.");
                    window.close()
                </script>';
            }
        } else {
            return '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';

        }
    }

    public function bundleDokumenRevDownloadRAR(Request $request) {
        
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        if (ob_get_level()) {
            ob_end_clean();
        }
        
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $disk = 'app/public/dokumen_klaim';
        

        $data = DB::select(DB::raw("select * from pasiendaftar_t
        where tglregistrasi::date between '$tglAwal' and '$tglAkhir'
        and ketverifikasi = 'BENAR'"));

        $files = [];
        for($x = 0; $x < count($data); $x++){
            $files[$x] = $data[$x]->noregistrasi;
        }

        // $files = ['0233R7790425V000541.pdf', '0233R7790425V000537.pdf', '0233R7790425V000501.pdf'];

        $zip = new \ZipArchive();
        $zipDirectory = storage_path('app/public/dokumen_klaim/zip');

        if (!file_exists($zipDirectory)) {
            mkdir($zipDirectory, 0755, true);
        }

        $zipFilePath = $zipDirectory . '/' . $tglAwal . '.zip';
        $result = $zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($result !== TRUE) {
            exit("Failed to create or open zip file");
        }

        $tmpDir = storage_path('app/public/dokumen_klaim/tmp_pdf');
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        foreach ($files as $file) {
            $row = DB::table('bundleklaim_t')
                ->where('noregistrasi', $file)
                ->orderBy('urut', 'desc')
                ->first();

            $nama = DB::selectOne("
                SELECT pa.nosep 
                FROM pasiendaftar_t AS pd
                JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
                WHERE pd.noregistrasi = ?
            ", [$file]);

            $filenameInZip = $nama ? $nama->nosep . '.pdf' : 'bundle_' . $file . '.pdf';
            $tmpPdfPath = $tmpDir . '/bundle_' . $file . '.pdf';

            if ($row && $row->data) {
                $binaryPdf = base64_decode($row->data);

                file_put_contents($tmpPdfPath, $binaryPdf);

                $zip->addFile($tmpPdfPath, $filenameInZip);
            }else {
                $filePath = storage_path('app/public/dokumen_klaim/bundle_' . $file. '.pdf');
                //var_dump(file_exists($filePath));
                if (file_exists($filePath)) {
                    $fileName = basename($filePath);
                    $encrypt = base64_encode(file_get_contents($filePath));
                    
                    DB::table('bundleklaim_t')->insert([
                        'norec'         => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                        'noregistrasi'  => $file,
                        'filename'      => $fileName,
                        'data'          => $encrypt,
                        'urut'          => 1
                    ]);
                    $zip->addFile($filePath, $filenameInZip);                   
                    // unlink($filePath);
                }
            }
        }

        $zip->close();
        File::deleteDirectory($tmpDir);

        return response()->download($zipFilePath)->deleteFileAfterSend(true);

        // $zip = new \ZipArchive();
        // $zipDirectory = storage_path('app/public/dokumen_klaim/zip');
        // if (!file_exists($zipDirectory)) {
        //     mkdir($zipDirectory, 0755, true);
        // }
        // $zipFilePath = $zipDirectory . '/'.$tglAwal.'.zip';
        // $result = $zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        // if ($result !== TRUE) {
        //     exit("Failed to create or open zip file");
        // } else{
        //     foreach ($files as $file) {
        //                 $filePath = storage_path('app/public/dokumen_klaim/bundle_' . $file. '.pdf');
        //                 //var_dump(file_exists($filePath));
        //                 if (file_exists($filePath)) {
        //                     var_dump('Ada');
        //                     $nama = DB::select(DB::raw("select pa.nosep from pasiendaftar_t as pd
        //                     inner join pemakaianasuransi_t as pa on pa.noregistrasifk = pd.norec
        //                     where pd.noregistrasi = '$file'"));
        //                     $zip->addFile($filePath, basename($nama[0]->nosep.'.pdf'));
        //                 }
        //             }
        //             $zip->close();
        //             // var_dump($zip);
        //             return response()->download($zipFilePath);
        //             // $rar = File::get(storage_path($zipDirectory . '/'.$tglAwal.'.zip'));

        //             // $type = File::mimeType(storage_path($zipDirectory . '/'.$tglAwal.'.zip'));

        //             // $response = response()->make($rar, 200);
        //             // $response->header("Content-Type", $type);
        //             // return $response;
        // }
    }

    // public function downloadBundleToZip(Request $request) {
    //     $tglAwal = $request['tglAwal'];
    //     $tglAkhir = $request['tglAkhir'];
    //     $namafile = $request['namafile'];
    //     $disk = 'app/public/dokumen_klaim';

    //     $data = DB::select(DB::raw("select pd.noregistrasi, pa.nosep from pasiendaftar_t pd
    //     inner join pemakaianasuransi_t as pa on pa.noregistrasifk = pd.norec
    //     where pd.tglregistrasi::date between '$tglAwal' and '$tglAkhir'
    //     and pd.ketverifikasi = 'BENAR'"));

    //     $files = [];
    //     $filesNameNew = [];
    //     for($x = 0; $x < count($data); $x++){
    //         $files[$x]='bundle_'. $data[$x]->noregistrasi . '.pdf';
    //         $filesNameNew[$x]=$data[$x]->nosep . '.pdf';
    //     }

    //     if(empty($files)) {
    //         return response()->json(['error' => 'Tidak ada berkas dengan catatan BENAR'], 404);
    //     }

    //     $zip = new \ZipArchive();
    //     $zipDirectory = storage_path('app/public/dokumen_klaim/zip');
    //     if (!file_exists($zipDirectory)) {
    //         mkdir($zipDirectory, 0755, true);
    //     }

    //     // $namafile = 'bundle_'.str_replace('-','',$tglAwal).'_'.str_replace('-','',$tglAkhir).'.zip';
    //     $zipFilePath = $zipDirectory . '/' . $namafile;
    //     // $zipFilePath = $zipDirectory . '/bundle_'.str_replace('-','',$tglAwal).'_'.str_replace('-','',$tglAkhir).'.zip';

    //     if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
    //         foreach ($files as $index => $file) {
    //             $filePath = storage_path('app/public/dokumen_klaim/' . $file);
    //             $newFileName =  $filesNameNew[$index]; // Nama file baru
    //             if (file_exists($filePath)) {
    //                 $zip->addFile($filePath, $newFileName);
    //             }
    //         }
    //         $zip->close();

    //     } else {
    //         return response()->json(['error' => 'Gagal membuat zip file'], 500);
    //     }

    //     return response()->download($zipFilePath, $namafile)->deleteFileAfterSend(true);
    //     // return response()->download($zipFilePath, $namafile);
    // }

    public function downloadBundleToZip(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        ini_set('output_buffering', 'off');
        ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        ob_implicit_flush(true);

        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $token = $request['token'];


        $namafile = 'bundle_klaim_' . str_replace('-', '', $tglAwal) . '_' . str_replace('-', '', $tglAkhir) . '.zip';
        $options = new ArchiveOptions();
        $options->setSendHttpHeaders(true);
        // return "msk";
        $zip = new ZipStream($namafile, $options);

        $data = DB::select(DB::raw("
            SELECT pd.noregistrasi, pa.nosep
            FROM pasiendaftar_t pd
            INNER JOIN pemakaianasuransi_t pa ON pa.noregistrasifk = pd.norec
            WHERE pd.tglregistrasi::date BETWEEN '$tglAwal' AND '$tglAkhir'
            AND pd.ketverifikasi = 'BENAR'
        "));

        foreach ($data as $row) {
            $newFileName = $row->nosep . '.pdf';
            $getAllBundle = DB::table('bundleklaim_t')
                ->where('noregistrasi', $row->noregistrasi)
                ->where('filename', 'bundle_' . $row->noregistrasi . '.pdf')
                ->orderBy('urut', 'desc')
                ->first();
            
            // return $getAllBundle;

            if ($getAllBundle) {
                $binary = base64_decode($getAllBundle->data);
                $zip->addFile($newFileName, $binary);
                // file_put_contents($tmp . '/' . $newFileName, $binary);
                // $zip->addFileFromPath($newFileName, $tmp . '/' . $newFileName);
            }else {
                $filePath = storage_path('app/public/dokumen_klaim/bundle_' . $row->noregistrasi . '.pdf');
                $raw = file_get_contents($filePath);
                if (file_exists($filePath)) {
                    BundleKlaim::create([
                        'norec' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                        'noregistrasi' => $request['noregistrasi'],
                        'filename' => 'bundle_' . $row->noregistrasi . '.pdf',
                        'data' => base64_encode($raw),
                        'urut' => 1
                    ]);
                    $zip->addFile($newFileName, $raw);
                }
            }

            flush();
        }
        // return "vrs";
        $zip->finish();
        // File::deleteDirectory($tmp);
        // Tidak perlu return response() lagi, ZipStream yang handle
    }

    public function bundleDokumenRevDownloadV2(Request $request)
    {

        $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])
            ->where('kdprofile', $this->kdProfile)
            ->first();
        $dataDokumen = DB::table('monitoringdokklaim_t as mk')
            ->join("dokumenklaim_m as dk", "dk.id", "=", "mk.documentklaimfk")
            ->select('mk.*','dk.id as iddk')
            ->where('mk.noregistrasifk', $dataRegistrasi->norec)
            ->where('mk.kdprofile', $this->kdProfile)
            ->where('mk.statusenabled', true)
            ->orderBy('dk.nourut')
            ->get();
        $fileName = 'bundle_' . $request['noregistrasi'] . '.pdf';
        $disk = 'app/public/';
        $pathbundle = $disk. 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;
        $pathbundles = 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;
        if (File::exists($pathbundle)) {
            File::delete($pathbundle);
        }

        $pathbundle = $disk. 'dokumen_klaim/' . $fileName;

        if (File::exists($pathbundle)) {
            File::delete($pathbundle);
        }

        $urutdokumen=explode(',',$request['urutdokumen']);
        $urutanfilename = [];
            $file = [];
            $filetmp = [];
            foreach ($dataDokumen as $item) {

                $urutanfilename[] = $item->filename;
                $filetmp[$item->iddk] = storage_path($disk . $item->filepath);
                $file[$item->iddk] = storage_path($disk . $item->filepath);
            }

            $jum=count($urutdokumen);
            $k=0;
            for($i=0;$i<$jum;$i++) {
                $j=$urutdokumen[$i];
                if(isset($filetmp[$j])) {
                    if($filetmp[$j]!=NULL){
                        $k++;
                    }
                }

            }

            $pdf = PDFMerger::init();

            $bundleDokKlaim = DB::table('bundleklaim_t')
                ->select('filename', 'data')
                ->where('noregistrasi', $request['noregistrasi'])
                ->where('filename', 'not ilike', '%bundle%')
                ->whereIn('filename', $urutanfilename) // Filter hanya filename yang dibutuhkan
                ->orderByRaw('array_position(ARRAY[' . collect($urutanfilename)->map(fn($f) => "'$f'")->implode(',') . '], filename)')
                ->get();

            if(!File::isDirectory(storage_path('tmp'))) {
                File::makeDirectory(storage_path('tmp'), 0777, true);
            };

            foreach($bundleDokKlaim as $k => $doc) {
                $pdf->addString(base64_decode($doc->data), 'all');
            }

            // foreach ($file as $data) {
            //     $pdf->addPDF($data, 'all');
            // }

            $pdf->merge();
            $pdfOutput = $pdf->output();
            $encrypt = base64_encode($pdfOutput);

            BundleKlaim::updateOrCreate(
                [
                    'noregistrasi' => $request['noregistrasi'],
                    'filename' => $fileName,
                ],
                [
                    'data' => $encrypt,
                    'urut' => 1,
                    'norec' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                ]
            );

            return response($pdfOutput, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="'.$fileName.'"');

                // if (!File::exists(public_path($fileName))) {
                //     return '
                //     <script language="javascript">
                //         window.alert("Tidak ada data.");
                //         window.close()
                //     </script>';

                // } else{
                //     return Storage::disk('public')->download('dokumen_klaim/'.$fileName);
                // }
    }
    public function savePersalinan(Request $r){
        try {
            DB::beginTransaction();
            $head = $r['head'];
            $detail = $r['detail'];
            Persalinan::where('norecpd', $r['norecpd'])
            ->delete();
            PersalinanDetail::where('norecpd', $r['norecpd'])
            ->delete();
            $pd = new Persalinan();
            $pd->norec = $pd->generateNewId();
            $pd->norecpd =  $r['norecpd'];
            $pd->usia_kehamilan = (float)$head['usia_kehamilan'];
            $pd->gravida = (float)$head['gravida'];
            $pd->partus = (float)$head['partus'];
            $pd->abortus = (float)$head['abortus'];
            $pd->onsetkontraksi = $head['onset_kontraksi']['nama'];
            $pd->kodeonsetkontraksi = $head['onset_kontraksi']['kode'];
            $pd->save();

            foreach ($detail as $item) {

                $pd = new PersalinanDetail();
                $pd->norec = $pd->generateNewId();
                $pd->norecpd =  $r['norecpd'];

                $pd->delivery_sequence = (float)$item['delivery_sequence'];
                $pd->delivery_method =  $item['delivery_method']['nama'];
                $pd->kodedelivery_method =  $item['delivery_method']['kode'];
                $pd->delivery_dttm = $item['delivery_dttm'];
                $pd->lokasi = $item['lokasi'];

                $pd->shk_spesimen_dttm = $item['shk_spesimen_dttm'];
                $pd->letak_janin = $item['letak_janin']['nama'];
                $pd->kodeletak_janin = $item['letak_janin']['kode'];
                $pd->kondisi = $item['kondisi']['nama'];
                $pd->kodekondisi = $item['kondisi']['kode'];

                $pd->use_manual = $item['use_manual']['kode'];
                $pd->use_forcep = $item['use_forcep']['kode'];
                $pd->use_vacuum = $item['use_vacuum']['kode'];
                $pd->shk_spesimen_ambil = $item['shk_spesimen_ambil']['nama'];
                $pd->kodeshk_spesimen_ambil = $item['shk_spesimen_ambil']['kode'];
                $pd->save();

            }
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $pd,
                "message" => "Simpan Persalinan Sukses"
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Hemooh",
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }
    public function saveApgar(Request $r)
    {
        try {
            DB::beginTransaction();
            $head = $r['head'];
            $detail = $r['detail'];
            Apgar::where('norecpd', $r['norecpd'])
            ->delete();

            $pd = new Apgar();
            $pd->norec = $pd->generateNewId();
            $pd->norecpd =  $r['norecpd'];
            $pd->appearance = (float)$head['appearance'];
            $pd->pulse = (float)$head['pulse'];
            $pd->grimace = (float)$head['grimace'];
            $pd->activity = (float)$head['activity'];
            $pd->respiration = (float)$head['respiration'];
            $pd->appearance5 = (float)$head['appearance5'];
            $pd->pulse5 = (float)$head['pulse5'];
            $pd->grimace5 = (float)$head['grimace5'];
            $pd->activity5 = (float)$head['activity5'];
            $pd->respiration5 = (float)$head['respiration5'];
            $pd->save();


            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $pd,
                "message" => "Simpan Apgar Sukses"
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Hemooh",
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }
    public function saveDializer(Request $r)
    {
        try {
            DB::beginTransaction();
            $head = $r['head'];
            Dializer::where('norecpd', $r['norecpd'])
            ->delete();

            $pd = new Dializer();
            $pd->norec = $pd->generateNewId();
            $pd->norecpd =  $r['norecpd'];
            $pd->dializer_single_use = (float)$head['dializer_single_use'];
            $pd->kantong_darah = (float)$head['kantong_darah'];
            $pd->save();


            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $pd,
                "message" => "Simpan Dializer Sukses"
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Gagal",
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

}
