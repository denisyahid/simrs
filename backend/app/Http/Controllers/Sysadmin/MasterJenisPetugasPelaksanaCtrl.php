<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisPetugasPelaksana;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterJenisPetugasPelaksanaCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterJenisPetugasPelaksana(Request $r)
    {
        $data  = DB::table('jenispetugaspelaksana_m')
            ->select(
                'id',
                'statusenabled',
                'jenispetugaspe',
                'reportdisplay',
                'kdjenispetugaspe'
            )
            ->where('kdprofile', $this->kdProfile);
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['jenispetugaspe']) && $r['jenispetugaspe'] != '') {
                $data = $data->where('jenispetugaspe', 'ilike', '%' . $r['jenispetugaspe'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }
            if (isset($r['_total']) && $r['_total'] != '') {
            }
            if (isset($r['offset']) && $r['offset'] != '') {
                $data = $data->offset($r['offset']);
            }
     

        $data = $data->orderByDesc('jenispetugaspe');
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

    public function saveJenisPetugasPelaksana(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Petugas Pelaksana

            $PSN =  $r['jenispetugaspe'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new JenisPetugasPelaksana(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new JenisPetugasPelaksana();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = JenisPetugasPelaksana ::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->jenispetugaspe =  $PSN['jenispetugaspe'];
            $dataPS->statusenabled =  $PSN['statusenabled'];
            $dataPS->namaexternal =  $PSN['jenispetugaspe'];
            $dataPS->reportdisplay =  $PSN['jenispetugaspe'];
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
    public function deleteJenisPetugasPelaksana(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = JenisPetugasPelaksana::where('id', $r['id'])
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
