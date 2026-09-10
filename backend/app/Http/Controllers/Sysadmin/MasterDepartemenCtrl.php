<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Agama;
use App\Models\Master\Departemen;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDepartemenCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterDepartemen(Request $r)
    {
        $data  = DB::table('departemen_m')
            ->select(
                'id',
                'statusenabled',
                'namadepartemen',
                'namaexternal',
                'reportdisplay',
                'norec',
                'ihs_id'
            )
            ->where('kdprofile', $this->kdProfile);
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['namadepartemen']) && $r['namadepartemen'] != '') {
                $data = $data->where('namadepartemen', 'ilike', '%' . $r['namadepartemen'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }
            if (isset($r['ihs_id']) && $r['ihs_id'] != '') {
                $data = $data->whereNotNull('ihs_id');
            }

        $data = $data->orderByDesc('id', 'desc');
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

    public function masterDepartemendropdown(Request $r){

    }
    public function saveDepartemen(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Departemen

            $PSN =  $r['departemen'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new Departemen(),'id',$this->kdProfile);// $this->Uuid4();
                $dataPS = new Departemen();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = Departemen::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
            }
            $dataPS->namadepartemen =  $PSN['namadepartemen'];
            $dataPS->reportdisplay =  $PSN['reportdisplay'];
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
    public function deleteDepartemen(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Departemen::where('id', $r['id'])
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
    public function updatesaveDepartemen(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Departemen::where('id', $r['id'])
                ->update([
                    'ihs_id' => $r['ihs_id']
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
            $transMessage = "Update Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
