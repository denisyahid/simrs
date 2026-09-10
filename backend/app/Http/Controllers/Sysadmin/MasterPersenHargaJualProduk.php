<?php

namespace App\Http\Controllers\Sysadmin;
use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Alergi;
use App\Models\Master\JenisTransaksi;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\PersenHargaJualProduk;
use App\Models\Master\SistemHargaNetto;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class MasterPersenHargaJualProduk extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getData(){

        $data = DB::table('persenhargajualproduk_m as ph')
                  ->join('kelas_m as ks','ks.id', 'ph.objectkelasfk')
                  ->join('kelompokpasien_m as kp','kp.id', 'ph.objectkelompokpasienfk')
                  ->join('range_m as rg','rg.id', 'ph.objectrangefk')
                  ->select('ph.id','ph.persenuphargasatuan','ks.namakelas','ks.id as kelasfk',
                           'kp.kelompokpasien','kp.id as kelompokpasienfk','ph.statusenabled',
                           'rg.rangemax','rg.rangemin','rg.id as range','ph.tglberlakuakhir','ph.tglberlakuawal')
                  ->where('ph.kdprofile',$this->kdProfile)
                  ->where('ph.statusenabled',true)
                  ->get();

        return $this->respond($data);
    }

    public function getComboPersenHargaJual(){

        $data['kelompokPasien'] = KelompokPasien::mine()->get();
        $data['sistemharganetto'] = SistemHargaNetto::mine()->get();
        $data['kelas'] = Kelas::mine()->get();
        $data['rangeHarga'] =   DB::table('range_m')
            ->select(DB::raw("id, rangemin || ' - ' || rangemax as nilai"))
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->orderBy('rangemin')
            ->get();

        return $this->respond($data);
    }

    public function getDataSistemHarga (){

        $data =  SistemHargaNetto::where('kdprofile',$this->kdProfile)
                    ->where('statusenabled',true)
                    ->get();

        return $this->respond($data);
    }

    public function getRangePersen(){

        $data =   DB::table('range_m')
                  ->select(DB::raw("id, rangemin || ' - ' || rangemax as nilai"))
                  ->where('statusenabled',true)
                  ->where('kdprofile',$this->kdProfile)
                  ->get();

        return $this->respond($data);
    }

    public function simpanData (Request $request){

        DB::beginTransaction();
        try {

            if ($request['id'] == '') {

                PersenHargaJualProduk::where('objectkelasfk', $request['kelasfk'])
                    ->where('objectkelompokpasienfk', $request['kelompokPasienfk'])
                    ->update(['statusenabled' => false]);

                $id =$this->SEQUENCE_MASTER(new PersenHargaJualProduk(), 'id', $this->kdProfile);
                $dataPS = new PersenHargaJualProduk();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $dataPS = PersenHargaJualProduk::where('id', $request['id'])->first();
                $dataPS->statusenabled =  $request['statusenabled'];
                $id =  $dataPS->id;
                $transMessage = "Proses Update Data Berhasil";
            }

            $dataPS->statusenabled = true;
            $dataPS->objectasalprodukfk =  1;
            $dataPS->objectjenistransaksifk =  5;
            $dataPS->objectkelasfk =  $request['kelasfk'];
            $dataPS->objectkelaspembandingfk =  $request['kelasfk'];
            $dataPS->objectkelastariffk =  $request['kelasfk'];
            $dataPS->objectkelompokpasienfk = $request['kelompokPasienfk'];
            $dataPS->objectrangefk = $request['rangefk'];
            $dataPS->jenisharganetto = 1;
            $dataPS->kdpenjaminpasien = $request['kelompokPasienfk'];
            $dataPS->persenuphargasatuan = $request['persenHarga'];
            $dataPS->tglberlakuakhir = $request['tglBerakhir'];
            $dataPS->tglberlakuawal = $request['tglBerlaku'] ;
            $dataPS->save();

            DB::commit();
            $result = [
                'status' => 201,
                'message' => $transMessage,
                'result' => $dataPS,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => "Simpan Gagal !",
                'result' => $e->getMessage(),
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = PersenHargaJualProduk::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                ]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => [
                    "messageError" => $e->getMessage(),
                ]
            ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function getDataJenisTransaksi(Request $request){
        $data = JenisTransaksi::where('statusenabled',true)->first();

        return $this->respond($data);
    }

    public function simpanJenisTransaksi(Request $request){

        DB::beginTransaction();
        try {

            JenisTransaksi::where('id',$request['id'])->where('kdprofile',$this->kdProfile)
                        ->update([
                            'metodeambilharganetto' => $request['mtdAmbilHargaNetto'],
                            'metodeharganetto' => $request['mtdHargaNetto'],
                            'metodestokharganetto' => $request['mtdStokHargaNetto'],
                            'sistemharganetto' => $request['sistemHargaNetto'],
                        ]);
            DB::commit();

            $result = ['data'=> '-','message' => 'Berhasil Update Jenis Transaksi','status' => 200];
        } catch (Exception $th) {
            DB::rollBack();
            $result = ['data' => '-', 'message' => 'Gagal Update Jenis Transaksi', 'status' => 400];
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

}
