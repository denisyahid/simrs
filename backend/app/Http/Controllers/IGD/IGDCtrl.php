<?php

namespace App\Http\Controllers\IGD;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\EMRPasien;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class IGDCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    // public function dataPasienLama(Request $r)
    // {
    //     $data  = DB::table('pasien_m as ps')
    //         ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
    //         ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
    //         ->select(
    //             'ps.id',
    //             'ps.namapasien',
    //             'ps.nocm',
    //             'ps.noidentitas',
    //             'ps.nohp',
    //             'ps.nobpjs',
    //             'jk.jeniskelamin',
    //             'alm.alamatlengkap',
    //             'ps.tempatlahir',
    //             'ps.tgllahir',
    //             'ps.tglmeninggal',
    //             'ps.ihs_number',
    //             'ps.namaibu',
    //             'ps.progress',
    //             'ps.isfoto',
    //             'ps.filename',
    //             'ps.isbayi'
    //         )
    //         ->where('ps.statusenabled', true)
    //         ->where('ps.kdprofile', $this->kdProfile);
    //     if (isset($r['id']) && $r['id'] != '') {
    //         $data = $data->where('ps.id', '=',  $r['id']);
    //     }
    //      $filter = false;
    //     if (isset($r['q']) && $r['q'] != '') {
    //         $filter = true;
    //         $data = $data->where('ps.namapasien', 'ilike', '%' . $r['q'] . '%');
    //     }

    //     if (isset($r['namapasien']) && $r['namapasien'] != '') {
    //         $filter = true;
    //         $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
    //     }
    //     if (isset($r['nocm']) && $r['nocm'] != '') {
    //         $filter = true;
    //         $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
    //     }
    //     if (isset($r['nik']) && $r['nik'] != '') {
    //         $filter = true;
    //         $data = $data->where('ps.noidentitas', '=',  $r['nik']);
    //     }
    //     if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
    //         $filter = true;
    //         $data = $data->where('ps.nobpjs', '=',  $r['nobpjs']);
    //     }
    //     if (isset($r['bpjs']) && $r['bpjs'] != '') {
    //         $filter = true;
    //         $data = $data->where('ps.nobpjs', '=',  $r['bpjs']);
    //     }
    //     if (isset($r['alamat']) && $r['alamat'] != '') {
    //         $filter = true;
    //         $data = $data->where('alm.alamatlengkap', '=',  $r['alamat']);
    //     }
    //     if (isset($r['tgllahir']) && $r['tgllahir'] != '') {
    //         $filter = true;
    //         $data = $data->where('ps.tgllahir', '=',  $r['tgllahir']);
    //     }
    //     if (isset($r['isbayi']) && $r['isbayi'] != '' && $r['isbayi'] == "true") {
    //         $data = $data->where('ps.isbayi', '=', $r['isbayi']);
    //         $filter = true;
    //     }

    //     $page = 1;
    //     if (isset($r['page']) && $r['page'] != '' && $filter == false) {
    //         $page = $r['page'];
    //     }

    //     $data = $data->orderByDesc('ps.nocm');
    //     // $data = $data->get();
    //     $data = $data ->paginate(isset($r['limit'])?$r['limit']: 10, ['*'], 'page', $page);
    //     // dd(DB::getQueryLog());

    //     return $this->respond($data);
    // }
        public function dataPasienLama (Request $r)
        {
            $rangeDate = [$r->dari, $r->sampai];
            $data  = DB::table('emrpasien_t as ep')
            ->leftjoin('pegawai_m as pg', 'pg.id','=','ep.pegawaifk')
            ->leftjoin('pasien_m as ps', 'ps.nocm','=', 'ep.nocm')
            ->select(
                'ep.namapasien',
                'ep.jeniskelamin',
                'ep.alamat',
                'ep.tgllahir',
                'ep.alamat',
                'ep.noemr',
                'pg.namalengkap as dokter',
                'ep.isverif',
                'ep.tglemr',
                'ep.norec',
                'ps.id as nocmfk',
                'ep.nocm'
            )
            ->whereNull('ep.noregistrasi')
            ->whereNotNull('ep.namapasien')
            ->whereBetween(DB::raw("ep.tglemr::date"),$rangeDate)
            ->where('ep.statusenabled', true)
            ->where('ep.kdprofile', $this->kdProfile);

            // if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            //     $data = $data->where('ep.tglemr', '>=', $r['dari'] . ' 00:00:00');
            // }
            if (isset($r['search']) && $r['search'] != "" && $r['search'] != "undefined") {
                $searchTerm = '%' . $r['search'] . '%';
                $data = $data->where(function ($query) use ($searchTerm) {
                    $query->where('ep.namapasien', 'ilike', $searchTerm)
                        ->orWhere('ep.noemr', 'ilike', $searchTerm)
                        ->orWhere('ep.alamat', 'ilike', $searchTerm);
                });
            }
    
            // if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            //     $data = $data->where('ep.tglemr', '<=',  $r['sampai'] . ' 23:59:59');
            // }
            // if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            //     $data = $data->where('ep.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
            // }

            $data = $data->orderByDesc('ep.tglemr')->get();

        return $this->respond($data);

    }

        
    public function UpdateTriage(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = EMRPasien::where('norec', $r['norec'])->update(['isverif' => $r['isverif']]);
            DB::commit();

            $result = [
                "statusCode" => 200,
                "message" => "Data Berhasil Di verifikasi",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "messageCode" => $e->getMessage(),
                "message" => "Simpan Gagal !",
            ];
        }

        return $this->respond($result,$result['statusCode'],$result['message']);

    }

    public function getDokterIGD(Request $r){

        $data  = DB::table('maploginusertoruangan_s as mp')
        ->join('loginuser_s as pr', 'mp.objectloginuserfk', '=', 'pr.id')
        ->join('pegawai_m as pg', 'pg.id', '=', 'pr.objectpegawaifk')
        ->join('ruangan_m as ru', 'mp.objectruanganfk', '=', 'ru.id')
        ->select(
            'pg.namalengkap',
            'pr.objectpegawaifk',
            'ru.namaruangan'
        )
            ->where('mp.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')))
            ->where('mp.kdprofile', $this->kdProfile);

            $data = $data->get();

            $res['data'] = $data;
            return $this->respond($res);


    }

}