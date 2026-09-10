<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalRujukan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterAsalRujukanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterAsalRujukan(Request $r)
    {
        $data  = DB::table('asalrujukan_m')
            ->select(
                'id',
                'statusenabled',
                'asalrujukan',
                'namaexternal',
                'reportdisplay',
                'kdasalrujukan'
            )
            ->where('kdprofile', $this->kdProfile);
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['asalrujukan']) && $r['asalrujukan'] != '') {
                $data = $data->where('asalrujukan', 'ilike', '%' . $r['asalrujukan'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }
            if (isset($r['_total']) && $r['_total'] != '') {
            }
            if (isset($r['offset']) && $r['offset'] != '') {
                $data = $data->offset($r['offset']);
            }

        $data = $data->orderByDesc('asalrujukan');
        $data = $data->get();

        foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'primary';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }

        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveAsalRujukan(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Asal Rujukan

            $PSN =  $r['asalrujukan'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new AsalRujukan,'id',$this->kdProfile);
                $dataPS = new AsalRujukan();
                $dataPS->id = $id;
                $dataPS->kdprofile = $this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = AsalRujukan::where('id', $PSN['id'])
                    // ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->asalrujukan =  $PSN['asalrujukan'];
            $dataPS->namaexternal =  $PSN['asalrujukan'];
            $dataPS->reportdisplay =  $PSN['asalrujukan'];
            $dataPS->statusenabled=  $PSN['statusenabled'];
            $dataPS->kdasalrujukan= $PSN['kdasalrujukan'];
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
                "result"  => $e->getMessage(),

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteAsalRujukan(Request $r)
    {
        DB::beginTransaction();
        try {

            // $dataPS = AsalRujukan::where('id', $r['id'])
            //     ->update([
            //         'statusenabled' => false
            //     ]);
            $dataPS = AsalRujukan::where('id', $r['id'])
                ->delete();

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
