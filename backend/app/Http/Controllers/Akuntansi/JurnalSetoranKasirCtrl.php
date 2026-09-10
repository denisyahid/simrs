<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\ChartOfAccountMapJurnal;
use App\Models\Transaksi\PostingJurnal;
use App\Models\Transaksi\PostingJurnalTransaksi;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;

class JurnalSetoranKasirCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDetailSetoranKasir(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $filterNoreg = '';
        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $filterNoreg = " and  sp.nostruk = '" . $request['nostruk']  . "'";
        }
        $filter = '';
        if (isset($request['search']) && $request['search'] != '') {
            $filter =" and ( ps.namapasien ilike '%". $request['search']."%' or    ps.nocm ilike '%". $request['search']."%' )";
        }
        $data = DB::select(
            DB::raw("select sc.noclosing,ps.nocm, ps.namapasien,sbm.nosbm,case when sbmc.totaldibayar is null then sbm.totaldibayar else sbmc.totaldibayar end as totaldibayar,sbm.tglsbm,sbmc.objectcarabayarfk ,
            sbmc.norec as norec_smbc,sbm.keteranganlainnya,to_char(sbm.tglsbm, 'YYYY-MM-DD') as tgl,lu.objectpegawaifk,pg.namalengkap as kasir,
            sc.norec as norec_sc,
            string_agg(cast(coa.id as text), ' , ' ) as idagg
            ,string_agg(case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end || ' : ' || coa.namaaccount || ' ' ||
            case when pjtd.hargasatuank = 0 then pjtd.hargasatuand  else  pjtd.hargasatuank  end, ' , ' order by case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end) as jurnal,
            pjt.nojurnal_intern,cab.id as carabayarfk,cab.carabayar
            from strukclosing_t as sc
            INNER JOIN strukbuktipenerimaan_t as sbm on sbm.noclosingfk=sc.norec
            INNER JOIN strukbuktipenerimaancarabayar_t as sbmc on sbm.norec=sbmc.nosbmfk
            left JOIN strukpelayanan_t as sp on sp.norec=sbm.nostrukfk
            left JOIN pasien_m as ps on ps.id=sp.nocmfk
            left JOIN loginuser_s as lu on lu.id=sbm.objectpegawaipenerimafk
            left JOIN pegawai_m as pg on pg.id=lu.objectpegawaifk
            left join postingjurnaltransaksi_t pjt on pjt.norecrelated = sc.norec
            LEFT join postingjurnaltransaksid_t pjtd on pjtd.norecrelated=pjt.norec
            left join chartofaccount_m coa on coa.id=pjtd.objectaccountfk
            left join carabayar_m cab on cab.id=sbmc.objectcarabayarfk
            where sc.kdprofile = $idProfile and sbm.tglsbm BETWEEN '$tglAwal' and '$tglAkhir' and pjt.norec is null
            and sbm.statusenabled <>'f' and (sbmc.totaldibayar is not null or sbmc.totaldibayar > 0)
            and sbm.keteranganlainnya in ('Pembayaran Tagihan','Pembayaran Non Layanan')
            $filter
            group by
             sc.noclosing,ps.nocm, ps.namapasien,sbm.nosbm,sbmc.totaldibayar ,sbm.tglsbm,sbmc.objectcarabayarfk ,
            sbmc.norec,sbm.keteranganlainnya,lu.objectpegawaifk,pg.namalengkap ,
            sc.norec,sbm.totaldibayar,pjt.nojurnal_intern,cab.id,cab.carabayar

        ")
        );
        return $this->respond($data);
    }
    public function getDetailTerimaKasir(Request $request)
    {
        $idProfile = (int) $this->kdProfile;

        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $filterNoreg = '';
        if (isset($request['nosbm']) && $request['nosbm'] != "" && $request['nosbm'] != "undefined") {
            $filterNoreg = " and  sbm.nosbm ilike '%" . $request['nosbm']  . "%'";
        }
        $filter = '';
        if (isset($request['search']) && $request['search'] != '') {
            $filter =" and ( ps.namapasien ilike '%". $request['search']."%' or    ps.nocm ilike '%". $request['search']."%' )";
        }
        $data = DB::select(
            DB::raw("

                select sbm.norec, sbm.tglsbm, sbm.nosbm , sbm.totaldibayar,sbmc.objectcarabayarfk,
                case when pd.noregistrasi is not null then pd.noregistrasi || ' ' || ps.namapasien else sp.nostruk || ' ' ||  sp.namapasien_klien end as namapasien,
                pd.objectkelompokpasienlastfk,kp.kelompokpasien,cb.carabayar,
                string_agg(cast(coa.id as text), ' , ' ) as idagg
                ,string_agg(case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end || ' : ' || coa.namaaccount || ' ' ||
                case when pjtd.hargasatuank = 0 then pjtd.hargasatuand  else  pjtd.hargasatuank  end, ' , ' order by case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end) as jurnal

                from strukbuktipenerimaan_t sbm
                INNER JOIN strukpelayanan_t sp on sp.norec=sbm.nostrukfk
                INNER JOIN strukbuktipenerimaancarabayar_t sbmc on sbmc.nosbmfk=sbm.norec
                LEFT JOIN pasiendaftar_t pd on pd.norec=sp.noregistrasifk
                LEFT JOIN kelompokpasien_m kp on kp.id=pd.objectkelompokpasienlastfk
                left JOIN pasien_m ps on ps.id=pd.nocmfk
                left JOIN ruangan_m ru on ru.id=sbm.objectruanganfk
                INNER JOIN carabayar_m cb on cb.id=sbmc.objectcarabayarfk
                left join postingjurnaltransaksi_t pjt on pjt.norecrelated = sbm.norec
                LEFT join postingjurnaltransaksid_t pjtd on pjtd.norecrelated=pjt.norec
                left join chartofaccount_m coa on coa.id=pjtd.objectaccountfk
                where sbm.kdprofile = $idProfile and  sbm.tglsbm between '$tglAwal' and '$tglAkhir'
                and sbm.statusenabled=true $filterNoreg
                $filter
                group by sbm.norec, sbm.tglsbm, sbm.nosbm,cb.carabayar,
                sbm.totaldibayar,sbmc.objectcarabayarfk,sp.namapasien_klien,sbm.objectkelompoktransaksifk,ps.namapasien,
                pd.noregistrasi,sp.nostruk,pd.objectkelompokpasienlastfk,kp.kelompokpasien
                order by sbm.nosbm


        ")
        );
        return $this->respond($data);
    }
    public function getDetailMapCoaByCaraBayar(Request $request)
    {
        $idProfile = (int) $this->kdProfile;

        $carabayarid = $request['carabayarid'];

        $data = DB::select(
            DB::raw("

            select  coa.noaccount as kddebet,coa.namaaccount as nmdebet,coa2.noaccount as kdkredit,coa2.namaaccount as nmkredit,
            dp.namadepartemen,ru.namaruangan,pr.namaproduk,rk.namarekanan,kp.kelompokpasien,
            cb.carabayar,bk.nama as bank,jpj.jenistransaksi,djp.detailjenisproduk,jp.jenisproduk,
            map.*
            from chartofaccountmapjurnal_t map
            INNER JOIN chartofaccount_m coa on coa.id=map.objectcoadebetfk
            INNER JOIN chartofaccount_m coa2 on coa2.id=map.objectcoakreditfk
            INNER JOIN jenispelayananjurnal_m jpj on jpj.id=map.objectjenistrxfk
            left JOIN departemen_m dp on dp.id=map.objectdepartemenfk
            left JOIN ruangan_m ru on ru.id=map.objectruanganfk
            left JOIN produk_m pr on pr.id=map.objectprodukfk
            left JOIN detailjenisproduk_m djp on djp.id=map.objectdetailjenisprodukfk
            left JOIN jenisproduk_m jp on jp.id=map.objectjenisprodukfk
            left JOIN rekanan_m rk on rk.id=map.objectrekananfk
            left JOIN kelompokpasien_m kp on kp.id=map.objectkelompokpasienfk
            left join carabayar_m cb on cb.id=map.objectcarabayarfk
            left join bank_m bk on bk.id=map.objectbankfk
            where map.objectcarabayarfk=$carabayarid
            and map.kdprofile=$idProfile

        ")
        );
        return $this->respond($data);
    }
    public function PostingJurnal_PerDetailTransaksi_Kwitansi(Request $request) {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();

        try {
            PostingJurnalTransaksi::where('norecrelated', $request['kwitansi']['norec'])
            ->where('deskripsiproduktransaksi', 'penerimaan_kas')
            ->where('statusenabled', true)
            ->delete();

            $objectcarabayarfk = $request['kwitansi']['objectcarabayarfk'];
            $dataMapJurnal = DB::select(
                DB::raw("

                    select  coa.noaccount as kddebet,coa.namaaccount as nmdebet,coa2.noaccount as kdkredit,coa2.namaaccount as nmkredit,
                    dp.namadepartemen,ru.namaruangan,pr.namaproduk,rk.namarekanan,kp.kelompokpasien,cb.carabayar,bk.nama as bank,jpj.jenistransaksi,map.*
                    from chartofaccountmapjurnal_t map
                    INNER JOIN chartofaccount_m coa on coa.id=map.objectcoadebetfk
                    INNER JOIN chartofaccount_m coa2 on coa2.id=map.objectcoakreditfk
                    INNER JOIN jenispelayananjurnal_m jpj on jpj.id=map.objectjenistrxfk
                    left JOIN departemen_m dp on dp.id=map.objectdepartemenfk
                    left JOIN ruangan_m ru on ru.id=map.objectruanganfk
                    left JOIN produk_m pr on pr.id=map.objectprodukfk
                    left JOIN rekanan_m rk on rk.id=map.objectrekananfk
                    left JOIN kelompokpasien_m kp on kp.id=map.objectkelompokpasienfk
                    left join carabayar_m cb on cb.id=map.objectcarabayarfk
                    left join bank_m bk on bk.id=map.objectbankfk
                    where map.objectcarabayarfk=$objectcarabayarfk

                ")
            );
            $dataHasilJurnal = [];
            foreach ($dataMapJurnal as $item) {

                $debetId = 0;
                $kreditId = 0;
                // if($item->objectruanganfk == null && $item->objectkelompokpasienfk == null){
                //     $debetId = $item->objectcoadebetfk;
                //     $kreditId = $item->objectcoakreditfk;
                // }
                // if($item->objectruanganfk != null && $item->objectkelompokpasienfk == null){
                //     if($item->objectruanganfk == $request['kwitansi']['objectruanganfk'] ){
                //         $debetId = $item->objectcoadebetfk;
                //         $kreditId = $item->objectcoakreditfk;
                //     }
                // }
                if($item->objectruanganfk == null && $item->objectkelompokpasienfk != null){
                    if($item->objectkelompokpasienfk == $request['kwitansi']['objectkelompokpasienlastfk'] ){
                        $debetId = $item->objectcoadebetfk;
                        $kreditId = $item->objectcoakreditfk;
                    }
                }
                // if($item->objectruanganfk != null && $item->objectkelompokpasienfk != null){
                //     if($item->objectruanganfk == $request['kwitansi']['objectruanganfk'] && $item->objectkelompokpasienfk == $request['kwitansi']['objectkelompokpasienlastfk']){
                //         $debetId = $item->objectcoadebetfk;
                //         $kreditId = $item->objectcoakreditfk;
                //     }
                // }
                if($debetId != 0 ){

                    $noSBM =$request['kwitansi']['nosbm'];
                    $nama = $request['kwitansi']['namapasien'];
                    $totalRp = $request['kwitansi']['totaldibayar'];

                    $noBuktiTransaksi = $request['kwitansi']['nosbm'];
                    $noPosting = '-';

                    $nojurnal = 0;

                    $newPJT = new PostingJurnalTransaksi;
                    $norecHead = $newPJT->generateNewId();
                    $newPJT->norec = $norecHead;
                    $noJurnalIntern = Carbon::parse($request['kwitansi']['tglsbm'])->format('ym') . 'PN' . Carbon::parse($request['kwitansi']['tglsbm'])->format('d') . '00003';
                    $cekSudahPosting =  PostingJurnal::where('norecrelated',$noJurnalIntern)->where('kdprofile', $kdProfile)->get();
                    $tgl = Carbon::parse($request['kwitansi']['tglsbm'])->format('Y-m-d');


                    if (count($cekSudahPosting) == 0) {
                        $newPJT->kdprofile = $kdProfile;
                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = $nojurnal;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $request['kwitansi']['tglsbm'];// $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Pembayaran tagihan a.n ' . $nama . ' (' . $noSBM . ')';
                        $newPJT->deskripsiproduktransaksi = 'penerimaan_kas';
                        $newPJT->keteranganlainnya = 'Penerimaan Kas ' . $tgl;
                        $newPJT->statusenabled = 1;
                        $newPJT->norecrelated = $request['kwitansi']['norec'];
                        $newPJT->save();

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = $nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = $nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                    }else{
                        $transMessage = "Sudah Posting";
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "result"  => null

                        );
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }
                }
            }
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }


        $transMessage = "Posting";

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $dataHasilJurnal,
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
                    "e" => $e->getMessage(). ' '.$e->getLine()
                )
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }
}
