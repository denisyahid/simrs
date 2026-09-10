<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisKondisiPasien;
use App\Models\Master\KondisiPasien;
use Mockery\Exception\InvalidOrderException;

class MasterKondisiPasienCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('kondisipasien_m as kp')
            ->join('jeniskondisipasien_m as jkp', 'kp.objectjeniskondisipasienfk', '=', 'jkp.id')
            ->select(
                'kp.id',
                'kp.qkondisipasien',
                'kp.statusenabled',
                'kp.namaexternal',
                'kp.reportdisplay',
                'kp.kodeexternal',
                'kp.objectjeniskondisipasienfk',
                'jkp.jeniskondisipasien',
                'kp.kondisipasien',
            )
            ->where('kp.kdprofile', $this->kdProfile)
            ->orderByDesc('kp.kondisipasien');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('kp.id', '=',  $request['id']);
        }
        if (isset($request['kondisipasien']) && $request['kondisipasien'] != '') {
            $data = $data->where('kp.kondisipasien', 'ilike', '%' . $request['kondisipasien'] . '%');
        }
        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('kp.statusenabled', '=', $request['statusenabled']);
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
        return $this->respond($res);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = KondisiPasien::count();
            // $PSN =  $request['kondisipasien'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($request['id'] == '') {
                $kondPS = new KondisiPasien();
                $kondPS->id = $this->SEQUENCE_MASTER(new KondisiPasien(),'id',$this->kdProfile);// (string)Uuid::uuid4();
                $kondPS->kdkondisipasien =  $codeUnique;
                $kondPS->qkondisipasien = $codeUnique;
                $kondPS->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $kondPS->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil !";
            } else {
                $kondPS = KondisiPasien::where('id', $request['id'])->first();
                $kondPS->statusenabled = $request['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $kondPS->id;
            }
            $kondPS->kdprofile = (int)$this->kdProfile;
            $kondPS->kondisipasien =  $request['kondisipasien'];
            $kondPS->namaexternal =  $request['kondisipasien'];
            $kondPS->reportdisplay =  $request['kondisipasien'];
            $kondPS->objectjeniskondisipasienfk =  $request['objectjeniskondisipasienfk'];
            $kondPS->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $kondPS,
                    "as" => 'setiawan@epic',
                ],
            ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    // public function detail($id)
    // {
    //     try {
    //         $data = DB::table('kondisipasien_m as kp')
    //             ->join('jeniskondisipasien_m as jkp', 'kp.objectjeniskondisipasienfk', '=', 'jkp.id')
    //             ->where('kp.kdprofile', $this->kdProfile)
    //             ->where('kp.id', $id)
    //             ->select(
    //                 'kp.id',
    //                 'jkp.jeniskondisipasien',
    //                 'kp.namaexternal',
    //                 'kp.reportdisplay',
    //                 'kp.norec',
    //                 'kp.kondisipasien',
    //                 'kp.statusenabled',
    //                 'kp.kdkondisipasien',
    //                 'kp.qkondisipasien',
    //             )->get();
    //         if ($data) {
    //             $respond = [
    //                 "status" => 200,
    //                 "result" => [
    //                     "data"  => $data,
    //                     "as" => 'masbro@jos',
    //                 ]
    //             ];
    //             return $this->respond($respond['result'], $respond['status'], 'Fetch Data Successfully');
    //         } else {
    //             $result = [
    //                 "status" => 404,
    //                 "result"  => [
    //                     'message' => 'Request data tidak ditemukan'
    //                 ],
    //             ];
    //             return $this->respond($result['result'], $result['status'], $result['message']);
    //         }
    //     } catch (Exception $e) {
    //         $result = [
    //             "status" => 400,
    //             "result"  =>null,
    //         ];

    //         return $this->respond($result['result'], $result['status'], 'Something Went Wrong');
    //     }
    // }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $kondisiPas = KondisiPasien::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $kondisiPas,
                    "as" => 'setiawan@epic',
                ],
            ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()
            ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function jenisKondisiPasien()
    {
        $res['jeniskondisipasien'] = JenisKondisiPasien::mine()->get();

        return $this->respond($res);
    }
}
