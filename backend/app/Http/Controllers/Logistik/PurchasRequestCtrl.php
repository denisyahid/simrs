<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\RiwayatRealisasi;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukRealisasi;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PurchaseRequestCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function getDaftarPermintaanBarangRuangan(Request $request)
    {

        $idProfile = $this->kdProfile;
        $dataLogin = $request->all();
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
        ->select('ru.id')
        ->where('mlu.objectloginuserfk', $dataLogin['userData']['id'])
            ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $data = DB::table('strukorder_t as sp')
        ->JOIN('orderpelayanan_t as op', 'op.noorderfk', '=', 'sp.norec')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
        ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpetugasfk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
        ->LEFTJOIN('mataanggaran_t as ma', 'ma.norec', '=', 'sp.objectmataanggaranfk')
        ->LEFTJOIN('asalproduk_m as ap', 'ap.id', '=', 'op.objectasalprodukfk')
        ->select(DB::raw("sp.norec,sp.tglorder,sp.noorder,pg.namalengkap AS penanggungjawab,pg2.namalengkap AS mengetahui,
                    sp.tglvalidasi AS tglkebutuhan,sp.alamattempattujuan,sp.keteranganlainnya,sp.tglvalidasi,sp.noorderintern,
                    sp.keterangankeperluan,sp.keteranganorder,ru.namaruangan AS ruangan,ru.id AS ruid,ru2.namaruangan AS ruangantujuan,
                    ru2.id AS ruidtujuan,ma.norec AS mataanggaranfk,ma.mataanggaran,ap.id AS apid,ap.asalproduk,sp.totalhargasatuan,sp.statusorder as status"))
        ->where('sp.kdprofile', $idProfile);
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('sp.tglorder', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('sp.tglorder', '<=', $tgl);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('sp.noorder', 'ILIKE', '%' . $request['noorder']);
        }
        if (isset($request['noKontrak']) && $request['noKontrak'] != "" && $request['noKontrak'] != "undefined") {
            $data = $data->where('sp.nokontrakspk', 'ILIKE', '%' . $request['noKontrak']);
        }
        if (isset($request['keterangan']) && $request['keterangan'] != "" && $request['keterangan'] != "undefined") {
            $data = $data->where('sp.keteranganorder', 'ILIKE', '%' . $request['keterangan']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('op.objectprodukfk', '=', $request['produkfk']);
        }

        $data = $data->distinct();
        $data = $data->where('sp.statusenabled', true);
        $data = $data->where('sp.objectkelompoktransaksifk', $request['kelompoktransaksi']);
        $data = $data->orderBy('sp.tglorder');
        $data = $data->get();

        $results = array();
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("
                     select pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargadiscount,spd.hargappnquo,spd.hargadiscountquo,
                    (spd.qtyproduk*(spd.hargasatuan)) as total,
                    (spd.qtyprodukkonfirmasi*(spd.hargasatuanquo + hargappnquo - hargadiscountquo)) as totalkonfirmasi,
                    spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid,
                    spd.hargasatuanquo,spd.qtyprodukkonfirmasi,spd.qtyrequest,spd.qtyverifkabag,spd.qtyverifkeuangan
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
                'penanggungjawab' => $item->penanggungjawab,
                'keterangan' => $item->keteranganorder,
                'koordinator' => $item->keteranganlainnya,
                'tglkebutuhan' => $item->tglkebutuhan,
                'tglusulan' => $item->tglorder,
                'nousulan' => $item->noorderintern,
                'namapengadaan' => $item->keterangankeperluan,
                'mengetahui' => $item->mengetahui,
                'ruangan' => $item->ruangan,
                'ruangantujuan' => $item->ruangantujuan,
                'totalhargasatuan' => $item->totalhargasatuan,
                'status' => $item->status,
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

    public function getDataProdukLogistik(Request $request)
    {
        $req = $request->all();
        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'pr.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'pr.kdprofile');
            })
            ->JOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->leftJOIN('satuanstandar_m as ss', function ($j) {
                $j->on('ss.id', '=', 'pr.objectsatuanstandarfk')->on('ss.kdprofile', '=', 'pr.kdprofile');
            })
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.kdproduk')
            ->where('pr.statusenabled', true)
            ->where('pr.kdprofile', $this->kdProfile)
            ->orderBy('pr.namaproduk');

        if (
            isset($req['namaproduk']) && $req['namaproduk'] != "" && $req['namaproduk'] != "undefined"
        ) {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ILIKE', '%' . $req['namaproduk'] . '%');
        };

        if (
            isset($req['filter']['filters'][0]['value']) &&
            $req['filter']['filters'][0]['value'] != "" &&
            $req['filter']['filters'][0]['value'] != "undefined"
        ) {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ILIKE', '%' . $req['filter']['filters'][0]['value'] . '%');
        };
        $dataProduk = $dataProduk->take(20);
        $dataProduk = $dataProduk->get();

        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', function ($j) {
                $j->on('ss.id', '=', 'ks.satuanstandar_asal')->on('ss.kdprofile', '=', 'ks.kdprofile');
            })
            ->JOIN('satuanstandar_m as ss2', function ($j) {
                $j->on('ss2.id', '=', 'ks.satuanstandar_tujuan')->on('ss2.kdprofile', '=', 'ks.kdprofile');
            })
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
            foreach ($dataKonversiProduk as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $satuanKonversi[] = array(
                        'ssid' =>   $item2->satuanstandar_tujuan,
                        'satuanstandar' =>   $item2->satuanstandar2,
                        'nilaikonversi' =>   $item2->nilaikonversi,
                    );
                }
            }

            $dataProdukResult[] = array(
                'id' =>   $item->id,
                'namaproduk' =>   $item->namaproduk,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'kdproduk' => $item->kdproduk,
            );
        }

        return $this->respond($dataProdukResult);
    }

    public function getHargaTerakhir(Request $request)
    {
        $maxHarga = DB::table("stokprodukdetail_t as spd")
                      ->join('produk_m as pr','pr.id','spd.objectprodukfk')
                      ->join('satuanstandar_m as ss','ss.id','pr.objectsatuanstandarfk')
                      ->leftjoin('detailjenisproduk_m as djp','djp.id','pr.objectdetailjenisprodukfk')
                      ->join('strukpelayanandetail_t as spdl','spdl.nostrukfk','spd.nostrukterimafk')
                      ->select(DB::raw("distinct spd.tglpelayanan,spd.harganetto1 as harga,spdl.harganetto,pr.id,pr.kdproduk,pr.namaproduk,ss.satuanstandar"))
                      ->where('spd.kdprofile', $this->kdProfile)
                      ->where('spd.objectprodukfk',$request['produkfk'])
                      ->where('djp.objectjenisprodukfk', $this->settingFix('kdJenisProdukObat'))
                      ->orderBy('spd.tglpelayanan','desc')
                      ->get();

        $dataNyawaTerakhir = [];
        $samateu = false;
        foreach ($maxHarga as $item) {
            $samateu = false;
            foreach ($dataNyawaTerakhir as $itemsss) {
                if ($item->id == $itemsss['id']) {
                    $samateu = true;
                    if ($item->tglpelayanan > date($itemsss['tglpelayanan'])) {
                        $itemsss['harga'] = $item->harga;
                        $itemsss['hargapenerimaan'] = $item->harganetto;
                        $itemsss['tglpelayanan'] = $item->tglpelayanan;
                        break;
                    }
                }
            }
            if ($samateu == false) {
                $dataNyawaTerakhir[] = array(
                    'id' => $item->id,
                    'tglpelayanan' => $item->tglpelayanan,
                    'harga' => $item->harga,
                    'hargapenerimaan' => $item->harganetto,
                    'kdproduk' => $item->kdproduk,
                    'namaproduk' => $item->namaproduk,
                    'satuanstandar' => $item->satuanstandar,
                );
            }
        }


        $result = array(
            'detail' => $dataNyawaTerakhir,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function saveUsulanPermintaan(Request $request) {
        $nomor = '';
        DB::beginTransaction();
        try{

            $dataSuplier = $request['details'];
            foreach ($dataSuplier as $dataSup) {
                // Looping suplier
                if ($request['strukorder']['norec'] == '') {
                    //###1###
                    //CARI KODE USULAN BERDASARKAN RUANGAN PENG-USUL
                    $idruangan = $request['strukorder']['ruanganfkPengusul'];
                    $dataruangan = collect(DB::select("
                        SELECT id,namaruangan,CASE WHEN website IS NULL THEN '/PO/RSADVENT/' ELSE '/PO/RSADVENT-'|| website || '/' END AS nomor  
                        FROM ruangan_m WHERE id = $idruangan
                    "))->first();                
                    //** LAMA *//                   
                    // $prefix = 'PO/' . $this->KonDecRomawi($this->getDateTime()->format('m')) . '/' . 
                    //           $this->getDateTime()->format('y');
                    //** LAMA *//
                    $prefix = $dataruangan->nomor . $this->KonDecRomawi($this->getDateTime()->format('m')) . '/' . 
                              $this->getDateTime()->format('y');               
                    $resultr = StrukOrder::where('noorder', 'ILIKE', '%' . $prefix)->max('noorder');                
                    $noOrder = $this->generateCodeBySeqTable(new StrukOrder, 'nomorpo', 10,'PO' . date('ym'),$this->kdProfile);                        
                    // $noOrder = (str_pad((int)$subPrefix + 1, 3, "0", STR_PAD_LEFT)) . '' . $prefix;
                    $nomor = substr($noOrder, 6) . $prefix;             
                    if ($noOrder == ''){
                        $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "message"  => $transMessage,
                            "as" => 'as@epic',
                        );
                        return $this->setStatusCode($result['status'])->respond($result, $transMessage);
                    }
                    //####1####
    
                    $dataSO = new StrukOrder();
                    $dataSO->norec = $dataSO->generateNewId();
                    $dataSO->kdprofile = $this->kdProfile;
                    $dataSO->statusenabled = true;
                    $dataSO->noorder = $nomor;//$request['strukorder']['noUsulan'];
                    $dataSO->noorderintern = $nomor;//$request['strukorder']['nousulan'];
                }else {
                    $dataSO = StrukOrder::where('norec', $request['strukorder']['norec'])->first();
                    $noStruk = $dataSO->nostruk;
    
                    $delSPD = OrderPelayanan::where('strukorderfk', $request['strukorder']['norec'])
                        ->delete();
                }
                $dataSO->isdelivered = 0;
                $dataSO->objectkelompoktransaksifk = 89;
                if (isset($request['strukorder']['keteranganorder'])) {
                    $dataSO->keteranganorder = $request['strukorder']['keteranganorder'];
                }      
                
                $dataSO->qtyjenisproduk = $request['strukorder']['qtyjenisproduk'];
                $dataSO->qtyproduk = $request['strukorder']['qtyjenisproduk'];
                $dataSO->tglorder = $request['strukorder']['tglUsulan'];
                $dataSO->tglvalidasi = $request['strukorder']['tglDibutuhkan'];
                $dataSO->tglrencana = $request['strukorder']['tgljatuhtempo'];
                // $dataSO->keteranganlainnya = $request['strukorder']['koordinator'];            
                $dataSO->objectruanganfk = $request['strukorder']['ruanganfkPengusul'];
                $dataSO->objectruangantujuanfk = $request['strukorder']['ruanganfkTujuan'];
                $dataSO->objectpegawaiorderfk = $request['strukorder']['penanggungjawabfk'];
                if (isset($request['strukorder']['mengetahuifk'])) {
                    $dataSO->objectpetugasfk = $request['strukorder']['mengetahuifk'];
                }                    
                $dataSO->statusorder = 0;
                $dataSO->totalbeamaterai = 0;
                $dataSO->totalbiayakirim = 0;
                $dataSO->totalbiayatambahan = 0;
                $dataSO->totaldiscount = $request['strukorder']['totaldiskon'];
                $dataSO->totalhargasatuan = $request['strukorder']['totalharga'];
                $dataSO->totalharusdibayar = $request['strukorder']['grandtotal'];
                $dataSO->totalpph = 0;
                $dataSO->totalppn =  $request['strukorder']['totalppn'];
                $dataSO->kelompokbarangfk =  $request['strukorder']['kelompokbarangfk'];
                $dataSO->kelompokbarang =  $request['strukorder']['kelompokbarang'];
                $dataSO->objectrekananfk = $dataSup['rekanan'];
                $dataSO->namarekanansales = $dataSup['namarekanan'];
                
    
                $dataSO->save();
                $SO = array(
                    "norec"  => $dataSO->norec,
                    "objectkelompoktransaksifk" => $dataSO->objectkelompoktransaksifk,
    
                );

                // foreach ($dataSup as $item) {
                    $dataOP = new OrderPelayanan();
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $this->kdProfile;
                    $dataOP->statusenabled = true;
                    $dataOP->hasilkonversi = $dataSup['nilaikonversi'];
                    $dataOP->iscito = 0;
                    $dataOP->noorderfk =$SO['norec'];
                    $dataOP->objectprodukfk = $dataSup['produkfk'];
                    // $dataOP->objectasalprodukfk = $request['strukorder']['asalproduk'];
                    $dataOP->qtyproduk = $dataSup['jumlah'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectsatuanstandarfk = $dataSup['satuanstandarfk'];
                    $dataOP->strukorderfk = $SO['norec'];
                    $dataOP->tglpelayanan = $request['strukorder']['tglUsulan'];
                    $dataOP->hargasatuan = $dataSup['hargasatuan'];
                    $dataOP->hargadiscount = $dataSup['hargadiscount'];
                    $dataOP->hargappn = $dataSup['ppn'];
                    $dataOP->persenppn = $dataSup['persenppn'];
                    $dataOP->persendiscount = $dataSup['persendiscount'];
                    // $dataOP->deskripsiprodukquo = $dataSup['spesifikasi'];//$dataSup['spesifikasi'];
                    $dataOP->tglpelayananakhir = $dataSup['tglkebutuhan'];
                    $dataOP->objectrekananfk = $dataSup['rekanan'];
                    $dataOP->save();
                // }

                //***** Struk Realisasi *****
                $datanorecSR='';
                if ($request['strukorder']['norecrealisasi'] == '') {
                    $dataSR= new StrukRealisasi();
                    $norealisasi = $this->generateCode(new StrukRealisasi(),'norealisasi',10,'RA-'.$this->getDateTime()->format('ym'), $this->kdProfile);
                    $dataSR->norec = $dataSR->generateNewId();
                    $dataSR->kdprofile = $this->kdProfile;
                    $dataSR->statusenabled = true;
                    $dataSR->norealisasi = $norealisasi;
                    $dataSR->tglrealisasi = $request['strukorder']['tglUsulan'];
                    $dataSR->totalbelanja = $request['strukorder']['grandtotal'];
                    $dataSR->save();
                    if ($request['strukorder']['norecrealisasi'] == '') {
                        $datanorecSR = $dataSR->norec;
                    }else{
                        $datanorecSR = $request['strukorder']['norecrealisasi'];
                    }
                }else {
                    $dataSR = StrukRealisasi::where('norec', $request['strukorder']['norecrealisasi'])->first();
                    $dataSR->tglrealisasi = $request['strukorder']['tglUsulan'];
                    $dataSR->objectmataanggaranfk = $request['strukorder']['objectmataanggaranfk'];
                    $dataSR->totalbelanja = $request['strukorder']['grandtotal'];
                    $dataSR->save();
                    if ($request['strukorder']['norecrealisasi'] == '') {
                        $datanorecSR = $dataSR->norec;
                    }else{
                        $datanorecSR = $request['strukorder']['norecrealisasi'];
                    }
                }



                //***** Riwayat Realisasi *****
                if ($request['strukorder']['norecrealisasi'] == '') {
                    $dataRR= new RiwayatRealisasi();
                    $dataRR->norec = $dataRR->generateNewId();
                    $dataRR->kdprofile = $this->kdProfile;
                    $dataRR->statusenabled = true;
                }else {

                    $dataRR = RiwayatRealisasi::where('objectstrukrealisasifk', $request['strukorder']['norecrealisasi'])->first();

                }
                $dataRR->objectstrukrealisasifk = $datanorecSR;
                $dataRR->objectstrukfk = $SO['norec'];
                $dataRR->objectkelompoktransaksifk = $SO['objectkelompoktransaksifk'];
                $dataRR->tglrealisasi =$request['strukorder']['tglUsulan'];
                $dataRR->objectpetugasfk = $request['strukorder']['penanggungjawabfk'];
                $dataRR->noorderintern = $nomor;//$request['strukorder']['nousulan'];
                if (isset($request['strukorder']['keteranganorder'])) {
                    $dataRR->keteranganlainnya = $request['strukorder']['keteranganorder'];
                }        
                $dataRR->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Simpan Berhasil',
                "nokirim" => $dataSO->norec,
                "as" => 'ea@epic',
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Simpan Gagal',
                "data" => $e->getMessage(),
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getDataDetailPO(Request $request)
    {

        $dataStruk = DB::table('strukorder_t as sp')
        ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.objectstrukfk', '=', 'sp.norec')
        ->LEFTJOIN('riwayatrealisasi_t as rr2', 'rr2.sppbfk', '=', 'sp.norec')
        ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec', '=', 'rr.objectstrukrealisasifk')
        ->LEFTJOIN('strukrealisasi_t as sr2', 'sr2.norec', '=', 'rr2.objectstrukrealisasifk')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
        ->LEFTJOIN('pegawai_m as pg1', 'pg1.id', '=', 'sp.objectpetugasfk')
        ->LEFTJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
        ->LEFTJOIN('rekanan_m as rkn1', 'rkn1.id', '=', 'sp.objectrekanansalesfk')
        ->LEFTJOIN('mataanggaran_t as ma', 'ma.norec', '=', 'sr.objectmataanggaranfk')
        ->LEFTJOIN('mataanggaran_t as ma1', 'ma1.norec', '=', 'sr2.objectmataanggaranfk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->LEFTJOIN('ruangan_m as ru1', 'ru1.id', '=', 'sp.objectruangantujuanfk')
        ->LEFTJOIN('rekanan_m as rek', 'rek.id', '=', 'sp.objectrekananfk')
        ->select(
            'sp.norec',
            'sp.tglorder',
            'sp.noorder',
            'pg.namalengkap',
            'pg.id as pgid',
            'pg1.nippns',
            'sp.alamat',
            'sp.alamattempattujuan',
            'sp.keteranganlainnya',
            'sp.tglvalidasi',
            'sp.noorderintern',
            'sp.keterangankeperluan',
            'sp.nokontrakspk',
            'sp.noorderrfq',
            'sp.keteranganorder',
            'rkn.namarekanan',
            'rkn.id as rknid',
            'sp.namarekanansales',
            'sp.totalhargasatuan',
            'sp.objectpetugasfk',
            'pg1.namalengkap as mengetahui',
            'pg1.nippns',
            'rkn1.namarekanan as namarekanansales',
            'sp.objectrekanansalesfk',
            'rkn.alamatlengkap as alamatrekanan',
            'rkn1.alamatlengkap as alamatrekanansales',
            'rkn.faksimile as faxrekanan',
            'rkn.telepon as tlprekanan',
            'rkn1.faksimile as faxrekanansales',
            'rkn1.telepon as tlprekanansales',
            'sr.norealisasi',
            'sr.norec as norecrealisasi',
            'rr.norec as norecrrusulan',
            'sr2.norec as norecrealisasisppb',
            'rr2.norec as norecrrsppb',
            'sr.objectmataanggaranfk as mataanggranid',
            'ma.mataanggaran',
            'sr2.objectmataanggaranfk as mataanggranfk',
            'ma1.mataanggaran as mataanggaransppb',
            'ru.id as idunitpengusul',
            'ru.namaruangan as unitpengusul',
            'ru1.id as idunittujuan',
            'ru1.namaruangan as unittujuan',
            'sp.jenisusulanfk',
            'rek.namarekanan',
            'rek.alamatlengkap as alamatrekanan',
            'rek.telepon as kontakrekanan',
            'rek.email as emailrekanan',
            DB::raw("to_char(sp.tglorder,'YYYY') as tahunusulan,sp.tglrencana,sp.kelompokbarangfk,sp.kelompokbarang,sp.tglrencana,sp.namarekanansales,
                sp.objectrekananfk,sp.isverifikasi")
        )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.norec', $request['norecOrder'])
            ->first();

        $listKlmpokBarang = array(
            [
                'id' => 1, 'kelompok' => 'Reagen'
            ], [
                'id' => 2, 'kelompok' => 'Obat'
            ], [
                'id' => 3, 'kelompok' => 'Alkes'
            ], [
                'id' => 4, 'kelompok' => 'Alat Medis'
            ], [
                'id' => 5, 'kelompok' => 'Barang Umum'
            ], [
                'id' => 6, 'kelompok' => 'Pekerjaan'
            ]
        );
        if ($dataStruk->kelompokbarang == null) {
            foreach ($listKlmpokBarang as $key => $value) {
                if ($value['id'] == $dataStruk->kelompokbarangfk) {
                    $dataStruk->kelompokbarang = $value['kelompok'];
                    break;
                }
            }
        }
        $detail = array(
            'tglorder' => $dataStruk->tglorder,
            'tglrencana' => $dataStruk->tglrencana,
            'noorder' => $dataStruk->noorder,
            'norec' => $dataStruk->norec,
            'petugasid' => $dataStruk->pgid,
            'petugas' => $dataStruk->namalengkap,
            'petugasmengetahui' => $dataStruk->mengetahui,
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
            'faxrekanan' => $dataStruk->faxrekanan,
            'tlprekanan' => $dataStruk->tlprekanan,
            'rekanansalesfk' => $dataStruk->objectrekanansalesfk,
            'namarekanansales' => $dataStruk->namarekanansales,
            'alamatrekanansales' => $dataStruk->alamatrekanansales,
            'faxrekanansales' => $dataStruk->faxrekanansales,
            'tlprekanansales' => $dataStruk->tlprekanansales,
            'totalhargasatuan' => $dataStruk->totalhargasatuan,
            'norealisasi' => $dataStruk->norealisasi,
            'norecrealisasi' => $dataStruk->norecrealisasi,
            'norecrealisasisppb' => $dataStruk->norecrealisasisppb,
            'norecrrusulan' => $dataStruk->norecrrusulan,
            'norecrrsppb' => $dataStruk->norecrrsppb,
            'norecrrsppb' => $dataStruk->norecrrsppb,
            'mataanggranid' => $dataStruk->mataanggranid,
            'mataanggaran' => $dataStruk->mataanggaran,
            'mataanggranfk' => $dataStruk->mataanggranfk,
            'mataanggaransppb' => $dataStruk->mataanggaransppb,
            'idunitpengusul' => $dataStruk->idunitpengusul,
            'unitpengusul' => $dataStruk->unitpengusul,
            'idunittujuan' => $dataStruk->idunittujuan,
            'unittujuan' => $dataStruk->unittujuan,
            'jenisusulanfk' => $dataStruk->jenisusulanfk,
            'kelompokbarangfk' => $dataStruk->kelompokbarangfk,
            'objectrekananfk' => $dataStruk->objectrekananfk,
            'namarekanan' => $dataStruk->namarekanan,
            'kelompokbarang' => $dataStruk->kelompokbarang,
            'alamatrekanan' => $dataStruk->alamatrekanan,
            'kontakrekanan' => $dataStruk->kontakrekanan,
            'emailrekanan' => $dataStruk->emailrekanan,
            'isverifikasi' => $dataStruk->isverifikasi,
        );

        $i = 0;
        $dataStok = $details = DB::select(
            DB::raw("        
                    select spd.norec as norec_op, pr.id as produkfk,pr.namaproduk,spd.objectrekananfk,rek.namarekanan,pr.kdproduk,
                    ss.satuanstandar,ss.id as ssid,spd.qtyproduk,spd.qtyterimalast,spd.hargasatuan,spd.deskripsiprodukquo,
                    spd.hargasatuanquo,spd.qtyprodukkonfirmasi,spd.hargadiscountquo,spd.hargappnquo,spd.hargadiscount,spd.hargappn,sb.name as statusbarang,
                    CAST(spd.qtyproduk * spd.hargasatuan AS FLOAT)  as subtotal,
                    ((spd.qtyproduk*(spd.hargasatuan))*(0.11))+(spd.qtyproduk*(spd.hargasatuan)) as totalanother,
                    (spd.qtyproduk*(spd.hargasatuan))*(0.11) as hargappnlain,
                    CAST( 
                    (spd.qtyproduk*spd.hargasatuan)-(spd.qtyproduk*spd.hargadiscount)-spd.hargappn
                    AS FLOAT) as total,                    
                    (spd.qtyproduk*(spd.hargasatuanquo-spd.hargadiscountquo+spd.hargappnquo)) totalkonfirmasi,
                    (spd.qtyprodukkonfirmasi*(spd.hargasatuanquo)) as totalkonfirmasiss,
                    spd.hasilkonversi,spd.noorderfk,spd.objectasalprodukfk,ap.id as apid,ap.asalproduk,
                    spd.tglpelayananakhir as tglkebutuhan,spd.qtyterimalast,spd.persenppn,spd.persendiscount
                    from orderpelayanan_t as spd 
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    left JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                    left JOIN rekanan_m as rek on rek.id=spd.objectrekananfk
                    left JOIN status_barang_m as sb on sb.id = spd.objectstatusbarang
                    where spd.kdprofile = $this->kdProfile and spd.noorderfk=:norec"),
            array(
                'norec' => $request['norecOrder'],
            )
        );

        $jmlstok = 0;
        $details = [];
        foreach ($dataStok as $item) {
            // dd($item->persendiscount);
            $i = $i + 1;
            $jmlDipakai = 0;
            if ($item->qtyterimalast == null) {
                $qtyterima = 0;
            } else {
                $qtyterima = (float)$item->qtyterimalast;
            }
            if ((float)$item->qtyproduk - $qtyterima > 0) {
                $details[] = array(
                    'no' => $i,
                    'kdproduk' => $item->kdproduk,
                    'produkfk' => $item->produkfk,
                    'norec_op' => $item->norec_op,
                    'namaproduk' => $item->namaproduk,
                    'namarekanan' => $item->namarekanan,
                    'rekananfk' => $item->objectrekananfk,
                    'nilaikonversi' => $item->hasilkonversi,
                    'satuanstandarfk' => $item->ssid,
                    'satuanstandar' => $item->satuanstandar,
                    'satuan' => $item->satuanstandar,
                    'ssid' => $item->ssid,
                    'satuanviewfk' => $item->ssid,
                    'satuanview' => $item->satuanstandar,
                    'spesifikasi' => $item->deskripsiprodukquo,
                    'jmlstok' => $jmlstok,
                    'jumlahsppb' => (float)$item->qtyproduk,
                    'jumlahterima' => $qtyterima,
                    'jumlah' => (float)$item->qtyproduk - (float)$qtyterima,
                    'jumlahdipakai' => $jmlDipakai,
                    'sisa' => ((float)$item->qtyproduk - (float)$qtyterima) - $jmlDipakai,
                    'hargasatuan' => $item->hargasatuan,
                    'hargasatuanquo' => $item->hargasatuanquo,
                    'hargadiscountquo' => $item->hargadiscountquo,
                    'hargappnquo' => $item->hargappnquo,
                    'qtyprodukkonfirmasi' => $item->qtyprodukkonfirmasi,
                    'qtyterima' => $item->qtyterimalast,
                    'hargasatuankonfirmasi' => $item->hargasatuanquo,
                    'totalkonfirmasi' => $item->totalkonfirmasi,
                    'totalkonfirmasiss' => $item->totalkonfirmasiss,
                    'hargadiscount' => $item->hargadiscount,
                    'hargadiskon' => $item->hargadiscount,
                    'ppn' => $item->hargappn,
                    'nilaippn' => $item->hargappn,
                    'persenppn' => $item->persenppn,
                    'persendiscount' => $item->persendiscount,
                    'subtotal' => $item->subtotal,
                    'total' => (float)$item->qtyproduk * (float)$item->hargasatuan - (float)$item->hargadiscount + (float)$item->hargappn, //$item->total,
                    'totalall' => (float)$item->qtyproduk * (float)$item->hargasatuan - (float)$item->hargadiscount + (float)$item->hargappn, //$item->total,
                    'ruanganfk' => 50,
                    'asalprodukfk' => $item->apid,
                    'asalproduk' => $item->asalproduk,
                    'keterangan' => '',
                    'nobatch' => '',
                    'statusbarang' => $item->statusbarang,
                    'tglkadaluarsa' => null,
                    'tglkebutuhan' => $item->tglkebutuhan,
                    'ppnlain' => $item->hargappnlain,
                    'subtotallain' => $item->totalanother,
                );
            }
        }

        $result = array(
            'detail' => $detail,
            'details' => $details,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function batalPO(Request $request)
    {
        
        DB::beginTransaction();
        try {

            StrukOrder::where('kdprofile',$this->kdProfile)->where('norec', $request['norec'])->update(['statusenabled' => false]);
            OrderPelayanan::where('kdprofile',$this->kdProfile)->where('noorderfk', $request['norec'])->update(['statusenabled' => false]);

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Batal Usulan Permintaan Barang Berhasil",
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => "Batal Usulan Permintaan Barang Gagal",
                "data" => $e->getMessage(),
            );
        }

        return $this->respond($result,$result['status'],$result['message']);

    }


    public function getDataVerifRequestPurchaseRequest(Request $request)
    {
        $kdProfile = (int)$this->getDataKdProfile($request);

        $data = StrukOrder::where('kdprofile', $kdProfile)->where('statusenabled', true)->where('objectkelompoktransaksifk', $request['kelompoktransaksi'])->where('norec', $request['norec_so'])->get();

        $result = array(
            'data' => $data,
            'as' => 'Epic'
        );

        return $this->respond($result);
    }

    // public function saveBatalUsulanPermintaanBarang(Request $request)
    // {

    //     $idProfile = (int) $this->kdProfile;
    //     DB::beginTransaction();
    //     $tglAyeuna = date('Y-m-d H:i:s');
    //     $dataLogin = $request->all();

    //     try {
    //         StrukOrder::where('norec', $request['data']['norec'])->update(['statusenabled' => false]);

    //         //## Logging User
    //         // $newId = LoggingUser::max('id');
    //         // $newId = $newId + 1;
    //         // $logUser = new LoggingUser();
    //         // $logUser->id = $newId;
    //         // $logUser->norec = $logUser->generateNewId();
    //         // $logUser->kdprofile = $idProfile;
    //         // $logUser->statusenabled = true;
    //         // $logUser->jenislog = 'Batal Usulan Permintaan Barang';
    //         // $logUser->noreff = $request['data']['norec'];
    //         // $logUser->referensi = 'norec Struk Order';
    //         // $logUser->objectloginuserfk =  $dataLogin['userData']['id'];
    //         // $logUser->tanggal = $tglAyeuna;
    //         // $logUser->save();

    //         // $transStatus = 'true';
    //         DB::commit();
    //         $result = array(
    //             'status' => 201,
    //             'message' => "Permintaan Berhasil Dibatalkan",
    //         );
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //     }

    //     if ($transStatus == 'true') {
    //         $transMessage = "Batal Usulan Permintaan Barang Berhasil";
          
    //     } else {
    //         $transMessage = "Batal Usulan Permintaan Barang Gagal";
    //         DB::rollBack();
    //         $result = array(
    //             'status' => 400,
    //             'message'  => $transStatus,
    //             'as' => 'Mr.Cepot',
    //         );
    //     }
    //     return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    // }

}
