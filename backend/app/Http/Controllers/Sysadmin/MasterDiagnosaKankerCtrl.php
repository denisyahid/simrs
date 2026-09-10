<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Ramsey\Uuid\Uuid;
use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Diagnosa;
use App\Models\Master\DiagnosaKanker;
use App\Models\Master\JenisKelamin;
use App\Models\Master\KategoriDiagnosa;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception\InvalidOrderException;

class MasterDiagnosaKankerCtrl extends Controller
{

    use Valet;


    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('diagnosakanker_m as diag')
                    ->leftJoin('jeniskelamin_m as jk','diag.objectjeniskelaminfk','=','jk.id')
                    ->leftJoin('kategorydiagnosa_m as kd','diag.objectkategorydiagnosafk','=','kd.id')
            ->select(
                'diag.id',
                'diag.statusenabled',
                'diag.namaexternal',
                'diag.reportdisplay',
                'diag.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'diag.objectkategorydiagnosafk',
                'kd.kategorydiagnosa',
                'diag.kodeexternal',
                'diag.namadiagnosa',
                'diag.kddiagnosa',
                'diag.qdiagnosa',
            )

            ->where('diag.kdprofile',$this->kdProfile)
            ->orderBy('diag.namadiagnosa');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('diag.id', '=',  $request['id']);
        }
        if (isset($request['diagnosa']) && $request['diagnosa'] != '') {
            $data = $data->where('diag.diagnosa', 'ilike', '%' . $request['diagnosa'] . '%');
        }
        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('diag.statusenabled', '=', $request['statusenabled']);
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
            $count = DiagnosaKanker::count();
            $PSN =  $request['diagnosa'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $diagnosa = new DiagnosaKanker();
                $diagnosa->id = $this->SEQUENCE_MASTER(new DiagnosaKanker(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $diagnosa->statusenabled = true;
                $diagnosa->qdiagnosa = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $diagnosa = DiagnosaKanker::where('id', $PSN['id'])->first();
                $diagnosa->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $diagnosa->id;
            }
            $diagnosa->kdprofile = (int)$this->kdProfile;
            $diagnosa->namadiagnosa =  $PSN['namadiagnosa'];
            $diagnosa->kddiagnosa =  $PSN['kddiagnosa'];
            $diagnosa->kodeexternal =  $PSN['kddiagnosa'];
            $diagnosa->objectjeniskelaminfk =  $PSN['objectjeniskelaminfk'];
            $diagnosa->objectkategorydiagnosafk =  $PSN['objectkategorydiagnosafk'];
            $diagnosa->namaexternal =  $PSN['namadiagnosa'];
            $diagnosa->reportdisplay =  $PSN['namadiagnosa'];
            $diagnosa->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $diagnosa,
                    "as" => '@epic',
                ],
            ];
            return $this->respond($result['result'], $result['statusCode'], $result['message']);

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()

            );
            return $this->respond($result['result'], $result['statusCode'], $result['message']);
        }
    }

    public function dropdownItem()
    {
        $res['jeniskelamin'] = JenisKelamin::mine()->get();
        $res['kategorydiagnosa'] = KategoriDiagnosa::mine()->get();

        return $this->respond($res);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = DiagnosaKanker::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $dataPS,
                    "as" => '@epic',
                ],
            ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'],$result['message']);

    }
}
