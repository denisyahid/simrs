<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisLaporan;
use App\Models\Master\Signa;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterJenisLaporanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterJenisLaporan(Request $r)
    {
        $data  = DB::table('jenislaporan_m')
            ->select('*')
            ->where('kdprofile', $this->kdProfile);

            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }

        $data = $data->orderBy('jenislaporan');
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

    public function saveJenisLaporan(Request $r)
    {
        DB::beginTransaction();
        try {

            if ($r['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new JenisLaporan(),'id',$this->kdProfile);
                $dataPS = new JenisLaporan();
                $dataPS->id = $id;
                $dataPS->kdprofile = $this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = JenisLaporan::where('id', $r['id'])->first();
                $dataPS->statusenabled = $r['statusenabled'];
                $id = $dataPS->id;
            }
            $dataPS->jenislaporan =  $r['jenislaporan'];
            $dataPS->namaexternal =  $r['namaexternal'];
            $dataPS->arrproduk =  $r['arrproduk'];
            $dataPS->sort =  $r['sort'];
            $dataPS->kdexternal =  $r['kdexternal'];
            $dataPS->save();

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Data Berhasil",
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );

        } catch (\Exception $e) {
            DB::rollBack();

            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
    public function deleteJenisLaporan(Request $r)
    {
        DB::beginTransaction();
        try {

            JenisLaporan::where('id', $r['id'])->update(['statusenabled' => false]);

            DB::commit();

            $result = array(
                "status" => 200,
                "message" => "Hapus Data Berhasil",
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage(),
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
}
