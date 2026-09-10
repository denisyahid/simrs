<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\Generik;
use App\Models\Master\JenisGenerik;
use App\Models\Master\Kamar;
use Exception;
use InvalidArgumentException;
use Mockery\Exception\InvalidOrderException;

class MasterGenerikCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('rm_generik_m as gen')
                ->leftjoin('rm_jenisgenerik_m as jegen','gen.golongangenerikfk','=','jegen.id')
                ->select(
                    'gen.id',
                    'gen.statusenabled',
                    'gen.namaexternal',
                    'gen.reportdisplay',
                    'gen.golongangenerikfk',
                    'jegen.jenisgenerik',
                    'gen.kodeexternal',
                    'gen.generik',
                )
                ->where('gen.kdprofile', $this->kdProfile)
                ->orderByDesc('gen.generik');

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('gen.statusenabled', '=', $r['statusenabled']);
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
            $count = generik::count();
            $PSN =  $request['generik'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new Generik();
                $data->id = $this->SEQUENCE_MASTER(new Generik(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = Generik::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->generik =  $PSN['generik'];
            $data->namaexternal =  $PSN['generik'];
            $data->golongangenerikfk =  $PSN['golongangenerikfk'];
            $data->reportdisplay =  $PSN['generik'];
            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
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

    public function dropdown()
    {
        $res['jenisgenerik'] = JenisGenerik::mine()->get();

        return $this->respond($res);
    }
    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = Generik::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ]
            ];

        } catch (Exception $e) {
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
