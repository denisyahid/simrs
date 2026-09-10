<?php

namespace App\Http\Controllers\Sysadmin;

use Ramsey\Uuid\Uuid;
use Faker\Factory;
use App\Traits\Valet;
use App\Http\Controllers\Controller;
use App\Models\Master\Diagnosa;
use App\Models\Master\JadwalPraktek;
use App\Models\Master\KelompokShiftKerja;
use App\Models\Master\ShiftKerja;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterShiftKerjaCtrl extends Controller
{
    use Valet;


    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('shiftkerja_m as sk')
                    ->leftJoin('sdm_kelompokshift_m as ks','sk.objectkelompokshiftfk','=','ks.id')
                    ->leftJoin('jadwalpraktek_m as jp','sk.objectjadwalpraktekfk','=','jp.id')
            ->select(
                'sk.id',
                'sk.statusenabled',
                'sk.namaexternal',
                'sk.namashift',
                'sk.reportdisplay',
                'sk.jambreakakhir',
                'sk.jambreakawal',
                'sk.jammasuk',
                'sk.jampulang',
                'sk.waktuistirahat',
                'ks.kelompokshiftkerja',
                'sk.objectkelompokshiftfk',
                'jp.waktupraktek',
                'sk.objectjadwalpraktekfk',
                'sk.kodeexternal',
                'sk.flagketidakhadiran',
                'sk.operatorfactorrate',
                'sk.factorrate',
                'sk.kdshift',
                'sk.qshift',
            )

            ->where('sk.kdprofile',$this->kdProfile)
            ->orderBy('sk.namashift');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('sk.id', '=',  $request['id']);
        }
        if (isset($request['namashift']) && $request['namashift'] != '') {
            $data = $data->where('sk.namashift', 'ilike', '%' . $request['namashift'] . '%');
        }
        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('sk.statusenabled', '=', $request['statusenabled']);
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
        return $this->respond($res);
    }


    public function store(Request $request){
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = ShiftKerja::count();
            $shiftKerja =  $request['shiftkerja'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($shiftKerja['id'] == '') {
                $dataSK = new ShiftKerja();
                $dataSK->id = $this->SEQUENCE_MASTER(new ShiftKerja(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $dataSK->statusenabled = true;
                $dataSK->kdshift =  $codeUnique;
                $dataSK->qshift = $codeUnique;
                $dataSK->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $dataSK->flagketidakhadiran =  true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $dataSK = ShiftKerja::where('id', $shiftKerja['id'])->first();
                $dataSK->statusenabled = $shiftKerja['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $dataSK->id;
            }
            $dataSK->kdprofile = (int)$this->kdProfile;
            $dataSK->namashift =  $shiftKerja['namashift'];
            $dataSK->objectkelompokshiftfk =  $shiftKerja['objectkelompokshiftfk'];
            $dataSK->objectjadwalpraktekfk =  $shiftKerja['objectjadwalpraktekfk'];
            $dataSK->jammasuk =  $shiftKerja['jammasuk'];
            $dataSK->jampulang =  $shiftKerja['jampulang'];
            $dataSK->waktuistirahat =  $shiftKerja['waktuistirahat'];
            $dataSK->namaexternal =  $shiftKerja['namashift'];
            $dataSK->reportdisplay =  $shiftKerja['namashift'];
            $dataSK->operatorfactorrate =  $shiftKerja['operatorfactorrate'];
            $dataSK->factorrate =  $shiftKerja['factorrate'];
            $dataSK->save();

            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $dataSK,
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
        $res['waktupraktek'] = JadwalPraktek::mine()->get();
        $res['kelompokshiftkerja'] = KelompokShiftKerja::mine()->get();

        return $this->respond($res);
    }

    public function delete(Request $r)
    {

        DB::beginTransaction();
        try {

            $dataSK = ShiftKerja::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => array(
                    "data"  => $dataSK,
                    "as" => 'setiawan@epic',
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
