<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Signa;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterSignaCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterSigna(Request $r)
    {
        $data  = DB::table('stigma')
            ->select(
                'id',
                'statusenabled',
                'jumlahpakai',
                'signa',
                'namaexternal',
                'reportdisplay'
            )
            ->where('kdprofile', $this->kdProfile);

            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['signa']) && $r['signa'] != '') {
                $data = $data->where('signa', 'ilike', '%' . $r['signa'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }


        $data = $data->orderByDesc('signa');
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

    public function saveSigna(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Signa

            $PSN =  $r['signa'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new Signa(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new Signa();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = Signa::where('id', $PSN['id'])->first();
                $dataPS->statusenabled = $PSN['statusenabled'];
                $id =  $dataPS->id;
            }
            $dataPS->signa =  $PSN['signa'];
            $dataPS->jumlahpakai =  $PSN['jumlahpakai'];
            $dataPS->reportdisplay =  $PSN['signa'];
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
    public function deleteSigna(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Signa::where('id', $r['id'])
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
