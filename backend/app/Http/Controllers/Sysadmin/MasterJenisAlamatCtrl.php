<?php 

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisAlamat;
use Exception;
use Mockery\Exception\InvalidOrderException;

class MasterJenisAlamatCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

        public function index(Request $r)
    {
        $data = DB::table('jenisalamat_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'jenisalamat',
                'kdjenisalamat',
                'qjenisalamat',
            )
            ->where('kdprofile',$this->kdProfile)
            ->orderByDesc('jenisalamat');
    
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['jenisalamat']) && $r['jenisalamat'] != '') {
                $data = $data->where('jenisalamat', 'ilike', '%' . $r['jenisalamat'] . '%');
            }
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
            
        $res = [
            'data' => $data,
            'count' =>$data->count()
        ];
        return $this->respond($res);
    }

   
    public function store(Request $r)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = JenisAlamat::count();
            $PSN =  $r['jenisalamat'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new JenisAlamat();
                $data->id = $this->SEQUENCE_MASTER(new JenisAlamat(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kdjenisalamat =  $codeUnique;
                $data->qjenisalamat = $codeUnique;
                $data->statusenabled = true;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique; 
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = JenisAlamat::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
                $data->kdprofile = (int)$this->kdProfile;
                $data->jenisalamat =  $PSN['jenisalamat']; 
                $data->norec =  $PSN['norec'];
                $data->namaexternal =  $PSN['jenisalamat'];
                $data->reportdisplay =  $PSN['jenisalamat'];           
                $data->save();
            
            DB::commit();
            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];

        } catch (Exception $e) {

            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getLine() . ' ' . $e->getMessage()
                ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = JenisAlamat::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $dataPS,
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