<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Provinsi;
use App\Models\Master\KotaKabupaten;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterKotaKabupatenCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterKotaKabupaten(Request $r)
    {
        $data  = DB::table('kotakabupaten_m as pe')
        ->join('propinsi_m as jp', 'pe.objectpropinsifk', '=', 'jp.id')
        ->select(
            'pe.id',
            'pe.statusenabled',
            'pe.namakotakabupaten',
            'jp.namapropinsi',
            'pe.objectpropinsifk',
            'pe.norec'
        )
        ->where('pe.kdprofile', $this->kdProfile)
        ->where('pe.statusenabled', true);

    if (isset($r['id']) && $r['id'] != '') {
        $data = $data->where('pe.id', '=',  $r['id']);
    }
    
    if (isset($r['namakotakabupaten']) && $r['namakotakabupaten'] != '') {
        $data = $data->where('pe.namakotakabupaten', 'ilike', '%' . $r['namakotakabupaten'] . '%');
    }
    if (isset($r['namapropinsi']) && $r['namapropinsi'] != '') {
        $data = $data->where('pe.id', '=', $r['namapropinsi']);
    }
    if (isset($r['objectpropinsifk']) && $r['objectpropinsifk'] != '') {
        $data = $data->where('pe.objectpropinsifk', '=',  $r['objectpropinsifk']);
    }

    $data = $data->orderByDesc('pe.namakotakabupaten');
    $data = $data->get();

    $res['data'] = $data;
    return $this->respond($res);
}
public function masterKotaKabupatendropdown(Request $r)
{
    $res['namapropinsi'] = Provinsi::mine()->get();

    return $this->respond($res);
}

    public function saveKotaKabupaten(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save KotaKabupaten

            $PSN =  $r['namakotakabupaten'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new KotaKabupaten(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new KotaKabupaten();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = KotaKabupaten::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->namakotakabupaten =  $PSN['namakotakabupaten'];
            $dataPS->objectpropinsifk =  $PSN['objectpropinsifk'];
            $dataPS->namaexternal =  $PSN['namakotakabupaten'];
            $dataPS->reportdisplay =  $PSN['reportdisplay'];
            $dataPS->qkotakabupaten =  $id ;
            $dataPS->kdkotakabupaten =  $id ;

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
    public function deleteKotaKabupaten(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = KotaKabupaten::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);

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
}
