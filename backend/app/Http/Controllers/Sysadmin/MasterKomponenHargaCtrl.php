<?php 

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JenisKemasan;
use App\Models\Master\JenisKomponenHarga;
use App\Models\Master\KomponenHarga;
use App\Models\Master\Produk;
use Exception;

class MasterKomponenHargaCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

        public function index(Request $r)
    {
        $data = DB::table('komponenharga_m as kh')
                ->leftJoin('departemen_m as dep','kh.objectdepartemenfk','=','dep.id')
                ->leftJoin('jeniskomponenharga_m as jkh','kh.objectjeniskomponenhargafk','=','jkh.id')
                ->leftJoin('produk_m as prod','kh.objectprodukpkfk','=','prod.id')
                ->select(
                    'kh.id',
                    'kh.statusenabled',
                    'kh.namaexternal',
                    'kh.reportdisplay',
                    'kh.objectdepartemenfk',
                    'dep.namadepartemen',
                    'kh.objectprodukpkfk',
                    'prod.namaproduk',
                    'kh.objectjeniskomponenhargafk',
                    'jkh.jeniskomponenharga',
                    'kh.komponenharga',
                    'kh.nourut',
                    'kh.nilainormal',
                    'kh.iscito',
                )
            ->where('kh.kdprofile',$this->kdProfile)
            ->orderByDesc('kh.komponenharga');
    
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['komponenharga']) && $r['komponenharga'] != '') {
                $data = $data->where('kh.komponenharga', 'ilike', '%' . $r['komponenharga'] . '%');
            }
            if (isset($r['objectjeniskomponenhargafk']) && $r['objectjeniskomponenhargafk'] != '') {
                $data = $data->where('kh.objectjeniskomponenhargafk', 'ilike', '%' . $r['objectjeniskomponenhargafk']);
            }
            if (isset($r['objectdepartemenfk']) && $r['objectdepartemenfk'] != '') {
                $data = $data->where('kh.objectdepartemenfk', 'ilike', '%' . $r['objectdepartemenfk']);
            }
            if (isset($r['objectprodukpkfk']) && $r['objectprodukpkfk'] != '') {
                $data = $data->where('kh.objectprodukpkfk', 'ilike', '%' . $r['objectprodukpkfk']);
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('kh.statusenabled', '=', $r['statusenabled']);
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
            $count = KomponenHarga::count();
            $PSN =  $r['komponenharga'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new KomponenHarga();
                $data->id = $this->SEQUENCE_MASTER(new KomponenHarga(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->statusenabled = true;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique; 
                $data->kdkomponenharga = $codeUnique; 
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = KomponenHarga::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
                $data->kdprofile = (int)$this->kdProfile;
                $data->komponenharga =  $PSN['komponenharga']; 
                $data->nilainormal =  $PSN['nilainormal']; 
                $data->nourut =  $PSN['nourut']; 
                $data->objectjeniskomponenhargafk =  $PSN['objectjeniskomponenhargafk']; 
                $data->objectprodukpkfk =  $PSN['objectprodukpkfk']; 
                $data->objectdepartemenfk =  $PSN['objectdepartemenfk']; 
                $data->namaexternal =  $PSN['komponenharga'];
                $data->reportdisplay =  $PSN['komponenharga'];           
                $data->iscito =  $PSN['iscito'];           
                $data->save();
            
            DB::commit();
            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                ],
            ];

        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => [
                    "e"  => $e->getLine() . ' ' . $e->getMessage(),
                    ]
                ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function dropdownItem()
    {
        $res['departemen'] = Departemen::mine()->get();
        $res['jeniskomponenharga'] = JenisKomponenHarga::mine()->get();
        $res['produk'] = Produk::mine()->get();

        return $this->respond($res);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = KomponenHarga::where('id', $r['id'])
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
                "status" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => [
                    "e"  => $e->getLine() . ' ' . $e->getMessage(),
                    ]
                ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}