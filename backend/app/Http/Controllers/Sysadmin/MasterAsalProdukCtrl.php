<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalProduk;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokProduk;
use App\Models\Master\KelompokTransaksi;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterAsalProdukCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterAsalProduk(Request $r)
    {
        $status = $r->statusenabled;
        $data  = DB::table('asalproduk_m')
            ->select(
                'id',
                'statusenabled',
                'asalproduk',
                'namaexternal',
                'reportdisplay',
                'norec',
                'objectdepartemenfk',
                'objectkelompokprodukfk'
            )
            ->where('kdprofile', $this->kdProfile)
            ->when($status,function($query) use ($status){
                $query->where('statusenabled', $status);
            });
        $data = $data->orderByDesc('asalproduk');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function masterAsalProdukdropdown(Request $r)
    {
        $res['departemen'] = Departemen::mine()->get();
        $res['kelompokproduk'] = KelompokProduk::mine()->get();

        return $this->respond($res);
    }

    public function saveAsalProduk(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Asal Produk

            $PSN =  $r['asalproduk'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new AsalProduk(),'id',$this->kdProfile); //$this->Uuid4();
                $dataPS = new AsalProduk();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = AsalProduk::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->asalproduk =  $PSN['asalproduk'];
            $dataPS->namaexternal =  $PSN['namaexternal'];
            $dataPS->id =  $id;
            $dataPS->reportdisplay =  $PSN['reportdisplay'];
            $dataPS->objectdepartemenfk =  $PSN['objectdepartemenfk'];
            $dataPS->objectkelompokprodukfk =  $PSN['objectkelompokprodukfk'];
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
                "result"  => $e->getMessage() . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteAsalProduk(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = AsalProduk::where('id', $r['id'])
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
