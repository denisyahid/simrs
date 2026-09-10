<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\IndikatorIPCN;
use App\Models\Master\KelompokIPCN;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterPPICtrl extends Controller
{
    public function masterIndikator(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('kelompokipcn_m')
            ->select('id', 'kdkelompokipcn', 'kelompokipcn', 'nourut', 'statusenabled')
            ->where('kdprofile', $kdProfile);
        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined") {
            $data = $data->where('statusenabled', $request['status']);
        }
        if (isset($request['search']) && $request['search'] != "" && $request['search'] != "undefined") {
            $data = $data->where('kdkelompokipcn', 'ILIKE', '%' . $request['search'] . '%')->orWhere('id', 'ILIKE', '%' . $request['search'] . '%')->orWhere('kelompokipcn', 'ILIKE', '%' . $request['search'] . '%');
        }
        $total = $data->count();
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != "" && $request['offset'] != "undefined") {
            $data = $data->offset($request['offset']);
        }
        $data = $data->get();
        $result = [
            'data' => $data,
            'total' => $total
        ];
        return $this->respond($result);
    }
    public function saveKelompokIPCN(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['id'] == "") {
                $idJenis = KelompokIPCN::max('id');
                $idJenis = $idJenis + 1;
                $JD = new KelompokIPCN();
                $JD->id = $idJenis;
                $JD->norec = $JD->generateNewId();
                $JD->kdprofile = $kdProfile;
                $JD->statusenabled = true;
                $JD->kodeexternal = $idJenis;
            } else {
                $JD = KelompokIPCN::where('id', $request['id'])->where('kdprofile', $kdProfile)->first();
            }
            $JD->namaexternal = $request['kelompok'];
            $JD->reportdisplay = $request['kelompok'];
            $JD->kelompokipcn = $request['kelompok'];
            $JD->nourut = $request['nourut'];
            $JD->kdkelompokipcn =  $request['kode'];
            $JD->statusenabled = isset($request['statusenabled']) ? $request['statusenabled'] : true;
            $JD->save();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Kelompok IPCN";
            DB::commit();
            $result = array(
                "status" => 201,
                "as" => 'sukses@win',
                "result" => $JD
            );
        } else {
            $transMessage = "Simpan Gagal Kelompok IPCN";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "as" => 'error@win',
                "result" => $e->getMessage() . '-' . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
    }

    public function deleteKelompokIPCN(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            $JD = KelompokIPCN::where('id', $request['id'])
                ->where('kdprofile', $kdProfile)
                ->update([
                    'statusenabled' => false
                ]);
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 201,
                "jenisdiet" => $JD,
                "as" => 'success@win',
            );
        } else {
            $transMessage = "Hapus gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "as" => 'error@win',
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
    }
    public function getindikatoripcn(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('indikatoripcn_m AS ii')
            ->join('kelompokipcn_m AS ki', 'ki.id', '=', 'ii.kelompokipcnfk')
            ->join('departemen_m AS dept', 'dept.id', '=', 'ii.departemenfk')
            ->select(DB::raw("
                ki.kdkelompokipcn || '. ' || ki.kelompokipcn AS kelompok,ii.kelompokipcnfk,
                ii.id AS indikatorfk,ki.kelompokipcn,ii.id,ii.indikatoripcn,ii.departemenfk,
                dept.namadepartemen,ki.nourut,ii.statusenabled as statusenabled
            "))
            ->where('ii.kdprofile', $kdProfile);
        if (isset($request['search']) && $request['search'] != "" && $request['search'] != "undefined") {
            $data = $data->where('ki.kelompokipcn', 'ILIKE', '%' . $request['search'] . '%')
                ->orWhere('ki.kdkelompokipcn', 'ILIKE', '%' . $request['search'] . '%')
                ->orWhere('ii.indikatoripcn', 'ILIKE', '%' . $request['search'] . '%')
                ->orWhere('ii.id', 'ILIKE', '%' . $request['search'] . '%')
                ->orWhere('dept.namadepartemen', 'ILIKE', '%' . $request['search'] . '%')
                ->orWhere('ii.kelompokipcnfk', 'ILIKE', '%' . $request['search'] . '%');
        }
        $total = $data->count();
        if (isset($request['status']) && $request['status'] != "" && $request['status'] != "undefined") {
            $data = $data->where('ii.statusenabled', $request['status']);
        }
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != "" && $request['offset'] != "undefined") {
            $data = $data->offset($request['offset']);
        }
        $data = $data->get();
        $result = [
            'data' => $data,
            'total' => $total
        ];
        return $this->respond($result);
    }
    public function saveIndikatorIPCN(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        if ($request['id'] == "") {
            $idJenis = IndikatorIPCN::max('id');
            $idJenis = $idJenis + 1;
            $JD = new IndikatorIPCN();
            $JD->norec = $JD->generateNewId();
            $JD->id = $idJenis;
            $JD->kdprofile = $kdProfile;
            $JD->statusenabled = true;
            $JD->kodeexternal = $idJenis;
        } else {
            $JD = IndikatorIPCN::where('id', $request->id)->where('kdprofile', $kdProfile)->first();
        }
        DB::beginTransaction();
        try {

            $JD->namaexternal = $request->indikator;
            $JD->reportdisplay = $request->indikator;
            $JD->kelompokipcnfk = $request->kelompokfk;
            $JD->indikatoripcn = $request->indikator;
            $JD->departemenfk = $request->departemenfk;
            $JD->statusenabled = $request->statusenabled ?? true;
            $JD->save();

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Indikator IPCN";
            DB::commit();
            $result = array(
                "status" => 201,
                "jenisdiet" => $JD,
                "as" => 'success@win',
                "result" => $JD
            );
        } else {
            $transMessage = "Simpan Gagal Indikator IPCN";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "as" => 'error@win',
                "result" => $e->getMessage() . '-' . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
    }
}
