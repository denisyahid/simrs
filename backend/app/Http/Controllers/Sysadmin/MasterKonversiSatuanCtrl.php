<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokProduk;
use App\Models\Master\KonversiSatuan;
use App\Models\Master\SatuanBesar;
use App\Models\Master\SatuanStandar;
use Exception;

class MasterKonversiSatuanCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('produk_m as pr')
            ->leftJOIN('konversisatuan_t as ks', 'pr.id', '=', 'ks.objekprodukfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.id',
                'ks.nilaikonversi',
                'ks.objekprodukfk',
                'ss.id as ssidasal',
                'ss.satuanstandar as satuanstandar_asal',
                'ss2.id as ssidtujuan',
                'ss2.satuanstandar as satuanstandar_tujuan'
            )
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true);
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('pr.id', $request['produkfk']);
        };
        $data = $data->get();

        return $this->respond($data);
    }

    public function getProduk(Request $request)
    {
        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
            ->where('pr.statusenabled', true)
            ->where('pr.kdprofile', $this->kdProfile)
            ->orderBy('pr.namaproduk');

        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        };
        $dataProduk = $dataProduk->take(4);
        $dataProduk = $dataProduk->get();

        return $this->respond($dataProduk);
    }

    public function listSatuanStandar()
    {
        $result = SatuanStandar::mine()->where('statusenabled', true)->get();
        return $this->respond($result);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request['id'] == '') {
                $newKS = new KonversiSatuan();
                $newKS->id = $this->SEQUENCE_MASTER(new KonversiSatuan(), 'id', $this->kdProfile);
                // $newKS->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $newKS = KonversiSatuan::where('id', $request['id'])->first();
                // $newKS->statusenabled = $request['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $newKS->id;
            }
            $newKS->kdprofile = $this->kdProfile;
            $newKS->statusenabled = true;
            $newKS->nilaikonversi = $request['nilaikonversi'];
            $newKS->objekprodukfk = $request['produk'];
            $newKS->satuanstandar_asal = $request['satuanasal'];
            $newKS->satuanstandar_tujuan = $request['satuantujuan'];
            $newKS->save();
            DB::commit();
            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $newKS,
                    "as" => 'setiawan@epic',
                ],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result" => $e->getMessage(),
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function getDataKonversiSatuan(Request $request){

        $data = DB::table('konversisatuan_t as kon')
                    ->join('produk_m as pro','pro.id','kon.objekprodukfk')
                    ->leftjoin('satuanstandar_m as ssal','ssal.id','kon.satuanstandar_asal')
                    ->leftjoin('satuanstandar_m as sstu','sstu.id','kon.satuanstandar_tujuan')
                    ->select('kon.id','pro.namaproduk',
                             'ssal.satuanstandar as satuanasal',
                             'sstu.satuanstandar as satuantujuan',
                             'kon.nilaikonversi','sstu.id as ssidtujuan','ssal.id as ssidasal')
                    ->where('kon.kdprofile',$this->kdProfile)
                    ->where('objekprodukfk',$request['produkId'])
                    ->where('kon.statusenabled',true)
                    ->get();

        return $this->respond($data);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = KonversiSatuan::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  =>$e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

}
