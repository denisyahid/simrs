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
use App\Models\Master\Negara;
use App\Models\Master\ProdusenProduk;
use InvalidArgumentException;
use Mockery\Exception\InvalidOrderException;

class MasterProdusenProdukCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('produsenproduk_m as pp')
            ->leftjoin('departemen_m as dep', 'pp.objectdepartemenfk', '=', 'dep.id')
            ->leftjoin('negara_m as neg', 'pp.objectnegarafk', '=', 'neg.id')
            ->select(
                'pp.id',
                'pp.kdprofile',
                'pp.namaexternal',
                'pp.kodeexternal',
                'pp.statusenabled',
                'pp.reportdisplay',
                'pp.norec',
                'dep.namadepartemen',
                'pp.objectdepartemenfk',
                'pp.objectnegarafk',
                'neg.namanegara',
                'pp.kdprodusenproduk',
                'pp.namaprodusenproduk',
                'pp.qprodusenproduk',
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->orderByDesc('namaprodusenproduk');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('pp.id', '=',  $request['id']);
        }
        if (isset($request['namaprodusenproduk']) && $request['namaprodusenproduk'] != '') {
            $data = $data->where('pp.namaprodusenproduk', 'ilike', '%' . $request['namaprodusenproduk'] . '%');
        }
        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('pp.statusenabled', '=', $request['statusenabled']);
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
            'count' => $data->count()
        ];
        // return response()->json($res);
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = ProdusenProduk::count();
            $codeUnique = $count < 1 ? 1 : $count + 1;
            $PSN =  $request['produsenproduk'];
            if ($PSN['id'] == null) {
                $prdsProd = new ProdusenProduk();
                $prdsProd->id = $this->SEQUENCE_MASTER(new ProdusenProduk(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $prdsProd->kdprodusenproduk =  $codeUnique;
                $prdsProd->qprodusenproduk = $codeUnique;
                $prdsProd->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $prdsProd->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $prdsProd = ProdusenProduk::where('id', $PSN['id'])->first();
                $prdsProd->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $prdsProd->id;
            }
            $prdsProd->kdprofile = (int)$this->kdProfile;
            $prdsProd->namaprodusenproduk =  $PSN['namaprodusenproduk'];
            $prdsProd->objectdepartemenfk =  $PSN['objectdepartemenfk'];
            $prdsProd->objectnegarafk =  $PSN['objectnegarafk'];
            $prdsProd->namaexternal =  $PSN['namaprodusenproduk'];
            $prdsProd->reportdisplay =  $PSN['namaprodusenproduk'];
            $prdsProd->save();
            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $prdsProd,
                    "as" => 'setiawan@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => 400,
                "message" => "Something Went Wrong", 
                "result"  => $e->getMessage()
            ];
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function dropdownItem()
    {
        $res['namanegara'] = Negara::mine()->get();
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = ProdusenProduk::where('id', $r['id'])
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

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong", 
                "result"  =>$e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
