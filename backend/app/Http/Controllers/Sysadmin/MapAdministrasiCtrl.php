<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Produk;
use App\Models\Master\Rekanan;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\MapRuanganToAdministrasi;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;
use Exception;

class MapAdministrasiCtrl extends Controller
{
    use Valet;

    public function getListCombo()
    {
        $res['ruangan'] = Ruangan::mine()->get();
        $res['jenispelayanan'] = JenisPelayanan::mine()->get();
        $res['kelompokpasien']= KelompokPasien::mine()->get();


        return $this->respond($res);
    }

    public function getProdukAdmin(Request $r)
    {
        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'mpr.objectruanganfk',
                'prd.namaproduk'
            )->distinct()
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        if (
            isset($r['idProduk']) &&
            $r['idProduk'] != "" &&
            $r['idProduk'] != "undefined"
        ) {
            $data = $data
                ->where('mpr.objectprodukfk', $r['idProduk']);
        }
        if (
            isset($r['idRuangan']) &&
            $r['idRuangan'] != "" &&
            $r['idRuangan'] != "undefined"
        ) {
            $data = $data
                ->where('mpr.objectruanganfk', $r['idRuangan']);
        }
        if (
            isset($r['limit']) &&
            $r['limit'] != "" &&
            $r['limit'] != "undefined"
        ) {
            $data = $data->take($r['limit']);
        }
        $data = $data->orderBy('prd.namaproduk', 'ASC');
        $data = $data->get();
        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function getTindakanKomponen(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $set = $this->settingFix('idPenjaminUmum');

        if ($set == $request['idPenjamin']) {
            $request['idPenjamin'] = null;
        }
        $sk =  DB::table('suratkeputusan_m')
            ->where('statusenabled', true)
            ->where('objectjeniskeputusanfk', $this->settingFix('jenisSK_TARIF'))->first();
        $skID = !empty($sk) ? $sk->id : 0;
        if (
            isset($request['idPenjamin']) && $request['idPenjamin'] != 'null' && $request['idPenjamin'] != ''
            && $request['idPenjamin'] != null
        ) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->select('hnp.objectkomponenhargafk', 'kh.komponenharga', 'hnp.hargasatuan', 'mpr.objectprodukfk', 'kh.iscito', 'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('mpr.statusenabled', true)
                ->where('hnp.statusenabled', true)
                ->where('prd.statusenabled', true);
            $data = $data->distinct();
            $data = $data->get();
        } else {
            $data = [];
        }


        if (count($data) == 0) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->select(
                    'hnp.objectkomponenhargafk',
                    'kh.komponenharga',
                    'hnp.hargasatuan',
                    'mpr.objectprodukfk',
                    'kh.iscito',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->whereNull('hnp.objectpenjaminfk')
                ->where('mpr.statusenabled', true)
                ->where('hnp.statusenabled', true)
                ->where('prd.statusenabled', true);
            $data = $data->distinct();
            $data = $data->get();
        }

        if (
            isset($request['idPenjamin']) && $request['idPenjamin'] != 'null' && $request['idPenjamin'] != ''
            && $request['idPenjamin'] != null
        ) {
            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.hargasatuan',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('hnp.statusenabled', true)
                ->where('sk.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile', $idProfile)
                ->where('prd.statusenabled', true)
                ->distinct()
                ->first();
        } else {
            $data2 = null;
        }

        if (empty($data2)) {

            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.hargasatuan',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->whereNull('hnp.objectpenjaminfk')
                ->where('hnp.statusenabled', true)
                ->where('sk.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile', $idProfile)
                ->where('prd.statusenabled', true)
                ->distinct()
                ->first();
            $istarifpenjamin = false;
        }

        $result = array(
            'komponen' => $data,
            'harga' => $data2,
            'istarifpenjamin' => $istarifpenjamin,
            'as' => '@epic',
        );

        return $this->respond($result);
    }

    public function saveMapAdmin(Request $request)
    {
        DB::beginTransaction();
        try {
            $PSN =  $request['mapping'];

            if ($PSN['id'] == '') {
                $data = new MapRuanganToAdministrasi();
                $data->id = $this->SEQUENCE_MASTER(new MapRuanganToAdministrasi(), 'id', $this->kdProfile); ///(string)Uuid::uuid4();
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = MapRuanganToAdministrasi::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = $this->kdProfile;
            $data->objectruanganfk =  $PSN['objectruanganfk'];
            $data->objectprodukfk =  $PSN['objectprodukfk'];
            $data->jenispelayananfk =  $PSN['jenispelayananfk'];
            $data->rekananfk =isset(  $PSN['rekananfk'])?  $PSN['rekananfk']:null;

            $data->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function getMapAdministrasi(Request $request)
    {
        $data = DB::table('mapruangantoadministrasi_t as mpa')
        ->leftjoin('ruangan_m as ru', 'ru.id', 'mpa.objectruanganfk')
        ->leftjoin('kelompokpasien_m as re', 're.id', 'mpa.rekananfk')
        ->leftjoin('jenispelayanan_m as jp', 'jp.id', 'mpa.jenispelayananfk')
        ->leftjoin('produk_m as pr', 'pr.id', 'mpa.objectprodukfk')
        ->select (
            'mpa.id',
            'mpa.objectruanganfk',
            'mpa.objectprodukfk',
            'mpa.rekananfk',
            'mpa.jenispelayananfk',
            'pr.namaproduk',
            'ru.namaruangan',
            'jp.jenispelayanan',
            're.kelompokpasien'
        )
        ->where('mpa.kdprofile', $this->kdProfile)
        ->where('mpa.statusenabled', true);

        if (isset($request['idruangan']) && $request['idruangan'] != '') {
            $data = $data->where('mpa.objectruanganfk', '=',  $request['idruangan']);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != '') {
            $data = $data->where('mpa.objectprodukfk', '=',  $request['idproduk']);
        }
        if (isset($request['idrekanan']) && $request['idrekanan'] != '') {
            $data = $data->where('mpa.rekananfk', '=',  $request['idrekanan']);
        }
        // if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
        //     $data = $data->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        // }

        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function deletMapping(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = MapRuanganToAdministrasi::where('id', $r['id'])->update(['statusenabled' => false]);

            DB::commit();

            $result = [
                'status' => 201,
                'message' => 'Proses Hapus Data Berhasil',
                'result' => $dataPS,
            ];

        }
        catch(Exception $e){
            $result = [
                'status' => 400,
                'message' => 'Simpan Gagal !',
                'result' => $e->getMessage(),
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

}
