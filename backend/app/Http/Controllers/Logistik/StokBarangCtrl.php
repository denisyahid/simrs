<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokProduk;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Standar\PasswordAutorisasi;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StokProdukDetailOpname;
use App\Models\Transaksi\StrukClosing;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class StokBarangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getStokProduck(Request $request)
    {
        // $kdProfile = $this->getDataKdProfile($request);

        $data = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->leftjoin('detailjenisproduk_m as djp', 'djp.id', 'prd.objectdetailjenisprodukfk')
            ->leftjoin('jenisproduk_m as jp', 'jp.id', 'djp.objectjenisprodukfk')
            ->leftjoin('kelompokproduk_m as kp', 'kp.id', 'jp.objectkelompokprodukfk')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->select(
                'apd.objectruanganfk',
                'ru.namaruangan',
                DB::raw("CAST(pp.tglpelayanan AS DATE)"),
                'pp.produkfk',
                'prd.namaproduk',
                'pp.jumlah',
                'kp.id as idkelompokproduk',
                'kp.kelompokproduk',
                'jp.id as idjenisproduk',
                DB::raw("CAST(pp.tglkadaluarsa AS DATE)"),
                'jp.jenisproduk',
                'pp.tglregistrasi',
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.isobat', true);

        if (isset($request['dari']) && $request['dari'] != "" && $request['dari'] != "undefined") {
            $data = $data->where('pp.tglpelayanan', '>=', $request['dari']);
        }
        if (isset($request['sampai']) && $request['sampai'] != "" && $request['sampai'] != "undefined") {
            $data = $data->where('pp.tglpelayanan', '<=', $request['sampai']);
        }

        if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('apd.objectruanganfk', $request['idruangan']);
        }
        if (isset($request['idkelproduk']) && $request['idkelproduk'] != "" && $request['idkelproduk'] != "undefined") {
            $data = $data->where('kp.id', $request['idkelproduk']);
        }

        $data = $data->get();

        $data10 = [];
        $jml = 0;
        $sama = false;
        // $isExpired = false;
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->produkfk == $data10[$i]['produkfk']) {
                    $sama = true;
                    $jml = (float)$hideung['total'] + 1 * $item->jumlah;
                    $data10[$i]['total'] = $jml;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $data10[] = array(
                    'objectruanganfk' => $item->objectruanganfk,
                    'namaruangan' => $item->namaruangan,
                    'tglpelayanan' => $item->tglpelayanan,
                    'produkfk' => $item->produkfk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'namaproduk' => $item->namaproduk,
                    'idkelompokproduk' => $item->idkelompokproduk,
                    'idjenisproduk' => $item->idjenisproduk,
                    'total' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['total'];
            }
            array_multisort($count, SORT_DESC, $data10);
        }
        $result = array(
            'data' => $data10,
            'message' => 'ramdanegie',

        );
        return $this->respond($result);
    }

    public function itemDropdown()
    {
        $res['kelompokproduk'] = KelompokProduk::mine()->get();
        $res['namaruangan'] = Ruangan::mine()->get();

        return $this->respond($res);
    }

    public function getProduk(Request $request)
    {
        $data = Produk::mine()->search($request['namaproduk'])->limit($request['limit'])->get();

        return $this->respond($data);
    }

    public function getDaftarStokOpname(Request $request)
    {
        $idProfile = $this->kdProfile;
        $detailjenisprodukfk = '';
        $jenisprodukfk = '';
        $produkfk = '';
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = '';

        if (isset($request['jeniskprodukid']) &&  $request['jeniskprodukid'] != '') {
            $jenisprodukfk = "and djp.objectjenisprodukfk in (" . $request['jeniskprodukid'] . ")";
        }
        if (isset($request['produkfk']) &&  $request['produkfk'] != '') {
            $produkfk = "and pr.id = " . $request['produkfk'];
        }
        if (isset($request['detailjenisprodukfk']) &&  $request['detailjenisprodukfk'] != '') {
            $detailjenisprodukfk = "and djp.id in (" . $request['detailjenisprodukfk'] . ")";
        }
        if (isset($request['ruanganfk']) &&  $request['ruanganfk'] != '') {
            $ruanganId = "and ru.id =" . $request['ruanganfk'];
        }

        $data = DB::select(DB::raw("
				SELECT
					x.kdproduk,
					x.tglclosing,
					x.namaproduk,
					x.satuanstandar,
					x.namaruangan,
                    x.nobatch,
					x.tglkadaluarsa,
					SUM (x.qtyprodukreal) AS qtyprodukreal,
					SUM (x.harganetto1) AS harganetto1,
					SUM (x.total) AS total
				FROM
					(
						SELECT DISTINCT
							pr.id AS kdproduk,
							sp.tglstruk,
							sc.tglclosing,
							pr.namaproduk,
							ss.satuanstandar,
                            spd.nobatch,
							spd.qtyprodukreal,
							spd.harganetto1,
							spd.qtyprodukreal * spd.harganetto1 AS total,
							ru.namaruangan,
							spdt.tglkadaluarsa
						FROM
							strukclosing_t sc
						LEFT JOIN stokprodukdetailopname_t spd ON spd.noclosingfk = sc.norec
						LEFT JOIN strukpelayanan_t sp ON sp.norec = spd.nostrukterimafk
						LEFT JOIN strukpelayanandetail_t spdt ON spdt.noclosingfk = sc.norec
						LEFT JOIN produk_m pr ON pr.id = spd.objectprodukfk
						LEFT JOIN detailjenisproduk_m djp ON djp.id = pr.objectdetailjenisprodukfk
						LEFT JOIN satuanstandar_m ss ON ss.id = pr.objectsatuanstandarfk
						LEFT JOIN ruangan_m ru ON ru.id = spd.objectruanganfk
						WHERE sc.kdprofile =$idProfile and CAST(sc.tglclosing as DATE) BETWEEN '$tglAwal' AND '$tglAkhir'
                        $ruanganId $produkfk $detailjenisprodukfk $jenisprodukfk
					) AS x
				GROUP BY
					x.kdproduk,
					x.tglclosing,
					x.namaproduk,
					x.satuanstandar,
					x.namaruangan,
                    x.nobatch,
					x.tglkadaluarsa
			"));

        $samateu = false;
        $arrayFix = [];
        foreach ($data as $item) {
            $samateu = false;
            foreach ($arrayFix as $itemsss) {
                if ($item->kdproduk == $itemsss['kdproduk']) {
                    $samateu = false;
                    if ($item->tglclosing > date($itemsss['tglclosing'])) {
                        $itemsss['qtyprodukreal'] = (float) $item->qtyprodukreal;
                        $itemsss['tglclosing'] = $item->tglclosing;
                        break;
                    }
                }
            }
            if ($samateu == false) {
                $arrayFix[] = array(
                    'kdproduk' => $item->kdproduk,
                    'tglclosing' => $item->tglclosing,
                    'namaproduk' => $item->namaproduk,
                    'satuanstandar' => $item->satuanstandar,
                    'namaruangan' => $item->namaruangan,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'nobatch' => $item->nobatch,
                    'qtyprodukreal' => (float)$item->qtyprodukreal,
                    'harga' => (float)$item->harganetto1,
                    'total' => (float) $item->total,
                );
            }
        }

        $result = array(
            'data' => $arrayFix,
            'message' => 'er@epic',
        );
        return $this->respond($result);
    }

    public function getStokRuanganSO(Request $request)
    {
        $idProfile = $this->kdProfile;
        $data = DB::table('produk_m as pr')
            ->leftJoin('stokprodukdetail_t as spd', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->leftJoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select(DB::raw('sum(spd.qtyproduk) as qtyproduk, pr.id as kodeproduk,UPPER(pr.namaproduk) as namaproduk,ss.satuanstandar'))
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', true)
            ->where('spd.kdprofile', $idProfile)
            ->where('spd.statusenabled', true)
            ->where('spd.objectruanganfk', $request['ruanganfk'])
            ->whereNotNull('spd.objectruanganfk')
            ->groupBy(
                'pr.id',
                'pr.namaproduk',
                'ss.satuanstandar',
            )
            ->orderBy('pr.namaproduk');

        if (isset($request['kelompokprodukid']) && $request['kelompokprodukid'] != "" && $request['kelompokprodukid'] != "undefined") {
            $data = $data->where('jp.objectkelompokprodukfk', '=', $request['kelompokprodukid']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('pr.id', $request['produkfk']);
        }
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function getStokRuanganSOByEd(Request $request)
    {
        $idProfile = $this->kdProfile;
        $data = DB::table('produk_m as pr')
            ->leftJoin('stokprodukdetail_t as spd', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->leftjoin('detailgolonganproduk_m as dgp', 'dgp.id', '=', 'pr.objectdetailgolonganprodukfk')
            ->leftJoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select(
                DB::raw('SUM(spd.qtyproduk) as qtyproduk'),
                DB::raw('DATE(spd.tglkadaluarsa) as tglkadaluarsa'), 
                'pr.id as kodeproduk',
                'pr.kdproduk',
                DB::raw('UPPER(pr.namaproduk) as namaproduk'),
                'ss.satuanstandar',
                'spd.harganetto1'
            )
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', true)
            ->where('spd.kdprofile', $idProfile)
            ->where('spd.statusenabled', true)
            ->where('spd.objectasalprodukfk', '<>', 4)
            ->where('spd.objectruanganfk', $request['ruanganfk'])
            ->whereNotNull('spd.objectruanganfk')
            ->groupBy(
                'pr.id',
                'pr.kdproduk',
                'pr.namaproduk',
                'spd.harganetto1',
                'spd.tglkadaluarsa',
                'ss.satuanstandar',
                'spd.tglkadaluarsa'
            )
            ->orderBy('pr.namaproduk');

        if (isset($request['kelompokprodukid']) && $request['kelompokprodukid'] != "" && $request['kelompokprodukid'] != "undefined") {
            $data = $data->where('jp.objectkelompokprodukfk', '=', $request['kelompokprodukid']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('pr.id', $request['produkfk']);
        }
        if (isset($request['jenis']) && $request['jenis'] != "" && $request['jenis'] != "undefined") {
            $data = $data->where('djp.id', $request['jenis']);
        }

        if (isset($request['golongan']) && $request['golongan'] != "" && $request['golongan'] != "undefined") {
            $data = $data->where('dgp.id', $request['golongan']);
        }

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }


    public function saveStockOpname(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        ini_set('max_execution_time', 300); //6 minutes
        $dataReq = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataReq['userData']['id'])
            ->first();
        $datas = array();

        DB::beginTransaction();
        try {

            $getSPD = StokProdukDetail::where('objectprodukfk', $request['stokProduk'][0]['kodeproduk'])
                ->where('kdprofile', $idProfile)
                ->where('statusenabled', true)
                ->where('objectruanganfk', $request['ruanganId'])
                ->orderby('tglkadaluarsa')
                ->first();

            $data = StokProdukDetailOpname::where('objectasalprodukfk', $getSPD->objectasalprodukfk)
                ->where('objectprodukfk', $getSPD->objectprodukfk)->where('qtyprodukreal', $request['stokProduk'][0]['qtyReal'])
                ->where('qtyproduksystem', $request['stokProduk'][0]['qtyproduk'])->where('qtyprodukinext', $request['stokProduk'][0]['selisih'])
                ->where('objectruanganfk', $request['ruanganId'])->where('nostrukterimafk', $getSPD->nostrukterimafk)
                ->where(DB::raw('created_at::date'), date('Y-m-d'))
                ->first();

            if ($data) {
                return $this->respond($data, 200, $request['stokProduk'][0]['namaproduk'] . ' Sudah Ada');
            }

            $noClosing = $this->SEQUENCE(new StrukClosing(), 'noclosing', 10, 'PN/' . $this->getDateTime()->format('ym'), $this->kdProfile);
            $dataSC = new StrukClosing();
            $dataSC->norec = $dataSC->generateNewId();
            $dataSC->kdprofile = $idProfile;
            $dataSC->statusenabled = true;
            $dataSC->objectpegawaidiclosefk = $dataPegawai->objectpegawaifk;
            $dataSC->objectkelompoktransaksifk = 12;
            $dataSC->keteranganlainnya = 'Stock Opname ' . $request['namaRuangan'];
            $dataSC->noclosing = $noClosing;
            $dataSC->objectruangandiclosefk = $request['ruanganId'];
            $dataSC->objectruanganfk = $request['ruanganId'];
            $dataSC->tglclosing = $request['tglClosing'];
            $dataSC->save();

            $norecSC = $dataSC->norec;
            foreach ($request['stokProduk'] as $item) {
                $dataSPDK = StokProdukDetail::where('objectprodukfk', $item['kodeproduk'])
                    ->where('kdprofile', $idProfile)
                    ->where('statusenabled', true)
                    ->where('objectruanganfk', $request['ruanganId'])
                    ->orderby('tglkadaluarsa')
                    ->first();

                if ($dataSPDK == null) {
                    $dataSPDK2 = StokProdukDetail::where('objectprodukfk', $item['kodeproduk'])
                        ->where('kdprofile', $idProfile)
                        ->where('statusenabled', true)
                        ->orderby('tglkadaluarsa')
                        ->first();

                    if (!empty($dataSPDK2) || $dataSPDK2 != null) {

                        $dataSPD = new StokProdukDetailOpname();
                        $dataSPD->norec = $dataSPD->generateNewId();
                        $dataSPD->kdprofile = $idProfile;
                        $dataSPD->statusenabled = true;
                        $dataSPD->objectasalprodukfk = $dataSPDK2->objectasalprodukfk;
                        $dataSPD->hargadiscount = 0;
                        $dataSPD->harganetto1 = $item['harganetto1'];
                        $dataSPD->harganetto2 = $dataSPDK2->harganetto2;
                        $dataSPD->persendiscount = 0;
                        $dataSPD->objectprodukfk = $item['kodeproduk'];
                        // $dataSPD->harganetto1 = $item['harganetto1'];
                        $dataSPD->qtyprodukreal = $item['qtyReal'];
                        $dataSPD->qtyproduksystem = $item['qtyproduk'];
                        $dataSPD->qtyprodukinext = $item['selisih'];
                        $dataSPD->objectruanganfk = $request['ruanganId'];
                        $dataSPD->noclosingfk = $norecSC;
                        $dataSPD->nostrukterimafk = $dataSPDK2->nostrukterimafk;
                        $dataSPD->save();

                        $dataNewSPD = new StokProdukDetail;
                        $dataNewSPD->norec = $dataNewSPD->generateNewId();
                        $dataNewSPD->kdprofile = $idProfile;
                        $dataNewSPD->statusenabled = true;
                        $dataNewSPD->objectasalprodukfk = $dataSPDK2->objectasalprodukfk;
                        $dataNewSPD->hargadiscount = $dataSPDK2->hargadiscount;
                        $dataNewSPD->harganetto1 = $item['harganetto1'];
                        $dataNewSPD->harganetto2 = $dataSPDK2->harganetto2;
                        $dataNewSPD->persendiscount = 0;
                        $dataNewSPD->objectprodukfk = $dataSPDK2->objectprodukfk;
                        $dataNewSPD->qtyproduk = (float)$item['selisih'];
                        $dataNewSPD->qtyprodukonhand = 0;
                        $dataNewSPD->qtyprodukoutext = 0;
                        $dataNewSPD->qtyprodukoutint = 0;
                        $dataNewSPD->objectruanganfk = $request['ruanganId'];
                        $dataNewSPD->nostrukterimafk = $dataSPDK2->nostrukterimafk;
                        $dataNewSPD->noverifikasifk = $dataSPDK2->noverifikasifk;
                        $dataNewSPD->nobatch = $dataSPDK2->nobatch;
                        $dataNewSPD->tglkadaluarsa = $dataSPDK2->tglkadaluarsa;
                        $dataNewSPD->tglpelayanan = $dataSPDK2->tglpelayanan;
                        $dataNewSPD->tglproduksi = $dataSPDK2->tglproduksi;
                        $dataNewSPD->save();

                        $dataSTOKDETAIL[] = DB::select(
                            DB::raw("select qtyproduk as qty,norec from stokprodukdetail_t
                            where kdprofile = $idProfile and objectruanganfk=:ruanganfk and statusenabled = true and objectprodukfk=:produkfk"),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                            )
                        );

                        //## KartuStok
                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$item['qtyproduk'],
                            "qtyin" =>   (float)$item['selisih'] > 0 ? (float)$item['selisih'] : 0,
                            "qtyout" => (float)$item['selisih'] < 0 ? (float)$item['selisih'] : 0,
                            "saldoakhir" => $item['qtyReal'],
                            "keterangan" => 'Stock Opname Ruangan ' . $request['namaRuangan'] . ' Pada produk ' . $request['stokProduk'][0]['namaproduk'],
                            "produkfk" =>  $item['kodeproduk'],
                            "ruanganfk" => $request['ruanganId'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => $dataSPDK2->nostrukterimafk,
                            "norectransaksi" =>  $dataSPDK2->norec,
                            "stokprodukdetailfk" => $dataSPDK2->norec,
                            "tabletransaksi" => 'stokprodukdetail_t',
                            "flagfk" => 5,
                        ));
                    } else {
                        $dataBarang = DB::select(
                            DB::raw("select * from produk_m where kdprofile = $idProfile and id=:produkfk"),
                            array(
                                'produkfk' => $item['produkfk'],
                            )
                        );
                        foreach ($dataBarang as $poek) {
                            $datas[] = array(
                                "kdproduk" => $item['produkfk'],
                                "namaproduk" => $poek->namaproduk,
                                "stokSistem" => $item['stokSistem'],
                                "stokReal" => $item['stokReal'],
                                "selisih" => $item['selisih'],
                            );
                        }
                    }
                } else {
                    $dataSPD = new StokProdukDetailOpname();
                    $dataSPD->norec = $dataSPD->generateNewId();
                    $dataSPD->kdprofile = $idProfile;
                    $dataSPD->statusenabled = true;
                    $dataSPD->objectasalprodukfk = $dataSPDK->objectasalprodukfk;
                    $dataSPD->hargadiscount = 0;
                    $dataSPD->harganetto1 = $item['harganetto1'];
                    $dataSPD->harganetto2 = $dataSPDK->harganetto2;
                    $dataSPD->persendiscount = 0;
                    $dataSPD->objectprodukfk = $item['kodeproduk'];
                    $dataSPD->qtyprodukreal = $item['qtyReal'];
                    $dataSPD->qtyproduksystem = $item['qtyproduk'];
                    $dataSPD->qtyprodukinext = $item['selisih'];
                    $dataSPD->objectruanganfk = $request['ruanganId'];
                    $dataSPD->noclosingfk = $norecSC;
                    $dataSPD->nostrukterimafk = $dataSPDK->nostrukterimafk;
                    $dataSPD->save();

                    //STOK MINUS//
                    $dataStokMinus = DB::select(
                        DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
                        where kdprofile = $this->kdProfile and objectruanganfk=:ruanganfk and statusenabled = true and objectprodukfk=:produkfk and qtyproduk < 0 "),
                        array(
                            'ruanganfk' => $request['ruanganId'],
                            'produkfk' => $item['kodeproduk'],
                        )
                    );

                    StokProdukDetail::where('objectruanganfk', $request['ruanganId'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('objectprodukfk', $item['kodeproduk'])
                        ->where('qtyproduk', '<', 0)
                        ->where('statusenabled', true)
                        ->update([
                            'qtyproduk' => 0
                        ]);

                    if (count($dataStokMinus) != 0) {
                        foreach ($dataStokMinus as $items) {
                            $stokMinus = (float)$items->qty;
                        }
                    }
                    $saldoAwal = 0;
                    $jumlah = (float)$item['selisih'] + (float)$stokMinus;

                    if ($jumlah > 0) {
                        $dataStok = DB::select(
                            DB::raw("select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                        where kdprofile = $idProfile and objectruanganfk=:ruanganfk and statusenabled = true and objectprodukfk=:produkfk and qtyproduk>0 limit 1"),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                            )
                        );

                        if (count($dataStok) == 0) {
                            $dataStok = DB::select(
                                DB::raw("select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                                  where kdprofile = $idProfile and objectruanganfk=:ruanganfk and statusenabled = true and objectprodukfk=:produkfk limit 1"),
                                array(
                                    'ruanganfk' => $request['ruanganId'],
                                    'produkfk' => $item['kodeproduk'],
                                )
                            );
                        }
                        foreach ($dataStok as $items) {
                            StokProdukDetail::where('norec', $items->norec)
                                ->where('kdprofile', $idProfile)
                                ->where('statusenabled', true)
                                ->update(
                                    [
                                        'qtyproduk' => (float)$items->qty + (float)$jumlah
                                    ]
                                );
                        }

                        $dataSTOKDETAIL = DB::select(
                            DB::raw("select qtyproduk as qty,norec from stokprodukdetail_t
                            where kdprofile = $idProfile and statusenabled = true and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk "),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                            )
                        );
                    } else {
                        $jumlah = $jumlah * (-1);
                        $dataStok = DB::select(
                            DB::raw("select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                        where kdprofile = $idProfile and statusenabled = true and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                            )
                        );
                        foreach ($dataStok as $items) {
                            if ((float)$items->qty < $jumlah) {
                                $jumlah = $jumlah - (float)$items->qty;
                                StokProdukDetail::where('norec', $items->norec)
                                    ->where('kdprofile', $idProfile)
                                    ->where('statusenabled', true)
                                    ->update(
                                        [
                                            'qtyproduk' => 0
                                        ]
                                    );
                            } else {
                                $saldoakhir = (float)$items->qty - $jumlah;
                                $jumlah = 0;
                                StokProdukDetail::where('norec', $items->norec)
                                    ->where('kdprofile', $idProfile)
                                    ->where('statusenabled', true)
                                    ->update(
                                        [
                                            'qtyproduk' => (float)$saldoakhir
                                        ]
                                    );
                            }
                        }

                        $dataSTOKDETAIL[] = DB::select(
                            DB::raw("select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                        where kdprofile = $idProfile and statusenabled = true and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                            )
                        );
                    }

                    $dataSaldoAwalK = DB::select(
                        DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
                                 where kdprofile = $idProfile and statusenabled = true and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                        array(
                            'ruanganfk' => $request['ruanganId'],
                            'produkfk' => $item['kodeproduk'],
                        )
                    );
                    $saldoAwal = 0;
                    foreach ($dataSaldoAwalK as $items) {
                        $saldoAwal = (float)$items->qty - $item['selisih'];
                    }

                    $statusssss = 0;
                    $flagfk = 0;
                    $saldoin = 0;
                    $saldoOut = 0;
                    if ($item['selisih'] < 0) {
                        $statusssss = 0;
                        $selisih = (float)$item['selisih'] * (-1);
                        $saldoOut = $selisih;
                        $flagfk = 5;
                    } else {
                        $statusssss = 1;
                        $selisih = (float)$item['selisih'];
                        $saldoin = $selisih;
                        $saldoOut = 0;
                        $flagfk = 4;
                    }
                    //## KartuStok
                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$saldoAwal,
                        "qtyin" =>  $saldoin,
                        "qtyout" => $saldoOut,
                        "saldoakhir" => $item['qtyReal'],
                        "keterangan" => 'Stock Opname Ruangan ' . $request['namaRuangan'] . ' Pada produk ' . $request['stokProduk'][0]['namaproduk'],
                        "produkfk" =>  $item['kodeproduk'],
                        "ruanganfk" => $request['ruanganId'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $dataSPDK->nostrukterimafk,
                        "norectransaksi" =>  $dataSPDK->norec,
                        "stokprodukdetailfk" => $dataSPDK->norec,
                        "tabletransaksi" => 'stokprodukdetail_t',
                        "flagfk" => $flagfk,
                    ));
                }
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $request['lengthProgress'] == 100 ? 'Berhasil Simpan Stok Opname' : 'hide',
                "by" => '@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => $request['stokProduk'][0]['namaproduk'] . ' Simpan Gagal',
                "data"  => $e->getMessage(),
                "by" => '@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getDaftarSO(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        $detailjenisprodukfk = '';
        $jenisprodukfk = '';
        $namaproduk = '';
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = '';

        if (isset($request['jeniskprodukid']) &&  $request['jeniskprodukid'] != '') {
            $jenisprodukfk = "and djp.objectjenisprodukfk in (" . $request['jeniskprodukid'] . ")";
        }
        if (isset($request['namaproduk']) &&  $request['namaproduk'] != '') {
            $namaproduk = "and pr.namaproduk  ILIKE '%" . $request['namaproduk'] . "%'";
        }
        if (isset($request['detailjenisprodukfk']) &&  $request['detailjenisprodukfk'] != '') {
            $detailjenisprodukfk = "and djp.id in (" . $request['detailjenisprodukfk'] . ")";
        }
        if (isset($request['ruanganfk']) &&  $request['ruanganfk'] != '') {
            $ruanganId = "and ru.id =" . $request['ruanganfk'];
        }

        $data = DB::select(DB::raw("
				SELECT
					x.kdproduk,
					x.tglclosing,
					x.namaproduk,
					x.satuanstandar,
					x.namaruangan,
					x.tglkadaluarsa,
					x.keteranganlainnya,
					x.pegawaiso,
					SUM (x.qtyprodukreal) AS qtyprodukreal,
					SUM (x.harganetto1) AS harganetto1,
					SUM (x.total) AS total
				FROM
					(
						SELECT DISTINCT
							pr.id AS kdproduk,
							sp.tglstruk,
							sc.tglclosing,
							pr.namaproduk,
							ss.satuanstandar,
							spd.qtyprodukreal,
							spd.harganetto1,
							spd.keteranganlainnya,
							spd.qtyprodukreal * spd.harganetto1 AS total,
							ru.namaruangan,
							pg.namalengkap as pegawaiso,
							spdt.tglkadaluarsa
						FROM
							strukclosing_t sc
						LEFT JOIN stokprodukdetailopname_t spd ON spd.noclosingfk = sc.norec
						LEFT JOIN strukpelayanan_t sp ON sp.norec = spd.nostrukterimafk
						LEFT JOIN strukpelayanandetail_t spdt ON spdt.noclosingfk = sc.norec
						LEFT JOIN produk_m pr ON pr.id = spd.objectprodukfk
						LEFT JOIN detailjenisproduk_m djp ON djp.id = pr.objectdetailjenisprodukfk
						LEFT JOIN satuanstandar_m ss ON ss.id = pr.objectsatuanstandarfk
						LEFT JOIN ruangan_m ru ON ru.id = spd.objectruanganfk
						LEFT JOIN pegawai_m pg ON pg.id = sc.objectpegawaidiclosefk
						WHERE sc.kdprofile =$idProfile and
							sc.tglclosing BETWEEN '$tglAwal'
						AND '$tglAkhir'
						$ruanganId
					$namaproduk
					$detailjenisprodukfk
					$jenisprodukfk


					) AS x
				GROUP BY
					x.kdproduk,
					x.tglclosing,
					x.namaproduk,
					x.satuanstandar,
					x.namaruangan,
					x.keteranganlainnya,
					x.pegawaiso,
					x.tglkadaluarsa
			"));
        $samateu = false;
        $arrayFix = [];
        foreach ($data as $item) {
            $samateu = false;
            foreach ($arrayFix as $itemsss) {
                if ($item->kdproduk == $itemsss['kdproduk']) {
                    $samateu = true;
                    if ($item->tglclosing > date($itemsss['tglclosing'])) {
                        $itemsss['qtyprodukreal'] = (float) $item->qtyprodukreal;
                        $itemsss['tglclosing'] = $item->tglclosing;
                        break;
                    }
                }
            }
            if ($samateu == false) {
                $arrayFix[] = array(
                    'kdproduk' => $item->kdproduk,
                    'tglclosing' => $item->tglclosing,
                    'namaproduk' => $item->namaproduk,
                    'satuanstandar' => $item->satuanstandar,
                    'namaruangan' => $item->namaruangan,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'qtyprodukreal' => (float)$item->qtyprodukreal,
                    'harga' => (float)$item->harganetto1,
                    'total' => (float) $item->total,
                    'keteranganlainnya' => $item->keteranganlainnya,
                    'pegawaiso' => $item->pegawaiso,

                );
            }
        }
        $result = array(
            'data' => $arrayFix,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function passwordCheck(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $message = "";
        $cekPassword =  PasswordAutorisasi::where('namaautorisasi', $request['namaautorisasi'])
            ->where('passcode', '=', $this->encryptSHA1($request->input('passcode')))
            ->where('kdprofile', $kdProfile)
            ->first();

        if ($cekPassword == null || $cekPassword == "") {
            $message = "Password Salah";
        }
        $result = array(
            'message' => $message,
            'create' => '@epic',
        );
        return $this->respond($result);
    }

    public function saveStockOpnameByEd(Request $request)
    {
        // return $this->respond($request);
        $idProfile = (int) $this->kdProfile;
        ini_set('max_execution_time', 600); //6 minutes
        $dataReq = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataReq['userData']['id'])
            ->first();
        $datas = array();

        DB::beginTransaction();
        try {

            $getSPD = StokProdukDetail::where('objectprodukfk', $request['stokProduk'][0]['kodeproduk'])
                ->where('kdprofile', $idProfile)
                ->where('statusenabled', true)
                ->where('objectruanganfk', $request['ruanganId'])
                ->orderby('tglkadaluarsa')
                ->first();

            $selisihReal = isset($request['stokProduk'][0]['selisih']) ? (float)$request['stokProduk'][0]['selisih'] : 0;

            $data = StokProdukDetailOpname::where('objectasalprodukfk', $getSPD->objectasalprodukfk)
                ->where('objectprodukfk', $getSPD->objectprodukfk)->where('qtyprodukreal', $request['stokProduk'][0]['qtyReal'])
                ->where('qtyproduksystem', $request['stokProduk'][0]['qtyproduk'])->where('qtyprodukinext', $selisihReal)
                ->where('objectruanganfk', $request['ruanganId'])->where('nostrukterimafk', $getSPD->nostrukterimafk)
                ->where(DB::raw('created_at::date'), date('Y-m-d'))
                ->first();

            if ($data) {
                $result = [
                    'data' => $data,
                    'status' => 200,
                    'message' => $request['lengthProgress'] == 100 ? 'Berhasil Simpan Stok Opname' : 'hide',
                ];
                return $this->respond($result, $result['status'], $result['message']);
            }


            $noClosing = $this->SEQUENCE(new StrukClosing(), 'noclosing', 10, 'PN/' . $this->getDateTime()->format('ym'), $this->kdProfile);
            $dataSC = new StrukClosing();
            $dataSC->norec = $dataSC->generateNewId();
            $dataSC->kdprofile = $idProfile;
            $dataSC->statusenabled = true;
            $dataSC->objectpegawaidiclosefk = $dataPegawai->objectpegawaifk;
            $dataSC->objectkelompoktransaksifk = 12;
            $dataSC->keteranganlainnya = 'Stock Opname ' . $request['namaRuangan'];
            $dataSC->noclosing = $noClosing;
            $dataSC->objectruangandiclosefk = $request['ruanganId'];
            $dataSC->objectruanganfk = $request['ruanganId'];
            $dataSC->tglclosing = $request['tglClosing'];
            $dataSC->save();
            $norecSC = $dataSC->norec;

            foreach ($request['stokProduk'] as $item) {
                $tglEd = "";
                if (isset($item['tglkadaluarsa'])) {
                    $tglEd = " tglkadaluarsa = '" . $item['tglkadaluarsa'] . "'";
                } else {
                    $tglEd = " tglkadaluarsa IS NULL";
                }

                $dataSPDK = StokProdukDetail::where('objectprodukfk', $item['kodeproduk'])
                    ->where('kdprofile', $idProfile)
                    ->where('objectruanganfk', $request['ruanganId'])
                    ->where('statusenabled', true)
                    ->whereRaw($tglEd)
                    ->orderby('tglkadaluarsa')
                    ->first();

                if ($dataSPDK == null) {
                    $dataSPDK2 = StokProdukDetail::where('objectprodukfk', $item['kodeproduk'])
                        ->where('kdprofile', $idProfile)
                        ->whereRaw($tglEd)
                        ->where('statusenabled', true)
                        ->orderby('tglkadaluarsa')
                        ->first();
                    $d[0] = $dataSPDK2;
                    $d[1] = $dataSPDK2;
                    if (!empty($dataSPDK2) || $dataSPDK2 != null) {

                        $dataSPD = new StokProdukDetailOpname();
                        $dataSPD->norec = $dataSPD->generateNewId();
                        $dataSPD->kdprofile = $idProfile;
                        $dataSPD->statusenabled = true;
                        $dataSPD->objectasalprodukfk = $dataSPDK2->objectasalprodukfk;
                        $dataSPD->hargadiscount = 0;
                        $dataSPD->harganetto1 = $dataSPDK2->harganetto1 ?? 0;
                        $dataSPD->harganetto2 = $dataSPDK2->harganetto2 ?? 0;
                        $dataSPD->persendiscount = 0;
                        $dataSPD->objectprodukfk = $item['kodeproduk'];
                        $dataSPD->qtyprodukreal = $item['qtyReal'];
                        $dataSPD->qtyproduksystem = $item['qtyproduk'];
                        $dataSPD->qtyprodukinext = $selisihReal;
                        $dataSPD->objectruanganfk = $request['ruanganId'];
                        $dataSPD->noclosingfk = $norecSC;
                        $dataSPD->nostrukterimafk = $dataSPDK2->nostrukterimafk;
                        $dataSPD->save();

                        $dataNewSPD = new StokProdukDetail;
                        $dataNewSPD->norec = $dataNewSPD->generateNewId();
                        $dataNewSPD->kdprofile = $idProfile;
                        $dataNewSPD->statusenabled = true;
                        $dataNewSPD->objectasalprodukfk = $dataSPDK2->objectasalprodukfk;
                        $dataNewSPD->hargadiscount = $dataSPDK2->hargadiscount;
                        $dataNewSPD->harganetto1 = $dataSPDK2->harganetto1;
                        $dataNewSPD->harganetto2 = $dataSPDK2->harganetto2;
                        $dataNewSPD->persendiscount = 0;
                        $dataNewSPD->objectprodukfk = $dataSPDK2->objectprodukfk;
                        $dataNewSPD->qtyproduk = $selisihReal;
                        $dataNewSPD->qtyprodukonhand = 0;
                        $dataNewSPD->qtyprodukoutext = 0;
                        $dataNewSPD->qtyprodukoutint = 0;
                        $dataNewSPD->objectruanganfk = $request['ruanganId'];
                        $dataNewSPD->nostrukterimafk = $dataSPDK2->nostrukterimafk;
                        $dataNewSPD->noverifikasifk = $dataSPDK2->noverifikasifk;
                        $dataNewSPD->nobatch = $dataSPDK2->nobatch;
                        $dataNewSPD->tglkadaluarsa = $dataSPDK2->tglkadaluarsa;
                        $dataNewSPD->tglpelayanan = $dataSPDK2->tglpelayanan;
                        $dataNewSPD->tglproduksi = $dataSPDK2->tglproduksi;
                        $dataNewSPD->save();

                        $paramTglEd = "";
                        if (isset($item['tglkadaluarsa'])) {
                            $paramTglEd = " AND tglkadaluarsa = '" . $item['tglkadaluarsa'] . "'";
                        } else {
                            $paramTglEd = " AND tglkadaluarsa IS NULL";
                        }

                        $dataSTOKDETAIL[] = DB::select(
                            DB::raw("
                            select qtyproduk as qty,norec
                            from stokprodukdetail_t
                            where kdprofile = $idProfile
                            and objectruanganfk=:ruanganfk
                            and objectprodukfk=:produkfk
                            and statusenabled=true
                            -- and tglkadaluarsa =:tglkadaluarsa
                            $paramTglEd
                        "),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                                // 'tglkadaluarsa' => $item['tglkadaluarsa']
                            )
                        );

                        $namaProduk = Produk::where('id', $item['kodeproduk'])->first();

                        //## KartuStok
                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$item['qtyproduk'],
                            "qtyin" =>   $selisihReal > 0 ? $selisihReal : 0,
                            "qtyout" => $selisihReal < 0 ? $selisihReal : 0,
                            "saldoakhir" => $item['qtyReal'],
                            "keterangan" => 'Stock Opname Namaobat' . $namaProduk->namaproduk . ' Ruangan ' . $request['namaRuangan'],
                            "produkfk" =>  $item['kodeproduk'],
                            "ruanganfk" => $request['ruanganId'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => $dataSPDK->nostrukterimafk,
                            "norectransaksi" =>  $dataSPDK->norec,
                            "stokprodukdetailfk" => $dataSPDK->norec,
                            "tabletransaksi" => 'stokprodukdetail_t',
                            "flagfk" => 5,
                        ));
                    } else {
                        $dataBarang = DB::select(
                            DB::raw("select * from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk=:produkfk and objectruanganfk=:ruanganId and statusenabled=true ORDER BY tglkadaluarsa ASC"),
                            array(
                                'produkfk' => $item['kodeproduk'],
                                'ruanganId' => $request['ruanganId'],
                            )
                        );
                        $namaProduk = Produk::where('id', $item['kodeproduk'])->first();
                        // foreach ($dataBarang as $poek) {
                        $datas[] = array(
                            "kdproduk" => $item['kodeproduk'],
                            "namaproduk" => $namaProduk->namaproduk,
                            "stokSistem" => $item['qtyproduk'],
                            "stokReal" => $item['qtyReal'],
                            "selisih" => $selisihReal,
                            "satuanStandar" => $item['satuanstandar'],
                            "tglkadaluarsa" => $item['tglkadaluarsa'],
                        );

                        // }
                        $dataSPD = new StokProdukDetailOpname();
                        $dataSPD->norec = $dataSPD->generateNewId();
                        $dataSPD->kdprofile = $idProfile;
                        $dataSPD->statusenabled = true;
                        $dataSPD->objectasalprodukfk = $dataBarang[0]->objectasalprodukfk;
                        $dataSPD->hargadiscount = 0;
                        $dataSPD->harganetto1 = $dataBarang[0]->harganetto1 ?? 0;
                        $dataSPD->harganetto2 = $dataBarang[0]->harganetto2 ?? 0;
                        $dataSPD->persendiscount = 0;
                        $dataSPD->objectprodukfk = $item['kodeproduk'];
                        $dataSPD->qtyprodukreal = $item['qtyReal'];
                        $dataSPD->qtyproduksystem = $item['qtyproduk'];
                        $dataSPD->qtyprodukinext = $selisihReal;
                        $dataSPD->objectruanganfk = $request['ruanganId'];
                        $dataSPD->noclosingfk = $norecSC;
                        $dataSPD->statusmasuk = false;
                        $dataSPD->save();
                    }
                } else {
                    $dataSPD = new StokProdukDetailOpname();
                    $dataSPD->norec = $dataSPD->generateNewId();
                    $dataSPD->kdprofile = $idProfile;
                    $dataSPD->statusenabled = true;
                    $dataSPD->objectasalprodukfk = $dataSPDK->objectasalprodukfk;
                    $dataSPD->hargadiscount = 0;
                    $dataSPD->harganetto1 = $dataSPDK->harganetto1 ?? 0;
                    $dataSPD->harganetto2 = $dataSPDK->harganetto2 ?? 0;
                    $dataSPD->persendiscount = 0;
                    $dataSPD->objectprodukfk = $item['kodeproduk'];
                    $dataSPD->qtyprodukreal = $item['qtyReal'];
                    $dataSPD->qtyproduksystem = $item['qtyproduk'];
                    $dataSPD->qtyprodukinext = $selisihReal;
                    $dataSPD->objectruanganfk = $request['ruanganId'];
                    $dataSPD->noclosingfk = $norecSC;
                    $dataSPD->nostrukterimafk = $dataSPDK->nostrukterimafk;
                    $dataSPD->save();

                    $paramTglEd = "";
                    if (isset($item['tglkadaluarsa'])) {
                        $paramTglEd = " AND tglkadaluarsa = '" . $item['tglkadaluarsa'] . "'";
                    } else {
                        $paramTglEd = " AND tglkadaluarsa IS NULL";
                    }
                    //STOK MINUS//
                    $dataStokMinus = DB::select(
                        DB::raw("
                        select sum(qtyproduk) as qty from stokprodukdetail_t
                        where kdprofile = $idProfile
                        and objectruanganfk=:ruanganfk
                        and objectprodukfk=:produkfk
                        and qtyproduk < 0
                        and statusenabled=true
                        $paramTglEd
                        -- and tglkadaluarsa =:tglkadaluarsa
                    "),
                        array(
                            'ruanganfk' => $request['ruanganId'],
                            'produkfk' => $item['kodeproduk'],
                            // 'tglkadaluarsa' => $item['tglkadaluarsa']
                        )
                    );

                    StokProdukDetail::where('objectruanganfk', $request['ruanganId'])
                        ->where('kdprofile',)
                        ->where('objectprodukfk', $item['kodeproduk'])
                        // ->where('tglkadaluarsa', $item['tglkadaluarsa'])
                        ->whereRaw($tglEd)
                        ->where('qtyproduk', '<', 0)
                        ->where('statusenabled', true)
                        ->update([
                            'qtyproduk' => 0
                        ]);

                    if (count($dataStokMinus) != 0) {
                        foreach ($dataStokMinus as $items) {
                            $stokMinus = (float)$items->qty;
                        }
                    }
                    $saldoAwal = 0;
                    $jumlah = $selisihReal;
                    +(float)$stokMinus;


                    if ($jumlah > 0) {
                        $dataStok = DB::select(
                            DB::raw("
                            select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                            where kdprofile = $idProfile
                            and objectruanganfk=:ruanganfk
                            and objectprodukfk=:produkfk
                            and statusenabled=true
                            and qtyproduk > 0
                            -- and tglkadaluarsa =:tglkadaluarsa
                            $paramTglEd
                            limit 1
                        "),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                                // 'tglkadaluarsa' => $item['tglkadaluarsa'],
                            )
                        );

                        if (count($dataStok) == 0) {
                            $dataStok = DB::select(
                                DB::raw("
                                select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                                where kdprofile = $idProfile
                                and objectruanganfk=:ruanganfk
                                and objectprodukfk=:produkfk
                                and statusenabled=true
                                $paramTglEd
                                -- and tglkadaluarsa =:tglkadaluarsa
                                limit 1
                            "),
                                array(
                                    'ruanganfk' => $request['ruanganId'],
                                    'produkfk' => $item['kodeproduk'],
                                    // 'tglkadaluarsa' => $item['tglkadaluarsa']
                                )
                            );
                        }
                        foreach ($dataStok as $items) {
                            StokProdukDetail::where('norec', $items->norec)
                                ->where('kdprofile', $idProfile)
                                // ->where('tglkadaluarsa', $item['tglkadaluarsa'])
                                ->where('statusenabled', true)
                                ->whereRaw($tglEd)
                                ->update([
                                    'qtyproduk' => (float)$items->qty + (float)$jumlah
                                ]);
                        }

                        $dataSTOKDETAIL[] = DB::select(
                            DB::raw("
                            select qtyproduk as qty,norec from stokprodukdetail_t
                            where kdprofile = $idProfile
                            and objectruanganfk=:ruanganfk
                            and objectprodukfk=:produkfk
                            and statusenabled=true
                            $paramTglEd
                           -- and tglkadaluarsa =:tglkadaluarsa
                        "),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                                // 'tglkadaluarsa' => $item['tglkadaluarsa']
                            )
                        );
                    } else {
                        $jumlah = $jumlah * (-1);
                        $dataStok = DB::select(
                            DB::raw("
                            select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                            where kdprofile = $idProfile
                            and objectruanganfk=:ruanganfk
                            and objectprodukfk=:produkfk
                            and statusenabled=true
                            $paramTglEd
                            -- and tglkadaluarsa =:tglkadaluarsa
                        "),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                                // 'tglkadaluarsa' => $item['tglkadaluarsa'],
                            )
                        );
                        foreach ($dataStok as $items) {
                            if ((float)$items->qty < $jumlah) {
                                $jumlah = $jumlah - (float)$items->qty;
                                StokProdukDetail::where('norec', $items->norec)
                                    ->where('kdprofile', $idProfile)
                                    ->where('statusenabled', true)
                                    ->update(
                                        [
                                            'qtyproduk' => 0
                                        ]
                                    );
                            } else {
                                $saldoakhir = (float)$items->qty - $jumlah;
                                $jumlah = 0;
                                StokProdukDetail::where('norec', $items->norec)
                                    ->where('kdprofile', $idProfile)
                                    ->where('statusenabled', true)
                                    ->update(
                                        [
                                            'qtyproduk' => (float)$saldoakhir
                                        ]
                                    );
                            }
                        }

                        $dataSTOKDETAIL[] = DB::select(
                            DB::raw("
                            select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                            where kdprofile = $idProfile
                            and objectruanganfk=:ruanganfk
                            and objectprodukfk=:produkfk
                            and statusenabled=true
                            $paramTglEd
                            -- and tglkadaluarsa =:tglkadaluarsa
                        "),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeproduk'],
                                // 'tglkadaluarsa' => $item['tglkadaluarsa']
                            )
                        );
                    }

                    $dataSaldoAwalK = DB::select(
                        DB::raw("
                        select sum(qtyproduk) as qty from stokprodukdetail_t
                        where kdprofile = $idProfile
                        and objectruanganfk=:ruanganfk
                        and objectprodukfk=:produkfk
                        and statusenabled=true
                    "),
                        array(
                            'ruanganfk' => $request['ruanganId'],
                            'produkfk' => $item['kodeproduk'],
                        )
                    );

                    $d[1] = $dataSaldoAwalK;

                    $saldoAwal = 0;
                    foreach ($dataSaldoAwalK as $items) {
                        $saldoAwal = (float)$items->qty - $selisihReal;
                    }
                    $d[0] = $saldoAwal;
                    $statusssss = 0;
                    $flagfk = 0;
                    $saldoin = 0;
                    $saldoOut = 0;
                    if ($selisihReal < 0) {
                        $statusssss = 0;
                        $selisih = $selisihReal * (-1);
                        $saldoOut = $selisih;
                        $flagfk = 5;
                    } else {
                        $statusssss = 1;
                        $selisih = $selisihReal;;
                        $saldoin = $selisih;
                        $saldoOut = 0;
                        $flagfk = 4;
                    }
                    $d[2] = $selisih;
                    $d[3] = $statusssss;
                    //## KartuStok

                    $namaProduk = Produk::where('id', $item['kodeproduk'])->first();

                    $saldoakhir = 0;
                    if ($saldoin == 0) {
                        $saldoakhir = $saldoAwal - $saldoOut;
                    } else {
                        $saldoakhir = $saldoAwal + $saldoin;
                    }
                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$saldoAwal,
                        "qtyin" =>  $saldoin,
                        "qtyout" => $saldoOut,
                        "saldoakhir" => $saldoakhir,
                        "keterangan" => 'Stock Opname Nama Obat ' . $namaProduk->namaproduk . ' Ruangan ' . $request['namaRuangan'],
                        "produkfk" =>  $item['kodeproduk'],
                        "ruanganfk" => $request['ruanganId'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $dataSPDK->nostrukterimafk,
                        "norectransaksi" =>  $dataSPDK->norec,
                        "stokprodukdetailfk" => $dataSPDK->norec,
                        "tabletransaksi" => 'stokprodukdetail_t',
                        "flagfk" => $flagfk,
                    ));
                }
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $request['lengthProgress'] == 100 ? 'Berhasil Simpan Stok Opname' : 'hide',
                "databarangtaktersave" => $datas,
                // "noSO" => $dataSC,
                // "detailstok" => $dataSTOKDETAIL ?? 0,
                // "dataSPDK" => $dataSPDK,
                "by" => '@epic',
            );
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "errornya" => $e->getMessage(),
                "message" => $request['stokProduk'][0]['namaproduk'] . ' Simpan Gagal',
                "databarangtaktersave" => $datas,
                // "noSO" => $dataSC,
                // "detailstok" => $dataSTOKDETAIL ?? 0,
                // "dataSPDK" => $dataSPDK,
                "by" => '@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
}