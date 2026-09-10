<?php

namespace App\Http\Controllers\Sysadmin;
use Faker\Factory;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokProduk;
use App\Models\Master\SatuanKecil;
use Exception;

class MasterSatuanKecilCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
         $data = DB::table('satuankecil_m as sk')
                ->leftJoin('departemen_m as dp', 'sk.objectdepartemenfk', '=', 'dp.id')
                ->leftJoin('kelompokproduk_m as kp', 'sk.objectkelompokprodukfk', '=', 'kp.id')
                ->select(
                    'sk.id',
                    'sk.qsatuankecil',
                    'sk.kdsatuankecil',
                    'sk.statusenabled',
                    'sk.namaexternal',
                    'sk.reportdisplay',
                    'sk.kodeexternal',
                    'sk.objectdepartemenfk',
                    'dp.namadepartemen',
                    'sk.objectkelompokprodukfk',
                    'kp.kelompokproduk',
                    'sk.satuankecil',
                )
                ->where('sk.kdprofile',$this->kdProfile)
                ->orderByDesc('sk.satuankecil');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('sk.id', '=',  $r['id']);
        }
        if (isset($r['satuankecil']) && $r['satuankecil'] != '') {
            $data = $data->where('sk.satuankecil', 'ilike', '%' . $r['satuankecil'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('sk.statusenabled', '=', $r['statusenabled']);
        }
        if (isset($r['total']) && $r['total'] != '') {
            $count = $data->count();
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
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
        $res['total'] = $count;
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = SatuanKecil::count();
            $PSN =  $request['satuankecil'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $data = new SatuanKecil();
                $data->id = $this->SEQUENCE_MASTER(new SatuanKecil(),'id',$this->kdProfile);///(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                $data->kdsatuankecil =  $codeUnique;
                $data->qsatuankecil = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";

            } else {
                $data = SatuanKecil::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->satuankecil =  $PSN['satuankecil'];
            $data->objectkelompokprodukfk =  $PSN['objectkelompokprodukfk'];
            $data->objectdepartemenfk =  $PSN['objectdepartemenfk'];
            $data->namaexternal =  $PSN['satuankecil'];
            $data->reportdisplay =  $PSN['satuankecil'];
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
                "result"  => $e->getMessage()

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
            $data = SatuanKecil::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => 'setiawan@epic',
                    ]
                ];

        } catch (Exception $e) {
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
