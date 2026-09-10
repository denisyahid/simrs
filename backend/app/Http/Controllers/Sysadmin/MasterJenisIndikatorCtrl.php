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
use App\Models\Master\JenisIndikator;
use App\Models\Master\JenisKondisiPasien;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class MasterJenisIndikatorCtrl extends Controller
{

    use Valet;
    
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
         $data = DB::table('jenisindikator_m')
                ->select(
                    'id',
                    'statusenabled',
                    'namaexternal',
                    'reportdisplay',
                    'kodeexternal',
                    'jenisindikator',
                )
                ->where('kdprofile',$this->kdProfile)
                ->orderByDesc('jenisindikator');
  
        if (isset($r['jenisindikator']) && $r['jenisindikator'] != '') {
            $data = $data->where('jenisindikator', 'ilike', '%' . $r['jenisindikator'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('statusenabled', '=', $r['statusenabled']);
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
            $count = JenisIndikator::count();
            $PSN =  $request['jenisindikator'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new JenisIndikator();
                $data->id = $this->SEQUENCE_MASTER(new JenisIndikator(),'id',$this->kdProfile);///(string)Uuid::uuid4();
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = JenisIndikator::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->jenisindikator =  $PSN['jenisindikator']; 
            $data->kodeexternal =  $PSN['kodeexternal']; 
            $data->norec = null;
            $data->namaexternal =  $PSN['jenisindikator'];
            $data->reportdisplay =  $PSN['jenisindikator'];
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

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = JenisIndikator::where('id', $r['id'])
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