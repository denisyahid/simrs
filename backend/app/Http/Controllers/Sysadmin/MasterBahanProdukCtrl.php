<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Master\TipePegawai;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\BahanProduk;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokProduk;
use Mockery\Exception\InvalidOrderException;

class MasterBahanProdukCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('bahanproduk_m as bp')
            ->leftjoin('departemen_m as dep', 'bp.objectdepartemenfk', '=', 'dep.id')
            ->leftjoin('kelompokproduk_m as kp', 'bp.objectkelompokprodukfk', '=', 'kp.id')
            ->select(
                'bp.id',
                'bp.kdprofile',
                'bp.namaexternal',
                'bp.kodeexternal',
                'bp.statusenabled',
                'bp.reportdisplay',
                'bp.norec',
                'dep.namadepartemen',
                'bp.objectkelompokprodukfk',
                'bp.objectdepartemenfk',
                'kp.kelompokproduk',
                'bp.kdbahanproduk',
                'bp.namabahanproduk',
                'bp.qbahanproduk',
            )
            ->where('bp.kdprofile', $this->kdProfile)
            ->orderByDesc('bp.namabahanproduk');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('bp.id', '=',  $request['id']);
        }
        if (isset($request['kondisipasien']) && $request['kondisipasien'] != '') {
            $data = $data->where('bp.kondisipasien', 'ilike', '%' . $request['kondisipasien'] . '%');
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

        // return response()->json($res);
        return $this->respond($data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = BahanProduk::count();
            $codeUnique = $count < 1 ? 1 : $count + 1;
            $PSN =  $request['namabahanproduk'];
            if ($PSN['id'] == null) {
                $bhnProduk = new BahanProduk();
                $bhnProduk->id = $this->SEQUENCE_MASTER(new BahanProduk(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $bhnProduk->kdbahanproduk =  $codeUnique;
                $bhnProduk->qbahanproduk = $codeUnique;
                $bhnProduk->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $bhnProduk->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $bhnProduk = BahanProduk::where('id', $PSN['id'])->first();
                $bhnProduk->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $bhnProduk->id;
            }
            $bhnProduk->kdprofile = (int)$this->kdProfile;
            $bhnProduk->namabahanproduk =  $PSN['namabahanproduk'];
            $bhnProduk->objectkelompokprodukfk =  $PSN['objectkelompokprodukfk'];
            $bhnProduk->objectdepartemenfk =  $PSN['objectdepartemenfk'];
            $bhnProduk->namaexternal =  $PSN['namabahanproduk'];
            $bhnProduk->reportdisplay =  $PSN['namabahanproduk'];
            $bhnProduk->save();
            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $bhnProduk,
                    "as" => 'setiawan@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "status" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result" => $e->getMessage() 
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
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
            $bhnProduk = BahanProduk::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $bhnProduk,
                    "as" => 'setiawan@epic',
                ]
            ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "status" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
