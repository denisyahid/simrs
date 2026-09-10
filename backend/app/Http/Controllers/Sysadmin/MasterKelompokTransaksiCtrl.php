<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokTransaksi;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterKelompokTransaksiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterKelompokTransaksi(Request $r)
    {
        $data  = DB::table('kelompoktransaksi_m as kt')
            ->leftjoin('chartofaccount_m as coa', 'coa.id', '=', 'kt.coadebetfk')
            ->leftjoin('chartofaccount_m as coa2', 'coa2.id', '=', 'kt.coakreditfk')
            ->select(
                'kt.id',
                'kt.statusenabled',
                'kt.kelompoktransaksi',
                'kt.coakreditfk',
                'kt.coadebetfk',
            )
            ->where('kt.kdprofile', $this->kdProfile);
        if (isset($r['kt.id']) && $r['kt.id'] != '') {
            $data = $data->where('kt.id', '=',  $r['kt.id']);
        }
        if (isset($r['kt.kelompoktransaksi']) && $r['kt.kelompoktransaksi'] != '') {
            $data = $data->where('kt.kelompoktransaksi', 'ilike', '%' . $r['kt.kelompoktransaksi'] . '%');
        }
        if (isset($r['kt.statusenabled']) && $r['kt.statusenabled'] != '' && $r['kt.statusenabled'] == 'true') {
            $data = $data->where('kt.statusenabled', '=', $r['kt.statusenabled']);
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }

        $data = $data->orderByDesc('kt.kelompoktransaksi');
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

    public function savekelompoktransaksi(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save kelompoktransaksi

            $PSN =  $r['kelompoktransaksi'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new KelompokTransaksi(), 'id', $this->kdProfile); //$this->Uuid4();
                $dataPS = new KelompokTransaksi();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = KelompokTransaksi::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
            }
            $dataPS->kelompoktransaksi =  $PSN['kelompoktransaksi'];
            // $dataPS->namaexternal =  $PSN['kelompoktransaksi'];
            // $dataPS->reportdisplay =  $PSN['kelompoktransaksi'];
            $dataPS->iscostinout =  $PSN['iscostinout'];

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
    public function deleteKelompokTransaksi(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = KelompokTransaksi::where('id', $r['id'])
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
