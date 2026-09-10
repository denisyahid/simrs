<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\MapJenisPaguToPegawai;
use App\Models\Master\MapRemunKelompok;
use App\Models\Master\Produk;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MapKelompokPenghasilCtrl extends Controller
{
    use Valet;

    public function saveMapRemunKelompok(Request $request)
    {
        DB::beginTransaction();
        try {
            MapRemunKelompok::where('tglawal', $request['tglawal'])->where('tglakhir', $request['tglakhir'])->where('statusenabled', true)
                ->where('objectruanganfk', $request['ruanganfk'])->delete();
            foreach ($request['detail'] as $item) {
                $dataPS = new MapRemunKelompok();
                $dataPS->id = $this->SEQUENCE_MASTER(new MapRemunKelompok(), 'id', $this->kdProfile);
                $dataPS->norec = $this->Uuid4(); //$this->Uuid4();
                $dataPS->kdprofile = $this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->objectruanganfk =  $request['ruanganfk'];
                $dataPS->tglawal =  $request['tglawal'];
                $dataPS->tglakhir =  $request['tglakhir'];
                $dataPS->objectpegawaifk =  $item['pegawai'];
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

    public function getMapRemunKelompok(Request $r)
    {
        $dateRange = [$r->tglawal,$r->tglakhir];
        $data  = DB::table('mapremunkelompok_t as mrk')
            ->join('pegawai_m as pg', 'pg.id', 'mrk.objectpegawaifk')
            ->join('ruangan_m as ru', 'ru.id', 'mrk.objectruanganfk')
            ->select('pg.namalengkap', 'ru.namaruangan', 'mrk.*')
            ->where('mrk.statusenabled', true)
            ->where('mrk.kdprofile', $this->kdProfile)
            ->whereBetween('mrk.tglawal',$dateRange);
            // ->whereDate('mrk.tglawal', $r['tglawal'])
            // ->whereDate('mrk.tglakhir', $r['tglakhir']);

        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->where('mrk.objectruanganfk', '=',  $r['ruanganfk']);
        }
        if (isset($r['pegawaifk']) && $r['pegawaifk'] != '') {
            $data = $data->where('mrk.objectpegawaifk', '=',  $r['pegawaifk']);
        }
        $data = $data->get();

        return $this->respond($data);
    }

    public function deleteMapRemunKelompok(Request $request)
    {
        DB::beginTransaction();
        try {
            MapRemunKelompok::whereIn('id', $request['idkelompok'])->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                'status' => 200,
                'message' => 'Hapus Mapping Berhasil',
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

    public function getDataByDate(Request $request)
    {
        $data = MapRemunKelompok::where('tglawal', $request['tglawal'])->where('tglakhir', $request['tglakhir'])->where('statusenabled', true)
            ->where('objectruanganfk', $request['ruanganfk'])->get();

        return $this->respond($data);
    }
}
