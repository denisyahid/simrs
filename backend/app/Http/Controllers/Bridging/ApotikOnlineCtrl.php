<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApotikOnlineCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDaftarMappingObatBpjsToObatRs(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $kdProfile = $this->kdProfile;
        $data = DB::table('produk_m as pr')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->select('pr.id', 'pr.kdobatbpjs', 'pr.namaproduk' ,'pr.kdproduk')
            ->where('pr.kdprofile', $kdProfile)
            ->where('pr.statusenabled', true)
            ->wherenotNull('pr.kdobatbpjs')
            ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));

        if (isset($request['produk_id']) && $request['produk_id'] != "" && $request['produk_id'] != "undefined") {
            $data = $data->where('pr.id', '=', $request['produk_id']);
        }
        $data = $data->orderBy('pr.namaproduk', 'asc');
        $count = $data->count();
        $data = $data->when($limit, function ($query) use ($limit) {
            return $query->limit($limit);
        });
        $data = $data->when($offset, function ($query) use ($offset) {
            return $query->offset($offset);
        });
        $result = [
            'data' => $data->get(),
            'total' => $count
        ];
        return $this->respond($result);
    }
    public function saveMappingObatBpjsObatRs(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = Produk::where('id', $request['idProduk'])
                ->where('kdprofile', $kdProfile)->first();
            $data->kodeexternal = $request['kodeObatBpjs'];
            $data->kdobatbpjs = $request['kodeObatBpjs'];
            $data->save();
            $this->LOGGING(
                'Mapping Obat Bpjs To Obat Rs',
                $data->norec ?? null,
                'produk_m',
                'Mapping Obat Bpjs To Obat Rs ' . $data->namalengkap ?? null
            );
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Simpan Gagal" .$e->getMessage();
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'ea@epic',
            );
        } else {
            $transMessage = $transMessage;
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "as" => 'ea@epic',
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function saveHapusMappingObatBpjs(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = Produk::where('id', $request['id'])
                ->where('kdprofile', $kdProfile)->first();
            $data->kodeexternal = $this->settingFix('KodeExternal');
            $data->kdobatbpjs = null;
            $data->save();
            $this->LOGGING(
                'Hapus Mapping Obat Bpjs To Obat Rs',
                $data->norec ?? null,
                'pegawai_m',
                'Hapus Mapping Obat Bpjs To Obat Rs ' . $data->namalengkap ?? null
            );
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Hapus Gagal" ;
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'ea@epic',
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "result" => null,
                "as" => 'ea@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function getDaftarMappingRuangan(Request $request){
        $data  = DB::table('ruangan_m as ru')
        ->join('departemen_m as dp', 'ru.objectdepartemenfk', '=', 'dp.id')
        ->select(
            'ru.id',
            'ru.statusenabled',
            'ru.namaruangan',
            'dp.namadepartemen',
            'ru.noruangan',
            'ru.icons',
            'ru.kdsubspesialisbpjs',
            'ru.namasubspesialisbpjs',
            'ru.kdspesialisbpjs',
            'ru.namaspesialisbpjs',
            'ru.objectdepartemenfk',
            'ru.kodeapotikonline'
        )
        ->where('ru.kdprofile', $this->kdProfile)
        ->whereNotNull('ru.kodeapotikonline');
        if ($request['ruangan']) {
            $data->where('ru.namaruangan', $request['ruangan']);
        }
        if ($request['statusenabled']) {
            $data->where('ru.statusenabled', $request['statusenabled']);
        }
        $data = $data->orderByDesc('ru.namaruangan', 'desc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function saveMappingRuangan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = Ruangan::where('id', $request->ruanganId)
                ->where('kdprofile', $kdProfile)->first();
            $data->kodeexternal = $this->settingFix('KodeExternal');
            $data->kodeapotikonline = $request->kodeApotikOnline;
            $data->save();
            $this->LOGGING(
                'Simpan Mapping ruangan Apotik Online',
                $data->norec ?? null,
                'ruangan_m',
                'Simpan ruangan Apotik Online ' . $data->namaruangan ?? null
            );
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Simpan Gagal" ;
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'ea@epic',
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "result" => null,
                "as" => 'ea@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
