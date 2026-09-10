<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalRujukan;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Bridging\BridgingBPJSCtrl;
use App\Models\Master\AsuransiPasien;
use App\Models\Master\Diagnosa;
use App\Models\Master\Pasien;
use App\Models\Transaksi\PemakaianAsuransi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PemakaianAsuransiCtrl extends Controller
{
    use Valet;
    protected $bridgingBPJSCtrl;
    public function __construct(BridgingBPJSCtrl $bridgingBPJSCtrl)
    {
        parent::__construct($is_encrypt = true);
        $this->bridgingBPJSCtrl = $bridgingBPJSCtrl;
    }
    public function pemakaianAsuransi(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.objectagamafk',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email',
            )
            ->where('ps.kdprofile', $this->kdProfile)
            ->where('ps.statusenabled', true);

        if (isset($r['noCm']) && $r['noCm'] != '' && $r['noCm'] != 'undefined') {
            $data = $data->where('ps.nocm', $r['noCm']);
        }
        if (isset($r['id']) && $r['id'] != '' && $r['id'] != 'undefined') {
            $data = $data->where('ps.id', $r['id']);
        }

        $data = $data->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi = null;
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $registrasi = DB::table('pasiendaftar_t as pd')
                ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
                ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
                ->leftjoin('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
                ->join('kelompokpasien_m as kps', 'kps.id', '=', 'pd.objectkelompokpasienlastfk')
                ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                ->join('jenispelayanan_m as jpl', 'jpl.id', '=', 'pd.jenispelayanan')
                ->select(
                    'pd.norec as norec_pd',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'pd.objectruanganlastfk',
                    'pd.nocmfk',
                    'ru.namaruangan',
                    'pd.objectkelasfk',
                    'kls.namakelas',
                    'pd.asalrujukanfk',
                    'pd.objectkelompokpasienlastfk',
                    'kps.kelompokpasien',
                    'pd.objectrekananfk',
                    'rk.namarekanan',
                    'pd.jenispelayanan as jenispelayananfk',
                    'jpl.jenispelayanan',
                    'pd.objectpegawaifk',
                    'pg.namalengkap as dokter',
                    'ru.objectdepartemenfk',
                    'ru.kdinternal as kdsubspesialis',
                    'pa.nosep',
                    'pd.catatan',
                    'pd.statuspasien',
                    'pg.kddokterbpjs',
                    'ru.kdsubspesialisbpjs',
                    'ru.namasubspesialisbpjs',
                    'pa.dpjplayan_kode',
                    'pa.dpjplayan_nama'

                )
                ->where('pd.norec', $r['norec_pd'])
                ->where('pd.statusenabled', true)
                ->where('pd.kdprofile', (int) $this->kdProfile)
                ->first();
                $registrasi->pemakaianasuransi = null;
            if($registrasi->nosep != null){
                $registrasi->pemakaianasuransi  = PemakaianAsuransi::where('noregistrasifk',$registrasi->norec_pd)->where('statusenabled',true)
                ->where('kdprofile', $this->kdProfile)
                ->first();
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['idDepartemenRI'] = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $result['idDepartemenRJ'] = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $result['idKelompokPasienBPJS'] = explode(',', $this->settingFix('idKelompokPasienBPJS'));
        $result['setting'] = $this->bridgingBPJSCtrl->getSetting();

        $ru =  Ruangan::mine()->get();
        $result['ruangan_RI'] = [];
        $result['ruangan_RJ'] = [];
        foreach ($ru as $item) {
            foreach ($result['idDepartemenRJ']  as $rj) {
                if ($item->objectdepartemenfk == (int)$rj) {
                    $result['ruangan_RJ'][]  = $item;
                }
            }
            foreach ($result['idDepartemenRI']  as $ri) {
                if ($item->objectdepartemenfk == (int)$ri) {
                    $result['ruangan_RI'][]  = $item;
                }
                if ($registrasi != null) {
                    $registrasi->israwatinap = false;
                    if (in_array($registrasi->objectdepartemenfk, $result['idDepartemenRI'])) {
                        $registrasi->israwatinap = true;
                    }
                }
            }
        }

        $result['jenispelayanan'] = JenisPelayanan::mine()->get();
        $result['asalrujukan'] = AsalRujukan::mine()->get();
        $result['kelompokpasien'] = KelompokPasien::mine()->get();
        $result['kelas'] = Kelas::mine()->get();
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function cetakSEP(Request $r)
    {

        $profile = $this->profile();
        $kdProfile = $this->kdProfile;
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['pdf'] = $r['pdf'];
        // start generate parameter kebutuhan save dokumen

        $registrasi = DB::table("pasiendaftar_t as pd")
        ->select('pa.nosep','pa.*','ps.namapasien','ps.nocm','ps.tgllahir','ps.objectjeniskelaminfk',
        'ap.jenispeserta','ps.nohp', 'ru.namaruangan', 'pg.namalengkap', 'ps.nobpjs')
        ->leftjoin('pemakaianasuransi_t as pa', 'pd.norec', '=', 'pa.noregistrasifk')
        ->leftjoin('asuransipasien_m as ap', 'ap.id', '=', 'pa.objectasuransipasienfk')
        ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
        ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
        ->where('pd.statusenabled', true)
        ->where('pd.kdprofile', $kdProfile);
        if(isset($r['noregistrasi']) ||  isset($r['norec_pd'])) {
            $registrasi=$registrasi ->whereRaw("(pd.noregistrasi='$r[noregistrasi]' or pd.norec='$r[norec_pd]')");
        }
        if(isset($r['nosep'])) {
            $registrasi=$registrasi ->where('pa.nosep',$r['nosep']);
        }
        $registrasi=$registrasi ->first();

        // dd($registrasi->nosurat);
        // end generate parameter kebutuhan save dokumen
        $isrujukinternal = false;
        // dd($registrasi->nosep);
        // if(empty($registrasi) || is_null($registrasi->nosep) || $registrasi->nosep === '') {
            // $r['nosep'] = "9999R9999999V000999";
            // cek vclaim
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = "SEP/" . $r['nosep'];
            $objetoRequest['method'] = "GET";
            $objetoRequest['data'] = null;
            $cariSEP =  $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
            $responseSEPVc = json_decode(json_encode($cariSEP, false));

            // dd($responseSEPVc);

            if (isset($responseSEPVc->metaData) && $responseSEPVc->metaData->code == 200) {

                // $res['sep'] = $responseSEPVc->response;
                $responseSEP = $responseSEPVc->response;

                $res['tglCreate'] =  $responseSEP->tglSep;

                $objreqhis = new \Illuminate\Http\Request();
                $objreqhis['url'] = "RencanaKontrol/nosep/".$r['nosep'];
                $objreqhis['method'] = "GET";
                $objreqhis['data'] = null;
                $cocokHistory =  $this->bridgingBPJSCtrl->bpjsTools($objreqhis, true);
                $responseHistory = json_decode(json_encode($cocokHistory, false));
                if (isset($responseHistory->metaData) && $responseHistory->metaData->code == 200) {
                    $namaProvider = $responseHistory->response->provPerujuk->nmProviderPerujuk;

                }
                $noHP = $registrasi->nohp;

            }else{
                // dd('Halo');
                $isrujukinternal = $registrasi->isrujukinternal ? true : false;
                $DPJP_LAYAN = $registrasi->dpjplayan_nama;
                $noHP = $registrasi->nohp;
                $responseSEP = [
                    "noSep" => $registrasi->nosep,
                    "tglSep" =>  $registrasi->tglsep,
                    "jnsPelayanan" =>  $registrasi->jnspelayanan == 2 ?'Rawat Jalan':'Rawat Inap',
                    "kelasRawat" =>$registrasi->klsrawathak_nama,
                    "diagnosa" => $registrasi->diagawal_nama,// "Observation for suspected disease or condition, unspecified",
                    "noRujukan" => null,
                    "poli" => strtoupper($registrasi->poli_nama),// "INSTALASI GAWAT DARURAT",
                    "poliEksekutif" =>$registrasi->eksekutif  == false ? "0":"1",
                    "catatan" => $registrasi->catatan  ,
                    "penjamin" =>  null ,
                    "peserta" => [
                            "noKartu" =>  $registrasi->nokartu  ,
                            "nama" =>   $registrasi->namapasien  ,
                            "tglLahir" =>  $registrasi->tgllahir  ,
                            "noMr" =>  $registrasi->nocm  ,
                            "kelamin" => $registrasi->objectjeniskelaminfk == 1?  "L":'P',
                            "jnsPeserta" => $registrasi->jenispeserta,
                            "hakKelas" => $registrasi->klsrawathak_nama,
                            "asuransi" => null
                        ],
                    "klsRawat" => [
                                "klsRawatHak" => $registrasi->klsrawathak_kode,
                                "klsRawatNaik" =>$registrasi->klsrawatnaik_nama,
                                "pembiayaan" =>$registrasi->pembiayaan_nama,
                                "penanggungJawab" => $registrasi->klsrawatnaik_nama,
                            ],
                    "informasi" => null,
                    "kdStatusKecelakaan" =>  $registrasi->lakalantas_kode,
                    "nmstatusKecelakaan" => $registrasi->lakalantas_nama,
                    "dpjp" => [
                                "kdDPJP" => $registrasi->dpjplayan_kode,
                                "nmDPJP" => $registrasi->dpjplayan_nama,
                                ],
                    "kontrol" => [
                                    "noSurat" =>  $registrasi->nosurat,
                                    "kdDokter" =>  $registrasi->kodedpjp,
                                    "nmDokter" =>  $registrasi->namadpjp,
                                ],
                    "lokasiKejadian" => [
                                        "tglKejadian" => $registrasi->tglkejadian,
                                        "kdProp" =>$registrasi->kdpropinsi_kode,
                                        "kdKab" =>$registrasi->kdkabupaten_kode,
                                        "kdKec" =>$registrasi->kdkecamatan_kode,
                                        "ketKejadian" =>$registrasi->keterangan,
                                        "lokasi" => null,
                                    ],
                    "cob" =>$registrasi->cob,
                    "katarak" =>  $registrasi->katarak,
                    "tujuanKunj" => [
                                            "kode" => $registrasi->tujuankun_kode,
                                            "nama" => $registrasi->tujuankun_nama,
                                        ],
                    "flagProcedure" => [
                                            "kode" => $registrasi->flagprocedure_kode,
                                            "nama" =>  $registrasi->flagprocedure_nama,
                                            ],
                    "kdPenunjang" => [
                                                "kode" => $registrasi->kdpenunjang_kode,
                                                "nama" => $registrasi->kdpenunjang_nama,
                                            ],
                    "assestmenPel" => [
                                                    "kode" => $registrasi->assesmentpel_kode,
                                                    "nama" =>$registrasi->assesmentpel_nama
                                                ],
                    "eSEP" => "False"
                    ];
    
                $responseSEP = json_decode(json_encode($responseSEP), FALSE);
                
                $DPJP_LAYAN = $registrasi->dpjplayan_nama;
            }
            $DPJP_LAYAN = $responseSEP->dpjp->nmDPJP ? $responseSEP->dpjp->nmDPJP : $registrasi->namalengkap;
            $namaProvider =$registrasi->ppkrujukan_nama;
        // }
        

        $res['tglCreate'] = '';
        $res['tglAyeuna'] = date('Y-m-d H:i:s');

            $res['tglCreate'] =  $responseSEP->tglSep;
            $res['sep'] = $responseSEP;
            $res['sep']->nmprovider =$namaProvider;
            $res['sep']->peserta->kelamin = $res['sep']->peserta->kelamin == "L" ? "Laki-Laki":"Perempuan";
            $res['sep']->peserta->nohp = "-";
            if($res['sep']->jnsPelayanan == "Rawat Inap") {
                $res['sep']->tujuanKunj->nama = "Kunjungan Kontrol (ulangan)";
                $res['sep']->flagProcedure->nama = "";

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = "RencanaKontrol/ListRencanaKontrol/Bulan/".date('m')."/Tahun/".date('Y')."/Nokartu/".$registrasi->nobpjs."/filter/2";
                // dd($objetoRequest['url']);

                $objetoRequest['method'] = "GET";
                $objetoRequest['data'] = null;
                $cariSPRI =  $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
                $responseSPRIVc = json_decode(json_encode($cariSPRI, false));
                // dd($responseSPRIVc->response->list);

                if(!isset($responseSPRIVc->response)) {
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] = "RencanaKontrol/ListRencanaKontrol/Bulan/".date('m')."/Tahun/".date('Y')."/Nokartu/".$registrasi->nobpjs."/filter/1";
                    // dd($objetoRequest['url']);
                    $objetoRequest['method'] = "GET";
                    $objetoRequest['data'] = null;
                    $cariSPRI =  $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
                    $responseSPRIVc = json_decode(json_encode($cariSPRI, false));
                }

                if(isset($responseSPRIVc->response)) {
                    if(count($responseSPRIVc->response->list) > 0){
                        $responseSPRI = $responseSPRIVc->response->list[0];
                        $res['sep']->poli = $responseSPRI->namaPoliTujuan;
                    }
                }

            } else {
                $res['sep']->tujuanKunj->nama = "Konsultasi dokter (pertama)";
                $res['sep']->flagProcedure->nama = "";
                // if($isrujukinternal){
                //     $res['sep']->tujuanKunj->nama = "Kunjungan rujukan internal";
                // }
                // switch ($res['sep']->tujuanKunj->kode) {
                //     case '0':
                //         $res['sep']->tujuanKunj->nama = "Konsultasi dokter (pertama)";
                //         $res['sep']->flagProcedure->nama = "";
                //         break;

                //     case '2':
                //         $res['sep']->tujuanKunj->nama = "Kunjungan Kontrol (ulangan)";
                //         $res['sep']->flagProcedure->nama = "";
                //         break;
                // }
                if($res['sep']->poli == 'INSTALASI GAWAT DARURAT') {
                    $res['sep']->tujuanKunj->nama = "";
                    $res['sep']->flagProcedure->nama = "";
                }
            }

            // // data pemakaian asuransi
            // $registrasi = DB::table("pemakaianasuransi_t as pa")
            // ->select('pa.*', 'ps.nohp')
            // ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'pa.noregistrasifk')
            // ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            // ->where('pa.nosep', $res['sep']->noSep)
            // ->first();

            // $objreqhis = new \Illuminate\Http\Request();
            // $objreqhis['url'] = "RencanaKontrol/nosep/".$res['sep']->noSep;
            // $objreqhis['method'] = "GET";
            // $objreqhis['data'] = null;
            // $cocokHistory =  $this->bridgingBPJSCtrl->bpjsTools($objreqhis, true);
            // $responseHistory = json_decode(json_encode($cocokHistory, false));
            // if (isset($responseHistory->metaData) && $responseHistory->metaData->code == 200) {
            //     $res['sep']->nmprovider = $responseHistory->response->provPerujuk->nmProviderPerujuk;

            // }
            // if(!empty($registrasi)){
            //     $res['sep']->peserta->nohp = $registrasi->nohp;
            //     if($res['sep']->jnsPelayanan == "Rawat Inap") {
            //         $res['sep']->dpjp->nmDPJP = $registrasi->dpjplayan_nama;
            //     }
                $res['sep']->peserta->nohp = $noHP;

               
                if($res['sep']->jnsPelayanan == "Rawat Inap") {
                    $res['sep']->dpjp->nmDPJP = $responseSEP->kontrol->nmDokter;
                }
            // }


        // } else {
        //     abort(403, 'Data SEP tidak ada');
        // }

        $blade = 'report.registrasi.sep';
        $qrcode = base64_encode(QrCode::format('svg')->size(45)->generate($res['sep']->peserta->noKartu));
        $tte = $qrcode;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper([0, 0, 841.89, 400.15]);
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'tte' => $qrcode,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'noHP' => $noHP,
                    'pdf' => true,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper([0, 0, 841.89, 423.15]);
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'tte' => $qrcode,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'noHP' => $noHP,
                    'pdf' => true
                )
            );
            return $pdf;
        }
        $pdf = false;
        return view($blade,compact('profile', 'pageWidth', 'print', 'res','pdf','noHP', 'tte'));
    }
    public function cetakSEPKlaim(Request $r)
    {

        $profile = $this->profile();
        $kdProfile = $this->kdProfile;
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['pdf'] = $r['pdf'];
        // start generate parameter kebutuhan save dokumen

        $registrasi = DB::table("pasiendaftar_t as pd")
        ->select('pa.nosep','pa.*','ps.namapasien','ps.nocm','ps.tgllahir','ps.objectjeniskelaminfk',
        'ap.jenispeserta','ps.nohp', 'ru.namaruangan', 'pg.namalengkap', 'ps.nobpjs', 'pd.tglregistrasi')
        ->leftjoin('pemakaianasuransi_t as pa', 'pd.norec', '=', 'pa.noregistrasifk')
        ->leftjoin('asuransipasien_m as ap', 'ap.id', '=', 'pa.objectasuransipasienfk')
        ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
        ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
        ->where('pd.statusenabled', true)
        ->where('pd.kdprofile', $kdProfile);
        if(isset($r['noregistrasi']) ||  isset($r['norec_pd'])) {
            $registrasi=$registrasi ->whereRaw("(pd.noregistrasi='$r[noregistrasi]' or pd.norec='$r[norec_pd]')");
        }
        if(isset($r['nosep'])) {
            $registrasi=$registrasi ->where('pa.nosep',$r['nosep']);
        }
        $registrasi=$registrasi ->first();
        $res['registrasi'] = $registrasi;
        // end generate parameter kebutuhan save dokumen
        $isrujukinternal = false;
        // dd($registrasi->nosep);
        // if(empty($registrasi) || is_null($registrasi->nosep) || $registrasi->nosep === '') {
            // $r['nosep'] = "9999R9999999V000999";
            // cek vclaim
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = "SEP/" . $registrasi->nosep;
            $objetoRequest['method'] = "GET";
            $objetoRequest['data'] = null;
            $cariSEP =  $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
            $responseSEPVc = json_decode(json_encode($cariSEP, false));

            // dd($responseSEPVc);

            if (isset($responseSEPVc->metaData) && $responseSEPVc->metaData->code == 200) {

                // $res['sep'] = $responseSEPVc->response;
                $responseSEP = $responseSEPVc->response;

                // dd($responseSEP);

                $res['tglCreate'] =  $responseSEP->tglSep;

                $objreqhis = new \Illuminate\Http\Request();
                $objreqhis['url'] = "RencanaKontrol/nosep/".$r['nosep'];
                $objreqhis['method'] = "GET";
                $objreqhis['data'] = null;
                $cocokHistory =  $this->bridgingBPJSCtrl->bpjsTools($objreqhis, true);
                $responseHistory = json_decode(json_encode($cocokHistory, false));
                if (isset($responseHistory->metaData) && $responseHistory->metaData->code == 200) {
                    $namaProvider = $responseHistory->response->provPerujuk->nmProviderPerujuk;

                }
                $noHP = $registrasi->nohp;

            }
            else{
                // dd('Halo');
                $isrujukinternal = $registrasi->isrujukinternal ? true : false;
                $DPJP_LAYAN = $registrasi->dpjplayan_nama;
                $noHP = $registrasi->nohp;
                $responseSEP = [
                    "noSep" => $registrasi->nosep,
                    "tglSep" =>  $registrasi->tglsep,
                    "jnsPelayanan" =>  $registrasi->jnspelayanan == 2 ?'Rawat Jalan':'Rawat Inap',
                    "kelasRawat" =>$registrasi->klsrawathak_nama,
                    "diagnosa" => $registrasi->diagawal_nama,// "Observation for suspected disease or condition, unspecified",
                    "noRujukan" => null,
                    "poli" => strtoupper($registrasi->namaruangan),// "INSTALASI GAWAT DARURAT",
                    "poliEksekutif" =>$registrasi->eksekutif  == false ? "0":"1",
                    "catatan" => $registrasi->catatan  ,
                    "penjamin" =>  null ,
                    "peserta" => [
                            "noKartu" =>  $registrasi->nokartu  ,
                            "nama" =>   $registrasi->namapasien  ,
                            "tglLahir" =>  $registrasi->tgllahir  ,
                            "noMr" =>  $registrasi->nocm  ,
                            "kelamin" => $registrasi->objectjeniskelaminfk == 1?  "L":'P',
                            "jnsPeserta" => $registrasi->jenispeserta,
                            "hakKelas" => $registrasi->klsrawathak_nama,
                            "asuransi" => null
                        ],
                    "klsRawat" => [
                                "klsRawatHak" => $registrasi->klsrawathak_kode,
                                "klsRawatNaik" =>$registrasi->klsrawatnaik_nama,
                                "pembiayaan" =>$registrasi->pembiayaan_nama,
                                "penanggungJawab" => $registrasi->klsrawatnaik_nama,
                            ],
                    "informasi" => null,
                    "kdStatusKecelakaan" =>  $registrasi->lakalantas_kode,
                    "nmstatusKecelakaan" => $registrasi->lakalantas_nama,
                    "dpjp" => [
                                "kdDPJP" => $registrasi->dpjplayan_kode,
                                "nmDPJP" => $registrasi->dpjplayan_nama,
                                ],
                    "kontrol" => [
                                    "noSurat" =>  $registrasi->nosurat,
                                    "kdDokter" =>  $registrasi->kodedpjp,
                                    "nmDokter" =>  $registrasi->namadpjp,
                                ],
                    "lokasiKejadian" => [
                                        "tglKejadian" => $registrasi->tglkejadian,
                                        "kdProp" =>$registrasi->kdpropinsi_kode,
                                        "kdKab" =>$registrasi->kdkabupaten_kode,
                                        "kdKec" =>$registrasi->kdkecamatan_kode,
                                        "ketKejadian" =>$registrasi->keterangan,
                                        "lokasi" => null,
                                    ],
                    "cob" =>$registrasi->cob,
                    "katarak" =>  $registrasi->katarak,
                    "tujuanKunj" => [
                                            "kode" => $registrasi->tujuankun_kode,
                                            "nama" => $registrasi->tujuankun_nama,
                                        ],
                    "flagProcedure" => [
                                            "kode" => $registrasi->flagprocedure_kode,
                                            "nama" =>  $registrasi->flagprocedure_nama,
                                            ],
                    "kdPenunjang" => [
                                                "kode" => $registrasi->kdpenunjang_kode,
                                                "nama" => $registrasi->kdpenunjang_nama,
                                            ],
                    "assestmenPel" => [
                                                    "kode" => $registrasi->assesmentpel_kode,
                                                    "nama" =>$registrasi->assesmentpel_nama
                                                ],
                    "eSEP" => "False"
                    ];
    
                $responseSEP = json_decode(json_encode($responseSEP), FALSE);
                
                $DPJP_LAYAN = $registrasi->dpjplayan_nama;
            }
            $DPJP_LAYAN = $responseSEP->dpjp ? $responseSEP->dpjp->nmDPJP : $registrasi->namalengkap;
            $namaProvider =$registrasi->ppkrujukan_nama;
        // }
        

        $res['tglCreate'] = '';
        $res['tglAyeuna'] = date('Y-m-d H:i:s');

            $res['tglCreate'] =  $responseSEP->tglSep;
            $res['sep'] = $responseSEP;
            $res['sep']->nmprovider =$namaProvider;
            $res['sep']->peserta->kelamin = $res['sep']->peserta->kelamin == "L" ? "Laki-Laki":"Perempuan";
            $res['sep']->peserta->nohp = "-";
            if($res['sep']->jnsPelayanan == "Rawat Inap") {
                $res['sep']->tujuanKunj->nama = "Kunjungan Kontrol (ulangan)";
                $res['sep']->flagProcedure->nama = "";

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = "RencanaKontrol/ListRencanaKontrol/Bulan/".date('m', strtotime($registrasi->tglregistrasi))."/Tahun/".date('Y', strtotime($registrasi->tglregistrasi))."/Nokartu/".$registrasi->nobpjs."/filter/2";
                // dd($objetoRequest['url']);

                $objetoRequest['method'] = "GET";
                $objetoRequest['data'] = null;
                $cariSPRI =  $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
                $responseSPRIVc = json_decode(json_encode($cariSPRI, false));
                // dd($responseSPRIVc->response->list);

                if(count($responseSPRIVc->response->list) > 0){
                    $responseSPRI = $responseSPRIVc->response->list[0];
                    $res['sep']->poli = $responseSPRI->namaPoliTujuan;
                }


                


            } else {
                $res['sep']->tujuanKunj->nama = "Konsultasi dokter (pertama)";
                $res['sep']->flagProcedure->nama = "";
                // if($isrujukinternal){
                //     $res['sep']->tujuanKunj->nama = "Kunjungan rujukan internal";
                // }
                // switch ($res['sep']->tujuanKunj->kode) {
                //     case '0':
                //         $res['sep']->tujuanKunj->nama = "Konsultasi dokter (pertama)";
                //         $res['sep']->flagProcedure->nama = "";
                //         break;

                //     case '2':
                //         $res['sep']->tujuanKunj->nama = "Kunjungan Kontrol (ulangan)";
                //         $res['sep']->flagProcedure->nama = "";
                //         break;
                // }
                if($res['sep']->poli == 'INSTALASI GAWAT DARURAT') {
                    $res['sep']->tujuanKunj->nama = "";
                    $res['sep']->flagProcedure->nama = "";
                }
            }

            // // data pemakaian asuransi
            // $registrasi = DB::table("pemakaianasuransi_t as pa")
            // ->select('pa.*', 'ps.nohp')
            // ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'pa.noregistrasifk')
            // ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            // ->where('pa.nosep', $res['sep']->noSep)
            // ->first();

            // $objreqhis = new \Illuminate\Http\Request();
            // $objreqhis['url'] = "RencanaKontrol/nosep/".$res['sep']->noSep;
            // $objreqhis['method'] = "GET";
            // $objreqhis['data'] = null;
            // $cocokHistory =  $this->bridgingBPJSCtrl->bpjsTools($objreqhis, true);
            // $responseHistory = json_decode(json_encode($cocokHistory, false));
            // if (isset($responseHistory->metaData) && $responseHistory->metaData->code == 200) {
            //     $res['sep']->nmprovider = $responseHistory->response->provPerujuk->nmProviderPerujuk;

            // }
            // if(!empty($registrasi)){
            //     $res['sep']->peserta->nohp = $registrasi->nohp;
            //     if($res['sep']->jnsPelayanan == "Rawat Inap") {
            //         $res['sep']->dpjp->nmDPJP = $registrasi->dpjplayan_nama;
            //     }
                $res['sep']->peserta->nohp = $noHP;
                $res['sep']->poli != "" ? $res['sep']->poli : $registrasi->namaruangan;

               
                if($res['sep']->jnsPelayanan == "Rawat Inap") {
                    $res['sep']->dpjp->nmDPJP = $responseSEP->kontrol->nmDokter;
                }
            // }


        // } else {
        //     abort(403, 'Data SEP tidak ada');
        // }

        $blade = 'report.registrasi.sep-klaim';
        $qrcode = base64_encode(QrCode::format('svg')->size(45)->generate($res['sep']->peserta->noKartu));
        $tte = $qrcode;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper([0, 0, 841.89, 400.15]);
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'tte' => $qrcode,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'DPJP_LAYAN' => $DPJP_LAYAN,
                    'res' => $res,
                    'noHP' => $noHP,
                    'pdf' => true,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper([0, 0, 841.89, 423.15]);
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'tte' => $qrcode,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'DPJP_LAYAN' => $DPJP_LAYAN,
                    'res' => $res,
                    'noHP' => $noHP,
                    'pdf' => true
                )
            );
            return $pdf;
        }
        $pdf = false;
        return view($blade,compact('profile', 'pageWidth', 'print', 'res','pdf','noHP', 'tte'));
    }
    public function savePemakaianAsuransi(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save
            $kdProfile = $this->kdProfile;
            $_AP = $r['asuransipasien'];
            $_PA = $r['pemakaianasuransi'];
            if ($_PA['norec'] == '') {
                // $cekPA =  PemakaianAsuransi::where('nosep', $_PA['nosep'])->first();
                $cekPA =  PemakaianAsuransi::where('noregistrasifk', $_PA['noregistrasifk'])->first();
              
                if(!empty($cekPA)){
                    $_AP['id'] = $cekPA->objectasuransipasienfk;
                    $_PA['norec'] = $cekPA->norec;
                }
              
            }

            if ($_AP['id'] == '') {
                $cek = AsuransiPasien::where('noasuransi', $_AP['noasuransi'])->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)->first();
                    
                if (empty($cek)) {
                    // $id = $this->SEQUENCE_MASTER(new AsuransiPasien,'id',$kdProfile);// $this->Uuid4();
                    $cekId = AsuransiPasien::where('kdprofile', $kdProfile)
                    ->orderBy('id', 'desc')
                    ->first();
                    $max=0;
                    if (!empty($cekId)) {
                        $max = (int) $cekId->id + 1;
                    }
                    $id = $max;
                    $model_AP = new AsuransiPasien();
                    $model_AP->id = $id;
                    $model_AP->norec = $model_AP->generateNewId();
                    $model_AP->kdprofile = $kdProfile;
                    $model_AP->statusenabled = true;
                    
                } else {
                    $model_AP  = $cek;
                }
                // $namaLog = 'Tambah Registrasi ke Ruang '.Ruangan::mine()->where('id',$PD['objectruanganlastfk'])->first()->namaruangan.' ';
            } else {
                $model_AP = AsuransiPasien::where('id', $_AP['id'])->where('kdprofile', $kdProfile)->first();

                // $namaLog = 'Edit Registrasi ke Ruang '.Ruangan::mine()->where('id',$PD['objectruanganlastfk'])->first()->namaruangan.' ';
            }
            $model_AP->kdpenjaminpasien = $_AP['kdpenjaminpasien'];
            $model_AP->namapeserta =  $_AP['namapeserta'];
            $model_AP->noasuransi =   $_AP['noasuransi'];
            $model_AP->noidentitas = $_AP['noidentitas'];
            $model_AP->nocmfk = $_AP['nocmfk'];
            $model_AP->kelompokpasienfk = $_AP['kelompokpasien'];
            $model_AP->tgllahir = $_AP['tgllahir'];
            $model_AP->jenispeserta =  $_AP['jenispeserta'];
            $model_AP->notelpmobile =  $_AP['notelpmobile'];
            $model_AP->kdprovider =  $_PA['kdprovider'];
            $model_AP->nmprovider =  $_PA['nmprovider'];
            if(isset($_AP['objectkelasdijaminfk'])){

                $kelas = Kelas::where('id',$_AP['objectkelasdijaminfk'])->first()->kodebpjs;
                $model_AP->objectkelasdijaminfk =  $kelas;
            }
            $model_AP->save();

            $diagnosafk = null;
            $kelasfk = null;
            if ($_PA['diagawal_kode'] != null) {
                $diag =  Diagnosa::mine()->where('kddiagnosa', $_PA['diagawal_kode'])->first();
                if (!empty($diag)) {
                    $diagnosafk = $diag->id;
                }
            }
            if ($_PA['klsrawathak_kode'] != null) {
                $kelas =  Kelas::mine()->where('kodebpjs', $_PA['klsrawathak_kode'])->first();
                if (!empty($kelas)) {
                    $kelasfk = $kelas->id;
                }
            }
            if ($_PA['norec'] == '') {
                $model_PA = new PemakaianAsuransi();
                $model_PA->norec = $model_PA->generateNewId();
                $model_PA->kdprofile = $kdProfile;
                $model_PA->statusenabled = true;
            } else {
                $model_PA =  PemakaianAsuransi::where('norec', $_PA['norec'])->first();
            }
            $model_PA->noregistrasifk =  $_PA['noregistrasifk'];
            $model_PA->norujukan = $_PA['norujukan'];
            $model_PA->nosep = $_PA['nosep'];
            $model_PA->nokartu = $_PA['nokartu'];
            $model_PA->tglsep =  $_PA['tglsep'];
            $model_PA->ppkpelayanan = $_PA['ppkpelayanan'];
            $model_PA->jnspelayanan =  $_PA['jnspelayanan'];
            $model_PA->klsrawathak_kode = $_PA['klsrawathak_kode'];
            $model_PA->klsrawathak_nama = $_PA['klsrawathak_nama'];
            $model_PA->klsrawatnaik_kode = $_PA['klsrawatnaik_kode'];
            $model_PA->klsrawatnaik_nama = $_PA['klsrawatnaik_nama'];
            $model_PA->pembiayaan_kode = $_PA['pembiayaan_kode'];
            $model_PA->pembiayaan_nama = $_PA['pembiayaan_nama'];
            $model_PA->nomr = $_PA['nomr'];
            $model_PA->asalrujukan = $_PA['asalrujukan'];
            $model_PA->tglrujukan = $_PA['tglrujukan'];
            $model_PA->ppkrujukan = $_PA['ppkrujukan'];
            $model_PA->ppkrujukan_nama = $_PA['ppkrujukan_nama'];
            $model_PA->kdprovider = $_PA['kdprovider'];
            $model_PA->nmprovider = $_PA['nmprovider'];
            $model_PA->catatan = $_PA['catatan'];
            $model_PA->diagawal_kode = $_PA['diagawal_kode'];
            $model_PA->diagawal_nama = $_PA['diagawal_nama'];
            $model_PA->poli_kode = $_PA['poli_kode'];
            $model_PA->poli_nama = $_PA['poli_nama'];
            $model_PA->eksekutif = $_PA['eksekutif'];
            $model_PA->cob = $_PA['cob'];
            $model_PA->katarak = $_PA['katarak'];
            $model_PA->lakalantas_kode = $_PA['lakalantas_kode'];
            $model_PA->lakalantas_nama = $_PA['lakalantas_nama'];
            $model_PA->nolp = $_PA['nolp'];
            $model_PA->tglkejadian = $_PA['tglkejadian'];
            $model_PA->keterangan = $_PA['keterangan'];
            $model_PA->suplesi = $_PA['suplesi'];
            $model_PA->nosepsuplesi = $_PA['nosepsuplesi'];
            $model_PA->kdpropinsi_kode = $_PA['kdpropinsi_kode'];
            $model_PA->kdpropinsi_nama = $_PA['kdpropinsi_nama'];
            $model_PA->kdkabupaten_kode = $_PA['kdkabupaten_kode'];
            $model_PA->kdkabupaten_nama = $_PA['kdkabupaten_nama'];
            $model_PA->kdkecamatan_kode = $_PA['kdkecamatan_kode'];
            $model_PA->kdkecamatan_nama = $_PA['kdkecamatan_nama'];
            $model_PA->tujuankun_kode = $_PA['tujuankun_kode'];
            $model_PA->tujuankun_nama = $_PA['tujuankun_nama'];
            $model_PA->flagprocedure_kode = $_PA['flagprocedure_kode'];
            $model_PA->flagprocedure_nama = $_PA['flagprocedure_nama'];
            $model_PA->kdpenunjang_kode = $_PA['kdpenunjang_kode'];
            $model_PA->kdpenunjang_nama = $_PA['kdpenunjang_nama'];
            $model_PA->assesmentpel_kode = $_PA['assesmentpel_kode'];
            $model_PA->assesmentpel_nama = $_PA['assesmentpel_nama'];
            $model_PA->nosurat = $_PA['nosurat'];
            $model_PA->kodedpjp = $_PA['kodedpjp'];
            $model_PA->namadpjp = $_PA['namadpjp'];
            $model_PA->dpjplayan_kode = $_PA['dpjplayan_kode'];
            $model_PA->dpjplayan_nama = $_PA['dpjplayan_nama'];
            $model_PA->user = $_PA['user'];
            $model_PA->notelp = $_PA['notelp'];
            $model_PA->backdate = $_PA['backdate'];
            $model_PA->kelasfk = $kelasfk;
            $model_PA->diagnosisfk = $diagnosafk;
            $model_PA->objectasuransipasienfk = $model_AP->id;
            $model_PA->isrujukinternal = isset($_PA['isrujukinternal'])?$_PA['isrujukinternal']:null;
            $model_PA->save();

            // if(isset($_PA['klsrawatnaik_kode'])) {
            //     PasienDaftar::where('norec', $_PA['noregistrasifk'])->update([
            //         'isnaikkelas' => true
            //     ]);
            // }
            Pasien::where('id', $_AP['nocmfk'] )->update([
                'noidentitas' => $_AP['noidentitas'],
                'nobpjs' => $_PA['nokartu']
            ]);
            //endregion

            $this->LOGGING(
                'Pemakaian Asuransi',
                $model_PA->norec,
                'pemakaianasuransi',
                $_PA['LOG'] . ' pada Pasien ' .
                    $_AP['namapeserta'] . ' (' .   $_AP['nocm'] . ') - ' . $_AP['noregistrasi']
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
                    "registrasi"  => array(
                        "ap" => $model_AP,
                        "pa" => $model_PA,
                    ),
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
}
