<?php

namespace App\Http\Controllers\Sysadmin;
use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JenisProduk;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\KelompokProduk;
use App\Models\Master\SatuanStandar;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterSatuanStandarCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function MasterSatuanStandar(Request $r)
    {
        $data  = DB::table('satuanstandar_m as ss')
            ->leftjoin('kelompokproduk_m as kp', 'ss.objectkelompokprodukfk', '=', 'kp.id')
            ->leftjoin('departemen_m as dp', 'ss.objectdepartemenfk', '=', 'dp.id')
            ->select(
                'ss.satuanstandar',
                'ss.statusenabled',
                'kp.kelompokproduk',
                'ss.namaexternal',
                'ss.reportdisplay',
                'ss.qsatuanstandar',
                'ss.qtykemasan',
                'ss.objectkelompokprodukfk',
                'ss.objectdepartemenfk',
                'dp.namadepartemen',
                'ss.id'
            )
            ->where('ss.kdprofile', $this->kdProfile);

            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('ss.id', '=',  $r['id']);
            }
            if (isset($r['satuanstandar']) && $r['satuanstandar'] != '') {
                $data = $data->where('ss.satuanstandar', 'ilike', '%' . $r['satuanstandar'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
                $data = $data->where('ss.statusenabled', '=', $r['statusenabled']);
            }
            if (isset($r['objectkelompokprodukfk']) && $r['objectkelompokprodukfk'] != '') {
                $data = $data->where('ss.objectkelompokprodukfk', '=', $r['objectkelompokprodukfk'] );
            }

            $data = $data->orderByDesc('ss.satuanstandar');
            $data = $data->get();

            foreach ($data as $d) {
                $d->statusenabled;
                $d->status = 'Aktif';
                $d->status_c = 'info';
                if ($d->statusenabled != 'false') {
                    $d->status = 'Nonaktif';
                    $d->status_c = 'danger';
                }

            }

            $res['data'] = $data;
            return $this->respond($res);
        }
    public function masterSatuanStandardropdown(Request $r)
    {
        $res['namadepartemen'] = Departemen::mine()->get();
        $res['kelompokproduk'] = KelompokProduk::mine()->get();

        return $this->respond($res);
    }
    public function saveSatuanStandar(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Satuan Standar

            $PSN =  $r['satuanstandar'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new SatuanStandar(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new SatuanStandar();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = SatuanStandar::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
            }
            $dataPS->satuanstandar =  $PSN['satuanstandar'];
            $dataPS->qtykemasan =  $PSN['qtykemasan'];
            $dataPS->objectkelompokprodukfk =  $PSN['objectkelompokprodukfk'];
            $dataPS->objectdepartemenfk =  $PSN['objectdepartemenfk'];
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
    public function deleteSatuanStandar(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = SatuanStandar::where('id', $r['id'])
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
