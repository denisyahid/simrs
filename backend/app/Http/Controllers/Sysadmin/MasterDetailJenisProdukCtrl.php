<?php

namespace App\Http\Controllers\Sysadmin;
use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JenisProduk;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\PPRAGenerik;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDetailJenisProdukCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterDetailJenisProduk(Request $r)
    {
        $data  = DB::table('detailjenisproduk_m as jsp')
            ->join('jenisproduk_m as js', 'jsp.objectjenisprodukfk', '=', 'js.id')
            ->leftJoin('departemen_m as dp', 'js.objectdepartemenfk', '=', 'dp.id')
            ->select(
                'jsp.id',
                'jsp.detailjenisproduk',
                'jsp.objectdepartemenfk',
                'jsp.objectjenisprodukfk',
                'js.jenisproduk',
                'dp.namadepartemen'
            )

            ->where('jsp.statusenabled', true)
            ->where('jsp.kdprofile', $this->kdProfile);
            
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('jsp.id', '=',  $r['id']);
        }
        if (isset($r['detailjenisproduk']) && $r['detailjenisproduk'] != '') {
            $data = $data->where('jsp.detailjenisproduk', 'ilike', '%' . $r['detailjenisproduk'] . '%');
        }
        $data = $data->orderByDesc('jsp.detailjenisproduk');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function masterPPRAGenerik(Request $r)
    {
        $data  = DB::table('ppra_generik as pg')
            ->select(
                'pg.id',
                'pg.namagenerik'
            )

            ->where('pg.statusenabled', true);
            
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('pg.id', '=',  $r['id']);
        }
        
        $data = $data->orderBy('pg.namagenerik', 'asc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    
    public function masterDetailJenisProdukdropdown(Request $r)
    {
        $res['namadepartemen'] = Departemen::mine()->get();
        $res['jenisproduk'] = JenisProduk::mine()->get();

        return $this->respond($res);
    }
    public function saveDetailJenisProduk(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan

            $PSN =  $r['detailjenisproduk'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new DetailJenisProduk(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new DetailJenisProduk();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = DetailJenisProduk::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->detailjenisproduk =  $PSN['detailjenisproduk'];
            $dataPS->objectjenisprodukfk =  $PSN['objectjenisprodukfk'];
            $dataPS->objectdepartemenfk =  $PSN['objectdepartemenfk'];
            $dataPS->qdetailjenisproduk = 0;  
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
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveMasterGenerik(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan

            $PSN =  $r['mastergenerik'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new PPRAGenerik(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new PPRAGenerik();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->kodeexternal = (int)$this->kdProfile;
                $dataPS->namaexternal = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = PPRAGenerik::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->norec =  $id;
            $dataPS->reportdisplay =  $PSN['namagenerik'];
            $dataPS->namagenerik =  $PSN['namagenerik'];
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
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function deleteDetailJenisProduk(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = DetailJenisProduk::where('id', $r['id'])
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

    public function deleteMasterGenerik(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = PPRAGenerik::where('id', $r['id'])
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
