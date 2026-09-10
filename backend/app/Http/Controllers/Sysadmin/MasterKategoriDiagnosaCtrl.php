<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\KategoriDiagnosa;
use Illuminate\Http\Request;
use Faker\Factory;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class MasterKategoriDiagnosaCtrl extends Controller
{
    use Valet;


    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('kategorydiagnosa_m')
            ->select(
                'id',
                'statusenabled',
                'namaexternal',
                'reportdisplay',
                'norec',
                'kdprofile',
                'kategorydiagnosa',
                'qkategorydiagnosa',
                'kodeexternal',
                'kdkategorydiagnosa',
                'rinciankodediagnosa',
            )
            ->where('kdprofile',$this->kdProfile)
            ->orderByDesc('kategorydiagnosa');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('id', '=',  $request['id']);
        }
        if (isset($request['kategorydiagnosa']) && $request['kategorydiagnosa'] != '') {
            $data = $data->where('kategorydiagnosa', 'ilike', '%' . $request['kategorydiagnosa'] . '%');
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
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = KategoriDiagnosa::count();
            $PSN =  $request['kategorydiagnosa'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $katDiagnosa = new KategoriDiagnosa();
                $katDiagnosa->id = $this->SEQUENCE_MASTER(new KategoriDiagnosa(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $katDiagnosa->statusenabled = true;
                $katDiagnosa->kdkategorydiagnosa =  $codeUnique;
                $katDiagnosa->qkategorydiagnosa = $codeUnique;
                $katDiagnosa->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $katDiagnosa = KategoriDiagnosa::where('id', $PSN['id'])->first();
                $katDiagnosa->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $katDiagnosa->id;
            }
            $katDiagnosa->kdprofile = (int)$this->kdProfile;
            $katDiagnosa->kategorydiagnosa =  $PSN['kategorydiagnosa'];
            $katDiagnosa->namaexternal =  $PSN['kategorydiagnosa'];
            $katDiagnosa->reportdisplay =  $PSN['kategorydiagnosa'];
            $katDiagnosa->rinciankodediagnosa =  $PSN['rinciankodediagnosa'];
            $katDiagnosa->save();

            DB::commit();
            $result = [
                "status" => 200,
                "messgae" => $transMessage,
                "result" => [
                    "data"  => $katDiagnosa,
                    "as" => 'setiawan@epic',
                ],
            ];
        } catch (Exception $e) {

            DB::rollBack();
            $result = array(
                "status" =>400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {

            $katDiagnosa = KategoriDiagnosa::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $katDiagnosa,
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
}
