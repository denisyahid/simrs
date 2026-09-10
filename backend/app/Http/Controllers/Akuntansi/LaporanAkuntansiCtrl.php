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

class LaporanAkuntansiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDataArusKasRevMar2023(Request $request)
    {
        // TODO : Akuntansi NERACA
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $tgltgl = $request['tgltgl'];
        $idProfile = (int) $this->kdProfile;
        $namaLaporan = $request['namalaporan'];
        //saldo mutasi
        $sql1 = "select mp.noaccount,mp.namaaccount,mp.namaexternal,  0 as debet,0 as kredit ,0 as type
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(coa.noaccount,1) as noaccount,
                sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
                INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
                where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
                --and coa.reportdisplay='$namaLaporan'
                group by left(coa.noaccount,1)
                )as x  on x.noaccount = left(mp.noaccount,1)
                --where mp.kodeexternal='1'
                where mp.objectstrukturaccountfk=4
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true";
        $sql2 = "select mp.noaccount,'---' || mp.namaaccount,mp.namaexternal,  x.debet,x.kredit ,1 as type
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(coa.noaccount,2) as noaccount,
                sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
                INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
                where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
                --and coa.reportdisplay='$namaLaporan'
                group by left(coa.noaccount,2)
                )as x  on x.noaccount = left(mp.noaccount,2)
                --where mp.kodeexternal='2'
                where mp.objectstrukturaccountfk=5
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true
                ";

        $sql3 = "select mp.noaccount,'-----' || mp.namaaccount,mp.namaexternal,  x.debet,x.kredit ,2 as type
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(coa.noaccount,4) as noaccount,
                sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
                INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
                where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
                --and coa.reportdisplay='$namaLaporan'
                group by left(coa.noaccount,4)
                )as x  on x.noaccount = left(mp.noaccount,4)
                --where mp.kodeexternal='3'
                where mp.objectstrukturaccountfk=6
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true
                ";

        $sql = $sql1 . " union all " . $sql2  . " union all " . $sql3;

        $sqlFinal = "select * from ($sql) as z  ORDER BY z.noaccount";
        $data = DB::select(DB::raw($sqlFinal));

        foreach ($data as $item) {
            $result[] = array(
                'kdmap' => $item->noaccount,
                'nomap' => $item->namaexternal,
                'namamap' => $item->namaaccount,
                'debet' => (float)$item->debet,
                'kredit' => (float)$item->kredit,
                'type' => $item->type
            );
        }
        if (isset($request['cetak']) && $request['cetak'] == 'true') {
            $profile = Profile::where('id', $this->kdProfile)->first();
            $pageWidth = 950;
            return view('report.keuangan.lap-akuntansi',  compact('result', 'pageWidth', 'profile', 'request'));
        }
        return $this->respond($result);
    }
    public function getJurnalPendapatan(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        // Verifikasi Tagihan Tgl. 2023-05
        $keterangan = 'Verifikasi Tagihan';

        $jenis = '';
        if ($request['jenis'] != "") {
            $jenis = "and pj.jenis = '" . $request['jenis'] . "'";
        }

        $data = DB::select(
            DB::raw("
            select y.* from (
                select z.accountid, z.noaccount,z.namaaccount  as namaaccount,
                case when z.hargasatuand - z.hargasatuank > 0 then z.hargasatuand - z.hargasatuank else 0 end as hargasatuand,
                case when z.hargasatuand - z.hargasatuank < 0 then z.hargasatuank - z.hargasatuand else 0 end as hargasatuank,
                z.funct,z.namafunct
                from
                (select  x.accountid, x.noaccount,
                        x.namaaccount,
                        sum(x.hargasatuand) as hargasatuand,sum(x.hargasatuank) as hargasatuank
                        ,x.funct,x.namafunct
                        from
                    (select pjd.objectaccountfk as accountid, coa.noaccount,
                        coa.namaaccount  as namaaccount,
                        pjd.hargasatuand as hargasatuand,pjd.hargasatuank as hargasatuank,
                        '' as funct,'' as namafunct
                        from postingjurnaltransaksi_t as pj
                        INNER JOIN postingjurnaltransaksid_t as pjd on pj.norec=pjd.norecrelated
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        where pj.kdprofile = $idProfile
                        --and pj.jenis= 'RJ'
                        $jenis
                        and pj.keteranganlainnya ilike '%$keterangan%'
                        and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                        --group by pjd.objectaccountfk, coa.noaccount,coa.namaaccount

                    union all
                         --tindakan--
                        select pjd.objectaccountfk as accountid, coa.noaccount,
                        coa.namaaccount  as namaaccount,
                        pjd.hargasatuand as hargasatuand,pjd.hargasatuank as hargasatuank,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct
                        from postingjurnaltransaksi_t as pj
                                        INNER JOIN pelayananpasien_t as pp on pp.strukfk = pj.norecrelated
                                        inner join postingjurnaltransaksi_t as pjpp on pjpp.norecrelated=pp.norec
                        INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated = pjpp.norec
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
                        INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
                        where pj.kdprofile = $idProfile
                        $jenis
                        --and pj.jenis= 'RJ'
                        and pj.keteranganlainnya ilike '%$keterangan%'
                        and pjpp.deskripsiproduktransaksi='pelayananpasien_t'
                        and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                        and pp.strukresepfk is NULL
                        --group by pjd.objectaccountfk, coa.noaccount,coa.namaaccount,
                        --case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end,
                        --case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end
                    union all
                        --obat--
                        select pjd.objectaccountfk as accountid, coa.noaccount,
                        coa.namaaccount  as namaaccount,
                        pjd.hargasatuand as hargasatuand,pjd.hargasatuank as hargasatuank,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct
                        from postingjurnaltransaksi_t as pj
                                        INNER JOIN pelayananpasien_t as pp on pp.strukfk = pj.norecrelated
                                        inner join postingjurnaltransaksi_t as pjpp on pjpp.norecrelated=pp.norec
                        INNER JOIN postingjurnaltransaksid_t as pjd on pjpp.norec=pjd.norecrelated
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        INNER JOIN strukresep_t as sr on sr.norec=pp.strukresepfk
                        INNER JOIN ruangan_m as ru on ru.id=sr.ruanganfk
                        where pj.kdprofile = $idProfile
                        $jenis
                        --and pj.jenis= 'RJ'
                        and pj.keteranganlainnya ilike '%$keterangan%'
                        and pjpp.deskripsiproduktransaksi='pelayananpasien_t'
                        and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                        --group by pjd.objectaccountfk, coa.noaccount,coa.namaaccount,
                        --case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end,
                        --case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end
                        union all
                        --obat bebas
                        select pjd.objectaccountfk as accountid, coa.noaccount,
                        coa.namaaccount as namaaccount,
                        pjd.hargasatuand as hargasatuand,pjd.hargasatuank as hargasatuank,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct
                        from postingjurnaltransaksi_t as pj
                        INNER JOIN strukpelayanandetail_t as spd on spd.norec = pj.norecrelated
                        INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        INNER JOIN strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                        INNER JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
                        where pj.kdprofile = $idProfile
                        $jenis
                        and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                        and substring(sp.nostruk,1,3)='OB/'
                        and pj.deskripsiproduktransaksi='pelayananpasien_tob'
                        --group by pjd.objectaccountfk, coa.noaccount,coa.namaaccount,
                        --case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end,
                        --case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end
                        union all
                        --nonlayanan
                        select pjd.objectaccountfk as accountid, coa.noaccount,
                        coa.namaaccount as namaaccount,
                        pjd.hargasatuand as hargasatuand,pjd.hargasatuank as hargasatuank,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct
                        from postingjurnaltransaksi_t as pj
                        INNER JOIN strukpelayanandetail_t as spd on spd.norec = pj.norecrelated
                        INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        INNER JOIN strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                        INNER JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
                        where pj.kdprofile = $idProfile
                        $jenis
                        and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                        and substring(sp.nostruk,1,2)='NL'
                        AND sp.statusenabled = true and spd.objectprodukfk not in (402611)
                        and pj.deskripsiproduktransaksi='pelayananpasien_tob'
                        --group by pjd.objectaccountfk, coa.noaccount,coa.namaaccount,
                        --case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end,
                        --case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end
                    ) as x
                    group by x.accountid, x.noaccount,x.namaaccount,x.funct,x.namafunct
                ) as z ) as y order by y.hargasatuank,y.namaaccount

        ")

        );
        return $this->respond($data);
    }
    public function getJurnalPendapatanBelumverif(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        // Verifikasi Tagihan Tgl. 2023-05
        $keterangan = 'Verifikasi Tagihan';

        $jenis = '';
        if ($request['jenis'] != "") {
            $jenis = "and pj.jenis = '" . $request['jenis'] . "'";
        }

        $data = DB::select(
            DB::raw("
            select y.* from (
                select z.accountid, z.noaccount,z.namaaccount  as namaaccount,
                case when z.hargasatuand - z.hargasatuank > 0 then z.hargasatuand - z.hargasatuank else 0 end as hargasatuand,
                case when z.hargasatuand - z.hargasatuank < 0 then z.hargasatuank - z.hargasatuand else 0 end as hargasatuank,
                z.funct,z.namafunct
                from
                (select  x.accountid, x.noaccount,
                        x.namaaccount,
                        sum(x.hargasatuand) as hargasatuand,sum(x.hargasatuank) as hargasatuank
                        ,x.funct,x.namafunct
                        from
                            (select pjd.objectaccountfk as accountid, coa.noaccount,
                            coa.namaaccount  as namaaccount,
                            sum(pjd.hargasatuand) as hargasatuand,sum(pjd.hargasatuank) as hargasatuank,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct
                            from postingjurnaltransaksi_t as pj
                            INNER JOIN pelayananpasien_t as pp on pp.norec = pj.norecrelated
                            INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                            INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                            INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
                            INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
                            where pj.kdprofile = $idProfile
                            $jenis
                            and pp.strukfk is null
                            and pj.deskripsiproduktransaksi = 'pelayananpasien_t'
                            and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                            and pp.strukresepfk is NULL
                            group by pjd.objectaccountfk, coa.noaccount,coa.namaaccount,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end
                            union all
                            select pjd.objectaccountfk as accountid, coa.noaccount,
                            coa.namaaccount  as namaaccount,
                            sum(pjd.hargasatuand) as hargasatuand,sum(pjd.hargasatuank) as hargasatuank,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct
                            from postingjurnaltransaksi_t as pj
                            INNER JOIN pelayananpasien_t as pp on pp.norec = pj.norecrelated
                            INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                            INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                            INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
                            INNER JOIN strukresep_t as sr on sr.norec=pp.strukresepfk
                            INNER JOIN ruangan_m as ru on ru.id=sr.ruanganfk
                            where pj.kdprofile = $idProfile
                            $jenis
                            and pp.strukfk is null
                            and pj.deskripsiproduktransaksi = 'pelayananpasien_t'
                            and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                            group by pjd.objectaccountfk, coa.noaccount,coa.namaaccount,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end
                        ) as x
                        group by x.accountid, x.noaccount,
                        x.namaaccount,x.funct,x.namafunct
                    ) as z
                ) as y order by y.hargasatuank,y.namaaccount

        ")
            // ,
            //     array(
            //         'tglAwal' => $request['tglAwal'],
            //         'tglAkhir' => $request['tglAkhir'],
            //         'jenis' => $request['jenis'],
            //         // 'keterangan' =>  $keterangan,
            //     )
        );
        return $this->respond($data);
    }
    public function getJurnalPelunasanPiutang(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        // Verifikasi Tagihan Tgl. 2023-05
        $keterangan = 'Verifikasi Tagihan';

        // $jenis = '';
        // if ($request['jenis'] != "") {
        //     $jenis = "and pj.jenis = '" . $request['jenis'] . "'";

        // } deskripsiproduktransaksi = 'bebanpelayananpasien_t'

        $data = DB::select(
            DB::raw("
                    select pjd.objectaccountfk as accountid,pj.tglbuktitransaksi,
                    '[ ' || coa.noaccount || ' ]' || ' ' || coa.namaaccount  as namaaccount,
                    pjd.hargasatuand as hargasatuand,pjd.hargasatuank as hargasatuank,
                    rk.namarekanan as keterangan,pj.namaproduktransaksi
                    from postingjurnaltransaksi_t as pj
                    INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated= pj.norec
                    INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                    INNER JOIN strukbuktipenerimaancarabayar_t as sbmc on sbmc.norec=pj.norecrelated
                    INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk
                    INNER JOIN strukpelayananpenjamin_t as spp on spp.nostrukfk=sbm.nostrukfk
                    INNER JOIN postinghutangpiutang_t as php on php.nostrukfk=spp.norec
                    INNER JOIN strukpelayanan_t as sp on sp.norec = spp.nostrukfk
                    left JOIN rekanan_m as rk on rk.id = spp.kdrekananpenjamin
                    where pj.deskripsiproduktransaksi = 'Penerimaan_kas_piutang'
                    and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                    ORDER BY pj.namaproduktransaksi,pjd.hargasatuank

        ")
            // ,
            //     array(
            //         'tglAwal' => $request['tglAwal'],
            //         'tglAkhir' => $request['tglAkhir'],
            //         'jenis' => $request['jenis'],

            //         // 'keterangan' =>  $keterangan,
            //     )
        );
        return $this->respond($data);
    }

    public function getdetailJurnalPelunasanPiutang(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        // Verifikasi Tagihan Tgl. 2023-05
        $keterangan = 'Verifikasi Tagihan';

        // $jenis = '';
        // if ($request['jenis'] != "") {
        //     $jenis = "and pj.jenis = '" . $request['jenis'] . "'";

        // } deskripsiproduktransaksi = 'bebanpelayananpasien_t'

        $data = DB::select(
            DB::raw("
                    select DISTINCT sbm.nosbm, substring(spp.noverifikasi,3,11) as notagihan ,pd.noregistrasi, ps.namapasien,pd.tglpulang, spp.totalppenjamin,
                    rk.namarekanan as keterangan,sbm.objectkelompoktransaksifk,pjd.hargasatuank
                    from postingjurnaltransaksi_t as pj
                    INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated= pj.norec
                    INNER JOIN strukbuktipenerimaancarabayar_t as sbmc on sbmc.norec=pj.norecrelated
                    INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk
                    INNER JOIN strukpelayananpenjamin_t as spp on spp.nostrukfk=sbm.nostrukfk
                    INNER JOIN postinghutangpiutang_t as php on php.nostrukfk=spp.norec
                    INNER JOIN strukpelayanan_t as sp on sp.norec = spp.nostrukfk
                    left JOIN rekanan_m as rk on rk.id = spp.kdrekananpenjamin
                    INNER JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                    INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                    where pj.deskripsiproduktransaksi = 'Penerimaan_kas_piutang'
                    and sbm.objectkelompoktransaksifk=76
                    and pj.tglbuktitransaksi BETWEEN '$tglAwal' and '$tglAkhir'
                    and pjd.hargasatuand=0
                   -- ORDER BY pj.namaproduktransaksi,pjd.hargasatuank

        ")
            // ,
            //     array(
            //         'tglAwal' => $request['tglAwal'],
            //         'tglAkhir' => $request['tglAkhir'],
            //         'jenis' => $request['jenis'],

            //         // 'keterangan' =>  $keterangan,
            //     )
        );
        return $this->respond($data);
    }
    public function getDetailJurnalPendapatanbelumverif(Request $request)
    {
        $idProfile = (int) $this->kdProfile;

        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        // Verifikasi Tagihan Tgl. 2023-05
        $keterangan = 'Verifikasi Tagihan';
        $noacc = $request['noacc'];


        $idruangan =  trim( $request['idru']);
        $ruangan =  trim($request['ru']);
        $jenispj = '';
        $jenis = '';
        if ($request['jenis'] != "") {
            $jenis = "and ru.jenis = '" . $request['jenis'] . "'";
            $jenispj = "and x.jenis = '" . $request['jenis'] . "'";

        }

        if ($request['nilaidebet'] != 0) {

            $data = DB::select(
                DB::raw("
                SELECT x.noaccount,x.tglbuktitransaksi, x.noregistrasi,x.namapasien,x.namarekanan, x.jenis,
                x.funct,x.namafunct,sum(x.total) as hargasatuand  FROM (
                    --tindakan--
                    select coa.noaccount,pj.tglbuktitransaksi,pd.noregistrasi, ps.namapasien,rk.namarekanan, pj.jenis,
                    case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                    case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct,ru.namaruangan,pjd.hargasatuand as total
                    from postingjurnaltransaksi_t as pj
                    INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                    INNER JOIN pelayananpasien_t as pp on pp.norec = pj.norecrelated
                    INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
                    INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                    left JOIN rekanan_m as rk on rk.id=pd.objectrekananfk
                    INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
                    where pj.kdprofile = $idProfile
                    and coa.noaccount ='$noacc'
                    $jenis
                    and pjd.hargasatuank=0
                    and pj.deskripsiproduktransaksi='pelayananpasien_t'
                    and pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                    and pp.strukresepfk is NULL
                    and pp.strukfk is NULL

                    union all
                    --obat--
                    select coa.noaccount,pj.tglbuktitransaksi,pd.noregistrasi, ps.namapasien,rk.namarekanan, pj.jenis,
                    case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                    case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct,ru.namaruangan,pjd.hargasatuand as total
                    from postingjurnaltransaksi_t as pj
                    INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                    INNER JOIN pelayananpasien_t as pp on pp.norec = pj.norecrelated
                    INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
                    INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                    left JOIN rekanan_m as rk on rk.id=pd.objectrekananfk
                    INNER JOIN strukresep_t as sr on sr.norec=pp.strukresepfk
                    INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
                    where pj.kdprofile = $idProfile
                    and coa.noaccount ='$noacc'
                    $jenis
                    and pjd.hargasatuank=0
                    and pp.strukfk is NULL
                    and pj.deskripsiproduktransaksi='pelayananpasien_t'
                    and pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                 ) as x

                 group by  x.noaccount,x.tglbuktitransaksi, x.noregistrasi,x.namapasien,x.namarekanan, x.jenis,
                 x.funct,x.namafunct
                ")
            );

        }else{
            $data = DB::select(
                DB::raw("
                        SELECT x.noaccount,x.tglbuktitransaksi, x.noregistrasi,x.namapasien,x.namarekanan, x.jenis,
                        x.funct,x.namafunct,sum(x.total) as hargasatuand  FROM (
                            --tindakan--
                            select coa.noaccount,pj.tglbuktitransaksi,pd.noregistrasi, ps.namapasien,rk.namarekanan, pj.jenis,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct,ru.namaruangan,pjd.hargasatuank as total
                            from postingjurnaltransaksi_ts as pj
                            INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                            INNER JOIN pelayananpasien_t as pp on pp.norec = pj.norecrelated
                            INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                            INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
                            INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                            INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                            left JOIN rekanan_m as rk on rk.id=pd.objectrekananfk
                            INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
                            where pj.kdprofile = $idProfile
                            and coa.noaccount ='$noacc'
                            and pjd.hargasatuand=0
                            and pp.strukfk is NULL
                            and pj.deskripsiproduktransaksi='pelayananpasien_t'
                            and pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                            and pp.strukresepfk is NULL

                            union all
                            --obat--
                            select coa.noaccount,pj.tglbuktitransaksi,pd.noregistrasi, ps.namapasien,rk.namarekanan, pj.jenis,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                            case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct,ru.namaruangan,pjd.hargasatuank as total
                            from postingjurnaltransaksi_t as pj
                            INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                            INNER JOIN pelayananpasien_t as pp on pp.norec = pj.norecrelated
                            INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                            INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
                            INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                            INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                            left JOIN rekanan_m as rk on rk.id=pd.objectrekananfk
                            INNER JOIN strukresep_t as sr on sr.norec=pp.strukresepfk
                            INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
                            where pj.kdprofile = $idProfile
                            and coa.noaccount ='$noacc'
                            and pjd.hargasatuand=0
                            and pp.strukfk is NULL
                            and pj.deskripsiproduktransaksi='pelayananpasien_t'
                            and pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                         ) as x
                         where   x.funct ilike '%$idruangan%'
                         and x.namafunct ilike  '%$ruangan%'
                         $jenispj
                         group by  x.noaccount,x.tglbuktitransaksi, x.noregistrasi,x.namapasien,x.namarekanan, x.jenis,
                         x.funct,x.namafunct

                ")
            );

        }

        return $this->respond($data);
    }
    public function getDetailJurnalPendapatan(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        // Verifikasi Tagihan Tgl. 2023-05
        $keterangan = 'Verifikasi Tagihan';
        $noacc = $request['noacc'];

        $idruangan = trim($request['idru']);
        if ($request['idru'] != "null") {
            $idruangan = " and x.funct ILIKE '%" . trim($request['idru']) . "%'";
        } else if ($request['idru'] != '') {
            $idruangan = " and x.funct IS NULL ";
        } else if ($request['idru'] == "-"){
            $idruangan = "and x.funct = '-'";
        } else {
            $idruangan = " and x.funct IS NULL ";
        }

        $ruangan =  trim($request['ru']);
        if ($request['ru'] != "null") {
            $ruangan = " and x.namafunct ILIKE '%" . trim($request['ru']) . "%'";
        } else if ($request['ru'] != '') {
            $ruangan = " and x.namafunct IS NULL ";
        } else if ($request['ru'] == "-"){
            $ruangan = "and x.namafunct = '-'";
        } else {
            $ruangan = " and x.namafunct IS NULL ";
        }


        $jenispj = '';
        $jenis = '';
        if ($request['jenis'] != "") {
            $jenis = "and ru.jenis = '" . $request['jenis'] . "'";
            $jenispj = "and x.jenis = '" . $request['jenis'] . "'";

        }
        // $request['nilaidebet'];
        if ($request['nilaidebet'] != 0) {
            // return '1';
            $data = DB::select(
                DB::raw("
                    select * from (

                        select
                        pd.noregistrasi,
                        pj.tglbuktitransaksi,
                        ps.namapasien,
                        rk.namarekanan,
                        pa.nosep,
                        '' AS namadokter,
                        '' AS namatindakan,
                        ru.jenis,
                        pjd.hargasatuand,
                        '' AS funct,
                        '' AS namafunct
                        from postingjurnaltransaksi_t as pj
                        INNER JOIN postingjurnaltransaksid_t as pjd on pj.norec=pjd.norecrelated
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        INNER join strukpelayanan_t as sp on sp.norec=pj.norecrelated
                        inner join pasiendaftar_t as pd on pd.norec=sp.noregistrasifk
                        INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                        left JOIN rekanan_m as rk on rk.id=pd.objectrekananfk
                        INNER JOIN ruangan_m as ru on ru.id=pd.objectruanganlastfk
                        LEFT JOIN pemakaianasuransi_t as pa on pa.noregistrasifk = pd.norec
                        where pj.kdprofile =  $idProfile
                        $jenis
                        --and pj.jenis= 'RJ'
                        and pjd.hargasatuank=0
                        and coa.noaccount='$noacc'
                        --and pj.keteranganlainnya ilike '%Verifikasi Tagihan%'
                        and pj.deskripsiproduktransaksi = 'verifikasi_tarek'
                        and sp.totalharusdibayar>0
                        and pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                        --and pj.namaproduktransaksi ilike '%2312230051%'

                        union all

                        select
                        pd.noregistrasi,
                        pj.tglbuktitransaksi,
                        ps.namapasien,
                        rk.namarekanan,
                        pa.nosep,
                        '' AS namadokter,
                        '' AS namatindakan,
                        ru.jenis,
                        pjd.hargasatuand,
                        '' AS funct,
                        '' AS namafunct
                        from postingjurnaltransaksi_t as pj
                        INNER JOIN postingjurnaltransaksid_t as pjd on pj.norec=pjd.norecrelated
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        inner JOIN strukpelayananpenjamin_t AS spp ON spp.norec = pj.norecrelated
                        INNER join strukpelayanan_t as sp on sp.norec=spp.nostrukfk
                        inner join pasiendaftar_t as pd on pd.norec=sp.noregistrasifk
                        INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                        left JOIN rekanan_m as rk on rk.id=spp.kdrekananpenjamin
                        INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
                        LEFT JOIN pemakaianasuransi_t as pa on pa.noregistrasifk = pd.norec
                        where pj.kdprofile =  $idProfile
                        $jenis
                        --and pj.jenis= 'RJ'
                        and pjd.hargasatuank=0
                        and coa.noaccount='$noacc'
                        --and pj.keteranganlainnya ilike '%Verifikasi Tagihan%'
                        and pj.deskripsiproduktransaksi = 'verifikasi_tarek'
                        and pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                        --and pj.namaproduktransaksi ilike '%2312230051%'

                        union all

                        select
                        sp.nostruk AS noregistrasi,
                        pj.tglbuktitransaksi,
                        sp.namapasien_klien AS namapasien,
                        'umum/tunai' AS namarekanan,
                        ru.jenis,
                        '' as nosep,
                        '' AS namadokter,
                        '' AS namatindakan,
                        pjd.hargasatuand,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.noaccount else '' end as funct,
                        case when pjd.objectaccountfk in (321,319,349,311) then ru.namaruangan else '' end as namafunct
                        from postingjurnaltransaksi_t as pj
                        INNER JOIN strukpelayanandetail_t as spd on spd.norec = pj.norecrelated
                        INNER JOIN postingjurnaltransaksid_t as pjd on pjd.norecrelated=pj.norec
                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
                        INNER JOIN strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                        left JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
                        where pj.kdprofile = $idProfile
                        and coa.noaccount='$noacc'
                        $jenis
                        --and pj.jenis= 'RJ'
                        and pjd.hargasatuank=0
                        and pj.deskripsiproduktransaksi='pelayananpasien_tob'
                        and pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                        --and pj.namaproduktransaksi ilike '%2312230051%'
                    ) as x
                ")
            );

        }else{
            // return '2';
            $data = DB::select(
                DB::raw("
                SELECT
                x.noaccount,
                x.tglbuktitransaksi,
                x.noregistrasi,
                x.namapasien,
                x.namarekanan,
                x.nosep,
                x.namadokter,
                x.namatindakan,
                x.jenis,
                x.funct,
                x.namafunct,
                SUM ( x.total ) AS hargasatuand
            FROM
                (
                SELECT
                    coa.noaccount,
                    pj.tglbuktitransaksi,
                    pd.noregistrasi,
                    ps.namapasien,
                    pj.jenis,
                    rm.namarekanan,
                    pa.nosep,
                    pg.namalengkap AS namadokter,
                    pro.namaproduk AS namatindakan,
                    '' AS funct,
			        '' AS namafunct,
                    pjd.hargasatuank AS total
                FROM
                    postingjurnaltransaksi_t AS pj
                    INNER JOIN postingjurnaltransaksid_t AS pjd ON pjd.norecrelated = pj.norec
                    LEFT JOIN chartofaccount_m AS coa ON coa.ID = pjd.objectaccountfk
                    LEFT JOIN pelayananpasien_t AS pp ON pp.norec = pj.norecrelated
                    LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
                    LEFT JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                    LEFT JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
                    LEFT JOIN pelayananpasienpetugas_t AS ppp ON ppp.pelayananpasien = pp.norec
                    LEFT JOIN produk_m AS pro ON pro.ID = pp.produkfk
                    LEFT JOIN pegawai_m AS pg ON pg.ID = ppp.objectpegawaifk
                    LEFT JOIN rekanan_m AS rm ON rm.ID = pd.objectrekananfk
                    LEFT JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
                WHERE
                    pj.kdprofile = $idProfile
                    AND pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                    AND pp.strukfk IS NOT NULL
                    and pjd.hargasatuand=0
                    AND coa.noaccount = '$noacc'
                    AND pj.deskripsiproduktransaksi = 'pelayananpasien_t'
                    --AND pd.noregistrasi = '2312230051'

                    UNION ALL

                    SELECT-- 671310	Medical Service Revenue
                    coa.noaccount,
                    pj.tglbuktitransaksi,
                    pd.noregistrasi,
                    ps.namapasien,
                    pj.jenis,
                    rm.namarekanan,
                    pa.nosep AS nosep,
                    '' AS namadokter,
                    '' AS namatindakan,
                CASE

                        WHEN pjd.objectaccountfk IN ( 321, 319, 349, 311 ) THEN
                        ru.noaccount ELSE''
                    END AS funct,
                CASE

                        WHEN pjd.objectaccountfk IN ( 321, 319, 349, 311 ) THEN
                        ru.namaruangan ELSE''
                    END AS namafunct,
                    pjd.hargasatuank AS total
                FROM
                    postingjurnaltransaksi_t AS pj
                    INNER JOIN postingjurnaltransaksid_t AS pjd ON pjd.norecrelated = pj.norec
                    LEFT JOIN chartofaccount_m AS coa ON coa.ID = pjd.objectaccountfk
                    LEFT JOIN pelayananpasien_t AS pp ON pp.norec = pj.norecrelated
                    LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
                    LEFT JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                    LEFT JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
                    LEFT JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
                    LEFT JOIN rekanan_m AS rm ON rm.ID = pd.objectrekananfk
                    LEFT JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
                WHERE
                    pj.kdprofile = $idProfile
                    AND pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                    AND coa.noaccount = '$noacc' -- 	AND pp.strukfk IS NOT NULL
                    AND pp.strukfk IS NOT NULL
                    and pjd.hargasatuand=0
                    AND pj.deskripsiproduktransaksi = 'pelayananpasien_t'
                    --AND pd.noregistrasi = '2312230051'

                    UNION ALL

                SELECT
                    coa.noaccount,
                    pj.tglbuktitransaksi,
                    sp.nostruk AS noregistrasi,
		            sp.namapasien_klien AS namapasien,
                    pj.jenis,
                    '' AS nosep,
                    '' AS namadokter,
                    '' AS namatindakan,
                    '' AS namarekanan,
                CASE

                        WHEN pjd.objectaccountfk IN ( 321, 319, 349, 311 ) THEN
                        ru.noaccount ELSE''
                    END AS funct,
                CASE

                        WHEN pjd.objectaccountfk IN ( 321, 319, 349, 311 ) THEN
                        ru.namaruangan ELSE''
                    END AS namafunct,
                    pjd.hargasatuank AS total
                FROM
                    postingjurnaltransaksi_t AS pj
                    INNER JOIN strukpelayanandetail_t AS spd ON spd.norec = pj.norecrelated
                    INNER JOIN postingjurnaltransaksid_t AS pjd ON pjd.norecrelated = pj.norec
                    INNER JOIN chartofaccount_m AS coa ON coa.ID = pjd.objectaccountfk
                    INNER JOIN strukpelayanan_t AS sp ON sp.norec = spd.nostrukfk
                    INNER JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
                WHERE
                    pj.kdprofile = $idProfile
                    AND pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                    AND coa.noaccount = '$noacc'
                    and pjd.hargasatuand=0
                    AND SUBSTRING ( sp.nostruk, 1, 3 ) = 'OB/'
                    AND pj.deskripsiproduktransaksi = 'pelayananpasien_tob'
                    --and pj.namaproduktransaksi ilike '%2312230051%'

                    UNION ALL

                SELECT
                    coa.noaccount,
                    pj.tglbuktitransaksi,
                    sp.nostruk AS noregistrasi,
		            sp.namapasien_klien AS namapasien,
                    pj.jenis,
                    '' AS nosep,
                    '' AS namarekanan,
                    '' AS namadokter,
                    '' AS namatindakan,
                CASE

                        WHEN pjd.objectaccountfk IN ( 321, 319, 349, 311 ) THEN
                        ru.noaccount ELSE''
                    END AS funct,
                CASE

                        WHEN pjd.objectaccountfk IN ( 321, 319, 349, 311 ) THEN
                        ru.namaruangan ELSE''
                    END AS namafunct,
                    pjd.hargasatuank AS total
                FROM
                    postingjurnaltransaksi_t AS pj
                    INNER JOIN strukpelayanandetail_t AS spd ON spd.norec = pj.norecrelated
                    INNER JOIN postingjurnaltransaksid_t AS pjd ON pjd.norecrelated = pj.norec
                    INNER JOIN chartofaccount_m AS coa ON coa.ID = pjd.objectaccountfk
                    LEFT JOIN strukpelayanan_t AS sp ON sp.norec = spd.nostrukfk
                    LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
                WHERE
                    pj.kdprofile = $idProfile
                    AND pj.tglbuktitransaksi BETWEEN ' $tglAwal ' and ' $tglAkhir'
                    AND SUBSTRING ( sp.nostruk, 1, 2 ) = 'NL'
                    AND coa.noaccount = '$noacc'
                    AND sp.statusenabled = TRUE
                    and pjd.hargasatuand=0
                    AND spd.objectprodukfk NOT IN ( 402611 )
                    AND pj.deskripsiproduktransaksi = 'pelayananpasien_tob'
                    --and pj.namaproduktransaksi ilike '%2312230051%'
                ) AS x
                WHERE x.noaccount = '$noacc'
                $idruangan
                $ruangan
            GROUP BY
                x.noaccount,
                x.tglbuktitransaksi,
                x.noregistrasi,
                x.namapasien,
                x.namarekanan,
                x.nosep,
                x.namadokter,
                x.namatindakan,
                x.jenis,
                x.funct,
                x.namafunct

                ")
            );

        }

        return $this->respond($data);
    }
    public function getDataArusKasRev_SAK(Request $request)
    {
        // TODO : Akuntansi NERACA
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $tgltgl = $request['tgltgl'];
        $idProfile = (int) $this->kdProfile;
        $namaLaporan = $request['namalaporan'];
        //saldo mutasi
        $sql1 = "select mp.noaccount,mp.namaaccount,mp.namaexternal,  0 as debet,0 as kredit ,0 as type
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(coa.noaccount,1) as noaccount,
                sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
                INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
                where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
                --and coa.reportdisplay='$namaLaporan'
                group by left(coa.noaccount,1)
                )as x  on x.noaccount = left(mp.noaccount,1)
                --where mp.kodeexternal='1'
                where mp.objectstrukturaccountfk=1
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true";
        $sql2 = "select mp.noaccount,'---' || mp.namaaccount,mp.namaexternal,  x.debet,x.kredit ,1 as type
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(coa.noaccount,2) as noaccount,
                sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
                INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
                where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
                --and coa.reportdisplay='$namaLaporan'
                group by left(coa.noaccount,2)
                )as x  on x.noaccount = left(mp.noaccount,2)
                --where mp.kodeexternal='2'
                where mp.objectstrukturaccountfk=2
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true
                ";

        $sql3 = "select mp.noaccount,'------' || mp.namaaccount,mp.namaexternal,  x.debet,x.kredit ,2 as type
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(coa.noaccount,3) as noaccount,
                sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
                INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
                where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
                --and coa.reportdisplay='$namaLaporan'
                group by left(coa.noaccount,3)
                )as x  on x.noaccount = left(mp.noaccount,3)
                --where mp.kodeexternal='3'
                where mp.objectstrukturaccountfk=3
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true
                ";

        $sql = $sql1 . " union all " . $sql2  . " union all " . $sql3;

        $sqlFinal = "select * from ($sql) as z  ORDER BY z.noaccount";
        $data = DB::select(DB::raw($sqlFinal));

        foreach ($data as $item) {
            $result[] = array(
                'kdmap' => $item->noaccount,
                'nomap' => $item->namaexternal,
                'namamap' => $item->namaaccount,
                'debet' => (float)$item->debet,
                'kredit' => (float)$item->kredit,
                'type' => $item->type
            );
        }
        if (isset($request['cetak']) && $request['cetak'] == 'true') {
            $profile = Profile::where('id', $this->kdProfile)->first();
            $pageWidth = 950;
            return view('report.keuangan.lap-akuntansi',  compact('result', 'pageWidth', 'profile', 'request'));
        }
        return $this->respond($result);
    }
}
