<?php 

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalSukuCadang;
use Exception;

class MasterAsalSukuCadangCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

        public function index(Request $r)
    {
        $data = DB::table('asalsukucadang_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'asalsukucadang',
            )
            ->where('kdprofile',$this->kdProfile)
            ->orderByDesc('asalsukucadang');
    
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['asalsukucadang']) && $r['asalsukucadang'] != '') {
                $data = $data->where('asalsukucadang', 'ilike', '%' . $r['asalsukucadang'] . '%');
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
            $count = AsalSukuCadang::count();
            $PSN =  $r['asalsukucadang'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new AsalSukuCadang();
                $data->id = $this->SEQUENCE_MASTER(new AsalSukuCadang(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->statusenabled = true;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique; 
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = AsalSukuCadang::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
                $data->kdprofile = (int)$this->kdProfile;
                $data->asalsukucadang =  $PSN['asalsukucadang']; 
                $data->namaexternal =  $PSN['asalsukucadang'];
                $data->reportdisplay =  $PSN['asalsukucadang'];           
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
                "result"  => $e->getLine() . ' ' . $e->getMessage(),
                ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = AsalSukuCadang::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $dataPS,
                    "as" => 'setiawan@epic',
                    ]
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
}