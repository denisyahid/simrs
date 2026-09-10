<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\Diagnosa;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\EMRPasien;
use App\Models\Transaksi\EMRPasienForm;
use App\Models\Transaksi\HasilPemeriksaanPcr;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukPelayanan;
// use Dompdf\Adapter\CPDF::$PAPER_SIZES;
use App\Traits\Valet;
use App\Exports\LaporanHarian;
use Carbon\Carbon;
use DateTime;
use Exception;
use stdClass;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode as Png;
use Endroid\QrCode\Writer\PngWriter;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isEmpty;

class ReportCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function higea(Request $res)
    {
        $blade = "report.higea2";
        $res['pdf'] = true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
            return "oke";
        } else {
            return view($blade, compact('res'));
        }
    }
    public function cetakResep(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];
        $params = '';
        $params2 = '';
        $params3 = '';
        $dataLogin = $request->all();
        $idUser = $dataLogin['userData']['id'];


        if (isset($request['norec']) && $request['norec'] != '' && $request['norec'] != 'null') {
            $params = " and sr.norec='$request[norec]' ";
        } else {
            if (isset($request['norec_order']) && $request['norec_order'] != '') {
                return $this->orderResep($request);
            }
        }
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
            $params2 = " and pd.noregistrasi='$request[noregistrasi]' ";
        }


        $profile = Profile::where('id', $this->kdProfile)->first();
        $data = collect(DB::select("
        SELECT 
        ps.nocm,
        sr.tepatpasien,
        sr.tepatobat,
        sr.campuranobat,
        sr.tepatdosis,
        sr.tepatrute,
        sr.duplikasiobat,
        sr.interaksiobat,
        sr.jenisobatl5,
        sr.kontraindikasi,
        sr.isbpl,
        aa.noantri,
        aa.jenis,
        ps.namapasien AS namapasienjk,
        ps.nohp AS noteleponfaks,
        pd.norec as norec_pd,
        ru.namaruangan as ruanganpengorder,
        apdp.objectruanganfk as idpengorder,
        sr.alergiobat,
        al.alamatlengkap AS alamat,
        to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir,
        TO_CHAR(age(ps.tgllahir), 'YY Tahun') as umur,
        sr.noresep,
        TO_CHAR(sr.tglresep, 'DD-MM-YYYY / HH24:MI') AS tglresep, 
        pgw.nosip,
                CASE 
                    WHEN pp.jasa IS NULL THEN 0 
                    ELSE pp.jasa END AS jasa,
                    pp.hargasatuan,
                    TO_CHAR(sr.tglresep, 'DD-MM-YYYY / HH24:MI') AS tglresep,
                    pp.dosis,
                    pp.aturanpakai || ' ' || 
                pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,
        pgw.namalengkap,
                CASE
                    WHEN pp.hargadiscount IS NULL THEN 0 
                    ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,
                ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,
        kpp.kelompokpasien as kelopokpasien
        FROM pelayananpasien_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec and pp.kdprofile = apdp.kdprofile
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec and pd.kdprofile = apdp.kdprofile
        left JOIN antrianapotik_t as aa on aa.noregistrasi = pd.noregistrasi
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id and ps.kdprofile = pd.kdprofile
        left join alamat_m as al on al.nocmfk=ps.id and ps.kdprofile = al.kdprofile
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id and ru.kdprofile = apdp.kdprofile
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec and sr.kdprofile = pp.kdprofile
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id and sr.kdprofile = pgw.kdprofile
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id and pd.kdprofile = kpp.kdprofile
        WHERE sr.kdprofile = $kdProfile
        and sr.statusenabled=true
        $params
        $params2
        "))->first();

        $detail = DB::table('pelayananpasien_t AS pp')
           ->join('produk_m AS pr', function ($join) {
                $join->on('pp.produkfk', '=', 'pr.id')
                    ->on('pp.kdprofile', '=', 'pr.kdprofile');
            })
            ->join('strukresep_t AS sr', function ($join) {
                $join->on('pp.strukresepfk', '=', 'sr.norec')
                    ->on('pp.kdprofile', '=', 'sr.kdprofile');
            })
            ->join('jeniskemasan_m AS jnskem', function ($join) {
                $join->on('pp.jeniskemasanfk', '=', 'jnskem.id')
                    ->on('pp.kdprofile', '=', 'jnskem.kdprofile');
            })
            ->join('satuanstandar_m AS sstd', function ($join) {
                $join->on('pp.satuanviewfk', '=', 'sstd.id')
                    ->on('pp.kdprofile', '=', 'sstd.kdprofile');
            })
            ->leftJoin('satuanresep_m AS ssr', function ($join) {
                $join->on('ssr.id', '=', 'pp.satuanresepfk')
                    ->on('ssr.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJoin('detailjenisproduk_m as djp', function ($join) {
                $join->on('pr.objectdetailjenisprodukfk', '=', 'djp.id')
                    ->on('pr.kdprofile', '=', 'djp.kdprofile');
            })
            ->leftJoin('strukorder_t AS sk', function ($join) {
                $join->on('sr.orderfk', '=', 'sk.norec')
                    ->on('sr.kdprofile', '=', 'sk.kdprofile');
            })
            ->leftJoin('pelayananpasienobatkronis_t AS ppk', function ($join) {
                $join->on('ppk.strukresepfk', '=', 'sr.norec')
                    ->on('ppk.produkfk', '=', 'pr.id')
                    ->on('ppk.strukresepfk', '=', 'sr.norec')
                    ->on('ppk.jeniskemasanfk', '=', 'jnskem.id');
            })
            ->leftJoin('jenisracikan_m AS jrcc', function ($join) {
                $join->on('jrcc.id', '=', 'pp.jenisracikanfk');
            })
            ->select([
                'sr.kdprofile',
                DB::raw("COALESCE(jrcc.jenisracikan, 'Non-Racikan') AS jenisracikan"),
                'sk.norec as test',
                'sk.namapengambilorder as pengambil',
                'sr.noresep',
                'pp.rke',
                'pr.id AS kdproduk',
                'pr.namaproduk AS namaprodukstandar',
                DB::raw("pp.jumlah + coalesce(ppk.jumlah, 0) as jumlah"),
                'pp.qtydetailresep',
                'pp.racikan',
                'sstd.satuanstandar',
                DB::raw('
                CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa, 
                CASE 
                    WHEN pp.aturanpakai IS NOT NULL AND CHAR_LENGTH(pp.aturanpakai) > 1 THEN SUBSTRING(pp.aturanpakai, 1, CHAR_LENGTH(pp.aturanpakai) - 1)
                    ELSE COALESCE(pp.aturanpakai, \'\')
                END AS aturanpakai1,
                CASE 
                    WHEN pp.aturanpakai IS NOT NULL AND CHAR_LENGTH(pp.aturanpakai) > 2 THEN RIGHT(pp.aturanpakai, CHAR_LENGTH(pp.aturanpakai) - 2)
                    ELSE \'\'
                END AS aturanpakai2
                '),
                'pp.hargasatuan',
                DB::raw("to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep"),
                'pp.dosis',
                DB::raw("pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai"),
                DB::raw('pp.jumlah + coalesce(ppk.jumlah, 0) AS qtyhrg'),
                DB::raw('((pp.jumlah + coalesce(ppk.jumlah, 0)) * (pp.hargasatuan - (CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END))) + CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS totalharga'),
                'jnskem.jeniskemasan',
                DB::raw('CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * (pp.jumlah + coalesce(ppk.jumlah, 0)) END AS totaldiscound'),
                'pp.qtydetailresep',
                DB::raw('CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END AS diskon'),
                'djp.detailjenisproduk'
            ])
            ->where('sr.kdprofile', '=', $kdProfile)
            ->where('sr.statusenabled', true)
            ->orderBy('pp.rke', 'asc')
            ->where('sr.norec', $request['norec'])
            ->get()
            ->groupBy('jenisracikan');

        $totalSemua = $detail->flatten(1)->sum('totalharga');
        
        $departemen = DB::table('ruangan_m')
                      ->select('id','objectdepartemenfk')
                      ->where('statusenabled', true)
                      ->where('id', $data->idpengorder)
                      ->first();
        $tb = '';
        $bb = '';

        if((int)$departemen->objectdepartemenfk == 18)
        {
            $res = DB::connection('mongodb')
            ->table('AsesmenAwalKeperawatanPasienRawatJalanNurse')
            ->where('registrasi.norec_pd', $data->norec_pd)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('namatemplate')
            ->select('beratbadanObgyn', 'tinggibadanObgyn')
            ->orderByDesc('created_at')
            ->first();
            
            $tb = $res['tinggibadanObgyn'] ?? 0;
            $bb = $res['beratbadanObgyn'] ?? 0;
        }
        else 
        {
            $res = DB::connection('mongodb')
            ->table('VitalSign')
            ->where('registrasi.norec_pd', $data->norec_pd)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('namatemplate')
            ->select('beratBadan', 'tinggiBadan')
            ->orderByDesc('created_at')
            ->first();

            $tb = $res['tinggiBadan'] ?? 0;
            $bb = $res['beratBadan'] ?? 0;
        }

        $dataReport = array(
            'data' => $data,
            'bb' => $bb,
            'tb' => $tb,
            'detail' => $detail,
            'totalharga' => $totalSemua,
            'user' => $this->getNamaPegawai()

        );
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : false;
        $user = $this->getNamaPegawai();
        $blade = "report.farmasi.resep-obat";
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'potrait');
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper([0, 0, 461.55, 841.00],'landscape');
            // $pdf->setPaper('a2');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'res' => $res,
                    // 'jenisracikan' => $jenis,
                    'profile' => $profile,
                    'user' => $this->getNamaPegawai()
                )
            );
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper('A5', 'landscape');
            return $pdf->stream();
        } else {
            return view($blade, compact('dataReport', 'res', 'profile', 'user'));
        }
    }

    
    public function cetakResepObatBebas(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];
        $dataReport = DB::table('strukpelayanan_t as sp')
            ->leftJoin('pasien_m as ps', 'sp.nocmfk', '=', 'ps.id')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->leftJoin('strukpelayanandetail_t as spd', 'sp.norec', 'spd.nostrukfk')
            ->leftJoin('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
            ->leftJoin('satuanstandar_m AS ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->leftjoin('kebangsaan_m as kbm', 'kbm.id', 'sp.objectkebangsaanfk')
            ->select(
                'djp.detailjenisproduk',
                'kbm.name as kebangsaan',
                'ps.nocm',
                'sp.tglfaktur as tgllahir',
                'sp.tglstruk',
                'sp.nostruk',
                'sp.nostruk_intern',
                'sp.namapasien_klien',
                'pg.nosip',
                'pg.namalengkap',
                'sp.namarekanan',
                'ru.namaruangan',
                'sp.norec',
                'sp.nocmfk as nocm',
                'sp.noteleponfaks',
                'sp.namatempattujuan',
                'sbm.nosbm',
                'ps.nobpjs',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim',
                'sp.namapasien_klien AS namapasien',
                'sp.nostruk',
                'pr.namaproduk',
                'spd.tglkadaluarsa',
                'spd.qtyproduk as qty',
                'spd.aturanpakai',
                'ss.satuanstandar AS satuan',
                'spd.keteranganpakai',
                'spd.ispagi AS pagi',
                'spd.issore AS sore',
                'spd.issiang AS siang',
                'spd.ismalam AS malam',
                'spd.hargasatuan',
                'spd.hargadiscountsave AS discount'
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.norec', $norec)
            ->get();
        // return $dataReport;
        // return response()->json(['data' =>$dataReport]);
        $profile = Profile::where('id', $this->kdProfile)->first();
        $pageWidth = 400;
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'potrait');
            $pdf->loadView(
                'report.farmasi.cetak-resep-bebas',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'profile' => $profile,
                    'user' => $this->getNamaPegawai(),
                    'res' => array(
                    'pdf' => true
                    ),
                    'profile' => $profile,
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.cetak-resep-bebas',
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    public function orderResep($r)
    {

        $profile = Profile::where('id', $this->kdProfile)->first();
        $raw = DB::table('strukorder_t AS st')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'st.noregistrasifk')
            ->join('pasien_m AS pm2', 'pm2.id', '=', 'st.nocmfk')
            ->leftJoin('jeniskelamin_m AS jm', 'jm.id', '=', 'pm2.objectjeniskelaminfk')
            ->leftJoin('pegawai_m AS pm3', 'pm3.id', '=', 'st.objectpegawaiorderfk')
            ->leftJoin('ruangan_m AS rm', 'rm.id', '=', 'st.objectruanganfk')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m AS rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->select(
                'pm2.nocm',
                DB::raw("to_char(pm2.tgllahir, 'dd-mm-yyyy') AS tgllahir"),
                'jm.jeniskelamin',
                'pm2.namapasien',
                'pm3.namalengkap',
                'rm.namaruangan',
                DB::raw("to_char(st.tglorder, 'dd-mm-yyyy MM:ss') AS tglorder"),
                'pm3.nosip',
                'kp.kelompokpasien',
                DB::raw("'' AS apoteker"),
                DB::raw("'' AS sipa"),
                'st.noorder AS noresep',
                'rkn.namarekanan',
                DB::raw("EXTRACT (YEAR FROM AGE(pd.tglregistrasi,pm2.tgllahir)) || ' Thn ' ||
                      EXTRACT (MONTH FROM AGE(pd.tglregistrasi,pm2.tgllahir)) || ' bln ' ||
                      EXTRACT (day FROM AGE(pd.tglregistrasi,pm2.tgllahir)) || ' hr' as umur")
            )
            ->where('st.norec', '=', $r->norec_order)
            ->where('st.kdprofile', '=', $this->kdProfile)
            ->first();


        $detel = [];

        $details = DB::table('strukorder_t AS st')
            ->join('orderpelayanan_t AS ot', 'ot.strukorderfk', '=', 'st.norec')
            ->join('produk_m AS pm', 'pm.id', '=', 'ot.objectprodukfk')
            ->join('jeniskemasan_m AS jek', 'jek.id', '=', 'ot.jeniskemasanfk')
            ->leftJoin('jenisracikan_m AS jer', 'jer.id', '=', 'ot.jenisobatfk')
            ->leftJoin('satuanstandar_m AS ss', 'ss.id', '=', 'ot.objectsatuanstandarfk')
            ->select(
                'ot.rke',
                'pm.namaproduk',
                'ot.dosis',
                'ot.jumlah AS jumlah',
                'pm.kekuatan AS kekuatan',
                'ot.aturanpakai',
                'ot.qtykemasan',
                'jek.jeniskemasan as  jeniskemasan',
                'ot.qtyprodukinuse',
                'ot.qtykemasan',
                DB::raw("CASE WHEN ot.jeniskemasanfk = '2' THEN jek.jeniskemasan ELSE jek.jeniskemasan || ' R' || ot.rke || ' Jumlah ' || ot.qtykemasan END AS jenisobat"),
                DB::raw("CASE WHEN ot.jeniskemasanfk = '2' THEN jek.jeniskemasan ELSE jer.namaexternal || ' R' || ot.rke || ' ' || ot.qtykemasan END AS jenisracikan"),
                'ss.satuanstandar'
            )
            ->where('st.norec', '=', $r->norec_order)
            ->where('st.kdprofile', '=', $this->kdProfile)
            ->get();
        $pageWidth = 800;
        $dataReport = array(
            'user' => $this->getNamaPegawai(),
            'raw' => $raw,
            'detail' => $details,
            'tanggal' => date('Y-m-d H:i:s'),
        );
        if ($r['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setOptions(['defaultFont' => 'Tahoma, sans-serif']);
            $pdf->loadView(
                'report.farmasi.cetak-order-resep',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'r' => $r,
                    'details' => $details,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.cetak-order-resep',
                compact('dataReport', 'pageWidth', 'r', 'details', 'profile')
            );
        }
    }
    public function cetakCopyResep(Request $request)
    {
        $arrayOP = explode(',', $request->norec_op);
        $profile = Profile::where('id', $this->kdProfile)->first();
        $apoteker = Pegawai::select('namalengkap as nama', 'nosipa')->where('id', $request['apoteker'])
            ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
        $noorder = $request['norec_order'];
        $Iter = isset($request['Iter']) ? $request['Iter'] : null;
        $pageWidth = 950;
        $identitas = collect(DB::select("
            select pm2.nocm ,to_char(pm2.tgllahir,'dd-mm-yyyy') as tgllahir,age(pm2.tgllahir) as umur ,jm.jeniskelamin ,pm2.namapasien ,pm3.namalengkap, rm.namaruangan,
                   to_char(st.tglorder,'dd-mm-yyyy MM:ss') as tglorder,pm3.nosip, kp.kelompokpasien,'-' AS apoteker, '-' AS sipa,st.noorder as noresep
            from strukorder_t st
            inner join pasien_m pm2 on pm2.id = st.nocmfk
            inner join jeniskelamin_m jm on jm.id = pm2.objectjeniskelaminfk
            inner join pegawai_m pm3 on pm3.id = st.objectpegawaiorderfk

            inner join ruangan_m rm on rm.id = st.objectruanganfk
            inner join pasiendaftar_t AS pd ON pd.norec = st.noregistrasifk
            left join kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
            where st.norec = '$noorder'
        "))->first();
        if (empty($identitas)) {
            $identitas = collect(DB::select("
                select pm2.nocm,to_char(pm2.tgllahir,'dd-mm-yyyy') as tgllahir,age(pm2.tgllahir) as umur,jm.jeniskelamin,
                       pm2.namapasien,pm3.namalengkap,rm.namaruangan,to_char(st.tglresep,'dd-mm-yyyy MM:ss') as tglorder,
                       pm3.nosip, kp.kelompokpasien,'-' AS apoteker, '-' AS sipa,st.noresep
                from strukresep_t st
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = st.pasienfk
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                inner join pasien_m pm2 on pm2.id = pd.nocmfk
                inner join jeniskelamin_m jm on jm.id = pm2.objectjeniskelaminfk
                inner join pegawai_m pm3 on pm3.id = st.penulisresepfk

                inner join ruangan_m rm on rm.id = st.ruanganfk
                left join kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                where st.norec = '$request[norec_resep]'
            "))->first();
        }

        $details = DB::table('strukorder_t as st')
            ->join('orderpelayanan_t as ot', 'ot.strukorderfk', 'st.norec')
            ->join('produk_m as pm', 'pm.id', 'ot.objectprodukfk')
            ->join('jeniskemasan_m AS jek', 'jek.id', '=', 'ot.jeniskemasanfk')
            ->select(DB::raw(
                "ot.rke,pm.namaproduk,ot.dosis,pm.kekuatan,ot.jumlah as jumlah,ot.aturanpakai,
                        CASE WHEN ot.iter IS NULL THEN '' ELSE ot.iter END AS iter,ot.keteranganpakai,ot.objectprodukfk,jek.jeniskemasan as  jeniskemasan"
            ))
            ->where('st.norec', $request['norec_order'])
            ->whereIn('ot.norec', $arrayOP)
            ->get();

        if (count($details) == 0) {
            $details = DB::select(DB::raw("
                select ot.rke,pm.namaproduk,ot.dosis,pm.kekuatan ,ot.jumlah as jumlah,
                       ot.aturanpakai,CASE WHEN ot.iter IS NULL THEN '' ELSE ot.iter END AS iter,ot.keteranganpakai,
                       ot.produkfk AS objectprodukfk,jek.jeniskemasan as  jeniskemasan
                from strukresep_t st
                inner join pelayananpasien_t ot on ot.strukresepfk = st.norec
                inner join produk_m pm on pm.id = ot.produkfk
                left  join jeniskemasan_m as jek on  jek.id = ot.jeniskemasanfk
                where st.norec = '$request[norec_resep]'
            "));
        }


        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $identitas,
            'details' => $details,
        );

        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setOptions(['defaultFont' => 'Arial, Helvetica, sans-serif']);
            $pdf->loadView(
                'report.farmasi.copy-resep',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'apoteker' => $request['apoteker'] ? $apoteker : $this->getPegawai(),
                    'Iter' => $Iter,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.copy-resep',
                compact('dataReport', 'pageWidth', 'profile', 'Iter')
            );
        }
    }

    public function apotikRekapLabelKecilObatBebas(Request $request)
    {
        $dataRacikan = DB::table('strukpelayanan_t as sp')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->LEFTJOIN('strukpelayanandetail_t as spd', 'sp.norec', 'spd.nostrukfk')
            ->LEFTJOIN('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->LEFTJOIN('satuanstandar_m AS ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->LEFTJOIN('satuanresep_m AS sr', 'sr.id', 'spd.satuanresepfk')
            ->LEFTJOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'spd.jenisracikanfk')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'spd.objectjeniskemasanfk')
            // ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->select(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.objectkebangsaanfk',
                'sp.nostruk_intern',
                'spd.objectjeniskemasanfk',
                'sp.namapasien_klien as namapasien',
                'pg.namalengkap',
                'sp.tglfaktur as tgllahir',
                'sp.nostruk_intern as nocm',
                'ru.namaruangan',
                'sp.norec',
                'jer.jeniskemasan',
                'sr.satuanresep',
                'sp.nocmfk as nocm',
                'sp.noteleponfaks',
                'sp.namatempattujuan as alamatlengkap',
                'sbm.nosbm',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim',
                'sp.namapasien_klien AS namapasien',
                'sp.nostruk',
                'pr.namaproduk',
                'spd.tglkadaluarsa',
                'spd.qtyproduk as qty',
                'spd.aturanpakai',
                'ss.satuanstandar AS satuan',
                'spd.keteranganpakai',
                'jrc.jenisracikan',
                'spd.tglpemakaian',
                DB::raw("SUBSTRING(spd.aturanpakai, 1, CHAR_LENGTH(spd.aturanpakai) ) as aturanpakai"),
                DB::raw("RIGHT(spd.aturanpakai, CHAR_LENGTH(spd.aturanpakai) ) as aturanpakai2"),
                DB::raw("
                CASE 
                    WHEN spd.ispagi != 't' THEN ''  
                    WHEN sp.objectkebangsaanfk = 1 THEN 'PAGI' 
                    ELSE 'MORNING' 
                END AS pagi
                "),
                DB::raw("
                    CASE 
                        WHEN spd.issiang != 't' THEN ''  
                        WHEN sp.objectkebangsaanfk = 1 THEN 'SIANG' 
                        ELSE 'AFTERNOON' 
                    END AS siang
                "),
                DB::raw("
                    CASE 
                        WHEN spd.issore != 't' THEN '' 
                        WHEN sp.objectkebangsaanfk = 1 THEN 'SORE' 
                        ELSE 'EVENING' 
                    END AS sore
                "),
                DB::raw("
                    CASE 
                        WHEN spd.ismalam != 't' THEN ''  
                        WHEN sp.objectkebangsaanfk = 1 THEN 'MALAM' 
                        ELSE 'NIGHT' 
                    END AS malam
                "),
                )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('spd.objectjeniskemasanfk', 1)
            ->where('sp.norec', $request->norec)
            ->get();

        $racikan = count($dataRacikan) > 0 ? $dataRacikan : 0;

        $dataNonRacikan = DB::table('strukpelayanan_t as sp')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->LEFTJOIN('strukpelayanandetail_t as spd', 'sp.norec', 'spd.nostrukfk')
            ->LEFTJOIN('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->LEFTJOIN('satuanstandar_m AS ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->LEFTJOIN('satuanresep_m AS sr', 'sr.id', 'spd.satuanresepfk')
            ->LEFTJOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'spd.objectjeniskemasanfk')
            // ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->select(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.nostruk_intern',
                'spd.objectjeniskemasanfk',
                'sp.namapasien_klien as namapasien',
                'pg.namalengkap',
                'sp.tglfaktur as tgllahir',
                'sp.objectkebangsaanfk',
                'sp.nostruk_intern as nocm',
                'ru.namaruangan',
                'sp.norec',
                'jer.jeniskemasan',
                'sr.satuanresep',
                'sp.nocmfk as nocm',
                'sp.noteleponfaks',
                'sp.namatempattujuan as alamatlengkap',
                'sbm.nosbm',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim',
                'sp.namapasien_klien AS namapasien',
                'sp.nostruk',
                'pr.namaproduk',
                'spd.tglkadaluarsa',
                'spd.qtyproduk as qty',
                'spd.tglpemakaian',
                DB::raw("SUBSTRING(spd.aturanpakai, 1, CHAR_LENGTH(spd.aturanpakai) ) as aturanpakai"),
                DB::raw("RIGHT(spd.aturanpakai, CHAR_LENGTH(spd.aturanpakai) ) as aturanpakai2"),
                'ss.satuanstandar AS satuan',
                'spd.keteranganpakai',
                 DB::raw("
                CASE 
                    WHEN spd.ispagi != 't' THEN ''  
                    WHEN sp.objectkebangsaanfk = 1 THEN 'PAGI' 
                    ELSE 'MORNING' 
                END AS pagi
                "),
                DB::raw("
                    CASE 
                        WHEN spd.issiang != 't' THEN ''  
                        WHEN sp.objectkebangsaanfk = 1 THEN 'SIANG' 
                        ELSE 'AFTERNOON' 
                    END AS siang
                "),
                DB::raw("
                    CASE 
                        WHEN spd.issore != 't' THEN '' 
                        WHEN sp.objectkebangsaanfk = 1 THEN 'SORE' 
                        ELSE 'EVENING' 
                    END AS sore
                "),
                DB::raw("
                    CASE 
                        WHEN spd.ismalam != 't' THEN ''  
                        WHEN sp.objectkebangsaanfk = 1 THEN 'MALAM' 
                        ELSE 'NIGHT' 
                    END AS malam
                "),
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('spd.objectjeniskemasanfk', 2)
            ->where('sp.norec', $request->norec)
            ->get();

        $nonRacikan = count($dataNonRacikan) > 0 ? $dataNonRacikan : 0;

        $profile = Profile::where('id', $this->kdProfile)->first();
        $pageWidth = '500p';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 327.15, 290.40]);
            $pdf->setPaper([0, 0, 210, 200]);
            $pdf->loadView(
                'report.farmasi.rekap-label-kecil-bebas',
                array(
                    'nonRacikan' => $nonRacikan,
                    'racikan' => $racikan,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                    'profile' => $profile,
                )
            );

            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.rekap-label-kecil-bebas',
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    public function apotikRekapLabelKecilObatPesanan(Request $request)
    {
        $dataRacikan = DB::table('strukreseppesanan_t as sp')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->LEFTJOIN('strukreseppesanandetail_t as spd', 'sp.norec', 'spd.nostrukfk')
            ->LEFTJOIN('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->LEFTJOIN('satuanstandar_m AS ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->LEFTJOIN('satuanresep_m AS sr', 'sr.id', 'spd.satuanresepfk')
            ->LEFTJOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'spd.jenisracikanfk')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'spd.objectjeniskemasanfk')
            // ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->select(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.objectkebangsaanfk',
                'sp.nostruk_intern',
                'spd.objectjeniskemasanfk',
                'sp.namapasien_klien as namapasien',
                'pg.namalengkap',
                'sp.tglfaktur as tgllahir',
                'sp.nostruk_intern as nocm',
                'ru.namaruangan',
                'sp.norec',
                'jer.jeniskemasan',
                'sr.satuanresep',
                'sp.nocmfk as nocm',
                'sp.noteleponfaks',
                'sp.namatempattujuan as alamatlengkap',
                'sbm.nosbm',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim',
                'sp.namapasien_klien AS namapasien',
                'sp.nostruk',
                'pr.namaproduk',
                'spd.tglkadaluarsa',
                'spd.qtyproduk as qty',
                'spd.aturanpakai',
                'ss.satuanstandar AS satuan',
                'spd.keteranganpakai',
                'jrc.jenisracikan',
                'spd.tglpemakaian',
                DB::raw("SUBSTRING(spd.aturanpakai, 1, CHAR_LENGTH(spd.aturanpakai) ) as aturanpakai"),
                DB::raw("RIGHT(spd.aturanpakai, CHAR_LENGTH(spd.aturanpakai) ) as aturanpakai2"),
                DB::raw("
                CASE 
                    WHEN spd.ispagi != 't' THEN ''  
                    WHEN sp.objectkebangsaanfk = 1 THEN 'PAGI' 
                    ELSE 'MORNING' 
                END AS pagi
                "),
                DB::raw("
                    CASE 
                        WHEN spd.issiang != 't' THEN ''  
                        WHEN sp.objectkebangsaanfk = 1 THEN 'SIANG' 
                        ELSE 'AFTERNOON' 
                    END AS siang
                "),
                DB::raw("
                    CASE 
                        WHEN spd.issore != 't' THEN '' 
                        WHEN sp.objectkebangsaanfk = 1 THEN 'SORE' 
                        ELSE 'EVENING' 
                    END AS sore
                "),
                DB::raw("
                    CASE 
                        WHEN spd.ismalam != 't' THEN ''  
                        WHEN sp.objectkebangsaanfk = 1 THEN 'MALAM' 
                        ELSE 'NIGHT' 
                    END AS malam
                "),
                )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('spd.objectjeniskemasanfk', 1)
            ->where('sp.norec', $request->norec)
            ->get();

        $racikan = count($dataRacikan) > 0 ? $dataRacikan : 0;

        $dataNonRacikan = DB::table('strukreseppesanan_t as sp')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->LEFTJOIN('strukreseppesanandetail_t as spd', 'sp.norec', 'spd.nostrukfk')
            ->LEFTJOIN('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->LEFTJOIN('satuanstandar_m AS ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->LEFTJOIN('satuanresep_m AS sr', 'sr.id', 'spd.satuanresepfk')
            ->LEFTJOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'spd.objectjeniskemasanfk')
            // ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->select(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.nostruk_intern',
                'spd.objectjeniskemasanfk',
                'sp.namapasien_klien as namapasien',
                'pg.namalengkap',
                'sp.tglfaktur as tgllahir',
                'sp.objectkebangsaanfk',
                'sp.nostruk_intern as nocm',
                'ru.namaruangan',
                'sp.norec',
                'jer.jeniskemasan',
                'sr.satuanresep',
                'sp.nocmfk as nocm',
                'sp.noteleponfaks',
                'sp.namatempattujuan as alamatlengkap',
                'sbm.nosbm',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim',
                'sp.namapasien_klien AS namapasien',
                'sp.nostruk',
                'pr.namaproduk',
                'spd.tglkadaluarsa',
                'spd.qtyproduk as qty',
                'spd.tglpemakaian',
                DB::raw("SUBSTRING(spd.aturanpakai, 1, CHAR_LENGTH(spd.aturanpakai) ) as aturanpakai"),
                DB::raw("RIGHT(spd.aturanpakai, CHAR_LENGTH(spd.aturanpakai) ) as aturanpakai2"),
                'ss.satuanstandar AS satuan',
                'spd.keteranganpakai',
                 DB::raw("
                CASE 
                    WHEN spd.ispagi != 't' THEN ''  
                    WHEN sp.objectkebangsaanfk = 1 THEN 'PAGI' 
                    ELSE 'MORNING' 
                END AS pagi
                "),
                DB::raw("
                    CASE 
                        WHEN spd.issiang != 't' THEN ''  
                        WHEN sp.objectkebangsaanfk = 1 THEN 'SIANG' 
                        ELSE 'AFTERNOON' 
                    END AS siang
                "),
                DB::raw("
                    CASE 
                        WHEN spd.issore != 't' THEN '' 
                        WHEN sp.objectkebangsaanfk = 1 THEN 'SORE' 
                        ELSE 'EVENING' 
                    END AS sore
                "),
                DB::raw("
                    CASE 
                        WHEN spd.ismalam != 't' THEN ''  
                        WHEN sp.objectkebangsaanfk = 1 THEN 'MALAM' 
                        ELSE 'NIGHT' 
                    END AS malam
                "),
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('spd.objectjeniskemasanfk', 2)
            ->where('sp.norec', $request->norec)
            ->get();

        $nonRacikan = count($dataNonRacikan) > 0 ? $dataNonRacikan : 0;

        $profile = Profile::where('id', $this->kdProfile)->first();
        $pageWidth = '500p';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 327.15, 290.40]);
            $pdf->setPaper([0, 0, 210, 200]);
            $pdf->loadView(
                'report.farmasi.rekap-label-kecil-bebas',
                array(
                    'nonRacikan' => $nonRacikan,
                    'racikan' => $racikan,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                    'profile' => $profile,
                )
            );

            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.rekap-label-kecil-bebas',
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    // public function apotikRekapLabelKecilObatBebas(Request $request)
    // {
    //     //DB::enableQueryLog();
    //     $dataRacikan = DB::table('strukpelayanan_t AS sp')->select(
    //         'ps.nocm',
    //         'sp.namapasien_klien as namapasien',
    //         'sp.tglfaktur as tgllahir',
    //         'sp.objectkebangsaanfk',
    //         'spd.resepke',
    //         'sp.tglstruk as tglresep',
    //         'ru.namaruangan',
    //         'sp.nostruk',
    //         'jer.jeniskemasan as  jeniskemasan',
    //         DB::raw("CASE WHEN spd.ispagi = true THEN 'Pagi' ELSE '' END AS pagi"),
    //         DB::raw("CASE WHEN spd.issiang = true THEN 'Siang' ELSE '' END AS siang"),
    //         DB::raw("CASE WHEN spd.issore = true THEN 'Sore' ELSE '' END AS sore"),
    //         DB::raw("CASE WHEN spd.ismalam = true THEN 'Malam' ELSE '' END AS malam"),
    //         'spd.keteranganpakai',
    //         // 'jrc.penjualan-obat-bebas',
    //         'sn.satuanresep',
    //         // 'pp.tglpemakaian',
    //         DB::raw("SUBSTRING(spd.aturanpakai, 1, CHAR_LENGTH(spd.aturanpakai) - 1) as aturanpakai"),
    //         DB::raw("RIGHT(spd.aturanpakai, CHAR_LENGTH(spd.aturanpakai) - 2) as aturanpakai2"),
    //         'spd.tglkadaluarsa',
    //         'sp.namatempattujuan as alamatlengkap'
    //     )
    //         ->join('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
    //         ->join('strukpelayanandetail_t as spd', 'spd.nostruk', '=', 'sp.norec')
    //         ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
    //         ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')          
    //         ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandar')
    //         ->leftJoin('satuanresep_m as sn', 'pp.satuanresepfk', '=', 'sn.id')
    //         ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'spd.objectjeniskemasanfk')
    //         ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'spd.jenisracikanfk')
    //         ->where('sr.kdprofile', $this->kdProfile)
    //         ->where('jer.jeniskemasan', 'Racikan')
    //         ->where('sp.norec', $request->norec)
    //         ->groupBy(
    //             // "pd.tglregistrasi",
    //             "ps.nocm",
    //             "sp.namapasien_klien",
    //             "sp.tglfaktur",
    //             "sp.tglstruk",
    //             "sp.nostruk",
    //             "jer.jeniskemasan",
    //             "sp.objectkebangsaanfk",
    //             'spd.ispagi',
    //             'spd.issiang',
    //             'spd.issore',
    //             "ru.namaruangan",
    //             'spd.ismalam',
    //             "spd.keteranganpakai",
    //             "jrc.jenisracikan",
    //             "sn.satuanresep",
    //             "spd.aturanpakai",
    //             "spd.tglkadaluarsa",
    //             'sp.namatempattujuan',
    //             // 'pp.tglpemakaian',
    //             'spd.resepke',
    //             DB::raw("SUBSTRING(spd.aturanpakai, 1, CHAR_LENGTH(spd.aturanpakai) - 1)"),
    //             DB::raw("RIGHT(spd.aturanpakai, CHAR_LENGTH(spd.aturanpakai) - 2)")
    //         )
    //         ->get();

    //     //$queries = DB::getQueryLog();
    //     //print_r($queries);

    //     //dd($dataRacikan->toSql());

    //     $racikan = count($dataRacikan) > 0 ? $dataRacikan : 0;

    //     // if (isset($request['norecpd'])) {
    //     //     return $this->cetakLabelRacikanResep($request);
    //     // }
    //     $profile = Profile::where('id', $this->kdProfile)->first();
    //     $norec = $request['norec'];
    //     // $orderfk = $request['orderfk'];

    //     $pageWidth = '500p';
    //     $produkfk = '';

    //     if ($request['norec']) {
    //         $dataNonRacikan = collect(DB::select("select
    //         pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,jer.jeniskemasan,
    //         pr.namaproduk,ss.satuanstandar,sr.tglresep,ss.id as ssid,ru.namaruangan,
    //         sr.noresep,pp.jenisracikanfk, ap.alamatlengkap, ps.objectkebangsaanfk,
    //         pp.tglpemakaian,
    //         pp.jumlah,CASE WHEN pp.ispagi = true THEN 'Pagi' ELSE '' END AS pagi,
    //         CASE WHEN pp.issiang = true THEN 'Siang' ELSE '' END AS siang,
    //         CASE WHEN pp.issore = true THEN 'Sore' ELSE '' END AS sore,
    //         CASE WHEN pp.ismalam = true THEN 'Malam' ELSE '' END AS malam,
    //         pp.keteranganpakai,sn.satuanresep,
    //         SUBSTRING(pp.aturanpakai, 1, CHAR_LENGTH(pp.aturanpakai) - 1) as aturanpakai,
    //         RIGHT(pp.aturanpakai, CHAR_LENGTH(pp.aturanpakai) - 2) as aturanpakai2,
    //         pp.tglkadaluarsa
    //         from strukpelayanan_t as sp
    //         join strukpelayanandetail_t as spd on spd.nostrukfk=sp.norec
    //         join jeniskemasan_m AS jer on jer.id = spd.objectjeniskemasanfk
    //         join produk_m as pr on pr.id=spd.objectprodukfk
    //         join pasien_m as ps  on ps.id=pd.nocmfk
    //         join ruangan_m as ru on ru.id=sp.objectruanganfk
    //         left join satuanstandar_m as ss on ss.id=pr.objectsatuanstandarfk
    //         left join satuanresep_m as sn on pp.satuanresepfk =sn.id
    //         where sp.kdprofile = $this->kdProfile
    //         and sp.norec='$orderfk'
    //         and spd.jeniskemasanfk = 2
    //         and ss.id = 501
    //         "));
    //     } else {
    //         $dataNonRacikan = collect(DB::select("select
    //         pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,
    //         pr.namaproduk,ss.satuanstandar,sr.tglresep, ru.namaruangan,
    //         sr.noresep,ss.id as ssid,pp.rke, ps.objectkebangsaanfk,
    //         pp.jumlah,CASE WHEN pp.ispagi = true THEN 'Pagi' ELSE '' END AS pagi,
    //         CASE WHEN pp.issiang = true THEN 'Siang' ELSE '' END AS siang,
    //         CASE WHEN pp.issore = true THEN 'Sore' ELSE '' END AS sore,
    //         CASE WHEN pp.ismalam = true THEN 'Malam' ELSE '' END AS malam,
    //         jer.jeniskemasan, ap.alamatlengkap,
    //         pp.tglpemakaian,
    //         pp.keteranganpakai,sn.satuanresep,
    //         SUBSTRING(pp.aturanpakai, 1, CHAR_LENGTH(pp.aturanpakai) - 1) as aturanpakai,
    //         RIGHT(pp.aturanpakai, CHAR_LENGTH(pp.aturanpakai) - 2) as aturanpakai2,
    //         pp.tglkadaluarsa,pp.jenisracikanfk
    //         from strukresep_t as sr
    //         join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk
    //         join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
    //         join jeniskemasan_m AS jer on jer.id = pp.jeniskemasanfk
    //         join produk_m as pr on pr.id=pp.produkfk
    //         join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk
    //         join pasien_m as ps  on ps.id=pd.nocmfk
    //         join ruangan_m as ru on ru.id=apd.objectruanganfk
    //         left join satuanstandar_m as ss on ss.id=pr.objectsatuanstandarfk
    //         left join satuanresep_m as sn on pp.satuanresepfk =sn.id
    //         left Join alamat_m as ap on ap.nocmfk = ps.id
    //         where sr.kdprofile = $this->kdProfile
    //         and pp.jeniskemasanfk = 2
    //         and sr.norec='$norec'
    //         "));
    //     }
    //     // return $dataNonRacikan;
    //     $nonRacikan = count($dataNonRacikan) > 0 ? $dataNonRacikan : 0;
    //     // return $nonRacikan;
    //     if ($request['pdf'] == 'true') {
    //         $pdf = App::make('dompdf.wrapper');
    //         // $pdf->setPaper([0, 0, 323.15, 290.40]);
    //         $pdf->setPaper([0, 0, 210, 200]);
    //         $pdf->loadView(
    //             'report.farmasi.rekap-label-kecil',
    //             array(
    //                 'nonRacikan' => $nonRacikan,
    //                 'racikan' => $racikan,
    //                 'pageWidth' => $pageWidth,
    //                 'r' => $request,
    //                 'request' => $request,
    //                 'profile' => $profile,
    //                 'res' => array(
    //                     'pdf' => true
    //                 ),
    //             )
    //         );
    //         return $pdf->stream();
    //     } else {
    //         return view(
    //             'report.farmasi.rekap-label-kecil',
    //             compact('dataReport', 'pageWidth', 'profile', 'request')
    //         );
    //     }

    //     // return $dataRacikan->toSql();
    // }

    public function apotikRekapLabelKecil(Request $request)
    {
        //DB::enableQueryLog();
        $dataRacikan = DB::table('strukresep_t AS sr')->select(
            'pd.tglregistrasi',
            'ps.nocm',
            'ps.namapasien',
            'ps.tgllahir',
            'sr.ruanganfk',
            'ps.objectkebangsaanfk',
            'pp.rke',
            'sr.tglresep',
            'ru.namaruangan',
            'sr.noresep',
            'jer.jeniskemasan as  jeniskemasan',
            DB::raw("
                CASE 
                    WHEN pp.ispagi != 't' THEN ''  
                    WHEN ps.objectkebangsaanfk = 1 THEN 'PAGI' 
                    ELSE 'MORNING' 
                END AS pagi
            "),
            DB::raw("
                CASE 
                    WHEN pp.issiang != 't' THEN ''  
                    WHEN ps.objectkebangsaanfk = 1 THEN 'SIANG' 
                    ELSE 'AFTERNOON' 
                END AS siang
            "),
            DB::raw("
                CASE 
                    WHEN pp.issore != 't' THEN '' 
                    WHEN ps.objectkebangsaanfk = 1 THEN 'SORE' 
                    ELSE 'EVENING' 
                END AS sore
            "),
            DB::raw("
                CASE 
                    WHEN pp.ismalam != 't' THEN ''  
                    WHEN ps.objectkebangsaanfk = 1 THEN 'MALAM' 
                    ELSE 'NIGHT' 
                END AS malam
            "),
            'pp.keteranganpakai',
            'jrc.jenisracikan',
            'sn.satuanresep',
            'pp.tglpemakaian',
            DB::raw("SUBSTRING(pp.aturanpakai, 1, CHAR_LENGTH(pp.aturanpakai) ) as aturanpakai"),
            DB::raw("RIGHT(pp.aturanpakai, CHAR_LENGTH(pp.aturanpakai) ) as aturanpakai2"),
            'pp.tglkadaluarsa',
            'ap.alamatlengkap'
        )
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')  
            ->leftJoin('alamat_m as ap', 'ap.nocmfk', '=', 'ps.id')         
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoin('satuanresep_m as sn', 'pp.satuanresepfk', '=', 'sn.id')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->where('sr.kdprofile', $this->kdProfile)
            ->where('jer.jeniskemasan', 'Racikan')
            ->where('pd.norec', $request['norecpd'])
            ->where('sr.norec', $request['norec'])
            ->groupBy(
                "pd.tglregistrasi",
                "ps.nocm",
                "ps.namapasien",
                "ps.tgllahir",
                "sr.tglresep",
                'sr.ruanganfk',
                "sr.noresep",
                "jer.jeniskemasan",
                "ps.objectkebangsaanfk",
                'pp.ispagi',
                'pp.issiang',
                'pp.issore',
                "ru.namaruangan",
                'pp.ismalam',
                "pp.keteranganpakai",
                "jrc.jenisracikan",
                "sn.satuanresep",
                "pp.aturanpakai",
                "pp.tglkadaluarsa",
                'ap.alamatlengkap',
                'pp.tglpemakaian',
                'pp.rke',
                DB::raw("SUBSTRING(pp.aturanpakai, 1, CHAR_LENGTH(pp.aturanpakai) - 1)"),
                DB::raw("RIGHT(pp.aturanpakai, CHAR_LENGTH(pp.aturanpakai) - 2)")
            )
            ->get();

        //$queries = DB::getQueryLog();
        //print_r($queries);

        //dd($dataRacikan->toSql());

        $racikan = count($dataRacikan) > 0 ? $dataRacikan : 0;

        // if (isset($request['norecpd'])) {
        //     return $this->cetakLabelRacikanResep($request);
        // }
        $profile = Profile::where('id', $this->kdProfile)->first();
        $norec = $request['norec'];
        $orderfk = $request['orderfk'];

        $pageWidth = '500p';
        $produkfk = '';

        if ($request['orderfk']) {
            $dataNonRacikan = collect(DB::select("select
            pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,jer.jeniskemasan,
            pr.namaproduk,ss.satuanstandar,sr.tglresep,ss.id as ssid,ru.namaruangan,
            sr.noresep,pp.jenisracikanfk, ap.alamatlengkap, ps.objectkebangsaanfk,
            pp.tglpemakaian,sr.ruanganfk,
            pp.jumlah,
            CASE 
                WHEN pp.ispagi != 't' THEN ''  
                WHEN ps.objectkebangsaanfk = 1 THEN 'PAGI' 
                ELSE 'MORNING'
            END AS pagi,
            CASE 
                WHEN pp.issiang != 't' THEN ''  
                WHEN ps.objectkebangsaanfk = 1 THEN 'SIANG' 
                ELSE 'AFTERNOON'
            END AS siang,
            CASE 
                WHEN pp.issore != 't' THEN '' 
                WHEN ps.objectkebangsaanfk = 1 THEN 'SORE' 
                ELSE 'EVENING'
            END AS sore,
            CASE 
                WHEN pp.ismalam != 't' THEN ''  
                WHEN ps.objectkebangsaanfk = 1 THEN 'MALAM' 
                ELSE 'NIGHT'
            END AS malam,
            pp.keteranganpakai,sn.satuanresep,
            pp.aturanpakai,
            RIGHT(pp.aturanpakai, CHAR_LENGTH(pp.aturanpakai) - 2) as aturanpakai2,
            pp.tglkadaluarsa
            from strukresep_t as sr
            join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk
            join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
            join jeniskemasan_m AS jer on jer.id = pp.jeniskemasanfk
            join produk_m as pr on pr.id=pp.produkfk
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk
            join pasien_m as ps  on ps.id=pd.nocmfk
            join ruangan_m as ru on ru.id=apd.objectruanganfk
            left join satuanstandar_m as ss on ss.id=pr.objectsatuanstandarfk
            left join satuanresep_m as sn on pp.satuanresepfk =sn.id
            left Join alamat_m as ap on ap.nocmfk = ps.id
            where sr.kdprofile = $this->kdProfile
            and sr.orderfk='$orderfk'
            and pp.jeniskemasanfk = 2
            and ss.id = 501
            "));
        } else {
            $dataNonRacikan = collect(DB::select("select
            pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,
            pr.namaproduk,ss.satuanstandar,sr.tglresep, ru.namaruangan,
            sr.noresep,ss.id as ssid,pp.rke, ps.objectkebangsaanfk,sr.ruanganfk,
            pp.jumlah,
            CASE 
                WHEN pp.ispagi != 't' THEN '' 
                WHEN ps.objectkebangsaanfk = 1 THEN 'PAGI' 
                ELSE 'MORNING'
            END AS pagi,
            CASE 
                WHEN pp.issiang != 't' THEN ''  
                WHEN ps.objectkebangsaanfk = 1 THEN 'SIANG' 
                ELSE 'AFTERNOON'
            END AS siang,
            CASE 
                WHEN pp.issore != 't' THEN '' 
                WHEN ps.objectkebangsaanfk = 1 THEN 'SORE' 
                ELSE 'EVENING'
            END AS sore,
            CASE 
                WHEN pp.ismalam != 't' THEN ''  
                WHEN ps.objectkebangsaanfk = 1 THEN 'MALAM' 
                ELSE 'NIGHT'
            END AS malam,
            jer.jeniskemasan, ap.alamatlengkap,
            pp.tglpemakaian,
            pp.keteranganpakai,sn.satuanresep,
            pp.aturanpakai,
            RIGHT(pp.aturanpakai, CHAR_LENGTH(pp.aturanpakai) - 2) as aturanpakai2,
            pp.tglkadaluarsa,pp.jenisracikanfk
            from strukresep_t as sr
            join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk
            join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
            join jeniskemasan_m AS jer on jer.id = pp.jeniskemasanfk
            join produk_m as pr on pr.id=pp.produkfk
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk
            join pasien_m as ps  on ps.id=pd.nocmfk
            join ruangan_m as ru on ru.id=apd.objectruanganfk
            left join satuanstandar_m as ss on ss.id=pr.objectsatuanstandarfk
            left join satuanresep_m as sn on pp.satuanresepfk =sn.id
            left Join alamat_m as ap on ap.nocmfk = ps.id
            where sr.kdprofile = $this->kdProfile
            and pp.jeniskemasanfk = 2
            and sr.norec='$norec'
            "));
        }
        // return $dataNonRacikan;
        $nonRacikan = count($dataNonRacikan) > 0 ? $dataNonRacikan : 0;
        // return $nonRacikan;
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 323.15, 290.40]);
            $pdf->setPaper([0, 0, 210, 200]);
            $pdf->loadView(
                'report.farmasi.rekap-label-kecil',
                array(
                    'nonRacikan' => $nonRacikan,
                    'racikan' => $racikan,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.rekap-label-kecil',
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }

        // return $dataRacikan->toSql();
    }

    public function apotikCetakNama(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $profile = collect(DB::select("
                select * from profile_m where id = $kdProfile limit 1
            "))->first();

        $norec = $request['norec'];
        $dataReport = collect(DB::select("select
            pd.tglregistrasi, ps.nocm,ps.namapasien,ps.nohp,ps.tgllahir,ps.alamatrmh,sr.noresep,aa.noantri,
            jk.jeniskelamin,ru.namaruangan,pd.noregistrasi,kp.kelompokpasien,aa.jenis,TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') as umur
            from strukresep_t as sr
            join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk and apd.kdprofile =sr.kdprofile
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            join jeniskelamin_m as jk  on jk.id=ps.objectjeniskelaminfk and pd.kdprofile =jk.kdprofile
            left join antrianapotik_t as aa on aa.noresep =sr.noresep  and sr.kdprofile =aa.kdprofile
            join ruangan_m as ru on ru.id =sr.ruanganfk  and sr.kdprofile =ru.kdprofile
            left join kelompokpasien_m as kp on kp.id =pd.objectkelompokpasienlastfk  and pd.kdprofile =kp.kdprofile
            where sr.kdprofile=$kdProfile
            and sr.norec='$norec'
        "))->first();

        $statusonline = "";
        $status = "Kartu ini adalah bukti antrian resep anda";


        $pageWidth = '500p';
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'tglregistrasi' => date('d-M-Y H:i', strtotime($dataReport->tglregistrasi)),
            'noregistrasi' => $dataReport->noregistrasi,
            'norm' => $dataReport->nocm,
            'umur' => $dataReport->umur,
            'alamatrmh' => $dataReport->alamatrmh,
            'tgllahir' => $dataReport->tgllahir,
            'nohp' => $dataReport->nohp,
            'namapasien' => $dataReport->namapasien,
            'jeniskelamin' => $dataReport->jeniskelamin,
            'ruangan' => $dataReport->namaruangan,
            'noantrian' => $dataReport->jenis . '-' . $dataReport->noantri,
            'kelompokpasien' => $dataReport->kelompokpasien,
            'status' => $status,
        );


        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 327.15, 290.40]);
            $pdf->loadView(
                'report.farmasi.cetak-nama-pasien',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.antrian-apotik',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakAntrianKiosk(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $norec = $request['norec'];
        $TglAwal = date('Y-m-d ') . '00:00';
        $TglAkhir = date('Y-m-d ') . '23:59';
        $tglAyeuna = date('Y-m-d H:i:s');
        $profile = collect(DB::select("
            select * from profile_m where id = $kdProfile AND statusenabled = true limit 1
            "))->first();

        $registrasi = DB::table('pasiendaftar_t AS pd')
            ->Join('antrianpasiendiperiksa_t as apdp', 'apdp.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('alamat_m as ap', 'ap.nocmfk', '=', 'ps.id')
            ->leftJoin('kebangsaan_m as kbg', 'ps.objectkebangsaanfk', '=', 'kbg.id')
            ->leftJoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->leftJoin('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftJoin('pegawai_m as pp', 'pd.objectpegawaifk', '=', 'pp.id')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftJoin('antrianpasienregistrasi_t as apr', 'apr.norec', '=', 'pd.antrianpasienregistrasifk')
            ->select(DB::raw("pd.noregistrasi,ps.nocm,ps.tgllahir,ps.namapasien,to_char(pd.tglregistrasi, 'DD-MM-YYYY HH:mm') AS tglregistrasi,jk.reportdisplay AS jk,
                     ap.alamatlengkap,ap.mobilephone2,ru.namaruangan AS ruanganperiksa,pp.namalengkap AS namadokter, kbg.name as kebangsaan, pa.nosep,
                     kp.kelompokpasien,apdp.noantrian,pd.statuspasien,apr.noreservasi,CASE WHEN apr.tanggalreservasi IS NULL THEN ''
                     ELSE to_char(apr.tanggalreservasi, 'DD-MM-YYYY HH:mm') END AS tanggalreservasi, TO_CHAR(age(ps.tgllahir), 'YY thn MM bln DD hr') as umur"))
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->orWhere('pd.norec', $request['norec_pd'])
            ->first();


        $antrian = DB::table('antrianpasienregistrasi_t AS apr')
            ->leftJoin('ruangan_m AS ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', '=', 'apr.objectpegawaifk')
            ->leftJoin('pasien_m AS ps', 'ps.id', '=', 'apr.nocmfk')
            ->select(DB::raw("apr.*, ps.namapasien as namalengkap, ps.nocm, CASE WHEN ru.namaruangan IS NULL
                                        THEN '' ELSE ru.namaruangan END AS namaruangan, pg.namalengkap as dpjp"))
            ->where('apr.norec', '=', $norec)
            ->first();

        $str = $antrian->jenis;
        $jmlAntrian = DB::table('antrianpasienregistrasi_t')
            ->select(DB::raw("count(noantrian) as jmlantri"))
            ->where('statuspanggil', '=', 0)
            ->whereBetween('tanggalreservasi', [$TglAwal, $TglAkhir])
            ->where('jenis', '=', $str)
            ->first();

        $noAntrian = $antrian->noantrian;
        $strJenis = $antrian->jenis;
        $jenis = "";
        if ($antrian->nobpjs == null) {
            $jenis = "NON BPJS";
        } else {
            $jenis = "BPJS";
        }
        if (strlen($antrian->noantrian) == 1) {
            $noAntrian = "00" . $antrian->noantrian;
        } else {
            $noAntrian = "0" . $antrian->noantrian;
        }

        $tanggalcetak = $this->tanggal_indonesia(date('Y-m-d', strtotime($tglAyeuna)));
        $hari = $this->hari_indonesia(date('Y-m-d', strtotime($tglAyeuna)));
        ;
        //dd($hari);

        //dd($registrasi);

        $pageWidth = 250;
        if ($registrasi != null) {
            $dataReport = array(
                'namaprofile' => $profile->namalengkap,
                'alamat' => $profile->alamatlengkap,
                'fixedphone' => $profile->fixedphone,
                'faksimile' => $profile->faksimile,
                'jenis' => $jenis,
                'noantrian' => $strJenis . "" . $noAntrian,
                'jmlantrian' => $jmlAntrian->jmlantri,
                // 'pageWidth'  => 365,
                'pageWidth' => 455,
                'tanggal' => $tglAyeuna,
                'hari' => $hari,
                'tanggalcetak' => $tanggalcetak,
                'namapasien' => $antrian->namapasien,
                'namalengkap' => $antrian->namalengkap,
                'dpjp' => $antrian->dpjp,
                'nocm' => $antrian->nocm,
                'tipepasien' => $antrian->tipepasien,
                'kode' => $antrian->jenis,
                'nobpjs' => $antrian->nobpjs,
                'namaruangan' => $antrian->namaruangan,
                'loket' => $antrian->loketkiosk,
                'tglregistrasi' => $registrasi->tglregistrasi,
                'noregistrasi' => $registrasi->noregistrasi,
                'kebangsaan' => $registrasi->kebangsaan,
                'norm' => $registrasi->nocm,
                'tgllahir' => $registrasi->tgllahir,
                'namapasien' => $registrasi->namapasien,
                'jeniskelamin' => $registrasi->jk,
                'alamatlengkap' => $registrasi->alamatlengkap,
                'mobilephone2' => $registrasi->mobilephone2,
                'ruangan' => $registrasi->ruanganperiksa,
                'namadokter' => $registrasi->namadokter,
                'namaruangan' => $registrasi->ruanganperiksa,
                'kelompokpasien' => $registrasi->kelompokpasien,
                'noantrianpoli' => $registrasi->noantrian,
                'statuspasien' => $registrasi->statuspasien,
                'umur' => $registrasi->umur,
                'noreservasi' => $registrasi->noreservasi,
                'tanggalreservasi' => $registrasi->tanggalreservasi,
                'isregistrasi' => true,
                'nosep' => $registrasi->nosep
            );
        } else {
            $dataReport = array(
                'namaprofile' => $profile->namalengkap,
                'alamat' => $profile->alamatlengkap,
                'fixedphone' => $profile->fixedphone,
                'faksimile' => $profile->faksimile,
                'jenis' => $jenis,
                'noantrian' => $strJenis . "" . $noAntrian,
                'jmlantrian' => $jmlAntrian->jmlantri,
                // 'pageWidth'  => 365,
                'pageWidth' => 455,
                'tanggal' => $tglAyeuna,
                'hari' => $hari,
                'kelompokpasien' => 'BPJS',
                'tanggalcetak' => $tanggalcetak,
                'namapasien' => $antrian->namapasien,
                'namalengkap' => $antrian->namalengkap,
                'noreservasi' => $antrian->noreservasi,
                'dpjp' => $antrian->dpjp,
                'nocm' => $antrian->nocm,
                'tipepasien' => $antrian->tipepasien,
                'kode' => $antrian->jenis,
                'nobpjs' => $antrian->nobpjs,
                'namaruangan' => $antrian->namaruangan,
                'loket' => $antrian->loketkiosk,
                'isregistrasi' => false,
                'nosep' => ''
            );
        }

        //var_dump($dataReport);


        //        $pdf = PDF::loadView('report.pendaftaran.antrian', array(
        //                'dataReport' => $dataReport,
        //        ));
        //        return $pdf->stream();

        if (isset($request['pdf']) && $request['pdf'] == 'true') {
            return view(
                'report.registrasi.antrian',
                compact('dataReport', 'pageWidth', 'profile')
            );
        } else {
            return view(
                'report.registrasi.antrian',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function hari_indonesia($tanggal)
    {
        //dd(date('l',strtotime($tanggal)));

        $hari = date('l', strtotime($tanggal));
        $namahari = '';
        if ($hari == "Sunday") {
            $namahari = 'Minggu';
        } else if ($hari == "Monday") {
            $namahari = 'Senin';
        } else if ($hari == "Tuesday") {
            $namahari = 'Selasa';
        } else if ($hari == "Wednesday") {
            $namahari = 'Rabu';
        } else if ($hari == "Thursday") {
            $namahari = 'Kamis';
        } else if ($hari == "Friday") {
            $namahari = 'Jumat';
        } else if ($hari == "Saturday") {
            $namahari = 'Sabtu';
        }

        return $namahari;
    }

    public function tanggal_indonesia($tanggal)
    {
        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $pecahkan = explode('-', $tanggal);

        return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public function cetakBuktiPendaftaran(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $profile = collect(DB::select("
                 select * from profile_m where id = $kdProfile limit 1
            "))->first();
        // dd($request['norec_pd']);
        $registrasi = DB::table('pasiendaftar_t AS pd')
            ->Join('antrianpasiendiperiksa_t as apdp', 'apdp.noregistrasifk', '=', 'pd.norec')
            ->Join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('alamat_m as ap', 'ap.nocmfk', '=', 'ps.id')
            ->leftJoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->leftJoin('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftJoin('pegawai_m as pp', 'pd.objectpegawaifk', '=', 'pp.id')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftJoin('kebangsaan_m as kb', 'ps.objectkebangsaanfk', 'kb.id')
            ->leftJoin('antrianpasienregistrasi_t as apr', 'apr.norec', '=', 'pd.antrianpasienregistrasifk')
            ->select(DB::raw("pd.noregistrasi,ps.nocm,ps.tgllahir,ps.namapasien,to_char(pd.tglregistrasi, 'DD-MM-YYYY HH:mm') AS tglregistrasi,jk.reportdisplay AS jk,
                     ap.alamatlengkap,ap.mobilephone2,ru.namaruangan AS ruanganperiksa,pp.namalengkap AS namadokter, pd.tglregistrasi::time AS jamregistrasi,
                     kp.kelompokpasien,apdp.noantrian,pd.statuspasien,apr.noreservasi,CASE WHEN apr.tanggalreservasi IS NULL THEN ''
                     ELSE to_char(apr.tanggalreservasi, 'DD-MM-YYYY HH:mm') END AS tanggalreservasi, TO_CHAR(age(ps.tgllahir), 'YY thn MM bln DD hr') as umur,
                     kb.name as kebangsaan"))
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->orWhere('pd.norec', $request['norec_pd'])
            ->first();



        $statusonline = "";
        $status = "";
        if ($registrasi->tanggalreservasi != '') {
            $statusonline = "PASIEN ONLINE";
            $status = "Kartu ini adalah bukti anda mendaftar hari ini";
        } else {
            $statusonline = "Kartu ini adalah bukti anda mendaftar hari ini";
        }
        $pageWidth = 365;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'email' => $profile->alamatemail,
            'tglregistrasi' => $registrasi->tglregistrasi,
            'jamregistrasi' => $registrasi->jamregistrasi,
            'noregistrasi' => $registrasi->noregistrasi,
            'norm' => $registrasi->nocm,
            'tgllahir' => $registrasi->tgllahir,
            'namapasien' => $registrasi->namapasien,
            'jeniskelamin' => $registrasi->jk,
            'alamatlengkap' => $registrasi->alamatlengkap,
            'mobilephone2' => $registrasi->mobilephone2,
            'ruangan' => $registrasi->ruanganperiksa,
            'namadokter' => $registrasi->namadokter,
            'namaruangan' => $registrasi->ruanganperiksa,
            'kelompokpasien' => $registrasi->kelompokpasien,
            'noantrian' => $registrasi->noantrian,
            'statuspasien' => $registrasi->statuspasien,
            'umur' => $registrasi->umur,
            'noreservasi' => $registrasi->noreservasi,
            'tanggalreservasi' => $registrasi->tanggalreservasi,
            'statusonline' => $statusonline,
            'status' => $status,
            'kebangsaan' => $registrasi->kebangsaan
        );

        if (isset($request['pdf']) && $request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.registrasi.buktipendaftaran-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'dataReport' => $dataReport,

                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.registrasi.buktipendaftaran-dom',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakSuratKeteranganSehat(Request $request)
    {
        $noreg = $request['noregistrasi'];
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $profile->id);
        $pageWidth = 950;
        $identitas = collect(DB::select("
            SELECT
            sk.*,
            pg2.namalengkap as namapembuat,
            jp.jenispegawai as jabatan,
            pm.namapasien,
            pm.nocm,
            pm.noidentitas,
            pm.tempatlahir,
            pm.tgllahir,
            jk.jeniskelamin,
            pd.tglregistrasi,
            al.alamatlengkap,
            pj.pekerjaan,
            pg.namalengkap as dokterdpjp,
            pg.nosip,
            ru.namaruangan,
            pd.tglregistrasi,
            pd.tglpulang,
            pg.nip
            FROM suratketerangan_t sk
            INNER JOIN pasiendaftar_t pd on pd.norec = sk.pasiendaftarfk and pd.kdprofile = sk.kdprofile
            INNER JOIN pasien_m pm on pm.id = pd.nocmfk and pm.kdprofile = pd.kdprofile
            LEFT JOIN jeniskelamin_m jk on pm.objectjeniskelaminfk = jk.id and jk.kdprofile = pm.kdprofile
            LEFT JOIN pekerjaan_m pj on pj.id = pm.objectpekerjaanfk and pj.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m al on pm.id = al.nocmfk and al.kdprofile = pm.kdprofile
            LEFT JOIN pegawai_m pg on pg.id = sk.dokterfk and pg.kdprofile = sk.kdprofile
            LEFT JOIN pegawai_m pg2 on pg2.id = sk.pegawaifk and pg2.kdprofile = sk.kdprofile
            LEFT JOIN jenispegawai_m jp on jp.id = pg2.objectjenispegawaifk and jp.kdprofile = pg2.kdprofile
            LEFT JOIN ruangan_m ru on ru.id = pd.objectruanganlastfk and ru.kdprofile = pd.kdprofile
            WHERE pd.noregistrasi = ?
            AND sk.jenissuratfk = ?
            and pd.kdprofile = ?
        ", [$noreg, $kdJenisSurat, $this->kdProfile]))->first();

        //1. buat inisial nama pasien terlebih dahulu
        $exp = explode(' ', $identitas->namapasien);
        $alfa = '';

        for ($i = 0; $i < count($exp); $i++) {
            $space = (count($exp) == $i ? '' : ' ');
            $alfa .= $exp[$i][0] . $space;
        }
        //2. buat data qr terlebih dahulu untuk mengubah menjadi string qr
        $dataQR = [
            "nocm" => $identitas->nocm,
            "inisial" => $alfa,
            "namaruangan" => $identitas->namaruangan,
            "dpjp" => $identitas->dokterdpjp,
            "tglberkunjung" => date('Y-m-d', strtotime($identitas->tglregistrasi)),
            "tglpulang" => date('Y-m-d', strtotime($identitas->tglpulang)),
            "type" => "SuratKeteranganSehat"
        ];

        // $stringQR = $collection . ';' . $data['_id'];
        $stringQR = $dataQR['nocm'] . ';' . $dataQR['inisial'] . ';' . $dataQR['namaruangan'] . ';' . $dataQR['dpjp'] . ';' . $dataQR['tglberkunjung'] . ';' . $dataQR['tglpulang'] . ';SuratKeteranganSehat';

        //3. encrypt string qr
        $encryptQR = base64_encode($stringQR);
        $pasien = $dataQR;
        $pasien['tte'] = route('dokumen.signature.nocollection') . '?key=' . $encryptQR;

        //4. generate qr to image
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);

        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);

        $qrcode = base64_encode($resultBarcode->getString());

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $identitas,
            'tte' => $qrcode,
        );
        // return $dataReport;
        return view(
            'report.pendaftaran.suratsehat',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakLembarRawatInap(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->select(
                'pd.noregistrasi as  noreg',
                'ps.namapasien as nama',
                'rk.desakelurahan as kelurahan',
                'rk.kecamatan',
                'rk.kotakabupaten',
                'rk.alamatlengkap',
                'kp.kelompokpasien',
                'ps.nocm',
                'pd.*'

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.norec', $request['noregistrasi'])
            ->first();

        $diagnosaX = DB::table('detaildiagnosapasien_t as ddp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ddp.noregistrasifk')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'jd.jenisdiagnosa',
                'ddp.keterangan',
                DB::raw("'ICD X' as jenis")
            )
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $data->norec)
            ->orderByDesc('ddp.tglinputdiagnosa')
            ->get();
        $diagnosaIX = DB::table('detaildiagnosatindakanpasien_t as ddt')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ddt.noregistrasifk')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->select(
                'dt.kddiagnosatindakan as kddiagnosa',
                'dt.namadiagnosatindakan as namadiagnosa',
                'ddt.keterangantindakan as keterangan',
                'ddt.tglinputdiagnosa',
                DB::raw("'ICD IX' as jenis, null as jenisdiagnosa")
            )
            ->where('ddt.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $data->norec)
            ->orderByDesc('ddt.tglinputdiagnosa')
            ->get();

        $diagnosa = array_merge($diagnosaX->toArray(), $diagnosaIX->toArray());


        $pageWidth = 890;
        $blade = 'report.pendaftaran.cetak-lemabar-rawat-inap';
        $res['profile'] = Profile::where('id', $this->getDataKdProfile($request))->first();
        $res['pdf'] = 'true';
        $res['storage'] = true;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            $blade,
            array(
                'data' => $data,
                'res' => $res,
                'pageWidth' => $pageWidth,
                'penaggungJawab' => $request->penaggungJawab,
                'alamat' => $request->alamat,
                'diagnosa' => $diagnosa
            )
        );
        return $pdf->stream();
    }

    public static function getUmurna($tgllahir, $tglregis)
    {
        $data = DB::select(DB::raw("
            SELECT
            EXTRACT (YEAR FROM AGE('$tglregis', '$tgllahir' )) || ' Tahun ' as thnumur,
            EXTRACT (MONTH  FROM AGE('$tglregis', '$tgllahir' )) || ' Bulan ' as blnumur,
            EXTRACT (DAY  FROM  AGE('$tglregis', '$tgllahir' )) || ' Hari' as hrumur
        "));
        $res['umurtahun'] = $data[0]->thnumur;
        $res['umurbulan'] = $data[0]->blnumur;
        $res['umurhari'] = $data[0]->hrumur;
        return $res;
    }

    public function cetakGelangPasien(Request $request)
    {
        $kdProfile = (int) $this->getDataKdProfile($request);
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m AS jk', function ($join) {
                $join->on('jk.id', '=', 'pm.objectjeniskelaminfk')
                    ->on('jk.kdprofile', '=', 'pm.kdprofile');
            })
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'jk.reportdisplay as jeniskelamin',
                'pm.tgllahir',
                'pd.tglregistrasi'

            )
            ->where('pd.noregistrasi', '=', $request['noregistrasi'])
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $pageWidth = 50;
        $height = 600;
        $blade = 'report.pendaftaran.gelangpasien';
        $pdf = App::make('dompdf.wrapper');
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 200, 80]);
            $pdf->loadView(
                $blade,
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'data' => $data
                )
            );
            // $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.gelangpasien',
                compact('res', 'pageWidth', 'data')
            );
        }
    }

    public function cetakSuratKematian(Request $request)
    {

        $raw = DB::table('pasiendaftar_t as ep')
            ->join('pasien_m AS ps', 'ps.id', '=', 'ep.nocmfk')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'ep.norec')
            ->join('suratketerangan_t as sk', 'sk.pasiendaftarfk', '=', 'ep.norec')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'ep.objectruanganlastfk')
            ->leftJoin('pegawai_m as pgl', 'pgl.id', '=', 'ep.objectpegawaifk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('penyebabkematian_m  as pk', 'pk.id', 'ep.objectpenyebabkematianfk')
            ->where('ep.norec', $request['norec'])
            ->select(
                'ps.namapasien',
                'ep.noregistrasi',
                'ps.noidentitas',
                'ps.tempatlahir',
                "ps.tgllahir AS tgllahir",
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'kbs.name',
                'sk.nosurat',
                'sk.nosint',
                'ps.tglmeninggal',
                'ru.namaruangan',
                'sk.diagnosa',
                'sk.keterangan',
                'ep.tglregistrasi',
                'sk.nosurat',
                'pgl.namalengkap',
                'pk.reportdisplay as kematian'
            )
            ->orderBy('sk.tglsurat', 'DESC')
            ->first();

        $date1 = new DateTime($raw->tglmeninggal);
        $date2 = new DateTime($raw->tglregistrasi);

        $interval = $date1->diff($date2);
        $res['pdf'] = 'true';
        $res['storage'] = true;
        $pageWidth = 780;
        $tgl_lahir = Carbon::parse($raw->tgllahir);
        $umur = $tgl_lahir->diff(Carbon::now());

        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($raw->namalengkap) {
            $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($raw->namalengkap ?? ""));
        } else {
            $qrcode = null;
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.pendaftaran.suratkematian',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'raw' => $raw,
                    'umur' => $umur,
                    'interval' => $interval,
                    'qrcode' => $qrcode
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.suratkematian',
                compact('res', 'pageWidth', 'raw', 'interval', 'umur', 'qrcode')
            );
        }
    }
    public function cetakSuratMeninggal(Request $request)
    {

        $norec_sk = $request['noregistrasi'];
        $type = $request->type ?? "LABORATORIUM";

        $raw = DB::table('pasiendaftar_t as ep')
            ->join('pasien_m AS ps', 'ps.id', '=', 'ep.nocmfk')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'ep.norec')
            ->join('suratketerangan_t as sk', 'sk.pasiendaftarfk', '=', 'ep.norec')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'ep.objectruanganlastfk')
            ->leftJoin('pegawai_m as pgl', 'pgl.id', '=', 'ep.objectpegawaifk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->where('ep.norec', $request['norec'])
            ->select(
                'ps.namapasien',
                'ep.noregistrasi',
                'ps.noidentitas',
                'ps.tempatlahir',
                "ps.tgllahir AS tgllahir",
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'kbs.name',
                'sk.nosurat',
                'sk.nosint',
                'ps.tglmeninggal',
                'ru.namaruangan',
                'sk.diagnosa',
                'sk.keterangan',
                'ep.tglregistrasi',
                'sk.nosurat',
                'pgl.namalengkap'
            )
            ->orderBy('sk.tglsurat', 'DESC')
            ->first();

        $tgl_lahir = Carbon::parse($raw->tgllahir);
        $umur = $tgl_lahir->diff(Carbon::now());

        if ($raw->namalengkap) {
            $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($raw->namalengkap ?? ""));
        } else {
            $qrcode = null;
        }
        $pageWidth = 780;

        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.pendaftaran.suratmeninggal',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'raw' => $raw,
                    'umur' => $umur,
                    'qrcode' => $qrcode
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.suratmeninggal',
                compact('res', 'pageWidth', 'raw', 'umur', 'qrcode')
            );
        }
    }
    public function cetakOrder(Request $request)
    {
        $norec = $request->noregistrasi;
        $type = strtoupper($request->type) ?? "LABORATORIUM";
        $datas = DB::table('strukorder_t as so')
            // ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
            ->join('pelayananpasien_t as pp','pp.strukorderfk','=','so.norec')
            ->join('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', 'so.objectpegawaiorderfk')
            ->leftJoin('alamat_m AS al', 'al.nocmfk', 'ps.id')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', 'so.norec')
            ->select(
                'so.noorder',
                'so.tglorder',
                'ps.nocm',
                'pp.hargasatuan',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.email',
                'al.alamatlengkap',
                'ps.nohp',
                'ru.namaruangan AS ruanganasal',
                'kp.kelompokpasien',
                'pr.namaproduk AS pemeriksaan',
                'pg.namalengkap AS pengorder',
                'pg.notlp AS tlpdokter',
                'pg.alamat AS alamatdokter',
                'ps.penanggungjawab'
            )
            ->where('so.norec', $norec)
            ->distinct()
            ->get();


        if($datas->isEmpty()){
            $datas=DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd','pd.nocmfk','=','ps.id')
            ->join('strukorder_t as so','so.noregistrasifk','=','pd.norec')
            ->join('orderpelayanan_t as op','op.strukorderfk','=','so.norec')
            ->join('produk_m as pr','pr.id','=','op.objectprodukfk')
            ->join('ruangan_m as ru','ru.id','=','so.objectruanganfk')
            ->leftjoin('pegawai_m as pg','pg.id','=','so.objectpegawaiorderfk')
            ->leftjoin('alamat_m as alm','alm.nocmfk','=','ps.id')
            ->leftjoin('kelompokpasien_m as klm','klm.id','=','pd.objectkelompokpasienlastfk')
            ->select(
                'so.noorder',
                'so.tglorder',
                'ps.nocm',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.email',
                'alm.alamatlengkap',
                'ps.nohp',
                'ru.namaruangan as ruanganasal',
                'klm.kelompokpasien',
                'pr.namaproduk as pemeriksaan',
                'pg.namalengkap as pengorder',
                'pg.notlp as tglpdokter',
                'pg.alamat as alamatdokter',
                'ps.penanggungjawab'
            )
            ->where('so.norec',$norec)
            ->distinct()
            ->get();
        }

        $pageWidth = 780;
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.pendaftaran.cetak-order',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'datas' => $datas,
                    'type' => $type
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.cetak-order',
                compact('res', 'pageWidth', 'type', 'datas')
            );
        }
    }

    public function cetakLabel(Request $request)
    {
        $norec = $request->noregistrasi;
        $israd = $request->has('dariradiologi');
        $datas = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
            ->join('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', 'so.objectpegawaiorderfk')
            ->leftJoin('alamat_m AS al', 'al.nocmfk', 'ps.id')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', 'so.norec')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', 'ps.objectkebangsaanfk')
            ->select(
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan DD hari') as umur"),
                'pg.namalengkap as dokterorder',
                'ru.namaruangan as ruanganasal',
                'kp.kelompokpasien',
                'kbs.name as kebangsaan',
                DB::raw("STRING_AGG(pr.namaproduk, ';') as modality"),
            )
            ->where('so.norec', $norec)
            ->groupBy(
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan DD hari')"),
                'pg.namalengkap',
                'ru.namaruangan',
                'kp.kelompokpasien',
                'kbs.name',
            )
            ->get();
        // dd($datas);

        $pageWidth = 850;
        return view(
            'report.pendaftaran.cetak-label',
            compact('pageWidth', 'datas','israd')
        );
    }

    public function getDataKdProfile(Request $request)
    {
        $dataLogin = $request->all();
        $idUser = $dataLogin['userData']['id'];
        $data = LoginUser::where('id', $idUser)->first();
        if (!empty($data)) {
            $idKdProfile = (int) $data->kdprofile;
            $Query = DB::table('profile_m')
                ->where('id', '=', $idKdProfile)
                ->first();
            $Profile = $Query;
            return (int) $Profile->id;
        } else {
            $data = Pasien::where('id', $idUser)->first();
            if (!empty($data)) {
                $idKdProfile = (int) $data->kdprofile;
                $Query = DB::table('profile_m')
                    ->where('id', '=', $idKdProfile)
                    ->first();
                $Profile = $Query;
                return (int) $Profile->id;
            } else {
                return null;
            }
        }
    }
    public function cetakLabelTindakan(Request $request)
    {
        $norec = $request->norec;
        $type = $request->type;
        $rad= $request->has($request['cetakanradiologi']);
        $kdProfile = (int) $this->kdProfile;
        $datas = DB::table('pelayananpasien_t AS pp')
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'pr.namaproduk AS pemeriksaan',
                'pg.namalengkap AS dokter',
                'pm.tgllahir AS  tgllahir',
                'apd.tglmasuk AS tglmasuk',
                'ru.objectdepartemenfk',
            )
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('pasien_m AS pm', 'pm.id', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'pd.objectdokterpemeriksafk')
            ->when($type, function ($query) use ($type) {
                if ($type == "radiologi") {
                    return $query->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'));
                } else {
                    return $query->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'));
                }
            })
            ->where('apd.noregistrasifk', $norec)
            ->where('pp.statusenabled',true)
            ->get();
        $pageWidth = 780;
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.pendaftaran.label-tindakan',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'datas' => $datas

                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.label-tindakan',
                compact('res', 'pageWidth', 'datas','rad')
            );
        }
    }
    public function getDataWaktuMinum(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $Norec = $request['Norec_sr'];
        $data = DB::select(
            DB::raw("SELECT  CASE WHEN pp.ispagi = true THEN 'Pagi : 07:00 - 07:30' ELSE '-' END AS pagi,
                             CASE WHEN pp.issiang = true THEN 'Siang : 13:00 - 13:30' ELSE '-' END AS siang,
                             CASE WHEN pp.issore = true THEN 'Sore : 13:00 - 13:30' ELSE '-' END AS sore,
                             CASE WHEN pp.ismalam = true THEN 'Malam : 19:00 - 20:00' ELSE '-' END AS malam
                             FROM pelayananpasien_t AS pp
                             INNER JOIN strukresep_t AS sr ON sr.norec = pp.strukresepfk
                             WHERE pp.kdprofile = ? and pp.jeniskemasanfk = 2 AND sr.norec = ?

                             UNION ALL
                             SELECT
                             CASE WHEN pp.ispagi = true THEN 'Pagi : 07:00 - 07:30' ELSE '-' END AS pagi,
                             CASE WHEN pp.issiang = true THEN 'Siang : 13:00 - 13:30' ELSE '-' END AS siang,
                             CASE WHEN pp.issore = true THEN 'Sore : 13:00 - 13:30' ELSE '-' END AS sore,
                             CASE WHEN pp.ismalam = true THEN 'Malam : 19:00 - 20:00' ELSE '-' END AS malam

                             FROM strukresep_t AS sr
                             INNER JOIN pelayananpasien_t AS pp ON sr.norec = pp.strukresepfk
                             WHERE sr.kdprofile = ? and  pp.jeniskemasanfk = 1 AND sr.norec = ? "),
            [$idProfile, $Norec, $idProfile, $Norec]
        );
        return $this->respond($data);
    }

    public function apotikRekapLabel(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];
        $waktuMinum = explode(',', $request['waktuMinum']);
        $profile = collect(DB::select("
            select * from profile_m where id = $kdProfile limit 1
        "))->first();
        $pageWidth = 400;
        $produkfk = '';
        if (isset($request['produkfk']) && $request['produkfk'] != '') {
            $produkfk = " and pr.id in ($request[produkfk])";
        }
        $dataReport = collect(DB::select("select
                pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,
                pr.namaproduk,ss.satuanstandar,sr.tglresep,
                pp.jumlah,CASE WHEN pp.ispagi = true THEN 'Pagi : 07:00 - 07:30' ELSE '-' END AS pagi,
                CASE WHEN pp.issiang = true THEN 'Siang : 13:00 - 13:30' ELSE '-' END AS siang,
                CASE WHEN pp.issore = true THEN 'Sore : 13:00 - 13:30' ELSE '-' END AS sore,
                CASE WHEN pp.ismalam = true THEN 'Malam : 19:00 - 20:00' ELSE '-' END AS malam,
                pp.keteranganpakai,sn.satuanresep,pp.aturanpakai
                from strukresep_t as sr
                join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk
                join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
                join produk_m as pr on pr.id=pp.produkfk
                join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk
                join pasien_m as ps  on ps.id=pd.nocmfk
                left join satuanstandar_m as ss on ss.id=pr.objectsatuanstandarfk
                left join satuanresep_m as sn on pp.satuanresepfk =sn.id
                where sr.kdprofile=$kdProfile
                and sr.norec='$norec'
                $produkfk
        "));

        return $dataReport;
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 420.15, 260.50]);
            // $pdf->setOptions(['defaultFont' => 'Arial, Helvetica, sans-serif']);
            $pdf->loadView(
                'report.farmasi.rekap-label',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'waktuMinum' => $waktuMinum,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.rekap-label',
                compact('dataReport', 'pageWidth', 'profile', 'request', 'waktuMinum')
            );
        }
    }
    public function apotikCetakAntrian(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $profile = collect(DB::select("
                select * from profile_m where id = $kdProfile limit 1
            "))->first();

        $norec = $request['norec'];
        $dataReport = collect(DB::select("select
            pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,sr.noresep,aa.noantri,
            jk.jeniskelamin,ru.namaruangan,pd.noregistrasi,kp.kelompokpasien,aa.jenis
            from strukresep_t as sr
            join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk and apd.kdprofile =sr.kdprofile
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            join jeniskelamin_m as jk  on jk.id=ps.objectjeniskelaminfk and pd.kdprofile =jk.kdprofile
            left join antrianapotik_t as aa on aa.noresep =sr.noresep  and sr.kdprofile =aa.kdprofile
            join ruangan_m as ru on ru.id =sr.ruanganfk  and sr.kdprofile =ru.kdprofile
            left join kelompokpasien_m as kp on kp.id =pd.objectkelompokpasienlastfk  and pd.kdprofile =kp.kdprofile
            where sr.kdprofile=$kdProfile
            and sr.norec='$norec'
        "))->first();

        $statusonline = "";
        $status = "Kartu ini adalah bukti antrian resep anda";

        $pageWidth = 365;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'tglregistrasi' => date('d-M-Y H:i', strtotime($dataReport->tglregistrasi)),
            'noregistrasi' => $dataReport->noregistrasi,
            'norm' => $dataReport->nocm,
            'tgllahir' => $dataReport->tgllahir,
            'namapasien' => $dataReport->namapasien,
            'jeniskelamin' => $dataReport->jeniskelamin,
            'ruangan' => $dataReport->namaruangan,
            'noantrian' => $dataReport->jenis . '-' . $dataReport->noantri,
            'kelompokpasien' => $dataReport->kelompokpasien,
            'status' => $status,
        );
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.farmasi.antrian-apotik',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.antrian-apotik',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakSuratKeteranganSakit(Request $request)
    {

        $noreg = $request['noregistrasi'];
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSakit', $profile->kdprofile);
        $pageWidth = 950;

        $identitas = collect(DB::select("
            SELECT
            sk.*,
            pg2.namalengkap as namapembuat,
            jp.jenispegawai as jabatan,
            pm.namapasien,
            pm.nocm,
            pm.noidentitas,
            pm.tempatlahir,
            pm.tgllahir,
            jk.jeniskelamin,
            pd.tglregistrasi,
            al.alamatlengkap,
            pj.pekerjaan,
            sk.diagnosa,
            pg.namalengkap as dokterdpjp,
            pg.nosip,
            ru.namaruangan
            FROM suratketerangan_t sk
            INNER JOIN pasiendaftar_t pd on pd.norec = sk.pasiendaftarfk and pd.kdprofile = sk.kdprofile
            INNER JOIN pasien_m pm on pm.id = pd.nocmfk and pm.kdprofile = pd.kdprofile
            LEFT JOIN jeniskelamin_m jk on pm.objectjeniskelaminfk = jk.id and jk.kdprofile = pm.kdprofile
            LEFT JOIN pekerjaan_m pj on pj.id = pm.objectpekerjaanfk and pj.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m al on pm.id = al.nocmfk and al.kdprofile = pm.kdprofile
            LEFT JOIN pegawai_m pg on pg.id = sk.dokterfk and pg.kdprofile = sk.kdprofile
            LEFT JOIN pegawai_m pg2 on pg2.id = sk.pegawaifk and pg2.kdprofile = sk.kdprofile
            LEFT JOIN jenispegawai_m jp on jp.id = pg2.objectjenispegawaifk and jp.kdprofile = pg2.kdprofile
            LEFT JOIN antrianpasienregistrasi_t apr on apr.norec = pd.antrianpasienregistrasifk
            LEFT JOIN ruangan_m ru on ru.id = apr.objectruanganfk and ru.kdprofile = pd.kdprofile
            WHERE pd.noregistrasi = ?
            AND jenissuratfk = ?
            AND pd.kdprofile = ?
        ", [$noreg, $kdJenisSurat, $this->kdProfile]))->first();
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $identitas,
        );
        // return $identitas;
        $exp = explode(' ', $identitas->namapasien);
        $alfa = '';

        for ($i = 0; $i < count($exp); $i++) {
            $space = (count($exp) == $i ? '' : ' ');
            $alfa .= $exp[$i][0] . $space;
        }
        $dataQR = [
            "nocm" => $identitas->nocm,
            "inisial" => $alfa,
            "namaruangan" => $identitas->namaruangan,
            "dpjp" => $identitas->dokterdpjp,
            "tglberkunjung" => date("Y-m-d", strtotime($identitas->tglawal)),
            "tglpulang" => date("Y-m-d", strtotime($identitas->tglakhir)),
            "type" => "Surat Sakit",
        ];

        $stringQR = $dataQR['nocm'] . ';' . $dataQR['inisial'] . ';' . $dataQR['namaruangan'] . ';' .
            $dataQR['dpjp'] . ';' . $dataQR['tglberkunjung'] . ';' . $dataQR['tglpulang'] . ';' . $dataQR['type'];

        $encryptQR = base64_encode($stringQR);
        $tte = route('dokumen.signature.nocollection') . '?key=' . $encryptQR . '&a=false';

        $canvasPNG = new Png($tte);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(150);

        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);

        $qrcode = base64_encode($resultBarcode->getString());

        return view(
            'report.pendaftaran.suratketerangansakit',
            compact('dataReport', 'pageWidth', 'profile', 'qrcode')
        );
    }
    function getDataLaporanPenerimaanHarianPDF(Request $request)
    {
        ini_set('max_execution_time', 60000);
        $kdProfile = (int)$this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $idKasir = $request['idKasir'];
        $idRuangKasir = $request['ruanganfk'];
        $depID = null;
        $listgdkanker = $this->settingFix('idRuanganKanker');
        $idgdKankerPen = null;
        $idgdKankerDep = null;

        if($request['tipe'] == "KASIR RAWAT JALAN") {
            $idgdKankerPen = "ru2.id NOT IN ($listgdkanker, 322, 323) AND ru2.objectdepartemenfk = 18";
            $idgdKankerDep = "ru.id NOT IN ($listgdkanker, 322, 323) AND ru.objectdepartemenfk = 18";
            $depID = "(18)";
        }else if($request['tipe'] == "KASIR RAWAT INAP") {
            $idgdKankerPen = "ru2.objectdepartemenfk NOT IN (18,9) AND ru2.id NOT IN ($listgdkanker) AND ru2.objectdepartemenfk = 16";
            $idgdKankerDep = "ru.objectdepartemenfk NOT IN (18,9) AND ru.id NOT IN ($listgdkanker) AND ru.objectdepartemenfk = 16";
            $depID = "(16)";
        }else if ($request['tipe'] == "KASIR IGD") {
            $idgdKankerPen = "ru2.objectdepartemenfk NOT IN (18,16) AND ru2.id NOT IN ($listgdkanker) AND ru2.objectdepartemenfk IN (9, 12, 53, 4)";
            $idgdKankerDep = "ru.objectdepartemenfk NOT IN (18,16) AND ru.id NOT IN ($listgdkanker) AND ru.objectdepartemenfk IN (9, 12, 53, 4)";
            $depID = "(9)";
        }else if ($request['tipe'] == "KASIR GEDUNG KANKER") {
            $idgdKankerPen = "ru2.id IN ($listgdkanker)";
            $idgdKankerDep = "ru.id IN ($listgdkanker)";
            $depID = "(18,16)";
        }
        $getpegawai = '';
        $getpegawaiNon = '';
        // 5319
        $pegnone = explode(',',$this->settingFix('idPegawaiNone'));
        if(!in_array($idKasir, $pegnone)) {
            $getpegawai = "AND sp.objectpegawaipenerimafk = $idKasir";
            $getpegawaiNon = "AND sbm.objectpegawaipenerimafk = $idKasir";
        }
        $datanonlayanan = DB::select(DB::raw("SELECT
            sbm.tglsbm::date as tglstruk, sp.namapasien_klien, sp.norec, sp.nostruk, sp.noteleponfaks, 
            kt.kelompoktransaksi AS jenistagihan, sp.keteranganlainnya, sp.nosbklastfk, sp.nosbmlastfk, 
            sp.totalharusdibayar, rk.namarekanan, kp.kelompokpasien, sbm.objectpegawaipenerimafk, kp.id AS kdkelompokpasien,
            CASE
                WHEN cb.ID IN ( 2, 4 ) THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS totalkartukredit,
            CASE    
                WHEN cb.ID IN ( 3, 8, 9, 10, 12, 13, 14, 15 ) THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS totaltransfer,
            CASE    
                WHEN cb.ID = 1 THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS totaltunai,
            CASE
                WHEN cb.ID = 17 THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS totaliks
        FROM
            strukpelayanan_t AS sp
            INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sp.objectkelompoktransaksifk
            LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.id = sp.objectkelompokpasienfk
            LEFT JOIN rekanan_m AS rk ON rk.id = sp.objectrekananfk 
            LEFT JOIN strukbuktipenerimaan_t as sbm ON sbm.nostrukfk = sp.norec
            LEFT JOIN strukbuktipenerimaancarabayar_t AS sbmcr ON sbmcr.nosbmfk = sbm.norec
            LEFT JOIN carabayar_m AS cb ON cb.id = sbmcr.objectcarabayarfk
        WHERE
            sp.statusenabled = true
            AND sp.totalharusdibayar IS NOT NULL 
            AND sp.kdprofile = 1 
            AND sp.objectkelompoktransaksifk IN ( 4, 1, 5, 99, 433 )
            AND sbm.tglsbm :: DATE >= '$tglAwal' 
            AND sbm.tglsbm :: DATE <= '$tglAkhir'
            AND (
                sbm.ruanganfk is not null and sbm.ruanganfk = $idRuangKasir
            )
            $getpegawaiNon"));
        // return $datanonlayanan;
        $views = 'report.kasir.laporan-penerimaan-kasir-harian'; 

        $datadeposit = DB::table('strukpelayanan_t AS sp')
        ->join('pasiendaftar_t AS pd', 'sp.noregistrasifk', '=', 'pd.norec')
        ->join('pasien_m AS pas', 'pas.id', '=', 'pd.nocmfk')
        ->join('strukbuktipenerimaan_t AS sbm', 'sbm.nostrukfk', '=', 'sp.norec')
        ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
        ->leftjoin('carabayar_m AS cb', 'cb.id', '=', 'sbmcr.objectcarabayarfk')
        ->join('pegawai_m AS pg', 'pg.id', '=', 'sbm.objectpegawaipenerimafk')
        ->leftjoin('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->leftjoin('departemen_m AS dept', 'dept.id', '=', 'ru.objectdepartemenfk')
        ->leftjoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->select(DB::raw("pas.namapasien, 
            case when pas.objectkebangsaanfk = 1 then 'WNI' else 'WNA' end as kelompokpasien, pas.nocm, sbm.tglsbm::date as tglsbm,
            case when cb.id in (2, 4) then SUM ( sbmcr.totaldibayar ) else 0 end AS totalkartukredit, 
            case when cb.id in ( 3, 8, 9, 10, 12, 13, 14, 15 ) then SUM ( sbmcr.totaldibayar ) else 0 end AS totaltransfer, 
            case when cb.id = 1 then SUM ( sbmcr.totaldibayar ) else 0 end AS totaltunai, 
            case when cb.id = 17 then SUM ( sbmcr.totaldibayar ) else 0 end AS totaliks,
            pd.noregistrasi, pd.norec AS norec_pd, pas.nocm"))
        ->where('pd.statusenabled', true)
        ->where('sp.statusenabled', true)
        ->where('sbm.statusenabled', true);

        if(isset($request['tipe']) && $request['tipe'] != null) {
            $datadeposit = $datadeposit->where(function ($query) use ($idRuangKasir, $idgdKankerDep) {
                $query->whereNotNull('sbm.ruanganfk')
                      ->where('sbm.ruanganfk', '=', $idRuangKasir)
                      ->orWhere(function ($query) use ($idgdKankerDep) {
                          $query->whereNull('sbm.ruanganfk')
                                ->whereRaw($idgdKankerDep);
                      });
            });
            // $datadeposit = $datadeposit->whereRaw("case when sbm.ruanganfk is not null then sbm.ruanganfk = $idRuangKasir ELSE
            // $idgdKankerDep END");
        }
        $datadeposit = $datadeposit->where('sp.objectkelompoktransaksifk', 46);
        if($getpegawai !== '') {
            $datadeposit = $datadeposit->where('sp.objectpegawaipenerimafk', $idKasir);
        }
        $datadeposit = $datadeposit->whereDate('sbm.tglsbm', '>=', $tglAwal)
        ->whereDate('sbm.tglsbm', '<=', $tglAkhir)
        ->where('pd.kdprofile', $kdProfile)
        // ->where('ru.objectdepartemenfk', $tipees)
        ->groupBy(DB::raw("pas.namapasien, kp.kelompokpasien, pas.nocm, kp.id, pas.objectkebangsaanfk,
            sbm.tglsbm::date, pd.noregistrasi, pd.norec, cb.id"))
        ->orderBy('pas.objectkebangsaanfk', 'desc')
        ->orderBy('kp.id', 'desc');
        $datadeposit = $datadeposit->get();
        // return $datadeposit;
        
        $datapiutang = DB::table('pasiendaftar_t AS pd')
        ->join('antrianpasiendiperiksa_t AS ap', 'pd.norec', '=', 'ap.noregistrasifk')
        ->join('pelayananpasien_t AS pp', 'ap.norec', '=', 'pp.noregistrasifk')
        ->leftjoin('strukpelayanan_t AS sp', 'pp.strukfk', '=', 'sp.norec')
        ->join('pasien_m AS p', 'p.id', '=', 'pd.nocmfk')
        ->join('jeniskelamin_m AS jk', 'jk.id', '=', 'p.objectjeniskelaminfk')
        ->join('statuspiutang_m AS stp', 'stp.id', '=', 'pd.objectstatuspiutangfk')
        ->leftjoin('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->leftjoin('departemen_m AS dept', 'dept.id', '=', 'ru.objectdepartemenfk')
        ->leftjoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sbm.nostrukfk', '=', 'sp.norec')
        ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
        ->leftjoin('carabayar_m as cb', 'cb.id', '=', 'sbmcr.objectcarabayarfk')
        ->select(DB::raw("
            kp.kelompokpasien, sbm.tglsbm::date tglsbm, pd.noregistrasi, pd.tglregistrasi, p.nocm, p.namapasien,
            SUM ( sbm.totaldibayar ) AS totaldibayar, 
            pd.norec AS norec_pd,
            sp.norec, pd.tglpulang, pd.nocmfk, pd.nostruklastfk, pd.nosbmlastfk, kp.id as jaminbayarid,
            case when p.objectkebangsaanfk = 1 then 'WNI' else 'WNA' end as kelompokpasien, stp.id as piutangid,
            CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) as totaldibayarcr
        "))
        ->where('pd.statusenabled', true)
        ->where('sp.statusenabled', true)
        ->where('sbm.statusenabled', true)
        ->where(function ($query) use ($idRuangKasir, $idgdKankerDep) {
            $query->whereNotNull('sbm.ruanganfk')
                ->where('sbm.ruanganfk', '=', $idRuangKasir)
                ->orWhere(function ($query) use ($idgdKankerDep) {
                    if(isset($idgdKankerDep)) {
                        $query->whereNull('sbm.ruanganfk')
                                ->whereRaw($idgdKankerDep);
                    }
                });
        });
        
        // ->whereRaw("case when sbm.ruanganfk is not null then sbm.ruanganfk = $idRuangKasir ELSE
        //     $idgdKankerDep END");

        // if(isset($idRuangKasir) && $idRuangKasir != null) {
        //     $datapiutang = $datapiutang->where('sbm.ruanganfk', $idRuangKasir);
        // }
        if($getpegawai !== '') {
            $datapiutang = $datapiutang->where('sp.objectpegawaipenerimafk', $idKasir);
        }
        // ->where('ru.objectdepartemenfk', $tipees)
        $datapiutang = $datapiutang->whereNotNull('pd.objectstatuspiutangfk')
        ->whereDate('sbm.tglsbm', '>=', $tglAwal)
        ->whereDate('sbm.tglsbm', '<=', $tglAkhir)
        ->where('pd.kdprofile', $kdProfile)
        ->groupBy(DB::raw("kp.kelompokpasien, sbm.tglsbm::date, pd.noregistrasi, pd.tglregistrasi, p.nocm, 
            p.namapasien, norec_pd, pd.tglpulang, sp.norec, pd.nocmfk, 
            pd.nostruklastfk, pd.nosbmlastfk, kp.kelompokpasien, p.objectkebangsaanfk, kp.id, stp.id,sbmcr.totaldibayar"))
        ->orderBy('p.namapasien');
        $datapiutang = $datapiutang->get();

        $data = collect(DB::select("SELECT
            DISTINCT
            sbm.norec,
            sbmcr.norec AS norec_sbmcr,
            cb.carabayar,
            sbmcr.objectcarabayarfk,
            sbm.objectkelompoktransaksifk,
            kt.kelompoktransaksi,
            sbm.keteranganlainnya,
            sbm.objectpegawaipenerimafk,
            p.namalengkap AS kasir,
            sbm.nosbm,
            kp.id AS kdkelompokpasien,
            kp.kelompokpasien as kelpasien,
            ru.namaruangan,
            dpr.namadepartemen,
        CASE
                
                WHEN ps.objectkebangsaanfk = 1 THEN
                'WNI' ELSE 'WNA' 
            END AS kelompokpasien,
            COALESCE ( sbm.tglsbm::date, sp.tglstruk::date ) AS tglsbm,
            -- sbm.tglsbm::date as tglsbm,
            pd.noregistrasi,
            sp.norec AS norec_sp,
            ps.nocm,
            pa.nosep,
            ps.namapasien,
            pd.noregistrasi,
            ps.nobpjs,
            ps.noidentitas,
            to_char(sbmcr.totaldibayar, '999999999FM') as totaldibayar,
            sp.totalprekanan AS totalharusdibayar,
        CASE
                
                WHEN cb.ID IN ( 2, 4 ) THEN
                CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
            END AS totalkartukredit,
        CASE
                
                WHEN cb.ID IN ( 3, 8, 9, 10, 12, 13, 14, 15 ) THEN
                CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
            END AS totaltransfer,
        CASE
                
                WHEN cb.ID = 1 THEN
                CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
            END AS totaltunai,
        CASE
                WHEN cb.ID = 17 THEN
                CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
            END AS totaliks,
        CASE
                
                WHEN sbm.noclosingfk IS NULL THEN
                'Belum Setor' ELSE'Setor' 
            END AS statussetor,
            ps.namapasien 
        FROM
            strukpelayanan_t AS sp
            LEFT JOIN strukbuktipenerimaan_t AS sbm ON sbm.nostrukfk = sp.norec
            LEFT JOIN pasiendaftar_t AS pd ON sp.noregistrasifk = pd.norec
            LEFT JOIN pegawai_m AS p ON p.id = sbm.objectpegawaipenerimafk
            INNER JOIN pasien_m AS ps ON ps.id = sp.nocmfk
            LEFT JOIN ruangan_m AS ru ON ru.id = sbm.ruanganfk
            LEFT JOIN ruangan_m AS ru2 ON ru2.id = pd.objectruanganlastfk
            LEFT JOIN departemen_m AS dpr ON dpr.id = ru2.objectdepartemenfk
            LEFT JOIN strukbuktipenerimaancarabayar_t AS sbmcr ON sbmcr.nosbmfk = sbm.norec
            LEFT JOIN carabayar_m AS cb ON cb.id = sbmcr.objectcarabayarfk
            LEFT JOIN kelompoktransaksi_m AS kt ON kt.id = sbm.objectkelompoktransaksifk
            LEFT JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
            LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk 
        WHERE
            sp.statusenabled = true
            AND pd.statusenabled = true
            AND sp.tglstruk :: DATE >= '$tglAwal'
            AND sp.tglstruk :: DATE <= '$tglAkhir' 
            AND sp.kdprofile = $kdProfile
            AND sp.isclosingnol = false
            AND sp.objectkelompoktransaksifk NOT IN ( 4, 1, 5, 99, 433, 46 )
            AND pd.objectstatuspiutangfk is null
            AND (
                sbmcr.statusenabled IS NULL 
                OR sbmcr.statusenabled = true
            )
            AND (
                (sbm.ruanganfk IS NOT NULL AND sbm.ruanganfk = $idRuangKasir)
                OR (sbm.ruanganfk IS NULL AND $idgdKankerPen)
            )
            AND (
                sbmcr.totaldibayar IS NOT NULL OR sp.totalprekanan > 0
            )
            $getpegawai"
        ));
        
        $penerimaan = [];
        $keyIndex = 0;
        $multi = DB::table('strukpelayananpenjamindetail_t as sppd')
        ->select('sppd.totalppenjamin', 'sppd.totalharusdibayar', 'rk.namarekanan', 'pd.noregistrasi', 'pd.tglpulang')
        ->join('strukpelayananpenjamin_t as spp', 'sppd.strukpelayananpenjaminfk', 'spp.norec')
        ->join('pasiendaftar_t as pd', 'pd.nostruklastfk', 'spp.nostrukfk')
        ->join('strukpelayanan_t as sp', 'spp.nostrukfk', 'sp.norec')
        ->leftjoin('rekanan_m as rk', 'rk.id', 'sppd.kdrekananpenjamin')
        // ->where('pd.noregistrasi', $group->noregistrasi)
        ->where('sppd.statusenabled', true)
        ->where('sp.statusenabled', true)
        ->where('sppd.keteranganlainnya', 'Multi Penjamin')
        ->whereDate('sp.tglstruk', '>=', $tglAwal)
        ->whereDate('sp.tglstruk', '<=', $tglAkhir)
        ->get();
        foreach($data as $keyGroup => $group) {
            // return $multi;
            $totalmulti = 0;
            $filt = array_filter($multi->toArray(), function($q) use($group) {
                return $q->noregistrasi == $group->noregistrasi;
            });
            $totalmulti = count($filt) > 0 ? array_sum(array_column($filt, 'totalppenjamin')) : 0;
            // return $multi;
            $group->totalmulti = $totalmulti;
            // return $group;
            $keyIndex++;
            if(count($penerimaan) > 0) {
                if($group->norec == null) {
                    $penerimaan[] = $group;
                }else {
                    if(!in_array($group->norec, array_column($penerimaan, 'norec'))) {
                        $penerimaan[] = $group;
                    }else {
                        foreach($penerimaan as $keyPen => $pen) {
                            if($pen->norec == $group->norec) {
                                $penerimaan[$keyPen]->totaltunai = (int)$pen->totaltunai == 0 ? (int)$pen->totaltunai + (int)$group->totaltunai : (int)$pen->totaltunai;
                                $penerimaan[$keyPen]->totaltransfer = (int)$pen->totaltransfer == 0 ? (int)$pen->totaltransfer + (int)$group->totaltransfer : (int)$pen->totaltransfer;
                                $penerimaan[$keyPen]->totalkartukredit = (int)$pen->totalkartukredit == 0 ? (int)$pen->totalkartukredit + (int)$group->totalkartukredit : (int)$pen->totalkartukredit;
                                $penerimaan[$keyPen]->totalharusdibayar = (int)$pen->totalharusdibayar == 0 ? (int)$pen->totalharusdibayar + (int)$group->totalharusdibayar : (int)$pen->totalharusdibayar;
                                $penerimaan[$keyPen]->totaliks = (int)$pen->totaliks == 0 ? (int)$pen->totaliks + (int)$group->totaliks : (int)$pen->totaliks;
                                $penerimaan[$keyPen]->totalmulti = (int)$group->totalmulti;
                            }
                        }
                    }
                }
            }else {
                $penerimaan[] = $group;
            }
        }
        $penerimaan = collect($penerimaan);
        // return $penerimaan->groupBy('kelpasien');
        $nonlayanan = $datanonlayanan;
        $deposit = $datadeposit;
        $piutang = $datapiutang;
        $profile = Profile::where('id', $this->kdProfile)->first();

        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('F4', 'landscape');

            $pdf->loadView(
                $views,
                array(
                    'penerimaan' => $penerimaan,
                    'nonlayanan' => $nonlayanan,
                    'deposit' => $deposit,
                    'piutang' => $piutang,
                    'tglAwal' => $tglAwal,
                    'tglAkhir' => $tglAkhir,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            $headers = array(
                'Content-Type: application/xlsx',
            );
            // Excel::create()
            $userKasirs = [
                "user" => $request['namaKasir'],
                "tipe" => $request['tipe']
            ];
            // return response()->download((new LaporanHarian($penerimaan,$nonlayanan,$deposit,$piutang,$tglAwal,$tglAkhir,$profile,$views))->store('tt.xlsx'), 'filename.xlsx', $headers);
            $file = Excel::download(new LaporanHarian($penerimaan,$nonlayanan,$deposit,$piutang,$tglAwal,$tglAkhir,$profile,$views,$userKasirs), date('YmdHis').'-laporanhariankasir.xlsx');
            // return response()->download($file, 'invoices.xlsx', $headers);
            return $file;
            return view(
                $views,
                compact('penerimaan', 'tglAwal', 'tglAkhir', 'profile', 'nonlayanan', 'deposit', 'piutang')
            );
        }
    }

    function getDataLaporanObatBebas(Request $request)
    {
        ini_set('max_execution_time', 6000);
        $kdProfile = (int)$this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];

        $idKasir = $request['idKasir'];
        $idRuangKasir = $request['ruanganfk'];
        $depID = null;
        $listgdkanker = $this->settingFix('idRuanganKanker');
        $idgdKankerPen = null;
        $idgdKankerDep = null;
        if($request['tipe'] == "KASIR RAWAT JALAN") {
            $idgdKankerPen = "ru2.id NOT IN ($listgdkanker, 322, 323) AND ru2.objectdepartemenfk = 18";
            $idgdKankerDep = "ru.id NOT IN ($listgdkanker, 322, 323) AND ru.objectdepartemenfk = 18";
            $depID = "(18)";
        }else if($request['tipe'] == "KASIR RAWAT INAP") {
            $idgdKankerPen = "ru2.objectdepartemenfk NOT IN (18,9) AND ru2.id NOT IN ($listgdkanker) AND ru2.objectdepartemenfk = 16";
            $idgdKankerDep = "ru.objectdepartemenfk NOT IN (18,9) AND ru.id NOT IN ($listgdkanker) AND ru.objectdepartemenfk = 16";
            $depID = "(16)";
        }else if ($request['tipe'] == "KASIR IGD") {
            $idgdKankerPen = "ru2.objectdepartemenfk NOT IN (18,16) AND ru2.id NOT IN ($listgdkanker) AND ru2.objectdepartemenfk = 9";
            $idgdKankerDep = "ru.objectdepartemenfk NOT IN (18,16) AND ru.id NOT IN ($listgdkanker) AND ru.objectdepartemenfk = 9";
            $depID = "(9)";
        }else if ($request['tipe'] == "KASIR GEDUNG KANKER") {
            $idgdKankerPen = "ru2.id IN ($listgdkanker)";
            $idgdKankerDep = "ru.id IN ($listgdkanker)";
            $depID = "(18,16)";
        }
        $getpegawai = '';
        $getpegawaiNon = '';
        // 5319
        $pegnone = explode(',',$this->settingFix('idPegawaiNone'));
        if(!in_array($idKasir, $pegnone)) {
            $getpegawai = "AND sp.objectpegawaipenerimafk = $idKasir";
            $getpegawaiNon = "AND sbm.objectpegawaipenerimafk = $idKasir";
        }
        $datanonlayanan = DB::select(DB::raw("SELECT
            sbm.tglsbm::date as tglstruk, sp.namapasien_klien, sp.norec, sp.nostruk, sp.noteleponfaks, 
            kt.kelompoktransaksi AS jenistagihan, sp.keteranganlainnya, sp.nosbklastfk, sp.nosbmlastfk, 
            sp.totalharusdibayar, rk.namarekanan, kp.kelompokpasien, sbm.objectpegawaipenerimafk, kp.id AS kdkelompokpasien,
            CASE
                WHEN cb.ID IN ( 2, 4 ) THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS totalkartukredit,
            CASE    
                WHEN cb.ID IN ( 3, 8, 9, 10, 12, 13, 14, 15 ) THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS totaltransfer,
            CASE    
                WHEN cb.ID = 1 THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS totaltunai,
            CASE
                WHEN cb.ID = 17 THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS totaliks
        FROM
            strukpelayanan_t AS sp
            INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sp.objectkelompoktransaksifk
            LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.id = sp.objectkelompokpasienfk
            LEFT JOIN rekanan_m AS rk ON rk.id = sp.objectrekananfk 
            LEFT JOIN strukbuktipenerimaan_t as sbm ON sbm.nostrukfk = sp.norec
            LEFT JOIN strukbuktipenerimaancarabayar_t AS sbmcr ON sbmcr.nosbmfk = sbm.norec
            LEFT JOIN carabayar_m AS cb ON cb.id = sbmcr.objectcarabayarfk
        WHERE
            sp.statusenabled = true
            AND sp.totalharusdibayar IS NOT NULL 
            AND sp.kdprofile = 1 
            AND sp.objectkelompoktransaksifk IN ( 4, 1, 5, 99, 433 )
            AND sbm.tglsbm :: DATE >= '$tglAwal' 
            AND sbm.tglsbm :: DATE <= '$tglAkhir'
            AND (
                (sbm.ruanganfk IS NOT NULL AND sbm.ruanganfk = $idRuanganKasir)
                OR (sbm.ruanganfk IS NULL)
            )
            AND case when sbm.ruanganfk is not null then sbm.ruanganfk = $idRuangKasir END
            $getpegawaiNon"));

        $views = 'report.kasir.laporan-penerimaan-kasir-harian'; 
        // $tipees = 18;

        $nonlayanan = $datanonlayanan;
        $profile = Profile::where('id', $this->kdProfile)->first();
        // return $penerimaan;

        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('F4', 'landscape');

            $pdf->loadView(
                $views,
                array(
                    'nonlayanan' => $nonlayanan,
                    'tglAwal' => $tglAwal,
                    'tglAkhir' => $tglAkhir,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            $headers = array(
                'Content-Type: application/xlsx',
            );
            // Excel::create()
            $userKasirs = [
                "user" => $request['namaKasir'],
                "tipe" => $request['tipe']
            ];
            // return response()->download((new LaporanHarian($penerimaan,$nonlayanan,$deposit,$piutang,$tglAwal,$tglAkhir,$profile,$views))->store('tt.xlsx'), 'filename.xlsx', $headers);
            $file = Excel::download(new LaporanHarian($penerimaan,$nonlayanan,$deposit,$piutang,$tglAwal,$tglAkhir,$profile,$views,$userKasirs), date('YmdHis').'-laporanhariankasir.xlsx');
            // return response()->download($file, 'invoices.xlsx', $headers);
            return $file;
            return view(
                $views,
                compact('penerimaan', 'tglAwal', 'tglAkhir', 'profile', 'nonlayanan', 'deposit', 'piutang')
            );
        }
    }

    function getDataLaporanPasienLost(Request $request)
    {

        ini_set('max_execution_time', 6000);
        $kdProfile = (int)$this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $idKasir = $request['idKasir'];
        $datadeposit = DB::table('strukpelayanan_t AS sp')
        ->join('pasiendaftar_t AS pd', 'sp.noregistrasifk', '=', 'pd.norec')
        ->join('pasien_m AS pas', 'pas.id', '=', 'pd.nocmfk')
        ->join('strukbuktipenerimaan_t AS sbm', 'sbm.nostrukfk', '=', 'sp.norec')
        ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
        ->leftjoin('carabayar_m AS cb', 'cb.id', '=', 'sbmcr.objectcarabayarfk')
        ->join('pegawai_m AS pg', 'pg.id', '=', 'sbm.objectpegawaipenerimafk')
        ->leftjoin('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->leftjoin('departemen_m AS dept', 'dept.id', '=', 'ru.objectdepartemenfk')
        ->leftjoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->select(DB::raw("pas.namapasien, 
            case when pas.objectkebangsaanfk = 1 then 'WNA' else kp.kelompokpasien end as kelompokpasien, pas.nocm, sbm.tglsbm::date as tglsbm,
            case when cb.id = 2 then SUM ( sbm.totaldibayar ) else 0 end AS totalkartukredit, 
            case when cb.id in (3, 12, 13, 14, 15) then SUM ( sbm.totaldibayar ) else 0 end AS totaltransfer, 
            case when cb.id not in (2, 3, 12, 13, 14, 15) then SUM ( sbm.totaldibayar ) else 0 end AS totaltunai, 
            pd.noregistrasi, pd.norec AS norec_pd"))
        ->where('pd.statusenabled', true)
        ->where('sp.statusenabled', true)
        ->where('sbm.statusenabled', true)
        ->where('sp.objectkelompoktransaksifk', 46)
        ->where('sp.objectpegawaipenerimafk', $idKasir)
        ->whereDate('sbm.tglsbm', '>=', $tglAwal)
        ->whereDate('sbm.tglsbm', '<=', $tglAkhir)
        ->where('pd.kdprofile', $kdProfile)
        // ->where('ru.objectdepartemenfk', $tipees)
        ->groupBy(DB::raw("pas.namapasien, kp.kelompokpasien, pas.nocm, kp.id, pas.objectkebangsaanfk,
            sbm.tglsbm::date, pd.noregistrasi, pd.norec, cb.id"))
        ->orderBy('pas.objectkebangsaanfk', 'desc')
        ->orderBy('kp.id', 'desc')
        ->get();
        // return $datadeposit;
        
        $datapiutang = DB::table('pasiendaftar_t AS pd')
        ->join('antrianpasiendiperiksa_t AS ap', 'pd.norec', '=', 'ap.noregistrasifk')
        ->join('pelayananpasien_t AS pp', 'ap.norec', '=', 'pp.noregistrasifk')
        ->leftjoin('strukpelayanan_t AS sp', 'pp.strukfk', '=', 'sp.norec')
        ->join('pasien_m AS p', 'p.id', '=', 'pd.nocmfk')
        ->join('jeniskelamin_m AS jk', 'jk.id', '=', 'p.objectjeniskelaminfk')
        ->join('statuspiutang_m AS stp', 'stp.id', '=', 'pd.objectstatuspiutangfk')
        ->leftjoin('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->leftjoin('departemen_m AS dept', 'dept.id', '=', 'ru.objectdepartemenfk')
        ->leftjoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sbm.nostrukfk', '=', 'sp.norec')
        ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
        ->leftjoin('carabayar_m as cb', 'cb.id', '=', 'sbmcr.objectcarabayarfk')
        ->select(DB::raw("kp.kelompokpasien, sbm.tglsbm::date tglsbm, pd.noregistrasi, pd.tglregistrasi, p.nocm, p.namapasien,
            SUM ( sbm.totaldibayar ) AS totaldibayar, pd.norec AS norec_pd,
            sp.norec, pd.tglpulang, pd.nocmfk, pd.nostruklastfk, pd.nosbmlastfk, kp.id as jaminbayarid,
            case when p.objectkebangsaanfk = 1 then 'WNI' else 'WNA' end as kelompokpasien
            "))
        ->where('pd.statusenabled', true)
        ->where('sp.statusenabled', true)
        ->where('sbm.statusenabled', true)
        ->where('sp.objectpegawaipenerimafk', $idKasir)
        // ->where('ru.objectdepartemenfk', $tipees)
        ->whereNotNull('pd.objectstatuspiutangfk')
        ->whereDate('sbm.tglsbm', '>=', $tglAwal)
        ->whereDate('sbm.tglsbm', '<=', $tglAkhir)
        ->where('pd.kdprofile', $kdProfile)
        ->groupBy(DB::raw("kp.kelompokpasien, sbm.tglsbm::date, pd.noregistrasi, pd.tglregistrasi, p.nocm, 
            p.namapasien, norec_pd, pd.tglpulang, sp.norec, pd.nocmfk, 
            pd.nostruklastfk, pd.nosbmlastfk, kp.kelompokpasien, p.objectkebangsaanfk, kp.id"))
        ->orderBy('p.namapasien')
        ->get();

        $views = 'report.kasir.laporan-penerimaan-kasir-lost'; 
        // $tipees = 18;

        $deposit = $datadeposit;
        $piutang = $datapiutang;
        $profile = Profile::where('id', $this->kdProfile)->first();
        // return $penerimaan;
        $userKasirs = [
            "user" => $request['namaKasir'],
            "tipe" => $request['tipe']
        ];

        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('F4', 'landscape');

            $pdf->loadView(
                $views,
                array(
                    'deposit' => $deposit,
                    'piutang' => $piutang,
                    'tglAwal' => $tglAwal,
                    'tglAkhir' => $tglAkhir,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $views,
                compact('tglAwal', 'tglAkhir', 'profile', 'deposit', 'piutang')
            );
            $headers = array(
                'Content-Type: application/xlsx',
            );
            // Excel::create()
            $userKasirs = [
                "user" => $request['user'],
                "tipe" => $request['tipe']
            ];
            // return response()->download((new LaporanHarian($penerimaan,$nonlayanan,$deposit,$piutang,$tglAwal,$tglAkhir,$profile,$views))->store('tt.xlsx'), 'filename.xlsx', $headers);
            $file = Excel::download(new LaporanHarian($penerimaan,$nonlayanan,$deposit,$piutang,$tglAwal,$tglAkhir,$profile,$views,$userKasirs), date('YmdHis').'-laporanhariankasir.xlsx');
            // return response()->download($file, 'invoices.xlsx', $headers);
            return $file;
            return view(
                $views,
                compact('penerimaan', 'tglAwal', 'tglAkhir', 'profile', 'nonlayanan', 'deposit', 'piutang')
            );
        }
    }
    function getDataLaporanPenerimaanSemuaKasirPDF(Request $request)
    {
        ini_set('max_execution_time', 6000);
        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];

        $idKasir = '';
        $idRuangan = $request['ruanganfk'];
        $depID = null;
        $listgdkanker = $this->settingFix('idRuanganKanker');
        $idgdKankerPen = null;
        $idgdKankerDep = null;
        if (isset($request['idKasir']) && $request['idKasir'] != "" && $request['idKasir'] != "undefined") {
            $idKasir = 'AND sp.objectpegawaipenerimafk = ' . $request['idKasir'];
        }
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
        //     $idRuangan = 'AND sbm.ruanganfk =' . $request['ruanganfk'];
        // }

        if($request['tipe'] == "KASIR RAWAT JALAN") {
            $idgdKankerPen = "ru2.id NOT IN ($listgdkanker, 322, 323) AND ru2.objectdepartemenfk = 18";
            $idgdKankerDep = "ru.id NOT IN ($listgdkanker, 322, 323) AND ru.objectdepartemenfk = 18";
            $depID = "(18)";
        }else if($request['tipe'] == "KASIR RAWAT INAP") {
            $idgdKankerPen = "ru2.objectdepartemenfk NOT IN (18,9) AND ru2.id NOT IN ($listgdkanker) AND ru2.objectdepartemenfk = 16";
            $idgdKankerDep = "ru.objectdepartemenfk NOT IN (18,9) AND ru.id NOT IN ($listgdkanker) AND ru.objectdepartemenfk = 16";
            $depID = "(16)";
        }else if ($request['tipe'] == "KASIR IGD") {
            $idgdKankerPen = "ru2.objectdepartemenfk NOT IN (18,16) AND ru2.id NOT IN ($listgdkanker) AND ru2.objectdepartemenfk = 9";
            $idgdKankerDep = "ru.objectdepartemenfk NOT IN (18,16) AND ru.id NOT IN ($listgdkanker) AND ru.objectdepartemenfk = 9";
            $depID = "(9)";
        }else if ($request['tipe'] == "KASIR GEDUNG KANKER") {
            $idgdKankerPen = "ru2.id IN ($listgdkanker)";
            $idgdKankerDep = "ru.id IN ($listgdkanker)";
            $depID = "(18,16)";
        }

        $data = DB::select(DB::raw("SELECT
            ru2.namaruangan,
            ru.namaruangan as ruangpenerima,
            p.namalengkap,
            ps.namapasien,
            sp.totalprekanan AS jumlahbpjs,
            pd.tglregistrasi,
            
            CASE 
                WHEN ps.objectkebangsaanfk != 1 and kp.id != 2 THEN 
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS jumlahwna,
            CASE    
                WHEN ps.objectkebangsaanfk = 1 and cb.ID IN ( 1 ,2 ,3, 4, 8, 9, 10, 12, 13, 14, 15 ) THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS jumlahumum,
            CASE
                WHEN ps.objectkebangsaanfk = 1 and cb.ID = 17 THEN
                    CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) ELSE 0 
                END AS jumlahiks
        FROM
            strukbuktipenerimaan_t AS sbm
            INNER JOIN strukpelayanan_t AS sp ON sbm.nostrukfk = sp.norec
            LEFT JOIN pasiendaftar_t AS pd ON sp.noregistrasifk = pd.norec
            INNER JOIN pasien_m AS ps ON ps.id = sp.nocmfk
            LEFT JOIN ruangan_m AS ru ON ru.id = sbm.ruanganfk
            LEFT JOIN ruangan_m AS ru2 ON ru2.id = pd.objectruanganlastfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk 
            LEFT JOIN pegawai_m AS p ON p.id = sbm.objectpegawaipenerimafk
            LEFT JOIN strukbuktipenerimaancarabayar_t AS sbmcr ON sbmcr.nosbmfk = sbm.norec
            LEFT JOIN carabayar_m AS cb ON cb.id = sbmcr.objectcarabayarfk
        WHERE
            sbm.statusenabled = true
            AND sp.statusenabled = true
            AND sbmcr.statusenabled = true
            AND pd.statusenabled = true
            AND sbm.tglsbm::DATE >= '$tglAwal' 
            AND sbm.tglsbm::DATE <= '$tglAkhir' 
            AND sbm.kdprofile = 1
            AND (
                (sbm.ruanganfk IS NOT NULL AND sbm.ruanganfk = $idRuangan)
                OR (sbm.ruanganfk IS NULL AND $idgdKankerPen)
            )
            $idKasir
            -- group by ru.namaruangan, ps.objectkebangsaanfk, kp.id, ru2.namaruangan, sp.totalprekanan, ps.namapasien, sbmcr.totaldibayar, cb.id
        "));

        // $getRuangan = array_unique(array_column($data, 'namaruangan'));
        $result = [];
        foreach($data as $dt) {
            $key = array_search($dt->namaruangan, array_column($result, 'namaruangan'));
            if($key !== false) {
                $result[$key]->jumlahwna = (int)$result[$key]->jumlahwna + (int)$dt->jumlahwna;
                $result[$key]->jumlahbpjs = (int)$result[$key]->jumlahbpjs + (int)$dt->jumlahbpjs;
                $result[$key]->jumlahumum = (int)$result[$key]->jumlahumum + (int)$dt->jumlahumum;
                $result[$key]->jumlahiks = (int)$result[$key]->jumlahiks + (int)$dt->jumlahiks;
            }else {
                $result[] = $dt;
            }
        }
        // return $result;
        $result = collect($result);
        // return $data;
        // dd($data);
        $profile = Profile::where('id', $this->kdProfile)->first();
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.kasir.laporan-penerimaan-kasir',
                array(
                    'data' => $result,
                    'tglAwal' => $tglAwal,
                    'tglAkhir' => $tglAkhir,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.kasir.laporan-penerimaan-kasir',
                compact('result', 'tglAwal', 'tglAkhir', 'profile')
            );
        }
    }

    function getDataLaporanPenerimaanSemuaKasirPerunitPDF(Request $request)
    {
        ini_set('max_execution_time', 6000);
        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];

        $data = DB::table('strukbuktipenerimaan_t AS sbm')
        ->join('strukpelayanan_t AS sp', 'sbm.nostrukfk', '=', 'sp.norec')
        ->leftJoin('pasiendaftar_t AS pd', 'sp.noregistrasifk', '=', 'pd.norec')
        ->join('pasien_m AS ps', 'ps.id', '=', 'sp.nocmfk')
        ->leftJoin('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->leftJoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->leftJoin('loginuser_s AS lu', 'lu.id', '=', 'sbm.objectpegawaipenerimafk')
        ->leftJoin('pegawai_m AS p', 'p.id', '=', 'lu.objectpegawaifk')
        ->select(DB::raw("ru.namaruangan,
            CASE WHEN ps.objectkebangsaanfk <> 1 THEN sum(sbm.totaldibayar) ELSE 0 END AS jumlahwna,
            CASE WHEN ps.objectkebangsaanfk = 1 and kp.id = 2 THEN sum(sbm.totaldibayar) ELSE 0 END AS jumlahbpjs,
            CASE WHEN ps.objectkebangsaanfk = 1 and kp.id = 3 THEN sum(sbm.totaldibayar) ELSE 0 END AS jumlahiks,
            CASE WHEN ps.objectkebangsaanfk = 1 and kp.id not in(2,3) THEN sum(sbm.totaldibayar) ELSE 0 END AS jumlahumum"))
        ->where('sbm.statusenabled', true)
        ->where('sp.statusenabled', true)
        ->whereDate('sbm.tglsbm', '>=', $tglAwal)
        ->whereDate('sbm.tglsbm', '<=', $tglAkhir);
        

        if (isset($request['idKasir']) && $request['idKasir'] != "" && $request['idKasir'] != "undefined") {
            $data = $data->where('p.id', $request['idKasir']);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $data = $data->where('sbm.ruanganfk', $request['idRuangan']);
        }

        $data = $data->groupBy('ru.namaruangan', 'ps.objectkebangsaanfk', 'kp.id')
        ->get();

        // dd($data);
        $profile = Profile::where('id', $this->kdProfile)->first();
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.kasir.laporan-penerimaan-kasir-unit',
                array(
                    'data' => $data,
                    'tglAwal' => $tglAwal,
                    'tglAkhir' => $tglAkhir,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.kasir.laporan-penerimaan-kasir-unit',
                compact('data', 'tglAwal', 'tglAkhir', 'profile')
            );
        }
    }


    public function getTotalKlaim($noregistrasi, $kdProfile)
    {
        $pelayanan = collect(DB::select("select sum(x.totalppenjamin) as totalklaim
         from (select spp.norec,spp.totalppenjamin
         from pasiendaftar_t as pd
            join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec and apd.kdprofile = pd.kdprofile
            join pelayananpasien_t as pp on pp.noregistrasifk =apd.norec and pp.kdprofile = apd.kdprofile
            join strukpelayanan_t as sp on sp.norec= pp.strukfk and sp.kdprofile = pp.kdprofile
            join strukpelayananpenjamin_t as spp on spp.nostrukfk=sp.norec and spp.kdprofile = sp.kdprofile
            where pd.noregistrasi = ?
        --and spp.statusenabled is null
        and pd.kdprofile= ?
        GROUP BY spp.norec,spp.totalppenjamin

        ) as x", [$noregistrasi, $kdProfile]))->first();
        if (!empty($pelayanan) && $pelayanan->totalklaim != null) {
            return (float) $pelayanan->totalklaim;
        } else {
            return 0;
        }
    }

    public function getTotolBayar($noregistrasi, $kdProfile)
    {
        $pelayanan = collect(DB::select("select sum(x.totaldibayar) as totaldibayar
         from (select sbm.norec,sbm.totaldibayar
         from pasiendaftar_t as pd
        join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec and apd.kdprofile = pd.kdprofile
        join pelayananpasien_t as pp on pp.noregistrasifk =apd.norec and pp.kdprofile = apd.kdprofile
        join strukpelayanan_t as sp on sp.norec= pp.strukfk and sp.kdprofile = pp.kdprofile
        join strukbuktipenerimaan_t as sbm on sbm.nostrukfk = sp.norec and sbm.kdprofile = sp.kdprofile
        where pd.noregistrasi = ?
        and sbm.statusenabled =true
        and pd.kdprofile= ?
        AND sbm.keteranganlainnya <> 'Pengembalian Deposit Pasien'
        GROUP BY sbm.norec,sbm.totaldibayar

        ) as x", [$noregistrasi, $kdProfile]))->first();
        if (!empty($pelayanan) && $pelayanan->totaldibayar != null) {
            return (float) $pelayanan->totaldibayar;
        } else {
            return 0;
        }
    }
    public function array_to_obj($array, &$obj)
    {
        foreach ($array as $key => $value)
        {
        if (is_array($value))
        {
        $obj->$key = new stdClass();
        $this->array_to_obj($value, $obj->$key);
        }
        else
        {
            $obj->$key = $value;
        }
        }
        return $obj;
    }

    public function arrayToObject($array)
    {
        $object= new stdClass();
        return $this->array_to_obj($array,$object);
    }
    public function cetakBillbpjs(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $noregistrasi = $r['noregistrasi'];
        $res['identitas'] = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', function ($j) {
                $j->on('apd.noregistrasifk', '=', 'pd.norec')->on('pd.kdprofile', '=', 'apd.kdprofile');
            })
            ->join('pelayananpasien_t AS pp', function ($j) {
                $j->on('pp.noregistrasifk', '=', 'apd.norec')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasien_m as ps', function ($j) {
                $j->on('ps.id', '=', 'pd.nocmfk')->on('pd.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'pd.objectruanganlastfk')->on('ru.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kl', function ($j) {
                $j->on('kl.id', '=', 'pd.objectkelasfk')->on('pd.kdprofile', '=', 'kl.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('dp.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('kelompokpasien_m as kp', function ($j) {
                $j->on('kp.id', '=', 'pd.objectkelompokpasienlastfk')->on('kp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('jeniskelamin_m as jk', function ($j) {
                $j->on('jk.id', '=', 'ps.objectjeniskelaminfk')->on('ps.kdprofile', '=', 'jk.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'pd.objectpegawaifk')->on('pd.kdprofile', '=', 'pg.kdprofile');
            })
            ->leftjoin('rekanan_m as rk', function ($j) {
                $j->on('rk.id', '=', 'pd.objectrekananfk')->on('rk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('kebangsaan_m as kbg', function ($j) {
                $j->on('kbg.id', '=', 'ps.objectkebangsaanfk')->on('kbg.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('pemakaianasuransi_t as pa', function ($j) {
                $j->on('pa.noregistrasifk', '=', 'pd.norec')->on('pa.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('tempattidur_m AS tt', function ($j) {
                $j->on('tt.id', '=', 'apd.nobed')->on('tt.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('strukpelayanan_t AS sp', function ($j) {
                $j->on('sp.norec', '=', 'pp.strukfk')->on('sp.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftjoin('strukbuktipenerimaan_t AS sbm', function ($j) {
                $j->on('sp.nosbmlastfk', '=', 'sbm.norec')->on('sbm.kdprofile', '=', 'sp.kdprofile');
            })
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
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
                'pa.nosep',
                'ps.namaayah',
                'ps.penanggungjawab',
                'tt.reportdisplay AS nobed',
                'sbm.nosbm',
                DB::raw("coalesce(sbm.totaldiskon, 0) as totaldiskon"),
                'sp.nostruk',
                'ps.nocm',
                'ps.nobpjs',
                'ps.tgllahir',
                'alm.alamatlengkap',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur")
            )


            ->where('pd.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        // var_dump($r['noregistrasi']);
        // var_dump($res['identitas']);
        // $res['identitas']->umur = $this->hitungUmur($res['identitas']->tgllahir);
        $res['identitas']->tgllahir = $this->getDateIndo($res['identitas']->tgllahir);

        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.norec', '=', 'pp.noregistrasifk')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasiendaftar_t as pd', function ($j) {
                $j->on('pd.norec', '=', 'apd.noregistrasifk')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kls', function ($j) {
                $j->on('kls.id', '=', 'apd.objectkelasfk')->on('apd.kdprofile', '=', 'kls.kdprofile');
            })
            ->join('produk_m as prd', function ($j) {
                $j->on('prd.id', '=', 'pp.produkfk')->on('prd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'apd.objectruanganfk')->on('apd.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('ru.kdprofile', '=', 'dp.kdprofile');
            })
            ->leftJOIN('strukresep_t as sr', function ($j) {
                $j->on('sr.norec', '=', 'pp.strukresepfk')->on('sr.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJOIN('antrianapotik_t as aa', function ($j) {
                $j->on('pp.noregistrasi', '=', 'aa.noregistrasi');
            })
            ->leftJOIN('ruangan_m as ru2', function ($j) {
                $j->on('ru2.id', '=', 'sr.ruanganfk')->on('sr.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('departemen_m as dp2', function ($j) {
                $j->on('dp2.id', '=', 'ru2.objectdepartemenfk')->on('dp2.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'sr.penulisresepfk')->on('pg.kdprofile', '=', 'sr.kdprofile');
            })
            ->leftJOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'prd.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'prd.kdprofile');
            })
            ->leftJOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'prd.objectdetailjenisprodukfk',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'aa.jenis as jenisantrian',
                'aa.noantri',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("case when prd.objectdetailjenisprodukfk = 3011 and pp.isobat is null then 'Lain-Lain' when pp.isobat = true then 'Obat' else 'Tindakan' end as detailjenisproduk"),
                DB::raw("
            case when pp.jasa is not null then pp.jasa else 0 end jasa,
            COALESCE (pp.hargadiscount, 0)  as diskon,
            case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
            (
                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                 * pp.jumlah)
            + (case when pp.jasa is not null then pp.jasa else 0 end)
             as total,
            to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
            case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
            case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
            case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
           ")
            )
            ->where('pp.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('pp.hargasatuan', '>', 0)
            ->where('pp.jumlah', '>', 0)
            ->orderBy('pp.tglpelayanan', 'desc');

        $datakronis = DB::table('pelayananpasienobatkronis_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.norec', '=', 'pp.noregistrasifk')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasiendaftar_t as pd', function ($j) {
                $j->on('pd.norec', '=', 'apd.noregistrasifk')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kls', function ($j) {
                $j->on('kls.id', '=', 'apd.objectkelasfk')->on('apd.kdprofile', '=', 'kls.kdprofile');
            })
            ->join('produk_m as prd', function ($j) {
                $j->on('prd.id', '=', 'pp.produkfk')->on('prd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'apd.objectruanganfk')->on('apd.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('ru.kdprofile', '=', 'dp.kdprofile');
            })
            ->leftJOIN('strukresep_t as sr', function ($j) {
                $j->on('sr.norec', '=', 'pp.strukresepfk')->on('sr.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJOIN('ruangan_m as ru2', function ($j) {
                $j->on('ru2.id', '=', 'sr.ruanganfk')->on('sr.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('departemen_m as dp2', function ($j) {
                $j->on('dp2.id', '=', 'ru2.objectdepartemenfk')->on('dp2.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'sr.penulisresepfk')->on('pg.kdprofile', '=', 'sr.kdprofile');
            })
            ->leftJOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'prd.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'prd.kdprofile');
            })
            ->leftJOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'prd.objectdetailjenisprodukfk',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                DB::raw("'' as jenisantrian, '' as noantri"),
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("'Obat Kronis'as detailjenisproduk"),
                DB::raw("
            case when pp.jasa is not null then pp.jasa else 0 end jasa,
            COALESCE (pp.hargadiscount, 0)  as diskon,
            case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
            (
                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                 * pp.jumlah)
            + (case when pp.jasa is not null then pp.jasa else 0 end)
             as total,
            to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
            case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
            case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
            case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
           ")
            )
            ->where('pp.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('pp.harganetto', '>', 0)
            ->where('pp.jumlah', '>', 0)
            ->orderBy('pp.tglpelayanan', 'desc');
        
        $sDokterPemeriksa = $this->settingDataFixed('jenisPetugasDokterPemeriksa', $r['kdprofile']);
        $pelayananpetugas = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('pelayananpasienpetugas_t as ptu', function ($join) {
                $join->on('ptu.nomasukfk', '=', 'apd.norec')->on('ptu.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($join) {
                $join->on('pg.id', '=', 'ptu.objectpegawaifk')->on('pg.kdprofile', '=', 'ptu.kdprofile');
            })
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.objectjenispetugaspefk', 4)
            ->where('pd.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['kdprofile'])
            ->get();

        $data = $data->union($datakronis)->get();
        

        $res['total'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $res['total'] = $res['total'] + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                }
            }
        }


        // $data = array_merge(collect($billcb)->toArray(),collect($multi)->toArray());

        // $res['billing'] = collect($data)->groupBy('ruang_group');
        // $data = collect($data)->groupBy('ruang_group');
        // $datakronis = collect($datakronis)->groupBy('ruang_group');
        $res['billing'] = collect($data)->groupBy('ruang_group');
        $res['antrian'] = $data[0]->jenisantrian . '-' . $data[0]->noantri;
        // $res['billing'] = $this->arrayToObject($res['billing']);
        $res['ismultipenjamin'] = false;
        $res['klaim'] = $this->getTotalKlaim($r['noregistrasi'], $r['kdprofile']);
        $res['deposit'] = $this->getDepositPasien($r['noregistrasi']);
        $res['dibayar'] = $this->getTotolBayar($r['noregistrasi'], $r['kdprofile']);
        $res['sisa'] = $res['total'] - $res['dibayar'] - $res['deposit'] - $res['klaim'];
        // $res['pdf'] = false;
        $res['indo'] = isset($r['bangsa']) && $r['bangsa'] != 'WNI' ? false : true;
        // $res['indo'] = true;
        // return $res['billing'];
        $blade = 'report.kasir.cetak-billing-bpjs';

        // dd($res);

        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] = collect($data)->groupBy('departemen_group');
            $blade = 'report.kasir.cetak-billing-bpjs';
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'r')
        );
    }
    public function cetakBillbpjsKlaim(Request $r)
    {

        $profile = $this->profile();
        $kdProfile = $this->kdProfile;
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $noregistrasi = $r['noregistrasi'];
        $qrcode2= '';
        $res['identitas'] = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', function ($j) {
                $j->on('apd.noregistrasifk', '=', 'pd.norec')->on('pd.kdprofile', '=', 'apd.kdprofile');
            })
            ->join('pelayananpasien_t AS pp', function ($j) {
                $j->on('pp.noregistrasifk', '=', 'apd.norec')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasien_m as ps', function ($j) {
                $j->on('ps.id', '=', 'pd.nocmfk')->on('pd.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'pd.objectruanganlastfk')->on('ru.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kl', function ($j) {
                $j->on('kl.id', '=', 'pd.objectkelasfk')->on('pd.kdprofile', '=', 'kl.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('dp.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('kelompokpasien_m as kp', function ($j) {
                $j->on('kp.id', '=', 'pd.objectkelompokpasienlastfk')->on('kp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('jeniskelamin_m as jk', function ($j) {
                $j->on('jk.id', '=', 'ps.objectjeniskelaminfk')->on('ps.kdprofile', '=', 'jk.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'pd.objectpegawaifk')->on('pd.kdprofile', '=', 'pg.kdprofile');
            })
            ->leftjoin('rekanan_m as rk', function ($j) {
                $j->on('rk.id', '=', 'pd.objectrekananfk')->on('rk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('kebangsaan_m as kbg', function ($j) {
                $j->on('kbg.id', '=', 'ps.objectkebangsaanfk')->on('kbg.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('pemakaianasuransi_t as pa', function ($j) {
                $j->on('pa.noregistrasifk', '=', 'pd.norec')->on('pa.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('tempattidur_m AS tt', function ($j) {
                $j->on('tt.id', '=', 'apd.nobed')->on('tt.kdprofile', '=', 'apd.kdprofile');
            })
            ->join('strukpelayanan_t AS sp', function ($j) {
                $j->on('sp.norec', '=', 'pp.strukfk')->on('sp.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftjoin('pegawai_m as pg1', function ($j) {
                $j->on('pg1.id', '=', 'sp.objectpegawaipenerimafk');
            })
            ->leftjoin('strukbuktipenerimaan_t AS sbm', function ($j) {
                $j->on('sp.nosbmlastfk', '=', 'sbm.norec')->on('sbm.kdprofile', '=', 'sp.kdprofile');
            })
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.nocmfk',
                'pd.tglpulang',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'rk.namarekanan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'kp.id as id_kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'pa.nosep',
                'ps.namaayah',
                'ps.penanggungjawab',
                'tt.reportdisplay AS nobed',
                'sbm.nosbm',
                DB::raw("coalesce(sbm.totaldiskon, 0) as totaldiskon"),
                'sp.nostruk',
                'ps.nocm',
                'ps.nobpjs',
                'pg1.namalengkap as kasir',
                'ps.tgllahir',
                'alm.alamatlengkap',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur")
            )


            ->where('pd.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        // var_dump($r['noregistrasi']);
        // var_dump($res['identitas']);
        // $res['identitas']->umur = $this->hitungUmur($res['identitas']->tgllahir);
        $res['identitas']->tgllahir = $this->getDateIndo($res['identitas']->tgllahir);

        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.norec', '=', 'pp.noregistrasifk')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasiendaftar_t as pd', function ($j) {
                $j->on('pd.norec', '=', 'apd.noregistrasifk')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kls', function ($j) {
                $j->on('kls.id', '=', 'apd.objectkelasfk')->on('apd.kdprofile', '=', 'kls.kdprofile');
            })
            ->join('produk_m as prd', function ($j) {
                $j->on('prd.id', '=', 'pp.produkfk')->on('prd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'apd.objectruanganfk')->on('apd.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('ru.kdprofile', '=', 'dp.kdprofile');
            })
            ->leftJOIN('strukresep_t as sr', function ($j) {
                $j->on('sr.norec', '=', 'pp.strukresepfk')->on('sr.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJOIN('ruangan_m as ru2', function ($j) {
                $j->on('ru2.id', '=', 'sr.ruanganfk')->on('sr.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('departemen_m as dp2', function ($j) {
                $j->on('dp2.id', '=', 'ru2.objectdepartemenfk')->on('dp2.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'sr.penulisresepfk')->on('pg.kdprofile', '=', 'sr.kdprofile');
            })
            ->leftJOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'prd.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'prd.kdprofile');
            })
            ->leftJOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'prd.objectdetailjenisprodukfk',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("case when prd.objectdetailjenisprodukfk = 3011 and pp.isobat is null then 'Lain-Lain' when pp.isobat = true then 'Obat' else 'Tindakan' end as detailjenisproduk"),
                DB::raw("
            case when pp.jasa is not null then pp.jasa else 0 end jasa,
            COALESCE (pp.hargadiscount, 0)  as diskon,
            case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
            (
                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                 * pp.jumlah)
            + (case when pp.jasa is not null then pp.jasa else 0 end)
             as total,
            to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
            case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
            case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
            case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
           ")
            )
            ->where('pp.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->orderBy('pp.tglpelayanan', 'desc');

        $datakronis = DB::table('pelayananpasienobatkronis_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.norec', '=', 'pp.noregistrasifk')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasiendaftar_t as pd', function ($j) {
                $j->on('pd.norec', '=', 'apd.noregistrasifk')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kls', function ($j) {
                $j->on('kls.id', '=', 'apd.objectkelasfk')->on('apd.kdprofile', '=', 'kls.kdprofile');
            })
            ->join('produk_m as prd', function ($j) {
                $j->on('prd.id', '=', 'pp.produkfk')->on('prd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'apd.objectruanganfk')->on('apd.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('ru.kdprofile', '=', 'dp.kdprofile');
            })
            ->leftJOIN('strukresep_t as sr', function ($j) {
                $j->on('sr.norec', '=', 'pp.strukresepfk')->on('sr.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJOIN('ruangan_m as ru2', function ($j) {
                $j->on('ru2.id', '=', 'sr.ruanganfk')->on('sr.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('departemen_m as dp2', function ($j) {
                $j->on('dp2.id', '=', 'ru2.objectdepartemenfk')->on('dp2.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'sr.penulisresepfk')->on('pg.kdprofile', '=', 'sr.kdprofile');
            })
            ->leftJOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'prd.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'prd.kdprofile');
            })
            ->leftJOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'prd.objectdetailjenisprodukfk',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("'Obat Kronis'as detailjenisproduk"),
                DB::raw("
            case when pp.jasa is not null then pp.jasa else 0 end jasa,
            COALESCE (pp.hargadiscount, 0)  as diskon,
            case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
            (
                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                 * pp.jumlah)
            + (case when pp.jasa is not null then pp.jasa else 0 end)
             as total,
            to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
            case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
            case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
            case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
           ")
            )
            ->where('pp.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('pp.harganetto', '>', 0)
            ->where('pp.jumlah', '>', 0)
            ->orderBy('pp.tglpelayanan', 'desc');
        
        $sDokterPemeriksa = $this->settingDataFixed('jenisPetugasDokterPemeriksa', $r['kdprofile']);
        $pelayananpetugas = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('pelayananpasienpetugas_t as ptu', function ($join) {
                $join->on('ptu.nomasukfk', '=', 'apd.norec')->on('ptu.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($join) {
                $join->on('pg.id', '=', 'ptu.objectpegawaifk')->on('pg.kdprofile', '=', 'ptu.kdprofile');
            })
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.objectjenispetugaspefk', 4)
            ->where('pd.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['kdprofile'])
            ->get();

        $data = $data->union($datakronis)->get();

        $res['total'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $res['total'] = $res['total'] + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                }
            }
        }

        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate("https://simrsbm-test.baliprov.go.id/service/bukti-layanan-bpjs-klaim?noregistrasi=".$r['noregistrasi']));


        $dataEMR = DB::connection('mongodb')
        ->table('JadwalKunjunganRehabDanFisio')
        ->where('profile.kdprofile', $this->kdProfile)
        ->where('statusenabled', true)
        ->where('pasien.nocmfk', $res['identitas']->nocmfk)
        ->orderBy('created_at', 'desc')
        ->first();
        $dataEMR2 = DB::connection('mongodb')
        ->table('FormulirCatatanInformasiEdukasi')
        ->where('profile.kdprofile', $this->kdProfile)
        ->where('statusenabled', true)
        ->where('pasien.nocmfk', $res['identitas']->nocmfk)
        ->orderBy('created_at', 'desc')
        ->first();

            // dd($dataEMR2);

        if($res['identitas']->id_kelompokpasien == 2){
        $qrcode2 = base64_encode(QrCode::format('svg')->size(45)->generate($res['identitas']->nobpjs));
        }

        $res['billing'] = collect($data)->groupBy('namaruangan');
        $res['ismultipenjamin'] = false;
        $res['klaim'] = $this->getTotalKlaim($r['noregistrasi'], $r['kdprofile']);
        $res['deposit'] = $this->getDepositPasien($r['noregistrasi']);
        $res['dibayar'] = $this->getTotolBayar($r['noregistrasi'], $r['kdprofile']);
        $res['sisa'] = $res['total'] - $res['dibayar'] - $res['deposit'] - $res['klaim'];
        $res['pdf'] = 'true';
        $res['storage'] = 'true';
        $blade = 'report.kasir.cetak-billing-bpjs-klaim';
        // start generate parameter kebutuhan save dokumen

        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper([0, 0, 500, 800], 'potrait');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'r' => $r,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'qrcode' => $qrcode,
                    'qrcode2' => $qrcode2,
                    'dataEMR' => $dataEMR,
                    'dataEMR2' => $dataEMR2,
                )
            );
            return $pdf;
        }
        // if ($res['storage'] == 'true') {
        //     var_dump('halo2');
        //     $res['storage']  = true;
        //     $pdf = App::make('dompdf.wrapper');
        //     $pdf->setpaper([0, 0, 841.89, 423.15]);
        //     $pdf->loadView(
        //         $blade,
        //         array(
        //             'profile' => $profile,
        //             'r' => $r,
        //             'pageWidth' => $pageWidth,
        //             'print' => $print,
        //             'res' => $res,
        //         )
        //     );
        //     return $pdf;
        // }
        $pdf = false;
        return view($blade,compact('profile', 'pageWidth', 'print', 'res','pdf', 'qrcode', 'qrcode2'));
    }
    public function cetakBillbpjsKlaim2(Request $r)
    {
        ini_set('max_execution_time', 6000);
        $kdProfile = $this->kdProfile;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $noregistrasi = $r['noregistrasi'];
        $res['identitas'] = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', function ($j) {
                $j->on('apd.noregistrasifk', '=', 'pd.norec')->on('pd.kdprofile', '=', 'apd.kdprofile');
            })
            ->join('pelayananpasien_t AS pp', function ($j) {
                $j->on('pp.noregistrasifk', '=', 'apd.norec')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasien_m as ps', function ($j) {
                $j->on('ps.id', '=', 'pd.nocmfk')->on('pd.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'pd.objectruanganlastfk')->on('ru.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kl', function ($j) {
                $j->on('kl.id', '=', 'pd.objectkelasfk')->on('pd.kdprofile', '=', 'kl.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('dp.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('kelompokpasien_m as kp', function ($j) {
                $j->on('kp.id', '=', 'pd.objectkelompokpasienlastfk')->on('kp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('jeniskelamin_m as jk', function ($j) {
                $j->on('jk.id', '=', 'ps.objectjeniskelaminfk')->on('ps.kdprofile', '=', 'jk.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'pd.objectpegawaifk')->on('pd.kdprofile', '=', 'pg.kdprofile');
            })
            ->leftjoin('rekanan_m as rk', function ($j) {
                $j->on('rk.id', '=', 'pd.objectrekananfk')->on('rk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('kebangsaan_m as kbg', function ($j) {
                $j->on('kbg.id', '=', 'ps.objectkebangsaanfk')->on('kbg.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('pemakaianasuransi_t as pa', function ($j) {
                $j->on('pa.noregistrasifk', '=', 'pd.norec')->on('pa.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('tempattidur_m AS tt', function ($j) {
                $j->on('tt.id', '=', 'apd.nobed')->on('tt.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('strukpelayanan_t AS sp', function ($j) {
                $j->on('sp.norec', '=', 'pp.strukfk')->on('sp.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftjoin('strukbuktipenerimaan_t AS sbm', function ($j) {
                $j->on('sp.nosbmlastfk', '=', 'sbm.norec')->on('sbm.kdprofile', '=', 'sp.kdprofile');
            })
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
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
                'pa.nosep',
                'ps.namaayah',
                'ps.penanggungjawab',
                'tt.reportdisplay AS nobed',
                'sbm.nosbm',
                'sp.nostruk',
                'ps.nocm',
                'ps.nobpjs',
                'ps.tgllahir',
                'alm.alamatlengkap',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur")
            )


            ->where('pd.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        // var_dump($r['noregistrasi']);
        // var_dump($res['identitas']);
        // $res['identitas']->umur = $this->hitungUmur($res['identitas']->tgllahir);
        $res['identitas']->tgllahir = $this->getDateIndo($res['identitas']->tgllahir);

        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.norec', '=', 'pp.noregistrasifk')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasiendaftar_t as pd', function ($j) {
                $j->on('pd.norec', '=', 'apd.noregistrasifk')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kls', function ($j) {
                $j->on('kls.id', '=', 'apd.objectkelasfk')->on('apd.kdprofile', '=', 'kls.kdprofile');
            })
            ->join('produk_m as prd', function ($j) {
                $j->on('prd.id', '=', 'pp.produkfk')->on('prd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'apd.objectruanganfk')->on('apd.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('ru.kdprofile', '=', 'dp.kdprofile');
            })
            ->leftJOIN('strukresep_t as sr', function ($j) {
                $j->on('sr.norec', '=', 'pp.strukresepfk')->on('sr.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJOIN('ruangan_m as ru2', function ($j) {
                $j->on('ru2.id', '=', 'sr.ruanganfk')->on('sr.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('departemen_m as dp2', function ($j) {
                $j->on('dp2.id', '=', 'ru2.objectdepartemenfk')->on('dp2.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'sr.penulisresepfk')->on('pg.kdprofile', '=', 'sr.kdprofile');
            })
            ->leftJOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'prd.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'prd.kdprofile');
            })
            ->leftJOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'prd.objectdetailjenisprodukfk',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("case when prd.objectdetailjenisprodukfk = 3011 and pp.isobat is null then 'Lain-Lain' when pp.isobat = true then 'Obat' else 'Tindakan' end as detailjenisproduk"),
                DB::raw("
            case when pp.jasa is not null then pp.jasa else 0 end jasa,
            COALESCE (pp.hargadiscount, 0)  as diskon,
            case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
            (
                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                 * pp.jumlah)
            + (case when pp.jasa is not null then pp.jasa else 0 end)
             as total,
            to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
            case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
            case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
            case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
           ")
            )
            ->where('pp.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->orderBy('pp.tglpelayanan', 'desc')
            ->get();

        $sDokterPemeriksa = $this->settingDataFixed('jenisPetugasDokterPemeriksa', $r['kdprofile']);
        $pelayananpetugas = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('pelayananpasienpetugas_t as ptu', function ($join) {
                $join->on('ptu.nomasukfk', '=', 'apd.norec')->on('ptu.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($join) {
                $join->on('pg.id', '=', 'ptu.objectpegawaifk')->on('pg.kdprofile', '=', 'ptu.kdprofile');
            })
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.objectjenispetugaspefk', 4)
            ->where('pd.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['kdprofile'])
            ->get();



        $res['total'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $res['total'] = $res['total'] + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                }
            }
        }

        $res['billing'] = collect($data)->groupBy('namaruangan');
        $res['ismultipenjamin'] = false;
        $res['klaim'] = $this->getTotalKlaim($r['noregistrasi'], $r['kdprofile']);
        $res['deposit'] = $this->getDepositPasien($r['noregistrasi']);
        $res['dibayar'] = $this->getTotolBayar($r['noregistrasi'], $r['kdprofile']);
        $res['sisa'] = $res['total'] - $res['dibayar'] - $res['deposit'] - $res['klaim'];
        $blade = 'report.kasir.cetak-billing-bpjs';

        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.kasir.cetak-billing-bpjs',
                array(
                    'profile' => $profile,
                    'r' => $r,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }

        if ($r['storage'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.kasir.cetak-billing-bpjs',
                array(
                    'profile' => $profile,
                    'r' => $r,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }
        
        return view('report.kasir.cetak-billing-bpjs',
        compact('profile', 'pageWidth', 'print', 'res', 'r'));
    }
    public function cetakBillbpjsNon(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $noregistrasi = $r['noregistrasi'];
        $res['identitas'] = DB::table('strukpelayanan_t AS sp')
            ->leftjoin('strukpelayanandetail_t AS spd', 'sp.norec', '=', 'spd.nostrukfk')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec') 
            ->select(
                DB::raw("null as noregistrasi"),
                DB::raw("null as tglregistrasi"),
                DB::raw("null as tglpulang"),
                'sp.namapasien_klien as namapasien',
                DB::raw("null as nocm"),
                DB::raw("null as namaruangan"),
                DB::raw("null as namakelas"),
                DB::raw("null as kelompokpasien"),
                DB::raw("null as namadepartemen"),
                DB::raw("null as jeniskelamin"),
                DB::raw("null as namalengkap"),
                DB::raw("null as namarekanan"),
                DB::raw("null as nosep"),
                DB::raw("null as namaayah"),
                DB::raw("null as penanggungjawab"),
                DB::raw("null AS nobed"),
                DB::raw("sbm.nosbm"),
                DB::raw("coalesce(sbm.totaldiskon, 0) as totaldiskon"),
                'sp.nostruk',
                DB::raw("null as nocm"),
                DB::raw("null as nobpjs"),
                DB::raw("null as tgllahir"),
                DB::raw("null as alamatlengkap"),
                DB::raw("null AS umur")
            )


            ->where('sp.statusenabled', true)
            ->where('sp.norec', $r['norec_sp'])
            ->first();

        // var_dump($r['noregistrasi']);
        // var_dump($res['identitas']);
        // $res['identitas']->umur = $this->hitungUmur($res['identitas']->tgllahir);

        $data = DB::table('strukpelayanan_t AS sp')
            ->leftjoin('strukpelayanandetail_t AS spd', 'sp.norec', '=', 'spd.nostrukfk')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftjoin('produk_m as prd', 'spd.objectprodukfk', '=', 'prd.id') 
            ->select(
                'spd.norec as norec',
                'prd.namaproduk',
                DB::raw("null as namakelas"),
                'spd.tglpelayanan',
                'prd.objectdetailjenisprodukfk',
                DB::raw("null as namaruangan"),
                DB::raw("null as ruanganfarmasi"),
                DB::raw("null as strukresepfk"),
                'spd.qtyproduk as jumlah',
                'spd.hargasatuan',
                DB::raw("null as penulisresep"),
                DB::raw("null as jenisproduk"),
                DB::raw("null as namadepartemen"),
                DB::raw("null as deparemenfarmasi"),
                DB::raw("'Tagihan Non Layanan' as detailjenisproduk"),
                DB::raw("0 as jasa, 0  as diskon, 0 as hargadiscount, ((spd.hargasatuan ) * (spd.qtyproduk)) as total, to_char(spd.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group, 'Non Layanan' as jenis, 'Non Layanan' as ruang_group , 'Non Layanan' as departemen_group")
            )
            ->where('sp.statusenabled', true)
            ->where('sp.norec', $r['norec_sp'])
            ->orderBy('spd.tglpelayanan', 'desc')
            ->get();
        $res['pdf'] = false;
        $res['indo'] = true;
        $res['total'] = 0;
        $res['ismultipenjamin'] = false;
        $blade = 'report.kasir.cetak-billing-bpjs';
                // return $data;
        // dd($res);
        $res['billing'] = collect($data)->groupBy('namaruangan');

        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] = collect($data)->groupBy('departemen_group');
            $blade = 'report.kasir.cetak-billing-bpjs';
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'r')
        );
    }
    public function cetakBillVA(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $noregistrasi = $r['noregistrasi'];
        $res['identitas'] = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', function ($j) {
                $j->on('apd.noregistrasifk', '=', 'pd.norec')->on('pd.kdprofile', '=', 'apd.kdprofile');
            })
            ->join('pelayananpasien_t AS pp', function ($j) {
                $j->on('pp.noregistrasifk', '=', 'apd.norec')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasien_m as ps', function ($j) {
                $j->on('ps.id', '=', 'pd.nocmfk')->on('pd.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'pd.objectruanganlastfk')->on('ru.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kl', function ($j) {
                $j->on('kl.id', '=', 'pd.objectkelasfk')->on('pd.kdprofile', '=', 'kl.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('dp.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('kelompokpasien_m as kp', function ($j) {
                $j->on('kp.id', '=', 'pd.objectkelompokpasienlastfk')->on('kp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('jeniskelamin_m as jk', function ($j) {
                $j->on('jk.id', '=', 'ps.objectjeniskelaminfk')->on('ps.kdprofile', '=', 'jk.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'pd.objectpegawaifk')->on('pd.kdprofile', '=', 'pg.kdprofile');
            })
            ->leftjoin('rekanan_m as rk', function ($j) {
                $j->on('rk.id', '=', 'pd.objectrekananfk')->on('rk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('pemakaianasuransi_t as pa', function ($j) {
                $j->on('pa.noregistrasifk', '=', 'pd.norec')->on('pa.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('tempattidur_m AS tt', function ($j) {
                $j->on('tt.id', '=', 'apd.nobed')->on('tt.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('strukpelayanan_t AS sp', function ($j) {
                $j->on('sp.norec', '=', 'pp.strukfk')->on('sp.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftjoin('strukbuktipenerimaan_t AS sbm', function ($j) {
                $j->on('sp.nosbmlastfk', '=', 'sbm.norec')->on('sbm.kdprofile', '=', 'sp.kdprofile');
            })
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
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
                'pa.nosep',
                'ps.namaayah',
                'sp.nostruk',
                'ps.penanggungjawab',
                'tt.reportdisplay AS nobed',
                'sbm.nosbm',
                'ps.nocm',
                'ps.nobpjs',
                'ps.tgllahir',
                'alm.alamatlengkap',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur")
            )


            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        // $res['identitas']->umur = $this->hitungUmur($res['identitas']->tgllahir);
        $res['identitas']->tgllahir = $this->getDateIndo($res['identitas']->tgllahir);

        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.norec', '=', 'pp.noregistrasifk')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasiendaftar_t as pd', function ($j) {
                $j->on('pd.norec', '=', 'apd.noregistrasifk')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kls', function ($j) {
                $j->on('kls.id', '=', 'apd.objectkelasfk')->on('apd.kdprofile', '=', 'kls.kdprofile');
            })
            ->join('produk_m as prd', function ($j) {
                $j->on('prd.id', '=', 'pp.produkfk')->on('prd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'apd.objectruanganfk')->on('apd.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('ru.kdprofile', '=', 'dp.kdprofile');
            })
            ->leftJOIN('strukresep_t as sr', function ($j) {
                $j->on('sr.norec', '=', 'pp.strukresepfk')->on('sr.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJOIN('ruangan_m as ru2', function ($j) {
                $j->on('ru2.id', '=', 'sr.ruanganfk')->on('sr.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('departemen_m as dp2', function ($j) {
                $j->on('dp2.id', '=', 'ru2.objectdepartemenfk')->on('dp2.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'sr.penulisresepfk')->on('pg.kdprofile', '=', 'sr.kdprofile');
            })
            ->leftJOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'prd.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'prd.kdprofile');
            })
            ->leftJOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'prd.objectdetailjenisprodukfk',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("case when prd.objectdetailjenisprodukfk = 3011 and pp.isobat <> true then 'Lain-Lain' when pp.isobat = true then 'Obat' else 'Tindakan' end as detailjenisproduk"),
                DB::raw("
            case when pp.jasa is not null then pp.jasa else 0 end jasa,
            COALESCE (pp.hargadiscount, 0)  as diskon,
            case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
            (
                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                 * pp.jumlah)
            + (case when pp.jasa is not null then pp.jasa else 0 end)
             as total,
            to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
            case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
            case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
            case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
           ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->orderBy('pp.tglpelayanan', 'desc')
            ->get();

        $sDokterPemeriksa = $this->settingDataFixed('jenisPetugasDokterPemeriksa', $r['kdprofile']);
        $pelayananpetugas = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('pelayananpasienpetugas_t as ptu', function ($join) {
                $join->on('ptu.nomasukfk', '=', 'apd.norec')->on('ptu.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($join) {
                $join->on('pg.id', '=', 'ptu.objectpegawaifk')->on('pg.kdprofile', '=', 'ptu.kdprofile');
            })
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.objectjenispetugaspefk', 4)
            ->where('pd.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['kdprofile'])
            ->get();



        $res['total'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $res['total'] = $res['total'] + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                }
            }
        }

        $res['billing'] = collect($data)->groupBy('namaruangan');
        $res['ismultipenjamin'] = false;
        $res['klaim'] = $this->getTotalKlaim($r['noregistrasi'], $r['kdprofile']);
        $res['deposit'] = $this->getDepositPasien($r['noregistrasi']);
        $res['dibayar'] = $this->getTotolBayar($r['noregistrasi'], $r['kdprofile']);
        $res['sisa'] = $res['total'] - $res['dibayar'] - $res['deposit'] - $res['klaim'];
        $res['pdf'] = false;
        $blade = 'report.kasir.cetak-billing-va';

        // dd($res);

        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] = collect($data)->groupBy('departemen_group');
            $blade = 'report.kasir.cetak-billing-va';
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'r')
        );
    }

    public function cetakBillCaraBayar(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $noregistrasi = $r['noregistrasi'];
        $dibayar = 0;
        $res['identitas'] = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('pelayananpasien_t AS pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('tempattidur_m AS tt', 'tt.id', '=', 'apd.nobed')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'pd.tglclosing',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'pa.nosep',
                'ps.namaayah',
                'sp.nostruk',
                'ps.penanggungjawab',
                'tt.reportdisplay AS nobed',
                'sbm.nosbm',
                DB::raw("coalesce(sbm.totaldiskon, 0) as totaldiskon"),
                DB::raw("case when sp.nostruk ilike 'D%' then sp.totaldeposit else sp.totalhargasatuan end as totaldibayar"),
                'ps.nocm',
                'ps.nobpjs',
                'ps.tgllahir',
                'alm.alamatlengkap',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur")
            )


            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('sp.norec', $r['norec_sp'])
            ->first();

        // $res['identitas']->umur = $this->hitungUmur($res['identitas']->tgllahir);
        $res['identitas']->tgllahir = $this->getDateIndo($res['identitas']->tgllahir);

        $billQuery = DB::table('pasiendaftar_t as pd')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.norec', '=', 'sbm.nostrukfk')
            ->leftjoin('strukbuktipenerimaancarabayar_t AS sbmcr', function ($j) {
                $j->on('sbm.norec', '=', 'sbmcr.nosbmfk');
            })
            ->leftjoin('carabayar_m AS cb', function ($j) {
                $j->on('cb.id', '=', 'sbmcr.objectcarabayarfk');
            })
            ->select(
                'sp.nostruk',
                'sbm.nosbm',
                DB::raw("CAST(to_char(sbmcr.totaldibayar, '999999999FM') as NUMERIC) as totalharusdibayar"),
                'cb.carabayar',
                DB::raw("CAST(to_char(sbm.totaldibayar, '999999999FM') as NUMERIC) as totaldibayar"),
                DB::raw("case when sp.nostruk ilike 'D%' then true else false end as isdeposit"),
            )
            ->where('pd.statusenabled', true)
            ->where('sbmcr.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            // ->where('sp.norec', $r['norec_sp'])
            ->groupBy(
                'sp.nostruk',
                'sbm.nosbm',
                'sbmcr.totaldibayar',
                'sbm.totaldibayar',
                'cb.carabayar',
                'sp.totaldeposit'
            );

        $billcb = (clone $billQuery)->where('sp.norec', $r['norec_sp'])->get();
        if (count($billcb) == 0) {
            $billcb = $billQuery->get();
        }

        $multi = DB::table('strukpelayananpenjamindetail_t as sppd')
        ->select(
            DB::raw('null as nostruk'),
            DB::raw('null as nosbm'),
            DB::raw("CAST(to_char(sppd.totalharusdibayar, '999999999FM') as NUMERIC) as totalharusdibayar"),
            'rk.namarekanan as carabayar',
            DB::raw("CAST(to_char(sppd.totalppenjamin, '999999999FM') as NUMERIC) as totaldibayar"),
            DB::raw('false as isdeposit'),
        )
        // ->select('sppd.totalppenjamin', 'sppd.totalharusdibayar', 'rk.namarekanan')
        ->join('strukpelayananpenjamin_t as spp', 'sppd.strukpelayananpenjaminfk', 'spp.norec')
        ->join('pasiendaftar_t as pd', 'pd.nostruklastfk', 'spp.nostrukfk')
        ->leftjoin('rekanan_m as rk', 'rk.id', 'sppd.kdrekananpenjamin')
        ->where('pd.noregistrasi', $r['noregistrasi'])
        ->where('sppd.statusenabled', true)
        ->where('sppd.keteranganlainnya', 'Multi Penjamin')
        ->get();

        $data = array_merge(collect($billcb)->toArray(),collect($multi)->toArray());
        // return $data;


        for($x = 0; $x < count($data); $x++){
            $dibayar += $data[$x]->totalharusdibayar;
        }
        // var_dump($dibayar);
        
        $res['dibayar'] = $dibayar;
        $res['pdf'] = false;
        $res['indo'] = isset($r['bangsa']) && $r['bangsa'] != 'WNI' ? false : true;
        // $res['indo'] = true;
        $blade = 'report.kasir.cetak-billing-carabayar';

        // dd($res);

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'r', 'data')
        );
    }

    public function cetakBillCaraBayarNon(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $print = false;
        $pageWidth = 950;
        $dibayar = 0;
        $res['user'] = $r['user'];
        $res['identitas'] = DB::table('strukpelayanan_t as sp')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->select(
                DB::raw("null as noregistrasi"),
                DB::raw("null as tglregistrasi"),
                DB::raw("null as tglpulang"),
                DB::raw("null as tglclosing"),
                'sp.namapasien_klien as namapasien',
                DB::raw("null as nocm"),
                DB::raw("null as namaruangan"),
                DB::raw("null as namakelas"),
                DB::raw("null as kelompokpasien"),
                DB::raw("null as namadepartemen"),
                DB::raw("null as jeniskelamin"),
                DB::raw("null as namalengkap"),
                DB::raw("null as namarekanan"),
                DB::raw("null as nosep"),
                DB::raw("null as namaayah"),
                'sp.nostruk',
                DB::raw("null as penanggungjawab"),
                DB::raw("null as reportdisplay"),
                'sbm.nosbm',
                DB::raw("null as nocm"),
                DB::raw("null as nobpjs"),
                DB::raw("null as tgllahir"),
                DB::raw("null as alamatlengkap"),
                DB::raw("case when sp.nostruk ilike 'D%' then sp.totaldeposit else sp.totalharusdibayar end as totaldibayar")
            )


            ->where('sp.statusenabled', true)
            ->where('sp.norec', $r['norec_sp'])
            ->first();

        // $res['identitas']->umur = $this->hitungUmur($res['identitas']->tgllahir);

        $data = DB::table('strukpelayanan_t AS sp')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftjoin('strukbuktipenerimaancarabayar_t AS sbmcr', function ($j) {
                $j->on('sbm.norec', '=', 'sbmcr.nosbmfk');
            })
            ->leftjoin('carabayar_m AS cb', function ($j) {
                $j->on('cb.id', '=', 'sbmcr.objectcarabayarfk');
            })
            ->select(
                'sp.nostruk',
                'sbm.nosbm',
                'sbmcr.totaldibayar',
                'cb.carabayar',
                DB::raw("case when sp.nostruk ilike 'D%' then sp.totaldeposit else sp.totalharusdibayar::float8 end as totalharusdibayar")
            )
            ->where('sp.statusenabled', true)
            ->where('sp.norec', $r['norec_sp'])
            ->groupBy(
                'sp.nostruk',
                'sbm.nosbm',
                'sbmcr.totaldibayar',
                'sp.totalharusdibayar',
                'sp.totaldeposit',
                'cb.carabayar',
            )
            ->get();

        for($x = 0; $x < count($data); $x++){
            $dibayar += $data[$x]->totalharusdibayar;
        }
        $res['dibayar'] = $dibayar;
        $res['pdf'] = false;
        // $res['indo'] = isset($r['bangsa']) && $r['bangsa'] != 'WNI' ? false : true;
        $res['indo'] = true;
        $blade = 'report.kasir.cetak-billing-carabayar';

        // dd($res);

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'r', 'data')
        );
    }

    public function cetakKwitansi(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $print = false;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $noregistrasi = $r['noregistrasi'];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('pelayananpasien_t AS pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftjoin('strukbuktipenerimaancarabayar_t AS sbmcr', function ($j) {
                $j->on('sbm.norec', '=', 'sbmcr.nosbmfk');
            })
            ->leftjoin('carabayar_m AS cb', function ($j) {
                $j->on('cb.id', '=', 'sbmcr.objectcarabayarfk');
            })
            ->select(
                'sp.nostruk',
                'sbm.nosbm',
                'ps.namapasien',
                'ps.nocm',
                DB::raw("case when sp.nostruk ilike 'D%' then sp.totaldeposit else sp.totalhargasatuan end as totaldibayar"),
                'cb.carabayar',
                DB::raw("case when sp.nostruk ilike 'D%' then sp.totaldeposit else sp.totalhargasatuan::float8 end as totalharusdibayar")
            )
            ->where('pd.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('sp.norec', $r['norec_sp'])
            ->groupBy(
                'sp.nostruk',
                'sbm.nosbm',
                'sp.totalhargasatuan',
                'sp.totaldeposit',
                'cb.carabayar',
                'ps.namapasien',
                'ps.nocm'
            )
            ->get();

        $res['pdf'] = false;
        $blade = 'report.kasir.cetak-kwitansi';

        // dd($res);

        return view(
            $blade,
            compact('profile', 'print', 'res', 'r', 'data')
        );
    }

    public function getLaporanPenyerahanObat(Request $request)
    {
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $kdProfile = (int) $this->kdProfile;

        $data = DB::table('strukresep_t as sr')
            ->leftJoin('antrianapotik_t as aa', 'aa.noresep', '=', 'sr.noresep')
            ->leftJoin('strukorder_t as so', 'so.norec', '=', 'sr.orderfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sr.ruanganfk')
            ->leftJoin('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruanganfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->where('sr.kdprofile', $kdProfile)
            ->whereBetween('sr.tglresep', [$tglAwal, $tglAkhir])
            ->whereNotNull('aa.noantri')
            ->select(
                'so.noorder',
                'sr.noresep',
                'pm.nocm',
                'pd.noregistrasi',
                'pm.namapasien',
                'jk.jeniskelamin',
                'so.tglorder',
                'sr.tglresep as tglverifikasi',
                DB::raw("CONCAT(aa.jenis,'-', aa.noantri) AS noantri"),
                'so.namapengambilorder',
                'so.tglambilorder',
                'ru.namaruangan as namaruanganapotik',
                'kp.kelompokpasien',
                'so.keterangankeperluan',
                'so.isreseppulang as checkreseppulang',
                'ru2.namaruangan AS namaruanganrawat'
            );

        if (isset($request['jeniskemasan']) && $request['jeniskemasan'] != "" && $request['jeniskemasan'] != "undefined") {
            if ($request['jeniskemasan'] == 1) {
                $data = $data->where('aa.jenis', 'R');
            } else {
                $data = $data->where('aa.jenis', 'N');
            }
        }

        if (isset($request['IdFarmasi']) && $request['IdFarmasi'] != "" && $request['IdFarmasi'] != "undefined") {
            $data = $data->where('sr.ruanganfk', $request['IdFarmasi']);
        }

        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function suratPendaftaranRanap(Request $request)
    {

        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $profile->id);
        $pageWidth = 1100;

        $mantanRanap = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
            ->select('pd.nocmfk', 'ru.namaruangan')
            ->where('pd.nocmfk', $request->nocmfk)
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->count();

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'pd.nocmfk', 'ps.id')
            ->leftjoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', 'jk.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', 'kp.id')
            ->leftjoin('agama_m as ag', 'ag.id', 'ps.objectagamafk')
            ->leftjoin('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
            ->leftjoin('asalrujukan_m as ar', 'pd.asalrujukanfk', 'ar.id')
            ->leftjoin('statusperkawinan_m as sp', 'sp.id', 'ps.objectstatusperkawinanfk')
            ->leftjoin('pekerjaan_m as pg', 'pg.id', 'ps.objectpekerjaanfk')
            ->leftjoin('kebangsaan_m as kb', 'kb.id', 'ps.objectkebangsaanfk')
            ->leftjoin('suku_m as sku', 'sku.id', 'ps.objectsukufk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('propinsi_m as pro', 'pro.id', 'alm.objectpropinsifk')
            ->leftJoin('kotakabupaten_m as kk', 'kk.id', 'alm.objectkotakabupatenfk')
            ->leftJoin('kecamatan_m as kt', 'kt.id', 'alm.objectkecamatanfk')
            ->leftJoin('desakelurahan_m as dk', 'dk.id', 'alm.objectdesakelurahanfk')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'ps.alamatrmh',
                'ps.nocm',
                'pd.objectkelompokpasienlastfk',
                'kp.kelompokpasien',
                'pd.tglregistrasi',
                'ps.tempatlahir',
                'ps.nohp',
                'ps.bahasa',
                'kb.name as kebangsaan',
                'jk.jeniskelamin',
                'ps.umurpenanggungjawab',
                'ps.jeniskelaminpenanggungjawab',
                'ps.pekerjaanpenangggungjawab',
                'ps.hubungankeluargapj',
                'ps.penanggungjawab',
                'ps.telponpenanggungjawab',
                'ag.agama',
                'sp.statusperkawinan',
                'ru.namaruangan',
                'ar.asalrujukan',
                'ps.namakeluarga',
                'alm.alamatlengkap',
                'jk.id as jkid',
                DB::raw("EXTRACT(YEAR FROM AGE(NOW(), ps.tgllahir)) AS tahun"),
                'pro.namapropinsi',
                'kk.namakotakabupaten',
                'alm.rtrw',
                'kt.namakecamatan',
                'dk.namadesakelurahan',
                'alm.kodepos',
                'sku.suku',
                'ps.objectpendidikanfk',
                'sp.id as statuskawinid',
                'ps.nohp',
                'pg.pekerjaan',
                'pd.bahasa',
                'pd.dikunjungi',
                'pd.bantuanpelayanan',
                'pd.bantuanpenerjemah'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.norec', $request->norec)
            ->first();

        $dataReport = array(
            'profile' => $profile,
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $data,
            'isRanap' => $mantanRanap > 1 ? true : false
        );

        // return $dataReport;
        return view(
            'report.registrasi.suratpendaftaranranap',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function suratKeluarMasuk(Request $request)
    {
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $profile->id);
        $pageWidth = 1100;
        $data = DB::table('pasiendaftar_t as pd')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'pd.norec', 'apd.noregistrasifk')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', 'kp.id')
            ->join('pasien_m as ps', 'pd.nocmfk', 'ps.id')
            ->leftJoin('pegawai_m as peg', 'peg.id', 'pd.objectpegawaifk')
            ->leftJoin('rekanan_m as rke', 'rke.id', 'pd.objectrekananfk')
            ->leftjoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', 'jk.id')
            ->leftjoin('agama_m as ag', 'ag.id', 'ps.objectagamafk')
            ->leftjoin('kelas_m as ks', 'ks.id', 'pd.objectkelasfk')
            ->leftjoin('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
            ->leftjoin('asalrujukan_m as ar', 'pd.asalrujukanfk', 'ar.id')
            ->leftjoin('statusperkawinan_m as sp', 'sp.id', 'ps.objectstatusperkawinanfk')
            ->leftjoin('pekerjaan_m as pg', 'pg.id', 'ps.objectpekerjaanfk')
            ->leftjoin('kebangsaan_m as kb', 'kb.id', 'ps.objectkebangsaanfk')
            ->leftjoin('suku_m as sku', 'sku.id', 'ps.objectsukufk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('propinsi_m as pro', 'pro.id', 'alm.objectpropinsifk')
            ->leftJoin('kotakabupaten_m as kk', 'kk.id', 'alm.objectkotakabupatenfk')
            ->leftJoin('kecamatan_m as kt', 'kt.id', 'alm.objectkecamatanfk')
            ->leftJoin('desakelurahan_m as dk', 'dk.id', 'alm.objectdesakelurahanfk')
            ->leftJoin('pendidikan_m as pend', 'pend.id', 'ps.objectpendidikanfk')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'ps.alamatrmh',
                'ps.nocm',
                'pd.tglregistrasi',
                'ps.tempatlahir',
                'ps.nohp',
                'ps.bahasa',
                'peg.namalengkap',
                'kb.name as kebangsaan',
                'jk.jeniskelamin',
                'ag.agama',
                'sp.statusperkawinan',
                'ru.namaruangan',
                'ar.asalrujukan',
                'alm.alamatlengkap',
                'pd.noregistrasi',
                'apd.tglmasuk',
                'jk.id as jkid',
                DB::raw("EXTRACT(YEAR FROM AGE(NOW(), ps.tgllahir)) AS tahun,EXTRACT(MONTH FROM AGE(NOW(), ps.tgllahir)) AS bulan,EXTRACT(DAY FROM AGE(NOW(), ps.tgllahir)) AS hari"),
                'pro.namapropinsi',
                'kk.namakotakabupaten',
                'alm.rtrw',
                'kt.namakecamatan',
                'dk.namadesakelurahan',
                'alm.kodepos',
                'ks.namakelas',
                'pd.tglmeninggal',
                'pd.tglpulang',
                'ps.namaayah',
                'ps.namaibu',
                'ps.namasuamiistri',
                'ps.penanggungjawab',
                'pend.pendidikan',
                'rke.namarekanan',
                'kp.kelompokpasien',
                'pg.pekerjaan'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            // ->whereNull('apd.tglkeluar')
            ->where('pd.norec', $request->norec)
            ->first();
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'profile' => $profile,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $data,
        );

        return view('report.registrasi.lembar-masuk-keluar', compact('dataReport', 'pageWidth', 'profile'));
    }

    public function buktiPembayaran(Request $request)
    {
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $profile->id);
        $pageWidth = 1100;

        return view('report.kasir.tandabuktipembayaran', compact('pageWidth', 'profile'));
    }

    public function cetakResumMedis(Request $request)
    {
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                'report.resume-medis',
                array(
                    'profile' => $profile,
                    // 'data' => $data,
                    // 'terbilang' => $terbilang,
                    // 'tglAwal' => $tglAwal,
                    // 'tglAkhir' => $tglAkhir,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.resume-medis',
                compact('profile'),
            );
        }
    }
    public function cetakLabelRacikanResep(Request $request)
    {
        $norecpd = $request->norecpd;
        $pageWidth = '500p';
        $dataReport = DB::table('strukresep_t AS sr')->select(
            'pd.tglregistrasi',
            'ps.nocm',
            'ps.namapasien',
            'ps.tgllahir',
            'pr.namaproduk',
            'ss.satuanstandar',
            'sr.tglresep',
            'sr.noresep',
            'pp.jumlah',
            'jer.jeniskemasan as  jeniskemasan',
            DB::raw("CASE WHEN pp.ispagi = true THEN 'Pagi' ELSE '' END AS pagi"),
            DB::raw("CASE WHEN pp.issiang = true THEN 'Siang' ELSE '' END AS siang"),
            DB::raw("CASE WHEN pp.issore = true THEN 'Sore' ELSE '' END AS sore"),
            DB::raw("CASE WHEN pp.ismalam = true THEN 'Malam' ELSE '' END AS malam"),
            'pp.keteranganpakai',
            'jrc.jenisracikan',
            'sn.satuanresep',
            'pp.aturanpakai',
            'pp.tglkadaluarsa'
        )
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoin('satuanresep_m as sn', 'pp.satuanresepfk', '=', 'sn.id')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->where('sr.kdprofile', $this->kdProfile)
            ->where('jer.jeniskemasan', 'Racikan')
            ->where('pd.norec', '=', $norecpd)
            ->get();

        $blade = "report.farmasi.cetak-label-racikan";
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 323.15, 270.40]);
            $pdf->setPaper([0, 0, 323.15, 290.40]);
            $pdf->loadView(
                $blade,
                array(
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'dataReport' => $dataReport,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    public function cetakLabelGizi(Request $request)
    {
        $depRI = $this->settingFix('idDepRawatInap');
        $depIGD = $this->settingFix('idDepartemenIGD');
        $profile = Profile::where('statusenabled', true)->where('id', $this->kdProfile)->first();

        $data = DB::table('orderpelayanan_t as op')
            ->join('ruangan_m as r', 'r.id', '=', 'op.objectruanganfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->join('strukorder_t as so', 'op.strukorderfk', '=', 'so.norec')
            ->join('kelas_m as kls', 'kls.id', '=', 'op.objectkelasfk')
            ->join('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->join('jeniswaktu_m as jw', 'jw.id', '=', 'op.objectjeniswaktufk')
            ->join('jenisdiet_m as jd', 'jd.id', '=', 'op.jenisdietfk')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'op.objectruanganfk');
            })
            ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->where('op.norec', $request->norec)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->whereIn('r.objectdepartemenfk', [$depRI, $depIGD])
            ->select(
                'ps.namapasien as nama',
                'ps.tgllahir',
                'ps.nocm',
                'r.namaruangan as ruangan',
                'r.objectdepartemenfk',
                'tt.reportdisplay as nobed',
                'km.namakamar as kamar',
                'kls.namakelas as kelas',
                'kd.kategorydiet',
                'jw.jeniswaktu as waktu',
                'jd.jenisdiet',
                // 'op.jenisdietexternal as jdexternal',
                'op.keteranganlainnya as keterangan',
                // 'op.batasKonsumsiAwal',
                // 'op.batasKonsumsiAkhir',
                'so.tglorder',
            )->first();

        $blade = "report.gizi._label-gizi";
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 200, 327.15], 'landscape');
            $pdf->setPaper([0, 0, 210, 200]);
            $pdf->loadView(
                $blade,
                array(
                    'request' => $request,
                    'res' => array(
                        'pdf' => true
                    ),
                    'data' => $data,
                    'profile' => $profile
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('request', 'data', 'profile')
            );
        }
    }

    public function cetakMultipleLabelGizi(Request $r)
    {
        $dataRequest = json_decode($r['data'], true);
        $array = $dataRequest['array'];
        $arrayAPD = array_column($array, 'norec_apd');
        $arrayOP = array_column($array, 'norec_op');
        $depRI = $this->settingFix('idDepRawatInap');
        $depIGD = $this->settingFix('idDepartemenIGD');

        $profile = Profile::where('statusenabled', true)->where('id', $this->kdProfile)->first();
        $data = DB::table('orderpelayanan_t as op')
            ->join('ruangan_m as r', 'r.id', '=', 'op.objectruanganfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->join('strukorder_t as so', 'op.strukorderfk', '=', 'so.norec')
            ->join('kelas_m as kls', 'kls.id', '=', 'op.objectkelasfk')
            ->join('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->join('jeniswaktu_m as jw', 'jw.id', '=', 'op.objectjeniswaktufk')
            ->join('jenisdiet_m as jd', 'jd.id', '=', 'op.jenisdietfk')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'op.objectruanganfk');
            })
            ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->whereIn('so.norec_apd', $arrayAPD)
            ->whereIn('op.norec', $arrayOP)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->whereIn('r.objectdepartemenfk', [$depRI, $depIGD])
            ->select(
                'ps.namapasien as nama',
                'ps.tgllahir',
                'ps.nocm',
                'r.namaruangan as ruangan',
                'r.objectdepartemenfk',
                'tt.reportdisplay as nobed',
                'km.namakamar as kamar',
                'kls.namakelas as kelas',
                'kd.kategorydiet',
                'jw.jeniswaktu as waktu',
                'jd.jenisdiet',
                // 'op.jenisdietexternal as jdexternal',
                'op.keteranganlainnya as keterangan',
                // 'op.batasKonsumsiAwal',
                // 'op.batasKonsumsiAkhir',
                'so.tglorder',
            )
            ->orderBy('pd.tglregistrasi', 'asc')
            ->get();

        $dataResult = [
            'message' => '@epic',
            'data' => $data
        ];

        $blade = "report.gizi._label-gizi";
        if ($dataRequest['pdf'] == 1) {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 190, 327.15], 'landscape');
            $pdf->setPaper([0, 0, 210, 200]);
            $pdf->loadView($blade, compact('dataResult'));
            return $pdf->stream();
        } else {
            return view($blade, compact('dataResult'));
        }
    }

    public function cetakResepObat23(Request $request)
    {

        $norec = $request['norec'];
        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
        "))->first();

        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk,jk.jeniskelamin AS jk,
        kpp.kelompokpasien AS kelopokpasien, rek.namarekanan AS penjamin,ps.nohp AS noteleponfaks,
        al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM bln DD hr') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        '-' AS alergi,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tgl,pp.rke,pr. ID AS kdproduk,
        pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
        CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa,pp.hargasatuan,to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep,pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai,
        pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip ,
        CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,
        ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,pp.qtydetailresep
        FROM pelayananpasienobatkronis_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        left join alamat_m as al on al.nocmfk=ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec='$norec'
        "))->first();

        $detail = collect(DB::select("
        SELECT
            pd.noregistrasi,
            ps.nocm,
            '=' AS umur,
            ps.namapasien AS namapasienjk,
            jk.jeniskelamin AS jk,
            CONCAT(kpp.kelompokpasien, ' (', rek.namarekanan, ')') AS penjamin,
            ps.nohp AS noteleponfaks,
            al.alamatlengkap AS alamat,
            TO_CHAR(ps.tgllahir, 'DD-MM-YYYY') AS tgllahir,
            TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur,
            pd.tglregistrasi,
            ru.namaruangan AS ruanganpasien,
            '-' AS alergi,
            sr.noresep,
            djp.detailjenisproduk,
            ru2.namaruangan,
            pp.tglpelayanan AS tgl,
            pp.rke,
            pr.id AS kdproduk,
            pr.namaproduk AS namaprodukstandar,
            sstd.satuanstandar,
            pp.jumlah,
            COALESCE(pp.jasa, 0) AS jasa,
            pp.hargasatuan,
            TO_CHAR(sr.tglresep, 'DD-MM-YYYY') AS tglresep,
            pp.dosis,
            CONCAT(pp.aturanpakai, ' ', COALESCE(ssr.satuanresep, '')) AS aturanpakai,
            pp.jumlah AS qtyhrg,
            (pp.jumlah * (pp.hargasatuan - COALESCE(pp.hargadiscount, 0))) +
                COALESCE(pp.jasa, 0) AS totalharga,
            jnskem.jeniskemasan,
            pgw.namalengkap,
            pgw.nosip,
            CASE
                WHEN kpp.kelompokpasien = 'BPJS' THEN 0
                ELSE ((pp.jumlah * pp.hargasatuan) - COALESCE(pp.hargadiscount, 0) * pp.jumlah) + COALESCE(pp.jasa, 0)
            END AS totalbiaya,
            pp.qtydetailresep,
            CASE
                WHEN kpp.kelompokpasien = 'BPJS' THEN
                    (pp.jumlah * (pp.hargasatuan - COALESCE(pp.hargadiscount, 0))) +
                    COALESCE(pp.jasa, 0)
                ELSE 0
            END AS totalplatofon
        FROM pelayananpasienobatkronis_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        LEFT JOIN alamat_m AS al ON al.nocmfk = ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        LEFT JOIN rekanan_m AS rek ON rek.id = pd.objectrekananfk
        LEFT JOIN detailjenisproduk_m AS djp ON djp.id = pr.objectdetailjenisprodukfk
        LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec = :norec
        ORDER BY pp.rke ASC
    ", ['norec' => $norec]));



        $detail = $detail->groupBy('jeniskemasan');


        $pageWidth = 950;

        $dataReport = array(
            'datas' => $data,
            'detail' => $detail,
            'user' => $this->getNamaPegawai()
        );

        // if ($request['pdf'] == 'true') {
        //     $pdf = App::make('dompdf.wrapper');
        //     $pdf->loadView(
        //         'report.farmasi.cetak-resep-obat-23-dom',
        //         array(
        //             'r' => $request,
        //             'profile' => $profile,
        //             'dataReport' => $dataReport,
        //             'res' => array(
        //                 'pdf' => true
        //             ),
        //             'pageWidth' => $pageWidth
        //         )
        //     );
        //     return $pdf->stream();
        // } else {
        //     return view(
        //         'report.farmasi.cetak-resep-obat-23-new',
        //         compact('dataReport', 'pageWidth', 'profile')
        //     );
        // }
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : false;
        $blade = "report.farmasi.cetak-resep-obat-23-new";
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'landscape');
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper([0, 0, 461.55, 841.00],'landscape');
            // $pdf->setPaper('a2');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'res' => $res,
                    'profile' => $profile,
                    'user' => $this->getNamaPegawai()
                )
            );
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper('A5', 'landscape');
            return $pdf->stream();
        } else {
            return view($blade, compact('dataReport', 'res', 'profile'));
        }
    }
    public function cetakKwitansiObat23(Request $request)
    {

        $norec = $request['norec'];
        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
        "))->first();

        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk,jk.jeniskelamin AS jk,
        kpp.kelompokpasien || ' ( ' || rek.namarekanan || ' ) ' AS penjamin,ps.nohp AS noteleponfaks,
        al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM bln DD hr') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        '-' AS alergi,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tgl,pp.rke,pr. ID AS kdproduk,
        pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
        CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa,pp.hargasatuan,to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep,pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai,
        pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip ,
        CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,
        ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,pp.qtydetailresep
        FROM pelayananpasienobatkronis_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        left join alamat_m as al on al.nocmfk=ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec='$norec'
        "))->first();

        $detail = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk,jk.jeniskelamin AS jk,
        kpp.kelompokpasien || ' ( ' || rek.namarekanan || ' ) ' AS penjamin,ps.nohp AS noteleponfaks,
        al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        '-' AS alergi,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tgl,pp.rke,pr. ID AS kdproduk,
        pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
        CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa,pp.hargasatuan,to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep,pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai,
        pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip ,
        CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,pp.hargasatuan,pp.hargadiscount,((pp.hargasatuan-pp.hargadiscount)*pp.jumlah)+pp.jasa as total,
        ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,pp.qtydetailresep
        FROM pelayananpasienobatkronis_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        left join alamat_m as al on al.nocmfk=ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec='$norec' order by pp.rke asc
        "));


        $detail = $detail->groupBy('jeniskemasan');

        $pageWidth = 950;

        $dataReport = array(
            'datas' => $data,
            'detail' => $detail,

        );
        if (isset($request["pdf"])) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('report.farmasi.cetak-kwitansi-obat-23-dom', array(
                'dataReport' => $dataReport,
                'pageWidth' => $pageWidth,
                'profile' => $profile,
            ));
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.cetak-kwitansi-obat-23',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }


    public function labelCustom(Request $request)
    {

        $arrayProdukfk = explode(',', $request->produkfk);

        $data = DB::table('strukresep_t as sr')
            ->JOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', 'pd.norec')
            ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->join('alamat_m as ap', 'ap.nocmfk', 'ps.id')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
            ->LeftJOIN('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->LeftJOIN('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
            ->select(
                'sr.tglresep',
                'sr.pasienfk',
                'sr.noresep',
                'sr.ruanganfk',
                'ps.objectkebangsaanfk',
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'pp.hargasatuan',
                'pp.tglregistrasi',
                'ru.namaruangan',
                'ps.tgllahir',
                'pp.tglpemakaian',
                'jk.jeniskemasan',
                'pp.aturanpakai',
                'rt.name as route',
                'pr.namaproduk',
                'ss.satuanstandar',
                'pp.jumlah',
                'pp.dosis',
                'pp.jenisracikanfk',
                DB::raw("
                CASE 
                    WHEN pp.ispagi != 't' THEN ''  
                    WHEN ps.objectkebangsaanfk = 1 THEN 'PAGI' 
                    ELSE 'MORNING' 
                END AS pagi
                "),
                DB::raw("
                    CASE 
                        WHEN pp.issiang != 't' THEN ''  
                        WHEN ps.objectkebangsaanfk = 1 THEN 'SIANG' 
                        ELSE 'AFTERNOON' 
                    END AS siang
                "),
                DB::raw("
                    CASE 
                        WHEN pp.issore != 't' THEN '' 
                        WHEN ps.objectkebangsaanfk = 1 THEN 'SORE' 
                        ELSE 'EVENING' 
                    END AS sore
                "),
                DB::raw("
                    CASE 
                        WHEN pp.ismalam != 't' THEN ''  
                        WHEN ps.objectkebangsaanfk = 1 THEN 'MALAM' 
                        ELSE 'NIGHT' 
                    END AS malam
                "),
                'pr.kekuatan',
                'pp.keteranganpakai',
                'pp.iskronis',
                'sn.satuanresep',
                'pp.tglkadaluarsa',
                'ap.alamatlengkap',
            )
            ->where('sr.kdprofile', $this->kdProfile)
            ->where('sr.norec', $request['norec_resep'])
            ->whereIn('pp.produkfk', $arrayProdukfk)
            ->get();

        $pageWidth = '500p';
        $blade = $request['injeksi'] == 'true' ? "report.farmasi.cetak-label-inject" : "report.farmasi.cetak-label-gabung";
        $profile = DB::table('profile_m')->where('id', $this->kdProfile)->first();
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 323.15, 290.40]);
            $pdf->setPaper([0, 0, 210, 200]);
            $pdf->loadView(
                $blade,
                array(
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'dataReport' => $data,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    function cetakRekapExpertise(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $kelompokpasien = $request->kelompokpasien;
        $tglAwal = Carbon::parse($request['tglAwal'])->format('Y-m-d');
        $tglAkhir = Carbon::parse($request['tglAkhir'])->format('Y-m-d');
        $sDokterPemeriksa = $request['dokter'];
        try {
            $data = DB::table('hasilradiologi_t as hr')
                ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'hr.pegawaifk')
                ->join('pelayananpasien_t as pp', 'pp.norec', '=', 'hr.pelayananpasienfk')
                ->leftJoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
                ->when($tglAwal && $tglAkhir, function ($query) use ($tglAwal, $tglAkhir) {
                    $query->where('hr.tanggalreport', '>=', "$tglAwal 00:00:00")
                        ->where('hr.tanggalreport', '<=', "$tglAkhir 23:59:59");
                })
                ->when($sDokterPemeriksa, function ($query, $sDokterPemeriksa) {
                    return $query->where('pg1.id', $sDokterPemeriksa);
                })
                ->where('hr.statusenabled', true)
                ->groupBy('pr.namaproduk', 'pg1.namalengkap', 'djp.detailjenisproduk')
                ->select('pr.namaproduk', 'pg1.namalengkap as dokter', 'djp.detailjenisproduk', DB::raw('count(hr.norec) as qty'))
                ->get();
            if ($sDokterPemeriksa !== "") {
                $dokter = DB::table('pegawai_m')->select('namalengkap')->where('id', $sDokterPemeriksa)->first();
            } else {
                $dokter = "";
            }
            $pageWidth = 950;
            $result = [
                "data" => $data,
                "pageWidth" => $pageWidth,
                "tglAwal" => $tglAwal,
                "tglAkhir" => $tglAkhir,
                "dokter" => $dokter,
            ];
        } catch (Exception $e) {
            $data = [];
            $result = [
                "status" => 400,
                "message" => $e->getMessage() . $e->getLine(),
                "data" => []
            ];
        }
        return view('report.radiologi.rekap-expertise', compact('data', 'pageWidth', 'tglAwal', 'tglAkhir', 'dokter'));
    }

    public function cetakMCU()
    {
        $pageWidth = 950;
        return view('report.mcu.cetak-mcu', compact('pageWidth'));
    }

    function loadEMR($r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            // ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', '=', $r['norec_pd']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();

        $cppt = DB::connection('mongodb')
            ->table('CPPTDetail')
            // ->where('norec_pd', $r['norec_pd'])
            ->where('nocmfk', $nocmfk)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
        }
        $cppt = $cppt->get();


        if (count($res) > 0) {
            $res = $res->toArray();
            $cppt = $cppt->toArray();
            foreach ($res as $k => $rr) {
                $res[$k]['details'] = [];
                foreach ($cppt as $z => $x) {
                    unset($cppt[$z]['_id']);
                    if ($rr['emrpasienfk'] == $cppt[$z]['emrpasienfk']) {
                        $res[$k]['details'][] = $cppt[$z];
                    }
                }
                unset($res[$k]['_id']);
            }
        }
        return $res;
    }

    public function cetakSKS(Request $r)
    {

        // $data = $this->respond($res);
        // return $data;
        // $id = $this->getPegawaiId();
        // $dokter = $this->getNamaPegawai();
        $res = $this->loadEMR($r);
        $id = $res[0]['dokterPemeriksa']['value'];
        $dokter = DB::table('pegawai_m')->where('id', $id)->select("*")->get();
        $noDokumen = 'RM D.24.4.L';
        $pageWidth = 950;
        return view('report.mcu.cetak-mcu-kesehatan', compact('res', 'pageWidth', 'dokter', 'noDokumen'));
    }
    public function cetakSKJiwa(Request $r)
    {

        // $data = $this->respond($res);
        // return $data;
        $res = $this->loadEMR($r);
        $id = isset($res[0]['dokterPemeriksaJiwa']) ? $res[0]['dokterPemeriksaJiwa']['value'] : '';
        if ($id === '') {
            $dokter = '';
        } else {
            $dokter = DB::table('pegawai_m')->where('id', $id)->select("*")->get();
        }
        $noDokumen = "";
        $pageWidth = 950;
        return view('report.mcu.cetak-mcu-pemeriksaan-kesehatan-jiwa', compact('res', 'pageWidth', 'dokter', 'noDokumen'));
    }
    public function cetakSKNapza(Request $r)
    {

        // $data = $this->respond($res);
        // return $data;
        $res = $this->loadEMR($r);
        // $id = $res[0]['dokterPemeriksaNapza']['value'];
        $id = isset($res[0]['dokterPemeriksaNapza']) ? $res[0]['dokterPemeriksaNapza']['value'] : '';
        if ($id === '') {
            $dokter = '';
        } else {
            $dokter = DB::table('pegawai_m')->where('id', $id)->select("*")->get();
        }
        $noDokumen = 'RM D.24.5.L';
        $pageWidth = 950;
        return view('report.mcu.cetak-mcu-pemeriksaan-napza', compact('res', 'pageWidth', 'dokter', 'noDokumen'));
    }
    public function cetakHasilAntigen(Request $request)
    {
        $data = DB::table('hasillabpcr_t as hs')->where('hs.norec', $request['norec'])
            ->join('pelayananpasien_t as pp', 'pp.norec', 'hs.pelayananpasienfk')
            ->leftJoin('produk_m as p', 'p.id', 'pp.produkfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jm', 'jm.id', 'ps.objectjeniskelaminfk')
            ->leftJoin('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
            ->leftJoin('spesimenpcr_m as pc', 'pc.id', 'hs.jenisspesimenfk')
            ->leftJoin('metodepemeriksanpcr_m as mp', 'mp.id', 'hs.metodeperiksafk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('pelayananpasienpetugas_t as pps', 'pps.pelayananpasien', 'pp.norec')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', 'pps.pegawaiverifikatorfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', 'pps.objectpegawaifk')
            ->leftJoin('pegawai_m as pg4', 'pg4.id', 'hs.petugasfk')
            ->leftJoin('pegawai_m as pg5', 'pg5.id', 'hs.petuggasapprovalfk')
            ->leftJoin('produk_m as pro', 'pro.id', 'pp.produkfk')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'jm.jeniskelamin',
                'ps.paspor',
                'pg.namalengkap as pengirim',
                'ps.noidentitas',
                'pc.namajenisspesimen as jenisspesimen',
                'pc.specimentname as typespeciment',
                'hs.tglterimasampel',
                'hs.tglselesaisampel',
                'alm.alamatlengkap as alamat',
                'hs.kodesampel',
                'mp.judulmetode',
                'mp.methodname',
                'mp.methodtitle',
                'so.noorder as noorder',
                'mp.namametodeperiksa',
                'hs.nopemerikasaan',
                'hs.hasil',
                'hs.keterangan',
                'pps.pegawaiverifikatorfk',
                'pg2.namalengkap as verifikator',
                'pg3.namalengkap as verifikator2',
                'pg4.namalengkap as petugaspemeriksa',
                'pg5.namalengkap as petugasapproval',
                'pro.namaproduk as namaproduk'
            )
            ->first();
        $dataQr = $data->noorder . $data->namapasien . $data->jeniskelamin . 'NIK :' . $data->noidentitas . 'Passport:' . $data->paspor . 'Telah melakukan' . $data->namaproduk . $data->tglselesaisampel . 'DENGAN HASIL' . $data->hasil == true ? 'Positif' : 'Negatif';
        $blade = 'report.laboratorium.cetak-antigen';
        $pageWidth = 950;
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($dataQr ?? ""));
        $request['pdf'] = 'true';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'qrcode' => $qrcode,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'data' => $data,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('data', 'pageWidth', 'request')
            );
        }
    }
    public function cetakHasilMikro(Request $request)
    {
        $data = DB::table('hasilmikro_t as hs')->where('hs.norec', $request['norec'])
            ->join('pelayananpasien_t as pp', 'pp.norec', 'hs.pelayananpasienfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jm', 'jm.id', 'ps.objectjeniskelaminfk')
            ->leftJoin('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', 'hs.pemeriksakultur')
            ->leftJoin('pegawai_m as dr', 'dr.id', 'pd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'hs.dokterpemeriksafk')
            ->leftJoin('produk_m as pro', 'pro.id', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
            // ->join('diagnosa_m as diag', 'diag.kddiagnosa', 'pd.inacbg_diagnosa')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'jm.jeniskelamin',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn') as umur"),
                'ps.paspor',
                'pp.tglregistrasi as tanggalmasukrs',
                'dr.namalengkap as dpjp',
                'ps.noidentitas',
                'hs.namajenisspesimen as jenisspesimen',
                'hs.namaasalspesimen as asalspesimen',
                'pg.namalengkap as dokterpemeriksa',
                'hs.asalspesimen',

                'hs.spesimenke',
                'hs.tglterimaspesimen',
                'hs.tgldikerjakanspesimen',
                'hs.hasilspesimen',
                'hs.pmn',
                'hs.gpc',
                'hs.gpr',
                'hs.gndc',
                'hs.sec',
                'hs.gnr',
                'hs.gncb',
                'hs.bas',
                'hs.eos',
                'hs.bat',
                'hs.seg',
                'hs.limf',
                'hs.sun',
                'hs.ycphh',
                'hs.pemeriksaan',
                'hs.pemeriksaanpenunjang',
                'hs.tglkultur',
                'hs.namapemeriksakultur',
                'hs.observasikultur',
                'hs.hasilakhirkultur',
                'hs.hasilujikepekaan',
                'hs.tglkeluarhasil',
                'hs.antibiotik',
                'hs.note',
                // 'diag.namadiagnosa as diagnosa',
                'hs.tglterimasampel',
                'hs.tglkeluarhasil',
                'hs.tglkultur',
                'alm.alamatlengkap as alamat',
                'hs.kodespesimen',
                'ps.nocm',
                'ps.noidentitas',
                'ps.nohp',
                'ps.alamatlengkap',
                'ru.namaruangan',
                // 'mp.methodtitle',
                'so.noorder as noorder',
                // 'mp.namametodeperiksa',
                // 'hs.nopemerikasaan',
                // 'hs.hasil',
                // 'hs.keterangan',
                // 'pps.pegawaiverifikatorfk',
                'pg2.namalengkap as pemeriksakultur',
                // 'pg3.namalengkap as verifikator2',
                // 'pg4.namalengkap as petugaspemeriksa',
                // 'pg5.namalengkap as petugasapproval',
                'pro.namaproduk as namaproduk'
            )
            ->first();
        // $dataQr =$data->noorder .$data->namapasien .$data->jeniskelamin . 'NIK :' .$data->noidentitas . 'Passport:' .$data->paspor. 'Telah melakukan' . $data->namaproduk .$data->tglselesaisampel .'DENGAN HASIL' .$data->hasil == true ? 'Positif' : 'Negatif';
        $blade = 'report.laboratorium.cetak-mikro';
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate("http://apps.transmedic.co.id:8989/service/cetak-hasil-mikro?pdf=true&norec=".$request['norec']));
        
        // $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($dataQr ?? ""));
        // $request['pdf'] = 'true';
        if ($request['pdf'] == 'true') {
            $pageWidth = 950;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'qrcode' =>$qrcode,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'data' => $data,
                    'pdf' => true,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            $pdf = false;
            $pageWidth = 780;
            return view(
                $blade,
                compact('data', 'pageWidth', 'request', 'qrcode', 'pdf')
            );
        }
    }
    public function cetakKwintansiTagihan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];
        $user = $this->getUserId();
        $profile = $this->profile();
        $tglSekarang = date('d/m/Y');
        $data = [];
        $data = DB::table('strukpelayananpenjamin_t AS spp')
            ->select(
                'km.kelompokpasien',
                'spp.norec',
                'stk.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar',
                'spp.totalsudahdibayar',
                'rk.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'po.noposting',
                'stk.kdhistorylogins',
                DB::raw("CASE
                    WHEN dpt.id = 18 THEN 'Tagihan Rawat Jalan'
                    WHEN dpt.id = 16 THEN 'Tagihan Rawat Inap'
                    WHEN dpt.id = 6 THEN 'Tagihan Gizi'
                    WHEN dpt.id = 14 THEN 'Tagihan Farmasi'
                    WHEN dpt.id = 24 THEN 'Tagihan IGD'
                    WHEN dpt.id = 27 THEN 'Tagihan Radiologi'
                    WHEN dpt.id = 115 THEN 'Tagihan Cathlab'
                    WHEN dpt.id = 127 THEN 'Tagihan Homecare'
                    END as keterangan")
            )
            ->join('strukpelayanan_t AS sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pelayananpasien_t AS pp', 'pp.strukfk', '=', 'sp.norec')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m AS dpt', 'dpt.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('pasien_m AS ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('postinghutangpiutang_t AS po', 'po.nostrukfk', '=', 'spp.norec')
            ->leftJoin('strukposting_t AS stk', 'stk.noposting', '=', 'po.noposting')
            ->leftJoin('rekanan_m AS rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelompokpasien_m AS km', 'km.id', '=', 'pd.objectkelompokpasienlastfk')
            // ->whereNotNull('spp.noverifikasi')
            ->where('spp.norec', $norec)
            ->groupBy(
                'km.kelompokpasien',
                'spp.norec',
                'stk.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar',
                'spp.totalsudahdibayar',
                'rk.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'po.noposting',
                'stk.kdhistorylogins',
                'dpt.id'
            )
            ->orderBy('pd.tglregistrasi', 'ASC')
            ->get();

        $pageWidth = 950;
        $totalbayar = $data[0]->totalbiaya;
        $terbilang = $this->terbilang($totalbayar);

        $dateTimestamp1 = "";
        $dateTimestamp2 = "";
        $periode = "";
        $date_arr = array();
        $tglAyeuna = date('d/m/Y');
        if (count($data) == 1) {
            $periode = $this->getDateIndo($data[0]->tglregistrasi);
        } elseif (count($data) > 1) {
            foreach ($data as $itm) {
                $date_arr[] = array(
                    'tgl' => $itm->tglregistrasi
                );
            }
            usort($date_arr, function ($a, $b) {
                $dt[0] = $a['tgl'];
                $dt[1] = $b['tgl'];
                $dateTimestamp1 = strtotime($a['tgl']);
                $dateTimestamp2 = strtotime($b['tgl']);
            });
            $akhir = $date_arr[count($date_arr) - 1];
            $periode = $this->getDateIndo($akhir['tgl']) . " - " . $this->getDateIndo($date_arr[0]['tgl']);
        }

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'datas' => $data,
            'periode' => $periode,
            'terbilang' => $terbilang,
            'tglsekarang' => $tglSekarang,
        );
        $blade = 'report.keuangan.kwitansitagihan';
        $request['pdf'] = true;
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                $blade,
                array(
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'dataReport' => $dataReport,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('pageWidth', 'profile', 'dataReport')
            );
        }
    }
    public function cetakRekapitulasiTagihanAsuransi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $noPosting = $request['noposting'];
        $user = $this->getUserId();
        $profile = $this->profile();
        $data = DB::table('strukpelayananpenjamin_t as spp')
            ->select(
                'kp.id as kpid',
                'kp.kelompokpasien',
                'spp.norec',
                'stp.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar as tarifklaim',
                'bpjs.tarif_inacbg as tarifklaimbpjs',
                'spp.totalsudahdibayar',
                'r.id as rknid',
                'r.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'php.noposting',
                'stp.kdhistorylogins',
                'kls.namakelas',
                'stp.tglposting',
                'ps.tgllahir',
                'gagalbpjs.keterangan',
                DB::raw("CASE WHEN pa.nokepesertaan IS NULL THEN
            CASE WHEN ps.noasuransilain = '' THEN ''
            WHEN ps.noasuransilain IS NULL THEN ''
            WHEN ps.noasuransilain = '-' THEN '-'
            ELSE ps.noasuransilain END
        ELSE pa.nokepesertaan END AS nokepesertaan")
            )
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftJoin('pelayananpasien_t as pp', 'pp.strukfk', '=', 'sp.norec')
            ->leftJoin('antrianpasiendiperiksa_t as ap', 'ap.norec', '=', 'pp.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'ap.noregistrasifk')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'sp.noregistrasifk')
            ->leftJoin('bpjsklaimtxt_t as bpjs', 'bpjs.sep', '=', 'pa.nosep')
            ->leftJoin('bpjsgagalklaimtxt_t as gagalbpjs', 'gagalbpjs.nosep', '=', 'pa.nosep')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftJoin('strukposting_t as stp', 'stp.noposting', '=', 'php.noposting')
            ->leftJoin('rekanan_m as r', 'r.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            // ->whereNotNull('spp.noverifikasi')
            ->where('spp.kdprofile', $kdProfile)
            ->where('php.statusenabled', 1)
            ->where('php.noposting', $noPosting)
            ->groupBy(
                'kp.kelompokpasien',
                'spp.norec',
                'stp.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar',
                'spp.totalsudahdibayar',
                'r.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'php.noposting',
                'stp.kdhistorylogins',
                'kls.namakelas',
                'stp.tglposting',
                'ps.tgllahir',
                'gagalbpjs.keterangan',
                'ps.noasuransilain',
                'pa.nokepesertaan',
                'ps.nobpjs',
                'r.id',
                'kp.id',
                'bpjs.tarif_inacbg'
            )
            ->orderBy('ps.namapasien', 'ASC')
            ->get();

        $dateTimestamp1 = "";
        $dateTimestamp2 = "";
        $periode = "";
        $date_arr = array();
        $tglAyeuna = date('d/m/Y');
        if (count($data) == 1) {
            $periode = $this->getDateIndo($data[0]->tglregistrasi);
        } elseif (count($data) > 1) {
            foreach ($data as $itm) {
                $date_arr[] = array(
                    'tgl' => $itm->tglregistrasi
                );
            }

            usort($date_arr, function ($a, $b) {
                $dt[0] = $a['tgl'];
                $dt[1] = $b['tgl'];
            });
            $akhir = $date_arr[count($date_arr) - 1];
            $periode = $this->getDateIndo($akhir['tgl']) . " - " . $this->getDateIndo($date_arr[0]['tgl']);
        }
        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'datas' => $data,
            'periode' => $periode,
            'tanggal' => $tglAyeuna,
        );
        $blade = 'report.keuangan.rekappitulasi-tagihan';
        $request['pdf'] = 'true';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('pageWidth', 'dataReport', 'profile', 'request')
            );
        }
    }
    public function cetakKwitansiPiutang(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $norec = $request['norec'];
        $user = $this->getUserId();
        $tglregis = $request['tglregistrasi'];
        $profile = $this->kdProfile();
        $data = [];
        $data = DB::table('strukkwitansipiutang_t AS sbk')
            ->where('sbk.statusenabled', true)
            ->where('sbk.kdprofile', $kdProfile)
            ->where('sbk.norec', $norec)
            ->get();
        $pageWidth = 950;
        $totalbayar = $data[0]->nominal;
        $terbilang = $this->terbilang($totalbayar ?? 0);

        $dateTimestamp1 = "";
        $dateTimestamp2 = "";
        $periode = "";
        $date_arr = array();
        $tglAyeuna = date('d/m/Y');
        $periode = $tglregis;

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'datas' => $data,
            'periode' => $periode,
            'terbilang' => $terbilang

        );
        $blade = 'report.keuangan.kwitansipiutang';
        $request['pdf'] = 'true';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakSurat(Request $request)
    {
        $pageWidth = 950;
        $profile = $this->getProfile();
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];
        $user = $this->getUserId();
        $profile = $this->profile();
        $tglSekarang = date('d/m/Y');
        $data = DB::table('strukpelayananpenjamin_t AS spp')
            ->select(
                'km.kelompokpasien',
                'spp.norec',
                'stk.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar',
                'spp.totalsudahdibayar',
                'rk.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'po.noposting',
                'stk.kdhistorylogins',
                DB::raw("CASE
                    WHEN dpt.id = 18 THEN 'Tagihan Rawat Jalan'
                    WHEN dpt.id = 16 THEN 'Tagihan Rawat Inap'
                    WHEN dpt.id = 6 THEN 'Tagihan Gizi'
                    WHEN dpt.id = 14 THEN 'Tagihan Farmasi'
                    WHEN dpt.id = 24 THEN 'Tagihan IGD'
                    WHEN dpt.id = 27 THEN 'Tagihan Radiologi'
                    WHEN dpt.id = 115 THEN 'Tagihan Cathlab'
                    WHEN dpt.id = 127 THEN 'Tagihan Homecare'
                    END as keterangan")
            )
            ->join('strukpelayanan_t AS sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pelayananpasien_t AS pp', 'pp.strukfk', '=', 'sp.norec')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m AS dpt', 'dpt.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('pasien_m AS ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('postinghutangpiutang_t AS po', 'po.nostrukfk', '=', 'spp.norec')
            ->leftJoin('strukposting_t AS stk', 'stk.noposting', '=', 'po.noposting')
            ->leftJoin('rekanan_m AS rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelompokpasien_m AS km', 'km.id', '=', 'pd.objectkelompokpasienlastfk')
            ->where('spp.norec', $norec)
            ->groupBy(
                'km.kelompokpasien',
                'spp.norec',
                'stk.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'spp.totalppenjamin',
                'rk.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'po.noposting',
                'stk.kdhistorylogins',
                'dpt.id'
            )
            ->orderBy('pd.tglregistrasi', 'ASC')
            ->first();
        $terbilang = $this->terbilang($data->totalppenjamin);
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'data' => $data,
            'terbilang' => $terbilang

        );
        $request['pdf'] = 'true';
        $blade = 'report.keuangan.surat-piutang';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }
    public function cetakTagihan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['noPosting'];
        $user = $this->getUserId();
        $profile = $this->profile();
        $data = DB::table('strukpelayananpenjamin_t as spp')
            ->select(
                'kp.id as kpid',
                'kp.kelompokpasien',
                'spp.norec',
                'stp.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar as tarifklaim',
                'bpjs.tarif_inacbg as tarifklaimbpjs',
                'spp.totalsudahdibayar',
                'r.id as rknid',
                'r.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'php.noposting',
                'stp.kdhistorylogins',
                'kls.namakelas',
                'stp.tglposting',
                'ps.tgllahir',
                'gagalbpjs.keterangan',
                DB::raw("CASE WHEN pa.nokepesertaan IS NULL THEN
            CASE WHEN ps.noasuransilain = '' THEN ''
            WHEN ps.noasuransilain IS NULL THEN ''
            WHEN ps.noasuransilain = '-' THEN '-'
            ELSE ps.noasuransilain END
        ELSE pa.nokepesertaan END AS nokepesertaan")
            )
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftJoin('pelayananpasien_t as pp', 'pp.strukfk', '=', 'sp.norec')
            ->leftJoin('antrianpasiendiperiksa_t as ap', 'ap.norec', '=', 'pp.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'ap.noregistrasifk')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'sp.noregistrasifk')
            ->leftJoin('bpjsklaimtxt_t as bpjs', 'bpjs.sep', '=', 'pa.nosep')
            ->leftJoin('bpjsgagalklaimtxt_t as gagalbpjs', 'gagalbpjs.nosep', '=', 'pa.nosep')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftJoin('strukposting_t as stp', 'stp.noposting', '=', 'php.noposting')
            ->leftJoin('rekanan_m as r', 'r.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            // ->whereNotNull('spp.noverifikasi')
            ->where('spp.kdprofile', $kdProfile)
            ->where('php.statusenabled', 1)
            ->where('php.noposting', $norec)
            ->groupBy(
                'kp.kelompokpasien',
                'spp.norec',
                'stp.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar',
                'spp.totalsudahdibayar',
                'r.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'php.noposting',
                'stp.kdhistorylogins',
                'kls.namakelas',
                'stp.tglposting',
                'ps.tgllahir',
                'gagalbpjs.keterangan',
                'ps.noasuransilain',
                'pa.nokepesertaan',
                'ps.nobpjs',
                'r.id',
                'kp.id',
                'bpjs.tarif_inacbg'
            )
            ->orderBy('ps.namapasien', 'ASC')
            ->get();

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'datas' => $data,
        );
        $total = 0;
        foreach ($data as $key => $d) {
            $total += $d->totalppenjamin;
        }
        $terbilang = $this->terbilang($total);
        $pageWidth = 950;
        $request['pdf'] = 'true';
        $blade = 'report.keuangan.tagihan-piutang';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'terbilang' => $terbilang,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function getLapKasir(Request $r)
    {

        $kdProfile = $this->kdProfile;
        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;

        $data = DB::table('strukbuktipenerimaan_t as sbm')
            ->leftJOIN('strukbuktipenerimaancarabayar_t as sbmc', 'sbmc.nosbmfk', '=', 'sbm.norec')
            ->leftJOIN('carabayar_m as cb', 'cb.id', '=', 'sbmc.objectcarabayarfk')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'sbm.nostrukfk')
            ->leftJOIN('loginuser_s as lu', 'lu.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftJOIN('pegawai_m as pg2', 'pg2.id', '=', 'lu.objectpegawaifk')
            ->leftJOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftJOIN('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
            ->leftJOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftJOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'sbm.tglsbm',
                'sbm.nosbm',
                'pd.noregistrasi',
                'totaldibayarbefore',
                'ps.nocm',
                'ru.namaruangan',
                'pg.namalengkap',
                'pg2.namalengkap as kasir',
                'sp.totalharusdibayar',
                'sbmc.totaldibayar as totalPenerimaan',
                // 'sbm.totaldibayar as totalPenerimaan',
                'sbmc.totaldibayar',
                'sbm.totaldiskon',
                'sbm.totalsisapiutang',
                'sbm.keteranganlainnya',
                'cb.carabayar',
                'cb.id as Kode',
                'sbmc.objectcarabayarfk',
                DB::raw('( case when pd.noregistrasi is null then sp.nostruk else pd.noregistrasi end) as noregistrasi,
        (case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end) as namapasien,
        (case when kp.kelompokpasien is null then null else kp.kelompokpasien end) as kelompokpasien,
        (CASE WHEN sp.totalprekanan is null then 0 else sp.totalprekanan end) as hutangpenjamin,
        (case when cb.id = 1 then sbmc.totaldibayar else 0 end) as tunai,
        (case when cb.id != 1 then sbmc.totaldibayar else 0 end) as nontunai')
            )
            ->where('sbm.kdprofile', $kdProfile)
            ->where('sbm.statusenabled', true);

        if (isset($r['tglAwal']) && $r['tglAwal'] != "" && $r['tglAwal'] != "undefined") {
            $data = $data->where('sbm.tglsbm', '>=', $r['tglAwal'] . ' 00:00:00');
        }
        if (isset($r['tglAkhir']) && $r['tglAkhir'] != "" && $r['tglAkhir'] != "undefined") {
            $tgl = $r['tglAkhir'] . ' 23:59:59';
            $data = $data->where('sbm.tglsbm', '<=', $tgl);
        }
        $data = $data->orderBy('pd.noregistrasi', 'ASC');

        $data = $data->get();

        $res['billing'] = $data->groupBy('kelompokpasien');

        $res['pdf'] = $r['pdf'];

        $blade = 'report.bendahara.lapkasir';


        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] = $data->groupBy('kasir');
            $blade = 'report.bendahara.lapkasir';
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage'] = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res')
        );
    }
    public function cetakKartuPiutangPerusahaan(Request $request)
    {
        $filter = $request->all();
        $kdProfile = (int) $this->kdProfile;
        $idPerusahaan = $request->idPerusahaan;
        $start = $request->start;
        $end = $request->end;
        $kodePosting = $request->kodePosting;
        $dataCollector = DB::table('postinghutangpiutang_t as php')
            ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
            ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
            ->join('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
            ->select(
                'sp.noposting as noposting',
                'sp.tglposting as tanggal',
                'sp.keteranganlainnya as keterangan',
                DB::raw('SUM(spp.totalppenjamin) as totalpenjamin'),
                DB::raw('sum(spp.totalsudahdibayar) as sumtotalsudahdibayar'),
                DB::raw("count(php.noposting) as jlhpasien,CASE WHEN spp.totaldiskon IS NULL THEN 0 ELSE spp.totaldiskon END AS totaldiskon "),
                DB::raw("'KPS-'|| rkn.id as idrekanan,'KPS-'|| rkn.id ||' ' || rkn.namarekanan as kps")
            )
            ->where('php.kdprofile', $kdProfile)
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('sp.tglposting', [$start, $end]);
            })
            ->when($kodePosting, function ($Query) use ($kodePosting) {
                $Query->where('sp.noposting', $kodePosting);
            })
            ->where('rkn.id', $request->idPerusahaan)
            ->orderBy('sp.tglposting')
            ->groupBy(
                'sp.noposting',
                'sp.tglposting',
                'sp.keteranganlainnya',
                'spp.totalsudahdibayar',
                'php.noposting',
                'spp.totaldiskon',
                'rkn.id',
                'rkn.namarekanan'
            )
            ->where('sp.statusenabled', '=', true)->get();

        $pageWidth = 950;
        $request['pdf'] = 'true';
        $total = 0;
        foreach ($dataCollector as $key => $c) {
            $total += ($c->totalpenjamin - $c->sumtotalsudahdibayar);
        }
        ;
        $profile = $this->profile();
        $terbilang = $this->terbilang($total);
        $dataReport = [
            'data' => $dataCollector,
            'terbilang' => $this->terbilang($total),
            'profile' => $this->profile(),
            'total' => $total
        ];
        $blade = 'report.keuangan.cetak-kartu-piutang-perusahaan';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'total')
            );
        }
    }

    public function laporanPembayaranPiutangPerusahaan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $filter = $request->all();
        $tglawal = $request->tglAwal;
        $tglakhir = $request->tglAkhir;
        $profile = $this->getProfile();
        $user = $this->getUserId();
        $dataCollector = DB::table('postinghutangpiutang_t as php')
            ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
            ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'spp.nostrukfk')
            ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
            ->join('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
            ->join('loginuser_s as lu', 'sp.kdhistorylogins', '=', 'lu.id')
            ->select(
                'sbm.tglsbm',
                'php.noposting',
                'rkn.id as idRekanan',
                'rkn.namarekanan',
                'php.statusenabled',
                'sbm.keteranganlainnya',
                DB::raw('sum(sbm.totaldibayar) as totaldibayar')
            )
            ->where('php.kdprofile', $kdProfile);
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $dataCollector = $dataCollector->where('sbm.tglsbm', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAwal'] != "undefined") {
            $dataCollector = $dataCollector->where('sbm.tglsbm', '<=', $filter['tglAkhir']);
        }
        if (isset($filter['noPosting']) && $filter['noPosting'] != "") {
            $dataCollector = $dataCollector->where('sp.noposting', 'ilike', '%' . $filter['noPosting'] . '');
        }
        if (isset($filter['idPerusahaan']) && $filter['idPerusahaan'] != "") {
            $dataCollector = $dataCollector->where('rkn.id', '=', $filter['idPerusahaan']);
        }
        $dataCollector = $dataCollector->where('sp.statusenabled', '=', 1);
        $dataCollector = $dataCollector->where('sbm.objectkelompoktransaksifk', $this->settingDataFixed('pembayaranCicilanPiutang', $kdProfile));
        $dataCollector = $dataCollector->groupBy('sbm.tglsbm', 'php.noposting', 'rkn.id', 'rkn.namarekanan', 'php.statusenabled', 'sbm.keteranganlainnya');
        $dataCollector = $dataCollector->orderBy('sbm.tglsbm', 'desc');
        $dataCollector = $dataCollector->get();
        $groupedData = collect($dataCollector)->groupBy('namarekanan');
        $dataReport = [
            'data' => $groupedData,
            'startDate' => $request->tglAwal ?? date('Y-m-d'),
            'endDate' => $request->tglAkhir ?? date('Y-m-d'),
            'user' => $user,
        ];
        $pageWidth = $request->pdf == false ? 950 : '100%';
        $blade = 'report.keuangan.laporan-pembayaran-piutang';
        $request['pdf'] = $request->pdf ?? false;
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }
    public function rekapPembayaranPiutangPerusahaan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $filter = $request->all();
        $tglawal = $request->tglAwal;
        $tglakhir = $request->tglAkhir;
        $profile = $this->getProfile();
        $idPerusahaan = $request->idPerusahaan;
        if (isset($idPerusahaan) && $idPerusahaan != "") {
            $dataRekap = DB::table('postinghutangpiutang_t as php')
                ->selectRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') as tglbayar, 0 as adm, sbm.totaldibayar")
                ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
                ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'spp.nostrukfk')
                ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
                ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
                ->join('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
                ->where('php.kdprofile', $kdProfile)
                ->where('rkn.id', $idPerusahaan)
                ->when($tglawal, function ($query) use ($tglawal) {
                    return $query->whereRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') >= ?", [$tglawal]);
                })
                ->when($tglakhir, function ($query) use ($tglakhir) {
                    return $query->whereRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') <= ?", [$tglakhir]);
                })
                ->where('sp.statusenabled', 1)
                ->where('sbm.objectkelompoktransaksifk', $this->settingDataFixed('pembayaranCicilanPiutang', $kdProfile))
                ->groupBy('tglbayar', 'adm', 'sbm.totaldibayar')
                ->orderBy('tglbayar')
                ->get();
        } else {
            $dataRekap = DB::table('postinghutangpiutang_t as php')
                ->selectRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') as tglbayar, 0 as adm, sbm.totaldibayar")
                ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
                ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'spp.nostrukfk')
                ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
                ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
                ->join('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
                ->where('php.kdprofile', $kdProfile)
                ->where('sp.statusenabled', 1)
                ->when($tglawal, function ($query) use ($tglawal) {
                    return $query->whereRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') >= ?", [$tglawal]);
                })
                ->when($tglakhir, function ($query) use ($tglakhir) {
                    return $query->whereRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') <= ?", [$tglakhir]);
                })
                ->where('sbm.objectkelompoktransaksifk', $this->settingDataFixed('pembayaranCicilanPiutang', $kdProfile))
                ->groupBy('tglbayar', 'adm', 'sbm.totaldibayar')
                ->orderBy('tglbayar')
                ->get();
        }
        $request['pdf'] = 'true';
        $pageWidth = 950;
        $dataReport = [
            'data' => $dataRekap,
            'startDate' => $request->tglAwal ?? date('Y-m-d'),
            'endDate' => $request->tglAkhir ?? date('Y-m-d'),
        ];
        $request['pdf'] = 'true';
        $blade = 'report.keuangan.rekap-pembayaran-piutang';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakLabelBarang(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];

        $profile = collect(DB::select(" select * from profile_m where id = $kdProfile limit 1"))->first();

        $dataBarang = collect(DB::select("
            select ra.norec, ra.noregisteraset, ra.objectprodukfk as kdproduk,
            pr.namaproduk, jp.jenisproduk, ru.id as ruanganasalfk, ru.namaruangan as namaruanganasal,
            ru1.id as ruangancurrenfk, ru1.namaruangan as ruangancurrent, ra.tglregisteraset, ra.tglstrukterima,
            ra.qtyprodukaset, ra.hargaperolehan, ra.tglregisteraset, ra.tglstrukterima, rek.namarekanan as namasupplier,
            rek.alamatlengkap as almSupplier, sp.norec as norecsp, spd.norec as norecspd, ap.asalproduk, ra.keteranganlainnya,
            ra.spesifikasi, ra.jenisaset, ra.judul, ra.kdbmn,ss.satuanstandar,ra.noseri, to_char(ra.tglpembelian, 'YYYY-MM-DD') as tglpembelian, to_char(ra.tgldistribusi,'YYYY-MM-DD') as tgldistribusi
            from registrasiaset_t as ra
            left join strukpelayanan_t as sp on sp.norec = ra.nostrukterimafk
            left join strukpelayanandetail_t as spd on spd.norec = ra.nostrukterimadetailfk
            left join produk_m as pr on pr.id = ra.objectprodukfk
            left join detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
            left join jenisproduk_m as jp on jp.id = djp.objectjenisprodukfk
            left join kelompokproduk_m as kp on kp.id = jp.objectkelompokprodukfk
            left join satuanstandar_m as ss on ss.id = pr.objectsatuanstandarfk
            left join asalproduk_m as ap on ap.id = ra.objectasalprodukfk
            left join ruangan_m as ru on ru.id = ra.objectruanganfk
            left join ruangan_m as ru1 on ru1.id = ra.objectruanganposisicurrentfk
            left join rekanan_m as rek on rek.id = ra.objectsupplier
            where ra.kdprofile = $kdProfile and ra.norec in ($norec)
        "));

        return $dataBarang;
        // $pageWidth = 250;
        $pageWidth = 950;
        $dataReport = array(
            'datas' => $dataBarang
        );

        return view(
            'report.cetak-label-barang',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    public function CetakRBADetail(Request $request)
    {
        $tahun = $request['tahun'];
        $tanggalcetak = $request['tglcetak'];
        $tahap = $request['tahap'];
        $subsubkeg = $request['subsubkegiatan'];
        $all = [];
        $profile = Profile::where('id', $this->kdProfile)->first();
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        // dd($settingrba);
        $qtahap = '';
        if (isset($request['tahap']) && $request['tahap'] != "" && $request['tahap'] != "undefined") {
            $qtahap = ' and kb.objecttahapfk =  ' . $request['tahap'];
        }

        $itahap = DB::select(DB::raw("select id, tahap from tahapanggaran_m where id = $tahap"));

        $qsubsubkegiatan = '';
        $subsubkegiatan = '';
        if (isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != "" && $request['subsubkegiatan'] != "undefined") {
            $qsubsubkegiatan = ' and km.id =  ' . $request['subsubkegiatan'];
            $subsubkegiatan = ' and dp.id =  ' . $request['subsubkegiatan'];
        }
        $asalproduk = DB::select(DB::raw("select * from asalproduk_m where kdprofile = $this->kdProfile and statusenabled = true and isanggaran = true"));
        $head = collect(DB::select("
        select DISTINCT ta.tahap, dp.tahun, dp.kode ||' - '|| dp.keterangan as subsubkegiatan,dp3.kode ||' - '||  dp3.keterangan as kegiatan,
        dp2.kode ||' - '||  dp2.keterangan as program,
        dp1.kode ||' - '|| dp1.keterangan as urusanpemerintahan, dp0.kode ||' - '|| dp0.keterangan  subkegiatan ,dp.indikatormasuk, dp.indikatorkeluaran, dp.indikatorhasil, dp.targetmasuk, dp.targetkeluaran, dp.targethasil
        from kegiatananggaran_m dp
        inner join kegiatananggaran_m as dp3 on dp.kode ilike dp3.kode || '%' and dp3.div = 2
        and dp3.kdprofile = $this->kdProfile and dp3.statusenabled = true
        inner join kegiatananggaran_m as dp2 on dp3.kode ilike dp2.kode || '%' and dp2.div = 1
        and dp2.kdprofile = $this->kdProfile and dp2.statusenabled = true
        inner join kegiatananggaran_m as dp1 on dp2.kode ilike dp1.kode || '%' and dp1.div = 0
        and dp1.kdprofile = $this->kdProfile and dp1.statusenabled = true
        inner join kegiatananggaran_m as dp0 on dp.kode ilike dp0.kode || '%' and dp0.div = 3
        and dp0.kdprofile = $this->kdProfile and dp0.statusenabled = true
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = dp.id
        inner join tahapanggaran_m ta on ta.id = kb.objecttahapfk
        where dp.tahun = '$tahun' $subsubkegiatan $qtahap"))->first();

        $div1 = DB::select(DB::raw("select DISTINCT sum(kb.subtotal) as subtotal, sum(kb.subtotal) as subtotal, mattt.kodemataanggaran as kode1, '' as kode2, '' as kode3, '' as kode4,mattt.kodemataanggaran, mattt.namamataanggaran, mattt.div from kegiatananggaran_m km
        inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
        inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
        inner join asalproduk_m ap on ap.id = kb.objectasalprodukfk
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        inner join mataanggaran_m mat on ma.kodemataanggaran ilike mat.kodemataanggaran || '%' and mat.div = 3
        inner join mataanggaran_m matt on mat.kodemataanggaran ilike matt.kodemataanggaran || '%' and matt.div = 2
        inner join mataanggaran_m mattt on matt.kodemataanggaran ilike mattt.kodemataanggaran || '%' and mattt.div = 1
        where km.id = $subsubkeg and kb.objecttahapfk = $tahap and km.tahun = '$tahun' and kb.statusenabled = true and kms.statusenabled = true and kb.kdprofile= $this->kdProfile
        GROUP BY mattt.kodemataanggaran, mattt.kodemataanggaran, mattt.namamataanggaran, mattt.div
        order by kode1 asc
        "));


        $div2 = DB::select(DB::raw("select DISTINCT sum(kb.subtotal) as subtotal,split_part(matt.kodemataanggaran, '.', 1) as kode1, split_part(matt.kodemataanggaran, '.', 2) as kode2, '' as kode3, '' as kode4,
        matt.kodemataanggaran, matt.namamataanggaran, matt.div from kegiatananggaran_m km
        inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
        inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
        inner join asalproduk_m ap on ap.id = kb.objectasalprodukfk
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        inner join mataanggaran_m mat on ma.kodemataanggaran ilike mat.kodemataanggaran || '%' and mat.div = 3
        inner join mataanggaran_m matt on mat.kodemataanggaran ilike matt.kodemataanggaran || '%' and matt.div = 2
        inner join mataanggaran_m mattt on matt.kodemataanggaran ilike mattt.kodemataanggaran || '%' and mattt.div = 1
        where km.id = $subsubkeg and kb.objecttahapfk = $tahap and km.tahun = '$tahun' and kb.statusenabled = true and kms.statusenabled = true and kb.kdprofile= $this->kdProfile
        GROUP BY matt.kodemataanggaran, matt.namamataanggaran, matt.div
        order by kode1, kode2 asc
        "));
        $div3 = DB::select(DB::raw("select DISTINCT sum(kb.subtotal) as subtotal, split_part(mat.kodemataanggaran, '.', 1) as kode1, split_part(mat.kodemataanggaran, '.', 2) as kode2, split_part(mat.kodemataanggaran, '.', 3) as kode3, '' as kode4,
        mat.kodemataanggaran, mat.namamataanggaran, mat.div from kegiatananggaran_m km
        inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
        inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
        inner join asalproduk_m ap on ap.id = kb.objectasalprodukfk
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        inner join mataanggaran_m mat on ma.kodemataanggaran ilike mat.kodemataanggaran || '%' and mat.div = 3
        inner join mataanggaran_m matt on mat.kodemataanggaran ilike matt.kodemataanggaran || '%' and matt.div = 2
        inner join mataanggaran_m mattt on matt.kodemataanggaran ilike mattt.kodemataanggaran || '%' and mattt.div = 1
        where km.id = $subsubkeg and kb.objecttahapfk = $tahap and km.tahun = '$tahun' and kb.statusenabled = true and kms.statusenabled = true and kb.kdprofile= $this->kdProfile
        GROUP BY mat.kodemataanggaran, mat.namamataanggaran, mat.div
        order by kode1, kode2, kode3 asc
        "));

        $data = DB::select(DB::raw("
        select distinct ap.asalproduk, ap.id as id_ap, split_part(ma.kodemataanggaran, '.', 1) as kode1, split_part(ma.kodemataanggaran, '.', 2) as kode2, split_part(ma.kodemataanggaran, '.', 3) as kode3,
        split_part(ma.kodemataanggaran, '.', 4)||'.'||split_part(ma.kodemataanggaran, '.', 5) as kode4, ma.kodemataanggaran,ma.namamataanggaran, kb.keteranganbelanja, kb.hargasatuan, kb.satuan, kb.jml, kb.subtotal, ma.div from kegiatananggaran_m km
        inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
        inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
        inner join asalproduk_m ap on ap.id = kb.objectasalprodukfk
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        where km.id = $subsubkeg and kb.objecttahapfk = $tahap and km.tahun = '$tahun' and kb.statusenabled = true and kms.statusenabled = true and kb.kdprofile= $this->kdProfile and kb.kdprofile=1
        order by kode1, kode2, kode3,kode4 asc
        "));
        $buatsubtotaldiv1 = [];
        foreach ($div1 as $d1) {
            $all[] = array(
                'kode1' => $d1->kode1,
                'kode2' => '',
                'kode3' => '',
                'kode4' => '',
                'kodemataanggaran' => $d1->kodemataanggaran,
                'namamataanggaran' => $d1->namamataanggaran,
                'div' => $d1->div,
                'jml' => '',
                'satuan' => '',
                'hargasatuan' => '',
                'keteranganbelanja' => '',
                'id_ap' => '',
                'subtotal' => $d1->subtotal,
            );
            foreach ($div2 as $d2) {
                if ($d1->kode1 == $d2->kode1 && $d2->div == 2) {
                    $all[] = array(
                        'kode1' => '',
                        'kode2' => $d2->kode2,
                        'kode3' => '',
                        'kode4' => '',
                        'kodemataanggaran' => $d2->kodemataanggaran,
                        'namamataanggaran' => $d2->namamataanggaran,
                        'div' => $d2->div,
                        'jml' => '',
                        'satuan' => '',
                        'hargasatuan' => '',
                        'keteranganbelanja' => '',
                        'id_ap' => '',
                        'subtotal' => $d2->subtotal

                    );
                    $subtotaldv1 = 0;
                    foreach ($div3 as $d3) {
                        if ($d2->kode2 == $d3->kode2 && $d3->div == 3) {
                            $all[] = array(
                                'kode1' => '',
                                'kode2' => '',
                                'kode3' => $d3->kode3,
                                'kode4' => '',
                                'kodemataanggaran' => $d3->kodemataanggaran,
                                'namamataanggaran' => $d3->namamataanggaran,
                                'div' => $d3->div,
                                'jml' => '',
                                'satuan' => '',
                                'hargasatuan' => '',
                                'keteranganbelanja' => '',
                                'id_ap' => '',
                                'subtotal' => $d3->subtotal
                            );
                            $rownum = 1;
                            $subtotaldv1 = 0;
                            foreach ($data as $dd) {
                                if ($d3->kode3 == $dd->kode3 && $dd->div == 4) {
                                    if ($d1->kode1 == $dd->kode1) {
                                        $d1->subtotal += $dd->subtotal;
                                    }
                                    if ($d2->kode2 == $dd->kode2) {
                                        $d2->subtotal += $dd->subtotal;
                                    }
                                    if ($rownum == 1) {
                                        $all[] = array(
                                            'kode1' => '',
                                            'kode2' => '',
                                            'kode3' => '',
                                            'kode4' => $dd->kode4,
                                            'kodemataanggaran' => $dd->kodemataanggaran,
                                            'namamataanggaran' => $dd->namamataanggaran,
                                            'keteranganbelanja' => $dd->keteranganbelanja,
                                            'jml' => $dd->jml,
                                            'satuan' => $dd->satuan,
                                            'hargasatuan' => $dd->hargasatuan,
                                            'div' => $dd->div,
                                            'subtotal' => $dd->subtotal,
                                            'id_ap' => $dd->id_ap
                                        );
                                        $rownum++;
                                    }

                                    $all[] = array(
                                        'kode1' => '',
                                        'kode2' => '',
                                        'kode3' => '',
                                        'kode4' => $dd->kode4,
                                        'kodemataanggaran' => $dd->kodemataanggaran,
                                        'namamataanggaran' => $dd->namamataanggaran,
                                        'keteranganbelanja' => $dd->keteranganbelanja,
                                        'jml' => $dd->jml,
                                        'satuan' => $dd->satuan,
                                        'hargasatuan' => $dd->hargasatuan,
                                        'div' => $dd->div,
                                        'subtotal' => $dd->subtotal,
                                        'id_ap' => $dd->id_ap,
                                        'rownum' => $rownum
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        // dd($div1);
        $pageWidth = 950;
        return view('report.anggaran.cetakrbadetail', compact('pageWidth', 'itahap', 'tanggalcetak', 'settingrba', 'profile', 'head', 'asalproduk', 'all'));
    }
    public function CetakRekapSumberDanaAnggaranSebelum(Request $request)
    {
        $tahun = $request['tahun'];
        $tahap = $request['tahap'];

        $profile = Profile::where('id', $this->kdProfile)->first();
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();


        $qtahap = '';
        if (isset($request['tahap']) && $request['tahap'] != "" && $request['tahap'] != "undefined") {
            $qtahap = ' and kb.objecttahapfk =  ' . $request['tahap'];
        }

        $tahap = DB::select(DB::raw("select tahapanggaran_m.*,$tahun as thn from tahapanggaran_m where id = $tahap"));
        $tahapnow = $request['tahap'];
        $data = \DB::select(DB::raw("select a.kode, a.div, a.kode1 kode2,
        a.kegiatan,a.jasalayanan,a.apbd,a.dak,a.danais,a.silpa,a.total
        from(

                select y.kegiatan, y.kode, y.kode1, y.div, sum(y.apbd) apbd, sum(y.jasalayanan) jasalayanan, sum(y.dak) dak, sum(y.danais) danais, sum(y.silpa) silpa,
                sum(y.apbd) + sum(y.jasalayanan) + sum(y.dak) + sum(y.danais) + sum(y.silpa) as total
                from(
                        select x.kegiatan, x.subkegiatan, x.kode, x.kode1, x.div,
                        case when x.id_asalproduk = 15 then x.subtotal else 0 end as apbd,
                        case when x.id_asalproduk = 18 then subtotal else 0 end as jasalayanan,
                        case when x.id_asalproduk = 21 then subtotal else 0 end as dak,
                        case when x.id_asalproduk = 2 then subtotal else 0 end as danais,
                        case when x.id_asalproduk = 4 then subtotal else 0 end as silpa

                        from(
                                select kmss.keterangan kegiatan, ap.id as id_asalproduk,kmss.kode, 0 kode1, kmss.div, kms.keterangan subkegiatan, sum(kb.subtotal) subtotal
                                from kegiatananggaran_m km
                                inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
                                inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
                                inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
                                inner join asalproduk_m ap on ap.id = kb.objectasalprodukfk
                                where km.kdprofile = $this->kdProfile and km.statusenabled = true
                                and kms.kdprofile = $this->kdProfile and kms.statusenabled = true
                                and kmss.kdprofile = $this->kdProfile and kmss.statusenabled = true
                                and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
                                and ap.kdprofile = $this->kdProfile and ap.statusenabled = true
                                and km.tahun = '$tahun'
                                $qtahap
                                group by kms.keterangan, kmss.keterangan, kmss.kode, kmss.div, ap.id
                        ) as x group by x.kegiatan, x.subkegiatan, x.subtotal, x.kode, x.kode1, x.div, x.id_asalproduk
                ) as y group by kegiatan, kode, kode1, div

                union all

                select y.subkegiatan as kegiatan, y.kode, y.kode1, y.div, sum(y.apbd) apbd, sum(y.jasalayanan) jasalayanan, sum(y.dak) dak, sum(y.danais) danais, sum(y.silpa) silpa,
                sum(y.apbd) + sum(y.jasalayanan) + sum(y.dak) + sum(y.danais) + sum(y.silpa) as total
                from(
                        select x.kegiatan, x.subkegiatan, x.kode, kode1, x.div,
                        case when x.id_asalproduk = 15 then x.subtotal else 0 end as apbd,
                        case when x.id_asalproduk = 18 then subtotal else 0 end as jasalayanan,

                        case when x.id_asalproduk = 21 then subtotal else 0 end as dak,
                        case when x.id_asalproduk = 2 then subtotal else 0 end as danais,
                        case when x.id_asalproduk = 4 then subtotal else 0 end as silpa

                        from(
                                select kmss.keterangan kegiatan, kms.keterangan subkegiatan, kms.kode, 0 kode1, kms.div, ap.id as id_asalproduk,sum(kb.subtotal) subtotal
                                from kegiatananggaran_m km
                                inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
                                inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
                                inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
                                inner join asalproduk_m ap on ap.id = kb.objectasalprodukfk
                                where km.kdprofile = $this->kdProfile and km.statusenabled = true
                                and kms.kdprofile = $this->kdProfile and kms.statusenabled = true
                                and kmss.kdprofile = $this->kdProfile and kmss.statusenabled = true
                                and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
                                and ap.kdprofile = $this->kdProfile and ap.statusenabled = true
                                and km.tahun = '$tahun'
                                $qtahap
                                group by kms.keterangan, kmss.keterangan, kms.kode, kms.div, ap.id
                        ) as x group by x.kegiatan, x.subkegiatan, x.subtotal, x.kode, x.kode1, x.div, x.id_asalproduk
                ) as y group by subkegiatan, kode, kode1, div

                union all

                select case when $tahapnow=1 or split_part( y.subkegiatan, '-', 2 ) = '' then y.subkegiatan else split_part(y.subkegiatan, '-', 2) end as kegiatan,
                y.kode, y.kode1, y.div, sum(y.apbd) apbd, sum(y.jasalayanan) jasalayanan, sum(y.dak) dak, sum(y.danais) danais, sum(y.silpa) silpa,
                sum(y.apbd) + sum(y.jasalayanan) + sum(y.dak) + sum(y.danais) + sum(y.silpa) as total
                from(
                        select x.kegiatan, x.subkegiatan, x.kode, x.kode1, x.div,
                        case when x.id_asalproduk = 15 then x.subtotal else 0 end as apbd,
                        case when x.id_asalproduk = 18 then subtotal else 0 end as jasalayanan,

                        case when x.id_asalproduk = 21 then subtotal else 0 end as dak,
                        case when x.id_asalproduk = 2 then subtotal else 0 end as danais,
                        case when x.id_asalproduk = 4 then subtotal else 0 end as silpa

                        from(
                                select km.keterangan kegiatan, km.keterangan subkegiatan,
                                km.kode as kode,
                                CAST(split_part(km.kode,'.',7) as int) as kode1,
                                km.div, ap.id as id_asalproduk,sum(kb.subtotal) subtotal
                                from kegiatananggaran_m km
                                inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
                                inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
                                inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
                                inner join asalproduk_m ap on ap.id = kb.objectasalprodukfk
                                where km.kdprofile = $this->kdProfile and km.statusenabled = true
                                and kms.kdprofile = $this->kdProfile and kms.statusenabled = true
                                and kmss.kdprofile = $this->kdProfile and kmss.statusenabled = true
                                and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
                                and ap.kdprofile = $this->kdProfile and ap.statusenabled = true
                                and km.tahun = '$tahun'
                                $qtahap
                                group by km.keterangan, km.keterangan, km.kode, km.div, ap.id
                        ) as x group by x.kegiatan, x.subkegiatan, x.subtotal, x.kode, x.kode1, x.div, x.id_asalproduk
                ) as y group by subkegiatan, kode, kode1, div

        ) as a order by CAST(split_part(kode,'.',1) as int), CAST(split_part(kode,'.',2) as int), CAST(split_part(kode,'.',3) as int),
				CAST(split_part(kode,'.',4) as int),CAST(split_part(kode,'.',5) as int),CAST(NULLIF(split_part( kode, '.', 6 ), '') AS INT), div, kode2"));

        $grandtotal = collect(DB::select("
        SELECT SUM ( xx.jasalayanan ) AS jasapelayanan, SUM ( xx.apbd ) AS apbd, SUM ( xx.dak ) AS dak, SUM ( xx.danais ) AS danais, SUM ( xx.silpa ) AS silpa, SUM ( xx.total ) AS total  FROM ( SELECT A .kode, A.div,
                    CASE WHEN A.div = 2 THEN SUBSTR( A.kode, 9, 4 )  WHEN A.div = 3 THEN SUBSTR( A.kode, 14, 2 ) WHEN A.div = 4 THEN A.kode END AS kode,
                        A.kode1 kode2, A.kegiatan, A.jasalayanan, A.apbd, A.dak, A.danais, A.silpa, A.total  FROM ( SELECT y.kegiatan, y.kode, y.kode1, y.div, SUM ( y.apbd ) apbd,
                            SUM ( y.jasalayanan ) jasalayanan, SUM ( y.dak ) dak, SUM ( y.danais ) danais, SUM ( y.silpa ) silpa, SUM ( y.apbd ) + SUM ( y.jasalayanan ) + SUM ( y.dak ) + SUM ( y.danais ) + SUM ( y.silpa ) AS total  FROM ( SELECT x.kegiatan, x.subkegiatan,
                                x.kode, x.kode1, x.div,
                            CASE WHEN x.id_asalproduk = 15 THEN x.subtotal ELSE 0  END AS apbd,
                            CASE WHEN x.id_asalproduk = 18 THEN subtotal ELSE 0  END AS jasalayanan,

                            CASE WHEN x.id_asalproduk = 21 THEN subtotal ELSE 0  END AS dak,
                            CASE WHEN x.id_asalproduk = 2 THEN subtotal ELSE 0  END AS danais,
                            CASE WHEN x.id_asalproduk = 4 THEN subtotal ELSE 0  END AS silpa

                            FROM ( SELECT kmss.keterangan kegiatan, ap.ID AS id_asalproduk, kmss.kode, 0 kode1, kmss.div, kms.keterangan subkegiatan,
                                    SUM ( kb.subtotal ) subtotal  FROM
                                    kegiatananggaran_m km
                                    INNER JOIN kegiatananggaran_m kms ON km.kode ILIKE kms.kode || '%'
                                    AND kms.div = 3
                                    inner JOIN kegiatananggaran_m kmss ON km.kode ILIKE kmss.kode || '%'
                                    AND kmss.div = 2
                                    INNER JOIN keteranganbelanja_t kb ON kb.objectkegiatanfk = km.
                                    ID INNER JOIN asalproduk_m ap ON ap.ID = kb.objectasalprodukfk
                                WHERE
                                    km.kdprofile = $this->kdProfile  AND km.statusenabled = TRUE
                                    AND kms.kdprofile = $this->kdProfile AND kms.statusenabled = TRUE
                                    AND kmss.kdprofile = $this->kdProfile AND kmss.statusenabled = TRUE
                                    AND kb.kdprofile = $this->kdProfile AND kb.statusenabled = TRUE
                                    and ap.kdprofile = $this->kdProfile and ap.statusenabled = true
                                    AND km.tahun = '$tahun'
                                    $qtahap
                                GROUP BY
                                    kms.keterangan,
                                    kmss.keterangan,
                                    kmss.kode,
                                    kmss.div,
                                    ap.ID
                                ) AS x
                            GROUP BY
                                x.kegiatan,
                                x.subkegiatan,
                                x.subtotal,
                                x.kode,
                                x.kode1,
                                x.div,
                                x.id_asalproduk
                            ) AS y
                        GROUP BY
                            kegiatan,
                            kode,
                            kode1,
                            div UNION ALL
                        SELECT
                            y.subkegiatan AS kegiatan,
                            y.kode,
                            y.kode1,
                            y.div,
                            SUM ( y.apbd ) apbd,
                            SUM ( y.jasalayanan ) jasalayanan,

                            SUM ( y.dak ) dak,
                            SUM ( y.danais ) danais,
                            SUM ( y.silpa ) silpa,

                            SUM ( y.apbd ) + SUM ( y.jasalayanan ) + SUM ( y.dak ) + SUM ( y.danais ) + SUM ( y.silpa ) AS total
                        FROM
                            (
                            SELECT
                                x.kegiatan,
                                x.subkegiatan,
                                x.kode,
                                kode1,
                                x.div,
                            CASE

                                    WHEN x.id_asalproduk = 15 THEN
                                    x.subtotal ELSE 0
                                END AS apbd,
                            CASE

                                    WHEN x.id_asalproduk = 18 THEN
                                    subtotal ELSE 0
                                END AS jasalayanan,

                            CASE
                                WHEN x.id_asalproduk = 21 THEN
                                subtotal ELSE 0
                            END AS dak,
                            CASE
                                WHEN x.id_asalproduk = 2 THEN
                                subtotal ELSE 0
                            END AS danais,
                            CASE
                                WHEN x.id_asalproduk = 4 THEN
                                subtotal ELSE 0
                            END AS silpa

                        FROM
                                (
                                SELECT
                                    kmss.keterangan kegiatan,
                                    kms.keterangan subkegiatan,
                                    kms.kode,
                                    0 kode1,
                                    kms.div,
                                    ap.ID AS id_asalproduk,
                                    SUM ( kb.subtotal ) subtotal
                                FROM
                                    kegiatananggaran_m km
                                    INNER JOIN kegiatananggaran_m kms ON km.kode ILIKE kms.kode || '%'
                                    AND kms.div = 3
                                    inner JOIN kegiatananggaran_m kmss ON km.kode ILIKE kmss.kode || '%'
                                    AND kmss.div = 2
                                    INNER JOIN keteranganbelanja_t kb ON kb.objectkegiatanfk = km.
                                    ID INNER JOIN asalproduk_m ap ON ap.ID = kb.objectasalprodukfk
                                WHERE
                                    km.kdprofile = $this->kdProfile AND km.statusenabled = TRUE
                                    AND kms.kdprofile = $this->kdProfile AND kms.statusenabled = TRUE
                                    AND kmss.kdprofile = $this->kdProfile AND kmss.statusenabled = TRUE
                                    AND kb.kdprofile = $this->kdProfile AND kb.statusenabled = TRUE
                                    and ap.kdprofile = $this->kdProfile and ap.statusenabled = true
                                    AND km.tahun = '$tahun'
                                    $qtahap
                                GROUP BY
                                    kms.keterangan,
                                    kmss.keterangan,
                                    kms.kode,
                                    kms.div,
                                    ap.ID
                                ) AS x
                            GROUP BY
                                x.kegiatan,
                                x.subkegiatan,
                                x.subtotal,
                                x.kode,
                                x.kode1,
                                x.div,
                                x.id_asalproduk
                            ) AS y
                        GROUP BY
                            subkegiatan,
                            kode,
                            kode1,
                            div UNION ALL
                        SELECT
                            y.subkegiatan AS kegiatan,
                            y.kode,
                            y.kode1,
                            y.div,
                            SUM ( y.apbd ) apbd,
                            SUM ( y.jasalayanan ) jasalayanan,

                            SUM ( y.dak ) dak,
                            SUM ( y.danais ) danais,
                            SUM ( y.silpa ) silpa,

                            SUM ( y.apbd ) + SUM ( y.jasalayanan ) + SUM ( y.dak ) + SUM ( y.danais ) + SUM ( y.silpa ) AS total
                        FROM
                            (
                            SELECT
                                x.kegiatan,
                                x.subkegiatan,
                                x.kode,
                                x.kode1,
                                x.div,
                            CASE

                                    WHEN x.id_asalproduk = 15 THEN
                                    x.subtotal ELSE 0
                                END AS apbd,
                            CASE

                                    WHEN x.id_asalproduk = 18 THEN
                                    subtotal ELSE 0
                                END AS jasalayanan,

                            CASE
                                WHEN x.id_asalproduk = 21 THEN
                                subtotal ELSE 0
                            END AS dak,
                            CASE
                                WHEN x.id_asalproduk = 2 THEN
                                subtotal ELSE 0
                            END AS danais,
                            CASE
                                WHEN x.id_asalproduk = 4 THEN
                                subtotal ELSE 0
                            END AS silpa

                            FROM
                                (
                                SELECT
                                    km.keterangan kegiatan,
                                    km.keterangan subkegiatan,
                                CASE

                                        WHEN LENGTH ( km.kode ) = 17 THEN
                                        LEFT ( km.kode, - 1 )
                                        WHEN LENGTH ( km.kode ) = 18 THEN
                                        LEFT ( km.kode, - 2 )
                                    END AS kode,
                                CASE

                                        WHEN LENGTH ( km.kode ) = 17 THEN
                                        RIGHT ( km.kode, 1 ) :: INT
                                        WHEN LENGTH ( km.kode ) = 18 THEN
                                        RIGHT ( km.kode, 2 ) :: INT
                                    END AS kode1,
                                    km.div,
                                    ap.ID AS id_asalproduk,
                                    SUM ( kb.subtotal ) subtotal
                                FROM
                                    kegiatananggaran_m km
                                    INNER JOIN kegiatananggaran_m kms ON km.kode ILIKE kms.kode || '%'
                                    AND kms.div = 3
                                    inner JOIN kegiatananggaran_m kmss ON km.kode ILIKE kmss.kode || '%'
                                    AND kmss.div = 2
                                    INNER JOIN keteranganbelanja_t kb ON kb.objectkegiatanfk = km.
                                    ID INNER JOIN asalproduk_m ap ON ap.ID = kb.objectasalprodukfk
                                WHERE
                                    km.kdprofile = $this->kdProfile AND km.statusenabled = TRUE
                                    AND kms.kdprofile = $this->kdProfile AND kms.statusenabled = TRUE
                                    AND kmss.kdprofile = $this->kdProfile AND kmss.statusenabled = TRUE
                                    AND kb.kdprofile = $this->kdProfile AND kb.statusenabled = TRUE
                                    and ap.kdprofile = $this->kdProfile and ap.statusenabled = true
                                    AND km.tahun = '$tahun'
                                    $qtahap
                                GROUP BY
                                    km.keterangan,
                                    km.keterangan,
                                    km.kode,
                                    km.div,
                                    ap.ID
                                ) AS x
                            GROUP BY
                                x.kegiatan,
                                x.subkegiatan,
                                x.subtotal,
                                x.kode,
                                x.kode1,
                                x.div,
                                x.id_asalproduk
                            ) AS y
                        GROUP BY
                            subkegiatan,
                            kode,
                            kode1,
                            div
                        ) AS A
                    ORDER BY
                        A.kode,
                    kode1
        ) AS xx"))->first();
        $pageWidth = 1200;
        return view('report.anggaran.cetakrekapanggaransumberdana', compact('grandtotal', 'pageWidth', 'tahap', 'settingrba', 'data'));
    }
    public function CetakPerJenisAnggaran(Request $request)
    {

        $tahun = $request['tahun'];
        $tahap = $request['tahap'];
        $profile = Profile::where('id', $this->kdProfile)->first();
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        // $tanggalcetak = $this->tanggal_indonesia(date('Y-m-d',strtotime($request['tglcetak'])));

        $qtahap = '';
        if (isset($request['tahap']) && $request['tahap'] != "" && $request['tahap'] != "undefined") {
            $qtahap = ' and kb.objecttahapfk =  ' . $request['tahap'];
        }

        $tahap = DB::select(DB::raw("select tahapanggaran_m.*,$tahun thn from tahapanggaran_m where id = $tahap"));
        $tahapnow = $request['tahap'];
        $data = DB::select(DB::raw("select * from(
        select kegiatan, kode, kode1::int, div, sum(jumlahbarangjasa) jumlahbarangjasa, sum(jumlahmodalgedung) jumlahmodalgedung, sum(jumlahpegawai) jumlahpegawai,  sum(jumlahmodalalat) jumlahmodalalat, sum(jumlahmodallainnya) jumlahmodallainnya,
        sum(jumlahbarangjasa) + sum(jumlahmodalgedung)+ sum(jumlahpegawai)+sum(jumlahmodalalat)+sum(jumlahmodallainnya) as total
        from(
        select kegiatan, subkegiatan, kode, kode1::int, div,
        case when id_jenisanggaran = 3 then subtotal else 0 end as jumlahpegawai,
        case when id_jenisanggaran = 4 then subtotal else 0 end as jumlahbarangjasa,
        case when id_jenisanggaran = 5 then subtotal else 0 end as jumlahmodalgedung,
        case when id_jenisanggaran = 6 then subtotal else 0 end as jumlahmodalalat,
        case when id_jenisanggaran = 7 then subtotal else 0 end as jumlahmodallainnya
        from(
        select kmss.keterangan kegiatan, 0 kode1, kmss.kode, kmss.div, kms.keterangan subkegiatan, ja.id as id_jenisanggaran, ja.jenisanggaran, sum(kb.subtotal) subtotal
        from kegiatananggaran_m km
        inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
        inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        inner join mataanggaran_m mat on ma.kodemataanggaran ilike mat.kodemataanggaran || '%' and mat.objectjenisanggaranfk is not null
        inner join jenisanggaran_m ja on ja.id = mat.objectjenisanggaranfk
        where km.kdprofile = $this->kdProfile and km.statusenabled = true
        and kms.kdprofile = $this->kdProfile and kms.statusenabled = true
        and kmss.kdprofile = $this->kdProfile and kmss.statusenabled = true
        and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
        and ma.kdprofile = $this->kdProfile and ma.statusenabled = true
        and mat.kdprofile = $this->kdProfile and mat.statusenabled = true
        and ja.kdprofile = $this->kdProfile and ja.statusenabled = true
        and km.tahun = '$tahun'
        $qtahap
        group by kms.keterangan, kmss.keterangan, kmss.kode, ja.jenisanggaran, kmss.div, ja.id
        ) as x group by kegiatan, subkegiatan, id_jenisanggaran, subtotal, kode, kode1::int, div
        ) as y group by kegiatan, kode, kode1::int, div


        union all


        select subkegiatan kegiatan, kode, kode1::int, div, sum(jumlahbarangjasa) jumlahbarangjasa, sum(jumlahmodalgedung) jumlahmodalgedung, sum(jumlahpegawai) jumlahpegawai,  sum(jumlahmodalalat) jumlahmodalalat, sum(jumlahmodallainnya) jumlahmodallainnya,
        sum(jumlahbarangjasa) + sum(jumlahmodalgedung)+ sum(jumlahpegawai)+sum(jumlahmodalalat)+sum(jumlahmodallainnya) as total
        from(
        select kegiatan, subkegiatan, kode, kode1::int, div,
        case when id_jenisanggaran = 3 then subtotal else 0 end as jumlahpegawai,
        case when id_jenisanggaran = 4 then subtotal else 0 end as jumlahbarangjasa,
        case when id_jenisanggaran = 5 then subtotal else 0 end as jumlahmodalgedung,
        case when id_jenisanggaran = 6 then subtotal else 0 end as jumlahmodalalat,
        case when id_jenisanggaran = 7 then subtotal else 0 end as jumlahmodallainnya
        from(
        select kmss.keterangan kegiatan, kms.keterangan subkegiatan, 0 kode1, kms.kode, kms.div, ja.id as id_jenisanggaran,  ja.jenisanggaran, sum(kb.subtotal) subtotal
        from kegiatananggaran_m km
        inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
        inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        inner join mataanggaran_m mat on ma.kodemataanggaran ilike mat.kodemataanggaran || '%' and mat.objectjenisanggaranfk is not null
        inner join jenisanggaran_m ja on ja.id = mat.objectjenisanggaranfk
        where km.kdprofile = $this->kdProfile and km.statusenabled = true
        and kms.kdprofile = $this->kdProfile and kms.statusenabled = true
        and kmss.kdprofile = $this->kdProfile and kmss.statusenabled = true
            and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
            and ma.kdprofile = $this->kdProfile and ma.statusenabled = true
            and mat.kdprofile = $this->kdProfile and mat.statusenabled = true
            and ja.kdprofile = $this->kdProfile and ja.statusenabled = true
            and km.tahun = '$tahun'
        $qtahap
        group by kms.keterangan, kmss.keterangan, kms.kode, ja.jenisanggaran, kms.div, ja.id
        ) as x group by kegiatan, subkegiatan, jenisanggaran, subtotal, kode, kode1::int, div, id_jenisanggaran
        ) as y group by subkegiatan, kode, kode1::int, div

        union all

        select subkegiatan kegiatan, kode, kode1::int, div, sum(jumlahbarangjasa) jumlahbarangjasa, sum(jumlahmodalgedung) jumlahmodalgedung, sum(jumlahpegawai) jumlahpegawai,  sum(jumlahmodalalat) jumlahmodalalat, sum(jumlahmodallainnya) jumlahmodallainnya,
        sum(jumlahbarangjasa) + sum(jumlahmodalgedung)+ sum(jumlahpegawai)+sum(jumlahmodalalat)+sum(jumlahmodallainnya) as total
        from(
        select kegiatan, case when $tahapnow=1 then subkegiatan else split_part(subkegiatan, '-', 2) end as subkegiatan, kode, kode1::int, div,
        case when id_jenisanggaran = 3 then subtotal else 0 end as jumlahpegawai,
        case when id_jenisanggaran = 4 then subtotal else 0 end as jumlahbarangjasa,
        case when id_jenisanggaran = 5 then subtotal else 0 end as jumlahmodalgedung,
        case when id_jenisanggaran = 6 then subtotal else 0 end as jumlahmodalalat,
        case when id_jenisanggaran = 7 then subtotal else 0 end as jumlahmodallainnya
        from(
        select km.keterangan kegiatan, km.keterangan subkegiatan,
        km.kode as kode,
        CAST(split_part(km.kode,'.',7) as int) as kode1,
        km.div, ja.id as id_jenisanggaran,ja.jenisanggaran, sum(kb.subtotal) subtotal
        from kegiatananggaran_m km
        inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
        inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
        inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
        inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
        inner join mataanggaran_m mat on ma.kodemataanggaran ilike mat.kodemataanggaran || '%' and mat.objectjenisanggaranfk is not null
        inner join jenisanggaran_m ja on ja.id = mat.objectjenisanggaranfk
        where km.kdprofile = $this->kdProfile and km.statusenabled = true
        and kms.kdprofile = $this->kdProfile and kms.statusenabled = true
        and kmss.kdprofile = $this->kdProfile and kmss.statusenabled = true
        and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
        and ma.kdprofile = $this->kdProfile and ma.statusenabled = true
        and mat.kdprofile = $this->kdProfile and mat.statusenabled = true
        and ja.kdprofile = $this->kdProfile and ja.statusenabled = true
        and km.tahun = '$tahun'
        $qtahap
        group by km.keterangan, km.keterangan, km.kode, ja.jenisanggaran, km.div, ja.id
        ) as x group by kegiatan, subkegiatan, jenisanggaran, subtotal, kode, kode1::int, div, id_jenisanggaran
        ) as y group by y.subkegiatan, y.kode, y.kode1::int, y.div
        ) as a order by CAST(split_part(kode,'.',1) as int), CAST(split_part(kode,'.',2) as int), CAST(split_part(kode,'.',3) as int),
        CAST(split_part(kode,'.',4) as int),CAST(split_part(kode,'.',5) as int),CAST(NULLIF(split_part( kode, '.', 6 ), '') AS INT),
        div, kode1"));

        $jumlah = DB::select(DB::raw("select sum(jumlahbarangjasa) jumlahbarangjasa, sum(jumlahmodalgedung) jumlahmodalgedung, sum(jumlahpegawai) jumlahpegawai,  sum(jumlahmodalalat) jumlahmodalalat, sum(jumlahmodallainnya) jumlahmodallainnya,
        sum(jumlahbarangjasa) + sum(jumlahmodalgedung)+ sum(jumlahpegawai)+sum(jumlahmodalalat)+sum(jumlahmodallainnya) as total
        from(
            select kegiatan, kode, div, sum(jumlahbarangjasa) jumlahbarangjasa, sum(jumlahmodalgedung) jumlahmodalgedung, sum(jumlahpegawai) jumlahpegawai,  sum(jumlahmodalalat) jumlahmodalalat, sum(jumlahmodallainnya) jumlahmodallainnya,
        sum(jumlahbarangjasa) + sum(jumlahmodalgedung)+ sum(jumlahpegawai)+sum(jumlahmodalalat)+sum(jumlahmodallainnya) as total
            from(
                select kegiatan, subkegiatan, kode, div,
                case when id_jenisanggaran = 3 then subtotal else 0 end as jumlahpegawai,
                case when id_jenisanggaran = 4 then subtotal else 0 end as jumlahbarangjasa,
                case when id_jenisanggaran = 5 then subtotal else 0 end as jumlahmodalgedung,
                case when id_jenisanggaran = 6 then subtotal else 0 end as jumlahmodalalat,
                case when id_jenisanggaran = 7 then subtotal else 0 end as jumlahmodallainnya
                from(
                    select kmss.keterangan kegiatan, kmss.kode, kmss.div, kms.keterangan subkegiatan,ja.id as id_jenisanggaran, ja.jenisanggaran, sum(kb.subtotal) subtotal
                    from kegiatananggaran_m km
                    inner join kegiatananggaran_m kms on km.kode ilike kms.kode || '%' and kms.div = 3
                    inner join kegiatananggaran_m kmss on km.kode ilike kmss.kode || '%' and kmss.div = 2
                    inner join keteranganbelanja_t kb on kb.objectkegiatanfk = km.id
                    inner join mataanggaran_m ma on ma.id = kb.objectmataanggaranfk
                    inner join mataanggaran_m mat on ma.kodemataanggaran ilike mat.kodemataanggaran || '%' and mat.objectjenisanggaranfk is not null
                    inner join jenisanggaran_m ja on ja.id = mat.objectjenisanggaranfk
                    where km.kdprofile = $this->kdProfile and km.statusenabled = true
                    and kms.kdprofile = $this->kdProfile and kms.statusenabled = true
                    and kmss.kdprofile = $this->kdProfile and kmss.statusenabled = true
                    and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
                    and ma.kdprofile = $this->kdProfile and ma.statusenabled = true
					and mat.kdprofile = $this->kdProfile and mat.statusenabled = true
					and ja.kdprofile = $this->kdProfile and ja.statusenabled = true
                    and km.tahun = '$tahun'
                    $qtahap
                    group by kms.keterangan, kmss.keterangan, kmss.kode, ja.jenisanggaran, kmss.div, ja.id
                ) as x group by kegiatan, subkegiatan, jenisanggaran, subtotal, kode, div, id_jenisanggaran
            ) as y group by kegiatan, kode, div
        ) as z"));


        $pageWidth = 900;
        //return $this->respond($result);
        return view('report.anggaran.cetakjenisanggaran', compact('data', 'pageWidth', 'jumlah', 'tahap', 'settingrba', 'profile'));
    }
    public function CetakRekapTotalAnggaran(Request $request)
    {
        $tahun = $request['tahun'];
        $tahap = $request['tahap'];
        $profile = Profile::where('id', $this->kdProfile)->first();
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();

        // $tanggalcetak = $this->tanggal_indonesia(date('Y-m-d',strtotime($request['tglcetak'])));

        $qtahap = '';
        if (isset($request['tahap']) && $request['tahap'] != "" && $request['tahap'] != "undefined") {
            $qtahap = ' and kb.objecttahapfk =  ' . $request['tahap'];
        }

        $itahap = DB::select(DB::raw("select id, tahap, $tahun thn from tahapanggaran_m where id = $tahap"));

        $data = DB::select(DB::raw("
        --div 1
        select * from(select mm4.kodemataanggaran div1 , '' div2, '' div3, mm4.namamataanggaran, sum(kt.subtotal) as subtotal from kegiatananggaran_m km
        inner join keteranganbelanja_t kt on kt.objectkegiatanfk = km.id
        inner join mataanggaran_m mm on mm.id = kt.objectmataanggaranfk
        inner join mataanggaran_m mm2 on mm.kodemataanggaran ilike mm2.kodemataanggaran ||'%' and mm2.div = 3
        inner join mataanggaran_m mm3 on mm2.kodemataanggaran ilike mm3.kodemataanggaran ||'%' and mm3.div = 2
        inner join mataanggaran_m mm4 on split_part(mm3.kodemataanggaran,'.',1) = mm4.kodemataanggaran and mm4.div = 1
        where km.tahun ='$tahun' and kt.objecttahapfk = $tahap and kt.statusenabled = true
        group by mm4.namamataanggaran , mm4.kodemataanggaran
        union all
        --div 2

        select split_part(mm3.kodemataanggaran,'.',1) div1 ,split_part(mm3.kodemataanggaran,'.',2)  div2, '' div3, mm3.namamataanggaran, sum(kt.subtotal) as subtotal from kegiatananggaran_m km
        inner join keteranganbelanja_t kt on kt.objectkegiatanfk = km.id
        inner join mataanggaran_m mm on mm.id = kt.objectmataanggaranfk
        inner join mataanggaran_m mm2 on mm.kodemataanggaran ilike mm2.kodemataanggaran ||'%' and mm2.div = 3
        inner join mataanggaran_m mm3 on mm2.kodemataanggaran ilike mm3.kodemataanggaran ||'%' and mm3.div = 2
        inner join mataanggaran_m mm4 on split_part(mm3.kodemataanggaran,'.',1) = mm4.kodemataanggaran and mm4.div = 1
        where km.tahun ='$tahun' and kt.objecttahapfk = $tahap and kt.statusenabled = true
        group by mm3.namamataanggaran , mm3.kodemataanggaran
        union all
        --div 3

        select split_part(mm2.kodemataanggaran,'.',1) div1 ,split_part(mm2.kodemataanggaran,'.',2)  div2, split_part( mm2.kodemataanggaran, '.', 3 ) div3,
		split_part( mm2.kodemataanggaran, '.', 3 )|| '. '|| mm2.namamataanggaran, sum(kt.subtotal) as subtotal from kegiatananggaran_m km
        inner join keteranganbelanja_t kt on kt.objectkegiatanfk = km.id
        inner join mataanggaran_m mm on mm.id = kt.objectmataanggaranfk
        inner join mataanggaran_m mm2 on mm.kodemataanggaran ilike mm2.kodemataanggaran ||'%' and mm2.div = 3
        inner join mataanggaran_m mm3 on mm2.kodemataanggaran ilike mm3.kodemataanggaran ||'%' and mm3.div = 2
        inner join mataanggaran_m mm4 on split_part(mm3.kodemataanggaran,'.',1) = mm4.kodemataanggaran and mm4.div = 1
        inner join jenisanggaran_m jm on jm.id = mm2.objectjenisanggaranfk
        where km.tahun ='$tahun' and kt.objecttahapfk = $tahap and kt.statusenabled = true
        group by mm2.namamataanggaran, mm2.kodemataanggaran

        ) as x
        order by div1 asc, split_part(div2, '.',1)asc ,split_part(div2, '.',2) asc, split_part(div3,'.',1) asc , split_part(div3,'.',2) asc , split_part(div3,'.',3) asc
        "));


        $pageWidth = 1200;
        return view('report.anggaran.cetakrekaptotalanggaran', compact('data', 'pageWidth', 'itahap', 'settingrba', 'profile'));
    }
    public function CetakAngkasJadwal(Request $request)
    {
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        $tahun = $request['tahun'];

        $qtahun = '';
        if (isset($request['tahun']) && $request['tahun'] != "" && $request['tahun'] != "undefined") {
            $qtahun = " and ka.tahun =  '" . $request['tahun'] . "'";
        }

        $qsubsubkegiatan = '';
        if (isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != "" && $request['subsubkegiatan'] != "undefined") {
            $qsubsubkegiatan = " and ka.id =  '" . $request['subsubkegiatan'] . "'";
        }

        $qtahap = '';
        if (isset($request['tahap']) && $request['tahap'] != "" && $request['tahap'] != "undefined") {
            $qtahap = " and kb.objecttahapfk =  '" . $request['tahap'] . "'";
        }

        $subk = $request['subkegiatan'];
        $subsubk = $request['subsubkegiatan'];
        $thp = $request['tahap'];

        $tahap = DB::select(DB::raw("select * from tahapanggaran_m where id = " . $thp));

        $subkegiatan = DB::select(DB::raw("select * from kegiatananggaran_m where id = " . $subk));

        $subsubkegiatan = DB::select(DB::raw("select * from kegiatananggaran_m where id = " . $subsubk));

        $data = DB::select(DB::raw("
        select * from (
            --head
            select 'iya' bold, kodemataanggaran, namamataanggaran, sum(jumlahjan) jumlahjan, sum(jumlahfeb) jumlahfeb,
            sum(jumlahmar) jumlahmar, sum(jumlahapr) jumlahapr, sum(jumlahmei) jumlahmei, sum(jumlahjun) jumlahjun,
            sum(jumlahjul) jumlahjul, sum(jumlahagt) jumlahagt, sum(jumlahsep) jumlahsep, sum(jumlahokt) jumlahokt,
            sum(jumlahnov) jumlahnov, sum(jumlahdes) jumlahdes,
            sum(jumlahjan) + sum(jumlahfeb) + sum(jumlahmar) + sum(jumlahapr) + sum(jumlahmei) + sum(jumlahjun) +
            sum(jumlahjul) + sum(jumlahagt) + sum(jumlahsep) + sum(jumlahokt) + sum(jumlahnov) + sum(jumlahdes) subtotal,
              '' asalproduk from (select
            ma.kodemataanggaran, ma.namamataanggaran,
            case when akb.bulanint = 1 then sum(akb.nilai::float8) else 0 end as jumlahjan,
            case when akb.bulanint = 2 then sum(akb.nilai::float8) else 0 end as jumlahfeb,
            case when akb.bulanint = 3 then sum(akb.nilai::float8) else 0 end as jumlahmar,
            case when akb.bulanint = 4 then sum(akb.nilai::float8) else 0 end as jumlahapr,
            case when akb.bulanint = 5 then sum(akb.nilai::float8) else 0 end as jumlahmei,
            case when akb.bulanint = 6 then sum(akb.nilai::float8) else 0 end as jumlahjun,
            case when akb.bulanint = 7 then sum(akb.nilai::float8) else 0 end as jumlahjul,
            case when akb.bulanint = 8 then sum(akb.nilai::float8) else 0 end as jumlahagt,
            case when akb.bulanint = 9 then sum(akb.nilai::float8) else 0 end as jumlahsep,
            case when akb.bulanint = 10 then sum(akb.nilai::float8) else 0 end as jumlahokt,
            case when akb.bulanint = 11 then sum(akb.nilai::float8) else 0 end as jumlahnov,
            case when akb.bulanint = 12 then sum(akb.nilai::float8) else 0 end as jumlahdes
            from mataanggaran_m ma
            inner join keteranganbelanja_t kb on ma.id=kb.objectmataanggaranfk
            INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
            inner join kegiatananggaran_m as dp0 on ka.kode ilike dp0.kode || '%' and dp0.div = 3
            left join alokasiketeranganbelanja_t akb on akb.keteranganbelanjafk = kb.norec
            and akb.kdprofile = $this->kdProfile and akb.statusenabled = true
            where ma.kdprofile = $this->kdProfile and ma.statusenabled = true
            and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
            and ka.kdprofile = $this->kdProfile and ka.statusenabled = true
            and dp0.kdprofile = $this->kdProfile and dp0.statusenabled = true
            and ka.tahun = '$tahun'
            $qsubsubkegiatan
            $qtahap
            group by
            ma.kodemataanggaran, ma.namamataanggaran, akb.bulanint)as x
            group by x.kodemataanggaran, x.namamataanggaran

            union all
            --detail alokasi
            select '' bold, kodemataanggaran , namamataanggaran, sum(jumlahjan) jumlahjan, sum(jumlahfeb) jumlahfeb,
            sum(jumlahmar) jumlahmar, sum(jumlahapr) jumlahapr, sum(jumlahmei) jumlahmei, sum(jumlahjun) jumlahjun,
            sum(jumlahjul) jumlahjul, sum(jumlahagt) jumlahagt, sum(jumlahsep) jumlahsep, sum(jumlahokt) jumlahokt,
            sum(jumlahnov) jumlahnov, sum(jumlahdes) jumlahdes,
            sum(jumlahjan) + sum(jumlahfeb) + sum(jumlahmar) + sum(jumlahapr) + sum(jumlahmei) + sum(jumlahjun) +
            sum(jumlahjul) + sum(jumlahagt) + sum(jumlahsep) + sum(jumlahokt) + sum(jumlahnov) + sum(jumlahdes) subtotal,
            asalproduk from (select ap.asalproduk,
            ma.kodemataanggaran ||'.'|| DENSE_RANK ( ) OVER ( order by kb.keteranganbelanja ) as kodemataanggaran, kb.keteranganbelanja as namamataanggaran,
            case when akb.bulanint = 1 then sum(akb.nilai::float8) else 0 end as jumlahjan,
            case when akb.bulanint = 2 then sum(akb.nilai::float8) else 0 end as jumlahfeb,
            case when akb.bulanint = 3 then sum(akb.nilai::float8) else 0 end as jumlahmar,
            case when akb.bulanint = 4 then sum(akb.nilai::float8) else 0 end as jumlahapr,
            case when akb.bulanint = 5 then sum(akb.nilai::float8) else 0 end as jumlahmei,
            case when akb.bulanint = 6 then sum(akb.nilai::float8) else 0 end as jumlahjun,
            case when akb.bulanint = 7 then sum(akb.nilai::float8) else 0 end as jumlahjul,
            case when akb.bulanint = 8 then sum(akb.nilai::float8) else 0 end as jumlahagt,
            case when akb.bulanint = 9 then sum(akb.nilai::float8) else 0 end as jumlahsep,
            case when akb.bulanint = 10 then sum(akb.nilai::float8) else 0 end as jumlahokt,
            case when akb.bulanint = 11 then sum(akb.nilai::float8) else 0 end as jumlahnov,
            case when akb.bulanint = 12 then sum(akb.nilai::float8) else 0 end as jumlahdes
            from mataanggaran_m ma
            inner join keteranganbelanja_t kb on ma.id=kb.objectmataanggaranfk
            INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
            inner join kegiatananggaran_m as dp0 on ka.kode ilike dp0.kode || '%' and dp0.div = 3
            left join alokasiketeranganbelanja_t akb on akb.keteranganbelanjafk = kb.norec
            and akb.kdprofile = $this->kdProfile and akb.statusenabled = true
            inner join asalproduk_m ap on ap.id = kb.objectasalprodukfk
            where ma.kdprofile = $this->kdProfile and ma.statusenabled = true
            and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
            and ka.kdprofile = $this->kdProfile and ka.statusenabled = true
            and dp0.kdprofile = $this->kdProfile and dp0.statusenabled = true
            and ka.tahun = '$tahun'
            $qsubsubkegiatan
            $qtahap
            group by
            ma.kodemataanggaran, kb.keteranganbelanja, akb.bulanint, ap.asalproduk)as x
            group by x.kodemataanggaran, x.namamataanggaran, x.asalproduk) as y
            order by split_part(kodemataanggaran,'.',1) asc, split_part(kodemataanggaran,'.',2) asc,
            split_part(kodemataanggaran,'.',3)::int asc, split_part(kodemataanggaran,'.',4)::int asc,
            split_part(kodemataanggaran,'.',5)::int asc, CAST(NULLIF(split_part(kodemataanggaran, '.', 6 ), '') AS INT)desc
        "));
        // order by split_part(x.kodemataanggaran,'.',4) asc , split_part(x.kodemataanggaran,'.',5) ::int4 asc, kodefix desc

        $pageWidth = 1200;
        //return $this->respond($result);
        return view('report.anggaran.cetakangkasjadwal', compact('data', 'pageWidth', 'tahun', 'tahap', 'subkegiatan', 'subsubkegiatan', 'settingrba'));
    }

    public function CetakKas(Request $request)
    {
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        $profile = Profile::where('id', $this->kdProfile)->first();
        $tahun = $request['tahun'];

        $qtahap = '';
        if (isset($request['tahap']) && $request['tahap'] != "" && $request['tahap'] != "undefined") {
            $qtahap = " and kb.objecttahapfk =  '" . $request['tahap'] . "'";
        }

        $tahap = DB::select(DB::raw("select * from tahapanggaran_m where id = " . $request['tahap']));

        $data = DB::select(DB::raw("
        select * from (
            select 'tidak' bold,
                    'iya' tkode,
                    kode,
                    keterangan,
                    SUM ( jumlahanggaran ) jumlahanggaran,
                    SUM ( jumlahjan ) jumlahjan,
                    SUM ( jumlahfeb ) jumlahfeb,
                    SUM ( jumlahmar ) jumlahmar,
                    SUM ( jumlahapr ) jumlahapr,
                    SUM ( jumlahmei ) jumlahmei,
                    SUM ( jumlahjun ) jumlahjun,
                    SUM ( jumlahjul ) jumlahjul,
                    SUM ( jumlahagt ) jumlahagt,
                    SUM ( jumlahsep ) jumlahsep,
                    SUM ( jumlahokt ) jumlahokt,
                    SUM ( jumlahnov ) jumlahnov,
                    SUM ( jumlahdes ) jumlahdes,
                    SUM ( jumlahjan ) + SUM ( jumlahfeb ) + SUM ( jumlahmar ) + SUM ( jumlahapr ) + SUM ( jumlahmei ) + SUM ( jumlahjun ) + SUM ( jumlahjul ) + SUM ( jumlahagt ) + SUM ( jumlahsep ) + SUM ( jumlahokt ) + SUM ( jumlahnov ) + SUM ( jumlahdes ) subtotal
                     from (
            select
                kode,keterangan,jumlahanggaran,idap,norec,
                SUM ( jumlahjan ) jumlahjan,SUM ( jumlahfeb ) jumlahfeb,
                SUM ( jumlahmar ) jumlahmar,SUM ( jumlahapr ) jumlahapr,SUM ( jumlahmei ) jumlahmei,
                SUM ( jumlahjun ) jumlahjun,SUM ( jumlahjul ) jumlahjul,SUM ( jumlahagt ) jumlahagt,SUM ( jumlahsep ) jumlahsep,
                SUM ( jumlahokt ) jumlahokt,SUM ( jumlahnov ) jumlahnov,SUM ( jumlahdes ) jumlahdes,
                SUM ( jumlahjan ) + SUM ( jumlahfeb ) + SUM ( jumlahmar ) + SUM ( jumlahapr ) + SUM ( jumlahmei ) + SUM ( jumlahjun ) + SUM ( jumlahjul ) + SUM ( jumlahagt ) + SUM ( jumlahsep ) + SUM ( jumlahokt ) + SUM ( jumlahnov ) + SUM ( jumlahdes ) subtotal
                from(SELECT
            ka.kode,
            ka.keterangan,
            kb.subtotal jumlahanggaran,
            ap.ID idap,
            kb.norec,
            case WHEN akb.bulanint = 1 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjan,
            case WHEN akb.bulanint = 2 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahfeb,
            case WHEN akb.bulanint = 3 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahmar,
            case WHEN akb.bulanint = 4 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahapr,
            case WHEN akb.bulanint = 5 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahmei,
            case WHEN akb.bulanint = 6 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjun,
            case WHEN akb.bulanint = 7 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjul,
            case WHEN akb.bulanint = 8 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahagt,
            case WHEN akb.bulanint = 9 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahsep,
            case WHEN akb.bulanint = 10 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahokt,
            case WHEN akb.bulanint = 11 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahnov,
            case WHEN akb.bulanint = 12 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahdes
            FROM
                mataanggaran_m ma
                INNER JOIN keteranganbelanja_t kb ON ma.ID = kb.objectmataanggaranfk
                INNER JOIN kegiatananggaran_m ka ON ka.ID = kb.objectkegiatanfk
                INNER JOIN kegiatananggaran_m AS dp0 ON ka.kode ILIKE dp0.kode || '%'  AND dp0.div = 3
                INNER JOIN asalproduk_m ap ON ap.ID = kb.objectasalprodukfk
                LEFT JOIN alokasiketeranganbelanja_t akb ON akb.keteranganbelanjafk = kb.norec AND akb.kdprofile = $this->kdProfile AND akb.statusenabled = TRUE
            WHERE
                ma.kdprofile = $this->kdProfile AND ma.statusenabled = TRUE AND kb.kdprofile = $this->kdProfile AND kb.statusenabled = TRUE AND ka.kdprofile = $this->kdProfile AND ka.statusenabled = TRUE
                AND dp0.kdprofile = $this->kdProfile AND dp0.statusenabled = TRUE AND ka.tahun = '$tahun' $qtahap
            GROUP by ka.kode,ka.keterangan,akb.bulanint,kb.subtotal,ap.ID,kb.norec )as x
                GROUP by kode,keterangan,jumlahanggaran,idap,norec
            )as y
            group by kode,keterangan

            union all

            select 'iya' bold,
                    'iya' tkode,
                    kode,
                    keterangan,
                    SUM ( jumlahanggaran ) jumlahanggaran,
                    SUM ( jumlahjan ) jumlahjan,
                    SUM ( jumlahfeb ) jumlahfeb,
                    SUM ( jumlahmar ) jumlahmar,
                    SUM ( jumlahapr ) jumlahapr,
                    SUM ( jumlahmei ) jumlahmei,
                    SUM ( jumlahjun ) jumlahjun,
                    SUM ( jumlahjul ) jumlahjul,
                    SUM ( jumlahagt ) jumlahagt,
                    SUM ( jumlahsep ) jumlahsep,
                    SUM ( jumlahokt ) jumlahokt,
                    SUM ( jumlahnov ) jumlahnov,
                    SUM ( jumlahdes ) jumlahdes,
                    SUM ( jumlahjan ) + SUM ( jumlahfeb ) + SUM ( jumlahmar ) + SUM ( jumlahapr ) + SUM ( jumlahmei ) + SUM ( jumlahjun ) + SUM ( jumlahjul ) + SUM ( jumlahagt ) + SUM ( jumlahsep ) + SUM ( jumlahokt ) + SUM ( jumlahnov ) + SUM ( jumlahdes ) subtotal
                     from (
            select
                kode,keterangan,jumlahanggaran,idap,norec,
                SUM ( jumlahjan ) jumlahjan,SUM ( jumlahfeb ) jumlahfeb,
                SUM ( jumlahmar ) jumlahmar,SUM ( jumlahapr ) jumlahapr,SUM ( jumlahmei ) jumlahmei,
                SUM ( jumlahjun ) jumlahjun,SUM ( jumlahjul ) jumlahjul,SUM ( jumlahagt ) jumlahagt,SUM ( jumlahsep ) jumlahsep,
                SUM ( jumlahokt ) jumlahokt,SUM ( jumlahnov ) jumlahnov,SUM ( jumlahdes ) jumlahdes,
                SUM ( jumlahjan ) + SUM ( jumlahfeb ) + SUM ( jumlahmar ) + SUM ( jumlahapr ) + SUM ( jumlahmei ) + SUM ( jumlahjun ) + SUM ( jumlahjul ) + SUM ( jumlahagt ) + SUM ( jumlahsep ) + SUM ( jumlahokt ) + SUM ( jumlahnov ) + SUM ( jumlahdes ) subtotal
                from(SELECT
            dp0.kode,
            dp0.keterangan,
            kb.subtotal jumlahanggaran,
            ap.ID idap,
            kb.norec,
            case WHEN akb.bulanint = 1 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjan,
            case WHEN akb.bulanint = 2 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahfeb,
            case WHEN akb.bulanint = 3 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahmar,
            case WHEN akb.bulanint = 4 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahapr,
            case WHEN akb.bulanint = 5 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahmei,
            case WHEN akb.bulanint = 6 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjun,
            case WHEN akb.bulanint = 7 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjul,
            case WHEN akb.bulanint = 8 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahagt,
            case WHEN akb.bulanint = 9 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahsep,
            case WHEN akb.bulanint = 10 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahokt,
            case WHEN akb.bulanint = 11 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahnov,
            case WHEN akb.bulanint = 12 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahdes
            FROM
                mataanggaran_m ma
                INNER JOIN keteranganbelanja_t kb ON ma.ID = kb.objectmataanggaranfk
                INNER JOIN kegiatananggaran_m ka ON ka.ID = kb.objectkegiatanfk
                INNER JOIN kegiatananggaran_m AS dp0 ON ka.kode ILIKE dp0.kode || '%'  AND dp0.div = 3
                INNER JOIN asalproduk_m ap ON ap.ID = kb.objectasalprodukfk
                LEFT JOIN alokasiketeranganbelanja_t akb ON akb.keteranganbelanjafk = kb.norec AND akb.kdprofile = $this->kdProfile AND akb.statusenabled = TRUE
            WHERE
                ma.kdprofile = $this->kdProfile AND ma.statusenabled = TRUE AND kb.kdprofile = $this->kdProfile AND kb.statusenabled = TRUE AND ka.kdprofile = $this->kdProfile AND ka.statusenabled = TRUE
                AND dp0.kdprofile = $this->kdProfile AND dp0.statusenabled = TRUE AND ka.tahun = '$tahun' $qtahap
            GROUP by dp0.kode,dp0.keterangan,akb.bulanint,kb.subtotal,ap.ID,kb.norec )as x
                GROUP by kode,keterangan,jumlahanggaran,idap,norec
            )as y
            group by kode,keterangan
                       union all
                       select 'iya' bold,
                    'iya' tkode,
                    kode,
                    keterangan,
                    SUM ( jumlahanggaran ) jumlahanggaran,
                    SUM ( jumlahjan ) jumlahjan,
                    SUM ( jumlahfeb ) jumlahfeb,
                    SUM ( jumlahmar ) jumlahmar,
                    SUM ( jumlahapr ) jumlahapr,
                    SUM ( jumlahmei ) jumlahmei,
                    SUM ( jumlahjun ) jumlahjun,
                    SUM ( jumlahjul ) jumlahjul,
                    SUM ( jumlahagt ) jumlahagt,
                    SUM ( jumlahsep ) jumlahsep,
                    SUM ( jumlahokt ) jumlahokt,
                    SUM ( jumlahnov ) jumlahnov,
                    SUM ( jumlahdes ) jumlahdes,
                    SUM ( jumlahjan ) + SUM ( jumlahfeb ) + SUM ( jumlahmar ) + SUM ( jumlahapr ) + SUM ( jumlahmei ) + SUM ( jumlahjun ) + SUM ( jumlahjul ) + SUM ( jumlahagt ) + SUM ( jumlahsep ) + SUM ( jumlahokt ) + SUM ( jumlahnov ) + SUM ( jumlahdes ) subtotal
                     from (
            select
                kode,keterangan,jumlahanggaran,idap,norec,
                SUM ( jumlahjan ) jumlahjan,SUM ( jumlahfeb ) jumlahfeb,
                SUM ( jumlahmar ) jumlahmar,SUM ( jumlahapr ) jumlahapr,SUM ( jumlahmei ) jumlahmei,
                SUM ( jumlahjun ) jumlahjun,SUM ( jumlahjul ) jumlahjul,SUM ( jumlahagt ) jumlahagt,SUM ( jumlahsep ) jumlahsep,
                SUM ( jumlahokt ) jumlahokt,SUM ( jumlahnov ) jumlahnov,SUM ( jumlahdes ) jumlahdes,
                SUM ( jumlahjan ) + SUM ( jumlahfeb ) + SUM ( jumlahmar ) + SUM ( jumlahapr ) + SUM ( jumlahmei ) + SUM ( jumlahjun ) + SUM ( jumlahjul ) + SUM ( jumlahagt ) + SUM ( jumlahsep ) + SUM ( jumlahokt ) + SUM ( jumlahnov ) + SUM ( jumlahdes ) subtotal
                from(SELECT
            dp0.kode,
            dp0.keterangan,
            kb.subtotal jumlahanggaran,
            ap.ID idap,
            kb.norec,
            case WHEN akb.bulanint = 1 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjan,
            case WHEN akb.bulanint = 2 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahfeb,
            case WHEN akb.bulanint = 3 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahmar,
            case WHEN akb.bulanint = 4 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahapr,
            case WHEN akb.bulanint = 5 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahmei,
            case WHEN akb.bulanint = 6 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjun,
            case WHEN akb.bulanint = 7 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahjul,
            case WHEN akb.bulanint = 8 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahagt,
            case WHEN akb.bulanint = 9 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahsep,
            case WHEN akb.bulanint = 10 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahokt,
            case WHEN akb.bulanint = 11 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahnov,
            case WHEN akb.bulanint = 12 then SUM ( akb.nilai :: FLOAT8 ) ELSE 0 END AS jumlahdes
            FROM
                mataanggaran_m ma
                INNER JOIN keteranganbelanja_t kb ON ma.ID = kb.objectmataanggaranfk
                INNER JOIN kegiatananggaran_m ka ON ka.ID = kb.objectkegiatanfk
                INNER JOIN kegiatananggaran_m AS dp0 ON ka.kode ILIKE dp0.kode || '%'  AND dp0.div = 2
                INNER JOIN asalproduk_m ap ON ap.ID = kb.objectasalprodukfk
                LEFT JOIN alokasiketeranganbelanja_t akb ON akb.keteranganbelanjafk = kb.norec AND akb.kdprofile = $this->kdProfile AND akb.statusenabled = TRUE
            WHERE
                ma.kdprofile = $this->kdProfile AND ma.statusenabled = TRUE AND kb.kdprofile = $this->kdProfile AND kb.statusenabled = TRUE AND ka.kdprofile = $this->kdProfile AND ka.statusenabled = TRUE
                AND dp0.kdprofile = $this->kdProfile AND dp0.statusenabled = TRUE AND ka.tahun = '$tahun' $qtahap
            GROUP by dp0.kode,dp0.keterangan,akb.bulanint,kb.subtotal,ap.ID,kb.norec )as x
                GROUP by kode,keterangan,jumlahanggaran,idap,norec
            )as y
            group by kode,keterangan
            )as z
              order by split_part(kode,'.',1) asc, split_part(kode,'.',2) asc,split_part(kode,'.',3)::int asc, split_part(kode,'.',4)::int asc,split_part(kode,'.',5)::int asc,
              CASE WHEN split_part(kode, '.', 6 ) = '' THEN 0 ELSE split_part(kode, '.', 6 )::INT end asc,
              CASE WHEN split_part(kode, '.', 7 ) = '' THEN 0 ELSE split_part(kode, '.', 7 )::INT end asc
        "));

        $pageWidth = 1200;
        //return $this->respond($result);

        return view('report.anggaran.cetakkas', compact('data', 'pageWidth', 'tahun', 'tahap', 'settingrba', 'profile'));
    }
    public function CetakKonsolidasiPermen(Request $request)
    {
        $tahun = $request['tahun'];

        $qtahunka = '';
        if (isset($request['tahun']) && $request['tahun'] != "" && $request['tahun'] != "undefined") {
            $qtahunka = " and ka.tahun =  '" . $request['tahun'] . "'";
        }

        $qsubkegiatan = '';
        if (isset($request['subkegiatan']) && $request['subkegiatan'] != "" && $request['subkegiatan'] != "undefined") {
            $qsubkegiatan = " and dp0.id =  '" . $request['subkegiatan'] . "'";
        }

        $qtahap = '';
        if (isset($request['tahap']) && $request['tahap'] != "" && $request['tahap'] != "undefined") {
            $qtahap = " and kb.objecttahapfk =  '" . $request['tahap'] . "'";
        }

        $subk = $request['subkegiatan'];
        $thp = $request['tahap'];

        $tahap = DB::select(DB::raw("select * from tahapanggaran_m where id = " . $thp));

        $subkegiatan = DB::select(DB::raw("select * from kegiatananggaran_m where id = " . $subk));
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        $profile = Profile::where('id', $this->kdProfile)->first();
        $data = DB::select(DB::raw("select kodemataanggaran, namamataanggaran, sum(jumlahanggaran) jumlahanggaran, sum(jumlahjan) jumlahjan, sum(jumlahfeb) jumlahfeb,
        sum(jumlahmar) jumlahmar, sum(jumlahapr) jumlahapr, sum(jumlahmei) jumlahmei, sum(jumlahjun) jumlahjun,
        sum(jumlahjul) jumlahjul, sum(jumlahagt) jumlahagt, sum(jumlahsep) jumlahsep, sum(jumlahokt) jumlahokt,
        sum(jumlahnov) jumlahnov, sum(jumlahdes) jumlahdes
        from(
            select kodemataanggaran, namamataanggaran, jumlahanggaran, sum(jumlahjan) jumlahjan, sum(jumlahfeb) jumlahfeb,
            sum(jumlahmar) jumlahmar, sum(jumlahapr) jumlahapr, sum(jumlahmei) jumlahmei, sum(jumlahjun) jumlahjun,
            sum(jumlahjul) jumlahjul, sum(jumlahagt) jumlahagt, sum(jumlahsep) jumlahsep, sum(jumlahokt) jumlahokt,
            sum(jumlahnov) jumlahnov, sum(jumlahdes) jumlahdes
            from(
                select map3.kode || '.' || map2.kode || '.' || map1.kode || '.' || map.kode kodemataanggaran,
                map.mataanggaranpermen namamataanggaran, sum(kb.subtotal) jumlahanggaran,
                case when akb.bulanint = 1 then sum(akb.nilai::float8) else 0 end as jumlahjan,
                case when akb.bulanint = 2 then sum(akb.nilai::float8) else 0 end as jumlahfeb,
                case when akb.bulanint = 3 then sum(akb.nilai::float8) else 0 end as jumlahmar,
                case when akb.bulanint = 4 then sum(akb.nilai::float8) else 0 end as jumlahapr,
                case when akb.bulanint = 5 then sum(akb.nilai::float8) else 0 end as jumlahmei,
                case when akb.bulanint = 6 then sum(akb.nilai::float8) else 0 end as jumlahjun,
                case when akb.bulanint = 7 then sum(akb.nilai::float8) else 0 end as jumlahjul,
                case when akb.bulanint = 8 then sum(akb.nilai::float8) else 0 end as jumlahagt,
                case when akb.bulanint = 9 then sum(akb.nilai::float8) else 0 end as jumlahsep,
                case when akb.bulanint = 10 then sum(akb.nilai::float8) else 0 end as jumlahokt,
                case when akb.bulanint = 11 then sum(akb.nilai::float8) else 0 end as jumlahnov,
                case when akb.bulanint = 12 then sum(akb.nilai::float8) else 0 end as jumlahdes
                from mataanggaran_m ma
                inner join keteranganbelanja_t kb on ma.id=kb.objectmataanggaranfk
                INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
                inner join kegiatananggaran_m as dp0 on ka.kode ilike dp0.kode || '%' and dp0.div = 3
                inner join mataanggaranpermen_m map on map.id = ma.mataanggaranpermenfk
                inner join mataanggaranpermen_m map1 on map1.id = map.mataanggaranpermenfk and map1.div = 4
                inner join mataanggaranpermen_m map2 on map2.id = map1.mataanggaranpermenfk and map2.div = 3
                inner join mataanggaranpermen_m map3 on map3.id = map2.mataanggaranpermenfk and map3.div = 2
                left join alokasiketeranganbelanja_t akb on akb.keteranganbelanjafk = kb.norec
                and akb.kdprofile = $this->kdProfile and akb.statusenabled = true
                where ma.kdprofile = $this->kdProfile and ma.statusenabled = true
                and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
                and ka.kdprofile = $this->kdProfile and ka.statusenabled = true
                and map.kdprofile = $this->kdProfile and map.statusenabled = true
                and dp0.kdprofile = $this->kdProfile and dp0.statusenabled = true
                $qtahunka
                $qsubkegiatan
                $qtahap
                group by map3.kode || '.' || map2.kode || '.' || map1.kode || '.' || map.kode, map.mataanggaranpermen,
                akb.bulanint
            ) as x group by kodemataanggaran, namamataanggaran, jumlahanggaran
        ) as y group by kodemataanggaran, namamataanggaran
        "));

        $pageWidth = 1200;
        //return $this->respond($result);
        return view('report.anggaran.cetakkonsolidasipermen', compact('data', 'pageWidth', 'tahun', 'subkegiatan', 'tahap', 'settingrba', 'profile'));
    }
    public function CetakSPJMonevRBA(Request $request)
    {
        $tahun = $request['tahun'];

        $qtahun = '';
        $qtahunka = '';
        if (isset($request['tahun']) && $request['tahun'] != "" && $request['tahun'] != "undefined") {
            $qtahun = " and extract(year from sr.tglrealisasi) =  '" . $request['tahun'] . "'";
            $qtahunka = " and ka.tahun =  '" . $request['tahun'] . "'";
        }

        $qsubsubkegiatan = '';
        $qsubsubkegiatansr = '';
        if (isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != "" && $request['subsubkegiatan'] != "undefined") {
            $qsubsubkegiatan = " and ka.id =  '" . $request['subsubkegiatan'] . "'";
            $qsubsubkegiatansr = " and sr.objectkegiatanfk =  '" . $request['subsubkegiatan'] . "'";
        }

        $subk = $request['subkegiatan'];
        $subsubk = $request['subsubkegiatan'];

        $subsubkegiatan = DB::select(DB::raw("select regexp_replace(keterangan, '\d|-', '', 'g') as keterangan2,*  from kegiatananggaran_m where id = " . $subsubk));
        $subkegiatan = DB::select(DB::raw("select * from kegiatananggaran_m where id = " . $subk));

        $data = DB::select(DB::raw("
                SELECT
                    split_part(x.kodemataanggaran, '.', 1) as subkodepertama
                    , split_part(x.kodemataanggaran, '.', 2) as subkodekedua
                    , case when split_part(x.kodemataanggaran, '.', 3) = '' then '0' else split_part(x.kodemataanggaran, '.', 3)::int4 end as subkodeketiga
                    , case when split_part(x.kodemataanggaran, '.', 4) = '' then '0' else split_part(x.kodemataanggaran, '.', 4)::int4 end as subkodekeempat
                    , case when split_part(x.kodemataanggaran, '.', 5) = '' then '0' else split_part(x.kodemataanggaran, '.', 5)::int4 end as subkodekelima,
                    x.kodemataanggaran,
                    x.namamataanggaran,
                    SUM ( jumlahanggaran ) AS jumlahanggaran,
                    SUM ( panjar ) AS jumlahpanjar,
                    SUM ( spj ) AS jumlahspj ,
                    SUM ( panjar ) + SUM ( spj )  as subtotal,
                    SUM ( jumlahanggaran )- (SUM ( panjar ) + SUM ( spj )) as sisaanggaran
                FROM
                    (
                    SELECT
                        ka.ID AS idKeg,
                        ma.ID AS idAng,
                        ka.kode,
                        ka.keterangan,
                        ma.kodemataanggaran,
                        ma.namamataanggaran,
                        COALESCE ( SUM ( kb.subtotal ), 0 ) AS jumlahanggaran,
                        0 AS panjar,
                        0 AS spj
                    FROM
                        keteranganbelanja_t kb
                        LEFT JOIN kegiatananggaran_m ka ON ka.ID = kb.objectkegiatanfk
                        LEFT JOIN mataanggaran_m ma ON ma.ID = kb.objectmataanggaranfk
                    WHERE
                        ka.ID = $subsubk
                        AND ka.tahun = '$tahun'
                        AND kb.statusenabled = TRUE
                        AND kb.kdprofile = $this->kdProfile
                        AND ka.statusenabled = TRUE
                        AND ka.kdprofile = $this->kdProfile
                        AND ma.statusenabled = TRUE
                        AND ma.kdprofile = $this->kdProfile
                    GROUP BY
                        ka.ID,
                        ma.ID,
                        ka.kode,
                        ka.keterangan,
                        ma.kodemataanggaran,
                        ma.namamataanggaran
                        UNION
                    SELECT
                        ka.ID AS idKeg,
                        ma.ID AS idAng,
                        ka.kode,
                        ka.keterangan,
                        ma.kodemataanggaran,
                        ma.namamataanggaran,
                        0 AS jumlahanggaran,
                        0 AS panjar,
                        COALESCE ( SUM ( sr.totalbelanja ), 0 ) AS spj
                    FROM
                        strukrealisasi_t sr
                        LEFT JOIN kegiatananggaran_m ka ON ka.ID = sr.objectkegiatanfk
                        LEFT JOIN mataanggaran_m ma ON ma.ID = sr.objectmataanggaranfk::int2
                    WHERE
                        sr.objectkegiatanfk IN (
                        SELECT ID
                        FROM
                            kegiatananggaran_m
                        WHERE
                            kode = ( SELECT kode FROM kegiatananggaran_m WHERE ID = $subsubk AND statusenabled = TRUE AND kdprofile = $this->kdProfile LIMIT 1 )
                            AND tahun = '$tahun'
                            AND statusenabled = TRUE
                            AND kdprofile = $this->kdProfile
                        )
                        AND ka.tahun = '$tahun'
                        AND sr.statusenabled = TRUE
                        AND sr.kdprofile = $this->kdProfile
                        AND ka.statusenabled = TRUE
                        AND ka.kdprofile = $this->kdProfile
                        AND ma.statusenabled = TRUE
                        AND ma.kdprofile = $this->kdProfile
                    GROUP BY
                        ka.ID,
                        ma.ID,
                        ka.kode,
                        ka.keterangan,
                        ma.kodemataanggaran,
                        ma.namamataanggaran UNION
                    SELECT
                        ka.ID AS idKeg,
                        ma.ID AS idAng,
                        ka.kode,
                        ka.keterangan,
                        ma.kodemataanggaran,
                        ma.namamataanggaran,
                        0 AS jumlahanggaran,
                        COALESCE ( SUM ( P.jumlah ), 0 ) - COALESCE ( SUM ( pp.jumlahpengembalian ), 0 ) - COALESCE ( SUM ( sp.jumlahspj ), 0 ) AS panjar,
                        0 AS spj
                    FROM
                        panjar_t
                        P LEFT JOIN kegiatananggaran_m ka ON ka.ID = P.objectsubsubkegiatanfk
                        LEFT JOIN mataanggaran_m ma ON ma.ID = P.objectmataanggaranfk
                        LEFT JOIN pengembalianpanjar_t pp ON pp.objectpanjarfk = P.norec
                        AND pp.statusenabled = TRUE
                        AND pp.kdprofile = $this->kdProfile
                        LEFT JOIN spjtopanjar_t sp ON sp.objectpanjarfk = P.norec
                        AND sp.statusenabled = TRUE
                        AND sp.kdprofile = $this->kdProfile
                    WHERE
                        P.objectsubsubkegiatanfk IN (
                        SELECT ID
                        FROM
                            kegiatananggaran_m
                        WHERE
                            kode = ( SELECT kode FROM kegiatananggaran_m WHERE ID = $subsubk AND statusenabled = TRUE AND kdprofile = $this->kdProfile LIMIT 1 )
                            AND tahun = '$tahun'
                            AND statusenabled = TRUE
                            AND kdprofile = $this->kdProfile
                        )
                        AND ka.tahun = '$tahun'
                        AND P.statusenabled = TRUE
                        AND P.kdprofile = $this->kdProfile
                        AND ka.statusenabled = TRUE
                        AND ka.kdprofile = $this->kdProfile
                        AND ma.statusenabled = TRUE
                        AND ma.kdprofile = $this->kdProfile
                    GROUP BY
                        ka.ID,
                        ma.ID,
                        ka.kode,
                        ka.keterangan,
                        ma.kodemataanggaran,
                        ma.namamataanggaran
                    ) AS x
                GROUP BY
                    kodemataanggaran,
                    namamataanggaran
                    order by subkodepertama asc, subkodekedua asc, subkodeketiga asc, subkodekeempat asc, subkodekelima asc

             "));

        $pageWidth = 950;
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        $profile = Profile::where('id', $this->kdProfile)->first();
        return view('report.anggaran.cetakspjmonev', compact('data', 'pageWidth', 'tahun', 'subkegiatan', 'subsubkegiatan', 'settingrba', 'profile'));
    }

    public function CetakSPJMonev(Request $request)
    {
        $tahun = $request['tahun'];

        $qtahun = '';
        $qtahunka = '';
        if (isset($request['tahun']) && $request['tahun'] != "" && $request['tahun'] != "undefined") {
            $qtahun = " and extract(year from sr.tglrealisasi) =  '" . $request['tahun'] . "'";
            $qtahunka = " and ka.tahun =  '" . $request['tahun'] . "'";
        }

        $qsubsubkegiatan = '';
        $qsubsubkegiatansr = '';
        if (isset($request['subsubkegiatan']) && $request['subsubkegiatan'] != "" && $request['subsubkegiatan'] != "undefined") {
            $qsubsubkegiatan = " and ka.id =  '" . $request['subsubkegiatan'] . "'";
            $qsubsubkegiatansr = " and sr.objectkegiatanfk =  '" . $request['subsubkegiatan'] . "'";
        }

        $subk = $request['subkegiatan'];
        $subsubk = $request['subsubkegiatan'];

        $subsubkegiatan = DB::select(DB::raw("select regexp_replace(keterangan, '\d|-', '', 'g') as keterangan2,*  from kegiatananggaran_m where id = " . $subsubk));
        $subkegiatan = DB::select(DB::raw("select * from kegiatananggaran_m where id = " . $subk));

        $data = DB::select(DB::raw("select map3.kode || '.' || map2.kode || '.' || map1.kode || '.' || map.kode kodemataanggaran,
        map.mataanggaranpermen namamataanggaran, sum(kb.subtotal) jumlahanggaran,
                x.totalbelanja jumlahspj, y.jumlah jumlahpanjar,
                x.totalbelanja + y.jumlah as subtotal, round(cast(((x.totalbelanja + y.jumlah)/sum(kb.subtotal))*100 as numeric), 2) as persentase,
                sum(kb.subtotal) - (coalesce(x.totalbelanja,0) + coalesce(y.jumlah,0)) sisaanggaran
                from mataanggaran_m ma
                inner join keteranganbelanja_t kb on ma.id=kb.objectmataanggaranfk
                INNER JOIN kegiatananggaran_m ka on ka.id=kb.objectkegiatanfk
                        left join (
                            select ma.kodemataanggaran, ma.namamataanggaran, sum(sr.totalbelanja) totalbelanja
                            from mataanggaran_m ma
                            inner join strukrealisasi_t sr on ma.id=sr.objectmataanggaranfk::int2
                            where sr.kdprofile = $this->kdProfile and sr.statusenabled = true
                            $qtahun
                            $qsubsubkegiatansr
                            group by ma.kodemataanggaran, ma.namamataanggaran
                        ) as x on x.kodemataanggaran = ma.kodemataanggaran
                        left join (
                            select kodemataanggaran, namamataanggaran, sum(jumlah) jumlah
                            from(
                                select ma.kodemataanggaran, ma.namamataanggaran, pj.jumlah panjar, coalesce(sum(sr.totalbelanja),0) totalbelanja,  coalesce(pj.jumlah - coalesce(sum(sr.totalbelanja),0), 0) as jumlah
                                from mataanggaran_m ma
                                left join panjar_t pj on ma.id=pj.objectmataanggaranfk and pj.kdprofile = $this->kdProfile and pj.statusenabled = true
                                left join strukrealisasi_t sr on sr.objectpanjarfk = pj.norec and sr.kdprofile = $this->kdProfile and sr.statusenabled = true
                                $qtahun
                                $qsubsubkegiatansr
                                group by ma.kodemataanggaran, ma.namamataanggaran, pj.jumlah
                            ) as s group by kodemataanggaran, namamataanggaran
                        ) as y on y.kodemataanggaran = ma.kodemataanggaran
                        inner join mataanggaranpermen_m map on map.id = ma.mataanggaranpermenfk
                        inner join mataanggaranpermen_m map1 on map1.id = map.mataanggaranpermenfk and map1.div = 4
                        inner join mataanggaranpermen_m map2 on map2.id = map1.mataanggaranpermenfk and map2.div = 3
                        inner join mataanggaranpermen_m map3 on map3.id = map2.mataanggaranpermenfk and map3.div = 2
                        where ma.kdprofile = $this->kdProfile and ma.statusenabled = true
                        and kb.kdprofile = $this->kdProfile and kb.statusenabled = true
                        and map.kdprofile = $this->kdProfile and map.statusenabled = true
                        and ka.kdprofile = $this->kdProfile and ka.statusenabled = true
                        $qsubsubkegiatan
                        $qtahunka
                group by map3.kode || '.' || map2.kode || '.' || map1.kode || '.' || map.kode, map.mataanggaranpermen, x.totalbelanja, y.jumlah
        "));

        $pageWidth = 1200;
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        $profile = Profile::where('id', $this->kdProfile)->first();
        return view('report.anggaran.cetakspjmonev', compact('data', 'pageWidth', 'tahun', 'subkegiatan', 'subsubkegiatan', 'settingrba'));
    }
    public function getCetakSPJ(Request $request)
    {
        $norec = $request['norec'];
        $terbilang = $request['terbilang'];
        $idsumberdana = $request['sumberdana'];

        $tampilsumberdana = false;
        if ($request['sumberdana'] == 18) {
            $tampilsumberdana = true;
        }

        $sumberdana = DB::select(DB::raw("
        select asalproduk from asalproduk_m where id = '$idsumberdana'
        "));

        $kegiatan = DB::select(
            DB::raw("
            select km.keterangan ktsubsub, km.kode kodesubsub, km.tahun, km.div divsubsub, kg.keterangan ktsub, kg.kode kodesub, kg.div divsub, pg.namalengkap, pg.nippns
            from strukrealisasi_t sr
            inner join realisasidetail_t rd on rd.strukrealisasifk =sr.norec
            inner join keteranganbelanja_t kb on kb.norec = rd.keteranganbelanjafk
            inner join kegiatananggaran_m km on km.id = kb.objectkegiatanfk
            inner join mataanggaran_m ma on ma.id = sr.objectmataanggaranfk::int2
            left join kegiatananggaran_m kg on km.kode ilike kg.kode || '%' and kg.div = 3
            left join pegawai_m pg on pg.id = km.objectpptkfk
            where sr.norec = '$norec' AND rd.statusenabled = true
            group by km.keterangan, km.kode, km.tahun, km.div, kg.keterangan, kg.kode, kg.div, pg.namalengkap, pg.nippns;
            ")
        );

        $keterangan = DB::select(DB::raw("select kb.keteranganbelanja
        from strukrealisasi_t sr
        inner join realisasidetail_t rd on rd.strukrealisasifk =sr.norec
        inner join keteranganbelanja_t kb on kb.norec = rd.keteranganbelanjafk
        inner join kegiatananggaran_m km on km.id = kb.objectkegiatanfk
        left join kegiatananggaran_m kg on km.kode ilike kg.kode || '%' and kg.div = 3
        inner join mataanggaran_m ma on ma.id = sr.objectmataanggaranfk::int2
        where sr.norec = '$norec' AND rd.statusenabled = true
        group by kb.keteranganbelanja;"));

        $mataanggaran = DB::select(DB::raw("select ma.namamataanggaran, ma.kodemataanggaran
        from strukrealisasi_t sr
        inner join realisasidetail_t rd on rd.strukrealisasifk =sr.norec
        inner join keteranganbelanja_t kb on kb.norec = rd.keteranganbelanjafk
        inner join kegiatananggaran_m km on km.id = kb.objectkegiatanfk
        left join kegiatananggaran_m kg on km.kode ilike kg.kode || '%' and kg.div = 3
        inner join mataanggaran_m ma on ma.id = sr.objectmataanggaranfk::int2
        where sr.norec = '$norec' AND rd.statusenabled = true
        group by ma.namamataanggaran, ma.kodemataanggaran;"));

        $spj = DB::select(DB::raw("select rk.namarekanan,
        sr.norealisasi, sr.pph, sr.ppn, sr.pph+sr.ppn as totalpp, pgb.namalengkap as namabendahara, pgb.nippns as nipbendahara, pgp.namalengkap as namapenerima,
        pgp.nippns as nippenerima, sr.deskripsi
        from strukrealisasi_t sr
        inner join realisasidetail_t rd on rd.strukrealisasifk =sr.norec
        inner join keteranganbelanja_t kb on kb.norec = rd.keteranganbelanjafk
        inner join kegiatananggaran_m km on km.id = kb.objectkegiatanfk
        left join rekanan_m rk on rk.id = sr.rekananfk
        left join pegawai_m pgb on pgb.id = sr.bendaharafk
        left join pegawai_m pgp on pgp.id = sr.penerimafk
        left join kegiatananggaran_m kg on km.kode ilike kg.kode || '%' and kg.div = 3
        inner join mataanggaran_m ma on ma.id = sr.objectmataanggaranfk::int2
        where sr.norec = '$norec' AND rd.statusenabled = true
        group by pgp.nippns, rk.namarekanan, sr.norealisasi, sr.pph, sr.ppn, pgb.namalengkap, pgp.namalengkap, pgb.nippns, sr.deskripsi"));

        $detail = DB::select(DB::raw("select row_number() over(order by rd.norec) as nomor, rd.norec, rd.uraian, rd.jml, rd.satuan, rd.harga, rd.subtotal
        from strukrealisasi_t sr
        inner join realisasidetail_t rd on rd.strukrealisasifk =sr.norec
        inner join keteranganbelanja_t kb on kb.norec = rd.keteranganbelanjafk
        inner join kegiatananggaran_m km on km.id = kb.objectkegiatanfk
        left join kegiatananggaran_m kg on km.kode ilike kg.kode || '%' and kg.div = 3
        inner join mataanggaran_m ma on ma.id = sr.objectmataanggaranfk::int2
        where sr.norec = '$norec' AND rd.statusenabled = true
        group by rd.uraian, rd.jml, rd.satuan, rd.harga, rd.subtotal, rd.norec;"));

        $jumlah = DB::select(DB::raw("select sum(subtotal) totalfix, round(sum(subtotal)) totalpembulatan
        from(
        select rd.subtotal, rd.norec
                from strukrealisasi_t sr
        inner join realisasidetail_t rd on rd.strukrealisasifk =sr.norec
        inner join keteranganbelanja_t kb on kb.norec = rd.keteranganbelanjafk
        inner join kegiatananggaran_m km on km.id = kb.objectkegiatanfk
        left join pegawai_m pgb on pgb.id = sr.bendaharafk
        left join pegawai_m pgp on pgp.id = sr.penerimafk
        left join kegiatananggaran_m kg on km.kode ilike kg.kode || '%' and kg.div = 3
        inner join mataanggaran_m ma on ma.id = sr.objectmataanggaranfk::int2
        where sr.norec = '$norec' AND rd.statusenabled = true
        group by rd.subtotal, rd.norec
        ) as x;"));



        $result = array(
            'data' => $detail,
            'message' => '@epic',
        );

        $pageWidth = 950;
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        return view('report.anggaran.cetakspj', compact('kegiatan', 'pageWidth', 'keterangan', 'mataanggaran', 'spj', 'detail', 'jumlah', 'terbilang', 'tampilsumberdana', 'sumberdana', 'settingrba'));
    }
    public function getCetakPengantarSPP(Request $request)
    {
        $norec = $request['norec'];
        $terbilang = $request['terbilang'];
        $terbilangspd = $request['terbilangspd'];
        $detail = collect(DB::select("select sp.*, sd.nospd, sd.nilaispd from spp_t sp
        left join spd_t sd on sd.norec = sp.objectspdfk
        where sp.statusenabled = true and sp.kdprofile = $this->kdProfile and sp.norec = '$norec'"))->first();
        $tgl = date('Y-m-d', strtotime($detail->tglspp));
        $result = array(
            'data' => $detail,
            'message' => '@epic',
        );
        $pageWidth = 950;
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        // dd($settingrba);
        $profile = Profile::where('id', $this->kdProfile)->first();

        return view('report.anggaran.cetaksuratpengantarspp', compact('pageWidth', 'detail', 'terbilang', 'tgl', 'settingrba', 'profile', 'terbilangspd'));
    }

    public function getCetakRingkasanSPP(Request $request)
    {
        $norec = $request['norec'];
        $terbilang = $request['terbilang'];

        $detail = collect(DB::select("select sp.*, sd.nospd, sd.nilaispd from spp_t sp
        left join spd_t sd on sd.norec = sp.objectspdfk
        where sp.statusenabled = true and sp.kdprofile = $this->kdProfile and sp.norec = '$norec'"))->first();

        $tgl = date('Y-m-d', strtotime($detail->tglspp));

        $result = array(
            'data' => $detail,
            'message' => '@epic',
        );
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        $pageWidth = 950;
        return view('report.anggaran.cetakringkasanspp', compact('pageWidth', 'detail', 'terbilang', 'tgl', 'settingrba'));
    }

    public function getCetakRincianSPP(Request $request)
    {
        $norec = $request['norec'];
        $terbilang = $request['terbilang'];
        $detail = collect(DB::select("select sp.*, sd.nospd, sd.nilaispd from spp_t sp
        left join spd_t sd on sd.norec = sp.objectspdfk
        where sp.statusenabled = true and sp.kdprofile = $this->kdProfile and sp.norec = '$norec'"))->first();


        $permen = DB::select(DB::raw("select row_number() over(order by id) as nomor, *
            from mataanggaranpermen_m where statusenabled = true and kdprofile = $this->kdProfile and div = 2
        "));

        $tgl = date('Y-m-d', strtotime($detail->tglspp));

        $result = array(
            'data' => $detail,
            'message' => '@epic',
        );
        $settingrba = collect(DB::select("
        select sa.*, pg.namalengkap as namalengkap, pg.nippns from settinganggaran_t sa
        inner join pegawai_m pg on pg.id = sa.objectkepalabludfk
        where sa.statusenabled = true
        "))->first();
        $pageWidth = 950;
        return view('report.anggaran.cetakrincianspp', compact('pageWidth', 'detail', 'terbilang', 'tgl', 'permen', 'settingrba'));
    }
    public function getCetakPengantarSPM(Request $request)
    {
        $norec = $request['norec'];
        $terbilang = $request['terbilang'];


        $detail = DB::select(
            DB::raw("
            select * from spm_t where statusenabled = true and kdprofile = $this->kdProfile and norec = '$norec'
            ")
        );

        $tgl = date('Y-m-d', strtotime($detail[0]->tglspm));

        $result = array(
            'data' => $detail,
            'message' => '@epic',
        );

        $pageWidth = 950;
        return view('report.anggaran.cetaksuratpengantarspm', compact('pageWidth', 'detail', 'terbilang', 'tgl'));
    }
    public function getCetakPertanggungjawabanSPM(Request $request)
    {
        $norec = $request['norec'];
        $terbilang = $request['terbilang'];


        $detail = DB::select(
            DB::raw("
            select * from spm_t where statusenabled = true and kdprofile = $this->kdProfile and norec = '$norec'
            ")
        );

        $tgl = date('Y-m-d', strtotime($detail[0]->tglspm));

        $result = array(
            'data' => $detail,
            'message' => '@epic',
        );

        $pageWidth = 950;
        return view('report.anggaran.cetaksuratpertanggungjawabanspm', compact('pageWidth', 'detail', 'terbilang', 'tgl'));
    }
    public function getCetakRincianSPM(Request $request)
    {
        $norec = $request['norec'];
        $terbilang = $request['terbilang'];


        $detail = DB::select(
            DB::raw("
            select * from spm_t where statusenabled = true and kdprofile = $this->kdProfile and norec = '$norec'
            ")
        );

        $spd = DB::select(DB::raw("
            select spd.*
                from spm_t spm
            inner join spp_t spp on spp.norec = spm.objectsppfk
            left join spd_t spd on spd.norec = spp.objectspdfk and spd.statusenabled = true
            where spm.statusenabled = true
            and spm.kdprofile = $this->kdProfile
            and spp.statusenabled = true
            and spp.kdprofile = $this->kdProfile
            and spm.norec = '$norec'
        "));

        $tglspd = '';
        $tglspd = date('Y-m-d', strtotime($spd[0]->tglspd));
        $tgl = date('Y-m-d', strtotime($detail[0]->tglspm));

        $result = array(
            'data' => $detail,
            'message' => '@epic',
        );

        $pageWidth = 950;
        return view('report.anggaran.cetaksuratrincianspm', compact('pageWidth', 'detail', 'terbilang', 'tgl', 'tglspd'));
    }

    public function penerimaanKasirIGD(Request $request) 
    {
        $profile = Profile::find($this->kdProfile);
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');

            $pdf->loadView(
                'report.kasir.laporan-penerimaan-kasir-igd-pagi',
                compact('profile')
            );
            return $pdf->stream();
        } else {
            return view(
                'report.kasir.laporan-penerimaan-kasir-igd-pagi',
                compact('profile')
            );
        }
    }

    public function liatViewLap(Request $req)
    {
        $profile = Profile::find($this->kdProfile);
//         SELECT  FROM antrianpasiendiperiksa_t as apd
// left join pasiendaftar_t as pd on pd.norec = apd.noregistrasifk
// left join kamar_m as km on km.id = apd.objectkamarfk
// left join pasien_m as ps on ps."id" = pd.nocmfk
// left join jeniskelamin_m as jk on jk."id" = ps.objectjeniskelaminfk
// left join pegawai_m as pg on pg.id = apd.objectpegawaifk
        
        // tar dl
        
        $data = DB::table('antrianpasiendiperiksa_t as apd')
                ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
                ->leftJoin('kamar_m as kmr', 'kmr.id', 'apd.objectkamarfk')
                ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'ps.objectkelaminfk')
                ->leftJoin('pegawai_m as pg', 'pg.id', 'apd.objectpegawaifk');
                // ->select('apd.')
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'potrait');

            $pdf->loadView(
                'report.laporan.registrasi-ranap',
                compact('profile')
            );
            return $pdf->stream();
        } else {
            return view(
                'report.laporan.registrasi-ranap',
                compact('profile')
            );
        }
    }
}
