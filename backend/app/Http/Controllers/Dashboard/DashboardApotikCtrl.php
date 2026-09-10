<?php

namespace App\Http\Controllers\Dashboard;

use App\Datatrans\Pegawai;
use App\Http\Controllers\Controller;
use App\Models\Master\HubunganKeluarga;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai as MasterPegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianApotik;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukResep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Exception;

class DashboardApotikCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDaftarOrder(Request $request)
    {
        $tglBetween = [$request->tglAwal, $request->tglAkhir];
        //var_dump($request['norec_so']);
        $fromStrukResep = DB::table('strukresep_t as sr')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m AS ps',  'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJoin('antrianapotik_t as aa', 'aa.noregistrasi', '=', 'pd.noregistrasi')
            ->leftJoin('statuspengerjaan_m as sp', 'sp.id', '=', 'sr.status')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->select(
                'sr.norec as norec_order',
                'pd.norec as norec_pd',
                'sr.noresep as noorder',
                // DB::raw("' ' as noorder"),
                'sr.norec as norec_resep',
                'apd.norec as norec_apd',
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.noidentitas',
                'ps.namapasien',
                'sr.tglresep AS tglorder',
                'aa.noantri',
                'jk.jeniskelamin',
                'jk.id as idJK',
                'kbs.name as kebangsaan',
                'sr.namalengkapambilresep AS namapengambilorder',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ru.namaruangan as namaruanganrawat',
                 DB::raw("
                    CASE 
                        WHEN ru.namaruangan ILIKE '%VIP%' THEN 1
                        WHEN aa.noantri IS NOT NULL THEN 2
                        ELSE 3
                    END as nourut
                "),
                'ru2.namaruangan',
                'sr.ruanganfk as objectruangantujuanfk',
                'sr.noresep',
                'aa.jenis',
                'sp.statuspengerjaan AS statusorder',
                'sp.id as pengerjaanfk',
                'pd.nostruklastfk',
                'pa.nosep',
                DB::raw("NULL AS noruangan,
                ps.tgllahir::date,
                sr.tglambilresep::date AS tglambilorder"),
                'sr.cito as cito',
                'sr.isrutin as rutin',
                'sr.isbpl as bpl',

            )
            ->where('sr.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('sr.statusenabled', true)
            ->whereNull('sr.orderfk');

        if (isset($request['tglAwal']) && isset($request['tglAkhir'])) {
            $fromStrukResep = $fromStrukResep->whereBetween(DB::raw("CAST(sr.tglresep as DATE)"), $tglBetween);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $fromStrukResep = $fromStrukResep->where('sr.ruanganfk', $request['ruanganfk']);
        }
        if (isset($request['objectruanganfk']) && $request['objectruanganfk'] != '') {
            $fromStrukResep = $fromStrukResep->where('apd.objectruanganfk', $request['objectruanganfk']);
        }
        if (isset($request['norec_so']) && $request['norec_so'] != '') {
            $fromStrukResep = $fromStrukResep->join('strukorder_t as so', 'sr.orderfk', '=', 'so.norec');
            $fromStrukResep = $fromStrukResep->where('so.norec', $request['norec_so']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $fromStrukResep = $fromStrukResep->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $fromStrukResep = $fromStrukResep->where('sp.id', $request['statusorder']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $fromStrukResep = $fromStrukResep->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $fromStrukResep = $fromStrukResep->offset($request['offset']);
        }
        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));

        $fromStrukOrder = DB::table('strukorder_t as so')
            ->join('pasien_m as ps', 'ps.id', '=', 'so.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftjoin('strukresep_t as sr', function ($join) {
                $join->on('sr.orderfk', '=', 'so.norec');
                $join->where('sr.statusenabled', true);
            })
            ->leftjoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftJoin('antrianapotik_t as aa', 'aa.noregistrasi', '=', 'pd.noregistrasi')
            ->leftjoin('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec');
                $join->on('apd.objectruanganfk', '=', 'so.objectruanganfk');
            })
            ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.statusorder')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->select(
                'so.norec as norec_order',
                'pd.norec as norec_pd',
                'so.noorder',
                'sr.norec as norec_resep',
                'apd.norec as norec_apd',
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.noidentitas',
                'ps.namapasien',
                'so.tglorder',
                'aa.noantri',
                'jk.jeniskelamin',
                'jk.id as idJK',
                'kbs.name as kebangsaan',
                'so.namapengambilorder',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ru.namaruangan as namaruanganrawat',
                DB::raw("
                    CASE 
                        WHEN ru.namaruangan ILIKE '%VIP%' THEN 1
                        WHEN aa.noantri IS NOT NULL THEN 2
                        ELSE 3
                    END as nourut
                "),
                'ru2.namaruangan',
                'so.objectruangantujuanfk',
                'sr.noresep',
                'aa.jenis',
                'sp.statuspengerjaan as statusorder',
                'sp.id as pengerjaanfk',
                'pd.nostruklastfk',
                'pa.nosep',
                DB::raw("so.nourutruangan  AS noruangan,
                ps.tgllahir::date,
                so.tglambilorder::date AS tglambilorder"),
                'so.cito as cito',
                'so.isrutin as rutin',
                'so.isbpl as bpl',

            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('so.objectkelompoktransaksifk',  $set->objectkelompoktransaksifk)
            ->where('so.statusenabled', true)
            ->unionAll($fromStrukResep)
            ->orderBy('nourut')
            ->orderBy('noantri', 'asc');

        if (isset($request['tglAwal']) && isset($request['tglAkhir'])) {
            $fromStrukOrder = $fromStrukOrder->whereBetween(DB::raw("CAST(so.tglorder as DATE)"), $tglBetween);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $fromStrukOrder = $fromStrukOrder->where('so.objectruangantujuanfk', $request['ruanganfk']);
        }
        if (isset($request['objectruanganfk']) && $request['objectruanganfk'] != '') {
            $fromStrukOrder = $fromStrukOrder->where('apd.objectruanganfk', $request['objectruanganfk']);
        }
        if (isset($request['norec_so']) && $request['norec_so'] != '') {
            $fromStrukOrder = $fromStrukOrder->where('so.norec', $request['norec_so']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $fromStrukOrder = $fromStrukOrder->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm)
                    ->orWhere('so.noorder', 'ilike', $searchTerm);
            });
        }
        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $fromStrukOrder = $fromStrukOrder->where('sp.id', $request['statusorder']);
        }
        $total = $fromStrukOrder->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $fromStrukOrder = $fromStrukOrder->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $fromStrukOrder = $fromStrukOrder->offset($request['offset']);
        }
        $fromStrukOrder = $fromStrukOrder->orderBy('tglorder')->get();
        if ($fromStrukOrder->isEmpty()) {
            return $this->respond(['total' => 0, 'data' => []]); // Jika tidak ada data
        }
        
        // Sorting cito true to be on top using sortByDesc
        $fromStrukOrder = $fromStrukOrder->sortByDesc('cito')->values();

        $diagnosaPasien = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->select(DB::raw("dg.kddiagnosa || ' - ' || dg.namadiagnosa as diagnosa,pd.norec as norec_pd"))
            ->where('dp.kdprofile', $this->kdProfile)
            ->where('dp.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as DATE)"), $tglBetween)
            ->where('ddp.objectjenisdiagnosafk', 1)
            ->get();

        $jenis = '';
        foreach ($fromStrukOrder as $item) {

            if ($item->jenis == 'B') {
                $jenis = 'Racikan';
            } else if ($item->jenis == 'A') {
                $jenis = 'Non Racikan';
            } else {
                $jenis = '-';
            }
            $item->noantri = $item->jenis . '-' . $item->noantri;
            $item->jenis = $jenis;
            $diagnosis = [];
            foreach ($diagnosaPasien as $diagnosa) {
                if ($item->norec_pd == $diagnosa->norec_pd) {
                    $diagnosis[] = $diagnosa;
                }
            }
            $item->diagnosa = $diagnosis;
        }
        $result = ['total' => $total, 'data' => $fromStrukOrder];
        return $this->respond($result);
    }


    public function getDetailOrder(Request $request)
    {
        $dataAsalProduk = DB::table('asalproduk_m as ap')->select('ap.id', 'ap.asalproduk')->get()->keyBy('id');
        $dataSigna = DB::table('stigma')->select('id', 'name')->get()->keyBy('id');
        $noorder = $request->get('noorder', '');

        $dataStruk = DB::table('strukorder_t as so')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select('so.noorder', 'pg.id as pgid', 'pg.namalengkap', 'ru.id', 'ru.namaruangan', 'so.tglorder', 'so.cito')
            ->where('so.kdprofile', $this->kdProfile)
            ->when($noorder, fn($q) => $q->where('so.noorder', '=', $noorder))
            ->first();

        if (empty($dataStruk)) {
            $dataStruk = DB::table('strukresep_t as so')
                ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.penulisresepfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'so.ruanganfk')
                ->select(DB::raw("so.noresep AS noorder, pg.id as pgid, pg.namalengkap, ru.id, ru.namaruangan, so.tglresep AS tglorder, 'false' AS cito"))
                ->where('so.kdprofile', $this->kdProfile)
                ->when($noorder, fn($q) => $q->where('so.noresep', '=', $noorder))
                ->first();
        }

        $data = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('jeniskemasan_m as jk', 'jk.id', '=', 'op.jeniskemasanfk')
            ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'op.routefk')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->leftJoin('rm_sediaan_m as sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'op.objectsatuanstandarfk')
            ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'op.satuanviewfk')
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'op.satuanresepfk')
            ->select(
                'op.norec as norec_op',
                'so.noorder',
                'op.hargasatuan',
                'op.qtystokcurrent',
                'so.objectruangantujuanfk',
                'ru.namaruangan',
                'op.rke',
                'op.jeniskemasanfk',
                'jk.id as jkid',
                'jk.jeniskemasan',
                'op.aturanpakai',
                'op.routefk',
                'rt.name as namaroute',
                'op.objectprodukfk',
                'pr.namaproduk',
                'op.hasilkonversi',
                'op.objectsatuanstandarfk',
                'ss.satuanstandar',
                'op.satuanviewfk',
                'ss2.satuanstandar as ssview',
                'op.qtyproduk',
                'op.hargadiscount',
                'op.hasilkonversi',
                'op.qtystokcurrent',
                'op.dosis',
                'op.jenisobatfk',
                'op.hargasatuan',
                'op.hargadiscount',
                'pr.kekuatan',
                'sdn.name as sediaan',
                'op.ispagi',
                'op.issiang',
                'op.ismalam',
                'op.issore',
                'op.keteranganpakai',
                'op.satuanresepfk',
                'sn.satuanresep',
                'op.tglkadaluarsa',
                'so.isreseppulang',
                'so.tglorder',
                // 'op.iter'
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->when($noorder, fn($q) => $q->where('so.noorder', $noorder))
            ->get();

        if ($data->isEmpty()) {
            $data = DB::table('strukresep_t as so')
                ->join('pelayananpasien_t AS pp', 'pp.strukresepfk', '=', 'so.norec')
                ->join('ruangan_m as ru', 'ru.id', '=', 'so.ruanganfk')
                ->leftJoin('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
                ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
                ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->leftJoin('rm_sediaan_m as sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
                ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
                ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
                ->select(DB::raw("
                    so.noresep AS noorder,pp.norec as norec_op,pp.hargasatuan,0 AS qtystokcurrent,so.ruanganfk AS objectruangantujuanfk,ru.namaruangan,
                    pp.rke,pp.jeniskemasanfk,jk.id AS jkid,jk.jeniskemasan,pp.aturanpakai,pp.routefk,
                    rt.name AS namaroute,pp.produkfk AS objectprodukfk,
                    pr.namaproduk,pp.nilaikonversi AS hasilkonversi,pp.satuanviewfk AS objectsatuanstandarfk,ss.satuanstandar,
                    pp.satuanviewfk,ss2.satuanstandar AS ssview,pp.jumlah AS qtyproduk,pp.hargadiscount,pp.dosis,pp.jenisobatfk,pp.hargasatuan,
                    pr.kekuatan,sdn.name AS sediaan,pp.ispagi,pp.issiang,pp.ismalam,pp.issore,pp.keteranganpakai,pp.satuanresepfk,
                    sn.satuanresep,pp.tglkadaluarsa,so.isreseppulang,so.tglresep AS tglorder
                "))
                ->where('so.kdprofile', $this->kdProfile)
                ->when($noorder, fn($q) => $q->where('so.noresep', $noorder))
                ->get();
        }

        $dataStok = DB::table('stokprodukdetail_t as spd')
            ->join('strukpelayanan_t as sk', 'sk.norec', 'spd.nostrukterimafk')
            ->select('sk.norec', 'spd.objectprodukfk', 'sk.tglstruk', 'spd.objectasalprodukfk', 'spd.harganetto2 as hargajual', 'spd.hargadiscount', 
                    DB::raw("sum(spd.qtyproduk) as qtyproduk"), 'spd.objectruanganfk')
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.statusenabled', true)
            ->where('spd.objectruanganfk', $dataStruk->id)
            ->groupBy('sk.norec', 'spd.objectprodukfk', 'sk.tglstruk', 'spd.objectasalprodukfk', 'spd.harganetto2', 'spd.hargadiscount', 'spd.objectruanganfk')
            ->orderBy('sk.tglstruk')
            ->get()
            ->keyBy('objectprodukfk');

        // Assemble 'orderPelayanan' with calculated fields
        $orderPelayanan = [];
        $tarifAdmin = $this->settingFix('tarifadminresep');
        $rke = '0';
        
        foreach ($data as $i => $item) {
            $tarifjasa = $item->jkid == 1 ? $tarifAdmin * (ceil($item->qtyproduk / 20) ?: 1) : $tarifAdmin;
            $stock = $dataStok[$item->objectprodukfk] ?? null;
            $asalproduk = $dataAsalProduk[$stock->objectasalprodukfk ?? null]->asalproduk ?? null;

            $orderPelayanan[] = [
                'no' => $i + 1,
                'norec_op' => $item->norec_op,
                'noregistrasifk' => '',
                'tglregistrasi' => '',
                'generik' => null,
                'hargajual' => round($item->hargasatuan, 0),
                'stock' => $stock->qtyproduk ?? 0,
                'jenisobatfk' => $item->jenisobatfk,
                'kelasfk' => '',
                'harganetto' => round($item->hargadiscount, 0),
                'nostrukterimafk' => $stock->norec,
                'ruanganfk' => $item->objectruangantujuanfk,
                'aturanpakaifk' => $dataSigna[$item->aturanpakai]->id ?? 0,
                'aturanpakai' => $item->aturanpakai,
                'rke' => $rke++,
                'jeniskemasanfk' => $item->jeniskemasanfk,
                'jeniskemasan' => $item->jeniskemasan,
                'jumlah' => round($item->qtyproduk),
                'jmlstok' => $item->qtystokcurrent,
                'jumlahobat' => $item->qtyproduk,
                'kekuatan' => $item->kekuatan,
                'dosis' => $item->dosis,
                'jmldosis' => (string)$item->qtyproduk / $item->dosis . '/' . (string)$item->dosis,
                'namaproduk' => $item->namaproduk,
                'satuanviewfk' => $item->satuanviewfk,
                'satuanview' => $item->ssview,
                'harga' => round($item->hargasatuan),
                'kekuatan' => $item->kekuatan,
                'hargasatuan' => round($item->hargasatuan, 0),
                'hargadiscount' => $stock->hargadiscount ?? 0,
                'sediaan' => $item->sediaan,
                'satuanresepfk' => $item->satuanresepfk,
                'satuanresep' => $item->satuanresep,
                'tglkadaluarsa' => $item->tglkadaluarsa,
                'ispagi' => $item->ispagi,
                'issiang' =>  $item->issiang,
                'ismalam' =>  $item->ismalam,
                'issore' =>  $item->issore,
                'asalprodukfk' => $stock->objectasalprodukfk,
                'asalproduk' => $asalproduk,
                'total' => round($item->hargasatuan * $item->qtyproduk) - $item->hargadiscount + $tarifjasa,
                'satuanstandarfk' => $item->satuanviewfk, //objectsatuanstandarfk,
                'satuanstandar' => $item->ssview, //satuanstandar,
                'routefk' => $item->routefk,
                'route' => $item->namaroute,
                "keterangan" => $item->keteranganpakai,
                "isreseppulang" => $item->isreseppulang,
            ];
        }

        $result = array(
            'strukorder' => $dataStruk,
            'orderpelayanan' => $orderPelayanan,
        );
        return $this->respond($result);
    }


    public function getDetailOrderOLD(Request $request)
    {

        $dataAsalProduk = DB::table('asalproduk_m as ap')
            ->select('ap.id', 'ap.asalproduk')
            ->get();
        $dataSigna = DB::table('stigma')
            ->select('id', 'name')
            ->get();
        $dataStruk = [];
        $dataStruk = DB::table('strukorder_t as so')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select('so.noorder', 'pg.id as pgid', 'pg.namalengkap', 'ru.id', 'ru.namaruangan', 'so.tglorder', 'so.cito')
            ->where('so.kdprofile', $this->kdProfile);

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $dataStruk = $dataStruk->where('so.noorder', '=', $request['noorder']);
        }
        $dataStruk = $dataStruk->first();

        if (empty($dataStruk)) {
            $dataStruk = DB::table('strukresep_t as so')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.penulisresepfk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.ruanganfk')
                ->select(DB::raw("
                    so.noresep AS noorder,pg.id as pgid,pg.namalengkap,ru.id,ru.namaruangan,so.tglresep AS tglorder,'false' AS cito
                    "))
                ->where('so.kdprofile', $this->kdProfile);

            if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
                $dataStruk = $dataStruk->where('so.noresep', '=', $request['noorder']);
            }
            $dataStruk = $dataStruk->first();
        }


        $data = [];
        $data = DB::table('strukorder_t as so')
            ->JOIN('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftJOIN('jeniskemasan_m as jk', 'jk.id', '=', 'op.jeniskemasanfk')
            ->leftJOIN('routefarmasi as rt', 'rt.id', '=', 'op.routefk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->leftJOIN('rm_sediaan_m as sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'op.objectsatuanstandarfk')
            ->leftJOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'op.satuanviewfk')
            ->leftJOIN('satuanresep_m as sn', 'sn.id', '=', 'op.satuanresepfk')
            ->select(
                'op.norec as norec_op',
                'so.noorder',
                'op.hargasatuan',
                'op.qtystokcurrent',
                'so.objectruangantujuanfk',
                'ru.namaruangan',
                'op.rke',
                'op.jeniskemasanfk',
                'jk.id as jkid',
                'jk.jeniskemasan',
                'op.aturanpakai',
                'op.routefk',
                'rt.name as namaroute',
                'op.objectprodukfk',
                'pr.namaproduk',
                'op.hasilkonversi',
                'op.objectsatuanstandarfk',
                'ss.satuanstandar',
                'op.satuanviewfk',
                'ss2.satuanstandar as ssview',
                'op.qtyproduk',
                'op.hargadiscount',
                'op.hasilkonversi',
                'op.qtystokcurrent',
                'op.dosis',
                'op.jenisobatfk',
                'op.hargasatuan',
                'op.hargadiscount',
                'pr.kekuatan',
                'sdn.name as sediaan',
                'op.ispagi',
                'op.issiang',
                'op.ismalam',
                'op.issore',
                'op.keteranganpakai',
                'op.satuanresepfk',
                'sn.satuanresep',
                'op.tglkadaluarsa',
                'so.isreseppulang',
                'so.tglorder',
                // 'op.iter'
            )
            ->where('so.kdprofile', $this->kdProfile);

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('so.noorder', $request['noorder']);
        }
        $data = $data->get();
        if (count($data) == 0) {
            $data = DB::table('strukresep_t as so')
                ->JOIN('pelayananpasien_t AS pp', 'pp.strukresepfk', '=', 'so.norec')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.ruanganfk')
                ->leftJOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
                ->leftJOIN('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->leftJOIN('rm_sediaan_m as sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
                ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
                ->leftJOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'pp.satuanviewfk')
                ->leftJOIN('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
                ->select(DB::raw("
                so.noresep AS noorder,pp.norec as norec_op,pp.hargasatuan,0 AS qtystokcurrent,so.ruanganfk AS objectruangantujuanfk,ru.namaruangan,
                pp.rke,pp.jeniskemasanfk,jk.id AS jkid,jk.jeniskemasan,pp.aturanpakai,pp.routefk,
                rt.name AS namaroute,pp.produkfk AS objectprodukfk,
                pr.namaproduk,pp.nilaikonversi AS hasilkonversi,pp.satuanviewfk AS objectsatuanstandarfk,ss.satuanstandar,
                pp.satuanviewfk,ss2.satuanstandar AS ssview,pp.jumlah AS qtyproduk,pp.hargadiscount,pp.dosis,pp.jenisobatfk,pp.hargasatuan,
                pr.kekuatan,sdn.name AS sediaan,pp.ispagi,pp.issiang,pp.ismalam,pp.issore,pp.keteranganpakai,pp.satuanresepfk,
                sn.satuanresep,pp.tglkadaluarsa,so.isreseppulang,so.tglresep AS tglorder
            "))
                ->where('so.kdprofile', $this->kdProfile);

            if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
                $data = $data->where('so.noresep', '=', $request['noorder']);
            }
            $data = $data->get();
        }

        $orderPelayanan = [];
        $i = 0;

        $dataStok = DB::table('stokprodukdetail_t as spd')
            ->join('strukpelayanan_t as sk', 'sk.norec', 'spd.nostrukterimafk')
            ->select(
                'sk.norec',
                'spd.objectprodukfk',
                'sk.tglstruk',
                'spd.objectasalprodukfk',
                'spd.harganetto2 as hargajual',
                'spd.harganetto2 as harganetto',
                'spd.hargadiscount',
                DB::raw("sum(spd.qtyproduk) as qtyproduk"),
                'spd.objectruanganfk'
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.statusenabled', true)
            ->where('spd.objectruanganfk', $dataStruk->id)
            ->groupBy('sk.norec', 'spd.objectprodukfk', 'sk.tglstruk', 'spd.objectasalprodukfk', 'spd.harganetto2', 'spd.hargadiscount', 'spd.objectruanganfk')
            ->orderBy('sk.tglstruk')
            ->get();

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
        $rke = '0';
        $tarifAdmin =  $this->settingFix('tarifadminresep');
        foreach ($data as $item) {
            $i = $i + 1;
            $hargajual = 0;
            $harganetto = 0;
            $jmlstok = 0;
            $hargasatuan = 0;
            $hargadiscount = 0;
            $total = 0;

            $tarifjasa = 0;
            $qty20 = 0;
            if ($item->jkid == 2) {
                $tarifjasa = $tarifAdmin; //800;
            }
            if ($item->jkid == 1) {
                if ($rke != $item->rke) {
                    if ($item->qtyproduk > 20) {
                        $qty20 = number_format($item->qtyproduk / 20, 0);
                        if ($item->qtyproduk % 20 == 0) {
                            $qty20 = $qty20;
                        } else {
                            $qty20 = $qty20 + 1;
                        }
                        $tarifjasa = $tarifAdmin * $qty20;
                    } else {
                        $tarifjasa = $tarifAdmin;
                    }
                    $rke = $item->rke;
                }
            }
            $hargajual = round($item->hargasatuan, 0);
            $hargasatuan = round($item->hargasatuan, 0);
            $harganetto = round($item->hargadiscount, 0);

            foreach ($dataStok as $item2) {
                if ($item2->objectprodukfk == $item->objectprodukfk) {
                    if ($item2->qtyproduk > $item->qtyproduk * $item->hasilkonversi) {
                        $nostrukterimafk = $item2->norec;
                        $asalprodukfk = $item2->objectasalprodukfk;
                        $jmlstok = $item2->qtyproduk;
                        $hargadiscount = $item2->hargadiscount;
                        $total = (((float)ceil($item->qtyproduk) * ((float)$hargasatuan - (float)$hargadiscount)) * $item->hasilkonversi) + $tarifjasa;
                        break;
                    }
                }
            }
            foreach ($dataAsalProduk as $item3) {
                if ($asalprodukfk == $item3->id) {
                    $asalproduk = $item3->asalproduk;
                }
            }
            foreach ($dataSigna as $item4) {
                if ($item->aturanpakai == $item4->id) {
                    $aturanpakaifk = $item4->id;
                }
            }
            if ((float)$item->dosis == 0) {
                $item->dosis = 1;
            }

            $orderPelayanan[] = array(
                'no' => $i,
                'norec_op' => $item->norec_op,
                'noregistrasifk' => '',
                'tglregistrasi' => '',
                'generik' => null,
                'hargajual' => $hargajual,
                'jenisobatfk' => $item->jenisobatfk,
                'kelasfk' => '',
                'stock' => $jmlstok,
                'harganetto' => $harganetto,
                'nostrukterimafk' => $nostrukterimafk,
                'ruanganfk' => $item->objectruangantujuanfk,
                'rke' => $item->rke,
                'jeniskemasanfk' => $item->jeniskemasanfk,
                'jeniskemasan' => $item->jeniskemasan,
                'aturanpakaifk' => $aturanpakaifk,
                'aturanpakai' => $item->aturanpakai,
                'routefk' => $item->routefk,
                'route' => $item->namaroute,
                'asalprodukfk' => $asalprodukfk,
                'asalproduk' => $asalproduk,
                'produkfk' => $item->objectprodukfk,
                'namaproduk' => $item->namaproduk,
                'nilaikonversi' => $item->hasilkonversi,
                'satuanstandarfk' => $item->satuanviewfk, //objectsatuanstandarfk,
                'satuanstandar' => $item->ssview, //satuanstandar,
                'satuanviewfk' => $item->satuanviewfk,
                'satuanview' => $item->ssview,
                'jmlstok' => $item->qtystokcurrent,
                'jumlah' => ceil($item->qtyproduk),
                'jumlahobat' => $item->qtyproduk,
                'dosis' => $item->dosis,
                'kekuatan' => $item->kekuatan,
                'hargasatuan' => $hargasatuan,
                'hargadiscount' => $hargadiscount,
                'total' => $total,
                'sediaan' => $item->sediaan,
                'jmldosis' => (string)$item->qtyproduk / $item->dosis . '/' . (string)$item->dosis,
                //                'jmldosis' => (String)$item->qtyproduk,
                'jasa' => $tarifjasa,
                'ispagi' => $item->ispagi,
                'issiang' =>  $item->issiang,
                'ismalam' =>  $item->ismalam,
                'issore' =>  $item->issore,
                "keterangan" => $item->keteranganpakai,
                'satuanresepfk' =>  $item->satuanresepfk,
                "satuanresep" => $item->satuanresep,
                "tglkadaluarsa" => $item->tglkadaluarsa,
                "isreseppulang" => $item->isreseppulang,
                // "iter" => $item->iter,
            );
        }

        $result = array(
            'strukorder' => $dataStruk,
            'orderpelayanan' => $orderPelayanan,
        );
        return $this->respond($result);
    }

    public function getStokObat(Request $r)
    {
        $data  = DB::table('stokprodukdetail_t as spd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->select(
                'ru.namaruangan',
                'spd.objectruanganfk',
                'pr.namaproduk',
                DB::raw("sum(spd.qtyproduk) as qtyproduk"),
                'spd.objectprodukfk'
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.statusenabled', true)
            ->orderBy('pr.namaproduk', 'asc')
            ->groupBy('ru.namaruangan', 'spd.objectruanganfk', 'pr.namaproduk', 'spd.objectprodukfk');

        if (isset($r['objectprodukfk']) && $r['objectprodukfk'] != '') {
            $data = $data->where('spd.objectprodukfk', 'ilike', '%' . $r['objectprodukfk'] . '%');
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->where('spd.objectruanganfk', $r['ruanganfk']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function dataComboApotik()
    {

        $datas = Ruangan::select('id', 'namaruangan')
            ->where('objectdepartemenfk', $this->settingFix('idInstalasiFarmasi'))
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();

        $set = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $datasRuangan = Ruangan::select('id', 'namaruangan')
            ->where('objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->orWhere('objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->orWhere('objectdepartemenfk', $set)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();

        $apoteker = MasterPegawai::select('id', 'namalengkap')
            ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)
            ->where('objectjenispegawaifk', $this->settingFix('idJenisPegawaiApoteker'))
            ->get();

        $res['ruangan'] = $datas;
        $res['datasruangan'] = $datasRuangan;
        $res['apoteker'] = $apoteker;
        $res['hubunganKeluarga'] = HubunganKeluarga::mine()->get();

        return $this->respond($res);
    }

    public function countAllbyRoom(Request $request)
    {
        $datas = DB::select(DB::raw(
            "SELECT namaruangan,COUNT(id) AS total
                    FROM
                        (
                            (
                            SELECT
                                rng.namaruangan,
                                rng.id
                            FROM
                                strukresep_t AS sr
                                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
                                INNER JOIN ruangan_m AS rng ON rng.id = apd.objectruanganfk
                                WHERE sr.kdprofile = ?
                                AND sr.statusenabled = TRUE
                                AND CAST ( sr.tglresep AS DATE ) >= ?
                                AND CAST ( sr.tglresep AS DATE ) <= ?
                            ) UNION ALL
                            (
                            SELECT
                                rng.namaruangan,
                                rng.id
                            FROM
                                strukorder_t AS so
                                INNER JOIN ruangan_m AS rng ON rng.id = so.objectruanganfk
                            WHERE
                                so.kdprofile = ?
                                AND so.objectkelompoktransaksifk = ?
                                AND so.statusenabled = TRUE
                                AND CAST ( so.tglorder AS DATE ) >= ?
                                AND CAST ( so.tglorder AS DATE ) <= ?
                            )
                        ) AS foo
                    GROUP BY namaruangan, id"
        ), [
            $this->kdProfile, $request->tglAwal, $request->tglAkhir, $this->kdProfile,
            $this->settingFix('idPelayananApotik'), $request->tglAwal, $request->tglAkhir
        ]);

        $seriesRuangan = [];
        $labelRuangan = [];
        foreach ($datas as $d) {
            $seriesRuangan[] = $d->total;
            $labelRuangan[]  = $d->namaruangan;
        }

        $result['chartRNG']['count'] = $seriesRuangan;
        $result['chartRNG']['namaruangan'] = $labelRuangan;

        return $this->respond($result);
    }

    public function countOrderByStatus(Request $request)
    {
        $tglBetween = [$request->tglAwal, $request->tglAkhir];
        $kelompokPasien = $this->settingFix('idPelayananApotik');
        $datas = [
            'pending' =>  $this->countStatusPendingByDate($tglBetween, $kelompokPasien),
            'produksi' =>  $this->countStatusProduksiByDate($tglBetween, $kelompokPasien),
            'success' =>  $this->countStatusDoneByDate($tglBetween, $kelompokPasien),
            'allOrderNow' => $this->countAllbyDate($tglBetween, $kelompokPasien),
        ];

        return $this->respond($datas);
    }

    public function countStatusPendingByDate($request, $kelompokPasien)
    {
        $fromStrukResep = StrukResep::select('norec')
            ->where('status', 0)
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(tglresep as DATE)"), $request);

        $fromStrukOrder = StrukOrder::select('norec')
            ->where('statusorder', 0)
            ->where('statusenabled', true)
            ->where('objectkelompoktransaksifk', $kelompokPasien)
            ->where('kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(tglorder as DATE)"), $request)
            ->unionAll($fromStrukResep)
            ->count();
        return $fromStrukOrder;
    }

    public function countStatusProduksiByDate($request, $kelompokPasien)
    {
        $fromStrukResep = StrukResep::select('norec')
            ->where('status', 1)
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(tglresep as DATE)"), $request);

        $fromStrukOrder = StrukOrder::select('norec')
            ->where('statusorder', 1)
            ->where('statusenabled', true)
            ->where('objectkelompoktransaksifk', $kelompokPasien)
            ->where('kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(tglorder as DATE)"), $request)
            ->unionAll($fromStrukResep)
            ->count();

        return $fromStrukOrder;
    }

    public function countStatusDoneByDate($request, $kelompokPasien)
    {
        $fromStrukResep = StrukResep::select('norec')
            ->where('status', 3)
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(tglresep as DATE)"), $request);

        $fromStrukOrder = StrukOrder::select('norec')
            ->where('statusorder', 3)
            ->where('statusenabled', true)
            ->where('objectkelompoktransaksifk', $kelompokPasien)
            ->where('kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(tglorder as DATE)"), $request)
            ->unionAll($fromStrukResep)
            ->count();
        return $fromStrukOrder;
    }

    public function countAllbyDate($request, $kelompokPasien)
    {
        $fromStrukResep = StrukResep::select('norec')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->whereIn('status', [3])
            ->whereBetween(DB::raw("CAST(tglresep as DATE)"), $request);

        $fromStrukOrder = StrukOrder::select('norec')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('objectkelompoktransaksifk', $kelompokPasien)
            ->whereIn('statusorder', [0, 1, 2, 3, 4])
            ->unionAll($fromStrukResep)
            ->whereBetween(DB::raw("CAST(tglorder as DATE)"), $request)
            ->count();

        return $fromStrukOrder;
    }

    public function batalVerifikasi(Request $r)
    {
        DB::beginTransaction();
        try {
            $SR = StrukResep::where('norec', $r['norec'])->first();
            if ($SR->orderfk != null) {
                $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
                StrukOrder::where('norec', $SR->orderfk)->update(
                    [
                        'statusorder' => $set->statusorder,
                        'namapengambilorder' => null,
                        'tglambilorder' => null
                    ]
                );
                $SR->orderfk = null;
                AntrianApotik::where('noresep', $SR->noresep)->where('kdprofile', $this->kdProfile)->delete();
            }
            $SR->statusenabled = false;
            $SR->save();

            $pasien = Pasien::where('nocm', $r['nocm'])->where('kdprofile', $this->kdProfile)->first();
            $newPP = PelayananPasien::where('strukresepfk', $SR->norec)->where('kdprofile', $this->kdProfile)->get();
            $prod = [];

            foreach ($newPP as $r_PPL) {
                $prod[] =  $r_PPL->produkfk;

                $norec_PP = $r_PPL->norec;
                PelayananPasienDetail::where('pelayananpasien', $norec_PP)->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasienPetugas::where('pelayananpasien', $norec_PP)->where('kdprofile', $this->kdProfile)->delete();

                $qtyJumlah = (float)$r_PPL->jumlah;

                $dataSaldoAwal = DB::table("stokprodukdetail_t")
                    ->select(DB::raw("sum(qtyproduk) as qty"))
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $SR->ruanganfk)
                    ->where('objectprodukfk', $r_PPL->produkfk)
                    ->first();

                $saldoAwal = (float)$dataSaldoAwal->qty + $qtyJumlah;

                $jmlPengurang = (float)$qtyJumlah;

                StokProdukDetail::where('kdprofile', $this->kdProfile)
                    ->where('norec', $r_PPL->stokprodukdetailfk)
                    ->lockForUpdate()
                    ->increment('qtyproduk', (float)$jmlPengurang);

                $this->kartu_STOK(array(
                    "saldoawal" =>  (float)$dataSaldoAwal->qty,
                    "qtyin" => (float)$qtyJumlah,
                    "qtyout" => 0,
                    "saldoakhir" => (float) $saldoAwal,
                    "keterangan" => 'Hapus Resep Pelayanan Obat Alkes No. '  . $SR->noresep . ' ' . $r['namapasien'],
                    "produkfk" => $r_PPL->produkfk,
                    "ruanganfk" => $SR->ruanganfk,
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $r_PPL->nostrukterimafk,
                    "norectransaksi" => $r_PPL->norec,
                    "tabletransaksi" => 'pelayananpasien_t',
                    "stokprodukdetailfk" => $SR->stokprodukdetailfk,
                ));
            }
            PelayananPasien::where('strukresepfk', $SR->norec)->where('kdprofile', $this->kdProfile)->delete();
            $pro = DB::table('produk_m')->whereIn('id', $prod)->get();
            $ruangan = Ruangan::where('id', $SR->ruanganfk)->first();

            $mergeProduk = '';
            foreach ($pro as $value) {
                $mergeProduk = $mergeProduk . ", " . $value->namaproduk;
            }
            $mergeProduk = substr($mergeProduk, 1, strlen($mergeProduk) - 1);
            $this->LOGGING(
                'Hapus Resep',
                $SR->norec,
                'strukresep_t',
                'Hapus Resep ' . $SR->noresep .
                    $mergeProduk
                    . ' ruang ' . $ruangan->namaruangan
                    . ' pada Pasien ' .
                    $pasien->namapasien . ' (' .   $pasien->nocm . ')  '
            );
            $transMessage = "Sukses";
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
                "result"  => null, // $e->getMessage(). ' '. $e->getLine()

            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveStatusResepElektronik(Request $request)
    {
        DB::beginTransaction();
        try {
            if (isset($request['strukresep']) && $request['strukresep'] == '') {
                StrukOrder::where('noorder', $request['noorder'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'statusorder' => $request['statusorder'],
                            'namapengambilorder' => $request['namapengambil'],
                            'tglambilorder' => $request['tglambil'],
                            'objecthubungankeluargafk' => $request['hubunganKeluarga']
                        ]
                    );
                $data = StrukOrder::where('noorder', $request['noorder'])->where('kdprofile', $this->kdProfile)->first();
                $dt = StrukResep::where('orderfk', $data->norec)->where('kdprofile', $this->kdProfile)->first();

                AntrianApotik::where('noresep', $dt->noresep)
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'status' => $request['statusorder']
                        ]
                    );
            }
            if (isset($request['strukresep']) && $request['strukresep'] == true) {
                StrukResep::where('noresep', $request['noorder'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'status' => $request['statusorder'],
                            'namalengkapambilresep' => $request['namapengambil'],
                            'tglambilresep' => $request['tglambil'],
                            'objecthubungankeluargafk' => $request['hubunganKeluarga']
                        ]
                    );

                AntrianApotik::where('noresep', $request['noorder'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'status' => $request['statusorder']
                        ]
                    );
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Update Status Berhasil",
                "by" => 'as@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Simpan Gagal !",
                "by" => 'as@epic',
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function cetakResepObat(Request $request)
    {
        $kdProfile = $request['kdprofile'];
        $norec = $request['norec'];
        // $user = $request['user'];


        $data = DB::table('pelayananpasien_t as pp')
            ->leftjoin('antrianpasiendiperiksa_t AS apdp', 'pp.noregistrasifk', 'apdp.norec')
            ->leftjoin('pasiendaftar_t AS pd', 'apdp.noregistrasifk', 'pd.norec')
            ->leftjoin('pasien_m AS ps', 'pd.nocmfk', 'ps.id')
            ->leftjoin('alamat_m as al', 'al.nocmfk', 'ps.id')
            ->leftjoin('produk_m AS pr', 'pp.produkfk', 'pr.id')
            ->leftjoin('ruangan_m AS ru', 'apdp.objectruanganfk', 'ru.id')
            ->leftjoin('strukresep_t AS sr', 'pp.strukresepfk', 'sr.norec')
            ->leftjoin('ruangan_m AS ru2', 'sr.ruanganfk', 'ru2.id')
            ->leftjoin('jeniskemasan_m AS jnskem', 'pp.jeniskemasanfk', 'jnskem.id')
            ->leftjoin('pegawai_m AS pgw', 'sr.penulisresepfk', 'pgw.id')
            ->leftjoin('satuanstandar_m AS sstd', 'pp.satuanviewfk', 'sstd.id')
            ->leftjoin('jeniskelamin_m AS jk', 'ps.objectjeniskelaminfk', 'jk.id')
            ->leftjoin('kelompokpasien_m AS kpp', 'pd.objectkelompokpasienlastfk', 'kpp.id')
            ->leftJoin('rekanan_m as rek', 'rek.id', 'pd.objectrekananfk')
            ->leftJoin('satuanresep_m AS ssr', 'ssr.id', 'pp.satuanresepfk')
            ->select(
                'sr.noresep',
                'ps.namapasien',
                'ps.nohp',
                'al.alamatlengkap',
                'ru.namaruangan',
                'ps.nocm',
                'jk.jeniskelamin',
                DB::raw("CAST(ps.tgllahir as DATE),TO_CHAR( age( ps.tgllahir ), 'YY thn MM bln DD hr' ) AS umur,CAST(sr.tglresep as DATE)"),
                'pgw.namalengkap',
                DB::raw("CASE WHEN pgw.nosip IS NULL THEN '-' ELSE pgw.nosip END as nosip")
            )
            ->where('sr.norec', $request['norec'])
            ->first();

        $detail = DB::table('pelayananpasien_t as pp')
            ->leftjoin('antrianpasiendiperiksa_t AS apdp', 'pp.noregistrasifk', 'apdp.norec')
            ->leftjoin('pasiendaftar_t AS pd', 'apdp.noregistrasifk', 'pd.norec')
            ->leftjoin('pasien_m AS ps', 'pd.nocmfk', 'ps.id')
            ->leftjoin('alamat_m as al', 'al.nocmfk', 'ps.id')
            ->leftjoin('produk_m AS pr', 'pp.produkfk', 'pr.id')
            ->leftjoin('ruangan_m AS ru', 'apdp.objectruanganfk', 'ru.id')
            ->leftjoin('strukresep_t AS sr', 'pp.strukresepfk', 'sr.norec')
            ->leftjoin('ruangan_m AS ru2', 'sr.ruanganfk', 'ru2.id')
            ->leftjoin('jeniskemasan_m AS jnskem', 'pp.jeniskemasanfk', 'jnskem.id')
            ->leftjoin('pegawai_m AS pgw', 'sr.penulisresepfk', 'pgw.id')
            ->leftjoin('satuanstandar_m AS sstd', 'pp.satuanviewfk', 'sstd.id')
            ->leftjoin('jeniskelamin_m AS jk', 'ps.objectjeniskelaminfk', 'jk.id')
            ->leftjoin('kelompokpasien_m AS kpp', 'pd.objectkelompokpasienlastfk', 'kpp.id')
            ->leftJoin('rekanan_m as rek', 'rek.id', 'pd.objectrekananfk')
            ->leftJoin('satuanresep_m AS ssr', 'ssr.id', 'pp.satuanresepfk')
            ->select(DB::raw("
                        pd.noregistrasi,ps.nocm,ps.namapasien,jk.jeniskelamin,kpp.kelompokpasien || ' ( ' || rek.namarekanan || ' ) ' AS penjamin,rek.namarekanan,
                        ps.nohp,al.alamatlengkap,CAST(ps.tgllahir as DATE),TO_CHAR( age( ps.tgllahir ), 'YY thn MM bln DD hr' ) AS umur,
                        pd.tglregistrasi,ru.namaruangan AS ruanganpasien,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tglPelayanan,
	                    pp.rke,pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
                        CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END as jasa,pp.hargasatuan,CAST(sr.tglresep as DATE),
                        pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END as aturanpakai,
                        CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END as totaldiscount, pp.jumlah AS qtyorder,
                        (pp.jumlah * (pp.hargasatuan - (CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount end ))) + CASE WHEN
                        pp.jasa IS NULL THEN 0 ELSE pp.jasa END as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip,
                        ((pp.jumlah * pp.hargasatuan) - (CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END)) + CASE WHEN
                        pp.jasa IS NULL THEN 0 ELSE pp.jasa END as totalbiaya,pp.qtydetailresep

                  "))
            ->where('sr.norec', $request['norec'])
            // ->groupBy('jeniskemasan')
            ->get();

        $result = [
            'pasien' => $data,
            'detail' => $detail
        ];
        // return $result ;
        // $norec = $request['norec'];
        // $detail = collect(DB::select("
        // SELECT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk,jk.jeniskelamin AS jk,
        // kpp.kelompokpasien || ' ( ' || rek.namarekanan || ' ) ' AS penjamin,ps.nohp AS noteleponfaks,
        // al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        // '-' AS alergi,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tgl,pp.rke,pr. ID AS kdproduk,
        // pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
        // CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa,pp.hargasatuan,to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep,pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai,
        // pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip ,
        // CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,
        // ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,pp.qtydetailresep,
        // CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END AS diskon
        // FROM pelayananpasien_t AS pp
        // INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        // INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        // INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        // left join alamat_m as al on al.nocmfk=ps.id
        // INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        // INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        // INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        // INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        // INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        // INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        // INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        // INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        // INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        // left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        // WHERE sr.norec='$norec' order by pp.rke asc
        // "));

        // return $this->respond($detail);


        $pageWidth = 950;

        $dataReport = array(
            'datas' => $data,
            'detail' => $detail

        );

        // dd($dataReport['detail']);
        return view(
            'report.apotik.cetak-resep-obat-biasa',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
}
