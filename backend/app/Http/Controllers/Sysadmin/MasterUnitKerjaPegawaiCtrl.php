<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\UnitKerjaPegawai;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class MasterUnitKerjaPegawaiCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('unitkerjapegawai_m as ukp')
            ->select('*')
            ->where('ukp.kdprofile', $this->kdProfile);

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('ukp.statusenabled', '=', $r['statusenabled']);
        }


        $data = $data->get();

        foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'primary';
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
            $count = UnitKerjaPegawai::count();
            $PSN =  $request['unitkerjapegawai'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new UnitKerjaPegawai();
                $data->id = $this->SEQUENCE_MASTER(new UnitKerjaPegawai(), 'id', $this->kdProfile); ///(string)Uuid::uuid4();
                $data->norec = $data->id;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = UnitKerjaPegawai::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->name =  $PSN['name'];
            $data->namaexternal =  $PSN['name'];
            $data->reportdisplay =  $PSN['name'];
            $data->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = UnitKerjaPegawai::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                ]
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => [
                    "messageError" => $e->getMessage(),
                ]
            ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
}
