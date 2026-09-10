<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalProduk;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\HargaNettoProdukByKelas;
use App\Models\Master\HargaNettoProdukByKelasD;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\JenisTarif;
use App\Models\Master\Kelas;
use App\Models\Master\KomponenHarga;
use App\Models\Master\MataUang;
use App\Models\Master\Produk;
use App\Models\Master\Rekanan;
use App\Models\Master\SuratKeputusan;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MasterHargaNettoProdukByKelasCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function masterHargaNettoProdukByKelas(Request $r)
    {
        $count = 0;
        $data  = DB::table('harganettoprodukbykelas_m as hpk')
            ->join('kelas_m as ks', 'hpk.objectkelasfk', '=', 'ks.id')
            ->join('produk_m as pr', 'hpk.objectprodukfk', '=', 'pr.id')
            ->join('jenispelayanan_m as jp', 'hpk.objectjenispelayananfk', '=', 'jp.id')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'hpk.objectpenjaminfk')

            ->select(
                'hpk.id',
                'hpk.hargasatuan',
                'hpk.harganetto1',
                'hpk.harganetto2',
                'hpk.persendiscount',
                'hpk.tglberlakuakhir',
                'hpk.tglberlakuawal',
                'hpk.tglkadaluarsalast',
                'ks.namakelas',
                'pr.namaproduk',
                'pr.id as produkfk',
                'jp.jenispelayanan',
                'rk.namarekanan as penjamin',
                'hpk.reportdisplay',
            )

            ->where('hpk.statusenabled', true)
            ->where('ks.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('hpk.kdprofile', $this->kdProfile);

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('hpk.id', '=',  $r['id']);
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        if (isset($r['kelasfk']) && $r['kelasfk'] != '') {
            $data = $data->where('ks.id', '=', $r['kelasfk']);
        }
        if (isset($r['jenispelayanan']) && $r['jenispelayanan'] != '') {
            $data = $data->where('jp.id', '=', $r['jenispelayanan']);
        }
        if (isset($r['penjamin']) && $r['penjamin'] != '') {
            $data = $data->where('rk.id', '=', $r['penjamin']);
        }
        if (isset($r['_total']) && $r['_total'] != '') {
            $count = $data->count();
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        $data = $data->orderByDesc('hpk.id');
        $data = $data->get();

        $res['data'] = $data;
        $res['total'] = $count;
        return $this->respond($res);
    }

    public function masterHargaNettoProdukByKelasdropdown(Request $r)
    {
        $res['namakelas'] = Kelas::mine()->get();
        $res['matauang'] = MataUang::mine()->get();
        $res['jenistarif'] = JenisTarif::mine()->get();
        $res['namask'] = SuratKeputusan::mine()->get();
        $res['asalproduk'] = AsalProduk::mine()->get();
        $res['jenispelayanan'] = JenisPelayanan::mine()->get();
        $res['komponenharga'] = KomponenHarga::mine()->get();
        $res['namarekanan'] = Rekanan::mine()->get();

        return $this->respond($res);
    }
    public function saveKomponenHarga(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Tabel Harga Netto Produk

            $PSN =  $r['harganettoprodukbykelasd'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new HargaNettoProdukByKelasD(), 'id', $this->kdProfile); //$this->Uuid4();
                $dataPS = new HargaNettoProdukByKelasD();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = HargaNettoProdukByKelasD::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->objectkomponenhargafk =  $PSN['objectkomponenhargafk'];
            $dataPS->factorrate =  $PSN['factorrate'];
            $dataPS->hargadiscount =  $PSN['hargadiscount'];
            $dataPS->harganetto1 =  $PSN['harganetto1'];
            $dataPS->harganetto2 =  $PSN['harganetto2'];
            $dataPS->hargasatuan =  $PSN['hargasatuan'];
            $dataPS->persendiscount =  $PSN['persendiscount'];
            $dataPS->hargadijamin =  $PSN['hargadijamin'];

            $dataPS->save();
            //endregion

            DB::commit();

            $result = array(
                "status" => 200,
                "message" => "Simpan Data Berhasil",
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Gagal",
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveHargaNettoProdukByKelas(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Harga Netto Produk
            $kdProfile = (int)$this->kdProfile;
            $PSN =  $r['harganettoprodukbykelas'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new HargaNettoProdukByKelas(), 'id', $this->kdProfile); //$this->Uuid4();
                $dataPS = new HargaNettoProdukByKelas();
                $dataPS->id = $id;
                $dataPS->kdprofile = $kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = HargaNettoProdukByKelas::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();

                HargaNettoProdukByKelasD::where('objectprodukfk', $PSN['objectprodukfk'])
                    ->where('kdprofile', $kdProfile)
                    ->where('objectjenistariffk', $PSN['objectjenistariffk'])
                    ->where('objectasalprodukfk', $PSN['objectasalprodukfk'])
                    ->where('objectkelasfk', $PSN['objectkelasfk'])
                    ->where('objectjenispelayananfk', $PSN['objectjenispelayananfk'])
                    ->where('objectpenjaminfk', $PSN['objectpenjaminfk'])
                    ->delete();
                $id =  $dataPS->id;
            }
            $dataPS->objectasalprodukfk =  $PSN['objectasalprodukfk'];
            $dataPS->objectjenistariffk =  $PSN['objectjenistariffk'];
            $dataPS->objectkelasfk =  $PSN['objectkelasfk'];
            $dataPS->objectmatauangfk =  $PSN['objectmatauangfk'];
            $dataPS->objectprodukfk =  $PSN['objectprodukfk'];
            $dataPS->hargasatuan =  $PSN['hargasatuan'];
            $dataPS->persendiscount =  isset($PSN['persendiscount'])?$PSN['persendiscount']:null;
            $dataPS->tglberlakuakhir =  $PSN['tglberlakuakhir'];
            $dataPS->tglberlakuawal =  $PSN['tglberlakuawal'];
            $dataPS->tglkadaluarsalast =  $PSN['tglkadaluarsalast'];
            $dataPS->objectjenispelayananfk =  $PSN['objectjenispelayananfk'];
            $dataPS->suratkeputusanfk =  $PSN['suratkeputusanfk'];
            $dataPS->hargadijamin =  $PSN['hargadijamin'];
            $dataPS->objectpenjaminfk =  isset($PSN['objectpenjaminfk']) ? $PSN['objectpenjaminfk'] : null;
            $dataPS->save();
            foreach ($r['harganettoprodukbykelasd'] as $r_Detail) {
                $DDDD = new HargaNettoProdukByKelasD();
                $DDDD->id = $this->SEQUENCE_MASTER(new HargaNettoProdukByKelasD(), 'id', $this->kdProfile);
                $DDDD->kdprofile = $kdProfile;
                $DDDD->factorrate = 0;
                $DDDD->objectkomponenhargafk = $r_Detail['objectkomponenhargafk'];
                $DDDD->hargadiscount = 0;
                $DDDD->harganetto1 = (float)$r_Detail['hargasatuan'];
                $DDDD->harganetto2 = (float)$r_Detail['hargasatuan'];
                $DDDD->hargasatuan = (float)$r_Detail['hargasatuan'];
                $DDDD->objectprodukfk = $PSN['objectprodukfk'];
                $DDDD->objectjenistariffk = $PSN['objectjenistariffk'];
                $DDDD->objectasalprodukfk = $PSN['objectasalprodukfk'];
                $DDDD->objectkelasfk = $PSN['objectkelasfk'];
                $DDDD->objectmatauangfk = $PSN['objectmatauangfk'];
                $DDDD->persendiscount = (float)$r_Detail['persendiscount'];
                $DDDD->qtycurrentstok = 0;
                $DDDD->tglberlakuakhir = $PSN['tglberlakuakhir'];
                $DDDD->tglberlakuawal = $PSN['tglberlakuawal'];
                $DDDD->tglkadaluarsalast = $PSN['tglkadaluarsalast'];
                $DDDD->statusenabled = true;
                $DDDD->objectjenispelayananfk = $PSN['objectjenispelayananfk'];
                $DDDD->objectpenjaminfk = isset($PSN['objectpenjaminfk']) ? $PSN['objectpenjaminfk'] : null;
                $DDDD->hargadijamin = (float)$r_Detail['hargadijamin'];
                $DDDD->suratkeputusanfk =  $PSN['suratkeputusanfk'];
                $DDDD->harganettofk =  $dataPS->id ;

                $DDDD->save();
            }
            //endregion

            DB::commit();

            $result = array(
                "status" => 200,
                "message" => "Simpan Data Berhasil",
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal",
                "result"  => null,//$e->getMessage() . ' '.$e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function deleteHargaNettoProdukByKelas(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = HargaNettoProdukByKelas::where('id', $r['id'])
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

    public function getDetailHargaNetoProduk (Request $r){
        $data  = DB::table('harganettoprodukbykelas_m as hpk')
        ->leftjoin('asalproduk_m as ap', 'hpk.objectasalprodukfk', '=', 'ap.id')
        ->leftjoin('jenistarif_m as jt', 'hpk.objectjenistariffk', '=', 'jt.id')
        ->join('kelas_m as ks', 'hpk.objectkelasfk', '=', 'ks.id')
        ->leftjoin('matauang_m as mu', 'hpk.objectmatauangfk', '=', 'mu.id')
        ->join('produk_m as pr', 'hpk.objectprodukfk', '=', 'pr.id')
        ->join('jenispelayanan_m as jp', 'hpk.objectjenispelayananfk', '=', 'jp.id')
        ->select(
            'hpk.id',
            'hpk.objectasalprodukfk',
            'hpk.objectjenistariffk',
            'hpk.objectkelasfk',
            'hpk.objectmatauangfk',
            'hpk.objectprodukfk',
            'hpk.hargasatuan',
            'hpk.norec',
            'hpk.objectpenjaminfk',
            'hpk.harganetto1',
            'hpk.harganetto2',
            'hpk.persendiscount',
            'hpk.tglberlakuakhir',
            'hpk.tglberlakuawal',
            'hpk.tglkadaluarsalast',
            'ap.asalproduk',
            'jt.jenistarif',
            'ks.namakelas',
            'mu.matauang',
            'pr.namaproduk',
            'jp.jenispelayanan',
        )

        ->where('hpk.statusenabled', true)
        ->where('hpk.kdprofile', $this->kdProfile)
        ->where('hpk.id',$r['id'])
        ->first();

        // $detailData = DB::table('harganettoprodukbykelasd_m as hnpk')
        //                 ->leftJoin('komponenharga_m as kh','kh.id','hnpk.objectkomponenhargafk')
    }
    public function masterHargaNettoProdukByKelasEdit($id)
    {

        $data  = DB::table('harganettoprodukbykelas_m as hpk')
            ->join('kelas_m as ks', 'hpk.objectkelasfk', '=', 'ks.id')
            ->join('produk_m as pr', 'hpk.objectprodukfk', '=', 'pr.id')
            ->join('jenispelayanan_m as jp', 'hpk.objectjenispelayananfk', '=', 'jp.id')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'hpk.objectpenjaminfk')
            ->leftjoin('jenistarif_m as jt', 'jt.id', '=', 'hpk.objectjenistariffk')
            ->leftjoin('matauang_m as mat', 'mat.id', '=', 'hpk.objectmatauangfk')
            ->leftjoin('asalproduk_m as asl', 'asl.id', '=', 'hpk.objectasalprodukfk')
            ->leftjoin('suratkeputusan_m as skk', 'skk.id', '=', 'hpk.suratkeputusanfk')
            ->select(
                'hpk.*',
                'ks.namakelas',
                'pr.namaproduk',
                'jp.jenispelayanan',
                'rk.namarekanan',
                'jt.jenistarif',
                'mat.matauang',
                'asl.asalproduk',
                'skk.namask',
            )

            ->where('hpk.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('ks.statusenabled', true)
            ->where('hpk.kdprofile', $this->kdProfile)
            ->where('hpk.id', '=', $id)
            ->first();
       $detail  = DB::table('harganettoprodukbykelasd_m as hpk')
            ->join('kelas_m as ks', 'hpk.objectkelasfk', '=', 'ks.id')
            ->join('produk_m as pr', 'hpk.objectprodukfk', '=', 'pr.id')
            ->join('jenispelayanan_m as jp', 'hpk.objectjenispelayananfk', '=', 'jp.id')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'hpk.objectpenjaminfk')
            ->leftjoin('jenistarif_m as jt', 'jt.id', '=', 'hpk.objectjenistariffk')
            ->leftjoin('matauang_m as mat', 'mat.id', '=', 'hpk.objectmatauangfk')
            ->leftjoin('asalproduk_m as asl', 'asl.id', '=', 'hpk.objectasalprodukfk')
            ->join('komponenharga_m as kom', 'kom.id', '=', 'hpk.objectkomponenhargafk')
            ->select(
                'hpk.*',

                'kom.komponenharga',
            )

            ->where('hpk.statusenabled', true)
            ->where('hpk.kdprofile', $this->kdProfile)
            ->where('hpk.objectjenistariffk', $data->objectjenistariffk)
            ->where('hpk.objectasalprodukfk', $data->objectasalprodukfk)
            ->where('hpk.objectkelasfk', $data->objectkelasfk)
            ->where('hpk.objectjenispelayananfk', $data->objectjenispelayananfk)
            ->where('hpk.objectpenjaminfk', $data->objectpenjaminfk)
            ->where('hpk.objectmatauangfk', $data->objectmatauangfk)
            ->where('hpk.objectprodukfk', $data->objectprodukfk)
            ->where('pr.statusenabled', true)
            ->where('ks.statusenabled', true)
            ->get();

        $res['head'] = $data;
        $res['detail'] = $detail;
        return $this->respond($res);
    }
    public function downloadTemplate(Request $request)
    {

        $pathbundle = 'import/tarif/template_tarif.xlsx';
        $name = 'Template Import Harga Netto.xlsx';
        $path =  public_path($pathbundle);
        if (File::exists($path)) {
            $file = File::get($path);

            $type = File::mimeType($path);

            $response = response()->make($file, 200);
            $response->header("Content-Type", $type)
            ->header( 'Content-disposition', 'attachment; filename="'.$name.'"');
            return $response;
        } else {
            return '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';

        }
    }
    public function importTarif(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Harga Netto Produk
            $kdProfile = (int) $this->kdProfile;
            $produk = Produk::where('namaproduk',$request['import']['namaproduk'])
                ->where('statusenabled',true)
                ->where('kdprofile',$kdProfile)
                ->first();

            $flag =  'IMPORT_EXCEL';

            if(empty($produk)){
                $id = $this->SEQUENCE_MASTER(new Produk(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new Produk();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->namaproduk = $request['import']['namaproduk'];
                $dataPS->namaexternal = $flag;
                $dataPS->reportdisplay = $flag;
                $dataPS->objectdetailjenisprodukfk = $this->settingFix('idDetailJenisProdukImport');
                $dataPS->objectsatuanstandarfk= $this->settingFix('satuanStandarLayanan');
                $dataPS->save();
                $produk = $dataPS;
            }

            $kom = KomponenHarga::whereIN('komponenharga', $request['komponenhargaIn'])
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->get();
            $komPUSH =[];
            foreach($request['komponenharga'] as $k => $d){
                $komPUSH[] = $d;
            }
            foreach($komPUSH as $k => $d){
                foreach($kom as $d2){
                  if($d['komponenharga'] == $d2->komponenharga){
                    $komPUSH[$k]['id'] = $d2->id;
                  }
                }
            }
            foreach($komPUSH as $k => $d){
                if($d['id'] == null){
                    $KOM = new KomponenHarga();
                    $KOM->id =$this->SEQUENCE_MASTER(new KomponenHarga(),'id',$this->kdProfile);//$this->Uuid4();
                    $KOM->kdprofile = (int)$this->kdProfile;
                    $KOM->statusenabled = true;
                    $KOM->komponenharga = $d['komponenharga'];
                    $KOM->namaexternal = $flag;
                    $KOM->nilainormal = 0;
                    $KOM->reportdisplay = $flag;
                    $KOM->save();
                }
            }

            // foreach ($request['import']['details'] as $k => $r_Detail) {
            //     foreach($komPUSH as  $d){
            //         if($r_Detail['komponenharga'] == $d['komponenharga']){
            //             $komPUSH[$k]['id'] = $d2->id;
            //           }
            //     }
            // }
            $kelas = Kelas::where('namakelas', $request['import']['kelas'])
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->first();
            $kelasfk = null;
            if(!empty($kelas)){
                $kelasfk = $kelas->id;
            }
            HargaNettoProdukByKelas::where('objectprodukfk',$produk->id)
            ->where('kdprofile', $kdProfile)
            ->where('reportdisplay', $flag)
            ->where('objectkelasfk',$kelasfk)
            ->where('objectjenispelayananfk', $request['import']['jenispelayananfk'])
            ->where('objectpenjaminfk', $request['import']['penjaminfk'])
            ->update([
                'statusenabled' => false
            ]);

            $dd = HargaNettoProdukByKelasD::where('objectprodukfk',$produk->id)
            ->where('kdprofile', $kdProfile)
            ->where('reportdisplay', $flag)
            ->where('objectkelasfk',$kelasfk)
            ->where('objectjenispelayananfk', $request['import']['jenispelayananfk'])
            ->where('objectpenjaminfk', $request['import']['penjaminfk'])
            ->update([
                'statusenabled' => false
            ]);



            $surkep = SuratKeputusan::where('statusenabled',true)
            ->where('kdprofile', $kdProfile)
            ->first()
            ->id;
            $HNP = new HargaNettoProdukByKelas();
            $HNP->id =  $this->SEQUENCE_MASTER(new HargaNettoProdukByKelas(), 'id', $this->kdProfile); //$this->Uuid4();
            $HNP->kdprofile = $kdProfile;
            $HNP->statusenabled = true;
            $HNP->reportdisplay = $flag;
            $HNP->objectasalprodukfk = null;
            $HNP->objectjenistariffk = null;
            $HNP->objectkelasfk = $kelasfk;
            $HNP->objectmatauangfk = null;
            $HNP->objectprodukfk = $produk->id;
            $HNP->hargasatuan = (float) $request['import']['hargasatuan'];
            $HNP->persendiscount = null;
            $HNP->tglberlakuakhir =   null;
            $HNP->tglberlakuawal =   null;
            $HNP->tglkadaluarsalast =   null;
            $HNP->objectjenispelayananfk = $request['import']['jenispelayananfk'];
            $HNP->suratkeputusanfk =$surkep;
            $HNP->hargadijamin = null;
            $HNP->objectpenjaminfk = $request['import']['penjaminfk'];
            $HNP->save();
            foreach ($request['import']['details'] as $r_Detail) {
                $objectkomponenhargafk = null;
                foreach($komPUSH as $d){
                    if($d['komponenharga'] == $r_Detail['komponenharga']){
                        $objectkomponenhargafk = $d['id'];
                        break;
                    }
                }

                $DDDD = new HargaNettoProdukByKelasD();
                $DDDD->id = $this->SEQUENCE_MASTER(new HargaNettoProdukByKelasD(), 'id', $this->kdProfile);
                $DDDD->kdprofile = $kdProfile;
                $DDDD->statusenabled = true;
                $DDDD->factorrate = 0;
                $DDDD->reportdisplay = $flag;
                $DDDD->objectkomponenhargafk = $objectkomponenhargafk;
                $DDDD->hargadiscount = 0;
                $DDDD->harganetto1 = (float)$r_Detail['hargasatuan'];
                $DDDD->harganetto2 = (float)$r_Detail['hargasatuan'];
                $DDDD->hargasatuan = (float)$r_Detail['hargasatuan'];
                $DDDD->objectprodukfk = $produk->id;
                $DDDD->objectkelasfk = $kelasfk;
                $DDDD->qtycurrentstok = 0;
                $DDDD->objectjenispelayananfk = $request['import']['jenispelayananfk'];
                $DDDD->objectpenjaminfk =  $request['import']['penjaminfk'];
                $DDDD->suratkeputusanfk = $surkep;
                $DDDD->harganettofk = $HNP->id ;
                $DDDD->save();
            }

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "hnp" => $HNP
                )
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function masterHargaNettoProdukByKelasdropdownImport(Request $r)
    {
        $res['jenispelayanan'] = JenisPelayanan::mine()->get();
        $res['namarekanan'] = Rekanan::mine()->get();
        $res['kelas'] = Kelas::mine()->get();
        return $this->respond($res);
    }
}
