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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DaftarTagihanNonLayananCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function daftarTagihanNonLayanan(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $list = explode(',', $this->settingFix('kelompokTransaksiNonPelayanan', $kdProfile));
        $data = DB::table('strukpelayanan_t as sp')
            ->join('kelompoktransaksi_m as kt', 'kt.id', '=', 'sp.objectkelompoktransaksifk')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'sp.objectkelompokpasienfk')
            ->leftJoin('rekanan_m as rk', 'rk.id', '=', 'sp.objectrekananfk')
            ->select(
                'sp.norec',
                'sp.nostruk',
                'sp.tglstruk',
                'sp.namapasien_klien',
                'sp.noteleponfaks',
                'kt.kelompoktransaksi as jenistagihan',
                'sp.keteranganlainnya',
                'sp.nosbklastfk',
                'sp.nosbmlastfk',
                'sp.totalharusdibayar',
                'rk.namarekanan',
                'kp.kelompokpasien',
                'sp.noregistrasi',
                'ps.nocm',
                
            )
            ->where('sp.statusenabled', true)
            ->whereNotNull('sp.totalharusdibayar')
            ->where('sp.kdprofile', $kdProfile)
            ->whereIn('sp.objectkelompoktransaksifk', $list);

            $filter = false;

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $filter = true;
            $data = $data->where('sp.tglstruk', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $filter = true;
            $data = $data->where('sp.tglstruk', '<=', $r['sampai'] . ' 23:59');
        }

        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('sp.namapasien_klien', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('kt.kelompoktransaksi', 'ilike', $searchTerm);
            });
        }


        $total = $data->get();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }

        $data = $data->get();
        foreach ($data as $d) {
            $d->totalharusdibayar = round($d->totalharusdibayar);
            $d->statusbayar = "Belum Bayar";
            if ($d->nosbmlastfk != null || $d->nosbklastfk != null) {
                $d->statusbayar = "Lunas";
            }
        }
        $result['data'] = $data;
        $result['count'] = count($total);
        $result['kelompokTransaksiNonPelayanan'] = $list;

        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function nomialTagihan(Request $r)
    {

        $dateRange = [$r->dari, $r->sampai];

        $dataTagihan = StrukPelayanan::select(DB::raw("SUM(totalharusdibayar) as totaltagihan"))
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->whereNotNull('totalharusdibayar')
            ->whereIn('objectkelompoktransaksifk', explode(',', $this->settingFix('kelompokTransaksiNonPelayanan')))
            ->whereBetween(DB::raw("CAST(tglstruk as date)"), $dateRange)
            ->where('nosbmlastfk',null)
            ->first();

        $dataDibayar = StrukBuktiPenerimaan::select(DB::raw("SUM(totaldibayar) as totaldibayar"))
            ->where('objectkelompoktransaksifk', $this->settingFix('pembayaranTagihanNonLayanan'))
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(tglsbm as date)"), $dateRange)
            ->first();

        $response = [
            'tagihan' => $dataTagihan,
            'dibayar' => $dataDibayar
        ];

        return $this->respond($response);
    }
    public function hapusNonLayanan(Request $r)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;

            $SP = StrukPelayanan::where('norec', $r['norec'])
                ->where('kdprofile', $kdProfile)->first();
            $namaLog = 'Hapus Tagihan Non Layanan ';
            $this->LOGGING(
                'Tagihan Non Layanan',
                $SP->norec,
                'strukpelayanan_t',
                $namaLog . ' pada Pasien '
                    .   $SP->namapasien_klien
                    . ' (' . $SP->noteleponfaks . ') - '
                    .  $SP->nostruk
            );
            $SP->statusenabled = false;
            $SP->save();

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "norec"  => $SP->norec,
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
