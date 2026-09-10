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
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class MasterStatusKeluarCtrl extends Controller
{

    use Valet;
    
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
         $data = DB::table('statuskeluar_m as sk')
                ->join('jeniskondisipasien_m as jkp', 'sk.objectjeniskondisipasienfk', '=', 'jkp.id')
                ->select(
                    'sk.id',
                    'sk.qstatuskeluar',
                    'sk.statusenabled',
                    'sk.namaexternal',
                    'sk.reportdisplay',
                    'sk.kodeexternal',
                    'sk.objectjeniskondisipasienfk',
                    'jkp.jeniskondisipasien',
                    'sk.statuskeluar',
                )
                ->where('sk.kdprofile',$this->kdProfile)
                ->orderByDesc('sk.statuskeluar');
  
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('sk.id', '=',  $r['id']);
        }
        if (isset($r['statuskeluar']) && $r['statuskeluar'] != '') {
            $data = $data->where('sk.statuskeluar', 'ilike', '%' . $r['statuskeluar'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('sk.statusenabled', '=', $r['statusenabled']);
        }
    
        
        $data = $data->get();
        
        foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'primary';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }

        }
        $res['data'] = $data;
        // return response()->json($res);
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = StatusKeluar::count();
            $PSN =  $request['statuskeluar'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new StatusKeluar();
                $data->id = $this->SEQUENCE_MASTER(new StatusKeluar(),'id',$this->kdProfile);///(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $data->kdstatuskeluar =  $codeUnique;
                $data->qstatuskeluar = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = StatusKeluar::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->statuskeluar =  $PSN['statuskeluar']; 
            $data->objectjeniskondisipasienfk =  $PSN['objectjeniskondisipasienfk']; 
            // $data->norec =  $PSN['norec'];
            $data->namaexternal =  $PSN['statuskeluar'];
            $data->reportdisplay =  $PSN['statuskeluar'];
            $data->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
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
    

    public function jenisKondisiPasien(Request $r)
    {
        $res['jeniskondisipasien'] = JenisKondisiPasien::mine()->get();

        return $this->respond($res);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = StatusKeluar::where('id', $r['id'])
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