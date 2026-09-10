<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\DetailKategoryPegawai;
use App\Models\Master\KategoryPegawai;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDetailKategoryPegawaiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterDetailKategoriPegawai (Request $r)
    {
        $data  = DB::table('detailkategorypegawai_m as ru')
            ->join('kategorypegawai_m as dp', 'ru.objectkategorypegawaifk', '=', 'dp.id')
            ->select(
                'ru.id',
                'ru.statusenabled',
                'ru.detailkategorypegawai',
                'dp.kategorypegawai',
                'ru.objectkategorypegawaifk',
                'ru.kddetailkategorypegawai',
                'ru.reportdisplay',
            )
            ->where('ru.kdprofile', $this->kdProfile);
  
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('ru.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('ru.statusenabled', '=', $r['statusenabled']);
        }
        if (isset($r['detailkategorypegawai']) && $r['detailkategorypegawai'] != '') {
            $data = $data->where('ru.detailkategorypegawai', 'ilike', '%' . $r['detailkategorypegawai'] . '%');
        }
        if (isset($r['kategorypegawai']) && $r['kategorypegawai'] != '') {
            $data = $data->where('dp.id', '=', $r['kategorypegawai']);
        }

        $data = $data->orderByDesc('ru.detailkategorypegawai');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function masterDetailKategoryPegawaidropdown(Request $r)
    {
        $res['kategorypegawai'] = KategoryPegawai::mine()->get();

        return $this->respond($res);
    }
    public function saveDetailKategoryPegawai(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Detail Kategory Pegawai

            $PSN =  $r['detailkategorypegawai'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new DetailKategoryPegawai(),'id',$this->kdProfile);// $this->Uuid4();
                $dataPS = new DetailKategoryPegawai();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = false;
            } else {
                $dataPS = DetailKategoryPegawai::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->detailkategorypegawai =  $PSN['detailkategorypegawai'];
            $dataPS->objectkategorypegawaifk =  $PSN['objectkategorypegawaifk'];
            $dataPS->kddetailkategorypegawai = $PSN['kddetailkategorypegawai'];
            $dataPS->namaexternal =  $PSN['detailkategorypegawai'];
            $dataPS->reportdisplay =  $PSN['detailkategorypegawai'];
            $dataPS->statusenabled =  $PSN['statusenabled'];
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
    public function deleteDetailKategoryPegawai(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = DetailKategoryPegawai::where('id', $r['id'])
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
