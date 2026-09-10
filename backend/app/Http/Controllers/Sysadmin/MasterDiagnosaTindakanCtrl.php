<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use App\Models\Master\StatusKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\DiagnosaTindakan;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\KategoriDiagnosa;

class MasterDiagnosaTindakanCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('diagnosatindakan_m as dt')
            ->join('kategorydiagnosa_m as kd', 'dt.objectkategorydiagnosafk', '=', 'kd.id')
            ->select(
                'dt.id',
                'dt.qdiagnosatindakan',
                'dt.statusenabled',
                'dt.namaexternal',
                'dt.reportdisplay',
                'dt.kodeexternal',
                'dt.kddiagnosatindakan',
                'dt.objectkategorydiagnosafk',
                'kd.kategorydiagnosa',
                'dt.keterangan',
                'dt.namadiagnosatindakan',
            )
            ->where('dt.kdprofile', $this->kdProfile)
            ->orderByDesc('dt.diagnosatindakan');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('dt.id', '=',  $r['id']);
        }
        if (isset($r['namadiagnosatindakan']) && $r['namadiagnosatindakan'] != '') {
            $data = $data->where('dt.diagnosatindakan', 'ilike', '%' . $r['namadiagnosatindakan'] . '%');
        }
        if (isset($r['objectkategorydiagnosafk']) && $r['objectkategorydiagnosafk'] != '') {
            $data = $data->where('dt.objectkategorydiagnosafk', 'ilike', '%' . $r['objectkategorydiagnosafk'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('dt.statusenabled', '=', $r['statusenabled']);
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
            $count = DiagnosaTindakan::count();
            $PSN =  $request['diagnosatindakan'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new DiagnosaTindakan();
                $data->id = $this->SEQUENCE_MASTER(new DiagnosaTindakan(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $data->kddiagnosatindakan =  $codeUnique;
                $data->qdiagnosatindakan = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = DiagnosaTindakan::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->namadiagnosatindakan =  $PSN['namadiagnosatindakan'];
            $data->objectkategorydiagnosafk =  $PSN['objectkategorydiagnosafk'];
            $data->norec =  $PSN['norec'];
            $data->keterangan =  $PSN['keterangan'];
            $data->namaexternal =  $PSN['namadiagnosatindakan'];
            $data->reportdisplay =  $PSN['namadiagnosatindakan'];
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
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function kategoriDiagnosa()
    {
        $res['kategorydiagnosa'] = KategoriDiagnosa::mine()->get();

        return $this->respond($res);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = DiagnosaTindakan::where('id', $r['id'])->update(['statusenabled' => false]);
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
                "result"  => $e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
