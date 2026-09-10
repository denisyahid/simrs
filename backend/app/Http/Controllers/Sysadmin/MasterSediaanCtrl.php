<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Sediaan;

class MasterSediaanCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('rm_sediaan_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'name',
            )
            ->where('kdprofile', $this->kdProfile)
            ->orderByDesc('name');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['name']) && $r['name'] != '') {
            $data = $data->where('name', 'ilike', '%' . $r['name'] . '%');
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
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = Sediaan::count();
            $PSN =  $request['persediaan'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new Sediaan();
                $data->id = $this->SEQUENCE_MASTER(new Sediaan(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = Sediaan::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->name =  $PSN['name'];
            $data->namaexternal =  $PSN['name'];
            $data->reportdisplay =  $PSN['name'];
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
            $data = Sediaan::where('id', $r['id'])
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
