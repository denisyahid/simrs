<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\JenisGenerik;
use App\Models\Master\Kamar;

class MasterJenisGenerikCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('rm_jenisgenerik_m')
            ->select(
                'id',
                'qjenisgenerik',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'jenisgenerik',
            )
            ->where('kdprofile', $this->kdProfile)
            ->orderByDesc('jenisgenerik');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['jenisgenerik']) && $r['jenisgenerik'] != '') {
            $data = $data->where('jenisgenerik', 'ilike', '%' . $r['jenisgenerik'] . '%');
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
        // return response()->json($res);
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = JenisGenerik::count();
            $PSN =  $request['jenisgenerik'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new JenisGenerik();
                $data->id = $this->SEQUENCE_MASTER(new JenisGenerik(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $data->qjenisgenerik = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = JenisGenerik::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->jenisgenerik =  $PSN['jenisgenerik'];
            $data->namaexternal =  $PSN['jenisgenerik'];
            $data->reportdisplay =  $PSN['jenisgenerik'];
            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = JenisGenerik::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ]
            ];
            $transMessage = "Proses Hapus Data Berhasil";
        } catch (\Exception $e) {
            DB::rollBack();
            $transMessage = "Something Went Wrong";
            $result = [
                "status" => 400,
                "result"  =>null
            ];
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

}
