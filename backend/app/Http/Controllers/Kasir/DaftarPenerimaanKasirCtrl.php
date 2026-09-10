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
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use GoogleTranslate;

class DaftarPenerimaanKasirCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function daftarPenerimaan(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $tglAwal = $r['dari'];
        $tglAkhir = $r['sampai'];
        $datanonlayanan = DB::select(DB::raw("SELECT
            sp.tglstruk, sp.namapasien_klien, sp.norec, sp.nostruk, sp.noteleponfaks, 
            kt.kelompoktransaksi AS jenistagihan, sp.keteranganlainnya, sp.nosbklastfk, sp.nosbmlastfk, 
            sp.totalharusdibayar, rk.namarekanan, kp.kelompokpasien 
        FROM
            strukpelayanan_t AS sp
            INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sp.objectkelompoktransaksifk
            LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.id = sp.objectkelompokpasienfk
            LEFT JOIN rekanan_m AS rk ON rk.id = sp.objectrekananfk 
        WHERE
            sp.statusenabled = true
            AND sp.totalharusdibayar IS NOT NULL 
            AND sp.kdprofile = 1 
            AND sp.objectkelompoktransaksifk IN ( 4, 1, 5, 99, 433 ) 
            AND sp.tglstruk >= '$tglAwal' 
            AND sp.tglstruk <= '$tglAkhir'"));

        $datadeposit = DB::select(DB::raw("SELECT
            pas.namapasien, 
            case when pas.objectkebangsaanfk <> 1 then 'WNA' else kp.kelompokpasien end as kelompokpasien, pas.nocm, pd.tglregistrasi,
            case when cb.id = 2 then SUM ( sbm.totaldibayar ) else 0 end AS totalkartukredit, 
            case when cb.id <> 2 then SUM ( sbm.totaldibayar ) else 0 end AS totaltunai, 
            pd.noregistrasi, pd.norec AS norec_pd 
        FROM
            strukpelayanan_t AS sp
            INNER JOIN pasiendaftar_t AS pd ON sp.noregistrasifk = pd.norec
            INNER JOIN pasien_m AS pas ON pas.id = pd.nocmfk
            INNER JOIN strukbuktipenerimaan_t AS sbm ON sbm.nostrukfk = sp.norec
            LEFT JOIN strukbuktipenerimaancarabayar_t as sbmcr on sbmcr.nosbmfk = sbm.norec
            left join carabayar_m cb on cb.id = sbmcr.objectcarabayarfk
            INNER JOIN pegawai_m AS pg ON pg.id = sbm.objectpegawaipenerimafk 
            LEFT JOIN kelompokpasien_m kp on kp.id = pd.objectkelompokpasienlastfk
        WHERE
            CAST ( sbm.tglsbm AS DATE ) BETWEEN '$tglAwal' AND '$tglAkhir' 
            AND pd.kdprofile = 1 
            AND sp.statusenabled = true
            AND sp.objectkelompoktransaksifk = 46 
        GROUP BY
            pas.namapasien, kp.kelompokpasien, pas.nocm, kp.id, pas.objectkebangsaanfk,
            pd.tglregistrasi, pd.noregistrasi, pd.norec, cb.id
        order by pas.objectkebangsaanfk desc, kp.id desc"));

        $datapiutang = DB::select(DB::raw("SELECT
            kp.kelompokpasien, sbm.tglsbm::date tglsbm, pd.noregistrasi, pd.tglregistrasi, p.nocm, p.namapasien,
            SUM ( sbm.totaldibayar ) AS totaldibayar, pd.norec AS norec_pd, 
            sp.norec, pd.tglpulang, pd.nocmfk, pd.nostruklastfk, pd.nosbmlastfk 
        FROM
            pasiendaftar_t AS pd
            INNER JOIN antrianpasiendiperiksa_t AS ap ON pd.norec = ap.noregistrasifk
            INNER JOIN pelayananpasien_t AS pp ON ap.norec = pp.noregistrasifk
            LEFT JOIN strukpelayanan_t AS sp ON pp.strukfk = sp.norec
            INNER JOIN pasien_m AS p ON p.id = pd.nocmfk
            INNER JOIN jeniskelamin_m AS jk ON jk.id = p.objectjeniskelaminfk
            INNER JOIN statuspiutang_m AS stp ON stp.id = pd.objectstatuspiutangfk
            LEFT JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
            LEFT JOIN departemen_m AS dept ON dept.id = ru.objectdepartemenfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
            LEFT JOIN strukbuktipenerimaan_t AS sbm ON sbm.nostrukfk = sp.norec 
            LEFT JOIN strukbuktipenerimaancarabayar_t as sbmcr on sbmcr.nosbmfk = sbm.norec
            left join carabayar_m cb on cb.id = sbmcr.objectcarabayarfk
        WHERE
            pd.statusenabled = true
            and sp.statusenabled = true
            and sbm.statusenabled = true
            AND pd.objectstatuspiutangfk IS NOT NULL 
            AND sbm.tglsbm::DATE BETWEEN '$tglAwal' AND '$tglAkhir' 
            AND pd.kdprofile = 1 
        GROUP BY
            kp.kelompokpasien, sbm.tglsbm::date, pd.noregistrasi, pd.tglregistrasi, p.nocm, 
            p.namapasien, norec_pd, pd.tglpulang, sp.norec, pd.nocmfk, 
            pd.nostruklastfk, pd.nosbmlastfk 
        ORDER BY
            p.namapasien ASC"));

        $data = DB::table('strukbuktipenerimaan_t as sbm')
            ->join('strukpelayanan_t as sp', 'sbm.nostrukfk', '=', 'sp.norec')
            ->leftjoin('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pegawai_m as p', 'p.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sbm.ruanganfk')
            ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
            ->leftjoin('carabayar_m as cb', 'cb.id', '=', 'sbmcr.objectcarabayarfk')
            ->leftjoin('kelompoktransaksi_m as kt', 'kt.id', '=', 'sbm.objectkelompoktransaksifk')
            ->select(
                'sbm.norec',
                'sbmcr.norec as norec_sbmcr',
                'cb.carabayar',
                'sbmcr.objectcarabayarfk',
                'sbm.objectkelompoktransaksifk',
                'kt.kelompoktransaksi',
                'sbm.keteranganlainnya',
                'sbm.objectpegawaipenerimafk',
                'p.namalengkap as kasir',
                'sbm.nosbm',
                'ru.namaruangan',
                'sbm.tglsbm',
                'pd.noregistrasi',
                'sp.norec as norec_sp',
                'ps.nocm',
                'ps.namapasien',
                'pd.noregistrasi',
                'ps.nobpjs',
                'ps.noidentitas',
                'sbmcr.totaldibayar',
                DB::raw("case when sbm.noclosingfk is null then 'Belum Setor' else 'Setor' end as statussetor,
                case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien
                ")
            )
            ->where('sbm.statusenabled', true)
            ->where('sbm.kdprofile', $kdProfile);


        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('sbm.tglsbm', '>=', $r['dari'] . ' 00:00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('sbm.tglsbm', '<=',  $r['sampai'] . ' 23:59:59');
        }

        $data = $data->orderByDesc('sbm.tglsbm');
        $data = $data->get();

        $result['penerimaan'] = $data;
        $result['nonlayanan'] = $datanonlayanan;
        $result['deposit'] = $datadeposit;
        $result['piutang'] = $datapiutang;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function change($lang)
    {
        App::setLocale($lang);
        session()->put('locale', $lang);

        return redirect()->back();
    }


    public function cetakKwitansiRajalWNA(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;

        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pd.noregistrasifk')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'km.namakamar',
                'alm.alamatlengkap',
                'ps.nobpjs',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY \"Year\" MM \"Month\"') AS umur"),
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'sbm.nosbm',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("
                    case when pp.jasa is not null then pp.jasa else 0 end jasa,
                    COALESCE (pp.hargadiscount, 0)  as diskon,
                    ( (COALESCE(pp.hargasatuan,0)  - COALESCE (pp.hargadiscount, 0))   * pp.jumlah)
                    + ( COALESCE (pp.jasa, 0)) as total,
                    to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                    case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
                    case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
                    case
                        when pp.isobat = true then 'Medicine'
                        when dp.id = 3 then 'Laboratory'
                        when dp.id = 27 then 'Radiology'
                        when dp.id in(18,24) then 'Polyclinic/Emergency Room'
                        when prd.objectdetailjenisprodukfk in(3032, 3060, 3007, 3011) then 'Others'
                        else 'Treatment'
                    end as namaruangan,
                    case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
                ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasi', $r['noregistrasi'])
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.kdprofile', $this->kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $r['noregistrasi'])
            ->get();
        $res['total'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $res['total']  = (float) $res['total']  + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                }
            }
        }
        $res['total'] = round($res['total']);
        $res['billing'] =  $data->groupBy('namaruangan');
        $res['ismultipenjamin']  = false;
        $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['pdf']  = $r['pdf'];
        //dd($result);

        // $english = GoogleTranslate::trans('Saya suka apel', app()->getLocale());
        // dd($english);

        //dd($res['billing']);

        $blade = 'report.kasir.billing';
        $user = $this->getPegawai();


        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =  $data->groupBy('departemen_group');
            $blade = 'report.kasir.rekap-billing-wna';
        }
        if ($res['pdf'] =='true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade.'-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                )
            );
            return $pdf;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'data', 'user')
        );
    }

    public function cetakKwitansiRanapWNA(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;


        $this->change('en');

        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pd.noregistrasifk')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'km.namakamar',
                'alm.alamatlengkap',
                'ps.nobpjs',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur"),
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'sbm.nosbm',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("
                    case when pp.jasa is not null then pp.jasa else 0 end jasa,
                    COALESCE (pp.hargadiscount, 0)  as diskon,
                    ( (COALESCE(pp.hargasatuan,0)  - COALESCE (pp.hargadiscount, 0))   * pp.jumlah)
                    + ( COALESCE (pp.jasa, 0)) as total,
                    to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                    case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
                    case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
                    case
                        when pp.isobat = true then 'Medicine'
                        when dp.id = 3 then 'Laboratory'
                        when dp.id = 27 then 'Radiology'
                        when dp.id in(18,24) then 'Polyclinic/Emergency Room'
                        when prd.objectdetailjenisprodukfk in(3032, 3060, 3007, 3011) then 'Others'
                        else 'Treatment'
                    end as namaruangan,
                    case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
                ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasi', $r['noregistrasi'])
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.kdprofile', $this->kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $r['noregistrasi'])
            ->get();
        $res['total'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $res['total']  = (float) $res['total']  + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                }
            }
        }
        $res['total'] = round($res['total']);
        $res['billing'] =  $data->groupBy('namaruangan');
        $res['ismultipenjamin']  = false;
        $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['pdf']  = $r['pdf'];

        // $english = GoogleTranslate::trans('Saya suka apel', app()->getLocale());
        // dd($english);

        //dd($res['billing']);

        $blade = 'report.kasir.billing';
        $user = $this->getPegawai();


        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =  $data->groupBy('departemen_group');
            $blade = 'report.kasir.rekap-billing-wna-ranap';
        }
        if ($res['pdf'] =='true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                )
            );
            return $pdf;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'data', 'user')
        );
    }

    public function daftarPenerimaanDropdown(Request $r)
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
        $res['ruangankasir'] = Ruangan::mine()->where('objectdepartemenfk', $this->settingFix('idDepartemenKasir'))->get();

        return $this->respond($res);
    }
    public function saveUbahCaraBayar(Request $r)
    {
        DB::beginTransaction();
        try {
            StrukBuktiPenerimaanCaraBayar::where('norec', $r['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(['objectcarabayarfk' => $r['carabayar']]);

            $this->LOGGING(
                'Ubah Cara Bayar',
                $r['norec'],
                'strukbuktipenerimaan_t',
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
            // $produkDeposit = $this->settingFix('idProdukDeposit');
            $sbm = StrukBuktiPenerimaan::where('norec', $r['norec'])->first();

            // if ($r['isdeposit'] == true && $sbm->noregistrasi != null) {
            //     $getPP = DB::select(DB::raw("
            //     select pp.norec ,pp.produkfk,pp.hargasatuan
            //     from  pelayananpasien_t as pp
            //     where pd.kdprofile = $kdProfile
            //     and pd.noregistrasi ='$sbm->noregistrasi'
            //     and pp.produkfk='$produkDeposit'"));
            //     if (count($getPP) > 0) {
            //         foreach ($getPP as $item) {
            //             if ($item->hargasatuan == $sbm['totaldibayar']) {
            //                 PelayananPasien::where('norec', $item->norec)
            //                     ->where('kdprofile', $kdProfile)
            //                     ->delete();
            //             }
            //         }
            //     }
            // }
            if ($sbm->keteranganlainnya == 'Pembayaran Cicilan Tagihan Pasien') {
                $strukPelayananPenjamin = StrukPelayananPenjamin::where('nostrukfk', $r['norec_sp'])
                    ->where('kdprofile', $kdProfile)
                    ->first();
                $strukPelayananPenjamin->totalsudahdibayar = $strukPelayananPenjamin->totalsudahdibayar - $sbm->totaldibayar;
                $strukPelayananPenjamin->save();
            }
            StrukPelayanan::where('norec', $r['norec_sp'])
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'nosbmlastfk'    => null,
                    ]
                );
            StrukBuktiPenerimaan::where('norec', $r['norec'])
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusenabled' => false,
                        'nostrukfk'    => null,
                    ]
                );
            PasienDaftar::where('nostruklastfk', $r['norec_sp'])
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'nosbmlastfk'    => null,
                    ]
                );

            PasienDaftar::where('noregistrasi', $sbm->noregistrasi)->update(
                [
                    'statusbayar' => 'Belum Bayar'
                ]
            );
            //  //update flag deposit
            //  $KT = $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN');
            //  StrukBuktiPenerimaan::where('noregistrasi',$sbm->noregistrasi)
            //  ->where('kdprofile',$kdProfile)
            //  ->where('objectkelompoktransaksifk', $KT)
            //  ->update([
            //      'isbayar' => false
            //  ]);
            $this->LOGGING(
                'Batal Bayar',
                $r['norec'],
                'strukbuktipenerimaan_t',
                'Batal Bayar pada Pasien ' .
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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function cetakKwitansi(Request $r)
    {
        $profile = $this->profile();
        $kdProfile = $profile->id;
        $namapegawai = $this->getNamaPegawai();
        $tglcetak = $r['tanggalcetak'];
        $pageWidth = 950;
        $identitas = collect(DB::select("
            SELECT
                sbp.nosbm as nokwitansi,
                sbmcr.totaldibayar,
                sbp.tglsbm,
                sbp.keteranganlainnya,
                sbp.objectpegawaipenerimafk,
                ps.namapasien,alm.alamatlengkap,
                rm.namaruangan,
                ps.nocm,
                sp.norec as norec_sp,
                case when sbp.namapegawaipenerima is null then ps.namapasien else sbp.namapegawaipenerima end as namapenangung,
                ps.nocm,
                pd.noregistrasi
            FROM
                strukbuktipenerimaan_t as sbp
                LEFT JOIN strukbuktipenerimaancarabayar_t as sbmcr on sbmcr.nosbmfk = sbp.norec
                LEFT JOIN strukpelayanan_t as sp on sp.norec = sbp.nostrukfk
                LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                LEFT JOIN ruangan_m as rm on rm.id =  pd.objectruanganlastfk
                LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk
                left join alamat_m as alm on alm.nocmfk = ps.id
                LEFT JOIN pegawai_m as pg on pg.id = sbp.objectpegawaipenerimafk
            WHERE
            sbp.norec = ?
            and pd.statusenabled= true
            and sbp.kdprofile=?
            ", [$r['norec'], $kdProfile]));

        if (count($identitas) == 0) {

            $identitas = collect(DB::select("
                SELECT
                    sbp.nosbm as nokwitansi,
                    sbmcr.totaldibayar,
                    sbp.tglsbm,
                    sbp.keteranganlainnya,
                    sp.norec as norec_sp,
                    sbp.objectpegawaipenerimafk,alm.alamatlengkap,
                    case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
                    case when sbp.namapegawaipenerima is null then sp.namapasien_klien else namapasien end as namapenangung,
                    case when ps.nocm is null then '-' else  ps.nocm end as nocm,
                    '-' as namaruangan,
                    case when pd.noregistrasi is null then sp.nostruk else  pd.noregistrasi end as noregistrasi

                FROM
                    strukbuktipenerimaan_t as sbp
                    LEFT JOIN strukbuktipenerimaancarabayar_t as sbmcr on sbmcr.nosbmfk = sbp.norec
                    LEFT JOIN strukpelayanan_t as sp on sp.norec = sbp.nostrukfk
                    LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                    LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk
                    left join alamat_m as alm on alm.nocmfk = ps.id
                    LEFT JOIN pegawai_m as pg on pg.id = sbp.objectpegawaipenerimafk
                WHERE
                sbp.norec = ?
                and sbp.kdprofile=?
                ", [$r['norec'], $kdProfile]));
            // return $identitas;
        }
        // return $identitas;
        $detailLayanan = DB::table('strukpelayanandetail_t as sp')
            ->join('produk_m as pr', 'sp.objectprodukfk', '=', 'pr.id')
            ->select('pr.namaproduk', 'sp.objectprodukfk', 'sp.namaproduk as produk')
            ->where('sp.statusenabled', true)
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.nostrukfk', $identitas['0']->norec_sp)
            ->get();

        // return $identitas;
        $terbilang = $this->makeTerbilang($identitas[0]->totaldibayar);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'report.kasir.kwitansi',
            array(
                'identitas' => $identitas,
                'namaalias' => $r['namabaru'],
                'pdf' => isset($r['pdf']) ? $r['pdf'] : true,
                'terbilang' => $terbilang,
                'pageWidth' => $pageWidth,
                'detailLayanan' => $detailLayanan,
                'namapegawai' => $namapegawai,
                'profile' => $profile,
                'tglcetak' => $tglcetak
            )
        );
        $pdf->setPaper('A5', 'landscape');
        return $pdf->stream();
        // return view(
        //     'report.kasir.kwitansi',
        //     compact('identitas', 'namapegawai', 'tglcetak', 'terbilang', 'pageWidth', 'r', 'profile')
        // );
    }
}
