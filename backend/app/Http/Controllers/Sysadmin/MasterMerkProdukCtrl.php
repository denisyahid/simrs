<?php 

namespace App\Http\Controllers\Sysadmin;
use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\MerkProduk;

class MasterMerkProdukCtrl extends Controller
{

    use Valet;
    
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
         $data = DB::table('merkproduk_m as mp')
                ->join('departemen_m as dep', 'mp.objectdepartemenfk', '=', 'dep.id')
                ->select(
                    'mp.id',
                    'mp.qmerkproduk',
                    'mp.statusenabled',
                    'mp.namaexternal',
                    'mp.reportdisplay',
                    'mp.kodeexternal',
                    'mp.objectdepartemenfk',
                    'dep.namadepartemen',
                    'mp.merkproduk',
                )
                ->where('mp.kdprofile',$this->kdProfile)
                ->orderByDesc('mp.merkproduk');
  
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=', $r['statusenabled']);
        }
        if(isset($r['limit']) && $r['limit'] != ''){
            $data = $data->limit($r['limit']);
        }
        if(isset($r['offset']) && $r['offset'] != ''){
            $data = $data->offset($r['offset']);
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
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = MerkProduk::count();
            $PSN =  $request['merkproduk'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new MerkProduk();
                $data->id = $this->SEQUENCE_MASTER(new MerkProduk(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $data->kdmerkproduk =  $codeUnique;
                $data->qmerkproduk = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = MerkProduk::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->merkproduk =  $PSN['merkproduk']; 
            $data->objectdepartemenfk =  $PSN['objectdepartemenfk']; 
            $data->namaexternal =  $PSN['merkproduk'];
            $data->reportdisplay =  $PSN['merkproduk'];
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
            $result = array(
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['status'],$result['message']);
    }
    

    public function dropdownItem()
    {
        $res['departemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = MerkProduk::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                    ]
                ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => [
                    "e"  => $e->getLine() . ' ' . $e->getMessage(),
                    ]
                ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}