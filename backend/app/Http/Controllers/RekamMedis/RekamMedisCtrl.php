<?php

namespace App\Http\Controllers\RekamMedis;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Ruangan;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Departemen;
use Ramsey\Uuid\Uuid;
use Exception;

class RekamMedisCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDropdown(Request $r)
    {
        $set = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $set2 = explode(',', $this->settingFix('listDepartemenPelayanan'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();
        $res['kelompokpasien'] = KelompokPasien::mine()->get();
        $res['departemen'] = Departemen::mine()->whereIn('id', $set2)->get();

        return $this->respond($res);
    }

    public function getRuanganBydepartemenId(Request $r)
    {
        $depId = $r->idDepartement;
        $ruangan = $r->ruangan;
        $res['ruangan'] = Ruangan::mine()->when($ruangan, function ($query) use ($ruangan) {
            $query->where('namaruangan' , 'ilike', '%'.$ruangan.'%');
        })->when($depId, function ($query) use ($depId) {
            $query->where('objectdepartemenfk', $depId);
        })->get();
        return $this->respond($res);
    }

    public function getDaftarKendaliDokumenRM(Request $request)
    {
        $status =$request->status;
        $data = DB::table('pasiendaftar_t as pd')
        ->select('pd.norec'
        ,'pd.tglregistrasi'
        ,'ps.nocm'
        ,'pd.noregistrasi'
        ,'rm.namaruangan'
        ,'ps.namapasien'
        ,'kp.kelompokpasien'
        ,'rk.namarekanan'
        ,'pg.namalengkap AS namadokter'
        ,'pd.tglpulang'
        ,'kd.iscari'
        ,'kd.tglcari'
        ,'kd.isdikirim'
        ,'kd.tglkirim'
        ,'kd.iskembali'
        ,'kd.tglkembali',
        'kd.statussekarang as status'
        )
        ->join('pasien_m as ps', "ps.id", "=", "pd.nocmfk")
        ->join('ruangan_m as rm', "rm.id", "=", "pd.objectruanganlastfk")
        ->join('kelompokpasien_m as kp', "kp.id", "=", "pd.objectkelompokpasienlastfk")
        ->leftjoin('kendalidokumen_t as kd', "kd.noregistrasifk", "=", "pd.norec")
        ->leftjoin('pegawai_m as pg', "pg.id", "=", "pd.objectpegawaifk")
        ->leftjoin('rekanan_m as rk', "rk.id", "=", "pd.objectrekananfk")
        ->leftjoin('jeniskelamin_m as jk', "jk.id", "=", "ps.objectjeniskelaminfk")
        ->whereNull('pd.tanggalpembatalan')
        ->whereBetween('pd.tglregistrasi', [$request['dari'], $request['sampai']]);

        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $data = $data->where('rm.objectdepartemenfk', $request['deptId']);
        }

        if (isset($request['ruangId']) && $request['ruangId'] != "" && $request['ruangId'] != "undefined") {
            $data = $data->where('pd.objectruanganlastfk', $request['ruangId']);
        }

        if (isset($request['kelId']) && $request['kelId'] != "" && $request['kelId'] != "undefined") {
            $data = $data->where('pd.objectkelompokpasienlastfk', $request['kelId']);
        }

        if (isset($request['namapasien']) && $request['namapasien'] != "" && $request['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%'.$request['namapasien'].'%');
        }

        if (isset($request['noreg']) && $request['noreg'] != "" && $request['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', $request['noreg']);
        }

        if (isset($request['norm']) && $request['norm'] != "" && $request['norm'] != "undefined") {
            $data = $data->where('ps.nocm', $request['norm']);
        }
        if (isset($request['kelompoknocm']) && $request['kelompoknocm'] != "" && $request['kelompoknocm'] != "undefined") {
            if( (int)$request['kelompoknocm'] % 2 === 0){
                $data = $data->whereRaw("ps.nocm::integer % 2 = 0");
            }else{
                $data = $data->whereRaw("ps.nocm::integer % 2 = 1");
            }
        }

        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        $data->when($status, function ($query) use ($status) {
            if ($status == "isBelumKirim") {
                $query->whereNull('kd.isdikirim')
                ->whereNull('kd.iscari')
                ->whereNull('kd.iskembali');
            }else{
                $query->where("kd.$status",true);

            }
        });
        $data = $data->orderBy('pd.tglregistrasi', 'desc');
        $data = $data->get();

        $rekap = DB::table('pasiendaftar_t as pd')
        ->select('kd.*')
        ->join('pasien_m as ps', "ps.id", "=", "pd.nocmfk")
        ->join('ruangan_m as rm', "rm.id", "=", "pd.objectruanganlastfk")
        ->join('kelompokpasien_m as kp', "kp.id", "=", "pd.objectkelompokpasienlastfk")
        ->leftjoin('kendalidokumen_t as kd', "kd.noregistrasifk", "=", "pd.norec")
        ->leftjoin('pegawai_m as pg', "pg.id", "=", "pd.objectpegawaifk")
        ->leftjoin('rekanan_m as rk', "rk.id", "=", "pd.objectrekananfk")
        ->leftjoin('jeniskelamin_m as jk', "jk.id", "=", "ps.objectjeniskelaminfk")
        ->whereBetween('pd.tglregistrasi', [$request['dari'], $request['sampai']]);

        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $rekap = $rekap->where('rm.objectdepartemenfk', $request['deptId']);
        }

        if (isset($request['ruangId']) && $request['ruangId'] != "" && $request['ruangId'] != "undefined") {
            $rekap = $rekap->where('pd.objectruanganlastfk', $request['ruangId']);
        }

        if (isset($request['kelId']) && $request['kelId'] != "" && $request['kelId'] != "undefined") {
            $rekap = $rekap->where('pd.objectkelompokpasienlastfk', $request['kelId']);
        }

        if (isset($request['namapasien']) && $request['namapasien'] != "" && $request['namapasien'] != "undefined") {
            $rekap = $rekap->where('ps.namapasien', 'ilike', '%'.$request['namapasien'].'%');
        }

        if (isset($request['noreg']) && $request['noreg'] != "" && $request['noreg'] != "undefined") {
            $rekap = $rekap->where('pd.noregistrasi', $request['noreg']);
        }
        if (isset($request['kelompoknocm']) && $request['kelompoknocm'] != "" && $request['kelompoknocm'] != "undefined") {
            if( (int)$request['kelompoknocm'] % 2 === 0){
                $rekap = $rekap->whereRaw("ps.nocm::integer % 2 = 0");
            }else{
                $rekap = $rekap->whereRaw("ps.nocm::integer % 2 = 1");
            }
        }


        if (isset($request['norm']) && $request['norm'] != "" && $request['norm'] != "undefined") {
            $rekap = $rekap->where('ps.nocm', $request['norm']);
        }
        $rekap = $rekap->get();


        $result = array(
            'data' => $data,
            'total' => count($rekap),
            'message' => 'inhuman',
        );
        return $this->respond($result);
    }

    public function updateStatusKendaliDokumenRM(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $update = array();
            switch ($request['update']) {
                case 'cari':
                    $update["iscari"] = true;
                    $update["statussekarang"] = 'Cari';
                    $update["tglcari"] =  date('Y-m-d H:i:s');
                    break;
                case 'kirim':
                    $update["isdikirim"] = true;
                    $update["statussekarang"] = 'Dikirim';
                    $update["tglkirim"] = date('Y-m-d H:i:s');
                    break;
                case 'kembali':
                    $update["iskembali"] = true;
                    $update["statussekarang"] = 'Kembali';
                    $update["tglkembali"] = date('Y-m-d H:i:s');
                    break;
            }

            $update['norec'] = Uuid::uuid4();
            $update['kdprofile'] = $kdProfile;
            $update['statusenabled'] = true;
            $update['noregistrasifk'] = $request['noregistrasifk'];
            DB::table('kendalidokumen_t') ->updateOrInsert(["noregistrasifk" => $request['noregistrasifk']], $update);

            $transMessage = "Berhasil Update Status Kendali Dokumen";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );

        } catch (Exception $e) {
            $transMessage = "Gagal Update Status Kendali Dokumen";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null,
                "error" =>  $e->getMessage()
            );

        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
