<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\RiwayatRealisasi;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPraOrder;
use App\Models\Transaksi\StrukPraOrderDetail;
use App\Models\Transaksi\StrukRealisasi;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PurchaseOrderCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDaftarUsulanPermintaanRuangan(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $datas = DB::table('strukorder_t as sp')
        ->JOIN('orderpelayanan_t as op', 'op.noorderfk', '=', 'sp.norec')
        ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.objectstrukfk', '=', 'sp.norec')
        ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec', '=', 'rr.objectstrukrealisasifk')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
        ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpetugasfk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
        ->LEFTJOIN('strukkonfirmasi_t as sk', 'sk.norec', '=', 'sp.objectkonfirmasifk')
        ->LEFTJOIN('strukkonfirmasi_t as sk1', 'sk1.norec', '=', 'sp.konfirmasidkfk')
        // ->LEFTJOIN('strukverifikasi_t as sv', 'sv.norec', '=', 'sp.strukverifkepalagudangfk')
        // ->LEFTJOIN('strukverifikasi_t as sv2', 'sv2.norec', '=', 'sp.strukverifpembelianfk')
        ->select(
            'sp.norec',
            'sp.tglorder',
            'sp.noorder',
            'pg.namalengkap as penanggungjawab',
            'pg2.namalengkap as mengetahui',
            'sp.tglvalidasi as tglkebutuhan',
            'sp.alamattempattujuan',
            'sp.keteranganlainnya',
            'sp.tglvalidasi',
            'sp.noorderintern',
            'sp.keterangankeperluan',
            'sp.keteranganorder',
            'ru.namaruangan as ruangan',
            'ru.id as ruid',
            'ru2.namaruangan as ruangantujuan',
            'ru2.id as ruidtujuan',
            // 'sv.tglverifikasi AS tglverifikasikabag',
            // 'sv.noverifikasi AS verifikasikabag',
            'sp.totalhargasatuan',
            'sp.statusorder',
            'sr.norec as norecrealisasi',
            'sk.nokonfirmasi',
            'sk.tglkonfirmasi',
            'sk1.nokonfirmasi as nokonfirmasidk',
            'sk1.tglkonfirmasi as tglkonfirmasidk',
            'sp.namarekanansales',
            'sp.keteranganorder',
            // 'sv2.tglverifikasi AS tglverifikasipenjualan',
            // 'sv2.noverifikasi AS verifikasipenjualan'
        )
        ->where('sp.kdprofile', $this->kdProfile)
        ->where('sp.statusorder',1)
        ->whereBetween(DB::raw("sp.tglorder::date"), $dateRange);

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $datas = $datas->where('sp.noorder', 'ILIKE', '%' . $request['noorder'] . '%');
        }
        if (isset($request['noKontrak']) && $request['noKontrak'] != "" && $request['noKontrak'] != "undefined") {
            $datas = $datas->where('sp.nokontrakspk', 'ILIKE', '%' . $request['noKontrak'] . '%');
        }

        $datas = $datas->distinct();
        $datas = $datas->where('sp.statusenabled', true);
        $datas = $datas->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('USULAN PERMINTAAN BARANG/JASA') );
        $datas = $datas->orderBy('sp.tglorder');
        $datas = $datas->get();

        $details = DB::table("orderpelayanan_t as spd")
                     ->leftJoin('produk_m as pr','pr.id','spd.objectprodukfk')
                     ->leftJoin('satuanstandar_m as ss','ss.id','spd.objectsatuanstandarfk')
                     ->selectRaw("pr.namaproduk,ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargadiscount,spd.hargappn,
                        ((spd.qtyproduk*spd.hargasatuan)-(spd.qtyproduk*spd.hargadiscount))+spd.hargappn as total,spd.strukorderfk,
                        spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid")
                     ->whereBetween(DB::raw('spd.tglpelayanan::date'), $dateRange)
                     ->where('spd.kdprofile',$this->kdProfile)
                     ->where('spd.statusenabled',true);
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        $details = $details->where('spd.objectprodukfk', '=', $request['produkfk']);
                    }
                $details = $details->get();

        $results = array();

        foreach($datas as $data){

            $items = [];
            foreach($details as $detail){
                if($detail->strukorderfk == $data->norec){
                    $items[] = $detail;
                }
            }

            $results[] = array(
                'tglorder' => $data->tglorder,
                'noorder' => $data->noorder,
                'norec' => $data->norec,
                'penanggungjawab' => $data->penanggungjawab,
                'keterangan' => $data->keteranganorder,
                'koordinator' => $data->keteranganlainnya,
                'tglkebutuhan' => $data->tglkebutuhan,
                'tglusulan' => $data->tglorder,
                'nousulan' => $data->noorderintern,
                'namapengadaan' => $data->keterangankeperluan,
                'mengetahui' => $data->mengetahui,
                'ruangan' => $data->ruangan,
                'ruangantujuan' => $data->ruangantujuan,
                'totalhargasatuan' => $data->totalhargasatuan,
                // 'tglverifikasikabag' => $data->tglverifikasikabag,
                // 'verifikasikabag' => $data->verifikasikabag,
                'status' => $data->statusorder,
                'details' => $items,
                'norecrealisasi' => $data->norecrealisasi,
                'nokonfirmasi' => $data->nokonfirmasi,
                'tglkonfirmasi' => $data->tglkonfirmasi,
                'nokonfirmasidk' => $data->nokonfirmasidk,
                'tglkonfirmasidk' => $data->tglkonfirmasidk,
                'namarekanansales' => $data->namarekanansales,
                'keteranganorder' => $data->keteranganorder,
                // 'tglverifikasipenjualan' => $data->tglverifikasipenjualan,
                // 'verifikasipenjualan' => $data->verifikasipenjualan,
            );
        }

        return $this->respond($results);
    }

    public function getDaftarUsulanPermintaan(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->get();

        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $datas = DB::table('strukorder_t as sp')
            ->JOIN('orderpelayanan_t as op', 'op.noorderfk', '=', 'sp.norec')
            ->LEFTJOIN('strukverifikasi_t as sv', 'sv.norec', '=', 'sp.objectsrukverifikasifk')
            ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.objectstrukfk', '=', 'sp.norec')
            ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec', '=', 'rr.objectstrukrealisasifk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpetugasfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->LEFTJOIN('strukkonfirmasi_t as sk', 'sk.norec', '=', 'sp.objectkonfirmasifk')
            ->LEFTJOIN('strukkonfirmasi_t as sk1', 'sk1.norec', '=', 'sp.konfirmasidkfk')
            ->select(
                'sp.norec',
                'sp.tglorder',
                'sp.noorder',
                'pg.namalengkap as penanggungjawab',
                'pg2.namalengkap as mengetahui',
                'sp.tglvalidasi as tglkebutuhan',
                'sp.alamattempattujuan',
                'sp.keteranganlainnya',
                'sp.tglvalidasi',
                'sp.noorderintern',
                'sp.keterangankeperluan',
                'sp.keteranganorder',
                'ru.namaruangan as ruangan',
                'ru.id as ruid',
                'ru2.namaruangan as ruangantujuan',
                'sv.tglverifikasi',
                'sv.noverifikasi',
                'sp.totalhargasatuan',
                'sp.statusorder',
                'sr.norec as norecrealisasi',
                'sk.nokonfirmasi',
                'sk.tglkonfirmasi',
                'sk1.nokonfirmasi as nokonfirmasidk',
                'sk1.tglkonfirmasi as tglkonfirmasidk',
                'sp.namarekanansales',
                'sp.keteranganorder'
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $dateRange);

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $datas = $datas->where('sp.noorder', 'ILIKE', '%' . $request['noorder'] . '%');
        }
        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined") {
            $datas = $datas->where('sp.statusorder', $request['status']);
        }

        $datas = $datas->distinct();
        $datas = $datas->where('sp.statusenabled', true);
        $datas = $datas->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('USULAN PERMINTAAN BARANG/JASA'));
        $datas = $datas->orderBy('sp.tglorder');
        $datas = $datas->get();

        $details = DB::table('orderpelayanan_t as spd')
            ->join('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->selectRaw("pr.namaproduk,ss.satuanstandar,spd.qtyproduk,spd.strukorderfk,spd.hargasatuan,spd.hargadiscount,spd.hargappn,
                                (spd.qtyproduk * spd.hargasatuan - spd.hargadiscount + spd.hargappn) as total,
                                spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid")
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.statusenabled', true)
            ->whereBetween(DB::raw('tglpelayanan::date'), $dateRange);
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $details = $details->where('spd.objectprodukfk', '=', $request['produkfk']);
        }
        $details = $details->get();

        $results = array();
        foreach ($datas as $data) {
            $items = [];
            foreach ($details as $detail) {
                if ($data->norec == $detail->strukorderfk) {
                    $items[] = $detail;
                }
            }
            $results[] = array(
                'tglorder' => $data->tglorder,
                'noorder' => $data->noorder,
                'norec' => $data->norec,
                'penanggungjawab' => $data->penanggungjawab,
                'keterangan' => $data->keteranganorder,
                'koordinator' => $data->keteranganlainnya,
                'tglkebutuhan' => $data->tglkebutuhan,
                'tglusulan' => $data->tglorder,
                'nousulan' => $data->noorderintern,
                'namapengadaan' => $data->keterangankeperluan,
                'mengetahui' => $data->mengetahui,
                'ruangan' => $data->ruangan,
                'ruangantujuan' => $data->ruangantujuan,
                'totalhargasatuan' => $data->totalhargasatuan,
                'tglverifikasi' => $data->tglverifikasi,
                'noverifikasi' => $data->noverifikasi,
                'status' => $data->statusorder,
                'details' => $items,
                'norecrealisasi' => $data->norecrealisasi,
                'nokonfirmasi' => $data->nokonfirmasi,
                'tglkonfirmasi' => $data->tglkonfirmasi,
                'nokonfirmasidk' => $data->nokonfirmasidk,
                'tglkonfirmasidk' => $data->tglkonfirmasidk,
                'namarekanansales' => $data->namarekanansales,
                'keteranganorder' => $data->keteranganorder,
            );
        }

        return $this->respond($results);
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
            ->join('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', 'pr.objectsatuanstandarfk')
            ->leftjoin('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
            ->join('strukpelayanandetail_t as spdl', 'spdl.nostrukfk', 'spd.nostrukterimafk')
            ->select(DB::raw("distinct spd.tglpelayanan,spd.harganetto1 as harga,spdl.harganetto,pr.id,pr.kdproduk,pr.namaproduk,ss.satuanstandar"))
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.objectprodukfk', $request['produkfk'])
            ->where('djp.objectjenisprodukfk', $this->settingFix('kdJenisProdukObat'))
            ->orderBy('spd.tglpelayanan', 'desc')
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

    public function saveUsulanPermintaan(Request $request)
    {
        $kdprofile =$this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['strukorder']['norec'] == '') {
                $noOrder = $this->SEQUENCE(new StrukOrder, 'nomorpo', 10, 'PR-' . $this->getDateTime()->format('ym'), $kdprofile);
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $kdprofile;
                $dataSO->statusenabled = true;
                $dataSO->isdelivered = 0;
                $dataSO->noorder = $noOrder;
            }else{
                $dataSO = StrukOrder::where('norec', $request['strukorder']['norec'])->first();
                $noOrder = $dataSO->noorder;
                OrderPelayanan::where('strukorderfk', $request['strukorder']['norec'])
                    ->delete();
            }
            $dataSO->isdelivered = 0;
            $dataSO->objectkelompoktransaksifk = $this->kelompokTransaksi('USULAN PERMINTAAN BARANG/JASA');
            $dataSO->keteranganorder = isset($request['strukorder']['keteranganorder']) ? $request['strukorder']['keteranganorder'] : null;
            $dataSO->qtyjenisproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->qtyproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->tglorder = $request['strukorder']['tglUsulan'];
            $dataSO->tglvalidasi = $request['strukorder']['tgljatuhtempo'] ?? null;
            $dataSO->tglrencana = $request['strukorder']['tgljatuhtempo'] ?? null;
            $dataSO->objectruanganfk = $request['strukorder']['ruanganfkPengusul'];
            $dataSO->objectruangantujuanfk = $request['strukorder']['ruanganfkTujuan'];
            $dataSO->objectpegawaiorderfk = $request['strukorder']['penanggungjawabfk'];
            if (isset($request['strukorder']['mengetahuifk'])) {
                $dataSO->objectpetugasfk = $request['strukorder']['mengetahuifk'];
            }
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = $request['strukorder']['totaldiskon'];
            $dataSO->totalhargasatuan = $request['strukorder']['totalharga'];
            $dataSO->totalharusdibayar = $request['strukorder']['grandtotal'];
            $dataSO->totalpph = 0;
            $dataSO->statusorder = $request['strukorder']['statusorder'] ?? 0;
            $dataSO->totalppn =  $request['strukorder']['totalppn'];
            $dataSO->kelompokbarangfk =  $request['strukorder']['kelompokbarangfk'];
            $dataSO->kelompokbarang =  $request['strukorder']['kelompokbarang'];
            $dataSO->save();
            $SO = [
                "norec"  => $dataSO->norec,
                "objectkelompoktransaksifk" => $dataSO->objectkelompoktransaksifk,

            ];
            foreach ($request['details'] as $item) {
                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $kdprofile;
                $dataOP->statusenabled = true;
                $dataOP->hasilkonversi = $item['nilaikonversi'];
                $dataOP->iscito = 0;
                $dataOP->noorderfk = $SO['norec'];
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->qtyproduk = $item['jumlah'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectsatuanstandarfk = $item['satuanstandarfk'];
                $dataOP->strukorderfk = $SO['norec'];
                $dataOP->tglpelayanan = $request['strukorder']['tglUsulan'];
                $dataOP->hargasatuan = $item['hargasatuan'];
                $dataOP->hargadiscount = $item['hargadiscount'];
                $dataOP->hargappn = $item['ppn'];
                $dataOP->deskripsiprodukquo = $item['spesifikasi'] ?? null;
                $dataOP->tglpelayananakhir = $item['tglkebutuhan'];
                $dataOP->save();
            }
                $datanorecSR='';
                if ($request['strukorder']['norecrealisasi'] == '') {
                    $dataSR= new StrukRealisasi();
                    $norealisasi = $this->generateCode(new StrukRealisasi(),'norealisasi',10,'RA-'.$this->getDateTime()->format('ym'), $kdprofile);
                    $dataSR->norec = $dataSR->generateNewId();
                    $dataSR->kdprofile = $kdprofile;
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
                    $dataSR->objectmataanggaranfk = isset($request['strukorder']['objectmataanggaranfk']) ?  $request['strukorder']['objectmataanggaranfk'] : null;
                    $dataSR->totalbelanja = $request['strukorder']['grandtotal'];
                    $dataSR->save();
                    if ($request['strukorder']['norecrealisasi'] == '') {
                        $datanorecSR = $dataSR->norec;
                    }else{
                        $datanorecSR = $request['strukorder']['norecrealisasi'];
                    }
                }
                if ($request['strukorder']['norecrealisasi'] == '') {
                    $dataRR= new RiwayatRealisasi();
                    $dataRR->norec = $dataRR->generateNewId();
                    $dataRR->kdprofile = $kdprofile;
                    $dataRR->statusenabled = true;
                }else {

                    $dataRR = RiwayatRealisasi::where('objectstrukrealisasifk', $request['strukorder']['norecrealisasi'])->first();

                }
                $dataRR->objectstrukrealisasifk = $datanorecSR;
                $dataRR->objectstrukfk = $SO['norec'];
                $dataRR->objectkelompoktransaksifk = $SO['objectkelompoktransaksifk'];
                $dataRR->tglrealisasi =$request['strukorder']['tglUsulan'];
                $dataRR->objectpetugasfk = $request['strukorder']['penanggungjawabfk'];
                $dataRR->noorderintern = $noOrder;
                if (isset($request['strukorder']['keteranganorder'])) {
                    $dataRR->keteranganlainnya = $request['strukorder']['keteranganorder'];
                }
                $dataRR->save();
                DB::commit();
                $result = [
                    'status' => 201,
                    'message' => "Simpan Usulan Permintaan Barang Berhasil",
                    'data' =>$dataRR
                ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message'  => "Simpan Usulan Permintaan Barang Gagal",
                "data" => $e->getMessage() .$e->getLine(),
            ];
        }

        return $this->respond($result,$result['status'],$result['message']);
    }
    public function getDataDetailPurchaseRequest(Request $request){

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
    public function getDataDetailPerencanaan(Request $request)
    {

        $idProfile = $this->kdProfile;
        $data = DB::table('strukpraorder_t as sp')
            ->JOIN('strukpraorderdetail_t as op', 'op.noorderfk', '=', 'sp.norec')
            ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.rencanaorderfk', '=', 'sp.norec')
            ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec', '=', 'rr.objectstrukrealisasifk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpetugasfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->LEFTJOIN('strukverifikasi_t as sv', 'sv.norec', '=', 'sp.objectsrukverifikasifk')
            ->LEFTJOIN('strukverifikasi_t as sv1', 'sv1.norec', '=', 'sp.objectsrukverifikasikafk')
            ->select(
                'sp.norec',
                'sp.tglorder',
                'sp.noorder',
                'sp.noorderintern',
                'pg.namalengkap as penanggungjawab',
                'pg2.namalengkap as mengetahui',
                'sp.tglvalidasi as tglkebutuhan',
                'sp.alamattempattujuan',
                'sp.keteranganlainnya',
                'sp.tglvalidasi',
                'sp.noorderintern',
                'sp.keterangankeperluan',
                'sp.keteranganorder',
                'ru.namaruangan as ruangan',
                'ru.id as ruid',
                'ru2.namaruangan as ruangantujuan',
                'ru2.id as ruidtujuan',
                'sp.totalhargasatuan',
                'sp.status',
                'sr.norec as norecrealisasi',
                'sv.noverifikasi as noverifpengelolaurusan',
                'sv1.noverifikasi as noverifkepalainstalasi'
            )
            ->where('sp.kdprofile', $idProfile);
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('sp.tglorder', '>=', $request['tglAwal'] . ' 00:00');
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'] . ' 23:59';
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
        $data = $data->where('sp.norec' ,$request->norecOrder);
        $data = $data->where('sp.statusenabled', true);
        $data = $data->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('RENCANA USULAN PERMINTAAN BARANG'));
        $data = $data->orderBy('sp.tglorder');
        $data = $data->first();

            $details = DB::select(
                DB::raw("
                     select  pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargadiscount,spd.hargappn,(spd.qtyproduk*(spd.hargasatuan)) as total,
                    spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid
                     from strukpraorderdetail_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $idProfile and strukorderfk=:norec"),
                array(
                    'norec' => $data->norec,
                )
            );

            $results = array(
                'tglorder' => $data->tglorder,
                'noorder' => $data->noorder,
                'norec' => $data->norec,
                'noorderintern' => $data->noorderintern,
                'penanggungjawab' => $data->penanggungjawab,
                'keterangan' => $data->keteranganorder,
                'koordinator' => $data->keteranganlainnya,
                'tglkebutuhan' => $data->tglkebutuhan,
                'tglusulan' => $data->tglorder,
                'nousulan' => $data->noorderintern,
                'namapengadaan' => $data->keterangankeperluan,
                'mengetahui' => $data->mengetahui,
                'ruangan' => $data->ruangan,
                'ruangantujuan' => $data->ruangantujuan,
                'totalhargasatuan' => $data->totalhargasatuan,
                'status' => $data->status,
                'noverifpengelolaurusan' => $data->noverifpengelolaurusan,
                'noverifkepalainstalasi' => $data->noverifkepalainstalasi,
                'details' => $details,
                'norecrealisasi' => $data->norecrealisasi,
            );

        $result = array(
            'daftar' => $results,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function batalPO(Request $request)
    {

        DB::beginTransaction();
        try {

            StrukOrder::where('kdprofile', $this->kdProfile)->where('norec', $request['norec'])->update(['statusenabled' => false]);
            OrderPelayanan::where('kdprofile', $this->kdProfile)->where('noorderfk', $request['norec'])->update(['statusenabled' => false]);

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

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function batalVerif(Request $request)
    {

        DB::beginTransaction();
        try {

            StrukOrder::where('norec',$request['norec'])->update(['statusorder' => 0]);

            DB::commit();

            $result = [
                'status' => 200,
                'message' => 'Batal Verif Berhasil',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => 'Batal Verif Gagal',
                'data' => $e->getMessage(),
            ];
        }

        return $this->respond($result,$result['status'],$result['message']);
    }
    public function getDaftarRencanaUsulanPermintaan(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dataLogin = $request->all();
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.objectloginuserfk',$this->kdUser())
            ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $data = DB::table('strukpraorder_t as sp')
            ->JOIN('strukpraorderdetail_t as op', 'op.noorderfk', '=', 'sp.norec')
            ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.rencanaorderfk', '=', 'sp.norec')
            ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec', '=', 'rr.objectstrukrealisasifk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpetugasfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->LEFTJOIN('strukverifikasi_t as sv', 'sv.norec', '=', 'sp.objectsrukverifikasifk')
            ->LEFTJOIN('strukverifikasi_t as sv1', 'sv1.norec', '=', 'sp.objectsrukverifikasikafk')
            ->select(
                'sp.norec',
                'sp.tglorder',
                'sp.noorder',
                'pg.namalengkap as penanggungjawab',
                'pg2.namalengkap as mengetahui',
                'sp.tglvalidasi as tglkebutuhan',
                'sp.alamattempattujuan',
                'sp.keteranganlainnya',
                'sp.tglvalidasi',
                'sp.noorderintern',
                'sp.keterangankeperluan',
                'sp.keteranganorder',
                'ru.namaruangan as ruangan',
                'ru.id as ruid',
                'ru2.namaruangan as ruangantujuan',
                'ru2.id as ruidtujuan',
                'sp.totalhargasatuan',
                'sp.status',
                'sr.norec as norecrealisasi',
                'sv.noverifikasi as noverifpengelolaurusan',
                'sv1.noverifikasi as noverifkepalainstalasi'
            )
            ->where('sp.kdprofile', $idProfile);
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('sp.tglorder', '>=', $request['tglAwal'] . ' 00:00');
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'] . ' 23:59';
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
        $data = $data->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('RENCANA USULAN PERMINTAAN BARANG'));
        $data = $data->orderBy('sp.tglorder');
        $data = $data->get();

        $results = array();
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("
                     select  pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargadiscount,spd.hargappn,(spd.qtyproduk*(spd.hargasatuan)) as total,
                    spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid
                     from strukpraorderdetail_t as spd
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
                'noverifpengelolaurusan' => $item->noverifpengelolaurusan,
                'noverifkepalainstalasi' => $item->noverifkepalainstalasi,
                'details' => $details,
                'norecrealisasi' => $item->norecrealisasi,
            );
        }

        $result = array(
            'daftar' => $results,
            'datalogin' => $dataLogin,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }
    public function saveRencanaUsulanPermintaan(Request $request)
    {
        $idProfile = $this->kdProfile;
        DB::beginTransaction();
        $dataLogin = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataLogin['userData']['id'])
            ->first();
        try {
            if ($request['strukorder']['norec'] == '') {
                //###1###
                //CARI KODE USULAN BERDASARKAN RUANGAN PENG-USUL
                // $dataRuanganLogin = DB::table('maploginusertoruangan_s as mlu')
                //     ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
                //     ->select('ru.id', 'ru.namaruangan', 'ru.website')
                //     ->where('ru.id', $request['strukorder']['ruanganfkPengusul'])
                //     ->first();
                //####1####

                $dataSO = new StrukPraOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;
                $dataSO->noorder = $request['strukorder']['notransaksi'];
            } else {
                $dataSO = StrukPraOrder::where('norec', $request['strukorder']['norec'])->first();
                $dataSO->nostruk;

                StrukPraOrderDetail::where('strukorderfk', $request['strukorder']['norec'])
                    ->delete();
            }
            $dataSO->isdelivered = 0;
            $dataSO->objectkelompoktransaksifk = $this->kelompokTransaksi('RENCANA USULAN PERMINTAAN BARANG');
            $dataSO->tglorder = $request['strukorder']['tglperencanaan'];
            $dataSO->tglvalidasi = $request['strukorder']['tglDibutuhkan'];
            $dataSO->noorderintern = $request['strukorder']['noperencanaan'];
            $dataSO->objectpegawaiperencanafk = $dataPegawai->objectpegawaifk;
            $dataSO->statusorder = 0;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = $request['strukorder']['totalharga'];
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn =  $request['strukorder']['totalppn'];
            $dataSO->keteranganorder =  $request['strukorder']['jenisperencanaan'];
            $dataSO->objectmataanggaranfk =  $request['strukorder']['mataanggaran'];
            $dataSO->objectsumberdanafk =  $request['strukorder']['sumberdana'];
            $dataSO->save();
            $SO = array(
                "norec"  => $dataSO->norec,
                "objectkelompoktransaksifk" => $dataSO->objectkelompoktransaksifk,

            );

            foreach ($request['details'] as $item) {
                $dataOP = new StrukPraOrderDetail();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->hasilkonversi = $item['nilaikonversi'];
                $dataOP->iscito = 0;
                $dataOP->noorderfk = $SO['norec'];
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->objectasalprodukfk = $request['strukorder']['sumberdana'];
                $dataOP->qtyproduk = $item['jumlah'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectsatuanstandarfk = $item['satuanstandarfk'];
                $dataOP->strukorderfk = $SO['norec'];
                $dataOP->tglpelayanan = $request['strukorder']['tglDibutuhkan'];
                $dataOP->hargasatuan = $item['hargasatuan'];
                $dataOP->hargadiscount = $item['hargadiscount'];
                $dataOP->hargappn = $item['ppn'];
                // $dataOP->deskripsiprodukquo = $item['spesifikasi']; //$item['spesifikasi'];
                $dataOP->tglpelayananakhir = $request['strukorder']['tglDibutuhkan'];
                $dataOP->save();
            }

            //***** Struk Realisasi *****
            $datanorecSR = '';
            if ($request['strukorder']['norecrealisasi'] == '') {
                $dataSR = new StrukRealisasi();
                $norealisasi = $this->generateCode(new StrukRealisasi(), 'norealisasi', 10, 'RA-' . $this->getDateTime()->format('ym'), $idProfile);
                $dataSR->norec = $dataSR->generateNewId();
                $dataSR->kdprofile = $idProfile;
                $dataSR->statusenabled = true;
                $dataSR->norealisasi = $norealisasi;
            } else {
                $dataRR = StrukRealisasi::where('norec', $request['strukorder']['norecrealisasi'])->first();
            }
            $dataSR->tglrealisasi = $request['strukorder']['tglUsulan'];
            $dataSR->totalbelanja = $request['strukorder']['totalharga'];
            $dataSR->save();
            $SR = array(
                "norec"  => $dataSR->norec,
            );

            //***** Riwayat Realisasi *****
            if ($request['strukorder']['norecrealisasi'] == '') {
                $dataRR = new RiwayatRealisasi();
                $dataRR->norec = $dataRR->generateNewId();
                $dataRR->kdprofile = $idProfile;
                $dataRR->statusenabled = true;
                $dataRR->objectkelompoktransaksifk = $this->kelompokTransaksi('USULAN PERMINTAAN BARANG/JASA');
                $dataRR->rencanaorderfk = $SO['norec'];
            } else {
                $dataRR = RiwayatRealisasi::where('objectstrukrealisasifk', $request['strukorder']['norecrealisasi'])->first();
            }
            $dataRR->objectstrukrealisasifk = $SR['norec'];
            $dataRR->tglrealisasi = $request['strukorder']['tglUsulan'];
            $dataRR->objectpetugasfk = $dataPegawai->objectpegawaifk;
            $dataRR->noorderintern = $request['strukorder']['noperencanaan'];
            $dataRR->save();

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Usulan Permintaan Barang Berhasil",
                'data' =>$dataSO
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => "Usulan Permintaan Barang Gagal",
                "data" => $e->getMessage(),
            );
        }
        return $this->respond($result,$result['status'],$result['message']);
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
                    ru2.id AS ruidtujuan,ma.norec AS mataanggaranfk,ma.mataanggaran,ap.id AS apid,ap.asalproduk,sp.totalhargasatuan"))
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
        $data = $data->where('sp.objectkelompoktransaksifk',$this->kelompokTransaksi('USULAN PERMINTAAN BARANG/JASA') );
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
                'details' => $details,
            );
        }

        $result = [
            'daftar' => $results,
            'datalogin' => $dataLogin,
            'message' => 'as@epic',
        ];

        return $this->respond($result);
    }
    public function getDetailRUPB(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dataReq = $request->all();
        $dataStruk = DB::table('strukpraorder_t as sp')
            ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.rencanaorderfk', '=', 'sp.norec')
            ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec', '=', 'rr.objectstrukrealisasifk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->LEFTJOIN('pegawai_m as pg1', 'pg1.id', '=', 'sp.objectpetugasfk')
            ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpegawaiperencanafk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru1', 'ru1.id', '=', 'sp.objectruangantujuanfk')
            ->select(DB::raw("
             sp.norec,sp.tglorder,sp.noorder,pg.namalengkap,pg.id as pgid,pg1.nippns,sp.alamat,
			 sp.alamattempattujuan,sp.objectmataanggaranfk,sp.objectsumberdanafk,sp.keteranganlainnya,sp.tglvalidasi,sp.noorderintern,sp.keterangankeperluan,
			 sp.nokontrakspk,sp.noorderrfq,sp.keteranganorder,sp.namarekanansales,sp.totalhargasatuan,
			 sp.objectpetugasfk,pg1.namalengkap as mengetahui,pg1.nippns,sr.norealisasi,sr.norec as norecrealisasi,
			 ru.id as idunitpengusul,ru.namaruangan as unitpengusul,ru1.id as idunittujuan,ru1.namaruangan as unittujuan,
             EXTRACT(YEAR FROM sp.tglorder) AS tahunusulan,pg2.id as idperencana,pg2.namalengkap as namaperencana"))
            ->where('sp.kdprofile', $idProfile);

        if (isset($request['norecOrder']) && $request['norecOrder'] != "" && $request['norecOrder'] != "undefined") {
            $dataStruk = $dataStruk->where('sp.norec', '=', $request['norecOrder']);
        }
        $dataStruk = $dataStruk->first();

        $detail = array(
            'tglorder' => $dataStruk->tglorder,
            'noorder' => $dataStruk->noorder,
            'norec' => $dataStruk->norec,
            'petugasid' => $dataStruk->pgid,
            'petugas' => $dataStruk->namalengkap,
            'petugasmengetahui' => $dataStruk->mengetahui,
            'petugasmengetahuiid' => $dataStruk->objectpetugasfk,
            'nippns' => $dataStruk->nippns,
            'keterangan' => $dataStruk->keteranganorder,
            'koordinator' => $dataStruk->keteranganlainnya,
            'tglusulan' => $dataStruk->tglvalidasi,
            'nousulan' => $dataStruk->noorderintern,
            'namapengadaan' => $dataStruk->keterangankeperluan,
            'nokontrak' => $dataStruk->nokontrakspk,
            'tahunusulan' => $dataStruk->tahunusulan,
            'totalhargasatuan' => $dataStruk->totalhargasatuan,
            'norealisasi' => $dataStruk->norealisasi,
            'norecrealisasi' => $dataStruk->norecrealisasi,
            'idunitpengusul' => $dataStruk->idunitpengusul,
            'unitpengusul' => $dataStruk->unitpengusul,
            'idunittujuan' => $dataStruk->idunittujuan,
            'unittujuan' => $dataStruk->unittujuan,
            'objectsumberdanafk' =>$dataStruk->objectsumberdanafk,
            'objectmataanggaranfk' =>$dataStruk->objectmataanggaranfk
        );

        $i = 0;
        $dataStok = $details = DB::select(
            DB::raw("select spd.norec as norec_op, pr.id as produkfk,pr.namaproduk,pr.kdproduk,
                                 pr.spesifikasi,ss.satuanstandar,ss.id as ssid,spd.qtyproduk,spd.hargasatuan,
                                 spd.hargadiscount,spd.hargappn,spd.qtyproduk*spd.hargasatuan as subtotal,
                                 ((spd.qtyproduk*(spd.hargasatuan)) + spd.hargappn- spd.hargadiscount ) as total,
                                 spd.hasilkonversi,spd.strukorderfk,spd.tglpelayananakhir as tglkebutuhan,spd.objectasalprodukfk as objectasalprodukfk
                    from strukpraorderdetail_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    left JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                    left JOIN status_barang_m as sb on sb.id = spd.objectstatusbarang
                    where spd.kdprofile = $idProfile and spd.strukorderfk=:norec"),
            array(
                'norec' => $request['norecOrder'],
            )
        );
        $jmlstok = 0;
        $details = [];
        foreach ($dataStok as $item) {
            $i = $i + 1;
            $details[] = array(
                'no' => $i,
                'kdproduk' => $item->kdproduk,
                'produkfk' => $item->produkfk,
                'norec_op' => $item->norec_op,
                'namaproduk' => $item->namaproduk,
                'nilaikonversi' => $item->hasilkonversi,
                'satuanstandarfk' => $item->ssid,
                'satuanstandar' => $item->satuanstandar,
                'satuanviewfk' => $item->ssid,
                'satuanview' => $item->satuanstandar,
                'spesifikasi' => $item->spesifikasi,
                'jumlah' => (float)$item->qtyproduk,
                'hargasatuan' => $item->hargasatuan,
                'hargadiscount' => $item->hargadiscount,
                'ppn' => $item->hargappn,
                'subtotal' => $item->subtotal,
                'total' => $item->total,
                'persendiscount' => 0,
                'persenppn' => 0,
                'keterangan' => '',
                'nobatch' => '',
                'tglkadaluarsa' => null,
                'tglkebutuhan' => $item->tglkebutuhan,
                'objectasalprodukfk' =>$item->objectasalprodukfk
            );
        }

        $result = array(
            'detail' => $detail,
            'details' => $details,
            'datalogin' => $dataReq,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }
    public function saveUsulanPermintaan2(Request $request) {
        $idProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['strukorder']['norec_so'] == '') {
                //###1###
                //CARI KODE USULAN BERDASARKAN RUANGAN PENG-USUL
                // $dataRuanganLogin = DB::table('maploginusertoruangan_s as mlu')
                //     ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
                //     ->select('ru.id', 'ru.namaruangan', 'ru.website')
                //     ->where('mlu.kdprofile', $idProfile)
                //     ->where('ru.id', $request['strukorder']['ruanganfkPengusul'])
                //     ->first();

                $prefix =  '/' . $this->KonDecRomawi($this->getDateTime()->format('m')) . '/' . $this->getDateTime()->format('y');
                $resultr = StrukOrder::where('noorder', 'ILIKE', '%' . $prefix)->max('noorder');
                $subPrefix = str_replace($prefix, '', $resultr);
                $noOrder = (str_pad((int)$subPrefix + 1, 3, "0", STR_PAD_LEFT)) . '' . $prefix;
                //####1####

                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;
                $dataSO->noorder = $request['strukorder']['noUsulan'];
            } else {
                $dataSO = StrukOrder::where('norec', $request['strukorder']['norec'])->first();
                // $noStruk = $dataSO->nostruk;

                $delSPD = OrderPelayanan::where('strukorderfk', $request['strukorder']['norec'])
                    ->delete();
            }
            $dataSO->isdelivered = 0;
            $dataSO->objectkelompoktransaksifk = $this->kelompokTransaksi('USULAN PERMINTAAN BARANG/JASA');
            $dataSO->qtyjenisproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->qtyproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->tglorder = $request['strukorder']['tglUsulan'];
            $dataSO->tglvalidasi = $request['strukorder']['tglDibutuhkan'];
            // $dataSO->keteranganlainnya = $request['strukorder']['koordinator'];
            $dataSO->noorderintern = $request['strukorder']['nousulan'];
            // $dataSO->objectruanganfk = $request['strukorder']['ruanganfkPengusul'];
            // $dataSO->objectruangantujuanfk = $request['strukorder']['ruanganfkTujuan'];
            // $dataSO->objectpegawaiorderfk = $request['strukorder']['penanggungjawabfk'];
            // $dataSO->objectpetugasfk = $request['strukorder']['mengetahuifk'];
            $dataSO->noorderintern = $request['strukorder']['nousulan'];
            $dataSO->statusorder = 0;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = $request['strukorder']['total'];
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn =  $request['strukorder']['ppn'];

            $dataSO->save();
            $SO = array(
                "norec"  => $dataSO->norec,
                "objectkelompoktransaksifk" => $dataSO->objectkelompoktransaksifk,

            );

            foreach ($request['details'] as $item) {
                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->hasilkonversi = $item['nilaikonversi'];
                $dataOP->iscito = 0;
                $dataOP->noorderfk = $SO['norec'];
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->objectasalprodukfk = $request['strukorder']['asalproduk'];
                $dataOP->qtyproduk = $item['jumlah'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectsatuanstandarfk = $item['satuanstandarfk'];
                $dataOP->strukorderfk = $SO['norec'];
                $dataOP->tglpelayanan = $request['strukorder']['tglUsulan'];
                $dataOP->hargasatuan = $item['hargasatuan'];
                $dataOP->hargadiscount = $item['hargadiscount'];
                $dataOP->hargappn = $item['ppn'];
                $dataOP->deskripsiprodukquo = $item['spesifikasi']; //$item['spesifikasi'];
                $dataOP->tglpelayananakhir = $item['tglkebutuhan'];
                $dataOP->save();
            }

            //***** Struk Realisasi *****
            $datanorecSR = '';
            if ($request['strukorder']['norecrealisasi'] == '') {
                $dataSR = new StrukRealisasi();
                $norealisasi = $this->generateCode(new StrukRealisasi(), 'norealisasi', 10, 'RA-' . $this->getDateTime()->format('ym'), $idProfile);
                $dataSR->norec = $dataSR->generateNewId();
                $dataSR->kdprofile = $idProfile;
                $dataSR->statusenabled = true;
                $dataSR->norealisasi = $norealisasi;
                $dataSR->tglrealisasi = $request['strukorder']['tglUsulan'];
                $dataSR->totalbelanja = $request['strukorder']['total'];
                $dataSR->save();
                if ($request['strukorder']['norecrealisasi'] == '') {
                    $datanorecSR = $dataSR->norec;
                } else {
                    $datanorecSR = $request['strukorder']['norecrealisasi'];
                }
            } else {
                $dataSR = StrukRealisasi::where('norec', $request['strukorder']['norecrealisasi'])->first();
                $dataSR->tglrealisasi = $request['strukorder']['tglUsulan'];
                $dataSR->objectmataanggaranfk = $request['strukorder']['objectmataanggaranfk'];
                $dataSR->totalbelanja = $request['strukorder']['total'];
                $dataSR->save();
                if ($request['strukorder']['norecrealisasi'] == '') {
                    $datanorecSR = $dataSR->norec;
                } else {
                    $datanorecSR = $request['strukorder']['norecrealisasi'];
                }
            }

            //***** Riwayat Realisasi *****
            if ($request['strukorder']['norecrealisasi'] == '') {
                $dataRR = new RiwayatRealisasi();
                $dataRR->norec = $dataRR->generateNewId();
                $dataRR->kdprofile = $idProfile;
                $dataRR->statusenabled = true;
            } else {

                $dataRR = RiwayatRealisasi::where('objectstrukrealisasifk', $request['strukorder']['norecrealisasi'])->first();
            }
            $dataRR->objectstrukrealisasifk = $datanorecSR;
            $dataRR->objectstrukfk = $SO['norec'];
            $dataRR->objectkelompoktransaksifk = $SO['objectkelompoktransaksifk'];
            $dataRR->tglrealisasi = $request['strukorder']['tglUsulan'];
            // $dataRR->objectpetugasfk = $request['strukorder']['penanggungjawabfk'];
            $dataRR->noorderintern = $request['strukorder']['nousulan'];
            // $dataRR->keteranganlainnya = $request['strukorder']['keteranganorder'];
            $dataRR->save();

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Usulan Permintaan Barang Berhasil",
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => "Usulan Permintaan Barang Gagal",
                "data" => $e->getMessage(),
            );
        }

        return $this->respond($result,$result['status'],$result['message']);

    }

    public function saveBatalUsulanPermintaanBarang(Request $request)
    {
        $idProfile = $this->kdProfile;
        DB::beginTransaction();
        $tglAyeuna = date('Y-m-d H:i:s');
        $dataLogin = $request->all();
        try {
            $Kel = StrukPraOrder::where('norec', $request['norec'])
                ->update([
                    'statusenabled' => 'f',
                ]);

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Usulan Permintaan Barang Berhasil",
            );

        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => "Usulan Permintaan Barang Gagal",
                "data" => $e->getMessage(),
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
}
