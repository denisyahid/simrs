<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\JenisTarif;
use App\Models\Master\Kamar;
use Exception;
use Mockery\Exception\InvalidOrderException;

class MasterJenisTarifCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('jenistarif_m')
            ->select(
                'id',
                'qjenistarif',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'jenistarif',
            )
            ->where('kdprofile', $this->kdProfile)
            ->orderByDesc('jenistarif');

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
            $count = JenisTarif::count();
            $PSN =  $request['jenistarif'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new JenisTarif();
                $data->id = $this->SEQUENCE_MASTER(new JenisTarif(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $data->kdjenistarif =  $codeUnique;
                $data->qjenistarif = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = JenisTarif::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->jenistarif =  $PSN['jenistarif'];
            $data->namaexternal =  $PSN['jenistarif'];
            $data->reportdisplay =  $PSN['jenistarif'];
            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
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
            $data = JenisTarif::where('id', $r['id'])
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
                "result"  => $e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

}
