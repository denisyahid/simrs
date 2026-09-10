<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Bku;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokProduk;
use App\Models\Master\KelompokTransaksi;
use App\Models\Master\MapBkuToKelompokTransaksi;
use App\Models\Master\MapRuanganToProduk;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapBkutoKelompokTransaksiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterMapBkU(Request $r)
    {
        $data  = DB::table('mapbkutokelompoktransaksi_m as mp')
        ->join('kelompoktransaksi_m as pr', 'mp.kelompoktransaksifk', '=', 'pr.id')
        ->join('bku_m as ru', 'mp.idbku', '=', 'ru.id')
        ->select(
          'mp.kelompoktransaksifk',
          'pr.id',
          'pr.kelompoktransaksi',
          'mp.idbku',
          'ru.bku'
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mp.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['idbku']) && $r['idbku'] != '') {
            $data = $data->where('mp.idbku', '=', $r['idbku'] );
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['kel']) && $r['kel'] != '') {
            $data = $data->where('pr.kel', 'ilike', '%'.$r['kel'].'%');
        }
        if (isset($r['rows']) && $r['rows'] != '') {
            $data = $data->limit($r['rows']);
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMapBKU(Request $r)
    {
        DB::beginTransaction();
        try {
            $delete = MapBkuToKelompokTransaksi::where('idbku', $r['idbku'])
            ->update(['statusenabled'=> false]);
            foreach($r['detail'] as $item){
                $dataPS = new MapBkuToKelompokTransaksi();
                $dataPS->id = $this->SEQUENCE_MASTER(new MapBkuToKelompokTransaksi(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->idbku =  $r['idbku'];
                $dataPS->kelompoktransaksifk = $item['produkfk'];
                $dataPS->save();
            }

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
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function deleteMapRuanganToProduk(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = MapRuanganToProduk::where('id', $r['id'])
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
    public function dropDownBKU (Request $r)
    {
        $res['bku'] = Bku::mine()->get();
        $res['kelompoktransaksi'] = KelompokTransaksi::mine()->get();
       
        return $this->respond($res);
    }




}

