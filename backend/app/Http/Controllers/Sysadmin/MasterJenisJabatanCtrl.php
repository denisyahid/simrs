<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisJabatan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterJenisJabatanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterJenisJabatan(Request $r)
    {
        $data  = DB::table('jenisjabatan_m')
            ->select(
                'id',
                'statusenabled',
                'jenisjabatan',
                'namaexternal',
                'reportdisplay',
                'norec',
            )
            ->where('kdprofile', $this->kdProfile);
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('pe.id', '=',  $r['id']);
            }
            if (isset($r['jenisjabatan']) && $r['jenisjabatan'] != '') {
                $data = $data->where('jenisjabatan', 'ilike', '%' . $r['jenisjabatan'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '' ) {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }
            if (isset($r['_total']) && $r['_total'] != '') {
            }

        $data = $data->orderByDesc('jenisjabatan');
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

    public function saveJenisJabatan(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin

            $PSN =  $r['jenisjabatan'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new JenisJabatan(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new JenisJabatan();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = JenisJabatan ::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
            }
            $dataPS->jenisjabatan =  $PSN['jenisjabatan'];
            $dataPS->namaexternal =  $PSN['jenisjabatan'];
            $dataPS->reportdisplay =  $PSN['jenisjabatan'];
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
    public function deleteJenisJabatan(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = JenisJabatan::where('id', $r['id'])
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
