<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Transaksi\EMR;
use Illuminate\Support\Facades\DB;
use App\Models\Master\BerkasPasien;
use App\Http\Controllers\Controller;

class MasterBerkasPasienCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getAllBerkasPasien(Request $request)
    {


        $data = DB::table('berkaspasien_m')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveBerkasPasien(Request $request)
    {
        try {
            DB::beginTransaction();

            $dto = $request['dto'];
            if($dto['id'] == '') {
                $data = new BerkasPasien();
                $data->id = $this->SEQUENCE_MASTER(new BerkasPasien(), 'id', $this->kdProfile);
                $data->kdprofile = $this->kdProfile;
                $data->statusenabled = true;
                $data->nama = $dto['nama'];
                $data->save();
            } else {
                $data = BerkasPasien::where('id', $dto['id'])->first();
                // $data->statusenabled = $dto['statusenabled'];
                $data->nama = $dto['nama'];
                $data->save();
            }
            $res['status'] = true;
            $res['message'] = "Proses Simpan Data Berhasil";
            DB::commit();
            return $this->respond($res);
        } catch (Exception $e) {
            DB::rollBack();
            $res['status'] = false;
            $res['message'] = $e->getMessage();
            return $this->respond($res);
        }
    }

    public function deleteBerkasPasien($id)
    {
        try {
            DB::beginTransaction();
            $data = BerkasPasien::where('id', $id)->first();
            $data->statusenabled = false;
            $data->save();
            $res['status'] = true;
            $res['message'] = "Proses Hapus Data Berhasil";
            DB::commit();
            return $this->respond($res);
        } catch (Exception $e) {
            DB::rollBack();
            $res['status'] = false;
            $res['message'] = $e->getMessage();
            return $this->respond($res);
        }
    }

}
