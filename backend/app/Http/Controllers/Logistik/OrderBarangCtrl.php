<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\DetailGolonganProduk;
use App\Models\Master\Ruangan;
use App\Models\Master\SatuanResep;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukOrder;
use App\Models\Master\DetailJenisProduk;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderBarangCtrl extends Controller
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
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', true);
        // ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        //->where('spd.qtyproduk','>',0)
        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
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
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
            );
        }
        $res['produk'] = $dataProdukResult;
        return $this->respond($res);
    }

    public function dropdownList()
    {
        // return $this->getUserId();
        $idProfile = (int)$this->kdProfile;
        // $kelompokuser = DB::table('loginuser_s')
        // ->where('namauser', $this->getUsername())
        // ->select('objectkelompokuserfk')
        // ->first();
        // return $kelompokuser->objectkelompokuserfk;

        // Dibuka semua ruangan tgl 8 April 2025
        $res['ruangan'] = Ruangan::mine()->get();

        // $res['ruangan'] = DB::table('maploginusertoruangan_s as mlu')
        //     ->join('ruangan_m as ru', 'ru.id', 'mlu.objectruanganfk')
        //     ->select('ru.id', 'ru.namaruangan')
        //     ->where('ru.kdprofile', $this->kdProfile)
        //     ->where('ru.statusenabled', true)
        //     ->where('mlu.kdprofile', $this->kdProfile)
        //     ->where('mlu.statusenabled', true)
        //     ->where('mlu.objectloginuserfk', $this->getUserId())
        //     ->get();

        $res['golonganproduk'] = DetailGolonganProduk::select('*')->where('kdprofile', $idProfile)
        ->where('statusenabled', true) 
        ->get();   
        $res['rutu'] = Ruangan::select('id', 'namaruangan')->where('kdprofile', $idProfile)->where('statusenabled', true)->get();
        $res['satuanresep'] = SatuanResep::mine()->get();
        $res['jenisbarang'] = DetailJenisProduk::select('*')->where('kdprofile', $idProfile)
        ->where('statusenabled', true)
        ->whereIn('id',[2549,2547,2546])
        ->get();
        // $jenisKirim[] = array(
        //     'id' =>   2,
        //     'jenis' =>   'Amprahan',
        // );
        // allz target amprahan
        $jenisKirim[] = array(
            'id' =>   2,
            'jenis' =>   'Amprahan',
        );
        // $res['produk'] = $dataProdukResult;
        $res['jenis'] = $jenisKirim;
        $res['tarifadminresep'] = $this->settingFix('tarifadminresep');

        return $this->respond($res);
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
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    // public function getDaftarOrderBarang(Request $request)
    // {

    //     $idProfile = $this->kdProfile;
    //     $rangeDate = [$request->tglAwal, $request->tglAkhir];
    //     $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
    //     ->join('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
    //     ->select('ru.id')
    //     ->where('mlu.statusenabled', true)
    //         ->where('mlu.kdprofile', $idProfile)
    //         ->where('mlu.objectloginuserfk', $this->getUserId())
    //         ->get();
    //     $strRuangan = [];
    //     foreach ($dataRuangan as $epic) {
    //         $strRuangan[] = $epic->id;
    //     }

    //     $datal = DB::table('strukorder_t as sp')
    //     ->leftJoin('strukkirim_t as sk', 'sp.norec', 'sk.noorderfk')
    //     ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
    //     ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
    //     ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
    //     ->select(
    //         'sp.norec',
    //         'sk.norec as noreckirim',
    //         'sp.noorder',
    //         'sp.jenispermintaanfk',
    //         'pg.namalengkap',
    //         'ru.namaruangan as ruanganasal',
    //         'ru.id as ruanganasalfk',
    //         'ru2.namaruangan as ruangantujuan',
    //         'ru2.id as ruangantujuanfk',
    //         'sp.keteranganorder',
    //         'sp.statusorder',
    //         'sp.qtyjenisproduk',
    //         'sp.tglorder',
    //     )
    //         ->where('sp.kdprofile', $idProfile)
    //         ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
    //         ->where('sp.statusenabled', true)
    //         // ->where('sp.statusorder','!=', 2)
    //         ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
    //         ->wherein('sp.objectruangantujuanfk', $strRuangan)
    //         ->orderBy('sp.noorder');

    //     $data = DB::table('strukkirim_t as sk')
    //     ->join('strukorder_t as sp', 'sp.norec', 'sk.noorderfk')
    //     ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
    //     ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
    //     ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
    //     ->select(
    //         'sk.noorderfk as norec',
    //         'sk.norec as noreckirim',
    //         'sp.noorder',
    //         'sp.jenispermintaanfk',
    //         'pg.namalengkap',
    //         'ru.namaruangan as ruanganasal',
    //         'ru.id as ruanganasalfk',
    //         'ru2.namaruangan as ruangantujuan',
    //         'ru2.id as ruangantujuanfk',
    //         'sp.keteranganorder',
    //         'sp.statusorder',
    //         'sp.qtyjenisproduk',
    //         'sp.tglorder',
    //     )
    //         ->where('sp.kdprofile', $idProfile)
    //         ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
    //         ->where('sp.statusenabled', true)
    //         // ->where('sp.statusorder','!=', 2)
    //         ->where('sk.objectkelompoktransaksifk', $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN'))
    //         ->wherein('sp.objectruangantujuanfk', $strRuangan)
    //         ->orderBy('sp.noorder')
    //         ->union($datal)
    //         ->get();

    //     // if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
    //     //     $data = $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
    //     // }
    //     // if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
    //     //     $data = $data->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
    //     // }
    //     // if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
    //     //     $data = $data->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
    //     // }

    //     // $data = $data
    //     // $data = $data->get();

    //     $detail = DB::table('orderpelayanan_t as spd')
    //     // ->leftJoin('strukkirim_t as sk', 'sk.noorderfk', 'spd.noorderfk')
    //     // ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk','sk.norec')
    //     ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
    //     ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
    //     ->leftjoin('strukorder_t as sp', 'sp.norec', '=', 'spd.strukorderfk')
    //     ->select(
    //         DB::raw("pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,sp.noorder,
    //                     ss.satuanstandar, spd.strukorderfk, spd.objectprodukfk,spd.hasilkonversi,
    //                     sp.norec as noorderfk,sum(spd.qtyproduk) as qtyproduk , 0 as qtyprodukkirim")
    //     )
    //         ->where('sp.kdprofile', $idProfile)
    //         ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
    //         ->where('sp.statusenabled', true)
    //         // ->where('kp.statusenabled', true)
    //         ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
    //         ->wherein('sp.objectruangantujuanfk', $strRuangan)
    //         ->groupByRaw("pr.id,pr.kdproduk,pr.namaproduk,sp.noorder,ss.satuanstandar, spd.strukorderfk, spd.objectprodukfk,spd.hasilkonversi,sp.norec,qtyprodukkirim");
    //     // ->orderBy('sp.noorder');

    //     $details = DB::table('kirimproduk_t as kp')
    //     ->leftJoin('strukkirim_t as sk', 'sk.norec', 'kp.nokirimfk')
    //     ->leftjoin('produk_m as pr', 'pr.id', '=', 'kp.objectprodukfk')
    //     ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'kp.objectsatuanstandarfk')
    //     ->select(
    //         DB::raw("pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,sk.nokirim as noorder,
    //                     ss.satuanstandar, kp.nokirimfk as strukorderfk, kp.objectprodukfk,kp.hasilkonversi,
    //                     sk.noorderfk, SUM(kp.qtyproduk) AS qtyprodukkirim, 0 as qtyproduk")
    //     )
    //         ->where('kp.kdprofile', $idProfile)
    //         ->whereBetween(DB::raw("kp.tglpelayanan::date"), $rangeDate)
    //         ->where('kp.statusenabled', true)
    //         // ->where('kp.statusenabled', true)
    //         ->where('sk.objectkelompoktransaksifk', $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN'))
    //         ->wherein('kp.objectruanganpengirimfk', $strRuangan)
    //         ->groupByRaw("pr.id,pr.kdproduk,pr.namaproduk,sk.nokirim,ss.satuanstandar, kp.nokirimfk, kp.objectprodukfk,kp.hasilkonversi,sk.noorderfk,kp.qtyproduk")
    //         // ->orderBy('sp.noorder')
    //         ->union($detail)
    //         ->get();
    //     return $details;
    //     // if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
    //     //     $detail = $detail->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
    //     // }
    //     // if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
    //     //     $detail = $detail->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
    //     // }

    //     // if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
    //     //     $detail = $detail->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
    //     // }
    //     // $detail = $detail->groupByRaw("pr.id,pr.kdproduk,pr.namaproduk,sp.noorder,ss.satuanstandar,spd.qtyproduk, spd.strukorderfk, spd.objectprodukfk,spd.hasilkonversi");
    //     // $detail = $detail->orderBy('sp.noorder');
    //     // $detail = $detail->get();

    //     $results = array();
    //     foreach ($data as $item) {
    //         $item->details = [];
    //         $produkCari = false;
    //         foreach ($details as $item2) {
    //             if ($item->norec == $item2->noorderfk) {
    //                 $item->details[] = $item2;
    //                 // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
    //                 //     if($item2->objectprodukfk == $request['produkfk']){
    //                 //         $produkCari = true;
    //                 //     }
    //                 // }
    //             }
    //         }

    //         if ($item->statusorder == 0) {
    //             $status = 'Belum Kirim';
    //         } else if ($item->statusorder == 1) {
    //             $status = 'Sudah Kirim';
    //         } else if ($item->statusorder == 2) {
    //             $status = 'Batal Kirim';
    //         } else if ($item->statusorder == 3) {
    //             $status = 'Terverifikasi';
    //         }
    //         // if(isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined"){
    //         //     if($produkCari == true){
    //         //         $results[] = array(
    //         //             'status' => 'Terima Order Barang',
    //         //             'tglorder' => $item->tglorder,
    //         //             // 'qtyprodukkirim' => $item->qtyprodukkirim,
    //         //             'noreckirim' => $item->noreckirim,
    //         //             'noorder' => $item->noorder,
    //         //             'ruanganasalfk' => $item->ruanganasalfk,
    //         //             'ruangantujuanfk' => $item->ruangantujuanfk,
    //         //             'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
    //         //             'norec' => $item->norec,
    //         //             'namaruanganasal' => $item->ruanganasal,
    //         //             'namaruangantujuan' => $item->ruangantujuan,
    //         //             'petugas' => $item->namalengkap,
    //         //             'keterangan' => $item->keteranganorder,
    //         //             'statusorder' => $status,
    //         //             'jmlitem' => $item->qtyjenisproduk,
    //         //             'details' =>  $item->details,
    //         //         );
    //         //     }
    //         // }else{
    //         $results[] = array(
    //             'status' => 'Terima Order Barang',
    //             'tglorder' => $item->tglorder,
    //             // 'qtyprodukkirim' => $item->qtyprodukkirim,
    //             'noorder' => $item->noorder,
    //             'noreckirim' => $item->noreckirim,
    //             'ruanganasalfk' => $item->ruanganasalfk,
    //             'ruangantujuanfk' => $item->ruangantujuanfk,
    //             'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
    //             'norec' => $item->norec,
    //             'namaruanganasal' => $item->ruanganasal,
    //             'namaruangantujuan' => $item->ruangantujuan,
    //             'petugas' => $item->namalengkap,
    //             'keterangan' => $item->keteranganorder,
    //             'statusorder' => $status,
    //             'jmlitem' => $item->qtyjenisproduk,
    //             'details' =>  $item->details,
    //         );
    //         // }
    //     }

    //     // return $results;


    //     // $data2 = DB::table('strukorder_t as sp')
    //     //     ->leftJoin('strukkirim_t as sk', 'sp.norec', 'sk.noorderfk')
    //     //     ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
    //     //     ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
    //     //     ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
    //     //     ->select(
    //     //         'sp.norec',
    //     //         'sk.norec as noreckirim',
    //     //         'sp.noorder',
    //     //         'sp.jenispermintaanfk',
    //     //         'pg.namalengkap',
    //     //         'ru.namaruangan as ruanganasal',
    //     //         'ru2.namaruangan as ruangantujuan',
    //     //         'sp.keteranganorder',
    //     //         'sp.statusorder',
    //     //         'sp.qtyjenisproduk',
    //     //         DB::raw("CAST(sp.tglorder AS DATE)")
    //     //     )
    //     //     ->where('sp.kdprofile', $idProfile)
    //     //     ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
    //     //     ->where('sp.statusenabled', true)
    //     //     ->where('sp.statusorder', '!=', 2)
    //     //     ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
    //     //     ->wherein('sp.objectruanganfk', $strRuangan);

    //     // if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
    //     //     $data2 = $data2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
    //     // }
    //     // if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
    //     //     $data2 = $data2->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
    //     // }
    //     // if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
    //     //     $data2 = $data2->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
    //     // }

    //     // $data2 = $data2->orderBy('sp.noorder');
    //     // $data2 = $data2->get();

    //     // $detail2 = DB::table('orderpelayanan_t as spd')
    //     //     ->leftJoin('strukkirim_t as sk', 'sk.noorderfk', 'spd.noorderfk')
    //     //     ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', 'sk.norec')
    //     //     ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
    //     //     ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
    //     //     ->leftjoin('strukorder_t as sp', 'sp.norec', '=', 'spd.strukorderfk')
    //     //     ->select(
    //     //         DB::raw("pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,sp.noorder,
    //     //                 ss.satuanstandar,spd.qtyproduk, spd.strukorderfk, spd.objectprodukfk,spd.hasilkonversi,
    //     //                 SUM(CASE WHEN kp.statusenabled = true THEN kp.qtyproduk ELSE 0 END) AS qtyprodukkirim")
    //     //     )
    //     //     ->where('sp.kdprofile', $idProfile)
    //     //     // ->where('kp.statusenabled',true)
    //     //     ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
    //     //     ->where('sp.statusenabled', true)
    //     //     ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
    //     //     ->wherein('sp.objectruanganfk', $strRuangan);

    //     // if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
    //     //     $detail2 = $detail2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
    //     // }
    //     // if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
    //     //     $detail2 = $detail2->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
    //     // }
    //     // if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
    //     //     $detail2 = $detail2->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
    //     // }
    //     // $detail2 = $detail2->groupByRaw("pr.id,pr.kdproduk,pr.namaproduk,sp.noorder,ss.satuanstandar,spd.qtyproduk, spd.strukorderfk, spd.objectprodukfk,spd.hasilkonversi");
    //     // $detail2 = $detail2->orderBy('sp.noorder');
    //     // $detail2 = $detail2->get();

    //     // foreach ($data2 as $item) {
    //     //     $item->details = [];
    //     //     $produkCari = false;
    //     //     foreach ($detail2 as $item2) {
    //     //         if ($item->norec == $item2->strukorderfk) {
    //     //             $item->details[] = $item2;
    //     //             if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined"){
    //     //                 if($item2->objectprodukfk == $request['produkfk']){
    //     //                     $produkCari = $item2->objectprodukfk;
    //     //                 }
    //     //             }
    //     //         }
    //     //     }

    //     //     if ($item->statusorder == 0) {
    //     //         $status = 'Belum Kirim';
    //     //     } else if ($item->statusorder == 1) {
    //     //         $status = 'Sudah Kirim';
    //     //     } else if ($item->statusorder == 2) {
    //     //         $status = 'Batal Kirim';
    //     //     } else if ($item->statusorder == 3) {
    //     //         $status = 'Terverifikasi';
    //     //     }

    //     //     if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined"){
    //     //         if($produkCari == true){
    //     //             $results[] = array(
    //     //                 'status' => 'Kirim Order Barang',
    //     //                 'tglorder' => $item->tglorder,
    //     //                 // 'qtyprodukkirim' => $item->qtyprodukkirim,
    //     //                 'noreckirim' => $item->noreckirim,
    //     //                 'noorder' => $item->noorder,
    //     //                 'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
    //     //                 'norec' => $item->norec,
    //     //                 'namaruanganasal' => $item->ruanganasal,
    //     //                 'namaruangantujuan' => $item->ruangantujuan,
    //     //                 'petugas' => $item->namalengkap,
    //     //                 'keterangan' => $item->keteranganorder,
    //     //                 'statusorder' => $status,
    //     //                 'jmlitem' => $item->qtyjenisproduk,
    //     //                 'details' =>  $item->details,
    //     //                 'produkCAri' => $produkCari
    //     //             );
    //     //         }
    //     //     }else{
    //     //         $results[] = array(
    //     //             'status' => 'Kirim Order Barang',
    //     //             'tglorder' => $item->tglorder,
    //     //             'noorder' => $item->noorder,
    //     //             // 'qtyprodukkirim' => $item->qtyprodukkirim,
    //     //             'noreckirim' => $item->noreckirim,
    //     //             'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
    //     //             'norec' => $item->norec,
    //     //             'namaruanganasal' => $item->ruanganasal,
    //     //             'namaruangantujuan' => $item->ruangantujuan,
    //     //             'petugas' => $item->namalengkap,
    //     //             'keterangan' => $item->keteranganorder,
    //     //             'statusorder' => $status,
    //     //             'jmlitem' => $item->qtyjenisproduk,
    //     //             'details' =>  $item->details,
    //     //             'produkCari' => "masuk sini"
    //     //         );
    //     //     }

    //     // }

    //     $result = array(
    //         'daftar' => $results,
    //         'message' => '@epic',
    //     );
    //     return $this->respond($result);
    // }

    // public function getDaftarOrderBarang(Request $request)
    // {

    //     $idProfile = $this->kdProfile;
    //     $tglawal = $request->tglAwal;
    //     $tglakhir = $request->tglAkhir;
    //     $rangeDate = [$request->tglAwal, $request->tglAkhir];
    //     $kelompokOrder =  $this->kelompokTransaksi('ORDER BARANG RUANGAN');
    //     $kelompokKirim =  $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
    //     $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
    //     ->join('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
    //     ->select('ru.id')
    //     ->where('mlu.statusenabled', true)
    //         ->where('mlu.kdprofile', $idProfile)
    //         ->where('mlu.objectloginuserfk', $this->getUserId())
    //         ->get();
    //     $strRuangan = [];
    //     foreach ($dataRuangan as $epic) {
    //         $strRuangan[] = $epic->id;
    //     }
    //     $ruanganAll = implode(',',$strRuangan);


    //     $data = DB::table('strukorder_t as sp')
    //         ->leftJoin('strukkirim_t as sk', 'sk.noorderfk', 'sp.norec')
    //         ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
    //         ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
    //         ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
    //         ->select(
    //             'sp.norec',
    //             'sp.noorder',
    //             'sk.norec as noreckirim',
    //             // DB::raw('null as noreckirim'),
    //             'sp.jenispermintaanfk',
    //             'pg.namalengkap',
    //             'ru.namaruangan as ruanganasal',
    //             'ru.id as ruanganasalfk',
    //             'ru2.namaruangan as ruangantujuan',
    //             'ru2.id as ruangantujuanfk',
    //             'sp.keteranganorder',
    //             'sp.statusorder',
    //             'sp.qtyjenisproduk',
    //             'sp.tglorder',
    //         )
    //         ->where('sp.kdprofile', $idProfile)
    //         ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
    //         ->where('sp.statusenabled', true)
    //         ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
    //         ->wherein('sp.objectruangantujuanfk', $strRuangan);

    //     if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
    //         $data = $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
    //     }
    //     if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
    //         $data = $data->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
    //     }
    //     if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
    //         $data = $data->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
    //     }

    //     $data = $data->orderBy('sp.noorder');
    //     $data = $data->get();

    //     $detail = DB::select(
    //         DB::raw(
    //             "
    //             SELECT  x.kdproduk,
    //                     x.kdsirs,
    //                     x.namaproduk,
    //                     --x.noorder,
    //                     x.satuanstandar,
    //                     x.strukorderfk,
    //                     x.objectprodukfk,
    //                     x.hasilkonversi,
    //                     --x.noorderfk,
    //                     SUM(x.qtykonfirm) as qtyprodukkirim,
    //                     CASE WHEN SUM(x.qtyorder) = 0 THEN SUM(x.qtykonfirm) ELSE sum(x.qtyorder) END as qtyproduk
    //                     --sum(x.qtyorder) as qtyproduk
    //             from( 

    //                 Select
    //                     pr.ID AS kdproduk,
    //                     pr.kdproduk AS kdsirs,
    //                     pr.namaproduk,
    //                     sp.noorder,
    //                     ss.satuanstandar,
    //                     spd.strukorderfk,
    //                     spd.objectprodukfk,
    //                     spd.hasilkonversi,
    //                     sp.norec AS noorderfk,
    //                     0 AS qtykonfirm,
    //                     spd.qtyproduk as qtyorder
                    
    //                 From orderpelayanan_t as spd
    //                     LEFT JOIN produk_m AS pr ON pr.id = spd.objectprodukfk
    //                     LEFT JOIN satuanstandar_m AS ss ON ss.id = spd.objectsatuanstandarfk
    //                     LEFT JOIN strukorder_t AS sp ON sp.norec = spd.strukorderfk 
    //                             where spd.kdprofile = 1 
    //                     AND sp.tglorder::DATE BETWEEN '$tglawal' AND '$tglakhir'
    //                     AND spd.statusenabled = true
    //                     AND sp.objectkelompoktransaksifk = $kelompokOrder 
    //                     AND sp.objectruangantujuanfk IN ($ruanganAll) 
                                    
    //             Union all

    //             Select
    //             pr.ID AS kdproduk,
    //             pr.kdproduk AS kdsirs,
    //             pr.namaproduk,
    //             sk.nokirim AS noorder,
    //             ss.satuanstandar,
    //             sp.norec AS strukorderfk,
    //             kp.objectprodukfk,
    //             kp.hasilkonversi,
    //             sk.noorderfk,
    //             kp.qtyprodukkonfirmasi as qtykonfirm,
    //             0 as qtyorder 

    //             from kirimproduk_t as kp
    //                     LEFT JOIN strukkirim_t AS sk ON sk.norec = kp.nokirimfk
    //                     JOIN strukorder_t AS sp ON sp.norec = sk.noorderfk
    //                     LEFT JOIN produk_m AS pr ON pr.id = kp.objectprodukfk
    //                     LEFT JOIN satuanstandar_m AS ss ON ss.id = kp.objectsatuanstandarfk 

    //                                 where kp.kdprofile = 1 
    //                                 AND kp.tglpelayanan::DATE BETWEEN '$tglawal' AND '$tglakhir'
    //                                 AND kp.statusenabled = true
    //                                 AND sk.objectkelompoktransaksifk = $kelompokKirim 
    //                                 AND kp.objectruanganpengirimfk IN ($ruanganAll) 

    //             ) as x 
    //             group by x.kdproduk,
    //                     x.kdsirs,
    //                     x.namaproduk,
    //                     -- x.noorder,
    //                     x.satuanstandar,
    //                     x.strukorderfk,
    //                     x.objectprodukfk,
    //                     x.hasilkonversi
    //                     --x.noorderfk

    //             "
    //         )
    //     );    


    //     $results = array();

    //     foreach ($data as $item) {
    //         $item->details = [];
    //         foreach ($detail as $item2) {
    //             if ($item->norec == $item2->strukorderfk) {
    //                 $item->details[] = $item2;
    //             }
    //         }

    //         if ($item->statusorder == 0) {
    //             $status = 'Belum Kirim';
    //         } else if ($item->statusorder == 1) {
    //             $status = 'Sudah Kirim';
    //         } else if ($item->statusorder == 2) {
    //             $status = 'Batal Kirim';
    //         } else if ($item->statusorder == 3) {
    //             $status = 'Terverifikasi';
    //         }
    //         $results[] = array(
    //             'status' => 'Terima Order Barang',
    //             'tglorder' => $item->tglorder,
    //             'noorder' => $item->noorder,
    //             'noreckirim' => $item->noreckirim,
    //             'ruanganasalfk' => $item->ruanganasalfk,
    //             'ruangantujuanfk' => $item->ruangantujuanfk,
    //             'jenispermintaanfk' => $item->jenispermintaanfk,
    //             'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
    //             'norec' => $item->norec,
    //             'namaruanganasal' => $item->ruanganasal,
    //             'namaruangantujuan' => $item->ruangantujuan,
    //             'petugas' => $item->namalengkap,
    //             'keterangan' => $item->keteranganorder,
    //             'statusorder' => $status,
    //             'jmlitem' => $item->qtyjenisproduk,
    //             'details' =>  $item->details,
    //         );
    //     }

    // $data2 = DB::table('strukorder_t as sp')
    //         ->leftJoin('strukkirim_t as sk', 'sk.noorderfk','sp.norec')
    //         ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
    //         ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
    //         ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
    //         ->select(
    //             'sp.norec',
    //             'sp.noorder',
    //             'sk.norec as noreckirim',
    //             // DB::raw('null as noreckirim'),
    //             'sp.jenispermintaanfk',
    //             'pg.namalengkap',
    //             'ru.namaruangan as ruanganasal',
    //             'ru.id as ruanganasalfk',
    //             'ru2.namaruangan as ruangantujuan',
    //             'ru2.id as ruangantujuanfk',
    //             'sp.keteranganorder',
    //             'sp.statusorder',
    //             'sp.qtyjenisproduk',
    //             'sp.tglorder',
    //         )
    //         ->where('sp.kdprofile', $idProfile)
    //         ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
    //         ->where('sp.statusenabled', true)
    //         ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
    //         ->wherein('sp.objectruangantujuanfk', $strRuangan);

    //         if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
    //            $data2 =$data2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
    //         }
    //         if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
    //            $data2 =$data2->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
    //         }
    //         if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
    //            $data2 =$data2->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
    //         }

    //         $data2 =$data2->orderBy('sp.noorder');
    //         $data2 =$data2->get();
 
    //         $detail2 = DB::select(
    //             DB::raw(
    //             "
    //             SELECT  x.kdproduk,
    //                 x.kdsirs,
    //                 x.namaproduk,
    //                 --x.noorder,
    //                 x.satuanstandar,
    //                 x.strukorderfk,
    //                 x.objectprodukfk,
    //                 x.hasilkonversi,
    //                 --x.noorderfk,
    //                 SUM(x.qtykonfirm) as qtyprodukkirim,
    //                 sum(x.qtyorder) as qtyproduk
    //         from( 

    //             Select
    //                 pr.ID AS kdproduk,
    //                 pr.kdproduk AS kdsirs,
    //                 pr.namaproduk,
    //                 sp.noorder,
    //                 ss.satuanstandar,
    //                 spd.strukorderfk,
    //                 spd.objectprodukfk,
    //                 spd.hasilkonversi,
    //                 sp.norec AS noorderfk,
    //                 0 AS qtykonfirm,
    //                 spd.qtyproduk as qtyorder
                
    //             From orderpelayanan_t as spd
    //                 LEFT JOIN produk_m AS pr ON pr.id = spd.objectprodukfk
    //                 LEFT JOIN satuanstandar_m AS ss ON ss.id = spd.objectsatuanstandarfk
    //                 LEFT JOIN strukorder_t AS sp ON sp.norec = spd.strukorderfk 
    //                 where spd.kdprofile = 1 
    //                 AND sp.tglorder::DATE BETWEEN '$tglawal' AND '$tglakhir'
    //                 AND spd.statusenabled = true
    //                 AND sp.objectkelompoktransaksifk = $kelompokOrder  
    //                 AND sp.objectruangantujuanfk IN ($ruanganAll) 
                                
    //         Union all

    //         Select
    //         pr.ID AS kdproduk,
    //         pr.kdproduk AS kdsirs,
    //         pr.namaproduk,
    //         sk.nokirim AS noorder,
    //         ss.satuanstandar,
    //         sp.norec AS strukorderfk,
    //         kp.objectprodukfk,
    //         kp.hasilkonversi,
    //         sk.noorderfk,
    //         kp.qtyprodukkonfirmasi as qtykonfirm,
    //         0 as qtyorder 

    //         from kirimproduk_t as kp
    //                 LEFT JOIN strukkirim_t AS sk ON sk.norec = kp.nokirimfk
    //                 JOIN strukorder_t AS sp ON sp.norec = sk.noorderfk
    //                 LEFT JOIN produk_m AS pr ON pr.id = kp.objectprodukfk
    //                 LEFT JOIN satuanstandar_m AS ss ON ss.id = kp.objectsatuanstandarfk 

    //                 where kp.kdprofile = 1 
    //                 AND kp.tglpelayanan::DATE BETWEEN '$tglawal' AND '$tglakhir'
    //                 AND kp.statusenabled = true
    //                 AND sk.objectkelompoktransaksifk = $kelompokKirim 
    //                 AND kp.objectruanganpengirimfk IN ($ruanganAll) 

    //         ) as x 
    //         group by x.kdproduk,
    //                 x.kdsirs,
    //                 x.namaproduk,
    //                 x.satuanstandar,
    //                 x.strukorderfk,
    //                 x.objectprodukfk,
    //                 x.hasilkonversi"
    //             )
    //         );

    //     // $detail2 = DB::select(
    //     //     DB::raw("
    //     //         SELECT
    //     //             pr.id AS kdproduk,
    //     //             pr.kdproduk AS kdsirs,
    //     //             pr.namaproduk,
    //     //             sp.noorder,
    //     //             ss.satuanstandar,
    //     //             spd.strukorderfk,
    //     //             spd.objectprodukfk,
    //     //             spd.hasilkonversi,
    //     //             sp.norec AS noorderfk,
    //     //             spd.qtyprodukkonfirmasi AS qtyprodukkirim,
    //     //             spd.qtyproduk as qtyproduk
    //     //         FROM
    //     //             orderpelayanan_t AS spd
    //     //             LEFT JOIN produk_m AS pr ON pr.id = spd.objectprodukfk
    //     //             LEFT JOIN satuanstandar_m AS ss ON ss.id = spd.objectsatuanstandarfk
    //     //             LEFT JOIN strukorder_t AS sp ON sp.norec = spd.strukorderfk 
    //     //         WHERE
    //     //             sp.kdprofile =  $idProfile 
    //     //             AND sp.tglorder::DATE BETWEEN '$tglawal' AND '$tglakhir'
    //     //             AND sp.statusenabled = true
    //     //             AND sp.objectkelompoktransaksifk = 100 
    //     //             AND sp.objectruangantujuanfk IN ($ruanganAll)
    //     //     ")
    //     // );

    //     foreach ($data2 as $item) {
    //         $item->details = [];

    //         foreach ($detail2 as $item2) {
    //             if ($item->norec == $item2->strukorderfk) {
    //                 $item->details[] = $item2;
    //             }
    //         }

    //         if ($item->statusorder == 0) {
    //             $status = 'Belum Kirim';
    //         } else if ($item->statusorder == 1) {
    //             $status = 'Belum Diverifikasi';
    //         } else if ($item->statusorder == 2) {
    //             $status = 'Batal Kirim';
    //         } else if ($item->statusorder == 3) {
    //             $status = 'Terverifikasi';
    //         }
    //         $results[] = array(
    //             'status' => 'Kirim Order Barang',
    //             'tglorder' => $item->tglorder,
    //             'noorder' => $item->noorder,
    //             'noreckirim' => $item->noreckirim,
    //             'ruanganasalfk' => $item->ruanganasalfk,
    //             'ruangantujuanfk' => $item->ruangantujuanfk,
    //             'jenispermintaanfk' => $item->jenispermintaanfk,
    //             'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
    //             'norec' => $item->norec,
    //             'namaruanganasal' => $item->ruanganasal,
    //             'namaruangantujuan' => $item->ruangantujuan,
    //             'petugas' => $item->namalengkap,
    //             'keterangan' => $item->keteranganorder,
    //             'statusorder' => $status,
    //             'jmlitem' => $item->qtyjenisproduk,
    //             'details' =>  $item->details,
    //         );
    //     }

    //     $result = array(
    //         'daftar' => $results,
    //         'message' => '@epic',
    //     );
    //     return $this->respond($result);

    // }

    public function getDaftarOrderBarang(Request $request)
    {

        $idProfile = $this->kdProfile;
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.statusenabled', true)
            ->where('mlu.kdprofile', $idProfile)
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $data = DB::table('strukorder_t as sp')
            ->leftJoin('strukkirim_t as sk', 'sp.norec', 'sk.noorderfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.noorder',
                'sk.norec as noreckirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'ru.namaruangan as ruanganasal',
                'ru.id as ruanganasalfk',
                'ru2.namaruangan as ruangantujuan',
                'ru2.id as ruangantujuanfk',
                'ru2.objectdepartemenfk',
                'sp.keteranganorder',
                'sp.statusorder',
                'sp.qtyjenisproduk',
                'sp.tglorder',
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
            ->where('sp.statusenabled', true)
            ->where('sp.statusorder', '!=', 2)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
            ->wherein('sp.objectruangantujuanfk', $strRuangan);

        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data = $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data = $data->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
        }

        $data = $data->orderBy('sp.noorder', 'desc');
        $data = $data->get();

        $detail = DB::table('orderpelayanan_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukorder_t as sp', 'sp.norec', '=', 'spd.strukorderfk')
            ->select(
                DB::raw("pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,sp.noorder,sum(spd.qtyprodukkonfirmasi) as qtyprodukkonfirmasi,
                        ss.satuanstandar,spd.qtyproduk, spd.strukorderfk, spd.objectprodukfk,spd.hasilkonversi,
                        sp.norec as noorderfk,sum(spd.qtyproduk) as qtyproduk , 0 as qtyprodukkirim")
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
            ->wherein('sp.objectruangantujuanfk', $strRuangan);

        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail = $detail->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail = $detail->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $detail = $detail->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
        }
        // if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
        //     $data = $data->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        // }
        $detail = $detail->groupByRaw("pr.id,pr.kdproduk,pr.namaproduk,sp.noorder,ss.satuanstandar,spd.qtyproduk, spd.strukorderfk, sp.norec,spd.objectprodukfk,spd.hasilkonversi,qtyprodukkirim");
        $detail = $detail->orderBy('sp.noorder', 'desc');
        $detail = $detail->get();
        $results = array();
        foreach ($data as $item) {
            $item->details = [];
            $produkCari = false;
            foreach ($detail as $item2) {
                if ($item->norec == $item2->strukorderfk) {
                    
                    $item->details[] = $item2;
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        if($item2->objectprodukfk == $request['produkfk']){
                            $produkCari = true;
                        }
                    }
                }
            }

            if ($item->statusorder == 0) {
                $status = 'Belum Kirim';
            } else if ($item->statusorder == 1) {
                $status = 'Sudah Kirim';
            } else if ($item->statusorder == 2) {
                $status = 'Batal Kirim';
            } else if ($item->statusorder == 3) {
                $status = 'Terverifikasi';
            }
            if(isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined"){
                if($produkCari == true){
                    $results[] = array(
                        'status' => 'Terima Order Barang',
                        'tglorder' => $item->tglorder,
                        'noreckirim' => $item->noreckirim,
                        'noorder' => $item->noorder,
                        'ruanganasalfk' => $item->ruanganasalfk,
                        'ruangantujuanfk' => $item->ruangantujuanfk,
                        'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
                        'norec' => $item->norec,
                        'namaruanganasal' => $item->ruanganasal,
                        'namaruangantujuan' => $item->ruangantujuan,
                        'petugas' => $item->namalengkap,
                        'keterangan' => $item->keteranganorder,
                        'statusorder' => $status,
                        'jmlitem' => $item->qtyjenisproduk,
                        'details' =>  $item->details,
                    );
                }
            }else{
                $results[] = array(
                    'status' => 'Terima Order Barang',
                    'tglorder' => $item->tglorder,
                    'noorder' => $item->noorder,
                    'noreckirim' => $item->noreckirim,
                    'ruanganasalfk' => $item->ruanganasalfk,
                    'ruangantujuanfk' => $item->ruangantujuanfk,
                    'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
                    'norec' => $item->norec,
                    'namaruanganasal' => $item->ruanganasal,
                    'namaruangantujuan' => $item->ruangantujuan,
                    'petugas' => $item->namalengkap,
                    'keterangan' => $item->keteranganorder,
                    'statusorder' => $status,
                    'jmlitem' => $item->qtyjenisproduk,
                    'details' =>  $item->details,
                );
            }
        }

        $data2 = DB::table('strukorder_t as sp')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.noorder',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'ru.namaruangan as ruanganasal',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganorder',
                'sp.statusorder',
                'sp.qtyjenisproduk',
                DB::raw("CAST(sp.tglorder AS DATE)")
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
            ->where('sp.statusenabled', true)
            ->where('sp.statusorder', '!=', 2)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
            ->wherein('sp.objectruanganfk', $strRuangan);

        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data2 = $data2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $data2 = $data2->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data2 = $data2->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
        }

        $data2 = $data2->orderBy('sp.noorder', 'desc');
        $data2 = $data2->get();

        $detail2 = DB::table('orderpelayanan_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukorder_t as sp', 'sp.norec', '=', 'spd.strukorderfk')
            ->select(
                DB::raw("pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,sum(spd.qtyprodukkonfirmasi) as qtyprodukkonfirmasi,sp.noorder,
                        ss.satuanstandar,spd.qtyproduk, spd.strukorderfk, spd.objectprodukfk")
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
            ->wherein('sp.objectruanganfk', $strRuangan);

        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail2 = $detail2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['ruanganasalfk']) && $request['ruanganasalfk'] != "" && $request['ruanganasalfk'] != "undefined") {
            $detail2 = $detail2->where('sp.objectruanganfk', '=', $request['ruanganasalfk']);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $detail2 = $detail2->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
        }

        $detail2 = $detail2->orderBy('sp.noorder', 'desc');
        $detail2 = $detail2->groupByRaw("pr.id,pr.kdproduk,pr.namaproduk,sp.noorder, ss.satuanstandar,spd.qtyproduk, spd.strukorderfk, spd.objectprodukfk");
        $detail2 = $detail2->get();

        foreach ($data2 as $item) {
            $item->details = [];
            $produkCari = false;
            foreach ($detail2 as $item2) {
                if ($item->norec == $item2->strukorderfk) {
                    $item->details[] = $item2;
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined"){
                        if($item2->objectprodukfk == $request['produkfk']){
                            $produkCari = $item2->objectprodukfk;
                        }
                    }
                }
            }

            if ($item->statusorder == 0) {
                $status = 'Belum Kirim';
            } else if ($item->statusorder == 1) {
                $status = 'Sudah Kirim';
            } else if ($item->statusorder == 2) {
                $status = 'Batal Kirim';
            } else if ($item->statusorder == 3) {
                $status = 'Terverifikasi';
            }

            if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined"){
                if($produkCari == true){
                    $results[] = array(
                        'status' => 'Kirim Order Barang',
                        'tglorder' => $item->tglorder,
                        'noorder' => $item->noorder,
                        'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
                        'norec' => $item->norec,
                        'namaruanganasal' => $item->ruanganasal,
                        'namaruangantujuan' => $item->ruangantujuan,
                        'petugas' => $item->namalengkap,
                        'keterangan' => $item->keteranganorder,
                        'statusorder' => $status,
                        'jmlitem' => $item->qtyjenisproduk,
                        'details' =>  $item->details,
                        'produkCAri' => $produkCari
                    );
                }
            }else{
                $results[] = array(
                    'status' => 'Kirim Order Barang',
                    'tglorder' => $item->tglorder,
                    'noorder' => $item->noorder,
                    'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
                    'norec' => $item->norec,
                    'namaruanganasal' => $item->ruanganasal,
                    'namaruangantujuan' => $item->ruangantujuan,
                    'petugas' => $item->namalengkap,
                    'keterangan' => $item->keteranganorder,
                    'statusorder' => $status,
                    'jmlitem' => $item->qtyjenisproduk,
                    'details' =>  $item->details,
                    'produkCari' => "masuk sini"
                );
            }

        }

        $result = array(
            'daftar' => $results,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function getDetailOrderBarang(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dataAsalProduk = DB::table('asalproduk_m as ap')
            ->select('ap.id', 'ap.asalproduk')
            ->get();

        $dataStruk = DB::table('strukorder_t as sp')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglorder',
                'sp.noorder',
                'sp.jenispermintaanfk as jeniskirimfk',
                'pg.namalengkap',
                'ru.namaruangan as namaruanganasal',
                'ru2.namaruangan as namaruangantujuan',
                'sp.keteranganorder',
                'sp.statusorder',
                'sp.objectruangantujuanfk',
                'sp.objectruanganfk as objectruanganasalfk',
                DB::raw("case when sp.jenispermintaanfk = 1 then 'Amprahan'
                        when  sp.jenispermintaanfk = 2 then 'Transfer' end as jeniskirim ")
            )
            ->where('sp.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sp.norec', '=', $request['norec']);
        }

        $dataStruk = $dataStruk->where('sp.statusenabled', true);
        $dataStruk = $dataStruk->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'));
        $dataStruk = $dataStruk->orderBy('sp.noorder');
        $dataStruk = $dataStruk->first();

        $data = DB::table('strukorder_t as so')
            ->JOIN('orderpelayanan_t as op', 'op.noorderfk', '=', 'so.norec')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'op.objectsatuanstandarfk')
            ->select(
                'so.noorder',
                'op.qtyproduk',
                'op.objectprodukfk as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'op.hasilkonversi as nilaikonversi',
                'op.objectsatuanstandarfk',
                'ss.satuanstandar',
                'op.objectsatuanstandarfk as satuanviewfk',
                'ss.satuanstandar as ssview',
                'op.qtyproduk as jumlah',
                'op.pemakaian as pengeluaran',
                'op.avgpemakaian as avgpemakaian',
                'op.sisastok as sisastok',
                'op.minstok as minstok',
                'op.maxstok as maxstok',
                'ru.namaruangan as ruanganasal',
                'ru2.namaruangan as ruangantujuan',
                'so.objectruanganfk',
                'so.objectruangantujuanfk',
            )
            ->where('so.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('so.norec', '=', $request['norec']);
        }
        $data = $data->get();

        $details = [];
        $i = 0;
        $dataStok = DB::select(
            DB::raw("select sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                spd.harganetto2 as hargajual,spd.harganetto2 as harganetto,spd.hargadiscount,ap.asalproduk,
                sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                inner JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                where spd.kdprofile = $idProfile and spd.objectruanganfk =:ruanganid
                group by sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                        spd.harganetto2,spd.hargadiscount,ap.asalproduk,
                spd.objectruanganfk
                order By sk.tglstruk"),
            array(
                'ruanganid' => $dataStruk->objectruangantujuanfk
            )
        );
        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $jmlstok2 = 0;
        $hargasatuan = 0;
        $hargadiscount = 0;
        $total = 0;
        $aturanpakaifk = 0;
        foreach ($data as $item) {
            if ($item->jumlah > 0) {
                $i = $i + 1;

                foreach ($dataStok as $item2) {
                    if ($item2->objectprodukfk == $item->produkfk) {
                        if ($item2->qtyproduk >= $item->jumlah * $item->nilaikonversi) {
                            $nostrukterimafk = $item2->norec;
                            $asalprodukfk = $item2->objectasalprodukfk;
                            $jmlstok = $item2->qtyproduk;
                            break;
                        }
                    }
                }

               foreach ($dataAsalProduk as $item3) {
                    if ($asalprodukfk == $item3->id) {
                        $asalproduk = $item3->asalproduk;
                    }
                }
                $details[] = array(
                    'no' => $i,
                    'noregistrasifk' => '',
                    'tglregistrasi' => '',
                    'generik' => null,
                    'hargajual' => $hargajual,
                    'jenisobatfk' => '',
                    'kelasfk' => '',
                    'stock' => $jmlstok,
                    'stockpemesan' => $jmlstok2,
                    'harganetto' => $harganetto,
                    'nostrukterimafk' => $nostrukterimafk,
                    'ruanganfk' => $item->objectruanganfk,
                    'ruanganpengirim' => $item->ruanganasal,
                    'ruangantujuan' => $item->ruangantujuan,
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
                    'nilaikonversi' => $item->nilaikonversi, 
                    'satuanstandarfk' => $item->satuanviewfk, 
                    'satuanstandar' => $item->ssview, 
                    'satuanviewfk' => $item->satuanviewfk,
                    'satuanview' => $item->ssview,
                    'jmlstok' => $jmlstok,
                    'jumlah' => $item->jumlah,
                    'sisastok' => $item->sisastok,
                    'pengeluaran' => $item->pengeluaran,
                    'minstok' => $item->minstok,
                    'maxstok' => $item->maxstok,
                    'avgpemakaian' => $item->avgpemakaian,
                );
            }
        }

        $result = array(
            'head' => $dataStruk,
            'detail' => $details,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function saveOrderBarang(Request $request)
    {

        $idProfile = (int)$this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['strukorder']['norecorder'] == '') {
                $log = "Order Barang";
                if ($request['strukorder']['jenispermintaanfk'] != 1) {
                    $noOrder = $this->SEQUENCE(new StrukOrder, 'noorder', 14, 'OTRF-' . $this->getDateTime()->format('ym'), $idProfile);
                } else {
                    $noOrder = $this->SEQUENCE(new StrukOrder, 'noorder', 14, 'OAMP-' . $this->getDateTime()->format('ym'), $idProfile);
                }
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;
                $dataSO->isdelivered = 0;
                $dataSO->noorder = $noOrder;
            } else {
                $log = "Edit Order Barang";
                $dataSO = StrukOrder::where('norec', $request['strukorder']['norecorder'])->first();
                OrderPelayanan::where('noorderfk', $request['strukorder']['norecorder'])->delete();
            }
            $jenisPermintaan =DB::table('jenis_permintaan_m')->where('id' ,$request['strukorder']['jenispermintaanfk'])->first();
            $dataSO->jenispermintaanfk = $request['strukorder']['jenispermintaanfk'];
            $dataSO->objectkelompoktransaksifk = $this->kelompokTransaksi('ORDER BARANG RUANGAN');
            $dataSO->keteranganorder = $request['strukorder']['keteranganorder'];
            $dataSO->objectpegawaiorderfk = $this->getPegawaiId();;
            $dataSO->qtyjenisproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->qtyproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->objectruanganfk = $request['strukorder']['objectruanganfk'];
            $dataSO->objectruangantujuanfk = $request['strukorder']['objectruangantujuanfk'];
            $dataSO->tglorder = $request['strukorder']['tglorder'];
            $dataSO->statusorder = 0;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->save();
            $norecSo = $dataSO->norec;
            $order = $dataSO->noorder;

            foreach ($request['details'] as $item) {
                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->hasilkonversi = $item['nilaikonversi'];
                $dataOP->iscito = 0;
                $dataOP->noorderfk = $norecSo;
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->qtyproduk = $item['jumlah'];
                $dataOP->avgpemakaian = $item['avgpemakaian'] ?? 0;
                $dataOP->sisastok = $item['jmlstok'] ?? 0;
                $dataOP->minstok = $item['minstok'] ?? 0;
                $dataOP->maxstok = $item['maxstock'] ?? 0;
                $dataOP->pemakaian = $item['pengeluaran'] ?? 0;
                $dataOP->hargasatuan = isset($item['harganetto']) ? $item['harganetto'] : 0;
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectruanganfk = $request['strukorder']['objectruanganfk'];
                $dataOP->objectruangantujuanfk =  $request['strukorder']['objectruangantujuanfk'];
                $dataOP->objectsatuanstandarfk = $item['satuanviewfk'];
                $dataOP->strukorderfk = $norecSo;
                $dataOP->tglpelayanan = $request['strukorder']['tglorder'];
                $dataOP->save();
            }
            $this->LOGGING(
                $log,
                $r_SR['noresep'] ?? '',
                'strukorder_t',
                $log . '    dengan no order  ' .  $order . ' Jenis Transfer  ' .$jenisPermintaan->name
            );

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "data"  => $dataSO,
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

    public function hapusOrderBarang(Request $request)
    {
        $idProfile = (int)$this->kdProfile;
        DB::beginTransaction();
        try {
            $dataOB = StrukOrder::where('norec', $request['norec'])
                ->where('kdprofile', $idProfile)
                ->update(
                    [
                        'statusenabled' => false
                    ]
                );
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Berhasil Di Hapus";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataOB,
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

    public function batalKirimBarang(Request $request)
    {

        DB::beginTransaction();
        try {

            $strukKirim = StrukKirim::where('norec', $request['norec_sk'])->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();

                foreach ($request['details'] as $order) {

                    $saldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
                        ->where('objectruanganfk', $request['ruangantujuanfk'])
                        ->where('objectprodukfk', $order['objectprodukfk'])
                        ->where('statusenabled', true)
                        ->where('qtyproduk', '>=', 0)
                        ->sum('qtyproduk');
        
                        $detailKirimProduk = KirimProduk::where('nokirimfk',$strukKirim->norec)->where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                                ->where('objectprodukfk', $order['objectprodukfk'])->get();
                   
                    $jumlah = (float)$order['qtyprodukkirim'] * (float)$order['hasilkonversi'];
                    $saldoAkhirPengirim = (float)$saldoAwalPengirim + $jumlah;
                    foreach($detailKirimProduk as $item){
                        $ruangKirim = $request['ruangantujuanfk'];
                        StokProdukDetail::where('kdprofile', $this->kdProfile)
                            ->where('norec', $item->stokprodukdetailfk)
                            ->where('statusenabled', true)
                            ->where('objectruanganfk', $ruangKirim)
                            ->where('objectprodukfk', $item->objectprodukfk)
                            ->lockForUpdate()
                            ->increment('qtyproduk', (float)$item->qtyprodukkonfirmasi);
                    }
                 
                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$saldoAwalPengirim,
                        "qtyin" => (float)$order['qtyprodukkonfirmasi'] * (float)$order['hasilkonversi'],
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhirPengirim,
                        "keterangan" => 'Batal Kirim Barang Order, dari Ruangan ' .  $request['namaruangantujuan'] . ' ke Ruangan ' .  $request['namaruanganasal'] . ' berupa produk ' . $order['namaproduk'] . '. No Order: ' .  $strukKirim->nokirim,
                        "produkfk" => $order['objectprodukfk'],
                        "ruanganfk" =>  $request['ruangantujuanfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => null,
                        "norectransaksi" => $strukKirim->norec,
                        "tabletransaksi" => 'strukkirim_t',
                        "stokprodukdetailfk" => null,
                        "flagfk" => null,
                    ));
                }


            KirimProduk::where('nokirimfk', $strukKirim->norec)->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->update(['qtyprodukkonfirmasi' => null]);
            StrukOrder::where('norec', $request['norec_so'])->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->update(['statusorder' => 2]);
        
            DB::commit();
            $result = [
                "message" => "Batal Kirim Order Berhasil",
                "status" => 200,
                // "data" => $strukOrder
            ];


        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "message" => "Batal Kirim Order Gagal",
                "status" => 400,
                // "data" => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
}
