<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\MKKO;
use App\Models\Transaksi\MKKODetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Http;
use Webpatser\Uuid\Uuid;

class MKKOCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function saveBORLOS(Request $r, $lokal = false)
    {
        DB::beginTransaction();
        try {
            $idProfile = $this->kdProfile;
            $tglAwal = $r['tanggal'] . ' 00:00';
            $tglAkhir =  $r['tanggal']  . ' 23:59';

            $sehari = 1;
            $jumlahTT = collect(DB::select("
            SELECT
            tt.id,
            tt.objectstatusbedfk
            FROM tempattidur_m AS tt
            INNER JOIN kamar_m AS kmr ON kmr.id = tt.objectkamarfk
            INNER JOIN ruangan_m AS ru ON ru.id = kmr.objectruanganfk
            WHERE  tt.kdprofile = $idProfile
            AND tt.statusenabled = true
            AND kmr.statusenabled = true
            and ru.statusenabled = true
            "))->count();


            $idDepRanap = $this->settingFix('kdDepartemenRanapFix');
            $hariPerawatan = collect(DB::select(
                "SELECT count(pd.noregistrasi) as jumlahhariperawatan
            FROM pasiendaftar_t AS pd
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
            WHERE pd.kdprofile = $idProfile
            AND pd.tglpulang IS NULL
            AND pd.statusenabled = TRUE
            and ru.objectdepartemenfk in ( $idDepRanap)
           "
            ))->first();

            $idStatKelMeninggal = (int) $this->settingFix('KdStatKeluarMeninggal', $idProfile);
            $idKondisiPasienMeninggal = (int) $this->settingFix('KdKondisiPasienMeninggal', $idProfile);
            // $hariPerawatNew = collect(DB::select("
            // SELECT  SUM (case when pd.tglpulang is null
            // then ('$tglAkhir' - pd.tglregistrasi::date) else (pd.tglpulang::date - pd.tglregistrasi::date) end)
            // AS jumlah_hari_perawatan
            // FROM pasiendaftar_t AS pd
            // INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
            // where
            // pd.kdprofile = $idProfile AND
            // ru.objectdepartemenfk in ( $idDepRanap)
            // and (  (pd.tglregistrasi::date >=  '$tglAwal' and pd.tglregistrasi::date <='$tglAkhir')
            // and (( pd.tglpulang::date  >=  '$tglAwal' and pd.tglpulang::date  <= '$tglAkhir' ) or pd.tglpulang is null))
            // and pd.statusenabled = true"));



            $lamaRawat = collect(DB::select("
                select sum(x.hari) as lamarawat, count(x.noregistrasi)as jumlahpasienpulang from (
                SELECT
                    date_part('DAY', case when  pd.tglpulang is null then now() else  pd.tglpulang end - pd.tglregistrasi) as hari ,pd.noregistrasi
                    FROM
                    pasiendaftar_t AS pd
                    INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
                    WHERE pd.kdprofile = $idProfile and
                    pd.tglpulang BETWEEN '$tglAwal'AND '$tglAkhir'
                    and pd.tglpulang is not null
                    and pd.statusenabled=true
                    and  ru.objectdepartemenfk in ( $idDepRanap)
                    GROUP BY pd.noregistrasi,pd.tglpulang,pd.tglregistrasi
                ) as x
        "))->first();

            $dataMeninggal = collect(DB::select("select count(x.noregistrasi) as jumlahmeninggal,
                count(case when x.objectkondisipasienfk = $idKondisiPasienMeninggal then 1 end ) AS jumlahlebih48
                FROM
                (select noregistrasi,to_char(tglregistrasi , 'mm')  as bulanregis ,statuskeluar,kondisipasien,objectkondisipasienfk
                from pasiendaftar_t
                join statuskeluar_m on statuskeluar_m.id =pasiendaftar_t.objectstatuskeluarfk
                left join kondisipasien_m on kondisipasien_m.id =pasiendaftar_t.objectkondisipasienfk
                where pasiendaftar_t.kdprofile = $idProfile and objectstatuspulangfk = $idStatKelMeninggal
                and  tglpulang BETWEEN '$tglAwal' and '$tglAkhir'
                and pasiendaftar_t.statusenabled=true
                ) as x"))->first();

            $bor = 0;
            $alos = 0;
            $toi = 0;
            $bto = 0;
            $ndr = 0;
            $gdr = 0;
            $hariPerawatanJml = 0;
            $jmlPasienPlg = 0;
            $jmlMeninggal = 0;
            $jmlMatilebih48 = 0;


            // if ($dataMeninggal->jumlahmeninggal == 0) {
            //     $dataMeninggal->jumlahmeninggal = 1;
            // }
            // if ($dataMeninggal->jumlahlebih48 == 0) {
            //     $dataMeninggal->jumlahlebih48 = 1;
            // }
            //                         if ($hariPerawatan->bulanregis == $lamaRawat->bulanpulang &&
            //                             $lamaRawat->bulanpulang == $dataMeninggal->bulanregis ) {
            /** @var  $gdr = (Jumlah Mati dibagi Jumlah pasien Keluar (Hidup dan Mati) */
            if ($lamaRawat->jumlahpasienpulang != 0) {

                $gdr = (int) $dataMeninggal->jumlahmeninggal * 1000 /  (int)$lamaRawat->jumlahpasienpulang;
                /** @var  $NDR = (Jumlah Mati > 48 Jam dibagi Jumlah pasien Keluar (Hidup dan Mati) */
                $ndr = (int) $dataMeninggal->jumlahlebih48 * 1000 / (int)$lamaRawat->jumlahpasienpulang;
            }
            $jmlMeninggal = (int) $dataMeninggal->jumlahmeninggal;
            $jmlMatilebih48 = (int) $dataMeninggal->jumlahlebih48;
            //                         }

            //                if ($hariPerawatan->bulanregis == $lamaRawat->bulanpulang ) {
            /** @var  $alos = (Jumlah Lama Dirawat dibagi Jumlah pasien Keluar (Hidup dan Mati) */
            //                return $this->respond($lamaRawat->jumlahpasienpulang );
            if ((int)$lamaRawat->jumlahpasienpulang > 0) {
                $alos = (int)$lamaRawat->lamarawat / (int)$lamaRawat->jumlahpasienpulang;
            }

            /** @var  $bto = Jumlah pasien Keluar (Hidup dan Mati) DIBAGI Jumlah tempat tidur */
            $bto = (int)$lamaRawat->jumlahpasienpulang / $jumlahTT;

            //                }
            //                foreach ($num_of_days as $numday){
            //                    if ($numday['bulan'] == $hariPerawatan->bulanregis){
            /** @var  $bor = (Jumlah hari perawatn RS dibagi ( jumlah TT x Jumlah hari dalam satu periode ) ) x 100 % */
            $bor = ((int)$hariPerawatan->jumlahhariperawatan * 100 / ($jumlahTT *  (float)$sehari)); //$numday['jumlahhari']));

            /** @var  $toi = (Jumlah TT X Periode) - Hari Perawatn DIBAGI Jumlah pasien Keluar (Hidup dan Mati)*/
            //                        $toi = ( ( $jumlahTT * $numday['jumlahhari'] )- (int)$hariPerawatan->jumlahhariperawatan ) /(int)$lamaRawat->jumlahpasienpulang ;
            if ((int)$lamaRawat->jumlahpasienpulang > 0) {
                $toi = (($jumlahTT * (float)$sehari) - (int)$hariPerawatan->jumlahhariperawatan) / (int)$lamaRawat->jumlahpasienpulang;
            }

            $hariPerawatanJml = (int)$hariPerawatan->jumlahhariperawatan;
            $jmlPasienPlg = (int)$lamaRawat->jumlahpasienpulang;
            $data10 = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $idProfile,
                'statusenabled' => true,
                'bor' => (float) number_format($bor, 2),
                'alos' => (float) number_format($alos, 2),
                'bto' => (float) number_format($bto, 2),
                'toi' => (float)number_format($toi, 2),
                'gdr' => (float)number_format($gdr, 2),
                'ndr' => (float) number_format($ndr, 2),
                'lamarawat' => (float)$lamaRawat->lamarawat,
                'hariperawatan' => $hariPerawatanJml,
                'pasienpulang' => $jmlPasienPlg,
                'meninggal' => $jmlMeninggal,
                'matilebih48' =>  $jmlMatilebih48,
                'tanggal' => $r['tanggal'],
                'insertdate' => date('Y-m-d H:i:s'),
                'jmltempattidur' => $jumlahTT
            );


            $new  = DB::table('closingborlostoi_t')->where('tanggal', $r['tanggal'])->first();
            if (empty($new)) {
                DB::table('closingborlostoi_t')->insert(
                    $data10
                );
            } else {
                DB::table('closingborlostoi_t')
                    ->where('norec', $new->norec)
                    ->update(
                        $data10
                    );
            }
            $dataBOR =  $this->saveBORLOSPerRuangan($r, true);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => 'Ok'
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }

        if ($lokal) {
            return $result;
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getBORLOS(Request $r, $lokal = false)
    {
        try {
            $data  = DB::table('closingborlostoi_t')->whereBetween('tanggal', [$r['dari'], $r['sampai']])->orderByDesc('tanggal')->get();
            $dataDetail  = DB::table('closingborlostoidetail_t')->whereBetween('tanggal', [$r['dari'], $r['sampai']])->orderByDesc('tanggal')->orderBy('namaruangan')->get();
            // $data = DB::select(DB::raw($r['query']));
            $res['data']  = $data;
            $res['detail']  = $dataDetail;
            if ($lokal == true || isset($r['send'])) {
                foreach ($data as $rekap) {

                    $postData = [
                        "tanggal" => $rekap->tanggal,
                        "detail" => [
                            "LOS_PASIEN_JIWA" => 0,
                            "ALOS_PASIEN_JIWA" => 0,
                            "BOR" =>  $rekap->bor,
                            "BTO" =>  $rekap->bto,
                            "LOS_PASIEN_NON_JIWA" =>  $rekap->lamarawat,
                            "ALOS_PASIEN_NON_JIWA" =>  $rekap->alos,
                            "jumlah_tempat_tidur" =>  $rekap->jmltempattidur,
                        ]
                    ];
                    // \Log::info(json_encode($postData));
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'bor';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;
                    $insert = $this->apiIntegrate($objetoRequest, true);

                    \Log::info("MKKO BOR auto " . json_encode($insert));
                }
            }
            if (isset($r['closing'])) {
                $sumPerMonth = [];
                $dataArray = json_decode(json_encode($data), true);
                // Loop through the data array
                foreach ($dataArray as $item) {
                    // Extract the month from the "bulan" key
                    $month = substr($item['tanggal'], 0, 7);

                    // If the month doesn't exist in the $sumPerMonth array, initialize it with zeros
                    if (!isset($sumPerMonth[$month])) {
                        $sumPerMonth[$month] = array_fill_keys(array_keys($item), 0);
                        // Remove "bulan" key from initialization
                        unset($sumPerMonth[$month]['tanggal']);
                    }

                    // Accumulate the values for each key in the corresponding month
                    foreach ($item as $key => $value) {
                        if ($key !== 'tanggal') {
                            $sumPerMonth[$month][$key] = $value;
                        }
                    }
                }

                // return $$dataArray;

                $resSUM = $sumPerMonth[$r['bulan']];

                $month = substr($r['bulan'], 5, 2);

                $arrayWithMonths = [
                    "jan" => null,
                    "feb" => null,
                    "mar" => null,
                    "apr" => null,
                    "mei" => null,
                    "jun" => null,
                    "jul" => null,
                    "agu" => null,
                    "sep" => null,
                    "okt" => null,
                    "nov" => null,
                    "des" => null,
                ];

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['bor'];
                $arrayWithMonths['id'] = 26;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['bto'];
                $arrayWithMonths['id'] = 27;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                // $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['LOS_PASIEN_JIWA'];
                // $arrayWithMonths['id'] = 30;
                // $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
                // $dataInsert[] = $arrayWithMonths;

                // $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['ALOS_PASIEN_JIWA'];
                // $arrayWithMonths['id'] = 31;
                // $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
                // $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['lamarawat'];
                $arrayWithMonths['id'] = 28;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                // return $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['alos'];
                $arrayWithMonths['id'] = 29;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['jmltempattidur'];
                $arrayWithMonths['id'] = 33;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                // return $resSUM['jmltempattidur'];

                return $this->apiPOST($dataInsert);
            }
            $msg = 'Ok';
        } catch (Exception $ex) {
            $data = [];
            $data10 = [];
            $msg = $ex->getMessage() . $ex->getLine();
        }
        $result = array(
            'data' => $data,
            'message' => $msg,
        );
        return $this->respond($res);
    }

    public function jmlPengunjung(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idDepRanap = $this->settingFix('kdDepartemenRanapFix');
        $pasienUMUM = $this->settingFix('idKelompokPasienUMUM');
        $pasienBPJS = $this->settingFix('idKelompokPasienBPJS');
        $pasienPerusahaan = $this->settingFix('idKelompokPasienPerusahaan');
        $pasienAsuransi = $this->settingFix('idKelompokPasienAsuransi');
        $eksekutif = $this->settingFix('idJenisPelayananEksek');
        $reguler = $this->settingFix('idJenisPelayananReguler');
        $idDepRajal = $this->settingFix('kdDepartemenRawatJalanFix');

        $data = DB::select(DB::raw($request['query']));
        // $data = DB::table('pasiendaftar_t as pd')
        //     ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
        //     ->join('jenispelayanan_m as jp', 'jp.id', '=', 'pd.jenispelayanan')
        //     ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
        //     ->leftjoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
        //     ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        //     ->select(
        //         'pd.tglregistrasi',
        //         'pd.noregistrasi',
        //         'ps.namapasien',
        //         'ru.namaruangan',
        //         'pd.norec as norec_pd',
        //         'kp.kelompokpasien',
        //         'dp.namadepartemen',
        //         'pd.objectkelasrawatfk',
        //         'pd.objectkelasfk',
        //         'pd.jenispelayanan as jenispelayanan',
        //         'jp.jenispelayanan as layanan',
        //         'ru.objectdepartemenfk as kddepartemen',
        //         'pd.objectkelompokpasienlastfk',
        //         DB::raw("to_char(pd.tglregistrasi, 'YYYY-MM') as bulan")
        //     )
        //     ->where('pd.statusenabled', true)
        //     ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDeptRS')))
        //     ->where('pd.kdprofile', $kdProfile)
        //     ->distinct();


        // if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
        //     $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        // }
        // if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
        //     $tgl = $request['tglAkhir'];
        //     $data = $data->where('pd.tglregistrasi', '<=', $tgl);
        // }
        // $data = $data->get();


        $data10 = [];
        $jml = 0;
        $sama = false;
        $pasienUmumRJ = 0;
        $pasienUmumRI = 0;
        $pasienJKNRegRI = 0;
        $pasienJKNNonRegRI = 0;
        $pasienJKNRJ = 0;
        $pasienAsuransiRI = 0;
        $pasienAsuransiRJ = 0;
        $pasienEksUmumRI = 0;
        $pasienEksUmumRJ = 0;
        $pasienEksAsun = 0;
        $pasienPerRI = 0;
        $pasienPerRJ = 0;
        $pasienEksPer = 0;
        $pasienEksAsunRJ = 0;
        $pasienEksPerRJ = 0;

        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->bulan == $data10[$i]['bulan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    //JKN

                    if ($item->objectkelompokpasienlastfk == 2 && $item->kddepartemen == $idDepRanap && $item->objectkelasrawatfk == $item->objectkelasfk) {
                        $data10[$i]['pasienJKNRegRI'] = (float)$hideung['pasienJKNRegRI'] + 1;
                    }

                    if ($item->objectkelompokpasienlastfk == 2 && $item->kddepartemen == $idDepRanap && $item->objectkelasrawatfk != null && $item->objectkelasrawatfk != $item->objectkelasfk) {
                        $data10[$i]['pasienJKNNonRegRI'] = (float)$hideung['pasienJKNNonRegRI'] + 1;
                    }
                    if (in_array($item->objectjenispegawaifk, [2, 18]) && $item->kddepartemen = !$idDepRanap) {
                        $data10[$i]['pasienJKNRJ'] = (float)$hideung['pasienJKNRJ'] + 1;
                    }
                    // Umum
                    if ($item->objectkelompokpasienlastfk == 1 && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $data10[$i]['pasienUmumRI'] = (float)$hideung['pasienUmumRI'] + 1;
                    }
                    if ($item->objectkelompokpasienlastfk == 1 && $item->kddepartemen != $idDepRanap && $item->jenispelayanan == $reguler) {
                        $data10[$i]['pasienUmumRJ'] = (float)$hideung['pasienUmumRJ'] + 1;
                    }
                    //asuransi
                    if ($item->objectkelompokpasienlastfk == 5 && $item->kddepartemen != $idDepRanap && $item->jenispelayanan == $reguler) {
                        $data10[$i]['pasienAsuransiRJ'] = (float)$hideung['pasienAsuransiRJ'] + 1;
                    }
                    if ($item->objectkelompokpasienlastfk == 5 && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $data10[$i]['pasienAsuransiRI'] = (float)$hideung['pasienAsuransiRI'] + 1;
                    }
                    //perusahaan
                    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $data10[$i]['pasienPerRI'] = (float)$hideung['pasienPerRI'] + 1;
                    }
                    if (in_array($item->objectjenispegawaifk, [5, 29]) && $item->kddepartemen != $idDepRanap && $item->jenispelayanan == $reguler) {
                        $data10[$i]['pasienPerRJ'] = (float)$hideung['pasienPerRJ'] + 1;
                    }
                    //eksekutif
                    if ($item->objectkelompokpasienlastfk == 1 && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $data10[$i]['pasienEksUmumRI'] = (float)$hideung['pasienEksUmumRI'] + 1;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $data10[$i]['pasienEksAsun'] = (float)$hideung['pasienEksAsun'] + 1;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $data10[$i]['pasienEksPer'] = (float)$hideung['pasienEksPer'] + 1;
                    }
                    if ($item->objectkelompokpasienlastfk == 1 && $item->kddepartemen != $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $data10[$i]['pasienEksUmumRJ'] = (float)$hideung['pasienEksUmumRJ'] + 1;
                    }
                    if ($item->objectkelompokpasienlastfk == 3 && $item->kddepartemen != $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $data10[$i]['pasienEksAsunRJ'] = (float)$hideung['pasienEksAsunRJ'] + 1;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen != $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $data10[$i]['pasienEksPerRJ'] = (float)$hideung['pasienEksPerRJ'] + 1;
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {

                if ($item->objectkelompokpasienlastfk == 2 && $item->kddepartemen == $idDepRanap && $item->objectkelasrawatfk == $item->objectkelasfk) {
                    $pasienJKNRegRI = 0;
                }
                if ($item->objectkelompokpasienlastfk == 2 && $item->kddepartemen == $idDepRanap && $item->objectkelasrawatfk != null && $item->objectkelasrawatfk != $item->objectkelasfk) {
                    $pasienJKNNonRegRI = 0;
                }
                if ($item->objectkelompokpasienlastfk == 2 && $item->kddepartemen == $idDepRajal) {
                    $pasienJKNRJ = 0;
                }
                // Umum
                if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $pasienUmumRI = 0;
                }
                if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $pasienUmumRJ = 0;
                }
                //asuransi
                if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $pasienAsuransiRJ = 0;
                }
                if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $pasienAsuransiRI = 0;
                }
                //perusahaan
                if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $pasienPerRI = 0;
                }
                if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $pasienPerRJ = 0;
                }
                //eksekutif
                if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $pasienEksUmumRI = 0;
                }
                if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $pasienEksAsun = 0;
                }
                if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $pasienEksPer = 0;
                }
                if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $pasienEksUmumRJ = 0;
                }
                if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $pasienEksAsunRJ = 0;
                }
                if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $pasienEksPerRJ = 0;
                }


                $data10[] = array(
                    'bulan' => $item->bulan,
                    'pasienJKNNonRegRI' => $pasienJKNNonRegRI,
                    'pasienJKNRegRI' => $pasienJKNRegRI,
                    'pasienUmumRJ' => $pasienUmumRJ,
                    'pasienUmumRI' => $pasienUmumRI,
                    'pasienAsuransiRJ' => $pasienAsuransiRJ,
                    'pasienJKNRJ' => $pasienJKNRJ,
                    'pasienAsuransiRI' => $pasienAsuransiRI,
                    'pasienPerRI' => $pasienPerRI,
                    'pasienPerRJ' => $pasienPerRJ,
                    'pasienEksUmumRI' => $pasienEksUmumRI,
                    'pasienEksUmumRJ' => $pasienEksUmumRJ,
                    'pasienEksAsun' => $pasienEksAsun,
                    'pasienEksPer' => $pasienEksPer,
                    'pasienEksAsunRJ' => $pasienEksAsunRJ,
                    'pasienEksPerRJ' => $pasienEksPerRJ,
                    'jumlah' => 0,
                );
            }

            // foreach ($data10 as $key => $row) {
            //     $count[$key] = $row['norec_pd'];
            // }

            // array_multisort($count, SORT_ASC, $data10);
        }

        $result = array(
            'details' => $data,
            'data' => $data10,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function persentaseInpatien(Request $request, $lokal = false)
    {
        $idProfile = $this->kdProfile;
        try {
            $START_DATE = $request['dari'];
            $END_DATE = $request['sampai'];
            $persen = DB::select(
                DB::raw("select bulan, sum(jumlah) as jml, sum(persentase) as persentase, total_count from (
                SELECT bulan, nama_pasien, COUNT(nama_pasien)-1 AS jumlah, (COUNT(nama_pasien) -1) * 100.00 / total_count AS persentase, total_count
            FROM (SELECT pd.tglregistrasi, ps.id AS psId, ps.namapasien AS nama_pasien,  pd.noregistrasi, to_char(pd.tglregistrasi, 'YYYY-MM') AS bulan,
               COUNT(*) OVER(PARTITION BY to_char(pd.tglregistrasi, 'YYYY-MM')) AS total_count
            FROM
                pasiendaftar_t AS pd
                INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
                LEFT JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
            WHERE
            pd.kdprofile = $idProfile and pd.tglregistrasi between '$START_DATE' and '$END_DATE' and pd.statusenabled=true
            and ru.objectdepartemenfk = 16 and pd.tglpulang is not null) AS subquery
            GROUP BY
            bulan, total_count,nama_pasien) as z
            GROUP BY z.bulan, z.total_count")
            );

            $result = [];
            foreach ($persen as $item) {
                $details = DB::select(
                    DB::raw("select * from ( select pd.tglregistrasi, pd.tglpulang, ps.id AS psID, ps.namapasien AS nama_pasien, pd.noregistrasi, pg.namalengkap as dokter, TO_CHAR(pd.tglregistrasi, 'YYYY-MM') AS bulan,
                        CASE WHEN COUNT(*) OVER (PARTITION BY ps.id) > 1 THEN 'Dirawat Kembali' ELSE 'Tidak'
                        END AS status from pasiendaftar_t AS pd
                    INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
                    LEFT JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
                    LEFT JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                    WHERE pd.kdprofile = $idProfile AND TO_CHAR(pd.tglregistrasi, 'YYYY-MM') = :bulan AND pd.statusenabled = true
                        AND ru.objectdepartemenfk = 16
                ) AS subquery
                WHERE
                    status = 'Dirawat Kembali';"),
                    array(
                        'bulan' => $item->bulan,

                    )
                );
                $result[] = array(
                    'bulan' => $item->bulan,
                    'jml' => $item->jml,
                    'persentase' => $item->persentase,
                    'total_count' => $item->total_count,
                    'details' => $details
                );
            }

            if ($lokal == true) {
                foreach ($persen as $rekap) {

                    $postData = [
                        "bulan" => substr($rekap->bulan, 5, 2),
                        "tahun" => substr($rekap->bulan, 0, 4),
                        "detail" => [
                            "inpatient_readmission_rate" => round($rekap->persentase, 2)
                        ]
                    ];
                    // \Log::info(json_encode($postData));
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'readmission-date';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;
                    $insert = $this->apiIntegrate($objetoRequest, true);

                    \Log::info("MKKO Laporan readmisi  " . json_encode($insert));
                }
            }
            if (isset($request['closing'])) {

                $sumPerMonth = [];
                $month = substr($result[0]['bulan'], 0, 7);
                if (!isset($sumPerMonth[$month])) {
                    $sumPerMonth[$month] = array_fill_keys(array_keys($result[0]), 0);
                    unset($sumPerMonth[$month]['bulan']);
                    $sumPerMonth[$month] = $result[0]['persentase'];
                }

                $resSUM = $sumPerMonth[$request['bulan']];
                $month = substr($request['bulan'], 5, 2);

                $arrayWithMonths = [
                    "jan" => null,
                    "feb" => null,
                    "mar" => null,
                    "apr" => null,
                    "mei" => null,
                    "jun" => null,
                    "jul" => null,
                    "agu" => null,
                    "sep" => null,
                    "okt" => null,
                    "nov" => null,
                    "des" => null,
                ];

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM;
                $arrayWithMonths['id'] = 25;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                return $this->apiPOST($dataInsert);
            }
            $msg = 'Ok';
        } catch (Exception $ex) {
            $data = [];
            $data10 = [];
            $msg = $ex->getMessage() . $ex->getLine();
        }
        // $result = array(
        //     '' => $result,
        //     'message' => $msg
        // );

        return $this->respond($result);
    }
    public function persentaseInpatienQuery(Request $request)
    {

        $idProfile = $this->kdProfile;
        $persen = DB::select(
            DB::raw($request['query'])

        );

        return $this->respond($persen);
    }

    public function getTargetMKKO(Request $request)
    {
        $idProfile = $this->kdProfile;

        $data = DB::select(
            DB::raw("
            select * from (
                    select mp.id,mp.noaccount,mp.namaaccount,mp.satuan,mp.urutan,'font-weight:bold' as style,
                                    null as jan, null as feb, null as mar, null as apr, null as mei, null as jun, null as jul, null as agu, null as sep,null as okt, null as nov, null as des,
                    null as jan_budget, null as feb_budget, null as mar_budget, null as apr_budget, null as mei_budget, null as jun_budget, null as jul_budget,
                    null as agu_budget, null as sep_budget, null as okt_budget, null as nov_budget, null as des_budget
                    from chartofmkko_m as mp
                    left join
                    (select left(coa.noaccount,3) as noaccount,
                    SUM(jan) AS jan, SUM(feb) AS feb, SUM(mar) AS mar, SUM(apr) AS apr,	SUM(mei) AS mei,
                    SUM(jun) AS jun, SUM(jul) AS jul, SUM(agu) AS agu,	SUM(sep) AS sep,SUM(okt) AS okt,
                    SUM(nov) AS nov,SUM(des) AS des,SUM(jan_budget) AS jan_budget, SUM(feb_budget) AS feb_budget,
                    SUM(mar_budget) AS mar_budget, SUM(apr_budget) AS apr_budget, SUM(mei_budget) AS mei_budget,
                    SUM(jun_budget) AS jun_budget, SUM(jul_budget) AS jul_budget, SUM(agu_budget) AS agu_budget,
                    SUM(sep_budget) AS sep_budget, SUM(okt_budget) AS okt_budget,SUM(nov_budget) AS nov_budget,
                    SUM(des_budget) AS des_budget
                    from chartofmkko_m as coa
                    INNER JOIN mkko_t as pjd ON pjd.objectaccountfk= coa.id
                    where coa.kdprofile = 1
                    and pjd.tahun ='$request[tahun]'
                    group by left(coa.noaccount,3)
                    )as x  on x.noaccount = left(mp.noaccount,3)
                    where mp.kodeexternal='1' and
                    left(mp.noaccount,1) in ('0') and mp.statusenabled=true
    
                    union all
    
                    select mp.id,mp.noaccount,'------' || mp.namaaccount,mp.satuan,mp.urutan,'' as style,
                    x.jan, x.feb, x.mar, x.apr, x.mei, x.jun, x.jul, x.agu, x.sep,x.okt, x.nov, x.des,
                    x.jan_budget, x.feb_budget, x.mar_budget, x.apr_budget, x.mei_budget, x.jun_budget, x.jul_budget,
                    x.agu_budget, x.sep_budget, x.okt_budget, x.nov_budget, x.des_budget
                    from chartofmkko_m as mp
                    left join
                    (select left(coa.noaccount,6) as noaccount,
                    SUM(jan) AS jan, SUM(feb) AS feb, SUM(mar) AS mar, SUM(apr) AS apr,	SUM(mei) AS mei,
                    SUM(jun) AS jun, SUM(jul) AS jul,	SUM(agu) AS agu,	SUM(sep) AS sep,SUM(okt) AS okt,
                    SUM(nov) AS nov,SUM(des) AS des,SUM(jan_budget) AS jan_budget,	SUM(feb_budget) AS feb_budget,
                    SUM(mar_budget) AS mar_budget, SUM(apr_budget) AS apr_budget, SUM(mei_budget) AS mei_budget,
                    SUM(jun_budget) AS jun_budget, SUM(jul_budget) AS jul_budget,	SUM(agu_budget) AS agu_budget,
                    SUM(sep_budget) AS sep_budget, SUM(okt_budget) AS okt_budget,SUM(nov_budget) AS nov_budget,
                    SUM(des_budget) AS des_budget
                    from chartofmkko_m as coa
                    INNER JOIN mkko_t as pjd ON pjd.objectaccountfk= coa.id
                    where coa.kdprofile = 1
                    and pjd.tahun ='$request[tahun]'
                    group by left(coa.noaccount,6)
                    )as x  on x.noaccount = left(mp.noaccount,6)
                    where mp.kodeexternal='2'
                    and left(mp.noaccount,1) in ('0') and mp.statusenabled=true
    
                    union ALL
    
                    select mp.id,mp.noaccount,'---------' || mp.namaaccount,mp.satuan,mp.urutan,'' as style,
                    x.jan, x.feb, x.mar, x.apr, x.mei, x.jun, x.jul, x.agu, x.sep,x.okt, x.nov, x.des,
                    x.jan_budget, x.feb_budget, x.mar_budget, x.apr_budget, x.mei_budget, x.jun_budget, x.jul_budget,
                    x.agu_budget, x.sep_budget, x.okt_budget, x.nov_budget, x.des_budget
                    from chartofmkko_m as mp
                    left join
                    (select left(coa.noaccount,9) as noaccount,
                    SUM(jan) AS jan, SUM(feb) AS feb, SUM(mar) AS mar, SUM(apr) AS apr,	SUM(mei) AS mei,
                    SUM(jun) AS jun, SUM(jul) AS jul,	SUM(agu) AS agu, SUM(sep) AS sep,SUM(okt) AS okt,
                    SUM(nov) AS nov,SUM(des) AS des,SUM(jan_budget) AS jan_budget,	SUM(feb_budget) AS feb_budget,
                    SUM(mar_budget) AS mar_budget, SUM(apr_budget) AS apr_budget, SUM(mei_budget) AS mei_budget,
                    SUM(jun_budget) AS jun_budget, SUM(jul_budget) AS jul_budget, SUM(agu_budget) AS agu_budget,
                    SUM(sep_budget) AS sep_budget,	SUM(okt_budget) AS okt_budget,SUM(nov_budget) AS nov_budget,
                    SUM(des_budget) AS des_budget
                    from chartofmkko_m as coa
                    INNER JOIN mkko_t as pjd ON pjd.objectaccountfk= coa.id
                    where coa.kdprofile = 1
                    and pjd.tahun ='$request[tahun]'
                    group by left(coa.noaccount,9)
                    )as x  on x.noaccount = left(mp.noaccount,9)
                    where mp.kodeexternal='3'
                    and left(mp.noaccount,1) in ('0') and mp.statusenabled=true
    
                    union ALL
    
                    select mp.id,mp.noaccount,'------------' || mp.namaaccount,mp.satuan,mp.urutan,'' as style,
                    x.jan, x.feb, x.mar, x.apr, x.mei, x.jun, x.jul, x.agu, x.sep,x.okt, x.nov, x.des,
                    x.jan_budget, x.feb_budget, x.mar_budget, x.apr_budget, x.mei_budget, x.jun_budget, x.jul_budget,
                    x.agu_budget, x.sep_budget, x.okt_budget, x.nov_budget, x.des_budget
                    from chartofmkko_m as mp
                    left join
                    (select left(coa.noaccount,11) as noaccount,
                    SUM(jan) AS jan, SUM(feb) AS feb, SUM(mar) AS mar, SUM(apr) AS apr,	SUM(mei) AS mei,
                    SUM(jun) AS jun, SUM(jul) AS jul, SUM(agu) AS agu, SUM(sep) AS sep,SUM(okt) AS okt,
                    SUM(nov) AS nov,SUM(des) AS des,SUM(jan_budget) AS jan_budget, SUM(feb_budget) AS feb_budget,
                    SUM(mar_budget) AS mar_budget, SUM(apr_budget) AS apr_budget, SUM(mei_budget) AS mei_budget,
                    SUM(jun_budget) AS jun_budget, SUM(jul_budget) AS jul_budget, SUM(agu_budget) AS agu_budget,
                    SUM(sep_budget) AS sep_budget, SUM(okt_budget) AS okt_budget,SUM(nov_budget) AS nov_budget,
                    SUM(des_budget) AS des_budget
                    from chartofmkko_m as coa
                    INNER JOIN mkko_t as pjd ON pjd.objectaccountfk= coa.id
                    where coa.kdprofile = 1
                    and pjd.tahun ='$request[tahun]'
                    group by left(coa.noaccount,11)
                    )as x  on x.noaccount = left(mp.noaccount,11)
                    where mp.kodeexternal='4'
                    and left(mp.noaccount,1) in ('0') and mp.statusenabled=true
    
                    union ALL
    
                    select mp.id,mp.noaccount,'------------------' || mp.namaaccount,mp.satuan,mp.urutan,'' as style,
                    x.jan, x.feb, x.mar, x.apr, x.mei, x.jun, x.jul, x.agu, x.sep,x.okt, x.nov, x.des,
                    x.jan_budget, x.feb_budget, x.mar_budget, x.apr_budget, x.mei_budget, x.jun_budget, x.jul_budget,
                    x.agu_budget, x.sep_budget, x.okt_budget, x.nov_budget, x.des_budget
                    from chartofmkko_m as mp
                    left join
                    (select left(coa.noaccount,12) as noaccount,
                    SUM(jan) AS jan, SUM(feb) AS feb, SUM(mar) AS mar, SUM(apr) AS apr,	SUM(mei) AS mei,
                    SUM(jun) AS jun, SUM(jul) AS jul,	SUM(agu) AS agu,	SUM(sep) AS sep,SUM(okt) AS okt,
                    SUM(nov) AS nov,SUM(des) AS des,SUM(jan_budget) AS jan_budget,	SUM(feb_budget) AS feb_budget,
                    SUM(mar_budget) AS mar_budget, SUM(apr_budget) AS apr_budget, SUM(mei_budget) AS mei_budget,
                    SUM(jun_budget) AS jun_budget, SUM(jul_budget) AS jul_budget,	SUM(agu_budget) AS agu_budget,
                    SUM(sep_budget) AS sep_budget, SUM(okt_budget) AS okt_budget,SUM(nov_budget) AS nov_budget,
                    SUM(des_budget) AS des_budget
                    from chartofmkko_m as coa
                    INNER JOIN mkko_t as pjd ON pjd.objectaccountfk= coa.id
                    where coa.kdprofile = 1
                    and pjd.tahun ='$request[tahun]'
                    group by left(coa.noaccount,12)
                    )as x  on x.noaccount = left(mp.noaccount,12)
                    where mp.kodeexternal='5'
                    and left(mp.noaccount,1) in ('0') and mp.statusenabled=true
    
                    union ALL
    
                    select mp.id,mp.noaccount,'---------------------' || mp.namaaccount,mp.satuan,mp.urutan,'' as style,
                    x.jan, x.feb, x.mar, x.apr, x.mei, x.jun, x.jul, x.agu, x.sep,x.okt, x.nov, x.des,
                    x.jan_budget, x.feb_budget, x.mar_budget, x.apr_budget, x.mei_budget, x.jun_budget, x.jul_budget,
                    x.agu_budget, x.sep_budget, x.okt_budget, x.nov_budget, x.des_budget
                    from chartofmkko_m as mp
                    left join
                    (select left(coa.noaccount,14) as noaccount,
                    SUM(jan) AS jan, SUM(feb) AS feb, SUM(mar) AS mar, SUM(apr) AS apr,	SUM(mei) AS mei,
                    SUM(jun) AS jun, SUM(jul) AS jul,	SUM(agu) AS agu,	SUM(sep) AS sep,SUM(okt) AS okt,
                    SUM(nov) AS nov,SUM(des) AS des,SUM(jan_budget) AS jan_budget,	SUM(feb_budget) AS feb_budget,
                    SUM(mar_budget) AS mar_budget, SUM(apr_budget) AS apr_budget, SUM(mei_budget) AS mei_budget,
                    SUM(jun_budget) AS jun_budget, SUM(jul_budget) AS jul_budget,	SUM(agu_budget) AS agu_budget,
                    SUM(sep_budget) AS sep_budget, SUM(okt_budget) AS okt_budget,SUM(nov_budget) AS nov_budget,
                    SUM(des_budget) AS des_budget
                    from chartofmkko_m as coa
                    INNER JOIN mkko_t as pjd ON pjd.objectaccountfk= coa.id
                    where coa.kdprofile = 1
                    and pjd.tahun ='$request[tahun]'
                    group by left(coa.noaccount,14)
                    )as x  on x.noaccount = left(mp.noaccount,14)
                    where mp.kodeexternal='6'
                    and left(mp.noaccount,1) in ('0') and mp.statusenabled=true
    
                    union ALL
    
                    select mp.id,mp.noaccount,'---------------------------' || mp.namaaccount,mp.satuan,mp.urutan,'' as style,
                    x.jan, x.feb, x.mar, x.apr, x.mei, x.jun, x.jul, x.agu, x.sep,x.okt, x.nov, x.des,
                    x.jan_budget, x.feb_budget, x.mar_budget, x.apr_budget, x.mei_budget, x.jun_budget, x.jul_budget,
                    x.agu_budget, x.sep_budget, x.okt_budget, x.nov_budget, x.des_budget
                    from chartofmkko_m as mp
                    left join
                    (select left(coa.noaccount,15) as noaccount,
                    SUM(jan) AS jan, SUM(feb) AS feb, SUM(mar) AS mar, SUM(apr) AS apr,	SUM(mei) AS mei,
                    SUM(jun) AS jun, SUM(jul) AS jul,	SUM(agu) AS agu,	SUM(sep) AS sep,SUM(okt) AS okt,
                    SUM(nov) AS nov,SUM(des) AS des,SUM(jan_budget) AS jan_budget,	SUM(feb_budget) AS feb_budget,
                    SUM(mar_budget) AS mar_budget, SUM(apr_budget) AS apr_budget, SUM(mei_budget) AS mei_budget,
                    SUM(jun_budget) AS jun_budget, SUM(jul_budget) AS jul_budget,	SUM(agu_budget) AS agu_budget,
                    SUM(sep_budget) AS sep_budget, SUM(okt_budget) AS okt_budget,SUM(nov_budget) AS nov_budget,
                    SUM(des_budget) AS des_budget
                    from chartofmkko_m as coa
                    INNER JOIN mkko_t as pjd ON pjd.objectaccountfk= coa.id
                    where coa.kdprofile = 1
                    and pjd.tahun ='$request[tahun]'
                    group by left(coa.noaccount,15)
                    )as x  on x.noaccount = left(mp.noaccount,15)
                    where mp.kodeexternal='7'
                    and left(mp.noaccount,1) in ('0') and mp.statusenabled=true

                )as z  ORDER BY z.urutan
            ")
        );
        return $this->respond($data);
    }

    public function laporanTindakanOperasi(Request $request, $lokal = false)
    {
        $idProfile = $this->kdProfile;

        try {

            $dataOperasi = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'pp.noregistrasifk', '=', 'apd.norec')
                ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftjoin('ruangan_m as ru1', 'ru1.id', '=', 'pd.objectruanganasalfk')
                ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->select(
                    'pd.noregistrasi',
                    'ps.namapasien',
                    'pp.tglpelayanan',
                    'pp.tglpelayanan as tanggal',
                    'pp.jumlah',
                    'ru1.namaruangan as ruanganasal',
                    'ru.namaruangan as ruangOK',
                    'pr.namaproduk',
                    DB::raw("to_char(pp.tglpelayanan,'yyyy-MM-dd') as tgl")
                )
                ->where('pp.kdprofile', $idProfile)
                ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBedah')))
                ->where('pp.statusenabled', true)
                ->whereNull('pp.strukresepfk')
                ->where('pd.statusenabled', true);

            if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
                $dataOperasi = $dataOperasi->where('pp.tglpelayanan', '>=', $request['tglAwal']);
            }
            if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
                $tgl = $request['tglAkhir'];
                $dataOperasi = $dataOperasi->where('pp.tglpelayanan', '<=', $tgl);
            }

            $jumlah = $dataOperasi->sum('pp.jumlah');
            $dataOperasi = $dataOperasi->get();

            $data10 = [];
            $jumlah_tindakan = 0;
            $sama = false;
            $i = 0;
            foreach ($dataOperasi as $item) {
                $sama = false;
                $i = 0;
                foreach ($data10 as $hideung) {
                    if ($item->tgl == $hideung['tgl']) {
                        $sama = true;
                        $jumlah_tindakan = (float)$hideung['jumlah_tindakan'] + 1;
                        $data10[$i]['jumlah_tindakan'] = $jumlah_tindakan;
                    }
                    $i = $i + 1;
                }

                if (!$sama) {

                    $data10[] = [
                        'tgl' => $item->tgl,
                        'jumlah_tindakan' => 1,

                    ];
                }
            }
            if ($lokal == true) {
                foreach ($data10 as $rekap) {

                    $postData = [
                        "tanggal" => $rekap['tgl'],
                        "detail" => [
                            "jml_tindakan_operasi" => $rekap['jumlah_tindakan']
                        ]
                    ];
                    // \Log::info(json_encode($postData));
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'jml-tindakan-operasi';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;
                    $insert = $this->apiIntegrate($objetoRequest, true);

                    \Log::info("MKKO Laporan tindakan operasi  " . json_encode($insert));
                }
                return;
            }

            $dataRencana = DB::table('strukorder_t as so')
                ->join('pasiendaftar_t as pd', 'so.noregistrasifk', '=', 'pd.norec')
                ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
                ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
                ->leftjoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
                // ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->select(
                    'pd.noregistrasi as noreg',
                    'ps.namapasien as pasien',
                    'so.tglorder',
                    'so.tglorder as tanggal',
                    'so.tglpelayananawal as rencana',
                    'so.noorder',
                    'ru1.namaruangan as ruangasal',
                    'ru.namaruangan as ruangbedah'
                    // 'pr.namaproduk'
                )
                ->where('so.kdprofile', $idProfile)
                ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBedah')))
                ->where('so.statusenabled', true)
                ->where('pd.statusenabled', true);

            if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
                $dataRencana = $dataRencana->where('so.tglorder', '>=', $request['tglAwal']);
            }
            if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
                $tgl = $request['tglAkhir'];
                $dataRencana = $dataRencana->where('so.tglorder', '<=', $tgl);
            }

            $jumlahRencana = $dataRencana->count('so.qtyproduk');
            $dataRencana = $dataRencana->get();


            $res['dataOperasi'] = $dataOperasi;
            $res['dataRencana'] = $dataRencana;
            $res['totalAll'] = $jumlah;
            $res['totalRencana'] = $jumlahRencana;
            // $data = DB::select(DB::raw($request['query']));
            $res['data'] = $data10;

            if (isset($request['closing'])) {

                $sumPerMonth = [];

                // Loop through the data array
                foreach ($data10 as $item) {
                    // Extract the month from the "bulan" key
                    $month = substr($item['tgl'], 0, 7);

                    // If the month doesn't exist in the $sumPerMonth array, initialize it with zeros
                    if (!isset($sumPerMonth[$month])) {
                        $sumPerMonth[$month] = array_fill_keys(array_keys($item), 0);
                        // Remove "bulan" key from initialization
                        unset($sumPerMonth[$month]['tgl']);
                    }

                    // Accumulate the values for each key in the corresponding month
                    foreach ($item as $key => $value) {
                        if ($key !== 'tgl') {
                            $sumPerMonth[$month][$key] += $value;
                        }
                    }
                }
                // return $data10;

                $resSUM = $sumPerMonth[$request['bulan']];
                $month = substr($request['bulan'], 5, 2);


                $arrayWithMonths = [
                    "jan" => null,
                    "feb" => null,
                    "mar" => null,
                    "apr" => null,
                    "mei" => null,
                    "jun" => null,
                    "jul" => null,
                    "agu" => null,
                    "sep" => null,
                    "okt" => null,
                    "nov" => null,
                    "des" => null,
                ];

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['jumlah_tindakan'];
                $arrayWithMonths['id'] = 32;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                return $this->apiPOST($dataInsert);
            }
            $msg = 'Ok';
        } catch (Exception $ex) {
            $data = [];
            $data10 = [];
            $msg = $ex->getMessage();
        }

        return $this->respond($res);
    }


    public function jmlPegawai(Request $request, $lokal = false)
    {

        $kdProfile = $this->kdProfile;
        try {

            $BULAN = $request['bulan'];
            $START_DATE = $request['dari'];
            $END_DATE = $request['sampai'];
            $data = DB::select(
                DB::raw(
                    "SELECT
               pg.ID AS idpegawai,
               pg.namalengkap,
               pg.objectjenispegawaifk,
               jp.jenispegawai,
               '$BULAN' AS bulan,
               CASE when pg.tglkeluar is null then 'Aktif' else 'Tidak Aktif' END AS status,
               CASE
                   WHEN jp.ID = 1 THEN 'Dokter'
                   WHEN jp.ID = 2 THEN 'Perawat'
                   WHEN jp.ID IN (8, 29) THEN 'Administrasi'
                   else 'Penunjang'
               END AS jenispegawai
           FROM
               pegawai_m AS pg
               LEFT JOIN jenispegawai_m AS jp ON jp.ID = pg.objectjenispegawaifk
           WHERE
               pg.statusenabled = 't'
               AND pg.kdprofile = 1
               AND (CASE WHEN pg.tglkeluar IS NULL THEN TRUE ELSE pg.tglkeluar BETWEEN '$START_DATE' AND '$END_DATE' END)
               and  (case when tglmasuk is not null then  to_char(pg.tglmasuk,'yyyy-MM-dd')  <= '$END_DATE'
               AND to_char(pg.tglkeluar,'yyyy-MM-dd') IS NULL OR to_char(pg.tglmasuk,'yyyy-MM-dd')  >= '$START_DATE' else true end)
           "
                )
            );

            $count = count($data);

            $data10 = [];
            $jml = 0;
            $sama = false;
            $dokter = 0;
            $perawat = 0;
            $penunjang = 0;
            $administrasi = 0;


            foreach ($data as $item) {
                $sama = false;
                $i = 0;
                foreach ($data10 as $hideung) {
                    if ($item->bulan == $data10[$i]['bulan']) {
                        $sama = true;
                        $jml = (float)$hideung['jumlah'] + 1;
                        $data10[$i]['jumlah'] = $jml;

                        if ($item->objectjenispegawaifk == 1) {
                            $data10[$i]['dokter'] = (float)$hideung['dokter'] + 1;
                        } else if ($item->objectjenispegawaifk == 2) {
                            $data10[$i]['perawat'] = (float)$hideung['perawat'] + 1;
                        } else  if (in_array($item->objectjenispegawaifk, [8, 29])) {
                            $data10[$i]['administrasi'] = (float)$hideung['administrasi'] + 1;
                        } else {
                            $data10[$i]['penunjang'] = (float)$hideung['penunjang'] + 1;
                        }
                    }
                    $i = $i + 1;
                }

                if ($sama == false) {
                    if ($item->objectjenispegawaifk == 1) {
                        $dokter = 1;
                    } else if ($item->objectjenispegawaifk == 2) {
                        $perawat =  1;
                    }

                    if (in_array($item->objectjenispegawaifk, [8, 29])) {
                        $administrasi =  1;
                    } else {
                        $penunjang =  1;
                    }

                    $data10[] = array(
                        'bulan' => $item->bulan,
                        'dokter' => $dokter,
                        'perawat' => $perawat,
                        'penunjang' => $penunjang,
                        'administrasi' => $administrasi,

                        'jumlah' => 1,
                    );
                }
            }
            if ($lokal == true) {
                foreach ($data10 as $rekap) {

                    $postData = [
                        "tanggal" => $rekap['bulan'],
                        "detail" => [
                            "jml_tindakan_operasi" => [
                                "perawat" => $rekap['perawat'],
                                "dokter" => $rekap['dokter'],
                                "penunjang" => $rekap['penunjang'],
                                "staff_administrasi" => $rekap['administrasi'],
                            ]
                        ]
                    ];
                    // \Log::info(json_encode($postData));
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'sdm-jml-pegawai';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;
                    $insert = $this->apiIntegrate($objetoRequest, true);

                    \Log::info("MKKO Laporan JML Peg  " . json_encode($insert));
                }
                return;
            }
            if (isset($request['closing'])) {

                $sumPerMonth = [];

                // Loop through the data array
                foreach ($data10 as $item) {
                    // Extract the month from the "bulan" key
                    $month = substr($item['bulan'], 0, 7);

                    // If the month doesn't exist in the $sumPerMonth array, initialize it with zeros
                    if (!isset($sumPerMonth[$month])) {
                        $sumPerMonth[$month] = array_fill_keys(array_keys($item), 0);
                        // Remove "bulan" key from initialization
                        unset($sumPerMonth[$month]['bulan']);
                    }

                    // Accumulate the values for each key in the corresponding month
                    foreach ($item as $key => $value) {
                        if ($key !== 'bulan') {
                            $sumPerMonth[$month][$key] += $value;
                        }
                    }
                }

                $resSUM = $sumPerMonth[$request['bulan']];
                $month = substr($request['bulan'], 5, 2);

                $arrayWithMonths = [
                    "jan" => null,
                    "feb" => null,
                    "mar" => null,
                    "apr" => null,
                    "mei" => null,
                    "jun" => null,
                    "jul" => null,
                    "agu" => null,
                    "sep" => null,
                    "okt" => null,
                    "nov" => null,
                    "des" => null,
                ];

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['dokter'];
                $arrayWithMonths['id'] = 35;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['perawat'];
                $arrayWithMonths['id'] = 36;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['penunjang'];
                $arrayWithMonths['id'] = 37;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['administrasi'];
                $arrayWithMonths['id'] = 38;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                return $this->apiPOST($dataInsert);
            }
            $msg = 'Ok';
        } catch (Exception $ex) {
            $data = [];
            $data10 = [];
            $msg = $ex->getMessage();
        }
        $result = array(
            'data' => $data10,
            'details' => $data,
            'count' => $count,
            'message' => '$msg',
        );
        return $this->respond($result);
    }

    public function jmlPendapatan(Request $request)
    {

        $idDepRanap = $this->settingFix('kdDepartemenRanapFix');
        $pasienUMUM = $this->settingFix('idKelompokPasienUMUM');
        $pasienBPJS = $this->settingFix('idKelompokPasienBPJS');
        $pasienPerusahaan = $this->settingFix('idKelompokPasienPerusahaan');
        $pasienAsuransi = $this->settingFix('idKelompokPasienAsuransi');
        $eksekutif = $this->settingFix('idJenisPelayananEksek');
        $reguler = $this->settingFix('idJenisPelayananReguler');
        $idDepRajal = $this->settingFix('kdDepartemenRawatJalanFix');

        $kdProfile = $this->kdProfile;

        $data = DB::select(DB::raw($request['query']));

        // $data = DB::table('pelayananpasien_t as pp')
        //     ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
        //     ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
        //     ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
        //     ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        //     ->leftjoin('jenispelayanan_m as jp', 'jp.id', '=', 'pd.jenispelayanan')
        //     ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
        //     ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
        //     ->leftjoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
        //     ->select(
        //         'pd.noregistrasi',
        //         'ps.namapasien',
        //         'ru.namaruangan',
        //         'dp.namadepartemen',
        //         'pp.jumlah',
        //         'pd.objectkelompokpasienlastfk',
        //         'ru.objectdepartemenfk as kddepartemen',
        //         'pp.hargasatuan',
        //         'kp.kelompokpasien',
        //         'pp.tglpelayanan as tgl',
        //         'pd.jenispelayanan',
        //         'pr.namaproduk',
        //         DB::raw("
        //         CASE WHEN pp.jasa IS NOT NULL THEN pp.jasa ELSE 0 END AS jasa,
        //         CASE WHEN pp.hargadiscount IS NOT NULL THEN pp.hargadiscount ELSE 0 END AS hargadiscount,
        //         ((pp.hargasatuan * pp.jumlah) - COALESCE(pp.hargadiscount, 0) + COALESCE(pp.jasa, 0)) AS total,
        //         to_char(pp.tglpelayanan, 'YYYY-MM') as bulan")
        //     )
        //     ->where('pp.statusenabled', true)
        //     ->where('apd.statusenabled', true)
        //     ->where('pd.statusenabled', true)
        //     ->where('pp.kdprofile', $kdProfile);


        // if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
        //     $data = $data->where('pp.tglpelayanan', '>=', $request['tglAwal']);
        // }
        // if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
        //     $tgl = $request['tglAkhir'];
        //     $data = $data->where('pp.tglpelayanan', '<=', $tgl);
        // }
        // $data = $data->get();


        $data10 = [];
        $jml = 0;
        $sama = false;


        foreach ($data as $item) {
            $sama = false;
            foreach ($data10 as &$hideung) {
                if ($item->bulan == $hideung['bulan']) {
                    $sama = true;

                    if ($item->objectkelompokpasienlastfk == $pasienBPJS && in_array($item->kddepartemen, [16, 9, 45])) {
                        $hideung['jkn_ranap'] += $item->total;
                    } else if ($item->objectkelompokpasienlastfk == $pasienBPJS && in_array($item->kddepartemen, [18, 3, 27])) {
                        $hideung['jkn_rajal'] += $item->total;
                    }
                    // Eksekutif
                    if ($item->objectkelompokpasienlastfk == $pasienAsuransi && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_Asuransi_ranap'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_perusahaan_ranap'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienUMUM && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_umum_ranap'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienAsuransi && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_Asuransi_rajal'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_perusahaan_rajal'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienUMUM && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_umum_rajal'] += $item->total;
                    }
                    // Reguler
                    if ($item->objectkelompokpasienlastfk == $pasienAsuransi && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_Asuransi_ranap'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_perusahaan_ranap'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienUMUM && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_umum_ranap'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienAsuransi && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_Asuransi_rajal'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_perusahaan_rajal'] += $item->total;
                    }
                    if ($item->objectkelompokpasienlastfk == $pasienUMUM && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_umum_rajal'] += $item->total;
                    }
                    if (in_array($item->kddepartemen, [4, 12])) {
                        $hideung['lainnya'] += $item->total;
                    }
                }
            }

            if (!$sama) {
                $cek = [
                    'bulan' => $item->bulan,
                    'jkn_rajal' => 0,
                    'jkn_ranap' => 0,
                    'Eks_Asuransi_ranap' => 0,
                    'Eks_perusahaan_ranap' => 0,
                    'Eks_umum_ranap' => 0,
                    'Eks_Asuransi_rajal' => 0,
                    'Eks_perusahaan_rajal' => 0,
                    'Eks_umum_rajal' => 0,
                    'Reg_Asuransi_ranap' => 0,
                    'Reg_perusahaan_ranap' => 0,
                    'Reg_umum_ranap' => 0,
                    'Reg_Asuransi_rajal' => 0,
                    'Reg_perusahaan_rajal' => 0,
                    'Reg_umum_rajal' => 0,
                    'lainnya' => 0,

                    'jumlah' => 0,
                ];
                if ($item->objectkelompokpasienlastfk == $pasienBPJS && in_array($item->kddepartemen, [16, 9, 45])) {
                    $cek['jkn_ranap'] = $item->total;
                } else if ($item->objectkelompokpasienlastfk == $pasienBPJS && in_array($item->kddepartemen, [18, 3, 27])) {
                    $cek['jkn_rajal'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienAsuransi && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_Asuransi_ranap'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_perusahaan_ranap'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienUMUM && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_umum_ranap'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienAsuransi && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_Asuransi_rajal'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_perusahaan_rajal'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienUMUM && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_umum_rajal'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienAsuransi && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $reguler) {
                    $cek['Reg_Asuransi_ranap'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $reguler) {
                    $cek['Reg_perusahaan_ranap'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienUMUM && in_array($item->kddepartemen, [16, 9, 45]) && $item->jenispelayanan == $reguler) {
                    $cek['Reg_umum_ranap'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienAsuransi && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $reguler) {
                    $cek['Reg_Asuransi_rajal'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $reguler) {
                    $cek['Reg_perusahaan_rajal'] = $item->total;
                }
                if ($item->objectkelompokpasienlastfk == $pasienUMUM && in_array($item->kddepartemen, [18, 3, 27]) && $item->jenispelayanan == $reguler) {
                    $cek['Reg_umum_rajal'] = $item->total;
                }
                if (in_array($item->kddepartemen, [4, 12])) {
                    $hideung['lainnya'] += $item->total;
                }
                $data10[] = $cek;
            }
        }

        $result = array(
            'data' => $data10,
            'detail' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function saveMKKOTarget(Request $r)
    {
        DB::beginTransaction();
        try {
            $d = $r['data'];
            $idProfile = $this->kdProfile;
            // if ($r['norec'] == '') {
            //     $new = new MKKO;
            //     $new->norec = $new->generateNewId();
            //     $new->statusenabled = true;
            //     $new->kdprofile = $idProfile;
            // } else {
            //     $new =  MKKO::where('norec', $r['norec'])->first();
            // }
            $cek = MKKO::where('objectaccountfk', $r['objectaccountfk'])
                ->where('tahun', $r['tahun'])
                ->first();
            if (empty($cek)) {
                $new = new MKKO;
                $new->norec = $new->generateNewId();
                $new->statusenabled = true;
                $new->kdprofile = $idProfile;
            } else {
                $new =  MKKO::where('norec', $cek->norec)->first();
            }
            $new->objectaccountfk = isset($r['objectaccountfk']) ? $r['objectaccountfk'] : null;
            $new->tahun = isset($r['tahun']) ? $r['tahun'] : null;
            // $new->jan = isset($d['jan']) ? str_replace('.', '', $d['jan']) : null;
            // $new->feb = isset($d['feb']) ? str_replace('.', '', $d['feb']) : null;
            // $new->mar = isset($d['mar']) ? str_replace('.', '', $d['mar']) : null;
            // $new->apr = isset($d['apr']) ? str_replace('.', '', $d['apr']) : null;
            // $new->mei = isset($d['mei']) ? str_replace('.', '', $d['mei']) : null;
            // $new->jun = isset($d['jun']) ? str_replace('.', '', $d['jun']) : null;
            // $new->jul = isset($d['jul']) ? str_replace('.', '', $d['jul']) : null;
            // $new->agu = isset($d['agu']) ? str_replace('.', '', $d['agu']) : null;
            // $new->sep = isset($d['sep']) ? str_replace('.', '', $d['sep']) : null;
            // $new->okt = isset($d['okt']) ? str_replace('.', '', $d['okt']) : null;
            // $new->nov = isset($d['nov']) ? str_replace('.', '', $d['nov']) : null;
            // $new->des = isset($d['des']) ? str_replace('.', '', $d['des']) : null;
            $new->jan_budget = isset($d['jan_budget']) ? str_replace('.', '', $d['jan_budget']) : null;
            $new->feb_budget = isset($d['feb_budget']) ? str_replace('.', '', $d['feb_budget']) : null;
            $new->mar_budget = isset($d['mar_budget']) ? str_replace('.', '', $d['mar_budget']) : null;
            $new->apr_budget = isset($d['apr_budget']) ? str_replace('.', '', $d['apr_budget']) : null;
            $new->mei_budget = isset($d['mei_budget']) ? str_replace('.', '', $d['mei_budget']) : null;
            $new->jun_budget = isset($d['jun_budget']) ? str_replace('.', '', $d['jun_budget']) : null;
            $new->jul_budget = isset($d['jul_budget']) ? str_replace('.', '', $d['jul_budget']) : null;
            $new->agu_budget = isset($d['agu_budget']) ? str_replace('.', '', $d['agu_budget']) : null;
            $new->sep_budget = isset($d['sep_budget']) ? str_replace('.', '', $d['sep_budget']) : null;
            $new->okt_budget = isset($d['okt_budget']) ? str_replace('.', '', $d['okt_budget']) : null;
            $new->nov_budget = isset($d['nov_budget']) ? str_replace('.', '', $d['nov_budget']) : null;
            $new->des_budget = isset($d['des_budget']) ? str_replace('.', '', $d['des_budget']) : null;
            $new->tgl = date('Y-m-d H:i:s');
            $new->save();
            $transMessage = "Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => 'Ok'
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function jmlPengunjungQuery(Request $request, $lokal = false)
    {
        $kdProfile = $this->kdProfile;
        try {
            // $connection = $this->setConnection($request);
            $data = DB::select(DB::raw("SELECT
            pd.tglregistrasi,
            pd.noregistrasi,
            ps.namapasien,
            ru.namaruangan,
            CASE
                WHEN dp.ID NOT IN (16) THEN 'Rawat Jalan'
                WHEN dp.ID  IN (16) THEN 'Rawat Inap'
                ELSE dp.namadepartemen
            END AS instalasi,
            CASE
                WHEN kp.ID in (2,18) THEN 'JKN'
                WHEN kp.ID in (31,1) THEN 'Non JKN Umum'
                WHEN kp.ID in (5,22,24) THEN 'Non JKN Asuransi'
                WHEN kp.ID in (29,32) THEN 'Non JKN Perusahaan'
                ELSE 'Non JKN Umum'
            END AS jenispasien,
            CASE
                WHEN pd.objectkelasrawatfk IS NOT NULL
                     AND kp.kelompokpasien = 'BPJS'
                     AND pd.objectkelasrawatfk != pd.objectkelasfk THEN TRUE
                ELSE FALSE
            END AS status_jkn_naik_kelas,
            CASE
                WHEN jp.ID = 1 THEN 'Reguler'
                WHEN jp.ID = 2 THEN 'Eksekutif'
                ELSE ''
            END AS jenispelayanan,
            to_char(pd.tglregistrasi, 'yyyy-MM-dd') AS bulan
        FROM
            pasiendaftar_t AS pd
            INNER JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
            INNER JOIN jenispelayanan_m AS jp ON jp.ID = pd.jenispelayanan
            LEFT JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
            LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
            LEFT JOIN kelompokpasien_m AS kp ON kp.ID = pd.objectkelompokpasienlastfk
        WHERE
            pd.statusenabled = TRUE
            AND pd.kdprofile = 1
            AND pd.tglregistrasi between '$request[dari]' AND '$request[sampai]';"));


            $data10 = [];
            $jml = 0;
            $sama = false;
            $pasienUmumRJ = 0;
            $pasienUmumRI = 0;
            $pasienJKNRegRI = 0;
            $pasienJKNNonRegRI = 0;
            $pasienJKNRJ = 0;
            $pasienAsuransiRI = 0;
            $pasienAsuransiRJ = 0;
            $pasienEksUmumRI = 0;
            $pasienEksUmumRJ = 0;
            $pasienEksAsun = 0;
            $pasienPerRI = 0;
            $pasienPerRJ = 0;
            $pasienEksPer = 0;
            $pasienEksAsunRJ = 0;
            $pasienEksPerRJ = 0;
            $pasienLainnya = 0;
            $pasienJKNRJEks = 0;

            $totalRajal = 0;
            $totalRanap = 0;
            $exx = [];
            $bulan = '';
            foreach ($data as $item) {
                $sama = false;
                $i = 0;
                $bulan = $item->bulan;
                foreach ($data10 as $hideung) {
                    if ($item->bulan == $data10[$i]['bulan']) {
                        $sama = true;
                        $jml = (float)$hideung['jumlah'] + 1;
                        $data10[$i]['jumlah'] = $jml;

                        //JKN

                        if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Inap' && $item->status_jkn_naik_kelas == false) {
                            $data10[$i]['pasienJKNRegRI'] = (float)$hideung['pasienJKNRegRI'] + 1;
                        }
                        if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Inap' && $item->status_jkn_naik_kelas == true) {
                            $data10[$i]['pasienJKNNonRegRI'] = (float)$hideung['pasienJKNNonRegRI'] + 1;
                        }
                        if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Jalan') {
                            $data10[$i]['pasienJKNRJ'] = (float)$hideung['pasienJKNRJ'] + 1;
                        }
                        // Umum
                        if ($item->jenispasien == 'Non JKN Umum' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Reguler') {
                            $data10[$i]['pasienUmumRI'] = (float)$hideung['pasienUmumRI'] + 1;
                        }
                        if ($item->jenispasien == 'Non JKN Umum' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Reguler') {
                            $data10[$i]['pasienUmumRJ'] = (float)$hideung['pasienUmumRJ'] + 1;
                        }
                        //asuransi
                        if ($item->jenispasien == 'Non JKN Asuransi'   && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Reguler') {
                            $data10[$i]['pasienAsuransiRJ'] = (float)$hideung['pasienAsuransiRJ'] + 1;
                        }
                        if ($item->jenispasien == 'Non JKN Asuransi' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Reguler') {
                            $data10[$i]['pasienAsuransiRI'] = (float)$hideung['pasienAsuransiRI'] + 1;
                        }
                        //perusahaan
                        if ($item->jenispasien == 'Non JKN Perusahaan' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Reguler') {
                            $data10[$i]['pasienPerRI'] = (float)$hideung['pasienPerRI'] + 1;
                        }
                        if ($item->jenispasien == 'Non JKN Perusahaan' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Reguler') {
                            $data10[$i]['pasienPerRJ'] = (float)$hideung['pasienPerRJ'] + 1;
                        }
                        //eksekutif
                        if ($item->jenispasien == 'Non JKN Umum' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Eksekutif') {
                            $data10[$i]['pasienEksUmumRI'] = (float)$hideung['pasienEksUmumRI'] + 1;
                        }
                        if ($item->jenispasien == 'Non JKN Asuransi' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Eksekutif') {
                            $data10[$i]['pasienEksAsun'] = (float)$hideung['pasienEksAsun'] + 1;
                        }
                        if ($item->jenispasien == 'Non JKN Perusahaan' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Eksekutif') {
                            $data10[$i]['pasienEksPer'] = (float)$hideung['pasienEksPer'] + 1;
                        }
                        if ($item->jenispasien == 'Non JKN Umum' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                            $data10[$i]['pasienEksUmumRJ'] = (float)$hideung['pasienEksUmumRJ'] + 1;
                        }
                        if ($item->jenispasien == 'Non JKN Asuransi' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                            $data10[$i]['pasienEksAsunRJ'] = (float)$hideung['pasienEksAsunRJ'] + 1;
                        }
                        if ($item->jenispasien == 'Non JKN Perusahaan' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                            $data10[$i]['pasienEksPerRJ'] = (float)$hideung['pasienEksPerRJ'] + 1;
                        }
                        // if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                        //     $data10[$i]['pasienJKNRJEks'] = (float)$hideung['pasienJKNRJEks'] + 1;
                        // }
                        if ($item->instalasi == 'Lain-lain') {
                            $data10[$i]['pasienLainnya'] = (float)$hideung['pasienLainnya'] + 1;
                        }
                        if ($item->instalasi == 'Rawat Jalan') {
                            $data10[$i]['totalRajal'] = (float)$hideung['totalRajal'] + 1;
                        }
                        if ($item->instalasi == 'Rawat Inap') {
                            $data10[$i]['totalRanap'] = (float)$hideung['totalRanap'] + 1;
                        }
                    }
                    $i = $i + 1;
                }

                if ($sama == false) {
                    if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Inap' && $item->status_jkn_naik_kelas == false) {
                        $pasienJKNRegRI =  1;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Inap' && $item->status_jkn_naik_kelas == true) {
                        $pasienJKNRegRI = 0;
                        $pasienJKNNonRegRI =  1;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Umum' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Reguler') {
                        $pasienUmumRI = 1;
                        $pasienJKNNonRegRI =  0;
                        // $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Umum' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Reguler') {
                        $pasienUmumRJ = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        // $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Reguler') {
                        $pasienJKNRJ = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        // $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                        $pasienJKNRJ = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        // $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Asuransi'   && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Reguler') {
                        $pasienAsuransiRJ = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI = 0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        // $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Asuransi' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Reguler') {
                        $pasienAsuransiRI = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        // $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Perusahaan' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Reguler') {
                        $pasienPerRI = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        // $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Perusahaan' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Reguler') {
                        $pasienPerRJ = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        // $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Umum' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Eksekutif') {
                        $pasienEksUmumRI = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        // $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Umum' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                        $pasienEksUmumRJ = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        // $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Asuransi' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Eksekutif') {
                        $pasienEksAsun = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        // $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Asuransi' && $item->instalasi == 'Rawat Inap' && $item->jenispelayanan == 'Eksekutif') {
                        $pasienEksPer = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        // $pasienEksPerRJ =  0;

                    }
                    if ($item->jenispasien == 'Non JKN Asuransi' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                        $pasienEksAsunRJ = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        // $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->jenispasien == 'Non JKN Perusahaan' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                        $pasienEksPerRJ = 1;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        // $pasienEksPerRJ =  0;

                    }
                    // if ($item->jenispasien == 'JKN' && $item->instalasi == 'Rawat Jalan' && $item->jenispelayanan == 'Eksekutif') {
                    //     $pasienJKNRJEks = 1;
                    // }

                    if ($item->instalasi == 'Lain-lain') {
                        $pasienLainnya = 1;
                        $pasienEksPerRJ = 0;
                        $pasienJKNNonRegRI = 0;
                        $pasienJKNNonRegRI =  0;
                        $pasienUmumRI =  0;
                        $pasienUmumRJ =  0;
                        $pasienJKNRJ =  0;
                        $pasienAsuransiRJ =  0;
                        $pasienAsuransiRI =  0;
                        $pasienPerRI =  0;
                        $pasienPerRJ =  0;
                        $pasienEksUmumRI =  0;
                        $pasienEksUmumRJ =  0;
                        $pasienEksAsun =  0;

                        $pasienEksPer =  0;
                        $pasienEksAsunRJ =  0;
                        $pasienEksPerRJ =  0;
                    }
                    if ($item->instalasi == 'Rawat Jalan') {
                        $totalRajal = 1;
                    }

                    if ($item->instalasi == 'Rawat Inap') {
                        $totalRanap = 1;
                    }


                    $data10[] = array(
                        'bulan' => $item->bulan,
                        'pasienJKNNonRegRI' => $pasienJKNNonRegRI,
                        'pasienJKNRegRI' => $pasienJKNRegRI,
                        'pasienUmumRJ' => $pasienUmumRJ,
                        'pasienUmumRI' => $pasienUmumRI,
                        'pasienAsuransiRJ' => $pasienAsuransiRJ,
                        'pasienJKNRJ' => $pasienJKNRJ,
                        'pasienAsuransiRI' => $pasienAsuransiRI,
                        'pasienPerRI' => $pasienPerRI,
                        'pasienPerRJ' => $pasienPerRJ,
                        'pasienEksUmumRI' => $pasienEksUmumRI,
                        'pasienEksUmumRJ' => $pasienEksUmumRJ,
                        'pasienEksAsun' => $pasienEksAsun,
                        'pasienEksPer' => $pasienEksPer,
                        'pasienEksAsunRJ' => $pasienEksAsunRJ,
                        'pasienEksPerRJ' => $pasienEksPerRJ,
                        'pasienLainnya' => $pasienLainnya,
                        // 'pasienJKNRJEks' => $pasienJKNRJEks,
                        'totalRajal' => $totalRajal,
                        'totalRanap' => $totalRanap,
                        'jumlah' => 1,
                    );
                }

                // foreach ($data10 as $key => $row) {
                //     $count[$key] = $row['norec_pd'];
                // }

                // array_multisort($count, SORT_ASC, $data10);
            }
            // dd($exx);
            if ($lokal == true) {
                foreach ($data10 as $rekap) {

                    $postData = [
                        "tanggal" => $rekap['bulan'],
                        "detail" => [
                            "outpatient_visit" => [
                                "jumlah_pasien_jkn" => [
                                    "jkn_reguler" => $rekap['pasienJKNRJ'],
                                    "jkn_naikkelas" => $rekap['pasienJKNNonRegRI']
                                ],
                                "jumlah_pasien_non_jkn_eksekutif" => [
                                    "asuransi" => $rekap['pasienEksAsunRJ'],
                                    "jaminan_perusahaan" => $rekap['pasienEksPerRJ'],
                                    "pembayaran_mandiri" => $rekap['pasienEksUmumRJ'],
                                ],
                                "jumlah_pasien_non_jkn_reguler" => [
                                    "asuransi" => $rekap['pasienAsuransiRJ'],
                                    "jaminan_perusahaan" => $rekap['pasienPerRJ'],
                                    "pembayaran_mandiri" => $rekap['pasienUmumRJ'],
                                ]
                            ],
                            "inpatient_visit" => [
                                "jumlah_pasien_jkn" => [
                                    "jkn_reguler" => $rekap['pasienJKNRegRI'],
                                    "jkn_naikkelas" => $rekap['pasienJKNNonRegRI']
                                ],
                                "jumlah_pasien_non_jkn_eksekutif" => [
                                    "asuransi" => $rekap['pasienEksAsun'],
                                    "jaminan_perusahaan" => $rekap['pasienEksPer'],
                                    "pembayaran_mandiri" => $rekap['pasienEksUmumRI'],
                                ],
                                "jumlah_pasien_non_jkn_reguler" => [
                                    "asuransi" => $rekap['pasienAsuransiRI'],
                                    "jaminan_perusahaan" => $rekap['pasienPerRI'],
                                    "pembayaran_mandiri" => $rekap['pasienUmumRI'],
                                ]
                            ]
                        ]
                    ];
                    // \Log::info(json_encode($postData));
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'kunjungan';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;
                    $insert = $this->apiIntegrate($objetoRequest, true);

                    \Log::info("mkko kunjungan auto " . json_encode($insert));
                }
            }
            if (isset($request['closing'])) {

                $sumPerMonth = [];

                // Loop through the data array
                foreach ($data10 as $item) {
                    // Extract the month from the "bulan" key
                    $month = substr($item['bulan'], 0, 7);

                    // If the month doesn't exist in the $sumPerMonth array, initialize it with zeros
                    if (!isset($sumPerMonth[$month])) {
                        $sumPerMonth[$month] = array_fill_keys(array_keys($item), 0);
                        // Remove "bulan" key from initialization
                        unset($sumPerMonth[$month]['bulan']);
                    }

                    // Accumulate the values for each key in the corresponding month
                    foreach ($item as $key => $value) {
                        if ($key !== 'bulan') {
                            $sumPerMonth[$month][$key] += $value;
                        }
                    }
                }

                $resSUM = $sumPerMonth[$request['bulan']];
                $month = substr($request['bulan'], 5, 2);

                $arrayWithMonths = [
                    "jan" => null,
                    "feb" => null,
                    "mar" => null,
                    "apr" => null,
                    "mei" => null,
                    "jun" => null,
                    "jul" => null,
                    "agu" => null,
                    "sep" => null,
                    "okt" => null,
                    "nov" => null,
                    "des" => null,
                ];

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienJKNNonRegRI'];
                $arrayWithMonths['id'] = 6;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienJKNRJ'];
                $arrayWithMonths['id'] = 5;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienJKNRegRI'];
                $arrayWithMonths['id'] = 16;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienUmumRJ'];
                $arrayWithMonths['id'] = 14;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienAsuransiRJ'];
                $arrayWithMonths['id'] = 12;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienUmumRI'];
                $arrayWithMonths['id'] = 24;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienAsuransiRI'];
                $arrayWithMonths['id'] = 22;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienPerRI'];
                $arrayWithMonths['id'] = 23;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienPerRJ'];
                $arrayWithMonths['id'] = 13;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienEksUmumRI'];
                $arrayWithMonths['id'] = 20;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienEksUmumRJ'];
                $arrayWithMonths['id'] = 10;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienEksAsun'];
                $arrayWithMonths['id'] = 18;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienEksPer'];
                $arrayWithMonths['id'] = 19;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienEksAsunRJ'];
                $arrayWithMonths['id'] = 8;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienEksPerRJ'];
                $arrayWithMonths['id'] = 9;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                // $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pasienLainnya'];
                // $arrayWithMonths['id'] = 5;
                // $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
                // $dataInsert[] = $arrayWithMonths;


                return $this->apiPOST($dataInsert);
            }
            $msg = 'Ok';
        } catch (Exception $ex) {
            $data = [];
            $data10 = [];
            $msg = $ex->getMessage();
        }
        $result = array(
            'details' => $data,
            'data' => $data10,
            'message' => $msg
        );
        return $this->respond($result);
    }
    function monthMap()
    {
        $monthMap = [
            '01' => 'jan',
            '02' => 'feb',
            '03' => 'mar',
            '04' => 'apr',
            '05' => 'mei',
            '06' => 'jun',
            '07' => 'jul',
            '08' => 'agu',
            '09' => 'sep',
            '10'  => 'okt',
            '11' => 'nov',
            '12' => 'des',
        ];
        return $monthMap;
    }
    public function getHeader()
    {
        $response = Http::withHeaders([
            "Content-Type" => "Application/json",
        ])
            ->withoutVerifying()
            ->withOptions(["verify" => false])
            ->post($this->url . 'auth', [
                'client_id' => 'cnN1cF9mYXRtYXdhdGk=',
                'client_key' => 'ZXBpY3Nzc3M='
            ]);
        $res = $response->json();
        $header = [
            'Authorization' => 'Bearer ' . $res['response']['token']
        ];

        return  $header;
    }
    protected  $url = 'https://dev-mkko.transmedic.co.id/api/mkko/';
    public function apiIntegrate(Request $request, $local = false)
    {
        try {
            $headers = $this->getHeader();
            $dataJsonSend = null;

            if ($request['data'] != null) {
                $dataJsonSend = $request['data'];
            }

            $methods = strtolower($request['method']);
            $url = $this->url . $request['url'];

            if (empty($dataJsonSend)) {
                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->withOptions(["verify" => false])
                    ->{$methods}($url);
            } else {
                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->withOptions(["verify" => false])
                    ->{$methods}($url, $dataJsonSend);
            }


            if ($response->ok()) {
                $transMessage = 'Sukses';
                $result = array(
                    "status" => 200,
                    "result"  => $response->json()['response']
                );
            } else {
                // return $response;
                $transMessage = $response->json()['metaData']['message'];
                $result = array(
                    "status" => 400,
                    "result"  => $response->json()['response']
                );
            }
        } catch (Exception $e) {
            $transMessage = $e->getMessage() . ' ' . $e->getline();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        if ($local) {
            return $result;
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function jmlPegawaiQuery(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::select(
            DB::raw(
                $request['query']
            )
        );

        $count = count($data);

        $data10 = [];
        $jml = 0;
        $sama = false;
        $dokter = 0;
        $perawat = 0;
        $penunjang = 0;
        $administrasi = 0;


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->bulan == $data10[$i]['bulan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    if ($item->objectjenispegawaifk == 1) {
                        $data10[$i]['dokter'] = (float)$hideung['dokter'] + 1;
                    } else if ($item->objectjenispegawaifk == 2) {
                        $data10[$i]['perawat'] = (float)$hideung['perawat'] + 1;
                    } else  if (in_array($item->objectjenispegawaifk, [8, 29])) {
                        $data10[$i]['administrasi'] = (float)$hideung['administrasi'] + 1;
                    } else {
                        $data10[$i]['penunjang'] = (float)$hideung['penunjang'] + 1;
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->objectjenispegawaifk == 1) {
                    $dokter = 1;
                } else if ($item->objectjenispegawaifk == 2) {
                    $perawat =  1;
                }

                if (in_array($item->objectjenispegawaifk, [8, 29])) {
                    $administrasi =  1;
                } else {
                    $penunjang =  1;
                }

                $data10[] = array(
                    'bulan' => $item->bulan,
                    'dokter' => $dokter,
                    'perawat' => $perawat,
                    'penunjang' => $penunjang,
                    'administrasi' => $administrasi,

                    'jumlah' => 0,
                );
            }
        }

        $result = array(
            'data' => $data10,
            'details' => $data,
            'count' => $count,
            'message' => '@epic',
        );
        return $this->respond($result);
    }
    public function jmlPendapatanQuery(Request $request, $lokal = false)
    {

        $idDepRanap = $this->settingFix('kdDepartemenRanapFix');
        $pasienUMUM = $this->settingFix('idKelompokPasienUMUM');
        $pasienBPJS = $this->settingFix('idKelompokPasienBPJS');
        $pasienPerusahaan = $this->settingFix('idKelompokPasienPerusahaan');
        $pasienAsuransi = $this->settingFix('idKelompokPasienAsuransi');
        $eksekutif = $this->settingFix('idJenisPelayananEksek');
        $reguler = $this->settingFix('idJenisPelayananReguler');
        $idDepRajal = $this->settingFix('kdDepartemenRawatJalanFix');

        $kdProfile = $this->kdProfile;
        // $data = DB::select(DB::raw($request['query']));
        $START_DATE = $request['dari'];
        $END_DATE = $request['sampai'];
        // $data = DB::select(DB::raw(
        //     "
        //     select totalsetujui/totalbiaya*total as proporsi,* from (
        //     SELECT

        //     case when pd.objectkelompokpasienlastfk=$pasienBPJS then
        //     (case when  mk.totalsetujui is null  and  sbm.keteranganlainnya ='Pembayaran Cicilan Tagihan Pasien Collecting'
        //     then sp.totalhargasatuan else sbm.totaldibayar end)
        //     else sbm.totaldibayar end as totalsetujui,
        //     case when spp.totalbiaya is null then mk.totaltarifrs else spp.totalbiaya end as totalbiaya,
        //                               sbm.nosbm,sbm.totaldibayar,
        //     pd.noregistrasi,ps.nocm,ps.namapasien,pa.nosep,
        //     to_char( pd.tglpulang, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,
        //     kps.kelompokpasien,(((CASE WHEN pp.hargadijamin IS NULL THEN pp.hargajual WHEN pp.hargadijamin = 0 THEN pp.hargajual ELSE pp.hargadijamin END -	CASE WHEN pp.                hargadiscount IS NULL THEN	0 ELSE pp.hargadiscount END) * pp.jumlah)
        //     + CASE WHEN pp.jasa IS NULL THEN	0 ELSE pp.jasa END) AS total,
        //     pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pd.tglpulang, 'yyyy-MM-dd' ) AS bulan,
        //     prd.namaproduk
        //     FROM pelayananpasien_t AS pp
        //     INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        //     INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
        //     INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //     LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        //     LEFT JOIN  strukpelayanan_t as sp on sp.noregistrasifk=pd.norec
        //     LEFT JOIN  strukpelayananpenjamin_t as spp on sp.norec=spp.nostrukfk
        //     LEFT JOIN  strukbuktipenerimaan_t as sbm on sbm.norec=sp.nosbmlastfk
        //     LEFT JOIN  monitoringklaim_t as mk on mk.nosep=pa.nosep
        //     INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        //     INNER JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
        //     LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        //     LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        //     WHERE  pd.tglpulang BETWEEN '$START_DATE' AND  '$END_DATE'
        //     AND pp.strukresepfk IS NULL AND pd.statusenabled = TRUE AND pp.statusenabled = TRUE
        //     AND pd.tglpulang IS NOT NULL


        //     UNION ALL

        //     SELECT

        //     case when pd.objectkelompokpasienlastfk=$pasienBPJS then
        //     (case when  mk.totalsetujui is null  and  sbm.keteranganlainnya ='Pembayaran Cicilan Tagihan Pasien Collecting'
        //     then sp.totalhargasatuan else sbm.totaldibayar end)
        //     else sbm.totaldibayar end as totalsetujui,
        //     case when spp.totalbiaya is null then mk.totaltarifrs else spp.totalbiaya end as totalbiaya,
        //                           sbm.nosbm,sbm.totaldibayar,
        //     pd.noregistrasi,ps.nocm,ps.namapasien,pa.nosep,
        //     to_char( pd.tglpulang, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
        //     (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
        //     CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS total,
        //      pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pd.tglpulang, 'yyyy-MM-dd' ) AS bulan,
        //      prd.namaproduk
        //     FROM strukresep_t AS sr
        //     INNER JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
        //     INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        //     INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        //     INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //     LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        //     LEFT JOIN  strukpelayanan_t as sp on sp.noregistrasifk=pd.norec
        //     LEFT JOIN  strukpelayananpenjamin_t as spp on sp.norec=spp.nostrukfk
        //     LEFT JOIN  strukbuktipenerimaan_t as sbm on sbm.norec=sp.nosbmlastfk
        //     LEFT JOIN  monitoringklaim_t as mk on mk.nosep=pa.nosep

        //     INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        //     INNER JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
        //     LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        //     LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        //     WHERE  pd.statusenabled = TRUE AND pp.statusenabled = TRUE AND sr.statusenabled = TRUE
        //     AND pd.tglpulang BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL AND pd.tglpulang IS NOT NULL
        //     AND pp.jumlah > 0 and dpm.statusenabled = true

        //     UNION ALL

        //     SELECT

        //     case when pd.objectkelompokpasienlastfk=$pasienBPJS then
        //     (case when  mk.totalsetujui is null  and  sbm.keteranganlainnya ='Pembayaran Cicilan Tagihan Pasien Collecting'
        //     then sp.totalhargasatuan else sbm.totaldibayar end)
        //     else sbm.totaldibayar end as totalsetujui,
        //     case when spp.totalbiaya is null then mk.totaltarifrs else spp.totalbiaya end as totalbiaya,
        //                          sbm.nosbm,sbm.totaldibayar,
        //     pd.noregistrasi,ps.nocm,ps.namapasien,pa.nosep,
        //     to_char( pd.tglpulang, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
        //     (((CASE WHEN pps.hargajual IS NULL THEN 0 ELSE pps.hargajual END - CASE WHEN pps.hargadiscount IS NULL THEN 0 ELSE pps.hargadiscount END) * pp.jumlah) +
        //     CASE WHEN pps.jasa IS NULL THEN 0 ELSE pps.jasa END) AS total,
        //     pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pd.tglpulang, 'yyyy-MM-dd' ) AS bulan,
        //     prd.namaproduk
        //     FROM strukresep_t AS sr
        //     INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
        //     INNER JOIN pelayananpasien_t AS pps ON pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
        //     INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        //     INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        //     INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //     LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        //     LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        //     LEFT JOIN  strukpelayanan_t as sp on sp.noregistrasifk=pd.norec
        //     LEFT JOIN  strukpelayananpenjamin_t as spp on sp.norec=spp.nostrukfk
        //     LEFT JOIN  strukbuktipenerimaan_t as sbm on sbm.norec=sp.nosbmlastfk
        //     LEFT JOIN  monitoringklaim_t as mk on mk.nosep=pa.nosep
        //     INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        //     INNER JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
        //     LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        //     WHERE  pd.statusenabled = TRUE AND pps.statusenabled = TRUE AND sr.statusenabled = TRUE
        //     AND pd.tglpulang BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
        //     AND pp.jumlah > 0 and dpm.statusenabled = true AND pd.tglpulang IS NOT NULL


        //     UNION ALL

        //     select 	 x.total as totalsetujui,
        //     x.total as totalbiaya, x.* from (
        //     SELECT sbm.nosbm,sbm.totaldibayar,
        //     case when ps.namapasien is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
        //     case when ps.namapasien is null then sp.nostruk_intern else ps.nocm end as nocm,
        //     case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien, null as nosep,
        //     to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dp.namadepartemen,'Umum/Pribadi' AS kelompokpasien,
        //     (spd.qtyproduk * ( spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END ) +
        //     CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
        //     1 as objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,1 as jenispelayanan,		to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan,
        //     prd.namaproduk
        //     FROM strukpelayanan_t AS sp
        //     INNER JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        //                         LEFT JOIN  strukbuktipenerimaan_t as sbm on sbm.norec=sp.nosbmlastfk
        //     LEFT JOIN produk_m AS prd ON prd.id = spd.objectprodukfk
        //     LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
        //     LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
        //     LEFT JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        //     LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
        //     WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
        //     AND sp.nostruk LIKE'OB%' AND sp.statusenabled = true AND spd.qtyproduk > 0 and dp.statusenabled = true

        //     UNION ALL

        //     SELECT
        //        sbm.nosbm,sbm.totaldibayar,
        //     case when ps.namapasien is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
        //     case when ps.namapasien is null then sp.nostruk_intern else ps.nocm end as nocm,
        //     case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,null as nosep,
        //     to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,'Non Layanan' AS namaruangan,'Instalasi Rawat Jalan dan Rehabilitasi Medik' AS namadepartemen,
        //     'Umum/Pribadi' AS kelompokpasien,(spd.qtyproduk * (spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END) +
        //     CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
        //     1 as objectkelompokpasienlastfk,18 as kddepartemen, 1 as jenispelayanan,	to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan,
        //     prd.namaproduk
        //     FROM strukpelayanan_t AS sp
        //     INNER JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        //                         LEFT JOIN  strukbuktipenerimaan_t as sbm on sbm.norec=sp.nosbmlastfk
        //     LEFT JOIN produk_m AS prd ON prd.id = spd.objectprodukfk
        //     LEFT JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        //     LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
        //     WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
        //     AND substring(sp.nostruk,0,3) = 'NL' AND sp.statusenabled = true
        //                         ) as x ) as y

        //     "
        // ));
        // $data = DB::select(DB::raw(
        //     "
        //     select x.*,
        //     case when x.objectkelompokpasienlastfk = $pasienBPJS then mk.totalpengajuan else x.total end pengajuan,
        //     case when x.objectkelompokpasienlastfk = $pasienBPJS then mk.totalsetujui else x.total end klaim,
        //     mk.totaltarifrs as totalbilling,
        //     case when x.objectkelompokpasienlastfk = $pasienBPJS then
        //     (case when mk.totalsetujui is not null then round(( mk.totalsetujui / mk.totaltarifrs * x.total)::numeric ,2) else 0 end)
        //     else x.total end
        //     as proporsi
        //     from ( SELECT
        //             pd.noregistrasi,ps.nocm,ps.namapasien,pa.nosep,
        //             to_char( pd.tglpulang, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,
        //             kps.kelompokpasien,(((CASE WHEN pp.hargadijamin IS NULL THEN pp.hargajual WHEN pp.hargadijamin = 0 THEN pp.hargajual ELSE pp.hargadijamin END -	CASE WHEN pp.                hargadiscount IS NULL THEN	0 ELSE pp.hargadiscount END) * pp.jumlah)
        //             + CASE WHEN pp.jasa IS NULL THEN	0 ELSE pp.jasa END) AS total,
        //             pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pd.tglpulang, 'yyyy-MM-dd' ) AS bulan,
        //             prd.namaproduk
        //             FROM pelayananpasien_t AS pp
        //             INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        //             INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
        //             INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //             LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        //             INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        //             INNER JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
        //             LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        //             LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        //             WHERE  pd.tglpulang BETWEEN '$START_DATE' AND  '$END_DATE'
        //             AND pp.strukresepfk IS NULL AND pd.statusenabled = TRUE AND pp.statusenabled = TRUE and dpm.statusenabled = true AND pd.tglpulang IS NOT NULL

        //             UNION ALL

        //             SELECT
        //             pd.noregistrasi,ps.nocm,ps.namapasien,pa.nosep,
        //             to_char( pd.tglpulang, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
        //             (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
        //             CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS total,
        //              pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pd.tglpulang, 'yyyy-MM-dd' ) AS bulan,
        //              prd.namaproduk
        //             FROM strukresep_t AS sr
        //             INNER JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
        //             INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        //             INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        //             INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //             LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        //             INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        //             INNER JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
        //             LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        //             LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        //             WHERE  pd.statusenabled = TRUE AND pp.statusenabled = TRUE AND sr.statusenabled = TRUE
        //             AND pd.tglpulang BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL AND pd.tglpulang IS NOT NULL
        //             AND pp.jumlah > 0 and dpm.statusenabled = true

        //             UNION ALL

        //             SELECT
        //             pd.noregistrasi,ps.nocm,ps.namapasien,pa.nosep,
        //             to_char( pd.tglpulang, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
        //             (((CASE WHEN pps.hargajual IS NULL THEN 0 ELSE pps.hargajual END - CASE WHEN pps.hargadiscount IS NULL THEN 0 ELSE pps.hargadiscount END) * pp.jumlah) +
        //             CASE WHEN pps.jasa IS NULL THEN 0 ELSE pps.jasa END) AS total,
        //             pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pd.tglpulang, 'yyyy-MM-dd' ) AS bulan,
        //             prd.namaproduk
        //             FROM strukresep_t AS sr
        //             INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
        //             INNER JOIN pelayananpasien_t AS pps ON pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
        //             INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        //             INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        //             INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //             LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        //             LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        //             INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        //             INNER JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
        //             LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        //             WHERE  pd.statusenabled = TRUE AND pps.statusenabled = TRUE AND sr.statusenabled = TRUE
        //             AND pd.tglpulang BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
        //             AND pp.jumlah > 0 and dpm.statusenabled = true AND pd.tglpulang IS NOT NULL
        // 	) as x
        // 	left join monitoringklaim_t as mk on mk.nosep =x.nosep

        //     UNION ALL

        //     select *,total as pengajuan,total as klaim,total as totalbilling,total as proporsi
        //      from (
        //     SELECT
        //     case when ps.namapasien is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
        //     case when ps.namapasien is null then sp.nostruk_intern else ps.nocm end as nocm,
        //     case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien, null as nosep,
        //     to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dp.namadepartemen,'Umum/Pribadi' AS kelompokpasien,
        //     (spd.qtyproduk * ( spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END ) +
        //     CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
        //     1 as objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,1 as jenispelayanan,		to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan,
        //     prd.namaproduk
        //     FROM strukpelayanan_t AS sp
        //     INNER JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        //     LEFT JOIN produk_m AS prd ON prd.id = spd.objectprodukfk
        //     LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
        //     LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
        //     LEFT JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        //     LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
        //     WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
        //     AND sp.nostruk LIKE'OB%' AND sp.statusenabled = true AND spd.qtyproduk > 0 and dp.statusenabled = true

        //     UNION ALL

        //     SELECT
        //     case when ps.namapasien is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
        //     case when ps.namapasien is null then sp.nostruk_intern else ps.nocm end as nocm,
        //     case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,null as nosep,
        //     to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,'Non Layanan' AS namaruangan,'Instalasi Rawat Jalan dan Rehabilitasi Medik' AS namadepartemen,
        //     'Umum/Pribadi' AS kelompokpasien,(spd.qtyproduk * (spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END) +
        //     CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
        //     1 as objectkelompokpasienlastfk,18 as kddepartemen, 1 as jenispelayanan,	to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan,
        //     prd.namaproduk
        //     FROM strukpelayanan_t AS sp
        //     INNER JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        //     LEFT JOIN produk_m AS prd ON prd.id = spd.objectprodukfk
        //     LEFT JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        //     LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
        //     WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
        //     AND substring(sp.nostruk,0,3) = 'NL' AND sp.statusenabled = true
        //     ) as x
        //     "
        // ));

        $data = DB::select(DB::raw("
        select x.*,
        case when x.objectkelompokpasienlastfk in ($pasienBPJS) then mk.totalpengajuan else x.total end pengajuan,
        case when x.objectkelompokpasienlastfk in ($pasienBPJS) then mk.totalsetujui else x.total end klaim,
        mk.totaltarifrs as totalbilling,
        case when x.objectkelompokpasienlastfk in ($pasienBPJS) then
        (case when mk.totalsetujui is not null then round(( mk.totalsetujui / mk.totaltarifrs * x.total)::numeric ,2) else 0 end)
        else x.total end
        as proporsi
        from (
        SELECT
        pd.noregistrasi,ps.nocm,ps.namapasien,
        to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,
             kps.kelompokpasien,(((CASE WHEN pp.hargadijamin IS NULL THEN pp.hargajual WHEN pp.hargadijamin = 0 THEN pp.hargajual ELSE pp.hargadijamin END -	CASE WHEN pp.hargadiscount IS NULL THEN	0 ELSE pp.hargadiscount END) * pp.jumlah)
             + CASE WHEN pp.jasa IS NULL THEN	0 ELSE pp.jasa END) AS total,
             pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan,
             prd.namaproduk,pa.nosep
        FROM pelayananpasien_t AS pp
        INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
        JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
        LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        WHERE  pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'
        AND pp.strukresepfk IS NULL AND pd.statusenabled = TRUE AND pp.statusenabled = TRUE and dpm.statusenabled = true
        --AND pd.tglpulang IS NOT NULL

        UNION ALL

        SELECT
        pd.noregistrasi,ps.nocm,ps.namapasien,
        to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
             (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
             CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS total,
                                    pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan,
                                    prd.namaproduk,pa.nosep
        FROM strukresep_t AS sr
        JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
        INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
        LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        WHERE  pd.statusenabled = TRUE AND pp.statusenabled = TRUE AND sr.statusenabled = TRUE
        AND pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
        --AND pd.tglpulang IS NOT NULL
        AND pp.jumlah > 0 and dpm.statusenabled = true

        UNION ALL

        SELECT
        pd.noregistrasi,ps.nocm,ps.namapasien,
        to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
             (((CASE WHEN pps.hargajual IS NULL THEN 0 ELSE pps.hargajual END - CASE WHEN pps.hargadiscount IS NULL THEN 0 ELSE pps.hargadiscount END) * pp.jumlah) +
             CASE WHEN pps.jasa IS NULL THEN 0 ELSE pps.jasa END) AS total,
                                    pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan,
                                    prd.namaproduk,pa.nosep
        FROM strukresep_t AS sr
        INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
        INNER JOIN pelayananpasien_t AS pps ON pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
        INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
        LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        WHERE  pd.statusenabled = TRUE AND pps.statusenabled = TRUE AND sr.statusenabled = TRUE
        AND pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
        AND pp.jumlah > 0 and dpm.statusenabled = true
        --AND pd.tglpulang IS NOT NULL

        ) as x
		left join monitoringklaim_t as mk on mk.nosep =x.nosep

        UNION ALL

        select *,total as pengajuan,total as klaim,total as totalbilling,total as proporsi
        from (
        SELECT
        case when ps.namapasien is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
             case when ps.namapasien is null then sp.nostruk_intern else ps.nocm end as nocm,
             case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
        to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dp.namadepartemen,'Umum/Pribadi' AS kelompokpasien,
        (spd.qtyproduk * ( spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END ) +
        CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
                              1 as objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,1 as jenispelayanan,		to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan,
                              prd.namaproduk,null as nosep
        FROM strukpelayanan_t AS sp
        JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        LEFT JOIN produk_m AS prd ON prd.id = spd.objectprodukfk
        LEFT JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
        LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
        LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
        WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
        AND sp.nostruk LIKE'OB%' AND sp.statusenabled = true AND spd.qtyproduk > 0 and dp.statusenabled = true

        UNION ALL

        SELECT
        case when ps.namapasien is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
           case when ps.namapasien is null then sp.nostruk_intern else ps.nocm end as nocm,
           case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
        to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,'Non Layanan' AS namaruangan,'Instalasi Rawat Jalan dan Rehabilitasi Medik' AS namadepartemen,
             'Umum/Pribadi' AS kelompokpasien,(spd.qtyproduk * (spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END) +
             CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
                                    1 as objectkelompokpasienlastfk,18 as kddepartemen, 1 as jenispelayanan,	to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan,
                                    prd.namaproduk,null as nosep
        FROM strukpelayanan_t AS sp
        JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        LEFT JOIN produk_m AS prd ON prd.id = spd.objectprodukfk
        LEFT JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
        WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
        AND substring(sp.nostruk,0,3) = 'NL' AND sp.statusenabled = true
        ) as z
        "));
        $data10 = [];
        $jml = 0;
        $sama = false;
        $jkn_ranap = 0;
        $jkn_rajal = 0;
        $Eks_Asuransi_ranap = 0;
        $Eks_perusahaan_ranap = 0;
        $Eks_umum_ranap = 0;
        $Eks_Asuransi_rajal = 0;
        $Eks_perusahaan_rajal = 0;
        $Eks_umum_rajal = 0;
        $Reg_Asuransi_ranap = 0;
        $Reg_perusahaan_ranap = 0;
        $Reg_umum_ranap = 0;
        $Reg_Asuransi_rajal = 0;
        $Reg_perusahaan_rajal = 0;
        $Reg_umum_rajal = 0;

        foreach ($data as $item) {
            $item->total = round((float) $item->total, 2);
            $item->proporsi = round((float) $item->proporsi, 2);
            $item->klaim = round((float) $item->klaim, 2);
            $item->pengajuan = round((float)  $item->pengajuan, 2);
            $sama = false;
            foreach ($data10 as $i => &$hideung) {
                $jml = (float)$hideung['jumlah'] + $item->total;
                $data10[$i]['jumlah'] = $jml;
                if ($item->bulan == $hideung['bulan']) {
                    $sama = true;

                    if ($item->objectkelompokpasienlastfk == $pasienBPJS && $item->kddepartemen == $idDepRanap) {
                        $hideung['jkn_ranap'] += (float)$item->total;
                    } else if ($item->objectkelompokpasienlastfk == $pasienBPJS && $item->kddepartemen == $idDepRajal) {
                        $hideung['jkn_rajal'] += (float)$item->total;
                    }
                    // Eksekutif
                    else if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_Asuransi_ranap'] += (float)$item->total;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_perusahaan_ranap'] += (float)$item->total;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_umum_ranap'] += (float) $item->total;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_Asuransi_rajal'] += (float)$item->total;
                    } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_perusahaan_rajal'] += (float)$item->total;
                    } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_umum_rajal'] += (float) $item->total;
                    }
                    // Reguler
                    else   if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_Asuransi_ranap'] += (float)$item->total;
                    } else    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_perusahaan_ranap'] += (float) $item->total;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_umum_ranap'] += (float)$item->total;
                    } else  if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_Asuransi_rajal'] += (float)$item->total;
                    } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_perusahaan_rajal'] += (float)$item->total;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_umum_rajal'] += (float) $item->total;
                    } else {
                        $hideung['pendapatan_lain'] += (float)$item->total;
                    }
                }
            }

            if (!$sama) {
                $cek = [
                    'bulan' => $item->bulan,
                    'jkn_rajal' => 0,
                    'jkn_ranap' => 0,
                    'Eks_Asuransi_ranap' => 0,
                    'Eks_perusahaan_ranap' => 0,
                    'Eks_umum_ranap' => 0,
                    'Eks_Asuransi_rajal' => 0,
                    'Eks_perusahaan_rajal' => 0,
                    'Eks_umum_rajal' => 0,
                    'Reg_Asuransi_ranap' => 0,
                    'Reg_perusahaan_ranap' => 0,
                    'Reg_umum_ranap' => 0,
                    'Reg_Asuransi_rajal' => 0,
                    'Reg_perusahaan_rajal' => 0,
                    'Reg_umum_rajal' => 0,
                    'pendapatan_lain' => 0,
                    'jumlah' => (float) $item->total,
                ];
                if ($item->objectkelompokpasienlastfk == $pasienBPJS && $item->kddepartemen == $idDepRanap) {
                    $cek['jkn_ranap'] = (float) $item->total;
                } else if ($item->objectkelompokpasienlastfk == $pasienBPJS && $item->kddepartemen == $idDepRajal) {
                    $cek['jkn_rajal'] = (float) $item->total;
                } else if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_Asuransi_ranap'] = (float)$item->total;
                } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_perusahaan_ranap'] = (float)$item->total;
                } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_umum_ranap'] = (float)$item->total;
                } else if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_Asuransi_rajal'] = (float)$item->total;
                } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_perusahaan_rajal'] = (float) $item->total;
                } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_umum_rajal'] = (float)$item->total;
                } else  if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $cek['Reg_Asuransi_ranap'] = (float) $item->total;
                } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $cek['Reg_perusahaan_ranap'] = (float)$item->total;
                } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $cek['Reg_umum_ranap'] = (float)$item->total;
                } else if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $cek['Reg_Asuransi_rajal'] = (float)$item->total;
                } else if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $cek['Reg_perusahaan_rajal'] = (float) $item->total;
                } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $cek['Reg_umum_rajal'] = (float)$item->total;
                } else {
                    $cek['pendapatan_lain'] = (float)$item->total;
                }

                $data10[] = $cek;
            }
        }


        foreach ($data10 as $key => $value) {
            $keyS = array_keys($data10[$key]);

            foreach ($keyS as $d) {
                if ($d != 'bulan') {
                    $data10[$key][$d] = round($data10[$key][$d], 2);
                }
            }
        }

        if ($lokal == true || isset($request['ebitda'])) {
            $objetoRequest = new \Illuminate\Http\Request();

            $objetoRequest['dari'] = $request['dari'];
            $objetoRequest['sampai'] =  $request['sampai'];
            $beban = $this->laporanBebanUsaha($objetoRequest, true);
            $resBeban = null;
            if (count($beban) > 0) {
                $resBeban = $beban[0];
            }
            $penL = $this->jmlPendapatanKeuangan($objetoRequest, true);
            $resPenL = null;
            if (count($penL) > 0) {
                $resPenL = $penL[0];
            }
            $SURPLUS_SEBELUM_PAJAK = 0;
            if (count($data10)) {
                $SURPLUS_SEBELUM_PAJAK =  ($data10[0]['jumlah'] - $resBeban['jumlah']) -
                    (!empty($resPenL) ? $resPenL['pendapatan_blu_lainnya'] + $resPenL['pendapatan_hibah'] + $resPenL['pend_apbn_lainnya'] : 0);
            }
            if (isset($request['closing'])) {
                // return $this->saveBebanPendapatan($request,$data10);
            } else {
                foreach ($data10 as $element) {

                    // Prepare the data to be sent in the POST request
                    $postData = [

                        "tanggal" => $element['bulan'],
                        "detail" => [
                            "pendapatan_rs" => [
                                "outpatient_revenue" => [
                                    "pasien_jkn" => [
                                        "jkn_reguler" => number_format($element['jkn_rajal'], 2, '.', ''),
                                        "jkn_naikkelas" => 0
                                    ],
                                    "pasien_non_jkn_eksekutif" => [
                                        "asuransi" => number_format($element['Eks_Asuransi_rajal'], 2, '.', ''),
                                        "jaminan_perusahaan" => number_format($element['Eks_perusahaan_rajal'], 2, '.', ''),
                                        "pembayaran_mandiri" => number_format($element['Eks_umum_rajal'], 2, '.', '')
                                    ],
                                    "pasien_non_jkn_reguler" => [
                                        "asuransi" => number_format($element['Reg_Asuransi_rajal'], 2, '.', ''),
                                        "jaminan_perusahaan" => number_format($element['Reg_perusahaan_rajal'], 2, '.', ''),
                                        "pembayaran_mandiri" => number_format($element['Reg_umum_rajal'], 2, '.', '')
                                    ]
                                ],
                                "inpatient_revenue" => [
                                    "pasien_jkn" => [
                                        "jkn_reguler" => number_format($element['jkn_ranap'], 2, '.', ''),
                                        "jkn_naikkelas" => 0
                                    ],
                                    "pasien_non_jkn_eksekutif" => [
                                        "asuransi" => number_format($element['Eks_Asuransi_ranap'], 2, '.', ''),
                                        "jaminan_perusahaan" => number_format($element['Eks_perusahaan_ranap'], 2, '.', ''),
                                        "pembayaran_mandiri" => number_format($element['Eks_umum_ranap'], 2, '.', '')
                                    ],
                                    "pasien_non_jkn_reguler" => [
                                        "asuransi" => number_format($element['Reg_Asuransi_ranap'], 2, '.', ''),
                                        "jaminan_perusahaan" => number_format($element['Reg_perusahaan_ranap'], 2, '.', ''),
                                        "pembayaran_mandiri" => number_format($element['Reg_umum_ranap'], 2, '.', '')
                                    ]
                                ],
                                "pendapatan_layanan_lain" => number_format($element['pendapatan_lain'], 2, '.', '')
                            ],
                            "rba_pendapatan" => 0,
                            "beban_pokok_pendapatan" => [
                                "beban_pegawai" => !empty($resBeban) ? number_format($resBeban['bebanPegBLU'], 2, '.', '') : 0,
                            ],
                            "beban_administrasi_umum" => [
                                "beban_barang_jasa" => !empty($resBeban) ? number_format($resBeban['bebanBarangJasa'], 2, '.', '') : 0,
                                "beban_pemeliharaan" => !empty($resBeban) ? number_format($resBeban['bebanPemeliharaan'], 2, '.', '') : 0,
                                "beban_perjalanan_dinas" =>  !empty($resBeban) ? number_format($resBeban['bebanPerdin'], 2, '.', '') : 0,
                                "beban_penyisihan_piutang_tak_tertagih" => !empty($resBeban) ? number_format($resBeban['bebanPenyisihanPiut'], 2, '.', '') : 0,
                            ],
                            "beban_persediaan" => [
                                "beban_persediaan_farmasi" =>  !empty($resBeban) ? number_format($resBeban['bebanPersediaanF'], 2, '.', '') : 0,
                                "beban_persediaan_non_farmasi" => !empty($resBeban) ? number_format($resBeban['bebanPersediaanNonF'], 2, '.', '') : 0,
                            ],


                            "beban_penyusutan_dan_amortisasi" =>  !empty($resBeban) ? number_format($resBeban['bebanamorti'], 2, '.', '') : 0,
                            "surplus_defisit_usaha" => number_format(($element['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0)), 2, '.', ''),
                            "depresiasi_amortisasi" =>  !empty($resBeban) ? number_format($resBeban['bebanamorti'], 2, '.', '') : 0,
                            "EBITDA" => ($element['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0)) + $resBeban['bebanamorti'],
                            "beban_pegawai" => !empty($resBeban) ? number_format($resBeban['bebanPegAPBN'], 2, '.', '') : 0,
                            "EBITDA_plus_beban_pegawai" => ($element['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0)) +  (!empty($resBeban) ? $resBeban['bebanamorti'] - $resBeban['bebanPegAPBN'] : 0),
                            "pendapatan_keuangan" => [
                                "pendapatan_bunga_bank" => !empty($resPenL) ? number_format($resPenL['pendapatan_bunga_bank'], 2, '.', '') : 0,
                                "deposito" =>  !empty($resPenL) ? number_format($resPenL['deposito'], 2, '.', '') : 0,
                                "pend_lainnya" => !empty($resPenL) ? number_format($resPenL['pend_lainnya'], 2, '.', '') : 0,
                                "jumlah_pendapatan_keuangan" => !empty($resPenL) ? number_format($resPenL['pendapatan_bunga_bank'] + $resPenL['deposito'] + $resPenL['pend_lainnya'], 2, '.', '') : 0,
                            ],
                            "biaya_keuangan" => 0,
                            "pendapatan_biaya_lain_lain" => [
                                "pend_apbn_lainnya" => !empty($resPenL) ? number_format($resPenL['pend_apbn_lainnya'], 2, '.', '') : 0,
                                "pendapatan_hibah" => !empty($resPenL) ? number_format($resPenL['pendapatan_hibah'], 2, '.', '') : 0,
                                "pendapatan_blu_lainnya" => !empty($resPenL) ? number_format($resPenL['pendapatan_blu_lainnya'], 2, '.', '') : 0,
                                "jumlah_pendapatan_lain_lain" => !empty($resPenL) ? number_format($resPenL['pendapatan_blu_lainnya'] + $resPenL['pendapatan_hibah'] + $resPenL['pend_apbn_lainnya'], 2, '.', '') : 0,
                            ],
                            "surplus_usaha_sebelum_pajak" => $SURPLUS_SEBELUM_PAJAK,
                            "manfaat_beban_pajak" => !empty($resPenL) ? number_format($resPenL['manfaat_beban'], 2, '.', '') : 0,
                            "surplus_bersih" => $SURPLUS_SEBELUM_PAJAK
                        ]
                    ];

                    // Convert the data to JSON format
                    $postDataJson = json_encode($postData);
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'keuangan';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;


                    $insert = $this->apiIntegrate($objetoRequest, true);
                    \Log::info("mkko pendapatan auto " . json_encode($insert));
                }
            }
        }
        $result = array(
            'data' => $data10,
            'detail' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }
    public function jmlPendapatanBAHV(Request $request, $lokal = false)
    {

        $idDepRanap = $this->settingFix('kdDepartemenRanapFix');
        $pasienUMUM = $this->settingFix('idKelompokPasienUMUM');
        $pasienBPJS = $this->settingFix('idKelompokPasienBPJS');
        $pasienPerusahaan = $this->settingFix('idKelompokPasienPerusahaan');
        $pasienAsuransi = $this->settingFix('idKelompokPasienAsuransi');
        $eksekutif = $this->settingFix('idJenisPelayananEksek');
        $reguler = $this->settingFix('idJenisPelayananReguler');
        $idDepRajal = $this->settingFix('kdDepartemenRawatJalanFix');

        $kdProfile = $this->kdProfile;
        $START_DATE = $request['dari'];
        $END_DATE = $request['sampai'];

        $data = DB::select(DB::raw("
        select x.*,
        case when x.objectkelompokpasienlastfk in ($pasienBPJS) then mk.totalpengajuan else x.total end pengajuan,
        case when x.objectkelompokpasienlastfk in ($pasienBPJS) then mk.totalsetujui else x.total end klaim,
        mk.totaltarifrs as totalbilling,
        case when x.objectkelompokpasienlastfk in ($pasienBPJS) then
        (case when mk.totalsetujui is not null then round(( mk.totalsetujui / mk.totaltarifrs * x.total)::numeric ,2) else 0 end)
        else x.total end
        as proporsi
        from (
        SELECT
        pd.noregistrasi,ps.nocm,ps.namapasien,
        to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,
             kps.kelompokpasien,(((CASE WHEN pp.hargadijamin IS NULL THEN pp.hargajual WHEN pp.hargadijamin = 0 THEN pp.hargajual ELSE pp.hargadijamin END -	CASE WHEN pp.hargadiscount IS NULL THEN	0 ELSE pp.hargadiscount END) * pp.jumlah)
             + CASE WHEN pp.jasa IS NULL THEN	0 ELSE pp.jasa END) AS total,
             pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan,
             prd.namaproduk,pa.nosep
        FROM pelayananpasien_t AS pp
        INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
        JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
        LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        WHERE  pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'
        AND pp.strukresepfk IS NULL AND pd.statusenabled = TRUE AND pp.statusenabled = TRUE and dpm.statusenabled = true
        --AND pd.tglpulang IS NOT NULL

        UNION ALL

        SELECT
        pd.noregistrasi,ps.nocm,ps.namapasien,
        to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
             (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
             CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS total,
                                    pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan,
                                    prd.namaproduk,pa.nosep
        FROM strukresep_t AS sr
        JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
        INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
        LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        WHERE  pd.statusenabled = TRUE AND pp.statusenabled = TRUE AND sr.statusenabled = TRUE
        AND pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
        --AND pd.tglpulang IS NOT NULL
        AND pp.jumlah > 0 and dpm.statusenabled = true

        UNION ALL

        SELECT
        pd.noregistrasi,ps.nocm,ps.namapasien,
        to_char( pp.tglpelayanan, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dpm.namadepartemen,kps.kelompokpasien,
             (((CASE WHEN pps.hargajual IS NULL THEN 0 ELSE pps.hargajual END - CASE WHEN pps.hargadiscount IS NULL THEN 0 ELSE pps.hargadiscount END) * pp.jumlah) +
             CASE WHEN pps.jasa IS NULL THEN 0 ELSE pps.jasa END) AS total,
                                    pd.objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,pd.jenispelayanan,		to_char( pp.tglpelayanan, 'yyyy-MM-dd' ) AS bulan,
                                    prd.namaproduk,pa.nosep
        FROM strukresep_t AS sr
        INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
        INNER JOIN pelayananpasien_t AS pps ON pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
        INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        LEFT JOIN  pemakaianasuransi_t as pa on pa.noregistrasifk=pd.norec
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        JOIN ruangan_m AS ru ON ru.ID = sr.ruanganfk
        LEFT JOIN kelompokpasien_m AS kps ON kps.ID = pd.objectkelompokpasienlastfk
        LEFT JOIN departemen_m AS dpm ON dpm.ID = ru.objectdepartemenfk
        WHERE  pd.statusenabled = TRUE AND pps.statusenabled = TRUE AND sr.statusenabled = TRUE
        AND pp.tglpelayanan BETWEEN '$START_DATE' AND  '$END_DATE'  AND pp.strukresepfk IS NOT NULL
        AND pp.jumlah > 0 and dpm.statusenabled = true
        --AND pd.tglpulang IS NOT NULL

        ) as x
		left join monitoringklaim_t as mk on mk.nosep =x.nosep

        UNION ALL

        select *,total as pengajuan,total as klaim,total as totalbilling,total as proporsi
        from (
        SELECT
        case when ps.namapasien is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
             case when ps.namapasien is null then sp.nostruk_intern else ps.nocm end as nocm,
             case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
        to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,ru.namaruangan,dp.namadepartemen,'Umum/Pribadi' AS kelompokpasien,
        (spd.qtyproduk * ( spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END ) +
        CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
                              1 as objectkelompokpasienlastfk,ru.objectdepartemenfk as kddepartemen,1 as jenispelayanan,		to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan,
                              prd.namaproduk,null as nosep
        FROM strukpelayanan_t AS sp
        JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        LEFT JOIN produk_m AS prd ON prd.id = spd.objectprodukfk
        LEFT JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
        LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
        LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
        WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
        AND sp.nostruk LIKE'OB%' AND sp.statusenabled = true AND spd.qtyproduk > 0 and dp.statusenabled = true

        UNION ALL

        SELECT
        case when ps.namapasien is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
           case when ps.namapasien is null then sp.nostruk_intern else ps.nocm end as nocm,
           case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
        to_char( sp.tglstruk, 'yyyy-MM-dd HH24:MI' ) AS tgl,'Non Layanan' AS namaruangan,'Instalasi Rawat Jalan dan Rehabilitasi Medik' AS namadepartemen,
             'Umum/Pribadi' AS kelompokpasien,(spd.qtyproduk * (spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END) +
             CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END) AS total,
                                    1 as objectkelompokpasienlastfk,18 as kddepartemen, 1 as jenispelayanan,	to_char( sp.tglstruk, 'yyyy-MM-dd' ) AS bulan,
                                    prd.namaproduk,null as nosep
        FROM strukpelayanan_t AS sp
        JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        LEFT JOIN produk_m AS prd ON prd.id = spd.objectprodukfk
        LEFT JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        LEFT JOIN pasien_m AS ps ON ps.id = sp.nocmfk
        WHERE sp.tglstruk BETWEEN '$START_DATE' AND '$END_DATE'
        AND substring(sp.nostruk,0,3) = 'NL' AND sp.statusenabled = true
        ) as z
        "));
        $data10 = [];
        $jml = 0;
        $sama = false;
        $jkn_ranap = 0;
        $jkn_rajal = 0;
        $Eks_Asuransi_ranap = 0;
        $Eks_perusahaan_ranap = 0;
        $Eks_umum_ranap = 0;
        $Eks_Asuransi_rajal = 0;
        $Eks_perusahaan_rajal = 0;
        $Eks_umum_rajal = 0;
        $Reg_Asuransi_ranap = 0;
        $Reg_perusahaan_ranap = 0;
        $Reg_umum_ranap = 0;
        $Reg_Asuransi_rajal = 0;
        $Reg_perusahaan_rajal = 0;
        $Reg_umum_rajal = 0;

        foreach ($data as $item) {
            $item->total = round((float) $item->total, 2);
            $item->proporsi = round((float) $item->proporsi, 2);
            $item->klaim = round((float) $item->klaim, 2);
            $item->pengajuan = round((float)  $item->pengajuan, 2);
            $sama = false;
            foreach ($data10 as $i => &$hideung) {

                if ($item->bulan == $hideung['bulan']) {
                    $jml = (float)$hideung['jumlah'] + $item->proporsi;
                    $data10[$i]['jumlah'] = $jml;
                    $sama = true;

                    if ($item->objectkelompokpasienlastfk == $pasienBPJS && $item->kddepartemen == $idDepRanap) {
                        $hideung['jkn_ranap'] += (float)$item->proporsi;
                    } else if ($item->objectkelompokpasienlastfk == $pasienBPJS && $item->kddepartemen == $idDepRajal) {
                        $hideung['jkn_rajal'] += (float)$item->proporsi;
                    }
                    // Eksekutif
                    else if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_Asuransi_ranap'] += (float)$item->proporsi;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_perusahaan_ranap'] += (float)$item->proporsi;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_umum_ranap'] += (float) $item->proporsi;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_Asuransi_rajal'] += (float)$item->proporsi;
                    } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_perusahaan_rajal'] += (float)$item->proporsi;
                    } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                        $hideung['Eks_umum_rajal'] += (float) $item->proporsi;
                    }
                    // Reguler
                    else   if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_Asuransi_ranap'] += (float)$item->proporsi;
                    } else    if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_perusahaan_ranap'] += (float) $item->proporsi;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_umum_ranap'] += (float)$item->proporsi;
                    } else  if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_Asuransi_rajal'] += (float)$item->proporsi;
                    } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_perusahaan_rajal'] += (float)$item->proporsi;
                    } else   if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                        $hideung['Reg_umum_rajal'] += (float) $item->proporsi;
                    } else {
                        $hideung['pendapatan_lain'] += (float)$item->proporsi;
                    }
                }
            }

            if ($sama == false) {

                $cek = [
                    'bulan' => $item->bulan,
                    'jkn_rajal' => 0,
                    'jkn_ranap' => 0,
                    'Eks_Asuransi_ranap' => 0,
                    'Eks_perusahaan_ranap' => 0,
                    'Eks_umum_ranap' => 0,
                    'Eks_Asuransi_rajal' => 0,
                    'Eks_perusahaan_rajal' => 0,
                    'Eks_umum_rajal' => 0,
                    'Reg_Asuransi_ranap' => 0,
                    'Reg_perusahaan_ranap' => 0,
                    'Reg_umum_ranap' => 0,
                    'Reg_Asuransi_rajal' => 0,
                    'Reg_perusahaan_rajal' => 0,
                    'Reg_umum_rajal' => 0,
                    'pendapatan_lain' => 0,
                    'jumlah' => (float) $item->proporsi,
                ];
                if ($item->objectkelompokpasienlastfk == $pasienBPJS && $item->kddepartemen == $idDepRanap) {
                    $cek['jkn_ranap'] = (float) $item->proporsi;
                } else if ($item->objectkelompokpasienlastfk == $pasienBPJS && $item->kddepartemen == $idDepRajal) {
                    $cek['jkn_rajal'] = (float) $item->proporsi;
                } else if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_Asuransi_ranap'] = (float)$item->proporsi;
                } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_perusahaan_ranap'] = (float)$item->proporsi;
                } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_umum_ranap'] = (float)$item->proporsi;
                } else if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_Asuransi_rajal'] = (float)$item->proporsi;
                } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_perusahaan_rajal'] = (float) $item->proporsi;
                } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $eksekutif) {
                    $cek['Eks_umum_rajal'] = (float)$item->proporsi;
                } else  if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $cek['Reg_Asuransi_ranap'] = (float) $item->proporsi;
                } else  if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $cek['Reg_perusahaan_ranap'] = (float)$item->proporsi;
                } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRanap && $item->jenispelayanan == $reguler) {
                    $cek['Reg_umum_ranap'] = (float)$item->proporsi;
                } else if ($item->objectkelompokpasienlastfk == $pasienAsuransi && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $cek['Reg_Asuransi_rajal'] = (float)$item->proporsi;
                } else if ($item->objectkelompokpasienlastfk == $pasienPerusahaan && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $cek['Reg_perusahaan_rajal'] = (float) $item->proporsi;
                } else  if ($item->objectkelompokpasienlastfk == $pasienUMUM && $item->kddepartemen == $idDepRajal && $item->jenispelayanan == $reguler) {
                    $cek['Reg_umum_rajal'] = (float)$item->proporsi;
                } else {
                    $cek['pendapatan_lain'] = (float)$item->proporsi;
                }

                $data10[] = $cek;
            }
        }


        foreach ($data10 as $key => $value) {
            $keyS = array_keys($data10[$key]);

            foreach ($keyS as $d) {
                if ($d != 'bulan') {
                    $data10[$key][$d] = round($data10[$key][$d], 2);
                }
            }
        }
        if ($lokal == true || isset($request['ebitda'])) {

            $objetoRequest = new \Illuminate\Http\Request();

            $objetoRequest['dari'] = $request['dari'];
            $objetoRequest['sampai'] =  $request['sampai'];
            $beban = $this->laporanBebanUsaha($objetoRequest, true);
            $resBeban = null;
            if (count($beban) > 0) {
                $resBeban = $beban[0];
            }
            $penL = $this->jmlPendapatanKeuangan($objetoRequest, true);
            $resPenL = null;
            if (count($penL) > 0) {
                $resPenL = $penL[0];
            }
            $SURPLUS_SEBELUM_PAJAK = 0;
            if (count($data10)) {
                $SURPLUS_SEBELUM_PAJAK =  ($data10[0]['jumlah'] - $resBeban['jumlah']) -
                    (!empty($resPenL) ? $resPenL['pendapatan_blu_lainnya'] + $resPenL['pendapatan_hibah'] + $resPenL['pend_apbn_lainnya'] : 0);
            }

            if (isset($request['closing'])) {
                return $this->saveBebanPendapatan($request, $data10, $resBeban, $resPenL);
            } else {
                foreach ($data10 as $element) {

                    // Prepare the data to be sent in the POST request
                    $postData = [

                        "tanggal" => $element['bulan'],
                        "detail" => [
                            "pendapatan_rs" => [
                                "outpatient_revenue" => [
                                    "pasien_jkn" => [
                                        "jkn_reguler" => number_format($element['jkn_rajal'], 2, '.', ''),
                                        "jkn_naikkelas" => 0
                                    ],
                                    "pasien_non_jkn_eksekutif" => [
                                        "asuransi" => number_format($element['Eks_Asuransi_rajal'], 2, '.', ''),
                                        "jaminan_perusahaan" => number_format($element['Eks_perusahaan_rajal'], 2, '.', ''),
                                        "pembayaran_mandiri" => number_format($element['Eks_umum_rajal'], 2, '.', '')
                                    ],
                                    "pasien_non_jkn_reguler" => [
                                        "asuransi" => number_format($element['Reg_Asuransi_rajal'], 2, '.', ''),
                                        "jaminan_perusahaan" => number_format($element['Reg_perusahaan_rajal'], 2, '.', ''),
                                        "pembayaran_mandiri" => number_format($element['Reg_umum_rajal'], 2, '.', '')
                                    ]
                                ],
                                "inpatient_revenue" => [
                                    "pasien_jkn" => [
                                        "jkn_reguler" => number_format($element['jkn_ranap'], 2, '.', ''),
                                        "jkn_naikkelas" => 0
                                    ],
                                    "pasien_non_jkn_eksekutif" => [
                                        "asuransi" => number_format($element['Eks_Asuransi_ranap'], 2, '.', ''),
                                        "jaminan_perusahaan" => number_format($element['Eks_perusahaan_ranap'], 2, '.', ''),
                                        "pembayaran_mandiri" => number_format($element['Eks_umum_ranap'], 2, '.', '')
                                    ],
                                    "pasien_non_jkn_reguler" => [
                                        "asuransi" => number_format($element['Reg_Asuransi_ranap'], 2, '.', ''),
                                        "jaminan_perusahaan" => number_format($element['Reg_perusahaan_ranap'], 2, '.', ''),
                                        "pembayaran_mandiri" => number_format($element['Reg_umum_ranap'], 2, '.', '')
                                    ]
                                ],
                                "pendapatan_layanan_lain" => number_format($element['pendapatan_lain'], 2, '.', '')
                            ],
                            "rba_pendapatan" => 0,
                            "beban_pokok_pendapatan" => [
                                "beban_pegawai" => !empty($resBeban) ? number_format($resBeban['bebanPegBLU'], 2, '.', '') : 0,
                            ],
                            "beban_administrasi_umum" => [
                                "beban_barang_jasa" => !empty($resBeban) ? number_format($resBeban['bebanBarangJasa'], 2, '.', '') : 0,
                                "beban_pemeliharaan" => !empty($resBeban) ? number_format($resBeban['bebanPemeliharaan'], 2, '.', '') : 0,
                                "beban_perjalanan_dinas" =>  !empty($resBeban) ? number_format($resBeban['bebanPerdin'], 2, '.', '') : 0,
                                "beban_penyisihan_piutang_tak_tertagih" => !empty($resBeban) ? number_format($resBeban['bebanPenyisihanPiut'], 2, '.', '') : 0,
                            ],
                            "beban_persediaan" => [
                                "beban_persediaan_farmasi" =>  !empty($resBeban) ? number_format($resBeban['bebanPersediaanF'], 2, '.', '') : 0,
                                "beban_persediaan_non_farmasi" => !empty($resBeban) ? number_format($resBeban['bebanPersediaanNonF'], 2, '.', '') : 0,
                            ],


                            "beban_penyusutan_dan_amortisasi" =>  !empty($resBeban) ? number_format($resBeban['bebanamorti'], 2, '.', '') : 0,
                            "surplus_defisit_usaha" => number_format(($element['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0)), 2, '.', ''),
                            "depresiasi_amortisasi" =>  !empty($resBeban) ? number_format($resBeban['bebanamorti'], 2, '.', '') : 0,
                            "EBITDA" => ($element['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0)) + $resBeban['bebanamorti'],
                            "beban_pegawai" => !empty($resBeban) ? number_format($resBeban['bebanPegAPBN'], 2, '.', '') : 0,
                            "EBITDA_plus_beban_pegawai" => ($element['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0)) +  (!empty($resBeban) ? $resBeban['bebanamorti'] - $resBeban['bebanPegAPBN'] : 0),
                            "pendapatan_keuangan" => [
                                "pendapatan_bunga_bank" => !empty($resPenL) ? number_format($resPenL['pendapatan_bunga_bank'], 2, '.', '') : 0,
                                "deposito" =>  !empty($resPenL) ? number_format($resPenL['deposito'], 2, '.', '') : 0,
                                "pend_lainnya" => !empty($resPenL) ? number_format($resPenL['pend_lainnya'], 2, '.', '') : 0,
                                "jumlah_pendapatan_keuangan" => !empty($resPenL) ? number_format($resPenL['pendapatan_bunga_bank'] + $resPenL['deposito'] + $resPenL['pend_lainnya'], 2, '.', '') : 0,
                            ],
                            "biaya_keuangan" => 0,
                            "pendapatan_biaya_lain_lain" => [
                                "pend_apbn_lainnya" => !empty($resPenL) ? number_format($resPenL['pend_apbn_lainnya'], 2, '.', '') : 0,
                                "pendapatan_hibah" => !empty($resPenL) ? number_format($resPenL['pendapatan_hibah'], 2, '.', '') : 0,
                                "pendapatan_blu_lainnya" => !empty($resPenL) ? number_format($resPenL['pendapatan_blu_lainnya'], 2, '.', '') : 0,
                                "jumlah_pendapatan_lain_lain" => !empty($resPenL) ? number_format($resPenL['pendapatan_blu_lainnya'] + $resPenL['pendapatan_hibah'] + $resPenL['pend_apbn_lainnya'], 2, '.', '') : 0,
                            ],
                            "surplus_usaha_sebelum_pajak" => $SURPLUS_SEBELUM_PAJAK,
                            "manfaat_beban_pajak" => !empty($resPenL) ? number_format($resPenL['manfaat_beban'], 2, '.', '') : 0,
                            "surplus_bersih" => $SURPLUS_SEBELUM_PAJAK
                        ]
                    ];

                    // Convert the data to JSON format
                    $postDataJson = json_encode($postData);
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'keuangan';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;


                    $insert = $this->apiIntegrate($objetoRequest, true);
                    \Log::info("mkko pendapatan auto " . json_encode($insert));
                }
            }
        }
        // if(isset($request['closing'])){

        //     $sumPerMonth = [];

        //     // Loop through the data array
        //     foreach ($data10 as $item) {
        //         // Extract the month from the "bulan" key
        //         $month = substr($item['bulan'], 0, 7);

        //         // If the month doesn't exist in the $sumPerMonth array, initialize it with zeros
        //         if (!isset($sumPerMonth[$month])) {
        //             $sumPerMonth[$month] = array_fill_keys(array_keys($item), 0);
        //             // Remove "bulan" key from initialization
        //             unset($sumPerMonth[$month]['bulan']);
        //         }

        //         // Accumulate the values for each key in the corresponding month
        //         foreach ($item as $key => $value) {
        //             if ($key !== 'bulan') {
        //                 $sumPerMonth[$month][$key] += $value;
        //             }
        //         }
        //     }

        //     $resSUM = $sumPerMonth[$request['bulan']];
        //     $month = substr($request['bulan'],5,2);

        //     $arrayWithMonths= [
        //         "jan" => null,
        //         "feb" => null,
        //         "mar" => null,
        //         "apr" => null,
        //         "mei" => null,
        //         "jun" => null,
        //         "jul" => null,
        //         "agu" => null,
        //         "sep" => null,
        //         "okt" => null,
        //         "nov" => null,
        //         "des" => null,
        //     ];

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['jkn_rajal'];
        //     $arrayWithMonths['id'] = 42;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['jkn_ranap'];
        //     $arrayWithMonths['id'] = 52;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;


        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_Asuransi_ranap'];
        //     $arrayWithMonths['id'] = 54;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;


        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_perusahaan_ranap'];
        //     $arrayWithMonths['id'] = 55;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;


        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_umum_ranap'];
        //     $arrayWithMonths['id'] = 56;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_Asuransi_rajal'];
        //     $arrayWithMonths['id'] = 44;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_perusahaan_rajal'];
        //     $arrayWithMonths['id'] = 45;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_umum_rajal'];
        //     $arrayWithMonths['id'] = 46;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_Asuransi_ranap'];
        //     $arrayWithMonths['id'] = 58;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_perusahaan_ranap'];
        //     $arrayWithMonths['id'] = 59;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_umum_ranap'];
        //     $arrayWithMonths['id'] = 60;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_Asuransi_rajal'];
        //     $arrayWithMonths['id'] = 48;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_perusahaan_rajal'];
        //     $arrayWithMonths['id'] = 49;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_umum_rajal'];
        //     $arrayWithMonths['id'] = 50;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;

        //     $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pendapatan_lain'];
        //     $arrayWithMonths['id'] = 61;
        //     $arrayWithMonths['tahun'] = substr($request['bulan'],0,4);
        //     $dataInsert[] = $arrayWithMonths;


        //     return $this->apiPOST ( $dataInsert);


        // }
        $result = array(
            'data' => $data10,
            'detail' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }
    public function laporanBebanUsaha(Request $r, $lokal = false)
    {
        $kdProfile =  $this->kdProfile;
        $data = DB::select(DB::raw("
                SELECT
                to_char( sh.tglsetortarikdeposit,'yyyy-MM') as bulan,
                sh.tglsetortarikdeposit as tgltransaksi,
                sh.nonhistori as notransaksi,
                kt.kelompoktransaksi as namaproduk,
                sc.norec AS norec_sc,
                sh.ketlainya,sh.nobukti,
                1 as qtyproduk,
                0 as total,
                COALESCE ( ( sh.totalsetortarikdeposit ), 0 ) as beban ,kt.kelompoktransaksi as jenis
                FROM
                strukhistori_t AS sh
                INNER JOIN strukclosing_t AS sc ON sc.norec = sh.noclosing
                LEFT JOIN strukbuktipenerimaan_t AS spp ON spp.noclosingfk = sc.norec
                LEFT JOIN strukbuktipengeluaran_t AS sbk ON sbk.noclosingfk = sc.norec
                INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sc.objectkelompoktransaksifk
                WHERE
                sh.kdprofile = $kdProfile
                AND sh.statusenabled = true
                AND sh.tglsetortarikdeposit between '$r[dari]' AND  '$r[sampai]'
                and sh.objectkelompoktransaksifk in (313,316,483,317,318,484,488,
                315,314,512)

                union ALL

                SELECT
                to_char( pp.tglpelayanan,'yyyy-MM') as bulan,
                pp.tglpelayanan as tgltransaksi,
                pd.noregistrasi as notransaksi,
                pr.namaproduk,pd.norec as norec_sc,
                ps.namapasien as ketlainya, ps.nocm as nobukti,
                pp.jumlah as qtyproduk,
                (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
                CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS total,ppd.hargajual as beban,  'Beban persediaan farmasi' as jenis
                FROM strukresep_t AS sr
                JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
                JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec  and ppd.komponenhargafk=9
                JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
                JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                JOIN pasien_m AS ps ON pd.nocmfk =ps.id
                JOIN produk_m AS pr ON pr.id = pp.produkfk
                WHERE  pd.statusenabled = TRUE AND pp.statusenabled = TRUE AND sr.statusenabled = TRUE
                AND pp.tglpelayanan BETWEEN '$r[dari]' AND  '$r[sampai]'
                AND pp.strukresepfk IS NOT NULL

                UNION ALL

                SELECT
                to_char( pp.tglpelayanan,'yyyy-MM') as bulan,
                pp.tglpelayanan as tgltransaksi,
                pd.noregistrasi as notransaksi,
                pr.namaproduk,pd.norec as norec_sc,
                ps.namapasien as ketlainya, ps.nocm as nobukti,
                pp.jumlah as qtyproduk,
                (((CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END) * pp.jumlah) +
                CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) AS total,ppd.hargajual as beban,  'Beban persediaan farmasi' as jenis
                FROM strukresep_t AS sr
                INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
                JOIN pelayananpasien_t AS pps ON pps.strukresepfk = sr.norec  AND pps.produkfk = pp.produkfk
                JOIN pelayananpasiendetail_t AS ppd ON ppd.pelayananpasien = pp.norec  and ppd.komponenhargafk=9
                JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
                JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                JOIN pasien_m AS ps ON pd.nocmfk =ps.id
                JOIN produk_m AS pr ON pr.id = pp.produkfk
                WHERE  pd.statusenabled = TRUE AND pps.statusenabled = TRUE AND sr.statusenabled = TRUE
                AND pp.tglpelayanan BETWEEN '$r[dari]' AND  '$r[sampai]'
                AND pp.strukresepfk IS NOT NULL

                union ALL

                SELECT
                to_char( sk.tglkirim,'yyyy-MM') as bulan,
                sk.tglkirim as tgltransaksi,sk.nokirim as notransaksi,
                pr.namaproduk,sk.norec as norec,pr.namaproduk as ketlainya,
                sk.nokirim as nobukti,
                kp.qtyproduk,
                kp.hargasatuan as total,kp.hargasatuan * kp.qtyproduk as beban,  'Beban persediaan non farmasi' as jenis
                FROM strukkirim_t as sk
                INNER JOIN kirimproduk_t as kp on kp.nokirimfk = sk.norec
                INNER JOIN ruangan_m as ru on ru.id = sk.objectruanganasalfk
                INNER JOIN ruangan_m as ru1 on ru1.id = sk.objectruangantujuanfk
                INNER JOIN produk_m as pr on pr.id = kp.objectprodukfk
                where sk.kdprofile = $kdProfile and sk.tglkirim between '$r[dari]' AND  '$r[sampai]'
                and sk.objectkelompoktransaksifk=34
                and sk.statusenabled =true
                and sk.jenispermintaanfk =1



            "));
        $data10 = [];
        $jml = 0;
        $sama = false;
        $bebanPegBLU = 0;
        $bebanPersediaanNonF = 0;
        $bebanPersediaanF = 0;
        $bebanBarangJasa = 0;
        $bebanPemeliharaan = 0;
        $bebanPerdin = 0;
        $bebanPenyisihanPiut = 0;
        $bebanamorti = 0;
        $bebanPegAPBN = 0;
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->bulan == $data10[$i]['bulan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + (float)$item->beban;
                    $data10[$i]['jumlah'] = $jml;
                    if ($item->jenis == 'Beban Pegawai (PNBP/BLU)') {
                        $data10[$i]['bebanPegBLU'] = (float)$hideung['bebanPegBLU'] + (float)$item->beban;
                    }
                    if ($item->jenis == 'Beban persediaan farmasi') {
                        $data10[$i]['bebanPersediaanF'] = (float)$hideung['bebanPersediaanF'] + (float)$item->beban;
                    }
                    if ($item->jenis == 'Beban persediaan non farmasi') {
                        $data10[$i]['bebanPersediaanNonF'] = (float)$hideung['bebanPersediaanNonF'] + (float)$item->beban;
                    }
                    if ($item->jenis == 'Beban Barang dan Jasa') {
                        $data10[$i]['bebanBarangJasa'] = (float)$hideung['bebanBarangJasa'] + (float)$item->beban;
                    }
                    if ($item->jenis == 'Beban Pemeliharaan') {
                        $data10[$i]['bebanPemeliharaan'] = (float)$hideung['bebanPemeliharaan'] + (float)$item->beban;
                    }
                    if ($item->jenis == 'Beban Perjalanan Dinas') {
                        $data10[$i]['bebanPerdin'] = (float)$hideung['bebanPerdin'] + (float)$item->beban;
                    }
                    if ($item->jenis == 'Beban Penyisihan Piutang Tak Tertagih') {
                        $data10[$i]['bebanPenyisihanPiut'] = (float)$hideung['bebanPenyisihanPiut'] + (float)$item->beban;
                    }
                    if ($item->jenis == 'Beban Penyusutan dan Amortisasi') {
                        $data10[$i]['bebanamorti'] = (float)$hideung['bebanamorti'] + (float)$item->beban;
                    }
                    if ($item->jenis == 'Beban Pegawai (APBN/RM)') {
                        $data10[$i]['bebanPegAPBN'] = (float)$hideung['bebanPegAPBN'] + (float)$item->beban;
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->jenis == 'Beban Pegawai (PNBP/BLU)') {
                    $bebanPegBLU = (float)$item->beban;

                    $bebanPersediaanNonF = 0;
                    $bebanPersediaanF = 0;
                    $bebanBarangJasa = 0;
                    $bebanPemeliharaan = 0;
                    $bebanPerdin = 0;
                    $bebanPenyisihanPiut = 0;
                    $bebanamorti = 0;
                    $bebanPegAPBN = 0;
                }
                if ($item->jenis == 'Beban persediaan farmasi') {
                    $bebanPersediaanF = (float)$item->beban;
                    $bebanPegBLU = 0;
                    $bebanPersediaanNonF = 0;

                    $bebanBarangJasa = 0;
                    $bebanPemeliharaan = 0;
                    $bebanPerdin = 0;
                    $bebanPenyisihanPiut = 0;
                    $bebanamorti = 0;
                    $bebanPegAPBN = 0;
                }
                if ($item->jenis == 'Beban persediaan non farmasi') {
                    $bebanPersediaanNonF = (float)$item->beban;
                    $bebanPegBLU = 0;
                    $bebanPersediaanF = 0;
                    $bebanBarangJasa = 0;
                    $bebanPemeliharaan = 0;
                    $bebanPerdin = 0;
                    $bebanPenyisihanPiut = 0;
                    $bebanamorti = 0;
                    $bebanPegAPBN = 0;
                }
                if ($item->jenis == 'Beban Barang dan Jasa') {
                    $bebanBarangJasa = (float)$item->beban;
                    $bebanPegBLU = 0;
                    $bebanPersediaanNonF = 0;
                    $bebanPersediaanF = 0;

                    $bebanPemeliharaan = 0;
                    $bebanPerdin = 0;
                    $bebanPenyisihanPiut = 0;
                    $bebanamorti = 0;
                    $bebanPegAPBN = 0;
                }
                if ($item->jenis == 'Beban Pemeliharaan') {
                    $bebanPemeliharaan = (float)$item->beban;
                    $bebanPegBLU = 0;
                    $bebanPersediaanNonF = 0;
                    $bebanPersediaanF = 0;
                    $bebanBarangJasa = 0;

                    $bebanPerdin = 0;
                    $bebanPenyisihanPiut = 0;
                    $bebanamorti = 0;
                    $bebanPegAPBN = 0;
                }
                if ($item->jenis == 'Beban Perjalanan Dinas') {
                    $bebanPerdin = (float)$item->beban;
                    $bebanPegBLU = 0;
                    $bebanPersediaanNonF = 0;
                    $bebanPersediaanF = 0;
                    $bebanBarangJasa = 0;
                    $bebanPemeliharaan = 0;

                    $bebanPenyisihanPiut = 0;
                    $bebanamorti = 0;
                    $bebanPegAPBN = 0;
                }
                if ($item->jenis == 'Beban Penyisihan Piutang Tak Tertagih') {
                    $bebanPenyisihanPiut = (float)$item->beban;
                    $bebanPegBLU = 0;
                    $bebanPersediaanNonF = 0;
                    $bebanPersediaanF = 0;
                    $bebanBarangJasa = 0;
                    $bebanPemeliharaan = 0;
                    $bebanPerdin = 0;

                    $bebanamorti = 0;
                    $bebanPegAPBN = 0;
                }
                if ($item->jenis == 'Beban Penyusutan dan Amortisasi') {
                    $bebanamorti = (float)$item->beban;
                    $bebanPegBLU = 0;
                    $bebanPersediaanNonF = 0;
                    $bebanPersediaanF = 0;
                    $bebanBarangJasa = 0;
                    $bebanPemeliharaan = 0;
                    $bebanPerdin = 0;
                    $bebanPenyisihanPiut = 0;
                    $bebanPegAPBN = 0;
                }
                if ($item->jenis == 'Beban Pegawai (APBN/RM)') {
                    $bebanPegAPBN = (float)$item->beban;
                    $bebanPegBLU = 0;
                    $bebanPersediaanNonF = 0;
                    $bebanPersediaanF = 0;
                    $bebanBarangJasa = 0;
                    $bebanPemeliharaan = 0;
                    $bebanPerdin = 0;
                    $bebanPenyisihanPiut = 0;
                    $bebanamorti = 0;
                }
                $data10[] = array(
                    'bulan' => $item->bulan,
                    'bebanPegBLU' => $bebanPegBLU,
                    'bebanPersediaanNonF' => $bebanPersediaanNonF,
                    'bebanPersediaanF' => $bebanPersediaanF,
                    'bebanBarangJasa' => $bebanBarangJasa,
                    'bebanPemeliharaan' => $bebanPemeliharaan,
                    'bebanPerdin' => $bebanPerdin,
                    'bebanPenyisihanPiut' => $bebanPenyisihanPiut,
                    'bebanamorti' => $bebanamorti,
                    'bebanPegAPBN' => $bebanPegAPBN,
                    'jumlah' =>  (float)$item->beban,
                );
            }
        }
        if ($lokal) {
            return $data10;
        }
        $result = array(
            'data' => $data10,
            'detail' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }


    public function jmlPendapatanLainQuery(Request $request, $lokal = false)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::select(DB::raw($request['query']));

        $data10 = [];
        $jml = 0;
        $sama = false;
        $pend_apbn_lainnya = 0;
        $pendapatan_hibah = 0;
        $pendapatan_blu_lainnya = 0;

        foreach ($data as $item) {
            $sama = false;
            foreach ($data10 as $i => &$hideung) {
                $jml = (float)$hideung['jumlah_pendapatan_lain_lain'] + $item->total;
                $data10[$i]['jumlah_pendapatan_lain_lain'] = $jml;
                if ($item->bulan == $hideung['bulan']) {
                    $sama = true;

                    if ($item->objectkelompoktransaksifk == 449) {
                        $hideung['pend_apbn_lainnya'] += $item->total;
                    } else if ($item->objectkelompoktransaksifk == 450) {
                        $hideung['pendapatan_hibah'] += $item->total;
                    } else if ($item->objectkelompoktransaksifk == 451) {
                        $hideung['pendapatan_blu_lainnya'] += $item->total;
                    }
                }
            }

            if (!$sama) {
                $cek = [
                    'bulan' => $item->bulan,
                    'pend_apbn_lainnya' => 0,
                    'pendapatan_hibah' => 0,
                    'pendapatan_blu_lainnya' => 0,
                    'jumlah_pendapatan_lain_lain' => $item->total,
                ];
                if ($item->objectkelompoktransaksifk == 449) {
                    $cek['pend_apbn_lainnya'] = $item->total;
                } else if ($item->objectkelompoktransaksifk == 450) {
                    $cek['pendapatan_hibah'] = $item->total;
                } else if ($item->objectkelompoktransaksifk == 451) {
                    $cek['pendapatan_blu_lainnya'] = $item->total;
                }

                $data10[] = $cek;
            }
        }
        if ($lokal == true) {
            foreach ($data10 as $element) {

                // Prepare the data to be sent in the POST request
                $postData = [

                    "tanggal" => $element['bulan'],
                    "detail" => [
                        "pendapatan_rs" => [
                            "outpatient_revenue" => [
                                "pasien_jkn" => [
                                    "jkn_reguler" => 0,
                                    "jkn_naikkelas" => 0
                                ],
                                "pasien_non_jkn_eksekutif" => [
                                    "asuransi" => 0,
                                    "jaminan_perusahaan" => 0,
                                    "pembayaran_mandiri" => 0
                                ],
                                "pasien_non_jkn_reguler" => [
                                    "asuransi" => 0,
                                    "jaminan_perusahaan" => 0,
                                    "pembayaran_mandiri" => 0
                                ]
                            ],
                            "inpatient_revenue" => [
                                "pasien_jkn" => [
                                    "jkn_reguler" => 0,
                                    "jkn_naikkelas" => 0
                                ],
                                "pasien_non_jkn_eksekutif" => [
                                    "asuransi" => 0,
                                    "jaminan_perusahaan" => 0,
                                    "pembayaran_mandiri" => 0
                                ],
                                "pasien_non_jkn_reguler" => [
                                    "asuransi" => 0,
                                    "jaminan_perusahaan" => 0,
                                    "pembayaran_mandiri" => 0
                                ]
                            ],
                            "pendapatan_layanan_lain" => 0
                        ],
                        "rba_pendapatan" => 0,
                        "beban_pokok_pendapatan" => [
                            "beban_pegawai" => 0
                        ],
                        "beban_administrasi_umum" => [
                            "beban_barang_jasa" => 0,
                            "beban_pemeliharaan" => 0,
                            "beban_perjalanan_dinas" => 0,
                            "beban_penyisihan_piutang_tak_tertagih" => 0
                        ],
                        "beban_persediaan" => [
                            "beban_persediaan_farmasi" => 0,
                            "beban_persediaan_non_farmasi" => 0,
                        ],


                        "beban_penyusutan_dan_amortisasi" => 0,
                        "surplus_defisit_usaha" => 0,
                        "depresiasi_amortisasi" => 0,
                        "EBITDA" => 0,
                        "beban_pegawai" => 0,
                        "EBITDA_plus_beban_pegawai" => 0,
                        "pendapatan_keuangan" => [
                            "pendapatan_bunga_bank" => 0,
                            "deposito" => 0,
                            "pend_lainnya" => 0,
                            "jumlah_pendapatan_keuangan" => 0
                        ],
                        "biaya_keuangan" => 0,
                        "pendapatan_biaya_lain_lain" => [
                            "pend_apbn_lainnya" => number_format($element['pend_apbn_lainnya'], 2, '.', ''),
                            "pendapatan_hibah" => number_format($element['pendapatan_hibah'], 2, '.', ''),
                            "pendapatan_blu_lainnya" => number_format($element['pendapatan_blu_lainnya'], 2, '.', ''),
                            "jumlah_pendapatan_lain_lain" => number_format($element['jumlah_pendapatan_lain_lain'], 2, '.', '')
                        ],
                        "surplus_usaha_sebelum_pajak" => 0,
                        "manfaat_beban_pajak" => 0,
                        "surplus_bersih" => 0
                    ]
                ];

                // Convert the data to JSON format
                $postDataJson = json_encode($postData);
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = 'keuangan';
                $objetoRequest['method'] = 'POST';
                $objetoRequest['data'] = $postData;


                $insert = $this->apiIntegrate($objetoRequest, true);
                \Log::info("mkko pendapatan auto " . json_encode($insert));
            }
        }
        if ($lokal) {
            return $data10;
        }
        $result = array(
            'data' => $data10,
            'detail' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function jmlPendapatanKeuangan(Request $request, $lokal = false)
    {
        $kdProfile = $this->kdProfile;

        $data = DB::select(
            DB::raw(
                "SELECT  sh.objectkelompoktransaksifk, kt.kelompoktransaksi as transaksi, sc.noclosing, sh.nonhistori, sh.ketlainya, sh.namaperkiraan, sh.kettransaksi, sh.nobukti, sh.totalsetortarikdeposit as total,
                CASE WHEN spp.nosbm IS NULL THEN sbk.nosbk ELSE spp.nosbm END AS notransaksi, to_char(sh.tglsetortarikdeposit, 'yyyy-MM') AS bulan, ap.asalproduk, sh.tglsetortarikdeposit as tanggal
                FROM strukhistori_t AS sh
                INNER JOIN strukclosing_t AS sc ON sc.norec = sh.noclosing
                LEFT JOIN strukbuktipenerimaan_t AS spp ON spp.noclosingfk = sc.norec
                LEFT JOIN strukbuktipengeluaran_t AS sbk ON sbk.noclosingfk = sc.norec
                INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sc.objectkelompoktransaksifk
                LEFT JOIN asalproduk_m AS ap ON ap.id = sh.objectasalprodukhasilfk
                WHERE
                sh.kdprofile = $kdProfile
                AND sh.statusenabled = 't'
                AND sh.objectkelompoktransaksifk IN (485, 486, 487, 449, 450, 451, 445, 453)
                AND sh.tglsetortarikdeposit BETWEEN '$request[dari]' AND '$request[sampai]'"
            )
        );
        $data10 = [];
        $jml = 0;
        $sama = false;
        $pendapatan_bunga_bank = 0;
        $deposito = 0;
        $pend_lainnya = 0;
        $pend_apbn_lainnya = 0;
        $pendapatan_hibah = 0;
        $pendapatan_blu_lainnya = 0;
        $biaya_bunga_bank = 0;
        $manfaat_beban = 0;

        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->bulan == $data10[$i]['bulan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + (float)$item->total;

                    $data10[$i]['jumlah'] = $jml;
                    if ($item->objectkelompoktransaksifk == 485) {
                        $data10[$i]['pendapatan_bunga_bank'] = (float)$hideung['pendapatan_bunga_bank'] + (float)$item->total;
                    }
                    if ($item->objectkelompoktransaksifk == 486) {
                        $data10[$i]['deposito'] = (float)$hideung['deposito'] + (float)$item->total;
                    }
                    if ($item->objectkelompoktransaksifk == 487) {
                        $data10[$i]['pend_lainnya'] = (float)$hideung['pend_lainnya'] + (float)$item->total;
                    }
                    if ($item->objectkelompoktransaksifk == 489) {
                        $data10[$i]['pend_apbn_lainnya'] = (float)$hideung['pend_apbn_lainnya'] + (float)$item->total;
                    }
                    if ($item->objectkelompoktransaksifk == 450) {
                        $data10[$i]['pendapatan_hibah'] = (float)$hideung['pendapatan_hibah'] + (float)$item->total;
                    }
                    if ($item->objectkelompoktransaksifk == 451) {
                        $data10[$i]['pendapatan_blu_lainnya'] = (float)$hideung['pendapatan_blu_lainnya'] + (float)$item->total;
                    }
                    if ($item->objectkelompoktransaksifk == 445) {
                        $data10[$i]['biaya_bunga_bank'] = (float)$hideung['biaya_bunga_bank'] + (float)$item->total;
                    }
                    if ($item->objectkelompoktransaksifk == 453) {
                        $data10[$i]['manfaat_beban'] = (float)$hideung['manfaat_beban'] + (float)$item->total;
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                $pendapatan_bunga_bank = 0;
                $deposito = 0;
                $pend_lainnya = 0;
                $pend_apbn_lainnya = 0;
                $pendapatan_hibah = 0;
                $pendapatan_blu_lainnya = 0;
                $biaya_bunga_bank = 0;

                if ($item->objectkelompoktransaksifk == 485) {
                    $pendapatan_bunga_bank =  (float)$item->total;
                }
                if ($item->objectkelompoktransaksifk == 486) {
                    $deposito = (float)$item->total;
                }
                if ($item->objectkelompoktransaksifk == 487) {
                    $pend_lainnya = (float)$item->total;
                }
                if ($item->objectkelompoktransaksifk == 489) {
                    $pend_apbn_lainnya = (float)$item->total;
                }
                if ($item->objectkelompoktransaksifk == 450) {
                    $pendapatan_hibah = (float)$item->total;
                }
                if ($item->objectkelompoktransaksifk == 451) {
                    $pendapatan_blu_lainnya = (float)$item->total;
                }
                if ($item->objectkelompoktransaksifk == 445) {
                    $biaya_bunga_bank = (float)$item->total;
                }
                if ($item->objectkelompoktransaksifk == 453) {
                    $manfaat_beban = (float)$item->total;
                }

                $data10[] = array(
                    'bulan' => $item->bulan,
                    'pendapatan_bunga_bank' => $pendapatan_bunga_bank,
                    'pend_lainnya' => $pend_lainnya,
                    'deposito' => $deposito,
                    'pend_apbn_lainnya' => $pend_apbn_lainnya,
                    'pendapatan_hibah' => $pendapatan_hibah,
                    'pendapatan_blu_lainnya' => $pendapatan_blu_lainnya,
                    'biaya_bunga_bank' => $biaya_bunga_bank,
                    'manfaat_beban' => $manfaat_beban,
                    'jumlah' => (float)$item->total

                );
            }
        }
        if ($lokal) {
            return $data10;
        }
        $result = array(
            'data' => $data10,
            'detail' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function LapCashflow(Request $request, $lokal = false)
    {
        $kdProfile =  $this->kdProfile;
        try {
            $data = DB::select(DB::raw("
            SELECT
            sh.objectkelompoktransaksifk as kode,
            kt.kelompoktransaksi as transaksi,
            sh.kettransaksi AS keterangan,
            sh.totalsetortarikdeposit AS total,
            to_char(sh.tglsetortarikdeposit, 'yyyy-MM-dd') AS bulan,
            sh.tglsetortarikdeposit as tanggal
            FROM
            strukhistori_t AS sh
            INNER JOIN strukclosing_t AS sc ON sc.norec = sh.noclosing
            INNER JOIN kelompoktransaksi_m AS kt ON kt.ID = sc.objectkelompoktransaksifk
            WHERE
            sh.kdprofile = $kdProfile
            AND sh.statusenabled = true
            AND sh.objectkelompoktransaksifk IN (438, 441, 439, 440, 491, 492, 493, 494, 495, 496, 497, 498, 499, 500, 501, 503, 504, 506, 507, 508, 509, 510, 502)
            AND sh.tglsetortarikdeposit BETWEEN '$request[dari]' AND '$request[sampai]'

            UNION ALL

            SELECT
            sbm.objectkelompoktransaksifk as kode,
            kt.kelompoktransaksi as transaksi,
            sbm.keteranganlainnya AS keterangan,
            sbmcr.totaldibayar as total,
            to_char(sbm.tglsbm, 'yyyy-MM-dd') AS bulan,
            sbm.tglsbm as tanggal
            FROM
            strukbuktipenerimaan_t AS sbm
            INNER JOIN strukpelayanan_t AS sp ON sbm.nostrukfk = sp.norec
            LEFT JOIN strukbuktipenerimaancarabayar_t AS sbmcr ON sbmcr.nosbmfk = sbm.norec
            LEFT JOIN kelompoktransaksi_m AS kt ON kt.id = sbm.objectkelompoktransaksifk
            WHERE
            sbm.statusenabled = true
            AND sbm.objectkelompoktransaksifk = 45
            AND sbm.kdprofile = $kdProfile
            AND sbm.tglsbm BETWEEN '$request[dari]' AND '$request[sampai]'



            "));
            $data10 = [];
            $jml = 0;
            $sama = false;
            $PenerimaanJasaLayanan = 0;
            $PenerimaanAPBN = 0;
            $PenerimaanAPBNLuar = 0;
            $PenerimaanHibah = 0;
            $penerimaanUsaha = 0;
            $pembayaranPegawai = 0;
            $pembayaranBarangPer = 0;
            $pengeluaranFarmasi = 0;
            $pengeluaranNonFarmasi = 0;
            $pembayaranJasa = 0;
            $pembayaranBarang = 0;
            $pembayaranPem = 0;
            $pengJasa = 0;
            $pengUT = 0;
            $pembayaranDinas = 0;
            $pembayaranBLU = 0;
            $penyetoranPNBP = 0;
            $penjualanAT = 0;
            $perolehanAT = 0;
            $penerimaanAPBNModal = 0;
            $penerimaanKetiga = 0;
            $pengeluaranKetiga = 0;
            $pembayaranLain = 0;
            $kasAwal = 0;

            foreach ($data as $item) {
                $sama = false;
                $i = 0;
                foreach ($data10 as $hideung) {
                    if ($item->bulan == $data10[$i]['bulan']) {
                        $sama = true;
                        $jml = (float)$hideung['jumlah'] + (float)$item->total;
                        $data10[$i]['jumlah'] = $jml;
                        if ($item->transaksi == 'PEMBAYARAN TAGIHAN PASIEN') {
                            $data10[$i]['PenerimaanJasaLayanan'] = (float)$hideung['PenerimaanJasaLayanan'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penerimaan APBN/RM diluar gaji pegawai dan belanja modal') {
                            $data10[$i]['PenerimaanAPBNLuar'] = (float)$hideung['PenerimaanAPBNLuar'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penerimaan APBN/RM untuk Gaji Pegawai') {
                            $data10[$i]['PenerimaanAPBN'] = (float)$hideung['PenerimaanAPBN'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penerimaan Hibah') {
                            $data10[$i]['PenerimaanHibah'] = (float)$hideung['PenerimaanHibah'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penerimaan Usaha Lainnya') {
                            $data10[$i]['penerimaanUsaha'] = (float)$hideung['penerimaanUsaha'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pembayaran Pegawai') {
                            $data10[$i]['pembayaranPegawai'] = (float)$hideung['pembayaranPegawai'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Biaya Bunga Bank') {
                            $data10[$i]['pembayaranBarangPer'] = (float)$hideung['pembayaranBarangPer'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pengeluaran Farmasi') {
                            $data10[$i]['pengeluaranFarmasi'] = (float)$hideung['pengeluaranFarmasi'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pengeluaran Non Farmasi') {
                            $data10[$i]['pengeluaranNonFarmasi'] = (float)$hideung['pengeluaranNonFarmasi'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pembayaran Jasa') {
                            $data10[$i]['pembayaranJasa'] = (float)$hideung['pembayaranJasa'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pembayaran Barang') {
                            $data10[$i]['pembayaranBarang'] = (float)$hideung['pembayaranBarang'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pembayaran Pemeliharaan') {
                            $data10[$i]['pembayaranPem'] = (float)$hideung['pembayaranPem'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pengeluaran Jasa & Pemeliharaan') {
                            $data10[$i]['pengJasa'] = (float)$hideung['pengJasa'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pengeluaran Utilities & Others') {
                            $data10[$i]['pengUT'] = (float)$hideung['pengUT'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pembayaran Perjalanan Dinas') {
                            $data10[$i]['pembayaranDinas'] = (float)$hideung['pembayaranDinas'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pembayaran Barang dan Jasa Kekhususnan BLU') {
                            $data10[$i]['pembayaranBLU'] = (float)$hideung['pembayaranBLU'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penyetoran PNBP ke Kas Negara') {
                            $data10[$i]['penyetoranPNBP'] = (float)$hideung['penyetoranPNBP'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penjualan atas Aset Tetap') {
                            $data10[$i]['penjualanAT'] = (float)$hideung['penjualanAT'] + (float)$item->total;
                        }

                        if ($item->transaksi == 'Perolehan atas Aset Tetap') {
                            $data10[$i]['perolehanAT'] = (float)$hideung['perolehanAT'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penerimaan APBN/RM untuk Belanja Modal') {
                            $data10[$i]['penerimaanAPBNModal'] = (float)$hideung['penerimaanAPBNModal'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penerimaan Perhitungan Pihak Ketiga') {
                            $data10[$i]['penerimaanKetiga'] = (float)$hideung['penerimaanKetiga'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Pengeluaran Perhitungan Pihak Ketiga') {
                            $data10[$i]['pengeluaranKetiga'] = (float)$hideung['pengeluaranKetiga'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Penerimaan (Pembayaran) lain-lain') {
                            $data10[$i]['pembayaranLain'] = (float)$hideung['pembayaranLain'] + (float)$item->total;
                        }
                        if ($item->transaksi == 'Kas dan setara kas awal bulan ') {
                            $data10[$i]['kasAwal'] = (float)$hideung['kasAwal'] + (float)$item->total;
                        }
                    }
                    $i = $i + 1;
                }

                if ($sama == false) {
                    $PenerimaanJasaLayanan = 0;
                    $PenerimaanAPBN = 0;
                    $PenerimaanAPBNLuar = 0;
                    $PenerimaanHibah = 0;
                    $penerimaanUsaha = 0;
                    $pembayaranPegawai = 0;
                    $pembayaranBarang = 0;
                    $pengeluaranFarmasi = 0;
                    $pengeluaranNonFarmasi = 0;
                    $pembayaranJasa = 0;
                    $pembayaranBarangPer = 0;
                    $pembayaranPem = 0;
                    $pengJasa = 0;
                    $pengUT = 0;
                    $pembayaranDinas = 0;
                    $pembayaranBLU = 0;
                    $penyetoranPNBP = 0;
                    $penjualanAT = 0;
                    $perolehanAT = 0;
                    $penerimaanAPBNModal = 0;
                    $penerimaanKetiga = 0;
                    $pengeluaranKetiga = 0;
                    $pembayaranLain = 0;
                    $kasAwal = 0;

                    if ($item->transaksi == 'PEMBAYARAN TAGIHAN PASIEN') {
                        $PenerimaanJasaLayanan =  (float)$item->total;
                    }
                    if ($item->transaksi == 'Penerimaan APBN/RM diluar gaji pegawai dan belanja modal') {
                        $PenerimaanAPBNLuar = (float)$item->total;
                    }
                    if ($item->transaksi == 'Penerimaan APBN/RM untuk Gaji Pegawai') {
                        $PenerimaanAPBN = (float)$item->total;
                    }
                    if ($item->transaksi == 'Penerimaan Hibah') {
                        $PenerimaanHibah = (float)$item->total;
                    }
                    if ($item->transaksi == 'Penerimaan Usaha Lainnya') {
                        $penerimaanUsaha = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pembayaran Pegawai') {
                        $pembayaranPegawai = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pembayaran Barang Menghasilkan Persediaan') {
                        $pembayaranBarangPer = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pengeluaran Farmasi') {
                        $pengeluaranFarmasi = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pengeluaran Non Farmasi') {
                        $pengeluaranNonFarmasi = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pembayaran Jasa') {
                        $pembayaranJasa = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pembayaran Barang') {
                        $pembayaranBarang = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pembayaran Pemeliharaan') {
                        $pembayaranPem = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pengeluaran Jasa & Pemeliharaan') {
                        $pengJasa = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pengeluaran Utilities & Others') {
                        $pengUT = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pembayaran Perjalanan Dinas') {
                        $pembayaranDinas = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pembayaran Barang dan Jasa Kekhususnan BLU') {
                        $pembayaranBLU = (float)$item->total;
                    }
                    if ($item->transaksi == 'Penyetoran PNBP ke Kas Negara') {
                        $penyetoranPNBP = (float)$item->total;
                    }
                    if ($item->transaksi == 'Penjualan atas Aset Tetap') {
                        $penjualanAT = (float)$item->total;
                    }

                    if ($item->transaksi == 'Perolehan atas Aset Tetap') {
                        $perolehanAT = (float)$item->total;
                    }
                    if ($item->transaksi == 'Penerimaan APBN/RM untuk Belanja Modal') {
                        $penerimaanAPBNModal = (float)$item->total;
                    }
                    if ($item->transaksi == 'Penerimaan Perhitungan Pihak Ketiga') {
                        $penerimaanKetiga = (float)$item->total;
                    }
                    if ($item->transaksi == 'Pengeluaran Perhitungan Pihak Ketiga') {
                        $pengeluaranKetiga = (float)$item->total;
                    }
                    if ($item->transaksi == 'Penerimaan (Pembayaran) lain-lain') {
                        $pembayaranLain = (float)$item->total;
                    }
                    if ($item->transaksi == 'Kas dan setara kas awal bulan ') {
                        $kasAwal = (float)$item->total;
                    }

                    $data10[] = array(
                        'bulan' => $item->bulan,
                        'PenerimaanJasaLayanan' => $PenerimaanJasaLayanan,
                        'PenerimaanAPBN' => $PenerimaanAPBN,
                        'PenerimaanAPBNLuar' => $PenerimaanAPBNLuar,
                        'PenerimaanHibah' => $PenerimaanHibah,
                        'penerimaanUsaha' => $penerimaanUsaha,
                        'pembayaranPegawai' => $pembayaranPegawai,
                        'pembayaranBarangPer' => $pembayaranBarangPer,
                        'pengeluaranFarmasi' => $pengeluaranFarmasi,
                        'pengeluaranNonFarmasi' => $pengeluaranNonFarmasi,
                        'pembayaranJasa' => $pembayaranJasa,
                        'pembayaranBarang' => $pembayaranBarang,
                        'pembayaranPem' => $pembayaranPem,
                        'pengJasa' => $pengJasa,
                        'pengUT' => $pengUT,
                        'pembayaranDinas' => $pembayaranDinas,
                        'pembayaranBLU' => $pembayaranBLU,
                        'penyetoranPNBP' =>  $penyetoranPNBP,
                        'penjualanAT' => $penjualanAT,
                        'perolehanAT' => $perolehanAT,
                        'penerimaanAPBNModal' => $penerimaanAPBNModal,
                        'penerimaanKetiga' => $penerimaanKetiga,
                        'pengeluaranKetiga' => $pengeluaranKetiga,
                        'pembayaranLain' => $pembayaranLain,
                        'kasAwal' => $kasAwal,
                        'jumlah' => (float)$item->total

                    );
                }
            }
            // foreach ($data10 as &$innerArray) {

            //     foreach ($innerArray as $key => $value) {
            //         if (is_numeric($value)) {
            //             $innerArray[$key] = round($value);
            //         }
            //     }
            //     unset($innerArray); // Unset the reference to avoid potential side effects
            // }

            if (isset($request['send'])) {
                $insert = null;
                foreach ($data10 as $rekap) {

                    $postData = [
                        "tanggal" => $rekap['bulan'],
                        "detail" => [
                            "kas_diperoleh_dari_aktivitas_operasi" => [
                                "total_kas_diperoleh_dari_aktivitas_operasi"  => 0,
                                "arus_masuk" => [
                                    "penerimaan_usaha_dari_jasa_layanan" => $rekap['PenerimaanJasaLayanan'],
                                    "penerimaan_apbn_untuk_gaji_pegawai" => $rekap['PenerimaanAPBN'],
                                    "penerimaan_apbn_diluar_gaji_pegawai_dan_belanja_modal" => $rekap['PenerimaanAPBNLuar'],
                                    "penerimaan_hibah" => $rekap['PenerimaanHibah'],
                                    "penerimaan_usaha_lainnya" => $rekap['penerimaanUsaha'],
                                ],
                                "arus_keluar" => [
                                    "pembayaran_pegawai" => $rekap['pembayaranPegawai'],
                                    "pembayaran_barang_menghasilkan_persediaan" => [
                                        "total_pembayaran_barang_menghasilkan_persediaan" => $rekap['pembayaranBarangPer'],
                                        "pengeluaran_farmasi" => $rekap['pengeluaranFarmasi'],
                                        "pengeluaran_non_farmasi" => $rekap['pengeluaranNonFarmasi'],
                                    ],
                                    "pembayaran_jasa" => $rekap['pembayaranJasa'],
                                    "pembayaran_barang" => $rekap['pembayaranBarang'],
                                    "pembayaran_pemeliharaan" => [
                                        "total_pembayaran_pemeliharaan" => $rekap['pembayaranPem'],
                                        "pengeluaran_jasa_pemeliharaan" => $rekap['pengJasa'],
                                        "pengeluaran_utilities_others" => $rekap['pengUT']
                                    ],
                                    "pembayaran_perjalanan_dinas" => $rekap['pembayaranDinas'],
                                    "pembayaran_barang_dan_jasa_blu" => $rekap['pembayaranBLU'],
                                    "penyetoran_pnpb_ke_kas_negara" => $rekap['penyetoranPNBP']
                                ]
                            ],
                            "kas_diperoleh_dari_aktivitas_investasi" => [
                                "total_kas_diperoleh_dari_aktivitas_investasi"  => 0,
                                "penjualan_atas_asset_tetap" => $rekap['penjualanAT'],
                                "perolehan_atas_asset_tetap" => $rekap['perolehanAT'],
                                "penerimaan_apbn_untuk_belanja_modal" => $rekap['penerimaanAPBNModal']
                            ],
                            "kas_diperoleh_dari_aktivitas_pendanaan" => [
                                "total_kas_diperoleh_dari_aktivitas_pendanaan"  => 0,
                                "penerimaan_perhitungan_pihak_ketiga" => $rekap['penerimaanKetiga'],
                                "pengeluaran_perhitungan_pihak_ketiga" => $rekap['pengeluaranKetiga'],
                                "penerimaan_pembayaran_lain_lain" => $rekap['pembayaranLain']
                            ],
                            "kas_dan_setara_kas_akhir_bulan" => [
                                "total_kas_dan_setara_kas_akhir_bulan"  => 0,
                                "kas_dan_setara_kas_awal_bulan" => 0,
                                "kenaikan_bersih_kas_dan_setara_kas" => 0
                            ]
                        ]
                    ];
                    // \Log::info(json_encode($postData));
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'arus-kas';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;
                    $insert = $this->apiIntegrate($objetoRequest, true);

                    \Log::info("MKKO Laporan Arus Kas  " . json_encode($insert));
                }
                return $this->respond($insert);
            }

            if (isset($request['closing'])) {

                $sumPerMonth = [];

                // Loop through the data array
                foreach ($data10 as $item) {
                    // Extract the month from the "bulan" key
                    $month = substr($item['bulan'], 0, 7);

                    // If the month doesn't exist in the $sumPerMonth array, initialize it with zeros
                    if (!isset($sumPerMonth[$month])) {
                        $sumPerMonth[$month] = array_fill_keys(array_keys($item), 0);
                        // Remove "bulan" key from initialization
                        unset($sumPerMonth[$month]['bulan']);
                    }

                    // Accumulate the values for each key in the corresponding month
                    foreach ($item as $key => $value) {
                        if ($key !== 'bulan') {
                            $sumPerMonth[$month][$key] += $value;
                        }
                    }
                }

                // return $data10;

                $resSUM = $sumPerMonth[$request['bulan']];
                $month = substr($request['bulan'], 5, 2);

                $arrayWithMonths = [
                    "jan" => null,
                    "feb" => null,
                    "mar" => null,
                    "apr" => null,
                    "mei" => null,
                    "jun" => null,
                    "jul" => null,
                    "agu" => null,
                    "sep" => null,
                    "okt" => null,
                    "nov" => null,
                    "des" => null,
                ];

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['PenerimaanJasaLayanan'];
                $arrayWithMonths['id'] = 105;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['PenerimaanAPBN'];
                $arrayWithMonths['id'] = 106;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['PenerimaanAPBNLuar'];
                $arrayWithMonths['id'] = 107;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['PenerimaanHibah'];
                $arrayWithMonths['id'] = 108;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['penerimaanUsaha'];
                $arrayWithMonths['id'] = 109;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                // return $dataInsert;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pembayaranPegawai'];
                $arrayWithMonths['id'] = 111;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pembayaranBarangPer'];
                $arrayWithMonths['id'] = 112;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pengeluaranFarmasi'];
                $arrayWithMonths['id'] = 113;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pengeluaranNonFarmasi'];
                $arrayWithMonths['id'] = 114;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pembayaranJasa'];
                $arrayWithMonths['id'] = 115;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pembayaranBarang'];
                $arrayWithMonths['id'] = 116;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pembayaranPem'];
                $arrayWithMonths['id'] = 117;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pengJasa'];
                $arrayWithMonths['id'] = 118;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pengUT'];
                $arrayWithMonths['id'] = 119;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pembayaranDinas'];
                $arrayWithMonths['id'] = 120;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pembayaranBLU'];
                $arrayWithMonths['id'] = 121;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['penyetoranPNBP'];
                $arrayWithMonths['id'] = 122;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['penjualanAT'];
                $arrayWithMonths['id'] = 124;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['perolehanAT'];
                $arrayWithMonths['id'] = 125;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['penerimaanAPBNModal'];
                $arrayWithMonths['id'] = 126;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['penerimaanKetiga'];
                $arrayWithMonths['id'] = 128;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pengeluaranKetiga'];
                $arrayWithMonths['id'] = 129;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pembayaranLain'];
                $arrayWithMonths['id'] = 130;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['kasAwal'];
                $arrayWithMonths['id'] = 133;
                $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                return $this->apiPOST($dataInsert);
            }
            $msg = 'Ok';
        } catch (Exception $ex) {
            $data = [];
            $data10 = [];
            $msg = $ex->getMessage();
        }

        $result = array(
            'data' => $data10,
            'detail' => $data,
            'message' => $msg
        );
        return $this->respond($result);
    }
    public function lapBalanceSheet(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        // $verifKasir =$this->kelompokTransaksi('VERIFIKASI TAGIHAN PASIEN');
        try {
            $data = DB::select(DB::raw("
        SELECT
        to_char( sp.tglstruk,'yyyy-MM-dd') as bulan,
        sp.tglstruk as tgltransaksi,
        sp.nostruk as notransaksi,
        kt.kelompoktransaksi as  ketlainya,
        pd.norec as norec_sc,
        ps.nocm || ' - '|| ps.namapasien as namaproduk,
        '' as nobukti,
        1 as qtyproduk,
        --sp.totalharusdibayar AS total,
        sp.totalhargasatuan AS total,
        'Piutang operasional (Piutang Usaha)' as jenis
        FROM strukpelayanan_t AS sp
        INNER JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        INNER JOIN pasien_m AS ps ON pd.nocmfk =ps.id
        INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sp.objectkelompoktransaksifk
        WHERE  pd.statusenabled = TRUE
        AND sp.tglstruk BETWEEN '$r[dari]' AND  '$r[sampai]'
        AND sp.objectkelompoktransaksifk =2 and sp.statusenabled=true and sp.nosbmlastfk is  null

        UNION ALL

        SELECT
        to_char(  sbm.tglsbm,'yyyy-MM-dd') as bulan,
        sbm.tglsbm as tgltransaksi,
        sbm.nosbm as notransaksi,
        kt.kelompoktransaksi as ketlainya ,
        sbm.norec as norec_sc,
        ps.nocm || ' - '|| ps.namapasien as namaproduk,
        '' as nobukti,
        1 as qtyproduk,
        sbm.totaldibayar AS total,
        'Biaya Dibayar Dimuka' as jenis
        FROM strukpelayanan_t AS sp
        inner join strukbuktipenerimaan_t AS sbm ON sbm.nostrukfk = sp.norec
        INNER JOIN pasiendaftar_t AS pd ON pd.norec = sp.noregistrasifk
        INNER JOIN pasien_m AS ps ON pd.nocmfk =ps.id
        INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sp.objectkelompoktransaksifk
        WHERE  pd.statusenabled = TRUE
        AND sbm.tglsbm BETWEEN '$r[dari]' AND  '$r[sampai]'
        AND sp.objectkelompoktransaksifk =46
        and sbm.statusenabled=true

        UNION ALL

        SELECT
        to_char( sp.tglstruk,'yyyy-MM-dd') as bulan,
        sp.tglstruk as tgltransaksi,
        sp.nostruk as notransaksi,
        kt.kelompoktransaksi as ketlainya,
        sp.norec as norec_sc,
        pr.id|| ' - '|| pr.namaproduk as namaproduk ,
        '' as nobukti,
        spd.qtyproduk,
        spd.harganetto* spd.qtyproduk AS total,
        'Hutang Usaha' as jenis
        FROM strukpelayanan_t AS sp
        JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        JOIN produk_m AS pr ON pr.id =spd.objectprodukfk
        JOIN kelompoktransaksi_m AS kt ON kt.id = sp.objectkelompoktransaksifk
        WHERE sp.tglstruk BETWEEN '$r[dari]' AND  '$r[sampai]'
        AND sp.objectkelompoktransaksifk =432 and sp.statusenabled=true and sp.nosbmlastfk is  null

-- 		UNION ALL
--
-- 		SELECT
-- 		to_char( spd.tglsaldo,'yyyy-MM-dd') as bulan,
-- 		spd.tglsaldo as tgltransaksi,
-- 		cast(pr.id  as text)	as notransaksi,
-- 		pr.namaproduk,
-- 		'' as norec_sc,
-- 		ru.namaruangan as ketlainya,
-- 		'' as nobukti,
-- 		spd.qtyproduk,
-- 		spd.harganetto AS total,  'Persediaan' as jenis
-- 		FROM saldoprodukdetail_t AS spd
-- 		INNER JOIN produk_m AS pr ON pr.id = spd.objectprodukfk
-- 		INNER JOIN ruangan_m AS ru ON ru.id = spd.objectruanganfk
-- 		WHERE  spd.statusenabled = TRUE AND spd.tglsaldo BETWEEN '$r[dari]' AND  '$r[sampai]'

        "));
            $data10 = [];
            $jml = 0;
            $sama = false;
            $kasDanSetaraKas = 0;
            $bank = 0;
            $investasiJangkaPendek = 0; // Deposito
            $piutangOperasional = 0; // Piutang Usaha
            $piutangKaryawan = 0;
            $piutangLainLain = 0;
            $persediaan = 0;
            $biayaDibayarDimuka = 0;
            $uangMuka = 0;
            $uangMukaPajak = 0;
            $asetLancarLainnya = 0;
            $asetTetap = 0;
            $asetPajakTangguhan = 0;
            $asetLain = 0;
            $hutangUsaha = 0;
            $hutangBank = 0;
            $hutangJangkaPendekLainnya = 0;
            $pendapatanDiterimaDimuka = 0;
            $biayaYangMasihHarusDibayar = 0;
            $kreditSLABunga = 0;
            $kreditRDIBunga = 0;
            $pinjamanMTN = 0;
            $hutangJangkaPanjangLainnya = 0;
            $kewajibanLainLain = 0;
            $ekuitasAwal = 0;
            $surplusDefisitTahunPeriodeBerjalan = 0;
            $komponenEkuitasLainnya = 0;
            foreach ($data as $item) {
                $sama = false;
                $i = 0;
                foreach ($data10 as $hideung) {
                    if ($item->bulan == $data10[$i]['bulan']) {
                        $sama = true;
                        $jml = (float)$hideung['jumlah'] + (float)$item->total;
                        $data10[$i]['jumlah'] = $jml;
                        if ($item->jenis == 'Piutang operasional (Piutang Usaha)') {
                            $data10[$i]['piutangOperasional'] = (float)$hideung['piutangOperasional'] + (float)$item->total;
                        }
                        if ($item->jenis == 'Persediaan') {

                            $data10[$i]['persediaan'] = (float)$hideung['persediaan'] + (float)$item->total;
                        }
                        if ($item->jenis == 'Biaya Dibayar Dimuka') {
                            $data10[$i]['biayaDibayarDimuka'] = (float)$hideung['biayaDibayarDimuka'] + (float)$item->total;
                        }
                        if ($item->jenis == 'Hutang Usaha') {
                            $data10[$i]['hutangUsaha'] = (float)$hideung['hutangUsaha'] + (float)$item->total;
                        }
                        if ($item->jenis == 'Pendapatan Diterima Dimuka') {
                            $data10[$i]['pendapatanDiterimaDimuka'] = (float)$hideung['pendapatanDiterimaDimuka'] + (float)$item->total;
                        }
                    }
                    $i = $i + 1;
                }

                if ($sama == false) {
                    $kasDanSetaraKas = 0;
                    $bank = 0;
                    $investasiJangkaPendek = 0; // Deposito
                    $piutangOperasional = 0; // Piutang Usaha
                    $piutangKaryawan = 0;
                    $piutangLainLain = 0;
                    $persediaan = 0;
                    $biayaDibayarDimuka = 0;
                    $uangMuka = 0;
                    $uangMukaPajak = 0;
                    $asetLancarLainnya = 0;
                    $asetTetap = 0;
                    $asetPajakTangguhan = 0;
                    $asetLain = 0;
                    $hutangUsaha = 0;
                    $hutangBank = 0;
                    $hutangJangkaPendekLainnya = 0;
                    $pendapatanDiterimaDimuka = 0;
                    $biayaYangMasihHarusDibayar = 0;
                    $kreditSLABunga = 0;
                    $kreditRDIBunga = 0;
                    $pinjamanMTN = 0;
                    $hutangJangkaPanjangLainnya = 0;
                    $kewajibanLainLain = 0;
                    $ekuitasAwal = 0;
                    $surplusDefisitTahunPeriodeBerjalan = 0;
                    $komponenEkuitasLainnya = 0;
                    if ($item->jenis == 'Piutang operasional (Piutang Usaha)') {
                        $piutangOperasional = (float)$item->total;
                    }
                    if ($item->jenis == 'Persediaan') {
                        $persediaan = (float)$item->total;
                    }
                    if ($item->jenis == 'Biaya Dibayar Dimuka') {
                        $biayaDibayarDimuka = (float)$item->total;
                    }
                    if ($item->jenis == 'Hutang Usaha') {
                        $hutangUsaha = (float)$item->total;
                    }
                    if ($item->jenis == 'Pendapatan Diterima Dimuka') {
                        $pendapatanDiterimaDimuka = (float)$item->total;
                    }

                    $data10[] = array(
                        'bulan' => $item->bulan,
                        'kasDanSetaraKas' => $kasDanSetaraKas,
                        'bank' => $bank,
                        'investasiJangkaPendek' => $investasiJangkaPendek, // Deposito
                        'piutangOperasional' => $piutangOperasional, // Piutang Usaha
                        'piutangKaryawan' => $piutangKaryawan,
                        'piutangLainLain' => $piutangLainLain,
                        'persediaan' => $persediaan,
                        'biayaDibayarDimuka' => $biayaDibayarDimuka,
                        'uangMuka' => $uangMuka,
                        'uangMukaPajak' => $uangMukaPajak,
                        'asetLancarLainnya' => $asetLancarLainnya,
                        'asetTetap' => $asetTetap,
                        'asetPajakTangguhan' => $asetPajakTangguhan,
                        'asetLain' => $asetLain,
                        'hutangUsaha' => $hutangUsaha,
                        'hutangBank' => $hutangBank,
                        'hutangJangkaPendekLainnya' => $hutangJangkaPendekLainnya,
                        'pendapatanDiterimaDimuka' => $pendapatanDiterimaDimuka,
                        'biayaYangMasihHarusDibayar' => $biayaYangMasihHarusDibayar,
                        'kreditSLABunga' => $kreditSLABunga,
                        'kreditRDIBunga' => $kreditRDIBunga,
                        'pinjamanMTN' => $pinjamanMTN,
                        'hutangJangkaPanjangLainnya' => $hutangJangkaPanjangLainnya,
                        'kewajibanLainLain' => $kewajibanLainLain,
                        'ekuitasAwal' => $ekuitasAwal,
                        'surplusDefisitTahunPeriodeBerjalan' => $surplusDefisitTahunPeriodeBerjalan,
                        'komponenEkuitasLainnya' => $komponenEkuitasLainnya,

                        'jumlah' =>  (float)$item->total,
                    );
                }
            }
            foreach ($data10 as &$innerArray) {

                foreach ($innerArray as $key => $value) {
                    if (is_numeric($value)) {
                        $innerArray[$key] = round($value);
                    }
                }
                unset($innerArray); // Unset the reference to avoid potential side effects
            }
            if (isset($r['send'])) {

                $insert = null;
                foreach ($data10 as $k => $element) {
                    $postData = [
                        "tanggal" => $element['bulan'],
                        "detail" => [
                            "aset" => [
                                "total_kas" => $element['kasDanSetaraKas'] + $element['bank'] + $element['investasiJangkaPendek'],
                                "aset_lancar" => [
                                    "kas_dan_setara_kas" => $element['kasDanSetaraKas'],
                                    "bank" => $element['bank'],
                                    "deposito" =>  $element['investasiJangkaPendek'],
                                    "piutang_usaha" =>  $element['piutangOperasional'],
                                    "piutang_karyawan" =>   $element['piutangKaryawan'],
                                    "piutang_lain_lain" =>  $element['piutangLainLain'],
                                    "persediaan" =>  $element['persediaan'],
                                    "biaya_dibayar_dimuka" =>  $element['biayaDibayarDimuka'],
                                    "uang_muka" =>  $element['uangMuka'],
                                    "uang_muka_pajak" =>  $element['uangMukaPajak'],
                                    "aset_lancar_lainnya" =>  $element['asetLancarLainnya'],
                                ],
                                "aset_tidak_lancar" => [
                                    "aset_tetap" =>  $element['asetTetap'],
                                    "aset_pajak_tangguhan" => $element['asetPajakTangguhan'],
                                    "aset_lain" => $element['asetLain'],
                                ],
                            ],
                            "kewajiban_jangka_pendek" => [
                                "hutang_usaha" =>  $element['hutangUsaha'],
                                "hutang_bank" => $element['hutangBank'],
                                "hutang_jangka_pendek_lain" =>  $element['hutangJangkaPendekLainnya'],
                                "pendapatan_diterima_dimuka" => $element['pendapatanDiterimaDimuka'],
                                "biaya_yg_masih_harus_bayar" =>  $element['biayaYangMasihHarusDibayar'],
                            ],
                            "kewajiban_jangka_panjang" => [
                                "kredit_SLA_bunga" =>  $element['kreditSLABunga'],
                                "kredit_RDI_bunga" =>  $element['kreditRDIBunga'],
                                "pinjaman_MTN" =>  $element['pinjamanMTN'],
                                "hutang_jangka_panjang_lain" =>  $element['hutangJangkaPanjangLainnya'],
                                "kewajiban_lain" =>  $element['kewajibanLainLain'],
                            ],
                            "ekuitas_akhir" => [
                                "ekuitas_awal" =>  $element['ekuitasAwal'],
                                "surplus_defisit_tahun_periode_berjalan" =>  $element['surplusDefisitTahunPeriodeBerjalan'],
                                "komponen_ekuitas_lain" =>  $element['komponenEkuitasLainnya'],
                            ],
                        ],
                    ];

                    // Convert the data to JSON format
                    $postDataJson = json_encode($postData);
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = 'balance-sheet';
                    $objetoRequest['method'] = 'POST';
                    $objetoRequest['data'] = $postData;


                    $insert = $this->apiIntegrate($objetoRequest, true);
                    \Log::info("mkko balance sheet auto " . json_encode($insert));
                }
                return $this->respond($insert);
            }

            if (isset($r['closing'])) {
                $sumPerMonth = [];

                // Loop through the data array
                foreach ($data10 as $item) {
                    // Extract the month from the "bulan" key
                    $month = substr($item['bulan'], 0, 7);

                    // If the month doesn't exist in the $sumPerMonth array, initialize it with zeros
                    if (!isset($sumPerMonth[$month])) {
                        $sumPerMonth[$month] = array_fill_keys(array_keys($item), 0);
                        // Remove "bulan" key from initialization
                        unset($sumPerMonth[$month]['bulan']);
                    }

                    // Accumulate the values for each key in the corresponding month
                    foreach ($item as $key => $value) {
                        if ($key !== 'bulan') {
                            $sumPerMonth[$month][$key] += $value;
                        }
                    }
                }
                $resSUM = $sumPerMonth[$r['bulan']];
                $month = substr($r['bulan'], 5, 2);

                $arrayWithMonths = [
                    "jan" => null,
                    "feb" => null,
                    "mar" => null,
                    "apr" => null,
                    "mei" => null,
                    "jun" => null,
                    "jul" => null,
                    "agu" => null,
                    "sep" => null,
                    "okt" => null,
                    "nov" => null,
                    "des" => null,
                ];

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['kasDanSetaraKas'];
                $arrayWithMonths['id'] = 137;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['bank'];
                $arrayWithMonths['id'] = 138;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['investasiJangkaPendek'];
                $arrayWithMonths['id'] = 139;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['piutangOperasional'];
                $arrayWithMonths['id'] = 140;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                // return $dataInsert;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['piutangKaryawan'];
                $arrayWithMonths['id'] = 141;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                // return $dataInsert;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['piutangLainLain'];
                $arrayWithMonths['id'] = 142;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['persediaan'];
                $arrayWithMonths['id'] = 143;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['biayaDibayarDimuka'];
                $arrayWithMonths['id'] = 144;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['uangMuka'];
                $arrayWithMonths['id'] = 145;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['uangMukaPajak'];
                $arrayWithMonths['id'] = 146;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['asetLancarLainnya'];
                $arrayWithMonths['id'] = 147;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['asetTetap'];
                $arrayWithMonths['id'] = 149;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['asetPajakTangguhan'];
                $arrayWithMonths['id'] = 150;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['asetLain'];
                $arrayWithMonths['id'] = 151;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['hutangUsaha'];
                $arrayWithMonths['id'] = 154;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['hutangBank'];
                $arrayWithMonths['id'] = 155;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['hutangJangkaPendekLainnya'];
                $arrayWithMonths['id'] = 156;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pendapatanDiterimaDimuka'];
                $arrayWithMonths['id'] = 157;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['biayaYangMasihHarusDibayar'];
                $arrayWithMonths['id'] = 158;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['kreditSLABunga'];
                $arrayWithMonths['id'] = 160;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['kreditRDIBunga'];
                $arrayWithMonths['id'] = 161;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pinjamanMTN'];
                $arrayWithMonths['id'] = 162;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['hutangJangkaPanjangLainnya'];
                $arrayWithMonths['id'] = 163;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['kewajibanLainLain'];
                $arrayWithMonths['id'] = 164;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['ekuitasAwal'];
                $arrayWithMonths['id'] = 167;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['surplusDefisitTahunPeriodeBerjalan'];
                $arrayWithMonths['id'] = 168;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;


                $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['komponenEkuitasLainnya'];
                $arrayWithMonths['id'] = 169;
                $arrayWithMonths['tahun'] = substr($r['bulan'], 0, 4);
                $dataInsert[] = $arrayWithMonths;

                return $this->apiPOST($dataInsert);

                return $this->apiPOST($dataInsert);
            }
            $msg = 'Ok';
        } catch (Exception $ex) {
            $data = [];
            $data10 = [];
            $msg = $ex->getMessage();
        }
        $result = array(
            'data' => $data10,
            'detail' => $data,
            'message' => $msg,
        );
        return $this->respond($result);
    }

    public function waktuRadiologi(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $depRadiologi = $this->settingFix('idDepartemenRadiologi');

        $data = DB::select(DB::raw("
        SELECT COUNT(*) AS total, namapasien, noregistrasi, namaproduk, namaruangan, tglorder, pengorder, tanggalreport, selisih
        FROM (
        SELECT
        ps.namapasien,
        prd.namaproduk,
        so.noregistrasi,
        ru.namaruangan,
        so.tglorder,
        pg.namalengkap AS pengorder,
        pg2.namalengkap AS dokter,
        hr.tanggalreport,
        EXTRACT(HOUR FROM (hr.tanggalreport - so.tglorder)) * 60 + EXTRACT(MINUTE FROM (hr.tanggalreport - so.tglorder)) AS selisih
        FROM
        strukorder_t AS so
        INNER JOIN orderpelayanan_t AS op ON so.norec = op.noorderfk
        INNER JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
        INNER JOIN produk_m AS prd ON prd.id = op.objectprodukfk
        INNER JOIN ruangan_m AS ru ON ru.id = op.objectruangantujuanfk
        INNER JOIN pasien_m AS ps ON ps.id = so.nocmfk
        LEFT JOIN pelayananpasien_t AS pp ON pp.strukorderfk = so.norec
        LEFT JOIN pelayananpasienpetugas_t AS ppp ON ppp.pelayananpasien = pp.norec
        INNER JOIN pegawai_m AS pg2 ON pg2.id = ppp.objectpegawaifk
        LEFT JOIN hasilradiologi_t AS hr ON pp.norec = hr.pelayananpasienfk
        WHERE
        so.kdprofile = $kdProfile
        AND so.tglorder BETWEEN '$r[dari]' AND  '$r[sampai]'
        AND ru.objectdepartemenfk in ($depRadiologi)
        AND so.statusenabled = true
        AND op.statusenabled = true
        ) AS subquery
        GROUP BY namapasien, namaproduk, namaruangan, noregistrasi,  tglorder, pengorder, tanggalreport, selisih;
        "));

        $totalAll = count($data);
        $jumlah = 0;
        foreach ($data as $d) {
            if ($d->selisih <= 60) {
                $jumlah++;
            }
        }
        $result = array(
            'data' => $data,
            'persentase' => ($totalAll > 0) ? ($jumlah / $totalAll * 100) : 0,
        );

        return $this->respond($result);
    }

    public function waktuLaboratorium(Request $r)
    {
        $kdProfile =  $this->kdProfile;

        $data = DB::select(DB::raw("
        SELECT so.tglorder as tglorder, pd.noregistrasi AS noreg, ps.namapasien as pasien, MIN(hh.tglhasil) AS tglhasil, pg.namalengkap AS validator, EXTRACT(HOUR FROM (MIN(hh.tglhasil) - so.tglorder)) * 60 + EXTRACT(MINUTE FROM (MIN(hh.tglhasil) - so.tglorder)) AS selisih
        FROM pelayananpasien_t AS pp
        INNER JOIN strukorder_t AS so ON so.norec = pp.strukorderfk
        INNER JOIN pasiendaftar_t AS pd ON pd.noregistrasi = pp.noregistrasi
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        LEFT JOIN hasillaboratorium_t AS hh ON hh.norecpelayanan = pp.norec
        LEFT JOIN pegawai_m AS pg ON pg.id = CAST(hh.pegawaifk AS INTEGER)
        WHERE pp.kdprofile = $kdProfile
        AND hh.statusenabled = TRUE
        AND pp.statusenabled = TRUE
        AND so.tglorder BETWEEN '$r[dari]' AND  '$r[sampai]'
        GROUP BY so.tglorder, ps.namapasien, pd.noregistrasi, pg.namalengkap

        UNION ALL

        SELECT so.tglorder as tglorder, hl.no_registrasi AS noreg, ps.namapasien as pasien, MIN(hl.tgl_hasil) AS tglhasil, hl.user_validasi AS validator, (EXTRACT(HOUR FROM (MIN(hl.tgl_hasil) - so.tglorder)) * 60 + EXTRACT(MINUTE FROM (MIN(hl.tgl_hasil) - so.tglorder))) AS selisih
        FROM lab_hasil AS hl
        INNER JOIN strukorder_t so ON so.noorder = hl.no_order
        INNER JOIN pasien_m AS ps ON ps.nocm = hl.no_rm
        WHERE
        so.kdprofile = $kdProfile
        AND so.statusenabled = TRUE
        AND so.tglorder BETWEEN '$r[dari]' AND  '$r[sampai]'
        GROUP BY hl.no_registrasi, ps.namapasien, hl.user_validasi, so.tglorder;
        "));

        if (isset($r['rekananfk']) && $r['rekananfk'] != "") {
            $data = $data->where('pd.objectrekananfk', '=', $r['rekananfk']);
        }

        $totalAll = count($data);
        $jumlah = 0;
        foreach ($data as $d) {
            if ($d->selisih <= 60) {
                $jumlah++;
            }
        }
        $result = array(
            'data' => $data,
            'persentase' => ($totalAll > 0) ? ($jumlah / $totalAll * 100) : 0,
        );

        return $this->respond($result);
    }

    public function waktuIGD(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $depIGD = $this->settingFix('idDepartemenIGD');

        $data = DB::select(DB::raw("
        select distinct ps.namapasien, pd.noregistrasi, pd.tglregistrasi, apd.tglmasuk, (apd.tglmasuk - pd.tglregistrasi) as selisih,
        EXTRACT(HOUR FROM (apd.tglmasuk - pd.tglregistrasi)) * 60 + EXTRACT(MINUTE FROM (apd.tglmasuk - pd.tglregistrasi)) AS selisihwaktu
        FROM antrianpasiendiperiksa_t  as apd
        inner join pasiendaftar_t as pd ON pd.norec = apd.noregistrasifk
        inner join pasien_m as ps on ps.id = pd.nocmfk
        inner join ruangan_m as ru on ru.id = apd.objectruanganasalfk
        where
        pd.kdprofile = $kdProfile AND
        pd.statusenabled = true
        AND pd.tglregistrasi BETWEEN '$r[dari]' AND '$r[sampai]'
        AND ru.objectdepartemenfk in ($depIGD);

        "));

        $totalAll = count($data);
        $jumlah = 0;
        foreach ($data as $d) {
            if ($d->selisihwaktu <= 240) {
                $jumlah++;
            }
        }
        $result = array(
            'data' => $data,
            'persentase' => ($totalAll > 0) ? ($jumlah / $totalAll * 100) : 0,
        );

        return $this->respond($result);
    }

    public function waktuPelayanan(Request $r)
    {

        $kdProfile =  $this->kdProfile;
        $depRJ = $this->settingFix('kdDepartemenRawatJalanFix');

        $data = DB::select(DB::raw("select ps.namapasien, pd.noregistrasi, pd.tglregistrasi, sr.updated_at, sr.status,
        EXTRACT(HOUR FROM (sr.updated_at - pd.tglregistrasi)) * 60 + EXTRACT(MINUTE FROM (sr.updated_at - pd.tglregistrasi)) AS selisih
        from pasiendaftar_t as pd
        inner join pasien_m as ps ON ps.id = pd.nocmfk
        left join strukresep_t as sr ON sr.noregistrasi = pd.noregistrasi
        left join ruangan_m as ru ON ru.id = pd.objectruanganasalfk
        left join statuspengerjaan_m as sp ON sp.id = sr.status
        where
        pd.kdprofile = $kdProfile
        AND pd.tglregistrasi between '$r[dari]' AND '$r[sampai]'
        AND ru.objectdepartemenfk in ($depRJ)
        AND pd.statusenabled = true
        AND sr.status = 5;

        "));

        $totalAll = count($data);
        $jumlah = 0;
        foreach ($data as $d) {
            if ($d->selisih <= 120) {
                $jumlah++;
            }
        }
        $result = array(
            'data' => $data,
            'persentase' => ($totalAll > 0) ? ($jumlah / $totalAll * 100) : 0,
        );

        return $this->respond($result);
    }

    public function waktuRanap(Request $r)
    {

        $kdProfile =  $this->kdProfile;
        $depRJ = $this->settingFix('kdDepartemenRawatJalanFix');
        $depRI = $this->settingFix('idDepRawatInap');

        $data = DB::select(DB::raw("
        select distinct ps.namapasien, pd.noregistrasi, pd.tglregistrasi, apd.tglmasuk, ru.namaruangan as ruanganasal, ru1.namaruangan as ruangantujuan,
        (apd.tglmasuk - pd.tglregistrasi) as selisih,
        EXTRACT(HOUR FROM (apd.tglmasuk - pd.tglregistrasi)) * 60 + EXTRACT(MINUTE FROM (apd.tglmasuk - pd.tglregistrasi)) AS selisihwaktu
        FROM antrianpasiendiperiksa_t  as apd
        inner join pasiendaftar_t as pd ON pd.norec = apd.noregistrasifk
        inner join pasien_m as ps on ps.id = pd.nocmfk
        inner join ruangan_m as ru on ru.id = apd.objectruanganasalfk
        inner join ruangan_m as ru1 on ru1.id = apd.objectruanganfk
        where
        pd.kdprofile = $kdProfile AND
        pd.statusenabled = true AND
        apd.statusenabled = true
        AND pd.tglregistrasi between '$r[dari]' AND '$r[sampai]'
        AND ru.objectdepartemenfk in ($depRJ)
        AND ru1.objectdepartemenfk in ($depRI);
        "));

        $totalAll = count($data);
        $jumlah = 0;
        foreach ($data as $d) {
            if ($d->selisihwaktu <= 60) {
                $jumlah++;
            }
        }
        $result = array(
            'data' => $data,
            'persentase' => ($totalAll > 0) ? ($jumlah / $totalAll * 100) : 0,
        );

        return $this->respond($result);
    }


    public function lapReceivable(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $rekananfk = isset($r['rekananfk']) && $r['rekananfk'] != '' ? $r['rekananfk'] : null;


        $data = DB::select(DB::raw(" 
        SELECT x.tglposting, x.noposting, x.namarekanan, SUM(x.totalpenjamin) AS tagihan, SUM(x.sumtotalsudahdibayar) AS totalbayar, MAX(x.tglsbm) AS tglbayar, 
        x.status FROM ( SELECT sp.norec, sp.tglposting, php.noposting, rkn.namarekanan, sbm.tglsbm, 
        SUM(spp.totalppenjamin) AS totalpenjamin, 
        SUM(spp.totalsudahdibayar) AS sumtotalsudahdibayar,
        COUNT(php.noposting) AS jlhpasien, 
        CASE WHEN spp.totaldiskon IS NULL THEN 0 ELSE spp.totaldiskon END AS totaldiskon, 
        CASE WHEN SUM(spp.totalppenjamin) = SUM(spp.totalsudahdibayar) THEN 'Lunas' ELSE 'Belum Lunas' END AS status
        FROM postinghutangpiutang_t AS php
            INNER JOIN strukpelayananpenjamin_t AS spp ON spp.norec = php.nostrukfk
            INNER JOIN strukpelayanan_t AS spy ON spy.norec = spp.nostrukfk
            INNER JOIN pasiendaftar_t AS pd ON pd.norec = spy.noregistrasifk
            LEFT JOIN rekanan_m AS rkn ON rkn.ID = pd.objectrekananfk
            INNER JOIN strukposting_t AS sp ON sp.noposting = php.noposting
            INNER JOIN loginuser_s AS lu ON sp.kdhistorylogins = lu.ID
            INNER JOIN pegawai_m AS P ON lu.objectpegawaifk = P.ID
            LEFT JOIN strukkwitansipiutang_t AS skp ON skp.norec = php.strukkwitansipiutangfk
            LEFT JOIN strukbuktipenerimaan_t AS sbm ON sbm.nostrukfk = spp.nostrukfk
        WHERE php.kdprofile = :kdProfile AND sp.statusenabled = 1 AND php.statusenabled = 1 AND (rkn.ID = :rekananfk OR :rekananfk IS NULL)
        GROUP BY sp.norec, php.noposting, sp.tglposting, rkn.namarekanan, php.statusenabled, sbm.tglsbm, spp.totaldiskon
    ) AS x GROUP BY x.noposting, x.namarekanan, x.tglposting, x.status"), ['kdProfile' => $kdProfile, 'rekananfk' => $rekananfk]);

        $dataALL = [];
        foreach ($data as $item) {
            $kumpulna = $item->namarekanan . '-' . $item->noposting;
            if (!isset($dataALL[$kumpulna])) {
                $tglposting = Carbon::parse($item->tglposting);
                $tglsbm = Carbon::parse($item->tglbayar);
                $umur = $tglsbm->diffInDays($tglposting);
                $sisautang = $item->tagihan - $item->totalbayar;

                $dataALL[$kumpulna] = [
                    'namarekanan' => $item->namarekanan,
                    'noposting' => $item->noposting,
                    'tglposting' => $item->tglposting,
                    'tglsbm' => $item->tglbayar,
                    'tagihan' =>  $item->tagihan,
                    'umur' => $umur,
                    'sudahbayar' => 0,
                    'status' => $item->status,
                    'sisautang' => $sisautang,
                    'bulan1' => [
                        'sudahbayar' => 0,
                        'persen' => 0,
                    ],
                    'bulan3' => [
                        'sudahbayar' => 0,
                        'persen' => 0,

                    ],
                    'bulan6' => [
                        'sudahbayar' => 0,
                        'persen' => 0,

                    ],
                    'bulan12' => [
                        'sudahbayar' => 0,
                        'persen' => 0,

                    ],
                    'lebihdari12' => [
                        'sudahbayar' => 0,
                        'persen' => 0,

                    ],
                ];
            }


            if ($item->tglbayar !== null || $item->totalbayar !== null) {
                $sudahBayar = $item->totalbayar;
                $dataALL[$kumpulna]['sudahbayar'] += $sudahBayar;
                $persen = ($sudahBayar / $item->tagihan) * 100;
                $tglsbm = Carbon::parse($item->tglbayar);
                $tglposting = Carbon::parse($item->tglposting);
                $dayDiff = $tglsbm->diffInDays($tglposting);
                if ($dayDiff <= 30) {
                    $bulanKey = 'bulan1';
                } elseif ($dayDiff <= 60) {
                    $bulanKey = 'bulan3';
                } elseif ($dayDiff <= 90) {
                    $bulanKey = 'bulan6';
                } elseif ($dayDiff <= 180) {
                    $bulanKey = 'bulan12';
                } else {
                    $bulanKey = 'lebihdari12';
                }

                $dataALL[$kumpulna][$bulanKey]['sudahbayar'] += $sudahBayar;
                $dataALL[$kumpulna][$bulanKey]['persen'] = $persen;
                // $test[] = $dataALL;
            }
        }

        $dataALL = array_values($dataALL);

        $result = [
            'data' => $dataALL,
            'count' => count($dataALL)


        ];
        return $this->respond($result);
    }


    // public function lapPayable(Request $r)
    // {
    //     $kdProfile =  $this->kdProfile;
    //     $kdTransaksiBK = (int) $this->settingFix('kdTransaksiBK');
    //     $kdPembayaran = (int) $this->settingFix('kdPembayaranSup');

    //     $data = DB::select(DB::raw("
    //    SELECT xx.tglstruk, xx.tgljatuhtempo, xx.namarekanan, xx.subtotal, xx.sisautang, (
    // 	CASE WHEN xx.totalbayar = 0 THEN 'BELUM LUNAS' WHEN xx.totalbayar <> 0  AND xx.totalbayar > 0 AND xx.sisautang <> 0 THEN 'BELUM LUNAS'  WHEN xx.totalbayar = xx.subtotal OR xx.sisautang = 0 THEN
    // 	'LUNAS' ELSE'BELUM LUNAS' END ) AS status, xx.tglsbk, xx.totalbayar FROM (SELECT x.tglstruk, x.tgljatuhtempo, x.namarekanan, ( ( x.total - x.totaldiskon ) + x.totalppn ) AS subtotal,
    //   x.totaldibayar AS totalbayar, (CASE WHEN x.totalsisahutang IS NULL THEN ( ( x.total - x.totaldiskon ) + x.totalppn ) ELSE x.totalsisahutang END ) AS sisautang, x.tglsbk
    // 	FROM (SELECT sp.tglstruk, sp.nostruk, sp.totalhargasatuan AS total, sp.totalppn AS totalppn, sp.totaldiscount AS totaldiskon, sp.totalsudahdibayar AS totaldibayar, sp.totalsudahdibayar AS totalsudahdibayar, sp.tgljatuhtempo,
    // 	rkn.namarekanan, sp.totalbelumdibayar AS totalsisahutang, sbk.tglsbk, sp.objectrekananfk FROM strukpelayanan_t AS sp
    // 	INNER JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
    // 	LEFT JOIN rekanan_m AS rkn ON rkn.ID = sp.objectrekananfk
    // 	LEFT JOIN strukbuktipengeluaran_t AS sbk ON sbk.norec = sp.nosbklastfk
    // 	AND sbk.objectkelompoktransaksifk = 107
    // 	AND sbk.statusenabled = TRUE
    //     LEFT JOIN strukverifikasi_t AS sv ON sv.norec = spd.noverifikasifk
    //     LEFT JOIN ruangan_m AS ru ON ru.ID = sv.objectruanganfk
    //     LEFT JOIN strukcollecting_t AS sc ON sc.norec = sp.strukcollectingfk
    // 	WHERE
    // 	sp.kdprofile = 1
    // 	AND sp.objectkelompoktransaksifk = 432
    // 	AND sp.tglstruk between '$r[dari]' AND '$r[sampai]'
    // 	GROUP BY
    // 	sp.norec, sp.tglstruk, sp.nosbklastfk, sbk.totaldibayar, sbk.totalsudahdibayar, sp.tgljatuhtempo, rkn.namarekanan, sbk.totalsisahutang, sbk.tglsbk) AS x) AS xx ;
    //     "));

    //     $dataGrouped = [];
    //     $test = [];
    //     foreach ($data as $item) {
    //         $patientKey = $item->namarekanan;
    //         if (!isset($dataGrouped[$patientKey])) {
    //             $tglstruk = Carbon::parse($item->tglstruk);
    //             $tglsbk = Carbon::parse($item->tglsbk);
    //             $umur = $tglsbk->diffInDays($tglstruk);
    //             // $sisautang = $item->subtotal - $item->sudahbayar;

    //             $dataGrouped[$patientKey] = [
    //                 'namarekanan' => $item->namarekanan,
    //                 'tglstruk' => $item->tglstruk,
    //                 'tglsbk' => $item->tglsbk,
    //                 'totaltagihan' =>  $item->subtotal,
    //                 'umur' => $umur,
    //                 'sudahbayar' => 0,
    //                 'status' => $item->status,
    //                 'sisautang' =>  $item->subtotal - $item->totalbayar,
    //                 'bulan3' => [
    //                     'totaldibayar' => 0,
    //                     'sudahbayar' => 0,
    //                     'persen' => 0,
    //                     'sisa' => 0,
    //                 ],
    //                 'bulan6' => [
    //                     'totaldibayar' => 0,
    //                     'sudahbayar' => 0,
    //                     'persen' => 0,
    //                     'sisa' => 0,
    //                 ],
    //                 'bulan9' => [
    //                     'totaldibayar' => 0,
    //                     'sudahbayar' => 0,
    //                     'persen' => 0,
    //                     'sisa' => 0,
    //                 ],
    //                 'bulan12' => [
    //                     'totaldibayar' => 0,
    //                     'sudahbayar' => 0,
    //                     'persen' => 0,
    //                     'sisa' => 0,
    //                 ],
    //                 'lebihdari12' => [
    //                     'totaldibayar' => 0,
    //                     'sudahbayar' => 0,
    //                     'persen' => 0,
    //                     'sisa' => 0,
    //                 ],
    //             ];
    //         }


    //         if ($item->tglsbk !== null || $item->totalbayar !== null) {
    //             $sudahBayar = $item->totalbayar;
    //             $dataGrouped[$patientKey]['sudahbayar'] += $sudahBayar;
    //             $sisa = $item->subtotal - $dataGrouped[$patientKey]['sudahbayar'];
    //             $persen = ($sudahBayar / $item->subtotal) * 100;
    //             $tglsbk = Carbon::parse($item->tglsbk);
    //             $tglstruk = Carbon::parse($item->tglstruk);
    //             $dayDiff = $tglsbk->diffInDays($tglstruk);
    //             if ($dayDiff <= 30) {
    //                 $bulanKey = 'bulan3';
    //             } elseif ($dayDiff <= 60) {
    //                 $bulanKey = 'bulan6';
    //             } elseif ($dayDiff <= 90) {
    //                 $bulanKey = 'bulan9';
    //             } elseif ($dayDiff <= 180) {
    //                 $bulanKey = 'bulan12';
    //             } else {
    //                 $bulanKey = 'lebihdari12';
    //             }
    //             $dataGrouped[$patientKey][$bulanKey]['totaldibayar'] += $item->totalbayar;
    //             $dataGrouped[$patientKey][$bulanKey]['sudahbayar'] += $sudahBayar;
    //             $dataGrouped[$patientKey][$bulanKey]['sisa'] = $sisa;
    //             $dataGrouped[$patientKey][$bulanKey]['persen'] = $persen;
    //             // $test[] = $dataGrouped;
    //         }
    //     }
    //     $dataGrouped = array_values($dataGrouped);


    //     $result = [
    //         'data' => $dataGrouped,
    //         'count' => count($dataGrouped)


    //     ];
    //     return $this->respond($result);
    // }

    public function lapInventory(Request $r)
    {

        $kdProfile =  $this->kdProfile;
        $rekananfk = isset($r['rekananfk']) && $r['rekananfk'] != '' ? $r['rekananfk'] : null;

        $data = DB::select(DB::raw("
    SELECT xx.norec, xx.tglstruk, xx.tgljatuhtempo, xx.tglsbk, xx.namarekanan, xx.subtotal, xx.totalbayar, xx.sisautang,
    (CASE 
        WHEN xx.totalbayar = 0 THEN 'BELUM LUNAS' 
        WHEN xx.totalbayar <> 0 AND xx.totalbayar > 0 AND xx.sisautang <> 0 THEN 'BELUM LUNAS'  
        WHEN xx.totalbayar = xx.subtotal OR xx.sisautang = 0 THEN 'LUNAS' 
        ELSE 'BELUM LUNAS'
    END) AS status 
    FROM (
        SELECT x.tglstruk, x.tgljatuhtempo, x.namarekanan,  ((x.total - x.totaldiskon) + x.totalppn) AS subtotal, 
        x.totaldibayar AS totalbayar, COALESCE(x.totalsisahutang, ((x.total - x.totaldiskon) + x.totalppn)) AS sisautang,
        x.tglsbk, x.norec 
        FROM (
            SELECT sp.tglstruk, sp.totalhargasatuan AS total, sp.totalppn AS totalppn, sp.totaldiscount AS totaldiskon, 
            sp.totalsudahdibayar AS totaldibayar, sp.totalsudahdibayar AS totalsudahdibayar, sp.tgljatuhtempo, 
            rkn.namarekanan, sp.totalbelumdibayar AS totalsisahutang, sbk.tglsbk, sp.norec 
            FROM strukpelayanan_t AS sp
            INNER JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
            LEFT JOIN rekanan_m AS rkn ON rkn.ID = sp.objectrekananfk
            LEFT JOIN strukbuktipengeluaran_t AS sbk ON sbk.norec = sp.nosbklastfk AND sbk.objectkelompoktransaksifk = 107  
            AND sbk.statusenabled = TRUE
            LEFT JOIN strukverifikasi_t AS sv ON sv.norec = spd.noverifikasifk
            LEFT JOIN ruangan_m AS ru ON ru.ID = sv.objectruanganfk
            LEFT JOIN strukcollecting_t AS sc ON sc.norec = sp.strukcollectingfk
            WHERE sp.kdprofile = :kdProfile AND sp.objectkelompoktransaksifk = '518' AND (rkn.ID = :rekananfk OR :rekananfk IS NULL) AND sp.statusenabled = true 
            GROUP BY sp.norec, sp.tglstruk, sp.nosbklastfk, sbk.totaldibayar, sbk.totalsudahdibayar, sp.tgljatuhtempo, 
            rkn.namarekanan, sbk.totalsisahutang, sbk.tglsbk
        ) AS x
    ) AS xx;"), ['kdProfile' => $kdProfile, 'rekananfk' => $rekananfk]);

        // $result = [];
        // foreach ($data as $item) {
        //     $details = DB::select(
        //         DB::raw("SELECT spd.norec, pro.namaproduk, spd.qtyproduk,  sp.totalsudahdibayar, ((spd.hargasatuan * spd.qtyproduk) - spd.hargadiscount) + spd.hargappn AS subtotal,
        //         ((sp.totalhargasatuan - sp.totaldiscount) + sp.totalppn) AS totalall,
        //         (sp.totalsudahdibayar / ((sp.totalhargasatuan - sp.totaldiscount) + sp.totalppn)) * ((spd.hargasatuan * spd.qtyproduk) - spd.hargadiscount) AS nominal,
        //         (((sp.totalsudahdibayar / ((sp.totalhargasatuan - sp.totaldiscount) + sp.totalppn)) * ((spd.hargasatuan * spd.qtyproduk) - spd.hargadiscount) / ((spd.hargasatuan * spd.qtyproduk) - spd.hargadiscount) + spd.hargappn)) * 100 AS persen
        //         FROM strukpelayanandetail_t AS spd
        //         INNER JOIN strukpelayanan_t AS sp ON spd.nostrukfk = sp.norec
        //         INNER JOIN produk_m AS pro ON pro.ID = spd.objectprodukfk
        //         WHERE spd.kdprofile = $kdProfile AND nostrukfk = :norec"),
        //         array(
        //             'norec' => $item->norec,
        //         )
        //     );


        //     $result[] = array(
        //         'tglstruk' => $item->tglstruk,
        //         'rekanan' => $item->namarekanan,
        //         'totalharusdibayar' => $item->subtotal,
        //         'totalbayar' => $item->totalbayar,
        //         'sisahutang' => $item->sisautang,
        //         'details' => $details
        //     );
        // }


        $dataGrouped = [];
        foreach ($data as $item) {
            $grup = $item->namarekanan;
            if (!isset($dataGrouped[$grup])) {
                $tglstruk = Carbon::parse($item->tglstruk);
                $tglsbk = Carbon::parse($item->tglsbk);
                $umur = $tglsbk->diffInDays($tglstruk);

                $dataGrouped[$grup] = [
                    'namarekanan' => $item->namarekanan,
                    'tglstruk' => $item->tglstruk,
                    'tglsbk' => $item->tglsbk,
                    'totaltagihan' =>  $item->subtotal,
                    'umur' => $umur,
                    'sudahbayar' => 0,
                    'status' => $item->status,
                    'sisautang' =>  $item->subtotal - $item->totalbayar,
                    'bulan3' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'persen' => 0,
                        'sisa' => 0,
                    ],
                    'bulan6' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'persen' => 0,
                        'sisa' => 0,
                    ],
                    'bulan9' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'persen' => 0,
                        'sisa' => 0,
                    ],
                    'bulan12' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'persen' => 0,
                        'sisa' => 0,
                    ],
                    'lebihdari12' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'persen' => 0,
                        'sisa' => 0,
                    ],
                ];
            }


            if ($item->tglsbk !== null || $item->totalbayar !== null) {
                $sudahBayar = $item->totalbayar;
                $dataGrouped[$grup]['sudahbayar'] += $sudahBayar;
                $sisa = $item->subtotal - $dataGrouped[$grup]['sudahbayar'];
                $persen = ($sudahBayar / $item->subtotal) * 100;
                $tglsbk = Carbon::parse($item->tglsbk);
                $tglstruk = Carbon::parse($item->tglstruk);
                $dayDiff = $tglsbk->diffInDays($tglstruk);
                if ($dayDiff <= 30) {
                    $bulanKey = 'bulan3';
                } elseif ($dayDiff <= 60) {
                    $bulanKey = 'bulan6';
                } elseif ($dayDiff <= 90) {
                    $bulanKey = 'bulan9';
                } elseif ($dayDiff <= 180) {
                    $bulanKey = 'bulan12';
                } else {
                    $bulanKey = 'lebihdari12';
                }
                $dataGrouped[$grup][$bulanKey]['totaldibayar'] += $item->totalbayar;
                $dataGrouped[$grup][$bulanKey]['sudahbayar'] += $sudahBayar;
                $dataGrouped[$grup][$bulanKey]['sisa'] = $sisa;
                $dataGrouped[$grup][$bulanKey]['persen'] = $persen;
                // $test[] = $dataGrouped;
            }
        }

        $totalAll = 0;
        $totalSisa = 0;
        $totalBayar = 0;
        foreach ($data as $d) {
            $totalAll =    $totalAll + (float) $d->subtotal;
            $totalBayar = $totalBayar + (float) $d->totalbayar;
            $totalSisa =    $totalSisa + ($totalAll -  $totalBayar);
        }


        $dataGrouped = array_values($dataGrouped);

        $result = [
            'data' => $dataGrouped,
            'sisahutang' => $totalSisa,
            'totalTagihan' => $totalAll,
            'totalbayar' => $totalBayar,
        ];
        return $this->respond($result);
    }

    public function saveBORLOSPerRuangan(Request $r, $lokal = false)
    {
        DB::beginTransaction();
        try {
            $idProfile = $this->kdProfile;
            $tglAwal = $r['tanggal'] . ' 00:00';
            $tglAkhir =  $r['tanggal']  . ' 23:59';

            $sehari = 1;
            $jumlahTT = collect(DB::select("
            SELECT
            ru.namaruangan,count(ru.namaruangan) as jml_tt, kmr.objectruanganfk as ruid
            FROM tempattidur_m AS tt
            INNER JOIN kamar_m AS kmr ON kmr.id = tt.objectkamarfk
            INNER JOIN ruangan_m AS ru ON ru.id = kmr.objectruanganfk
            WHERE  tt.kdprofile = $idProfile
            AND tt.statusenabled = true
            AND kmr.statusenabled = true
            and ru.statusenabled = true
            group by  ru.namaruangan,kmr.objectruanganfk
            "));


            $idStatKelMeninggal = (int) $this->settingFix('KdStatKeluarMeninggal', $idProfile);
            $idKondisiPasienMeninggal = (int) $this->settingFix('KdKondisiPasienMeninggal', $idProfile);

            $idDepRanap = $this->settingFix('kdDepartemenRanapFix');
            $data = collect(DB::select("
            select sum(z.jumlahmeninggal) as jumlahmeninggal,
            sum(z.jumlahlebih48) as jumlahlebih48,
            sum(z.jumlahhariperawatan) as jumlahhariperawatan,
            sum(z.lamarawat) as lamarawat,
            sum(z.jumlahpasienpulang) as jumlahpasienpulang,
            z.namaruangan,z.ruid
            from (
                SELECT 0 as jumlahmeninggal, 0 as jumlahlebih48 , count(pd.noregistrasi) as jumlahhariperawatan, 0 as lamarawat,
                0 as jumlahpasienpulang, ru.namaruangan,pd.objectruanganlastfk as ruid
                FROM pasiendaftar_t AS pd
                INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
                WHERE pd.kdprofile =$idProfile
                AND pd.tglpulang IS NULL
                AND pd.statusenabled = TRUE
                and ru.objectdepartemenfk in ( $idDepRanap)
                group by ru.namaruangan,pd.objectruanganlastfk

                union all

                select 0 as jumlahmeninggal, 0 as jumlahlebih48 ,0 as jumlahhariperawatan, sum(x.hari) as lamarawat, count(x.noregistrasi) as jumlahpasienpulang,
                x.namaruangan,x.ruid
                from (
                        SELECT
                        date_part('DAY', case when  pd.tglpulang is null then now() else  pd.tglpulang end - pd.tglregistrasi) +1  as hari ,pd.noregistrasi,
                        ru.namaruangan, pd.objectruanganlastfk as ruid
                        FROM
                        pasiendaftar_t AS pd
                        INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
                        WHERE pd.kdprofile = $idProfile and
                        pd.tglpulang BETWEEN  '$tglAwal'AND '$tglAkhir'
                        and pd.tglpulang is not null
                        and pd.statusenabled=true
                        and  ru.objectdepartemenfk in ($idDepRanap)
                ) as x group by   x.namaruangan,x.ruid

                union all

                select count(x.noregistrasi) as jumlahmeninggal,
                count(case when x.objectkondisipasienfk = $idKondisiPasienMeninggal then 1 end ) AS jumlahlebih48,
                0 as jumlahhariperawatan, 0 as lamarawat,0 as jumlahpasienpulang,
                x.namaruangan,x.ruid
                FROM
                (
                    select noregistrasi,to_char(tglregistrasi , 'mm')  as bulanregis ,statuskeluar,kondisipasien,objectkondisipasienfk,
                    ru.namaruangan,pasiendaftar_t.objectruanganlastfk as ruid
                    from pasiendaftar_t
                    join statuskeluar_m on statuskeluar_m.id =pasiendaftar_t.objectstatuskeluarfk
                    join ruangan_m  ru on ru.id =pasiendaftar_t.objectruanganlastfk
                    left join kondisipasien_m on kondisipasien_m.id =pasiendaftar_t.objectkondisipasienfk
                    where pasiendaftar_t.kdprofile = $idProfile and objectstatuskeluarfk = $idStatKelMeninggal
                    and  tglpulang BETWEEN '$tglAwal'AND '$tglAkhir'
                    and pasiendaftar_t.statusenabled=true
                ) as x
                group by 	x.namaruangan,x.ruid
                ) as z group by z.namaruangan,z.ruid
              "));
            $data10 = [];
            foreach ($data as $k =>  $d) {
                $data10[] = array(
                    'norec' => $this->Uuid4(),
                    'kdprofile' => $idProfile,
                    'statusenabled' => true,
                    'bor' => 0,
                    'alos' => 0,
                    'bto' => 0,
                    'toi' => 0,
                    'gdr' => 0,
                    'ndr' => 0,
                    'lamarawat' => 0,
                    'hariperawatan' => 0,
                    'pasienpulang' => 0,
                    'meninggal' => 0,
                    'matilebih48' =>  0,
                    'tanggal' => $r['tanggal'],
                    'insertdate' => date('Y-m-d H:i:s'),
                    'jmltempattidur' => 0,
                    'ruanganfk' => $d->ruid,
                    'namaruangan' => $d->namaruangan,
                    'jmlruang' => 0
                );
                foreach ($jumlahTT as $dd) {
                    if ($d->ruid == $dd->ruid) {
                        $data10[$k]['jmlruang'] = count($jumlahTT);
                        $data10[$k]['lamarawat'] = $d->lamarawat;
                        $data10[$k]['hariperawatan'] = $d->jumlahhariperawatan;
                        $data10[$k]['meninggal'] = $d->jumlahmeninggal;
                        $data10[$k]['matilebih48'] = $d->jumlahlebih48;
                        $data10[$k]['jmltempattidur'] = $dd->jml_tt;
                        $data10[$k]['pasienpulang'] = $d->jumlahpasienpulang;
                        $data10[$k]['bor'] =  (float) number_format(((int)$d->jumlahhariperawatan * 100 / ($dd->jml_tt *  (float)$sehari)), 2);
                        $alos = 0;
                        $ndr = 0;
                        $gdr = 0;
                        $toi = 0;
                        if ((int)$d->jumlahpasienpulang > 0) {
                            $alos = (int)$d->lamarawat / (int)$d->jumlahpasienpulang;
                            $gdr = (int) $d->jumlahmeninggal * 1000 /  (int)$d->jumlahpasienpulang;
                            $ndr = (int) $d->jumlahlebih48 * 1000 / (int)$d->jumlahpasienpulang;
                        }
                        $data10[$k]['alos'] =  (float) number_format($alos, 2);
                        $data10[$k]['bto'] = (float) number_format((int)$d->jumlahpasienpulang / $dd->jml_tt, 2);
                        $data10[$k]['toi'] = (float) number_format($toi, 2);
                        $data10[$k]['gdr'] = (float) number_format($ndr, 2);
                        $data10[$k]['ndr'] = (float) number_format($gdr, 2);
                    }
                }
            }



            $new  = DB::table('closingborlostoidetail_t')->where('tanggal', $r['tanggal'])->first();

            if (empty($new)) {
                $insert = DB::table('closingborlostoidetail_t')->insert(
                    $data10
                );
            } else {
                $insert =  DB::table('closingborlostoidetail_t')
                    ->where('norec', $new->norec)
                    ->update(
                        $data10
                    );
            }

            $transMessage = "Sukses BOR DETAIL";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => 'Ok',
                "data" => $insert
            );
        } catch (Exception $e) {
            $transMessage = "Simpan BOR DETAIL Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $result;
    }

    public function getPenjualan(Request $r)
    {
        $kdProfile =  $this->kdProfile;

        $data = DB::select(DB::raw("SELECT prd.namaproduk AS produk,djp.detailjenisproduk as jenis, pp.tglpelayanan AS tglpelayanan, pp.jumlah AS terjual, ((pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah) + COALESCE(pp.jasa, 0) AS total, sp1.tglstruk AS tglstruk, spd.qtyproduk as jumlah
        FROM pelayananpasien_t AS pp
        INNER JOIN produk_m AS prd ON prd.ID = pp.produkfk
        JOIN detailjenisproduk_m AS djp ON prd.objectdetailjenisprodukfk = djp.id
        JOIN jenisproduk_m AS jp ON djp.objectjenisprodukfk = jp.id
        LEFT JOIN stokprodukdetail_t AS spt ON spt.objectprodukfk = prd.id
        LEFT JOIN strukpelayanan_t AS sp1 ON sp1.norec = pp.strukterimafk
        LEFT JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp1.norec
        WHERE pp.statusenabled = true AND pp.strukresepfk IS NOT NULL AND pp.kdprofile = $kdProfile AND pp.tglpelayanan BETWEEN '$r[dari]' AND '$r[sampai]'
        GROUP BY prd.namaproduk, pp.tglpelayanan, pp.jumlah, pp.hargasatuan, pp.hargadiscount, pp.jumlah, pp.jasa, djp.detailjenisproduk, pp.strukterimafk, sp1.tglstruk, spd.qtyproduk

        UNION ALL

        SELECT pr.namaproduk AS produk, djp.detailjenisproduk as jenis, spd.tglpelayanan AS tglpelayanan, spd.qtyproduk AS terjual, ((spd.hargasatuan - spd.hargadiscount) * spd.qtyproduk) + spd.hargatambahan AS total, MAX(sp1.tglstruk) as tglstruk, MAX(spd1.qtyproduk) as jumlah
        FROM strukpelayanan_t AS sp
        LEFT JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
        LEFT JOIN produk_m AS pr ON pr.id = spd.objectprodukfk
        JOIN detailjenisproduk_m AS djp ON pr.objectdetailjenisprodukfk = djp.id
        JOIN jenisproduk_m AS jp ON djp.objectjenisprodukfk = jp.id
        LEFT JOIN stokprodukdetail_t AS spt ON spt.objectprodukfk = pr.id
        LEFT JOIN strukpelayanan_t AS sp1 ON sp1.norec = spt.nostrukterimafk
        LEFT JOIN strukpelayanandetail_t AS spd1 ON spd1.nostrukfk = sp1.norec
        WHERE sp.kdprofile = $kdProfile AND sp.tglstruk BETWEEN '$r[dari]' AND '$r[sampai]' AND SUBSTRING(sp.nostruk, 1, 2) = 'OB' AND sp.statusenabled = true AND pr.statusenabled = true AND sp1.tglstruk IS NOT NULL
        GROUP BY spd.tglpelayanan, pr.namaproduk, spd.qtyproduk, spd.hargasatuan, spd.hargadiscount, spd.hargatambahan, sp.nostruk, djp.detailjenisproduk;
        "));

        // return $data;

        $dataGrouped = [];
        foreach ($data as $item) {
            $kumpulkeun = $item->jenis;
            if (!isset($dataGrouped[$kumpulkeun])) {
                $tglstruk = Carbon::parse($item->tglstruk);
                $tglpelayanan = Carbon::parse($item->tglpelayanan);
                $umur =  $tglpelayanan->diffInDays($tglstruk);


                $dataGrouped[$kumpulkeun] = [
                    'jenisproduk' => $item->jenis,
                    'namaproduk' => $item->produk,
                    'tglstruk' => $item->tglstruk,
                    'tglpelayanan' => $item->tglpelayanan,
                    'umur' => $umur,
                    'stok' => $item->jumlah,
                    'sisa' => 0,
                    'terjual' => 0,
                    'bulan3' => [

                        'terjual' => 0,
                        'nominal' => 0,
                        'persen' => 0,
                    ],
                    'bulan6' => [

                        'terjual' => 0,
                        'nominal' => 0,
                        'persen' => 0,

                    ],
                    'bulan9' => [

                        'terjual' => 0,
                        'nominal' => 0,
                        'persen' => 0,

                    ],
                    'bulan12' => [

                        'terjual' => 0,
                        'nominal' => 0,
                        'persen' => 0,

                    ],
                    'lebihdari12' => [

                        'terjual' => 0,
                        'nominal' => 0,
                        'persen' => 0,

                    ]
                ];
            }


            if ($item->tglpelayanan !== null || $item->sisastok == 0) {
                $terjual = $item->terjual;
                $dataGrouped[$kumpulkeun]['terjual'] = $terjual;
                $sisa = $item->jumlah - $dataGrouped[$kumpulkeun]['terjual'];
                $persen = ($item->terjual / $item->jumlah) * 100;
                $nominal = $item->total;
                $tglpelayanan = Carbon::parse($item->tglpelayanan);
                $tglstruk = Carbon::parse($item->tglstruk);
                $dayDiff = $tglpelayanan->diffInDays($tglstruk);
                if ($dayDiff <= 30) {
                    $bulanKey = 'bulan3';
                } elseif ($dayDiff <= 60) {
                    $bulanKey = 'bulan6';
                } elseif ($dayDiff <= 90) {
                    $bulanKey = 'bulan9';
                } elseif ($dayDiff <= 180) {
                    $bulanKey = 'bulan12';
                } else {
                    $bulanKey = 'lebihdari12';
                }
                $dataGrouped[$kumpulkeun][$bulanKey]['nominal'] += $nominal;
                $dataGrouped[$kumpulkeun][$bulanKey]['terjual'] += $terjual;
                $dataGrouped[$kumpulkeun][$bulanKey]['sisa'] = $sisa;
                $dataGrouped[$kumpulkeun][$bulanKey]['persen'] = $persen;
                // $test[] = $dataGrouped;
            }
        }

        // $totalAll = 0;
        // $totalSisa = 0;
        // $totalBayar = 0;
        // foreach ($data as $d) {
        //     $totalAll =    $totalAll + (float) $d->subtotal;
        //     $totalBayar = $totalBayar + (float) $d->totalbayar;
        //     $totalSisa =    $totalSisa + ($totalAll -  $totalBayar);
        // }

        $dataGrouped = array_values($dataGrouped);

        $result = [
            'data' => $dataGrouped
            // 'sisahutang' => $totalSisa,
            // 'totalTagihan' => $totalAll,
            // 'totalbayar' => $totalBayar,
        ];
        return $this->respond($result);
    }

    public function apiOperasional(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $depRJ = $this->settingFix('kdDepartemenRawatJalanFix');
        $depRI = $this->settingFix('idDepRawatInap');
        $depIGD = $this->settingFix('idDepartemenIGD');
        $depRadiologi = $this->settingFix('idDepartemenRadiologi');
        $data = DB::select(DB::raw("
        select tanggal, count(tanggal) as jml ,
        sum(waktu_pelayanan_rawat_jalan_persen) as waktu_pelayanan_rawat_jalan_persen,
        sum(waktu_pemeriksaan_lab_persen) as waktu_pemeriksaan_lab_persen,
        sum(waktu_tunggu_pelayanan_radiologi_persen) as waktu_tunggu_pelayanan_radiologi_persen,
        sum(waktu_pelayanan_igd_persen) as waktu_pelayanan_igd_persen,
        sum(waktu_masuk_rawat_inap_persen) as waktu_masuk_rawat_inap_persen


        from (
        select tanggal,  sum(selisih) /sum(x.jml)*100 as waktu_pelayanan_rawat_jalan_persen,0 as waktu_pemeriksaan_lab_persen,0 as waktu_tunggu_pelayanan_radiologi_persen, 0 as waktu_pelayanan_igd_persen,0 as waktu_masuk_rawat_inap_persen
        from (	select ps.namapasien, pd.noregistrasi, pd.tglregistrasi, sr.updated_at, sr.status,
        case when   EXTRACT(HOUR FROM (sr.updated_at - pd.tglregistrasi)) * 60 + EXTRACT(MINUTE FROM (sr.updated_at - pd.tglregistrasi)) <= 120 then 1 else 0 end  AS selisih,
        to_char( pd.tglregistrasi,'yyyy-MM-dd') as tanggal,1 as jml
        from pasiendaftar_t as pd
        inner join pasien_m as ps ON ps.id = pd.nocmfk
        left join strukresep_t as sr ON sr.noregistrasi = pd.noregistrasi
        left join ruangan_m as ru ON ru.id = pd.objectruanganasalfk
        left join statuspengerjaan_m as sp ON sp.id = sr.status
        where
        pd.kdprofile = $kdProfile
        AND pd.tglregistrasi between '$r[dari]' AND '$r[sampai]'
        AND ru.objectdepartemenfk in ($depRJ)
        AND pd.statusenabled = true
        AND sr.status = 5
        ) as x group by tanggal

        UNION all

        select tanggal,0 as waktu_pelayanan_rawat_jalan_persen,

        round(  CASE
        WHEN sum(x.jml) = 0 THEN 0 -- Handle division by zero
        ELSE (sum(x.selisih)::numeric / sum(x.jml)) * 100
        ENd,2) AS waktu_pemeriksaan_lab_persen,0 as waktu_tunggu_pelayanan_radiologi_persen, 0 as waktu_pelayanan_igd_persen,0 as waktu_masuk_rawat_inap_persen
        from (
        SELECT so.tglorder as tglorder, pd.noregistrasi AS noreg, ps.namapasien as pasien, MIN(hh.tglhasil) AS tglhasil, pg.namalengkap AS validator,  case when EXTRACT(HOUR FROM (MIN(hh.tglhasil) - so.tglorder)) * 60 + EXTRACT(MINUTE FROM (MIN(hh.tglhasil) - so.tglorder))<= 60 then 1 else 0 end AS selisih,1 as jml,
        to_char( so.tglorder,'yyyy-MM-dd') as tanggal
        FROM pelayananpasien_t AS pp
        INNER JOIN strukorder_t AS so ON so.norec = pp.strukorderfk
        INNER JOIN pasiendaftar_t AS pd ON pd.noregistrasi = pp.noregistrasi
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        LEFT JOIN hasillaboratorium_t AS hh ON hh.norecpelayanan = pp.norec
        LEFT JOIN pegawai_m AS pg ON pg.id = CAST(hh.pegawaifk AS INTEGER)
        WHERE pp.kdprofile = $kdProfile
        AND hh.statusenabled = TRUE
        AND pp.statusenabled = TRUE
        AND so.tglorder BETWEEN  '$r[dari]' AND '$r[sampai]'
        GROUP BY so.tglorder, ps.namapasien, pd.noregistrasi, pg.namalengkap, pd.tglregistrasi

        UNION ALL

        SELECT so.tglorder as tglorder, hl.no_registrasi AS noreg, ps.namapasien as pasien, MIN(hl.tgl_hasil) AS tglhasil, hl.user_validasi AS validator,
            case when (EXTRACT(HOUR FROM (MIN			(hl.tgl_hasil) - so.tglorder)) * 60 + EXTRACT(MINUTE FROM (MIN(hl.tgl_hasil) - so.tglorder))) <= 60 then 1 else 0 end AS selisih
        ,1 as jml,
        to_char( so.tglorder,'yyyy-MM-dd') as tanggal
        FROM lab_hasil AS hl
        INNER JOIN strukorder_t so ON so.noorder = hl.no_order
        INNER JOIN pasien_m AS ps ON ps.nocm = hl.no_rm
        WHERE
        so.kdprofile = $kdProfile
        AND so.statusenabled = TRUE
        AND so.tglorder BETWEEN  '$r[dari]' AND '$r[sampai]'
        GROUP BY hl.no_registrasi, ps.namapasien, hl.user_validasi, so.tglorder
        ) as x group by tanggal



        union ALL

        select tanggal,0 as waktu_pelayanan_rawat_jalan_persen, 0 as waktu_pemeriksaan_lab_persen,
        round(  CASE
        WHEN sum(x.jml) = 0 THEN 0 -- Handle division by zero
        ELSE (sum(x.selisih)::numeric / sum(x.jml)) * 100
        ENd,2) as waktu_tunggu_pelayanan_radiologi_persen, 0 as waktu_pelayanan_igd_persen,0 as waktu_masuk_rawat_inap_persen
        from (
        SELECT
        ps.namapasien,
        prd.namaproduk,
        so.noregistrasi,
        ru.namaruangan,
        so.tglorder,
        pg.namalengkap AS pengorder,
        pg2.namalengkap AS dokter,
        hr.tanggalreport,
            case when  EXTRACT(HOUR FROM (hr.tanggalreport - so.tglorder)) * 60 + EXTRACT(MINUTE FROM (hr.tanggalreport - so.tglorder)) <= 60 then 1 else 0 end AS selisih,
        1 as jml,
        to_char( so.tglorder,'yyyy-MM-dd') as tanggal
        FROM
        strukorder_t AS so
        INNER JOIN orderpelayanan_t AS op ON so.norec = op.noorderfk
        INNER JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
        INNER JOIN produk_m AS prd ON prd.id = op.objectprodukfk
        INNER JOIN ruangan_m AS ru ON ru.id = op.objectruangantujuanfk
        INNER JOIN pasien_m AS ps ON ps.id = so.nocmfk
        LEFT JOIN pelayananpasien_t AS pp ON pp.strukorderfk = so.norec
        LEFT JOIN pelayananpasienpetugas_t AS ppp ON ppp.pelayananpasien = pp.norec
        INNER JOIN pegawai_m AS pg2 ON pg2.id = ppp.objectpegawaifk
        LEFT JOIN hasilradiologi_t AS hr ON pp.norec = hr.pelayananpasienfk
        WHERE
        so.kdprofile = $kdProfile
        AND so.tglorder BETWEEN  '$r[dari]' AND '$r[sampai]'
        AND ru.objectdepartemenfk in ($depRadiologi)
        AND so.statusenabled = true
        AND op.statusenabled = true
        ) as x group by tanggal


        union ALL


        select tanggal,0 as waktu_pelayanan_rawat_jalan_persen, 0 as waktu_pemeriksaan_lab_persen,0 as waktu_tunggu_pelayanan_radiologi_persen,  round(  CASE
        WHEN sum(x.jml) = 0 THEN 0 -- Handle division by zero
        ELSE (sum(x.selisih)::numeric / sum(x.jml)) * 100
        ENd,2) as waktu_pelayanan_igd_persen,0 as waktu_masuk_rawat_inap_persen
            from (
        select distinct ps.namapasien, pd.noregistrasi, pd.tglregistrasi, apd.tglmasuk,
        case when      EXTRACT(HOUR FROM (apd.tglmasuk - pd.tglregistrasi)) * 60 + EXTRACT(MINUTE FROM (apd.tglmasuk - pd.tglregistrasi))  <=240 then 1 else 0 end AS selisih,
        1 as jml,
        to_char( pd.tglregistrasi,'yyyy-MM-dd') as tanggal
        FROM antrianpasiendiperiksa_t  as apd
        inner join pasiendaftar_t as pd ON pd.norec = apd.noregistrasifk
        inner join pasien_m as ps on ps.id = pd.nocmfk
        inner join ruangan_m as ru on ru.id = apd.objectruanganasalfk
        where
        pd.kdprofile = $kdProfile AND
        pd.statusenabled = true
        AND ru.objectdepartemenfk in ($depIGD)
        AND pd.tglregistrasi BETWEEN  '$r[dari]' AND '$r[sampai]'
        ) as x GROUP BY x.tanggal


        union ALL

        select tanggal,0 as waktu_pelayanan_rawat_jalan_persen, 0 as waktu_pemeriksaan_lab_persen,0 as waktu_tunggu_pelayanan_radiologi_persen,
        0 as waktu_pelayanan_igd_persen,	 round(  CASE
        WHEN sum(x.jml) = 0 THEN 0 -- Handle division by zero
        ELSE (sum(x.selisih)::numeric / sum(x.jml)) * 100
        ENd,2) as waktu_masuk_rawat_inap_persen
        from (
        select distinct ps.namapasien, pd.noregistrasi, pd.tglregistrasi, apd.tglmasuk, ru.namaruangan as ruanganasal, ru1.namaruangan as ruangantujuan,
        case when    EXTRACT(HOUR FROM (apd.tglmasuk - pd.tglregistrasi)) * 60 + EXTRACT(MINUTE FROM (apd.tglmasuk - pd.tglregistrasi))  <=60 then 1 else 0 end AS selisih,
        1 as jml,
        to_char( pd.tglregistrasi,'yyyy-MM-dd') as tanggal
        FROM antrianpasiendiperiksa_t  as apd
        inner join pasiendaftar_t as pd ON pd.norec = apd.noregistrasifk
        inner join pasien_m as ps on ps.id = pd.nocmfk
        inner join ruangan_m as ru on ru.id = apd.objectruanganasalfk
        inner join ruangan_m as ru1 on ru1.id = apd.objectruanganfk
        where
        pd.kdprofile = $kdProfile AND
        pd.statusenabled = true AND
        apd.statusenabled = true
        AND pd.tglregistrasi between  '$r[dari]' AND '$r[sampai]'
        AND ru.objectdepartemenfk in ($depRJ)
        AND ru1.objectdepartemenfk in ($depRI)
        ) as x GROUP BY x.tanggal
        ) as z
        GROUP BY tanggal

        "));
        $insert = null;
        foreach ($data as $rekap) {

            $postData = [
                "tanggal" => $rekap->tanggal,
                "detail" => [
                    "cash_conversion_cycle_days" => 0, //round($rekap->cash_conversion_cycle_days, 2),
                    "patient_satisfaction" =>  0, //round($rekap->patient_satisfaction, 2),
                    "patient_waiting_time" => 0, // round($rekap->patient_waiting_time, 2),
                    "waktu_pelayanan_rawat_jalan_persen" => round($rekap->waktu_pelayanan_rawat_jalan_persen, 2),
                    "waktu_pemeriksaan_lab_persen" => round($rekap->waktu_pemeriksaan_lab_persen, 2),
                    "waktu_tunggu_pelayanan_radiologi_persen" => round($rekap->waktu_tunggu_pelayanan_radiologi_persen, 2),
                    "pembatalan_operasi_elektif_persen" => 0, //round($rekap->pembatalan_operasi_elektif_persen, 2),
                    "waktu_pelayanan_igd_persen" => round($rekap->waktu_pelayanan_igd_persen, 2),
                    "waktu_masuk_rawat_inap_persen" => round($rekap->waktu_masuk_rawat_inap_persen, 2),
                    "realisasi_pasien_yg_direncanakan_pulang_H_min_1_persen" => 100, //round($rekap->realisasi_pasien_yg_direncanakan_pulang_H_min_1_persen, 2)
                ]
            ];
            // \Log::info(json_encode($postData));
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = 'operasional-lain';
            $objetoRequest['method'] = 'POST';
            $objetoRequest['data'] = $postData;
            $insert = $this->apiIntegrate($objetoRequest, true);

            \Log::info("MKK Matriks Operasional Lain  " . json_encode($insert));
        }

        return $this->respond($insert);
    }
    public function postBOR(Request $r)
    {
        DB::beginTransaction();
        try {
            $idProfile = $this->idRumahSakit($r);
            $cek =  MKKO::where('kdprofile', $idProfile)
                ->where('tgl', $r['tanggal'])
                ->whereIn('objectaccountfk', [26, 27, 28, 29, 33, 30, 31])
                ->delete();
            $d = $r['detail'];
            $tglAyeuna =  $r['tanggal'];

            $dataInsert = [];
            $nn = date('Y-m-d H:i:s');

            $dataInsert[] = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $idProfile,
                'statusenabled' => true,
                'objectaccountfk' =>  26,

                'jumlah' =>  $d['BOR'],
                'tgl' => $tglAyeuna,
                'created_at' => $nn
            );
            $dataInsert[] = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $idProfile,
                'statusenabled' => true,
                'objectaccountfk' =>  27,

                'jumlah' =>  $d['BTO'],
                'tgl' => $tglAyeuna,
                'created_at' => $nn
            );
            $dataInsert[] = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $idProfile,
                'statusenabled' => true,
                'objectaccountfk' =>  28,

                'jumlah' =>  $d['LOS_PASIEN_NON_JIWA'],
                'tgl' => $tglAyeuna,
                'created_at' => $nn
            );
            $dataInsert[] = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $idProfile,
                'statusenabled' => true,
                'objectaccountfk' =>  29,

                'jumlah' =>  $d['ALOS_PASIEN_NON_JIWA'],
                'tgl' => $tglAyeuna,
                'created_at' => $nn
            );


            $dataInsert[] = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $idProfile,
                'statusenabled' => true,
                'objectaccountfk' =>  30,

                'jumlah' =>  $d['LOS_PASIEN_JIWA'],
                'tgl' => $tglAyeuna,
                'created_at' => $nn
            );
            $dataInsert[] = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $idProfile,
                'statusenabled' => true,
                'objectaccountfk' =>  31,

                'jumlah' =>  $d['ALOS_PASIEN_JIWA'],
                'tgl' => $tglAyeuna,
                'created_at' => $nn
            );

            $dataInsert[] = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $idProfile,
                'statusenabled' => true,
                'objectaccountfk' =>  33,

                'jumlah' =>  $d['jumlah_tempat_tidur'],
                'tgl' => $tglAyeuna,
                'created_at' => $nn
            );

            $chunkSize = 100; // Adjust the chunk size based on your needs

            collect($dataInsert)->chunk($chunkSize)->each(function ($chunk) {
                DB::table('mkko_t')->insert($chunk->toArray());
            });
            MKKODetail::where('tgl', $tglAyeuna)->where('path', 'bor')->delete();

            $psn = new MKKODetail();
            $psn->norec = $this->Uuid4();
            $psn->kdprofile = $idProfile;
            $psn->statusenabled = true;
            $psn->tgl = $tglAyeuna;
            $psn->path = 'bor';
            $psn->detail = json_encode($r['detail']);
            $psn->save();
            $transMessage = "Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => [
                    'tanggal' => $tglAyeuna
                ]
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }

        return $result;
    }
    public function apiPOST($dataInsert)
    {
        DB::beginTransaction();
        try {
            $idProfile = $this->kdProfile;

            foreach ($dataInsert as $d) {

                $cek = MKKO::where('objectaccountfk', $d['id'])
                    ->where('tahun', $d['tahun'])
                    ->first();
                if (empty($cek)) {
                    $new = new MKKO;
                    $new->norec = $new->generateNewId();
                    $new->statusenabled = true;
                    $new->kdprofile = $idProfile;
                } else {
                    $new = $cek;
                }
                $new->objectaccountfk = isset($d['id']) ? $d['id'] : null;
                $new->tahun = isset($d['tahun']) ? $d['tahun'] : null;
                if (isset($d['jan']) && $d['jan'] != null) {
                    $new->jan = $d['jan'];
                }
                if (isset($d['feb']) && $d['feb'] != null) {
                    $new->feb = $d['feb'];
                }
                if (isset($d['mar']) && $d['mar'] != null) {
                    $new->mar = $d['mar'];
                }
                if (isset($d['apr']) && $d['apr'] != null) {
                    $new->apr = $d['apr'];
                }
                if (isset($d['mei']) && $d['mei'] != null) {
                    $new->mei = $d['mei'];
                }
                if (isset($d['jun']) && $d['jun'] != null) {
                    $new->jun = $d['jun'];
                }
                if (isset($d['jul']) && $d['jul'] != null) {
                    $new->jul = $d['jul'];
                }
                if (isset($d['agu']) && $d['agu'] != null) {
                    $new->agu = $d['agu'];
                }
                if (isset($d['sep']) && $d['sep'] != null) {
                    $new->sep = $d['sep'];
                }
                if (isset($d['okt']) && $d['okt'] != null) {
                    $new->okt = $d['okt'];
                }
                if (isset($d['nov']) && $d['nov'] != null) {
                    $new->nov = $d['nov'];
                }
                if (isset($d['des']) && $d['des'] != null) {
                    $new->des = $d['des'];
                }

                // $new->feb = isset($d['feb']) ? $d['feb'] : null;
                // $new->mar = isset($d['mar']) ? $d['mar'] : null;
                // $new->apr = isset($d['apr']) ? $d['apr'] : null;
                // $new->mei = isset($d['mei']) ? $d['mei'] : null;
                // $new->jun = isset($d['jun']) ? $d['jun'] : null;
                // $new->jul = isset($d['jul']) ? $d['jul'] : null;
                // $new->agu = isset($d['agu']) ? $d['agu'] : null;
                // $new->sep = isset($d['sep']) ? $d['sep'] : null;
                // $new->okt = isset($d['okt']) ? $d['okt'] : null;
                // $new->nov = isset($d['nov']) ? $d['nov'] : null;
                // $new->des = isset($d['des']) ? $d['des'] : null;
                $new->tgl = date('Y-m-d H:i:s');
                $new->save();
            }



            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => 'Ok'
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()

            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveBebanPendapatan($request, $data10, $resBeban, $resPenL)
    {
        $sumPerMonth = [];

        // Loop through the data array
        foreach ($data10 as $item) {
            // Extract the month from the "bulan" key
            $month = substr($item['bulan'], 0, 7);

            // If the month doesn't exist in the $sumPerMonth array, initialize it with zeros
            if (!isset($sumPerMonth[$month])) {
                $sumPerMonth[$month] = array_fill_keys(array_keys($item), 0);
                // Remove "bulan" key from initialization
                unset($sumPerMonth[$month]['bulan']);
            }

            // Accumulate the values for each key in the corresponding month
            foreach ($item as $key => $value) {
                if ($key !== 'bulan') {
                    $sumPerMonth[$month][$key] += $value;
                }
            }
        }

        $resSUM = $sumPerMonth[$request['bulan']];

        $month = substr($request['bulan'], 5, 2);

        $arrayWithMonths = [
            "jan" => null,
            "feb" => null,
            "mar" => null,
            "apr" => null,
            "mei" => null,
            "jun" => null,
            "jul" => null,
            "agu" => null,
            "sep" => null,
            "okt" => null,
            "nov" => null,
            "des" => null,
        ];

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['jkn_rajal'];
        $arrayWithMonths['id'] = 42;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['jkn_ranap'];
        $arrayWithMonths['id'] = 52;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;


        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_Asuransi_ranap'];
        $arrayWithMonths['id'] = 54;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;


        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_perusahaan_ranap'];
        $arrayWithMonths['id'] = 55;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;


        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_umum_ranap'];
        $arrayWithMonths['id'] = 56;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_Asuransi_rajal'];
        $arrayWithMonths['id'] = 44;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_perusahaan_rajal'];
        $arrayWithMonths['id'] = 45;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Eks_umum_rajal'];
        $arrayWithMonths['id'] = 46;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_Asuransi_ranap'];
        $arrayWithMonths['id'] = 58;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_perusahaan_ranap'];
        $arrayWithMonths['id'] = 59;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_umum_ranap'];
        $arrayWithMonths['id'] = 60;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_Asuransi_rajal'];
        $arrayWithMonths['id'] = 48;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_perusahaan_rajal'];
        $arrayWithMonths['id'] = 49;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['Reg_umum_rajal'];
        $arrayWithMonths['id'] = 50;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resSUM['pendapatan_lain'];
        $arrayWithMonths['id'] = 61;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;


        //beban
        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanPegBLU'];
        $arrayWithMonths['id'] = 66;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanPersediaanNonF'];
        $arrayWithMonths['id'] = 69;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanPersediaanF'];
        $arrayWithMonths['id'] = 68;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanBarangJasa'];
        $arrayWithMonths['id'] = 71;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanPemeliharaan'];
        $arrayWithMonths['id'] = 72;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanPerdin'];
        $arrayWithMonths['id'] = 73;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanPenyisihanPiut'];
        $arrayWithMonths['id'] = 74;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanamorti'];
        $arrayWithMonths['id'] = 75;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanPegAPBN'];

        $arrayWithMonths['id'] = 80;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;
        if ($resPenL != null) {

            $SURPLUS_SEBELUM_PAJAK =  ($resSUM['jumlah'] - $resBeban['jumlah']) -
                (!empty($resPenL) ? $resPenL['pendapatan_blu_lainnya'] + $resPenL['pendapatan_hibah'] + $resPenL['pend_apbn_lainnya'] : 0);

            //lainnya
            $arrayWithMonths[$this->monthMap()[$month]] = $resPenL['pendapatan_bunga_bank'];
            $arrayWithMonths['id'] = 83;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] = $resPenL['pend_lainnya'];
            $arrayWithMonths['id'] = 85;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] = $resPenL['deposito'];
            $arrayWithMonths['id'] = 84;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] = $resPenL['pend_apbn_lainnya'];
            $arrayWithMonths['id'] = 94;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] = $resPenL['pendapatan_hibah'];
            $arrayWithMonths['id'] = 95;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] = $resPenL['pendapatan_blu_lainnya'];
            $arrayWithMonths['id'] = 96;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] = $SURPLUS_SEBELUM_PAJAK;
            $arrayWithMonths['id'] = 100;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] = $SURPLUS_SEBELUM_PAJAK;
            $arrayWithMonths['id'] = 102;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] = $resPenL['manfaat_beban'];
            $arrayWithMonths['id'] = 101;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;

            $arrayWithMonths[$this->monthMap()[$month]] =   !empty($resPenL) ? ($resPenL['pendapatan_blu_lainnya'] + $resPenL['pendapatan_hibah'] + $resPenL['pend_apbn_lainnya']) : 0;
            $arrayWithMonths['id'] = 99;
            $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
            $dataInsert[] = $arrayWithMonths;
        }
        $arrayWithMonths[$this->monthMap()[$month]] = ($resSUM['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0));
        $arrayWithMonths['id'] = 77;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] = $resBeban['bebanamorti'];
        $arrayWithMonths['id'] = 78;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] =  ($resSUM['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0)) + $resBeban['bebanamorti'];
        $arrayWithMonths['id'] = 79;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;

        $arrayWithMonths[$this->monthMap()[$month]] =  ($resSUM['jumlah'] - (!empty($resBeban['jumlah']) ? $resBeban['jumlah'] : 0)) +  (!empty($resBeban) ? $resBeban['bebanamorti'] - $resBeban['bebanPegAPBN'] : 0);
        $arrayWithMonths['id'] = 81;
        $arrayWithMonths['tahun'] = substr($request['bulan'], 0, 4);
        $dataInsert[] = $arrayWithMonths;




        return $this->apiPOST($dataInsert);
    }
}
