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
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;
use Mockery\Undefined;

class MasterTargetIndikatorCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getTargetIndikator(Request $request)
    {

        $data = DB::table('targetindikator_m as tg')
                  ->join('indikatorrensar_m as ids', 'tg.indikatorrensarfk', 'ids.id')
                  ->leftJoin('jenisindikator_m as jd','jd.id','ids.jenisindikatorfk')
                  ->select('tg.id','tg.statusenabled','ids.indikator', 'tg.tahun', 'tg.target','tg.keterangan','ids.pic',
                    'tg.indikatorrensarfk','ids.jenisindikatorfk','jd.jenisindikator')
                  ->where('tg.kdprofile',$this->kdProfile)
                  ->where('tg.statusenabled', $request['statusenabled'])
                  ->get();

        return $this->respond($data);
    }

    public function saveTargetIndikator(Request $request)
    {

        DB::beginTransaction();
        try {
            $newID = TargetIndikator::max('id') + 1;
            if ($request['id'] == '') {
                $TG = new TargetIndikator();
                $TG->id = $newID;
                $TG->kdprofile = $this->kdProfile;
                $TG->statusenabled = true;
            } else {
                $TG = TargetIndikator::where('id', $request['id'])->first();
                $TG->statusenabled = $request['statusenabled'];
            }
            $TG->indikatorrensarfk = $request['indikatorfk'];
            $TG->tahun = $request['tahun'];
            $TG->target = $request['target'];
            $TG->jenisindikatorfk = $request['jenisIndikatorfk'];
            $TG->save();

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => 'Berhasil Simpan Data',
                'result' => $TG,
                'as' => 'setiawan',
            );

        } catch (Exception $e) {

            DB::rollBack();
            $result = array(
                'status' => 400,
                'message' => 'Gagal Simpan Data',
                'result' => $e->getMessage(),
                'as' => 'setiawan',
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

        TargetIndikator::where('id',$request['id'])->update(['statusenabled'=>false]);

        return $this->respond('',200,'Berhasil Hapus Data');
    }
}
