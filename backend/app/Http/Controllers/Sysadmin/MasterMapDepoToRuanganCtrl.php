<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Standar\LoginUser;
use App\Models\Standar\MapDepoToRuangan;
use App\Models\Standar\MapLoginUserToRuangan;
use App\Models\Standar\ModulAplikasi;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMapDepoToRuanganCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterMapDepoToRuangan(Request $r)
    {
        $data  = DB::table('mapdepotoruangan_t as mp')
        ->join('ruangan_m as ru', 'mp.objectdepofk', '=', 'ru.id')
        ->select(
            'mp.id',
            'mp.objectdepofk',
            'mp.objectruanganfk',
            'ru.namaruangan',
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mp.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['objectdepofk']) && $r['objectdepofk'] != '') {
            $data = $data->where('mp.objectdepofk', '=', $r['objectdepofk'] );
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        // if (isset($r['namaruangan']) && $r['namaruangan'] != '') {
        //     $data = $data->where('pr.namaruangan', 'ilike', '%'.$r['namaruangan'].'%');
        // }
        // if (isset($r['rows']) && $r['rows'] != '') {
        //     $data = $data->limit($r['rows']);
        // }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMapDepoToRuangan(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Login User To Ruangan
            MapDepoToRuangan::where('objectdepofk', $r['objectdepofk'])->update(['statusenabled'=> false]);
            foreach($r['detail'] as $item){
                $dataPS = new MapDepoToRuangan();
                $dataPS->id = $this->SEQUENCE_MASTER(new MapDepoToRuangan(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->objectdepofk =  $r['objectdepofk'];
                $dataPS->objectruanganfk = $item['ruanganfk'];
                $dataPS->save();
            }

            DB::commit();
            $result = [
                'data' => $dataPS,
                'status' => 201,
                'message' => 'Maping Ruangan Berhasil'
            ];

        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'data' =>$e->getMessage(),
                'status' => 400,
                'message' => 'Maping Ruangan Gagal'
            ];
        }
       
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function deleteMapDepoToRuangan(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = MapDepoToRuangan::where('id', $r['id'])->update(['statusenabled' => false]);
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
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function masterMapDepoToRuangandropdown(Request $r)
    {    
        $res['depo'] = Ruangan::select('id','namaruangan')->where('kdprofile',$this->kdProfile)
                        ->where('statusenabled',true)
                        ->where('objectdepartemenfk',$this->settingFix('idInstalasiFarmasi'))->get();
        $res['ruangan'] = Ruangan::select('id','namaruangan','objectdepartemenfk')->where('kdprofile',$this->kdProfile)
                            ->where('statusenabled',true)->where('objectdepartemenfk','!=', $this->settingFix('idInstalasiFarmasi'))
                            ->get();


        return $this->respond($res);
    }
    
}

