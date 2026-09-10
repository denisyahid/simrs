<?php 

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JenisKomponenHarga;
use Exception;
use Mockery\Exception\InvalidOrderException;

class MasterJenisKomponenHargaCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {

        $data = DB::table('jeniskomponenharga_m as jkh')
                ->leftjoin('departemen_m as dep','jkh.objectdepartemenfk','=','dep.id')
                ->select(
                    'jkh.id',
                    'jkh.statusenabled',
                    'jkh.namaexternal',
                    'jkh.reportdisplay',
                    'jkh.kodeexternal',
                    'jkh.objectdepartemenfk',
                    'dep.namadepartemen',
                    'jkh.jeniskomponenharga',
                    'jkh.nourut',
                )
            ->where('jkh.kdprofile',$this->kdProfile)
            ->orderByDesc('jkh.jeniskomponenharga');

            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['jeniskomponenharga']) && $r['jeniskomponenharga'] != '') {
                $data = $data->where('jkh.jeniskomponenharga', 'ilike', '%' . $r['jeniskomponenharga'] . '%');
            }
            if (isset($r['objectdepartemenfk']) && $r['objectdepartemenfk'] != '') {
                $data = $data->where('jkh.objectdepartemenfk', 'ilike', '%' . $r['objectdepartemenfk'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('jkh.statusenabled', '=', $r['statusenabled']);
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
            $count = JenisKomponenHarga::count();
            $PSN =  $r['jeniskomponenharga'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new JenisKomponenHarga();
                $data->id = $this->SEQUENCE_MASTER(new JenisKomponenHarga(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->statusenabled = true;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique; 
                $data->kdjeniskomponenharga = $codeUnique; 
                $data->qjeniskomponenharga = $codeUnique; 
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = JenisKomponenHarga::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
                $data->kdprofile = (int)$this->kdProfile;
                $data->jeniskomponenharga =  $PSN['jeniskomponenharga']; 
                $data->objectdepartemenfk =  $PSN['objectdepartemenfk']; 
                $data->namaexternal =  $PSN['jeniskomponenharga'];
                $data->reportdisplay =  $PSN['jeniskomponenharga'];           
                $data->nourut =  $PSN['nourut'];                     
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
                "status" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => $e->getLine() . ' ' . $e->getMessage(),
                ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
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
            $dataPS = JenisKomponenHarga::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $dataPS,
                    "as" => '@epic',
                    ]
                ];

        } catch (Exception $e) {
            DB::rollBack();
   
            $result = [
                "status" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => $e->getLine() . ' ' . $e->getMessage()
                ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}