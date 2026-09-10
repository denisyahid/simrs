<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Webpatser\Uuid\Uuid;

class BPJSKlaimCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BPJSklaim:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BPJS Klaim (BAHV)';

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
         
            $kdProfile = 1;
            $monthAgo = date("Y-m-d", strtotime("-2 months", strtotime(date("Y-m-d"))));
          
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = "/Monitoring/Klaim/Tanggal/$monthAgo/JnsPelayanan/1/Status/3";
            $objetoRequest['method'] = "GET";
            $objetoRequest['data'] = null;
            $tools = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->bpjsTools($objetoRequest,true);
            
            $responseSEPVc = json_decode(json_encode($tools, false));
    
            if (isset($responseSEPVc->metaData) && $responseSEPVc->metaData->code == 200) {
                DB::table('monitoringklaim_t')
                    ->where('jenispelayanan', 1)
                    ->where('statusklaimfk', 3)
                    ->whereRaw("to_char(tglpulang,'yyyy-MM-dd') ='$monthAgo'")
                    ->delete();
                $klaim = $responseSEPVc->response->klaim;
                $dataInsert = [];
                foreach ($klaim as $item) {
                   $count =   DB::table('monitoringklaim_t')
                    ->where('jenispelayanan', 1)
                    ->where('nosep', $item->noSEP)
                    ->where('statusklaimfk','!=', 3)
                    ->delete();
    
                    $dataInsert[] = array(
                        'norec' => substr(Uuid::generate(), 0, 32),
                        'kdprofile' => $kdProfile,
                        'statusenabled' => true,
                        'nofpk' => $item->noFPK,
                        'tglpulang' => $item->tglPulang,
                        'jenispelayanan' => 1,
                        'nosep' => $item->noSEP,
                        'tglsep' => $item->tglSep,
                        'status' => $item->status,
                        'totalpengajuan' => $item->biaya->byPengajuan,
                        'totalsetujui' => $item->biaya->bySetujui,
                        'totaltarifrs' =>$item->biaya->byTarifRS,
                        'statusklaimfk' => 3,
                        'totalgrouper' => $item->biaya->byTarifGruper,
                    );
                 
                    if (count($dataInsert) > 100) {
                        DB::table('monitoringklaim_t')->insert($dataInsert);
                        $dataInsert = [];
                    }
                }
    
                DB::table('monitoringklaim_t')->insert($dataInsert);
           
                Log::info("Posting klaim BPJS RANAP : " . date('Y-m-d H:i:s') .' ( '.$responseSEPVc->metaData->message. ' '.$monthAgo. ' )');
            }else{
                Log::info("Posting klaim BPJS RANAP gagal : " . date('Y-m-d H:i:s') .' ( '.$responseSEPVc->metaData->message. ' '.$monthAgo.  ' )');
            }

       
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = "/Monitoring/Klaim/Tanggal/$monthAgo/JnsPelayanan/2/Status/3";
            $objetoRequest['method'] = "GET";
            $objetoRequest['data'] = null;
            $tools = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->bpjsTools($objetoRequest,true);
            
            $responseSEPVc = json_decode(json_encode($tools, false));
    
            if (isset($responseSEPVc->metaData) && $responseSEPVc->metaData->code == 200) {
                DB::table('monitoringklaim_t')
                    ->where('jenispelayanan', 2)
                    ->where('statusklaimfk', 3)
                    ->whereRaw("to_char(tglpulang,'yyyy-MM-dd') ='$monthAgo'")
                    ->delete();
                $klaim = $responseSEPVc->response->klaim;
                $dataInsert = [];
                foreach ($klaim as $item) {
                   $count =   DB::table('monitoringklaim_t')
                    ->where('jenispelayanan', 2)
                    ->where('nosep', $item->noSEP)
                    ->where('statusklaimfk','!=', 3)
                    ->delete();
    
                    $dataInsert[] = array(
                        'norec' => substr(Uuid::generate(), 0, 32),
                        'kdprofile' => $kdProfile,
                        'statusenabled' => true,
                        'nofpk' => $item->noFPK,
                        'tglpulang' => $item->tglPulang,
                        'jenispelayanan' => 2,
                        'nosep' => $item->noSEP,
                        'tglsep' => $item->tglSep,
                        'status' => $item->status,
                        'totalpengajuan' => $item->biaya->byPengajuan,
                        'totalsetujui' => $item->biaya->bySetujui,
                        'totaltarifrs' =>$item->biaya->byTarifRS,
                        'statusklaimfk' => 3,
                        'totalgrouper' => $item->biaya->byTarifGruper,
                    );
                 
                    if (count($dataInsert) > 100) {
                        DB::table('monitoringklaim_t')->insert($dataInsert);
                        $dataInsert = [];
                    }
                }
    
                DB::table('monitoringklaim_t')->insert($dataInsert);
           
                Log::info("Posting klaim BPJS RAJAL : " . date('Y-m-d H:i:s') .' ( '.$responseSEPVc->metaData->message. ' '.$monthAgo. ' )');
            }else{
                Log::info("Posting klaim BPJS RAJAL gagal : " . date('Y-m-d H:i:s') .' ( '.$responseSEPVc->metaData->message. ' '.$monthAgo.  ' )');
            }
        } catch (\Exception $e) {

            Log::info("Posting klaim BPJS gagal " . $e->getMessage() . " " . $e->getLine());
        }
    }
}
