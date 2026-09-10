<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokProduk;
use App\Models\Master\MapRuanganToProduk;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMapRuanganToProdukCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterMapRuanganToProduk(Request $r)
    {
        $data  = DB::table('mapruangantoproduk_m as mp')
        ->join('produk_m as pr', 'mp.objectprodukfk', '=', 'pr.id')
        ->join('ruangan_m as ru', 'mp.objectruanganfk', '=', 'ru.id')
        ->select(
            'mp.id',
            'mp.kodeexternal',
            'mp.namaexternal',
            'mp.norec',
            'mp.reportdisplay',
            'mp.objectprodukfk',
            'mp.objectruanganfk',
            'mp.status',
            'pr.namaproduk',
            'ru.namaruangan',
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mp.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['objectruanganfk']) && $r['objectruanganfk'] != '') {
            $data = $data->where('mp.objectruanganfk', '=', $r['objectruanganfk'] );
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

    public function saveMapRuanganToProduk(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan To Produk

            $delete = MapRuanganToProduk::where('objectruanganfk', $r['objectruanganfk'])
            ->update(['statusenabled'=> false]);
            foreach($r['detail'] as $item){
                $dataPS = new MapRuanganToProduk();
                $dataPS->id = $this->SEQUENCE_MASTER(new MapRuanganToProduk(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->objectruanganfk =  $r['objectruanganfk'];
                $dataPS->objectprodukfk = $item['produkfk'];
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
    public function masterMapRuanganToProdukdropdown(Request $r)
    {
        // $res['namadepartemen'] = Departemen::mine()->get();
        // $res['kelompokproduk'] = KelompokProduk::mine()->get();
        // $res['namaproduk'] = DB::table('produk_m as pr')
        // ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        // ->select('pr.id', 'pr.namaproduk')
        // ->where('pr.kdprofile', $this->kdProfile)
        // ->where('pr.statusenabled', true)
        // ->whereNotIn('djp.objectjenisprodukfk', explode(',', $this->settingFix('kdJenisProdukObat')))
        // ->get();
        $res['namaruangan'] = Ruangan::mine()->orderBy('namaruangan')->get();


        return $this->respond($res);
    }

    public function listRuangan(Request $r)
    {
        $res['namaruangan'] = Ruangan::mine();
        if (isset($r['rufk']) && $r['rufk'] != '') {
            $res['namaruangan'] = $res['namaruangan']->where('objectdepartemenfk', $r['rufk']);
        }
        if (isset($r['objectdepartemenfk']) && $r['objectdepartemenfk'] != '') {
            $res['namaruangan'] = $res['namaruangan']->where('objectdepartemenfk', $r['objectdepartemenfk']);
        }
        $res['namaruangan'] = $res['namaruangan']
            ->orderby('namaruangan')
            ->get();
        return $this->respond($res);
    }
    public function masterMapRuanganToProdukdropdownProduk(Request $r)
    {
     
        $res['namaproduk'] = DB::table('produk_m as pr')
        ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        ->select('pr.id', 'pr.namaproduk')
        ->where('pr.kdprofile', $this->kdProfile)
        ->where('pr.statusenabled', true)
        ->whereNotIn('pr.namaproduk', ['-','--','---'])
        ->whereNotIn('djp.objectjenisprodukfk', explode(',', $this->settingFix('kdJenisProdukObat')));
        if(isset($r['namaproduk']) && $r['namaproduk']!='' ){
            $res['namaproduk']= $res['namaproduk'] ->where('namaproduk','ilike','%'.$r['namaproduk'].'%');
        }
        if(isset($r['limit']) && $r['limit']!='' ){
            $res['namaproduk']= $res['namaproduk'] ->limit($r['limit']);
        }
        $res['namaproduk']= $res['namaproduk'] ->orderBy('pr.namaproduk');
        $res['namaproduk']= $res['namaproduk'] ->get();
     

        return $this->respond($res);
    }

}

