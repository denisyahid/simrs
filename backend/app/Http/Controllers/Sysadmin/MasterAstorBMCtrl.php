<?php

namespace App\Http\Controllers\Sysadmin;
use App\Http\Controllers\Controller;
use App\Models\Master\PPRAGenerik;
use App\Models\Master\OperasiProfilaksis;
use App\Models\Master\OperasiProfilaksisDetail;
use App\Models\Master\PPRADivisi;
use App\Models\Master\TindakanEmpiris;
use App\Models\Master\TindakanEmpirisDetail;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterAstorBMCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function masterDataGenerik(Request $r)
    {
        $res['namagenerik'] = PPRAGenerik::mine()->get();

        return $this->respond($res);
    }

    public function ListOperasiProfilaksis(Request $r)
    {
        $res['namaoperasi'] = OperasiProfilaksis::mine()->get();

        return $this->respond($res);
    }

    public function ListTindakanEmpiris(Request $r)
    {
        $res['namatindakan'] = TindakanEmpiris::mine()->get();

        return $this->respond($res);
    }

    public function masterDivisiPPRA(Request $r)
    {
        $data = PPRADivisi::mine();
        if ($r['statusantibiotik'] == 1) {
            $data = $data->where('statusantibiotik', 1);
        } elseif ($r['statusantibiotik'] == 2) {
            $data = $data->where('statusantibiotik', 2);
        }

        $res['namadivisippra'] = $data->get();
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

    public function masterPPRADivisi(Request $r)
    {
        $data  = DB::table('ppra_divisi')
            ->select(
                'id',
                'divisi'
            )

            ->where('statusenabled', true);
            
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('pg.id', '=',  $r['id']);
        }
        
        $data = $data->orderBy('divisi', 'asc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMasterPPRAGenerik(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan

            $PSN =  $r['datanya'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new PPRADivisi(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new PPRADivisi();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->kodeexternal = (int)$this->kdProfile;
                $dataPS->namaexternal = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = PPRADivisi::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->norec =  $id;
            $dataPS->reportdisplay =  $PSN['divisi'];
            $dataPS->divisi =  $PSN['divisi'];
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

    public function deleteMasterPPRADivisi(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = PPRADivisi::where('id', $r['id'])
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

    public function masterOperasiProfilaksis(Request $r)
    {
        $data  = DB::table('ppra_jenisoperasi as pjo')
                 ->leftjoin('ppra_divisi as pd', 'pd.id', '=', 'pjo.objectdivisifk')
                 ->select('pjo.id as idpjo', 'pd.id as idpd', 'pjo.namaoperasi', 'pd.divisi')
                 ->where('pjo.statusenabled', true);
            
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        
        $data = $data->orderBy('namaoperasi', 'asc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMasterOperasiProfilaksis(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan

            $PSN =  $r['datasave'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new OperasiProfilaksis(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new OperasiProfilaksis();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->kodeexternal = (int)$this->kdProfile;
                $dataPS->namaexternal = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = OperasiProfilaksis::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->norec =  $id;
            $dataPS->reportdisplay =  $PSN['namaoperasi'];
            $dataPS->namaoperasi =  $PSN['namaoperasi'];
            $dataPS->objectantibiotikfk =  '1';
            $dataPS->objectdivisifk =  $PSN['divisi'];
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

    public function deleteMasterOperasiProfilaksis(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = OperasiProfilaksis::where('id', $r['id'])
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

    public function masterDataProfilaksis(Request $r)
    {
        $data  = DB::table('ppra_jenisoperasidetail as pjod')
        ->join('ppra_jenisoperasi as pjo', 'pjo.id', '=', 'pjod.objectjenisoperasifk')
        ->join('ppra_generik as pg', 'pg.id', '=', 'pjod.objectgenerikfk')
        ->select(
            'pjod.id as idpjod',
        'pjo.id as idpjo',
            'pg.id as idpg',
            'pjo.namaoperasi',
            'pg.namagenerik',
            'pjod.qtymax'
        )
        ->where('pg.statusenabled', true)
        ->where('pjod.statusenabled', true)
        ->where('pjo.statusenabled', true);
            
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('pjod.id', '=',  $r['id']);
        }
        
        $data = $data->orderBy('pjo.namaoperasi', 'asc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMasterDataOperasiProfilaksis(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan

            $PSN =  $r['datasave'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new OperasiProfilaksisDetail(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new OperasiProfilaksisDetail();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->kodeexternal = (int)$this->kdProfile;
                $dataPS->namaexternal = 'ASTOR-BM';
                $dataPS->statusenabled = true;
            } else {
                $dataPS = OperasiProfilaksisDetail::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->norec =  $id;
            $dataPS->reportdisplay =  'Profilaksis';
            $dataPS->objectjenisoperasifk =  $PSN['objectjenisoperasifk'];
            $dataPS->objectgenerikfk =  $PSN['objectjenisgenerikfk'];
            $dataPS->qtymax =  $PSN['qtymax'];
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

    public function deleteMasterDataOperasiProfilaksis(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = OperasiProfilaksisDetail::where('id', $r['id'])
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

    public function masterTindakanEmpiris(Request $r)
    {
        $data  = DB::table('ppra_tindakan as pt')
                 ->leftjoin('ppra_divisi as pd', 'pd.id', '=', 'pt.objectppradivisifk')
                 ->select('pt.id as idpt', 'pd.id as idpd', 'pt.namatindakan', 'pd.divisi')
                 ->where('pt.statusenabled', true);
            
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        
        $data = $data->orderBy('namatindakan', 'asc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMasterTindakanEmpiris(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan

            $PSN =  $r['datasave'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new TindakanEmpiris(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new TindakanEmpiris();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->kodeexternal = (int)$this->kdProfile;
                $dataPS->namaexternal = 'ASTOR-BM';
                $dataPS->statusenabled = true;
            } else {
                $dataPS = TindakanEmpiris::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->norec =  $id;
            $dataPS->reportdisplay =  $PSN['namatindakan'];
            $dataPS->namatindakan =  $PSN['namatindakan'];
            $dataPS->tipe =  'Empiris';
            $dataPS->objectppradivisifk =  $PSN['divisi'];
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

    public function deleteMasterTindakanEmpiris(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = TindakanEmpiris::where('id', $r['id'])
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

    public function masterDataEmpiris(Request $r)
    {
        $data  = DB::table('ppra_tindakandetail as ptd')
        ->join('ppra_tindakan as pt', 'pt.id', '=', 'ptd.objecttindakanfk')
        ->join('ppra_generik as pg', 'pg.id', '=', 'ptd.objectgenerikfk')
        ->select(
            'ptd.id as idptd',
            'pt.id as idpt',
            'pg.id as idpg',
            'pt.namatindakan',
            'pg.namagenerik',
            'ptd.qtymax'
        )
        ->where('ptd.statusenabled', '=', 't');
            
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('pjod.id', '=',  $r['id']);
        }
        
        $data = $data->orderBy('pt.namatindakan', 'asc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMasterDataTindakanEmpiris(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan

            $PSN =  $r['datasave'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new TindakanEmpirisDetail(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new TindakanEmpirisDetail();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->kodeexternal = (int)$this->kdProfile;
                $dataPS->namaexternal = 'ASTOR-BM';
                $dataPS->statusenabled = true;
            } else {
                $dataPS = TindakanEmpirisDetail::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->norec =  $id;
            $dataPS->reportdisplay =  'Empiris';
            $dataPS->objecttindakanfk =  $PSN['objecttindakanfk'];
            $dataPS->objectgenerikfk =  $PSN['objectjenisgenerikfk'];
            $dataPS->qtymax =  $PSN['qtymax'];
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

    public function deleteMasterDataTindakanEmpiris(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = TindakanEmpirisDetail::where('id', $r['id'])
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
