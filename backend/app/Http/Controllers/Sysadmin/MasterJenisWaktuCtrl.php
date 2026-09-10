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
use App\Models\Master\JenisDiet;
use App\Models\Master\JenisWaktu;
use App\Models\Master\KelompokProduk;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class MasterJenisWaktuCtrl extends Controller
{

    use Valet;
    
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function masterJenisWaktu(Request $r)
    {
         $data = DB::table('jeniswaktu_m as jw')
                ->join('kelompokproduk_m as kp', 'jw.objectkelompokprodukfk', '=', 'kp.id')
                ->join('departemen_m as dp', 'jw.objectdepartemenfk', '=', 'dp.id' )
                ->select(
                    'jw.id',
                    'jw.jeniswaktu',
                    'kp.kelompokproduk',
                    'dp.namadepartemen',
                    'jw.statusenabled',
                    'jw.jamakhir',
                    'jw.jamawal'
                )
                ->where('jw.kdprofile',$this->kdProfile);
  
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('jw.id', '=',  $r['id']);
        }
        if (isset($r['kelompokproduk']) && $r['kelompokproduk'] != '') {
            $data = $data->where('kp.kelompokproduk', 'ilike', '%' . $r['kelompokproduk'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('jw.statusenabled', '=', $r['statusenabled']);
        }
    
        $data = $data->orderBy('jw.jeniswaktu');
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
        // return response()->json($res);
        return $this->respond($res);
    }

    public function saveJenisWaktu(Request $request)
    {
        DB::beginTransaction();
        try {

            $faker = Factory::create();
            $count = JenisWaktu::count();
            $PSN =  $request['jeniswaktu'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new JenisWaktu();
                $data->id = $this->SEQUENCE_MASTER(new JenisWaktu(),'id',$this->kdProfile);///(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $transMessage = "Simpan Data Berhasil";

            } else {
                $data = JenisWaktu::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->jeniswaktu =  $PSN['jeniswaktu'];
            $data->objectkelompokprodukfk =  $PSN['objectkelompokprodukfk']; 
            $data->namaexternal =  $PSN['jeniswaktu'];
            $data->reportdisplay =  $PSN['jeniswaktu'];
            $data->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
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
    

    public function DropdownKP(Request $r)
    {
        $res['kelompokproduk'] = KelompokProduk::mine()->get();
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }


    public function deleteJenisWaktu(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = JenisWaktu::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                    ]
                ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Hapus Data Gagal, Silakan Cek Kembali Data",
                "result"  => [
                    "messageError" => $e->getMessage(),
                    ]
                ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
}