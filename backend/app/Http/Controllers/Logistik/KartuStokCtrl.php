<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStokCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getDataGrid(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $dateRange = [$request->tglawal, $request->tglakhir];

        $data = DB::table('kartustok_t as ks')
            ->JOIN('produk_m as pro', 'pro.id', '=', 'ks.produkfk')
            ->leftJOIN('ruangan_m as ru', 'ru.id', '=', 'ks.ruanganfk')
            // ->leftJoin('flag_m as fg','fg.id','=','ks.flagfk')
            ->select(
                'ks.keterangan',
                DB::raw('DATE(ks.tglinput) as tglinput'),
                DB::raw('DATE(ks.tglkejadian) as tglkejadian'),
                'ks.produkfk',
                'pro.namaproduk',
                'ks.ruanganfk',
                'ru.namaruangan',
                'ks.status',
                DB::raw('COALESCE(ks.saldoawal,0.0) as saldoawal, coalesce(ks.saldoakhir,0.0) as saldoakhir,
                    coalesce(ks.qtyin,0.0) as saldomasuk, coalesce(ks.qtyout,0.0) as saldokeluar')

            )
            ->where('ks.kdprofile', $idProfile)
            ->whereBetween(DB::raw("cast(ks.tglkejadian as DATE)"), $dateRange)
            ->where('ks.statusenabled', true)
            ->orderBy('ks.tglkejadian', 'asc');
        // ->where('ks.ruanganfk', $request['idruangan']);

        if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('ks.ruanganfk', '=', $request['idruangan']);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
            $data = $data->where('ks.produkfk', '=', $request['idproduk']);
        }
        $data = $data->get();

        if($data->isEmpty())
        {
            $data = DB::table('produk_m as pr')
                ->join('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
                ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
                ->select(
                    DB::raw("'Stok Hari Ini' AS keterangan"),
                    DB::raw('CURRENT_DATE  AS tglinput'),
                    DB::raw('CURRENT_DATE  AS tglkejadian'),
                    DB::raw('NULL AS produkfk'),
                    'pr.namaproduk as namaproduk',
                    DB::raw('NULL AS ruanganfk'),
                    'ru.namaruangan as namaruangan',
                    DB::raw('NULL AS status'),
                    DB::raw('SUM(spd.qtyproduk) as saldoawal'),
                    DB::raw('SUM(spd.qtyproduk) as saldoakhir'),
                    DB::raw('0.0 AS saldomasuk'),
                    DB::raw('0.0 AS saldokeluar')
                )
                ->where('pr.id', $request['idproduk'])
                ->where('spd.objectruanganfk', $request['idruangan'])
                ->groupBy('pr.namaproduk', 'ru.namaruangan')
                ->get();
        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getPenggunaanObatAlkes(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $dateRange = [$request->tglawal, $request->tglakhir];

        $departemen = DB::table('ruangan_m')
                        ->select('id','namaruangan','objectdepartemenfk')
                        ->where('statusenabled', true)
                        ->where('id', $request['idruangan'])
                        ->first();
        
       if((int)$departemen->objectdepartemenfk == 14)
       { 
            $data = DB::table('strukresep_t as sr')
            ->JOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->select(
                DB::raw('DATE(pp.tglpelayanan) as tglpelayanan'),
                'pr.namaproduk',
                'pr.kdproduk',
                DB::raw("sum(pp.jumlah) as jumlah"),
                'pp.hargasatuan',
                DB::raw("sum(pp.hargasatuan*pp.jumlah) as total"),
            )
            ->where('sr.kdprofile', $idProfile)
            ->whereBetween(DB::raw("cast(pp.tglpelayanan as DATE)"), $dateRange)
            ->where('sr.statusenabled', true)
            ->where('pp.statusenabled', true)
            ->orderBy('pp.tglpelayanan', 'asc')
            ->groupBy('pp.tglpelayanan','pr.namaproduk','pp.hargasatuan','pr.kdproduk');
            if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
                $data = $data->where('sr.ruanganfk', '=', $request['idruangan']);
            }
            if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
                $data = $data->where('pp.produkfk', '=', $request['idproduk']);
            }
        }
        else
        {
            $data = DB::table('strukkirim_t as sk')
            ->JOIN('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sk.norec')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'kp.objectprodukfk')
            ->select(
                DB::raw('DATE(sk.tglkirim) as tglpelayanan'),
                'pr.namaproduk',
                'pr.kdproduk',
                DB::raw("sum(kp.qtyprodukkonfirmasi) as jumlah"),
                'kp.hargasatuan',
                DB::raw("sum(kp.hargasatuan*kp.qtyprodukkonfirmasi) as total"),
            )
            ->where('sk.kdprofile', $idProfile)
            ->whereBetween(DB::raw("cast(sk.tglkirim as DATE)"), $dateRange)
            ->where('sk.statusenabled', true)
            ->where('kp.statusenabled', true)
            ->orderBy('sk.tglkirim', 'asc')
            ->groupBy('sk.tglkirim','pr.namaproduk','kp.hargasatuan','pr.kdproduk');

            if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
                $data = $data->where('sk.objectruanganfk', '=', $request['idruangan']);
            }
            if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
                $data = $data->where('kp.objectprodukfk', '=', $request['idproduk']);
            }
        }
            

            $data = $data->get();

            $res['data'] = $data;
            return $this->respond($res);
    }

    public function getPenggunaanAlkesFloorStok(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $dateRange = [$request->tglawal, $request->tglakhir];

        $departemen = DB::table('ruangan_m')
                        ->select('id','namaruangan','objectdepartemenfk')
                        ->where('statusenabled', true)
                        ->where('id', $request['idruangan'])
                        ->first();
        
       if((int)$departemen->objectdepartemenfk == 14)
       { 
            $data = DB::table('strukresep_t as sr')
            ->JOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->where('sr.ruanganfk', $request['idruangan'])
            ->whereBetween(DB::raw("cast(pp.tglpelayanan as DATE)"), $dateRange)
            ->select([
                'pr.id',
                'pr.namaproduk',
                'pr.kdproduk',
                DB::raw("(
                    SELECT saldoawal 
                    FROM kartustok_t ks2
                    WHERE ks2.produkfk = pp.produkfk
                    AND ks2.ruanganfk = ?
                    AND ks2.tglkejadian BETWEEN ? AND ?
                    ORDER BY ks2.tglkejadian ASC 
                    LIMIT 1
                ) AS saldoawal"),
                DB::raw("(
                    SELECT saldoakhir 
                    FROM kartustok_t ks2
                    WHERE ks2.produkfk = pp.produkfk
                    AND ks2.ruanganfk = ?
                    AND ks2.tglkejadian BETWEEN ? AND ?
                    ORDER BY ks2.tglkejadian DESC 
                    LIMIT 1
                ) AS saldoakhir"),
                DB::raw("(
                    SELECT COALESCE(SUM(qtyprodukkonfirmasi), 0) 
                    FROM kirimproduk_t ks2
                    WHERE ks2.objectprodukfk = pp.produkfk
                    AND ks2.objectruanganfk = ?
                    AND ks2.tglpelayanan BETWEEN ? AND ?
                ) AS saldomasuk"),
                DB::raw('SUM(pp.jumlah) AS jumlah')
            ])
            ->addBinding([$request->idruangan, $request->tglawal, $request->tglakhir. ' 23:59:59'], 'select')
            ->addBinding([$request->idruangan, $request->tglawal, $request->tglakhir. ' 23:59:59'], 'select')
            ->addBinding([$request->idruangan, $request->tglawal, $request->tglakhir. ' 23:59:59'], 'select')
            ->groupBy('pr.id', 'pr.namaproduk', 'pr.kdproduk', 'pp.produkfk'); 
           
            if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
                $data = $data->where('sr.ruanganfk', '=', $request['idruangan']);
            }
            if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
                $data = $data->where('pp.produkfk', '=', $request['idproduk']);
            }     
       }
        
        else
        {
            $data = DB::table('strukkirim_t as sr')
            ->JOIN('kirimproduk_t as pp', 'pp.nokirimfk', '=', 'sr.norec')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.objectprodukfk')
            ->where('sr.objectruanganfk', $request['idruangan'])
            ->whereBetween(DB::raw("cast(pp.tglpelayanan as DATE)"), $dateRange)
            ->select([
                'pr.id',
                'pr.namaproduk',
                'pr.kdproduk',
                DB::raw("(
                    SELECT saldoawal 
                    FROM kartustok_t ks2
                    WHERE ks2.produkfk = pp.objectprodukfk
                    AND ks2.ruanganfk = ?
                    AND ks2.tglkejadian BETWEEN ? AND ?
                    ORDER BY ks2.tglkejadian ASC 
                    LIMIT 1
                ) AS saldoawal"),
                DB::raw("(
                    SELECT saldoakhir 
                    FROM kartustok_t ks2
                    WHERE ks2.produkfk = pp.objectprodukfk
                    AND ks2.ruanganfk = ?
                    AND ks2.tglkejadian BETWEEN ? AND ?
                    ORDER BY ks2.tglkejadian DESC 
                    LIMIT 1
                ) AS saldoakhir"),
                DB::raw("(SELECT COALESCE((
                    SELECT SUM(qtyprodukkonfirmasi)
                    FROM kirimproduk_t ks2
                    JOIN strukkirim_t AS sk ON sk.norec = ks2.nokirimfk
                    WHERE ks2.objectprodukfk = pp.objectprodukfk
                    AND (sk.isfloor != 't' OR sk.isfloor IS NULL)
                    AND ks2.objectruanganfk = ?
                    AND ks2.tglpelayanan BETWEEN ? AND ?
                ), 0) AS saldomasuk)"),
                DB::raw('SUM(pp.qtyprodukkonfirmasi) AS jumlah')
            ])
            ->addBinding([$request->idruangan, $request->tglawal, $request->tglakhir. ' 23:59:59'], 'select')
            ->addBinding([$request->idruangan, $request->tglawal, $request->tglakhir. ' 23:59:59'], 'select')
            ->addBinding([$request->idruangan, $request->tglawal, $request->tglakhir. ' 23:59:59'], 'select')
            ->groupBy('pr.id', 'pr.namaproduk', 'pr.kdproduk', 'pp.objectprodukfk'); 

            if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
                $data = $data->where('sr.objectruanganfk', '=', $request['idruangan']);
            }
            if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
                $data = $data->where('pp.objectprodukfk', '=', $request['idproduk']);
            }
        }
            

            $data = $data->get();
            $res['data'] = $data;
            return $this->respond($res);
    }

    public function listProduk(Request $r)
    {
        $data =  DB::table('produk_m as pr')
            ->select('pr.id', 'pr.namaproduk')
            ->where('pr.statusenabled', true)
            ->where('pr.kdprofile')
            ->get();
        // if (isset($r['nama'])) {
        //     $data = $data->where('pr.namaproduk', 'ilike', '%' . $r['nama'] . '%');
        // }
        // $data = $data->limit($r['limit']);
        // $data = $data->orderBy('pr.namaproduk');
        // $data = $data->get();

        return $this->respond($data);
    }

    public function getCombo(Request $request)
    {
        $idProfile = (int)$this->kdProfile;

        $dataRuangan =  DB::table('maploginusertoruangan_s as mlur')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mlur.objectruanganfk')
            ->join('loginuser_s as lu', 'lu.id', '=', 'mlur.objectloginuserfk')
            ->select('ru.id', 'ru.namaruangan')
            ->where('mlur.statusenabled', true)
            ->where('mlur.objectloginuserfk', $this->getUserId())
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('mlur.kdprofile', $this->kdProfile)
            ->get();

        $dataProduk = Produk::mine()
        ->select('id', 'namaproduk','kdproduk')
        ->where('statusenabled', true)
        ->search($request['name'])
        ->limit($request['limit'])
        ->orderBy('namaproduk')
        ->get();


        $res['ruangan'] = $dataRuangan;
        $res['produk'] = $dataProduk;

        return $this->respond($res);
    }


    public function getRuangan(Request $request)
    {
        $dataRuangan = DB::table('ruangan_m')
        ->select('id', 'namaruangan')
        ->where('statusenabled', true)
        ->where('objectdepartemenfk', 16)
        ->get();
        $res['ruangan'] = $dataRuangan;
        return $this->respond($res);
    }


    public function getProduk(Request $request)
    {

        $data = Produk::mine()->search($request['namaproduk'])
        // ->select('namaproduk')
        ->limit(20)
        ->get();

        return $this->respond($data);
    }

    public function getFastMoving(Request $request)
    {
        // $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int)$this->kdProfile;
        $dataLogin = $request->all();
        $data = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->leftjoin('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftjoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select(
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pp.tglpelayanan',
                'pp.produkfk',
                'prd.namaproduk',
                'pp.jumlah',
                'kp.id as idkelompokproduk',
                'kp.kelompokproduk',
                'jp.id as idjenisproduk',
                'jp.jenisproduk'
            )
            ->where('pp.kdprofile', $idProfile);

        if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
            $data = $data->where('pp.tglpelayanan', '>=', $request->tglawal);
        }
        if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
            $data = $data->where('pp.tglpelayanan', '<=', $request->tglakhir);
        }
        if (isset($request->idruangan) && $request->idruangan != "" && $request->idruangan != "undefined") {
            $data = $data->where('apd.objectruanganfk', '=', $request->idruangan);
        }

        if (isset($request->idproduk) && $request->idproduk != "" && $request->idproduk != "undefined") {
            $data = $data->where('prd.id', '=', $request->idproduk);
        }
        $data = $data->whereIn('kp.id', [6, 20, 23, 24]);
        $data = $data->get();

        $data10 = [];
        $jml = 0;
        $sama = false;

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
    public function getSlowMoving(Request $request)
    {
        // $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int)$this->kdProfile;
        $dataLogin = $request->all();
        $data = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->leftjoin('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftjoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select(
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pp.tglpelayanan',
                'pp.produkfk',
                'prd.namaproduk',
                'pp.jumlah',
                'kp.id as idkelompokproduk',
                'kp.kelompokproduk',
                'jp.id as idjenisproduk',
                'jp.jenisproduk'
            )
            ->where('pp.kdprofile', $idProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pp.tglpelayanan', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data = $data->where('pp.tglpelayanan', '<=', $request['tglAkhir']);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $data = $data->where('apd.objectruanganfk', '=', $request['idRuangan']);
        }
        if (isset($request['idKelProduk']) && $request['idKelProduk'] != "" && $request['idKelProduk'] != "undefined") {
            $data = $data->where('kp.id', '=', $request['idKelProduk']);
        }
        if (isset($request['idJenisProduk']) && $request['idJenisProduk'] != "" && $request['idJenisProduk'] != "undefined") {
            $data = $data->where('jp.id', '=', $request['idJenisProduk']);
        }
        if (isset($request['namaProduk']) && $request['namaProduk'] != "" && $request['namaProduk'] != "undefined") {
            $data = $data->where('prd.namaproduk', 'ILIKE', '%' . $request['namaProduk'] . '%');
        }
        $data = $data->whereIn('kp.id', [6, 20, 23, 24]);
        // $data = $data->take(10);

        $data = $data->get();

        $data10 = [];
        $jml = 0;
        $sama = false;

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
                    'namaproduk' => $item->namaproduk,
                    'idkelompokproduk' => $item->idkelompokproduk,
                    'idjenisproduk' => $item->idjenisproduk,
                    'total' => 1,

                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['total'];
            }

            array_multisort($count, SORT_ASC, $data10);
        }
        $result = array(
            'data' => $data10,
            'message' => 'ramdanegie',
        );

        return $this->respond($result);
    }
}