<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\MapJenisPaguToPegawai;
use App\Models\Master\Produk;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MapJenisPaguToPegawaiCtrl extends Controller
{
    use Valet;

    public function saveMapPaguToPegawai(Request $request)
    {
        DB::beginTransaction();
        try {
            MapJenisPaguToPegawai::where('jenispagufk', $request['jenisPeagu'])->where('statusenabled', true)
                ->where('detailjenispagufk', $request['detailJenisPagu'])->delete();
            foreach ($request['detail'] as $item) {
                $dataPS = new MapJenisPaguToPegawai();
                $dataPS->id = $this->SEQUENCE_MASTER(new MapJenisPaguToPegawai(), 'id', $this->kdProfile);
                $dataPS->norec = $this->Uuid4(); //$this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->jenispagufk =  $request['jenisPeagu'];
                $dataPS->detailjenispagufk =  $request['detailJenisPagu'];
                $dataPS->pegawaifk =  $item['pegawai'];
                $dataPS->save();
            }
            DB::commit();
            $result = [
                'status' => 201,
                'message' => 'Mapping Berhasil',
                'result' => null,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => 'Simpan Gagal !',
                'result' => $e->getMessage(),
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getMappingPaguToPegawai(Request $r)
    {
        $data  = DB::table('mapjenispagutopegawai_t as mpp')
            ->join('pegawai_m as pg', 'pg.id', 'mpp.pegawaifk')
            ->select('pg.namalengkap', 'mpp.*')
            ->where('mpp.statusenabled', true)
            ->where('mpp.kdprofile', $this->kdProfile);

        if (isset($r['jenispagu']) && $r['jenispagu'] != '') {
            $data = $data->where('mpp.jenispagufk', '=',  $r['jenispagu']);
        }
        if (isset($r['detailjenispagu']) && $r['detailjenispagu'] != '') {
            $data = $data->where('mpp.detailjenispagufk', '=',  $r['detailjenispagu']);
        }
        $data = $data->get();
        $res['data'] = $data;

        return $this->respond($res);
    }
}
