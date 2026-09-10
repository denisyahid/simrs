<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Translation\t;

class MasterRuanganCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterRuangan(Request $r)
    {
        $data  = DB::table('ruangan_m as ru')
            ->join('departemen_m as dp', 'ru.objectdepartemenfk', '=', 'dp.id')
            ->select(
                'ru.id',
                'ru.statusenabled',
                'ru.namaruangan',
                'dp.namadepartemen',
                'ru.noruangan',
                'ru.icons',
                'ru.kdsubspesialisbpjs',
                'ru.namasubspesialisbpjs',
                'ru.kdspesialisbpjs',
                'ru.namaspesialisbpjs',
                'ru.objectdepartemenfk',
                'ru.ihs_id'
            )
            ->where('ru.kdprofile', $this->kdProfile);
        if ($r['ruangan']) {
            $data->where('ru.namaruangan', $r['ruangan']);
        }
        if ($r['objectdepartemenfk']) {
            $data->where('ru.objectdepartemenfk', $r['objectdepartemenfk']);
        }
        if ($r['statusenabled']) {
            $data->where('ru.statusenabled', $r['statusenabled']);
        }
        if (isset($r['ihs_id']) && $r['ihs_id'] != '') {
            $data = $data->whereNotNull('ru.ihs_id');
        }
        $data = $data->orderByDesc('ru.id', 'desc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function masterRuangandropdown(Request $request)
    {
        $res['namadepartemen'] = Departemen::mine()->get();
        return $this->respond($res);
    }
    public function saveRuangan(Request $r)
    {
        DB::beginTransaction();
        try {
            $PSN =  $r['ruangan'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new Ruangan(), 'id', $this->kdProfile); //$this->Uuid4();
                $dataPS = new Ruangan();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $dataPS = Ruangan::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
                $transMessage = "Proses Update Data Berhasil";
            }
            $dataPS->namaruangan =  $PSN['namaruangan'];
            $dataPS->reportdisplay =  $PSN['namaruangan'];
            $dataPS->namaexternal =  $PSN['namaruangan'];
            $dataPS->objectdepartemenfk =  $PSN['objectdepartemenfk'];
            $dataPS->kdsubspesialisbpjs = $PSN['kdsubspesialisbpjs'];
            $dataPS->namasubspesialisbpjs = $PSN['namasubspesialisbpjs'];
            $dataPS->kdspesialisbpjs = $PSN['kdspesialisbpjs'];
            $dataPS->namaspesialisbpjs = $PSN['namaspesialisbpjs'];
            $dataPS->icons = $PSN['icons'] ? $PSN['icons'] : null;
            $dataPS->noruangan = $PSN['noruangan'] ? $PSN['noruangan'] : null;
            $dataPS->ihs_id = isset($PSN['ihs_id']) ? $PSN['ihs_id'] : null;
            $dataPS->save();

            DB::commit();
            $result = [
                'status' => 201,
                'message' => $transMessage,
                'result' => $dataPS,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => "Simpan Gagal !",
                'result' => $e->getMessage(),
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function deleteRuangan(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Ruangan::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();

            $result = [
                'status' => 201,
                'message' => 'Proses Hapus Data Berhasil',
                'result' => $dataPS,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 400,
                'message' => 'Simpan Gagal !',
                'result' => $e->getMessage(),
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function updateRuangan(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = Ruangan::where('id', $r['id'])->update([
                'ihs_id' => $r['ihs_id']
            ]);

            DB::commit();
            $result = [
                'status' => 201,
                'message' => 'Sukses',
                'result' => $dataPS,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => "Simpan Gagal !",
                'result' => $e->getMessage(),
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
