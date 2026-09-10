<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokPasien;
use App\Models\Master\JenisTarif;
use App\Traits\Valet;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;


class MasterKelompokPasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterKelompokPasien(Request $r)
    {
        $data  = DB::table('kelompokpasien_m as pe')
        ->leftjoin('jenistarif_m as jp', 'pe.objectjenistariffk', '=', 'jp.id')
        ->select(
            'pe.id',
            'pe.statusenabled',
            'pe.kelompokpasien',
            'pe.kodeexternal',
            'pe.reportdisplay',
            'jp.jenistarif',
            'pe.objectjenistariffk',

        )
        ->where('pe.kdprofile', $this->kdProfile);

    if (isset($r['id']) && $r['id'] != '') {
        $data = $data->where('pe.id', '=',  $r['id']);
    }

    if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
        $data = $data->where('pe.statusenabled', '=', $r['statusenabled']);
    }

    $data = $data->orderByDesc('pe.kelompokpasien');
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
public function masterKelompokPasiendropdown(Request $r)
{
    $res['jenistarif'] = JenisTarif::mine()->get();

    return $this->respond($res);
}

     public function saveKelompokPasien(Request $r)
    {
        DB::beginTransaction();
        try {
            $PSN =  $r['kelompokpasien'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new KelompokPasien(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new KelompokPasien();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $dataPS = KelompokPasien::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
                $transMessage = "Proses Update Data Berhasil";
            }
            $dataPS->kelompokpasien =  $PSN['kelompokpasien'];
            $dataPS->reportdisplay =  $PSN['kelompokpasien'];
            $dataPS->namaexternal =  $PSN['kelompokpasien'];
            $dataPS->objectjenistariffk =  $PSN['objectjenistariffk'];
            $dataPS->save();
            DB::commit();

            $result = [
                'status' => 201,
                'message' => $transMessage,
                'result' => $dataPS,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => "Simpan Gagal !",
                'result' => $e->getMessage(),
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);

    }

    public function deleteKelompokPasien(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = KelompokPasien::where('id', $r['id'])
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
