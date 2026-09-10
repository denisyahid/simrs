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

class ArusKasCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDataArusKas(Request $request) {
        // TODO : Akuntansi NERACA
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $tgltgl = $request['tgltgl'];
        $idProfile = (int) $this->kdProfile;
        $namaLaporan = $request['namalaporan'];
        //saldo mutasi
        $sql1 = "select mp.noaccount,mp.namaaccount,mp.namaexternal,  x.debet-x.kredit  as total
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
                where mp.kodeexternal='1'
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true";
        $sql2 = "select mp.noaccount,'---' || mp.namaaccount,mp.namaexternal,  x.debet-x.kredit  as total
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
                where mp.kodeexternal='2'
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true
                ";
        $sql3 = "select mp.noaccount,'------' || mp.namaaccount, mp.namaexternal, x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(coa.noaccount,6) as noaccount,
                sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
                INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
                where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
                 --and coa.reportdisplay='$namaLaporan'
                group by left(coa.noaccount,6)
                )as x  on x.noaccount = left(mp.noaccount,6)
                where mp.kodeexternal='3'
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true";

        $sql4 = "select mp.noaccount,'---------' || mp.namaaccount, mp.namaexternal, x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(coa.noaccount,9) as noaccount,
                sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
                from chartofaccount_m as coa
                INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
                INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
                where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
                 --and coa.reportdisplay='$namaLaporan'
                group by left(coa.noaccount,9)
                )as x  on x.noaccount = left(mp.noaccount,9)
                where mp.kodeexternal ='4'
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true";

        $sql = $sql1 . " union all " . $sql2 . " union all " . $sql3. " union all " . $sql4 ;

        $sqlFinal = "select * from ($sql) as z  ORDER BY z.noaccount";
        $data = DB::select(DB::raw($sqlFinal));

        //saldo Awal
        $sql11 = "select mp.noaccount,mp.namaaccount,x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(mp.noaccount,1) as kdmap,
                sum(pj.hargasatuand) as debet,sum(pj.hargasatuank) as kredit
                from chartofaccount_m as mp
                INNER JOIN postingsaldoawal_t as pj ON pj.objectaccountfk= mp.id
                where mp.kdprofile = $idProfile and pj.ym='$tgltgl'
                group by left(mp.noaccount,1)
                )as x  on x.kdmap = left(mp.noaccount,1)
                where mp.kodeexternal='1'
                and left(mp.noaccount,1) in ($namaLaporan)
                and sk.statusenabled=true and mp.statusenabled=true";
        $sql22 = "select mp.noaccount,'---'  || mp.namaaccount,x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(mp.noaccount,3) as kdmap,
                sum(pj.hargasatuand) as debet,sum(pj.hargasatuank) as kredit
                from chartofaccount_m as mp
                INNER JOIN postingsaldoawal_t as pj ON pj.objectaccountfk= mp.id
                where mp.kdprofile = $idProfile and pj.ym='$tgltgl'
                group by left(mp.noaccount,3)
                )as x  on x.kdmap = left(mp.noaccount,3)
                where mp.kodeexternal='2'
                and left(mp.noaccount,1) in ($namaLaporan)
                and sk.statusenabled=true and mp.statusenabled=true ";
        $sql33 = "select mp.noaccount,'------'  || mp.namaaccount,x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(mp.noaccount,6) as kdmap,
                sum(pj.hargasatuand) as debet,sum(pj.hargasatuank) as kredit
                from chartofaccount_m as mp
                INNER JOIN postingsaldoawal_t as pj ON pj.objectaccountfk= mp.id
                where mp.kdprofile = $idProfile and pj.ym='$tgltgl'
                group by left(mp.noaccount,6)
                )as x  on x.kdmap = left(mp.noaccount,6)
                where mp.kodeexternal='3'
                and left(mp.noaccount,1) in ($namaLaporan)
                and sk.statusenabled=true and mp.statusenabled=true";
        $sql44 = "select mp.noaccount,'--------'  || mp.namaaccount,x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(mp.noaccount,9) as kdmap,
                sum(pj.hargasatuand) as debet,sum(pj.hargasatuank) as kredit
                from chartofaccount_m as mp
                INNER JOIN postingsaldoawal_t as pj ON pj.objectaccountfk= mp.id
                where mp.kdprofile = $idProfile and pj.ym='$tgltgl'
                group by left(mp.noaccount,9)
                )as x  on x.kdmap = left(mp.noaccount,9)
                where mp.kodeexternal is null
                and left(mp.noaccount,1) in ($namaLaporan)
                and sk.statusenabled=true and mp.statusenabled=true";

        $sql2 = $sql11 . " union all " . $sql22 . " union all " . $sql33 . " union all " . $sql44 ;

        $sqlFinal2= "select * from ($sql2) as z  ORDER BY z.noaccount";
        $data2 = DB::select(DB::raw($sqlFinal2));

        $result =[];
        $total=0;
        $total2=0;
        foreach ($data as $item){
            $total=0;
            $total2=0;
            foreach ($data2 as $itm){
                if ($item->noaccount == $itm->noaccount){
                    $total2 = $itm->total;
                    if ((float)$itm->total < 0){
                        $total2 = $total2 * (-1);
                    }
                }
            }
            $total = $item->total;
            if ((float)$item->total < 0){
                $total = $total * (-1);
            }
            $result[] = array(
                'kdmap' => $item->noaccount,
                'nomap' => $item->namaexternal,
                'namamap' => $item->namaaccount,
                'total' => $total2,
                'total2' => $total,
                'total3' => $total2 + $total ,
            );
        }

        return $this->respond($result);
    }
    public function getDataArusKas_COA_SAK(Request $request) {
        // TODO : Akuntansi NERACA
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $tgltgl = $request['tgltgl'];
        $idProfile = (int) $this->kdProfile;
        $namaLaporan = $request['namalaporan'];
        //saldo mutasi
        $sql1 = "select mp.noaccount,mp.namaaccount,mp.namaexternal,  x.debet-x.kredit  as total
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
                where mp.kodeexternal='1'
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true";
        $sql2 = "select mp.noaccount,'---' || mp.namaaccount,mp.namaexternal,  x.debet-x.kredit  as total
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
                where mp.kodeexternal='2'
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true
                ";
        $sql3 = "select mp.noaccount,'------' || mp.namaaccount, mp.namaexternal, x.debet-x.kredit  as total
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
                where mp.kodeexternal='3'
                and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
                and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true";

        // $sql4 = "select mp.noaccount,'---------' || mp.namaaccount, mp.namaexternal, x.debet-x.kredit  as total
        //         from chartofaccount_m as mp
        //         INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
        //         left join
        //         (select left(coa.noaccount,9) as noaccount,
        //         sum(pjd.hargasatuand) as debet,sum(pjd.hargasatuank) as kredit
        //         from chartofaccount_m as coa
        //         INNER JOIN postingjurnald_t as pjd ON pjd.objectaccountfk= coa.id
        //         INNER JOIN postingjurnal_t as pj on pj.norec=pjd.norecrelated
        //         where coa.kdprofile = $idProfile and pj.tglbuktitransaksi between '$tglAwal' and '$tglAkhir'
        //          --and coa.reportdisplay='$namaLaporan'
        //         group by left(coa.noaccount,9)
        //         )as x  on x.noaccount = left(mp.noaccount,9)
        //         where mp.kodeexternal ='4'
        //         and sk.statusenabled=true  --and mp.reportdisplay='$namaLaporan'
        //         and left(mp.noaccount,1) in ($namaLaporan) and mp.statusenabled=true";

        $sql = $sql1 . " union all " . $sql2 . " union all " . $sql3;

        $sqlFinal = "select * from ($sql) as z  ORDER BY z.noaccount";
        $data = DB::select(DB::raw($sqlFinal));

        //saldo Awal
        $sql11 = "select mp.noaccount,mp.namaaccount,x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(mp.noaccount,1) as kdmap,
                sum(pj.hargasatuand) as debet,sum(pj.hargasatuank) as kredit
                from chartofaccount_m as mp
                INNER JOIN postingsaldoawal_t as pj ON pj.objectaccountfk= mp.id
                where mp.kdprofile = $idProfile and pj.ym='$tgltgl'
                group by left(mp.noaccount,1)
                )as x  on x.kdmap = left(mp.noaccount,1)
                where mp.kodeexternal='1'
                and left(mp.noaccount,1) in ($namaLaporan)
                and sk.statusenabled=true and mp.statusenabled=true";
        $sql22 = "select mp.noaccount,'---'  || mp.namaaccount,x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(mp.noaccount,2) as kdmap,
                sum(pj.hargasatuand) as debet,sum(pj.hargasatuank) as kredit
                from chartofaccount_m as mp
                INNER JOIN postingsaldoawal_t as pj ON pj.objectaccountfk= mp.id
                where mp.kdprofile = $idProfile and pj.ym='$tgltgl'
                group by left(mp.noaccount,2)
                )as x  on x.kdmap = left(mp.noaccount,2)
                where mp.kodeexternal='2'
                and left(mp.noaccount,1) in ($namaLaporan)
                and sk.statusenabled=true and mp.statusenabled=true ";
        $sql33 = "select mp.noaccount,'------'  || mp.namaaccount,x.debet-x.kredit  as total
                from chartofaccount_m as mp
                INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
                left join
                (select left(mp.noaccount,3) as kdmap,
                sum(pj.hargasatuand) as debet,sum(pj.hargasatuank) as kredit
                from chartofaccount_m as mp
                INNER JOIN postingsaldoawal_t as pj ON pj.objectaccountfk= mp.id
                where mp.kdprofile = $idProfile and pj.ym='$tgltgl'
                group by left(mp.noaccount,3)
                )as x  on x.kdmap = left(mp.noaccount,3)
                where mp.kodeexternal='3'
                and left(mp.noaccount,1) in ($namaLaporan)
                and sk.statusenabled=true and mp.statusenabled=true";
        // $sql44 = "select mp.noaccount,'--------'  || mp.namaaccount,x.debet-x.kredit  as total
        //         from chartofaccount_m as mp
        //         INNER JOIN suratkeputusan_m as sk on sk.id=mp.suratkeputusanfk
        //         left join
        //         (select left(mp.noaccount,9) as kdmap,
        //         sum(pj.hargasatuand) as debet,sum(pj.hargasatuank) as kredit
        //         from chartofaccount_m as mp
        //         INNER JOIN postingsaldoawal_t as pj ON pj.objectaccountfk= mp.id
        //         where mp.kdprofile = $idProfile and pj.ym='$tgltgl'
        //         group by left(mp.noaccount,9)
        //         )as x  on x.kdmap = left(mp.noaccount,9)
        //         where mp.kodeexternal is null
        //         and left(mp.noaccount,1) in ($namaLaporan)
        //         and sk.statusenabled=true and mp.statusenabled=true";

        $sql2 = $sql11 . " union all " . $sql22 . " union all " . $sql33 ;
        // . " union all " . $sql44 ;

        $sqlFinal2= "select * from ($sql2) as z  ORDER BY z.noaccount";
        $data2 = DB::select(DB::raw($sqlFinal2));

        $result =[];
        $total=0;
        $total2=0;
        foreach ($data as $item){
            $total=0;
            $total2=0;
            foreach ($data2 as $itm){
                if ($item->noaccount == $itm->noaccount){
                    $total2 = $itm->total;
                    if ((float)$itm->total < 0){
                        // $total2 = $total2 * (-1);
                    }
                }
            }
            $total = $item->total;
            if ((float)$item->total < 0){
                // $total = $total * (-1);
            }
            $result[] = array(
                'kdmap' => $item->noaccount,
                'nomap' => $item->namaexternal,
                'namamap' => $item->namaaccount,
                'total' => $total2,
                'total2' => $total,
                'total3' => $total2 + $total ,
            );
        }

        return $this->respond($result);
    }
}
