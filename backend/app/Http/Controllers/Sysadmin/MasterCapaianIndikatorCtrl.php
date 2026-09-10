<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use App\Models\Master\StatusKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\IndikatorRensar;
use App\Models\Master\JenisIndikator;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\TargetIndikator;
use App\Models\Transaksi\IndikatorRensarDetail;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;
use Mockery\Undefined;

class MasterCapaianIndikatorCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getCapaianIndikator(Request $request)
    {

        $data = DB::table('indikatorrensardetail_t as ird')
                  ->join('indikatorrensar_m as ir', 'ir.id', 'ird.indikatorfk')
                  ->leftJoin('jenisindikator_m as jd','jd.id', 'ird.jenisindikatorfk')
                  ->select('ird.norec','ird.bulan','ird.capaian','ird.denumerator','ird.numerator','ird.target',
                          'ird.tahun','ird.tgl','ird.jenisindikatorfk','ird.indikatorfk','ir.pic','ir.indikator',
                          'jd.jenisindikator')
                  ->where('ird.kdprofile',$this->kdProfile)
                  ->where('ird.statusenabled',true);
        if (isset($request['indikator']) && $request['indikator'] != '' && $request['indikator'] != "undefined") {
            $data = $data->where('ir.id', $request['indikator'] );
        }
        if (isset($request['jenisindikator']) && $request['jenisindikator'] != '' && $request['jenisindikator'] != "undefined") {
            $data = $data->where('ird.jenisindikatorfk', $request['jenisindikator']);
        }
        if (isset($request['tahun']) && $request['tahun'] != '') {
            $data = $data->where('ird.tahun', $request['jenisindikator']);
        }
        $data = $data->get();

        return $this->respond($data);
    }

    public function saveIndikatorRensar(Request $request)
    {

        DB::beginTransaction();
        try {
            if ($request['norec'] == '') {
                $TP = new IndikatorRensarDetail();
                $TP->kdprofile = $this->kdProfile;
                $TP->norec = $TP->generateNewId();
                $TP->statusenabled = true;
            } else {
                $TP = IndikatorRensarDetail::where('norec', $request['norec'])->first();
            }

            $TP->bulan = $request['bulan'];
            $TP->jenisindikatorfk = $request['jenisindikatorfk'];
            $TP->indikatorfk = $request['indikatorfk'];
            $TP->capaian = $request['capaian'];
            $TP->target = $request['target'];
            $TP->denumerator = $request['denumerator'];
            $TP->numerator = $request['numerator'];
            $TP->tahun = $request['tahun'];
            $TP->tgl = $request['tgl'];
            $TP->save();
            // return $TP;
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Berhasil Simpan Data",
                'result' => $TP,
                'as' => 'inhuman',
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => 'Gagal Simpan Data',
                'data' => $e->getMessage(),
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getDataCombo(Request $request)
    {
        
        $result['indikator'] = IndikatorRensar::mine()->get();
        $result['jenisIndikator'] = JenisIndikator::mine()->get();   
        
        return $this->respond($result);
    }

    public function delete(Request $request){

        IndikatorRensarDetail::where('norec',$request['norec'])->update(['statusenabled'=>false]);

        return $this->respond('',200,'Berhasil Hapus Data');
    }
}
