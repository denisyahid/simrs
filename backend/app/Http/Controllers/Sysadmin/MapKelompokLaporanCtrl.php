<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisLaporan;
use App\Models\Master\KelompokLaporan;
use App\Models\Master\MapJenisPaguToPegawai;
use App\Models\Master\MapProdukToLaporanRl;
use App\Models\Master\MapRemunKelompok;
use App\Models\Master\Produk;
// use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MapKelompokLaporanCtrl extends Controller
{
    use Valet;

    public function getCombo(){

        $result['kelompoklaporan'] = KelompokLaporan::mine()->get();
        $result['jenislaporan'] = JenisLaporan::mine()->get();
        // $result['ruangan'] = Ruangan::mine()->get();

        return $this->respond($result);
    }

    public function getMapLaporanRL(Request $request) {

        $data = DB::table('mapproduktolaporanrl_m as mptlr')
            ->leftjoin('produk_m as pro','pro.id','=','mptlr.produkfk')
            ->leftjoin('ruangan_m as ru','ru.id','=','mptlr.ruanganfk')
            ->join('jenislaporan_m as jl','jl.id','=','mptlr.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl','kl.id','=','mptlr.objectkontenlaporanfk')
            ->select('mptlr.norec','pro.id as idproduk','pro.namaproduk','jl.id','jl.jenislaporan',
                     'kl.id as idkelompoklaporan','kl.kelompoklaporan','ru.id as ruanganid','ru.namaruangan')
            ->where('mptlr.kdprofile', $this->kdProfile)
            ->where('mptlr.statusenabled',true)
            ->orderBy('jl.id','asc');

            if(isset($request['idjenislaporan'])){
              $data = $data->where('jl.id', $request['idjenislaporan']);
            }

            if(isset($request['idkonten'])){
              $data = $data->where('kl.id', $request['idkonten']);
            }

        $data = $data->get();

        return $this->respond($data);
    }

    public function SaveMappingRl(Request $request) {
        DB::beginTransaction();

        try {

            if ($request['norec'] == '') {
                $newMap = new MapProdukToLaporanRl();
                $norecMap = $this->Uuid4();
                $newMap->norec = $norecMap;
                $newMap->kdprofile = $this->kdProfile;
                $newMap->statusenabled = true;
            }else{
                $newMap = MapProdukToLaporanRl::where('norec',$request['norec'])->first();
            }
            $newMap->objectjenislaporanfk = $request['objectjenislaporanfk'];
            $newMap->objectkontenlaporanfk = $request['objectkelompokkontenfk'];
            $newMap->produkfk = $request['produkfk'];
            $newMap->ruanganfk = $request['ruanganfk'];
            $newMap->save();

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan Mapping Berhasil",
                "map" => $newMap,
                "as" => 'as@epic',
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => "Simpan Gagal !",
                "map" => $e->getMessage(),
                "as" => 'as@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function deleteMap(Request $request){

        DB::beginTransaction();
        try{

            MapProdukToLaporanRl::where('norec',$request['norec'])->where('kdprofile',$this->kdProfile)
            ->where('statusenabled',true)
            ->update(['statusenabled' => false]);

            DB::commit();

            $result = [
                'status' => 200,
                'message' => 'Hapus Data Berhasil'
            ];

        }catch(Exception $e){
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }

        return $this->respond($result);

    }
}
