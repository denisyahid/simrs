<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokUser;
use App\Models\Master\Pasien;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception\InvalidOrderException;

class PasienLamaCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function pasienLama(Request $r)
    {
        $data  = DB::table('pasien_m as ps')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kebangsaan_m as kbg', 'kbg.id', '=', 'ps.objectkebangsaanfk')
            // ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->select(
                'ps.id',
                'ps.namapasien',
                'ps.nocm',
                'ps.noidentitas',
                'ps.nohp',
                'ps.statusemr',
                'ps.nobpjs',
                'jk.jeniskelamin',
                // 'alm.alamatlengkap',
                'ps.alamatlengkap',
                'ps.tempatlahir',
                'ps.tgllahir',
                'ps.tglmeninggal',
                'ps.ihs_number',
                'ps.namaibu',
                'ps.progress',
                'ps.isfoto',
                'ps.filename',
                'ps.isbayi',
                'ps.objectkebangsaanfk',
                'kbg.name as kebangsaan'
            )
            ->where('ps.statusenabled', true)
            ->where('ps.kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('ps.id', '=',  $r['id']);
        }
         $filter = false;
        if (isset($r['q']) && $r['q'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['q'] . '%');
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');

        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $filter = true;
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');

        }
        if (isset($r['nik']) && $r['nik'] != '') {
            $filter = true;
            $data = $data->where('ps.noidentitas', '=',  $r['nik']);
        }
        if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
            $filter = true;
            $data = $data->where('ps.nobpjs', '=',  $r['nobpjs']);
        }
        if (isset($r['bpjs']) && $r['bpjs'] != '') {
            $filter = true;
            $data = $data->where('ps.nobpjs', '=',  $r['bpjs']);
        }
        // if (isset($r['alamat']) && $r['alamat'] != '') {
        //     $filter = true;
        //     $data = $data->where('alm.alamatlengkap', '=',  $r['alamat']);
        // }
        if (isset($r['alamat']) && $r['alamat'] != '') {
            $filter = true;
            $data = $data->where('ps.alamatlengkap', '=',  $r['alamat']);
        }
        if (isset($r['tgllahir']) && $r['tgllahir'] != '') {
            $filter = true;
            $data = $data->where('ps.tgllahir', '=',  $r['tgllahir']);
        }
        if (isset($r['isbayi']) && $r['isbayi'] != '' && $r['isbayi'] == "true") {
            $data = $data->where('ps.isbayi', '=', $r['isbayi']);
            $filter = true;
        }

        $page = 1;
        if (isset($r['page']) && $r['page'] != '' && $filter == false) {
            $page = $r['page'];
        }

        $data = $data->orderByDesc('ps.nocm');
        // $data = $data->get();
        $data = $data ->paginate(isset($r['limit'])?$r['limit']: 10, ['*'], 'page', $page);
        // $data = $data->paginate(isset($r['limit'])?$r['limit']: 10);

        return $this->respond($data);
    }

    public function deletePasien(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = Pasien::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => "Hapus Data Berhasil",
                "result" => [
                    "data"  => $dataPS,
                    "as" => '@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
    public function cekPulangpasien(Request $r)
    {
        $cekRI = PasienDaftar::where('nocmfk', $r['id'])
            ->whereNull('tglpulang')
            ->where('statusenabled', true)
            ->first();

        return $this->respond($cekRI);
    }
    public function cekPiutangpasien(Request $r)
    {
        $cekRI['piutang'] = DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->whereNotNull('pd.objectstatuspiutangfk')
            ->whereNull('pd.islunaspiutang')
            ->where('ps.nocm', $r['nocm'])
            ->where('pd.statusenabled', true)
            ->first();

        $cekRI['closing'] =  DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->whereNull('pd.tglclosing')
            ->where('ps.nocm', $r['nocm'])
            ->where('pd.statusenabled', true)
            ->whereDate('pd.tglregistrasi', '<', date('Y-m-d'))
            ->first();

        return $this->respond($cekRI);
    }

    public function dropdown (){
        $result['kelompokuser'] = KelompokUser::mine()->get();

        return $this->respond($result);
    }
}