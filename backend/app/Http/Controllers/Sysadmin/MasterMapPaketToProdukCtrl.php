<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\JenisProduk;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokProduk;
use App\Models\Master\MapPaketToProduk;
use App\Models\Master\MapRuanganToProduk;
use App\Models\Master\Paket;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMapPaketToProdukCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterMapPaketToProduk(Request $r)
    {
        $data  = DB::table('mappakettoproduk_m as mp')
        ->join('paket_m as pr', 'mp.objectpaketfk', '=', 'pr.id')
        ->join('produk_m as ru', 'mp.objectprodukfk', '=', 'ru.id')
        ->select(
            'mp.id',
            'mp.kodeexternal',
            'mp.namaexternal',
            'mp.norec',
            'mp.reportdisplay',
            'mp.objectpaketfk',
            'mp.objectprodukfk',
            'pr.namapaket',
            'ru.namaproduk',
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mp.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['objectpaketfk']) && $r['objectpaketfk'] != '') {
            $data = $data->where('mp.objectpaketfk', '=', $r['objectpaketfk'] );
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

    public function saveMapPaketToProduk(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Paket To Produk

            $delete = MapPaketToProduk::where('objectpaketfk', $r['objectpaketfk'])
            ->update(['statusenabled'=> false]);
            $last = null;
            foreach ($r['detail'] as $item) {
                $dataPS = MapPaketToProduk::where('id', $item['id'])
                    ->update([
                        'kdprofile' => (int)$this->kdProfile,
                        'statusenabled' => true,
                        'objectpaketfk' => $r['objectpaketfk'],
                        'objectprodukfk' => $item['produkfk'],
                        'objectruanganfk' => $item['objectruanganfk'],
                        'status' => $item['status'],
                    ]);

                $last = MapPaketToProduk::find($item['id']); // Ambil data terakhir jika diperlukan
            }

            //endregion

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = "false";
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $last,
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

    public function saveMapPaketToProdukTemporary(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Paket To Produk

            $delete = MapPaketToProduk::where('objectpaketfk', $r['objectpaketfk'])
            ->update(['statusenabled'=> false]);
            foreach($r['detail'] as $item){
                $dataPS = new MapPaketToProduk();
                $dataPS->id =  $this->SEQUENCE_MASTER(new MapPaketToProduk,'id',$this->kdProfile);//$this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->objectpaketfk =  $r['objectpaketfk'];
                $dataPS->objectprodukfk = $item['produkfk'];
                $dataPS->status = $item['status'];
                // $dataPS->objectruanganfk = $item['objectruanganfk'];
                $dataPS->save();
            }

            //endregion

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = "false";
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

    public function getMapPaketToProdukStatusPending(Request $request)
    {
        try {
            $data = DB::table('mappakettoproduk_m as mp')
                ->join('produk_m as pr', 'mp.objectprodukfk', '=', 'pr.id')
                ->select(
                    'mp.id',
                    'mp.objectpaketfk',
                    'mp.objectprodukfk',
                    'pr.namaproduk',
                    'mp.status',
                    'pr.id as idproduk'
                )
                ->where('mp.objectpaketfk', $request->input('objectpaketfk'))
                ->where('mp.statusenabled', true)
                ->where('mp.status', '=', 'Pending');
                // ->get();
            $data = $data->get();

            if ($data->isEmpty()) {
                $transStatus = 'false';
                $transMessage = "Paket dengan status Pending Tidak ditemukan";
                $result = array(
                    "status" => 404,
                    "result" => null,
                );
            } else {
                $transStatus = 'true';
                $transMessage = "Sukses";
                $result = array(
                    "status" => 200,
                    "result" => $data,
                );
            }
        } catch (\Exception $e) {
            return $this->respond([
                "status" => 500,
                "message" => "Terjadi kesalahan: " . $e->getMessage(),
            ], 500);
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getMapPaketToProdukByID(Request $request)
    {
        try {
            $data = DB::table('mappakettoproduk_m as mp')
                ->join('produk_m as pr', 'mp.objectprodukfk', '=', 'pr.id')
                ->select(
                    'mp.id',
                    'mp.objectpaketfk',
                    'mp.objectprodukfk',
                    'pr.namaproduk',
                    'mp.status',
                    'pr.id as idproduk'
                )
                ->where('mp.objectpaketfk', $request->input('objectpaketfk'))
                ->where('mp.statusenabled', true)
                ->where('mp.status', '!=', 'Pending');
                // ->get();
            $data = $data->get();

            if ($data->isEmpty()) {
                $transStatus = 'false';
                $transMessage = "Silahkan Pilih Produk Terlebih Dahulu";
                $result = array(
                    "status" => 404,
                    "result" => null,
                );
            } else {
                $transStatus = 'true';
                $transMessage = "Sukses";
                $result = array(
                    "status" => 200,
                    "result" => $data,
                );
            }
        } catch (\Exception $e) {
            return $this->respond([
                "status" => 500,
                "message" => "Terjadi kesalahan: " . $e->getMessage(),
            ], 500);
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }


    public function deleteMapPaketToProduk(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = MapPaketToProduk::where('id', $r['id'])
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
    public function masterMapPaketToProdukdropdown(Request $r)
    {
        $res['namapaket'] = Paket::mine()->get();
        $products = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->leftJoin('harganettoprodukbykelas_m as harganettoproduk', 'harganettoproduk.objectprodukfk', '=', 'pr.id')
            ->leftJoin('kebangsaan_m as kb', 'kb.id', '=', 'harganettoproduk.objectkebangsaanfk')
            ->select(   'pr.id',
                        'pr.namaproduk',
                        'harganettoproduk.harganetto1',
                        'harganettoproduk.harganetto2',
                        'harganettoproduk.hargasatuan',
                        'kb.name as kebangsaan',
                    )
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('harganettoproduk.objectkelasfk', '=', 6)
            ->where('kb.name', '=', 'WNI')
            ->distinct()
            // ->groupBy('harganettoproduk.objectprodukfk', 'pr.id')
            ->where('pr.statusenabled', true);
            // ->whereIn('djp.id', [3063, 3064, 3065, 3066, 3010, 3074, 3075, 3076, 3077]);

        $total = $products->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $products = $products->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $products = $products->offset($r['offset']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $products = $products->where(function ($query) use ($searchTerm) {
                $query->where('pr.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('pr.kdproduk', 'ilike', $searchTerm);
            });
        }
        $products = $products->get();
        // namaproduk
        $res['namaproduk'] = $products;
        $res['totalProduct'] = $total;
        return $this->respond($res);
    }
}
