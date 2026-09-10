<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\RouteFarmasi;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterRouteFarmasiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterRouteFarmasi(Request $r)
    {
        $data  = DB::table('routefarmasi')
            ->select(
                'id',
                'statusenabled',
                'name',
                'namaexternal',
                'reportdisplay',
                'norec',
            )
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);

        $data = $data->orderByDesc('id');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveRouteFarmasi(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Status Perkawinan

            $PSN =  $r['name'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new RouteFarmasi(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new RouteFarmasi();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = RouteFarmasi::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->name =  $PSN['name'];
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
    public function deleteRouteFarmasi(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = RouteFarmasi::where('id', $r['id'])
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
