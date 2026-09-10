<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalProduk;
use App\Models\Master\ProdukFormulaProduksi;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\ProduksiNonSteril;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Traits\Valet;
use Exception;
use Ramsey\Uuid\Uuid;

class ProduksiObatCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDataMasterBarangProduksi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $result = [];
        $data = DB::table('produkformulaproduksi_m as pfp')
            ->join('produk_m AS pr', 'pr.id', '=', 'pfp.objectprodukhasilfk')
            ->join('satuanstandar_m AS ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select(DB::raw("
                    pr.id,pr.namaproduk,ss.id AS ssid,ss.satuanstandar,pfp.qtyhasil,pfp.keteranganlainnya
                "))
            ->where('pfp.kdprofile', $kdProfile)
            ->where('pr.statusenabled', true)
            ->where('pfp.statusenabled', true);

            if (isset($request['produkProduksiId']) && $request['produkProduksiId'] != '') {
                $data = $data->where('pr.id', $request['produkProduksiId']);
            }
            if (isset($request['namaprodukProduksi']) && $request['namaprodukProduksi'] != '') {
                $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaprodukProduksi'] . '%');
            }
            $data = $data->groupBy(DB::raw("
                pr.id,pr.namaproduk,ss.id,ss.satuanstandar,pfp.qtyhasil,pfp.keteranganlainnya
            "));
        $data = $data->get();


        $result = [];
        foreach ($data as $item) {
            $details = collect(DB::select("
                SELECT pfp.id,pr.id,pr.id AS produkfk,pr.namaproduk,ss.id AS ssid,ss.satuanstandar,
                       pfp.qtyprodukasal AS jumlah,pfp.nilaikonversi,ss.id AS satuanstandarfk
                FROM produkformulaproduksi_m AS pfp
                INNER JOIN produk_m AS pr ON pr.id = pfp.objectprodukasalfk
                INNER JOIN satuanstandar_m AS ss ON ss.id = pfp.satuanprodukasal
                WHERE pr.kdprofile = $kdProfile AND pr.statusenabled = true AND pfp.statusenabled = true
                      AND pfp.objectprodukhasilfk = $item->id;
            "));
            // dd($details);

            $result[] = array(
                'produproduksifk' => $item->id,
                'namaprodukproduksi' => $item->namaproduk,
                'satuanproduksifk' => $item->ssid,
                'satuanproduksi' => $item->satuanstandar,
                'qtyhasil' => $item->qtyhasil,
                'keteranganlainnya' => $item->keteranganlainnya,
                'details' => $details,
            );
        }

        $results = array(
            'data' => $result,
            'as' => 'ea@epic'
        );
        return $this->respond($results);
    }

    public function saveMasterProdukFormulaProduksi(Request $request)
    {
   
        DB::beginTransaction();
        try {

            $dataAwal = ProdukFormulaProduksi::where('kdprofile',$this->kdProfile)->where('statusenabled',true)
                        ->where('objectprodukhasilfk', $request['produkproduksifk'])
                        ->count();

            if ($dataAwal > 0) {
                ProdukFormulaProduksi::where('kdprofile', $this->kdProfile)
                    ->where('objectprodukhasilfk', $request['produkproduksifk'])
                    ->delete();
            }

            foreach ($request['paketobat'] as $item) {
                $map = new ProdukFormulaProduksi();
                $map->id = ProdukFormulaProduksi::max('id') + 1;
                $map->kdprofile = $this->kdProfile;
                $map->statusenabled = true;
                $map->norec = $this->Uuid4();
                $map->objectprodukasalfk = $item['produkfk'];
                $map->objectprodukhasilfk = $request['produkproduksifk'];
                $map->formulaproduksi = $request['keterangan'];
                $map->keteranganlainnya = $request['keterangan'];
                $map->qtyprodukasal = $item['jumlah'];
                $map->nilaikonversi = 1;
                $map->qtyhasil = $request['jumlahproduksi'];
                $map->satuanprodukasal = $item['satuanstandarfk'];
                $map->save();
            }

            DB::commit();

            $result = ['status' => 201, 'message' => 'Simpan Data Berhasil', 'detail' => $map];

        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message' => 'Simpan Data Gagal !',
                'respon' => $e->getMessage(),
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function DeleteMasterProduksi(Request $request)
    {

        DB::beginTransaction();
        try {

            ProdukFormulaProduksi::where('objectprodukhasilfk', $request['produproduksifk'])
                ->where('kdprofile', $this->kdProfile)->where('statusenabled',true)
                ->delete();

            DB::commit();

            $result = array(
                'status' => 201,
                'message' => 'Hapus Data Berhasil',
            );

        } catch (Exception $e) {

            DB::rollBack();
            $result = array(
                'status' => 400,
                'message' => 'Hapus Data Gagal !',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function comboProduksiObat(Request $request)
    {

        $result ['mapruangan'] = DB::table('maploginusertoruangan_s as mlu')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
        ->select('ru.id', 'ru.namaruangan')
        ->where('mlu.objectloginuserfk', $this->getUserId())
        ->where('mlu.statusenabled', true)
        ->where('ru.statusenabled', true)
        ->get();

        $result['pegawailogin'] = DB::table('loginuser_s as lu')
            ->JOIN('pegawai_m as pg', 'pg.id', 'lu.objectpegawaifk')
            ->select('pg.id', 'pg.namalengkap')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('lu.id', $this->getUserId())
            ->get();

        $dataProduk = DB::table('produk_m as pr')
        ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        ->JOIN('produkformulaproduksi_m as pfp', 'pfp.objectprodukhasilfk', '=', 'pr.id')
        ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pfp.qtyhasil', 'pfp.keteranganlainnya')
        ->where('pr.statusenabled', true)
        ->where('jp.id', 97)
        ->groupBy('pr.id', 'ss.id', 'pfp.qtyhasil', 'pfp.keteranganlainnya', 'pr.namaproduk', 'ss.satuanstandar')
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
        ->where('ks.statusenabled', true)
        ->get();

        $dataProdukResult = [];
        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk  as $item2) {
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
                'qtyhasil' =>   $item->qtyhasil,
                'keteranganlainnya' =>   $item->keteranganlainnya,
                'konversisatuan' => $satuanKonversi,
            );
        }

        $result['produk'] = $dataProdukResult;
        
        return $this->respond($result);
    }

    public function getDetailMasterProduksi(Request $request)
    {
        $data = DB::table('produkformulaproduksi_m as pfp')
            ->leftJOIN('produk_m as pr1', 'pr1.id', '=', 'pfp.objectprodukhasilfk')
            ->leftJOIN('produk_m as pr2', 'pr2.id', '=', 'pfp.objectprodukasalfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pfp.satuanprodukasal')
            ->leftJOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'pr1.objectsatuanstandarfk')
            ->select(
                'pr1.id as idprodukhasil',
                'pr1.namaproduk as namaprodukhasil',
                'pr2.id as idprodukasal',
                'pr2.namaproduk as namaprodukasal',
                'ss.id as ssid',
                'ss.satuanstandar as satuanview',
                'pfp.qtyprodukasal',
                'pfp.nilaikonversi',
                'pfp.keteranganlainnya',
                'pfp.id',
                'pfp.norec',
                'pfp.qtyhasil',
                'ss2.id as ssid2',
                'ss2.satuanstandar as satuanview2'
            );

        if (isset($request['namaprodukhasil']) && $request['namaprodukhasil'] != "" && $request['namaprodukhasil'] != "undefined") {
            $data = $data->where('pr1.namaproduk', 'ilike', '%' . $request['namaprodukhasil']);
        }
        if (isset($request['pridhasil']) && $request['pridhasil'] != "" && $request['pridhasil'] != "undefined") {
            $data = $data->where('pr1.id', $request['pridhasil']);
        }
        $data = $data->where('pfp.statusenabled', true);
        //        $data = $data->take(50);
        if (isset($request['idprodukhasil']) && $request['idprodukhasil'] != "" && $request['idprodukhasil'] != "undefined") {
            $data = $data->where('pr1.id', '=', $request['idprodukhasil']);
        }
        $data = $data->get();

        $dataAsalProduk = AsalProduk::mine()->get();

        $result = [];
        $stt = false;
        $head = [];
        foreach ($data as $item) {
            foreach ($head as $rrsl) {
                if ($item->idprodukhasil == $rrsl['idprodukhasil']) {
                    $stt = true;
                    break;
                } else {
                    $stt = false;
                }
            }
            if ($stt == false) {
                $head[] = array(
                    'idprodukhasil' => $item->idprodukhasil,
                    'namaprodukhasil' => $item->namaprodukhasil,
                    'qtyhasil' => $item->qtyhasil,
                    'keteranganlainnya' => $item->keteranganlainnya,
                    'ssid' => $item->ssid,
                    'satuanview' => $item->satuanview,
                    //                    'details' => [],
                );
            }
        }
        $results = [];
        foreach ($head as $item2) {
            $result = [];
            foreach ($data as $item) {
                if ($item->idprodukhasil == $item2['idprodukhasil']) {
                    $result[] = array(
                        'idprodukasal' => $item->idprodukasal,
                        'namaprodukasal' => $item->namaprodukasal,
                        'ssid' => $item->ssid,
                        'satuanview' => $item->satuanview,
                        'qtyprodukasal' => (float)$item->qtyprodukasal * (float)$item->nilaikonversi,
                        'nilaikonversi' => $item->nilaikonversi,
                        'id' => $item->id,
                        'norec' => $item->norec,
                    );
                }
            }
            $results[] = array(
                'produkhasilfk' => $item2['idprodukhasil'],
                'namaprodukhasil' => $item2['namaprodukhasil'],
                'qtyhasil' => $item2['qtyhasil'],
                'keterangan' => $item2['keteranganlainnya'],
                'ssid' => $item->ssid,
                'satuanview' => $item->satuanview,
                //                'details' => $result,
            );
        }
        $i = 0;
        $dataStok = DB::select(
            DB::raw("select sk.norec, sk.tglstruk,spd.objectprodukfk, spd.objectasalprodukfk,
                    sum(spd.qtyproduk) as qtyproduk,spd.harganetto1,spd.norec as norecspd
                    from stokprodukdetail_t as spd
                    inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                    where  spd.objectruanganfk =:ruanganid and spd.qtyproduk >0 
                    group by sk.norec,sk.tglstruk,spd.objectprodukfk, spd.objectasalprodukfk,
                    spd.harganetto1,spd.norec
                    order By sk.tglstruk"),
            array(
                'ruanganid' => $request->ruid
            )
        );
        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $hargasatuan = 0;
        $norecSPD = '';
        $hargadiscount = 0;
        $total = 0;
        foreach ($result as $item) {
            $i = $i + 1;
            $jmlstok = 0;
            $harganetto = 0.0;
            foreach ($dataStok as $item2) {
                if ($item2->objectprodukfk == $item['idprodukasal']) {
                    if ((float)$item2->qtyproduk >= (float)$item['qtyprodukasal'] * (float)$item['nilaikonversi']) {
                        $harganetto = $item2->harganetto1;
                        $nostrukterimafk = $item2->norec;
                        $norecSPD = $item2->norecspd;
                        $asalprodukfk = $item2->objectasalprodukfk;
                        $jmlstok = $jmlstok + (float)$item2->qtyproduk;
                    }
                }
            }
            foreach ($dataAsalProduk as $item3) {
                if ($asalprodukfk == $item3->id) {
                    $asalproduk = $item3->asalproduk;
                }
            }
            $formula[] = array(
                'no' => $i, #
                'hargajual' => 0, #
                'stock' => 0, #
                'harganetto' => $harganetto, #
                'nostrukterimafk' => $nostrukterimafk, #
                'ruanganfk' => null, #
                'asalprodukfk' => $asalprodukfk, #
                'asalproduk' => $asalproduk, #
                'norecspd' => $norecSPD, #
                'produkfk' => $item['idprodukasal'], #
                'namaproduk' => $item['namaprodukasal'], #
                'nilaikonversi' => $item['nilaikonversi'], #
                'satuanstandarfk' => $item['ssid'], #
                'satuanstandar' => $item['satuanview'], #
                'satuanviewfk' => $item['ssid'], #
                'satuanview' => $item['satuanview'], #
                'jmlstok' => $jmlstok, #
                'jumlah' => $item['qtyprodukasal'], #
                'jumlahbahan' => 0, #
                'hargasatuan' => $harganetto, #
                'hargadiscount' => 0, #
                'total' => 0, #
            );
        }

        $result = array(
            'head' => $results,
            'child' => $result,
            'details' => $formula,
            'stok' => $dataStok,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function saveProduksiObatNonSteril(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();

        $ruanganAsal = Ruangan::where('kdprofile',$this->kdProfile)->where('statusenabled',true)
                        ->where('id', $request['struk']['objectruanganfk'])
                        ->first();
      
        $strRuanganAsal = $ruanganAsal->namaruangan;
        try {
            $noStruk = $this->generateCodeBySeqTable(new ProduksiNonSteril, 'noproduksi', 14, 'FP-' . date('ym'), $kdProfile);

            if ($noStruk == '') {
                $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "message"  => $transMessage,
                    "as" => 'ea@epic',
                );
                return $this->setStatusCode($result['status'])->respond($result, $transMessage);
            }
            
            $dataPNS = new ProduksiNonSteril;
            $dataPNS->norec = $dataPNS->generateNewId();
            $dataPNS->kdprofile = $kdProfile;
            $dataPNS->statusenabled = true;
            $dataPNS->hargasatuan = $request['struk']['hargasatuan'];
            $dataPNS->jumlahproduksi = $request['struk']['jumlahproduksi'];
            $dataPNS->noproduksi = $request['struk']['kodeproduksi'] != '' ? $request['struk']['kodeproduksi'] : $noStruk;
            $dataPNS->objectpegawaiygmengetahuifk = $request['struk']['objectpegawaiygmengetahuifk'];
            $dataPNS->objectprodukfk = $request['struk']['objectprodukfk'];
            $dataPNS->satuan = $request['struk']['satuan'];
            $dataPNS->spesifikasi = $request['struk']['spesifikasi'];
            $dataPNS->tanggalexpired = $request['struk']['tanggalexpired'];
            $dataPNS->tglproduksi = date('Y-m-d H:i:s');
            $dataPNS->save();

            $norecPNS = $dataPNS->norec;

            $SP = new StrukPelayanan();
            $norecSP = $SP->generateNewId();
            // $noStruk = $this->generateCode(new StrukPelayanan, 'nostruk', 13, 'FP/'.$this->getDateTime()->format('ym/'));
            $SP->norec = $norecSP;
            $SP->kdprofile = $kdProfile;
            $SP->statusenabled = true;
            $SP->nostruk = $request['struk']['kodeproduksi'] != '' ? $request['struk']['kodeproduksi'] : $noStruk;
            $SP->noterima = $request['struk']['kodeproduksi'] != '' ? $request['struk']['kodeproduksi'] : $noStruk;

            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('PRODUKSI OBAT');

            $SP->objectruanganfk = $request['struk']['objectruanganfk'];
            $SP->keteranganlainnya = 'Farmasi Produksi';

            $SP->tglstruk = date('Y-m-d H:i:s');
            $SP->objectpegawaipenerimafk = $request['struk']['objectpegawaiygmengetahuifk'];
            $SP->objectpegawaimenerimafk = $request['struk']['objectpegawaiygmengetahuifk'];
            $SP->qtyproduk =  1;

            $SP->totalhargasatuan = $request['struk']['hargasatuan'];

            $SP->save();

            $norec_SP = $SP->norec;

            $SPD = new StrukPelayananDetail();
            $norecKS = $SPD->generateNewId();
            $SPD->norec = $norecKS;
            $SPD->kdprofile = $kdProfile;
            $SPD->statusenabled = true;
            $SPD->nostrukfk = $SP->norec;

            $SPD->objectasalprodukfk = 10;
            $SPD->objectprodukfk = $request['struk']['objectprodukfk'];
            $SPD->objectruanganfk = $request['struk']['objectruanganfk'];
            $SPD->objectruanganstokfk = $request['struk']['objectruanganfk'];
            $SPD->objectsatuanstandarfk = $request['struk']['satuan'];
            $SPD->hargadiscount =  0;
            $SPD->hargadiscountgive = 0;
            $SPD->hargadiscountsave = 0;
            $SPD->harganetto = $request['struk']['hargasatuan'];
            $SPD->hargapph = 0;
            $SPD->hargappn = 0;
            $SPD->hargasatuan = $request['struk']['hargasatuan'];
            $SPD->hasilkonversi = 1;
            //            $SPD->namaproduk = $item['namaproduk'];
            $SPD->keteranganlainnya = 'Farmasi Produksi';
            $SPD->hargasatuandijamin = 0;
            $SPD->hargasatuanppenjamin = 0;
            $SPD->hargatambahan = 0;
            $SPD->hargasatuanpprofile = 0;
            $SPD->isonsiteservice = 0;
            $SPD->kdpenjaminpasien = 0;
            $SPD->persendiscount = 0;
            $SPD->persenppn = 0;
            $SPD->qtyproduk = $request['struk']['jumlahproduksi'];
            $SPD->qtyprodukoutext = 0;
            $SPD->qtyprodukoutint = 0;
            $SPD->qtyprodukretur = 0;
            $SPD->satuan =  '-'; //$item['satuanstandar'];;
            $SPD->satuanstandar =  $request['struk']['satuan'];
            $SPD->tglpelayanan = date('Y-m-d H:i:s');
            $SPD->is_terbayar = 0;
            $SPD->linetotal = 0;
            $SPD->tglkadaluarsa = $request['struk']['tanggalexpired'];
            $SPD->nobatch = $request['struk']['kodeproduksi'] != '' ? $request['struk']['kodeproduksi'] : $noStruk;

            $SPD->save();

            //PENAMBAH STOCK HASIL PRODUKSI
            $saldoAwalPenerima = StokProdukDetail::where('objectruanganfk', $request['struk']['objectruanganfk'])
                                ->where('objectprodukfk', $request['struk']['objectprodukfk'])
                                ->sum('qtyproduk');

            $dataNewSPD = new StokProdukDetail;
            $dataNewSPD->norec = $dataNewSPD->generateNewId();
            $dataNewSPD->kdprofile = $kdProfile;
            $dataNewSPD->statusenabled = true;
            $dataNewSPD->objectasalprodukfk = 10;
            $dataNewSPD->hargadiscount = 0;
            $dataNewSPD->harganetto1 = $request['struk']['hargasatuan'];
            $dataNewSPD->harganetto2 = $request['struk']['hargasatuan'];
            $dataNewSPD->persendiscount = 0;
            $dataNewSPD->objectprodukfk = $request['struk']['objectprodukfk'];
            $dataNewSPD->qtyproduk = (float)$request['struk']['jumlahproduksi'];
            $dataNewSPD->qtyprodukonhand = 0;
            $dataNewSPD->qtyprodukoutext = 0;
            $dataNewSPD->qtyprodukoutint = 0;
            $dataNewSPD->objectruanganfk = $request['struk']['objectruanganfk'];
            $dataNewSPD->nostrukterimafk = $norec_SP;
            $dataNewSPD->noverifikasifk = null;
            $dataNewSPD->nobatch = $noStruk;
            $dataNewSPD->tglkadaluarsa = $request['struk']['tanggalexpired'];
            $dataNewSPD->tglpelayanan = date('Y-m-d H:i:s');
            $dataNewSPD->tglproduksi = date('Y-m-d H:i:s');
            $dataNewSPD->save();
            //            }
            //## KartuStok

            $this->kartu_STOK(array(
                "saldoawal" => (float) $saldoAwalPenerima,
                "qtyin" => (float)$request['struk']['jumlahproduksi'],
                "qtyout" => 0,
                "saldoakhir" => (float)$saldoAwalPenerima + (float)$request['struk']['jumlahproduksi'],
                "keterangan" => 'Farmasi Produksi ruangan ' . $strRuanganAsal .  ', Pada Produk : ' . $request['struk']['namaproduk']  .', Kode Produksi ' .  $SP->nostruk = $noStruk,
                "produkfk" =>  $request['struk']['objectprodukfk'],
                "ruanganfk" =>  $request['struk']['objectruanganfk'],
                "tglinput" =>  date('Y-m-d H:i:s'),
                "tglkejadian" =>  date('Y-m-d H:i:s'),
                "nostrukterimafk" => $norec_SP,
                "norectransaksi" =>  null,
                "tabletransaksi" => 'strukpelayanan_t',
                // "stokprodukdetailfk" =>  $item['norec_spd'],
                "flagfk" => null,
            ));
         

            foreach ($request['details'] as $item) {
                //PENGURANG STOCK

                $saldoAwalPengirim = StokProdukDetail::where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                    ->where('objectprodukfk', $item['produkfk'])
                                    ->where('objectruanganfk', $request['struk']['objectruanganfk'])
                                    ->sum('qtyproduk');
                if($saldoAwalPengirim <= 0){
                    DB::rollBack();
                    $message = 'Qty Produk ' . $item['namaproduk'] . ' , Tidak Cukup';
                    $result = ['message' => $message,'status' => 400];
                    return $this->respond($result,$result['status'],$result['message']);
                }                   

                StokProdukDetail::where('norec', $item['norecspd'])->where('statusenabled',true)
                        ->lockForUpdate()
                        ->decrement('qtyproduk',  (float)$item['jumlah'] * (float)$item['nilaikonversi']);

                //## KartuStok

                $this->kartu_STOK(array(
                    "saldoawal" => (float) $saldoAwalPengirim,
                    "qtyin" => 0,
                    "qtyout" =>  ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
                    "saldoakhir" => (float)$saldoAwalPengirim - ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
                    "keterangan" => 'Farmasi Produksi ruangan ' . $strRuanganAsal . ', Pada Produk : ' . $item['namaproduk'] . ' .Dengan Kode Produksi ' .  $SP->nostruk = $noStruk,
                    "produkfk" =>   $item['produkfk'],
                    "ruanganfk" =>  $request['struk']['objectruanganfk'],
                    "tglinput" =>  date('Y-m-d H:i:s'),
                    "tglkejadian" =>  date('Y-m-d H:i:s'),
                    "nostrukterimafk" =>  $item['nostrukterimafk'],
                    "norectransaksi" =>  $norecPNS,
                    "tabletransaksi" => 'produksinonsteril_t',
                    "stokprodukdetailfk" => $item['norecspd'],
                    "flagfk" => null,
                ));

            }

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan Data Berhasil",
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => "Simpan Data Gagal !",
                "respon" => $e->getMessage() . $e->getLine(),
                "as" => 'as@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);

       
    }

    public function getDaftarProduksiObat(Request $request)
    {
        $dataLogin = $request->all();
        $dateRange = array($request['tglAwal'], $request['tglAkhir']);

        $data = DB::table('strukpelayanan_t as sp')
        ->leftJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->LEFTJOIN('strukbuktipengeluaran_t as sbk', 'sbk.norec', '=', 'sp.nosbklastfk')
        ->select(
            'sp.tglstruk',
            'sp.nostruk',
            'rkn.namarekanan',
            'pg.namalengkap',
            'ru.namaruangan',
            'sp.norec',
            'sp.nofaktur',
            'sp.tglfaktur',
            'sp.totalharusdibayar',
            'sbk.nosbk'
        )
        ->whereBetween(DB::raw("CAST(sp.tglstruk as DATE)"), $dateRange)
        ->where('sp.statusenabled', true)
        ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('PRODUKSI OBAT'));

        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $data = $data->where('sp.nostruk', 'ilike', '%' . $request['nostruk'] . '%');
        }
    
        $data = $data->orderBy('sp.nostruk');
        $data = $data->get();

        $result = [];

        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("
                    select  pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargadiscount,
                    spd.hargappn,((spd.hargasatuan-spd.hargadiscount+spd.hargappn)*spd.qtyproduk) as total,spd.tglkadaluarsa,spd.nobatch
                    from strukpelayanandetail_t as spd 
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where nostrukfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );
            $result[] = array(
                'tglstruk' => $item->tglstruk,
                'nostruk' => $item->nostruk,
                'nofaktur' => $item->nofaktur,
                'tglfaktur' => $item->tglfaktur,
                'namarekanan' => $item->namarekanan,
                'norec' => $item->norec,
                'namaruangan' => $item->namaruangan,
                'namapenerima' => $item->namalengkap,
                'totalharusdibayar' => $item->totalharusdibayar,
                'nosbk' => $item->nosbk,
                'details' => $details,
            );
        }

        $hasil = array(
            'daftar' => $result,
            'datalogin' => $dataLogin,
            'message' => 'as@epic',
        );

        return $this->respond($hasil);
    }

    public function hapusObatProduksi(Request $request)
    {
        DB::beginTransaction();

        try {

            StrukPelayanan::where('norec', $request['norec'])->update(['statusenabled' => false]);

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Hapus Data Berhasil",
            );

        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => "Hapus Data Gagal !",
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }
}
