<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Mockery\Exception\InvalidOrderException;

class MasterTandaTanganCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {

        $data =  DB::connection('mongodb')
                    ->table('TandaTangan')
                    ->where('statusenabled', true)
                    ->where('kdprofile', (int) $this->kdProfile)
                    ->get();

        return $this->respond($data);
    }

    public function save(Request $r)
    {
        DB::beginTransaction();
        try{
            if ($r->input('id') == '') {
                $data['id'] = Uuid::uuid4()->toString();
                $data['pegawaifk'] = $r['pegawaifk'];
                $data['namaLengkap'] = $r['namaLengkap'];
                $data['ttd'] = $r['ttd'];
                $data['created_at'] =  date('Y-m-d H:i:s');
                $data['statusenabled'] = true;
                $data['kdprofile'] = $this->kdProfile;
                $data['updated_at'] =  null;
                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->insert($data);
                DB::commit();
            } else {
                // return 'cek';
                $data['updated_at'] = date('Y-m-d H:i:s');
                $update = DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->where('id', $r->input('id'));
                $data['id']  =  $r->input('id');
                $update =  $update->update($data);
            }
            $transMessage = "Sukses ";
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        }catch(Exception $e){
            DB::rollback();
            $transMessage = "Simpan Gagal";
            $result = array(
                "status" => 400,
                "result" => array(

                    "as" => '@epic',
                ),
            );

        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
               $test= DB::connection('mongodb')
                    ->table('TandaTangan')
                    ->where('id', $r['id'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
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

}
