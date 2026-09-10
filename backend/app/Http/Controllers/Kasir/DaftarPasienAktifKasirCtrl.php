<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokTransaksi;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukPelayanan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DaftarPasienAktifKasirCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function daftarPasienAktif(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
               'pd.norec as norec_pd',
               'pd.noregistrasi',
               'pd.tglregistrasi',
               'pd.tglpulang',
               'ps.nocm',
               'pd.nocmfk',
               'ps.namapasien',
               'kp.kelompokpasien',
               'ru.namaruangan'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile);

        if (
            isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined"
            && isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined"
        ) {
            $data =  $data->whereRaw("(
                pd.tglregistrasi between '$r[dari] 00:00' and '$r[sampai] 23:59'
                or pd.tglpulang is null
            )");
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['jenis']) && $r['jenis'] != "" && $r['jenis'] != "undefined") {
            if($r['jenis'] == 'Rawat Inap'){
                $data = $data->whereNull('pd.tglpulang');
            }else{
                $data = $data->whereNotNull('pd.tglpulang');
            }
        }
        // if (isset($r['filter']) && $r['filter'] != "" && $r['filter'] != "undefined") {
        //     $data =  $data->whereRaw("(
        //         pd.noregistrasi ilike '%$r[filter]%'
        //         or ps.namapasien ilike '%$r[filter]%'
        //         or ps.nocm ilike '%$r[filter]%'
        //     )");
        // }

        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $total = $data->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }

        $data = $data->get();

        $result['data'] = $data;
        $result['count'] = $total;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function detailDeposit(Request $r)
    {
        $data = DB::table('strukpelayanan_t as sp')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sbm.objectpegawaipenerimafk')
            ->select('pg.namalengkap as kasir','sp.nostruk','sp.tglstruk','sbm.totaldibayar',
            'sbm.tglsbm','sbm.nosbm','pd.noregistrasi','pd.norec as norec_pd','sp.nosbklastfk',
             DB::raw("'penerimaan' as jenis"))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->where('pd.noregistrasi',  $r['noregistrasi'])
            ->where('sp.objectkelompoktransaksifk',   $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN'))
            ->get();

            $data2 = DB::table('strukpelayanan_t as sp')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('strukbuktipengeluaran_t as sbm', 'sbm.norec', '=', 'sp.nosbklastfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sbm.objectpegawaipenerimafk')
            ->select('pg.namalengkap as kasir','sp.nostruk','sp.tglstruk','sbm.totaldibayar',
            'sbm.tglsbk as tglsbm','sbm.nosbk as nosbm','pd.noregistrasi','pd.norec as norec_pd','sp.nosbklastfk',
                DB::raw("'pengeluaran' as jenis"))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->where('pd.noregistrasi',  $r['noregistrasi'])
            ->where('sbm.objectkelompoktransaksifk',   $this->kelompokTransaksi('PENGEMBALIAN DEPOSIT PASIEN'))
            ->get();
            
        $arr = array_merge($data->toArray(),$data2->toArray());
        return $this->respond($arr);
    }

}
