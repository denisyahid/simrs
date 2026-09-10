<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\BentukProduk;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokProduk;
use Mockery\Exception\InvalidOrderException;

class MasterBentukProdukCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function index(Request $request)
    {
        $data = DB::table('bentukproduk_m as bp')
            ->leftjoin('departemen_m as dep', 'bp.objectdepartemenfk', '=', 'dep.id')
            ->leftjoin('kelompokproduk_m as kp', 'bp.objectkelompokprodukfk', '=', 'kp.id')
            ->select(
                'bp.id',
                'bp.kdprofile',
                'bp.namaexternal',
                'bp.kodeexternal',
                'bp.reportdisplay',
                'bp.norec',
                'bp.statusenabled',
                'dep.namadepartemen',
                'bp.objectkelompokprodukfk',
                'bp.objectdepartemenfk',
                'kp.kelompokproduk',
                'bp.kdbentukproduk',
                'bp.namabentukproduk',
                'bp.qbentukproduk',
            )
            ->where('bp.kdprofile', $this->kdProfile)
            ->orderByDesc('bp.namabentukproduk');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('bp.id', '=',  $request['id']);
        }
        if (isset($request['namabentukproduk']) && $request['namabentukproduk'] != '') {
            $data = $data->where('bp.namabentukproduk', 'ilike', '%' . $request['namabentukproduk'] . '%');
        }
        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('bp.statusenabled', '=', $request['statusenabled']);
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

        return $this->respond($data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = BentukProduk::count();
            $codeUnique = $count < 1 ? 1 : $count + 1;
            $PSN =  $request['namabentukproduk'];
            if ($PSN['id'] == null) {
                $btkProduk = new BentukProduk();
                $btkProduk->id = $this->SEQUENCE_MASTER(new BentukProduk(),'id',$this->kdProfile);///(string)Uuid::uuid4();
                $btkProduk->kdbentukproduk =  $codeUnique;
                $btkProduk->qbentukproduk = $codeUnique;
                $btkProduk->statusenabled = true;
                $btkProduk->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $btkProduk = BentukProduk::where('id', $PSN['id'])->first();
                $btkProduk->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $btkProduk->id;
            }
            $btkProduk->kdprofile = (int)$this->kdProfile;
            $btkProduk->namabentukproduk =  $PSN['namabentukproduk'];
            $btkProduk->objectkelompokprodukfk =  $PSN['objectkelompokprodukfk'];
            $btkProduk->objectdepartemenfk =  $PSN['objectdepartemenfk'];
            $btkProduk->namaexternal =  $PSN['namabentukproduk'];
            $btkProduk->reportdisplay =  $PSN['namabentukproduk'];
            $btkProduk->save();
            DB::commit();
    
            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $btkProduk,
                    "as" => 'setiawan@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {

            DB::rollBack();
            $result = [
                "message" => "Something Went Wrong", 
                "statusCode" => $e->getCode(),
                "result"  => $e->getMessage()
            ];
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function dropdownItem()
    {
        $res['kelompokproduk'] = KelompokProduk::mine()->get();
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $btkproduk = BentukProduk::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $btkproduk,
                    "as" => 'setiawan@epic',
                ]
            ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  =>$e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
}
