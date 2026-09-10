<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\StatusBed;
use Illuminate\Http\Request;
use Faker\Factory;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\DB;

class MasterStatusBedCtrl extends Controller
{
    use Valet;


    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('statusbed_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'norec',
                'kdprofile',
                'kodeexternal',
                'statusbed',
                'statusbed',
                'txtcolor',
                'color',
                'nourut',
            )
            ->where('kdprofile',$this->kdProfile)
            ->orderByDesc('statusbed');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('id', '=',  $request['id']);
        }
        if (isset($request['statusbed']) && $request['statusbed'] != '') {
            $data = $data->where('statusbed', 'ilike', '%' . $request['statusbed'] . '%');
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
            $count = StatusBed::count();
            $PSN =  $request['statusbed'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $dataPS = new StatusBed();
                $dataPS->id = $this->SEQUENCE_MASTER(new StatusBed(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $dataPS->statusenabled = true;
                $dataPS->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $dataPS = StatusBed::where('id', $PSN['id'])->first();
                $dataPS->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $dataPS->id;
            }
            $dataPS->kdprofile = (int)$this->kdProfile;
            $dataPS->namaexternal =  $PSN['statusbed'];
            $dataPS->statusbed =  $PSN['statusbed'];
            $dataPS->reportdisplay =  $PSN['statusbed'];
            $dataPS->nourut =  $PSN['nourut'];


            $dataPS->save();

            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $dataPS,
                    "as" => 'setiawan@epic',
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

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = StatusBed::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
                DB::commit();
                $result = array(
                    "status" => 200,
                    "message" => "Proses Hapus Data Berhasil",
                    "result" => array(
                        "data"  => $dataPS,
                        "as" => 'setiawan@epic',
                    ),
                );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
