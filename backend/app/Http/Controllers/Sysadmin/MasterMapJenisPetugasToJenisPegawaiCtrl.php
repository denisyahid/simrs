<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JenisPegawai;
use App\Models\Master\JenisPetugasPelaksana;
use App\Models\Master\KelompokProduk;
use App\Models\Master\MapJenisPetugasToJenisPegawai;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMapJenisPetugasToJenisPegawaiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterMapJenisPetugasToJenisPegawai(Request $r)
    {
        $data  = DB::table('mapjenispetugasptojenispegawai_m as mp')
        ->join('jenispetugaspelaksana_m as pr', 'mp.objectjenispetugaspefk', '=', 'pr.id')
        ->join('jenispegawai_m as ru', 'mp.objectjenispegawaifk', '=', 'ru.id')
        ->select(
            'mp.id',
            'mp.kodeexternal',
            'mp.namaexternal',
            'mp.norec',
            'mp.reportdisplay',
            'mp.objectjenispegawaifk',
            'mp.objectjenispetugaspefk',
            'pr.jenispetugaspe',
            'ru.jenispegawai',
        )
            ->where('mp.statusenabled', true)
            ->where('ru.kdprofile',$this->kdProfile)
            ->where('ru.statusenabled',true)
            ->where('mp.kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mp.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['objectjenispetugaspefk']) && $r['objectjenispetugaspefk'] != '') {
            $data = $data->where('mp.objectjenispetugaspefk', '=', $r['objectjenispetugaspefk'] );
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['jenispegawai']) && $r['jenispegawai'] != '') {
            $data = $data->where('ru.jenispegawai', 'ilike', '%'.$r['jenispegawai'].'%');
        }
        if (isset($r['rows']) && $r['rows'] != '') {
            $data = $data->limit($r['rows']);
        }
        if (isset($r['objectjenispegawaifk']) && $r['objectjenispegawaifk'] != '') {
            $data = $data->where('mp.objectjenispegawaifk', '=', $r['objectjenispegawaifk'] );
        }
        if (isset($r['objectjenispetugaspefk']) && $r['objectjenispetugaspefk'] != '') {
            $data = $data->where('mp.objectjenispetugaspefk', '=', $r['objectjenispetugaspefk'] );
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMapJenisPetugasToJenisPegawai(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Petugas To Jenis Pegawai

            $delete = MapJenisPetugasToJenisPegawai::where('objectjenispetugaspefk', $r['objectjenispetugaspefk'])
            ->update(['statusenabled'=> false]);
            foreach($r['detail'] as $item){
                $dataPS = new MapJenisPetugasToJenisPegawai();
                $dataPS->id =  $this->SEQUENCE_MASTER(new MapJenisPetugasToJenisPegawai(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->objectjenispetugaspefk =  $r['objectjenispetugaspefk'];
                $dataPS->objectjenispegawaifk = $item['pegawaifk'];
                $dataPS->save();
            }
              
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

    public function deleteMapJenisPetugasToJenisPegawai(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = MapJenisPetugasToJenisPegawai::where('id', $r['id'])
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
    public function MasterMapJenisPetugasToJenisPegawaiDropdown (Request $r)
    {
        $res['jenispetugaspe'] = JenisPetugasPelaksana::mine()->get();
        $res['jenispegawai'] = JenisPegawai::mine()->get();


        return $this->respond($res);
    }
    
    // public function listRuangan(Request $r)
    // {
    //     $res['namaruangan'] = Ruangan::mine();
    //     if (isset($r['rufk']) && $r['rufk'] != '') {
    //         $res['namaruangan'] = $res['namaruangan']->where('objectdepartemenfk', $r['rufk']);
    //     }
    //     if (isset($r['objectdepartemenfk']) && $r['objectdepartemenfk'] != '') {
    //         $res['namaruangan'] = $res['namaruangan']->where('objectdepartemenfk', $r['objectdepartemenfk']);
    //     }
    //     $res['namaruangan'] = $res['namaruangan']
    //         ->orderby('namaruangan')
    //         ->get();
    //     return $this->respond($res);
    // }

    
}

