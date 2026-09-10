<?php 

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisKondisiPasien;
use Exception;

class MasterJenisKondisiPasienCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

        public function index(Request $r)
    {
        $data = DB::table('jeniskondisipasien_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'jeniskondisipasien',
                'kdjeniskondisipasien',
                'qjeniskondisipasien',
            )
            ->where('kdprofile',$this->kdProfile);    
    
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['jeniskondisipasien']) && $r['jeniskondisipasien'] != '') {
                $data = $data->where('jeniskondisipasien', 'ilike', '%' . $r['jeniskondisipasien'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }
        
            $data = $data->orderByDesc('jeniskondisipasien');
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
            $count = JenisKondisiPasien::count();
            $PSN =  $r['jeniskondisipasien'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $jnkKonPas = new JenisKondisiPasien();
                $jnkKonPas->id =$this->SEQUENCE_MASTER(new JenisKondisiPasien(),'id',$this->kdProfile);// (string)Uuid::uuid4();
                $jnkKonPas->kdjeniskondisipasien =  $codeUnique;
                $jnkKonPas->qjeniskondisipasien = $codeUnique;
                $jnkKonPas->statusenabled = true;
                $jnkKonPas->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique; 
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $jnkKonPas = JenisKondisiPasien::where('id', $PSN['id'])->first();
                $jnkKonPas->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $jnkKonPas->id;
            }
                $jnkKonPas->kdprofile = (int)$this->kdProfile;
                $jnkKonPas->jeniskondisipasien =  $PSN['jeniskondisipasien']; 
                $jnkKonPas->namaexternal =  $PSN['jeniskondisipasien'];
                $jnkKonPas->reportdisplay =  $PSN['jeniskondisipasien'];           
                $jnkKonPas->save();
            
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $jnkKonPas,
                    "as" => 'setiawan@epic',
                ],
            ];

        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "statusCode" => 400,
                "message" => "Something Went Wrong",
                "result"  =>$e->getLine() . ' ' . $e->getMessage()
            ];
        }

        return $this->respond($result['result'], $result['statusCode'],$result['message']);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $jenKonPas = JenisKondisiPasien::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $jenKonPas,
                    "as" => '@epic',
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