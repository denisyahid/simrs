<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JenisTransaksi;
use App\Models\Master\KelompokBarang;
use App\Models\Master\KelompokProduk;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class MasterKelompokBarangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterKelompokBarang(Request $r)
    {
        $data  = DB::table('kelompokproduk_m')
            ->select('*')
            ->where('kdprofile', $this->kdProfile)
            ->orderByDesc('kelompokproduk');

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('statusenabled', $r['statusenabled']);
        }

        $data = $data->get();

        foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'info';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }
        }
        $res['data'] = $data;

        return $this->respond($res);
    }

    public function saveKelompokBarang(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = KelompokBarang::count();
            $PSN =  $request['kelompokbarang'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new KelompokBarang();
                $data->id = $this->SEQUENCE_MASTER(new KelompokBarang(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->norec = $data->id;
                $data->statusenabled = true;
                $data->kdkelompokbarang =  $codeUnique;
                $data->qkelompokbarang = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = KelompokBarang::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->kelompokbarang =  $PSN['kelompokbarang']; 
            $data->namaexternal =  $PSN['kelompokbarang'];
            $data->reportdisplay =  $PSN['kelompokbarang'];
            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
            
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
    public function deleteKelompokBarang(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = KelompokBarang::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                    ]
                ];
                $transMessage = "Proses Hapus Data Berhasil";
        } catch (\Exception $e) {
            DB::rollBack();
            $transMessage = "Something Went Wrong";
            $result = [
                "status" => 400,
                "result"  => [
                    "e"  => $e->getLine() . ' ' . $e->getMessage(),
                    ]
                ];
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
