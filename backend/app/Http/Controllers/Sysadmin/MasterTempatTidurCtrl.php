<?php

namespace App\Http\Controllers\Sysadmin;
use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Kamar;
use App\Models\Master\StatusBed;
use App\Models\Master\TempatTidur;
use Exception;

class MasterTempatTidurCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
         $data = DB::table('tempattidur_m as tt')
                ->Join('kamar_m as kmr', 'tt.objectkamarfk', '=', 'kmr.id')
                ->leftJoin('statusbed_m as sb', 'tt.objectstatusbedfk', '=', 'sb.id')
                ->select(
                    'tt.id',
                    'tt.statusenabled',
                    'tt.namaexternal',
                    'tt.reportdisplay',
                    'tt.kodeexternal',
                    'tt.objectkamarfk',
                    'kmr.namakamar',
                    'tt.objectstatusbedfk',
                    'sb.statusbed',
                    'tt.objectruangperawatankemenkesfk',
                    'tt.namaexternal',
                    'tt.nomorbed',
                )
                ->where('tt.kdprofile',$this->kdProfile)
                ->orderByDesc('tt.reportdisplay');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('tt.id', '=',  $r['id']);
        }
        if (isset($r['reportdisplay']) && $r['reportdisplay'] != '') {
            $data = $data->where('tt.reportdisplay', 'ilike', '%' . $r['reportdisplay'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('tt.statusenabled', '=', $r['statusenabled']);
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
            $faker = Factory::create();
            $count = TempatTidur::count();
            $PSN =  $request['tempattidur'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $data = new TempatTidur();
                $data->id = $this->SEQUENCE_MASTER(new TempatTidur(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = TempatTidur::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->reportdisplay =  $PSN['reportdisplay'];
            $data->objectkamarfk =  $PSN['objectkamarfk'];
            $data->objectstatusbedfk =  $PSN['objectstatusbedfk'];
            $data->nomorbed =  $PSN['nomorbed'];
            $data->namaexternal =  $PSN['reportdisplay'];
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


    public function dropdownItem()
    {
        $res['namakamar'] = Kamar::mine()->get();
        $res['statusbed'] = StatusBed::mine()->get();

        return $this->respond($res);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = TempatTidur::where('id', $r['id'])->update(['statusenabled' => false]);
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
