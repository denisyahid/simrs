<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB ;
use Illuminate\Support\Facades\Log;

class AkomodasiCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akomodasi:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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


        $kdProfile = DB::table('profile_m')->where('statusenabled',true)->first()->id;
        $inap =  DB::table('settingdatafixed_m')->where('namafield','kdDepartemenRanapFix')->first()->nilaifield;
        $data = DB::select(DB::raw("
            select pd.noregistrasi
            from pasiendaftar_t as pd
            INNER JOIN ruangan_m as ru_pd on ru_pd.id=pd.objectruanganlastfk
            where ru_pd.objectdepartemenfk in ($inap)
            and pd.kdprofile=$kdProfile
            and pd.statusenabled=true
            and pd.tglpulang is null")
        );
        Log::info("Akomodasi START ");
        foreach ($data as $item) {
            try {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest ['noregistrasi'] = $item->noregistrasi;
                $insert = app('App\Http\Controllers\Sysadmin\MapAkomodasiCtrl')->saveAkomodasiAuto($objetoRequest, true, $kdProfile);
                if($insert['status'] == 201){
                    Log::info("Akomodasi berhasil pada no registrasi ". $item->noregistrasi . ' ( Jumlah : '.$insert['result']['count']. ' )') ;
                }else{
                    Log::info("Akomodasi gagal pada no registrasi ". $item->noregistrasi . ' ( '.$insert['result']. ' )');
                }

            } catch (\Exception $e) {

                Log::info("Akomodasi gagal pada no registrasi ". $item->noregistrasi . " ". $e->getMessage(). " ". $e->getLine());
            }
        }
        Log::info("Akomodasi END ");
    }
}
