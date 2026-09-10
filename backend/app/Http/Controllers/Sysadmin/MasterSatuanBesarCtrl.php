<?php

namespace App\Http\Controllers\Sysadmin;
use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokProduk;
use App\Models\Master\SatuanBesar;
use Exception;
class MasterSatuanBesarCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
         $data = DB::table('satuanbesar_m as sb')
                ->leftJoin('departemen_m as dp', 'sb.objectdepartemenfk', '=', 'dp.id')
                ->leftJoin('kelompokproduk_m as kp', 'sb.objectkelompokprodukfk', '=', 'kp.id')
                ->select(
                    'sb.id',
                    'sb.qsatuanbesar',
                    'sb.kdsatuanbesar',
                    'sb.statusenabled',
                    'sb.namaexternal',
                    'sb.reportdisplay',
                    'sb.kodeexternal',
                    'sb.objectdepartemenfk',
                    'dp.namadepartemen',
                    'sb.objectkelompokprodukfk',
                    'kp.kelompokproduk',
                    'sb.satuanbesar',
                )
                ->where('sb.kdprofile',$this->kdProfile)
                ->orderByDesc('sb.satuanbesar');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('sb.id', '=',  $r['id']);
        }
        if (isset($r['satuanbesar']) && $r['satuanbesar'] != '') {
            $data = $data->where('sb.satuanbesar', 'ilike', '%' . $r['satuanbesar'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('sb.statusenabled', '=', $r['statusenabled']);
        }
        if (isset($r['limit']) && $r['limit'] != ''){
            $data = $data->limit($r['limit']);
        }
        if(isset($r['offset']) && $r['offset'] != ''){
            $data = $data->offset($r['offset']);
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
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = SatuanBesar::count();
            $PSN =  $request['satuanbesar'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $data = new SatuanBesar();
                $data->id = $this->SEQUENCE_MASTER(new SatuanBesar(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $data->kdsatuanbesar =  $codeUnique;
                $data->qsatuanbesar = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = SatuanBesar::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->satuanbesar =  $PSN['satuanbesar'];
            $data->objectkelompokprodukfk =  $PSN['objectkelompokprodukfk'];
            $data->objectdepartemenfk =  $PSN['objectdepartemenfk'];
            $data->namaexternal =  $PSN['satuanbesar'];
            $data->reportdisplay =  $PSN['satuanbesar'];
            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                ],
            ];

        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getLine() . ' ' . $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function dropdownItem()
    {
        $res['departemen'] = Departemen::mine()->get();
        $res['kelompokproduk'] = KelompokProduk::mine()->get();

        return $this->respond($res);
    }


    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = SatuanBesar::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                    ]
                ];

        } catch (\Exception $e) {
            DB::rollBack();

            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getLine() . ' ' . $e->getMessage()
                ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
