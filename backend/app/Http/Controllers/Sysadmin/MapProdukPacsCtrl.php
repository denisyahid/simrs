<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MapProdukPacsCtrl extends Controller
{
    use Valet;

    public function getProdukRad (Request $r)
    {
        $data  = DB::table('mapruangantoproduk_m as mp')
        ->join('produk_m as pr', 'mp.objectprodukfk', '=', 'pr.id')
        ->join('ruangan_m as ru', 'mp.objectruanganfk', '=', 'ru.id')
        ->select(
            'mp.objectprodukfk',
            'pr.id',
            'mp.objectruanganfk',
            'pr.namaproduk',
            'ru.namaruangan',
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile)
            ->where('mp.objectruanganfk', $this->settingFix('idRuanganRadiologi'));

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mp.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=',  $r['statusenabled']);
        }
 
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $data = $data->where('pr.namaproduk', 'ilike', '%'.$r['namaproduk'].'%');
        }
  
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMapPacs(Request $r)
    {
        DB::beginTransaction();
        try {
            foreach($r['detail'] as $item){
                Produk::where('id', $item['produkfk'])->update([
                    'modality' => $r['modality'],
                ]);
            }

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
                    "data"  => 'cek',
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

    public function getMapping (Request $r) 
    {
        $data  = DB::table('produk_m as mp')
        ->select(
            'mp.id',
            'mp.namaproduk',
            'mp.modality',      
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile);

        if (isset($r['modality']) && $r['modality'] != '') {
            $data = $data->where('mp.modality', '=',  $r['modality']);
        }

        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $data = $data->where('pr.namaproduk', 'ilike', '%'.$r['namaproduk'].'%');
        }
        if (isset($r['rows']) && $r['rows'] != '') {
            $data = $data->limit($r['rows']);
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

}