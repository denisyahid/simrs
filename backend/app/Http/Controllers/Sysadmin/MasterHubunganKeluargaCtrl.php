<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\HubunganKeluarga;

class MasterHubunganKeluargaCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

        public function index(Request $r)
    {
        $data = DB::table('hubungankeluarga_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'hubungankeluarga',
                'kdhubungankeluarga',
                'qhubungankeluarga',
            )
            ->where('kdprofile',$this->kdProfile);    
    
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['hubungankeluarga']) && $r['hubungankeluarga'] != '') {
                $data = $data->where('hubungankeluarga', 'ilike', '%' . $r['hubungankeluarga'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }
        
            $data = $data->orderByDesc('hubungankeluarga');
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
        // return response()->json($res);
        return $this->respond($res);
    }

   
    public function store(Request $r)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = HubunganKeluarga::count();
            $PSN =  $r['hubungankeluarga'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $hbgKeluarga = new HubunganKeluarga();
                $hbgKeluarga->id = $this->SEQUENCE_MASTER(new HubunganKeluarga(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $hbgKeluarga->kdhubungankeluarga =  $codeUnique;
                $hbgKeluarga->qhubungankeluarga = $codeUnique;
                $hbgKeluarga->statusenabled = true;
                $hbgKeluarga->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique; 
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $hbgKeluarga = HubunganKeluarga::where('id', $PSN['id'])->first();
                $hbgKeluarga->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $hbgKeluarga->id;
            }
                $hbgKeluarga->kdprofile = (int)$this->kdProfile;
                $hbgKeluarga->hubungankeluarga =  $PSN['hubungankeluarga']; 
                $hbgKeluarga->namaexternal =  $PSN['hubungankeluarga'];
                $hbgKeluarga->reportdisplay =  $PSN['hubungankeluarga'];           
                $hbgKeluarga->save();
            
            DB::commit();
            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $hbgKeluarga,
                    "as" => 'setiawan@epic',
                ],
            ];

        } catch (Exception $e) {
        
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  =>  $e->getLine() . ' ' . $e->getMessage()
                ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $hbgKeluarga = HubunganKeluarga::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $hbgKeluarga,
                    "as" => 'setiawan@epic',
                    ]
                ];

        } catch (Exception $e) {
            DB::rollBack();
          
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  =>  $e->getLine() . ' ' . $e->getMessage()
                ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
