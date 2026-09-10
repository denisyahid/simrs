<?php 

namespace App\Http\Controllers\Sysadmin;
use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use App\Models\Master\StatusKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\JenisUsulan;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class MasterJenisUsulanCtrl extends Controller
{

    use Valet;
    
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
         $data = DB::table('jenisusulan_m')
                ->select(
                    'id','statusenabled','kodeexternal','jenisusulan','reportdisplay'
                )
                ->where('kdprofile',$this->kdProfile)
                ->orderByDesc('jenisusulan');
  
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('statusenabled', '=', $r['statusenabled']);
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
        $res = $data;
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = JenisUsulan::count();
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($request['id'] == '') {
                $data = new JenisUsulan();
                $data->id = $this->SEQUENCE_MASTER(new JenisUsulan(),'id',$this->kdProfile);///(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $data->kdjenisusulan =  $codeUnique;
                $data->qjenisusulan = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = JenisUsulan::where('id', $request['id'])->first();
                $data->statusenabled = $request['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->jenisusulan =  $request['jenisusulan']; 
            $data->namaexternal =  $request['jenisusulan'];
            $data->reportdisplay =  $request['jenisusulan'];
            $data->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'setiawan@epic',
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
    

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = JenisUsulan::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                    ]
                ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => [
                    "messageError" => $e->getMessage(),
                    ]
                ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
}