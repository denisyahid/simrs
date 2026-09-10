<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Profile;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
class BSRECtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    
    public function BSRECPPTRanap(Request $request){
        $norec = $request['emr'];
        $kdProfile = 1;
        $data = DB::select(DB::raw("
                  SELECT
                        epd.emrdfk,
                        ep.noemr,
                        ed.TYPE,
                        pa.namapasien,
                        pa.tgllahir,
                        pa.nohp,
                        pa.nocm,
                        ep.jeniskelamin,
                        ep.umur,
                        al.alamatlengkap,
                        ep.noregistrasifk as noregistrasi ,
                        epd.value,ep.namaruangan,pg.namalengkap as namadokter
                     FROM
                        emrpasien_t AS ep
                        INNER JOIN emrpasiend_t AS epd ON ep.noemr = epd.emrpasienfk
                        INNER JOIN emrd_t AS ed ON epd.emrdfk = ed.ID
                        INNER JOIN antrianpasiendiperiksa_t AS pd ON pd.norec = ep.norec_apd
                        left JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                        left JOIN pasien_m as pa on ep.nocm =  pa.nocm
                        left JOIN alamat_m as al on pa.id = al.nocmfk

                    "
        ));
  
          foreach ($data as $z){
            if ($z->type == "datetime"){
                $z->value= date('Y-m-d H:i:s',strtotime($z->value));
            }
          }
          $pageWidth = 500;
       
        //   $headers = $this->headers();
          $res['profile'] = Profile::where('id',$request['kdprofile'])->first();
          $res['d'] = $data;
          ini_set("memory_limit","256M");
          ini_set('max_execution_time', '300');
          ini_set("pcre.backtrack_limit", "5000000");
          $rss = array(
            'res' => $res,
            'pageWidth' => $pageWidth
          );
          $pageWidth = 950;
          $pdf = App::make('dompdf.wrapper');
          $pdf->loadView('report.cppt-ranap-dom', $rss);
          return $pdf->stream();
      
     
         return $pdf->download('invoice.pdf');
    }
}