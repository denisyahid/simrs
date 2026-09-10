<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BORCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BOR:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BOR LOS TOI HARIAN';

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
            $objetoRequest['tanggal'] = date('Y-m-d');
            $insert = app('App\Http\Controllers\laporan\MKKOCtrl')->saveBORLOS($objetoRequest, true);
            if($insert['status'] == 200){
                Log::info("POST BOR Harian berhasil : " . date('Y-m-d H:i:s') .' ( '.$insert['result']. ' )');
            }else{
                Log::info("POST BOR Harian gagal : " . date('Y-m-d H:i:s') .' ( '.$insert['result']. ' )');
            }
        } catch (\Exception $e) {

            Log::info("POST BOR gagal " . $e->getMessage() . " " . $e->getLine());
        }
    }
}
