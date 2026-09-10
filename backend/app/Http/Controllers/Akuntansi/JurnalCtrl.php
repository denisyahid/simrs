<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\ChartOfAccountMapJurnal;
use App\Models\Transaksi\PostingJurnal;
use App\Models\Transaksi\PostingJurnalD;
use App\Models\Transaksi\PostingJurnalTransaksi;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;

class JurnalCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDetailJurnalRev2018(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $data = DB::select(
            DB::raw("select pj.norec, pjd.objectaccountfk as accountid, pj.nojurnal,coa.noaccount,
                case when pjd.hargasatuand = 0 then '--- ' || coa.namaaccount else coa.namaaccount end as namaaccount,
                pj.namaproduktransaksi as keteranganlainnya,pjd.hargasatuand,pjd.hargasatuank from postingjurnaltransaksi_t as pj
                INNER JOIN postingjurnaltransaksid_t as pjd on pj.norec=pjd.norecrelated
                INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                where pj.kdprofile = $idProfile and nojurnal_intern=:nojurnal;"),
            array(
                'nojurnal' => $request['nojurnal'],
            )
        );
        return $this->respond($data);
    }
    public function getDetailJurnalPosting(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $data = DB::select(
            DB::raw("select pjd.objectaccountfk as accountid, coa.noaccount,
                case when pjd.hargasatuand = 0 then '--- ' || coa.namaaccount else coa.namaaccount end as namaaccount,
                sum(pjd.hargasatuand) as hargasatuand,sum(pjd.hargasatuank) as hargasatuank, '' as keteranganlainnya,
                '' as nojurnal from postingjurnaltransaksi_t as pj
                INNER JOIN postingjurnaltransaksid_t as pjd on pj.norec=pjd.norecrelated
                INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                where pj.kdprofile = $idProfile and pj.nojurnal_intern=:nojurnal
                group by pjd.objectaccountfk, coa.noaccount,case when pjd.hargasatuand = 0 then '--- ' || coa.namaaccount else coa.namaaccount end
                order by sum(pjd.hargasatuank);"),
            array(
                'nojurnal' => $request['nojurnal'],
            )
        );
        return $this->respond($data);
    }
    public function PostingJurnalRev2018(Request $request)
    {
        DB::beginTransaction();

        $idProfile = (int) $this->kdProfile;
        $ceksudahposting =  PostingJurnal::where('norecrelated', $request['nojurnal'])->where('kdprofile', $idProfile)->get();
        $newPJT = '';
        if (count($ceksudahposting) == 0) {
            try {
                $transStatus = 'true';
                $noPosting = $this->generateCode(new PostingJurnal, 'nojurnal_intern', 10, 'PJ' . $this->getDateTime()->format('ym'), $idProfile);
                $nojurnal =  PostingJurnal::max('nojurnal');
                $nojurnal = $nojurnal + 1;

                $newPJT = new PostingJurnal();
                $norecHead = $newPJT->generateNewId();
                $newPJT->norec = $norecHead;
                $newPJT->kdprofile = $idProfile;
                $newPJT->noposting = $noPosting;
                $newPJT->objectjenisjurnalfk = 1;
                $newPJT->nobuktitransaksi = $request['nojurnal'];
                $newPJT->nojurnal = $nojurnal;
                $newPJT->nojurnal_intern = $noPosting;
                $newPJT->tglbuktitransaksi = $request['tglbuktitransaksi']; //$this->getDateTime()->format('Y-m-d H:i:s');
                //            $newPJT->kdproduk = null;
                //            $newPJT->namaproduktransaksi = null;
                $newPJT->deskripsiproduktransaksi = $request['keteranganlainnya'];
                $newPJT->keteranganlainnya = $request['keteranganlainnya'];
                $newPJT->statusenabled = 1;
                $newPJT->norecrelated = $request['nojurnal'];;
                $newPJT->save();

                $norecHead2 = $newPJT->norec;

                foreach ($request['data'] as $item) {
                    //debet
                    $nojurnald =  PostingJurnalD::max('nojurnal');
                    $nojurnald = $nojurnald + 1;

                    $newPJD = new PostingJurnalD();
                    $newPJD->norec = $newPJD->generateNewId();
                    $newPJD->kdprofile = $idProfile;
                    $newPJD->noposting = $noPosting;
                    $newPJD->nojurnal = $nojurnal;
                    $newPJD->objectaccountfk = $item['accountid'];
                    $newPJD->hargasatuand = $item['hargasatuand'];
                    $newPJD->hargasatuank = $item['hargasatuank'];
                    $newPJD->statusenabled = 1;
                    $newPJD->norecrelated = $norecHead2;
                    $newPJD->save();
                }
                $transStatus = 'true';
                $transMessage = "Posting Jurnal" . " Berhasil";
            } catch (\Exception $e) {
                $transStatus = 'false';
                $transMessage = "Posting Jurnal" . " Gagal " . $e->getMessage() . $e->getLine();
            }
        } else {
            $transStatus = 'true';
            $transMessage = "Sudah Posting Jurnal";
        }

        if ($transStatus == 'true') {

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $newPJT,
                    "as" => '@epic',
                ),
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function UnPostingJurnalRev2018(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();

        try {
            $data =  PostingJurnal::where('norecrelated', $request['nojurnal'])->where('kdprofile', $idProfile)->get();
            foreach ($data as $item) {
                PostingJurnalD::where('norecrelated', $item->norec)->where('kdprofile', $idProfile)->delete();
            }
            PostingJurnal::where('norecrelated', $request['nojurnal'])->where('kdprofile', $idProfile)->delete();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = "Posting Jurnal";


        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function BengkelJurnal(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        //        $dataLogin = $request->all();
        $noJurnal = $request['nojurnal'];
        try {
            $countDeleteNa = 0;
            $delDetail = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (
                        select
                        pj.norec
                        from postingjurnaltransaksi_t as pj
                        INNER JOIN postingjurnaltransaksid_t as pjd on pj.norec=pjd.norecrelated
                        INNER JOIN pelayananpasien_t as pp on pp.norec=pj.norecrelated
                        INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                        INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                        INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                        INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
                        INNER JOIN ruangan_m as ru2 on ru.id=pd.objectruanganlastfk
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        where pj.kdprofile = $idProfile and nojurnal_intern='$noJurnal' and pjd.hargasatuank >0
                        and case when pjd.hargasatuand =0 then  pjd.hargasatuank else pjd.hargasatuand end  <>
                        ((((case when pp.hargajual is null then 0 else pp.hargajual end )-case when pp.hargadiscount is null then 0 else pp.hargadiscount end ) * pp.jumlah ) + case when pp.jasa is null then 0 else pp.jasa end)
                        and pj.deskripsiproduktransaksi = 'pelayananpasien_t' and pj.jenis <> ru2.jenis

                    );
                "
            );
            // --(case when pp.hargajual is null then 0 else pp.hargajual end-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)) *pp.jumlah

            $delDetail = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (
                        select
                        pj.norec
                        from postingjurnaltransaksi_t as pj
                        INNER JOIN postingjurnaltransaksid_t as pjd on pj.norec=pjd.norecrelated
                        INNER JOIN strukpelayanandetail_t as spd on spd.norec=pj.norecrelated
                        inner join strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                        where pj.kdprofile = $idProfile and pj.nojurnal_intern='$noJurnal' and pjd.hargasatuank >0
                        and case when pjd.hargasatuand =0 then  pjd.hargasatuank else pjd.hargasatuand end  <>
                        ((spd.hargasatuan  )*spd.qtyproduk)+ spd.hargatambahan
                         and substring(sp.nostruk,1,3)='OB/' and pj.deskripsiproduktransaksi = 'pelayananpasien_tob'
                    );
                "
            );
            $delHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (
                        select pj.norec from postingjurnaltransaksi_t as pj
                        left JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                        where pj.kdprofile = $idProfile and pj.nojurnal_intern='$noJurnal' and pjd.norec is null
                      );
                "
            );

            $delPelayanan = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                    left JOIN pelayananpasien_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                    where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='pelayananpasien_t' and pp.norec is null and posted.nojurnal_intern is null
                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal')"
            );
            $delPelayananHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                    left JOIN pelayananpasien_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                    where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='pelayananpasien_t' and pp.norec is null and posted.nojurnal_intern is null
                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );

            $delObatBebas = DB::delete(
                "
                        delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                        left JOIN strukpelayanandetail_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                        left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                        where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='pelayananpasien_tob' and pp.norec is null and posted.nojurnal_intern is null
                        and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal')"
            );
            $delObatBebasHead = DB::delete(
                "
                        delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                        left JOIN strukpelayanandetail_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                        left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                        where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='pelayananpasien_tob' and pp.norec is null and posted.nojurnal_intern is null
                        and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );

            $delVerifTarek = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                    INNER JOIN  strukpelayanan_t as pp on pp.norec=pjt.norecrelated and pp.tglstruk >'2019-01-01 00:00'
                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                    where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='verifikasi_tarek' and pp.statusenabled='f' and posted.nojurnal_intern is null
                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );
            $delVerifTarekHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                    INNER JOIN  strukpelayanan_t as pp on pp.norec=pjt.norecrelated and pp.tglstruk >'2019-01-01 00:00'
                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                    where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='verifikasi_tarek' and pp.statusenabled='f' and posted.nojurnal_intern is null
                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );

            $delDeposit = DB::delete(
                "
                delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                INNER JOIN  strukbuktipenerimaan_t as pp on pp.norec=pjt.norecrelated and pp.tglsbm >'2019-01-01 00:00'
                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='penerimaan_deposit' and pp.norec is null and posted.nojurnal_intern is null
                and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );
            $delDepositHead = DB::delete(
                "
                delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                INNER JOIN  strukbuktipenerimaan_t as pp on pp.norec=pjt.norecrelated and pp.tglsbm >'2019-01-01 00:00'
                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='penerimaan_deposit' and pp.norec is null and posted.nojurnal_intern is null
                and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );
            $delDiskon = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                    INNER JOIN  pelayananpasien_t as pp on pp.norec =pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                    where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='diskon' and pp.norec is null and posted.nojurnal_intern is null
                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );
            $delDiskonHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                    INNER JOIN  pelayananpasien_t as pp on pp.norec =pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                    where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='diskon' and pp.norec is null and posted.nojurnal_intern is null
                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );
            $delPenerimaanKas = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                INNER JOIN strukbuktipenerimaancarabayar_t as sbmc on sbmc.norec=pjt.norecrelated
                INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk and sbm.tglsbm  >'2019-01-01 00:00'
                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='penerimaan_kas' and (sbmc.totaldibayar is null or sbmc.totaldibayar = 0) and posted.nojurnal_intern is null
                and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal') "
            );
            $delPenerimaanKasHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                INNER JOIN strukbuktipenerimaancarabayar_t as sbmc on sbmc.norec=pjt.norecrelated
                INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk and sbm.tglsbm  >'2019-01-01 00:00'
                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='penerimaan_kas' and (sbmc.totaldibayar is null or sbmc.totaldibayar = 0) and posted.nojurnal_intern is null
                and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal')"
            );

            $delBebanPelayanan = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                    left JOIN pelayananpasien_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                    where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='bebanpelayananpasien_t' and pp.norec is null and posted.nojurnal_intern is null
                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal')"
            );
            $delBebanPelayananHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                    left JOIN pelayananpasien_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                    where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='bebanpelayananpasien_t' and pp.norec is null and posted.nojurnal_intern is null
                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );

            $delObatBebasbeban = DB::delete(
                "
                        delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                        left JOIN strukpelayanandetail_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                        left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                        where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='bebanpelayananpasien_t' and pp.norec is null and posted.nojurnal_intern is null
                        and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal')"
            );
            $delObatBebasbebanHead = DB::delete(
                "
                        delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                        left JOIN strukpelayanandetail_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                        left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                        where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='bebanpelayananpasien_t' and pp.norec is null and posted.nojurnal_intern is null
                        and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal');"
            );
            //            $countDeleteNa =count($delHead) + count($delPelayananHead) + count($delObatBebasHead) + count($delVerifTarekHead) +
            //                count($delDepositHead) + count($delDiskonHead) + count($delPenerimaanKasHead) + count($delTerimaBarangHead) + count($delAmprahanHead);


            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = "Perbaikan Jurnal";


        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => 0,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function HapusDoubleJurnal(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        //“Don’t stop when you’re tired; stop when you’re done.”
        //— Marilyn Monroe
        DB::beginTransaction();
        //        $dataLogin = $request->all();
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $noJurnal = $request['nojurnal'];
        try {
            $delDetailVerif = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (
                        select pjt.norec from postingjurnaltransaksi_t as pjt
                        INNER JOIN strukpelayanan_t as sp on sp.norec=pjt.norecrelated
                        where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='verifikasi_tarek' and sp.statusenabled = 'f'
                        and nojurnal_intern='$noJurnal')
                "
            );
            $delHeadVerif = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (
                        select pjt.norec from postingjurnaltransaksi_t as pjt
                        INNER JOIN strukpelayanan_t as sp on sp.norec=pjt.norecrelated
                        where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='verifikasi_tarek' and sp.statusenabled = 'f'
                        and nojurnal_intern='$noJurnal');
                "
            );
            $delDetail = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (
                    select norec from (select norec, row_number() over (partition by norecrelated order by norec desc) as rownum  from postingjurnaltransaksi_t
                    where kdprofile = $idProfile and
                    --deskripsiproduktransaksi='pelayananpasien_t' and
                    tglbuktitransaksi between '$tglAwal' and '$tglAkhir'  and nojurnal_intern='$noJurnal')
                    as x
                    where x.rownum >1)
                "
            );
            $delHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (
                    select norec from (select norec, row_number() over (partition by norecrelated order by norec desc) as rownum  from postingjurnaltransaksi_t
                    where kdprofile = $idProfile and
                    --deskripsiproduktransaksi='pelayananpasien_t' and
                    tglbuktitransaksi between '$tglAwal' and '$tglAkhir' and nojurnal_intern='$noJurnal')
                    as x where x.rownum >1);
                "
            );
            $delTerimaBarang = DB::delete(
                "
                delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                INNER JOIN strukpelayanan_t as sp on sp.norec=pjt.norecrelated and sp.tglstruk >'2019-01-01 00:00'
                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='penerimaan_barang' and sp.norec is null  and posted.nojurnal_intern is null
                and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal') "
            );
            $delTerimaBarangHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                INNER JOIN strukpelayanan_t as sp on sp.norec=pjt.norecrelated and sp.tglstruk >'2019-01-01 00:00'
                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='penerimaan_barang'  and sp.norec is null  and posted.nojurnal_intern is null
                and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal')"
            );
            $delAmprahan = DB::delete(
                "
                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                INNER JOIN strukkirim_t as sp on sp.norec=pjt.norecrelated and sp.tglkirim  >'2019-01-01 00:00'
                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='amprahan_barang_ruangan' and sp.norec is null  and posted.nojurnal_intern is null
                and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal')"
            );
            $delAmprahanHead = DB::delete(
                "
                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                INNER JOIN strukkirim_t as sp on sp.norec=pjt.norecrelated and sp.tglkirim  >'2019-01-01 00:00'
                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                where pjt.kdprofile = $idProfile and pjt.deskripsiproduktransaksi='amprahan_barang_ruangan' and sp.norec is null  and posted.nojurnal_intern is null
                and pjt.tglbuktitransaksi  >'2019-01-01 00:00' and pjt.nojurnal_intern='$noJurnal')"
            );
            //            $jumlahna = count($delHead);
            //            $delDetail2 = DB::delete("
            //                    delete from postingjurnaltransaksid_t where norecrelated in (select pj.norec from postingjurnaltransaksi_t as pj
            //                        INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
            //                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
            //                        INNER JOIN pelayananpasien_t as pp on pp.norec=pj.norecrelated
            //                        INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
            //                        INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
            //                        inner join produk_m as pr on pr.id=pp.produkfk
            //                        inner join detailjenisproduk_m as djp on djp.id=pr.objectdetailjenisprodukfk
            //                        where pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir' and pjd.hargasatuank >0 and pj.nojurnal_intern='$noJurnal'
            //                        and coa.namaaccount not like '%' + ru.namaruangan + '' and pp.strukresepfk is null
            //                        and djp.objectjenisprodukfk <> 97 and pj.deskripsiproduktransaksi='pelayananpasien_t'
            //                        and pp.produkfk not in (10011572,10011571))
            //                "
            //            );
            //            $delHead2 = DB::delete("
            //                    delete from postingjurnaltransaksi_t where norec in (select pj.norec from postingjurnaltransaksi_t as pj
            //                        INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
            //                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
            //                        INNER JOIN pelayananpasien_t as pp on pp.norec=pj.norecrelated
            //                        INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
            //                        INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
            //                        inner join produk_m as pr on pr.id=pp.produkfk
            //                        inner join detailjenisproduk_m as djp on djp.id=pr.objectdetailjenisprodukfk
            //                        where pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir' and pjd.hargasatuank >0 and pj.nojurnal_intern='$noJurnal'
            //                        and coa.namaaccount not like '%' + ru.namaruangan + '' and pp.strukresepfk is null
            //                        and djp.objectjenisprodukfk <> 97 and pj.deskripsiproduktransaksi='pelayananpasien_t'
            //                        and pp.produkfk not in (10011572,10011571))
            //                "
            //            );
            //            $jumlahna = $jumlahna  + count($delHead2);

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = "Perbaikan Jurnal";


        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => 0,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getCoaSaeutik(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $req = $request->all();
        $data = [];
        $data = DB::table('chartofaccount_m as pr')
            ->select('pr.id', 'pr.noaccount', 'pr.namaaccount')
            ->join('suratkeputusan_m as sk', 'sk.id', '=', 'pr.suratkeputusanfk')
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', '=', true)
            //            ->where('pr.namaexternal', '2018-03-01')
            ->where('sk.statusenabled', '=', true)
            ->orderBy('pr.noaccount');

        //        if ($request['jenis'] != 'noaccount'){
        if (
            isset($req['filter']['filters'][0]['value']) &&
            $req['filter']['filters'][0]['value'] != "" &&
            $req['filter']['filters'][0]['value'] != "undefined"
        ) {
            $data = $data->where('pr.namaaccount', 'ilike', '%' . $req['filter']['filters'][0]['value'] . '%')
                ->orWhere('pr.noaccount', 'ilike', $req['filter']['filters'][0]['value'] . '%');
        };
        if (
            isset($req['name']) &&
            $req['name'] != "" &&
            $req['name'] != "undefined"
        ) {
            $data = $data->where('pr.namaaccount', 'ilike', '%' . $req['name'] . '%')
                ->orWhere('pr.noaccount', 'ilike', $req['name'] . '%');
        };
        if (
            isset($req['limit']) &&
            $req['limit'] != "" &&
            $req['limit'] != "undefined"
        ) {
            $data = $data->take($req['limit'] . '%');
        } else {
            $data = $data->take(10);
        }



        $data = $data->get();
        return $this->respond($data);
    }
    public function PostingJurnal_entry(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $dataReq = $request->all();

        try {
            if ($dataReq['head']['nojurnal'] == '-') {
                $noBuktiTransaksi = '-';
                $noPosting = '-';
                $noJurnalIntern = $this->generateCode(new PostingJurnalTransaksi, 'nojurnal_intern', 13, Carbon::parse($dataReq['head']['tglentry'])->format('ym') . 'MJ' . Carbon::parse($dataReq['head']['tglentry'])->format('d'), $idProfile);

                $nojurnal = 1; //$this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                $postingJurnalTransaksi = new PostingJurnalTransaksi;
                $norecHead = $postingJurnalTransaksi->generateNewId();
                $postingJurnalTransaksi->norec = $norecHead;
                $postingJurnalTransaksi->kdprofile = $idProfile;
                $postingJurnalTransaksi->noposting =  $noPosting;
                $postingJurnalTransaksi->nojurnal = $nojurnal;
                $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                $postingJurnalTransaksi->tglbuktitransaksi = $dataReq['head']['tglentry'];
                $postingJurnalTransaksi->kdproduk = null;
                $postingJurnalTransaksi->namaproduktransaksi = 'Manual Jurnal';
                $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_t_manual';
                $postingJurnalTransaksi->statusenabled = 1;
                $postingJurnalTransaksi->norecrelated = null;
            } else {

                $postingJurnalTransaksi = PostingJurnalTransaksi::where('nojurnal_intern', $dataReq['head']['nojurnal'])
                    ->where('kdprofile', $idProfile)
                    ->first();
                $nojurnal2 = $dataReq['head']['nojurnal'];
                $delDetail = DB::select(
                    DB::raw("
                    delete from postingjurnaltransaksid_t
                    where norecrelated in (select norec from postingjurnaltransaksi_t where kdprofile = $idProfile and nojurnal_intern='$nojurnal2');
                  ")
                );
                //                $newPPD = PostingJurnalTransaksiD::where('norecrelated', $postingJurnalTransaksi->norec)->delete();
                $nojurnal = $postingJurnalTransaksi->nojurnal;
                $postingJurnalTransaksi->tglbuktitransaksi = $dataReq['head']['tglentry'];
                if ($postingJurnalTransaksi->tglbuktitransaksi != $dataReq['head']['tglentry']) {
                    $noJurnalIntern = $this->generateCode(new PostingJurnalTransaksi, 'nojurnal_intern', 13, Carbon::parse($dataReq['head']['tglentry'])->format('ym') . 'MJ' . Carbon::parse($dataReq['head']['tglentry'])->format('d'), $idProfile);
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                }

                $noPosting = '-';
                $norecHead = $postingJurnalTransaksi->norec;
            }

            $postingJurnalTransaksi->keteranganlainnya = $dataReq['head']['deskripsi'];
            $postingJurnalTransaksi->save();

            foreach ($dataReq['detail'] as $item) {
                $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                $postingJurnalTransaksiD->kdprofile = $idProfile;
                $postingJurnalTransaksiD->nojurnal = $nojurnal;
                $postingJurnalTransaksiD->noposting = $noPosting;
                $postingJurnalTransaksiD->objectaccountfk = $item['accountid'];;
                $postingJurnalTransaksiD->hargasatuand = $item['hargasatuand'];
                $postingJurnalTransaksiD->hargasatuank = $item['hargasatuank'];
                $postingJurnalTransaksiD->statusenabled = 1;
                $postingJurnalTransaksiD->norecrelated = $norecHead;
                $postingJurnalTransaksiD->save();
            }
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Entry ';

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $postingJurnalTransaksi,
                    "as" => 'as@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "e" => $e->getMessage() . ' ' . $e->getLine(),
                    "as" => 'as@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingHapusJurnal_entry(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $dataReq = $request->all();
        $nojurnal = $dataReq['head'];
        try {
            if ($dataReq['head'] != '-') {


                $norec_head = DB::select("select norec from postingjurnaltransaksi_t where kdprofile = $idProfile and nojurnal_intern='$nojurnal'");
                $norec = $norec_head[0]->norec;

                $delDetail = DB::raw("
                        delete from postingjurnaltransaksid_t
                        where norecrelated in (select norec from postingjurnaltransaksi_t where kdprofile = $idProfile and  nojurnal_intern='$nojurnal');
                    ");
                // $HapusPPD = PostingJurnalTransaksiD::where('norecrelated','=',$norec)->where('kdprofile', $idProfile)->delete();

                $HapusPPD2 = PostingJurnalTransaksi::where('nojurnal_intern', '=', $nojurnal)->where('kdprofile', $idProfile)->delete();
                //                $delDetail = DB::raw("
                //                    delete from postingjurnaltransaksid_t
                //                    where norecrelated in (select norec from postingjurnaltransaksi_t where nojurnal_intern='$nojurnal');
                //                ");
                //                $delHead = DB::raw("
                //                    delete from postingjurnaltransaksi_t where nojurnal_intern='$nojurnal'
                //                ");

            }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Hapus';

        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => 'as@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "as" => 'as@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function downloadTemplate(Request $request)
    {

        $pathbundle = 'import/akuntansi/format_import_jurnal.xlsx';
        $name = 'Template Jurnal.xlsx';
        $path =  public_path($pathbundle);
        if (File::exists($path)) {
            $file = File::get($path);

            $type = File::mimeType($path);

            $response = response()->make($file, 200);
            $response->header("Content-Type", $type)
                ->header('Content-disposition', 'attachment; filename="' . $name . '"');
            return $response;
        } else {
            return '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';
        }
    }
    public function getInputJurnalManualFromFileExcel(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $dataLogin = $request->all();
        $arr_fix = $dataLogin['data'];
        $kode = [];

        try {

            $arr = $request['data'];


            $data = DB::table('chartofaccount_m as coa')
                ->select(
                    'coa.norec',
                    'coa.id',
                    'coa.noaccount',
                    'coa.namaaccount',
                    'coa.objectjenisaccountfk',
                    'ja.jenisaccount',
                    'coa.objectkategoryaccountfk',
                    'ka.kategoryaccount',
                    'coa.objectstatusaccountfk',
                    'sa.statusaccount',
                    'coa.objectstrukturaccountfk',
                    'sta.strukturaccount',
                    'coa.saldonormaladd',
                    'coa.saldonormalmin',
                    'coa.statusenabled'
                )
                ->leftJOIN('jenisaccount_m as ja', 'ja.id', '=', 'coa.objectjenisaccountfk')
                ->leftJOIN('kategoryaccount_m as ka', 'ka.id', '=', 'coa.objectkategoryaccountfk')
                ->leftJOIN('statusaccount_m as sa', 'sa.id', '=', 'coa.objectstatusaccountfk')
                ->leftJOIN('strukturaccount_m as sta', 'sta.id', '=', 'coa.objectstrukturaccountfk')
                ->JOIN('suratkeputusan_m as sk', 'sk.id', '=', 'coa.suratkeputusanfk')
                ->where('coa.kdprofile', $idProfile)
                ->where('coa.statusenabled', '=', 1)
                ->where('sk.statusenabled', '=', 1)
                ->orderBy('coa.noaccount');
            $data = $data->get();

            $nocoaexd = '';
            $nocoaexk = '';
            $samadebit = false;
            $samakredit = false;
            $gaBisaSave = true;

            $nocoadid = '';
            $nocoakid = '';
            $listakun = '';
            foreach ($arr as $ketanItem) {

                $samadebit = false;
                $samakredit = false;

                $nocoaexd = $ketanItem['noaccountd'];
                $nocoaexk = $ketanItem['noaccountk'];
                $deskripsi = $ketanItem['deskripsi'];
                $tglTransaksi = $ketanItem['tgltransaksi'];

                $nocoadid = '';
                $nocoakid = '';

                foreach ($data as $dt) {

                    $nocoa =$dt->noaccount;// str_replace('.', '', $dt->noaccount);
                    $nocoadid = '';


                    if ($nocoa == $nocoaexd) {
                        $nocoadid = $dt->id;
                        $samadebit = true;

                        break;
                    }
                }


                foreach ($data as $dt) {

                    $nocoa =$dt->noaccount;// str_replace('.', '', $dt->noaccount);

                    $nocoakid = '';

                    if ($nocoa == $nocoaexk) {
                        $nocoakid = $dt->id;
                        $samakredit = true;
                        break;
                    }
                }


                if ($samakredit == true && $samadebit == true) {

                    // $noJurnalIntern = Carbon::parse($ketanItem['tgltransaksi'])->format('ym') . 'MJ' . Carbon::parse($ketanItem['tgltransaksi'])->format('d') . '00014';

                    $datapj = DB::table('postingjurnaltransaksi_t as pj')
                        ->select('pj.nojurnal_intern', 'pj.deskripsiproduktransaksi')
                        ->where('pj.kdprofile', $idProfile)
                        ->where('pj.deskripsiproduktransaksi', '=', $deskripsi)
                        ->whereDate('pj.tglbuktitransaksi', '=', $tglTransaksi);
                    $datapj = $datapj->get();


                    if (count($datapj) == 0) {
                        $noJurnalIntern = $this->generateCode(new PostingJurnalTransaksi, 'nojurnal_intern', 13, Carbon::parse($ketanItem['tgltransaksi'])->format('ym') . 'MJ' . Carbon::parse($ketanItem['tgltransaksi'])->format('d'), $idProfile);

                    } else {
                        $noJurnalIntern = $datapj[0]->nojurnal_intern;
                    }



                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $idProfile;
                    $postingJurnalTransaksi->noposting = '-';
                    $postingJurnalTransaksi->nojurnal = 0; //$nojurnal
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $ketanItem['nomorbukti'];
                    $postingJurnalTransaksi->tglbuktitransaksi = $tglTransaksi;
                    $postingJurnalTransaksi->kdproduk = null;
                    $postingJurnalTransaksi->namaproduktransaksi = $ketanItem['deskripsi'];
                    $postingJurnalTransaksi->deskripsiproduktransaksi = $ketanItem['deskripsi'];
                    $postingJurnalTransaksi->keteranganlainnya = $ketanItem['deskripsi'] . ' ' . $tglTransaksi;
                    $postingJurnalTransaksi->statusenabled = true;
                    $postingJurnalTransaksi->norecrelated = null;
                    $postingJurnalTransaksi->save();


                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $idProfile;
                    $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                    $postingJurnalTransaksiD->noposting = '-';
                    $postingJurnalTransaksiD->objectaccountfk = $nocoadid;
                    $postingJurnalTransaksiD->hargasatuand = (float) $ketanItem['nilai'];
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = true;
                    $postingJurnalTransaksiD->norecrelated = $norecHead;
                    $postingJurnalTransaksiD->save();
                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $idProfile;
                    $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                    $postingJurnalTransaksiD->noposting = '-';
                    $postingJurnalTransaksiD->objectaccountfk = $nocoakid;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank =  (float) $ketanItem['nilai'];
                    $postingJurnalTransaksiD->statusenabled = true;
                    $postingJurnalTransaksiD->norecrelated = $norecHead;
                    $postingJurnalTransaksiD->save();
                }else{
                    $listakun = $listakun . "," . $nocoaexd ;
                    $listakun = $listakun . "," . $nocoaexk ;


                }
            }
            if(!empty($listakun)){
                $listakun = substr($listakun, 1, strlen($listakun) - 1);
                DB::rollBack();
                $transMessage = 'No Akun ini ( ' .$listakun . ' )  tidak ditemukan' ;
                $result = array("status" => 400, "result"  => null);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }


            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;

        }

        if ($transStatus) {
            $transMessage = 'Import Data Berhasil';
            DB::commit();
            $result = array(
                'status' => 200,
                "result" => array(

                    'as' => '@epic',
                )
            );
        } else {
            $transMessage =  'Import Data Gagal';
            DB::rollBack();
            $result = array(
                'status' => 400,
                "result" => array(
                    'e'  => $e->getMessage(). ' '.$e->getLine(),

                    'as' => '@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
