<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MKKO_read_operasi_jmlpegCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MKKO_read_operasi_jmlpeg:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MKKO Laporan readmisi, tindakan operasi, Jml Pegawai';

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
            $PARAMETER_BULAN = date('Y-m');
            $objetoRequest['dari'] = $START_DATE;
            $objetoRequest['sampai'] = $END_DATE;
            $insert = app('App\Http\Controllers\laporan\MKKOCtrl')->persentaseInpatien($objetoRequest, true);


            $PARAMETER_BULAN = date('Y-m');
            $objetoRequest['tglAwal'] = $START_DATE;
            $objetoRequest['tglAkhir'] = $END_DATE;
            $insert = app('App\Http\Controllers\laporan\MKKOCtrl')->laporanTindakanOperasi($objetoRequest, true);



            $PARAMETER_BULAN = date('Y-m');
            $objetoRequest['dari'] = $START_DATE;
            $objetoRequest['sampai'] = $END_DATE;
            $objetoRequest['bulan'] = $PARAMETER_BULAN;
            $insert = app('App\Http\Controllers\laporan\MKKOCtrl')->jmlPegawai($objetoRequest, true);


        } catch (\Exception $e) {

            Log::info("MKKO Laporan readmisi, tindakan operasi, Jml Pegawai gagal " . $e->getMessage() . " " . $e->getLine());
        }
    }
}
