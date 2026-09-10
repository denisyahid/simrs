<?php

namespace App\Http\Controllers\Sysadmin;
use Ramsey\Uuid\Uuid;
use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\KelompokShiftKerja;
use Exception;
use Illuminate\Http\Request;

class MasterKelompokShiftCtrl extends Controller
{
    use Valet;


    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('sdm_kelompokshift_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'factorrate',
                'reportdisplay',
                'kodeexternal',
                'kelompokshiftkerja',
                'kelompokshiftkerja',
                'qkelompokshift',
                'kodesirs',
                'operatorfactorrate',
            )
            ->where('kdprofile',$this->kdProfile)
            ->orderByDesc('kelompokshiftkerja');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('id', '=',  $request['id']);
        }
        if (isset($request['kelompokshift']) && $request['kelompokshift'] != '') {
            $data = $data->where('kelompokshiftkerja', 'ilike', '%' . $request['kelompokshiftkerja'] . '%');
        }
        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('statusenabled', '=', $request['statusenabled']);
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
            'count' =>$data->count()
        ];
        // return response()->json($res);
        return $this->respond($res);
    }


    public function store(Request $request){
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = KelompokShiftKerja::count();
            $PSN =  $request['kelompokshift'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $data = new KelompokShiftKerja();
                $data->id = $this->SEQUENCE_MASTER(new KelompokShiftKerja(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->statusenabled = true;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->kdkelompokshiftkerja =  $codeUnique;
                $data->qkelompokshift = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = KelompokShiftKerja::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->factorrate =  $PSN['factorrate'];
            $data->kelompokshiftkerja =  $PSN['kelompokshiftkerja'];
            $data->namaexternal =  $PSN['kelompokshiftkerja'];
            $data->operatorfactorrate =  $PSN['operatorfactorrate'];
            $data->reportdisplay =  $PSN['kelompokshiftkerja'];
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
            $dataPS = KelompokShiftKerja::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );

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
}
