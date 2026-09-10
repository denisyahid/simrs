<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JadwalPraktek;
use App\Traits\Valet;
use Exception;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterJadwalPraktekCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('jadwalpraktek_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'waktupraktek',
                'jammulai',
                'kdpraktek',
                'jamselesai',
            )
            ->where('kdprofile', $this->kdProfile)
            ->orderByDesc('namaexternal');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['waktupraktek']) && $r['waktupraktek'] != '') {
            $data = $data->where('waktupraktek', 'ilike', '%' . $r['waktupraktek'] . '%');
        }
        if (isset($r['namaexternal']) && $r['namaexternal'] != '') {
            $data = $data->where('namaexternal', 'ilike', '%' . $r['namaexternal'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('statusenabled', '=', $r['statusenabled']);
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

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = JadwalPraktek::count();
            $JP =  $request['jadwalpraktek'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($JP['id'] == '') {
                $dataPS = new JadwalPraktek();
                $dataPS->id = $this->SEQUENCE_MASTER(new JadwalPraktek(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $dataPS->statusenabled = true;
                $dataPS->kdpraktek = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $dataPS = JadwalPraktek::where('id',$JP['id'])->first();
                $dataPS->statusenabled = $JP['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $dataPS->id;
            }
            $dataPS->kdprofile = (int)$this->kdProfile;
            $dataPS->waktupraktek =  $JP['waktupraktek'];
            $dataPS->jammulai =  $JP['jammulai'];
            $dataPS->jamselesai =  $JP['jamselesai'];
            $dataPS->namaexternal =  $JP['waktupraktek'];
            $dataPS->reportdisplay =  $JP['waktupraktek'];
            $dataPS->save();
            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $dataPS,
                    "as" => '@epic',
                ],
            ];
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

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = JadwalPraktek::where('id', $r['id'])->update(['statusenabled' => false]);
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
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
