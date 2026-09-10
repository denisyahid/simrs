<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokJabatan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterKelompokJabatanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterKelompokJabatan(Request $r)
    {
        $data  = DB::table('kelompokjabatan_m')
            ->select(
                'id',
                'statusenabled',
                'namakelompokjabatan',
                'usiapensiun',
                'namaexternal',
                'reportdisplay',
                'kodekelompok',
            )
            ->where('kdprofile', $this->kdProfile);
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['namakelompokjabatan']) && $r['namakelompokjabatan'] != '') {
                $data = $data->where('namakelompokjabatan', 'ilike', '%' . $r['namakelompokjabatan'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }
            if (isset($r['_total']) && $r['_total'] != '') {
            }

        $data = $data->orderByDesc('namakelompokjabatan');
        $data = $data->get();
        foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'purple';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }
        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveKelompokJabatan(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin

            $PSN =  $r['kelompokjabatan'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new KelompokJabatan(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new KelompokJabatan();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = KelompokJabatan ::where('id', $PSN['id'])->first();
                $id =  $dataPS->id;
                $dataPS->statusenabled =  $PSN['statusenabled'];
            }
            $dataPS->namakelompokjabatan =  $PSN['namakelompokjabatan'];  
            $dataPS->namaexternal =  $PSN['namakelompokjabatan'];
            $dataPS->reportdisplay =  $PSN['reportdisplay'];
            $dataPS->usiapensiun =  $PSN['usiapensiun'];
            $dataPS->kodekelompok =  $id;

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
    public function deleteKelompokJabatan(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = KelompokJabatan::where('id', $r['id'])
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
