<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisPaket;
use App\Models\Master\JenisTransaksi;
use App\Models\Master\Paket;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterPaketCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterPaket(Request $r)
    {
       $data  = DB::table('paket_m as pa')
            ->leftjoin ('jenispaket_m as jp', 'pa.objectjenispaketfk', '=', 'jp.id')
            ->leftjoin ('jenistransaksi_m as jt', 'pa.objectjenistransaksifk', '=', 'jt.id')
            ->select(
                'pa.id',
                'pa.statusenabled',
                'pa.namapaket',
                'pa.harga',
                'pa.reportdisplay',
                'jp.jenispaket',
                'jt.jenistransaksi'
            )
            ->where('pa.kdprofile', $this->kdProfile);
       
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('pa.id', '=',  $r['id']);
            }
            if (isset($r['namapaket']) && $r['namapaket'] != '') {
                $data = $data->where('pa.namapaket', 'ilike', '%' . $r['namapaket'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('pa.statusenabled', '=', $r['statusenabled']);
            }
            if (isset($r['objectjenistransaksifk']) && $r['objectjenistransaksifk'] != '') {
                $data = $data->where('pa.objectjenistransaksifk', '=', $r['objectjenistransaksifk'] );
            }
    
            $data = $data->orderByDesc('pa.namapaket');
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
    public function masterPaketdropdown(Request $r)
    {
        $res['jenispaket'] = JenisPaket::mine()->get();
        $res['jenistransaksi'] = JenisTransaksi::mine()->get();

        return $this->respond($res);
    }

    public function savePaket(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Paket

            $PSN =  $r['paket'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new Paket(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new Paket();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = Paket::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
            }
            $dataPS->namapaket =  $PSN['namapaket'];
            $dataPS->objectjenispaketfk =  $PSN['objectjenispaketfk'];
            $dataPS->objectjenistransaksifk =  $PSN['objectjenistransaksifk'];
            $dataPS->harga=  $PSN['harga'];
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
    public function deletePaket(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Paket::where('id', $r['id'])
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
