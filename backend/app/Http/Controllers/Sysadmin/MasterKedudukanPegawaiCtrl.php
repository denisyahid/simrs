<?php

namespace App\Http\Controllers\Sysadmin;


use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\KedudukanPegawai;
use Mockery\Exception\InvalidOrderException;

class MasterKedudukanPegawaiCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('sdm_kedudukan_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'kedudukan',
            )
            ->where('kdprofile', $this->kdProfile);

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['kedudukan']) && $r['kedudukan'] != '') {
            $data = $data->where('kedudukan', 'ilike', '%' . $r['kedudukan'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('statusenabled', '=', $r['statusenabled']);
        }

        $data = $data->orderByDesc('kedudukan');
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

        return $this->respond($res);
    }


    public function store(Request $r)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = KedudukanPegawai::count();
            $PSN =  $r['kedudukan'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $kedudukan = new KedudukanPegawai();
                $kedudukan->id = $this->SEQUENCE_MASTER(new KedudukanPegawai(), 'id', $this->kdProfile); //(string)Uuid::uuid4();
                $kedudukan->statusenabled = true;
                $kedudukan->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $kedudukan = KedudukanPegawai::where('id', $PSN['id'])->first();
                $kedudukan->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $kedudukan->id;
            }
            $kedudukan->kdprofile = (int)$this->kdProfile;
            $kedudukan->kedudukan =  $PSN['kedudukan'];
            $kedudukan->namaexternal =  $PSN['kedudukan'];
            $kedudukan->reportdisplay =  $PSN['kedudukan'];
            $kedudukan->save();

            DB::commit();
            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $kedudukan,
                    "as" => '@epic',
                ],
            ];
        } catch (Exception $e) {

            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getLine() . ' ' . $e->getMessage()
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $kedudukan = KedudukanPegawai::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => $kedudukan
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getLine() . ' ' . $e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
