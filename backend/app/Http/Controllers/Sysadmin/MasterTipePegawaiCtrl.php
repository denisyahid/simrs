<?php 

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Master\TipePegawai;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Exception;

class MasterTipePegawaiCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('typepegawai_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'typepegawai',
                'kdtypepegawai',
                'qtypepegawai',
            )
            ->where('kdprofile',$this->kdProfile)
            ->orderByDesc('typepegawai');
            
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['typepegawai']) && $r['typepegawai'] != '') {
                $data = $data->where('typepegawai', 'ilike', '%' . $r['typepegawai'] . '%');
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

            $res['data'] = $data;
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = TipePegawai::count();
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
            $PSN =  $request['typepegawai'];
            if($PSN['id'] == null)
            {
                $typePeg = new TipePegawai();
                $typePeg->id = $this->SEQUENCE_MASTER(new TipePegawai(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $typePeg->statusenabled = true;
                $typePeg->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;   
                $typePeg->kdtypepegawai =  $codeUnique;
                $typePeg->qtypepegawai = $codeUnique;   
                $transMessage = "Proses Simpan Data Berhasil"; 
            }else{
                $typePeg = TipePegawai::where('id', $PSN['id'])->first();
                $typePeg->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $typePeg->id; 
            }
                $typePeg->kdprofile = (int)$this->kdProfile;   
                $typePeg->typepegawai =  $PSN['typepegawai'];
                $typePeg->norec =  $PSN['norec'];
                $typePeg->namaexternal =  $PSN['typepegawai'];
                $typePeg->reportdisplay =  $PSN['typepegawai'];
                $typePeg->save();
                DB::commit();

                $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $typePeg,
                    "as" => 'setiawan@epic',
                    ],
                ];
        }
        catch(Exception $e)
        {
            DB::rollBack();
            $result = [
                "statusCode" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getLine() . ' ' . $e->getMessage()
                ];
        }

        return $this->respond($result['result'], $result['statusCode'],$result['message']);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = TipePegawai::where('id', $r['id'])->update(['statusenabled' => false]);
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
        
        return $this->respond($result['result'], $result['status'],$result['message']);
    }
    
}