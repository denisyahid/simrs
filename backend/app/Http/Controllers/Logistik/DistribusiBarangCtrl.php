<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Master\SatuanResep;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukRetur;
use Illuminate\Support\Facades\App;
use App\Models\Transaksi\StrukReturDetail;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DistribusiBarangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getProduk(Request $r)
    {
        $idProfile = (int)$this->kdProfile;
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
            ->where('ks.kdprofile', $idProfile)
            ->where('ks.statusenabled', true)
            ->get();

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id','pr.kdproduk', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar','pr.kdproduk')
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', true);
        // ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dataProduk = $dataProduk->limit($r['limit']);
        }
        //->where('spd.qtyproduk','>',0)
        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar','pr.kdproduk');
        $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        $dataProduk = $dataProduk->get();

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
                'kdproduk' =>   $item->kdproduk,
                'kode_namaproduk' => $item->kdproduk . ' - ' . $item->namaproduk,
                'ssid' =>   $item->ssid,
                'kdproduk' => $item->kdproduk,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
            );
        }
        $res['produk'] = $dataProdukResult;
        return $this->respond($res);
    }

    public function getCombo(Request $r)
    {
        $idProfile = (int)$this->kdProfile;
        $res['ruangan'] = DB::table('maploginusertoruangan_s as mlu')
        ->join('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
        ->select('ru.id', 'ru.namaruangan')
        ->where('mlu.kdprofile', $this->kdProfile)
        ->where('mlu.statusenabled', true)
        ->where('ru.statusenabled', true)
        ->where('ru.kdprofile', $this->kdProfile)
        ->where('mlu.objectloginuserfk', $this->getUserId());
    //     return $r;
    // if (!$r->query('norec')) {
        
    //     $res['ruangan']->where('mlu.objectloginuserfk', $this->getUserId());
    // }
    
    $res['ruangan'] = $res['ruangan']->distinct()->get();
    

            // if(isset($r['maplog']) && $r['maplog'] == 'true'){
            //     $res['ruangan']=$res['ruangan']->where('mlu.objectloginuserfk', $this->getUserId());
            // }
            // if(isset($r['maplog']) && $r['maplog'] == 'false'){
            //     $res['ruangan']=$res['ruangan']->where('ru.objectdepartemenfk',14);
            // }

        $res['rutu'] = Ruangan::select('id', 'namaruangan')->where('kdprofile', $idProfile)->where('statusenabled', true)->get();
        $res['satuanresep'] = SatuanResep::mine()->get();
        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ss.id',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $idProfile)
            ->where('ks.statusenabled', true)
            ->get();
        $res['satuankonversi'] = $dataKonversiProduk;
        // $dataProduk = DB::table('produk_m as pr')
        //     ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        //     ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        //     ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        //     ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
        //     ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
        //     ->where('pr.kdprofile', $idProfile)
        //     ->where('pr.statusenabled', true);
        // // ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        // if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
        //     $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        // }
        // //->where('spd.qtyproduk','>',0)
        // $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
        // $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        // $dataProduk = $dataProduk->get();

        // $dataProdukResult = [];
        // foreach ($dataProduk as $item) {
        //     $satuanKonversi = [];
        //     foreach ($dataKonversiProduk  as $item2) {
        //         if ($item->id == $item2->objekprodukfk) {
        //             $satuanKonversi[] = array(
        //                 'ssid' =>   $item2->satuanstandar_tujuan,
        //                 'satuanstandar' =>   $item2->satuanstandar2,
        //                 'nilaikonversi' =>   $item2->nilaikonversi,
        //             );
        //         }
        //     }

        //     $dataProdukResult[] = array(
        //         'id' =>   $item->id,
        //         'namaproduk' =>   $item->namaproduk,
        //         'ssid' =>   $item->ssid,
        //         'satuanstandar' =>   $item->satuanstandar,
        //         'konversisatuan' => $satuanKonversi,
        //     );
        // }

        // $jenisKirim[] = array(
        //     'id' =>   1,
        //     'jenis' =>   'Amprahan',
        // );
        $jenisKirim[] = array(
            'id' =>   2,
            'jenis' =>   'Amprahan',
        );
        // $res['produk'] = $dataProdukResult;
        $res['jenis'] = $jenisKirim;
        $res['tarifadminresep'] = $this->settingFix('tarifadminresep');

        return $this->respond($res);
    }

    public function checkStok(Request $request)
    {

        foreach ($request['details'] as $item) {
            $qtyProduk = StokProdukDetail::where('objectprodukfk', $item['produkfk'])->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->sum('qtyproduk');
            if ($qtyProduk == 0) {
                $result = [
                    'qty' => $qtyProduk,
                    'message' => 'Gagal',
                    'respon' => 'Jumlah Stok Produk ' . $item['namaproduk'] . ' Tidak Tersedia',
                    'status' => 400,
                ];
                return $this->respond($result,$result['status'],$result['message']);
            }
            if ((int)$item['qtyprodukkonfirmasi'] > (int)$qtyProduk){
                $result = [
                    'qty' => $qtyProduk,
                    'message' => 'Gagal',
                    'respon' => 'Jumlah Stok Produk ' . $item['namaproduk'] . ' Lebih dari yang  Tersedia',
                    'status' => 400,
                ];
                return $this->respond($result,$result['status'],$result['message']);

            }
        }
        
        $result = ['qty' => $qtyProduk,'status' => 200, 'message' => 'ada'];

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getInformasiStok(Request $request)
    {
        $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int) $kdProfile;
        $results = DB::select(
            DB::raw("select sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                            spd.harganetto2,spd.hargadiscount,ru.namaruangan,
                   CAST(sum(spd.qtyproduk) AS FLOAT) as qtyproduk,spd.objectruanganfk as kdruangan
                    from stokprodukdetail_t as spd
                    inner JOIN ruangan_m as ru on ru.id=spd.objectruanganfk
                    inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                    where spd.kdprofile = $idProfile and spd.objectprodukfk =:produkId
                    and ru.statusenabled = true
                    -- and ru.statusenabled = true
                    --and spd.objectruanganfk =:ruanganid
                    group by sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                            spd.harganetto2,spd.hargadiscount,ru.namaruangan,
                    spd.objectruanganfk
                    order By sk.tglstruk"),
            array(
                'produkId' => $request['produkfk'],
                //                'ruanganid' => $request['ruanganfk'],
            )
        );
        $jmlstok = 0;
        foreach ($results as $item) {
            $jmlstok = $jmlstok + $item->qtyproduk;
        }
        $a = [];
        foreach ($results as $nenden) {
            $i = 0;
            $sama = false;
            foreach ($a as $hideung) {
                if ($nenden->kdruangan == $a[$i]['kdruangan']) {
                    $sama = true;
                    $a[$i]['qtyproduk'] = $a[$i]['qtyproduk'] + $nenden->qtyproduk;
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                $a[] = array(
                    'qtyproduk' => $nenden->qtyproduk,
                    'kdruangan' => $nenden->kdruangan,
                    'namaruangan' => $nenden->namaruangan,
                );
            }
        }

        $result = array(
            'jmlstok' => $jmlstok,
            'infostok' => $a,
            'detail' => $results,
            'message' => 'inhuman@epic',
        );
        return $this->respond($result);
    }

    // public function saveKirimOrderBarang(Request $request){

    //     DB::beginTransaction();
    //     try {
    //          $noKirim = $request['strukkirim']['jenispermintaanfk'] == 2 ?
    //             $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'TRF-' . $this->getDateTime()->format('ym'), $this->kdProfile) :
    //             $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $this->kdProfile);

    //         $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
    //             ->where('statusenabled', true)
    //             ->where('id', $request['strukkirim']['objectruanganfk'])
    //             ->first();

    //         $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
    //             ->where('statusenabled', true)
    //             ->where('id', $request['strukkirim']['objectruangantujuanfk'])
    //             ->first();

    //         $nameRuAsal = $ruanganAsal->namaruangan;
    //         $nameRuTujuan = $ruanganTujuan->namaruangan;

    //         if ($request['strukkirim']['norecStrukKirim'] == '') {
    //             if ($request['strukkirim']['norecOrder'] != '') {
    //                 StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 1]);
    //             }

    //             $dataSK = new StrukKirim;
    //             $dataSK->norec = $dataSK->generateNewId();
    //             $dataSK->nokirim = $noKirim;
    //             $dataSK->kdprofile = $this->kdProfile;
    //             $dataSK->statusenabled = true;
    //             $dataSK->objectpegawaipengirimfk = $this->getUserId();
    //             $dataSK->objectruanganasalfk = $request['strukkirim']['objectruanganfk'];
    //             $dataSK->objectruanganfk = $request['strukkirim']['objectruanganfk'];
    //             $dataSK->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
    //             $dataSK->jenispermintaanfk = $request['strukkirim']['jenispermintaanfk'];
    //             $dataSK->objectkelompoktransaksifk = $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
    //             $dataSK->keteranganlainnyakirim = $request['strukkirim']['keteranganlainnyakirim'];
    //             $dataSK->qtydetailjenisproduk = 0;
    //             $dataSK->qtyproduk = $request['strukkirim']['qtyproduk'];
    //             $dataSK->tglkirim = date($request['strukkirim']['tglkirim']);
    //             $dataSK->totalbeamaterai = 0;
    //             $dataSK->totalbiayakirim = 0;
    //             $dataSK->totalbiayatambahan = 0;
    //             $dataSK->totaldiscount = 0;
    //             $dataSK->totalhargasatuan = $request['strukkirim']['totalhargasatuan'];
    //             $dataSK->totalharusdibayar = 0;
    //             $dataSK->totalpph = 0;
    //             $dataSK->totalppn = 0;
    //             $dataSK->statuskirim = 1;
    //             $dataSK->noorderfk = $request['strukkirim']['norecOrder'];
    //             $dataSK->save();

    //             $norecSK = $dataSK->norec;
    //         } else {
    //             $dataSK = StrukKirim::where('noorderfk', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->first();
    //             $dataSO = StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();

    //             $norecSK = $dataSK->norec;

    //             $detailProdukKirim = KirimProduk::where('nokirimfk', $norecSK)->where('statusenabled', true)->get();

    //             if(isset($request['strukkirim']['iseditkirim'])){
    //                 foreach ($request['details'] as $item) {
    //                     $saldoAwalPengerim = StokProdukDetail::where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                         ->where('kdprofile', $this->kdProfile)
    //                         ->where('statusenabled', true)
    //                         ->where('objectprodukfk', $item['produkfk'])
    //                         ->sum('qtyproduk');

    //                     $saldoAkhirPengirim = (float)$saldoAwalPengerim + (float)$item['qtysebelumnya'];
    //                     $tglnow =  date('Y-m-d H:i:s');
    //                     $tglUbah = date('Y-m-d H:i:s', strtotime('-1 seconds', strtotime($tglnow)));

    //                     $this->kartu_STOK(array(
    //                         "saldoawal" => (float)$saldoAwalPengerim,
    //                         "qtyin" => $item['qtysebelumnya'],
    //                         "qtyout" => 0,
    //                         "saldoakhir" => (float) $saldoAkhirPengirim,
    //                         "keterangan" => 'Ubah Kirim Barang Order, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . '. Berupa Produk ' . $item['namaproduk'] . ' Dengan No Kirim : ' . $dataSK->nokirim,
    //                         "produkfk" => $item['produkfk'],
    //                         "ruanganfk" => $request['strukkirim']['objectruanganfk'],
    //                         "tglinput" => $tglUbah,
    //                         "tglkejadian" => $tglUbah,
    //                         "nostrukterimafk" => $request['strukkirim']['noreckirim'],
    //                         "norectransaksi" => null,
    //                         "tabletransaksi" => 'strukkirim_t',
    //                         // "stokprodukdetailfk" =>  $item['norec_spd'],
    //                         "flagfk" => null,
    //                     ));

    //                     foreach ($detailProdukKirim as $items) {

    //                         StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('norec', $items->stokprodukdetailfk)
    //                             ->where('statusenabled', true)
    //                             ->lockForUpdate()
    //                             ->increment('qtyproduk', (float)$items->qtyprodukkonfirmasi);
    //                     }
    //                 }
    //                 KirimProduk::where('nokirimfk', $norecSK)->where('statusenabled', true)->where('kdprofile',$this->kdProfile)->update(['statusenabled'=> false]);
    //             }else{
    //                 StrukKirim::where('noorderfk', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statuskirim' => 2]);
    //                 StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 3]);
    //             }
    //         }

    //         $dataSO = StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();

    //         foreach ($request['details'] as $item) {
             
    //             $saldoAwalPengirim = StokProdukDetail::where('objectprodukfk', $item['produkfk'])->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                                 ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)
    //                                 ->sum('qtyproduk');    

    //             $saldoAwalPenerima = StokProdukDetail::where('objectprodukfk', $item['produkfk'])->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                                 ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)
    //                                 ->sum('qtyproduk');        
                
    //             $jumlah = (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'];
    //             $saldoAkhirPengirim = (float)$saldoAwalPengirim - $jumlah;
    //             $saldoAkhirPenerima = (float)$saldoAwalPenerima + $jumlah;

    //             if($request['strukkirim']['prihal'] == 'kirimproduk'){

    //                 $dataProdukDetail = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                     ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                     ->where('objectprodukfk', $item['produkfk'])
    //                     ->where('statusenabled', true)
    //                     ->where('qtyproduk', '>', 0)
    //                     ->where('statusenabled', true)
    //                     ->orderBy('tglkadaluarsa', 'ASC')
    //                     ->get(); 

    //                 foreach ($dataProdukDetail as $items) {
    //                     if ((float)$items->qtyproduk <= $jumlah) {
    //                         $dataKP = new KirimProduk;
    //                         $dataKP->norec = $dataKP->generateNewId();
    //                         $dataKP->kdprofile = $this->kdProfile;
    //                         $dataKP->statusenabled = true;
    //                         $dataKP->objectasalprodukfk = $items['objectasalprodukfk'];
    //                         $dataKP->hargadiscount = $items['hargadiscount'];
    //                         $dataKP->harganetto = $items['hargasatuan'] ? $items['hargasatuan'] : $items['harganetto1'];
    //                         $dataKP->hargapph = 0;
    //                         $dataKP->hargappn = 0;
    //                         $dataKP->hargasatuan = $items['hargasatuan'] ? $items['hargasatuan'] : $items['harganetto1'];
    //                         $dataKP->hargatambahan = 0;
    //                         $dataKP->hasilkonversi = $items['hasilkonversi'];
    //                         $dataKP->objectprodukfk = $items['objectprodukfk'];
    //                         $dataKP->objectprodukkirimfk = $items['objectprodukfk'];
    //                         $dataKP->nokirimfk = $norecSK;
    //                         $dataKP->persendiscount = 0;
    //                         $dataKP->qtyproduk = (float)$items['qty'];
    //                         $dataKP->qtyprodukkonfirmasi = (float)$items['qtyproduk'];
    //                         $dataKP->qtyprodukretur = 0;
    //                         $dataKP->qtyorder = (float)$item['qtyorder'];
    //                         $dataKP->qtyprodukterima = (float)$items['qtyproduk'];
    //                         $dataKP->nostrukterimafk = $items['nostrukterimafk'];
    //                         $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                         $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                         $dataKP->satuan = '-';
    //                         $dataKP->objectsatuanstandarfk = $items['objectsatuanstandarfk'];
    //                         $dataKP->satuanviewfk = $items['objectsatuanstandarfk'];
    //                         $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                         $dataKP->qtyprodukterimakonversi = (float)$items['qtyproduk'];
    //                         $dataKP->stokprodukdetailfk = $items['norec'];
    //                         $dataKP->save();
                            
    //                         StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('norec', $items->norec)
    //                             ->where('statusenabled', true)
    //                             ->lockForUpdate()
    //                             ->decrement('qtyproduk', (float)$items->qtyproduk);

    //                         $jumlah = $jumlah - (float)$items->qtyproduk;
    //                     } else {
    //                         $dataKP = new KirimProduk;
    //                         $dataKP->norec = $dataKP->generateNewId();
    //                         $dataKP->kdprofile = $this->kdProfile;
    //                         $dataKP->statusenabled = true;
    //                         $dataKP->objectasalprodukfk = $items['objectasalprodukfk'];
    //                         $dataKP->hargadiscount = $items['hargadiscount'];
    //                         $dataKP->harganetto = $items['hargasatuan'] ? $items['hargasatuan'] : $items['harganetto1'];
    //                         $dataKP->hargapph = 0;
    //                         $dataKP->hargappn = 0;
    //                         $dataKP->hargasatuan = $items['hargasatuan'] ? $items['hargasatuan'] : $items['harganetto1'];
    //                         $dataKP->hargatambahan = 0;
    //                         $dataKP->hasilkonversi = $items['hasilkonversi'];
    //                         $dataKP->objectprodukfk = $items['objectprodukfk'];
    //                         $dataKP->objectprodukkirimfk = $items['objectprodukfk'];
    //                         $dataKP->nokirimfk = $norecSK;
    //                         $dataKP->persendiscount = 0;
    //                         $dataKP->qtyproduk = $jumlah;
    //                         $dataKP->qtyprodukkonfirmasi = (float)$jumlah;
    //                         $dataKP->qtyprodukretur = 0;
    //                         $dataKP->qtyorder = (float)$item['qtyorder'];
    //                         $dataKP->qtyprodukterima = (float)$jumlah;
    //                         $dataKP->nostrukterimafk = $items['nostrukterimafk'];
    //                         $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                         $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                         $dataKP->satuan = '-';
    //                         $dataKP->objectsatuanstandarfk = $items['objectsatuanstandarfk'];
    //                         $dataKP->satuanviewfk = $items['objectsatuanstandarfk'];
    //                         $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                         $dataKP->qtyprodukterimakonversi = (float)$jumlah;
    //                         $dataKP->stokprodukdetailfk = $items['norec'];
    //                         $dataKP->save();

    //                         StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('norec', $items->norec)
    //                             ->where('statusenabled', true)
    //                             ->lockForUpdate()
    //                             ->decrement('qtyproduk', (float)$jumlah);

    //                         $jumlah = 0;
    //                     }
    //                 }

    //                 $ketNomer = $dataSO ?  'Dengan No Order : ' . $dataSO->noorder  : 'Dengan No Kirim :' . $norecSK;

    //                $this->kartu_STOK(array(
    //                    "saldoawal" => (float)$saldoAwalPengirim,
    //                    "qtyin" => 0,
    //                    "qtyout" => ((float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi']),
    //                    "saldoakhir" => (float) $saldoAkhirPengirim,
    //                    "keterangan" => 'Kirim Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . '. Berupa Produk ' . $item['namaproduk'] .  $ketNomer ,
    //                    "produkfk" => $item['produkfk'],
    //                    "ruanganfk" => $request['strukkirim']['objectruanganfk'],
    //                    "tglinput" => date('Y-m-d H:i:s'),
    //                    "tglkejadian" => date('Y-m-d H:i:s'),
    //                    "nostrukterimafk" => null,
    //                    "norectransaksi" => null,
    //                    "tabletransaksi" => 'strukkirim_t',
    //                    // "stokprodukdetailfk" =>  $item['norec_spd'],
    //                    "flagfk" => null,
    //                ));

    //             }else{
    //                 if($request['strukkirim']['jenispermintaanfk'] == 2){
    //                     $detailProdukTerima = KirimProduk::where('nokirimfk', $norecSK)->where('objectprodukfk', $item['produkfk'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();
    //                     foreach ($detailProdukTerima as $items) {
    //                         if ((float)$items->qtyprodukkonfirmasi <= $jumlah) {
    //                             $dataStok = StokProdukDetail::where('norec', $items['stokprodukdetailfk'])->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
    //                             $dataNewSPD = new StokProdukDetail;
    //                             $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                             $dataNewSPD->kdprofile = $this->kdProfile;
    //                             $dataNewSPD->statusenabled = true;
    //                             $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                             $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                             $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                             $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                             $dataNewSPD->persendiscount = 0;
    //                             $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                             $dataNewSPD->qtyproduk = (float)$items['qtyprodukkonfirmasi'];
    //                             $dataNewSPD->qtyprodukonhand = 0;
    //                             $dataNewSPD->qtyprodukoutext = 0;
    //                             $dataNewSPD->qtyprodukoutint = 0;
    //                             $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                             $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
    //                             $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                             $dataNewSPD->nobatch = $dataStok->nobatch;
    //                             $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                             $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                             $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                             $dataNewSPD->save();
    //                             KirimProduk::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $dataStok->norec)
    //                                 ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
    //                             $data = KirimProduk::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('stokprodukdetailfk', $dataStok->norec)->where('nokirimfk', $norecSK)->get();
    //                             $test[] = $data;
    //                             $jumlah = $jumlah - (float)$items->qtyprodukkonfirmasi;
    //                         } else {

    //                             $dataStok = StokProdukDetail::where('norec', $items['stokprodukdetailfk'])->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();

    //                             $dataNewSPD = new StokProdukDetail;
    //                             $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                             $dataNewSPD->kdprofile = $this->kdProfile;
    //                             $dataNewSPD->statusenabled = true;
    //                             $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                             $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                             $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                             $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                             $dataNewSPD->persendiscount = 0;
    //                             $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                             $dataNewSPD->qtyproduk = (float)$jumlah;
    //                             $dataNewSPD->qtyprodukonhand = 0;
    //                             $dataNewSPD->qtyprodukoutext = 0;
    //                             $dataNewSPD->qtyprodukoutint = 0;
    //                             $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                             $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
    //                             $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                             $dataNewSPD->nobatch = $dataStok->nobatch;
    //                             $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                             $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                             $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                             $dataNewSPD->save();
    //                             KirimProduk::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('stokprodukdetailfk', $dataStok->norec)->where('nokirimfk', $norecSK)
    //                                 ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);

    //                             $jumlah = 0;
    //                         }
    //                     }

    //                     $this->kartu_STOK(array(
    //                         "saldoawal" => (float)$saldoAwalPenerima,
    //                         "qtyin" => ((float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi']),
    //                         "qtyout" => 0,
    //                         "saldoakhir" => (float) $saldoAkhirPenerima,
    //                         "keterangan" => 'Kirim Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . '. Berupa Produk ' . $item['namaproduk'] . '. Dengan No Kirim : ' . $dataSK->nokirim,
    //                         "produkfk" => $item['produkfk'],
    //                         "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
    //                         "tglinput" => date('Y-m-d H:i:s'),
    //                         "tglkejadian" => date('Y-m-d H:i:s'),
    //                         "nostrukterimafk" => null,
    //                         "norectransaksi" => $norecSK,
    //                         "tabletransaksi" => 'strukkirim_t',
    //                         // "stokprodukdetailfk" =>  $item['norec_spd'],
    //                         "flagfk" => null,
    //                     ));
    //                 }
    //             }      
    //         }

    //         DB::commit();

    //         $result = [
    //             'status' => 201,
    //             'message' => 'Data Berhasil Disimpan',
    //         ];

           
    //     }catch(Exception $e){
    //         DB::rollBack();
    //         $result = [
    //             'data' => $e->getMessage(),
    //             'status' => 400,
    //             'message' => 'Data Gagal Disimpan',
    //         ];
    //     }

    //     return $this->respond($result,$result['status'],$result['message']);
    // }

    public function getPenarikanBarang(Request $request)
    {
        $idProfile = (int)$this->kdProfile;

        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.statusenabled',true)
            ->where('ru.statusenabled',true)
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->where('mlu.kdprofile', $idProfile)
            ->get();

        $strRuangan = [];
        
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }
        
        $data = DB::table('strukkirim_t as sp')
            ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->leftjoin('strukorder_t as so', 'sp.noorderfk', '=', 'so.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'so.statusorder',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.kdprofile', $idProfile)
            ->whereNotNull("kp.objectprodukfk")
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'so.statusorder',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
            )
            ->where('sp.statusenabled', true)
            ->where('sp.ispenarikan', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruangantujuanfk', $strRuangan)
            ->orderBy('sp.nokirim', 'desc');

        if (isset($request['dari']) && $request['dari'] != '') {
            $data =  $data->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data =  $data->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data =  $data->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data =  $data->where('sp.objectruanganfk', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data =  $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
   
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }

        $data = $data->get();

        //var_dump($data->toSql());

        // return $data;
        $detail = DB::table('kirimproduk_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukkirim_t as sp', 'sp.norec', '=', 'spd.nokirimfk')
            ->select(
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyproduk'),
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyprodukkirim')
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruangantujuanfk', $strRuangan);
            // ->where('sp.noregistrasifk', '=', 0);
        // ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $detail =  $detail->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $detail =  $detail->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $detail =  $detail->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }

        $detail = $detail->groupBy('pr.id', 'pr.namaproduk', 'spd.qtyprodukretur', 'spd.objectprodukfk', 'sp.nokirim', 'spd.nokirimfk', 'ss.satuanstandar');
        $detail = $detail->get();
        // return $detail;

        $results = array();
        foreach ($data as $item) {
            $item->details = [];
            $cariData = false;
            foreach ($detail as $item2) {
                if ($item->norec == $item2->nokirimfk) {
                    $item->details[] = $item2;
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        if ($item2->objectprodukfk == $request['produkfk']) {
                            $cariData = true;
                        }
                    }
                }
            }
            if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                if ($cariData) {
                    $results[] = array(
                        'status' => 'Kirim Barang',
                        'statuskirim' => $item->statuskirim,
                        'tglstruk' => $item->tglkirim,
                        'nostruk' => $item->nokirim,
                        'noorderfk' => $item->noorderfk,
                        'statusorder' => $item->statusorder,
                        'jenispermintaanfk' => $item->jenispermintaanfk,
                        'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                        'norec' => $item->norec,
                        'ruasalid' => $item->ruasalid,
                        'namaruanganasal' => $item->ruanganasal,
                        'rutujuanid' => $item->rutujuanid,
                        'namaruangantujuan' => $item->ruangantujuan,
                        'petugas' => $item->namalengkap,
                        'keterangan' => $item->keteranganlainnyakirim,
                        'jmlitem' => $item->jmlitem,
                        'details' => $item->details,
                        // 'qtyproduk' => $item2->qtyproduk,
                    );
                }
            } else {
                $results[] = array(
                    'status' => 'Kirim Barang',
                    'statuskirim' => $item->statuskirim,
                    'tglstruk' => $item->tglkirim,
                    'nostruk' => $item->nokirim,
                    'noorderfk' => $item->noorderfk,
                    'statusorder' => $item->statusorder,
                    'jenispermintaanfk' => $item->jenispermintaanfk,
                    'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                    'norec' => $item->norec,
                    'ruasalid' => $item->ruasalid,
                    'namaruanganasal' => $item->ruanganasal,
                    'rutujuanid' => $item->rutujuanid,
                    'namaruangantujuan' => $item->ruangantujuan,
                    'petugas' => $item->namalengkap,
                    'keterangan' => $item->keteranganlainnyakirim,
                    'jmlitem' => $item->jmlitem,
                    'details' => $item->details,
                    // 'qtyproduk' => $item2->qtyproduk,
                );
            }
        }

        $data2 = DB::table('strukkirim_t as sp')
            ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            // ->whereNot('sp.isfloor', true)
            ->whereNotNull("kp.objectprodukfk")
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
            )
            ->where('sp.statusenabled', true)
            ->where('sp.isfloor', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruangantujuanfk', $strRuangan)
            ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $data2 =  $data2->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data2 =  $data2->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data2 =  $data2->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data2 =  $data2->where('sp.objectruanganfk', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data2 =  $data2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
        //     $data2 =  $data2->where('sp.objectprodukfk', '=', $request['produkfk']);
        // }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data2 = $data2->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data2 = $data2->limit($request['limit']);
        }

        $data2 = $data2->get();

        $detail2 = DB::table('kirimproduk_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukkirim_t as sp', 'sp.norec', '=', 'spd.nokirimfk')
            ->select(
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyproduk'),
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyprodukkirim')
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruangantujuanfk', $strRuangan);
            // ->where('sp.noregistrasifk', '=', 0);
        // ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $detail2 =  $detail2->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $detail2 =  $detail2->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $detail2 =  $detail2->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail2 =  $detail2->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail2 =  $detail2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }

        $detail2 = $detail2->groupBy('pr.id', 'pr.namaproduk', 'spd.qtyprodukretur', 'spd.objectprodukfk', 'sp.nokirim', 'spd.nokirimfk', 'ss.satuanstandar');
        $detail2 = $detail2->get();
        // return $detail;

        // $results = array();

        //var_dump($data);
        foreach ($data2 as $item) {
            $item->details = [];
            $cariData = false;
            foreach ($detail2 as $item2) {
                if ($item->norec == $item2->nokirimfk) {
                    $item->details[] = $item2;
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        if ($item2->objectprodukfk == $request['produkfk']) {
                            $cariData = true;
                        }
                    }
                }
            }
            if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                if ($cariData) {
                    $results[] = array(
                        'status' => 'Terima Barang',
                        'statuskirim' => $item->statuskirim,
                        'tglstruk' => $item->tglkirim,
                        'nostruk' => $item->nokirim,
                        'noorderfk' => $item->noorderfk,
                        'jenispermintaanfk' => $item->jenispermintaanfk,
                        'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                        'norec' => $item->norec,
                        'ruasalid' => $item->ruasalid,
                        'namaruanganasal' => $item->ruanganasal,
                        'rutujuanid' => $item->rutujuanid,
                        'namaruangantujuan' => $item->ruangantujuan,
                        'petugas' => $item->namalengkap,
                        'keterangan' => $item->keteranganlainnyakirim,
                        'jmlitem' => $item->jmlitem,
                        'details' => $item->details,
                        // 'qtyproduk' => $item2->qtyproduk,
                    );
                }
            } else {
                $results[] = array(
                    'status' => 'Terima Barang',
                    'statuskirim' => $item->statuskirim,
                    'tglstruk' => $item->tglkirim,
                    'nostruk' => $item->nokirim,
                    'noorderfk' => $item->noorderfk,
                    'jenispermintaanfk' => $item->jenispermintaanfk,
                    'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                    'norec' => $item->norec,
                    'ruasalid' => $item->ruasalid,
                    'namaruanganasal' => $item->ruanganasal,
                    'rutujuanid' => $item->rutujuanid,
                    'namaruangantujuan' => $item->ruangantujuan,
                    'petugas' => $item->namalengkap,
                    'keterangan' => $item->keteranganlainnyakirim,
                    'jmlitem' => $item->jmlitem,
                    'details' => $item->details,
                    // 'qtyproduk' => $item2->qtyproduk,
                );
            }
        }

        $result = array(
            'daftar' => $results,
        );

        return $this->respond($result);
    }


    public function savePenarikanBarang(Request $request)
    {
        DB::beginTransaction();

        try {
            $noKirim = $request['strukkirim']['jenispermintaanfk'] == 2 ?
                $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'PBA-' . $this->getDateTime()->format('ym'), $this->kdProfile) :
                $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $this->kdProfile);

            $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $request['strukkirim']['objectruanganfk'])
                ->first();

            $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $request['strukkirim']['objectruangantujuanfk'])
                ->first();

            $nameRuAsal = $ruanganAsal->namaruangan;
            $nameRuTujuan = $ruanganTujuan->namaruangan;

            if ($request['strukkirim']['noreckirim'] == '') 
            {
                if ($request['strukkirim']['norecOrder'] != '') 
                {
                    StrukOrder::where('norec', $request['strukkirim']['norecOrder'])
                        ->where('kdprofile', $this->kdProfile)
                        ->update(['statusorder' => 1]);
                }

                $message = "Penarikan Barang Berhasil";

                $dataSK = new StrukKirim;
                $dataSK->norec = $dataSK->generateNewId();
                $dataSK->nokirim = $noKirim;

            } 
            else 
            {
                $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->first();
                if (isset($request['strukkirim']['prihal']) == 'verifikasi') 
                {
                    $message = "Verifikasi Penarikan Barang Berhasil";
                    StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 3]);
                }

                else
                {
                    $message = "Update Kirim Barang Berhasil";

                    $strukKirimOld = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                    
                    $detailProdukKirim = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])
                    ->where('qtyprodukkonfirmasi','>' , 0)
                    ->where('statusenabled', true)
                    ->get();

                    foreach ($request['details'] as $item) {
                            $saldoAwalPengerim = StokProdukDetail::where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                            ->where('kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('objectprodukfk', $item['produkfk'])
                            ->sum('qtyproduk');

                        $saldoAkhirPengirim = (float)$saldoAwalPengerim + (float)$item['qtysebelumnya'];
                        $tglnow =  date('Y-m-d H:i:s');
                        $tglUbah = date('Y-m-d H:i:s', strtotime('-1 seconds', strtotime($tglnow)));

                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengerim,
                            "qtyin" => $item['qtysebelumnya'],
                            "qtyout" => 0,
                            "saldoakhir" => (float) $saldoAkhirPengirim,
                            "keterangan" => 'Ubah Kirim Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . '. Berupa Produk ' . $item['namaproduk'] . ' Dengan No Kirim : ' . $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $request['strukkirim']['noreckirim'],
                            "norectransaksi" => null,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    }

                    if ($request['strukkirim']['objectruangantujuanfk'] == $strukKirimOld->objectruangantujuanfk) {
                       
                        foreach($detailProdukKirim as $items){
                            StokProdukDetail::where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->stokprodukdetailfk)
                                ->where('statusenabled', true)
                                ->lockForUpdate()
                                ->increment('qtyproduk', (float)$items->qtyprodukkonfirmasi);
                        }

                    }

                    KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('statusenabled', true)->where('kdprofile',$this->kdProfile)->update(['statusenabled' => false]);
                   
                }
            }

            $dataSK->kdprofile = $this->kdProfile;
            $dataSK->statusenabled = true;
            $dataSK->objectpegawaipengirimfk = $this->getPegawaiId();
            $dataSK->objectruanganasalfk = $request['strukkirim']['objectruanganfk'];
            $dataSK->objectruanganfk = $request['strukkirim']['objectruanganfk'];
            $dataSK->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
            $dataSK->jenispermintaanfk = $request['strukkirim']['jenispermintaanfk'];
            $dataSK->objectkelompoktransaksifk = $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
            $dataSK->keteranganlainnyakirim = $request['strukkirim']['keteranganlainnyakirim'];
            $dataSK->qtydetailjenisproduk = 0;
            $dataSK->qtyproduk = $request['strukkirim']['qtyproduk'];
            $dataSK->tglkirim = date($request['strukkirim']['tglkirim']);
            $dataSK->totalbeamaterai = 0;
            $dataSK->ispenarikan = true;
            $dataSK->totalbiayakirim = 0;
            $dataSK->totalbiayatambahan = 0;
            $dataSK->totaldiscount = 0;
            $dataSK->totalhargasatuan = $request['strukkirim']['totalhargasatuan'];
            $dataSK->totalharusdibayar = 0;
            $dataSK->totalpph = 0;
            $dataSK->totalppn = 0;
            $dataSK->noorderfk = $request['strukkirim']['norecOrder'] != '' ? $request['strukkirim']['norecOrder'] : null;
            // $dataSK->statuskirim = $request['strukkirim']['jenispermintaanfk'] == 1 ? 2 : $request['strukkirim']['statuskirim'];
            if ($request['strukkirim']['statuskirim'] == 1) 
            { //kirim
                $dataSK->statuskirim = 1;
            } else 
            { //=2 == verif
                $dataSK->statuskirim = 2;
            }
            $dataSK->save();

            $norecSK = $dataSK->norec;

            foreach ($request['details'] as $item) {

                $satuanstandar = Produk::select('objectsatuanstandarfk')
                                ->where('kdprofile', $this->kdProfile)
                                ->where('statusenabled', true)
                                ->where('id', $item['produkfk'])
                                ->first();
                
                $satuanstandarfk = $satuanstandar->objectsatuanstandarfk;

                ## Data Saldo Awal Pengirim
                $dataSaldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    ->where('qtyproduk', '>', 0)
                    ->where('statusenabled', true)
                    ->orderBy('tglkadaluarsa')
                    ->get();

                $dataKirimProduk = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('objectprodukfk', $item['produkfk'])
                                    ->where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                    ->where('qtyprodukkonfirmasi','>' , 0)
                                    ->where('kdprofile',$this->kdProfile)
                                    ->select('stokprodukdetailfk as norec','qtyproduk','qtyprodukkonfirmasi')
                                    ->get();    

                $jumlah = (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'];

                ## Jumlah Saldo Awal Pengirim
                $saldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('statusenabled', true)
                    ->where('objectprodukfk', $item['produkfk'])
                    ->sum('qtyproduk');

                ## Jumlah Saldo Penerima
                $saldoAwalPenerima = StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    ->where('statusenabled', true)
                    ->sum('qtyproduk');

                $detailProduk = count($dataKirimProduk) > 0 ?  $dataKirimProduk : $dataSaldoAwalPengirim;
                

                #### Jumlah Saldo Akhir Pengirim
                $saldoAkhirPengirim = (float)$saldoAwalPengirim - $jumlah;

                ############## Pengondisian Berdasarkan Jenis Permintaan ##############
                ##### Ket : (1. Amprahan) (2. Transfer)

                if ($request['strukkirim']['norecOrder']) {
                    OrderPelayanan::where('strukorderfk', $request['strukkirim']['norecOrder'])->where('objectprodukfk', $item['produkfk'])
                    ->update(
                        ['qtyprodukkonfirmasi' => $item['qtyprodukkonfirmasi']]);
                }

                if ($request['strukkirim']['jenispermintaanfk'] == 1) { //amprah
                    if ($request['strukkirim']['statuskirim'] == 1) {
                        foreach ($detailProduk as $items) {
                            if ((float)$items->qtyproduk <= $jumlah) {
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $items->nilaikonversi;
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = (float)$items->qtyorder;
                                $dataKP->qtyprodukterima = (float)$items->qtyproduk;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $items->satuanviewfk;
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                $dataKP->save();

                                $jumlah = $jumlah - (float)$items->qtyproduk;
                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$items->qtyproduk);
                            } else {
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $items->nilaikonversi;
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = $jumlah;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $jumlah;
                                $dataKP->qtyprodukterima = $jumlah;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $items->satuanviewfk;
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = $jumlah;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                $dataKP->save();

                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', $jumlah);

                                $jumlah = 0;
                            }
                        }
                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengirim,
                            "qtyin" => 0,
                            "qtyout" => (float)$item['jumlah'],
                            "saldoakhir" => $saldoAkhirPengirim,
                            "keterangan" => 'Kirim Amprahan ' . $item['namaproduk'] . ', dari Ruangan' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => null,
                            "norectransaksi" => $norecSK,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd']
                        ));
                    }
                }

                if ($request['strukkirim']['jenispermintaanfk'] == 2) 
                {
                    //PENGIRIM
                    foreach ($detailProduk as $items) 
                    {
                        if ((float)$items->qtyproduk < $jumlah) 
                        {

                            if ($request['strukkirim']['statuskirim'] == 1) { //kirim
                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$items->qtyproduk);

                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $item['nilaikonversi'];
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = (float)$item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $item['qtyorder'];
                                $dataKP->qtyprodukterima = (float)$items->qtyproduk;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk;
                                $dataKP->satuanviewfk = $item['satuanviewfk'];
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                                $dataKP->save();   
                                 
                                   
                            }

                            else
                            {
                                $dataStok = StokProdukDetail::where('norec', $items->norec)->where('statusenabled', true)->first();

                                // foreach ($request['details'] as  $value) {  
                                    $dataNewSPD = new StokProdukDetail;
                                    $dataNewSPD->norec = $dataNewSPD->generateNewId();
                                    $dataNewSPD->kdprofile = $this->kdProfile;
                                    $dataNewSPD->statusenabled = true;
                                    $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                                    $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                                    $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                                    $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                                    $dataNewSPD->persendiscount = 0;
                                    $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                                    // $dataNewSPD->qtyproduk = (float)$items->qtyproduk;
                                    $dataNewSPD->qtyproduk = (float)$items->qtyprodukkonfirmasi;
                                    $dataNewSPD->qtyprodukonhand = 0;
                                    $dataNewSPD->qtyprodukoutext = 0;
                                    $dataNewSPD->qtyprodukoutint = 0;
                                    $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                    $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                                    $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                                    $dataNewSPD->nobatch = $dataStok->nobatch;
                                    $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                                    $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                                    $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                                    $dataNewSPD->save();
                                // }

                                KirimProduk::where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $items->norec)
                                    ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
                            }
                            $jumlah = $jumlah - (float)$items->qtyproduk;

                        } 
                        
                        else 
                        {
                            if ($request['strukkirim']['statuskirim'] == 1) 
                            { //kirim
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $item['nilaikonversi'];
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = $jumlah;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $item['qtyorder'];
                                $dataKP->qtyprodukterima = $jumlah;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $item['satuanviewfk'];
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = $jumlah;
                                $dataKP->save();

                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', $jumlah);
                            }
                            
                            else
                            {
                                $dataStok = StokProdukDetail::where('norec', $items->norec)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
                                $dataNewSPD = new StokProdukDetail;
                                $dataNewSPD->norec = $dataNewSPD->generateNewId();
                                $dataNewSPD->kdprofile = $this->kdProfile;
                                $dataNewSPD->statusenabled = true;
                                $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                                $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                                $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                                $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                                $dataNewSPD->persendiscount = 0;
                                $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                                $dataNewSPD->qtyproduk = ((float)$jumlah);
                                $dataNewSPD->qtyprodukonhand = 0;
                                $dataNewSPD->qtyprodukoutext = 0;
                                $dataNewSPD->qtyprodukoutint = 0;
                                $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                                $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                                $dataNewSPD->nobatch = $dataStok->nobatch;
                                $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                                $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                                $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                                $dataNewSPD->save();

                                KirimProduk::where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $items->norec)
                                    ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
                            }
                            $jumlah = 0;

                        }
                    }

                    if ($request['strukkirim']['statuskirim'] == 1) 
                    {
                        # Dari Sisi Pengirim
                        $pengirim = $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengirim,
                            "qtyin" => 0,
                            "qtyout" => (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'],
                            "saldoakhir" => $saldoAkhirPengirim,
                            "keterangan" => 'Penarikan Barang ' . $item['namaproduk'] . ', dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => null,
                            "norectransaksi" => $norecSK,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    } 
                    else 
                    {
                        $penerima = $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPenerima,
                            "qtyin" => (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'],
                            "qtyout" => 0,
                            "saldoakhir" => (float)$saldoAwalPenerima + (float)$item['qtyprodukkonfirmasi'],
                            "keterangan" => 'Terima Barang Penarikan ' . $item['namaproduk'] . ', dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" =>  null,
                            "norectransaksi" => $request['strukkirim']['noreckirim'],
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" => $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    }
                }

            }

            DB::commit();
            $result = [
                'status' => 201,
                'message' => $message,
                'data' => [
                    'Struk_Kirim' => $dataSK,
                    'datasaldoawal' => $dataSaldoAwalPengirim,
                    'datakirimproduk' =>$dataKirimProduk,
                    'detailproduk' =>$detailProduk,
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'data' => $e->getMessage() . $e->getLine(),
                'status' => 400,
                'message' => 'Simpan Data Gagal',
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
    

    public function saveKirimBarangRuangan(Request $request)
    {
        DB::beginTransaction();

        try {
            $noKirim = $request['strukkirim']['jenispermintaanfk'] == 2 ?
                $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'TRF-' . $this->getDateTime()->format('ym'), $this->kdProfile) :
                $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $this->kdProfile);

            $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $request['strukkirim']['objectruanganfk'])
                ->first();

            $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $request['strukkirim']['objectruangantujuanfk'])
                ->first();

            $nameRuAsal = $ruanganAsal->namaruangan;
            $nameRuTujuan = $ruanganTujuan->namaruangan;

            if ($request['strukkirim']['noreckirim'] == '') 
            {
                if ($request['strukkirim']['norecOrder'] != '') 
                {
                    StrukOrder::where('norec', $request['strukkirim']['norecOrder'])
                        ->where('kdprofile', $this->kdProfile)
                        ->update(['statusorder' => 1]);
                }

                $message = "Kirim Barang Berhasil";

                $dataSK = new StrukKirim;
                $dataSK->norec = $dataSK->generateNewId();
                $dataSK->nokirim = $noKirim;

            } 
            else 
            {
                $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->first();
                if (isset($request['strukkirim']['prihal']) == 'verifikasi') 
                {
                    $message = "Verifikasi Barang Berhasil";
                    StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 3]);
                }

                else
                {
                    $message = "Update Kirim Barang Berhasil";

                    $strukKirimOld = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                    
                    $detailProdukKirim = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])
                    ->where('qtyprodukkonfirmasi','>' , 0)
                    ->where('statusenabled', true)
                    ->get();

                    foreach ($request['details'] as $item) {
                            $saldoAwalPengerim = StokProdukDetail::where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                            ->where('kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('objectprodukfk', $item['produkfk'])
                            ->sum('qtyproduk');

                        $saldoAkhirPengirim = (float)$saldoAwalPengerim + (float)$item['qtysebelumnya'];
                        $tglnow =  date('Y-m-d H:i:s');
                        $tglUbah = date('Y-m-d H:i:s', strtotime('-1 seconds', strtotime($tglnow)));

                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengerim,
                            "qtyin" => $item['qtysebelumnya'],
                            "qtyout" => 0,
                            "saldoakhir" => (float) $saldoAkhirPengirim,
                            "keterangan" => 'Ubah Kirim Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . '. Berupa Produk ' . $item['namaproduk'] . ' Dengan No Kirim : ' . $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $request['strukkirim']['noreckirim'],
                            "norectransaksi" => null,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    }

                    if ($request['strukkirim']['objectruangantujuanfk'] == $strukKirimOld->objectruangantujuanfk) {
                       
                        foreach($detailProdukKirim as $items){
                            StokProdukDetail::where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->stokprodukdetailfk)
                                ->where('statusenabled', true)
                                ->lockForUpdate()
                                ->increment('qtyproduk', (float)$items->qtyprodukkonfirmasi);
                        }

                    }

                    KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('statusenabled', true)->where('kdprofile',$this->kdProfile)->update(['statusenabled' => false]);
                   
                }
            }

            $dataSK->kdprofile = $this->kdProfile;
            $dataSK->statusenabled = true;
            $dataSK->objectpegawaipengirimfk = $this->getPegawaiId();
            $dataSK->objectruanganasalfk = $request['strukkirim']['objectruanganfk'];
            $dataSK->objectruanganfk = $request['strukkirim']['objectruanganfk'];
            $dataSK->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
            $dataSK->jenispermintaanfk = $request['strukkirim']['jenispermintaanfk'];
            $dataSK->objectkelompoktransaksifk = $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
            $dataSK->keteranganlainnyakirim = $request['strukkirim']['keteranganlainnyakirim'];
            $dataSK->qtydetailjenisproduk = 0;
            $dataSK->qtyproduk = $request['strukkirim']['qtyproduk'];
            $dataSK->tglkirim = date($request['strukkirim']['tglkirim']);
            $dataSK->totalbeamaterai = 0;
            $dataSK->totalbiayakirim = 0;
            $dataSK->totalbiayatambahan = 0;
            $dataSK->totaldiscount = 0;
            $dataSK->totalhargasatuan = $request['strukkirim']['totalhargasatuan'];
            $dataSK->totalharusdibayar = 0;
            $dataSK->totalpph = 0;
            $dataSK->totalppn = 0;
            $dataSK->noorderfk = $request['strukkirim']['norecOrder'] != '' ? $request['strukkirim']['norecOrder'] : null;
            // $dataSK->statuskirim = $request['strukkirim']['jenispermintaanfk'] == 1 ? 2 : $request['strukkirim']['statuskirim'];
            if ($request['strukkirim']['statuskirim'] == 1) 
            { //kirim
                $dataSK->statuskirim = 1;
            } else 
            { //=2 == verif
                $dataSK->statuskirim = 2;
            }
            $dataSK->save();

            $norecSK = $dataSK->norec;

            foreach ($request['details'] as $item) {

                $satuanstandar = Produk::select('objectsatuanstandarfk')
                                ->where('kdprofile', $this->kdProfile)
                                ->where('statusenabled', true)
                                ->where('id', $item['produkfk'])
                                ->first();
                
                $satuanstandarfk = $satuanstandar->objectsatuanstandarfk;

                ## Data Saldo Awal Pengirim
                $dataSaldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    // ->where('qtyproduk', '>', 0)
                    ->where('statusenabled', true)
                    ->orderBy('tglkadaluarsa')
                    ->get();

                $dataKirimProduk = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('objectprodukfk', $item['produkfk'])
                                    ->where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                    // ->where('qtyprodukkonfirmasi','>' , 0)
                                    ->where('kdprofile',$this->kdProfile)
                                    ->select('stokprodukdetailfk as norec','qtyproduk','qtyprodukkonfirmasi')
                                    ->get();    

                $jumlah = (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'];

                ## Jumlah Saldo Awal Pengirim
                $saldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('statusenabled', true)
                    ->where('objectprodukfk', $item['produkfk'])
                    ->sum('qtyproduk');

                ## Jumlah Saldo Penerima
                $saldoAwalPenerima = StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    ->where('statusenabled', true)
                    ->sum('qtyproduk');

                $detailProduk = count($dataKirimProduk) > 0 ?  $dataKirimProduk : $dataSaldoAwalPengirim;
                

                #### Jumlah Saldo Akhir Pengirim
                $saldoAkhirPengirim = (float)$saldoAwalPengirim - $jumlah;

                ############## Pengondisian Berdasarkan Jenis Permintaan ##############
                ##### Ket : (1. Amprahan) (2. Transfer)

                if ($request['strukkirim']['norecOrder']) {
                    OrderPelayanan::where('strukorderfk', $request['strukkirim']['norecOrder'])->where('objectprodukfk', $item['produkfk'])
                    ->update(
                        ['qtyprodukkonfirmasi' => $item['qtyprodukkonfirmasi']]);
                }

                if ($request['strukkirim']['jenispermintaanfk'] == 1) { //amprah
                    if ($request['strukkirim']['statuskirim'] == 1) {
                        foreach ($detailProduk as $items) {
                            if ((float)$items->qtyproduk <= $jumlah) {
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $items->nilaikonversi;
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = (float)$items->qtyorder;
                                $dataKP->qtyprodukterima = (float)$items->qtyproduk;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $items->satuanviewfk;
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                $dataKP->save();

                                $jumlah = $jumlah - (float)$items->qtyproduk;
                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$items->qtyproduk);
                            } else {
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $items->nilaikonversi;
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = $jumlah;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $jumlah;
                                $dataKP->qtyprodukterima = $jumlah;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $items->satuanviewfk;
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = $jumlah;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                $dataKP->save();

                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', $jumlah);

                                $jumlah = 0;
                            }
                        }
                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengirim,
                            "qtyin" => 0,
                            "qtyout" => (float)$item['jumlah'],
                            "saldoakhir" => $saldoAkhirPengirim,
                            "keterangan" => 'Kirim Amprahan ' . $item['namaproduk'] . ', dari Ruangan' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => null,
                            "norectransaksi" => $norecSK,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd']
                        ));
                    }
                }

                if ($request['strukkirim']['jenispermintaanfk'] == 2) 
                {
                    //PENGIRIM
                    foreach ($detailProduk as $items) 
                    {
                        if ((float)$items->qtyproduk < $jumlah) 
                        {

                            if ($request['strukkirim']['statuskirim'] == 1) { //kirim
                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$items->qtyproduk);

                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $item['nilaikonversi'];
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = (float)$item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $item['qtyorder'];
                                $dataKP->qtyprodukterima = (float)$items->qtyproduk;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk;
                                $dataKP->satuanviewfk = $item['satuanviewfk'];
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                                $dataKP->save();   
                                 
                                   
                            }

                            else
                            {
                                $dataStok = StokProdukDetail::where('norec', $items->norec)->where('statusenabled', true)->first();

                                // foreach ($request['details'] as  $value) {  
                                    $dataNewSPD = new StokProdukDetail;
                                    $dataNewSPD->norec = $dataNewSPD->generateNewId();
                                    $dataNewSPD->kdprofile = $this->kdProfile;
                                    $dataNewSPD->statusenabled = true;
                                    $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                                    $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                                    $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                                    $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                                    $dataNewSPD->persendiscount = 0;
                                    $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                                    // $dataNewSPD->qtyproduk = (float)$items->qtyproduk;
                                    $dataNewSPD->qtyproduk = (float)$items->qtyprodukkonfirmasi;
                                    $dataNewSPD->qtyprodukonhand = 0;
                                    $dataNewSPD->qtyprodukoutext = 0;
                                    $dataNewSPD->qtyprodukoutint = 0;
                                    $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                    $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                                    $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                                    $dataNewSPD->nobatch = $dataStok->nobatch;
                                    $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                                    $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                                    $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                                    $dataNewSPD->save();
                                // }

                                KirimProduk::where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $items->norec)
                                    ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
                            }
                            $jumlah = $jumlah - (float)$items->qtyproduk;

                        } 
                        
                        else 
                        {
                            if ($request['strukkirim']['statuskirim'] == 1) 
                            { //kirim
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $item['nilaikonversi'];
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = $jumlah;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $item['qtyorder'];
                                $dataKP->qtyprodukterima = $jumlah;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $item['satuanviewfk'];
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = $jumlah;
                                $dataKP->save();

                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', $jumlah);
                            }
                            
                            else
                            {
                                $dataStok = StokProdukDetail::where('norec', $items->norec)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
                                $dataNewSPD = new StokProdukDetail;
                                $dataNewSPD->norec = $dataNewSPD->generateNewId();
                                $dataNewSPD->kdprofile = $this->kdProfile;
                                $dataNewSPD->statusenabled = true;
                                $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                                $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                                $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                                $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                                $dataNewSPD->persendiscount = 0;
                                $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                                // $dataNewSPD->qtyproduk = ((float)$jumlah);
                                $dataNewSPD->qtyproduk = ((float)$items->qtyprodukkonfirmasi);
                                $dataNewSPD->qtyprodukonhand = 0;
                                $dataNewSPD->qtyprodukoutext = 0;
                                $dataNewSPD->qtyprodukoutint = 0;
                                $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                                $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                                $dataNewSPD->nobatch = $dataStok->nobatch;
                                $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                                $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                                $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                                $dataNewSPD->save();

                                KirimProduk::where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $items->norec)
                                    ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
                            }
                            $jumlah = 0;

                        }
                    }

                    if ($request['strukkirim']['statuskirim'] == 1) 
                    {
                        # Dari Sisi Pengirim
                        $pengirim = $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengirim,
                            "qtyin" => 0,
                            "qtyout" => (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'],
                            "saldoakhir" => $saldoAkhirPengirim,
                            "keterangan" => 'Kirim Barang ' . $item['namaproduk'] . ', dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => null,
                            "norectransaksi" => $norecSK,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    } 
                    else 
                    {
                        $penerima = $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPenerima,
                            "qtyin" => (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'],
                            "qtyout" => 0,
                            "saldoakhir" => (float)$saldoAwalPenerima + (float)$item['qtyprodukkonfirmasi'],
                            "keterangan" => 'Terima Barang ' . $item['namaproduk'] . ', dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" =>  null,
                            "norectransaksi" => $request['strukkirim']['noreckirim'],
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" => $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    }
                }

            }

            DB::commit();
            $result = [
                'status' => 201,
                'message' => $message,
                'data' => [
                    'Struk_Kirim' => $dataSK,
                    'datasaldoawal' => $dataSaldoAwalPengirim,
                    'datakirimproduk' =>$dataKirimProduk,
                    'detailproduk' =>$detailProduk,
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'data' => $e->getMessage() . $e->getLine(),
                'status' => 400,
                'message' => 'Simpan Data Gagal',
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function batalVerifDanKirimBarang(Request $request)
    {

        DB::beginTransaction();
        try {
            $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->first();
            $ruanganPengirim = Ruangan::where('id', $request['strukkirim']['ruangantujuanfk'])->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
            $ruanganPenerima = Ruangan::where('id', $request['strukkirim']['obejectruanganasal'])->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();

            $test = [];

            foreach ($request['details'] as $index => $order) {

                if($order['qtyprodukkirim'] > 0){
                    $getDetails = DB::table('kirimproduk_t as kp')
                        ->join('produk_m as pr', 'pr.id', 'kp.objectprodukfk')
                        ->select('kp.*', 'pr.namaproduk')
                        ->where('kp.kdprofile', $this->kdProfile)
                        ->where('kp.qtyprodukkonfirmasi', '>', 0)
                        ->where('kp.statusenabled', true)
                        ->where('objectprodukfk', $order['objectprodukfk'])
                        ->where('nokirimfk', $request['strukkirim']['noreckirim'])
                        ->get();

                    if ($request['strukkirim']['info'] == 'batal kirim') {

                        $ketBatal = 'Batal Kirim Barang Berhasil';

                        $saldoAwalPengirim = StokProdukDetail::where('objectprodukfk', $order['objectprodukfk'])
                            ->where('objectruanganfk', $request['strukkirim']['ruangantujuanfk'])
                            ->where('kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->sum('qtyproduk');

                        $saldoAkhir = $saldoAwalPengirim + (float)$order['qtyproduk'];

                        OrderPelayanan::where('strukorderfk', $request['strukkirim']['norecorder'])->where('kdprofile', $this->kdProfile)->update(['qtyprodukkonfirmasi' => null]);

                        foreach ($getDetails as $items) {

                            StokProdukDetail::where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->stokprodukdetailfk)
                                ->where('statusenabled', true)
                                ->lockForUpdate()
                                ->increment('qtyproduk', (float)$items->qtyprodukkonfirmasi);
                        }

                        $notransaksi = isset($request['strukkirim']['nostruk']) ?  ', No Kirim : ' .  $request['strukkirim']['nostruk'] : ', No Order : ' .  $request['strukkirim']['noorder'];

                        // SISI PENGIRIM;
                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengirim,
                            "qtyin" => (float)$items->qtyprodukkonfirmasi,
                            "qtyout" => 0,
                            "saldoakhir" => (float)$saldoAwalPengirim + (float)$items->qtyprodukkonfirmasi ,
                            "keterangan" => 'Batal Kirim Barang ' . $order['namaproduk'] . ', dari Ruangan ' . $ruanganPengirim['namaruangan'] . ' ke Ruangan ' . $ruanganPenerima['namaruangan'] . $notransaksi,
                            "produkfk" => $order['objectprodukfk'],
                            "ruanganfk" => $request['strukkirim']['ruangantujuanfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => null,
                            "norectransaksi" => $request['strukkirim']['noreckirim'],
                            "tabletransaksi" => 'strukkirim_t',
                            "stokprodukdetailfk" =>  null
                        ));

                        KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->delete();
                        StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->delete();
                        StrukOrder::where('norec', $request['strukkirim']['norecorder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 0]);

                        $this->LOGGING(
                            'Batal Kirim Barang Amprahan',
                            $request['strukkirim']['noreckirim'],
                            'strukkirim_t',
                            'Batal Kirim Barang dari Ruangan ' . $ruanganPengirim['namaruangan']
                                . ' ke Ruangan ' . $ruanganPenerima['namaruangan'] . ' Jumlah '
                                . $request['strukkirim']['jmlitem']
                                . ' (' . $notransaksi . ')'
                        );
                    } 

                    if ($request['strukkirim']['info'] == 'Batal FS')
                    {
                        $ketBatal = 'Batal Penggunaan';
                        $saldoAwalPenerima = StokProdukDetail::where('objectprodukfk', $order['objectprodukfk'])
                            ->where('objectruanganfk', $request['strukkirim']['ruangantujuanfk'])
                            ->where('kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->sum('qtyproduk');   
                        
                        $saldoAkhirPenerima = $saldoAwalPenerima + (float)$order['qtyproduk'];
                        
                        foreach ($getDetails as $items) {
                            $infoStokPenerima = StokProdukDetail::where('norec', $items->stokprodukdetailfk)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
                            StokProdukDetail::where('kdprofile', $this->kdProfile)
                                ->where('norec', $infoStokPenerima->norec)
                                ->where('statusenabled', true)
                                ->lockForUpdate()
                                ->increment('qtyproduk', (float)$items->qtyprodukkonfirmasi);
                        }

                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPenerima,
                            "qtyin" => (float)$order['qtyproduk'],
                            "qtyout" => 0,
                            "saldoakhir" => $saldoAkhirPenerima,//(float)$saldoAwalPengirim + (float)$items->qtyprodukkonfirmasi ,
                            "keterangan" => 'Batal Penggunaan Barang Barang ' . $order['namaproduk'] . ', dari Ruangan ' . $ruanganPengirim['namaruangan'],
                            "produkfk" => $order['objectprodukfk'],
                            "ruanganfk" => $request['strukkirim']['ruangantujuanfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => null,
                            "norectransaksi" => $request['strukkirim']['noreckirim'],
                            "tabletransaksi" => 'strukkirim_t',
                            "stokprodukdetailfk" =>  null
                        ));
                        $this->LOGGING(
                            'Batal Penggunaan Berhasil',
                            $request['strukkirim']['noreckirim'],
                            'strukkirim_t',
                            'Batal Penggunaan dari Ruangan ' . $ruanganPengirim['namaruangan']  
                        );

                        // Riwayatnya terhapus ketika foreachnya habis
                        if (count($request['details']) == $index + 1) {
                            KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->delete();
                            StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->delete();
                        }
                    }

                    else 
                    {

                        $ketBatal = 'Batal Verif Amprahan';
                        if ($request['strukkirim']['jenispermintaanfk'] == 2) {
                            $saldoAwalPenerima = StokProdukDetail::where('objectprodukfk', $order['objectprodukfk'])
                                ->where('objectruanganfk', $request['strukkirim']['obejectruanganasal'])
                                ->where('kdprofile', $this->kdProfile)
                                ->where('statusenabled', true)
                                ->sum('qtyproduk');

                            $saldoAkhirPenerima = $saldoAwalPenerima - (float)$order['qtyproduk'];
                            $ketBatal = 'Batal Verifikasi Terima Barang';

                            foreach ($getDetails as $items) {


                                $infoStokPenerima = StokProdukDetail::where('norec', $items->stokprodukdetailterimafk)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();

                                if ($infoStokPenerima->qtyproduk != $items->qtyprodukkonfirmasi) {
                                    DB::rollBack();

                                    $result = [
                                        'message' => 'Batal Distribusi Gagal, Barang Sudah Terpakai !',
                                        'status' => 400,
                                    ];

                                    return $this->respond($result, $result['status'], $result['message']);
                                }

                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $infoStokPenerima->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$items->qtyprodukkonfirmasi);
                            }
                            $notransaksi = isset($request['strukkirim']['nostruk']) ?  ', No Kirim : ' .  $request['strukkirim']['nostruk'] : ', No Order : ' .  $request['strukkirim']['noorder'];

                            // SISI PENERIMA;
                            $this->kartu_STOK(array(
                                "saldoawal" => (float)$saldoAwalPenerima,
                                "qtyin" => 0,
                                "qtyout" => (float)$items->qtyprodukkonfirmasi,
                                "saldoakhir" => (float)$saldoAwalPenerima - (float)$items->qtyprodukkonfirmasi,
                                "keterangan" => 'Batal Verifikasi Terima Barang ' . $order['namaproduk'] . ', dari Ruangan ' . $ruanganPengirim['namaruangan'] . ' ke Ruangan ' . $ruanganPenerima['namaruangan'] . $notransaksi,
                                "produkfk" => $order['objectprodukfk'],
                                "ruanganfk" => $request['strukkirim']['obejectruanganasal'],
                                "tglinput" => date('Y-m-d H:i:s'),
                                "tglkejadian" => date('Y-m-d H:i:s'),
                                "nostrukterimafk" => null,
                                "norectransaksi" => $request['strukkirim']['noreckirim'],
                                "tabletransaksi" => 'strukkirim_t',
                                "stokprodukdetailfk" =>  null
                            ));
                        }

                        StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->update(['statuskirim' => 1]);
                        StrukOrder::where('norec', $request['strukkirim']['norecorder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 1]);

                        $notransaksi = isset($request['strukkirim']['nostruk']) ?  ', No Kirim : ' .  $request['strukkirim']['nostruk'] : ', No Order : ' .  $request['strukkirim']['noorder'];
                        $this->LOGGING(
                            $ketBatal,
                            $request['strukkirim']['noreckirim'],
                            'strukkirim_t',
                            $ketBatal . $ruanganPengirim['namaruangan']
                                . ' ke Ruangan ' . $ruanganPenerima['namaruangan'] . ' Jumlah '
                                . $request['strukkirim']['jmlitem']
                                . ' (' . $notransaksi . ')'
                        );
                    }
                }
            }
            DB::commit();

            $result = [
                'data' => $dataSK,
                'status' => 200,
                'message' =>  $ketBatal,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'data' => $e->getMessage(),
                'message' => "Gagal Batal",
                'status' => 400,
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }


    // public function saveKirimBarangRuangan(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $noKirim = $request['strukkirim']['jenispermintaanfk'] == 2 ?
    //             $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'TRF-' . $this->getDateTime()->format('ym'), $this->kdProfile) :
    //             $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $this->kdProfile);

    //         $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
    //             ->where('statusenabled', true)
    //             ->where('id', $request['strukkirim']['objectruanganfk'])
    //             ->first();

    //         $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
    //             ->where('statusenabled', true)
    //             ->where('id', $request['strukkirim']['objectruangantujuanfk'])
    //             ->first();

    //         $nameRuAsal = $ruanganAsal->namaruangan;
    //         $nameRuTujuan = $ruanganTujuan->namaruangan;


    //         if ($request['strukkirim']['noreckirim'] == '') {
    //             // if ($request['strukkirim']['norecOrder'] != '') {
    //             //     StrukOrder::where('norec', $request['strukkirim']['norecOrder'])
    //             //         ->where('kdprofile', $this->kdProfile)
    //             //         ->update(['statusorder' => 1]);
    //             // }

    //             $message = "Pendistribusian Barang Berhasil";

    //             $dataSK = new StrukKirim;
    //             $dataSK->norec = $dataSK->generateNewId();
    //             $dataSK->nokirim = $noKirim;

    //         } else {
    //             $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->first();
    //             if ($request['strukkirim']['prihal'] == 'verifikasi') {
    //                 $message = "Verifikasi Barang Berhasil";
    //                 StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 3]);
    //             }else{
    //                 $message = "Update Distribusi Barang Berhasil";

    //                 $norec = $request['strukkirim']['noreckirim'];
    //                 $ruanganStrukKirimSebelumnya = DB::select(
    //                     DB::raw("
    //                      select  ru.id, ru.namaruangan
    //                      from ruangan_m as ru
    //                      where ru.kdprofile = $this->kdProfile and ru.id=(select objectruangantujuanfk from strukkirim_t where norec = :norec)"),
    //                     array(
    //                         'norec' => $norec,
    //                     )
    //                 );

    //                 $strNmRuanganStrukKirimSebelumnya = $ruanganStrukKirimSebelumnya[0]->namaruangan;
    //                 $strIdRuanganStrukKirimSebelumnya = $ruanganStrukKirimSebelumnya[0]->id;

    //                 //#1
    //                 $strukKirimOld = StrukKirim::where('norec', $norec)->where('kdprofile', $this->kdProfile)->first();
    //                 // KartuStok::where('keterangan',  'Kirim Amprahan, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim)
    //                 //     ->update([
    //                 //         'flagfk' => null
    //                 //     ]);

    //                 if ($request['strukkirim']['objectruanganfk'] == $strukKirimOld->objectruanganfk) {

    //                     $getDetails = DB::table('kirimproduk_t as kp')
    //                         ->join('produk_m as pro', 'pro.id', 'kp.objectprodukfk')
    //                         ->select('kp.*', 'pro.namaproduk')
    //                         ->where('kp.kdprofile', $this->kdProfile)
    //                         ->where('kp.qtyproduk', '>', 0)
    //                         ->where('nokirimfk', $norec)
    //                         ->get();
    //                     $test = [];
    //                     foreach ($getDetails as $item) {
    //                         //PENGIRIM
    //                         $noterimaS = $item->nostrukterimafk;

    //                         $ruangKirim = $request['strukkirim']['objectruanganfk'];
    //                         $saldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                             ->where('nostrukterimafk', $noterimaS)
    //                             ->where('statusenabled', true)
    //                             ->where('objectprodukfk', $item->objectprodukfk)
    //                             ->sum('qtyproduk');

    //                         $saldoAkhirPengirim = (float)$saldoAwalPengirim + (float)$item->qtyproduk;

    //                         $tambah = StokProdukDetail::where('nostrukterimafk', $noterimaS)
    //                             ->where('kdprofile', $this->kdProfile)
    //                             ->where('statusenabled', true)
    //                             ->where('objectruanganfk', $strukKirimOld->objectruanganfk)
    //                             ->where('objectprodukfk', $item->objectprodukfk)
    //                             ->first();

    //                         StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('norec', $tambah->norec)
    //                             ->where('statusenabled', true)
    //                             ->where('objectruanganfk', $ruangKirim)
    //                             ->where('objectprodukfk', $item->objectprodukfk)
    //                             ->lockForUpdate()
    //                             ->increment('qtyproduk', (float)$item->qtyproduk);

    //                         $this->kartu_STOK(array(
    //                             "saldoawal" => (float)$saldoAwalPengirim,
    //                             "qtyin" => (float)$item->qtyproduk,
    //                             "qtyout" => 0,
    //                             "saldoakhir" => (float) $saldoAkhirPengirim,
    //                             "keterangan" => 'Ubah Kirim Barang ' . $item->namaproduk . ', dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim,
    //                             "produkfk" => $item->objectprodukfk,
    //                             "ruanganfk" => $request['strukkirim']['objectruanganfk'],
    //                             "tglinput" => date('Y-m-d H:i:s'),
    //                             "tglkejadian" => date('Y-m-d H:i:s'),
    //                             "nostrukterimafk" => $item->nostrukterimafk,
    //                             "norectransaksi" => $request['strukkirim']['noreckirim'],
    //                             "tabletransaksi" => 'strukkirim_t',
    //                             "stokprodukdetailfk" =>  $item->stokprodukdetailfk
    //                         ));

    //                         if ($request['strukkirim']['jenispermintaanfk'] == 2) {

    //                             $infoStokPenerima = StokProdukDetail::where('norec', $item->stokprodukdetailterimafk)->where('kdprofile', $this->kdProfile)
    //                                 ->where('statusenabled', true)->first();

    //                             if ($infoStokPenerima->qtyproduk != $item->qtyprodukkonfirmasi) {
    //                                 DB::rollBack();
    //                                 $result = [
    //                                     'message' => 'Edit Distribusi Gagal, Barang Sudah Terpakai !',
    //                                     'status' => 400,
    //                                 ];

    //                                 return $this->respond($result, $result['status'], $result['message']);
    //                             }

    //                             $saldoAwalPenerima = StokProdukDetail::where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
    //                                 ->where('objectprodukfk', $item->objectprodukfk)
    //                                 ->sum('qtyproduk');

    //                             // return $strIdRuanganStrukKirimSebelumnya;
    //                             $saldoAkhirPenerima =  (float)$saldoAwalPenerima - (float)$item->qtyprodukkonfirmasi;

    //                             // return $noterimaS;
    //                             if ($dataSK->jenispermintaanfk == 2) {
    //                                 $kurang = StokProdukDetail::where('nostrukterimafk', $noterimaS)
    //                                     ->where('kdprofile', $this->kdProfile)
    //                                     ->where('objectprodukfk', $item->objectprodukfk)
    //                                     ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
    //                                     ->first();
    //                                 StokProdukDetail::where('norec', $kurang->norec)->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                                     ->where('objectprodukfk', $item->objectprodukfk)
    //                                     ->lockForUpdate()
    //                                     ->decrement('qtyproduk',  (float)$item->qtyprodukkonfirmasi);

    //                                 //## KartuStok
    //                                 $this->kartu_STOK(array(
    //                                     "saldoawal" => (float)$saldoAwalPenerima,
    //                                     "qtyin" => 0,
    //                                     "qtyout" => (float)$item->qtyprodukkonfirmasi,
    //                                     "saldoakhir" => $saldoAkhirPenerima,
    //                                     "keterangan" => 'Ubah Terima Barang ' . $item->namaproduk . ', dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim,
    //                                     "produkfk" => $item->objectprodukfk,
    //                                     "ruanganfk" => $strIdRuanganStrukKirimSebelumnya,
    //                                     "tglinput" => date('Y-m-d H:i:s'),
    //                                     "tglkejadian" => date('Y-m-d H:i:s'),
    //                                     "nostrukterimafk" => $item->nostrukterimafk,
    //                                     "norectransaksi" => $request['strukkirim']['noreckirim'],
    //                                     "tabletransaksi" => 'strukkirim_t',
    //                                     "stokprodukdetailfk" =>  $item->stokprodukdetailfk
    //                                 ));
    //                             }
    //                         } elseif ($strukKirimOld->jenispermintaanfk != $request['strukkirim']['jenispermintaanfk']) {

    //                             $saldoAwalPenerima = StokProdukDetail::where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
    //                                 ->where('objectprodukfk', $item->objectprodukfk)
    //                                 ->sum('qtyproduk');

    //                             $saldoAkhirPenerima =  (float)$saldoAwalPenerima - (float)$item->qtyprodukkonfirmasi;

    //                             $kurangin = StokProdukDetail::where('nostrukterimafk', $noterimaS)
    //                                 ->where('kdprofile', $this->kdProfile)
    //                                 ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                                 ->where('objectprodukfk', $item->objectprodukfk)
    //                                 ->first();

    //                             StokProdukDetail::where('norec', $kurangin->norec)
    //                                 ->where('kdprofile', $this->kdProfile)
    //                                 ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
    //                                 ->where('objectprodukfk', $item->objectprodukfk)
    //                                 ->lockForUpdate()
    //                                 ->decrement('qtyproduk',  (float)$item->qtyprodukkonfirmasi);
    //                             $tglnow1 =  date('Y-m-d H:i:s');
    //                             $tglUbah1 = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow1)));

    //                             //## KartuStok
    //                             $this->kartu_STOK(array(
    //                                 "saldoawal" => (float)$saldoAwalPenerima,
    //                                 "qtyin" => 0,
    //                                 "qtyout" => (float)$item->qtyprodukkonfirmasi,
    //                                 "saldoakhir" => $saldoAkhirPenerima,
    //                                 "keterangan" => 'Ubah Terima Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim,
    //                                 "produkfk" => $item['objectprodukfk'],
    //                                 "ruanganfk" => $strIdRuanganStrukKirimSebelumnya,
    //                                 "tglinput" => date('Y-m-d H:i:s'),
    //                                 "tglkejadian" => date('Y-m-d H:i:s'),
    //                                 "nostrukterimafk" => $item['nostrukterimafk'],
    //                                 "norectransaksi" => $request['strukkirim']['noreckirim'],
    //                                 "tabletransaksi" => 'strukkirim_t',
    //                                 "stokprodukdetailfk" =>  $item['stokprodukdetailfk']
    //                             ));
    //                         }
    //                         KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->delete();
    //                     }
    //                 } else {
    //                     $ruanganAsal = Ruangan::where('id', $strukKirimOld->objectruanganfk)->where('kdprofile', $this->kdProfile)->first();
    //                     $ruanganTujuan = Ruangan::where('id', $strukKirimOld->objectruangantujuanfk)->where('kdprofile', $this->kdProfile)->first();
    //                     $getDetails = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])
    //                         ->where('kdprofile', $this->kdProfile)
    //                         ->where('qtyproduk', '>', 0)
    //                         ->get();

    //                     foreach ($getDetails as $item) {
    //                         //PENGIRIM
    //                         $saldoAwalPengirim = 0;
    //                         $noterimaS = $item['nostrukterimafk'];

    //                         $dataSaldoAwalK = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('objectruanganfk', $strukKirimOld->objectruanganfk)
    //                             ->where('objectprodukfk', $item->objectprodukfk)
    //                             ->where('statusenabled', true)
    //                             ->sum('qtyproduk');

    //                         $saldoAkhirPengirim = (float)$dataSaldoAwalK + (float)$item->qtyprodukkonfirmasi;
    //                         $tambah = StokProdukDetail::where('nostrukterimafk', $noterimaS)
    //                             ->where('kdprofile', $this->kdProfile)
    //                             ->where('statusenabled', true)
    //                             ->where('objectruanganfk', $strukKirimOld->objectruanganfk)
    //                             ->where('objectprodukfk', $item->objectprodukfk)
    //                             ->first();

    //                         StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('norec', $tambah->norec)
    //                             ->where('statusenabled', true)
    //                             ->where('objectruanganfk', $strukKirimOld->objectruanganfk)
    //                             ->where('objectprodukfk', $item->objectprodukfk)
    //                             ->lockForUpdate()
    //                             ->increment('qtyproduk', (float)$item->qtyprodukkonfirmasi);
    //                         //## KartuStok
    //                         $this->kartu_STOK(array(
    //                             "saldoawal" => (float)$saldoAwalPengirim,
    //                             "qtyin" => (float)$item->qtyprodukkonfirmasi,
    //                             "qtyout" => 0,
    //                             "saldoakhir" => (float)$saldoAkhirPengirim,
    //                             "keterangan" => 'Ubah Kirim Barang, dari Ruangan ' . $ruanganAsal->namaruangan  . ' ke Ruangan ' . $ruanganTujuan->namaruangan . ' No Kirim: ' .  $dataSK->nokirim,
    //                             "produkfk" => $item['produkfk'],
    //                             "ruanganfk" => $request['strukkirim']['objectruanganfk'],
    //                             "tglinput" => date('Y-m-d H:i:s'),
    //                             "tglkejadian" => date('Y-m-d H:i:s'),
    //                             "nostrukterimafk" => $item['nostrukterimafk'],
    //                             "norectransaksi" => $request['strukkirim']['noreckirim'],
    //                             "tabletransaksi" => 'strukkirim_t',
    //                             "stokprodukdetailfk" =>  $item['stokprodukdetailfk']
    //                         ));

    //                         if ($request['strukkirim']['jenispermintaanfk'] == 2) {
    //                             //PENERIMA
    //                             $infoStokPenerima = StokProdukDetail::where('norec', $item->stokprodukdetailterimafk)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
    //                             if ($infoStokPenerima->qtyproduk != $item->qtyprodukkonfirmasi) {
    //                                 DB::rollBack();
    //                                 $result = [
    //                                     'message' => 'Edit Distribusi Gagal, Barang Sudah Terpakai !',
    //                                     'status' => 400,
    //                                 ];

    //                                 return $this->respond($result, $result['status'], $result['message']);
    //                             }

    //                             $saldoAwalPenerima = 0;
    //                             $dataSaldoAwalT = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                                 ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
    //                                 ->where('objectprodukfk', $item->objectprodukfk)
    //                                 ->where('statusenabled', true)
    //                                 ->sum('qtyproduk');
    //                             $saldoAkhirPenerima = (float)$dataSaldoAwalT - (float)$item->qtyprodukkonfirmasi;
    //                             if ($dataSK->jenispermintaanfk == 2) {
    //                                 $kurang = StokProdukDetail::where('nostrukterimafk', $noterimaS)
    //                                     ->where('kdprofile', $this->kdProfile)
    //                                     ->where('statusenabled', true)
    //                                     ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
    //                                     ->where('objectprodukfk', $item->objectprodukfk)
    //                                     ->first();

    //                                 StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                                     ->where('norec', $kurang->norec)
    //                                     ->where('statusenabled', true)
    //                                     ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
    //                                     ->where('objectprodukfk', $item->objectprodukfk)
    //                                     ->lockForUpdate()
    //                                     ->increment('qtyproduk', (float)$item->qtyprodukkonfirmasi);

    //                                 $tglnow1 =  date('Y-m-d H:i:s');
    //                                 $tglUbah1 = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow1)));

    //                                 //## KartuStok
    //                                 $this->kartu_STOK(array(
    //                                     "saldoawal" => (float)$dataSaldoAwalT,
    //                                     "qtyin" => 0,
    //                                     "qtyout" => (float)$item->qtyprodukkonfirmasi,
    //                                     "saldoakhir" => (float)$saldoAkhirPenerima,
    //                                     "keterangan" => 'Ubah Terima Barang, dari Ruangan ' . $ruanganAsal->namaruangan . ' ke Ruangan ' . $ruanganTujuan->namaruangan . ' No Kirim: ' .  $dataSK->nokirim,
    //                                     "produkfk" => $item['produkfk'],
    //                                     "ruanganfk" => $strukKirimOld->objectruangantujuanfk,
    //                                     "tglinput" => date('Y-m-d H:i:s'),
    //                                     "tglkejadian" => date('Y-m-d H:i:s'),
    //                                     "nostrukterimafk" => $item['nostrukterimafk'],
    //                                     "norectransaksi" => $request['strukkirim']['noreckirim'],
    //                                     "tabletransaksi" => 'strukkirim_t',
    //                                     "stokprodukdetailfk" =>  $item['stokprodukdetailfk']
    //                                 ));
    //                             }
    //                         }
    //                         KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->delete();
    //                     }
    //                 }                    
    //             }
    //         }

    //         $dataSK->kdprofile = $this->kdProfile;
    //         $dataSK->statusenabled = true;
    //         $dataSK->objectpegawaipengirimfk = $this->getUserId();
    //         $dataSK->objectruanganasalfk = $request['strukkirim']['objectruanganfk'];
    //         $dataSK->objectruanganfk = $request['strukkirim']['objectruanganfk'];
    //         $dataSK->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
    //         $dataSK->jenispermintaanfk = $request['strukkirim']['jenispermintaanfk'];
    //         $dataSK->objectkelompoktransaksifk = $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
    //         $dataSK->keteranganlainnyakirim = $request['strukkirim']['keteranganlainnyakirim'];
    //         $dataSK->qtydetailjenisproduk = 0;
    //         $dataSK->qtyproduk = $request['strukkirim']['qtyproduk'];
    //         $dataSK->tglkirim = date($request['strukkirim']['tglkirim']);
    //         $dataSK->totalbeamaterai = 0;
    //         $dataSK->totalbiayakirim = 0;
    //         $dataSK->totalbiayatambahan = 0;
    //         $dataSK->totaldiscount = 0;
    //         $dataSK->totalhargasatuan = $request['strukkirim']['totalhargasatuan'];
    //         $dataSK->totalharusdibayar = 0;
    //         $dataSK->totalpph = 0;
    //         $dataSK->totalppn = 0;
    //         $dataSK->noorderfk = $request['strukkirim']['norecOrder'];
    //         if ($request['strukkirim']['statuskirim'] == 1) { //kirim
    //             $dataSK->statuskirim = 1;
    //         } else { //=2 == verif
    //             $dataSK->statuskirim = 2;
    //         }
    //         $dataSK->save();

    //         $norecSK = $dataSK->norec;

    //         foreach ($request['details'] as $item) {

    //             $satuanstandar = Produk::select('objectsatuanstandarfk')
    //                             ->where('kdprofile', $this->kdProfile)
    //                             ->where('statusenabled', true)
    //                             ->where('id', $item['produkfk'])
    //                             ->first();
                
    //             $satuanstandarfk = $satuanstandar->objectsatuanstandarfk;

    //             ## Data Saldo Awal Pengirim
    //             $dataSaldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                 ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                 ->where('objectprodukfk', $item['produkfk'])
    //                 ->where('qtyproduk', '>', 0)
    //                 ->where('statusenabled', true)
    //                 ->orderBy('tglkadaluarsa')
    //                 ->get();

    //             $jumlah = (float)$item['jumlah'] * (float)$item['nilaikonversi'];

    //             ## Jumlah Saldo Awal Pengirim
    //             $saldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                 ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                 ->where('statusenabled', true)
    //                 ->where('objectprodukfk', $item['produkfk'])
    //                 ->sum('qtyproduk');

    //             ## Jumlah Saldo Penerima
    //             $saldoAwalPenerima = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                 ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                 ->where('objectprodukfk', $item['produkfk'])
    //                 ->where('statusenabled', true)
    //                 ->sum('qtyproduk');

    //             #### Jumlah Saldo Akhir Pengirim
    //             $saldoAkhirPengirim = (float)$saldoAwalPengirim - $jumlah;

    //             ############## Pengondisian Berdasarkan Jenis Permintaan ##############
    //             ##### Ket : (1. Amprahan) (2. Transfer)

    //             if ($request['strukkirim']['jenispermintaanfk'] == 1) { //amprah

    //                 foreach ($dataSaldoAwalPengirim as $items) {
    //                     if ((float)$items->qtyproduk <= $jumlah) {
    //                         $dataKP = new KirimProduk;
    //                         $dataKP->norec = $dataKP->generateNewId();
    //                         $dataKP->kdprofile = $this->kdProfile;
    //                         $dataKP->statusenabled = true;
    //                         $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
    //                         $dataKP->hargadiscount = $items->hargadiscount;
    //                         $dataKP->harganetto = $items->harganetto1;
    //                         $dataKP->hargapph = 0;
    //                         $dataKP->hargappn = 0;
    //                         $dataKP->hargasatuan = $items->harganetto1;
    //                         $dataKP->hargatambahan = 0;
    //                         $dataKP->hasilkonversi = $item['nilaikonversi'];
    //                         $dataKP->objectprodukfk = $item['produkfk'];
    //                         $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                         $dataKP->nokirimfk = $norecSK;
    //                         $dataKP->persendiscount = 0;
    //                         $dataKP->qtyproduk = (float)$items->qtyproduk;
    //                         $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
    //                         $dataKP->qtyprodukretur = 0;
    //                         $dataKP->qtyorder = (float)$item->qtyorder;
    //                         $dataKP->qtyprodukterima = (float)$items->qtyproduk;
    //                         $dataKP->nostrukterimafk = $items->nostrukterimafk;
    //                         $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                         $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
    //                         $dataKP->satuan = '-';
    //                         $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
    //                         $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                         $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                         $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
    //                         $dataKP->stokprodukdetailfk = $items->norec;
    //                         $dataKP->save();

    //                         $jumlah = $jumlah - (float)$items->qtyproduk;
    //                         StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('norec', $items->norec)
    //                             ->where('statusenabled', true)
    //                             ->lockForUpdate()
    //                             ->decrement('qtyproduk', (float)$items->qtyproduk);
    //                     } else {
    //                         $dataKP = new KirimProduk;
    //                         $dataKP->norec = $dataKP->generateNewId();
    //                         $dataKP->kdprofile = $this->kdProfile;
    //                         $dataKP->statusenabled = true;
    //                         $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
    //                         $dataKP->hargadiscount = $items->hargadiscount;
    //                         $dataKP->harganetto = $items->harganetto1;
    //                         $dataKP->hargapph = 0;
    //                         $dataKP->hargappn = 0;
    //                         $dataKP->hargasatuan = $items->harganetto1;
    //                         $dataKP->hargatambahan = 0;
    //                         $dataKP->hasilkonversi = $item['nilaikonversi'];
    //                         $dataKP->objectprodukfk = $item['produkfk'];
    //                         $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                         $dataKP->nokirimfk = $norecSK;
    //                         $dataKP->persendiscount = 0;
    //                         $dataKP->qtyproduk = (float)$items->qtyproduk;
    //                         $dataKP->qtyprodukkonfirmasi = $jumlah;
    //                         $dataKP->qtyprodukretur = 0;
    //                         $dataKP->qtyorder = (float)$item->qtyorder;
    //                         $dataKP->qtyprodukterima = $jumlah;
    //                         $dataKP->nostrukterimafk = $items->nostrukterimafk;
    //                         $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                         $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
    //                         $dataKP->satuan = '-';
    //                         $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
    //                         $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                         $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                         $dataKP->qtyprodukterimakonversi = $jumlah;
    //                         $dataKP->stokprodukdetailfk = $items->norec;
    //                         $dataKP->save();

    //                         StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                             ->where('norec', $items->norec)
    //                             ->where('statusenabled', true)
    //                             ->lockForUpdate()
    //                             ->decrement('qtyproduk', $jumlah);

    //                         $jumlah = 0;
    //                     }
    //                 }

    //                 $this->kartu_STOK(array(
    //                     "saldoawal" => (float)$saldoAwalPengirim,
    //                     "qtyin" => 0,
    //                     "qtyout" => (float)$item['jumlah'],
    //                     "saldoakhir" => $saldoAkhirPengirim,
    //                     "keterangan" => 'Kirim Amprahan ' . $item['namaproduk'] . ', dari Ruangan' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
    //                     "produkfk" => $item['produkfk'],
    //                     "ruanganfk" => $request['strukkirim']['objectruanganfk'],
    //                     "tglinput" => date('Y-m-d H:i:s'),
    //                     "tglkejadian" => date('Y-m-d H:i:s'),
    //                     "nostrukterimafk" => null,
    //                     "norectransaksi" => $norecSK,
    //                     "tabletransaksi" => 'strukkirim_t',
    //                     // "stokprodukdetailfk" =>  $item['norec_spd']
    //                 ));
    //             }

    //             if ($request['strukkirim']['jenispermintaanfk'] == 2) {
    //                 //PENGIRIM
    //                 foreach ($dataSaldoAwalPengirim as $items) {
    //                     if ((float)$items->qtyproduk <= $jumlah) {

    //                         if ($request['strukkirim']['statuskirim'] == 1) { //kirim
    //                             StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                                 ->where('norec', $items->norec)
    //                                 ->where('statusenabled', true)
    //                                 ->lockForUpdate()
    //                                 ->decrement('qtyproduk', (float)$items->qtyproduk);

    //                             $dataKP = new KirimProduk;
    //                             $dataKP->norec = $dataKP->generateNewId();
    //                             $dataKP->kdprofile = $this->kdProfile;
    //                             $dataKP->statusenabled = true;
    //                             $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
    //                             $dataKP->hargadiscount = $items->hargadiscount;
    //                             $dataKP->harganetto = $items->harganetto1;
    //                             $dataKP->hargapph = 0;
    //                             $dataKP->hargappn = 0;
    //                             $dataKP->hargasatuan = $items->harganetto1;
    //                             $dataKP->hargatambahan = 0;
    //                             $dataKP->hasilkonversi = $item['nilaikonversi'];
    //                             $dataKP->objectprodukfk = $item['produkfk'];
    //                             $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                             $dataKP->nokirimfk = $norecSK;
    //                             $dataKP->persendiscount = 0;
    //                             $dataKP->qtyproduk = (float)$items->qtyproduk;
    //                             $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
    //                             $dataKP->qtyprodukretur = 0;
    //                             $dataKP->qtyorder = $item['qtyorder'];
    //                             $dataKP->qtyprodukterima = (float)$items->qtyproduk;
    //                             $dataKP->nostrukterimafk = $items->nostrukterimafk;
    //                             $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                             $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                             $dataKP->satuan = '-';
    //                             $dataKP->objectsatuanstandarfk = $satuanstandarfk;
    //                             $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                             $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                             $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
    //                             $dataKP->stokprodukdetailfk = $items->norec;
    //                             // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
    //                             $dataKP->save();    
    //                         }else{
    //                             $dataStok = StokProdukDetail::where('norec', $items->norec)->where('statusenabled', true)->first();

    //                             $dataNewSPD = new StokProdukDetail;
    //                             $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                             $dataNewSPD->kdprofile = $this->kdProfile;
    //                             $dataNewSPD->statusenabled = true;
    //                             $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                             $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                             $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                             $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                             $dataNewSPD->persendiscount = 0;
    //                             $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                             $dataNewSPD->qtyproduk = (float)$items->qtyproduk;
    //                             $dataNewSPD->qtyprodukonhand = 0;
    //                             $dataNewSPD->qtyprodukoutext = 0;
    //                             $dataNewSPD->qtyprodukoutint = 0;
    //                             $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                             $dataNewSPD->nostrukterimafk = $items->nostrukterimafk;
    //                             $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                             $dataNewSPD->nobatch = $dataStok->nobatch;
    //                             $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                             $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                             $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                             $dataNewSPD->save();

    //                             KirimProduk::where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $items->norec)
    //                                 ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
    //                         }
    //                         $jumlah = $jumlah - (float)$items->qtyproduk;

    //                     } else {

    //                         if ($request['strukkirim']['statuskirim'] == 1) { //kirim
    //                             $dataKP = new KirimProduk;
    //                             $dataKP->norec = $dataKP->generateNewId();
    //                             $dataKP->kdprofile = $this->kdProfile;
    //                             $dataKP->statusenabled = true;
    //                             $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
    //                             $dataKP->hargadiscount = $items->hargadiscount;
    //                             $dataKP->harganetto = $items->harganetto1;
    //                             $dataKP->hargapph = 0;
    //                             $dataKP->hargappn = 0;
    //                             $dataKP->hargasatuan = $items->harganetto1;
    //                             $dataKP->hargatambahan = 0;
    //                             $dataKP->hasilkonversi = $item['nilaikonversi'];
    //                             $dataKP->objectprodukfk = $item['produkfk'];
    //                             $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                             $dataKP->nokirimfk = $norecSK;
    //                             $dataKP->persendiscount = 0;
    //                             $dataKP->qtyproduk = $jumlah;
    //                             $dataKP->qtyprodukkonfirmasi = $jumlah;
    //                             $dataKP->qtyprodukretur = 0;
    //                             $dataKP->qtyorder = $item['qtyorder'];
    //                             $dataKP->qtyprodukterima = $jumlah;
    //                             $dataKP->stokprodukdetailfk = $items->norec;
    //                             // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
    //                             $dataKP->nostrukterimafk = $items->nostrukterimafk;
    //                             $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                             $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                             $dataKP->satuan = '-';
    //                             $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
    //                             $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                             $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                             $dataKP->qtyprodukterimakonversi = $jumlah;
    //                             $dataKP->save();

    //                             StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                                 ->where('norec', $items->norec)
    //                                 ->where('statusenabled', true)
    //                                 ->lockForUpdate()
    //                                 ->decrement('qtyproduk', $jumlah);
    //                         }else{
    //                             $dataStok = StokProdukDetail::where('norec', $items->norec)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();

    //                             $dataNewSPD = new StokProdukDetail;
    //                             $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                             $dataNewSPD->kdprofile = $this->kdProfile;
    //                             $dataNewSPD->statusenabled = true;
    //                             $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                             $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                             $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                             $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                             $dataNewSPD->persendiscount = 0;
    //                             $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                             $dataNewSPD->qtyproduk = ((float)$jumlah);
    //                             $dataNewSPD->qtyprodukonhand = 0;
    //                             $dataNewSPD->qtyprodukoutext = 0;
    //                             $dataNewSPD->qtyprodukoutint = 0;
    //                             $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                             $dataNewSPD->nostrukterimafk = $items->nostrukterimafk;
    //                             $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                             $dataNewSPD->nobatch = $dataStok->nobatch;
    //                             $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                             $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                             $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                             $dataNewSPD->save();

    //                             KirimProduk::where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $items->norec)
    //                                 ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
    //                         }
    //                         $jumlah = 0;

    //                     }
    //                 }

    //                 if ($request['strukkirim']['statuskirim'] == 1) {
    //                     # Dari Sisi Pengirim
    //                     $pengirim = $this->kartu_STOK(array(
    //                         "saldoawal" => (float)$saldoAwalPengirim,
    //                         "qtyin" => 0,
    //                         "qtyout" => ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
    //                         "saldoakhir" => $saldoAkhirPengirim,
    //                         "keterangan" => 'Kirim Barang ' . $item['namaproduk'] . ', dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
    //                         "produkfk" => $item['produkfk'],
    //                         "ruanganfk" => $request['strukkirim']['objectruanganfk'],
    //                         "tglinput" => date('Y-m-d H:i:s'),
    //                         "tglkejadian" => date('Y-m-d H:i:s'),
    //                         "nostrukterimafk" => null,
    //                         "norectransaksi" => $norecSK,
    //                         "tabletransaksi" => 'strukkirim_t',
    //                         // "stokprodukdetailfk" =>  $item['norec_spd'],
    //                         "flagfk" => null,
    //                     ));
    //                 } else {
    //                     $penerima = $this->kartu_STOK(array(
    //                         "saldoawal" => (float)$saldoAwalPenerima,
    //                         "qtyin" => ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
    //                         "qtyout" => 0,
    //                         "saldoakhir" => (float)$saldoAwalPenerima + (float)$item['jumlah'],
    //                         "keterangan" => 'Terima Barang ' . $item['namaproduk'] . ', dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' No Kirim: ' .  $dataSK->nokirim,
    //                         "produkfk" => $item['produkfk'],
    //                         "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
    //                         "tglinput" => date('Y-m-d H:i:s'),
    //                         "tglkejadian" => date('Y-m-d H:i:s'),
    //                         "nostrukterimafk" =>  null,
    //                         "norectransaksi" => $request['strukkirim']['noreckirim'],
    //                         "tabletransaksi" => 'strukkirim_t',
    //                         // "stokprodukdetailfk" => $item['norec_spd'],
    //                         "flagfk" => null,
    //                     ));
    //                 }
    //             }

    //         }

    //         DB::commit();
    //         $result = [
    //             'status' => 201,
    //             'message' => $message,
    //             'data' => [
    //                 'Struk Kirim' => $dataSK,
    //             ]
    //         ];
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         $result = [
    //             'data' => $e->getMessage() . $e->getLine(),
    //             'status' => 400,
    //             'message' => 'Simpan Data Gagal',
    //         ];
    //     }

    //     return $this->respond($result, $result['status'], $result['message']);
    // }



    public function getDetailKirimBarang(Request $request)
    {

        $idProfile = $this->kdProfile;
        $dataAsalProduk = DB::table('asalproduk_m as ap')
            ->select('ap.id', 'ap.asalproduk')
            ->get();
        $dataStruk = DB::table('strukkirim_t as sr')
            ->JOIN('pegawai_m as pg', 'pg.id', '=', 'sr.objectpegawaipengirimfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.objectruanganfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.objectruangantujuanfk')
            ->select(
                'sr.norec',
                'sr.nokirim',
                'pg.id as pgid',
                'pg.namalengkap',
                'ru.id',
                'ru.namaruangan',
                'ru2.id as ruid2',
                'ru2.namaruangan as namaruangan2',
                'sr.jenispermintaanfk',
                'sr.tglkirim',
                'sr.keteranganlainnyakirim as keterangan'
            )
            ->where('sr.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sr.norec', '=', $request['norec']);
        }
        $dataStruk = $dataStruk->first();

        $data = DB::table('strukkirim_t as sp')
            ->JOIN('kirimproduk_t as spd', 'spd.nokirimfk', '=', 'sp.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->select(DB::raw("
             sp.nokirim,spd.hargasatuan,spd.qtyprodukoutext,sp.objectruanganfk,ru.namaruangan,spd.nostrukterimafk,
             spd.objectprodukfk AS produkfk,pr.kdproduk,pr.namaproduk,CAST(spd.hasilkonversi AS FLOAT) AS nilaikonversi,
             spd.objectsatuanstandarfk,ss.satuanstandar,spd.objectsatuanstandarfk AS satuanviewfk,ss.satuanstandar AS ssview,
             spd.qtyproduk AS jumlah,spd.hargadiscount,spd.hargatambahan AS jasa,spd.hargasatuan AS hargajual,spd.harganetto,
             spd.qtyprodukretur
        "))
            ->where('sp.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('sp.norec', '=', $request['norec']);
        }
        $data = $data->get();

        $pelayananPasien = [];
        $i = 0;
        $dataStok = DB::select(
            DB::raw("select sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                        spd.harganetto2 as hargajual,spd.harganetto2 as harganetto,spd.hargadiscount,
                sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                where spd.kdprofile = $idProfile and spd.objectruanganfk =:ruanganid
                group by sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                        spd.harganetto2,spd.hargadiscount,
                spd.objectruanganfk
                order By sk.tglstruk"),
            array(
                'ruanganid' => $dataStruk->id
            )
        );

        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $hargasatuan = 0;
        $hargadiscount = 0;
        $total = 0;
        $aturanpakaifk = 0;
        foreach ($data as $item) {
            if ($item->jumlah > 0) {
                $i = $i + 1;

                foreach ($dataStok as $item2) {
                    if ($item2->objectprodukfk == $item->produkfk) {
                        if ($item2->qtyproduk > ($item->jumlah * $item->nilaikonversi)) {
                            $hargajual = $item->hargajual;
                            $harganetto = $item->harganetto;
                            $nostrukterimafk = $item2->norec;
                            $asalprodukfk = $item2->objectasalprodukfk;
                            $jmlstok = $item2->qtyproduk;
                            $hargasatuan = $harganetto; //$item2->harganetto;
                            $hargadiscount = $item->hargadiscount;
                            $total = (((float)$item->jumlah * ((float)$hargasatuan - (float)$hargadiscount)));
                            break;
                        }
                    }
                }
                foreach ($dataAsalProduk as $item3) {
                    if ($asalprodukfk == $item3->id) {
                        $asalproduk = $item3->asalproduk;
                    }
                }
                $pelayananPasien[] = array(
                    'no' => $i,
                    'noregistrasifk' => '',
                    'tglregistrasi' => '',
                    'generik' => null,
                    'hargajual' => $hargajual,
                    'jenisobatfk' => '',
                    'kelasfk' => '',
                    'stock' => $jmlstok,
                    'harganetto' => $harganetto,
                    'nostrukterimafk' => $nostrukterimafk,
                    'ruanganfk' => $item->objectruanganfk,
                    'rke' => 0,
                    'jeniskemasanfk' => 0,
                    'jeniskemasan' => '',
                    'aturanpakaifk' => $aturanpakaifk,
                    'aturanpakai' => '',
                    'routefk' => 0,
                    'route' => '',
                    'asalprodukfk' => $asalprodukfk,
                    'asalproduk' => $asalproduk,
                    'produkfk' => $item->produkfk,
                    'kdproduk' => $item->kdproduk,
                    'namaproduk' => $item->namaproduk,
                    'nilaikonversi' => $item->nilaikonversi, ///$item->jumlah,
                    'satuanstandarfk' => $item->satuanviewfk, //objectsatuanstandarfk,
                    'satuanstandar' => $item->ssview, //satuanstandar,
                    'satuanviewfk' => $item->satuanviewfk,
                    'satuanview' => $item->ssview,
                    'jmlstok' => $jmlstok,
                    'jumlah' => $item->jumlah,
                    'qtyorder' => $item->jumlah,
                    'qtyretur' => $item->qtyprodukretur,
                    'dosis' => 1,
                    'hargasatuan' => $hargasatuan,
                    'hargadiscount' => $hargadiscount,
                    'total' => $total + $item->jasa,
                    'jmldosis' => (string)($item->jumlah / $item->nilaikonversi) / 1 . '/' . (string)1,
                    'jasa' => $item->jasa,
                );
            }
        }

        $result = array(
            'head' => $dataStruk,
            'detail' => $pelayananPasien,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function cetakBuktiTarik(Request $request)
    {
        $idProfile = $this->kdProfile;
        $judul = '';
        $namaPegawai = $this->getNamaPegawai();
        $dataAsalProduk = DB::table('asalproduk_m as ap')
            ->select('ap.id', 'ap.asalproduk')
            ->get();
        if ($request['jenis'] == 'distribusi') {
            $judul = 'Bukti Penarikan Barang';
            $dataStruk = DB::table('strukkirim_t as sr')
                ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'sr.noorderfk')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.objectpegawaipengirimfk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.objectruanganfk')
                ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.objectruangantujuanfk')
                ->leftJOIN('pegawai_m as pg2', 'pg2.id', '=', 'so.objectpegawaiorderfk')
                ->JOIN('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sr.norec')
                ->select(
                    'sr.norec',
                    'sr.isfloor',
                    'sr.nokirim',
                    'pg.id as pgid',
                    'pg.namalengkap',
                    'pg2.namalengkap as pegawaiorder',
                    'ru.id',
                    'kp.harganetto',
                    'ru.namaruangan',
                    'ru2.id as ruid2',
                    'ru2.namaruangan as namaruangan2',
                    'sr.jenispermintaanfk',
                    'sr.tglkirim',
                    'so.keterangan as keteranganminta',
                    'sr.keteranganlainnyakirim as keterangan','so.noorder'
                )
                ->where('sr.kdprofile', $idProfile);

            if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
                $dataStruk = $dataStruk->where('sr.nokirim', '=', $request['nokirim']);
            }
            $dataStruk = $dataStruk->first();

            $data = DB::table('strukkirim_t as sp')
                ->leftJOIN('kirimproduk_t as spd', 'spd.nokirimfk', '=', 'sp.norec')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
                ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
                ->select(DB::raw("
                 sp.nokirim,spd.hargasatuan,sp.objectruanganfk,ru.namaruangan,
                 spd.objectprodukfk AS produkfk,pr.kdproduk,pr.namaproduk,CAST(spd.hasilkonversi AS FLOAT) AS nilaikonversi,
                 spd.objectsatuanstandarfk,ss.satuanstandar,spd.objectsatuanstandarfk AS satuanviewfk,ss.satuanstandar AS ssview,
                 sum(spd.qtyprodukkonfirmasi) as jumlah,spd.hargadiscount,spd.hargatambahan AS jasa,spd.hargasatuan AS hargajual,spd.harganetto,
                 CAST(sum(spd.qtyprodukkonfirmasi) * ROUND(spd.hargasatuan) - ROUND(spd.hargadiscount) as float) as total,sum(spd.qtyorder) as qtyorder,spd.qtyprodukretur
            "))
                ->where('sp.kdprofile', $idProfile)
                ->where('sp.statusenabled', true)
                ->where('spd.statusenabled', true)
                ->groupBy(
                    'sp.nokirim',
                    'sp.objectruanganfk',
                    'ru.namaruangan',
                    'spd.objectprodukfk',
                    'pr.kdproduk',
                    'pr.namaproduk',
                    'spd.hasilkonversi',
                    'spd.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'spd.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'spd.hargadiscount',
                    'spd.hargatambahan',
                    'spd.hargasatuan',
                    'spd.harganetto',
                    'spd.qtyprodukretur'
                );
            if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
                $data = $data->where('sp.nokirim', '=', $request['nokirim']);
            }
            $data = $data->get();
        }

        if ($request['jenis'] == 'floorstok') {
            $judul = 'Bukti Penggunaan Barang';
            $dataStruk = DB::table('strukkirim_t as sr')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.objectpegawaipengirimfk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.objectruanganfk')
                ->JOIN('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sr.norec')
                ->select(
                    'sr.norec',
                    'sr.isfloor',
                    'sr.nokirim',
                    'pg.id as pgid',
                    'pg.namalengkap',
                    'ru.id',
                    'kp.harganetto',
                    'ru.namaruangan',
                    'sr.jenispermintaanfk',
                    'sr.tglkirim',
                    'sr.keteranganlainnyakirim as keterangan'
                )
                ->where('sr.kdprofile', $idProfile);

            if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
                $dataStruk = $dataStruk->where('sr.nokirim', '=', $request['nokirim']);
            }
            $dataStruk = $dataStruk->first();

            $data = DB::table('strukkirim_t as sp')
                ->leftJOIN('kirimproduk_t as spd', 'spd.nokirimfk', '=', 'sp.norec')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
                ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
                ->select(DB::raw("
                 sp.nokirim,spd.hargasatuan,sp.objectruanganfk,ru.namaruangan,
                 spd.objectprodukfk AS produkfk,pr.kdproduk,pr.namaproduk,CAST(spd.hasilkonversi AS FLOAT) AS nilaikonversi,
                 spd.objectsatuanstandarfk,ss.satuanstandar,spd.objectsatuanstandarfk AS satuanviewfk,ss.satuanstandar AS ssview,
                 sum(spd.qtyprodukkonfirmasi) as jumlah,spd.hargadiscount,spd.hargatambahan AS jasa,spd.hargasatuan AS hargajual,spd.harganetto,
                 CAST(sum(spd.qtyprodukkonfirmasi) * ROUND(spd.hargasatuan) - ROUND(spd.hargadiscount) as float) as total,sum(spd.qtyorder) as qtyorder,spd.qtyprodukretur
            "))
                ->where('sp.kdprofile', $idProfile)
                ->where('sp.statusenabled', true)
                ->where('spd.statusenabled', true)
                ->groupBy(
                    'sp.nokirim',
                    'ru.namaruangan',
                    'sp.objectruanganfk',
                    'spd.objectprodukfk',
                    'pr.kdproduk',
                    'pr.namaproduk',
                    'spd.hasilkonversi',
                    'spd.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'spd.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'spd.hargadiscount',
                    'spd.hargatambahan',
                    'spd.hargasatuan',
                    'spd.harganetto',
                    'spd.qtyprodukretur'
                );
            if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
                $data = $data->where('sp.nokirim', '=', $request['nokirim']);
            }
            $data = $data->get();

        }

        $pelayananPasien = [];
        $i = 0;
        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $hargasatuan = 0;
        $hargadiscount = 0;
        $total = 0;
        $aturanpakaifk = 0;
        $tglkadaluarsa = '';
        $nobatch = '';
        // return $data;
        foreach ($data as $item) {
            if ($item->jumlah > 0) {
                $i = $i + 1;
                $pelayananPasien[] = array(
                    'no' => $i,
                    'hargajual' => $item->hargajual,
                    'harganetto' => $item->harganetto,
                    'ruanganfk' => $item->objectruanganfk,
                    'produkfk' => $item->produkfk,
                    'kdproduk' => $item->kdproduk,
                    'namaproduk' => $item->namaproduk,
                    'nilaikonversi' => $item->nilaikonversi, 
                    'satuanstandarfk' => $item->satuanviewfk, 
                    'satuanstandar' => $item->ssview, 
                    'satuanviewfk' => $item->satuanviewfk,
                    'satuanview' => $item->ssview,
                    'jumlah' => isset($item->jumlahkonfirm) ? $item->jumlahkonfirm : $item->jumlah,
                    'qtyretur' => $item->qtyprodukretur,
                    'hargasatuan' => $item->harganetto,//isset($dataStruk->harganetto) && $dataStruk->harganetto ? $item->harganetto,
                    'hargadiscount' => $item->hargadiscount,
                    'total' => $item->harganetto*$item->jumlah,//(isset($dataStruk->harganetto) && $dataStruk->harganetto ? $item->harganetto : $item->harganetto2) * 
                    //(isset($item->jumlahkonfirm) ? $item->jumlahkonfirm : $item->jumlah),
                );
            }
        }

        $pageWidth = 950;
        
        $dataReport = array(
            'head' => $dataStruk,
            'detail' => $pelayananPasien,
            'message' => '@epic',
            'judul' => $judul,
            'pageWidth'=>$pageWidth
        );
        $res = array(
            'pdf'=>false,
            'indo'=>true,
            'print'=>true
        );

        // // dd(compact('head'));
        return view('report.logistik.bukti-penarikan', compact('dataReport', 'pageWidth','res', 'namaPegawai'));
    }

    public function cetakBuktiKirim(Request $request)
    {
        $idProfile = $this->kdProfile;
        $namaPegawai = $this->getNamaPegawai();
        $dataAsalProduk = DB::table('asalproduk_m as ap')
            ->select('ap.id', 'ap.asalproduk')
            ->get();
        if ($request['jenis'] == 'distribusi') {
            $dataStruk = DB::table('strukkirim_t as sr')
                ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'sr.noorderfk')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.objectpegawaipengirimfk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.objectruanganfk')
                ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.objectruangantujuanfk')
                ->leftJOIN('pegawai_m as pg2', 'pg2.id', '=', 'so.objectpegawaiorderfk')
                ->JOIN('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sr.norec')
                ->select(
                    'sr.norec',
                    'sr.nokirim',
                    'pg.id as pgid',
                    'pg.namalengkap',
                    'pg2.namalengkap as pegawaiorder',
                    'ru.id',
                    'kp.harganetto',
                    'ru.namaruangan',
                    'ru2.id as ruid2',
                    'ru2.namaruangan as namaruangan2',
                    'sr.jenispermintaanfk',
                    'sr.tglkirim',
                    'so.keterangan as keteranganminta',
                    'sr.keteranganlainnyakirim as keterangan','so.noorder'
                )
                ->where('sr.kdprofile', $idProfile);

            if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
                $dataStruk = $dataStruk->where('sr.nokirim', '=', $request['nokirim']);
            }
            $dataStruk = $dataStruk->first();

            $data = DB::table('strukkirim_t as sp')
                ->leftJOIN('kirimproduk_t as spd', 'spd.nokirimfk', '=', 'sp.norec')
                ->leftJoin('orderpelayanan_t as sot', function($join) {
                    $join->on('sot.noorderfk', '=', 'sp.noorderfk')
                        ->on('sot.objectprodukfk', '=', 'spd.objectprodukfk');
                })
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
                ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
                ->select(DB::raw("
                 sp.nokirim,
                 avg(spd.hargasatuan) as hargasatuan,
                 sp.objectruanganfk,
                 ru.namaruangan,
                 spd.objectprodukfk AS produkfk,
                 pr.kdproduk,
                 sot.avgpemakaian as avgpemakaian,
                 sot.sisastok as sisastok,
                 pr.namaproduk,
                 CAST(spd.hasilkonversi AS FLOAT) AS nilaikonversi,
                 spd.objectsatuanstandarfk,
                 ss.satuanstandar,
                 spd.objectsatuanstandarfk AS satuanviewfk,
                 ss.satuanstandar AS ssview,
                 sum(spd.qtyprodukkonfirmasi) as jumlah,
                 spd.hargadiscount,spd.hargatambahan AS jasa,spd.hargasatuan AS hargajual,
                 spd.harganetto,
                 CAST(sum(spd.qtyprodukkonfirmasi) * ROUND(spd.hargasatuan) - ROUND(spd.hargadiscount) as float) as total,
                 sum(spd.qtyorder) as qtyorder,
                 spd.qtyprodukretur
            "))
                ->where('sp.kdprofile', $idProfile)
                ->where('sp.statusenabled', true)
                ->where('spd.statusenabled', true)
                ->groupBy(
                    'sp.nokirim',
                    'sp.objectruanganfk',
                    'ru.namaruangan',
                    'spd.objectprodukfk',
                    'sot.avgpemakaian',
                    'sot.sisastok',
                    'pr.kdproduk',
                    'pr.namaproduk',
                    'spd.hasilkonversi',
                    'spd.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    // 'spd.nostrukterimafk',
                    'spd.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'spd.hargadiscount',
                    'spd.hargatambahan',
                    'spd.hargasatuan',
                    'spd.harganetto',
                    'spd.qtyprodukretur'
                );
                // -- CAST(sum(qtyprodukkonfirmasi) * spd.hargasatuan - spd.hargadiscount as float) as total
            if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
                $data = $data->where('sp.nokirim', '=', $request['nokirim']);
            }
            $data = $data->get();

        }

        if ($request['jenis'] == 'order') {
            $dataStruk = DB::table('strukorder_t as sr')
                ->leftJOIN('strukkirim_t as so', 'sr.norec', '=', 'so.noorderfk')
                ->leftJOIN('kirimproduk_t as spd', 'spd.nokirimfk', '=', 'so.norec')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.objectpegawaiorderfk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.objectruanganfk')
                ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.objectruangantujuanfk')
                ->leftJOIN('pegawai_m as pg2', 'pg2.id', '=', 'so.objectpegawaipengirimfk')
                ->select(
                    'sr.norec',
                    DB::raw("case when  so.nokirim is null then '-' else so.nokirim end as nokirim,case when pg2.namalengkap is null then
                     '-'  else pg2.namalengkap end as pegawaiorder "),
                    'sr.noorder',
                    'pg.id as pgid',
                    'pg.namalengkap',
                    'ru.id',
                    'ru.namaruangan',
                    'ru2.id as ruid2',
                    'spd.harganetto',
                    'ru2.namaruangan as namaruangan2',
                    'sr.jenispermintaanfk',
                    'sr.tglorder as tglkirim',
                    // 'sr.keterangan'
                    'sr.keteranganorder as keterangan'
                )
                ->where('sr.kdprofile', $idProfile);

            if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
                $dataStruk = $dataStruk->where('sr.noorder', '=', $request['noorder']);
            }
            $dataStruk = $dataStruk->first();

            $data = DB::table('strukorder_t as sp')
                ->JOIN('orderpelayanan_t as spd', 'spd.noorderfk', '=', 'sp.norec')
                ->leftJOIN('strukkirim_t as sk', 'sk.noorderfk', '=', 'sp.norec')
                ->leftJoin('kirimproduk_t as kp', function ($join) {
                    $join->on('kp.nokirimfk', '=', 'sk.norec')
                         ->on('kp.objectprodukfk', '=', 'spd.objectprodukfk');
                })
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
                ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
                ->select(DB::raw("
                 sp.noorder,
                 round(avg(spd.hargasatuan)) as hargasatuanorder,
                 sp.objectruanganfk,
                 ru.namaruangan,
                 spd.avgpemakaian as avgpemakaian,
                 spd.sisastok as sisastok,
                 round(avg(kp.hargasatuan)) as hargasatuan,
                 round(avg(kp.harganetto)) as harganetto,
                 spd.objectprodukfk AS produkfk,
                 pr.kdproduk,
                 pr.namaproduk,
                 CAST(spd.hasilkonversi AS FLOAT) AS nilaikonversi,
                 spd.objectsatuanstandarfk,spd.objectsatuanstandarfk AS satuanviewfk,ss.satuanstandar AS ssview,
                 sum(kp.qtyprodukkonfirmasi) as jumlahkonfirm, spd.qtyproduk AS jumlah,spd.hargadiscount,ROUND(avg(kp.hargasatuan)) AS hargajual,
                 spd.qtyprodukretur,ROUND(avg(kp.hargasatuan))  AS harganetto,
                 CAST(sum(spd.qtyprodukkonfirmasi) * ROUND(avg(kp.hargasatuan)) - ROUND(spd.hargadiscount) as float) as total
            "))
                ->where('sp.kdprofile', $idProfile)
                ->where('sp.statusenabled', true)
                ->where('spd.statusenabled', true)
                ->groupBy(
                    'sp.noorder',
                    'sp.objectruanganfk',
                    'ru.namaruangan',
                    'spd.objectprodukfk',
                    'spd.avgpemakaian',
                    'spd.sisastok',
                    'pr.kdproduk',
                    'pr.namaproduk',
                    // 'kp.hargasatuan',
                    // 'kp.harganetto',
                    'spd.hasilkonversi',
                    'spd.objectsatuanstandarfk',
                    // 'spd.qtyprodukkonfirmasi',
                    'spd.qtyproduk',
                    'ss.satuanstandar',
                    'spd.hargadiscount',
                    // 'spd.hargasatuan',
                    'spd.qtyprodukretur'
                );

            if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
                $data = $data->where('sp.noorder', '=', $request['noorder']);
            }
            $data = $data->get();

        }

        $pelayananPasien = [];
        $i = 0;
        // $dataStok = DB::select(
        //     DB::raw("select sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk, spd.tglkadaluarsa,
        //                 spd.harganetto2 as hargajual,spd.harganetto2 as harganetto,spd.hargadiscount, spd.nobatch,
        //         sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk
        //         from stokprodukdetail_t as spd
        //         inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
        //         where spd.kdprofile = $idProfile and spd.objectruanganfk =:ruanganid
        //         group by sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
        //                 spd.harganetto2,spd.hargadiscount,spd.tglkadaluarsa, spd.nobatch,
        //         spd.objectruanganfk
        //         order By sk.tglstruk"),
        //     array(
        //         'ruanganid' => $dataStruk->id
        //     )
        // );
        // return  $dataStok;
        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $hargasatuan = 0;
        $hargadiscount = 0;
        $total = 0;
        $aturanpakaifk = 0;
        $tglkadaluarsa = '';
        $nobatch = '';
        // return $data;
        foreach ($data as $item) {
            if ($item->jumlah > 0) {
                $i = $i + 1;
                // $dataStok = StokProdukDetail::where('objectprodukfk',$item->produkfk)->where('objectruanganfk',$item->objectruanganfk)->first();
                // foreach ($dataStok as $item2) {
                //     if ($item2->objectprodukfk == $item->produkfk) {
                //         if ($item2->qtyproduk > ($item->jumlah * $item->nilaikonversi)) {
                //             $hargajual = $item->hargajual;
                //             // $harganetto = $item->harganetto;
                //             $tglkadaluarsa = $item2->tglkadaluarsa;
                //             $nobatch = $item2->nobatch;
                //             $nostrukterimafk = $item2->norec;
                //             $asalprodukfk = $item2->objectasalprodukfk;
                //             $jmlstok = $item2->qtyproduk;
                //             // $hargasatuan = $harganetto; //$item2->harganetto;
                //             $hargadiscount = $item->hargadiscount;
                //             $total = (((float)$item->jumlah * ((float)$hargasatuan - (float)$hargadiscount)));
                //             break;
                //         }
                //     }
                // }
                // foreach ($dataAsalProduk as $item3) {
                //     if ($asalprodukfk == $item3->id) {
                //         $asalproduk = $item3->asalproduk;
                //     }
                // }
                $pelayananPasien[] = array(
                    'no' => $i,
                    'hargajual' => $item->hargajual,
                    // 'tglkadaluarsa' => $dataStok->tglkadaluarsa,
                    'harganetto' => $item->harganetto,
                    // 'nostrukterimafk' => $dataStok->nostrukterimafk,
                    'ruanganfk' => $item->objectruanganfk,
                    // 'asalprodukfk' => $dataStok->asalprodukfk,
                    // 'asalproduk' => $asalproduk,
                    'produkfk' => $item->produkfk,
                    'kdproduk' => $item->kdproduk,
                    'namaproduk' => $item->namaproduk,
                    'nilaikonversi' => $item->nilaikonversi, ///$item->jumlah,
                    'satuanstandarfk' => $item->satuanviewfk, //objectsatuanstandarfk,
                    'satuanstandar' => $item->ssview, //satuanstandar,
                    'satuanviewfk' => $item->satuanviewfk,
                    'satuanview' => $item->ssview,
                    'avgpemakaian' => $item->avgpemakaian ? $item->avgpemakaian : 0,
                    'sisastok' => $item->sisastok ? $item->sisastok : 0,
                    'jumlah' => isset($item->jumlahkonfirm) ? $item->jumlahkonfirm : $item->jumlah,
                    // 'qtyorder' => $item->qtyorder,
                    'qtyretur' => $item->qtyprodukretur,
                    'hargasatuan' => isset($dataStruk->harganetto) && $dataStruk->harganetto ? $item->harganetto : $item->harganetto,
                    'hargadiscount' => $item->hargadiscount,
                    'total' => (isset($dataStruk->harganetto) && $dataStruk->harganetto ? $item->harganetto : $item->harganetto) * 
                    (isset($item->jumlahkonfirm) ? $item->jumlahkonfirm : $item->jumlah),
                    // 'noorder' => $item->noorder,
                    
                    // 'nobatch' => $nobatch,
                );
            }
        }


        // $result = array(
        //     'head' => $dataStruk,
        //     'detail' => $pelayananPasien,
        //     'message' => '@epic',
        // );

        $pageWidth = 950;
        // $res['pdf']=true;
        // $res['indo']=true;
        $res = array(
            'pdf'=>false,
            'indo'=>true
        );

        $dataReport = array(
            'head' => $dataStruk,
            'detail' => $pelayananPasien,
            'message' => '@epic',
            'judul' => $request['jenis'] == 'order' ? 'Bukti Order Barang Antar Ruangan' : 'Bukti Pengiriman Barang Antar Ruangan',
        );

        // dd(compact('head'));
        return view('report.logistik.bukti-pengiriman', compact('dataReport', 'pageWidth','res', 'namaPegawai'));
    }

    // order kirim
    public function getDetailOrderBarangForKirim(Request $request)
    {
        $idProfile = (int)$this->kdProfile;
        $dataAsalProduk = DB::table('asalproduk_m as ap')
        ->select('ap.id', 'ap.asalproduk')
        ->get();

        $dataStruk = DB::table('strukorder_t as sr')
        ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.objectpegawaiorderfk')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.objectruanganfk')
        ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.objectruangantujuanfk')
        ->select(
            'sr.noorder',
            'pg.id as pgid',
            'pg.namalengkap',
            'ru.id as ruidpengirim',
            'ru.namaruangan as ruanganpengirim',
            'ru2.id as ruidpenerima',
            'ru2.namaruangan as ruanganpenerima',
            'sr.tglorder',
            'sr.jenispermintaanfk',
            'sr.keteranganorder'
        )

            ->where('sr.kdprofile', $idProfile)
            ->where('sr.statusenabled', true);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sr.norec', '=', $request['norec']);
        }
        $dataStruk = $dataStruk->first();

        $jenis = '';
        if ($dataStruk->jenispermintaanfk == 1) {
            $jenis = 'Amprahan';
        }
        if ($dataStruk->jenispermintaanfk == 2) {
            $jenis = 'Transfer';
        }
        $detail = array(
            'tglorder' => $dataStruk->tglorder,
            'noorder' => $dataStruk->noorder,
            'pgid' => $dataStruk->pgid,
            'namalengkap' => $dataStruk->namalengkap,
            'ruidasal' => $dataStruk->ruidpenerima,
            'ruanganasal' => $dataStruk->ruanganpenerima,
            'ruidtujuan' => $dataStruk->ruidpengirim,
            'ruangantujuan' => $dataStruk->ruanganpengirim,
            'jenis' => $jenis,
            'jenisid' => $dataStruk->jenispermintaanfk,
            'keterangan' => $dataStruk->keteranganorder,
        );

        if(isset($request['noreckirim']) || $request['noreckirim'] != ''){
            $detailss = DB::table('kirimproduk_t as kp')
                ->Join('strukkirim_t as sk', 'sk.norec', '=', 'kp.nokirimfk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'kp.objectruanganfk')
                ->leftJoin('strukorder_t as sp', 'sp.norec', '=', 'sk.noorderfk')
                ->leftJoin('produk_m as pr', 'pr.id', '=', 'kp.objectprodukfk')
                ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'kp.objectsatuanstandarfk')
                ->select(
                    'sp.noorder',
                    'sp.objectruanganfk',
                    'ru.namaruangan',
                    'kp.objectprodukfk as produkfk',
                    'pr.kdproduk',
                    'pr.namaproduk',
                    DB::raw("SUM(kp.qtyprodukkonfirmasi) AS qtyprodukkonfirmasi"),
                    'kp.hasilkonversi as nilaikonversi',
                    'kp.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'kp.objectsatuanstandarfk as satuanviewfk',
                    'ss.satuanstandar as ssview',
                    DB::raw('sum(kp.qtyproduk) as jumlah')
                )
                ->where('kp.kdprofile', $this->kdProfile)
                ->where('sp.norec', $request['norec'])
                ->where('kp.qtyprodukkonfirmasi', '>', 0)
                ->where('kp.statusenabled', true)
                ->groupBy(
                    'sp.noorder',
                    'sp.objectruanganfk',
                    'ru.namaruangan',
                    'kp.objectprodukfk',
                    'pr.kdproduk',
                    'pr.namaproduk',
                    'kp.hasilkonversi',
                    'kp.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'kp.objectsatuanstandarfk',
                )
                ->get();
        }else{
            $detailss = DB::table('orderpelayanan_t as spd')
            ->leftJoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftJoin('strukorder_t as sp', 'sp.norec', '=', 'spd.strukorderfk')
            ->select(
                'sp.noorder',
                'sp.objectruanganfk',
                'ru.namaruangan',
                'spd.objectprodukfk as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                DB::raw("SUM(spd.qtyprodukkonfirmasi) as qtyprodukkonfirmasi"),
                'spd.hasilkonversi as nilaikonversi',
                'spd.objectsatuanstandarfk',
                'ss.satuanstandar',
                'spd.objectsatuanstandarfk as satuanviewfk',
                'ss.satuanstandar as ssview',
                'spd.qtyproduk as jumlah'
            )
                ->where('sp.kdprofile', $this->kdProfile)
                ->where('sp.norec', $request['norec'])
                ->where('sp.statusenabled', true)
                ->groupBy(
                    'sp.noorder',
                    'sp.objectruanganfk',
                    'ru.namaruangan',
                    'spd.objectprodukfk',
                    'pr.kdproduk',
                    'pr.namaproduk',
                    'spd.hasilkonversi',
                    'spd.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'spd.objectsatuanstandarfk',
                    'spd.qtyproduk'
                )
                ->get();
        }


        // Second Query
        // $detailOrder = DB::table('orderpelayanan_t as spd')
        //     ->leftJoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
        //     ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
        //     ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
        //     ->leftJoin('strukorder_t as sp', 'sp.norec', '=', 'spd.strukorderfk')
        //     ->select(
        //         'sp.noorder',
        //         'sp.objectruanganfk',
        //         'ru.namaruangan',
        //         'spd.objectprodukfk as produkfk',
        //         'pr.kdproduk',
        //         'pr.namaproduk',
        //         DB::raw("SUM(spd.qtyprodukkonfirmasi) as qtyprodukkonfirmasi"),
        //         'spd.hasilkonversi as nilaikonversi',
        //         'spd.objectsatuanstandarfk',
        //         'ss.satuanstandar',
        //         'spd.objectsatuanstandarfk as satuanviewfk',
        //         'ss.satuanstandar as ssview',
        //         'spd.qtyproduk as jumlah'
        //     )
        //     ->where('sp.kdprofile', $this->kdProfile)
        //     ->where('sp.norec', $request['norec'])
        //     ->where('sp.statusenabled', true)
        //     // ->whereNotExists(function ($query) {
        //     //     $query->select(DB::raw(1))
        //     //         ->from('kirimproduk_t as kr')
        //     //         ->join('strukkirim_t as sk', 'sk.norec', '=', 'kr.nokirimfk')
        //     //         ->join('strukorder_t as w', 'w.norec', '=', 'sk.noorderfk')
        //     //         ->whereRaw('sk.noorderfk = sp.norec')
        //     //         ->whereRaw('kr.objectprodukfk = spd.objectprodukfk');
        //     // })
        //     ->groupBy(
        //         'sp.noorder',
        //         'sp.objectruanganfk',
        //         'ru.namaruangan',
        //         'spd.objectprodukfk',
        //         'pr.kdproduk',
        //         'pr.namaproduk',
        //         'spd.hasilkonversi',
        //         'spd.objectsatuanstandarfk',
        //         'ss.satuanstandar',
        //         'spd.objectsatuanstandarfk',
        //         'spd.qtyproduk');

        // // Union of both queries
        // $data = $detailKirim->union($detailOrder)->get();

      

        $details = [];
        $i = 0;
       
        $asalprodukfk = 0;
        $asalproduk = '';
        $stokDammy = true;
        $jmlstok = 0;
        // $total = 0;
        // $aturanpakaifk = 0;
        foreach ($detailss as $item) {
            $i = $i + 1;

            $dataStok = StokProdukDetail::where('objectruanganfk', $detail['ruidasal'])->where('objectprodukfk', $item->produkfk)
                ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->sum('qtyproduk');

            $details[] = array(
                'no' => $i,
                'stock' => $dataStok,
                'ruanganfk' => $item->objectruanganfk,
                'asalprodukfk' => $asalprodukfk,
                'asalproduk' => $asalproduk,
                'produkfk' => $item->produkfk,
                'kdproduk' => $item->kdproduk,
                'namaproduk' => $item->namaproduk,
                'nilaikonversi' => $item->nilaikonversi,
                'satuanstandarfk' => $item->satuanviewfk, //objectsatuanstandarfk,
                'satuanstandar' => $item->ssview, //satuanstandar,
                'satuanviewfk' => $item->satuanviewfk,
                'satuanview' => $item->ssview,
                'jmlstok' => $dataStok,
                'jumlah' => $item->jumlah,
                'qtykonfirmasi' => $item->qtyprodukkonfirmasi,
                'qtyorder' => $item->jumlah,
            );
        }
        $result = array(
            'strukorder' => $detail,
            'order' => $details,
        );


        return $this->respond($result);
    }

    public function getDaftarDistribusiBarang(Request $request)
    {
        $idProfile = (int)$this->kdProfile;

        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.statusenabled',true)
            ->where('ru.statusenabled',true)
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->where('mlu.kdprofile', $idProfile)
            ->get();

        $strRuangan = [];
        
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }
        
        $data = DB::table('strukkirim_t as sp')
            ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->leftjoin('strukorder_t as so', 'sp.noorderfk', '=', 'so.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'so.statusorder',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereNotNull("kp.objectprodukfk")
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'so.statusorder',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
            )
            ->where('sp.statusenabled', true)
            // ->whereRaw('sp.isfloor != true')
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruanganasalfk', $strRuangan)
            ->orderBy('sp.nokirim', 'desc');

        if (isset($request['dari']) && $request['dari'] != '') {
            $data =  $data->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data =  $data->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data =  $data->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data =  $data->where('sp.objectruanganfk', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data =  $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
        //     $data =  $data->where('sp.objectprodukfk', '=', $request['produkfk']);
        // }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }

        $data = $data->get();

        //var_dump($data->toSql());

        // return $data;
        $detail = DB::table('kirimproduk_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukkirim_t as sp', 'sp.norec', '=', 'spd.nokirimfk')
            ->select(
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyproduk'),
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyprodukkirim')
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            // ->whereRaw('sp.isfloor != true')
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruanganasalfk', $strRuangan);
            // ->where('sp.noregistrasifk', '=', 0);
        // ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $detail =  $detail->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $detail =  $detail->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $detail =  $detail->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }

        $detail = $detail->groupBy('pr.id', 'pr.namaproduk', 'spd.qtyprodukretur', 'spd.objectprodukfk', 'sp.nokirim', 'spd.nokirimfk', 'ss.satuanstandar');
        $detail = $detail->get();
        // return $detail;

        $results = array();
        foreach ($data as $item) {
            $item->details = [];
            $cariData = false;
            foreach ($detail as $item2) {
                if ($item->norec == $item2->nokirimfk) {
                    $item->details[] = $item2;
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        if ($item2->objectprodukfk == $request['produkfk']) {
                            $cariData = true;
                        }
                    }
                }
            }
            if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                if ($cariData) {
                    $results[] = array(
                        'status' => 'Kirim Barang',
                        'statuskirim' => $item->statuskirim,
                        'tglstruk' => $item->tglkirim,
                        'nostruk' => $item->nokirim,
                        'noorderfk' => $item->noorderfk,
                        'statusorder' => $item->statusorder,
                        'jenispermintaanfk' => $item->jenispermintaanfk,
                        'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                        'norec' => $item->norec,
                        'ruasalid' => $item->ruasalid,
                        'idlogin' => $strRuangan,
                        'namaruanganasal' => $item->ruanganasal,
                        'rutujuanid' => $item->rutujuanid,
                        'namaruangantujuan' => $item->ruangantujuan,
                        'petugas' => $item->namalengkap,
                        'keterangan' => $item->keteranganlainnyakirim,
                        'jmlitem' => $item->jmlitem,
                        'details' => $item->details,
                        // 'qtyproduk' => $item2->qtyproduk,
                    );
                }
            } else {
                $results[] = array(
                    'status' => 'Kirim Barang',
                    'statuskirim' => $item->statuskirim,
                    'tglstruk' => $item->tglkirim,
                    'nostruk' => $item->nokirim,
                    'noorderfk' => $item->noorderfk,
                    'statusorder' => $item->statusorder,
                    'jenispermintaanfk' => $item->jenispermintaanfk,
                    'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                    'norec' => $item->norec,
                    'ruasalid' => $item->ruasalid,
                    'idlogin' => $strRuangan,
                    'namaruanganasal' => $item->ruanganasal,
                    'rutujuanid' => $item->rutujuanid,
                    'namaruangantujuan' => $item->ruangantujuan,
                    'petugas' => $item->namalengkap,
                    'keterangan' => $item->keteranganlainnyakirim,
                    'jmlitem' => $item->jmlitem,
                    'details' => $item->details,
                    // 'qtyproduk' => $item2->qtyproduk,
                );
            }
        }

        $data2 = DB::table('strukkirim_t as sp')
            ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            // ->whereRaw('sp.isfloor != true')
            ->whereNotNull("kp.objectprodukfk")
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
            )
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            // ->wherein('sp.objectruangantujuanfk', $strRuangan)
            ->wherein('sp.objectruanganfk', $strRuangan)
            ->orderBy('sp.nokirim', 'desc');

        if (isset($request['dari']) && $request['dari'] != '') {
            $data2 =  $data2->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data2 =  $data2->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data2 =  $data2->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data2 =  $data2->where('sp.objectruanganfk', $request['ruanganasalfk']);
        }
        
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data2 =  $data2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
        //     $data2 =  $data2->where('sp.objectprodukfk', '=', $request['produkfk']);
        // }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data2 = $data2->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data2 = $data2->limit($request['limit']);
        }

        $data2 = $data2->get();

        $detail2 = DB::table('kirimproduk_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukkirim_t as sp', 'sp.norec', '=', 'spd.nokirimfk')
            ->select(
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyproduk'),
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyprodukkirim')
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            // ->whereRaw('sp.isfloor != true')
            // ->wherein('sp.objectruangantujuanfk', $strRuangan);
            ->wherein('sp.objectruanganfk', $strRuangan);
            // ->where('sp.noregistrasifk', '=', 0);
        // ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $detail2 =  $detail2->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $detail2 =  $detail2->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $detail2 =  $detail2->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail2 =  $detail2->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail2 =  $detail2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }

        $detail2 = $detail2->groupBy('pr.id', 'pr.namaproduk', 'spd.qtyprodukretur', 'spd.objectprodukfk', 'sp.nokirim', 'spd.nokirimfk', 'ss.satuanstandar');
        $detail2 = $detail2->get();
        // return $detail;

        // $results = array();

        //var_dump($data);
        foreach ($data2 as $item) {
            $item->details = [];
            $cariData = false;
            foreach ($detail2 as $item2) {
                if ($item->norec == $item2->nokirimfk) {
                    $item->details[] = $item2;
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        if ($item2->objectprodukfk == $request['produkfk']) {
                            $cariData = true;
                        }
                    }
                }
            }
            if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                if ($cariData) {
                    $results[] = array(
                        'status' => 'Terima Barang',
                        'statuskirim' => $item->statuskirim,
                        'tglstruk' => $item->tglkirim,
                        'nostruk' => $item->nokirim,
                        'noorderfk' => $item->noorderfk,
                        'jenispermintaanfk' => $item->jenispermintaanfk,
                        'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                        'idlogin2' => $strRuangan,
                        'norec' => $item->norec,
                        'ruasalid' => $item->ruasalid,
                        'namaruanganasal' => $item->ruanganasal,
                        'rutujuanid' => $item->rutujuanid,
                        'namaruangantujuan' => $item->ruangantujuan,
                        'petugas' => $item->namalengkap,
                        'keterangan' => $item->keteranganlainnyakirim,
                        'jmlitem' => $item->jmlitem,
                        'details' => $item->details,
                        // 'qtyproduk' => $item2->qtyproduk,
                    );
                }
            } else {
                $results[] = array(
                    'status' => 'Terima Barang',
                    'statuskirim' => $item->statuskirim,
                    'tglstruk' => $item->tglkirim,
                    'nostruk' => $item->nokirim,
                    'noorderfk' => $item->noorderfk,
                    'jenispermintaanfk' => $item->jenispermintaanfk,
                    'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                    'idlogin2' => $strRuangan,
                    'norec' => $item->norec,
                    'ruasalid' => $item->ruasalid,
                    'namaruanganasal' => $item->ruanganasal,
                    'rutujuanid' => $item->rutujuanid,
                    'namaruangantujuan' => $item->ruangantujuan,
                    'petugas' => $item->namalengkap,
                    'keterangan' => $item->keteranganlainnyakirim,
                    'jmlitem' => $item->jmlitem,
                    'details' => $item->details,
                    // 'qtyproduk' => $item2->qtyproduk,
                );
            }
        }

        $result = array(
            'daftar' => $results,
        );

        return $this->respond($result);
    }

    public function getReturDistribusiBarang(Request $request)
    {
        $idProfile = (int)$this->kdProfile;

        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->where('mlu.kdprofile', $idProfile)
            ->get();

        $strRuangan = [];

        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $data = DB::table('strukkirim_t as sp')
            ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim'
            )
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruanganasalfk', $strRuangan)
            // ->where('sp.noregistrasifk', '=', 0)
            ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $data =  $data->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data =  $data->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $data =  $data->where('sp.nokirim', 'ILIKE', '%' . $request['nostruk']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data =  $data->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data =  $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
        //     $data =  $data->where('sp.objectprodukfk', '=', $request['produkfk']);
        // }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }

        $data = $data->get();

        $detail = DB::table('kirimproduk_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukkirim_t as sp', 'sp.norec', '=', 'spd.nokirimfk')
            ->select(
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw("sum(spd.qtyproduk) as qtyproduk")
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruanganasalfk', $strRuangan)
            ->where('sp.noregistrasifk', '=', 0)
            ->where('spd.qtyprodukretur', '!=', 0);
        // ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $detail =  $detail->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $detail =  $detail->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $detail =  $detail->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $detail =  $detail->where('spd.objectprodukfk', '=', $request['produkfk']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $detail = $detail->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $detail = $detail->limit($request['limit']);
        }

        $detail = $detail->groupBy('pr.id', 'pr.namaproduk', 'spd.qtyprodukretur', 'spd.objectprodukfk', 'sp.nokirim', 'spd.nokirimfk', 'ss.satuanstandar');
        $detail = $detail->get();
        // return $detail;

        $results = array();
        foreach ($data as $item) {
            $item->details = [];
            foreach ($detail as $item2) {
                if ($item->norec == $item2->nokirimfk) {
                    $item->details[] = $item2;
                }
            }
            $results[] = array(
                'status' => 'Kirim Barang',
                'tglstruk' => $item->tglkirim,
                'nostruk' => $item->nokirim,
                'noorderfk' => $item->noorderfk,
                'jenispermintaanfk' => $item->jenispermintaanfk,
                'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                'norec' => $item->norec,
                'ruasalid' => $item->ruasalid,
                'namaruanganasal' => $item->ruanganasal,
                'rutujuanid' => $item->rutujuanid,
                'namaruangantujuan' => $item->ruangantujuan,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganlainnyakirim,
                'jmlitem' => $item->jmlitem,
                'details' => $item->details,
                // 'qtyproduk' => $item2->qtyproduk,
            );
        }

        $result = array(
            'daftar' => $results,
        );

        return $this->respond($result);
    }

    public function SaveReturDistribusi(Request $request)
    {

        $idProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $jeniskirim = '';
            if ($request['strukkirim']['jenispermintaanfk'] == 2) {
                $jeniskirim = 'Transfer';
            } else {
                $jeniskirim = 'Amprahan';
            }
            $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->first();
            if ($request['strukkirim']['norecRetur'] == '') {
                $newSRetur = new StrukRetur();
                $norecSRetur = $newSRetur->generateNewId();
                $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $idProfile);
                $newSRetur->norec = $norecSRetur;
                $newSRetur->kdprofile = $idProfile;
                $newSRetur->statusenabled = true;
                $newSRetur->objectkelompoktransaksifk = $this->settingFix("KelompokTransaksiReturDistribusi");
                $newSRetur->noretur = $noRetur;
            } else {
                $newSRetur =  StrukRetur::where('norec', $request['strukkirim']['norecRetur'])->where('kdprofile', $idProfile)->first();
                StrukReturDetail::where('strukreturfk', $request['strukkirim']['norecRetur'])->where('kdprofile', $idProfile)->delete();
            }
            $newSRetur->keteranganalasan = $request['strukkirim']['keteranganlainnyakirim'];
            $newSRetur->keteranganlainnya = "Retur $jeniskirim Dari Ruangan " . $request['strukkirim']['ruangantujuan'] . " Ke Ruangan " . $request['strukkirim']['ruangan'] . "No Kirim: $dataSK->nokirim";
            $newSRetur->objectruanganfk = $request['strukkirim']['objectruanganfk'];
            $newSRetur->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
            $newSRetur->objectpegawaifk = $this->getPegawaiId();
            $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
            $newSRetur->strukkirimfk = $request['strukkirim']['noreckirim'];
            $newSRetur->save();
            $norecRetur = $newSRetur->norec;

            foreach($request['details'] as $items){
                $detailKirimProduk = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('objectprodukfk', $items['produkfk'])->get();

                $jumlah = (float)$items['qtyreturSekarang'];
                // foreach ($detailKirimProduk as $item) {
                    
                //     if ((float)$item->qtyprodukkonfirmasi <= $jumlah) {

                //         $sotkPengirim = StokProdukDetail::where('norec', $item->stokprodukdetailfk)->first();

                //         StokProdukDetail::where('kdprofile', $this->kdProfile)
                //             ->where('norec', $item->stokprodukdetailfk)
                //             ->where('statusenabled', true)
                //             ->lockForUpdate()
                //             ->increment('qtyproduk', (float)$jumlah);

                //         $strukRD = new StrukReturDetail();
                //         $strukRD->norec = $strukRD->generateNewId();
                //         $strukRD->kdprofile = $idProfile;
                //         $strukRD->statusenabled = true;
                //         $strukRD->objectasalprodukfk = $sotkPengirim->asalprodukfk;
                //         $strukRD->hargadiscount = 0;
                //         $strukRD->harganetto1 = (float)$sotkPengirim->harganetto1;
                //         $strukRD->harganetto2 = (float)$sotkPengirim->harganetto2;
                //         $strukRD->persendiscount = 0;
                //         $strukRD->objectprodukfk = $item['produkfk'];
                //         $strukRD->qtyproduk = (float)$item['qtyreturSekarang'];
                //         $strukRD->qtyprodukonhand = 0;
                //         $strukRD->qtyprodukoutext = 0;
                //         $strukRD->qtyprodukoutint = 0;
                //         $strukRD->nostrukterimafk = $sotkPengirim->nostrukterimafk;
                //         $strukRD->strukreturfk = $norecRetur;
                //         $strukRD->tglkadaluarsa = $sotkPengirim->tglkadaluarsa;
                //         $strukRD->save();    

                //         $jumlah = $jumlah - (float)$items->qtyprodukkonfirmasi;
                //     }else{
                //         $sotkPengirim = StokProdukDetail::where('norec', $item->stokprodukdetailfk)->first();

                //         $strukRD = new StrukReturDetail();
                //         $strukRD->norec = $strukRD->generateNewId();
                //         $strukRD->kdprofile = $idProfile;
                //         $strukRD->statusenabled = true;
                //         $strukRD->objectasalprodukfk = $sotkPengirim->asalprodukfk;
                //         $strukRD->hargadiscount = 0;
                //         $strukRD->harganetto1 = (float)$sotkPengirim->harganetto1;
                //         $strukRD->harganetto2 = (float)$sotkPengirim->harganetto2;
                //         $strukRD->persendiscount = 0;
                //         $strukRD->objectprodukfk = $item['produkfk'];
                //         $strukRD->qtyproduk = (float)$item['qtyreturSekarang'];
                //         $strukRD->qtyprodukonhand = 0;
                //         $strukRD->qtyprodukoutext = 0;
                //         $strukRD->qtyprodukoutint = 0;
                //         $strukRD->nostrukterimafk = $sotkPengirim->nostrukterimafk;
                //         $strukRD->strukreturfk = $norecRetur;
                //         $strukRD->tglkadaluarsa = $sotkPengirim->tglkadaluarsa;
                //         $strukRD->save();    

                //         StokProdukDetail::where('kdprofile', $this->kdProfile)
                //             ->where('norec', $item->stokprodukdetailfk)
                //             ->where('statusenabled', true)
                //             ->lockForUpdate()
                //             ->increment('qtyproduk', (float)$jumlah); 
                //     }
                // }

                // if ($request['strukkirim']['jenispermintaanfk'] == 2) {

                // }
                // $detailStokPenerima = StokProdukDetail::where('objectruanganfk',$request['strukkirim']['objectruangantujuanfk'])
                //                       ->where('objectprodukfk',$items->produkfk)->where('kdprofile',$this->kdProfile)
                //                       ->where('statusenabled',true)
                //                       ->get();

                // $stokAwalPenerima = StokProdukDetail::where('objectruanganfk',$request['strukkirim']['objectruangantujuanfk'])
                //                       ->where('objectprodukfk',$items->produkfk)->where('kdprofile',$this->kdProfile)
                //                       ->where('statusenabled',true)
                //                       ->sum('qtyproduk');
                                      

            }
        
            $this->LOGGING(
                $newSRetur->keteranganlain,
                $newSRetur->noretur,
                $request['strukkirim']['noreckirim'],
                'Retur ' . $jeniskirim . ' Dari Ruangan ' . $request['strukkirim']['ruangan'] .
                    ' Ke Ruangan ' . $request['strukkirim']['ruangantujuan'] .
                    ' No Kirim: ' . $dataSK->nokirim
            );
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Retur Barang Berhasil",
                'result' => [
                    'strukRetur' => $newSRetur,
                ],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => "Something Went Wrong",
                "result" => $e->getMessage()
            ];
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    // public function SaveReturDistribusi(Request $request)
    // {

    //     $idProfile = $this->kdProfile;
    //     DB::beginTransaction();
    //     try {
    //         $jeniskirim = '';
    //         if ($request['strukkirim']['jenispermintaanfk'] == 2) {
    //             $jeniskirim = 'Transfer';
    //         } else {
    //             $jeniskirim = 'Amprahan';
    //         }
    //         $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->first();
    //         if ($request['strukkirim']['norecRetur'] == '') {
    //             $newSRetur = new StrukRetur();
    //             $norecSRetur = $newSRetur->generateNewId();
    //             $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $idProfile);
    //             $newSRetur->norec = $norecSRetur;
    //             $newSRetur->kdprofile = $idProfile;
    //             $newSRetur->statusenabled = true;
    //             $newSRetur->objectkelompoktransaksifk = $this->settingFix("KelompokTransaksiReturDistribusi");
    //             $newSRetur->noretur = $noRetur;
    //         } else {
    //             $newSRetur =  StrukRetur::where('norec', $request['strukkirim']['norecRetur'])->where('kdprofile', $idProfile)->first();
    //             StrukReturDetail::where('strukreturfk', $request['strukkirim']['norecRetur'])->where('kdprofile', $idProfile)->delete();
    //         }
    //         $newSRetur->keteranganalasan = $request['strukkirim']['keteranganlainnyakirim'];
    //         $newSRetur->keteranganlainnya = "Retur $jeniskirim Dari Ruangan " . $request['strukkirim']['ruangantujuan'] . " Ke Ruangan " . $request['strukkirim']['ruangan'] . "No Kirim: $dataSK->nokirim";
    //         $newSRetur->objectruanganfk = $request['strukkirim']['objectruanganfk'];
    //         $newSRetur->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
    //         $newSRetur->objectpegawaifk = $this->getPegawaiId();
    //         $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
    //         $newSRetur->strukkirimfk = $request['strukkirim']['noreckirim'];
    //         $newSRetur->save();
    //         $norecRetur = $newSRetur->norec;

    //         foreach ($request['details'] as $item) {
    //             $norecKirim = "";
    //             $IdProduk = "";
    //             if ((float)$item['qtyreturSekarang'] != 0) {
    //                 //PENGIRIM
    //                 $dataSaldoAwalK = DB::select(
    //                     DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
    //                     where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
    //                     array(
    //                         'ruanganfk' => $request['strukkirim']['objectruanganfk'],
    //                         'produkfk' => $item['produkfk'],
    //                     )
    //                 );

    //                 $saldoAwalPengirim = 0;
    //                 foreach ($dataSaldoAwalK as $items) {
    //                     $saldoAwalPengirim = (float)$items->qty;
    //                 }
    //                 $norecKirim = $request['strukkirim']['noreckirim'];
    //                 $IdProduk = $item['produkfk'];
    //                 $nostrukterima = DB::select(
    //                     DB::raw("select nostrukterimafk from kirimproduk_t
    //                     where kdprofile = $idProfile and nokirimfk='$norecKirim' and objectprodukfk=$IdProduk")
    //                 );
    //                 $noTarimakeun = '';

    //                 foreach ($nostrukterima as $items) {
    //                     $noTarimakeun = $items->nostrukterimafk;
    //                 }

    //                 $tambah = StokProdukDetail::where('nostrukterimafk', $noTarimakeun)
    //                     ->where('kdprofile', $idProfile)
    //                     ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                     ->where('objectprodukfk', $item['produkfk'])
    //                     ->first();
    //                 // return $tambah;   
    //                 //## StrukReturDetail
    //                 $strukRD = new StrukReturDetail();
    //                 $strukRD->norec = $strukRD->generateNewId();
    //                 $strukRD->kdprofile = $idProfile;
    //                 $strukRD->statusenabled = true;
    //                 $strukRD->objectasalprodukfk = $tambah->asalprodukfk;
    //                 $strukRD->hargadiscount = 0;
    //                 $strukRD->harganetto1 = (float)$tambah->harganetto1;
    //                 $strukRD->harganetto2 = (float)$tambah->harganetto2;
    //                 $strukRD->persendiscount = 0;
    //                 $strukRD->objectprodukfk = $item['produkfk'];
    //                 $strukRD->qtyproduk = (float)$item['qtyreturSekarang'];
    //                 $strukRD->qtyprodukonhand = 0;
    //                 $strukRD->qtyprodukoutext = 0;
    //                 $strukRD->qtyprodukoutint = 0;
    //                 $strukRD->nostrukterimafk = $noTarimakeun;
    //                 $strukRD->strukreturfk = $norecRetur;
    //                 $strukRD->tglkadaluarsa = $tambah->tglkadaluarsa;
    //                 $strukRD->save();

    //                 StokProdukDetail::where('norec', $tambah->norec)
    //                     ->where('kdprofile', $idProfile)
    //                     ->update([
    //                         'qtyproduk' => (float)$tambah->qtyproduk + (float)$item['qtyreturSekarang']
    //                     ]);

    //                 KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])
    //                     ->where('kdprofile', $idProfile)
    //                     ->where('objectprodukfk', $item['produkfk'])
    //                     ->update([
    //                         'qtyprodukretur' => (float)$item['qtyreturSekarang'],
    //                         'qtyproduk' => (float)$item['jumlah'] - (float)$item['qtyreturSekarang'],
    //                     ]);

    //                 //## KartuStok

    //                 $this->kartu_STOK(array(
    //                     "saldoawal" => $saldoAwalPengirim,
    //                     "qtyin" => $item['qtyreturSekarang'],
    //                     "qtyout" => 0,
    //                     "saldoakhir" => $saldoAwalPengirim + (float)$item['qtyorder'],
    //                     "keterangan" => 'Retur  ' . $jeniskirim . '  Dari Ruangan  ' . $request['strukkirim']['ruangantujuan'] . '  Ke Ruangan  ' . $request['strukkirim']['ruangan'] . '  No Kirim:  ' . $dataSK->nokirim,
    //                     "produkfk" => $item['produkfk'],
    //                     "ruanganfk" => $request['strukkirim']['objectruanganfk'],
    //                     "tglinput" => date('Y-m-d H:i:s'),
    //                     "tglkejadian" => date('Y-m-d H:i:s'),
    //                     "nostrukterimafk" => $item['nostrukterimafk'],
    //                     "norectransaksi" => $request['strukkirim']['noreckirim'],
    //                     "tabletransaksi" => 'strukkirim_t',
    //                     "flagfk" => null,
    //                 ));

    //                 if ($request['strukkirim']['jenispermintaanfk'] == 2) {
    //                     //PENERIMA

    //                     $dataSaldoAwalT = DB::select(
    //                         DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
    //                     where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
    //                         array(
    //                             'ruanganfk' => $request['strukkirim']['objectruangantujuanfk'],
    //                             'produkfk' => $item['produkfk'],
    //                         )
    //                     );
    //                     $saldoAwalPenerima = 0;
    //                     foreach ($dataSaldoAwalT as $items) {
    //                         $saldoAwalPenerima = (float)$items->qty;
    //                     }

    //                     $kurang = StokProdukDetail::where('nostrukterimafk', $noTarimakeun)
    //                         ->where('kdprofile', $idProfile)
    //                         ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                         ->where('objectprodukfk', $item['produkfk'])
    //                         ->first();
    //                     StokProdukDetail::where('norec', $kurang->norec)
    //                         ->where('kdprofile', $idProfile)
    //                         ->update([
    //                             'qtyproduk' => (float)$kurang->qtyproduk - (float)$item['qtyreturSekarang']
    //                         ]);

    //                     //## KartuStok
    //                     $this->kartu_STOK(array(
    //                         "saldoawal" => $saldoAwalPenerima,
    //                         "qtyin" => 0,
    //                         "qtyout" => $item['qtyreturSekarang'],
    //                         "saldoakhir" => $saldoAwalPenerima - (float)$item['qtyreturSekarang'],
    //                         "keterangan" => 'Retur  ' . $jeniskirim . '  Dari Ruangan  ' . $request['strukkirim']['ruangantujuan'] . '  Ke Ruangan  ' . $request['strukkirim']['ruangan'] . '  No Kirim:  ' . $dataSK->nokirim,
    //                         "produkfk" => $item['produkfk'],
    //                         "ruanganfk" => $request['strukkirim']['objectruanganfk'],
    //                         "tglinput" => date('Y-m-d H:i:s'),
    //                         "tglkejadian" => date('Y-m-d H:i:s'),
    //                         "nostrukterimafk" => $item['nostrukterimafk'],
    //                         "norectransaksi" => $request['strukkirim']['noreckirim'],
    //                         "tabletransaksi" => 'strukkirim_t',
    //                         "flagfk" => null,
    //                     ));
    //                 }
    //             }
    //         }

    //         $this->LOGGING(
    //             $newSRetur->keteranganlain,
    //             $newSRetur->noretur,
    //             $request['strukkirim']['noreckirim'],
    //             'Retur ' . $jeniskirim . ' Dari Ruangan ' . $request['strukkirim']['ruangan'] .
    //                 ' Ke Ruangan ' . $request['strukkirim']['ruangantujuan'] .
    //                 ' No Kirim: ' . $dataSK->nokirim
    //         );
    //         DB::commit();
    //         $result = [
    //             "status" => 200,
    //             "message" => "Retur Barang Berhasil",
    //             'result' => [
    //                 'strukRetur' => $newSRetur,
    //             ],
    //         ];
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         $result = [
    //             "status" => 400,
    //             "message"  => "Something Went Wrong",
    //             "result" => $e->getMessage()
    //         ];
    //     }
    //     return $this->respond($result, $result['status'], $result['message']);
    // }

    public function getDetailKirimBarangNew(Request $request)
    {

        $idProfile = $this->kdProfile;
        $dataAsalProduk = DB::table('asalproduk_m as ap')
            ->select('ap.id', 'ap.asalproduk')
            ->get();
        $dataStruk = DB::table('strukkirim_t as sr')
            ->JOIN('pegawai_m as pg', 'pg.id', '=', 'sr.objectpegawaipengirimfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.objectruanganfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.objectruangantujuanfk')
            ->select(
                'sr.norec',
                'sr.nokirim',
                'pg.id as pgid',
                'pg.namalengkap',
                'ru.id',
                'ru.namaruangan',
                'ru2.id as ruid2',
                'ru2.namaruangan as namaruangan2',
                'sr.jenispermintaanfk',
                'sr.tglkirim',
                'sr.keteranganlainnyakirim as keterangan'
            )
            ->where('sr.statusenabled', true)
            ->where('sr.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sr.norec', '=', $request['norec']);
        }
        $dataStruk = $dataStruk->first();

        $data = DB::table('strukkirim_t as sp')
            ->JOIN('kirimproduk_t as spd', 'spd.nokirimfk', '=', 'sp.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->select(DB::raw("
             sp.nokirim,spd.hargasatuan,spd.qtyprodukoutext,sp.objectruanganfk,ru.namaruangan,
             spd.objectprodukfk AS produkfk,pr.kdproduk,pr.namaproduk,CAST(spd.hasilkonversi AS FLOAT) AS nilaikonversi,
             spd.objectsatuanstandarfk,ss.satuanstandar,spd.objectsatuanstandarfk AS satuanviewfk,ss.satuanstandar AS ssview,
             sum(spd.qtyprodukkonfirmasi) AS jumlah,spd.hargadiscount,spd.hargatambahan AS jasa,spd.hargasatuan AS hargajual,spd.harganetto,
             spd.qtyprodukretur
        "))
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            ->where('spd.statusenabled', true);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('sp.norec', '=', $request['norec']);
        }

        $data = $data->groupByRaw("sp.nokirim,spd.hargasatuan,spd.qtyprodukoutext,sp.objectruanganfk,ru.namaruangan,
             spd.objectprodukfk,pr.kdproduk,pr.namaproduk,spd.hasilkonversi,
             spd.objectsatuanstandarfk,ss.satuanstandar,spd.objectsatuanstandarfk,ss.satuanstandar,
             spd.hargadiscount,spd.hargatambahan,spd.hargasatuan,spd.harganetto,
             spd.qtyprodukretur");
        $data = $data->get();

        $pelayananPasien = [];
        $i = 0;
        // $dataStok = DB::select(
        //     DB::raw("select sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
        //                 spd.harganetto2 as hargajual,spd.harganetto2 as harganetto,spd.hargadiscount,
        //         sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk
        //         from stokprodukdetail_t as spd
        //         inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
        //         where spd.kdprofile = $idProfile and spd.objectruanganfk =:ruanganid
        //         group by sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
        //                 spd.harganetto2,spd.hargadiscount,
        //         spd.objectruanganfk
        //         order By sk.tglstruk"),
        //     array(
        //         'ruanganid' => $dataStruk->id
        //     )
        // );

        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $hargasatuan = 0;
        $hargadiscount = 0;
        $total = 0;
        $aturanpakaifk = 0;
        foreach ($data as $item) {
            if ($item->jumlah > 0) {
                $i = $i + 1;
                $dataStok = StokProdukDetail::where('objectruanganfk', $dataStruk->id)->where('objectprodukfk', $item->produkfk)
                    ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->sum('qtyproduk');

                // foreach ($dataStok as $item2) {
                //     if ($item2->objectprodukfk == $item->produkfk) {
                //         if ($item2->qtyproduk > ($item->jumlah * $item->nilaikonversi)) {
                //             $hargajual = $item->hargajual;
                //             $harganetto = $item->harganetto;
                //             $nostrukterimafk = $item2->norec;
                //             $asalprodukfk = $item2->objectasalprodukfk;
                //             $jmlstok = $item2->qtyproduk;
                //             $hargasatuan = $harganetto; //$item2->harganetto;
                //             $hargadiscount = $item->hargadiscount;
                //             $total = (((float)$item->jumlah * ((float)$hargasatuan - (float)$hargadiscount)));
                //             break;
                //         }
                //     }
                // }
                // foreach ($dataAsalProduk as $item3) {
                //     if ($asalprodukfk == $item3->id) {
                //         $asalproduk = $item3->asalproduk;
                //     }
                // }
                $pelayananPasien[] = array(
                    'no' => $i,
                    'noregistrasifk' => '',
                    'tglregistrasi' => '',
                    'generik' => null,
                    'hargajual' => $item->hargasatuan,
                    'jenisobatfk' => '',
                    'kelasfk' => '',
                    'stock' =>  $dataStok,
                    'harganetto' => $item->harganetto,
                    // 'nostrukterimafk' => $item->nostrukterimafk,
                    // 'norec_spd' => $item->stokprodukdetailfk,
                    'ruanganfk' => $item->objectruanganfk,
                    'rke' => 0,
                    'jeniskemasanfk' => 0,
                    'jeniskemasan' => '',
                    'aturanpakai' => '',
                    'routefk' => 0,
                    'route' => '',
                    // 'asalprodukfk' => $asalprodukfk,
                    // 'asalproduk' => $asalproduk,
                    'produkfk' => $item->produkfk,
                    'kdproduk' => $item->kdproduk,
                    'namaproduk' => $item->namaproduk,
                    'nilaikonversi' => $item->nilaikonversi, ///$item->jumlah,
                    'satuanstandarfk' => $item->satuanviewfk, //objectsatuanstandarfk,
                    'satuanstandar' => $item->ssview, //satuanstandar,
                    'satuanviewfk' => $item->satuanviewfk,
                    'satuanview' => $item->ssview,
                    'jmlstok' =>  $item->jumlah,
                    'qtysebelumnya' => $item->jumlah,
                    'qtyprodukkonfirmasi' => $item->jumlah,
                    'jumlah' => $item->jumlah,
                    'qtyorder' => $item->jumlah,
                    'qtyretur' => $item->qtyprodukretur,
                    'dosis' => 1,
                    'hargasatuan' => $item->hargasatuan,
                    'hargadiscount' => 0,
                    // 'total' => $total + $item->jasa,
                    // 'jmldosis' => (string)($item->jumlah / $item->nilaikonversi) / 1 . '/' . (string)1,
                    'jasa' => $item->jasa,
                    // 'no' => $i,
                    // 'noregistrasifk' => '',
                    // 'tglregistrasi' => '',
                    // 'generik' => null,
                    // 'hargajual' => $hargajual,
                    // 'jenisobatfk' => '',
                    // 'kelasfk' => '',
                    // 'stock' => $dataStok,
                    // 'harganetto' => $harganetto,
                    // 'ruanganfk' => $item->objectruanganfk,
                    // 'rke' => 0,
                    // 'jeniskemasanfk' => 0,
                    // 'jeniskemasan' => '',
                    // 'aturanpakaifk' => $aturanpakaifk,
                    // 'aturanpakai' => '',
                    // 'routefk' => 0,
                    // 'route' => '',
                    // 'asalprodukfk' => $asalprodukfk,
                    // 'asalproduk' => $asalproduk,
                    // 'produkfk' => $item->produkfk,
                    // 'kdproduk' => $item->kdproduk,
                    // 'namaproduk' => $item->namaproduk,
                    // 'nilaikonversi' => $item->nilaikonversi, ///$item->jumlah,
                    // 'satuanstandarfk' => $item->satuanviewfk, //objectsatuanstandarfk,
                    // 'satuanstandar' => $item->ssview, //satuanstandar,
                    // 'satuanviewfk' => $item->satuanviewfk,
                    // 'satuanview' => $item->ssview,
                    // 'jmlstok' => $dataStok,
                    // 'jumlah' => $item->jumlah,
                    // 'qtyorder' => $item->jumlah,
                    // 'qtyretur' => $item->qtyprodukretur,
                    // 'dosis' => 1,
                    // 'hargasatuan' => $hargasatuan,
                    // 'hargadiscount' => $hargadiscount,
                    // 'total' => $total + $item->jasa,
                    // 'jmldosis' => (string)($item->jumlah / $item->nilaikonversi) / 1 . '/' . (string)1,
                    // 'jasa' => $item->jasa,
                );
            }
        }

        $result = array(
            'head' => $dataStruk,
            'detail' => $pelayananPasien,
            'message' => '@epic',
        );
        return $this->respond($result);
    }


    public function saveKirimOrderBarangVersiLama(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $noOrder = '';
        $dataStok = '';

        DB::beginTransaction();
        try {

            if ($request['strukkirim']['norecOrder'] == '') {

                if ($request['strukkirim']['jenispermintaanfk'] != 1) {
                    $noOrder = $this->generateCode(new StrukOrder, 'noorder', 14, 'OTRF-' . $this->getDateTime()->format('ym'), $idProfile);
                } else {
                    $noOrder = $this->generateCode(new StrukOrder, 'noorder', 14, 'OAMP-' . $this->getDateTime()->format('ym'), $idProfile);
                }
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;
                $dataSO->isdelivered = 0;
                $dataSO->noorder = $noOrder;
                $dataSO->objectkelompoktransaksifk = $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
                $dataSO->objectruanganfk = $request['strukkirim']['objectruanganfk'];
                $dataSO->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
                $dataSO->tglorder = $request['strukkirim']['tglkirim'];
                $dataSO->jenispermintaanfk = $request['strukkirim']['jenispermintaanfk'];
                $dataSO->keteranganorder = $request['strukkirim']['keteranganlainnyakirim'];
                $dataSO->objectpegawaiorderfk = $this->getPegawaiId();
                $dataSO->qtyjenisproduk = $request['strukkirim']['qtydetailjenisproduk'];
                $dataSO->qtyproduk = $request['strukkirim']['qtyproduk'];
            } else {;
                $dataSO = StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->first();
                OrderPelayanan::where('noorderfk', $request['strukkirim']['norecOrder'])->delete();
            }

            $dataSO->statusorder = 1;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->save();
            $dataSO = $dataSO->norec;
            $resultOrder = [];
            foreach ($request['details'] as $item) {
                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->hasilkonversi = $item['nilaikonversi'];
                $dataOP->iscito = 0;
                $dataOP->nostrukterimafk = $item['nostrukterimafk'];
                $dataOP->noorderfk = $dataSO;
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->qtyproduk = $item['jumlah'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->qtyprodukkonfirmasi = $item['qtyprodukkonfirmasi'];
                $dataOP->objectruanganfk = $request['strukkirim']['objectruanganfk'];
                $dataOP->objectruangantujuanfk =  $request['strukkirim']['objectruangantujuanfk'];
                $dataOP->objectsatuanstandarfk = $item['satuanviewfk'];
                $dataOP->strukorderfk = $dataSO;
                $dataOP->tglpelayanan = $request['strukkirim']['tglkirim'];
                $dataOP->save();

                $resultOrder[] = $dataOP;

                $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)->where('id', $request['strukkirim']['objectruanganfk'])
                    ->first();

                $strRuanganAsal = $ruanganAsal->namaruangan;

                $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)->where('id', $request['strukkirim']['objectruangantujuanfk'])
                    ->first();

                $strRuanganTujuan = $ruanganTujuan->namaruangan;
                $ru = $request['strukkirim']['objectruanganfk'];

                $dataSaldoAwaPengirim = StokProdukDetail::where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectprodukfk', $item['produkfk'])
                    ->sum('qtyproduk');

                $jumlah = (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'];
                $saldoAwalPengirimIn = $dataSaldoAwaPengirim - $jumlah;

                if ($saldoAwalPengirimIn < 0) {
                    $transMessage = "Simpan Kirim Barang Gagal, Stok Produk " . $item['namaproduk'] . ", ada " . $jumlah . " Data Stok Kurang Dari Qty Resep !";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "message"  => $transMessage,
                        "as" => 'as@epic',
                    );
                    return $this->respond($result, $result['status'], $result['message']);
                }

                $dataSaldoAwalK = StokProdukDetail::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    ->get();

                foreach ($dataSaldoAwalK as $items) {
                    if ((float)$items->qtyproduk <= $jumlah) {
                        if ((float)$items->qtyproduk > 0) {
                            DB::table('stokprodukdetail_t')
                                ->where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->norec)
                                ->lockForUpdate()
                                ->decrement('qtyproduk', $jumlah);

                            // $jumlah = $jumlah - (float)$items->qtyproduk;
                        }
                    } else {
                        if ((float)$items->qtyproduk > 0) {
                            DB::table('stokprodukdetail_t')
                                ->where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->norec)
                                ->lockForUpdate()
                                ->decrement('qtyproduk', $jumlah);

                            // $jumlah = $jumlah - (float)$items->qtyproduk;
                        }
                    }
                }

                //## KartuStok
                $this->kartu_STOK([
                    "saldoawal" => (float)$dataSaldoAwaPengirim,
                    "qtyin" => 0,
                    "qtyout" => $jumlah,
                    "saldoakhir" => (float) $saldoAwalPengirimIn,
                    "keterangan" => 'Kirim Barang, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strRuanganTujuan . '. Berupa Produk ' . $item['namaproduk'],
                    "produkfk" => $item['produkfk'],
                    "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $item['nostrukterimafk'],
                    "norectransaksi" => $request['strukkirim']['noreckirim'],
                    "tabletransaksi" => 'strukorder_t',
                    // "stokprodukdetailfk" =>  $dataStok->norec
                ]);
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Simpan Kirim Order Berhasil',
                "data" => $dataSO,
                "order" => $resultOrder,
                "as" => 'as@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Simpan Kirim Order Gagal',
                "data" =>  $e->getMessage(),
                "as" => 'as@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getDetailKirimOrderBarang(Request $request)
    {

        $header = DB::table('strukorder_t as so')
            ->join('ruangan_m as rutu', 'rutu.id', 'so.objectruanganfk')
            ->join('ruangan_m as ruas', 'ruas.id', 'so.objectruangantujuanfk')
            ->join('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
            ->selectRaw("
                        so.norec,
                        so.keteranganorder as keterangan,
                        so.noorder,
                        so.objectpegawaiorderfk as pgid,
                        pg.namalengkap,
                        ruas.namaruangan AS ruanganasal,
                        rutu.namaruangan as ruangantujuan,
                        rutu.id as ruidtujuan,
                        ruas.id as ruidasal,
                        so.qtyproduk,
                        so.tglorder,
                        CASE WHEN so.jenispermintaanfk = 1 then 'Amprahan' else 'Transfer' end as jenis,
                        so.jenispermintaanfk as jenisid
                        ")
            ->where('so.norec', $request['norec'])
            ->where('so.statusenabled', true)
            ->where('so.kdprofile', $this->kdProfile)
            ->first();

        $details = DB::table('orderpelayanan_t as op')
            ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', 'op.objectsatuanstandarfk')
            ->select(
                'op.norec',
                'op.kdprofile',
                'op.statusenabled',
                'op.hasilkonversi',
                'op.iscito',
                'op.noorderfk',
                'pr.namaproduk',
                'op.objectprodukfk',
                'op.qtyproduk',
                'op.qtyprodukkonfirmasi',
                'op.objectruanganfk',
                'op.objectruangantujuanfk',
                'op.objectsatuanstandarfk',
                'ss.satuanstandar',
                'op.strukorderfk',
                'op.tglpelayanan',
                'op.nostrukterimafk'
            )
            ->where('op.strukorderfk', $request['norec'])
            ->where('op.kdprofile', $this->kdProfile)
            ->where('op.statusenabled', true)
            ->get();


        $detailOrder = [];
        foreach ($details as $detail) {
            $infoSPD = StokProdukDetail::selectRaw("norec,qtyproduk,harganetto2,harganetto1,tglkadaluarsa")
                ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                ->where('objectruanganfk', $detail->objectruanganfk)->where('objectprodukfk', $detail->objectprodukfk)
                ->where('nostrukterimafk', $detail->nostrukterimafk)
                ->where('qtyproduk', '>', 0)
                ->first();

            $totalStok = StokProdukDetail::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                ->where('objectruanganfk', $detail->objectruanganfk)->where('objectprodukfk', $detail->objectprodukfk)
                ->where('qtyproduk', '>', 0)
                ->sum('qtyproduk');

            $detailOrder[] = array(
                'norec_spd' => $infoSPD['norec'],
                'hargajual' => $infoSPD['harganetto1'],
                'stock' => $totalStok,
                'harganetto' => $infoSPD['harganetto2'],
                'nostrukterimafk' => $detail->nostrukterimafk,
                'ruanganfk' => $detail->objectruanganfk,
                'produkfk' => $detail->objectprodukfk,
                'tglkadaluarsa' => $infoSPD['tglkadaluarsa'],
                'namaproduk' => $detail->namaproduk,
                'nilaikonversi' => $detail->hasilkonversi,
                'satuanstandarfk' => $detail->objectsatuanstandarfk, //objectsatuanstandarfk,
                'satuanstandar' => $detail->satuanstandar, //satuanstandar,
                'satuanviewfk' => $detail->objectsatuanstandarfk,
                'satuanview' => $detail->satuanstandar,
                'jmlstok' => $totalStok,
                'jumlah' => $detail->qtyproduk,
                'qtyprodukkonfirmasi' => $detail->qtyprodukkonfirmasi,
                'qtyorder' => $detail->qtyproduk,
            );
        }

        $result = [
            'strukorder' => $header,
            'order' => $detailOrder,
        ];
        return $this->respond($result);
    }

    public function saveKirimFloorStok(Request $request)
    {
        DB::beginTransaction();

        try {
            $noKirim = $request['strukkirim']['jenispermintaanfk'] == 2 ?
                $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'FLS-' . $this->getDateTime()->format('ym'), $this->kdProfile) :
                $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $this->kdProfile);

            $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $request['strukkirim']['objectruanganfk'])
                ->first();

            // $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
            //     ->where('statusenabled', true)
            //     ->where('id', $request['strukkirim']['objectruangantujuanfk'])
            //     ->first();

            $nameRuAsal = $ruanganAsal->namaruangan;
            // $nameRuTujuan = $ruanganTujuan->namaruangan;

            if ($request['strukkirim']['noreckirim'] == '') {
                if ($request['strukkirim']['norecOrder'] != '') {
                    StrukOrder::where('norec', $request['strukkirim']['norecOrder'])
                        ->where('kdprofile', $this->kdProfile)
                        ->update(['statusorder' => 1]);
                }

                $message = "Kirim Barang Berhasil";

                $dataSK = new StrukKirim;
                $dataSK->norec = $dataSK->generateNewId();
                $dataSK->nokirim = $noKirim;

            } else {
                $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->first();
                if (isset($request['strukkirim']['prihal']) == 'verifikasi') {
                    $message = "Verifikasi Barang Berhasil";
                    StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 3]);
                }else{
                    $message = "Update Kirim Barang Berhasil";

                    $strukKirimOld = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $this->kdProfile)->first();
                    $detailProdukKirim = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('statusenabled', true)->get();

                    foreach ($request['details'] as $item) {
                            $saldoAwalPengerim = StokProdukDetail::where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                            ->where('kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('objectprodukfk', $item['produkfk'])
                            ->sum('qtyproduk');

                        $saldoAkhirPengirim = (float)$saldoAwalPengerim + (float)$item['qtysebelumnya'];
                        $tglnow =  date('Y-m-d H:i:s');
                        $tglUbah = date('Y-m-d H:i:s', strtotime('-1 seconds', strtotime($tglnow)));

                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengerim,
                            "qtyin" => $item['qtysebelumnya'],
                            "qtyout" => 0,
                            "saldoakhir" => (float) $saldoAkhirPengirim,
                            "keterangan" => 'Ubah Kirim Barang, dari Ruangan ' . $nameRuAsal . ' Berupa Produk ' . $item['namaproduk'] . ' Dengan No Kirim : ' . $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $request['strukkirim']['noreckirim'],
                            "norectransaksi" => null,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    }

                    // if ($request['strukkirim']['objectruangantujuanfk'] == $strukKirimOld->objectruangantujuanfk) {
                       
                    //     foreach($detailProdukKirim as $items){
                    //         StokProdukDetail::where('kdprofile', $this->kdProfile)
                    //             ->where('norec', $items->stokprodukdetailfk)
                    //             ->where('statusenabled', true)
                    //             ->lockForUpdate()
                    //             ->increment('qtyproduk', (float)$items->qtyprodukkonfirmasi);
                    //     }

                    // }

                    // KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('statusenabled', true)->where('kdprofile',$this->kdProfile)->update(['statusenabled' => false]);
                   
                }
            }

            $dataSK->kdprofile = $this->kdProfile;
            $dataSK->statusenabled = true;
            $dataSK->objectpegawaipengirimfk = $this->getPegawaiId();
            $dataSK->objectruanganasalfk = $request['strukkirim']['objectruanganfk'];
            $dataSK->objectruanganfk = $request['strukkirim']['objectruanganfk'];
            $dataSK->objectruangantujuanfk = 0;
            $dataSK->jenispermintaanfk = $request['strukkirim']['jenispermintaanfk'];
            $dataSK->objectkelompoktransaksifk = $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
            $dataSK->keteranganlainnyakirim = $request['strukkirim']['keteranganlainnyakirim'];
            $dataSK->qtydetailjenisproduk = 0;
            $dataSK->qtyproduk = $request['strukkirim']['qtyproduk'];
            $dataSK->tglkirim = date($request['strukkirim']['tglkirim']);
            $dataSK->totalbeamaterai = 0;
            $dataSK->totalbiayakirim = 0;
            $dataSK->isfloor = true;
            $dataSK->totalbiayatambahan = 0;
            $dataSK->totaldiscount = 0;
            $dataSK->totalhargasatuan = $request['strukkirim']['totalhargasatuan'];
            $dataSK->totalharusdibayar = 0;
            $dataSK->totalpph = 0;
            $dataSK->totalppn = 0;
            $dataSK->noorderfk = $request['strukkirim']['norecOrder'] != '' ? $request['strukkirim']['norecOrder'] : null;
            // $dataSK->statuskirim = $request['strukkirim']['jenispermintaanfk'] == 1 ? 2 : $request['strukkirim']['statuskirim'];
            if ($request['strukkirim']['statuskirim'] == 1) { //kirim
                $dataSK->statuskirim = 1;
            } else { //=2 == verif
                $dataSK->statuskirim = 2;
            }
            $dataSK->save();

            $norecSK = $dataSK->norec;

            foreach ($request['details'] as $item) {

                $satuanstandar = Produk::select('objectsatuanstandarfk')
                                ->where('kdprofile', $this->kdProfile)
                                ->where('statusenabled', true)
                                ->where('id', $item['produkfk'])
                                ->first();
                
                $satuanstandarfk = $satuanstandar->objectsatuanstandarfk;

                ## Data Saldo Awal Pengirim
                $dataSaldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    ->where('qtyproduk', '>', 0)
                    ->where('statusenabled', true)
                    ->orderBy('tglkadaluarsa')
                    ->get();

                $dataKirimProduk = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('objectprodukfk', $item['produkfk'])
                                    ->where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                    ->select('stokprodukdetailfk as norec','qtyproduk')
                                    ->get();    

                $jumlah = (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'];

                ## Jumlah Saldo Awal Pengirim
                $saldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('statusenabled', true)
                    ->where('objectprodukfk', $item['produkfk'])
                    ->sum('qtyproduk');

                ## Jumlah Saldo Penerima
                // $saldoAwalPenerima = StokProdukDetail::where('kdprofile', $this->kdProfile)
                //     ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                //     ->where('objectprodukfk', $item['produkfk'])
                //     ->where('statusenabled', true)
                //     ->sum('qtyproduk');

                $detailProduk = count($dataKirimProduk) > 0 ?  $dataKirimProduk : $dataSaldoAwalPengirim;

                #### Jumlah Saldo Akhir Pengirim
                $saldoAkhirPengirim = (float)$saldoAwalPengirim - $jumlah;

                ############## Pengondisian Berdasarkan Jenis Permintaan ##############
                ##### Ket : (1. Amprahan) (2. Transfer)

                if ($request['strukkirim']['norecOrder']) {
                    OrderPelayanan::where('strukorderfk', $request['strukkirim']['norecOrder'])->where('objectprodukfk', $item['produkfk'])
                    ->update(
                        ['qtyprodukkonfirmasi' => $item['qtyprodukkonfirmasi']]);
                }

                if ($request['strukkirim']['jenispermintaanfk'] == 1) { //amprah
                    if ($request['strukkirim']['statuskirim'] == 1) {
                        foreach ($detailProduk as $items) {
                            if ((float)$items->qtyproduk <= $jumlah) {
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $items->nilaikonversi;
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = (float)$items->qtyorder;
                                $dataKP->qtyprodukterima = (float)$items->qtyproduk;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $items->satuanviewfk;
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                $dataKP->save();

                                $jumlah = $jumlah - (float)$items->qtyproduk;
                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$items->qtyproduk);
                            } else {
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $items->nilaikonversi;
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = $jumlah;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $jumlah;
                                $dataKP->qtyprodukterima = $jumlah;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $items->satuanviewfk;
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = $jumlah;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                $dataKP->save();

                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', $jumlah);

                                $jumlah = 0;
                            }
                        }
                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengirim,
                            "qtyin" => 0,
                            "qtyout" => (float)$item['jumlah'],
                            "saldoakhir" => $saldoAkhirPengirim,
                            "keterangan" => 'Kirim Amprahan ' . $item['namaproduk'] . ', dari Ruangan' . $nameRuAsal . ' No Kirim: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => null,
                            "norectransaksi" => $norecSK,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd']
                        ));
                    }
                }

                if ($request['strukkirim']['jenispermintaanfk'] == 2) {
                    //PENGIRIM
                    foreach ($detailProduk as $items) {
                        if ((float)$items->qtyproduk <= $jumlah) {

                            if ($request['strukkirim']['statuskirim'] == 1) { //kirim
                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$items->qtyproduk);

                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $item['nilaikonversi'];
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = (float)$item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $item['qtyorder'];
                                $dataKP->qtyprodukterima = (float)$items->qtyproduk;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk;
                                $dataKP->satuanviewfk = $item['satuanviewfk'];
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                                $dataKP->save();    
                            }else{
                                $dataStok = StokProdukDetail::where('norec', $items->norec)->where('statusenabled', true)->first();

                                $dataNewSPD = new StokProdukDetail;
                                $dataNewSPD->norec = $dataNewSPD->generateNewId();
                                $dataNewSPD->kdprofile = $this->kdProfile;
                                $dataNewSPD->statusenabled = true;
                                $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                                $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                                $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                                $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                                $dataNewSPD->persendiscount = 0;
                                $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                                $dataNewSPD->qtyproduk = (float)$items->qtyproduk;
                                $dataNewSPD->qtyprodukonhand = 0;
                                $dataNewSPD->qtyprodukoutext = 0;
                                $dataNewSPD->qtyprodukoutint = 0;
                                $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                                $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                                $dataNewSPD->nobatch = $dataStok->nobatch;
                                $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                                $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                                $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                                $dataNewSPD->save();

                                KirimProduk::where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $items->norec)
                                    ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
                            }
                            $jumlah = $jumlah - (float)$items->qtyproduk;

                        } else {

                            if ($request['strukkirim']['statuskirim'] == 1) { //kirim
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $this->kdProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto1;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->harganetto1;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $item['nilaikonversi'];
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $item['jumlah'];
                                $dataKP->qtyprodukkonfirmasi = $jumlah;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $item['qtyorder'];
                                $dataKP->qtyprodukterima = $jumlah;
                                $dataKP->stokprodukdetailfk = $items->norec;
                                // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = 0;
                                $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $item['satuanviewfk'];
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = $jumlah;
                                $dataKP->save();

                                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', $jumlah);
                            }else{
                                $dataStok = StokProdukDetail::where('norec', $items->norec)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();

                                $dataNewSPD = new StokProdukDetail;
                                $dataNewSPD->norec = $dataNewSPD->generateNewId();
                                $dataNewSPD->kdprofile = $this->kdProfile;
                                $dataNewSPD->statusenabled = true;
                                $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                                $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                                $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                                $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                                $dataNewSPD->persendiscount = 0;
                                $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                                $dataNewSPD->qtyproduk = ((float)$jumlah);
                                $dataNewSPD->qtyprodukonhand = 0;
                                $dataNewSPD->qtyprodukoutext = 0;
                                $dataNewSPD->qtyprodukoutint = 0;
                                $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                                $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                                $dataNewSPD->nobatch = $dataStok->nobatch;
                                $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                                $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                                $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                                $dataNewSPD->save();

                                KirimProduk::where('nokirimfk', $norecSK)->where('stokprodukdetailfk', $items->norec)
                                    ->update(['stokprodukdetailterimafk' => $dataNewSPD->norec]);
                            }
                            $jumlah = 0;

                        }
                    }

                    if ($request['strukkirim']['statuskirim'] == 1) {
                        # Dari Sisi Pengirim
                        $pengirim = $this->kartu_STOK(array(
                            "saldoawal" => (float)$saldoAwalPengirim,
                            "qtyin" => 0,
                            "qtyout" => (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'],
                            "saldoakhir" => $saldoAkhirPengirim,
                            "keterangan" => 'Pemakaian Floor Stok ' . $item['namaproduk'] . ', dari Ruangan ' . $nameRuAsal  . ' Nomor Input: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => null,
                            "norectransaksi" => $norecSK,
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" =>  $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    } else {
                        $penerima = $this->kartu_STOK(array(
                            // "saldoawal" => (float)$saldoAwalPenerima,
                            "qtyin" => (float)$item['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'],
                            "qtyout" => 0,
                            // "saldoakhir" => (float)$saldoAwalPenerima + (float)$item['qtyprodukkonfirmasi'],
                            "keterangan" => 'Terima Barang ' . $item['namaproduk'] . ', dari Ruangan ' . $nameRuAsal . ' No Kirim: ' .  $dataSK->nokirim,
                            "produkfk" => $item['produkfk'],
                            "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" =>  null,
                            "norectransaksi" => $request['strukkirim']['noreckirim'],
                            "tabletransaksi" => 'strukkirim_t',
                            // "stokprodukdetailfk" => $item['norec_spd'],
                            "flagfk" => null,
                        ));
                    }
                }

            }

            DB::commit();
            $result = [
                'status' => 201,
                'message' => $message,
                'data' => [
                    'Struk Kirim' => $dataSK,
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'data' => $e->getMessage() . $e->getLine(),
                'status' => 400,
                'message' => 'Simpan Data Gagal',
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getDaftarFloorStok(Request $request)
    {
        $idProfile = (int)$this->kdProfile;

        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.statusenabled',true)
            ->where('ru.statusenabled',true)
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->where('mlu.kdprofile', $idProfile)
            ->get();

        $strRuangan = [];
        
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }
        
        $data = DB::table('strukkirim_t as sp')
            ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->leftjoin('strukorder_t as so', 'sp.noorderfk', '=', 'so.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'so.statusorder',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.kdprofile', $idProfile)
            ->whereNotNull("kp.objectprodukfk")
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'so.statusorder',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
            )
            ->where('sp.statusenabled', true)
            ->where('sp.isfloor', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruanganasalfk', $strRuangan)
            ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $data =  $data->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data =  $data->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data =  $data->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data =  $data->where('sp.objectruanganfk', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data =  $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
        //     $data =  $data->where('sp.objectprodukfk', '=', $request['produkfk']);
        // }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }

        $data = $data->get();

        //var_dump($data->toSql());

        // return $data;
        $detail = DB::table('kirimproduk_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukkirim_t as sp', 'sp.norec', '=', 'spd.nokirimfk')
            ->select(
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyproduk'),
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyprodukkirim')
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruanganasalfk', $strRuangan);
            // ->where('sp.noregistrasifk', '=', 0);
        // ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $detail =  $detail->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $detail =  $detail->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $detail =  $detail->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }

        $detail = $detail->groupBy('pr.id', 'pr.namaproduk', 'spd.qtyprodukretur', 'spd.objectprodukfk', 'sp.nokirim', 'spd.nokirimfk', 'ss.satuanstandar');
        $detail = $detail->get();
        // return $detail;

        $results = array();
        foreach ($data as $item) {
            $item->details = [];
            $cariData = false;
            foreach ($detail as $item2) {
                if ($item->norec == $item2->nokirimfk) {
                    $item->details[] = $item2;
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        if ($item2->objectprodukfk == $request['produkfk']) {
                            $cariData = true;
                        }
                    }
                }
            }
            if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                if ($cariData) {
                    $results[] = array(
                        'status' => 'Kirim Barang',
                        'statuskirim' => $item->statuskirim,
                        'tglstruk' => $item->tglkirim,
                        'nostruk' => $item->nokirim,
                        'noorderfk' => $item->noorderfk,
                        'statusorder' => $item->statusorder,
                        'jenispermintaanfk' => $item->jenispermintaanfk,
                        'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                        'norec' => $item->norec,
                        'ruasalid' => $item->ruasalid,
                        'namaruanganasal' => $item->ruanganasal,
                        'rutujuanid' => $item->rutujuanid,
                        'namaruangantujuan' => $item->ruangantujuan,
                        'petugas' => $item->namalengkap,
                        'keterangan' => $item->keteranganlainnyakirim,
                        'jmlitem' => $item->jmlitem,
                        'details' => $item->details,
                        // 'qtyproduk' => $item2->qtyproduk,
                    );
                }
            } else {
                $results[] = array(
                    'status' => 'Kirim Barang',
                    'statuskirim' => $item->statuskirim,
                    'tglstruk' => $item->tglkirim,
                    'nostruk' => $item->nokirim,
                    'noorderfk' => $item->noorderfk,
                    'statusorder' => $item->statusorder,
                    'jenispermintaanfk' => $item->jenispermintaanfk,
                    'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                    'norec' => $item->norec,
                    'ruasalid' => $item->ruasalid,
                    'namaruanganasal' => $item->ruanganasal,
                    'rutujuanid' => $item->rutujuanid,
                    'namaruangantujuan' => $item->ruangantujuan,
                    'petugas' => $item->namalengkap,
                    'keterangan' => $item->keteranganlainnyakirim,
                    'jmlitem' => $item->jmlitem,
                    'details' => $item->details,
                    // 'qtyproduk' => $item2->qtyproduk,
                );
            }
        }

        $data2 = DB::table('strukkirim_t as sp')
            ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            // ->whereNot('sp.isfloor', true)
            ->whereNotNull("kp.objectprodukfk")
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
            )
            ->where('sp.statusenabled', true)
            ->where('sp.isfloor', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruangantujuanfk', $strRuangan)
            ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $data2 =  $data2->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data2 =  $data2->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data2 =  $data2->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data2 =  $data2->where('sp.objectruanganfk', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data2 =  $data2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
        //     $data2 =  $data2->where('sp.objectprodukfk', '=', $request['produkfk']);
        // }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data2 = $data2->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data2 = $data2->limit($request['limit']);
        }

        $data2 = $data2->get();

        $detail2 = DB::table('kirimproduk_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukkirim_t as sp', 'sp.norec', '=', 'spd.nokirimfk')
            ->select(
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyproduk'),
                DB::raw('SUM(CASE WHEN spd.statusenabled = true THEN spd.qtyprodukkonfirmasi ELSE 0 END) AS qtyprodukkirim')
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruangantujuanfk', $strRuangan);
            // ->where('sp.noregistrasifk', '=', 0);
        // ->orderBy('sp.nokirim');

        if (isset($request['dari']) && $request['dari'] != '') {
            $detail2 =  $detail2->where(DB::raw("sp.tglkirim::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $detail2 =  $detail2->where(DB::raw("sp.tglkirim::date"), '<=', $request->sampai);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $detail2 =  $detail2->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail2 =  $detail2->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail2 =  $detail2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }

        $detail2 = $detail2->groupBy('pr.id', 'pr.namaproduk', 'spd.qtyprodukretur', 'spd.objectprodukfk', 'sp.nokirim', 'spd.nokirimfk', 'ss.satuanstandar');
        $detail2 = $detail2->get();
        // return $detail;

        // $results = array();

        //var_dump($data);
        foreach ($data2 as $item) {
            $item->details = [];
            $cariData = false;
            foreach ($detail2 as $item2) {
                if ($item->norec == $item2->nokirimfk) {
                    $item->details[] = $item2;
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        if ($item2->objectprodukfk == $request['produkfk']) {
                            $cariData = true;
                        }
                    }
                }
            }
            if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                if ($cariData) {
                    $results[] = array(
                        'status' => 'Terima Barang',
                        'statuskirim' => $item->statuskirim,
                        'tglstruk' => $item->tglkirim,
                        'nostruk' => $item->nokirim,
                        'noorderfk' => $item->noorderfk,
                        'jenispermintaanfk' => $item->jenispermintaanfk,
                        'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                        'norec' => $item->norec,
                        'ruasalid' => $item->ruasalid,
                        'namaruanganasal' => $item->ruanganasal,
                        'rutujuanid' => $item->rutujuanid,
                        'namaruangantujuan' => $item->ruangantujuan,
                        'petugas' => $item->namalengkap,
                        'keterangan' => $item->keteranganlainnyakirim,
                        'jmlitem' => $item->jmlitem,
                        'details' => $item->details,
                        // 'qtyproduk' => $item2->qtyproduk,
                    );
                }
            } else {
                $results[] = array(
                    'status' => 'Terima Barang',
                    'statuskirim' => $item->statuskirim,
                    'tglstruk' => $item->tglkirim,
                    'nostruk' => $item->nokirim,
                    'noorderfk' => $item->noorderfk,
                    'jenispermintaanfk' => $item->jenispermintaanfk,
                    'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                    'norec' => $item->norec,
                    'ruasalid' => $item->ruasalid,
                    'namaruanganasal' => $item->ruanganasal,
                    'rutujuanid' => $item->rutujuanid,
                    'namaruangantujuan' => $item->ruangantujuan,
                    'petugas' => $item->namalengkap,
                    'keterangan' => $item->keteranganlainnyakirim,
                    'jmlitem' => $item->jmlitem,
                    'details' => $item->details,
                    // 'qtyproduk' => $item2->qtyproduk,
                );
            }
        }

        $result = array(
            'daftar' => $results,
        );

        return $this->respond($result);
    }
}
