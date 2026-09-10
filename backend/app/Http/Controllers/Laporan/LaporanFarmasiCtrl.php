<?php

namespace App\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LaporanFarmasiCtrl extends Controller
{
    use Valet;
    public function getLaporanPenjualanObatDetail(Request $request)
    {
        ini_set('memory_limit', '2048M');
        $kdProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $tahun = (float) $request['tahun'];
        $idDepRajal = (int) $this->settingFix('KdDepartemenRawatJalan');
        $idDepRanap = (int) $this->settingFix('idDepRawatInap');
        $dokid = '';
        $kpid = '';
        $ruid = '';
        $rajal = "";
        $ranap = "";
        $noresep = "";
        $noresepBebas = "";
        $namaProduk = "";
        if (isset($request['dokid']) && $request['dokid'] != "" && $request['dokid'] != "undefined") {
            $dokid = ' and pg.id=' . $request['dokid'];
        }
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $kpid = ' and pd.objectkelompokpasienlastfk=' . $request['kpid'];
        }
        if (isset($request['ruid']) && $request['ruid'] != "" && $request['ruid'] != "undefined") {
            $ruid = ' and ru.id=' . $request['ruid'];
        }
        if (isset($request['isRajal']) && $request['isRajal'] != "" && $request['isRajal'] != "undefined" && $request['isRajal'] == "true" || $request['isRajal'] == true) {
            $rajal = " AND ru2.objectdepartemenfk in ($idDepRajal) ";
        }
        if (isset($request['isRanap']) && $request['isRanap'] != "" && $request['isRanap'] != "undefined" && $request['isRanap'] == "true" || $request['isRanap'] == true) {
            $ranap = " AND ru2.objectdepartemenfk in ($idDepRanap) ";
        }
        if (isset($request['noresep']) && $request['noresep'] != "" && $request['noresep'] != "undefined") {
            $noresep = " AND sr.noresep ILIKE  '%" . $request['noresep'] . "%'";
            $noresepBebas = " AND sp.nostruk ILIKE  '%" . $request['noresep'] . "%'";
        }
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $namaProduk = " AND pro.namaproduk ILIKE  '%" . $request['namaproduk'] . "%'";
        }

        $data = DB::select(DB::raw("

                SELECT TO_CHAR(sr.tglresep, 'DD-MM-YYYY') AS tglresep, ruanganAntrian.namaruangan as ruanganpengorder,
                       CASE WHEN ru1.objectdepartemenfk = $idDepRanap AND kmr.namakamar IS NULL AND tt.nomorbed IS NULL THEN
                       ru1.namaruangan || '[]' WHEN ru1.objectdepartemenfk = $idDepRanap AND kmr.namakamar IS NOT NULL AND tt.nomorbed IS NOT NULL THEN
                       ru1.namaruangan || '[' || CASE WHEN kmr.namakamar IS NULL THEN '' ELSE kmr.namakamar END || ' ' ||
                       CASE WHEN tt.nomorbed IS NULL THEN null ELSE tt.nomorbed END || ']'
                       ELSE dep1.namadepartemen END AS departemen,ru.namaruangan AS gudang,pro.namaproduk,ss.satuanstandar,pp.jumlah,pp.hargajual,
                       (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
			           CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS subtotal,
                       pp.hargadiscount,pd.noregistrasi,ps.nocm,ps.namapasien,pg.namalengkap as dokter,sr.noresep,
                       CASE WHEN pp.hargahpp IS NULL THEN (pp.hargajual/115)* 100 ELSE pp.hargahpp END  AS hpp,
                       djp.detailjenisproduk,rkn.namarekanan,kp.kelompokpasien,ru2.namaruangan AS ruanganterakhir,
                       CASE WHEN ru2.objectdepartemenfk in ('$idDepRajal') THEN 'Rajal' ELSE 'Ranap' END AS statrawat,pro.isfornas,pro.isgeneric,0 AS hpp,pp.produkfk,pp.hargajual / 111*100 as hna
                FROM strukresep_t as sr
                INNER JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
                INNER JOIN pasiendaftar_t AS pd ON pd.norec=apd.noregistrasifk
                INNER JOIN pasien_m AS ps ON ps.id=pd.nocmfk
                LEFT JOIN jeniskelamin_m AS jk ON jk.id=ps.objectjeniskelaminfk
                LEFT JOIN pegawai_m AS pg ON pg.id=sr.penulisresepfk
                LEFT JOIN ruangan_m AS ru ON ru.id=sr.ruanganfk
                LEFT JOIN ruangan_m AS ru1 ON ru1.id = apd.objectruanganfk
                LEFT JOIN departemen_m AS dep ON dep.id = ru1.objectdepartemenfk
                LEFT JOIN departemen_m AS dep1 ON dep1.id = ru.objectdepartemenfk
                LEFT JOIN kamar_m AS kmr ON kmr.id = apd.objectkamarfk
                LEFT JOIN tempattidur_m AS tt ON tt.id = apd.nobed
                LEFT JOIN kelompokpasien_m kp ON kp.id=pd.objectkelompokpasienlastfk
                LEFT JOIN rekanan_m AS rkn ON rkn.id = pd.objectrekananfk
                LEFT JOIN strukpelayanan_t AS sp ON sp.norec = pp.strukfk
                LEFT JOIN ruangan_m as ruanganAntrian on ruanganAntrian.id = apd.objectruanganfk
                LEFT JOIN produk_m AS pro ON pro.id = pp.produkfk
                LEFT JOIN satuanstandar_m AS ss ON ss.id = pp.satuanviewfk
                LEFT JOIN detailjenisproduk_m AS djp ON djp.id = pro.objectdetailjenisprodukfk
                LEFT JOIN ruangan_m AS ru2 ON ru2.id = pd.objectruanganlastfk
                WHERE sr.kdprofile = $kdProfile AND pd.statusenabled=true AND sr.statusenabled = true
                AND pp.tglpelayanan BETWEEN '$tglAwal' and '$tglAkhir'
                AND pp.jumlah > 0
                $dokid
                $kpid
                $ruid
                $rajal
                $ranap
                $noresep
                $namaProduk

                UNION ALL

                SELECT TO_CHAR(sp.tglstruk, 'DD-MM-YYYY') AS tglresep,ruanganAntrian.namaruangan as ruanganpengorder,dep.namadepartemen AS departemen,
                       ru.namaruangan AS gudang,pro.namaproduk,ss.satuanstandar,spd.qtyproduk as jumlah,spd.harganetto AS hargajual,(spd.qtyproduk * (spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END)
                       + CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS  subtotal,
                       spd.hargadiscount,'' as noregistrasi,sp.nostruk_intern as nocm,sp.namapasien_klien as namapasien,pg.namalengkap as dokter,sp.nostruk AS noresep,
                       (spd.harganetto/115)* 100 AS hpp,djp.detailjenisproduk,'-' AS namarekanan,'Umum/Pribadi' AS kelompokpasien,ruanganAntrian.namaruangan AS ruanganterakhir,
                       'Rajal' AS statrawat,pro.isfornas,pro.isgeneric,0 AS hpp,spd.objectprodukfk AS produkfk,pp.hargajual / 111*100 as hna
                FROM strukpelayanan_t as sp
                INNER JOIN strukpelayanandetail_t as spd on spd.nostrukfk = sp.norec
                LEFT JOIN stokprodukdetail_t AS spt ON spt.nostrukterimafk = spd.strukterimafk
                AND spt.objectprodukfk = spd.objectprodukfk AND spt.objectruanganfk = sp.objectruanganfk
                LEFT JOIN pegawai_m as pg on pg.id=sp.objectpegawaipenanggungjawabfk
                LEFT JOIN strukbuktipenerimaan_t as sbm on sbm.norec = sp.nosbmlastfk
                LEFT JOIN pegawai_m as pg2 on pg2.id = sbm.objectpegawaipenerimafk
                LEFT JOIN loginuser_s as lu on lu.id = sbm.objectpegawaipenerimafk
                LEFT JOIN pegawai_m as pg3 on pg3.id = lu.objectpegawaifk
                LEFT JOIN ruangan_m as ru on ru.id = sp.objectruanganfk
                LEFT JOIN departemen_m AS dep ON dep.id = ru.objectdepartemenfk
                left JOIN pelayananpasien_t AS pp ON pp.strukfk = sp.norec
                left join strukresep_t as sr on sr.norec = pp.strukresepfk
                left JOIN antrianpasiendiperiksa_t AS apd ON apd.norec=sr.pasienfk
                left join ruangan_m as ruanganAntrian on ruanganAntrian.id = apd.objectruanganfk
                LEFT JOIN produk_m AS pro ON pro.id = spd.objectprodukfk
                LEFT JOIN satuanstandar_m AS ss ON ss.id = spd.objectsatuanstandarfk
                LEFT JOIN detailjenisproduk_m AS djp ON djp.id = pro.objectdetailjenisprodukfk
                WHERE sp.statusenabled = true AND sp.kdprofile = $kdProfile and sp.tglstruk BETWEEN '$tglAwal' and '$tglAkhir'
                AND substring(sp.nostruk,1,2)='OB'
                AND spd.qtyproduk > 0
                $dokid
                $ruid
                $noresepBebas
                $namaProduk
                GROUP BY sp.tglstruk,ruanganAntrian.namaruangan,dep.namadepartemen,ru.namaruangan,pro.namaproduk,ss.satuanstandar,spd.qtyproduk,spd.harganetto,
                spd.hargadiscount,spd.hargasatuan,spd.hargadiscount,spd.hargatambahan,sp.nostruk_intern,sp.namapasien_klien,pg.namalengkap,sp.nostruk,
                djp.detailjenisproduk,pro.isfornas,pro.isgeneric,spd.objectprodukfk,pp.hargajual
        "));

        $date = Carbon::parse($tglAwal);
        $month = $date->format('m');
        $tglAyeuna = date('Y');
        $tglAwalH = "01-01-" . $tglAyeuna . " 00:00:00";
        if ($month != "01") {
            $dataHarga = collect(DB::select("
                SELECT x.objectprodukfk,count(x.objectprodukfk) AS row,sum(nullif(x.rata,0)) AS totalrata,sum(nullif(x.rata,0))/count(nullif(x.objectprodukfk,0)) AS rata
                FROM(select spd.objectprodukfk,spd.hargasatuan,1 AS row,
                        CAST(spd.hargasatuan- CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END AS FLOAT) / nullif (spd.hasilkonversi,0) as rata
                from strukpelayanan_t sp
                inner join strukpelayanandetail_t spd on sp.norec=spd.nostrukfk
                where spd.tglpelayanan BETWEEN '$tglAwalH' and '$tglAkhir'
                and spd.qtyproduk > 0
                and sp.objectkelompoktransaksifk=35 and sp.kdprofile = $kdProfile
                GROUP BY spd.objectprodukfk,spd.hargasatuan,spd.hargadiscount,spd.hasilkonversi)
                AS x
                GROUP BY x.objectprodukfk;
            "));

            // Tanggal yang telah ada
            $existingDate = Carbon::parse($tglAwal);
            $existingDateAkhir = Carbon::parse($tglAkhir);

            // Mengurangkan satu bulan
            $oneMonthAgo = $existingDate->subMonth(1);
            $oneMonthAgoAkhir = $existingDateAkhir->subMonth(1);

            // Tampilkan hasilnya

            $dataHargaTahun = collect(DB::select("
                SELECT x.objectprodukfk,count(x.objectprodukfk) AS row,sum(nullif(x.rata,0)) AS totalrata,sum(nullif(x.rata,0))/count(nullif(x.objectprodukfk,0)) AS rata
                FROM(select spd.objectprodukfk,spd.hargasatuan,1 AS row,
                            CAST(spd.hargasatuan- CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END AS FLOAT) / nullif(spd.hasilkonversi,0) as rata
                from strukpelayanan_t sp
                inner join strukpelayanandetail_t spd on sp.norec=spd.nostrukfk
                where spd.tglpelayanan BETWEEN '$oneMonthAgo' and '$oneMonthAgoAkhir'
                and spd.qtyproduk > 0
                and sp.objectkelompoktransaksifk=35 and sp.kdprofile = $kdProfile
                GROUP BY spd.objectprodukfk,spd.hargasatuan,spd.hargadiscount,spd.hasilkonversi)
                AS x
                GROUP BY x.objectprodukfk;
            "));
        } else {
            $dataHarga = collect(DB::select("
                SELECT x.objectprodukfk,count(x.objectprodukfk) AS row,sum(nullif(x.rata,0)) AS totalrata,sum(nullif(x.rata,0))/count(nullif(x.objectprodukfk,0)) AS rata
                FROM(select spd.objectprodukfk,spd.hargasatuan,1 AS row,
                        CAST(spd.hargasatuan- CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END AS FLOAT) / nullif(spd.hasilkonversi,0) as rata
                from strukpelayanan_t sp
                inner join strukpelayanandetail_t spd on sp.norec=spd.nostrukfk
                where spd.tglpelayanan BETWEEN '$tglAwalH' and '$tglAkhir'
                and spd.qtyproduk > 0
                and sp.objectkelompoktransaksifk=35 and sp.kdprofile = $kdProfile
                GROUP BY spd.objectprodukfk,spd.hargasatuan,spd.hargadiscount,spd.hasilkonversi)
                AS x
                GROUP BY x.objectprodukfk;
            "));

            // Tanggal yang telah ada
            $existingDate = Carbon::parse($tglAwal);
            $existingDateAkhir = Carbon::parse($tglAkhir);

            // Mengurangkan satu bulan
            $oneMonthAgo = $existingDate->subMonth(1);
            $oneMonthAgoAkhir = $existingDateAkhir->subMonth(1);

            // Tampilkan hasilnya

            $dataHargaTahun = collect(DB::select("
                SELECT x.objectprodukfk,count(x.objectprodukfk) AS row,sum(nullif(x.rata,0)) AS totalrata,sum(nullif(x.rata,0))/count(nullif(x.objectprodukfk,0)) AS rata
                FROM(select spd.objectprodukfk,spd.hargasatuan,1 AS row,
                            CAST(spd.hargasatuan- CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END AS FLOAT) / nullif(spd.hasilkonversi,0) as rata
                from strukpelayanan_t sp
                inner join strukpelayanandetail_t spd on sp.norec=spd.nostrukfk
                where spd.tglpelayanan BETWEEN '$oneMonthAgo' and '$oneMonthAgoAkhir'
                and spd.qtyproduk > 0
                and sp.objectkelompoktransaksifk=35 and sp.kdprofile = $kdProfile
                GROUP BY spd.objectprodukfk,spd.hargasatuan,spd.hargadiscount,spd.hasilkonversi)
                AS x
                GROUP BY x.objectprodukfk;
            "));
        }
        $tahunM = $tahun - 1;
        $dataHargaMundur = collect(DB::select("
            SELECT x.objectprodukfk,count(x.objectprodukfk) AS row,sum(x.rata) AS totalrata,sum(x.rata)/count(x.objectprodukfk) AS rata
            FROM(select spd.objectprodukfk,spd.hargasatuan,1 AS row,
                    CAST(spd.hargasatuan- CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END AS FLOAT) / spd.hasilkonversi as rata
            from strukpelayanan_t sp
            inner join strukpelayanandetail_t spd on sp.norec=spd.nostrukfk
            where extract(year from spd.tglpelayanan) = '$tahunM'
            and spd.qtyproduk > 0
            and sp.objectkelompoktransaksifk=35 and sp.kdprofile = $kdProfile
            GROUP BY spd.objectprodukfk,spd.hargasatuan,spd.hargadiscount,spd.hasilkonversi)
            AS x
            GROUP BY x.objectprodukfk;
        "));

        foreach ($data as $item) {
            $item->hpp = 0;
            foreach ($dataHarga as $itemHarga) {
                if ($item->produkfk == $itemHarga->objectprodukfk) {
                    $item->hpp = $itemHarga->rata;
                }
            }

            if ($item->hpp == 0) {
                foreach ($dataHargaTahun as $itemHargaTahun) {
                    if ($item->produkfk == $itemHargaTahun->objectprodukfk) {
                        $item->hpp = $itemHargaTahun->rata;
                    }
                }
            }

            if ($item->hpp == 0) {
                foreach ($dataHargaMundur as $itemHTs) {
                    if ($item->produkfk == $itemHTs->objectprodukfk) {
                        $item->hpp = $itemHTs->rata;
                    }
                }
            }
        }

        $result = [
            'daftar' => $data,
            'message' => 'as@epic',
        ];

        return $this->respond($result);
    }
    public function getLaporanPengeluaranObat(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $dokid = '';
        $kpid = '';
        $ruid = '';
        if (isset($request['dokid']) && $request['dokid'] != "" && $request['dokid'] != "undefined") {
            $dokid = ' and pg.id=' . $request['dokid'];
        }
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $kpid = ' and pd.objectkelompokpasienlastfk=' . $request['kpid'];
        }
        if (isset($request['ruid']) && $request['ruid'] != "" && $request['ruid'] != "undefined") {
            $ruid = ' and ru.id=' . $request['ruid'];
        }
        $data = DB::select(DB::raw("SELECT x.tglresep,x.noresep,x.noregistrasi,x.nocm,x.namapasien,x.jeniskelamin,x.kelompokpasien,x.namalengkap,
              x.ruanganapotik,SUM(x.tunai) AS tunai,SUM(x.penjamin) AS penjamin ,CASE WHEN x.norec_sp IS NOT NULL THEN 'SUDAH BAYAR'  ELSE 'BELUM BAYAR' END AS status_bayar
              FROM(SELECT to_char(sr.tglresep, 'yyyy-MM-dd') AS tglresep,sr.noresep,pd.noregistrasi,ps.nocm,UPPER(ps.namapasien) AS namapasien,
              UPPER(jk.reportdisplay) AS jeniskelamin,kp.kelompokpasien,CASE WHEN pg.namalengkap IS NULL THEN '-' ELSE pg.namalengkap END AS namalengkap,ru.namaruangan AS ruanganapotik,
              CASE WHEN pd.objectkelompokpasienlastfk = 1  THEN CAST(sp.totalharusdibayar AS FLOAT) ELSE 0 END AS tunai,
              CASE WHEN pd.objectkelompokpasienlastfk = 2 THEN CAST(sp.totalprekanan AS FLOAT)
              WHEN pd.objectkelompokpasienlastfk in (3,8,11,12,13,14,15,16,17) THEN CAST(sp.totalharusdibayar AS FLOAT) ELSE 0 END AS penjamin ,sp.norec AS norec_sp
              FROM strukresep_t as sr
              INNER JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
              INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec=sr.pasienfk
              INNER JOIN pasiendaftar_t AS pd ON pd.norec=apd.noregistrasifk
              INNER JOIN pasien_m AS ps ON ps.id=pd.nocmfk
              LEFT JOIN jeniskelamin_m AS jk ON jk.id=ps.objectjeniskelaminfk
              LEFT JOIN pegawai_m AS pg ON pg.id=sr.penulisresepfk
              LEFT JOIN ruangan_m AS ru ON ru.id=sr.ruanganfk
              LEFT JOIN kelompokpasien_m kp ON kp.id=pd.objectkelompokpasienlastfk
              LEFT JOIN strukpelayanan_t AS sp ON sp.norec = pp.strukfk
              WHERE sr.kdprofile = $kdProfile and sr.tglresep BETWEEN '$tglAwal' and '$tglAkhir'
              $dokid
              $kpid
              $ruid
              GROUP BY sr.tglresep,sr.noresep,pd.noregistrasi,ps.nocm,ps.namapasien,jk.reportdisplay,kp.kelompokpasien,
				 pg.namalengkap,ru.namaruangan,pd.objectkelompokpasienlastfk,sp.totalharusdibayar,sp.totalprekanan,sp.norec
            UNION ALL

              SELECT to_char(sp.tglstruk, 'yyyy-MM-dd')  AS tglresep,sp.nostruk AS noresep,'-' AS noregistrasi,'-' AS nocm,
              UPPER(sp.namapasien_klien) AS namapasien,'-' AS jeniskelamin,'Umum/Pribadi' as kelompokpasien,
              CASE WHEN pg.namalengkap IS NULL THEN '-' ELSE pg.namalengkap END AS namalengkap,ru.namaruangan AS ruanganapotik,
              sp.totalharusdibayar AS tunai, 0 AS penjamin,sp.norec AS norec
              FROM strukpelayanan_t as sp
              LEFT JOIN strukpelayanandetail_t as spd on spd.nostrukfk = sp.norec
              LEFT JOIN pegawai_m as pg on pg.id=sp.objectpegawaipenanggungjawabfk
              INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec = sp.nosbmlastfk
              LEFT JOIN pegawai_m as pg2 on pg2.id = sbm.objectpegawaipenerimafk
              LEFT JOIN loginuser_s as lu on lu.id = sbm.objectpegawaipenerimafk
              LEFT JOIN pegawai_m as pg3 on pg3.id = lu.objectpegawaifk
              LEFT JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
              WHERE sp.kdprofile = $kdProfile and sp.tglstruk BETWEEN '$tglAwal' and '$tglAkhir'
                    AND sp.nostruk_intern='-' AND substring(sp.nostruk,1,2)='OB'
                    $dokid
                    $ruid
              GROUP BY sp.tglstruk,sp.nostruk,sp.namapasien_klien,pg.namalengkap,ru.namaruangan,sp.totalharusdibayar,sp.norec

            UNION ALL

              SELECT  to_char(sp.tglstruk, 'yyyy-MM-dd')  AS tglresep,sp.nostruk AS noresep,'-' AS noregistrasi,ps.nocm,
              UPPER(sp.namapasien_klien) AS namapasien,UPPER(jk.reportdisplay) AS jeniskelamin,
              'Umum/Pribadi' as kelompokpasien,CASE WHEN pg.namalengkap IS NULL THEN '-' ELSE pg.namalengkap END AS namalengkap,
              ru.namaruangan AS ruanganapotik,sp.totalharusdibayar AS tunai, 0 AS penjamin,	'-' AS norec_sp
              FROM strukpelayanan_t as sp
              INNER JOIN strukpelayanandetail_t as spd on spd.nostrukfk = sp.norec
              INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec = sp.nosbmlastfk
              LEFT JOIN pegawai_m as pg2 on pg2.id = sbm.objectpegawaipenerimafk
              LEFT JOIN loginuser_s as lu on lu.objectpegawaifk = sbm.objectpegawaipenerimafk
              LEFT JOIN pasien_m as ps on ps.nocm=sp.nostruk_intern
              LEFT JOIN jeniskelamin_m as jk on jk.id=ps.objectjeniskelaminfk
              LEFT JOIN pegawai_m as pg on pg.id=sp.objectpegawaipenanggungjawabfk
              LEFT JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
              WHERE sp.kdprofile = $kdProfile and sp.tglstruk BETWEEN '$tglAwal' and '$tglAkhir'
                AND sp.nostruk_intern not in ('-') AND substring(sp.nostruk,1,2)='OB'
                $dokid
               $ruid
              GROUP BY sp.tglstruk,sp.nostruk,sp.namapasien_klien,jk.reportdisplay,ps.nocm,
				       pg.namalengkap,ru.namaruangan,sp.totalharusdibayar
              ) AS x GROUP BY x.tglresep,x.noresep,x.noregistrasi,x.nocm,x.namapasien,
              x.jeniskelamin,x.kelompokpasien,x.namalengkap,x.ruanganapotik,x.norec_sp
              ORDER BY x.tglresep asc"));
        $result = [
            'daftar' => $data,
            'message' => 'as@epic',
        ];
        return $this->respond($result);
    }
}
