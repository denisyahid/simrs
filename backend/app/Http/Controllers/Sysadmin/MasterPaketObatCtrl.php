<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\PaketObat;
use App\Models\Master\PaketObatD;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterPaketObatCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterPaketObat(Request $request)
    {
        $result=[];
        $data = DB::table('paketobat_m as sp')
            ->select('sp.id as paketId','sp.namapaket')
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true);

        if(isset($request['paketId']) && $request['paketId'] !='' ){
            $data = $data->where('sp.id',$request['paketId']);
        }
        if(isset($request['namaPaket']) && $request['namaPaket'] !='' ){
            $data = $data->where('sp.namapaket','ilike','%'.$request['namaPaket'].'%');
        }
        $data = $data->get();
        foreach ($data as $item) {
            $details = DB::select(DB::raw("SELECT pkd.*,pro.namaproduk,pro.objectsatuanstandarfk,ss.satuanstandar,
                         pkd.qty as jumlah,sn.satuanresep,pro.kekuatan,stg.name as aturanpakai,pkd.aturanpakai as aturanpakaifk
                    FROM paketobatd_m as pkd
                    -- INNER JOIN paketobat_m as po on po.id = pkd.objectpaketobatfk
                    INNER JOIN produk_m As pro ON pro.id = pkd.produkfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.id = pro.objectsatuanstandarfk
                    LEFT JOIN satuanresep_m AS sn ON sn.id = pkd.satuanresepfk
                    LEFT JOIN stigma as stg on stg.id = pkd.aturanpakai
                    where pkd.kdprofile = $this->kdProfile and pkd.objectpaketobatfk=:norec"),
                array(
                    'norec' => $item->paketId,
                )
            );
            $result[] = array(
                'paketId' => $item->paketId,
                'namapaket' => $item->namapaket,
                'details' => $details,
            );
        }
        $result = array(
            'data' => $result,
            'as' => 'inhuman'
        );
        return $this->respond($result);
    }

    public function savePaketObat(Request $request){
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if($request['idPaket'] == '') {
                $data = new PaketObat();
                $newId = PaketObat::max('id') + 1;
                $data->id = $newId;
                $data->kdpaket = $newId;
                // $data->norec = $data->generateNewId();
                $data->kdprofile = $kdProfile;
                $data->statusenabled = true;
                $data->namapaket = $request['namapaket'];
            }else
            {
                $data = PaketObat::where('id',$request['idPaket'])->where('kdprofile', $kdProfile)->first();
                $dataDetail = PaketObatD::where('objectpaketobatfk', $request['idPaket'])->where('kdprofile', $kdProfile)->delete();
            }
            $data->reportdisplay = $request['namapaket'];
            $data->namaexternal = $request['namapaket'];
            $data->save();
            $idPaket = $data->id;

            foreach ( $request['paketobat'] as $item){
                $map = new PaketObatD();
                $map->id = PaketObatD::max('id') + 1;
                $map->kdprofile = $kdProfile;//12;
                $map->statusenabled = true;
                // $map->norec =  $data->generateNewId();
                $map->norec = PaketObatD::max('id') + 1;
                $map->objectpaketobatfk = $idPaket;
                $map->produkfk = $item['produkfk'];
                $map->qpaket = 0;
                $map->harga = 0;
                if (isset($item['ispagi'])) {
                    $map->ispagi = $item['ispagi'];
                }
                if (isset($item['issiang'])) {
                    $map->issiang = $item['issiang'];
                }
                if (isset($item['ismalam'])) {
                    $map->ismalam = $item['ismalam'];
                }
                if (isset($item['issore'])) {
                    $map->issore = $item['issore'];
                }
                if (isset($item['satuanresepfk'])){
                    $map->satuanresepfk = $item['satuanresepfk'];
                }
                $map->qty = $item['jumlah'];
                if (isset($item['aturanpakai'])){
                    $map->aturanpakai = $item['aturanpakai'];
                }
                if (isset($item['keterangan'])){
                    $map->keterangan = $item['keterangan'];
                }
                $map->save();
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "data"  => $map,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function updatePaketObat(Request $request){
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            // Mencari data PaketObat berdasarkan idPaket yang diberikan
            $data = PaketObat::where('id', $request['idPaket'])
                              ->where('kdprofile', $kdProfile)
                              ->first();
    
            // Jika data tidak ditemukan, kembalikan pesan gagal
            if (!$data) {
                throw new \Exception('Paket obat tidak ditemukan');
            }
    
            // Update informasi paket obat
            $data->namapaket = $request['namapaket'];
            $data->reportdisplay = $request['namapaket'];
            $data->namaexternal = $request['namapaket'];
            $data->save();  // Simpan perubahan pada PaketObat
    
            // Menghapus detail PaketObatD yang lama
            PaketObatD::where('objectpaketobatfk', $request['idPaket'])
                      ->where('kdprofile', $kdProfile)
                      ->delete();
    
            // Menyimpan data detail PaketObatD yang baru
            foreach ($request['paketobat'] as $item) {
                $map = new PaketObatD();
                $map->id = PaketObatD::max('id') + 1;
                $map->kdprofile = $kdProfile;
                $map->statusenabled = true;
                $map->norec = PaketObatD::max('id') + 1;
                $map->objectpaketobatfk = $data->id;  // Referensi ke PaketObat yang sudah ada
                $map->produkfk = $item['produkfk'];
                $map->qpaket = 0;
                $map->harga = 0;
                
                // Set optional values jika ada
                if (isset($item['ispagi'])) {
                    $map->ispagi = $item['ispagi'];
                }
                if (isset($item['issiang'])) {
                    $map->issiang = $item['issiang'];
                }
                if (isset($item['ismalam'])) {
                    $map->ismalam = $item['ismalam'];
                }
                if (isset($item['issore'])) {
                    $map->issore = $item['issore'];
                }
                if (isset($item['satuanresepfk'])) {
                    $map->satuanresepfk = $item['satuanresepfk'];
                }
                $map->qty = $item['jumlah'];
                if (isset($item['aturanpakai'])) {
                    $map->aturanpakai = $item['aturanpakai'];
                }
                if (isset($item['keterangan'])) {
                    $map->keterangan = $item['keterangan'];
                }
                $map->save();  // Simpan detail PaketObatD
            }
    
            // Commit transaksi jika semua berhasil
            DB::commit();
    
            // Response sukses
            $result = array(
                "status" => 200,
                "message" => "Update Berhasil",
                "result" => array(
                    "data"  => $map,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
    
            // Response gagal
            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
    
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    

    public function deletePaketObat (Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = PaketObat::where('id', $request['idpaket'])->where('kdprofile', $kdProfile)->update([
                'statusenabled' => false
            ]);

            $dataDetail = PaketObatD::where('objectpaketobatfk', $request['idpaket'])->where('kdprofile', $kdProfile)->update([
                'statusenabled' => false
            ]);

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Hapus Berhasil",
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

}