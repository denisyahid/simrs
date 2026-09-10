<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SaldoAwalHarianCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saldoawalharian:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saldo Awal Harian Persediaan';

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
            $objetoRequest['produkfk'] = 1; //hardcore
            
            $insert = app('App\Http\Controllers\Logistik\PersediaanCtrl')->saveSaldoProdukDetail($objetoRequest, true);
            if($insert['status'] == 200){
                Log::info("Post Saldo awal berhasil : " . date('Y-m-d H:i:s') .' ( '.$insert['result']. ' )');
            }else{
                Log::info("Post Saldo awal gagal : " . date('Y-m-d H:i:s') .' ( '.$insert['result']. ' )');
            }
        } catch (\Exception $e) {

            Log::info("Post Saldo gagal " . $e->getMessage() . " " . $e->getLine());
        }
    }
}
