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
use App\Models\Master\BankAccount;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\Rekanan;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class MasterBankCtrl extends Controller
{

    use Valet;
    
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function masterBank(Request $r)
    {
         $data = DB::table('bankaccount_m as ba')
                ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'ba.kdrekananfk')
                ->select(
               'ba.id',
               'ba.bankaccountnama',
               'ba.bankaccountnomor',
               'rk.namarekanan',
               'ba.statusenabled'
                )
                ->where('ba.kdprofile',$this->kdProfile)
                ->orderByDesc('ba.bankaccountnama');
  
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('ba.id', '=',  $r['id']);
        }
        if (isset($r['bankaccountnama']) && $r['bankaccountnama'] != '') {
            $data = $data->where('ba.bankaccountnama', 'ilike', '%' . $r['bankaccountnama'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('ba.statusenabled', '=', $r['statusenabled']);
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

    public function saveBankAkun(Request $request)
    {
        DB::beginTransaction();
        try {

            $faker = Factory::create();
            $count = BankAccount::count();
            $PSN =  $request['bankaccountnama'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;  
               
            if ($PSN['id'] == '') {
                $data = new BankAccount();
                $data->id = $this->SEQUENCE_MASTER(new BankAccount(),'id',$this->kdProfile);///(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = BankAccount::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->bankaccountnama =  $PSN['bankaccountnama']; 
            $data->bankaccountnomor =  $PSN['bankaccountnomor']; 
            $data->keteranganlainnya =  $PSN['keteranganlainnya'];
            $data->kdrekananfk =  $PSN['kdrekananfk'];
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
    

    public function rkDD (Request $r)
    {
        $res['namarekanan'] = Rekanan::mine()->get();

        return $this->respond($res);
    }


    public function deleteBankAkun(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = BankAccount::where('id', $r['id'])
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
                "message" => "Something Went Wrong",
                "result"  => [
                    "messageError" => $e->getMessage(),
                    ]
                ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
}