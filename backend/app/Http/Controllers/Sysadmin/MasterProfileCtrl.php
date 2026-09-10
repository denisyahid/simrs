<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Profile;
use App\Models\Master\Suku;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterProfileCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function index()
    {
       $data = DB::table('profile_m')->where('statusenabled',true)->where('kdprofile',$this->kdProfile)->get();

       return $this->respond($data);
    }

    public function saveProfile(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Suku
            if ($r['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new Suku(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new Suku();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = Profile::where('id', $r['id'])->where('statusenabled', true)->first();
                $id =  $dataPS->id;
            }
            $dataPS->kodeexternal =  $r['suku'];
            $dataPS->namaexternal = $r['namalengkap'];
            $dataPS->reportdisplay = $r['reportdisplay'];
            $dataPS->objectkelaslevelfk = $r['objectkelaslevelfk'];
            $dataPS->objectpemilikprofilefk = $r['objectpemilikprofilefk'];
            $dataPS->objectsatuankerjafk = $r['objectsatuankerjafk'];
            $dataPS->objecttahapanakreditasilastfk = $r['objecttahapanakreditasilastfk'];
            $dataPS->alamatemail = $r['alamatemail'];
            $dataPS->alamatlengkap = $r['alamatlengkap'];
            $dataPS->faksimile = $r['faksimile'];
            $dataPS->fixedphone = $r['fixedphone'];
            $dataPS->kodepost = $r['kodepost'];
            $dataPS->luasbangunan = $r['luasbangunan'];
            $dataPS->luastanah = $r['luastanah'];
            $dataPS->mobilephone = $r['mobilephone'];
            $dataPS->motosemboyan = $r['motosemboyan'];
            $dataPS->namalengkap = $r['namalengkap'];
            $dataPS->nosuratijinlast = $r['nosuratijinlast'];
            $dataPS->npwp = $r['npwp'];
            $dataPS->signaturebylast = $r['signaturebylast'];
            $dataPS->website = $r['website'];
            $dataPS->gambarlogo = $r['gambarlogo'];
            $dataPS->tglakreditasilast = $r['tglakreditasilast'];
            $dataPS->tglsuratijinexpiredlast = $r['tglsuratijinexpiredlast'];
            $dataPS->tglsuratijinlast = $r['tglsuratijinlast'];
            $dataPS->namapemerintahan = $r['namapemerintahan'];
            $dataPS->namakota = $r['namakota'];
            $dataPS->logoprofile = $r['logoprofile'];
            $dataPS->logopemerintahan = $r['logopemerintahan'];
            $dataPS->lat = $r['lat'];
            $dataPS->lng = $r['lng'];
            $dataPS->ihs_id = $r['ihs_id'];
            $dataPS->whatsapp = $r['whatsapp'];
            $dataPS->ihs_province = $r['ihs_province'];
            $dataPS->ihs_city = $r['ihs_city'];
            $dataPS->ihs_district = $r['ihs_district'];
            $dataPS->ihs_village = $r['ihs_village'];
            $dataPS->save();
            //endregion

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function dropDown(Request $r){
        $res['tipeRs'] = DB::table('kelasrs_m')->select('id', 'kelasrs')
                    ->where('kdprofile',$this->kdProfile)->where('statusenabled',true)
                    ->get();

        return $this->respond($res);          
    }

}
