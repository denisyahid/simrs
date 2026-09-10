<?php 

namespace App\Http\Controllers\Sysadmin;
use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisDiet;
use App\Models\Master\KategoryDiet;
use App\Models\Master\KelompokPasien;
use App\Models\Master\KelompokProduk;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class MasterKategoryDietCtrl extends Controller
{

    use Valet;
    
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function masterKategoryDiet(Request $r)
    {
         $data = DB::table('kategorydiet_m as kd')
                ->join('kelompokproduk_m as kp', 'kd.objectkelompokprodukfk', '=', 'kp.id')
                ->select(
                    'kd.id',
                    'kd.kategorydiet',
                    'kp.kelompokproduk',
                    'kp.statusenabled'
                )
                ->where('kd.kdprofile',$this->kdProfile);
  
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('kd.id', '=',  $r['id']);
        }
        if (isset($r['kategorydiet']) && $r['kategorydiet'] != '') {
            $data = $data->where('kd.kategorydiet', 'ilike', '%' . $r['kategorydiet'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('kd.statusenabled', '=', $r['statusenabled']);
        }
    
        $data = $data->orderBy('kd.kategorydiet');
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
        // return response()->json($res);
        return $this->respond($res);
    }

    public function saveKategoryDiet(Request $request)
    {
        DB::beginTransaction();
        try {

            $faker = Factory::create();
            $count = KategoryDiet::count();
            $PSN =  $request['kategorydiet'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new KategoryDiet();
                $data->id = $this->SEQUENCE_MASTER(new JenisDiet(),'id',$this->kdProfile);///(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $transMessage = "Simpan Data Berhasil";

            } else {
                $data = KategoryDiet::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->kategorydiet =  $PSN['kategorydiet'];
            $data->objectkelompokprodukfk =  $PSN['objectkelompokprodukfk']; 
            $data->namaexternal =  $PSN['kategorydiet'];
            $data->reportdisplay =  $PSN['kategorydiet'];
            $data->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
            
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
    

    public function ListKP(Request $r)
    {
        $res['kelompokproduk'] = KelompokProduk::mine()->get();

        return $this->respond($res);
    }


    public function deleteKategoryDiet(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = KategoryDiet::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                    ]
                ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Hapus Data Gagal, Silakan Cek Kembali Data",
                "result"  => [
                    "messageError" => $e->getMessage(),
                    ]
                ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
}