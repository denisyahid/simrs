<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\Jabatan;
use App\Models\Master\Profile;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\RiwayatRealisasi;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananDetail;
use App\Models\Transaksi\StrukRealisasi;
use App\Models\Transaksi\StrukRetur;
use App\Traits\Valet;
use App\Models\Transaksi\StrukReturDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PemesananBarangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function ComboSPBB()
    {

        $pegawai = DB::table('loginuser_s as lu')
            ->JOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'lu.objectpegawaifk')->on('pg.kdprofile', '=', 'lu.kdprofile');
            })
            ->select('pg.id', 'pg.namalengkap')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('lu.id', $this->getUserId())
            ->get();


        $pegawaiPembuat = DB::table('pegawai_m')
            ->select('id', 'namalengkap')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();

        $koordinator = DB::table('jenisusulan_m')
            ->select('id', 'jenisusulan')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->get();
        
            $anggaran = DB::table('mataanggaran_m')
            ->select('id','namamataanggaran')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();

        $dataSumberDana = DB::table('asalproduk_m as lu')
            ->select('lu.id', 'lu.asalproduk')
            ->where('lu.statusenabled', true)
            ->get();

        $rekanan = DB::table('rekanan_m')
            ->select('id', 'namarekanan')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();

        $pengendali = DB::table('pengendali_m')
        ->select('id', 'pengendali')
        ->where('kdprofile', $this->kdProfile)
        ->where('statusenabled', true)
        ->get();

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.kdproduk', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
            ->where('pr.statusenabled', true)
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('spd.qtyproduk', '>', 0)
            ->groupBy('pr.id', 'pr.kdproduk', 'pr.namaproduk', 'ssid', 'ss.satuanstandar')
            ->orderBy('pr.namaproduk')
            ->get();

  
        

        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $this->kdProfile)
            ->where('ks.statusenabled', true)
            ->get();

        $dataProdukResult = [];
        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk  as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $satuanKonversi[] = array(
                        'proid' => $item2->objekprodukfk,
                        'ssid' =>   $item2->satuanstandar_tujuan,
                        'satuanstandar' =>   $item2->satuanstandar2,
                        'nilaikonversi' =>   $item2->nilaikonversi,
                    );
                }
            }

            $dataProdukResult[] = array(
                'id' =>   $item->id,
                'namaproduk' =>   $item->namaproduk,
                'kdsirs' => $item->kdproduk,
                'kdproduk' => $item->kdproduk,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
            );
        }

        $result = [
            'pegawai' => $pegawai,
            'suplier' => $rekanan,
            'produk' => $dataProduk,
            'sumberdana' => $dataSumberDana,
            'koordinator' => $koordinator,
            'pembuatkomit' => $pegawaiPembuat,
            'produk' => $dataProdukResult,
            'anggaran' => $anggaran,
            'pengendali' => $pengendali
        ];

        return $this->respond($result);
    }


    public function savePemesananBarang (Request $request) {

        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $kdSPPB = $this->settingFix('kdSPPB');

        DB::beginTransaction();
        $noOrder =  $request['strukorder']['noorder'];
        $alamat='';
        if ($request['strukorder']['alamat'] != ''){
            $alamat=$request['strukorder']['alamat'];
        }
        try{
            foreach ($request['details'] as $item) {
                if($item['norec_op'] != null) {
                OrderPelayanan::where('norec', $item['norec_op'])
                        ->update([
                            'qtyprodukkonfirmasi' => $item['jumlah'],
                            'hargasatuanquo'=> $item['hargaProduk'],
                            'hargadiscountquo' => $item['hargadiskon'],
                            'hargappnquo' => $item['nilaippn']
                        ]);
                }
            }
            if ($request['strukorder']['norec'] == '') {
                $prefix = '/RSAB-SPPB' . '/' . $this->KonDecRomawi($this->getDateTime()->format('m')) . '/' . $this->getDateTime()->format('y');
                $resultr = StrukOrder::where('noorder', 'ILIKE', '%' . $prefix)->max('noorder');
                $subPrefix = str_replace($prefix, '', $resultr);
                //$noOrder = (str_pad((int)$subPrefix + 1, 3, "0", STR_PAD_LEFT)) . '' . $prefix;
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;
                $dataSO->isdelivered = 0;
            }else {
                $dataSO = StrukOrder::where('norec', $request['strukorder']['norec'])->first();
                $noStruk = $dataSO->nostruk;

                $delSPD = OrderPelayanan::where('strukorderfk', $request['strukorder']['norec'])
                    ->delete();
            }
            $dataSO->noorder = $noOrder;
            $dataSO->keteranganorder = $request['strukorder']['keteranganorder'];
            $dataSO->objectpegawaiorderfk = $request['strukorder']['pegawaiorderfk'];
            $dataSO->qtyjenisproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->qtyproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->namarekanansales = $request['strukorder']['namarekanansales'];
            $dataSO->objectrekanansalesfk = $request['strukorder']['objectrekananfk'];
            $dataSO->tglorder = $request['strukorder']['tglorder'];
            $dataSO->statusorder = 0;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = $request['strukorder']['totalhargasatuan'];
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            // $dataSO->totalhargasatuan = $request['strukorder']['total'];
            $dataSO->alamat = $alamat;
            $dataSO->alamattempattujuan = $request['strukorder']['notelpmobile'];
            $dataSO->keteranganlainnya = $request['strukorder']['koordinator'];
            $dataSO->jenisusulanfk = $request['strukorder']['koordinatorid'];
            $dataSO->tglvalidasi = $request['strukorder']['tglusulan'];
            $dataSO->noorderintern = $request['strukorder']['nousulan'];
            $dataSO->keterangankeperluan = $request['strukorder']['namapengadaan'];
            $dataSO->nokontrakspk = $request['strukorder']['nokontrak'];
            $dataSO->noorderrfq = $request['strukorder']['tahunusulan'];
            $dataSO->nourutlogin = $request['strukorder']['jmlHari'];
            if (isset($request['strukorder']['objectmataanggaranfk']) || $request['strukorder']['objectmataanggaranfk'] != ""){
                $dataSO->objectmataanggaranfk = $request['strukorder']['objectmataanggaranfk'];
            }
            $dataSO->objectkelompoktransaksifk = $kdSPPB;
            $dataSO->save();
            $dataSO = $dataSO->norec;

            foreach ($request['details'] as $item) {
                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->hasilkonversi = $item['nilaikonversi'];
                $dataOP->iscito = 0;
                $dataOP->noorderfk = $dataSO;
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->objectasalprodukfk = $request['strukorder']['asalprodukfk'];
                $dataOP->qtyproduk=$item['jumlah'];
                $dataOP->qtyprodukkonfirmasi = $item['qtyprodukkonfirmasi'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectsatuanstandarfk = $item['satuanstandarfk'];
                $dataOP->strukorderfk = $dataSO;
                $dataOP->tglpelayanan = $request['strukorder']['tglorder'];
                $dataOP->hargasatuan = $item['hargaProduk'];
                $dataOP->hargadiscount = $item['hargadiskon'];
                $dataOP->hargappn = $item['nilaippn'];
                $dataOP->save();
            }

            //***** Struk Realisasi *****
            $norecSR = '';
            $norecso='';
            if ($request['strukorder']['norecrealisasi'] == '') {
                $dataSR= new StrukRealisasi();
                $norealisasi = $this->generateCode(new StrukRealisasi(),'norealisasi',10,'RA-'.$this->getDateTime()->format('ym'), $idProfile);
                $dataSR->norec = $dataSR->generateNewId();
                $dataSR->kdprofile = $idProfile;
                $dataSR->statusenabled = true;
                $dataSR->norealisasi = $norealisasi;
                $dataSR->tglrealisasi = $request['strukorder']['tglorder'];
                $dataSR->totalbelanja = $request['strukorder']['totalhargasatuan'];
                if (isset($request['strukorder']['objectmataanggaranfk']) || $request['strukorder']['objectmataanggaranfk'] != ""){
                    $dataSR->objectmataanggaranfk = $request['strukorder']['objectmataanggaranfk'];
                }
                $dataSR->save();
                $dataSR=$dataSR->norec;

                if ($request['strukorder']['norecrealisasi'] == null) {
                    $norecSR = $dataSR;
                }else{
                    $norecSR = $request['strukorder']['norecrealisasi'];
                }

            }else {
                $dataSR = StrukRealisasi::where('norec', $request['strukorder']['norecrealisasi'])->first();
                $dataSR->tglrealisasi = $request['strukorder']['tglorder'];
                $dataSR->totalbelanja = $request['strukorder']['totalhargasatuan'];
                $dataSR->save();

                if ($request['strukorder']['norecrealisasi'] == null) {
                    $norecSR = $dataSR;
                }else{
                    $norecSR = $request['strukorder']['norecrealisasi'];
                }
            }

            if ($request['strukorder']['norec'] == null) {
                $norecso = $dataSO;
            }else{
                $norecso = $request['strukorder']['norec'];
            }

            //***** Riwayat Realisasi *****
            if ($request['strukorder']['norecrealisasi'] != '' || $dataSR != '') {
                $dataRR= new RiwayatRealisasi();
                $dataRR->norec = $dataRR->generateNewId();
                $dataRR->kdprofile = $idProfile;
                $dataRR->statusenabled = true;
                $dataRR->objectkelompoktransaksifk = 88;
            }else {
                $dataRR = RiwayatRealisasi::where('objectstrukrealisasifk', $request['strukorder']['norecrealisasi'])->first();
            }
            $dataRR->objectstrukrealisasifk = $norecSR;
            $dataRR->objectstrukfk = $norecso;
            $dataRR->sppbfk = $dataSO;
            $dataRR->tglrealisasi = $request['strukorder']['tglorder'];
            $dataRR->objectpetugasfk = $request['strukorder']['pegawaiorderfk'];
            $dataRR->noorderintern = $request['strukorder']['nousulan'];
            $dataRR->keteranganlainnya = $request['strukorder']['keteranganorder'];
            $dataRR->save();

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null,
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDaftarSPPB(Request $request) {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
       
        $data = DB::table('strukorder_t as sp')
            ->JOIN('orderpelayanan_t as op','op.noorderfk','=','sp.norec')
            ->LEFTJOIN('pegawai_m as pg','pg.id','=','sp.objectpegawaiorderfk')
            ->LEFTJOIN('rekanan_m as rkn','rkn.id','=','sp.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg2','pg2.id','=','sp.objectpetugasfk')
            ->LEFTJOIN('ruangan_m as ru','ru.id','=','sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru2','ru2.id','=','sp.objectruangantujuanfk')
            ->select('sp.norec','sp.tglorder','sp.noorder','pg.namalengkap',
                'sp.alamat','sp.alamattempattujuan','sp.keteranganlainnya','sp.tglvalidasi','sp.noorderintern',
                'sp.keterangankeperluan','sp.nokontrakspk','sp.noorderrfq','sp.keteranganorder','rkn.namarekanan','rkn.id as rknid',
                'sp.namarekanansales','sp.totalhargasatuan','sp.status','ru.namaruangan as ruangan','ru.id as ruid',
                'ru2.namaruangan as ruangantujuan','ru2.id as ruidtujuan','pg2.namalengkap as mengetahui','sp.qtyproduk', 'sp.objectrekanansalesfk'
            )
            ->where('sp.kdprofile', $idProfile);

        if(isset($request['tglAwal']) && $request['tglAwal']!="" && $request['tglAwal']!="undefined"){
            $data = $data->where('sp.tglorder','>=', $request['tglAwal']);
        }
        if(isset($request['tglAkhir']) && $request['tglAkhir']!="" && $request['tglAkhir']!="undefined"){
            $tgl= $request['tglAkhir'];
            $data = $data->where('sp.tglorder','<=', $tgl);
        }
        if(isset($request['noorder']) && $request['noorder']!="" && $request['noorder']!="undefined"){
            $data = $data->where('sp.noorder','ILIKE','%'. $request['noorder']);
        }
        if(isset($request['noKontrak']) && $request['noKontrak']!="" && $request['noKontrak']!="undefined"){
            $data = $data->where('sp.nokontrakspk','ILIKE','%'. $request['noKontrak']);
        }
        if(isset($request['keterangan']) && $request['keterangan']!="" && $request['keterangan']!="undefined"){
            $data = $data->where('sp.keteranganorder','ILIKE','%'. $request['keterangan']);
        }
        // if(isset($request['rekanan']) && $request['rekanan']!="" && $request['rekanan']!="undefined"){
        //     $data = $data->where('sp.objectrekananfk','ILIKE','%'. $request['rekanan']);
        // }
        if(isset($request['rekanan']) && $request['rekanan']!="" && $request['rekanan']!="undefined"){
            $data = $data->where('sp.objectrekanansalesfk','=',$request['rekanan']);
        }
        if(isset($request['produkfk']) && $request['produkfk']!="" && $request['produkfk']!="undefined"){
            $data = $data->where('op.objectprodukfk','=',$request['produkfk']);
        }

        $data = $data->distinct();
        $data = $data->where('sp.statusenabled',true);
        $data = $data->whereIn('sp.objectkelompoktransaksifk',[88,89]);
        $data = $data->orderBy('sp.noorder');
        $data = $data->get();

        $results =array();
        foreach ($data as $item){
            $details = DB::select(DB::raw("select  pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.qtyterimalast,spd.hargasatuan,spd.hargadiscount,spd.hargappn,
                    ((spd.qtyproduk*spd.hargasatuan)+spd.hargappn-spd.hargadiscount) as total,
                    (spd.qtyprodukkonfirmasi*(spd.hargasatuanquo)) as totalkonfirmasi,
                    spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid,
                    spd.hargasatuanquo,spd.qtyprodukkonfirmasi
                     from orderpelayanan_t as spd 
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $idProfile and strukorderfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );

            $results[] = array(
                'tglorder' => $item->tglorder,
                'noorder' => $item->noorder,
                'norec' => $item->norec,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganorder,
                'alamat' => $item->alamat,
                'rknid' => $item->objectrekanansalesfk,
                'telp' => $item->alamattempattujuan,
                'koordinator' => $item->keteranganlainnya,
                'tglusulan' => $item->tglvalidasi,
                'nousulan' => $item->noorderintern,
                'namapengadaan' => $item->keterangankeperluan,
                'nokontrak' => $item->nokontrakspk,
                'tahunusulan' => $item->noorderrfq,
                'namarekanan' => $item->namarekanansales,
                'totalhargasatuan' => $item->totalhargasatuan,
                'ruangan' => $item->ruangan,
                'ruangantujuan' => $item->ruangantujuan,
                'mengetahui' => $item->mengetahui,
                'status' => $item->status,
                'jmlitem' => $item->qtyproduk,
                'details' => $details,

            );
        }

        $result = array(
            'daftar' => $results,
            'datalogin' => $dataLogin,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getDetailDataSPPB(Request $request) {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataReq = $request->all();
        $dataStruk = DB::table('strukorder_t as sp')
            ->LEFTJOIN('riwayatrealisasi_t as rr','rr.objectstrukfk','=','sp.norec')
            ->LEFTJOIN('riwayatrealisasi_t as rr2','rr2.sppbfk','=','sp.norec')
            ->LEFTJOIN('strukrealisasi_t as sr','sr.norec','=','rr.objectstrukrealisasifk')
            ->LEFTJOIN('strukrealisasi_t as sr2','sr2.norec','=','rr2.objectstrukrealisasifk')
            ->LEFTJOIN('pegawai_m as pg','pg.id','=','sp.objectpegawaiorderfk')
            ->LEFTJOIN('pegawai_m as pg1','pg1.id','=','sp.objectpetugasfk')
            ->LEFTJOIN('rekanan_m as rkn','rkn.id','=','sp.objectrekananfk')
            ->LEFTJOIN('rekanan_m as rkn1','rkn1.id','=','sp.objectrekanansalesfk')
            ->LEFTJOIN('mataanggaran_t as ma','ma.norec','=','sr.objectmataanggaranfk')
            ->LEFTJOIN('mataanggaran_t as ma1','ma1.norec','=','sr2.objectmataanggaranfk')
            ->LEFTJOIN('ruangan_m as ru','ru.id','=','sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru1','ru1.id','=','sp.objectruangantujuanfk')
            ->select('sp.norec','sp.tglorder','sp.noorder','pg.namalengkap','pg.id as pgid','pg1.nippns',
                'sp.alamat','sp.alamattempattujuan','sp.keteranganlainnya','sp.tglvalidasi','sp.noorderintern',
                'sp.keterangankeperluan','sp.nokontrakspk','sp.noorderrfq','sp.keteranganorder','rkn.namarekanan','rkn.id as rknid',
                'sp.namarekanansales','sp.totalhargasatuan','sp.objectpetugasfk','pg1.namalengkap as mengetahui','pg1.nippns',
                'rkn1.namarekanan as namarekanansales','sp.objectrekanansalesfk','rkn.alamatlengkap as alamatrekanan',
                'rkn1.alamatlengkap as alamatrekanansales','rkn.faksimile as faxrekanan','rkn.telepon as tlprekanan',
                'rkn1.faksimile as faxrekanansales','rkn1.telepon as tlprekanansales','sr.norealisasi','sr.norec as norecrealisasi',
                'rr.norec as norecrrusulan','sr2.norec as norecrealisasisppb','rr2.norec as norecrrsppb','sr.objectmataanggaranfk as mataanggranid','ma.mataanggaran',
                'sr2.objectmataanggaranfk as mataanggranfk','ma1.mataanggaran as mataanggaransppb','ru.id as idunitpengusul','ru.namaruangan as unitpengusul',
                'ru1.id as idunittujuan','ru1.namaruangan as unittujuan','sp.jenisusulanfk',
                DB::raw('EXTRACT (YEAR from sp.tglorder) AS tahunusulan'))
            ->where('sp.kdprofile', $idProfile);

        if(isset($request['norecOrder']) && $request['norecOrder']!="" && $request['norecOrder']!="undefined"){
            $dataStruk = $dataStruk->where('sp.norec','=', $request['norecOrder']);
        }
        $dataStruk = $dataStruk->first();

        $detail = array(
            'tglorder' => $dataStruk->tglorder,
            'noorder' => $dataStruk->noorder,
            'norec' => $dataStruk->norec,
            'petugasid' => $dataStruk->pgid,
            'petugas' => $dataStruk->namalengkap,
            'petugasmengetahui'=> $dataStruk->mengetahui,
            'petugasmengetahuiid' => $dataStruk->objectpetugasfk,
            'nippns' => $dataStruk->nippns,
            'keterangan' => $dataStruk->keteranganorder,
            'alamat' => $dataStruk->alamat,
            'telp' => $dataStruk->alamattempattujuan,
            'koordinator' => $dataStruk->keteranganlainnya,
            'tglusulan' => $dataStruk->tglvalidasi,
            'nousulan' => $dataStruk->noorderintern,
            'namapengadaan' => $dataStruk->keterangankeperluan,
            'nokontrak' => $dataStruk->nokontrakspk,
            'tahunusulan' => $dataStruk->tahunusulan,
            'namarekananid' => $dataStruk->rknid,
            'namarekanan' => $dataStruk->namarekanan,
            'alamatrekanan' => $dataStruk->alamatrekanan,
            'faxrekanan'=> $dataStruk->faxrekanan,
            'tlprekanan'=> $dataStruk->tlprekanan,
            'rekanansalesfk'=> $dataStruk->objectrekanansalesfk,
            'namarekanansales'=> $dataStruk->namarekanansales,
            'alamatrekanansales'=>$dataStruk->alamatrekanansales,
            'faxrekanansales'=> $dataStruk->faxrekanansales,
            'tlprekanansales'=> $dataStruk->tlprekanansales,
            'totalhargasatuan' => $dataStruk->totalhargasatuan,
            'norealisasi'=>$dataStruk->norealisasi,
            'norecrealisasi'=>$dataStruk->norecrealisasi,
            'norecrealisasisppb'=>$dataStruk->norecrealisasisppb,
            'norecrrusulan'=>$dataStruk->norecrrusulan,
            'norecrrsppb'=>$dataStruk->norecrrsppb,
            'norecrrsppb'=>$dataStruk->norecrrsppb,
            'mataanggranid'=>$dataStruk->mataanggranid,
            'mataanggaran'=>$dataStruk->mataanggaran,
            'mataanggranfk'=>$dataStruk->mataanggranfk,
            'mataanggaransppb'=>$dataStruk->mataanggaransppb,
            'idunitpengusul' =>$dataStruk->idunitpengusul,
            'unitpengusul' =>$dataStruk->unitpengusul,
            'idunittujuan' =>$dataStruk->idunittujuan,
            'unittujuan' =>$dataStruk->unittujuan,
            'jenisusulanfk' =>$dataStruk->jenisusulanfk
        );

        $i = 0;
        $dataStok = $details = DB::select(DB::raw("
                    select spd.norec as norec_op, pr.id as produkfk,pr.namaproduk,spd.objectrekananfk,rek.namarekanan,pr.kdproduk,
                    ss.satuanstandar,ss.id as ssid,spd.qtyproduk,spd.qtyterimalast,spd.hargasatuan,spd.deskripsiprodukquo,
                    spd.hargasatuanquo,spd.qtyprodukkonfirmasi,spd.hargadiscountquo,spd.hargappnquo,spd.hargadiscount,
                    CASE WHEN spd.hargappn = 10 THEN (spd.hargasatuan*spd.hargappn)/100 ELSE spd.hargappn END AS hargappn,
                    sb.name as statusbarang,
                    spd.qtyproduk*spd.hargasatuan as subtotal,
                    (spd.qtyproduk*(spd.hargasatuan+CASE WHEN spd.hargappn = 10 THEN (spd.hargasatuan*spd.hargappn)/100 ELSE spd.hargappn END-spd.hargadiscount)) as total,
                    (spd.qtyproduk*(spd.hargasatuanquo-spd.hargadiscountquo+spd.hargappnquo)) totalkonfirmasi,
                    (spd.qtyprodukkonfirmasi*(spd.hargasatuanquo)) as totalkonfirmasiss,
                    spd.hasilkonversi,spd.noorderfk,spd.objectasalprodukfk,ap.id as apid,ap.asalproduk,
                    spd.tglpelayananakhir as tglkebutuhan,spd.qtyterimalast
                    from orderpelayanan_t as spd 
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    left JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                    left JOIN rekanan_m as rek on rek.id=spd.objectrekananfk
                    left JOIN status_barang_m as sb on sb.id = spd.objectstatusbarang
                    where spd.kdprofile = $idProfile and spd.noorderfk=:norec"),
            array(
                'norec' => $request['norecOrder'],
            )
        );
        $jmlstok=0;
        $details=[];
        foreach ($dataStok as $item){
            $i = $i+1;
            if ($item->qtyterimalast == null){
                $qtyterima = 0;
            }else{
                $qtyterima = (float)$item->qtyterimalast;
            }
            if ((float)$item->qtyproduk - $qtyterima > 0){
                $details[] = array(
                    'no' => $i,
                    'kdproduk' => $item->kdproduk,
                    'produkfk' => $item->produkfk,
                    'norec_op' => $item->norec_op,
                    'namaproduk' => $item->namaproduk,
                    'namarekanan' => $item->namarekanan,
                    'rekananfk' =>$item->objectrekananfk,
                    'nilaikonversi' => $item->hasilkonversi,
                    'satuanstandarfk' => $item->ssid,
                    'satuanstandar' => $item->satuanstandar,
                    'satuanviewfk' => $item->ssid,
                    'satuanview' => $item->satuanstandar,
                    'spesifikasi' => $item->deskripsiprodukquo,
                    'jmlstok' => (float)$jmlstok,
                    'jumlahsppb' =>(float)$item->qtyproduk,
                    'jumlahterima' => (float)$qtyterima,
                    'jumlah' => (float)$item->qtyproduk - (float)$qtyterima,
                    'hargasatuan' => (float)$item->hargasatuan,
                    'hargasatuanquo' => (float)$item->hargasatuanquo,
                    'hargadiscountquo' => (float)$item->hargadiscountquo,
                    'hargappnquo' => (float)$item->hargappnquo,
                    'qtyprodukkonfirmasi' => (float)$item->qtyprodukkonfirmasi,
                    'qtyterima' => (float)$item->qtyterimalast,
                    'hargasatuankonfirmasi' => (float)$item->hargasatuanquo,
                    'totalkonfirmasi' => (float)$item->totalkonfirmasi,
                    'totalkonfirmasiss'=> (float)$item->totalkonfirmasiss,
                    'hargadiscount' => (float)$item->hargadiscount,
                    'ppn' => (float)$item->hargappn,
                    'subtotal' => (float)$item->subtotal ,
                    'total' => (float)$item->total ,
                    'ruanganfk'=> 50 ,
                    'asalprodukfk'=> $item->apid ,
                    'asalproduk'=> $item->asalproduk ,
                    'persendiscount'=> 0 ,
                    'persenppn'=> 0 ,
                    'keterangan'=> '',
                    'nobatch'=> '',
                    'statusbarang'=> $item->statusbarang,
                    'tglkadaluarsa'=> null,
                    'tglkebutuhan'=> $item->tglkebutuhan,
                );
            }
        }

        $result = array(
            'detail' => $detail,
            'details' => $details,
            'datalogin' => $dataReq,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }


   

    // public function cetakBuktiPenerimaanBarang(Request $request)
    // {
    //     $kdProfile = $request['kdprofile'];
    //     $norec = $request['norec'];
    //     $tglAyeuna = date('d/m/Y');
    //     $tglAyeuna = date('Y-m-d H:i:s');
    //     $print = false;
    //     $profile = Profile::where('id', $this->kdProfile)->first();
    //     $data = collect(DB::select("
    //             select  sp.nostruk,sp.nofaktur,to_char(sp.tglstruk, 'DD-MM-YYYY') as tglstruk,sp.tglspk,
    //                     to_char(sp.tglfaktur, 'DD-MM-YYYY') as tglfaktur,to_char(sp.tglkontrak, 'DD-MM-YYYY') as tglkontrak,
    //                     to_char(sr.tglrealisasi, 'DD-MM-YYYY') as tglrealisasi, case when ap.asalproduk is null then '-' else ap.asalproduk end as asalproduk,
    //                     case when sp.totaldiscount is null then 0 else (sp.totaldiscount * 100) / sp.totalhargasatuan end as persendiskon,
    //                     case when sp.totalppn is null then 0 else (sp.totalppn * 100) / sp.totalhargasatuan end as persenppn,
    //                     case when rk.namarekanan is null then '-' else rk.namarekanan end as rekanan,
    //                     pr.id as idproduk, pr.namaproduk,
    //                     ss.satuanstandar, sp.totalharusdibayar,
    //                     (spd.hargasatuan - spd.hargadiscount + spd.hargappn) as harga, spd.qtyproduk,
    //                     case when ru.namaruangan is null then '-' else ru.kdruangan  ||  ' - '  ||  ru.namaruangan end as gudang,sp.keteranganambil,
    //                     case when sp.nokontrak is null then '-' else sp.nokontrak end as nokontrak,case when sp.nosppb is null then '-' else sp.nosppb end as nosppb,
    //                     sp.keteranganambil,spd.qtyproduk*(spd.hargasatuan - spd.hargadiscount + spd.hargappn) as subtotal,
    //                     CASE WHEN spd.hargappn IS NULL THEN 0 ELSE spd.hargappn END AS ppn,
    //                     CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END AS diskon,
    //                     CAST (((spd.hargasatuan- spd.hargadiscount) * spd.qtyproduk) + spd.hargappn AS FLOAT) AS total
    //             from strukpelayanan_t sp
    //             left join strukpelayanandetail_t spd on spd.nostrukfk=sp.norec
    //             left JOIN pegawai_m pg on pg.id=sp.objectpegawaipenanggungjawabfk
    //             left JOIN ruangan_m ru on ru.id=sp.objectruanganfk
    //             left JOIN produk_m pr on pr.id=spd.objectprodukfk
    //             left join asalproduk_m as ap on ap.id=spd.objectasalprodukfk
    //             left join rekanan_m rk on rk.id=sp.objectrekananfk
    //             left JOIN jeniskemasan_m jkm on jkm.id=spd.objectjeniskemasanfk
    //             left join satuanstandar_m ss on ss.id=spd.objectsatuanstandarfk
    //             left join riwayatrealisasi_t as rr on rr.penerimaanfk = sp.norec
    //             left join strukrealisasi_t as sr on sr.norec = rr.objectstrukrealisasifk
    //             where sp.norec = '$norec'
    //             GROUP BY sp.nostruk,sp.nofaktur,sp.tglstruk,sp.tglspk,tglfaktur,sp.tglkontrak,ap.asalproduk,rk.kdrekanan,rk.namarekanan,ru.kdruangan,
    //                     ru.namaruangan,sp.tglstruk,sp.keteranganambil,sp.totaldiscount,sp.totalhargasatuan,sp.totalppn,pr.id,ss.satuanstandar,sp.totalharusdibayar,
    //                     spd.hargasatuan,spd.hargadiscount,spd.hargappn,pr.namaproduk,spd.qtyproduk,sp.nokontrak,sp.nosppb,sr.tglrealisasi
    //     "))->first();

    //     $detail = DB::table('strukpelayanan_t as sp')
    //         ->leftJoin('strukpelayanandetail_t as spd', 'spd.nostrukfk', 'sp.norec')
    //         ->leftJoin('pegawai_m as pg', 'pg.id', 'sp.objectpegawaipenanggungjawabfk')
    //         ->leftJoin('ruangan_m as ru', 'ru.id', 'sp.objectruanganfk')
    //         ->leftJoin('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
    //         ->leftJoin('asalproduk_m as ap', 'ap.id', 'spd.objectasalprodukfk')
    //         ->leftJoin('rekanan_m as rk', 'rk.id', 'sp.objectrekananfk')
    //         ->leftJoin('jeniskemasan_m as jkm', 'jkm.id', 'spd.objectjeniskemasanfk')
    //         ->leftJoin('satuanstandar_m as ss', 'ss.id', 'spd.objectsatuanstandarfk')
    //         ->leftJoin('riwayatrealisasi_t as rr', 'rr.penerimaanfk', 'sp.norec')
    //         ->leftJoin('strukrealisasi_t as sr', 'sr.norec', 'rr.objectstrukrealisasifk')
    //         ->select(
    //             'pr.namaproduk',
    //             'pr.id as produkfk',
    //             'ss.satuanstandar',
    //             'spd.qtyproduk',
    //             'spd.hasilkonversi',
    //             'spd.qtyprodukretur',
    //             'spd.hargasatuan',
    //             'spd.hargadiscount as hargadiskon',
    //             'spd.hargappn',
    //             'spd.persendiscount',
    //             'spd.persenppn',
    //             DB::raw("CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+
    //                             (spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*
    //                             spd.qtyproduk))/100) AS FLOAT) AS totalall"),
    //             'spd.tglkadaluarsa',
    //             'spd.keteranganlainnya',
    //             'spd.nobatch',
    //             DB::raw("CAST(spd.qtyproduk*spd.hargasatuan as float) as subtotal")
    //         )
    //         ->where('sp.norec', $norec)
    //         ->get();

    //     $pageWidth = 950;
    //     $pegawaiMegetahui = "-";
    //     $nippegawaiMegetahui = "NIP. -";
    //     if (isset($request['pegawaiMengetahui']) && $request['pegawaiMengetahui'] != "") {
    //         $idPegMeg = (int) $request['pegawaiMengetahui'];
    //         $dataPegMeng = collect(DB::select("
    //             select pg.namalengkap,pg.nip_pns,jb.namajabatan
    //             from pegawai_m as pg
    //             left join jabatan_m as jb on jb.id = pg.objectjabatanstrukturalfk
    //             where pg.id = $idPegMeg
    //        "))->first();

    //         if (!empty($dataPegMeng)) {
    //             $pegawaiMegetahui = $dataPegMeng->namalengkap;
    //             $nippegawaiMegetahui = "NIP. " . $dataPegMeng->nip_pns;
    //         }
    //     }

    //     $pegawaiPenerima = "-";
    //     $nippegawaiPenerima = "NIP. -";
    //     if (isset($request['pegawaiPenerima']) && $request['pegawaiPenerima'] != "") {
    //         $idPegMeg2 = (int) $request['pegawaiPenerima'];
    //         $dataPegMeng2 = collect(DB::select("
    //             select pg.namalengkap,pg.nip_pns,jb.namajabatan
    //             from pegawai_m as pg
    //             left join jabatan_m as jb on jb.id = pg.objectjabatanstrukturalfk
    //             where pg.id = $idPegMeg2
    //        "))->first();

    //         if (!empty($dataPegMeng2)) {
    //             $pegawaiPenerima = $dataPegMeng2->namalengkap;
    //             $nippegawaiPenerima = "NIP. " . $dataPegMeng2->nip_pns;
    //         }
    //     }

    //     $pegawaiPenyerah = "-";
    //     $nippegawaiPenyerah = "NIP. -";
    //     if (isset($request['pegawaiMeminta']) && $request['pegawaiMeminta'] != "") {
    //         $idPegMeg3 = (int) $request['pegawaiMeminta'];
    //         $dataPegMeng3 = collect(DB::select("
    //             select pg.namalengkap,pg.nip_pns,jb.namajabatan
    //             from pegawai_m as pg
    //             left join jabatan_m as jb on jb.id = pg.objectjabatanstrukturalfk
    //             where pg.id = $idPegMeg3
    //        "))->first();

    //         if (!empty($dataPegMeng3)) {
    //             $pegawaiPenyerah = $dataPegMeng3->namalengkap;
    //             $nippegawaiPenyerah = "NIP. " . $dataPegMeng3->nip_pns;
    //         }
    //     }

    //     $dataReport = array(
    //         'datas' => $data,
    //         'detail' => $detail,
    //         'tanggal' => $tglAyeuna,
    //         'judul' => 'BUKTI PENERIMAAN BARANG',
    //         'pegawaimengetahui' => $pegawaiMegetahui,
    //         'nipmengetahui' => $nippegawaiMegetahui,
    //         'pegawaipenerima' => $pegawaiPenerima,
    //         'nippenerima' => $nippegawaiPenerima,
    //         'pegawaipenyerah' => $pegawaiPenyerah,
    //         'nippenyerah' => $nippegawaiPenyerah
    //     );

    //     $res['pdf']  = false;

    //     $blade = 'report.logistik.bukti-penerimaan';
    //     if ($res['pdf']) {
    //         $pdf = App::make('dompdf.wrapper');
    //         $pdf->loadView(
    //             $blade,
    //             array(
    //                 'profile' => $profile,
    //                 'pageWidth' => $pageWidth,
    //                 'print' => $print,
    //                 'res' => $res,
    //             )
    //         );
    //         return $pdf->stream();
    //     }

    //     return view(
    //         $blade,
    //         compact('dataReport', 'profile', 'pageWidth', 'print', 'res')
    //     );
    // }

    // public function SaveReturPenerimaan(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         if ($request['struk']['norecRetur'] == '') {
    //             $newSRetur = new StrukRetur();
    //             $norecSRetur = $newSRetur->generateNewId();
    //             $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
    //             $newSRetur->norec = $norecSRetur;
    //             $newSRetur->kdprofile = $this->kdProfile;
    //             $newSRetur->statusenabled = true;
    //             $newSRetur->objectkelompoktransaksifk = $this->settingFix("KelompokTransaksiReturSupplier");
    //         } else {
    //             $newSRetur =  StrukRetur::where('norec', $request['struk']['norecRetur'])->where('kdprofile', $this->kdProfile)->first();
    //             StrukReturDetail::where('strukreturfk', $request['struk']['norecRetur'])->where('kdprofile', $this->kdProfile)->delete();
    //         }
    //         $newSRetur->keteranganalasan = $request['struk']['keterangan'];
    //         $newSRetur->keteranganlainnya = 'Retur  Penerimaan ' . ' Dari Ruangan  ' . $request['struk']['namaruangan']  . ' Dengan No Terima:  ' . $request['struk']['noterima'] . '  Ke Supplier  ' . $request['struk']['namarekanan'];
    //         $newSRetur->noretur = $noRetur;
    //         $newSRetur->objectruanganfk = $request['struk']['ruanganfk'];
    //         $newSRetur->objectpegawaifk = $request['struk']['pegawaimenerimafk'];
    //         $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
    //         $newSRetur->strukterimafk = $request['struk']['nostruk'];
    //         $newSRetur->save();
    //         $norecRetur = $newSRetur->norec;

    //         foreach ($request['details'] as $item) {

    //             $noTarimakeun =  $request['struk']['nostruk'];

    //             $tambah = StokProdukDetail::where('nostrukterimafk', $noTarimakeun)
    //                 ->where('kdprofile', $this->kdProfile)
    //                 ->where('objectruanganfk', $request['struk']['ruanganfk'])
    //                 ->where('objectprodukfk', $item['produkfk'])
    //                 ->first();
    //             //## StrukReturDetail
    //             $StokPD = new StrukReturDetail();
    //             $norecStokPD = $StokPD->generateNewId();
    //             $StokPD->norec = $norecStokPD;
    //             $StokPD->kdprofile = $this->kdProfile;
    //             $StokPD->statusenabled = true;
    //             $StokPD->objectasalprodukfk = $item['asalprodukfk'];
    //             $StokPD->hargadiscount = 0;
    //             $StokPD->harganetto1 = (float)$tambah->harganetto1;
    //             $StokPD->harganetto2 = (float)$tambah->harganetto2;
    //             $StokPD->persendiscount = 0;
    //             $StokPD->objectprodukfk = $item['produkfk'];
    //             $StokPD->qtyproduk = (float)$item['qtyprodukretur'];
    //             $StokPD->qtyprodukonhand = 0;
    //             $StokPD->qtyprodukoutext = 0;
    //             $StokPD->qtyprodukoutint = 0;
    //             $StokPD->nostrukterimafk = $noTarimakeun;
    //             $StokPD->strukreturfk = $norecRetur;
    //             $StokPD->tglkadaluarsa = $tambah->tglkadaluarsa;
    //             $StokPD->save();
    //             //PENERIMA
    //             $dataSaldoAwalT = DB::select(
    //                 DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
    //                 where kdprofile = $this->kdProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
    //                 array(
    //                     'ruanganfk' => $request['struk']['ruanganfk'],
    //                     'produkfk' => $item['produkfk'],
    //                 )
    //             );

    //             $saldoAwalPenerima = 0;
    //             foreach ($dataSaldoAwalT as $items) {
    //                 $saldoAwalPenerima = (float)$items->qty;
    //             }

    //             $kurang = StokProdukDetail::where('nostrukterimafk', $noTarimakeun)
    //                 ->where('kdprofile', $this->kdProfile)
    //                 ->where('objectruanganfk', $request['struk']['ruanganfk'])
    //                 ->where('objectprodukfk', $item['produkfk'])
    //                 ->first();

    //             StokProdukDetail::where('norec', $kurang->norec)
    //                 ->where('kdprofile', $this->kdProfile)
    //                 ->update([
    //                     'qtyproduk' => (float)$kurang->qtyproduk - (float)$item['qtyprodukretur']
    //                 ]);

    //             StrukPelayananDetail::where('nostrukfk', $request['struk']['nostruk'])
    //                 ->where('kdprofile', $this->kdProfile)
    //                 ->where('objectprodukfk', $item['produkfk'])
    //                 ->update([
    //                     'qtyproduk' => (float)$kurang->qtyproduk - (float)$item['qtyprodukretur'],
    //                     'qtyprodukretur' => (float)$item['qtyprodukretur']
    //                 ]);

    //             //## KartuStok
    //             $this->kartu_STOK(array(
    //                 "saldoawal" => (float)$saldoAwalPenerima,
    //                 "qtyin" => 0,
    //                 "qtyout" => (float)$item['qtyprodukretur'],
    //                 "saldoakhir" => (float)$saldoAwalPenerima - (float)$item['qtyprodukretur'],
    //                 "keterangan" => 'Retur  Penerimaan ' . ' Dari Ruangan  ' . $request['struk']['namaruangan']  . ' Dengan No Terima:  ' . $request['struk']['noterima'] . '  Ke Supplier  ' . $request['struk']['namarekanan'],
    //                 "produkfk" => $item['produkfk'],
    //                 "ruanganfk" => $request['struk']['ruanganfk'],
    //                 "tglinput" => date('Y-m-d H:i:s'),
    //                 "tglkejadian" => date('Y-m-d H:i:s'),
    //                 "nostrukterimafk" => $noTarimakeun,
    //                 "norectransaksi" => $request['struk']['nostruk'],
    //                 "tabletransaksi" => 'strukpelayanan_t',
    //                 "stokprodukdetailfk" =>  $tambah->norec,
    //                 "flagfk" => null,
    //             ));
    //         }

    //         //## Logging User
    //         // $newId = LoggingUser::max('id');
    //         // $newId = $newId + 1;
    //         // $logUser = new LoggingUser();
    //         // $logUser->id = $newId;
    //         // $logUser->norec = $logUser->generateNewId();
    //         // $logUser->kdprofile = $this->kdProfile;
    //         // $logUser->statusenabled = true;
    //         // $logUser->jenislog = 'Batal Kirim';
    //         // $logUser->noreff = $request['struk']['nostruk'];
    //         // $logUser->referensi = 'norec Struk Terima';
    //         // $logUser->objectloginuserfk =  $dataLogin['userData']['id'];
    //         // $logUser->tanggal = $this->getDateTime()->format('Y-m-d H:i:s');
    //         // $logUser->keterangan = 'Retur  Penerimaan ' . ' Dari Ruangan  ' . $request['struk']['namaruangan']  . ' Dengan No Terima:  ' . $request['struk']['noterima'] . '  Ke Supplier  ' . $request['struk']['namarekanan'];
    //         // $logUser->save();
    //         DB::commit();
    //         $result = [
    //             "status" => 200,
    //             "noretur" => $norecRetur,
    //             "data" => $newSRetur,
    //             "message" => "Data berhasil diretur",
    //         ];
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         $result = [
    //             "status" => 400,
    //             "data" => $e->getMessage(),
    //             "message"  => 'Something Went Wrong',
    //         ];
    //     }
    //     return $this->respond($result, $result['status'], $result['message']);
    // }

    public function getRekananDetail(Request $request)
    {
        $data = DB::table('rekanan_m')
            ->select('telepon', 'alamatlengkap')
            ->where('id', $request->input('idrekanan'))
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', '=', 'true')
            ->first();

        return $this->respond($data);
    }
}
