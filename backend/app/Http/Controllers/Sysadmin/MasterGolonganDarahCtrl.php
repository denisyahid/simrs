<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\GolonganDarah;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterGolonganDarahCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterGolonganDarah(Request $r)
    {
        $data  = DB::table('golongandarah_m')
            ->select(
                'id',
                'statusenabled',
                'golongandarah',
                'namaexternal',
                'reportdisplay',
                'norec',
            )
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);

        $data = $data->orderByDesc('golongandarah');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveGolonganDarah(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Golongan Darah

            $PSN =  $r['golongandarah'];
            if ($PSN['id'] == '') { 
                $id = $this->SEQUENCE_MASTER(new GolonganDarah(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new GolonganDarah();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = GolonganDarah::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->golongandarah =  $PSN['golongandarah'];
            $dataPS->namaexternal =  $PSN['namaexternal'];
            $dataPS->reportdisplay =  $PSN['reportdisplay'];
            $dataPS->norec =  $PSN['norec'];
            $dataPS->save();
            //endregion

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteGolonganDarah(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = GolonganDarah::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
