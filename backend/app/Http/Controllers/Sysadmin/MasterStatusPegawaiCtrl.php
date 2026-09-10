<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\StatusPegawai;
use App\Models\Master\StatusPulang;
use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MasterStatusPegawaiCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('statuspegawai_m')
            ->select(
                'id',
                'qstatuspegawai',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'statuspegawai',
            )
            ->where('kdprofile', $this->kdProfile);
            // ->orderByDesc('statuspegawai');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['statuspegawai']) && $r['statuspegawai'] != '') {
            $data = $data->where('statuspegawai', 'ilike', '%' . $r['statuspegawai'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('statusenabled', '=', $r['statusenabled']);
        }

        $data = $data->orderByDesc('statuspegawai');
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
            $faker = Factory::create();
            $count = StatusPegawai::count();
            $SP =  $request['statuspegawai'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($SP['id'] == '') {
                $statusPeg = new StatusPegawai();
                $statusPeg->id = $this->SEQUENCE_MASTER(new StatusPegawai(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $statusPeg->statusenabled = true;
                $statusPeg->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $statusPeg->kdstatuspegawai =  $codeUnique;
                $statusPeg->qstatuspegawai = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $statusPeg = StatusPegawai::where('id',$SP['id'])->first();
                $statusPeg->statusenabled = $SP['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $statusPeg->id;
            }
            $statusPeg->kdprofile = (int)$this->kdProfile;
            $statusPeg->statuspegawai =  $SP['statuspegawai'];
            $statusPeg->norec =  $SP['norec'];
            $statusPeg->namaexternal =  $SP['statuspegawai'];
            $statusPeg->reportdisplay =  $SP['statuspegawai'];
            $statusPeg->save();
            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $statusPeg,
                    "as" => 'setiawan@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "status" => $e->getCode(),
                "message" => "Proses Simpan Data Gagal", 
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $statusPeg = StatusPegawai::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $statusPeg,
                    "as" => 'setiawan@epic',
                ]
            ];
        
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "status" => $e->getCode(),
                "message" =>"Something Went Wrong", 
                "result"  => $e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
