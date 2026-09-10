<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StokProdukDetailAdjusment;
use App\Models\Transaksi\StrukClosing;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananDetail;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokRuanganCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getDataGrid(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $tanggalSekarang = Carbon::now()->format('Y-m-d').' 00:00';
        $tanggalAkhir = Carbon::now()->subMonths(1)->format('Y-m-d').' 23:59';
        $dateRange = [$tanggalAkhir, $tanggalSekarang];

        // return Carbon::now()->subMonths(6)->format('Y-m-d').' 23:59';
        // die;
        $data = DB::table('stokprodukdetail_t as spd')
            ->JOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'spd.nostrukterimafk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->LEFTJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->LEFTJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->LEFTJOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
            ->LEFTJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->LEFTJOIN('konversisatuan_t as ks', 'ks.objekprodukfk', '=', 'pr.id')
            ->select(
                'sp.nostruk as noterima',
                'spd.nostrukterimafk',
                'pr.objectdetailjenisprodukfk',
                'spd.objectruanganfk',
                'pr.kdproduk as kdsirs',
                'pr.id as produkfk',
                'pr.namaproduk',
                'djp.detailjenisproduk',
                'ap.asalproduk',
                'ru.namaruangan',
                // 'spd.qtyproduk',
                'ss.satuanstandar',
                'spd.tglkadaluarsa',
                'spd.nobatch',
                'spd.harganetto1 as harga',
                'spd.norec as norec_spd',
                DB::raw("sum(spd.qtyproduk) as jumlah"),
                'pr.stokminimum','pr.stokmaximum','pr.kekuatan',
                DB::raw("case when pr.bufferstok is null then (case when pr.stokminimum is null then 0 else pr.stokminimum *0.2 end) 
                else (case when pr.stokminimum is null then 0 else pr.stokminimum * (pr.bufferstok/100) end) end as bufferstok,case when extract(day from spd.tglkadaluarsa::timestamp - CURRENT_DATE::timestamp) >= 60 then 'SUDAH MENDEKATI'  else  'BELUM' end as statused,
               case when (case when pr.stokminimum is null then sum(spd.qtyproduk) * (pr.stokminimum/100) else sum(spd.qtyproduk) *0.2 end) > sum(spd.qtyproduk)  then 'AMAN' else 'TIDAK' end as statusstokuse   "),'ks.nilaikonversi',
                'pr.kodeexternal'
            )
            ->where('pr.statusenabled', true)
            ->where('spd.statusenabled', true)
            ->where('pr.kdprofile', $idProfile)
            ->where('spd.kdprofile', $idProfile);
            // ->where('spd.qtyproduk', '>', 0);

        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        }
        if (isset($request['idruangan'])  && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('spd.objectruanganfk', '=', $request['idruangan']);
        }
        if (isset($request['idasalproduk']) && $request['v'] != "" && $request['idasalproduk'] != "undefined") {
            $data = $data->where('spd.objectasalprodukfk', '=', $request['idasalproduk']);
        }
        if (isset($request['iddetailjenisbarang']) && $request['iddetailjenisbarang'] != "" && $request['iddetailjenisbarang'] != "undefined") {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', $request['iddetailjenisbarang']);
        }
        if (isset($request['kodeproduk']) && $request['kodeproduk'] != "" && $request['kodeproduk'] != "undefined") {
            $data = $data->where('pr.id', 'ilike', '%' . $request['kodeproduk'] . '%');
        }
        if (isset($request['kosong']) && $request['kosong'] != "" && $request['kosong'] != "undefined" && $request['kosong'] != "false") {
            $data = $data->where('spd.qtyproduk', '<=', 0);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
            $data = $data->where('pr.id', '=', $request['idproduk']);
        }
        $data = $data->groupBy(
            'sp.nostruk',
            'spd.nostrukterimafk',
            'spd.objectruanganfk',
            'pr.kdproduk',
            'pr.id',
            'pr.namaproduk',
            'ap.asalproduk',
            'pr.objectdetailjenisprodukfk',
            'djp.detailjenisproduk',
            'ru.namaruangan',
            // 'spd.qtyproduk',
            'ss.satuanstandar',
            'spd.tglkadaluarsa',
            'spd.nobatch',
            'spd.harganetto1',
            'spd.norec',
            'pr.bufferstok','pr.stokmaximum',
            'pr.stokminimum','pr.kekuatan','ks.nilaikonversi','pr.kodeexternal'
        );
        $data = $data->limit($request['limit']);
        $data = $data->get();

        foreach ($data as $item) {

            $dataPemakaian = DB::table('kartustok_t as ks')
            ->JOIN('produk_m as pro', 'pro.id', '=', 'ks.produkfk')
            ->leftJOIN('ruangan_m as ru', 'ru.id', '=', 'ks.ruanganfk')
            ->select(
                DB::raw('sum(ks.qtyout) as saldokeluar')

            )
            ->where('ks.kdprofile', $idProfile)
            ->whereBetween(DB::raw("cast(ks.tglkejadian as DATE)"), $dateRange)
            ->where('ks.statusenabled', true)
            ->where('ks.produkfk', $item->produkfk)
            ->where('ks.ruanganfk', $request['idruangan']);
            $dataPemakaian = $dataPemakaian->get();
            $item->saldokeluar = $dataPemakaian[0]->saldokeluar?$dataPemakaian[0]->saldokeluar:0;
            $item->saldokeluarAVG = $dataPemakaian[0]->saldokeluar?($dataPemakaian[0]->saldokeluar/6):0;
            $item->saldokebutuhan = $dataPemakaian[0]->saldokeluar?($dataPemakaian[0]->saldokeluar*6) + ($dataPemakaian[0]->saldokeluar*1.5) + ($dataPemakaian[0]->saldokeluar*0.2):0;
            $item->saldorencanakebutuhan = $dataPemakaian[0]->saldokeluar?(($dataPemakaian[0]->saldokeluar*6) + ($dataPemakaian[0]->saldokeluar*1.5) + ($dataPemakaian[0]->saldokeluar*0.2))-9:0;
            $item->stokminimum = $dataPemakaian[0]->saldokeluar?(($dataPemakaian[0]->saldokeluar*1.5) + ($dataPemakaian[0]->saldokeluar*0.2)):0;
            $item->stokmaximum = $dataPemakaian[0]->saldokeluar?(($dataPemakaian[0]->saldokeluar*1.5) + ($dataPemakaian[0]->saldokeluar*0.2))*9:0;
                if($item->stokminimum >= $item->jumlah){
                    $item->statusstokuse="TIDAK";
                }else{
                    $item->statusstokuse="AMAN";
                }

        }
        // $data2 = [];

        // $dataOrder = [];

        // foreach ($data as $item) {
        //     $data2[] = array(
        //         'noterima' => $item->noterima,
        //         'kodeproduk' => $item->objectprodukfk,
        //         // 'kdsirs' => $item->kdsirs,
        //         'namaproduk' => $item->namaproduk,
        //         'asalproduk' => $item->asalproduk,
        //         'qtyproduk' => $item->qtyproduk,
        //         'satuanstandar' => $item->satuanstandar,
        //         'tglkadaluarsa' => $item->tglkadaluarsa,
        //         'nobatch' => $item->nobatch,
        //         'harga' => $item->harganetto1,
        //         'norecspd' => $item->norec_spd,
        //         'nostrukterimafk' => $item->nostrukterimafk,
        //     );
        // }
        // $res['data'] = $data2;
        // $res['detailorder'] = $dataOrder;
        return $this->respond($data);
    }
    
    public function getDataGridOrder(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
    
        $sevenDaysAgo = now()->subDays(30);
        $sevenDaysAgo2 = now()->subDays(90);

        $departemen = DB::table('ruangan_m')
        ->select('id','namaruangan','objectdepartemenfk')
        ->where('statusenabled', true)
        ->where('id', $request['idruangan'])
        ->first();
        
        // Departemen Farmasi --------------------------------------------------------------------------
        if((int)$departemen->objectdepartemenfk == 14)
        { 
            $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
            ->select('spd_latest.objectprodukfk', DB::raw('MAX(spd_latest.harganetto1) as harganetto1'))
            ->where('spd_latest.statusenabled', true)
            ->whereRaw("spd_latest.created_at >= NOW() - INTERVAL '1 YEAR'")
            ->groupBy('spd_latest.objectprodukfk');

        $stoktujuan = DB::table('stokprodukdetail_t as spd_stoktujuan')
            ->selectRaw('SUM(spd_stoktujuan.qtyproduk) as stokpengirim, spd_stoktujuan.objectprodukfk')  
            ->where('spd_stoktujuan.statusenabled', true)
            ->where('spd_stoktujuan.objectruanganfk',$request['idruangantujuan'] )  
            ->groupBy('spd_stoktujuan.objectprodukfk');  
        
        $stokpemesan = DB::table('stokprodukdetail_t as stokpemesan')
            ->selectRaw('SUM(stokpemesan.qtyproduk) as total, stokpemesan.objectprodukfk')  
            ->where('stokpemesan.statusenabled', true)
            ->where('stokpemesan.objectruanganfk',$request['idruangan'] )  
            ->groupBy('stokpemesan.objectprodukfk'); 
         
        $stokpengeluaran = DB::table('pelayananpasien_t as pp_pengeluaran')
        ->join('strukresep_t as sr', 'pp_pengeluaran.strukresepfk', '=', 'sr.norec')
        ->selectRaw('SUM(pp_pengeluaran.jumlah) as pengeluaran, pp_pengeluaran.produkfk')  
        ->where('pp_pengeluaran.statusenabled', true)
        ->where('sr.ruanganfk',$request['idruangan'] )  
        ->where('pp_pengeluaran.tglpelayanan', '>=', $sevenDaysAgo)
        ->groupBy('pp_pengeluaran.produkfk'); 

        $data = DB::table('produk_m as pr')
            ->join('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id') 
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->leftJoinSub($stoktujuan, 'stoktujuan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stoktujuan.objectprodukfk');
            })
            ->leftJoinSub($stokpemesan, 'stokpemesan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stokpemesan.objectprodukfk');
            })
            ->leftJoinSub($stokpengeluaran, 'pp_pengeluaran', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran.produkfk');
            })
            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'ss.id as satuanstandarfk',
                'stoktujuan.stokpengirim AS stokpengirim',
                DB::raw('CASE WHEN stokpemesan.total IS NULL THEN 0 ELSE stokpemesan.total END AS total'),
                DB::raw('CASE WHEN pp_pengeluaran.pengeluaran IS NULL THEN 0 ELSE pp_pengeluaran.pengeluaran END AS pengeluaran'),
                'latest_price.harganetto1 AS latest_harganetto1'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pr.objectkelompokprodukfk', 24)
            ->whereRaw('(pr.objectsumberdanafk IS NULL OR pr.objectsumberdanafk != 4)')
            ->where('spd.objectruanganfk', $request['idruangantujuan'])
            ->where('pp_pengeluaran.pengeluaran', '>', 0)
            ->groupBy(
                'pr.kdproduk',   
                'ss.id',            
                'pr.id',                
                'pr.namaproduk',                                   
                'ss.satuanstandar',               
                'stokpemesan.total',  
                'pp_pengeluaran.pengeluaran',  
                'stoktujuan.stokpengirim',          
                'latest_price.harganetto1' 
            );
        }
        // Departemen Non Farmasi --------------------------------------------------------------------------
        else
        {
            $latestPriceSubquery = DB::table('stokprodukdetail_t as spd_latest')
            ->select('spd_latest.objectprodukfk', 'spd_latest.harganetto1')
            ->where('spd_latest.statusenabled', true)
            ->whereRaw('spd_latest.created_at = (
                SELECT MAX(spd2.created_at)
                FROM stokprodukdetail_t AS spd2
                WHERE spd2.objectprodukfk = spd_latest.objectprodukfk
                AND spd2.statusenabled = true
            )')
            ->whereRaw('spd_latest.harganetto1 = (
                SELECT MAX(spd3.harganetto1)
                FROM stokprodukdetail_t AS spd3
                WHERE spd3.objectprodukfk = spd_latest.objectprodukfk
                AND spd3.statusenabled = true
                AND spd3.created_at = spd_latest.created_at
            )');

        $stoktujuan = DB::table('stokprodukdetail_t as spd_stoktujuan')
            ->selectRaw('SUM(spd_stoktujuan.qtyproduk) as stokpengirim, spd_stoktujuan.objectprodukfk')  
            ->where('spd_stoktujuan.statusenabled', true)
            ->where('spd_stoktujuan.objectruanganfk',$request['idruangantujuan'] )  
            ->groupBy('spd_stoktujuan.objectprodukfk');  
        
        $stokpemesan = DB::table('stokprodukdetail_t as stokpemesan')
            ->selectRaw('SUM(stokpemesan.qtyproduk) as total, stokpemesan.objectprodukfk')  
            ->where('stokpemesan.statusenabled', true)
            ->where('stokpemesan.objectruanganfk',$request['idruangan'] )  
            ->groupBy('stokpemesan.objectprodukfk'); 
         
        $stokpengeluaran = DB::table('kirimproduk_t as pp_pengeluaran')
        ->join('strukkirim_t as sr', 'pp_pengeluaran.nokirimfk', '=', 'sr.norec')
        ->selectRaw('SUM(pp_pengeluaran.qtyprodukkonfirmasi) as pengeluaran, pp_pengeluaran.objectprodukfk')  
        ->where('pp_pengeluaran.statusenabled', true)
        ->where('sr.isfloor', true)
        ->where('sr.objectruanganfk',$request['idruangan'] )  
        ->where('pp_pengeluaran.tglpelayanan', '>=', $sevenDaysAgo2)
        ->groupBy('pp_pengeluaran.objectprodukfk'); 

        $data = DB::table('produk_m as pr')
            ->join('stokprodukdetail_t as spd', 'pr.id', '=', 'spd.objectprodukfk') 
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->leftJoinSub($stoktujuan, 'stoktujuan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stoktujuan.objectprodukfk');
            })
            ->leftJoinSub($stokpemesan, 'stokpemesan', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'stokpemesan.objectprodukfk');
            })
            ->leftJoinSub($stokpengeluaran, 'pp_pengeluaran', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pp_pengeluaran.objectprodukfk');
            })
            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'ss.id as satuanstandarfk',
                'stoktujuan.stokpengirim AS stokpengirim',
                DB::raw('CASE WHEN stokpemesan.total IS NULL THEN 0 ELSE stokpemesan.total END AS total'),
                DB::raw('CASE WHEN pp_pengeluaran.pengeluaran IS NULL THEN 0 ELSE pp_pengeluaran.pengeluaran END AS pengeluaran'),
                'latest_price.harganetto1 AS latest_harganetto1'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pr.objectkelompokprodukfk', 24)
            ->where('spd.objectruanganfk', $request['idruangantujuan'])
            ->where('pp_pengeluaran.pengeluaran', '>', 0)
            ->groupBy(
                'pr.kdproduk',   
                'ss.id',            
                'pr.id',                
                'pr.namaproduk',                                   
                'ss.satuanstandar',               
                'stokpemesan.total',  
                'pp_pengeluaran.pengeluaran',  
                'stoktujuan.stokpengirim',          
                'latest_price.harganetto1' 
            );
        }

        
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        }

        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "S")  {
            $data = $data;
        }

        else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "O")  {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2546)
                         ->whereRaw('(pr.ispsikotropika IS NULL OR pr.ispsikotropika != true)')
                         ->whereRaw('(pr.isnarkotika IS NULL OR pr.isnarkotika != true)');
        }

        else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "A")  {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2547)
                        ->whereRaw('(pr.ispsikotropika IS NULL OR pr.ispsikotropika != true)')
                        ->whereRaw('(pr.isnarkotika IS NULL OR pr.isnarkotika != true)');
        }

        else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "B")  {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', 2549)
                         ->whereRaw('(pr.ispsikotropika IS NULL OR pr.ispsikotropika != true)')
                         ->whereRaw('(pr.isnarkotika IS NULL OR pr.isnarkotika != true)');
        }

        else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "G")  {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', 3099)
                         ->whereRaw('(pr.ispsikotropika IS NULL OR pr.ispsikotropika != true)')
                         ->whereRaw('(pr.isnarkotika IS NULL OR pr.isnarkotika != true)');
        }

        else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "P")  {
            $data = $data->where('pr.ispsikotropika', '=', true);
        }

        else if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined" && $request['status'] == "N")  {
            $data = $data->where('pr.isnarkotika', '=', true);
        }

        $data = $data->orderBy('pengeluaran', 'desc')->get();

        $result = array(
            'data' => $data,
            'message' => 'Data successfully retrieved',
        );

        return $this->respond($result);

    }


    public function getDataHargaJual(Request $request)
    {
        $latestPriceSubquery = DB::table(DB::raw("(
            SELECT *
            FROM (
                SELECT 
                    spd.objectprodukfk,
                    CASE 
                        WHEN spd.objectasalprodukfk = 3 THEN 0 
                        ELSE MAX(spd.harganetto1)
                    END AS harganetto1,
                    sk.tglstruk,
                    sk.norec AS nostrukterimafk,
                    1 AS prioritas,
                    ROW_NUMBER() OVER (PARTITION BY spd.objectprodukfk ORDER BY 1 ASC, MAX(spd.harganetto1) DESC) as rn
                FROM stokprodukdetail_t AS spd
                LEFT JOIN strukpelayanan_t AS sk ON sk.norec = spd.nostrukterimafk
                WHERE 
                    spd.kdprofile = 1
                    AND spd.statusenabled = TRUE
                    AND spd.created_at BETWEEN '2025-01-01 00:00:00' AND NOW()
                GROUP BY spd.objectprodukfk, sk.tglstruk, sk.norec, spd.objectasalprodukfk
            
                UNION ALL

                SELECT 
                    spd.objectprodukfk,
                    CASE 
                        WHEN spd.objectasalprodukfk = 3 THEN 0 
                        ELSE MAX(spd.harganetto1)
                    END AS harganetto1,
                    sk.tglstruk,
                    sk.norec AS nostrukterimafk,
                    2 AS prioritas,
                    ROW_NUMBER() OVER (PARTITION BY spd.objectprodukfk ORDER BY 2 ASC, MAX(spd.harganetto1) DESC) as rn
                FROM stokprodukdetail_t AS spd
                LEFT JOIN strukpelayanan_t AS sk ON sk.norec = spd.nostrukterimafk
                WHERE spd.kdprofile = 1 
                    AND spd.statusenabled = TRUE
                    AND spd.created_at BETWEEN (NOW() - INTERVAL '1 year') AND NOW()
                GROUP BY spd.objectprodukfk, sk.tglstruk, sk.norec, spd.objectasalprodukfk
            ) AS harga_union
            WHERE rn = 1
        ) AS latest_price"))
        ->select('objectprodukfk', 'harganetto1', 'tglstruk', 'nostrukterimafk', 'prioritas');

            $data = DB::table('stokprodukdetail_t as spd')
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoinSub($latestPriceSubquery, 'latest_price', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'latest_price.objectprodukfk');
            })
            ->select(
                'spd.objectprodukfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'latest_price.harganetto1 as latest_harganetto1',
                'latest_price.tglstruk'
            )
            ->where('spd.statusenabled', true)
            ->groupBy(
                'spd.objectprodukfk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'latest_price.harganetto1',
                'latest_price.tglstruk' 
        )
        ->havingRaw('SUM(DISTINCT spd.qtyproduk) >= 0');

        if (isset($request['produk']) && $request['produk'] != "" && $request['produk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['produk'] . '%');
        }

        if (isset($request['jenis']) && $request['jenis'] != "" && $request['jenis'] != "undefined") {
            $data = $data->where('pr.objectdetailjenisprodukfk', '=', $request['jenis']);
        }

        $data = $data->groupBy('pr.kdproduk', 'pr.namaproduk','pr.objectdetailjenisprodukfk','ss.satuanstandar', 'latest_price.harganetto1');
        $data = $data->orderBy('pr.namaproduk', 'asc');
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDataGridRekap(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $data = DB::table('stokprodukdetail_t as spd')
            ->JOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'spd.nostrukterimafk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->LEFTJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->LEFTJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->LEFTJOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
            ->LEFTJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->LEFTJOIN('konversisatuan_t as ks', 'ks.objekprodukfk', '=', 'pr.id')
            ->select(
                'pr.id as produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                DB::raw("sum(spd.qtyproduk) as jumlah"),
                'pr.stokminimum','pr.stokmaximum',
                DB::raw("case when pr.bufferstok is null then (case when pr.stokminimum is null then 0 else pr.stokminimum *0.2 end) 
                else (case when pr.stokminimum is null then 0 else pr.stokminimum * (pr.bufferstok/100) end) end as bufferstok ")
            )
            ->where('pr.statusenabled', true)
            ->where('spd.statusenabled', true)
            ->where('pr.kdprofile', $idProfile)
            ->where('spd.kdprofile', $idProfile)
            ->where('spd.qtyproduk', '>', 0);

        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        }
        if (isset($request['idruangan'])  && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('spd.objectruanganfk', '=', $request['idruangan']);
        }
        if (isset($request['idasalproduk']) && $request['v'] != "" && $request['idasalproduk'] != "undefined") {
            $data = $data->where('spd.objectasalprodukfk', '=', $request['idasalproduk']);
        }
        $data = $data->groupBy(
            'pr.id',
            'pr.kdproduk',
            'pr.namaproduk',
            'ss.satuanstandar',
            'pr.bufferstok','pr.stokmaximum',
            'pr.stokminimum'
        );
        $data = $data->get();
        // $data2 = [];

        // $dataOrder = [];

        // foreach ($data as $item) {
        //     $data2[] = array(
        //         'noterima' => $item->noterima,
        //         'kodeproduk' => $item->objectprodukfk,
        //         // 'kdsirs' => $item->kdsirs,
        //         'namaproduk' => $item->namaproduk,
        //         'asalproduk' => $item->asalproduk,
        //         'qtyproduk' => $item->qtyproduk,
        //         'satuanstandar' => $item->satuanstandar,
        //         'tglkadaluarsa' => $item->tglkadaluarsa,
        //         'nobatch' => $item->nobatch,
        //         'harga' => $item->harganetto1,
        //         'norecspd' => $item->norec_spd,
        //         'nostrukterimafk' => $item->nostrukterimafk,
        //     );
        // }
        // $res['data'] = $data2;
        // $res['detailorder'] = $dataOrder;
        return $this->respond($data);
    }

    public function updateED(Request $request)
    {
        DB::beginTransaction();
        try{
            StokProdukDetail::where('norec', $request['norec'])->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update([
                    'tglkadaluarsa' => $request['tglkadaluarsa'],
                    'harganetto1' => $request['hargaad'],
                    'harganetto2' => $request['hargaad']
                ]);
              
          DB::commit();   
          
          $result = [
            'status' => 201,
            'message' => 'Berhasil Update Data'
          ];
        }catch(Exception $e){
            DB::rollBack();

            $result = [
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }
       
        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getDaftarPemakaianStokRuangan(Request $request)
    {
        $dateRange = [$request->tglAwal,$request->tglAkhir];
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
                ->select('ru.id')
                ->where('mlu.kdprofile',$this->kdProfile)
                ->where('mlu.statusenabled',true)
                ->where('mlu.objectloginuserfk', $this->getUserId())
                ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }
        $data = DB::table('strukpelayanan_t as sp')
        ->leftJOIN('rekanan_m as rkn', 'rkn.id', 'sp.objectrekananfk')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', 'sp.objectpegawaipenanggungjawabfk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', 'sp.objectruanganfk')
        ->select(
            'sp.tglstruk',
            'sp.nostruk',
            'pg.namalengkap',
            'ru.namaruangan',
            'sp.norec',
            'sp.nofaktur',
            'sp.tglfaktur',
            'sp.totalhargasatuan as total',
            'sp.keteranganlainnya'
        )
        ->where('sp.kdprofile', $this->kdProfile)
        ->whereBetween(DB::raw("sp.tglstruk::date"),$dateRange);

        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $data = $data->where('sp.nostruk', 'ILIKE', '%' . $request['nostruk']);
        }
        if (isset($request['ruanganid']) && $request['ruanganid'] != "" && $request['ruanganid'] != "undefined") {
            $data = $data->where('sp.objectruanganfk', '=', $request['ruanganid']);
        }
        $data = $data->where('sp.statusenabled', true);
        $data = $data->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('PEMAKAIAN BARANG HABIS PAKAI'));
        // $data = $data->wherein('sp.objectruanganfk', $strRuangan);
        $data = $data->orderBy('sp.nostruk');
        $data = $data->get();

        $details = DB::table('strukpelayanandetail_t as spd')
                    ->leftJoin('produk_m as prd','prd.id', 'spd.objectprodukfk')
                    ->leftJoin('satuanstandar_m as ss','ss.id', 'spd.objectsatuanstandarfk')
                    ->selectRaw(
                     "prd.namaproduk, ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargadiscount,
                      ((spd.hargasatuan-spd.hargadiscount)*spd.qtyproduk) as total,spd.nostrukfk"
                    )
                    ->where('spd.kdprofile',$this->kdProfile)
                    ->where('spd.statusenabled',true)
                    ->whereBetween(DB::raw("spd.tglpelayanan::date"),$dateRange)
                    ->get();
            
        $result = [];

        foreach($data as $dat){
             $detailss = [];
            foreach($details as $detail){
                if($detail->nostrukfk == $dat->norec){
                    $detailss[] = $detail;
                }
            }
            $result[] = [
                'tglstruk' => $dat->tglstruk,
                'nostruk' => $dat->nostruk,
                'norec' => $dat->norec,
                'namaruangan' => $dat->namaruangan,
                'namapegawai' => $dat->namalengkap,
                'keterangan' => $dat->keteranganlainnya,
                'total' => $dat->total,
                'details' => $detailss,
            ];
        }            

        return $this->respond($result);
    
    }

    public function savePemakaianStokRuangan(Request $request)
    {

        $idProfile = $this->kdProfile;
        DB::beginTransaction();
        $req = $request;
        try {
            if ($request['struk']['nostruk'] == '') {
                $SP = new StrukPelayanan();
                $norecSP = $SP->generateNewId();
                $noStruk = $this->generateCode(new StrukPelayanan, 'nostruk', 13, 'PS/' . $this->getDateTime()->format('ym/'), $idProfile);
                $SP->norec = $norecSP;
                $SP->kdprofile = $idProfile;
                $SP->statusenabled = true;
                $SP->nostruk = $noStruk;
                $SP->noterima = $noStruk;
                $message = "Simpan Data Berhasil";
                
            } else {
                $message = "Update Data Berhasil";
                $SP = StrukPelayanan::where('norec', $request['struk']['nostruk'])->first();
                $noStruk = $SP->nostruk;
                $norecSP = $SP->norec;
              
                $dataKembaliStok = DB::select(
                    DB::raw("select spd.qtyproduk ,spd.hasilkonversi,spd.objectruanganfk ,
                    spd.objectprodukfk,spd.harganetto,pr.namaproduk,spd.norec as norec_spd
                    from strukpelayanandetail_t as spd
                    join produk_m as pr on pr.id = spd.objectprodukfk
                    where spd.kdprofile = $idProfile and spd.nostrukfk=:strukfk"),
                    array(
                        'strukfk' => $norecSP,
                    )
                );
    
                foreach ($dataKembaliStok as $item5) {
                    $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
                    $newSPD = StokProdukDetail::where('objectruanganfk', $item5->objectruanganfk)
                        ->where('kdprofile', $idProfile)
                        ->where('objectprodukfk', $item5->objectprodukfk)
                        ->orderby('tglkadaluarsa', 'desc')
                        ->first();
                         $newSPD->qtyproduk = (float)$newSPD->qtyproduk + (float)$TambahStok;
                         $newSPD->save();

                    // StokProdukDetail::where('kdprofile', $this->kdProfile)
                    //             ->where('objectruanganfk', $item5->objectruanganfk)
                    //             ->where('objectprodukfk', $item5->objectprodukfk)
                    //             ->orderby('tglkadaluarsa', 'desc')
                    //             ->lockForUpdate()
                    //             ->increment('qtyproduk', (float)$TambahStok);
                    
                    $dataSaldoAkhir = StokProdukDetail::where('kdprofile',$this->kdProfile)
                                     ->where('statusenabled',true)->where('objectruanganfk', $item5->objectruanganfk)
                                     ->where('objectprodukfk', $item5->objectprodukfk)
                                     ->sum('qtyproduk');

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAkhir - (float)$TambahStok,
                        "qtyin" =>  $TambahStok,
                        "qtyout" => 0,
                        "saldoakhir" =>  $dataSaldoAkhir,
                        "keterangan" => 'Ubah Pemakaian Stok Ruangan  No. ' . $noStruk . ', pada produk ' . $item5->namaproduk,
                        "produkfk" => $item5->objectprodukfk,
                        "ruanganfk" => $item5->objectruanganfk,
                        "tglinput" =>  date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $newSPD->nostrukterimafk,
                        "norectransaksi" => $norecSP,
                        "stokprodukdetailfk" => $item5->norec_spd,
                        "tabletransaksi" => 'strukpelayanan_t',
                        "flagfk" => null,
                    ));
    
                }

                StrukPelayananDetail::where('nostrukfk', $request['struk']['nostruk'])->where('kdprofile', $idProfile)->delete();
                //</editor-fold>
            }

            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('PEMAKAIAN BARANG HABIS PAKAI');
            $SP->objectruanganfk = $req['struk']['ruanganfk'];
            $SP->keteranganlainnya = $req['struk']['keterangan'];
            $SP->tglstruk = $req['struk']['tglstruk'];
            $SP->objectpegawaipenanggungjawabfk = $req['struk']['pegawaimenerimafk'];
            $SP->qtyproduk = $req['struk']['qtyproduk'];

            $SP->totalhargasatuan = $req['struk']['total'];
            $SP->save();
            
            $ddd = [];
            foreach ($req['details'] as $item) {
                $SPD = new StrukPelayananDetail();
                $norecKS = $SPD->generateNewId();
                $SPD->norec = $norecKS;
                $SPD->kdprofile = $idProfile;
                $SPD->statusenabled = true;
                $SPD->nostrukfk = $SP->norec;
                $SPD->objectasalprodukfk = $item['asalprodukfk'];
                $SPD->objectprodukfk = $item['produkfk'];
                $SPD->objectruanganfk = $item['ruanganfk'];
                $SPD->objectruanganstokfk = $item['ruanganfk'];
                $SPD->objectsatuanstandarfk = $item['satuanstandarfk'];
                $SPD->hargadiscount = 0;
                $SPD->hargadiscountgive = 0;
                $SPD->hargadiscountsave = 0;
                $SPD->harganetto = $item['hargasatuan'];
                $SPD->hargapph = 0;
                $SPD->hargappn = 0;
                $SPD->hargasatuan = $item['hargasatuan'];
                $SPD->hasilkonversi = $item['nilaikonversi'];
                $SPD->namaproduk = $item['namaproduk'];
                $SPD->strukterimafk = $item['nostrukterimafk'];
                $SPD->hargasatuandijamin = 0;
                $SPD->hargasatuanppenjamin = 0;
                $SPD->hargatambahan = 0;
                $SPD->hargasatuanpprofile = 0;
                $SPD->isonsiteservice = 0;
                $SPD->kdpenjaminpasien = 0;
                $SPD->persendiscount = 0;
                $SPD->persenppn = 0;
                $SPD->qtyproduk = $item['jumlah'];
                $SPD->qtyprodukoutext = 0;
                $SPD->qtyprodukoutint = 0;
                $SPD->qtyprodukretur = 0;
                $SPD->satuan = '-'; //$item['satuanstandar'];;
                $SPD->satuanstandar = $item['satuanviewfk'];
                $SPD->tglpelayanan = $req['struk']['tglstruk'];
                $SPD->is_terbayar = 0;
                $SPD->linetotal = 0;
                $SPD->save();

                $dataSaldoAwalK = StokProdukDetail::where('kdprofile',$this->kdProfile)
                                ->where('statusenabled',true)
                                ->where('objectruanganfk', $item['ruanganfk'])
                                ->where('objectprodukfk', $item['produkfk'])
                                ->sum('qtyproduk');
                          
                $dataSPDK = StokProdukDetail::where('nostrukterimafk', $item['nostrukterimafk'])
                    ->where('kdprofile', $idProfile)
                    ->where('objectprodukfk', $item['produkfk'])
                    ->where('qtyproduk', '>', 0)
                    ->where('objectruanganfk', $item['ruanganfk'])
                    ->first();
               
                
                $permintaan = ((float)$item['jumlah'] * (float)$item['nilaikonversi']);
          
                StokProdukDetail::where('kdprofile', $this->kdProfile)
                                ->where('norec', $dataSPDK->norec)
                                ->lockForUpdate()
                                ->decrement('qtyproduk', $permintaan);

                              
                $this->kartu_STOK(array(
                    "saldoawal" => (float)$dataSaldoAwalK,
                    "qtyin" => 0,
                    "qtyout" => $permintaan,
                    "saldoakhir" => (float)$dataSaldoAwalK - (float) $permintaan,
                    "keterangan" => 'Pemakaian Stok Ruangan ' . $req['struk']['namaruangan'] . ' No. ' . $noStruk . ', ' . ' Pada produk : ' . $SPD->namaproduk,
                    "produkfk" => $item['produkfk'],
                    "ruanganfk" => $req['struk']['ruanganfk'],
                    "tglinput" =>  date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $item['nostrukterimafk'],
                    "norectransaksi" => $dataSPDK->norec,
                    "stokprodukdetailfk" => $norecKS,
                    "tabletransaksi" => 'stokprodukdetail_t',
                    "flagfk" => null,
                ));
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $message,
                "data" => $SP,
                "as" => 'as@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $message = "Data Gagal Disimpan";
            $result = array(
                "status" => 400,
                "message"  => $message,
                "res" => $e->getMessage(),
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
        // return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }

    public function getCombo()
    {
        // $dataRuangan =  DB::table('maploginusertoruangan_s as mlur')
        //     ->join('ruangan_m as ru', 'ru.id', '=', 'mlur.objectruanganfk')
        //     ->join('loginuser_s as lu', 'lu.id', '=', 'mlur.objectloginuserfk')
        //     ->select('ru.id', 'ru.namaruangan')
        //     ->where('mlur.statusenabled', true)
        //     ->where('mlur.kdprofile', $this->kdProfile)
        //     ->get();

        $ruangan = DB::table('ruangan_m as ru')
            ->select('ru.id', 'ru.namaruangan')
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();


        $dataSumberDana = DB::table('asalproduk_m as lu')
            ->select('lu.id', 'lu.asalproduk as asalProduk')
            ->where('lu.statusenabled', true)
            ->get();
        
        $detailjenisproduk = DB::table('detailjenisproduk_m as djp')
            ->select('djp.id', 'djp.detailjenisproduk as detailjenisproduk')
            ->where('djp.statusenabled', true)
            ->whereIn('djp.id', [2546, 2547, 2549])
            ->get();

        $password = $this->settingFix('AdjustmanPassword');
        $res = ['ruangan' => $ruangan, 'asalproduk' => $dataSumberDana, 'detailjenisproduk' => $detailjenisproduk, 'password' => $password];

        return $this->respond($res);
    }

    public function hapusPemakaianStokRuangan(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();

        try {
            $dataKembaliStok = DB::select(
                DB::raw("select sp.norec,spd.qtyproduk,spd.hasilkonversi,sp.objectruanganfk,spd.objectprodukfk,
                      sp.nostruk,pr.namaproduk
                            from strukpelayanandetail_t as spd
                            INNER JOIN strukpelayanan_t sp on sp.norec=spd.nostrukfk
                            INNER JOIN produk_m as pr on pr.id=spd.objectprodukfk
                            where sp.kdprofile = $idProfile and sp.norec=:norec"),
                array(
                    'norec' => $request['nostruk'],
                )
            );
           
            $dataStokSudahKirim = StokProdukDetail::where('nostrukterimafk', $request['nostruk'])
                ->where('kdprofile', $idProfile)
                ->whereNotIn('objectruanganfk', [$dataKembaliStok[0]->objectruanganfk])
                ->where('qtyproduk', '>', 0)
                ->get();

            if (count($dataStokSudahKirim) == 0) {
                foreach ($dataKembaliStok as $item5) {

                    StrukPelayanan::where('norec', $request['nostruk'])->where('kdprofile', $idProfile)->update(['statusenabled' => false]);
                    StrukPelayananDetail::where('nostrukfk', $request['nostruk'])->where('kdprofile', $idProfile)->delete();

                    $dataSaldoAwal = StokProdukDetail::where('objectruanganfk', $item5->objectruanganfk)
                                    ->where('objectprodukfk', $item5->objectprodukfk)->where('kdprofile',$this->kdProfile)
                                    ->where('statusenabled',true)->sum('qtyproduk');

                    $dataSPDK = DB::table('stokprodukdetail_t as spd')
                                ->join('produk_m as pr','pr.id', 'spd.objectprodukfk')
                                ->select('pr.namaproduk', 'spd.qtyproduk','spd.norec')
                                ->where('spd.qtyproduk','>', 0)
                                ->where('spd.kdprofile',$this->kdProfile)
                                ->where('spd.statusenabled', true)
                                ->where('spd.objectruanganfk', $item5->objectruanganfk)
                                ->where('spd.objectprodukfk', $item5->objectprodukfk)
                                ->first();

                    $stokKembali =  (float)$item5->qtyproduk * (float)$item5->hasilkonversi;     
                
                    StokProdukDetail::where('kdprofile', $this->kdProfile)
                                    ->where('norec', $dataSPDK->norec)
                                    ->where('statusenabled', true)
                                    ->lockForUpdate()
                                    ->increment('qtyproduk', $stokKembali);

                    $this->kartu_STOK(array(
                       "saldoawal" => (float)$dataSaldoAwal,
                        "qtyin" =>  $stokKembali,
                        "qtyout" => 0,
                        "saldoakhir" =>  (float)$dataSaldoAwal + (float)$stokKembali,
                        "keterangan" => 'Hapus Pemakaian Ruangan No. ' . $item5->nostruk . ' Pada produk : ' . $item5->namaproduk,
                        "produkfk" => $item5->objectprodukfk,
                        "ruanganfk" => $item5->objectruanganfk,
                        "tglinput" =>  date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $request['nostruk'],
                        "norectransaksi" => null,
                        "stokprodukdetailfk" => null,
                        "tabletransaksi" => 'strukpelayanan_t',
                        "flagfk" => null,
                    ));
                }

            } else {
                DB::rollBack();
                $result = [
                    "status" => 400,
                    "message"  => 'Gagal',
                    "as" => 'as@epic',
                ];
                return $this->respond($result, $result['status'], $result['message']);
            }
            
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Berhasil Hapus Data',
                "as" => 'as@epic',
            );

        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Gagal Hapus Data',
                "resp" => $e->getMessage(),
            );
        };

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getPemakaianStokRuanganByNorec(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $dataStruk = DB::table('strukpelayanan_t as sp')
        ->leftJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->select(
            'sp.tglstruk',
            'sp.nostruk',
            'pg.namalengkap',
            'pg.id as pgid',
            'sp.objectruanganfk',
            'ru.namaruangan',
            'sp.norec',
            'sp.nofaktur',
            'sp.tglfaktur',
            'sp.totalhargasatuan as total',
            'sp.keteranganlainnya'
        )
        ->where('sp.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sp.norec', '=', $request['norec']);
        }

        $dataStruk = $dataStruk->first();

        $data = DB::table('strukpelayanan_t as sp')
        ->JOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
        ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        ->leftJOIN('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
        ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
        ->leftJOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
        ->select(
            'sp.nostruk',
            'spd.strukterimafk',
            'spd.hargasatuan',
            'spd.qtyproduk',
            'sp.objectruanganfk',
            'ru.namaruangan',
            'spd.objectprodukfk as produkfk',
            'pr.namaproduk',
            'spd.hasilkonversi as nilaikonversi',
            'spd.objectsatuanstandarfk',
            'ss.satuanstandar',
            'spd.satuanstandar as satuanviewfk',
            'ss.satuanstandar as ssview',
            'spd.qtyproduk as jumlah',
            'spd.hargadiscount',
            'spd.hargappn',
            'spd.hargasatuan',
            'spd.objectasalprodukfk',
            'ap.asalproduk',
            'spd.persendiscount',
            'spd.persenppn',
            'spd.keteranganlainnya',
            'spd.nobatch',
            'spd.tglkadaluarsa',
            'kp.id as kpid',
            'kp.kelompokproduk'
        )
        ->where('sp.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('sp.norec', '=', $request['norec']);
        }
        $data = $data->get();
        $pelayananPasien = [];
        $i = 0;
        foreach ($data as $item) {
            $i = $i + 1;
            $pelayananPasien[] = array(
                'no' => $i,
                'nostrukterimafk' => $item->strukterimafk,
                'hargasatuan' => $item->hargasatuan,
                'ruanganfk' => $item->objectruanganfk,
                'asalprodukfk' =>  $item->objectasalprodukfk,
                'asalproduk' =>  $item->asalproduk,
                'produkfk' => $item->produkfk,
                'namaproduk' => $item->namaproduk,
                'nilaikonversi' => $item->nilaikonversi,
                'satuanstandarfk' => $item->satuanviewfk,
                'satuanstandar' => $item->ssview,
                'satuanviewfk' => $item->satuanviewfk,
                'satuanview' => $item->ssview,
                'jumlah' => $item->jumlah,
                'hargadiscount' => $item->hargadiscount,
                'persendiscount' => $item->persendiscount,
                'ppn' => $item->hargappn,
                'persenppn' => $item->persenppn,
                'total' => ((float)$item->hargasatuan - (float)$item->hargadiscount + (float)$item->hargappn) * $item->jumlah,
                'keterangan' => $item->keteranganlainnya,
                'nobatch' => $item->nobatch,
                'tglkadaluarsa' => $item->tglkadaluarsa,
                'kpid' => $item->kpid,
                'kelompokproduk' => $item->kelompokproduk,
            );
        }

        $result = array(
            'struk' => $dataStruk,
            'details' => $pelayananPasien,
            'message' => 'as@epic',
        );
        
        return $this->respond($result);
    }

    public function saveAdjustmentStok(Request $request)
    {

        DB::beginTransaction();
        try {
            $newSPD = StokProdukDetail::where('norec', $request['norec_spd'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('nostrukterimafk', $request['nostruterimafk'])
                        ->where('objectruanganfk', $request['ruanganfk'])
                        ->where('objectprodukfk', $request['produkfk'])
                        ->first();

            $noClosing = $this->generateCode(new StrukClosing, 'noclosing', 10, 'AS/' . $this->getDateTime()->format('ym'), $this->kdProfile);
            $dataSC = new StrukClosing();
            $dataSC->norec = $dataSC->generateNewId();
            $dataSC->kdprofile = $this->kdProfile;
            $dataSC->statusenabled = true;
            $dataSC->objectpegawaidiclosefk = $this->getPegawaiId();
            $dataSC->objectkelompoktransaksifk = $this->kelompokTransaksi('ADJUSTMENT STOK');
            $dataSC->keteranganlainnya = 'Adjusment Stok ' . $request['namaRuangan'];
            $dataSC->noclosing = $noClosing;
            $dataSC->objectruangandiclosefk = $request['ruanganfk'];
            $dataSC->objectruanganfk = $request['ruanganfk'];
            $dataSC->tglclosing = date('Y-m-d H:i:s');
            $dataSC->save();
            $norecSC = $dataSC->norec;

            $dataSPD = new StokProdukDetailAdjusment();
            $dataSPD->norec = $dataSPD->generateNewId();
            $dataSPD->kdprofile = $this->kdProfile;
            $dataSPD->statusenabled = true;
            $dataSPD->objectasalprodukfk = $newSPD->objectasalprodukfk;
            $dataSPD->hargadiscount = 0;
            $dataSPD->harganetto1 = $request['hargaad'];
            $dataSPD->harganetto2 = $request['hargaad'];
            $dataSPD->persendiscount = 0;
            $dataSPD->objectprodukfk = $request['produkfk'];
            $dataSPD->qtyprodukreal = $request['qtyreal'];
            $dataSPD->qtyproduksystem = $request['qtyad'];
            $dataSPD->qtyprodukadjusment = $request['qtyad'];
            $dataSPD->objectruanganfk = $request['ruanganfk'];
            $dataSPD->noclosingfk = $norecSC;
            $dataSPD->nostrukterimafk = $newSPD->nostrukterimafk;
            $dataSPD->norec_spd = $request['norec_spd'];
            $dataSPD->save();
            $dataSpdAD = $dataSPD->norec;

            $saldoAwal = StokProdukDetail::where('objectruanganfk',$request['ruanganfk'])->where('objectprodukfk',$request['produkfk'])
                                    ->where('kdprofile',$this->kdProfile)->where('statusenabled',true)
                                    ->sum('qtyproduk');
            if ($saldoAwal == 0) {
                $this->kartu_STOK(array(
                    "saldoawal" => (float)$saldoAwal,
                    "qtyin" =>  $request['qtyad'],
                    "qtyout" => 0,
                    "saldoakhir" =>  (float)$saldoAwal +  (float)$request['qtyad'],
                    "keterangan" => 'Adjusment Stok Ruangan. ' . $request['namaruangan'] . ' Pada Produk : ' . $request['namaproduk'],
                    "produkfk" => $request['produkfk'],
                    "ruanganfk" =>  $request['ruanganfk'],
                    "tglinput" =>  date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $newSPD->nostrukterimafk,
                    "norectransaksi" =>  $newSPD->norec,
                    "stokprodukdetailfk" =>  $newSPD->norec,
                    "tabletransaksi" => 'stokprodukdetail_t',
                    "flagfk" => null,
                ));
            } elseif ($request['qtyreal'] < $request['qtyad']){
                $Selisih = (float) $request['qtyad'] - (float) $request['qtyreal'];
                $statusssss = 0;
                $hasilSelisih = 0;
                $saldoAwalR = 0;
                $jumlahR = 0;
                if ($Selisih < 0) {
                    $statusssss = 0;
                    $selisih = (float)$Selisih * (-1);
                } else {
                    $statusssss = 1;
                    $selisih = (float)$Selisih;
                }
                if ($statusssss == 0) {
                    $jumlahR = $selisih;
                    $saldoAwalR = (float)$saldoAwal - (float)$selisih;
                } else {
                    $jumlahR = $selisih;
                    $saldoAwalR = (float)$selisih + (float)$saldoAwal;
                }

                $this->kartu_STOK(array(
                    "saldoawal" => (float)$saldoAwal,
                    "qtyin" => (float)$Selisih,
                    "qtyout" => 0,
                    "saldoakhir" =>  (float)$saldoAwal +  (float)$Selisih,
                    // "saldoakhir" =>  (float)$request['qtyad'],
                    "keterangan" => 'Adjusment Stok Ruangan. ' . $request['namaruangan'] . ' Pada Produk : ' . $request['namaproduk'],
                    "produkfk" => $request['produkfk'],
                    "ruanganfk" =>  $request['ruanganfk'],
                    "tglinput" =>  date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $newSPD->nostrukterimafk,
                    "norectransaksi" =>  $newSPD->norec,
                    "stokprodukdetailfk" =>  $newSPD->norec,
                    "tabletransaksi" => 'stokprodukdetail_t',
                    "flagfk" => null,
                ));
            }
            else {
                $Selisih = (float) $request['qtyad'] - (float) $request['qtyreal'];
                $statusssss = 0;
                $hasilSelisih = 0;
                $saldoAwalR = 0;
                $jumlahR = 0;
                if ($Selisih < 0) {
                    $statusssss = 0;
                    $selisih = (float)$Selisih * (-1);
                } else {
                    $statusssss = 1;
                    $selisih = (float)$Selisih;
                }
                if ($statusssss == 0) {
                    $jumlahR = $selisih;
                    $saldoAwalR = (float)$saldoAwal - (float)$selisih;
                } else {
                    $jumlahR = $selisih;
                    $saldoAwalR = (float)$selisih + (float)$saldoAwal;
                }

                $this->kartu_STOK(array(
                    "saldoawal" => (float)$saldoAwal,
                    "qtyin" => 0,
                    "qtyout" => $jumlahR,
                    // "saldoakhir" =>  (float)$saldoAwal -  (float)$request['qtyad'],
                    "saldoakhir" =>  (float)$saldoAwalR,
                    "keterangan" => 'Adjusment Stok Ruangan. ' . $request['namaruangan'] . ' Pada Produk : ' . $request['namaproduk'],
                    "produkfk" => $request['produkfk'],
                    "ruanganfk" =>  $request['ruanganfk'],
                    "tglinput" =>  date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $newSPD->nostrukterimafk,
                    "norectransaksi" =>  $newSPD->norec,
                    "stokprodukdetailfk" =>  $newSPD->norec,
                    "tabletransaksi" => 'stokprodukdetail_t',
                    "flagfk" => null,
                ));
            }

            StokProdukDetail::where('norec', $newSPD->norec)
                ->where('kdprofile', $this->kdProfile)
                ->update(['qtyproduk' => $request['qtyad'],
                          'harganetto1'=> $request['hargaad'],
                          'harganetto2'=> $request['hargaad']
                ]);

            $this->LOGGING(
                "Adjusment Stok Ruangan",
                 $dataSpdAD,
                "stokprodukdetailadjustment_t",
                "Adjustemen Stok Pada Produk : " . $request['namaproduk'] . " dengan Qty : " . $request['qtyad'] .
                ", Pada Ruangan : " . $request['namaruangan'] . ", BY : " . $this->getNamaPegawai()
            );

            DB::commit();
            $result = array(
                "status" => 200,
                "message"  => 'Berhasil Simpan',
                "as" => 'ea@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "error" => $e->getMessage(),
                "message"  => "Gagal Simpan",
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);

       
    }
    public function getDataGridSR(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $data = DB::table('stokprodukdetail_t as spd')
            ->JOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'spd.nostrukterimafk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->JOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->LEFTJOIN('rekanan_m as rk', 'rk.id', '=', 'sp.objectrekananfk')
            ->select(
                'spd.objectruanganfk',
                'pr.kdproduk as kdsirs',
                'pr.namaproduk',
                'ap.asalproduk',
                'ss.satuanstandar',
                'spd.tglkadaluarsa',
                'spd.nobatch',
                'rk.namarekanan',
                'spd.harganetto1 as harga',
                'ru.namaruangan',
                DB::raw("sum(spd.qtyproduk) as qtyproduk")
            )
            ->where('pr.statusenabled', true)
            ->where('spd.statusenabled', true)
            ->where('pr.kdprofile', $idProfile)
            ->where('spd.kdprofile', $idProfile)
            ->where('spd.qtyproduk', '>', 0);

            if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
                $data = $data->where(function($query) use ($request) {
                    $query->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%')
                          ->orWhere('pr.kdproduk', 'ilike', '%' . $request['namaproduk'] . '%');
                });
            }
        if (isset($request['idruangan'])  && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('spd.objectruanganfk', '=', $request['idruangan']);
        }
        if (isset($request['idasalproduk']) && $request['v'] != "" && $request['idasalproduk'] != "undefined") {
            $data = $data->where('spd.objectasalprodukfk', '=', $request['idasalproduk']);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
            $data = $data->where('pr.id', '=', $request['idproduk']);
        }
        $data = $data->groupBy(
            'spd.objectruanganfk',
            'pr.kdproduk',
            'pr.namaproduk',
            'ap.asalproduk',
            'ss.satuanstandar',
            'spd.tglkadaluarsa',
            'spd.nobatch',
            'spd.harganetto1',
            'ru.namaruangan',
            'rk.namarekanan',
        );
        $data = $data->get();
     
        return $this->respond($data);
    }

}
