<?php

namespace App\Http\Controllers\Sysadmin;

use Ramsey\Uuid\Uuid;
use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Master\StatusPulang;
use App\Http\Controllers\Controller;
use Mockery\Exception\InvalidOrderException;

class MasterStatusPulangCtrl extends Controller
{

    use Valet;


    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('statuspulang_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'statuspulang',
                'kdstatuspulang',
                'qstatuspulang',
            )
            ->where('kdprofile',$this->kdProfile)
            ->orderByDesc('statuspulang');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('id', '=',  $request['id']);
        }
        if (isset($request['statuskeluar']) && $request['statuskeluar'] != '') {
            $data = $data->where('statuskeluar', 'ilike', '%' . $request['statuskeluar'] . '%');
        }
        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('statusenabled', '=', $request['statusenabled']);
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
        // return response()->json($res);
        return $this->respond($res);
    }


    public function store(Request $request){
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = StatusPulang::count();
            $PSN =  $request['statuspulang'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $data = new StatusPulang();
                $data->id = $this->SEQUENCE_MASTER(new StatusPulang(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->statusenabled = true;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->kdstatuspulang =  $codeUnique;
                $data->qstatuspulang = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = StatusPulang::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->statuspulang =  $PSN['statuspulang'];
            $data->namaexternal =  $PSN['statuspulang'];
            $data->reportdisplay =  $PSN['statuspulang'];
            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => 'Setiawan@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "messageCode" => $e->getMessage(),
                'message' => "Simpan Gagal !"

            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = StatusPulang::where('id', $r->id)->update(['statusenabled' => false]);
            DB::commit();

            $result = [
                "statusCode" => 200,
                "message" => "Data Berhasil Dihapus",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "messageCode" => $e->getMessage(),
                "message" => "Simpan Gagal !",
            ];
        }

        return $this->respond($result,$result['statusCode'],$result['message']);

    }

}
