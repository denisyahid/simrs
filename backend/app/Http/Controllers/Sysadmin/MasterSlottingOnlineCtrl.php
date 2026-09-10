<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\SlottingOnline;
use App\Models\Master\JenisSlottingOnline;
use App\Models\Master\KelompokSlottingOnline;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterSlottingOnlineCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterSlottingOnline(Request $r)
    {
        $data  = DB::table('slottingonline_m as pe')
        ->join('ruangan_m as jp', 'pe.objectruanganfk', '=', 'jp.id')
        ->select(
            'pe.id',
            'pe.statusenabled',
            'pe.objectruanganfk',
            'jp.namaruangan',
            'pe.jambuka',
            'pe.jamtutup',
            'pe.quota',
            'pe.norec'

        )
        ->where('pe.kdprofile', $this->kdProfile);
    if (isset($r['id']) && $r['id'] != '') {
        $data = $data->where('pe.id', '=',  $r['id']);
    }
    if (isset($r['namaruangan']) && $r['namaruangan'] != '') {
        $data = $data->where('jp.namaruangan', 'ilike', '%' . $r['namaruangan'] . '%');
    }
    if (isset($r['statusenabled']) && $r['statusenabled'] != '' && $r['statusenabled'] == 'true') {
        $data = $data->where('pe.statusenabled', '=', $r['statusenabled']);
    }
    // if (isset($r['_total']) && $r['_total'] != '') {
    // }
    // if (isset($r['offset']) && $r['offset'] != '') {
    //     $data = $data->offset($r['offset']);
    // }
    // if (isset($r['limit']) && $r['limit'] != '') {
    //     $data = $data->limit($r['limit']);
    // }

    $data = $data->orderByDesc('pe.id');
    $data = $data->get();

    $res['data'] = $data;
    return $this->respond($res);
}

public function masterSlottingOnlinedropdown(Request $r)
{
    $res['namaruangan'] = Ruangan::mine()->get();

    return $this->respond($res);
}

    public function saveSlottingOnline(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Slotting Online

            $PSN =  $r['slottingonline'];
            if ($PSN['id'] == '') {
                $id =$this->SEQUENCE_MASTER(new SlottingOnline(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new SlottingOnline();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = SlottingOnline::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->objectruanganfk =  $PSN['objectruanganfk'];
            $dataPS->jambuka =  $PSN['jambuka'];
            $dataPS->jamtutup =  $PSN['jamtutup'];
            $dataPS->quota =  $PSN['quota'];
            $dataPS->statusenabled =  $PSN['statusenabled'];
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
    public function deleteSlottingOnline(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = SlottingOnline::where('id', $r['id'])
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
