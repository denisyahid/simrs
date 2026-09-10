<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalProduk;
use App\Models\Master\BahanProduk;
use App\Models\Master\Departemen;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\FungsiProduk;
use App\Models\Master\Pegawai;
use App\Models\Master\JenisProduk;
use App\Models\Master\JenisSertifikat;
use App\Models\Master\KelompokAset;
use App\Models\Master\KelompokPasien;
use App\Models\Master\MerkProduk;
use App\Models\Master\ProdusenProduk;
use App\Models\Master\Profile;
use App\Models\Master\Rekanan;
use App\Models\Master\Ruangan;
use App\Models\Master\SatuanStandar;
use App\Models\Master\TypeProduk;
use App\Models\Master\WarnaProduk;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\ChartOfAccountMapJurnal;
use App\Models\Transaksi\KirimProdukAset;
use App\Models\Transaksi\PostingJurnal;
use App\Models\Transaksi\PostingJurnalD;
use App\Models\Transaksi\PostingJurnalTransaksi;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use App\Models\Transaksi\PostingSaldoAwal;
use App\Models\Transaksi\RegistrasiAset;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukPlanning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\FacadesDB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AssetCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDataBarangRegisterAset(Request $request) {

        $idProfile = (int) $this->kdProfile;
        $data = DB::table('registrasiaset_t as ra')
            ->leftJOIN('strukpelayanan_t as sp','sp.norec','=','ra.nostrukterimafk')
            ->leftJOIN('strukpelayanandetail_t as spd','spd.norec','=','ra.nostrukterimadetailfk')
            ->leftJOIN('produk_m as pr','pr.id','=','ra.objectprodukfk')
            ->leftJOIN('detailjenisproduk_m as djp','djp.id','=','pr.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp','jp.id','=','djp.objectjenisprodukfk')
            ->leftJOIN('kelompokproduk_m as kp','kp.id','=','jp.objectkelompokprodukfk')
            ->leftJOIN('satuanstandar_m as ss','ss.id','=','pr.objectsatuanstandarfk')
            ->leftJOIN('asalproduk_m as ap','ap.id','=','ra.objectasalprodukfk')
            ->leftJOIN('ruangan_m as ru','ru.id','=','ra.objectruanganfk')
            ->leftJOIN('ruangan_m as ru1','ru1.id','=','ra.objectruanganposisicurrentfk')
            ->leftJOIN('rekanan_m as rek','rek.id','=','ra.objectsupplier')
            ->select('ra.norec','ra.noregisteraset','ra.objectprodukfk as kdproduk','pr.namaproduk','jp.jenisproduk',
                     'ru.id as ruanganasalfk','ru.namaruangan as namaruanganasal','ru1.id as ruangancurrenfk','ru1.namaruangan as ruangancurrent',
                     'ra.tglregisteraset','ra.tglstrukterima','ra.qtyprodukaset','ra.hargaperolehan','ra.tglregisteraset','ra.tglstrukterima',
                     'rek.namarekanan as namasupplier','rek.alamatlengkap as almSupplier','sp.norec as norecsp',
                     'spd.norec as norecspd','ap.asalproduk','ra.keteranganlainnya','ra.spesifikasi','ra.jenisaset','ra.judul','ra.kdbmn')
            ->where('ra.kdprofile', $idProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('ra.tglregisteraset', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data = $data->where('ra.tglregisteraset', '<=', $request['tglAkhir']);
        }
        if(isset($request['kdproduk']) && $request['kdproduk']!="" && $request['kdproduk']!="undefined"){
            $data = $data->where('ra.objectprodukfk','=', $request['kdproduk']);
        }
        if(isset($request['kdDetailJenis']) && $request['kdDetailJenis']!="" && $request['kdDetailJenis']!="undefined"){
            $data = $data->where('djp.id','=', $request['kdDetailJenis']);
        }
        if(isset($request['ruangancurrenfk']) && $request['ruangancurrenfk']!="" && $request['ruangancurrenfk']!="undefined"){
            $data = $data->where('ru1.id','=', $request['ruangancurrenfk']);
        }
        if (isset($request['norecAsset']) && $request['norecAsset'] != "" && $request['norecAsset'] != "undefined") {
            $data = $data->where('ra.norec', '=', $request['norecAsset']);
        }
        if (isset($request['departemen']) && $request['departemen'] != "" && $request['departemen'] != "undefined") {
            $data = $data->where('ru1.objectdepartemenfk', '=', $request['departemen']);
        }

        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ILIKE', '%'.$request['namaproduk'].'%');
        }
       $data = $data->where('ra.statusenabled',true);

        $page = 1;
        if (isset($request['page']) && $request['page'] != '') {
            $page = $request['page'];
        }

        $data = $data->orderBy('ra.noregisteraset');
        $data = $data ->paginate(isset($request['limit'])?$request['limit']: 10, ['*'], 'page', $page);


        return $this->respond($data);
    }

    public function getDataComboAset(Request $request)
    {
        $data['jenis'] = JenisProduk::mine()->get();
        $data['detailJenis'] = DetailJenisProduk::mine()->get();
        $data['asalproduk'] = AsalProduk::mine()->get();
        $data['kelompokaset'] = KelompokAset::mine()->get();
        $data['fungsiProduk'] = FungsiProduk::mine()->get();
        $data['bahanProduk'] = BahanProduk::mine()->get();
        $data['typeProduk'] = TypeProduk::mine()->get();
        $data['warnaProduk'] = WarnaProduk::mine()->get();
        $data['merkProduk'] = MerkProduk::mine()->get();
        $data['satuanStandar'] = SatuanStandar::mine()->get();
        $data['jenisSertifikat'] = JenisSertifikat::mine()->get();
        $data['produsenProduk'] = ProdusenProduk::mine()->get();
        $data['rekanan'] = Rekanan::mine()->get();

        return $this->respond($data);
    }

    public function getDropdownAsset(Request $request) {

        $idProfile = (int) $this->kdProfile;
        $result['detailjenisproduk'] = DetailJenisProduk::mine()->get();
        $result['departemen'] = Departemen::mine()->get()->toArray();
        $ru = Ruangan::mine()->get();
        foreach ($result['departemen']  as $k => $d) {
            $result['departemen'][$k]['ruangan'] = [];
            foreach ($ru  as $dd) {
                if ($dd->objectdepartemenfk == $d['id']) {
                    $result['departemen'][$k]['ruangan'][] = $dd;
                }
            }
        }
        return $this->respond($result);
    }
    public function SaveDataJadwalAssetKalibrasi(Request $request) {

        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $dataLogin = $request->all();

        try {
            if ($request['norec'] == ''){
                $newCOA = new StrukPlanning();
                $norecHead = $newCOA->generateNewId();
                $newCOA->kdprofile = $idProfile;
                $newCOA->norec = $norecHead;
                $newCOA->statusenabled = 1;
            }else{

                $newCOA =  StrukPlanning::where('norec',$request['norec'])->where('kdprofile', $idProfile)->first();
            }
            $newCOA->tglplanning = $request['tglplanning'];
            $newCOA->keteranganlainnya = $request['keteranganlainnya'];
            $newCOA->noregisterassetfk = $request['noregisterassetfk'];
            $newCOA->objectpegawaipjawabfk = $request['objectpegawaipjawabfk'];
            $newCOA->objectkelompoktransaksifk = 123;
            $newCOA->save();

            $norecHead2 = $newCOA->norec;


            $transMessage = "Simpan Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
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
    public function getDaftarKalibrasi(Request $request){
        $arrru = $request['arrru'];

        $idProfile = (int) $this->kdProfile;
        $data= DB::table('strukplanning_t as spl')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'spl.objectpegawaipjawabfk')
            ->select('spl.tglplanning','spl.objectpegawaipjawabfk','spl.keteranganlainnya','spl.norec'
                ,'pg.namalengkap','spl.startdate','spl.duedate'
            )
            ->where('spl.kdprofile', $idProfile)
            ->where('spl.objectkelompoktransaksifk',123)
            ->where('spl.statusenabled',true);

        if(isset($request['norecAsset']) && $request['norecAsset']!="" && $request['norecAsset']!="undefined"){
            $data = $data->where('spl.noregisterassetfk','ilike','%'. $request['norecAsset'].'%' );
        };
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
////            $data = $data->where('spl.tglplanning','>', date('Y-m-d 00:00:00'));
//        }else{
            $data = $data->whereBetween('spl.tglplanning', [ $request['tglAwal'],$request['tglAkhir'] ]);
        }
        $data = $data->get();
        if (count($data)==0 ){
            $data = [];
        }
        return $this->respond($data);
    }

    public function getDaftarPemeliharaan(Request $request){

        $idProfile = (int) $this->kdProfile;
        $arrru = $request['arrru'];
        $data= DB::table('strukplanning_t as spl')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'spl.objectpegawaipjawabfk')
            ->select('spl.tglplanning','spl.objectpegawaipjawabfk','spl.keteranganlainnya','spl.norec'
                ,'pg.namalengkap','spl.startdate','spl.duedate','spl.keteranganverifikasi'
            )
            ->where('spl.kdprofile', $idProfile)
            ->where('spl.objectkelompoktransaksifk',124);

        if(isset($request['norecAsset']) && $request['norecAsset']!="" && $request['norecAsset']!="undefined"){
            $data = $data->where('spl.noregisterassetfk','=',$request['norecAsset']);
        };
        if(isset($request['jenis']) && $request['jenis']!="" && $request['jenis']!="undefined"){
            $data = $data->whereNotNull('spl.duedate');
        };
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
////            $data = $data->where('spl.tglplanning','>', date('Y-m-d 00:00:00'));
//        }else{
            $data = $data->whereBetween('spl.tglplanning', [ $request['tglAwal'],$request['tglAkhir'] ]);
        }
        $data = $data->get();
        if (count($data)==0 ){
            $data = [];
        }


        return $this->respond($data);
    }
    public function SaveDataJadwalAssetPemeliharaan(Request $request) {
        DB::beginTransaction();


        $idProfile = (int) $this->kdProfile;
        try {
            if ($request['norec'] == ''){
                $newCOA = new StrukPlanning();
                $norecHead = $newCOA->generateNewId();
                $newCOA->kdprofile = $idProfile;
                $newCOA->norec = $norecHead;
                $newCOA->statusenabled = 1;
            }else{

                $newCOA =  StrukPlanning::where('norec',$request['norec'])->first();
            }
            $newCOA->tglplanning = $request['tglplanning'];
            $newCOA->keteranganlainnya = $request['keteranganlainnya'];
            $newCOA->noregisterassetfk = $request['noregisterassetfk'];
            $newCOA->objectpegawaipjawabfk = $request['objectpegawaipjawabfk'];
            $newCOA->objectkelompoktransaksifk = 124;
            $newCOA->save();

            $norecHead2 = $newCOA->norec;

            $transMessage = "Simpan Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
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

    public function getJadwalKalibrasi(Request $request){
        $arrru = $request['arrru'];
        $kdProfile = (int) $this->kdProfile;
        $KdKelTransKalibrasi = (int) $this->settingDataFixed('KdTransKalibrasi', $kdProfile);
        $data= DB::table('strukplanning_t as spl')
            ->leftjoin('registrasiaset_t as ra', 'ra.norec', '=', 'spl.noregisterassetfk')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'ra.objectprodukfk')
            ->select('spl.tglplanning','spl.objectpegawaipjawabfk','spl.keteranganlainnya','spl.norec','pr.namaproduk',
                DB::raw(" to_char(spl.tglplanning, 'YYYY/MM/DD') || ' 00:00'   AS start,
                to_char(spl.tglplanning, 'YYYY/MM/DD') || ' 23:59'  AS ends,
                to_char(spl.tglplanning, 'YYYY/MM/DD') || ' 00:00' AS startepoch,
                to_char(spl.tglplanning, 'YYYY/MM/DD') || ' 23:59'  AS endpoch"))
            ->where('spl.objectkelompoktransaksifk',$KdKelTransKalibrasi)
            ->where('spl.kdprofile', $kdProfile)->where('spl.statusenabled',true);

//        if(isset($request['norecAsset']) && $request['norecAsset']!="" && $request['norecAsset']!="undefined"){
//            $data = $data->where('spl.noregisterassetfk','ilike','%'. $request['norecAsset'].'%' );
//        };
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
////            $data = $data->where('spl.tglplanning','>', date('Y-m-d 00:00:00'));
//        }else{
            $data = $data->whereBetween('spl.tglplanning', [ $request['tglAwal'],$request['tglAkhir'] ]);
        }
        $data = $data->get();
        if (count($data)==0 ){
            $data = [];
        }


        return $this->respond($data);
    }
    public function getJadwalPemeliharaan(Request $request){
        $arrru = $request['arrru'];

        $idProfile = (int) $this->kdProfile;
        $data= DB::table('strukplanning_t as spl')
            ->leftjoin('registrasiaset_t as ra', 'ra.norec', '=', 'spl.noregisterassetfk')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'ra.objectprodukfk')
            ->select('spl.tglplanning','spl.objectpegawaipjawabfk','spl.keteranganlainnya','spl.norec'
                ,'pr.namaproduk',
                DB::raw(" to_char(spl.tglplanning, 'YYYY/MM/DD') || ' 00:00'   AS start,
                to_char(spl.tglplanning, 'YYYY/MM/DD') || ' 23:59'  AS ends,
                to_char(spl.tglplanning, 'YYYY/MM/DD') || ' 00:00' AS startepoch,
                to_char(spl.tglplanning, 'YYYY/MM/DD') || ' 23:59'  AS endpoch")
            )
            ->where('spl.kdprofile', $idProfile)
            ->where('spl.objectkelompoktransaksifk',124)
            ->where('spl.statusenabled',true);

//        if(isset($request['norecAsset']) && $request['norecAsset']!="" && $request['norecAsset']!="undefined"){
//            $data = $data->where('spl.noregisterassetfk','ilike','%'. $request['norecAsset'].'%' );
//        };
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
////            $data = $data->where('spl.tglplanning','>', date('Y-m-d 00:00:00'));
//        }else{
            $data = $data->whereBetween('spl.tglplanning', [ $request['tglAwal'],$request['tglAkhir'] ]);
        }
        $data = $data->get();
        if (count($data)==0 ){
            $data = [];
        }


        return $this->respond($data);
    }
    public function pegwaiPart(Request $r)
    {

        $result=
            Pegawai::mine()
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }
    public function DeleteDataJadwalAssetPemeliharaan(Request $request) {
        DB::beginTransaction();


        $idProfile = (int) $this->kdProfile;
        try {

                $newCOA =  StrukPlanning::where('norec',$request['norec'])->update([
                    'statusenabled' =>false
                ]);

            $transMessage = "Hapus Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }
    public function SaveDataWorkList(Request $request) {
        DB::beginTransaction();
        $dataLogin = $request->all();
        $kdProfile = (int) $this->kdProfile;
        try {
            if ($request['norec'] == ''){
                $newCOA = new StrukPlanning();
                $norecHead = $newCOA->generateNewId();
                $newCOA->kdprofile = $kdProfile;
                $newCOA->norec = $norecHead;
                $newCOA->statusenabled = 1;
            }else{

                $newCOA =  StrukPlanning::where('norec',$request['norec'])->where('kdprofile', $kdProfile)->first();
            }
            $newCOA->deskripsiplanning = $request['deskripsiplanning'];
            $newCOA->save();

            $norecHead2 = $newCOA->norec;
            $transMessage = "Simpan Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
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
    public function SaveDataInspeksi(Request $request) {
        DB::beginTransaction();
        $dataLogin = $request->all();
        $kdProfile = (int)  $this->kdProfile;
        try {
            if ($request['norec'] == ''){
                $newCOA = new StrukPlanning();
                $norecHead = $newCOA->generateNewId();
                $newCOA->kdprofile = $kdProfile;
                $newCOA->norec = $norecHead;
                $newCOA->statusenabled = 1;
            }else{

                $newCOA =  StrukPlanning::where('norec',$request['norec'])->where('kdprofile', $kdProfile)->first();
            }
            $newCOA->keteranganverifikasi = $request['keteranganverifikasi'];
            $newCOA->objectpegawaipjawabevaluasifk = $request['objectpegawaipjawabevaluasifk'];
            $newCOA->save();

            $norecHead2 = $newCOA->norec;

            $transMessage = "Simpan Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
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
    public function SaveDataStartDate(Request $request) {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $dataLogin = $request->all();

        try {
            if ($request['norec'] == ''){
                $newCOA = new StrukPlanning();
                $norecHead = $newCOA->generateNewId();
                $newCOA->kdprofile = $kdProfile;
                $newCOA->norec = $norecHead;
                $newCOA->statusenabled = 1;
            }else{

                $newCOA =  StrukPlanning::where('norec',$request['norec'])->where('kdprofile', $kdProfile)->first();
            }
            $newCOA->startdate = date('Y-m-d H:i:s');
            $newCOA->save();

            $norecHead2 = $newCOA->norec;
            $transMessage = "Simpan Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
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
    public function SaveDataDueDate(Request $request) {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $dataLogin = $request->all();

        try {
            if ($request['norec'] == ''){
                $newCOA = new StrukPlanning();
                $norecHead = $newCOA->generateNewId();
                $newCOA->kdprofile = $kdProfile;
                $newCOA->norec = $norecHead;
                $newCOA->statusenabled = 1;
            }else{

                $newCOA =  StrukPlanning::where('norec',$request['norec'])->where('kdprofile', $kdProfile)->first();
            }
            $newCOA->duedate = date('Y-m-d H:i:s');
            $newCOA->save();

            $norecHead2 = $newCOA->norec;

            $transMessage = "Simpan Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
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

    public function SimpanDetailRegisterAset (Request $request) {
        try {
            $idProfile = (int) $this->kdProfile;
            DB::beginTransaction();
            //## RegisterAsset
            if ($request['regAset']['norec'] == '') {
                $noasset = $this->SEQUENCE(new RegistrasiAset(),'noregisteraset',12,'AST'.$this->getDateTime()->format('ym'), $idProfile);
                $dataRegAset = new RegistrasiAset();
                $dataRegAset->norec = $dataRegAset->generateNewId();
                $dataRegAset->kdprofile = $idProfile;
                $dataRegAset->statusenabled = true;
                $dataRegAset->noregisteraset = $noasset;
                $dataRegAset->tglregisteraset =$request['regAset']['tglregisteraset'];
                $dataRegAset->objectprodukfk=$request['regAset']['objectprodukfk'];
                $dataRegAset->qtyprodukaset=1;
            }else{
                $dataRegAset =  RegistrasiAset::where('norec',$request['regAset']['norec'])->first();
            }
            $dataRegAset->tglpembelian =$request['regAset']['tglpembelian'];
            $dataRegAset->tgldistribusi =$request['regAset']['tgldistribusi'];
            $dataRegAset->alamatlengkap = $request['regAset']['alamatlengkap'];
            $dataRegAset->objectruanganfk = $request['regAset']['objectruanganfk'];
            $dataRegAset->objectruanganposisicurrentfk =$request['regAset']['objectruanganposisicurrentfk'];
            $dataRegAset->objectasalprodukfk = $request['regAset']['objectasalprodukfk'];
            $dataRegAset->objectbahanprodukfk = $request['regAset']['objectbahanprodukfk'];
            $dataRegAset->bpkb_atasnama =$request['regAset']['bpkb_atasnama'];
            $dataRegAset->objectdesakelurahanfk=$request['regAset']['objectdesakelurahanfk'];
            $dataRegAset->kdbmn=$request['regAset']['kdbmn'];
            $dataRegAset->kdjenissertifikat=$request['regAset']['kdjenissertifikat'];
//            $dataRegAset->kdrsabhk=$request['regAset']['kdrsabhk'];
            $dataRegAset->objectkecamatanfk=$request['regAset']['objectkecamatanfk'];
            $dataRegAset->objectkelompokasetfk=$request['regAset']['objectkelompokasetfk'];
            $dataRegAset->kodepos=$request['regAset']['kodepos'];
            $dataRegAset->objectkotakabupatenfk=$request['regAset']['objectkotakabupatenfk'];
            $dataRegAset->lb_lebar=$request['regAset']['lb_lebar'];
            $dataRegAset->lb_panjang=$request['regAset']['lb_panjang'];
            $dataRegAset->lb_tinggi=$request['regAset']['lb_tinggi'];
            $dataRegAset->masaberlakusertifikat=$request['regAset']['masaberlakusertifikat'];
            $dataRegAset->sertifikat_atasnama=$request['regAset']['sertifikat_atasnama'];
            $dataRegAset->kdjenissertifikat=$request['regAset']['kdjenissertifikat'];
            $dataRegAset->objectmerkprodukfk=$request['regAset']['objectmerkprodukfk'];
            $dataRegAset->desakelurahan=$request['regAset']['desakelurahan'];
            $dataRegAset->kecamatan=$request['regAset']['kecamatan'];
            $dataRegAset->kotakabupaten=$request['regAset']['kotakabupaten'];
            $dataRegAset->nobpkb=$request['regAset']['nobpkb'];
            $dataRegAset->nomesin=$request['regAset']['nomesin'];
            $dataRegAset->nomodel=$request['regAset']['nomodel'];
            $dataRegAset->nopolisi=$request['regAset']['nopolisi'];
            $dataRegAset->norangka=$request['regAset']['norangka'];
            $dataRegAset->noseri=$request['regAset']['noserispek'];
            $dataRegAset->nosertifikat=$request['regAset']['nosertifikat'];
            $dataRegAset->objectprodusenprodukfk=$request['regAset']['objectprodusenprodukfk'];
            $dataRegAset->objectpropinsifk=$request['regAset']['objectpropinsifk'];
            $dataRegAset->objectwarnaprodukfk=$request['regAset']['objectwarnaprodukfk'];
            $dataRegAset->dayalistrik=$request['regAset']['dayalistrik'];
            $dataRegAset->objectdetailjenisproduk=$request['regAset']['objectdetailjenisproduk'];
            $dataRegAset->objectjenisproduk=$request['regAset']['objectjenisproduk'];
            if (isset($request['regAset']['klasifikasiteknologi'])) {
                $dataRegAset->klasifikasiteknologi=$request['regAset']['klasifikasiteknologi'];
            }
            $dataRegAset->objectsatuan=$request['regAset']['objectsatuan'];
            $dataRegAset->sisaumur=$request['regAset']['sisaumur'];
            $dataRegAset->objectsupplier=$request['regAset']['objectsupplier'];
            $dataRegAset->tahunperolehan=$request['regAset']['tahunperolehan'];
            $dataRegAset->usiapakai=$request['regAset']['usiapakai'];
            $dataRegAset->usiateknis=$request['regAset']['usiateknis'];
            $dataRegAset->fungsikegunaan=$request['regAset']['fungsikegunaan'];
//            $dataRegAset->objecttypeprodukfk=$request['regAset']['objecttypeprodukfk'];
            $dataRegAset->tglproduksi=$request['regAset']['tglproduksi'];
            // $dataRegAset->noseri=$request['regAset']['noseri'];
            $dataRegAset->hargaperolehan=$request['regAset']['hargaperolehan'];
            $dataRegAset->noregisteraset_int=$request['regAset']['noaset'];
            $dataRegAset->judul=$request['regAset']['judul'];
            $dataRegAset->spesifikasi=$request['regAset']['spesifikasi'];
            $dataRegAset->jenisaset=$request['regAset']['jenisaset'];
            $dataRegAset->nilaisisa=$request['regAset']['nilaisisa'];
            $dataRegAset->umurasset=$request['regAset']['umurasset'];
            $dataRegAset->save();
            //## END RegisterAsset

            $norecdataRegAset = $dataRegAset->norec;

            //* STOKPRODUK DETAIL
                $StokPD = new StokProdukDetail();
                $norecStokPD = $StokPD->generateNewId();
                $StokPD->norec = $norecStokPD;
                $StokPD->kdprofile = $idProfile;
                $StokPD->statusenabled = true;
                $StokPD->objectasalprodukfk = $request['regAset']['objectasalprodukfk'];
                $StokPD->hargadiscount = 0;
                $StokPD->harganetto1 = ((float)$request['regAset']['hargaperolehan']); //+(float)$request['regAset']['ppn'])/(float)$request['regAset']['nilaikonversi'];
                $StokPD->harganetto2 = ((float)$request['regAset']['hargaperolehan']); // /(float)$request['regAset']['nilaikonversi'];
                $StokPD->persendiscount = 0;
                $StokPD->objectprodukfk = $request['regAset']['objectprodukfk'];
                $StokPD->qtyproduk = $request['regAset']['qtyprodukaset'];;
                $StokPD->qtyprodukonhand = 0;
                $StokPD->qtyprodukoutext = 0;
                $StokPD->qtyprodukoutint = 0;
                $StokPD->objectruanganfk = $request['regAset']['objectruanganposisicurrentfk'];
                if(isset($request['regAset']['nostruk']) &&
                    $request['regAset']['nostruk']!="" &&
                    $request['regAset']['nostruk']!="undefined"){
                    $StokPD->nostrukterimafk = $request['regAset']['nostruk'];
                }else{
                    $StokPD->nostrukterimafk = "INITASSET-0001";
                }
//                $StokPD->nobatch = $request['regAset']['nobatch'];
                if(isset($request['regAset']['nostrukterimadetailfk']) &&
                    $request['regAset']['nostrukterimadetailfk']!="" &&
                    $request['regAset']['nostrukterimadetailfk']!="undefined"){
                    $StokPD->nostrukterimafk = $request['regAset']['nostrukterimadetailfk'];
                }else{
                    $StokPD->nostrukterimafk = "INITASSET-0001";
                }
//                $StokPD->tglkadaluarsa = $request['regAset']['tglkadaluarsa'];
                $StokPD->tglpelayanan = date('Y-m-d H:i:s');//$request['regAset']['tglregisteraset'];
                $StokPD->save();
            //* END STOKPRODUK DETAIL

            $transMessage = "Simpan Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
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
    public function getDetailBarangRegisterAset(Request $request) {

        $idProfile = (int) $this->kdProfile;
        $data = DB::table('registrasiaset_t as ra')
            ->leftJOIN('strukpelayanan_t as sp','sp.norec','=','ra.nostrukterimafk')
            ->leftJOIN('strukpelayanandetail_t as spd','spd.norec','=','ra.nostrukterimadetailfk')
            ->JOIN('produk_m as pr','pr.id','=','ra.objectprodukfk')
            ->leftJOIN('detailjenisproduk_m as djp','djp.id','=','ra.objectdetailjenisproduk')
            ->leftJOIN('jenisproduk_m as jp','jp.id','=','ra.objectjenisproduk')
//            ->leftJOIN('kelompokproduk_m as kp','kp.id','=','ra.objectkelompokasetfk')
            ->leftJOIN('satuanstandar_m as ss','ss.id','=','ra.objectsatuan')
            ->leftJOIN('asalproduk_m as ap','ap.id','=','ra.objectasalprodukfk')
            ->leftJOIN('ruangan_m as ru','ru.id','=','ra.objectruanganfk')
            ->leftJOIN('ruangan_m as ru1','ru1.id','=','ra.objectruanganposisicurrentfk')
            ->leftJOIN('rekanan_m as rek','rek.id','=','ra.objectsupplier')
            ->leftJOIN('kelompokaset_m as ka','ka.id','=','ra.objectkelompokasetfk')
            ->leftJOIN('merkproduk_m as mp','mp.id','=','ra.objectmerkprodukfk')
            ->leftJOIN('typeproduk_m as tp','tp.id','=','ra.objecttypeprodukfk')
            ->select('ra.norec','ra.noregisteraset','ra.objectprodukfk as idproduk','pr.namaproduk','jp.id as jpid','jp.jenisproduk','ra.kdbmn as kodebmn',
                'pr.kodeexternal','pr.kdproduk','pr.tglproduksi','ru.id as ruanganasalfk','ru.namaruangan as namaruanganasal','ru1.id as ruangancurrenfk',
                'ru1.namaruangan as ruangancurrent','ap.id as apid','ap.asalproduk','ka.id as kaid','ka.kelompokaset','ra.tglregisteraset',
                'ra.tglstrukterima','ra.tahunperolehan','djp.id as djpid','djp.detailjenisproduk','ra.qtyprodukaset','ra.hargaperolehan','ra.tglregisteraset',
                'ra.tglstrukterima','rek.id as idsupplier','rek.namarekanan as namasupplier','rek.alamatlengkap as almSupplier','sp.norec as norecsp',
                'ss.id as ssid','ss.satuanstandar','ra.qtyprodukaset','spd.norec as norecspd','ap.asalproduk','ra.noregisteraset_int',
                'ra.masaberlakusertifikat','ra.sisaumur','ra.noseri','ra.tahunperolehan','ra.objectmerkprodukfk as merkid','mp.merkproduk',
                'ra.tahunperolehan','tp.typeproduk','ra.nilaisisa','ra.umurasset', 'ra.spesifikasi',
                DB::raw("to_char(ra.tglpembelian,'YYYY-MM-DD') as tglpembelian, to_char(ra.tgldistribusi,'YYYY-MM-DD') as tgldistribusi"))
            ->where('ra.kdprofile',$idProfile);

        if (isset($request['norecAsset']) && $request['norecAsset'] != "" && $request['norecAsset'] != "undefined") {
            $data = $data->where('ra.norec', '=', $request['norecAsset']);
        }
//        $data = $data->where('ra.statusenabled',true);
        $data = $data->orderBy('ra.noregisteraset');
        $data = $data->limit(1);
        $data = $data->get();

        $result = array(
            'datas' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }
    public function getDataPenyusutan(Request $request) {

        $idProfile = (int) $this->kdProfile;
        //todo : raw query sum
        $req=$request->all();
        $dataProduk = DB::table('penyusutanasset_t as pa')
            ->select('pa.*')
            ->where('pa.kdprofile',$idProfile);
        if(isset($request['norecAsset']) && $request['norecAsset']!="" && $request['norecAsset']!="undefined"){
            $dataProduk = $dataProduk->where('pa.noregistrasifk','=', $request['norecAsset']);
        }
        $dataProduk = $dataProduk->get();

        $result=array(
            'data' => $dataProduk,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }
    public function getDaftarHistoryAsset(Request $request){

        $idProfile = (int) $this->kdProfile;
        if(isset( $request['norecAsset'])&&  $request['norecAsset']!=''){
            $noregisterasetfk = "and kpa.noregisterasetfk ='" . $request['norecAsset'] . "'";
        }

        $data = DB::select(DB::raw("
                select sk.nokirim,sk.tglkirim,sk.objectruanganasalfk,
                ruasal.namaruangan as ruanganasal,sk.objectruangantujuanfk ,rutujuan.namaruangan as ruangantujuan
                from strukkirim_t as sk
                INNER JOIN kirimprodukaset_t as kpa on kpa.nokirimfk=sk.norec
                INNER JOIN ruangan_m as ruasal on ruasal.id=sk.objectruanganasalfk
                INNER JOIN ruangan_m as rutujuan on rutujuan.id=sk.objectruangantujuanfk
                where sk.kdprofile = $idProfile and sk.objectkelompoktransaksifk=95
                $noregisterasetfk
			"));

        $result= array(
            'data' => $data,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function getDataProdukKirim(Request $request)
    {
        //todo : raw query sum
        $req = $request->all();
        $dataProduk = DB::table('registrasiaset_t as ra')
        ->JOIN('produk_m as pr', 'pr.id', '=', 'ra.objectprodukfk')
        ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'ra.objectdetailjenisproduk')
        ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'ra.objectjenisproduk')
        ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'ra.objectsatuan')
        ->leftJOIN('ruangan_m as ru', 'ru.id', '=', 'ra.objectruanganposisicurrentfk')
        ->select('ra.noregisteraset', 'pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.kdproduk', 'ra.qtyprodukaset', 'ru.namaruangan')
        ->where('ra.qtyprodukaset', '>', 0)
            ->where('ra.kdprofile', $this->kdProfile)
            ->orderBy('pr.namaproduk');
        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $dataProduk = $dataProduk->where('ru.id', '=', $request['ruanganId']);
        }
        $dataProduk = $dataProduk->get();

        $result[] = array(
            'data' => $dataProduk,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }


    public function saveKirimBarangAsset(Request $request)
    {

        $idProfile = $this->kdProfile;
        DB::beginTransaction();
        $nilaikonversi = 1;
        try {
            //## StrukKirim
            if ($request['strukkirimaset']['noreckirim'] == '') {
             
                $noKirim = $this->generateCode(new StrukKirim(), 'nokirim', 14, 'TRFA-' . $this->getDateTime()->format('ym'), $idProfile);
                $jenispermintaan = 1;
                $dataSK = new StrukKirim();
                $dataSK->norec = $dataSK->generateNewId();
                $dataSK->nokirim = $noKirim;
                $dataSK->kdprofile = $idProfile;
                $dataSK->statusenabled = true;
            } else {
                $dataSK =  StrukKirim::where('norec', $request['strukkirimaset']['noreckirim'])->first();
            }
            $dataSK->qtyproduk = 0;
            $dataSK->tglkirim = $request['strukkirimaset']['tglkirim'];
            $dataSK->totalbeamaterai = 0;
            $dataSK->totalbiayakirim = 0;
            $dataSK->totalbeamaterai = 0;
            $dataSK->totalbiayatambahan = 0;
            $dataSK->totaldiscount = 0;
            $dataSK->totalhargasatuan = 0;
            $dataSK->totalharusdibayar = 0;
            $dataSK->totalpph = 0;
            $dataSK->totalppn = 0;
            $dataSK->qtydetailjenisproduk = 0;
            $dataSK->objectpegawaipengirimfk = $this->getPegawaiId();;
            $dataSK->objectruanganasalfk = $request['strukkirimaset']['objectruanganfk'];
            $dataSK->objectruangantujuanfk = $request['strukkirimaset']['objectruangantujuanfk'];
            $dataSK->jenispermintaanfk = $jenispermintaan;
            $dataSK->objectkelompoktransaksifk = $this->kelompokTransaksi('TRANSFER ASET');
            $dataSK->save();
            //## END StrukKirim

            $SK = array("norec"  => $dataSK->norec);

            // return $request['strukkirimaset']['objectruanganfk'];
            foreach ($request['details'] as $item) {
                $dataSaldoAwalK = DB::select(
                    DB::raw("select qtyproduk as qty,nostrukterimafk,norec,objectasalprodukfk as asalprodukfk,
                        hargadiscount,harganetto1 as harganetto,harganetto1 as hargasatuan
                        from stokprodukdetail_t 
                        where kdprofile = $idProfile and  objectruanganfk=:ruanganfk and objectprodukfk=:produkfk and qtyproduk > 0 "),
                    array(
                        'ruanganfk' => $request['strukkirimaset']['objectruanganfk'],
                        'produkfk' => $item['produkfk'],
                    )
                );
                
                $saldoAwalPengirim = 0;
                $jumlah = (float)$item['jumlah'] * (float)$nilaikonversi;
                foreach ($dataSaldoAwalK as $items) {
                    $saldoAwalPengirim = $saldoAwalPengirim + (float)$items->qty;
                }
                
                if ((float)$items->qty <= $jumlah) {
                    //## KirimProdukAset
                    $dataKPA = new KirimProdukAset();
                    $dataKPA->norec = $dataKPA->generateNewId();
                    $dataKPA->kdprofile = $idProfile;
                    $dataKPA->statusenabled = true;
                    $dataKPA->objectkondisiprodukfk = $item['kondisiasetfk'];
                    $dataKPA->nokirimfk = $SK['norec'];
                    $dataKPA->noregisterasetfk = $request['strukkirimaset']['norecAsset'];
                    $dataKPA->qtyproduk = $item['jumlah'];
                    $dataKPA->produkfk = $item['produkfk'];
                    $dataKPA->save();
                    //## END KirimProdukAset
                    $jumlah = $jumlah - (float)$items->qty;
                    StokProdukDetail::where('norec', $items->norec)->where('kdprofile', $idProfile)->update(['qtyproduk' => 0]);
                } else {
                    //## KirimProdukAset
                    $dataKPA = new KirimProdukAset();
                    $dataKPA->norec = $dataKPA->generateNewId();
                    $dataKPA->kdprofile = $idProfile;
                    $dataKPA->statusenabled = true;
                    $dataKPA->objectkondisiprodukfk = $item['kondisiasetfk'];
                    $dataKPA->nokirimfk = $SK['norec'];
                    $dataKPA->noregisterasetfk = $request['strukkirimaset']['norecAsset'];
                    $dataKPA->qtyproduk = $item['jumlah'];
                    $dataKPA->produkfk =  $item['produkfk'];
                    $dataKPA->save();
                    //## END KirimProdukAset
                    $saldoakhir = (float)$items->qty - $jumlah;
                    $jumlah = 0;
                    StokProdukDetail::where('norec', $items->norec)
                        ->where('kdprofile', $idProfile)
                        ->update(['qtyproduk' => (float)$saldoakhir]);
                }

                //## RegisterAsset
                RegistrasiAset::where('norec', $request['strukkirimaset']['norecAsset'])
                ->where('kdprofile', $idProfile)
                    ->update(['objectruanganposisicurrentfk' => $request['strukkirimaset']['objectruangantujuanfk']]);
            }
            
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan Kirim Asset Berhasil",
                'data' => '-',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Simpan Kirim Asset Gagal',
                'data' => $e->getMessage(),
            );
        }

        return $this->respond($result,$result['status'],$result['message']);

    }
}
