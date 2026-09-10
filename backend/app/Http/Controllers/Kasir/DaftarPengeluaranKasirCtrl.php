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
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukBuktiPengeluaranCaraBayar;
use App\Models\Transaksi\StrukPelayanan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DaftarPengeluaranKasirCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function daftarPengeluaran(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $data = DB::table('strukbuktipengeluaran_t as sbm')
            ->join('strukpelayanan_t as sp', 'sbm.nostrukfk', '=', 'sp.norec')
            ->leftjoin('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pegawai_m as p', 'p.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('strukbuktipengeluarancarabayar_t as sbmcr', 'sbmcr.nosbkfk', '=', 'sbm.norec')
            ->join('carabayar_m as cb', 'cb.id', '=', 'sbmcr.carabayarfk')
            ->join('kelompoktransaksi_m as kt', 'kt.id', '=', 'sbm.objectkelompoktransaksifk')
            ->select(
                'sbm.norec',
                'sbmcr.norec as norec_sbmcr',
                'cb.carabayar',
                'sbmcr.carabayarfk as objectcarabayarfk',
                'sbm.objectkelompoktransaksifk',
                'kt.kelompoktransaksi',
                'sbm.keteranganlainnya',
                'sbm.objectpegawaipenerimafk',
                'p.namalengkap as kasir',
                'sbm.nosbk',
                'sbm.tglsbk',
                'pd.noregistrasi',
                'sp.norec as norec_sp',
                'ps.nocm',
                'sbmcr.totaldibayar',
                DB::raw("case when sbm.noclosingfk is null then 'Belum Setor' else 'Setor' end as statussetor,
                case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien
                ")
            )
            ->where('sbm.statusenabled', true)
            ->where('sbm.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('sbm.tglsbk', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('sbm.tglsbk', '<=',  $r['sampai'] . ' 23:59');
        }
        if (isset($r['idPegawai']) && $r['idPegawai'] != "" && $r['idPegawai'] != "undefined") {
            $data = $data->where('p.id', '=', $r['idPegawai']);
        }
        if (isset($r['carabayar']) && $r['carabayar'] != "" && $r['carabayar'] != "undefined") {
            $data = $data->where('cb.id', '=', $r['carabayar']);
        }
        if (isset($r['idKelTransaksi']) && $r['idKelTransaksi'] != "" && $r['idKelTransaksi'] != "undefined") {
            $data = $data->where('kt.id', $r['idKelTransaksi']);
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk', $r['ins']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->where('ru.id', $r['ruang']);
        }
        if (isset($r['nosbm']) && $r['nosbm'] != "" && $r['nosbm'] != "undefined") {
            $data = $data->where('sbm.nosbm', 'ilike', '%' . $r['nosbm'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['desk']) && $r['desk'] != "" && $r['desk'] != "undefined") {
            $data = $data->where('sp.namapasien_klien', 'ilike', '%' . $r['desk'] . '%');
        }
        if (isset($request['kasirArr']) && $request['kasirArr'] != "" && $request['kasirArr'] != "undefined") {
            $arrRuang = explode(',', $request['kasirArr']);
            $kodeRuang = [];
            foreach ($arrRuang as $item) {
                $kodeRuang[] = (int) $item;
            }
            $data = $data->whereIn('p.id', $kodeRuang);
        }
        $total = $data->get();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }

        $data = $data->get();

        $cara = CaraBayar::mine()->get();
        $totalAll = 0;
        foreach ($total as $d) {
            $totalAll =    $totalAll +  (float) $d->totaldibayar;
            foreach ($cara as $dd) {
                if ($dd->id == $d->objectcarabayarfk) {
                    $dd->total = (float)  $dd->total + (float) $d->totaldibayar;
                }
            }
        }

        $result['data'] = $data;
        $result['count'] = count($total);
        $result['total'] = $totalAll;
        $result['carabayar'] = $cara;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function daftarpengeluaranDropdown(Request $r)
    {
        $res['carabayar'] = CaraBayar::mine()->get();
        $res['kasir'] = Pegawai::mine()->get();
        $res['departemen'] = Departemen::mine()->get()->toArray();
        $ru = Ruangan::mine()->get();
        foreach ($res['departemen']  as $k => $d) {
            $res['departemen'][$k]['ruangan'] = [];
            foreach ($ru  as $dd) {
                if ($dd->objectdepartemenfk == $d['id']) {
                    $res['departemen'][$k]['ruangan'][] = $dd;
                }
            }
        }
        $res['kelompoktransaksi'] = KelompokTransaksi::mine()->get();

        return $this->respond($res);
    }
    public function saveUbahCaraBayar(Request $r)
    {
        DB::beginTransaction();
        try {
            StrukBuktiPengeluaranCaraBayar::where('norec', $r['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(['carabayarfk' => $r['carabayar']]);

            $this->LOGGING(
                'Ubah Cara Bayar',
                $r['norec'],
                'strukbuktipengeluaran_t',
                'Ubah Cara Bayar ke ' . $r['carabayarname'] . ' pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $r['nosbm']
            );
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveBatalBayar(Request $r)
    {
        DB::beginTransaction();
        try {
            $kdProfile = (int)$this->kdProfile;
            $sbm = StrukBuktiPengeluaran::where('norec', $r['norec'])->first();


            StrukPelayanan::where('norec', $r['norec_sp'])
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'nosbklastfk'    => null,
                    ]
                );
                StrukBuktiPengeluaran::where('norec', $r['norec'])
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusenabled' => false,
                        'nostrukfk'    => null,
                    ]
                );
            // PasienDaftar::where('nostruklastfk', $r['norec_sp'])
            //     ->where('kdprofile', $kdProfile)
            //     ->update(
            //         [
            //             'nosbmlastfk'    => null,
            //         ]
            //     );

            PasienDaftar::where('noregistrasi', $sbm->noregistrasi)->update(
                [
                    'statusbayar' => 'Belum Bayar'
                ]
            );
            $this->LOGGING(
                'Batal Pengeluaran Kasir',
                $r['norec'],
                'strukbuktipengeluaran_t',
                'Batal Pengeluaran Kasir pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $r['nosbk']
            );
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage(). ' '.$e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function cetakKwitansi(Request $r)
    {
        $profile = $this->profile();
        $kdProfile = $profile->id;
        $namapegawai = $r['user'];
        $tglcetak = $r['tanggalcetak'];
        $pageWidth = 950;
        $identitas = collect(DB::select("
            SELECT
                sbp.nosbk as nokwitansi,
                sbp.totaldibayar,
                to_char(sbp.tglsbk, 'DD-MM-YYYY') as tglsbm,
                sbp.keteranganlainnya,
                sbp.objectpegawaipenerimafk,
                ps.namapasien,
                ps.nocm,
                pd.noregistrasi
            FROM
                strukbuktipengeluaran_t as sbp
                LEFT JOIN strukpelayanan_t as sp on sp.norec = sbp.nostrukfk 
                LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk 
                LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk 
                LEFT JOIN pegawai_m as pg on pg.id = sbp.objectpegawaipenerimafk
            WHERE
            sbp.norec = ? 
            and pd.statusenabled= true  
            and sbp.kdprofile=?
            ",[$r['norec'],$kdProfile]));

        if (count($identitas) == 0) {
            $identitas = collect(DB::select("
                SELECT
                    sbp.nosbk as nokwitansi,
                    sbp.totaldibayar,
                    to_char(sbp.tglsbk, 'DD-MM-YYYY') as tglsbm,
                    sbp.keteranganlainnya,
                    sbp.objectpegawaipenerimafk,
                    case when ps.namapasien is null then sp.namapasien_klien else   ps.namapasien end as namapasien,
                    case when ps.nocm is null then '-' else      ps.nocm end as nocm,
                    case when pd.noregistrasi is null then sp.nostruk else     pd.noregistrasi end as noregistrasi
                  
                FROM
                    strukbuktipengeluaran_t as sbp
                    LEFT JOIN strukpelayanan_t as sp on sp.norec = sbp.nostrukfk
                    LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                    LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk 
                    LEFT JOIN pegawai_m as pg on pg.id = sbp.objectpegawaipenerimafk
                WHERE
                sbp.norec = ? 
                and sbp.kdprofile=?
                ",[$r['norec'],$kdProfile]));
        }
        $terbilang = $this->makeTerbilang($identitas[0]->totaldibayar);
        $res['terbilang'] = $terbilang;

        return view(
            'report.kasir.kwitansi',
            compact('identitas', 'namapegawai', 'tglcetak', 'terbilang', 'pageWidth', 'r', 'profile')
        );
    }
}
