<?php

namespace App\Http\Controllers\Remunerasi;

use App\Http\Controllers\Controller;
use App\Models\Master\GolonganPegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Pendidikan;
use App\Models\Master\Ruangan;
use App\Models\Master\UnitKerjaPegawai;
use App\Models\Transaksi\PotonganRemun;
use App\Models\Transaksi\StrukPagu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class PotonganRemunCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getData()
    {
        $data = DB::table('potonganremun_t as pr')
            ->join('pegawai_m as pg', 'pg.id', 'pr.objectpegawaifk')
            ->join('jenispagu_t as jp', 'jp.id', 'pr.objectjenispagufk')
            ->select('pr.id', 'pg.namalengkap', 'pr.objectpegawaifk', 'jp.jenispagu', 'pr.potpersen', 'pr.remunfixed', 'pr.objectjenispagufk', 'pr.objectdetailjenispagufk')
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->get();

        return $this->respond($data);
    }

    public function savePotonganRemun(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['id'] == '') {
                $newId = PotonganRemun::max('id');
                $newId = $newId + 1;
                $dataSC = new PotonganRemun();
                $dataSC->id = $newId;
                $dataSC->norec = $dataSC->generateNewId();
                $dataSC->kdprofile = $this->kdProfile;
                $dataSC->statusenabled = true;
                $message = 'Simpan Potongan Remun Berhasil';
            } else {
                $message = "Edit Potongan Remun Berhasil";
                $dataSC = PotonganRemun::where('id', $request['id'])->first();
            }

            $dataSC->objectpegawaifk = $request['objectpegawaifk'];
            $dataSC->potpersen = $request['potpersen'];
            $dataSC->remunfixed = $request['remunfixed'];
            $dataSC->objectjenispagufk = $request['objectjenispagufk'];
            $dataSC->objectdetailjenispagufk = $request['objectdetailjenispagufk'];
            $dataSC->save();
            DB::commit();
            $result = [
                'message' => $message,
                'status' => 201,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal !',
                'status' => 400,
                "result" => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function deletePotongan(Request $request)
    {

        DB::beginTransaction();
        try {

            PotonganRemun::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                ->where('id', $request['id'])->update(['statusenabled' => false]);

            DB::commit();

            $result = [
                'message' => 'Hapus Potongan Berhasil',
                'status' => 200,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $result = [
                'message' => 'Simpan Gagal !',
                'status' => 400,
                'result' => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
}
