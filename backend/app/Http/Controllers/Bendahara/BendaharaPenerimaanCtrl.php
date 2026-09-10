<?php

namespace App\Http\Controllers\Bendahara;

use App\Models\Master\Profile;
use App\Http\Controllers\Controller;
use App\Models\Master\AsalProduk;
use App\Models\Master\CaraBayar;
use App\Models\Master\CaraSetor;
use App\Models\Master\MapBkuToKelompokTransaksi;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile as MasterProfile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukClosing;
use App\Models\Transaksi\StrukClosingKasir;
use App\Models\Transaksi\StrukHistori;
use App\Models\Transaksi\StrukPelayanan;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class BendaharaPenerimaanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getDaftarSBM(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $data = DB::table('strukbuktipenerimaan_t as sbm')
            ->join('strukpelayanan_t as sp', 'sbm.nostrukfk', '=', 'sp.norec')
            ->leftjoin('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('loginuser_s as lu', 'lu.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftjoin('pegawai_m as p', 'p.id', '=', 'lu.objectpegawaifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
            ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
            ->leftjoin('carabayar_m as cb', 'cb.id', '=', 'sbmcr.objectcarabayarfk')
            ->leftjoin('kelompoktransaksi_m as kt', 'kt.id', '=', 'sbm.objectkelompoktransaksifk')
            ->leftjoin('strukclosing_t as sc', 'sc.norec', '=', 'sbm.noclosingfk')
            ->leftjoin('strukverifikasi_t as sv', 'sv.norec', '=', 'sbm.noverifikasifk')
            ->select(
                'sbm.norec as noRec',
                'cb.carabayar as caraBayar',
                'sbmcr.objectcarabayarfk as idCaraBayar',
                'sbm.objectkelompoktransaksifk as idKelTransaksi',
                'kt.kelompoktransaksi as kelTransaksi',
                'sbm.keteranganlainnya as keterangan',
                'p.id as idPegawai',
                'p.namalengkap as namaPenerima',
                'sc.noclosing as noClosing',
                'sbm.nosbm as noSbm',
                'sv.noverifikasi as noVerifikasi',
                'sc.tglclosing as tglClosing',
                'sbm.tglsbm as tglSbm',
                'sv.tglverifikasi as tglVerif',
                'sbmcr.totaldibayar as totalPenerimaan',
                // 'sbm.totaldibayar as totalPenerimaan',
                'pd.noregistrasi',
                'ps.namapasien',
                'sp.norec as norec_sp',
                'ru.id as ruid',
                'ru.namaruangan',
                'sp.namapasien_klien',
                'ps.nocm',
                'sbm.noclosingfk',
                DB::raw("case when sbm.noclosingfk is null then 'Belum Setor' else 'Setor' end as statussetor,
                case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien
                ")
            )
            ->where('sbm.statusenabled', true)
            ->where('sbm.kdprofile', $kdProfile);


        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('sbm.tglsbm', '>=', $r['dari'] . ' 00:00:00');
        }

        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('sbm.tglsbm', '<=',  $r['sampai'] . ' 23:59:59');
        }
        if (isset($r['setoran']) && $r['setoran'] != "" && $r['setoran'] != "undefined") {
            $data = $data->where('sbm.noclosingfk', '>=', $r['setoran']);
        }
        if (isset($r['idPegawai']) && $r['idPegawai'] != "" && $r['idPegawai'] != "undefined") {
            $data = $data->where('sbm.objectpegawaipenerimafk', '=', $r['idPegawai']);
        }
        if (isset($r['carabayar']) && $r['carabayar'] != "" && $r['carabayar'] != "undefined") {
            $data = $data->where('cb.id', '=', $r['carabayar']);
        }
        if (isset($r['KetSetor']) && $r['KetSetor'] != "" && $r['KetSetor'] != "undefined") {
            if ($r['KetSetor'] == 1) {
                $data = $data->whereNull('sc.noclosing');
            } elseif ($r['KetSetor'] == 2) {
                $data = $data->whereNotNull('sc.noclosing');
            }
        }

        if (isset($r['nosbm']) && $r['nosbm'] != "" && $r['nosbm'] != "undefined") {
            $data = $data->where('sbm.nosbm', 'ilike', '%' . $r['nosbm'] . '%');
        }


        $total = $data->get();

        $data = $data->get();

        $result = [];
        foreach ($data as $item) {
            $noclosingfk = $item->noclosingfk;
            $caraBayar = $item->caraBayar;
            $details = DB::select(
                DB::raw("
                    select
                  --count(x.id) as idCaraBayar,x.caraBayar ,sum (x.jumlah) as jumlah from(
                    distinct sck.noclosingfk,cb.id,
                    cb.carabayar as caraBayar,sh.noclosing,sck.totaldibayar as jumlah,
                    sc.totaldibayar as total,
                    sh.objectpegawaiterimafk, pg.namalengkap as pegawaipenerima
                    from strukclosingkasir_t  as sck
                    left join strukclosing_t as sc on sc.norec=sck.noclosingfk
                    left join carabayar_m as cb on cb.id=sck.carabayarfk
                    left join strukhistori_t as sh on sh.noclosing=sc.noclosing
                    LEFT JOIN carasetor_m as cs on cs.id=sck.objectcarasetorfk
                    left join pegawai_m as pg on pg.id=sh.objectpegawaiterimafk
                    where sck.kdprofile = $kdProfile and sck.noclosingfk ='$noclosingfk'
                   -- and sc.objectkelompoktransaksifk=6
                   -- and cb.carabayar ='$caraBayar'
                    GROUP BY cs.carasetor,sck.objectcarasetorfk,cb.id,
                    sck.totaldibayar, sc.totaldibayar,sck.noclosingfk, sh.noclosing,
                    sh.objectpegawaiterimafk, pg.namalengkap,sck.totaldibayar,cb.carabayar
                    --)as x GROUP BY x.caraBayar")
            );

            $result[] = array(
                'noRec' => $item->noRec,
                'caraBayar' => $item->caraBayar,
                'idCaraBayar' => $item->idCaraBayar,
                'idKelTransaksi' => $item->idKelTransaksi,
                'kelTransaksi' => $item->kelTransaksi,
                'keterangan' => $item->keterangan,
                'idPegawai' => $item->idPegawai,
                'namaPenerima' => $item->namaPenerima,
                'noClosing' => $item->noClosing,
                'noSbm' => $item->noSbm,
                'tglSbm' => $item->tglSbm,
                'noVerifikasi' => $item->noVerifikasi,
                'tglClosing' => $item->tglClosing,
                'norec_sp' => $item->norec_sp,
                'totalPenerimaan' => $item->totalPenerimaan,
                'namapasien' => $item->namapasien,
                'ruid' => $item->ruid,
                'namaruangan' => $item->namaruangan,
                'namapasien_klien' => $item->namapasien_klien,
                'noclosingfk' => $item->noclosingfk,
                'nocm' => $item->nocm,
                'statussetor' => $item->statussetor,
                'details' => $details,
            );
        }

        $cara = CaraBayar::mine()->get();
        $totalAll = 0;
        foreach ($total as $d) {
            $totalAll =    $totalAll +  (float) $d->totalPenerimaan;
            foreach ($cara as $dd) {
                if ($dd->id == $d->idCaraBayar) {
                    $dd->total = (float)  $dd->total + (float) $d->totalPenerimaan;
                }
            }
        }

        $result = array(
            'data' => $result,
            'total' => $totalAll,
            'carabayar' => $cara,
        );
        return $this->respond($result);
    }
    public function getListPilihan(Request $request)
    {
        $res['carabayar'] = CaraBayar::mine()->get();
        $res['carasetor'] = CaraSetor::mine()->get();
        $res['asalproduk'] = AsalProduk::mine()->get();

        $bp = DB::table('mapbkutokelompoktransaksi_m as mp')
            ->join('kelompoktransaksi_m as kt', 'kt.id', '=', 'mp.kelompoktransaksifk')
            ->select('kt.id', 'kt.kelompoktransaksi')
            ->where('mp.kdprofile', $this->kdProfile)
            ->whereIn('mp.idbku', explode(',', $this->settingFix('kdBKUPenerimaan')))
            // ->whereIn('mp.kelompoktransaksifk', explode(',', $this->settingFix('kdPenerimaanBP')))
            ->where('kt.statusenabled', true)
            ->where('mp.statusenabled', true);
        $res['kelompoktransaksi'] = $bp->get();

        $bk = DB::table('mapbkutokelompoktransaksi_m as mp')
            ->join('kelompoktransaksi_m as kt', 'kt.id', '=', 'mp.kelompoktransaksifk')
            ->select('kt.id', 'kt.kelompoktransaksi as pengeluaran')
            ->where('mp.kdprofile', $this->kdProfile)
            ->whereIn('mp.idbku', explode(',', $this->settingFix('kdBKUPengeluaran')))
            // ->whereIn('mp.kelompoktransaksifk', explode(',', $this->settingFix('kdPengeluaranBP')))
            ->where('kt.statusenabled', true)
            ->where('mp.statusenabled', true);
        $res['pengeluaran'] = $bk->get();



        return $this->respond($res);
    }
    public function simpanSetoran(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $kdKelTrans = (int) $this->settingFix('KdTransSetoranKasir');

        DB::beginTransaction();
        try {
            $input  = $request->all();
            $SC = new StrukClosing();
            $SC->norec  = $SC->generateNewId();
            $SC->kdprofile = $this->kdProfile;
            $SC->statusenabled = true;
            $SC->noclosing = $this->generateCode(new StrukClosing, 'noclosing', 10, 'C-' . $this->getDateTime()->format('ym'), $this->kdProfile);
            $SC->objectpegawaidiclosefk = $input['kdPegawai'];
            $SC->tglclosing = $input['tglsetor'];
            $SC->totaldibayar = $input['setoran'];
            $SC->objectkelompoktransaksifk = $kdKelTrans;
            $SC->keteranganlainnya = "Setoran Kasir";
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
            $SH->totalsetortarikdeposit = $input['setoran'];
            $SH->tglsetortarikdeposit =  $input['tglsetor'];; //$this->getDateTime();
            $SH->objectpegawaitarikdepositfk = $input['kdPegawai'];
            $SH->objectpegawaiterimafk = $this->getUserId();
            $SH->objectkelompoktransaksifk = $kdKelTrans;
            $SH->noclosing = $NorecSc; //$SC->noclosing;
            // $SH->objectcarasetorfk = $item['idCaraSetor'];
            $SH->ketlainya = 'Setoran Kasir';
            if (isset($idRuangan)) {
                $SH->objectruanganterimafk = $idRuangan;
                $SH->objectruanganfk = $idRuangan;
            }

            $SH->nobukti = '-';
            $SH->kdperkiraan = '-'; //$input['kdperkiraan'];
            $SH->namaperkiraan = 'Penerimaan Kasir '; //$input['keterangan'];
            $SH->kettransaksi = '-'; // $input['keterangantransaksi'];
            $SH->save();

            /** @Save_StrukBuktiPenerimaan */
            $strukBuktiPenerimanan = new StrukBuktiPenerimaan();
            $strukBuktiPenerimanan->norec = $strukBuktiPenerimanan->generateNewId();
            $strukBuktiPenerimanan->kdprofile = $this->kdProfile;
            $strukBuktiPenerimanan->keteranganlainnya = 'Setoran Kasir'; //nama perkiraan
            $strukBuktiPenerimanan->statusenabled = true;
            //      $strukBuktiPenerimanan->nostrukfk = $strukPelayanan->norec;
            //      $strukBuktiPenerimanan->objectkelompokpasienfk = $strukPelayanan->pasien_daftar->pasien->objectkelompokpasienfk;
            $strukBuktiPenerimanan->objectpegawaipenerimafk = $this->getUserId();
            $strukBuktiPenerimanan->tglsbm =  $input['tglsetor'];; //date('Y-m-d H:i:s');
            $strukBuktiPenerimanan->totaldibayar =  $input['setoran']; //$input['setoran'];
            $strukBuktiPenerimanan->objectkelompoktransaksifk = $kdKelTrans;
            $strukBuktiPenerimanan->nosbm = $this->generateCode(new StrukBuktiPenerimaan, 'nosbm', 14, 'RV-' . $this->getDateTime()->format('ym'), $this->kdProfile);
            $strukBuktiPenerimanan->noclosingfk = $NorecSc;
            $strukBuktiPenerimanan->save();
            /** @End_Save_StrukBuktiPenerimaan */

            foreach ($input['detailSetoran'] as $item) {
                $SCK = new StrukClosingKasir();
                $SCK->norec  = $SC->generateNewId();
                $SCK->kdprofile = $this->kdProfile;
                $SCK->statusenabled = true;
                $SCK->noclosingfk = $SC->norec;
                $SCK->totaldibayar = $item['totalPenerimaan'];
                $SCK->totaldibayarcashin = 0;
                $SCK->totaldibayarcashout = 0;
                $SCK->totaldibayarclose = 0;
                $SCK->carabayarfk = $item['kdCaraBayar'];
                $SCK->qtystrukbuktiextclose = 0;
                $SCK->qtystrukbuktiintclose = 0;
                $SCK->objectcarasetorfk = $item['idCaraSetor'];
                $SCK->save();

                /** @Save_StrukBuktiPenerimaanCaraBayar */
                $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $this->kdProfile;
                $SBPCB->statusenabled = true;
                $SBPCB->nosbmfk = $strukBuktiPenerimanan->norec;
                $SBPCB->objectcarabayarfk = $item['kdCaraBayar'];
                if (isset($input['detailBank'])) {
                    $SBPCB->objectbankaccountfk = $input['detailBank']['id'];
                    $SBPCB->namabankprovider = $input['detailBank']['namaBank'];
                    $SBPCB->namapemilik = $input['detailBank']['namaKartu'];
                }
                $SBPCB->save();
                /** @End_Save_SStrukBuktiPenerimaanCaraBayar */
            }
            foreach ($input['detailSBM'] as $item2) {
                $updateSBM = StrukBuktiPenerimaan::where('norec', $item2['norec_sbm']) //$input['kdPegawaiLu'])
                    ->where('kdprofile', $this->kdProfile)
                    ->whereNull('noclosingfk')
                    ->update([
                        'noclosingfk' => $NorecSc
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

            $transMessage = "Gagal Setor";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function daftarBKU(Request $request)
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
                'sh.objectasalprodukhasilfk',
                'psetor.namalengkap as penyetor',
                DB::raw("
                    CASE WHEN sc.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
                    INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
                    WHERE idbku IN (1) AND ks.statusenabled = TRUE) THEN COALESCE ((spp.totaldibayar), 0) END AS debit,
                    CASE WHEN sc.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
                    INNER JOIN kelompoktransaksi_m AS ks ON ks. ID = bku.kelompoktransaksifk
                    WHERE idbku IN (4) AND ks.statusenabled = TRUE) THEN coalesce((sbk.totaldibayar ), 0) END AS kredit,
                    case when spp.nosbm is null then sbk.nosbk else spp.nosbm end as notransaksi,
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
                    WHERE idbku IN (1,4) AND ks.statusenabled = TRUE)");
        // ->whereIn('sh.objectkelompoktransaksifk',$kdTrans);
        //            ->whereIn('sh.objectkelompoktransaksifk',[60,64,70,105,106,119,120]);

        $filter = $request->all();

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != 'undefined') {
            $dataPenerimaanBank = $dataPenerimaanBank->where('sh.tglsetortarikdeposit', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != 'undefined') {
            $dataPenerimaanBank = $dataPenerimaanBank->where('sh.tglsetortarikdeposit', '<=', $filter['tglAkhir']);
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
        $explode =  explode("-", $filter['tglAwal']);
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
        $tglMinSabulan = Carbon::parse($filter['tglAwal'])->subMonth(1)->startOfMonth()->addDay(1)->format('Y-m-01'); // $arr1 . '-' . $arr2 . '-' . $arr3;
        /** @End_Function */

        $tglAwal = $filter['tglAwal'];
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
                           WHERE idbku IN (1,4) AND ks.statusenabled = TRUE)
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
                'noStruk'  => $dataPenerimaan->norec,
                'tglStruk'  => $dataPenerimaan->tglsetortarikdeposit,
                'keterangan'  => $dataPenerimaan->ketlainya,
                'jenisTransaksi'  => $dataPenerimaan->kelompoktransaksi,
                'idJenisTransaksi'  => $dataPenerimaan->objectkelompoktransaksifk,
                'kredit'  => (float) $dataPenerimaan->kredit,
                'debit'  => (float)$dataPenerimaan->debit,
                'saldo'  => $saldo,
                'nontunai'  => $dataPenerimaan->nontunai,
                //                'idPegawai'  =>@$login_user->objectpegawaifk,
                //                'namaPegawai' => @$login_user->pegawai->namalengkap,
                //                'status' => $status,
                //                'idPegawai'  =>@$dataPenerimaan->objectpegawaipenerimafk,
                //                'namaPegawai' => @$dataPenerimaan->namalengkap,
                //                'nostrukfk' => $dataPenerimaan->nostrukfk,
                // 'objectbankaccountfk' => $dataPenerimaan->objectbankaccountfk,
                // 'bankaccountnama' => $dataPenerimaan->bankaccountnama,
                // 'namabankprovider' => $dataPenerimaan->namabankprovider,
                // 'namapemilik' => $dataPenerimaan->namapemilik,
                'nohistori' => $dataPenerimaan->nonhistori,
                //                'noclosing' => $dataPenerimaan->noclosing,
                'notransaksi' => $dataPenerimaan->notransaksi,
                //                'kdmataanggaran' =>$dataPenerimaan->kdchildkeempat,
                //                'mataanggaran' =>$dataPenerimaan->mataanggaran,
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

    public function simpanBKU(Request $request)
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

    public function batalSetoranKasir(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $transStatus = true;
        DB::beginTransaction();
        $input  = $request->all();

        try {
            foreach ($input['details'] as $item) {
                $sc = StrukClosing::where('noclosing', $item['noclosing'])->where('kdprofile', $idProfile)->first();

                $sh = StrukHistori::where('noclosing', $sc->norec)
                    ->where('kdprofile', $idProfile)
                    ->update([
                        'statusenabled' => false
                    ]);
                $sbm = StrukBuktiPenerimaan::where('norec', $item['norec_sbm'])
                    ->where('kdprofile', $idProfile)
                    ->where('noclosingfk', $sc->norec)
                    ->update([
                        'noclosingfk' => null
                    ]);
                $sch = StrukClosing::where('noclosing', $item['noclosing'])
                    ->where('kdprofile', $idProfile)
                    ->update([
                        "statusenabled" => false
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

    public function getDataPendapatanBP(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $tglAwal = $request['dari'];
        $tglAkhir = $request['sampai'];
        $set =  $this->settingFix('idDepartemenIGD');
        $lab =  $this->settingFix('idDepartemenLab');
        $rad =  $this->settingFix('idDepartemenRadiologi');
        $ambulan = $this->settingFix('kdAmbulance');
        $jenazah = $this->settingFix('kdJenazah');

        $data = DB::select(DB::raw("
                SELECT x.namaruangan,SUM(x.jumlah) AS jumlah,SUM(x.totalp) AS totalp,SUM(x.totalt) AS totalt
                FROM( SELECT ru.namaruangan,pp.jumlah,(((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalp,
                             0 AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE
                AND pp.produkfk in (33625,28343,30111,30110,30168,30650,31206,31207,32361,32362,33630,30151,33740,403531)
                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($set)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,0 AS totalp,
                             (((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE
                AND pp.produkfk not in (33625,28343,30111,30110,30168,30650,31206,31207,32361,32362,33630,30151,33740,403531)
                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($set)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,(((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalp,
                             0 AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE
                AND pp.produkfk in (33625,28343,30111,30110,30168,30650,31206,31207,32361,32362,33630,30151,33740,403531)
                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($lab)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,0 AS totalp,
                             (((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE
                AND pp.produkfk not in (33625,28343,30111,30110,30168,30650,31206,31207,32361,32362,33630,30151,33740,403531)
                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($lab)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,(((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalp,
                             0 AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE
                AND pp.produkfk in (33625,28343,30111,30110,30168,30650,31206,31207,32361,32362,33630,30151,33740,403531)
                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($rad)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,0 AS totalp,
                             (((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($rad)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'
                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,(((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalp,
                             0 AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE
                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($ambulan)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,0 AS totalp,
                             (((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($ambulan)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,(((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalp,
                             0 AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($jenazah)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,pp.jumlah,0 AS totalp,
                             (((CASE WHEN pp.hargajual IS NULL THEN	0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN
                             0 ELSE pp.hargadiscount	END) * pp.jumlah) + CASE WHEN pp.jasa IS NULL THEN	0 ELSE	pp.jasa	END	) AS totalt
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile = $kdProfile AND apd.statusenabled = TRUE

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($jenazah)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT namaruangan,0 AS jumlah,0 AS totalp,0 AS totalt
                FROM ruangan_m WHERE statusenabled=true AND kdprofile = $kdProfile
                AND objectdepartemenfk IN ($set,$lab,$rad,$ambulan,$jenazah)) AS x
                GROUP BY x.namaruangan
        "));

        $dataDetail = DB::select(DB::raw("
                SELECT x.namaruangan,SUM(x.jaspelp) AS jaspelp,SUM(x.jassarp) AS jassarp,
                SUM(x.jaspelt) AS jaspelt,SUM(x.jassart) AS jassart
                FROM(
                SELECT ru.namaruangan,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelp,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassarp,
                0 AS jaspelt,0 AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($set)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                0 AS jaspelp,0 AS jassarp,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelt,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($set)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelp,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassarp,
                0 AS jaspelt,0 AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($lab)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                0 AS jaspelp,0 AS jassarp,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelt,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($lab)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelp,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassarp,
                0 AS jaspelt,0 AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($rad)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                0 AS jaspelp,0 AS jassarp,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelt,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($rad)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelp,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassarp,
                0 AS jaspelt,0 AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($ambulan)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                0 AS jaspelp,0 AS jassarp,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelt,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($ambulan)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelp,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassarp,
                0 AS jaspelt,0 AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($jenazah)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT ru.namaruangan,
                0 AS jaspelp,0 AS jassarp,
                CASE WHEN ppd.komponenhargafk = 94 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jaspelt,
                CASE WHEN ppd.komponenhargafk = 93 THEN pp.jumlah*ppd.hargajual ELSE 0 END AS jassart
                FROM antrianpasiendiperiksa_t AS apd
                INNER JOIN pelayananpasien_t AS pp ON pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec
                LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                WHERE apd.kdprofile =$kdProfile AND apd.statusenabled = TRUE
                AND ppd.statusenabled = true

                AND pp.strukresepfk IS NULL AND ru.objectdepartemenfk in ($jenazah)
                AND pp.tglpelayanan BETWEEN '$tglAwal' AND '$tglAkhir'

                UNION ALL

                SELECT namaruangan,0 AS jaspelp,0 AS jassarp,0 AS jaspelt,0 AS jassart
                FROM ruangan_m WHERE statusenabled=true AND kdprofile= $kdProfile
                AND objectdepartemenfk IN ($set,$lab,$rad,$ambulan,$jenazah) ) AS x
                GROUP BY x.namaruangan
        "));

        $i = 0;
        foreach ($data as $items) {
            foreach ($dataDetail as $dD) {
                //                $data[$i]->jaspelp = 0;
                //                $data[$i]->jassarp = 0;
                //                $data[$i]->jaspelt = 0;
                //                $data[$i]->jassart = 0;
                if ($data[$i]->namaruangan == $dD->namaruangan) {
                    $data[$i]->jaspelp = $dD->jaspelp;
                    $data[$i]->jassarp = $dD->jassarp;
                    $data[$i]->jaspelt = $dD->jaspelt;
                    $data[$i]->jassart = $dD->jassart;
                }
            }
            $i = $i + 1;
        }

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    // function getLapKasir(Request $request)
    // {
    //     $kdProfile = $this->kdProfile;
    //     $idPegawai = $this->getPegawaiId();
    //     $tglAwal = $request['tglAwal'];
    //     $tglAkhir = $request['tglAkhir'];
    //     $namapegawai = $this->getNamaPegawai();

    //     $idKasir = '';
    //     $idRuangan = '';
    //     $idKelompokPasien = '';
    //     // if (isset($request['idKasir']) && $request['idKasir'] != "" && $request['idKasir'] != "undefined") {
    //     //     $idKasir = 'AND pg2.id =' . $request['idKasir'];
    //     // }
    //     if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
    //         $idRuangan = 'AND sbm.ruanganfk =' . $request['idRuangan'];
    //     }
    //     if (isset($request['idKelompokPasien']) && $request['idKelompokPasien'] != "" && $request['idKelompokPasien'] != "undefined") {
    //         $idKelompokPasien = 'AND kp.id =' . $request['idKelompokPasien'];
    //     }
    //     $ruangan = null;
    //     $data = DB::table('strukbuktipenerimaan_t as sbm')
    //         ->leftJOIN('strukbuktipenerimaancarabayar_t as sbmc', 'sbmc.nosbmfk', '=', 'sbm.norec')
    //         ->leftJOIN('carabayar_m as cb', 'cb.id', '=', 'sbmc.objectcarabayarfk')
    //         ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'sbm.nostrukfk')
    //         ->leftJOIN('loginuser_s as lu', 'lu.id', '=', 'sbm.objectpegawaipenerimafk')
    //         ->leftJOIN('pegawai_m as pg2', 'pg2.id', '=', 'lu.objectpegawaifk')
    //         ->leftJOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
    //         ->leftJOIN('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
    //         ->leftJOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
    //         ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
    //         ->leftJOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
    //         ->leftJoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
    //         ->leftJOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
    //         ->select(
    //             'sbm.tglsbm',
    //             'sbm.nosbm',
    //             'totaldibayarbefore',
    //             'ps.nocm',
    //             'ru.namaruangan',
    //             'pg.namalengkap',
    //             'pg2.namalengkap as kasir',
    //             'sp.totalharusdibayar',
    //             'sbmc.totaldibayar',
    //             'sbm.keteranganlainnya',
    //             'cb.carabayar',
    //             'sbmc.objectcarabayarfk',
    //             DB::raw('( case when pd.noregistrasi is null then sp.nostruk else pd.noregistrasi end) as noregistrasi,
    //         (case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end) as namapasien,
    //         (case when kp.kelompokpasien is null then null else kp.kelompokpasien end) as kelompokpasien,
    //         (CASE WHEN sp.totalprekanan is null then 0 else sp.totalprekanan end) as hutangpenjamin,
    //         (case when cb.id = 1 then sbmc.totaldibayar else 0 end) as tunai,
    //         (case when cb.id != 1 then sbmc.totaldibayar else 0 end) as nontunai')
    //         )
    //         ->where('sbm.kdprofile', $kdProfile)
    //         ->where('sbm.statusenabled', true);

    //     if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
    //         $data = $data->where('sbm.tglsbm', '>=', $request['tglAwal'] . ' 00:00:00');
    //     }
    //     if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
    //         $tgl = $request['tglAkhir'] . ' 23:59:59';
    //         $data = $data->where('sbm.tglsbm', '<=', $tgl);
    //     }
    //     if (isset($request['idKasir']) && $request['idKasir'] != "" && $request['idKasir'] != "undefined") {
    //         $data = $data->where('sbm.objectpegawaipenerimafk', '=', $request['idKasir']);
    //     }
    //     if (isset($request['idDokter']) && $request['idDokter'] != "" && $request['idDokter'] != "undefined") {
    //         $data = $data->where('pd.objectpegawaifk', '=', $request['idDokter']);
    //     }
    //     if (isset($request['idDept']) && $request['idDept'] != "" && $request['idDept'] != "undefined") {
    //         $data = $data->where('ru.objectdepartemenfk', '=', $request['idDept']);
    //     }
    //     if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
    //         $data = $data->where('sbm.ruanganfk', '=', $request['idRuangan']);
    //         $ruangan = Ruangan::where('id', $request['idRuangan'])->first()->namaruangan;
    //     }
    //     if (isset($request['idKelompokPasien']) && $request['idKelompokPasien'] != "" && $request['idKelompokPasien'] != "undefined") {
    //         $data = $data->where('kp.kdkelompokpasien', '=', $request['idKelompokPasien']);
    //     }
    //     $data->when($request->namapasien, function ($query) use ($request) {
    //         return $query->where('ps.namapasien', 'like', '%' . $request->namapasien . '%');
    //     });


    //     $data = $data->orderBy('pd.noregistrasi', 'ASC');

    //     $data = $data->get();

    //     // dd($data);

    //     $totalsaldo = 0;
    //     foreach ($data as $d) {
    //         $totalsaldo += $d->totaldibayar;
    //     }
    //     $terbilang = $this->terbilang($totalsaldo);
    //     $profile = Profile::where('id', $this->kdProfile)->first();

    //     $carabayar = CaraBayar::mine()->get();
    //     $totalAll = 0;
    //     foreach ($data as $d) {
    //         $totalAll =    $totalAll +  (float) $d->totaldibayar;
    //         foreach ($carabayar as $dd) {
    //             if ($dd->id == $d->objectcarabayarfk) {
    //                 $dd->total = (float)  $dd->total + (float) $d->totaldibayar;
    //             }
    //         }
    //     }

    //     if ($request['pdf'] == 'true') {
    //         $pdf = App::make('dompdf.wrapper');
    //         $pdf->setPaper('A4', 'landscape');

    //         $pdf->loadView(
    //             'report.kasir.laporan-penerimaan-kasir-harian',
    //             array(
    //                 'data' => $data,
    //                 'namaPegawai' => $namapegawai,
    //                 'carabayar' => $carabayar,
    //                 'terbilang' => $terbilang,
    //                 'tglAwal' => $tglAwal,
    //                 'tglAkhir' => $tglAkhir,
    //                 'profile' => $profile,
    //                 'ruangan' => $ruangan,
    //                 'res' => array(
    //                     'pdf' => true
    //                 ),
    //             )
    //         );
    //         return $pdf->stream();
    //     } else {
    //         return view(
    //             'report.bendahara.lap-penerimaan-kasir',
    //             compact('data', 'terbilang', 'tglAwal', 'tglAkhir', 'profile', 'carabayar', 'namaPegawai', 'ruangan')
    //         );
    //     }
    // }


}
