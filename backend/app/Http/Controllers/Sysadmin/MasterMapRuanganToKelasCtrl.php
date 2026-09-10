<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\JenisProduk;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokProduk;
use App\Models\Master\MapRuanganToKelas;
use App\Models\Master\MapRuanganToProduk;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMapRuanganToKelasCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterMapRuanganToKelas(Request $r)
    {
        $data  = DB::table('mapruangantokelas_m as mp')
        ->join('kelas_m as pr', 'mp.objectkelasfk', '=', 'pr.id')
        ->join('ruangan_m as ru', 'mp.objectruanganfk', '=', 'ru.id')
        ->select(
            'mp.id',
            'mp.kodeexternal',
            'mp.namaexternal',
            'mp.norec',
            'mp.reportdisplay',
            'mp.objectkelasfk',
            'mp.objectruanganfk',
            'pr.namakelas',
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
        if (isset($r['objectkelasfk']) && $r['objectkelasfk'] != '') {
            $data = $data->where('mp.objectkelasfk', '=', $r['objectkelasfk'] );
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMapRuanganToKelas(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan To Kelas

            $delete = MapRuanganToKelas::where('objectkelasfk', $r['objectkelasfk'])
            ->update(['statusenabled'=> false]);
            foreach($r['detail'] as $item){
                $dataPS = new MapRuanganToKelas();
                $dataPS->id = $this->SEQUENCE_MASTER(new MapRuanganToKelas(),'id',$this->kdProfile);// $this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->objectkelasfk =  $r['objectkelasfk'];
                $dataPS->objectruanganfk = $item['ruanganfk'];
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
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    
    public function deleteMapRuanganToKelas(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = MapRuanganToKelas::where('id', $r['id'])
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
    public function masterMapRuanganToKelasdropdown(Request $r)
    {
        $res['namakelas'] = Kelas::mine()->get();
        $res['namaruangan'] = Ruangan::mine()->get();


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
}

