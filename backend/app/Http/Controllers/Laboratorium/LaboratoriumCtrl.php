<?php

namespace App\Http\Controllers\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\HasilLaboratorium;
use App\Models\Transaksi\HasilPemeriksaanLab;
use App\Models\Transaksi\HasilPemeriksaanPcr;
use App\Models\Transaksi\HasilPemeriksaanMikro;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\LabBuktiTransaksi;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\BundleKlaim;
use App\Traits\Valet;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode as Png;
use Endroid\QrCode\Writer\PngWriter;
use Ramsey\Uuid\Uuid;


class LaboratoriumCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function dokterLab(Request $r)
    {
        $kdProfile = (int) $this->kdProfile;
        $res['dokter'] = Pegawai::mine()->where('objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))->get();
        return $this->respond($res);
    }

    public function LayananLab(Request $r)
    {
        $kdProfile = (int) $this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)->where('norec', $r['norec_pd'])->first();
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftJoin('orderpelayanan_t as op', 'op.strukorderfk', '=', 'pp.strukorderfk')
            ->leftJOIN('order_lab as lis', function ($join) {
                $join->on('lis.no_lab', '=', 'so.noorder');
                $join->on(DB::raw('lis.kode_test::int'), '=', 'pp.produkfk');
            })
            ->select(
                'pp.norec',
                'apd.norec as norec_apd',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'apd.norec as norec_apd',
                'so.noorder',
                'so.noregistrasi',
                'so.keteranganlainnya',
                'pp.strukfk',
                'pp.produkfk',
                'ru.objectdepartemenfk',
                'lis.no_lab as idbridging',
                'so.namafile',
                'so.norec as norec_so',
                DB::raw("
                    case when pp.jasa is not null then pp.jasa else 0 end jasa,
                    case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                    ((pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah) + (case when pp.jasa is not null then pp.jasa else 0 end) as total,
                    to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                    case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
                ")
            )
            ->where('pp.statusenabled', true)
            ->whereNull('pp.strukresepfk')
            ->where('pp.kdprofile', $kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->where('so.objectkelompoktransaksifk', $this->settingFix('kelompokTransaksiLab'))
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->distinct();

        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }

        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.kdprofile', $kdProfile)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();

        $orderPelayanan = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->select(
                'op.objectprodukfk',
                'op.norec as norec_op',
                'so.noregistrasi',
                'so.norec as norec_so',
                'so.noorder',
            )
            ->where('so.noregistrasi', $pd->noregistrasi)
            ->where('op.statusenabled', true)
            ->get();

        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group = [];

        foreach ($data as $item) {
            $item->checked = false;
            $result['total'] = $result['total'] + (float) $item->total;
            $result['diskon'] = $result['diskon'] + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                }
            }
            foreach ($orderPelayanan as $detail) {
                $item->norec_op = null;
                $sameRegistration = $detail->noregistrasi === $item->noregistrasi;
                $sameProduct = $detail->objectprodukfk === $item->produkfk;
                $sameOrderRecord = $detail->norec_so === $item->norec_so;
                $sameOrderNumber = $detail->noorder === $item->noorder;

                if ($sameRegistration && $sameProduct && $sameOrderRecord && $sameOrderNumber) {
                    $item->norec_op = $detail->norec_op;
                    break;
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->tglpelayanan_group == $group[$i]['tglpelayanan_group']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'tglpelayanan_group' => $item->tglpelayanan_group,
                    'details' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['tglpelayanan_group'] == $d2->tglpelayanan_group) {
                    $group[$k]['details'][] = $d2;
                }
            }
        }

        $firstData = $data->first();

        $result['length'] = count($data);
        $result['detail'] = $group;
        $result['noorder'] = $firstData ? $firstData->noorder : null;
        $result['list_ruangan'] = AntrianPasienDiperiksa::listRuangan($pd->noregistrasi);
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function getHasilLabManual(Request $r)
    {
        $norecpp = explode(',', $r['norec_pp']);
        $kdProfile = $this->kdProfile;
        $jenis = DB::table('pasien_m as ps')
            ->join('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.objectjeniskelaminfk',
            )
            ->where('ps.id', $r['nocmfk'])
            ->where('ps.statusenabled', true)
            ->first();
        if (!empty($jenis)) {

            $date = new \DateTime($jenis->tgllahir);
            $now = new \DateTime();
            $jenis->umur = $now->diff($date)->format("%a");
        }
        $data = DB::select(DB::raw("SELECT 
        pp.noregistrasifk AS norec_apd,
        djp.detailjenisproduk,
        pp.produkfk,
        prd.namaproduk,
        prd.metode_lab,
        pdl.detailpemeriksaan,
        hh.hasil,
        pdlm.rangemin AS nilaimin,
        pdlm.rangemax AS nilaimax,
        pdlm.refrange AS nilaitext,
        ss.satuanstandar,
        pdl.id AS iddetailproduk,
        hh.metode,
        pp.norec AS norec_pp,
        hh.metode,
        hh.norec AS norec_hasil,
        hh.keterangan,
        hh.flag,
        hh.catatan,
        pg.id as iddokter,
        pg.namalengkap as namadokter,
        pg2.id as iddokterverif,
        pg2.namalengkap as namadokterverif
    FROM
        pelayananpasien_t AS pp
        INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        INNER JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
        LEFT JOIN produkdetaillaboratorium_m AS pdl ON pdl.produkfk = prd.id AND pdl.statusenabled = TRUE
        LEFT JOIN produkdetaillaboratoriumnilainormal_m AS pdlm ON pdlm.produkdetaillabfk = pdl.id
        AND pdlm.jeniskelaminfk = '$jenis->objectjeniskelaminfk'
        AND '$jenis->umur' between pdlm.ageminday and pdlm.agemaxday
        left JOIN hasillaboratorium_t AS hh ON hh.norecpelayanan = pp.norec
        AND hh.statusenabled = TRUE
        AND pp.noregistrasifk = hh.noregistrasifk
        AND pp.norec = hh.norecpelayanan
        AND hh.produkdetaillabfk = pdl.id
        LEFT JOIN satuanstandar_m AS ss ON ss.id = pdl.satuanstandarfk
        LEFT JOIN pelayananpasienpetugas_t as ppp on ppp.pelayananpasien = pp.norec
        LEFT JOIN pegawai_m AS pg ON pg.id = ppp.objectpegawaifk
        LEFT JOIN pegawai_m AS pg2 ON pg2.id = ppp.pegawaiverifikatorfk
    WHERE
         pp.noregistrasifk = '$r[norec_apd]'
        AND pp.kdprofile = $kdProfile
        AND pdl.statusenabled = TRUE
        and pdl.nourut <> 0
    GROUP BY pp.noregistrasifk,
        djp.detailjenisproduk,
        pp.produkfk,
        prd.namaproduk,
        prd.metode_lab,
        pdl.detailpemeriksaan,
        hh.hasil,
        pdlm.rangemin,
        pdlm.rangemax,
        pdlm.refrange,
        ss.satuanstandar,
        pdl.id,
        hh.metode,
        pp.norec,
        hh.metode,
        hh.norec,
        hh.keterangan,
        hh.flag,
        hh.catatan,
        pg.id,
        pg.namalengkap,
        pg2.id,
        pg2.namalengkap,
        djp.qdetailjenisproduk
    ORDER BY
        djp.qdetailjenisproduk,
        pdl.nourut"));

        $hasil = [];
        foreach ($data as $datas) {
            if ($r['norec_pp'] == '') {
                $hasil[] = [
                    'namaproduk' => $datas->namaproduk,
                    'detailpemeriksaan' => $datas->detailpemeriksaan,
                    'hasil' => $datas->hasil,
                    'flag' => $datas->flag,
                    'noregistrasifk' => $datas->norec_apd,
                    'produkdetaillabfk' => $datas->iddetailproduk,
                    'produkfk' => $datas->produkfk,
                    'nilaitext' => $datas->nilaitext,
                    'satuanstandar' => $datas->satuanstandar,
                    'norec_pp' => $datas->norec_pp,
                    'nilaimin' => $datas->nilaimin,
                    'nilaimax' => $datas->nilaimax,
                    'metode' => $datas->metode,
                    'catatan' => $datas->catatan,
                    'iddokter' => $datas->iddokter,
                    'namadokter' => $datas->namadokter,
                    'iddokterverif' => $datas->iddokterverif,
                    'namadokterverif' => $datas->namadokterverif,
                    'metode' => $datas->metode_lab
                ];
            } else {
                foreach ($norecpp as $norec_pp) {
                    if (trim($datas->norec_pp) == trim($norec_pp)) {
                        $hasil[] = [
                            'namaproduk' => $datas->namaproduk,
                            'detailpemeriksaan' => $datas->detailpemeriksaan,
                            'hasil' => $datas->hasil,
                            'flag' => $datas->flag,
                            'noregistrasifk' => $datas->norec_apd,
                            'produkdetaillabfk' => $datas->iddetailproduk,
                            'produkfk' => $datas->produkfk,
                            'nilaitext' => $datas->nilaitext,
                            'satuanstandar' => $datas->satuanstandar,
                            'norec_pp' => $norec_pp,
                            'nilaimin' => $datas->nilaimin,
                            'nilaimax' => $datas->nilaimax,
                            'metode' => $datas->metode,
                            'catatan' => $datas->catatan,
                            'iddokter' => $datas->iddokter,
                            'namadokter' => $datas->namadokter,
                            'iddokterverif' => $datas->iddokterverif,
                            'namadokterverif' => $datas->namadokterverif,
                            'metode' => $datas->metode_lab
                        ];
                    }
                }
            }
        }
        $res['data'] = $hasil;
        return $this->respond($res);
    }
    public function getHasilLabPABridging(Request $re)
    {
        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            // ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO') // Uncomment if needed
            ->where('rh.ONO', $re['noorder'])
            ->select('rh.*')
            ->get();
        // ->get(['rh.*']);

        // dd($dataBrid);

        // Network share credentials
        $username = 'SIM_LIS';
        $password = 'Admin123';
        $server = '103.110.184.30';
        $sharePath = '\\\\103.110.184.30\\backup';

        if (empty($dataBrid) || count($dataBrid) == 0) {
            abort(404, 'data belum di input oleh lab');
        }
        // return $test;
        $res['data'] = $dataBrid;
        if (!empty($dataBrid)) {
            $path = $dataBrid[0]->pdf_url;
        }

        //khusus di lokal server
        // $path_file = str_replace('10.60.1.45','103.110.184.30',$path);

        //ini khusus untuk di cloud server
        // $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/share_rad/', $path);
        $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/', $path);


        // $path_file ='/mnt/L2412000008_PUTU RESTI DEWI_00.43.53_20241217_PBM20241248.pdf';
        if (!file_exists($path_file)) {
            return response()->json(['error' => 'Belum ada hasil'], 404);
        }

        // Serve the file to the client
        return ResponseFacade::make(file_get_contents($path_file), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename=testing.pdf'
        ]);

        return $this->respond($path_file);
    }

    public function getHasilLabPABridgingKlaim(Request $re)
    {

        $kdProfile = $this->kdProfile;
        $dataRegistrasi = PasienDaftar::where('noregistrasi', $re['noregistrasi'])->first();

        $order = DB::table('strukorder_t AS so')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pasien_m AS pm', 'pm.id', '=', 'so.nocmfk')
            ->where('so.keteranganorder', 'Order Laboratorium')
            ->where('so.objectruangantujuanfk', 336)
            ->where('pd.noregistrasi', '=', $re['noregistrasi'])
            ->where('pd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->select('so.noorder')
            ->get();

        $order = $order->toArray();

        $arr = [];
        for ($x = 0; $x < count($order); $x++) {
            $arr[$x] = $order[$x]->noorder;
        }

        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->where('rh.ONO', $arr)
            ->select('rh.*')
            ->get();


        $res['data'] = $dataBrid;
        if (!empty($dataBrid)) {
            $path = $dataBrid[0]->pdf_url;
        }

        // $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/share_rad/', $path);
        $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/', $path);
        $disk = 'app/public/';
        $fileName = 'hasil_lab_pa' . $re['noregistrasi'] . '.pdf';
        $pathbundle = $disk . 'dokumen_klaim/' . $re['noregistrasi'] . "/" . $fileName;

        $file_content = file_get_contents($path_file);

        if ($file_content === false) {
            return response()->json(['error' => 'File tidak ditemukan atau tidak dapat diakses'], 404);
        } else {
            file_put_contents(storage_path($pathbundle), file_get_contents($path_file));

            if (storage_path($pathbundle)) {

                $cek = DB::table('monitoringdokklaim_t')->where('filename', '=', $fileName)->first();

                if (empty($cek)) {
                    $dataInsert = array(
                        "norec" => Uuid::uuid4(),
                        "kdprofile" => $kdProfile,
                        "statusenabled" => true,
                        "filename" => $fileName,
                        "filepath" => 'dokumen_klaim/' . $re['noregistrasi'] . "/" . $fileName,
                        "nocmfk" => $dataRegistrasi->nocmfk,
                        "tglregistrasi" => $dataRegistrasi->tglregistrasi,
                        "noregistrasifk" => $dataRegistrasi->norec,
                        "documentklaimfk" => 515,
                    );


                    DB::table('monitoringdokklaim_t')->insert($dataInsert);
                }

                $bundleKlaimDB = BundleKlaim::where('filename', $fileName)
                ->where('noregistrasi', $re['noregistrasi'])
                ->first();

                if($bundleKlaimDB) {
                    $bundleKlaimDB->data = base64_encode(file_get_contents(storage_path($pathbundle)));
                    $bundleKlaimDB->save();
                }else {
                    $bundleKlaimDB = new BundleKlaim;
                    $bundleKlaimDB->data = base64_encode(file_get_contents(storage_path($pathbundle)));
                    $bundleKlaimDB->filename = $fileName;
                    $bundleKlaimDB->noregistrasi = $re['noregistrasi'];
                    $bundleKlaimDB->save();
                }
            }
        }

        // return response($file_content, 200)
        //     ->header('Content-Type', 'application/pdf')
        //     ->header('Content-Disposition', 'inline; filename="testing.pdf"');
        // return $this->respond($path_file);










        // $kdProfile = $this->kdProfile;
        // $dataRegistrasi = PasienDaftar::where('noregistrasi', $re['noregistrasi'])->first();

        // $order = DB::table('strukorder_t AS so')
        // ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
        // ->leftjoin('pasien_m AS pm', 'pm.id', '=', 'so.nocmfk')
        // ->where('so.keteranganorder', 'Order Laboratorium')
        // ->where('so.objectruangantujuanfk', 336)
        // ->where('pd.noregistrasi', '=', $re['noregistrasi'])
        // ->where('pd.statusenabled', true)
        // ->where('so.statusenabled', true)
        // ->select('so.noorder')
        // ->get();


        // $order = $order->toArray();

        // // dd($order);


        // $arr = [];
        // for($x = 0; $x < count($order); $x++){
        //     $arr[$x] = $order[$x]->noorder;
        // }

        // $dataBrid = DB::connection('sqlsrv_lis')
        //     ->table('reshd as rh')
        //     // ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO') // Uncomment if needed
        //     ->where('rh.ONO', $arr)
        //     ->select('rh.*')
        //     ->get();
        //     // ->get(['rh.*']);

        //     // dd($dataBrid);

        // // Network share credentials
        // $username = 'SIM_LIS';
        // $password = 'Admin123';
        // $server = '103.110.184.24';
        // $sharePath = '\\\\103.110.184.24\\backup';

        // if(empty($dataBrid)){
        //     abort(404,'data belum di input oleh lab');
        // }
        // // return $test;
        // $res['data'] = $dataBrid;
        // if (!empty($dataBrid)) {
        //     $path = $dataBrid[0]->pdf_url;
        // }

        // //khusus di lokal server
        // // $path_file = str_replace('10.60.1.45','103.110.184.30',$path);

        // //ini khusus untuk di cloud server
        // $path_file = str_replace('\\\\103.110.184.24\\backup\\', '/mnt/share_rad/', $path);
        // $disk = 'app/public/';
        // $fileName = 'hasil_lab_pa' . $re['noregistrasi'] . '.pdf';
        // $pathbundle = $disk. 'dokumen_klaim/' . $re['noregistrasi'] . "/" . $fileName;



        // // $path_file ='/mnt/L2412000008_PUTU RESTI DEWI_00.43.53_20241217_PBM20241248.pdf';
        // if (!file_exists($path_file)) {
        //     return response()->json(['error' => 'Belum ada hasil'], 404);
        // } else{
        //     file_put_contents(storage_path($pathbundle), file_get_contents($path_file));

        //     if (storage_path($pathbundle)) {

        //         $cek = DB::table('monitoringdokklaim_t')->where('filename', '=', $fileName)->first();

        //         if(empty($cek)){
        //             $dataInsert = array(
        //                 "norec" => Uuid::uuid4(),
        //                 "kdprofile" => $kdProfile,
        //                 "statusenabled" => true,
        //                 "filename" => $fileName,
        //                 "filepath" => 'dokumen_klaim/' . $re['noregistrasi'] . "/" . $fileName,
        //                 "nocmfk" => $dataRegistrasi->nocmfk,
        //                 "tglregistrasi" => $dataRegistrasi->tglregistrasi,
        //                 "noregistrasifk" => $dataRegistrasi->norec,
        //                 "documentklaimfk" => 515,
        //             );


        //             DB::table('monitoringdokklaim_t')->insert($dataInsert);
        //         }


        //     }
        // }

        // // Serve the file to the client

        // // dd($pdf);

        return;
    }

    public function getHasilLabPABridgingKlaim2(Request $re)
    {

        $order = DB::table('strukorder_t AS so')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pasien_m AS pm', 'pm.id', '=', 'so.nocmfk')
            ->where('pd.noregistrasi', '=', $re['noregistrasi'])
            ->where('pd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->select('so.noorder')
            ->get();


        $order = $order->toArray();

        // dd($order);


        $arr = [];
        for ($x = 0; $x < count($order); $x++) {
            $arr[$x] = $order[$x]->noorder;
        }

        // dd($arr);

        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->where('rh.ONO', $arr)
            ->select('rh.*')
            ->get();
        // ->get(['rh.*']);

        // dd($dataBrid);

        // Network share credentials
        $username = 'SIM_LIS';
        $password = 'Admin123';
        $server = '103.110.184.30';
        $sharePath = '\\\\103.110.184.30\\backup';

        if (empty($dataBrid)) {
            abort(404, 'data belum di input oleh lab');
        }
        // return $test;
        $res['data'] = $dataBrid;
        if (!empty($dataBrid)) {
            $path = $dataBrid[0]->pdf_url;
        }

        //khusus di lokal server
        // $path_file = str_replace('10.60.1.45','103.110.184.30',$path);

        //ini khusus untuk di cloud server
        // $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/share_rad/', $path);
        $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/', $path);


        // $path_file ='/mnt/L2412000008_PUTU RESTI DEWI_00.43.53_20241217_PBM20241248.pdf';
        if (!file_exists($path_file)) {
            return response()->json(['error' => 'Belum ada hasil'], 404);
        }

        // Serve the file to the client
        return Response::make(file_get_contents($path_file), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename=testing.pdf'
        ]);

        return $this->respond($path_file);
    }

    public function getHasilLabPAAllBridging(Request $re)
    {
        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->where('rh.ONO', $re['noorder'])
            ->select('rh.*')
            ->get();
        $res['data'] = $dataBrid;
        if (!empty($dataBrid)) {
            $path = $dataBrid[0]->pdf_url;
        }

        // $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/share_rad/', $path);
        $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/', $path);

        $file_content = file_get_contents($path_file);

        if ($file_content === false) {
            return response()->json(['error' => 'File tidak ditemukan atau tidak dapat diakses'], 404);
        }

        return response($file_content, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="testing.pdf"');
        return $this->respond($path_file);
    }

    public function getHasilLabPAAllBridgingKlaim(Request $re)
    {
        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->where('rh.ONO', $re['noorder'])
            ->select('rh.*')
            ->get();
        $res['data'] = $dataBrid;
        if (!empty($dataBrid)) {
            $path = $dataBrid[0]->pdf_url;
        }

        $path_file = str_replace('\\\\10.60.1.45\\backup\\', '/mnt/share_rad/', $path);

        $file_content = file_get_contents($path_file);

        if ($file_content === false) {
            return response()->json(['error' => 'File tidak ditemukan atau tidak dapat diakses'], 404);
        }

        return response($file_content, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="testing.pdf"');
        return $this->respond($path_file);
    }

    private function formatTimestamp($timestamp)
    {
        $year = substr($timestamp, 0, 4);
        $month = substr($timestamp, 4, 2);
        $day = substr($timestamp, 6, 2);
        $hour = substr($timestamp, 8, 2);
        $minute = substr($timestamp, 10, 2);
        $second = substr($timestamp, 12, 2);

        $date = Carbon::create($year, $month, $day, $hour, $minute, $second);
        return $date->format('d-m-Y H:i:s');
    }

    public function getHasilLabBridging(Request $r)
    {
        // hasil bridging
        $noorder = explode(',', $r['noorder']);
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));
        // $dataBrid = DB::select(DB::raw("SELECT hl.nama_pemeriksaan as namaproduk, hl.nama_pemeriksaan as detailpemeriksaan,
        // hl.hasil, hl.flag, hl.normal as nilaitext, hl.unit as satuanstandar, hl.user_validasi, hl.tgl_hasil, hl.metode
        // FROM lab_hasil hl
        // INNER JOIN strukorder_t so ON so.noorder = hl.no_order
        // --INNER JOIN produk_m AS prd ON prd.id = hl.kode_pemeriksaan::int
        // WHERE so.noorder IN ($placeholders)"), $noorder);

        // return $placeholders;
        $new_arr = [];
        $onoarr  = "";
        $i = 0;
        $count_noorder = count($noorder);
        foreach ($noorder as $value) {
            $new_arr[] = (string) $value;
            if ($value != '') {
                if ($i > "0" && $i < $count_noorder) {
                    $onoarr = $onoarr . "," . "'" . $value . "'";
                } else {
                    $onoarr = $onoarr . "'" . $value . "'";
                }
            }
            $i++;
        }
        // return $onoarr;
        //var_dump($new_arr);

        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
            // ->whereIn('rh.ONO', [$new_arr[0]])
            ->whereraw("rh.ono in ($onoarr)")
            ->select('rd.*')
            ->get();



        $hasilbrid = [];
        foreach ($dataBrid as $item) {
            $hasilbrid[] = [
                'namaproduk' => $item->ORDER_TESTNM,
                'detailpemeriksaan' => $item->TEST_NM,
                'comment' => $item->TEST_COMMENT,
                'hasil' => $item->RESULT_VALUE,
                'flag' => $item->FLAG,
                'nilaitext' => $item->REF_RANGE,
                'satuanstandar' => $item->UNIT,
                'analis' => strstr($item->VALIDATE_BY, '^', false),
                'tglhasil' => $this->formatTimestamp($item->VALIDATE_ON),
                'metode' => $item->METHOD,
            ];
        }

        $res['data'] = $hasilbrid;
        return $this->respond($res);
    }
    public function saveHasilLabManual(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            foreach ($request['hasil'] as $value) {

                $PelPasienPetugas = new PelayananPasienPetugas();
                $PelPasienPetugas->norec = $PelPasienPetugas->generateNewId();
                $PelPasienPetugas->kdprofile = $this->kdProfile;
                $PelPasienPetugas->statusenabled = true;
                $PelPasienPetugas->nomasukfk = $value['noregistrasifk'];
                $PelPasienPetugas->objectpegawaifk = $request['pegawaifk']; //$request['objectpegawaiorderfk'];
                $PelPasienPetugas->pegawaiverifikatorfk = $request['pegawaiverif'];
                $PelPasienPetugas->tglpelayanan =  date('Y-m-d H:i:s');
                $PelPasienPetugas->objectjenispetugaspefk = $this->settingFix('idDokterPemeriksa'); //$jenisPetugasPe->objectjenispetugaspefk;
                $PelPasienPetugas->pelayananpasien = $value['norec_pp'];
                $PelPasienPetugas->noregistrasi =  $request['noregistrasi'];
                $PelPasienPetugas->save();


                $h = HasilLaboratorium::where([
                    'noregistrasifk' => $value['noregistrasifk'],
                    'norecpelayanan' => $value['norec_pp'],
                    'produkfk' => $value['produkfk'],
                    'detailpemeriksaan' => $value['detailpemeriksaan'],
                ])->first();

                if (!empty($h)) {
                    HasilLaboratorium::where([
                        'noregistrasifk' => $value['noregistrasifk'],
                        'norecpelayanan' => $value['norec_pp'],
                        'produkfk' => $value['produkfk'],
                        'detailpemeriksaan' => $value['detailpemeriksaan'],
                    ])->update(
                        [
                            'hasil' => $value['hasil'],
                            'flag' => $value['flag'],
                            'satuan' => $value['satuanstandar'],
                            'nilainormal' => $value['nilaitext'],
                            'group' => $value['namaproduk'],
                            'metode' => isset($value['metode']) ? $value['metode'] : null,
                            'nilaimin' => $value['nilaimin'],
                            'nilaimax' => $value['nilaimax'],
                            'pegawaifk' => $request['pegawaifk'],
                            'catatan' => $request['catatan'],
                        ]
                    );
                } else {
                    $h = new HasilLaboratorium();
                    $h->norec = $h->generateNewId();
                    $h->kdprofile = $kdProfile;
                    $h->statusenabled = true;
                    $h->tglhasil = date('Y-m-d H:i:s');
                    $h->pegawaifk = $value['pegawaifk'];
                    $h->hasil = $value['hasil'];
                    $h->noregistrasifk = $value['noregistrasifk'];
                    $h->produkfk = $value['produkfk'];
                    $h->flag = $value['flag'];
                    $h->produkdetaillabfk = $value['produkdetaillabfk'];
                    $h->detailpemeriksaan = $value['detailpemeriksaan'];
                    $h->norecpelayanan = $value['norec_pp'];
                    $h->satuan = $value['satuanstandar'];
                    $h->nilainormal = $value['nilaitext'];
                    $h->group = $value['namaproduk'];
                    $h->metode = isset($value['metode']) ? $value['metode'] : null;
                    $h->nilaimin = $value['nilaimin'];
                    $h->nilaimax = $value['nilaimax'];
                    $h->pegawaifk = $request['pegawaifk'];
                    $h->catatan = $request['catatan'];
                    $h->save();
                }
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $h,
                "message" => "Simpan Hasil Laboratorium Sukses"
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Hemooh",
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getHasilPemeriksaanLab(Request $request)
    {
        $data = DB::table('hasilpemeriksaanlab_t as ar')
            ->join('pegawai_m as pg', 'pg.id', '=', 'ar.pegawaifk')
            ->select('ar.*', 'pg.namalengkap')
            ->where('ar.kdprofile', $this->kdProfile)
            ->where('ar.statusenabled', true)
            ->where('ar.pelayananpasienfk', $request['norec_pp'])
            ->first();

        return $this->respond($data);
    }

    public function saveHasilLabPA(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == "") {
                $dataSO = new HasilPemeriksaanLab();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $kdProfile;
                $dataSO->statusenabled = true;
            } else {
                $dataSO = HasilPemeriksaanLab::where('norec', $request['norec'])->first();
            }
            $dataSO->nomorpa = $request['nomorpa'];
            $dataSO->tanggal = date('Y-m-d H:i:s');
            $dataSO->pegawaifk = $request['pegawaifk'];
            $dataSO->dokterpengirimfk = $request['dokterpengirimfk'];
            $dataSO->jenis = $request['jenis'];
            $dataSO->pelayananpasienfk = $request['pelayananpasienfk'];
            $dataSO->noregistrasifk = $request['noregistrasifk'];
            $dataSO->diagnosaklinik = $request['diagnosaklinik'];
            $dataSO->keteranganklinik = $request['keteranganklinik'];
            $dataSO->diagnosapb = $request['diagnosapb'];
            $dataSO->keteranganpb = $request['keteranganpb'];
            $dataSO->topografi = $request['topografi'];
            $dataSO->morfologi = $request['morfologi'];
            $dataSO->makroskopik = $request['makroskopik'];
            $dataSO->mikroskopik = $request['mikroskopik'];
            $dataSO->kesimpulan = $request['kesimpulan'];
            $dataSO->anjuran = $request['anjuran'];
            $dataSO->jaringanasal = $request['jaringanasal'];
            // return $dataSO;
            $dataSO->save();

            //
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
                    "data" => $dataSO,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function hapusTIndakanLabVerif(Request $r)
    {
        DB::beginTransaction();
        try {
            OrderPelayanan::where('norec', $r['norec_op'])->where('kdprofile', $this->kdProfile)->update([
                'statusenabled' => false,
            ]);

            // $this->LOGGING(
            //     'Hapus Tindakan',
            //     $r['norec_pp'],
            //     'pelayananpasien_t',
            //     'Hapus Tindakan ' . $r['namaproduk'] . ' di ' . $r['ruangantujuan'] . ' pada Pasien ' .
            //     $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
            // );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function hapusTindakanLab(Request $r)
    {
        DB::beginTransaction();
        try {
            foreach ($r['data'] as $item) {

                //old method
                // PelayananPasienDetail::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                // PelayananPasienPetugas::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                // PelayananPasien::where('norec', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                // OrderPelayanan::where('norec',$item['norec_op'])->where('kdprofile', $this->kdProfile)->delete();

                // new method
                PelayananPasienDetail::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->update([
                    'statusenabled' => false,
                ]);
                PelayananPasienPetugas::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->update([
                    'statusenabled' => false,
                ]);
                PelayananPasien::where('norec', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->update([
                    'statusenabled' => false,
                ]);
                OrderPelayanan::where('norec', $item['norec_op'])->where('kdprofile', $this->kdProfile)->update([
                    'statusenabled' => false,
                ]);
                StrukOrder::where('noorder', $item['noorder'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update([
                        'statusorder' => 1,
                    ]);

                $this->LOGGING(
                    'Hapus Tindakan',
                    $item['norec_pp'],
                    'pelayananpasien_t',
                    'Hapus Tindakan ' . $item['namaproduk'] . ' di ' . $item['namaruangan'] . ' pada Pasien ' .
                        $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );
            }


            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function hapusTindakanLabAll(Request $req)
    {

        DB::beginTransaction();

        try {
            $getSoValueFalse = StrukOrder::where('noorder', $req['noorder'])->select('norec', 'noorder')->first();
            if (!$getSoValueFalse) {
                throw new \Exception('StrukOrder tidak ditemukan');
            }

            $getPPValueFalse = PelayananPasien::where('strukorderfk', $getSoValueFalse->norec)->pluck('norec');

            if ($getPPValueFalse->isEmpty()) {
                throw new \Exception('PelayananPasien tidak ditemukan');
            }

            PelayananPasienDetail::whereIn('pelayananpasien', $getPPValueFalse)
                ->where('kdprofile', $this->kdProfile)
                ->update(['statusenabled' => false]);

            PelayananPasienPetugas::whereIn('pelayananpasien', $getPPValueFalse)
                ->where('kdprofile', $this->kdProfile)
                ->update(['statusenabled' => false]);

            PelayananPasien::where('strukorderfk', $getSoValueFalse->norec)
                ->where('kdprofile', $this->kdProfile)
                ->update(['statusenabled' => false]);

            OrderPelayanan::where('strukorderfk', $getSoValueFalse->norec)
                ->where('kdprofile', $this->kdProfile)
                ->update(['statusenabled' => false]);

            StrukOrder::where('norec', $getSoValueFalse->norec)
                ->where('kdprofile', $this->kdProfile)
                ->update([
                    'statusenabled' => false,
                    'isbatallis' => true
                ]);

            DB::commit();

            return $this->respond(['as' => '@epic'], 200, 'Sukses');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Hapus gagal: ' . $e->getMessage(), ['line' => $e->getLine()]);

            return $this->respond(['error' => $e->getMessage()], 400, 'Hapus Gagal');
        }
    }

    public function detailPetugasLab(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('pelayananpasienpetugas_t as pp')
            ->join('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'pp.objectjenispetugaspefk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pp.objectpegawaifk')
            ->select(
                'pp.norec',
                'pg.namalengkap',
                'jp.jenispetugaspe',
                'pp.objectpegawaifk',
                'pp.objectjenispetugaspefk',
                'pp.nomasukfk',
                'pp.pelayananpasien'
            )
            ->where('pp.pelayananpasien', $r['norec'])
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->get();

        return $this->respond($result);
    }

    public function savePetugasLab(Request $r)
    {
        DB::beginTransaction();
        try {
            if ($r['norec'] == '') {
                $log = 'Input ';
                $new_PPP = new PelayananPasienPetugas();
                $new_PPP->norec = $new_PPP->generateNewId();
                $new_PPP->kdprofile = $this->kdProfile;
                $new_PPP->statusenabled = true;
            } else {
                $new_PPP = PelayananPasienPetugas::where('norec', $r['norec'])->first();
                $log = 'Ubah ';
            }

            $new_PPP->nomasukfk = $r['nomasukfk'];
            $new_PPP->objectjenispetugaspefk = $r['objectjenispetugaspefk'];
            $new_PPP->objectpegawaifk = $r['objectpegawaifk'];
            $new_PPP->pelayananpasien = $r['pelayananpasien'];
            $new_PPP->noregistrasi = $r['noregistrasi'];
            $new_PPP->save();

            $pg = Pegawai::where('id', $r['objectpegawaifk'])->first();
            $ps = PasienDaftar::detailPasien($r['noregistrasi']);

            $this->LOGGING(
                $log . 'Petugas Tindakan',
                $new_PPP->norec,
                'pelayananpasienpetugas_t',
                $log . 'Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                    $r['noregistrasi']
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
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
                "result" => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deletePetugasLab(Request $r)
    {
        DB::beginTransaction();
        try {

            PelayananPasienPetugas::where('norec', $r['norec'])->delete();

            $ps = PasienDaftar::detailPasien($r['noregistrasi']);
            $pg = Pegawai::where('id', $r['objectpegawaifk'])->first();
            $this->LOGGING(
                'Hapus Petugas Tindakan',
                $r['norec'],
                'pelayananpasienpetugas_t',
                'Hapus Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . ' pada Pasien ' .
                    $r['noregistrasi']
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
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
                "result" => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function cetakbukti(Request $req)
    {
        $data = DB::table('labbukti_t as bt')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'bt.norec_pd_fk')
            ->leftjoin('pasien_m as ps', 'ps.norec', '=', 'pd.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pgw', 'pgw.id', '=', 'pd.objectpegawaifk')
            ->where('bt.noorderfk', $req->input('noorder'))
            ->where('bt.statusenabled', true)
            ->select(
                'bt.*',
                'ps.namapasien as nama_pasien',
                'ps.tgllahir as tanggal_lahir',
                'ps.nocm as no_cm',
                'jk.jeniskelamin',
                'pgw.namalengkap'

            )
            ->get();

        $pageWidth = 950;
        $judul = "Cetak Bukti";


        $res['pdf'] = true;

        if ($res['pdf'] === true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('a4', 'portrait');
            $pdf->loadView(
                'report.emr.cetakanLab',
                compact('data', 'judul', 'pageWidth', 'res')
            );
            return $pdf->stream();
        }

        // Return HTML view if not PDF
        return view('report.laboratorium.hasil-lab', compact('data', 'judul', 'pageWidth'));
    }

    public function saveDataBukti(Request $req)
    {
        DB::beginTransaction();
        $regis = $req['registrasi'];
        try {
            $new = new LabBuktiTransaksi();
            $new->norec = $new->generateNewId();
            $new->statusenabled = true;
            $new->norec_pd_fk = $regis['norec_pd'];
            $new->norec_apd_fk = $req['NOREC_APD'];
            $new->ascites = $req['ascites'];
            $new->ascites2 = $req['ascites2'];
            $new->cpap = $req['cpap'];
            $new->empatcels = $req['empatcels'];
            $new->evd = $req['evd'];
            $new->harike = $req['harike'];
            $new->ivline = $req['ivline'];
            $new->klinis_diagnosis = $req['klinis_diagnosis'];
            $new->kuteterurine = $req['kuteterurine'];
            $new->lain1 = $req['lain1'];
            $new->lainnya3 = $req['lainnya3'];
            $new->lcs = $req['lcs'];
            $new->pagi = $req['pagi'];
            $new->sputanEET = $req['sputanEET'];
            $new->eet = $req['eet'];
            $new->cpap = $req['cpap'];
            $new->sisi2 = $req['sisi2'];
            $new->induksi = $req['induksi'];
            $new->dasarluka = $req['dasarluka'];
            $new->aspirasipus = $req['aspirasipus'];
            $new->pleura = $req['pleura'];
            $new->lp = $req['lp'];
            $new->midstream = $req['midstream'];
            $new->namaantibiotik = $req['namaantibiotik'];
            $new->namaantibiotik2 = $req['namaantibiotik2'];
            $new->noorderfk = $req['noorder'];
            $new->pengambilan1 = $req['pengambilan1'];
            $new->pengambilan2 = $req['pengambilan2'];
            $new->pengambilan3 = $req['pengambilan3'];
            $new->pengambilan4 = $req['pengambilan4'];
            $new->pengirimanspesimen = $req['pengirimanspesimen'];
            $new->penyimpanan = $req['penyimpanan'];
            $new->perikardium2 = $req['perikardium2'];
            $new->qvc = $req['qvc'];
            $new->sebelumab = $req['sebelumab'];
            $new->sewaktu = $req['sewaktu'];
            $new->sisi = $req['sisi'];
            $new->spesimen = $req['spesimen'];
            $new->sputumETT1 = $req['sputumETT1'];
            $new->sputumETT2 = $req['sputumETT2'];
            $new->sputumETT3 = $req['sputumETT3'];
            $new->supraPubik = $req['supraPubik'];
            $new->tempatEndokarditis = $req['tempatEndokarditis'];
            $new->terapiab = $req['terapiab'];
            $new->urineKateter = $req['urineKateter'];
            $new->vpshunt = $req['vpshunt'];
            $new->urine = $req['urine'];
            $new->ventilator = $req['ventilator'];
            $new->volspes = $req['volspes'];
            $new->wsd = $req['wsd'];
            $new->save();
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $new,

                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function LayananLabPerTindakan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $noregistrasi = $request['noregistrasi'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }
        if ($request['so_norec']) {
            $paramsPp = "AND tp.strukorderfk = '" . $request['so_norec'] . "'";
        }
        $profile = $this->profile();

        $ruangan = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
            ->select('ru.namaruangan')
            ->where('pd.noregistrasi', '=', $noregistrasi)
            ->get();

        $data = $this->indentitasCetak($kdProfile, $noregistrasi);
        $data->ruanganasal = $ruangan[0]->namaruangan;
        // return $data;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total, x.namaruangan
            FROM ( SELECT tp.tglpelayanan, ru.namaruangan, (select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah,
                   tp.hargajual as hargasatuan,CASE WHEN tp.hargadiscount IS NULL THEN 0 ELSE tp.hargadiscount END AS diskon,
                   CASE WHEN tp.jasa IS NULL THEN 0 ELSE tp.jasa END AS jasa
            FROM pelayananpasien_t AS tp
            LEFT JOIN produk_m AS pro ON tp.produkfk = pro.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.norec = tp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            WHERE tp.kdprofile = $kdProfile
            AND tp.statusenabled = true
            $paramsPp

            ) AS x
            ORDER BY x.tglpelayanan
        "));

        $pageWidth = 950;
        // $totalbayar = $data->totaldibayar;
        // $terbilang = $this->terbilang($totalbayar); //strtoupper($this->terbilang($totalbayar));

        $res = array(
            'pdf' => false,
            'indo' => true
        );
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'judul' => $request['ket'] == 'jenazah' ? "BUKTI LAYANAN JENAZAH" : "BUKTI LAYANAN LABORATORIUM",
            'header' => $data,
            'details' => $details,
        );
        // dd($dataReport);
        return view(
            'report.laboratorium.bukti-layanan-lab',
            compact('dataReport', 'res', 'pageWidth', 'profile')
        );
    }
    function indentitasCetak($kdProfile, $noregistrasi)
    {
        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,ps.tgllahir,to_char(ps.tgllahir, 'DD-MM-YYYY') as tglkelahiran,ps.namapasien, ps.noidentitas, ps.nohp,ps.tempatlahir as tempatlahir , ng.namanegara,
               pd.tglregistrasi,jk.reportdisplay AS jk,ru2.namaruangan AS ruanganperiksa,ru.namaruangan AS ruangakhir,
               ks.namakelas,ar.asalrujukan,ps.notelepon,CASE WHEN rek.namarekanan is null then '-' else rek.namarekanan END as namapenjamin,
               CASE WHEN kmr.namakamar is null then '-' else kmr.namakamar END as namakamar,alm.alamatlengkap,kp.kelompokpasien,pp.namalengkap AS dpjp
        FROM pasiendaftar_t AS pd
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.noregistrasifk = pd.norec
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
        LEFT JOIN kelas_m AS ks ON apdp.objectkelasfk = ks.id
        LEFT JOIN asalrujukan_m AS ar ON apdp.objectasalrujukanfk = ar.id
        left JOIN rekanan_m AS rek ON rek.id= pd.objectrekananfk
        left JOIN kamar_m as kmr on apdp.objectkamarfk=kmr.id
        INNER join ruangan_m  as ru2 on ru2.id=apdp.objectruanganfk
        LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
        LEFT JOIN negara_m as ng on ng.id = ps.objectnegarafk
        WHERE pd.kdprofile = $kdProfile  AND pd.noregistrasi = '$noregistrasi'
"))->first();
        return $data;
    }


    public function cetakHasilLab(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab = $this->settingFix('KdKomponenTarifJasaDokter');

        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }

        $profile = $this->profile();
        $data = $this->indentitasCetak($kdProfile, $noregistrasi);
        // return "disini";

        //dd($request['norec_apd']);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $noorder = explode(',', $request['noorder']);
        $norec_so = explode(',', $request['norec_so']);
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));

        //disini
        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
            // ->leftjoin('LISORDERS_LOG as ls', 'ls.ONO', '=', 'rh.ONO')
            ->whereIn('rh.ONO', $noorder)
            // ->select('rd.*', 'rh.CLINICIAN_NM', 'rh.VALIDATE_ON','ls.CLINICIAN')
            ->get('rd.*', 'rh.CLINICIAN_NM', 'rh.VALIDATE_ON');

        $dataRegis = DB::table('strukorder_t as so')
            ->join('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('pegawai_m as peg1', 'peg1.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as peg2', 'peg2.id', '=', 'ppp.objectpegawaifk')
            ->where('so.statusenabled', true)
            ->where('so.noorder', $noorder)
            // ->where('so.norec', $norec_so[0])
            ->select('so.norec as norec_so', 'ru1.namaruangan as ruanganasal', 'peg1.namalengkap as dokterpengirim', 'peg2.namalengkap as dokterpemeriksa')
            ->distinct()
            ->first();

        $hasilbrid = [];
        foreach ($dataBrid as $item) {
            // $clinician = isset($item->CLINICIAN) ? explode('^', $item->CLINICIAN) : '';
            $hasilbrid[] = [
                'namaproduk' => $item->ORDER_TESTNM,
                'namalengkap' => '-',
                'detailpemeriksaan' => $item->TEST_NM,
                'hasil' => $item->RESULT_VALUE,
                'test_group' => $item->TEST_GROUP,
                'tglOrder' => '-',
                'flag' => $item->FLAG,
                'nilaitext' => $item->REF_RANGE,
                // 'clinician' => $clinician != '' && count($clinician) > 0 ? $clinician[1] : ($clinician != '' ? $clinician[0] : ''),
                // 'diotorisasi' => strpos($item->VALIDATE_BY, '^'),
                // 'dokterdiperiksa' => strpos($item->RELEASE_BY,'^'),
                'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                // 'diotorisasi' => explode('^', $item->VALIDATE_BY)[1] ,
                // 'dokterdiperiksa' =>  explode('^', $item->RELEASE_BY)[1],
                'satuanstandar' => $item->UNIT,
                'analis' => $item->VALIDATE_BY,
                'tglhasil' => $this->formatTimestamp($item->VALIDATE_ON),
                'noorder' => $item->ONO,
                'dokterlab' => '-',
                'pegawaiverifikator' => '-',
                'nipdokterlab' => '-',
                'nippegawaiverifikator' => '-',
                'ruanganasal' => '-',
                'catatanklinis' => $item->TEST_COMMENT,
                'metode' => $item->METHOD,
            ];
        };


        $stringQR = 'Hasil Laboratorium;' . "$data->nocm;" . "$data->namapasien;" . "$data->tglregistrasi";
        $encryptQR = base64_encode($stringQR);
        $isLab = 'Lab';
        $pasien = [];
        $pasien['tte'] = route('dokumen.signature.hasillab') . '?key=' . $encryptQR . '&a=' . $isLab;
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);
        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);
        $qrcode = base64_encode($resultBarcode->getString());

        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($hasilbrid), FALSE),
            // 'details' => json_decode(json_encode($groupedData), FALSE),
            // 'details' => $groupedData,
            // 'diotorisasi'=>strpos($dataBrid[0]->VALIDATE_BY,'^'),
            // 'dokterdiperiksa'=>strpos($dataBrid[0]->RELEASE_BY,'^')

        );
        $res['pdf'] = true;
        $judul = 'Cetak Hasil Lab';
        $blade = 'report.laboratorium.hasil-lab';
        // return "disini";

        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'tte' => $qrcode,
                    'dataRegis' => $dataRegis
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.laboratorium.hasil-lab',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakHasilLabManual(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab = $this->settingFix('KdKomponenTarifJasaDokter');


        // start generate parameter kebutuhan save dokumen
        // if (isset($request['noregistrasi'])) {
        //     $registrasi = DB::table("pasiendaftar_t as pd")
        //         ->select("apd.norec")
        //         ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
        //         ->join('hasillaboratorium_t as hh', 'hh.noregistrasifk', '=', 'apd.norec')
        //         ->where('pd.statusenabled', true)
        //         ->where('pd.kdprofile', $kdProfile)
        //         ->first();

        //     if (empty($registrasi)) {
        //         $request['norec_apd'] = "";
        //         $request['norec'] = "";
        //     } else {
        //         $request['norec_apd'] = $registrasi->norec;
        //         $request['norec'] = "";
        //     }
        // }

        // end generate parameter kebutuhan save dokumen
        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }

        $profile = $this->profile();
        $data = $this->indentitasCetak($kdProfile, $noregistrasi);
        // return "disini";

        //dd($request['norec_apd']);
        $jenis = DB::table('pasien_m as ps')
            ->join('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.objectjeniskelaminfk',
            )
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->where('ps.statusenabled', true)
            ->first();
        if (!empty($jenis)) {

            $date = new \DateTime($jenis->tgllahir);
            $now = new \DateTime();
            $jenis->umur = $now->diff($date)->format("%a");
        }

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $noorder = explode(',', $request['noorder']);
        $norec_so = explode(',', $request['norec_so']);
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));
        // $dataBrid = DB::select(DB::raw("SELECT hl.detailpemeriksaan as  namaproduk, hl.detailpemeriksaan as detailpemeriksaan,so.tglorder, pg.namalengkap,
        // hl.hasil, hl.flag, hl.nilainormal as nilaitext, hl.satuan as satuanstandar, '' user_validasi, hl.tglhasil as tgl_hasil, hl.metode,so.noorder, hl.flag, so.catatanklinis,
        // ru.namaruangan as ruanganasal, pg1.namalengkap as dokterlab, pg2.namalengkap as pegawaiverifikator, pg1.nip as nipdokterlab, pg2.nip as nippegawaiverifikator
        // FROM hasillaboratorium_t as hl
        // inner join pelayananpasien_t as pp on pp.norec = hl.norecpelayanan
        // inner join antrianpasiendiperiksa_t as apd on hl.noregistrasifk = apd.norec
        // left JOIN strukorder_t so ON so.norec_apd = apd.norec
        // left JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
        // left join pelayananpasienpetugas_t as ppp on pp.norec = ppp.pelayananpasien
        // left JOIN pegawai_m AS pg1 ON pg1.id = ppp.objectpegawaifk
        // left JOIN pegawai_m AS pg2 ON pg2.id = ppp.pegawaiverifikatorfk
        // left JOIN ruangan_m AS ru ON ru.id = so.objectruanganfk
        // WHERE apd.norec = '$norec_apd'"));
        //dd($dataBrid[0]->dokterlab);

        //disini
        $dataBrid = DB::select(DB::raw("SELECT
            DISTINCT
            pp.noregistrasifk AS norec_apd,
            djp.detailjenisproduk,
            pp.produkfk,
            prd.namaproduk,
            pdl.detailpemeriksaan,
            hh.hasil,
            pdl.nourut,
            pdlm.rangemin AS nilaimin,
            pdlm.rangemax AS nilaimax,
            pdlm.refrange AS nilaitext,
            ss.satuanstandar,
            pdl.id AS iddetailproduk,
            -- hh.metode,
            prd.metode_lab,
            -- pp.norec AS norec_pp,
            --hh.metode,
           -- hh.norec AS norec_hasil,
            hh.keterangan,
            hh.flag,
            hh.catatan,
            pg.id as iddokter,
            pg.namalengkap as namadokter
        FROM
            pelayananpasien_t AS pp
            INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
            INNER JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
            LEFT JOIN produkdetaillaboratorium_m AS pdl ON pdl.produkfk = prd.id AND pdl.statusenabled = TRUE
            LEFT JOIN produkdetaillaboratoriumnilainormal_m AS pdlm ON pdlm.produkdetaillabfk = pdl.id
            AND pdlm.jeniskelaminfk = '$jenis->objectjeniskelaminfk'
            AND '$jenis->umur' between pdlm.ageminday and pdlm.agemaxday
            left JOIN hasillaboratorium_t AS hh ON hh.norecpelayanan = pp.norec
            AND hh.statusenabled = TRUE
            AND pp.noregistrasifk = hh.noregistrasifk
            AND pp.norec = hh.norecpelayanan
            AND hh.produkdetaillabfk = pdl.id
            LEFT JOIN satuanstandar_m AS ss ON ss.id = pdlm.satuanstandarfk
            LEFT JOIN pegawai_m AS pg ON pg.id = hh.pegawaifk::integer
        WHERE
            pp.noregistrasifk = '$request[norec_apd]'
            AND pp.kdprofile = $kdProfile
            AND pdl.statusenabled = TRUE
            AND pp.statusenabled = TRUE
            AND pp.produkfk in (
                $request[product]
            )
            order by prd.namaproduk, pdl.nourut
       -- ORDER BY
         --   djp.qdetailjenisproduk,
           --pdl.nourut 
           "));

        $tglhasillab = DB::table('hasillaboratorium_t')->select('tglhasil')->where('noregistrasifk', $norec_apd)->orderBy('tglhasil', 'asc')->first();

        $dataRegis = DB::table('strukorder_t as so')
            ->join('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('pegawai_m as peg1', 'peg1.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as peg2', 'peg2.id', '=', 'ppp.objectpegawaifk')
            ->leftJoin('pegawai_m as peg3', 'peg3.id', '=', 'ppp.pegawaiverifikatorfk')
            ->where('so.statusenabled', true)
            // ->where('so.noregistrasi', $noregistrasi)
            ->where('so.norec_apd', $norec_apd)
            ->whereNotNull('ppp.pegawaiverifikatorfk')
            // ->where('so.norec', $norec_so[0])
            ->select('so.norec as norec_so', 'so.noorder', 'so.tglverif as tglpengambilan', 'so.updated_at', 'ru1.namaruangan as ruanganasal', 'peg1.namalengkap as dokterpengirim', 'peg2.id as iddokterverif', 'peg2.namalengkap as dokterpemeriksa', 'peg2.nip as nippegawaiverifikator', 'peg3.id as idpegawaiverif', 'peg3.namalengkap as namapegawaiverif', 'peg3.nip as nipverifikator')
            // ->distinct()
            ->first();

        if (!$dataRegis || empty($dataRegis->iddokterverif)) {
            echo '
            <script language="javascript">
                window.alert("Input ulang dokter verif di halaman input manual hasil laboratorium tersebut.");
                window.close()
            </script>';
        }
        if (!$dataRegis || empty($dataRegis->idpegawaiverif)) {
            echo '
            <script language="javascript">
                window.alert("Input ulang pegawai verif di halaman input manual hasil laboratorium tersebut.");
                window.close()
            </script>';
        }

        $hasilbrid = [];
        foreach ($dataBrid as $datas) {
            $hasilbrid[] = [
                'namaproduk' => $datas->namaproduk,
                'detailpemeriksaan' => $datas->detailpemeriksaan,
                'hasil' => $datas->hasil,
                'flag' => $datas->flag,
                'noregistrasifk' => $datas->norec_apd,
                'produkdetaillabfk' => $datas->iddetailproduk,
                'produkfk' => $datas->produkfk,
                'nilaitext' => $datas->nilaitext,
                'satuanstandar' => $datas->satuanstandar,
                // 'norec_pp' => $datas->norec_pp,
                'nilaimin' => $datas->nilaimin,
                'nilaimax' => $datas->nilaimax,
                'metode' => $datas->metode_lab,
                'catatan' => $datas->catatan,
                'iddokter' => $datas->iddokter,
                'namadokter' => $datas->namadokter,
            ];
        };
        if (empty($hasilbrid)) {
            abort(404, 'data belum di input oleh lab');
        }


        $qrpegawaiverif = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$dataRegis->idpegawaiverif)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();
        $qrdokterverif = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$dataRegis->iddokterverif)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();

        // $qrcodedokterverif = base64_encode(QrCode::format('svg')->size(75)->generate($dataRegis->dokterpemeriksa));
        $qrcodepegawaiverif = base64_encode(QrCode::format('svg')->size(75)->generate($dataRegis->namapegawaiverif));


        $stringQR = 'Hasil Laboratorium;' . "$data->nocm;" . "$data->namapasien;" . "$data->tglregistrasi;" . "$dataRegis->noorder";
        $noorder = "$dataRegis->noorder";
        $encryptQR = base64_encode($noorder);
        $isLab = 'Lab';
        $pasien = [];
        $pasien['tte'] = route('dokumen.signature.hasillab') . '?key=' . $encryptQR . '&a=' . $isLab . '&no=' . $noorder;
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);
        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);
        $qrcode = base64_encode($resultBarcode->getString());


        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($hasilbrid), FALSE)
        );
        $res['pdf'] = true;
        $judul = 'Cetak Hasil Lab';
        $blade = 'report.laboratorium.hasil-lab-invitro';
        // return "disini";

        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper([0, 0, 595.28, 935.43], 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab-invitro",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'tglhasillab' => !empty($tglhasillab) ? $tglhasillab : null,
                    'res' => $res,
                    'tte' => $qrcode,
                    'ttepegawaiverif' => $qrcodepegawaiverif,
                    'ttedokterverif' => $qrcode,
                    'dataRegis' => $dataRegis
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.laboratorium.hasil-lab-invitro',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakHasilLabManualKlaim(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab = $this->settingFix('KdKomponenTarifJasaDokter');

        // end generate parameter kebutuhan save dokumen
        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }

        $profile = $this->profile();
        $data = $this->indentitasCetak($kdProfile, $noregistrasi);
        // return "disini";

        //dd($request['norec_apd']);
        $jenis = DB::table('pasien_m as ps')
            ->join('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.objectjeniskelaminfk',
            )
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->where('ps.statusenabled', true)
            ->first();
        if (!empty($jenis)) {

            $date = new \DateTime($jenis->tgllahir);
            $now = new \DateTime();
            $jenis->umur = $now->diff($date)->format("%a");
        }

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $noorder = explode(',', $request['noorder']);
        $norec_so = explode(',', $request['norec_so']);
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));

        //disini
        $dataBrid = DB::select(DB::raw("SELECT
            pp.noregistrasifk AS norec_apd,
            djp.detailjenisproduk,
            pp.produkfk,
            prd.namaproduk,
            pdl.detailpemeriksaan,
            hh.hasil,
            pdlm.rangemin AS nilaimin,
            pdlm.rangemax AS nilaimax,
            pdlm.refrange AS nilaitext,
            ss.satuanstandar,
            pdl.id AS iddetailproduk,
            -- hh.metode,
            prd.metode_lab,
            pp.norec AS norec_pp,
            hh.metode,
            hh.norec AS norec_hasil,
            hh.keterangan,
            hh.flag,
            hh.catatan,
            pg.id as iddokter,
            pg.namalengkap as namadokter
        FROM
            pelayananpasien_t AS pp
            INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = pp.noregistrasifk
            INNER JOIN pasiendaftar_t as pd on pd.norec = apd.noregistrasifk
            INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
            INNER JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
            LEFT JOIN produkdetaillaboratorium_m AS pdl ON pdl.produkfk = prd.id AND pdl.statusenabled = TRUE
            LEFT JOIN produkdetaillaboratoriumnilainormal_m AS pdlm ON pdlm.produkdetaillabfk = pdl.id
            AND pdlm.jeniskelaminfk = '$jenis->objectjeniskelaminfk'
            AND '$jenis->umur' between pdlm.ageminday and pdlm.agemaxday
            left JOIN hasillaboratorium_t AS hh ON hh.norecpelayanan = pp.norec
            AND hh.statusenabled = TRUE
            AND pp.noregistrasifk = hh.noregistrasifk
            AND pp.norec = hh.norecpelayanan
            AND hh.produkdetaillabfk = pdl.id
            LEFT JOIN satuanstandar_m AS ss ON ss.id = pdl.satuanstandarfk
            LEFT JOIN pegawai_m AS pg ON pg.id = hh.pegawaifk::integer
        WHERE
            pd.noregistrasi = '$request[noregistrasi]'
            AND pp.kdprofile = $kdProfile
            AND pdl.statusenabled = TRUE
            AND pp.statusenabled = TRUE
        ORDER BY
            djp.qdetailjenisproduk,
            pdl.nourut"));

        $dataRegis = DB::table('strukorder_t as so')
            ->join('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('pegawai_m as peg1', 'peg1.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as peg2', 'peg2.id', '=', 'ppp.objectpegawaifk')
            ->leftJoin('pegawai_m as peg3', 'peg3.id', '=', 'ppp.pegawaiverifikatorfk')
            ->where('so.statusenabled', true)
            // ->where('so.noregistrasi', $noregistrasi)
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->whereNotNull('ppp.pegawaiverifikatorfk')
            // ->where('so.norec', $norec_so[0])
            ->select('so.norec as norec_so', 'so.noorder', 'so.tglverif as tglpengambilan', 'so.updated_at', 'ru1.namaruangan as ruanganasal', 'peg1.namalengkap as dokterpengirim', 'peg2.id as iddokterverif', 'peg2.namalengkap as dokterpemeriksa', 'peg2.nip as nippegawaiverifikator', 'peg3.id as idpegawaiverif', 'peg3.namalengkap as namapegawaiverif', 'peg3.nip as nipverifikator')
            // ->distinct()
            ->first();

        $hasilbrid = [];
        foreach ($dataBrid as $datas) {
            $hasilbrid[] = [
                'namaproduk' => $datas->namaproduk,
                'detailpemeriksaan' => $datas->detailpemeriksaan,
                'hasil' => $datas->hasil,
                'flag' => $datas->flag,
                'noregistrasifk' => $datas->norec_apd,
                'produkdetaillabfk' => $datas->iddetailproduk,
                'produkfk' => $datas->produkfk,
                'nilaitext' => $datas->nilaitext,
                'satuanstandar' => $datas->satuanstandar,
                'norec_pp' => $datas->norec_pp,
                'nilaimin' => $datas->nilaimin,
                'nilaimax' => $datas->nilaimax,
                'metode' => $datas->metode_lab,
                'catatan' => $datas->catatan,
                'iddokter' => $datas->iddokter,
                'namadokter' => $datas->namadokter,
            ];
        };
        if (empty($hasilbrid)) {
            abort(404, 'data belum di input oleh lab');
        }

        $qrpegawaiverif = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$dataRegis->idpegawaiverif)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();
        $qrdokterverif = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$dataRegis->iddokterverif)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();

        $qrcodedokterverif = base64_encode(QrCode::format('svg')->size(75)->generate($dataRegis->dokterpemeriksa));
        $qrcodepegawaiverif = base64_encode(QrCode::format('svg')->size(75)->generate($dataRegis->namapegawaiverif));

        $stringQR = 'Hasil Laboratorium;' . "$data->nocm;" . "$data->namapasien;" . "$data->tglregistrasi";
        $encryptQR = base64_encode($stringQR);
        $isLab = 'Lab';
        $pasien = [];
        $pasien['tte'] = route('dokumen.signature.hasillab') . '?key=' . $encryptQR . '&a=' . $isLab;
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);
        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);
        $qrcode = base64_encode($resultBarcode->getString());

        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($hasilbrid), FALSE)
        );
        $res['pdf'] = true;
        $judul = 'Cetak Hasil Lab';
        $blade = 'report.laboratorium.hasil-lab-invitro-klaim';
        // return "disini";

        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper([0, 0, 595.28, 935.43], 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab-invitro-klaim",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'tte' => $qrcode,
                    'ttepegawaiverif' => $qrcodepegawaiverif,
                    'ttedokterverif' => $qrcodedokterverif,
                    'dataRegis' => $dataRegis
                )
            );
            return $pdf;
        }
        return view(
            'report.laboratorium.hasil-lab-invitro-klaim',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }



    public function cetakHasilLab2(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab = $this->settingFix('KdKomponenTarifJasaDokter');

        $showHIV = isset($request['showHIV']) && $request['showHIV'] == 'true' ? true : false;
        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }

        $profile = $this->profile();
        $data = $this->indentitasCetak($kdProfile, $noregistrasi);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $noorder = explode(',', $request['noorder']);
        $norec_so = explode(',', $request['norec_so']);
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));

        //disini
        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
            ->whereIn('rh.ONO', $noorder)
            ->where('rd.RESULT_VALUE', '!=', '!')
            ->where('rd.RESULT_FT', '!=', '!')
            ->orderBy('rd.DISP_SEQ')
            ->orderBy('rd.TEST_NM', 'desc')
            ->select(
                'rd.*',
                'rh.CLINICIAN_NM',
                'rh.COMMENT',
                'rh.clinician_info'
            )
            ->get();

        $nobilling = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->whereIn('rh.ONO', $noorder)
            ->select('rh.LNO')
            ->first();

        $dataRegis = DB::table('strukorder_t as so')
            ->join('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('pegawai_m as peg1', 'peg1.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as peg2', 'peg2.id', '=', 'ppp.objectpegawaifk')
            ->where('so.statusenabled', true)
            ->where('so.noorder', $noorder)
            ->select('so.norec as norec_so', 'so.noorder', 'ru1.namaruangan as ruanganasal', 'peg1.namalengkap as dokterpengirim', 'peg2.namalengkap as dokterpemeriksa', 'so.tglverif as tglpengambilan')
            ->distinct()
            ->first();


        $groupedData = [];

        foreach ($dataBrid as $item) {
            // Format data untuk tiap item
            $formattedItem = [
                'namaproduk' => $item->ORDER_TESTNM,
                'namalengkap' => '-',
                'detailpemeriksaan' => $item->TEST_NM,
                'hasil' => $item->RESULT_VALUE,
                'result_ft' => $item->RESULT_FT,
                'test_group' => $item->TEST_GROUP,
                'order_testnm' => $item->ORDER_TESTNM,
                'tglOrder' => '-',
                'flag' => $item->FLAG,
                'test_comment' => $item->TEST_COMMENT,
                'nilaitext' => $item->REF_RANGE,
                'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                'satuanstandar' => $item->UNIT,
                'analis' => $item->VALIDATE_BY,
                'tglhasil' => $this->formatTimestamp($item->VALIDATE_ON),
                // 'nobilling' => $item->LNO,
                'noorder' => $item->ONO,
                'dokterlab' => '-',
                'pegawaiverifikator' => '-',
                'nipdokterlab' => '-',
                'nippegawaiverifikator' => '-',
                'ruanganasal' => '-',
                'catatanklinis' => $item->TEST_COMMENT,
                'metode' => $item->METHOD,
                // 'comment' => $item->COMMENT ?? null,
                'comment' => $item->TEST_COMMENT ?? null,
            ];

            // Cek apakah item ini "Rhesus" atau "Golongan Darah"
            $specialDetail = in_array($item->TEST_NM, ['Rhesus', 'Golongan Darah']);

            // Kelompokkan berdasarkan test_group
            if ($specialDetail) {
                // Buat grup baru berdasarkan ORDER_TESTNM
                $groupKey = $item->ORDER_TESTNM;
                if (!isset($groupedData[$groupKey])) {
                    $groupedData[$groupKey] = [
                        'group' => $item->ORDER_TESTNM, // Pakai ORDER_TESTNM sebagai nama grup
                        'items' => [],
                        'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                        'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                    ];
                }
                $groupedData[$groupKey]['items'][] = $formattedItem;
            } else {
                // Normal group by TEST_GROUP
                $groupKey = $item->TEST_GROUP;
                if (!isset($groupedData[$groupKey])) {
                    $groupedData[$groupKey] = [
                        'group' => $groupKey,
                        'items' => [],
                        'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                        'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                    ];
                }
                $groupedData[$groupKey]['items'][] = $formattedItem;
            }
        }

        if (empty($groupedData)) {
            abort(404, 'data belum di input oleh lab');
        }

        $stringQR = 'Hasil Laboratorium;' . "$data->nocm;" . "$data->namapasien;" . "$data->tglregistrasi;" . "$dataRegis->noorder";
        $noorder = "$dataRegis->noorder";
        $encryptQR = base64_encode($noorder);
        $isLab = 'Lab';
        $pasien = [];
        $pasien['tte'] = route('dokumen.signature.hasillab') . '?key=' . $encryptQR . '&a=' . $isLab . '&no=' . $noorder;
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);
        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);
        $qrcode = base64_encode($resultBarcode->getString());
        $data->diagnosa = $dataBrid[0]->clinician_info;
        $data->tglpengambilan = $dataRegis->tglpengambilan;

        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($groupedData), false)
        );
        $res['pdf'] = true;
        $judul = 'Cetak Hasil Lab';
        $blade = 'report.laboratorium.hasil-lab-new';

        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab-new",
                array(
                    'showHIV' => $showHIV,
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'tte' => $qrcode,
                    'dataRegis' => $dataRegis,
                    'tglhasil' => $this->formatTimestamp($dataBrid[0]->VALIDATE_ON),
                    'FT' => $dataBrid[0]->DATA_TYP ?? null,
                    'dokterdiperiksa' => isset(explode('^', $dataBrid[0]->VALIDATE_BY)[1]) ? explode('^', $dataBrid[0]->VALIDATE_BY)[1] : explode('^', $dataBrid[0]->VALIDATE_BY)[0],
                    'diotorisasi' => isset(explode('^', $dataBrid[0]->RELEASE_BY)[1]) ? explode('^', $dataBrid[0]->RELEASE_BY)[1] : explode('^', $dataBrid[0]->RELEASE_BY)[0],
                    'nobilling' => isset($nobilling->LNO) ? $nobilling->LNO : $dataRegis->noorder
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.laboratorium.hasil-lab-new',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakHasilLab2Klaim(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab = $this->settingFix('KdKomponenTarifJasaDokter');

        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }

        $profile = $this->profile();
        $data = $this->indentitasCetak($kdProfile, $noregistrasi);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $norec_so = explode(',', $request['norec_so']);

        $order = DB::table('strukorder_t AS so')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pasien_m AS pm', 'pm.id', '=', 'so.nocmfk')
            ->where('pd.noregistrasi', '=', $request['noregistrasi'])
            ->where('so.keteranganorder', 'Order Laboratorium')
            ->where('so.objectruangantujuanfk', 335)
            ->where('pd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->select('so.noorder')
            ->get();


        $order = $order->toArray();

        $arr = [];
        for ($x = 0; $x < count($order); $x++) {
            $arr[$x] = $order[$x]->noorder;
        }

        $nobilling = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->whereIn('rh.ONO', $arr)
            ->select('rh.LNO')
            ->get();


        $dataRegis = DB::table('strukorder_t as so')
            ->leftJoin('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('pegawai_m as peg1', 'peg1.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as peg2', 'peg2.id', '=', 'ppp.objectpegawaifk')
            ->where('so.statusenabled', true)
            ->whereIn('so.noorder', $arr)
            ->select('so.norec as norec_so', 'so.noorder', 'ru1.namaruangan as ruanganasal', 'peg1.namalengkap as dokterpengirim', 'peg2.namalengkap as dokterpemeriksa')
            ->get();

        $daftar = DB::select(DB::raw("select pd.noregistrasi, ru.objectdepartemenfk
        from pasiendaftar_t as pd 
        inner join ruangan_m as ru on ru.id = pd.objectruanganlastfk
        where pd.noregistrasi = '$noregistrasi'"));

        if($daftar[0]->objectdepartemenfk != 16){
            $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
            ->whereIn('rh.ONO', $arr)
            ->where('rd.RESULT_VALUE', '!=', '!')
            ->orderByRaw("
                CASE 
                    WHEN rd.VALIDATE_ON != '' THEN 1
                    WHEN rd.VALIDATE_ON = '' THEN 2
                END ASC
            ")
            ->orderBy('rd.VALIDATE_ON', 'asc')
            ->orderByDesc('rd.ONO')
            ->get('rd.*', 'rh.CLINICIAN_NM', 'rh.VALIDATE_ON');
        } else{
            $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
            ->whereIn('rh.ONO', $arr)
            ->where('rd.RESULT_VALUE', '!=', '!')
            ->orderBy('rd.VALIDATE_ON', 'asc')
            ->orderByDesc('rd.ONO')
            ->get('rd.*', 'rh.CLINICIAN_NM', 'rh.VALIDATE_ON');
        }

        $groupedData = [];

        foreach ($dataBrid as $item) {
            // Format data untuk tiap item
            $formattedItem = [
                'namaproduk' => $item->ORDER_TESTNM,
                'namalengkap' => '-',
                'detailpemeriksaan' => $item->TEST_NM,
                'hasil' => $item->RESULT_VALUE,
                'test_group' => $item->TEST_GROUP,
                'order_testnm' => $item->ORDER_TESTNM,
                'tglOrder' => '-',
                'flag' => $item->FLAG,
                'nilaitext' => $item->REF_RANGE,
                'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                'satuanstandar' => $item->UNIT,
                'analis' => $item->VALIDATE_BY,
                'tglhasil' => $this->formatTimestamp($item->VALIDATE_ON),
                'noorder' => $item->ONO,
                'dokterlab' => '-',
                'pegawaiverifikator' => '-',
                'nipdokterlab' => '-',
                'nippegawaiverifikator' => '-',
                'ruanganasal' => '-',
                'catatanklinis' => $item->TEST_COMMENT,
                'metode' => $item->METHOD,
            ];

            $matchingDataRegis = $dataRegis->firstWhere('noorder', $item->ONO);

            if ($matchingDataRegis) {
                // Cek apakah item ini "Rhesus" atau "Golongan Darah"
                $specialDetail = in_array($item->TEST_NM, ['Rhesus', 'Golongan Darah']);
                $groupKey = $item->ONO;
                $testGroup = $item->TEST_GROUP;

                if (!isset($groupedData[$groupKey])) {
                    $groupedData[$groupKey] = [
                        'ONO' => $groupKey,
                        'dokter_pengirim' => $matchingDataRegis->dokterpengirim,
                        'ruanganasal' => $matchingDataRegis->ruanganasal,
                        'dokter_pemeriksa' => $matchingDataRegis->dokterpemeriksa,
                        'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                        'tglhasil' => $this->formatTimestamp($item->VALIDATE_ON),
                        'groups' => []
                    ];
                }

                if ($specialDetail) {
                    $customGroupKey = $item->ORDER_TESTNM;
                    if (!isset($groupedData[$groupKey]['groups'][$customGroupKey])) {
                        $groupedData[$groupKey]['groups'][$customGroupKey] = [
                            'test_group' => $item->ORDER_TESTNM,
                            'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                            'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                            'items' => []
                        ];
                    }
                    $groupedData[$groupKey]['groups'][$customGroupKey]['items'][] = $formattedItem;
                } else {
                    if (!isset($groupedData[$groupKey]['groups'][$testGroup])) {
                        $groupedData[$groupKey]['groups'][$testGroup] = [
                            'test_group' => $testGroup,
                            'diotorisasi' => $formattedItem['diotorisasi'],
                            'dokterdiperiksa' => $formattedItem['dokterdiperiksa'],
                            'items' => []
                        ];
                    }
                }

                $groupedData[$groupKey]['groups'][$testGroup]['items'][] = $formattedItem;
            }
        }

        if (empty($groupedData)) {
            abort(404, 'data belum di input oleh lab');
        }

        $stringQR = 'Hasil Laboratorium;' . "$data->nocm;" . "$data->namapasien;" . "$data->tglregistrasi";
        $encryptQR = base64_encode($stringQR);
        $isLab = 'Lab';
        $pasien = [];
        $pasien['tte'] = route('dokumen.signature.hasillab') . '?key=' . $encryptQR . '&a=' . $isLab;
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);
        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);
        $qrcode = base64_encode($resultBarcode->getString());

        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Dokumen Klaim Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($groupedData), false)

        );
        $res['pdf'] = true;
        $judul = 'Dokumen Klaim';
        $blade = 'report.laboratorium.hasil-lab-klaim-new';

        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab-klaim-new",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'tte' => $qrcode,
                    'daftar' => $daftar,
                    'tglhasil' => $this->formatTimestamp($dataBrid[0]->VALIDATE_ON),
                    'dokterdiperiksa' => isset(explode('^', $dataBrid[0]->VALIDATE_BY)[1]) ? explode('^', $dataBrid[0]->VALIDATE_BY)[1] : explode('^', $dataBrid[0]->VALIDATE_BY)[0],
                    'diotorisasi' => isset(explode('^', $dataBrid[0]->VALIDATE_BY)[1]) ? explode('^', $dataBrid[0]->VALIDATE_BY)[1] : explode('^', $dataBrid[0]->VALIDATE_BY)[0]
                )
            );
            return $pdf;
        }
        return view(
            'report.laboratorium.hasil-lab-klaim-new',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakHasilLab2KlaimMikro(Request $request)
    {
        $order = DB::table('strukorder_t AS so')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            // ->leftjoin('pasien_m AS pm', 'pm.id', '=', 'so.nocmfk')
            ->where('so.noregistrasi', '=', $request['noregistrasi'])
            // ->where('so.keteranganorder', 'Order Laboratorium')
            ->where('so.objectruangantujuanfk', 337)
            ->where('pd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->select('so.noorder')
            ->distinct()
            ->get();

        $noregis = DB::table('pasiendaftar_t')->where('noregistrasi', $request['noregistrasi'])->select('noregistrasi')->first();

        $arr = $order->pluck('noorder')->filter()->unique()->values()->toArray();

        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab = $this->settingFix('KdKomponenTarifJasaDokter');
        $user = $request['user'];

        $profile = $this->profile();
        $data = $this->indentitasCetak($kdProfile, $noregis->noregistrasi);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');

        // Ambil data registrasi
        $dataRegis = DB::table('strukorder_t as so')
            ->leftJoin('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('pegawai_m as peg1', 'peg1.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as peg2', 'peg2.id', '=', 'ppp.objectpegawaifk')
            ->where('so.statusenabled', true)
            ->where('so.noorder', $arr[0])
            ->select(
                'so.norec as norec_so',
                'so.noorder',
                'ru1.namaruangan as ruanganasal',
                'peg1.namalengkap as dokterpengirim',
                'peg2.namalengkap as dokterpemeriksa'
            )
            ->distinct()
            ->first();

        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->leftJoin('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
            ->whereIn('rd.ONO', $arr)
            ->select('rd.*', 'rh.CLINICIAN_NM', 'rh.COMMENT')
            ->get();

        $groupedData = [];

        foreach ($dataBrid as $item) {
            $formattedItem = [
                'hasil' => $item->RESULT_FT ?? '-',
                'comment' => $item->COMMENT,
            ];

            $groupKey = $item->TEST_GROUP ?? '';
            $tesnama = $item->ORDER_TESTNM ?? '';

            $validateParts = isset($item->VALIDATE_BY) ? explode('^', $item->VALIDATE_BY) : ['-'];
            $releaseParts  = isset($item->RELEASE_BY) ? explode('^', $item->RELEASE_BY) : ['-'];

            $diotorisasi = $validateParts[1] ?? $validateParts[0];
            $dokterDiperiksa = $releaseParts[1] ?? $releaseParts[0];

            if (!isset($groupedData[$groupKey])) {
                $groupedData[$groupKey] = [
                    'namates' => $tesnama,
                    'group' => $groupKey,
                    'items' => [],
                    'diotorisasi' => $diotorisasi,
                    'dokterdiperiksa' => $dokterDiperiksa,
                ];
            }

            $groupedData[$groupKey]['items'][] = $formattedItem;
        }

        if (count($groupedData) == 0) {
            abort(404, 'Data belum diinput oleh lab.');
        }

        $stringQR = 'Hasil Laboratorium;' . "$data->nocm;" . "$data->namapasien;" . "$data->tglregistrasi";
        $encryptQR = base64_encode($stringQR);
        $pasien['tte'] = route('dokumen.signature.hasillab') . '?key=' . $encryptQR . '&a=Lab';

        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);
        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);
        $qrcode = base64_encode($resultBarcode->getString());

        $pageWidth = 950;

        $dataReport = [
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Dokumen Klaim Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($groupedData), false),
        ];

        // === Generate PDF & Download ===
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'portrait');
        $pdf->loadView('report.laboratorium.hasil-lab-culture-new', [
            'dataReport' => $dataReport,
            'pageWidth' => $pageWidth,
            'res' => ['pdf' => true],
            'tte' => $qrcode,
            'dataRegis' => $dataRegis,
            'tglhasil' => $this->formatTimestamp($dataBrid[0]->VALIDATE_ON ?? now()),
            'dokterdiperiksa' => $releaseParts[1] ?? $releaseParts[0],
            'diotorisasi' => $validateParts[1] ?? $validateParts[0],
        ]);

        return $pdf;
    }



    public function cetakHasilLabCulture(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab = $this->settingFix('KdKomponenTarifJasaDokter');
        // end generate parameter kebutuhan save dokumen
        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }

        $profile = $this->profile();
        $data = $this->indentitasCetak($kdProfile, $noregistrasi);


        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $noorder = explode(',', $request['noorder']);
        $norec_so = explode(',', $request['norec_so']);
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));

        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
            ->whereIn('rh.ONO', $noorder)
            ->select('rd.*', 'rh.CLINICIAN_NM', 'rh.COMMENT', 'rh.clinician_info')
            ->get();

        $dataRegis = DB::table('strukorder_t as so')
            ->leftjoin('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('pegawai_m as peg1', 'peg1.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as peg2', 'peg2.id', '=', 'ppp.objectpegawaifk')
            ->where('so.statusenabled', true)
            ->where('so.noorder', $noorder)
            ->select('so.norec as norec_so', 'so.noorder', 'ru1.namaruangan as ruanganasal', 'peg1.namalengkap as dokterpengirim', 'peg2.namalengkap as dokterpemeriksa', 'so.tglverif as tglpengambilan')
            ->distinct()
            ->first();


        $groupedData = [];

        foreach ($dataBrid as $item) {
            // Format data untuk tiap item
            $formattedItem = [
                'namaproduk' => $item->ORDER_TESTNM,
                'namalengkap' => '-',
                'detailpemeriksaan' => $item->TEST_NM,
                'hasil' => $item->RESULT_FT,
                // 'comment' => $item->COMMENT,
                'comment' => $item->TEST_COMMENT ?? null,
                'test_group' => $item->TEST_GROUP,
                'tglOrder' => '-',
                'flag' => $item->FLAG,
                'nilaitext' => $item->REF_RANGE ?? '-',
                'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                'satuanstandar' => $item->UNIT ?? '-',
                'analis' => $item->VALIDATE_BY,
                'tglhasil' => $this->formatTimestamp($item->VALIDATE_ON),
                'noorder' => $item->ONO,
                'dokterlab' => '-',
                'pegawaiverifikator' => '-',
                'nipdokterlab' => '-',
                'nippegawaiverifikator' => '-',
                'ruanganasal' => '-',
                'catatanklinis' => $item->TEST_COMMENT ?? '-',
                'metode' => $item->METHOD,
            ];

            // Kelompokkan berdasarkan test_group
            $groupKey = $item->TEST_GROUP;
            $tesnama = $item->ORDER_TESTNM;
            if (!isset($groupedData[$groupKey])) {
                $groupedData[$groupKey] = [
                    'namates' => $tesnama,
                    'group' => $groupKey,
                    'items' => [],
                    'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                    'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                ];
            }

            $groupedData[$groupKey]['items'][] = $formattedItem;
        }

        if (empty($groupedData)) {
            abort(404, 'data belum di input oleh lab');
        }



        $stringQR = 'Hasil Laboratorium;' . "$data->nocm;" . "$data->namapasien;" . "$data->tglregistrasi";
        $encryptQR = base64_encode($stringQR);
        $isLab = 'Lab';
        $pasien = [];
        $pasien['tte'] = route('dokumen.signature.hasillab') . '?key=' . $encryptQR . '&a=' . $isLab;
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);
        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);
        $qrcode = base64_encode($resultBarcode->getString());
        $data->diagnosa = $dataBrid[0]->clinician_info;
        $data->tglpengambilan = $dataRegis->tglpengambilan;

        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($groupedData), false),
        );
        $res['pdf'] = true;
        $judul = 'Cetak Hasil Lab kultur';
        $blade = 'report.laboratorium.hasil-lab-culture-new';

        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab-culture-new",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'tte' => $qrcode,
                    'dataRegis' => $dataRegis,
                    'tglhasil' => $this->formatTimestamp($dataBrid[0]->VALIDATE_ON),
                    'dokterdiperiksa' => isset(explode('^', $dataBrid[0]->VALIDATE_BY)[1]) ? explode('^', $dataBrid[0]->VALIDATE_BY)[1] : explode('^', $dataBrid[0]->VALIDATE_BY)[0],
                    'diotorisasi' => isset(explode('^', $dataBrid[0]->VALIDATE_BY)[1]) ? explode('^', $dataBrid[0]->VALIDATE_BY)[1] : explode('^', $dataBrid[0]->VALIDATE_BY)[0]
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.laboratorium.hasil-lab-culture-new',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }


    public function listPasienLab(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->select(
                'pd.norec as norec_pd',
                'pd.statusenabled',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'pg.namalengkap as namadokter',
                'pd.tglpulang',
                'pd.statuspasien',
                'pd.objectpegawaifk as pgid',
                'pd.objectruanganlastfk',
                'pd.nostruklastfk',
                'kls.namakelas',
                'ps.tgllahir',
                'ru.objectdepartemenfk',
                'pd.objectkelasfk',
                'ps.nobpjs',
                'jk.jeniskelamin',
                // 'apd.norec as norec_apd',
                'ps.noidentitas',
                'kbs.name as kebangsaan',
                DB::raw("CAST(pd.tglregistrasi
                AS DATE),
                (case when pd.ispelayananpasien=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk")
            )

            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true);


        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=', $r['ruanganid']);
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=', $r['nocm']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }

        $total = $data->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->get();

        foreach ($data as $d) {
            $d->umur = $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';
        }
        $res['data'] = $data;
        $res['total'] = $total;
        return $this->respond($res);
    }

    public function saveTransaksi(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $idKelasRadLab = (int) $this->settingFix('kdKelasLabRad');

            $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $r_NewAPD['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 00:00')
                ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 23:59')
                ->count();

            $noAntrian = $countNoAntrian + 1;

            PasienDaftar::where('norec', $r_NewPD['norec_pd'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'objectkelasfk' => $idKelasRadLab
                    ]
                );

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            $dataAPD->statusenabled = true;
            $dataAPD->objectkelasfk = $r_NewPD['objectkelasfk'];
            $dataAPD->noantrian = $noAntrian;
            $dataAPD->noregistrasifk = $r_NewPD['norec_pd'];
            // $dataAPD->objectpegawaifk = $r_NewPD['dokterfk'];
            $dataAPD->objectruanganfk = $r_NewAPD['objectruangantujuanfk'];
            $dataAPD->statusantrian = 0;
            $dataAPD->noregistrasi = $r_NewPD['noregiskun'];
            $dataAPD->statuspasien = 1;
            $dataAPD->statuskunjungan = 'LAMA';
            $dataAPD->statuspenyakit = 'BARU';
            $dataAPD->tglregistrasi = $r_NewPD['tglregistrasi'];
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->save();

            $this->LOGGING(
                'Registrasi Transaksi Pelayanan',
                $r_NewPD['norec_pd'],
                'pasiendaftar_t',
                ' pada Pasien ' .
                    $r_NewPD['norec_pd'] . 'ke' . $r_NewAPD['objectruangantujuanfk']
            );



            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Berhasil',
                "result" => $dataAPD,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result" => $e->getMessage() . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function getExpertise(Request $r) {}

    public function cetakEkspertiseEcho(Request $r)
    {
        $data = DB::table('hasilpemeriksaanlab_t as ar')
            ->leftJoin('pelayananpasien_t as pp', 'pp.norec', '=', 'ar.pelayananpasienfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'ar.pegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'pd.objectpegawaifk')
            ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as kl', 'kl.id', 'pd.objectkelasfk')
            ->where('ar.pelayananpasienfk', $r->pelayananpasienfk)
            ->select(
                'ar.noregistrasifk as hasilpemerikasaan',
                'pp.noregistrasifk as pelayanan_pasien',
                'apd.noregistrasifk as antrianpasiendiperiksa',
                'pp.norec as pasiendaftar',
                'pd.noregistrasi',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'ps.tgllahir',
                'alm.alamatlengkap as alamatlengkap',
                'kp.kelompokpasien',
                'ar.tanggal as tanggalpemeriksaan',
                'pg1.namaexternal  as dokterpengirim',
                'pg2.namaexternal  as penanggungjawab',
                'ar.diagnosaklinik as diagnosa',
                'ar.diagnosaklinik',
                'ar.morfologi',
                'ar.diagnosapb',
                'ar.jenis',
                'ar.makroskopik',
                'ar.mikroskopik',
                'ar.kesimpulan',
                'ar.anjuran',
                'kl.namakelas'
            )
            ->orderBy('ar.tanggal', 'DESC')
            ->first();
        $blade = "report.laboratorium.expertise";
        $profile = $this->getProfile();
        $pageWidth = 950;
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.laboratorium.expertise',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'data' => $data,
                    'profile' => $profile

                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view($blade, compact('pageWidth', 'data', 'res', 'profile'));
        }
    }

    public function hasilLab(Request $request)
    {

        $dataHasil = DB::table('lab_hasil as hl')
            ->join('strukorder_t as so', 'so.noorder', 'hl.no_order')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->select(DB::raw("TO_CHAR(hl.tgl_hasil, 'DD/MM/YYYY HH24:MI:SS') ||'  '|| hl.nama_pemeriksaan ||'  '|| hl.hasil ||'  '|| 'Normal : ' || hl.normal || '\n' as hasilLab"))
            ->where('hl.no_registrasi', $request['noregistrasi'])
            ->where('hl.hasil', '!=', '');
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataHasil = $dataHasil->whereIn('hl.no_order', explode(',', $request['noorder']));
        }
        if (isset($request['nourut']) && $request['nourut'] != '') {
            $dataHasil = $dataHasil->whereIn('hl.no_urut', explode(',', $request['nourut']));
        }
        $dataHasil = $dataHasil->get();

        return $this->respond($dataHasil);
    }

    public function sourceHasilLab(Request $request)
    {
        $dataHasil = DB::table('lab_hasil as hl')
            ->join('strukorder_t as so', 'so.noorder', 'hl.no_order')
            // ->leftJoin(DB::raw("produk_m AS prd ON cast(prd.id as text) = hl.kode_sir"))
            ->leftJoin('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->select('hl.nama_pemeriksaan', 'hl.tgl_hasil', 'hl.normal', 'hl.no_order', 'hl.no_urut', 'hl.hasil')
            ->where('hl.no_registrasi', $request['noregistrasi'])
            ->where('hl.hasil', '!=', '')
            ->get();

        return $this->respond($dataHasil);
    }
    public function saveHasilLabPCR(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == "") {
                $hasilPcr = new HasilPemeriksaanPcr();
                $hasilPcr->norec = $hasilPcr->generateNewId();
                $hasilPcr->kdprofile = $kdProfile;
                $hasilPcr->statusenabled = true;
            } else {
                $hasilPcr = HasilPemeriksaanPcr::where('pelayananpasienfk', $request['pelayananpasienfk'])->orWhere('norec', $request['norec'])->first();
            }
            $hasilPcr->nopemerikasaan = $request['nopemerikasaan'];
            $hasilPcr->objectruanganfk = $request['objectruanganfk'];
            $hasilPcr->jenisspesimenfk = $request['jenisspesimenfk'];
            $hasilPcr->metodeperiksafk = $request['metodeperiksafk'];
            $hasilPcr->tglterimasampel = $request['tglterimasampel'];
            $hasilPcr->tglselesaisampel = $request['tglselesaisampel'];
            $hasilPcr->kodesampel = $request['kodesampel'];
            $hasilPcr->sample = $request['sample'];
            $hasilPcr->hasil = $request['hasil'];
            $hasilPcr->keterangan = $request['keterangan'];
            $hasilPcr->keperluaan = $request['keperluaan'];
            $hasilPcr->keadaan = $request['keadaan'];
            $hasilPcr->keluarkota = $request['keluarkota'];
            $hasilPcr->kotanegara = $request['kotanegara'];
            $hasilPcr->petugasfk = $request['petugasfk'];
            $hasilPcr->petugasverifikatorfk = $request['petugasverifikatorfk'];
            $hasilPcr->petuggasapprovalfk = $request['petuggasapprovalfk'];
            $hasilPcr->pelayananpasienfk = $request['pelayananpasienfk'];
            $hasilPcr->save();
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
                    "data" => $hasilPcr,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getHasilPemeriksaanPcr(Request $request)
    {
        $data = DB::table('hasillabpcr_t as pc')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', 'pc.petugasfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', 'pc.petugasverifikatorfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', 'pc.petuggasapprovalfk')
            ->leftJoin('ruangan_m as rm', 'rm.id', 'pc.objectruanganfk')
            ->leftJoin('metodepemeriksanpcr_m as mp', 'mp.id', 'pc.metodeperiksafk')
            ->leftJoin('spesimenpcr_m as js', 'js.id', 'pc.jenisspesimenfk')
            ->select(
                'pc.*',
                'pg1.id as idpegawaipemeriksa',
                'pg1.namalengkap as pegawaipemeriksa',
                'pg2.id as idpegawaiverifikator',
                'pg2.namalengkap as pegawaiverifikator',
                'rm.id as idruangan',
                'rm.namaruangan',
                'mp.id as idmetodeperiksa',
                'mp.namametodeperiksa',
                'js.id as idjenisspesimen',
                'js.namajenisspesimen as namajenisspesimen',
                'pg3.id as idpetugasapproval',
                'pg3.namalengkap as namapetuggasapprovalfk'
            )
            ->where('pc.kdprofile', $this->kdProfile)
            ->where('pc.statusenabled', true)
            ->where('pc.pelayananpasienfk', $request['norec_pp'])
            ->first();

        return $this->respond($data);
    }

    public function saveHasilLabMikro(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == "") {
                $hasilMikro = new HasilPemeriksaanMikro();
                $hasilMikro->norec = $hasilMikro->generateNewId();
                $hasilMikro->kdprofile = $kdProfile;
                $hasilMikro->statusenabled = true;
            } else {
                $hasilMikro = HasilPemeriksaanMikro::where('pelayananpasienfk', $request['pelayananpasienfk'])->orWhere('norec', $request['norec'])->first();
            }
            $hasilMikro->kodespesimen = $request['kodespesimen'];
            $hasilMikro->tglterimaspesimen = $request['tglterimaspesimen'];
            $hasilMikro->tgldikerjakanspesimen = $request['tgldikerjakanspesimen'];
            $hasilMikro->tglkeluarhasil = $request['tglkeluarhasil'];
            $hasilMikro->jenisspesimen = $request['jenisspesimen'];
            $hasilMikro->asalspesimen = $request['asalspesimen'];
            $hasilMikro->spesimenke = $request['spesimenke'];
            $hasilMikro->hasilspesimen = $request['hasilspesimen'];
            $hasilMikro->namajenisspesimen = $request['namajenisspesimen'];
            $hasilMikro->namaasalspesimen = $request['namaasalspesimen'];
            $hasilMikro->dokterpemeriksafk = $request['dokterpemeriksafk'];
            $hasilMikro->pmn = $request['pmn'];
            $hasilMikro->gpc = $request['gpc'];
            $hasilMikro->gpr = $request['gpr'];
            $hasilMikro->gnr = $request['gnr'];
            $hasilMikro->gndc = $request['gndc'];
            $hasilMikro->sec = $request['sec'];
            $hasilMikro->gncb = $request['gncb'];
            $hasilMikro->ycphh = $request['ycphh'];
            $hasilMikro->note = $request['note'];
            $hasilMikro->pemeriksaan = $request['pemeriksaan'];
            $hasilMikro->pemeriksaanpenunjang = $request['pemeriksaanpenunjang'];
            $hasilMikro->bas = $request['bas'];
            $hasilMikro->eos = $request['eos'];
            $hasilMikro->bat = $request['bat'];
            $hasilMikro->seg = $request['seg'];
            $hasilMikro->limf = $request['limf'];
            $hasilMikro->sun = $request['sun'];
            $hasilMikro->antibiotik = $request['antibiotik'];
            $hasilMikro->tglkultur = $request['tglkultur'];
            $hasilMikro->observasikultur = $request['observasikultur'];
            $hasilMikro->pemeriksakultur = $request['pemeriksakultur'];
            $hasilMikro->namapemeriksakultur = $request['namapemeriksakultur'];
            $hasilMikro->hasilakhirkultur = $request['hasilakhirkultur'];
            $hasilMikro->hasilujikepekaan = $request['hasilujikepekaan'];
            $hasilMikro->nocmfk = $request['nocmfk'];
            $hasilMikro->norec_pd = $request['norec_pd'];
            $hasilMikro->norec_apd = $request['norec_apd'];
            $hasilMikro->pelayananpasienfk = $request['pelayananpasienfk'];
            $hasilMikro->save();
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
                    "data" => $hasilMikro,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getHasilPemeriksaanMikro(Request $request)
    {
        $data = DB::table('hasilmikro_t as pc')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', 'pc.pemeriksakultur')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', 'pc.dokterpemeriksafk')
            ->select(
                'pc.*',
                'pg1.id as pemeriksakultur',
                'pg2.namalengkap',
            )
            ->where('pc.kdprofile', $this->kdProfile)
            ->where('pc.statusenabled', true)
            ->where('pc.pelayananpasienfk', $request['norec_pp'])
            ->first();

        return $this->respond($data);
    }

    public function getLaporanGlucotest(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $ruangan = $request->ruangan;
        $tglAwal = Carbon::parse($request['tglAwal'])->format('Y-m-d H:i') ?? date('Y-m-d H:i');
        $tglAkhir = Carbon::parse($request['tglAkhir'])->format('Y-m-d H:i') ?? date('Y-m-d H:i');
        $kelompokpasien = $request['kelompokpasien'] == "undefined" ? "" : $request['kelompokpasien'];
        $dokterdpjp = $request['dokterdpjp'] == "undefined" ? "" : $request['dokterdpjp'];
        $sDokterPemeriksa = $request['dokter'] == "undefined" ? "" : $request['dokter'];
        $rangeDate = [$tglAwal, $tglAkhir];
        $idProduk = 6715218;
        // return $rangeDate;
        try {
            $data = DB::table('pasiendaftar_t AS pd')
                ->select(
                    // 'apd.tanggal as test',
                    'pp.norec',
                    'pp.tglpelayanan',
                    'ps.nocm',
                    'pd.noregistrasi',
                    'ps.namapasien',
                    'pp.statusenabled',
                    'apd.objectruanganfk',
                    'jk.jeniskelamin',
                    // 'klp.kelompokpasien',
                    'pro.namaproduk',
                    'pp.jumlah',
                    'pp.hargajual',
                    'djp.detailjenisproduk',
                    // 'hr.tanggal as tglexpertise',
                    'pg.namalengkap AS dokterdpjp',
                    'ru1.namaruangan AS ruangan'
                )
                ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', 'pd.norec')
                ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
                ->join('pasien_m AS ps', 'ps.id', 'pd.nocmfk')
                ->join('jeniskelamin_m AS jk', 'jk.id', 'ps.objectjeniskelaminfk')
                ->join('ruangan_m AS  rg', 'rg.id', 'pd.objectruanganlastfk')
                ->leftJoin('produk_m AS pro', 'pro.id', 'pp.produkfk')
                ->join('detailjenisproduk_m as djp', 'djp.id', 'pro.objectdetailjenisprodukfk')
                ->leftJoin('strukorder_t AS so', 'so.norec', 'apd.objectstrukorderfk')
                ->leftJoin('ruangan_m AS ru1', 'ru1.id', 'apd.objectruanganfk')
                ->leftJoin('batalregistrasi_t AS  br', 'br.pasiendaftarfk', 'pd.norec')
                ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
                ->where('pd.kdprofile', $kdProfile)
                ->whereNotNull('pro.namaproduk')
                ->where('br.norec', null)
                ->when($ruangan, function ($query) use ($ruangan) {
                    return $query->where('ru1.id', $ruangan);
                })
                ->when($dokterdpjp, function ($query) use ($dokterdpjp) {
                    return $query->where('pg.id', $dokterdpjp);
                })
                ->where('pro.id', $idProduk);
            if (isset($request['tglAwal']) && isset($request['tglAkhir'])) {
                $data = $data->whereBetween(DB::raw("CAST(pp.tglregistrasi as DATE)"), $rangeDate);
            }
            $data = $data->get();
            $result = [
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            $result = [
                "status" => 400,
                "message" => $e->getMessage() . $e->getLine(),
                "data" => []
            ];
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function buktiMikro(Request $req)
    {
        $noorder = $req['noorder'];
        $data = DB::table('labbukti_t')->where('noorderfk', $noorder)->select('*')->first();
        return $this->respond($data);
    }

    public function laporanJenisPemeriksaan(Request $req)
    {
        $rangeDate = [
            $req->tglAwal,
            $req->tglAkhir . ' 23:59:59'
        ];

        $data = DB::table('pelayananpasien_t AS pp')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('produk_m AS pr', 'pr.id', '=', 'pp.produkfk')
            ->selectRaw('pr.namaproduk')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 1 THEN pp.jumlah ELSE 0 END) AS day_1')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 2 THEN pp.jumlah ELSE 0 END) AS day_2')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 3 THEN pp.jumlah ELSE 0 END) AS day_3')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 4 THEN pp.jumlah ELSE 0 END) AS day_4')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 5 THEN pp.jumlah ELSE 0 END) AS day_5')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 6 THEN pp.jumlah ELSE 0 END) AS day_6')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 7 THEN pp.jumlah ELSE 0 END) AS day_7')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 8 THEN pp.jumlah ELSE 0 END) AS day_8')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 9 THEN pp.jumlah ELSE 0 END) AS day_9')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 10 THEN pp.jumlah ELSE 0 END) AS day_10')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 11 THEN pp.jumlah ELSE 0 END) AS day_11')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 12 THEN pp.jumlah ELSE 0 END) AS day_12')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 13 THEN pp.jumlah ELSE 0 END) AS day_13')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 14 THEN pp.jumlah ELSE 0 END) AS day_14')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 15 THEN pp.jumlah ELSE 0 END) AS day_15')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 16 THEN pp.jumlah ELSE 0 END) AS day_16')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 17 THEN pp.jumlah ELSE 0 END) AS day_17')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 18 THEN pp.jumlah ELSE 0 END) AS day_18')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 19 THEN pp.jumlah ELSE 0 END) AS day_19')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 20 THEN pp.jumlah ELSE 0 END) AS day_20')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 21 THEN pp.jumlah ELSE 0 END) AS day_21')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 22 THEN pp.jumlah ELSE 0 END) AS day_22')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 23 THEN pp.jumlah ELSE 0 END) AS day_23')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 24 THEN pp.jumlah ELSE 0 END) AS day_24')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 25 THEN pp.jumlah ELSE 0 END) AS day_25')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 26 THEN pp.jumlah ELSE 0 END) AS day_26')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 27 THEN pp.jumlah ELSE 0 END) AS day_27')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 28 THEN pp.jumlah ELSE 0 END) AS day_28')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 29 THEN pp.jumlah ELSE 0 END) AS day_29')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 30 THEN pp.jumlah ELSE 0 END) AS day_30')
            ->selectRaw('SUM(CASE WHEN EXTRACT(DAY FROM pp.tglpelayanan) = 31 THEN pp.jumlah ELSE 0 END) AS day_31')
            // ->whereBetween('pp.tglpelayanan', [$startDate, $endDate])
            ->whereBetween('pp.tglpelayanan', $rangeDate)
            // ->whereIn('pr.objectdetailjenisprodukfk', [3063, 3087])
            ->where('apd.objectruanganfk', $req['ruangId'])
            ->whereIn('pr.objectdetailjenisprodukfk', explode(',', $this->settingFix('idJenisProdukLaborat')))
            // ->where('apd.objectruanganfk', 335)
            ->groupBy('pr.namaproduk')
            ->orderBy('pr.namaproduk')
            ->get();

        return $this->respond($data);
    }

    public function laporanDataRujukan(Request $req)
    {

        $startDate = $req['tglAwal'] . ' 00:00';
        $endDate = $req['tglAkhir'] . ' 23:59';

        $data = DB::table('pelayananpasien_t as pp')
            ->select([
                'pp.tglpelayanan as tanggal',
                'pd.norec',
                'pm.namapasien',
                'pg.namalengkap as dokterpengirim',
                'pr.namaproduk as jenispemeriksaan',
                'pm.nocm as RM',
                'kp.kelompokpasien as tanggungan',
                DB::raw('(pp.hargasatuan * pp.jumlah) as totalbiayapemeriksaan'),
                'pd.keteranganasalrujukan as keterangan',
                'ru.namaruangan',
                'pp.jumlah',
                'pp.hargasatuan',
                'so.noorder'
            ])
            ->join('pelayananpasienpetugas_t as ppt', 'pp.norec', '=', 'ppt.pelayananpasien')
            ->join('pegawai_m as pg', 'pg.id', '=', 'ppt.objectpegawaifk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as pm', 'pm.norec', '=', 'pd.nocmfk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->where('apd.objectruanganfk', $req['ruangId'])
            ->whereIn('pr.objectdetailjenisprodukfk', explode(',', $this->settingFix('idJenisProdukLaborat')))
            ->whereBetween('pp.tglpelayanan', [$startDate, $endDate])
            ->groupBy([
                'pp.tglpelayanan',
                'pd.norec',
                'pm.namapasien',
                'pg.namalengkap',
                'pr.namaproduk',
                'pp.hargasatuan',
                'pp.jumlah',
                'pd.keteranganasalrujukan',
                'ru.namaruangan',
                'pm.nocm',
                'kp.kelompokpasien',
                'pp.jumlah',
                'pp.hargasatuan',
                'so.noorder'
            ])
            ->distinct()
            ->get();

        return $this->respond($data);
    }

     public function laporanTransaksiOrderLaboratorium(Request $req)
    {

        $startDate = $req['tglAwal'] . ' 00:00';
        $endDate = $req['tglAkhir'] . ' 23:59';

        //WNA
        if (isset($req['type']) && $req['type'] == 'true') {

            $result = DB::table('pelayananpasien_t as pp')
            ->select([
                DB::raw("CASE 
                WHEN pp.norec is null  THEN 'Pending' 
                WHEN pp.norec is not null THEN 'Verifikasi' 
                ELSE 'Lainnya' 
                END as status_order_label"),
                'pp.norec as norec_pp',
                'so.statusorder',
                'so.objectruangantujuanfk',
                'so.cito',
                'ru.id as idruangan',
                'ru.namaruangan',
                'pgwm.namalengkap as petugasverifikator',
                'pr.id',
                'pr.namaproduk',
                'pp.jumlah as qtypemeriksaan',
                'pp.hargasatuan as total',
                'km.kelompokpasien',
                'kbm.name as type_pasien',
                'pm.namapasien',
                'pp.tglpelayanan',
                'pm.nocm'
                ])
                ->rightJoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
                ->leftJoin('orderpelayanan_t as op', 'op.noorderfk', '=', 'so.norec')
                ->leftJoin('antrianpasiendiperiksa_t as apd', function ($join) {
                    $join->on('apd.norec', '=', DB::raw("COALESCE(so.norec_apd,pp.noregistrasifk)"));
                })
                ->join('ruangan_m as ru', function ($join) {
                    $join->on('ru.id', '=', DB::raw('COALESCE(so.objectruanganfk, apd.objectruanganfk)'));
                })
                ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
                ->leftJoin('pegawai_m as pgwm', 'pgwm.id', '=', 'ppp.pegawaiverifikatorfk')
                ->leftJoin('kelompokpasien_m as km', 'km.id', '=', 'pd.objectkelompokpasienlastfk')
                ->leftJoin('pasien_m as pm', 'pm.norec', '=', 'pd.nocmfk')
                ->leftJoin('produk_m as pr', DB::raw('pr.id'), '=', DB::raw('COALESCE(pp.produkfk, op.objectprodukfk)'))
                ->leftJoin('kebangsaan_m as kbm', 'kbm.id', '=', 'pm.objectkebangsaanfk')
                ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
                ->where(function ($query) use ($req) {
                $query->where('apd.objectruanganfk', $req['ruangId'])
                ->orWhere('so.objectruangantujuanfk', $req['ruangId']);
                })
                ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('pp.tglpelayanan', [$startDate, $endDate])
                ->orWhereBetween('op.tglpelayanan', [$startDate, $endDate]);
                })
                ->whereIn('pr.objectdetailjenisprodukfk', explode(',', $this->settingFix('idJenisProdukLaborat')))
                ->where(function ($query) {
                $query->where('pp.statusenabled', true)
                ->orWhereNull('pp.statusenabled');
                })
                ->where(function ($query) {
                    $query->where('so.objectruangantujuanfk', '!=', 337)
                        ->orWhere(function ($query) {
                            $query->where('so.objectruangantujuanfk', 337)
                                    ->whereNotNull('pp.norec'); 
                        });
                })
                ->where(function ($query) {
                $query->where('apd.statusenabled', true)
                ->orWhereNull('apd.statusenabled');
                })
                ->where('pm.objectkebangsaanfk','!=', 1)
                ->groupBy([
                        'pr.id',
                        'pp.norec',
                        'pr.namaproduk',
                        'km.kelompokpasien',
                        'kbm.reportdisplay',
                        'pgwm.namalengkap',
                        'pp.jumlah',
                        'so.objectruangantujuanfk',
                        'pp.hargasatuan',
                        'kbm.name',
                        'ru.id',
                        'so.statusorder',
                        'so.cito',
                        'ru.namaruangan',
                        'pm.namapasien',
                        'pm.nocm',
                        'pp.tglpelayanan',
                    ])
                    ->orderBy('pm.namapasien', 'asc');
        }
        //WNI
        else {
            $result = DB::table('pelayananpasien_t as pp')
            ->select([
                DB::raw("CASE 
                WHEN pp.norec is null THEN 'Pending' 
                WHEN pp.norec is not null THEN 'Verifikasi' 
                ELSE 'Lainnya' 
                END as status_order_label"),
                'pp.norec as norec_pp',
                'so.statusorder',
                'so.cito',
                'ru.id as idruangan',
                'so.objectruangantujuanfk',
                'ru.namaruangan',
                'pgwm.namalengkap as petugasverifikator',
                'pr.id',
                'pr.namaproduk',
                'pp.jumlah as qtypemeriksaan',
                'pp.hargasatuan as total',
                // DB::raw('SUM(pp.jumlah) as qtypemeriksaan'),
                // DB::raw('SUM(pp.hargasatuan) as total'),
                'km.kelompokpasien',
                'kbm.name as type_pasien',
                'pm.namapasien',
                'pp.tglpelayanan',
                'pm.nocm'
                ])
                ->leftjoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
                ->leftJoin('orderpelayanan_t as op', 'op.noorderfk', '=', 'so.norec')
                ->leftJoin('antrianpasiendiperiksa_t as apd', function ($join) {
                    $join->on('apd.norec', '=', DB::raw("COALESCE(pp.noregistrasifk, so.norec_apd)"));
                })
                ->join('ruangan_m as ru', function ($join) {
                    $join->on('ru.id', '=', DB::raw('COALESCE(so.objectruanganfk, apd.objectruanganfk)'));
                })
                ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->leftJoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
                ->leftJoin('pegawai_m as pgwm', 'pgwm.id', '=', 'ppp.pegawaiverifikatorfk')
                ->leftJoin('kelompokpasien_m as km', 'km.id', '=', 'pd.objectkelompokpasienlastfk')
                ->leftJoin('pasien_m as pm', 'pm.norec', '=', 'pd.nocmfk')
                ->leftJoin('produk_m as pr', DB::raw('pr.id'), '=', DB::raw('COALESCE(pp.produkfk, op.objectprodukfk)'))
                ->leftJoin('kebangsaan_m as kbm', 'kbm.id', '=', 'pm.objectkebangsaanfk')
                ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
                ->where(function ($query) use ($req) {
                    $query->where('apd.objectruanganfk', $req['ruangId'])
                        ->orWhere('so.objectruangantujuanfk', $req['ruangId']);
                })
                ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('pp.tglpelayanan', [$startDate, $endDate])
                ->orWhereBetween('op.tglpelayanan', [$startDate, $endDate]);
                })
                ->whereIn('pr.objectdetailjenisprodukfk', explode(',', $this->settingFix('idJenisProdukLaborat')))
                ->where(function ($query) {
                $query->where('pp.statusenabled', true)
                ->orWhereNull('pp.statusenabled');
                })
                // ->where(function ($query) {
                //     $query->where('so.objectruangantujuanfk', '!=', 337)
                //         ->orWhere(function ($query) {
                //             $query->where('so.objectruangantujuanfk', 337)
                //                     ->whereNotNull('pp.norec'); 
                //         });
                // })
                ->where(function ($query) {
                $query->where('apd.statusenabled', true)
                ->orWhereNull('apd.statusenabled');
                })
                ->where('pm.objectkebangsaanfk', 1)
                ->groupBy([
                        'pr.id',
                        'pr.namaproduk',
                        'km.kelompokpasien',
                        'kbm.reportdisplay',
                        'ru.namaruangan',
                        'kbm.name',
                        'pp.jumlah',
                        'pp.norec',
                        'so.objectruangantujuanfk',
                        'pp.hargasatuan',
                        'pgwm.namalengkap',
                        'so.statusorder',
                        'so.cito',
                        'ru.id',
                        'pm.namapasien',
                        'pm.nocm',
                        'pp.tglpelayanan',
                    ])
                    ->orderBy('pm.namapasien', 'asc');
        }
        $result = $result->get();
        return $this->respond($result);
    }

    public function laporanKunjungan(Request $req)
    {

        $startDate = $req['tglAwal'] . ' 00:00';
        $endDate = $req['tglAkhir'] . ' 23:59';

        $query = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kelompokpasien_m as klm', 'pd.objectkelompokpasienlastfk', '=', 'klm.id')
            ->leftJoin('kebangsaan_m as kb', 'ps.objectkebangsaanfk', '=', 'kb.id')
            ->where('pd.statusenabled', true)
            ->where('apd.objectruanganfk', $req['ruangId'])
            ->where('apd.statusenabled', true)
            ->whereBetween('pd.tglregistrasi', [$startDate, $endDate])
            ->selectRaw('
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 1 THEN 1 END), 0) AS day_1,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 2 THEN 1 END), 0) AS day_2,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 3 THEN 1 END), 0) AS day_3,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 4 THEN 1 END), 0) AS day_4,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 5 THEN 1 END), 0) AS day_5,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 6 THEN 1 END), 0) AS day_6,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 7 THEN 1 END), 0) AS day_7,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 8 THEN 1 END), 0) AS day_8,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 9 THEN 1 END), 0) AS day_9,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 10 THEN 1 END), 0) AS day_10,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 11 THEN 1 END), 0) AS day_11,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 12 THEN 1 END), 0) AS day_12,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 13 THEN 1 END), 0) AS day_13,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 14 THEN 1 END), 0) AS day_14,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 15 THEN 1 END), 0) AS day_15,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 16 THEN 1 END), 0) AS day_16,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 17 THEN 1 END), 0) AS day_17,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 18 THEN 1 END), 0) AS day_18,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 19 THEN 1 END), 0) AS day_19,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 20 THEN 1 END), 0) AS day_20,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 21 THEN 1 END), 0) AS day_21,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 22 THEN 1 END), 0) AS day_22,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 23 THEN 1 END), 0) AS day_23,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 24 THEN 1 END), 0) AS day_24,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 25 THEN 1 END), 0) AS day_25,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 26 THEN 1 END), 0) AS day_26,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 27 THEN 1 END), 0) AS day_27,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 28 THEN 1 END), 0) AS day_28,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 29 THEN 1 END), 0) AS day_29,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 30 THEN 1 END), 0) AS day_30,
                COALESCE(COUNT(CASE WHEN EXTRACT(DAY FROM pd.tglregistrasi) = 31 THEN 1 END), 0) AS day_31,
                COALESCE(COUNT(pd.norec), 0) AS total_per_tipe_pasien,
                klm.kelompokpasien,
                kb.name AS tipe_pasien
            ')
            ->groupBy('klm.kelompokpasien', 'kb.name')
            ->get();

        return $this->respond($query);
    }

    public function transaksiLaboratorium(Request $req)
    {

        $startDate = $req['tglAwal'] . ' 00:00';
        $endDate = $req['tglAkhir'] . ' 23:59';

        $query = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pp.produkfk', '=', 'pr.id')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('kelompokpasien_m as klm', 'pd.objectkelompokpasienlastfk', '=', 'klm.id')
            ->where('pp.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('apd.objectruanganfk', $req['ruangId'])
            ->where('pd.statusenabled', true)
            ->whereIn('pr.objectdetailjenisprodukfk', explode(',', $this->settingFix('idJenisProdukLaborat')))
            ->whereBetween('pp.tglpelayanan', [$startDate, $endDate])
            ->select(
                'pr.namaproduk',
                'klm.kelompokpasien',
                DB::raw('SUM(pp.hargasatuan) as total'),
                DB::raw('SUM(pp.jumlah) as qtypemeriksaan')
            )
            ->groupBy('pp.produkfk', 'pr.namaproduk', 'klm.kelompokpasien')
            ->get();
        return $this->respond($query);
    }
}
