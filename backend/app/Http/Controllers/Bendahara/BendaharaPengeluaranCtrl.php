<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalProduk;
use App\Models\Master\CaraBayar;
use App\Models\Master\CaraSetor;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukBuktiPengeluaranCaraBayar;
use App\Models\Transaksi\StrukClosing;
use App\Models\Transaksi\StrukCollecting;
use App\Models\Transaksi\StrukCollectingDetail;
use App\Models\Transaksi\StrukHistori;
use App\Models\Transaksi\StrukPelayanan;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class BendaharaPengeluaranCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDaftarTagihanSuplier(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        $kdTransaksiBK = (int) $this->settingFix('kdTransaksiBK');
        $kdPembayaran = (int) $this->settingFix('kdPembayaranSup');
        $tglAwal = $request['tglAwal'] . ' 00:00:00';
        $tglAkhir = $request['tglAkhir'] . ' 23:59:59';

        $Supplier = ' ';
        if (isset($request['rekanan']) && $request['rekanan'] != "" && $request['rekanan'] != "undefined") {
            // $Supplier = " and rkn.namarekanan LIKE '%".$request['Supplier']."%'";
            $Supplier = " and rkn.id =" . $request['rekanan'];
        }

        $NoFaktur = ' ';
        if (isset($request['NoFaktur']) && $request['NoFaktur'] != "" && $request['UserId'] != "undefined") {
            $NoFaktur = " and sp.nofaktur LIKE '%" . $request['NoFaktur'] . "%'";
        }

        $NoStruk = ' ';
        if (isset($request['NoStruk']) && $request['NoStruk'] != "" && $request['NoStruk'] != "undefined") {
            $NoStruk = " and sp.nostruk LIKE '%" . $request['NoStruk'] . "%'";
        }

        $status = ' ';
        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined") {
            $statusTea = $request['status'];
            $status =  'where su.status = ' . "'$statusTea'";
        }

        // if(isset($r['KetSetor']) && $r['KetSetor'] != "" && $r['KetSetor'] != "undefined") {
        //     if ($r['KetSetor'] == 1){
        //         $data = $data->whereNull('sc.noclosing');
        //     }elseif ($r['KetSetor'] == 2){
        //         $data = $data->whereNotNull('sc.noclosing');
        //     }
        // }

        $dataPegawaiUser = DB::select(
            DB::raw("select pg.id,pg.namalengkap from loginuser_s as lu
                INNER JOIN pegawai_m as pg on lu.objectpegawaifk=pg.id
                where lu.kdprofile = $idProfile and lu.id=:idLoginUser"),
            array(
                'idLoginUser' => $request['userData']['id'],
            )
        );

        $listNorec = ' ';
        if (isset($request['listNorec']) && $request['listNorec'] != "" && $request['listNorec'] != "undefined") {
            $arrNorec = explode(',', $request['listNorec']);
            $norec = '';
            foreach ($arrNorec as $ob) {
                $norec = $norec . ",'" . $ob . "'";
            }
            $norec = substr($norec, 1, strlen($norec) - 1);
            $listNorec = ' AND sp.norec in (' . $norec . ")";
        }

        $listNotInNorec = ' ';
        if (isset($request['NorecFalse']) && $request['NorecFalse'] != "" && $request['NorecFalse'] != "undefined") {
            $arrNorec = explode(',', $request['NorecFalse']);
            $norec = '';
            foreach ($arrNorec as $ob) {
                $norec = $norec . ",'" . $ob . "'";
            }
            $norec = substr($norec, 1, strlen($norec) - 1);

            if ($request['KetCollecting'] == 'collectingkeun') {
                $listNotInNorec = ' AND sp.norec not in (' . $norec . ")";
            } elseif ($request['KetCollecting'] == 'editCollecting') {
                $dataSCD = collect(DB::select("
                    select * from strukcollectingdetail_t where kdprofile = $idProfile and statusenabled = true and strukcollectingfk in ($norec)
                "));

                $norecSCD = '';
                foreach ($dataSCD as $dot) {
                    $norecSCD = $norecSCD . ",'" . $dot->nostrukterimafk . "'";
                }
                $norecSCD = substr($norecSCD, 1, strlen($norecSCD) - 1);
                $listNotInNorec = ' AND sp.norec not in (' . $norecSCD . ")";
            }
        }



        $results = array();
        $data = DB::select(DB::raw(
            "select * from(
                            select xx.norec,xx.tglstruk,xx.tgldokumen,xx.tgljatuhtempo,xx.rknid,xx.namarekanan,xx.nostruk,
                                   xx.nodokumen,xx.nopo,xx.nosbk,xx.noverifikasi,xx.total,xx.totalppn,xx.totaldiskon,xx.subtotal,xx.sisautang,
                                   (CASE WHEN xx.totalbayar = 0 THEN 'BELUM LUNAS' WHEN xx.totalbayar <> 0 and xx.totalbayar > 0 
                                   and xx.sisautang <> 0 THEN 'BELUM LUNAS' WHEN xx.totalbayar = xx.subtotal OR xx.sisautang = 0 THEN 'LUNAS'
                                   ELSE 'BELUM LUNAS' END) as status,xx.tglsbk,xx.partnercode,xx.nomorreferencebri,
                                  xx.bankrekeningnomor,
                                   xx.objectrekananfk,xx.nocollecting,xx.totalbayar
                            FROM(SELECT x.norec,x.tglstruk,x.tgldokumen,x.tgljatuhtempo,x.rknid,x.namarekanan,x.nostruk,x.nodokumen,x.nopo,
                                        (x.totalppn) as totalppn,(x.totaldiskon) as totaldiskon,(x.total) as total,
                                        ((x.total-x.totaldiskon)+x.totalppn) as subtotal,x.totaldibayar as totalbayar,
                                        (case when x.totalsisahutang is null then ((x.total-x.totaldiskon)+x.totalppn)
                                        ELSE x.totalsisahutang end) as sisautang,x.nosbk,x.noverifikasi,x.tglsbk,x.partnercode,x.nomorreferencebri,
                                        x.bankrekeningnomor,x.objectrekananfk,
                                        x.nocollecting
                            FROM (SELECT sp.norec,sp.tglstruk,sp.tglfaktur as tgldokumen,sp.nostruk,sp.nosppb as nopo,sp.nofaktur as nodokumen,
                            SUM(spd.qtyproduk) as qty,sp.totalhargasatuan as total,
                            sp.totalppn as totalppn,sp.totaldiscount as totaldiskon,
                            sp.totalsudahdibayar as totaldibayar,
                            sp.totalsudahdibayar as totalsudahdibayar,
                            sbk.nosbk,sv.noverifikasi,sp.tgljatuhtempo,rkn.id as rknid,rkn.namarekanan,
                            sp.totalbelumdibayar as totalsisahutang,sbk.tglsbk,rkn.kodeexternal as partnercode,sp.nomorreferencebri,
                           rkn.bankrekeningnomor,sp.objectrekananfk,sc.nocollecting
                            from strukpelayanan_t as sp
                            inner join strukpelayanandetail_t as spd on spd.nostrukfk = sp.norec
                            left join rekanan_m as rkn on rkn.id = sp.objectrekananfk
                            left join strukbuktipengeluaran_t as sbk on sbk.norec = sp.nosbklastfk and sbk.objectkelompoktransaksifk =$kdPembayaran and sbk.statusenabled = true
                            left join strukverifikasi_t as sv on sv.norec = spd.noverifikasifk
                            left join ruangan_m as ru on ru.id = sv.objectruanganfk
                            left join strukcollecting_t AS sc ON sc.norec = sp.strukcollectingfk
                            where sp.kdprofile = $idProfile and sp.objectkelompoktransaksifk= $kdTransaksiBK and sp.tglfaktur BETWEEN '$tglAwal' and '$tglAkhir'
                            $Supplier
                            $NoFaktur
                            $NoStruk
                            $status
                            GROUP BY sp.norec,sp.tglstruk,sp.tglfaktur,sp.nostruk,sp.nosppb,sp.nofaktur,sp.nosbklastfk,sbk.totaldibayar,
                                     sbk.totalsudahdibayar,sp.tgljatuhtempo,rkn.id,rkn.namarekanan,sbk.nosbk,sv.noverifikasi,
                                     sbk.totalsisahutang,sbk.tglsbk,
                                     rkn.kodeexternal,sp.nomorreferencebri,
                                    rkn.bankrekeningnomor,sp.objectrekananfk,sc.nocollecting) as x) as xx) as su;"
        ));



        foreach ($data as $item) {
            $results[] = array(
                'tglstruk' => $item->tglstruk,
                'tgldokumen' => $item->tgldokumen,
                'tgljatuhtempo' => $item->tgljatuhtempo,
                'rknid' => $item->rknid,
                'namarekanan' => $item->namarekanan,
                'nostruk' => $item->nostruk,
                'nodokumen' => $item->nodokumen,
                'nopo' => $item->nopo,
                'nosbk' => $item->nosbk,
                'noverifikasi' => $item->noverifikasi,
                'total' => $item->total,
                'totalppn' => $item->totalppn,
                'totaldiskon' => $item->totaldiskon,
                'subtotal' => $item->subtotal,
                'sisautang' => $item->sisautang,
                'totalbayar' => $item->totalbayar,
                'status' => $item->status,
                'norec' => $item->norec,
                'tglsbk' => $item->tglsbk,
                'partnercode' => $item->partnercode,
                'nomorreferencebri' => $item->nomorreferencebri,
                'bankrekeningnomor' => $item->bankrekeningnomor,
                'objectrekananfk' => $item->objectrekananfk,
                'nocollecting' => $item->nocollecting,
            );
        }

        $totalAll = 0;
        $totalTagihan = 0;
        $totalBayar = 0;
        foreach ($data as $d) {
            $totalAll =    $totalAll + (float) $d->sisautang;
            $totalTagihan = $totalTagihan + (float) $d->subtotal;
            $totalBayar = $totalBayar + (float) $d->totalbayar;
        }

        $result = array(
            'daftar' => $results,
            'sisahutang' => $totalAll,
            'totalTagihan' => $totalTagihan,
            'totalbayar' => $totalBayar,
            'datalogin' => $dataPegawaiUser,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDetailTagihanSuplier(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;

        $NoStrukFk = $request['norec_sp'];

        $data = DB::select(DB::raw(
            "select spd.norec,pro.id as kdproduk,pro.kdproduk as kdsirs,pro.namaproduk,
                ss.id as  ssid,ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargappn,
			    spd.hargadiscount,((spd.hargasatuan*spd.qtyproduk) - (spd.hargadiscount)) + spd.hargappn as subtotal,
                asp.id as aspid,asp.asalproduk
                from strukpelayanan_t as sp
                inner join strukpelayanandetail_t as spd on spd.nostrukfk = sp.norec
                inner join produk_m as pro on pro.id = spd.objectprodukfk
                left join asalproduk_m as asp on asp.id = spd.objectasalprodukfk
                left join satuanstandar_m as ss on ss.id = spd.objectsatuanstandarfk
                left join rekanan_m as rkn on rkn.id = sp.objectrekananfk
                left join strukbuktipengeluaran_t as sbk on sbk.norec = sp.nosbklastfk
                left join strukverifikasi_t as sv on sv.norec = spd.noverifikasifk
                left join ruangan_m as ru on ru.id = sv.objectruanganfk
                where sp.kdprofile = $idProfile and spd.nostrukfk='$NoStrukFk'
                order by pro.namaproduk asc;"
        ));
        $result = array(
            'data' => $data,
            'message' => '@epic'
        );
        return $this->respond($result);
    }

    public function getRiwayatPembayaran(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $NoStruk = $request['nostruk'];
        $NoFaktur = $request['nodokumen'];

        $data = DB::select(DB::raw(
            "select sp.norec as norec_sp,sbk.norec as norec_sbk,sp.nostruk,sp.nofaktur as nodokumen,sbk.nosbk,
                sp.tglstruk,sp.tglfaktur as tgldokumen,sbk.tglsbk,sbk.pembayaranke,sp.tgljatuhtempo,
                SUM(spd.qtyproduk) as qty,SUM(spd.hargasatuan) as total,
                SUM(spd.hargappn) as totalppn,SUM(spd.hargadiscount) as totaldiskon,
                ((SUM(spd.hargasatuan*spd.qtyproduk)+ SUM(spd.hargappn)-SUM(spd.hargadiscount))) as totalharusdibayar,
                coalesce(sbk.totalsisahutang,0) as totalsisahutang,
                coalesce(sbk.totaldibayar,0) as totaldibayar,
                coalesce(sbk.totalsudahdibayar,0) as totalsudahdibayar,
                coalesce(sbk.totaldibayarbefore,0) as totaldibayarbefore
                from strukbuktipengeluaran_t as sbk
                left join strukpelayanan_t as sp on sp.norec = sbk.nostrukfk
                left join strukpelayanandetail_t as spd on spd.nostrukfk = sp.norec
                left join rekanan_m as rkn on rkn.id = sp.objectrekananfk
                left join kelompoktransaksi_m as kt on kt.id = sbk.objectkelompoktransaksifk
                left join pegawai_m as pg on pg.id = sbk.objectpegawaipembayarfk
                left join ruangan_m as ru on ru.id = sbk.objectruanganfk
                where sbk.kdprofile = $kdProfile and sp.nostruk='$NoStruk' 
                GROUP BY sp.norec,sbk.norec,sp.nostruk,sp.nofaktur,sbk.nosbk,
                         sp.tglstruk,sp.tglfaktur,sbk.tglsbk,sbk.pembayaranke,
                         sp.tgljatuhtempo,sbk.totalsisahutang,sbk.totaldibayar,
                         sbk.totalsudahdibayar,sbk.totaldibayarbefore;"
        ));

        $result = array(
            'data' => $data,
            'message' => '@epic'
        );
        return $this->respond($result);
    }

    public function detailRekanan(Request $request)
    {

        $data = DB::table('rekanan_m')
            ->select('bankrekeningnama', 'bankrekeningnomor', 'bankrekeningatasnama')
            ->where('id', $request->input('idrekanan'))
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', '=', 'true')
            ->first();

        return $this->respond($data);
    }

    public function daftarBKUPengeluaran(Request $request)
    {
        $kdProfile = (int)$this->kdProfile;
        $idProfile = (int) $kdProfile;
        $list =  explode(',', $this->settingFix('KdTransaksiBendaharaPenerimaan', $idProfile));

        $kdTrans = [];
        $KdTransak = $this->settingFix('KdTransaksiBendaharaPenerimaan', $idProfile);
        foreach ($list as $itemTrans) {
            $kdTrans[] =  (int)$itemTrans;
        }
        $dataPenerimaanBank = DB::table('strukhistori_t as sh')
            ->join('strukclosing_t as sc', 'sc.norec', '=', 'sh.noclosing')
            ->leftjoin('strukbuktipenerimaan_t as spp', function ($join) {
                $join->on('spp.noclosingfk', '=', 'sc.norec')
                    ->where('spp.keteranganlainnya', 'ilike', '%' . 'Setoran' . '%')
                    ->where('spp.keteranganlainnya', '<>', 'BKO');
            })
            ->leftjoin('strukbuktipengeluaran_t as sbk', function ($join) {
                $join->on('sbk.noclosingfk', '=', 'sc.norec')
                    ->where('sbk.keteranganlainnya', '<>', 'BKO');
            })
            ->leftjoin('loginuser_s as lu', 'lu.id', '=', 'spp.objectpegawaipenerimafk')
            ->leftjoin('loginuser_s as lu2', 'lu2.id', '=', 'sbk.objectpegawaipembayarfk')
            ->leftjoin('pegawai_m as p', 'p.id', '=', 'lu.objectpegawaifk')
            ->leftjoin('pegawai_m as p2', 'p2.id', '=', 'lu2.objectpegawaifk')
            ->leftjoin('pegawai_m as psetor', 'psetor.id', '=', 'sh.objectpegawaitarikdepositfk')
            ->join('kelompoktransaksi_m as kt', 'kt.id', '=', 'sc.objectkelompoktransaksifk')
            ->leftjoin('asalproduk_m AS ap', 'ap.id', '=', 'sh.objectasalprodukhasilfk')
            ->select(
                'spp.norec',
                'spp.tglsbm',
                'spp.keteranganlainnya',
                'spp.nosbm_intern',
                'spp.objectpegawaipenerimafk',
                'p.namalengkap',
                'kt.kelompoktransaksi',
                'spp.nostrukfk',
                'sc.objectkelompoktransaksifk',
                'sc.norec as norec_sc',
                // 'spc.objectbankaccountfk','ba.bankaccountnama','spc.namabankprovider','spc.namapemilik',
                'sc.noclosing',
                'sh.nonhistori',
                'sbk.objectpegawaipembayarfk',
                'p2.namalengkap as pegawaibayar',
                'sh.ketlainya',
                'sh.norec as norec_sh',
                'sh.kdperkiraan',
                'sh.namaperkiraan',
                'sh.kettransaksi',
                'sh.nobukti',
                'psetor.namalengkap as penyetor',
                DB::raw("
                    CASE WHEN sc.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
                    INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
                    WHERE idbku IN (3) AND ks.statusenabled = TRUE) THEN COALESCE ((spp.totaldibayar), 0) END AS debit,
                    CASE WHEN sc.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
                    INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
                    WHERE idbku IN (2) AND ks.statusenabled = TRUE) THEN coalesce((sbk.totaldibayar ), 0) END AS kredit,
                   
                    cast(sh.tglsetortarikdeposit as date)as tglsetortarikdeposit,sc.objectkelompoktransaksifk,
                    sh.objectasalprodukhasilfk,ap.asalproduk,sh.norec AS norec_sh,sc.norec AS norec_sc")
            )
            ->groupBy(
                'spp.norec',
                'spp.tglsbm',
                'spp.keteranganlainnya',
                'spp.nosbm_intern',
                'spp.objectpegawaipenerimafk',
                'p.namalengkap',
                'kt.kelompoktransaksi',
                'spp.nostrukfk',
                'sc.objectkelompoktransaksifk',
                'sc.norec',
                'sc.noclosing',
                'sh.nonhistori',
                'sbk.objectpegawaipembayarfk',
                'p2.namalengkap',
                'sh.ketlainya',
                'sh.norec',
                'sh.kdperkiraan',
                'sh.namaperkiraan',
                'sh.kettransaksi',
                'sh.nobukti',
                'psetor.namalengkap',
                'spp.totaldibayar',
                'sbk.totaldibayar',
                'spp.nosbm',
                'sbk.nosbk',
                'sh.tglsetortarikdeposit',
                'sc.tglclosing',
                'sc.objectkelompoktransaksifk',
                'sh.objectasalprodukhasilfk',
                'ap.asalproduk',
                'sh.norec',
                'sc.norec'
            )
            ->orderBy('sc.tglclosing', 'asc')
            ->where('sh.kdprofile', $idProfile)
            ->where('sh.statusenabled', true)
            // ->whereRaw('','BKO')
            ->whereRaw("sh.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
                    INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
                    WHERE idbku IN (3,2) AND ks.statusenabled = TRUE)");
        // ->whereIn('sh.objectkelompoktransaksifk','=',1);
        //            ->whereIn('sh.objectkelompoktransaksifk',[60,64,70,105,106,119,120]);

        $filter = $request->all();


        if (isset($filter['dari']) && $filter['dari'] != "" && $filter['dari'] != "undefined") {
            $dataPenerimaanBank =  $dataPenerimaanBank->where('sh.tglsetortarikdeposit', '>=', $filter['dari'] . " 00:00:00");
        }
        if (isset($filter['sampai']) && $filter['sampai'] != "" && $filter['sampai'] != "undefined") {
            $dataPenerimaanBank = $dataPenerimaanBank->where('sh.tglsetortarikdeposit', '<=',  $filter['sampai'] . ' 23:59:59');
        }
        if (isset($filter['nohistoris']) && $filter['nohistoris'] != "" && $filter['nohistoris'] != "undefined") {
            $dataPenerimaanBank = $dataPenerimaanBank->where('sh.nonhistori', $filter['nohistoris']);
        }
        if (isset($filter['keterangan']) && $filter['keterangan'] != "" && $filter['keterangan'] != "undefined") {
            $dataPenerimaanBank = $dataPenerimaanBank->where('sh.ketlainya', 'ilike', '%' . $filter['keterangan'] . '%');
        }

        //        $dataPenerimaanBank = $dataPenerimaanBank->distinct();
        $dataPenerimaanBank = $dataPenerimaanBank->get();



        /** @Function_ Ambil Saldo Satu Bulan Sebelum */
        $explode =  explode("-", $filter['dari']);
        $arr1 = $explode[0];
        $arr2 = $explode[1];
        if ($arr2 == 1) {
            $arr2 = $explode[1];
        } else {
            $arr2 = $explode[1] - 1; //bulan kurangi 1
        }
        $arr3 = '01'; //ambil tgl 1
        $arr2 = str_pad($arr2, 2, '0', STR_PAD_LEFT);
        //        $arrayExplode = array($arr1,$arr2,$arr3);
        //        $tglMinSabulan = implode("-", $arrayExplode);
        $tglMinSabulan = Carbon::parse($filter['dari'])->subMonth(1)->startOfMonth()->addDay(1)->format('Y-m-01'); // $arr1 . '-' . $arr2 . '-' . $arr3;
        /** @End_Function */

        $tglAwal = $filter['dari'];
        $dataSaldo = DB::select(DB::raw(" select sh.tglsetortarikdeposit,
                     coalesce((spp.totaldibayar ), 0) debit ,
                     coalesce((sbk.totaldibayar ), 0) kredit           
                     from strukhistori_t as sh
                     inner join strukclosing_t as sc on sc.norec = sh.noclosing 
                     left join strukbuktipenerimaan_t as spp on spp.noclosingfk = sc.norec
                     left join strukbuktipengeluaran_t as sbk on sbk.noclosingfk = sc.norec
                     inner join kelompoktransaksi_m as kt on kt.id = sc.objectkelompoktransaksifk
                     inner join mapbkutokelompoktransaksi_m as mbk on mbk.kelompoktransaksifk = kt.id
                     where sh.kdprofile = $idProfile and sh.tglsetortarikdeposit > '$tglMinSabulan' and sh.tglsetortarikdeposit < '$tglAwal'
                       and sh.statusenabled=true
                       and sh.objectkelompoktransaksifk in (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
                           INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
                           WHERE idbku IN (3,2) AND ks.statusenabled = TRUE)
                     order by sh.nonhistori asc"));
        $saldolama = 0;
        $jmlSaldoLama = 0;
        $saldo = 0;
        if (count($dataSaldo) > 0) {
            foreach ($dataSaldo as $dataSaldoLama) {
                if ($dataSaldoLama->debit == 0) {
                    $saldolama = $saldolama + (float)$dataSaldoLama->debit - (float)$dataSaldoLama->kredit;
                } else {
                    $saldolama = $saldolama + (float)$dataSaldoLama->debit;
                }
                $jmlSaldoLama = $saldolama;
            }
            $saldo = $jmlSaldoLama;
        }

        $result = array();
        $jumlahD = 0;
        $jumlahK = 0;
        $saldoAkhir = 0;

        foreach ($dataPenerimaanBank as $dataPenerimaan) {
            /** @Function_ Non TUNAI Ambil */
            $norec = $dataPenerimaan->norec_sc;
            $dataPenerimaan->nontunai = 0;
            $SBMC = DB::select(DB::raw("
                 select  spc.noclosingfk,spc.carabayarfk,cb.carabayar,
                 coalesce((spc.totaldibayar ), 0) totaldibayar
                 from strukclosingkasir_t as spc 
                 join carabayar_m as cb on cb.id = spc.carabayarfk
                 where spc.kdprofile = $idProfile and spc.noclosingfk ='$norec'
            "));

            if (count($SBMC) > 0) {
                $tunai = 0;
                $nonTunai = 0;
                foreach ($SBMC as $itemSbmc) {
                    if ($itemSbmc->carabayarfk == "1") { //TUNAI
                        $tunai = $tunai + (float) $itemSbmc->totaldibayar;
                    } else {
                        $nonTunai = $nonTunai +  (float)$itemSbmc->totaldibayar;
                    }
                }

                $dataPenerimaan->debit = $tunai;
                $dataPenerimaan->nontunai = $nonTunai;
            }
            /** @End_Function */
            if ($dataPenerimaan->debit == 0) {
                $saldo = $saldo + (float)$dataPenerimaan->debit - (float)$dataPenerimaan->kredit;
            } else {
                $saldo = $saldo + (float)$dataPenerimaan->debit;
            }
            $jumlahD = $jumlahD + (float)$dataPenerimaan->debit;
            $jumlahK = $jumlahK +  (float)$dataPenerimaan->kredit;
            $saldoAkhir = $saldo;
            //            if ($dataPenerimaan->nostrukfk != null){
            //                $status = 'Sudah Di Kompensasi';
            //            }else{
            //                $status = '-';
            //            }
            $result[] = array(
                'norec_sh'  => $dataPenerimaan->norec_sh,
                'norec_sc' => $dataPenerimaan->norec_sc,
                // 'noStruk'  => $dataPenerimaan->norec,
                'tglStruk'  => $dataPenerimaan->tglsetortarikdeposit,
                'keterangan'  => $dataPenerimaan->ketlainya,
                'jenisTransaksi'  => $dataPenerimaan->kelompoktransaksi,
                'idJenisTransaksi'  => $dataPenerimaan->objectkelompoktransaksifk,
                'kredit'  => (float) $dataPenerimaan->kredit,
                'debit'  => (float)$dataPenerimaan->debit,
                'saldo'  => $saldo,
                'nontunai'  => $dataPenerimaan->nontunai,
                'nohistori' => $dataPenerimaan->nonhistori,
                // 'notransaksi' => $dataPenerimaan->notransaksi,
                'nobukti' => $dataPenerimaan->nobukti,
                'kdperkiraan' => $dataPenerimaan->kdperkiraan,
                'namaperkiraan' => $dataPenerimaan->namaperkiraan,
                'kettransaksi' => $dataPenerimaan->kettransaksi,
                'penyetor' => $dataPenerimaan->penyetor,
                'asalprodukfk' => $dataPenerimaan->objectasalprodukhasilfk,
                'asalproduk' => $dataPenerimaan->asalproduk,

            );
        }
        $uhman = array(
            'data' =>  $result,
            'saldolama' =>  $jmlSaldoLama,
            'dataawal' => $dataSaldo,
            'jumlahD' => $jumlahD,
            'jumlahK' => $jumlahK,
            'saldoAkhir' => $saldoAkhir,
            'tglmin' => $tglMinSabulan,
            // 'tglmin_sebulan'=> Carbon::parse($filter['dari'])->subMonth(1)->startOfMonth()->addDay(1)->format('Y-m-d'),
            'message' => "@epic"
        );
        return $this->respond($uhman);
    }

    public function saveBayarTagihanSuplier(Request $request)
    {

        $kdProfile = (int)$this->kdProfile;
        $idProfile = (int) $kdProfile;

        DB::beginTransaction();
        $datastruk = DB::select(
            DB::raw("select * from strukpelayanan_t where kdprofile = $idProfile and norec=:nostruk"),
            array(
                'nostruk' => $request['sbk']['nostruk'],
            )
        );
        $totaldibayarbefore = 0;
        $totalsudahdibayar = 0;
        $sisautang = 0;
        $pembayaranke = 0;
        try {

            $SC = new StrukClosing();
            $SC->norec  = $SC->generateNewId();
            $SC->kdprofile = $this->kdProfile;
            $SC->statusenabled = true;
            $SC->noclosing = $this->generateCode(new StrukClosing, 'noclosing', 10, 'C-' . $this->getDateTime()->format('ym'), $this->kdProfile);
            $SC->objectpegawaidiclosefk = $this->getUserId();
            $SC->tglclosing = $request['sbk']['tglsbk'];
            $SC->totaldibayar = $request['sbk']['totalbayar'];
            $SC->objectkelompoktransaksifk = 107;
            $SC->keteranganlainnya = "Pembayaran Tagihan Suplier";
            $SC->tglawal = $this->getDateTime()->format('Y-m-d H:i:s');
            $SC->tglakhir = $this->getDateTime()->format('Y-m-d H:i:s');
            $SC->tglclosing = $this->getDateTime()->format('Y-m-d H:i:s');
            $SC->save();
            $NorecSc = $SC->norec;

            $SH = new StrukHistori();
            $SH->norec  = $SH->generateNewId();
            $SH->nonhistori = $this->generateCode(new StrukHistori(), 'nonhistori', 14, 'SK-' . $this->getDateTime()->format('ym'), $this->kdProfile);
            //$SH->objectbankaccountfk= $item['kdAccountBank'];
            $SH->kdprofile = $this->kdProfile;
            $SH->statusenabled = true;
            $SH->totalsetortarikdeposit = $request['sbk']['totalbayar'];
            $SH->tglsetortarikdeposit = $request['sbk']['tglsbk']; //$this->getDateTime();
            $SH->objectpegawaitarikdepositfk = $this->getUserId();
            $SH->objectpegawaiterimafk = $this->getUserId();
            $SH->objectkelompoktransaksifk = 107;
            $SH->noclosing = $NorecSc; //$SC->noclosing;
            // $SH->objectcarasetorfk = $item['idCaraSetor'];
            $SH->ketlainya = 'Pembayaran Tagihan Suplier';
            if (isset($idRuangan)) {
                $SH->objectruanganterimafk = $idRuangan;
                $SH->objectruanganfk = $idRuangan;
            }

            $SH->nobukti = '-';
            $SH->kdperkiraan = '-'; //$input['kdperkiraan'];
            $SH->namaperkiraan = 'Pembayaran Tagihan Suplier'; //$input['keterangan'];
            $SH->kettransaksi = '-'; // $input['keterangantransaksi'];
            $SH->save();


            foreach ($datastruk as $items) {
                if ($items->nosbklastfk == null || $items->nosbklastfk == '') {
                    $totaldibayarbefore = 0;
                    $totalsudahdibayar = 0;
                    $sisautang = $request['sbk']['sisautang'];
                    // return $sisautang;
                    $pembayaranke = 1;
                } else {
                    // return 'b';
                    $datasbk = DB::select(
                        DB::raw("select * from strukbuktipengeluaran_t where kdprofile = $idProfile and norec=:nostruk"),
                        array(
                            'nostruk' => $items->nosbklastfk,
                        )
                    );
                    $pembayaranke = (float) count($datasbk) + 1;
                    foreach ($datasbk as $Hits) {
                        $totaldibayarbefore = $Hits->totaldibayar;
                        $totalsudahdibayar = $Hits->totaldibayar;
                        $sisautang = $Hits->totalsisahutang - $request['sbk']['totalbayar'];
                        // $sisautang = $request['sbk']['sisautang'];
                    }
                    // return  $sisautang;
                }


                if ($request['sbk']['nosbk'] == '') {
                    $dataSBK = new StrukBuktiPengeluaran();
                    $dataSBK->norec = $dataSBK->generateNewId();
                    $dataSBK->kdprofile = $idProfile;
                    $dataSBK->statusenabled = true;
                    $dataSBK->nosbk = $this->generateCode(new StrukBuktiPengeluaran(), 'nosbk', 14, 'PV-' . $this->getDateTime()->format('ym'), $idProfile);
                } else {
                    $dataSBK = StrukBuktiPengeluaran::where('norec', $request['sbk']['nosbk'])->where('kdprofile', $idProfile)->first();

                    $delSBKCB = StrukBuktiPengeluaranCaraBayar::where('nosbkfk', $request['sbk']['nosbk'])->where('kdprofile', $idProfile)
                        ->delete();
                }
                $ket = "Pembayaran Tagihan Supplier";
                if (isset($request['sbk']['ketbayar'])) {
                    $ket = $request['sbk']['ketbayar'];
                }
                $dataSBK->keteranganlainnya = $ket;
                $dataSBK->objectkelompoktransaksifk =  $request['sbk']['kelompoktransaksi'];
                //          $dataSBK->objectpegawaipenerimafk  = $this->getCurrentLoginID();
                $dataSBK->tglsbk  = $request['sbk']['tglsbk'];
                $dataSBK->nostrukfk = $request['sbk']['nostruk'];
                if (isset($request['sbk']['nosbk_intern'])) {
                    $dataSBK->nosbk_intern = $request['sbk']['nosbk_intern'];
                }
                $dataSBK->objectpegawaipembayarfk = $this->getUserId();
                $dataSBK->namapegawaipenerima = $request['sbk']['pemilikrekanan'];
                $dataSBK->namapegawaipembayar = $request['sbk']['pemilikrekanan'];
                $dataSBK->totaldibayar  = $request['sbk']['totalbayar'];
                $dataSBK->keterangan = $request['sbk']['keterangan'];
                $dataSBK->totaldibayarbefore = $totaldibayarbefore;
                $dataSBK->noclosingfk = $NorecSc;
                $dataSBK->totalsudahdibayar = $totalsudahdibayar;
                $dataSBK->totalsisahutang = $sisautang;
                $dataSBK->pembayaranke = $pembayaranke;
                if ((float)$request['sbk']['biayaadmin'] != 0) {
                    $dataSBK->totalbiayaadmin = (float)$request['sbk']['biayaadmin'];
                } else {
                    $dataSBK->totalbiayaadmin = 0;
                }

                // return $$dataSBK;
                $dataSBK->save();
                $dataSBKNorec = $dataSBK->norec;
                $dataNoSBK = $dataSBK->nosbk;

                if ($dataSBKNorec != '') {
                    $SBPCB = new StrukBuktiPengeluaranCaraBayar();
                    $SBPCB->norec = $SBPCB->generateNewId();
                    $SBPCB->kdprofile = $idProfile;
                    $SBPCB->statusenabled = true;
                    $SBPCB->nosbkfk = $dataSBKNorec;
                    $SBPCB->namapemilik = $request['sbk']['pemilikrekanan'];
                    $SBPCB->nokartuaccount = $request['sbk']['rekeningrekanan'];
                    $SBPCB->namabank = $request['sbk']['bankrekanan'];
                    $SBPCB->carabayarfk = $request['sbk']['carabayar'];
                    $SBPCB->nourutcb = 0;
                    $SBPCB->pegawaipembayarfk = $this->getUserId();
                    $SBPCB->totaldibayar = $request['sbk']['totalbayar'];
                    $SBPCB->totaldibayarcashin = $request['sbk']['totalbayar'];
                    if (isset($request['sbk']['kdbankaccounttujuanfk'])) {
                        $SBPCB->kdbankaccounttujuanfk = $request['sbk']['kdbankaccounttujuanfk'];
                    }
                    if (isset($request['sbk']['rekening'])) {
                        $SBPCB->keterangan = $request['sbk']['rekening'];
                    }


                    $SBPCB->save();

                    if ($totalsudahdibayar == 0) {
                        $totalsudahdibayar = (float) $request['sbk']['totalbayar'];
                    } else {
                        $totalsudahdibayar = (float) $totalsudahdibayar + (float) $request['sbk']['totalbayar'];
                    }

                    // dd($totalsudahdibayar);

                    StrukPelayanan::where('norec', $request['sbk']['nostruk'])
                        ->where('kdprofile', $idProfile)
                        ->update([
                            'nosbklastfk' => $dataSBKNorec,
                            'totalsudahdibayar' => $totalsudahdibayar,
                            'totalbelumdibayar' => $sisautang,
                        ]);
                }
            }
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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    // public function daftarBKUPengeluaran(Request $request)
    // {
    //     $kdProfile = (int)$this->kdProfile;
    //     $idProfile = (int) $kdProfile;
    //     $list =  explode(',', $this->settingFix('KdTransaksiBendaharaPengeluaran', $idProfile));

    //     $kdTrans = [];
    //     $KdTransak = $this->settingFix('KdTransaksiBendaharaPengeluaran', $idProfile);
    //     foreach ($list as $itemTrans) {
    //         $kdTrans[] =  (int)$itemTrans;
    //     }
    //     $dataPenerimaanBank = DB::table('strukhistori_t as sh')
    //         ->join('strukclosing_t as sc', 'sc.norec', '=', 'sh.noclosing')
    //         ->leftjoin('strukbuktipengeluaran_t as sbk', 'sbk.noclosingfk', '=', 'sc.norec')
    //         ->leftjoin('loginuser_s as lu', 'lu.id', '=', 'sbk.objectpegawaipenerimafk')
    //         ->leftjoin('loginuser_s as lu2', 'lu2.id', '=', 'sbk.objectpegawaipembayarfk')
    //         ->leftjoin('pegawai_m as p', 'p.id', '=', 'lu.objectpegawaifk')
    //         ->leftjoin('pegawai_m as p2', 'p2.id', '=', 'lu2.objectpegawaifk')
    //         ->leftjoin('pegawai_m as psetor', 'psetor.id', '=', 'sh.objectpegawaitarikdepositfk')
    //         ->join('kelompoktransaksi_m as kt', 'kt.id', '=', 'sc.objectkelompoktransaksifk')
    //         ->leftjoin('asalproduk_m AS ap', 'ap.id', '=', 'sh.objectasalprodukhasilfk')
    //         ->leftjoin('strukbuktipengeluarancarabayar_t as spc', 'spc.nosbkfk', '=', 'sbk.norec')
    //         // ->leftjoin('bankaccount_m as ba', 'ba.id', '=', 'spc.objectbankaccountfk')

    //         ->select(
    //             // 'spp.norec',
    //             // 'spp.tglsbm',
    //             // 'spp.keteranganlainnya',
    //             // 'spp.nosbm_intern',
    //             // 'spp.objectpegawaipenerimafk',
    //             'p.namalengkap',
    //             'kt.kelompoktransaksi',
    //             'spp.nostrukfk',
    //             'sc.objectkelompoktransaksifk',
    //             'sc.norec as norec_sc',
    //             'sc.noclosing',
    //             'sh.nonhistori',
    //             'sbk.objectpegawaipembayarfk',
    //             'p2.namalengkap as pegawaibayar',
    //             'sh.ketlainya',
    //             'sh.norec as norec_sh',
    //             'sh.kdperkiraan',
    //             'sh.namaperkiraan',
    //             'sh.kettransaksi',
    //             'sh.nobukti',
    //             'psetor.namalengkap as penyetor',
    //             DB::raw("
    //                 CASE WHEN sc.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
    //                 INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
    //                 WHERE idbku IN (3) AND ks.statusenabled = TRUE) THEN COALESCE ((sbk.totaldibayar), 0) END AS debit,
    //                 CASE WHEN sc.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
    //                 INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
    //                 WHERE idbku IN (2) AND ks.statusenabled = TRUE) THEN coalesce((sbk.totaldibayar ), 0) END AS kredit,
    //                 cast(sh.tglsetortarikdeposit as date)as tglsetortarikdeposit,sc.objectkelompoktransaksifk,
    //                 sh.objectasalprodukhasilfk,ap.asalproduk,sh.norec AS norec_sh,sc.norec AS norec_sc")
    //         )
    //         ->groupBy(
    //             // 'spp.norec',
    //             // 'spp.tglsbm',
    //             // 'spp.keteranganlainnya',
    //             // 'spp.nosbm_intern',
    //             // 'spp.objectpegawaipenerimafk',
    //             'p.namalengkap',
    //             'kt.kelompoktransaksi',
    //             'spp.nostrukfk',
    //             'sc.objectkelompoktransaksifk',
    //             'sc.norec',
    //             'sc.noclosing',
    //             'sh.nonhistori',
    //             'sbk.objectpegawaipembayarfk',
    //             'p2.namalengkap',
    //             'sh.ketlainya',
    //             'sh.norec',
    //             'sh.kdperkiraan',
    //             'sh.namaperkiraan',
    //             'sh.kettransaksi',
    //             'sh.nobukti',
    //             'psetor.namalengkap',
    //             // 'spp.totaldibayar',
    //             'sbk.totaldibayar',
    //             'spp.nosbm',
    //             'sbk.nosbk',
    //             'sh.tglsetortarikdeposit',
    //             'sc.tglclosing',
    //             'sc.objectkelompoktransaksifk',
    //             'sh.objectasalprodukhasilfk',
    //             'ap.asalproduk',
    //             'sh.norec',
    //             'sc.norec',

    //         )
    //         ->orderBy('sc.tglclosing', 'asc')
    //         ->where('sh.kdprofile', $idProfile)
    //         ->where('sh.statusenabled', true)
    //         // ->whereRaw('','BKO')
    //         ->whereRaw("sh.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
    //                 INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
    //                 WHERE idbku IN (2,3) AND ks.statusenabled = TRUE)");
    //     // ->whereIn('sh.objectkelompoktransaksifk',$kdTrans);
    //     //            ->whereIn('sh.objectkelompoktransaksifk',[60,64,70,105,106,119,120]);

    //     $filter = $request->all();


    //     if (isset($filter['dari']) && $filter['dari'] != "" && $filter['dari'] != "undefined") {
    //         $dataPenerimaanBank =  $dataPenerimaanBank->where('sh.tglsetortarikdeposit', '>=', $filter['dari'] . " 00:00:00");
    //     }
    //     if (isset($filter['sampai']) && $filter['sampai'] != "" && $filter['sampai'] != "undefined") {
    //         $dataPenerimaanBank = $dataPenerimaanBank->where('sh.tglsetortarikdeposit', '<=',  $filter['sampai'] . ' 23:59:59');
    //     }
    //     if (isset($filter['nohistoris']) && $filter['nohistoris'] != "" && $filter['nohistoris'] != "undefined") {
    //         $dataPenerimaanBank = $dataPenerimaanBank->where('sh.nonhistori', $filter['nohistoris']);
    //     }

    //     if (isset($filter['keterangan']) && $filter['keterangan'] != "" && $filter['keterangan'] != "undefined") {
    //         $dataPenerimaanBank = $dataPenerimaanBank->where('sh.ketlainya', 'ilike', '%' . $filter['keterangan'] . '%');
    //     }

    //     //        $dataPenerimaanBank = $dataPenerimaanBank->distinct();
    //     $dataPenerimaanBank = $dataPenerimaanBank->get();



    //     /** @Function_ Ambil Saldo Satu Bulan Sebelum */
    //     $explode =  explode("-", $filter['dari']);
    //     $arr1 = $explode[0];
    //     $arr2 = $explode[1];
    //     if ($arr2 == 1) {
    //         $arr2 = $explode[1];
    //     } else {
    //         $arr2 = $explode[1] - 1; //bulan kurangi 1
    //     }
    //     $arr3 = '01'; //ambil tgl 1
    //     $arr2 = str_pad($arr2, 2, '0', STR_PAD_LEFT);
    //     //        $arrayExplode = array($arr1,$arr2,$arr3);
    //     //        $tglMinSabulan = implode("-", $arrayExplode);
    //     $tglMinSabulan = Carbon::parse($filter['dari'])->subMonth(1)->startOfMonth()->addDay(1)->format('Y-m-01'); // $arr1 . '-' . $arr2 . '-' . $arr3;
    //     /** @End_Function */

    //     $tglAwal = $filter['dari'];
    //     $dataSaldo = DB::select(DB::raw(" select sh.tglsetortarikdeposit,
    //                  coalesce((spp.totaldibayar ), 0) debit ,
    //                  coalesce((sbk.totaldibayar ), 0) kredit           
    //                  from strukhistori_t as sh
    //                  inner join strukclosing_t as sc on sc.norec = sh.noclosing 
    
    //                  left join strukbuktipengeluaran_t as sbk on sbk.noclosingfk = sc.norec
    //                  inner join kelompoktransaksi_m as kt on kt.id = sc.objectkelompoktransaksifk
    //                  inner join mapbkutokelompoktransaksi_m as mbk on mbk.kelompoktransaksifk = kt.id
    //                  where sh.kdprofile = $idProfile and sh.tglsetortarikdeposit > '$tglMinSabulan' and sh.tglsetortarikdeposit < '$tglAwal'
    //                    and sh.statusenabled=true
    //                    and sh.objectkelompoktransaksifk in (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
    //                        INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
    //                        WHERE idbku IN (2,3) AND ks.statusenabled = TRUE)
    //                  order by sh.nonhistori asc"));
    //     $saldolama = 0;
    //     $jmlSaldoLama = 0;
    //     $saldo = 0;
    //     if (count($dataSaldo) > 0) {
    //         foreach ($dataSaldo as $dataSaldoLama) {
    //             if ($dataSaldoLama->debit == 0) {
    //                 $saldolama = $saldolama + (float)$dataSaldoLama->debit - (float)$dataSaldoLama->kredit;
    //             } else {
    //                 $saldolama = $saldolama + (float)$dataSaldoLama->debit;
    //             }
    //             $jmlSaldoLama = $saldolama;
    //         }
    //         $saldo = $jmlSaldoLama;
    //     }

    //     $result = array();
    //     $jumlahD = 0;
    //     $jumlahK = 0;
    //     $saldoAkhir = 0;

    //     foreach ($dataPenerimaanBank as $dataPenerimaan) {
    //         /** @Function_ Non TUNAI Ambil */
    //         $norec = $dataPenerimaan->norec_sc;
    //         $dataPenerimaan->nontunai = 0;
    //         $SBMC = DB::select(DB::raw("
    //              select  spc.noclosingfk,spc.carabayarfk,cb.carabayar,
    //              coalesce((spc.totaldibayar ), 0) totaldibayar
    //              from strukclosingkasir_t as spc 
    //              join carabayar_m as cb on cb.id = spc.carabayarfk
    //              where spc.kdprofile = $idProfile and spc.noclosingfk ='$norec'
    //         "));

    //         if (count($SBMC) > 0) {
    //             $tunai = 0;
    //             $nonTunai = 0;
    //             foreach ($SBMC as $itemSbmc) {
    //                 if ($itemSbmc->carabayarfk == "1") { //TUNAI
    //                     $tunai = $tunai + (float) $itemSbmc->totaldibayar;
    //                 } else {
    //                     $nonTunai = $nonTunai +  (float)$itemSbmc->totaldibayar;
    //                 }
    //             }

    //             $dataPenerimaan->debit = $tunai;
    //             $dataPenerimaan->nontunai = $nonTunai;
    //         }
    //         /** @End_Function */
    //         if ($dataPenerimaan->debit == 0) {
    //             $saldo = $saldo + (float)$dataPenerimaan->debit - (float)$dataPenerimaan->kredit;
    //         } else {
    //             $saldo = $saldo + (float)$dataPenerimaan->debit;
    //         }
    //         $jumlahD = $jumlahD + (float)$dataPenerimaan->debit;
    //         $jumlahK = $jumlahK +  (float)$dataPenerimaan->kredit;
    //         $saldoAkhir = $saldo;

    //         $result[] = array(
    //             'norec_sh'  => $dataPenerimaan->norec_sh,
    //             'norec_sc' => $dataPenerimaan->norec_sc,
    //             'noStruk'  => $dataPenerimaan->norec,
    //             'tglStruk'  => $dataPenerimaan->tglsetortarikdeposit,
    //             'keterangan'  => $dataPenerimaan->ketlainya,
    //             'jenisTransaksi'  => $dataPenerimaan->kelompoktransaksi,
    //             'idJenisTransaksi'  => $dataPenerimaan->objectkelompoktransaksifk,
    //             'kredit'  => (float) $dataPenerimaan->kredit,
    //             'debit'  => (float)$dataPenerimaan->debit,
    //             'saldo'  => $saldo,
    //             'nontunai'  => $dataPenerimaan->nontunai,
              
    //             'namaPegawai' => $dataPenerimaan->namalengkap,
               
    //             'nostrukfk' => $dataPenerimaan->nostrukfk,

    //             'nohistori' => $dataPenerimaan->nonhistori,
    //             //                'noclosing' => $dataPenerimaan->noclosing,
    //             'notransaksi' => $dataPenerimaan->notransaksi,
    //             //                'kdmataanggaran' =>$dataPenerimaan->kdchildkeempat,
    //             //                'mataanggaran' =>$dataPenerimaan->mataanggaran,
    //             'nobukti' => $dataPenerimaan->nobukti,
    //             'kdperkiraan' => $dataPenerimaan->kdperkiraan,
    //             'namaperkiraan' => $dataPenerimaan->namaperkiraan,
    //             'kettransaksi' => $dataPenerimaan->kettransaksi,
    //             'penyetor' => $dataPenerimaan->penyetor,
    //             'asalprodukfk' => $dataPenerimaan->objectasalprodukhasilfk,
    //             'asalproduk' => $dataPenerimaan->asalproduk,

    //         );
    //     }
    //     $uhman = array(
    //         'data' =>  $result,
    //         'saldolama' =>  $jmlSaldoLama,
    //         'dataawal' => $dataSaldo,
    //         'jumlahD' => $jumlahD,
    //         'jumlahK' => $jumlahK,
    //         'saldoAkhir' => $saldoAkhir,
    //         'tglmin' => $tglMinSabulan,
    //         // 'tglmin_sebulan'=> Carbon::parse($filter['dari'])->subMonth(1)->startOfMonth()->addDay(1)->format('Y-m-d'),
    //         'message' => "@epic"
    //     );
    //     return $this->respond($uhman);
    // }

    public function simpanBKUBK(Request $request)
    {
        $kdProfile = (int)$this->kdProfile;
        $idProfile = (int) $kdProfile;
        DB::beginTransaction();
        $input = $request->all();
        $nohistori = '';
        $idRuangan = 0;
        $transStatus = true;
        try {

            $dataruangan = DB::table('maploginusertoruangan_s as mlu')
                ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
                ->leftjoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
                ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk')
                ->where('mlu.kdprofile', $idProfile)
                ->where('objectloginuserfk', $input['userData']['id'])
                ->get();
            if (count($dataruangan) == 0) {
                $idRuangan = 0;
            } else {
                $idRuangan = $dataruangan[0]->id; //471
            }

            if ($input['norec_sc'] == '') {
                $SC = new StrukClosing();
                $SC->norec = $SC->generateNewId();
                $SC->kdprofile = $idProfile;
                $SC->noclosing = $this->generateCode(new StrukClosing, 'noclosing', 10, 'C-' . $this->getDateTime()->format('ym'), $idProfile);
            } else {
                $SC = StrukClosing::where('norec', $input['norec_sc'])
                    ->where('kdprofile', $idProfile)
                    ->first();

                $SBM = StrukBuktiPenerimaan::where('noclosingfk', $input['norec_sc'])
                    ->where('kdprofile', $idProfile)
                    ->delete();

                $SBK = StrukBuktiPengeluaran::where('noclosingfk', $input['norec_sc'])
                    ->where('kdprofile', $idProfile)
                    ->delete();
            }
            $SC->objectpegawaidiclosefk = $this->getUserId();
            $SC->totaldibayar = $input['totalSetor'];
            $SC->objectkelompoktransaksifk = $input['jenisTransaksi'];
            if ($input['penerimaan'] == true) {
                $SC->keteranganlainnya = "PENERIMAAN BKU";
            } else {
                $SC->keteranganlainnya = "PENGELUARAN BKU";
            }
            $SC->tglawal = $this->getDateTime()->format('Y-m-d H:i:s');
            $SC->tglakhir = $this->getDateTime()->format('Y-m-d H:i:s');
            $SC->tglclosing = $this->getDateTime()->format('Y-m-d H:i:s');; //$input['tglbku'];
            $SC->save();
            $norec_sc = $SC->norec;
            $noclosing_SC = $SC->noclosing;

            if ($input['norec_sh'] == '') {
                $SH = new StrukHistori();
                $SH->norec = $SH->generateNewId();
                $nohistori = $this->generateCode(new StrukHistori(), 'nonhistori', 14, 'BKU-' . $this->getDateTime()->format('ym'), $idProfile);
                $SH->nonhistori = $nohistori;
                $SH->kdprofile = $idProfile;
                $SH->statusenabled = true;
            } else {
                $SH = StrukHistori::where('norec', $input['norec_sh'])
                    ->where('kdprofile', $idProfile)
                    ->first();
            }
            $SH->totalsetortarikdeposit = $input['totalSetor'];
            $SH->tglsetortarikdeposit = $input['tglbku']; // $this->getDateTime();
            $SH->objectpegawaitarikdepositfk = $this->getUserId();
            $SH->objectpegawaiterimafk = $this->getUserId();
            $SH->objectruanganterimafk = $idRuangan;
            $SH->objectruanganfk = $idRuangan;
            $SH->objectkelompoktransaksifk = $input['jenisTransaksi'];
            $SH->noclosing = $norec_sc; //$SC->noclosing;
            $SH->ketlainya = $input['keterangan'];
            $SH->nobukti = $input['nobukti'];
            $SH->kdperkiraan = $input['kdperkiraan'];
            $SH->namaperkiraan = $input['keterangan'];
            $SH->kettransaksi = $input['keterangan'];
            $SH->objectasalprodukhasilfk = $input['sumberdana'];
            $SH->save();

            if ($input['penerimaan'] == true) {
                $strukBuktiPenerimanan = new StrukBuktiPenerimaan();
                $strukBuktiPenerimanan->norec = $strukBuktiPenerimanan->generateNewId();
                $strukBuktiPenerimanan->kdprofile = $idProfile;
                $strukBuktiPenerimanan->keteranganlainnya = 'Setoran'; //$input['keterangan'];
                $strukBuktiPenerimanan->statusenabled = 1;
                $strukBuktiPenerimanan->objectpegawaipenerimafk = $this->getUserId();
                $strukBuktiPenerimanan->tglsbm = $input['tglbku']; //$this->getDateTime();
                $strukBuktiPenerimanan->totaldibayar = $input['totalSetor'];
                $strukBuktiPenerimanan->objectkelompoktransaksifk = $input['jenisTransaksi'];
                $strukBuktiPenerimanan->nosbm = $this->generateCode(new StrukBuktiPenerimaan, 'nosbm', 14, 'RV-' . $this->getDateTime()->format('ym'), $idProfile);
                $strukBuktiPenerimanan->noclosingfk = $norec_sc; //$SC->norec;
                $strukBuktiPenerimanan->asalprodukfk = $input['sumberdana'];
                $strukBuktiPenerimanan->save();

                $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $idProfile;
                $SBPCB->statusenabled = 1;
                $SBPCB->nosbmfk = $strukBuktiPenerimanan->norec;
                $SBPCB->objectcarabayarfk = $input['caraBayar'];
                if ($input['detailBank'] != 'KOSONG') {
                    $SBPCB->objectbankaccountfk = $input['detailBank']['id'];
                    $SBPCB->namabankprovider = $input['detailBank']['namaBank'];
                    $SBPCB->namapemilik = $input['detailBank']['namaKartu'];
                }
                $SBPCB->save();
            } else {

                $SBK = new StrukBuktiPengeluaran();
                $SBK->norec = $SBK->generateNewId();
                $SBK->kdprofile = $idProfile;
                $SBK->keteranganlainnya = $input['keterangan'];
                $SBK->statusenabled = 1;
                $SBK->objectpegawaipembayarfk = $this->getUserId();
                $SBK->tglsbk = $input['tglbku']; // $this->getDateTime();
                $SBK->totaldibayar = $input['totalSetor'];
                $SBK->objectkelompoktransaksifk = $input['jenisTransaksi'];
                $SBK->nosbk = $this->generateCode(new StrukBuktiPengeluaran(), 'nosbk', 14, 'PV-' . $this->getDateTime()->format('ym'), $idProfile);
                $SBK->noclosingfk = $SC->norec;
                $SBK->asalprodukfk = $input['sumberdana'];
                $SBK->save();
            }

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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getListBK(Request $request)
    {
        $res['carabayar'] = CaraBayar::mine()->get();
        $res['carasetor'] = CaraSetor::mine()->get();
        $res['asalproduk'] = AsalProduk::mine()->get();

        $bp = DB::table('mapbkutokelompoktransaksi_m as mp')
            ->join('kelompoktransaksi_m as kt', 'kt.id', '=', 'mp.kelompoktransaksifk')
            ->select('kt.id', 'kt.kelompoktransaksi')
            ->where('mp.kdprofile', $this->kdProfile)
            ->whereIn('mp.kelompoktransaksifk', explode(',', $this->settingFix('kdPenerimaanBK')))
            ->where('kt.statusenabled', true)
            ->where('mp.statusenabled', true);
        $res['kelompoktransaksi'] = $bp->get();

        $bk = DB::table('mapbkutokelompoktransaksi_m as mp')
            ->join('kelompoktransaksi_m as kt', 'kt.id', '=', 'mp.kelompoktransaksifk')
            ->select('kt.id', 'kt.kelompoktransaksi as pengeluaran')
            ->where('mp.kdprofile', $this->kdProfile)
            ->whereIn('mp.kelompoktransaksifk', explode(',', $this->settingFix('kdPengeluaranBK')))
            ->where('kt.statusenabled', true)
            ->where('mp.statusenabled', true);
        $res['pengeluaran'] = $bk->get();

        return $this->respond($res);
    }


    public function hapusBKU(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;


        DB::beginTransaction();
        try {

            if ($request['norec_struk_histori'] != '') {
                $sH = StrukHistori::where('norec', $request['norec_struk_histori'])->where('kdprofile', $idProfile)->first();

                $strukClosing = StrukClosing::where('noclosing', $sH->noclosing)->where('kdprofile', $idProfile)->first();
                // StrukBuktiPengeluaran::where('noclosingfk', $strukClosing['norec'])
                //     ->where('kdprofile', $idProfile)
                //     ->where('statusenabled', true)
                //     ->update(['noclosingfk' => null]);
                StrukHistori::where('norec', $request['norec_struk_histori'])
                    ->where('kdprofile', $idProfile)
                    ->update(
                        [
                            //                        'statusenabled' => false,
                            'statusenabled' => 0,
                        ]
                    );
            }
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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveCollectTagihan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {

            if ($request['norec'] == '') {
                $noOrder = $this->generateCode(new StrukCollecting(), 'nocollecting', 14, 'RCP-' . $this->getDateTime()->format('ym'), $kdProfile);
                if ($noOrder == '') {
                    $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "message"  => $transMessage,
                        "as" => 'ea@epic',
                    );
                    return $this->respond($result, $transMessage);
                }
                $SH = new StrukCollecting();
                $SH->norec = $SH->generateNewId();
                $SH->kdprofile = $kdProfile;
                $SH->statusenabled = true;
                $SH->nocollecting = $noOrder;
                $SH->tanggalcollecting = $request['tglcollecting'];
                $SH->rekananfk = $request['objectrekananfk'];

                // return $SH;
            } else {
                $SH = StrukCollecting::where('norec', $request['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->first();


                $upSCD = StrukCollectingDetail::where('strukcollectingfk', $request['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->delete();
            }
            $SH->pegawaifk = $this->getUserId();
            $SH->totalharga = (float) $request['totalharga'];
            $SH->totalppn = (float) $request['totalppn'];
            $SH->totaldiskon = (float) $request['totaldiskon'];
            $SH->totaltagihan = (float) $request['totaltagihan'];
            $SH->save();
            $norecSH = $SH->norec;

            foreach ($request['detail'] as $item) {
                $SHC = new StrukCollectingDetail();
                $SHC->norec = $SH->generateNewId();
                $SHC->kdprofile = $kdProfile;
                $SHC->statusenabled = true;
                $SHC->strukcollectingfk = $norecSH;
                $SHC->nostrukterimafk = $item['norec'];
                $SHC->totaltagihan = $item['subtotal'];
                $SHC->totalharga = $item['total'];
                $SHC->totaldiskon = $item['totaldiskon'];
                $SHC->totalppn = $item['totalppn'];
                $SHC->save();
                $upSP = strukPelayanan::where('norec', $item['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->update([
                        'strukcollectingfk' => $norecSH,
                    ]);
            }


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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDataPembayaran(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        $kodeBK =   $this->settingFix('kdPembayaranBK', $idProfile);
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];


        $ScaraBayar = ' ';
        if (isset($request['ScaraBayar']) && $request['ScaraBayar'] != "" && $request['ScaraBayar'] != "undefined") {
            $ScaraBayar = " and sbc.carabayarfk = " . $request['ScaraBayar'];
        }
        $bank = ' ';
        if (isset($request['bank']) && $request['bank'] != "" && $request['bank'] != "undefined") {
            $bank = " and ba.id = " . $request['bank'];
        }
        $noaccount = ' ';
        if (isset($request['noaccount']) && $request['noaccount'] != "" && $request['noaccount'] != "undefined") {
            $noaccount = " and ba.bankaccountnomor = " . $request['noaccount'];
        }

        $Skasir = ' ';
        if (isset($request['Skasir']) && $request['Skasir'] != "" && $request['Skasir'] != "undefined") {
            $Skasir = " and lu.objectpegawaifk =" . $request['Skasir'];
        }

        $noSBK = ' ';
        if (isset($request['noSBK']) && $request['noSBK'] != "" && $request['noSBK'] != "undefined") {
            $noSBK = "where sbk.nosbk LIKE '%" . $request['noSBK'] . "%'";
        }



        $results = array();
        $data = DB::select(DB::raw("
                select sbk.norec as norec_sbk,sbk.tglsbk,sbk.nosbk,sbk.totaldibayar,sbk.objectkelompoktransaksifk,
			    kt.kelompoktransaksi,lu.objectpegawaifk,pg.namalengkap,sbc.carabayarfk,cb.carabayar,ba.bankaccountnomor,sbk.keterangan,
                sbk.statusrekon,CASE WHEN sbk.totalbiayaadmin IS NULL THEN 0 ELSE sbk.totalbiayaadmin END AS totalbiayaadmin
                from strukbuktipengeluaran_t as sbk
                inner join strukbuktipengeluarancarabayar_t as sbc on sbc.nosbkfk=sbk.norec
                inner join strukpelayanan_t as sp on sp.norec = sbk.nostrukfk
                inner join strukpelayanandetail_t as spd on spd.nostrukfk = sp.norec
                left join rekanan_m as rkn on rkn.id = sp.objectrekananfk
                inner join kelompoktransaksi_m as kt on kt.id = sbk.objectkelompoktransaksifk
                inner join loginuser_s as lu on lu.id = sbk.objectpegawaipembayarfk
                inner join pegawai_m as pg on pg.id = lu.objectpegawaifk
                left join ruangan_m as ru on ru.id = sbk.objectruanganfk
                left join carabayar_m as cb on cb.id = sbc.carabayarfk
                left join bankaccount_m as ba on ba.id = sbc.kdbankaccounttujuanfk
                where sbk.kdprofile = $idProfile 
                and sbk.statusenabled = true
                and sbk.objectkelompoktransaksifk in ($kodeBK) 
                and CAST(sbk.tglsbk as DATE) BETWEEN '$tglAwal' and '$tglAkhir'
                $Skasir
                $ScaraBayar
                $noSBK
                GROUP BY sbk.norec,sbk.nosbk,sbk.tglsbk,sbk.objectkelompoktransaksifk,kt.kelompoktransaksi,
				sbk.totaldibayar,lu.objectpegawaifk,pg.namalengkap,sbc.carabayarfk,cb.carabayar,ba.bankaccountnomor,
                sbk.keterangan, sbk.statusrekon,sbk.totalbiayaadmin

                UNION ALL

                select sbk.norec as norec_sbk,sbk.tglsbk,sbk.nosbk,sbk.totaldibayar,sbk.objectkelompoktransaksifk,
                kt.kelompoktransaksi,lu.objectpegawaifk,pg.namalengkap,sbc.carabayarfk,cb.carabayar,ba.bankaccountnomor,sbk.keterangan,
                sbk.statusrekon,CASE WHEN sbk.totalbiayaadmin IS NULL THEN 0 ELSE sbk.totalbiayaadmin END AS totalbiayaadmin
                from strukbuktipengeluaran_t as sbk
                inner join strukbuktipengeluarancarabayar_t as sbc on sbc.nosbkfk=sbk.norec
                inner join strukcollecting_t as sp on sp.norec = sbk.strukcollectingfk
                left join rekanan_m as rkn on rkn.id = sp.rekananfk
                inner join kelompoktransaksi_m as kt on kt.id = sbk.objectkelompoktransaksifk
                inner join loginuser_s as lu on lu.id = sbk.objectpegawaipembayarfk
                inner join pegawai_m as pg on pg.id = lu.objectpegawaifk
                left join ruangan_m as ru on ru.id = sbk.objectruanganfk
                left join carabayar_m as cb on cb.id = sbc.carabayarfk
                left join bankaccount_m as ba on ba.id = sbc.kdbankaccounttujuanfk
                where sbk.kdprofile = $idProfile 
                and sbk.statusenabled = true
                and sbk.objectkelompoktransaksifk in ($kodeBK) 
                and CAST(sbk.tglsbk as DATE) BETWEEN '$tglAwal' and '$tglAkhir'
                $Skasir
                $ScaraBayar
                $noSBK
                GROUP BY sbk.norec,sbk.nosbk,sbk.tglsbk,sbk.objectkelompoktransaksifk,kt.kelompoktransaksi,
                sbk.totaldibayar,lu.objectpegawaifk,pg.namalengkap,sbc.carabayarfk,cb.carabayar,ba.bankaccountnomor,
                sbk.keterangan, sbk.statusrekon,sbk.totalbiayaadmin

        "));

        $result = array(
            'daftar' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function getRekapCollecting(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $dataLogin = $request->all();
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];

        $Supplier = ' ';
        if (isset($request['Supplier']) && $request['Supplier'] != "" && $request['Supplier'] != "undefined") {
            // $Supplier = " and rkn.namarekanan LIKE '%".$request['Supplier']."%'";
            $Supplier = " and rkn.id =" . $request['Supplier'];
        }

        $NoFaktur = ' ';
        if (isset($request['NoFaktur']) && $request['NoFaktur'] != "" && $request['UserId'] != "undefined") {
            $NoFaktur = " and sp.nofaktur LIKE '%" . $request['NoFaktur'] . "%'";
        }

        $NoStruk = ' ';
        if (isset($request['NoStruk']) && $request['NoStruk'] != "" && $request['NoStruk'] != "undefined") {
            $NoStruk = " and sp.nostruk LIKE '%" . $request['NoStruk'] . "%'";
        }

        $status = ' ';
        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined") {
            $statusTea = $request['status'];
            $status =  'where su.status = ' . "'$statusTea'";
        }



        $results = array();
        $data = collect(DB::select("
            SELECT sc.norec,sc.tanggalcollecting,sc.nocollecting,sc.pegawaifk,sc.rekananfk,
                   rk.namarekanan,pg.namalengkap,sc.totalharga,sc.totalppn,sc.totaldiskon,sc.totaltagihan,
                   sbk.nosbk,0 AS totalsudahdibayar,sc.totaltagihan AS sisahutang
            FROM strukcollecting_t AS sc
            LEFT JOIN strukbuktipengeluaran_t AS sbk ON sbk.norec = sc.nosbklastfk and sbk.objectkelompoktransaksifk = 107
            INNER JOIN rekanan_m AS rk ON rk.id = sc.rekananfk
            INNER JOIN pegawai_m AS pg ON pg.id = sc.pegawaifk
            WHERE sc.statusenabled = true AND sc.kdprofile = $kdProfile AND CAST(sc.tanggalcollecting as DATE ) BETWEEN '$tglAwal' and '$tglAkhir'
        "));

        $norecSC = "";
        foreach ($data as $ob) {
            $norecSC = $norecSC . ",'" . $ob->norec . "'";
        }
        $norecSC = substr($norecSC, 1, strlen($norecSC) - 1);

        if ($norecSC != '') {
            $sbk = collect(DB::select("
                select strukcollectingfk,totaldibayar
                from strukbuktipengeluaran_t 
                where kdprofile = $kdProfile and statusenabled = true 
                and objectkelompoktransaksifk = 107 and strukcollectingfk in ($norecSC)
            "));
            $i = 0;
            foreach ($data as $h) {
                $totalsudahdibayar = 0;
                $sisahutang = 0;
                foreach ($sbk as $d) {
                    if ($data[$i]->norec == $d->strukcollectingfk) {
                        $totalsudahdibayar = (float) $totalsudahdibayar + (float) $d->totaldibayar;
                        if ($sisahutang == 0) {
                            $sisahutang = $data[$i]->sisahutang - (float) $d->totaldibayar;
                        } else {
                            $sisahutang = $sisahutang - (float) $d->totaldibayar;
                        }
                    }
                    $data[$i]->totalsudahdibayar = $totalsudahdibayar;
                    $data[$i]->sisahutang = $sisahutang;
                }
                $i++;
            }
        }


        foreach ($data as $item) {
            $status = "BELUM BAYAR";
            if ($item->totalsudahdibayar == $item->totaltagihan) {
                $status = "LUNAS";
            } else if ($item->totalsudahdibayar == 0) {
                $status = "BELUM BAYAR";
            } else if ($item->sisahutang != $item->totaltagihan) {
                $status = "BAYAR SEBAGIAN";
            }
            $details = DB::select(
                DB::raw("
                    SELECT sp.nostruk,sp.tglstruk,sp.nofaktur,sp.tgljatuhtempo,sp.totalhargasatuan as total,
                           sp.totalppn as totalppn,sp.totaldiscount as totaldiskon,sp.totalharusdibayar as totaltagihan
                    FROM strukcollectingdetail_t AS scd
                    INNER JOIN strukpelayanan_t AS sp ON sp.norec = scd.nostrukterimafk
                    WHERE scd.kdprofile = $kdProfile AND scd.strukcollectingfk = :norec
                "),
                array(
                    'norec' => $item->norec,
                )
            );
            $result[] = array(
                'norec' => $item->norec,
                'tanggalcollecting' => $item->tanggalcollecting,
                'nocollecting' => $item->nocollecting,
                'pegawaifk' => $item->pegawaifk,
                'rekananfk' => $item->rekananfk,
                'namalengkap' => $item->namalengkap,
                'namarekanan' => $item->namarekanan,
                'nosbk' => $item->nosbk,
                'totalharga' => $item->totalharga,
                'totalppn' => $item->totalppn,
                'totaldiskon' => $item->totaldiskon,
                'totaltagihan' => $item->totaltagihan,
                'totalsudahdibayar' => $item->totalsudahdibayar,
                'sisahutang' => $item->sisahutang,
                'status' => $status,
                'details' => $details,
            );
        }

        if (count($data) == 0) {
            $result = [];
        }

        $result = array(
            'daftar' => $result,
            'datalogin' => $dataLogin,
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function getDetailTagihanCollecting(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norecCollecting = $request['norec'];
        $data = collect(DB::select("
            select * from strukcollecting_t where kdprofile = $kdProfile and statusenabled = true and norec = '$norecCollecting'
        "))->first();

        return $this->respond($data);
    }

    public function savePembayaranCollecting(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        DB::beginTransaction();

        $totaldibayarbefore = 0;
        $totalsudahdibayar = 0;
        $sisautang = 0;
        $pembayaranke = 0;
        $norecCollecting = $request['sbk']['nostruk'];
        try {
            $datastruk = collect(DB::select("
                select * from strukcollecting_t where kdprofile = $idProfile and norec = '$norecCollecting'
            "));

            foreach ($datastruk as $items) {
                if ($items->nosbklastfk == null || $items->nosbklastfk == '') {
                    $totaldibayarbefore = 0;
                    $totalsudahdibayar = 0;
                    $sisautang = $request['sbk']['sisautang'];
                    $pembayaranke = 1;
                } else {

                    $datasbk = DB::select(
                        DB::raw("
                        select * from strukbuktipengeluaran_t where kdprofile = $idProfile and norec=:nostruk"),
                        array(
                            'nostruk' => $items->nosbklastfk,
                        )
                    );
                    $pembayaranke = (float) count($datasbk) + 1;
                    foreach ($datasbk as $Hits) {
                        $totaldibayarbefore = $Hits->totaldibayar;
                        $totalsudahdibayar = $Hits->totaldibayar;
                        $sisautang = $Hits->totalsisahutang - $request['sbk']['totalbayar'];
                    }
                }
            }

            if ($request['sbk']['nosbk'] == '') {
                $noOrder = $this->generateCode(new StrukBuktiPengeluaran, 'nosbk', 14, 'PV-' . $this->getDateTime()->format('ym'), $kdProfile);
                if ($noOrder == '') {
                    $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "message"  => $transMessage,
                        "as" => '@epic',
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $dataSBK = new StrukBuktiPengeluaran();
                $dataSBK->norec = $dataSBK->generateNewId();
                $dataSBK->kdprofile = $idProfile;
                $dataSBK->statusenabled = true;
                $dataSBK->nosbk = $noOrder;
            } else {
                $dataSBK = StrukBuktiPengeluaran::where('norec', $request['sbk']['nosbk'])->where('kdprofile', $idProfile)->first();

                $delSBKCB = StrukBuktiPengeluaranCaraBayar::where('nosbkfk', $request['sbk']['nosbk'])->where('kdprofile', $idProfile)->delete();
            }
            $ket = "Pembayaran Tagihan Supplier";
            if (isset($request['sbk']['ketbayar'])) {
                $ket = $request['sbk']['ketbayar'];
            }
            $dataSBK->keteranganlainnya = $ket;
            $dataSBK->objectkelompoktransaksifk =  $request['sbk']['kelompoktransaksi'];
            $dataSBK->tglsbk  = $request['sbk']['tglsbk'];
            $dataSBK->nostrukfk = $request['sbk']['nostruk'];
            $dataSBK->strukcollectingfk = $request['sbk']['nostruk'];
            if (isset($request['sbk']['nosbk_intern'])) {
                $dataSBK->nosbk_intern = $request['sbk']['nosbk_intern'];
            }
            $dataSBK->objectpegawaipembayarfk =  $this->getUserId();
            $dataSBK->pegawaifk = $this->getUserId();
            $dataSBK->namapegawaipenerima = $request['sbk']['pemilikrekanan'];
            $dataSBK->namapegawaipembayar = $this->getUsername();
            $dataSBK->norekeningtujuan = $request['sbk']['rekeningrekanan'];
            $dataSBK->namabank = $request['sbk']['bankrekanan'];
            $dataSBK->totaldibayar  = (float) $request['sbk']['totalbayar'];
            $dataSBK->keterangan = $request['sbk']['keterangan'];
            $dataSBK->totaldibayarbefore = $totaldibayarbefore;
            $dataSBK->totalsudahdibayar = $totalsudahdibayar;
            $dataSBK->totalsisahutang = $sisautang;
            $dataSBK->pembayaranke = $pembayaranke;
            if ((float)$request['sbk']['biayaadmin'] != 0) {
                $dataSBK->totalbiayaadmin = (float)$request['sbk']['biayaadmin'];
            } else {
                $dataSBK->totalbiayaadmin = 0;
            }
            $dataSBK->save();
            $dataSBKNorec = $dataSBK->norec;
            $dataNoSBK = $dataSBK->nosbk;

            if ($dataSBKNorec != '') {
                $SBPCB = new StrukBuktiPengeluaranCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $idProfile;
                $SBPCB->statusenabled = true;
                $SBPCB->nosbkfk = $dataSBKNorec;
                $SBPCB->namapemilik = $request['sbk']['pemilikrekanan'];
                $SBPCB->nokartuaccount = $request['sbk']['rekeningrekanan'];
                $SBPCB->namabank = $request['sbk']['bankrekanan'];
                $SBPCB->carabayarfk = $request['sbk']['carabayar'];
                $SBPCB->nourutcb = 0;
                $SBPCB->pegawaipembayarfk = $this->getUserId();
                $SBPCB->totaldibayar = (float) $request['sbk']['totalbayar'];
                $SBPCB->totaldibayarcashin = (float) $request['sbk']['totalbayar'];
                if (isset($request['sbk']['kdbankaccounttujuanfk'])) {
                    $SBPCB->kdbankaccounttujuanfk = $request['sbk']['kdbankaccounttujuanfk'];
                }
                if (isset($request['sbk']['rekening'])) {
                    $SBPCB->keterangan = $request['sbk']['rekening'];
                }
                $SBPCB->save();

                StrukPelayanan::where('strukcollectingfk', $request['sbk']['nostruk'])
                    ->where('kdprofile', $idProfile)
                    ->update([
                        'nosbklastfk' => $dataSBKNorec
                    ]);


                if ($totalsudahdibayar == 0) {
                    $totalsudahdibayar = (float) $request['sbk']['totalbayar'];
                } else {
                    $totalsudahdibayar = (float) $totalsudahdibayar + (float) $request['sbk']['totalbayar'];
                }


                StrukCollecting::where('norec', $request['sbk']['nostruk'])
                    ->where('kdprofile', $idProfile)
                    ->update([
                        'nosbklastfk' => $dataSBKNorec,
                        'totalsudahdibayar' => $totalsudahdibayar,
                        'sisahutang' => $sisautang
                    ]);
            }


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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDetailPembayaranCollecting(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norecCollecting = $request['norec'];
        $data = collect(DB::select("
                SELECT sbk.norec,sbk.nosbk,sbk.tglsbk,sbk.keterangan,sbk.pegawaifk AS idpegawaipembayar,sbk.namapegawaipembayar,
                       sbk.totaldibayar,sbk.totaldibayarbefore AS totaldibayarsebelumnya,sbk.totalsisahutang,sbk.norekeningtujuan,
                       sbk.namabank,sbk.namapegawaipenerima,sbk.pembayaranke
                FROM strukbuktipengeluaran_t AS sbk            
                INNER JOIN pegawai_m AS pg ON pg.id = sbk.pegawaifk
                WHERE sbk.kdprofile = $kdProfile AND sbk.statusenabled = true
                AND sbk.strukcollectingfk = '$norecCollecting';
            "));

        return $this->respond($data);
    }

    public function batalCollectSup(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {

            StrukCollecting::where('norec', $request['norec'])
                ->where('kdprofile', $kdProfile)
                ->update([
                    'statusenabled' => false,
                ]);

            StrukPelayanan::where('strukcollectingfk', $request['norec'])
                ->where('kdprofile', $kdProfile)
                ->update([
                    'strukcollectingfk' => null,
                ]);

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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveBatalBayarSup(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;

        DB::beginTransaction();
        try {

            $SBK = StrukBuktiPengeluaran::where('norec', $request['norec_sbk'])
                ->where('kdprofile', $kdProfile)
                ->first();

            $SP = StrukPelayanan::where('nosbklastfk', $request['norec_sbk'])
                ->where('kdprofile', $kdProfile)
                ->first();


            StrukBuktiPengeluaran::where('norec', $request['norec_sbk'])
                ->where('kdprofile', $kdProfile)
                ->update([
                    'statusenabled' => false
                ]);

            if (isset($SBK->strukcollectingfk)) {

                $SC =  StrukCollecting::where('norec', $SBK->strukcollectingfk)
                    ->where('kdprofile', $kdProfile)
                    ->first();

                $SCD = StrukCollectingDetail::where('strukcollectingfk', $SBK->strukcollectingfk)
                    ->where('kdprofile', $kdProfile)
                    ->get();

                // dd($SC);

                StrukCollecting::where('nosbklastfk', $request['norec_sbk'])
                    ->where('kdprofile', $kdProfile)
                    ->update([
                        'nosbklastfk' => null,
                        'totalsudahdibayar' => $SC->totaltagihan - $SBK->totaldibayar,
                        'sisahutang' => $SBK->totaltagihan,
                    ]);

                foreach ($SCD as $item) {
                    StrukPelayanan::where('norec', $item->nostrukterimafk)
                        ->where('kdprofile', $kdProfile)
                        ->update([
                            'nosbklastfk' => null,
                            'totalsudahdibayar' => $SC->totaltagihan - $SBK->totaldibayar,
                            'totalbelumdibayar' => $SC->totaltagihan,
                        ]);
                }
            } else {
                StrukPelayanan::where('nosbklastfk', $request['norec_sbk'])
                    ->where('kdprofile', $kdProfile)
                    ->update([
                        'nosbklastfk' => null,
                        'totalsudahdibayar' => $SP->totalsudahdibayar - $SBK->totaldibayar,
                        'totalbelumdibayar' => $SP->totalharusdibayar,
                    ]);
            }


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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
