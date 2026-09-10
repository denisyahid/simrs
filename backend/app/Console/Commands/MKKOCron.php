<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MKKOCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MKKO:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MKKO HARIAN';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $objetoRequest = new \Illuminate\Http\Request();
            $START_DATE  = date('Y-m-d 00:00:00');
            $END_DATE  = date('Y-m-d 23:59:59');
            // $PARAMETER_BULAN = date('Y-m');
            $objetoRequest['dari'] = $START_DATE;
            $objetoRequest['sampai'] = $END_DATE;
            $objetoRequest['query'] = "
            SELECT
            to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,
                kps.kelompokpasien,(((CASE WHEN pp.hargadijamin IS NULL THEN pp.hargajual WHEN pp.hargadijamin = 0 THEN pp.hargajual ELSE pp.hargadijamin END -	CASE WHEN pp.hargadiscount IS NULL THEN	0 ELSE pp.hargadiscount END) * pp.jumlah)
                + CASE WHEN pp.jasa IS NULL THEN	0 ELSE pp.jasa END) AS total,
                pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan
            FROM pelayananpasien_t AS pp
            JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
            JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
            JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
            LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
            LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
            WHERE  pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'
            AND pp.strukresepfk IS NULL AND pd.statusenabled = TRUE AND pp.statusenabled = TRUE and dpm.statusenabled = true

            UNION ALL

            SELECT to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
                (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
                CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS total,
                                        pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan
            FROM strukresep_t AS sr
            JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
            JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
            JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
            JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
            LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
            LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
            WHERE  pd.statusenabled = TRUE AND pp.statusenabled = TRUE AND sr.statusenabled = TRUE
            AND pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
            AND pp.jumlah > 0 and dpm.statusenabled = true

            UNION ALL

            SELECT to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
                (((CASE WHEN pps.hargajual IS NULL THEN 0 ELSE pps.hargajual END - CASE WHEN pps.hargadiscount IS NULL THEN 0 ELSE pps.hargadiscount END) * pp.jumlah) +
                CASE WHEN pps.jasa IS NULL THEN 0 ELSE pps.jasa END) AS total,
                                        pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan
            FROM strukresep_t AS sr
            INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
            INNER JOIN pelayananpasien_t AS pps ON pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
            JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
            JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
            JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
            LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
            LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
            WHERE  pd.statusenabled = TRUE AND pps.statusenabled = TRUE AND sr.statusenabled = TRUE
            AND pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
            AND pp.jumlah > 0 and dpm.statusenabled = true

            UNION ALL

            SELECT to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dp.namadepartemen,'Umum/Pribadi' AS kelompokpasien,
            (spd.qtyproduk * ( spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END ) +
            CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
                                1 as objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,1 as jenispelayanan,		to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan
            FROM strukpelayanan_t AS sp
            JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
            LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
            LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
            WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
            AND sp.nostruk LIKE'OB%' AND sp.statusenabled = true AND spd.qtyproduk > 0 and dp.statusenabled = true

            UNION ALL

            SELECT to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,'Non Layanan' AS namaruangan,'Instalasi Rawat Jalan dan Rehabilitasi Medik' AS namadepartemen,
                'Umum/Pribadi' AS kelompokpasien,(spd.qtyproduk * (spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END) +
                CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
                                        1 as objectkelompokpasienlastfk,18 as kddepartemen, 1 as jenispelayanan,	to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan
            FROM strukpelayanan_t AS sp
            JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
            WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
            AND substring(sp.nostruk,0,3) = 'NL' AND sp.statusenabled = true";
            $insert = app('App\Http\Controllers\laporan\MKKOCtrl')->jmlPendapatanQuery($objetoRequest, true);
            // if($insert['status'] == 200){
            //     Log::info("POST Pendapatan MKKO berhasil : " . date('Y-m-d H:i:s') .' ( '.$insert['result']. ' )');
            // }else{
            //     Log::info("POST Pendapatan MKKO gagal : " . date('Y-m-d H:i:s') .' ( '.$insert['result']. ' )');
            // }



            $objetoRequest = new \Illuminate\Http\Request();
            // $START_DATE  = date('Y-m-01');
            // $END_DATE  = date('Y-m-t');
            $objetoRequest['query'] = "SELECT
            pd.tglregistrasi,
            pd.noregistrasi,
            ps.namapasien,
            ru.namaruangan,
            CASE
                WHEN dp.ID NOT IN (16) THEN 'Rawat Jalan'
                WHEN dp.ID  IN (16) THEN 'Rawat Inap'
                ELSE dp.namadepartemen
            END AS instalasi,
            CASE
                WHEN kp.ID in (2,18) THEN 'JKN'
                WHEN kp.ID in (31,1) THEN 'Non JKN Umum'
                WHEN kp.ID in (5,22,24) THEN 'Non JKN Asuransi'
                WHEN kp.ID in (29,32) THEN 'Non JKN Perusahaan'
                ELSE 'Non JKN Umum'
            END AS jenispasien,
            CASE
                WHEN pd.objectkelasrawatfk IS NOT NULL
                     AND kp.kelompokpasien = 'BPJS'
                     AND pd.objectkelasrawatfk != pd.objectkelasfk THEN TRUE
                ELSE FALSE
            END AS status_jkn_naik_kelas,
            CASE
                WHEN jp.ID = 1 THEN 'Reguler'
                WHEN jp.ID = 2 THEN 'Eksekutif'
                ELSE ''
            END AS jenispelayanan,
            to_char(pd.tglregistrasi, 'yyyy-MM-dd') AS bulan
        FROM
            pasiendaftar_t AS pd
            INNER JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
            INNER JOIN jenispelayanan_m AS jp ON jp.ID = pd.jenispelayanan
            LEFT JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganasalfk
            LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.ID = pd.objectkelompokpasienlastfk
        WHERE
            pd.statusenabled = TRUE
            AND pd.kdprofile = 1
            AND pd.tglregistrasi between '$START_DATE' AND '$END_DATE';";

        $insert = app('App\Http\Controllers\laporan\MKKOCtrl')->jmlPengunjungQuery($objetoRequest, true);

        $bor = app('App\Http\Controllers\laporan\MKKOCtrl')->getBORLOS($objetoRequest, true);
        // if($insert['status'] == 200){
        //     Log::info("POST Kunjungan MKKO berhasil : " . date('Y-m-d H:i:s') .' ( '.$insert['result']. ' )');
        // }else{
        //     Log::info("POST Kunjungan MKKO gagal : " . date('Y-m-d H:i:s') .' ( '.$insert['result']. ' )');
        // }
        } catch (\Exception $e) {

            Log::info("POST Pendapatan MKKO gagal " . $e->getMessage() . " " . $e->getLine());
        }
    }
}
