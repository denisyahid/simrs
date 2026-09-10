<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalAnggaran;
use App\Models\Master\JenisAlamat;
use Exception;
use Mockery\Exception\InvalidOrderException;

class MasterAsalAnggaranCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('asalanggaran_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'asalanggaran',
                'keterangan',
                'kdasalanggaran',
                'qasalanggaran',
            )
            ->where('kdprofile', $this->kdProfile)
            ->orderByDesc('asalanggaran');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['asalanggaran']) && $r['asalanggaran'] != '') {
            $data = $data->where('asalanggaran', 'ilike', '%' . $r['asalanggaran'] . '%');
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
            $count = AsalAnggaran::count();
            $PSN =  $r['asalanggaran'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new AsalAnggaran();
                $data->id = $this->SEQUENCE_MASTER(new AsalAnggaran, 'id', $this->kdProfile); //(string)Uuid::uuid4();
                $data->kdasalanggaran =  $codeUnique;
                $data->qasalanggaran = $codeUnique;
                $data->statusenabled = true;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = AsalAnggaran::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->asalanggaran =  $PSN['asalanggaran'];
            $data->keterangan =  $PSN['keterangan'];
            $data->namaexternal =  $PSN['asalanggaran'];
            $data->reportdisplay =  $PSN['asalanggaran'];
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
            $dataPS = AsalAnggaran::where('id', $r['id'])->update(['statusenabled' => false]);
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
                "result"  => $e->getLine() . ' ' . $e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
