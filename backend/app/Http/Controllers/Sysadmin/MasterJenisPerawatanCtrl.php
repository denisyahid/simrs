<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\JenisPerawatan;
use App\Models\Master\Kamar;
use Exception;

class MasterJenisPerawatanCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('jenisperawatan_m')
            ->select(
                'id',
                'qjenisperawatan',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'kodeexternal',
                'jenisperawatan',
            )
            ->where('kdprofile', $this->kdProfile)
            ->orderByDesc('jenisperawatan');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['jenisperawatan']) && $r['jenisperawatan'] != '') {
            $data = $data->where('jenisperawatan', 'ilike', '%' . $r['jenisperawatan'] . '%');
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
            $count = JenisPerawatan::count();
            $PSN =  $request['jenisperawatan'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new JenisPerawatan();
                $data->id = $this->SEQUENCE_MASTER(new JenisPerawatan(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $data->qjenisperawatan = $codeUnique;
                $data->kdjenisperawatan = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = JenisPerawatan::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->jenisperawatan =  $PSN['jenisperawatan'];
            $data->namaexternal =  $PSN['jenisperawatan'];
            $data->reportdisplay =  $PSN['jenisperawatan'];
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


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = JenisPerawatan::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
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
