<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisLaporan;
use App\Models\Master\KelompokLaporan;

class MasterKelompokLaporanCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('kelompoklaporan_m as kl')
            ->leftJoin('jenislaporan_m as jl','jl.id','kl.idjenislaporanfk')
            ->select('kl.*','jl.jenislaporan')
            ->where('kl.kdprofile', $this->kdProfile);

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('kl.statusenabled', $r['statusenabled']);
        }

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
        // return response()->json($res);
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = KelompokLaporan::count();
            $PSN =  $request['kelompoklaporan'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new KelompokLaporan();
                $data->id = $this->SEQUENCE_MASTER(new KelompokLaporan(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $data->kdkelompoklaporan = $data->kodeexternal;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = KelompokLaporan::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->kelompoklaporan =  $PSN['kelompoklaporan'];
            $data->namaexternal =  $PSN['kelompoklaporan'];
            $data->reportdisplay =  $PSN['kelompoklaporan'];
            $data->init =  $PSN['init'];
            $data->sort =  $PSN['sort'];
            $data->idjenislaporanfk =  $PSN['jenislaporanfk'];
            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = KelompokLaporan::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ]
            ];
            $transMessage = "Proses Hapus Data Berhasil";
        } catch (\Exception $e) {
            DB::rollBack();
            $transMessage = "Something Went Wrong";
            $result = [
                "status" => 400,
                "result"  =>null
            ];
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getJenisLaporan(){

        $data = JenisLaporan::mine()->get();

        return $this->respond($data);
    }

}
